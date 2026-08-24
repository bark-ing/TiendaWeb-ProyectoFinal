<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Al iniciar sesión, fusionamos el carrito de la sesión (invitado) con el de la base de datos
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function (\Illuminate\Auth\Events\Login $event) {
            $sessionCart = session()->get('cart', []);
            $dbCart = $event->user->cart_data ?? [];

            // Fusionar los carritos
            $mergedCart = $sessionCart;
            foreach ($dbCart as $key => $item) {
                if (isset($mergedCart[$key])) {
                    $mergedCart[$key]['cantidad'] = min(
                        $mergedCart[$key]['cantidad'] + $item['cantidad'],
                        99
                    );
                    $mergedCart[$key]['subtotal'] = $mergedCart[$key]['precio'] * $mergedCart[$key]['cantidad'];
                } else {
                    $mergedCart[$key] = $item;
                }
            }

            session()->put('cart', $mergedCart);
            $event->user->update(['cart_data' => $mergedCart]);
        });
    }
}

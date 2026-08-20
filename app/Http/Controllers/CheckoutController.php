<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Services\CartService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $carrito = $this->cartService->obtenerCarrito();

        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'El carrito esta vacio. Agrega productos antes de continuar.');
        }

        $subtotal = $this->cartService->obtenerSubtotal();
        $impuesto = $this->cartService->obtenerImpuesto();
        $envio = $this->cartService->obtenerEnvio();
        $total = $this->cartService->obtenerTotal();

        return view('checkout.index', compact('carrito', 'subtotal', 'impuesto', 'envio', 'total'));
    }

    public function procesar(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:paypal,card',
            'direccion_envio' => 'required|string|min:10',
            'card_number' => 'required_if:metodo_pago,card|string|min:16|max:19',
            'card_expiry' => 'required_if:metodo_pago,card|string|min:5|max:5',
            'card_cvv' => 'required_if:metodo_pago,card|string|min:3|max:4',
        ]);

        $carrito = $this->cartService->obtenerCarrito();

        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'El carrito esta vacio.');
        }

        $subtotal = $this->cartService->obtenerSubtotal();
        $impuesto = $this->cartService->obtenerImpuesto();
        $envio = $this->cartService->obtenerEnvio();
        $total = $subtotal + $impuesto + $envio;

        $pedido = Pedido::create([
            'usuario_id' => auth()->id(),
            'numero_seguimiento' => Pedido::generarNumeroSeguimiento(),
            'estado' => 'pending',
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'costo_envio' => $envio,
            'total' => $total,
            'metodo_pago' => $request->metodo_pago,
            'estado_pago' => 'paid',
            'direccion_envio' => $request->direccion_envio,
        ]);

        foreach ($carrito as $item) {
            PedidoItem::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio'],
                'talla' => $item['talla'],
                'color' => $item['color'],
            ]);
        }

        $this->cartService->vaciar();

        return redirect()->route('pedido.confirmacion', $pedido)->with('success', 'Compra realizada exitosamente.');
    }
}

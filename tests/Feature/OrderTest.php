<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $producto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $categoria = Categoria::create([
            'nombre' => 'Test Categoria',
            'slug' => 'test-categoria'
        ]);

        $this->producto = Producto::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Producto Test Order',
            'slug' => 'producto-test-order',
            'descripcion' => 'Descripcion',
            'precio' => 10000.00,
            'imagen' => 'images/products/test.jpg',
            'stock' => 10,
            'activo' => true
        ]);
    }

    public function test_invitado_es_redireccionado_de_rutas_protegidas()
    {
        $this->get(route('checkout.index'))->assertRedirect(route('login'));
        $this->get(route('pedidos.index'))->assertRedirect(route('login'));
    }

    public function test_usuario_autenticado_puede_ver_checkout()
    {
        // Poner algo en el carrito en sesión primero
        $clave = $this->producto->id . '_def_def';
        $cart = [
            $clave => [
                'clave_carrito' => $clave,
                'producto_id' => $this->producto->id,
                'nombre' => $this->producto->nombre,
                'slug' => $this->producto->slug,
                'imagen' => $this->producto->imagen,
                'precio' => 10000.00,
                'cantidad' => 1,
                'talla' => null,
                'color' => null,
                'subtotal' => 10000.00
            ]
        ];

        $response = $this->actingAs($this->user)
            ->withSession(['cart' => $cart])
            ->get(route('checkout.index'));

        $response->assertStatus(200);
        $response->assertSee('Confirmar Compra');
    }

    public function test_usuario_puede_procesar_pedido_con_tarjeta_simulada()
    {
        $clave = $this->producto->id . '_def_def';
        $cart = [
            $clave => [
                'clave_carrito' => $clave,
                'producto_id' => $this->producto->id,
                'nombre' => $this->producto->nombre,
                'slug' => $this->producto->slug,
                'imagen' => $this->producto->imagen,
                'precio' => 10000.00,
                'cantidad' => 2,
                'talla' => null,
                'color' => null,
                'subtotal' => 20000.00
            ]
        ];

        $response = $this->actingAs($this->user)
            ->withSession(['cart' => $cart])
            ->post(route('checkout.procesar'), [
                'metodo_pago' => 'card',
                'direccion_envio' => 'Calle Falsa 123, San Jose, Costa Rica',
                'card_number' => '1234567890123456',
                'card_expiry' => '12/28',
                'card_cvv' => '123'
            ]);

        $this->assertDatabaseHas('pedidos', [
            'usuario_id' => $this->user->id,
            'metodo_pago' => 'card',
            'estado_pago' => 'paid',
            'direccion_envio' => 'Calle Falsa 123, San Jose, Costa Rica'
        ]);

        $pedido = Pedido::first();
        $response->assertRedirect(route('pedido.confirmacion', $pedido));

        $this->assertDatabaseHas('pedido_items', [
            'pedido_id' => $pedido->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 2
        ]);

        // El carrito debe estar vaciado en la sesion
        $this->assertFalse(session()->has('cart'));
    }

    public function test_usuario_puede_ver_confirmacion_de_su_pedido()
    {
        $pedido = Pedido::create([
            'usuario_id' => $this->user->id,
            'numero_seguimiento' => 'VB-TEST11',
            'estado' => 'pending',
            'subtotal' => 10000,
            'impuesto' => 1300,
            'costo_envio' => 3500,
            'total' => 14800,
            'metodo_pago' => 'card',
            'estado_pago' => 'paid',
            'direccion_envio' => 'San Jose, Costa Rica'
        ]);

        $response = $this->actingAs($this->user)->get(route('pedido.confirmacion', $pedido));
        $response->assertStatus(200);
        $response->assertSee('VB-TEST11');
    }

    public function test_usuario_puede_ver_su_historial_de_pedidos()
    {
        $pedido = Pedido::create([
            'usuario_id' => $this->user->id,
            'numero_seguimiento' => 'VB-TEST12',
            'estado' => 'pending',
            'subtotal' => 10000,
            'impuesto' => 1300,
            'costo_envio' => 3500,
            'total' => 14800,
            'metodo_pago' => 'card',
            'estado_pago' => 'paid',
            'direccion_envio' => 'San Jose, Costa Rica'
        ]);

        $response = $this->actingAs($this->user)->get(route('pedidos.index'));
        $response->assertStatus(200);
        $response->assertSee('VB-TEST12');
    }

    public function test_usuario_no_puede_ver_pedido_de_otro_usuario()
    {
        $otroUsuario = User::factory()->create();
        $pedido = Pedido::create([
            'usuario_id' => $otroUsuario->id,
            'numero_seguimiento' => 'VB-TEST13',
            'estado' => 'pending',
            'subtotal' => 10000,
            'impuesto' => 1300,
            'costo_envio' => 3500,
            'total' => 14800,
            'metodo_pago' => 'card',
            'estado_pago' => 'paid',
            'direccion_envio' => 'San Jose, Costa Rica'
        ]);

        $response = $this->actingAs($this->user)->get(route('pedido.ver', $pedido));
        $response->assertStatus(403);
    }
}

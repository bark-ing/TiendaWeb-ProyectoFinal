<?php

namespace Tests\Unit\Services;

use App\Models\Categoria;
use App\Models\Producto;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    private $cartService;
    private $producto1;
    private $producto2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cartService = new CartService();

        $categoria = Categoria::create([
            'nombre' => 'Test Categoria',
            'slug' => 'test-categoria'
        ]);

        $this->producto1 = Producto::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Producto Caro',
            'slug' => 'producto-caro',
            'descripcion' => 'Desc',
            'precio' => 60000.00,
            'imagen' => 'test.jpg',
            'stock' => 10,
            'activo' => true
        ]);

        $this->producto2 = Producto::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Producto Barato',
            'slug' => 'producto-barato',
            'descripcion' => 'Desc',
            'precio' => 10000.00,
            'imagen' => 'test.jpg',
            'stock' => 3,
            'activo' => true
        ]);

        // Asegurarse de que el carrito esté vacío al iniciar cada test
        $this->cartService->vaciar();
    }

    public function test_carrito_inicia_vacio()
    {
        $this->assertEmpty($this->cartService->obtenerCarrito());
        $this->assertEquals(0, $this->cartService->obtenerSubtotal());
        $this->assertEquals(0, $this->cartService->obtenerTotal());
    }

    public function test_agregar_y_obtener_carrito()
    {
        $this->cartService->agregar($this->producto2->id, 2, 'M', 'Rojo');

        $carrito = $this->cartService->obtenerCarrito();
        $this->assertCount(1, $carrito);

        $clave = $this->producto2->id . '_M_Rojo';
        $this->assertArrayHasKey($clave, $carrito);
        $this->assertEquals(2, $carrito[$clave]['cantidad']);
        $this->assertEquals(20000.00, $carrito[$clave]['subtotal']);
    }

    public function test_agregar_no_supera_el_stock_disponible()
    {
        // El producto 2 tiene stock de 3
        $this->cartService->agregar($this->producto2->id, 5);

        $carrito = $this->cartService->obtenerCarrito();
        $clave = $this->producto2->id . '_def_def';

        $this->assertEquals(3, $carrito[$clave]['cantidad']); // Se limita al stock de 3
    }

    public function test_calculo_subtotal_impuestos_y_envio()
    {
        // Agregar producto barato (10,000)
        // Subtotal = 10,000. Envío = 3,500 (ya que subtotal <= 50,000). IVA (13%) = 1,300
        // Total = 10,000 + 1,300 + 3,500 = 14,800
        $this->cartService->agregar($this->producto2->id, 1);

        $this->assertEquals(10000.00, $this->cartService->obtenerSubtotal());
        $this->assertEquals(1300.00, $this->cartService->obtenerImpuesto());
        $this->assertEquals(3500.00, $this->cartService->obtenerEnvio());
        $this->assertEquals(14800.00, $this->cartService->obtenerTotal());

        // Vaciar
        $this->cartService->vaciar();

        // Agregar producto caro (60,000)
        // Subtotal = 60,000. Envío = 0 (gratis por superar 50,000). IVA (13%) = 7,800
        // Total = 60,000 + 7,800 + 0 = 67,800
        $this->cartService->agregar($this->producto1->id, 1);

        $this->assertEquals(60000.00, $this->cartService->obtenerSubtotal());
        $this->assertEquals(7800.00, $this->cartService->obtenerImpuesto());
        $this->assertEquals(0.00, $this->cartService->obtenerEnvio());
        $this->assertEquals(67800.00, $this->cartService->obtenerTotal());
    }

    public function test_obtener_cantidad_total_de_items()
    {
        $this->cartService->agregar($this->producto1->id, 1);
        $this->cartService->agregar($this->producto2->id, 2);

        $this->assertEquals(3, $this->cartService->obtenerCantidad());
    }

    public function test_eliminar_item_del_carrito()
    {
        $this->cartService->agregar($this->producto1->id, 1);
        $clave = $this->producto1->id . '_def_def';

        $this->assertCount(1, $this->cartService->obtenerCarrito());

        $this->cartService->eliminar($clave);

        $this->assertCount(0, $this->cartService->obtenerCarrito());
    }
}

<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private $producto;

    protected function setUp(): void
    {
        parent::setUp();

        $categoria = Categoria::create([
            'nombre' => 'Test Categoria',
            'slug' => 'test-categoria'
        ]);

        $this->producto = Producto::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Producto Test Cart',
            'slug' => 'producto-test-cart',
            'descripcion' => 'Descripcion',
            'precio' => 10000.00,
            'imagen' => 'images/products/test.jpg',
            'stock' => 10,
            'activo' => true
        ]);
    }

    public function test_carrito_index_carga_correctamente()
    {
        $response = $this->get(route('carrito.index'));

        $response->assertStatus(200);
        $response->assertSee('Carrito');
    }

    public function test_agregar_producto_al_carrito()
    {
        $response = $this->post(route('carrito.agregar'), [
            'producto_id' => $this->producto->id,
            'cantidad' => 2,
            'talla' => 'M',
            'color' => 'Negro'
        ]);

        $response->assertRedirect(route('carrito.index'));
        $response->assertSessionHas('success');

        $this->assertEquals(1, count(session('cart')));
        
        $clave = $this->producto->id . '_M_Negro';
        $this->assertArrayHasKey($clave, session('cart'));
        $this->assertEquals(2, session('cart')[$clave]['cantidad']);
    }

    public function test_actualizar_cantidad_producto_en_carrito()
    {
        // Agregar primero
        $this->post(route('carrito.agregar'), [
            'producto_id' => $this->producto->id,
            'cantidad' => 2,
            'talla' => 'M',
            'color' => 'Negro'
        ]);

        $clave = $this->producto->id . '_M_Negro';

        // Actualizar
        $response = $this->put(route('carrito.actualizar', $clave), [
            'cantidad' => 5
        ]);

        $response->assertRedirect(route('carrito.index'));
        $this->assertEquals(5, session('cart')[$clave]['cantidad']);
    }

    public function test_eliminar_producto_del_carrito()
    {
        // Agregar primero
        $this->post(route('carrito.agregar'), [
            'producto_id' => $this->producto->id,
            'cantidad' => 2,
            'talla' => 'M',
            'color' => 'Negro'
        ]);

        $clave = $this->producto->id . '_M_Negro';

        // Eliminar
        $response = $this->delete(route('carrito.eliminar', $clave));

        $response->assertRedirect(route('carrito.index'));
        $this->assertArrayNotHasKey($clave, session('cart'));
    }

    public function test_vaciar_carrito()
    {
        // Agregar
        $this->post(route('carrito.agregar'), [
            'producto_id' => $this->producto->id,
            'cantidad' => 2,
            'talla' => 'M',
            'color' => 'Negro'
        ]);

        // Vaciar
        $response = $this->delete(route('carrito.vaciar'));

        $response->assertRedirect(route('carrito.index'));
        $this->assertFalse(session()->has('cart'));
    }
}

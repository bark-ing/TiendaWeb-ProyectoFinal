<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalogo_muestra_productos_activos()
    {
        $categoria = Categoria::create([
            'nombre' => 'Test Categoria',
            'slug' => 'test-categoria',
            'descripcion' => 'Descripcion test'
        ]);

        $productoActivo = Producto::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Producto Activo',
            'slug' => 'producto-activo',
            'descripcion' => 'Descripcion',
            'precio' => 1000,
            'imagen' => 'images/products/test.jpg',
            'stock' => 10,
            'activo' => true
        ]);

        $productoInactivo = Producto::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Producto Inactivo',
            'slug' => 'producto-inactivo',
            'descripcion' => 'Descripcion',
            'precio' => 2000,
            'imagen' => 'images/products/test.jpg',
            'stock' => 5,
            'activo' => false
        ]);

        $response = $this->get(route('productos.index'));

        $response->assertStatus(200);
        $response->assertSee('Producto Activo');
        $response->assertDontSee('Producto Inactivo');
    }

    public function test_se_puede_ver_detalle_de_un_producto()
    {
        $categoria = Categoria::create([
            'nombre' => 'Test Categoria',
            'slug' => 'test-categoria'
        ]);

        $producto = Producto::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Producto Test Detalle',
            'slug' => 'producto-test-detalle',
            'descripcion' => 'Descripcion detallada del producto',
            'precio' => 1500,
            'imagen' => 'images/products/test.jpg',
            'stock' => 10,
            'activo' => true
        ]);

        $response = $this->get(route('productos.ver', $producto->slug));

        $response->assertStatus(200);
        $response->assertSee('Producto Test Detalle');
        $response->assertSee('Descripcion detallada del producto');
        $response->assertCookie('recently_viewed');
    }

    public function test_se_pueden_filtrar_productos_por_categoria()
    {
        $cat1 = Categoria::create(['nombre' => 'Cat Uno', 'slug' => 'cat-uno']);
        $cat2 = Categoria::create(['nombre' => 'Cat Dos', 'slug' => 'cat-dos']);

        $prod1 = Producto::create([
            'categoria_id' => $cat1->id,
            'nombre' => 'Prod de Cat Uno',
            'slug' => 'prod-uno',
            'descripcion' => 'Desc',
            'precio' => 1000,
            'imagen' => 'test.jpg',
            'stock' => 5,
            'activo' => true
        ]);

        $prod2 = Producto::create([
            'categoria_id' => $cat2->id,
            'nombre' => 'Prod de Cat Dos',
            'slug' => 'prod-dos',
            'descripcion' => 'Desc',
            'precio' => 2000,
            'imagen' => 'test.jpg',
            'stock' => 5,
            'activo' => true
        ]);

        $response = $this->get(route('productos.categoria', $cat1->slug));

        $response->assertStatus(200);
        $response->assertSee('Prod de Cat Uno');
        $response->assertDontSee('Prod de Cat Dos');
    }

    public function test_se_pueden_buscar_productos_por_nombre()
    {
        $cat = Categoria::create(['nombre' => 'Cat', 'slug' => 'cat']);

        $prod1 = Producto::create([
            'categoria_id' => $cat->id,
            'nombre' => 'Camisa de algodon',
            'slug' => 'camisa-algodon',
            'descripcion' => 'Desc',
            'precio' => 1000,
            'imagen' => 'test.jpg',
            'stock' => 5,
            'activo' => true
        ]);

        $prod2 = Producto::create([
            'categoria_id' => $cat->id,
            'nombre' => 'Pantalon Mezclilla',
            'slug' => 'pantalon-mezclilla',
            'descripcion' => 'Desc',
            'precio' => 2000,
            'imagen' => 'test.jpg',
            'stock' => 5,
            'activo' => true
        ]);

        $response = $this->get(route('productos.buscar', ['q' => 'Camisa']));

        $response->assertStatus(200);
        $response->assertSee('Camisa de algodon');
        $response->assertDontSee('Pantalon Mezclilla');
    }
}

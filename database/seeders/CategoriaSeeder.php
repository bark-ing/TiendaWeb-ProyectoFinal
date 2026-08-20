<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Mujer', 'slug' => 'mujer', 'descripcion' => 'Ropa y accesorios para mujer', 'imagen' => 'images/categories/mujer.jpg'],
            ['nombre' => 'Hombre', 'slug' => 'hombre', 'descripcion' => 'Ropa y accesorios para hombre', 'imagen' => 'images/categories/hombre.jpg'],
            ['nombre' => 'Accesorios', 'slug' => 'accesorios', 'descripcion' => 'Bolsos, relojes y mas', 'imagen' => 'images/categories/accesorios.jpg'],
            ['nombre' => 'Calzado', 'slug' => 'calzado', 'descripcion' => 'Zapatos, zapatillas y tenis', 'imagen' => 'images/categories/calzado.jpg'],
            ['nombre' => 'Deportiva', 'slug' => 'deportiva', 'descripcion' => 'Ropa y calzado deportivo', 'imagen' => 'images/categories/deportiva.jpg'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}

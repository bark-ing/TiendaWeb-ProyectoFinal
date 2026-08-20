<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Mujer', 'slug' => 'mujer', 'description' => 'Ropa y accesorios para mujer', 'image' => 'images/categories/mujer.jpg'],
            ['name' => 'Hombre', 'slug' => 'hombre', 'description' => 'Ropa y accesorios para hombre', 'image' => 'images/categories/hombre.jpg'],
            ['name' => 'Accesorios', 'slug' => 'accesorios', 'description' => 'Bolsos, relojes y mas', 'image' => 'images/categories/accesorios.jpg'],
            ['name' => 'Calzado', 'slug' => 'calzado', 'description' => 'Zapatos, zapatillas y tenis', 'image' => 'images/categories/calzado.jpg'],
            ['name' => 'Deportiva', 'slug' => 'deportiva', 'description' => 'Ropa y calzado deportivo', 'image' => 'images/categories/deportiva.jpg'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

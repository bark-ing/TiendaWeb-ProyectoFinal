<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category' => 'Mujer',
                'name' => 'Vestido Floral Primavera',
                'slug' => 'vestido-floral-primavera',
                'description' => 'Vestido largo con estampa floral perfecto para la temporada. Confeccionado en tela ligera y transpirable, ideal para ocasiones casuales y formales. Disponible en multiples talles y colores.',
                'price' => 25000.00,
                'image' => 'images/products/imagen1.jpg',
                'stock' => 15,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Negro', 'Blanco', 'Rojo'],
            ],
            [
                'category' => 'Mujer',
                'name' => 'Blusa Seda Elegante',
                'slug' => 'blusa-seda-elegante',
                'description' => 'Blusa de seda sintetica con corte moderno y elegante. Perfecta para el trabajo o una salida especial. Tela suave al tacto con acabados de primera calidad.',
                'price' => 18500.00,
                'image' => 'images/products/imagen2.jpg',
                'stock' => 20,
                'sizes' => ['XS', 'S', 'M', 'L'],
                'colors' => ['Blanco', 'Negro', 'Azul'],
            ],
            [
                'category' => 'Hombre',
                'name' => 'Camisa Formal Hombre',
                'slug' => 'camisa-formal-hombre',
                'description' => 'Camisa formal de manga larga con corte slim fit. Confeccionada en algodon de alta calidad con acabados premium. Ideal para reuniones de negocios y eventos formales.',
                'price' => 22000.00,
                'image' => 'images/products/imagen3.jpg',
                'stock' => 25,
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Blanco', 'Azul', 'Gris'],
            ],
            [
                'category' => 'Hombre',
                'name' => 'Jeans Slim Fit',
                'slug' => 'jeans-slim-fit',
                'description' => 'Jeans de corte slim fit con estilo moderno. Fabricado con denim de alta resistencia y comodidad. Disponible en varios colores y talles.',
                'price' => 28000.00,
                'image' => 'images/products/imagen4.jpg',
                'stock' => 30,
                'sizes' => ['28', '30', '32', '34', '36'],
                'colors' => ['Azul', 'Negro', 'Gris'],
            ],
            [
                'category' => 'Accesorios',
                'name' => 'Bolso Cuero Artesanal',
                'slug' => 'bolso-cuero-artesanal',
                'description' => 'Bolso elaborado artesanalmente en cuero genuino. Diseno unico con multiples compartimentos. Incluye correa ajustable y cierre magnetico de seguridad.',
                'price' => 35000.00,
                'image' => 'images/products/imagen5.jpg',
                'stock' => 10,
                'sizes' => ['Unico'],
                'colors' => ['Marron', 'Negro', 'Caramelo'],
            ],
            [
                'category' => 'Accesorios',
                'name' => 'Reloj Clasico Dorado',
                'slug' => 'reloj-clasico-dorado',
                'description' => 'Reloj de pulsera con acabado dorado y correa de acero inoxidable. Movimiento de cuarzo de alta precision. Resistente al agua hasta 30 metros.',
                'price' => 45000.00,
                'image' => 'images/products/imagen6.jpg',
                'stock' => 8,
                'sizes' => ['Unico'],
                'colors' => ['Dorado', 'Plata'],
            ],
            [
                'category' => 'Calzado',
                'name' => 'Zapatillas Urbanas',
                'slug' => 'zapatillas-urbanas',
                'description' => 'Zapatillas de estilo urbano con suela de goma antideslizante. Diseño moderno y comodo para el dia a dia. Material resistente y duradero.',
                'price' => 32000.00,
                'image' => 'images/products/imagen7.jpg',
                'stock' => 18,
                'sizes' => ['38', '39', '40', '41', '42', '43'],
                'colors' => ['Negro', 'Blanco', 'Gris'],
            ],
            [
                'category' => 'Deportiva',
                'name' => 'Tenis Deportivos Pro',
                'slug' => 'tenis-deportivos-pro',
                'description' => 'Tenis deportivos de alto rendimiento con amortiguacion avanzada. Ideales para correr, entrenar o actividades fisnicas. Material transpirable y ligero.',
                'price' => 40000.00,
                'image' => 'images/products/imagen8.jpg',
                'stock' => 22,
                'sizes' => ['38', '39', '40', '41', '42', '43', '44'],
                'colors' => ['Negro/Rojo', 'Blanco/Azul', 'Gris/Verde'],
            ],
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->first();
            if ($category) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'slug' => $productData['slug'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'image' => $productData['image'],
                    'stock' => $productData['stock'],
                    'sizes' => $productData['sizes'],
                    'colors' => $productData['colors'],
                ]);
            }
        }
    }
}

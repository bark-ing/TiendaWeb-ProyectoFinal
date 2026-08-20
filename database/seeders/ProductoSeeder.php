<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'categoria' => 'Mujer',
                'nombre' => 'Vestido Floral Primavera',
                'slug' => 'vestido-floral-primavera',
                'descripcion' => 'Vestido largo con estampa floral perfecto para la temporada. Confeccionado en tela ligera y transpirable, ideal para ocasiones casuales y formales. Disponible en multiples talles y colores.',
                'precio' => 25000.00,
                'imagen' => 'images/products/imagen1.jpg',
                'stock' => 15,
                'tallas' => ['S', 'M', 'L', 'XL'],
                'colores' => ['Negro', 'Blanco', 'Rojo'],
            ],
            [
                'categoria' => 'Mujer',
                'nombre' => 'Blusa Seda Elegante',
                'slug' => 'blusa-seda-elegante',
                'descripcion' => 'Blusa de seda sintetica con corte moderno y elegante. Perfecta para el trabajo o una salida especial. Tela suave al tacto con acabados de primera calidad.',
                'precio' => 18500.00,
                'imagen' => 'images/products/imagen2.jpg',
                'stock' => 20,
                'tallas' => ['XS', 'S', 'M', 'L'],
                'colores' => ['Blanco', 'Negro', 'Azul'],
            ],
            [
                'categoria' => 'Hombre',
                'nombre' => 'Camisa Formal Hombre',
                'slug' => 'camisa-formal-hombre',
                'descripcion' => 'Camisa formal de manga larga con corte slim fit. Confeccionada en algodon de alta calidad con acabados premium. Ideal para reuniones de negocios y eventos formales.',
                'precio' => 22000.00,
                'imagen' => 'images/products/imagen3.jpg',
                'stock' => 25,
                'tallas' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colores' => ['Blanco', 'Azul', 'Gris'],
            ],
            [
                'categoria' => 'Hombre',
                'nombre' => 'Jeans Slim Fit',
                'slug' => 'jeans-slim-fit',
                'descripcion' => 'Jeans de corte slim fit con estilo moderno. Fabricado con denim de alta resistencia y comodidad. Disponible en varios colores y talles.',
                'precio' => 28000.00,
                'imagen' => 'images/products/imagen4.jpg',
                'stock' => 30,
                'tallas' => ['28', '30', '32', '34', '36'],
                'colores' => ['Azul', 'Negro', 'Gris'],
            ],
            [
                'categoria' => 'Accesorios',
                'nombre' => 'Bolso Cuero Artesanal',
                'slug' => 'bolso-cuero-artesanal',
                'descripcion' => 'Bolso elaborado artesanalmente en cuero genuino. Diseno unico con multiples compartimentos. Incluye correa ajustable y cierre magnetico de seguridad.',
                'precio' => 35000.00,
                'imagen' => 'images/products/imagen5.jpg',
                'stock' => 10,
                'tallas' => ['Unico'],
                'colores' => ['Marron', 'Negro', 'Caramelo'],
            ],
            [
                'categoria' => 'Accesorios',
                'nombre' => 'Reloj Clasico Dorado',
                'slug' => 'reloj-clasico-dorado',
                'descripcion' => 'Reloj de pulsera con acabado dorado y correa de acero inoxidable. Movimiento de cuarzo de alta precision. Resistente al agua hasta 30 metros.',
                'precio' => 45000.00,
                'imagen' => 'images/products/imagen6.jpg',
                'stock' => 8,
                'tallas' => ['Unico'],
                'colores' => ['Dorado', 'Plata'],
            ],
            [
                'categoria' => 'Calzado',
                'nombre' => 'Zapatillas Urbanas',
                'slug' => 'zapatillas-urbanas',
                'descripcion' => 'Zapatillas de estilo urbano con suela de goma antideslizante. Diseno moderno y comodo para el dia a dia. Material resistente y duradero.',
                'precio' => 32000.00,
                'imagen' => 'images/products/imagen7.jpg',
                'stock' => 18,
                'tallas' => ['38', '39', '40', '41', '42', '43'],
                'colores' => ['Negro', 'Blanco', 'Gris'],
            ],
            [
                'categoria' => 'Deportiva',
                'nombre' => 'Tenis Deportivos Pro',
                'slug' => 'tenis-deportivos-pro',
                'descripcion' => 'Tenis deportivos de alto rendimiento con amortiguacion avanzada. Ideales para correr, entrenar o actividades fisnicas. Material transpirable y ligero.',
                'precio' => 40000.00,
                'imagen' => 'images/products/imagen8.jpg',
                'stock' => 22,
                'tallas' => ['38', '39', '40', '41', '42', '43', '44'],
                'colores' => ['Negro/Rojo', 'Blanco/Azul', 'Gris/Verde'],
            ],
        ];

        foreach ($productos as $productoData) {
            $categoria = Categoria::where('nombre', $productoData['categoria'])->first();
            if ($categoria) {
                Producto::create([
                    'categoria_id' => $categoria->id,
                    'nombre' => $productoData['nombre'],
                    'slug' => $productoData['slug'],
                    'descripcion' => $productoData['descripcion'],
                    'precio' => $productoData['precio'],
                    'imagen' => $productoData['imagen'],
                    'stock' => $productoData['stock'],
                    'tallas' => $productoData['tallas'],
                    'colores' => $productoData['colores'],
                ]);
            }
        }
    }
}

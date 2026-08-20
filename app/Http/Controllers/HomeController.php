<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $productos = Producto::where('activo', true)->latest()->take(8)->get();
        $categorias = Categoria::all();

        $idsRecientes = json_decode(request()->cookie('recently_viewed', '[]'), true) ?? [];
        $productosRecientes = count($idsRecientes) > 0
            ? Producto::whereIn('id', $idsRecientes)->get()
            : collect();

        return view('home.index', compact('productos', 'categorias', 'productosRecientes'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::where('activo', true);

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }

        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        $productos = $query->paginate(12);
        $categorias = Categoria::all();

        return view('products.index', compact('productos', 'categorias'));
    }

    public function ver($slug)
    {
        $producto = Producto::where('slug', $slug)->firstOrFail();

        $id = (int) $producto->id;
        $idsVistos = json_decode(request()->cookie('recently_viewed', '[]'), true) ?? [];
        $idsVistos = array_map('intval', $idsVistos);
        $idsVistos = array_values(array_diff($idsVistos, [$id]));
        array_unshift($idsVistos, $id);
        $idsVistos = array_slice($idsVistos, 0, 8);

        $cookie = cookie('recently_viewed', json_encode($idsVistos), 45000);

        return response()->view('products.show', compact('producto'))->cookie($cookie);
    }

    public function categoria($slug)
    {
        $categoria = Categoria::where('slug', $slug)->firstOrFail();
        $productos = Producto::where('categoria_id', $categoria->id)
            ->where('activo', true)
            ->paginate(12);
        $categorias = Categoria::all();

        return view('products.index', compact('productos', 'categorias'));
    }

    public function buscar(Request $request)
    {
        $busqueda = $request->input('q');
        $productos = Producto::where('activo', true)
            ->where('nombre', 'like', "%{$busqueda}%")
            ->paginate(12);
        $categorias = Categoria::all();

        return view('products.index', compact('productos', 'categorias'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        if ($request->filled('categoria')) {
            $query->where('category_id', $request->categoria);
        }

        if ($request->filled('precio_min')) {
            $query->where('price', '>=', $request->precio_min);
        }

        if ($request->filled('precio_max')) {
            $query->where('price', '<=', $request->precio_max);
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $id = (int) $product->id;
        $idsVistos = json_decode(request()->cookie('recently_viewed', '[]'), true) ?? [];
        $idsVistos = array_map('intval', $idsVistos);
        $idsVistos = array_values(array_diff($idsVistos, [$id]));
        array_unshift($idsVistos, $id);
        $idsVistos = array_slice($idsVistos, 0, 8);

        $cookie = cookie('recently_viewed', json_encode($idsVistos), 45000);

        return response()->view('products.show', compact('product'))->cookie($cookie);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->paginate(12);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $products = Product::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->paginate(12);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }
}

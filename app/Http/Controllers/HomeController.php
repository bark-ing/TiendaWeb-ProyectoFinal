<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->latest()->take(8)->get();
        $categories = Category::all();

        $recentIds = json_decode(request()->cookie('recently_viewed', '[]'), true) ?? [];
        $recentProducts = count($recentIds) > 0
            ? Product::whereIn('id', $recentIds)->get()
            : collect();

        return view('home.index', compact('products', 'categories', 'recentProducts'));
    }
}

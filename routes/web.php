<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/productos/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categorias/{slug}', [ProductController::class, 'category'])->name('products.category');
Route::get('/buscar', [ProductController::class, 'search'])->name('products.search');

// Carrito de compras (Etapa 2)
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar', [CartController::class, 'add'])->name('cart.add');
Route::put('/carrito/actualizar/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/eliminar/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/carrito/vaciar', [CartController::class, 'clear'])->name('cart.clear');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

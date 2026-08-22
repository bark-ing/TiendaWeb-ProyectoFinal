<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('inicio');

Route::get('/productos', [ProductController::class, 'index'])->name('productos.index');
Route::get('/productos/{slug}', [ProductController::class, 'ver'])->name('productos.ver');
Route::get('/categorias/{slug}', [ProductController::class, 'categoria'])->name('productos.categoria');
Route::get('/buscar', [ProductController::class, 'buscar'])->name('productos.buscar');

// Carrito de compras (Etapa 2)
Route::get('/carrito', [CartController::class, 'index'])->name('carrito.index');
Route::post('/carrito/agregar', [CartController::class, 'agregar'])->name('carrito.agregar');
Route::put('/carrito/actualizar/{id}', [CartController::class, 'actualizar'])->name('carrito.actualizar');
Route::delete('/carrito/eliminar/{id}', [CartController::class, 'eliminar'])->name('carrito.eliminar');
Route::delete('/carrito/vaciar', [CartController::class, 'vaciar'])->name('carrito.vaciar');

// Checkout y Pedidos (Etapa 3)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/procesar', [CheckoutController::class, 'procesar'])->name('checkout.procesar');
    Route::get('/pedido/confirmacion/{pedido}', [OrderController::class, 'confirmacion'])->name('pedido.confirmacion');
    Route::get('/mis-pedidos', [OrderController::class, 'index'])->name('pedidos.index');
    Route::get('/pedido/{pedido}', [OrderController::class, 'ver'])->name('pedido.ver');

    // Reportes PDF (Etapa 4)
    Route::get('/pedido/{pedido}/factura', [OrderController::class, 'factura'])->name('pedido.factura');
    Route::get('/reportes/ventas', [OrderController::class, 'reporteVentas'])->name('reportes.ventas');
    Route::get('/reportes/cliente/{usuario?}', [OrderController::class, 'reporteCliente'])->name('reportes.cliente');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function confirmacion(Pedido $pedido)
    {
        if ($pedido->usuario_id !== auth()->id()) {
            abort(403);
        }

        $pedido->load('items.producto');

        return view('checkout.confirmation', compact('pedido'));
    }

    public function index()
    {
        $pedidos = Pedido::where('usuario_id', auth()->id())
            ->with('items.producto')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('pedidos'));
    }

    public function ver(Pedido $pedido)
    {
        if ($pedido->usuario_id !== auth()->id()) {
            abort(403);
        }

        $pedido->load('items.producto');

        return view('orders.show', compact('pedido'));
    }
}

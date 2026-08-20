<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $subtotal = $this->cartService->getSubtotal();
        $tax = $this->cartService->getTax();
        $shipping = $this->cartService->getShipping();
        $total = $this->cartService->getTotal();

        return view('cart.index', compact('cart', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $this->cartService->add(
            $request->product_id,
            $request->quantity,
            $request->size,
            $request->color
        );

        return redirect()->route('cart.index')->with('success', '¡Producto agregado al carrito exitosamente!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cartService->update($id, $request->quantity);

        return redirect()->route('cart.index')->with('success', 'Carrito actualizado correctamente.');
    }

    public function remove($id)
    {
        $this->cartService->remove($id);

        return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function clear()
    {
        $this->cartService->clear();

        return redirect()->route('cart.index')->with('success', 'El carrito ha sido vaciado.');
    }
}

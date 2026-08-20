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
        $carrito = $this->cartService->obtenerCarrito();
        $subtotal = $this->cartService->obtenerSubtotal();
        $impuesto = $this->cartService->obtenerImpuesto();
        $envio = $this->cartService->obtenerEnvio();
        $total = $this->cartService->obtenerTotal();

        return view('cart.index', compact('carrito', 'subtotal', 'impuesto', 'envio', 'total'));
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'talla' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $this->cartService->agregar(
            $request->producto_id,
            $request->cantidad,
            $request->talla,
            $request->color
        );

        return redirect()->route('carrito.index')->with('success', '¡Producto agregado al carrito exitosamente!');
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $this->cartService->actualizar($id, $request->cantidad);

        return redirect()->route('carrito.index')->with('success', 'Carrito actualizado correctamente.');
    }

    public function eliminar($id)
    {
        $this->cartService->eliminar($id);

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function vaciar()
    {
        $this->cartService->vaciar();

        return redirect()->route('carrito.index')->with('success', 'El carrito ha sido vaciado.');
    }
}

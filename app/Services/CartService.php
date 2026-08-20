<?php

namespace App\Services;

use App\Models\Producto;

class CartService
{
    public function obtenerCarrito()
    {
        return session()->get('cart', []);
    }

    public function agregar($productoId, $cantidad = 1, $talla = null, $color = null)
    {
        $producto = Producto::findOrFail($productoId);
        $carrito = $this->obtenerCarrito();

        $claveCarrito = $productoId . '_' . ($talla ?? 'def') . '_' . ($color ?? 'def');

        if (isset($carrito[$claveCarrito])) {
            $carrito[$claveCarrito]['cantidad'] += (int) $cantidad;
        } else {
            $carrito[$claveCarrito] = [
                'clave_carrito' => $claveCarrito,
                'producto_id' => $producto->id,
                'nombre' => $producto->nombre,
                'slug' => $producto->slug,
                'imagen' => $producto->imagen,
                'precio' => (float) $producto->precio,
                'cantidad' => (int) $cantidad,
                'talla' => $talla,
                'color' => $color,
                'subtotal' => 0,
            ];
        }

        if ($producto->stock > 0 && $carrito[$claveCarrito]['cantidad'] > $producto->stock) {
            $carrito[$claveCarrito]['cantidad'] = $producto->stock;
        }

        $carrito[$claveCarrito]['subtotal'] = $carrito[$claveCarrito]['precio'] * $carrito[$claveCarrito]['cantidad'];

        session()->put('cart', $carrito);

        return $carrito;
    }

    public function actualizar($claveCarrito, $cantidad)
    {
        $carrito = $this->obtenerCarrito();

        if (isset($carrito[$claveCarrito])) {
            $producto = Producto::find($carrito[$claveCarrito]['producto_id']);
            $maxStock = $producto ? $producto->stock : 99;

            $cant = max(1, min((int) $cantidad, $maxStock));
            $carrito[$claveCarrito]['cantidad'] = $cant;
            $carrito[$claveCarrito]['subtotal'] = $carrito[$claveCarrito]['precio'] * $cant;

            session()->put('cart', $carrito);
        }

        return $carrito;
    }

    public function eliminar($claveCarrito)
    {
        $carrito = $this->obtenerCarrito();

        if (isset($carrito[$claveCarrito])) {
            unset($carrito[$claveCarrito]);
            session()->put('cart', $carrito);
        }

        return $carrito;
    }

    public function vaciar()
    {
        session()->forget('cart');
    }

    public function obtenerSubtotal()
    {
        $carrito = $this->obtenerCarrito();
        $subtotal = 0;

        foreach ($carrito as $item) {
            $subtotal += $item['precio'] * $item['cantidad'];
        }

        return $subtotal;
    }

    public function obtenerImpuesto()
    {
        return $this->obtenerSubtotal() * 0.13;
    }

    public function obtenerEnvio()
    {
        $subtotal = $this->obtenerSubtotal();
        if ($subtotal == 0) {
            return 0;
        }

        return $subtotal > 50000 ? 0 : 3500;
    }

    public function obtenerTotal()
    {
        return $this->obtenerSubtotal() + $this->obtenerImpuesto() + $this->obtenerEnvio();
    }

    public function obtenerCantidad()
    {
        $carrito = $this->obtenerCarrito();
        $cantidad = 0;

        foreach ($carrito as $item) {
            $cantidad += $item['cantidad'];
        }

        return $cantidad;
    }
}

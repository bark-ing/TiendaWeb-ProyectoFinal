<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    public function getCart()
    {
        return session()->get('cart', []);
    }

    public function add($productId, $quantity = 1, $size = null, $color = null)
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getCart();

        $cartKey = $productId . '_' . ($size ?? 'def') . '_' . ($color ?? 'def');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += (int) $quantity;
        } else {
            $cart[$cartKey] = [
                'cart_key' => $cartKey,
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->image,
                'price' => (float) $product->price,
                'quantity' => (int) $quantity,
                'size' => $size,
                'color' => $color,
                'subtotal' => 0,
            ];
        }

        if ($product->stock > 0 && $cart[$cartKey]['quantity'] > $product->stock) {
            $cart[$cartKey]['quantity'] = $product->stock;
        }

        $cart[$cartKey]['subtotal'] = $cart[$cartKey]['price'] * $cart[$cartKey]['quantity'];

        session()->put('cart', $cart);

        return $cart;
    }

    public function update($cartKey, $quantity)
    {
        $cart = $this->getCart();

        if (isset($cart[$cartKey])) {
            $product = Product::find($cart[$cartKey]['product_id']);
            $maxStock = $product ? $product->stock : 99;

            $qty = max(1, min((int) $quantity, $maxStock));
            $cart[$cartKey]['quantity'] = $qty;
            $cart[$cartKey]['subtotal'] = $cart[$cartKey]['price'] * $qty;

            session()->put('cart', $cart);
        }

        return $cart;
    }

    public function remove($cartKey)
    {
        $cart = $this->getCart();

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }

        return $cart;
    }

    public function clear()
    {
        session()->forget('cart');
    }

    public function getSubtotal()
    {
        $cart = $this->getCart();
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return $subtotal;
    }

    public function getTax()
    {
        return $this->getSubtotal() * 0.13;
    }

    public function getShipping()
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal == 0) {
            return 0;
        }

        return $subtotal > 50000 ? 0 : 3500;
    }

    public function getTotal()
    {
        return $this->getSubtotal() + $this->getTax() + $this->getShipping();
    }

    public function getCount()
    {
        $cart = $this->getCart();
        $count = 0;

        foreach ($cart as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }
}

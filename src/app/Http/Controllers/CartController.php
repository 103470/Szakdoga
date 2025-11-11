<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\CartItem;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $cartItems = Auth::user()->cartItems()->with('product')->get();
        } else {
            $cartSession = session('cart', []);
            $cartItems = collect($cartSession)->map(function ($item) {
                $product = Product::find($item['product_id']);
                return (object)[
                    'product' => $product,
                    'quantity' => $item['quantity']
                ];
            });
        }

        return view('partials.cart', [
            'cart' => $cartItems
        ]);
    }



    private function getCartCollection()
    {
        if (Auth::check()) {
            $cart = Auth::user()->cartItems()->with('product')->get();
            return $cart;
        }

        $sessionCart = session('cart', []);

        $cartCollection = collect($sessionCart)->map(function ($item) {

            $product = Product::find($item['product_id'] ?? null);
            if (!$product) {
                return null;
            }

            return (object)[
                'product_id' => $product->id,
                'product'    => $product,
                'quantity'   => $item['quantity'] ?? 0,
            ];
        })->filter();

        return $cartCollection;
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = max(1, (int) $request->input('quantity', 1));

        if (Auth::check()) {
            $user = Auth::user();
            $cartItem = $user->cartItems()->firstOrNew(['product_id' => $product->id]);

            $cartItem->quantity = ($cartItem->quantity ?? 0) + $quantity;
            $cartItem->save();
        } else {
            $cart = session('cart', []);

            if (isset($cart[$id])) {
                $cart[$id]['quantity'] += $quantity;
            } else {
                $cart[$id] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ];
            }

            session(['cart' => $cart]);
        }

        return response()->json([
            'success' => true,
            'message' => "{$product->name} sikeresen a kosárba került ({$quantity} db)!",
            'cartCount' => $this->getCartCollection()->sum('quantity'),
        ]);
    }

    public function updateQuantity(Request $request, $id)
    {
        $delta = (int) $request->input('delta', 0);
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Termék nem található'], 404);
        }

        if (Auth::check()) {
            $item = CartItem::where('product_id', $id)
                            ->where('user_id', Auth::id())
                            ->first();

            if (!$item) {
                return response()->json(['error' => 'Termék nincs a kosárban'], 404);
            }

            $newQuantity = max(1, min($item->quantity + $delta, $product->stock));
            $item->quantity = $newQuantity;
            $item->save();

            $cartItems = CartItem::where('user_id', Auth::id())->get();
            $total = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
            $count = $cartItems->sum('quantity');
        } else {
            $cart = session('cart', []);
            if (!isset($cart[$id])) {
                return response()->json(['error' => 'Termék nincs a kosárban'], 404);
            }

            $newQuantity = max(1, min($cart[$id]['quantity'] + $delta, $product->stock));
            $cart[$id]['quantity'] = $newQuantity;
            session(['cart' => $cart]);

            $total = collect($cart)->sum(fn($item) => Product::find($item['product_id'])->price * $item['quantity']);
            $count = collect($cart)->sum('quantity');
        }

        return response()->json([
            'success' => true,
            'newQuantity' => $newQuantity,
            'subtotal' => $subtotal,
            'total' => $total,
            'count' => $count,
            'stock' => $product->stock,
        ]);
    }


    public function getCartContent()
    {
        $cart = $this->getCartCollection();
        $count = $cart->sum('quantity');

        $html = view('partials.cart_dropdown', compact('cart'))->render();

        return response()->json([
            'html' => $html,
            'count' => $count,
        ]);
    }

    public function removeItem($id)
    {
        if (Auth::check()) {
            $item = CartItem::where('product_id', $id)
                            ->where('user_id', Auth::id())
                            ->first();

            if ($item) {
                $item->delete();
            }

            $cartItems = CartItem::where('user_id', Auth::id())->get();
            $total = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
            $count = $cartItems->sum('quantity');
        } else {
            $cart = session('cart', []);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session(['cart' => $cart]);
            }

            $total = collect($cart)->sum(fn($item) => Product::find($item['product_id'])->price * $item['quantity']);
            $count = collect($cart)->sum('quantity');
        }

        return response()->json([
            'success' => true,
            'total' => $total,
            'count' => $count,
        ]);
    }



    public function dropdown()
    {
        $cart = $this->getCartCollection();

        $cartArray = collect($cart)->map(function ($item) {
            $productId = is_array($item) ? ($item['product_id'] ?? null) : ($item->product_id ?? null);
            $quantity  = is_array($item) ? ($item['quantity'] ?? 0) : ($item->quantity ?? 0);

            if (!$productId) return null;

            $product = is_array($item) && isset($item['product'])
                ? $item['product']
                : ($item->product ?? Product::find($productId));

            if (!$product) return null;

            return [
                'product_id' => $productId,
                'quantity'   => $quantity,
                'product'    => [
                    'name'  => $product->name,
                    'price' => (float) $product->price,
                    'image' => asset('storage/' . ltrim($product->image, '/')),
                    'stock' => $product->stock ?? 1,
                ],
            ];
        })->filter()->values();

        $total = $cartArray->sum(fn($item) => $item['quantity'] * $item['product']['price']);
        $count = $cartArray->sum(fn($item) => $item['quantity']);

        return response()->json([
            'cart'  => $cartArray,
            'total' => $total,
            'count' => $count,
        ]);
    }

}

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
        $cart = $this->getCartCollection();
        $total = $cart->sum(fn($i) => $i->product->price * $i->quantity);

        $html = view('partials.cart_dropdown', compact('cart', 'total'))->render();

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'total' => $total,
            'html' => $html
        ]);
    }


    private function getCartCollection()
    {
        if (Auth::check()) {
            $cart = Auth::user()->cartItems()->with('product')->get();
            \Log::info('Auth cart', ['cart' => $cart->toArray()]);
            return $cart;
        }

        $sessionCart = session('cart', []);
        \Log::info('Raw session cart', ['cart' => $sessionCart]);

        $cartCollection = collect($sessionCart)->map(function ($item) {
            \Log::info('Session cart item', ['item' => $item]);

            $product = Product::find($item['product_id'] ?? null);
            if (!$product) {
                \Log::warning('Product not found for cart item', ['item' => $item]);
                return null;
            }

            return (object)[
                'product_id' => $product->id,
                'product'    => $product,
                'quantity'   => $item['quantity'] ?? 0,
            ];
        })->filter();

        \Log::info('Processed session cart', ['cart' => $cartCollection->toArray()]);

        return $cartCollection;
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = (int) $request->input('quantity', 1);

        if (Auth::check()) {
            $user = Auth::user();
            $user->cartItems()->updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => \DB::raw('quantity + {$quantity}')]
            );
        } else {
            $cart = session('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] += $quantity;
            } else {
                $cart[$id] = [
                    'product_id' => $product->id,
                    'quantity' => 1
                ];
            }
            session(['cart' => $cart]);
        }

        \Log::info('Session cart after add:', session('cart'));

        return response()->json([
            'success' => true,
            'message' => "{$product->name} sikeresen a kosárba került!",
            'cartCount' => $this->getCartCollection()->sum('quantity'),
        ]);
    }

    
    public function updateQuantity(Request $request, $id)
    {
        $delta = $request->input('delta', 0);
        $cart = session('cart', []);

        if (!isset($cart[$id])) {
            return response()->json(['error' => 'Termék nincs a kosárban'], 404);
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Termék nem található'], 404);
        }

        $newQuantity = max(1, min($cart[$id]['quantity'] + $delta, $product->stock));
        $cart[$id]['quantity'] = $newQuantity;
        session(['cart' => $cart]);

        $total = collect($cart)->sum(fn($item) => Product::find($item['product_id'])->price * $item['quantity']);
        $count = collect($cart)->sum('quantity');

        return response()->json([
            'success' => true,
            'newQuantity' => $newQuantity,
            'total' => $total,
            'count' => $count, 
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
            $item = CartItem::findOrFail($id);
            $item->delete();
        } else {
            $cart = session('cart', []);
            unset($cart[$id]);
            session(['cart' => $cart]);
        }

        return response()->json(['success' => true]);
    }

    public function dropdown()
    {
        $cart = $this->getCartCollection();

        \Log::info('CART DATA', ['cart' => $cart]);

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

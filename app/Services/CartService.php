<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CartService
{
    public function getCart(): Cart
    {
        // if (Auth::check()) {
        //     return Cart::firstOrCreate(['user_id' => Auth::id()]);
        // }

        $sessionId = session()->get('cart_session_id');
        if (!$sessionId) {
            Log::info('No sessionId');
            $sessionId = (string) Str::uuid();
            session(['cart_session_id' => $sessionId]);
        }
            Log::info(['sessionId' => $sessionId]);

        return Cart::with('items')->firstOrCreate(['session_id' => $sessionId]);
    }

    public function addItem($product, int $quantity = 1): void
    {
        $cart = $this->getCart();

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price, // snapshot price
            ]);
        }
        $cart->touch();
    }

    public function removeItem(int $itemId): void
    {
        $cart = $this->getCart();
        $cart->items()->where('id', $itemId)->delete();
        $cart->touch();
    }

    public function clearCart(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        $cart->touch();
    }

    public function mergeCartOnLogin($user): void
    {
        $sessionId = session()->get('cart_session_id');
        if (!$sessionId) return;

        $guestCart = Cart::where('session_id', $sessionId)->first();
        if (!$guestCart) return;

        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($guestCart->items as $item) {
            $existing = $userCart->items()->where('product_id', $item->product_id)->first();
            if ($existing) {
                $existing->increment('quantity', $item->quantity);
            } else {
                $userCart->items()->create($item->only(['product_id', 'quantity', 'price']));
            }
        }

        $guestCart->delete();
        session()->forget('cart_session_id');
    }
}

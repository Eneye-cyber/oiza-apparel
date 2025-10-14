<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Throwable;

class CartService
{
    public function getCart(): ?Cart
    {
        try {
            // Attempt to get or create a session ID
            $sessionId = session()->get('cart_session_id');

            if (!$sessionId) {
                Log::info('Cart session not found, generating new session ID.');
                $sessionId = (string) Str::uuid();
                session(['cart_session_id' => $sessionId]);
            }

            Log::info(['cart_session_id' => $sessionId]);

            // Try to find or create the cart for this session
            $cart = Cart::with('items')->firstOrCreate(['session_id' => $sessionId]);

            return $cart;
        } catch (QueryException $e) {
            // Handles database-related issues
            Log::error('Database error while fetching/creating cart.', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId ?? null,
            ]);
        } catch (Throwable $e) {
            // Handles all other unexpected errors
            Log::error('Unexpected error in getCart()', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId ?? null,
            ]);
        }

        // Return null or handle gracefully if something went wrong
        return null;
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

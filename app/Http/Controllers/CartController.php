<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Products\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): JsonResponse
    {
        $cart = $this->cartService->getCart()->load('items.product');
        return response()->json([
            'items' => $cart->items,
            'subtotal' => $cart->items->sum(fn($i) => $i->price * $i->quantity),
            'count' => $cart->items->count()
        ]);
    }

    public function add($productId): JsonResponse
    {
        $product = Product::findOrFail($productId);
        $this->cartService->addItem($product, 1);

        return $this->index();
    }

    public function update(Request $request, $itemId): JsonResponse
    {
        $cart = $this->cartService->getCart();
        $item = $cart->items()->where('id', $itemId)->firstOrFail();

        $item->update(['quantity' => (int) $request->input('quantity', 1)]);
        return $this->index();
    }

    public function remove($itemId): JsonResponse
    {
        $cart = $this->cartService->getCart();
        $cart->items()->where('id', $itemId)->delete();

        return $this->index();
    }
}

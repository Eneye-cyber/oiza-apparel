<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Products\Product;
use App\Services\CartService;
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
        $cart = $this->cartService->getCart()->load([
            'items.product' => function ($query) {
                $query->select('id', 'cover_media', 'name', 'category_id', 'main_color', 'discount_price', 'price');
            },
            'items.product.category' => function ($query) {
                $query->select('id', 'name');
            },
            'items.variant' => function ($query) {
                $query->select('id', 'name', 'media', 'price');
            },
        ]);

        // Transform cover_media for each item
        $items = $cart->items;

        return response()->json([
            'items' => $items,
            'subtotal' => $items->sum(fn($i) => ($i->variant?->price ?? $i->product?->discount_price ?? $i->product->price) * $i->quantity),
            'count' => $items->count()
        ]);
    }

    public function add(Request $request, $productId): JsonResponse
    {
        $quantity = $request->input('quantity', 1);
        $variant_id = $request->input('variant_id', null);

        $product = Product::findOrFail($productId);
        $this->cartService->addItem($product, $quantity, $variant_id);

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

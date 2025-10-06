<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Products\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
                $query->select('id', 'cover_media', 'name', 'category_id', 'main_color');
            },
            'items.product.category' => function ($query) {
                $query->select('id', 'name');
            },
        ]);

        // Transform cover_media for each item
        $items = $cart->items->map(function ($item) {
            $item->product->cover_media = $item->product->cover_media
                ? Storage::disk(env('APP_DISK', 'local'))->url($item->product->cover_media)
                : null;
            // Log::info(['controller' => 'CartController', 'method' => 'index', 'data' => $item]);
            return $item;
        });

        return response()->json([
            'items' => $items,
            'subtotal' => $items->sum(fn($i) => $i->price * $i->quantity),
            'count' => $items->count()
        ]);
    }

    public function add(Request $request, $productId): JsonResponse
    {
        $qty = $request->input('quantity', 1);
        $product = Product::findOrFail($productId);
        $this->cartService->addItem($product, $qty);

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

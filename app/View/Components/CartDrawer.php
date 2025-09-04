<?php

namespace App\View\Components;

use App\Models\Products\Product;
use App\Services\CartService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CartDrawer extends Component
{
    public $cartItems;
    public $cart;
    /**
     * Create a new component instance.
     */
    public function __construct(CartService $cartService)
    {
        // Load the cart with its items & products
        $this->cart = $cartService->getCart()->load('items.product');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cart-drawer');
    }
}

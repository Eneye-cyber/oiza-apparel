<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CartDrawer extends Component
{
    
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Load the cart with its items & products
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cart-drawer');
    }
}

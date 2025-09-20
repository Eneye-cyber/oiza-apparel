<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class CheckoutController extends Controller
{
    //
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart()->load([
            'items.product' => function ($query) {
                $query->select('id', 'cover_media', 'name', 'category_id', 'main_color');
            },
            'items.product.category' => function ($query) {
                $query->select('id', 'name');
            },
        ]);

        // No need to map for URL conversion anymore
        $cartItems = $cart->items;
        $subtotal = $cartItems->sum(fn($i) => $i->price * $i->quantity);

        return view('pages.checkout', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request)
    {
        // Validate and process the checkout form submission
        $validatedData = $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'create_account' => 'sometimes|boolean',
            'password' => 'required_if:create_account,1|string|min:8|confirmed',
            'terms_agreement' => 'accepted',
        ]);

        // Process payment and order creation logic here

        // For now, just log the validated data
        Log::info('Checkout data:', $validatedData);

        return redirect()->route('checkout.success');
    }
}

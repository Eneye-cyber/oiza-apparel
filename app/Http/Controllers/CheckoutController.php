<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;


class CheckoutController extends Controller
{
    //
    protected CartService $cartService;
    protected OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
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
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Your cart is empty.');
        }

        return view('pages.checkout', compact('cartItems', 'subtotal'));
    }


    public function store(StoreOrderRequest $request, PaymentService $paymentService)
    {
        $validated = $request->validated();

        $cart = $this->cartService->getCart()->load([
            'items.product:id',
            'items.product.category:id',
        ]);

        try {
            $order = $this->orderService->createFromCart($validated, $cart->items);

            Log::info('Order placed successfully', [
                'order_id' => $order->id,
                'total'    => $order->total,
                'items'    => $order->items()->count(),
            ]);

            $paymentData = $paymentService->initializePayment($order, $validated);
            Log::info('Payment initialized', [
                'order_id' => $order->id,
                'payment' => $paymentData,
                'payment_ref' => $paymentData['paymentReference'] ?? null,
            ]);

            // TODO: Handle cases where redirecUrl is not working
            return redirect()->away($paymentData['checkoutUrl']);

        } catch (\Throwable $th) {
            Log::error('Order creation failed', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return redirect()->route('checkout')->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function paymentCallback(Request $request)
    {
        try {
            $order = app(PaymentService::class)->handleCallback($request->all());

            if ($order->payment_status->value === 'paid') {
                return redirect()->route('checkout', $order->id)
                    ->with('success', 'Payment successful! Your order is confirmed.');
            }

            return redirect()->route('checkout', $order->id)
                ->with('error', 'Payment failed. Please try again.');
        } catch (\Throwable $th) {
            Log::error('Payment callback failed', ['error' => $th->getMessage()]);
            return redirect()->route('checkout')->with('error', 'Payment verification failed.');
        }
    }
}

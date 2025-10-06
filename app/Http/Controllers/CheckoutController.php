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

            $order->update([
                'transaction_ref' => $paymentData['transactionReference'],
                'cart_id' => $cart->id
            ]);

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
            // redirect to orders confirmation page
            if ($order->payment_status->value === 'paid') {

                return redirect()->route('order')
                    ->with([
                        'success' => 'Payment successful! Your order is confirmed.',
                        'order_number' => $order->order_number,
                        'order_amount' => $order->total,
                        'estimated_delivery' => $order->delivery_min_days . ' - ' . $order->delivery_max_days . ' Business Days'
                    ]);
            }

            return redirect()->route('order')
                ->with([
                    'error' => 'Payment failed. Please try again.',
                    'order_number' => $order->order_number,
                    'order_amount' => $order->total,
                ]);
        } catch (\Throwable $th) {
            // on error, consider deleting the order as there is no point in leaving a pending order with an invalid transaction reference
            // instead of just redirecting to checkout page
            Log::error('Payment callback failed', ['error' => $th->getMessage()]);
            return redirect()->route('checkout')->with('error', 'Payment verification failed.');
        }
    }

    public function monnifyWebhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Monnify webhook received', $payload);

        try {
            $order = app(PaymentService::class)->handleWebhook($payload);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'payment_status' => $order->payment_status->value,
            ]);
        } catch (\Throwable $th) {
            Log::error('Webhook handling failed', [
                'error' => $th->getMessage(),
                'payload' => $payload,
            ]);
            return response()->json(['success' => false], 500);
        }
    }
}

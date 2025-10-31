<?php

namespace App\Services;

use App\Models\Orders\Order;
use App\Enums\PaymentStatus;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Log;
use Monnify\MonnifyLaravel\Facades\Monnify;

class PaymentService
{
    public function initializePayment(Order $order, array $validated)
    {
        // Prepare Monnify payload
        $payload = [
            "amount" => $order->total,
            "customerName" => $order->shippingAddress->name,
            "customerEmail" => $order->guest_email,
            "paymentReference" => $order->order_number,
            "paymentDescription" => "Order #{$order->order_number}: Purchase at Oiza Apparels",
            "currencyCode" => "NGN",
            'contractCode' => config('monnify.contract_code'),
            "redirectUrl" => route('payment.callback'),
        ];

        $response = Monnify::transactions()->initialise($payload);
        // Log::info('Monnify initialization response', $response);
        if (
            empty($response['status']) ||
            $response['status'] !== 200 ||
            empty($response['body']['requestSuccessful']) ||
            $response['body']['requestSuccessful'] !== true
        ) {
            Log::error('Monnify initialization failed', $response);
            throw new \RuntimeException('Payment initialization failed');
        }

        // Return only responseBody
        return $response['body']['responseBody'] ?? [];
    }

    public function handleCallback(array $payload)
    {
        Log::info(["payload" => $payload]);
        $paymentReference = $payload['paymentReference'];
        $order = Order::where('order_number', $paymentReference)->firstOrFail();
        $transactionReference = $order->transaction_ref;
        $transaction = Monnify::transactions()->status($transactionReference);

        Log::info(["transaction" => $transaction]);

        $paymentStatus = $transaction['body']['responseBody']['paymentStatus'];
        Log::info(["paymentStatus" => $paymentStatus]);

        if ($paymentStatus === 'PAID') {
            // Let OrderService handle domain logic
            app(OrderService::class)->completeCheckout($order);
        } else {
            $this->handleFailed($order, $paymentReference);
        }

        return $order->refresh();
    }

    // TODO: GENERATE A LOGGER SERVICE THAT LOGS ONLY ON DEV TO A SEPARATE FILE
    public function handleWebhook(array $payload)
    {
        try {
            // ✅ Verify Monnify signature
            $this->verifySignature();

            $orderRef = $payload['eventData']['paymentReference'] ?? null;
            $status   = $payload['eventData']['paymentStatus'] ?? null;

            if (! $orderRef || ! $status) {
                Log::warning('Monnify webhook: Invalid payload', ['payload' => $payload]);
                throw new \InvalidArgumentException('Invalid Monnify payload');
            }

            $order = Order::where('order_number', $orderRef)->first();

            if (! $order) {
                Log::error('Monnify webhook: Order not found', ['order_reference' => $orderRef]);
                throw new \RuntimeException("Order not found: {$orderRef}");
            }

            // ✅ Process payment status
            match ($status) {
                'PAID' => $this->handlePaid($order, $orderRef),
                'FAILED' => $this->handleFailed($order, $orderRef),
                default => Log::notice('Monnify webhook: Unknown status', [
                    'order_reference' => $orderRef,
                    'status' => $status,
                ]),
            };

            return $order;
        } catch (\Throwable $e) {
            Log::error('Monnify webhook: Error processing', [
                'message' => $e->getMessage(),
                'trace' => app()->environment('local') ? $e->getTraceAsString() : null,
            ]);
            throw $e;
        }
    }

    protected function handlePaid(Order $order, string $orderRef): void
    {
        app(OrderService::class)->completeCheckout($order);

        Log::info('Monnify webhook: Payment successful', [
            'order_reference' => $orderRef,
            'order_id' => $order->id,
        ]);
    }

    protected function handleFailed(Order $order, string $orderRef): void
    {
        $order->update(['payment_status' => PaymentStatus::Failed, 'status' => OrderStatus::Cancelled]);

        Log::warning('Monnify webhook: Payment failed', [
            'order_reference' => $orderRef,
            'order_id' => $order->id,
        ]);
    }

    protected function verifySignature(): void
    {
        $requestSignature = request()->header('monnify-signature');
        $secretKey = config('monnify.secret_key');
        $rawBody = request()->getContent();

        if (! $requestSignature) {
            throw new \RuntimeException('Missing Monnify signature header');
        }

        $calculatedSignature = hash_hmac('sha512', $rawBody, $secretKey);

        if (! hash_equals($calculatedSignature, $requestSignature)) {
            throw new \RuntimeException('Invalid Monnify signature');
        }
    }
}

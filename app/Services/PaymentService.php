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
        $order = Order::where('order_number', $payload['paymentReference'])->firstOrFail();

        if ($payload['paymentStatus'] === 'PAID') {
            $order->update([
                'payment_status' => PaymentStatus::Success,
                'status'         => OrderStatus::Processing,
            ]);
        } else {
            $order->update([
                'payment_status' => PaymentStatus::Failed,
            ]);
        }

        return $order;
    }
}

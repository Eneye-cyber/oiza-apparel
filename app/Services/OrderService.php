<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Orders\Order;
use App\Models\Shipping\ShippingState;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
  public function createFromCart(array $validated, Collection $cartItems): Order
  {
    if ($cartItems->isEmpty()) {
      throw ValidationException::withMessages([
        'cart' => 'Your cart is empty.',
      ]);
    }

    // Calculate totals
    $subtotal = $cartItems->sum(fn($i) => $i->price * $i->quantity);
    $state    = ShippingState::findOrFail($validated['state']);

    $shippingMethod = $state->findMethodByIdDirect($validated['shipping_method']);
    if (! $shippingMethod) {
      throw ValidationException::withMessages([
        'shipping_method' => 'Invalid shipping method.',
      ]);
    }

    $shippingCost = $shippingMethod['delivery_cost'] ?? 0;
    $total        = $subtotal + $shippingCost;

    // Shipping data
    $shippingData = [
      'name'     => $validated['first_name'] . ' ' . $validated['last_name'],
      'country'  => $validated['country'],
      'address'  => $validated['address'],
      'state'    => $state->name,
      'state_id' => $state->id,
      'city'     => $validated['city'],
      'zip'      => $validated['zip'] ?? null,
      'phone'    => $validated['phone'],
      'type'     => 'shipping',
    ];

    return DB::transaction(function () use ($validated, $subtotal, $shippingCost, $total, $shippingData, $cartItems) {
      // Create order
      $order = Order::create([
        'guest_email'    => $validated['email'],
        'subtotal'       => $subtotal,
        'discount'       => 0, // TODO: add coupons/discount service
        'tax'            => 0, // TODO: tax calculation
        'shipping'       => $shippingCost,
        'total'          => $total,
        'status'         => OrderStatus::Pending,
        'payment_method' => PaymentMethod::Gateway, // <- default to gateway, can be updated later
        'order_channel'  => 'website',
        'payment_status' => PaymentStatus::Pending,
      ]);

      // Addresses
      $order->shippingAddress()->create($shippingData);

      if ($validated['billing_same_as_shipping']) {
        $order->billingAddress()->create(array_merge($shippingData, ['type' => 'billing']));
      } else {
        $order->billingAddress()->create([
          'name'    => $validated['billing_first_name'] . ' ' . $validated['billing_last_name'],
          'country' => $validated['billing_country'],
          'address' => $validated['billing_address'],
          'state'   => $validated['billing_state'],
          'city'    => $validated['billing_city'],
          'zip'     => $validated['billing_zip'] ?? null,
          'phone'   => $validated['billing_phone'] ?? $validated['phone'],
          'type'    => 'billing',
        ]);
      }

      // Items (bulk insert for performance)
      $order->items()->insert($cartItems->map(fn($i) => [
        'order_id'   => $order->id,
        'product_id' => $i->product->id,
        'quantity'   => $i->quantity,
        'price'      => $i->price,
        'total'      => $i->price * $i->quantity,
        'created_at' => now(),
        'updated_at' => now(),
      ])->toArray());

      return $order;
    });
  }
}

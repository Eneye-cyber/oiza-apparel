<?php

namespace App\Models\Orders;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Address;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Default static values
     */
    protected $attributes = [
        'status'         => 'pending',
        'payment_status' => 'unpaid',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'confirmed_at'      => 'datetime',
        'payment_method' => PaymentMethod::class, // enum cast
        'payment_status' => PaymentStatus::class, // enum cast
        'status'         => OrderStatus::class,   // enum cast
    ];

    /**
     * Boot model events
     */
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            // Generate UUID order number if not set
            if (! $order->order_number) {
                $order->order_number = (string) Str::uuid();
            }

            // Generate tracking token if not set
            if (! $order->tracking_token) {
                $order->tracking_token = Str::random(32);
            }
        });
        // Depreciated, moved to order service as a transaction
        // static::updating(function (Order $order) {
        //     if (
        //         $order->isDirty('payment_status')
        //         && $order->payment_status === PaymentStatus::Success->value
        //         && is_null($order->confirmed_at)
        //     ) {

        //         $order->confirmed_at = now();

        //         if ($order->cart) {
        //             $order->cart->items()->delete();
        //         }
        //     }
        // });
    }

    // 🔹 Relationships
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function shippingAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('type', 'shipping');
    }

    public function billingAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('type', 'billing');
    }

    // 🔹 Helpers
    public function generateTrackingToken(): void
    {
        $this->tracking_token = Str::random(32); // 32-char token
        $this->save();
    }
}

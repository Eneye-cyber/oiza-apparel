<?php

namespace App\Models\Shipping;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingStateMethod extends Model
{
    //
    protected $fillable = [
    'state_id',
    'method_id',
    'delivery_cost',
    'free_shipping_minimum',
    'delivery_min_days',
    'delivery_max_days',
    'is_active',
  ];

  public function state(): BelongsTo
  {
    return $this->belongsTo(ShippingState::class);
  }

  public function method(): BelongsTo
  {
    return $this->belongsTo(ShippingMethod::class);
  }
}

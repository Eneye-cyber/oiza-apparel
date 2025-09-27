<?php

namespace App\Models\Shipping;

use Illuminate\Database\Eloquent\Model;

class ShippingCountryMethod extends Model
{
    //
    protected $fillable = [
        'country_id',
        'method_id',
        'delivery_cost',
        'free_shipping_minimum',
        'delivery_min_days',
        'delivery_max_days',
        'is_active',
    ];

    public function country()
    {
        return $this->belongsTo(ShippingCountry::class);
    }

    public function method()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
}

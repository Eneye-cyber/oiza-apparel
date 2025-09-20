<?php

namespace App\Models\Shipping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingState extends Model
{
    //
    use HasFactory;

    protected $fillable = ['country_id', 'code', 'name', 'is_active'];
    protected $appends = ['active_methods'];

    public function country()
    {
        return $this->belongsTo(ShippingCountry::class, 'country_id');
    }

    public function methods(): HasMany
    {
        return $this->hasMany(ShippingStateMethod::class, 'state_id');
    }

    public function getActiveMethodsAttribute()
    {
        $methods = $this->methods->map(function ($stateMethod) {
            return [
                'name' => $stateMethod->method->name,
                'delivery_cost' => $stateMethod->delivery_cost,
                'free_shipping_minimum' => $stateMethod->free_shipping_minimum,
                'delivery_min_days' => $stateMethod->delivery_min_days,
                'delivery_max_days' => $stateMethod->delivery_max_days,
            ];
        })->values();
        if ($methods->isEmpty()) {
            // Fallback to country-level methods if no state-specific methods
            return $this->country ? $this->country->active_methods : [];
        }
        return $methods;
    }

}

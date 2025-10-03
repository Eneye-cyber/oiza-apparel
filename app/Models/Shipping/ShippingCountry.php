<?php

namespace App\Models\Shipping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingCountry extends Model
{
  //
  use HasFactory;

  protected $fillable = ['code', 'name', 'is_active'];
  // protected $appends = ['active_methods'];

  public function states(): HasMany
  {
    return $this->hasMany(ShippingState::class, 'country_id', 'id');
  }

  public function methods(): HasMany
  {
    return $this->hasMany(ShippingCountryMethod::class, 'country_id');
  }

  // Add this to your ShippingCountry model
  public function getActiveMethodsAttribute()
  {
    return $this->methods->map(function ($countryMethod) {
      return [
        'id' => $countryMethod->id,
        'name' => $countryMethod->method->name,
        'delivery_cost' => $countryMethod->delivery_cost,
        'free_shipping_minimum' => $countryMethod->free_shipping_minimum,
        'delivery_min_days' => $countryMethod->delivery_min_days,
        'delivery_max_days' => $countryMethod->delivery_max_days,
      ];
    })->values();
  }

  public function withActiveMethods()
  {
    return $this->append('active_methods');
  }

  public function findMethodById($methodId)
  {
    $method = $this->methods()
      ->where('id', $methodId)
      ->with('method')
      ->first();

    if ($method) {
      return [
        'name' => $method->method->name,
        'delivery_cost' => $method->delivery_cost,
        'free_shipping_minimum' => $method->free_shipping_minimum,
        'delivery_min_days' => $method->delivery_min_days,
        'delivery_max_days' => $method->delivery_max_days,
      ];
    }

    return null;
  }

  // Helper: Check free shipping
  // public function hasFreeShipping(): bool
  // {
  //   return $this->methods()->where('is_free', true)->exists();
  // }

  // Helper: Get delivery estimate string

}

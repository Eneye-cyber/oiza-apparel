<?php

namespace App\Models\Shipping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingMethod extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name', 'code', 'is_active'];

    public function countryMethods(): HasMany
    {
        return $this->hasMany(ShippingCountryMethod::class, 'method_id');
    }

    public function stateMethods(): HasMany
    {
        return $this->hasMany(ShippingStateMethod::class, 'method_id');
    }
}

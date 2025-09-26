<?php

namespace App\Models;

use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    //
      protected $fillable = [
        'type', 'name', 'country', 'address',
        'state', 'state_id', 'city', 'zip', 'phone'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

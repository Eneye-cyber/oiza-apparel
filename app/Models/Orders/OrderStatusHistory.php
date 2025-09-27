<?php

namespace App\Models\Orders;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
        'status'         => OrderStatus::class,   // enum cast
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

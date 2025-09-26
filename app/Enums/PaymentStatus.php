<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'unpaid';
    case Success = 'paid';
    case Failed = 'failed';
    case Refunded = 'refunded';
    
}

<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Gateway = 'payment_gateway';
    case Transfer = 'transfer';
    case CashOnDelivery = 'cash_on_delivery';
}

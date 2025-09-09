<?php

namespace App\Enums;

enum OrderType: string
{
    case BasedOnStock = 'based_on_stock';
    case Unlimited = 'unlimited';
    case PreOrder = 'pre_order';
    case Unavailable = 'unavailable';

    public function label(): string
    {
        return match ($this) {
            self::BasedOnStock => 'Based on Stock',
            self::Unlimited => 'Unlimited',
            self::PreOrder => 'Pre-order',
            self::Unavailable => 'Unavailable',
        };
    }
}

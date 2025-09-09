<?php

namespace App\Enums;

enum ProductStatus: string
{
    case InStock = 'in_stock';
    case SoldOut = 'sold_out';
    case ComingSoon = 'coming_soon';

    public function label(): string
    {
        return match ($this) {
            self::InStock => 'In Stock',
            self::SoldOut => 'Sold Out',
            self::ComingSoon => 'Coming Soon',
        };
    }
}

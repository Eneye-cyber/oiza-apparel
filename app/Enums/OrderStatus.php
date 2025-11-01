<?php

namespace App\Enums;


use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending    = 'pending';
    case Processing = 'processing';
    case Completed  = 'completed';
    case Cancelled  = 'cancelled';
    case Refunded   = 'refunded';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Awaiting payment',
            self::Processing => 'Processing',
            self::Completed => 'Completed',
            // self::Delivered => 'Delivered',
            self::Cancelled => 'Cancelled',
            self::Refunded => 'Refunded',
            
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Processing => 'info',
            self::Completed /*, self::Delivered */ => 'success',
            self::Cancelled => 'danger',
            self::Refunded => 'neutral',

        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Pending => 'heroicon-m-sparkles',
            self::Processing => 'heroicon-m-arrow-path',
            // self::Shipped => 'heroicon-m-truck',
            self::Completed => 'heroicon-m-check-badge',
            self::Cancelled => 'heroicon-m-x-circle',
            self::Refunded => 'heroicon-m-arrow-uturn-left',

        };
    }
}

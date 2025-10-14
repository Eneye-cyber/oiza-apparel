<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order_number'),
                TextEntry::make('guest_email'),
                TextEntry::make('subtotal')
                    ->numeric(),
                TextEntry::make('discount')
                    ->numeric(),
                TextEntry::make('tax')
                    ->numeric(),
                TextEntry::make('shipping')
                    ->numeric(),
                TextEntry::make('total')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('payment_method'),
                TextEntry::make('order_channel'),
                TextEntry::make('payment_status'),
                TextEntry::make('confirmed_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('transaction_ref'),
                TextEntry::make('shipping_type'),
                TextEntry::make('delivery_min_days')
                    ->numeric(),
                TextEntry::make('delivery_max_days')
                    ->numeric(),
                TextEntry::make('delivered_at')
                    ->dateTime(),
                TextEntry::make('cart.id'),
            ]);
    }
}

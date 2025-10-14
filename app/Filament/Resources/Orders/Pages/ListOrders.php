<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'pending' => Tab::make()->query(fn ($query) => $query->where('status', 'pending')),
            'processing' => Tab::make()->query(fn ($query) => $query->where('status', 'processing')),
            'completed' => Tab::make()->query(fn ($query) => $query->where('status', 'completed')),
            'cancelled' => Tab::make()->query(fn ($query) => $query->where('status', 'cancelled')),
            'refunded' => Tab::make()->query(fn ($query) => $query->where('status', 'refunded')),
        ];
    }
}

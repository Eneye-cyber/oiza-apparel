<?php

namespace App\Filament\Resources\Enquiries\Pages;

use App\Enums\EnquiryStatus;
use App\Filament\Resources\Enquiries\EnquiryResource;
use App\Filament\Resources\Enquiries\Widgets\EnquiryOverview;
use App\Models\Enquiry;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEnquiries extends ListRecords
{
    protected static string $resource = EnquiryResource::class;
    // Default active tab
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            EnquiryOverview::class,
        ];
    }


    public function getTabs(): array
    {
        return [
            'new' => Tab::make('New')
                ->icon(EnquiryStatus::New->getIcon())
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('status', EnquiryStatus::New->value)
                ),

            'pending' => Tab::make('Processing')
                ->icon(EnquiryStatus::Processing->getIcon())
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('status', EnquiryStatus::Processing->value)
                ),

            'resolved' => Tab::make('Resolved')
                ->icon(EnquiryStatus::Resolved->getIcon())
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('status', EnquiryStatus::Resolved->value)
                ),

            'closed' => Tab::make('Closed')
                ->icon(EnquiryStatus::Closed->getIcon())
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('status', EnquiryStatus::Closed->value)
                ),

            'all' => Tab::make('All')
                ->icon('heroicon-o-list-bullet')
                ->badge(Enquiry::count()),
        ];
    }
}

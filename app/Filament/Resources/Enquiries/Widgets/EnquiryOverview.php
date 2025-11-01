<?php

namespace App\Filament\Resources\Enquiries\Widgets;

use App\Enums\EnquiryStatus;
use App\Models\Enquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EnquiryOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Open Issues', Enquiry::whereIn('status', [
                EnquiryStatus::New->value,
                EnquiryStatus::Processing->value,
            ])->count())->description('Issues needing attention')->color('warning'),
            Stat::make('Resolved Issues', Enquiry::where('status', EnquiryStatus::Resolved->value)->count())
                ->description('Successfully resolved issues')
                ->descriptionIcon(EnquiryStatus::Resolved->getIcon())
                ->color('success'),
            Stat::make('Closed Issues', Enquiry::where('status', EnquiryStatus::Closed->value)->count())
                ->description('Closed issues')
                ->descriptionIcon(EnquiryStatus::Resolved->getIcon())
                ->color('gray'),
        ];
    }
}

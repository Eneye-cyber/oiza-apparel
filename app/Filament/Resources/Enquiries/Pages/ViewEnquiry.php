<?php

namespace App\Filament\Resources\Enquiries\Pages;

use App\Enums\EnquiryStatus;
use App\Filament\Resources\Enquiries\Actions\CloseAction;
use App\Filament\Resources\Enquiries\Actions\ResolveAction;
use App\Filament\Resources\Enquiries\Actions\RespondAction;
use App\Filament\Resources\Enquiries\EnquiryResource;
use App\Models\Enquiry;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEnquiry extends ViewRecord
{
    protected static string $resource = EnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
            RespondAction::make(),
            ResolveAction::make(),
            CloseAction::make(),
            DeleteAction::make()->hidden(fn(Enquiry $enquiry) => $enquiry->status != EnquiryStatus::Closed),

        ];
    }
}

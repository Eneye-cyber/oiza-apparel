<?php

namespace App\Filament\Resources\Faqs\Pages;

use App\Filament\Resources\Faqs\FaqResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFaqs extends ManageRecords
{
    protected static string $resource = FaqResource::class;
    protected static ?string $title = "Frequently asked Questions";

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label("Add Question"),
        ];
    }
}

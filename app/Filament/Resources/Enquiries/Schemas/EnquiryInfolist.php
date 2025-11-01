<?php

namespace App\Filament\Resources\Enquiries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class EnquiryInfolist
{
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([


        Section::make('Enquiry Details')
          ->schema([
            TextEntry::make('name'),
            TextEntry::make('email')->icon(Heroicon::Envelope),
            TextEntry::make('status')->badge(),
            TextEntry::make('subject')->columnSpan(2),
            TextEntry::make('created_at')
              ->dateTime(),

            TextEntry::make('message')
              ->columnSpanFull()->formatStateUsing(fn($state) => nl2br($state)),
          ])->columns([
            'default' => 1, // Mobile
            'md' => 2,      // Tablet
            'lg' => 3,      // Desktop
          ])->columnSpanFull(),

        Section::make('Administration')
          ->schema([
            TextEntry::make('admin_notes')->html(), // Sanitize and format
            TextEntry::make('responded_at')->label("Last Responded at")->dateTime(),

          ])->columnSpanFull()
          ->visible(fn($record) => filled($record->admin_notes) || filled($record->responded_at)),
      ]);
  }
}

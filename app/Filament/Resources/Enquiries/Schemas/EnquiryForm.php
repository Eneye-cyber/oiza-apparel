<?php

namespace App\Filament\Resources\Enquiries\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;

class EnquiryForm
{
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([
        //
        Section::make('Customer Information')
          ->schema([
            TextInput::make('name')
              ->required()
              ->maxLength(255)
              ->disabledOn(Operation::Edit->value),
            TextInput::make('email')
              ->email()
              ->required()
              ->maxLength(255)->disabledOn(Operation::Edit->value),
            TextInput::make('phone')
              ->tel()
              ->maxLength(255)->disabledOn(Operation::Edit->value)
          ])->columns(3)->columnSpanFull(),

        Section::make('Enquiry Details')
          ->schema([
            TextInput::make('subject')
              ->required()
              ->maxLength(255)->disabledOn(Operation::Edit->value),
            Textarea::make('message')
              ->required()
              ->rows(5)
              ->columnSpanFull()->disabledOn(Operation::Edit->value),
          ])->columnSpanFull(),

        Section::make('Administration')
          ->schema([
            Select::make('status')
              ->options([
                'new' => 'New',
                'in_progress' => 'In Progress',
                'resolved' => 'Resolved',
                'closed' => 'Closed',
              ])
              ->required()
              ->default('new'),
            RichEditor::make('admin_notes')
              ->placeholder('Add internal notes here...'),
            DateTimePicker::make('responded_at')
              ->label('Responded At'),
          ])->columnSpanFull(),
      ]);
  }
}

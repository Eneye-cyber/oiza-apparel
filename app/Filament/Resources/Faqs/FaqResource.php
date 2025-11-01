<?php

namespace App\Filament\Resources\Faqs;

use App\Filament\Resources\Faqs\Pages\ManageFaqs;
use App\Models\Faq;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class FaqResource extends Resource
{
  protected static ?string $model = Faq::class;

  protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

  protected static string|UnitEnum|null $navigationGroup = "Customer management";

  public static function form(Schema $schema): Schema
  {
    return $schema
      ->components([
        Textarea::make('question')
          ->required()
          ->columnSpanFull(),
        RichEditor::make('answer')
          ->required()
          ->columnSpanFull(),
        Toggle::make('is_active')
          ->label("Visible")
          ->required(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Stack::make([
          Split::make([
            TextColumn::make('question')
              ->label('Questions')->weight(FontWeight::Bold),
            IconColumn::make('is_active')
              ->boolean()->grow(false),
          ]),
          TextColumn::make('answer')->label('Answers')->html(),
        ])->space(2),
      ])
      ->contentGrid([
        'md' => 2,
      ])
      ->filters([
        //
      ])
      ->recordActions([
        EditAction::make(),
        DeleteAction::make(),
      ])
      ->toolbarActions([
        // BulkActionGroup::make([
        //   DeleteBulkAction::make(),
        // ]),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index' => ManageFaqs::route('/'),
    ];
  }
}

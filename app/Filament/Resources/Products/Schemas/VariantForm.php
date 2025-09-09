<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;

class VariantForm
{
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([
        Grid::make(['default' => 12])->schema([

          Group::make()->schema([

            TextInput::make('name')
              ->required()
              ->maxLength(255)
              ->live(onBlur: true)
              ->afterStateUpdated(function ($state, callable $set, callable $get, $livewire) {
                if ($state) {
                  $product = $livewire->getOwnerRecord();
                  $productSlug = $product?->slug ?? '';
                  $set('slug', Str::slug(trim($productSlug . '-' . $state)));
                }
              })
              ->columnSpan(2),

            TextInput::make('price')
              ->numeric()
              ->prefix('₦')
              ->nullable()
              ->minValue(0)
              ->default(fn($get, $livewire) => $livewire->getOwnerRecord()?->discount_price
                ?? $livewire->getOwnerRecord()?->price)
              ->helperText('Overrides product price if set')
              ->columnSpan(2),

            TextInput::make('slug')
              ->label('Slug')
              ->required()
              ->disabled()
              ->dehydrated()
              ->unique('product_variants', 'slug', ignoreRecord: true)
              ->columnSpan(2),

            FileUpload::make('media')
              ->directory('products/variants')
              ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
              ->disk(env('APP_DISK', 'local'))
              ->previewable(true)
              ->maxSize(5120)
              ->required()
              ->columnSpanFull(),

            TextInput::make('max_quantity')
              ->columnSpan(2)
              ->numeric()
              ->nullable()
              ->minValue(1)
              ->default(fn($get, $livewire) => $livewire->getOwnerRecord()?->max_quantity)
              ->helperText('Maximum per order (leave empty to fallback to product)'),

            Select::make('order_type')
              ->columnSpan(2)
              ->label('Order Type')
              ->options([
                'based_on_stock' => 'Based on Stock',
                'unlimited' => 'Unlimited',
                'pre_order' => 'Pre-order',
                'unavailable' => 'Unavailable',
              ])
              ->default(fn($get, $livewire) => $livewire->getOwnerRecord()?->order_type)
              ->reactive()
              ->afterStateUpdated(function ($state, callable $set) {
                if ($state !== 'based_on_stock') {
                  $set('stock_quantity', null);
                }
              }),

            TextInput::make('stock_quantity')
              ->columnSpan(2)
              ->label('Stock Quantity')
              ->numeric()
              ->nullable()
              ->minValue(0)
              ->visible(fn($get) => $get('order_type') === 'based_on_stock')
              ->requiredIf('order_type', 'based_on_stock'),

          ])->columns(6)->columnSpan(['default' => 12]),

        ]),
      ])->columns(1);
  }
}

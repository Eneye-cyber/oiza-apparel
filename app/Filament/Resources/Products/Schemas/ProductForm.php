<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\OrderType;
use App\Enums\ProductStatus;
use App\Models\Attribute;
use App\Models\AttributeType;
use App\Models\Products\Product;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProductForm
{
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([

        Grid::make(['default' => 12])->schema([

          Group::make()->schema([
            Section::make('Product Details')
              ->schema([
                TextInput::make('name')
                  ->required()
                  ->maxLength(255)
                  ->live(onBlur: true)
                  ->afterStateUpdated(function ($state, callable $set) {
                    $set('slug', Str::slug($state));
                  }),
                TextInput::make('slug')
                  ->label('Slug')
                  ->required()
                  ->disabled()
                  ->maxLength(255)
                  ->dehydrated()
                  ->unique(Product::class, 'slug', ignoreRecord: true),

                Select::make('category_id')
                  ->relationship('category', 'name')
                  ->searchable()
                  ->preload()
                  ->required(),

                TextInput::make('main_color')
                  ->maxLength(50)
                  ->nullable(),

                RichEditor::make('description')
                  ->columnSpanFull(),

                TagsInput::make('tags')
                  ->placeholder('Useful for searches and grouping, can be colors or materials')
                  ->columnSpanFull()
                  ->suggestions(['fabrics', 'aso-ebi', 'wedding', 'green', 'white', 'cotton', 'lace'])
                  ->rules([
                    'array',
                    'max:10',
                    'tags.*' => 'max:50',
                  ]),
              ])
              ->columns(2)
              ->columnSpan(['default' => 12, 'md' => 8]),

            Section::make('Product Media')
              ->schema([
                FileUpload::make('cover_media')
                  ->directory('products/covers')
                  ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                  ->previewable(true)
                  ->maxSize(2560)
                  ->disk(env('APP_DISK', 'local'))
                  ->helperText('Main cover image/video (2.5MB max)'),

                FileUpload::make('media')
                  ->multiple()
                  ->directory('products/media')
                  ->disk(env('APP_DISK', 'local'))
                  ->reorderable()
                  ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                  ->previewable(true)
                  ->maxFiles(4)
                  ->maxSize(15360)
                  ->helperText('Additional media files (4 files max, 15MB each)'),
              ])
              ->columns(1)
              ->columnSpan(['default' => 12, 'md' => 8]),

            Section::make('Product Attributes')
              ->schema([
                Repeater::make('attributes')
                  ->relationship('attributes') // 👈 binds to belongsToMany
                  ->collapsible()
                  ->default([])              // 👈 ensures repeater starts empty
                  ->collapsed()              // 👈 optional: collapse items by default
                  ->addActionLabel('Add Attribute')
                  ->schema([

                    // Attribute type (color, size, material...)
                    Select::make('attribute_type_id')
                      ->label('Attribute Type')
                      ->options(AttributeType::all()->pluck('name', 'id'))
                      ->reactive()
                      ->afterStateUpdated(fn($state, callable $set) => $set('attribute_id', null))
                      ->required(),

                    // Attribute value depends on selected type
                    // Select::make('attribute_id')
                    //   ->label('Attribute Value')
                    //   ->options(function (callable $get) {
                    //     $typeId = $get('attribute_type_id');
                    //     return $typeId
                    //       ? Attribute::where('attribute_type_id', $typeId)->pluck('value', 'id')
                    //       : [];
                    //   })
                    //   ->required(),

                    Select::make('value')
                      ->label('Attribute Value')
                      ->options(fn($get) => $get('attribute_type_id')
                        ? Attribute::where('attribute_type_id', $get('attribute_type_id'))->pluck('value', 'id')
                        : [])
                      ->searchable()
                      ->required()
                      ->createOptionForm([
                        TextInput::make('value')->required()->maxLength(255),
                      ])
                      ->createOptionUsing(fn($data, $get) => Attribute::create([
                        'attribute_type_id' => $get('attribute_type_id'),
                        'value'             => $data['value'],
                      ])->id),


                    // Pivot fields
                    TextInput::make('pivot.quantity')
                      ->numeric()
                      ->minValue(0)
                      ->nullable()
                      ->label('Quantity'),

                    TextInput::make('pivot.price')
                      ->numeric()
                      ->minValue(0)
                      ->prefix('₦')
                      ->nullable()
                      ->label('Price Override'),
                  ])
                  ->columns(2),
              ])
              ->columns(1)
              ->columnSpan(['default' => 12, 'md' => 8]),

          ])->columns(1)->columnSpan(['default' => 12, 'md' => 8]),

          Group::make()->schema([
            Section::make('Pricing Information')
              ->schema([
                TextInput::make('price')
                  ->numeric()
                  ->prefix('₦')
                  ->required()
                  ->minValue(0),

                TextInput::make('discount_price')
                  ->numeric()
                  ->prefix('₦')
                  ->nullable()
                  ->minValue(0)
                  ->rule(function ($get) {
                    return $get('discount_price') !== null
                      ? 'lte:' . $get('price')
                      : null;
                  })
                  ->helperText('Must be less than or equal to main price.'),
              ])
              ->columns(1),

            Section::make('Product Status')
              ->schema([
                Checkbox::make('is_active')->default(true),
                Checkbox::make('is_featured')->label('Show on homepage')->default(false),

                Select::make('status')
                  ->options(
                    collect(ProductStatus::cases())
                      ->mapWithKeys(fn($case) => [$case->value => $case->label()])
                  )
                  ->required()
                  ->reactive()
                  ->default(ProductStatus::InStock->value),

                Select::make('order_type')
                  ->label('Order Type')
                  ->options(function (callable $get) {
                    $status = $get('status');
                    return match ($status) {
                      ProductStatus::ComingSoon->value => [OrderType::PreOrder->value => OrderType::PreOrder->label()],
                      ProductStatus::InStock->value => [
                        OrderType::Unlimited->value => OrderType::Unlimited->label(),
                        OrderType::BasedOnStock->value => OrderType::BasedOnStock->label()
                      ],
                      ProductStatus::SoldOut->value => [
                        OrderType::PreOrder->value => OrderType::PreOrder->label(),
                        OrderType::Unavailable->value => OrderType::Unavailable->label()
                      ],
                      default => collect(OrderType::cases())
                        ->mapWithKeys(fn($case) => [$case->value => $case->label()])
                        ->toArray(),
                    };
                  })
                  ->default(function (callable $get) {
                    $status = $get('status');
                    $options = match ($status) {
                      ProductStatus::ComingSoon->value => [OrderType::PreOrder->value],
                      ProductStatus::InStock->value => [OrderType::Unlimited->value, OrderType::BasedOnStock->value],
                      ProductStatus::SoldOut->value => [OrderType::PreOrder->value, OrderType::Unavailable->value],
                      default => collect(OrderType::cases())->pluck('value')->toArray(),
                    };
                    return $options[0] ?? null;
                  })
                  ->reactive()
                  ->afterStateUpdated(function ($state, callable $set) {
                    if ($state !== OrderType::BasedOnStock->value) {
                      $set('stock_quantity', null);
                    }
                  }),

                TextInput::make('stock_quantity')
                  ->label('Stock Quantity')
                  ->numeric()
                  ->nullable()
                  ->minValue(0)
                  ->visible(fn($get) => $get('order_type') === OrderType::BasedOnStock->value)
                  ->requiredIf('order_type', OrderType::BasedOnStock->value)
                  ->helperText('Required when order type is "Based on Stock"'),
              ])
              ->columns(1),

            Section::make('SEO Information')
              ->schema([
                TextInput::make('meta_title')
                  ->maxLength(255)
                  ->helperText('Optimal: 50-60 characters'),
                Textarea::make('meta_description')
                  ->maxLength(65535)
                  ->helperText('Optimal: 150-160 characters'),
                TagsInput::make('meta_keywords')
                  ->helperText('Comma-separated keywords for SEO'),
              ])->columns(1)
          ])->columns(1)->columnSpan(['default' => 12, 'md' => 4]),

          Section::make('Product Variants')->schema([
            Repeater::make('variants')
              ->relationship('variants') // assumes Product hasMany ProductVariant
              ->default([])              // 👈 ensures repeater starts empty
              ->collapsed()              // 👈 optional: collapse items by default
              ->collapsible()
              ->schema([
                TextInput::make('name')
                  ->required()
                  ->maxLength(255)
                  ->live(onBlur: true)
                  ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    if ($state) {
                      $productSlug = $get('../../slug') ?? ''; // go up 2 levels to parent form
                      $set('slug', Str::slug(trim($productSlug . '-' . $state)));
                    }
                  })->columnSpan(2),

                TextInput::make('price')
                  ->numeric()
                  ->prefix('₦')
                  ->nullable()
                  ->minValue(0)
                  ->default(fn($get) => $get('../../price')) // TODO => set it to discount price if given
                  ->helperText('Overrides product price if set')->columnSpan(2),

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

                  ->required()->columnSpanFull(),



                TextInput::make('max_quantity')->columnSpan(2)
                  ->numeric()
                  ->nullable()
                  ->minValue(1)
                  ->helperText('Maximum per order (leave empty to fallback to product)'),

                Select::make('order_type')->columnSpan(2)
                  ->label('Order Type')
                  ->options([
                    'based_on_stock' => 'Based on Stock',
                    'unlimited' => 'Unlimited',
                    'pre_order' => 'Pre-order',
                    'unavailable' => 'Unavailable',
                  ])
                  ->default(fn($get) => $get('../../order_type'))
                  ->reactive()
                  ->afterStateUpdated(function ($state, callable $set) {
                    if ($state !== 'based_on_stock') {
                      $set('stock_quantity', null);
                    }
                  }),

                TextInput::make('stock_quantity')->columnSpan(2)
                  ->label('Stock Quantity')
                  ->numeric()
                  ->nullable()
                  ->minValue(0)
                  ->visible(fn($get) => $get('order_type') === 'based_on_stock')
                  ->requiredIf('order_type', 'based_on_stock'),
              ])->columns(6)
              ->addActionLabel('Add Variant'),
          ])->visibleOn('create')->columns(1)->columnSpan(['default' => 12]),

        ]),

      ])->columns(1);
  }
}

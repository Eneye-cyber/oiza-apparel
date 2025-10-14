<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Products\Product;
use App\Models\Shipping\ShippingCountry;
use App\Models\Shipping\ShippingState;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;
use Illuminate\Support\Facades\Log;

class OrderForm
{
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->schema([
        Section::make('Order Details')
          ->columnSpanFull()
          ->schema(static::getDetailComponents()),

        Section::make('Delivery Details')
          ->columnSpanFull()
          ->schema(static::getAddressComponents('shipping')),

        Section::make('Order Items')
          ->columnSpanFull()
          ->schema([static::getItemsRepeater()]),

        Section::make('Additional Fees')
          ->columnSpanFull()
          ->schema([static::getAdditionalPricing()]),



        Section::make('')
          ->columnSpanFull()
          ->schema([

            Grid::make(['default' => 1, 'md' => 2])
              ->schema([

                TextInput::make('total')
                  ->label('Order Total')
                  ->disabled()
                  ->dehydrated()
                  ->numeric()
                  ->required(),
              ]),

            TextInput::make('subtotal')
              ->label('Product Total')
              ->disabled()
              ->dehydrated()
              ->numeric()
              ->required(),
          ]),

      ]);
  }

  /**
   * Update order subtotal and total calculations
   */
  private static function updateOrderTotals(Get $get, Set $set, string $repeaterPath = 'items', string $subtotalField = 'subtotal', string $totalField = 'total'): void
  {
    // Calculate subtotal from all items
    $items = $get("{$repeaterPath}") ?? [];
    Log::info(['items' => $items]);

    $subtotal = 0;

    foreach ($items as $item) {
      $itemTotal = $item['total'] ?? 0;
      Log::info(['itemTotal' => $itemTotal, 'subtotal' => $subtotal]);

      $subtotal += floatval($itemTotal);
    }
    Log::info(['subtotal' => $subtotal]);

    $set("{$subtotalField}", number_format($subtotal, 2, '.', ''));

    // Calculate total: subtotal + shipping + tax - discount
    $shipping = floatval($get('shipping') ?? 0);
    $discount = floatval($get('discount') ?? 0);
    $tax = floatval($get('tax') ?? 0);

    $total = $subtotal + $shipping + $tax - $discount;
    $set("{$totalField}", number_format($total, 2, '.', ''));
  }

  public static function getItemsRepeater(): Repeater
  {
    return Repeater::make('items')
      ->disabled(fn(string $operation) => $operation === Operation::Edit->value)
      ->relationship('items')
      ->live(false, 300)
      ->deleteAction(
        fn(Action $action) => $action->after(fn(Get $get, Set $set) => static::updateOrderTotals($get, $set)),
      )
      ->table([
        TableColumn::make('Product')
          ->width(400),
        TableColumn::make('Quantity')
          ->width(70),
        TableColumn::make('Unit Price')
          ->width(120),
        TableColumn::make('Line Total')
          ->width(120),
      ])
      ->schema([
        Select::make('product_id')
          ->label('Product')
          ->options(Product::all()->mapWithKeys(fn($product) => [
            $product->id => "
                <div style='display:flex;align-items:center;gap:8px;'>
                    <img src='{$product->cover_media_url}' alt='' width='24' height='24'>
                    <span>{$product->name}</span>
                </div>
            ",
          ]))
          ->allowHtml()
          ->required()
          ->reactive()
          ->afterStateUpdated(function ($state, Set $set, Get $get) {
            $product = Product::find($state);
            if ($product) {
              $price = $product?->discount_price ?? $product?->price ?? 0;
              $set('price', $price);
              $quantity = $get('quantity') ?? 1;
              $line_total = $quantity * $price;
              // $total = $get('../../total') ?? 0;
              // Log::info(["line_total" => $line_total, "total" => $total]);
              $set('total', $line_total);
              static::updateOrderTotals($get, $set, '../../items', '../../subtotal', '../../total');
              // $set('../../total', $total + $line_total);
            }
          })
          ->distinct()
          ->disableOptionsWhenSelectedInSiblingRepeaterItems()
          ->searchable(),

        TextInput::make('quantity')
          ->numeric()
          ->minValue(1)
          ->default(1)
          ->required()
          ->label('Quantity')
          ->reactive()
          ->afterStateUpdated(function ($state, Set $set, Get $get) {
            $price = $get('price') ?? 0;
            $line_total = $state * $price;
            $set('total', $line_total);
            static::updateOrderTotals($get, $set, '../../items', '../../subtotal', '../../total');
          }),

        TextInput::make('price')
          ->label('Unit Price')
          ->disabled()
          ->dehydrated()
          ->reactive()
          ->numeric()
          ->required(),

        TextInput::make('total')
          ->label('Line Total')
          ->disabled()
          ->dehydrated()
          ->numeric()
          ->reactive()
          ->required(),
      ])
      ->extraItemActions([
        // Action::make('openProduct')
        //     ->tooltip('Open product')
        //     ->icon('heroicon-m-arrow-top-right-on-square')
        //     ->url(function (array $arguments, Repeater $component): ?string {
        //         $itemData = $component->getRawItemState($arguments['item']);

        //         $product = Product::find($itemData['shop_product_id']);

        //         if (! $product) {
        //             return null;
        //         }

        //         return ProductResource::getUrl('edit', ['record' => $product]);
        //     }, shouldOpenInNewTab: true)
        //     ->hidden(fn (array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['shop_product_id'])),
      ])
      ->defaultItems(1)
      ->hiddenLabel()
      ->required();
  }

  public static function getAdditionalPricing(): Grid
  {
    return Grid::make(['default' => 1, 'md' => 3])
      ->schema([
        TextInput::make('shipping')
          ->default(0)
          ->numeric()
          ->live(false, 300)
          ->afterStateUpdated(function (Get $get, Set $set) {
            self::updateOrderTotals($get, $set);
          }),

        TextInput::make('discount')
          ->default(0)
          ->numeric()
          ->live(false, 300)
          ->afterStateUpdated(function (Get $get, Set $set) {
            self::updateOrderTotals($get, $set);
          }),
        TextInput::make('tax')
          ->label('VAT')
          ->default(0)
          ->numeric()
          ->live(false, 300)
          ->afterStateUpdated(function (Get $get, Set $set) {
            self::updateOrderTotals($get, $set);
          }),
      ]);
  }

  /**
   * @return array<\Filament\Forms\Components\Component>
   */
  public static function getAddressComponents(string $type): array
  {
    $relationshipName = $type . 'Address';

    return [
      Grid::make(2)
        ->relationship($relationshipName)
        ->schema([

          TextInput::make('name')
            ->label('Full Name')
            ->placeholder('John Doe')
            ->required(),

          TextInput::make('phone')
            ->label('Phone Number')
            ->placeholder('08012345678')
            ->tel()
            ->required(),

          TextInput::make('address')
            ->label('Address')
            ->placeholder('123 Main St')
            ->required()
            ->columnSpanFull(),

          Grid::make(3)
            ->schema([
              TextInput::make('city')
                ->label('City')
                ->placeholder('Gbagada')
                ->required(),

              Select::make('state_id')
                ->options(function (Get $get) {
                  $country = $get('country');

                  if (! $country) {
                    return [];
                  }

                  return ShippingState::whereHas('country', function ($query) use ($country) {
                    $query->where('code', $country);
                  })->pluck('name', 'id');
                })
                ->label('State')
                ->disabled(fn(Get $get) => ! $get('country'))
                ->required()
                ->searchable()
                ->reactive()
                ->afterStateUpdated(function ($state, Set $set) {
                  // Only query if a state was selected
                  if ($state) {
                    $stateName = ShippingState::find($state)?->name;
                    $set('state', $stateName);
                  } else {
                    $set('state', null);
                  }
                }),

              Hidden::make('state')->reactive(),

              Select::make('country')
                ->options(ShippingCountry::pluck('name', 'code'))
                ->label('Country')
                ->default('NG')
                ->required()
                ->reactive()
                ->afterStateUpdated(function (Set $set) {
                  // Reset dependent fields when country changes
                  $set('state_id', null);
                  $set('state', null);
                }),
            ])->columnSpanFull(),
          Hidden::make('type')
            ->default($type),
        ]),
    ];
  }

  /**
   * @return array<\Filament\Forms\Components\Component>
   */
  public static function getDetailComponents(): array
  {
    return [
      Grid::make(2)
        ->schema([

          TextInput::make('guest_email')
            ->email(),
          Select::make('order_channel')
            ->options(['website' => 'Website', 'whatsapp' => 'WhatsApp'])
            ->required(),

          Select::make('payment_method')
            ->options(PaymentMethod::class)
            ->required(),

          Select::make('payment_status')
            ->options(PaymentStatus::class)
            ->default('unpaid')
            ->required(),

          ToggleButtons::make('status')
            ->inline()
            ->options(OrderStatus::class)
            ->default('pending')
            ->required()
            ->columnSpanFull(),
        ]),
    ];
  }
}

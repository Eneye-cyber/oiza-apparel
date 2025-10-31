<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Orders\Order;
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
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Log;

class EditOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Wizard::make([

                    Step::make('Detail')
                        ->completedIcon('heroicon-o-hand-thumb-up')
                        ->schema(static::getDetailComponents()),
                    Step::make('Delivery')
                        ->completedIcon('heroicon-o-hand-thumb-up')
                        ->schema(static::getAddressComponents('shipping')),
                    Step::make('Items')
                        ->completedIcon('heroicon-o-hand-thumb-up')
                        ->columns(1)
                        ->hidden(function (string $operation) {
                            return $operation === Operation::Create;
                        })
                        ->schema([
                            static::getItemsRepeater(),
                        ]),

                ])->columnSpanFull(),

                Section::make('Order Items')
                    ->columnSpanFull()
                    ->schema(static::getOrderItems()),

                Section::make('Pricing')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 3])
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
                            ]),

                        Grid::make(['default' => 1, 'md' => 2])
                            ->schema([
                            TextInput::make('subtotal')
                                    ->label('Sub Total')
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric()
                                    ->required(),

                            TextInput::make('total')
                                    ->label('Order Total')
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric()
                                    ->required(),
                        ]),
                ]),

            ]);
    }

    /**
     * Update order subtotal and total calculations
     */
    private static function updateOrderTotals(Get $get, Set $set): void
    {
        // Calculate subtotal from all items
        $items = $get('items') ?? [];
        Log::info(['items' => $items]);

        $subtotal = 0;

        foreach ($items as $item) {
            $itemTotal = $item['total'] ?? 0;
            Log::info(['itemTotal' => $itemTotal, 'subtotal' => $subtotal]);

            $subtotal += floatval($itemTotal);
        }
        Log::info(['subtotal' => $subtotal]);

        $set('subtotal', number_format($subtotal, 2, '.', ''));

        // Calculate total: subtotal + shipping + tax - discount
        $shipping = floatval($get('shipping') ?? 0);
        $discount = floatval($get('discount') ?? 0);
        $tax = floatval($get('tax') ?? 0);

        $total = $subtotal + $shipping + $tax - $discount;
        $set('total', $total);
    }

    /**
     * @return array<\Filament\Forms\Components\Component>
     */
    public static function getOrderItems(): array
    {

        return [
            Repeater::make('items')
                ->relationship('items')
                ->columns(['default' => 2, 'md' => 4])
                ->schema([
                    Select::make('product_id')
                        ->label('Product')
                        ->searchable()
                        ->options(Product::query()->pluck('name', 'id'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            $product = Product::find($state);
                            if ($product) {
                                $price = $product?->discount_price ?? $product?->price ?? 0;
                                $set('price', $price);
                                $quantity = $get('quantity') ?? 1;
                                $total = $quantity * $price;
                                $set('total', $total);
                            }
                        })
                        ->distinct()
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                    TextInput::make('quantity')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->required()
                        ->label('Quantity')
                        ->reactive()
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            $price = $get('price') ?? 0;
                            $set('total', $state * $price);
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
                ->deleteAction(
                    fn ($action) => $action->after(fn (Get $get, Set $set) => self::updateOrderTotals($get, $set)),
                ),

        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('items')
            ->relationship('items')
            ->table([
                TableColumn::make('Product'),
                TableColumn::make('Quantity')
                    ->width(100),
                TableColumn::make('Unit Price')
                    ->width(110),
            ])
            ->schema([
                Select::make('product_id')
                    ->label('Product')
                    ->options(Product::query()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $product = Product::find($state);
                        if ($product) {
                            $price = $product?->discount_price ?? $product?->price ?? 0;
                            $set('price', $price);
                            $quantity = $get('quantity') ?? 1;
                            $total = $quantity * $price;
                            $set('total', $total);
                        }
                    })
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->searchable(),

                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->default(1)
                    ->required(),

                TextInput::make('price')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
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

    /**
     * @return array<\Filament\Forms\Components\Component>
     */
    public static function getAddressComponents(string $type): array
    {
        $relationshipName = $type.'Address';

        return [
            Grid::make(2)
                ->relationship($relationshipName)
                ->schema([

                    Grid::make(3)
                        ->schema([

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
                                ->disabled(fn (Get $get) => ! $get('country'))
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

                            TextInput::make('city')
                                ->label('City')
                                ->placeholder('Gbagada')
                                ->required(),
                        ])->columnSpanFull(),

                    TextInput::make('address')
                        ->label('Address')
                        ->placeholder('123 Main St')
                        ->required()
                        ->columnSpanFull(),

                    TextInput::make('name')
                        ->label('Full Name')
                        ->placeholder('John Doe')
                        ->required(),

                    TextInput::make('phone')
                        ->label('Phone Number')
                        ->placeholder('08012345678')
                        ->tel()
                        ->required(),

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

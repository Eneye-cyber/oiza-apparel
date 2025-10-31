<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Orders\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')->searchable()->limit(10),
                TextColumn::make('contact_info')
                    ->label('Contact Info')
                    ->html() // ✅ tells Filament to render HTML instead of plain text
                    ->getStateUsing(fn (Order $record) => "
                        <div>
                            <span style='color:oklch(0.552 0.016 285.938);'>{$record->shippingAddress?->name}</span> <br>
                            <span style='color:oklch(0.552 0.016 285.938);'>{$record->shippingAddress?->phone}</span> <br>
                            <x-heroicon-m-envelope class='w-4 h-4 inline text-gray-500' /> {$record->guest_email}
                        </div>
                    ")
                    ->searchable(['guest_email' /* , 'shippingAddress.phone' */])
                    ->wrap(),
                TextColumn::make('status')->badge(),
                TextColumn::make('total')->numeric()->sortable(),
                TextColumn::make('shipping')
                    ->numeric()
                    ->sortable(),

                // TextColumn::make('order_channel')
                //     ->searchable(),
                // TextColumn::make('payment_status')
                //     ->searchable(),
                TextColumn::make('created_at')
                    ->label('Order date')
                    ->date()
                    ->sortable(),
                TextColumn::make('confirmed_at')->label('Payment date')
                    ->wrap()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])->groups([
                Group::make('created_at')
                    ->label('Order date')
                    ->date()
                    ->collapsible(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // ImageColumn::make('image'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('parent.name')
                    ->searchable(),

                TextColumn::make('products_count')
                    ->label('Product Count')
                    ->getStateUsing(fn (Category $record) => $record->getAllProductsCount())
                    ->color(fn ($state) => $state === 0 ? 'danger' : 'gray')
                    ->numeric()
                    ->icon('heroicon-o-shopping-bag'),
                    // ->counts('products')


                ToggleColumn::make('is_active')->label('Show Category'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()->requiresConfirmation()
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ])->defaultSort('parent_id', direction: 'desc');
    }
}

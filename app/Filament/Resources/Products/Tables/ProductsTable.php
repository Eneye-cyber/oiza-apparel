<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->columns([
                ImageColumn::make('cover_media')->disk(env('APP_DISK', 'local'))
                    ->square(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()->limit(50)->wrap()->grow(false),
                TextColumn::make('category.name')
                    ->sortable(),
                TextColumn::make('price')
                    ->prefix('₦')
                    ->sortable(),
                TextColumn::make('stock_quantity')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->boolean(),
                TextColumn::make('rating')
                    ->sortable()->toggleable(true, true),
            ])
            ->filters([
                //
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
                TernaryFilter::make('is_active'),
                TernaryFilter::make('is_featured'),
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Filament\Resources\Products\Schemas\VariantForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;

class ProductVariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';


    public function form(Schema $schema): Schema
    {
        return VariantForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('media')->disk(env('APP_DISK', 'local'))
                    ->square(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('ngn'),
                Tables\Columns\TextColumn::make('stock_quantity'),
                Tables\Columns\TextColumn::make('order_type'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

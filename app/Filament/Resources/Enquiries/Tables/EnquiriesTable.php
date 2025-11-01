<?php

namespace App\Filament\Resources\Enquiries\Tables;

use App\Enums\EnquiryStatus;
use App\Filament\Resources\Enquiries\Actions\CloseAction;
use App\Filament\Resources\Enquiries\Actions\ResolveAction;
use App\Models\Enquiry;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filament\Resources\Enquiries\Actions\RespondAction;
use Filament\Actions\DeleteAction;

class EnquiriesTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->columns([
        //
        TextColumn::make('name')
          ->label('Customer name'),

        TextColumn::make('email')
          ->searchable(),
        TextColumn::make('subject')
          ->searchable()
          ->limit(50)
          ->tooltip(function (TextColumn $column): ?string {
            $state = $column->getState();
            if (strlen($state) <= 50) {
              return null;
            }
            return $state;
          }),

        TextColumn::make('status')
          ->badge(),

        TextColumn::make('created_at')
          ->dateTime('M j, Y g:i A')
          ->sortable()
          ->label('Received'),

        TextColumn::make('responded_at')
          ->dateTime('M j, Y g:i A')
          ->sortable()
          ->placeholder('Not responded'),
      ])
      ->filters([
        SelectFilter::make('status')
          ->options(EnquiryStatus::class),
        Filter::make('created_at')
          ->schema([
            DatePicker::make('created_from')
              ->placeholder('From date'),
            DatePicker::make('created_until')
              ->placeholder('Until date'),
          ])
          ->query(function (Builder $query, array $data): Builder {
            return $query
              ->when(
                $data['created_from'] ?? null,
                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
              )
              ->when(
                $data['created_until'] ?? null,
                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
              );
          })->indicateUsing(function (array $data): array {
            $indicators = [];

            if ($data['created_from'] ?? null) {
              $indicators['created_from'] = 'From: ' . Carbon::parse($data['created_from'])->format('M d, Y');
            }

            if ($data['created_until'] ?? null) {
              $indicators['created_until'] = 'Until: ' . Carbon::parse($data['created_until'])->format('M d, Y');
            }

            return $indicators;
          }),

      ])
      ->recordActions([
        ViewAction::make(),
        DeleteAction::make()->hidden(fn(Enquiry $enquiry) => $enquiry->status != EnquiryStatus::Closed),

        ActionGroup::make([

          // EditAction::make(),
          RespondAction::make(),
          ResolveAction::make(),
          CloseAction::make(),
        ])
      ])
      ->toolbarActions([
        BulkActionGroup::make([
          DeleteBulkAction::make(),
        ]),
      ]);
  }
}

<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class CategoryInfolist
{
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([
        // Main Section: Basic Information
        Section::make('Basic Information')
          ->schema([
            Grid::make(['default' => 1, 'md' => 2, 'xl' => 3])
              ->schema([
                TextEntry::make('name')
                  ->label('Category Name')
                  ->weight('bold')
                  ->icon('heroicon-o-tag')
                  ->color('primary'),
                TextEntry::make('slug')
                  ->label('Slug')
                  ->icon('heroicon-o-link')
                  ->color('gray'),

                  
                IconEntry::make('is_active')
                  ->label('Active Status')
                  ->boolean()
                  ->trueIcon('heroicon-o-check-circle')
                  ->falseIcon('heroicon-o-x-circle')
                  ->trueColor('success')
                  ->falseColor('danger'),
               
                TextEntry::make('parent.name')
                  ->label('Parent Category')
                  ->icon('heroicon-o-folder')
                  ->formatStateUsing(fn($state) => $state ?? 'None (Top-level)')
                  ->color('gray'),

                TextEntry::make('order')
                  ->label('Order')
                  ->numeric()
                  ->icon('heroicon-o-arrows-up-down')
                  ->color('gray'),

                   ImageEntry::make('image')
                  ->label('Category Image')
                  ->defaultImageUrl(asset('img/placeholder.svg'))
                  ->extraImgAttributes(['class' => 'rounded-lg shadow-sm w-full']),



              ]),
          ])
          ->collapsible()
          ->icon('heroicon-o-information-circle'),

        // Section: SEO Settings
        Section::make('SEO Settings')
          ->schema([
            Grid::make(['default' => 1, 'md' => 2])
              ->schema([
                TextEntry::make('meta_title')
                  ->label('Meta Title')
                  ->icon('heroicon-o-document-text')
                  ->formatStateUsing(fn($state) => $state ?? 'N/A')
                  ->color('gray'),
                TextEntry::make('meta_keywords')
                  ->label('Meta Keywords')
                  ->icon('heroicon-o-bookmark')
                  ->formatStateUsing(fn($state) => is_array($state) ? implode(', ', $state) : ($state ?? 'N/A'))
                  ->color('gray'),
                TextEntry::make('meta_description')
                  ->label('Meta Description')
                  ->formatStateUsing(fn($state) => $state ?? 'N/A')
                  ->columnSpan(['md' => 2]),
              ]),
          ])
          ->collapsible()
          ->icon('heroicon-o-globe-alt'),

        // Section: Metadata
        Section::make('Metadata')
          ->schema([
            Grid::make(['default' => 1, 'md' => 3])
              ->schema([
                TextEntry::make('created_at')
                  ->label('Created At')
                  ->dateTime('M j, Y H:i')
                  ->icon('heroicon-o-clock')
                  ->color('gray'),
                TextEntry::make('updated_at')
                  ->label('Updated At')
                  ->dateTime('M j, Y H:i')
                  ->icon('heroicon-o-clock')
                  ->color('gray'),
                TextEntry::make('full_slug_path')
                  ->label('Full Slug Path')
                  ->icon('heroicon-o-link')
                  ->formatStateUsing(fn($state) => $state ?? 'N/A')
                  ->color('gray'),


              ]),
          ])
          ->collapsible()
          ->icon('heroicon-o-cog'),
      ])
      ->columns(1);
  }
}

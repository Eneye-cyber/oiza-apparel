<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([
        Grid::make(['default' => 12])->schema([
          Section::make('Category Information')->schema([
            TextInput::make('name')
              ->required()
              ->maxLength(160)
              ->live(onBlur: true)
              ->afterStateUpdated(function ($state, callable $set) {
                $set('slug', Str::slug($state));
              }),

            TextInput::make('slug')
              ->required()
              ->disabled()
              ->maxLength(255)
              ->dehydrated()
              ->live(onBlur: true)
              ->unique(Category::class, 'slug', ignoreRecord: true),

            Select::make('parent_id')
              ->relationship('parent', 'name')
              ->searchable()
              ->preload()
              ->rule('not_in:' . request()->route('record'))
              ->options(function ($livewire, $get) {
                // Get the current category ID (if editing)
                $currentCategoryId = $livewire->record?->id;

                // Fetch categories that can be parents (depth < 2)
                return Category::query()
                  ->where(function ($query) use ($currentCategoryId) {
                    // Exclude the current category and its descendants
                    if ($currentCategoryId) {
                      $descendantIds = self::getDescendantIds($currentCategoryId);
                      $query->whereNotIn('id', array_merge([$currentCategoryId], $descendantIds));
                    }
                  })
                  ->get()
                  ->filter(function ($category) {
                    // Only include categories with depth < 2
                    return self::getCategoryDepth($category) < 2;
                  })
                  ->pluck('name', 'id')
                  ->prepend('None', null); // Allow selecting no parent
              })
              ->getOptionLabelFromRecordUsing(fn(Category $record) => "{$record->name} (Depth: " . self::getCategoryDepth($record) . ")"),
              // ->hint('Only categories with a depth of 0 or 1 can be selected as parents to maintain a maximum tree depth of 3.'),

            TextInput::make('order')
              ->required()
              ->numeric()
              ->minValue(0)
              ->default(fn() => Category::max('order') + 1), // Use max(order) + 1,

            Textarea::make('description')
              ->columnSpanFull(),
            FileUpload::make('image')
              ->image()
              ->imagePreviewHeight('150')
              ->maxSize(2048) // Limit to 2MB
              ->imageResizeMode('cover')
              ->imageCropAspectRatio('1:1')
              ->columnSpanFull(),

          ])->columns(2)->columnSpan(['default' => 12, 'md' => 8]),

          Group::make()->schema([

            Section::make()->schema([

              Checkbox::make('is_active')
                ->required()
                ->default(true),

            ])->columnSpanFull(),

            Section::make('SEO Settings')->schema([
              TextInput::make('meta_title'),
              Textarea::make('meta_description')
                ->maxLength(160)
                ->default(0)
                ->columnSpanFull(),

              TagsInput::make('meta_keywords'),

            ])->columnSpanFull()
          ])->columnSpan(['default' => 12, 'md' => 4]),
        ]),

      ])->columns(1);
  }

  /**
   * Calculate the depth of a category in the tree.
   *
   * @param Category $category
   * @return int
   */
  private static function getCategoryDepth(Category $category): int
  {
    $depth = 0;
    $current = $category;

    while ($current->parent_id) {
      $current = $current->parent;
      $depth++;
    }

    return $depth;
  }

  /**
   * Get all descendant IDs of a category.
   *
   * @param int $categoryId
   * @return array
   */
  private static function getDescendantIds(int $categoryId): array
  {
    $descendantIds = [];
    $children = Category::where('parent_id', $categoryId)->pluck('id')->toArray();

    foreach ($children as $childId) {
      $descendantIds[] = $childId;
      $descendantIds = array_merge($descendantIds, self::getDescendantIds($childId));
    }

    return $descendantIds;
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Products\Product;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'name',
    //     'slug',
    //     'parent_id',
    //     'description',
    //     'image',
    //     'meta_title',
    //     'meta_description',
    //     'meta_keywords',
    //     'order',
    //     'is_active',
    //     'full_slug_path',
    // ];

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'meta_keywords' => 'array'
    ];

    protected static function booted()
    {
        static::saving(function ($category) {
            $slugs = [];
            $current = $category;

            // Collect slugs up the hierarchy
            while ($current) {
                array_unshift($slugs, $current->slug);
                $current = $current->parent;
            }

            $category->full_slug_path = implode('/', $slugs);
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }


    // Helper to get image URL
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk(env('APP_DISK', 'local'))->url($this->image) : null;
    }
    /**
     * Get all products from this category and all descendant categories
     */
    public function getAllProducts(): Collection
    {
        $categoryIds = $this->getAllDescendantIds();
        $categoryIds[] = $this->id;

        return Product::whereIn('category_id', $categoryIds)->get();
    }

    /**
     * Get all products count from this category and all descendant categories
     */
    public function getAllProductsCount(): int
    {
        $categoryIds = $this->getAllDescendantIds();
        $categoryIds[] = $this->id;

        return Product::whereIn('category_id', $categoryIds)->count();
    }

    /**
     * Get all descendant category IDs (including nested children)
     */
    public function getAllDescendantIds(): array
    {
        $ids = [];
        
        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllDescendantIds());
        }

        return $ids;
    }

    /**
     * Get query builder for all products from this category and descendants
     */
    public function getAllProductsQuery()
    {
        $categoryIds = $this->getAllDescendantIds();
        if (empty($categoryIds)) {
            return Product::where('category_id', $this->id);
        }
        $categoryIds[] = $this->id;
        return Product::whereIn('category_id', $categoryIds);
    }

    /**
     * Get all products from this category and all descendant categories (relationship)
     */
    public function allProducts(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            Category::class,
            'parent_id', // Foreign key on categories table
            'category_id', // Foreign key on products table
            'id', // Local key on categories table
            'id' // Local key on categories table for the relationship
        )->orWhere('products.category_id', $this->id);
    }

    // /**
    //  * Get the full URL path for the category (e.g., /fabrics/ankara/kente).
    //  */
    // public function getFullSlugPathAttribute(): string
    // {
    //     $slugs = [];
    //     $current = $this;

    //     // Traverse up the hierarchy to collect slugs
    //     while ($current) {
    //         array_unshift($slugs, $current->slug);
    //         $current = $current->parent;
    //     }

    //     return '/' . implode('/', $slugs);
    // }
}

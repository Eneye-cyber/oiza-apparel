<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'order',
        'is_active',
        'full_slug_path',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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


    // Helper to get image URL
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
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

<?php

namespace App\Models\Products;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'name',
        'main_color',
        'slug',
        'full_slug_path',
        'category_id',
        'description',
        'cover_media',
        'media',
        'meta_title',
        'meta_description',
        'tags',
        'is_active',
        'price',
        'discount_price',
        'stock_quantity',
        'rating',
        'max_quantity',
        'is_featured',
        'meta_keywords',
        'status',
        'order_type',
        'rating',
    ];

    protected $casts = [
        'media' => 'array',
        'tags' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'rating' => 'float',
        'max_quantity' => 'integer',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }


}

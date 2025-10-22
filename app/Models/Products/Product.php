<?php

namespace App\Models\Products;

use App\Enums\OrderType;
use App\Enums\ProductStatus;
use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class Product extends Model
{
    //
    use HasFactory;
    // protected $fillable = [
    //     'name',
    //     'main_color',
    //     'slug',
    //     'full_slug_path',
    //     'category_id',
    //     'description',
    //     'cover_media',
    //     'media',
    //     'meta_title',
    //     'meta_description',
    //     'tags',
    //     'is_active',
    //     'price',
    //     'discount_price',
    //     'stock_quantity',
    //     'rating',
    //     'max_quantity',
    //     'is_featured',
    //     'meta_keywords',
    //     'status',
    //     'order_type',
    //     'rating',
    // ];
    protected $guarded = [];

    protected $casts = [
        'media' => 'array',
        'tags' => 'array',
        'meta_keywords' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'rating' => 'float',
        'max_quantity' => 'integer',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => ProductStatus::class,
        'order_type' => OrderType::class,
    ];

    // ensures that cover_media_url is automatically included when you access the model as an array/JSON 
    protected $appends = ['cover_media_url'];
    // defines a computed attribute
    public function getCoverMediaUrlAttribute()
    {
        return $this->cover_media
            ? Storage::disk(env('APP_DISK', 'local'))->url($this->cover_media)
            : null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_product')
            ->withPivot(['quantity', 'price'])
            ->using(\App\Models\Pivots\AttributeProduct::class)
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->whereNot(function ($q) {
            $this->applyInactiveConstraints($q);
        });
    }

    public function scopeInactive($query)
    {
        return $query->where(function ($q) {
            $this->applyInactiveConstraints($q);
        });
    }

    /**
     * Apply inactive product conditions.
     */
    private function applyInactiveConstraints($query): void
    {
        $query->where('is_active', false)
            ->orWhere(function ($q2) {
                $q2->where('status', ProductStatus::InStock)
                    ->where('order_type', OrderType::BasedOnStock)
                    ->where('stock_quantity', '<=', 0);
            })
            ->orWhere(function ($q2) {
                $q2->where('status', ProductStatus::SoldOut)
                    ->where('order_type', OrderType::Unavailable);
            });
    }

    public function getIsActuallyInactiveAttribute(): bool
    {
        if (! $this->is_active) {
            return true;
        }

        if ($this->status === ProductStatus::InStock && $this->stock_quantity <= 0) {
            return true;
        }

        if ($this->status === ProductStatus::SoldOut && $this->order_type === OrderType::Unavailable) {
            return true;
        }

        return false;
    }

    public function getIsActuallyActiveAttribute(): bool
    {
        return ! $this->is_actually_inactive;
    }

    /**
     * Self-referential many-to-many for related products.
     * Excludes self by default in queries.
     */
    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_related', 'product_id', 'related_id')
            ->where('related_id', '!=', $this->id); // Exclude self
    }

    /**
     * Get related products: Prioritize manual, fallback to tag-based similarity.
     * Cached for 1 hour (3600 seconds) to reduce DB load.
     *
     * @param int $limit
     * @return Collection
     */
    public function getRelatedProducts(int $limit = 4): Collection
    {
        $cacheKey = "related_products_{$this->id}_{$limit}";

        return Cache::remember($cacheKey, 3600, function () use ($limit) {
            // Get manual related products
            $manualRelated = $this->relatedProducts()->take($limit)->get();

            if ($manualRelated->count() > 0) {
                return $manualRelated;
            }

            // Fallback: Get the product's tag IDs (eager-load to avoid N+1)

            $originalTags = $this->tags ?? [];

            if (empty($originalTags)) {
                return collect();
            }

            // Query for similar products: Share at least one tag, ordered by shared count
            $candidates = Product::where('id', '!=', $this->id)
                ->where(function ($query) use ($originalTags) {
                    foreach ($originalTags as $tag) {
                        $query->orWhereJsonContains('tags', $tag);
                    }
                })
                ->limit(20) // Fetch more candidates than needed for better sorting options
                ->get();

            // Compute shared count in PHP and sort (portable and efficient for small datasets)
            $similarProducts = $candidates->sortByDesc(function ($candidate) use ($originalTags) {
                $candidateTags = $candidate->tags ?? [];
                return count(array_intersect($candidateTags, $originalTags));
            })->take(4); // Limit to top 4 by shared count
            return $similarProducts;
        });
    }
}

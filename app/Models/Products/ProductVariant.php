<?php

namespace App\Models\Products;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductVariant extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'slug',
        'name',
        'media',
        'price',
        'max_quantity',
        'order_type',
        'stock_quantity',
    ];

    protected $appends = ['media_url'];
    // defines a computed attribute
    public function getMediaUrlAttribute()
    {
        return $this->media
            ? Storage::disk(env('APP_DISK', 'local'))->url($this->media)
            : null;
    }


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // 🔗 Many-to-Many with attributes
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_variant')
            ->withPivot(['quantity', 'price'])
            ->using(\App\Models\Pivots\AttributeVariant::class)
            ->withTimestamps();
    }

}

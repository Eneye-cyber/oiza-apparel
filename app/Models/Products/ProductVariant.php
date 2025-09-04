<?php

namespace App\Models\Products;

use App\Models\Attribute;
use Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ProductVariantFactory::new();
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

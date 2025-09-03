<?php

namespace App\Models;

use App\Models\Products\ProductVariant;
use App\Models\Products\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['attribute_type_id', 'value'];

    // 🔗 Attribute belongs to a type
    public function type()
    {
        return $this->belongsTo(AttributeType::class, 'attribute_type_id');
    }

    // 🔗 Many-to-Many with products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'attribute_product')
            ->withPivot(['quantity', 'price'])
            ->using(\App\Models\Pivots\AttributeProduct::class)
            ->withTimestamps();
    }

    // 🔗 Many-to-Many with variants
    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'attribute_variant')
            ->withPivot(['quantity', 'price'])
            ->using(\App\Models\Pivots\AttributeVariant::class)
            ->withTimestamps();
    }
}

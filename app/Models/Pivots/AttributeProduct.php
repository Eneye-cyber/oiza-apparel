<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AttributeProduct extends Pivot
{
  protected $table = 'attribute_product';

  protected $fillable = ['product_id', 'attribute_id', 'quantity', 'price'];
}

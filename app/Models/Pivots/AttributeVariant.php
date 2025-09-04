<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AttributeVariant extends Pivot
{
  protected $table = 'attribute_variant';

  protected $fillable = ['variant_id', 'attribute_id', 'quantity', 'price'];
}
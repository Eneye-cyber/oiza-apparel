<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttributeType extends Model
{
  use HasFactory;

  protected $fillable = ['name'];

  // 🔗 One type has many attributes
  public function attributes()
  {
    return $this->hasMany(Attribute::class);
  }
}

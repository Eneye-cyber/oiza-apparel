<?php

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributeType;

class AttributeSeeder extends Seeder
{
  public function run(): void
  {
    $types = [
      'color' => [
        'Red', 'Blue', 'Green', 'Black', 'White', 'Gray', 'Navy', 'Pink',
        'Yellow', 'Beige', 'Brown', 'Purple', 'Orange', 'Maroon',
        'Teal', 'Olive', 'Cream', 'Sky Blue', 'Gold', 'Silver'
      ],
      'size' => [
        'XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'
      ],
      'length' => [
        'Short', 'Knee Length', 'Ankle Length', 'Full Length',
        '1 yard', '3 yards', '6 yards', '10 yards', '12 yards', '20 yards'
      ],
      'material' => [
        'Cotton', 'Linen', 'Polyester', 'Wool', 'Silk',
        'Denim', 'Leather', 'Nylon', 'Rayon', 'Spandex'
      ]
    ];

    foreach ($types as $type => $values) {
      $typeModel = AttributeType::firstOrCreate(['name' => $type]);

      foreach ($values as $value) {
        Attribute::firstOrCreate([
          'attribute_type_id' => $typeModel->id,
          'value' => $value
        ]);
      }
    }
  }
}

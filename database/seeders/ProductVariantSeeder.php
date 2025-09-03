<?php

namespace Database\Seeders;

use App\Models\Products\Product;
use App\Models\Products\ProductVariant;
use App\Models\Products\ProductVariantAttribute;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        // Create Product
        // $product = Product::create(array_merge($productData, [
        //     'slug' => Str::slug($productData['name']), // required slug
        // ]));

        // // Create Variant for this product
        // $variant = $product->variants()->create([
        //     'sku'            => strtoupper(Str::random(8)),
        //     'slug'           => $product->slug . '-default',
        //     'name'           => $product->name . ' - Default',
        //     'media'          => $product->cover_media,
        //     'price'          => $product->price,
        //     'max_quantity'   => 10,
        //     'order_type'     => 'based_on_stock',
        //     'stock_quantity' => 100,
        // ]);


    }
}

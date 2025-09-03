<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
             $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            $table->string('sku')->nullable()->unique()->comment('Stock keeping unit for variant');
            $table->string('slug')->unique()->comment('combination of product slug and variant identifier or name e.g royal-blue-xl');
            $table->string('name')->comment('name e.g Red - XL');
            
            $table->string('media')->nullable()->comment('Variant media');
            $table->decimal('price', 10, 2)->nullable()->comment('Overrides product price if set');
            $table->integer('max_quantity')->nullable()->comment('Max order quantity for this variant (null = fallback to product)');
            
            $table->enum('order_type', ['based_on_stock', 'unlimited', 'pre_order', 'unavailable'])
                ->nullable()
                ->default('based_on_stock')
                ->comment('Order rules for this variant');

            $table->integer('stock_quantity')->default(0)->comment('Available stock if order_type=based_on_stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};

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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('main_color')->nullable(); // main color for the product
            $table->string('slug')->unique(); // url unique identifier
            $table->string('full_slug_path')->unique()->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->text('description')->nullable(); // Short description of the category to be displayed on the category listing page
            $table->string('cover_media')->nullable()->comment('Featured image for product');
            $table->json('media')->nullable(); // JSON array of additional images for the product
            $table->string('meta_title')->nullable()->comment('SEO title for the category ');
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->json('tags')->nullable()->comment('JSON array of tags for the product');
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['in_stock', 'sold_out', 'coming_soon'])->default('in_stock')
            ->comment('Product availability status: in stock, sold out, or coming soon');
            $table->enum('order_type', ['based_on_stock', 'unlimited', 'pre_order', 'unavailable'])
                ->default('unlimited')
                ->comment('Order rules for this product: based_on_stock, unlimited, pre_order, unavailable');

            $table->integer('stock_quantity')->nullable()->default(0)->comment('Available stock if order_type=based_on_stock');

            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('max_quantity')->default(5)->comment('Maximum quantity allowed for purchase');
            $table->boolean('is_featured')->default(false)->comment('Is the product featured on the homepage');

            $table->timestamps();

            // Indexes for faster lookups
            $table->index('slug');
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

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
        Schema::create('shipping_country_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('shipping_countries')->onDelete('cascade');
            $table->foreignId('method_id')->constrained('shipping_methods')->onDelete('cascade');

            $table->decimal('delivery_cost', 10, 2)->default(0);
            $table->decimal('free_shipping_minimum', 10, 2)->nullable();

            $table->integer('delivery_min_days')->nullable(); // e.g. 2
            $table->integer('delivery_max_days')->nullable(); // e.g. 5

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_country_methods');
    }
};

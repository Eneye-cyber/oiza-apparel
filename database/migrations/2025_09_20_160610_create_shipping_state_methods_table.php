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
        Schema::create('shipping_state_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('shipping_states')->onDelete('cascade');
            $table->foreignId('method_id')->constrained('shipping_methods')->onDelete('cascade');

            $table->decimal('delivery_cost', 10, 2)->default(0);
            $table->decimal('free_shipping_minimum', 10, 2)->nullable();

            $table->integer('delivery_min_days')->nullable();
            $table->integer('delivery_max_days')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_state_methods');
    }
};

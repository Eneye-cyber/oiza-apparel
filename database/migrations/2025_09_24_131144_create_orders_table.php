<?php

use App\Enums\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_number')->unique();

            // Guest info
            $table->string('guest_email')->nullable();

            // Order amounts
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // Status + payment
            $table->string('status')->default(OrderStatus::Pending->value);
            $table->string('payment_method')->nullable(); // changed from enum for flexibility
            $table->enum('order_channel', ['website', 'whatsapp'])->nullable();
             $table->string('payment_status')->default('unpaid'); // changed from enum for flexibility;

            $table->string('tracking_token')->nullable()->unique();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

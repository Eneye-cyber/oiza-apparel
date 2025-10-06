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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('transaction_ref')->nullable()->after('payment_status');
            $table->string('shipping_type')->nullable()->after('transaction_ref');
            $table->integer('delivery_min_days')->nullable()->after('shipping_type');
            $table->integer('delivery_max_days')->nullable()->after('delivery_min_days');
            $table->timestamp('delivered_at')->nullable()->after('delivery_max_days');
            $table->foreignId('cart_id')->nullable()->constrained('carts', 'id')->nullOnDelete()->after('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_ref',
                'shipping_type',
                'delivery_min_days',
                'delivery_max_days',
                'delivered_at',
            ]);
            if (Schema::hasColumn('orders', 'cart_id')) {
                $table->dropColumn([
                    'cart_id'
                ]);
            }
        });
    }
};

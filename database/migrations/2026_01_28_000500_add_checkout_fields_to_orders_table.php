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
            // Alamat & Pengiriman
            $table->string('customer_name')->nullable()->after('user_id');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->text('delivery_address')->nullable()->after('customer_phone');
            $table->string('delivery_city')->nullable()->after('delivery_address');
            $table->string('delivery_postal_code')->nullable()->after('delivery_city');
            
            // Metode Pengiriman
            $table->enum('shipping_method', ['kurir', 'pickup'])->default('kurir')->after('delivery_postal_code');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_method');
            
            // Metode Pembayaran
            $table->enum('payment_method', ['transfer', 'ewallet', 'cod'])->default('transfer')->after('shipping_cost');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->after('payment_method');
            
            // Update total_price untuk include shipping
            $table->text('notes')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_phone',
                'delivery_address',
                'delivery_city',
                'delivery_postal_code',
                'shipping_method',
                'shipping_cost',
                'payment_method',
                'payment_status',
                'notes',
            ]);
        });
    }
};

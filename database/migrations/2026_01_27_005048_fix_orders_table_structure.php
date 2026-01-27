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
            // Drop old columns
            $table->dropColumn([
                'total_price',
                'shipping_courier',
                'shipping_service',
                'shipping_address',
                'tracking_number',
                'customer_name',
                'customer_phone',
                'customer_email'
            ]);
            
            // Add new columns
            $table->string('email')->after('order_number');
            $table->string('name')->after('email');
            $table->string('phone')->after('name');
            $table->text('address')->after('phone');
            $table->string('province_id')->after('address');
            $table->string('city_id')->after('province_id');
            $table->string('postal_code')->after('city_id');
            $table->string('payment_method')->after('postal_code');
            $table->decimal('subtotal', 12, 2)->after('payment_method');
            
            // Modify existing columns
            $table->decimal('shipping_cost', 12, 2)->default(0)->change();
            
            // Add total column
            $table->decimal('total', 12, 2)->after('shipping_cost');
            
            // Modify status column
            $table->dropColumn('status');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending')->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert changes
            $table->dropColumn([
                'email',
                'name',
                'phone',
                'address',
                'province_id',
                'city_id',
                'postal_code',
                'payment_method',
                'subtotal',
                'total',
                'status'
            ]);
            
            // Add back old columns
            $table->decimal('total_price', 12, 0)->after('order_number');
            $table->string('shipping_courier')->nullable()->after('status');
            $table->string('shipping_service')->nullable()->after('shipping_courier');
            $table->text('shipping_address')->after('shipping_cost');
            $table->string('tracking_number')->nullable()->after('shipping_address');
            $table->string('customer_name')->after('tracking_number');
            $table->string('customer_phone')->after('customer_name');
            $table->string('customer_email')->after('customer_phone');
            $table->string('status')->default('pending');
            
            $table->decimal('shipping_cost', 12, 0)->default(0)->change();
        });
    }
};

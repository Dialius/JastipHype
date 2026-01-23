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
        // Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('total_price', 12, 0); // Storing as integer/decimal without cents for IDR usually, but decimal is safer
            $table->string('status')->default('pending'); // pending, processing, shipped, completed, cancelled
            
            // Shipping Info
            $table->string('shipping_courier')->nullable();
            $table->string('shipping_service')->nullable();
            $table->decimal('shipping_cost', 12, 0)->default(0);
            $table->text('shipping_address'); // Snapshot of full address
            $table->string('tracking_number')->nullable();
            
            // Contact Info Snapshot
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email');

            $table->timestamps();
        });

        // Order Items Table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Snapshot in case product is deleted/renamed
            $table->string('size');
            $table->integer('quantity');
            $table->decimal('price', 12, 0); // Price per item at time of purchase
            $table->decimal('subtotal', 12, 0);
            $table->timestamps();
        });

        // Payments Table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_type'); // manual_transfer, midtrans, etc.
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, failed, expired
            
            // For Manual Transfer
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('payment_proof')->nullable(); // Path to image
            
            // For Payment Gateway
            $table->string('transaction_id')->nullable();
            $table->json('payment_details')->nullable(); // Response from gateway

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

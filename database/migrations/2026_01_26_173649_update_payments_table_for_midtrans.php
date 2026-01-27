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
        Schema::table('payments', function (Blueprint $table) {
            // Rename payment_status to transaction_status for Midtrans compatibility
            if (Schema::hasColumn('payments', 'payment_status') && !Schema::hasColumn('payments', 'transaction_status')) {
                $table->renameColumn('payment_status', 'transaction_status');
            }
            
            // Rename payment_details to payment_data for consistency
            if (Schema::hasColumn('payments', 'payment_details') && !Schema::hasColumn('payments', 'payment_data')) {
                $table->renameColumn('payment_details', 'payment_data');
            }
            
            // Add gross_amount column
            if (!Schema::hasColumn('payments', 'gross_amount')) {
                $table->decimal('gross_amount', 12, 2)->nullable()->after('payment_type');
            }
            
            // Add payment_code for VA numbers, payment codes, etc
            if (!Schema::hasColumn('payments', 'payment_code')) {
                $table->string('payment_code')->nullable()->after('gross_amount');
            }
            
            // Add fraud_status
            if (!Schema::hasColumn('payments', 'fraud_status')) {
                $table->string('fraud_status')->nullable()->after('payment_code');
            }
            
            // Add time-related columns
            if (!Schema::hasColumn('payments', 'transaction_time')) {
                $table->timestamp('transaction_time')->nullable()->after('fraud_status');
            }
            if (!Schema::hasColumn('payments', 'settlement_time')) {
                $table->timestamp('settlement_time')->nullable()->after('transaction_time');
            }
            if (!Schema::hasColumn('payments', 'expiry_time')) {
                $table->timestamp('expiry_time')->nullable()->after('settlement_time');
            }
            
            // Add URLs for QR codes, deeplinks, PDFs
            if (!Schema::hasColumn('payments', 'qr_code_url')) {
                $table->text('qr_code_url')->nullable()->after('payment_data');
            }
            if (!Schema::hasColumn('payments', 'deeplink_redirect')) {
                $table->text('deeplink_redirect')->nullable()->after('qr_code_url');
            }
            if (!Schema::hasColumn('payments', 'pdf_url')) {
                $table->text('pdf_url')->nullable()->after('deeplink_redirect');
            }
        });
        
        // Add indexes in a separate statement to avoid issues
        Schema::table('payments', function (Blueprint $table) {
            $table->index('transaction_id');
            $table->index('transaction_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['transaction_id']);
            $table->dropIndex(['transaction_status']);
        });
        
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'gross_amount',
                'payment_code',
                'fraud_status',
                'transaction_time',
                'settlement_time',
                'expiry_time',
                'qr_code_url',
                'deeplink_redirect',
                'pdf_url'
            ]);
            
            // Rename back
            if (Schema::hasColumn('payments', 'transaction_status')) {
                $table->renameColumn('transaction_status', 'payment_status');
            }
            if (Schema::hasColumn('payments', 'payment_data')) {
                $table->renameColumn('payment_data', 'payment_details');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cookie consent tracking
        Schema::create('cookie_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->boolean('necessary')->default(true);
            $table->boolean('functional')->default(false);
            $table->boolean('analytics')->default(false);
            $table->boolean('marketing')->default(false);
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('session_id');
        });

        // Data export requests
        Schema::create('data_export_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('file_path')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });

        // Data deletion requests
        Schema::create('data_deletion_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'processing', 'completed', 'rejected'])->default('pending');
            $table->text('reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });

        // Add GDPR fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('gdpr_consent_at')->nullable()->after('email_verified_at');
            $table->timestamp('marketing_consent_at')->nullable()->after('gdpr_consent_at');
            $table->timestamp('data_retention_notified_at')->nullable()->after('marketing_consent_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gdpr_consent_at', 'marketing_consent_at', 'data_retention_notified_at']);
        });
        
        Schema::dropIfExists('data_deletion_requests');
        Schema::dropIfExists('data_export_requests');
        Schema::dropIfExists('cookie_consents');
    }
};

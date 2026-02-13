<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Login attempts tracking
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('ip_address');
            $table->boolean('successful')->default(false);
            $table->text('user_agent')->nullable();
            $table->timestamp('attempted_at');
            
            $table->index(['email', 'attempted_at']);
            $table->index(['ip_address', 'attempted_at']);
        });

        // Security events log
        Schema::create('security_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('event_type'); // password_change, email_change, 2fa_enabled, etc
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'event_type', 'created_at']);
        });

        // Blocked IPs
        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->unique();
            $table->text('reason');
            $table->foreignId('blocked_by')->nullable()->constrained('users');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index('ip_address');
        });

        // Session management
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->unique();
            $table->string('ip_address');
            $table->text('user_agent');
            $table->timestamp('last_activity');
            $table->timestamps();
            
            $table->index(['user_id', 'last_activity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
        Schema::dropIfExists('blocked_ips');
        Schema::dropIfExists('security_events');
        Schema::dropIfExists('login_attempts');
    }
};

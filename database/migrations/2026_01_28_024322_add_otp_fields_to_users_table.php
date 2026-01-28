<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // OTP for password reset
            $table->string('password_reset_otp', 6)->nullable()->after('remember_token');
            $table->timestamp('password_reset_otp_expires_at')->nullable()->after('password_reset_otp');
            
            // OTP for password change (from profile)
            $table->string('password_change_otp', 6)->nullable()->after('password_reset_otp_expires_at');
            $table->timestamp('password_change_otp_expires_at')->nullable()->after('password_change_otp');
            $table->string('pending_password')->nullable()->after('password_change_otp_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'password_reset_otp',
                'password_reset_otp_expires_at',
                'password_change_otp',
                'password_change_otp_expires_at',
                'pending_password'
            ]);
        });
    }
};

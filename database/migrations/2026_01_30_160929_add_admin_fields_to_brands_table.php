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
        Schema::table('brands', function (Blueprint $table) {
            $table->string('status')->default('active')->after('is_featured'); // active, inactive
            $table->integer('display_order')->default(0)->after('status');
            $table->string('logo_path')->nullable()->after('logo');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn(['status', 'display_order', 'logo_path']);
            $table->dropSoftDeletes();
        });
    }
};

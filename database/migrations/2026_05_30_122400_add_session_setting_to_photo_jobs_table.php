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
        Schema::table('photo_jobs', function (Blueprint $table) {
            $table->foreignId('photo_session_id')->nullable()->constrained('photo_sessions')->nullOnDelete();
            $table->foreignId('photo_setting_id')->nullable()->constrained('photo_settings')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photo_jobs', function (Blueprint $table) {
            $table->dropForeign(['photo_session_id']);
            $table->dropForeign(['photo_setting_id']);
            $table->dropColumn(['photo_session_id', 'photo_setting_id']);
        });
    }
};

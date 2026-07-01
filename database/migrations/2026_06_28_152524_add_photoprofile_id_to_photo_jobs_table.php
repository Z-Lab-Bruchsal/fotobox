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
            $table->foreignId('photoprofile_id')->nullable()->constrained('photoprofiles')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photo_jobs', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Photoprofile::class);
        });
    }
};

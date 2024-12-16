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
        Schema::table('thesis_assets', function (Blueprint $table) {
            $table->boolean('is_approved')->default(0)->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_assets', function (Blueprint $table) {
            $table->dropColumn(['is_approved']);
        });
    }
};

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
        Schema::table('conferences', function (Blueprint $table) {
            $table->boolean('schedule_is_published')->default(0)->after('thesis_edit_until');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropColumn('schedule_is_published');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->boolean('is_published')->default(0)->after('end_time');
        });
    }
};

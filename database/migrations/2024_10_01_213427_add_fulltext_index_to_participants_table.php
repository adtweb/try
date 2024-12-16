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
        Schema::table('participants', function (Blueprint $table) {
            $table->fullText(['name_ru', 'surname_ru'], 'participants_name_ru_fulltext');
            $table->fullText(['name_en', 'surname_en'], 'participants_name_en_fulltext');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropFullText('participants_name_ru_fulltext');
            $table->dropFullText('participants_name_en_fulltext');
        });
    }
};

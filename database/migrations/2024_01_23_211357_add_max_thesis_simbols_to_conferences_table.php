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
            $table->unsignedSmallInteger('max_thesis_characters')
                ->after('abstracts_lang')
                ->default(5000);

            $table->text('thesis_instruction')
                ->after('max_thesis_characters')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropColumn('max_thesis_characters');
            $table->dropColumn('thesis_instruction');
        });
    }
};

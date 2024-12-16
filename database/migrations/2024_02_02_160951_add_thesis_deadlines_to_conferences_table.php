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
            $table->date('thesis_accept_until')
                ->nullable()
                ->after('thesis_instruction');
            $table->date('thesis_edit_until')
                ->nullable()
                ->after('thesis_accept_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropColumn('thesis_accept_until');
            $table->dropColumn('thesis_edit_until');
        });
    }
};

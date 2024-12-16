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
        Schema::table('moderables', function (Blueprint $table) {
            $table->dropColumn(['moderable_type', 'name', 'surname', 'created_at', 'updated_at']);

            $table->renameColumn('moderable_id', 'section_id');
            $table->foreign('section_id')
                ->references('id')
                ->on('sections')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->rename('moderators');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('moderators', function (Blueprint $table) {
            $table->after('section_id', function (Blueprint $table) {
                $table->string('moderable_type');
                $table->string('name');
                $table->string('surname');
            });
            $table->timestamps();

            $table->dropConstrainedForeignId('section_id');
            $table->renameColumn('section_id', 'moderable_id');
            $table->rename('moderables');
        });
    }
};

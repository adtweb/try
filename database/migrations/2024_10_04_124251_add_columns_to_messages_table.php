<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Domains\Auth\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(User::class);
            $table->after('id', function (Blueprint $table) {
                $table->unsignedBigInteger('chatable_id');
                $table->string('chatable_type', 50);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['chatable_type', 'chatable_id']);
            $table->foreignIdFor(User::class)->after('id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }
};

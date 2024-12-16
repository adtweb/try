<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Domains\Auth\Models\User;
use Src\Domains\Conferences\Models\Conference;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->foreignIdFor(User::class)
                ->after('organization_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });

        $conferences = Conference::with('organization')->get();

        foreach ($conferences as $conference) {
            $conference->user_id = $conference->organization->user_id;
            $conference->save();
        }

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(User::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(User::class);
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }
};

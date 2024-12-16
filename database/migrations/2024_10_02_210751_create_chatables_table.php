<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Messenger\Models\Chat;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chatables', function (Blueprint $table) {
            $table->foreignIdFor(Chat::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('chatable_id')->index();
            $table->string('chatable_type')->index();
            $table->foreignIdFor(Conference::class)->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('moderator_user_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatables');
    }
};

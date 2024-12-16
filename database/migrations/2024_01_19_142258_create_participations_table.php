<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Domains\Auth\Models\Participant;
use Src\Domains\Conferences\Models\Conference;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('participations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Participant::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Conference::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name_ru');
            $table->string('surname_ru');
            $table->string('middle_name_ru')->nullable();
            $table->string('name_en');
            $table->string('surname_en');
            $table->string('middle_name_en')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->json('affiliations')->nullable();
            $table->string('orcid_id')->nullable();
            $table->string('website')->nullable();
            $table->string('participation_type');
            $table->boolean('is_young');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participations');
    }
};

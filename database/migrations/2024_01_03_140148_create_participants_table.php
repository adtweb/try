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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name_ru')->nullable();
            $table->string('surname_ru')->nullable();
            $table->string('middle_name_ru')->nullable();
            $table->string('name_en')->nullable();
            $table->string('surname_en')->nullable();
            $table->string('middle_name_en')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('photo')->nullable();
            $table->string('orcid_id')->nullable();
            $table->string('website')->nullable();
            $table->json('affiliations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Domains\Auth\Models\Organization;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\ConferenceType;
use Src\Domains\Conferences\Models\Subject;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conferences', function (Blueprint $table) {
            $table->id()->startingValue(1001);
            $table->string('title_ru');
            $table->string('title_en', 250);
            $table->string('slug')->unique();
            $table->foreignIdFor(Organization::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(ConferenceType::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('format');
            $table->boolean('with_foreign_participation')->default(0);
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->boolean('need_site');
            $table->json('co-organizers')->nullable();
            $table->string('address');
            $table->string('phone')->nullable();
            $table->string('email');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description_ru')->nullable();
            $table->text('description_en')->nullable();
            $table->string('lang');
            $table->string('participants_number');
            $table->string('report_form');
            $table->string('whatsapp')->nullable();
            $table->string('telegram')->nullable();
            $table->unsignedInteger('price_participants')->nullable();
            $table->unsignedInteger('price_visitors')->nullable();
            $table->json('discount_students')->nullable();
            $table->json('discount_participants')->nullable();
            $table->json('discount_special_guest')->nullable();
            $table->json('discount_young_scientist')->nullable();
            $table->unsignedInteger('abstracts_price')->nullable();
            $table->string('abstracts_format');
            $table->string('abstracts_lang');
            $table->timestamps();
        });

        Schema::create('conference_subject', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Conference::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Subject::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (app()->isLocal()) {
            Schema::dropIfExists('conferences');
            Schema::dropIfExists('conference_subject');
        }
    }
};

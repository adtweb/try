<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Domains\Schedule\Models\ScheduleItem;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedule_item_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ScheduleItem::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title_ru', 255)->nullable();
            $table->string('title_en', 255)->nullable();
            $table->string('color', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_item_tags');
    }
};

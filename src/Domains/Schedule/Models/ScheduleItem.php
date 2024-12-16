<?php

namespace Src\Domains\Schedule\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Domains\Conferences\Models\Thesis;
use Src\Domains\Schedule\Enums\ScheduleItemType;

class ScheduleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'title',
        'thesis_id',
        'section_id',
        'schedule_session_id',
        'time_start',
        'time_end',
        'position',
        'is_standart',
        'type',
    ];

    protected $casts = [
        'time_start' => 'datetime',
        'time_end' => 'datetime',
        'is_standart' => 'boolean',
        'type' => ScheduleItemType::class,
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class);
    }

    public function scheduleItemTags(): HasMany
    {
        return $this->hasMany(ScheduleItemTag::class);
    }
}

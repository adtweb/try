<?php

namespace Src\Domains\Schedule\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Domains\Conferences\Models\Conference;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function scheduleItems(): HasMany
    {
        return $this->hasMany(ScheduleItem::class);
    }

    public function scheduleSessions(): HasMany
    {
        return $this->hasMany(ScheduleSession::class);
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }
}

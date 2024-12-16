<?php

namespace Src\Domains\Conferences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Domains\Auth\Models\User;
use Src\Domains\Schedule\Models\ScheduleItem;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'conference_id',
        'title_ru',
        'title_en',
    ];

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function theses(): HasMany
    {
        return $this->hasMany(Thesis::class);
    }

    public function moderators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'moderators')
            ->withPivot(['comment']);
    }

    public function scheduleItems(): HasMany
    {
        return $this->hasMany(ScheduleItem::class);
    }
}

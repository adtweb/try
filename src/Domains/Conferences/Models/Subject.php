<?php

namespace Src\Domains\Conferences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ru',
        'title_en',
        'slug',
    ];

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Subject $subject) {
            $subject->slug = str($subject->title_en)->slug()->value();
        });
    }
}

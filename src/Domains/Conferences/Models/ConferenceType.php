<?php

namespace Src\Domains\Conferences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConferenceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ru',
        'title_en',
    ];

    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    }
}

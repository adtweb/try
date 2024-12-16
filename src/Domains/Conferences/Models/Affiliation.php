<?php

namespace Src\Domains\Conferences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ru',
        'abbreviation_ru',
        'title_en',
        'abbreviation_en',
    ];
}

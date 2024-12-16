<?php

namespace Src\Domains\Conferences\Models;

use App\Events\ThesisAssetCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Domains\Conferences\Enums\ThesisAssetTitle;

class ThesisAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'is_approved',
        'title',
        'thesis_id',
    ];

    protected $casts = [
        'title' => ThesisAssetTitle::class,
    ];

    protected $dispatchesEvents = [
        'created' => ThesisAssetCreated::class,
    ];

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class);
    }
}

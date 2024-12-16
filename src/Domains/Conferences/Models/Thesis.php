<?php

namespace Src\Domains\Conferences\Models;

use App\Casts\ThesisTitleCast;
use App\Events\ThesisCreated;
use App\Events\ThesisCreating;
use App\Events\ThesisDeleted;
use App\Events\ThesisUpdating;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Src\Domains\Conferences\Enums\ThesisReportForm;
use Src\Domains\Schedule\Models\ScheduleItem;

class Thesis extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'thesis_id',
        'participation_id',
        'section_id',
        'report_form',
        'solicited_talk',
        'title',
        'authors',
        'reporter',
        'contact',
        'text',
        'deleted_at',
    ];

    protected $casts = [
        'title' => ThesisTitleCast::class,
        'report_form' => ThesisReportForm::class,
        'solicited_talk' => 'boolean',
        'authors' => 'array',
        'reporter' => 'array',
        'contact' => 'array',
    ];

    protected $dispatchesEvents = [
        'creating' => ThesisCreating::class,
        'created' => ThesisCreated::class,
        'updating' => ThesisUpdating::class,
        'deleted' => ThesisDeleted::class,
    ];

    public function participation(): BelongsTo
    {
        return $this->belongsTo(Participation::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function scheduleItem(): HasOne
    {
        return $this->hasOne(ScheduleItem::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(ThesisAsset::class);
    }
}

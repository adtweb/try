<?php

namespace Src\Domains\Schedule\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleItemTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_item_id',
        'title_ru',
        'title_en',
        'color',
    ];

    public function scheduleItem(): BelongsTo
    {
        return $this->belongsTo(ScheduleItem::class);
    }
}

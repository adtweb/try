<?php

declare(strict_types=1);

namespace Src\Domains\Messenger\Models;

use App\Events\MessageCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'participant_id',
        'text',
        'chat_id',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    protected $dispatchesEvents = [
        'created' => MessageCreated::class,
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}

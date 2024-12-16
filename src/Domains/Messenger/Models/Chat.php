<?php

namespace Src\Domains\Messenger\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Domains\Auth\Models\Participant;
use Src\Domains\Auth\Models\User;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Messenger\Enums\ChatType;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'type',
        'conference_id',
    ];

    protected $casts = [
        'data' => 'object',
        'type' => ChatType::class,
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class)
            ->withPivot(['conference_id', 'is_read', 'role']);
    }
}

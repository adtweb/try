<?php

namespace Src\Domains\Auth\Models;

use App\Casts\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;
use Src\Domains\Conferences\Models\Participation;
use Src\Domains\Conferences\Models\Thesis;
use Src\Domains\Messenger\Contracts\Chatable;
use Src\Domains\Messenger\Enums\Role;
use Src\Domains\Messenger\Models\Chat;

class Participant extends Model implements Chatable
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name_ru',
        'surname_ru',
        'middle_name_ru',
        'name_en',
        'surname_en',
        'middle_name_en',
        'phone',
        'affiliations',
        'orcid_id',
        'website',
        'photo',
    ];

    protected $casts = [
        'phone' => PhoneNumber::class,
        'affiliations' => 'array',
    ];

    public function toChatable(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name_ru' => $this->name_ru,
            'surname_ru' => $this->surname_ru,
            'middle_name_ru' => $this->middle_name_ru,
            'name_en' => $this->name_en,
            'surname_en' => $this->surname_en,
            'middle_name_en' => $this->middle_name_en,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function participations(): HasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function theses(): HasManyThrough
    {
        return $this->hasManyThrough(Thesis::class, Participation::class);
    }

    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class)
            ->withPivot(['conference_id', 'is_read', 'role']);
    }

    public function participantChats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class)
            ->withPivotValue('role', Role::PARTICIPANT->value);
    }

    public function moderatorChats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class)
            ->withPivotValue('role', Role::MODERATOR->value);
    }

    public function organizerChats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class)
            ->withPivotValue('role', Role::ORGANIZER->value);
    }

    public function unreadChatsCount(): int
    {
        return DB::table('chatables')
            ->where('chatable_type', 'participant')
            ->where('chatable_id', $this->id)
            ->where('role', 'participant')
            ->where('is_read', false)
            ->count();
    }
}

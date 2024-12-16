<?php

namespace Src\Domains\Auth\Models;

use App\Casts\PhoneNumber;
use App\Events\OrganizationCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Src\Domains\Conferences\Models\Conference;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name_ru',
        'short_name_ru',
        'full_name_en',
        'short_name_en',
        'inn',
        'address',
        'phone',
        'whatsapp',
        'telegram',
        'type',
        'actions',
        'vk',
        'logo',
    ];

    protected $casts = [
        'phone' => PhoneNumber::class,
        'actions' => 'array',
    ];

    protected $dispatchesEvents = [
        'created' => OrganizationCreated::class,
    ];

    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    }

    public function unreadChatsCount(): int
    {
        return DB::table('chatables')
            ->where('chatable_type', 'organization')
            ->where('chatable_id', $this->id)
            ->where('role', 'organization')
            ->where('is_read', false)
            ->count();
    }
}

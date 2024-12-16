<?php

namespace Src\Domains\Conferences\Models;

use App\Casts\DiscountCast;
use App\Casts\PhoneNumber;
use App\Enums\Timezone;
use App\Events\ConferenceCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Src\Domains\Auth\Models\Organization;
use Src\Domains\Auth\Models\Participant;
use Src\Domains\Auth\Models\User;
use Src\Domains\Conferences\Enums\AbstractsFormat;
use Src\Domains\Conferences\Enums\AbstractsLanguage;
use Src\Domains\Conferences\Enums\ConferenceFormat;
use Src\Domains\Conferences\Enums\ConferenceLanguage;
use Src\Domains\Conferences\Enums\ConferenceReportForm;
use Src\Domains\Conferences\Enums\ParticipantsNumber;
use Src\Domains\Schedule\Models\Schedule;

/**
 * @property int $user_id
 */
class Conference extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ru',
        'title_en',
        'slug',
        'conference_type_id',
        'organization_id',
        'user_id',
        'format',
        'with_foreign_participation',
        'logo',
        'website',
        'need_site',
        'co-organizers',
        'address',
        'phone',
        'email',
        'start_date',
        'end_date',
        'timezone',
        'description_ru',
        'description_en',
        'lang',
        'participants_number',
        'report_form',
        'whatsapp',
        'telegram',
        'price_participants',
        'price_visitors',
        'discount_students',
        'discount_participants',
        'discount_special_guest',
        'discount_young_scientist',
        'abstracts_price',
        'abstracts_format',
        'abstracts_lang',
        'max_thesis_characters',
        'thesis_instruction',
        'thesis_accept_until',
        'thesis_edit_until',
        'assets_load_until',
        'schedule_is_published',
        'asset_is_published',
    ];

    protected $casts = [
        'format' => ConferenceFormat::class,
        'lang' => ConferenceLanguage::class,
        'abstracts_format' => AbstractsFormat::class,
        'abstracts_lang' => AbstractsLanguage::class,
        'participants_number' => ParticipantsNumber::class,
        'report_form' => ConferenceReportForm::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'timezone' => Timezone::class,
        'phone' => PhoneNumber::class,
        'co-organizers' => 'array',
        'discount_students' => DiscountCast::class,
        'discount_participants' => DiscountCast::class,
        'discount_special_guest' => DiscountCast::class,
        'discount_young_scientist' => DiscountCast::class,
        'thesis_accept_until' => 'date',
        'thesis_edit_until' => 'date',
        'assets_load_until' => 'date',
        'schedule_is_published' => 'boolean',
        'asset_is_published' => 'boolean',
    ];

    protected $dispatchesEvents = [
        'created' => ConferenceCreated::class,
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ConferenceType::class, 'conference_type_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }

    public function participants(): HasManyThrough
    {
        return $this->hasManyThrough(Participant::class, Participation::class);
    }

    public function theses(): HasManyThrough
    {
        return $this->hasManyThrough(Thesis::class, Participation::class);
    }

    public function participations(): HasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function participationByUser(): ?Participation
    {
        return $this->participations()->where('participant_id', auth()->user()->participant?->id)->first();
    }

    public function unreadOrganizerChatsCount(): int
    {
        if (! auth()->user()->has('conferences')) {
            return 0;
        }

        // TODO messenger
        // return DB::table('chatables')
        //     ->where('role', 'organization')
        //     ->where('chatable_id', organization()->id)
        //     ->where('conference_id', $this->id)
        //     ->where('is_read', false)
        //     ->count();

        return 0;
    }

    public function isArchived(): bool
    {
        $maxDate = $this->end_date->endOfDay()->gt($this->assets_load_until->endOfDay())
            ? $this->end_date->endOfDay()
            : $this->assets_load_until->endOfDay();

        return $maxDate->isPast();
    }
}

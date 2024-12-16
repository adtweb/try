<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleItemTagController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ThesisAssetController;
use App\Http\Controllers\ThesisController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Src\Domains\Auth\Models\Participant;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Thesis;

if (app()->isLocal()) {
    Route::any('test', function () {});
}

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect'],
], function () {
    /**
     * Auth
     */
    include 'auth.php';

    /**
     * Personal cabinet
     */
    Route::prefix('my')
        ->middleware(['auth', 'verified'])
        ->group(function () {
            Route::get('password/edit', [PasswordChangeController::class, 'edit'])->name('my.password.edit');
            Route::middleware(['precognitive'])
                ->post('password/edit', [PasswordChangeController::class, 'update'])
                ->name('my.password.update');

            // Participant
            Route::get('participant/create', [ParticipantController::class, 'create'])->name('participant.create');
            Route::middleware(['precognitive'])
                ->post('participant/store', [ParticipantController::class, 'store'])
                ->name('participant.store');
            Route::get('participant/edit', [ParticipantController::class, 'edit'])->name('participant.edit');
            Route::middleware(['precognitive'])
                ->post('participant/edit', [ParticipantController::class, 'update'])
                ->name('participant.update');
            Route::get('participant/events', [ConferenceController::class, 'participantIndex'])->name('events.participant-index');
            Route::delete('participant/avatar', [ParticipantController::class, 'deleteAvatar'])->name('participant.avatar.delete');

            // Organizer
            Route::get('organization/create', [OrganizationController::class, 'create'])->name('organization.create');
            // Route::get('organization/edit', [OrganizationController::class, 'edit'])->name('organization.edit');
            Route::middleware(['precognitive'])
                ->post('organization/store', [OrganizationController::class, 'store'])
                ->name('organization.store');
            Route::middleware(['precognitive'])
                ->post('organization/update', [OrganizationController::class, 'update'])
                ->name('organization.update');

            Route::get('organization/events', [ConferenceController::class, 'organizerIndex'])->name('events.organization-index');

            Route::prefix('events')->group(function () {
                Route::controller(ConferenceController::class)->group(function () {
                    Route::get('create', 'create')->name('conference.create');
                    Route::middleware(['precognitive'])
                        ->post('create', 'store')
                        ->name('conference.store')
                        ->can('create', Conference::class);
                    Route::get('{conference:slug}/edit', 'edit')
                        ->name('conference.edit')
                        ->can('update', 'conference');
                    Route::middleware(['precognitive', 'notArchived'])
                        ->post('{conference:slug}/edit', 'update')
                        ->name('conference.update')
                        ->can('update', 'conference');
                    Route::middleware(['notArchived'])
                        ->post('{conference:slug}/publish-schedule', 'publishSchedule')
                        ->name('conference.publishSchedule')
                        ->can('update', 'conference');
                    Route::middleware(['notArchived'])
                        ->post('{conference:slug}/publish-thesis-assets', 'publishThesisAssets')
                        ->name('conference.publishThesisAssets')
                        ->can('publishThesisAssets', 'conference');
                    Route::delete('{conference:slug}/logo/delete', 'deleteLogo')
                        ->name('conference.logo.delete')
                        ->can('update', 'conference');

                    Route::get('{conference:slug}/messenger', 'messenger')
                        ->name('conference.messenger');
                });

                Route::get('{conference:slug}/participations', [ParticipationController::class, 'indexByConference'])
                    ->name('conference.participations')
                    ->can('viewParticipations', 'conference');
                Route::get('{conference:slug}/participation', [ParticipationController::class, 'create'])
                    ->name('participation.create');
                Route::middleware(['precognitive', 'notArchived'])
                    ->post('{conference:slug}/participation', [ParticipationController::class, 'store'])
                    ->name('participation.store');
                Route::get('{conference:slug}/participation/edit', [ParticipationController::class, 'edit'])->name('participation.edit');
                Route::middleware(['precognitive', 'notArchived'])
                    ->post('{conference:slug}/participation/edit', [ParticipationController::class, 'update'])
                    ->name('participation.update');

                Route::controller(ScheduleController::class)->group(function () {
                    Route::get('{conference:slug}/schedules', 'index')
                        ->name('schedule.index')
                        ->can('viewSchedules', 'conference');
                    Route::get('{conference:slug}/schedules/{schedule}/sections/{section}/edit', 'edit')
                        ->can('editSectionSchedule', 'section')
                        ->name('schedule.section.edit');
                    Route::middleware(['notArchived'])
                        ->post('{conferenceSlug}/schedules/{scheduleId}/sections/{section}/send-changes', 'sendChanges')
                        ->can('editSectionSchedule', 'section')
                        ->name('schedule.section.send-changes');
                    Route::middleware(['notArchived'])
                        ->post('{conference:slug}/schedules/create', 'store')
                        ->name('schedule.store');
                    Route::middleware(['notArchived'])
                        ->put('{conference:slug}/schedules/mass-update', 'massUpdate')
                        ->name('schedule.massUpdate');
                });

                Route::controller(ScheduleItemTagController::class)->group(function () {
                    Route::middleware(['notArchived'])
                        ->post('{conference:slug}/sections/{section}/schedule-item-tags/create', 'store')
                        ->name('scheduleItemTag.store');
                    Route::middleware(['notArchived'])
                        ->delete('{conference:slug}/sections/{section}/schedule-item-tags/{scheduleItemTag}/delete', 'destroy')
                        ->name('scheduleItemTag.destroy');
                });

                Route::controller(ThesisController::class)->group(function () {
                    Route::get('{conference:slug}/abstracts', 'indexByConference')
                        ->name('theses.index-by-conference')
                        ->can('viewAbstracts', 'conference');
                    Route::get('{conference:slug}/abstracts/create', 'create')
                        ->name('theses.create')
                        ->can('create', Thesis::class);
                    Route::middleware(['precognitive', 'notArchived'])
                        ->post('{conference:slug}/abstracts/create', 'store')
                        ->name('theses.store');
                    Route::get('{conference:slug}/abstracts/{thesis}', 'show')
                        ->name('theses.show')
                        ->can('viewAbstracts', 'conference');
                    Route::get('{conference:slug}/abstracts/{thesis}/edit', 'edit')
                        ->name('theses.edit')
                        ->can('update', 'thesis');
                    Route::middleware(['precognitive', 'notArchived'])
                        ->post('{conference:slug}/abstracts/{thesis}/edit', 'update')
                        ->name('theses.update');
                    Route::delete('abstracts/{thesis}', 'destroy')
                        ->name('theses.destroy')
                        ->can('delete', 'thesis');
                });

                Route::controller(ThesisAssetController::class)->group(function () {
                    Route::get('{conference:slug}/assets', 'index')
                        ->name('assets.index');
                    Route::middleware(['notArchived'])
                        ->post('{conference:slug}/theses/{thesis}/assets', 'store')
                        ->name('thesis.assets.store');
                    Route::middleware(['notArchived'])
                        ->delete('{conference:slug}/assets/{thesisAsset}', 'destroy')
                        ->name('assets.destroy')
                        ->can('changeThesisAssets', 'conference');
                    Route::middleware(['notArchived'])
                        ->patch('{conference:slug}/assets/{thesisAsset}', 'updateApproved')
                        ->name('assets.update-approved')
                        ->can('changeThesisAssets', 'conference');
                });

                Route::controller(SectionController::class)->group(function () {
                    Route::get('{conference:slug}/sections', 'index')
                        ->name('sections.index')
                        ->can('massSectionUpdate', 'conference');
                    Route::middleware(['precognitive', 'notArchived'])
                        ->post('{conference:slug}/sections/mass-update', 'massUpdate')
                        ->name('sections.mass-update');
                });

                Route::controller(ModeratorController::class)->group(function () {
                    Route::middleware(['precognitive', 'notArchived'])
                        ->post('{conference:slug}/moderators', 'store')
                        ->name('moderators.store');
                    Route::middleware(['notArchived'])
                        ->delete('{conference:slug}/moderators', 'destroy')
                        ->name('moderators.destroy');
                });
            });

            Route::controller(CsvController::class)->group(function () {
                Route::get('csv/events/{conference:slug}/theses', 'thesesById')
                    ->name('csv.theses.download')
                    ->can('viewAbstracts', 'conference');
                Route::get('csv/events/{conference:slug}/participations', 'participationsById')
                    ->name('csv.participations.download')
                    ->can('viewParticipations', 'conference');
            });
        });

    /**
     * Pages
     */
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/archive', [ConferenceController::class, 'archive'])->name('archive');
    Route::get('/events/subject/{subject:slug}', [ConferenceController::class, 'subjectIndex'])->name('subject');
    Route::get('/events/{conference:slug}', [ConferenceController::class, 'show'])->name('conference.show');
    Route::get('events/{conference:slug}/schedule', [ConferenceController::class, 'schedule'])->name('conference.schedule');
    Route::get('events/{conference:slug}/abstract/{thesis:thesis_id}', [ThesisController::class, 'showPublicly'])->name('conference.thesis.show');

    Route::view('/contacts', 'contacts')->name('contacts');
    Route::view('/about', 'about')->name('about');
    Route::view('/search', 'search')->name('search');
    Route::view('/announcement', 'announcement')->name('announcement');

    /**
     * Chats
     */
    Route::middleware(['auth'])
        ->controller(ChatController::class)
        ->prefix('messenger')
        ->group(function () {
            Route::get('/', 'view')->name('chats.view');
            Route::get('search', 'search')->name('chats.search');
            Route::get('chats', 'index')->name('chats.index');
            Route::get('chats/{chat}', 'show')->name('chats.show');
            Route::post('chats', 'store')->name('chats.store');
            Route::post('chats/start-organization', 'startChatWithOrganization')->name('chats.start.organization');
            Route::post('chats/start-moderator', 'startChatWithModerator')->name('chats.start.moderator');
            Route::post('chats/{chat}/messages', 'storeMessage')->name('chats.messages.store');
            Route::get('chats/{chat}/messages', 'chatMessages')->name('chats.messages.index');
            Route::post('chats/{chat}/messages/read', 'readMessages')->name('chats.messages.read');
            Route::put('chats/{chat}/read', 'readChat')->name('chats.read');
        });

    /**
     * PDF generation
     */
    Route::controller(PdfController::class)
        ->group(function () {
            Route::middleware(['auth'])
                ->post('pdf/events/{conference:slug}/thesis-preview', 'thesisPreview')
                ->name('pdf.thesis.preview');
            Route::get('pdf/events/{conference:slug}/theses/{thesis}', 'thesisDownload')
                ->name('pdf.thesis.download');
            Route::middleware(['auth'])
                ->post('pdf/events/{conference:slug}/theses', 'thesesDownload')
                ->name('pdf.theses.download')
                ->can('viewAbstracts', 'conference');
            Route::get('pdf/events/{conference:slug}/schedule', 'scheduleDownload')
                ->name('pdf.schedule.download');
        });
});

Route::middleware(['precognitive'])->post('feedback', FeedbackController::class)->name('feedback');

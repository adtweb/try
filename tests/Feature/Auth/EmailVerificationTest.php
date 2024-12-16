<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Database\Factories\UserFactory;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        $this->refreshApplicationWithLocale('ru');
    }

    // public function test_email_can_be_verified(): void
    // {
    //     $user = UserFactory::new()->create([
    //         'email_verified_at' => null,
    //     ])->first();

    //     Event::fake();

    //     $verificationUrl = URL::temporarySignedRoute(
    //         'verification.verify',
    //         now()->addMinutes(60),
    //         ['id' => $user->id, 'hash' => sha1($user->email)]
    //     );

    //     $response = $this->actingAs($user)->get($verificationUrl);

    //     Event::assertDispatched(Verified::class);
    //     $this->assertTrue($user->fresh()->hasVerifiedEmail());
    //     $response->assertRedirect(config('app.frontend_url').RouteServiceProvider::HOME.'?verified=1');
    // }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $user = UserFactory::new()->create([
            'email_verified_at' => null,
        ])->first();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}

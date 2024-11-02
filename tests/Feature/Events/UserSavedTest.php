<?php

namespace Tests\Feature\Events;

use App\Events\UserSaved;
use App\Listeners\SaveUserBackgroundInformation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserSavedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_user_saved_event_was_dispatched(): void
    {
        // Arrange
        Event::fake(UserSaved::class);

        // Action
        User::factory()->create();

        // Assert
        Event::assertDispatched(UserSaved::class);
    }
}

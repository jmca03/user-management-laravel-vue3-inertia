<?php

namespace Tests\Unit\Listeners;

use App\Enums\UserDetailKey;
use App\Enums\UserPrefix;
use App\Events\UserSaved;
use App\Listeners\SaveUserBackgroundInformation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class SaveUserBackgroundInformationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void.
     */
    public function test_save_user_background_information_data(): void
    {
        // Arrange
        $file = (UploadedFile::fake()->image('avatar.jpg'))->store('photos');

        // Action
        $user = User::factory()->create([
            'prefixname' => UserPrefix::MR->value,
            'firstname'  => 'Lorem Ipsum',
            'middlename' => 'Lorem Ipsum',
            'lastname'   => 'Lorem Ipsum',
            'photo'      => $file,
        ]);

        // Assert
        $this->assertDatabaseHas('details', [
            'key' => UserDetailKey::FULL_NAME->value
        ]);

        $this->assertDatabaseHas('details', [
            'key' => UserDetailKey::MIDDLE_INITIAL->value
        ]);

        $this->assertDatabaseHas('details', [
            'key' => UserDetailKey::AVATAR->value
        ]);

        $this->assertDatabaseHas('details', [
            'key' => UserDetailKey::GENDER->value
        ]);
    }
}

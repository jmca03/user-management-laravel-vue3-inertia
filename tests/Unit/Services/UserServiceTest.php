<?php

namespace Tests\Unit\Services;

use App\Http\Controllers\UserController;
use App\Http\Requests\UploadProfilePictureRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_users()
    {
        // Arrangements
        User::factory(5)->create();

        // Actions
        $response = app(UserService::class)->list(request());

        // Assertions
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_store_a_user_to_database()
    {
        // Arrangements
        $user = User::factory()->create();
        $file = (UploadedFile::fake()->image('profile.jpg', 500, 500)->size(512))->store('public');

        // Actions
        $response = app(UserService::class)->store([
            ...User::factory()->make()->toArray(),
            'password' => bcrypt('password'),
            'photo' => $file
        ]);

        // Assertions
        $this->assertDatabaseHas('users', [
            'id' => $response->id,
        ]);

        $this->assertInstanceOf(User::class, $response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_find_and_return_an_existing_user()
    {

        // Arrangements
        $user = User::factory()->create();

        // Actions
        $response = app(UserService::class)->find($user->id);

        // Assertions
        $this->assertInstanceOf(User::class, $response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
    {
        // Arrangements
        $user = User::factory()->create();

        $file = (UploadedFile::fake()->image('profile.jpg', 500, 500)->size(512))->store('public');

        // Actions
        $response = app(UserService::class)->update($user->id, [
            ...User::factory()->make()->toArray(),
            'password' => bcrypt('password'),
            'photo' => $file
        ]);

        // Assertions
        $this->assertInstanceOf(User::class, $response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_soft_delete_an_existing_user()
    {
        // Arrangements
        $user = User::factory()->create();

        // Actions
        app(UserService::class)->destroy($user->id);


        // Assertions
        $this->assertSoftDeleted('users', [
            'id' => $user->id
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {
        // Arrangements
        User::factory(5)->deleted()->create();

        // Actions
        $response = app(UserService::class)->listTrashed(request());

        // Assertions
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_restore_a_soft_deleted_user()
    {
        // Arrangements
        $user = User::factory()->deleted()->create();

        // Actions
        app(UserService::class)->restore($user->id);


        // Assertions
        $this->assertNotSoftDeleted('users', [
            'id' => $user->id
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        // Arrangements
        $user = User::factory()->deleted()->create();

        // Actions
        app(UserService::class)->delete($user->id);


        // Assertions
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_upload_photo                             ()
    {
        // Arrangements
        $file = UploadedFile::fake()->image('profile.jpg', 500, 500)->size(512);

        // Actions
        $file = app(UserService::class)->upload($file);


        // Assertions
        $this->assertIsString($file);
        Storage::disk('public')->assertExists($file);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_get_initials                             ()
    {
        // Arrangements
        $value = 'Lorem Ipsum Dolor Sit Amet';

        // Actions
        $response = app(UserService::class)->getInitials($value);


        // Assertions
        $this->assertEquals('L. I. D. S. A.', $response);
    }
}

<?php

namespace Controllers;

use App\Http\Controllers\UserController;
use App\Http\Requests\UploadProfilePictureRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * @return void
     */
    public function it_can_render_listing()
    {
        // Arrangements
        $user = User::factory()->create();

        // Actions
        $response = $this->actingAs($user)->get(route('users.index'));

        // Assertions
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Users/Users')
            ->has('users')
        );
    }

    /**
     * @test
     * @return void
     */
    public function it_can_render_create()
    {
        // Arrangements
        $user = User::factory()->create();

        // Actions
        $response = $this->actingAs($user)->get(route('users.create'));

        // Assertions
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Users/CreateUser')
            ->has('userPrefixes')
        );
    }

    /**
     * @test
     * @return void
     */
    public function it_can_store_a_user_to_database()
    {
        // Arrangements
        $user = User::factory()->create();

        $email = fake()->unique()->safeEmail();
        $username = fake()->unique()->userName();
        $password = fake()->password(8);

        // Actions
        $response = $this->actingAs($user)->post(route('users.store'), [
            ...User::factory()->make([
                'email' => $email,
                'username' => $username,
            ])->toArray(),
            'password' => $password,
            'password_confirmation' => $password,
            'photo' => UploadedFile::fake()->image('profile.jpg', 500, 500)->size(512),
        ]);

        // Assertions
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'username' => $username
        ]);

        $response->assertRedirect(route('users.index'));
    }

    /**
     * @test
     * @return void
     */
    public function it_can_render_show()
    {
        // Arrangements
        $user = User::factory()->create();

        // Actions
        $response = $this->actingAs($user)->get(route('users.show', [
            'user' => $user->id,
        ]));

        // Assertions
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Users/ViewUser')
            ->has('user')
            ->has('userPrefixes')
        );
    }

    /**
     * @test
     * @return void
     */
    public function it_can_render_edit()
    {
        // Arrangements
        $user = User::factory()->create();

        // Actions
        $response = $this->actingAs($user)->get(route('users.edit', [
            'user' => $user->id,
        ]));

        // Assertions
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Users/EditUser')
            ->has('user')
            ->has('userPrefixes')
        );
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
    {
        // Arrangements
        $user = User::factory()->create();

        $email = fake()->unique()->safeEmail();
        $username = fake()->unique()->userName();
        $password = fake()->password(8);

        // Actions
        $response = $this->actingAs($user)->post(route('users.update', [
            'user' => $user->id,
        ]), [
            ...User::factory()->make([
                'email' => $email,
                'username' => $username,
            ])->toArray(),
            'password' => $password,
            'password_confirmation' => $password,
            'photo' => UploadedFile::fake()->image('profile.jpg', 500, 500)->size(512),
        ]);

        // Assertions
        $response->assertRedirect(route('users.edit', [
            'user' => $user->id,
        ]));
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
        $response = $this->actingAs($user)->delete(route('users.destroy', [
            'user' => (User::factory()->create())->id,
        ]));

        // Assertions
        $response->assertRedirect(route('users.index'));
    }

    /**
     * @test
     * @return void
     */
    public function it_can_render_trashed_user()
    {
        // Arrangements
        $user = User::factory()->create();

        // Actions
        $response = $this->actingAs($user)->get(route('users.trashed'));

        // Assertions
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Users/TrashedUsers')
            ->has('users')
        );
    }

    /**
     * @test
     * @return void
     */
    public function it_can_restore_a_soft_deleted_user()
    {
        // Arrangements
        $user = User::factory()->create();
        $deletedUser = User::factory()->deleted()->create();

        // Actions
        $response = $this->actingAs($user)->patch(route('users.restore', [
            'user' => $deletedUser->id,
        ]));

        // Assertions
        $response->assertRedirect(route('users.index'));
    }

    /**
     * @test
     * @return void
     */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        // Arrangements
        $user = User::factory()->create();
        $deletedUser = User::factory()->deleted()->create();

        // Actions
        $response = $this->actingAs($user)->delete(route('users.delete', [
            'user' => $deletedUser->id,
        ]));

        // Assertions
        $response->assertRedirect(route('users.index'));
    }
}

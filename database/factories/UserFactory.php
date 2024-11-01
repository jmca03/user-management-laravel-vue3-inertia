<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prefixname' => $this->faker->title, // You can customize this as needed
            'firstname' => $this->faker->firstName,
            'middlename' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'suffixname' => $this->faker->suffix, // Can be null or a specific suffix
            'username' => $this->faker->unique()->userName,
            'photo' => null, // Set photo to null
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'type' => 'user',
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'              => fake('fr_FR')->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
            'role'              => 'patient',
            'phone'             => '06' . fake()->numerify('########'),
            'is_active'         => true,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => ['email_verified_at' => null]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes) => ['role' => 'admin']);
    }

    public function doctor(): static
    {
        return $this->state(fn(array $attributes) => ['role' => 'doctor']);
    }

    public function secretary(): static
    {
        return $this->state(fn(array $attributes) => ['role' => 'secretary']);
    }

    public function patient(): static
    {
        return $this->state(fn(array $attributes) => ['role' => 'patient']);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => ['is_active' => false]);
    }
}

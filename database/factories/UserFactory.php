<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'role' => 'student'
        ];
    }

    public function admin(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => User::ROLE_ADMIN,
                'email' => 'admin@example.com',
            ];
        });
    }

    public function teacher(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => User::ROLE_TEACHER,
            ];
        });
    }

    public function student(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => User::ROLE_STUDENT,
            ];
        });
    }
}

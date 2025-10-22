<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassRoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Mathematics', 'Physics', 'Chemistry', 'Biology', 'History', 'English', 'Computer Science']) . ' ' . fake()->randomElement(['A', 'B', 'C']),
            'description' => fake()->sentence(),
            'schedule' => fake()->randomElement(['Monday/Wednesday 9:00-10:30', 'Tuesday/Thursday 11:00-12:30', 'Monday/Friday 14:00-15:30']),
            'room' => fake()->bothify('Room-##??'),
            'teacher_id' => User::factory()->teacher()
        ];
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\AttendanceRecord;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::factory()->admin()->create();

        // Create teachers
        $teachers = User::factory()->teacher()->count(5)->create();

        // Create students
        $students = User::factory()->student()->count(30)->create();

        // Create classes with teachers
        ClassRoom::factory()
            ->count(10)
            ->create()
            ->each(function ($class) use ($students) {
                // Assign random students to each class (between 5 and 15 students)
                $assigned = $students->random(rand(5, 15));
                $class->students()->attach($assigned->pluck('id'));

                // Seed attendance for the last 5 days
                foreach (range(0,4) as $daysAgo) {
                    $date = now()->subDays($daysAgo)->startOfDay();
                        foreach ($assigned as $student) {
                            AttendanceRecord::create([
                                'class_id' => $class->id,
                                'student_id' => $student->id,
                                'class_session_id' => null,
                                'status' => (rand(1,100) > 20) ? 'present' : 'absent',
                                'notes' => null,
                                'marked_at' => $date->addMinutes(rand(0,60)),
                                'created_at' => $date,
                                'updated_at' => $date,
                            ]);
                        }
                }
            });
    }
}

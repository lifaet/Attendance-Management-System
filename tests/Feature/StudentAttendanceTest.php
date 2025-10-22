<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_mark_attendance_via_ajax()
    {
        // seed minimal data
        $teacher = User::factory()->teacher()->create();
        $student = User::factory()->student()->create();
        $class = ClassRoom::factory()->for($teacher, 'teacher')->create();
        $class->students()->attach($student->id);

        $this->actingAs($student);

        $payload = [
            'attendance' => [[
                'student_id' => $student->id,
                'status' => 'present'
            ]]
        ];

    $response = $this->postJson(route('classes.attendance.student.post', $class), $payload);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('attendance_records', [
            'class_id' => $class->id,
            'student_id' => $student->id,
            'status' => 'present'
        ]);
    }
}

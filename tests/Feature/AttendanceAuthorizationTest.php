<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_teachers_can_take_attendance_for_their_classes()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $class = ClassRoom::factory()->create(['teacher_id' => $teacher->id]);

        $response = $this->actingAs($teacher)
            ->get(route('classes.attendance.create', $class));

        $response->assertSuccessful();
    }

    public function test_teachers_cannot_take_attendance_for_other_teachers_classes()
    {
        $teacher1 = User::factory()->create(['role' => 'teacher']);
        $teacher2 = User::factory()->create(['role' => 'teacher']);
        $class = ClassRoom::factory()->create(['teacher_id' => $teacher2->id]);

        $response = $this->actingAs($teacher1)
            ->get(route('classes.attendance.create', $class));

        $response->assertForbidden();
    }

    public function test_students_cannot_access_attendance_taking_page()
    {
        $student = User::factory()->create(['role' => 'student']);
        $class = ClassRoom::factory()->create();

        $response = $this->actingAs($student)
            ->get(route('classes.attendance.create', $class));

        $response->assertForbidden();
    }
}
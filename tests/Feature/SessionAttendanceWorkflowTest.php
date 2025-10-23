<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\ClassSession;
use App\Models\AttendanceRecord;

class SessionAttendanceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_starts_session_students_mark_and_end_finalizes()
    {
        // Create teacher, student and class
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);

        $class = ClassRoom::factory()->create(['teacher_id' => $teacher->id]);
        $class->students()->attach($student->id);

        // Teacher starts session
        $this->actingAs($teacher)
            ->post(route('classes.sessions.store', $class))
            ->assertSessionHas('success');

        $session = $class->fresh()->activeSession();
        $this->assertNotNull($session, 'Session should have been created');

        // Ensure session starts with zero attendance records
        $this->assertEquals(0, $session->attendanceRecords()->count(), 'Session should start with 0 attendance records');

        // Student marks attendance
        $this->actingAs($student)
            ->post(route('classes.attendance.mark', $class), ['notes' => 'Here'])
            ->assertSessionHas('success');

        $record = AttendanceRecord::where('class_session_id', $session->id)
            ->where('student_id', $student->id)
            ->first();

        $this->assertNotNull($record, 'Attendance record should be created when student marks');
        $this->assertEquals('pending', $record->status);

        // Teacher ends session (use PUT because route is defined as PUT)
        $this->actingAs($teacher)
            ->put(route('classes.sessions.end', $session))
            ->assertSessionHas('success');

        $record = $record->fresh();
        $this->assertEquals('absent', $record->status, 'Pending record should be finalized to absent on session end');

        // Student tries to mark after session end -> should be blocked
        $this->actingAs($student)
            ->post(route('classes.attendance.mark', $class), ['notes' => 'Late attempt'])
            ->assertSessionHas('error');
    }
}

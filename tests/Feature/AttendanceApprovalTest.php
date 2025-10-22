<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\AttendanceRecord;
use App\Models\ClassSession;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_attendance_is_marked_as_pending()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        $class = ClassRoom::factory()->create(['teacher_id' => $teacher->id]);
        $class->students()->attach($student);
        
        $session = $class->sessions()->create([
            'started_at' => now(),
            'status' => 'active'
        ]);

        $response = $this->actingAs($student)
            ->post(route('classes.attendance.student.post', $class), [
                'attendance' => [
                    [
                        'student_id' => $student->id,
                        'status' => 'present',
                        'notes' => 'Test attendance'
                    ]
                ]
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendance_records', [
            'student_id' => $student->id,
            'class_id' => $class->id,
            'status' => 'pending',
            'teacher_approved_at' => null
        ]);
    }

    public function test_teacher_can_approve_pending_attendance()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        $class = ClassRoom::factory()->create(['teacher_id' => $teacher->id]);
        
        $record = AttendanceRecord::factory()->create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($teacher)
            ->patch(route('classes.attendance.approve', $record));

        $response->assertRedirect();
        $this->assertDatabaseHas('attendance_records', [
            'id' => $record->id,
            'status' => 'present',
            'approved_by' => $teacher->id,
        ]);
        $this->assertNotNull($record->fresh()->teacher_approved_at);
    }

    public function test_teacher_can_reject_pending_attendance()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        $class = ClassRoom::factory()->create(['teacher_id' => $teacher->id]);
        
        $record = AttendanceRecord::factory()->create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($teacher)
            ->patch(route('classes.attendance.reject', $record), [
                'notes' => 'Rejected due to late submission'
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendance_records', [
            'id' => $record->id,
            'status' => 'absent',
            'approved_by' => $teacher->id,
            'approval_notes' => 'Rejected due to late submission'
        ]);
    }

    public function test_other_teachers_cannot_approve_attendance()
    {
        $teacher1 = User::factory()->create(['role' => 'teacher']);
        $teacher2 = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        $class = ClassRoom::factory()->create(['teacher_id' => $teacher1->id]);
        
        $record = AttendanceRecord::factory()->create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($teacher2)
            ->patch(route('classes.attendance.approve', $record));

        $response->assertForbidden();
        $this->assertDatabaseHas('attendance_records', [
            'id' => $record->id,
            'status' => 'pending',
            'teacher_approved_at' => null
        ]);
    }
}
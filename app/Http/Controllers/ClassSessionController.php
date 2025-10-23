<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\ClassSession;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class ClassSessionController extends Controller
{
    public function store(Request $request, ClassRoom $class)
    {
        $this->authorize('update', $class);

        $activeSession = $class->sessions()->where('status', 'active')->first();
        if ($activeSession) {
            return back()->with('error', 'This class already has an active session.');
        }

        $session = $class->sessions()->create([
            'started_at' => now(),
            'status' => 'active'
        ]);

        // Note: Do not pre-seed attendance records. Sessions should start with zero attendance.
        // Students will create their attendance record when they mark during an active session.

        return back()->with('success', 'Class session started successfully.');
    }

    public function end(Request $request, ClassSession $session)
    {
        $this->authorize('update', $session->class);

        if (!$session->isActive()) {
            return back()->with('error', 'This session has already ended.');
        }

        // Finalize any pending attendance records as absent
        $session->attendanceRecords()
            ->where('status', 'pending')
            ->update([
                'status' => 'absent',
                'approval_notes' => 'Automatically marked absent at session end',
                'marked_at' => now(),
            ]);

        $session->end();

        return back()->with('success', 'Class session ended successfully. All pending attendance records have been finalized.');
    }

    public function show(ClassSession $session)
    {
        $this->authorize('view', $session->class);

        $attendanceRecords = $session->attendanceRecords()->with(['student'])->get();
        $presentCount = $attendanceRecords->where('status', 'present')->count();
        $totalCount = $attendanceRecords->count();

        return view('classes.session', compact('session', 'attendanceRecords', 'presentCount', 'totalCount'));
    }
}
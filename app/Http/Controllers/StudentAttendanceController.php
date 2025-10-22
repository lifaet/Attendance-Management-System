<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $classes = $user->classes()->with('teacher')->get();
        
        $activeClassSessions = ClassRoom::whereIn('id', $classes->pluck('id'))
            ->whereHas('sessions', function ($query) {
                $query->where('status', 'active');
            })
            ->with(['sessions' => function ($query) {
                $query->where('status', 'active');
            }])
            ->get();

        $recentAttendance = AttendanceRecord::where('student_id', $user->id)
            ->with(['class', 'classSession'])
            ->latest()
            ->take(10)
            ->get();

        return view('attendance.student', compact('classes', 'activeClassSessions', 'recentAttendance'));
    }

    public function mark(Request $request, ClassRoom $class)
    {
        $user = $request->user();
        if (!$class->students()->where('id', $user->id)->exists()) {
            return back()->with('error', 'You are not enrolled in this class.');
        }

        $activeSession = $class->sessions()
            ->where('status', 'active')
            ->first();

        if (!$activeSession) {
            return back()->with('error', 'No active session for this class.');
        }

        // Check if attendance was already marked for this session
        $existingRecord = AttendanceRecord::where([
            'student_id' => $user->id,
            'class_id' => $class->id,
            'class_session_id' => $activeSession->id,
        ])->first();

        if ($existingRecord) {
            if ($existingRecord->status === 'present') {
                return back()->with('info', 'Your attendance has already been marked for this session.');
            } elseif ($existingRecord->status === 'rejected') {
                return back()->with('error', 'Your attendance was rejected for this session. Please contact your teacher.');
            }
        }

        // Validate the request
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        // Create new attendance record
        $record = AttendanceRecord::create([
            'student_id' => $user->id,
            'class_id' => $class->id,
            'class_session_id' => $activeSession->id,
            'status' => 'pending',
            'notes' => $request->input('notes'),
            'marked_at' => now(),
        ]);

        return back()->with('success', 'Attendance marked successfully and awaiting teacher approval.');
    }

    public function stats(Request $request)
    {
        $user = $request->user();
        $classes = $user->classes()->with('teacher')->get();
        
        $classStats = $classes->map(function ($class) use ($user) {
            $totalSessions = $class->sessions()->where('status', 'ended')->count();
            $attendedSessions = $class->sessions()
                ->where('status', 'ended')
                ->whereHas('attendanceRecords', function ($query) use ($user) {
                    $query->where('student_id', $user->id)
                        ->where('status', 'present');
                })
                ->count();

            return [
                'class' => $class,
                'totalSessions' => $totalSessions,
                'attendedSessions' => $attendedSessions,
                'attendanceRate' => $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100, 1) : 0
            ];
        });

        return view('attendance.stats', compact('classStats'));
    }
}
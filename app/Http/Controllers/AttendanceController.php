<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\AttendanceRecord;

class AttendanceController extends Controller
{
    public function studentMark(Request $request, ClassRoom $class)
    {
        $payload = $request->all();

        // Accept either JSON AJAX or form data
        $attendance = $payload['attendance'][0] ?? null;

        if (! $attendance) {
            return response()->json(['message' => 'Invalid payload'], 422);
        }

        $validated = validator($attendance, [
            'student_id' => 'required|exists:users,id',
            'status' => 'required|in:present,absent,late',
            'notes' => 'nullable|string|max:255'
        ])->validate();

        $record = AttendanceRecord::updateOrCreate(
            [
                'class_id' => $class->id,
                'student_id' => $validated['student_id'],
                'created_at' => now()->startOfDay()
            ],
            [
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null
            ]
        );

        if ($request->wantsJson() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'student_id' => $record->student_id,
                'student_name' => $record->student->name,
                'status' => $record->status
            ]);
        }

        return redirect()->back()->with('success','Attendance marked!');
    }

    // Teacher: show attendance form for class (today)
    public function create(ClassRoom $class)
    {
        $this->authorize('takeAttendance', $class);

        $existingAttendance = $class->attendanceRecords()
            ->whereDate('created_at', today())
            ->get()
            ->keyBy('student_id');

        return view('attendance.create', [
            'class' => $class->load('students'),
            'existingAttendance' => $existingAttendance
        ]);
    }

    // Teacher: store bulk attendance (form submission)
    public function store(Request $request, ClassRoom $class)
    {
        $this->authorize('takeAttendance', $class);

        $validated = $request->validate([
            'attendance.*.student_id' => ['required', 'exists:users,id'],
            'attendance.*.status' => ['required', 'in:present,absent,late'],
            'attendance.*.notes' => ['nullable', 'string', 'max:255']
        ]);

        foreach ($validated['attendance'] as $record) {
            AttendanceRecord::updateOrCreate(
                [
                    'class_id' => $class->id,
                    'student_id' => $record['student_id'],
                    'created_at' => now()->startOfDay()
                ],
                [
                    'status' => $record['status'],
                    'notes' => $record['notes'] ?? null
                ]
            );
        }

        return redirect()->route('classes.show', $class)
            ->with('success', 'Attendance recorded successfully.');
    }

    // View for students to quickly mark attendance (AJAX)
    public function studentMarkView(ClassRoom $class)
    {
        // Ensure the current student is enrolled in the class
        $user = auth()->user();
        if (! $class->students->contains($user)) {
            abort(403, 'You are not enrolled in this class.');
        }

        return view('attendance.student_mark', compact('class'));
    }

    // Backwards-compatible teacher update (not used directly)
    public function teacherUpdate(Request $request, ClassRoom $class)
    {
        $request->validate([
            'status' => 'required|array',
            'status.*' => 'in:present,absent,late'
        ]);
        foreach ($request->status as $student_id => $status) {
            AttendanceRecord::updateOrCreate(
                [
                    'class_id' => $class->id,
                    'student_id' => $student_id,
                    'created_at' => now()->startOfDay()
                ],
                ['status' => $status]
            );
        }

        return redirect()->back()->with('success','Attendance updated!');
    }

    public function show(AttendanceRecord $record)
    {
        $user = auth()->user();
        // allow admin, the student himself, or the class teacher
        if (! ($user->role === 'admin' || $record->student_id === $user->id || $record->class->teacher_id === $user->id)) {
            abort(403);
        }

        return view('attendance.show', compact('record'));
    }

    public function edit(AttendanceRecord $record)
    {
        $user = auth()->user();
        if (! ($user->role === 'admin' || $record->class->teacher_id === $user->id)) {
            abort(403);
        }

        return view('attendance.edit', compact('record'));
    }

    public function update(Request $request, AttendanceRecord $record)
    {
        $user = auth()->user();
        if (! ($user->role === 'admin' || $record->class->teacher_id === $user->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:present,absent,late'],
            'notes' => ['nullable', 'string', 'max:255']
        ]);

        $record->update($validated);

        return redirect()->route('classes.attendance.show', $record)
            ->with('success', 'Attendance record updated successfully.');
    }
}



<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;

class ClassRoomController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'teacher') {
            $classes = $user->teacherClasses()->get(); // assumes teacherClasses() relation
        } else {
            $classes = $user->classes()->get(); // assumes classes() relation for students
        }

        return view('dashboard', compact('classes'));
    }

    public function show(ClassRoom $class)
    {
        $attendanceSummary = $class->attendanceRecords()
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present')
            ->selectRaw('SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late')
            ->selectRaw('SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent')
            ->groupBy('date')
            ->orderByDesc('date')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                return [
                    'date' => \Carbon\Carbon::parse($row->date),
                    'present' => $row->present,
                    'late' => $row->late,
                    'absent' => $row->absent
                ];
            });

        return view('classes.show', compact('class', 'attendanceSummary'));
    }

    public function create()
    {
        $teachers = User::where('role', User::ROLE_TEACHER)->get();
        return view('classes.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'schedule' => ['required', 'string'],
            'room' => ['required', 'string'],
            'teacher_id' => ['required', 'exists:users,id']
        ]);

        $class = ClassRoom::create($validated);

        return redirect()->route('classes.show', $class)
            ->with('success', 'Class created successfully.');
    }

    public function edit(ClassRoom $class)
    {
        $this->authorize('update', $class);
        
        $teachers = User::where('role', User::ROLE_TEACHER)->get();
        return view('classes.edit', compact('class', 'teachers'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $this->authorize('update', $class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'schedule' => ['required', 'string'],
            'room' => ['required', 'string'],
            'teacher_id' => ['required', 'exists:users,id']
        ]);

        $class->update($validated);

        return redirect()->route('classes.show', $class)
            ->with('success', 'Class updated successfully.');
    }
}


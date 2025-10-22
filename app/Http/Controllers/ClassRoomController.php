<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassRoomController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ClassRoom::class, 'class');
    }

    public function index()
    {
        $user = auth()->user();
        $classes = null;
        $teachers = null;

        if ($user->role === 'admin') {
            $classes = ClassRoom::with(['teacher', 'students'])->get();
            $teachers = User::where('role', 'teacher')->get();
            $students = User::where('role', 'student')->get();
            return view('classes.admin-index', compact('classes', 'teachers', 'students'));
        } elseif ($user->role === 'teacher') {
            $classes = $user->teacherClasses()->with('students')->get();
            return view('classes.teacher-index', compact('classes'));
        } else {
            $classes = $user->classes()->with('teacher')->get();
            return view('classes.student-index', compact('classes'));
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

    public function destroy(ClassRoom $class)
    {
        $this->authorize('delete', $class);

        DB::beginTransaction();
        try {
            // Delete related records first
            $class->attendanceRecords()->delete();
            $class->sessions()->delete();
            $class->students()->detach();
            $class->delete();
            
            DB::commit();
            return redirect()->route('classes.index')
                ->with('success', 'Class deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete class. Please try again.');
        }
    }

    public function assignTeacher(Request $request, ClassRoom $class)
    {
        $this->authorize('update', $class);
        
        $validated = $request->validate([
            'teacher_id' => ['required', 'exists:users,id']
        ]);

        $teacher = User::findOrFail($validated['teacher_id']);
        if ($teacher->role !== 'teacher') {
            return back()->with('error', 'Selected user is not a teacher.');
        }

        $class->update(['teacher_id' => $validated['teacher_id']]);

        return back()->with('success', 'Teacher assigned successfully.');
    }

    public function assignStudents(Request $request, ClassRoom $class)
    {
        $this->authorize('update', $class);
        
        $validated = $request->validate([
            'student_ids' => ['required', 'array'],
            'student_ids.*' => ['exists:users,id']
        ]);

        // Verify all users are students
        $students = User::whereIn('id', $validated['student_ids'])
            ->where('role', 'student')
            ->get();

        if ($students->count() !== count($validated['student_ids'])) {
            return back()->with('error', 'One or more selected users are not students.');
        }

        $class->students()->sync($validated['student_ids']);

        return back()->with('success', 'Students assigned successfully.');
    }
}


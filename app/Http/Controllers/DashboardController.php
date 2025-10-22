<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClassRoom;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'teacher':
                return $this->teacherDashboard($user);
            default:
                return $this->studentDashboard($user);
        }
    }

    protected function adminDashboard()
    {
        $stats = [
            'students' => User::where('role', 'student')->count(),
            'teachers' => User::where('role', 'teacher')->count(),
            'classes' => ClassRoom::count(),
            'todayAttendanceRate' => $this->calculateTodayAttendanceRate(),
            'activeClasses' => ClassRoom::has('attendanceRecords')->whereDate('created_at', today())->count()
        ];

        $recentActivity = AttendanceRecord::with(['student', 'class'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($record) {
                return (object)[
                    'created_at' => $record->created_at,
                    'description' => 'Attendance Recorded',
                    'details' => "For {$record->class->name} by {$record->student->name}"
                ];
            });

        return view('dashboard.admin', compact('stats', 'recentActivity'));
    }

    protected function teacherDashboard(User $user)
    {
        $classes = $user->teacherClasses()->with('students')->get();
        
        $recentAttendance = AttendanceRecord::whereIn('class_id', $classes->pluck('id'))
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->selectRaw('class_id, DATE(created_at) as date, count(*) as total_count, sum(case when status = "present" then 1 else 0 end) as present_count')
            ->groupBy('class_id', 'date')
            ->with('class')
            ->orderByDesc('date')
            ->get();

        return view('dashboard.teacher', compact('classes', 'recentAttendance'));
    }

    protected function studentDashboard(User $user)
    {
        $classes = $user->classes()->with('teacher')->get();
        
        $recentAttendance = AttendanceRecord::where('student_id', $user->id)
            ->with('class')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.student', compact('classes', 'recentAttendance', 'user'));
    }

    protected function calculateTodayAttendanceRate()
    {
        $records = AttendanceRecord::whereDate('created_at', today())->get();
        
        if ($records->isEmpty()) {
            return 0;
        }

        $presentCount = $records->where('status', 'present')->count();
        return round(($presentCount / $records->count()) * 100, 1);
    }
}
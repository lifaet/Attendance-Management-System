<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassRoom extends Model
{
    use HasFactory;
    
    protected $table = 'classes';
    protected $fillable = [
        'name',
        'description',
        'schedule',
        'room',
        'teacher_id'
    ];

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students() {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'student_id');
    }

    public function attendanceRecords() {
        return $this->hasMany(AttendanceRecord::class, 'class_id');
        }

        public function sessions() {
            return $this->hasMany(ClassSession::class, 'class_id');
        }

        public function activeSession() {
            return $this->sessions()->where('status', 'active')->first();
        }

        public function hasActiveSession() {
            return $this->sessions()->where('status', 'active')->exists();
        }

        public function startSession() {
            return $this->sessions()->create([
                'started_at' => now(),
                'status' => 'active'
            ]);
    }

    public function getAttendanceForDate($date) {
        return $this->attendanceRecords()
            ->whereDate('created_at', $date)
            ->get()
            ->keyBy('student_id');
    }

    public function getAttendanceStats() {
        $totalDays = $this->attendanceRecords()
            ->distinct('created_at')
            ->count('created_at');

        $stats = [];
        foreach ($this->students as $student) {
            $present = $this->attendanceRecords()
                ->where('student_id', $student->id)
                ->where('status', 'present')
                ->count();

            $stats[$student->id] = [
                'student' => $student,
                'present' => $present,
                'absent' => $totalDays - $present,
                'percentage' => $totalDays > 0 ? round(($present / $totalDays) * 100, 1) : 0
            ];
        }

        return collect($stats);
    }
}

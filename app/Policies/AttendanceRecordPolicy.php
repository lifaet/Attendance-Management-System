<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AttendanceRecord;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendanceRecordPolicy
{
    use HandlesAuthorization;

    public function approve(User $user, AttendanceRecord $record)
    {
        return $user->id === $record->class->teacher_id;
    }

    public function view(User $user, AttendanceRecord $record)
    {
        return $user->id === $record->class->teacher_id || 
               $user->id === $record->student_id ||
               $user->role === 'admin';
    }
}
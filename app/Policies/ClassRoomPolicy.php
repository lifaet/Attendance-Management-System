<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClassRoom;

class ClassRoomPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'teacher', 'student']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClassRoom $class)
    {
        return $user->role === 'admin' ||
            ($user->role === 'teacher' && $class->teacher_id === $user->id) ||
            ($user->role === 'student' && $class->students()->where('users.id', $user->id)->exists());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClassRoom $class)
    {
        return $user->role === 'admin' ||
            ($user->role === 'teacher' && $class->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClassRoom $class)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can take attendance.
     */
    public function takeAttendance(User $user, ClassRoom $class)
    {
        return $user->role === 'admin' ||
            ($user->role === 'teacher' && $class->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can mark their attendance.
     */
    public function markAttendance(User $user, ClassRoom $class)
    {
        return $user->role === 'student' && 
            $class->students()->where('users.id', $user->id)->exists() &&
            $class->hasActiveSession();
    }
}
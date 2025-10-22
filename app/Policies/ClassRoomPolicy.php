<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClassRoom;

class ClassRoomPolicy
{
    public function update(User $user, ClassRoom $class)
    {
        return $user->role === User::ROLE_ADMIN || 
               ($user->role === User::ROLE_TEACHER && $class->teacher_id === $user->id);
    }

    public function create(User $user)
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_TEACHER]);
    }

    public function takeAttendance(User $user, ClassRoom $class)
    {
        return $user->role === User::ROLE_ADMIN || 
               ($user->role === User::ROLE_TEACHER && $class->teacher_id === $user->id);
    }
}
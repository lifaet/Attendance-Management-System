<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;
    
    public const ROLE_ADMIN = 'admin';
    public const ROLE_TEACHER = 'teacher';
    public const ROLE_STUDENT = 'student';
    
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role'
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'string'
    ];
    
    public function teacherClasses(): HasMany
    {
        return $this->hasMany(ClassRoom::class, 'teacher_id');
    }
    
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(ClassRoom::class, 'class_student', 'student_id', 'class_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isTeacher(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }
}


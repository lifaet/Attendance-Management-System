<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = ['class_id', 'student_id', 'status', 'notes', 'created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at'];

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function class() {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
}

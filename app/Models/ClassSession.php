<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    protected $fillable = ['class_id', 'started_at', 'ended_at', 'status'];
    protected $dates = ['started_at', 'ended_at'];

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && is_null($this->ended_at);
    }

    public function end()
    {
        $this->update([
            'ended_at' => now(),
            'status' => 'ended'
        ]);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'class_id',
        'class_session_id',
        'student_id',
        'status',
        'notes',
        'approval_notes',
        'marked_at',
        'approved_at',
        'approved_by'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'marked_at',
        'approved_at'
    ];

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function class() {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function classSession() {
        return $this->belongsTo(ClassSession::class, 'class_session_id');
    }

    public function approver() {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isApproved(): bool {
        return $this->status === 'present' && $this->approved_at !== null;
    }

    public function isPending(): bool {
        return $this->status === 'pending';
    }

    public function isRejected(): bool {
        return $this->status === 'rejected';
    }

    public function approve(string $notes = null): void {
        $this->update([
            'status' => 'present',
            'approval_notes' => $notes,
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);
    }

    public function reject(string $notes = null): void {
        $this->update([
            'status' => 'rejected',
            'approval_notes' => $notes,
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);
    }
}

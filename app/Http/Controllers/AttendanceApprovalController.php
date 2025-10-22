<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class AttendanceApprovalController extends Controller
{
    public function index()
    {
        $pendingRecords = AttendanceRecord::pending()
            ->whereHas('class', function ($query) {
                $query->where('teacher_id', auth()->id());
            })
            ->with(['student', 'class', 'classSession'])
            ->latest()
            ->paginate(15);

        return view('attendance.pending-approvals', compact('pendingRecords'));
    }

    public function approve(Request $request, AttendanceRecord $record)
    {
        $this->authorize('approve', $record);

        $record->approve(auth()->user(), $request->notes);

        return back()->with('success', 'Attendance record approved successfully.');
    }

    public function reject(Request $request, AttendanceRecord $record)
    {
        $this->authorize('approve', $record);

        $record->reject(auth()->user(), $request->notes);

        return back()->with('success', 'Attendance record rejected.');
    }

    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'records' => 'required|array',
            'records.*' => 'exists:attendance_records,id'
        ]);

        $records = AttendanceRecord::whereIn('id', $validated['records'])
            ->whereHas('class', function ($query) {
                $query->where('teacher_id', auth()->id());
            })
            ->get();

        foreach ($records as $record) {
            $record->approve(auth()->user());
        }

        return back()->with('success', count($records) . ' attendance records approved successfully.');
    }
}
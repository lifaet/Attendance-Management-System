<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassSessionController;
use App\Http\Controllers\StudentAttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth'])
        ->name('dashboard');

    // User management routes (admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    // Class management routes
    Route::prefix('classes')->name('classes.')->group(function () {
        // Base routes for all roles
        Route::get('/', [ClassRoomController::class, 'index'])->name('index');
        Route::get('/{class}', [ClassRoomController::class, 'show'])->name('show');
        
        // Shared attendance routes for admin and teacher
        Route::middleware(['role:admin,teacher'])->group(function () {
            Route::get('/{class}/attendance/report', [AttendanceController::class, 'report'])
                ->name('attendance.report');
            Route::get('/{class}/attendance/pending', [AttendanceController::class, 'pending'])
                ->name('attendance.pending');
            Route::post('/{class}/attendance/{record}/approve', [AttendanceController::class, 'approve'])
                ->name('attendance.approve');
            Route::post('/{class}/attendance/{record}/reject', [AttendanceController::class, 'reject'])
                ->name('attendance.reject');

            // Explicit report route
            Route::get('/{class}/report', [AttendanceController::class, 'report'])
                ->name('attendance.report');
        });

        // Admin only routes
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/create', [ClassRoomController::class, 'create'])->name('create');
            Route::post('/', [ClassRoomController::class, 'store'])->name('store');
            Route::get('/{class}/edit', [ClassRoomController::class, 'edit'])->name('edit');
            Route::put('/{class}', [ClassRoomController::class, 'update'])->name('update');
            Route::delete('/{class}', [ClassRoomController::class, 'destroy'])->name('destroy');
            Route::post('/{class}/assign-teacher', [ClassRoomController::class, 'assignTeacher'])->name('assign.teacher');
            Route::post('/{class}/assign-students', [ClassRoomController::class, 'assignStudents'])->name('assign.students');
        });
        
        // Teacher class and attendance management
        Route::middleware('role:teacher')->group(function () {
            // Session management
            Route::post('/{class}/sessions', [ClassSessionController::class, 'store'])->name('sessions.store');
            Route::put('/sessions/{session}/end', [ClassSessionController::class, 'end'])->name('sessions.end');
            
            // Attendance management
            Route::get('/{class}/attendance/pending', [AttendanceController::class, 'pending'])->name('attendance.pending');
            Route::post('/{class}/attendance/{record}/approve', [AttendanceController::class, 'approve'])->name('attendance.approve');
            Route::post('/{class}/attendance/{record}/reject', [AttendanceController::class, 'reject'])->name('attendance.reject');
        });

        // Student attendance
        Route::middleware('role:student')->group(function () {
            Route::get('/student', [StudentAttendanceController::class, 'index'])->name('student.index');
            Route::post('/{class}/attendance/mark', [StudentAttendanceController::class, 'mark'])->name('attendance.mark');
            Route::get('/{class}/attendance/report', [StudentAttendanceController::class, 'report'])
                ->name('attendance.student.report');
        });
    });
});

require __DIR__.'/auth.php';
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

    // Class Session routes
    Route::middleware(['auth'])->group(function () {
        Route::post('/classes/{class}/sessions', [ClassSessionController::class, 'store'])->name('classes.sessions.store');
        Route::put('/sessions/{session}/end', [ClassSessionController::class, 'end'])->name('classes.sessions.end');
        Route::get('/sessions/{session}', [ClassSessionController::class, 'show'])->name('classes.sessions.show');
    });

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
    
    // Admin and teacher class management
    Route::prefix('classes')->name('classes.')->group(function () {
        // Routes accessible by both admin and teacher
        Route::middleware(['role:admin,teacher'])->group(function () {
            Route::get('/', [ClassRoomController::class, 'index'])->name('index');
            Route::get('/{class}', [ClassRoomController::class, 'show'])->name('show');
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
        
        // Teacher only routes
        Route::middleware('role:teacher')->group(function () {
            Route::get('/{class}/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
            Route::post('/{class}/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
            Route::get('/attendance/{record}', [AttendanceController::class, 'show'])->name('attendance.show');
            Route::get('/attendance/{record}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
            Route::put('/attendance/{record}', [AttendanceController::class, 'update'])->name('attendance.update');
        });

        // Student attendance UI
        Route::middleware('role:student')->group(function () {
            Route::get('/attendance', [StudentAttendanceController::class, 'show'])->name('attendance.student');
            Route::post('/{class}/attendance', [StudentAttendanceController::class, 'mark'])->name('attendance.student.post');
            Route::get('/attendance/stats', [StudentAttendanceController::class, 'stats'])->name('student.attendance.stats');
        });
    });
});

require __DIR__.'/auth.php';
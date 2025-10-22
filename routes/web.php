<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\AttendanceController;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Unified routes are defined below inside the auth+verified group

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
    
    // Class routes
    Route::prefix('classes')->name('classes.')->group(function () {
        Route::get('/', [ClassRoomController::class, 'index'])->name('index');
            Route::get('/create', [ClassRoomController::class, 'create'])->name('create');
            Route::post('/', [ClassRoomController::class, 'store'])->name('store');
        Route::get('/{class}', [ClassRoomController::class, 'show'])->name('show');
            Route::get('/{class}/edit', [ClassRoomController::class, 'edit'])->name('edit');
            Route::put('/{class}', [ClassRoomController::class, 'update'])->name('update');
            Route::delete('/{class}', [ClassRoomController::class, 'destroy'])->name('destroy');
        
        // Teacher only routes
        Route::middleware('role:teacher')->group(function () {
                Route::get('/{class}/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
                Route::post('/{class}/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        });

        // Student attendance UI
        Route::middleware('role:student')->group(function () {
            Route::get('/{class}/mark', [AttendanceController::class, 'studentMarkView'])->name('attendance.student.view');
            Route::post('/{class}/attendance', [AttendanceController::class, 'studentMark'])->name('attendance.student.post');
        });
        
            Route::get('/attendance/{record}', [AttendanceController::class, 'show'])->name('attendance.show');
            Route::get('/attendance/{record}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
            Route::put('/attendance/{record}', [AttendanceController::class, 'update'])->name('attendance.update');
    });
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Inertia Task Page
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

// Handle Form Submission (new task)
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

// Toggle task completion
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

// Delete task
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');



Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

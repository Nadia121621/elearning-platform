<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return auth()->user()->isAdmin()
        ? redirect()->route('admin.courses.index')
        : view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('courses', CourseController::class);

    Route::resource('courses.lessons', LessonController::class)
        ->except(['index', 'show'])
        ->scoped();

    Route::post('courses/{course}/lessons/{lesson}/move', [LessonController::class, 'move'])
        ->scopeBindings()
        ->name('courses.lessons.move');
});

require __DIR__ . '/auth.php';

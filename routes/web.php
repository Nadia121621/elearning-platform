<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\LessonVideoController as AdminLessonVideoController;
use App\Http\Controllers\LessonVideoStreamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\LessonController as StudentLessonController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route(
        auth()->user()->isAdmin() ? 'admin.courses.index' : 'student.courses.index'
    );
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('courses', AdminCourseController::class);

    Route::resource('courses.lessons', AdminLessonController::class)
        ->except(['index', 'show'])
        ->scoped();

    Route::post('courses/{course}/lessons/{lesson}/move', [AdminLessonController::class, 'move'])
        ->scopeBindings()
        ->name('courses.lessons.move');

    Route::post('courses/{course}/lessons/{lesson}/video', [AdminLessonVideoController::class, 'store'])
        ->scopeBindings()
        ->name('courses.lessons.video.store');

    Route::delete('courses/{course}/lessons/{lesson}/video', [AdminLessonVideoController::class, 'destroy'])
        ->scopeBindings()
        ->name('courses.lessons.video.destroy');
});

Route::middleware(['auth', 'role:student'])->name('student.')->group(function () {
    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/lessons/{lesson}', [StudentLessonController::class, 'show'])
        ->scopeBindings()
        ->name('lessons.show');
});

// Pemutaran video: admin (preview) dan student, dicek di controller
Route::get('/courses/{course}/lessons/{lesson}/video', LessonVideoStreamController::class)
    ->middleware(['auth', 'role:admin,student'])
    ->scopeBindings()
    ->name('lessons.video');

require __DIR__ . '/auth.php';

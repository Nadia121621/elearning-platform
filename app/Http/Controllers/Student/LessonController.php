<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson): View
    {
        abort_unless($course->is_published, 404);

        $course->load('lessons');

        return view('student.lessons.show', compact('course', 'lesson'));
    }
}

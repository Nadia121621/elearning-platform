<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::published()
            ->withCount('lessons')
            ->latest()
            ->paginate(9);

        return view('student.courses.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        abort_unless($course->is_published, 404);

        $course->load('lessons');

        return view('student.courses.show', compact('course'));
    }
}

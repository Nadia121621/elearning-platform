<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::withCount('lessons')->latest()->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        $course->load('lessons');

        return view('admin.courses.show', compact('course'));
    }
    public function create(): View
    {
        return view('admin.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['created_by'] = $request->user()->id;

        Course::create($data);

        return redirect()->route('admin.courses.index')
            ->with('status', 'Course berhasil ditambahkan.');
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $course->update($this->validated($request));

        return redirect()->route('admin.courses.index')
            ->with('status', 'Course berhasil diperbarui.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('status', 'Course berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'is_published' => ['required', 'boolean'],
        ]);
    }
}


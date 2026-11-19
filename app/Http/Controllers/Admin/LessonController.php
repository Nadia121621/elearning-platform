<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function create(Course $course): View
    {
        return view('admin.lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        // position diisi otomatis (urutan terakhir) oleh model Lesson
        $course->lessons()->create($this->validated($request));

        return redirect()->route('admin.courses.show', $course)
            ->with('status', 'Lesson berhasil ditambahkan.');
    }

    public function edit(Course $course, Lesson $lesson): View
    {
        return view('admin.lessons.edit', compact('course', 'lesson'));
    }

    public function update(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $lesson->update($this->validated($request));

        return redirect()->route('admin.courses.show', $course)
            ->with('status', 'Lesson berhasil diperbarui.');
    }

    public function destroy(Course $course, Lesson $lesson): RedirectResponse
    {
        $lesson->delete();

        return redirect()->route('admin.courses.show', $course)
            ->with('status', 'Lesson berhasil dihapus.');
    }

    public function move(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $direction = $request->validate([
            'direction' => ['required', 'in:up,down'],
        ])['direction'];

        $neighbor = $course->lessons()
            ->where('position', $direction === 'up' ? '<' : '>', $lesson->position)
            ->reorder('position', $direction === 'up' ? 'desc' : 'asc')
            ->first();

        if ($neighbor) {
            DB::transaction(function () use ($lesson, $neighbor) {
                $a = $lesson->position;
                $b = $neighbor->position;

                $lesson->update(['position' => $b]);
                $neighbor->update(['position' => $a]);
            });
        }

        return redirect()->route('admin.courses.show', $course);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
    }
}

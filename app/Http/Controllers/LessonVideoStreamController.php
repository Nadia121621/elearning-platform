<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LessonVideoStreamController extends Controller
{
    public function __invoke(Request $request, Course $course, Lesson $lesson): BinaryFileResponse
    {
        // Student hanya boleh melihat video dari course yang dipublikasikan
        abort_unless($request->user()->isAdmin() || $course->is_published, 404);
        abort_unless($lesson->hasPlayableVideo(), 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($lesson->video_path), 404);

        // BinaryFileResponse otomatis mendukung Range request (seek video)
        return response()->file($disk->path($lesson->video_path), [
            'Content-Type' => $disk->mimeType($lesson->video_path) ?: 'video/mp4',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}

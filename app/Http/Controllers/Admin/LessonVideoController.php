<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class LessonVideoController extends Controller
{
    public function store(Request $request, Course $course, Lesson $lesson): JsonResponse
    {
        // Validasi dulu: video lama tidak terhapus kalau file baru ditolak
        $request->validate([
            'video' => ['required', 'file', 'mimetypes:video/mp4,video/webm', 'max:204800'], // 200 MB
        ], [
            'video.required' => 'Pilih file video terlebih dahulu.',
            'video.uploaded' => 'Upload gagal. Ukuran file melebihi batas server.',
            'video.mimetypes' => 'Format video harus MP4 atau WebM.',
            'video.max' => 'Ukuran video maksimal 200 MB.',
        ]);

        $lesson->deleteVideoFile();
        $lesson->update([
            'video_path' => null,
            'video_status' => Lesson::STATUS_UPLOADING,
        ]);

        try {
            $path = $request->file('video')->store('videos', 'local');

            if (! $path) {
                throw new \RuntimeException('File video gagal disimpan.');
            }

            $lesson->update([
                'video_path' => $path,
                'video_status' => Lesson::STATUS_READY,
            ]);
        } catch (Throwable $e) {
            report($e);

            $lesson->update([
                'video_path' => null,
                'video_status' => Lesson::STATUS_FAILED,
            ]);

            return response()->json(['message' => 'Video gagal disimpan di server.'], 500);
        }

        return response()->json([
            'status' => $lesson->video_status,
            'message' => 'Video berhasil diunggah.',
        ]);
    }

    public function destroy(Course $course, Lesson $lesson): RedirectResponse
    {
        $lesson->deleteVideoFile();
        $lesson->update([
            'video_path' => null,
            'video_status' => Lesson::STATUS_NONE,
        ]);

        return redirect()->route('admin.courses.lessons.edit', [$course, $lesson])
            ->with('status', 'Video berhasil dihapus.');
    }
}

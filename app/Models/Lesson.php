<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    public const STATUS_NONE = 'none';
    public const STATUS_UPLOADING = 'uploading';
    public const STATUS_READY = 'ready';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'course_id', 'title', 'description', 'position', 'video_path', 'video_status',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function hasPlayableVideo(): bool
    {
        return $this->video_status === self::STATUS_READY && $this->video_path !== null;
    }

    public function deleteVideoFile(): void
    {
        if ($this->video_path) {
            Storage::disk('local')->delete($this->video_path);
        }
    }

    protected static function booted(): void
    {
        // Lesson baru otomatis ditaruh di urutan terakhir
        static::creating(function (Lesson $lesson) {
            if (! $lesson->position) {
                $lesson->position = (int) static::where('course_id', $lesson->course_id)->max('position') + 1;
            }
        });

        // Hapus file video dari storage saat lesson dihapus
        static::deleting(function (Lesson $lesson) {
            $lesson->deleteVideoFile();
        });
    }
}

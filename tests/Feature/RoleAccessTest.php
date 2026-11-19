<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    private function course(bool $published, string $title = 'Course Uji'): Course
    {
        return Course::create([
            'created_by' => $this->user('admin')->id,
            'title' => $title,
            'description' => 'Deskripsi course uji.',
            'is_published' => $published,
        ]);
    }

    private function readyLesson(Course $course): Lesson
    {
        Storage::fake('local');
        Storage::disk('local')->put('videos/uji.mp4', 'konten-video-uji');

        return $course->lessons()->create([
            'title' => 'Lesson Uji',
            'video_path' => 'videos/uji.mp4',
            'video_status' => Lesson::STATUS_READY,
        ]);
    }

    public function test_guest_is_redirected_to_login_from_admin_area(): void
    {
        $this->get('/admin/courses')->assertRedirect('/login');
    }

    public function test_student_cannot_access_admin_area(): void
    {
        $student = $this->user('student');

        $this->actingAs($student)->get('/admin/courses')->assertForbidden();
        $this->actingAs($student)->post('/admin/courses', [
            'title' => 'Tidak boleh',
            'description' => 'Tidak boleh',
            'is_published' => 1,
        ])->assertForbidden();

        $this->assertDatabaseMissing('courses', ['title' => 'Tidak boleh']);
    }

    public function test_admin_can_access_admin_area(): void
    {
        $this->actingAs($this->user('admin'))->get('/admin/courses')->assertOk();
    }

    public function test_student_sees_only_published_courses(): void
    {
        $this->course(true, 'Course Terbit');
        $draft = $this->course(false, 'Course Rahasia');

        $this->actingAs($this->user('student'))
            ->get('/courses')
            ->assertOk()
            ->assertSee('Course Terbit')
            ->assertDontSee('Course Rahasia');

        $this->actingAs($this->user('student'))
            ->get('/courses/' . $draft->id)
            ->assertNotFound();
    }

    public function test_guest_cannot_stream_video(): void
    {
        $course = $this->course(true);
        $lesson = $this->readyLesson($course);

        $this->get(route('lessons.video', [$course, $lesson]))->assertRedirect('/login');
    }

    public function test_student_cannot_stream_video_of_draft_course(): void
    {
        $course = $this->course(false);
        $lesson = $this->readyLesson($course);

        $this->actingAs($this->user('student'))
            ->get(route('lessons.video', [$course, $lesson]))
            ->assertNotFound();
    }

    public function test_student_can_stream_video_of_published_course(): void
    {
        $course = $this->course(true);
        $lesson = $this->readyLesson($course);

        $this->actingAs($this->user('student'))
            ->get(route('lessons.video', [$course, $lesson]))
            ->assertOk();
    }

    public function test_admin_upload_rejects_non_video_file(): void
    {
        Storage::fake('local');
        $course = $this->course(true);
        $lesson = $course->lessons()->create(['title' => 'Lesson Uji']);

        $this->actingAs($this->user('admin'))
            ->postJson(route('admin.courses.lessons.video.store', [$course, $lesson]), [
                'video' => UploadedFile::fake()->create('catatan.pdf', 100, 'application/pdf'),
            ])
            ->assertStatus(422);

        $this->assertNull($lesson->fresh()->video_path);
    }

    public function test_student_cannot_upload_video(): void
    {
        Storage::fake('local');
        $course = $this->course(true);
        $lesson = $course->lessons()->create(['title' => 'Lesson Uji']);

        $this->actingAs($this->user('student'))
            ->postJson(route('admin.courses.lessons.video.store', [$course, $lesson]), [
                'video' => UploadedFile::fake()->create('uji.mp4', 100, 'video/mp4'),
            ])
            ->assertForbidden();
    }

    public function test_public_registration_creates_student_only(): void
    {
        $this->post('/register', [
            'name' => 'Pendaftar Baru',
            'email' => 'baru@example.com',
            'password' => 'password-uji-123',
            'password_confirmation' => 'password-uji-123',
        ]);

        $this->assertSame('student', User::where('email', 'baru@example.com')->value('role'));
    }
}

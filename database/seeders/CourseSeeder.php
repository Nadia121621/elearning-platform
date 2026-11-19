<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@elearning.test')->firstOrFail();

        $courses = [
            [
                'title' => 'Dasar Pemrograman PHP',
                'description' => 'Pelajari sintaks PHP, tipe data, fungsi, dan pemrograman berorientasi objek dari nol.',
                'is_published' => true,
                'lessons' => ['Pengenalan PHP', 'Variabel dan Tipe Data', 'Fungsi dan Array', 'Dasar OOP'],
            ],
            [
                'title' => 'Membangun Aplikasi dengan Laravel',
                'description' => 'Routing, Eloquent ORM, Blade, autentikasi, dan deployment aplikasi Laravel.',
                'is_published' => true,
                'lessons' => ['Instalasi dan Struktur Proyek', 'Routing dan Controller', 'Eloquent dan Migration'],
            ],
            [
                'title' => 'Dasar Database MySQL',
                'description' => 'Draft materi SQL untuk pemula. Belum dipublikasikan.',
                'is_published' => false,
                'lessons' => ['Pengenalan SQL'],
            ],
        ];

        foreach ($courses as $data) {
            $lessons = $data['lessons'];
            unset($data['lessons']);

            $course = Course::updateOrCreate(
                ['title' => $data['title']],
                $data + ['created_by' => $admin->id]
            );

            foreach ($lessons as $i => $title) {
                $course->lessons()->updateOrCreate(
                    ['title' => $title],
                    ['position' => $i + 1, 'description' => "Materi: {$title}."]
                );
            }
        }
    }
}

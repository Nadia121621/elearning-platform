# Simple E-Learning Platform

Aplikasi e-learning sederhana: Admin mengelola course dan lesson berupa video, Student melihat course dan memutar video lesson. Dibuat untuk Technical Assessment Web Developer (Track A).

## Teknologi dan alasan pemilihan

| Teknologi | Alasan |
|---|---|
| Laravel 13 (PHP 8.3) | Framework yang diwajibkan; routing, validasi, Eloquent, dan storage bawaan mempercepat pengerjaan |
| MySQL | Relasi sederhana (user, course, lesson) cocok untuk database relasional |
| Blade + Tailwind CSS | Server-side rendering cukup untuk kebutuhan ini; kode lebih sedikit dan mudah dipahami |
| Laravel Breeze | Autentikasi standar yang teruji, sehingga waktu difokuskan ke fitur inti |
| JavaScript (XHR) | Upload asynchronous dengan progress bar, tanpa tambahan library |

## Fitur

- Login untuk Admin dan Student, dengan pembatasan halaman per role (middleware `role`)
- Admin: CRUD course (judul, deskripsi, status publikasi)
- Admin: CRUD lesson dan pengurutan lesson (tombol naik/turun)
- Admin: upload video per lesson dengan progress bar, status `none`/`uploading`/`ready`/`failed`, ganti dan hapus video
- Student: daftar course published, detail course beserta lesson, pemutar video
- Video disimpan di private storage dan hanya bisa diputar lewat route yang mengecek login, role, dan status publikasi course

## Requirement

- PHP 8.3 atau lebih baru (ekstensi umum Laravel: mbstring, openssl, pdo_mysql, fileinfo)
- Composer
- MySQL 8 (atau MariaDB setara)
- Node.js 20.19+ atau 22.12+ dan npm

## Instalasi

    git clone https://github.com/Nadia121621/elearning-platform.git elearning-platform
    cd elearning-platform

    composer install
    npm install

    cp .env.example .env
    php artisan key:generate

Di Windows PowerShell, ganti `cp .env.example .env` dengan `Copy-Item .env.example .env`.

## Konfigurasi

Buat database kosong, misalnya `elearning_platform`. File `.env.example` bawaan Laravel memakai SQLite, jadi ubah pengaturan database di `.env` menjadi MySQL seperti berikut:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=elearning_platform
    DB_USERNAME=root
    DB_PASSWORD=

Atur batas upload di `php.ini` agar video besar tidak gagal:

    upload_max_filesize = 256M
    post_max_size = 260M

Aplikasi membatasi video maksimal 200 MB. Restart server PHP setelah mengubah `php.ini`.

## Menjalankan

    php artisan migrate --seed
    npm run build
    php artisan serve

Buka http://127.0.0.1:8000. Untuk pengembangan, jalankan `npm run dev` di terminal lain.

## Akun demo

| Role | Email | Password |
|---|---|---|
| Admin | admin@elearning.test | Admin123! |
| Student | student@elearning.test | Student123! |

Seeder juga membuat 3 course dummy (2 published, 1 draft) beserta lesson-nya. Video belum ada di data awal; unggah lewat halaman Edit Lesson sebagai Admin.

## Struktur penting

    app/Http/Controllers/Admin/    CRUD course, lesson, dan upload video
    app/Http/Controllers/Student/  Tampilan course dan lesson untuk student
    app/Http/Controllers/LessonVideoStreamController.php   Streaming video dengan otorisasi
    app/Http/Middleware/EnsureRole.php                      Pembatasan akses per role
    app/Models/                    User, Course, Lesson
    database/migrations/           Skema database
    database/seeders/              Akun dan data demo
    docs/DESIGN.md                 System design, ERD, flowchart, asumsi, keterbatasan

## Dokumentasi desain

Lihat [docs/DESIGN.md](docs/DESIGN.md) untuk system design, ERD, sequence diagram, asumsi, dan daftar keterbatasan.

## Pengujian

    php artisan test

Tes memakai SQLite in-memory (butuh ekstensi `pdo_sqlite`), sehingga tidak menyentuh database MySQL. `tests/Feature/RoleAccessTest.php` memverifikasi pembatasan akses per role, course draft yang tidak terlihat student, otorisasi pemutaran video, validasi upload, dan registrasi publik yang hanya menghasilkan Student.




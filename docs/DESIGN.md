# Dokumen Desain

## 1. System design

```mermaid
flowchart LR
    B[Browser] -->|HTTP| L[Laravel: routes + middleware auth/role]
    L --> C[Controllers]
    C --> M[(MySQL: users, courses, lessons)]
    C --> S[Private storage: storage/app/private/videos]
    L -->|stream dengan otorisasi| B
```

Komponen utama:
- **Laravel** menangani routing, autentikasi (Breeze), otorisasi per role (middleware `role`), validasi, dan rendering Blade.
- **MySQL** menyimpan user, course, dan lesson (termasuk path dan status video).
- **Private storage** menyimpan file video di luar folder `public`, sehingga tidak ada URL langsung ke file.

## 2. ERD

```mermaid
erDiagram
    USERS ||--o{ COURSES : "dibuat oleh admin"
    COURSES ||--o{ LESSONS : memiliki

    USERS {
        bigint id PK
        string name
        string email UK
        string password
        string role "admin | student"
    }
    COURSES {
        bigint id PK
        bigint created_by FK
        string title
        text description
        boolean is_published
    }
    LESSONS {
        bigint id PK
        bigint course_id FK
        string title
        text description
        unsigned_int position
        string video_path
        string video_status "none | uploading | ready | failed"
    }
```

## 3. Alur pengelolaan course dan lesson (Admin)

```mermaid
flowchart TD
    A[Admin login] --> B[Daftar course]
    B --> C{Aksi}
    C -->|Tambah/Edit/Hapus| D[Form course + validasi]
    C -->|Buka course| E[Daftar lesson]
    E --> F{Aksi}
    F -->|Tambah/Edit/Hapus| G[Form lesson + validasi]
    F -->|Naik/Turun| H[Tukar position dengan lesson tetangga]
    F -->|Edit lesson| I[Upload video]
```

## 4. Alur upload dan pemutaran video

```mermaid
sequenceDiagram
    actor A as Admin
    actor S as Student
    participant B as Browser
    participant L as Laravel
    participant D as Private storage

    A->>B: Pilih file video
    B->>L: POST video (XHR, progress bar)
    L->>L: Validasi tipe dan ukuran
    L->>L: status = uploading
    L->>D: Simpan file
    alt berhasil
        L->>L: status = ready
    else gagal
        L->>L: status = failed
    end
    L-->>B: JSON hasil

    S->>B: Buka lesson
    B->>L: GET /courses/{id}/lessons/{id}/video
    L->>L: Cek login, role, course published, status ready
    L->>D: Baca file
    L-->>B: Stream video (mendukung Range / seek)
```

## 5. Keputusan desain dan asumsi

- Satu user memiliki satu role (kolom `role`), tanpa tabel roles terpisah agar tetap sederhana. Pendaftaran publik hanya menghasilkan Student; role Admin hanya dari seeder.
- Student hanya melihat course berstatus published. Course draft menghasilkan 404, bukan 403, agar keberadaannya tidak terbuka.
- Admin boleh memutar video semua course (untuk preview).
- Video dibatasi MP4 dan WebM, maksimal 200 MB, karena kedua format ini diputar langsung oleh browser tanpa transcoding.
- Pengurutan lesson memakai kolom `position`. Lesson baru otomatis menempati urutan terakhir; tombol naik/turun menukar posisi dengan lesson tetangga dalam satu transaksi.
- Menghapus lesson atau course ikut menghapus file video dari storage.
- Menghapus course menghapus seluruh lesson-nya (cascade).
- Upload memakai satu request XHR. Status `uploading` terlihat singkat karena file diterima penuh dulu baru disimpan.

## 6. Keterbatasan

- Upload tidak bisa dilanjutkan (resume) bila koneksi terputus; harus diulang.
- Tidak ada transcoding, thumbnail, atau durasi video (sesuai batasan assessment).
- Status `uploading` tidak dibersihkan otomatis bila proses PHP berhenti di tengah upload; admin perlu mengunggah ulang.
- Belum ada pelacakan progres belajar student, pendaftaran ke course, atau pencarian.
- Pengurutan lesson memakai tombol naik/turun, belum drag-and-drop.
- Pengujian otomatis masih minim (lihat bagian pengujian jika ditambahkan).

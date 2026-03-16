# ZeroCourse: LMS Platform

Platform pembelajaran daring (*Learning Management System*) yang dibangun untuk mengelola kursus, modul materi, dan progres belajar siswa secara sistematis dengan alur belajar yang terproteksi.

## 🚀 Fitur Utama

- **Sequential Learning Path**: Sistem kunci materi yang memaksa siswa menyelesaikan materi sebelumnya sebelum membuka materi baru.
- **Progress Tracking**: Visualisasi progres belajar siswa secara *real-time* di dashboard.
- **Role-Based Access**: Manajemen kursus khusus untuk instruktur dan alur belajar untuk siswa.
- **Interactive Material**: Mendukung konten materi yang fleksibel dan navigasi yang intuitif (Prev/Next lesson).

## 🛠 Tech Stack

- **Framework**: Laravel 10
- **Frontend**: Tailwind CSS & Blade
- **Database**: MySQL
- **Logic**: Eloquent ORM, Collection Methods, Middleware-based Security

## 📸 Tampilan Proyek

*(Tambahkan screenshot dashboard siswa atau halaman belajar di sini)*

## 📦 Instalasi

1. Clone repository:
   ```bash
   git clone [URL-REPO-MU]

2. Install dependensi:
    composer install
    npm install && npm run build

3. Konfigurasi .env:
    cp .env.example .env
    php artisan key:generate

4. Jalankan migrasi:
    php artisan migrate

5. Jalankan Server:
    php artisan serve
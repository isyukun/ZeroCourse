# ZeroCourse: LMS Platform

Platform pembelajaran daring (*Learning Management System*) yang dibangun untuk mengelola kursus, modul materi, dan progres belajar siswa secara sistematis dengan alur belajar yang terproteksi.

## 🚀 Fitur Utama

- **Sequential Learning Path**: Sistem kunci materi (Locked Content) yang memastikan siswa menyelesaikan materi secara berurutan.
- **Progress Tracking**: Pencatatan otomatis progres belajar siswa per materi dan per modul.
- **Interactive Quiz Player**: Sistem kuis dengan kalkulasi skor otomatis dan ambang batas kelulusan (Minimum Score).
- **Instructor Dashboard**: Alat manajemen kursus lengkap (CRUD Course, Module, & Lesson) khusus untuk role pengajar.
- **Dynamic Navigation**: Navigasi intuitif (Prev/Next lesson) yang mendukung perpindahan materi lintas modul.

## 🛠 Tech Stack

- **Framework**: Laravel 11.x (Latest)
- **PHP Version**: 8.3+
- **Frontend**: Tailwind CSS & Laravel Blade
- **Database**: MySQL
- **Dependencies**: 
    - `barryvdh/laravel-dompdf` (Untuk fitur Sertifikat)
    - `Breeze` (Authentication starter kit)

## 📸 Tampilan Proyek
![Dashboard Preview](link-ke-gambar-kamu)

## 📦 Instalasi & Konfigurasi

1. **Clone Repository**:
   ```bash
   git clone [https://github.com/username/zerocourse.git](https://github.com/username/zerocourse.git)
   cd zerocourse

2. Install Dependencies:
    composer install
    npm install && npm run build

3. Environment Setup:
    cp .env.example .env
    php artisan key:generate

4. Database Migration & Seeding:
    php artisan migrate --seed

5. Run Development Server:
    php artisan serve
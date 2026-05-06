<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Sistem Manajemen Penjadwalan Shift & KPI Agen - Capstone Project
Sistem berbasis web ini dirancang untuk mengotomatisasi pengelolaan jadwal operasional dan perhitungan metrik kinerja (KPI) agen di PT Tirta Gracia Abadi. Dibangun menggunakan Laravel 12 untuk memastikan performa yang mutakhir dan keamanan data yang optimal.
🚀 Fitur Utama
•	Role-Based Access Control (RBAC): Pemisahan hak akses yang tegas antara Supervisor (Manajemen penuh) dan Agen (Akses personal).
•	Hybrid Scheduling System: Dukungan input jadwal secara manual melalui antarmuka web maupun secara masal melalui Import File CSV.
•	Otomatisasi Kalkulasi KPI: Perhitungan otomatis nilai akhir berdasarkan metrik Quality Assurance (QA), Average Handling Time (AHT), dan tingkat kehadiran.
•	Personal Agent Dashboard: Halaman khusus bagi agen untuk memantau jadwal kerja harian dan hasil evaluasi kinerja secara privat.
•	Responsive UI: Antarmuka yang ergonomis menggunakan Tailwind CSS untuk mengurangi beban kognitif dan kelelahan visual.
🛠️ Tech Stack
•	Framework: Laravel 12.x
•	Language: PHP 8.2+
•	Database: MySQL
•	Frontend: Tailwind CSS & Blade Templating
•	Server Environment: Laragon / XAMPP

💻 Panduan Instalasi Lokal
Ikuti langkah-langkah di bawah ini untuk menjalankan program di perangkat masing-masing:
1. Persiapan Lingkungan
- Pastikan Anda sudah menginstal Laragon (direkomendasikan), Composer, dan Node.js di laptop Anda.

2.  Clone Repositori
Buka terminal/git , lalu jalankan perintah:
- git clone https://github.com/username/nama-repo.git
- cd nama-repo

4. Instalasi Dependency
Instal paket PHP dan JavaScript yang dibutuhkan:
- composer install
- npm install

5. Konfigurasi Environment
Salin file .env.example menjadi .env:
cp .env.example .env
Buka file .env di VS Code, lalu sesuaikan konfigurasi database:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=

7. Generate Application Key
- php artisan key:generate

7. Migrasi Database & Seeding
Buat database di MySQL (via Laragon/phpMyAdmin) sesuai dengan nama di .env, lalu jalankan migrasi untuk membuat tabel dan data awal (admin):
 - php artisan migrate --seed

8. Menjalankan Aplikasi
Buka dua terminal, lalu jalankan perintah berikut secara bersamaan:
Terminal 1 (Server PHP):
 - php artisan serve
Terminal 2 (Compiler Asset):
 - npm run dev
Aplikasi kini dapat diakses melalui browser di alamat http://127.0.0.1:8000.

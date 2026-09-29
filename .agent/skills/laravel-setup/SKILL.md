# Skill: Setup Laravel 11 & Authentication (Tanjung Jaya)

**Deskripsi:** 
Panduan standar yang digunakan oleh Agent untuk menginisialisasi proyek Laravel 11 untuk sistem e-commerce "Tanjung Jaya", mengkonfigurasi koneksi *database* (SQLite), dan menyetel sistem autentikasi dasar (Laravel Breeze).

## 1. Persyaratan Sistem
- **PHP**: Minimum versi 8.2+ (Direkomendasikan PHP 8.4)
- **Composer**: Terinstal secara global.
- **Node.js & NPM**: Untuk *compiling assets* (Tailwind CSS).

## 2. Instalasi Proyek Laravel 11
Gunakan *command* berikut untuk membuat kerangka *project* baru:
```bash
composer create-project laravel/laravel tanjung-jaya-ecommerce
cd tanjung-jaya-ecommerce
```

## 3. Konfigurasi Database (.env)
Proyek ini menggunakan **SQLite** agar *setup* lebih ringkas, cepat, dan *portable* (terutama untuk *testing* portofolio). Buka file `.env` dan pastikan konfigurasi *database* mengarah ke SQLite:

```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```
*(Catatan: File `database/database.sqlite` akan terbuat secara otomatis ketika Anda menjalankan migration pertama).*

## 4. Instalasi Autentikasi (Laravel Breeze + Blade)
Tanjung Jaya menggunakan **Laravel Breeze** dengan kombinasi **Blade + Tailwind CSS** (sesuai *stack* standar Laravel modern). 

Jalankan urutan *command* berikut:
1. **Require package Breeze:**
   ```bash
   composer require laravel/breeze --dev
   ```
2. **Install stack Blade:**
   ```bash
   php artisan breeze:install blade
   ```
3. **Jalankan Migration & Seeder:**
   ```bash
   php artisan migrate:fresh --seed
   ```
4. **Compile CSS & JS (Tailwind):**
   ```bash
   npm install
   npm run build
   ```

## 5. Kriteria Keberhasilan (Success Criteria)
- Ketika *project* dijalankan (`php artisan serve`), halaman utama sukses memuat CSS Tailwind tanpa *error* di *console*.
- Terdapat menu **Log in** dan **Register** di pojok kanan atas web.
- Proses registrasi dan *login* *user* berhasil disimpan langsung ke dalam file `database/database.sqlite`.

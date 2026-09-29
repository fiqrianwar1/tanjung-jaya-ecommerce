# Workflow: Database Reset & Verification

**Deskripsi:** 
Workflow ini digunakan ketika *Developer* atau *Agent* perlu membersihkan *database* secara total, membangun ulang seluruh skema tabel dari awal, dan mengisinya kembali dengan data *dummy* (Seeder). Workflow ini juga mencakup langkah verifikasi untuk memastikan tidak ada data yang gagal di-*generate*.

## 1. Eksekusi Reset & Seed
Jalankan perintah berikut di terminal aplikasi:

```bash
php artisan migrate:fresh --seed
```

**Ekspektasi Hasil:**
- Muncul log `Dropping all tables .. DONE`.
- Seluruh file *migration* di-eksekusi ulang tanpa pesan *error* relasi kunci asing (*foreign key constraint*).
- Muncul log eksekusi `UserSeeder`, `CategorySeeder`, `ProductSeeder`, dan `OrderSeeder` dengan status `DONE`.

## 2. Verifikasi Ketersediaan Data (Sanity Check)
Setelah reset selesai, wajib memverifikasi jumlah data (*record*) di setiap tabel utama untuk memastikan Factory tidak gagal di tengah jalan. 

Jalankan **Laravel Tinker**:
```bash
php artisan tinker
```

Lalu jalankan skrip pengecekan berikut di dalam Tinker:
```php
echo "\n--- HASIL VERIFIKASI SEEDER ---\n";
echo "Total Users      : " . \App\Models\User::count() . "\n";
echo "Total Categories : " . \App\Models\Category::count() . "\n";
echo "Total Products   : " . \App\Models\Product::count() . "\n";
echo "Total Orders     : " . \App\Models\Order::count() . "\n";
echo "Total OrderItems : " . \App\Models\OrderItem::count() . "\n";
echo "-------------------------------\n";
```

## 3. Kriteria Keberhasilan (Success Criteria)
Workflow ini dianggap berhasil jika *output* dari verifikasi Tinker di atas memenuhi standar minimal berikut:
- **Users**: Minimal 14 *records* (Terdiri dari 4 akun Role statis + 10 akun Factory).
- **Categories**: Tepat 5 *records*.
- **Products**: Tepat 25 *records*.
- **Orders**: Tepat 30 *records*.

Jika jumlah *record* mengembalikan nilai `0` atau terjadi *error* pada tahap verifikasi, periksa kembali *log error* di `storage/logs/laravel.log`.

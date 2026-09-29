# Skill: Pembuatan Laravel Migration 11 (Tanjung Jaya E-Commerce)

Skill ini dirancang untuk memandu Agent dalam melakukan *generate* atau memodifikasi file *Database Migration* di kerangka kerja Laravel 11 agar selalu 100% konsisten dengan desain ERD yang ada.

## 1. Referensi Kebenaran (Single Source of Truth)
Selalu merujuk ke file **`docs/database/erd.dbml`** untuk memastikan tipe data, nama kolom, nilai *default*, dan relasi.

## 2. Urutan Pembuatan Tabel (Migration Order)
Agar *Foreign Key Constraint* dapat dibuat tanpa error (misal MySQL error `150 Foreign Key Constraint Fails`), migration tabel referensi (Induk) harus dieksekusi lebih dahulu dari tabel turunan (Anak). Ikuti urutan prioritas ini:

1. **Level 0 (Independent Tables):** `users`, `categories`
2. **Level 1 (Direct Children):** `products` (butuh `categories`), `carts` (butuh `users`)
3. **Level 2 (Transactions):** `orders` (butuh `users`)
4. **Level 3 (Pivot & Log Tables):** 
   - `order_items` (butuh `orders`, `products`)
   - `reviews` (butuh `users`, `products`)
   - `return_requests` (butuh `orders`)
   - `audit_logs` (butuh `users`)
   - `stock_logs` (butuh `products`, `users`)
   - `cart_items` (butuh `carts`, `products`)
   - `user_activities` (butuh `users`, `products`)
   - `report_subscriptions` (butuh `users`)

## 3. Konvensi Tipe Data & Relasi
Saat merumuskan fungsi `Schema::create` di PHP, gunakan pedoman berikut:

- **Primary Key:** Selalu gunakan `$table->id();` (menghasilkan BIGINT Unsigned Auto Increment).
- **Foreign Key:** Gunakan *Constrained Method*. Jika nama kolom tidak standar, sebutkan nama tabelnya secara eksplisit.
  ```php
  $table->foreignId('user_id')->constrained()->cascadeOnDelete();
  $table->foreignId('manager_id')->constrained('users')->cascadeOnDelete();
  ```
- **String:** Gunakan `$table->string('column');` untuk varchar. Jika unik, tambah `->unique()`.
- **Text & JSON:** Gunakan `$table->text('column')->nullable();` atau `$table->json('column')->nullable();` jika data tidak wajib diisi.
- **Desimal (Uang):** Gunakan `$table->decimal('price', 15, 2);` agar presisi 2 angka di belakang koma terjaga.
- **Enum:** Gunakan metode enum dengan menetapkan array opsi dan nilai default.
  ```php
  $table->enum('role', ['Customer', 'Admin', 'Gudang', 'Manager'])->default('Customer');
  ```
- **Boolean:** `$table->boolean('emailed')->default(false);`

## 4. Timestamps & Soft Deletes
- **Timestamps:** **Wajib** meletakkan `$table->timestamps();` di bagian paling akhir setiap pembuatan tabel (menyediakan `created_at` dan `updated_at`).
- **Soft Deletes:** Untuk mencegah rusaknya historis riwayat *Audit Trail* maupun laporan *Machine Learning*, data entitas master tidak boleh dihapus secara permanen. Tambahkan kolom `$table->softDeletes();` pada tabel:
  - `users`
  - `products`
  - `orders`

## 5. Indexing (Performa)
Walaupun *foreign keys* otomatis mendapatkan index oleh DBMS, pertimbangkan menambahkan metode *chaining* `->index()` untuk atribut spesifik yang di-query massal atau difilter terus-menerus (contoh: Status Order atau Type Log).
  ```php
  $table->string('status')->default('Menunggu Pembayaran')->index();
  ```

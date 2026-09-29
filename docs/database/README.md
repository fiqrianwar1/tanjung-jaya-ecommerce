# Database Schema & Relasi (Tanjung Jaya E-Commerce)

Dokumen ini mendeskripsikan rancangan struktur database yang ditulis dalam format [DBML](https://dbml.dbdiagram.io/home/). Anda dapat menyalin isi dari file `erd.dbml` ke situs web [dbdiagram.io](https://dbdiagram.io) untuk merender grafis ERD-nya.

Semua penamaan tabel menggunakan format standar konvensi Laravel yaitu _snake_case plural_ (contoh: `order_items`, bukan `OrderItem`).

## Penjelasan Relasi Antar Tabel

### 1. Produk & Kategori
- **`categories` (1) ke `products` (N)**: Satu kategori bisa memiliki banyak produk. Apabila kategori dihapus, produk yang bersangkutan juga akan ikut terhapus otomatis via constraint `cascadeOnDelete`.

### 2. Transaksi Utama
- **`users` (1) ke `orders` (N)**: Satu user (Customer) dapat membuat banyak order transaksi.
- **`orders` (1) ke `order_items` (N)**: Satu order berisi satu atau lebih item pesanan.
- **`products` (1) ke `order_items` (N)**: Tabel _pivot_ yang menghubungkan produk yang dibeli ke dalam order.

### 3. Ekosistem Order Lanjutan (Retur & Ulasan)
- **`orders` (1) ke `return_requests` (1)**: Satu pesanan maksimal hanya bisa memiliki 1 pengajuan Retur / RMA.
- **`users` (1) ke `reviews` (N)**: Satu customer bisa memberikan ulasan berulang pada produk berbeda.
- **`products` (1) ke `reviews` (N)**: Satu produk memiliki banyak ulasan dari berbagai pengguna.

### 4. Ekosistem Gudang (Warehouse)
- **`products` (1) ke `stock_logs` (N)**: Setiap pergerakan stok aktual dari suatu produk dicatat historisnya ke dalam kartu log.
- **`users` (1) ke `stock_logs` (N)**: Relasi yang mengidentifikasi karyawan Gudang mana yang meng-input/mengubah angka stok tersebut.

### 5. Ekosistem AI & Cart Recovery
- **`users` (1) ke `carts` (1)**: Setiap *user* memiliki satu keranjang belanja permanen. 
- **`carts` (1) ke `cart_items` (N)**: Keranjang belanja diisi dengan item keranjang (produk). (Note: Jika tabel `carts` ini terabaikan > 24 jam tanpa *checkout*, cron job *Abandoned Cart* akan mengubah flag `emailed` menjadi true).
- **`users` (1) ke `user_activities` (N)** dan **`products` (1) ke `user_activities` (N)**: Menyimpan rekam jejak setiap interaksi *user* pada sebuah *product*. Data ini digunakan secara _headless_ oleh Machine Learning untuk sistem Rekomendasi (Collaborative Filtering).

### 6. Audit & Report
- **`users` (1) ke `audit_logs` (N)**: Log keamanan yang mencatat seluruh aksi manipulasi sistem oleh Admin/Manager. Relasi ini digunakan untuk pelacakan *Impersonation* dan keamanan sistem.
- **`users` (1) ke `report_subscriptions` (N)**: Menyimpan preferensi Manager yang berlangganan laporan otomatis berbasis email.

### Fitur Tambahan & Sistem Pakar
- **user_activities:** Data mentah pencatatan interaksi user dengan produk untuk *machine learning* rekomendasi (view, click, cart, dll).
- **report_subscriptions:** Mengelola jadwal langganan laporan otomatis Manager via Cron.
- **wishlists:** Menyimpan produk favorit pengguna (N:M relasi antara users dan products).

---
Dengan struktur yang solid ini, *schema builder* Laravel (`Illuminate\Database\Schema\Blueprint`) akan sangat mudah dibangun, menekan duplikasi, dan aman secara integritas relasional (Referential Integrity).

# Tanjung Jaya Corporation â€” Aplikasi E-Commerce

Aplikasi e-commerce untuk **PT. Warna Tanjung Jaya & PT. Sumber Tanjung Jaya** (toko bangunan & peralatan rumah tangga, Banjarmasin). Dibangun dengan Laravel + Blade + Alpine.js + Tailwind CSS.

## Fitur Utama

### Pelanggan (Customer)
- Katalog produk dengan pencarian, filter kategori, banner promo, dan blok rekomendasi produk.
- Keranjang belanja (ubah jumlah, hapus item, checkout dengan validasi stok).
- Riwayat pesanan, pelacakan status, ulasan produk, pengajuan retur, dan wishlist.

### Administrator
- CRUD produk & kategori (upload gambar, filter harga/stok).
- Monitoring & update status pesanan (kurir + nomor resi).
- Moderasi ulasan (publish/hidden + balasan admin).
- Verifikasi pengajuan retur (setujui/tolak + pengembalian stok).
- Manajemen peran pengguna dan audit log aktivitas.

### Gudang
- Monitor stok & riwayat mutasi inventori (in/out/adjustment).
- Antrian pengiriman pesanan dan input nomor resi.
- Penerimaan barang retur fisik (layak jual vs stok rusak).

### Manager
- Dashboard eksekutif (pendapatan, tren penjualan, produk terlaris).
- Laporan penjualan per rentang tanggal + ekspor CSV.
- Langganan laporan otomatis (harian/mingguan/bulanan).

### Asisten AI (Chatbot)
Widget chat di halaman publik/katalog yang di-*grounding* ke katalog asli sehingga hanya menjawab seputar produk Tanjung Jaya (harga, stok, rekomendasi, cara belanja).

## Kebutuhan Sistem
- PHP 8.3+ dengan ekstensi standar Laravel
- Composer 2
- Node.js 20+ & npm
- Database: MySQL/MariaDB (default Laravel Herd) atau SQLite

## Instalasi

```bash
composer install
copy .env.example .env      # Linux/macOS: cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build               # wajib: meng-generate public/build (Alpine.js)
php artisan storage:link
php artisan serve
```

## Konfigurasi Asisten AI (Groq)

Isi kredensial pada `.env`:

```env
GROQ_API_KEY=isi_api_key_anda
GROQ_API_URL=https://api.groq.com/openai/v1/chat/completions
GROQ_MODEL=openai/gpt-oss-120b
```

> Nama model Groq dapat berubah/dipensiunkan. Cek daftar model aktif lewat
> `GET https://api.groq.com/openai/v1/models` memakai API key Anda, lalu sesuaikan `GROQ_MODEL`.
> Bila model utama tidak tersedia, layanan otomatis mencoba model cadangan (`openai/gpt-oss-20b`).

## Akun Demo (hasil seeder)

| Peran    | Email                    | Password |
|----------|--------------------------|----------|
| Admin    | admin@tanjungjaya.com    | password |
| Manager  | manager@tanjungjaya.com  | password |
| Gudang   | gudang@tanjungjaya.com   | password |
| Customer | customer@tanjungjaya.com | password |

## Pengujian & Tangkapan Layar

Proyek ini memakai [Playwright](https://playwright.dev/) untuk *smoke test* setiap halaman per peran sekaligus menghasilkan screenshot dokumentasi.

```bash
php artisan serve                                   # di terminal terpisah
npx playwright test tests/e2e/smoke-screenshots.spec.ts
npx playwright test tests/e2e/register.spec.ts
```

Hasil screenshot tersimpan di `docs/screenshots/` (katalog, keranjang, pesanan, admin, gudang, manager, dan jawaban chatbot).

## Hak Akses per Peran

| Modul     | Prefix      | Middleware      |
|-----------|-------------|-----------------|
| Customer  | `/carts`, `/orders`, `/reviews`, `/returns`, `/wishlists` | `role:Customer` |
| Admin     | `/admin/*`  | `role:Admin`    |
| Gudang    | `/gudang/*` | `role:Gudang`   |
| Manager   | `/manager/*`| `role:Manager`  |


## Lisensi

Proyek internal Tanjung Jaya Corporation. Dibangun di atas framework [Laravel](https://laravel.com) (MIT).


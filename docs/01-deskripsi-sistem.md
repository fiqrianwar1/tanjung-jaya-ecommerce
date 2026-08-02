# Analisis Kebutuhan Sistem E-Commerce Tanjung Jaya

## 1. Latar Belakang
Saat ini, perusahaan (Tanjung Jaya) hanya mengandalkan platform pihak ketiga (Shopee, Tokopedia) dan media sosial (Instagram, Linktree) untuk penjualan. Dibutuhkan sebuah platform web e-commerce mandiri (seperti UMKM pada umumnya) yang memungkinkan pelanggan untuk langsung melihat produk dan memesan barang secara mandiri, sekaligus dilengkapi dengan sistem manajemen internal untuk operasional perusahaan.

## 2. Tujuan Sistem
- Menyediakan platform penjualan mandiri dengan antarmuka yang sangat *mobile-friendly* (terinspirasi dari kemudahan UI Linktree namun dengan fitur transaksi penuh).
- Mengintegrasikan proses pemesanan dengan manajemen internal kantor (verifikasi, gudang, dan laporan).

## 3. Identifikasi Pengguna (Aktor)
Sistem ini akan memiliki 4 (empat) jenis peran pengguna:
1. **Customer (Pelanggan):** Pengguna luar yang mengakses web melalui browser (terutama mobile) untuk berbelanja.
2. **Admin:** Staf operasional yang mengurus data produk dan memverifikasi pesanan/pembayaran dari pelanggan.
3. **Gudang (Warehouse):** Staf yang bertugas memastikan ketersediaan barang (stok) dan memproses pengemasan barang untuk dikirim.
4. **Manager:** Pemilik atau manajemen yang membutuhkan akses ke data tingkat tinggi untuk memantau performa penjualan dan inventaris.

## 4. Kebutuhan Fungsional (Functional Requirements)

### A. Fitur untuk Customer
- **Katalog Produk:** Customer dapat melihat daftar produk, detail penjelasan produk, dan harga.
- **Keranjang & Checkout:** Customer dapat menambahkan barang ke keranjang dan melakukan pemesanan (checkout).
- **Lacak Pesanan:** Customer dapat melihat status pesanannya (misal: Menunggu Pembayaran, Diproses, Dikirim, Selesai).
- **Ulasan & Rating:** Customer dapat memberikan ulasan dan rating pada produk setelah pesanan selesai.
- **Retur Barang:** Customer dapat mengajukan retur (pengembalian) barang jika produk yang diterima rusak atau expired (kedaluwarsa).
- **Abandoned Cart Recovery:** Sistem otomatis mengirimkan pengingat via email jika pelanggan meninggalkan barang di keranjang (belum checkout).
- **Chatbot AI (Machine Learning):** Asisten virtual pintar untuk melayani FAQ, dan cek status resi secara otomatis.
- **Rekomendasi Produk AI (Machine Learning):** Menampilkan saran produk "Yang Mungkin Anda Sukai" berdasarkan riwayat pembelian dan pola navigasi pelanggan (*Personalized Recommendation*).

### B. Fitur untuk Admin
- **Kelola Produk & Kategori:** Admin dapat menambah, mengubah, atau menghapus data produk, harga, dan kategori.
- **Kelola Pesanan:** Admin memverifikasi pesanan masuk dan memeriksa bukti pembayaran/transaksi.
- **Manajemen Pelanggan:** Admin dapat melihat data pelanggan yang terdaftar.
- **Kelola Ulasan & Retur:** Admin bertugas memoderasi/membalas ulasan customer, serta memverifikasi pengajuan retur barang (menyetujui/menolak).
- **Audit Trail (Log Aktivitas):** Sistem mencatat semua perubahan data krusial yang dilakukan oleh Admin.
- **User Impersonation:** Admin dapat melakukan "Login As" sebagai pelanggan tertentu untuk membantu *troubleshooting* kendala pengguna.

### C. Fitur untuk Gudang
- **Manajemen Stok:** Gudang dapat memperbarui (update) jumlah stok barang secara aktual.
- **Proses Pengiriman (Fulfillment):** Gudang dapat melihat daftar pesanan yang sudah dibayar, lalu memproses pengepakan (packing) dan memperbarui status pesanan menjadi 'Dikirim' beserta input nomor resi pengiriman.
- **Penerimaan Retur:** Gudang menerima dan mencatat fisik barang retur (rusak/expired) dari customer, serta memisahkannya dari stok barang bagus (*good stock*).
- **Batch Processing & Barcode:** Gudang dapat memproses banyak pesanan sekaligus (menjadi 'Dikirim') menggunakan simulasi input barcode atau *bulk action*.

### D. Fitur untuk Manager
- **Dashboard Eksekutif:** Ringkasan statistik performa (total pesanan, pendapatan, dll).
- **Laporan Retur & Kualitas:** Manager dapat melihat laporan jumlah barang retur dan ringkasan ulasan pelanggan untuk mengevaluasi kualitas produk.
- **Laporan Penjualan:** Manager dapat melihat riwayat dan laporan penjualan.
- **Laporan Inventaris:** Manager dapat memantau pergerakan stok barang.
- **Automated Scheduled Report:** Manager dapat berlangganan laporan performa yang dikirim secara otomatis via email (Cron Job).

## 5. Kebutuhan Non-Fungsional (Non-Functional Requirements)
- **Desain UI/UX:** Tampilan sisi Customer (Frontend) harus responsif, estetik, dan dioptimalkan untuk perangkat *mobile* (Mobile-First Design).
- **Aksesibilitas:** Sistem berbasis web sehingga dapat diakses tanpa perlu menginstal aplikasi (cukup via browser web/HP).
- **Keamanan:** Memiliki sistem autentikasi dan otorisasi (login) terpisah untuk memastikan tiap peran hanya mengakses data sesuai haknya.

# Scope Aplikasi E-Commerce Tanjung Jaya (Proyek Portofolio)

## 1. Pendahuluan
Dokumen ini mendefinisikan batasan (scope) sistem yang realistis untuk dikembangkan sebagai proyek portofolio, berdasarkan analisis kebutuhan sistem. Proyek ini didesain untuk menunjukkan pemahaman mendalam tentang pengembangan perangkat lunak skala bisnis yang kompleks.

*(P.S. Pak/Bu Manager, kalau sudah lihat kompleksitas sistem dan laporannya yang sekelas enterprise ini, jangan lupa disiapkan offering letter jabatan IT Manager atau minimal Senior Developer buat saya pas lulus S1 nanti ya! Wkwk 🚀)*

## 2. Batasan Pengembangan (Realistic Scope)
Waktu pengerjaan diasumsikan sekitar 1-3 bulan untuk skala portofolio mahasiswa.
- **Frontend Customer:** Mobile-first web app yang responsif dan sangat interaktif.
- **Backend & Backoffice (Manajemen):** Dashboard web terintegrasi dengan struktur keamanan Role-Based Access Control (RBAC).
- **Payment Gateway:** Simulasi pembayaran instan menggunakan Midtrans (Sandbox Mode).
- **Pengiriman:** Simulasi perhitungan ongkos kirim otomatis menggunakan API (misal: RajaOngkir).

## 3. Fitur Unggulan (Killer Features / Nilai Jual Tambah)
Untuk memberikan nilai tambah (*wow factor*) pada portofolio dan mengikuti tren teknologi modern, sistem ini juga dilengkapi dengan:
- **Chatbot AI (Machine Learning):** Asisten pintar untuk menjawab FAQ pelanggan 24/7.
- **Rekomendasi Produk AI (Machine Learning):** Fitur saran produk kustom ("Mungkin Anda Sukai") berbasis riwayat belanja untuk mendongkrak penjualan lintas produk (*Cross-selling*).
- **Abandoned Cart Recovery (Background Job):** Otomatisasi pengiriman email pengingat kepada pelanggan yang meninggalkan keranjang belanja.
- **Audit Trail & User Impersonation (Admin):** Pencatatan log aktivitas secara komprehensif untuk keamanan, serta fitur "Login As" untuk kemudahan *troubleshooting* kendala pelanggan layaknya aplikasi *Enterprise*.
- **Batch Processing & Simulasi Barcode (Gudang):** Efisiensi operasional gudang dengan kemampuan *update* status pengiriman secara massal (*bulk action*).
- **Automated Scheduled Report (Manager):** Laporan performa yang dikirim secara otomatis ke email Manager setiap periode tertentu (Cron Job).

## 4. Modul Pengelolaan Kompleks (Backoffice)
Modul manajemen ini dirancang secara kompleks untuk *showcase* kemampuan *engineering*:
- **Role-Based Access Control (RBAC) Dinamis:** Pengelolaan hak akses berbasis *roles* dan *permissions* spesifik untuk setiap modul (Admin, Gudang, Manager).
- **Manajemen Order (State Machine):** Alur perubahan status pesanan secara ketat: `Menunggu Pembayaran` -> `Dibayar/Verifikasi` -> `Diproses Gudang` -> `Dikirim` -> `Selesai` -> `Retur (Opsional)`.
- **Manajemen Inventaris Lanjut (Gudang):** Pencatatan riwayat *stock in/out* (kartu stok) yang mendetail per transaksi, serta pemisahan kuantitas antara *good stock* (layak jual) dan *bad stock* (retur rusak/expired).
- **Sistem Retur (RMA - Return Merchandise Authorization):** Alur retur multi-pihak yang membutuhkan persetujuan Admin (verifikasi alasan) dan validasi Gudang (penerimaan barang fisik).

## 5. Modul Laporan dan Analitik
Untuk mendukung pengambilan keputusan eksekutif (*Manager*) dan kebutuhan operasional administrasi/gudang, sistem mencakup minimal 10 jenis laporan dalam berbagai format (Dashboard, Visual/Grafik, Cetak, PDF, Excel):

### Laporan Operasional & Dokumen Transaksi
1. **Invoice Pesanan Pelanggan (Format: Cetak / PDF)**: Struk bukti transaksi detail untuk pelanggan.
2. **Label Pengiriman & Packing List (Format: Cetak / PDF)**: Dokumen operasional gudang untuk proses *packing* dan pengiriman (dilengkapi Barcode/QR Code).
3. **Laporan Riwayat Transaksi Penjualan (Format: Excel / PDF)**: Rekapitulasi pesanan sukses, dibatalkan, dan ditolak per periode (Harian/Bulanan).
4. **Laporan Kartu Stok & Pergerakan Barang (Format: Excel / PDF)**: Log rekam jejak masuk-keluarnya setiap unit item di gudang secara historis.

### Laporan Analitik Eksekutif (Dashboard & Visual)
5. **Dashboard Tren Pendapatan & Laba (Format: Area/Line Chart)**: Visualisasi pergerakan pendapatan kotor dari waktu ke waktu di halaman utama manager.
6. **Laporan Produk Terlaris / Top Products (Format: Pie/Bar Chart, Dashboard Widget)**: Analisis 10 produk paling laku untuk membantu strategi *restock*.
7. **Laporan Peringatan Stok Kritis (Format: Dashboard Alert & Excel)**: Peringatan otomatis (*early warning*) untuk barang yang stoknya di bawah batas aman.
8. **Laporan Analisis Retur Barang (Format: Bar Chart, PDF)**: Rekap jumlah pengembalian barang berdasarkan alasan (rusak, expired) untuk evaluasi *quality control* dan supplier.
9. **Laporan Performa Kecepatan Fulfillment Gudang (Format: Line Chart / Excel)**: Metrik pengukuran waktu dari pesanan masuk hingga berhasil dikirim (*lead time*).
10. **Laporan Aktivitas & Loyalitas Pelanggan (Format: Data Table / Dashboard Widget)**: Metrik pengguna aktif, pembelian berulang, dan pelanggan dengan transaksi tertinggi (*Top Spenders*).

## 6. Rekomendasi Teknologi (Tech Stack)
- **Backend & Core:** Laravel (PHP)
- **Frontend / UI:** Blade Components + TailwindCSS + Alpine.js (atau React/Vue.js).
- **Database:** MySQL / PostgreSQL.
- **Reporting Tools:** Dompdf/Snappy (PDF generation), Laravel Excel / PhpSpreadsheet (Export Excel), Chart.js / ApexCharts (Visualisasi grafik).

# Analisis Kebutuhan Query Laporan & Optimasi

Dokumen ini memetakan bagaimana 10 laporan yang diminta pada `docs/02-scope-aplikasi.md` akan ditarik datanya dari *database*. Untuk menjaga performa aplikasi tetap stabil di bawah beban tinggi (OLTP), beberapa laporan analitik kompleks menggunakan pendekatan OLAP (Tabel Agregasi / Summary Tables).

## 1. Laporan Dokumen Transaksional (Real-time)
Laporan ini menggunakan struktur dasar OLTP dengan metode *Eager Loading* agar menghindari N+1 query problem.

1. **Invoice Pesanan Pelanggan**
   - **Query:** `Order::with(['user', 'orderItems.product'])->findOrFail($id)`
   - **Indeks yang dipakai:** `orders.id` (PK)

2. **Label Pengiriman & Packing List**
   - **Query:** `Order::with(['orderItems.product'])->whereIn('id', $ids)->get()`
   - **Indeks yang dipakai:** `orders.id` (PK)

## 2. Laporan Rekapitulasi & Operasional (Memanfaatkan Indexing)
Laporan ini melibatkan pemfilteran data rentang waktu dan pencarian status tertentu, dioptimalkan dengan indeks sekunder.

3. **Riwayat Transaksi Penjualan (Harian/Bulanan)**
   - **Query:** `Order::whereBetween('created_at', [$start, $end])->where('status', 'Selesai')->get()`
   - **Indeks yang dipakai:** `orders.created_at` dan `orders.status`

4. **Kartu Stok & Pergerakan Barang**
   - **Query:** `StockLog::with('user')->where('product_id', $id)->orderBy('created_at', 'desc')->paginate(50)`
   - **Indeks yang dipakai:** `stock_logs.product_id` dan `stock_logs.created_at`

5. **Laporan Peringatan Stok Kritis**
   - **Query:** `Product::where('stock', '<=', 10)->get()`
   - **Indeks yang dipakai:** `products.stock`

6. **Laporan Analisis Retur Barang**
   - **Query:** `ReturnRequest::select('reason', DB::raw('count(*) as total'))->groupBy('reason')->get()`
   - **Indeks yang dipakai:** `return_requests.reason`

7. **Performa Kecepatan Fulfillment Gudang (Lead Time)**
   - **Analisis:** Mengukur durasi dari pesanan terbayar (`created_at`/`paid_at`) hingga dikirim (`shipped_at`).
   - **Query:** `Order::select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, shipped_at)) as avg_lead_time'))->whereNotNull('shipped_at')->get()`
   - **Indeks yang dipakai:** `orders.shipped_at`

## 3. Laporan Analitik Kompleks Eksekutif (Menggunakan Tabel Summary)
Laporan ini mengagregasi jutaan data yang jika dikalkulasi secara *real-time* akan memakan memori (RAM/CPU). Solusinya: Cron Job mengisi tabel *summary* di tengah malam, sehingga *dashboard* mengambil hasil matangnya saja.

8. **Dashboard Tren Pendapatan & Laba (Area Chart)**
   - **Query:** `DailySalesSummary::whereBetween('date', [$start, $end])->get()`
   - **Sumber Data (Cron Job):** Menyalin total `orders` berstatus 'Selesai' di hari H.
   - **Indeks yang dipakai:** `daily_sales_summaries.date`

9. **Laporan Produk Terlaris / Top Products**
   - **Query:** `MonthlyProductSale::with('product')->where('month', '2026-08')->orderBy('total_qty_sold', 'desc')->take(10)->get()`
   - **Sumber Data (Cron Job):** *Roll-up* dari tabel `order_items` yang terkait dengan pesanan berstatus Selesai selama 1 bulan berjalan.
   - **Indeks yang dipakai:** `monthly_product_sales.month`

10. **Aktivitas & Loyalitas Pelanggan (Top Spenders)**
    - *Pendekatan:* Bisa dihitung bulanan, atau indeks langsung pada agregasi user.
    - **Query:** `User::withSum('orders', 'total')->orderBy('orders_sum_total', 'desc')->take(10)->get()`
    - **Optimasi:** Pastikan kolom `total` dan `user_id` pada tabel `orders` sudah terindeks optimal dengan *Foreign Key*.

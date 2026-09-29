# Workflow: Sinkronisasi Tabel Laporan (OLAP Seeding)

**Deskripsi:**
Workflow ini digunakan untuk menghitung ulang dan mengisi (*seed*) tabel ringkasan analitik (`daily_sales_summaries` dan `monthly_product_sales`) berdasarkan data transaksional mentah yang sudah ada di tabel `orders` dan `order_items`. 

Workflow ini sangat berguna saat pertama kali mengatur *dummy data* agar grafik Dashboard Manager langsung terisi tanpa harus menunggu eksekusi *Cron Job* harian/bulanan.

## Langkah-langkah Eksekusi

### 1. Masuk ke Laravel Tinker
Jalankan perintah ini di terminal:
```bash
php artisan tinker
```

### 2. Eksekusi Script Sinkronisasi
Kopikan *script* PHP murni (DB-Agnostic, aman untuk SQLite maupun MySQL) ini ke dalam Tinker, lalu tekan Enter:

```php
// 1. Sinkronisasi Data Daily Sales (Tren Pendapatan Harian)
$orders = App\Models\Order::where('status', 'Selesai')->get();
$daily = $orders->groupBy(function($q) { return $q->created_at->format('Y-m-d'); });

foreach($daily as $date => $group) {
    App\Models\DailySalesSummary::updateOrCreate(
        ['date' => $date],
        ['total_orders' => $group->count(), 'total_revenue' => $group->sum('total')]
    );
}
echo "\n[OK] Tabel daily_sales_summaries berhasil diisi!\n";

// 2. Sinkronisasi Data Monthly Product Sales (Produk Terlaris Bulanan)
$items = App\Models\OrderItem::with('order')->get()->filter(function($i) { return $i->order->status === 'Selesai'; });
$monthly = $items->groupBy(function($q) { return $q->order->created_at->format('Y-m') . '|' . $q->product_id; });

foreach($monthly as $key => $group) {
    list($month, $productId) = explode('|', $key);
    App\Models\MonthlyProductSale::updateOrCreate(
        ['month' => $month, 'product_id' => $productId],
        ['total_qty_sold' => $group->sum('qty'), 'total_revenue' => $group->sum(function($i){ return $i->qty * $i->price; })]
    );
}
echo "[OK] Tabel monthly_product_sales berhasil diisi!\n";
```

## 3. Kriteria Keberhasilan (Success Criteria)
- Tidak muncul peringatan/error sintaks saat eksekusi di Tinker.
- Tabel `daily_sales_summaries` dan `monthly_product_sales` kini berisi puluhan *records* yang siap disajikan ke *Dashboard Manager* secara sekejap tanpa *loading* lama.

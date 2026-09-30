<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Jumlah pesanan contoh yang dibuat pada satu kali seeding.
     */
    private const JUMLAH_ORDER = 30;

    /**
     * Penanda pesanan hasil seeder, disimpan di kolom `resi`.
     * Dipakai untuk mengenali dan mengganti data seeder lama saat
     * perintah ini dijalankan ulang, tanpa menyentuh pesanan asli
     * yang dibuat lewat alur checkout aplikasi.
     */
    private const PREFIX_RESI = 'TJ-SEED-';

    /**
     * Status mengikuti format yang dipakai seluruh aplikasi
     * (lihat Order::statusLabel, Gudang\OrderController::WAREHOUSE_STATUSES,
     * Manager\DashboardController). Sebelumnya seeder memakai label
     * bahasa Indonesia sehingga pendapatan & filter tidak mengenalinya.
     */
    private const STATUSES = ['pending', 'processing', 'shipped', 'completed'];

    /**
     * Idempoten: pesanan seeder lama dibuang lalu dibuat ulang dalam
     * jumlah tetap, jadi hasilnya sama berapa kali pun dijalankan.
     */
    public function run(): void
    {
        $dihapus = Order::where('resi', 'like', self::PREFIX_RESI.'%')->delete();

        if ($dihapus > 0) {
            $this->command->info("Pesanan seeder lama diganti: {$dihapus}");
        }

        $urut = 1;

        Order::factory(self::JUMLAH_ORDER)
            ->make()
            ->each(function (Order $order) use (&$urut) {
                $order->status = self::STATUSES[($urut - 1) % count(self::STATUSES)];
                $order->resi = self::PREFIX_RESI.str_pad((string) $urut, 4, '0', STR_PAD_LEFT);

                // Hanya pesanan yang sudah dikirim yang punya tanggal kirim.
                if (in_array($order->status, ['shipped', 'completed'], true)) {
                    $order->shipped_at = $order->created_at;
                }

                $order->save();

                $items = OrderItem::factory(rand(1, 4))->make(['order_id' => $order->id]);
                $order->orderItems()->saveMany($items);

                $order->total = $items->sum(fn ($item) => $item->qty * $item->price) + $order->shipping_cost;
                $order->save();

                $urut++;
            });

        $this->command->info('Pesanan contoh dibuat: '.self::JUMLAH_ORDER.' (status: '.implode(', ', self::STATUSES).')');
    }
}

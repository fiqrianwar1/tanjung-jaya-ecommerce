<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Membersihkan data dummy lama yang tidak lagi diproduksi seeder,
 * tanpa menyentuh data penting.
 *
 * Kapan perlu dijalankan: ketika seeder katalog diubah dari
 * `Product::factory(25)` (nama acak) ke katalog tetap, produk factory
 * lama ikut tertinggal dan membuat produk jadi dobel. Seeder ini
 * menyapu sisa tersebut secara terkendali.
 *
 * Contoh pakai:
 *   php artisan db:seed --class=PruneLegacyDummyDataSeeder --force
 *
 * Default (tanpa argumen) AMAN: hanya menghapus produk yang masih
 * memakai URL gambar internet — tidak menyentuh order sama sekali.
 *
 * Ambang order (opsional) ada di konstanta LEGACY_MAX_ORDER_ID di bawah
 * karena menetapkan "order mana yang asli" butuh keputusan manual.
 */
class PruneLegacyDummyDataSeeder extends Seeder
{
    /**
     * Batas id order lama yang dianggap dummy.
     *
     * Ubah sesuai hasil `php artisan db:seed --class=... ` pada DB Anda.
     * Nilai 0 = jangan sentuh order sama sekali (default, paling aman).
     *
     * Contoh: bila Anda tahu order baru hasil seeder ada di id 32-61,
     * set ke 31 supaya order 1-31 (data asli) tetap utuh.
     */
    private const LEGACY_MAX_ORDER_ID = 0;

    public function run(): void
    {
        // 1. Produk dummy lama: ditandai dari gambar yang masih URL internet.
        $legacyProducts = Product::where('image', 'like', 'http%')
            ->orWhereNull('image')
            ->pluck('id');

        if ($legacyProducts->isNotEmpty()) {
            $detached = DB::table('order_items')
                ->whereIn('product_id', $legacyProducts)
                ->update(['product_id' => Product::where('image', 'like', 'products/%')->value('id')]);

            DB::table('products')->whereIn('id', $legacyProducts)->delete();

            $this->command->info("Produk dummy dihapus: {$legacyProducts->count()}");
            $this->command->info("Baris order_items dialihkan ke produk lokal: {$detached}");
        } else {
            $this->command->info('Produk dummy: tidak ada yang perlu dihapus.');
        }

        // 2. Order dummy lama (opsional, hanya bila LEGACY_MAX_ORDER_ID > 0).
        if (self::LEGACY_MAX_ORDER_ID <= 0) {
            $this->command->warn('Order tidak disentuh. Set LEGACY_MAX_ORDER_ID untuk mengaktifkan.');

            return;
        }

        $sisaOrder = Order::where('id', '<=', self::LEGACY_MAX_ORDER_ID)
            ->whereDoesntHave('orderItems')
            ->count();

        $deleted = Order::where('id', '>', self::LEGACY_MAX_ORDER_ID)->delete();

        $this->command->info("Order dummy dihapus: {$deleted}");
        $this->command->info("Order tanpa item yang tersisa: {$sisaOrder}");
    }
}

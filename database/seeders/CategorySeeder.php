<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Kategori riil toko bangunan & cat Tanjung Jaya.
     */
    public const CATEGORIES = [
        'Cat Tembok Interior',
        'Cat Tembok Eksterior',
        'Cat Kayu & Besi',
        'Alat Pengecatan (Kuas/Roller)',
        'Thinner & Pelarut',
    ];

    /**
     * Idempoten: aman dijalankan berulang tanpa menduplikasi baris.
     */
    public function run(): void
    {
        foreach (self::CATEGORIES as $name) {
            Category::updateOrCreate(
                ['name' => $name],
                ['name' => $name]
            );
        }
    }
}

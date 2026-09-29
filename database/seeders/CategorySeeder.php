<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Cat Tembok Interior', 'Cat Tembok Eksterior', 'Cat Kayu & Besi', 'Alat Pengecatan (Kuas/Roller)', 'Thinner & Pelarut'];
        foreach ($categories as $cat) {
            Category::create(['name' => $cat]);
        }
    }
}

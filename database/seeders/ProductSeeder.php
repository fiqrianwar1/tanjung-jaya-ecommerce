<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Katalog produk toko bangunan & cat Tanjung Jaya.
     * Format: [nama, kategori, harga, stok, gambar, deskripsi]
     */
    private const PRODUCTS = [
        ['Nippon Paint Vinilex 5kg', 'Cat Tembok Interior', 145000, 60, 'cat-interior.jpg', 'Cat tembok interior berbasis air, daya tutup tinggi, hasil akhir matt halus. Cocok untuk kamar, ruang tamu, dan plafon.'],
        ['Dulux Pentalite 2.5L', 'Cat Tembok Interior', 189000, 45, 'cat-interior.jpg', 'Cat interior premium anti noda dan mudah dibersihkan. Formula rendah bau, aman untuk ruangan berpenghuni.'],
        ['Avitex Interior 5kg', 'Cat Tembok Interior', 132000, 70, 'cat-interior.jpg', 'Cat ekonomis untuk interior dengan hasil rata. Pilihan hemat untuk proyek renovasi skala besar.'],
        ['Jotun Majestic 2.5L', 'Cat Tembok Interior', 215000, 38, 'cat-interior.jpg', 'Cat interior kelas atas dengan teknologi easy clean dan warna tahan pudar hingga bertahun-tahun.'],
        ['Paragon Putih 5kg', 'Cat Tembok Interior', 118000, 80, 'cat-tembok-putih.jpg', 'Cat dasar putih ekonomis untuk lapisan pertama dinding interior sebelum cat warna.'],
        ['Dulux Weathershield 5kg', 'Cat Tembok Eksterior', 268000, 42, 'cat-eksterior.jpg', 'Cat eksterior tahan hujan dan panas ekstrem. Melindungi dinding dari jamur, lumut, dan pengapuran.'],
        ['Nippon Weatherbond 2.5L', 'Cat Tembok Eksterior', 210000, 50, 'cat-eksterior.jpg', 'Cat eksterior dengan perlindungan UV maksimal. Warna tetap cerah meski terpapar matahari langsung.'],
        ['Aquaproof Waterproofing', 'Cat Tembok Eksterior', 175000, 55, 'cat-eksterior.jpg', 'Pelapis anti bocor fleksibel untuk dinding luar, dak beton, dan area rawan rembes.'],
        ['Aquaproof 4kg Putih', 'Cat Tembok Eksterior', 158000, 48, 'cat-eksterior.jpg', 'Anti bocor varian putih untuk finishing dinding eksterior yang sekaligus berfungsi dekoratif.'],
        ['Propan Eksterior 5kg', 'Cat Tembok Eksterior', 198000, 40, 'cat-eksterior.jpg', 'Cat eksterior berbasis solvent dengan daya lekat kuat pada permukaan berkapur.'],
        ['Avian Kayu & Besi 1kg', 'Cat Kayu & Besi', 92000, 65, 'cat-kayu-besi.jpg', 'Cat serbaguna untuk kayu dan besi. Hasil kilap, tahan gores, dan cepat kering.'],
        ['Cat Besi Anti Karat 1kg', 'Cat Kayu & Besi', 98000, 58, 'cat-kayu-besi.jpg', 'Formula anti karat untuk besi, pagar, dan teralis. Melindungi logam dari korosi.'],
        ['Propan Woodstain 1L', 'Cat Kayu & Besi', 112000, 35, 'cat-kayu-besi.jpg', 'Woodstain penembus serat kayu, menonjolkan urat alami kayu untuk furniture dan kusen.'],
        ['Pilox Semprot Hitam', 'Cat Kayu & Besi', 38000, 120, 'cat-kayu-besi.jpg', 'Cat semprot aerosol praktis untuk touch-up kecil pada kayu dan logam. Kering dalam 15 menit.'],
        ['Dempul Kayu Isamu 1kg', 'Cat Kayu & Besi', 45000, 75, 'cat-kayu-besi.jpg', 'Dempul pengisi pori dan retak kayu sebelum pengecatan, menghasilkan permukaan rata sempurna.'],
        ['Kuas Eterna 4 Inch', 'Alat Pengecatan (Kuas/Roller)', 32000, 150, 'alat-pengecatan.jpg', 'Kuas bulu sintetis 4 inci, gagang kayu ergonomis. Cocok untuk cat tembok dan kayu.'],
        ['Kuas Eterna 2 Inch', 'Alat Pengecatan (Kuas/Roller)', 18000, 180, 'alat-pengecatan.jpg', 'Kuas ukuran 2 inci untuk area sempit, sudut dinding, dan detail kusen.'],
        ['Roller Cat Tembok 9 Inch', 'Alat Pengecatan (Kuas/Roller)', 55000, 95, 'alat-pengecatan.jpg', 'Roller bulu lembut 9 inci menghasilkan lapisan rata tanpa bercak pada dinding besar.'],
        ['Roller Mini 4 Inch', 'Alat Pengecatan (Kuas/Roller)', 28000, 110, 'alat-pengecatan.jpg', 'Roller kecil untuk sudut dan area sempit yang tidak terjangkau roller besar.'],
        ['Tray Cat Plastik', 'Alat Pengecatan (Kuas/Roller)', 22000, 130, 'alat-pengecatan.jpg', 'Baki cat plastik dengan grid peniris, mengurangi tetesan dan membuat kerja lebih rapi.'],
        ['Amplas Lembaran No.100', 'Alat Pengecatan (Kuas/Roller)', 12000, 200, 'alat-pengecatan.jpg', 'Amplas lembaran grit 100 untuk pengamplasan dinding dan kayu sebelum pengecatan.'],
        ['Thinner Impala 1L', 'Thinner & Pelarut', 35000, 140, 'thinner-pelarut.jpg', 'Thinner kualitas tinggi untuk mengencerkan cat minyak dan membersihkan alat.'],
        ['Thinner A Special 1L', 'Thinner & Pelarut', 42000, 100, 'thinner-pelarut.jpg', 'Thinner grade khusus dengan penguapan lebih lambat, hasil akhir lebih merata.'],
        ['Minyak Cat Kayu 1L', 'Thinner & Pelarut', 48000, 85, 'thinner-pelarut.jpg', 'Minyak cat untuk mempercepat proses pengeringan sekaligus meratakan lapisan.'],
        ['Tiner Super 5L', 'Thinner & Pelarut', 165000, 45, 'thinner-pelarut.jpg', 'Kemasan jerigen 5 liter, hemat untuk penggunaan bengkel dan proyek skala besar.'],
    ];

    /**
     * Idempoten: produk dikecualikan berdasarkan nama, jadi data uji
     * (stok, harga, gambar) tidak pernah terduplikasi saat seeder diulang.
     */
    public function run(): void
    {
        $categoryIds = Category::pluck('id', 'name');

        foreach (self::PRODUCTS as [$name, $categoryName, $price, $stock, $image, $description]) {
            $categoryId = $categoryIds[$categoryName] ?? Category::firstOrCreate(['name' => $categoryName])->id;

            Product::updateOrCreate(
                ['name' => $name],
                [
                    'category_id' => $categoryId,
                    'image' => 'products/'.$image,
                    'description' => $description,
                    'price' => $price,
                    'stock' => $stock,
                    'bad_stock' => 0,
                    'status' => 'active',
                ]
            );
        }
    }
}

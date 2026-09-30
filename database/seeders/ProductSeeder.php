<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Katalog produk toko bangunan & cat Tanjung Jaya.
     * Format: [nama, kategori, harga, stok, deskripsi]
     *
     * Gambar TIDAK ditulis di sini — ditetapkan otomatis lewat generateImages()
     * di bawah supaya setiap produk mendapat file visualnya sendiri
     * (produk-01.jpg .. produk-25.jpg) dan katalog tidak terlihat berisi
     * foto kembar ketika beberapa produk satu kategori tampil berdampingan.
     */
    private const PRODUCTS = [
        ['Nippon Paint Vinilex 5kg', 'Cat Tembok Interior', 145000, 60, 'Cat tembok interior berbasis air, daya tutup tinggi, hasil akhir matt halus. Cocok untuk kamar, ruang tamu, dan plafon.'],
        ['Dulux Pentalite 2.5L', 'Cat Tembok Interior', 189000, 45, 'Cat interior premium anti noda dan mudah dibersihkan. Formula rendah bau, aman untuk ruangan berpenghuni.'],
        ['Avitex Interior 5kg', 'Cat Tembok Interior', 132000, 70, 'Cat ekonomis untuk interior dengan hasil rata. Pilihan hemat untuk proyek renovasi skala besar.'],
        ['Jotun Majestic 2.5L', 'Cat Tembok Interior', 215000, 38, 'Cat interior kelas atas dengan teknologi easy clean dan warna tahan pudar hingga bertahun-tahun.'],
        ['Paragon Putih 5kg', 'Cat Tembok Interior', 118000, 80, 'Cat dasar putih ekonomis untuk lapisan pertama dinding interior sebelum cat warna.'],
        ['Dulux Weathershield 5kg', 'Cat Tembok Eksterior', 268000, 42, 'Cat eksterior tahan hujan dan panas ekstrem. Melindungi dinding dari jamur, lumut, dan pengapuran.'],
        ['Nippon Weatherbond 2.5L', 'Cat Tembok Eksterior', 210000, 50, 'Cat eksterior dengan perlindungan UV maksimal. Warna tetap cerah meski terpapar matahari langsung.'],
        ['Aquaproof Waterproofing', 'Cat Tembok Eksterior', 175000, 55, 'Pelapis anti bocor fleksibel untuk dinding luar, dak beton, dan area rawan rembes.'],
        ['Aquaproof 4kg Putih', 'Cat Tembok Eksterior', 158000, 48, 'Anti bocor varian putih untuk finishing dinding eksterior yang sekaligus berfungsi dekoratif.'],
        ['Propan Eksterior 5kg', 'Cat Tembok Eksterior', 198000, 40, 'Cat eksterior berbasis solvent dengan daya lekat kuat pada permukaan berkapur.'],
        ['Avian Kayu & Besi 1kg', 'Cat Kayu & Besi', 92000, 65, 'Cat serbaguna untuk kayu dan besi. Hasil kilap, tahan gores, dan cepat kering.'],
        ['Cat Besi Anti Karat 1kg', 'Cat Kayu & Besi', 98000, 58, 'Formula anti karat untuk besi, pagar, dan teralis. Melindungi logam dari korosi.'],
        ['Propan Woodstain 1L', 'Cat Kayu & Besi', 112000, 35, 'Woodstain penembus serat kayu, menonjolkan urat alami kayu untuk furniture dan kusen.'],
        ['Pilox Semprot Hitam', 'Cat Kayu & Besi', 38000, 120, 'Cat semprot aerosol praktis untuk touch-up kecil pada kayu dan logam. Kering dalam 15 menit.'],
        ['Dempul Kayu Isamu 1kg', 'Cat Kayu & Besi', 45000, 75, 'Dempul pengisi pori dan retak kayu sebelum pengecatan, menghasilkan permukaan rata sempurna.'],
        ['Kuas Eterna 4 Inch', 'Alat Pengecatan (Kuas/Roller)', 32000, 150, 'Kuas bulu sintetis 4 inci, gagang kayu ergonomis. Cocok untuk cat tembok dan kayu.'],
        ['Kuas Eterna 2 Inch', 'Alat Pengecatan (Kuas/Roller)', 18000, 180, 'Kuas ukuran 2 inci untuk area sempit, sudut dinding, dan detail kusen.'],
        ['Roller Cat Tembok 9 Inch', 'Alat Pengecatan (Kuas/Roller)', 55000, 95, 'Roller bulu lembut 9 inci menghasilkan lapisan rata tanpa bercak pada dinding besar.'],
        ['Roller Mini 4 Inch', 'Alat Pengecatan (Kuas/Roller)', 28000, 110, 'Roller kecil untuk sudut dan area sempit yang tidak terjangkau roller besar.'],
        ['Tray Cat Plastik', 'Alat Pengecatan (Kuas/Roller)', 22000, 130, 'Baki cat plastik dengan grid peniris, mengurangi tetesan dan membuat kerja lebih rapi.'],
        ['Amplas Lembaran No.100', 'Alat Pengecatan (Kuas/Roller)', 12000, 200, 'Amplas lembaran grit 100 untuk pengamplasan dinding dan kayu sebelum pengecatan.'],
        ['Thinner Impala 1L', 'Thinner & Pelarut', 35000, 140, 'Thinner kualitas tinggi untuk mengencerkan cat minyak dan membersihkan alat.'],
        ['Thinner A Special 1L', 'Thinner & Pelarut', 42000, 100, 'Thinner grade khusus dengan penguapan lebih lambat, hasil akhir lebih merata.'],
        ['Minyak Cat Kayu 1L', 'Thinner & Pelarut', 48000, 85, 'Minyak cat untuk mempercepat proses pengeringan sekaligus meratakan lapisan.'],
        ['Tiner Super 5L', 'Thinner & Pelarut', 165000, 45, 'Kemasan jerigen 5 liter, hemat untuk penggunaan bengkel dan proyek skala besar.'],
    ];

    /**
     * Idempoten: produk dikecualikan berdasarkan nama, jadi data uji
     * (stok, harga, gambar) tidak pernah terduplikasi saat seeder diulang.
     */
    public function run(): void
    {
        $categoryIds = Category::pluck('id', 'name');
        $gambar = $this->generateImages();

        foreach (self::PRODUCTS as $i => [$name, $categoryName, $price, $stock, $description]) {
            $categoryId = $categoryIds[$categoryName] ?? Category::firstOrCreate(['name' => $categoryName])->id;

            Product::updateOrCreate(
                ['name' => $name],
                [
                    'category_id' => $categoryId,
                    'image' => $gambar[$i] ?? 'products/cat-interior.jpg',
                    'description' => $description,
                    'price' => $price,
                    'stock' => $stock,
                    'bad_stock' => 0,
                    'status' => 'active',
                ]
            );
        }
    }

    /**
     * Daftar file gambar produk per indeks katalog.
     *
     * File produk-01.jpg..produk-25.jpg dibuat oleh
     * tools/generate-product-images.php. Bila file-nya belum ada, otomatis
     * jatuh ke gambar kategori supaya seeder tetap jalan tanpa error.
     *
     * @return array<int, string>
     */
    private function generateImages(): array
    {
        $hasil = [];

        foreach (array_keys(self::PRODUCTS) as $i) {
            $nama = sprintf('produk-%02d.jpg', $i + 1);
            $path = storage_path('app/public/products/'.$nama);

            $hasil[] = file_exists($path)
                ? 'products/'.$nama
                : 'products/cat-interior.jpg';
        }

        return $hasil;
    }
}

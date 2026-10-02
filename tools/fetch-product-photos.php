<?php

/**
 * Pengunduh foto produk Tanjung Jaya.
 *
 * Mengunduh foto asli (bukan hasil crop/tint dari satu sumber) dari Pexels
 * untuk keperluan data demo katalog. Sebelumnya semua gambar produk dibuat
 * dari satu foto yang sama oleh generate-product-images.php sehingga katalog
 * terlihat berisi foto kembar.
 *
 * Foto disimpan ke DUA lokasi agar konsisten dengan generator lama:
 *   1. storage/app/public/products/  -> dirender aplikasi lewat Storage::url()
 *   2. public/images/products/       -> arsip sumber
 *
 * Lisensi: Pexels License (bebas dipakai, atribusi tidak wajib).
 * Sumber: https://www.pexels.com  — id foto ditulis pada konstanta FOTO.
 *
 * Jalankan: php tools/fetch-product-photos.php [--force]
 */

$force = in_array('--force', $argv, true);

// --only=produk-23.jpg,produk-24.jpg : batasi unduhan ke file tertentu saja,
// supaya memperbaiki satu-dua foto tidak perlu mengunduh ulang semuanya.
$only = null;

foreach ($argv as $arg) {
    if (str_starts_with($arg, '--only=')) {
        $only = array_values(array_filter(array_map('trim', explode(',', substr($arg, 7)))));
    }
}

$root = dirname(__DIR__);
$storageDir = $root.'/storage/app/public/products';
$publicDir = $root.'/public/images/products';

foreach ([$storageDir, $publicDir] as $dir) {
    if (! is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

/**
 * Peta nama file lokal -> id foto Pexels.
 *
 * - produk-01..25.jpg : satu foto berbeda per produk
 * - cat-*.jpg         : gambar hero carousel + cadangan per kategori
 *
 * @var array<string, int>
 */
const FOTO = [
    // --- Cadangan per kategori (dipakai juga oleh hero carousel) ---
    'cat-interior.jpg' => 5691694,      // tukang meng-roll dinding apartemen terang
    'cat-eksterior.jpg' => 34581859,    // pekerja mengecat fasad gedung tinggi
    'cat-kayu-besi.jpg' => 5974326,     // mengampelas kusen kayu
    'alat-pengecatan.jpg' => 5799096,   // roller & kuas di tray plastik
    'thinner-pelarut.jpg' => 2786527,   // drum plastik berisi larutan
    'cat-tembok-putih.jpg' => 5691692,  // roller di dinding putih

    // --- Cat Tembok Interior (kaleng cat + dinding & roller) ---
    'produk-01.jpg' => 3616760,   // gallons of paint
    'produk-02.jpg' => 6764238,   // paintbrushes and paint buckets
    'produk-03.jpg' => 26632166,  // ember cat di depan dinding mural
    'produk-04.jpg' => 6764240,   // containers with paints
    'produk-05.jpg' => 1669754,   // person holding paint roller on wall (putih)

    // --- Cat Tembok Eksterior (fasad rumah + perlindungan dinding) ---
    'produk-06.jpg' => 13714499,  // construction worker painting a building
    'produk-07.jpg' => 18969810,  // man renovating store facade on ladder
    'produk-08.jpg' => 12534266,  // sealing of a concrete (pelapis anti bocor)
    'produk-09.jpg' => 36096165,  // scaffolding: mengecat eksterior gedung
    'produk-10.jpg' => 26918635,  // man painting a building

    // --- Cat Kayu & Besi (kayu, logam, semprot, dempul) ---
    'produk-11.jpg' => 5710745,   // artisan polishing wooden plank
    'produk-12.jpg' => 5853118,   // painted iron barrier (pagar besi dicat)
    'produk-13.jpg' => 37661593,  // craftsman varnishing wood (woodstain)
    'produk-14.jpg' => 7109129,   // menyemprot cat hitam ke dinding beton
    'produk-15.jpg' => 6654749,   // amplas & poles bilah kayu

    // --- Alat Pengecatan (Kuas/Roller/Tray) ---
    'produk-16.jpg' => 9308109,   // paint brushes in a can
    'produk-17.jpg' => 5799052,   // paint brush covered in paint
    'produk-18.jpg' => 5691700,   // roller in paint tray with white paint
    'produk-19.jpg' => 34046208,  // close-up paint roller on wall
    'produk-20.jpg' => 7217952,   // roller & brush on plastic tray
    'produk-21.jpg' => 6791489,   // sanding a wooden board

    // --- Thinner & Pelarut (botol & jerigen larutan) ---
    'produk-22.jpg' => 592670,    // baris botol larutan (thinner 1L)
    'produk-23.jpg' => 9381058,   // botol & kaleng pelarut di rak display
    'produk-24.jpg' => 4465828,   // botol minyak cat kayu (cairan kuning)
    'produk-25.jpg' => 10566503,  // jerigen putih 5L + botol pelarut

];

$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: TanjungJayaSeeder/1.0 (demo lokal)\r\n",
        'timeout' => 15,
        'ignore_errors' => true,
    ],
]);

/** Validasi sederhana: JPEG asli (magic bytes FF D8) dan berukuran wajar. */
$jpegValid = fn (string $isi): bool => strlen($isi) > 5000 && str_starts_with($isi, "\xFF\xD8\xFF");

$sukses = 0;
$gagal = [];

foreach (FOTO as $nama => $id) {
    if ($only !== null && ! in_array($nama, $only, true)) {
        continue;
    }

    $tujuan = [$storageDir.'/'.$nama, $publicDir.'/'.$nama];

    if (! $force && file_exists($tujuan[0]) && file_exists($tujuan[1])) {
        echo sprintf("  = %-22s dilewati (sudah ada)\n", $nama);

        continue;
    }

    $url = sprintf(
        'https://images.pexels.com/photos/%d/pexels-photo-%d.jpeg?auto=compress&cs=tinysrgb&w=1000',
        $id,
        $id,
    );

    $isi = @file_get_contents($url, false, $context);

    if ($isi === false || ! $jpegValid($isi)) {
        $gagal[] = sprintf('%s (id %d)', $nama, $id);
        echo sprintf("  x %-22s GAGAL\n", $nama);

        continue;
    }

    foreach ($tujuan as $path) {
        file_put_contents($path, $isi);
    }

    printf("  + %-22s %5.0f KB  (pexels %d)\n", $nama, strlen($isi) / 1024, $id);
    $sukses++;
}

echo "\nSelesai: {$sukses} foto diunduh";
echo $gagal ? ' — gagal: '.implode(', ', $gagal) : '';
echo "\n";

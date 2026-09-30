<?php

/**
 * Generator gambar produk lokal.
 *
 * Membuat 6 varian gambar produk dengan mentransformasi sumber tunggal
 * (painting.jpg) memakai GD: crop, tint, overlay warna, dan mode crop
 * berbeda supaya setiap produk punya visual yang tidak identik.
 * Tidak butuh koneksi internet.
 *
 * Output ditulis ke DUA lokasi:
 *   1. storage/app/public/products/  -> yang dirender aplikasi lewat Storage::url()
 *   2. public/images/products/       -> arsip sumber, dipakai sebagai bahan mentah
 *
 * Jalankan: php tools/generate-product-images.php
 */
$sumberDir = dirname(__DIR__).'/public/images/products';
$outputDir = dirname(__DIR__).'/storage/app/public/products';
$source = $sumberDir.'/painting.jpg';

if (! is_dir($outputDir)) {
    mkdir($outputDir, 0775, true);
}

if (! file_exists($source)) {
    fwrite(STDERR, "Sumber tidak ditemukan: {$source}\n");
    exit(1);
}

if (! extension_loaded('gd')) {
    fwrite(STDERR, "Ekstensi GD tidak aktif.\n");
    exit(1);
}

/**
 * Definisi varian: [nama file, lebar, tinggi, mode crop, warna tint, intensitas]
 *  - crop 'cover' = ambil bagian tengah lalu scale
 *  - crop 'zoom'  = crop lebih rapat (detail)
 *  - tint dipakai untuk memberi nuansa warna berbeda per varian
 */
$variants = [
    ['cat-interior.jpg',      900, 900, 'cover', [235, 245, 255], 0.28],
    ['cat-eksterior.jpg',     900, 900, 'zoom',  [255, 240, 225], 0.30],
    ['cat-kayu-besi.jpg',     900, 900, 'cover', [255, 230, 230], 0.26],
    ['alat-pengecatan.jpg',   900, 900, 'zoom',  [235, 255, 240], 0.24],
    ['thinner-pelarut.jpg',   900, 900, 'cover', [245, 240, 255], 0.30],
    ['cat-tembok-putih.jpg',  900, 900, 'zoom',  [255, 255, 245], 0.22],
];

$src = imagecreatefromjpeg($source);
$srcW = imagesx($src);
$srcH = imagesy($src);

echo "Sumber: {$srcW}x{$srcH}\n";

$created = 0;

foreach ($variants as [$name, $outW, $outH, $mode, $tint, $strength]) {
    // 1. Tentukan area crop dari sumber
    if ($mode === 'zoom') {
        // crop 70% area tengah -> terlihat lebih dekat / detail
        $cropW = (int) round($srcW * 0.70);
        $cropH = (int) round($srcH * 0.70);
    } else {
        // cover: pakai seluruh sumber
        $cropW = $srcW;
        $cropH = $srcH;
    }

    $cropX = (int) round(($srcW - $cropW) / 2);
    $cropY = (int) round(($srcH - $cropH) / 2);

    // 2. Canvas keluaran (persegi)
    $canvas = imagecreatetruecolor($outW, $outH);

    // 3. Resample crop sumber ke canvas: jaga aspect ratio, cover penuh
    $scale = max($outW / $cropW, $outH / $cropH);
    $drawW = (int) round($cropW * $scale);
    $drawH = (int) round($cropH * $scale);
    $drawX = (int) round(($outW - $drawW) / 2);
    $drawY = (int) round(($outH - $drawH) / 2);

    imagecopyresampled(
        $canvas, $src,
        $drawX, $drawY, $cropX, $cropY,
        $drawW, $drawH, $cropW, $cropH
    );

    // 4. Lapisan tint semi-transparan -> beda nuansa warna tiap varian
    $overlay = imagecreatetruecolor($outW, $outH);
    $color = imagecolorallocate($overlay, $tint[0], $tint[1], $tint[2]);
    imagefilledrectangle($overlay, 0, 0, $outW, $outH, $color);

    imagecopymerge($canvas, $overlay, 0, 0, 0, 0, $outW, $outH, (int) round($strength * 100));
    imagedestroy($overlay);

    // 5. Gradasi gelap tipis di bawah -> kesan foto produk katalog
    $shadow = imagecreatetruecolor($outW, $outH);
    $dark = imagecolorallocate($shadow, 15, 23, 42);
    imagefilledrectangle($shadow, 0, 0, $outW, $outH, $dark);
    imagecopymerge($canvas, $shadow, 0, (int) round($outH * 0.72), 0, 0, $outW, (int) round($outH * 0.28), 18);
    imagedestroy($shadow);

    // 6. Simpan & bersihkan (kualitas 82 -> ukuran file wajar)
    $arsip = $sumberDir.'/'.$name;
    $target = $outputDir.'/'.$name;

    imagejpeg($canvas, $arsip, 82);
    imagejpeg($canvas, $target, 82);
    imagedestroy($canvas);

    $size = round(filesize($target) / 1024, 1);
    echo sprintf("  + %-26s %4dx%-4d %6s KB  [%s]\n", $name, $outW, $outH, $size, $mode);
    $created++;
}

imagedestroy($src);
echo "\nSelesai: {$created} gambar ditulis ke:\n";
echo "  - storage/app/public/products/ (dirender aplikasi)\n";
echo "  - public/images/products/      (arsip sumber)\n";

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
 * Definisi varian kategori: [nama file, mode crop, warna tint, intensitas]
 *  - crop 'cover' = pakai seluruh sumber
 *  - crop 'zoom'  = crop lebih rapat (terlihat lebih detail)
 *  - tint dipakai untuk memberi nuansa warna berbeda per varian
 *
 * 6 gambar ini dipakai untuk banner carousel & sebagai cadangan kategori.
 */
$variants = [
    ['cat-interior.jpg',      'cover', [235, 245, 255], 0.28],
    ['cat-eksterior.jpg',     'zoom',  [255, 240, 225], 0.30],
    ['cat-kayu-besi.jpg',     'cover', [255, 230, 230], 0.26],
    ['alat-pengecatan.jpg',   'zoom',  [235, 255, 240], 0.24],
    ['thinner-pelarut.jpg',   'cover', [245, 240, 255], 0.30],
    ['cat-tembok-putih.jpg',  'zoom',  [255, 255, 245], 0.22],
];

/**
 * Palet tint untuk varian per-produk. Dipilih supaya tiap produk punya
 * nuansa warna sendiri, sehingga katalog tidak terlihat berisi foto kembar
 * ketika beberapa produk satu kategori tampil berdampingan.
 */
$paletProduk = [
    [235, 245, 255], // biru sejuk
    [255, 240, 225], // krem hangat
    [255, 230, 230], // rose lembut
    [235, 255, 240], // mint
    [245, 240, 255], // lavender
    [255, 255, 245], // ivory
    [240, 250, 255], // langit
    [255, 245, 235], // pasir
    [235, 250, 250], // aqua
    [250, 240, 250], // lilac
];

$src = imagecreatefromjpeg($source);
$srcW = imagesx($src);
$srcH = imagesy($src);

$outW = 900;
$outH = 900;

/**
 * Bangun canvas 900x900 dari sumber, dengan crop & mode tertentu.
 */
$buatGambar = function (array $tint, float $strength, string $mode, array $opsi = []) use ($src, $srcW, $srcH, $outW, $outH) {
    $flipH = $opsi['flipH'] ?? false;
    $flipV = $opsi['flipV'] ?? false;
    $rotate = $opsi['rotate'] ?? 0;          // derajat
    $offsetX = $opsi['offsetX'] ?? 0;        // -1..1, geser horizontal
    $offsetY = $opsi['offsetY'] ?? 0;        // -1..1, geser vertikal
    $band = $opsi['band'] ?? null;           // warna bilah aksen di tepi bawah

    // Latar saat rotasi: selalu abu sangat terang, bukan hitam. Latar gelap
    // membuat sudut gambar tampak seperti lubang hitam saat diputar.
    $bgTint = [247, 249, 251];

    // 1. Tentukan area crop dari sumber
    if ($mode === 'zoom') {
        $cropW = (int) round($srcW * 0.70);
        $cropH = (int) round($srcH * 0.70);
    } else {
        $cropW = $srcW;
        $cropH = $srcH;
    }

    // Titik tengah crop digeser sesuai offset (dijaga tetap di dalam sumber)
    $cx = ($srcW / 2) + ($offsetX * ($srcW - $cropW) / 2);
    $cy = ($srcH / 2) + ($offsetY * ($srcH - $cropH) / 2);

    $cropX = (int) round(max(0, min($srcW - $cropW, $cx - $cropW / 2)));
    $cropY = (int) round(max(0, min($srcH - $cropH, $cy - $cropH / 2)));

    // 2. Siapkan potongan sumber sebagai gambar tersendiri agar bisa
    //    dicermin / diputar tanpa merusak sumber aslinya.
    $piece = imagecreatetruecolor($cropW, $cropH);
    imagecopyresampled($piece, $src, 0, 0, $cropX, $cropY, $cropW, $cropH, $cropW, $cropH);

    if ($flipH || $flipV) {
        // imageflip tidak selalu tersedia di semua build GD -> cek ketersediaan
        if (function_exists('imageflip')) {
            $modeFlip = $flipH && $flipV ? IMG_FLIP_BOTH : ($flipH ? IMG_FLIP_HORIZONTAL : IMG_FLIP_VERTICAL);
            imageflip($piece, $modeFlip);
        }
    }

    if ($rotate !== 0) {
        $piece = imagerotate($piece, $rotate, imagecolorallocate($piece, $bgTint[0], $bgTint[1], $bgTint[2]));

        // Potong ulang area tengah setelah rotasi supaya tepi berlatar
        // (yang muncul akibat pemutaran) tidak ikut terlihat di hasil akhir.
        $pw = imagesx($piece);
        $ph = imagesy($piece);
        $innerW = (int) round($pw * 0.88);
        $innerH = (int) round($ph * 0.88);

        $inner = imagecreatetruecolor($innerW, $innerH);
        imagecopyresampled(
            $inner, $piece,
            0, 0,
            (int) round(($pw - $innerW) / 2), (int) round(($ph - $innerH) / 2),
            $innerW, $innerH,
            $innerW, $innerH
        );
        imagedestroy($piece);
        $piece = $inner;
    }

    // 3. Canvas keluaran (persegi)
    $canvas = imagecreatetruecolor($outW, $outH);
    $bg = imagecolorallocate($canvas, $bgTint[0], $bgTint[1], $bgTint[2]);
    imagefilledrectangle($canvas, 0, 0, $outW, $outH, $bg);

    // 4. Tempel potongan ke canvas (cover penuh, tetap di tengah)
    $pw = imagesx($piece);
    $ph = imagesy($piece);
    $scale = max($outW / $pw, $outH / $ph);
    $drawW = (int) round($pw * $scale);
    $drawH = (int) round($ph * $scale);
    $drawX = (int) round(($outW - $drawW) / 2);
    $drawY = (int) round(($outH - $drawH) / 2);

    imagecopyresampled($canvas, $piece, $drawX, $drawY, 0, 0, $drawW, $drawH, $pw, $ph);
    imagedestroy($piece);

    // 5. Lapisan tint semi-transparan -> beda nuansa warna tiap varian
    $overlay = imagecreatetruecolor($outW, $outH);
    $color = imagecolorallocate($overlay, $tint[0], $tint[1], $tint[2]);
    imagefilledrectangle($overlay, 0, 0, $outW, $outH, $color);
    imagecopymerge($canvas, $overlay, 0, 0, 0, 0, $outW, $outH, (int) round($strength * 100));
    imagedestroy($overlay);

    // 6. Aksen warna: bilah tipis di tepi bawah, BUKAN pita tinggi.
    //
    //    Sebelumnya pita setinggi 10% membuat sebagian foto tertutup dan
    //    terlihat seperti gambar terpotong. Sekarang hanya bilah tipis
    //    (3% tinggi) di tepi, sehingga foto produk tetap utuh.
    if ($band !== null) {
        $bandLayer = imagecreatetruecolor($outW, $outH);
        $bandColor = imagecolorallocate($bandLayer, $band[0], $band[1], $band[2]);
        $tinggiBand = (int) round($outH * 0.03);
        $yBand = $outH - $tinggiBand;

        imagefilledrectangle($bandLayer, 0, 0, $outW, $tinggiBand, $bandColor);

        imagecopymerge(
            $canvas, $bandLayer,
            0, $yBand,   // tujuan: mulai dari yBand
            0, 0,        // sumber: dari awal lapisan
            $outW, $tinggiBand,
            90
        );

        imagedestroy($bandLayer);
    }

    // 7. Gradasi gelap tipis di bawah -> kesan foto produk katalog
    $shadow = imagecreatetruecolor($outW, $outH);
    $dark = imagecolorallocate($shadow, 15, 23, 42);
    imagefilledrectangle($shadow, 0, 0, $outW, $outH, $dark);
    imagecopymerge($canvas, $shadow, 0, (int) round($outH * 0.72), 0, 0, $outW, (int) round($outH * 0.28), 18);
    imagedestroy($shadow);

    return $canvas;
};

$simpan = function ($canvas, string $name) use ($sumberDir, $outputDir) {
    imagejpeg($canvas, $sumberDir.'/'.$name, 82);
    imagejpeg($canvas, $outputDir.'/'.$name, 82);
    imagedestroy($canvas);

    return round(filesize($outputDir.'/'.$name) / 1024, 1);
};

echo "Sumber: {$srcW}x{$srcH}\n\n";

$created = 0;

// --- Bagian A: gambar kategori (dipakai banner carousel) ---
echo "Gambar kategori:\n";

foreach ($variants as [$name, $mode, $tint, $strength]) {
    $canvas = $buatGambar($tint, $strength, $mode);
    $size = $simpan($canvas, $name);

    echo sprintf("  + %-26s %6s KB  [%s]\n", $name, $size, $mode);
    $created++;
}

// --- Bagian B: gambar per-produk (25 file, visual berbeda tiap produk) ---
echo "\nGambar per-produk:\n";

$jumlahProduk = 25;

/**
 * Kombinasi komposisi. Setiap produk mengambil satu kombinasi berbeda,
 * sehingga visual antar produk benar-benar beda bentuknya — bukan hanya
 * beda warna. Ini menuntaskan masalah katalog terlihat berisi foto kembar.
 *
 * Format: [mode, flipH, flipV, rotate, offsetX, offsetY, warna pita]
 */
$komposisi = [
    ['cover', false, false,  0,  0.0,  0.0, null],
    ['zoom',  true,  false,  0,  0.0,  0.0, null],
    ['cover', false,  true,  0, -0.6,  0.0, [16, 185, 129]],
    ['zoom',  true,  true,   0,  0.6,  0.0, null],
    ['cover', false, false,  0,  0.0, -0.5, [59, 130, 246]],
    ['zoom',  false, false,  0,  0.0,  0.5, null],
    ['cover', true,  false, -4,  0.0,  0.0, [245, 158, 11]],
    ['zoom',  false, true,   4,  0.0,  0.0, null],
    ['cover', false, false,  0, -1.0,  0.0, [139, 92, 246]],
    ['zoom',  true,  false,  0,  1.0,  0.0, null],
    ['cover', false, true,  -3,  0.0,  0.0, [236, 72, 153]],
    ['zoom',  true,  true,   3,  0.0,  0.0, null],
    ['cover', false, false,  0,  0.0,  1.0, [6, 182, 212]],
    ['zoom',  false, false,  0,  0.0, -1.0, null],
    ['cover', true,  false,  5,  0.0,  0.0, [239, 68, 68]],
    ['zoom',  false, true, -5,  0.0,  0.0, null],
    ['cover', false, false,  0,  0.5,  0.5, [132, 204, 22]],
    ['zoom',  true,  false,  0, -0.5, -0.5, null],
    ['cover', false, true,   0,  1.0,  1.0, [100, 116, 139]],
    ['zoom',  true,  true,   0, -1.0, -1.0, null],
    ['cover', false, false,  2,  0.0,  0.0, [20, 184, 166]],
    ['zoom',  false, false, -2,  0.0,  0.0, null],
    ['cover', true,  false,  0,  0.35, -0.35, [249, 115, 22]],
    ['zoom',  false, true,   0, -0.35,  0.35, null],
    ['cover', false, false,  0,  0.0,  0.0, [168, 85, 247]],
];

for ($i = 1; $i <= $jumlahProduk; $i++) {
    $k = $komposisi[($i - 1) % count($komposisi)];
    [$mode, $flipH, $flipV, $rotate, $offsetX, $offsetY, $band] = $k;

    // Variasi diturunkan dari indeks produk supaya deterministik:
    // menjalankan ulang menghasilkan file yang sama persis.
    $tint = $paletProduk[($i - 1) % count($paletProduk)];
    $strength = 0.18 + (($i % 6) * 0.025); // 0.18 .. 0.30

    $nama = sprintf('produk-%02d.jpg', $i);

    $canvas = $buatGambar($tint, $strength, $mode, [
        'flipH' => $flipH,
        'flipV' => $flipV,
        'rotate' => $rotate,
        'offsetX' => $offsetX,
        'offsetY' => $offsetY,
        'band' => $band,
    ]);

    $size = $simpan($canvas, $nama);

    $tanda = [];
    if ($flipH) $tanda[] = 'flipH';
    if ($flipV) $tanda[] = 'flipV';
    if ($rotate) $tanda[] = "rot{$rotate}";
    if ($offsetX || $offsetY) $tanda[] = sprintf('geser %.1f,%.1f', $offsetX, $offsetY);
    if ($band) $tanda[] = 'pita';

    echo sprintf("  + %-16s %6s KB  [%s%s]\n", $nama, $size, $mode, $tanda ? ', '.implode(', ', $tanda) : '');
    $created++;
}

imagedestroy($src);
echo "\nSelesai: {$created} gambar ditulis ke:\n";
echo "  - storage/app/public/products/ (dirender aplikasi)\n";
echo "  - public/images/products/      (arsip sumber)\n";

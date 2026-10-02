<?php

/**
 * Pemeriksa foto produk Tanjung Jaya.
 *
 * Memastikan setiap produk di database:
 *   1. memakai foto yang benar-benar ada di storage/app/public/products
 *      sekaligus di arsip public/images/products;
 *   2. tidak berbagi foto yang sama dengan produk lain (katalog tidak boleh
 *      terlihat berisi foto kembar).
 *
 * Jalankan: php tools/check-product-images.php
 * Keluar dengan kode 1 bila ada masalah (mudah dipakai di CI).
 */

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$storageDir = storage_path('app/public');
$publicDir = public_path('images');

$produk = App\Models\Product::query()->orderBy('id')->get(['id', 'name', 'image']);

$tanpaFile = [];
$petaFoto = [];

foreach ($produk as $p) {
    $relatif = (string) $p->image;

    $petaFoto[$relatif][] = $p->name;

    if (! file_exists($storageDir.'/'.$relatif) || ! file_exists($publicDir.'/'.$relatif)) {
        $tanpaFile[] = sprintf('%s -> %s', $p->name, $relatif);
    }
}

$kembar = array_filter($petaFoto, fn (array $daftar): bool => count($daftar) > 1);

printf("Produk diperiksa : %d\n", $produk->count());
printf("Foto dipakai     : %d (unik)\n", count($petaFoto));

if ($tanpaFile) {
    printf("\nFILE TIDAK DITEMUKAN (%d):\n", count($tanpaFile));

    foreach ($tanpaFile as $baris) {
        echo '  x '.$baris."\n";
    }
}

if ($kembar) {
    printf("\nFOTO DIPAKAI BERULANG (%d):\n", count($kembar));

    foreach ($kembar as $foto => $daftar) {
        echo '  x '.$foto.' dipakai '.count($daftar).'x: '.implode(', ', $daftar)."\n";
    }
}

$bermasalah = $tanpaFile !== [] || $kembar !== [];

echo $bermasalah
    ? "\nHASIL: ada masalah pada foto produk\n"
    : "\nHASIL: semua produk punya foto unik dan file-nya tersedia\n";

exit($bermasalah ? 1 : 0);
<?php

/**
 * Smoke test ringan Tanjung Jaya.
 *
 * Menjalankan request HTTP ke halaman-halaman utama langsung lewat kernel
 * Laravel (tanpa server), lalu melaporkan status code dan menandai halaman
 * yang isinya mengandung pesan error (ParseError/ViewException/Whoops).
 *
 * Dipakai untuk memastikan tidak ada view yang pecah setelah perubahan UI.
 *
 * Jalankan: php tools/smoke-test.php
 */

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';

// Bootstrap console kernel lebih dulu supaya koneksi database & config siap
// dipakai sebelum query model di bawah.
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

/** @var array<int, string> */
$rute = [
    '/',
    '/login',
    '/register',
    '/carts',
    '/orders',
    '/wishlists',
    '/reviews',
    '/returns',
];

// Halaman detail produk butuh id yang benar-benar ada di database.
$productId = App\Models\Product::query()->value('id');

if ($productId) {
    $rute[] = '/produk/'.$productId;
}

$bermasalah = 0;

foreach ($rute as $path) {
    $request = Illuminate\Http\Request::create($path, 'GET');

    try {
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        $body = (string) $response->getContent();

        $adaError = preg_match('/Whoops|Fatal error|ParseError|ViewException|syntax error/i', $body) === 1;
        $statusBuruk = $status >= 500 || $status === 404;

        if ($adaError || $statusBuruk) {
            $bermasalah++;
        }

        printf(
            "%-16s -> %d%s%s\n",
            $path,
            $status,
            $adaError ? '  <-- pesan error di isi halaman' : '',
            $statusBuruk && ! $adaError ? '  <-- status tidak diharapkan' : '',
        );
    } catch (Throwable $e) {
        $bermasalah++;
        printf("%-16s -> GAGAL: %s: %s\n", $path, $e::class, $e->getMessage());
    }
}

echo $bermasalah
    ? "\nHASIL: {$bermasalah} halaman bermasalah\n"
    : "\nHASIL: semua halaman render tanpa error\n";

exit($bermasalah ? 1 : 0);
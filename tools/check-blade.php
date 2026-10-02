<?php

/**
 * Pemeriksa sintaks Blade Tanjung Jaya.
 *
 * Setiap view Blade di-compile memakai BladeCompiler lalu hasil compile-nya
 * divalidasi dengan token_get_all(..., TOKEN_PARSE). Ini menangkap kasus yang
 * TIDAK terdeteksi `php artisan view:cache`, mis. `@if` tanpa `@endif` yang
 * membuat hasil compile berisi `endforeach` di dalam blok `if(...):` sehingga
 * halaman gagal dengan ParseError saat dirender.
 *
 * Jalankan: php tools/check-blade.php
 * Keluar dengan kode 1 bila ada view yang rusak (mudah dipakai di CI).
 */

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$compiler = $app->make('blade.compiler');
$root = dirname(__DIR__);

$views = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root.'/resources/views', FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $views[] = $file->getPathname();
    }
}

sort($views);

$rusak = [];

foreach ($views as $view) {
    $compiler->setPath($view);
    $php = $compiler->compileString(file_get_contents($view));

    try {
        token_get_all($php, TOKEN_PARSE);
    } catch (ParseError $e) {
        $baris = preg_split("/\r\n|\n|\r/", $php);
        $petunjuk = trim($baris[$e->getLine() - 1] ?? '');

        $rusak[] = sprintf(
            "%s\n      %s (compile baris %d)\n      %s",
            str_replace($root.DIRECTORY_SEPARATOR, '', $view),
            $e->getMessage(),
            $e->getLine(),
            substr($petunjuk, 0, 160),
        );
    }
}

printf("Diperiksa: %d view\n", count($views));

if ($rusak) {
    printf("\nRUSAK (%d):\n", count($rusak));

    foreach ($rusak as $r) {
        echo '  x '.$r."\n";
    }

    exit(1);
}

echo "OK: semua view Blade valid\n";
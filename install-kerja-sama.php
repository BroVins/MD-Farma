<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Installer fitur Kerja Sama - Apotek MD Farma
|--------------------------------------------------------------------------
|
| Jalankan dari root proyek:
| php install-kerja-sama.php
|
| Script akan:
| - membuat backup lokal file yang diubah;
| - menambah route /kerja-sama;
| - menambah method HomeController::partnership();
| - menambah menu dan tombol Kerja Sama pada landing page;
| - menyimpan nomor dan pesan WhatsApp ke .env;
| - membersihkan cache Laravel.
|
*/

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "Installer ini hanya boleh dijalankan melalui terminal.\n");
    exit(1);
}

$root = __DIR__;
$options = getopt('', [
    'number:',
    'message:',
    'skip-artisan',
]);

function output(string $message = ''): void
{
    fwrite(STDOUT, $message . PHP_EOL);
}

function fail(string $message): never
{
    fwrite(STDERR, "[GAGAL] {$message}\n");
    exit(1);
}

function prompt(string $label, string $default = ''): string
{
    $suffix = $default !== '' ? " [{$default}]" : '';
    fwrite(STDOUT, $label . $suffix . ': ');

    $value = trim((string) fgets(STDIN));

    return $value !== '' ? $value : $default;
}

function normalizeWhatsappNumber(string $number): string
{
    $digits = preg_replace('/\D+/', '', $number) ?? '';

    if (str_starts_with($digits, '0')) {
        $digits = '62' . substr($digits, 1);
    } elseif (str_starts_with($digits, '8')) {
        $digits = '62' . $digits;
    }

    return $digits;
}

function quoteEnvValue(string $value): string
{
    $value = preg_replace('/\R+/', ' ', trim($value)) ?? '';
    $value = str_replace(
        ['\\', '"'],
        ['\\\\', '\\"'],
        $value
    );

    return '"' . $value . '"';
}

function setEnvValue(string $content, string $key, string $value): string
{
    $line = $key . '=' . quoteEnvValue($value);
    $pattern = '/^' . preg_quote($key, '/') . '=.*$/m';

    if (preg_match($pattern, $content) === 1) {
        return (string) preg_replace($pattern, $line, $content, 1);
    }

    return rtrim($content) . PHP_EOL . $line . PHP_EOL;
}

function backupFile(string $root, string $backupRoot, string $relative): void
{
    $source = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);

    if (!is_file($source)) {
        return;
    }

    $destination = $backupRoot . DIRECTORY_SEPARATOR
        . str_replace('/', DIRECTORY_SEPARATOR, $relative);

    $directory = dirname($destination);

    if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
        fail("Tidak dapat membuat folder backup: {$directory}");
    }

    if (!copy($source, $destination)) {
        fail("Tidak dapat membackup file: {$relative}");
    }
}

$requiredFiles = [
    'artisan',
    '.env',
    'routes/web.php',
    'app/Http/Controllers/HomeController.php',
    'resources/views/home.blade.php',
    'resources/views/partnership.blade.php',
    'config/mdfarma.php',
];

foreach ($requiredFiles as $relative) {
    $path = $root . DIRECTORY_SEPARATOR
        . str_replace('/', DIRECTORY_SEPARATOR, $relative);

    if (!is_file($path)) {
        fail(
            "File {$relative} tidak ditemukan. "
            . "Ekstrak ZIP ke root proyek MD-Farma terlebih dahulu."
        );
    }
}

output('=== Instalasi Fitur Kerja Sama MD Farma ===');
output();

$numberInput = isset($options['number'])
    ? (string) $options['number']
    : prompt('Nomor WhatsApp resmi', '');

$number = normalizeWhatsappNumber($numberInput);

if (
    $number === ''
    || !str_starts_with($number, '62')
    || strlen($number) < 10
    || strlen($number) > 15
) {
    fail(
        'Nomor WhatsApp tidak valid. '
        . 'Gunakan format 628xxxxxxxxxx atau 08xxxxxxxxxx.'
    );
}

$defaultMessage =
    'Halo Apotek MD Farma, saya ingin menanyakan informasi kerja sama.';

$message = isset($options['message'])
    ? trim((string) $options['message'])
    : prompt('Pesan awal WhatsApp', $defaultMessage);

if ($message === '') {
    $message = $defaultMessage;
}

$timestamp = date('Ymd-His');
$backupRoot = $root
    . DIRECTORY_SEPARATOR . 'storage'
    . DIRECTORY_SEPARATOR . 'app'
    . DIRECTORY_SEPARATOR . 'feature-backups'
    . DIRECTORY_SEPARATOR . 'kerja-sama-' . $timestamp;

$filesToBackup = [
    '.env',
    'routes/web.php',
    'app/Http/Controllers/HomeController.php',
    'resources/views/home.blade.php',
];

foreach ($filesToBackup as $relative) {
    backupFile($root, $backupRoot, $relative);
}

$routePath = $root . DIRECTORY_SEPARATOR . 'routes'
    . DIRECTORY_SEPARATOR . 'web.php';
$controllerPath = $root . DIRECTORY_SEPARATOR . 'app'
    . DIRECTORY_SEPARATOR . 'Http'
    . DIRECTORY_SEPARATOR . 'Controllers'
    . DIRECTORY_SEPARATOR . 'HomeController.php';
$homePath = $root . DIRECTORY_SEPARATOR . 'resources'
    . DIRECTORY_SEPARATOR . 'views'
    . DIRECTORY_SEPARATOR . 'home.blade.php';
$envPath = $root . DIRECTORY_SEPARATOR . '.env';

$routes = (string) file_get_contents($routePath);
$controller = (string) file_get_contents($controllerPath);
$home = (string) file_get_contents($homePath);
$env = (string) file_get_contents($envPath);

/*
|--------------------------------------------------------------------------
| Siapkan patch route
|--------------------------------------------------------------------------
*/

if (!str_contains($routes, "->name('partnership')")) {
    $homeRoutePattern = <<<'REGEX'
~Route::get\(\s*['"]\/['"]\s*,\s*\[\s*HomeController::class\s*,\s*['"]index['"]\s*\]\s*\)\s*->name\(\s*['"]home['"]\s*\)\s*;~s
REGEX;

    $routeBlock = <<<'PHP'

Route::get(
    '/kerja-sama',
    [HomeController::class, 'partnership']
)->name('partnership');
PHP;

    $patchedRoutes = preg_replace_callback(
        $homeRoutePattern,
        static fn (array $matches): string => $matches[0] . $routeBlock,
        $routes,
        1,
        $routeCount
    );

    if (!is_string($patchedRoutes) || $routeCount !== 1) {
        fail(
            'Route home tidak ditemukan dalam routes/web.php. '
            . 'Tidak ada file yang diubah.'
        );
    }

    $routes = $patchedRoutes;
}

/*
|--------------------------------------------------------------------------
| Siapkan patch HomeController
|--------------------------------------------------------------------------
*/

if (!str_contains($controller, 'function partnership')) {
    $method = <<<'PHP'

    public function partnership()
    {
        $number = preg_replace(
            '/\D+/',
            '',
            (string) config('mdfarma.whatsapp_number')
        );

        $message = trim(
            (string) config('mdfarma.whatsapp_message')
        );

        $isConfigured = is_string($number)
            && str_starts_with($number, '62')
            && strlen($number) >= 10
            && strlen($number) <= 15;

        $whatsappUrl = $isConfigured
            ? 'https://wa.me/' . $number
                . ($message !== ''
                    ? '?text=' . rawurlencode($message)
                    : '')
            : null;

        $displayNumber = $isConfigured
            ? '+' . $number
            : null;

        return view('partnership', compact(
            'number',
            'message',
            'isConfigured',
            'whatsappUrl',
            'displayNumber'
        ));
    }
PHP;

    $lastBrace = strrpos($controller, '}');

    if ($lastBrace === false) {
        fail(
            'Struktur HomeController.php tidak dikenali. '
            . 'Tidak ada file yang diubah.'
        );
    }

    $controller = rtrim(substr($controller, 0, $lastBrace))
        . PHP_EOL
        . $method
        . PHP_EOL
        . '}'
        . PHP_EOL;
}

/*
|--------------------------------------------------------------------------
| Siapkan patch landing page
|--------------------------------------------------------------------------
*/

if (!str_contains($home, 'data-partnership-nav')) {
    $navNeedle = '                <a href="#marketplace">Marketplace</a>';

    if (!str_contains($home, $navNeedle)) {
        fail(
            'Menu Marketplace tidak ditemukan pada home.blade.php. '
            . 'Tidak ada file yang diubah.'
        );
    }

    $navReplacement = $navNeedle . PHP_EOL
        . '                <a'
        . ' data-partnership-nav'
        . ' href="{{ route(\'partnership\') }}">'
        . 'Kerja Sama</a>';

    $home = str_replace(
        $navNeedle,
        $navReplacement,
        $home,
        $navCount
    );

    if ($navCount !== 1) {
        fail(
            'Posisi menu landing page tidak unik. '
            . 'Tidak ada file yang diubah.'
        );
    }
}

if (!str_contains($home, 'data-partnership-hero')) {
    $heroPattern = <<<'REGEX'
~(?P<marketplace><a\s+class="button button-secondary"\s+href="#marketplace"\s*>\s*Belanja Obat\s*</a>)(?P<closing>\s*</div>\s*<div class="trust-row">)~s
REGEX;

    $heroButton = <<<'BLADE'
                        <a
                            class="button button-secondary"
                            data-partnership-hero
                            href="{{ route('partnership') }}"
                        >
                            Kerja Sama
                        </a>
BLADE;

    $patchedHome = preg_replace_callback(
        $heroPattern,
        static function (array $matches) use ($heroButton): string {
            return rtrim($matches['marketplace'])
                . PHP_EOL
                . $heroButton
                . PHP_EOL
                . $matches['closing'];
        },
        $home,
        1,
        $heroCount
    );

    if (!is_string($patchedHome) || $heroCount !== 1) {
        fail(
            'Tombol Belanja Obat tidak ditemukan pada hero landing page. '
            . 'Tidak ada file yang diubah.'
        );
    }

    $home = $patchedHome;
}

/*
|--------------------------------------------------------------------------
| Siapkan konfigurasi .env
|--------------------------------------------------------------------------
*/

$env = setEnvValue(
    $env,
    'MD_FARMA_WHATSAPP_NUMBER',
    $number
);
$env = setEnvValue(
    $env,
    'MD_FARMA_WHATSAPP_MESSAGE',
    $message
);

/*
|--------------------------------------------------------------------------
| Tulis perubahan setelah seluruh validasi berhasil
|--------------------------------------------------------------------------
*/

$writes = [
    $routePath => $routes,
    $controllerPath => $controller,
    $homePath => $home,
    $envPath => $env,
];

foreach ($writes as $path => $content) {
    if (file_put_contents($path, $content) === false) {
        fail(
            "Gagal menulis {$path}. "
            . "Gunakan backup di {$backupRoot} untuk pemulihan."
        );
    }
}

output('[OK] Route /kerja-sama ditambahkan.');
output('[OK] Halaman Kerja Sama ditambahkan.');
output('[OK] Menu dan tombol landing page ditambahkan.');
output('[OK] Nomor WhatsApp disimpan ke .env.');
output('[OK] Backup lokal: ' . $backupRoot);

if (!array_key_exists('skip-artisan', $options)) {
    output();
    output('Membersihkan cache Laravel...');

    $artisanCommand = escapeshellarg(PHP_BINARY)
        . ' '
        . escapeshellarg($root . DIRECTORY_SEPARATOR . 'artisan')
        . ' optimize:clear';

    passthru($artisanCommand, $artisanExitCode);

    if ($artisanExitCode !== 0) {
        output(
            '[PERINGATAN] optimize:clear gagal. '
            . 'Jalankan manual: php artisan optimize:clear'
        );
    }
}

output();
output('Instalasi selesai.');
output('Buka: http://localhost/md-farma/public/kerja-sama');
output('Atau sesuaikan URL dengan konfigurasi virtual host Anda.');

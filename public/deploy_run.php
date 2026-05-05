<?php
// FILE SEMENTARA - HAPUS SETELAH SELESAI DIPAKAI

// Keamanan: hanya bisa diakses dengan token rahasia
$token = $_GET['token'] ?? '';
if ($token !== 'deploy_secret_2026') {
    http_response_code(403);
    die('Forbidden');
}

define('BASE', dirname(__DIR__));
chdir(BASE);

$commands = [
    'git pull origin master',
    PHP_BINARY . ' artisan config:clear',
    PHP_BINARY . ' artisan route:clear',
    PHP_BINARY . ' artisan cache:clear',
    PHP_BINARY . ' artisan view:clear',
    PHP_BINARY . ' artisan migrate --force',
    PHP_BINARY . ' artisan storage:link --force 2>&1 || true',
];

// Jalankan commands standar dulu
echo '<pre style="background:#111;color:#0f0;padding:20px;font-size:14px;">';
echo "=== DEPLOY RUNNER ===\n\n";

foreach ($commands as $cmd) {
    echo "<b>$ $cmd</b>\n";
    $output = shell_exec($cmd . ' 2>&1');
    echo htmlspecialchars($output ?? '(no output)') . "\n";
    echo str_repeat('-', 60) . "\n";
}

// Migrasi file lama dari storage/app/public → public/storage
echo "\n<b>=== MIGRASI FILE STORAGE ===</b>\n";
$oldBase = BASE . '/storage/app/public';
$newBase = BASE . '/public/storage';

$dirs = ['landing-location', 'inbound-products', 'products'];
foreach ($dirs as $dir) {
    $oldDir = $oldBase . '/' . $dir;
    $newDir = $newBase . '/' . $dir;

    if (!is_dir($oldDir)) {
        echo "Skip $dir (folder lama tidak ada)\n";
        continue;
    }

    $files = glob($oldDir . '/*');
    $moved = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            $dest = $newDir . '/' . basename($file);
            if (!file_exists($dest)) {
                if (!is_dir($newDir)) mkdir($newDir, 0755, true);
                if (copy($file, $dest)) {
                    unlink($file);
                    $moved++;
                }
            }
        }
    }
    echo "Folder $dir: $moved file dipindah ke public/storage/$dir\n";
}

echo "\n✅ SELESAI. Hapus file ini sekarang!\n";
echo '</pre>';
$fakeEof = true; // stop duplicate pre

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
];

echo '<pre style="background:#111;color:#0f0;padding:20px;font-size:14px;">';
echo "=== DEPLOY RUNNER ===\n\n";

foreach ($commands as $cmd) {
    echo "<b>$ $cmd</b>\n";
    $output = shell_exec($cmd . ' 2>&1');
    echo htmlspecialchars($output ?? '(no output)') . "\n";
    echo str_repeat('-', 60) . "\n";
}

echo "\n✅ SELESAI. Hapus file ini sekarang!\n";
echo '</pre>';

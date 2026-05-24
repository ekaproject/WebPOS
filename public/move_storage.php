<?php
// Script sekali pakai - hapus setelah digunakan
$token = $_GET['token'] ?? '';
if ($token !== 'deploy_secret_2026') {
    http_response_code(403);
    die('Forbidden');
}

$appRoot = dirname(__DIR__);
$oldBase = $appRoot . '/storage/app/public';
$newBase = $appRoot . '/public/storage';

echo '<pre style="background:#111;color:#0f0;padding:20px;font-family:monospace;">';
echo "=== MOVE STORAGE FILES ===\n\n";

$dirs = ['inbound-products', 'landing-location', 'products', 'master-products'];
$totalMoved = 0;

foreach ($dirs as $dir) {
    $oldDir = $oldBase . '/' . $dir;
    $newDir = $newBase . '/' . $dir;

    echo "Folder: $dir\n";
    echo "  Dari : $oldDir\n";
    echo "  Ke   : $newDir\n";

    if (!is_dir($oldDir)) {
        echo "  [SKIP] Folder lama tidak ada\n\n";
        continue;
    }

    if (!is_dir($newDir)) {
        mkdir($newDir, 0755, true);
        echo "  [OK] Folder tujuan dibuat\n";
    }

    $files = array_filter(glob($oldDir . '/*'), 'is_file');
    $moved = 0;
    $skipped = 0;

    foreach ($files as $file) {
        $dest = $newDir . '/' . basename($file);
        if (file_exists($dest)) {
            $skipped++;
            continue;
        }
        if (copy($file, $dest)) {
            unlink($file);
            $moved++;
        } else {
            echo "  [ERROR] Gagal memindahkan: " . basename($file) . "\n";
        }
    }

    echo "  [DONE] Dipindah: $moved file, Skip (sudah ada): $skipped file\n\n";
    $totalMoved += $moved;
}

// Cek hasil akhir
echo "=== HASIL ===\n";
echo "Total file dipindah: $totalMoved\n\n";

foreach ($dirs as $dir) {
    $newDir = $newBase . '/' . $dir;
    $count = is_dir($newDir) ? count(array_filter(glob($newDir . '/*'), 'is_file')) : 0;
    echo "public/storage/$dir/ → $count file\n";
}

echo "\n✅ Selesai! Hapus file ini via cPanel.\n";
echo '</pre>';

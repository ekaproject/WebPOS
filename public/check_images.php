<?php
// Diagnostic - hapus setelah dipakai
$token = $_GET['token'] ?? '';
if ($token !== 'deploy_secret_2026') {
    http_response_code(403);
    die('Forbidden');
}

$appRoot = dirname(__DIR__);
$dirs = ['master-products', 'inbound-products', 'landing-location', 'products'];

echo '<pre style="background:#111;color:#0f0;padding:20px;font-family:monospace;">';
echo "=== CEK FILE GAMBAR ===\n\n";

foreach ($dirs as $dir) {
    $newLoc = $appRoot . '/public/storage/' . $dir;
    $oldLoc = $appRoot . '/storage/app/public/' . $dir;

    echo "[$dir]\n";
    echo "  public/storage/$dir : " . (is_dir($newLoc) ? count(array_filter(glob($newLoc.'/*'), 'is_file')) . ' file' : 'TIDAK ADA') . "\n";
    echo "  storage/app/public/$dir : " . (is_dir($oldLoc) ? count(array_filter(glob($oldLoc.'/*'), 'is_file')) . ' file' : 'TIDAK ADA') . "\n\n";
}

// Sample dari DB
echo "=== SAMPLE FILE DARI DB ===\n\n";
require $appRoot . '/vendor/autoload.php';
$app = require_once $appRoot . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$samples = [
    'master_products' => 'image',
    'inbound_items' => 'photo_path',
];

foreach ($samples as $table => $col) {
    try {
        $rows = \DB::table($table)->whereNotNull($col)->where($col, '!=', '')->limit(5)->pluck($col);
        echo "$table.$col:\n";
        foreach ($rows as $path) {
            $full = $appRoot . '/public/storage/' . $path;
            $exists = file_exists($full) ? 'ADA' : 'HILANG';
            echo "  [$exists] $path\n";
        }
        echo "\n";
    } catch (\Exception $e) {
        echo "$table: ERROR - " . $e->getMessage() . "\n\n";
    }
}

echo '</pre>';

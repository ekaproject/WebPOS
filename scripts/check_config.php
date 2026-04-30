<?php

$autoload = __DIR__ . '/..' . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    echo "MISSING_VENDOR" . PHP_EOL;
    exit(1);
}
require $autoload;

$app = require __DIR__ . '/..' . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "CONFIG_PASSWORD:" . (config('database.connections.mysql.password') ?? '[NULL]') . PHP_EOL;
echo "ENV_DB_PASSWORD:" . (env('DB_PASSWORD') ?? '[NULL]') . PHP_EOL;

// Try a quick connection check (without exposing full errors)
try {
    $pdo = DB::connection()->getPdo();
    echo "DB_CONNECTION_OK" . PHP_EOL;
} catch (Throwable $e) {
    echo "DB_ERR:" . $e->getMessage() . PHP_EOL;
}

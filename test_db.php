<?php
try {
    require __DIR__ . '/vendor/autoload.php';

    $app = require __DIR__ . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    $database = config('database.connections.mysql');
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%s;dbname=%s', $database['host'], $database['port'], $database['database']),
        $database['username'],
        $database['password']
    );
    echo "OK\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

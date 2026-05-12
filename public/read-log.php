<?php
$logsDir = __DIR__ . '/../storage/logs';
$logFile = $logsDir . '/laravel.log';

echo "<h3>Diagnostik Log Laravel</h3>";

if (!file_exists($logsDir)) {
    if (mkdir($logsDir, 0775, true)) {
        echo "<p style='color:green'>✅ Folder logs berhasil dibuat!</p>";
    } else {
        echo "<p style='color:red'>❌ Gagal membuat folder logs. Cek permission cPanel.</p>";
    }
} else {
    chmod($logsDir, 0775);
    echo "<p style='color:green'>✅ Folder logs sudah ada dan permission 775 di-set.</p>";
}

if (!file_exists($logFile)) {
    echo "<p>Log file does not exist yet. Laravel hasn't written any errors. Please try to Logout again now (to trigger the error), then refresh this page!</p>";
    exit;
}

$lines = file($logFile);
$lastLines = array_slice($lines, -100);

echo "<h4>100 Baris Log Terakhir:</h4>";
echo "<pre style='background:#f4f4f4; padding:10px; overflow-x:auto;'>";
foreach ($lastLines as $line) {
    echo htmlspecialchars($line);
}
echo "</pre>";

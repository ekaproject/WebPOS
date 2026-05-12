<?php
$cacheDir = __DIR__ . '/../bootstrap/cache/';

echo "<h3>Membersihkan Cache Laravel di Server Hosting</h3>";

$files = ['packages.php', 'services.php', 'config.php', 'routes-v7.php', 'events.php'];
$success = true;

foreach ($files as $file) {
    $path = $cacheDir . $file;
    if (file_exists($path)) {
        if (unlink($path)) {
            echo "<p style='color:green'>✅ Berhasil menghapus cache: $file</p>";
        } else {
            echo "<p style='color:red'>❌ Gagal menghapus: $file (Cek Permission)</p>";
            $success = false;
        }
    } else {
        echo "<p style='color:gray'>ℹ️ Tidak ditemukan (Aman): $file</p>";
    }
}

if ($success) {
    echo "<h3>🎉 Selesai! Semua cache telah dibersihkan.</h3>";
    echo "<p>Silakan coba kembali halaman <a href='/debug-session'>/debug-session</a> atau langsung coba Login kembali.</p>";
}

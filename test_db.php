<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=db_pos', 'root', 'qwerty');
    echo "OK\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

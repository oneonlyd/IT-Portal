<?php
// config/db.php
// File ini digunakan untuk membuat koneksi tunggal ke database MySQL menggunakan PDO.

// 1. Parameter Konfigurasi Database
$db       = 'it_portal';    // Nama database yang telah kita buat
$user     = 'root';         // User utama MySQL
$charset  = 'utf8mb4';      // Charset yang mendukung penyimpanan teks Unicode modern

// 2. Opsi Tambahan untuk PDO
$options = [
    // Mengaktifkan Error Mode sebagai Exception
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    // Mengatur fetch mode default menjadi array asosiatif
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    // Menonaktifkan emulasi prepared statement agar lebih aman dari SQL Injection
    PDO::ATTR_EMULATE_PREPARES   => false,                  
];

// Mencoba berbagai skenario koneksi secara dinamis untuk kompatibilitas multi-environment (Docker & XAMPP lokal)
$connections = [
    ['host' => 'mysql',     'pass' => 'devina123'], // Skenario 1: Docker (Default)
    ['host' => '127.0.0.1', 'pass' => 'devina123'], // Skenario 2: XAMPP lokal default
    ['host' => '127.0.0.1', 'pass' => 'Apel1289']   // Skenario 3: Host lokal dengan password khusus
];

$pdo = null;
$connected = false;
$lastError = '';

foreach ($connections as $conn) {
    try {
        $dsn = "mysql:host=" . $conn['host'] . ";dbname=$db;charset=$charset";
        $pdo = new PDO($dsn, $user, $conn['pass'], $options);
        $connected = true;
        break; // Berhasil terhubung, keluar dari loop
    } catch (\PDOException $e) {
        $lastError = $e->getMessage();
    }
}

if (!$connected) {
    // Jika seluruh skenario gagal, kembalikan respon JSON agar tidak merusak parser browser
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Koneksi database gagal setelah mencoba seluruh skenario: ' . $lastError
    ]);
    exit;
}

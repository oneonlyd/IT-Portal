<?php
// config/db.php
// File ini digunakan untuk membuat koneksi tunggal ke database MySQL menggunakan PDO.

// 1. Parameter Konfigurasi Database
$host     = 'mysql';    // Menggunakan IP local loopback (lebih cepat dibanding 'localhost' di beberapa OS)
$db       = 'it_portal';    // Nama database yang telah kita buat
$user     = 'root';         // User utama MySQL
$pass     = 'Apel1289'; // Password untuk koneksi MySQL lokal
$charset  = 'utf8mb4';      // Charset yang mendukung penyimpanan teks Unicode modern

// 2. Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// 3. Opsi Tambahan untuk PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => false,                  
];

try {
    // Coba koneksi ke host default ('mysql')
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Jika gagal, lakukan fallback otomatis ke localhost (127.0.0.1) untuk XAMPP lokal
    $host = '127.0.0.1';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $fallbackError) {
        die("Koneksi database gagal: " . $fallbackError->getMessage());
    }
}

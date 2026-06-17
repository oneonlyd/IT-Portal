<?php
// config/db.php
// File ini digunakan untuk membuat koneksi tunggal ke database MySQL menggunakan PDO.

// 1. Parameter Konfigurasi Database
$host     = '127.0.0.1';    // Menggunakan IP local loopback (lebih cepat dibanding 'localhost' di beberapa OS)
$db       = 'it_portal';    // Nama database yang telah kita buat
$user     = 'root';         // User utama MySQL
$pass     = 'Apel1289'; // PENTING: Ganti dengan password baru yang Anda atur di phpMyAdmin sebelumnya!
$charset  = 'utf8mb4';      // Charset yang mendukung penyimpanan teks Unicode modern

// 2. Data Source Name (DSN)
// DSN memberi tahu PDO driver mana yang dipakai (mysql), alamat host, nama database, dan charset-nya.
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// 3. Opsi Tambahan untuk PDO
$options = [
    // Mengaktifkan Error Mode sebagai Exception. Jika ada query yang salah, PHP akan mengeluarkan error (try-catch bisa menangkapnya).
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    // Mengatur fetch mode default menjadi array asosiatif (misal: $row['nama_app'] daripada $row[0]).
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    // Menonaktifkan emulasi prepared statement agar MySQL native yang memproses query (lebih aman dari SQL Injection).
    PDO::ATTR_EMULATE_PREPARES   => false,                  
];

try {
    // 4. Membuat Koneksi
    // Kita membuat objek koneksi baru bernama $pdo. Objek inilah yang nanti kita panggil untuk menjalankan query database.
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Baris ini bisa diaktifkan sementara untuk testing. Nanti jika sudah sukses, baris ini harus dinonaktifkan/dihapus.
    // echo "Koneksi ke database berhasil!";
} catch (\PDOException $e) {
    // Jika koneksi gagal, tangkap error-nya dan tampilkan pesan error yang ramah.
    die("Koneksi database gagal: " . $e->getMessage());
}

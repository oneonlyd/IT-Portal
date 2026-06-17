<?php
// api/dashboard.php
// Endpoint ini digunakan untuk mengambil daftar aplikasi yang boleh diakses oleh user yang sedang login.

// Mengatur header agar output data berbentuk JSON
header('Content-Type: application/json');

// Memulai session PHP
session_start();

// 1. Cek Proteksi Session: Apakah user sudah login?
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    http_response_code(401); // 401 Unauthorized
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak. Silakan login terlebih dahulu.'
    ]);
    exit;
}

// Import file koneksi database
require_once '../config/db.php';

// Ambil ID user dari session yang aktif
$userId = $_SESSION['user_id'];

try {
    // 2. Query JOIN dengan Prepared Statement
    // Kita menyeleksi kolom dari tabel 'aplikasi' (alias a) yang terhubung dengan tabel 'user_app_akses' (alias uaa)
    // berdasarkan ID user yang sedang login.
    $query = "SELECT a.id, a.nama_app, a.deskripsi, a.tipe, a.url_atau_path, a.warna_banner, a.ikon, a.kategori 
              FROM aplikasi a 
              INNER JOIN user_app_akses uaa ON a.id = uaa.app_id 
              WHERE uaa.user_id = :user_id 
              ORDER BY a.kategori ASC, a.nama_app ASC";
              
    $stmt = $pdo->prepare($query);
    
    // 3. Eksekusi query dengan binding parameter :user_id
    $stmt->execute([':user_id' => $userId]);
    
    // 4. Fetch semua baris aplikasi
    $apps = $stmt->fetchAll();

    // Kirim respon sukses beserta data aplikasi
    echo json_encode([
        'status' => 'success',
        'data' => $apps
    ]);
    exit;
} catch (\PDOException $e) {
    // Tangkap error jika database bermasalah
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengambil data dashboard: ' . $e->getMessage()
    ]);
    exit;
}

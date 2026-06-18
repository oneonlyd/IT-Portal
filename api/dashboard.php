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

// Import file koneksi database dan pengaman
require_once '../config/db.php';
require_once '../config/security.php';

// Ambil ID user dari session yang aktif
$userId = $_SESSION['user_id'];

try {
    // 2. Query JOIN dengan Prepared Statement
    // Kita menyeleksi kolom dari tabel 'aplikasi' beserta kredensial terenkripsi
    $query = "SELECT a.id, a.nama_app, a.deskripsi, a.tipe, a.url_atau_path, a.warna_banner, a.ikon, a.kategori,
                     ka.tipe_kredensial, ka.username_app, ka.password_app_encrypted
              FROM aplikasi a 
              INNER JOIN user_app_akses uaa ON a.id = uaa.app_id 
              LEFT JOIN kredensial_app ka ON a.id = ka.app_id
              WHERE uaa.user_id = :user_id 
              ORDER BY a.kategori ASC, a.nama_app ASC";
              
    $stmt = $pdo->prepare($query);
    
    // 3. Eksekusi query dengan binding parameter :user_id
    $stmt->execute([':user_id' => $userId]);
    
    // 4. Fetch semua baris aplikasi
    $apps = $stmt->fetchAll();

    // 5. Dekripsi kredensial untuk ditampilkan langsung pada kartu dashboard
    $decryptedApps = [];
    foreach ($apps as $app) {
        $usernameApp = '';
        $passwordApp = '';
        if (isset($app['tipe_kredensial'])) {
            if ($app['tipe_kredensial'] === 'sama_portal') {
                $usernameApp = $_SESSION['username'];
                $passwordApp = isset($_SESSION['portal_password']) ? $_SESSION['portal_password'] : '';
            } else {
                $usernameApp = $app['username_app'];
                $passwordApp = decrypt_password($app['password_app_encrypted']);
            }
        }
        
        $decryptedApps[] = [
            'id' => $app['id'],
            'nama_app' => $app['nama_app'],
            'deskripsi' => $app['deskripsi'],
            'tipe' => $app['tipe'],
            'url_atau_path' => $app['url_atau_path'],
            'warna_banner' => $app['warna_banner'],
            'ikon' => $app['ikon'],
            'kategori' => $app['kategori'],
            'username_app' => $usernameApp,
            'password_app' => $passwordApp
        ];
    }

    // Kirim respon sukses beserta data aplikasi terdekripsi
    echo json_encode([
        'status' => 'success',
        'data' => $decryptedApps
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

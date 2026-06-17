<?php
// api/get_credential.php
// Endpoint ini digunakan untuk mengambil kredensial aplikasi secara aman (hanya jika user berhak mengakses).

header('Content-Type: application/json');
session_start();

// 1. Cek Proteksi Session: Apakah user sudah login?
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak. Silakan login terlebih dahulu.'
    ]);
    exit;
}

require_once '../config/db.php';
require_once '../config/security.php';

// Ambil app_id dari parameter GET (contoh: api/get_credential.php?app_id=3)
$appId = isset($_GET['app_id']) ? intval($_GET['app_id']) : 0;
$userId = $_SESSION['user_id'];

if ($appId <= 0) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'ID Aplikasi tidak valid!'
    ]);
    exit;
}

try {
    // 2. CEK OTORISASI: Apakah user ini terdaftar memiliki hak akses ke aplikasi ini?
    // Mencegah user biasa memanggil kredensial aplikasi lain dengan menebak app_id
    $authQuery = "SELECT id FROM user_app_akses WHERE user_id = :user_id AND app_id = :app_id LIMIT 1";
    $authStmt = $pdo->prepare($authQuery);
    $authStmt->execute([
        ':user_id' => $userId,
        ':app_id'  => $appId
    ]);
    
    if (!$authStmt->fetch()) {
        http_response_code(403); // 403 Forbidden
        echo json_encode([
            'status' => 'error',
            'message' => 'Anda tidak memiliki hak akses untuk aplikasi ini!'
        ]);
        exit;
    }

    // 3. AMBIL KREDENSIAL APLIKASI
    $credQuery = "SELECT tipe_kredensial, username_app, password_app_encrypted, catatan 
                  FROM kredensial_app 
                  WHERE app_id = :app_id LIMIT 1";
    $credStmt = $pdo->prepare($credQuery);
    $credStmt->execute([':app_id' => $appId]);
    $cred = $credStmt->fetch();

    if (!$cred) {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Kredensial aplikasi tidak ditemukan!'
        ]);
        exit;
    }

    // 4. DEKRIPSI KATA SANDI BERDASARKAN TIPE
    $usernameApp = '';
    $passwordApp = '';

    if ($cred['tipe_kredensial'] === 'sama_portal') {
        // Jika bertipe 'sama_portal', ambil username & password dari session aktif user saat ini
        $usernameApp = $_SESSION['username'];
        $passwordApp = isset($_SESSION['portal_password']) ? $_SESSION['portal_password'] : '';
    } else {
        // Jika bertipe 'custom', baca dari db dan dekripsi password-nya
        $usernameApp = $cred['username_app'];
        $passwordApp = decrypt_password($cred['password_app_encrypted']);
    }

    // Mengirim hasil ke frontend
    echo json_encode([
        'status' => 'success',
        'data' => [
            'tipe_kredensial' => $cred['tipe_kredensial'],
            'username_app'    => $usernameApp,
            'password_app'    => $passwordApp,
            'catatan'         => $cred['catatan']
        ]
    ]);
    exit;
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan database: ' . $e->getMessage()
    ]);
    exit;
}

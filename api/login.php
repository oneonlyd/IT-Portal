<?php
// api/login.php
// Endpoint ini digunakan untuk memproses login user dan menyimpan session jika berhasil.

// Mengatur header agar output data berbentuk JSON
header('Content-Type: application/json');

// Memulai session PHP
session_start();

// Import file koneksi database
require_once '../config/db.php';

// Memastikan method request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed. Gunakan POST.'
    ]);
    exit;
}

// Membaca data input (mendukung JSON fetch maupun Form submit biasa)
$input = json_decode(file_get_contents('php://input'), true);

$username = isset($input['username']) ? trim($input['username']) : (isset($_POST['username']) ? trim($_POST['username']) : '');
$password = isset($input['password']) ? trim($input['password']) : (isset($_POST['password']) ? trim($_POST['password']) : '');

// Validasi jika input kosong
if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Username dan password wajib diisi!'
    ]);
    exit;
}

try {
    // 1. Prepared Statement: Mencari user berdasarkan username
    // Kita menggunakan placeholder ':username' (bukan variabel langsung) untuk mencegah SQL Injection
    $query = "SELECT id, username, password_hash, nama_lengkap, role FROM users WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($query);
    
    // 2. Mengeksekusi query dengan mengikat (binding) variabel $username ke placeholder ':username'
    $stmt->execute([':username' => $username]);
    
    // 3. Mengambil hasil query (fetch)
    $user = $stmt->fetch();

    // 4. Verifikasi password menggunakan password_verify()
    // password_verify() membandingkan password teks biasa ($password) dengan hash ($user['password_hash'])
    if ($user && password_verify($password, $user['password_hash'])) {
        // Jika login sukses, buat session baru
        session_regenerate_id(true); // Langkah keamanan untuk mencegah Session Fixation
        
        // Simpan data user ke Session Global PHP
        $_SESSION['user_id']      = $user['id'];
        $_SESSION['username']     = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role']         = $user['role'];
        $_SESSION['is_logged_in'] = true;
        $_SESSION['portal_password'] = $password; // Simpan password polos sementara untuk kebutuhan autofill 'sama_portal'

        // Kirim respon sukses
        echo json_encode([
            'status' => 'success',
            'message' => 'Login berhasil! Selamat datang, ' . $user['nama_lengkap'],
            'data' => [
                'nama_lengkap' => $user['nama_lengkap'],
                'role' => $user['role']
            ]
        ]);
        exit;
    } else {
        // Jika username tidak ditemukan atau password salah, kirim pesan error yang samar (untuk keamanan)
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'Username atau password salah!'
        ]);
        exit;
    }
} catch (\PDOException $e) {
    // Tangkap error jika terjadi kendala pada database
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
    ]);
    exit;
}

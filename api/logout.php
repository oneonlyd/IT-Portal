<?php
// api/logout.php
// File ini digunakan untuk menghancurkan session user dan mengalihkan halaman (redirect) kembali ke login.

// Memulai session PHP agar bisa membaca session yang sedang aktif
session_start();

// 1. Hapus semua data di dalam array $_SESSION
$_SESSION = [];

// 2. Hapus cookie session di browser user (Sangat direkomendasikan untuk keamanan)
// Ini memberi tahu browser untuk menghapus cookie session ID (PHPSESSID) yang tersimpan
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hancurkan session yang tersimpan di server
session_destroy();

// 4. Redirect (alihkan) kembali ke halaman login.html di folder utama
header("Location: ../login.html");
exit;

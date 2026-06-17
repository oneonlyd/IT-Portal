<?php
// admin/admin_app.php
// Endpoint ini digunakan oleh admin untuk mengelola daftar aplikasi (CRUD).

// Mengatur header agar output data berbentuk JSON
header('Content-Type: application/json');

// Memulai session PHP
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

// 2. Proteksi Role: Hanya 'admin' atau 'superadmin' yang boleh mengakses fitur manajemen
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') {
    http_response_code(403); // 403 Forbidden
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak. Hanya Admin yang dapat mengakses fitur ini.'
    ]);
    exit;
}

// Import koneksi database & pengaman password
require_once '../config/db.php';
require_once '../config/security.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    // ==========================================
    // GET: Membaca daftar aplikasi (READ)
    // ==========================================
    if ($method === 'GET') {
        // Ambil semua data aplikasi digabung dengan kredensialnya
        $query = "SELECT a.*, k.tipe_kredensial, k.username_app, k.password_app_encrypted, k.catatan 
                  FROM aplikasi a 
                  LEFT JOIN kredensial_app k ON a.id = k.app_id 
                  ORDER BY a.id DESC";
        $stmt = $pdo->query($query);
        $apps = $stmt->fetchAll();

        // Dekripsi password untuk dikirim ke panel edit admin
        foreach ($apps as &$app) {
            if ($app['tipe_kredensial'] === 'custom' && !empty($app['password_app_encrypted'])) {
                $app['password_app'] = decrypt_password($app['password_app_encrypted']);
            } else {
                $app['password_app'] = '';
            }
            // Hapus data enkripsi mentah dari output agar lebih bersih
            unset($app['password_app_encrypted']);
        }

        echo json_encode([
            'status' => 'success',
            'data' => $apps
        ]);
        exit;
    }

    // ==========================================
    // POST: Create, Update, Delete
    // ==========================================
    if ($method === 'POST') {
        // Membaca data input (JSON maupun POST form biasa)
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $action = isset($input['action']) ? $input['action'] : '';

        // ------------------------------------------
        // A. CREATE (Tambah Aplikasi Baru)
        // ------------------------------------------
        if ($action === 'create') {
            $namaApp      = isset($input['nama_app']) ? trim($input['nama_app']) : '';
            $deskripsi    = isset($input['deskripsi']) ? trim($input['deskripsi']) : '';
            $tipe         = isset($input['tipe']) ? trim($input['tipe']) : ''; // web / desktop
            $urlAtauPath  = isset($input['url_atau_path']) ? trim($input['url_atau_path']) : '';
            $warnaBanner  = isset($input['warna_banner']) ? trim($input['warna_banner']) : '#3b82f6';
            $ikon         = isset($input['ikon']) ? trim($input['ikon']) : 'default-icon';
            $kategori     = isset($input['kategori']) ? trim($input['kategori']) : 'Umum';

            $tipeKred     = isset($input['tipe_kredensial']) ? trim($input['tipe_kredensial']) : 'custom';
            $usernameApp  = isset($input['username_app']) ? trim($input['username_app']) : null;
            $passwordApp  = isset($input['password_app']) ? trim($input['password_app']) : null;
            $catatan      = isset($input['catatan']) ? trim($input['catatan']) : null;

            if (empty($namaApp) || empty($tipe) || empty($urlAtauPath)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Nama, tipe, dan URL/Path wajib diisi!']);
                exit;
            }

            // Memulai Transaksi database (untuk keamanan jika salah satu query gagal)
            $pdo->beginTransaction();

            // 1. Simpan aplikasi baru
            $queryApp = "INSERT INTO aplikasi (nama_app, deskripsi, tipe, url_atau_path, warna_banner, ikon, kategori) 
                         VALUES (:nama_app, :deskripsi, :tipe, :url_atau_path, :warna_banner, :ikon, :kategori)";
            $stmtApp = $pdo->prepare($queryApp);
            $stmtApp->execute([
                ':nama_app'      => $namaApp,
                ':deskripsi'    => $deskripsi,
                ':tipe'         => $tipe,
                ':url_atau_path' => $urlAtauPath,
                ':warna_banner'  => $warnaBanner,
                ':ikon'         => $ikon,
                ':kategori'     => $kategori
            ]);
            $appId = $pdo->lastInsertId();

            // 2. Enkripsi password kustom sebelum masuk ke DB
            $passwordEncrypted = null;
            if ($tipeKred === 'custom' && !empty($passwordApp)) {
                $passwordEncrypted = encrypt_password($passwordApp);
            }

            // 3. Simpan kredensial aplikasi
            $queryCred = "INSERT INTO kredensial_app (app_id, tipe_kredensial, username_app, password_app_encrypted, catatan) 
                          VALUES (:app_id, :tipe_kredensial, :username_app, :password_app_encrypted, :catatan)";
            $stmtCred = $pdo->prepare($queryCred);
            $stmtCred->execute([
                ':app_id'                 => $appId,
                ':tipe_kredensial'        => $tipeKred,
                ':username_app'           => ($tipeKred === 'sama_portal') ? null : $usernameApp,
                ':password_app_encrypted' => ($tipeKred === 'sama_portal') ? null : $passwordEncrypted,
                ':catatan'                => $catatan
            ]);

            $pdo->commit(); // Commit semua query jika berhasil
            echo json_encode(['status' => 'success', 'message' => 'Aplikasi berhasil ditambahkan!']);
            exit;
        }

        // ------------------------------------------
        // B. UPDATE (Ubah Aplikasi)
        // ------------------------------------------
        elseif ($action === 'update') {
            $id = isset($input['id']) ? intval($input['id']) : 0;
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'ID Aplikasi tidak valid!']);
                exit;
            }

            $namaApp      = isset($input['nama_app']) ? trim($input['nama_app']) : '';
            $deskripsi    = isset($input['deskripsi']) ? trim($input['deskripsi']) : '';
            $tipe         = isset($input['tipe']) ? trim($input['tipe']) : '';
            $urlAtauPath  = isset($input['url_atau_path']) ? trim($input['url_atau_path']) : '';
            $warnaBanner  = isset($input['warna_banner']) ? trim($input['warna_banner']) : '#3b82f6';
            $ikon         = isset($input['ikon']) ? trim($input['ikon']) : 'default-icon';
            $kategori     = isset($input['kategori']) ? trim($input['kategori']) : 'Umum';

            $tipeKred     = isset($input['tipe_kredensial']) ? trim($input['tipe_kredensial']) : 'custom';
            $usernameApp  = isset($input['username_app']) ? trim($input['username_app']) : null;
            $passwordApp  = isset($input['password_app']) ? trim($input['password_app']) : '';
            $catatan      = isset($input['catatan']) ? trim($input['catatan']) : null;

            if (empty($namaApp) || empty($tipe) || empty($urlAtauPath)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Nama, tipe, dan URL/Path wajib diisi!']);
                exit;
            }

            $pdo->beginTransaction();

            // 1. Update tabel aplikasi
            $queryApp = "UPDATE aplikasi SET nama_app = :nama_app, deskripsi = :deskripsi, tipe = :tipe, 
                         url_atau_path = :url_atau_path, warna_banner = :warna_banner, ikon = :ikon, kategori = :kategori 
                         WHERE id = :id";
            $stmtApp = $pdo->prepare($queryApp);
            $stmtApp->execute([
                ':nama_app'      => $namaApp,
                ':deskripsi'    => $deskripsi,
                ':tipe'         => $tipe,
                ':url_atau_path' => $urlAtauPath,
                ':warna_banner'  => $warnaBanner,
                ':ikon'         => $ikon,
                ':kategori'     => $kategori,
                ':id'           => $id
            ]);

            // 2. Ambil data kredensial lama untuk mengecek password
            $checkCred = $pdo->prepare("SELECT password_app_encrypted FROM kredensial_app WHERE app_id = :app_id");
            $checkCred->execute([':app_id' => $id]);
            $existingCred = $checkCred->fetch();

            // 3. Penanganan enkripsi password baru vs password lama
            $passwordEncrypted = null;
            if ($tipeKred === 'custom') {
                if (!empty($passwordApp)) {
                    // Jika password baru diinputkan, encrypt
                    $passwordEncrypted = encrypt_password($passwordApp);
                } elseif ($existingCred) {
                    // Jika password baru dikosongkan (tidak diubah), tetap gunakan password enkripsi yang lama
                    $passwordEncrypted = $existingCred['password_app_encrypted'];
                }
            }

            // 4. Update atau Insert kredensial
            if ($existingCred) {
                $queryCred = "UPDATE kredensial_app SET tipe_kredensial = :tipe_kredensial, username_app = :username_app, 
                              password_app_encrypted = :password_app_encrypted, catatan = :catatan WHERE app_id = :app_id";
            } else {
                $queryCred = "INSERT INTO kredensial_app (app_id, tipe_kredensial, username_app, password_app_encrypted, catatan) 
                              VALUES (:app_id, :tipe_kredensial, :username_app, :password_app_encrypted, :catatan)";
            }
            $stmtCred = $pdo->prepare($queryCred);
            $stmtCred->execute([
                ':app_id'                 => $id,
                ':tipe_kredensial'        => $tipeKred,
                ':username_app'           => ($tipeKred === 'sama_portal') ? null : $usernameApp,
                ':password_app_encrypted' => ($tipeKred === 'sama_portal') ? null : $passwordEncrypted,
                ':catatan'                => $catatan
            ]);

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Aplikasi berhasil diupdate!']);
            exit;
        }

        // ------------------------------------------
        // C. DELETE (Hapus Aplikasi)
        // ------------------------------------------
        elseif ($action === 'delete') {
            $id = isset($input['id']) ? intval($input['id']) : 0;
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'ID Aplikasi tidak valid!']);
                exit;
            }

            // Hapus data aplikasi. Kredensial & hak akses terhapus otomatis karena relasi ON DELETE CASCADE di database.
            $query = "DELETE FROM aplikasi WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id' => $id]);

            echo json_encode(['status' => 'success', 'message' => 'Aplikasi berhasil dihapus!']);
            exit;
        }

        // Jika action tidak valid
        else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Action tidak dikenali. Gunakan create, update, atau delete.']);
            exit;
        }
    }
} catch (\PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack(); // Rollback transaksi agar DB tidak rusak setengah jalan jika terjadi error
    }
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
    ]);
    exit;
}

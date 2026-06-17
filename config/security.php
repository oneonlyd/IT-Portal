<?php
// config/security.php
// File helper untuk menangani enkripsi dan dekripsi password aplikasi (kredensial kustom).

// Kunci rahasia untuk enkripsi (Gunakan kunci yang unik dan aman)
define('ENCRYPTION_KEY', 'PortalITSecretKey2026!#$@123'); 
define('ENCRYPTION_METHOD', 'aes-256-cbc');

/**
 * Mengenkripsi teks biasa menggunakan AES-256-CBC
 * @param string $password Kata sandi teks biasa
 * @return string Hasil enkripsi dalam format base64
 */
function encrypt_password($password) {
    $key = hash('sha256', ENCRYPTION_KEY);
    $iv_length = openssl_cipher_iv_length(ENCRYPTION_METHOD);
    // Membuat IV (Initialization Vector) acak agar hasil enkripsi selalu berbeda walaupun teksnya sama
    $iv = openssl_random_pseudo_bytes($iv_length);
    
    $encrypted = openssl_encrypt($password, ENCRYPTION_METHOD, $key, 0, $iv);
    
    // Gabungkan teks terenkripsi dengan IV menggunakan pemisah '::' lalu encode ke base64
    return base64_encode($encrypted . '::' . $iv);
}

/**
 * Mendekripsi teks terenkripsi kembali ke teks biasa
 * @param string $encrypted_string Teks terenkripsi (base64)
 * @return string|false Kata sandi asli atau false jika gagal
 */
function decrypt_password($encrypted_string) {
    $key = hash('sha256', ENCRYPTION_KEY);
    $decoded = base64_decode($encrypted_string);
    
    if (strpos($decoded, '::') !== false) {
        list($encrypted_data, $iv) = explode('::', $decoded, 2);
        $iv_length = openssl_cipher_iv_length(ENCRYPTION_METHOD);
        
        // Validasi panjang IV sebelum didekripsi
        if (strlen($iv) === $iv_length) {
            return openssl_decrypt($encrypted_data, ENCRYPTION_METHOD, $key, 0, $iv);
        }
    }
    return false;
}

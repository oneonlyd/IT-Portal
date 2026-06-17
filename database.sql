-- Membuat Database jika belum ada
CREATE DATABASE IF NOT EXISTS `it_portal` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `it_portal`;

-- ========================================================
-- 1. TABEL USERS (Untuk data login anggota tim IT)
-- ========================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `role` ENUM('superadmin', 'admin', 'user') NOT NULL DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- 2. TABEL APLIKASI (Untuk daftar aplikasi web & desktop)
-- ========================================================
CREATE TABLE IF NOT EXISTS `aplikasi` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_app` VARCHAR(100) NOT NULL,
    `deskripsi` TEXT,
    `tipe` ENUM('web', 'desktop') NOT NULL,
    `url_atau_path` TEXT NOT NULL,
    `warna_banner` VARCHAR(7) DEFAULT '#3b82f6', -- Menyimpan kode warna HEX (misal: #3b82f6)
    `ikon` VARCHAR(50) DEFAULT 'default-icon',     -- Nama class ikon / file ikon
    `kategori` VARCHAR(50) DEFAULT 'Umum',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- 3. TABEL KREDENSIAL_APP (Kredensial login aplikasi)
-- ========================================================
CREATE TABLE IF NOT EXISTS `kredensial_app` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT NOT NULL,
    `tipe_kredensial` ENUM('sama_portal', 'custom') NOT NULL DEFAULT 'custom',
    `username_app` VARCHAR(100) DEFAULT NULL,
    `password_app_encrypted` TEXT DEFAULT NULL, -- Password dienkripsi dengan openssl_encrypt()
    `catatan` TEXT DEFAULT NULL,
    FOREIGN KEY (`app_id`) REFERENCES `aplikasi`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- 4. TABEL USER_APP_AKSES (Hak akses user ke aplikasi tertentu)
-- ========================================================
CREATE TABLE IF NOT EXISTS `user_app_akses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `app_id` INT NOT NULL,
    UNIQUE KEY `user_app_unique` (`user_id`, `app_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`app_id`) REFERENCES `aplikasi`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

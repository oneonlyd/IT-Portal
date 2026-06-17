USE `it_portal`;

-- ========================================================
-- 1. INSERT DATA AKUN DEFAULT (Tabel: users)
-- ========================================================
-- Catatan Keamanan: Password asli di-hash menggunakan password_hash() PHP (BCRYPT)
-- - Devina Maharani (devina) -> Password asli: Apel1289
-- - Tim IT (admin) -> Password asli: Lenovo001@
INSERT INTO `users` (`username`, `password_hash`, `nama_lengkap`, `role`) VALUES
('devina', '$2y$10$/a8NZv5/9Z6WCe9McvPDwelgqwgO5HRcJ3I1O8YKpBDDin8OiL/ea', 'Devina Maharani', 'superadmin'),
('admin', '$2y$10$jW3ZBEf/keGxjQ7giY7CveabR6S.NA0mtpUwci1V1z/n8Jhtb4h76', 'Tim IT', 'admin');

-- ========================================================
-- 2. INSERT DATA APLIKASI DUMMY (Tabel: aplikasi)
-- ========================================================
INSERT INTO `aplikasi` (`nama_app`, `deskripsi`, `tipe`, `url_atau_path`, `warna_banner`, `ikon`, `kategori`) VALUES
('Proxmox VE', 'Virtual Environment Server Management', 'web', 'https://192.168.1.100:8006', '#f97316', 'cpu', 'Server'),
('Zabbix Monitoring', 'Dashboard Monitoring Infrastruktur Jaringan', 'web', 'http://192.168.1.105/zabbix', '#ef4444', 'activity', 'Monitoring'),
('Putty SSH', 'Desktop SSH Client untuk Remote Server', 'desktop', 'C:\\Program Files\\PuTTY\\putty.exe', '#3b82f6', 'terminal', 'Network Tools'),
('Active Directory Admin', 'Web Portal Management User Active Directory', 'web', 'http://ad-server.local/admin', '#10b981', 'users', 'Database');

-- ========================================================
-- 3. INSERT KREDENSIAL APLIKASI DUMMY (Tabel: kredensial_app)
-- ========================================================
-- password_app_encrypted di sini contoh data dummy (nanti akan dienkripsi via backend)
INSERT INTO `kredensial_app` (`app_id`, `tipe_kredensial`, `username_app`, `password_app_encrypted`, `catatan`) VALUES
(1, 'custom', 'root', 'RVlMb2tUdWlzU3U5aUI0UjJjV1pxZz09OjpLujhcUVFbsZe8jn0Qoyw1', 'Kredensial root server utama'),
(2, 'custom', 'Admin', 'Y2xPTzFvRWQ1dk4zZVdrbCt5RkRNdz09OjoF9+bJrg34k1KkNhlB9Mh+', 'Kredensial monitoring administrator'),
(3, 'sama_portal', NULL, NULL, 'Gunakan kredensial portal IT untuk login'),
(4, 'custom', 'ad_admin', 'bHFsYnk2dmM0UnhUSFl5VG9lNzl5QT09OjqtcIToPtOJQW3jjqjbuoNH', 'Kredensial delegasi admin AD');

-- ========================================================
-- 4. INSERT HAK AKSES USER-APLIKASI (Tabel: user_app_akses)
-- ========================================================
-- Memberikan akses ke user 'devina' (ID: 1) dan 'admin' (ID: 2) ke semua aplikasi dummy
INSERT INTO `user_app_akses` (`user_id`, `app_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4),
(2, 1), (2, 2), (2, 3), (2, 4);

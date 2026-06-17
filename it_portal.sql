-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2026 at 03:25 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `it_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `aplikasi`
--

CREATE TABLE `aplikasi` (
  `id` int(11) NOT NULL,
  `nama_app` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tipe` enum('web','desktop') NOT NULL,
  `url_atau_path` text NOT NULL,
  `warna_banner` varchar(7) DEFAULT '#3b82f6',
  `ikon` varchar(50) DEFAULT 'default-icon',
  `kategori` varchar(50) DEFAULT 'Umum',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aplikasi`
--

INSERT INTO `aplikasi` (`id`, `nama_app`, `deskripsi`, `tipe`, `url_atau_path`, `warna_banner`, `ikon`, `kategori`, `created_at`) VALUES
(1, 'Proxmox VE', 'Virtual Environment Server Management', 'web', 'https://192.168.1.100:8006', '#f97316', 'cpu', 'Server', '2026-06-16 16:50:31'),
(2, 'Zabbix Monitoring', 'Dashboard Monitoring Infrastruktur Jaringan', 'web', 'http://192.168.1.105/zabbix', '#ef4444', 'activity', 'Monitoring', '2026-06-16 16:50:31'),
(3, 'Putty SSH', 'Desktop SSH Client untuk Remote Server', 'desktop', 'C:\\Program Files\\PuTTY\\putty.exe', '#3b82f6', 'terminal', 'Network Tools', '2026-06-16 16:50:31'),
(4, 'Active Directory Admin', 'Web Portal Management User Active Directory', 'web', 'http://ad-server.local/admin', '#10b981', 'users', 'Database', '2026-06-16 16:50:31'),
(5, 'Pfsense Firewall Router', 'Portal Management Router Utama', 'web', 'https://192.168.1.1', '#ef4444', 'shield', 'Network Tools', '2026-06-16 23:21:26');

-- --------------------------------------------------------

--
-- Table structure for table `kredensial_app`
--

CREATE TABLE `kredensial_app` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `tipe_kredensial` enum('sama_portal','custom') NOT NULL DEFAULT 'custom',
  `username_app` varchar(100) DEFAULT NULL,
  `password_app_encrypted` text DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kredensial_app`
--

INSERT INTO `kredensial_app` (`id`, `app_id`, `tipe_kredensial`, `username_app`, `password_app_encrypted`, `catatan`) VALUES
(1, 1, 'custom', 'root', 'RVlMb2tUdWlzU3U5aUI0UjJjV1pxZz09OjpLujhcUVFbsZe8jn0Qoyw1', 'Kredensial root server utama'),
(2, 2, 'custom', 'Admin', 'Y2xPTzFvRWQ1dk4zZVdrbCt5RkRNdz09OjoF9+bJrg34k1KkNhlB9Mh+', 'Kredensial monitoring administrator'),
(3, 3, 'sama_portal', NULL, NULL, 'Gunakan kredensial portal IT untuk login'),
(4, 4, 'custom', 'ad_admin', 'bHFsYnk2dmM0UnhUSFl5VG9lNzl5QT09OjqtcIToPtOJQW3jjqjbuoNH', 'Kredensial delegasi admin AD'),
(5, 5, 'custom', 'admin', 'djdWc1BNdGFWY3A4N3pObFdhWWFIcDRzZkVOM2ZGdCtXczRkaDlxTWlVND06OgSBPH5QAFyi/bleV1J04k4=', 'Router internal IT');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('superadmin','admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `nama_lengkap`, `role`, `created_at`) VALUES
(1, 'devina', '$2y$10$/a8NZv5/9Z6WCe9McvPDwelgqwgO5HRcJ3I1O8YKpBDDin8OiL/ea', 'Devina Maharani', 'superadmin', '2026-06-16 16:50:31'),
(2, 'admin', '$2y$10$jW3ZBEf/keGxjQ7giY7CveabR6S.NA0mtpUwci1V1z/n8Jhtb4h76', 'Tim IT', 'admin', '2026-06-16 16:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `user_app_akses`
--

CREATE TABLE `user_app_akses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_app_akses`
--

INSERT INTO `user_app_akses` (`id`, `user_id`, `app_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 1),
(6, 2, 2),
(7, 2, 3),
(8, 2, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aplikasi`
--
ALTER TABLE `aplikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kredensial_app`
--
ALTER TABLE `kredensial_app`
  ADD PRIMARY KEY (`id`),
  ADD KEY `app_id` (`app_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_app_akses`
--
ALTER TABLE `user_app_akses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_app_unique` (`user_id`,`app_id`),
  ADD KEY `app_id` (`app_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aplikasi`
--
ALTER TABLE `aplikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kredensial_app`
--
ALTER TABLE `kredensial_app`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_app_akses`
--
ALTER TABLE `user_app_akses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kredensial_app`
--
ALTER TABLE `kredensial_app`
  ADD CONSTRAINT `kredensial_app_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `aplikasi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_app_akses`
--
ALTER TABLE `user_app_akses`
  ADD CONSTRAINT `user_app_akses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_app_akses_ibfk_2` FOREIGN KEY (`app_id`) REFERENCES `aplikasi` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

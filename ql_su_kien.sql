-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 04, 2026 at 04:24 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ql_su_kien`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `description`, `model_type`, `model_id`, `ip_address`, `user_agent`, `properties`, `created_at`) VALUES
(1, '64131942', 'Dương Phú Quảng', 'login', 'Đăng nhập thành công — Dương Phú Quảng (quang.dp.64cntt@ntu.edu.vn)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-08 11:59:30'),
(2, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-08 12:00:14'),
(3, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-08 12:00:42'),
(4, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-08 12:01:31'),
(5, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-08 15:52:11'),
(6, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 08:08:48'),
(7, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 08:12:22'),
(8, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 08:13:13'),
(9, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 08:14:46'),
(10, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 08:15:39'),
(11, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 08:20:08'),
(12, '64131940', 'Quản trị viên Demo', 'update', 'Test gửi email SMTP tới duongquangpy11@gmail.com — thành công', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 08:20:35'),
(13, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 08:21:02'),
(14, NULL, 'Hệ thống', 'update', 'Cập nhật User: 64131942', 'App\\Models\\User', '64131942', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"mat_khau\": \"$2y$12$Mc4Y.L..j/vPavu3kUAdTeDkQ7WqTbbe/0sZYjB1JjJql67/n.tg6\"}, \"old\": {\"mat_khau\": \"$2y$12$zlnV6QkwTFX5YdqYu6NUZuHi/tSt6DdM.YtVd4Y.CqdNPZYDoixJO\"}}', '2026-04-11 08:21:44'),
(15, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 08:21:55'),
(16, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131942', 'App\\Models\\User', '64131942', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"trang_thai\": \"bi_khoa\"}, \"old\": {\"trang_thai\": \"hoat_dong\"}}', '2026-04-11 09:34:58'),
(17, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131942', 'App\\Models\\User', '64131942', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"trang_thai\": \"hoat_dong\"}, \"old\": {\"trang_thai\": \"bi_khoa\"}}', '2026-04-11 09:35:01'),
(18, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới User: 64131949', 'App\\Models\\User', '64131949', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"attributes\": {\"email\": \"phuquangpy11@gmail.com\", \"ho_ten\": \"Trà Phương Duyên\", \"vai_tro\": \"sinh_vien\", \"mat_khau\": \"$2y$12$SNjbDwElt1xP9wFiHHhpx.ggPainIQYqlx4g6FNDcNVQkn7aa.zTe\", \"created_at\": \"2026-04-11 16:35:42\", \"trang_thai\": \"hoat_dong\", \"updated_at\": \"2026-04-11 16:35:42\", \"ma_sinh_vien\": \"64131949\", \"so_dien_thoai\": \"0337041004\"}}', '2026-04-11 09:35:42'),
(19, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 09:36:22'),
(20, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 09:38:10'),
(21, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131949', 'App\\Models\\User', '64131949', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"trang_thai\": \"bi_khoa\"}, \"old\": {\"trang_thai\": \"hoat_dong\"}}', '2026-04-11 09:38:18'),
(22, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131949', 'App\\Models\\User', '64131949', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"trang_thai\": \"hoat_dong\"}, \"old\": {\"trang_thai\": \"bi_khoa\"}}', '2026-04-11 09:38:20'),
(23, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131949', 'App\\Models\\User', '64131949', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"trang_thai\": \"bi_khoa\"}, \"old\": {\"trang_thai\": \"hoat_dong\"}}', '2026-04-11 09:38:56'),
(24, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131949', 'App\\Models\\User', '64131949', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"trang_thai\": \"hoat_dong\"}, \"old\": {\"trang_thai\": \"bi_khoa\"}}', '2026-04-11 09:39:02'),
(25, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131949', 'App\\Models\\User', '64131949', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"trang_thai\": \"bi_khoa\"}, \"old\": {\"trang_thai\": \"hoat_dong\"}}', '2026-04-11 09:41:19'),
(26, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131949', 'App\\Models\\User', '64131949', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"trang_thai\": \"hoat_dong\"}, \"old\": {\"trang_thai\": \"bi_khoa\"}}', '2026-04-11 09:41:21'),
(27, '64131940', 'Quản trị viên Demo', 'delete', 'Xóa User: 64131949', 'App\\Models\\User', '64131949', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"attributes\": {\"lop\": null, \"email\": \"phuquangpy11@gmail.com\", \"ho_ten\": \"Trà Phương Duyên\", \"vai_tro\": \"sinh_vien\", \"mat_khau\": \"$2y$12$SNjbDwElt1xP9wFiHHhpx.ggPainIQYqlx4g6FNDcNVQkn7aa.zTe\", \"created_at\": \"2026-04-11 16:35:42\", \"deleted_at\": \"2026-04-11 16:42:23\", \"trang_thai\": \"hoat_dong\", \"updated_at\": \"2026-04-11 16:42:23\", \"ma_sinh_vien\": \"64131949\", \"duong_dan_anh\": null, \"so_dien_thoai\": \"0337041004\", \"email_verified_at\": null}}', '2026-04-11 09:42:23'),
(28, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 09:42:50'),
(29, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 09:42:59'),
(30, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới User: 64131951', 'App\\Models\\User', '64131951', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"attributes\": {\"email\": \"duongquangpy11@gmail.com\", \"ho_ten\": \"Trà Phương Duyên\", \"vai_tro\": \"sinh_vien\", \"mat_khau\": \"$2y$12$/D8IhbBT78F2nBcEAnqs.OpqsIn0VFgMCt2OzXPzuk6rnbJxNsQPu\", \"created_at\": \"2026-04-11 16:44:01\", \"trang_thai\": \"hoat_dong\", \"updated_at\": \"2026-04-11 16:44:01\", \"ma_sinh_vien\": \"64131951\", \"so_dien_thoai\": \"0337041004\"}}', '2026-04-11 09:44:01'),
(31, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 09:44:07'),
(32, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 09:44:47'),
(33, '64131940', 'Quản trị viên Demo', 'update', 'Test gửi email SMTP tới duongquangpy11@gmail.com — thành công', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 09:45:14'),
(34, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 09:47:57'),
(35, NULL, 'Hệ thống', 'update', 'Cập nhật User: 64131951', 'App\\Models\\User', '64131951', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"mat_khau\": \"$2y$12$BwFhvzGCO672CC4SNCOVuOaCX8lipSBf65t5RrwEywg7X6mH5ym1G\"}, \"old\": {\"mat_khau\": \"$2y$12$/D8IhbBT78F2nBcEAnqs.OpqsIn0VFgMCt2OzXPzuk6rnbJxNsQPu\"}}', '2026-04-11 09:49:53'),
(36, NULL, 'Hệ thống', 'update', 'Cập nhật User: 64131951', 'App\\Models\\User', '64131951', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"email_verified_at\": \"2026-04-11 16:50:15\"}, \"old\": {\"email_verified_at\": null}}', '2026-04-11 09:50:15'),
(37, '64131951', 'Trà Phương Duyên', 'login', 'Đăng nhập thành công — Trà Phương Duyên (duongquangpy11@gmail.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 09:50:19'),
(38, '64131951', 'Trà Phương Duyên', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 09:50:23'),
(39, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 09:50:27'),
(40, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131951', 'App\\Models\\User', '64131951', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"trang_thai\": \"bi_khoa\"}, \"old\": {\"trang_thai\": \"hoat_dong\"}}', '2026-04-11 09:51:46'),
(41, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131951', 'App\\Models\\User', '64131951', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"trang_thai\": \"hoat_dong\"}, \"old\": {\"trang_thai\": \"bi_khoa\"}}', '2026-04-11 09:51:50'),
(42, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới BauCu: 1', 'App\\Models\\BauCu', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"attributes\": {\"mo_ta\": null, \"tieu_de\": \"Bầu cử BCH Đoàn Khoa CNTT\", \"ma_bau_cu\": 1, \"created_at\": \"2026-04-11 16:59:46\", \"updated_at\": \"2026-04-11 16:59:46\", \"ma_nguoi_tao\": \"64131940\", \"so_chon_toi_da\": \"1\", \"so_chon_toi_thieu\": \"1\", \"thoi_gian_bat_dau\": \"2026-04-11 16:58:00\", \"thoi_gian_ket_thuc\": \"2026-04-12 16:58:00\"}}', '2026-04-11 09:59:46'),
(43, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 1', 'App\\Models\\BauCu', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"hien_thi\": true}, \"old\": {\"hien_thi\": false}}', '2026-04-11 10:00:24'),
(44, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 1', 'App\\Models\\BauCu', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"hien_thi_ket_qua\": true}, \"old\": {\"hien_thi_ket_qua\": false}}', '2026-04-11 10:00:25'),
(45, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 1', 'App\\Models\\BauCu', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"trang_thai\": \"dang_dien_ra\"}, \"old\": {\"trang_thai\": \"nhap\"}}', '2026-04-11 10:00:53'),
(46, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 10:01:17'),
(47, '64131951', 'Trà Phương Duyên', 'login', 'Đăng nhập thành công — Trà Phương Duyên (duongquangpy11@gmail.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 10:01:21'),
(48, '64131951', 'Trà Phương Duyên', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 10:01:52'),
(49, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-11 10:01:57'),
(50, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-13 12:42:28'),
(51, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-13 12:42:41'),
(52, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-13 12:42:44'),
(53, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-13 12:42:51'),
(54, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-13 12:43:06'),
(55, NULL, 'Hệ thống', 'create', 'Tạo mới User: 64131947', 'App\\Models\\User', '64131947', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"attributes\": {\"lop\": \"64.CNTT-1\", \"email\": \"a@gmaill.com\", \"ho_ten\": \"Dương Phú Quảng\", \"vai_tro\": \"sinh_vien\", \"mat_khau\": \"$2y$12$z3CokP5CfVf9vHb/zgqnhOys1RXqQqu.kThbb3Njk2AKVHh0HpI5W\", \"created_at\": \"2026-04-13 19:45:02\", \"trang_thai\": \"hoat_dong\", \"updated_at\": \"2026-04-13 19:45:02\", \"ma_sinh_vien\": \"64131947\"}}', '2026-04-13 12:45:02'),
(56, NULL, 'Hệ thống', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-13 12:46:11'),
(57, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-13 12:47:45'),
(58, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới SuKien: 2', 'App\\Models\\SuKien', '2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"attributes\": {\"bo_cuc\": \"[\\\"banner\\\",\\\"header\\\",\\\"info\\\",\\\"description\\\",\\\"gallery\\\",\\\"info\\\",\\\"info\\\",\\\"header\\\",\\\"description\\\",\\\"banner\\\"]\", \"dia_diem\": \"Hội trường 4\", \"diem_cong\": \"5\", \"created_at\": \"2026-04-13 19:56:02\", \"ma_su_kien\": 2, \"trang_thai\": \"sap_to_chuc\", \"updated_at\": \"2026-04-13 19:56:02\", \"anh_su_kien\": null, \"ten_su_kien\": \"WorkShop\", \"ma_nguoi_tao\": \"64131940\", \"mo_ta_chi_tiet\": \"<p>Vì đây là hoạt động chính trị rất quan trọng, các bạn cần triển khai gấp thông báo triệu tập bổ sung này đến lớp, và đôn đốc sinh viên tham dự đầy đủ nhé. Ban Cán sự theo dõi link đăng ký tham gia để nắm bắt sinh viên tham dự buổi tiếp xúc và cộng điểm rèn luyện cho các bạn tham gia.</p><p>Thời gian đăng ký:&nbsp;Đến 08h ngày 06/03/2026.</p><p>1. Thông tin về buổi tiếp xúc cử tri:</p><p>- Thời gian: 07h45 ngày 07/03/2026</p><p>- Địa điểm: Hội trường số 3, Trường Đại học Nha Trang</p><p>- Số lượng sinh viên tham dự: 40 Sinh viên</p><p>Link đăng ký tham gia:&nbsp;<a href=\\\"https://docs.google.com/spreadsheets/d/1ncYBc74J37vzUEZ-r-mRp3TbzTlsPIjVNrFNSrLncKg/edit?usp=sharing\\\" target=\\\"_blank\\\" style=\\\"color: rgb(17, 85, 204);\\\">https://docs.google.com/spreadsheets/d/1ncYBc74J37vzUEZ-r-mRp3TbzTlsPIjVNrFNSrLncKg/edit?usp=sharing</a>&nbsp;</p><p><br></p><p>2. Yêu cầu đối với sinh viên tham dự:</p><p>- Tham gia đầy đủ chương trình, không rời khỏi hội trường khi chưa kết thúc buổi tiếp xúc cử tri.</p><p>- Trang phục: mặc áo Đoàn Thanh niên</p><p>- Có mặt đúng giờ, giữ gìn trật tự, tác phong nghiêm túc.</p><p>--</p><p><strong><em>Đoàn Khoa Công nghệ Thông tin, Trường Đại học Nha Trang</em></strong></p><p><strong><em>Văn phòng: Phòng 708, Nhà Đa năng (NĐN.708)</em></strong></p><p><a href=\\\"https://facebook.com/DoanHoiFIT.NTU\\\" target=\\\"_blank\\\" style=\\\"color: rgb(17, 85, 204);\\\"><em>Fanpage chính thức của Đoàn khoa Công nghệ Thông tin</em></a></p>\", \"ma_loai_su_kien\": \"6\", \"so_luong_toi_da\": \"100\", \"thoi_gian_bat_dau\": \"2026-04-14 19:49:00\", \"thoi_gian_ket_thuc\": \"2026-04-15 19:49:00\"}}', '2026-04-13 12:56:02'),
(59, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới SuKien: 3', 'App\\Models\\SuKien', '3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"attributes\": {\"bo_cuc\": \"[\\\"banner\\\",\\\"gallery\\\",\\\"header\\\",\\\"info\\\",\\\"description\\\",\\\"banner\\\"]\", \"dia_diem\": \"Hội trường 2\", \"diem_cong\": \"2\", \"created_at\": \"2026-04-13 20:24:42\", \"ma_su_kien\": 3, \"trang_thai\": \"sap_to_chuc\", \"updated_at\": \"2026-04-13 20:24:42\", \"anh_su_kien\": null, \"ten_su_kien\": \"WorkShop23\", \"ma_nguoi_tao\": \"64131940\", \"mo_ta_chi_tiet\": \"<p>Vì đây là hoạt động chính trị rất quan trọng, các bạn cần triển khai gấp thông báo triệu tập bổ sung này đến lớp, và đôn đốc sinh viên tham dự đầy đủ nhé. Ban Cán sự theo dõi link đăng ký tham gia để nắm bắt sinh viên tham dự buổi tiếp xúc và cộng điểm rèn luyện cho các bạn tham gia.</p><p>Thời gian đăng ký:&nbsp;Đến 08h ngày 06/03/2026.</p><p>1. Thông tin về buổi tiếp xúc cử tri:</p><p>- Thời gian: 07h45 ngày 07/03/2026</p><p>- Địa điểm: Hội trường số 3, Trường Đại học Nha Trang</p><p>- Số lượng sinh viên tham dự: 40 Sinh viên</p><p>Link đăng ký tham gia:&nbsp;<a href=\\\"https://docs.google.com/spreadsheets/d/1ncYBc74J37vzUEZ-r-mRp3TbzTlsPIjVNrFNSrLncKg/edit?usp=sharing\\\" target=\\\"_blank\\\" style=\\\"color: rgb(17, 85, 204);\\\">https://docs.google.com/spreadsheets/d/1ncYBc74J37vzUEZ-r-mRp3TbzTlsPIjVNrFNSrLncKg/edit?usp=sharing</a>&nbsp;</p><p><br></p><p>2. Yêu cầu đối với sinh viên tham dự:</p><p>- Tham gia đầy đủ chương trình, không rời khỏi hội trường khi chưa kết thúc buổi tiếp xúc cử tri.</p><p>- Trang phục: mặc áo Đoàn Thanh niên</p><p>- Có mặt đúng giờ, giữ gìn trật tự, tác phong nghiêm túc.</p><p>--</p><p><strong><em>Đoàn Khoa Công nghệ Thông tin, Trường Đại học Nha Trang</em></strong></p><p><strong><em>Văn phòng: Phòng 708, Nhà Đa năng (NĐN.708)</em></strong></p><p><a href=\\\"https://facebook.com/DoanHoiFIT.NTU\\\" target=\\\"_blank\\\" style=\\\"color: rgb(17, 85, 204);\\\"><em>Fanpage chính thức của Đoàn khoa Công nghệ Thông tin</em></a></p>\", \"ma_loai_su_kien\": \"3\", \"so_luong_toi_da\": \"1234567890\", \"thoi_gian_bat_dau\": \"2026-04-23 20:22:00\", \"thoi_gian_ket_thuc\": \"2026-04-24 20:22:00\"}}', '2026-04-13 13:24:42'),
(60, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới SuKien: 4', 'App\\Models\\SuKien', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"attributes\": {\"bo_cuc\": \"[\\\"banner\\\",\\\"header\\\",\\\"info\\\",\\\"description\\\",\\\"gallery\\\"]\", \"dia_diem\": null, \"diem_cong\": 0, \"created_at\": \"2026-04-13 20:56:33\", \"ma_su_kien\": 4, \"trang_thai\": \"sap_to_chuc\", \"updated_at\": \"2026-04-13 20:56:33\", \"anh_su_kien\": null, \"ten_su_kien\": \"Hội thảo học tốt\", \"ma_nguoi_tao\": \"64131940\", \"mo_ta_chi_tiet\": null, \"ma_loai_su_kien\": \"1\", \"so_luong_toi_da\": 0, \"thoi_gian_bat_dau\": \"2026-04-13 20:40:00\", \"thoi_gian_ket_thuc\": \"2026-04-14 20:40:00\"}}', '2026-04-13 13:56:34'),
(61, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 4', 'App\\Models\\SuKien', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"bo_cuc\": \"[\\\"banner\\\"]\", \"so_luong_toi_da\": \"1\"}, \"old\": {\"bo_cuc\": [\"banner\", \"header\", \"info\", \"description\", \"gallery\"], \"so_luong_toi_da\": 0}}', '2026-04-13 13:58:40'),
(62, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 4', 'App\\Models\\SuKien', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"dia_diem\": \"Hội trường 3\", \"mo_ta_chi_tiet\": \"<p><span style=\\\"background-color: rgb(248, 250, 252); color: rgb(220, 38, 38);\\\">Lỗi tải ảnh từ nguồn khác (CORS chặn). Vui lòng lưu hình về máy trước khi kéo thả tải lên.</span></p>\"}, \"old\": {\"dia_diem\": null, \"mo_ta_chi_tiet\": null}}', '2026-04-13 13:59:08'),
(63, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 4', 'App\\Models\\SuKien', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"anh_su_kien\": \"su_kien/k49bjnH4SOYIwjVvI7YkZHUlWhg06mdHw3ajYBIk.png\"}, \"old\": {\"anh_su_kien\": null}}', '2026-04-13 13:59:24'),
(64, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới SuKien: 5', 'App\\Models\\SuKien', '5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"attributes\": {\"bo_cuc\": \"[\\\"banner\\\",\\\"header\\\",\\\"info\\\",\\\"description\\\",\\\"gallery\\\",\\\"banner\\\"]\", \"dia_diem\": \"Lab A5\", \"diem_cong\": \"15\", \"created_at\": \"2026-04-13 21:04:41\", \"ma_su_kien\": 5, \"trang_thai\": \"sap_to_chuc\", \"updated_at\": \"2026-04-13 21:04:41\", \"anh_su_kien\": \"su_kien/rAZWNCnxT7o3WSb80FxDl5GOgeIg9AePY6WxFOl8.png\", \"ten_su_kien\": \"Test Event 2026-1\", \"ma_nguoi_tao\": \"64131940\", \"mo_ta_chi_tiet\": \"<p>Vì đây là hoạt động chính trị rất quan trọng, các bạn cần triển khai gấp thông báo triệu tập bổ sung này đến lớp, và đôn đốc sinh viên tham dự đầy đủ nhé. Ban Cán sự theo dõi link đăng ký tham gia để nắm bắt sinh viên tham dự buổi tiếp xúc và cộng điểm rèn luyện cho các bạn tham gia.</p><p>Thời gian đăng ký:&nbsp;Đến 08h ngày 06/03/2026.</p><p>1. Thông tin về buổi tiếp xúc cử tri:</p><p>- Thời gian: 07h45 ngày 07/03/2026</p><p>- Địa điểm: Hội trường số 3, Trường Đại học Nha Trang</p><p>- Số lượng sinh viên tham dự: 40 Sinh viên</p><p>Link đăng ký tham gia:&nbsp;<a href=\\\"https://docs.google.com/spreadsheets/d/1ncYBc74J37vzUEZ-r-mRp3TbzTlsPIjVNrFNSrLncKg/edit?usp=sharing\\\" target=\\\"_blank\\\" style=\\\"color: rgb(17, 85, 204);\\\">https://docs.google.com/spreadsheets/d/1ncYBc74J37vzUEZ-r-mRp3TbzTlsPIjVNrFNSrLncKg/edit?usp=sharing</a>&nbsp;</p><p><br></p><p>2. Yêu cầu đối với sinh viên tham dự:</p><p>- Tham gia đầy đủ chương trình, không rời khỏi hội trường khi chưa kết thúc buổi tiếp xúc cử tri.</p><p>- Trang phục: mặc áo Đoàn Thanh niên</p><p>- Có mặt đúng giờ, giữ gìn trật tự, tác phong nghiêm túc.</p><p>--</p><p><strong><em>Đoàn Khoa Công nghệ Thông tin, Trường Đại học Nha Trang</em></strong></p><p><strong><em>Văn phòng: Phòng 708, Nhà Đa năng (NĐN.708)</em></strong></p><p><a href=\\\"https://facebook.com/DoanHoiFIT.NTU\\\" target=\\\"_blank\\\" style=\\\"color: rgb(17, 85, 204);\\\"><em>Fanpage chính thức của Đoàn khoa Công nghệ Thông tin</em></a></p>\", \"ma_loai_su_kien\": \"4\", \"so_luong_toi_da\": \"1\", \"thoi_gian_bat_dau\": \"2026-04-12 21:02:00\", \"thoi_gian_ket_thuc\": \"2026-04-13 21:02:00\"}}', '2026-04-13 14:04:41'),
(65, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 5', 'App\\Models\\SuKien', '5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"bo_cuc\": \"[\\\"banner\\\",\\\"banner\\\",\\\"header\\\",\\\"info\\\",\\\"description\\\",\\\"gallery\\\"]\"}, \"old\": {\"bo_cuc\": [\"banner\", \"header\", \"info\", \"description\", \"gallery\", \"banner\"]}}', '2026-04-13 14:05:13'),
(66, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 4', 'App\\Models\\SuKien', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"diem_cong\": \"1\"}, \"old\": {\"diem_cong\": 0}}', '2026-04-13 14:15:10'),
(67, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 4', 'App\\Models\\SuKien', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"bo_cuc\": \"[\\\"banner\\\",\\\"header\\\",\\\"info\\\",\\\"description\\\",\\\"gallery\\\"]\"}, \"old\": {\"bo_cuc\": [\"banner\"]}}', '2026-04-13 14:15:26'),
(68, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-13 15:09:06'),
(69, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-13 15:09:49'),
(70, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 1', 'App\\Models\\BauCu', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"hien_thi\": false}, \"old\": {\"hien_thi\": true}}', '2026-04-13 15:10:30'),
(71, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 1', 'App\\Models\\BauCu', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"hien_thi\": true}, \"old\": {\"hien_thi\": false}}', '2026-04-13 15:10:33'),
(72, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131940', 'App\\Models\\User', '64131940', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"duong_dan_anh\": \"avatars/f0KBGI5LaWeTz4RxHX1a1DMZSz1IFT2gdJ64PBQa.jpg\"}, \"old\": {\"duong_dan_anh\": null}}', '2026-04-13 15:12:15'),
(73, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 4', 'App\\Models\\SuKien', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"so_luong_hien_tai\": 1}, \"old\": {\"so_luong_hien_tai\": 0}}', '2026-04-13 15:15:03'),
(74, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 4', 'App\\Models\\SuKien', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"so_luong_hien_tai\": 0}, \"old\": {\"so_luong_hien_tai\": 1}}', '2026-04-13 15:15:16'),
(75, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 4', 'App\\Models\\SuKien', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"bo_cuc\": \"[\\\"banner\\\",\\\"header\\\",\\\"info\\\",\\\"description\\\",\\\"gallery\\\",\\\"banner\\\"]\"}, \"old\": {\"bo_cuc\": [\"banner\", \"header\", \"info\", \"description\", \"gallery\"]}}', '2026-04-13 15:16:22'),
(76, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 4', 'App\\Models\\SuKien', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"bo_cuc\": \"[\\\"header\\\",\\\"info\\\",\\\"description\\\",\\\"gallery\\\"]\"}, \"old\": {\"bo_cuc\": [\"banner\", \"header\", \"info\", \"description\", \"gallery\", \"banner\"]}}', '2026-04-13 15:16:45'),
(77, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 4', 'App\\Models\\SuKien', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"bo_cuc\": \"[\\\"header\\\",\\\"info\\\",\\\"description\\\",\\\"gallery\\\",\\\"header\\\",\\\"header\\\",\\\"header\\\",\\\"header\\\",\\\"header\\\",\\\"header\\\",\\\"header\\\"]\"}, \"old\": {\"bo_cuc\": [\"header\", \"info\", \"description\", \"gallery\"]}}', '2026-04-13 15:17:03'),
(78, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-14 10:08:42'),
(79, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 1', 'App\\Models\\BauCu', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"hien_thi\": false}, \"old\": {\"hien_thi\": true}}', '2026-04-14 10:19:49'),
(80, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 1', 'App\\Models\\BauCu', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"hien_thi\": true}, \"old\": {\"hien_thi\": false}}', '2026-04-14 10:19:52'),
(81, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 1', 'App\\Models\\BauCu', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"hien_thi_ket_qua\": false}, \"old\": {\"hien_thi_ket_qua\": true}}', '2026-04-14 10:21:31'),
(82, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 1', 'App\\Models\\BauCu', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"hien_thi_ket_qua\": true}, \"old\": {\"hien_thi_ket_qua\": false}}', '2026-04-14 10:21:35'),
(83, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 1', 'App\\Models\\BauCu', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"hien_thi\": false}, \"old\": {\"hien_thi\": true}}', '2026-04-14 10:21:38'),
(84, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 1', 'App\\Models\\BauCu', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"hien_thi\": true}, \"old\": {\"hien_thi\": false}}', '2026-04-14 10:21:40'),
(85, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-14 14:52:14'),
(86, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới SuKien: 6', 'App\\Models\\SuKien', '6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"attributes\": {\"bo_cuc\": \"[\\\"banner\\\",\\\"header\\\",\\\"info\\\",\\\"description\\\",\\\"gallery\\\",\\\"banner\\\"]\", \"dia_diem\": \"Hội trường 4\", \"diem_cong\": \"5\", \"created_at\": \"2026-04-14 22:12:26\", \"ma_su_kien\": 6, \"trang_thai\": \"sap_to_chuc\", \"updated_at\": \"2026-04-14 22:12:26\", \"anh_su_kien\": \"su_kien/sBzCnQXj2XWCVBjIEBLgdOqcmIqevCebmyIGSxF7.jpg\", \"ten_su_kien\": \"WorkShop234\", \"ma_nguoi_tao\": \"64131940\", \"mo_ta_chi_tiet\": \"<p>http://ql_su_kien.test/admin/su-kien/create</p><p>&nbsp; - Thư viện media bị trùng ảnh khi dùng lại ảnh cũ</p><p>&nbsp; - Các mô đun phụ trợ được thêm vào có thể điền nội dung khác thay vì copy mô dun đã có</p><p>http://ql_su_kien.test/admin/nguoi-dung</p><p>&nbsp; - Thêm phương thức truy vấn dữ liệu nhanh theo tên. lớp, mssv</p><p>&nbsp; - Điều chỉnh bố cục các cột phù hợp</p><p>&nbsp; - trong phần thêm mới người dùng bắt buộc nhập đầy dủ thông tin như đăng ký tài khoản và có xác thực smtp</p><p>&nbsp; - Bỏ chức năng import file excel</p><p>&nbsp; - Hỗ trợ hiện ảnh đại điện người dùng</p><p>http://ql_su_kien.test/admin/media</p><p>&nbsp; - Xử lý ảnh bị trùng lặp khi tái sử dụng </p><p>&nbsp; - Căn giữa cho phần phân trang</p><p>&nbsp; - hiển thị 10 ảnh 1 trang </p><p>&nbsp; - Tính năng upload cải thiện tự động phân loại </p><p>http://ql_su_kien.test/admin/templates</p><p>&nbsp; - Bỏ chức năng này thay bằng chức năng khác có ích hơn</p><p>&nbsp; - Đồng bộ thay đổi cho các trang liên quan</p><p>http://ql_su_kien.test/admin/bau-cu/1/edit</p><p>&nbsp; - cập nhật giao diện trang này cho phù hợp với bố cục hiện tại</p><p>http://ql_su_kien.test/admin/bau-cu/1</p><p>&nbsp; - cho phép chọn cử tri từ danh sách sinh viên</p><p>http://ql_su_kien.test/admin/bao-cao</p><p>&nbsp; - Thêm hiệu ứng load trang khi xuất file</p><p>&nbsp; - Nếu file không có thông tin gì thì không cần xuất và thông báo cho người dùng</p><p>http://ql_su_kien.test/admin/thong-ke</p><p>&nbsp; - Điều chỉnh lại các biểu đồ thống kê trực quan, chuyên nghiệp</p><p>http://ql_su_kien.test/admin/diem-danh</p><p>&nbsp; - Khi chọn QR sự kiện -&gt; phóng to màn hình để sinh viên dễ quét hơn</p><p>&nbsp; - QR chỉ được bởi ứng dụng</p><p>http://ql_su_kien.test/admin/diem-danh/scanner</p><p>&nbsp; - sửa lỗi : Lỗi truy cập máy ảnh: Camera streaming not supported by the browser.</p><p>http://ql_su_kien.test/admin/smtp</p><p>&nbsp; - Thêm hướng dẫn người dùng cách cài smtp</p><p>http://ql_su_kien.test/admin/activity-logs?search=&amp;action=&amp;from=2026-04-13&amp;to=2026-04-12</p><p>&nbsp; - sửa lại logic phần lọc thông tin cho đúng logic thực tế</p><p>&nbsp; - Chỉ ghi lại các log của Admin</p><p><br></p><p>&nbsp; &nbsp; </p><p><br></p>\", \"ma_loai_su_kien\": \"1\", \"so_luong_toi_da\": \"100\", \"thoi_gian_bat_dau\": \"2026-04-15 22:11:00\", \"thoi_gian_ket_thuc\": \"2026-04-16 22:12:00\"}}', '2026-04-14 15:12:26'),
(87, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 6', 'App\\Models\\SuKien', '6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"new\": {\"so_luong_hien_tai\": 1}, \"old\": {\"so_luong_hien_tai\": 0}}', '2026-04-14 15:15:23'),
(88, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-15 13:17:37'),
(89, '64131940', 'Quản trị viên Demo', 'delete', 'Xóa User: 64131951', 'App\\Models\\User', '64131951', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '{\"attributes\": {\"lop\": \"64.CNTT-2\", \"email\": \"duongquangpy11@gmail.com\", \"ho_ten\": \"Trà Phương Duyên\", \"vai_tro\": \"sinh_vien\", \"mat_khau\": \"$2y$12$BwFhvzGCO672CC4SNCOVuOaCX8lipSBf65t5RrwEywg7X6mH5ym1G\", \"created_at\": \"2026-04-11 16:44:01\", \"deleted_at\": \"2026-04-15 21:50:27\", \"trang_thai\": \"hoat_dong\", \"updated_at\": \"2026-04-15 21:50:27\", \"ma_sinh_vien\": \"64131951\", \"duong_dan_anh\": null, \"so_dien_thoai\": \"0337041004\", \"email_verified_at\": \"2026-04-11 16:50:15\"}}', '2026-04-15 14:50:27'),
(90, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-15 14:51:33'),
(91, '64131940', 'Quản trị viên Demo', 'update', 'Test gửi email SMTP tới duongquangpy11@gmail.com — thành công', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-15 14:52:00'),
(92, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-15 15:31:14'),
(93, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-15 15:31:31'),
(94, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-15 15:32:56'),
(95, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '2026-04-15 15:40:33'),
(96, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-16 14:31:15'),
(97, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới User: 64131949', 'App\\Models\\User', '64131949', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"attributes\": {\"lop\": \"64.CNTT-1\", \"email\": \"duongquangpy11@gmail.com\", \"ho_ten\": \"Nguyễn Văn A\", \"vai_tro\": \"sinh_vien\", \"mat_khau\": \"$2y$12$IyBjfpVfQLN.Fzl0FpOoeu1A/bveY2AgRYsLrqLFXJsR1QFKd9zdK\", \"created_at\": \"2026-04-16 21:34:07\", \"trang_thai\": \"hoat_dong\", \"updated_at\": \"2026-04-16 21:34:07\", \"ma_sinh_vien\": \"64131949\", \"duong_dan_anh\": null, \"so_dien_thoai\": \"0337041004\", \"email_verified_at\": null}}', '2026-04-16 14:34:07'),
(98, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131949', 'App\\Models\\User', '64131949', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"email_verified_at\": \"2026-04-16 21:34:28\"}, \"old\": {\"email_verified_at\": null}}', '2026-04-16 14:34:28'),
(99, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-16 14:40:24'),
(100, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới BauCu: 2', 'App\\Models\\BauCu', '2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"attributes\": {\"mo_ta\": null, \"tieu_de\": \"Bầu cử BCH Đoàn Khoa CNTT\", \"ma_bau_cu\": 2, \"created_at\": \"2026-04-16 21:41:07\", \"updated_at\": \"2026-04-16 21:41:07\", \"ma_nguoi_tao\": \"64131940\", \"so_chon_toi_da\": \"1\", \"so_chon_toi_thieu\": \"1\", \"thoi_gian_bat_dau\": \"2026-04-16 21:41:00\", \"thoi_gian_ket_thuc\": \"2026-04-17 21:41:00\"}}', '2026-04-16 14:41:07'),
(101, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 2', 'App\\Models\\BauCu', '2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"hien_thi\": true}, \"old\": {\"hien_thi\": false}}', '2026-04-16 14:41:33'),
(102, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới BauCu: 3', 'App\\Models\\BauCu', '3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"attributes\": {\"mo_ta\": null, \"tieu_de\": \"Bầu cử BCH Đoàn Khoa CNTT\", \"ma_bau_cu\": 3, \"created_at\": \"2026-04-16 22:16:01\", \"updated_at\": \"2026-04-16 22:16:01\", \"ma_nguoi_tao\": \"64131940\", \"so_chon_toi_da\": \"1\", \"so_chon_toi_thieu\": \"1\", \"thoi_gian_bat_dau\": \"2026-04-17 22:15:00\", \"thoi_gian_ket_thuc\": \"2026-04-18 22:15:00\"}}', '2026-04-16 15:16:01'),
(103, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới SuKien: 7', 'App\\Models\\SuKien', '7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"attributes\": {\"bo_cuc\": \"[\\\"banner\\\",\\\"gallery\\\",\\\"header\\\",\\\"info\\\",\\\"description\\\"]\", \"dia_diem\": \"Hội trường 4\", \"diem_cong\": \"5\", \"created_at\": \"2026-04-16 23:08:01\", \"ma_su_kien\": 7, \"trang_thai\": \"sap_to_chuc\", \"updated_at\": \"2026-04-16 23:08:01\", \"anh_su_kien\": \"su_kien/6erhsh72tWuy6RQT7Y7bxFYvfTAePPbGCKvgsjtF.jpg\", \"ten_su_kien\": \"WorkShop234\", \"ma_nguoi_tao\": \"64131940\", \"mo_ta_chi_tiet\": \"<p>bao-cao:27 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.</p><p>bao-cao:27 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.</p><p>bao-cao:27 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.</p><p>bao-cao:27 Tracking Prevention blocked access to storage for<a href=\\\" https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\\\" target=\\\"_blank\\\"> https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.</a></p><p>app-CBbX3x7F.js:6 [LoadingInterceptor] ✓ Interceptors attached to axios.</p><p>bao-cao:1 The file at \'blob:http://ql_su_kien.test/c5ac4f8f-3f6a-4d40-9c20-642fb75635f6\' was loaded over an insecure connection. This file should be served over HTTPS.</p><p><br></p>\", \"ma_loai_su_kien\": \"3\", \"so_luong_toi_da\": \"100\", \"thoi_gian_bat_dau\": \"2026-04-17 23:07:00\", \"thoi_gian_ket_thuc\": \"2026-04-18 23:07:00\"}}', '2026-04-16 16:08:01'),
(104, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-16 16:08:23'),
(105, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 10:23:11'),
(106, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 1', 'App\\Models\\SuKien', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"so_luong_hien_tai\": 0}, \"old\": {\"so_luong_hien_tai\": 1}}', '2026-04-17 10:49:08'),
(107, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 7', 'App\\Models\\SuKien', '7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"so_luong_hien_tai\": 1}, \"old\": {\"so_luong_hien_tai\": 0}}', '2026-04-17 11:19:17'),
(108, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 7', 'App\\Models\\SuKien', '7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"thoi_gian_bat_dau\": \"2026-04-17 18:07:00\", \"thoi_gian_ket_thuc\": \"2026-04-17 18:25:00\"}, \"old\": {\"thoi_gian_bat_dau\": \"2026-04-17T16:07:00.000000Z\", \"thoi_gian_ket_thuc\": \"2026-04-18T16:07:00.000000Z\"}}', '2026-04-17 11:20:33'),
(109, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 7', 'App\\Models\\SuKien', '7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"so_luong_hien_tai\": 0}, \"old\": {\"so_luong_hien_tai\": 1}}', '2026-04-17 11:26:10');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `description`, `model_type`, `model_id`, `ip_address`, `user_agent`, `properties`, `created_at`) VALUES
(110, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 6', 'App\\Models\\SuKien', '6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"so_luong_hien_tai\": 0}, \"old\": {\"so_luong_hien_tai\": 1}}', '2026-04-17 11:26:43'),
(111, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 7', 'App\\Models\\SuKien', '7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"bo_cuc\": \"[\\\"gallery\\\",\\\"header\\\",\\\"info\\\",\\\"description\\\",\\\"banner\\\"]\"}, \"old\": {\"bo_cuc\": [\"banner\", \"gallery\", \"header\", \"info\", \"description\"]}}', '2026-04-17 11:30:04'),
(112, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 7', 'App\\Models\\SuKien', '7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"thoi_gian_bat_dau\": \"2026-04-18 18:07:00\", \"thoi_gian_ket_thuc\": \"2026-04-19 18:25:00\"}, \"old\": {\"thoi_gian_bat_dau\": \"2026-04-17T11:07:00.000000Z\", \"thoi_gian_ket_thuc\": \"2026-04-17T11:25:00.000000Z\"}}', '2026-04-17 11:33:19'),
(113, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 3', 'App\\Models\\SuKien', '3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"so_luong_hien_tai\": 1}, \"old\": {\"so_luong_hien_tai\": 0}}', '2026-04-17 11:34:28'),
(114, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 3', 'App\\Models\\SuKien', '3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"thoi_gian_bat_dau\": \"2026-04-17 18:36:00\", \"thoi_gian_ket_thuc\": \"2026-04-17 18:37:00\"}, \"old\": {\"thoi_gian_bat_dau\": \"2026-04-23T13:22:00.000000Z\", \"thoi_gian_ket_thuc\": \"2026-04-24T13:22:00.000000Z\"}}', '2026-04-17 11:35:12'),
(115, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 7', 'App\\Models\\SuKien', '7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"bo_cuc\": \"[\\\"gallery\\\",\\\"banner\\\",\\\"header\\\",\\\"info\\\",\\\"description\\\"]\"}, \"old\": {\"bo_cuc\": [\"gallery\", \"header\", \"info\", \"description\", \"banner\"]}}', '2026-04-17 11:55:57'),
(116, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 14:47:14'),
(117, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:04:11'),
(118, '64131940', 'Quản trị viên Demo', 'update', 'Test gửi email SMTP tới quang.dp.64cntt@ntu.edu.vn — thành công', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:04:28'),
(119, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:09:20'),
(120, '64131940', 'Quản trị viên Demo', 'update', 'Test gửi email SMTP tới quang.dp.64cntt@ntu.edu.vn — thành công', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:09:53'),
(121, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:11:07'),
(122, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:14:34'),
(123, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:17:44'),
(124, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:20:23'),
(125, '64131940', 'Quản trị viên Demo', 'update', 'Test gửi email SMTP tới quang.dp.64cntt@ntu.edu.vn — thành công', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:20:51'),
(126, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:21:18'),
(127, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:23:43'),
(128, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:24:01'),
(129, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:24:16'),
(130, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình SMTP', 'App\\Models\\SmtpSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-17 15:24:27'),
(131, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-18 02:02:39'),
(132, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, '2026-04-18 03:03:42'),
(133, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, '2026-04-18 03:03:55'),
(134, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, '2026-04-18 04:10:39'),
(135, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, '2026-04-18 04:10:42'),
(136, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 7', 'App\\Models\\SuKien', '7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"thoi_gian_bat_dau\": \"2026-04-18 06:07:00\", \"thoi_gian_ket_thuc\": \"2026-04-19 00:25:00\"}, \"old\": {\"thoi_gian_bat_dau\": \"2026-04-18T11:07:00.000000Z\", \"thoi_gian_ket_thuc\": \"2026-04-19T11:25:00.000000Z\"}}', '2026-04-18 04:56:07'),
(137, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 10:51:07'),
(138, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 14:53:15'),
(139, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 14:57:40'),
(140, '64131940', 'Quản trị viên Demo', 'update', 'Kiểm tra kết nối Gemini AI thành công', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 14:57:48'),
(141, '64131940', 'Quản trị viên Demo', 'update', 'Kiểm tra kết nối Gemini AI thành công', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 14:58:00'),
(142, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 14:59:30'),
(143, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 15:19:38'),
(144, '64131940', 'Quản trị viên Demo', 'update', 'Kiểm tra kết nối Gemini AI thành công', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 15:19:44'),
(145, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 15:34:17'),
(146, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 15:36:17'),
(147, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 15:36:44'),
(148, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 15:37:30'),
(149, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 15:37:56'),
(150, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 15:38:34'),
(151, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-22 15:39:03'),
(152, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-25 10:49:07'),
(153, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-25 10:49:18'),
(154, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-25 11:05:53'),
(155, '64131940', 'Quản trị viên Demo', 'update', 'Kiểm tra kết nối Gemini AI thành công', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-25 11:05:57'),
(156, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-25 11:07:05'),
(157, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-25 11:09:13'),
(158, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật cấu hình Gemini AI chatbot', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-25 11:12:43'),
(159, '64131940', 'Quản trị viên Demo', 'update', 'Kiểm tra kết nối Gemini AI thành công', 'App\\Models\\GeminiSetting', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-25 11:12:54'),
(160, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới SuKien: 10', 'App\\Models\\SuKien', '10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"attributes\": {\"bo_cuc\": \"[{\\\"id\\\":\\\"banner-1\\\",\\\"type\\\":\\\"banner\\\",\\\"title\\\":\\\"\\\\u1ea2nh b\\\\u00eca\\\",\\\"settings\\\":{\\\"caption_label\\\":\\\"Ch\\\\u00fa th\\\\u00edch \\\\u1ea3nh\\\"},\\\"content\\\":{\\\"caption\\\":\\\"\\\",\\\"image_path\\\":\\\"su_kien\\\\/modules\\\\/banner\\\\/lq0tnbPFAti190H2gmoohK8PFtqJYz8WcxJ3zubL.jpg\\\"}},{\\\"id\\\":\\\"header-1\\\",\\\"type\\\":\\\"header\\\",\\\"title\\\":\\\"Ti\\\\u00eau \\\\u0111\\\\u1ec1 ch\\\\u00ednh\\\",\\\"settings\\\":{\\\"subtitle_label\\\":\\\"Ph\\\\u1ee5 \\\\u0111\\\\u1ec1\\\",\\\"badge_label\\\":\\\"Badge\\\"},\\\"content\\\":{\\\"title\\\":\\\"H\\\\u1ed9i th\\\\u1ea3o nghi\\\\u00ean c\\\\u1ee9u khoa h\\\\u1ecdc\\\",\\\"subtitle\\\":\\\"Ch\\\\u01b0\\\\u01a1ng tr\\\\u00ecnh d\\\\u00e0nh ri\\\\u00eang cho sinh vi\\\\u00ean khoa C\\\\u00f4ng Ngh\\\\u1ec7 Th\\\\u00f4ng Tin\\\",\\\"badge\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n th\\\\u01b0\\\\u1eddng ni\\\\u00ean\\\"}},{\\\"id\\\":\\\"info-1\\\",\\\"type\\\":\\\"info\\\",\\\"title\\\":\\\"Th\\\\u00f4ng tin s\\\\u1ef1 ki\\\\u1ec7n\\\",\\\"settings\\\":{\\\"items\\\":[\\\"time\\\",\\\"location\\\",\\\"capacity\\\",\\\"points\\\"]},\\\"content\\\":{\\\"items\\\":[\\\"time\\\",\\\"location\\\",\\\"capacity\\\",\\\"points\\\"],\\\"custom_note\\\":\\\"Sinh vi\\\\u00ean mang theo th\\\\u1ebb sinh vi\\\\u00ean\\\"}},{\\\"id\\\":\\\"gallery-1\\\",\\\"type\\\":\\\"gallery\\\",\\\"title\\\":\\\"H\\\\u00ecnh \\\\u1ea3nh s\\\\u1ef1 ki\\\\u1ec7n\\\",\\\"settings\\\":{\\\"hint\\\":\\\"T\\\\u1ea3i nhi\\\\u1ec1u \\\\u1ea3nh cho ri\\\\u00eang kh\\\\u1ed1i n\\\\u00e0y\\\"},\\\"content\\\":{\\\"images\\\":[\\\"su_kien\\\\/modules\\\\/gallery\\\\/RpfMhjLQXlUgNZtL30jcTK20VHyGYMLrefZPsNEs.jpg\\\",\\\"su_kien\\\\/modules\\\\/gallery\\\\/BEBjBFn4jOE5tYO4ssaz4ZBFVyF6dHm38fohUH4p.jpg\\\",\\\"su_kien\\\\/modules\\\\/gallery\\\\/Bo9X21BXNBDiWYmQQxSlzmWVV3tDCUrcy6ah3YBi.jpg\\\",\\\"su_kien\\\\/modules\\\\/gallery\\\\/oE8cmLMX6Im0f2fv5oAW5Lw11jJELzeYyh2tvyRC.jpg\\\",\\\"su_kien\\\\/modules\\\\/gallery\\\\/RYZoDSUWlnJl7DDoopIFWFRC3O3lu348eJPZrih4.jpg\\\"]}},{\\\"id\\\":\\\"description-1\\\",\\\"type\\\":\\\"description\\\",\\\"title\\\":\\\"N\\\\u1ed9i dung chi ti\\\\u1ebft\\\",\\\"settings\\\":{\\\"body_label\\\":\\\"N\\\\u1ed9i dung\\\"},\\\"content\\\":{\\\"heading\\\":\\\"N\\\\u1ed9i dung chi ti\\\\u1ebft\\\",\\\"body\\\":\\\"H\\\\u00e3y \\\\u0111\\\\u1ecdc  d\\\\u1ee5 \\\\u00e1n , ch\\\\u00fa tr\\\\u1ecdng ch\\\\u00fac n\\\\u0103ng tr\\\\u1ee3 l\\\\u00fd AI th\\\\u1ef1c hi\\\\u1ec7n c\\\\u00e1c thay \\\\u0111\\\\u1ed5i sau :\\\\r\\\\n- Thay v\\\\u00ec l\\\\u00fac n\\\\u00e0o c\\\\u0169ng g\\\\u1eedi y\\\\u00eau c\\\\u1ea7u v\\\\u1ec1 AI th\\\\u00ec h\\\\u00e3y t\\\\u1ee5 tr\\\\u1ea3 l\\\\u1eddi c\\\\u00e1c c\\\\u00e2u h\\\\u1ecfi c\\\\u00f3 s\\\\u1eb5n b\\\\u00e0ng d\\\\u1eef li\\\\u1ec7u c\\\\u00f3 s\\\\u1eb5n, d\\\\u00f9ng c\\\\u00e1c c\\\\u00e2u l\\\\u1ec7nh sql \\\\u0111\\\\u1ec3 l\\\\u1ea5y d\\\\u1eef li\\\\u1ec7u t\\\\u1eeb s\\\\u1ef1 ki\\\\u1ec7n \\\\u0111\\\\u1ec3 tr\\\\u1ea3 l\\\\u1eddi cho c\\\\u00e1c c\\\\u00e2u h\\\\u1ecfi \\\\r\\\\n- T\\\\u1ed1i \\\\u01b0u h\\\\u00f3a c\\\\u00e2u h\\\\u1ecfi \\\\u0111\\\\u1ec3 gi\\\\u1ea3m token cho c\\\\u00e1c c\\\\u00e2u tr\\\\u1ea3 l\\\\u1eddi\\\\r\\\\n- \\\\u0110\\\\u01b0a th\\\\u00eam c\\\\u00e1c gi\\\\u1ea3i ph\\\\u00e1p t\\\\u1ed1i \\\\u01b0u\\\"}},{\\\"id\\\":\\\"gallery-2\\\",\\\"type\\\":\\\"gallery\\\",\\\"title\\\":\\\"Gallery 2\\\",\\\"settings\\\":{\\\"hint\\\":\\\"T\\\\u1ea3i nhi\\\\u1ec1u \\\\u1ea3nh cho ri\\\\u00eang kh\\\\u1ed1i n\\\\u00e0y\\\"},\\\"content\\\":{\\\"images\\\":[\\\"su_kien\\\\/modules\\\\/gallery\\\\/biJBGEGcU0plaAQwV2BS5JDUnDl9COJA3eov4jgS.jpg\\\",\\\"su_kien\\\\/modules\\\\/gallery\\\\/KWB5tqnhZUUJB7J1u3XvSilg1OeVoN4ftykQ3Iac.jpg\\\",\\\"su_kien\\\\/modules\\\\/gallery\\\\/QRIRNB0ZA4nRZ9omqYTxProuoAkGJ6ctPZ4kGiEY.jpg\\\",\\\"su_kien\\\\/modules\\\\/gallery\\\\/qnYQ97RmTnAZltgdmDyuplXz5e3uLzONUpEhiZjJ.jpg\\\"]}},{\\\"id\\\":\\\"description-2\\\",\\\"type\\\":\\\"description\\\",\\\"title\\\":\\\"N\\\\u1ed9i dung 2\\\",\\\"settings\\\":{\\\"body_label\\\":\\\"N\\\\u1ed9i dung\\\"},\\\"content\\\":{\\\"heading\\\":\\\"N\\\\u1ed9i dung 2\\\",\\\"body\\\":\\\"V\\\\u00ec sao sinh vi\\\\u00ean n\\\\u00ean tham gia ho\\\\u1ea1t \\\\u0111\\\\u1ed9ng ngo\\\\u1ea1i kh\\\\u00f3a?\\\"}}]\", \"dia_diem\": \"Hội trường 3\", \"diem_cong\": \"2\", \"created_at\": \"2026-04-25 18:40:56\", \"ma_su_kien\": 10, \"trang_thai\": \"sap_to_chuc\", \"updated_at\": \"2026-04-25 18:40:56\", \"anh_su_kien\": \"su_kien/modules/banner/lq0tnbPFAti190H2gmoohK8PFtqJYz8WcxJ3zubL.jpg\", \"ten_su_kien\": \"Hội thảo học tốt\", \"ma_nguoi_tao\": \"64131940\", \"mo_ta_chi_tiet\": \"Hãy đọc  dụ án , chú trọng chúc năng trợ lý AI thực hiện các thay đổi sau :\\r\\n- Thay vì lúc nào cũng gửi yêu cầu về AI thì hãy tụ trả lời các câu hỏi có sẵn bàng dữ liệu có sẵn, dùng các câu lệnh sql để lấy dữ liệu từ sự kiện để trả lời cho các câu hỏi \\r\\n- Tối ưu hóa câu hỏi để giảm token cho các câu trả lời\\r\\n- Đưa thêm các giải pháp tối ưu\", \"ma_loai_su_kien\": \"1\", \"so_luong_toi_da\": \"200\", \"thoi_gian_bat_dau\": \"2026-04-26 18:38:00\", \"thoi_gian_ket_thuc\": \"2026-04-26 20:38:00\"}}', '2026-04-25 11:40:56'),
(161, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 10', 'App\\Models\\SuKien', '10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"so_luong_hien_tai\": 1}, \"old\": {\"so_luong_hien_tai\": 0}}', '2026-04-25 11:41:57'),
(162, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 10', 'App\\Models\\SuKien', '10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"so_luong_hien_tai\": 0}, \"old\": {\"so_luong_hien_tai\": 1}}', '2026-04-25 11:42:04'),
(163, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-27 08:16:20'),
(164, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, '2026-04-27 08:19:03'),
(165, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-27 08:19:35'),
(166, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-27 08:41:47'),
(167, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-27 08:41:52'),
(168, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới SuKien: 11', 'App\\Models\\SuKien', '11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"attributes\": {\"bo_cuc\": \"[{\\\"id\\\":\\\"banner-1\\\",\\\"type\\\":\\\"banner\\\",\\\"title\\\":\\\"\\\\u1ea2nh b\\\\u00eca\\\",\\\"settings\\\":{\\\"caption_label\\\":\\\"Ch\\\\u00fa th\\\\u00edch \\\\u1ea3nh\\\"},\\\"content\\\":{\\\"caption\\\":\\\"\\\",\\\"image_path\\\":\\\"media\\\\/images\\\\/ae2In5CkBfanmSInMbB1QvJEyRyTKIpO1j3XuT8n.jpg\\\"}},{\\\"id\\\":\\\"header-1\\\",\\\"type\\\":\\\"header\\\",\\\"title\\\":\\\"Ti\\\\u00eau \\\\u0111\\\\u1ec1 ch\\\\u00ednh\\\",\\\"settings\\\":{\\\"subtitle_label\\\":\\\"Ph\\\\u1ee5 \\\\u0111\\\\u1ec1\\\",\\\"badge_label\\\":\\\"Badge\\\"},\\\"content\\\":{\\\"title\\\":\\\"H\\\\u1ed9i th\\\\u1ea3o nghi\\\\u00ean c\\\\u1ee9u khoa h\\\\u1ecdc\\\",\\\"subtitle\\\":\\\"\\\",\\\"badge\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n th\\\\u01b0\\\\u1eddng ni\\\\u00ean\\\"}},{\\\"id\\\":\\\"info-1\\\",\\\"type\\\":\\\"info\\\",\\\"title\\\":\\\"Th\\\\u00f4ng tin s\\\\u1ef1 ki\\\\u1ec7n\\\",\\\"settings\\\":{\\\"items\\\":[\\\"time\\\",\\\"location\\\",\\\"capacity\\\",\\\"points\\\"]},\\\"content\\\":{\\\"items\\\":[\\\"time\\\",\\\"location\\\",\\\"capacity\\\",\\\"points\\\"],\\\"custom_note\\\":\\\"\\\"}},{\\\"id\\\":\\\"description-1\\\",\\\"type\\\":\\\"description\\\",\\\"title\\\":\\\"N\\\\u1ed9i dung chi ti\\\\u1ebft\\\",\\\"settings\\\":{\\\"body_label\\\":\\\"N\\\\u1ed9i dung\\\"},\\\"content\\\":{\\\"heading\\\":\\\"N\\\\u1ed9i dung chi ti\\\\u1ebft\\\",\\\"body\\\":\\\"media:993 Tracking Prevention blocked access to storage for https:\\\\/\\\\/cdn.jsdelivr.net\\\\/npm\\\\/bootstrap-icons@1.11.3\\\\/font\\\\/bootstrap-icons.min.css.\\\\r\\\\nmedia:993 Tracking Prevention blocked access to storage for https:\\\\/\\\\/cdn.jsdelivr.net\\\\/npm\\\\/bootstrap-icons@1.11.3\\\\/font\\\\/bootstrap-icons.min.css.\\\\r\\\\nmedia:993 Tracking Prevention blocked access to storage for https:\\\\/\\\\/cdn.jsdelivr.net\\\\/npm\\\\/bootstrap-icons@1.11.3\\\\/font\\\\/bootstrap-icons.min.css.\\\\r\\\\nmedia:993 Tracking Prevention blocked access to storage for https:\\\\/\\\\/cdn.jsdelivr.net\\\\/npm\\\\/bootstrap-icons@1.11.3\\\\/font\\\\/bootstrap-icons.min.css.\\\\r\\\\nmedia:1486 [Intervention] Images loaded lazily and replaced with placeholders. Load events are deferred. See https:\\\\/\\\\/go.microsoft.com\\\\/fwlink\\\\/?linkid=2048113\\\\r\\\\napp-CBbX3x7F.js:6 [LoadingInterceptor] \\\\u2713 Interceptors attached to axios.\\\\r\\\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=\\\\u200b\\\\\\\"file\\\\\\\" name=\\\\u200b\\\\\\\"file\\\\\\\" id=\\\\u200b\\\\\\\"fileInput\\\\\\\" required style=\\\\u200b\\\\\\\"display:\\\\u200bnone\\\\\\\">\\\\u200b\\\\r\\\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=\\\\u200b\\\\\\\"file\\\\\\\" name=\\\\u200b\\\\\\\"file\\\\\\\" id=\\\\u200b\\\\\\\"fileInput\\\\\\\" required style=\\\\u200b\\\\\\\"display:\\\\u200bnone\\\\\\\">\\\\u200b\\\\r\\\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=\\\\u200b\\\\\\\"file\\\\\\\" name=\\\\u200b\\\\\\\"file\\\\\\\" id=\\\\u200b\\\\\\\"fileInput\\\\\\\" required style=\\\\u200b\\\\\\\"display:\\\\u200bnone\\\\\\\">\\\"}},{\\\"id\\\":\\\"gallery-1\\\",\\\"type\\\":\\\"gallery\\\",\\\"title\\\":\\\"H\\\\u00ecnh \\\\u1ea3nh s\\\\u1ef1 ki\\\\u1ec7n\\\",\\\"settings\\\":{\\\"hint\\\":\\\"T\\\\u1ea3i nhi\\\\u1ec1u \\\\u1ea3nh cho ri\\\\u00eang kh\\\\u1ed1i n\\\\u00e0y\\\"},\\\"content\\\":{\\\"images\\\":[\\\"media\\\\/images\\\\/PDv4bpBvduvdh1vwxYqdH1CAHi3aPNhM0uqcGlX7.jpg\\\",\\\"media\\\\/images\\\\/qKkz8HoQsYS9pkbiSEeVSDxZYheFT2xejIIT46ZF.jpg\\\",\\\"media\\\\/images\\\\/MQslEZT9vQ1nvo5R1UqguxE7p0tfjdHgKLgwKL3I.jpg\\\"]}},{\\\"id\\\":\\\"documents-1777284094489-6fbe\\\",\\\"type\\\":\\\"documents\\\",\\\"title\\\":\\\"T\\\\u00e0i li\\\\u1ec7u \\\\u0111\\\\u00ednh k\\\\u00e8m 1\\\",\\\"settings\\\":{\\\"label\\\":\\\"T\\\\u00e0i li\\\\u1ec7u\\\"},\\\"content\\\":{\\\"items\\\":[{\\\"ma_phuong_tien\\\":38,\\\"ten_tep\\\":\\\"bao_cao_lop_64.CNTT-1_2026-04-04_09-33-13.xlsx\\\",\\\"duong_dan_tep\\\":\\\"media\\\\/documents\\\\/alxusrAKKMciZLUPEmU8TEL9oiZ3cUovYUHjQbtm.xlsx\\\",\\\"loai_tep\\\":\\\"tai_lieu\\\",\\\"kich_thuoc\\\":6768}]}}]\", \"dia_diem\": \"Hội trường 3\", \"diem_cong\": \"2\", \"created_at\": \"2026-04-27 17:02:32\", \"ma_su_kien\": 11, \"trang_thai\": \"sap_to_chuc\", \"updated_at\": \"2026-04-27 17:02:32\", \"anh_su_kien\": \"media/images/ae2In5CkBfanmSInMbB1QvJEyRyTKIpO1j3XuT8n.jpg\", \"ten_su_kien\": \"Hội thảo học tốt\", \"ma_nguoi_tao\": \"64131940\", \"mo_ta_chi_tiet\": \"media:993 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\\r\\nmedia:993 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\\r\\nmedia:993 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\\r\\nmedia:993 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\\r\\nmedia:1486 [Intervention] Images loaded lazily and replaced with placeholders. Load events are deferred. See https://go.microsoft.com/fwlink/?linkid=2048113\\r\\napp-CBbX3x7F.js:6 [LoadingInterceptor] ✓ Interceptors attached to axios.\\r\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=​\\\"file\\\" name=​\\\"file\\\" id=​\\\"fileInput\\\" required style=​\\\"display:​none\\\">​\\r\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=​\\\"file\\\" name=​\\\"file\\\" id=​\\\"fileInput\\\" required style=​\\\"display:​none\\\">​\\r\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=​\\\"file\\\" name=​\\\"file\\\" id=​\\\"fileInput\\\" required style=​\\\"display:​none\\\">\", \"ma_loai_su_kien\": \"1\", \"so_luong_toi_da\": \"200\", \"thoi_gian_bat_dau\": \"2026-04-28 17:01:00\", \"thoi_gian_ket_thuc\": \"2026-04-29 17:01:00\"}}', '2026-04-27 10:02:32'),
(169, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-27 10:08:10'),
(170, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 11', 'App\\Models\\SuKien', '11', '192.168.1.119', 'okhttp/4.12.0', '{\"new\": {\"so_luong_hien_tai\": 1}, \"old\": {\"so_luong_hien_tai\": 0}}', '2026-04-27 12:21:39'),
(171, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-29 06:25:39'),
(172, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật BauCu: 3', 'App\\Models\\BauCu', '3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"hien_thi\": true}, \"old\": {\"hien_thi\": false}}', '2026-04-29 07:32:37'),
(173, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật User: 64131942', 'App\\Models\\User', '64131942', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"vai_tro\": \"admin\"}, \"old\": {\"vai_tro\": \"sinh_vien\"}}', '2026-04-29 07:39:46'),
(174, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-29 07:39:52'),
(175, '64131942', 'Dương Phú Quảng', 'login', 'Đăng nhập thành công — Dương Phú Quảng (quang.dp.64cntt@ntu.edu.vn)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-29 07:39:58'),
(176, '64131942', 'Dương Phú Quảng', 'update', 'Cập nhật User: 64131942', 'App\\Models\\User', '64131942', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"vai_tro\": \"sinh_vien\"}, \"old\": {\"vai_tro\": \"admin\"}}', '2026-04-29 07:40:12'),
(177, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-04-29 07:40:27'),
(178, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-05-01 14:37:10'),
(179, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-05-02 15:10:07'),
(180, '64131940', 'Quản trị viên Demo', 'delete', 'Xóa User: 64131931', 'App\\Models\\User', '64131931', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"attributes\": {\"lop\": \"64.CNTT-2\", \"email\": \"duongquangpy11@gmail.com\", \"ho_ten\": \"Dương Phú Quảng\", \"vai_tro\": \"sinh_vien\", \"mat_khau\": \"$2y$12$.BI/4zZ.bThTvqadA39VDuMhSxAF.iTczv5N3KKUrx1m1G9Q1A6/a\", \"created_at\": \"2026-04-17 22:23:17\", \"deleted_at\": \"2026-05-02 22:10:20\", \"trang_thai\": \"hoat_dong\", \"updated_at\": \"2026-05-02 22:10:20\", \"ma_sinh_vien\": \"64131931\", \"duong_dan_anh\": null, \"so_dien_thoai\": null, \"email_verified_at\": null}}', '2026-05-02 15:10:20'),
(181, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới SuKien: 12', 'App\\Models\\SuKien', '12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"attributes\": {\"bo_cuc\": \"[{\\\"id\\\":\\\"banner-1\\\",\\\"type\\\":\\\"banner\\\",\\\"title\\\":\\\"\\\\u1ea2nh b\\\\u00eca\\\",\\\"settings\\\":{\\\"caption_label\\\":\\\"Ch\\\\u00fa th\\\\u00edch \\\\u1ea3nh\\\"},\\\"content\\\":{\\\"caption\\\":\\\"\\\",\\\"image_path\\\":null}},{\\\"id\\\":\\\"header-1\\\",\\\"type\\\":\\\"header\\\",\\\"title\\\":\\\"Ti\\\\u00eau \\\\u0111\\\\u1ec1 ch\\\\u00ednh\\\",\\\"settings\\\":{\\\"subtitle_label\\\":\\\"Ph\\\\u1ee5 \\\\u0111\\\\u1ec1\\\",\\\"badge_label\\\":\\\"Badge\\\"},\\\"content\\\":{\\\"title\\\":\\\"\\\",\\\"subtitle\\\":\\\"\\\",\\\"badge\\\":\\\"\\\"}},{\\\"id\\\":\\\"info-1\\\",\\\"type\\\":\\\"info\\\",\\\"title\\\":\\\"Th\\\\u00f4ng tin s\\\\u1ef1 ki\\\\u1ec7n\\\",\\\"settings\\\":{\\\"items\\\":[\\\"time\\\",\\\"location\\\",\\\"capacity\\\",\\\"points\\\"]},\\\"content\\\":{\\\"items\\\":[\\\"time\\\",\\\"location\\\",\\\"capacity\\\",\\\"points\\\"],\\\"custom_note\\\":\\\"\\\"}},{\\\"id\\\":\\\"description-1\\\",\\\"type\\\":\\\"description\\\",\\\"title\\\":\\\"N\\\\u1ed9i dung chi ti\\\\u1ebft\\\",\\\"settings\\\":{\\\"body_label\\\":\\\"N\\\\u1ed9i dung\\\"},\\\"content\\\":{\\\"heading\\\":\\\"N\\\\u1ed9i dung chi ti\\\\u1ebft\\\",\\\"body\\\":\\\"\\\"}},{\\\"id\\\":\\\"gallery-1\\\",\\\"type\\\":\\\"gallery\\\",\\\"title\\\":\\\"H\\\\u00ecnh \\\\u1ea3nh s\\\\u1ef1 ki\\\\u1ec7n\\\",\\\"settings\\\":{\\\"hint\\\":\\\"T\\\\u1ea3i nhi\\\\u1ec1u \\\\u1ea3nh cho ri\\\\u00eang kh\\\\u1ed1i n\\\\u00e0y\\\"},\\\"content\\\":{\\\"images\\\":[]}}]\", \"dia_diem\": \"Hội trường 3\", \"diem_cong\": \"2\", \"created_at\": \"2026-05-02 22:14:35\", \"ma_su_kien\": 12, \"trang_thai\": \"sap_to_chuc\", \"updated_at\": \"2026-05-02 22:14:35\", \"anh_su_kien\": null, \"ten_su_kien\": \"Hội thảo học tốt\", \"ma_nguoi_tao\": \"64131940\", \"mo_ta_chi_tiet\": null, \"ma_loai_su_kien\": \"1\", \"so_luong_toi_da\": \"200\", \"thoi_gian_bat_dau\": \"2026-05-02 22:14:00\", \"thoi_gian_ket_thuc\": \"2026-05-02 22:17:00\"}}', '2026-05-02 15:14:35'),
(182, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 12', 'App\\Models\\SuKien', '12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"thoi_gian_bat_dau\": \"2026-05-02 22:16:00\", \"thoi_gian_ket_thuc\": \"2026-05-02 22:20:00\"}, \"old\": {\"thoi_gian_bat_dau\": \"2026-05-02T15:14:00.000000Z\", \"thoi_gian_ket_thuc\": \"2026-05-02T15:17:00.000000Z\"}}', '2026-05-02 15:15:22'),
(183, '64131940', 'Quản trị viên Demo', 'logout', 'Đăng xuất khỏi hệ thống', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-05-02 15:24:22'),
(184, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-05-02 15:31:23'),
(185, '64131940', 'Quản trị viên Demo', 'login', 'Đăng nhập thành công — Quản trị viên Demo (admin@example.com)', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', NULL, '2026-05-04 15:57:30'),
(186, '64131940', 'Quản trị viên Demo', 'create', 'Tạo mới SuKien: 13', 'App\\Models\\SuKien', '13', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"attributes\": {\"bo_cuc\": \"[{\\\"id\\\":\\\"banner-1\\\",\\\"type\\\":\\\"banner\\\",\\\"title\\\":\\\"\\\\u1ea2nh b\\\\u00eca\\\",\\\"settings\\\":{\\\"caption_label\\\":\\\"Ch\\\\u00fa th\\\\u00edch \\\\u1ea3nh\\\"},\\\"content\\\":{\\\"caption\\\":\\\"\\\",\\\"image_path\\\":null}},{\\\"id\\\":\\\"header-1\\\",\\\"type\\\":\\\"header\\\",\\\"title\\\":\\\"Ti\\\\u00eau \\\\u0111\\\\u1ec1 ch\\\\u00ednh\\\",\\\"settings\\\":{\\\"subtitle_label\\\":\\\"Ph\\\\u1ee5 \\\\u0111\\\\u1ec1\\\",\\\"badge_label\\\":\\\"Badge\\\"},\\\"content\\\":{\\\"title\\\":\\\"\\\",\\\"subtitle\\\":\\\"\\\",\\\"badge\\\":\\\"\\\"}},{\\\"id\\\":\\\"info-1\\\",\\\"type\\\":\\\"info\\\",\\\"title\\\":\\\"Th\\\\u00f4ng tin s\\\\u1ef1 ki\\\\u1ec7n\\\",\\\"settings\\\":{\\\"items\\\":[\\\"time\\\",\\\"location\\\",\\\"capacity\\\",\\\"points\\\"]},\\\"content\\\":{\\\"items\\\":[\\\"time\\\",\\\"location\\\",\\\"capacity\\\",\\\"points\\\"],\\\"custom_note\\\":\\\"\\\"}},{\\\"id\\\":\\\"description-1\\\",\\\"type\\\":\\\"description\\\",\\\"title\\\":\\\"N\\\\u1ed9i dung chi ti\\\\u1ebft\\\",\\\"settings\\\":{\\\"body_label\\\":\\\"N\\\\u1ed9i dung\\\"},\\\"content\\\":{\\\"heading\\\":\\\"N\\\\u1ed9i dung chi ti\\\\u1ebft\\\",\\\"body\\\":\\\"\\\"}},{\\\"id\\\":\\\"gallery-1\\\",\\\"type\\\":\\\"gallery\\\",\\\"title\\\":\\\"H\\\\u00ecnh \\\\u1ea3nh s\\\\u1ef1 ki\\\\u1ec7n\\\",\\\"settings\\\":{\\\"hint\\\":\\\"T\\\\u1ea3i nhi\\\\u1ec1u \\\\u1ea3nh cho ri\\\\u00eang kh\\\\u1ed1i n\\\\u00e0y\\\"},\\\"content\\\":{\\\"images\\\":[]}}]\", \"dia_diem\": \"Hội trường 3\", \"diem_cong\": \"2\", \"created_at\": \"2026-05-04 22:59:16\", \"ma_su_kien\": 13, \"trang_thai\": \"sap_to_chuc\", \"updated_at\": \"2026-05-04 22:59:16\", \"anh_su_kien\": null, \"ten_su_kien\": \"Hội thảo học tốt\", \"ma_nguoi_tao\": \"64131940\", \"mo_ta_chi_tiet\": null, \"ma_loai_su_kien\": \"1\", \"so_luong_toi_da\": \"200\", \"thoi_gian_bat_dau\": \"2026-05-04 23:00:00\", \"thoi_gian_ket_thuc\": \"2026-05-04 23:04:00\"}}', '2026-05-04 15:59:16'),
(187, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 11', 'App\\Models\\SuKien', '11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"bo_cuc\": \"[{\\\"id\\\":\\\"banner-1\\\",\\\"type\\\":\\\"banner\\\",\\\"title\\\":\\\"\\\\u1ea2nh b\\\\u00eca\\\",\\\"settings\\\":{\\\"caption_label\\\":\\\"Ch\\\\u00fa th\\\\u00edch \\\\u1ea3nh\\\"},\\\"content\\\":{\\\"caption\\\":\\\"\\\",\\\"image_path\\\":\\\"media\\\\/images\\\\/ae2In5CkBfanmSInMbB1QvJEyRyTKIpO1j3XuT8n.jpg\\\"}},{\\\"id\\\":\\\"documents-1777284094489-6fbe\\\",\\\"type\\\":\\\"documents\\\",\\\"title\\\":\\\"T\\\\u00e0i li\\\\u1ec7u \\\\u0111\\\\u00ednh k\\\\u00e8m 1\\\",\\\"settings\\\":{\\\"label\\\":\\\"T\\\\u00e0i li\\\\u1ec7u\\\"},\\\"content\\\":{\\\"items\\\":[{\\\"ma_phuong_tien\\\":38,\\\"ten_tep\\\":\\\"bao_cao_lop_64.CNTT-1_2026-04-04_09-33-13.xlsx\\\",\\\"duong_dan_tep\\\":\\\"media\\\\/documents\\\\/alxusrAKKMciZLUPEmU8TEL9oiZ3cUovYUHjQbtm.xlsx\\\",\\\"loai_tep\\\":\\\"tai_lieu\\\",\\\"kich_thuoc\\\":6768}]}},{\\\"id\\\":\\\"header-1\\\",\\\"type\\\":\\\"header\\\",\\\"title\\\":\\\"Ti\\\\u00eau \\\\u0111\\\\u1ec1 ch\\\\u00ednh\\\",\\\"settings\\\":{\\\"subtitle_label\\\":\\\"Ph\\\\u1ee5 \\\\u0111\\\\u1ec1\\\",\\\"badge_label\\\":\\\"Badge\\\"},\\\"content\\\":{\\\"title\\\":\\\"H\\\\u1ed9i th\\\\u1ea3o nghi\\\\u00ean c\\\\u1ee9u khoa h\\\\u1ecdc\\\",\\\"subtitle\\\":\\\"\\\",\\\"badge\\\":\\\"S\\\\u1ef1 ki\\\\u1ec7n th\\\\u01b0\\\\u1eddng ni\\\\u00ean\\\"}},{\\\"id\\\":\\\"info-1\\\",\\\"type\\\":\\\"info\\\",\\\"title\\\":\\\"Th\\\\u00f4ng tin s\\\\u1ef1 ki\\\\u1ec7n\\\",\\\"settings\\\":{\\\"items\\\":[\\\"time\\\",\\\"location\\\",\\\"capacity\\\",\\\"points\\\"]},\\\"content\\\":{\\\"items\\\":[\\\"time\\\",\\\"location\\\",\\\"capacity\\\",\\\"points\\\"],\\\"custom_note\\\":\\\"\\\"}},{\\\"id\\\":\\\"description-1\\\",\\\"type\\\":\\\"description\\\",\\\"title\\\":\\\"N\\\\u1ed9i dung chi ti\\\\u1ebft\\\",\\\"settings\\\":{\\\"body_label\\\":\\\"N\\\\u1ed9i dung\\\"},\\\"content\\\":{\\\"heading\\\":\\\"N\\\\u1ed9i dung chi ti\\\\u1ebft\\\",\\\"body\\\":\\\"media:993 Tracking Prevention blocked access to storage for https:\\\\/\\\\/cdn.jsdelivr.net\\\\/npm\\\\/bootstrap-icons@1.11.3\\\\/font\\\\/bootstrap-icons.min.css.\\\\r\\\\nmedia:993 Tracking Prevention blocked access to storage for https:\\\\/\\\\/cdn.jsdelivr.net\\\\/npm\\\\/bootstrap-icons@1.11.3\\\\/font\\\\/bootstrap-icons.min.css.\\\\r\\\\nmedia:993 Tracking Prevention blocked access to storage for https:\\\\/\\\\/cdn.jsdelivr.net\\\\/npm\\\\/bootstrap-icons@1.11.3\\\\/font\\\\/bootstrap-icons.min.css.\\\\r\\\\nmedia:993 Tracking Prevention blocked access to storage for https:\\\\/\\\\/cdn.jsdelivr.net\\\\/npm\\\\/bootstrap-icons@1.11.3\\\\/font\\\\/bootstrap-icons.min.css.\\\\r\\\\nmedia:1486 [Intervention] Images loaded lazily and replaced with placeholders. Load events are deferred. See https:\\\\/\\\\/go.microsoft.com\\\\/fwlink\\\\/?linkid=2048113\\\\r\\\\napp-CBbX3x7F.js:6 [LoadingInterceptor] \\\\u2713 Interceptors attached to axios.\\\\r\\\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=\\\\u200b\\\\\\\"file\\\\\\\" name=\\\\u200b\\\\\\\"file\\\\\\\" id=\\\\u200b\\\\\\\"fileInput\\\\\\\" required style=\\\\u200b\\\\\\\"display:\\\\u200bnone\\\\\\\">\\\\u200b\\\\r\\\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=\\\\u200b\\\\\\\"file\\\\\\\" name=\\\\u200b\\\\\\\"file\\\\\\\" id=\\\\u200b\\\\\\\"fileInput\\\\\\\" required style=\\\\u200b\\\\\\\"display:\\\\u200bnone\\\\\\\">\\\\u200b\\\\r\\\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=\\\\u200b\\\\\\\"file\\\\\\\" name=\\\\u200b\\\\\\\"file\\\\\\\" id=\\\\u200b\\\\\\\"fileInput\\\\\\\" required style=\\\\u200b\\\\\\\"display:\\\\u200bnone\\\\\\\">\\\"}},{\\\"id\\\":\\\"gallery-1\\\",\\\"type\\\":\\\"gallery\\\",\\\"title\\\":\\\"H\\\\u00ecnh \\\\u1ea3nh s\\\\u1ef1 ki\\\\u1ec7n\\\",\\\"settings\\\":{\\\"hint\\\":\\\"T\\\\u1ea3i nhi\\\\u1ec1u \\\\u1ea3nh cho ri\\\\u00eang kh\\\\u1ed1i n\\\\u00e0y\\\"},\\\"content\\\":{\\\"images\\\":[\\\"media\\\\/images\\\\/PDv4bpBvduvdh1vwxYqdH1CAHi3aPNhM0uqcGlX7.jpg\\\",\\\"media\\\\/images\\\\/qKkz8HoQsYS9pkbiSEeVSDxZYheFT2xejIIT46ZF.jpg\\\",\\\"media\\\\/images\\\\/MQslEZT9vQ1nvo5R1UqguxE7p0tfjdHgKLgwKL3I.jpg\\\"]}}]\"}, \"old\": {\"bo_cuc\": [{\"id\": \"banner-1\", \"type\": \"banner\", \"title\": \"Ảnh bìa\", \"content\": {\"caption\": \"\", \"image_path\": \"media/images/ae2In5CkBfanmSInMbB1QvJEyRyTKIpO1j3XuT8n.jpg\"}, \"settings\": {\"caption_label\": \"Chú thích ảnh\"}}, {\"id\": \"header-1\", \"type\": \"header\", \"title\": \"Tiêu đề chính\", \"content\": {\"badge\": \"Sự kiện thường niên\", \"title\": \"Hội thảo nghiên cứu khoa học\", \"subtitle\": \"\"}, \"settings\": {\"badge_label\": \"Badge\", \"subtitle_label\": \"Phụ đề\"}}, {\"id\": \"info-1\", \"type\": \"info\", \"title\": \"Thông tin sự kiện\", \"content\": {\"items\": [\"time\", \"location\", \"capacity\", \"points\"], \"custom_note\": \"\"}, \"settings\": {\"items\": [\"time\", \"location\", \"capacity\", \"points\"]}}, {\"id\": \"description-1\", \"type\": \"description\", \"title\": \"Nội dung chi tiết\", \"content\": {\"body\": \"media:993 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\\r\\nmedia:993 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\\r\\nmedia:993 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\\r\\nmedia:993 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\\r\\nmedia:1486 [Intervention] Images loaded lazily and replaced with placeholders. Load events are deferred. See https://go.microsoft.com/fwlink/?linkid=2048113\\r\\napp-CBbX3x7F.js:6 [LoadingInterceptor] ✓ Interceptors attached to axios.\\r\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=​\\\"file\\\" name=​\\\"file\\\" id=​\\\"fileInput\\\" required style=​\\\"display:​none\\\">​\\r\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=​\\\"file\\\" name=​\\\"file\\\" id=​\\\"fileInput\\\" required style=​\\\"display:​none\\\">​\\r\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=​\\\"file\\\" name=​\\\"file\\\" id=​\\\"fileInput\\\" required style=​\\\"display:​none\\\">\", \"heading\": \"Nội dung chi tiết\"}, \"settings\": {\"body_label\": \"Nội dung\"}}, {\"id\": \"gallery-1\", \"type\": \"gallery\", \"title\": \"Hình ảnh sự kiện\", \"content\": {\"images\": [\"media/images/PDv4bpBvduvdh1vwxYqdH1CAHi3aPNhM0uqcGlX7.jpg\", \"media/images/qKkz8HoQsYS9pkbiSEeVSDxZYheFT2xejIIT46ZF.jpg\", \"media/images/MQslEZT9vQ1nvo5R1UqguxE7p0tfjdHgKLgwKL3I.jpg\"]}, \"settings\": {\"hint\": \"Tải nhiều ảnh cho riêng khối này\"}}, {\"id\": \"documents-1777284094489-6fbe\", \"type\": \"documents\", \"title\": \"Tài liệu đính kèm 1\", \"content\": {\"items\": [{\"ten_tep\": \"bao_cao_lop_64.CNTT-1_2026-04-04_09-33-13.xlsx\", \"loai_tep\": \"tai_lieu\", \"kich_thuoc\": 6768, \"duong_dan_tep\": \"media/documents/alxusrAKKMciZLUPEmU8TEL9oiZ3cUovYUHjQbtm.xlsx\", \"ma_phuong_tien\": 38}]}, \"settings\": {\"label\": \"Tài liệu\"}}]}}', '2026-05-04 16:06:25');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `description`, `model_type`, `model_id`, `ip_address`, `user_agent`, `properties`, `created_at`) VALUES
(188, '64131940', 'Quản trị viên Demo', 'update', 'Cập nhật SuKien: 13', 'App\\Models\\SuKien', '13', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '{\"new\": {\"bo_cuc\": \"[{\\\"id\\\":\\\"banner-1\\\",\\\"type\\\":\\\"banner\\\",\\\"title\\\":\\\"\\\\u1ea2nh b\\\\u00eca\\\",\\\"settings\\\":{\\\"caption_label\\\":\\\"Ch\\\\u00fa th\\\\u00edch \\\\u1ea3nh\\\"},\\\"content\\\":{\\\"caption\\\":\\\"\\\",\\\"image_path\\\":\\\"media\\\\/images\\\\/C0Nw5PQYENxlbEvqVK4bmuTZmojTOMLVj1deo5EC.png\\\"}},{\\\"id\\\":\\\"header-1\\\",\\\"type\\\":\\\"header\\\",\\\"title\\\":\\\"Ti\\\\u00eau \\\\u0111\\\\u1ec1 ch\\\\u00ednh\\\",\\\"settings\\\":{\\\"subtitle_label\\\":\\\"Ph\\\\u1ee5 \\\\u0111\\\\u1ec1\\\",\\\"badge_label\\\":\\\"Badge\\\"},\\\"content\\\":{\\\"title\\\":\\\"\\\",\\\"subtitle\\\":\\\"\\\",\\\"badge\\\":\\\"\\\"}},{\\\"id\\\":\\\"info-1\\\",\\\"type\\\":\\\"info\\\",\\\"title\\\":\\\"Th\\\\u00f4ng tin s\\\\u1ef1 ki\\\\u1ec7n\\\",\\\"settings\\\":{\\\"items\\\":[\\\"time\\\",\\\"location\\\",\\\"capacity\\\",\\\"points\\\"]},\\\"content\\\":{\\\"items\\\":[\\\"time\\\",\\\"location\\\",\\\"capacity\\\",\\\"points\\\"],\\\"custom_note\\\":\\\"\\\"}},{\\\"id\\\":\\\"description-1\\\",\\\"type\\\":\\\"description\\\",\\\"title\\\":\\\"N\\\\u1ed9i dung chi ti\\\\u1ebft\\\",\\\"settings\\\":{\\\"body_label\\\":\\\"N\\\\u1ed9i dung\\\"},\\\"content\\\":{\\\"heading\\\":\\\"N\\\\u1ed9i dung chi ti\\\\u1ebft\\\",\\\"body\\\":\\\"\\\"}},{\\\"id\\\":\\\"gallery-1\\\",\\\"type\\\":\\\"gallery\\\",\\\"title\\\":\\\"H\\\\u00ecnh \\\\u1ea3nh s\\\\u1ef1 ki\\\\u1ec7n\\\",\\\"settings\\\":{\\\"hint\\\":\\\"T\\\\u1ea3i nhi\\\\u1ec1u \\\\u1ea3nh cho ri\\\\u00eang kh\\\\u1ed1i n\\\\u00e0y\\\"},\\\"content\\\":{\\\"images\\\":[]}}]\", \"anh_su_kien\": \"media/images/C0Nw5PQYENxlbEvqVK4bmuTZmojTOMLVj1deo5EC.png\"}, \"old\": {\"bo_cuc\": [{\"id\": \"banner-1\", \"type\": \"banner\", \"title\": \"Ảnh bìa\", \"content\": {\"caption\": \"\", \"image_path\": null}, \"settings\": {\"caption_label\": \"Chú thích ảnh\"}}, {\"id\": \"header-1\", \"type\": \"header\", \"title\": \"Tiêu đề chính\", \"content\": {\"badge\": \"\", \"title\": \"\", \"subtitle\": \"\"}, \"settings\": {\"badge_label\": \"Badge\", \"subtitle_label\": \"Phụ đề\"}}, {\"id\": \"info-1\", \"type\": \"info\", \"title\": \"Thông tin sự kiện\", \"content\": {\"items\": [\"time\", \"location\", \"capacity\", \"points\"], \"custom_note\": \"\"}, \"settings\": {\"items\": [\"time\", \"location\", \"capacity\", \"points\"]}}, {\"id\": \"description-1\", \"type\": \"description\", \"title\": \"Nội dung chi tiết\", \"content\": {\"body\": \"\", \"heading\": \"Nội dung chi tiết\"}, \"settings\": {\"body_label\": \"Nội dung\"}}, {\"id\": \"gallery-1\", \"type\": \"gallery\", \"title\": \"Hình ảnh sự kiện\", \"content\": {\"images\": []}, \"settings\": {\"hint\": \"Tải nhiều ảnh cho riêng khối này\"}}], \"anh_su_kien\": null}}', '2026-05-04 16:18:28');

-- --------------------------------------------------------

--
-- Table structure for table `bau_cu`
--

CREATE TABLE `bau_cu` (
  `ma_bau_cu` bigint UNSIGNED NOT NULL,
  `tieu_de` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `thoi_gian_bat_dau` datetime NOT NULL,
  `thoi_gian_ket_thuc` datetime NOT NULL,
  `so_chon_toi_thieu` int UNSIGNED NOT NULL DEFAULT '1',
  `so_chon_toi_da` int UNSIGNED NOT NULL DEFAULT '1',
  `hien_thi` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Hiển thị trên trang chủ',
  `hien_thi_ket_qua` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Hiển thị kết quả realtime',
  `trang_thai` enum('nhap','dang_dien_ra','hoan_thanh','huy') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nhap',
  `ma_nguoi_tao` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bau_cu`
--

INSERT INTO `bau_cu` (`ma_bau_cu`, `tieu_de`, `mo_ta`, `thoi_gian_bat_dau`, `thoi_gian_ket_thuc`, `so_chon_toi_thieu`, `so_chon_toi_da`, `hien_thi`, `hien_thi_ket_qua`, `trang_thai`, `ma_nguoi_tao`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Bầu cử BCH Đoàn Khoa CNTT', NULL, '2026-04-11 16:58:00', '2026-04-12 16:58:00', 1, 1, 1, 1, 'dang_dien_ra', '64131940', '2026-04-11 09:59:46', '2026-04-14 10:21:40', NULL),
(2, 'Bầu cử BCH Đoàn Khoa CNTT', NULL, '2026-04-16 21:41:00', '2026-04-17 21:41:00', 1, 1, 1, 0, 'nhap', '64131940', '2026-04-16 14:41:07', '2026-04-16 14:41:33', NULL),
(3, 'Bầu cử BCH Đoàn Khoa CNTT', NULL, '2026-04-17 22:15:00', '2026-04-18 22:15:00', 1, 1, 1, 0, 'nhap', '64131940', '2026-04-16 15:16:01', '2026-04-29 07:32:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_diem_danh`
--

CREATE TABLE `chi_tiet_diem_danh` (
  `ma_chi_tiet_diem_danh` bigint UNSIGNED NOT NULL,
  `ma_dang_ky` bigint UNSIGNED NOT NULL,
  `ma_su_kien` bigint UNSIGNED NOT NULL,
  `ma_sinh_vien` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loai_diem_danh` enum('dau_buoi','cuoi_buoi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dau_buoi',
  `diem_danh_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chi_tiet_diem_danh`
--

INSERT INTO `chi_tiet_diem_danh` (`ma_chi_tiet_diem_danh`, `ma_dang_ky`, `ma_su_kien`, `ma_sinh_vien`, `loai_diem_danh`, `diem_danh_at`) VALUES
(1, 15, 13, '64131999', 'dau_buoi', '2026-05-04 16:00:39');

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_phieu_bau`
--

CREATE TABLE `chi_tiet_phieu_bau` (
  `ma_chi_tiet` bigint UNSIGNED NOT NULL,
  `ma_phieu_bau` bigint UNSIGNED NOT NULL,
  `ma_ung_cu_vien` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chi_tiet_phieu_bau`
--

INSERT INTO `chi_tiet_phieu_bau` (`ma_chi_tiet`, `ma_phieu_bau`, `ma_ung_cu_vien`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-04-11 10:01:33', '2026-04-11 10:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `cu_tri`
--

CREATE TABLE `cu_tri` (
  `ma_cu_tri` bigint UNSIGNED NOT NULL,
  `ma_bau_cu` bigint UNSIGNED NOT NULL,
  `ma_sinh_vien` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `da_bo_phieu` tinyint(1) NOT NULL DEFAULT '0',
  `thoi_gian_bo_phieu` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cu_tri`
--

INSERT INTO `cu_tri` (`ma_cu_tri`, `ma_bau_cu`, `ma_sinh_vien`, `da_bo_phieu`, `thoi_gian_bo_phieu`, `created_at`, `updated_at`) VALUES
(1, 1, '64131941', 0, NULL, '2026-04-11 10:01:05', '2026-04-11 10:01:05'),
(2, 1, '64131942', 0, NULL, '2026-04-11 10:01:05', '2026-04-11 10:01:05'),
(16, 3, '64131942', 0, NULL, '2026-04-16 15:45:53', '2026-04-16 15:45:53'),
(17, 3, '64131947', 0, NULL, '2026-04-16 15:45:53', '2026-04-16 15:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `dang_ky`
--

CREATE TABLE `dang_ky` (
  `ma_dang_ky` bigint UNSIGNED NOT NULL,
  `ma_sinh_vien` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_su_kien` bigint UNSIGNED NOT NULL,
  `thoi_gian_dang_ky` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trang_thai_tham_gia` enum('da_dang_ky','da_tham_gia','vang_mat','chua_du_dieu_kien','huy') COLLATE utf8mb4_unicode_ci DEFAULT 'da_dang_ky',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dang_ky`
--

INSERT INTO `dang_ky` (`ma_dang_ky`, `ma_sinh_vien`, `ma_su_kien`, `thoi_gian_dang_ky`, `created_at`, `updated_at`, `trang_thai_tham_gia`, `deleted_at`) VALUES
(2, '64131940', 1, '2026-04-04 02:37:36', '2026-04-17 11:06:47', '2026-04-17 11:06:47', 'huy', '2026-04-17 10:49:08'),
(4, '64131940', 6, '2026-04-14 15:15:23', '2026-04-17 11:06:47', '2026-04-17 11:26:43', 'huy', '2026-04-17 11:26:43'),
(5, '64131940', 7, '2026-04-17 11:19:17', '2026-04-17 11:19:17', '2026-04-17 11:26:10', 'huy', '2026-04-17 11:26:10'),
(7, '64131940', 3, '2026-04-17 11:34:28', '2026-04-17 11:34:28', '2026-04-17 11:34:28', 'da_dang_ky', NULL),
(11, '64131942', 7, '2026-04-18 05:02:14', '2026-04-18 05:02:14', '2026-04-18 05:02:14', 'da_dang_ky', NULL),
(12, '64131940', 10, '2026-04-25 11:41:57', '2026-04-25 11:41:57', '2026-04-25 11:42:04', 'huy', '2026-04-25 11:42:04'),
(13, '64131940', 11, '2026-04-27 12:21:39', '2026-04-27 12:21:39', '2026-04-27 12:21:39', 'da_dang_ky', NULL),
(14, '64131999', 12, '2026-05-02 15:15:27', '2026-05-02 15:15:27', '2026-05-02 15:16:38', 'da_tham_gia', NULL),
(15, '64131999', 13, '2026-05-04 15:59:47', '2026-05-04 15:59:47', '2026-05-04 16:00:39', 'da_tham_gia', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gemini_settings`
--

CREATE TABLE `gemini_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `api_key` text COLLATE utf8mb4_unicode_ci,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gemini-2.5-flash',
  `system_prompt` text COLLATE utf8mb4_unicode_ci,
  `temperature` decimal(3,2) NOT NULL DEFAULT '0.40',
  `max_output_tokens` int UNSIGNED NOT NULL DEFAULT '512',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gemini_settings`
--

INSERT INTO `gemini_settings` (`id`, `api_key`, `model`, `system_prompt`, `temperature`, `max_output_tokens`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'eyJpdiI6Ik5Mdi9pL2pVRXMyRGZteDdrMThKM3c9PSIsInZhbHVlIjoib1JXZFVLK3JONUZoTldJMWxhWGtkM3QvT1BRd1EzOUhnM2xFTFpPcmhnL042dXVNWTU2VnhNL2I0YXZ3WHIrSyIsIm1hYyI6IjQwZTRmNWVjZjM2N2JjMjI4NzM5NTkzMzdmMDVkNWI2OWM5ODIzYTUwMTYzMzU4M2JlOWQxNWFmNjJiM2NjMjYiLCJ0YWciOiIifQ==', 'gemini-2.5-flash', 'Bạn là trợ lý AI của hệ thống Quản Lý Sự Kiện Khoa CNTT.\r\n\r\nMục tiêu:\r\n- Trả lời ngắn gọn, rõ ràng, bằng tiếng Việt.\r\n- Chỉ trả lời các câu hỏi cơ bản liên quan đến sự kiện, đăng ký, địa điểm, thời gian, trạng thái tham gia, điểm cộng và lịch sử tham gia.\r\n- Chỉ dùng thông tin được cung cấp trong ngữ cảnh hệ thống.\r\n- Nếu dữ liệu không có trong ngữ cảnh, hãy nói rõ rằng bạn chưa có đủ thông tin và hướng người dùng xem trang sự kiện hoặc liên hệ ban tổ chức.\r\n\r\nQuy tắc:\r\n- Không bịa thông tin.\r\n- Không trả lời ngoài phạm vi hệ thống sự kiện.\r\n- Nếu người dùng hỏi nhiều ý, trả lời theo từng ý ngắn gọn.', '1.00', 800, 1, '2026-04-22 14:53:24', '2026-04-25 11:05:53');

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_diem`
--

CREATE TABLE `lich_su_diem` (
  `ma_lich_su_diem` bigint UNSIGNED NOT NULL,
  `ma_sinh_vien` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_dang_ky` bigint UNSIGNED DEFAULT NULL,
  `diem` int NOT NULL DEFAULT '0',
  `nguon` enum('tham_gia_su_kien','thuong_them','phat_tru','he_thong') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tham_gia_su_kien',
  `loai_log` enum('diem','system','chatbot') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'diem',
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `context` json DEFAULT NULL,
  `thoi_gian_ghi_nhan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loai_su_kien`
--

CREATE TABLE `loai_su_kien` (
  `ma_loai_su_kien` bigint UNSIGNED NOT NULL,
  `ten_loai` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loai_su_kien`
--

INSERT INTO `loai_su_kien` (`ma_loai_su_kien`, `ten_loai`, `mo_ta`, `created_at`, `updated_at`) VALUES
(1, 'Hội thảo', 'Các buổi hội thảo học thuật và chuyên đề', '2026-04-03 19:09:56', '2026-04-03 19:09:56'),
(2, 'Seminar', 'Seminar nghiên cứu và trao đổi kiến thức', '2026-04-03 19:09:56', '2026-04-03 19:09:56'),
(3, 'Câu lạc bộ', 'Hoạt động câu lạc bộ sinh viên', '2026-04-03 19:09:56', '2026-04-03 19:09:56'),
(4, 'Ngoại khóa', 'Hoạt động ngoại khóa và tình nguyện', '2026-04-03 19:09:56', '2026-04-03 19:09:56'),
(5, 'Thi đấu', 'Cuộc thi học thuật và kỹ năng', '2026-04-03 19:09:56', '2026-04-03 19:09:56'),
(6, 'Workshop', 'Buổi thực hành kỹ năng thực tế', '2026-04-03 19:09:56', '2026-04-03 19:09:56');

-- --------------------------------------------------------

--
-- Table structure for table `mau_bai_dang`
--

CREATE TABLE `mau_bai_dang` (
  `ma_mau` bigint UNSIGNED NOT NULL,
  `ten_mau` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `noi_dung` longtext COLLATE utf8mb4_unicode_ci,
  `ma_nguoi_tao` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ma_loai_su_kien` bigint UNSIGNED DEFAULT NULL,
  `dia_diem` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_luong_toi_da` int NOT NULL DEFAULT '0',
  `diem_cong` int NOT NULL DEFAULT '0',
  `anh_su_kien` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bo_cuc` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mau_bai_dang`
--

INSERT INTO `mau_bai_dang` (`ma_mau`, `ten_mau`, `noi_dung`, `ma_nguoi_tao`, `ma_loai_su_kien`, `dia_diem`, `so_luong_toi_da`, `diem_cong`, `anh_su_kien`, `bo_cuc`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Mẫu Hội Thảo', NULL, '64131940', 1, 'Hội trường 3', 200, 2, 'su_kien/QqDUG9T4jsCGQAD4KQ5Q9MJniORzuojpyyDAVjRu.jpg', '[{\"id\": \"banner-1\", \"type\": \"banner\", \"title\": \"Ảnh bìa\", \"content\": [], \"settings\": {\"caption_label\": \"Chú thích ảnh\"}}, {\"id\": \"header-1\", \"type\": \"header\", \"title\": \"Tiêu đề chính\", \"content\": [], \"settings\": {\"badge_label\": \"Badge\", \"subtitle_label\": \"Phụ đề\"}}, {\"id\": \"info-1\", \"type\": \"info\", \"title\": \"Thông tin sự kiện\", \"content\": [], \"settings\": {\"items\": [\"time\", \"location\", \"capacity\", \"points\"]}}, {\"id\": \"description-1\", \"type\": \"description\", \"title\": \"Nội dung chi tiết\", \"content\": [], \"settings\": {\"body_label\": \"Nội dung\"}}, {\"id\": \"gallery-1\", \"type\": \"gallery\", \"title\": \"Hình ảnh sự kiện\", \"content\": [], \"settings\": {\"hint\": \"Tải nhiều ảnh cho riêng khối này\"}}]', '2026-04-25 12:31:27', '2026-04-25 12:31:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2026_02_22_232058_create_nguoi_dung_table', 1),
(5, '2026_02_22_232128_create_loai_su_kien_table', 1),
(6, '2026_02_22_232136_create_su_kien_table', 1),
(7, '2026_02_22_232202_create_dang_ky_table', 1),
(8, '2026_02_22_232208_create_lich_su_diem_table', 1),
(9, '2026_02_22_232215_create_thu_vien_da_phuong_tien_table', 1),
(10, '2026_02_22_232228_create_thong_bao_table', 1),
(11, '2026_03_15_000001_create_bau_cu_table', 1),
(12, '2026_03_15_000002_create_ung_cu_vien_table', 1),
(13, '2026_03_15_000003_create_cu_tri_table', 1),
(14, '2026_03_15_000004_create_phieu_bau_table', 1),
(15, '2026_03_15_000005_create_chi_tiet_phieu_bau_table', 1),
(16, '2026_03_16_000010_add_qr_fields_to_su_kien_table', 1),
(17, '2026_03_19_000000_add_email_verified_to_nguoi_dung_table', 1),
(18, '2026_04_01_000001_add_lop_to_nguoi_dung_table', 1),
(19, '2026_04_04_100000_use_mssv_as_user_primary_key', 2),
(20, '2026_04_04_110000_rename_user_foreign_columns_to_mssv', 3),
(21, '2026_04_08_000001_create_smtp_settings_table', 4),
(22, '2026_04_08_000002_create_activity_logs_table', 4),
(23, '2026_04_17_add_created_at_to_dang_ky_table', 5),
(24, '2026_04_17_add_chua_du_dieu_kien_status_to_dang_ky', 6),
(25, '2026_04_17_create_chi_tiet_diem_danh_table', 6),
(26, '2026_04_17_000001_add_mail_content_to_smtp_settings_table', 7),
(27, '2026_04_22_000001_create_gemini_settings_table', 8),
(29, '2026_04_25_000001_create_the_anh_table', 9),
(30, '2026_04_25_000002_create_the_anh_thu_vien_table', 9),
(31, '2026_04_25_120000_create_mau_bai_dang_table', 9),
(32, '2026_04_27_000000_drop_la_cong_khai_from_thu_vien_da_phuong_tien', 10);

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `vai_tro` enum('admin','sinh_vien') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sinh_vien',
  `ma_sinh_vien` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lop` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lớp của sinh viên (ví dụ: 64.CNTT-1)',
  `ho_ten` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `mat_khau` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_dien_thoai` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trang_thai` enum('hoat_dong','khong_hoat_dong','bi_khoa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hoat_dong',
  `duong_dan_anh` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`vai_tro`, `ma_sinh_vien`, `lop`, `ho_ten`, `email`, `email_verified_at`, `mat_khau`, `so_dien_thoai`, `trang_thai`, `duong_dan_anh`, `created_at`, `updated_at`, `deleted_at`) VALUES
('admin', '64131940', '64.CNTT-1', 'Quản trị viên Demo', 'admin@example.com', '2026-04-03 17:26:16', '$2y$10$1/25Zcy8oq6CLvkQNMKNfuhodzTmOybl9aOovO0iOIcb/Me3rei.u', NULL, 'hoat_dong', 'avatars/f0KBGI5LaWeTz4RxHX1a1DMZSz1IFT2gdJ64PBQa.jpg', '2026-04-03 17:26:16', '2026-04-13 15:12:15', NULL),
('sinh_vien', '64131941', '64.CNTT-1', 'Sinh viên Demo', 'student@example.com', '2026-04-03 17:26:16', '$2y$10$1/25Zcy8oq6CLvkQNMKNfuhodzTmOybl9aOovO0iOIcb/Me3rei.u', NULL, 'hoat_dong', NULL, '2026-04-03 17:26:16', '2026-04-03 17:26:16', NULL),
('sinh_vien', '64131942', '64.CNTT-1', 'Dương Phú Quảng', 'quang.dp.64cntt@ntu.edu.vn', '2026-04-03 17:40:02', '$2y$12$Mc4Y.L..j/vPavu3kUAdTeDkQ7WqTbbe/0sZYjB1JjJql67/n.tg6', '0929363175', 'hoat_dong', 'avatars/sobcGQ3mdRwOw8y01Sl8J3PbNT9k1Kx2UZ3Ksxj3.jpg', '2026-04-03 17:39:13', '2026-04-29 07:40:12', NULL),
('sinh_vien', '64131947', '64.CNTT-1', 'Dương Phú Quảng', 'a@gmaill.com', NULL, '$2y$12$z3CokP5CfVf9vHb/zgqnhOys1RXqQqu.kThbb3Njk2AKVHh0HpI5W', NULL, 'hoat_dong', NULL, '2026-04-13 12:45:02', '2026-04-13 12:45:02', NULL),
('sinh_vien', '64131999', '64.CNTT-1', 'Dương Phú Quảng', 'duongquangpy11@gmail.com', '2026-05-02 15:12:14', '$2y$12$inZ0Pw90cjlTJJvKmnYzT.E9zs2l2H0gIrn/p4wF5Akf2QkpNAx.e', '0929363175', 'hoat_dong', 'avatars/FrWE5JATRs6dnJXgTKFLVJy5kMNBcbSeouEjr5c3.jpg', '2026-05-02 15:11:49', '2026-05-04 15:57:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('quang.dp.64cntt@ntu.edu.vn', '$2y$12$lQx91RF28IIkI3sNjAKeveKZ1PsaJkA/.8O6uQaidS2VTlrtR3zYa', '2026-04-13 12:46:21');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(2, 'App\\Models\\User', '64131999', 'mobile-app', '347e9786e47f066c2fcb54d8473ef5aa7ab6cdcd4076cc9e2ecffb385f366cad', '[\"*\"]', '2026-05-04 16:23:55', NULL, '2026-05-02 15:12:52', '2026-05-04 16:23:55');

-- --------------------------------------------------------

--
-- Table structure for table `phieu_bau`
--

CREATE TABLE `phieu_bau` (
  `ma_phieu_bau` bigint UNSIGNED NOT NULL,
  `ma_bau_cu` bigint UNSIGNED NOT NULL,
  `hash_ip` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hash IP người bỏ phiếu',
  `thoi_gian_gui` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phieu_bau`
--

INSERT INTO `phieu_bau` (`ma_phieu_bau`, `ma_bau_cu`, `hash_ip`, `thoi_gian_gui`, `created_at`, `updated_at`) VALUES
(1, 1, '365f4fa03fbffdd5cd97c88c166a42c0d2e426570cbab07419432922e328f7f4', '2026-04-11 17:01:33', '2026-04-11 10:01:33', '2026-04-11 10:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `smtp_settings`
--

CREATE TABLE `smtp_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `mail_host` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'smtp.gmail.com',
  `mail_port` int NOT NULL DEFAULT '587',
  `mail_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_password` text COLLATE utf8mb4_unicode_ci,
  `mail_encryption` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tls',
  `mail_from_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_from_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Quản Lý Sự Kiện',
  `mail_header` text COLLATE utf8mb4_unicode_ci,
  `mail_body_template` text COLLATE utf8mb4_unicode_ci,
  `mail_footer` text COLLATE utf8mb4_unicode_ci,
  `mail_signature` text COLLATE utf8mb4_unicode_ci,
  `subject_welcome` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_event_confirm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_event_cancel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_event_update` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `smtp_settings`
--

INSERT INTO `smtp_settings` (`id`, `mail_host`, `mail_port`, `mail_username`, `mail_password`, `mail_encryption`, `mail_from_address`, `mail_from_name`, `mail_header`, `mail_body_template`, `mail_footer`, `mail_signature`, `subject_welcome`, `subject_event_confirm`, `subject_event_cancel`, `subject_event_update`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'smtp.gmail.com', 587, 'phuquangpy11@gmail.com', 'eyJpdiI6IkFUdWFtMmtOUVkwek0vR2R1blFMV1E9PSIsInZhbHVlIjoiRG1JdUtsWmpUWGRBbEQzTDY1Y0pLQzZ3NnM3ZlFGY0Z3eklLakF1aks5MD0iLCJtYWMiOiI1NDBiNTMzMjYzMGY5NjBmYjY1ZTQxNWE4NmM5ZmJjMWMwMDEzZWRmZGJhNWE3MDZmMTQ4MzA1NjQwYTFlNGMyIiwidGFnIjoiIn0=', 'ssl', 'phuquangpy11@gmail.com', 'Dương Phú Quảng QLSK', '<h2 style=\"color: #007bff;\">Quản Lý Sự Kiện NTU</h2>\r\n<p style=\"color: #666;\">Khoa Công Nghệ Thông Tin</p>', 'Sau khi xác thực email, bạn sẽ có thể:\r\n                    - Tham gia các sự kiện\r\n                    - Xem điểm tích lũy\r\n                    - Quản lý hồ sơ cá nhân', '© 2026 Khoa Công Nghệ Thông Tin - Đại học Nha Trang', 'Đội ngũ Quản Lý Sự Kiện\r\nKhoa Công Nghệ Thông Tin - NTU', 'Chào mừng bạn đến với Quản Lý Sự Kiện', 'Xác nhận đăng ký tham gia: {event_name}', 'Sự kiện {event_name} đã bị hủy', 'Cập nhật thông tin: {event_name}', 1, '2026-04-08 11:58:39', '2026-04-17 15:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `su_kien`
--

CREATE TABLE `su_kien` (
  `ma_su_kien` bigint UNSIGNED NOT NULL,
  `ten_su_kien` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta_chi_tiet` longtext COLLATE utf8mb4_unicode_ci,
  `ma_loai_su_kien` bigint UNSIGNED NOT NULL,
  `la_mau_bai_dang` tinyint(1) NOT NULL DEFAULT '0',
  `thoi_gian_bat_dau` datetime NOT NULL,
  `thoi_gian_ket_thuc` datetime NOT NULL,
  `dia_diem` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anh_su_kien` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bo_cuc` text COLLATE utf8mb4_unicode_ci,
  `qr_checkin_token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_code_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_luong_toi_da` int NOT NULL DEFAULT '0',
  `so_luong_hien_tai` int NOT NULL DEFAULT '0',
  `diem_cong` int NOT NULL DEFAULT '0',
  `ma_nguoi_tao` char(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ma_nguoi_to_chuc` char(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trang_thai` enum('sap_to_chuc','dang_dien_ra','da_ket_thuc','huy') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sap_to_chuc',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_kien`
--

INSERT INTO `su_kien` (`ma_su_kien`, `ten_su_kien`, `mo_ta_chi_tiet`, `ma_loai_su_kien`, `la_mau_bai_dang`, `thoi_gian_bat_dau`, `thoi_gian_ket_thuc`, `dia_diem`, `anh_su_kien`, `bo_cuc`, `qr_checkin_token`, `qr_code_path`, `so_luong_toi_da`, `so_luong_hien_tai`, `diem_cong`, `ma_nguoi_tao`, `ma_nguoi_to_chuc`, `trang_thai`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Hội thảo học tốt', '<p>Bố cục hồ sơ được thiết kế theo hướng dung hòa giữa nét cổ điển của hệ thống và cảm hứng <strong>hiện đại từ các mẫu trong thư mục </strong><code><strong>img</strong></code><strong>, tạo nên một không gian trực quan, rõ ràng nhưng vẫn có chiều sâu. Ảnh đại diện trở thành điểm nhấn trung tâm, giúp nhận diện cá nhân một cách trực tiếp, trong khi các thông tin học tập được tổ chức mạch lạc để phản ánh quá trình tham gia và phát triển của người dùng. Bên cạnh đó, các tín hiệu hoạt động gần đây như đăng ký sự kiện, điểm danh hay tương tác được hiển thị có chọn lọc, giúp người dùng dễ dàng theo dõi hành trình của mình trên hệ thống. Tổng thể, trang hồ sơ không chỉ là nơi lưu trữ thông tin mà còn là một “bản ghi” trực quan về quá trình học tập và tham gia hoạt động trong môi trường học thuật.</strong></p>', 1, 0, '2026-04-04 02:10:00', '2026-04-05 02:10:00', 'Hội trường 3', 'su_kien/uYIEqLPTMuQliYTNQDiq5vDp8FcL2rlGWz1Q2jGy.jpg', '[\"banner\",\"header\",\"info\",\"description\",\"gallery\",\"gallery\",\"gallery\",\"description\",\"description\",\"description\"]', NULL, NULL, 123, 0, 2, '64131940', NULL, 'sap_to_chuc', '2026-04-03 19:10:49', '2026-04-17 10:49:08', NULL),
(2, 'WorkShop', '<p>Vì đây là hoạt động chính trị rất quan trọng, các bạn cần triển khai gấp thông báo triệu tập bổ sung này đến lớp, và đôn đốc sinh viên tham dự đầy đủ nhé. Ban Cán sự theo dõi link đăng ký tham gia để nắm bắt sinh viên tham dự buổi tiếp xúc và cộng điểm rèn luyện cho các bạn tham gia.</p><p>Thời gian đăng ký:&nbsp;Đến 08h ngày 06/03/2026.</p><p>1. Thông tin về buổi tiếp xúc cử tri:</p><p>- Thời gian: 07h45 ngày 07/03/2026</p><p>- Địa điểm: Hội trường số 3, Trường Đại học Nha Trang</p><p>- Số lượng sinh viên tham dự: 40 Sinh viên</p><p>Link đăng ký tham gia:&nbsp;<a href=\"https://docs.google.com/spreadsheets/d/1ncYBc74J37vzUEZ-r-mRp3TbzTlsPIjVNrFNSrLncKg/edit?usp=sharing\" target=\"_blank\" style=\"color: rgb(17, 85, 204);\">https://docs.google.com/spreadsheets/d/1ncYBc74J37vzUEZ-r-mRp3TbzTlsPIjVNrFNSrLncKg/edit?usp=sharing</a>&nbsp;</p><p><br></p><p>2. Yêu cầu đối với sinh viên tham dự:</p><p>- Tham gia đầy đủ chương trình, không rời khỏi hội trường khi chưa kết thúc buổi tiếp xúc cử tri.</p><p>- Trang phục: mặc áo Đoàn Thanh niên</p><p>- Có mặt đúng giờ, giữ gìn trật tự, tác phong nghiêm túc.</p><p>--</p><p><strong><em>Đoàn Khoa Công nghệ Thông tin, Trường Đại học Nha Trang</em></strong></p><p><strong><em>Văn phòng: Phòng 708, Nhà Đa năng (NĐN.708)</em></strong></p><p><a href=\"https://facebook.com/DoanHoiFIT.NTU\" target=\"_blank\" style=\"color: rgb(17, 85, 204);\"><em>Fanpage chính thức của Đoàn khoa Công nghệ Thông tin</em></a></p>', 6, 0, '2026-04-14 19:49:00', '2026-04-15 19:49:00', 'Hội trường 4', NULL, '[\"banner\",\"header\",\"info\",\"description\",\"gallery\",\"info\",\"info\",\"header\",\"description\",\"banner\"]', NULL, NULL, 100, 0, 5, '64131940', NULL, 'sap_to_chuc', '2026-04-13 12:56:02', '2026-04-13 12:56:02', NULL),
(3, 'WorkShop23', '<p>Vì đây là hoạt động chính trị rất quan trọng, các bạn cần triển khai gấp thông báo triệu tập bổ sung này đến lớp, và đôn đốc sinh viên tham dự đầy đủ nhé. Ban Cán sự theo dõi link đăng ký tham gia để nắm bắt sinh viên tham dự buổi tiếp xúc và cộng điểm rèn luyện cho các bạn tham gia.</p><p>Thời gian đăng ký:&nbsp;Đến 08h ngày 06/03/2026.</p><p>1. Thông tin về buổi tiếp xúc cử tri:</p><p>- Thời gian: 07h45 ngày 07/03/2026</p><p>- Địa điểm: Hội trường số 3, Trường Đại học Nha Trang</p><p>- Số lượng sinh viên tham dự: 40 Sinh viên</p><p>Link đăng ký tham gia:&nbsp;<a href=\"https://docs.google.com/spreadsheets/d/1ncYBc74J37vzUEZ-r-mRp3TbzTlsPIjVNrFNSrLncKg/edit?usp=sharing\" target=\"_blank\" style=\"color: rgb(17, 85, 204);\">https://docs.google.com/spreadsheets/d/1ncYBc74J37vzUEZ-r-mRp3TbzTlsPIjVNrFNSrLncKg/edit?usp=sharing</a>&nbsp;</p><p><br></p><p>2. Yêu cầu đối với sinh viên tham dự:</p><p>- Tham gia đầy đủ chương trình, không rời khỏi hội trường khi chưa kết thúc buổi tiếp xúc cử tri.</p><p>- Trang phục: mặc áo Đoàn Thanh niên</p><p>- Có mặt đúng giờ, giữ gìn trật tự, tác phong nghiêm túc.</p><p>--</p><p><strong><em>Đoàn Khoa Công nghệ Thông tin, Trường Đại học Nha Trang</em></strong></p><p><strong><em>Văn phòng: Phòng 708, Nhà Đa năng (NĐN.708)</em></strong></p><p><a href=\"https://facebook.com/DoanHoiFIT.NTU\" target=\"_blank\" style=\"color: rgb(17, 85, 204);\"><em>Fanpage chính thức của Đoàn khoa Công nghệ Thông tin</em></a></p>', 3, 0, '2026-04-17 18:36:00', '2026-04-17 18:37:00', 'Hội trường 2', NULL, '[\"banner\",\"gallery\",\"header\",\"info\",\"description\",\"banner\"]', NULL, NULL, 1234567890, 1, 2, '64131940', NULL, 'sap_to_chuc', '2026-04-13 13:24:42', '2026-04-17 11:35:12', NULL),
(4, 'Hội thảo học tốt', '<p><span style=\"background-color: rgb(248, 250, 252); color: rgb(220, 38, 38);\">Lỗi tải ảnh từ nguồn khác (CORS chặn). Vui lòng lưu hình về máy trước khi kéo thả tải lên.</span></p>', 1, 0, '2026-04-13 20:40:00', '2026-04-14 20:40:00', 'Hội trường 3', 'su_kien/k49bjnH4SOYIwjVvI7YkZHUlWhg06mdHw3ajYBIk.png', '[\"header\",\"info\",\"description\",\"gallery\",\"header\",\"header\",\"header\",\"header\",\"header\",\"header\",\"header\"]', NULL, NULL, 1, 0, 1, '64131940', NULL, 'sap_to_chuc', '2026-04-13 13:56:33', '2026-04-13 15:17:03', NULL),
(5, 'Test Event 2026-1', '<p>Vì đây là hoạt động chính trị rất quan trọng, các bạn cần triển khai gấp thông báo triệu tập bổ sung này đến lớp, và đôn đốc sinh viên tham dự đầy đủ nhé. Ban Cán sự theo dõi link đăng ký tham gia để nắm bắt sinh viên tham dự buổi tiếp xúc và cộng điểm rèn luyện cho các bạn tham gia.</p><p>Thời gian đăng ký:&nbsp;Đến 08h ngày 06/03/2026.</p><p>1. Thông tin về buổi tiếp xúc cử tri:</p><p>- Thời gian: 07h45 ngày 07/03/2026</p><p>- Địa điểm: Hội trường số 3, Trường Đại học Nha Trang</p><p>- Số lượng sinh viên tham dự: 40 Sinh viên</p><p>Link đăng ký tham gia:&nbsp;<a href=\"https://docs.google.com/spreadsheets/d/1ncYBc74J37vzUEZ-r-mRp3TbzTlsPIjVNrFNSrLncKg/edit?usp=sharing\" target=\"_blank\" style=\"color: rgb(17, 85, 204);\">https://docs.google.com/spreadsheets/d/1ncYBc74J37vzUEZ-r-mRp3TbzTlsPIjVNrFNSrLncKg/edit?usp=sharing</a>&nbsp;</p><p><br></p><p>2. Yêu cầu đối với sinh viên tham dự:</p><p>- Tham gia đầy đủ chương trình, không rời khỏi hội trường khi chưa kết thúc buổi tiếp xúc cử tri.</p><p>- Trang phục: mặc áo Đoàn Thanh niên</p><p>- Có mặt đúng giờ, giữ gìn trật tự, tác phong nghiêm túc.</p><p>--</p><p><strong><em>Đoàn Khoa Công nghệ Thông tin, Trường Đại học Nha Trang</em></strong></p><p><strong><em>Văn phòng: Phòng 708, Nhà Đa năng (NĐN.708)</em></strong></p><p><a href=\"https://facebook.com/DoanHoiFIT.NTU\" target=\"_blank\" style=\"color: rgb(17, 85, 204);\"><em>Fanpage chính thức của Đoàn khoa Công nghệ Thông tin</em></a></p>', 4, 0, '2026-04-12 21:02:00', '2026-04-13 21:02:00', 'Lab A5', 'su_kien/rAZWNCnxT7o3WSb80FxDl5GOgeIg9AePY6WxFOl8.png', '[\"banner\",\"banner\",\"header\",\"info\",\"description\",\"gallery\"]', NULL, NULL, 1, 0, 15, '64131940', NULL, 'sap_to_chuc', '2026-04-13 14:04:41', '2026-04-13 14:05:13', NULL),
(6, 'WorkShop234', '<p>http://ql_su_kien.test/admin/su-kien/create</p><p>&nbsp; - Thư viện media bị trùng ảnh khi dùng lại ảnh cũ</p><p>&nbsp; - Các mô đun phụ trợ được thêm vào có thể điền nội dung khác thay vì copy mô dun đã có</p><p>http://ql_su_kien.test/admin/nguoi-dung</p><p>&nbsp; - Thêm phương thức truy vấn dữ liệu nhanh theo tên. lớp, mssv</p><p>&nbsp; - Điều chỉnh bố cục các cột phù hợp</p><p>&nbsp; - trong phần thêm mới người dùng bắt buộc nhập đầy dủ thông tin như đăng ký tài khoản và có xác thực smtp</p><p>&nbsp; - Bỏ chức năng import file excel</p><p>&nbsp; - Hỗ trợ hiện ảnh đại điện người dùng</p><p>http://ql_su_kien.test/admin/media</p><p>&nbsp; - Xử lý ảnh bị trùng lặp khi tái sử dụng </p><p>&nbsp; - Căn giữa cho phần phân trang</p><p>&nbsp; - hiển thị 10 ảnh 1 trang </p><p>&nbsp; - Tính năng upload cải thiện tự động phân loại </p><p>http://ql_su_kien.test/admin/templates</p><p>&nbsp; - Bỏ chức năng này thay bằng chức năng khác có ích hơn</p><p>&nbsp; - Đồng bộ thay đổi cho các trang liên quan</p><p>http://ql_su_kien.test/admin/bau-cu/1/edit</p><p>&nbsp; - cập nhật giao diện trang này cho phù hợp với bố cục hiện tại</p><p>http://ql_su_kien.test/admin/bau-cu/1</p><p>&nbsp; - cho phép chọn cử tri từ danh sách sinh viên</p><p>http://ql_su_kien.test/admin/bao-cao</p><p>&nbsp; - Thêm hiệu ứng load trang khi xuất file</p><p>&nbsp; - Nếu file không có thông tin gì thì không cần xuất và thông báo cho người dùng</p><p>http://ql_su_kien.test/admin/thong-ke</p><p>&nbsp; - Điều chỉnh lại các biểu đồ thống kê trực quan, chuyên nghiệp</p><p>http://ql_su_kien.test/admin/diem-danh</p><p>&nbsp; - Khi chọn QR sự kiện -&gt; phóng to màn hình để sinh viên dễ quét hơn</p><p>&nbsp; - QR chỉ được bởi ứng dụng</p><p>http://ql_su_kien.test/admin/diem-danh/scanner</p><p>&nbsp; - sửa lỗi : Lỗi truy cập máy ảnh: Camera streaming not supported by the browser.</p><p>http://ql_su_kien.test/admin/smtp</p><p>&nbsp; - Thêm hướng dẫn người dùng cách cài smtp</p><p>http://ql_su_kien.test/admin/activity-logs?search=&amp;action=&amp;from=2026-04-13&amp;to=2026-04-12</p><p>&nbsp; - sửa lại logic phần lọc thông tin cho đúng logic thực tế</p><p>&nbsp; - Chỉ ghi lại các log của Admin</p><p><br></p><p>&nbsp; &nbsp; </p><p><br></p>', 1, 0, '2026-04-15 22:11:00', '2026-04-16 22:12:00', 'Hội trường 4', 'su_kien/sBzCnQXj2XWCVBjIEBLgdOqcmIqevCebmyIGSxF7.jpg', '[\"banner\",\"header\",\"info\",\"description\",\"gallery\",\"banner\"]', NULL, NULL, 100, 0, 5, '64131940', NULL, 'sap_to_chuc', '2026-04-14 15:12:26', '2026-04-17 11:26:43', NULL),
(7, 'WorkShop234', '<p>bao-cao:27 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.</p><p>bao-cao:27 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.</p><p>bao-cao:27 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.</p><p>bao-cao:27 Tracking Prevention blocked access to storage for<a href=\" https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\" target=\"_blank\"> https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.</a></p><p>app-CBbX3x7F.js:6 [LoadingInterceptor] ✓ Interceptors attached to axios.</p><p>bao-cao:1 The file at \'blob:http://ql_su_kien.test/c5ac4f8f-3f6a-4d40-9c20-642fb75635f6\' was loaded over an insecure connection. This file should be served over HTTPS.</p><p><br></p>', 3, 0, '2026-04-18 06:07:00', '2026-04-19 00:25:00', 'Hội trường 4', 'su_kien/6erhsh72tWuy6RQT7Y7bxFYvfTAePPbGCKvgsjtF.jpg', '[\"gallery\",\"banner\",\"header\",\"info\",\"description\"]', NULL, NULL, 100, 2, 5, '64131940', NULL, 'sap_to_chuc', '2026-04-16 16:08:01', '2026-04-18 05:02:14', NULL),
(10, 'Hội thảo học tốt', 'Hãy đọc  dụ án , chú trọng chúc năng trợ lý AI thực hiện các thay đổi sau :\r\n- Thay vì lúc nào cũng gửi yêu cầu về AI thì hãy tụ trả lời các câu hỏi có sẵn bàng dữ liệu có sẵn, dùng các câu lệnh sql để lấy dữ liệu từ sự kiện để trả lời cho các câu hỏi \r\n- Tối ưu hóa câu hỏi để giảm token cho các câu trả lời\r\n- Đưa thêm các giải pháp tối ưu', 1, 0, '2026-04-26 18:38:00', '2026-04-26 20:38:00', 'Hội trường 3', 'su_kien/modules/banner/lq0tnbPFAti190H2gmoohK8PFtqJYz8WcxJ3zubL.jpg', '[{\"id\":\"banner-1\",\"type\":\"banner\",\"title\":\"\\u1ea2nh b\\u00eca\",\"settings\":{\"caption_label\":\"Ch\\u00fa th\\u00edch \\u1ea3nh\"},\"content\":{\"caption\":\"\",\"image_path\":\"su_kien\\/modules\\/banner\\/lq0tnbPFAti190H2gmoohK8PFtqJYz8WcxJ3zubL.jpg\"}},{\"id\":\"header-1\",\"type\":\"header\",\"title\":\"Ti\\u00eau \\u0111\\u1ec1 ch\\u00ednh\",\"settings\":{\"subtitle_label\":\"Ph\\u1ee5 \\u0111\\u1ec1\",\"badge_label\":\"Badge\"},\"content\":{\"title\":\"H\\u1ed9i th\\u1ea3o nghi\\u00ean c\\u1ee9u khoa h\\u1ecdc\",\"subtitle\":\"Ch\\u01b0\\u01a1ng tr\\u00ecnh d\\u00e0nh ri\\u00eang cho sinh vi\\u00ean khoa C\\u00f4ng Ngh\\u1ec7 Th\\u00f4ng Tin\",\"badge\":\"S\\u1ef1 ki\\u1ec7n th\\u01b0\\u1eddng ni\\u00ean\"}},{\"id\":\"info-1\",\"type\":\"info\",\"title\":\"Th\\u00f4ng tin s\\u1ef1 ki\\u1ec7n\",\"settings\":{\"items\":[\"time\",\"location\",\"capacity\",\"points\"]},\"content\":{\"items\":[\"time\",\"location\",\"capacity\",\"points\"],\"custom_note\":\"Sinh vi\\u00ean mang theo th\\u1ebb sinh vi\\u00ean\"}},{\"id\":\"gallery-1\",\"type\":\"gallery\",\"title\":\"H\\u00ecnh \\u1ea3nh s\\u1ef1 ki\\u1ec7n\",\"settings\":{\"hint\":\"T\\u1ea3i nhi\\u1ec1u \\u1ea3nh cho ri\\u00eang kh\\u1ed1i n\\u00e0y\"},\"content\":{\"images\":[\"su_kien\\/modules\\/gallery\\/RpfMhjLQXlUgNZtL30jcTK20VHyGYMLrefZPsNEs.jpg\",\"su_kien\\/modules\\/gallery\\/BEBjBFn4jOE5tYO4ssaz4ZBFVyF6dHm38fohUH4p.jpg\",\"su_kien\\/modules\\/gallery\\/Bo9X21BXNBDiWYmQQxSlzmWVV3tDCUrcy6ah3YBi.jpg\",\"su_kien\\/modules\\/gallery\\/oE8cmLMX6Im0f2fv5oAW5Lw11jJELzeYyh2tvyRC.jpg\",\"su_kien\\/modules\\/gallery\\/RYZoDSUWlnJl7DDoopIFWFRC3O3lu348eJPZrih4.jpg\"]}},{\"id\":\"description-1\",\"type\":\"description\",\"title\":\"N\\u1ed9i dung chi ti\\u1ebft\",\"settings\":{\"body_label\":\"N\\u1ed9i dung\"},\"content\":{\"heading\":\"N\\u1ed9i dung chi ti\\u1ebft\",\"body\":\"H\\u00e3y \\u0111\\u1ecdc  d\\u1ee5 \\u00e1n , ch\\u00fa tr\\u1ecdng ch\\u00fac n\\u0103ng tr\\u1ee3 l\\u00fd AI th\\u1ef1c hi\\u1ec7n c\\u00e1c thay \\u0111\\u1ed5i sau :\\r\\n- Thay v\\u00ec l\\u00fac n\\u00e0o c\\u0169ng g\\u1eedi y\\u00eau c\\u1ea7u v\\u1ec1 AI th\\u00ec h\\u00e3y t\\u1ee5 tr\\u1ea3 l\\u1eddi c\\u00e1c c\\u00e2u h\\u1ecfi c\\u00f3 s\\u1eb5n b\\u00e0ng d\\u1eef li\\u1ec7u c\\u00f3 s\\u1eb5n, d\\u00f9ng c\\u00e1c c\\u00e2u l\\u1ec7nh sql \\u0111\\u1ec3 l\\u1ea5y d\\u1eef li\\u1ec7u t\\u1eeb s\\u1ef1 ki\\u1ec7n \\u0111\\u1ec3 tr\\u1ea3 l\\u1eddi cho c\\u00e1c c\\u00e2u h\\u1ecfi \\r\\n- T\\u1ed1i \\u01b0u h\\u00f3a c\\u00e2u h\\u1ecfi \\u0111\\u1ec3 gi\\u1ea3m token cho c\\u00e1c c\\u00e2u tr\\u1ea3 l\\u1eddi\\r\\n- \\u0110\\u01b0a th\\u00eam c\\u00e1c gi\\u1ea3i ph\\u00e1p t\\u1ed1i \\u01b0u\"}},{\"id\":\"gallery-2\",\"type\":\"gallery\",\"title\":\"Gallery 2\",\"settings\":{\"hint\":\"T\\u1ea3i nhi\\u1ec1u \\u1ea3nh cho ri\\u00eang kh\\u1ed1i n\\u00e0y\"},\"content\":{\"images\":[\"su_kien\\/modules\\/gallery\\/biJBGEGcU0plaAQwV2BS5JDUnDl9COJA3eov4jgS.jpg\",\"su_kien\\/modules\\/gallery\\/KWB5tqnhZUUJB7J1u3XvSilg1OeVoN4ftykQ3Iac.jpg\",\"su_kien\\/modules\\/gallery\\/QRIRNB0ZA4nRZ9omqYTxProuoAkGJ6ctPZ4kGiEY.jpg\",\"su_kien\\/modules\\/gallery\\/qnYQ97RmTnAZltgdmDyuplXz5e3uLzONUpEhiZjJ.jpg\"]}},{\"id\":\"description-2\",\"type\":\"description\",\"title\":\"N\\u1ed9i dung 2\",\"settings\":{\"body_label\":\"N\\u1ed9i dung\"},\"content\":{\"heading\":\"N\\u1ed9i dung 2\",\"body\":\"V\\u00ec sao sinh vi\\u00ean n\\u00ean tham gia ho\\u1ea1t \\u0111\\u1ed9ng ngo\\u1ea1i kh\\u00f3a?\"}}]', NULL, NULL, 200, 0, 2, '64131940', NULL, 'sap_to_chuc', '2026-04-25 11:40:56', '2026-04-25 11:42:04', NULL),
(11, 'Hội thảo học tốt', 'media:993 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\r\nmedia:993 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\r\nmedia:993 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\r\nmedia:993 Tracking Prevention blocked access to storage for https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css.\r\nmedia:1486 [Intervention] Images loaded lazily and replaced with placeholders. Load events are deferred. See https://go.microsoft.com/fwlink/?linkid=2048113\r\napp-CBbX3x7F.js:6 [LoadingInterceptor] ✓ Interceptors attached to axios.\r\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=​\"file\" name=​\"file\" id=​\"fileInput\" required style=​\"display:​none\">​\r\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=​\"file\" name=​\"file\" id=​\"fileInput\" required style=​\"display:​none\">​\r\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=​\"file\" name=​\"file\" id=​\"fileInput\" required style=​\"display:​none\">', 1, 0, '2026-04-28 17:01:00', '2026-04-29 17:01:00', 'Hội trường 3', 'media/images/ae2In5CkBfanmSInMbB1QvJEyRyTKIpO1j3XuT8n.jpg', '[{\"id\":\"banner-1\",\"type\":\"banner\",\"title\":\"\\u1ea2nh b\\u00eca\",\"settings\":{\"caption_label\":\"Ch\\u00fa th\\u00edch \\u1ea3nh\"},\"content\":{\"caption\":\"\",\"image_path\":\"media\\/images\\/ae2In5CkBfanmSInMbB1QvJEyRyTKIpO1j3XuT8n.jpg\"}},{\"id\":\"documents-1777284094489-6fbe\",\"type\":\"documents\",\"title\":\"T\\u00e0i li\\u1ec7u \\u0111\\u00ednh k\\u00e8m 1\",\"settings\":{\"label\":\"T\\u00e0i li\\u1ec7u\"},\"content\":{\"items\":[{\"ma_phuong_tien\":38,\"ten_tep\":\"bao_cao_lop_64.CNTT-1_2026-04-04_09-33-13.xlsx\",\"duong_dan_tep\":\"media\\/documents\\/alxusrAKKMciZLUPEmU8TEL9oiZ3cUovYUHjQbtm.xlsx\",\"loai_tep\":\"tai_lieu\",\"kich_thuoc\":6768}]}},{\"id\":\"header-1\",\"type\":\"header\",\"title\":\"Ti\\u00eau \\u0111\\u1ec1 ch\\u00ednh\",\"settings\":{\"subtitle_label\":\"Ph\\u1ee5 \\u0111\\u1ec1\",\"badge_label\":\"Badge\"},\"content\":{\"title\":\"H\\u1ed9i th\\u1ea3o nghi\\u00ean c\\u1ee9u khoa h\\u1ecdc\",\"subtitle\":\"\",\"badge\":\"S\\u1ef1 ki\\u1ec7n th\\u01b0\\u1eddng ni\\u00ean\"}},{\"id\":\"info-1\",\"type\":\"info\",\"title\":\"Th\\u00f4ng tin s\\u1ef1 ki\\u1ec7n\",\"settings\":{\"items\":[\"time\",\"location\",\"capacity\",\"points\"]},\"content\":{\"items\":[\"time\",\"location\",\"capacity\",\"points\"],\"custom_note\":\"\"}},{\"id\":\"description-1\",\"type\":\"description\",\"title\":\"N\\u1ed9i dung chi ti\\u1ebft\",\"settings\":{\"body_label\":\"N\\u1ed9i dung\"},\"content\":{\"heading\":\"N\\u1ed9i dung chi ti\\u1ebft\",\"body\":\"media:993 Tracking Prevention blocked access to storage for https:\\/\\/cdn.jsdelivr.net\\/npm\\/bootstrap-icons@1.11.3\\/font\\/bootstrap-icons.min.css.\\r\\nmedia:993 Tracking Prevention blocked access to storage for https:\\/\\/cdn.jsdelivr.net\\/npm\\/bootstrap-icons@1.11.3\\/font\\/bootstrap-icons.min.css.\\r\\nmedia:993 Tracking Prevention blocked access to storage for https:\\/\\/cdn.jsdelivr.net\\/npm\\/bootstrap-icons@1.11.3\\/font\\/bootstrap-icons.min.css.\\r\\nmedia:993 Tracking Prevention blocked access to storage for https:\\/\\/cdn.jsdelivr.net\\/npm\\/bootstrap-icons@1.11.3\\/font\\/bootstrap-icons.min.css.\\r\\nmedia:1486 [Intervention] Images loaded lazily and replaced with placeholders. Load events are deferred. See https:\\/\\/go.microsoft.com\\/fwlink\\/?linkid=2048113\\r\\napp-CBbX3x7F.js:6 [LoadingInterceptor] \\u2713 Interceptors attached to axios.\\r\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=\\u200b\\\"file\\\" name=\\u200b\\\"file\\\" id=\\u200b\\\"fileInput\\\" required style=\\u200b\\\"display:\\u200bnone\\\">\\u200b\\r\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=\\u200b\\\"file\\\" name=\\u200b\\\"file\\\" id=\\u200b\\\"fileInput\\\" required style=\\u200b\\\"display:\\u200bnone\\\">\\u200b\\r\\nmedia:1 An invalid form control with name=\'file\' is not focusable. <input type=\\u200b\\\"file\\\" name=\\u200b\\\"file\\\" id=\\u200b\\\"fileInput\\\" required style=\\u200b\\\"display:\\u200bnone\\\">\"}},{\"id\":\"gallery-1\",\"type\":\"gallery\",\"title\":\"H\\u00ecnh \\u1ea3nh s\\u1ef1 ki\\u1ec7n\",\"settings\":{\"hint\":\"T\\u1ea3i nhi\\u1ec1u \\u1ea3nh cho ri\\u00eang kh\\u1ed1i n\\u00e0y\"},\"content\":{\"images\":[\"media\\/images\\/PDv4bpBvduvdh1vwxYqdH1CAHi3aPNhM0uqcGlX7.jpg\",\"media\\/images\\/qKkz8HoQsYS9pkbiSEeVSDxZYheFT2xejIIT46ZF.jpg\",\"media\\/images\\/MQslEZT9vQ1nvo5R1UqguxE7p0tfjdHgKLgwKL3I.jpg\"]}}]', NULL, NULL, 200, 1, 2, '64131940', NULL, 'sap_to_chuc', '2026-04-27 10:02:32', '2026-05-04 16:06:25', NULL),
(12, 'Hội thảo học tốt', NULL, 1, 0, '2026-05-02 22:16:00', '2026-05-02 22:20:00', 'Hội trường 3', NULL, '[{\"id\":\"banner-1\",\"type\":\"banner\",\"title\":\"\\u1ea2nh b\\u00eca\",\"settings\":{\"caption_label\":\"Ch\\u00fa th\\u00edch \\u1ea3nh\"},\"content\":{\"caption\":\"\",\"image_path\":null}},{\"id\":\"header-1\",\"type\":\"header\",\"title\":\"Ti\\u00eau \\u0111\\u1ec1 ch\\u00ednh\",\"settings\":{\"subtitle_label\":\"Ph\\u1ee5 \\u0111\\u1ec1\",\"badge_label\":\"Badge\"},\"content\":{\"title\":\"\",\"subtitle\":\"\",\"badge\":\"\"}},{\"id\":\"info-1\",\"type\":\"info\",\"title\":\"Th\\u00f4ng tin s\\u1ef1 ki\\u1ec7n\",\"settings\":{\"items\":[\"time\",\"location\",\"capacity\",\"points\"]},\"content\":{\"items\":[\"time\",\"location\",\"capacity\",\"points\"],\"custom_note\":\"\"}},{\"id\":\"description-1\",\"type\":\"description\",\"title\":\"N\\u1ed9i dung chi ti\\u1ebft\",\"settings\":{\"body_label\":\"N\\u1ed9i dung\"},\"content\":{\"heading\":\"N\\u1ed9i dung chi ti\\u1ebft\",\"body\":\"\"}},{\"id\":\"gallery-1\",\"type\":\"gallery\",\"title\":\"H\\u00ecnh \\u1ea3nh s\\u1ef1 ki\\u1ec7n\",\"settings\":{\"hint\":\"T\\u1ea3i nhi\\u1ec1u \\u1ea3nh cho ri\\u00eang kh\\u1ed1i n\\u00e0y\"},\"content\":{\"images\":[]}}]', NULL, NULL, 200, 1, 2, '64131940', NULL, 'sap_to_chuc', '2026-05-02 15:14:35', '2026-05-02 15:15:27', NULL),
(13, 'Hội thảo học tốt', NULL, 1, 0, '2026-05-04 23:00:00', '2026-05-04 23:04:00', 'Hội trường 3', 'media/images/C0Nw5PQYENxlbEvqVK4bmuTZmojTOMLVj1deo5EC.png', '[{\"id\":\"banner-1\",\"type\":\"banner\",\"title\":\"\\u1ea2nh b\\u00eca\",\"settings\":{\"caption_label\":\"Ch\\u00fa th\\u00edch \\u1ea3nh\"},\"content\":{\"caption\":\"\",\"image_path\":\"media\\/images\\/C0Nw5PQYENxlbEvqVK4bmuTZmojTOMLVj1deo5EC.png\"}},{\"id\":\"header-1\",\"type\":\"header\",\"title\":\"Ti\\u00eau \\u0111\\u1ec1 ch\\u00ednh\",\"settings\":{\"subtitle_label\":\"Ph\\u1ee5 \\u0111\\u1ec1\",\"badge_label\":\"Badge\"},\"content\":{\"title\":\"\",\"subtitle\":\"\",\"badge\":\"\"}},{\"id\":\"info-1\",\"type\":\"info\",\"title\":\"Th\\u00f4ng tin s\\u1ef1 ki\\u1ec7n\",\"settings\":{\"items\":[\"time\",\"location\",\"capacity\",\"points\"]},\"content\":{\"items\":[\"time\",\"location\",\"capacity\",\"points\"],\"custom_note\":\"\"}},{\"id\":\"description-1\",\"type\":\"description\",\"title\":\"N\\u1ed9i dung chi ti\\u1ebft\",\"settings\":{\"body_label\":\"N\\u1ed9i dung\"},\"content\":{\"heading\":\"N\\u1ed9i dung chi ti\\u1ebft\",\"body\":\"\"}},{\"id\":\"gallery-1\",\"type\":\"gallery\",\"title\":\"H\\u00ecnh \\u1ea3nh s\\u1ef1 ki\\u1ec7n\",\"settings\":{\"hint\":\"T\\u1ea3i nhi\\u1ec1u \\u1ea3nh cho ri\\u00eang kh\\u1ed1i n\\u00e0y\"},\"content\":{\"images\":[]}}]', NULL, NULL, 200, 1, 2, '64131940', NULL, 'sap_to_chuc', '2026-05-04 15:59:16', '2026-05-04 16:18:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `the_anh`
--

CREATE TABLE `the_anh` (
  `ma_the_anh` bigint UNSIGNED NOT NULL,
  `ten_the` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mau_sac` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#007bff' COMMENT 'Mã màu hex cho tag',
  `ma_nguoi_tao` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `the_anh`
--

INSERT INTO `the_anh` (`ma_the_anh`, `ten_the`, `mo_ta`, `mau_sac`, `ma_nguoi_tao`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Ảnh sự kiện 1', NULL, '#007bff', '64131940', '2026-04-25 12:29:01', '2026-04-25 12:29:01', NULL),
(2, 'Ảnh sự kiện 2', NULL, '#ff0000', '64131940', '2026-04-25 12:49:42', '2026-04-25 12:49:42', NULL),
(3, 'tài liệu 1', NULL, '#185fa5', '64131940', '2026-04-27 08:34:04', '2026-04-27 08:34:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `the_anh_thu_vien`
--

CREATE TABLE `the_anh_thu_vien` (
  `id` bigint UNSIGNED NOT NULL,
  `ma_the_anh` bigint UNSIGNED NOT NULL,
  `ma_phuong_tien` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `the_anh_thu_vien`
--

INSERT INTO `the_anh_thu_vien` (`id`, `ma_the_anh`, `ma_phuong_tien`, `created_at`, `updated_at`) VALUES
(2, 1, 31, NULL, NULL),
(3, 2, 32, NULL, NULL),
(5, 1, 34, NULL, NULL),
(6, 2, 34, NULL, NULL),
(7, 1, 35, NULL, NULL),
(8, 2, 35, NULL, NULL),
(10, 1, 37, NULL, NULL),
(11, 3, 38, NULL, NULL),
(12, 3, 39, NULL, NULL),
(13, 1, 40, NULL, NULL),
(14, 1, 42, NULL, NULL),
(15, 2, 43, NULL, NULL),
(16, 1, 45, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `thong_bao`
--

CREATE TABLE `thong_bao` (
  `ma_thong_bao` bigint UNSIGNED NOT NULL,
  `ma_sinh_vien` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tieu_de` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `noi_dung` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `da_doc` tinyint(1) NOT NULL DEFAULT '0',
  `loai_thong_bao` enum('he_thong','nhac_nho_su_kien','cap_nhat_diem','khac') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_su_kien_lien_quan` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thong_bao`
--

INSERT INTO `thong_bao` (`ma_thong_bao`, `ma_sinh_vien`, `tieu_de`, `noi_dung`, `da_doc`, `loai_thong_bao`, `ma_su_kien_lien_quan`, `created_at`, `updated_at`) VALUES
(1, '64131941', 'Sự kiện mới: WorkShop234', 'Có một sự kiện mới WorkShop234 sắp diễn ra. Hãy đăng ký tham gia!', 0, 'nhac_nho_su_kien', 7, '2026-04-16 16:08:01', '2026-04-16 16:08:01'),
(2, '64131942', 'Sự kiện mới: WorkShop234', 'Có một sự kiện mới WorkShop234 sắp diễn ra. Hãy đăng ký tham gia!', 1, 'nhac_nho_su_kien', 7, '2026-04-16 16:08:01', '2026-04-27 10:08:31'),
(3, '64131947', 'Sự kiện mới: WorkShop234', 'Có một sự kiện mới WorkShop234 sắp diễn ra. Hãy đăng ký tham gia!', 0, 'nhac_nho_su_kien', 7, '2026-04-16 16:08:01', '2026-04-16 16:08:01'),
(6, '64131941', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 0, 'nhac_nho_su_kien', 10, '2026-04-25 11:40:56', '2026-04-25 11:40:56'),
(7, '64131942', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 1, 'nhac_nho_su_kien', 10, '2026-04-25 11:40:56', '2026-04-27 10:08:31'),
(8, '64131947', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 0, 'nhac_nho_su_kien', 10, '2026-04-25 11:40:56', '2026-04-25 11:40:56'),
(10, '64131941', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 0, 'nhac_nho_su_kien', 11, '2026-04-27 10:02:32', '2026-04-27 10:02:32'),
(11, '64131942', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 1, 'nhac_nho_su_kien', 11, '2026-04-27 10:02:32', '2026-04-27 10:08:31'),
(12, '64131947', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 0, 'nhac_nho_su_kien', 11, '2026-04-27 10:02:32', '2026-04-27 10:02:32'),
(13, '64131941', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 0, 'nhac_nho_su_kien', 12, '2026-05-02 15:14:35', '2026-05-02 15:14:35'),
(14, '64131942', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 0, 'nhac_nho_su_kien', 12, '2026-05-02 15:14:35', '2026-05-02 15:14:35'),
(15, '64131947', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 0, 'nhac_nho_su_kien', 12, '2026-05-02 15:14:35', '2026-05-02 15:14:35'),
(16, '64131999', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 1, 'nhac_nho_su_kien', 12, '2026-05-02 15:14:35', '2026-05-02 15:14:57'),
(17, '64131941', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 0, 'nhac_nho_su_kien', 13, '2026-05-04 15:59:16', '2026-05-04 15:59:16'),
(18, '64131942', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 0, 'nhac_nho_su_kien', 13, '2026-05-04 15:59:16', '2026-05-04 15:59:16'),
(19, '64131947', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 0, 'nhac_nho_su_kien', 13, '2026-05-04 15:59:16', '2026-05-04 15:59:16'),
(20, '64131999', 'Sự kiện mới: Hội thảo học tốt', 'Có một sự kiện mới Hội thảo học tốt sắp diễn ra. Hãy đăng ký tham gia!', 1, 'nhac_nho_su_kien', 13, '2026-05-04 15:59:16', '2026-05-04 15:59:34');

-- --------------------------------------------------------

--
-- Table structure for table `thu_vien_da_phuong_tien`
--

CREATE TABLE `thu_vien_da_phuong_tien` (
  `ma_phuong_tien` bigint UNSIGNED NOT NULL,
  `ma_su_kien` bigint UNSIGNED DEFAULT NULL,
  `ma_nguoi_tai_len` char(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ten_tep` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duong_dan_tep` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loai_tep` enum('hinh_anh','video','tai_lieu','khac') COLLATE utf8mb4_unicode_ci NOT NULL,
  `kich_thuoc` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thu_vien_da_phuong_tien`
--

INSERT INTO `thu_vien_da_phuong_tien` (`ma_phuong_tien`, `ma_su_kien`, `ma_nguoi_tai_len`, `ten_tep`, `duong_dan_tep`, `loai_tep`, `kich_thuoc`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '64131940', 'Ảnh chụp màn hình_31-3-2026_222750_127.0.0.1.jpeg', 'su_kien/gallery/EWBUihoBa8bhfQsqsyM76KEHex42lOmMyVBP7WrQ.jpg', 'hinh_anh', 120682, '2026-04-03 19:10:49', '2026-04-03 19:10:49', NULL),
(2, 1, '64131940', 'Ảnh chụp màn hình_31-3-2026_222744_127.0.0.1.jpeg', 'su_kien/gallery/tYj5MnoNQ4DWmo6uZdcZA0FgFs3tq6QEbHYRf2bM.jpg', 'hinh_anh', 51616, '2026-04-03 19:10:49', '2026-04-03 19:10:49', NULL),
(3, 1, '64131940', 'Ảnh chụp màn hình_31-3-2026_222739_127.0.0.1.jpeg', 'su_kien/gallery/WpFK6WMPxLSZJ60b7hLCqQlXXkXaCvZrdxJoM2zH.jpg', 'hinh_anh', 69062, '2026-04-03 19:10:49', '2026-04-03 19:10:49', NULL),
(4, 2, '64131940', 'Ảnh chụp màn hình_31-3-2026_222750_127.0.0.1.jpeg', 'su_kien/gallery/47209f08-d5da-4f35-b4e5-88e2712461c9.jpg', 'hinh_anh', 120682, '2026-04-13 12:56:03', '2026-04-13 12:56:03', NULL),
(5, 2, '64131940', 'Ảnh chụp màn hình_31-3-2026_222744_127.0.0.1.jpeg', 'su_kien/gallery/1ce27996-fd41-45a0-9956-dea4d6073f92.jpg', 'hinh_anh', 51616, '2026-04-13 12:56:03', '2026-04-13 12:56:03', NULL),
(6, 2, '64131940', 'Ảnh chụp màn hình_31-3-2026_222739_127.0.0.1.jpeg', 'su_kien/gallery/cfbb8a3b-4b85-41ab-8f97-7709202cb312.jpg', 'hinh_anh', 69062, '2026-04-13 12:56:03', '2026-04-13 12:56:03', NULL),
(7, 3, '64131940', 'Ảnh chụp màn hình_31-3-2026_222750_127.0.0.1.jpeg', 'su_kien/gallery/fcf0bae4-6cad-4727-a073-7dd23e2fdc8f.jpg', 'hinh_anh', 120682, '2026-04-13 13:24:42', '2026-04-13 13:24:42', NULL),
(8, 3, '64131940', 'Ảnh chụp màn hình_31-3-2026_222744_127.0.0.1.jpeg', 'su_kien/gallery/c7332c36-2481-4453-b2a2-4a6f05ea8fb8.jpg', 'hinh_anh', 51616, '2026-04-13 13:24:42', '2026-04-13 13:24:42', NULL),
(9, 3, '64131940', 'Ảnh chụp màn hình_31-3-2026_222739_127.0.0.1.jpeg', 'su_kien/gallery/e63b7dc9-f3fc-41c2-83c2-3f859a8e8a3e.jpg', 'hinh_anh', 69062, '2026-04-13 13:24:42', '2026-04-13 13:24:42', NULL),
(10, 5, '64131940', 'Ảnh chụp màn hình_31-3-2026_222750_127.0.0.1.jpeg', 'su_kien/gallery/0e9bd60b-1010-4e59-aa70-5d95f528a334.jpg', 'hinh_anh', 120682, '2026-04-13 14:04:42', '2026-04-13 14:04:42', NULL),
(11, 5, '64131940', 'Ảnh chụp màn hình_31-3-2026_222744_127.0.0.1.jpeg', 'su_kien/gallery/af4fea57-0805-44bd-bc07-5e4348316726.jpg', 'hinh_anh', 51616, '2026-04-13 14:04:42', '2026-04-13 14:04:42', NULL),
(12, 5, '64131940', 'Ảnh chụp màn hình_31-3-2026_222739_127.0.0.1.jpeg', 'su_kien/gallery/bdefbb97-5d21-4ab5-b4d1-38543c29ff7b.jpg', 'hinh_anh', 69062, '2026-04-13 14:04:42', '2026-04-13 14:04:42', NULL),
(13, 5, '64131940', 'Ảnh chụp màn hình_31-3-2026_222750_127.0.0.1.jpeg', 'su_kien/gallery/0af8aca1-cb06-4010-ab72-2696902d5381.jpg', 'hinh_anh', 120682, '2026-04-13 14:04:42', '2026-04-13 14:04:42', NULL),
(14, 5, '64131940', 'Ảnh chụp màn hình_31-3-2026_222744_127.0.0.1.jpeg', 'su_kien/gallery/baf0c478-3703-4612-9084-6fd38ac808a0.jpg', 'hinh_anh', 51616, '2026-04-13 14:04:42', '2026-04-13 14:04:42', NULL),
(15, 5, '64131940', 'Ảnh chụp màn hình_31-3-2026_222739_127.0.0.1.jpeg', 'su_kien/gallery/d7865255-d0ee-4111-bb15-c2756fc33c6e.jpg', 'hinh_anh', 69062, '2026-04-13 14:04:42', '2026-04-13 14:04:42', NULL),
(16, NULL, '64131940', 'Ảnh chụp màn hình_31-3-2026_222644_127.0.0.1.jpeg', 'media/pfbdQmssJA1EhxBX8HdTChVKQ1sdatExGSaHA9hM.jpg', 'video', 56341, '2026-04-14 10:17:58', '2026-04-14 10:18:06', '2026-04-14 10:18:06'),
(17, 6, '64131940', 'Ảnh chụp màn hình_31-3-2026_222750_127.0.0.1.jpeg', 'su_kien/gallery/6LEAINqXcFmCEzBtHZbC81PFDjBTM0oiwalFN2BS.jpg', 'hinh_anh', 120682, '2026-04-14 15:12:26', '2026-04-14 15:12:26', NULL),
(18, 6, '64131940', 'Ảnh chụp màn hình_31-3-2026_222744_127.0.0.1.jpeg', 'su_kien/gallery/xMZpzf6JmxzyOu629oP4feAy2HLRssyoGoZDSx14.jpg', 'hinh_anh', 51616, '2026-04-14 15:12:26', '2026-04-14 15:12:26', NULL),
(19, 6, '64131940', 'Ảnh chụp màn hình_31-3-2026_222739_127.0.0.1.jpeg', 'su_kien/gallery/GPStnBhXGmGrlkWVw8XHU6JhtP9Pu7IjggdHXjnI.jpg', 'hinh_anh', 69062, '2026-04-14 15:12:26', '2026-04-14 15:12:26', NULL),
(20, 6, '64131940', 'Ảnh chụp màn hình_31-3-2026_222724_127.0.0.1.jpeg', 'su_kien/gallery/vR5foXpESukzTl15uRzMcuCgyJukeDjxItMlBMus.jpg', 'hinh_anh', 72237, '2026-04-14 15:12:26', '2026-04-14 15:12:26', NULL),
(21, 6, '64131940', 'Ảnh chụp màn hình_31-3-2026_222718_127.0.0.1.jpeg', 'su_kien/gallery/d9x77IPLcCoRIa8myoUrPOXIXdb9NqiQHzPdt03h.jpg', 'hinh_anh', 195141, '2026-04-14 15:12:26', '2026-04-14 15:12:26', NULL),
(22, 6, '64131940', 'Ảnh chụp màn hình_31-3-2026_222712_127.0.0.1.jpeg', 'su_kien/gallery/7HhRrUN8iZR2JvMSDs83X092FENW2d4YAjQqCckM.jpg', 'hinh_anh', 269615, '2026-04-14 15:12:26', '2026-04-14 15:12:26', NULL),
(23, 6, '64131940', 'Ảnh chụp màn hình_31-3-2026_222654_127.0.0.1.jpeg', 'su_kien/gallery/E7URJtaUTS7znM6mo4aj7zyZQNVZIXpit0IbQvqb.jpg', 'hinh_anh', 56515, '2026-04-14 15:12:26', '2026-04-14 15:12:26', NULL),
(24, 6, '64131940', 'Ảnh chụp màn hình_31-3-2026_222644_127.0.0.1.jpeg', 'su_kien/gallery/FQD7BpPWTWoQmIvhnwerScOQkImbJnYIhbzGF6wY.jpg', 'hinh_anh', 56341, '2026-04-14 15:12:26', '2026-04-14 15:12:26', NULL),
(26, 6, '64131940', 'Ảnh chụp màn hình_31-3-2026_222744_127.0.0.1.jpeg', 'media/images/jPCc2qqOFAYVuhm4x7gQIGD4aJ8MXAEhJYvHFe9T.jpg', 'hinh_anh', 51616, '2026-04-14 15:13:20', '2026-04-14 15:13:20', NULL),
(27, 7, '64131940', 'jPCc2qqOFAYVuhm4x7gQIGD4aJ8MXAEhJYvHFe9T.jpg', 'su_kien/gallery/tgvEPi3YPDmknH7sXpjUIpcAnCldcAxNh5KtJdhp.jpg', 'hinh_anh', 51616, '2026-04-16 16:08:01', '2026-04-16 16:08:01', NULL),
(28, 7, '64131940', 'Ảnh chụp màn hình_31-3-2026_222744_127.0.0.1.jpeg', 'media/images/jPCc2qqOFAYVuhm4x7gQIGD4aJ8MXAEhJYvHFe9T.jpg', 'hinh_anh', 51616, '2026-04-16 16:08:01', '2026-04-16 16:08:01', NULL),
(31, NULL, '64131940', 'Ảnh chụp màn hình_14-4-2026_22150_ql_su_kien.test.jpeg', 'media/images/s79cgL0Ama5bksE74gwXokFYIN9GzLrqK8TYohct.jpg', 'hinh_anh', 96641, '2026-04-25 12:49:15', '2026-04-25 12:49:15', NULL),
(32, NULL, '64131940', 'Ảnh chụp màn hình_31-3-2026_222750_127.0.0.1.jpeg', 'media/images/H5ZZyuR5ghru71dNxD1cIJYm8UfqRTDgMURHo1NK.jpg', 'hinh_anh', 120682, '2026-04-25 12:49:42', '2026-04-25 12:49:42', NULL),
(34, NULL, '64131940', 'Ảnh chụp màn hình_14-4-2026_221533_ql_su_kien.test.jpeg', 'media/images/JPHg5qvFIeqFLzvCG2thBY72QhLd3iWVBUvwhePk.jpg', 'hinh_anh', 75652, '2026-04-27 08:31:46', '2026-04-27 08:31:46', NULL),
(35, NULL, '64131940', 'z5643457000906_e0297950b78fb04a14b0f8941470b282.jpg', 'media/images/eOrq4pXXKSoGmW01ADLeD09WdAOWgtdmH0MSvXQf.jpg', 'hinh_anh', 470954, '2026-04-27 08:32:25', '2026-04-27 08:32:25', NULL),
(37, NULL, '64131940', 'z5643457000906_e0297950b78fb04a14b0f8941470b282.jpg', 'media/images/ae2In5CkBfanmSInMbB1QvJEyRyTKIpO1j3XuT8n.jpg', 'hinh_anh', 470954, '2026-04-27 08:52:33', '2026-04-27 08:52:33', NULL),
(38, NULL, '64131940', 'bao_cao_lop_64.CNTT-1_2026-04-04_09-33-13.xlsx', 'media/documents/alxusrAKKMciZLUPEmU8TEL9oiZ3cUovYUHjQbtm.xlsx', 'tai_lieu', 6768, '2026-04-27 08:53:11', '2026-04-27 08:53:11', NULL),
(39, NULL, '64131940', 'bao_cao_lop_64.CNTT-1_2026-04-04_09-33-13.xlsx', 'media/documents/cpa1ShzovgoQTbSPA27PvPLya1VhwzG7RFomCUSg.xlsx', 'tai_lieu', 6768, '2026-04-27 08:55:18', '2026-04-27 08:55:18', NULL),
(40, NULL, '64131940', 'ảnh a.jpeg', 'media/images/kIwy1467HOohQoVqgudLhBtHMVKTptwEpSVukI0I.jpg', 'hinh_anh', 75652, '2026-04-27 08:58:36', '2026-04-27 08:58:36', NULL),
(41, NULL, '64131940', 'hoi_thao.jpg', 'media/images/PDv4bpBvduvdh1vwxYqdH1CAHi3aPNhM0uqcGlX7.jpg', 'hinh_anh', 26928, '2026-04-27 08:59:01', '2026-04-27 08:59:01', NULL),
(42, NULL, '64131940', 'hoi_thao.jpg', 'media/images/qKkz8HoQsYS9pkbiSEeVSDxZYheFT2xejIIT46ZF.jpg', 'hinh_anh', 26928, '2026-04-27 08:59:13', '2026-04-27 08:59:13', NULL),
(43, NULL, '64131940', 'hoi_thao.jpg', 'media/images/MQslEZT9vQ1nvo5R1UqguxE7p0tfjdHgKLgwKL3I.jpg', 'hinh_anh', 26928, '2026-04-27 08:59:21', '2026-04-27 08:59:21', NULL),
(44, NULL, '64131940', 'Ảnh chụp màn hình_14-4-2026_221533_ql_su_kien.test.jpeg', 'media/images/slcFaIyuF5H0uRDO7VkrTGjF7IYPy6IHr3uGWVNn.jpg', 'hinh_anh', 75652, '2026-04-27 09:01:14', '2026-04-27 09:01:14', NULL),
(45, NULL, '64131940', 'Ảnh chụp màn hình_31-3-2026_222750_127.0.0.1.jpeg', 'media/images/zZGNcKgUlDUx5DKMbZDxG2Cqp75B3BeGCxKYYi8f.jpg', 'hinh_anh', 120682, '2026-04-27 09:01:34', '2026-04-27 09:01:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ung_cu_vien`
--

CREATE TABLE `ung_cu_vien` (
  `ma_ung_cu_vien` bigint UNSIGNED NOT NULL,
  `ma_bau_cu` bigint UNSIGNED NOT NULL,
  `ho_ten` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lop` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_sinh_vien` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diem_trung_binh` decimal(4,2) DEFAULT NULL COMMENT 'ĐTB tích lũy hệ 10',
  `diem_ren_luyen` decimal(5,2) DEFAULT NULL COMMENT 'Điểm rèn luyện tích lũy',
  `gioi_thieu` text COLLATE utf8mb4_unicode_ci,
  `thu_tu_hien_thi` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ung_cu_vien`
--

INSERT INTO `ung_cu_vien` (`ma_ung_cu_vien`, `ma_bau_cu`, `ho_ten`, `lop`, `ma_sinh_vien`, `diem_trung_binh`, `diem_ren_luyen`, `gioi_thieu`, `thu_tu_hien_thi`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nguyễn Văn A', '64.CNTT-1', '64131948', '9.00', '99.00', NULL, 0, '2026-04-11 10:00:17', '2026-04-11 10:00:17'),
(2, 3, 'Dương Phú Quảng', '64.CNTT-1', '64131942', '9.00', '99.00', NULL, 0, '2026-04-16 15:16:19', '2026-04-16 15:16:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_index` (`user_id`),
  ADD KEY `activity_logs_action_index` (`action`),
  ADD KEY `activity_logs_created_at_index` (`created_at`),
  ADD KEY `activity_logs_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `bau_cu`
--
ALTER TABLE `bau_cu`
  ADD PRIMARY KEY (`ma_bau_cu`),
  ADD KEY `bau_cu_thoi_gian_bat_dau_thoi_gian_ket_thuc_index` (`thoi_gian_bat_dau`,`thoi_gian_ket_thuc`),
  ADD KEY `bau_cu_trang_thai_index` (`trang_thai`),
  ADD KEY `bau_cu_ma_nguoi_tao_foreign` (`ma_nguoi_tao`);

--
-- Indexes for table `chi_tiet_diem_danh`
--
ALTER TABLE `chi_tiet_diem_danh`
  ADD PRIMARY KEY (`ma_chi_tiet_diem_danh`),
  ADD UNIQUE KEY `chi_tiet_diem_danh_ma_dang_ky_loai_diem_danh_unique` (`ma_dang_ky`,`loai_diem_danh`),
  ADD KEY `chi_tiet_diem_danh_ma_dang_ky_index` (`ma_dang_ky`),
  ADD KEY `chi_tiet_diem_danh_ma_su_kien_index` (`ma_su_kien`),
  ADD KEY `chi_tiet_diem_danh_ma_sinh_vien_index` (`ma_sinh_vien`),
  ADD KEY `chi_tiet_diem_danh_ma_dang_ky_loai_diem_danh_index` (`ma_dang_ky`,`loai_diem_danh`);

--
-- Indexes for table `chi_tiet_phieu_bau`
--
ALTER TABLE `chi_tiet_phieu_bau`
  ADD PRIMARY KEY (`ma_chi_tiet`),
  ADD UNIQUE KEY `unique_phieu_ucv` (`ma_phieu_bau`,`ma_ung_cu_vien`),
  ADD KEY `chi_tiet_phieu_bau_ma_ung_cu_vien_foreign` (`ma_ung_cu_vien`);

--
-- Indexes for table `cu_tri`
--
ALTER TABLE `cu_tri`
  ADD PRIMARY KEY (`ma_cu_tri`),
  ADD UNIQUE KEY `unique_cu_tri_bau_cu` (`ma_bau_cu`,`ma_sinh_vien`),
  ADD KEY `cu_tri_ma_sinh_vien_foreign` (`ma_sinh_vien`);

--
-- Indexes for table `dang_ky`
--
ALTER TABLE `dang_ky`
  ADD PRIMARY KEY (`ma_dang_ky`),
  ADD UNIQUE KEY `dang_ky_ma_sinh_vien_ma_su_kien_unique` (`ma_sinh_vien`,`ma_su_kien`),
  ADD KEY `dang_ky_ma_su_kien_foreign` (`ma_su_kien`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gemini_settings`
--
ALTER TABLE `gemini_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lich_su_diem`
--
ALTER TABLE `lich_su_diem`
  ADD PRIMARY KEY (`ma_lich_su_diem`),
  ADD KEY `lich_su_diem_ma_dang_ky_foreign` (`ma_dang_ky`),
  ADD KEY `lich_su_diem_ma_sinh_vien_index` (`ma_sinh_vien`),
  ADD KEY `lich_su_diem_ma_sinh_vien_loai_log_index` (`ma_sinh_vien`,`loai_log`);

--
-- Indexes for table `loai_su_kien`
--
ALTER TABLE `loai_su_kien`
  ADD PRIMARY KEY (`ma_loai_su_kien`),
  ADD UNIQUE KEY `loai_su_kien_ten_loai_unique` (`ten_loai`);

--
-- Indexes for table `mau_bai_dang`
--
ALTER TABLE `mau_bai_dang`
  ADD PRIMARY KEY (`ma_mau`),
  ADD KEY `mau_bai_dang_ma_nguoi_tao_foreign` (`ma_nguoi_tao`),
  ADD KEY `mau_bai_dang_ma_loai_su_kien_foreign` (`ma_loai_su_kien`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`ma_sinh_vien`),
  ADD UNIQUE KEY `nguoi_dung_ma_sinh_vien_unique` (`ma_sinh_vien`),
  ADD UNIQUE KEY `nguoi_dung_email_unique` (`email`),
  ADD KEY `nguoi_dung_lop_index` (`lop`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `phieu_bau`
--
ALTER TABLE `phieu_bau`
  ADD PRIMARY KEY (`ma_phieu_bau`),
  ADD KEY `phieu_bau_ma_bau_cu_foreign` (`ma_bau_cu`);

--
-- Indexes for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `su_kien`
--
ALTER TABLE `su_kien`
  ADD PRIMARY KEY (`ma_su_kien`),
  ADD UNIQUE KEY `su_kien_qr_checkin_token_unique` (`qr_checkin_token`),
  ADD KEY `su_kien_ma_loai_su_kien_foreign` (`ma_loai_su_kien`),
  ADD KEY `su_kien_thoi_gian_bat_dau_thoi_gian_ket_thuc_index` (`thoi_gian_bat_dau`,`thoi_gian_ket_thuc`),
  ADD KEY `su_kien_trang_thai_index` (`trang_thai`),
  ADD KEY `su_kien_ma_nguoi_tao_foreign` (`ma_nguoi_tao`),
  ADD KEY `su_kien_ma_nguoi_to_chuc_foreign` (`ma_nguoi_to_chuc`);

--
-- Indexes for table `the_anh`
--
ALTER TABLE `the_anh`
  ADD PRIMARY KEY (`ma_the_anh`),
  ADD UNIQUE KEY `the_anh_ten_the_unique` (`ten_the`),
  ADD KEY `the_anh_ma_nguoi_tao_foreign` (`ma_nguoi_tao`),
  ADD KEY `the_anh_ten_the_index` (`ten_the`),
  ADD KEY `the_anh_created_at_index` (`created_at`);

--
-- Indexes for table `the_anh_thu_vien`
--
ALTER TABLE `the_anh_thu_vien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `the_anh_thu_vien_ma_the_anh_ma_phuong_tien_unique` (`ma_the_anh`,`ma_phuong_tien`),
  ADD KEY `the_anh_thu_vien_ma_the_anh_index` (`ma_the_anh`),
  ADD KEY `the_anh_thu_vien_ma_phuong_tien_index` (`ma_phuong_tien`);

--
-- Indexes for table `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD PRIMARY KEY (`ma_thong_bao`),
  ADD KEY `thong_bao_ma_su_kien_lien_quan_foreign` (`ma_su_kien_lien_quan`),
  ADD KEY `thong_bao_ma_sinh_vien_da_doc_index` (`ma_sinh_vien`,`da_doc`);

--
-- Indexes for table `thu_vien_da_phuong_tien`
--
ALTER TABLE `thu_vien_da_phuong_tien`
  ADD PRIMARY KEY (`ma_phuong_tien`),
  ADD KEY `thu_vien_da_phuong_tien_ma_su_kien_foreign` (`ma_su_kien`),
  ADD KEY `thu_vien_da_phuong_tien_ma_nguoi_tai_len_foreign` (`ma_nguoi_tai_len`);

--
-- Indexes for table `ung_cu_vien`
--
ALTER TABLE `ung_cu_vien`
  ADD PRIMARY KEY (`ma_ung_cu_vien`),
  ADD UNIQUE KEY `unique_ucv_bau_cu` (`ma_bau_cu`,`ma_sinh_vien`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `bau_cu`
--
ALTER TABLE `bau_cu`
  MODIFY `ma_bau_cu` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chi_tiet_diem_danh`
--
ALTER TABLE `chi_tiet_diem_danh`
  MODIFY `ma_chi_tiet_diem_danh` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chi_tiet_phieu_bau`
--
ALTER TABLE `chi_tiet_phieu_bau`
  MODIFY `ma_chi_tiet` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cu_tri`
--
ALTER TABLE `cu_tri`
  MODIFY `ma_cu_tri` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `dang_ky`
--
ALTER TABLE `dang_ky`
  MODIFY `ma_dang_ky` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gemini_settings`
--
ALTER TABLE `gemini_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lich_su_diem`
--
ALTER TABLE `lich_su_diem`
  MODIFY `ma_lich_su_diem` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loai_su_kien`
--
ALTER TABLE `loai_su_kien`
  MODIFY `ma_loai_su_kien` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mau_bai_dang`
--
ALTER TABLE `mau_bai_dang`
  MODIFY `ma_mau` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `phieu_bau`
--
ALTER TABLE `phieu_bau`
  MODIFY `ma_phieu_bau` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `su_kien`
--
ALTER TABLE `su_kien`
  MODIFY `ma_su_kien` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `the_anh`
--
ALTER TABLE `the_anh`
  MODIFY `ma_the_anh` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `the_anh_thu_vien`
--
ALTER TABLE `the_anh_thu_vien`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `thong_bao`
--
ALTER TABLE `thong_bao`
  MODIFY `ma_thong_bao` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `thu_vien_da_phuong_tien`
--
ALTER TABLE `thu_vien_da_phuong_tien`
  MODIFY `ma_phuong_tien` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `ung_cu_vien`
--
ALTER TABLE `ung_cu_vien`
  MODIFY `ma_ung_cu_vien` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bau_cu`
--
ALTER TABLE `bau_cu`
  ADD CONSTRAINT `bau_cu_ma_nguoi_tao_foreign` FOREIGN KEY (`ma_nguoi_tao`) REFERENCES `nguoi_dung` (`ma_sinh_vien`) ON DELETE RESTRICT;

--
-- Constraints for table `chi_tiet_diem_danh`
--
ALTER TABLE `chi_tiet_diem_danh`
  ADD CONSTRAINT `chi_tiet_diem_danh_ma_dang_ky_foreign` FOREIGN KEY (`ma_dang_ky`) REFERENCES `dang_ky` (`ma_dang_ky`) ON DELETE CASCADE,
  ADD CONSTRAINT `chi_tiet_diem_danh_ma_sinh_vien_foreign` FOREIGN KEY (`ma_sinh_vien`) REFERENCES `nguoi_dung` (`ma_sinh_vien`) ON DELETE CASCADE,
  ADD CONSTRAINT `chi_tiet_diem_danh_ma_su_kien_foreign` FOREIGN KEY (`ma_su_kien`) REFERENCES `su_kien` (`ma_su_kien`) ON DELETE CASCADE;

--
-- Constraints for table `chi_tiet_phieu_bau`
--
ALTER TABLE `chi_tiet_phieu_bau`
  ADD CONSTRAINT `chi_tiet_phieu_bau_ma_phieu_bau_foreign` FOREIGN KEY (`ma_phieu_bau`) REFERENCES `phieu_bau` (`ma_phieu_bau`) ON DELETE CASCADE,
  ADD CONSTRAINT `chi_tiet_phieu_bau_ma_ung_cu_vien_foreign` FOREIGN KEY (`ma_ung_cu_vien`) REFERENCES `ung_cu_vien` (`ma_ung_cu_vien`) ON DELETE CASCADE;

--
-- Constraints for table `cu_tri`
--
ALTER TABLE `cu_tri`
  ADD CONSTRAINT `cu_tri_ma_bau_cu_foreign` FOREIGN KEY (`ma_bau_cu`) REFERENCES `bau_cu` (`ma_bau_cu`) ON DELETE CASCADE,
  ADD CONSTRAINT `cu_tri_ma_sinh_vien_foreign` FOREIGN KEY (`ma_sinh_vien`) REFERENCES `nguoi_dung` (`ma_sinh_vien`) ON DELETE CASCADE;

--
-- Constraints for table `dang_ky`
--
ALTER TABLE `dang_ky`
  ADD CONSTRAINT `dang_ky_ma_sinh_vien_foreign` FOREIGN KEY (`ma_sinh_vien`) REFERENCES `nguoi_dung` (`ma_sinh_vien`) ON DELETE CASCADE,
  ADD CONSTRAINT `dang_ky_ma_su_kien_foreign` FOREIGN KEY (`ma_su_kien`) REFERENCES `su_kien` (`ma_su_kien`) ON DELETE CASCADE;

--
-- Constraints for table `lich_su_diem`
--
ALTER TABLE `lich_su_diem`
  ADD CONSTRAINT `lich_su_diem_ma_dang_ky_foreign` FOREIGN KEY (`ma_dang_ky`) REFERENCES `dang_ky` (`ma_dang_ky`) ON DELETE SET NULL,
  ADD CONSTRAINT `lich_su_diem_ma_sinh_vien_foreign` FOREIGN KEY (`ma_sinh_vien`) REFERENCES `nguoi_dung` (`ma_sinh_vien`) ON DELETE CASCADE;

--
-- Constraints for table `mau_bai_dang`
--
ALTER TABLE `mau_bai_dang`
  ADD CONSTRAINT `mau_bai_dang_ma_loai_su_kien_foreign` FOREIGN KEY (`ma_loai_su_kien`) REFERENCES `loai_su_kien` (`ma_loai_su_kien`) ON DELETE SET NULL,
  ADD CONSTRAINT `mau_bai_dang_ma_nguoi_tao_foreign` FOREIGN KEY (`ma_nguoi_tao`) REFERENCES `nguoi_dung` (`ma_sinh_vien`) ON DELETE SET NULL;

--
-- Constraints for table `phieu_bau`
--
ALTER TABLE `phieu_bau`
  ADD CONSTRAINT `phieu_bau_ma_bau_cu_foreign` FOREIGN KEY (`ma_bau_cu`) REFERENCES `bau_cu` (`ma_bau_cu`) ON DELETE CASCADE;

--
-- Constraints for table `su_kien`
--
ALTER TABLE `su_kien`
  ADD CONSTRAINT `su_kien_ma_loai_su_kien_foreign` FOREIGN KEY (`ma_loai_su_kien`) REFERENCES `loai_su_kien` (`ma_loai_su_kien`) ON DELETE RESTRICT,
  ADD CONSTRAINT `su_kien_ma_nguoi_tao_foreign` FOREIGN KEY (`ma_nguoi_tao`) REFERENCES `nguoi_dung` (`ma_sinh_vien`) ON DELETE SET NULL,
  ADD CONSTRAINT `su_kien_ma_nguoi_to_chuc_foreign` FOREIGN KEY (`ma_nguoi_to_chuc`) REFERENCES `nguoi_dung` (`ma_sinh_vien`) ON DELETE SET NULL;

--
-- Constraints for table `the_anh`
--
ALTER TABLE `the_anh`
  ADD CONSTRAINT `the_anh_ma_nguoi_tao_foreign` FOREIGN KEY (`ma_nguoi_tao`) REFERENCES `nguoi_dung` (`ma_sinh_vien`) ON DELETE SET NULL;

--
-- Constraints for table `the_anh_thu_vien`
--
ALTER TABLE `the_anh_thu_vien`
  ADD CONSTRAINT `the_anh_thu_vien_ma_phuong_tien_foreign` FOREIGN KEY (`ma_phuong_tien`) REFERENCES `thu_vien_da_phuong_tien` (`ma_phuong_tien`) ON DELETE CASCADE,
  ADD CONSTRAINT `the_anh_thu_vien_ma_the_anh_foreign` FOREIGN KEY (`ma_the_anh`) REFERENCES `the_anh` (`ma_the_anh`) ON DELETE CASCADE;

--
-- Constraints for table `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD CONSTRAINT `thong_bao_ma_sinh_vien_foreign` FOREIGN KEY (`ma_sinh_vien`) REFERENCES `nguoi_dung` (`ma_sinh_vien`) ON DELETE CASCADE,
  ADD CONSTRAINT `thong_bao_ma_su_kien_lien_quan_foreign` FOREIGN KEY (`ma_su_kien_lien_quan`) REFERENCES `su_kien` (`ma_su_kien`) ON DELETE SET NULL;

--
-- Constraints for table `thu_vien_da_phuong_tien`
--
ALTER TABLE `thu_vien_da_phuong_tien`
  ADD CONSTRAINT `thu_vien_da_phuong_tien_ma_nguoi_tai_len_foreign` FOREIGN KEY (`ma_nguoi_tai_len`) REFERENCES `nguoi_dung` (`ma_sinh_vien`) ON DELETE SET NULL,
  ADD CONSTRAINT `thu_vien_da_phuong_tien_ma_su_kien_foreign` FOREIGN KEY (`ma_su_kien`) REFERENCES `su_kien` (`ma_su_kien`) ON DELETE SET NULL;

--
-- Constraints for table `ung_cu_vien`
--
ALTER TABLE `ung_cu_vien`
  ADD CONSTRAINT `ung_cu_vien_ma_bau_cu_foreign` FOREIGN KEY (`ma_bau_cu`) REFERENCES `bau_cu` (`ma_bau_cu`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

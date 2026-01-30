-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Jan 30, 2026 at 05:34 AM
-- Server version: 8.0.44
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `goldenz_hr`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` int DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'INSERT', 'employees', 1, NULL, '{\"surname\": \"ABAD\", \"first_name\": \"JOHN MARK\", \"employee_no\": 1, \"employee_type\": \"SG\"}', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '2024-01-15 00:30:00'),
(2, 1, 'UPDATE', 'employees', 1, '{\"status\": \"Active\"}', '{\"status\": \"Active\"}', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '2024-01-15 01:15:00'),
(3, 2, 'INSERT', 'time_off_requests', 1, NULL, '{\"start_date\": \"2024-02-01\", \"employee_id\": 1, \"request_type\": \"vacation\"}', '192.168.1.101', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '2024-01-15 02:00:00'),
(4, 25, 'INSERT', 'employees', 2574, NULL, '{\"employee_no\":\"23432\",\"first_name\":\"asdfsdfsdfsd\",\"surname\":\"sdfasdfsdfsdfasdfsdafsdf\",\"employee_type\":\"SG\",\"post\":\"Lady Guard - Hospital\",\"status\":\"Inactive\",\"created_by\":\"HR Administrator\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-13 04:52:11'),
(5, 25, 'INSERT', 'employees', 2867, NULL, '{\"employee_no\":\"43434\",\"first_name\":\"asdf\",\"surname\":\"sadf\",\"employee_type\":\"SG\",\"post\":\"Lady Guard - Hospital\",\"status\":\"Active\",\"created_by\":\"HR Administrator\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-14 06:13:18'),
(6, 25, 'INSERT', 'employees', 2924, NULL, '{\"employee_no\":\"12121\",\"first_name\":\"asdfsadf\",\"surname\":\"asfd\",\"employee_type\":\"SG\",\"post\":\"Lady Guard - Office Building\",\"status\":\"Active\",\"created_by\":\"HR Administrator\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-14 06:24:02'),
(7, 25, 'INSERT', 'employees', 3141, NULL, '{\"employee_no\":\"21342\",\"first_name\":\"dsafsdfsadf\",\"surname\":\"efsdafa\",\"employee_type\":\"SO\",\"post\":\"Security Officer - Headquarters\",\"status\":\"Inactive\",\"created_by\":\"HR Administrator\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-14 06:34:18'),
(8, 25, 'INSERT', 'employees', 3401, NULL, '{\"employee_no\":\"34232\",\"first_name\":\"sadfasfdasd\",\"surname\":\"sadfsf\",\"employee_type\":\"LG\",\"post\":\"Security Officer - Headquarters\",\"status\":\"Terminated\",\"created_by\":\"HR Administrator\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-14 07:26:36'),
(9, 25, 'INSERT', 'employees', 3750, NULL, '{\"employee_no\":\"23423\",\"first_name\":\"SADFSADF\",\"surname\":\"ASDFSADF\",\"employee_type\":\"SG\",\"post\":\"LADY GUARD - OFFICE BUILDING\",\"status\":\"Terminated\",\"created_by\":\"HR Administrator\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-14 08:15:05'),
(10, 25, 'INSERT', 'employees', 3783, NULL, '{\"employee_no\":\"24234\",\"first_name\":\"SDFASDFSFA\",\"surname\":\"SAFASDF\",\"employee_type\":\"SG\",\"post\":\"LADY GUARD - OFFICE BUILDING\",\"status\":\"Inactive\",\"created_by\":\"HR Administrator\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-14 08:17:00'),
(11, 1, 'UPDATE', 'employees', 3817, '{\"surname\": \"SADFSADFASD\", \"first_name\": \"FASDFSADFS\", \"status\": \"Inactive\", \"post\": \"SECURITY GUARD - RESIDENTIAL\"}', '{\"surname\": \"SADFSADFASD\", \"first_name\": \"FASDFSADFS\", \"status\": \"Inactive\", \"post\": \"SECURITY GUARD - RESIDENTIAL\"}', NULL, NULL, '2025-12-14 08:20:48'),
(12, 25, 'INSERT', 'employees', 3817, NULL, '{\"employee_no\":\"23423\",\"first_name\":\"FASDFSADFS\",\"surname\":\"SADFSADFASD\",\"employee_type\":\"SG\",\"post\":\"SECURITY GUARD - RESIDENTIAL\",\"status\":\"Inactive\",\"created_by\":\"HR Administrator\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-14 08:20:48'),
(13, 1, 'UPDATE', 'employees', 3866, '{\"surname\": \"SDFASDFASDF\", \"first_name\": \"SADFASFASFSADF\", \"status\": \"Inactive\", \"post\": \"LADY GUARD - OFFICE BUILDING\"}', '{\"surname\": \"SDFASDFASDF\", \"first_name\": \"SADFASFASFSADF\", \"status\": \"Inactive\", \"post\": \"LADY GUARD - OFFICE BUILDING\"}', NULL, NULL, '2025-12-14 08:27:18'),
(14, 25, 'INSERT', 'employees', 3866, NULL, '{\"employee_no\":\"24324\",\"first_name\":\"SADFASFASFSADF\",\"surname\":\"SDFASDFASDF\",\"employee_type\":\"SG\",\"post\":\"LADY GUARD - OFFICE BUILDING\",\"status\":\"Inactive\",\"created_by\":\"HR Administrator\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-14 08:27:18'),
(15, 1, 'UPDATE', 'employees', 3907, '{\"surname\": \"ASFSDFSADFASDF\", \"first_name\": \"ASDFSDFSD\", \"status\": \"Suspended\", \"post\": \"SECURITY GUARD - RESIDENTIAL\"}', '{\"surname\": \"ASFSDFSADFASDF\", \"first_name\": \"ASDFSDFSD\", \"status\": \"Suspended\", \"post\": \"SECURITY GUARD - RESIDENTIAL\"}', NULL, NULL, '2025-12-14 08:30:41'),
(16, 25, 'INSERT', 'employees', 3907, NULL, '{\"employee_no\":\"23432\",\"first_name\":\"ASDFSDFSD\",\"surname\":\"ASFSDFSADFASDF\",\"employee_type\":\"LG\",\"post\":\"SECURITY GUARD - RESIDENTIAL\",\"status\":\"Suspended\",\"created_by\":\"HR Administrator\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-14 08:30:42'),
(17, 1, 'UPDATE', 'employees', 3996, '{\"surname\": \"ASDFASDFSADFS\", \"first_name\": \"SDFSADFASDFSDAF\", \"status\": \"Suspended\", \"post\": \"SECURITY OFFICER - FIELD OPERATIONS\"}', '{\"surname\": \"ASDFASDFSADFS\", \"first_name\": \"SDFSADFASDFSDAF\", \"status\": \"Suspended\", \"post\": \"SECURITY OFFICER - FIELD OPERATIONS\"}', NULL, NULL, '2025-12-14 08:33:42'),
(18, 25, 'INSERT', 'employees', 3996, NULL, '{\"employee_no\":\"23423\",\"first_name\":\"SDFSADFASDFSDAF\",\"surname\":\"ASDFASDFSADFS\",\"employee_type\":\"LG\",\"post\":\"SECURITY OFFICER - FIELD OPERATIONS\",\"status\":\"Suspended\",\"created_by\":\"HR Administrator\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-14 08:33:42'),
(19, 25, 'INSERT', 'employees', 12729, NULL, '{\"employee_no\":\"34324\",\"first_name\":\"HEHE\",\"surname\":\"HAHA\",\"employee_type\":\"SG\",\"post\":\"UNASSIGNED\",\"status\":\"Active\",\"created_by\":\"HR Administrator\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-12 07:30:43'),
(20, 25, 'INSERT', 'employees', 13042, NULL, '{\"employee_no\":\"24324\",\"first_name\":\"CHRISTIAN\",\"surname\":\"AMOR\",\"employee_type\":\"SG\",\"post\":\"KAHIT SAN\",\"status\":\"Active\",\"created_by\":\"HR Administrator\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-12 07:47:54'),
(21, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 13:22:12\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 05:22:12'),
(22, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 13:22:17\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 05:22:17'),
(23, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 13:45:41\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 05:45:41'),
(24, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 13:49:45\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 05:49:45'),
(25, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 16:02:37\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:02:37'),
(26, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 16:06:17\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:06:17'),
(27, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 16:08:29\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:08:29'),
(28, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 16:08:36\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:08:36'),
(29, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 16:09:04\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:09:04'),
(30, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 16:10:03\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:10:03'),
(31, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 16:12:39\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:12:39'),
(32, 25, 'USER_CREATED', 'users', 30, NULL, '{\"username\":\"grey\",\"email\":\"greycruz00000000@gmail.com\",\"name\":\"aldrin\",\"role\":\"hr_admin\",\"status\":\"active\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:36:54'),
(33, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-13 16:37:46\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:37:46'),
(34, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-13 16:38:08\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:38:08'),
(35, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-13 16:42:01\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:42:01'),
(36, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-13 16:52:06\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:52:06'),
(37, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 16:52:12\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:52:12'),
(38, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-13 16:52:42\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 08:52:42'),
(39, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-13 17:10:10\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:10:10'),
(40, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 17:16:42\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:16:42'),
(41, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-13 17:17:12\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:17:12'),
(42, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-13 17:23:58\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:23:58'),
(43, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-13 17:42:17\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:42:17'),
(44, 30, 'USER_ROLE_UPDATED', 'users', 25, '{\"role\":\"hr_admin\"}', '{\"role\":\"super_admin\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:45:59'),
(45, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 17:46:06\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:46:06'),
(46, 25, 'USER_ROLE_UPDATED', 'users', 25, '{\"role\":\"super_admin\"}', '{\"role\":\"hr_admin\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:46:29'),
(47, 25, 'USER_ROLE_UPDATED', 'users', 25, '{\"role\":\"hr_admin\"}', '{\"role\":\"super_admin\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:46:34'),
(48, 25, 'USER_ROLE_UPDATED', 'users', 25, '{\"role\":\"super_admin\"}', '{\"role\":\"hr_admin\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:46:36'),
(49, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-13 17:46:51\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:46:51'),
(50, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-13 17:47:04\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:47:04'),
(51, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-13 17:49:29\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:49:29'),
(52, 30, 'USER_CREATED', 'users', 31, NULL, '{\"username\":\"amor\",\"email\":\"amor@gmail.com\",\"name\":\"amor\",\"role\":\"hr_admin\",\"status\":\"active\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 09:58:43'),
(53, 30, 'USER_CREATED', 'users', 32, NULL, '{\"username\":\"ChristianAmor\",\"email\":\"christian5787264@gmail.com\",\"name\":\"christian amor\",\"role\":\"super_admin\",\"status\":\"active\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 10:01:09'),
(54, 32, 'LOGIN_ATTEMPT', 'users', 32, NULL, '{\"login_time\":\"2026-01-13 18:01:59\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 10:01:59'),
(55, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-14 07:42:03\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2026-01-13 23:42:03'),
(56, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-16 16:12:26\"}', '192.168.1.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-16 08:12:26'),
(57, 1, 'UPDATE', 'employees', 14910, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:12:54'),
(58, 1, 'UPDATE', 'employees', 14910, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:12:54'),
(59, 1, 'UPDATE', 'employees', 1, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:14:52'),
(60, 1, 'UPDATE', 'employees', 1, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:14:52'),
(61, 1, 'UPDATE', 'employees', 2, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:17:15'),
(62, 1, 'UPDATE', 'employees', 2, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:17:15'),
(63, 1, 'UPDATE', 'employees', 3, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:19:27'),
(64, 1, 'UPDATE', 'employees', 3, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:19:27'),
(65, 1, 'UPDATE', 'employees', 4, '{\"surname\": \"ASDFASDF\", \"first_name\": \"ASDFASDF\", \"status\": \"Inactive\", \"post\": \"\"}', '{\"surname\": \"ASDFASDF\", \"first_name\": \"ASDFASDF\", \"status\": \"Inactive\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:27:15'),
(66, 1, 'UPDATE', 'employees', 4, '{\"surname\": \"ASDFASDF\", \"first_name\": \"ASDFASDF\", \"status\": \"Inactive\", \"post\": \"\"}', '{\"surname\": \"ASDFASDF\", \"first_name\": \"ASDFASDF\", \"status\": \"Inactive\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:27:15'),
(67, 1, 'UPDATE', 'employees', 5, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:28:13'),
(68, 1, 'UPDATE', 'employees', 5, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:28:13'),
(69, 1, 'UPDATE', 'employees', 6, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:31:30'),
(70, 1, 'UPDATE', 'employees', 7, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:33:42'),
(71, 1, 'UPDATE', 'employees', 7, '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', '{\"surname\": \"USER\", \"first_name\": \"TEMP\", \"status\": \"\", \"post\": \"\"}', NULL, NULL, '2026-01-16 08:33:42'),
(72, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-20 21:43:24\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-20 13:43:24'),
(73, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 08:31:12\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 00:31:12'),
(74, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 09:34:47\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 01:34:47'),
(75, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 09:37:38\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 01:37:38'),
(76, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 09:40:24\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 01:40:24'),
(77, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 09:45:25\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 01:45:25'),
(78, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 09:46:26\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 01:46:26'),
(79, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 09:53:54\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 01:53:54'),
(80, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 09:56:39\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 01:56:39'),
(81, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 10:03:36\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 02:03:36'),
(82, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 10:05:40\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 02:05:40'),
(83, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 10:17:43\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 02:17:43'),
(84, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 10:19:04\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 02:19:04'),
(85, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 10:22:39\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 02:22:39'),
(86, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-21 10:22:48\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 02:22:48'),
(87, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 10:23:07\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 02:23:07'),
(88, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-21 10:25:09\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 02:25:09'),
(89, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 10:25:27\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 02:25:27'),
(90, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 10:27:32\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 02:27:32'),
(91, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 10:33:07\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 02:33:07'),
(92, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-21 14:53:05\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 06:53:05'),
(93, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 07:40:05\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-21 23:40:05'),
(94, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 08:39:13\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 00:39:13'),
(95, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 08:39:27\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 00:39:27'),
(96, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 08:39:45\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 00:39:45'),
(97, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 09:37:28\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 01:37:28'),
(98, 1, 'UPDATE', 'employees', 8, '{\"post\": \"ASDFASDF\", \"status\": \"Active\", \"surname\": \"INOCENCIO\", \"first_name\": \"JOHN ALDRIN\"}', '{\"post\": \"ASDFASDF\", \"status\": \"Active\", \"surname\": \"INOCENCIO\", \"first_name\": \"JOHN ALDRIN\"}', NULL, NULL, '2026-01-22 02:10:06'),
(99, 1, 'UPDATE', 'employees', 9, '{\"post\": \"LADY GUARD - HOSPITAL\", \"status\": \"Inactive\", \"surname\": \"INOCENCIO\", \"first_name\": \"INOCENCIO\"}', '{\"post\": \"LADY GUARD - HOSPITAL\", \"status\": \"Inactive\", \"surname\": \"INOCENCIO\", \"first_name\": \"INOCENCIO\"}', NULL, NULL, '2026-01-22 02:35:15'),
(100, 1, 'UPDATE', 'employees', 9, '{\"post\": \"LADY GUARD - HOSPITAL\", \"status\": \"Inactive\", \"surname\": \"INOCENCIO\", \"first_name\": \"INOCENCIO\"}', '{\"post\": \"LADY GUARD - HOSPITAL\", \"status\": \"Inactive\", \"surname\": \"INOCENCIO\", \"first_name\": \"INOCENCIO\"}', NULL, NULL, '2026-01-22 02:35:15'),
(101, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 10:51:49\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 02:51:49'),
(102, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 10:56:37\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 02:56:37'),
(103, 1, 'UPDATE', 'employees', 8, '{\"post\": \"ASDFASDF\", \"status\": \"Active\", \"surname\": \"INOCENCIO\", \"first_name\": \"JOHN ALDRIN\"}', '{\"post\": \"ASDFASDF\", \"status\": \"Active\", \"surname\": \"INOCENCIO\", \"first_name\": \"JOHN ALDRIN\"}', NULL, NULL, '2026-01-22 03:01:23'),
(104, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 11:18:01\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 03:18:01'),
(105, 1, 'UPDATE', 'employees', 10, '{\"post\": \"UNASSIGNED\", \"status\": \"Terminated\", \"surname\": \"INOCENCIO\", \"first_name\": \"ALDRIN\"}', '{\"post\": \"UNASSIGNED\", \"status\": \"Terminated\", \"surname\": \"INOCENCIO\", \"first_name\": \"ALDRIN\"}', NULL, NULL, '2026-01-22 03:20:59'),
(106, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 11:39:10\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 03:39:10'),
(107, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 11:48:09\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 03:48:09'),
(108, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-22 12:02:20\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 04:02:20'),
(109, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 12:52:28\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 04:52:28'),
(110, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 13:23:44\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 05:23:44'),
(111, 32, 'LOGIN_ATTEMPT', 'users', 32, NULL, '{\"login_time\":\"2026-01-22 13:24:17\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 05:24:17'),
(112, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-22 13:29:08\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 05:29:08'),
(113, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-22 13:33:25\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 05:33:25'),
(114, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 13:36:48\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 05:36:48'),
(115, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 13:38:26\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 05:38:26'),
(116, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 13:58:34\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 05:58:34'),
(117, 1, 'UPDATE', 'employees', 10, '{\"post\": \"UNASSIGNED\", \"status\": \"Terminated\", \"surname\": \"INOCENCIO\", \"first_name\": \"ALDRIN\"}', '{\"post\": \"UNASSIGNED\", \"status\": \"Terminated\", \"surname\": \"asdf\", \"first_name\": \"ALDRIN\"}', NULL, NULL, '2026-01-22 07:19:29'),
(118, 25, 'UPDATE', 'employees', 10, '{\"id\":10,\"employee_no\":32424,\"employee_type\":\"LG\",\"surname\":\"INOCENCIO\",\"first_name\":\"ALDRIN\",\"middle_name\":\"JOHN\",\"post\":\"UNASSIGNED\",\"license_no\":\"NCR0-94309234234324\",\"license_exp_date\":\"2025-01-22\",\"rlm_exp\":\"2025-02-22\",\"date_hired\":\"2026-01-22\",\"cp_number\":\"9563211331\",\"sss_no\":\"23-424324\",\"pagibig_no\":\"3453-4435-4354\",\"tin_number\":\"233-453-454-35\",\"philhealth_no\":\"54-354353454-3\",\"birth_date\":\"2000-08-27\",\"height\":\"5\'5\\\"\",\"weight\":\"22\",\"address\":\"FSADFASDF\",\"contact_person\":\"ASDF\",\"relationship\":\"Colleague\",\"contact_person_address\":\"FSDA\",\"contact_person_number\":\"9563211331\",\"blood_type\":\"A-\",\"religion\":\"Muslim\",\"vacancy_source\":\"[\\\"Walk-in\\\"]\",\"referral_name\":null,\"knows_agency_person\":\"No\",\"agency_person_name\":null,\"physical_defect\":\"No\",\"physical_defect_specify\":null,\"drives\":\"No\",\"drivers_license_no\":null,\"drivers_license_exp\":null,\"drinks_alcohol\":\"No\",\"alcohol_frequency\":null,\"prohibited_drugs\":\"No\",\"security_guard_experience\":null,\"convicted\":\"Yes\",\"conviction_details\":null,\"filed_case\":\"No\",\"case_specify\":null,\"action_after_termination\":null,\"signature_1\":\"sadfas\",\"signature_2\":\"asdfasfd\",\"signature_3\":null,\"initial_1\":\"asdfasd\",\"initial_2\":\"fasdfsad\",\"initial_3\":null,\"fingerprint_right_thumb\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_right_thumb.png\",\"fingerprint_right_index\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_right_index.jpg\",\"fingerprint_right_middle\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_right_middle.png\",\"fingerprint_right_ring\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_right_ring.jpg\",\"fingerprint_right_little\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_right_little.png\",\"fingerprint_left_thumb\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_left_thumb.png\",\"fingerprint_left_index\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_left_index.png\",\"fingerprint_left_middle\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_left_middle.png\",\"fingerprint_left_ring\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_left_ring.png\",\"fingerprint_left_little\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_left_little.png\",\"requirements_signature\":null,\"req_2x2\":\"YO\",\"req_birth_cert\":\"NO\",\"req_barangay\":\"YO\",\"req_police\":\"NO\",\"req_nbi\":\"YO\",\"req_di\":\"NO\",\"req_diploma\":\"YO\",\"req_neuro_drug\":\"NO\",\"req_sec_license\":\"YO\",\"sec_lic_no\":null,\"req_sec_lic_no\":\"NO\",\"req_sss\":\"NO\",\"req_pagibig\":\"NO\",\"req_philhealth\":\"NO\",\"req_tin\":\"NO\",\"sworn_day\":\"33\",\"sworn_month\":\"33\",\"sworn_year\":null,\"tax_cert_no\":\"33\",\"tax_cert_issued_at\":\"33\",\"sworn_signature\":\"asdfasfd\",\"affiant_community\":null,\"doc_no\":\"3\",\"page_no\":\"2\",\"book_no\":\"4\",\"series_of\":\"sdf\",\"status\":\"Terminated\",\"created_at\":\"2026-01-22 03:20:59\",\"updated_at\":\"2026-01-22 03:20:59\",\"created_by\":25,\"created_by_name\":\"HR Administrator\",\"gender\":\"Male\",\"civil_status\":\"Single\",\"age\":25,\"birthplace\":\"SFASDF\",\"citizenship\":\"ASDFA\",\"provincial_address\":\"SADFSADF\",\"special_skills\":\"asdf\",\"spouse_name\":\"ASDF\",\"spouse_age\":2,\"spouse_occupation\":\"SDF\",\"father_name\":\"ASDF\",\"father_age\":2,\"father_occupation\":\"ASDF\",\"mother_name\":\"ASDF\",\"mother_age\":2,\"mother_occupation\":\"SDAF\",\"children_names\":\"sdf\",\"college_course\":null,\"college_school_name\":null,\"college_school_address\":null,\"college_years\":null,\"vocational_course\":null,\"vocational_school_name\":null,\"vocational_school_address\":null,\"vocational_years\":null,\"highschool_school_name\":\"FSADASDF\",\"highschool_school_address\":\"ASDF\",\"highschool_years\":\"2002 - 2005\",\"elementary_school_name\":\"ASDFASDF\",\"elementary_school_address\":\"ASDF\",\"elementary_years\":\"2002 - 2005\",\"trainings_json\":\"[{\\\"title\\\":\\\"AFSD\\\",\\\"by\\\":\\\"ASDF\\\",\\\"date\\\":\\\"2026-01-22\\\"}]\",\"gov_exam_taken\":0,\"gov_exam_json\":null,\"employment_history_json\":\"[{\\\"position\\\":\\\"FSDA\\\",\\\"company_name\\\":\\\"SADF\\\",\\\"company_address\\\":\\\"FDSA\\\",\\\"company_phone\\\":\\\"dfg\\\",\\\"period\\\":\\\"02\\\\\\/2005 - 09\\\\\\/2020\\\",\\\"reason\\\":\\\"asdf\\\"}]\",\"profile_image\":null}', '{\"employee_no\":\"32424\",\"employee_type\":\"LG\",\"surname\":\"asdf\",\"first_name\":\"ALDRIN\",\"middle_name\":\"JOHN\",\"post\":\"UNASSIGNED\",\"license_no\":\"NCR0-94309234234324\",\"license_exp_date\":\"2025-01-22\",\"rlm_exp\":\"2025-02-22\",\"date_hired\":\"2026-01-22\",\"cp_number\":\"+63-9563211331\",\"sss_no\":\"23-424324\",\"pagibig_no\":\"3453-4435-4354\",\"tin_number\":\"233-453-454-35\",\"philhealth_no\":\"54-354353454-3\",\"birth_date\":\"2000-08-27\",\"height\":\"5\'5\\\"\",\"weight\":\"22\",\"address\":\"FSADFASDF\",\"contact_person\":\"ASDF\",\"relationship\":\"Colleague\",\"contact_person_address\":\"FSDA\",\"contact_person_number\":\"+63-9563211331\",\"blood_type\":\"A-\",\"religion\":\"Muslim\",\"status\":\"Terminated\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 07:19:29'),
(119, 1, 'UPDATE', 'employees', 10, '{\"post\": \"UNASSIGNED\", \"status\": \"Terminated\", \"surname\": \"asdf\", \"first_name\": \"ALDRIN\"}', '{\"post\": \"UNASSIGNED\", \"status\": \"Terminated\", \"surname\": \"aldrin\", \"first_name\": \"ALDRIN\"}', NULL, NULL, '2026-01-22 07:24:55'),
(120, 25, 'UPDATE', 'employees', 10, '{\"id\":10,\"employee_no\":32424,\"employee_type\":\"LG\",\"surname\":\"asdf\",\"first_name\":\"ALDRIN\",\"middle_name\":\"JOHN\",\"post\":\"UNASSIGNED\",\"license_no\":\"NCR0-94309234234324\",\"license_exp_date\":\"2025-01-22\",\"rlm_exp\":\"2025-02-22\",\"date_hired\":\"2026-01-22\",\"cp_number\":\"+63-9563211331\",\"sss_no\":\"23-424324\",\"pagibig_no\":\"3453-4435-4354\",\"tin_number\":\"233-453-454-35\",\"philhealth_no\":\"54-354353454-3\",\"birth_date\":\"2000-08-27\",\"height\":\"5\'5\\\"\",\"weight\":\"22\",\"address\":\"FSADFASDF\",\"contact_person\":\"ASDF\",\"relationship\":\"Colleague\",\"contact_person_address\":\"FSDA\",\"contact_person_number\":\"+63-9563211331\",\"blood_type\":\"A-\",\"religion\":\"Muslim\",\"vacancy_source\":\"[\\\"Walk-in\\\"]\",\"referral_name\":null,\"knows_agency_person\":\"No\",\"agency_person_name\":null,\"physical_defect\":\"No\",\"physical_defect_specify\":null,\"drives\":\"No\",\"drivers_license_no\":null,\"drivers_license_exp\":null,\"drinks_alcohol\":\"No\",\"alcohol_frequency\":null,\"prohibited_drugs\":\"No\",\"security_guard_experience\":null,\"convicted\":\"Yes\",\"conviction_details\":null,\"filed_case\":\"No\",\"case_specify\":null,\"action_after_termination\":null,\"signature_1\":\"sadfas\",\"signature_2\":\"asdfasfd\",\"signature_3\":null,\"initial_1\":\"asdfasd\",\"initial_2\":\"fasdfsad\",\"initial_3\":null,\"fingerprint_right_thumb\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_right_thumb.png\",\"fingerprint_right_index\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_right_index.jpg\",\"fingerprint_right_middle\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_right_middle.png\",\"fingerprint_right_ring\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_right_ring.jpg\",\"fingerprint_right_little\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_right_little.png\",\"fingerprint_left_thumb\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_left_thumb.png\",\"fingerprint_left_index\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_left_index.png\",\"fingerprint_left_middle\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_left_middle.png\",\"fingerprint_left_ring\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_left_ring.png\",\"fingerprint_left_little\":\"uploads\\/employees\\/fingerprints\\/10_fingerprint_left_little.png\",\"requirements_signature\":null,\"req_2x2\":\"YO\",\"req_birth_cert\":\"NO\",\"req_barangay\":\"YO\",\"req_police\":\"NO\",\"req_nbi\":\"YO\",\"req_di\":\"NO\",\"req_diploma\":\"YO\",\"req_neuro_drug\":\"NO\",\"req_sec_license\":\"YO\",\"sec_lic_no\":null,\"req_sec_lic_no\":\"NO\",\"req_sss\":\"NO\",\"req_pagibig\":\"NO\",\"req_philhealth\":\"NO\",\"req_tin\":\"NO\",\"sworn_day\":\"33\",\"sworn_month\":\"33\",\"sworn_year\":null,\"tax_cert_no\":\"33\",\"tax_cert_issued_at\":\"33\",\"sworn_signature\":\"asdfasfd\",\"affiant_community\":null,\"doc_no\":\"3\",\"page_no\":\"2\",\"book_no\":\"4\",\"series_of\":\"sdf\",\"status\":\"Terminated\",\"created_at\":\"2026-01-22 03:20:59\",\"updated_at\":\"2026-01-22 07:19:29\",\"created_by\":25,\"created_by_name\":\"HR Administrator\",\"gender\":null,\"civil_status\":null,\"age\":null,\"birthplace\":null,\"citizenship\":null,\"provincial_address\":null,\"special_skills\":null,\"spouse_name\":null,\"spouse_age\":null,\"spouse_occupation\":null,\"father_name\":null,\"father_age\":null,\"father_occupation\":null,\"mother_name\":null,\"mother_age\":null,\"mother_occupation\":null,\"children_names\":null,\"college_course\":null,\"college_school_name\":null,\"college_school_address\":null,\"college_years\":null,\"vocational_course\":null,\"vocational_school_name\":null,\"vocational_school_address\":null,\"vocational_years\":null,\"highschool_school_name\":null,\"highschool_school_address\":null,\"highschool_years\":null,\"elementary_school_name\":null,\"elementary_school_address\":null,\"elementary_years\":null,\"trainings_json\":null,\"gov_exam_taken\":null,\"gov_exam_json\":null,\"employment_history_json\":null,\"profile_image\":null}', '{\"employee_no\":\"32424\",\"employee_type\":\"LG\",\"surname\":\"aldrin\",\"first_name\":\"ALDRIN\",\"middle_name\":\"JOHN\",\"post\":\"UNASSIGNED\",\"license_no\":\"NCR0-94309234234324\",\"license_exp_date\":\"2025-01-22\",\"rlm_exp\":\"2025-02-22\",\"date_hired\":\"2026-01-22\",\"cp_number\":\"+63-9563211331\",\"sss_no\":\"23-424324\",\"pagibig_no\":\"3453-4435-4354\",\"tin_number\":\"233-453-454-35\",\"philhealth_no\":\"54-354353454-3\",\"birth_date\":\"2000-08-27\",\"height\":\"5\'5\\\"\",\"weight\":\"22\",\"address\":\"FSADFASDF\",\"contact_person\":\"ASDF\",\"relationship\":\"Colleague\",\"contact_person_address\":\"FSDA\",\"contact_person_number\":\"+63-9563211331\",\"blood_type\":\"A-\",\"religion\":\"Muslim\",\"status\":\"Terminated\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 07:24:55'),
(121, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-22 15:48:37\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 07:48:37'),
(122, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-23 09:13:47\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 01:13:47'),
(123, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 09:16:58\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 01:16:58'),
(124, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-23 09:58:26\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 01:58:26'),
(125, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 10:09:23\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 02:09:23'),
(126, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 10:20:04\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 02:20:04'),
(127, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 10:25:43\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 02:25:43'),
(128, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 10:26:01\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 02:26:01'),
(129, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-23 10:26:55\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 02:26:55'),
(130, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-23 10:28:50\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 02:28:50'),
(131, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-23 10:29:04\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 02:29:04'),
(132, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 10:36:42\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 02:36:42'),
(133, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-23 10:46:38\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 02:46:38'),
(134, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 11:00:44\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 03:00:44'),
(135, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 11:01:00\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 03:01:00'),
(136, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 12:03:55\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 04:03:55'),
(137, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-23 12:35:37\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 04:35:37'),
(138, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 13:40:30\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 05:40:30'),
(139, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 13:41:30\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 05:41:30'),
(140, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-23 13:44:55\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 05:44:55'),
(141, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 14:06:44\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 06:06:44'),
(142, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 14:57:49\",\"login_type\":\"auto\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 06:57:49'),
(143, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-23 14:58:13\",\"login_type\":\"auto\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 06:58:13'),
(144, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-23 15:31:56\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 07:31:56'),
(145, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 15:32:09\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 07:32:09'),
(146, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 15:33:07\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 07:33:07'),
(147, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-23 15:54:40\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-23 07:54:40'),
(148, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-26 07:35:41\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-25 23:35:41'),
(149, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-26 08:01:06\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-26 00:01:06'),
(150, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-26 09:33:59\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-26 01:33:59'),
(151, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-26 09:34:40\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-26 01:34:40');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(152, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-26 10:05:50\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-26 02:05:50'),
(153, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-26 10:53:49\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-26 02:53:49'),
(154, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-26 11:32:47\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-26 03:32:47'),
(155, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-26 11:41:06\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-26 03:41:06'),
(156, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-26 14:54:50\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-26 06:54:50'),
(157, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-26 15:08:00\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-26 07:08:00'),
(158, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-26 16:14:49\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-26 08:14:49'),
(159, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-26 16:18:35\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-26 08:18:35'),
(160, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 08:16:42\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 00:16:42'),
(161, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 08:18:00\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 00:18:00'),
(162, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 11:15:58\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 03:15:58'),
(163, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 11:37:27\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 03:37:27'),
(164, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 11:42:40\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 03:42:40'),
(165, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 11:49:08\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 03:49:08'),
(166, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 11:49:22\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 03:49:22'),
(167, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 11:53:21\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 03:53:21'),
(168, 30, 'USER_CREATED', 'users', 39, NULL, '{\"username\":\"aldrin\",\"email\":\"aldrininocencio212527@gmail.com\",\"name\":\"Aldrin Inocencio\",\"first_name\":\"\",\"last_name\":\"\",\"role\":\"super_admin\",\"status\":\"active\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 03:54:28'),
(169, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 11:57:46\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 03:57:46'),
(170, 1, 'UPDATE', 'employees', 11, '{\"post\": \"LADY GUARD - HOSPITAL\", \"status\": \"Active\", \"surname\": \"INONO\", \"first_name\": \"INANO\"}', '{\"post\": \"LADY GUARD - HOSPITAL\", \"status\": \"Active\", \"surname\": \"INONO\", \"first_name\": \"INANO\"}', NULL, NULL, '2026-01-27 04:07:11'),
(171, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 13:07:08\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:07:08'),
(172, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 13:13:40\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:13:40'),
(173, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 13:19:39\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:19:39'),
(174, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 13:19:50\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:19:50'),
(175, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 13:24:56\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:24:56'),
(176, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 13:25:05\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:25:05'),
(177, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 13:32:58\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:32:58'),
(178, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 13:34:26\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:34:26'),
(179, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 13:34:38\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:34:38'),
(180, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 13:34:55\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:34:55'),
(181, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 13:35:45\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:35:45'),
(182, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 13:39:29\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:39:29'),
(183, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 13:40:30\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:40:30'),
(184, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 13:40:43\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:40:43'),
(185, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 13:41:17\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:41:17'),
(186, 39, 'LOGIN_ATTEMPT', 'users', 39, NULL, '{\"login_time\":\"2026-01-27 13:50:34\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:50:34'),
(187, 39, 'LOGIN_ATTEMPT', 'users', 39, NULL, '{\"login_time\":\"2026-01-27 13:50:49\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:50:49'),
(188, 39, 'LOGIN_ATTEMPT', 'users', 39, NULL, '{\"login_time\":\"2026-01-27 13:53:22\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:53:22'),
(189, 39, 'LOGIN_ATTEMPT', 'users', 39, NULL, '{\"login_time\":\"2026-01-27 13:53:36\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:53:36'),
(190, 39, 'LOGIN_ATTEMPT', 'users', 39, NULL, '{\"login_time\":\"2026-01-27 13:57:18\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:57:18'),
(191, 39, 'LOGIN_ATTEMPT', 'users', 39, NULL, '{\"login_time\":\"2026-01-27 13:57:33\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:57:33'),
(192, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 13:59:32\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 05:59:32'),
(193, 1, 'UPDATE', 'employees', 11, '{\"post\": \"LADY GUARD - HOSPITAL\", \"status\": \"Active\", \"surname\": \"INONO\", \"first_name\": \"INANO\"}', '{\"post\": \"Security Guard - Mall\", \"status\": \"Active\", \"surname\": \"INONO\", \"first_name\": \"INANO\"}', NULL, NULL, '2026-01-27 06:25:18'),
(194, 1, 'UPDATE', 'employees', 8, '{\"post\": \"ASDFASDF\", \"status\": \"Active\", \"surname\": \"INOCENCIO\", \"first_name\": \"JOHN ALDRIN\"}', '{\"post\": \"Unassigned\", \"status\": \"Active\", \"surname\": \"INOCENCIO\", \"first_name\": \"JOHN ALDRIN\"}', NULL, NULL, '2026-01-27 06:25:45'),
(195, 25, 'UPDATE', 'employee_violations', 1, '{\"id\":1,\"employee_id\":10,\"violation_type_id\":178,\"violation_date\":\"2026-01-22\",\"description\":\"asfdsadf\",\"severity\":\"Major\",\"offense_number\":1,\"sanction\":\"asfdsdf\",\"sanction_date\":\"2026-01-22\",\"reported_by\":\"asdfasdfasd\",\"status\":\"Under Review\",\"created_at\":\"2026-01-22 08:15:13\",\"updated_at\":\"2026-01-22 08:15:13\",\"first_name\":\"ALDRIN\",\"surname\":\"aldrin\",\"post\":\"UNASSIGNED\",\"employee_name\":\"aldrin, ALDRIN\",\"violation_type_name\":\"He shall familiarize himself by heart with the Private Security Agency Law (RA 5487, as amended) and these implementing rules and regulations.\",\"violation_category\":\"Major\"}', '{\"employee_id\":\"10\",\"violation_type_id\":\"178\",\"violation_date\":\"2026-01-22\",\"description\":\"aaaaaaaaaaaaaaaaaaaaaa\",\"severity\":\"Major\",\"sanction\":\"asfdsdf\",\"sanction_date\":\"2026-01-22\",\"reported_by\":\"asdfasdfasd\",\"status\":\"Under Review\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 06:47:28'),
(196, 25, 'UPDATE', 'employee_violations', 1, '{\"id\":1,\"employee_id\":10,\"violation_type_id\":178,\"violation_date\":\"2026-01-22\",\"description\":\"aaaaaaaaaaaaaaaaaaaaaa\",\"severity\":\"Major\",\"offense_number\":1,\"sanction\":\"asfdsdf\",\"sanction_date\":\"2026-01-22\",\"reported_by\":\"asdfasdfasd\",\"status\":\"Under Review\",\"created_at\":\"2026-01-22 08:15:13\",\"updated_at\":\"2026-01-27 06:47:28\",\"first_name\":\"ALDRIN\",\"surname\":\"aldrin\",\"post\":\"UNASSIGNED\",\"employee_name\":\"aldrin, ALDRIN\",\"violation_type_name\":\"He shall familiarize himself by heart with the Private Security Agency Law (RA 5487, as amended) and these implementing rules and regulations.\",\"violation_category\":\"Major\"}', '{\"employee_id\":\"10\",\"violation_type_id\":\"178\",\"violation_date\":\"2026-01-22\",\"description\":\"aaaaaaaaaaaaaaaaaaaaaa\",\"severity\":\"Major\",\"sanction\":\"asfdsdf\",\"sanction_date\":\"2026-01-22\",\"reported_by\":\"asdfasdfasd\",\"status\":\"Under Review\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 06:48:13'),
(197, 39, 'LOGIN_ATTEMPT', 'users', 39, NULL, '{\"login_time\":\"2026-01-27 15:25:48\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 07:25:48'),
(198, 39, 'USER_STATUS_UPDATED', 'users', 30, '{\"status\":\"active\"}', '{\"status\":\"suspended\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 07:32:53'),
(199, 39, 'USER_STATUS_UPDATED', 'users', 30, '{\"status\":\"suspended\"}', '{\"status\":\"inactive\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 07:50:13'),
(200, 39, 'USER_STATUS_UPDATED', 'users', 30, '{\"status\":\"inactive\"}', '{\"status\":\"active\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 07:50:34'),
(201, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-27 15:50:36\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 07:50:36'),
(202, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 15:50:52\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 07:50:52'),
(203, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 16:13:25\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 08:13:25'),
(204, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 16:26:07\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 08:26:07'),
(205, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 16:38:04\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 08:38:04'),
(206, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-27 16:42:01\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-27 08:42:01'),
(207, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-28 08:56:47\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-28 00:56:47'),
(208, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-28 09:17:14\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-28 01:17:14'),
(209, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-28 10:40:02\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-28 02:40:02'),
(210, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-28 10:41:14\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-28 02:41:14'),
(211, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-28 12:08:48\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-28 04:08:48'),
(212, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-29 07:41:21\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-28 23:41:21'),
(213, 25, 'UPDATE', 'employee_violations', 1, '{\"id\":1,\"employee_id\":10,\"violation_type_id\":178,\"violation_date\":\"2026-01-22\",\"description\":\"aaaaaaaaaaaaaaaaaaaaaa\",\"severity\":\"Major\",\"offense_number\":1,\"sanction\":\"asfdsdf\",\"sanction_date\":\"2026-01-22\",\"reported_by\":\"asdfasdfasd\",\"status\":\"Under Review\",\"created_at\":\"2026-01-22 08:15:13\",\"updated_at\":\"2026-01-27 06:47:28\",\"first_name\":\"ALDRIN\",\"surname\":\"aldrin\",\"post\":\"UNASSIGNED\",\"employee_name\":\"aldrin, ALDRIN\",\"violation_type_name\":\"He shall familiarize himself by heart with the Private Security Agency Law (RA 5487, as amended) and these implementing rules and regulations.\",\"violation_category\":\"Major\"}', '{\"employee_id\":\"10\",\"violation_type_id\":\"178\",\"violation_date\":\"2026-01-22\",\"description\":\"bbbbbbbbbbbbbbbb\",\"severity\":\"Major\",\"sanction\":\"asfdsdf\",\"sanction_date\":\"2026-01-22\",\"reported_by\":\"asdfasdfasd\",\"status\":\"Under Review\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-29 00:33:13'),
(214, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-29 12:23:10\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-29 04:23:10'),
(215, 25, 'LOGIN_ATTEMPT', 'users', 25, NULL, '{\"login_time\":\"2026-01-29 13:58:21\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-29 05:58:21'),
(216, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-29 14:38:28\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-29 06:38:28'),
(217, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-29 14:52:02\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-29 06:52:02'),
(218, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-29 16:22:47\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-29 08:22:47'),
(219, NULL, 'SECURITY_Login Attempt', 'security', NULL, NULL, '{\"details\":\"User: grey - IP: 172.18.0.1\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 01:58:30'),
(220, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-30 01:58:30\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 01:58:30'),
(221, NULL, 'SYSTEM_INFO', 'authentication', NULL, NULL, '{\"message\":\"User logged in: grey (aldrin)\",\"context\":{\"user_id\":30,\"role\":\"hr_admin\",\"ip\":\"172.18.0.1\"}}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 01:58:30'),
(222, NULL, 'SECURITY_Login Success', 'security', NULL, NULL, '{\"details\":\"User: grey (aldrin) - Role: hr_admin - IP: 172.18.0.1\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 01:58:30'),
(223, NULL, 'SECURITY_Login Attempt', 'security', NULL, NULL, '{\"details\":\"User: grey - IP: 172.18.0.1\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 02:03:48'),
(224, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-30 02:03:48\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 02:03:48'),
(225, NULL, 'SYSTEM_INFO', 'authentication', NULL, NULL, '{\"message\":\"User logged in: grey (aldrin)\",\"context\":{\"user_id\":30,\"role\":\"hr_admin\",\"ip\":\"172.18.0.1\"}}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 02:03:48'),
(226, NULL, 'SECURITY_Login Success', 'security', NULL, NULL, '{\"details\":\"User: grey (aldrin) - Role: hr_admin - IP: 172.18.0.1\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 02:03:48'),
(227, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-30 11:29:45\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 03:29:45'),
(228, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-30 11:30:56\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 03:30:56'),
(229, NULL, 'SECURITY_Login Attempt', 'security', NULL, NULL, '{\"details\":\"User: grey - IP: 172.18.0.1\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 04:32:28'),
(230, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-30 04:32:28\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 04:32:28'),
(231, NULL, 'SYSTEM_INFO', 'authentication', NULL, NULL, '{\"message\":\"User logged in: grey (aldrin)\",\"context\":{\"user_id\":30,\"role\":\"hr_admin\",\"ip\":\"172.18.0.1\"}}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 04:32:28'),
(232, NULL, 'SECURITY_Login Success', 'security', NULL, NULL, '{\"details\":\"User: grey (aldrin) - Role: hr_admin - IP: 172.18.0.1\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 04:32:28'),
(233, NULL, 'SECURITY_Login Attempt', 'security', NULL, NULL, '{\"details\":\"User: grey - IP: 172.18.0.1\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 05:18:14'),
(234, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-30 05:18:14\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 05:18:14'),
(235, NULL, 'SYSTEM_INFO', 'authentication', NULL, NULL, '{\"message\":\"User logged in: grey (aldrin)\",\"context\":{\"user_id\":30,\"role\":\"hr_admin\",\"ip\":\"172.18.0.1\"}}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 05:18:14'),
(236, NULL, 'SECURITY_Login Success', 'security', NULL, NULL, '{\"details\":\"User: grey (aldrin) - Role: hr_admin - IP: 172.18.0.1\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 05:18:14'),
(237, NULL, 'SECURITY_Login Attempt', 'security', NULL, NULL, '{\"details\":\"User: grey - IP: 172.18.0.1\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 05:18:31'),
(238, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-30 05:18:31\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 05:18:31'),
(239, NULL, 'SYSTEM_INFO', 'authentication', NULL, NULL, '{\"message\":\"User logged in: grey (aldrin)\",\"context\":{\"user_id\":30,\"role\":\"hr_admin\",\"ip\":\"172.18.0.1\"}}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 05:18:31'),
(240, NULL, 'SECURITY_Login Success', 'security', NULL, NULL, '{\"details\":\"User: grey (aldrin) - Role: hr_admin - IP: 172.18.0.1\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 05:18:31'),
(241, NULL, 'SECURITY_Login Attempt', 'security', NULL, NULL, '{\"details\":\"User: grey - IP: 172.18.0.1\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 05:25:43'),
(242, 30, 'LOGIN_ATTEMPT', 'users', 30, NULL, '{\"login_time\":\"2026-01-30 05:25:43\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 05:25:43'),
(243, NULL, 'SYSTEM_INFO', 'authentication', NULL, NULL, '{\"message\":\"User logged in: grey (aldrin)\",\"context\":{\"user_id\":30,\"role\":\"hr_admin\",\"ip\":\"172.18.0.1\"}}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 05:25:43'),
(244, NULL, 'SECURITY_Login Success', 'security', NULL, NULL, '{\"details\":\"User: grey (aldrin) - Role: hr_admin - IP: 172.18.0.1\"}', '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-01-30 05:25:43');

-- --------------------------------------------------------

--
-- Table structure for table `backup_history`
--

CREATE TABLE `backup_history` (
  `id` int NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filepath` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `backup_history`
--

INSERT INTO `backup_history` (`id`, `filename`, `filepath`, `file_size`, `created_at`) VALUES
(1, 'backup_goldenz_hr_2026-01-21_075644.sql', 'storage/backups/backup_goldenz_hr_2026-01-21_075644.sql', 72244, '2026-01-20 23:56:45'),
(2, 'backup_goldenz_hr_2026-01-21_103706.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_103706.sql.gz', 10381, '2026-01-21 02:37:07'),
(3, 'backup_goldenz_hr_2026-01-21_133652.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_133652.sql.gz', 10416, '2026-01-21 05:36:52'),
(4, 'backup_goldenz_hr_2026-01-21_135543.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_135543.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_135543.sql.gz', 10447, '2026-01-21 05:55:51'),
(5, 'backup_goldenz_hr_2026-01-21_140124.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_140124.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_140124.sql.gz', 10494, '2026-01-21 06:01:32'),
(6, 'backup_goldenz_hr_2026-01-21_151313.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_151313.sql.gz', 10544, '2026-01-21 07:13:13'),
(7, 'backup_goldenz_hr_2026-01-21_152509.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_152509.sql.gz', 10568, '2026-01-21 07:25:09'),
(8, 'backup_goldenz_hr_2026-01-21_153001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_153001.sql.gz', 10589, '2026-01-21 07:30:02'),
(9, 'backup_goldenz_hr_2026-01-21_153501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_153501.sql.gz', 10615, '2026-01-21 07:35:01'),
(10, 'backup_goldenz_hr_2026-01-21_154001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_154001.sql.gz', 10637, '2026-01-21 07:40:02'),
(11, 'backup_goldenz_hr_2026-01-21_154501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_154501.sql.gz', 10659, '2026-01-21 07:45:01'),
(12, 'backup_goldenz_hr_2026-01-21_155001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_155001.sql.gz', 10676, '2026-01-21 07:50:01'),
(13, 'backup_goldenz_hr_2026-01-21_155031.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_155031.sql.gz', 10703, '2026-01-21 07:50:32'),
(14, 'backup_goldenz_hr_2026-01-21_155501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_155501.sql.gz', 10725, '2026-01-21 07:55:01'),
(15, 'backup_goldenz_hr_2026-01-21_160001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_160001.sql.gz', 10742, '2026-01-21 08:00:01'),
(16, 'backup_goldenz_hr_2026-01-21_160015.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_160015.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_160015.sql.gz', 10763, '2026-01-21 08:00:27'),
(17, 'backup_goldenz_hr_2026-01-21_160501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_160501.sql.gz', 10789, '2026-01-21 08:05:01'),
(18, 'backup_goldenz_hr_2026-01-21_160841.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_160841.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_160841.sql.gz', 10809, '2026-01-21 08:08:51'),
(19, 'backup_goldenz_hr_2026-01-21_160934.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_160934.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_160934.sql.gz', 10847, '2026-01-21 08:09:44'),
(20, 'backup_goldenz_hr_2026-01-21_161001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_161001.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_161001.sql.gz', 10871, '2026-01-21 08:10:09'),
(21, 'backup_goldenz_hr_2026-01-21_161502.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_161502.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_161502.sql.gz', 10902, '2026-01-21 08:15:08'),
(22, 'backup_goldenz_hr_2026-01-21_162002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_162002.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_162002.sql.gz', 10933, '2026-01-21 08:20:08'),
(23, 'backup_goldenz_hr_2026-01-21_162501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_162501.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_162501.sql.gz', 10960, '2026-01-21 08:25:08'),
(24, 'backup_goldenz_hr_2026-01-21_163002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_163002.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_163002.sql.gz', 10983, '2026-01-21 08:30:08'),
(25, 'backup_goldenz_hr_2026-01-21_163502.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_163502.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_163502.sql.gz', 11003, '2026-01-21 08:35:09'),
(26, 'backup_goldenz_hr_2026-01-21_164001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_164001.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_164001.sql.gz', 11024, '2026-01-21 08:40:08'),
(27, 'backup_goldenz_hr_2026-01-21_164501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_164501.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-21_164501.sql.gz', 11047, '2026-01-21 08:45:08'),
(28, 'backup_goldenz_hr_2026-01-21_165001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_165001.sql.gz', 11069, '2026-01-21 08:50:01'),
(29, 'backup_goldenz_hr_2026-01-21_165501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_165501.sql.gz', 11084, '2026-01-21 08:55:01'),
(30, 'backup_goldenz_hr_2026-01-21_170002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_170002.sql.gz', 11100, '2026-01-21 09:00:02'),
(31, 'backup_goldenz_hr_2026-01-21_170501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_170501.sql.gz', 11121, '2026-01-21 09:05:01'),
(32, 'backup_goldenz_hr_2026-01-21_171001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_171001.sql.gz', 11139, '2026-01-21 09:10:01'),
(33, 'backup_goldenz_hr_2026-01-21_171502.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_171502.sql.gz', 11158, '2026-01-21 09:15:02'),
(34, 'backup_goldenz_hr_2026-01-21_172001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-21_172001.sql.gz', 11179, '2026-01-21 09:20:01'),
(35, 'backup_goldenz_hr_2026-01-22_074001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_074001.sql.gz', 11193, '2026-01-21 23:40:01'),
(36, 'backup_goldenz_hr_2026-01-22_074501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_074501.sql.gz', 11232, '2026-01-21 23:45:01'),
(37, 'backup_goldenz_hr_2026-01-22_075501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_075501.sql.gz', 11248, '2026-01-21 23:55:02'),
(38, 'backup_goldenz_hr_2026-01-22_080001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_080001.sql.gz', 11265, '2026-01-22 00:00:01'),
(39, 'backup_goldenz_hr_2026-01-22_080501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_080501.sql.gz', 11291, '2026-01-22 00:05:01'),
(40, 'backup_goldenz_hr_2026-01-22_083501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_083501.sql.gz', 11306, '2026-01-22 00:35:01'),
(41, 'backup_goldenz_hr_2026-01-22_084002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_084002.sql.gz', 11363, '2026-01-22 00:40:02'),
(42, 'backup_goldenz_hr_2026-01-22_084501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_084501.sql.gz', 11383, '2026-01-22 00:45:01'),
(43, 'backup_goldenz_hr_2026-01-22_085001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_085001.sql.gz', 11399, '2026-01-22 00:50:01'),
(44, 'backup_goldenz_hr_2026-01-22_085501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_085501.sql.gz', 11417, '2026-01-22 00:55:01'),
(45, 'backup_goldenz_hr_2026-01-22_090001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_090001.sql.gz', 11432, '2026-01-22 01:00:01'),
(46, 'backup_goldenz_hr_2026-01-22_090501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_090501.sql.gz', 11450, '2026-01-22 01:05:02'),
(47, 'backup_goldenz_hr_2026-01-22_091001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_091001.sql.gz', 11463, '2026-01-22 01:10:01'),
(48, 'backup_goldenz_hr_2026-01-22_093002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_093002.sql.gz', 11482, '2026-01-22 01:30:02'),
(49, 'backup_goldenz_hr_2026-01-22_100001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_100001.sql.gz', 11516, '2026-01-22 02:00:01'),
(50, 'backup_goldenz_hr_2026-01-22_103001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_103001.sql.gz', 11958, '2026-01-22 02:30:01'),
(51, 'backup_goldenz_hr_2026-01-22_110001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_110001.sql.gz', 12470, '2026-01-22 03:00:01'),
(52, 'backup_goldenz_hr_2026-01-22_113001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_113001.sql.gz', 12946, '2026-01-22 03:30:01'),
(53, 'backup_goldenz_hr_2026-01-22_120002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_120002.sql.gz', 13121, '2026-01-22 04:00:02'),
(54, 'backup_goldenz_hr_2026-01-22_123001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_123001.sql.gz', 13170, '2026-01-22 04:30:01'),
(55, 'backup_goldenz_hr_2026-01-22_130001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_130001.sql.gz', 13200, '2026-01-22 05:00:02'),
(56, 'backup_goldenz_hr_2026-01-22_133001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_133001.sql.gz', 13266, '2026-01-22 05:30:01'),
(57, 'backup_goldenz_hr_2026-01-22_140001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_140001.sql.gz', 13344, '2026-01-22 06:00:02'),
(58, 'backup_goldenz_hr_2026-01-22_142501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_142501.sql.gz', 13368, '2026-01-22 06:25:01'),
(59, 'backup_goldenz_hr_2026-01-22_143001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_143001.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-22_143001.sql.gz', 13387, '2026-01-22 06:30:08'),
(60, 'backup_goldenz_hr_2026-01-22_143501.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_143501.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-22_143501.sql.gz', 13414, '2026-01-22 06:35:10'),
(61, 'backup_goldenz_hr_2026-01-22_144001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_144001.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-22_144001.sql.gz', 13433, '2026-01-22 06:40:09'),
(62, 'backup_goldenz_hr_2026-01-22_150002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_150002.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-22_150002.sql.gz', 13482, '2026-01-22 07:00:09'),
(63, 'backup_goldenz_hr_2026-01-22_153001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_153001.sql.gz', 14830, '2026-01-22 07:30:02'),
(64, 'backup_goldenz_hr_2026-01-22_160001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_160001.sql.gz', 15317, '2026-01-22 08:00:02'),
(65, 'backup_goldenz_hr_2026-01-22_163001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_163001.sql.gz', 23162, '2026-01-22 08:30:01'),
(66, 'backup_goldenz_hr_2026-01-22_165919.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_165919.sql.gz', 23228, '2026-01-22 08:59:20'),
(67, 'backup_goldenz_hr_2026-01-22_165941.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_165941.sql.gz', 23257, '2026-01-22 08:59:42'),
(68, 'backup_goldenz_hr_2026-01-22_170001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_170001.sql.gz', 23279, '2026-01-22 09:00:02'),
(69, 'backup_goldenz_hr_2026-01-22_170655.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-22_170655.sql.gz | gdrive://GoldenZ5/backup/backup_goldenz_hr_2026-01-22_170655.sql.gz', 23301, '2026-01-22 09:07:03'),
(70, 'backup_goldenz_hr_2026-01-23_080001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_080001.sql.gz', 23327, '2026-01-23 00:00:02'),
(71, 'backup_goldenz_hr_2026-01-23_083001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_083001.sql.gz', 23355, '2026-01-23 00:30:01'),
(72, 'backup_goldenz_hr_2026-01-23_090001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_090001.sql.gz', 23379, '2026-01-23 01:00:01'),
(73, 'backup_goldenz_hr_2026-01-23_093001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_093001.sql.gz', 23465, '2026-01-23 01:30:02'),
(74, 'backup_goldenz_hr_2026-01-23_100001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_100001.sql.gz', 23529, '2026-01-23 02:00:01'),
(75, 'backup_goldenz_hr_2026-01-23_103001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_103001.sql.gz', 23682, '2026-01-23 02:30:02'),
(76, 'backup_goldenz_hr_2026-01-23_110001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_110001.sql.gz', 24260, '2026-01-23 03:00:02'),
(77, 'backup_goldenz_hr_2026-01-23_113001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_113001.sql.gz', 24393, '2026-01-23 03:30:01'),
(78, 'backup_goldenz_hr_2026-01-23_120001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_120001.sql.gz', 24260, '2026-01-23 04:00:02'),
(79, 'backup_goldenz_hr_2026-01-23_123001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_123001.sql.gz', 24514, '2026-01-23 04:30:02'),
(80, 'backup_goldenz_hr_2026-01-23_130001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_130001.sql.gz', 24591, '2026-01-23 05:00:02'),
(81, 'backup_goldenz_hr_2026-01-23_133001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_133001.sql.gz', 24612, '2026-01-23 05:30:02'),
(82, 'backup_goldenz_hr_2026-01-23_140001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_140001.sql.gz', 24783, '2026-01-23 06:00:02'),
(83, 'backup_goldenz_hr_2026-01-23_143001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_143001.sql.gz', 24871, '2026-01-23 06:30:01'),
(84, 'backup_goldenz_hr_2026-01-23_150001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_150001.sql.gz', 24917, '2026-01-23 07:00:02'),
(85, 'backup_goldenz_hr_2026-01-23_153001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_153001.sql.gz', 24935, '2026-01-23 07:30:01'),
(86, 'backup_goldenz_hr_2026-01-23_160002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-23_160002.sql.gz', 25093, '2026-01-23 08:00:02'),
(87, 'backup_goldenz_hr_2026-01-26_080001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_080001.sql.gz', 25299, '2026-01-26 00:00:02'),
(88, 'backup_goldenz_hr_2026-01-26_083002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_083002.sql.gz', 25367, '2026-01-26 00:30:03'),
(89, 'backup_goldenz_hr_2026-01-26_090001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_090001.sql.gz', 25384, '2026-01-26 01:00:01'),
(90, 'backup_goldenz_hr_2026-01-26_093001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_093001.sql.gz', 25404, '2026-01-26 01:30:02'),
(91, 'backup_goldenz_hr_2026-01-26_100001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_100001.sql.gz', 25458, '2026-01-26 02:00:02'),
(92, 'backup_goldenz_hr_2026-01-26_103001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_103001.sql.gz', 25497, '2026-01-26 02:30:01'),
(93, 'backup_goldenz_hr_2026-01-26_110001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_110001.sql.gz', 25529, '2026-01-26 03:00:02'),
(94, 'backup_goldenz_hr_2026-01-26_113001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_113001.sql.gz', 25542, '2026-01-26 03:30:02'),
(95, 'backup_goldenz_hr_2026-01-26_120001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_120001.sql.gz', 25616, '2026-01-26 04:00:02'),
(96, 'backup_goldenz_hr_2026-01-26_123001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_123001.sql.gz', 25688, '2026-01-26 04:30:01'),
(97, 'backup_goldenz_hr_2026-01-26_130001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_130001.sql.gz', 25730, '2026-01-26 05:00:02'),
(98, 'backup_goldenz_hr_2026-01-26_133001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_133001.sql.gz', 25750, '2026-01-26 05:30:01'),
(99, 'backup_goldenz_hr_2026-01-26_140001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_140001.sql.gz', 25771, '2026-01-26 06:00:02'),
(100, 'backup_goldenz_hr_2026-01-26_143001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_143001.sql.gz', 25836, '2026-01-26 06:30:02'),
(101, 'backup_goldenz_hr_2026-01-26_150001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_150001.sql.gz', 25866, '2026-01-26 07:00:02'),
(102, 'backup_goldenz_hr_2026-01-26_153001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_153001.sql.gz', 25898, '2026-01-26 07:30:02'),
(103, 'backup_goldenz_hr_2026-01-26_160001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_160001.sql.gz', 25912, '2026-01-26 08:00:01'),
(104, 'backup_goldenz_hr_2026-01-26_163002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-26_163002.sql.gz', 25959, '2026-01-26 08:30:02'),
(105, 'backup_goldenz_hr_2026-01-27_083002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_083002.sql.gz', 26029, '2026-01-27 00:30:02'),
(106, 'backup_goldenz_hr_2026-01-27_090001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_090001.sql.gz', 26140, '2026-01-27 01:00:02'),
(107, 'backup_goldenz_hr_2026-01-27_093001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_093001.sql.gz', 26161, '2026-01-27 01:30:02'),
(108, 'backup_goldenz_hr_2026-01-27_100001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_100001.sql.gz', 26177, '2026-01-27 02:00:01'),
(109, 'backup_goldenz_hr_2026-01-27_103001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_103001.sql.gz', 26193, '2026-01-27 02:30:02'),
(110, 'backup_goldenz_hr_2026-01-27_110001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_110001.sql.gz', 26210, '2026-01-27 03:00:01'),
(111, 'backup_goldenz_hr_2026-01-27_113001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_113001.sql.gz', 26274, '2026-01-27 03:30:02'),
(112, 'backup_goldenz_hr_2026-01-27_120001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_120001.sql.gz', 26604, '2026-01-27 04:00:02'),
(113, 'backup_goldenz_hr_2026-01-27_123001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_123001.sql.gz', 27067, '2026-01-27 04:30:01'),
(114, 'backup_goldenz_hr_2026-01-27_130001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_130001.sql.gz', 27080, '2026-01-27 05:00:02'),
(115, 'backup_goldenz_hr_2026-01-27_133001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_133001.sql.gz', 27186, '2026-01-27 05:30:01'),
(116, 'backup_goldenz_hr_2026-01-27_140001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_140001.sql.gz', 27370, '2026-01-27 06:00:01'),
(117, 'backup_goldenz_hr_2026-01-27_143001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_143001.sql.gz', 27516, '2026-01-27 06:30:02'),
(118, 'backup_goldenz_hr_2026-01-27_150001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_150001.sql.gz', 27848, '2026-01-27 07:00:02'),
(119, 'backup_goldenz_hr_2026-01-27_153001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_153001.sql.gz', 28029, '2026-01-27 07:30:01'),
(120, 'backup_goldenz_hr_2026-01-27_160001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_160001.sql.gz', 28148, '2026-01-27 08:00:02'),
(121, 'backup_goldenz_hr_2026-01-27_163001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_163001.sql.gz', 28206, '2026-01-27 08:30:01'),
(122, 'backup_goldenz_hr_2026-01-27_170001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_170001.sql.gz', 28252, '2026-01-27 09:00:02'),
(123, 'backup_goldenz_hr_2026-01-27_173001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-27_173001.sql.gz', 28272, '2026-01-27 09:30:01'),
(124, 'backup_goldenz_hr_2026-01-28_080001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-28_080001.sql.gz', 28332, '2026-01-28 00:00:02'),
(125, 'backup_goldenz_hr_2026-01-28_083001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-28_083001.sql.gz', 28352, '2026-01-28 00:30:02'),
(126, 'backup_goldenz_hr_2026-01-28_090001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-28_090001.sql.gz', 28395, '2026-01-28 01:00:02'),
(127, 'backup_goldenz_hr_2026-01-28_093001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-28_093001.sql.gz', 28431, '2026-01-28 01:30:01'),
(128, 'backup_goldenz_hr_2026-01-28_100001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-28_100001.sql.gz', 29104, '2026-01-28 02:00:02'),
(129, 'backup_goldenz_hr_2026-01-28_103001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-28_103001.sql.gz', 29421, '2026-01-28 02:30:01'),
(130, 'backup_goldenz_hr_2026-01-28_110001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-28_110001.sql.gz', 29576, '2026-01-28 03:00:01'),
(131, 'backup_goldenz_hr_2026-01-28_113001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-28_113001.sql.gz', 29760, '2026-01-28 03:30:02'),
(132, 'backup_goldenz_hr_2026-01-28_120001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-28_120001.sql.gz', 29852, '2026-01-28 04:00:02'),
(133, 'backup_goldenz_hr_2026-01-28_123001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-28_123001.sql.gz', 29904, '2026-01-28 04:30:01'),
(134, 'backup_goldenz_hr_2026-01-28_130001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-28_130001.sql.gz', 29921, '2026-01-28 05:00:01'),
(135, 'backup_goldenz_hr_2026-01-29_080002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_080002.sql.gz', 29963, '2026-01-29 00:00:02'),
(136, 'backup_goldenz_hr_2026-01-29_083002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_083002.sql.gz', 29986, '2026-01-29 00:30:03'),
(137, 'backup_goldenz_hr_2026-01-29_090001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_090001.sql.gz', 30038, '2026-01-29 01:00:02'),
(138, 'backup_goldenz_hr_2026-01-29_093001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_093001.sql.gz', 30059, '2026-01-29 01:30:02'),
(139, 'backup_goldenz_hr_2026-01-29_100001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_100001.sql.gz', 30077, '2026-01-29 02:00:02'),
(140, 'backup_goldenz_hr_2026-01-29_103001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_103001.sql.gz', 30326, '2026-01-29 02:30:01'),
(141, 'backup_goldenz_hr_2026-01-29_110002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_110002.sql.gz', 30343, '2026-01-29 03:00:02'),
(142, 'backup_goldenz_hr_2026-01-29_113001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_113001.sql.gz', 30363, '2026-01-29 03:30:02'),
(143, 'backup_goldenz_hr_2026-01-29_120001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_120001.sql.gz', 30377, '2026-01-29 04:00:02'),
(144, 'backup_goldenz_hr_2026-01-29_123001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_123001.sql.gz', 30356, '2026-01-29 04:30:02'),
(145, 'backup_goldenz_hr_2026-01-29_130001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_130001.sql.gz', 30373, '2026-01-29 05:00:02'),
(146, 'backup_goldenz_hr_2026-01-29_133001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_133001.sql.gz', 30390, '2026-01-29 05:30:01'),
(147, 'backup_goldenz_hr_2026-01-29_140002.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_140002.sql.gz', 30421, '2026-01-29 06:00:02'),
(148, 'backup_goldenz_hr_2026-01-29_143001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_143001.sql.gz', 30441, '2026-01-29 06:30:02'),
(149, 'backup_goldenz_hr_2026-01-29_150001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_150001.sql.gz', 30493, '2026-01-29 07:00:01'),
(150, 'backup_goldenz_hr_2026-01-29_153001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_153001.sql.gz', 30511, '2026-01-29 07:30:02'),
(151, 'backup_goldenz_hr_2026-01-29_160001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_160001.sql.gz', 30528, '2026-01-29 08:00:01'),
(152, 'backup_goldenz_hr_2026-01-29_163001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_163001.sql.gz', 30558, '2026-01-29 08:30:02'),
(153, 'backup_goldenz_hr_2026-01-29_170001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-29_170001.sql.gz', 30573, '2026-01-29 09:00:01'),
(154, 'backup_goldenz_hr_2026-01-30_113001.sql', 'minio://db-backups/backup_goldenz_hr_2026-01-30_113001.sql.gz', 30824, '2026-01-30 03:30:02');

-- --------------------------------------------------------

--
-- Table structure for table `chat_conversations`
--

CREATE TABLE `chat_conversations` (
  `id` int NOT NULL,
  `user1_id` int NOT NULL,
  `user2_id` int NOT NULL,
  `last_message_id` int DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `user1_unread_count` int NOT NULL DEFAULT '0',
  `user2_unread_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_size` int DEFAULT NULL,
  `attachment_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_by_sender` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_by_receiver` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `sender_id`, `receiver_id`, `message`, `attachment_type`, `attachment_path`, `attachment_size`, `attachment_name`, `is_read`, `deleted_by_sender`, `deleted_by_receiver`, `read_at`, `created_at`, `updated_at`) VALUES
(30, 25, 30, 'hi', NULL, NULL, NULL, NULL, 1, 1, 1, '2026-01-23 04:39:31', '2026-01-23 04:39:24', '2026-01-23 04:45:50'),
(31, 25, 30, 'asdf', NULL, NULL, NULL, NULL, 1, 1, 1, '2026-01-23 04:42:35', '2026-01-23 04:42:31', '2026-01-23 04:45:50'),
(32, 30, 25, 'a', NULL, NULL, NULL, NULL, 1, 1, 1, '2026-01-23 04:42:44', '2026-01-23 04:42:42', '2026-01-23 04:45:50'),
(33, 30, 25, 'asdfasd', NULL, NULL, NULL, NULL, 1, 1, 0, '2026-01-23 04:45:43', '2026-01-23 04:45:33', '2026-01-23 04:45:50'),
(34, 25, 30, 'sdaf', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-23 04:49:48', '2026-01-23 04:49:44', '2026-01-23 04:49:48'),
(35, 30, 25, '[Photo]', 'image', 'uploads/chat_attachments/chat_30_1769143803_f74ab0c82b80b71c.jpg', 78201, 'christian.jpg', 1, 0, 0, '2026-01-23 04:50:04', '2026-01-23 04:50:03', '2026-01-23 04:50:04'),
(36, 30, 25, '[Photo]', 'image', 'uploads/chat_attachments/chat_30_1769143817_10b5cb74e54763cc.jpg', 271075, '20c9a4a5-cdfe-45fc-a5e7-fc6c2c837147.jpg', 1, 0, 0, '2026-01-23 04:50:19', '2026-01-23 04:50:17', '2026-01-23 04:50:19'),
(37, 25, 30, 'asdfsadf', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-23 05:44:59', '2026-01-23 05:44:35', '2026-01-23 05:44:59'),
(38, 30, 25, 'waddap', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-23 05:45:05', '2026-01-23 05:45:04', '2026-01-23 05:45:05'),
(39, 25, 30, 'sdfasfd', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-23 05:51:05', '2026-01-23 05:51:02', '2026-01-23 05:51:05'),
(40, 30, 25, 'fds', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-23 05:51:17', '2026-01-23 05:51:16', '2026-01-23 05:51:17'),
(41, 25, 30, '💬😫💚', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-26 01:34:07', '2026-01-23 06:07:00', '2026-01-26 01:34:07'),
(42, 25, 30, 'sdf', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-26 01:34:07', '2026-01-26 00:01:34', '2026-01-26 01:34:07'),
(43, 25, 30, 'dfsds', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-26 01:34:07', '2026-01-26 00:01:59', '2026-01-26 01:34:07'),
(44, 30, 25, 'hi', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-26 05:15:39', '2026-01-26 03:35:13', '2026-01-26 05:15:39'),
(45, 25, 30, 'san ka', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-27 00:28:23', '2026-01-26 06:27:27', '2026-01-27 00:28:23'),
(46, 25, 30, 'putna ka rito', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-27 00:28:23', '2026-01-26 06:27:29', '2026-01-27 00:28:23'),
(47, 30, 25, 'asfd', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-27 00:28:27', '2026-01-27 00:28:25', '2026-01-27 00:28:27'),
(48, 30, 25, '[Photo]', 'image', 'uploads/chat_attachments/chat_30_1769474586_8253371f324ca0af.jpg', 7768, 'monkey.jpg', 1, 0, 0, '2026-01-27 00:43:09', '2026-01-27 00:43:06', '2026-01-27 00:43:09'),
(49, 25, 30, '[Photo]', 'image', 'uploads/chat_attachments/chat_25_1769474970_72aa8b19a57a54ee.jpg', 7768, 'monkey.jpg', 1, 0, 0, '2026-01-27 00:49:35', '2026-01-27 00:49:30', '2026-01-27 00:49:35'),
(50, 25, 30, '🤚', NULL, NULL, NULL, NULL, 1, 0, 0, '2026-01-27 03:16:03', '2026-01-27 03:15:44', '2026-01-27 03:16:03'),
(51, 25, 30, '[Photo]', 'image', 'uploads/chat_attachments/chat_25_1769506818_4580aeda625d5ff0.png', 37257, 'test.png', 0, 0, 0, NULL, '2026-01-27 09:40:18', '2026-01-27 09:40:18');

-- --------------------------------------------------------

--
-- Table structure for table `chat_typing_status`
--

CREATE TABLE `chat_typing_status` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `recipient_id` int NOT NULL,
  `is_typing` tinyint(1) NOT NULL DEFAULT '1',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_typing_status`
--

INSERT INTO `chat_typing_status` (`id`, `user_id`, `recipient_id`, `is_typing`, `updated_at`) VALUES
(1, 25, 30, 0, '2026-01-27 03:15:44'),
(3, 30, 25, 0, '2026-01-27 03:18:28');

-- --------------------------------------------------------

--
-- Table structure for table `dtr_adjustments`
--

CREATE TABLE `dtr_adjustments` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `entry_date` date NOT NULL,
  `dtr_entry_id` int DEFAULT NULL COMMENT 'Related dtr_entries.id if present',
  `old_time_in` time DEFAULT NULL,
  `new_time_in` time DEFAULT NULL,
  `old_time_out` time DEFAULT NULL,
  `new_time_out` time DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `adjusted_by` int DEFAULT NULL COMMENT 'users.id who performed the adjustment',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Audit log for manual DTR adjustments';

-- --------------------------------------------------------

--
-- Table structure for table `dtr_entries`
--

CREATE TABLE `dtr_entries` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `entry_date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `entry_type` enum('time-in','time-out','break','overtime') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `dtr_summary`
-- (See below for the actual view)
--
CREATE TABLE `dtr_summary` (
`employee_id` int
,`employee_name` varchar(102)
,`entry_date` date
,`entry_type` enum('time-in','time-out','break','overtime')
,`hours_worked` bigint
,`post` varchar(100)
,`time_in` time
,`time_out` time
);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int NOT NULL,
  `employee_no` int NOT NULL,
  `employee_type` enum('SG','LG','SO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'SG = Security Guard, LG = Lady Guard, SO = Security Officer',
  `surname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `middle_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `post` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Assignment/Post',
  `license_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `license_exp_date` date DEFAULT NULL,
  `rlm_exp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'RLM = Renewal of License/Membership',
  `date_hired` date NOT NULL,
  `cp_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Contact Phone Number',
  `sss_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Social Security System Number',
  `pagibig_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'PAG-IBIG Fund Number',
  `tin_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tax Identification Number',
  `philhealth_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'PhilHealth Number',
  `hr_remarks` text COLLATE utf8mb4_general_ci,
  `status_summary` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `height` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `weight` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `contact_person` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `relationship` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_person_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `contact_person_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_person_alt` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `relationship_alt` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_person_number_alt` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_person_address_alt` text COLLATE utf8mb4_general_ci,
  `blood_type` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `religion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vacancy_source` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'How did you know of the vacancy (JSON array: Ads, Walk-in, Referral)',
  `referral_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Referral name if vacancy source is Referral',
  `knows_agency_person` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Do you know anyone from the agency?',
  `agency_person_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Name and relationship with agency person',
  `physical_defect` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Do you have any physical defects or chronic ailments?',
  `physical_defect_specify` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Specify physical defects if yes',
  `drives` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Do you drive?',
  `drivers_license_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Driver license number',
  `drivers_license_exp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Driver license expiration date',
  `drinks_alcohol` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Do you drink alcoholic beverages?',
  `alcohol_frequency` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'How frequent do you drink alcohol?',
  `prohibited_drugs` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Are you taking prohibited drugs?',
  `security_guard_experience` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'How long have you worked as a Security Guard?',
  `convicted` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Have you ever been convicted of any offense?',
  `conviction_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Specify conviction details if yes',
  `filed_case` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Have you filed any criminal/civil case against previous employer?',
  `case_specify` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Specify case details if yes',
  `action_after_termination` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'What was your action after termination?',
  `signature_1` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Specimen signature line 1',
  `signature_2` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Specimen signature line 2',
  `signature_3` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Specimen signature line 3',
  `initial_1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Specimen initial 1 (Pinakiling Pirma)',
  `initial_2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Specimen initial 2 (Pinakiling Pirma)',
  `initial_3` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Specimen initial 3 (Pinakiling Pirma)',
  `fingerprint_right_thumb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Right thumb fingerprint file path',
  `fingerprint_right_index` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Right index finger fingerprint file path',
  `fingerprint_right_middle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Right middle finger fingerprint file path',
  `fingerprint_right_ring` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Right ring finger fingerprint file path',
  `fingerprint_right_little` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Right little finger fingerprint file path',
  `fingerprint_left_thumb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Left thumb fingerprint file path',
  `fingerprint_left_index` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Left index finger fingerprint file path',
  `fingerprint_left_middle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Left middle finger fingerprint file path',
  `fingerprint_left_ring` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Left ring finger fingerprint file path',
  `fingerprint_left_little` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Left little finger fingerprint file path',
  `requirements_signature` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Signature over printed name for requirements section',
  `req_2x2` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '2x2 photos provided (YO/NO)',
  `req_birth_cert` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'NSO/Birth Certificate provided (YO/NO)',
  `req_barangay` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Barangay Clearance provided (YO/NO)',
  `req_police` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Police Clearance provided (YO/NO)',
  `req_nbi` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'NBI Clearance provided (YO/NO)',
  `req_di` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'D.I. Clearance provided (YO/NO)',
  `req_diploma` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'High School/College Diploma provided (YO/NO)',
  `req_neuro_drug` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Neuro & Drug test result provided (YO/NO)',
  `req_sec_license` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Sec.License Certificate from SOSIA provided (YO/NO)',
  `sec_lic_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Security License Number for ID copy',
  `req_sec_lic_no` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Sec.Lic.No. ID copy provided (YO/NO)',
  `req_sss` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'SSS No. ID copy provided (YO/NO)',
  `req_pagibig` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Pag-Ibig No. ID copy provided (YO/NO)',
  `req_philhealth` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'PhilHealth No. ID copy provided (YO/NO)',
  `req_tin` enum('YO','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'TIN No. ID copy provided (YO/NO)',
  `sworn_day` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Sworn statement day',
  `sworn_month` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Sworn statement month',
  `sworn_year` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Sworn statement year',
  `tax_cert_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tax Certificate Number',
  `tax_cert_issued_at` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tax Certificate issued at location',
  `sworn_signature` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Signature over printed name for sworn statement',
  `affiant_community` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Affiant exhibited community',
  `doc_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Document Number',
  `page_no` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Page Number',
  `book_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Book Number',
  `series_of` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Series of',
  `status` enum('Active','Inactive','Terminated','Suspended') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Active',
  `employment_status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `created_by_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `civil_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `age` int DEFAULT NULL,
  `birthplace` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `citizenship` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `provincial_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `special_skills` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `spouse_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `spouse_age` int DEFAULT NULL,
  `spouse_occupation` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `father_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `father_age` int DEFAULT NULL,
  `father_occupation` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mother_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mother_age` int DEFAULT NULL,
  `mother_occupation` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `children_names` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `college_course` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `college_school_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `college_school_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `college_years` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vocational_course` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vocational_school_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vocational_school_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vocational_years` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `highschool_school_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `highschool_school_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `highschool_years` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `elementary_school_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `elementary_school_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `elementary_years` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trainings_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `gov_exam_taken` tinyint(1) DEFAULT NULL,
  `gov_exam_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `employment_history_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `character_references_json` text COLLATE utf8mb4_general_ci,
  `profile_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_no`, `employee_type`, `surname`, `first_name`, `middle_name`, `post`, `license_no`, `license_exp_date`, `rlm_exp`, `date_hired`, `cp_number`, `sss_no`, `pagibig_no`, `tin_number`, `philhealth_no`, `hr_remarks`, `status_summary`, `birth_date`, `height`, `weight`, `address`, `contact_person`, `relationship`, `contact_person_address`, `contact_person_number`, `contact_person_alt`, `relationship_alt`, `contact_person_number_alt`, `contact_person_address_alt`, `blood_type`, `religion`, `vacancy_source`, `referral_name`, `knows_agency_person`, `agency_person_name`, `physical_defect`, `physical_defect_specify`, `drives`, `drivers_license_no`, `drivers_license_exp`, `drinks_alcohol`, `alcohol_frequency`, `prohibited_drugs`, `security_guard_experience`, `convicted`, `conviction_details`, `filed_case`, `case_specify`, `action_after_termination`, `signature_1`, `signature_2`, `signature_3`, `initial_1`, `initial_2`, `initial_3`, `fingerprint_right_thumb`, `fingerprint_right_index`, `fingerprint_right_middle`, `fingerprint_right_ring`, `fingerprint_right_little`, `fingerprint_left_thumb`, `fingerprint_left_index`, `fingerprint_left_middle`, `fingerprint_left_ring`, `fingerprint_left_little`, `requirements_signature`, `req_2x2`, `req_birth_cert`, `req_barangay`, `req_police`, `req_nbi`, `req_di`, `req_diploma`, `req_neuro_drug`, `req_sec_license`, `sec_lic_no`, `req_sec_lic_no`, `req_sss`, `req_pagibig`, `req_philhealth`, `req_tin`, `sworn_day`, `sworn_month`, `sworn_year`, `tax_cert_no`, `tax_cert_issued_at`, `sworn_signature`, `affiant_community`, `doc_no`, `page_no`, `book_no`, `series_of`, `status`, `employment_status`, `created_at`, `updated_at`, `created_by`, `created_by_name`, `gender`, `civil_status`, `age`, `birthplace`, `citizenship`, `provincial_address`, `special_skills`, `spouse_name`, `spouse_age`, `spouse_occupation`, `father_name`, `father_age`, `father_occupation`, `mother_name`, `mother_age`, `mother_occupation`, `children_names`, `college_course`, `college_school_name`, `college_school_address`, `college_years`, `vocational_course`, `vocational_school_name`, `vocational_school_address`, `vocational_years`, `highschool_school_name`, `highschool_school_address`, `highschool_years`, `elementary_school_name`, `elementary_school_address`, `elementary_years`, `trainings_json`, `gov_exam_taken`, `gov_exam_json`, `employment_history_json`, `character_references_json`, `profile_image`) VALUES
(1, 24324, '', 'USER', 'TEMP', NULL, '', NULL, NULL, NULL, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', NULL, NULL, '', NULL, '2026-01-16 08:14:52', '2026-01-16 08:14:52', 25, 'HR Administrator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'uploads/employees/1.png'),
(2, 0, '', 'USER', 'TEMP', NULL, '', NULL, NULL, NULL, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', NULL, NULL, '', NULL, '2026-01-16 08:17:15', '2026-01-16 08:17:15', 25, 'HR Administrator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'uploads/employees/2.jpg'),
(3, 24325, '', 'USER', 'TEMP', NULL, '', NULL, NULL, NULL, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', NULL, NULL, '', NULL, '2026-01-16 08:19:27', '2026-01-16 08:19:27', 25, 'HR Administrator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'uploads/employees/3.png'),
(4, 23423, 'SG', 'ASDFASDF', 'ASDFASDF', NULL, '', NULL, NULL, NULL, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', NULL, NULL, 'Inactive', NULL, '2026-01-16 08:27:15', '2026-01-16 08:27:15', 25, 'HR Administrator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'uploads/employees/4.png'),
(5, 23432, '', 'USER', 'TEMP', NULL, '', NULL, NULL, NULL, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', NULL, NULL, '', NULL, '2026-01-16 08:28:13', '2026-01-16 08:28:13', 25, 'HR Administrator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'uploads/employees/5.png'),
(6, 24326, '', 'USER', 'TEMP', NULL, '', NULL, NULL, NULL, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', NULL, NULL, '', NULL, '2026-01-16 08:31:30', '2026-01-16 08:31:30', 25, 'HR Administrator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(7, 24327, '', 'USER', 'TEMP', NULL, '', NULL, NULL, NULL, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', NULL, NULL, '', NULL, '2026-01-16 08:33:42', '2026-01-16 08:33:42', 25, 'HR Administrator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'uploads/employees/7.jpg'),
(8, 11111, 'SG', 'INOCENCIO', 'JOHN ALDRIN', 'RIVO', 'Unassigned', 'RSDA0F931K', '2025-01-22', '2026-01-22', '2026-01-21', NULL, '123123123123', '12312312', '123123', '123123211', NULL, NULL, '2000-08-27', NULL, '55', 'MANDA', 'ASDFASDFSADF', 'Spouse', 'SADFASDF', NULL, NULL, NULL, NULL, NULL, 'AB-', 'Iglesia ni Cristo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', NULL, '2026-01-22 02:10:06', '2026-01-27 06:25:45', 25, 'HR Administrator', 'Male', 'Single', 25, 'QUEZON CITY', 'FILIPINO', 'DIYAN LANG', 'asdfsdfs', 'DFASDF', 2, 'SDAF', 'SDAFASDFSAD', 3, 'SFSDF', 'FSADFASDF', 3, 'SASADFASD', 'sdafasdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SADFSDAF', 'SADF', '2002 - 2025', 'ASDFASDF', 'ASDFASDF', '2002 - 2025', '[{\"title\":\"ASDF\",\"by\":\"ASDFASD\",\"date\":\"\"}]', 0, NULL, '[{\"position\":\"SADFASD\",\"company_name\":\"FSDF\",\"company_address\":\"SADF\",\"company_phone\":\"sdfsdaf\",\"period\":\"02\\/2002 - 02\\/2010\",\"reason\":\"sadfasd\"}]', NULL, 'uploads/employees/8.jpg'),
(9, 13123, 'LG', 'INOCENCIO', 'INOCENCIO', 'INOCENCIO', 'LADY GUARD - HOSPITAL', 'NCR9-3928492323409', '2026-01-22', '2026-01-22', '2026-01-22', '9563211331', '34234', '24234', '432', '23423', NULL, NULL, '2000-08-27', '5\'5\"', '25', 'ASDF', 'SADF', 'Colleague', 'ASFSADF', '9563211331', NULL, NULL, NULL, NULL, 'A-', 'Indigenous / Tribal', '[\"Walk-in\"]', NULL, 'No', NULL, 'No', NULL, 'Yes', '312312321', '08/23/2029', 'No', NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'sadf', 'asdf', NULL, 'aa', 'aa', NULL, 'uploads/employees/fingerprints/9_fingerprint_right_thumb.png', 'uploads/employees/fingerprints/9_fingerprint_right_index.png', 'uploads/employees/fingerprints/9_fingerprint_right_middle.jpg', 'uploads/employees/fingerprints/9_fingerprint_right_ring.png', 'uploads/employees/fingerprints/9_fingerprint_right_little.png', 'uploads/employees/fingerprints/9_fingerprint_left_thumb.png', 'uploads/employees/fingerprints/9_fingerprint_left_index.jpg', 'uploads/employees/fingerprints/9_fingerprint_left_middle.png', 'uploads/employees/fingerprints/9_fingerprint_left_ring.png', 'uploads/employees/fingerprints/9_fingerprint_left_little.png', NULL, 'YO', 'NO', 'YO', 'NO', 'YO', 'NO', 'YO', 'NO', 'YO', '21312312', 'YO', 'YO', 'YO', 'YO', 'YO', '22', '22', NULL, '22', '22', 'asdfsaf', NULL, '33', '2', '33', 'asdf', 'Inactive', NULL, '2026-01-22 02:35:15', '2026-01-22 02:35:15', 25, 'HR Administrator', 'Male', 'Married', 25, 'ASDF', 'ASDF', 'ASDF', 'asdf', 'ASDF', 2, 'ASDF', 'ASDF', 32, 'ASDF', 'ASDF', 32, 'ASDF', 'asdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ASDF', 'ASDF', '2012 - 2020', 'ASDF', 'FDSA', '2012 - 2020', '[{\"title\":\"ASDF\",\"by\":\"FSAD\",\"date\":\"2026-01-22\"}]', 0, NULL, '[{\"position\":\"SDAF\",\"company_name\":\"FD\",\"company_address\":\"SD\",\"company_phone\":\"09563211331\",\"period\":\"09\\/2002 - 08\\/2050\",\"reason\":\"asdf\"}]', NULL, 'uploads/employees/9.png'),
(10, 32424, 'LG', 'aldrin', 'ALDRIN', 'JOHN', 'UNASSIGNED', 'NCR0-94309234234324', '2025-01-22', '2025-02-22', '2026-01-22', '+63-9563211331', '23-424324', '3453-4435-4354', '233-453-454-35', '54-354353454-3', NULL, NULL, '2000-08-27', '5\'5\"', '22', 'FSADFASDF', 'ASDF', 'Colleague', 'FSDA', '+63-9563211331', NULL, NULL, NULL, NULL, 'A-', 'Muslim', '[\"Walk-in\"]', NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'No', NULL, 'No', NULL, 'Yes', NULL, 'No', NULL, NULL, 'sadfas', 'asdfasfd', NULL, 'asdfasd', 'fasdfsad', NULL, 'uploads/employees/fingerprints/10_fingerprint_right_thumb.png', 'uploads/employees/fingerprints/10_fingerprint_right_index.jpg', 'uploads/employees/fingerprints/10_fingerprint_right_middle.png', 'uploads/employees/fingerprints/10_fingerprint_right_ring.jpg', 'uploads/employees/fingerprints/10_fingerprint_right_little.png', 'uploads/employees/fingerprints/10_fingerprint_left_thumb.png', 'uploads/employees/fingerprints/10_fingerprint_left_index.png', 'uploads/employees/fingerprints/10_fingerprint_left_middle.png', 'uploads/employees/fingerprints/10_fingerprint_left_ring.png', 'uploads/employees/fingerprints/10_fingerprint_left_little.png', NULL, 'YO', 'NO', 'YO', 'NO', 'YO', 'NO', 'YO', 'NO', 'YO', NULL, 'NO', 'NO', 'NO', 'NO', 'NO', '33', '33', NULL, '33', '33', 'asdfasfd', NULL, '3', '2', '4', 'sdf', 'Terminated', NULL, '2026-01-22 03:20:59', '2026-01-22 07:24:55', 25, 'HR Administrator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 23432, 'SG', 'INONO', 'INANO', 'NANA', 'Security Guard - Mall', 'NCRS-JASHFDKJH', '2026-01-27', NULL, '2026-01-27', NULL, '23-234234-323', '23-234234-323', '23-234234-323', '23-234234-323', NULL, NULL, '2000-08-27', NULL, '55', 'SDFASDF', 'ASDF', 'Child', 'SDFADF', NULL, NULL, NULL, NULL, NULL, 'A-', 'Taoist', '[\"Walk-in\"]', NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'No', NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'asfa', 'sdfasd', NULL, 'fasf', 'asdfasdf', NULL, 'uploads/employees/fingerprints/11_fingerprint_right_thumb.jpg', 'uploads/employees/fingerprints/11_fingerprint_right_index.jpg', 'uploads/employees/fingerprints/11_fingerprint_right_middle.jpg', 'uploads/employees/fingerprints/11_fingerprint_right_ring.jpg', 'uploads/employees/fingerprints/11_fingerprint_right_little.jpg', 'uploads/employees/fingerprints/11_fingerprint_left_thumb.jpg', 'uploads/employees/fingerprints/11_fingerprint_left_index.jpg', 'uploads/employees/fingerprints/11_fingerprint_left_middle.jpg', 'uploads/employees/fingerprints/11_fingerprint_left_ring.jpg', 'uploads/employees/fingerprints/11_fingerprint_left_little.jpg', NULL, 'NO', 'NO', 'NO', 'YO', 'NO', 'YO', 'NO', 'YO', 'NO', NULL, 'YO', 'NO', 'YO', 'NO', 'YO', '4353', '', '3453', '434', '3443', 'asdfasdfasd', NULL, '343', '2', '343', '434', 'Active', 'Probationary', '2026-01-27 04:07:11', '2026-01-27 06:25:18', 25, 'HR Administrator', 'Male', 'Single', 25, 'SDFASDF', 'ASDFASDFA', 'FASDFS', 'asdfasdf', 'ASDFAS', 22, 'SDFASFD', 'DFASDF', 22, 'SFASDF', 'ASDF', 22, 'FASFDAS', 'asdfasdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ASDFASDF', 'ASDFA', '2012 - 2015', 'ASDFASDF', 'ASDFASD', '2012 - 2015', '[{\"title\":\"SDFASD\",\"by\":\"ASDFASDF\",\"date\":\"2026-01-27\"}]', 0, NULL, '[{\"position\":\"ASFASDF\",\"company_name\":\"SADF\",\"company_address\":\"SDFASDF\",\"company_phone\":\"09563211331\",\"period\":\"08\\/2034 - 08\\/2055\",\"reason\":\"asdfs\"}]', NULL, NULL);

--
-- Triggers `employees`
--
DELIMITER $$
CREATE TRIGGER `log_employee_changes` AFTER UPDATE ON `employees` FOR EACH ROW BEGIN
    INSERT INTO audit_logs (user_id, action, table_name, record_id, old_values, new_values, created_at)
    VALUES (
        1, 
        'UPDATE',
        'employees',
        NEW.id,
        JSON_OBJECT(
            'surname', OLD.surname,
            'first_name', OLD.first_name,
            'status', OLD.status,
            'post', OLD.post
        ),
        JSON_OBJECT(
            'surname', NEW.surname,
            'first_name', NEW.first_name,
            'status', NEW.status,
            'post', NEW.post
        ),
        NOW()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_post_filled_count_after_employee_update` AFTER UPDATE ON `employees` FOR EACH ROW BEGIN
    IF OLD.status != NEW.status THEN
        CALL UpdatePostFilledCount((SELECT id FROM posts WHERE post_title = NEW.post LIMIT 1));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_alerts`
--

CREATE TABLE `employee_alerts` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `alert_type` enum('license_expiry','document_expiry','missing_documents','contract_expiry','training_due','medical_expiry','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `alert_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `status` enum('active','acknowledged','resolved','dismissed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_by` int DEFAULT NULL,
  `acknowledged_by` int DEFAULT NULL,
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_alerts`
--

INSERT INTO `employee_alerts` (`id`, `employee_id`, `alert_type`, `title`, `description`, `alert_date`, `due_date`, `priority`, `status`, `created_by`, `acknowledged_by`, `acknowledged_at`, `resolved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'license_expiry', 'Security License Expiring Soon', 'Security guard license (R4B-202309000367) will expire in 30 days. Please renew before expiration.', '2024-01-15', '2028-09-14', 'medium', 'active', 5, NULL, NULL, NULL, '2025-12-06 08:39:02', '2025-12-06 08:39:02'),
(2, 2, 'license_expiry', 'Security License Expiring Soon', 'Lady guard license (NCR-202411000339) will expire in 45 days. Please renew before expiration.', '2024-01-15', '2029-11-07', 'medium', 'active', 5, NULL, NULL, NULL, '2025-12-06 08:39:02', '2025-12-06 08:39:02'),
(3, 3, 'training_due', 'Training Required', 'RLM training is due. Please complete required training before expiration.', '2024-01-15', '2025-04-05', 'high', 'active', 5, NULL, NULL, NULL, '2025-12-06 08:39:02', '2025-12-06 08:39:02'),
(4, 4, 'document_expiry', 'Medical Certificate Expiring', 'Medical certificate will expire soon. Please renew for continued employment.', '2024-01-15', '2024-02-15', 'urgent', 'active', 5, NULL, NULL, NULL, '2025-12-06 08:39:02', '2025-12-06 08:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `employee_checklist`
--

CREATE TABLE `employee_checklist` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `item_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed` tinyint(1) DEFAULT '0',
  `completed_by` int DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `employee_details`
-- (See below for the actual view)
--
CREATE TABLE `employee_details` (
`created_at` timestamp
,`date_hired` date
,`employee_no` int
,`employee_type` enum('SG','LG','SO')
,`full_name` varchar(153)
,`id` int
,`license_exp_date` date
,`license_no` varchar(50)
,`license_status` varchar(13)
,`post` varchar(100)
,`status` enum('Active','Inactive','Terminated','Suspended')
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `employee_files`
--

CREATE TABLE `employee_files` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL COMMENT 'Employee this file belongs to',
  `uploaded_by` int NOT NULL COMMENT 'User ID who uploaded the file',
  `original_filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Original filename for display',
  `stored_filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'UUID filename on disk',
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Full path relative to storage root',
  `category` enum('Personal Records','Contracts','Government IDs','Certifications','Other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Other' COMMENT 'Document category',
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'MIME type (validated server-side)',
  `size_bytes` bigint NOT NULL COMMENT 'File size in bytes',
  `storage_driver` enum('local','minio') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local' COMMENT 'Storage backend used',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'Soft delete timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Secure employee file storage metadata';

--
-- Dumping data for table `employee_files`
--

INSERT INTO `employee_files` (`id`, `employee_id`, `uploaded_by`, `original_filename`, `stored_filename`, `file_path`, `category`, `mime_type`, `size_bytes`, `storage_driver`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 11, 25, 'Docker Documentation.docx', '0a170bff-ca33-448c-b131-6d787ca32f6d.docx', '11/0a170bff-ca33-448c-b131-6d787ca32f6d.docx', 'Personal Records', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 20793, 'local', '2026-01-28 02:04:27', '2026-01-28 02:04:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_violations`
--

CREATE TABLE `employee_violations` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `violation_type_id` int NOT NULL,
  `violation_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `severity` enum('Major','Minor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `offense_number` int DEFAULT '1' COMMENT '1st, 2nd, 3rd, 4th, or 5th offense for this violation type',
  `sanction` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sanction_date` date DEFAULT NULL,
  `reported_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Under Review','Resolved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_violations`
--

INSERT INTO `employee_violations` (`id`, `employee_id`, `violation_type_id`, `violation_date`, `description`, `severity`, `offense_number`, `sanction`, `sanction_date`, `reported_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 10, 178, '2026-01-22', 'bbbbbbbbbbbbbbbb', 'Major', 1, 'asfdsdf', '2026-01-22', 'asdfasdfasd', 'Under Review', '2026-01-22 08:15:13', '2026-01-29 00:33:13'),
(2, 10, 178, '2026-01-22', 'asfdsadf', 'Major', 2, 'asfdsdf', '2026-01-22', 'asdfasdfasd', 'Under Review', '2026-01-22 08:15:57', '2026-01-22 08:15:57'),
(3, 9, 174, '2026-01-22', 'sadfdsafsdfasd', 'Major', 1, '7 days suspension', '2026-01-20', 'fsdfdsfsdafdsfasdfdsa', 'Pending', '2026-01-22 08:19:41', '2026-01-22 08:19:41'),
(4, 8, 182, '2026-01-22', 'asdfas', 'Major', 1, 'fsadfsadf', '2026-01-22', 'safasffdaff', 'Pending', '2026-01-22 08:41:22', '2026-01-22 08:41:22');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `event_type` enum('Holiday','Examination','Academic','Special Event','Other') COLLATE utf8mb4_unicode_ci DEFAULT 'Other',
  `holiday_type` enum('Regular Holiday','Special Non-Working Holiday','Local Special Non-Working Holiday','N/A') COLLATE utf8mb4_unicode_ci DEFAULT 'N/A',
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `start_date`, `start_time`, `end_date`, `end_time`, `event_type`, `holiday_type`, `category`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'asdfafsd', NULL, '2026-01-21', NULL, NULL, NULL, 'Academic', 'N/A', NULL, NULL, '2026-01-26 04:07:34', '2026-01-26 04:07:34'),
(2, 'sdafsd', NULL, '2026-01-26', NULL, NULL, NULL, 'Examination', 'N/A', NULL, NULL, '2026-01-26 04:46:59', '2026-01-26 04:46:59'),
(3, 'hello', 'hello', '2026-01-28', '10:32:00', '2026-01-30', '10:34:00', 'Special Event', 'N/A', 'sdfasdfsafsa', NULL, '2026-01-28 02:32:36', '2026-01-28 02:32:36');

-- --------------------------------------------------------

--
-- Table structure for table `file_audit_logs`
--

CREATE TABLE `file_audit_logs` (
  `id` int NOT NULL,
  `action` enum('upload','download','delete','view') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Action performed',
  `user_id` int NOT NULL COMMENT 'User who performed the action',
  `employee_id` int NOT NULL COMMENT 'Employee whose file was accessed',
  `file_id` int DEFAULT NULL COMMENT 'File ID (NULL if file was deleted)',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP address of requester',
  `user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User agent string',
  `success` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Whether action succeeded',
  `error_message` text COLLATE utf8mb4_unicode_ci COMMENT 'Error message if action failed',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Audit log for file operations';

--
-- Dumping data for table `file_audit_logs`
--

INSERT INTO `file_audit_logs` (`id`, `action`, `user_id`, `employee_id`, `file_id`, `ip_address`, `user_agent`, `success`, `error_message`, `created_at`) VALUES
(1, 'upload', 25, 11, NULL, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 0, 'Failed to create storage directory', '2026-01-28 02:02:42'),
(2, 'upload', 25, 11, 1, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 1, NULL, '2026-01-28 02:04:27');

-- --------------------------------------------------------

--
-- Table structure for table `hr_tasks`
--

CREATE TABLE `hr_tasks` (
  `id` int NOT NULL,
  `task_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` enum('Employee Record','License','Leave Request','Clearance','Cash Bond','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Other',
  `assigned_by` int DEFAULT NULL,
  `assigned_by_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `urgency_level` enum('normal','important','critical') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'normal',
  `location_page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Where can it be found',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Notes created by the person who alerted',
  `status` enum('pending','in_progress','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `assigned_to` int DEFAULT NULL COMMENT 'HR Admin user ID',
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_balances`
--

CREATE TABLE `leave_balances` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `leave_type` enum('vacation','sick','personal','emergency','maternity','paternity','bereavement') NOT NULL,
  `year` int NOT NULL,
  `total_entitlement` int DEFAULT '0',
  `used_days` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `leave_balances`
--

INSERT INTO `leave_balances` (`id`, `employee_id`, `leave_type`, `year`, `total_entitlement`, `used_days`, `created_at`, `updated_at`) VALUES
(1, 1, 'vacation', 2024, 15, 5, '2026-01-20 13:48:27', '2026-01-20 13:48:27'),
(2, 1, 'sick', 2024, 10, 2, '2026-01-20 13:48:27', '2026-01-20 13:48:27');

-- --------------------------------------------------------

--
-- Stand-in structure for view `leave_balance_summary`
-- (See below for the actual view)
--
CREATE TABLE `leave_balance_summary` (
`employee_id` int
,`employee_name` varchar(102)
,`employee_type` enum('SG','LG','SO')
,`leave_type` enum('vacation','sick','personal','emergency','maternity','paternity','bereavement')
,`post` varchar(100)
,`remaining_days` bigint
,`total_entitlement` int
,`used_days` int
,`year` int
);

-- --------------------------------------------------------

--
-- Table structure for table `notification_status`
--

CREATE TABLE `notification_status` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'User who read/dismissed the notification',
  `notification_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ID of the notification (can be numeric or string like license_123)',
  `notification_type` enum('alert','license','clearance','task','message') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'alert' COMMENT 'Type of notification',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether the notification has been read',
  `is_dismissed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether the notification has been dismissed',
  `read_at` timestamp NULL DEFAULT NULL COMMENT 'When the notification was read',
  `dismissed_at` timestamp NULL DEFAULT NULL COMMENT 'When the notification was dismissed',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks read and dismissed status of notifications per user';

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `post_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_type` enum('SG','LG','SO') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `requirements` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `responsibilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `required_count` int DEFAULT '1',
  `filled_count` int DEFAULT '0',
  `priority` enum('Low','Medium','High','Urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Medium',
  `status` enum('Active','Inactive','Closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Active',
  `shift_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_hours` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary_range` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `benefits` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reporting_to` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `post_title`, `post_code`, `department`, `employee_type`, `location`, `description`, `requirements`, `responsibilities`, `required_count`, `filled_count`, `priority`, `status`, `shift_type`, `work_hours`, `salary_range`, `benefits`, `reporting_to`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'Security Guard - Mall', 'SG-MALL-001', 'Security', 'SG', 'SM Mall of Asia', 'Provide security services for mall operations', 'Valid Security Guard License, Physical fitness, Good communication skills', 'Patrol assigned areas, Monitor CCTV, Respond to incidents, Customer assistance', 5, 1, 'High', 'Active', 'Rotating', '8 hours', 'â‚±15,000 - â‚±18,000', 'SSS, PhilHealth, Pag-IBIG, 13th month pay', 'Security Supervisor', '2024-12-31', '2025-12-06 08:39:02', '2026-01-27 06:25:18'),
(2, 'Lady Guard - Office Building', 'LG-OFFICE-001', 'Security', 'LG', 'BGC Office Tower', 'Provide security services for office building', 'Valid Lady Guard License, Professional appearance, Customer service skills', 'Access control, Visitor management, Emergency response, Building patrol', 3, 1, 'Medium', 'Active', 'Day Shift', '8 hours', 'â‚±16,000 - â‚±19,000', 'SSS, PhilHealth, Pag-IBIG, 13th month pay, HMO', 'Security Manager', '2024-12-31', '2025-12-06 08:39:02', '2025-12-06 08:39:02'),
(3, 'Security Officer - Headquarters', 'SO-HQ-001', 'Security', 'SO', 'Main Office', 'Supervise security operations and personnel', 'Security Officer License, Leadership skills, 3+ years experience', 'Team supervision, Security planning, Incident investigation, Training coordination', 2, 1, 'Urgent', 'Active', 'Administrative', '8 hours', 'â‚±25,000 - â‚±30,000', 'SSS, PhilHealth, Pag-IBIG, 13th month pay, HMO, Car allowance', 'Security Director', '2024-12-31', '2025-12-06 08:39:02', '2025-12-06 08:39:02'),
(4, 'Security Guard - Residential', 'SG-RES-001', 'Security', 'SG', 'Exclusive Subdivision', 'Provide security for residential community', 'Valid Security Guard License, Trustworthy, Community-oriented', 'Gate control, Community patrol, Resident assistance, Incident reporting', 4, 1, 'Medium', 'Active', 'Night Shift', '12 hours', 'â‚±14,000 - â‚±17,000', 'SSS, PhilHealth, Pag-IBIG, 13th month pay', 'Property Manager', '2024-12-31', '2025-12-06 08:39:02', '2025-12-06 08:39:02'),
(5, 'Lady Guard - Hospital', 'LG-HOSP-001', 'Security', 'LG', 'Metro Hospital', 'Provide security services in hospital environment', 'Valid Lady Guard License, Medical knowledge preferred, Compassionate', 'Patient area security, Visitor screening, Emergency response, Medical escort', 2, 0, 'High', 'Active', 'Rotating', '8 hours', 'â‚±17,000 - â‚±20,000', 'SSS, PhilHealth, Pag-IBIG, 13th month pay, HMO, Hazard pay', 'Hospital Security Chief', '2024-12-31', '2025-12-06 08:39:02', '2025-12-06 08:39:02'),
(6, 'Security Officer - Field Operations', 'SO-FIELD-001', 'Security', 'SO', 'Various Locations', 'Supervise field security operations', 'Security Officer License, Field experience, Problem-solving skills', 'Field supervision, Site assessments, Client relations, Team coordination', 1, 1, 'Medium', 'Active', 'Field Work', '8 hours', 'â‚±22,000 - â‚±28,000', 'SSS, PhilHealth, Pag-IBIG, 13th month pay, HMO, Field allowance', 'Operations Manager', '2024-12-31', '2025-12-06 08:39:02', '2025-12-06 08:39:02'),
(7, 'sdafsadfasdf', 'sfd32432432', 'Security', 'SG', 'asdfsdfsdaf', 'asdfasdf', 'asdfasdf', 'asdfsadfds', 1, 0, 'Medium', 'Active', 'Day', '8 hours', '23423432', 'asdfsdf', 'sadfasdf', '2025-12-14', '2025-12-14 08:34:48', '2025-12-14 08:34:48'),
(8, 'asdfasdf', 'ssdf2332423', 'Administration', 'SG', 'asdfasdf', 'asdfsdf', 'sadfsdf', 'asdfsdfasdf', 1, 0, 'Medium', 'Active', 'Day', '8 hours', '24234234', 'asdfsdfasd', 'asfdasd', '2025-12-14', '2025-12-14 09:14:40', '2025-12-14 09:14:40'),
(9, 'ZZZ', 'aaaaaaaaaaaaaaaaaaa', 'Security', 'LG', 'aaaaaaaaaaaaaaaaaaa', 'aaaaaaaaaaaaaaaaaaa', 'aaaaaaaaaaaaaaaaaaa', 'aaaaaaaaaaaaaaaaaaa', 1, 1, 'Medium', 'Active', 'Day', '12312', '123123213', 'asdfasdf', 'aaaaaaaaaaaaaaaaaaa', '2026-01-27', '2026-01-27 06:14:20', '2026-01-28 03:50:22');

-- --------------------------------------------------------

--
-- Stand-in structure for view `post_statistics`
-- (See below for the actual view)
--
CREATE TABLE `post_statistics` (
`available_positions` int
,`created_at` int
,`employee_type` int
,`filled_count` int
,`id` int
,`location` int
,`post_title` int
,`priority` int
,`required_count` int
,`status` int
);

-- --------------------------------------------------------

--
-- Table structure for table `security_settings`
--

CREATE TABLE `security_settings` (
  `key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `security_settings`
--

INSERT INTO `security_settings` (`key`, `value`, `updated_at`) VALUES
('password_expiry_days', '90', '2026-01-15 08:23:05'),
('password_min_length', '8', '2026-01-15 07:17:22'),
('password_require_special', '0', '2026-01-15 08:39:28');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int NOT NULL,
  `ticket_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `user_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` enum('system_issue','access_request','data_issue','feature_request','general_inquiry','bug_report') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'general_inquiry',
  `priority` enum('low','medium','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'medium',
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('open','in_progress','pending_user','resolved','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'open',
  `assigned_to` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` int NOT NULL,
  `ticket_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `user_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_internal` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_off_requests`
--

CREATE TABLE `time_off_requests` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `request_type` enum('vacation','sick','personal','emergency','maternity','paternity','bereavement','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `approved_by` int DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_off_requests`
--

INSERT INTO `time_off_requests` (`id`, `employee_id`, `request_type`, `start_date`, `end_date`, `total_days`, `reason`, `status`, `approved_by`, `approved_at`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 1, 'vacation', '2024-02-01', '2024-02-05', 5, 'Family vacation', 'approved', 5, '2024-01-20 02:30:00', NULL, '2025-12-06 08:39:02', '2025-12-06 08:39:02'),
(2, 2, 'sick', '2024-01-20', '2024-01-22', 3, 'Flu symptoms', 'approved', 5, '2024-01-19 06:15:00', NULL, '2025-12-06 08:39:02', '2025-12-06 08:39:02'),
(3, 3, 'personal', '2024-02-10', '2024-02-10', 1, 'Personal appointment', 'approved', 25, '2026-01-28 04:19:10', NULL, '2025-12-06 08:39:02', '2026-01-28 04:19:10'),
(4, 4, 'emergency', '2024-01-25', '2024-01-25', 1, 'Family emergency', 'approved', 5, '2024-01-24 08:45:00', NULL, '2025-12-06 08:39:02', '2025-12-06 08:39:02'),
(5, 5, 'vacation', '2024-03-15', '2024-03-20', 6, 'Holiday break', 'pending', NULL, NULL, NULL, '2025-12-06 08:39:02', '2025-12-06 08:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','admin','humanresource','accounting','operation','logistics','employee','developer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  `status` enum('active','inactive','suspended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `employee_id` int DEFAULT NULL COMMENT 'Link to employees table if user is an employee',
  `department` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Profile picture path',
  `last_login` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `failed_login_attempts` int DEFAULT '0',
  `locked_until` timestamp NULL DEFAULT NULL COMMENT 'Account lockout until this timestamp',
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_secret` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` int DEFAULT NULL COMMENT 'User who created this account',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `name`, `role`, `status`, `employee_id`, `department`, `phone`, `avatar`, `last_login`, `last_login_ip`, `failed_login_attempts`, `locked_until`, `password_changed_at`, `remember_token`, `two_factor_secret`, `two_factor_enabled`, `two_factor_recovery_codes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'hr.admin', 'hr.admin@goldenz5.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria L. Santos', 'employee', 'active', NULL, 'Human Resources', '0917-100-0001', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2025-12-01 02:11:35', '2025-12-01 02:11:35'),
(2, 'hr.lead', 'hr.lead@goldenz5.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Angela M. Reyes', 'employee', 'active', NULL, 'Human Resources', '0917-100-0002', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-12-01 02:11:35', '2025-12-01 02:11:35'),
(3, 'hr.ops', 'hr.ops@goldenz5.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos P. Dizon', 'employee', 'active', NULL, 'Human Resources', '0917-100-0003', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-12-01 02:11:35', '2025-12-01 02:11:35'),
(4, 'dev.lead', 'dev.lead@goldenz5.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jacob R. Villanueva', 'employee', 'active', NULL, 'IT/Development', '0917-200-0001', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-12-01 02:11:35', '2025-12-01 02:11:35'),
(5, 'dev.engineer', 'dev.engineer@goldenz5.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lara S. Mendoza', 'employee', 'active', NULL, 'IT/Development', '0917-200-0002', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-12-01 02:11:35', '2025-12-01 02:11:35'),
(6, 'dev.ops', 'dev.ops@goldenz5.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Noel T. Cruz', 'employee', 'active', NULL, 'IT/Development', '0917-200-0003', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, 1, '2025-12-01 02:11:35', '2025-12-01 02:11:35'),
(25, 'hradmin', 'hradmin@goldenz5.com', '$2y$10$2Fp4cu96Oey7AQ87V/fWd.EVqmVzV5chLxspeoyzzSPooNUOXxYDq', 'HR Administrator', 'employee', 'active', NULL, NULL, NULL, '', '2026-01-29 05:58:21', '172.18.0.1', 0, NULL, '2026-01-13 05:22:28', NULL, NULL, 0, NULL, NULL, '2025-12-11 17:49:50', '2026-01-29 05:59:05'),
(30, 'grey', 'greycruz00000000@gmail.com', '$2y$10$7qyDoZ3GUP4okfOd0TGQpeDWUkPAdvBJTXDnTVqOXCoOxOu.z/Vui', 'aldrin', 'employee', 'active', NULL, 'dikoalam', '09563211331', 'uploads/users/user_30_1769142679.jpg', '2026-01-30 05:25:43', '172.18.0.1', 0, NULL, '2026-01-15 05:31:24', NULL, NULL, 0, NULL, 25, '2026-01-13 08:36:54', '2026-01-30 05:25:43'),
(31, 'amor', 'amor@gmail.com', '$2y$10$evvPUIl.aoXr/icZ85PNH.zTj4wnx.TEzKxHqijLpO0NgoAw7OyAa', 'amor', 'employee', 'active', NULL, 'asdfjh', '09562312321', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, 30, '2026-01-13 09:58:43', '2026-01-13 09:58:43'),
(32, 'ChristianAmor', 'christian5787264@gmail.com', '$2y$10$4jtmtLYPqQBDYvKq3D9AouI3kPDYDJObT7mtYZ.PHY2j5rx64rqj.', 'christian amor', 'employee', 'active', NULL, 'it', '09613014462', NULL, '2026-01-22 05:24:55', '172.18.0.1', 0, NULL, '2026-01-15 08:18:54', NULL, 'ZHLMOAXVWIX2DK4A', 1, NULL, 30, '2026-01-13 10:01:09', '2026-01-22 05:24:55'),
(33, 'aaaaa', 'aa@gmail.com', '$2y$10$qQKlgAavsZ6C7QHcXYq2TeSoJeGi7jZs0VXQZMjAvdgFN1eSSkR.6', 'alskdjfjklfdsa', 'employee', 'active', NULL, 'askldjf', '2980374234', NULL, '2026-01-15 05:28:23', '192.168.1.7', 0, NULL, '2026-01-15 06:29:12', 'd95a49d4bb7ba0a9c1a277b9fe776d20bbc3c7fd633877cba5b9f4198b03245d|1768458552', NULL, 0, NULL, 30, '2026-01-15 03:07:15', '2026-01-16 04:54:16'),
(34, 'zzzzz', 'zzz@gmail.com', '$2y$10$qQKlgAavsZ6C7QHcXYq2TeSoJeGi7jZs0VXQZMjAvdgFN1eSSkR.6', 'salkjfksldaj', 'employee', 'active', NULL, 'salkjfdklsajd', '213849089234', NULL, '2026-01-15 05:28:23', '192.168.1.7', 0, NULL, '2026-01-15 06:29:12', 'd95a49d4bb7ba0a9c1a277b9fe776d20bbc3c7fd633877cba5b9f4198b03245d|1768458552', NULL, 0, NULL, 30, '2026-01-15 03:08:25', '2026-01-16 04:54:16'),
(35, 'bbbbaaa', 'bb@gmail.com', '$2y$10$qQKlgAavsZ6C7QHcXYq2TeSoJeGi7jZs0VXQZMjAvdgFN1eSSkR.6', 'sadfsdfa', 'employee', 'active', NULL, 'sadfasfd', '2893749823', NULL, '2026-01-15 05:28:23', '192.168.1.7', 0, NULL, '2026-01-15 06:29:12', 'd95a49d4bb7ba0a9c1a277b9fe776d20bbc3c7fd633877cba5b9f4198b03245d|1768458552', NULL, 0, NULL, 30, '2026-01-15 03:21:17', '2026-01-16 04:54:16'),
(36, 'zazaza', 'zz@gmail.com', '$2y$10$qQKlgAavsZ6C7QHcXYq2TeSoJeGi7jZs0VXQZMjAvdgFN1eSSkR.6', 'saddfsafdas', 'employee', 'active', NULL, 'asfsadf', '12312321', NULL, '2026-01-15 05:28:23', '192.168.1.7', 0, NULL, '2026-01-15 06:29:12', 'd95a49d4bb7ba0a9c1a277b9fe776d20bbc3c7fd633877cba5b9f4198b03245d|1768458552', NULL, 0, NULL, 30, '2026-01-15 03:23:16', '2026-01-16 04:54:16'),
(38, 'sssss', 'sss@gmail.com', '$2y$10$V.Tmj4tp3ANqZvMY77yJBurZ1kBuJGCwf5hMdu.vXR1uJbkxioxXi', 'ssssss', 'employee', 'active', NULL, 'sss', '23432432', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, 30, '2026-01-16 04:34:07', '2026-01-16 04:54:16'),
(39, 'aldrin', 'aldrininocencio212527@gmail.com', '$2y$10$Rey34d6jYHvRNwCUkr1WIeEAgzwtOLiXa18TTfaxaFc09Whg27xLK', 'Aldrin Inocencio', 'employee', 'active', NULL, 'IT', '09563211331', NULL, '2026-01-27 07:25:48', '172.18.0.1', 0, NULL, '2026-01-27 05:50:24', NULL, NULL, 0, NULL, 30, '2026-01-27 03:54:24', '2026-01-27 07:25:48');

-- --------------------------------------------------------

--
-- Table structure for table `violation_types`
--

CREATE TABLE `violation_types` (
  `id` int NOT NULL,
  `reference_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('Major','Minor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `subcategory` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sanctions` json DEFAULT NULL,
  `first_offense` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_offense` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `third_offense` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fourth_offense` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fifth_offense` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ra5487_compliant` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Violation types including RA 5487 Major Violations, Minor Violations, and RA 5487 Offenses with progressive sanctions';

--
-- Dumping data for table `violation_types`
--

INSERT INTO `violation_types` (`id`, `reference_no`, `name`, `category`, `subcategory`, `description`, `sanctions`, `first_offense`, `second_offense`, `third_offense`, `fourth_offense`, `fifth_offense`, `ra5487_compliant`, `is_active`, `created_at`, `updated_at`) VALUES
(109, 'MAJ-1', 'Engaging in espionage or sabotage of company properties or operation process', 'Major', NULL, 'Engaging in espionage or sabotage activities against company properties or operational processes.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:02:12'),
(110, 'MAJ-2', 'Circulating written or printed unauthorized materials inside the company premises.', 'Major', NULL, 'Distributing unauthorized written or printed materials within company premises.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:02:12'),
(111, 'MAJ-3', 'Selling of any company properties including guns and ammunitions.', 'Major', NULL, 'Unauthorized sale of company properties, including firearms and ammunition.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:02:12'),
(112, 'MAJ-4', 'Prostitution and violation of sexual harassment act under influence of drugs or using prohibited drugs while in the performance of duty and/or while inside the client/company premises.', 'Major', NULL, 'Engaging in prostitution, violating sexual harassment laws, or using prohibited drugs while on duty or within company/client premises.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:02:12'),
(113, 'MAJ-5', 'Acts of cowardism. defeatism or turning away from the performance of duty in the face of criminals, arsonist, pilferers, thieves or robbers and/or other criminal elements.', 'Major', NULL, 'Displaying cowardice, defeatism, or abandoning duty when facing criminals, arsonists, pilferers, thieves, robbers, or other criminal elements.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:02:12'),
(114, 'MAJ-6', 'Endangering the safety of client, client\'s personnel and relatives, tenants, colleagues or workers on the post by any of the following: 1. misconduct; 2. negligence; 3. disobedience.', 'Major', NULL, 'Endangering safety of clients, personnel, relatives, tenants, colleagues, or workers through misconduct, negligence, or disobedience.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:02:12'),
(115, 'MAJ-7', 'Stealing and/or in connivance with thieves while on and/or off official duty.', 'Major', NULL, 'Engaging in theft or conspiring with thieves while on or off official duty.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:02:12'),
(116, 'MAJ-8', 'Challenging or assaulting co-employees, clients, clients authorized representatives, client/s children and legally adopted children and relatives and or company\'s officers, company officers children and/or legally adopted children and relatives.', 'Major', NULL, 'Challenging or assaulting co-employees, clients, authorized representatives, children, relatives, or company officers and their families.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:02:12'),
(117, 'MAJ-18', 'Disclosure of operation methods or formula of the company or revealing any company information considered confidential to competitors.', 'Major', NULL, 'Disclosing company operational methods, formulas, or confidential information to competitors.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(118, 'MAJ-21', 'Malversation of funds and other financial accountabilities such as payroll for the guards, company funds for SSS, Philhealth, HDMF, Insurance remittances and any other similar acts.', 'Major', NULL, 'Misappropriation of funds including payroll, SSS, PhilHealth, HDMF, Insurance remittances, and other financial accountabilities.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(119, 'MAJ-24', 'Dishonesty, or any manner obstructing legitimate facts and concealing fraudulent action which is material to any subject that may greatly affect the company\'s operation in general and to the department where he/she is belong in particular.', 'Major', NULL, 'Dishonesty, obstruction of legitimate facts, or concealment of fraudulent actions materially affecting company operations.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(120, 'MAJ-9', 'Threatening, intimidating, bad mouthing, coercing, or disturbing fellow employees inside the company premises.', 'Major', NULL, 'Threatening, intimidating, bad mouthing, coercing, or disturbing fellow employees within company premises.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(121, 'MAJ-10', 'Provoking or instigating a fight, fighting or inflicting bodily harm upon another within the company premises.', 'Major', NULL, 'Provoking, instigating fights, or inflicting bodily harm upon others within company premises.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(122, 'MAJ-11', 'Leaving or abandoning the place of works without the authorization from the immediate superior.', 'Major', NULL, 'Leaving or abandoning the workplace without authorization from immediate superior.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(123, 'MAJ-12', 'Gross negligence resulting to damage of any company property and termination of security services with the clientele.', 'Major', NULL, 'Gross negligence causing damage to company property or termination of security services with clients.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(124, 'MAJ-13', 'Making false statement or testimony during investigation conducted by the management or derogatory remarks (oral or written) to the detriment of company\'s operation and loss of company\'s probity.', 'Major', NULL, 'Making false statements during investigations or derogatory remarks detrimental to company operations and reputation.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(125, 'MAJ-14', 'Sleeping on post during office or working hours.', 'Major', NULL, 'Sleeping while on post during office or working hours.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(126, 'MAJ-15', 'Fraud or willful breach of the trust and confidence entrusted to him/her by the management like misappropriation or malversation of funds, merchandise or other properties of the company and such other fraudulent acts.', 'Major', NULL, 'Fraud or willful breach of trust including misappropriation or malversation of funds, merchandise, or company properties.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(127, 'MAJ-16', 'Securing employment or working with other business establishment without notice or permissions from the management while still connected with the company.', 'Major', NULL, 'Securing employment or working with other businesses without notice or permission while still employed with the company.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(128, 'MAJ-17', 'Encouraging, coercing, initiating bribery including other employees to engage in any practice to violate company\'s rules and regulations.', 'Major', NULL, 'Encouraging, coercing, or initiating bribery or practices that violate company rules and regulations.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(129, 'MAJ-19', 'Threatening, drawing deadly weapon, dry firing, accidental firing and/or unlawful discharge of ammunitions, or offering violence to an officer, clients and client/s personnel and duly authorized representatives and relatives, tenants, workers, visitors and fellow employee without justifiable cause.', 'Major', NULL, 'Threatening with deadly weapons, dry firing, accidental firing, unlawful discharge of ammunition, or offering violence without justifiable cause.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(130, 'MAJ-20', 'Illegally selling or wrongfully disposing clients or tenant and/or company property.', 'Major', NULL, 'Illegally selling or wrongfully disposing of client, tenant, or company property.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(131, 'MAJ-23', 'Sending, giving, entrusting company issued firearm/s to any unauthorized personalities.', 'Major', NULL, 'Sending, giving, or entrusting company-issued firearms to unauthorized persons.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(132, 'MAJ-25', 'Allowing one\'s ID card to be used by the others.', 'Major', NULL, 'Allowing one\'s ID card to be used by others.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(133, 'MAJ-26', 'Entering into any business with third parties for personal gain or profit without company\'s authorization.', 'Major', NULL, 'Entering into business arrangements with third parties for personal gain without company authorization.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(134, 'MAJ-27', 'Fighting, horse playing, manhandling, shopping, slapping, punching, or quarreling with client, client authorized representatives, tenants, contractors, workers, visitors or co-employee without justifiable cause.', 'Major', NULL, 'Fighting, horse playing, manhandling, slapping, punching, or quarreling with clients, representatives, tenants, contractors, workers, visitors, or co-employees without justifiable cause.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(135, 'MAJ-22', 'Allowing unauthorized person/s to enter the client\'s premises or company premises without the required security pass.', 'Major', NULL, 'Allowing unauthorized persons to enter client or company premises without required security pass.', NULL, '30 days suspension & payment for disbursed/malversed funds', 'Dismissal & Payment for disbursed/malversed funds.', NULL, NULL, NULL, 1, 1, '2026-01-22 08:02:12', '2026-01-22 08:03:12'),
(136, 'MIN-1', 'Insubordination, disrespect, disobedience, or willfully and intentionally refusing to obey superior\'s legal order to perform task. Refuse to accept duty assignment, to include failure to submit reportorial requirements required by the management.', 'Minor', NULL, 'Insubordination, disrespect, disobedience, or willfully refusing to obey superior\'s legal orders or accept duty assignments, including failure to submit required reports.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(137, 'MIN-2', 'Habitual neglect of duty or responsibility.', 'Minor', NULL, 'Habitual neglect of duty or responsibility.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(138, 'MIN-3', 'Illegally obtaining material, tools, or supplies on fraudulent orders.', 'Minor', NULL, 'Illegally obtaining materials, tools, or supplies through fraudulent orders.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(139, 'MIN-4', 'Non-intentional sleeping on post during working hours.', 'Minor', NULL, 'Non-intentional sleeping while on post during working hours.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(140, 'MIN-5', 'Drinking liquor or drunkenness while in the performance of duty and/or while inside the office/client premises.', 'Minor', NULL, 'Drinking liquor or being drunk while performing duty or inside office/client premises.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(141, 'MIN-6', 'Gambling, play cara y cruz, card and all other forms of illegal gambling while on duty or inside the client premises.', 'Minor', NULL, 'Engaging in gambling, cara y cruz, card games, or other illegal gambling while on duty or inside client premises.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(142, 'MIN-7', 'Favoring and/or conniving with suppliers, creditors, fellow guards, fellow officers in the client and company in consideration of kickbacks or personal rebates working company funds.', 'Minor', NULL, 'Favoring or conniving with suppliers, creditors, fellow guards, or officers for kickbacks or personal rebates using company funds.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(143, 'MIN-8', 'Loitering and/or attending to personal matters without the authority from the immediate superior and/or Manager during hours and/or while within the company premises.', 'Minor', NULL, 'Loitering or attending to personal matters without authorization from immediate superior or Manager during working hours or within company premises.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(144, 'MIN-9', 'Accumulation of a total of sixty (60) minutes or more than 1 hour late in a week.', 'Minor', NULL, 'Accumulating 60 minutes or more of tardiness in a week.', NULL, '3 days suspension', '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(145, 'MIN-10', 'Failure to return to work upon authorized leave without reasonable or justifiable cause.', 'Minor', NULL, 'Failure to return to work after authorized leave without reasonable or justifiable cause.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(146, 'MIN-11', 'Failure to observe proper behaviour during working hours.', 'Minor', NULL, 'Failure to observe proper behavior during working hours.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(147, 'MIN-12', 'Use of vulgar, abusive and indecent words/language towards co-employees/staff and/or company officials.', 'Minor', NULL, 'Using vulgar, abusive, or indecent words/language towards co-employees, staff, or company officials.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(148, 'MIN-13', 'Using company\'s time, equipment, tools, or supplies for personal purposes without management permission.', 'Minor', NULL, 'Using company time, equipment, tools, or supplies for personal purposes without management permission.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(149, 'MIN-14', 'False report to have ailment or malingering or pretending to be sick in order to be absent for work.', 'Minor', NULL, 'Falsely reporting illness, malingering, or pretending to be sick to be absent from work.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(150, 'MIN-15', 'Refusal to observed company policy on security and safety requirements such as wearing personal protective equipment etc.', 'Minor', NULL, 'Refusal to observe company policy on security and safety requirements such as wearing personal protective equipment.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(151, 'MIN-16', 'Disrespect towards clients, relatives and personal tenants, superior officer and visitors.', 'Minor', NULL, 'Disrespect towards clients, relatives, personal tenants, superior officers, and visitors.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(152, 'MIN-17', 'Disobeying lawful orders from client, client\'s authorized representatives.', 'Minor', NULL, 'Disobeying lawful orders from clients or client\'s authorized representatives.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(153, 'MIN-18', 'Allowing client\'s or tenant\'s property to be brought out without the necessary gate pass or clearance.', 'Minor', NULL, 'Allowing client\'s or tenant\'s property to be brought out without necessary gate pass or clearance.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(154, 'MIN-19', 'Borrowing money and anything of value convertible to cash from client and/or client representative and tenants thereby damaging the company\'s good reputations.', 'Minor', NULL, 'Borrowing money or anything of value convertible to cash from clients, client representatives, or tenants, damaging company reputation.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(155, 'MIN-20', 'Cooking, washing clothes, bathing, and overnight staying without permission from the client or the company.', 'Minor', NULL, 'Cooking, washing clothes, bathing, or overnight staying without permission from client or company.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(156, 'MIN-21', 'Smoking inside the company premises except in the designated places for smoking.', 'Minor', NULL, 'Smoking inside company premises except in designated smoking areas.', NULL, 'Written reprimand', '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(157, 'MIN-22', 'Unauthorized use of company\'s telephone for national and overseas long distance call and other personal calls, company equipment and other company properties.', 'Minor', NULL, 'Unauthorized use of company telephone for long distance calls, personal calls, or company equipment and properties.', NULL, '15 days suspension & Payment for the entire billing acquired', '30 days suspension & Payment for the entire billing acquired', 'Payment for the entire billing acquired & Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(158, 'MIN-23', 'Unauthorized tampering of co-employee\'s pay envelope for the purpose of retrieving liabilities and obligation.', 'Minor', NULL, 'Unauthorized tampering with co-employee\'s pay envelope to retrieve liabilities and obligations.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(159, 'MIN-24', 'Reporting to work/duty with incomplete paraphernalia and uniform.', 'Minor', NULL, 'Reporting to work or duty with incomplete paraphernalia and uniform.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(160, 'MIN-25', 'Failure to attend company meeting, guard mounting, general formation, training activities, and other invitations for human development without justifiable reason/s.', 'Minor', NULL, 'Failure to attend company meetings, guard mounting, general formation, training activities, or human development invitations without justifiable reasons.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(161, 'MIN-26', 'Failure to report for duty without notifying the duty officer/direct superior.', 'Minor', NULL, 'Failure to report for duty without notifying the duty officer or direct superior.', NULL, '3 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(162, 'MIN-27', 'Entering the office, house of the client and management staff personnel without proper authority and/or legitimate transaction/purpose.', 'Minor', NULL, 'Entering the office or house of clients and management staff personnel without proper authority or legitimate transaction/purpose.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(163, 'MIN-28', 'Reporting to the assigned post without attending the required guard mounting.', 'Minor', NULL, 'Reporting to assigned post without attending required guard mounting.', NULL, '3 days suspension', '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(164, 'MIN-29', 'Failure to wear company ID card inside the company premises.', 'Minor', NULL, 'Failure to wear company ID card inside company premises.', NULL, 'Written reprimand', '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(165, 'MIN-30', 'Repeated or deliberate slow-down of work.', 'Minor', NULL, 'Repeated or deliberate slow-down of work.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:02:45', '2026-01-22 08:02:45'),
(166, 'A.1', 'As a security guard my fundamental duty is to protect lives and property and maintain order within my place of duty; protect the interest of my employer and our clients and the security and stability of our government and country without compromise and prejudice, honest in my action, words and thought; and do my best to uphold the principle: MAKADIOS, MAKABAYAN, MAKATAO at MAKAKALIKASAN.', 'Major', 'A. Security Guard Creed', 'Violation of the fundamental duty to protect lives and property, maintain order, and uphold MAKADIOS, MAKABAYAN, MAKATAO at MAKAKALIKASAN.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(167, 'B.1', 'He shall carry with him at all times during his tour of duty his license, identification card and duty detail order with an authority to carry firearm.', 'Major', 'B. Code of Conduct', 'Failure to carry license, identification card, and duty detail order with authority to carry firearm during tour of duty.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(168, 'B.2', 'He shall not use his license and other privileges if any, to be prejudice of the public, the client or customer and his agency.', 'Major', 'B. Code of Conduct', 'Using license and privileges to the prejudice of the public, client, customer, or agency.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(169, 'B.3', 'He shall not engage in any unnecessary conversation with any, to the prejudice of the public, the client or customer and his agency.', 'Major', 'B. Code of Conduct', 'Engaging in unnecessary conversations to the prejudice of the public, client, customer, or agency.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(170, 'B.4', 'He shall refrain from reading newspapers, magazines, books, etc, while actually performing his duties.', 'Major', 'B. Code of Conduct', 'Reading newspapers, magazines, books, etc. while actually performing duties.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(171, 'B.5', 'He shall not drink any intoxicating liquor immediately before and during his tour of duty.', 'Major', 'B. Code of Conduct', 'Drinking intoxicating liquor immediately before or during tour of duty.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(172, 'B.6', 'He shall know the location of the alarm box near his post and sound the alarm in case of fire or disorder.', 'Major', 'B. Code of Conduct', 'Failure to know location of alarm box near post and sound alarm in case of fire or disorder.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(173, 'B.7', 'He shall know how to operate any fire extinguisher at his post.', 'Major', 'B. Code of Conduct', 'Failure to know how to operate fire extinguisher at post.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(174, 'B.8', 'He shall know the location of the telephone and/or telephone number of the police precincts as well as the telephone numbers of the fire stations in the locality.', 'Major', 'B. Code of Conduct', 'Failure to know location of telephone or telephone numbers of police precincts and fire stations in the locality.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(175, 'B.9', 'He shall immediately notify the police in case of any sign of disorder, strike, riot, or any serious violation of the law.', 'Major', 'B. Code of Conduct', 'Failure to immediately notify police in case of disorder, strike, riot, or serious violation of the law.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(176, 'B.10', 'He or his group of guards, shall not participate or integrate any disorder, strike, riot, or any serious violations of the law.', 'Major', 'B. Code of Conduct', 'Participating or integrating in disorder, strike, riot, or serious violations of the law.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(177, 'B.11', 'He shall assist the police in the preservation and maintenance of peace and order and in the protection of life and property having in mind that the nature of his responsibilities is similar to that of the latter.', 'Major', 'B. Code of Conduct', 'Failure to assist police in preservation and maintenance of peace and order and protection of life and property.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(178, 'B.12', 'He shall familiarize himself by heart with the Private Security Agency Law (RA 5487, as amended) and these implementing rules and regulations.', 'Major', 'B. Code of Conduct', 'Failure to familiarize by heart with RA 5487 and implementing rules and regulations.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(179, 'B.13', 'When issued a Firearms he should not lend his Firearms to anybody.', 'Major', 'B. Code of Conduct', 'Lending issued firearms to anybody.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(180, 'B.14', 'He shall always be in proper uniform and shall always carry with him his basic requirements, and equipment\'s such as writing notebook, ballpen, night stick (baton) and/or radio.', 'Major', 'B. Code of Conduct', 'Failure to be in proper uniform or carry basic requirements and equipment such as writing notebook, ballpen, night stick (baton), and/or radio.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(181, 'B.15', 'He shall endeavor at all times, to merit and be worthy of the trust and confidence of the agency he represents and the client he serves.', 'Major', 'B. Code of Conduct', 'Failure to merit and be worthy of the trust and confidence of the agency and client.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(182, 'C.1', 'As a security guard/detective his fundamental duty is to serve the interest or mission of his agency in compliance with the contract entered into with clients or customers of the agency he is supposed to serve.', 'Major', 'C. Code of Ethics', 'Failure to serve the interest or mission of agency in compliance with contract entered into with clients or customers.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(183, 'C.2', 'He shall be honest in thoughts and deeds both in his personal and official actuations, obeying the laws of the land and the regulations prescribed by his agency and those established by the company he is supposed to protect.', 'Major', 'C. Code of Ethics', 'Failure to be honest in thoughts and deeds, or failure to obey laws and regulations.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(184, 'C.3', 'He shall not reveal any confidential information confided to him as security guard and such other matters imposed upon him by law.', 'Major', 'C. Code of Ethics', 'Revealing confidential information confided as security guard or matters imposed by law.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(185, 'C.4', 'He shall act at all times with decorum and shall not permit personal feelings, prejudices and undue friendship to influence his actuation while in the performance of his functions.', 'Major', 'C. Code of Ethics', 'Failure to act with decorum or allowing personal feelings, prejudices, or undue friendship to influence performance.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(186, 'C.5', 'He shall not compromise with criminals and other lawless elements to the prejudice of the customers or clients and shall assist the government in its relentless drive against lawlessness and other forms of criminality.', 'Major', 'C. Code of Ethics', 'Compromising with criminals and lawless elements to the prejudice of customers or clients, or failure to assist government against lawlessness.', NULL, 'Dismissal', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(187, 'C.6', 'He shall carry out his assigned duties as required by law to the best of his ability and shall safeguard the life and property of the establishment he is assigned to.', 'Major', 'C. Code of Ethics', 'Failure to carry out assigned duties to the best of ability or safeguard life and property of assigned establishment.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(188, 'C.7', 'He shall wear his uniform, badge, patches, and insignia properly as a symbol of public trust and confidence, as an honest and trustworthy security guard and private detectives.', 'Major', 'C. Code of Ethics', 'Failure to wear uniform, badge, patches, and insignia properly as symbol of public trust and confidence.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(189, 'C.8', 'He shall keep his allegiance first to the government, then to the agency where is employed and to the establishment he is assigned to serve with loyalty and utmost dedication.', 'Major', 'C. Code of Ethics', 'Failure to keep allegiance first to government, then to agency, and to assigned establishment with loyalty and dedication.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(190, 'C.9', 'He shall diligently and progressively familiarize himself with the rules and regulations laid down by his agency and those of the customers or clients.', 'Major', 'C. Code of Ethics', 'Failure to diligently and progressively familiarize with rules and regulations of agency and customers or clients.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(191, 'C.10', 'He shall at all times be courteous, respectful and salute his superior officers.', 'Major', 'C. Code of Ethics', 'Failure to be courteous, respectful, and salute superior officers at all times.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(192, 'C.11', 'He shall report to duty always in proper uniform and neat in his appearance.', 'Major', 'C. Code of Ethics', 'Failure to report to duty in proper uniform and neat appearance.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(193, 'C.12', 'He shall learn at heart and strictly observe the laws and regulations governing the use of firearms.', 'Major', 'C. Code of Ethics', 'Failure to learn at heart and strictly observe laws and regulations governing the use of firearms.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(194, 'D.1', 'To protect life and properties and to protect/preserve the same with utmost diligence.', 'Major', 'D. Eleven General Orders', 'Failure to protect life and properties and protect/preserve the same with utmost diligence.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(195, 'D.2', 'To walk in an alert manner during my tour of duty and observe everything within sight or hearing.', 'Major', 'D. Eleven General Orders', 'Failure to walk in alert manner during tour of duty and observe everything within sight or hearing.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(196, 'D.3', 'To report all violations of regulations or orders I am instructed to enforce.', 'Major', 'D. Eleven General Orders', 'Failure to report all violations of regulations or orders instructed to enforce.', NULL, '15 days suspension', NULL, NULL, NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(197, 'D.4', 'To relay all calls from more distant from the guard house where I am stationed.', 'Major', 'D. Eleven General Orders', 'Failure to relay all calls from more distant from the guard house where stationed.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', 'Dismissal', NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(198, 'D.5', 'To quit my post only when properly relieved.', 'Major', 'D. Eleven General Orders', 'Quitting post without being properly relieved.', NULL, '30 days suspension', 'Dismissal', NULL, NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(199, 'D.6', 'To receive, obey and pass to the relieving guard all orders from the company officials, officers in the agency, supervisor, post in charge of shift leaders.', 'Major', 'D. Eleven General Orders', 'Failure to receive, obey, and pass to relieving guard all orders from company officials, agency officers, supervisor, post in charge, or shift leaders.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(200, 'D.7', 'To talk to no one except in the line of duty.', 'Major', 'D. Eleven General Orders', 'Talking to someone not in the line of duty.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(201, 'D.8', 'To sound or call the alarm in case of fire or disorder.', 'Major', 'D. Eleven General Orders', 'Failure to sound or call alarm in case of fire or disorder.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(202, 'D.9', 'To call the superior officer in any case not covered by the instructions.', 'Major', 'D. Eleven General Orders', 'Failure to call superior officer in any case not covered by instructions.', NULL, '15 days suspension', '30 days suspension', 'Dismissal', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(203, 'D.10', 'To salute all company official, officers of the agency, ranking public officials and officers of the AFP and PNP.', 'Major', 'D. Eleven General Orders', 'Failure to salute company officials, agency officers, ranking public officials, and officers of AFP and PNP.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(204, 'D.11', 'To be especially watchful at night, and during the time of challenging and to challenge all person on or near my post and allow no one to enter or pass on without proper authority.', 'Major', 'D. Eleven General Orders', 'Failure to be especially watchful at night, challenge all persons on or near post, or allow entry/passage without proper authority.', NULL, '7 days suspension', '15 days suspension', '30 days suspension', NULL, NULL, 1, 1, '2026-01-22 08:03:01', '2026-01-22 08:03:01'),
(205, 'aaaaaaaaaa', 'aaaaaaaaaa', 'Major', 'aaaaaaaaaa', 'aaaaaaaaaa', '{\"fifth_offense\": \"yes\", \"first_offense\": \"12\", \"third_offense\": \"34\", \"fourth_offense\": \"56\", \"second_offense\": \"23\"}', '12', '23', '34', '56', 'yes', 0, 1, '2026-01-27 07:01:14', '2026-01-27 07:01:14'),
(206, 'aaa', 'aaa', 'Minor', 'aaa', 'aaa', '{\"fifth_offense\": \"44\", \"first_offense\": \"12\", \"third_offense\": \"23\", \"fourth_offense\": \"34\", \"second_offense\": \"21\"}', '12', '21', '23', '34', '44', 0, 1, '2026-01-27 07:03:45', '2026-01-27 07:03:45'),
(207, 'KDE23454', 'bago lang', 'Major', 'RA 5487', 'wala pa naman', '{\"fifth_offense\": null, \"first_offense\": \"13\", \"third_offense\": \"15\", \"fourth_offense\": \"16\", \"second_offense\": \"14\"}', '13', '14', '15', '16', NULL, 1, 1, '2026-01-28 03:10:39', '2026-01-28 03:10:39'),
(208, 'bb', 'bb', 'Major', 'bb', 'bb', '{\"fifth_offense\": \"6\", \"first_offense\": \"1\", \"third_offense\": \"4\", \"fourth_offense\": \"5\", \"second_offense\": \"23\"}', '1', '23', '4', '5', '6', 0, 1, '2026-01-28 03:19:14', '2026-01-28 03:19:14'),
(209, 'ZZZZZZZZZZZ', 'ZZZZZZZZZZZ', 'Major', 'ZZZZZZZZZZZ', 'ZZZZZZZZZZZ', '{\"fifth_offense\": null, \"first_offense\": \"2\", \"third_offense\": \"2\", \"fourth_offense\": \"2\", \"second_offense\": \"1\"}', '2', '1', '2', '2', NULL, 1, 1, '2026-01-28 03:27:20', '2026-01-28 03:27:20'),
(210, 'zzzzzzzzzzzzzzzzzzzz', 'zzzzzzzzzzzzzzzzzzzzzzzz', 'Minor', 'zzzzzzzzzzzzzzzzzzzzzzzz', 'zzzzzzzzzzzzzzzzzzzzzzzz', '{\"fifth_offense\": \"1\", \"first_offense\": \"1\", \"third_offense\": \"1\", \"fourth_offense\": \"1\", \"second_offense\": \"1\"}', '1', '1', '1', '1', '1', 0, 1, '2026-01-28 03:33:32', '2026-01-28 03:33:32'),
(211, 'asdkjfhwuif', 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzz', 'Major', 'zzzzzzzzzzzzzzzzzzzzzzzz', 'zzzzzzzzzzzzzzzzzzzzzzzz', '{\"fifth_offense\": \"1\", \"first_offense\": \"1\", \"third_offense\": \"1\", \"fourth_offense\": \"1\", \"second_offense\": \"1\"}', '1', '1', '1', '1', '1', 0, 1, '2026-01-28 03:34:08', '2026-01-28 03:34:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_table_name` (`table_name`),
  ADD KEY `idx_record_id` (`record_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `backup_history`
--
ALTER TABLE `backup_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_conversation` (`user1_id`,`user2_id`),
  ADD KEY `idx_user1_id` (`user1_id`),
  ADD KEY `idx_user2_id` (`user2_id`),
  ADD KEY `idx_last_message_at` (`last_message_at`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sender_id` (`sender_id`),
  ADD KEY `idx_receiver_id` (`receiver_id`),
  ADD KEY `idx_conversation` (`sender_id`,`receiver_id`,`created_at`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_attachment` (`attachment_type`,`attachment_path`),
  ADD KEY `idx_deleted_by_sender` (`deleted_by_sender`),
  ADD KEY `idx_deleted_by_receiver` (`deleted_by_receiver`);

--
-- Indexes for table `chat_typing_status`
--
ALTER TABLE `chat_typing_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_recipient` (`user_id`,`recipient_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_recipient_id` (`recipient_id`),
  ADD KEY `idx_updated_at` (`updated_at`);

--
-- Indexes for table `dtr_adjustments`
--
ALTER TABLE `dtr_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_employee_date` (`employee_id`,`entry_date`),
  ADD KEY `idx_dtr_entry_id` (`dtr_entry_id`),
  ADD KEY `idx_adjusted_by` (`adjusted_by`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `dtr_entries`
--
ALTER TABLE `dtr_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_employee_date` (`employee_id`,`entry_date`),
  ADD KEY `idx_employee_id` (`employee_id`),
  ADD KEY `idx_entry_date` (`entry_date`),
  ADD KEY `idx_entry_type` (`entry_type`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_no` (`license_no`),
  ADD KEY `idx_employee_no` (`employee_no`),
  ADD KEY `idx_employee_type` (`employee_type`),
  ADD KEY `idx_post` (`post`),
  ADD KEY `idx_license_no` (`license_no`),
  ADD KEY `idx_license_exp` (`license_exp_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_convicted` (`convicted`),
  ADD KEY `idx_filed_case` (`filed_case`),
  ADD KEY `idx_prohibited_drugs` (`prohibited_drugs`),
  ADD KEY `idx_created_by` (`created_by`);

--
-- Indexes for table `employee_alerts`
--
ALTER TABLE `employee_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_employee_id` (`employee_id`),
  ADD KEY `idx_alert_type` (`alert_type`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_due_date` (`due_date`);

--
-- Indexes for table `employee_checklist`
--
ALTER TABLE `employee_checklist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_employee_item` (`employee_id`,`item_key`),
  ADD KEY `completed_by` (`completed_by`),
  ADD KEY `idx_employee_id` (`employee_id`),
  ADD KEY `idx_item_key` (`item_key`),
  ADD KEY `idx_completed` (`completed`);

--
-- Indexes for table `employee_files`
--
ALTER TABLE `employee_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_employee_id` (`employee_id`),
  ADD KEY `idx_uploaded_by` (`uploaded_by`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_deleted_at` (`deleted_at`);

--
-- Indexes for table `employee_violations`
--
ALTER TABLE `employee_violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_employee_id` (`employee_id`),
  ADD KEY `idx_violation_type_id` (`violation_type_id`),
  ADD KEY `idx_severity` (`severity`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_violation_date` (`violation_date`),
  ADD KEY `idx_offense_number` (`offense_number`),
  ADD KEY `idx_employee_violation_type` (`employee_id`,`violation_type_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_start_date` (`start_date`),
  ADD KEY `idx_end_date` (`end_date`),
  ADD KEY `idx_event_type` (`event_type`);

--
-- Indexes for table `file_audit_logs`
--
ALTER TABLE `file_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_employee_id` (`employee_id`),
  ADD KEY `idx_file_id` (`file_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `hr_tasks`
--
ALTER TABLE `hr_tasks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `task_number` (`task_number`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_due_date` (`due_date`),
  ADD KEY `idx_assigned_to` (`assigned_to`);

--
-- Indexes for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_employee_leave_year` (`employee_id`,`leave_type`,`year`),
  ADD KEY `idx_employee_id` (`employee_id`),
  ADD KEY `idx_leave_type` (`leave_type`),
  ADD KEY `idx_year` (`year`);

--
-- Indexes for table `notification_status`
--
ALTER TABLE `notification_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_notification` (`user_id`,`notification_id`,`notification_type`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_notification_id` (`notification_id`),
  ADD KEY `idx_notification_type` (`notification_type`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_is_dismissed` (`is_dismissed`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_code` (`post_code`),
  ADD KEY `idx_employee_type` (`employee_type`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_department` (`department`);

--
-- Indexes for table `security_settings`
--
ALTER TABLE `security_settings`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_no` (`ticket_no`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_ticket_no` (`ticket_no`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ticket_id` (`ticket_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `violation_types`
--
ALTER TABLE `violation_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_reference_no` (`reference_no`),
  ADD KEY `idx_ra5487_compliant` (`ra5487_compliant`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT for table `backup_history`
--
ALTER TABLE `backup_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `chat_typing_status`
--
ALTER TABLE `chat_typing_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT for table `dtr_adjustments`
--
ALTER TABLE `dtr_adjustments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dtr_entries`
--
ALTER TABLE `dtr_entries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `employee_files`
--
ALTER TABLE `employee_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_violations`
--
ALTER TABLE `employee_violations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `file_audit_logs`
--
ALTER TABLE `file_audit_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hr_tasks`
--
ALTER TABLE `hr_tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_balances`
--
ALTER TABLE `leave_balances`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notification_status`
--
ALTER TABLE `notification_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `violation_types`
--
ALTER TABLE `violation_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

-- --------------------------------------------------------

--
-- Structure for view `dtr_summary`
--
DROP TABLE IF EXISTS `dtr_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dtr_summary`  AS SELECT `e`.`id` AS `employee_id`, concat(`e`.`surname`,', ',`e`.`first_name`) AS `employee_name`, `e`.`post` AS `post`, `d`.`entry_date` AS `entry_date`, `d`.`time_in` AS `time_in`, `d`.`time_out` AS `time_out`, `d`.`entry_type` AS `entry_type`, (case when ((`d`.`time_in` is not null) and (`d`.`time_out` is not null)) then timestampdiff(HOUR,concat(`d`.`entry_date`,' ',`d`.`time_in`),concat(`d`.`entry_date`,' ',`d`.`time_out`)) else NULL end) AS `hours_worked` FROM (`employees` `e` left join `dtr_entries` `d` on((`e`.`id` = `d`.`employee_id`))) WHERE (`d`.`entry_date` >= (curdate() - interval 30 day)) ;

-- --------------------------------------------------------

--
-- Structure for view `employee_details`
--
DROP TABLE IF EXISTS `employee_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `employee_details`  AS SELECT `e`.`id` AS `id`, `e`.`employee_no` AS `employee_no`, `e`.`employee_type` AS `employee_type`, concat(`e`.`surname`,', ',`e`.`first_name`,' ',coalesce(`e`.`middle_name`,'')) AS `full_name`, `e`.`post` AS `post`, `e`.`license_no` AS `license_no`, `e`.`license_exp_date` AS `license_exp_date`, `e`.`date_hired` AS `date_hired`, `e`.`status` AS `status`, `e`.`created_at` AS `created_at`, `e`.`updated_at` AS `updated_at`, (case when (`e`.`license_exp_date` < curdate()) then 'Expired' when (`e`.`license_exp_date` <= (curdate() + interval 30 day)) then 'Expiring Soon' else 'Valid' end) AS `license_status` FROM `employees` AS `e` ;

-- --------------------------------------------------------

--
-- Structure for view `leave_balance_summary`
--
DROP TABLE IF EXISTS `leave_balance_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `leave_balance_summary`  AS SELECT `e`.`id` AS `employee_id`, concat(`e`.`surname`,', ',`e`.`first_name`) AS `employee_name`, `e`.`employee_type` AS `employee_type`, `e`.`post` AS `post`, `lb`.`leave_type` AS `leave_type`, `lb`.`year` AS `year`, `lb`.`total_entitlement` AS `total_entitlement`, `lb`.`used_days` AS `used_days`, (ifnull(`lb`.`total_entitlement`,0) - ifnull(`lb`.`used_days`,0)) AS `remaining_days` FROM (`employees` `e` left join `leave_balances` `lb` on(((`e`.`id` = `lb`.`employee_id`) and (`lb`.`year` = year(curdate()))))) ;

-- --------------------------------------------------------

--
-- Structure for view `post_statistics`
--
DROP TABLE IF EXISTS `post_statistics`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `post_statistics`  AS SELECT 1 AS `id`, 1 AS `post_title`, 1 AS `location`, 1 AS `employee_type`, 1 AS `required_count`, 1 AS `filled_count`, 1 AS `available_positions`, 1 AS `priority`, 1 AS `status`, 1 AS `created_at` ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dtr_adjustments`
--
ALTER TABLE `dtr_adjustments`
  ADD CONSTRAINT `fk_dtr_adj_dtr_entry` FOREIGN KEY (`dtr_entry_id`) REFERENCES `dtr_entries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_dtr_adj_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_dtr_adj_user` FOREIGN KEY (`adjusted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dtr_entries`
--
ALTER TABLE `dtr_entries`
  ADD CONSTRAINT `dtr_entries_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_files`
--
ALTER TABLE `employee_files`
  ADD CONSTRAINT `fk_employee_files_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_employee_files_uploader` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `employee_violations`
--
ALTER TABLE `employee_violations`
  ADD CONSTRAINT `fk_violations_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_violations_type` FOREIGN KEY (`violation_type_id`) REFERENCES `violation_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_audit_logs`
--
ALTER TABLE `file_audit_logs`
  ADD CONSTRAINT `fk_file_audit_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_file_audit_file` FOREIGN KEY (`file_id`) REFERENCES `employee_files` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_file_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

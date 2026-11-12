-- MEDLINK Database Schema and Seed Data for MySQL / MariaDB (Laragon)
-- Import this file into your database (e.g., 'techyknowloadeia1' or 'medlink') using HeidiSQL, phpMyAdmin, or MySQL CLI.

CREATE DATABASE IF NOT EXISTS `techyknowloadeia1` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `techyknowloadeia1`;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'resident',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_phone_number_unique` (`phone_number`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table `users`
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `first_name`, `last_name`, `phone_number`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Resident', 'User', '09111111111', NULL, 'resident', NOW(), '$2y$12$yVeokU7cfLE4Ap8KPDYSiulbZPpBmnsKBXp58PSgyyww2lTDynFEG', NULL, NOW(), NOW()),
(2, 'BHW', 'User', '09222222222', NULL, 'bhw', NOW(), '$2y$12$yVeokU7cfLE4Ap8KPDYSiulbZPpBmnsKBXp58PSgyyww2lTDynFEG', NULL, NOW(), NOW()),
(3, 'Nurse', 'User', '09333333333', NULL, 'nurse', NOW(), '$2y$12$yVeokU7cfLE4Ap8KPDYSiulbZPpBmnsKBXp58PSgyyww2lTDynFEG', NULL, NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for table `password_reset_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `sessions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `cache`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `medicines`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `medicines`;
CREATE TABLE `medicines` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `unit` varchar(255) NOT NULL,
  `reorder_level` int(11) NOT NULL DEFAULT 30,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table `medicines`
-- --------------------------------------------------------
INSERT INTO `medicines` (`id`, `name`, `category`, `stock`, `unit`, `reorder_level`, `created_at`, `updated_at`) VALUES
(1, 'Paracetamol 500mg', 'Pain Reliever', 150, 'tablets', 50, NOW(), NOW()),
(2, 'Amoxicillin 500mg', 'Antibiotic', 80, 'capsules', 40, NOW(), NOW()),
(3, 'Vitamin C 500mg', 'Supplement', 25, 'tablets', 30, NOW(), NOW()),
(4, 'Ibuprofen 400mg', 'Pain Reliever', 95, 'tablets', 50, NOW(), NOW()),
(5, 'Cough Syrup', 'Cough Medicine', 15, 'bottles', 20, NOW(), NOW()),
(6, 'Antibiotic Ointment', 'Topical', 5, 'tubes', 15, NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for table `medicine_requests`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `medicine_requests`;
CREATE TABLE `medicine_requests` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `item_service` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medicine_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `medicine_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table `medicine_requests`
-- --------------------------------------------------------
INSERT INTO `medicine_requests` (`id`, `user_id`, `type`, `item_service`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Medicine', 'Paracetamol', 'Pending', NOW(), NOW()),
(2, 2, 'Home Service', 'Blood Pressure Check', 'Pending', NOW(), NOW()),
(3, 1, 'Medicine', 'Amoxicillin', 'Pending', NOW(), NOW());

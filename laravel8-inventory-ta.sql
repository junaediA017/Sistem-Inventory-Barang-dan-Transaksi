-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table laravel_inventory_ta.applications
CREATE TABLE IF NOT EXISTS `applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `copyright` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.applications: ~0 rows (approximately)
INSERT INTO `applications` (`id`, `name`, `copyright`) VALUES
	(1, 'PT. Auto Sunrise Mandiri', 'Apliakasi Inventaris 2023');

-- Dumping structure for table laravel_inventory_ta.brands
CREATE TABLE IF NOT EXISTS `brands` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.brands: ~0 rows (approximately)

-- Dumping structure for table laravel_inventory_ta.cabang
CREATE TABLE IF NOT EXISTS `cabang` (
  `id_cabang` int unsigned NOT NULL AUTO_INCREMENT,
  `keterangan_cabang` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id_cabang`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table laravel_inventory_ta.cabang: ~5 rows (approximately)
INSERT INTO `cabang` (`id_cabang`, `keterangan_cabang`) VALUES
	(1, 'All'),
	(2, 'Magelang'),
	(3, 'Purwokerto'),
	(4, 'Yogyakarta'),
	(5, 'Cilacap');

-- Dumping structure for table laravel_inventory_ta.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.categories: ~0 rows (approximately)

-- Dumping structure for table laravel_inventory_ta.expenditure_transactions
CREATE TABLE IF NOT EXISTS `expenditure_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `picker` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` bigint NOT NULL,
  `id_cabang` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expenditure_transactions_reference_number_unique` (`reference_number`),
  KEY `id_cabang` (`id_cabang`),
  CONSTRAINT `FK_expenditure_transactions_cabang` FOREIGN KEY (`id_cabang`) REFERENCES `cabang` (`id_cabang`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.expenditure_transactions: ~0 rows (approximately)
INSERT INTO `expenditure_transactions` (`id`, `picker`, `reference_number`, `remarks`, `keterangan`, `created_at`, `id_cabang`) VALUES
	(11, 'yyy', '33', '1', 'OK', 1689934180, 2);

-- Dumping structure for table laravel_inventory_ta.expenditure_transaction_items
CREATE TABLE IF NOT EXISTS `expenditure_transaction_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `expenditure_transaction_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `expenditure_transaction_items_expenditure_transaction_id_foreign` (`expenditure_transaction_id`),
  KEY `expenditure_transaction_items_item_id_foreign` (`item_id`),
  CONSTRAINT `expenditure_transaction_items_expenditure_transaction_id_foreign` FOREIGN KEY (`expenditure_transaction_id`) REFERENCES `expenditure_transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `expenditure_transaction_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.expenditure_transaction_items: ~1 rows (approximately)
INSERT INTO `expenditure_transaction_items` (`id`, `expenditure_transaction_id`, `item_id`, `amount`) VALUES
	(22, 11, 8, 20.00);

-- Dumping structure for table laravel_inventory_ta.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table laravel_inventory_ta.income_transactions
CREATE TABLE IF NOT EXISTS `income_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `supplier` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` bigint NOT NULL,
  `id_cabang` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `income_transactions_reference_number_unique` (`reference_number`),
  KEY `FK_income_transactions_cabang` (`id_cabang`),
  CONSTRAINT `FK_income_transactions_cabang` FOREIGN KEY (`id_cabang`) REFERENCES `cabang` (`id_cabang`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.income_transactions: ~0 rows (approximately)
INSERT INTO `income_transactions` (`id`, `supplier`, `reference_number`, `remarks`, `keterangan`, `created_at`, `id_cabang`) VALUES
	(12, 'udin', '132', '1', 'OK', 1689933891, 2),
	(13, 'coba', '5006', '1', 'OK', 1689974959, 3);

-- Dumping structure for table laravel_inventory_ta.income_transaction_items
CREATE TABLE IF NOT EXISTS `income_transaction_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `income_transaction_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `income_transaction_items_income_transaction_id_foreign` (`income_transaction_id`),
  KEY `income_transaction_items_item_id_foreign` (`item_id`),
  CONSTRAINT `income_transaction_items_income_transaction_id_foreign` FOREIGN KEY (`income_transaction_id`) REFERENCES `income_transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `income_transaction_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.income_transaction_items: ~0 rows (approximately)
INSERT INTO `income_transaction_items` (`id`, `income_transaction_id`, `item_id`, `amount`) VALUES
	(24, 12, 8, 100.00),
	(25, 13, 8, 100.00);

-- Dumping structure for table laravel_inventory_ta.items
CREATE TABLE IF NOT EXISTS `items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `part_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `satuan_brg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_part_number_unique` (`part_number`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.items: ~5 rows (approximately)
INSERT INTO `items` (`id`, `part_number`, `description`, `price`, `image`, `satuan_brg`) VALUES
	(3, 'Q070', 'Transparency Enhancing Binder', 576.00, NULL, 'gram'),
	(4, 'Q065', 'connector', 410.00, NULL, 'kilogram'),
	(5, 'Q110', 'white', 460.00, 'uploads/images/CIj80M989ls5nh6R61BWX26eN791f7I6s7xVVg27.jpg', 'gram'),
	(6, 'Q120', 'High Strength White', 568.00, NULL, 'gram'),
	(7, '2134', 'sadqeqf', 898.00, NULL, 'gram'),
	(8, 'c0191', 'Cat Kuning', 900.00, NULL, 'gram');

-- Dumping structure for table laravel_inventory_ta.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.migrations: ~13 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2023_02_19_160145_create_categories_table', 1),
	(6, '2023_02_19_160817_create_brands_table', 1),
	(7, '2023_02_19_161034_create_unit_of_measurements_table', 1),
	(8, '2023_02_19_161439_create_items_table', 1),
	(9, '2023_02_19_163153_create_income_transactions_table', 1),
	(10, '2023_02_19_163943_create_income_transaction_items_table', 1),
	(11, '2023_02_19_165530_create_expenditure_transactions_table', 1),
	(12, '2023_02_19_165908_create_expenditure_transaction_items_table', 1),
	(13, '2023_02_20_014831_create_applications_table', 1);

-- Dumping structure for table laravel_inventory_ta.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.password_resets: ~0 rows (approximately)

-- Dumping structure for table laravel_inventory_ta.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table laravel_inventory_ta.unit_of_measurements
CREATE TABLE IF NOT EXISTS `unit_of_measurements` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.unit_of_measurements: ~0 rows (approximately)

-- Dumping structure for table laravel_inventory_ta.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Admin','Pemilik') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` bigint NOT NULL,
  `updated_at` bigint DEFAULT NULL,
  `id_cabang` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `cabang` (`id_cabang`) USING BTREE,
  CONSTRAINT `FK_users_cabang` FOREIGN KEY (`id_cabang`) REFERENCES `cabang` (`id_cabang`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel_inventory_ta.users: ~4 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `id_cabang`) VALUES
	(3, 'bima', 'bima@gmail.com', 'pemilikbim', NULL, '$2y$10$djqmOYtMRUhkRVnTojh7iu3JK.74VthhTEQySwzDJFRbtRM0rxQOe', 'Pemilik', NULL, 1686787945, NULL, 1),
	(4, 'magelang', 'magelang@gmail.com', 'magelang', NULL, '$2y$10$Fi36A0U4eLfbUL8XlRm0ROHKS/RFdzlRLlDkp9XhfHaEvoB2N4WPm', 'Admin', NULL, 1688591666, NULL, 2),
	(5, 'purwokerto', 'purwokerto@gmail.com', 'purwokerto', NULL, '$2y$10$meSOrKTIHxf080ezXg5zn.rb.AhpkOnOX/d/.U1KTWc0mL7ZvA3eS', 'Admin', NULL, 1688591699, NULL, 3),
	(6, 'Yogyakarta', 'Yogyakarta@gmail.com', 'Yogyakarta', NULL, '$2y$10$wl/VMs513LHgwlmxUN4UjOQ95eEw06JLneWv4Wo8Dz9.qQEMpUCJ2', 'Admin', NULL, 1688591728, NULL, 4),
	(7, 'Cilacap', 'Cilacap@gmail.com', 'Cilacap', NULL, '$2y$10$jgdNHeCFcZPtFxdm5Sx9ouqyHFF6jiX9hX1GvkrjTH3xotddinhZa', 'Admin', NULL, 1688591753, NULL, 5);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 17, 2026 at 07:50 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cooperative_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `dividend_histories_tbls`
--

DROP TABLE IF EXISTS `dividend_histories_tbls`;
CREATE TABLE IF NOT EXISTS `dividend_histories_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `share_capital_account_id` bigint UNSIGNED NOT NULL,
  `period_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` tinyint NOT NULL,
  `year` int NOT NULL,
  `dividend_rate` decimal(5,2) NOT NULL,
  `share_capital` decimal(10,2) NOT NULL,
  `dividend_amount` decimal(10,2) NOT NULL,
  `date_paid` date NOT NULL,
  `status` enum('Pending','Paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dividend_histories_tbls_share_capital_account_id_foreign` (`share_capital_account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dividend_rates_tbls`
--

DROP TABLE IF EXISTS `dividend_rates_tbls`;
CREATE TABLE IF NOT EXISTS `dividend_rates_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `rate` decimal(5,2) NOT NULL,
  `effective_year` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `educational_tbls`
--

DROP TABLE IF EXISTS `educational_tbls`;
CREATE TABLE IF NOT EXISTS `educational_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `educational_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specify` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `educational_tbls_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lending_program_tbls`
--

DROP TABLE IF EXISTS `lending_program_tbls`;
CREATE TABLE IF NOT EXISTS `lending_program_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lending_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lending_amount` decimal(10,2) DEFAULT NULL,
  `lending_type_term` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monthly_income` decimal(10,2) DEFAULT NULL,
  `monthly_payment` decimal(10,2) DEFAULT NULL,
  `total_payment` decimal(10,2) DEFAULT NULL,
  `total_interest` decimal(10,2) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `late_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `penalty_applied_at` timestamp NULL DEFAULT NULL,
  `purpose_loan` text COLLATE utf8mb4_unicode_ci,
  `valid_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_of_income` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_of_emergency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_permit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `financial_statement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_quotation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drivers_license` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cog` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Declined','Archived') COLLATE utf8mb4_unicode_ci NOT NULL,
  `decline_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lending_program_tbls_reference_no_unique` (`reference_no`),
  KEY `lending_program_tbls_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lending_program_tbls`
--

INSERT INTO `lending_program_tbls` (`id`, `user_id`, `reference_no`, `lending_type`, `lending_amount`, `lending_type_term`, `monthly_income`, `monthly_payment`, `total_payment`, `total_interest`, `due_date`, `late_fee`, `penalty_applied_at`, `purpose_loan`, `valid_id`, `proof_of_income`, `proof_of_emergency`, `business_permit`, `financial_statement`, `vehicle_quotation`, `drivers_license`, `school_id`, `cor`, `cog`, `status`, `decline_reason`, `created_at`, `updated_at`) VALUES
(1, 1, 'LN-2026040508592887', 'Personal Lending', 1000.00, '3 months', 10000.00, NULL, NULL, NULL, NULL, 0.00, NULL, 'For my extra credit assignment', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Declined', 'Not valid. Try again.', '2026-04-04 16:59:28', '2026-04-04 17:26:56'),
(2, 1, 'LN-2026040509283965', 'Personal Lending', 1000.00, '3 months', 10000.00, 343.38, 1030.15, 30.15, '2026-07-05', 0.00, NULL, 'Need for my graduation toga.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Approved', NULL, '2026-04-04 17:28:39', '2026-04-13 06:11:44'),
(3, 1, 'LN-2026040514535441', 'Personal Lending', 1000.00, '3 months', 10000.00, 343.38, 1030.15, 30.15, '2026-07-05', 0.00, NULL, 'Need for my out of town trip', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Approved', 'Not valid', '2026-04-04 22:53:54', '2026-04-13 06:11:45'),
(4, 1, 'LN-2026041114595737', 'Personal Loan', 1000.00, '6 months', 10000.00, 176.67, 1060.00, 60.00, '2026-10-11', 0.00, NULL, 'Admin test #32', 'documents/valid_id/Cw6n8VDJUgiKI6ReLJzsKtUCPvNNUCfutqmzqEhx.png', 'documents/proof_of_income/yI95jvEHZOXaZ1yGJbsy6VP8EA3jouCrruwspwGT.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Approved', NULL, '2026-04-11 06:59:57', '2026-04-13 06:11:45'),
(5, 1, 'LN-2026041213392371', 'Personal Lending', 1000.00, '6 months', 10000.00, 172.55, 1035.29, 35.29, '2026-10-12', 0.00, NULL, 'member test', 'documents/valid_id/RroVpnoz0XAtCFDK5u2FLWhYRUJEYZz1HJAHYO0c.png', 'documents/proof_of_income/dUYHMpLA19AwzwJNdkFUlzjav3vRLpJTwjfzvUIX.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Approved', NULL, '2026-04-12 05:39:24', '2026-04-13 06:11:45'),
(6, 1, 'LN-2026041213411853', 'Personal Loan', 1000.00, '6 months', 10000.00, 176.67, 1060.00, 60.00, '2026-10-12', 0.00, NULL, 'admin test #2', 'documents/valid_id/0M6u49YiBgyBlwNhyLuhF0J1lJm7j1ucu4kf7QHc.png', 'documents/proof_of_income/NFcEl8cOawzDpnUI1i7AS4iwmtt0mLDnFIBm6T5Z.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Approved', NULL, '2026-04-12 05:41:18', '2026-04-13 06:11:45'),
(7, 1, 'LN-2026041402305440', 'Education Lending', 20000.00, '6 months', 30000.00, 3570.52, 21423.10, 1423.10, NULL, 0.00, NULL, 'papagawa ng bubong ng tindahan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'documents/school_id/mNYkvR88xH5YSuAVSPDH72X5HsI5Uq7UFPkskAiL.jpg', 'documents/cor/vuewgHAYfv1O5cYhSrEEvsJMRKkw65zGSVmredtm.png', 'documents/cog/YTmTSjthKFMwjDorOYciWHKoN2PROmGHg12lJkku.png', 'Pending', NULL, '2026-04-13 18:30:55', '2026-04-13 18:30:55'),
(8, 10, 'LN-2026041507025620', 'Personal Lending', 1000.00, '6 months', 20000.00, 172.55, 1035.29, 35.29, '2026-10-15', 0.00, NULL, 'jhjh', 'documents/valid_id/hmOfHz3qq5n6QlaRHP9DIBJLnu3LqVvo2x6aTiBb.png', 'documents/proof_of_income/dhJo2raMYHdr5qWpIaNPp8RmydYHHXGweP1aFBnN.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Approved', NULL, '2026-04-14 23:02:56', '2026-04-14 23:04:28');

-- --------------------------------------------------------

--
-- Table structure for table `lending_repayments_tbls`
--

DROP TABLE IF EXISTS `lending_repayments_tbls`;
CREATE TABLE IF NOT EXISTS `lending_repayments_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `lending_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `payment_number` int NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `recorded_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lending_repayments_tbls_lending_id_foreign` (`lending_id`),
  KEY `lending_repayments_tbls_user_id_foreign` (`user_id`),
  KEY `lending_repayments_tbls_recorded_by_foreign` (`recorded_by`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lending_repayments_tbls`
--

INSERT INTO `lending_repayments_tbls` (`id`, `lending_id`, `user_id`, `payment_number`, `amount_paid`, `payment_date`, `payment_method`, `reference_no`, `notes`, `recorded_by`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 343.38, '2026-04-15', 'Cash', 'RCP-20260415065756', 'gg', NULL, '2026-04-14 22:57:56', '2026-04-14 22:57:56'),
(2, 2, 1, 2, 343.38, '2026-04-15', 'Cash', 'RCP-20260415065823', NULL, NULL, '2026-04-14 22:58:23', '2026-04-14 22:58:23'),
(3, 2, 1, 3, 343.38, '2026-04-15', 'Cash', 'RCP-20260415065830', NULL, NULL, '2026-04-14 22:58:30', '2026-04-14 22:58:30');

-- --------------------------------------------------------

--
-- Table structure for table `lending_status_tbls`
--

DROP TABLE IF EXISTS `lending_status_tbls`;
CREATE TABLE IF NOT EXISTS `lending_status_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `lending_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `remaining_balance` decimal(10,2) NOT NULL,
  `total_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payments_made` int NOT NULL DEFAULT '0',
  `total_payments` int NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `next_due_date` date DEFAULT NULL,
  `status` enum('Active','Completed','Overdue','Defaulted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lending_status_tbls_lending_id_foreign` (`lending_id`),
  KEY `lending_status_tbls_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lending_status_tbls`
--

INSERT INTO `lending_status_tbls` (`id`, `lending_id`, `user_id`, `remaining_balance`, `total_paid`, `payments_made`, `total_payments`, `interest_rate`, `next_due_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 1060.00, 0.00, 0, 6, 6.00, '2026-05-11', 'Active', '2026-04-11 06:59:57', '2026-04-11 06:59:57'),
(2, 5, 1, 1035.29, 0.00, 0, 6, 3.53, '2026-05-12', 'Active', '2026-04-12 05:39:53', '2026-04-12 05:39:53'),
(3, 6, 1, 1060.00, 0.00, 0, 6, 6.00, '2026-05-12', 'Active', '2026-04-12 05:41:18', '2026-04-12 05:41:18'),
(4, 3, 1, 1030.15, 0.00, 0, 3, 3.02, '2026-05-13', 'Active', '2026-04-12 17:40:49', '2026-04-12 17:40:49'),
(5, 2, 1, 0.01, 1030.14, 3, 3, 3.02, '2026-05-13', 'Completed', '2026-04-13 06:38:14', '2026-04-14 22:58:30'),
(6, 8, 10, 1035.29, 0.00, 0, 6, 3.53, '2026-05-15', 'Active', '2026-04-14 23:04:28', '2026-04-14 23:04:28');

-- --------------------------------------------------------

--
-- Table structure for table `loan_settings_tbls`
--

DROP TABLE IF EXISTS `loan_settings_tbls`;
CREATE TABLE IF NOT EXISTS `loan_settings_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `loan_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL DEFAULT '2.00',
  `max_amount` decimal(12,2) DEFAULT NULL,
  `late_fee_percentage` decimal(5,2) NOT NULL DEFAULT '2.00',
  `grace_period_months` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loan_settings_tbls_loan_type_unique` (`loan_type`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_settings_tbls`
--

INSERT INTO `loan_settings_tbls` (`id`, `loan_type`, `interest_rate`, `max_amount`, `late_fee_percentage`, `grace_period_months`, `created_at`, `updated_at`) VALUES
(1, 'Personal Loan', 1.00, 25000.00, 2.00, 1, '2026-04-11 05:58:04', '2026-04-11 06:58:47'),
(2, 'Emergency Loan', 2.00, 25000.00, 2.00, 1, '2026-04-11 05:58:04', '2026-04-11 06:58:47'),
(3, 'Business Loan', 2.00, 25000.00, 2.00, 1, '2026-04-11 05:58:04', '2026-04-11 06:58:47'),
(4, 'Education Loan', 2.00, 25000.00, 2.00, 1, '2026-04-11 05:58:04', '2026-04-11 06:58:47');

-- --------------------------------------------------------

--
-- Table structure for table `membergovern_ids_tbls`
--

DROP TABLE IF EXISTS `membergovern_ids_tbls`;
CREATE TABLE IF NOT EXISTS `membergovern_ids_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `sss_id` longtext COLLATE utf8mb4_unicode_ci,
  `philhealth_id` longtext COLLATE utf8mb4_unicode_ci,
  `pagibig_id` longtext COLLATE utf8mb4_unicode_ci,
  `tin_id` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `membergovern_ids_tbls_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `membergovern_ids_tbls`
--

INSERT INTO `membergovern_ids_tbls` (`id`, `user_id`, `sss_id`, `philhealth_id`, `pagibig_id`, `tin_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, '2026-03-31 10:29:35', '2026-03-31 10:29:35'),
(2, 9, NULL, NULL, NULL, NULL, '2026-04-13 17:51:26', '2026-04-13 17:51:26'),
(3, 10, NULL, NULL, NULL, NULL, '2026-04-14 22:49:32', '2026-04-14 22:49:32');

-- --------------------------------------------------------

--
-- Table structure for table `membervehi_tbls`
--

DROP TABLE IF EXISTS `membervehi_tbls`;
CREATE TABLE IF NOT EXISTS `membervehi_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `plate_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `membervehi_tbls_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `membervehi_tbls`
--

INSERT INTO `membervehi_tbls` (`id`, `user_id`, `plate_no`, `vehicle_type`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 9, 'ABS123', 'JEEP', 1, '2026-04-13 17:51:27', '2026-04-13 17:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_02_27_052843_create_users_tbls_table', 1),
(2, '2026_02_27_053230_create_otherinfo_tbls_table', 1),
(3, '2026_02_27_053448_create_membervehi_tbls_table', 1),
(4, '2026_02_27_053620_create_spouse_tbls_table', 1),
(5, '2026_02_27_053749_create_membergovern_ids_tbls_table', 1),
(6, '2026_03_11_033446_create_educational_tbls_table', 1),
(7, '2026_03_13_135526_create_lending_program_tbls_table', 1),
(8, '2026_03_19_120901_create_savings_account_tbls_table', 1),
(9, '2026_03_19_120939_create_savings_transaction_tbls_table', 1),
(10, '2026_03_26_045259_create_lending_status_tbls_table', 1),
(11, '2026_03_26_053503_create_lending_repayments_tbls_table', 1),
(12, '2026_03_29_083138_create_share_capital_account_tbls_table', 1),
(13, '2026_03_29_083220_create_share_capital_transaction_tbls_table', 1),
(14, '2026_04_05_000001_add_decline_reason_to_lending_program_tbls', 1),
(15, '2026_04_05_000002_add_archived_to_savings_transaction_tbls', 1),
(16, '2026_04_05_000003_add_archived_to_share_capital_transaction_tbls', 1),
(17, '2026_04_05_000004_create_system_settings_tbls_table', 1),
(18, '2026_04_09_214539_create_dividend_rates_tbls_table', 1),
(19, '2026_04_09_214625_create_dividend_histories_tbls_table', 1),
(20, '2026_04_11_000001_create_loan_settings_tbls_table', 1),
(21, '2026_04_11_000002_update_lending_program_status_enum', 1),
(22, '2026_04_12_050717_update_share_capital_status_to_lowercase', 2),
(23, '2026_04_13_000001_add_late_fee_fields', 3);

-- --------------------------------------------------------

--
-- Table structure for table `otherinfo_tbls`
--

DROP TABLE IF EXISTS `otherinfo_tbls`;
CREATE TABLE IF NOT EXISTS `otherinfo_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `membership_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `present_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sex` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `civil_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `citizenship` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skills` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_picture` longtext COLLATE utf8mb4_unicode_ci,
  `signature` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `approval_status` enum('Pending','Approved','Declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `membership_status` enum('Unofficial','Active','Not Active') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unofficial',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `otherinfo_tbls_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otherinfo_tbls`
--

INSERT INTO `otherinfo_tbls` (`id`, `user_id`, `membership_category`, `place_of_birth`, `date_of_birth`, `contact_no`, `present_address`, `permanent_address`, `sex`, `civil_status`, `citizenship`, `height`, `weight`, `blood_type`, `skills`, `profile_picture`, `signature`, `approval_status`, `membership_status`, `created_at`, `updated_at`) VALUES
(2, 1, 'Operator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 'Approved', 'Active', '2026-04-11 06:14:11', '2026-04-11 06:14:11'),
(3, 7, 'Regular', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 'Approved', 'Active', '2026-04-11 06:14:11', '2026-04-11 06:14:11'),
(5, 9, 'Dispatcher', 'Angeles City Pampanga', '2005-02-14', NULL, NULL, NULL, 'Male', 'Single', NULL, NULL, NULL, NULL, NULL, NULL, 'data:image/png;base64,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', 'Pending', 'Unofficial', '2026-04-13 17:51:27', '2026-04-13 17:51:27'),
(6, 10, 'Driver', 'Angeles City Pampanga', '2005-02-14', NULL, NULL, NULL, 'Female', 'Single', NULL, NULL, NULL, NULL, NULL, NULL, 'data:image/png;base64,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', 'Approved', 'Active', '2026-04-14 22:49:32', '2026-04-14 22:49:32'),
(7, 14, 'Driver', 'Cebu', '2004-12-07', NULL, NULL, NULL, 'Female', 'Widowed', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(8, 15, 'Allied Workers', 'Manila', '1975-03-18', NULL, NULL, NULL, 'Male', 'Married', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(9, 16, 'Driver', 'Cebu City', '1959-11-02', NULL, NULL, NULL, 'Female', 'Widowed', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(10, 17, 'Transport Entrepreneur', 'Pangasinan', '1991-02-19', NULL, NULL, NULL, 'Male', 'Widowed', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(11, 18, 'Allied Workers', 'Baguio', '1994-01-15', NULL, NULL, NULL, 'Female', 'Widowed', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(12, 19, 'Dispatcher', 'Cebu', '1972-01-04', NULL, NULL, NULL, 'Male', 'Single', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(13, 20, 'Investor Associate', 'Batangas', '1996-12-27', NULL, NULL, NULL, 'Female', 'Widowed', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(14, 21, 'Operator', 'Manila', '1987-10-21', NULL, NULL, NULL, 'Male', 'Single', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(15, 22, 'Driver', 'Laguna', '1964-04-17', NULL, NULL, NULL, 'Female', 'Widowed', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(16, 23, 'Operator', 'Laguna', '1991-04-27', NULL, NULL, NULL, 'Male', 'Married', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(17, 24, 'Operator', 'Pangasinan', '1969-04-23', NULL, NULL, NULL, 'Female', 'Separated', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(18, 25, 'Driver-Operator', 'Cebu', '1994-11-26', NULL, NULL, NULL, 'Male', 'Widowed', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(19, 26, 'Investor Associate', 'Iloilo', '1998-08-25', NULL, NULL, NULL, 'Female', 'Married', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(20, 27, 'Operator', 'Iloilo', '1987-11-01', NULL, NULL, NULL, 'Male', 'Separated', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(21, 28, 'Allied Workers', 'Baguio', '1994-07-02', NULL, NULL, NULL, 'Female', 'Single', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(22, 29, 'Driver-Operator', 'Laguna', '1968-03-03', NULL, NULL, NULL, 'Male', 'Single', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(23, 30, 'Investor Associate', 'Cebu', '1998-10-14', NULL, NULL, NULL, 'Female', 'Single', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(24, 31, 'Allied Workers', 'Davao', '2006-06-13', NULL, NULL, NULL, 'Male', 'Separated', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(25, 32, 'Driver-Operator', 'Laguna', '1979-11-18', NULL, NULL, NULL, 'Female', 'Widowed', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(26, 33, 'Driver', 'Pangasinan', '1998-07-13', NULL, NULL, NULL, 'Male', 'Separated', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(27, 34, 'Operator', 'Cebu', '1962-04-11', NULL, NULL, NULL, 'Female', 'Single', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(28, 35, 'Transport Entrepreneur', 'Pangasinan', '1988-10-15', NULL, NULL, NULL, 'Male', 'Single', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Approved', 'Active', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(29, 36, 'Dispatcher', 'Manila', '1970-11-19', NULL, NULL, NULL, 'Female', 'Separated', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Pending', 'Unofficial', '2026-04-16 23:47:42', '2026-04-16 23:47:42'),
(30, 37, 'Operator', 'Manila', '1982-02-26', NULL, NULL, NULL, 'Male', 'Married', 'Filipino', NULL, NULL, NULL, 'None', NULL, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'Pending', 'Unofficial', '2026-04-16 23:47:42', '2026-04-16 23:47:42');

-- --------------------------------------------------------

--
-- Table structure for table `savings_account_tbls`
--

DROP TABLE IF EXISTS `savings_account_tbls`;
CREATE TABLE IF NOT EXISTS `savings_account_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('active','frozen','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `opened_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `savings_account_tbls_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `savings_account_tbls`
--

INSERT INTO `savings_account_tbls` (`id`, `user_id`, `balance`, `status`, `opened_at`, `created_at`, `updated_at`) VALUES
(1, 1, 5500.00, 'active', '2026-04-01', '2026-04-11 06:14:16', '2026-04-12 17:47:50'),
(2, 9, 0.00, 'active', '2026-04-14', '2026-04-13 17:52:54', '2026-04-13 17:52:54'),
(3, 10, 500.00, 'active', '2026-04-15', '2026-04-14 22:56:54', '2026-04-14 23:05:23');

-- --------------------------------------------------------

--
-- Table structure for table `savings_transaction_tbls`
--

DROP TABLE IF EXISTS `savings_transaction_tbls`;
CREATE TABLE IF NOT EXISTS `savings_transaction_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `savings_account_id` bigint UNSIGNED NOT NULL,
  `type` enum('deposit','withdrawal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance_after` decimal(12,2) NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `savings_transaction_tbls_savings_account_id_foreign` (`savings_account_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `savings_transaction_tbls`
--

INSERT INTO `savings_transaction_tbls` (`id`, `savings_account_id`, `type`, `amount`, `payment_method`, `balance_after`, `note`, `reference_no`, `transaction_date`, `archived`, `created_at`, `updated_at`) VALUES
(1, 1, 'deposit', 1000.00, 'cash', 1000.00, NULL, 'SAV-DEP-20260401-1B1D77', '2026-04-01', 0, '2026-04-11 06:14:24', '2026-04-11 06:14:24'),
(2, 1, 'deposit', 500.00, 'cash', 1500.00, NULL, 'SAV-DEP-20260405-D95971', '2026-04-05', 0, '2026-04-11 06:14:24', '2026-04-11 06:14:24'),
(3, 1, 'deposit', 500.00, 'cash', 2000.00, NULL, 'SAV-DEP-20260405-119709', '2026-04-05', 0, '2026-04-11 06:14:24', '2026-04-11 06:14:24'),
(4, 1, 'deposit', 50.00, 'gcash', 2050.00, NULL, 'SAV-DEP-20260405-077003', '2026-04-05', 0, '2026-04-11 06:14:24', '2026-04-11 06:14:24'),
(5, 1, 'deposit', 1000.00, 'cash', 3050.00, NULL, 'SAV-DEP-20260405-2ECD15', '2026-04-05', 0, '2026-04-11 06:14:24', '2026-04-11 06:14:24'),
(6, 1, 'deposit', 50.00, 'cash', 3100.00, 'admin test', 'SAV-DEP-20260411-0F26BC', '2026-04-11', 0, '2026-04-11 06:47:16', '2026-04-11 06:47:16'),
(7, 1, 'deposit', 250.00, 'cash', 3350.00, 'admin test', 'SAV-DEP-20260411-DDAA16', '2026-04-11', 0, '2026-04-11 06:47:48', '2026-04-11 06:47:48'),
(8, 1, 'deposit', 150.00, 'cash', 3500.00, 'admin test', 'SAV-DEP-20260411-00B95F', '2026-04-11', 0, '2026-04-11 07:16:40', '2026-04-11 07:16:40'),
(9, 1, 'deposit', 500.00, 'cash', 4000.00, 'admin test', 'SAV-DEP-20260412-FCFFC6', '2026-04-12', 0, '2026-04-12 05:42:00', '2026-04-12 05:42:00'),
(10, 1, 'withdrawal', 500.00, 'cash', 3500.00, 'admin test', 'SAV-WDR-20260412-C0E435', '2026-04-12', 0, '2026-04-12 05:42:23', '2026-04-12 05:42:23'),
(11, 1, 'deposit', 2000.00, 'cash', 5500.00, 'Admin test #34', 'SAV-DEP-20260413-6ED87F', '2026-04-13', 0, '2026-04-12 17:47:50', '2026-04-12 17:47:50'),
(12, 3, 'deposit', 500.00, 'cash', 500.00, NULL, 'SAV-DEP-20260415-33E1F9', '2026-04-15', 0, '2026-04-14 23:05:23', '2026-04-14 23:05:23');

-- --------------------------------------------------------

--
-- Table structure for table `share_capital_account_tbls`
--

DROP TABLE IF EXISTS `share_capital_account_tbls`;
CREATE TABLE IF NOT EXISTS `share_capital_account_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_shares` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('Active','Inactive','Closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `share_capital_account_tbls_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `share_capital_account_tbls`
--

INSERT INTO `share_capital_account_tbls` (`id`, `user_id`, `total_shares`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 15.00, 15000.00, 'Active', '2026-04-11 08:22:47', '2026-04-12 13:57:34'),
(2, 10, 10.00, 10000.00, 'Active', '2026-04-14 23:00:15', '2026-04-14 23:00:15');

-- --------------------------------------------------------

--
-- Table structure for table `share_capital_transaction_tbls`
--

DROP TABLE IF EXISTS `share_capital_transaction_tbls`;
CREATE TABLE IF NOT EXISTS `share_capital_transaction_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `share_capital_account_id` bigint UNSIGNED NOT NULL,
  `type` enum('Subscription','Deposit','Withdrawal') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shares` decimal(10,2) DEFAULT NULL,
  `amount_per_share` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Pending','Completed','Approved','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `transaction_date` date NOT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `share_capital_transaction_tbls_share_capital_account_id_foreign` (`share_capital_account_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `share_capital_transaction_tbls`
--

INSERT INTO `share_capital_transaction_tbls` (`id`, `share_capital_account_id`, `type`, `shares`, `amount_per_share`, `total_amount`, `payment_method`, `reference_no`, `note`, `status`, `transaction_date`, `archived`, `created_at`, `updated_at`) VALUES
(1, 1, 'Deposit', 1.00, 1000.00, 1000.00, 'cash', 'SC-2026041116224790', 'admin test', 'Completed', '2026-04-11', 0, '2026-04-11 08:22:47', '2026-04-11 08:22:47'),
(2, 1, 'Deposit', 1.00, 1000.00, 1000.00, 'cash', 'SC-2026041116233851', 'test 2 admin', 'Completed', '2026-04-11', 0, '2026-04-11 08:23:38', '2026-04-11 08:23:38'),
(3, 1, 'Deposit', 10.00, 1000.00, 10000.00, 'cash', 'SC-69DB1D8292400-20260412', 'member test', 'Completed', '2026-04-12', 0, '2026-04-11 20:20:18', '2026-04-11 20:20:18'),
(4, 1, 'Deposit', 1.00, 1000.00, 1000.00, 'cash', 'SC-69DB262BDB461-20260412', NULL, 'Completed', '2026-04-12', 0, '2026-04-11 20:57:15', '2026-04-11 20:57:15'),
(5, 1, 'Withdrawal', 1.00, 1000.00, 1000.00, 'cash', 'SC-69DB34FA0EA5C-20260412', 'member test', 'Rejected', '2026-04-12', 0, '2026-04-11 22:00:26', '2026-04-11 22:01:06'),
(6, 1, 'Withdrawal', 1.00, 1000.00, 1000.00, 'cash', 'SC-69DB3549F20EE-20260412', 'member test', 'Approved', '2026-04-12', 0, '2026-04-11 22:01:45', '2026-04-11 22:08:58'),
(7, 1, 'Deposit', 1.00, 1000.00, 1000.00, 'cash', 'SC-69DB9619C3A79-20260412', NULL, 'Completed', '2026-04-12', 0, '2026-04-12 04:54:49', '2026-04-12 04:54:49'),
(8, 1, 'Deposit', 1.00, 1000.00, 1000.00, 'gcash', 'SC-2026041213425577', 'admin test', 'Completed', '2026-04-12', 0, '2026-04-12 05:42:55', '2026-04-12 05:42:55'),
(9, 1, 'Deposit', 1.00, 1000.00, 1000.00, 'cash', 'SC-69DBA1CF83561-20260412', 'member test', 'Completed', '2026-04-12', 0, '2026-04-12 05:44:47', '2026-04-12 05:44:47'),
(10, 2, 'Deposit', 10.00, 1000.00, 10000.00, 'cash', 'SC-69DF377F51A44-20260415', NULL, 'Completed', '2026-04-15', 0, '2026-04-14 23:00:15', '2026-04-14 23:00:15');

-- --------------------------------------------------------

--
-- Table structure for table `spouse_tbls`
--

DROP TABLE IF EXISTS `spouse_tbls`;
CREATE TABLE IF NOT EXISTS `spouse_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `spouse_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spouse_date_birth` date DEFAULT NULL,
  `spouse_place_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_son` int DEFAULT NULL,
  `number_daughter` int DEFAULT NULL,
  `other_spec` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `spouse_tbls_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spouse_tbls`
--

INSERT INTO `spouse_tbls` (`id`, `user_id`, `spouse_name`, `spouse_date_birth`, `spouse_place_birth`, `number_son`, `number_daughter`, `other_spec`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, 1, 1, 'None', '2026-03-31 10:29:35', '2026-03-31 10:29:35'),
(2, 9, 'none', NULL, NULL, 1, 2, 'none', '2026-04-13 17:51:26', '2026-04-13 17:51:26'),
(3, 10, 'juju', '2026-04-15', 'Angeles', 1, 2, 'none', '2026-04-14 22:49:32', '2026-04-14 22:49:32');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings_tbls`
--

DROP TABLE IF EXISTS `system_settings_tbls`;
CREATE TABLE IF NOT EXISTS `system_settings_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_settings_tbls_key_unique` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_tbls`
--

DROP TABLE IF EXISTS `users_tbls`;
CREATE TABLE IF NOT EXISTS `users_tbls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Member',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_tbls_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users_tbls`
--

INSERT INTO `users_tbls` (`id`, `first_name`, `middle_name`, `last_name`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Rogelio', 'A', 'Amoyan', 'rogelioamoyan768', 'rogelioamoyan768@gmail.com', '$2y$12$unYGXrTQRxmAph97U2yzOuZjwB/hwJ.Fsy08hOI8eNeBmbMD476ii', 'member', '2026-04-11 06:14:04', '2026-04-11 06:14:04'),
(2, 'Roger', 'A', 'Amoyan', 'rogelioamoyan123', 'rogelioamoyan123@gmail.com', '$2y$12$unYGXrTQRxmAph97U2yzOuZjwB/hwJ.Fsy08hOI8eNeBmbMD476ii', 'member', '2026-04-11 06:14:04', '2026-04-11 06:14:04'),
(3, 'Justine Lee', 'F', 'Fampulme', 'fampulmejl3', 'fampulmejl3@gmail.com', '$2y$12$unYGXrTQRxmAph97U2yzOuZjwB/hwJ.Fsy08hOI8eNeBmbMD476ii', 'member', '2026-04-11 06:14:04', '2026-04-11 06:14:04'),
(4, 'Jhun Gerald', 'D', 'Amihan', 'jhunamihan', 'jhunamihan@gmail.com', '$2y$12$unYGXrTQRxmAph97U2yzOuZjwB/hwJ.Fsy08hOI8eNeBmbMD476ii', 'member', '2026-04-11 06:14:04', '2026-04-11 06:14:04'),
(5, 'Zuroa', 'D', 'Amihan', 'zuroaanimo', 'zuroaanimo@gmail.com', '$2y$12$unYGXrTQRxmAph97U2yzOuZjwB/hwJ.Fsy08hOI8eNeBmbMD476ii', 'member', '2026-04-11 06:14:04', '2026-04-11 06:14:04'),
(6, 'Zur', 'E', 'Dzai', 'amihan.jhund.kld', 'amihan.jhund.kld@gmail.com', '$2y$12$unYGXrTQRxmAph97U2yzOuZjwB/hwJ.Fsy08hOI8eNeBmbMD476ii', 'member', '2026-04-11 06:14:04', '2026-04-11 06:14:04'),
(7, 'Ronald', '', 'Sales', 'ronald', 'ronald@coop.com', '$2y$12$unYGXrTQRxmAph97U2yzOuZjwB/hwJ.Fsy08hOI8eNeBmbMD476ii', 'admin', '2026-04-11 06:14:04', '2026-04-11 06:14:04'),
(9, 'JINGER HAN', 'D', 'AMIHAN', 'celestine', 'jingerhan.amihan@cvsu.edu.ph', '$2y$12$.qeCyinPess/MietP/hgkO4tg.E52CKXH39wd3v0DDElN8UOR2/aq', 'Member', '2026-04-13 17:51:26', '2026-04-13 17:51:26'),
(10, 'JINGER HAN', 'D', 'AMIHAN', 'jingered', 'jingerhan.ami@gmail.com', '$2y$12$86neOKzct0dwOvqbFgGaTOdjaLZuQwAFkGY10p5obeG.ceFuzfnQC', 'Member', '2026-04-14 22:49:32', '2026-04-14 22:56:19'),
(11, 'Juan', '', 'Dela Cruz', 'juandelacruz1', 'juandelacruz1@gmail.com', '$2y$10$tXsC07YuKENiwreV0fKwsOWC0poaBEkJXkeB.Gh0.OELKQmOIbeZ2', 'Member', '2026-04-17 07:44:59', '2026-04-17 07:44:59'),
(12, 'Juan', '', 'Dela Cruz', 'juandelacruz1test', 'juandelacruz1test@gmail.com', '$2y$10$hcrvMGWd9eLXflAr5eccN.l8qTLrVzaDti5XSs8Y.KYQC7OCr0BHK', 'Member', '2026-04-17 07:46:47', '2026-04-17 07:46:47'),
(13, 'Juan', '', 'Dela Cruz', 'juandelacruz12', 'juandelacruz12@gmail.com', '$2y$10$gNLkipEKTEqkBRswynY75eRFE.qpcfAHV3jau1f14atmKEWmzzEtm', 'Member', '2026-04-17 07:47:21', '2026-04-17 07:47:21'),
(14, 'Juan', '', 'Dela Cruz', 'juandelacruz13', 'juandelacruz13@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(15, 'Maria', '', 'Garcia', 'mariagarcia2', 'mariagarcia2@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(16, 'Pedro', '', 'Santos', 'pedrosantos3', 'pedrosantos3@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(17, 'Ana', '', 'Reyes', 'anareyes4', 'anareyes4@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(18, 'Carlos', '', 'Cruz', 'carloscruz5', 'carloscruz5@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(19, 'Sofia', '', 'Mendoza', 'sofiamendoza6', 'sofiamendoza6@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(20, 'Jose', '', 'Torres', 'josetorres7', 'josetorres7@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(21, 'Luz', '', 'Flores', 'luzflores8', 'luzflores8@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(22, 'Miguel', '', 'Rivera', 'miguelrivera9', 'miguelrivera9@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(23, 'Rosa', '', 'Morales', 'rosamorales10', 'rosamorales10@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(24, 'Antonio', '', 'Ng', 'antoniong11', 'antoniong11@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(25, 'Carmen', '', 'Tan', 'carmentan12', 'carmentan12@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(26, 'Francisco', '', 'Co', 'franciscoco13', 'franciscoco13@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(27, 'Elena', '', 'Uy', 'elenauy14', 'elenauy14@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(28, 'Gabriel', '', 'Sy', 'gabrielsy15', 'gabrielsy15@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(29, 'Carla', '', 'Chua', 'carlachua16', 'carlachua16@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(30, 'Renato', '', 'Lee', 'renatolee17', 'renatolee17@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(31, 'Nina', '', 'Kim', 'ninakim18', 'ninakim18@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(32, 'Alden', '', 'Parker', 'aldenparker19', 'aldenparker19@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(33, 'Katrina', '', 'Smith', 'katrinasmith20', 'katrinasmith20@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(34, 'Barney', '', 'Johnson', 'barneyjohnson21', 'barneyjohnson21@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(35, 'Sheena', '', 'Brown', 'sheenabrown22', 'sheenabrown22@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(36, 'Rex', '', 'Wilson', 'rexwilson23', 'rexwilson23@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42'),
(37, 'Megan', '', 'Davis', 'megandavis24', 'megandavis24@gmail.com', '$2y$10$DQkXIL.5auqS8.xerGrx0O1.t7cEBfLWl.Fsx83JvFNb2FhePtpEC', 'Member', '2026-04-17 07:47:42', '2026-04-17 07:47:42');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

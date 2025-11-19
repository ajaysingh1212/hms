-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 11:57 AM
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
-- Database: `rtechcrem`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` text DEFAULT NULL,
  `host` varchar(46) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `skype` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_statuses`
--

CREATE TABLE `client_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_companies`
--

CREATE TABLE `contact_companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `company_website` varchar(255) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_contacts`
--

CREATE TABLE `contact_contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_first_name` varchar(255) DEFAULT NULL,
  `contact_last_name` varchar(255) DEFAULT NULL,
  `contact_phone_1` varchar(255) DEFAULT NULL,
  `contact_phone_2` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_skype` varchar(255) DEFAULT NULL,
  `contact_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content_categories`
--

CREATE TABLE `content_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content_category_content_page`
--

CREATE TABLE `content_category_content_page` (
  `content_page_id` bigint(20) UNSIGNED NOT NULL,
  `content_category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content_pages`
--

CREATE TABLE `content_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `page_text` longtext DEFAULT NULL,
  `excerpt` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content_page_content_tag`
--

CREATE TABLE `content_page_content_tag` (
  `content_page_id` bigint(20) UNSIGNED NOT NULL,
  `content_tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content_tags`
--

CREATE TABLE `content_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_student`
--

CREATE TABLE `course_student` (
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_teacher`
--

CREATE TABLE `course_teacher` (
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_user`
--

CREATE TABLE `course_user` (
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_customers`
--

CREATE TABLE `crm_customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `skype` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_documents`
--

CREATE TABLE `crm_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_notes`
--

CREATE TABLE `crm_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `note` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_statuses`
--

CREATE TABLE `crm_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crm_statuses`
--

INSERT INTO `crm_statuses` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`, `created_by_id`) VALUES
(1, 'Lead', '2025-11-15 01:45:46', '2025-11-15 01:45:46', NULL, NULL),
(2, 'Customer', '2025-11-15 01:45:46', '2025-11-15 01:45:46', NULL, NULL),
(3, 'Partner', '2025-11-15 01:45:46', '2025-11-15 01:45:46', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `main_currency` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entry_date` date DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `expense_category_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entry_date` date DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `income_category_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income_categories`
--

CREATE TABLE `income_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income_sources`
--

CREATE TABLE `income_sources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `fee_percent` double(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `short_text` longtext DEFAULT NULL,
  `long_text` longtext DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 0,
  `is_free` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2025_11_15_000001_create_audit_logs_table', 1),
(4, '2025_11_15_000002_create_media_table', 1),
(5, '2025_11_15_000003_create_permissions_table', 1),
(6, '2025_11_15_000004_create_roles_table', 1),
(7, '2025_11_15_000005_create_users_table', 1),
(8, '2025_11_15_000006_create_user_alerts_table', 1),
(9, '2025_11_15_000007_create_expense_categories_table', 1),
(10, '2025_11_15_000008_create_income_categories_table', 1),
(11, '2025_11_15_000009_create_expenses_table', 1),
(12, '2025_11_15_000010_create_incomes_table', 1),
(13, '2025_11_15_000011_create_courses_table', 1),
(14, '2025_11_15_000012_create_lessons_table', 1),
(15, '2025_11_15_000013_create_tests_table', 1),
(16, '2025_11_15_000014_create_questions_table', 1),
(17, '2025_11_15_000015_create_question_options_table', 1),
(18, '2025_11_15_000016_create_test_results_table', 1),
(19, '2025_11_15_000017_create_test_answers_table', 1),
(20, '2025_11_15_000018_create_time_work_types_table', 1),
(21, '2025_11_15_000019_create_time_projects_table', 1),
(22, '2025_11_15_000020_create_time_entries_table', 1),
(23, '2025_11_15_000021_create_currencies_table', 1),
(24, '2025_11_15_000022_create_transaction_types_table', 1),
(25, '2025_11_15_000023_create_income_sources_table', 1),
(26, '2025_11_15_000024_create_client_statuses_table', 1),
(27, '2025_11_15_000025_create_project_statuses_table', 1),
(28, '2025_11_15_000026_create_clients_table', 1),
(29, '2025_11_15_000027_create_projects_table', 1),
(30, '2025_11_15_000028_create_notes_table', 1),
(31, '2025_11_15_000029_create_documents_table', 1),
(32, '2025_11_15_000030_create_transactions_table', 1),
(33, '2025_11_15_000031_create_contact_companies_table', 1),
(34, '2025_11_15_000032_create_contact_contacts_table', 1),
(35, '2025_11_15_000033_create_crm_statuses_table', 1),
(36, '2025_11_15_000034_create_crm_customers_table', 1),
(37, '2025_11_15_000035_create_crm_notes_table', 1),
(38, '2025_11_15_000036_create_crm_documents_table', 1),
(39, '2025_11_15_000037_create_task_statuses_table', 1),
(40, '2025_11_15_000038_create_task_tags_table', 1),
(41, '2025_11_15_000039_create_tasks_table', 1),
(42, '2025_11_15_000040_create_content_categories_table', 1),
(43, '2025_11_15_000041_create_content_tags_table', 1),
(44, '2025_11_15_000042_create_content_pages_table', 1),
(45, '2025_11_15_000043_create_students_table', 1),
(46, '2025_11_15_000044_create_teachers_table', 1),
(47, '2025_11_15_000045_create_other_employees_table', 1),
(48, '2025_11_15_000046_create_teacher_timeings_table', 1),
(49, '2025_11_15_000047_create_student_payrolls_table', 1),
(50, '2025_11_15_000048_create_permission_role_pivot_table', 1),
(51, '2025_11_15_000049_create_role_user_pivot_table', 1),
(52, '2025_11_15_000050_create_user_user_alert_pivot_table', 1),
(53, '2025_11_15_000051_create_course_user_pivot_table', 1),
(54, '2025_11_15_000052_create_task_task_tag_pivot_table', 1),
(55, '2025_11_15_000053_create_content_category_content_page_pivot_table', 1),
(56, '2025_11_15_000054_create_content_page_content_tag_pivot_table', 1),
(57, '2025_11_15_000055_create_course_student_pivot_table', 1),
(58, '2025_11_15_000056_create_course_teacher_pivot_table', 1),
(59, '2025_11_15_000057_add_relationship_fields_to_expenses_table', 1),
(60, '2025_11_15_000058_add_relationship_fields_to_incomes_table', 1),
(61, '2025_11_15_000059_add_relationship_fields_to_courses_table', 1),
(62, '2025_11_15_000060_add_relationship_fields_to_lessons_table', 1),
(63, '2025_11_15_000061_add_relationship_fields_to_tests_table', 1),
(64, '2025_11_15_000062_add_relationship_fields_to_questions_table', 1),
(65, '2025_11_15_000063_add_relationship_fields_to_question_options_table', 1),
(66, '2025_11_15_000064_add_relationship_fields_to_test_results_table', 1),
(67, '2025_11_15_000065_add_relationship_fields_to_test_answers_table', 1),
(68, '2025_11_15_000066_add_relationship_fields_to_time_work_types_table', 1),
(69, '2025_11_15_000067_add_relationship_fields_to_time_projects_table', 1),
(70, '2025_11_15_000068_add_relationship_fields_to_time_entries_table', 1),
(71, '2025_11_15_000069_add_relationship_fields_to_clients_table', 1),
(72, '2025_11_15_000070_add_relationship_fields_to_projects_table', 1),
(73, '2025_11_15_000071_add_relationship_fields_to_notes_table', 1),
(74, '2025_11_15_000072_add_relationship_fields_to_documents_table', 1),
(75, '2025_11_15_000073_add_relationship_fields_to_transactions_table', 1),
(76, '2025_11_15_000074_add_relationship_fields_to_contact_contacts_table', 1),
(77, '2025_11_15_000075_add_relationship_fields_to_crm_statuses_table', 1),
(78, '2025_11_15_000076_add_relationship_fields_to_crm_customers_table', 1),
(79, '2025_11_15_000077_add_relationship_fields_to_crm_notes_table', 1),
(80, '2025_11_15_000078_add_relationship_fields_to_crm_documents_table', 1),
(81, '2025_11_15_000079_add_relationship_fields_to_tasks_table', 1),
(82, '2025_11_15_000080_add_relationship_fields_to_students_table', 1),
(83, '2025_11_15_000081_add_relationship_fields_to_teachers_table', 1),
(84, '2025_11_15_000082_add_relationship_fields_to_other_employees_table', 1),
(85, '2025_11_15_000083_add_relationship_fields_to_teacher_timeings_table', 1),
(86, '2025_11_15_000084_add_relationship_fields_to_student_payrolls_table', 1),
(87, '2025_11_15_000085_create_qa_topics_table', 1),
(88, '2025_11_15_000086_create_qa_messages_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `note_text` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_employees`
--

CREATE TABLE `other_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `date_of_joining` date NOT NULL,
  `salary` varchar(255) DEFAULT NULL,
  `present_address` longtext DEFAULT NULL,
  `parmanent_address` varchar(255) DEFAULT NULL,
  `aadhar` varchar(255) DEFAULT NULL,
  `working_days` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `select_employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `title`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'user_management_access', NULL, NULL, NULL),
(2, 'permission_create', NULL, NULL, NULL),
(3, 'permission_edit', NULL, NULL, NULL),
(4, 'permission_show', NULL, NULL, NULL),
(5, 'permission_delete', NULL, NULL, NULL),
(6, 'permission_access', NULL, NULL, NULL),
(7, 'role_create', NULL, NULL, NULL),
(8, 'role_edit', NULL, NULL, NULL),
(9, 'role_show', NULL, NULL, NULL),
(10, 'role_delete', NULL, NULL, NULL),
(11, 'role_access', NULL, NULL, NULL),
(12, 'user_create', NULL, NULL, NULL),
(13, 'user_edit', NULL, NULL, NULL),
(14, 'user_show', NULL, NULL, NULL),
(15, 'user_delete', NULL, NULL, NULL),
(16, 'user_access', NULL, NULL, NULL),
(17, 'audit_log_show', NULL, NULL, NULL),
(18, 'audit_log_access', NULL, NULL, NULL),
(19, 'user_alert_create', NULL, NULL, NULL),
(20, 'user_alert_show', NULL, NULL, NULL),
(21, 'user_alert_delete', NULL, NULL, NULL),
(22, 'user_alert_access', NULL, NULL, NULL),
(23, 'expense_management_access', NULL, NULL, NULL),
(24, 'expense_category_create', NULL, NULL, NULL),
(25, 'expense_category_edit', NULL, NULL, NULL),
(26, 'expense_category_show', NULL, NULL, NULL),
(27, 'expense_category_delete', NULL, NULL, NULL),
(28, 'expense_category_access', NULL, NULL, NULL),
(29, 'income_category_create', NULL, NULL, NULL),
(30, 'income_category_edit', NULL, NULL, NULL),
(31, 'income_category_show', NULL, NULL, NULL),
(32, 'income_category_delete', NULL, NULL, NULL),
(33, 'income_category_access', NULL, NULL, NULL),
(34, 'expense_create', NULL, NULL, NULL),
(35, 'expense_edit', NULL, NULL, NULL),
(36, 'expense_show', NULL, NULL, NULL),
(37, 'expense_delete', NULL, NULL, NULL),
(38, 'expense_access', NULL, NULL, NULL),
(39, 'income_create', NULL, NULL, NULL),
(40, 'income_edit', NULL, NULL, NULL),
(41, 'income_show', NULL, NULL, NULL),
(42, 'income_delete', NULL, NULL, NULL),
(43, 'income_access', NULL, NULL, NULL),
(44, 'expense_report_create', NULL, NULL, NULL),
(45, 'expense_report_edit', NULL, NULL, NULL),
(46, 'expense_report_show', NULL, NULL, NULL),
(47, 'expense_report_delete', NULL, NULL, NULL),
(48, 'expense_report_access', NULL, NULL, NULL),
(49, 'course_create', NULL, NULL, NULL),
(50, 'course_edit', NULL, NULL, NULL),
(51, 'course_show', NULL, NULL, NULL),
(52, 'course_delete', NULL, NULL, NULL),
(53, 'course_access', NULL, NULL, NULL),
(54, 'lesson_create', NULL, NULL, NULL),
(55, 'lesson_edit', NULL, NULL, NULL),
(56, 'lesson_show', NULL, NULL, NULL),
(57, 'lesson_delete', NULL, NULL, NULL),
(58, 'lesson_access', NULL, NULL, NULL),
(59, 'test_create', NULL, NULL, NULL),
(60, 'test_edit', NULL, NULL, NULL),
(61, 'test_show', NULL, NULL, NULL),
(62, 'test_delete', NULL, NULL, NULL),
(63, 'test_access', NULL, NULL, NULL),
(64, 'question_create', NULL, NULL, NULL),
(65, 'question_edit', NULL, NULL, NULL),
(66, 'question_show', NULL, NULL, NULL),
(67, 'question_delete', NULL, NULL, NULL),
(68, 'question_access', NULL, NULL, NULL),
(69, 'question_option_create', NULL, NULL, NULL),
(70, 'question_option_edit', NULL, NULL, NULL),
(71, 'question_option_show', NULL, NULL, NULL),
(72, 'question_option_delete', NULL, NULL, NULL),
(73, 'question_option_access', NULL, NULL, NULL),
(74, 'test_result_create', NULL, NULL, NULL),
(75, 'test_result_edit', NULL, NULL, NULL),
(76, 'test_result_show', NULL, NULL, NULL),
(77, 'test_result_delete', NULL, NULL, NULL),
(78, 'test_result_access', NULL, NULL, NULL),
(79, 'test_answer_create', NULL, NULL, NULL),
(80, 'test_answer_edit', NULL, NULL, NULL),
(81, 'test_answer_show', NULL, NULL, NULL),
(82, 'test_answer_delete', NULL, NULL, NULL),
(83, 'test_answer_access', NULL, NULL, NULL),
(84, 'time_management_access', NULL, NULL, NULL),
(85, 'time_work_type_create', NULL, NULL, NULL),
(86, 'time_work_type_edit', NULL, NULL, NULL),
(87, 'time_work_type_show', NULL, NULL, NULL),
(88, 'time_work_type_delete', NULL, NULL, NULL),
(89, 'time_work_type_access', NULL, NULL, NULL),
(90, 'time_project_create', NULL, NULL, NULL),
(91, 'time_project_edit', NULL, NULL, NULL),
(92, 'time_project_show', NULL, NULL, NULL),
(93, 'time_project_delete', NULL, NULL, NULL),
(94, 'time_project_access', NULL, NULL, NULL),
(95, 'time_entry_create', NULL, NULL, NULL),
(96, 'time_entry_edit', NULL, NULL, NULL),
(97, 'time_entry_show', NULL, NULL, NULL),
(98, 'time_entry_delete', NULL, NULL, NULL),
(99, 'time_entry_access', NULL, NULL, NULL),
(100, 'time_report_create', NULL, NULL, NULL),
(101, 'time_report_edit', NULL, NULL, NULL),
(102, 'time_report_show', NULL, NULL, NULL),
(103, 'time_report_delete', NULL, NULL, NULL),
(104, 'time_report_access', NULL, NULL, NULL),
(105, 'client_management_setting_access', NULL, NULL, NULL),
(106, 'currency_create', NULL, NULL, NULL),
(107, 'currency_edit', NULL, NULL, NULL),
(108, 'currency_show', NULL, NULL, NULL),
(109, 'currency_delete', NULL, NULL, NULL),
(110, 'currency_access', NULL, NULL, NULL),
(111, 'transaction_type_create', NULL, NULL, NULL),
(112, 'transaction_type_edit', NULL, NULL, NULL),
(113, 'transaction_type_show', NULL, NULL, NULL),
(114, 'transaction_type_delete', NULL, NULL, NULL),
(115, 'transaction_type_access', NULL, NULL, NULL),
(116, 'income_source_create', NULL, NULL, NULL),
(117, 'income_source_edit', NULL, NULL, NULL),
(118, 'income_source_show', NULL, NULL, NULL),
(119, 'income_source_delete', NULL, NULL, NULL),
(120, 'income_source_access', NULL, NULL, NULL),
(121, 'client_status_create', NULL, NULL, NULL),
(122, 'client_status_edit', NULL, NULL, NULL),
(123, 'client_status_show', NULL, NULL, NULL),
(124, 'client_status_delete', NULL, NULL, NULL),
(125, 'client_status_access', NULL, NULL, NULL),
(126, 'project_status_create', NULL, NULL, NULL),
(127, 'project_status_edit', NULL, NULL, NULL),
(128, 'project_status_show', NULL, NULL, NULL),
(129, 'project_status_delete', NULL, NULL, NULL),
(130, 'project_status_access', NULL, NULL, NULL),
(131, 'client_management_access', NULL, NULL, NULL),
(132, 'client_create', NULL, NULL, NULL),
(133, 'client_edit', NULL, NULL, NULL),
(134, 'client_show', NULL, NULL, NULL),
(135, 'client_delete', NULL, NULL, NULL),
(136, 'client_access', NULL, NULL, NULL),
(137, 'project_create', NULL, NULL, NULL),
(138, 'project_edit', NULL, NULL, NULL),
(139, 'project_show', NULL, NULL, NULL),
(140, 'project_delete', NULL, NULL, NULL),
(141, 'project_access', NULL, NULL, NULL),
(142, 'note_create', NULL, NULL, NULL),
(143, 'note_edit', NULL, NULL, NULL),
(144, 'note_show', NULL, NULL, NULL),
(145, 'note_delete', NULL, NULL, NULL),
(146, 'note_access', NULL, NULL, NULL),
(147, 'document_create', NULL, NULL, NULL),
(148, 'document_edit', NULL, NULL, NULL),
(149, 'document_show', NULL, NULL, NULL),
(150, 'document_delete', NULL, NULL, NULL),
(151, 'document_access', NULL, NULL, NULL),
(152, 'transaction_create', NULL, NULL, NULL),
(153, 'transaction_edit', NULL, NULL, NULL),
(154, 'transaction_show', NULL, NULL, NULL),
(155, 'transaction_delete', NULL, NULL, NULL),
(156, 'transaction_access', NULL, NULL, NULL),
(157, 'client_report_create', NULL, NULL, NULL),
(158, 'client_report_edit', NULL, NULL, NULL),
(159, 'client_report_show', NULL, NULL, NULL),
(160, 'client_report_delete', NULL, NULL, NULL),
(161, 'client_report_access', NULL, NULL, NULL),
(162, 'contact_management_access', NULL, NULL, NULL),
(163, 'contact_company_create', NULL, NULL, NULL),
(164, 'contact_company_edit', NULL, NULL, NULL),
(165, 'contact_company_show', NULL, NULL, NULL),
(166, 'contact_company_delete', NULL, NULL, NULL),
(167, 'contact_company_access', NULL, NULL, NULL),
(168, 'contact_contact_create', NULL, NULL, NULL),
(169, 'contact_contact_edit', NULL, NULL, NULL),
(170, 'contact_contact_show', NULL, NULL, NULL),
(171, 'contact_contact_delete', NULL, NULL, NULL),
(172, 'contact_contact_access', NULL, NULL, NULL),
(173, 'basic_c_r_m_access', NULL, NULL, NULL),
(174, 'crm_status_create', NULL, NULL, NULL),
(175, 'crm_status_edit', NULL, NULL, NULL),
(176, 'crm_status_show', NULL, NULL, NULL),
(177, 'crm_status_delete', NULL, NULL, NULL),
(178, 'crm_status_access', NULL, NULL, NULL),
(179, 'crm_customer_create', NULL, NULL, NULL),
(180, 'crm_customer_edit', NULL, NULL, NULL),
(181, 'crm_customer_show', NULL, NULL, NULL),
(182, 'crm_customer_delete', NULL, NULL, NULL),
(183, 'crm_customer_access', NULL, NULL, NULL),
(184, 'crm_note_create', NULL, NULL, NULL),
(185, 'crm_note_edit', NULL, NULL, NULL),
(186, 'crm_note_show', NULL, NULL, NULL),
(187, 'crm_note_delete', NULL, NULL, NULL),
(188, 'crm_note_access', NULL, NULL, NULL),
(189, 'crm_document_create', NULL, NULL, NULL),
(190, 'crm_document_edit', NULL, NULL, NULL),
(191, 'crm_document_show', NULL, NULL, NULL),
(192, 'crm_document_delete', NULL, NULL, NULL),
(193, 'crm_document_access', NULL, NULL, NULL),
(194, 'task_management_access', NULL, NULL, NULL),
(195, 'task_status_create', NULL, NULL, NULL),
(196, 'task_status_edit', NULL, NULL, NULL),
(197, 'task_status_show', NULL, NULL, NULL),
(198, 'task_status_delete', NULL, NULL, NULL),
(199, 'task_status_access', NULL, NULL, NULL),
(200, 'task_tag_create', NULL, NULL, NULL),
(201, 'task_tag_edit', NULL, NULL, NULL),
(202, 'task_tag_show', NULL, NULL, NULL),
(203, 'task_tag_delete', NULL, NULL, NULL),
(204, 'task_tag_access', NULL, NULL, NULL),
(205, 'task_create', NULL, NULL, NULL),
(206, 'task_edit', NULL, NULL, NULL),
(207, 'task_show', NULL, NULL, NULL),
(208, 'task_delete', NULL, NULL, NULL),
(209, 'task_access', NULL, NULL, NULL),
(210, 'tasks_calendar_access', NULL, NULL, NULL),
(211, 'content_management_access', NULL, NULL, NULL),
(212, 'content_category_create', NULL, NULL, NULL),
(213, 'content_category_edit', NULL, NULL, NULL),
(214, 'content_category_show', NULL, NULL, NULL),
(215, 'content_category_delete', NULL, NULL, NULL),
(216, 'content_category_access', NULL, NULL, NULL),
(217, 'content_tag_create', NULL, NULL, NULL),
(218, 'content_tag_edit', NULL, NULL, NULL),
(219, 'content_tag_show', NULL, NULL, NULL),
(220, 'content_tag_delete', NULL, NULL, NULL),
(221, 'content_tag_access', NULL, NULL, NULL),
(222, 'content_page_create', NULL, NULL, NULL),
(223, 'content_page_edit', NULL, NULL, NULL),
(224, 'content_page_show', NULL, NULL, NULL),
(225, 'content_page_delete', NULL, NULL, NULL),
(226, 'content_page_access', NULL, NULL, NULL),
(227, 'test_seary_access', NULL, NULL, NULL),
(228, 'employee_access', NULL, NULL, NULL),
(229, 'student_create', NULL, NULL, NULL),
(230, 'student_edit', NULL, NULL, NULL),
(231, 'student_show', NULL, NULL, NULL),
(232, 'student_delete', NULL, NULL, NULL),
(233, 'student_access', NULL, NULL, NULL),
(234, 'teacher_create', NULL, NULL, NULL),
(235, 'teacher_edit', NULL, NULL, NULL),
(236, 'teacher_show', NULL, NULL, NULL),
(237, 'teacher_delete', NULL, NULL, NULL),
(238, 'teacher_access', NULL, NULL, NULL),
(239, 'other_employee_create', NULL, NULL, NULL),
(240, 'other_employee_edit', NULL, NULL, NULL),
(241, 'other_employee_show', NULL, NULL, NULL),
(242, 'other_employee_delete', NULL, NULL, NULL),
(243, 'other_employee_access', NULL, NULL, NULL),
(244, 'teacher_timeing_create', NULL, NULL, NULL),
(245, 'teacher_timeing_edit', NULL, NULL, NULL),
(246, 'teacher_timeing_show', NULL, NULL, NULL),
(247, 'teacher_timeing_delete', NULL, NULL, NULL),
(248, 'teacher_timeing_access', NULL, NULL, NULL),
(249, 'attendance_payroll_access', NULL, NULL, NULL),
(250, 'aattendance_create', NULL, NULL, NULL),
(251, 'aattendance_edit', NULL, NULL, NULL),
(252, 'aattendance_show', NULL, NULL, NULL),
(253, 'aattendance_delete', NULL, NULL, NULL),
(254, 'aattendance_access', NULL, NULL, NULL),
(255, 'payroll_create', NULL, NULL, NULL),
(256, 'payroll_edit', NULL, NULL, NULL),
(257, 'payroll_show', NULL, NULL, NULL),
(258, 'payroll_delete', NULL, NULL, NULL),
(259, 'payroll_access', NULL, NULL, NULL),
(260, 'student_payroll_create', NULL, NULL, NULL),
(261, 'student_payroll_edit', NULL, NULL, NULL),
(262, 'student_payroll_show', NULL, NULL, NULL),
(263, 'student_payroll_delete', NULL, NULL, NULL),
(264, 'student_payroll_access', NULL, NULL, NULL),
(265, 'profile_password_edit', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(1, 27),
(1, 28),
(1, 29),
(1, 30),
(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(1, 37),
(1, 38),
(1, 39),
(1, 40),
(1, 41),
(1, 42),
(1, 43),
(1, 44),
(1, 45),
(1, 46),
(1, 47),
(1, 48),
(1, 49),
(1, 50),
(1, 51),
(1, 52),
(1, 53),
(1, 54),
(1, 55),
(1, 56),
(1, 57),
(1, 58),
(1, 59),
(1, 60),
(1, 61),
(1, 62),
(1, 63),
(1, 64),
(1, 65),
(1, 66),
(1, 67),
(1, 68),
(1, 69),
(1, 70),
(1, 71),
(1, 72),
(1, 73),
(1, 74),
(1, 75),
(1, 76),
(1, 77),
(1, 78),
(1, 79),
(1, 80),
(1, 81),
(1, 82),
(1, 83),
(1, 84),
(1, 85),
(1, 86),
(1, 87),
(1, 88),
(1, 89),
(1, 90),
(1, 91),
(1, 92),
(1, 93),
(1, 94),
(1, 95),
(1, 96),
(1, 97),
(1, 98),
(1, 99),
(1, 100),
(1, 101),
(1, 102),
(1, 103),
(1, 104),
(1, 105),
(1, 106),
(1, 107),
(1, 108),
(1, 109),
(1, 110),
(1, 111),
(1, 112),
(1, 113),
(1, 114),
(1, 115),
(1, 116),
(1, 117),
(1, 118),
(1, 119),
(1, 120),
(1, 121),
(1, 122),
(1, 123),
(1, 124),
(1, 125),
(1, 126),
(1, 127),
(1, 128),
(1, 129),
(1, 130),
(1, 131),
(1, 132),
(1, 133),
(1, 134),
(1, 135),
(1, 136),
(1, 137),
(1, 138),
(1, 139),
(1, 140),
(1, 141),
(1, 142),
(1, 143),
(1, 144),
(1, 145),
(1, 146),
(1, 147),
(1, 148),
(1, 149),
(1, 150),
(1, 151),
(1, 152),
(1, 153),
(1, 154),
(1, 155),
(1, 156),
(1, 157),
(1, 158),
(1, 159),
(1, 160),
(1, 161),
(1, 162),
(1, 163),
(1, 164),
(1, 165),
(1, 166),
(1, 167),
(1, 168),
(1, 169),
(1, 170),
(1, 171),
(1, 172),
(1, 173),
(1, 174),
(1, 175),
(1, 176),
(1, 177),
(1, 178),
(1, 179),
(1, 180),
(1, 181),
(1, 182),
(1, 183),
(1, 184),
(1, 185),
(1, 186),
(1, 187),
(1, 188),
(1, 189),
(1, 190),
(1, 191),
(1, 192),
(1, 193),
(1, 194),
(1, 195),
(1, 196),
(1, 197),
(1, 198),
(1, 199),
(1, 200),
(1, 201),
(1, 202),
(1, 203),
(1, 204),
(1, 205),
(1, 206),
(1, 207),
(1, 208),
(1, 209),
(1, 210),
(1, 211),
(1, 212),
(1, 213),
(1, 214),
(1, 215),
(1, 216),
(1, 217),
(1, 218),
(1, 219),
(1, 220),
(1, 221),
(1, 222),
(1, 223),
(1, 224),
(1, 225),
(1, 226),
(1, 227),
(1, 228),
(1, 229),
(1, 230),
(1, 231),
(1, 232),
(1, 233),
(1, 234),
(1, 235),
(1, 236),
(1, 237),
(1, 238),
(1, 239),
(1, 240),
(1, 241),
(1, 242),
(1, 243),
(1, 244),
(1, 245),
(1, 246),
(1, 247),
(1, 248),
(1, 249),
(1, 250),
(1, 251),
(1, 252),
(1, 253),
(1, 254),
(1, 255),
(1, 256),
(1, 257),
(1, 258),
(1, 259),
(1, 260),
(1, 261),
(1, 262),
(1, 263),
(1, 264),
(1, 265),
(2, 17),
(2, 18),
(2, 23),
(2, 24),
(2, 25),
(2, 26),
(2, 27),
(2, 28),
(2, 29),
(2, 30),
(2, 31),
(2, 32),
(2, 33),
(2, 34),
(2, 35),
(2, 36),
(2, 37),
(2, 38),
(2, 39),
(2, 40),
(2, 41),
(2, 42),
(2, 43),
(2, 44),
(2, 45),
(2, 46),
(2, 47),
(2, 48),
(2, 49),
(2, 50),
(2, 51),
(2, 52),
(2, 53),
(2, 54),
(2, 55),
(2, 56),
(2, 57),
(2, 58),
(2, 59),
(2, 60),
(2, 61),
(2, 62),
(2, 63),
(2, 64),
(2, 65),
(2, 66),
(2, 67),
(2, 68),
(2, 69),
(2, 70),
(2, 71),
(2, 72),
(2, 73),
(2, 74),
(2, 75),
(2, 76),
(2, 77),
(2, 78),
(2, 79),
(2, 80),
(2, 81),
(2, 82),
(2, 83),
(2, 84),
(2, 85),
(2, 86),
(2, 87),
(2, 88),
(2, 89),
(2, 90),
(2, 91),
(2, 92),
(2, 93),
(2, 94),
(2, 95),
(2, 96),
(2, 97),
(2, 98),
(2, 99),
(2, 100),
(2, 101),
(2, 102),
(2, 103),
(2, 104),
(2, 105),
(2, 106),
(2, 107),
(2, 108),
(2, 109),
(2, 110),
(2, 111),
(2, 112),
(2, 113),
(2, 114),
(2, 115),
(2, 116),
(2, 117),
(2, 118),
(2, 119),
(2, 120),
(2, 121),
(2, 122),
(2, 123),
(2, 124),
(2, 125),
(2, 126),
(2, 127),
(2, 128),
(2, 129),
(2, 130),
(2, 131),
(2, 132),
(2, 133),
(2, 134),
(2, 135),
(2, 136),
(2, 137),
(2, 138),
(2, 139),
(2, 140),
(2, 141),
(2, 142),
(2, 143),
(2, 144),
(2, 145),
(2, 146),
(2, 147),
(2, 148),
(2, 149),
(2, 150),
(2, 151),
(2, 152),
(2, 153),
(2, 154),
(2, 155),
(2, 156),
(2, 157),
(2, 158),
(2, 159),
(2, 160),
(2, 161),
(2, 162),
(2, 163),
(2, 164),
(2, 165),
(2, 166),
(2, 167),
(2, 168),
(2, 169),
(2, 170),
(2, 171),
(2, 172),
(2, 173),
(2, 174),
(2, 175),
(2, 176),
(2, 177),
(2, 178),
(2, 179),
(2, 180),
(2, 181),
(2, 182),
(2, 183),
(2, 184),
(2, 185),
(2, 186),
(2, 187),
(2, 188),
(2, 189),
(2, 190),
(2, 191),
(2, 192),
(2, 193),
(2, 194),
(2, 195),
(2, 196),
(2, 197),
(2, 198),
(2, 199),
(2, 200),
(2, 201),
(2, 202),
(2, 203),
(2, 204),
(2, 205),
(2, 206),
(2, 207),
(2, 208),
(2, 209),
(2, 210),
(2, 211),
(2, 212),
(2, 213),
(2, 214),
(2, 215),
(2, 216),
(2, 217),
(2, 218),
(2, 219),
(2, 220),
(2, 221),
(2, 222),
(2, 223),
(2, 224),
(2, 225),
(2, 226),
(2, 227),
(2, 228),
(2, 229),
(2, 230),
(2, 231),
(2, 232),
(2, 233),
(2, 234),
(2, 235),
(2, 236),
(2, 237),
(2, 238),
(2, 239),
(2, 240),
(2, 241),
(2, 242),
(2, 243),
(2, 244),
(2, 245),
(2, 246),
(2, 247),
(2, 248),
(2, 249),
(2, 250),
(2, 251),
(2, 252),
(2, 253),
(2, 254),
(2, 255),
(2, 256),
(2, 257),
(2, 258),
(2, 259),
(2, 260),
(2, 261),
(2, 262),
(2, 263),
(2, 264),
(2, 265);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `budget` double(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_statuses`
--

CREATE TABLE `project_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qa_messages`
--

CREATE TABLE `qa_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qa_topics`
--

CREATE TABLE `qa_topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_text` varchar(255) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `test_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `option_text` varchar(255) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `question_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', NULL, NULL, NULL),
(2, 'User', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `dob` date NOT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `qualification` varchar(255) NOT NULL,
  `parmanet_address` longtext DEFAULT NULL,
  `present_address` varchar(255) DEFAULT NULL,
  `date_of_joing` date DEFAULT NULL,
  `course_fee` decimal(15,2) DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount` decimal(15,2) DEFAULT NULL,
  `due_amount` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `select_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `refered_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_payrolls`
--

CREATE TABLE `student_payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_amount` varchar(255) NOT NULL,
  `paid_amount` decimal(15,2) DEFAULT NULL,
  `due_amount` decimal(15,2) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `note` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `student_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_statuses`
--

CREATE TABLE `task_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_statuses`
--

INSERT INTO `task_statuses` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Open', NULL, NULL, NULL),
(2, 'In progress', NULL, NULL, NULL),
(3, 'Closed', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `task_tags`
--

CREATE TABLE `task_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_task_tag`
--

CREATE TABLE `task_task_tag` (
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `task_tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `date_of_joining` date NOT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `salary` decimal(15,2) NOT NULL,
  `known_language` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `select_teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_timeings`
--

CREATE TABLE `teacher_timeings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `classes_timeing` time DEFAULT NULL,
  `selecte_days` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lesson_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_answers`
--

CREATE TABLE `test_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `test_result_id` bigint(20) UNSIGNED DEFAULT NULL,
  `question_id` bigint(20) UNSIGNED DEFAULT NULL,
  `option_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_results`
--

CREATE TABLE `test_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `test_id` bigint(20) UNSIGNED DEFAULT NULL,
  `student_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_entries`
--

CREATE TABLE `time_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `work_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_projects`
--

CREATE TABLE `time_projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_work_types`
--

CREATE TABLE `time_work_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `select_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `income_source_id` bigint(20) UNSIGNED DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_types`
--

CREATE TABLE `transaction_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `status`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', 'admin@admin.com', NULL, '$2y$10$VPpO4R7xRwbLAa3Ob8WqOeeK33uG2pbZKkWYW1SoQoIYz4ZBOvTT6', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_alerts`
--

CREATE TABLE `user_alerts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `alert_text` varchar(255) NOT NULL,
  `alert_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_user_alert`
--

CREATE TABLE `user_user_alert` (
  `user_alert_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_fk_10762087` (`status_id`);

--
-- Indexes for table `client_statuses`
--
ALTER TABLE `client_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_companies`
--
ALTER TABLE `contact_companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_contacts`
--
ALTER TABLE `contact_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_fk_10762136` (`company_id`);

--
-- Indexes for table `content_categories`
--
ALTER TABLE `content_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_category_content_page`
--
ALTER TABLE `content_category_content_page`
  ADD KEY `content_page_id_fk_10762214` (`content_page_id`),
  ADD KEY `content_category_id_fk_10762214` (`content_category_id`);

--
-- Indexes for table `content_pages`
--
ALTER TABLE `content_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_page_content_tag`
--
ALTER TABLE `content_page_content_tag`
  ADD KEY `content_page_id_fk_10762215` (`content_page_id`),
  ADD KEY `content_tag_id_fk_10762215` (`content_tag_id`);

--
-- Indexes for table `content_tags`
--
ALTER TABLE `content_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_fk_10761970` (`teacher_id`),
  ADD KEY `created_by_fk_10762252` (`created_by_id`);

--
-- Indexes for table `course_student`
--
ALTER TABLE `course_student`
  ADD KEY `student_id_fk_10762262` (`student_id`),
  ADD KEY `course_id_fk_10762262` (`course_id`);

--
-- Indexes for table `course_teacher`
--
ALTER TABLE `course_teacher`
  ADD KEY `teacher_id_fk_10762288` (`teacher_id`),
  ADD KEY `course_id_fk_10762288` (`course_id`);

--
-- Indexes for table `course_user`
--
ALTER TABLE `course_user`
  ADD KEY `course_id_fk_10761976` (`course_id`),
  ADD KEY `user_id_fk_10761976` (`user_id`);

--
-- Indexes for table `crm_customers`
--
ALTER TABLE `crm_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_fk_10762155` (`status_id`),
  ADD KEY `created_by_fk_10762304` (`created_by_id`);

--
-- Indexes for table `crm_documents`
--
ALTER TABLE `crm_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_fk_10762172` (`customer_id`);

--
-- Indexes for table `crm_notes`
--
ALTER TABLE `crm_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_fk_10762166` (`customer_id`),
  ADD KEY `created_by_fk_10762306` (`created_by_id`);

--
-- Indexes for table `crm_statuses`
--
ALTER TABLE `crm_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by_fk_10762303` (`created_by_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_fk_10762108` (`project_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_category_fk_10761954` (`expense_category_id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `income_category_fk_10761962` (`income_category_id`);

--
-- Indexes for table `income_categories`
--
ALTER TABLE `income_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_sources`
--
ALTER TABLE `income_sources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_fk_10761981` (`course_id`),
  ADD KEY `created_by_fk_10762253` (`created_by_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_fk_10762102` (`project_id`);

--
-- Indexes for table `other_employees`
--
ALTER TABLE `other_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `other_employees_phone_number_unique` (`phone_number`),
  ADD KEY `select_employee_fk_10762290` (`select_employee_id`),
  ADD KEY `created_by_fk_10762301` (`created_by_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD KEY `role_id_fk_10761914` (`role_id`),
  ADD KEY `permission_id_fk_10761914` (`permission_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_fk_10762093` (`client_id`),
  ADD KEY `status_fk_10762097` (`status_id`);

--
-- Indexes for table `project_statuses`
--
ALTER TABLE `project_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qa_messages`
--
ALTER TABLE `qa_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qa_messages_topic_id_foreign` (`topic_id`),
  ADD KEY `qa_messages_sender_id_foreign` (`sender_id`);

--
-- Indexes for table `qa_topics`
--
ALTER TABLE `qa_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qa_topics_creator_id_foreign` (`creator_id`),
  ADD KEY `qa_topics_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_fk_10762003` (`test_id`),
  ADD KEY `created_by_fk_10762255` (`created_by_id`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_fk_10762011` (`question_id`),
  ADD KEY `created_by_fk_10762256` (`created_by_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD KEY `user_id_fk_10761923` (`user_id`),
  ADD KEY `role_id_fk_10761923` (`role_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_phone_number_unique` (`phone_number`),
  ADD KEY `select_user_fk_10762260` (`select_user_id`),
  ADD KEY `refered_by_fk_10762261` (`refered_by_id`),
  ADD KEY `created_by_fk_10762278` (`created_by_id`);

--
-- Indexes for table `student_payrolls`
--
ALTER TABLE `student_payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_fk_10762482` (`student_id`),
  ADD KEY `created_by_fk_10762493` (`created_by_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_fk_10762192` (`status_id`),
  ADD KEY `assigned_to_fk_10762196` (`assigned_to_id`);

--
-- Indexes for table `task_statuses`
--
ALTER TABLE `task_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_tags`
--
ALTER TABLE `task_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_task_tag`
--
ALTER TABLE `task_task_tag`
  ADD KEY `task_id_fk_10762193` (`task_id`),
  ADD KEY `task_tag_id_fk_10762193` (`task_tag_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teachers_phone_number_unique` (`phone_number`),
  ADD KEY `select_teacher_fk_10762280` (`select_teacher_id`),
  ADD KEY `created_by_fk_10762287` (`created_by_id`);

--
-- Indexes for table `teacher_timeings`
--
ALTER TABLE `teacher_timeings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_fk_10762336` (`teacher_id`),
  ADD KEY `created_by_fk_10762342` (`created_by_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_fk_10761994` (`course_id`),
  ADD KEY `lesson_fk_10761995` (`lesson_id`),
  ADD KEY `created_by_fk_10762254` (`created_by_id`);

--
-- Indexes for table `test_answers`
--
ALTER TABLE `test_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_result_fk_10762025` (`test_result_id`),
  ADD KEY `question_fk_10762026` (`question_id`),
  ADD KEY `option_fk_10762027` (`option_id`),
  ADD KEY `created_by_fk_10762258` (`created_by_id`);

--
-- Indexes for table `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_fk_10762018` (`test_id`),
  ADD KEY `student_fk_10762019` (`student_id`),
  ADD KEY `created_by_fk_10762257` (`created_by_id`);

--
-- Indexes for table `time_entries`
--
ALTER TABLE `time_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_type_fk_10762043` (`work_type_id`),
  ADD KEY `project_fk_10762044` (`project_id`),
  ADD KEY `created_by_fk_10762310` (`created_by_id`);

--
-- Indexes for table `time_projects`
--
ALTER TABLE `time_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by_fk_10762309` (`created_by_id`);

--
-- Indexes for table `time_work_types`
--
ALTER TABLE `time_work_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `select_user_fk_10762307` (`select_user_id`),
  ADD KEY `created_by_fk_10762308` (`created_by_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_fk_10762116` (`project_id`),
  ADD KEY `transaction_type_fk_10762117` (`transaction_type_id`),
  ADD KEY `income_source_fk_10762118` (`income_source_id`),
  ADD KEY `currency_fk_10762120` (`currency_id`);

--
-- Indexes for table `transaction_types`
--
ALTER TABLE `transaction_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_alerts`
--
ALTER TABLE `user_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_user_alert`
--
ALTER TABLE `user_user_alert`
  ADD KEY `user_alert_id_fk_10761940` (`user_alert_id`),
  ADD KEY `user_id_fk_10761940` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_statuses`
--
ALTER TABLE `client_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_companies`
--
ALTER TABLE `contact_companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_contacts`
--
ALTER TABLE `contact_contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `content_categories`
--
ALTER TABLE `content_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `content_pages`
--
ALTER TABLE `content_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `content_tags`
--
ALTER TABLE `content_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_customers`
--
ALTER TABLE `crm_customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_documents`
--
ALTER TABLE `crm_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_notes`
--
ALTER TABLE `crm_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_statuses`
--
ALTER TABLE `crm_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income_categories`
--
ALTER TABLE `income_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income_sources`
--
ALTER TABLE `income_sources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_employees`
--
ALTER TABLE `other_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=266;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_statuses`
--
ALTER TABLE `project_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qa_messages`
--
ALTER TABLE `qa_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qa_topics`
--
ALTER TABLE `qa_topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_payrolls`
--
ALTER TABLE `student_payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_statuses`
--
ALTER TABLE `task_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `task_tags`
--
ALTER TABLE `task_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_timeings`
--
ALTER TABLE `teacher_timeings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_answers`
--
ALTER TABLE `test_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_results`
--
ALTER TABLE `test_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_entries`
--
ALTER TABLE `time_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_projects`
--
ALTER TABLE `time_projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_work_types`
--
ALTER TABLE `time_work_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_types`
--
ALTER TABLE `transaction_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_alerts`
--
ALTER TABLE `user_alerts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `status_fk_10762087` FOREIGN KEY (`status_id`) REFERENCES `client_statuses` (`id`);

--
-- Constraints for table `contact_contacts`
--
ALTER TABLE `contact_contacts`
  ADD CONSTRAINT `company_fk_10762136` FOREIGN KEY (`company_id`) REFERENCES `contact_companies` (`id`);

--
-- Constraints for table `content_category_content_page`
--
ALTER TABLE `content_category_content_page`
  ADD CONSTRAINT `content_category_id_fk_10762214` FOREIGN KEY (`content_category_id`) REFERENCES `content_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `content_page_id_fk_10762214` FOREIGN KEY (`content_page_id`) REFERENCES `content_pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `content_page_content_tag`
--
ALTER TABLE `content_page_content_tag`
  ADD CONSTRAINT `content_page_id_fk_10762215` FOREIGN KEY (`content_page_id`) REFERENCES `content_pages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `content_tag_id_fk_10762215` FOREIGN KEY (`content_tag_id`) REFERENCES `content_tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `created_by_fk_10762252` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `teacher_fk_10761970` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `course_student`
--
ALTER TABLE `course_student`
  ADD CONSTRAINT `course_id_fk_10762262` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_id_fk_10762262` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_teacher`
--
ALTER TABLE `course_teacher`
  ADD CONSTRAINT `course_id_fk_10762288` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_id_fk_10762288` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_user`
--
ALTER TABLE `course_user`
  ADD CONSTRAINT `course_id_fk_10761976` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id_fk_10761976` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `crm_customers`
--
ALTER TABLE `crm_customers`
  ADD CONSTRAINT `created_by_fk_10762304` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `status_fk_10762155` FOREIGN KEY (`status_id`) REFERENCES `crm_statuses` (`id`);

--
-- Constraints for table `crm_documents`
--
ALTER TABLE `crm_documents`
  ADD CONSTRAINT `customer_fk_10762172` FOREIGN KEY (`customer_id`) REFERENCES `crm_customers` (`id`);

--
-- Constraints for table `crm_notes`
--
ALTER TABLE `crm_notes`
  ADD CONSTRAINT `created_by_fk_10762306` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `customer_fk_10762166` FOREIGN KEY (`customer_id`) REFERENCES `crm_customers` (`id`);

--
-- Constraints for table `crm_statuses`
--
ALTER TABLE `crm_statuses`
  ADD CONSTRAINT `created_by_fk_10762303` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `project_fk_10762108` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expense_category_fk_10761954` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`);

--
-- Constraints for table `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `income_category_fk_10761962` FOREIGN KEY (`income_category_id`) REFERENCES `income_categories` (`id`);

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `course_fk_10761981` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `created_by_fk_10762253` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `project_fk_10762102` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `other_employees`
--
ALTER TABLE `other_employees`
  ADD CONSTRAINT `created_by_fk_10762301` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `select_employee_fk_10762290` FOREIGN KEY (`select_employee_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_id_fk_10761914` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_id_fk_10761914` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `client_fk_10762093` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `status_fk_10762097` FOREIGN KEY (`status_id`) REFERENCES `project_statuses` (`id`);

--
-- Constraints for table `qa_messages`
--
ALTER TABLE `qa_messages`
  ADD CONSTRAINT `qa_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `qa_messages_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `qa_topics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `qa_topics`
--
ALTER TABLE `qa_topics`
  ADD CONSTRAINT `qa_topics_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `qa_topics_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `created_by_fk_10762255` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `test_fk_10762003` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`);

--
-- Constraints for table `question_options`
--
ALTER TABLE `question_options`
  ADD CONSTRAINT `created_by_fk_10762256` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `question_fk_10762011` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_id_fk_10761923` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id_fk_10761923` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `created_by_fk_10762278` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `refered_by_fk_10762261` FOREIGN KEY (`refered_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `select_user_fk_10762260` FOREIGN KEY (`select_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `student_payrolls`
--
ALTER TABLE `student_payrolls`
  ADD CONSTRAINT `created_by_fk_10762493` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `student_fk_10762482` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `assigned_to_fk_10762196` FOREIGN KEY (`assigned_to_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `status_fk_10762192` FOREIGN KEY (`status_id`) REFERENCES `task_statuses` (`id`);

--
-- Constraints for table `task_task_tag`
--
ALTER TABLE `task_task_tag`
  ADD CONSTRAINT `task_id_fk_10762193` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_tag_id_fk_10762193` FOREIGN KEY (`task_tag_id`) REFERENCES `task_tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `created_by_fk_10762287` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `select_teacher_fk_10762280` FOREIGN KEY (`select_teacher_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `teacher_timeings`
--
ALTER TABLE `teacher_timeings`
  ADD CONSTRAINT `created_by_fk_10762342` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `teacher_fk_10762336` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);

--
-- Constraints for table `tests`
--
ALTER TABLE `tests`
  ADD CONSTRAINT `course_fk_10761994` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `created_by_fk_10762254` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `lesson_fk_10761995` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`);

--
-- Constraints for table `test_answers`
--
ALTER TABLE `test_answers`
  ADD CONSTRAINT `created_by_fk_10762258` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `option_fk_10762027` FOREIGN KEY (`option_id`) REFERENCES `question_options` (`id`),
  ADD CONSTRAINT `question_fk_10762026` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `test_result_fk_10762025` FOREIGN KEY (`test_result_id`) REFERENCES `test_results` (`id`);

--
-- Constraints for table `test_results`
--
ALTER TABLE `test_results`
  ADD CONSTRAINT `created_by_fk_10762257` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `student_fk_10762019` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `test_fk_10762018` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`);

--
-- Constraints for table `time_entries`
--
ALTER TABLE `time_entries`
  ADD CONSTRAINT `created_by_fk_10762310` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `project_fk_10762044` FOREIGN KEY (`project_id`) REFERENCES `time_projects` (`id`),
  ADD CONSTRAINT `work_type_fk_10762043` FOREIGN KEY (`work_type_id`) REFERENCES `time_work_types` (`id`);

--
-- Constraints for table `time_projects`
--
ALTER TABLE `time_projects`
  ADD CONSTRAINT `created_by_fk_10762309` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `time_work_types`
--
ALTER TABLE `time_work_types`
  ADD CONSTRAINT `created_by_fk_10762308` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `select_user_fk_10762307` FOREIGN KEY (`select_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `currency_fk_10762120` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `income_source_fk_10762118` FOREIGN KEY (`income_source_id`) REFERENCES `income_sources` (`id`),
  ADD CONSTRAINT `project_fk_10762116` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `transaction_type_fk_10762117` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_types` (`id`);

--
-- Constraints for table `user_user_alert`
--
ALTER TABLE `user_user_alert`
  ADD CONSTRAINT `user_alert_id_fk_10761940` FOREIGN KEY (`user_alert_id`) REFERENCES `user_alerts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id_fk_10761940` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

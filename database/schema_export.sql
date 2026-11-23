-- ====================================================================
-- IT0049 (Web System Technologies) - Technical Summative Assessment 4
-- Student: Simoune Nicole
-- Project: Tasks for Today Management System (Full CRUD, Auth & Mail)
-- File: database/schema_export.sql
-- ====================================================================

SET NAMES utf8mb4;
SET time_zone = '+08:00';

DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Demo password for seeded user: "password"
INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `email_verified_at`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Kat.1na', 'Katarina Ivanov', 'katarina.ivanov@gmail.com', NOW(), '$2y$12$eImiTXuWVxfM37uY4JANjQ==', NOW(), NOW());

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `task_date` date NOT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_task_date_index` (`task_date`),
  KEY `tasks_is_archived_index` (`is_archived`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tasks` (`title`, `status`, `task_date`, `is_archived`, `created_at`, `updated_at`) VALUES
('Prepare Quarterly Reports', 'pending', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 0, NOW(), NOW()),
('Pitch Deck', 'pending', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 0, NOW(), NOW()),
('Meeting', 'ongoing', CURDATE(), 0, NOW(), NOW()),
('Project deployment', 'completed', CURDATE(), 0, NOW(), NOW()),
('Review Investor Feedback', 'completed', CURDATE(), 0, NOW(), NOW()),
('Meeting with Marketing', 'pending', DATE_ADD(CURDATE(), INTERVAL 1 DAY), 0, NOW(), NOW()),
('Review the monthly budget', 'ongoing', DATE_ADD(CURDATE(), INTERVAL 1 DAY), 0, NOW(), NOW()),
('Finalize client onboarding checklist', 'pending', DATE_ADD(CURDATE(), INTERVAL 2 DAY), 0, NOW(), NOW()),
('Audit weekly team milestones', 'pending', DATE_ADD(CURDATE(), INTERVAL 2 DAY), 0, NOW(), NOW());

-- ====================================================================
-- IT0049 (Web System Technologies) - Technical Summative Assessment 3
-- Student: Simoune Nicole
-- Project: Tasks for Today Management System (Laravel)
-- File: database/schema_export.sql
-- ====================================================================

SET NAMES utf8mb4;
SET time_zone = '+08:00';

DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Kat.1na', 'Katarina Ivanov', 'katarina.ivanov@gmail.com', NOW(), NOW());

CREATE TABLE `tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `task_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_task_date_index` (`task_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tasks` (`title`, `status`, `task_date`, `created_at`, `updated_at`) VALUES
('Meeting', 'ongoing', CURDATE(), NOW(), NOW()),
('Project deployment', 'completed', CURDATE(), NOW(), NOW()),
('Review Investor Feedback', 'completed', CURDATE(), NOW(), NOW()),
('Prepare Quarterly Reports', 'pending', DATE_SUB(CURDATE(), INTERVAL 1 DAY), NOW(), NOW()),
('Pitch Deck', 'pending', DATE_SUB(CURDATE(), INTERVAL 1 DAY), NOW(), NOW()),
('Meeting with Marketing', 'pending', DATE_ADD(CURDATE(), INTERVAL 1 DAY), NOW(), NOW()),
('Review the monthly budget', 'ongoing', DATE_ADD(CURDATE(), INTERVAL 1 DAY), NOW(), NOW()),
('Finalize client onboarding checklist', 'pending', DATE_ADD(CURDATE(), INTERVAL 2 DAY), NOW(), NOW()),
('Audit weekly team milestones', 'pending', DATE_ADD(CURDATE(), INTERVAL 2 DAY), NOW(), NOW());

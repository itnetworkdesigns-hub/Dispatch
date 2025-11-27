-- SQL to create database and users table for dispatch app
-- Update DB name `dispatch_db` as you like

CREATE DATABASE IF NOT EXISTS `dispatch_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `dispatch_db`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(191) NOT NULL,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('trucker','supplier') NOT NULL DEFAULT 'trucker',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional index on role
CREATE INDEX idx_users_role ON users(role);

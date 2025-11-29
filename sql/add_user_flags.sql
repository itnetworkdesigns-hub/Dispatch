-- Migration: add approval and admin flags to users
USE `dispatch_db`;

ALTER TABLE users
  ADD COLUMN IF NOT EXISTS `is_approved` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `is_admin` TINYINT(1) NOT NULL DEFAULT 0;

CREATE INDEX IF NOT EXISTS idx_users_is_approved ON users(is_approved);

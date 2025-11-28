-- Migration: add trucker columns to orders table
USE `dispatch_db`;

ALTER TABLE orders
  ADD COLUMN IF NOT EXISTS `trucker_id` INT UNSIGNED NULL AFTER `supplier_id`,
  ADD COLUMN IF NOT EXISTS `accepted_at` TIMESTAMP NULL DEFAULT NULL AFTER `status`;

-- index for quick lookup
CREATE INDEX IF NOT EXISTS idx_orders_trucker_id ON orders(trucker_id);

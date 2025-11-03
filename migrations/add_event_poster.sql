-- Add a poster column to the event table to store poster file path/URL
-- Run this in your MySQL console or phpMyAdmin connected to the ticketing database.

ALTER TABLE `event`
  ADD COLUMN `poster` VARCHAR(255) NULL AFTER `Category_ID`;

-- Add Category_ID to invoice so purchases record the selected seat category
-- Run this on your MySQL database (phpMyAdmin or mysql client)

ALTER TABLE `invoice`
  ADD COLUMN `Category_ID` INT UNSIGNED NULL AFTER `Event_ID`;

-- Add foreign key constraint to category table (set null if category deleted)
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_invoice_category` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

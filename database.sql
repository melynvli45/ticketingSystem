-- Database schema for Ticketing System
-- Use: CREATE DATABASE tixpop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- then: USE tixpop;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `User_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Full_name` VARCHAR(255) NOT NULL,
  `Email` VARCHAR(255) NOT NULL,
  `Password` VARCHAR(255) NOT NULL,
  `User_type` ENUM('user','admin') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `uq_users_email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Category table (seat categories / ticket categories)
CREATE TABLE IF NOT EXISTS `category` (
  `Category_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `Category_type` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`Category_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Event table
CREATE TABLE IF NOT EXISTS `event` (
  `Event_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Date` DATE NOT NULL,
  `Time` TIME NULL,
  `Venue` VARCHAR(255) NOT NULL,
  `Name` VARCHAR(255) NOT NULL,
  `Category_ID` INT UNSIGNED NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Event_ID`),
  KEY `fk_event_category_idx` (`Category_ID`),
  CONSTRAINT `fk_event_category` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Invoice table (purchase cart / order)
CREATE TABLE IF NOT EXISTS `invoice` (
  `Invoice_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `User_ID` INT UNSIGNED NOT NULL,
  `Event_ID` INT UNSIGNED NOT NULL,
  `Quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `Date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  -- Payment is modelled in the `payment` table referencing this invoice (one-to-one)
  PRIMARY KEY (`Invoice_ID`),
  KEY `fk_invoice_user_idx` (`User_ID`),
  KEY `fk_invoice_event_idx` (`Event_ID`),
  CONSTRAINT `fk_invoice_user` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_invoice_event` FOREIGN KEY (`Event_ID`) REFERENCES `event` (`Event_ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payment table (proof of payment, status)
CREATE TABLE IF NOT EXISTS `payment` (
  `Payment_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Invoice_ID` INT UNSIGNED NOT NULL,
  `Payment_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Proof_of_payment` VARCHAR(512) NULL,
  `Payment_status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`Payment_ID`),
  UNIQUE KEY `uq_payment_invoice` (`Invoice_ID`),
  KEY `fk_payment_invoice_idx` (`Invoice_ID`),
  CONSTRAINT `fk_payment_invoice` FOREIGN KEY (`Invoice_ID`) REFERENCES `invoice` (`Invoice_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notes:
-- 1) The diagram attached shows a Payment <-> Invoice relationship. To avoid circular foreign-key creation, this schema models a single-direction relation where `payment.Invoice_ID` references `invoice.Invoice_ID` (one payment per invoice). If you need Invoice to also contain a Payment_ID FK, that can be implemented as a nullable column without a foreign key constraint or by creating one FK via ALTER TABLE after both tables exist.
-- 2) Adjust types/lengths according to expected data and add more constraints (e.g., seat allocation) as needed.

-- Sample: create user and sample category
INSERT INTO `category` (`Price`, `Category_type`, `description`) VALUES (150.00, 'VIP', 'Front-row VIP seating'), (50.00, 'General', 'General admission');

-- End of `database.sql` file

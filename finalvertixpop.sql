-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2025 at 03:33 PM
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
-- Database: `tixpop`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `Category_ID` int(10) UNSIGNED NOT NULL,
  `Price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Category_type` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Category_ID`, `Price`, `Category_type`, `description`) VALUES
(1, 1099.00, 'VIP', 'Front-row VIP seating'),
(3, 599.00, 'Bronze', 'Standard seating'),
(4, 699.00, 'Platinum', 'Premium middle-section seating'),
(5, 899.00, 'Gold', 'Best middle-section seating');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `Event_ID` int(10) UNSIGNED NOT NULL,
  `Date` date NOT NULL,
  `Time` time DEFAULT NULL,
  `Venue` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`Event_ID`, `Date`, `Time`, `Venue`, `Name`, `poster`, `created_at`) VALUES
(1, '2026-03-15', '20:00:00', 'Seoul Olympic Stadium', 'BLACKPINK - BORN PINK Encore Concert', NULL, '2025-10-27 11:48:32'),
(2, '2026-04-01', '16:00:00', 'COEX Convention Hall', 'Stray Kids - 5-STAR Fan Sign Event', NULL, '2025-10-27 11:48:32'),
(3, '2026-04-20', '12:00:00', 'YouTube Live Stream', 'NewJeans - How Sweet Album Release Party', NULL, '2025-10-27 11:48:32'),
(10, '2026-02-11', '12:12:00', 'GMI KT5 Lab Crypto', 'Zizan On the GMI yo', 'image/1762680815_zizan.jpg', '2025-11-09 09:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `Invoice_ID` int(10) UNSIGNED NOT NULL,
  `User_ID` int(10) UNSIGNED NOT NULL,
  `Event_ID` int(10) UNSIGNED NOT NULL,
  `Category_ID` int(10) UNSIGNED DEFAULT NULL,
  `Quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Refund_reason` text DEFAULT NULL,
  `Ticket_status` enum('active','refunded','cancelled') NOT NULL DEFAULT 'active',
  `Payment_ID` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`Invoice_ID`, `User_ID`, `Event_ID`, `Category_ID`, `Quantity`, `Date`, `Refund_reason`, `Ticket_status`, `Payment_ID`) VALUES
(38, 8, 1, 1, 2, '2025-11-09 17:25:37', NULL, 'active', NULL),
(39, 11, 2, 3, 1, '2025-11-09 17:30:29', 'Other: I am very busy because gmi paper', 'cancelled', NULL),
(41, 11, 10, 5, 1, '2025-11-09 17:43:13', NULL, 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Payment_ID` int(10) UNSIGNED NOT NULL,
  `Invoice_ID` int(10) UNSIGNED NOT NULL,
  `Payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `Proof_of_payment` varchar(512) DEFAULT NULL,
  `Payment_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`Payment_ID`, `Invoice_ID`, `Payment_date`, `Proof_of_payment`, `Payment_status`) VALUES
(38, 38, '2025-11-09 17:27:21', 'uploads/payment_38_1762680441_mocktransfer.png', 'rejected'),
(39, 39, '2025-11-09 17:30:39', 'uploads/payment_39_1762680639_mocktransfer.png', 'pending'),
(41, 41, '2025-11-09 17:43:19', 'uploads/payment_41_1762681399_mocktransfer.png', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(10) UNSIGNED NOT NULL,
  `Full_name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `User_type` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `Full_name`, `Email`, `Password`, `User_type`, `created_at`) VALUES
(1, 'amirkawansayaaaa', 'amirkawan@gmail.com', '$2y$10$.b7eQ1T342rDAr7Kl2BQ4u5hPfS8aYhN23h1ppYofpmMnMdHVVmGK', 'admin', '2025-10-27 11:25:21'),
(8, 'amir arshad bin user', 'amiruser@gmail.com', '$2y$10$coN2nYZhsh3llyyg4XBBq.sGrLIn11QRk5IreISDTjGPStK2VEU4a', 'user', '2025-11-07 17:27:54'),
(11, 'amir ke dua bin arshad', 'amiruser2@gmail.com', '$2y$10$SpQ5Fk8xE625Q2uQT0V/eOct.My6XS85bw1z7vp.BEssJ6zHERwu6', 'user', '2025-11-09 09:30:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`Category_ID`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`Event_ID`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`Invoice_ID`),
  ADD KEY `fk_invoice_user_idx` (`User_ID`),
  ADD KEY `fk_invoice_event_idx` (`Event_ID`),
  ADD KEY `fk_invoice_payment` (`Payment_ID`),
  ADD KEY `fk_invoice_category` (`Category_ID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Payment_ID`),
  ADD UNIQUE KEY `uq_payment_invoice` (`Invoice_ID`),
  ADD KEY `fk_payment_invoice_idx` (`Invoice_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `uq_users_email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `Category_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `Event_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `Invoice_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Payment_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_invoice_category` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoice_event` FOREIGN KEY (`Event_ID`) REFERENCES `event` (`Event_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoice_payment` FOREIGN KEY (`Payment_ID`) REFERENCES `payment` (`Payment_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoice_user` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_invoice` FOREIGN KEY (`Invoice_ID`) REFERENCES `invoice` (`Invoice_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

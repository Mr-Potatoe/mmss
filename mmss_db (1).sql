-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 02:28 PM
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
-- Database: `mmss_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `approved_services`
--

CREATE TABLE `approved_services` (
  `approved_service_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `mechanic_id` int(11) NOT NULL,
  `service_type_id` int(11) NOT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completion_status` enum('pending','in_progress','completed') DEFAULT 'pending',
  `completion_notes` text DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approved_services`
--


-- --------------------------------------------------------

--
-- Table structure for table `maintenance_schedules`
--

CREATE TABLE `maintenance_schedules` (
  `schedule_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_type_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL,
  `status` enum('scheduled','completed','canceled') DEFAULT 'scheduled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_schedules`
--

-- --------------------------------------------------------

--
-- Table structure for table `mechanics`
--

CREATE TABLE `mechanics` (
  `mechanic_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mechanics`
--


-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_type_id` int(11) NOT NULL,
  `schedule_date` date NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `mechanic_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_requests`
--


-- --------------------------------------------------------

--
-- Table structure for table `service_types`
--

CREATE TABLE `service_types` (
  `service_type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_types`
--

INSERT INTO `service_types` (`service_type_id`, `name`, `description`, `is_active`) VALUES
(2, 'Change Gear Oil', 'dsadsad', 1),
(3, 'Air Filter Cleaning', NULL, 1),
(4, 'CVT Cleaning', NULL, 1),
(5, 'Tire Replacement', NULL, 1),
(6, 'Brake Inspection', NULL, 1),
(7, 'Full Body Maintenance', NULL, 1),
(8, 'Wiring Checking', NULL, 1),
(9, 'Other', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('rider','admin') DEFAULT 'rider',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fullname`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(7, 'Admin', 'admin@gexample.com', '$2y$10$CWjojxSRfbUnOXlxE8aIAOGEjmgad4WoMjOedSEfixS0eegkuu5J6', 'admin', '2024-12-10 09:40:44', '2024-12-10 09:40:44'),
(11, 'john doe', 'joh@gmail.com', '$2y$10$11IlrMarYA5/jicl1fkL3.jDF8ywoQEtFIMfeZShZRp61DvBJsHAi', 'rider', '2024-12-10 12:58:34', '2024-12-10 12:58:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approved_services`
--
ALTER TABLE `approved_services`
  ADD PRIMARY KEY (`approved_service_id`),
  ADD UNIQUE KEY `request_id` (`request_id`),
  ADD KEY `mechanic_id` (`mechanic_id`),
  ADD KEY `service_type_id` (`service_type_id`),
  ADD KEY `idx_approved_services_completion` (`completion_status`);

--
-- Indexes for table `maintenance_schedules`
--
ALTER TABLE `maintenance_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_type_id` (`service_type_id`);

--
-- Indexes for table `mechanics`
--
ALTER TABLE `mechanics`
  ADD PRIMARY KEY (`mechanic_id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_type_id` (`service_type_id`),
  ADD KEY `idx_service_requests_status` (`status`),
  ADD KEY `idx_service_requests_date` (`schedule_date`),
  ADD KEY `mechanic_id` (`mechanic_id`);

--
-- Indexes for table `service_types`
--
ALTER TABLE `service_types`
  ADD PRIMARY KEY (`service_type_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approved_services`
--
ALTER TABLE `approved_services`
  MODIFY `approved_service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `maintenance_schedules`
--
ALTER TABLE `maintenance_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mechanics`
--
ALTER TABLE `mechanics`
  MODIFY `mechanic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_types`
--
ALTER TABLE `service_types`
  MODIFY `service_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approved_services`
--
ALTER TABLE `approved_services`
  ADD CONSTRAINT `approved_services_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `service_requests` (`request_id`),
  ADD CONSTRAINT `approved_services_ibfk_2` FOREIGN KEY (`mechanic_id`) REFERENCES `mechanics` (`mechanic_id`),
  ADD CONSTRAINT `approved_services_ibfk_3` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`service_type_id`);

--
-- Constraints for table `maintenance_schedules`
--
ALTER TABLE `maintenance_schedules`
  ADD CONSTRAINT `maintenance_schedules_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `maintenance_schedules_ibfk_2` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`service_type_id`);

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `service_requests_ibfk_2` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`service_type_id`),
  ADD CONSTRAINT `service_requests_ibfk_3` FOREIGN KEY (`mechanic_id`) REFERENCES `mechanics` (`mechanic_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

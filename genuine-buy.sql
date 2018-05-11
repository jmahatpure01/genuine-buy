-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 11, 2018 at 02:54 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `genuine-buy`
--
CREATE DATABASE IF NOT EXISTS `genuine-buy` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `genuine-buy`;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer_details`
--

CREATE TABLE `manufacturer_details` (
  `manufacturer_public_id` varchar(6) NOT NULL,
  `manufacturer_registered_office_address` varchar(250) NOT NULL,
  `manufacturer_plant_addresses` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_public_id` varchar(6) NOT NULL,
  `user_email` varchar(70) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_salt` varchar(6) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_address` varchar(250) NOT NULL,
  `user_phone` varchar(12) NOT NULL,
  `user_gstin` varchar(15) NOT NULL,
  `user_city` varchar(25) NOT NULL,
  `user_state` varchar(30) NOT NULL,
  `user_type` varchar(15) NOT NULL,
  `user_created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_medicines`
--

CREATE TABLE `user_medicines` (
  `id` int(11) NOT NULL,
  `user_medicine_id` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `manufacturer_details`
--
ALTER TABLE `manufacturer_details`
  ADD PRIMARY KEY (`manufacturer_public_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_username` (`user_public_id`),
  ADD KEY `user_email` (`user_email`);

--
-- Indexes for table `user_medicines`
--
ALTER TABLE `user_medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_medicine_id` (`user_medicine_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_medicines`
--
ALTER TABLE `user_medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_medicines`
--
ALTER TABLE `user_medicines`
  ADD CONSTRAINT `users_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

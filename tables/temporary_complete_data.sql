-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2024 at 08:55 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testing`
--

-- --------------------------------------------------------

--
-- Table structure for table `temporary_complete_data`
--

CREATE TABLE `temporary_complete_data` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `academic_year` varchar(255) DEFAULT NULL,
  `session` varchar(255) DEFAULT NULL,
  `alloted_category` varchar(255) DEFAULT NULL,
  `voucher_type` varchar(255) DEFAULT NULL,
  `voucher_number` int(11) DEFAULT NULL,
  `roll_number` varchar(255) NOT NULL,
  `admission_number` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `fee_category` varchar(255) DEFAULT NULL,
  `faculty` text DEFAULT NULL,
  `program` text DEFAULT NULL,
  `department` text DEFAULT NULL,
  `batch` text DEFAULT NULL,
  `receipt_number` varchar(255) DEFAULT NULL,
  `fee_head` varchar(255) DEFAULT NULL,
  `due_amount` int(11) DEFAULT NULL,
  `paid_amount` int(11) DEFAULT NULL,
  `concession_amount` int(11) DEFAULT NULL,
  `scholarship_amount` int(11) DEFAULT NULL,
  `reverse_concession_amount` int(11) DEFAULT NULL,
  `write_off_amount` int(11) DEFAULT NULL,
  `adjusted_amount` int(11) DEFAULT NULL,
  `refund_amount` int(11) DEFAULT NULL,
  `fund_transfer_amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `temporary_complete_data`
--
ALTER TABLE `temporary_complete_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `temporary_complete_data`
--
ALTER TABLE `temporary_complete_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

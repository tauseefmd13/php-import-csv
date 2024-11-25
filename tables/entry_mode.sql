-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2024 at 08:27 PM
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
-- Table structure for table `entry_mode`
--

CREATE TABLE `entry_mode` (
  `id` int(11) NOT NULL,
  `entry_mode_name` varchar(255) DEFAULT NULL,
  `crdr` varchar(255) DEFAULT NULL,
  `entry_mode_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `entry_mode`
--

INSERT INTO `entry_mode` (`id`, `entry_mode_name`, `crdr`, `entry_mode_number`) VALUES
(1, 'DUE', 'D', 0),
(2, 'REVDUE', 'C', 12),
(3, 'SCHOLARSHIP', 'C', 15),
(4, 'SCHOLARSHIPREV/REVCONCESSION', 'D', 16),
(5, 'CONCESSION', 'C', 15),
(6, 'RCPT', 'C', 0),
(7, 'REVRCPT', 'D', 0),
(8, 'JV', 'C', 14),
(9, 'REVJV', 'D', 14),
(10, 'PMT', 'D', 1),
(11, 'REVPMT', 'C', 1),
(12, 'Fundtransfer', '+ ve and -ve', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `entry_mode`
--
ALTER TABLE `entry_mode`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `entry_mode`
--
ALTER TABLE `entry_mode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

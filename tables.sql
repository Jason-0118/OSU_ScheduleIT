-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 08, 2023 at 07:39 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `idEvent` int(11) NOT NULL,
  `hashEvent` varchar(8) DEFAULT NULL,
  `hashUsers` varchar(8) DEFAULT NULL,
  `topic` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `method` varchar(16) DEFAULT NULL,
  `allowComment` int(4) DEFAULT NULL,
  `allowUpload` int(4) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `origFileName` varchar(255) DEFAULT NULL,
  `storedFileName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`idEvent`, `hashEvent`, `hashUsers`, `topic`, `location`, `method`, `allowComment`, `allowUpload`, `description`, `origFileName`, `storedFileName`) VALUES
(41, '3416a75f', '1d88b7b7', 'week 8 meeting', 'zoom.com', 'virtual', 0, 0, '\n', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `idOptions` int(11) NOT NULL,
  `idEvent` int(11) DEFAULT NULL,
  `duration` varchar(16) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `totalSlots` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`idOptions`, `idEvent`, `duration`, `date`, `totalSlots`) VALUES
(196, 41, 'PT15M', '2023-03-07 16:00:00', 2),
(197, 41, 'PT15M', '2023-03-08 16:15:00', 1),
(198, 41, 'PT15M', '2023-03-09 16:30:00', 1),
(199, 41, 'PT15M', '2023-03-10 16:45:00', 0),
(200, 41, 'PT15M', '2023-03-11 17:00:00', 0),
(201, 41, 'PT15M', '2023-03-12 16:15:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `idReservations` int(11) NOT NULL,
  `idOptions` int(11) DEFAULT NULL,
  `hashUsers` varchar(8) DEFAULT NULL,
  `origFileName` varchar(255) DEFAULT NULL,
  `storedFileName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`idReservations`, `idOptions`, `hashUsers`, `origFileName`, `storedFileName`) VALUES
(26, 196, '5c0f323e', NULL, NULL),
(27, 197, '5c0f323e', NULL, NULL),
(28, 198, '5c0f323e', NULL, NULL),
(29, 196, '5c0f323e', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idUsers` int(11) NOT NULL,
  `hashUsers` varchar(8) DEFAULT NULL,
  `onid` varchar(16) DEFAULT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `timeZone` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idUsers`, `hashUsers`, `onid`, `lastName`, `firstName`, `timeZone`) VALUES
(20, '1d88b7b7', 'zhangxin2', 'Zhang', 'Xin', NULL),
(21, 'abs213', 'test', 'John', 'Doe', NULL),
(22, 'testId', 'test2', NULL, NULL, NULL),
(23, 'testId3', 'test3', NULL, NULL, NULL),
(24, 'testId4', 'test4', NULL, NULL, NULL),
(25, '5c0f323e', 'test_onid', 'Doe', 'John', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`idEvent`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`idOptions`),
  ADD KEY `idEvent` (`idEvent`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`idReservations`),
  ADD KEY `idOptions` (`idOptions`),
  ADD KEY `idUsers` (`hashUsers`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUsers`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `idEvent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `idOptions` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `idReservations` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idUsers` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `idEvent` FOREIGN KEY (`idEvent`) REFERENCES `event` (`idEvent`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `idOptions` FOREIGN KEY (`idOptions`) REFERENCES `options` (`idOptions`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

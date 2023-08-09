-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Aug 09, 2023 at 06:54 AM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vision_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `loginLog`
--

CREATE TABLE `loginLog` (
  `loginId` bigint(20) NOT NULL,
  `deviceName` varchar(50) NOT NULL,
  `deviceCookie` varchar(100) NOT NULL,
  `deviceOS` varchar(100) NOT NULL,
  `deviceBrowser` varchar(50) NOT NULL,
  `dateTimeLogin` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastActivity` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sessionStatus` enum('Active','Inactive') DEFAULT NULL,
  `userId` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` bigint(20) NOT NULL,
  `userFirstName` varchar(100) NOT NULL,
  `userLastName` varchar(100) NOT NULL,
  `userMailId` varchar(100) NOT NULL,
  `userPassword` varchar(100) NOT NULL,
  `userPermission` int(3) NOT NULL,
  `allowedSessions` int(3) NOT NULL,
  `userCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userFirstName`, `userLastName`, `userMailId`, `userPassword`, `userPermission`, `allowedSessions`, `userCreated`) VALUES
(1, 'Akash', 'Singh', 'akashsngh681681@gmail.com', '4280cc936cebdd304f81690df529922c', 3, 2, '2023-08-02 16:24:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `loginLog`
--
ALTER TABLE `loginLog`
  ADD PRIMARY KEY (`loginId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `loginLog`
--
ALTER TABLE `loginLog`
  MODIFY `loginId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2026 at 04:15 PM
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
-- Database: `whatsupdlsu`
--

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `EVENT_ID` int(11) NOT NULL,
  `USER_ID` int(8) NOT NULL,
  `CATEGORY` enum('ACADEMIC','NON-ACADEMIC','CAREER') NOT NULL,
  `TITLE` text NOT NULL,
  `DESCRIPTION` mediumtext NOT NULL,
  `LOCATION` varchar(100) NOT NULL,
  `VENUE` varchar(10) NOT NULL,
  `DATE` date NOT NULL,
  `START_TIME` time NOT NULL,
  `END_TIME` time NOT NULL,
  `APPROVAL_STATUS` enum('APPROVED','PENDING','REJECTED') NOT NULL,
  `REMARKS` varchar(500) DEFAULT NULL,
  `STATUS` enum('ONGOING','UPCOMING','ENDED') NOT NULL,
  `REGISTRATION_STATUS` tinyint(1) NOT NULL,
  `BANNER_IMAGE` varchar(255) NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`EVENT_ID`, `USER_ID`, `CATEGORY`, `TITLE`, `DESCRIPTION`, `LOCATION`, `VENUE`, `DATE`, `START_TIME`, `END_TIME`, `APPROVAL_STATUS`, `REMARKS`, `STATUS`, `REGISTRATION_STATUS`, `BANNER_IMAGE`, `CREATED_AT`, `UPDATED_AT`) VALUES
(1, 2, 'NON-ACADEMIC', 'Animusika 2026', 'Animusika descriptions...', 'Henry Sy Sr. Hall (HSSH)', '807', '2026-12-11', '08:00:00', '18:00:00', 'APPROVED', NULL, 'UPCOMING', 1, 'Example_Image.jpg', '2026-07-13 15:41:49', '2026-07-13 15:41:49'),
(2, 2, 'CAREER', 'Leadership Seminar', 'A seminar on leadership skills.', 'Yuchengco Hall (YUCH)', '204', '2026-12-14', '09:00:00', '12:00:00', 'APPROVED', '', 'UPCOMING', 1, '', '2026-07-13 16:43:48', '2026-07-13 16:43:48'),
(3, 2, 'CAREER', 'Research Expos', 'Showcase of student research projects.', 'Andrew Gonzalez Hall (AG)', 'Lobbyasdfa', '2026-12-18', '10:00:00', '16:00:00', 'REJECTED', 'Venue conflicts with another approved event on the same date. Please propose an alternate venue or date.', 'UPCOMING', 1, '', '2026-07-07 16:43:48', '2026-07-13 18:25:15'),
(4, 2, 'ACADEMIC', 'Hackathon', 'A 24-hour coding competition.', 'Gokongwei Hall (GOKONGWEI)', '501', '2026-12-20', '08:00:00', '20:00:00', 'APPROVED', NULL, 'UPCOMING', 1, '', '2026-07-09 16:43:48', '2026-07-10 16:43:48'),
(5, 2, 'NON-ACADEMIC', 'Sports Fest', 'Annual inter-org sports festival.', 'Enrique M. Razon Sports Center', 'Main Court', '2027-01-04', '07:00:00', '18:00:00', 'PENDING', NULL, 'UPCOMING', 1, '', '2026-07-12 16:43:48', '2026-07-12 16:43:48'),
(6, 2, 'CAREER', 'Career Fair 2027', 'Meet potential employers and recruiters.', 'Andrew Gonzalez Hall (AG)', 'Grounds', '2027-01-10', '09:00:00', '17:00:00', 'APPROVED', NULL, 'UPCOMING', 1, '', '2026-07-10 16:43:48', '2026-07-11 16:43:48'),
(7, 2, 'ACADEMIC', 'Study Jam', 'Group study session before finals.', 'LS Building (LS)', '301', '2026-12-13', '13:00:00', '17:00:00', 'APPROVED', NULL, 'ONGOING', 1, '', '2026-07-13 16:43:48', '2026-07-13 16:43:48'),
(8, 2, 'NON-ACADEMIC', 'Freshman Welcome Party', 'Welcome event for new students.', 'Mutien Marie Hall', 'Ground Flo', '2026-11-20', '18:00:00', '22:00:00', 'APPROVED', NULL, 'ENDED', 1, '', '2026-07-01 16:43:48', '2026-07-03 16:43:48'),
(12, 2, 'ACADEMIC', 'LAST OF THE LAST', 'aaaaaaaaaa', 'Br. Andrew Gonzalez FSC Sports Complex', 'AG1724', '2026-07-14', '23:11:00', '15:12:00', 'PENDING', NULL, 'ONGOING', 1, '1784038229_pfp.jpg', '2026-07-14 22:10:29', '2026-07-14 22:10:29');

-- --------------------------------------------------------

--
-- Table structure for table `event_interest`
--

CREATE TABLE `event_interest` (
  `INTEREST_ID` int(11) NOT NULL,
  `USER_ID` int(8) NOT NULL,
  `EVENT_ID` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `NOTIFICATION_ID` int(11) NOT NULL,
  `TITLE` varchar(50) NOT NULL,
  `MESSAGE` varchar(200) NOT NULL,
  `CREATED_AT` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `USER_ID` int(11) NOT NULL,
  `USER_NAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(50) NOT NULL,
  `ROLE` enum('ADMIN','USER','OFFICER') NOT NULL,
  `CREATED_AT` date NOT NULL,
  `STATUS` enum('ACTIVE','INACTIVE') NOT NULL,
  `PROFILE_PIC` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`USER_ID`, `USER_NAME`, `PASSWORD`, `ROLE`, `CREATED_AT`, `STATUS`, `PROFILE_PIC`) VALUES
(1, 'admin', 'admin@1234', 'ADMIN', '2026-07-10', 'ACTIVE', ''),
(2, 'officer', 'officer@1234', 'OFFICER', '2026-07-10', 'ACTIVE', NULL),
(3, 'user', 'user@1234', 'USER', '2026-07-10', 'ACTIVE', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`EVENT_ID`);

--
-- Indexes for table `event_interest`
--
ALTER TABLE `event_interest`
  ADD PRIMARY KEY (`INTEREST_ID`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`NOTIFICATION_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`USER_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `EVENT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

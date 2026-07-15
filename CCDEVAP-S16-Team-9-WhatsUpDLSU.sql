-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2026 at 06:09 PM
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
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `COMMENT_ID` int(11) NOT NULL,
  `USERNAME` varchar(50) DEFAULT NULL,
  `TEXT` varchar(200) DEFAULT NULL,
  `IS_ANONYMOUS` tinyint(1) DEFAULT NULL,
  `EVENT_ID` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`COMMENT_ID`, `USERNAME`, `TEXT`, `IS_ANONYMOUS`, `EVENT_ID`) VALUES
(1, 'maria.santos', '10/10!', 1, 20),
(2, 'maria.santos', 'Would recommend!', 0, 20),
(3, 'maria.santos', 'So excited for this!!', 1, 22),
(4, 'maria.santos', 'Where can I register for this?', 0, 22),
(5, 'juan.delacruz', 'So stoked for this!', 0, 24),
(6, 'juan.delacruz', 'This seems quite interesting.', 1, 24),
(7, 'juan.delacruz', 'I\'ve been waiting for this all year!', 0, 22);

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
(20, 5, 'CAREER', 'FOMO: Forging Opportunities, Mastering Operations\" and \"Transcend', 'Event which features industry professionals, business pitching competitions, and alumni panels discussing corporate social responsibility and career pathways.', 'Cory Aquino Democratic Space', 'CADS', '2026-07-18', '10:00:00', '13:00:00', 'APPROVED', '', 'UPCOMING', 1, '1784126863_FOMO.png', '2026-07-15 22:47:43', '2026-07-15 22:53:46'),
(21, 5, 'NON-ACADEMIC', 'ENGLICOM General Assembly', 'A highly anticipated, interactive event hosted by the official Filipino-Chinese socio-civic organization at De La Salle University (DLSU).', 'Gokongwei Hall (GOKONGWEI)', 'Pardo', '2026-07-25', '15:30:00', '18:00:00', 'APPROVED', '', 'UPCOMING', 1, '1784126966_ENG_GEN_ASSEM.jpg', '2026-07-15 22:49:26', '2026-07-15 22:53:45'),
(22, 4, 'NON-ACADEMIC', 'Technology Summit 2026', 'An event that explores innovations, shares insights, and builds meaningful connections that will continue to inspire students moving forward.', 'Gokongwei Hall (GOKONGWEI)', 'G404B', '2026-07-29', '08:00:00', '12:00:00', 'APPROVED', '', 'UPCOMING', 1, '1784127028_TECH_SUMM.jpg', '2026-07-15 22:50:28', '2026-07-15 22:53:43'),
(23, 4, 'NON-ACADEMIC', 'LSCS General Assembly', 'Mandatory general assembly for all LSCS members.', 'Yuchengco Hall (YUCH)', '204', '2026-07-30', '12:00:00', '15:30:00', 'APPROVED', '', 'UPCOMING', 1, '1784127162_LSCS_ASSEM.jpg', '2026-07-15 22:52:42', '2026-07-15 22:53:43'),
(24, 4, 'ACADEMIC', 'CodeFest 2026', 'A campus-wide coding competition for all skill levels.', 'Gokongwei Hall (GOKONGWEI)', '304B', '2026-07-31', '11:00:00', '14:00:00', 'APPROVED', '', 'UPCOMING', 1, '1784127211_CODE_FEST.jpg', '2026-07-15 22:53:31', '2026-07-15 22:53:42');

-- --------------------------------------------------------

--
-- Table structure for table `event_interest`
--

CREATE TABLE `event_interest` (
  `INTEREST_ID` int(11) NOT NULL,
  `USER_ID` int(8) NOT NULL,
  `EVENT_ID` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_interest`
--

INSERT INTO `event_interest` (`INTEREST_ID`, `USER_ID`, `EVENT_ID`) VALUES
(1, 6, 22),
(2, 6, 24),
(3, 7, 22),
(4, 7, 21),
(5, 7, 23);

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
(4, 'LSCS', 'lscs2026', 'OFFICER', '2026-07-15', 'ACTIVE', NULL),
(5, 'ENGLICOM', 'englicom2026', 'OFFICER', '2026-07-15', 'ACTIVE', NULL),
(6, 'juan.delacruz', 'juan2026', 'USER', '2026-07-15', 'ACTIVE', NULL),
(7, 'maria.santos', 'maria2026', 'USER', '2026-07-15', 'ACTIVE', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`COMMENT_ID`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`USER_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `COMMENT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `EVENT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

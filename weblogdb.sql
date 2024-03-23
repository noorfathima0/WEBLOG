-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2024 at 09:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `weblogdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `CommentID` int(11) NOT NULL,
  `EntryID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Comment` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`CommentID`, `EntryID`, `UserID`, `Comment`, `CreatedAt`) VALUES
(2, 1, 5, 'yes', '2024-03-19 15:35:05'),
(3, 11, 6, 'its good\r\n', '2024-03-22 18:58:22'),
(4, 11, 6, 'this', '2024-03-22 19:42:45');

-- --------------------------------------------------------

--
-- Table structure for table `journalentry`
--

CREATE TABLE `journalentry` (
  `EntryID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Title` varchar(255) NOT NULL,
  `Content` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Privacy` enum('Public','Private','SelectedUsers') DEFAULT 'Public',
  `TextFormatting` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `journalentry`
--

INSERT INTO `journalentry` (`EntryID`, `UserID`, `Title`, `Content`, `CreatedAt`, `UpdatedAt`, `Privacy`, `TextFormatting`) VALUES
(1, 5, 'title', 'there are many things to write!', '2024-03-17 14:08:57', '2024-03-17 18:10:36', 'Public', NULL),
(2, 5, 'name', 'offer things to strangers to make them smile', '2024-03-17 15:12:06', '2024-03-17 18:12:21', 'Public', NULL),
(10, 5, 'First Day', 'This is the day I brought by Eid dress!!!', '2024-03-22 17:42:21', '2024-03-22 17:42:21', 'Public', NULL),
(11, 6, 'first', 'this on is on first day', '2024-03-22 17:50:52', '2024-03-22 17:50:52', 'Public', NULL),
(12, 6, 'second', 'this is the second day', '2024-03-22 18:19:34', '2024-03-22 18:19:34', 'Public', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `LikeID` int(11) NOT NULL,
  `EntryID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`LikeID`, `EntryID`, `UserID`, `CreatedAt`) VALUES
(23, 1, 5, '2024-03-20 16:33:17'),
(24, 11, 6, '2024-03-22 18:11:02'),
(25, 11, 6, '2024-03-22 18:48:30'),
(26, 11, 6, '2024-03-22 18:53:09');

-- --------------------------------------------------------

--
-- Table structure for table `reactions`
--

CREATE TABLE `reactions` (
  `ReactionID` int(11) NOT NULL,
  `EntryID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ReactionType` enum('Like','Love','Haha','Wow','Sad','Angry') DEFAULT NULL,
  `ReactionDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reactions`
--

INSERT INTO `reactions` (`ReactionID`, `EntryID`, `UserID`, `ReactionType`, `ReactionDate`) VALUES
(1, 1, 5, 'Like', '2024-03-20 16:10:52'),
(16, 1, 5, 'Haha', '2024-03-20 16:31:29'),
(17, 1, 5, 'Wow', '2024-03-20 16:32:13'),
(18, 1, 5, 'Sad', '2024-03-20 16:32:14'),
(19, 1, 5, 'Angry', '2024-03-20 16:32:15'),
(20, 1, 5, 'Love', '2024-03-20 16:32:16'),
(21, 1, 5, 'Haha', '2024-03-20 16:32:17'),
(22, 10, 5, 'Love', '2024-03-22 17:42:32'),
(23, 11, 6, 'Wow', '2024-03-22 18:46:09'),
(24, 11, 6, 'Haha', '2024-03-22 18:57:15');

-- --------------------------------------------------------

--
-- Table structure for table `recommended_entries`
--

CREATE TABLE `recommended_entries` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shares`
--

CREATE TABLE `shares` (
  `ShareID` int(11) NOT NULL,
  `EntryID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `SharedWithUserID` int(11) DEFAULT NULL,
  `ShareDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shares`
--

INSERT INTO `shares` (`ShareID`, `EntryID`, `UserID`, `SharedWithUserID`, `ShareDate`) VALUES
(1, 1, 5, 6, '2024-03-20 15:57:51'),
(2, 1, 5, 6, '2024-03-20 15:59:04'),
(3, 1, 5, 6, '2024-03-20 15:59:10');

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `TagID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`TagID`, `Name`) VALUES
(1, '1'),
(2, '2'),
(3, '3'),
(4, 'Eid'),
(5, 'dress'),
(6, 'brought'),
(7, 'first'),
(8, 'entry'),
(9, 'day'),
(10, 'good'),
(11, 'first'),
(12, 'entry'),
(13, 'day'),
(14, 'good'),
(15, 'second'),
(16, 'day'),
(17, 'that'),
(18, 'is');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Bio` text DEFAULT NULL,
  `RedistrationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `LastLogin` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `Username`, `Email`, `Password`, `Bio`, `RedistrationDate`, `LastLogin`) VALUES
(5, 'noor', 'noorfathima847@gmail.com', '$2y$10$wLpYZTw4U6mnz.0g4bTnq.BuinnNFqgQEgP0LNZvA86wwUExaKSjm', 'this is my bio', '2024-03-17 12:19:53', NULL),
(6, 'begum', 'asma@gmail.com', '$2y$10$OM/R.E3ivNfas2E.g8ymD.9wojl6tj5LYcVjPZBNLe8RPfNs0A4.y', 'this one ', '2024-03-20 12:37:07', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `EntryID` (`EntryID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `journalentry`
--
ALTER TABLE `journalentry`
  ADD PRIMARY KEY (`EntryID`),
  ADD KEY `UserID` (`UserID`);
ALTER TABLE `journalentry` ADD FULLTEXT KEY `idx_fulltext_search` (`Title`,`Content`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`LikeID`),
  ADD KEY `EntryID` (`EntryID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `reactions`
--
ALTER TABLE `reactions`
  ADD PRIMARY KEY (`ReactionID`),
  ADD KEY `EntryID` (`EntryID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `recommended_entries`
--
ALTER TABLE `recommended_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `shares`
--
ALTER TABLE `shares`
  ADD PRIMARY KEY (`ShareID`),
  ADD KEY `EntryID` (`EntryID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `SharedWithUserID` (`SharedWithUserID`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`TagID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `journalentry`
--
ALTER TABLE `journalentry`
  MODIFY `EntryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `LikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `reactions`
--
ALTER TABLE `reactions`
  MODIFY `ReactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `recommended_entries`
--
ALTER TABLE `recommended_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shares`
--
ALTER TABLE `shares`
  MODIFY `ShareID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `TagID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`EntryID`) REFERENCES `journalentry` (`EntryID`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `journalentry`
--
ALTER TABLE `journalentry`
  ADD CONSTRAINT `journalentry_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`EntryID`) REFERENCES `journalentry` (`EntryID`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `reactions`
--
ALTER TABLE `reactions`
  ADD CONSTRAINT `reactions_ibfk_1` FOREIGN KEY (`EntryID`) REFERENCES `journalentry` (`EntryID`) ON DELETE CASCADE,
  ADD CONSTRAINT `reactions_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `recommended_entries`
--
ALTER TABLE `recommended_entries`
  ADD CONSTRAINT `recommended_entries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`userid`);

--
-- Constraints for table `shares`
--
ALTER TABLE `shares`
  ADD CONSTRAINT `shares_ibfk_1` FOREIGN KEY (`EntryID`) REFERENCES `journalentry` (`EntryID`) ON DELETE CASCADE,
  ADD CONSTRAINT `shares_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `shares_ibfk_3` FOREIGN KEY (`SharedWithUserID`) REFERENCES `user` (`userid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

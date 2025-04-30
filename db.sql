-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Apr 30, 2025 at 10:55 AM
-- Server version: 9.3.0
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `praksa-projekat-1`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `applicationId` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `userId` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jobId` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `submittedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`applicationId`, `userId`, `jobId`, `submittedAt`) VALUES
('25210088-18b9-47f3-8865-2af6ca7c04cc', 'debe185b-7cf5-4f85-9ad2-989382bd8fb0', '2ad67dc2-3f84-45de-a4f3-3f17fc70d79d', '2025-04-12 23:47:24'),
('f1112365-13b0-48fb-8ba9-d90c40663fb1', '16cba2b1-58a9-438f-aa67-92e8715be11d', '438c0d92-4efc-4048-8945-e58266610e2b', '2025-04-13 22:14:15');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int NOT NULL,
  `user_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `job_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `text` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `job_id`, `text`, `createdAt`) VALUES
(15, '16cba2b1-58a9-438f-aa67-92e8715be11d', 'b2c592be-db13-4a94-96a1-3bf4b05789a9', 'dobar oglas', '2025-04-21 14:31:51'),
(16, '16cba2b1-58a9-438f-aa67-92e8715be11d', '438c0d92-4efc-4048-8945-e58266610e2b', 'dobar komentar', '2025-04-21 14:35:46'),
(19, '16cba2b1-58a9-438f-aa67-92e8715be11d', 'b2c592be-db13-4a94-96a1-3bf4b05789a9', 'super oglas', '2025-04-28 10:19:41');

-- --------------------------------------------------------

--
-- Table structure for table `employers`
--

CREATE TABLE `employers` (
  `employerId` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `employerName` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `basedIn` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `employerDescription` varchar(200) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employers`
--

INSERT INTO `employers` (`employerId`, `employerName`, `basedIn`, `employerDescription`) VALUES
('asdf123', 'Laguna', 'Beograd', 'jako dobra knjizara Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem'),
('asjdlf1023', 'Google', 'Palo Alto', 'google Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has be');

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `favouriteId` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `userId` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jobId` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favourites`
--

INSERT INTO `favourites` (`favouriteId`, `userId`, `jobId`) VALUES
('b2b7ae70-320f-4535-abd3-aa7784587a45', '16cba2b1-58a9-438f-aa67-92e8715be11d', 'b2c592be-db13-4a94-96a1-3bf4b05789a9');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `jobId` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `employerId` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jobName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `field` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `startSalary` int NOT NULL,
  `shifts` int NOT NULL DEFAULT '1',
  `location` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `flexibleHours` tinyint(1) NOT NULL,
  `workFromHome` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`jobId`, `employerId`, `jobName`, `description`, `field`, `startSalary`, `shifts`, `location`, `createdAt`, `flexibleHours`, `workFromHome`) VALUES
('1d0b4fe2-f4bc-494f-a1c5-90746466fa06', 'asdf123', 'Social media menadzer', 'social media menadzer u laguni', 'menadzment', 100000, 1, 'Subotica', '2025-04-29 14:30:44', 1, 1),
('2ad67dc2-3f84-45de-a4f3-3f17fc70d79d', 'asjdlf1023', 'Software engineer', 'softver engineer u guglu', 'it', 200000, 1, 'Novi Sad', '2025-04-12 00:00:00', 1, 1),
('438c0d92-4efc-4048-8945-e58266610e2b', 'asdf123', 'Menadzer u knjizari', 'menadzer u laguni', 'menadzment', 210000, 1, 'Subotica', '2025-04-09 00:00:00', 1, 0),
('9dbb71c2-5fcf-4a49-a1c9-e68b921f055f', 'asjdlf1023', 'Backend web developer', 'backend web developer u guglu', 'it', 200000, 1, 'Beograd', '2025-04-09 00:00:00', 1, 1),
('b2c592be-db13-4a94-96a1-3bf4b05789a9', 'asdf123', 'Prodavac u knjizari', 'prodavac u laguni', 'prodaja', 150000, 2, 'Subotica', '2025-04-09 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `job_fields`
--

CREATE TABLE `job_fields` (
  `field_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_fields`
--

INSERT INTO `job_fields` (`field_id`, `name`) VALUES
(1, 'it'),
(2, 'menadzment'),
(3, 'prodaja');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`location_id`, `city`, `country`) VALUES
(1, 'Subotica', 'Serbia'),
(2, 'Novi Sad', 'Serbia'),
(3, 'Beograd', 'Serbia');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `firstName` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `lastName` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `field` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(15) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `firstName`, `lastName`, `password`, `field`, `email`, `role`) VALUES
('16cba2b1-58a9-438f-aa67-92e8715be11d', 'test', 'test', '$2y$10$DJgTOOiFF63GBggXMF9IBOlaezatMCDHe0d2Amg/Zq1NnynC.ihkC', 'prodaja', 'test@gmail.com', 'admin'),
('3e8a7bae-5f7b-45a9-a218-96ee45fcf696', 'new', 'user', '$2y$10$vLmd/qMLMLwREqbCUp66BevWvyoEXxg3s5yR1Pv/e.3ooBNAqcPOS', 'prodaja', 'new@gmail.com', 'user'),
('6330e79a-d56f-4b72-bbd4-a56a56b126b1', 'milos', 'user', '$2y$10$FNLYHNnI/yZ2rntVCEfX6OoZcWP7jYKNF3x0BVRuCsVPQbbEJAseO', 'it', 'milos2@gmail.com', 'user'),
('88d05f25-9b65-494f-95bc-3ba3ce63b434', 'milos', 'ja', '$2y$10$5VnXslejo82D4lJRx.cx6.tkBvh6f/n6MGU6n2IdKggfhTBakWBnu', 'prodaja', 'milos@gmail.com', 'user'),
('debe185b-7cf5-4f85-9ad2-989382bd8fb0', 'new', 'test', '$2y$10$ATIt1KFVFw/xLRhcDwpvZexG6G79iEsHfshEg5LMYliWXexYEkaeO', 'it', 'new.test@gmail.com', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`applicationId`),
  ADD KEY `fk_application_user` (`userId`),
  ADD KEY `fk_application_job` (`jobId`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `fk_comment_job` (`job_id`),
  ADD KEY `fk_comment_user` (`user_id`);

--
-- Indexes for table `employers`
--
ALTER TABLE `employers`
  ADD PRIMARY KEY (`employerId`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`favouriteId`),
  ADD KEY `fk_favourite_user` (`userId`),
  ADD KEY `fk_favourite_job` (`jobId`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`jobId`),
  ADD KEY `employerId` (`employerId`);

--
-- Indexes for table `job_fields`
--
ALTER TABLE `job_fields`
  ADD PRIMARY KEY (`field_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `job_fields`
--
ALTER TABLE `job_fields`
  MODIFY `field_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_application_job` FOREIGN KEY (`jobId`) REFERENCES `jobs` (`jobId`),
  ADD CONSTRAINT `fk_application_user` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comment_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`jobId`),
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`);

--
-- Constraints for table `favourites`
--
ALTER TABLE `favourites`
  ADD CONSTRAINT `fk_favourite_job` FOREIGN KEY (`jobId`) REFERENCES `jobs` (`jobId`),
  ADD CONSTRAINT `fk_favourite_user` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_job_employer` FOREIGN KEY (`employerId`) REFERENCES `employers` (`employerId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

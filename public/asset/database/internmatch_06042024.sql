-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2024 at 02:54 AM
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
-- Database: `internmatch`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `aboutme` varchar(1000) NOT NULL,
  `address` varchar(100) NOT NULL,
  `linkedin` varchar(100) NOT NULL,
  `verified` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`email`, `name`, `password`, `aboutme`, `address`, `linkedin`, `verified`) VALUES
('erwinwingyonata@gmail.com', 'Erwin Yonata', '$2y$10$9/uwMen10BlNP2F2/vmWvu2c2ck2N5B8X2Fm6axED2o63dPj8hWEK', 'Hello my name is erwin', 'Kalibata City', 'https://www.linkedin.com/in/erwinyonata/', 1),
('janesmith@example.com', 'Jane Smith', 'secretpassword', 'I love cats!', '456 Elm St.', 'janesmith123', 1),
('johndoe@example.com', 'John Doe', 'password123', 'I am a software developer.', '123 Main St.', 'johndoe', 0);

-- --------------------------------------------------------

--
-- Table structure for table `apply`
--

CREATE TABLE `apply` (
  `id` varchar(100) NOT NULL,
  `account` varchar(100) NOT NULL,
  `vacancy` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apply`
--

INSERT INTO `apply` (`id`, `account`, `vacancy`) VALUES
('6c407c0e-2336-483c-8fdd-dc1dea772c1a', 'janesmith@example.com', '84b1e4cf-c64c-4445-84b8-525a6ac90352'),
('de855e3b-7fcc-4c39-ae1d-813c9ac0e6b9', 'johndoe@example.com', 'a179cee7-bf1d-49d8-87da-e6f1a1bf5ad7');

-- --------------------------------------------------------

--
-- Table structure for table `vacancy`
--

CREATE TABLE `vacancy` (
  `id` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `status` varchar(100) NOT NULL,
  `account` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vacancy`
--

INSERT INTO `vacancy` (`id`, `title`, `description`, `status`, `account`) VALUES
('84b1e4cf-c64c-4445-84b8-525a6ac90352', 'New Job Title', 'New Job Description', 'active', 'johndoe@example.com'),
('a179cee7-bf1d-49d8-87da-e6f1a1bf5ad7', 'Job Title 1', 'Job Description 1', 'Open', 'janesmith@example.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `apply`
--
ALTER TABLE `apply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`),
  ADD KEY `vacancy` (`vacancy`);

--
-- Indexes for table `vacancy`
--
ALTER TABLE `vacancy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`account`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `apply`
--
ALTER TABLE `apply`
  ADD CONSTRAINT `apply_ibfk_1` FOREIGN KEY (`account`) REFERENCES `account` (`email`),
  ADD CONSTRAINT `apply_ibfk_2` FOREIGN KEY (`vacancy`) REFERENCES `vacancy` (`id`);

--
-- Constraints for table `vacancy`
--
ALTER TABLE `vacancy`
  ADD CONSTRAINT `vacancy_ibfk_1` FOREIGN KEY (`account`) REFERENCES `account` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

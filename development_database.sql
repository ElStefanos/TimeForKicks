-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 29, 2023 at 07:10 PM
-- Server version: 10.6.12-MariaDB-1:10.6.12+maria~deb11
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timeforkicks2_development`
--

-- --------------------------------------------------------

--
-- Table structure for table `kicks_admin`
--

CREATE TABLE `kicks_admin` (
  `id` int(255) NOT NULL,
  `active` int(1) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kicks_filters`
--

CREATE TABLE `kicks_filters` (
  `id` int(255) NOT NULL,
  `index_id` int(255) DEFAULT NULL,
  `is_global` int(1) DEFAULT NULL,
  `filter` varchar(255) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kicks_found_indexes`
--

CREATE TABLE `kicks_found_indexes` (
  `id` int(255) NOT NULL,
  `filter_id` int(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kicks_indexes`
--

CREATE TABLE `kicks_indexes` (
  `id` int(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `start_url` varchar(255) DEFAULT NULL,
  `number_of_links` int(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `active` int(1) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `parse_time` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kicks_narrowing`
--

CREATE TABLE `kicks_narrowing` (
  `id` int(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `minimum_match` int(255) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kicks_networking`
--

CREATE TABLE `kicks_networking` (
  `id` int(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `port` int(255) NOT NULL,
  `username_password` varchar(255) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kicks_networking_daily_stats`
--

CREATE TABLE `kicks_networking_daily_stats` (
  `id` int(255) NOT NULL,
  `requests` int(255) NOT NULL,
  `bandwith` float NOT NULL,
  `successes` int(255) NOT NULL,
  `errors` int(255) NOT NULL,
  `date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kicks_networking_stats`
--

CREATE TABLE `kicks_networking_stats` (
  `id` int(255) NOT NULL,
  `url` text NOT NULL,
  `response_code` varchar(255) NOT NULL,
  `response_time` float NOT NULL,
  `size` varchar(255) NOT NULL,
  `index_id` int(255) DEFAULT NULL,
  `date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kicks_notifications`
--

CREATE TABLE `kicks_notifications` (
  `id` int(255) NOT NULL,
  `status` int(1) NOT NULL,
  `title` varchar(32) NOT NULL,
  `content` text NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kicks_stocks`
--

CREATE TABLE `kicks_stocks` (
  `id` int(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `sizes` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kick_webhooks`
--

CREATE TABLE `kick_webhooks` (
  `id` int(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `site` varchar(255) DEFAULT 'global',
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kicks_admin`
--
ALTER TABLE `kicks_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kicks_filters`
--
ALTER TABLE `kicks_filters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kicks_found_indexes`
--
ALTER TABLE `kicks_found_indexes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kicks_indexes`
--
ALTER TABLE `kicks_indexes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kicks_narrowing`
--
ALTER TABLE `kicks_narrowing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kicks_networking`
--
ALTER TABLE `kicks_networking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kicks_networking_daily_stats`
--
ALTER TABLE `kicks_networking_daily_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kicks_networking_stats`
--
ALTER TABLE `kicks_networking_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kicks_notifications`
--
ALTER TABLE `kicks_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kicks_stocks`
--
ALTER TABLE `kicks_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kick_webhooks`
--
ALTER TABLE `kick_webhooks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kicks_admin`
--
ALTER TABLE `kicks_admin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kicks_filters`
--
ALTER TABLE `kicks_filters`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kicks_found_indexes`
--
ALTER TABLE `kicks_found_indexes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kicks_indexes`
--
ALTER TABLE `kicks_indexes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kicks_narrowing`
--
ALTER TABLE `kicks_narrowing`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kicks_networking`
--
ALTER TABLE `kicks_networking`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kicks_networking_daily_stats`
--
ALTER TABLE `kicks_networking_daily_stats`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kicks_networking_stats`
--
ALTER TABLE `kicks_networking_stats`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kicks_notifications`
--
ALTER TABLE `kicks_notifications`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kicks_stocks`
--
ALTER TABLE `kicks_stocks`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kick_webhooks`
--
ALTER TABLE `kick_webhooks`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
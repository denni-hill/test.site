-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 14, 2020 at 06:25 PM
-- Server version: 10.3.22-MariaDB-54+deb10u1
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dennihill`
--

-- --------------------------------------------------------

--
-- Table structure for table `table_rows`
--

CREATE TABLE `table_rows` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `name` text NOT NULL,
  `count` int(11) NOT NULL,
  `distance_meters` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `table_rows`
--

INSERT INTO `table_rows` (`id`, `date`, `name`, `count`, `distance_meters`) VALUES
(1, '2020-01-01', 'Las Vegas', 34, 140),
(2, '2020-02-15', 'San Francisco', 12, 240),
(3, '2020-05-24', 'Los Angeles', 8, 153),
(4, '2020-06-12', 'New Jersey', 4, 120);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `username` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `task_content` text COLLATE utf8_unicode_ci NOT NULL,
  `is_edited` int(1) NOT NULL DEFAULT 0,
  `is_completed` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `username`, `email`, `task_content`, `is_edited`, `is_completed`) VALUES
(36, 'Denis', 'denissaenkoo@gmail.com', 'Send report to summer practise supervisor', 0, 0),
(35, 'Denis', 'denissaenkoo@gmail.com', 'Write a report for university about this work', 0, 1),
(34, 'Denis', 'denissaenkoo@gmail.com', 'Create MVC framework based todo application', 0, 1),
(33, 'Denis', 'denissaenkoo@gmail.com', 'Create MVC framework', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` text COLLATE utf8_unicode_ci NOT NULL,
  `is_admin` int(1) NOT NULL DEFAULT 0,
  `hash` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `is_admin`, `hash`) VALUES
(1, 'admin', 1, '7c24b97f700c18f3298ba7ff1729f0eec3dec8058e194d9c0cdbc1025d393d61');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `table_rows`
--
ALTER TABLE `table_rows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `table_rows`
--
ALTER TABLE `table_rows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

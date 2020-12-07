-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 06, 2020 at 09:44 PM
-- Server version: 8.0.18
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `entmgr`
--

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `mac_address` bigint(11) DEFAULT NULL,
  `points_quota` int(11) NOT NULL,
  `period` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sshport` int(4) DEFAULT NULL,
  `sshuser` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sshpass` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createssh` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`id`, `user_id`, `mac_address`, `points_quota`, `period`, `status`, `sshport`, `sshuser`, `sshpass`, `createssh`) VALUES
(3, '1234', 0, 200, '2020-03-11', 1, 1234, '1234001', '1234001111', 1),
(4, '12345', 0, 800, '2020-03-03', 1, 0, '12345001', '12345001111', 1),
(6, '123456', NULL, 1200, '2020-12-31', 1, 123456, '123456001', '123456001111', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `password`) VALUES
('1234', '$2y$10$5WXz.0701Dh.qVkYDHApAOyVJFbYyDoUJq86.KwgZrL4Mkf72KuQu'),
('12345', '$2y$10$oVmW6OKqNKjFLA.7TpKnj.wpgAR/1DFoj8JexNjeRvXyqqtSgm24S'),
('123456', '$2y$10$25Tvjk6Ga2WlFq7C0uZ.9OvQMJIv/winmapArhMQI72DbYA.md4Um'),
('saadmin', '$2y$10$g/YH/ZxkU331vmwmVJtSse2uja./Ti1evEESzLaum8X0FvCc6tSRi'),
('sadmin', '$2y$10$NwVO/dbacSXeh6O0aeThZODb8SlCwxAAXGvfUZfL0Ah8xWJ9aeqDS'),
('twenty', '$2y$10$JlsSs4tn5AFEkvTzpOtWh.Ur45My6OmoAlGVaB/mTQ2GoLJOL5fS.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `device`
--
ALTER TABLE `device`
  ADD CONSTRAINT `user id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

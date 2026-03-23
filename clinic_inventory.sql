-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2026 at 05:34 AM
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
-- Database: `clinic_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `item_name`, `qty`, `created_at`) VALUES
(9, 'aaaaa', 1107, '2026-03-17 13:02:49'),
(10, 'paracetamol', 12, '2026-03-19 04:18:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` text NOT NULL,
  `email_index` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `email_index`, `password`, `role`) VALUES
(1, 'i6oVycKgr+NalsoEpX6S4b95I0R+Zikf6AuPypSMkEI=', 'aa1bd863a92b50b8d3d51821bddcdc8e9d2c22664971311f5aaa6dcda1931331', '$2y$10$GEfqOQ3HgJGKJqCxm6LuneGQdEs5elXm3ISiL/D/a.KZRY1Rxoho2', 'tugigxsFUmklt/tjNFs+IwmnBDmhOc+VbAcp+nBMnsk='),
(2, 'VNGuIwKo9XuGywM4LYvegwRwU9Hic7lblN+qnP9brbgbi8sKUB/Q63mhxYmx63V7', 'fd5d10d6953350462c8a87234558d952d41f2531fb105c20a1b5db3a11ddef7a', '$2y$10$cBzIL4GBOC/8XBNq.XSyF.4Rrv1vKey4EBUebJ4Kv8tQDIyzzWggC', 'O6v0JYKaaHvuviisOYi4kP2W8LH/6APRQX/+UnAItr4='),
(3, 'PTPkm5SrL0R4NKTE0U6Gj5/2zRLX6HEvUscCeog0Fsk=', 'd2fa928b3545b5b0a10e630db3772d6b7f2dee7e420b4e1989999dd9f74f2d2f', '$2y$10$Cng1x4Wmj5hh3CFjEubgrur1aDWMuJ0MQwsYHGobIBOb3ZQ6/ZKL.', 'zbStTM6ASFffyqR4r3J1QuHKFP5j/kiCHRJSTuQDUv8='),
(4, 'bSF5E49Baz53ogzpySOwXMNJ+iAwwPINLrX26sLwFR4=', '30cba6d6269099815f6034d34470d7818b7daa82d7c2e1366933a62491a0782f', '$2y$10$ohjtaCMNx8uxQpIxc8dRH.nw.V1L7YXUQiKRfIINX3Dt3L2OF/B86', 'rEiZkZOxk9tTTXlMLSWWo6hbXb1D6RNcXY/ct4CVV3E='),
(5, 'tO4A/iZHdxPLivJZlAW2nyUVAY8HJcakijMDg0qHKCjgll2zrwxP5zAU7QhjJGco', '0fafe982c0a27363786a6e575a4984869e85ab467a8e379f5a8c1975acb98acf', '$2y$10$7l4DM7KDzE/WEf6kX48gXuI3xFOg5LIjR3pHW8HRXMUVejOAShFtq', 'k1fSQl+Vgbw1CA0lSLspIR/iXQ7UIIT6Q9GJyuywgRk=');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_email_blind` (`email_index`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

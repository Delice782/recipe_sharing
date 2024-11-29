-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2024 at 07:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

USE webtech_fall2024_delice_ishimwe;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `recipe_sharing`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `food_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `food_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `food_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `origin` varchar(100) DEFAULT NULL,
  `type` enum('breakfast','lunch','dinner','snack','dessert') NOT NULL,
  `is_healthy` tinyint(1) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `preparation_time` int(11) DEFAULT NULL,
  `cooking_time` int(11) DEFAULT NULL,
  `serving_size` int(11) DEFAULT NULL,
  `calories_per_serving` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`food_id`, `name`, `origin`, `type`, `is_healthy`, `instructions`, `description`, `preparation_time`, `cooking_time`, `serving_size`, `calories_per_serving`, `image_url`, `created_by`, `is_approved`, `created_at`) VALUES
(24, 'Ugali', 'Rw', 'lunch', 1, 'jhgvhj', 'ohgvkkjb', 15, 15, 3, 30, 'https://images.pexels.com/photos/633627/pexels-photo-633627.jpeg?auto=compress&cs=tinysrgb&w=600', NULL, 0, '2024-11-16 12:43:14'),
(27, 'Kitoke', 'Rw', 'lunch', 1, 'poiuytfdcghjiopkjhbv', 'oiuygfcvhjioiuhgfc', 12, 23, 10, 10, ';jhgfdsxfghiop[piuhg', NULL, 0, '2024-11-16 12:54:24'),
(41, 'Waakye', 'African', 'dinner', 1, 'oiuytrdfghjkl', 'poiuytdfghjk', 3, 3, 3, 3, 'https://images.pexels.com/photos/29414983/pexels-photo-29414983/free-photo-of-delicious-rice-with-meat-dish-and-vegetables.jpeg?auto=compress&cs=tinysrgb&w=', NULL, 0, '2024-11-16 20:18:53'),
(42, 'Fried Rice', 'African', 'dessert', 1, 'oiufdfghjk', 'oufghjkllkjh', 3, 3, 3, 3, 'https://images.pexels.com/photos/29414983/pexels-photo-29414983/free-photo-of-delicious-rice-with-meat-dish-and-vegetables.jpeg?auto=compress&cs=tinys', NULL, 0, '2024-11-16 20:19:20'),
(46, 'Kokoroko', 'African', 'breakfast', 1, 'lkhgfdcghjk', 'ouytfdfgjkljhgv', 4, 4, 4, 4, 'https://images.pexels.com/photos/29414983/pexels-photo-29414983/free-photo-of-delicious-rice-with-meat-dish-and-vegetables.jpeg?auto=compress&cs=tiny', NULL, 0, '2024-11-16 20:22:51'),
(47, 'Indomie', 'African', 'dinner', 1, 'iufdxcvhjkl', 'gfdxchjklkjhv', 5, 5, 5, 5, 'https://images.pexels.com/photos/29414983/pexels-photo-29414983/free-photo-of-delicious-rice-with-meat-dish-and-vegetables.jpeg?auto=compress&cs=tiny', NULL, 0, '2024-11-16 20:23:16'),
(48, 'Cassava', 'Rw', 'dinner', 1, 'eat when hot', 'cooked for approximate 30mins', 45, 30, 10, 20, 'https://images.pexels.com/photos/27397342/pexels-photo-27397342/free-photo-of-paraguayan-vori-vori-soup.jpeg?auto=compress&cs=tinysrgb&w=600', 56, 0, '2024-11-25 16:19:37'),
(49, 'Cassava', 'Rw', 'dinner', 1, 'eat when hot', 'cooked for approximate 30mins', 45, 30, 10, 20, 'https://images.pexels.com/photos/27397342/pexels-photo-27397342/free-photo-of-paraguayan-vori-vori-soup.jpeg?auto=compress&cs=tinysrgb&w=600', 56, 0, '2024-11-25 16:19:42');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `origin` varchar(100) DEFAULT NULL,
  `nutritional_value` text DEFAULT NULL,
  `allergen_info` varchar(255) DEFAULT NULL,
  `shelf_life` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nutritionfacts`
--

CREATE TABLE `nutritionfacts` (
  `nutrition_id` int(11) NOT NULL,
  `food_id` int(11) DEFAULT NULL,
  `protein` decimal(5,2) DEFAULT NULL,
  `carbohydrates` decimal(5,2) DEFAULT NULL,
  `fat` decimal(5,2) DEFAULT NULL,
  `fiber` decimal(5,2) DEFAULT NULL,
  `sugar` decimal(5,2) DEFAULT NULL,
  `sodium` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `recipe_id` int(11) NOT NULL,
  `food_id` int(11) DEFAULT NULL,
  `ingredient_id` int(11) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `optional` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(4) DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fname`, `lname`, `email`, `password`, `role`, `created_at`, `updated_at`, `approval_status`) VALUES
(56, 'Delice', 'Ishimwe', 'delice@gmail.com', '$2y$10$3qTWb3LxanHNcI4F.lMJX.CSHSt6C2zU5HthzhvSMAiImo2NH7TuO', 1, '2024-11-19 21:04:12', '2024-11-19 21:04:12', 'approved'),
(57, 'Dahlia', 'Igiraneza', 'dahlia@gmail.com', '$2y$10$DvfGQwHwZDWDEhDPZzSpeeuEl35mjPcBCJl76qM6U6LtisNFB.R.a', 1, '2024-11-19 21:15:49', '2024-11-19 21:15:49', 'approved'),
(58, 'Betty', 'Beni', 'betty@gmail.com', '$2y$10$Y8RjY/VFJTR0v3kiZnlIIONc30gx0xXv6zi8yR6m6MOAyW4hWqjHK', 2, '2024-11-19 21:17:24', '2024-11-19 21:17:24', 'pending'),
(70, 'John', 'Terence', 'john@gmail.com', '$2y$10$mX/ehapVOWZoPKJXA2IYj.QE09CXJGl/oXQlP4LLcF.DcuUrh0NTW', 1, '2024-11-27 02:17:37', '2024-11-27 02:17:37', 'approved'),
(71, 'Cloe', 'Nuy', 'cloe@gmail.com', '$2y$10$tYiE2KO4ywf/kNmsmeB48eljeafaXT7faO3lZ.sLP.KGQq8kUhgke', 2, '2024-11-27 02:35:48', '2024-11-27 02:35:48', 'pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `food_id` (`food_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`food_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`);

--
-- Indexes for table `nutritionfacts`
--
ALTER TABLE `nutritionfacts`
  ADD PRIMARY KEY (`nutrition_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipe_id`),
  ADD KEY `food_id` (`food_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `food_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nutritionfacts`
--
ALTER TABLE `nutritionfacts`
  MODIFY `nutrition_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`food_id`) REFERENCES `foods` (`food_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `foods` (`food_id`) ON DELETE CASCADE;

--
-- Constraints for table `foods`
--
ALTER TABLE `foods`
  ADD CONSTRAINT `foods_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `nutritionfacts`
--
ALTER TABLE `nutritionfacts`
  ADD CONSTRAINT `nutritionfacts_ibfk_1` FOREIGN KEY (`food_id`) REFERENCES `foods` (`food_id`) ON DELETE CASCADE;

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`food_id`) REFERENCES `foods` (`food_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipes_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

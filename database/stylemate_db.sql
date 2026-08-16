-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2026 at 09:13 PM
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
-- Database: `stylemate_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(8, 'Accessories'),
(5, 'Dresses'),
(6, 'Jackets'),
(2, 'Jeans'),
(3, 'Pants'),
(7, 'Shoes'),
(4, 'Skirts'),
(1, 'Tops');

-- --------------------------------------------------------

--
-- Table structure for table `clothing_items`
--

CREATE TABLE `clothing_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `image` varchar(255) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `secondary_color` varchar(50) DEFAULT NULL,
  `pattern` varchar(50) DEFAULT NULL,
  `style` varchar(50) DEFAULT NULL,
  `season` varchar(50) DEFAULT NULL,
  `occasion` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `outfit_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outfits`
--

CREATE TABLE `outfits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `outfit_name` varchar(150) NOT NULL,
  `occasion` varchar(100) DEFAULT NULL,
  `season` varchar(50) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT 0.00,
  `is_favorite` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outfit_items`
--

CREATE TABLE `outfit_items` (
  `id` int(11) NOT NULL,
  `outfit_id` int(11) NOT NULL,
  `clothing_item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `style_preferences`
--

CREATE TABLE `style_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `preferred_style` varchar(100) DEFAULT NULL,
  `preferred_colors` varchar(255) DEFAULT NULL,
  `preferred_occasions` varchar(255) DEFAULT NULL,
  `preferred_seasons` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `clothing_items`
--
ALTER TABLE `clothing_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favorite` (`user_id`,`outfit_id`),
  ADD KEY `outfit_id` (`outfit_id`);

--
-- Indexes for table `outfits`
--
ALTER TABLE `outfits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `outfit_items`
--
ALTER TABLE `outfit_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `outfit_id` (`outfit_id`),
  ADD KEY `clothing_item_id` (`clothing_item_id`);

--
-- Indexes for table `style_preferences`
--
ALTER TABLE `style_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clothing_items`
--
ALTER TABLE `clothing_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outfits`
--
ALTER TABLE `outfits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outfit_items`
--
ALTER TABLE `outfit_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `style_preferences`
--
ALTER TABLE `style_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clothing_items`
--
ALTER TABLE `clothing_items`
  ADD CONSTRAINT `clothing_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `clothing_items_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`outfit_id`) REFERENCES `outfits` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `outfits`
--
ALTER TABLE `outfits`
  ADD CONSTRAINT `outfits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `outfit_items`
--
ALTER TABLE `outfit_items`
  ADD CONSTRAINT `outfit_items_ibfk_1` FOREIGN KEY (`outfit_id`) REFERENCES `outfits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `outfit_items_ibfk_2` FOREIGN KEY (`clothing_item_id`) REFERENCES `clothing_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `style_preferences`
--
ALTER TABLE `style_preferences`
  ADD CONSTRAINT `style_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

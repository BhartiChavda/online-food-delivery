-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2026 at 04:21 PM
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
-- Database: `online_food_delivery`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`username`, `password`, `email`) VALUES
('admin', '$2y$10$uff6K.odb63rwRSWA5E1AOfdo8gfmeVBj04Q6KxScS2Q81cKVWN9q', 'zippy@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `food_name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boys`
--

CREATE TABLE `delivery_boys` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_boys`
--

INSERT INTO `delivery_boys` (`id`, `name`, `email`, `password`, `mobile`, `status`, `created_at`) VALUES
(1, 'delivery-boy1', 'boy1@gmail.com', '$2y$10$5y1d4O652fJ6L5U/2FZYF.WYHjqQoOdKgb59pkSA/W3JwOVImhvcq', '0987667890', 'active', '2025-09-06 03:43:44'),
(3, 'sky', 'skyjones@gmail.com', '$2y$10$60He6Iv5UXP2U.vsKWStueHKcicPH32m0nET/iRdUxrTsqK1fHjsG', '1234554321', 'active', '2025-09-06 03:46:36'),
(4, 'smith', 'smith@gmail.com', '$2y$10$Ve5GuWfZHw5k50h9XFxOrejuQDBho0/0Pw9dshV/pHvO3CeCXsxva', '9876544567', 'active', '2025-09-06 10:20:36');

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `category` enum('gallery','popular','speciality') NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `name`, `image_path`, `category`, `description`, `price`) VALUES
(1, 'Aloo Paneer Tikki', 'images/gallery/2.jpg', 'gallery', 'Tasty aloo paneer tikki', 80.00),
(2, 'Aloo Gobi', 'images/gallery/9alugobi.jpg', 'gallery', 'Delicious aloo gobi curry', 90.00),
(3, 'Bhel Puri Chat', 'images/gallery/13bhelpurichat.jpg', 'gallery', 'Crunchy bhel puri chat', 70.00),
(4, 'Biriyani', 'images/gallery/12.jpg', 'gallery', 'Spicy and flavorful biriyani', 120.00),
(5, 'Paratha', 'images/gallery/3.jpg', 'gallery', 'Stuffed paratha delight', 60.00),
(6, 'Aloo Tikki Chat', 'images/gallery/8.jpg', 'gallery', 'Crispy aloo tikki chat', 75.00),
(7, 'Salad Sandwich', 'images/gallery/11.jpg', 'gallery', 'Healthy salad sandwich', 50.00),
(8, 'Pizza', 'images/gallery/14.jpg', 'gallery', 'Cheesy pizza slice', 150.00),
(9, 'Burger', 'images/gallery/15.jpg', 'gallery', 'Yummy veggie burger', 130.00),
(10, 'Barafi', 'images/gallery/18.jpg', 'gallery', 'Sweet white barfi', 60.00),
(11, 'Kaju Katari', 'images/gallery/22.jpg', 'gallery', 'Premium kaju sweet', 100.00),
(12, 'Chocoballs', 'images/gallery/24.jpg', 'gallery', 'Chocolaty balls', 40.00),
(13, 'Tasty Dosa', 'images/popular/s-11-dosa.png', 'popular', 'Crispy and tasty dosa served with chutney', 99.00),
(14, 'Tasty Sandwich', 'images/popular/s-6-bread.jpg', 'popular', 'Fresh bread sandwich with veggies and sauces', 79.00),
(15, 'Pizza', 'images/popular/s-9-pizza.jpg', 'popular', 'Cheesy pizza with fresh toppings', 149.00),
(16, 'Punjabi Food', 'images/popular/s-8-panjabi.jpg', 'popular', 'Authentic Punjabi thali with spicy curry', 179.00),
(17, 'Thepla Gujarati', 'images/popular/s-1-thepla.jpg', 'popular', 'Traditional Gujarati thepla served hot', 89.00),
(18, 'Dal Tadaka', 'images/popular/s-10-daltadaka.jpg', 'popular', 'Flavorful tadka dal with jeera rice', 99.00),
(19, 'Paneer Tikka', 'images/popular/s-13-panirtikka.jpg', 'popular', 'Smoky grilled paneer cubes with spices', 129.00),
(20, 'Pauva', 'images/popular/s-2-pauva.jpg', 'popular', 'Poha with peanuts and mustard seeds', 59.00),
(21, 'Samosa', 'images/popular/s-5-samosa.jpg', 'popular', 'Crispy samosa filled with spicy potato mash', 25.00),
(22, 'Idali Sambhar', 'images/popular/s-3-idali.jpg', 'popular', 'Soft idli served with hot sambhar', 69.00),
(23, 'Naan', 'images/popular/s-7-naan.jpg', 'popular', 'Soft butter naan for Indian curries', 40.00),
(24, 'Dhokla', 'images/popular/s-12-dhokla.jpg', 'popular', 'Steamed spongy Gujarati dhokla', 55.00),
(38, 'Masala Meggie', 'images/popular/1753516677_Masala-Maggie.jpg', 'popular', 'Maggi masala a brand of noodles with a packet of dried spices for flavouring very popular in India', 25.00),
(39, 'dal-pakwan', 'images/popular/1753516933_Dal-Pakwan.jpg', 'popular', 'Dal Pakwan is a famous Sindhi dish, originating from the Sindh region, now primarily in Pakistan.', 30.00),
(41, 'tasty burger', 'images/Speciality/s-3.jpg', 'speciality', 'A burger is a sandwich consisting of one or more cooked patties, typically ground meat, placed inside a sliced bun.', NULL),
(42, 'tasty pizza', 'images/Speciality/s-4.jpg', 'speciality', 'Pizza, a globally beloved dish, originated in Naples, Italy, as a simple flatbread topped with various ingredients.', NULL),
(43, 'manchurian dish', 'images/Speciality/s-2.jpg', 'speciality', 'Pizza, a globally beloved dish, originated in Naples, Italy, as a simple flatbread topped with various ingredients.', NULL),
(44, 'Chinese bhel', 'images/Speciality/s-1.jpg', 'speciality', 'Chinese bhel is a popular Indo-Chinese street food dish, known for its crispy fried noodles tossed with colorful vegetables and a spicy, tangy sauce', NULL),
(45, 'Tasty uttapam', 'images/Speciality/s-5.jpg', 'speciality', 'An uttapam, uthapam, utapam or uttappam is a type of dosa from South India,Unlike a typical dosa,  uttapam is thicker, with toppings.', NULL),
(46, 'Tasty Naan', 'images/Speciality/s-6.jpg', 'speciality', 'Naan is a popular, leavened flatbread, traditionally baked in a tandoor (clay oven), but also cooked on a tava or oven.', NULL),
(47, 'tasty Rasgulla', 'images/Speciality/s-7.jpg', 'speciality', 'Rasgulla is a popular Indian dessert, especially loved in West Bengal and Odisha.', NULL),
(48, 'tasty Rasmalai', 'images/Speciality/s-6rasmalia.jpg', 'speciality', 'Rasmalai is a popular Indian dessert known for its soft, thickened milk flavored with cardamom and saffron.', NULL),
(49, 'Tasty Mawa Peda', 'images/Speciality/s-9.jpg', 'speciality', 'Mawa Peda, also known as Khoya Peda, is a popular Indian sweet made from milk solids, sugar, and cardamom.', NULL),
(81, 'Manchurian', 'images/popular/1756693655_s-2.jpg', 'popular', 'Delicious food', 150.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` int(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `order_details` text DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `payment_status` varchar(20) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_confirmed` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `confirm_time` datetime DEFAULT NULL,
  `user_confirm` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `order_time` datetime DEFAULT current_timestamp(),
  `delivery_boy_id` int(11) DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `estimated_time` int(11) DEFAULT NULL COMMENT 'time in minutes',
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `fullname`, `email`, `mobile`, `address`, `order_details`, `total_amount`, `payment_status`, `order_date`, `user_confirmed`, `confirm_time`, `user_confirm`, `order_time`, `delivery_boy_id`, `completed_at`, `estimated_time`, `status`) VALUES
(66, 17, 'vidhya', 'faldu@gmail.com', 987890987, 'lodhavad chock gondal road rajkot', 'Kaju Katari (x1) - ₹100\r\nChocoballs (x1) - ₹40\r\nPizza (x1) - ₹150', 290, 'COD - Pending', '2025-09-06 05:05:40', 'pending', '2025-09-07 12:13:41', 'pending', '2025-09-06 10:35:40', 1, NULL, 55, 'completed'),
(71, 17, 'v', 'faldu@gmail.com', 2147483647, 'kotecha chok rajkot', 'Tasty Paratha (x1) - ₹5\r\nTasty Aloo Gobi (x1) - ₹5', 10, 'COD - Pending', '2025-09-06 10:56:14', 'pending', '2025-09-07 12:13:31', 'pending', '2025-09-06 16:26:14', 4, NULL, 40, 'completed'),
(77, 17, 'vidya', 'faldu@gmail.com', 1234554321, 'vapi', '0', 75, 'COD - Pending', '2025-09-07 04:47:19', 'pending', '2025-09-07 10:23:30', 'pending', '2025-09-07 10:17:19', 3, NULL, 60, 'completed'),
(78, 17, 'vidya', 'faldu@gmail.com', 1234554321, 'vapi', '0', 75, 'COD - Pending', '2025-09-07 04:49:20', 'pending', '2025-09-07 11:49:05', 'pending', '2025-09-07 10:19:20', 1, NULL, 16, 'completed'),
(79, 18, 'bharti', 'chavda@gmail.com', 2147483647, 'rajkot', 'Biriyani (x5) - ₹600\r\nSalad Sandwich (x1) - ₹50', 650, 'COD - Pending', '2025-09-07 13:30:32', 'pending', '2025-09-11 13:14:09', 'pending', '2025-09-07 19:00:32', 3, NULL, 1, 'completed'),
(80, 18, 'bharti', 'chavda@gmail.com', 2147483647, 'rajkot', 'Kaju Katari (x1) - ₹100\r\nPizza (x1) - ₹150\r\nBurger (x1) - ₹130', 380, 'COD - Pending', '2025-09-07 13:32:02', 'pending', '2025-09-07 19:22:31', 'pending', '2025-09-07 19:02:02', 1, NULL, 6, 'completed'),
(81, 18, 'bh', 'chavda@gmail.com', 2147483647, 'rajkot', 'Paratha (x4) - ₹240\r\nAloo Paneer Tikki (x1) - ₹80', 320, 'COD - Pending', '2025-09-07 13:34:52', 'pending', '2025-09-07 19:22:23', 'pending', '2025-09-07 19:04:52', 3, NULL, 56, 'completed'),
(82, 18, 'test', 'chavda@gmail.com', 1234567908, 'rajkot', 'Pauva (x1) - ₹59\r\nKaju Katari (x1) - ₹100', 159, 'COD - Pending', '2025-09-11 08:12:27', 'pending', NULL, 'pending', '2025-09-11 13:42:27', NULL, NULL, NULL, 'Pending'),
(83, 18, 'abc', 'chavda@gmail.com', 1234567890, 'surat', 'Tasty Dosa (x1) - ₹99\r\nBarafi (x1) - ₹60', 159, 'COD - Pending', '2025-09-11 08:17:03', 'pending', '2025-09-11 14:25:32', 'pending', '2025-09-11 13:47:03', 1, NULL, 2, 'completed'),
(84, 18, 'susmitaba', 'chavda@gmail.com', 1234560987, 'rajkot', 'Tasty Sandwich (x1) - ₹79\r\nMasala Meggie (x1) - ₹25', 104, 'COD - Pending', '2025-09-11 08:44:05', 'pending', '2025-09-11 14:14:52', 'pending', '2025-09-11 14:14:05', 1, NULL, 1, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` int(10) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expire` datetime DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT 'images/default-user.png',
  `status` enum('active','blocked') NOT NULL DEFAULT 'active',
  `role` enum('customer','delivery','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `profile_image`, `password`, `otp_code`, `otp_expire`, `profile_img`, `status`, `role`, `created_at`) VALUES
(17, 'v', 'faldu@gmail.com', 987678909, NULL, '$2y$10$REKdCMl3bmbjGcM2rToLAOx35KM61MhXm4/loAyVyGUrl0qKt.pb6', NULL, NULL, 'images/default-user.png', 'active', 'customer', '2025-09-06 09:47:08'),
(18, 'bharti', 'chavda@gmail.com', 2147483647, '1757577632_person1.avif', '$2y$10$CZkOOa505jIz7phjsTEy4eI/1zXDyYLvWQaHC8YDQCN/1yrASpVBK', NULL, NULL, 'images/default-user.png', 'active', 'customer', '2025-09-07 10:44:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

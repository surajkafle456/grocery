-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 05, 2026 at 07:58 AM
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
-- Database: `grocery_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_addresses`
--

CREATE TABLE `delivery_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `municipality` varchar(50) DEFAULT NULL,
  `ward` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_addresses`
--

INSERT INTO `delivery_addresses` (`id`, `user_id`, `full_name`, `contact`, `city`, `municipality`, `ward`, `address`, `created_at`, `updated_at`) VALUES
(1, 1, 'Suraj Kafle', '9876543212', 'ktm', 'kathmandu', '2', 'fttyu', '2026-02-06 16:50:13', '2026-02-06 16:50:13'),
(2, 8, 'Suraj Kafle', '9876543212', 'ktm', 'kathmandu', '2', 'rtrtt', '2026-02-06 16:54:43', '2026-02-06 16:54:43'),
(3, 15, 'sanjay kafle', '1234554321', 'kathmandu', 'ktm', '5', 'berbewrg43gergbrge', '2026-02-08 17:54:16', '2026-02-08 17:54:16'),
(4, 16, 'sosti giri', '9812345678', 'ktm', 'kathmandu', '7', 'gautamtoll', '2026-02-09 03:23:30', '2026-02-09 03:23:30'),
(5, 18, 'suman', '9880077007', 'ktm', 'kathmandu', '2', 'sikhar', '2026-02-10 02:41:30', '2026-02-10 02:41:30'),
(6, 19, 'nobody', '9999999999', 'ktm', 'kathmandu', '7', 'abcde', '2026-02-10 18:37:08', '2026-02-10 18:37:08'),
(7, 20, 'some', '9888888888', 'ktm', 'kathmandu', '7', 'tuyvhjvjh', '2026-02-11 04:50:07', '2026-02-11 04:50:07'),
(8, 21, 'PRATIK', '9860667506', 'HHDFJ', 'HFKJSHFJK', '14', 'JFDJFLDKJK', '2026-02-11 05:30:26', '2026-02-11 05:30:26');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `order_date`, `status`) VALUES
(1, 1, 140.00, '2026-01-08 13:39:58', 'Pending'),
(2, 1, 90.00, '2026-01-08 13:41:28', 'Pending'),
(3, 1, 90.00, '2026-01-08 13:42:33', 'Pending'),
(4, 1, 90.00, '2026-01-08 13:44:41', 'Pending'),
(5, 1, 420.00, '2026-01-08 17:15:36', 'Pending'),
(6, 1, 270.00, '2026-01-08 17:19:34', 'Pending'),
(7, 1, 180.00, '2026-01-08 17:28:32', 'Pending'),
(8, 1, 240.00, '2026-01-09 00:20:17', 'Pending'),
(20, 1, 140.00, '2026-02-08 08:51:55', 'Pending'),
(24, 1, 70.00, '2026-02-08 17:16:36', 'Pending'),
(27, 15, 7000.00, '2026-02-08 23:39:37', 'Delivered'),
(28, 15, 62090.00, '2026-02-08 23:42:45', 'Pending'),
(29, 15, 6160.00, '2026-02-08 19:04:05', 'Pending'),
(30, 15, 35000.00, '2026-02-08 19:06:33', 'Pending'),
(31, 15, 1400.00, '2026-02-08 19:11:01', 'Pending'),
(32, 15, 3000.00, '2026-02-08 19:11:36', 'Pending'),
(33, 15, 91980.00, '2026-02-08 19:15:15', 'Pending'),
(34, 15, 900.00, '2026-02-08 19:17:59', 'Pending'),
(35, 15, 60.00, '2026-02-08 19:21:12', 'Pending'),
(39, 18, 70.00, '2026-02-10 03:41:39', 'Pending'),
(40, 18, 350.00, '2026-02-10 03:55:17', 'Pending'),
(41, 18, 140.00, '2026-02-10 04:25:08', 'Pending'),
(42, 18, 70.00, '2026-02-10 04:29:23', 'Pending'),
(43, 1, 210.00, '2026-02-10 04:33:39', 'Pending'),
(44, 1, 70.00, '2026-02-10 14:57:56', 'Pending'),
(45, 1, 560.00, '2026-02-10 15:15:27', 'Pending'),
(46, 1, 1400.00, '2026-02-10 18:51:38', 'Pending'),
(47, 1, 560.00, '2026-02-10 18:52:25', 'Pending'),
(49, 1, 280.00, '2026-02-10 19:27:41', 'Pending'),
(50, 19, 70.00, '2026-02-10 19:37:11', 'Pending'),
(51, 19, 7540.00, '2026-02-10 19:46:42', 'Delivered'),
(52, 19, 2380.00, '2026-02-10 19:47:12', 'Pending'),
(53, 19, 3920.00, '2026-02-10 19:47:39', 'Pending'),
(54, 1, 840.00, '2026-02-11 03:19:51', 'Pending'),
(56, 1, 280.00, '2026-02-11 04:17:23', 'Pending'),
(57, 1, 1120.00, '2026-02-11 04:29:02', 'Delivered'),
(58, 20, 210.00, '2026-02-11 05:50:12', 'Delivered'),
(59, 21, 210.00, '2026-02-11 06:30:29', 'Pending'),
(60, 21, 140.00, '2026-02-11 09:34:13', 'Pending'),
(61, 1, 280.00, '2026-02-11 10:14:37', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 3, 1, 140.00),
(2, 2, 4, 1, 90.00),
(3, 3, 4, 1, 90.00),
(4, 4, 4, 1, 90.00),
(5, 5, 3, 3, 140.00),
(6, 6, 4, 3, 90.00),
(7, 7, 4, 2, 90.00),
(8, 8, 1, 6, 40.00),
(19, 20, 4, 2, 70.00),
(22, 24, 4, 1, 70.00),
(25, 27, 4, 100, 70.00),
(26, 28, 4, 887, 70.00),
(27, 29, 4, 88, 70.00),
(28, 30, 4, 500, 70.00),
(29, 31, 4, 20, 70.00),
(30, 32, 15, 60, 50.00),
(31, 33, 4, 279, 70.00),
(32, 33, 2, 37, 70.00),
(33, 33, 3, 499, 140.00),
(34, 34, 5, 45, 20.00),
(35, 35, 14, 3, 20.00),
(49, 39, 4, 1, 70.00),
(50, 40, 4, 5, 70.00),
(51, 41, 4, 2, 70.00),
(52, 42, 4, 1, 70.00),
(53, 43, 4, 3, 70.00),
(54, 44, 4, 1, 70.00),
(55, 45, 4, 8, 70.00),
(56, 46, 3, 10, 140.00),
(57, 47, 3, 4, 140.00),
(60, 49, 4, 4, 70.00),
(61, 50, 4, 1, 70.00),
(62, 51, 2, 6, 70.00),
(63, 51, 1, 20, 6.00),
(64, 51, 5, 1, 20.00),
(65, 51, 4, 64, 70.00),
(66, 51, 13, 100, 25.00),
(67, 52, 3, 17, 140.00),
(68, 53, 7, 98, 40.00),
(69, 54, 3, 6, 140.00),
(71, 56, 4, 4, 70.00),
(72, 57, 2, 8, 70.00),
(73, 57, 3, 4, 140.00),
(74, 58, 4, 3, 70.00),
(75, 59, 2, 1, 70.00),
(76, 59, 3, 1, 140.00),
(77, 60, 3, 1, 140.00),
(78, 61, 4, 4, 70.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `visible` tinyint(1) DEFAULT 1,
  `unit` enum('kg','liter','piece') NOT NULL DEFAULT 'piece'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `stock`, `visible`, `unit`) VALUES
(1, 'coffee', 6.00, 'coffee.webp', 500, 1, 'piece'),
(2, 'Coca Cola', 70.00, 'coca_cola.jpeg', 427, 1, 'liter'),
(3, 'Sugar', 140.00, 'sugar.jpg', 488, 1, 'kg'),
(4, 'Red Bull', 70.00, 'Red bull.jpeg', 489, 1, 'piece'),
(5, 'Water', 20.00, 'water.webp', 500, 1, 'liter'),
(6, 'Fanta', 110.00, 'fanta.webp', 99, 1, 'liter'),
(7, 'Kit kat', 40.00, 'kit_kat.jpg', 500, 1, 'piece'),
(8, 'Kurkure', 50.00, 'kurkure.webp', 199, 1, 'piece'),
(9, 'Lays', 60.00, 'lays.webp', 99, 1, 'piece'),
(10, 'Pepsi', 70.00, 'pepsi.png', 99, 1, 'liter'),
(11, 'potato', 60.00, 'potato.png', 99, 1, 'kg'),
(12, 'Rice', 140.00, 'rice.webp', 99, 1, 'kg'),
(13, 'salt', 25.00, '1770779600_salt.webp', 600, 1, 'piece'),
(14, 'Wai Wai', 20.00, 'wai_wai.png', 97, 1, 'piece'),
(15, 'Dairy milk', 50.00, 'dairy_milk.webp', 380, 1, 'piece'),
(16, 'Egg', 25.00, 'egg.jpg', 300, 0, 'piece'),
(17, 'Colgate', 90.00, '1770779540_colgate.jpg', 700, 0, 'piece');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `fullname` varchar(255) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`fullname`, `username`, `email`, `phone`, `address`, `password`, `id`) VALUES
('suraj kafle', 'suraj123', 'suraj235@gmail.com', '9876543212', 'ktm', 'suraj123', 1),
('suraj kafle', 'suraj12345', 'suraj23545@gmail.com', '1234567890', 'ktm', 'suraj12345', 8),
('suraj kafle', 'suraj1', 'suraj23@gmail.com', '1234455678', 'ktm', 'suraj1', 9),
('suraj kafle', 'suraj12', 'suraj25@gmail.com', '9876543213', 'ktm', 'sutraj12', 10),
('suraj kafle', 'suraj19', 'suraj2359@gmail.com', '9876543288', 'ktm', 'suraj19', 12),
('suraj kafle', 'suraj123688', 'suraj2385@gmail.com', '9876543217', '3455erg', 'suraj123', 13),
('sanjay kafle', 'sanjay123', 'sanjay@gamil.com', '1234567890', '12345', 'sanjay123', 14),
('sanjay kafle', 'sanjay1234', 'sanjay1234@email.com', '1234554321', 'ktm', 'sanjay1234', 15),
('sosti giri', 'sosti2002', 'swostigiri5@gmail.com', '9812345678', 'kalanki', 'sostigiri2002', 16),
('shishir', '8899', 'surajkafle456@gmail.com', '9807890687', 'iiii', '3r3t46rtr', 17),
('suman', 'suman123', 'suman@gmail.com', '9876787876', 'ktm', 'suman123', 18),
('nobody', 'nobody', 'nobody@gmail.com', '9999999999', 'jinvrjibnrbn', 'nobody', 19),
('some', 'some123', 'some@gmail.com', '9234543212', 'k678', 'some123', 20),
('pratik', 'pratik123', 'pratik@gmail.com', '9860667506', 'pyuthan', 'pratik123', 21);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cart_product` (`product_id`),
  ADD KEY `fk_cart_user` (`user_id`);

--
-- Indexes for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_delivery_user` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order` (`order_id`),
  ADD KEY `fk_order_items_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD UNIQUE KEY `id` (`id`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);
ALTER TABLE `users` ADD FULLTEXT KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  ADD CONSTRAINT `fk_delivery_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

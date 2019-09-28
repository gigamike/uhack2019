-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 27, 2019 at 10:55 PM
-- Server version: 5.7.26
-- PHP Version: 7.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uhack2019`
--

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved') NOT NULL,
  `comments` text,
  `created_datetime` datetime NOT NULL,
  `created_user_id` bigint(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`id`, `status`, `comments`, `created_datetime`, `created_user_id`) VALUES
(1, 'pending', NULL, '2019-09-24 00:00:00', 1001),
(4, 'pending', 'This is a test', '2019-09-24 11:51:06', 1001),
(5, 'pending', '', '2019-09-24 11:53:45', 1001),
(16, 'pending', NULL, '2019-09-24 13:01:41', 1001),
(17, 'pending', NULL, '2019-09-24 13:03:11', 1001),
(18, 'pending', NULL, '2019-09-24 13:03:59', 1001),
(19, 'pending', '', '2019-09-25 09:32:17', 1001),
(20, 'pending', NULL, '2019-09-25 11:32:43', 1001),
(21, 'pending', NULL, '2019-09-25 11:34:42', 1001),
(22, 'pending', '', '2019-09-27 09:40:19', 1001),
(23, 'pending', NULL, '2019-09-27 09:41:54', 1001),
(24, 'pending', '', '2019-09-27 09:44:58', 1001),
(25, 'pending', NULL, '2019-09-27 09:45:50', 1001),
(26, 'pending', '', '2019-09-27 09:49:42', 1001),
(27, 'pending', NULL, '2019-09-27 09:50:18', 1001);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_item`
--

CREATE TABLE `purchase_order_item` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `item` varchar(255) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchase_order_item`
--

INSERT INTO `purchase_order_item` (`id`, `purchase_order_id`, `item`, `quantity`, `unit_price`, `created_datetime`) VALUES
(1, 1, 'welding rod', 1, '90.00', '2019-09-24 00:00:00'),
(2, 4, 'light bulbs', 20, '150.00', '2019-09-24 11:51:06'),
(3, 4, 'light switch', 20, '50.00', '2019-09-24 11:51:06'),
(4, 5, 'light bulbs', 1, '1.00', '2019-09-24 11:53:45'),
(5, 5, 'light switch', 1, '1.00', '2019-09-24 11:53:45'),
(6, 16, 'bulb', 1, '50.00', '2019-09-24 13:01:41'),
(7, 17, 'bulb', 1, '50.00', '2019-09-24 13:03:11'),
(8, 18, 'bulb', 1, '4.00', '2019-09-24 13:03:59'),
(9, 19, 'bulb', 1, '200.00', '2019-09-25 09:32:18'),
(10, 19, 'switch', 1, '100.00', '2019-09-25 09:32:18'),
(11, 20, 'bulb', 10, '100.00', '2019-09-25 11:32:43'),
(12, 21, 'bulb', 10, '100.00', '2019-09-25 11:34:42'),
(13, 21, 'switch', 10, '50.00', '2019-09-25 11:34:42'),
(14, 22, 'bulb', 10, '100.00', '2019-09-27 09:40:19'),
(15, 22, 'switch', 10, '50.00', '2019-09-27 09:40:19'),
(16, 23, 'bulb', 10, '100.00', '2019-09-27 09:41:54'),
(17, 23, 'switch', 10, '50.00', '2019-09-27 09:41:54'),
(18, 24, 'bulb', 10, '100.00', '2019-09-27 09:44:58'),
(19, 24, 'switch', 10, '50.00', '2019-09-27 09:44:58'),
(20, 25, 'bulb', 10, '100.00', '2019-09-27 09:45:51'),
(21, 25, 'switch', 1, '50.00', '2019-09-27 09:45:51'),
(22, 26, 'bulb', 10, '100.00', '2019-09-27 09:49:42'),
(23, 26, 'switch', 10, '50.00', '2019-09-27 09:49:42'),
(24, 27, 'light', 10, '100.00', '2019-09-27 09:50:18'),
(25, 27, 'switch', 1, '100.00', '2019-09-27 09:50:18');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_item_bid`
--

CREATE TABLE `purchase_order_item_bid` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `item` varchar(255) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchase_order_item_bid`
--

INSERT INTO `purchase_order_item_bid` (`id`, `purchase_order_id`, `supplier_id`, `item`, `quantity`, `unit_price`, `created_datetime`) VALUES
(1, 19, 1005, 'bulb', 1, '190.00', '2019-09-25 10:28:09'),
(2, 19, 1005, 'switch', 1, '90.00', '2019-09-25 10:28:09'),
(3, 18, 1005, 'bulb', 1, '2.00', '2019-09-25 10:40:21'),
(4, 19, 1006, 'bulb', 1, '210.00', '2019-09-25 10:49:21'),
(5, 19, 1006, 'switch', 1, '110.00', '2019-09-25 10:49:21'),
(6, 19, 1007, 'bulb', 1, '210.00', '2019-09-26 11:43:50'),
(7, 19, 1007, 'switch', 1, '110.00', '2019-09-26 11:43:50'),
(8, 27, 1005, 'light', 10, '90.00', '2019-09-27 11:10:14'),
(9, 27, 1005, 'switch', 1, '90.00', '2019-09-27 11:10:14'),
(10, 27, 1006, 'light', 10, '110.00', '2019-09-27 11:11:20'),
(11, 27, 1006, 'switch', 1, '90.00', '2019-09-27 11:11:20'),
(12, 27, 1007, 'light', 10, '150.00', '2019-09-27 11:11:42'),
(13, 27, 1007, 'switch', 1, '110.00', '2019-09-27 11:11:42');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `modified_datetime` datetime DEFAULT NULL,
  `modified_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_portal`
--

CREATE TABLE `supplier_portal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `created_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `supplier_portal`
--

INSERT INTO `supplier_portal` (`id`, `name`, `url`, `created_datetime`) VALUES
(1, 'Ace Hardware', 'https://www.acehardware.ph/', '0000-00-00 00:00:00'),
(2, 'Alibaba', 'https://www.alibaba.com/countrysearch/PH/hardware-supplies-supplier.html', '0000-00-00 00:00:00'),
(3, 'Malaya Lumber & Construction Supply', 'https://malayalumber.powersites.ph/', '0000-00-00 00:00:00'),
(4, 'Vic\'s Construction & Sons Company', 'https://www.vicsconstruction.com.ph/', '0000-00-00 00:00:00'),
(5, 'Yale Hardware', 'https://www.yalehardwareph.com/', '0000-00-00 00:00:00'),
(6, 'Kik\'s Hardware', 'https://www.kikshardware.ph/', '0000-00-00 00:00:00'),
(7, 'Stronghold', 'https://stronghold.ph/', '0000-00-00 00:00:00'),
(8, 'Up-Town Industrial Sales, Inc.', 'http://www.uptown.com.ph/', '0000-00-00 00:00:00'),
(9, 'Kentool Hardware Corp. ', 'https://www.kentoolhardware.com/', '0000-00-00 00:00:00'),
(10, 'Wilcon', 'https://www.wilcon.com.ph/8-hardware', '0000-00-00 00:00:00'),
(11, 'TKL Steel Corp.', 'https://tkl.com.ph/', '0000-00-00 00:00:00'),
(12, 'ScrewTech Bolts and Nuts', 'https://www.screwtech.com.ph/', '0000-00-00 00:00:00'),
(13, 'Mackenzie Hardware', 'https://www.mackenzie.com.ph/', '0000-00-00 00:00:00'),
(14, 'Häfele', 'https://www.hafele.com.ph/en/', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('admin','supplier','manager-operation','manager-finance','engineer') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_address` text,
  `mobile_no` varchar(255) DEFAULT NULL,
  `public_address` varchar(255) DEFAULT NULL,
  `salt` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` enum('N','Y') NOT NULL,
  `created_datetime` datetime NOT NULL,
  `created_user_id` bigint(10) UNSIGNED NOT NULL,
  `modified_datetime` datetime DEFAULT NULL,
  `modified_user_id` bigint(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `role`, `email`, `password`, `first_name`, `last_name`, `company_name`, `company_address`, `mobile_no`, `public_address`, `salt`, `active`, `created_datetime`, `created_user_id`, `modified_datetime`, `modified_user_id`) VALUES
(1, 'admin', 'admin@gigamike.net', 'e698b4a0b08532cdff8443a4dd615588', 'Mik', 'Galon', NULL, NULL, NULL, NULL, 'kJ(26<$y>u01=1Su6V[t,GuDxMS=TCcAmkR%(V}FL/Wh?+_T`;', 'Y', '2014-12-30 11:40:44', 1, '2018-09-29 13:34:08', 0),
(1001, 'engineer', 'engineer@gigamike.net', '3ecdc8eb95d8152c7977d7a9aad873f1', 'Engr. Mik', 'Galon', NULL, NULL, '639096087306', '1SYUeoyrxbjZ3iN65a57f7Dxb7Wrt6onNXmFtM', '3,+]Z8@Wvdsl1*{RSe_M\'?uBd!E~@@lSX<:vzpezEx\'Q;xh$.H', 'Y', '2019-09-22 18:01:40', 0, NULL, NULL),
(1002, 'manager-operation', 'operation@gigamike.net', '35b344ea03685d365e30acdf9b391475', 'Zeev', 'Galon', NULL, NULL, '639096087306', NULL, ')de6>/J>z:2cO\'q]zvw5:lJmn_[VOe:$A\"Z`clyn\'1nn4=#^Pu', 'Y', '2019-09-22 18:02:11', 0, NULL, NULL),
(1003, 'manager-finance', 'finance@gigamike.net', '7f9ec8c627fa22bd600165e16a044237', 'Amah', 'Buenaventura CPA', NULL, NULL, '639096087306', '1SYUeoyrxbjZ3iN65a57f7Dxb7Wrt6onNXmFtM', 'UQ<g2Sl@P1+t^H848bM&Y]=|\\yN5`]IpW#0nY^-_=SwC8zJ#:\'', 'Y', '2019-09-22 18:02:47', 0, NULL, NULL),
(1005, 'supplier', 'supplier@gigamike.net', '7b909110e19363d6e63565064b170a2f', 'Raffy', 'Repiso', 'Electro Works', '290 Joseph St., Annex 41', '639086087306', '1SYUeoyrxbjZ3iN65a57f7Dxb7Wrt6onNXmFtM', 'dp!kb\\:\"0wHa1}V/ncp2TD=`T5i:]}Z?[}f%RbX(bg<A(1n[U(', 'Y', '2019-09-23 10:49:30', 0, NULL, NULL),
(1006, 'supplier', 'supplier2@gigamike.net', '7b909110e19363d6e63565064b170a2f', 'Raffy', 'Repiso', 'Working at Topway Builders, Inc.', '290 Joseph St., Annex 41', '639086087306', '1SYUeoyrxbjZ3iN65a57f7Dxb7Wrt6onNXmFtM', 'dp!kb\\:\"0wHa1}V/ncp2TD=`T5i:]}Z?[}f%RbX(bg<A(1n[U(', 'Y', '2019-09-23 10:49:30', 0, NULL, NULL),
(1007, 'supplier', 'supplier3@gigamike.net', '7b909110e19363d6e63565064b170a2f', 'Raffy', 'Repiso', 'Wilcon Depot Pasong Tamo', '290 Joseph St., Annex 41', '639086087306', '1SYUeoyrxbjZ3iN65a57f7Dxb7Wrt6onNXmFtM', 'dp!kb\\:\"0wHa1}V/ncp2TD=`T5i:]}Z?[}f%RbX(bg<A(1n[U(', 'Y', '2019-09-23 10:49:30', 0, NULL, NULL),
(1008, 'supplier', 'supplier4@gigamike.net', '7b909110e19363d6e63565064b170a2f', 'Raffy', 'Repiso', 'Tileland Bath & Tile Warehouse Incorporated', '290 Joseph St., Annex 41', '639086087306', '1SYUeoyrxbjZ3iN65a57f7Dxb7Wrt6onNXmFtM', 'dp!kb\\:\"0wHa1}V/ncp2TD=`T5i:]}Z?[}f%RbX(bg<A(1n[U(', 'Y', '2019-09-23 10:49:30', 0, NULL, NULL),
(1009, 'supplier', 'supplier5@gigamike.net', '7b909110e19363d6e63565064b170a2f', 'Raffy', 'Repiso', 'South Avenue Construction Supply', '290 Joseph St., Annex 41', '639086087306', '1SYUeoyrxbjZ3iN65a57f7Dxb7Wrt6onNXmFtM', 'dp!kb\\:\"0wHa1}V/ncp2TD=`T5i:]}Z?[}f%RbX(bg<A(1n[U(', 'Y', '2019-09-23 10:49:30', 0, NULL, NULL),
(1010, 'supplier', 'supplier6@gigamike.net', '7b909110e19363d6e63565064b170a2f', 'Raffy', 'Repiso', 'Mc Home Depot Makati', '290 Joseph St., Annex 41', '639086087306', '1SYUeoyrxbjZ3iN65a57f7Dxb7Wrt6onNXmFtM', 'dp!kb\\:\"0wHa1}V/ncp2TD=`T5i:]}Z?[}f%RbX(bg<A(1n[U(', 'Y', '2019-09-23 10:49:30', 0, NULL, NULL),
(1011, 'supplier', 'supplier7@gigamike.net', '7b909110e19363d6e63565064b170a2f', 'Raffy', 'Repiso', 'True Value Home Center Makati', '290 Joseph St., Annex 41', '639086087306', '1SYUeoyrxbjZ3iN65a57f7Dxb7Wrt6onNXmFtM', 'dp!kb\\:\"0wHa1}V/ncp2TD=`T5i:]}Z?[}f%RbX(bg<A(1n[U(', 'Y', '2019-09-23 10:49:30', 0, NULL, NULL),
(1012, 'supplier', 'supplier8@gigamike.net', '7b909110e19363d6e63565064b170a2f', 'Raffy', 'Repiso', 'Handyman Mandaluyong', '290 Joseph St., Annex 41', '639086087306', '1SYUeoyrxbjZ3iN65a57f7Dxb7Wrt6onNXmFtM', 'dp!kb\\:\"0wHa1}V/ncp2TD=`T5i:]}Z?[}f%RbX(bg<A(1n[U(', 'Y', '2019-09-23 10:49:30', 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_datetime` (`created_datetime`),
  ADD KEY `created_user_id` (`created_user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `purchase_order_item`
--
ALTER TABLE `purchase_order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`purchase_order_id`),
  ADD KEY `item` (`item`);

--
-- Indexes for table `purchase_order_item_bid`
--
ALTER TABLE `purchase_order_item_bid`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`purchase_order_id`),
  ADD KEY `item` (`item`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `supplier_portal`
--
ALTER TABLE `supplier_portal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `purchase_order_item`
--
ALTER TABLE `purchase_order_item`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `purchase_order_item_bid`
--
ALTER TABLE `purchase_order_item_bid`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_portal`
--
ALTER TABLE `supplier_portal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1013;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

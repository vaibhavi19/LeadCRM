-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2021 at 07:04 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lead_crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `sys_listviews`
--

CREATE TABLE `sys_listviews` (
  `view_id` int(4) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `sql_text` varchar(6000) DEFAULT NULL,
  `status` char(1) NOT NULL,
  `autoforward` char(1) DEFAULT NULL,
  `parent_menu_id` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sys_listviews_menus`
--

CREATE TABLE `sys_listviews_menus` (
  `menu_id` int(4) NOT NULL,
  `view_id` int(4) NOT NULL,
  `menu_caption` varchar(50) DEFAULT NULL,
  `menu_url` varchar(100) DEFAULT NULL,
  `target` varchar(10) DEFAULT NULL,
  `icon_url` varchar(200) DEFAULT NULL,
  `display_order` int(4) DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sys_menus`
--

CREATE TABLE `sys_menus` (
  `menu_id` int(4) NOT NULL COMMENT '		',
  `menu_caption` varchar(100) DEFAULT NULL,
  `parent_id` int(4) DEFAULT NULL,
  `menu_url` varchar(200) DEFAULT NULL,
  `target` varchar(10) DEFAULT NULL,
  `icon_url` varchar(200) DEFAULT NULL,
  `display_order` int(2) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `badge_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_image` varchar(500) DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` char(1) NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edited_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_name`, `user_type`, `email_id`, `mobile_no`, `password`, `user_image`, `created_on`, `created_by`, `status`, `edited_by`, `edited_on`) VALUES
(1, 'admin', 'U', 'admin@gmail.com', '8286302366', '12345', NULL, '2021-08-03 17:33:11', 1, 'A', NULL, '2021-08-03 17:33:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sys_listviews`
--
ALTER TABLE `sys_listviews`
  ADD PRIMARY KEY (`view_id`);

--
-- Indexes for table `sys_listviews_menus`
--
ALTER TABLE `sys_listviews_menus`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `sys_listviews_menus_fk1_idx` (`view_id`);

--
-- Indexes for table `sys_menus`
--
ALTER TABLE `sys_menus`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sys_listviews`
--
ALTER TABLE `sys_listviews`
  MODIFY `view_id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_listviews_menus`
--
ALTER TABLE `sys_listviews_menus`
  MODIFY `menu_id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_menus`
--
ALTER TABLE `sys_menus`
  MODIFY `menu_id` int(4) NOT NULL AUTO_INCREMENT COMMENT '		';

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2021 at 07:28 PM
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
-- Table structure for table `gm_role_master`
--

CREATE TABLE `gm_role_master` (
  `role_id` int(4) NOT NULL,
  `role_desc` varchar(100) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `default_page` varchar(250) DEFAULT NULL,
  `status` char(1) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(4) NOT NULL,
  `edited_on` datetime DEFAULT NULL,
  `edited_by` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gm_role_master`
--

INSERT INTO `gm_role_master` (`role_id`, `role_desc`, `client_id`, `default_page`, `status`, `created_on`, `created_by`, `edited_on`, `edited_by`) VALUES
(1, 'Administator', NULL, 'dashboard_admin.php', 'A', '2021-08-06 20:41:07', 1, NULL, NULL),
(3, 'Sales Person', 3, 'dashboard.php', 'A', '2021-08-10 15:35:15', 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gm_role_menu`
--

CREATE TABLE `gm_role_menu` (
  `id` int(10) NOT NULL,
  `role_id` int(5) NOT NULL,
  `menu_id` int(5) NOT NULL,
  `menu_type` char(5) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gm_role_menu`
--

INSERT INTO `gm_role_menu` (`id`, `role_id`, `menu_id`, `menu_type`) VALUES
(16, 3, 5, 'M'),
(17, 3, 6, 'M'),
(18, 3, 8, 'M'),
(19, 3, 9, 'M'),
(20, 1, 1, 'M'),
(21, 1, 4, 'M'),
(22, 1, 7, 'M'),
(23, 1, 5, 'M'),
(24, 1, 6, 'M'),
(25, 1, 10, 'M'),
(26, 1, 2, 'M'),
(27, 1, 3, 'M'),
(28, 1, 8, 'M'),
(29, 1, 9, 'M');

-- --------------------------------------------------------

--
-- Table structure for table `gm_user_roles`
--

CREATE TABLE `gm_user_roles` (
  `user_id` int(4) NOT NULL,
  `role_id` int(4) NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gm_user_roles`
--

INSERT INTO `gm_user_roles` (`user_id`, `role_id`, `created_by`, `created_on`) VALUES
(1, 1, 1, '2021-08-06 17:31:13'),
(4, 1, 1, '2021-08-08 13:43:41'),
(5, 1, 1, '2021-08-08 14:51:57'),
(6, 1, 6, '2021-08-08 15:37:45'),
(7, 1, 1, '2021-08-08 18:22:22'),
(9, 3, 7, '2021-08-10 16:29:47'),
(10, 1, 1, '2021-08-10 19:51:11'),
(11, 1, 1, '2021-08-10 20:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `lead_followup`
--

CREATE TABLE `lead_followup` (
  `followup_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `followup_date` date NOT NULL,
  `followup_time` time DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edited_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lead_notes`
--

CREATE TABLE `lead_notes` (
  `note_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `notes` text NOT NULL,
  `status` char(1) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `edited_on` datetime DEFAULT NULL,
  `edited_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lead_reminder`
--

CREATE TABLE `lead_reminder` (
  `reminder_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `reminder_date` date NOT NULL,
  `reminder_time` time DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_on` datetime DEFAULT NULL,
  `edited_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mst_clients`
--

CREATE TABLE `mst_clients` (
  `client_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_first_name` varchar(100) NOT NULL,
  `client_last_name` varchar(100) NOT NULL,
  `client_gender` varchar(100) NOT NULL,
  `client_email` varchar(100) NOT NULL,
  `client_mobile` varchar(100) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `gst_no` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `pincode` varchar(20) NOT NULL,
  `city_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `email_verified_yn` char(1) DEFAULT NULL,
  `email_verified_on` datetime DEFAULT NULL,
  `mobile_verified_yn` char(1) DEFAULT NULL,
  `mobile_verfied_on` datetime DEFAULT NULL,
  `agree_terms_yn` char(1) DEFAULT NULL,
  `agree_terms_on` datetime DEFAULT NULL,
  `country_id` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mst_clients`
--

INSERT INTO `mst_clients` (`client_id`, `user_id`, `client_first_name`, `client_last_name`, `client_gender`, `client_email`, `client_mobile`, `company_name`, `gst_no`, `address`, `pincode`, `city_id`, `state_id`, `email_verified_yn`, `email_verified_on`, `mobile_verified_yn`, `mobile_verfied_on`, `agree_terms_yn`, `agree_terms_on`, `country_id`, `created_on`, `created_by`) VALUES
(1, 5, 'rutika', '', '', 'rutika@gmail.com', '9854784262', '', NULL, '', '', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00', 2147483647),
(2, 6, 'vaibhavi', 'joshi', 'Notice: Undefined variable: gender in C:\\xampp\\htdocs\\LeadCRM\\forms\\client_info.php on line 247 valu', 'naru@gail.com', 'hiranandani', '', '', 'shsdsdxhd', '400069', 0, 0, 'Y', '2021-08-08 17:58:25', 'Y', '2021-08-08 17:59:17', 'Y', '2021-08-08 17:59:25', 0, '0000-00-00 00:00:00', 2147483647),
(3, 7, 'kishor', '', '', 'kishor@gmail.com', '8108006235', '', NULL, '', '', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00', 2147483647),
(4, 10, 'Pinky Padgaonkar', '', '', 'pinky@gmail.com', '9004763010', '', NULL, '', '', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, '0000-00-00 00:00:00', 2147483647),
(5, 11, 'Rinky', 'Padgaonkar', 'Female', 'rinky@gmail.com', '', '', 'gst_no', 'Kandivali west ', '400067', 0, 0, 'Y', '2021-08-10 21:22:11', 'Y', '2021-08-10 21:30:50', 'Y', '2021-08-10 21:31:15', 0, '0000-00-00 00:00:00', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `mst_industries`
--

CREATE TABLE `mst_industries` (
  `industry_id` int(11) NOT NULL,
  `industry_name` varchar(255) NOT NULL,
  `industry_desc` varchar(255) DEFAULT NULL,
  `industry_url` varchar(500) NOT NULL,
  `creted_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mst_industries`
--

INSERT INTO `mst_industries` (`industry_id`, `industry_name`, `industry_desc`, `industry_url`, `creted_on`, `created_by`) VALUES
(1, 'Real Estate', 'Real Estate', '', '2021-08-08 09:43:10', 1),
(2, 'SPA/Salon', 'SPA/Salon', '', '2021-08-08 09:43:10', 1),
(3, 'Banquets', 'Banquets', '', '2021-08-08 09:43:10', 1),
(4, 'Loans', 'Loans', '', '2021-08-08 09:43:10', 1),
(5, 'Health Care', 'Health Care', '', '2021-08-08 09:43:10', 1),
(6, 'Education/School/Institute', 'Education/School/Institute', '', '2021-08-08 09:43:10', 1),
(7, 'Digital Agencies', 'Digital Agencies', '', '2021-08-08 09:43:10', 1),
(8, 'Banking', 'Banking', '', '2021-08-08 09:43:10', 1),
(9, 'Travel', 'Travel', '', '2021-08-08 09:43:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mst_lead`
--

CREATE TABLE `mst_lead` (
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `source_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `lead_status` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edited_on` datetime DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mst_lead`
--

INSERT INTO `mst_lead` (`lead_id`, `user_id`, `client_id`, `client_name`, `display_name`, `mobile_number`, `whatsapp_number`, `email_id`, `source_id`, `remarks`, `lead_status`, `created_by`, `created_on`, `edited_by`, `edited_on`, `status`) VALUES
(1, 7, 7, 'Vaibhavi', 'Vaibhu', '8286302366', '8286302366', 'vaibhavijsohi44@gmail.com', 1, 'New lead manual', 'N', 7, '2021-08-04 20:22:54', NULL, NULL, 'A'),
(2, 7, 7, 'ewteststs', 'ewteststs', '7845785451', '7845785451', 'sdtsdtdsts@gmail.com', 1, 'sdtdstsdtg', 'N', 7, '2021-08-08 10:05:18', NULL, NULL, NULL),
(3, 7, 7, 'ewteststs', 'ewteststs', 'sdtsdtdst', 'sdtsdtdst', 'vaibhu@gmail.com', 1, 'sdtdstsdtg', 'N', 7, '2021-08-08 10:07:36', NULL, NULL, 'A'),
(4, 5, 0, 'India private ltd', 'India private ltd', '9898989898', '9898988888', 'india@gmail.com', 1, 'qwfaafafaf', 'N', 5, '2021-08-08 18:18:24', NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `mst_lead_banking`
--

CREATE TABLE `mst_lead_banking` (
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `account_type` varchar(100) DEFAULT NULL,
  `city_name` varchar(100) DEFAULT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `lead_status` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edited_on` datetime DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mst_lead_banquets`
--

CREATE TABLE `mst_lead_banquets` (
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `occasion` varchar(100) DEFAULT NULL,
  `booking_date` varchar(100) DEFAULT NULL,
  `additional_option` varchar(100) DEFAULT NULL,
  `menu` varchar(100) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `lead_status` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edited_on` datetime DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mst_lead_digital_agency`
--

CREATE TABLE `mst_lead_digital_agency` (
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `business_type` varchar(100) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `service` varchar(100) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `lead_status` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edited_on` datetime DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mst_lead_eduacation`
--

CREATE TABLE `mst_lead_eduacation` (
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `grade` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `board` varchar(100) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `lead_status` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edited_on` datetime DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mst_lead_health_care`
--

CREATE TABLE `mst_lead_health_care` (
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `treatment_for` varchar(100) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `lead_status` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edited_on` datetime DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mst_lead_loan`
--

CREATE TABLE `mst_lead_loan` (
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `loan_type` varchar(100) DEFAULT NULL,
  `loan_amount` varchar(100) DEFAULT NULL,
  `yearly_income` varchar(100) DEFAULT NULL,
  `profession` varchar(100) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `lead_status` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edited_on` datetime DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mst_lead_real_estate`
--

CREATE TABLE `mst_lead_real_estate` (
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `flat_type` varchar(100) DEFAULT NULL,
  `flat_size` varchar(100) DEFAULT NULL,
  `project_type` varchar(100) DEFAULT NULL,
  `property_type` varchar(100) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `budget` varchar(100) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `lead_status` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edited_on` datetime DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mst_lead_spa`
--

CREATE TABLE `mst_lead_spa` (
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `service` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `lead_status` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edited_on` datetime DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mst_lead_stages`
--

CREATE TABLE `mst_lead_stages` (
  `stage_id` int(11) NOT NULL,
  `stage_name` varchar(255) NOT NULL,
  `stage_description` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mst_lead_stages`
--

INSERT INTO `mst_lead_stages` (`stage_id`, `stage_name`, `stage_description`, `created_on`) VALUES
(1, 'New', 'New', '2021-08-08 08:31:46'),
(2, 'Inprocess', 'Inprocess', '2021-08-08 08:31:59'),
(3, 'Lost', 'Lost', '2021-08-08 08:31:59'),
(4, 'Converted', 'Converted', '2021-08-08 08:32:25'),
(5, 'Closed', 'Closed', '2021-08-08 08:32:42');

-- --------------------------------------------------------

--
-- Table structure for table `mst_lead_travel`
--

CREATE TABLE `mst_lead_travel` (
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `email_id` varchar(100) NOT NULL,
  `travel_date_from` date DEFAULT NULL,
  `travel_date_to` date DEFAULT NULL,
  `adult` varchar(100) DEFAULT NULL,
  `child` varchar(100) DEFAULT NULL,
  `souce` varchar(100) DEFAULT NULL,
  `destination` varchar(100) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `lead_status` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edited_on` datetime DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mst_sources`
--

CREATE TABLE `mst_sources` (
  `source_id` int(11) NOT NULL,
  `source_name` varchar(255) NOT NULL,
  `source_description` varchar(400) DEFAULT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mst_sources`
--

INSERT INTO `mst_sources` (`source_id`, `source_name`, `source_description`, `created_on`) VALUES
(1, 'Manual', 'Manual', '2021-08-08 08:22:47'),
(2, 'Excel', 'Excel', '2021-08-08 08:22:58');

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

--
-- Dumping data for table `sys_listviews`
--

INSERT INTO `sys_listviews` (`view_id`, `description`, `sql_text`, `status`, `autoforward`, `parent_menu_id`) VALUES
(1, 'Lead Listing', 'SELECT lead_id  as ID, client_name as `Client Name`, display_name as `Display Name`,mobile_number as `Mobile`,whatsapp_number as `Whatsapp No`,\r\nemail_id as `Email`,\r\ncase when lead_status =\'N\' then \'New\'\r\nwhen lead_status =\'A\' then \'Activities\'\r\nwhen lead_status =\'C\' then \'Close\'\r\nwhen lead_status =\'L\' then \'Lost\' end as `Lead Status`,\r\n\r\n\r\ncase when status =\'A\' then \'Enabled\' else \'Disabled\' end as Status, \r\nconcat(\'<a href=lead.php?action=EDIT&pkeyid=\',lead_id,\'&viewid=1>\',\'<i class=\"fas fa-edit\"></i>\r\n\r\n\',\'</a>\') as `Edit` FROM mst_lead order by created_on desc', 'A', 'Y', 1),
(2, 'User Master', 'SELECT user_id   as ID, user_name as `Username`, email_id as `Email`,mobile_no as `Mobile`,\r\ncase when status =\'A\' then \'Enabled\' else \'Disabled\' end as Status, \r\nconcat(\'<a href=master_user.php?action=EDIT&pkeyid=\',user_id,\'&viewid=2>\',\'<i class=\"fas fa-edit\"></i>\',\'</a>\') as `Edit`, \r\n concat(\'<a href=tran_reset_password.php?viewid=2&id=\', cast(user_id as char), \'><i class=\"fa fa-key\" aria-hidden=\"true\"></i> </a>\') as `Reset Password`, \r\n \r\n  concat(\'<a href=grant_users_role.php?viewid=2&uid=\',cast(user_id as char),\'><i class=\"fa fa-universal-access\" aria-hidden=\"true\"></i> </a>\') AS `Roles`\r\n\r\n FROM tbl_users where created_by=_SESSION_USER_ID order by created_on desc', 'A', 'Y', 1),
(3, 'User Role Master', 'SELECT role_id as ID, role_desc as `Role Description`, default_page \'Home Page\',\r\n case when status =\'A\' then \'Enabled\' else \'Disabled\' end as Status, \r\nconcat(\'<a href=grant_menu.php?rid=\',cast(role_id as char),\' target=main>Menu Rights</a>\') AS \'Grant Menu Rights\', \r\nconcat(\'<a href=master_role.php?action=EDIT&pkeyid=\',role_id,\'&viewid=1>\',\'<i class=\"fas fa-edit\"></i>\',\'</a>\') as `Edit` \r\nFROM gm_role_master where created_by=_SESSION_USER_ID order by created_on desc', 'A', 'Y', 1);

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

--
-- Dumping data for table `sys_listviews_menus`
--

INSERT INTO `sys_listviews_menus` (`menu_id`, `view_id`, `menu_caption`, `menu_url`, `target`, `icon_url`, `display_order`, `status`) VALUES
(1, 1, 'Create Lead', 'lead.php?action=NEW&viewid=1', NULL, NULL, NULL, 'A'),
(2, 2, 'Create User', 'master_user.php?action=NEW&viewid=2', NULL, NULL, NULL, 'A'),
(3, 3, 'Create Role', 'master_role.php?action=NEW&viewid=3', NULL, NULL, NULL, 'A');

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

--
-- Dumping data for table `sys_menus`
--

INSERT INTO `sys_menus` (`menu_id`, `menu_caption`, `parent_id`, `menu_url`, `target`, `icon_url`, `display_order`, `status`, `badge_name`) VALUES
(1, 'Configuration', 0, '#', NULL, 'fa fa-cogs', 100, 'A', NULL),
(2, 'Setting', 0, '#', NULL, 'fa fa-cogs', 200, 'A', NULL),
(3, 'Report', 0, '#', NULL, 'fa fa-database', 300, 'A', NULL),
(4, 'User Management', 1, 'listview.php?id=2', NULL, 'fa fa-cogs', 100, 'A', NULL),
(5, 'My Leads', 0, '#', NULL, 'fa fa-cogs', 100, 'A', NULL),
(6, 'Lead Creation', 5, 'listview.php?id=1', NULL, 'fa fa-cogs', 100, 'A', NULL),
(7, 'Role Management', 1, 'listview.php?id=3', NULL, 'fa fa-cogs', 100, 'A', NULL),
(8, 'Logout', 0, 'logout.php', NULL, 'fa fa-cogs', 1000, 'A', NULL),
(9, 'Logout Session', 8, 'logout.php', NULL, 'fa fa-cogs', 1000, 'A', NULL),
(10, 'Lead Assigning', 5, 'assign_leads.php', NULL, 'fa fa-cogs', 100, 'A', NULL);

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
  `salt` varchar(500) NOT NULL,
  `industry_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
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

INSERT INTO `tbl_users` (`user_id`, `user_name`, `user_type`, `email_id`, `mobile_no`, `password`, `salt`, `industry_id`, `client_id`, `user_image`, `created_on`, `created_by`, `status`, `edited_by`, `edited_on`) VALUES
(1, 'admin', 'A', 'admin@gmail.com', '8286302366', 'b90ef1e0c790662033e63d8e66be80e5c2f4b29f1b0f840f432f45a342204af539f861562a53a33a3e1f3b21613faefa30f655163d376cd36d636d7cdf07ae22', 'd71ca7f5431e1eeaea4ed2eb49fc6a8609b169ade6db7aeb9a1b816c7adf577a43320294d315d915f5a275a712d6f10aba43191e9c65f9e316cdec09ba34e0df', 0, NULL, NULL, '2021-08-03 17:33:11', 1, 'A', NULL, '2021-08-03 17:33:11'),
(2, 'vaibhavi', 'U', 'vaibhavijoshi44@gmail.com', '8286457852', '383ba4e6cc877b970258c680925de38c739053b076575b740d822fd9ec54c24183be4edc4efdc7e8bbdba9f491a439c8f8d5f09dada964ba22587ecc2fe0c14c', '36cb8cf2c924c8ba06392767ce5dda65b8fbf44025f156295dc27dbc3c3ebc3cb98fbe8ea96e17820634491bbcb61aea46dd5cd5e9507bd4c3a2e22f6abdfaa7', 4, NULL, NULL, '2021-08-08 13:41:03', 1, 'A', NULL, NULL),
(4, 'vaibhavi', 'U', 'bhavi@gmail.com', '9898989898', '464c02e6e38fced5024f76831d734011267056e90a06aa5bb135e59fe868e6d92b4b68c589ef6f254996ee7800c5de100eb7d2f01b9ca3dcb2cf9832f2a8ce0e', '9e888a1a826689d21363f1231991ba1616e526f256ebacb05dbfd68896cc9d75a4457db6f4ff80d650414bec1f9dc0e78a62d7546f77c7efaa7e3adf5d691146', 3, NULL, NULL, '2021-08-08 13:43:41', 1, 'A', NULL, NULL),
(5, 'rutika', 'U', 'rutika@gmail.com', '9854784262', '7ceb04fe23f6ee4329033bb2fb8d973b911baa5f7c2cc09cdbf96ccdbcf113d07f1bffad1026d983c541b4d75ec360ba56aa36af9b3daf12c6d4bbcfa2316418', '3405c0bf4a10e5fa88d6b66f60918bd96bcd86f377c6a5fd0c0f81a26350edbaa540db829ee4714ac029c04de670b6fc66da1959fb89a7067034051ca8169009', 8, NULL, NULL, '2021-08-08 14:51:57', 1, 'A', NULL, NULL),
(6, 'Nikhil', 'U', 'naru@gail.com', '9856895656', '7ad31ba0d03cb7900057305fe687c6a8ef74a617e85ff971339f76972a580a7cf2b3edf4c81bc2bbb0887a55bef4a6e603bbc6523c2ba3af47aafc8f367251a5', '251681b03f379679fa8f105eb9d2f53ae4877a68fbb4968d8750f12f9ecfc8890fca87cb897df761a1499295cbb33f684dc800d7f63a807e25d0f8331259a7a1', 1, 2, NULL, '2021-08-08 14:56:29', 1, 'A', NULL, NULL),
(7, 'kishor', 'U', 'kishor@gmail.com', '8108006235', 'b90ef1e0c790662033e63d8e66be80e5c2f4b29f1b0f840f432f45a342204af539f861562a53a33a3e1f3b21613faefa30f655163d376cd36d636d7cdf07ae22', 'd71ca7f5431e1eeaea4ed2eb49fc6a8609b169ade6db7aeb9a1b816c7adf577a43320294d315d915f5a275a712d6f10aba43191e9c65f9e316cdec09ba34e0df', 3, 3, NULL, '2021-08-08 18:22:22', 1, 'A', NULL, NULL),
(9, 'salesperson1', 'U', 'salesperson1@gmail.com', '7878986848', '085625699f853d77a101704ca3cdda16e5045b4e11ebd14eda75732b8996db8ee88b22a24224b8c7f2305b3334b94b74d96f342781751199f753a6d0c7d9ada5', 'ff06bad552a285eed0f8340f8fca10ea5030caeed7a7ebcdd66011790f0135a3cce32273be44e50d372ba84d60cb6b1d314ced163124ede30db1bb0c4f141686', 3, 3, '../data/uploads/7_248677.png', '2021-08-10 16:24:11', 7, 'A', NULL, NULL),
(10, 'Pinky Padgaonkar', 'U', 'pinky@gmail.com', '9004763010', '1eaa9aed17703a811218b30e6dbf75baf8c7c861a767b00c54a014fc44a09a2a4db10015b91676b394efb3bea57026be81d76b916823db56e71821ecb00ae51a', 'b74c6103065ade454ccaa6ec4b1834da20ef882ce88183f203aee6e4e76e4988b56655c46acae32ff23b17b7aeaebf7f601def8432346c9ef9bdcb2e12bbf7f1', 4, 4, NULL, '2021-08-10 19:51:11', 1, 'A', NULL, NULL),
(11, 'Rinky', 'U', 'rinky@gmail.com', '9004763064', '5fad6a7d4aacaf79718031d0f3cb9523a99fec638f246f3ecfe7b7b54633bd6185ab8a8b6a40220525f1d04cc5ae09096b671dc2b53b60ef37bbdf3df71087aa', 'bc2ef440aa3bdca9e6bde5e70ef340313a3da8fc364aec1e341587fd748deb6f8548c8d21601cd7c0b7cafddf20f435053dc8a9b8f98cd5eb1d0bc4956aa2afe', 2, 5, NULL, '2021-08-10 20:23:20', 1, 'A', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trn_lead_assignment`
--

CREATE TABLE `trn_lead_assignment` (
  `assignment_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transfer_from` int(11) NOT NULL,
  `transfer_to` int(11) NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `status` char(1) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `trn_log_lead`
--

CREATE TABLE `trn_log_lead` (
  `log_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `activity_desc` text NOT NULL,
  `activity_type` varchar(500) NOT NULL,
  `created_on` datetime NOT NULL,
  `ip_address` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gm_role_master`
--
ALTER TABLE `gm_role_master`
  ADD PRIMARY KEY (`role_id`),
  ADD KEY `gm_role_master_fk1_idx` (`created_by`),
  ADD KEY `gm_role_master_fk2_idx` (`edited_by`);

--
-- Indexes for table `gm_role_menu`
--
ALTER TABLE `gm_role_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gm_role_menu_fk1_idx` (`role_id`),
  ADD KEY `gm_role_menu_fk2_idx` (`menu_id`);

--
-- Indexes for table `gm_user_roles`
--
ALTER TABLE `gm_user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `gm_user_roles_fk1_idx` (`user_id`),
  ADD KEY `gm_user_roles_fk2_idx` (`role_id`),
  ADD KEY `gm_user_roles_fk3_idx` (`created_by`);

--
-- Indexes for table `lead_followup`
--
ALTER TABLE `lead_followup`
  ADD PRIMARY KEY (`followup_id`);

--
-- Indexes for table `lead_notes`
--
ALTER TABLE `lead_notes`
  ADD PRIMARY KEY (`note_id`);

--
-- Indexes for table `lead_reminder`
--
ALTER TABLE `lead_reminder`
  ADD PRIMARY KEY (`reminder_id`);

--
-- Indexes for table `mst_clients`
--
ALTER TABLE `mst_clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `mst_industries`
--
ALTER TABLE `mst_industries`
  ADD PRIMARY KEY (`industry_id`);

--
-- Indexes for table `mst_lead`
--
ALTER TABLE `mst_lead`
  ADD PRIMARY KEY (`lead_id`);

--
-- Indexes for table `mst_lead_banking`
--
ALTER TABLE `mst_lead_banking`
  ADD PRIMARY KEY (`lead_id`);

--
-- Indexes for table `mst_lead_banquets`
--
ALTER TABLE `mst_lead_banquets`
  ADD PRIMARY KEY (`lead_id`);

--
-- Indexes for table `mst_lead_digital_agency`
--
ALTER TABLE `mst_lead_digital_agency`
  ADD PRIMARY KEY (`lead_id`);

--
-- Indexes for table `mst_lead_eduacation`
--
ALTER TABLE `mst_lead_eduacation`
  ADD PRIMARY KEY (`lead_id`);

--
-- Indexes for table `mst_lead_health_care`
--
ALTER TABLE `mst_lead_health_care`
  ADD PRIMARY KEY (`lead_id`);

--
-- Indexes for table `mst_lead_loan`
--
ALTER TABLE `mst_lead_loan`
  ADD PRIMARY KEY (`lead_id`);

--
-- Indexes for table `mst_lead_real_estate`
--
ALTER TABLE `mst_lead_real_estate`
  ADD PRIMARY KEY (`lead_id`);

--
-- Indexes for table `mst_lead_spa`
--
ALTER TABLE `mst_lead_spa`
  ADD PRIMARY KEY (`lead_id`);

--
-- Indexes for table `mst_lead_stages`
--
ALTER TABLE `mst_lead_stages`
  ADD PRIMARY KEY (`stage_id`);

--
-- Indexes for table `mst_lead_travel`
--
ALTER TABLE `mst_lead_travel`
  ADD PRIMARY KEY (`lead_id`);

--
-- Indexes for table `mst_sources`
--
ALTER TABLE `mst_sources`
  ADD PRIMARY KEY (`source_id`);

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
-- Indexes for table `trn_lead_assignment`
--
ALTER TABLE `trn_lead_assignment`
  ADD PRIMARY KEY (`assignment_id`);

--
-- Indexes for table `trn_log_lead`
--
ALTER TABLE `trn_log_lead`
  ADD PRIMARY KEY (`log_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gm_role_master`
--
ALTER TABLE `gm_role_master`
  MODIFY `role_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gm_role_menu`
--
ALTER TABLE `gm_role_menu`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `lead_followup`
--
ALTER TABLE `lead_followup`
  MODIFY `followup_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_notes`
--
ALTER TABLE `lead_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_reminder`
--
ALTER TABLE `lead_reminder`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_clients`
--
ALTER TABLE `mst_clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mst_industries`
--
ALTER TABLE `mst_industries`
  MODIFY `industry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mst_lead`
--
ALTER TABLE `mst_lead`
  MODIFY `lead_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mst_lead_banking`
--
ALTER TABLE `mst_lead_banking`
  MODIFY `lead_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_lead_banquets`
--
ALTER TABLE `mst_lead_banquets`
  MODIFY `lead_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_lead_digital_agency`
--
ALTER TABLE `mst_lead_digital_agency`
  MODIFY `lead_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_lead_eduacation`
--
ALTER TABLE `mst_lead_eduacation`
  MODIFY `lead_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_lead_health_care`
--
ALTER TABLE `mst_lead_health_care`
  MODIFY `lead_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_lead_loan`
--
ALTER TABLE `mst_lead_loan`
  MODIFY `lead_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_lead_real_estate`
--
ALTER TABLE `mst_lead_real_estate`
  MODIFY `lead_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_lead_spa`
--
ALTER TABLE `mst_lead_spa`
  MODIFY `lead_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_lead_stages`
--
ALTER TABLE `mst_lead_stages`
  MODIFY `stage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mst_lead_travel`
--
ALTER TABLE `mst_lead_travel`
  MODIFY `lead_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_sources`
--
ALTER TABLE `mst_sources`
  MODIFY `source_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sys_listviews`
--
ALTER TABLE `sys_listviews`
  MODIFY `view_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sys_listviews_menus`
--
ALTER TABLE `sys_listviews_menus`
  MODIFY `menu_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sys_menus`
--
ALTER TABLE `sys_menus`
  MODIFY `menu_id` int(4) NOT NULL AUTO_INCREMENT COMMENT '		', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `trn_lead_assignment`
--
ALTER TABLE `trn_lead_assignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trn_log_lead`
--
ALTER TABLE `trn_log_lead`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

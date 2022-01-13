-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2017 at 09:30 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.5.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecom`
--

-- --------------------------------------------------------

--
-- Table structure for table `address_details`
--

CREATE TABLE `address_details` (
  `aid` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `address` varchar(500) NOT NULL,
  `state` varchar(500) NOT NULL,
  `city` varchar(100) NOT NULL,
  `mobile_no` varchar(30) NOT NULL,
  `uid` int(11) NOT NULL,
  `pincode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `address_details`
--

INSERT INTO `address_details` (`aid`, `name`, `address`, `state`, `city`, `mobile_no`, `uid`, `pincode`) VALUES
(1, 'vijay', 'kandhar', 'maharshtra', 'maharshtra', '9158680769', 1, 431714),
(2, 'vokau', 'good', 'maharshtra', 'nanded', '9158680769', 3, 431713),
(3, 'vijay', 'kandhar', 'maharshtra', 'maharshtra', '9158680769', 0, 431714);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `status`, `date`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin1@', 'active', '2016-08-06');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cid`, `pid`, `uid`, `quantity`, `date`) VALUES
(3, 5, 2, 1, '2017-07-17 14:02:48'),
(4, 4, 2, 1, '2017-07-17 14:02:58');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cid` int(11) NOT NULL,
  `category_name` varchar(200) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cid`, `category_name`, `status`, `date`) VALUES
(1, 'Mens', 'active', '2017-07-05 05:19:36'),
(2, 'Women', 'active', '2017-07-05 05:19:46'),
(3, 'Kids & Baby', 'active', '2017-07-05 05:20:20'),
(4, 'Education', 'active', '2017-07-05 05:20:40'),
(5, 'Electronics', 'active', '2017-07-05 05:20:56'),
(6, 'Others', 'active', '2017-07-05 05:21:34');

-- --------------------------------------------------------

--
-- Table structure for table `compaire_product`
--

CREATE TABLE `compaire_product` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `ip_address` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_rating`
--

CREATE TABLE `feedback_rating` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `rating_number` int(11) NOT NULL,
  `total_points` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` enum('1','0') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedback_rating`
--

INSERT INTO `feedback_rating` (`id`, `uid`, `rating_number`, `total_points`, `created`, `modified`, `status`) VALUES
(1, 5, 3, 14, '2017-07-24 11:16:09', '2017-07-24 11:18:12', '1'),
(2, 4, 2, 9, '2017-07-24 11:20:08', '2017-07-24 11:20:18', '1'),
(3, 2, 2, 9, '2017-07-24 11:21:15', '2017-07-25 06:27:56', '1'),
(4, 6, 1, 5, '2017-07-24 11:21:29', '2017-07-24 11:21:29', '1');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `pid` int(11) NOT NULL,
  `image_path` varchar(500) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `amid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`pid`, `image_path`, `status`, `amid`) VALUES
(6, 'client-face2.png', 'active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification_master`
--

CREATE TABLE `notification_master` (
  `nid` int(11) NOT NULL,
  `notification_message` text NOT NULL,
  `oid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `notification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification_master`
--

INSERT INTO `notification_master` (`nid`, `notification_message`, `oid`, `uid`, `status`, `notification_date`) VALUES
(2, 'New orders is purchase', 18, 1, 'inactive', '2017-07-18 17:09:26'),
(3, 'New orders is purchase', 19, 1, 'inactive', '2017-07-18 17:14:57'),
(4, 'New orders is purchase', 20, 1, 'inactive', '2017-07-18 17:15:22'),
(5, 'New orders is purchase', 21, 1, 'inactive', '2017-07-18 17:15:26'),
(6, 'New orders is purchase', 22, 1, 'inactive', '2017-07-18 17:15:30'),
(7, 'New orders is purchase', 23, 1, 'inactive', '2017-07-18 17:15:51'),
(8, 'New orders is purchase', 24, 1, 'inactive', '2017-07-18 17:21:58'),
(9, 'New orders is purchase', 25, 1, 'inactive', '2017-07-18 17:31:01'),
(10, 'New orders is purchase', 26, 1, 'inactive', '2017-07-18 17:31:35'),
(11, 'New orders is purchase', 27, 1, 'inactive', '2017-07-18 18:20:29'),
(12, 'New orders is purchase', 28, 1, 'inactive', '2017-07-19 10:13:23'),
(13, 'New orders is purchase', 29, 1, 'inactive', '2017-07-19 10:37:00'),
(14, 'order is return', 9, 1, 'inactive', '2017-07-19 14:12:57'),
(15, 'order is cancel', 29, 1, 'inactive', '2017-07-19 14:19:01'),
(16, 'add review on product', 6, 1, 'inactive', '2017-07-19 14:54:45'),
(17, 'new account is create', 0, 3, 'inactive', '2017-07-19 15:05:18'),
(18, 'New orders is purchase', 30, 3, 'inactive', '2017-07-19 16:04:59'),
(19, 'order is return', 9, 1, 'inactive', '2017-07-19 16:31:04'),
(20, 'order is return', 9, 1, 'inactive', '2017-07-19 16:31:32'),
(21, 'order is return', 9, 1, 'inactive', '2017-07-19 16:34:19'),
(22, 'order is return', 7, 1, 'inactive', '2017-07-19 16:44:56'),
(23, 'New orders is purchase', 31, 1, 'inactive', '2017-07-19 16:58:27'),
(24, 'order is cancel', 31, 1, 'inactive', '2017-07-19 18:11:57'),
(27, 'New orders is purchase', 34, 1, 'inactive', '2017-07-21 16:13:41'),
(28, 'New orders is purchase', 35, 1, 'inactive', '2017-07-21 16:23:33'),
(29, 'New orders is purchase', 36, 1, 'inactive', '2017-07-21 16:24:34'),
(30, 'New orders is purchase', 37, 1, 'inactive', '2017-07-21 17:53:11'),
(31, 'New orders is purchase', 38, 1, 'inactive', '2017-07-25 10:50:03'),
(32, 'order is cancel', 38, 1, 'inactive', '2017-07-25 10:57:26');

-- --------------------------------------------------------

--
-- Table structure for table `orders_cancelation`
--

CREATE TABLE `orders_cancelation` (
  `ocid` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `cancelation_reason` varchar(300) NOT NULL,
  `comment` text NOT NULL,
  `uid` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_cancelation`
--

INSERT INTO `orders_cancelation` (`ocid`, `oid`, `cancelation_reason`, `comment`, `uid`, `status`, `date`) VALUES
(1, 14, 'Order placed by mistake', 'test', 1, 'active', '2017-07-18 14:56:22'),
(2, 13, 'The delivery is delayed', 'test', 1, 'active', '2017-07-18 14:58:32'),
(3, 12, 'Order placed by mistake', 'test', 1, 'active', '2017-07-18 14:59:53'),
(4, 15, 'by mistake', 'safdasf sadfasdfasf', 1, 'active', '2017-07-18 00:00:00'),
(5, 29, 'The delivery is delayed', 'very delay for delivery', 1, 'active', '2017-07-19 14:19:01'),
(6, 31, 'Bought it from somewhere else', 'My Mistake', 1, 'active', '2017-07-19 18:11:57'),
(7, 38, 'Expected delivery time is too long', 'GOOD COMMENT ON MY PRODUCT', 1, 'active', '2017-07-25 10:57:26');

-- --------------------------------------------------------

--
-- Table structure for table `orders_details`
--

CREATE TABLE `orders_details` (
  `oid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `date` datetime NOT NULL,
  `status` enum('send','recived','cancel','delivered','confirm','return','returnprocess') NOT NULL,
  `cancelation_date` datetime NOT NULL,
  `recived_date` datetime NOT NULL,
  `cofirm_date` datetime NOT NULL,
  `delivery_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_details`
--

INSERT INTO `orders_details` (`oid`, `uid`, `order_id`, `date`, `status`, `cancelation_date`, `recived_date`, `cofirm_date`, `delivery_date`) VALUES
(1, 1, 'ID-181696', '2017-07-06 02:12:17', 'recived', '2017-07-06 03:01:27', '0000-00-00 00:00:00', '2017-07-06 02:30:18', '2017-07-06 02:47:40'),
(3, 1, 'ID-849944', '2017-07-07 23:04:57', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 1, 'ID-109102', '2017-07-07 23:05:22', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 1, 'ID-857509', '2017-07-07 23:10:40', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 1, 'ID-874155', '2017-07-07 23:13:01', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 1, 'ID-218171', '2017-07-14 15:51:30', 'recived', '0000-00-00 00:00:00', '2017-07-17 15:28:14', '2017-07-14 15:51:49', '2017-07-14 15:55:19'),
(8, 1, 'ID-994881', '2017-07-15 12:59:50', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 1, 'ID-504185', '2017-07-15 18:24:53', 'recived', '0000-00-00 00:00:00', '2017-07-18 15:35:23', '2017-07-18 15:34:55', '2017-07-18 15:35:09'),
(10, 1, 'ID-175871', '2017-07-15 18:25:30', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 1, 'ID-115978', '2017-07-17 10:26:47', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 1, 'ID-207288', '2017-07-17 10:28:59', 'cancel', '2017-07-18 14:59:53', '0000-00-00 00:00:00', '2017-07-17 15:26:11', '0000-00-00 00:00:00'),
(13, 1, 'ID-722930', '2017-07-17 16:03:22', 'cancel', '2017-07-18 14:58:32', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 1, 'ID-603835', '2017-07-18 12:33:21', 'cancel', '2017-07-18 14:56:22', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 1, 'ID-127114', '2017-07-18 14:48:07', 'cancel', '2017-07-18 14:49:46', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 1, 'ID-592982', '2017-07-18 15:38:08', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 1, 'ID-637666', '2017-07-18 16:53:24', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 1, 'ID-291482', '2017-07-18 17:09:26', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 1, 'ID-238760', '2017-07-18 17:14:57', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 1, 'ID-400449', '2017-07-18 17:15:22', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 1, 'ID-129103', '2017-07-18 17:15:26', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 1, 'ID-199794', '2017-07-18 17:15:30', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 1, 'ID-444251', '2017-07-18 17:15:51', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 1, 'ID-176627', '2017-07-18 17:21:58', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 1, 'ID-194384', '2017-07-18 17:31:01', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 1, 'ID-318528', '2017-07-18 17:31:35', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 1, 'ID-761504', '2017-07-18 18:20:29', 'confirm', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2017-07-18 18:24:18', '0000-00-00 00:00:00'),
(28, 1, 'ID-857783', '2017-07-19 10:13:23', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 1, 'ID-490369', '2017-07-19 10:37:00', 'cancel', '2017-07-19 14:19:01', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 3, 'ID-486788', '2017-07-19 16:04:59', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 1, 'ID-267982', '2017-07-19 16:58:27', 'cancel', '2017-07-19 18:11:57', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 1, 'ID-972679', '2017-07-21 16:13:41', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 1, 'ID-682487', '2017-07-21 16:23:33', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 1, 'ID-300710', '2017-07-21 16:24:34', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 1, 'ID-325489', '2017-07-21 17:53:11', 'send', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 1, 'ID-241771', '2017-07-25 10:50:03', 'cancel', '2017-07-25 10:57:26', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_items_details`
--

CREATE TABLE `order_items_details` (
  `oidid` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `return_process` datetime NOT NULL,
  `return_approve` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_items_details`
--

INSERT INTO `order_items_details` (`oidid`, `oid`, `uid`, `quantity`, `pid`, `return_process`, `return_approve`) VALUES
(1, 1, 1, 10, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 3, 1, 1, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 4, 1, 5, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 5, 1, 1, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 6, 1, 2, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 6, 1, 2, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 7, 1, 2, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 7, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 7, 1, 1, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 8, 1, 2, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 8, 1, 3, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 8, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 8, 1, 1, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 9, 1, 1, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 9, 1, 1, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 10, 1, 1, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 11, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 11, 1, 1, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 12, 1, 1, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 13, 1, 4, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 14, 1, 3, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 15, 1, 4, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 16, 1, 4, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 17, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 18, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 19, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 20, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 21, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 22, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 23, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 24, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 25, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 26, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 27, 1, 1, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 28, 1, 1, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 29, 1, 2, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 29, 1, 1, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 30, 3, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 31, 1, 2, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 31, 1, 2, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 34, 1, 1, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 34, 1, 1, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 35, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 36, 1, 1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 37, 1, 3, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 38, 1, 10, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_return_items`
--

CREATE TABLE `order_return_items` (
  `oidid` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `pid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_return_items`
--

INSERT INTO `order_return_items` (`oidid`, `oid`, `uid`, `quantity`, `pid`) VALUES
(1, 9, 1, 1, 4),
(2, 9, 1, 1, 3),
(3, 7, 1, 2, 2),
(4, 7, 1, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pincode_master`
--

CREATE TABLE `pincode_master` (
  `pmid` int(11) NOT NULL,
  `pincode` int(11) NOT NULL,
  `area_city` varchar(500) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `area_state` varchar(200) NOT NULL,
  `area_country` varchar(200) NOT NULL,
  `area` varchar(200) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pincode_master`
--

INSERT INTO `pincode_master` (`pmid`, `pincode`, `area_city`, `status`, `area_state`, `area_country`, `area`, `date`) VALUES
(3, 431714, 'Nanded', 'active', 'Maharashtra', 'India', 'Kandhar', '2017-07-06 01:38:46'),
(4, 431713, 'Nanded', 'active', 'Maharashtra', 'India', 'Loha', '2017-07-06 01:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `product_details`
--

CREATE TABLE `product_details` (
  `pid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `price` double NOT NULL,
  `discount_price` double NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  `sub_sub_category_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_additional_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_details`
--

INSERT INTO `product_details` (`pid`, `title`, `price`, `discount_price`, `description`, `category_id`, `sub_category_id`, `sub_sub_category_id`, `date`, `status`, `product_quantity`, `product_additional_description`) VALUES
(2, 'Brand Ladies T-Shirt', 700, 500, '<p>\r\n	Pellentesque neque leo, dictum sit amet accumsan non, dignissim ac mauris. Mauris rhoncus, lectus tincidunt tempus aliquam, odio libero tincidunt metus, sed euismod elit enim ut mi. Nulla porttitor et dolor sed condimentum. Praesent porttitor lorem dui, in pulvinar enim rhoncus vitae. Curabitur tincidunt, turpis ac lobortis hendrerit, ex elit vestibulum est, at faucibus erat ligula non neque</p>\r\n', 2, 16, 0, '2017-07-05 05:43:49', 'active', 86, '<p>\r\n	Pellentesque neque leo, dictum sit amet accumsan non, dignissim ac mauris. Mauris rhoncus, lectus tincidunt tempus aliquam, odio libero tincidunt metus, sed euismod elit enim ut mi. Nulla porttitor et dolor sed condimentum. Praesent porttitor lorem dui, in pulvinar enim rhoncus vitae. Curabitur tincidunt, turpis ac lobortis hendrerit, ex elit vestibulum est, at faucibus erat ligula non neque</p>\r\n'),
(3, 'Ladies T-shirt', 1000, 9000, '<p>\r\n	Pellentesque neque leo, dictum sit amet accumsan non, dignissim ac mauris. Mauris rhoncus, lectus tincidunt tempus aliquam,</p>\r\n', 2, 16, 3, '2017-07-07 23:12:21', 'active', 91, '<p>\r\n	Pellentesque neque leo, dictum sit amet accumsan non, dignissim ac mauris. Mauris rhoncus, lectus tincidunt tempus aliquam,</p>\r\n'),
(4, 'Shed Villa', 500, 300, '<p>\r\n	Config : all configuration files Models : application&rsquo;s models, data sources and behaviors Controllers : application&rsquo;s controllers and components Views : presentational files Vendors : 3 rd party classes or libraries Webroot : acts as document root for the application</p>\r\n', 4, 0, 0, '2017-07-12 17:36:39', 'active', 52, '<p>\r\n	Config : all configuration files Models : application&rsquo;s models, data sources and behaviors Controllers : application&rsquo;s controllers and components Views : presentational files Vendors : 3 rd party classes or libraries Webroot : acts as document root for the application</p>\r\n'),
(5, 'Gent Shirt 3 Combo packs', 2000, 1500, '<p>\r\n	Suspendisse sed accumsan risus. Curabitur rhoncus, elit vel tincidunt elementum, nunc urna tristique nisi, in interdum libero magna tristique ante. adipiscing varius. Vestibulum dolor</p>\r\n', 1, 15, 0, '2017-07-13 11:07:52', 'active', 81, '<p>\r\n	tincidunt elementum, nunc urna tristique nisi, in interdum libero magna tristique ante. adipiscing varius. Vestibulum dolor</p>\r\n'),
(6, 'Decorate Hall accessories', 5000, 4500, '<p>\r\n	Pellentesque neque leo, dictum sit amet accumsan non, dignissim ac mauris. Mauris rhoncus, lectus tincidunt tempus aliquam,</p>\r\n', 6, 11, 0, '2017-07-18 10:16:40', 'active', 98, '<p>\r\n	Pellentesque neque leo, dictum sit amet accumsan non, dignissim ac mauris. Mauris rhoncus, lectus</p>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `piid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `product_path` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`piid`, `pid`, `product_path`) VALUES
(5, 2, 'si.jpg'),
(6, 2, 'si1.jpg'),
(7, 2, 'si2.jpg'),
(8, 3, 'si1.jpg'),
(9, 3, 'ch2.jpg'),
(10, 3, 'ch.jpg'),
(11, 3, 'ch1.jpg'),
(12, 4, '20131221_130857.jpg'),
(13, 4, '090620132556.jpg'),
(14, 4, 'banner1.png'),
(15, 4, 'cat.png'),
(16, 5, 'pc2.jpg'),
(17, 5, 'pc5.jpg'),
(18, 5, 'pc7.jpg'),
(19, 6, 'h2.jpg'),
(20, 6, 'h3.jpg'),
(21, 6, 'h4.jpg'),
(22, 6, 'l3.jpg'),
(23, 6, 'l4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_return`
--

CREATE TABLE `product_return` (
  `prid` int(11) NOT NULL,
  `unique_id` varchar(200) NOT NULL,
  `oid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `return_reason` varchar(500) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `return_date` datetime NOT NULL,
  `retrun_confrim_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_return`
--

INSERT INTO `product_return` (`prid`, `unique_id`, `oid`, `uid`, `return_reason`, `status`, `return_date`, `retrun_confrim_date`) VALUES
(1, 'ID-504185', 9, 1, 'good services details', 'inactive', '2017-07-19 16:34:19', '0000-00-00 00:00:00'),
(2, 'ID-218171', 7, 1, 'foooood', 'inactive', '2017-07-19 16:44:56', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_review`
--

CREATE TABLE `product_review` (
  `prid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `description` text NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_review`
--

INSERT INTO `product_review` (`prid`, `uid`, `pid`, `description`, `status`, `date`) VALUES
(1, 1, 4, 'good product', 'active', '2017-07-15 18:19:39'),
(2, 1, 4, 'Check the address for typing errors such as ww.example.com instead of www.example.com', 'active', '2017-07-17 11:13:31'),
(3, 1, 4, 'good', 'active', '2017-07-17 11:54:19'),
(4, 1, 5, 'Good product', 'active', '2017-07-17 16:21:50'),
(5, 1, 6, 'Best Products', 'active', '2017-07-19 14:54:45');

-- --------------------------------------------------------

--
-- Table structure for table `rating_star`
--

CREATE TABLE `rating_star` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `rating_star` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rating_star`
--

INSERT INTO `rating_star` (`id`, `uid`, `rating_star`) VALUES
(1, 5, 5),
(2, 5, 4),
(3, 5, 5),
(4, 4, 5),
(5, 4, 4),
(6, 2, 5),
(7, 6, 5),
(8, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `rid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `mobile` varchar(40) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `date` datetime NOT NULL,
  `profile_picture` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`rid`, `name`, `email`, `mobile`, `password`, `status`, `date`, `profile_picture`) VALUES
(1, 'vijay', 'vijay@globallianz.com', '9158680769', '123456', 'active', '2017-07-05 05:22:48', 'ch2_7250.jpg'),
(2, 'vijay', 'vijay@gmail.com', '9158680769', '123456', 'active', '2017-07-17 14:02:01', ''),
(3, 'Girish Choudhri', 'girish143@gmail.com', '9404469895', '123456', 'active', '2017-07-19 15:05:18', '');

-- --------------------------------------------------------

--
-- Table structure for table `subscribes`
--

CREATE TABLE `subscribes` (
  `sid` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `date` datetime NOT NULL,
  `status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subscribes`
--

INSERT INTO `subscribes` (`sid`, `email`, `date`, `status`) VALUES
(2, 'vijay2@gmail.com', '2017-07-14 14:07:21', 'active'),
(3, 'vijay@globallianz.com', '2017-07-14 16:22:37', 'active'),
(4, 'qqrqwr@gmail.com', '2017-07-14 17:28:14', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `scid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `category_name` varchar(200) NOT NULL,
  `date` datetime NOT NULL,
  `status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category` (`scid`, `mid`, `category_name`, `date`, `status`) VALUES
(1, 1, 'Shoes', '2017-07-05 05:25:24', 'active'),
(2, 1, 'Watch', '2017-07-05 05:26:33', 'active'),
(3, 2, 'Shoes', '2017-07-05 05:26:47', 'active'),
(4, 2, 'sandal', '2017-07-05 05:27:14', 'active'),
(5, 2, 'Watch', '2017-07-05 05:27:26', 'active'),
(7, 5, 'TV', '2017-07-05 05:28:00', 'active'),
(8, 5, 'Laptop', '2017-07-05 05:28:18', 'active'),
(9, 5, 'Dis', '2017-07-05 05:29:05', 'active'),
(10, 6, 'Table', '2017-07-05 05:29:41', 'active'),
(11, 6, 'Chair', '2017-07-05 05:30:10', 'active'),
(13, 3, 'Shoes', '2017-07-05 05:30:46', 'active'),
(14, 3, 'Kids Cycle', '2017-07-05 05:31:04', 'active'),
(15, 1, 'Shirt', '2017-07-05 05:37:12', 'active'),
(16, 2, 'T-Shirt', '2017-07-05 05:42:21', 'active'),
(17, 5, 'Dis', '2017-07-06 05:52:44', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `sub_sub_category`
--

CREATE TABLE `sub_sub_category` (
  `sscid` int(11) NOT NULL,
  `scid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `category_name` varchar(200) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_sub_category`
--

INSERT INTO `sub_sub_category` (`sscid`, `scid`, `cid`, `category_name`, `status`, `date`) VALUES
(1, 11, 6, 'Goods', 'active', '2017-07-06 05:53:49'),
(2, 8, 5, 'Dell', 'active', '2017-07-06 05:54:03'),
(3, 16, 2, 'Brand Factory', 'active', '2017-07-06 05:54:24');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address_details`
--
ALTER TABLE `address_details`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `compaire_product`
--
ALTER TABLE `compaire_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback_rating`
--
ALTER TABLE `feedback_rating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `notification_master`
--
ALTER TABLE `notification_master`
  ADD PRIMARY KEY (`nid`);

--
-- Indexes for table `orders_cancelation`
--
ALTER TABLE `orders_cancelation`
  ADD PRIMARY KEY (`ocid`);

--
-- Indexes for table `orders_details`
--
ALTER TABLE `orders_details`
  ADD PRIMARY KEY (`oid`);

--
-- Indexes for table `order_items_details`
--
ALTER TABLE `order_items_details`
  ADD PRIMARY KEY (`oidid`);

--
-- Indexes for table `order_return_items`
--
ALTER TABLE `order_return_items`
  ADD PRIMARY KEY (`oidid`);

--
-- Indexes for table `pincode_master`
--
ALTER TABLE `pincode_master`
  ADD PRIMARY KEY (`pmid`),
  ADD KEY `pincode` (`pincode`);

--
-- Indexes for table `product_details`
--
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`piid`);

--
-- Indexes for table `product_return`
--
ALTER TABLE `product_return`
  ADD PRIMARY KEY (`prid`);

--
-- Indexes for table `product_review`
--
ALTER TABLE `product_review`
  ADD PRIMARY KEY (`prid`);

--
-- Indexes for table `rating_star`
--
ALTER TABLE `rating_star`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `subscribes`
--
ALTER TABLE `subscribes`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`scid`);

--
-- Indexes for table `sub_sub_category`
--
ALTER TABLE `sub_sub_category`
  ADD PRIMARY KEY (`sscid`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address_details`
--
ALTER TABLE `address_details`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `compaire_product`
--
ALTER TABLE `compaire_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feedback_rating`
--
ALTER TABLE `feedback_rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `notification_master`
--
ALTER TABLE `notification_master`
  MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `orders_cancelation`
--
ALTER TABLE `orders_cancelation`
  MODIFY `ocid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `orders_details`
--
ALTER TABLE `orders_details`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `order_items_details`
--
ALTER TABLE `order_items_details`
  MODIFY `oidid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `order_return_items`
--
ALTER TABLE `order_return_items`
  MODIFY `oidid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `pincode_master`
--
ALTER TABLE `pincode_master`
  MODIFY `pmid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `product_details`
--
ALTER TABLE `product_details`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `piid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `product_return`
--
ALTER TABLE `product_return`
  MODIFY `prid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `product_review`
--
ALTER TABLE `product_review`
  MODIFY `prid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `rating_star`
--
ALTER TABLE `rating_star`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `subscribes`
--
ALTER TABLE `subscribes`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `scid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `sub_sub_category`
--
ALTER TABLE `sub_sub_category`
  MODIFY `sscid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wid` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

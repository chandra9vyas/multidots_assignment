-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2018 at 06:00 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yii2latest`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL COMMENT '1=''roles'',2=''action or task''',
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, 'admin', NULL, NULL, NULL, NULL),
('FrontendSiteIndex', 2, 'Index Page for Insta feeds', NULL, NULL, 1523533767, 1523533767),
('FrontendSiteInstafeeds', 2, 'Callback function for instagram', NULL, NULL, 1523699809, 1523699809),
('FrontendSiteLogin', 2, 'Site Login', NULL, NULL, 1523533726, 1523533726),
('FrontendSiteSignup', 2, 'Sign up Page', NULL, NULL, 1523535907, 1523535907),
('guest', 1, 'guest', NULL, NULL, NULL, NULL),
('user', 1, 'user', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('admin', 'FrontendSiteIndex'),
('user', 'FrontendSiteIndex'),
('guest', 'FrontendSiteInstafeeds'),
('guest', 'FrontendSiteLogin'),
('guest', 'FrontendSiteSignup');

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1469431647),
('m130524_201442_init', 1469437375),
('m140506_102106_rbac_init', 1469431651),
('m160725_093452_rbac_init', 1469439768);

-- --------------------------------------------------------

--
-- Table structure for table `ytl_insta_feeds`
--

CREATE TABLE IF NOT EXISTS `ytl_insta_feeds` (
  `ifd_id` int(11) NOT NULL AUTO_INCREMENT,
  `ifd_u_id` int(11) NOT NULL,
  `ifd_feed_id` varchar(255) DEFAULT NULL,
  `ifd_type` tinyint(4) DEFAULT '0' COMMENT '0=''image'',1=''video'',2=''carousel''',
  `ifd_url` varchar(255) DEFAULT NULL,
  `ifd_link` varchar(255) DEFAULT NULL,
  `ifd_caption` text,
  `ifd_created` datetime DEFAULT NULL,
  PRIMARY KEY (`ifd_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ytl_user`
--

CREATE TABLE IF NOT EXISTS `ytl_user` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_role` tinyint(4) NOT NULL DEFAULT '2' COMMENT '1= ''admin'', 2=''user''',
  `u_first_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_last_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_password_hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_salt` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_auth_key` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_phone` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_bio` text COLLATE utf8_unicode_ci,
  `u_website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `u_isBusiness` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_insta_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_insta_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `u_follows` int(11) DEFAULT '0',
  `u_media` int(11) DEFAULT '0',
  `u_followed_by` int(11) DEFAULT '0',
  `u_status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0 = inactive, 1 = active, 2 = deleted,3=''blocked''',
  `u_created` datetime NOT NULL,
  `u_modified` datetime NOT NULL,
  PRIMARY KEY (`u_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ytl_user`
--

INSERT INTO `ytl_user` (`u_id`, `u_role`, `u_first_name`, `u_last_name`, `u_username`, `u_email`, `u_image`, `u_password_hash`, `u_password_reset_token`, `u_salt`, `u_auth_key`, `u_phone`, `u_bio`, `u_website`, `u_isBusiness`, `u_insta_id`, `u_insta_token`, `u_follows`, `u_media`, `u_followed_by`, `u_status`, `u_created`, `u_modified`) VALUES
(1, 1, 'Admin', 'Myproj', 'Admin_1523711116', 'admin@myproject.com', '', '48f99337bbf403e381b20b1740f30a8730f63e4426858163724b532cf12ff237', '', 'cicQALhu', 'kOxEqwPlIFU2hX9sL8Hyd-cmIiMuV23B', '', '', '', '', '', '', 0, 0, 0, 1, '2018-04-14 15:05:16', '2018-04-14 15:05:16');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

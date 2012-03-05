-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2012 at 11:18 AM
-- Server version: 5.5.21
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zfapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Url the page can be found at',
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Used as the meta title for the page',
  `content` longtext COLLATE utf8_unicode_ci COMMENT 'The page content',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Only active pages can be accessed',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` datetime DEFAULT NULL,
  `can_delete` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'If 0 this page cannot be deleted.',
  `edit_url` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If 0 the url cannot be changed.',
  `full_page` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If 0 do not create a route for this page.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `routes` (`active`,`full_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstname` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  `accesslevel` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `token` char(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_key` char(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `login` (`username`,`password`,`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `firstname`, `lastname`, `email`, `active`, `date_created`, `last_login`, `accesslevel`, `token`, `password_key`, `token_date`) VALUES
(1, 'admin', '4c8b80639e84199f6fc0585a76a48d6dd3a44b7ebd8714a94fb433ae7ce2c66bc88f6bb0ad4e0562519af04ca19d4735ec75159717a9be667be9ccedb059b961', 'Firstname', 'Lastname', 'email@example.com', 1, '2011-12-12 02:26:00', '2012-03-05 09:51:21', 'superadmin', NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

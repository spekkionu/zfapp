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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Url the page can be found at',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Used as the meta title for the page',
  `content` longtext COLLATE utf8_unicode_ci COMMENT 'The page content',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Only active pages can be accessed',
  `date_created` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `can_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If 0 this page cannot be deleted.',
  `edit_url` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If 0 the url cannot be changed.',
  `full_page` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If 0 do not create a route for this page.',
  `meta_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Used in the <title> tag',
  `meta_keywords` text COLLATE utf8_unicode_ci COMMENT 'Kewords for SEO <meta> keywords tag',
  `meta_description` text COLLATE utf8_unicode_ci COMMENT 'Description for SEO <meta> description tag',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `routes_idx` (`active`,`full_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--


--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The username the user logs in with',
  `password` char(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The password the user logs in with',
  `firstname` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The first name of the user',
  `lastname` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The last name of the user',
  `email` varchar(127) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The email address of the user',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Only active users may log in.',
  `date_created` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL COMMENT 'The date the user last logged in.',
  `accesslevel` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user' COMMENT 'The access level of the user',
  `token` char(128) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Token used for password reset',
  `password_key` char(128) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Hash used for password reset',
  `token_date` date DEFAULT NULL COMMENT 'Date the password reset token expires.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login_idx` (`username`,`password`,`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `firstname`, `lastname`, `email`, `active`, `date_created`, `last_login`, `accesslevel`, `token`, `password_key`, `token_date`) VALUES
(null, 'admin', '4c8b80639e84199f6fc0585a76a48d6dd3a44b7ebd8714a94fb433ae7ce2c66bc88f6bb0ad4e0562519af04ca19d4735ec75159717a9be667be9ccedb059b961', 'Firstname', 'Lastname', 'email@address.com', 1, '2012-06-02 10:54:07', NULL, 'superadmin', NULL, NULL, NULL);


--
-- Table structure for table `migration_version`
--

CREATE TABLE IF NOT EXISTS `migration_version` (
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migration_version`
--

INSERT INTO `migration_version` (`version`) VALUES
(3);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

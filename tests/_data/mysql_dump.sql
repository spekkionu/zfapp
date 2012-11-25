-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2012 at 12:52 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.8

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE IF NOT EXISTS `content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Url the page can be found at',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Used as the meta title for the page',
  `content` longtext COLLATE utf8_unicode_ci COMMENT 'The page content',
  `meta_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Used in the <title> tag',
  `meta_keywords` text COLLATE utf8_unicode_ci COMMENT 'Kewords for SEO <meta> keywords tag',
  `meta_description` text COLLATE utf8_unicode_ci COMMENT 'Description for SEO <meta> description tag',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Only active pages can be accessed',
  `date_created` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `can_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If 0 this page cannot be deleted.',
  `edit_url` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If 0 the url cannot be changed.',
  `full_page` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If 0 do not create a route for this page.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `routes_idx` (`active`,`full_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `url`, `title`, `content`, `meta_title`, `meta_keywords`, `meta_description`, `active`, `date_created`, `last_updated`, `can_delete`, `edit_url`, `full_page`) VALUES
(1, 'about/us', 'About Us', '<p>\r\n  This is the about us page.\r\n</p>', NULL, NULL, NULL, 1, '2012-11-08 23:54:05', '2012-11-09 09:46:37', 1, 1, 1),
(2, 'contact-us', 'Contact Us', '<p>\r\n  This is the contact page.\r\n</p>', NULL, NULL, NULL, 1, '2012-11-19 21:18:09', '2012-11-19 21:18:09', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `migration_version`
--

DROP TABLE IF EXISTS `migration_version`;
CREATE TABLE IF NOT EXISTS `migration_version` (
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migration_version`
--

INSERT INTO `migration_version` (`version`) VALUES
(3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The username the user logs in with',
  `password` char(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'The password the user logs in with',
  `firstname` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The first name of the user',
  `lastname` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The last name of the user',
  `email` varchar(127) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The email address of the user',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Only active users may log in.',
  `date_created` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL COMMENT 'The date the user last logged in.',
  `accesslevel` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user' COMMENT 'The access level of the user',
  `token` char(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Token used for password reset',
  `token_date` date DEFAULT NULL COMMENT 'Date the password reset token expires.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login_idx` (`username`,`password`,`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `firstname`, `lastname`, `email`, `active`, `date_created`, `last_login`, `accesslevel`, `token`, `token_date`) VALUES
(1, 'admin', '$2a$12$axpUYoY9uAR.yTw0R9jQL.KuXx41GDTiz6cK96ph575tax7U8Grhy', 'Administrator', 'Administrator', 'admin@example.com', 1, '2012-09-17 22:52:52', '2012-11-17 12:50:23', 'superadmin', NULL, NULL),
(2, 'testadmin', '$2a$12$TzZ.v/WoAOGdlBXdJ9gbQu9NPq2.piTRsX5iQWDOdYcKbmDDlm8ga', 'Firstname', 'Lastname', 'testadmin@example.com', 1, '2012-11-17 12:50:27', NULL, 'admin', NULL, NULL);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

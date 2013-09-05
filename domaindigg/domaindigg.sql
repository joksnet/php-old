-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 26, 2009 at 07:59 PM
-- Server version: 5.1.30
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `domaindigg`
--

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `value` varchar(140) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`name`, `value`) VALUES
('lang', 'es'),
('root', '/'),
('sitename', 'Domain Digg'),
('perpage', '10');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `pid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(140) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `public` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`pid`, `uid`, `name`, `description`, `public`) VALUES
(1, 1, 'Domain Digg', 'Para que no exista proyecto sin nombre.', 0),
(2, 2, 'Story', 'Sitio de Alquiler de Casas Rurales.', 0),
(3, 2, 'Agua', '...', 0),
(4, 2, 'Hal-Cash', 'Sitio de Hal-Cash.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `projects_access`
--

CREATE TABLE IF NOT EXISTS `projects_access` (
  `aid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `email` varchar(70) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `message` varchar(140) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `notice` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `projects_access`
--

INSERT INTO `projects_access` (`aid`, `pid`, `uid`, `email`, `message`, `notice`) VALUES
(1, 1, 2, 'joksnet@yahoo.com.ar', 'Creo que te puede interesar.', 0),
(2, 1, 3, 'joksnet@hotmail.com', '', 0),
(3, 1, 0, 'webmaster@joksnet.com.ar', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `projects_names`
--

CREATE TABLE IF NOT EXISTS `projects_names` (
  `nid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(140) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `projects_names`
--


-- --------------------------------------------------------

--
-- Table structure for table `projects_tlds`
--

CREATE TABLE IF NOT EXISTS `projects_tlds` (
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`,`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `projects_tlds`
--

INSERT INTO `projects_tlds` (`pid`, `tid`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tlds`
--

CREATE TABLE IF NOT EXISTS `tlds` (
  `tid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `domain` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(140) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `suggest` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tlds`
--

INSERT INTO `tlds` (`tid`, `domain`, `description`, `suggest`) VALUES
(1, 'fr', 'France', 0),
(2, 'com.ar', 'Argentina', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(70) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `lastip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `email`, `password`, `lastip`) VALUES
(1, 'joksnet@gmail.com', 'd4dc1861c4231d27a8d0811b130fd8ee', ''),
(2, 'joksnet@yahoo.com.ar', 'd4dc1861c4231d27a8d0811b130fd8ee', ''),
(3, 'joksnet@hotmail.com', 'd4dc1861c4231d27a8d0811b130fd8ee', '');

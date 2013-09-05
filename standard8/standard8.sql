-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 30, 2010 at 10:55 PM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `standard8`
--

-- --------------------------------------------------------

--
-- Table structure for table `configuracion`
--

CREATE TABLE IF NOT EXISTS `configuracion` (
  `nombre` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nombre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `configuracion`
--

INSERT INTO `configuracion` (`nombre`, `valor`) VALUES
('uriScheme', 'http'),
('uriHost', 'standard8');

-- --------------------------------------------------------

--
-- Table structure for table `personas`
--

CREATE TABLE IF NOT EXISTS `personas` (
  `id_personas` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `correo` varchar(140) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clave` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id_personas`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `personas`
--

INSERT INTO `personas` (`id_personas`, `usuario`, `correo`, `clave`) VALUES
(1, 'joksnet', 'joksnet@gmail.com', 'f531bfff76fa1f662de7b8c6b0db413e');

-- --------------------------------------------------------

--
-- Table structure for table `sesiones`
--

CREATE TABLE IF NOT EXISTS `sesiones` (
  `id_sesiones` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `id_personas` int(11) NOT NULL DEFAULT '0',
  `fecha` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_sesiones`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sesiones`
--

INSERT INTO `sesiones` (`id_sesiones`, `id_personas`, `fecha`) VALUES
('2db330b6b91a0de408dbb6db4cbd342a', 1, 1269981738);

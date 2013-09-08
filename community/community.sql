-- phpMyAdmin SQL Dump
-- version 2.6.4-pl1-Debian-1ubuntu1.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 18, 2006 at 03:50 AM
-- Server version: 4.0.24
-- PHP Version: 4.4.0-3ubuntu2
-- 
-- Database: `community`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `categories`
-- 

DROP TABLE IF EXISTS categories;
CREATE TABLE IF NOT EXISTS categories (
  id_categories bigint(20) NOT NULL auto_increment,
  name varchar(55) NOT NULL default '',
  description longtext NOT NULL,
  parent bigint(20) NOT NULL default '0',
  PRIMARY KEY  (id_categories)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `categories`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `comments`
-- 

DROP TABLE IF EXISTS comments;
CREATE TABLE IF NOT EXISTS comments (
  id_comments bigint(20) unsigned NOT NULL auto_increment,
  id_posts bigint(20) NOT NULL default '0',
  author tinytext NOT NULL,
  author_email varchar(100) NOT NULL default '',
  author_url varchar(200) NOT NULL default '',
  author_ip varchar(100) NOT NULL default '',
  agent varchar(255) NOT NULL default '',
  date datetime NOT NULL default '0000-00-00 00:00:00',
  content text NOT NULL,
  approved enum('no','yes','spam') NOT NULL default 'yes',
  PRIMARY KEY  (id_comments)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `comments`
-- 

INSERT INTO comments VALUES (1, 1, 'joksnet', 'joksnet@gmail.com', '', '192.168.1.2', '', '2006-04-18 00:00:00', 'Exellent Page!', 'yes');
INSERT INTO comments VALUES (2, 1, 'Martin Scotta', 'martinscotta@gmail.com', '', '', '', '2006-04-18 00:00:00', 'I love it.', 'yes');

-- --------------------------------------------------------

-- 
-- Table structure for table `options`
-- 

DROP TABLE IF EXISTS options;
CREATE TABLE IF NOT EXISTS options (
  name varchar(255) NOT NULL default '',
  value varchar(255) NOT NULL default ''
) TYPE=MyISAM;

-- 
-- Dumping data for table `options`
-- 

INSERT INTO options VALUES ('site_name', 'The <strong>Geek</strong> Community');
INSERT INTO options VALUES ('site_description', '(...)');
INSERT INTO options VALUES ('site_slogan', 'Blog by geeks. To geeks.');
INSERT INTO options VALUES ('per_page', '20');
INSERT INTO options VALUES ('date_format', 'l d F Y');

-- --------------------------------------------------------

-- 
-- Table structure for table `posts`
-- 

DROP TABLE IF EXISTS posts;
CREATE TABLE IF NOT EXISTS posts (
  id_posts bigint(20) unsigned NOT NULL auto_increment,
  id_users bigint(20) NOT NULL default '0',
  title text NOT NULL,
  content longtext NOT NULL,
  date datetime NOT NULL default '0000-00-00 00:00:00',
  modified datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id_posts)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `posts`
-- 

INSERT INTO posts VALUES (1, 0, 'Lorem ipsum', '<h2>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam augue ligula, hendrerit in, congue nec, cursus sed, dui.</h2>\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam augue ligula, hendrerit in, congue nec, cursus sed, dui. Ut egestas aliquam mi. Praesent lacinia velit eu dolor. Aliquam sagittis sagittis enim. Ut at libero varius diam consequat dignissim. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Suspendisse faucibus magna quis ante. Integer pulvinar. Vestibulum faucibus ornare orci. Vestibulum dictum mi eget ligula. Praesent bibendum eros sit amet nisl.</p>\r\n<h2>Lorem ipsum</h2>\r\n<ul>\r\n    <li>Lorem ipsum dolor sit amet.</li>\r\n    <li>Lorem ipsum dolor sit amet.</li>\r\n    <li>Lorem ipsum dolor sit amet.</li>\r\n    <li>Lorem ipsum dolor sit amet.</li>\r\n</ul>\r\n<h2>Lorem ipsum</h2>\r\n<blockquote><p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam augue ligula, hendrerit in, congue nec, cursus sed, dui. Ut egestas aliquam mi. Praesent lacinia velit eu dolor. Aliquam sagittis sagittis enim.</p></blockquote>', '2006-04-18 00:00:00', '2006-04-18 00:00:00');

-- --------------------------------------------------------

-- 
-- Table structure for table `posts2categories`
-- 

DROP TABLE IF EXISTS posts2categories;
CREATE TABLE IF NOT EXISTS posts2categories (
  id_posts2categories bigint(20) NOT NULL auto_increment,
  id_posts bigint(20) NOT NULL default '0',
  id_categories bigint(20) NOT NULL default '0',
  PRIMARY KEY  (id_posts2categories)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `posts2categories`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
  id_users bigint(20) NOT NULL auto_increment,
  username varchar(100) NOT NULL default '',
  password varchar(64) NOT NULL default '',
  name varchar(100) NOT NULL default '',
  description text NOT NULL,
  PRIMARY KEY  (id_users)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO users VALUES (1, 'imsoubelet', '119acfa31e8d51bc61bb1f57448ac470', 'Ignacio Soubelet', '(...)');
INSERT INTO users VALUES (2, 'lcantelmo', '98e0d80b3e860e0ce053729fd91225d9', 'Leonardo Cantelmo', '(...)');
INSERT INTO users VALUES (3, 'joksnet', '45af931cbc71964a8b90f5c6f550c9d6', 'Juan Manuel Mart&iacute;nez', '(...)');
INSERT INTO users VALUES (4, 'pablogrigo', '2c6db5fb03af005c5064044beeaa811f', 'Pablo Grigolatto', '(...)');

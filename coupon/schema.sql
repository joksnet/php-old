/*!40101 SET NAMES utf8 */;
/*!40103 SET TIME_ZONE='+00:00' */;

--
-- Table structure for table `businesses`
--

CREATE TABLE `businesses` (
  `business_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `key` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(80) NOT NULL DEFAULT '',
  `address_addr1` varchar(100) NOT NULL DEFAULT '',
  `address_addr2` varchar(100) NOT NULL DEFAULT '',
  `address_addr3` varchar(100) NOT NULL DEFAULT '',
  `address_city` varchar(80) NOT NULL DEFAULT '',
  `address_province` varchar(80) NOT NULL DEFAULT '',
  `address_postal_code` varchar(10) NOT NULL DEFAULT '',
  `country` varchar(80) NOT NULL DEFAULT '',
  `phone_main` varchar(20) NOT NULL DEFAULT '',
  `phone_mobile` varchar(20) NOT NULL DEFAULT '',
  `phone_fax` varchar(20) NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `modified` datetime NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  PRIMARY KEY (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `businesses_categories`
--

CREATE TABLE `businesses_categories` (
  `business_category_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `business_id` mediumint(8) NOT NULL,
  `category_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`business_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `modified` datetime NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 16, 2016 at 01:14 PM
-- Server version: 5.7.12-0ubuntu1.1
-- PHP Version: 7.0.8-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fuzenetcuba`
--
CREATE DATABASE IF NOT EXISTS `fuzenetcuba` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `fuzenetcuba`;

-- --------------------------------------------------------

--
-- Table structure for table `business`
--

DROP TABLE IF EXISTS `business`;
CREATE TABLE IF NOT EXISTS `business` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `social_media` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `hours_begin` time NOT NULL,
  `hours_end` time NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mall_map_directions` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mall_map_x` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mall_map_y` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D36E385E237E06` (`name`),
  UNIQUE KEY `UNIQ_8D36E38989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `business`
--

INSERT INTO `business` (`id`, `name`, `description`, `slug`, `phone`, `logo`, `social_media`, `created_at`, `updated_at`, `hours_begin`, `hours_end`, `website`, `email`, `mall_map_directions`, `mall_map_x`, `mall_map_y`) VALUES
(7, 'America\'s Insurances', 'Description of the business ...', 'america-s-insurances', '843-0400', 'americas-insurance.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.americasinsurance.com', 'customers@americasinsurance.com', 'Follow the mall map directions ...', NULL, NULL),
(8, 'Maz Fresco', 'Description of the business ...', 'maz-fresco', '843-0400', 'mazfresco-market.png', 'https://www.facebook.com/mazfresco/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.mazfrescomarket.com', 'customers@mazfrescomarket.com', 'Follow the mall map directions ...', NULL, NULL),
(9, 'Tito\'s Playland', 'Description of the business ...', 'tito-s-playland', '843-0400', 'titos.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.titos.com', 'customers@titos.com', 'Follow the mall map directions ...', NULL, NULL),
(11, 'Reforma Law Group', 'Description of the business ...', 'reforma-law-group', '843-0400', 'reforma-law.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.reformalaw.com', 'customers@reformalaw.com', 'Follow the mall map directions ...', NULL, NULL),
(12, 'Salon Ixchel', 'Description of the business ...', 'salon-ixchel', '843-0400', 'salon-ixchel.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.salonixchel.com', 'customers@salonixchel.com', 'Follow the mall map directions ...', NULL, NULL),
(14, 'Zumito', 'Description of the business ...', 'zumito', '843-0400', 'zumito.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.zumito.com', 'customers@zumito.com', 'Follow the mall map directions ...', NULL, NULL),
(15, 'Madera Café', 'Description of the business ...', 'madera-cafe', '843-0400', 'madera.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.madera.com', 'customers@madera.com', 'Follow the mall map directions ...', NULL, NULL),
(16, 'Tres gauchos', 'Description of the business ...', 'tres-gauchos', '845-0404', 'tres-gauchos.png', 'https://www.facebook.com/BrooksBrothers/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.tresgauchos.com', 'customers@tresgauchos.com', 'Follow the mall map directions ...', NULL, NULL),
(19, 'Activa 1240 Radio Station', 'Description of the business ...', 'activa-1240-radio-station', '843-0400', 'activa.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.activa.com', 'customers@activa.com', 'Follow the mall map directions ...', NULL, NULL),
(20, 'La Ranchera 880 Radio Station', 'Description of the business ...', 'la-ranchera-880-radio-station', '843-0400', 'ranchera.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.ranchera.com', 'customers@ranchera.com', 'Follow the mall map directions ...', NULL, NULL),
(22, 'Xenote Restaurant', 'Description of the business ...', 'xenote-restaurant', '843-0400', 'xenote.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.xenote.com', 'customers@xenote.com', 'Follow the mall map directions ...', NULL, NULL),
(28, 'Baila Dance Studios', 'Description of the business ...', 'baila-dance-studios', '843-0400', 'baila.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.baila.com', 'customers@baila.com', 'Follow the mall map directions ...', NULL, NULL),
(33, 'Hispanic Family Foundation', 'Description of the business ...', 'hispanic-family-foundation', '843-0400', 'hff.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.hff.com', 'customers@hff.com', 'Follow the mall map directions ...', NULL, NULL),
(37, 'Fuzenet Marketing', 'Description of the business ...', 'fuzenet-marketing', '843-0400', 'fuzenet-marketing.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.fuzenet.com', 'customers@fuzenet.com', 'Follow the mall map directions ...', NULL, NULL),
(38, 'Agua Viva', 'Description of the business ...\n', 'agua-viva', '(952) 843-0400', 'agua-viva.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.aguaviva.com', 'customers@aguaviva.com', 'Follow the mall map directions ...\n', NULL, NULL),
(39, 'AM Ofertas', 'Description of the business ...\n', 'am-ofertas', '(952) 843-0400', 'am-ofertas.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.amofertas.com', 'customers@amofertas.com', 'Follow the mall map directions ...\n', NULL, NULL),
(40, 'America United', 'Description of the business ...\n', 'america-united', '(952) 843-0400', 'america-united.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.americaunited.com', 'customers@americaunited.com', 'Follow the mall map directions ...\n', NULL, NULL),
(41, 'Auto Masters', 'Description of the business ...\n', 'auto-masters', '(952) 843-0400', 'automasters.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.automasters.com', 'customers@automasters.com', 'Follow the mall map directions ...\n', NULL, NULL),
(42, 'Balero Clothing', 'Description of the business ...\n', 'balero-clothing', '(952) 843-0400', 'balero-clothing.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.balero-clothing.com', 'customers@baleroclothing.com', 'Follow the mall map directions ...\n', NULL, NULL),
(43, 'Balero Group', 'Description of the business ...\n', 'balero-group', '(952) 843-0400', 'balero-group.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.balerogroup.com', 'customers@balerogroup.com', 'Follow the mall map directions ...\n', NULL, NULL),
(44, 'Dr Auto', 'Description of the business ...\n', 'dr-auto', '(952) 843-0400', 'dr-auto.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.drauto.com', 'customers@drauto.com', 'Follow the mall map directions ...\n', NULL, NULL),
(45, 'Maxima 1320', 'Description of the business ...\n', 'maxima-1320', '(952) 843-0400', 'maxima.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.maxima.com', 'customers@maxima.com', 'Follow the mall map directions ...\n', NULL, NULL),
(46, 'ODA Ofertas de Auto', 'Description of the business ...\n', 'oda-ofertas-de-auto', '(952) 843-0400', 'oda.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.oda.com', 'customers@oda.com', 'Follow the mall map directions ...\n', NULL, NULL),
(47, 'Palazzo ThemeWorks', 'Description of the business ...\n', 'palazzo-themeworks', '(952) 843-0400', 'palazzo-themeworks.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.palazzothemeworks.com', 'customers@palazzothemeworks.com', 'Follow the mall map directions ...\n', NULL, NULL),
(48, 'TBLC Media', 'Description of the business ...', 'tblc-media', '843-0400', 'tblc-media.png', 'https://www.facebook.com/KalvinClein/', '2016-08-16 00:54:52', '2016-08-16 00:54:52', '10:00:00', '21:00:00', 'http://www.tblcmedia.com', 'customers@tblcmedia.com', 'Follow the mall map directions ...', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `business_category`
--

DROP TABLE IF EXISTS `business_category`;
CREATE TABLE IF NOT EXISTS `business_category` (
  `business_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`business_id`,`category_id`),
  KEY `IDX_E7C1757A89DB457` (`business_id`),
  KEY `IDX_E7C175712469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `business_category`
--

INSERT INTO `business_category` (`business_id`, `category_id`) VALUES
(7, 9),
(8, 2),
(9, 14),
(11, 3),
(12, 13),
(14, 26),
(15, 1),
(16, 26),
(19, 17),
(20, 17),
(22, 1),
(28, 10),
(28, 14),
(33, 3),
(37, 3),
(48, 3);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_64C19C1989D9B62` (`slug`),
  KEY `IDX_64C19C1727ACA70` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `parent_id`, `name`, `slug`, `description`, `icon`) VALUES
(1, NULL, 'Dining', 'dining', 'Restaurants', NULL),
(2, NULL, 'Groceries', 'groceries', 'Groceries and food', NULL),
(3, NULL, 'Professional Services', 'professional-services', 'Professional services to every one', NULL),
(4, NULL, 'Clothing', 'clothing', 'Clothing and shoes store', NULL),
(5, NULL, 'Electronics', 'electronics', 'Buy any kind of appliances', NULL),
(6, NULL, 'Travel', 'travel', 'Manage your traveling', NULL),
(7, NULL, 'Automotive Rrepair', 'automotive-rrepair', 'Leave your car in our hands', NULL),
(8, NULL, 'Car Buying', 'car-buying', 'Find the car of your dreams', NULL),
(9, NULL, 'Auto Insurance', 'auto-insurance', 'Get insurance for your car', NULL),
(10, NULL, 'Events', 'events', 'Assist to our events', NULL),
(11, NULL, 'Banking / Finance', 'banking-finance', 'Keep your money secure', NULL),
(12, NULL, 'Art', 'art', 'Get the most beatiful pieces of art', NULL),
(13, NULL, 'Beauty / Salon', 'beauty-salon', 'Get the most beatiful face', NULL),
(14, NULL, 'Entertainment', 'entertainment', 'Enjoy the life', NULL),
(15, NULL, 'Food & Eat', 'food-eat', 'Food and eatables', NULL),
(16, NULL, 'Rapid Food', 'rapid-food', 'Rapid food', NULL),
(17, NULL, 'Radio', 'radio', 'Radio transmition', NULL),
(18, NULL, 'Legal & Advocacy', 'legal-advocacy', 'Legal and advocacy', NULL),
(19, NULL, 'Playground', 'playground', 'Playground', NULL),
(20, NULL, 'TV', 'tv', 'Television', NULL),
(21, NULL, 'Internet & Marketing', 'internet-marketing', 'Internet and marketing', NULL),
(22, NULL, 'Business Office', 'business-office', 'Business office', NULL),
(23, NULL, 'Store', 'store', 'Store', NULL),
(24, NULL, 'Boutique', 'boutique', 'Boutique', NULL),
(25, NULL, 'Show', 'show', 'Show', NULL),
(26, NULL, 'Food Court Vendor', 'food-court-vendor', 'Food Court Vendor', NULL),
(27, NULL, 'Retail', 'retail', 'Retail', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deal`
--

DROP TABLE IF EXISTS `deal`;
CREATE TABLE IF NOT EXISTS `deal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `video` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `points` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E3FEC116989D9B62` (`slug`),
  KEY `IDX_E3FEC116A89DB457` (`business_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `deal`
--

INSERT INTO `deal` (`id`, `business_id`, `name`, `slug`, `description`, `starts_at`, `ends_at`, `image`, `video`, `points`, `created_at`, `updated_at`) VALUES
(1, 7, 'ALL DRESSES UP TO 40% OFF!', 'all-dresses-up-to-40-off', 'Up to 40% off All Dresses!\n\nRestrictions Apply. See store for details.\n', '2016-06-04 00:00:00', '2016-06-08 00:00:00', 'W_292549.jpg', NULL, '10', '2016-08-16 00:54:53', '2016-08-16 00:54:53'),
(2, 8, 'Cool Colors … Hot Summer', 'cool-colors-hot-summer', 'Visit a Vera Bradley Factory Outlet Store\n\nFrom June 2 – June 8 for the Color Sale and enjoy 50% off the entire store, plus an extra\n20% off select colors, including Magenta Vera Vera, Flower Shower, Shower Vines, Cheery\nBlossoms and Ziggy Zags. Hurry in for the best selection! Exclusions and limitations apply.\nSee a store associate for details.\n', '2016-06-02 00:00:00', '2016-06-08 00:00:00', 'W_293800.jpg', NULL, '10', '2016-08-16 00:54:53', '2016-08-16 00:54:53'),
(3, 14, '50% off Entire Store!', '50-off-entire-store', '50% off Entire store!\n\nNew Markdowns taken! Take an extra 50% off Markdowns\nDiscount taken at register and does not apply to gift cards and previous purchases.\nMay not be combined with other offers. See a Store Associate for details.\n', '2016-06-07 00:00:00', '2016-06-12 00:00:00', 'W_294283.jpg', NULL, '10', '2016-08-16 00:54:53', '2016-08-16 00:54:53'),
(4, 14, 'Doorbuster Deals - This Weekend Only!', 'doorbuster-deals-this-weekend-only', 'Save 60 - 80% Off Entire Store + Extra 30% Off Select Styles.\n\nShop our Doorbusters for Amazing Deals This Weekend Only!  Sandals Starting at $9.99,\nHandbags Starting at $19.99, and Outerwear Starting at $39.99.\n', '2016-06-09 00:00:00', '2016-06-12 00:00:00', 'W_294403.jpg', NULL, '10', '2016-08-16 00:54:53', '2016-08-16 00:54:53');

-- --------------------------------------------------------

--
-- Constraints for dumped tables
--

--
-- Constraints for table `business_category`
--
ALTER TABLE `business_category`
  ADD CONSTRAINT `FK_E7C175712469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_E7C1757A89DB457` FOREIGN KEY (`business_id`) REFERENCES `business` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_64C19C1727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `deal`
--
ALTER TABLE `deal`
  ADD CONSTRAINT `FK_E3FEC116A89DB457` FOREIGN KEY (`business_id`) REFERENCES `business` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

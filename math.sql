-- phpMyAdmin SQL Dump
-- version 3.3.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2013 at 10:51 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.15
use math;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `math`
--

-- --------------------------------------------------------

--
-- Table structure for table `equations`
--

DROP TABLE IF EXISTS `equations`;
CREATE TABLE IF NOT EXISTS `equations` (
  `EquationID` int(11) NOT NULL AUTO_INCREMENT,
  `equation` varchar(128) NOT NULL,
  `resulting` varchar(64) DEFAULT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`EquationID`),
  KEY `score` (`score`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `equations`
--

INSERT INTO `equations` (`EquationID`, `equation`, `resulting`, `score`) VALUES
(1, 'a+a+a+a+a-a', ' ', 3328),
(2, 'a+a+a+a+a/a', ' ', 3326),
(3, 'a+a+a+a/a+a', ' ', 3326),
(4, 'a+a+a/a+a+a', ' ', 3326),
(5, 'a+a/a+a+a+a', ' ', 3326),
(6, 'a/a+a+a+a+a', ' ', 3326),
(7, 'a+a+a/a/a+b', ' ', 3325),
(8, 'a+a/a/a+a+b', ' ', 3325),
(9, 'a/a/a+a+a+b', ' ', 3325),
(10, 'a+a+a+a+a+c', ' ', 3311),
(11, 'a/a+a*a*a/f', ' ', 3311),
(12, 'a-a-a*a+b*c', ' ', 3302),
(13, 'a-a*a-a+b*c', ' ', 3302),
(14, 'a+b+da^2^c*', '0', 1755);

-- --------------------------------------------------------

--
-- Table structure for table `performance`
--

DROP TABLE IF EXISTS `performance`;
CREATE TABLE IF NOT EXISTS `performance` (
  `PerformanceID` int(11) NOT NULL AUTO_INCREMENT,
  `recordedTime` datetime NOT NULL,
  `numRecords` int(11) NOT NULL,
  `hostName` varchar(64) NOT NULL,
  PRIMARY KEY (`PerformanceID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `performance`
--

INSERT INTO `performance` (`PerformanceID`, `recordedTime`, `numRecords`, `hostName`) VALUES
(1, '2013-02-23 05:35:49', 30000, 'exilis.local'),
(2, '2013-02-23 05:35:49', 30000, 'exilis.local'),
(3, '2013-02-23 05:35:49', 30000, 'exilis.local');

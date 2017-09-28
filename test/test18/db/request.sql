-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2017 at 12:05 PM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `timer` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `geschleht` text NOT NULL,
  `_alter` text NOT NULL,
  `producte` text NOT NULL,
  `monatliche_kosten` text NOT NULL,
  `checker` text NOT NULL,
  `bewertung` text NOT NULL,
  `wonhort` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`timer`, `geschleht`, `_alter`, `producte`, `monatliche_kosten`, `checker`, `bewertung`, `wonhort`) VALUES
('2017-09-27 10:03:24', '1', '1', '1', '1', '1', '1', '1'),
('2017-09-27 10:03:24', '1', '1', '1', '1', '1', '1', '1'),
('2017-09-27 10:03:24', '1', '1', '1', '1', '1', '1', '1'),
('2017-09-27 10:03:24', '1', '1', '1', '1', '1', '1', '1'),
('2017-09-27 10:03:24', '', '', '', 'yruurt', '', '', ''),
('2017-09-27 10:03:24', '', 'rwerwr', 'rweqrw', 'yruurt', '', '', 'rqwerqwr'),
('2017-09-27 10:03:24', '', 'rwerwr', 'rweqrw', 'yruurt', '', '', 'rqwerqwr'),
('2017-09-27 10:03:24', '', '', '', '1111', '', '', '13123'),
('2017-09-27 10:03:24', '', '', '', '', '', '', ''),
('2017-09-27 10:03:24', '', '', '', '', '', '', ''),
('2017-09-27 10:03:24', '', '', '', '', '', '', ''),
('2017-09-27 10:03:24', '', '', '', '', '', '', ''),
('2017-09-27 10:03:24', '', '', '', '', '', '', ''),
('2017-09-27 10:03:24', '', '', '', '', '', '', ''),
('2017-09-27 10:03:24', '', '', '', '', '', '', ''),
('2017-09-27 10:03:24', '', '', '', '', '', '', ''),
('2017-09-27 10:03:24', '', '', '', '', '', '', ''),
('2017-09-27 10:03:24', '', 'dhfhd', 'dhsfhdf', 'dhfhffdh', '', '', 'hdfshfh'),
('2017-09-27 10:04:17', '', 'dhfhd', 'dhsfhdf', 'dhfhffdh', '', '', 'hdfshfh'),
('2017-09-27 10:04:19', '', 'dhfhd', 'dhsfhdf', 'dhfhffdh', '', '', 'hdfshfh'),
('2017-09-27 10:05:03', '', 'dhfhd', 'dhsfhdf', 'dhfhffdh', '', '', 'hdfshfh');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

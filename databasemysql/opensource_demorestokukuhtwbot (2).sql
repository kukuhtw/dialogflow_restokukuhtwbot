-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2022 at 03:25 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `opensource_demorestokukuhtwbot`
--

-- --------------------------------------------------------

--
-- Table structure for table `log_conversation`
--

CREATE TABLE `log_conversation` (
  `id` bigint(20) NOT NULL,
  `cookies_user` varchar(255) NOT NULL,
  `actor` char(16) NOT NULL,
  `ipaddress` text NOT NULL,
  `browser` text NOT NULL,
  `messages` text NOT NULL,
  `msgdate` datetime NOT NULL,
  `responsebot` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `category` char(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `category`, `title`, `harga`, `photo`) VALUES
(1, 'Makanan', 'Bakso Mie', '25100.00', ''),
(2, 'Makanan', 'Bakso Bihun', '26500.00', ''),
(3, 'Makanan', 'Bakso Pangsit Goreng', '27500.00', ''),
(4, 'Makanan', 'Bakso Campur', '25650.00', ''),
(5, 'Makanan', 'Mie Ayam Yamin', '25900.00', ''),
(6, 'Makanan', 'Mie Ayam Jamur', '27550.00', ''),
(7, 'Makanan', 'Mie Goreng', '18150.00', ''),
(8, 'Makanan', 'Mie Rebus', '27450.00', ''),
(9, 'Minuman', 'Es Jeruk', '15500.00', ''),
(10, 'Minuman', 'Es Teh Manis', '10200.50', ''),
(11, 'Minuman', 'Es Teh Tawar', '11500.00', ''),
(12, 'Minuman', 'Air Es', '7500.25', ''),
(13, 'Minuman', 'Es Kelapa Muda', '18500.75', ''),
(14, 'Minuman', 'Teh Manis Hangat', '12600.00', ''),
(15, 'Minuman', 'Teh Tawar Hangat', '15500.65', ''),
(16, 'Makanan', 'Nasi goreng', '20450.15', ''),
(17, 'Minuman', 'Kopi susu ABC', '19500.25', ''),
(18, 'Makanan', 'Kerupuk kulit', '2000.25', ''),
(19, 'Makanan', 'Tahu isi', '1500.00', '');

-- --------------------------------------------------------

--
-- Table structure for table `restocart`
--

CREATE TABLE `restocart` (
  `id` bigint(20) NOT NULL,
  `session` varchar(255) NOT NULL,
  `namaproduct` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `cartdate` datetime NOT NULL,
  `isconfirm` tinyint(1) NOT NULL,
  `invoiceid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `restoinvoice`
--

CREATE TABLE `restoinvoice` (
  `invoiceid` int(11) NOT NULL,
  `session` varchar(255) NOT NULL,
  `totalbilling` decimal(12,2) NOT NULL,
  `invoicedate` datetime NOT NULL,
  `customername` varchar(255) NOT NULL,
  `customeremail` varchar(255) NOT NULL,
  `customerhape` char(64) NOT NULL,
  `customeralamat` text NOT NULL,
  `customernote` text NOT NULL,
  `ispaid` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `restosessionuser`
--

CREATE TABLE `restosessionuser` (
  `session` varchar(255) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `hape` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_conversation`
--
ALTER TABLE `log_conversation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restocart`
--
ALTER TABLE `restocart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restoinvoice`
--
ALTER TABLE `restoinvoice`
  ADD PRIMARY KEY (`invoiceid`);

--
-- Indexes for table `restosessionuser`
--
ALTER TABLE `restosessionuser`
  ADD PRIMARY KEY (`session`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_conversation`
--
ALTER TABLE `log_conversation`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `restocart`
--
ALTER TABLE `restocart`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restoinvoice`
--
ALTER TABLE `restoinvoice`
  MODIFY `invoiceid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

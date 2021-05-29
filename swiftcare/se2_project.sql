-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2021 at 05:31 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `se2_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(25) CHARACTER SET latin1 NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(20, 'super23', '$2y$10$4e5B6hMpQJCmwJTE/Xy.4.gcsRTb16IZvg1/adyEaqFQKuaFzBne6');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `user_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `add_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `IID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `totalPrice` float(30,2) NOT NULL,
  `UID` int(11) NOT NULL,
  `timeStamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `address` varchar(300) NOT NULL,
  `cardInfo` varchar(200) NOT NULL,
  `adID` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ID` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `category` varchar(30) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` float(4,2) DEFAULT NULL,
  `image` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID`, `name`, `category`, `description`, `price`, `image`) VALUES
(1, 'Melatonin', 'Sleep Aid', 'Melatonin is a hormone primarily released by the pineal gland at night, and has long been associated with control of the sleep–wake cycle.', 5.00, 'product_pics/melatonin.jfif'),
(2, 'Advil', 'Pain Relief', 'Used to treat mild aches and pain. Each tablet of Advil contains 200 mg of ibuprofen.', 6.00, 'product_pics/advil.jfif'),
(3, 'Tums', 'Antacid', 'TUMS antacid tablets relieve heartburn, sour stomach, acid indigestion, and upset stomach associated with these symptoms.', 3.00, 'product_pics/tums.jfif'),
(4, 'Zyrtec', 'Allergy Relief', 'With 10 milligrams of cetirizine hydrochloride per tablet, this prescription-strength allergy medicine provides 24 hours of relief.', 9.00, 'product_pics/zyrtec.jfif'),
(5, 'Benadryl', 'Allergy Relief', 'Benadryl Allergy Ultratabs Antihistamine Allergy Relief Tablets offer effective allergy relief.', 12.00, 'product_pics/benadryl.jfif'),
(6, 'Tylenol', 'Pain Relief', 'Tylenol Extra Strength caplets with 500mg of acetaminophen help reduce fever and provide temporary relief of minor aches and pains.', 6.00, 'product_pics/tylenol.jfif'),
(7, 'Acetaminophen', 'Pain Relief', 'Eliminate your aches and pains with CVS Health extra strength acetaminophen 500mg gelcaps.', 6.00, 'product_pics/acetaminophen.jpg'),
(8, 'Pepcid', 'Antacid', 'Quickly relieve heartburn associated with acid indigestion and sour stomach with Pepcid Complete Acid Reducer + Antacid Chewable Tablets.', 20.00, 'product_pics/pepcid.jfif'),
(9, 'Pepto Bismol', 'Antacid', 'Peptos formula coats your stomach and provides fast relief from nausea, heartburn, indigestion, upset stomach, and diarrhea.', 7.00, 'product_pics/pepto_bismol.jfif'),
(10, 'ZzzQuil', 'Sleep Aid', 'This non-habit-forming sleep-aid helps you get some shut-eye, so you can wake up feeling refreshed.', 7.00, 'product_pics/zzzquil.jfif');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(4, 'arthur', '$2y$10$MeLtuLGQoaPePSA269BqRuKzKt1V04yxAvRvned8K9r8bG5YA9dj.', 'art@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

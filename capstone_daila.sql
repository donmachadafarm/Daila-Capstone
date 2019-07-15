-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 15, 2019 at 11:44 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `capstone_daila`
--

-- --------------------------------------------------------

--
-- Table structure for table `audittrail`
--

CREATE TABLE `audittrail` (
  `productID` int(11) NOT NULL,
  `oldQuantity` int(11) NOT NULL,
  `quantityChange` int(11) NOT NULL,
  `dateChange` date NOT NULL,
  `userID` int(11) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `audittrail`
--

INSERT INTO `audittrail` (`productID`, `oldQuantity`, `quantityChange`, `dateChange`, `userID`, `remarks`) VALUES
(1, 60, 10, '2019-07-01', 1, 'awdwa'),
(19, 50, 1000, '2019-07-01', 1, 'ajfpaw');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerID` int(11) NOT NULL,
  `company` varchar(45) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `address` text NOT NULL,
  `contactNum` varchar(45) NOT NULL,
  `position` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerID`, `company`, `firstName`, `lastName`, `email`, `address`, `contactNum`, `position`) VALUES
(1, 'Daila Herbals', 'Jarvis', 'Punasan', 'JP.DH@Dailaherbals.com', '2752 Tyaft Avenue Extension1300 Pasay City', '09269384771', 'CEO'),
(2, 'Mercury Drug', 'Moses Trevion', 'Miedes', 'Moses.Miedes@mercurydrug.com', '125 West Capitol Drive Kapitolyo 1600', '09263032925', 'Operations Manager'),
(3, 'Kultura', 'Toli Marquis', 'Guevara', 'Toli.Guevara@kulturaph.com', '14 Esteban Street 1550, Mandaluyong City', '09299585795', 'Supply Relations Manager'),
(4, 'SM Hypermarket', 'Leonides Justus Songco', 'Aguilar', 'Leo.Aguilar@smph.com', 'Unit 211 Danarra Condominium Mola Street Cor. Metropolitan Avenue La Paz 1200, Makati City', '09913099303', 'Product Manager'),
(5, 'Watsons', 'Geoffrey Cesario Tangco', 'Villarom', 'Geof.Villaroman@watsons.com', '8473 LE West Service Rd., Km 14, Brgy. Sun Valley, ParaÃ????Ã???Ã??Ã?Â±aque City', '09438619417', 'Operations Manager'),
(6, 'Puregold', 'Henriqua Toli', 'Montenegro', 'Henriqua.Montenegro@pg.com', '719 Quirino H-Way San Bartolome 1100, Quezon City', '09574107826', 'Product Manager'),
(7, 'Waltermart', 'Jaycee Irene', 'Silvestre', 'Jaycee.Silvestre@walterph.com', 'Dominga I I I2113 Chino Roces Avenue Corner Dela Rosa Street1231, Makati City', '09852073747', 'Warehouse Manager'),
(8, 'Southstar Drug', 'Laura Stacy', 'Montecillo', 'Laura.Montecillo@southdrug.com', '5/F Transphil House Chino Roces Avenue Corner Bagtikan StreetsSan Antonio Village 1200, Makati City', '09528211667', 'Product Manager'),
(9, 'DLSU', 'Bernie', 'Oca', 'bernie.oca@dlsu.edu.ph', '4/F Aguada Inc. Building B 7 L 30 Merville Access Road Kalayaan 1300 Pasay City', '09277364773', 'President'),
(10, 'Ice Box Trading', 'Terry', 'Coo', 'tcoo@ibt.com', '1122 South, Ninoy Aquino Drive, Paranaque', '2255473', 'General Manager'),
(11, 'Infinity at Home', 'Adrian', 'Lazo', 'adlzo@iahome.com', '7787 Unit 506, Village East, Cainta', '7879954', 'CMO'),
(12, 'The palace', 'rodrigo', 'duterte', 'president@palace.gov.ph', 'the palace complex jp laurel street san miguel manila', '7644206', 'president');

-- --------------------------------------------------------

--
-- Table structure for table `ingredient`
--

CREATE TABLE `ingredient` (
  `ingredientID` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ingredient`
--

INSERT INTO `ingredient` (`ingredientID`, `quantity`, `name`) VALUES
(1, 601, 'Argan Oil'),
(2, 10000100, 'Coconut Oil'),
(3, 300000, 'Olive Oil'),
(4, 0, 'Lemon Grass'),
(5, 387, 'Peppermint'),
(6, 0.799802, 'Rose Hip'),
(7, 177, 'Shea Butter'),
(8, -0.00000119209, 'Citric Acid'),
(9, 0, 'Citronella Oil');

-- --------------------------------------------------------

--
-- Table structure for table `joborder`
--

CREATE TABLE `joborder` (
  `orderID` int(11) NOT NULL,
  `customerID` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `dueDate` date NOT NULL,
  `totalPrice` float NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `type` varchar(45) NOT NULL,
  `status` text NOT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `joborder`
--

INSERT INTO `joborder` (`orderID`, `customerID`, `orderDate`, `dueDate`, `totalPrice`, `remarks`, `type`, `status`, `createdBy`) VALUES
(7, 1, '2019-03-24', '2019-04-05', 18500, '', 'Made to Stock', 'For Out', 1),
(8, 10, '2019-01-24', '2019-02-07', 68000, '', 'Made to Order', 'Finished', 3),
(9, 1, '2019-01-26', '2019-02-07', 23500, '', 'Made to Stock', 'For Out', 3),
(10, 11, '2019-02-20', '2019-03-06', 30800, '', 'Made to Order', 'Finished', 1),
(11, 1, '2019-02-20', '2019-03-04', 131600, '', 'Made to Stock', 'For Out', 6),
(12, 1, '2019-03-24', '2019-04-05', 2530, '', 'Made to Stock', 'For Out', 1),
(13, 9, '2019-03-24', '2019-04-09', 16000, '', 'Made to Order', 'Finished', 3),
(14, 1, '2019-03-24', '2019-04-05', 4400, '', 'Made to Stock', 'removed', 3),
(15, 5, '2019-03-24', '2019-04-09', 22000, '', 'Made to Order', 'For Out', 3),
(16, 6, '2019-03-25', '2019-04-09', 1800, '', 'Made to Order', 'removed', 3),
(17, 9, '2019-03-25', '2019-04-09', 16000, '', 'Made to Order', 'Finished', 3),
(18, 1, '2019-03-25', '2019-04-12', 18500, '', 'Made to Stock', 'removed', 3),
(19, 9, '2019-03-25', '2019-04-10', 16000, '', 'Made to Order', 'Finished', 3),
(20, 1, '2019-03-24', '2019-04-05', 18500, '', 'Made to Stock', 'Pending for approval', 3),
(21, 12, '2019-04-02', '2019-04-18', 16000, '', 'Made to Order', 'Finished', 3),
(22, 9, '2019-05-14', '2019-05-31', 22000, '', 'Made to Order', 'Shipping', 1),
(23, 9, '2019-06-11', '2019-06-27', 333320, '', 'Made to Order', 'For Out', 1),
(24, 9, '2019-06-13', '2019-06-29', 3333200, '', 'Made to Order', 'Pending for approval', 1),
(25, 10, '2019-06-17', '2019-07-03', 333320, '', 'Made to Order', 'Shipping', 1),
(26, 0, '2019-07-01', '0000-00-00', 666640, '', 'Made to Order', 'Pending for approval', 1),
(27, 0, '2019-07-01', '0000-00-00', 37031900, '', 'Made to Order', 'Pending for approval', 1),
(28, 0, '2019-07-01', '0000-00-00', 7399700, '', 'Made to Order', 'Pending for approval', 1),
(29, 0, '2019-07-01', '0000-00-00', 7399700, '', 'Made to Order', 'Pending for approval', 1),
(30, 0, '2019-07-01', '0000-00-00', 99996, '', 'Made to Order', 'Pending for approval', 1),
(31, 0, '2019-07-01', '0000-00-00', 40698400, '', 'Made to Order', 'Pending for approval', 1),
(32, 0, '2019-07-01', '0000-00-00', 404017000, '', 'Made to Order', 'Pending for approval', 1),
(33, 0, '2019-07-01', '0000-00-00', 4066500, '', 'Made to Order', 'Pending for approval', 1),
(34, 9, '2019-07-04', '2019-07-24', 4040540000, '', 'Made to Order', 'Pending for approval', 1),
(35, 2, '2019-07-04', '2019-07-20', 3299870, '', 'Made to Order', 'Pending for approval', 1),
(36, 9, '2019-07-05', '2019-07-21', 3299870, '', 'Made to Order', 'Pending for approval', 1),
(37, 9, '2019-07-11', '2019-07-28', 33298700, '', 'Made to Order', 'Pending for approval', 1);

-- --------------------------------------------------------

--
-- Table structure for table `machine`
--

CREATE TABLE `machine` (
  `machineID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `acquiredDate` date NOT NULL,
  `status` varchar(45) NOT NULL,
  `hoursWorked` int(11) NOT NULL,
  `lifetimeWorked` int(11) NOT NULL,
  `processTypeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `machine`
--

INSERT INTO `machine` (`machineID`, `name`, `acquiredDate`, `status`, `hoursWorked`, `lifetimeWorked`, `processTypeID`) VALUES
(1, 'Bosch Mixer', '2013-06-11', 'Available', 1213045, 107065, 1),
(2, 'Bosch Extruding Machine', '2014-10-22', 'Available', 920, 63620, 7),
(3, 'Honeywell Stamping Machine', '2011-07-29', 'Available', 27400, 27400, 8),
(4, 'Honeywell Mixer', '2016-09-20', 'Available', 74520, 74520, 1),
(5, 'DeWalt Pouring Machine', '2014-03-05', 'Available', 98440, 98440, 2),
(6, 'Bechtel Curing Machine', '2016-04-01', 'Available', 65290, 68690, 3),
(7, 'Kobalt Detaching Machine', '2015-09-22', 'Available', 25000, 25000, 4),
(8, 'Terex Cutter', '2013-08-31', 'Available', 65890, 65890, 5),
(9, 'Terex Sieving Machine', '2014-05-21', 'Available', 18500, 18500, 10),
(10, 'Trimaco Roller', '2013-01-14', 'Available', 192880, 192880, 6),
(11, 'Trimaco Granulating Machine', '2014-02-18', 'Available', 41000, 41000, 9);

-- --------------------------------------------------------

--
-- Table structure for table `maintenancetransaction`
--

CREATE TABLE `maintenancetransaction` (
  `transactionID` int(11) NOT NULL,
  `machineID` int(11) NOT NULL,
  `maintenanceCost` float NOT NULL,
  `maintenanceDate` date NOT NULL,
  `problemIdentified` text NOT NULL,
  `solution` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `maintenancetransaction`
--

INSERT INTO `maintenancetransaction` (`transactionID`, `machineID`, `maintenanceCost`, `maintenanceDate`, `problemIdentified`, `solution`) VALUES
(1, 6, 500, '2019-03-19', 'n/a', 'n/a'),
(2, 2, 12000, '2019-03-07', 'bearing', 'bearing replaced');

-- --------------------------------------------------------

--
-- Table structure for table `poitem`
--

CREATE TABLE `poitem` (
  `purchaseOrderID` int(11) NOT NULL,
  `rawMaterialID` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `quantityShipped` float NOT NULL,
  `defective` float NOT NULL,
  `subTotal` float NOT NULL,
  `unitOfMeasurement` varchar(45) NOT NULL,
  `deliveryReceipt` int(11) NOT NULL,
  `status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `poitem`
--

INSERT INTO `poitem` (`purchaseOrderID`, `rawMaterialID`, `quantity`, `quantityShipped`, `defective`, `subTotal`, `unitOfMeasurement`, `deliveryReceipt`, `status`) VALUES
(11, 6, 10, 0, 0, 350, 'Kilogram', 1111, 'Delivered'),
(12, 8, 1, 0, 0, 195, 'Liter', 1122, 'Delivered'),
(13, 4, 37, 0, 0, 3145, 'Kilogram', 7711, 'Delivered'),
(14, 12, 188, 0, 0, 14476, 'Liter', 7700, 'Delivered'),
(15, 8, 45, 0, 4, 8775, 'Liter', 9799, 'Delivered'),
(16, 8, 3, 0, 0, 585, 'Liter', 98799, 'Delivered'),
(17, 1, 95, 0, 2, 11875, 'Liter', 9979, 'Delivered'),
(18, 8, 158, 0, 7, 30810, 'Liter', 12344, 'Delivered'),
(19, 1, 2, 0, 0, 250, 'Liter', 8788, 'Delivered'),
(20, 8, 6, 0, 0, 1170, 'Liter', 2243, 'Delivered'),
(21, 5, 265, 0, 0, 39750, 'Kilogram', 99979, 'Delivered'),
(22, 8, 253, 0, 0, 49335, 'Liter', 888997, 'Delivered'),
(23, 4, 3, 0, 0, 255, 'Kilogram', 45564, 'Delivered'),
(24, 8, 2, 0, 0, 390, 'Liter', 376675, 'Delivered'),
(25, 5, 454, 0, 0, 68100, 'Kilogram', 577823, 'Delivered'),
(26, 8, 15, 0, 0, 2925, 'Liter', 32495, 'Delivered'),
(27, 1, 15, 0, 2, 1875, 'Liter', 23984, 'Delivered'),
(28, 4, 5, 0, 0, 425, 'Kilogram', 84592, 'Delivered'),
(29, 1, 2, 0, 0, 250, 'Liter', 35647, 'Delivered'),
(30, 8, 15, 0, 0, 2925, 'Liter', 63849, 'Delivered'),
(31, 1, 15, 0, 2, 1875, 'Liter', 23985, 'Delivered'),
(32, 4, 5, 0, 0, 425, 'Kilogram', 98374, 'Delivered'),
(33, 8, 15, 0, 0, 2925, 'Liter', 0, 'Not Delivered'),
(34, 1, 15, 0, 0, 1875, 'Liter', 0, 'Not Delivered'),
(35, 4, 5, 0, 0, 425, 'Kilogram', 87639, 'Delivered'),
(36, 1, 2, 0, 0, 250, 'Liter', 97863, 'Delivered'),
(37, 8, 15, 0, 0, 2925, 'Liter', 32495, 'Delivered'),
(38, 1, 15, 0, 2, 1875, 'Liter', 23984, 'Delivered'),
(39, 4, 5, 0, 0, 425, 'Kilogram', 84592, 'Delivered'),
(40, 1, 2, 0, 0, 250, 'Liter', 35647, 'Delivered'),
(41, 1, 15, 0, 2, 1875, 'Liter', 23984, 'Delivered'),
(42, 4, 5, 0, 0, 425, 'Kilogram', 84592, 'Delivered'),
(43, 8, 15, 0, 0, 2925, 'Liter', 32495, 'Delivered'),
(44, 1, 2, 0, 0, 250, 'Liter', 35647, 'Delivered'),
(45, 1, 46, 0, 0, 5750, 'Liter', 21, 'Delivered'),
(46, 2, 11, 0, 0, 550, 'Liter', 412, 'Delivered'),
(47, 6, 24, 0, 0, 840, 'Kilogram', 3123, 'Delivered'),
(48, 8, 51, 0, 0, 9945, 'Liter', 23123, 'Delivered'),
(49, 3, 3, 0, 0, 210, 'Liter', 12, 'Delivered'),
(50, 7, 15, 0, 10, 225, 'Kilogram', 12312, 'Delivered'),
(50, 8, 25, 0, 0, 4875, 'Liter', 111, 'Delivered'),
(51, 3, 11, 0, 0, 770, 'Liter', 1111, 'Delivered'),
(52, 1, 99, 0, 0, 12375, 'Liter', 0, 'Not Delivered'),
(53, 3, 333, 0, 0, 23310, 'Liter', 0, 'Not Delivered'),
(54, 1, 8, 0, 0, 1000, 'Liter', 0, 'Not Delivered'),
(55, 2, 10, 0, 0, 500, 'Liter', 0, 'Not Delivered'),
(56, 4, 16, 0, 10, 1360, 'Kilogram', 0, 'Delivered'),
(57, 6, 22, 0, 10, 770, 'Kilogram', 2412412, 'Delivered'),
(58, 1, 100, 0, 100, 12500, 'Liter', 54321, 'Not Delivered'),
(59, 1, 100, 0, 5, 12500, 'Liter', 1231, 'Delivered'),
(60, 6, 100, -9900, 10090, 3500, 'Kilogram', 41324134, 'Not Delivered'),
(61, 10, 100, 70, 130, 12500, 'Kilogram', 241, 'Not Delivered'),
(62, 9, 100, 0, 0, 6500, 'Liter', 0, 'Not Delivered'),
(63, 1, 100, 100, 100, 12500, 'Liter', 124124, 'Not Delivered'),
(64, 10, 50, 62, 38, 6250, 'Kilogram', 1421, 'Not Delivered'),
(65, 1, 100, -45, 45, 12500, 'Liter', 123, 'Not Delivered'),
(66, 7, 100, 100, 170, 1500, 'Kilogram', 12312, 'Not Delivered'),
(67, 2, 100, 100, 200, 5000, 'Liter', 0, 'Not Delivered'),
(68, 7, 40, 40, 80, 600, 'Kilogram', 12412412, 'Delivered'),
(69, 1, 100, 0, 0, 12500, 'Liter', 0, 'Not Delivered'),
(69, 2, 20, 0, 0, 1000, 'Liter', 0, 'Not Delivered'),
(70, 4, 44, 44, 0, 3740, 'Kilogram', 12312312, 'Delivered'),
(71, 6, 9800, 9800, 9800, 343000, 'Kilogram', 12541241, 'Delivered'),
(72, 8, 54, 54, 0, 10530, 'Liter', 4124, 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `processtype`
--

CREATE TABLE `processtype` (
  `processTypeID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `processtype`
--

INSERT INTO `processtype` (`processTypeID`, `name`) VALUES
(1, 'Mixing'),
(2, 'Pouring'),
(3, 'Curing'),
(4, 'Detaching'),
(5, 'Cutting'),
(6, 'Rolling'),
(7, 'Extruding'),
(8, 'Stamping'),
(9, 'Granulating'),
(10, 'Sieving');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reorderPoint` int(11) NOT NULL,
  `productPrice` float NOT NULL,
  `unitOfMeasurement` varchar(45) NOT NULL,
  `productTypeID` int(11) NOT NULL,
  `custom` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `name`, `quantity`, `reorderPoint`, `productPrice`, `unitOfMeasurement`, `productTypeID`, `custom`) VALUES
(1, 'Avocado Beauty Soap', 0, 0, 110, 'Pieces', 1, 0),
(2, 'Dayap Beauty Soap', 30, 0, 110, 'Pieces', 1, 0),
(3, 'Papaya Whitening Soap', 49, 0, 120, 'Pieces', 2, 0),
(4, 'Coco Loco Applemint Soap', 100, 0, 110, 'Pieces', 1, 0),
(5, 'Rosenmint Aromatherapy Oil', 54, 0, 185, 'Pieces', 3, 0),
(6, 'Rosenlav Aromatherapy Oil', 0, 0, 185, 'Pieces', 3, 0),
(7, 'Herbal Body Oil', 20, 0, 100, 'Pieces', 4, 0),
(8, 'Gugo Liquid Shampoo', 234, 0, 150, 'Pieces', 5, 0),
(9, 'Gugo Bar Shampoo', 300, 0, 120, 'Pieces', 5, 0),
(10, 'Victoria Laundry Powder', 442, 0, 235, 'Pieces', 6, 0),
(11, 'Citronella Magic', 0, 0, 170, 'Liter', 3, 1),
(12, 'Coffee Kojic', 0, 0, 88, 'Pieces', 1, 1),
(13, 'La Salle Shampoo', 3, 0, 120, 'Pieces', 5, 1),
(15, 'Dove Beauty Soap', 0, 0, 12, 'Pieces', 1, 1),
(17, 'the palace beauty soap', 1, 0, 40, 'Pieces', 1, 1),
(18, 'the palace  shampoo', 1, 0, 120, 'Pieces', 5, 1),
(19, '222', 1000, 0, 33332, 'Pieces', 1, 0),
(20, 'kamote', 0, 0, 123, 'Pieces', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `production`
--

CREATE TABLE `production` (
  `orderID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `status` varchar(45) NOT NULL,
  `quantity` int(11) NOT NULL,
  `totalGoods` int(11) NOT NULL,
  `totalYield` int(11) NOT NULL,
  `totalLost` int(11) NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `production`
--

INSERT INTO `production` (`orderID`, `productID`, `status`, `quantity`, `totalGoods`, `totalYield`, `totalLost`, `startDate`, `endDate`) VALUES
(7, 5, 'Finished', 100, 97, 101, 4, '2019-03-24 17:02:17', '2019-03-24 17:02:41'),
(8, 11, 'Finished', 400, 400, 404, 4, '2019-01-24 17:08:40', '2019-01-26 17:09:42'),
(9, 10, 'Finished', 100, 100, 101, 1, '2019-01-26 17:11:54', '2019-02-20 17:12:44'),
(10, 12, 'Finished', 350, 350, 354, 4, '2019-02-20 17:19:31', '2019-02-20 17:19:51'),
(11, 10, 'Finished', 560, 562, 566, 4, '2019-02-20 17:23:42', '2019-02-25 17:24:45'),
(12, 4, 'Finished', 23, 22, 23, 1, '2019-03-24 17:29:09', '2019-03-24 17:29:45'),
(13, 13, 'Finished', 100, 101, 101, 0, '2019-03-25 00:48:07', '2019-03-25 00:49:05'),
(13, 14, 'Finished', 100, 101, 101, 0, '2019-03-25 00:48:08', '2019-03-25 00:49:19'),
(17, 13, 'Finished', 100, 101, 101, 0, '2019-03-25 11:29:15', '2019-03-25 11:51:34'),
(17, 14, 'Finished', 100, 101, 101, 0, '2019-03-25 11:29:16', '2019-03-25 12:03:04'),
(19, 13, 'Finished', 100, 101, 101, 0, '2019-03-25 14:32:31', '2019-03-25 14:33:28'),
(19, 16, 'Finished', 100, 101, 101, 0, '2019-03-25 14:32:32', '2019-03-25 14:34:15'),
(21, 17, 'Finished', 100, 101, 101, 0, '2019-04-02 11:35:35', '2019-04-02 11:37:11'),
(21, 18, 'Finished', 100, 101, 101, 0, '2019-04-02 11:35:35', '2019-04-02 11:37:21'),
(15, 3, 'Finished', 100, 101, 101, 0, '2019-05-14 13:39:14', '2019-05-14 13:39:36'),
(15, 7, 'Finished', 100, 101, 101, 0, '0000-00-00 00:00:00', '2019-05-14 13:39:41'),
(23, 19, 'Finished', 10, 10, 10, 0, '2019-06-11 15:33:40', '2019-06-11 15:33:50'),
(25, 19, 'Finished', 10, 10, 10, 0, '2019-06-17 15:44:28', '2019-06-17 15:44:38'),
(22, 1, 'Finished', 200, 202, 202, 0, '2019-07-15 16:37:37', '2019-07-15 16:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `productionprocess`
--

CREATE TABLE `productionprocess` (
  `orderID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `processTypeID` int(11) NOT NULL,
  `machineID` int(11) NOT NULL,
  `machineQueue` int(11) NOT NULL,
  `processSequence` int(11) NOT NULL,
  `timeEstimate` float NOT NULL,
  `status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productionprocess`
--

INSERT INTO `productionprocess` (`orderID`, `productID`, `processTypeID`, `machineID`, `machineQueue`, `processSequence`, `timeEstimate`, `status`) VALUES
(7, 5, 9, 11, 0, 1, 5000, 'Shipping'),
(7, 5, 10, 9, 0, 2, 4500, 'Shipping'),
(7, 5, 4, 7, 0, 3, 3000, 'Shipping'),
(8, 11, 1, 4, 0, 1, 4000, 'Shipping'),
(8, 11, 3, 6, 0, 2, 4400, 'Shipping'),
(8, 11, 2, 5, 0, 3, 22800, 'Shipping'),
(9, 10, 2, 5, 0, 1, 8000, 'Shipping'),
(9, 10, 1, 4, 0, 2, 4500, 'Shipping'),
(9, 10, 7, 2, 0, 3, 9500, 'Shipping'),
(9, 10, 9, 11, 0, 4, 5000, 'Shipping'),
(10, 12, 1, 4, 0, 1, 12600, 'Shipping'),
(10, 12, 3, 6, 0, 2, 24500, 'Shipping'),
(10, 12, 5, 8, 0, 3, 5250, 'Shipping'),
(10, 12, 2, 5, 0, 4, 7000, 'Shipping'),
(11, 10, 2, 5, 0, 1, 44800, 'Shipping'),
(11, 10, 1, 4, 0, 2, 25200, 'Shipping'),
(11, 10, 7, 2, 0, 3, 53200, 'Shipping'),
(11, 10, 9, 11, 0, 4, 28000, 'Shipping'),
(12, 4, 2, 5, 0, 1, 1840, 'Shipping'),
(12, 4, 3, 6, 0, 2, 1150, 'Shipping'),
(12, 4, 7, 2, 0, 3, 920, 'Shipping'),
(13, 13, 2, 5, 0, 1, 2500, 'Shipping'),
(13, 13, 3, 6, 0, 2, 1200, 'Shipping'),
(13, 13, 6, 10, 0, 3, 6200, 'Shipping'),
(13, 14, 1, 4, 0, 1, 7200, 'Shipping'),
(13, 14, 10, 9, 0, 2, 3500, 'Shipping'),
(13, 14, 4, 7, 0, 3, 5500, 'Shipping'),
(13, 14, 8, 3, 0, 4, 4600, 'Shipping'),
(17, 13, 2, 5, 0, 1, 2500, 'Shipping'),
(17, 13, 3, 6, 0, 2, 1200, 'Shipping'),
(17, 13, 6, 10, 0, 3, 6200, 'Shipping'),
(17, 14, 1, 1, 0, 1, 7200, 'Shipping'),
(17, 14, 10, 9, 0, 2, 3500, 'Shipping'),
(17, 14, 4, 7, 0, 3, 5500, 'Shipping'),
(17, 14, 8, 3, 0, 4, 4600, 'Shipping'),
(19, 13, 2, 5, 0, 1, 2500, 'Shipping'),
(19, 13, 3, 6, 0, 2, 1200, 'Shipping'),
(19, 13, 6, 10, 0, 3, 6200, 'Shipping'),
(19, 16, 1, 1, 0, 1, 7200, 'Shipping'),
(19, 16, 10, 9, 0, 2, 3500, 'Shipping'),
(19, 16, 4, 7, 0, 3, 5500, 'Shipping'),
(19, 16, 8, 3, 0, 4, 4600, 'Shipping'),
(21, 17, 1, 4, 0, 1, 7200, 'Shipping'),
(21, 17, 10, 9, 0, 2, 3500, 'Shipping'),
(21, 17, 4, 7, 0, 3, 5500, 'Shipping'),
(21, 17, 8, 3, 0, 4, 4600, 'Shipping'),
(21, 18, 2, 5, 0, 1, 2500, 'Shipping'),
(21, 18, 3, 6, 0, 2, 1200, 'Shipping'),
(21, 18, 6, 10, 0, 3, 6200, 'Shipping'),
(15, 3, 1, 1, 0, 1, 6000, 'Shipping'),
(15, 3, 8, 3, 0, 2, 3000, 'Shipping'),
(15, 3, 5, 8, 0, 3, 6000, 'Shipping'),
(15, 7, 2, 5, 0, 1, 4000, 'Shipping'),
(15, 7, 6, 10, 0, 2, 3000, 'Shipping'),
(15, 7, 8, 3, 0, 3, 2000, 'Shipping'),
(15, 7, 9, 11, 0, 4, 3000, 'Shipping'),
(23, 19, 1, 1, 0, 1, 820, 'Shipping'),
(25, 19, 1, 4, 0, 1, 820, 'Shipping'),
(22, 1, 1, 1, 0, 1, 13000, 'Shipping'),
(22, 1, 3, 6, 0, 2, 3400, 'Shipping'),
(22, 1, 6, 10, 0, 3, 20800, 'Shipping'),
(22, 1, 5, 8, 0, 4, 6400, 'Shipping');

-- --------------------------------------------------------

--
-- Table structure for table `productprocess`
--

CREATE TABLE `productprocess` (
  `productID` int(11) NOT NULL,
  `processTypeID` int(11) NOT NULL,
  `processSequence` int(11) NOT NULL,
  `timeNeed` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productprocess`
--

INSERT INTO `productprocess` (`productID`, `processTypeID`, `processSequence`, `timeNeed`) VALUES
(1, 1, 1, 65),
(1, 3, 2, 17),
(1, 6, 3, 104),
(1, 5, 4, 32),
(2, 1, 1, 190),
(2, 3, 2, 80),
(2, 5, 3, 80),
(3, 1, 1, 60),
(3, 8, 2, 30),
(3, 5, 3, 60),
(4, 2, 1, 80),
(4, 3, 2, 50),
(4, 7, 3, 40),
(5, 9, 1, 50),
(5, 10, 2, 45),
(5, 4, 3, 30),
(6, 2, 1, 30),
(6, 4, 2, 50),
(6, 9, 3, 30),
(6, 10, 4, 30),
(7, 2, 1, 40),
(7, 6, 2, 30),
(7, 8, 3, 20),
(7, 9, 4, 30),
(8, 2, 1, 70),
(8, 4, 2, 30),
(8, 7, 3, 20),
(8, 6, 4, 70),
(9, 3, 1, 80),
(9, 5, 2, 60),
(9, 6, 3, 70),
(9, 8, 4, 40),
(10, 2, 1, 80),
(10, 1, 2, 45),
(10, 7, 3, 95),
(10, 9, 4, 50),
(11, 1, 1, 10),
(11, 3, 2, 11),
(11, 2, 3, 57),
(12, 1, 1, 36),
(12, 3, 2, 70),
(12, 5, 3, 15),
(12, 2, 4, 20),
(13, 2, 1, 25),
(13, 3, 2, 12),
(13, 6, 3, 62),
(14, 1, 1, 72),
(14, 10, 2, 35),
(14, 4, 3, 55),
(14, 8, 4, 46),
(15, 1, 1, 10),
(15, 2, 2, 12),
(15, 3, 3, 15),
(16, 1, 1, 72),
(16, 10, 2, 35),
(16, 4, 3, 55),
(16, 8, 4, 46),
(17, 1, 1, 72),
(17, 10, 2, 35),
(17, 4, 3, 55),
(17, 8, 4, 46),
(18, 2, 1, 25),
(18, 3, 2, 12),
(18, 6, 3, 62),
(19, 1, 1, 82),
(20, 1, 1, 70);

-- --------------------------------------------------------

--
-- Table structure for table `productsales`
--

CREATE TABLE `productsales` (
  `productID` int(11) NOT NULL,
  `salesID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subTotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productsales`
--

INSERT INTO `productsales` (`productID`, `salesID`, `quantity`, `subTotal`) VALUES
(11, 9, 400, 68000),
(10, 10, 70, 16450),
(12, 12, 350, 30800),
(13, 15, 100, 12000),
(14, 15, 100, 4000),
(13, 17, 100, 12000),
(14, 17, 100, 4000),
(13, 19, 100, 12000),
(16, 19, 100, 4000),
(17, 21, 100, 4000),
(18, 21, 100, 12000),
(10, 22, 150, 35250),
(1, 26, 10, 1100),
(5, 26, 30, 5550),
(5, 27, 10, 1850);

-- --------------------------------------------------------

--
-- Table structure for table `producttype`
--

CREATE TABLE `producttype` (
  `productTypeID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `producttype`
--

INSERT INTO `producttype` (`productTypeID`, `name`) VALUES
(1, 'Beauty Soap'),
(2, 'Whitening Soap'),
(3, 'Aromatherapy Oil'),
(4, 'Body Oil'),
(5, 'Shampoo'),
(6, 'Laundry Powder');

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorder`
--

CREATE TABLE `purchaseorder` (
  `purchaseOrderID` int(11) NOT NULL,
  `supplierID` int(11) NOT NULL,
  `totalPrice` float NOT NULL,
  `orderDate` date NOT NULL,
  `status` text NOT NULL,
  `deadline` date NOT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchaseorder`
--

INSERT INTO `purchaseorder` (`purchaseOrderID`, `supplierID`, `totalPrice`, `orderDate`, `status`, `deadline`, `createdBy`) VALUES
(11, 4, 350, '2019-03-24', 'Completed!', '2019-03-31', 1),
(12, 5, 195, '2019-03-24', 'Completed!', '2019-03-31', 1),
(13, 2, 3145, '2019-01-24', 'Completed!', '2019-01-30', 3),
(14, 2, 14476, '2019-01-24', 'Completed!', '2019-01-30', 3),
(15, 5, 8775, '2019-01-26', 'Completed!', '2019-02-02', 3),
(16, 5, 585, '2019-01-26', 'Completed!', '2019-02-02', 3),
(17, 1, 11875, '2019-02-20', 'Completed!', '2019-02-23', 1),
(18, 5, 30810, '2019-02-20', 'Completed!', '2019-02-27', 1),
(19, 1, 250, '2019-02-20', 'Completed!', '2019-02-23', 1),
(20, 5, 1170, '2019-02-20', 'Completed!', '2019-02-27', 1),
(21, 2, 39750, '2019-02-20', 'Completed!', '2019-02-26', 3),
(22, 5, 49335, '2019-02-20', 'Completed!', '2019-02-27', 3),
(23, 2, 255, '2019-03-24', 'Completed!', '2019-03-30', 1),
(24, 5, 390, '2019-03-24', 'Completed!', '2019-03-31', 1),
(25, 2, 68100, '2019-03-24', 'Completed!', '2019-03-30', 3),
(26, 5, 2925, '2019-03-24', 'Completed!', '2019-03-31', 3),
(27, 1, 1875, '2019-03-24', 'Completed!', '2019-03-27', 3),
(28, 2, 425, '2019-03-24', 'Completed!', '2019-03-30', 3),
(29, 1, 250, '2019-03-25', 'Completed!', '2019-03-28', 3),
(30, 5, 2925, '2019-03-25', 'Completed!', '2019-04-01', 3),
(31, 1, 1875, '2019-03-25', 'Completed!', '2019-03-28', 3),
(32, 2, 425, '2019-03-25', 'Completed!', '2019-03-31', 3),
(33, 5, 2925, '2019-03-25', 'removed', '2019-04-01', 3),
(34, 1, 1875, '2019-03-25', 'removed', '2019-03-28', 3),
(35, 2, 425, '2019-03-25', 'Completed!', '2019-03-31', 3),
(36, 1, 250, '2019-03-25', 'Completed!', '2019-03-28', 3),
(37, 5, 2925, '2019-03-25', 'Completed!', '2019-04-01', 3),
(38, 1, 1875, '2019-03-25', 'Completed!', '2019-03-28', 3),
(39, 2, 425, '2019-03-25', 'Completed!', '2019-03-31', 3),
(40, 1, 250, '2019-03-25', 'Completed!', '2019-03-28', 3),
(41, 1, 1875, '2019-04-02', 'Completed!', '2019-04-05', 3),
(42, 2, 425, '2019-04-02', 'Completed!', '2019-04-08', 3),
(43, 5, 2925, '2019-04-02', 'Completed!', '2019-04-09', 3),
(44, 1, 250, '2019-04-02', 'Completed!', '2019-04-05', 3),
(45, 1, 5750, '2019-05-14', 'Completed!', '2019-05-17', 1),
(46, 1, 550, '2019-05-14', 'Completed!', '2019-05-17', 1),
(47, 4, 840, '2019-05-14', 'Completed!', '2019-05-21', 1),
(48, 5, 9945, '2019-05-14', 'Completed!', '2019-05-21', 1),
(49, 2, 210, '2019-06-11', 'Completed!', '2019-06-17', 1),
(50, 5, 5100, '2019-06-11', 'Completed!', '2019-06-18', 1),
(51, 2, 770, '2019-06-17', 'Completed!', '2019-06-23', 1),
(52, 1, 12375, '2019-07-04', 'removed', '2019-07-07', 1),
(53, 2, 23310, '2019-07-08', 'removed', '2019-07-14', 1),
(54, 1, 1000, '2019-07-13', 'removed', '2019-07-16', 1),
(55, 1, 500, '2019-07-13', 'removed', '2019-07-16', 1),
(56, 2, 1360, '2019-07-13', 'Completed!', '2019-07-19', 1),
(57, 4, 770, '2019-07-13', 'Completed!', '2019-07-20', 1),
(58, 1, 12500, '2019-07-13', 'removed', '2019-07-16', 1),
(59, 1, 12500, '2019-07-13', 'Completed!', '2019-07-16', 1),
(60, 4, 3500, '2019-07-15', 'removed', '2019-07-22', 1),
(61, 7, 12500, '2019-07-15', 'removed', '2019-07-22', 1),
(62, 6, 6500, '2019-07-15', 'removed', '2019-07-20', 1),
(63, 1, 12500, '2019-07-15', 'removed', '2019-07-18', 1),
(64, 7, 6250, '2019-07-15', 'removed', '2019-07-22', 1),
(65, 1, 12500, '2019-07-15', 'removed', '2019-07-18', 1),
(66, 5, 1500, '2019-07-15', 'removed', '2019-07-22', 1),
(67, 1, 5000, '2019-07-15', 'removed', '2019-07-18', 1),
(68, 5, 600, '2019-07-15', 'Completed!', '2019-07-22', 1),
(69, 1, 13500, '2019-07-15', 'Pending', '2019-07-18', 1),
(70, 2, 3740, '2019-07-15', 'Completed!', '2019-07-21', 1),
(71, 4, 343000, '2019-07-15', 'Completed!', '2019-07-22', 1),
(72, 5, 10530, '2019-07-15', 'Completed!', '2019-07-22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rawmaterial`
--

CREATE TABLE `rawmaterial` (
  `rawMaterialID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `supplierID` int(11) NOT NULL,
  `pricePerUnit` float NOT NULL,
  `unitOfMeasurement` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rawmaterial`
--

INSERT INTO `rawmaterial` (`rawMaterialID`, `name`, `supplierID`, `pricePerUnit`, `unitOfMeasurement`) VALUES
(1, 'HHN Argan Oil', 1, 125, 'Liter'),
(2, 'HHN Coconut Oil', 1, 50, 'Liter'),
(3, 'ASL Olive Oil', 2, 70, 'Liter'),
(4, 'ASL Lemon Grass', 2, 85, 'Kilogram'),
(5, 'ASL Peppermint', 2, 150, 'Kilogram'),
(6, 'RT Rosehip', 4, 35, 'Kilogram'),
(7, 'TAM Shea Butter', 5, 15, 'Kilogram'),
(8, 'TAM Citric Acid', 5, 195, 'Liter'),
(9, 'KI Olive Oil', 6, 65, 'Liter'),
(10, 'CST Peppermint', 7, 125, 'Kilogram'),
(11, 'CST Coconut Oil', 7, 100, 'Liter'),
(12, 'Citronella Magic', 2, 77, 'Liter');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `orderID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subTotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receipt`
--

INSERT INTO `receipt` (`orderID`, `productID`, `quantity`, `subTotal`) VALUES
(7, 5, 100, 18500),
(8, 11, 400, 68000),
(9, 10, 100, 23500),
(10, 12, 350, 30800),
(11, 10, 560, 131600),
(12, 4, 23, 2530),
(13, 14, 100, 4000),
(13, 13, 100, 12000),
(14, 1, 40, 4400),
(15, 7, 100, 10000),
(15, 3, 100, 12000),
(16, 15, 150, 1800),
(17, 14, 100, 4000),
(17, 13, 100, 12000),
(18, 6, 100, 18500),
(19, 16, 100, 4000),
(19, 13, 100, 12000),
(20, 6, 100, 18500),
(21, 17, 100, 4000),
(21, 18, 100, 12000),
(22, 1, 200, 22000),
(23, 19, 10, 333320),
(24, 19, 100, 3333200),
(25, 19, 10, 333320),
(26, 19, 20, 666640),
(27, 19, 1111, 37031900),
(28, 19, 222, 7399700),
(29, 19, 222, 7399700),
(30, 19, 3, 99996),
(31, 19, 1221, 40698400),
(32, 19, 12121, 404017000),
(33, 19, 122, 4066500),
(34, 19, 121221, 4040540000),
(35, 19, 99, 3299870),
(36, 19, 99, 3299870),
(37, 19, 999, 33298700);

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `ingredientID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `unitOfMeasurement` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`ingredientID`, `productID`, `quantity`, `unitOfMeasurement`) VALUES
(2, 1, 0.05, 'Liter'),
(4, 1, 0.25, 'Kilogram'),
(6, 1, 0.61, 'Kilogram'),
(8, 1, 0.4, 'Liter'),
(2, 2, 0.75, 'Liter'),
(3, 2, 0.65, 'Liter'),
(5, 2, 0.55, 'Kilogram'),
(7, 2, 0.1, 'Kilogram'),
(3, 3, 0.1, 'Liter'),
(1, 3, 0.45, 'Liter'),
(5, 3, 0.35, 'Kilogram'),
(7, 3, 0.1, 'Kilogram'),
(7, 4, 0.1, 'Kilogram'),
(3, 4, 0.15, 'Liter'),
(4, 4, 0.12, 'Kilogram'),
(8, 4, 0.09, 'Liter'),
(2, 5, 0.04, 'Liter'),
(5, 5, 0.05, 'Kilogram'),
(6, 5, 0.1, 'Kilogram'),
(1, 5, 0.03, 'Liter'),
(8, 5, 0.05, 'Liter'),
(2, 6, 0.09, 'Liter'),
(1, 6, 0.09, 'Liter'),
(4, 6, 0.15, 'Kilogram'),
(6, 6, 0.22, 'Kilogram'),
(5, 6, 0.05, 'Kilogram'),
(2, 7, 0.26, 'Liter'),
(6, 7, 0.24, 'Kilogram'),
(8, 7, 0.5, 'Liter'),
(3, 8, 0.2, 'Liter'),
(4, 8, 0.18, 'Kilogram'),
(6, 8, 0.3, 'Kilogram'),
(1, 9, 0.2, 'Liter'),
(4, 9, 0.15, 'Kilogram'),
(6, 9, 0.12, 'Kilogram'),
(5, 9, 0.18, 'Kilogram'),
(5, 10, 0.5, 'Kilogram'),
(2, 10, 0.1, 'Liter'),
(7, 10, 0.28, 'Kilogram'),
(8, 10, 0.45, 'Liter'),
(9, 11, 0.47, 'Liter'),
(2, 11, 0.4, 'Liter'),
(4, 11, 0.13, 'Kilogram'),
(1, 12, 0.3, 'Liter'),
(2, 12, 0.25, 'Liter'),
(8, 12, 0.45, 'Liter'),
(3, 13, 0.33, 'Liter'),
(5, 13, 0.41, 'Kilogram'),
(8, 13, 0.15, 'Liter'),
(1, 14, 0.15, 'Liter'),
(4, 14, 0.05, 'Kilogram'),
(7, 14, 0.58, 'Kilogram'),
(2, 14, 1.35, 'Liter'),
(6, 15, 0.4, 'Kilogram'),
(1, 16, 0.15, 'Liter'),
(4, 16, 0.05, 'Kilogram'),
(7, 16, 0.58, 'Kilogram'),
(2, 16, 1.35, 'Liter'),
(1, 17, 0.15, 'Liter'),
(4, 17, 0.05, 'Kilogram'),
(7, 17, 0.58, 'Kilogram'),
(2, 17, 1.35, 'Liter'),
(3, 18, 0.33, 'Liter'),
(5, 18, 0.41, 'Kilogram'),
(8, 18, 0.15, 'Liter'),
(3, 19, 1, 'Liter'),
(3, 20, 2, 'Liter');

-- --------------------------------------------------------

--
-- Table structure for table `rmingredient`
--

CREATE TABLE `rmingredient` (
  `rawMaterialID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rmingredient`
--

INSERT INTO `rmingredient` (`rawMaterialID`, `ingredientID`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 3),
(10, 5),
(11, 2),
(12, 9);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `salesID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `officialReceipt` int(11) NOT NULL,
  `saleDate` date NOT NULL,
  `totalPrice` float NOT NULL,
  `payment` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`salesID`, `orderID`, `officialReceipt`, `saleDate`, `totalPrice`, `payment`) VALUES
(8, 8, 3387, '2019-01-24', 68000, 34000),
(9, 8, 3397, '2019-01-26', 68000, 34000),
(10, 0, 0, '2019-02-20', 16450, 0),
(11, 10, 44544, '2019-02-20', 30800, 15400),
(12, 10, 44545, '2019-02-20', 30800, 15400),
(13, 13, 15723, '2019-03-24', 16000, 8000),
(14, 16, 34567, '2019-03-25', 1800, 900),
(15, 13, 23432, '2019-03-25', 16000, 8000),
(16, 17, 32413, '2019-03-25', 16000, 8000),
(17, 17, 32451, '2019-03-25', 16000, 8000),
(18, 19, 15723, '2019-03-25', 16000, 8000),
(19, 19, 32451, '2019-03-25', 16000, 8000),
(20, 21, 15723, '2019-04-02', 16000, 8000),
(21, 21, 10235, '2019-04-02', 16000, 8000),
(22, 0, 0, '2019-04-02', 35250, 0),
(23, 15, 90909, '2019-05-14', 22000, 11000),
(24, 23, 121, '2019-06-11', 333320, 166660),
(25, 25, 1331, '2019-06-17', 333320, 166660),
(26, 0, 0, '2019-07-09', 6650, 0),
(27, 0, 12345, '2019-07-13', 1850, 1850),
(28, 22, 78778, '2019-07-15', 22000, 11000);

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `orderID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `shippingQuantity` int(11) NOT NULL,
  `shippedQuantity` int(11) NOT NULL,
  `status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shipping`
--

INSERT INTO `shipping` (`orderID`, `productID`, `shippingQuantity`, `shippedQuantity`, `status`) VALUES
(7, 5, 0, 97, 'Shipped'),
(8, 11, 0, 400, 'Shipped'),
(9, 10, 0, 100, 'Shipped'),
(10, 12, 0, 350, 'Shipped'),
(11, 10, 0, 562, 'Shipped'),
(12, 4, 0, 22, 'Shipped'),
(13, 13, 0, 101, 'Shipped'),
(13, 14, 0, 101, 'Shipped'),
(17, 13, 0, 101, 'Shipped'),
(17, 14, 0, 101, 'Shipped'),
(19, 13, 0, 101, 'Shipped'),
(19, 16, 0, 101, 'Shipped'),
(21, 17, 0, 101, 'Shipped'),
(21, 18, 0, 101, 'Shipped'),
(15, 3, 102, -1, 'Pending'),
(15, 7, 101, 0, 'Pending'),
(23, 19, 10, 0, 'Pending'),
(25, 19, 10, 0, 'Pending'),
(22, 1, 202, 0, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplierID` int(11) NOT NULL,
  `company` varchar(45) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  `landline` varchar(45) NOT NULL,
  `mobileNumber` varchar(45) NOT NULL,
  `address` text NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplierID`, `company`, `firstName`, `lastName`, `landline`, `mobileNumber`, `address`, `duration`) VALUES
(1, 'Human Heart Nature', 'Edward', 'White', '4546432', '09288374758', '50 C. Cordero St. 5th Ave. Caloocan City. Metro Manila', 3),
(2, 'Asia Shine Limited', 'Sydnee Zurine', 'Estrada', '8443702', '09266475993', '6/F P C C I Corporate Centre 118 L. P. Leviste Street. Makati CIty. Metro Manila', 6),
(3, 'Hancole Corporation', 'Pasqual Feo Chua', 'Montecillo', '4563748', '09178589035', '1107 Alabang Zapote Road, Metro Manila Kuzon. Muntinlupa CIty. Metro Manila', 5),
(4, 'Rahman Trading', 'Cooper Nikolas Tubo', 'MuÃ????Ã???Ã??Ã?Â±oz', '8109990', '09217774658', '3/F The Landmark Makati Avenue Ayala Center, Makati City, Metro Manila', 7),
(5, 'Tranity Akam Merchandise', 'Horado Roano', 'Belmonte', '2433366', '09278394857', '634 Sto. Cristo Street San Nicolas 1010, Manila', 7),
(6, 'Kemrad Incorporated', 'Reginald Raymon Sariua', 'Zulueta', '8402776', '09254848330', '#18 Melantic St., San Lorenzo Village NCR, Makati City', 5),
(7, 'Chemical Solutions Trading', 'Jaycee Irene', 'Silvestre', '8189935', '09218990182', 'Dominga I I I2113 Chino Roces Avenue, Makati City, Metro Manila', 7);

-- --------------------------------------------------------

--
-- Table structure for table `supply`
--

CREATE TABLE `supply` (
  `rawMaterialID` int(11) NOT NULL,
  `supplierID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supply`
--

INSERT INTO `supply` (`rawMaterialID`, `supplierID`) VALUES
(1, 1),
(2, 1),
(3, 2),
(4, 2),
(5, 2),
(6, 4),
(7, 5),
(8, 5),
(9, 6),
(10, 7),
(11, 7),
(12, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `userName` varchar(45) NOT NULL,
  `password` text NOT NULL,
  `givenName` varchar(45) NOT NULL,
  `userType` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `userName`, `password`, `givenName`, `userType`) VALUES
(1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'Administrator', 104),
(2, 'president', '5f4dcc3b5aa765d61d8327deb882cf99', 'Jarvis', 100),
(3, 'operations', '5f4dcc3b5aa765d61d8327deb882cf99', 'Vangie', 101),
(4, 'warehouse', '5f4dcc3b5aa765d61d8327deb882cf99', 'Dylan', 102),
(5, 'plant', '5f4dcc3b5aa765d61d8327deb882cf99', 'Jacob', 103),
(6, 'renzo', '5f4dcc3b5aa765d61d8327deb882cf99', 'renzoDL', 102);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerID`);

--
-- Indexes for table `ingredient`
--
ALTER TABLE `ingredient`
  ADD PRIMARY KEY (`ingredientID`);

--
-- Indexes for table `joborder`
--
ALTER TABLE `joborder`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `FK` (`customerID`);

--
-- Indexes for table `machine`
--
ALTER TABLE `machine`
  ADD PRIMARY KEY (`machineID`),
  ADD KEY `FK` (`processTypeID`);

--
-- Indexes for table `maintenancetransaction`
--
ALTER TABLE `maintenancetransaction`
  ADD PRIMARY KEY (`transactionID`),
  ADD KEY `FK` (`machineID`);

--
-- Indexes for table `poitem`
--
ALTER TABLE `poitem`
  ADD KEY `FK` (`purchaseOrderID`,`rawMaterialID`);

--
-- Indexes for table `processtype`
--
ALTER TABLE `processtype`
  ADD PRIMARY KEY (`processTypeID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productID`),
  ADD KEY `FK` (`productTypeID`);

--
-- Indexes for table `production`
--
ALTER TABLE `production`
  ADD KEY `FK` (`orderID`,`productID`);

--
-- Indexes for table `productionprocess`
--
ALTER TABLE `productionprocess`
  ADD KEY `FK` (`orderID`,`productID`,`processTypeID`,`machineID`);

--
-- Indexes for table `productprocess`
--
ALTER TABLE `productprocess`
  ADD KEY `FK` (`productID`,`processTypeID`);

--
-- Indexes for table `productsales`
--
ALTER TABLE `productsales`
  ADD KEY `FK` (`productID`,`salesID`);

--
-- Indexes for table `producttype`
--
ALTER TABLE `producttype`
  ADD PRIMARY KEY (`productTypeID`);

--
-- Indexes for table `purchaseorder`
--
ALTER TABLE `purchaseorder`
  ADD PRIMARY KEY (`purchaseOrderID`),
  ADD KEY `FK` (`supplierID`);

--
-- Indexes for table `rawmaterial`
--
ALTER TABLE `rawmaterial`
  ADD PRIMARY KEY (`rawMaterialID`),
  ADD KEY `FK` (`supplierID`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD KEY `FK` (`orderID`,`productID`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD KEY `FK` (`ingredientID`,`productID`);

--
-- Indexes for table `rmingredient`
--
ALTER TABLE `rmingredient`
  ADD KEY `FK` (`rawMaterialID`,`ingredientID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`salesID`),
  ADD KEY `FK` (`orderID`);

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD KEY `FK` (`orderID`,`productID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplierID`);

--
-- Indexes for table `supply`
--
ALTER TABLE `supply`
  ADD KEY `FK` (`rawMaterialID`,`supplierID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ingredient`
--
ALTER TABLE `ingredient`
  MODIFY `ingredientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `joborder`
--
ALTER TABLE `joborder`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `machine`
--
ALTER TABLE `machine`
  MODIFY `machineID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `maintenancetransaction`
--
ALTER TABLE `maintenancetransaction`
  MODIFY `transactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `processtype`
--
ALTER TABLE `processtype`
  MODIFY `processTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `producttype`
--
ALTER TABLE `producttype`
  MODIFY `productTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `purchaseorder`
--
ALTER TABLE `purchaseorder`
  MODIFY `purchaseOrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `rawmaterial`
--
ALTER TABLE `rawmaterial`
  MODIFY `rawMaterialID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `salesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 23, 2018 at 08:52 AM
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
-- Table structure for table `Customer`
--

CREATE TABLE `Customer` (
  `customerID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `address` text NOT NULL,
  `contactNum` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Customer`
--

INSERT INTO `Customer` (`customerID`, `name`, `email`, `address`, `contactNum`) VALUES
(1, 'Daila', 'daila@daila.com', 'mandaluyong', '123'),
(2, 'Customer A', 'customer@customer.com', 'somwer', '123'),
(3, 'Customer B', 'customer@customer.com', 'somwer', '123');

-- --------------------------------------------------------

--
-- Table structure for table `Ingredient`
--

CREATE TABLE `Ingredient` (
  `ingredientID` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Ingredient`
--

INSERT INTO `Ingredient` (`ingredientID`, `quantity`, `name`) VALUES
(1, 233, 'Coconut Oil'),
(2, 534, 'Shea Butter'),
(3, 0, 'Flower Extract'),
(4, 205, 'Jojoba Oil'),
(5, 400, 'Avocado Oil');

-- --------------------------------------------------------

--
-- Table structure for table `JobOrder`
--

CREATE TABLE `JobOrder` (
  `orderID` int(11) NOT NULL,
  `customerID` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `dueDate` date NOT NULL,
  `totalPrice` float NOT NULL,
  `remarks` varchar(255) NOT NULL DEFAULT 'N/A',
  `type` varchar(45) NOT NULL,
  `status` text NOT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `JobOrder`
--

INSERT INTO `JobOrder` (`orderID`, `customerID`, `orderDate`, `dueDate`, `totalPrice`, `remarks`, `type`, `status`, `createdBy`) VALUES
(165, 2, '2018-11-23', '2018-11-29', 400, 'N/A', 'Made to Order', 'Finished', 1),
(166, 2, '2018-11-23', '2018-11-26', 2400, 'N/A', 'Made to Order', 'Incomplete', 1),
(167, 2, '2018-11-23', '2018-11-29', 5200, 'N/A', 'Made to Order', 'Incomplete', 1),
(168, 2, '2018-11-23', '2018-11-27', 799920, 'N/A', 'Made to Order', 'Pending for approval', 1),
(169, 2, '2018-11-23', '2018-11-30', 320, 'N/A', 'Made to Order', 'Pending for approval', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Machine`
--

CREATE TABLE `Machine` (
  `machineID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `acquiredDate` date NOT NULL,
  `status` varchar(45) NOT NULL,
  `timesUsed` int(11) NOT NULL,
  `processTypeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Machine`
--

INSERT INTO `Machine` (`machineID`, `name`, `acquiredDate`, `status`, `timesUsed`, `processTypeID`) VALUES
(1, 'Soap Mixer', '2018-11-06', 'Used', 1, 1),
(2, 'Soap Roller', '2018-10-31', 'Used', 2, 6),
(3, 'Soap Cutter', '2018-11-05', 'Available', 0, 5),
(4, 'Machine A', '2018-11-11', 'Available', 1, 1),
(5, 'Pouring mach', '2018-11-11', 'Available', 0, 2),
(6, 'Curing machine', '2018-11-11', 'Used', 2, 3),
(7, 'Cutter', '2018-11-16', 'Used', 0, 5),
(8, 'Extrudor', '2018-11-11', 'Available', 0, 7),
(9, 'Detacher', '2018-11-02', 'Used', 0, 4),
(10, 'Second Extrudor', '2018-11-20', 'Available', 0, 7),
(11, 'Stamp machine', '2018-11-15', 'Available', 0, 8),
(12, 'Granulator', '2018-11-23', 'Available', 0, 9),
(13, 'Main Sieving machine', '2018-11-01', 'Used', 0, 10),
(14, 'Very Large Pouring Machine', '2018-11-20', 'Available', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `MaintenanceTransaction`
--

CREATE TABLE `MaintenanceTransaction` (
  `transactionID` int(11) NOT NULL,
  `machineID` int(11) NOT NULL,
  `maintenanceCost` float NOT NULL,
  `maintenanceDate` date NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `MaintenanceTransaction`
--

INSERT INTO `MaintenanceTransaction` (`transactionID`, `machineID`, `maintenanceCost`, `maintenanceDate`, `remarks`) VALUES
(1, 7, 650, '2018-11-20', 'hello');

-- --------------------------------------------------------

--
-- Table structure for table `POItem`
--

CREATE TABLE `POItem` (
  `purchaseOrderID` int(11) NOT NULL,
  `rawMaterialID` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `subTotal` float NOT NULL,
  `unitOfMeasurement` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `POItem`
--

INSERT INTO `POItem` (`purchaseOrderID`, `rawMaterialID`, `quantity`, `subTotal`, `unitOfMeasurement`, `status`) VALUES
(1, 1, 25, 1500, 'Liter', 'Delivered'),
(1, 4, 30, 1200, 'Kilogram', 'Delivered'),
(2, 2, 15, 750, 'Liter', 'Delivered'),
(3, 7, 10, 500, 'Kilogram', 'Not Delivered'),
(4, 7, 25, 1250, 'Kilogram', 'Delivered'),
(5, 7, 5, 250, 'Kilogram', 'Delivered'),
(5, 8, 5, 5, 'Liter', 'Delivered'),
(6, 7, 500, 25000, 'Kilogram', 'Delivered'),
(6, 8, 500, 500, 'Liter', 'Delivered'),
(7, 1, 500, 30000, 'Liter', 'Delivered'),
(7, 4, 500, 20000, 'Kilogram', 'Delivered'),
(7, 2, 50, 2500, 'Liter', 'Delivered'),
(7, 1, 40, 2400, 'Liter', 'Not Delivered'),
(7, 4, 20, 800, 'Kilogram', 'Not Delivered'),
(7, 1, 2, 120, 'Liter', 'Not Delivered'),
(7, 4, 3, 120, 'Kilogram', 'Not Delivered'),
(8, 1, 2, 120, 'Liter', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `ProcessType`
--

CREATE TABLE `ProcessType` (
  `processTypeID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ProcessType`
--

INSERT INTO `ProcessType` (`processTypeID`, `name`) VALUES
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
-- Table structure for table `Product`
--

CREATE TABLE `Product` (
  `productID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reorderPoint` int(11) NOT NULL,
  `productPrice` float NOT NULL,
  `unitOfMeasurement` varchar(45) NOT NULL,
  `productTypeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Product`
--

INSERT INTO `Product` (`productID`, `name`, `quantity`, `reorderPoint`, `productPrice`, `unitOfMeasurement`, `productTypeID`) VALUES
(3, 'Gugo', 137, 0, 80, 'Pieces', 1),
(10, 'Victoria', 49, 0, 200, 'Pieces', 2),
(11, 'Herbal Body Lotion', 40, 0, 120, 'Pieces', 5),
(12, 'Coco Loco Coffee Scrub', 0, 0, 150, 'Pieces', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Production`
--

CREATE TABLE `Production` (
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
-- Dumping data for table `Production`
--

INSERT INTO `Production` (`orderID`, `productID`, `status`, `quantity`, `totalGoods`, `totalYield`, `totalLost`, `startDate`, `endDate`) VALUES
(165, 3, 'Finished', 5, 5, 5, 0, '2018-11-23 09:32:03', '2018-11-23 09:32:23'),
(166, 3, 'Started', 5, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(166, 10, 'Started', 10, 0, 0, 0, '2018-11-23 09:32:07', '0000-00-00 00:00:00'),
(167, 3, 'Started', 20, 0, 0, 0, '2018-11-23 11:35:27', '0000-00-00 00:00:00'),
(167, 11, 'Started', 30, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ProductionProcess`
--

CREATE TABLE `ProductionProcess` (
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
-- Dumping data for table `ProductionProcess`
--

INSERT INTO `ProductionProcess` (`orderID`, `productID`, `processTypeID`, `machineID`, `machineQueue`, `processSequence`, `timeEstimate`, `status`) VALUES
(165, 3, 3, 6, 0, 1, 25, 'Added'),
(165, 3, 1, 1, 0, 2, 10, 'Added'),
(165, 3, 6, 2, 0, 3, 50, 'Added'),
(166, 3, 3, 6, 1, 1, 25, 'Ongoing'),
(166, 3, 1, 4, 1, 2, 10, 'Wait'),
(166, 3, 6, 2, 1, 3, 50, 'Wait'),
(166, 10, 1, 4, 1, 1, 50, 'Ongoing'),
(166, 10, 5, 7, 1, 2, 30, 'Wait'),
(166, 10, 4, 9, 1, 3, 100, 'Wait'),
(166, 10, 10, 13, 1, 4, 20, 'Wait'),
(167, 3, 3, 6, 1, 1, 100, 'Ongoing'),
(167, 3, 1, 1, 1, 2, 40, 'Wait'),
(167, 3, 6, 2, 1, 3, 200, 'Wait'),
(167, 11, 1, 4, 1, 1, 60, 'Wait'),
(167, 11, 6, 2, 1, 2, 150, 'Wait'),
(167, 11, 8, 11, 1, 3, 90, 'Wait');

-- --------------------------------------------------------

--
-- Table structure for table `ProductProcess`
--

CREATE TABLE `ProductProcess` (
  `productID` int(11) NOT NULL,
  `processTypeID` int(11) NOT NULL,
  `processSequence` int(11) NOT NULL,
  `timeNeed` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ProductProcess`
--

INSERT INTO `ProductProcess` (`productID`, `processTypeID`, `processSequence`, `timeNeed`) VALUES
(3, 3, 1, 5),
(3, 1, 2, 2),
(3, 6, 3, 10),
(10, 1, 1, 5),
(10, 5, 2, 3),
(10, 4, 3, 10),
(10, 10, 4, 2),
(11, 1, 1, 2),
(11, 6, 2, 5),
(11, 8, 3, 3),
(12, 6, 1, 30),
(12, 1, 2, 15);

-- --------------------------------------------------------

--
-- Table structure for table `ProductSales`
--

CREATE TABLE `ProductSales` (
  `productID` int(11) NOT NULL,
  `salesID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subTotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ProductSales`
--

INSERT INTO `ProductSales` (`productID`, `salesID`, `quantity`, `subTotal`) VALUES
(3, 2, 25, 2000),
(10, 3, 12, 2400),
(3, 3, 18, 1440),
(11, 4, 15, 1800),
(3, 4, 4, 320),
(10, 4, 21, 4200),
(10, 5, 3, 600),
(11, 6, 1, 120),
(3, 7, 3, 240),
(11, 7, 4, 480),
(10, 7, 4, 800),
(3, 8, 5, 400),
(3, 9, 5, 400);

-- --------------------------------------------------------

--
-- Table structure for table `ProductType`
--

CREATE TABLE `ProductType` (
  `productTypeID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ProductType`
--

INSERT INTO `ProductType` (`productTypeID`, `name`) VALUES
(1, 'Soap'),
(2, 'Laundry Detergent'),
(3, 'Dishwashing Liquid'),
(4, 'Beauty Cream'),
(5, 'Beauty Lotion');

-- --------------------------------------------------------

--
-- Table structure for table `PurchaseOrder`
--

CREATE TABLE `PurchaseOrder` (
  `purchaseOrderID` int(11) NOT NULL,
  `supplierID` int(11) NOT NULL,
  `totalPrice` float NOT NULL,
  `orderDate` date NOT NULL,
  `status` text NOT NULL,
  `deadline` date NOT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PurchaseOrder`
--

INSERT INTO `PurchaseOrder` (`purchaseOrderID`, `supplierID`, `totalPrice`, `orderDate`, `status`, `deadline`, `createdBy`) VALUES
(1, 1, 2700, '2018-11-17', 'removed', '2018-11-23', 1),
(2, 2, 750, '2018-11-17', 'Completed!', '2018-11-21', 1),
(3, 5, 500, '2018-11-20', 'removed', '2018-11-25', 1),
(4, 5, 1250, '2018-11-20', 'removed', '2018-11-24', 1),
(5, 5, 255, '2018-11-20', 'removed', '2018-11-24', 1),
(6, 5, 25500, '2018-11-20', 'removed', '2018-11-22', 1),
(7, 1, 240, '2018-11-20', 'removed', '2018-11-22', 1),
(8, 1, 120, '2018-11-21', 'Completed!', '2018-11-22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `RawMaterial`
--

CREATE TABLE `RawMaterial` (
  `rawMaterialID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `supplierID` int(11) NOT NULL,
  `pricePerUnit` float NOT NULL,
  `unitOfMeasurement` varchar(45) NOT NULL,
  `rawMaterialTypeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `RawMaterial`
--

INSERT INTO `RawMaterial` (`rawMaterialID`, `name`, `supplierID`, `pricePerUnit`, `unitOfMeasurement`, `rawMaterialTypeID`) VALUES
(1, 'Enhanced Coconut Oil', 1, 60, 'Liter', 1),
(2, 'Virgin Coconut Oil', 2, 50, 'Liter', 1),
(3, 'Extra Virgin Coconut Oil', 3, 80, 'Liter', 1),
(4, 'Potent Shea Butter', 1, 40, 'Kilogram', 2),
(5, 'Regular Shea Butter', 3, 20, 'Kilogram', 2),
(6, 'Bee generated flower scent', 3, 80, 'Liter', 2),
(7, 'Human Nature Jojoba Oil', 5, 50, 'Kilogram', 1),
(8, 'Human Nature Avocado Oil', 5, 1, 'Liter', 1);

-- --------------------------------------------------------

--
-- Table structure for table `RawMaterialType`
--

CREATE TABLE `RawMaterialType` (
  `rawMaterialTypeID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `RawMaterialType`
--

INSERT INTO `RawMaterialType` (`rawMaterialTypeID`, `name`) VALUES
(1, 'Oil'),
(2, 'Chemical');

-- --------------------------------------------------------

--
-- Table structure for table `Receipt`
--

CREATE TABLE `Receipt` (
  `orderID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subTotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Receipt`
--

INSERT INTO `Receipt` (`orderID`, `productID`, `quantity`, `subTotal`) VALUES
(165, 3, 5, 400),
(166, 3, 5, 400),
(166, 10, 10, 2000),
(167, 3, 20, 1600),
(167, 11, 30, 3600),
(168, 3, 9999, 799920),
(169, 3, 4, 320);

-- --------------------------------------------------------

--
-- Table structure for table `Recipe`
--

CREATE TABLE `Recipe` (
  `ingredientID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `unitOfMeasurement` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Recipe`
--

INSERT INTO `Recipe` (`ingredientID`, `productID`, `quantity`, `unitOfMeasurement`) VALUES
(1, 3, 2, 'Liter'),
(2, 3, 3, 'Kilogram'),
(1, 10, 5, 'Liter'),
(4, 11, 5, 'Kilogram'),
(5, 11, 1, 'Liter'),
(1, 12, 1, 'Liter'),
(5, 12, 2, 'Liter');

-- --------------------------------------------------------

--
-- Table structure for table `RMIngredient`
--

CREATE TABLE `RMIngredient` (
  `rawMaterialID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `RMIngredient`
--

INSERT INTO `RMIngredient` (`rawMaterialID`, `ingredientID`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 2),
(5, 2),
(6, 3),
(7, 4),
(8, 5);

-- --------------------------------------------------------

--
-- Table structure for table `Sales`
--

CREATE TABLE `Sales` (
  `salesID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `saleDate` date NOT NULL,
  `totalPrice` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Sales`
--

INSERT INTO `Sales` (`salesID`, `orderID`, `saleDate`, `totalPrice`) VALUES
(2, 0, '2018-11-14', 2000),
(3, 0, '2018-11-23', 3840),
(4, 0, '2018-11-30', 6320),
(5, 0, '2018-11-06', 600),
(6, 0, '2018-11-01', 120),
(7, 0, '2018-11-15', 1520),
(8, 165, '2018-11-23', 400),
(9, 165, '2018-11-23', 400);

-- --------------------------------------------------------

--
-- Table structure for table `Supplier`
--

CREATE TABLE `Supplier` (
  `supplierID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `contactNum` varchar(45) NOT NULL,
  `address` text NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Supplier`
--

INSERT INTO `Supplier` (`supplierID`, `name`, `contactNum`, `address`, `duration`) VALUES
(1, 'Supplier A', '', 'Somplace', 7),
(2, 'Supplier B', '', 'Someplace', 10),
(3, 'Supplier C', '', 'Somwer', 5),
(4, 'Supplier D', '', 'somplace', 14),
(5, 'Human Heart Nature', '', '463 Commonwealth Avenue, Quezon City', 5);

-- --------------------------------------------------------

--
-- Table structure for table `Supply`
--

CREATE TABLE `Supply` (
  `rawMaterialID` int(11) NOT NULL,
  `supplierID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Supply`
--

INSERT INTO `Supply` (`rawMaterialID`, `supplierID`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 1),
(5, 3),
(6, 3),
(7, 5),
(8, 5);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `userID` int(11) NOT NULL,
  `userName` varchar(45) NOT NULL,
  `password` text NOT NULL,
  `givenName` varchar(45) NOT NULL,
  `userType` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`userID`, `userName`, `password`, `givenName`, `userType`) VALUES
(1, 'donmac', '202cb962ac59075b964b07152d234b70', 'Don Mac', 101),
(2, 'donsales', '202cb962ac59075b964b07152d234b70', 'basta yun', 102),
(3, 'donplant', '202cb962ac59075b964b07152d234b70', 'planter', 103),
(4, 'admin', '202cb962ac59075b964b07152d234b70', 'adminboy', 104);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Customer`
--
ALTER TABLE `Customer`
  ADD PRIMARY KEY (`customerID`);

--
-- Indexes for table `Ingredient`
--
ALTER TABLE `Ingredient`
  ADD PRIMARY KEY (`ingredientID`);

--
-- Indexes for table `JobOrder`
--
ALTER TABLE `JobOrder`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `FK` (`customerID`);

--
-- Indexes for table `Machine`
--
ALTER TABLE `Machine`
  ADD PRIMARY KEY (`machineID`),
  ADD KEY `FK` (`processTypeID`);

--
-- Indexes for table `MaintenanceTransaction`
--
ALTER TABLE `MaintenanceTransaction`
  ADD PRIMARY KEY (`transactionID`),
  ADD KEY `FK` (`machineID`);

--
-- Indexes for table `POItem`
--
ALTER TABLE `POItem`
  ADD KEY `FK` (`purchaseOrderID`,`rawMaterialID`);

--
-- Indexes for table `ProcessType`
--
ALTER TABLE `ProcessType`
  ADD PRIMARY KEY (`processTypeID`);

--
-- Indexes for table `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`productID`),
  ADD KEY `FK` (`productTypeID`);

--
-- Indexes for table `Production`
--
ALTER TABLE `Production`
  ADD KEY `FK` (`orderID`,`productID`);

--
-- Indexes for table `ProductionProcess`
--
ALTER TABLE `ProductionProcess`
  ADD KEY `FK` (`orderID`,`productID`,`processTypeID`,`machineID`);

--
-- Indexes for table `ProductProcess`
--
ALTER TABLE `ProductProcess`
  ADD KEY `FK` (`productID`,`processTypeID`);

--
-- Indexes for table `ProductSales`
--
ALTER TABLE `ProductSales`
  ADD KEY `FK` (`productID`,`salesID`);

--
-- Indexes for table `ProductType`
--
ALTER TABLE `ProductType`
  ADD PRIMARY KEY (`productTypeID`);

--
-- Indexes for table `PurchaseOrder`
--
ALTER TABLE `PurchaseOrder`
  ADD PRIMARY KEY (`purchaseOrderID`),
  ADD KEY `FK` (`supplierID`);

--
-- Indexes for table `RawMaterial`
--
ALTER TABLE `RawMaterial`
  ADD PRIMARY KEY (`rawMaterialID`),
  ADD KEY `FK` (`supplierID`,`rawMaterialTypeID`);

--
-- Indexes for table `RawMaterialType`
--
ALTER TABLE `RawMaterialType`
  ADD PRIMARY KEY (`rawMaterialTypeID`);

--
-- Indexes for table `Receipt`
--
ALTER TABLE `Receipt`
  ADD KEY `FK` (`orderID`,`productID`);

--
-- Indexes for table `Recipe`
--
ALTER TABLE `Recipe`
  ADD KEY `FK` (`ingredientID`,`productID`);

--
-- Indexes for table `RMIngredient`
--
ALTER TABLE `RMIngredient`
  ADD KEY `FK` (`rawMaterialID`,`ingredientID`);

--
-- Indexes for table `Sales`
--
ALTER TABLE `Sales`
  ADD PRIMARY KEY (`salesID`);

--
-- Indexes for table `Supplier`
--
ALTER TABLE `Supplier`
  ADD PRIMARY KEY (`supplierID`);

--
-- Indexes for table `Supply`
--
ALTER TABLE `Supply`
  ADD KEY `FK` (`rawMaterialID`,`supplierID`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Customer`
--
ALTER TABLE `Customer`
  MODIFY `customerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Ingredient`
--
ALTER TABLE `Ingredient`
  MODIFY `ingredientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `JobOrder`
--
ALTER TABLE `JobOrder`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `Machine`
--
ALTER TABLE `Machine`
  MODIFY `machineID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `MaintenanceTransaction`
--
ALTER TABLE `MaintenanceTransaction`
  MODIFY `transactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ProcessType`
--
ALTER TABLE `ProcessType`
  MODIFY `processTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Product`
--
ALTER TABLE `Product`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ProductType`
--
ALTER TABLE `ProductType`
  MODIFY `productTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `PurchaseOrder`
--
ALTER TABLE `PurchaseOrder`
  MODIFY `purchaseOrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `RawMaterial`
--
ALTER TABLE `RawMaterial`
  MODIFY `rawMaterialID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `RawMaterialType`
--
ALTER TABLE `RawMaterialType`
  MODIFY `rawMaterialTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Sales`
--
ALTER TABLE `Sales`
  MODIFY `salesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Supplier`
--
ALTER TABLE `Supplier`
  MODIFY `supplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/*
SQLyog Enterprise - MySQL GUI v7.02 
MySQL - 5.6.17 : Database - homemeals
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`homemeals` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci */;

USE `homemeals`;

/*Table structure for table `client` */

DROP TABLE IF EXISTS `client`;

CREATE TABLE `client` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(45) COLLATE utf8_polish_ci NOT NULL,
  `DESCRIPTION` varchar(500) COLLATE utf8_polish_ci DEFAULT NULL,
  `ORDER_TIME_LIMIT` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UNQ_NAME` (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

/*Table structure for table `dinner` */

DROP TABLE IF EXISTS `dinner`;

CREATE TABLE `dinner` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(250) COLLATE utf8_polish_ci NOT NULL,
  `DATE` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

/*Table structure for table `order` */

DROP TABLE IF EXISTS `order`;

CREATE TABLE `order` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(250) COLLATE utf8_polish_ci NOT NULL,
  `DATE` datetime NOT NULL,
  `AMOUNT` int(1) NOT NULL,
  `DINNER_ID` int(11) NOT NULL,
  `CLIENT_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_ORDER_DINNER_idx` (`DINNER_ID`),
  KEY `fk_ORDER_CLIENT1_idx` (`CLIENT_ID`),
  CONSTRAINT `fk_ORDER_CLIENT` FOREIGN KEY (`CLIENT_ID`) REFERENCES `client` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ORDER_DINNER` FOREIGN KEY (`DINNER_ID`) REFERENCES `dinner` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

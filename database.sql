-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.25a


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema gsh_sms
--

CREATE DATABASE IF NOT EXISTS gsh_sms;
USE gsh_sms;

--
-- Definition of table `phonebook`
--

DROP TABLE IF EXISTS `phonebook`;
CREATE TABLE `phonebook` (
  `ID` int(11) NOT NULL,
  `Number` varchar(45) NOT NULL,
  `Name` longtext NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phonebook`
--

/*!40000 ALTER TABLE `phonebook` DISABLE KEYS */;
INSERT INTO `phonebook` (`ID`,`Number`,`Name`) VALUES 
 (1,'09358288540','Mary Ann');
/*!40000 ALTER TABLE `phonebook` ENABLE KEYS */;


--
-- Definition of table `sms`
--

DROP TABLE IF EXISTS `sms`;
CREATE TABLE `sms` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `mobile_no` varchar(45) NOT NULL,
  `station` varchar(45) NOT NULL,
  `message` longtext NOT NULL,
  `remarks` longtext NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sms`
--

/*!40000 ALTER TABLE `sms` DISABLE KEYS */;
INSERT INTO `sms` (`ID`,`datetime`,`mobile_no`,`station`,`message`,`remarks`) VALUES 
 (1,'2016-01-06 22:47:54','09204789213','','asdasd','Message Sent'),
 (2,'2016-01-06 22:53:19','09204789213','','asdasd','Message Sent'),
 (3,'2016-01-07 10:18:26','09368807044','','SMS CHECK!!!','Message Sent'),
 (4,'2016-01-07 10:22:29','09368807044','','SMS CHECK!!!','Message Sent');
/*!40000 ALTER TABLE `sms` ENABLE KEYS */;


--
-- Definition of table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `USERID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Station` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `Usertype` varchar(45) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`USERID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`USERID`,`Name`,`Station`,`username`,`password`,`Usertype`,`date`) VALUES 
 (1,'a','NS1','a','a','USER','2016-01-06'),
 (2,'Ebrahim','NS1','ediangca22','ed','USER','2016-01-06'),
 (3,'Mark','NS1','mark','m','USER','2016-01-07');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

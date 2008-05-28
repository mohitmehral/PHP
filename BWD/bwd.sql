-- MySQL dump 10.10
--
-- Host: localhost    Database: bwd
-- ------------------------------------------------------
-- Server version	5.0.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bwd_stations`
--

DROP TABLE IF EXISTS `bwd_stations`;
CREATE TABLE `bwd_stations` (
  `numind` varchar(255) NOT NULL default '',
  `latitude` float default NULL,
  `longitude` float default NULL,
  `cc` varchar(255) default NULL,
  `WaterType` varchar(255) default NULL,
  `SeaWater` varchar(255) default NULL,
  `Region` varchar(255) default NULL,
  `Province` varchar(255) default NULL,
  `Commune` varchar(255) default NULL,
  `Prelev` varchar(255) default NULL,
  `y1990` varchar(255) default NULL,
  `y1991` varchar(255) default NULL,
  `y1992` varchar(255) default NULL,
  `y1993` varchar(255) default NULL,
  `y1994` varchar(255) default NULL,
  `y1995` varchar(255) default NULL,
  `y1996` varchar(255) default NULL,
  `y1997` varchar(255) default NULL,
  `y1998` varchar(255) default NULL,
  `y1999` varchar(255) default NULL,
  `y2000` varchar(255) default NULL,
  `y2001` varchar(255) default NULL,
  `y2002` varchar(255) default NULL,
  `y2003` varchar(255) default NULL,
  `y2004` varchar(255) default NULL,
  `y2005` varchar(255) default NULL,
  `y2006` varchar(255) default NULL,
  `y2007` varchar(255) default NULL,
  `remarks_etcw` varchar(255) default NULL,
  PRIMARY KEY  (`numind`),
  KEY `cc` (`cc`),
  KEY `y2000` (`y2000`),
  KEY `y2001` (`y2001`),
  KEY `y2002` (`y2002`),
  KEY `y2003` (`y2003`),
  KEY `y2004` (`y2004`),
  KEY `y2005` (`y2005`),
  KEY `y2006` (`y2006`),
  KEY `y2007` (`y2007`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `countrycodes_iso`
--

DROP TABLE IF EXISTS `countrycodes_iso`;
CREATE TABLE `countrycodes_iso` (
  `ISO2` varchar(255) NOT NULL default '',
  `Country` varchar(255) default NULL,
  `NationalName` varchar(100) default NULL,
  PRIMARY KEY  (`ISO2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `numind_geographic`
--

DROP TABLE IF EXISTS `numind_geographic`;
CREATE TABLE `numind_geographic` (
  `numind` varchar(18) NOT NULL default '',
  `geographic` varchar(50) default NULL,
  PRIMARY KEY  (`numind`),
  KEY `geo` (`geographic`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-05-28 13:38:55

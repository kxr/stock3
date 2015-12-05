-- MySQL dump 10.13  Distrib 5.1.61, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: ktdb_stock
-- ------------------------------------------------------
-- Server version	5.1.61

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
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `client_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(50) NOT NULL,
  `client_phone` varchar(20) DEFAULT NULL,
  `client_fax` varchar(20) DEFAULT NULL,
  `client_email` varchar(30) DEFAULT NULL,
  `client_details` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'Lighting Trading','0998473','84849432','test@test.com','Some details'),(2,'ABC Trading','94949494','290-8902-324','blas@abc.com','shop no. 84, rolla shajrha'),(3,'Al Naeem Ayub','8885895','22229999','','Al khan shajraha'),(4,'Halaaa','04747160-94','78165153','hal@halllaa.com','Deira bridge, dubai, UAE'),(5,'New Pumps','940-19774','7266405','newpumpt@ndlkjds.com','Ware house no. 44, Industrial area 14, Dubai Jabal Ali freezone'),(6,'Class Trading ','5556489600','6581861','lakdjf@lkdf.com',''),(7,'Rolla Mall','4498955','65416198','ljdkl@cdla.com','Some details about this client hellow work'),(8,'Lulu Building','848909875','89475895','asdf@dasf.com','');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_groups`
--

DROP TABLE IF EXISTS `item_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_groups` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_groups`
--

LOCK TABLES `item_groups` WRITE;
/*!40000 ALTER TABLE `item_groups` DISABLE KEYS */;
INSERT INTO `item_groups` VALUES (1,'Group1'),(2,'Group2'),(3,'Group3'),(4,'Group4'),(5,'Group5'),(6,'Group6'),(7,'Group7'),(8,'Group8');
/*!40000 ALTER TABLE `item_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL DEFAULT '1',
  `item_name` varchar(50) DEFAULT NULL,
  `item_detail` varchar(255) DEFAULT NULL,
  `item_saleprice` decimal(10,3) unsigned DEFAULT '0.000',
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,2,'Pak wall 18\" (Bracket) fan','fan description','10.000'),(2,2,'Pak Pedestal 24\" Fan','test description','0.000'),(3,2,'Pak Industrial 30\" Wall Fan','good quality','0.000'),(4,2,'Pak Test 30\" Wall Fan','good quality','0.000'),(5,2,'Pak Exhaust Fan 18\" H/D','test','0.000'),(6,2,'Pak Exhaust Fan 16\" H/D','','0.000'),(7,2,'Pak Ceiling Fan 56\"',NULL,'0.000'),(8,2,'Tracon Pk ceiling fan 56\"','alpha description','0.000'),(9,3,'AL Ahmed Pedestal Fan 24\"','','0.000'),(10,3,'AL Ahmed Pedestal Fan 18\"','1123','0.000'),(11,3,'AL Ahmed Wall Fan 18\"','power:24W','0.000'),(12,3,'GFC ceiling fan 56\"',NULL,'0.000'),(13,4,'Yunas Exhaust Fan 9\" H/D','220V','0.000'),(14,4,'Yunas Exhaust Fan 12\" H/D','','0.000'),(15,4,'Yunas Exhaust Fan 16\" H/D','','0.000'),(16,4,'Yunas Exhaust Fan 18\" H/D','','0.000'),(17,5,'CDR-PV07-CS 0.75KW Drainage Pump','','0.000'),(18,5,'0.75KW 11/4\"X1\" centrifugal ','CTSS-8/07M','0.000'),(19,5,'CTC-21F/15M, 1.50KW 2X2\" Pump','','0.000'),(20,6,'Calpeda 1HP NMM 2A/E','','0.000'),(21,6,'Calpeda 0.45HP CTM 60','','0.000'),(22,6,'Calpeda 0.45HP CTM 61','','0.000'),(23,6,'Pressure control kit 1.5Bar MPC-10','','0.000'),(24,6,'Pressure control kit 1.5Bar MPC-20','MPC','0.000'),(25,4,'Yunas Exhaust Fan 8\" Round ','','0.000'),(26,4,'Yunas Exhaust Fan 6\" Round','','0.000'),(27,4,'GFC Pedestal Fan 24\"','','0.000'),(28,7,'Prakash 0.5HP cent Pump','','0.000'),(29,8,'Shattaf set ROME made in Italy',NULL,'0.000'),(30,8,'Double Water Filter so Pure ','','0.000'),(31,8,'SS Flexible 90CM Mori',NULL,'0.000'),(32,8,'SS Flexible 120CM Mori',NULL,'0.000'),(33,8,'SQD (pressure) switch','China','0.000'),(34,8,'Gas Hose RAR','etc etc','474.000'),(35,8,'Copper Flexible 60CM Mori','original','10.000'),(36,8,'Copper Flexible 45CM Mori','original','50.000'),(37,0,'test itme 2 blah bal','test','50.000');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_invoices`
--

DROP TABLE IF EXISTS `purchase_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_invoices` (
  `p_invoice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `vendor_id` int(10) NOT NULL,
  `vendor_invoice_no` varchar(20) NOT NULL,
  `invoice_total` decimal(10,3) unsigned NOT NULL,
  `invoice_note` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `payment_reference` varchar(20) DEFAULT NULL,
  `timestamp` int(8) DEFAULT '0',
  PRIMARY KEY (`p_invoice_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_invoices`
--

LOCK TABLES `purchase_invoices` WRITE;
/*!40000 ALTER TABLE `purchase_invoices` DISABLE KEYS */;
INSERT INTO `purchase_invoices` VALUES (1,'2014-08-28',1,'X1122','0.000','inv_note ahah',0,'',1409248943),(2,'2014-08-29',3,'X22002','0.000','test note pur inv',0,'',1409297824),(3,'2014-08-29',9,'HZQ 101043','0.000','blha bhala',0,'',1409306051),(4,'2014-08-29',10,'H2023','0.000','',0,'',1409306463);
/*!40000 ALTER TABLE `purchase_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_transactions`
--

DROP TABLE IF EXISTS `purchase_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_transactions` (
  `p_trans_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `payment_type` varchar(10) DEFAULT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `vendor_id` int(10) unsigned DEFAULT NULL,
  `invoice_id` int(20) DEFAULT NULL,
  `uprice` decimal(10,3) unsigned DEFAULT NULL,
  `qty` decimal(10,3) unsigned DEFAULT NULL,
  `comments` varchar(50) DEFAULT NULL,
  `timestamp` int(8) DEFAULT NULL,
  PRIMARY KEY (`p_trans_id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_transactions`
--

LOCK TABLES `purchase_transactions` WRITE;
/*!40000 ALTER TABLE `purchase_transactions` DISABLE KEYS */;
INSERT INTO `purchase_transactions` VALUES (1,'2013-09-22','Cash',1,1,7323,'105.000','10.000','',1380813781),(2,'2013-09-22','Cash',2,1,7322,'180.000','6.000','',1380813916),(3,'2013-09-18','Cash',3,1,7282,'330.000','4.000','',1380814494),(4,'2013-09-16','Cash',4,1,180,'180.000','2.000','',1380814610),(5,'2013-09-16','Cash',5,1,7259,'225.000','1.000','',1380814649),(6,'2013-09-16','Cash',6,1,7259,'180.000','2.000','',1380814876),(7,'2013-10-03','Cash',7,1,0,'95.000','1.000','',1380814986),(8,'2013-09-09','Cash',8,1,7204,'52.000','15.000','',1380816175),(9,'2013-07-25','Cash',9,1,1569,'105.000','1.000','',1380817586),(10,'2013-07-14','Cash',10,1,1483,'90.000','10.000','',1380817675),(11,'2013-07-14','Cash',11,1,1483,'88.000','10.000','',1380817724),(12,'2013-10-03','Cash',12,1,696,'85.000','9.000','',1380817824),(13,'2013-08-01','Cash',13,1,23654,'95.000','4.000','',1380825314),(14,'2013-09-01','Cash',14,1,23654,'135.000','2.000','',1380825355),(15,'2013-09-01','Cash',15,1,23654,'170.000','2.000','',1380825399),(16,'2013-10-03','Cash',16,1,22295,'230.000','4.000','',1380825496),(17,'2013-09-30','Cash',19,1,0,'512.000','2.000','',1380980902),(18,'2013-09-30','Cash',18,1,0,'417.000','2.000','',1380980963),(19,'2013-09-30','Cash',17,1,0,'607.000','2.000','',1380980997),(20,'2013-09-07','Cash',20,1,2769,'480.000','5.000','',1381239625),(21,'2013-09-07','Cash',21,1,2796,'205.000','10.000','',1381239667),(22,'2013-09-07','Cash',22,1,2769,'210.000','5.000','',1381239699),(23,'2013-09-07','Cash',23,1,2769,'80.000','5.000','',1381239779),(24,'2013-09-07','Cash',24,1,2769,'80.000','5.000','',1381239809),(25,'2013-10-08','Cash',25,1,0,'26.000','10.000','',1381241823),(26,'2013-10-08','Cash',26,1,0,'18.000','10.000','',1381241846),(27,'2013-10-08','Cash',27,1,0,'165.000','6.000','',1381242018),(28,'2013-10-08','Cash',28,1,0,'130.000','4.000','',1381242672),(29,'2013-10-08','Cash',29,1,21700,'20.000','24.000','',1381245121),(30,'2013-10-08','Cash',30,1,21700,'50.000','2.000','',1381245158),(31,'2013-10-08','Cash',34,1,21700,'100.000','1.000','',1381245352),(32,'2013-10-08','Cash',33,1,21700,'14.000','12.000','',1381245494),(34,'2013-10-08','Cash',31,1,21700,'4.250','30.000','',1381249507),(35,'2013-10-08','Cash',32,1,21700,'5.000','20.000','',1381249557),(36,'2014-08-28','',13,NULL,NULL,'200.000','10.000','test',1409240737),(37,'2014-08-28','Cheque',29,1,1,'40.000','10.000','com1',1409248943),(38,'2014-08-28','Cheque',9,1,1,'220.000','20.000','com2',1409248943),(39,'2014-08-28','Cheque',22,1,1,'180.150','15.000','com3',1409248943),(40,'2014-08-29','Cash',10,3,2,'100.000','10.000','aho',1409297824),(41,'2014-08-29','Cash',9,3,2,'120.000','10.000','',1409297824),(42,'2014-08-29','Cheque',20,9,3,'500.000','10.000','test com1',1409306051),(43,'2014-08-29','Cheque',21,9,3,'200.000','20.000','test desc2',1409306051),(44,'2014-08-29','Cheque',2,10,4,'244.000','20.000','test',1409306463),(45,'2014-08-29','Cheque',1,10,4,'123.000','12.000','',1409306463),(46,'2014-08-29','Cheque',20,10,4,'800.000','10.000','',1409306463),(47,'2014-08-29','',2,NULL,NULL,'120.000','20.000','xstock',1409306875);
/*!40000 ALTER TABLE `purchase_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_invoices`
--

DROP TABLE IF EXISTS `sale_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_invoices` (
  `sale_invoice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `invoice_title` varchar(50) DEFAULT NULL,
  `invoice_note` varchar(200) DEFAULT NULL,
  `amount_received` decimal(10,3) unsigned DEFAULT '0.000',
  `raw_data` text,
  `timestamp` int(8) DEFAULT '0',
  PRIMARY KEY (`sale_invoice_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_invoices`
--

LOCK TABLES `sale_invoices` WRITE;
/*!40000 ALTER TABLE `sale_invoices` DISABLE KEYS */;
INSERT INTO `sale_invoices` VALUES (1,'2014-08-27','','','6099.200','xyz',1409148091),(2,'2014-08-27','','','6099.200','xyz',1409148147),(3,'2014-08-27','test invoice','test note ','1137.500','xyz',1409165512),(4,'2014-08-28','','','106.000','xyz',1409231176),(5,'2014-08-28','New test','','2852.000','xyz',1409247079),(6,'2014-08-29','','','1000.000','xyz',1409258003),(7,'2014-08-16','Khizer','test note','1500.000','xyz',1409258380),(8,'2014-08-29','','','1488.000','xyz',1409258486),(9,'2014-08-29','','','2132.000','xyz',1409258621),(10,'2014-08-29','KXR','test invoice','2676.600','xyz',1409258902),(11,'2014-08-29','','','1900.000','xyz',1409259175),(12,'2014-08-29','KXR','test note','1500.000','xyz',1409259231),(13,'2014-08-29','KXR','test note','3500.000','xyz',1409259305);
/*!40000 ALTER TABLE `sale_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_transactions`
--

DROP TABLE IF EXISTS `sale_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_transactions` (
  `sale_trans_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `payment_type` varchar(10) DEFAULT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `invoice_no` varchar(20) DEFAULT NULL,
  `uprice` decimal(10,3) unsigned DEFAULT NULL,
  `qty` decimal(10,3) unsigned DEFAULT NULL,
  `amount_received` decimal(10,3) unsigned DEFAULT '0.000',
  `comments` varchar(255) DEFAULT NULL,
  `timestamp` int(8) DEFAULT NULL,
  PRIMARY KEY (`sale_trans_id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_transactions`
--

LOCK TABLES `sale_transactions` WRITE;
/*!40000 ALTER TABLE `sale_transactions` DISABLE KEYS */;
INSERT INTO `sale_transactions` VALUES (1,'2014-08-27','Cash',9,'KTSS2','200.000','2.000','0.000','',1409148147),(2,'2014-08-27','Cash',12,'KTSS2','230.400','23.000','0.000','',1409148147),(3,'2014-08-27','Cash',36,'KTSS2','40.000','10.000','0.000','',1409148147),(4,'2014-08-27','Cash',3,'KTSS3','50.000','2.000','0.000','',1409165512),(5,'2014-08-27','Cash',21,'KTSS3','12.500','3.000','0.000','',1409165512),(6,'2014-08-27','Cash',17,'KTSS3','1000.000','1.000','0.000','',1409165512),(7,'2014-08-28','Cash',5,'KTSS4','106.000','1.000','0.000','',1409231176),(8,'2014-08-28','Cash',0,'KTSS4','0.000','1.000','0.000','',1409231176),(9,'2014-08-28','Cash',0,'KTSS4','0.000','1.000','0.000','',1409231176),(10,'2014-08-28','Cash',0,'KTSS4','0.000','1.000','0.000','',1409231176),(11,'2014-08-28','Cash',0,'KTSS4','0.000','1.000','0.000','',1409231176),(12,'2014-08-28','Cash',0,'KTSS4','0.000','1.000','0.000','',1409231176),(13,'2014-08-28','Cash',9,'12303','22.000','1.000','22.000','',1409242827),(14,'2014-08-28','Cash',3,'2345','23.150','23.000','532.450','',1409245284),(15,'2014-08-28','Cash',10,'KTSS5','500.000','500.000','1.000','blah blah',1409247079),(16,'2014-08-28','Cash',9,'KTSS5','588.000','2352.000','4.000','asdfa asdfa',1409247079),(17,'2014-08-29','Cash',2,'KTSS6','500.000','1000.000','2.000','',1409258003),(18,'2014-08-29','Cash',0,'KTSS6','0.000','0.000','1.000','',1409258003),(19,'2014-08-29','Cash',0,'KTSS6','0.000','0.000','1.000','',1409258003),(20,'2014-08-29','Cash',0,'KTSS6','0.000','0.000','1.000','',1409258003),(21,'2014-08-29','Cash',0,'KTSS6','0.000','0.000','1.000','',1409258003),(22,'2014-08-29','Cash',0,'KTSS6','0.000','0.000','1.000','',1409258003),(23,'2014-08-16','Cash',23,'KTSS7','500.000','1000.000','2.000','test desc',1409258380),(24,'2014-08-16','Cash',21,'KTSS7','180.150','540.450','3.000','',1409258380),(25,'2014-08-29','Cash',16,'KTSS8','544.000','1088.000','2.000','',1409258486),(26,'2014-08-29','Cash',22,'KTSS8','400.000','400.000','1.000','',1409258486),(27,'2014-08-29','Cash',11,'KTSS9','400.000','2.000','800.000','',1409258621),(28,'2014-08-29','Cash',20,'KTSS9','444.000','3.000','1332.000','',1409258621),(29,'2014-08-29','Cash',6,'KTSS10','500.000','3.000','1500.000','',1409258902),(30,'2014-08-29','Cash',10,'KTSS10','588.300','2.000','1176.600','',1409258902),(31,'2014-08-29','Cash',11,'KTSS11','500.000','1.000','500.000','',1409259175),(32,'2014-08-29','Cash',22,'KTSS11','299.000','4.000','1196.000','',1409259175),(33,'2014-08-29','Cash',13,'KTSS11','140.000','2.000','280.000','',1409259175),(34,'2014-08-29','Cash',5,'KTSS12','400.000','2.000','800.000','',1409259231),(35,'2014-08-29','Cash',9,'KTSS12','200.000','3.000','600.000','',1409259231),(36,'2014-08-29','Cash',12,'KTSS12','130.400','1.000','130.400','',1409259231),(37,'2014-08-29','Cash',6,'KTSS13','230.000','2.000','460.000','',1409259305),(38,'2014-08-29','Cash',20,'KTSS13','500.000','2.000','1000.000','',1409259305),(39,'2014-08-29','Cash',17,'KTSS13','700.000','3.000','2100.000','',1409259305),(40,'2014-08-29','Cash',2,'78975','70.000','2.000','140.000','',1409307098),(41,'2014-08-29','Cash',3,'','0.000','1.000','0.000','',1409313086);
/*!40000 ALTER TABLE `sale_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendors` (
  `vendor_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(50) NOT NULL,
  `vendor_phone` varchar(50) DEFAULT NULL,
  `vendor_fax` varchar(20) DEFAULT NULL,
  `vendor_email` varchar(30) DEFAULT NULL,
  `vendor_details` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`vendor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
INSERT INTO `vendors` VALUES (1,'Test Vendor','','','',''),(2,'Tracon PK','','','',''),(3,'Al Dhaw AL Wahaj','','','',''),(4,'Fahad Ayoun','','','',''),(5,'C.R.I. Pumps','','','',''),(6,'Metel Trading','','','',''),(7,'Tokyo Gen.','','','',''),(8,'Hardware & Electric','','','',''),(9,'abc','','','',''),(10,'Hazaq LLC','0503669640','065662925','hazaq@test.com','blah blah');
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-08-29 17:39:54

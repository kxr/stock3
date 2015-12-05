DROP DATABASE ktdb_stock;
CREATE DATABASE /*!32312 IF NOT EXISTS*/ `ktdb_stock` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `ktdb_stock`;



DROP TABLE IF EXISTS `vendors`;
CREATE TABLE `vendors` (
  `vendor_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(50) NOT NULL,
  `vendor_phone` varchar(50) DEFAULT NULL,
  `vendor_fax` varchar(20) DEFAULT NULL,
  `vendor_email` varchar(30) DEFAULT NULL,
  `vendor_details` varchar(255) DEFAULT NULL,
 PRIMARY KEY (`vendor_id`)
);

DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `client_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(50) NOT NULL,
  `client_phone` varchar(20) DEFAULT NULL,
  `client_fax` varchar(20) DEFAULT NULL,
  `client_email` varchar(30) DEFAULT NULL,
  `client_details` varchar(255) DEFAULT NULL,
 PRIMARY KEY (`client_id`)
);

DROP TABLE IF EXISTS `item_groups`;
CREATE TABLE `item_groups` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
 PRIMARY KEY (`group_id`)
);

DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL DEFAULT '1',
  `item_name` varchar(50) DEFAULT NULL,
  `item_detail` varchar(255) DEFAULT NULL,
  `item_saleprice` decimal(10,3) unsigned DEFAULT '0',
  PRIMARY KEY (`item_id`)
);

DROP TABLE IF EXISTS `sale_transactions`;
CREATE TABLE `sale_transactions` (
  `sale_trans_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `payment_type` varchar(10) DEFAULT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `invoice_no` varchar(20) DEFAULT NULL,
  `uprice` decimal(10,3) unsigned DEFAULT NULL,
  `qty` decimal(10,3) unsigned DEFAULT NULL,
  `amount_received` decimal(10,3) unsigned DEFAULT '0',
  `comments` varchar(255) DEFAULT NULL,
  `timestamp` int(8) DEFAULT NULL,
  PRIMARY KEY (`sale_trans_id`)
);

DROP TABLE IF EXISTS `sale_invoices`;
CREATE TABLE `sale_invoices` (
  `sale_invoice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `invoice_title` varchar(50) DEFAULT NULL,
  `invoice_note` varchar(200) DEFAULT NULL,
  `amount_received` decimal(10,3) unsigned DEFAULT '0',
  `raw_data` text DEFAULT NULL,
  `timestamp` int(8) DEFAULT '0',
 PRIMARY KEY (`sale_invoice_id`)
);

/*DROP TABLE IF EXISTS `credit_transactions`;
CREATE TABLE `credit_transactions` (
  `credit_trans_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `item_id` int(10) unsigned NOT NULL,
  `credit_invoice_id` int(10) DEFAULT NULL,
  `uprice` decimal(10,3) unsigned DEFAULT NULL,
  `qty` decimal(10,3) unsigned DEFAULT NULL,
  `payment_id` int(10) unsigned DEFAULT '0',
  `amount_received` decimal(10,3) unsigned DEFAULT '0', 
  `comments` varchar(50) DEFAULT NULL,
  `timestamp` int(8) DEFAULT NULL,
  PRIMARY KEY (`credit_trans_id`)
);

DROP TABLE IF EXISTS `credit_invoices`;
CREATE TABLE `credit_invoices` (
  `credit_invoice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `client_id` int(10) NOT NULL,
  `lpo_no` varchar(20) DEFAULT NULL,
  `invoice_note` varchar(255) DEFAULT NULL,
  `amount_received` decimal(10,3) unsigned DEFAULT '0',
  `status` tinyint DEFAULT '0',
  `timestamp` int(8) DEFAULT '0',
 PRIMARY KEY (`credit_invoice_id`)
);

DROP TABLE IF EXISTS `credit_ledger`;
CREATE TABLE `credit_ledger` (
  `ledger_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `description` varchar(255) DEFAULT NULL,
  `client_id` int(10) NOT NULL,
  `entry_type` tinyint NOT NULL,
  `invoice_or_payment_id` int(10) DEFAULT NULL,
  `clear_balance_marker` decimal(10,3) DEFAULT '0',
 PRIMARY KEY (`ledger_id`)
);

DROP TABLE IF EXISTS `credit_payments`;
CREATE TABLE `credit_payments` (
  `payment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `payment_type` tinyint DEFAULT '0',
  `payment_reference` varchar(20) NOT NULL,
  `payment_amount` decimal(10,3) unsigned DEFAULT '0',
  `payment_desc` varchar(255) DEFAULT NULL,
 PRIMARY KEY (`payment_id`)
);

DROP TABLE IF EXISTS `credit_payment2invoice`;
CREATE TABLE `credit_payment2invoice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` int(10) unsigned NOT NULL,
  `credit_invoice_id` int(10) unsigned NOT NULL,
 PRIMARY KEY (`id`)
);
*/

DROP TABLE IF EXISTS `purchase_transactions`;
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
);

DROP TABLE IF EXISTS `purchase_invoices`;
CREATE TABLE `purchase_invoices` (
  `p_invoice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `vendor_id` int(10) NOT NULL,
  `vendor_invoice_no` varchar(20) NOT NULL,
  `invoice_total` decimal(10,3) unsigned NOT NULL,
  `invoice_note` varchar(255) DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  `payment_reference` varchar(20) DEFAULT NULL,
  `timestamp` int(8) DEFAULT '0',
 PRIMARY KEY (`p_invoice_id`)
);


INSERT INTO `vendors` VALUES
(1,'Test Vendor', '', '', '', ''),
(2,'Tracon PK', '', '', '', ''),
(3,'Al Dhaw AL Wahaj', '', '', '', ''),
(4,'Fahad Ayoun', '', '', '', ''),
(5,'C.R.I. Pumps', '', '', '', ''),
(6,'Metel Trading', '', '', '', ''),
(7,'Tokyo Gen.', '', '', '', ''),
(8,'Hardware & Electric', '', '', '', '');

INSERT INTO `item_groups` VALUES
(1,'Group1'),
(2,'Group2'),
(3,'Group3'),
(4,'Group4'),
(5,'Group5'),
(6,'Group6'),
(7,'Group7'),
(8,'Group8');


INSERT INTO `items` VALUES
(1,2,'Pak wall 18\" (Bracket) fan','fan description',10.000),
(2,2,'Pak Pedestal 24\" Fan','test description',0.000),
(3,2,'Pak Industrial 30\" Wall Fan','good quality',0.000),
(4,2,'Pak Test 30\" Wall Fan','good quality',0.000),
(5,2,'Pak Exhaust Fan 18\" H/D','test',0.000),
(6,2,'Pak Exhaust Fan 16\" H/D','',0.000),
(7,2,'Pak Ceiling Fan 56\"',NULL,0.000),
(8,2,'Tracon Pk ceiling fan 56\"','alpha description',0.000),
(9,3,'AL Ahmed Pedestal Fan 24\"','',0.000),
(10,3,'AL Ahmed Pedestal Fan 18\"','1123',0.000),
(11,3,'AL Ahmed Wall Fan 18\"','power:24W',0.000),
(12,3,'GFC ceiling fan 56\"',NULL,0.000),
(13,4,'Yunas Exhaust Fan 9\" H/D','220V',0.000),
(14,4,'Yunas Exhaust Fan 12\" H/D','',0.000),
(15,4,'Yunas Exhaust Fan 16\" H/D','',0.000),
(16,4,'Yunas Exhaust Fan 18\" H/D','',0.000),
(17,5,'CDR-PV07-CS 0.75KW Drainage Pump','',0.000),
(18,5,'0.75KW 11/4\"X1\" centrifugal ','CTSS-8/07M',0.000),
(19,5,'CTC-21F/15M, 1.50KW 2X2\" Pump','',0.000),
(20,6,'Calpeda 1HP NMM 2A/E','',0.000),
(21,6,'Calpeda 0.45HP CTM 60','',0.000),
(22,6,'Calpeda 0.45HP CTM 61','',0.000),
(23,6,'Pressure control kit 1.5Bar MPC-10','',0.000),
(24,6,'Pressure control kit 1.5Bar MPC-20','MPC',0.000),
(25,4,'Yunas Exhaust Fan 8\" Round ','',0.000),
(26,4,'Yunas Exhaust Fan 6\" Round','',0.000),
(27,4,'GFC Pedestal Fan 24\"','',0.000),
(28,7,'Prakash 0.5HP cent Pump','',0.000),
(29,8,'Shattaf set ROME made in Italy',NULL,0.000),
(30,8,'Double Water Filter so Pure ','',0.000),
(31,8,'SS Flexible 90CM Mori',NULL,0.000),
(32,8,'SS Flexible 120CM Mori',NULL,0.000),
(33,8,'SQD (pressure) switch','China',0.000),
(34,8,'Gas Hose RAR','etc etc',0.000),
(35,8,'Copper Flexible 60CM Mori','original',0.000),
(36,8,'Copper Flexible 45CM Mori','original',0.000);

INSERT INTO `purchase_transactions` VALUES 
 (1,'2013-09-22','Cash',1,  1, '7323',105.000,10.000,'',1380813781),
 (2,'2013-09-22','Cash',2,  1, '7322',180.000,6.000,'',1380813916),
 (3,'2013-09-18','Cash',3,  1, '7282',330.000,4.000,'',1380814494),
 (4,'2013-09-16','Cash',4,  1, '180',180.000,2.000,'',1380814610),
 (5,'2013-09-16','Cash',5,  1, '7259',225.000,1.000,'',1380814649),
 (6,'2013-09-16','Cash',6,  1, '7259',180.000,2.000,'',1380814876),
 (7,'2013-10-03','Cash',7,  1, 'Old Stock',95.000,1.000,'',1380814986),
 (8,'2013-09-09','Cash',8,  1, '7204',52.000,15.000,'',1380816175),
 (9,'2013-07-25','Cash',9,  1, '1569',105.000,1.000,'',1380817586),
(10,'2013-07-14','Cash',10, 1, '1483',90.000,10.000,'',1380817675),
(11,'2013-07-14','Cash',11, 1, '1483',88.000,10.000,'',1380817724),
(12,'2013-10-03','Cash',12, 1, '0696',85.000,9.000,'',1380817824),
(13,'2013-08-01','Cash',13, 1, '23654',95.000,4.000,'',1380825314),
(14,'2013-09-01','Cash',14, 1, '23654',135.000,2.000,'',1380825355),
(15,'2013-09-01','Cash',15, 1, '23654',170.000,2.000,'',1380825399),
(16,'2013-10-03','Cash',16, 1, '22295',230.000,4.000,'',1380825496),
(17,'2013-09-30','Cash',19, 1, 'shjh1314ssv1051',512.000,2.000,'',1380980902),
(18,'2013-09-30','Cash',18, 1, 'shjh1314ssv1050',417.000,2.000,'',1380980963),
(19,'2013-09-30','Cash',17, 1, 'shjh1314ssv1050',607.000,2.000,'',1380980997),
(20,'2013-09-07','Cash',20, 1, '2769',480.000,5.000,'',1381239625),
(21,'2013-09-07','Cash',21, 1, '2796',205.000,10.000,'',1381239667),
(22,'2013-09-07','Cash',22, 1, '2769',210.000,5.000,'',1381239699),
(23,'2013-09-07','Cash',23, 1, '2769',80.000,5.000,'',1381239779),
(24,'2013-09-07','Cash',24, 1, '2769',80.000,5.000,'',1381239809),
(25,'2013-10-08','Cash',25, 1, 'Old Stock',26.000,10.000,'',1381241823),
(26,'2013-10-08','Cash',26, 1, 'Old Stock',18.000,10.000,'',1381241846),
(27,'2013-10-08','Cash',27, 1, 'Old Stock',165.000,6.000,'',1381242018),
(28,'2013-10-08','Cash',28, 1, 'Old Stock',130.000,4.000,'',1381242672),
(29,'2013-10-08','Cash',29, 1, '21700',20.000,24.000,'',1381245121),
(30,'2013-10-08','Cash',30, 1, '21700',50.000,2.000,'',1381245158),
(31,'2013-10-08','Cash',34, 1, '21700',100.000,1.000,'',1381245352),
(32,'2013-10-08','Cash',33, 1, '21700',14.000,12.000,'',1381245494),
(34,'2013-10-08','Cash',31, 1, '21700',4.250,30.000,'',1381249507),
(35,'2013-10-08','Cash',32, 1, '21700',5.000,20.000,'',1381249557);

INSERT INTO `clients` VALUES
(1,'Lighting Trading','0998473','84849432','test@test.com','Some details'),
(2,'ABC Trading','94949494','290-8902-324','blas@abc.com','shop no. 84, rolla shajrha'),
(3,'Al Naeem Ayub','8885895','22229999','','Al khan shajraha'),
(4,'Halaaa','04747160-94','78165153','hal@halllaa.com','Deira bridge, dubai, UAE'),
(5,'New Pumps','940-19774','7266405','newpumpt@ndlkjds.com', 'Ware house no. 44, Industrial area 14, Dubai Jabal Ali freezone'),
(6,'Class Trading ','5556489600','6581861','lakdjf@lkdf.com',''),
(7,'Rolla Mall','4498955','65416198','ljdkl@cdla.com','Some details about this client hellow work'),
(8,'Lulu Building','848909875','89475895','asdf@dasf.com','');

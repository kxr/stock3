DROP DATABASE ktdb_stock;
CREATE DATABASE IF NOT EXISTS `ktdb_stock`;
USE `ktdb_stock`;



CREATE TABLE IF NOT EXISTS `vendors` (
  `vendor_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(50) NOT NULL,
  `vendor_phone` varchar(50) DEFAULT NULL,
  `vendor_fax` varchar(20) DEFAULT NULL,
  `vendor_email` varchar(30) DEFAULT NULL,
  `vendor_details` varchar(255) DEFAULT NULL,
 PRIMARY KEY (`vendor_id`)
);
CREATE TABLE IF NOT EXISTS `payment_out` (
  `payment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `payment_type` varchar(20) NOT NULL,
  `ref_no` varchar(20) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `timestamp` int(8) DEFAULT NULL,
 PRIMARY KEY (`payment_id`)
);
/*
CREATE TABLE IF NOT EXISTS `clients` (
  `client_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(50) NOT NULL,
  `client_phone` varchar(50) DEFAULT NULL,
  `client_fax` varchar(20) DEFAULT NULL,
  `client_email` varchar(30) DEFAULT NULL,
  `client_details` varchar(255) DEFAULT NULL,
 PRIMARY KEY (`vendor_id`)
);
*/

CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(50) DEFAULT NULL,
  `item_detail` varchar(255) DEFAULT NULL,
  `item_saleprice` decimal(10,3) unsigned DEFAULT '0',
  PRIMARY KEY (`item_id`)
);

CREATE TABLE IF NOT EXISTS `sale_transactions` (
  `sale_trans_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `item_id` int(10) unsigned NOT NULL,
  `invoice_no` varchar(20) DEFAULT NULL,
  `uprice` decimal(10,3) unsigned DEFAULT NULL,
  `qty` decimal(10,3) unsigned DEFAULT NULL,
  `amount_received` decimal(10,3) unsigned DEFAULT '0',
  `comments` varchar(255) DEFAULT NULL,
  `timestamp` int(8) DEFAULT NULL,
  PRIMARY KEY (`sale_trans_id`)
);

CREATE TABLE IF NOT EXISTS `sale_invoices` (
  `sale_invoice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `invoice_title` varchar(50) DEFAULT NULL,
  `invoice_note` varchar(200) DEFAULT NULL,
  `amount_received` decimal(10,3) unsigned DEFAULT '0',
  `raw_data` text DEFAULT NULL,
  `timestamp` int(8) DEFAULT '0',
 PRIMARY KEY (`sale_invoice_id`)
);

CREATE TABLE IF NOT EXISTS `purchase_transactions` (
  `p_trans_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `item_id` int(10) unsigned NOT NULL,
  `vendor_id` int(10) unsigned DEFAULT NULL,
  `invoice_id` int(20) DEFAULT NULL,
  `uprice` decimal(10,3) unsigned DEFAULT NULL,
  `qty` decimal(10,3) unsigned DEFAULT NULL,
  `comments` varchar(50) DEFAULT NULL,
  `timestamp` int(8) DEFAULT NULL,
  PRIMARY KEY (`p_trans_id`)
);

CREATE TABLE IF NOT EXISTS `purchase_invoices` (
  `p_invoice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `vendor_id` int(10) NOT NULL,
  `vendor_invoice_no` varchar(20) NOT NULL,
  `invoice_total` decimal(10,3) unsigned NOT NULL,
  `invoice_note` varchar(255) DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  `payment_id` int(10) unsigned DEFAULT NULL,
  `timestamp` int(8) DEFAULT '0',
 PRIMARY KEY (`p_invoice_id`)
);
/*
CREATE TABLE IF NOT EXISTS `credit_transactions` (
  `c_trans_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `item_id` int(10) unsigned NOT NULL,
  `client_id` int(10) unsigned DEFAULT NULL,
  `invoice_id` int(20) DEFAULT NULL,
  `uprice` decimal(10,3) unsigned DEFAULT NULL,
  `qty` decimal(10,3) unsigned DEFAULT NULL,
  `comments` varchar(50) DEFAULT NULL,
  `timestamp` int(8) DEFAULT NULL,
  PRIMARY KEY (`p_trans_id`)
);

CREATE TABLE IF NOT EXISTS `credit_invoices` (
  `p_invoice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '1970-01-01',
  `vendor_id` int(10) NOT NULL,
  `vendor_invoice_no` varchar(20) NOT NULL,
  `invoice_total` decimal(10,3) unsigned NOT NULL,
  `invoice_note` varchar(255) DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  `payment_id` int(10) unsigned DEFAULT NULL,
  `timestamp` int(8) DEFAULT '0',
 PRIMARY KEY (`p_invoice_id`)
);
*/

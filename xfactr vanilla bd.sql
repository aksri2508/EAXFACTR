/*
SQLyog Community v13.0.1 (64 bit)
MySQL - 8.0.40 : Database - xfactr_live
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`xfactr_live` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `xfactr_live`;

/*Table structure for table `tbl_access_control` */

DROP TABLE IF EXISTS `tbl_access_control`;

CREATE TABLE `tbl_access_control` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `access_control_name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `is_system_config` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not sytem config, 1 - system config ',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_access_control` */

insert  into `tbl_access_control`(`id`,`access_control_name`,`status`,`is_system_config`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,'Administrator',1,1,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(2,'Customer',1,1,'2023-07-31 04:09:50',1,'2023-08-26 12:49:51',1,0,'','WEB','WEB',0),
(3,'Dealer',1,1,'2023-07-31 04:10:30',1,'2023-08-31 08:54:46',1,0,'','WEB','WEB',0);

/*Table structure for table `tbl_access_control_screen_list` */

DROP TABLE IF EXISTS `tbl_access_control_screen_list`;

CREATE TABLE `tbl_access_control_screen_list` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `access_control_id` int unsigned NOT NULL,
  `master_module_screen_mapping_id` int unsigned NOT NULL,
  `enable_view` tinyint NOT NULL DEFAULT '1' COMMENT '1 - No, 2 - Yes, 3 - Not visible',
  `enable_add` tinyint NOT NULL DEFAULT '1' COMMENT '1 - No, 2 - Yes, 3 - Not visible',
  `enable_update` tinyint NOT NULL DEFAULT '1' COMMENT '1 - No, 2 - Yes, 3 - Not visible',
  `enable_download` tinyint NOT NULL DEFAULT '1' COMMENT '1 - No, 2 - Yes, 3 - Not visible',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_access_control_screen_list` */

insert  into `tbl_access_control_screen_list`(`id`,`access_control_id`,`master_module_screen_mapping_id`,`enable_view`,`enable_add`,`enable_update`,`enable_download`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,1,4,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(2,1,1,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(3,1,2,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(4,1,3,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(5,1,10,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(6,1,14,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(7,1,12,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(8,1,11,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(9,1,13,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(10,1,5,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(11,1,6,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(12,1,7,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(13,1,16,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(14,1,8,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(15,1,9,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(16,1,15,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(17,1,27,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(18,1,28,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(19,1,29,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(20,1,17,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(21,1,30,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(22,1,31,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(23,1,32,2,3,3,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(24,1,33,2,3,3,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(25,1,34,2,3,3,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(26,1,18,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(27,1,26,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(28,1,19,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(29,1,20,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(30,1,21,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(31,1,22,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(32,1,23,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(33,1,25,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(34,1,24,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(35,1,35,2,2,2,2,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(36,1,36,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(37,1,37,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(38,1,38,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(39,1,39,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(40,1,40,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(41,1,41,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(42,1,42,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(43,1,43,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(44,1,44,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(45,1,45,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(46,1,46,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(47,1,47,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(48,1,48,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(49,1,49,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(50,1,50,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(51,1,51,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(52,1,52,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(53,1,53,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(54,1,54,2,3,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(55,1,55,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(56,1,56,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(57,1,57,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(58,1,58,2,3,3,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(59,1,62,2,2,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(60,1,59,2,3,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(61,1,60,2,3,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(62,1,61,2,3,2,3,'2023-07-27 11:22:52',1,'2023-07-27 11:22:52',NULL,0,'','WEB','SAP',0),
(63,2,2,2,1,1,2,'2023-07-31 04:09:50',1,'2023-08-26 12:49:51',1,0,'','WEB','WEB',0),
(64,2,3,2,2,2,2,'2023-07-31 04:09:50',1,'2023-08-26 12:49:51',1,0,'','WEB','WEB',0),
(65,2,10,2,1,1,2,'2023-07-31 04:09:50',1,'2023-08-26 12:49:51',1,0,'','WEB','WEB',0),
(66,2,14,2,1,1,2,'2023-07-31 04:09:50',1,'2023-08-26 12:49:51',1,0,'','WEB','WEB',0),
(67,2,12,2,1,1,2,'2023-07-31 04:09:50',1,'2023-08-26 12:49:51',1,0,'','WEB','WEB',0),
(68,2,11,2,1,1,2,'2023-07-31 04:09:50',1,'2023-08-26 12:49:51',1,0,'','WEB','WEB',0),
(69,2,13,2,1,1,2,'2023-07-31 04:09:50',1,'2023-08-26 12:49:51',1,0,'','WEB','WEB',0),
(70,2,60,2,3,2,3,'2023-07-31 04:09:50',1,'2023-08-26 12:49:51',1,0,'','WEB','WEB',0),
(71,2,61,2,3,2,3,'2023-07-31 04:09:50',1,'2023-08-26 12:49:51',1,0,'','WEB','WEB',0),
(72,3,4,1,1,1,1,'2023-07-31 04:10:30',1,'2023-12-09 05:33:42',1,0,'','WEB','WEB',0),
(73,3,1,1,1,1,1,'2023-07-31 04:10:31',1,'2023-12-09 05:33:42',1,0,'','WEB','WEB',0),
(74,3,60,2,3,2,3,'2023-07-31 04:10:31',1,'2023-12-09 05:33:42',1,0,'','WEB','WEB',0),
(75,3,61,2,3,2,3,'2023-07-31 04:10:31',1,'2023-12-09 05:33:42',1,0,'','WEB','WEB',0),
(154,3,2,2,1,1,2,'2023-12-09 05:29:56',1,'2023-12-09 05:33:42',1,0,'','WEB','WEB',0),
(155,3,3,2,2,2,2,'2023-12-09 05:29:56',1,'2023-12-09 05:33:42',1,0,'','WEB','WEB',0),
(156,3,10,2,1,1,2,'2023-12-09 05:29:56',1,'2023-12-09 05:33:42',1,0,'','WEB','WEB',0),
(157,3,14,2,1,1,2,'2023-12-09 05:29:56',1,'2023-12-09 05:33:42',1,0,'','WEB','WEB',0),
(158,3,12,2,1,1,2,'2023-12-09 05:29:56',1,'2023-12-09 05:33:42',1,0,'','WEB','WEB',0),
(159,3,11,2,1,1,2,'2023-12-09 05:29:56',1,'2023-12-09 05:33:42',1,0,'','WEB','WEB',0),
(160,3,13,2,1,1,2,'2023-12-09 05:29:56',1,'2023-12-09 05:33:42',1,0,'','WEB','WEB',0),
(161,7,4,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(162,7,1,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(163,7,2,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(164,7,3,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(165,7,10,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(166,7,14,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(167,7,12,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(168,7,11,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(169,7,13,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(170,7,5,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(171,7,6,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(172,7,7,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(173,7,16,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(174,7,8,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(175,7,9,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(176,7,15,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(177,7,27,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(178,7,28,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(179,7,29,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(180,7,17,2,2,2,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(181,7,30,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(182,7,31,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(183,7,32,2,3,3,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(184,7,33,2,3,3,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(185,7,34,2,3,3,2,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(186,7,36,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(187,7,37,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(188,7,38,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(189,7,39,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(190,7,40,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(191,7,41,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(192,7,42,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(193,7,43,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(194,7,44,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(195,7,45,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(196,7,46,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(197,7,47,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(198,7,48,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(199,7,49,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(200,7,50,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(201,7,51,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(202,7,52,2,2,2,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(203,7,53,1,1,1,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(204,7,54,1,3,1,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(205,7,55,1,1,1,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(206,7,56,1,1,1,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(207,7,57,1,1,1,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(208,7,62,1,1,1,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(209,7,59,1,3,1,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(210,7,60,1,3,1,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0),
(211,7,61,1,3,1,3,'2024-01-19 10:05:55',1,'2024-01-19 10:15:22',1,0,'','WEB','WEB',0);

/*Table structure for table `tbl_activity` */

DROP TABLE IF EXISTS `tbl_activity`;

CREATE TABLE `tbl_activity` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `activity_no` int unsigned NOT NULL DEFAULT '0',
  `activity_type_id` int unsigned NOT NULL,
  `assigned_to_id` int unsigned NOT NULL,
  `business_partner_id` int unsigned NOT NULL,
  `bp_contacts_id` int unsigned NOT NULL,
  `remarks` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `start_date_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recurrence_type_id` int unsigned NOT NULL COMMENT '1-daily,2-weekly,3-monthly,4-yearly',
  `recurrence_end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reminder_before_time` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '' COMMENT 'Hours or Timer',
  `reminder_type_id` int unsigned NOT NULL DEFAULT '1' COMMENT '1 - minutes, 2 - hours',
  `priority_id` int unsigned NOT NULL COMMENT '1 - Low, 2 - Normal , 3 - High',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active, 3 - Closed',
  `udf_fields` text NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `document_id` int unsigned NOT NULL,
  `document_type_id` tinyint NOT NULL DEFAULT '1' COMMENT '1-opportunity,2-sales quote,3-sales order',
  `branch_id` int NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_activity` */

/*Table structure for table `tbl_approval_status_report` */

DROP TABLE IF EXISTS `tbl_approval_status_report`;

CREATE TABLE `tbl_approval_status_report` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_id` int unsigned NOT NULL,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `document_type_id` tinyint NOT NULL DEFAULT '1' COMMENT '1-opportunity,2-sales quote,3-sales order',
  `document_created_by` int unsigned NOT NULL,
  `overall_approval_status` tinyint NOT NULL DEFAULT '1' COMMENT '1-Pending, 2 - Approved, 3 Rejected',
  `last_remarks` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `no_of_approvals` tinyint NOT NULL DEFAULT '0',
  `no_of_rejections` tinyint NOT NULL DEFAULT '0',
  `total_approved` tinyint NOT NULL DEFAULT '0' COMMENT 'total number of approvals',
  `total_rejected` tinyint NOT NULL DEFAULT '0' COMMENT 'total number of rejections',
  `approvers_id` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_approval_status_report` */

/*Table structure for table `tbl_approval_templates` */

DROP TABLE IF EXISTS `tbl_approval_templates`;

CREATE TABLE `tbl_approval_templates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `template_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `template_description` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `originator_id` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_id` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `approval_stages_id` int unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_approval_templates` */

/*Table structure for table `tbl_approval_templates_validation` */

DROP TABLE IF EXISTS `tbl_approval_templates_validation`;

CREATE TABLE `tbl_approval_templates_validation` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `approval_template_id` int unsigned NOT NULL,
  `orginator_document_id` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_approval_templates_validation` */

/*Table structure for table `tbl_attachments` */

DROP TABLE IF EXISTS `tbl_attachments`;

CREATE TABLE `tbl_attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `reference_id` int unsigned NOT NULL,
  `file_name` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `screen_name` enum('SALES_QUOTE','SALES_ORDER','ACTIVITY','EMPLOYEE_PROFILE','BUSINESS_PARTNER','OPPORTUNITY','MASTER_ITEM','PURCHASE_REQUEST','PURCHASE_ORDER','GRPO','INVENTORY_TRANSFER_REQUEST','INVENTORY_TRANSFER','SALES_DELIVERY','SALES_AR_INVOICE','SALES_AR_DP_INVOICE','SALES_AR_CREDIT_MEMO','SALES_RETURN','RENTAL_QUOTE','RENTAL_ORDER','RENTAL_INSPECTION_OUT','RENTAL_DELIVERY','RENTAL_RETURN','RENTAL_INSPECTION_IN','RENTAL_INVOICE','RENTAL_WORKLOG','MASTER_RENTAL_ITEM','MASTER_RENTAL_EQUIPMENT') DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_attachments` */

/*Table structure for table `tbl_bp_address` */

DROP TABLE IF EXISTS `tbl_bp_address`;

CREATE TABLE `tbl_bp_address` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `business_partner_id` int unsigned NOT NULL,
  `address_type_id` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Ship To , 2 - Bill To , 3 - Pay To ',
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `state_id` int unsigned NOT NULL DEFAULT '0',
  `city` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `zipcode` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `default_address` tinyint NOT NULL DEFAULT '0' COMMENT '0- Not Default,  1 - Default',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1164 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_bp_address` */

/*Table structure for table `tbl_bp_contacts` */

DROP TABLE IF EXISTS `tbl_bp_contacts`;

CREATE TABLE `tbl_bp_contacts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `business_partner_id` int unsigned NOT NULL,
  `contact_type_id` tinyint NOT NULL DEFAULT '1' COMMENT '1 - General, 2 - Other',
  `contact_email_id` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `contact_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `primary_country_code` varchar(16) NOT NULL DEFAULT '',
  `primary_contact_no` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=207 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_bp_contacts` */

/*Table structure for table `tbl_business_partner` */

DROP TABLE IF EXISTS `tbl_business_partner`;

CREATE TABLE `tbl_business_partner` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `partner_type_id` int unsigned NOT NULL,
  `partner_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `partner_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `pan_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint unsigned NOT NULL,
  `credit_limit` float unsigned NOT NULL DEFAULT '0',
  `account_balance` float unsigned NOT NULL DEFAULT '0',
  `emp_id` int unsigned NOT NULL DEFAULT '0',
  `industry_id` int unsigned NOT NULL DEFAULT '0',
  `territory_id` int unsigned NOT NULL DEFAULT '0',
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `price_list_id` int unsigned NOT NULL DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=899 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_business_partner` */

/*Table structure for table `tbl_company_details` */

DROP TABLE IF EXISTS `tbl_company_details`;

CREATE TABLE `tbl_company_details` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `company_logo` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `tax_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `location` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin,
  `hierarchy_mode` tinyint NOT NULL DEFAULT '1' COMMENT '1 -Distribution Rules, 2 - Reporting Manager',
  `udf_mode` tinyint NOT NULL DEFAULT '1' COMMENT '0 - Apply for old records, 1 - Apply for new records only',
  `toaster_freeze` tinyint NOT NULL DEFAULT '1' COMMENT '0 - off Freeze, 1 - Enable Freeze',
  `toaster_position` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT 'top-right' COMMENT 'Toaster Position values - top-right,top-left,bottom-right,bottom-left,top-center,bottom-center,center',
  `rental_worklog_sheet_type` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Sheet type-1, 2 - Sheet type-2',
  `approvers_modify_document` tinyint NOT NULL DEFAULT '1',
  `is_sap` tinyint NOT NULL DEFAULT '1',
  `smtp_host` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '' COMMENT 'smtp.mandrillapp.com or smtp.amazon.com',
  `smtp_secure` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `smtp_protocol` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '' COMMENT 'smtp',
  `smtp_port` smallint NOT NULL DEFAULT '0' COMMENT '465 or any other port',
  `smtp_username` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `smtp_password` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `mail_provider` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sender_user_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sender_user_emailid` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `total_users_count` int unsigned NOT NULL DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_company_details` */

insert  into `tbl_company_details`(`id`,`company_name`,`company_logo`,`tax_number`,`location`,`hierarchy_mode`,`udf_mode`,`toaster_freeze`,`toaster_position`,`rental_worklog_sheet_type`,`approvers_modify_document`,`is_sap`,`smtp_host`,`smtp_secure`,`smtp_protocol`,`smtp_port`,`smtp_username`,`smtp_password`,`mail_provider`,`sender_user_name`,`sender_user_emailid`,`total_users_count`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,'NEHMEH - LIVE','','','Qatar',2,1,0,'top-right',2,1,1,'smtp.gmail.com','TLS','Test smtpHost13',587,'xfactrteam@gmail.com','ydch iudc ufwc wcmf','Gmail','Nehmeh','xfactrteam@gmail.com',150,'2019-07-28 01:13:50',1,'2023-07-15 03:02:32',1,0,'0','SAP','WEB',0);

/*Table structure for table `tbl_dev_api_tracker` */

DROP TABLE IF EXISTS `tbl_dev_api_tracker`;

CREATE TABLE `tbl_dev_api_tracker` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `module_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `screen_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `api_url` varchar(2048) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `method_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `method_type` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `request_parameter` text NOT NULL,
  `response_parameter` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=642 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_dev_api_tracker` */

/*Table structure for table `tbl_employee_attendance` */

DROP TABLE IF EXISTS `tbl_employee_attendance`;

CREATE TABLE `tbl_employee_attendance` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `team_id` int unsigned DEFAULT NULL,
  `emp_id` int unsigned DEFAULT NULL,
  `punch_in_datetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `punch_out_datetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `punch_in_latitude` decimal(10,8) NOT NULL DEFAULT '0.00000000',
  `punch_out_latitude` decimal(10,8) NOT NULL DEFAULT '0.00000000',
  `punch_in_longitude` decimal(10,8) NOT NULL DEFAULT '0.00000000',
  `punch_out_longitude` decimal(10,8) NOT NULL DEFAULT '0.00000000',
  `branch_id` int NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=553 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_employee_attendance` */

/*Table structure for table `tbl_employee_profile` */

DROP TABLE IF EXISTS `tbl_employee_profile`;

CREATE TABLE `tbl_employee_profile` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `emp_code` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `gender_id` int unsigned NOT NULL COMMENT '1 - Male, 2 -Female',
  `first_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `last_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `email_id` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `primary_country_code` varchar(16) NOT NULL DEFAULT '',
  `primary_contact_no` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `profile_img` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `remarks` text,
  `branch_id` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL COMMENT 'STORING MULTIPLE BRANCH IDS',
  `designation_id` int unsigned NOT NULL COMMENT 'Employee Designation',
  `employee_type_id` tinyint NOT NULL DEFAULT '0',
  `business_partner_id` varchar(64) NOT NULL,
  `territory_id` int unsigned NOT NULL,
  `reporting_manager_id` int unsigned NOT NULL COMMENT 'Reporting Manager',
  `is_user` tinyint NOT NULL DEFAULT '0',
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `access_control_id` int unsigned NOT NULL,
  `system_user` enum('YES','NO') DEFAULT 'NO',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=276 DEFAULT CHARSET=utf8mb3;

/*Data for the table `tbl_employee_profile` */

insert  into `tbl_employee_profile`(`id`,`emp_code`,`gender_id`,`first_name`,`last_name`,`email_id`,`primary_country_code`,`primary_contact_no`,`profile_img`,`status`,`remarks`,`branch_id`,`designation_id`,`employee_type_id`,`business_partner_id`,`territory_id`,`reporting_manager_id`,`is_user`,`distribution_rules_id`,`access_control_id`,`system_user`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,'AD101',1,'Admin','EA1','admin@sap.com','91','9791630311','',1,'','1,2,3,4,5,6,7,8,9,10',1,2,'',0,1,1,'',1,'YES','2019-07-28 01:13:56',1,'2023-12-14 05:39:52',1,0,'AD101','','SAP','WEB',0);

/*Table structure for table `tbl_grpo` */

DROP TABLE IF EXISTS `tbl_grpo`;

CREATE TABLE `tbl_grpo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `vendor_bp_id` int unsigned NOT NULL,
  `vendor_bp_contacts_id` int unsigned NOT NULL,
  `vendor_ship_to_bp_address_id` int unsigned NOT NULL,
  `vendor_ship_to_address` text NOT NULL,
  `vendor_pay_to_bp_address_id` int unsigned NOT NULL,
  `vendor_pay_to_address` text NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint NOT NULL DEFAULT '1',
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `approval_status` tinyint NOT NULL DEFAULT '4' COMMENT '1 - PENDING, 2 - APPROVED, 3 - REJECTED 4 - No Approval process',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `buyer_emp_id` int unsigned NOT NULL,
  `cancellation_date` date NOT NULL DEFAULT '0000-00-00',
  `goods_in_transit` tinyint NOT NULL DEFAULT '0' COMMENT '1 - YES, 2 - NO',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_grpo` */

/*Table structure for table `tbl_grpo_items` */

DROP TABLE IF EXISTS `tbl_grpo_items`;

CREATE TABLE `tbl_grpo_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','PURCHASE_ORDER') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `grpo_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double unsigned NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `unit_price` double unsigned NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL,
  `bin_id` int unsigned DEFAULT '0',
  `last_price` double unsigned DEFAULT '0',
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_grpo_items` */

/*Table structure for table `tbl_individual_approval_status_report` */

DROP TABLE IF EXISTS `tbl_individual_approval_status_report`;

CREATE TABLE `tbl_individual_approval_status_report` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `approval_status_report_id` int unsigned NOT NULL,
  `approval_status` tinyint NOT NULL DEFAULT '1' COMMENT '1-Pending, 2 - Approved, 3 Rejected',
  `approver_remarks` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `approver_id` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `prev_approval_flag` tinyint NOT NULL DEFAULT '0' COMMENT '1 - old record , 0 - new record',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_individual_approval_status_report` */

/*Table structure for table `tbl_inventory_transfer` */

DROP TABLE IF EXISTS `tbl_inventory_transfer`;

CREATE TABLE `tbl_inventory_transfer` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `business_partner_id` int unsigned NOT NULL,
  `bp_contacts_id` int unsigned NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `intra_branch_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 - No Transfer , 1 - Transfer with in branch',
  `status` tinyint NOT NULL DEFAULT '1',
  `duty_status_id` tinyint NOT NULL DEFAULT '1',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `sales_emp_id` int unsigned NOT NULL,
  `from_warehouse_id` int unsigned NOT NULL,
  `to_warehouse_id` int unsigned NOT NULL,
  `udf_fields` text NOT NULL,
  `approval_status` tinyint NOT NULL DEFAULT '4' COMMENT '1 - PENDING, 2 - APPROVED, 3 - REJECTED 4 - No Approval process',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `branch_id` int NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_inventory_transfer` */

/*Table structure for table `tbl_inventory_transfer_items` */

DROP TABLE IF EXISTS `tbl_inventory_transfer_items`;

CREATE TABLE `tbl_inventory_transfer_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','INVENTORY_TRANSFER_REQUEST') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `inventory_transfer_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double unsigned NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `from_warehouse_id` int unsigned NOT NULL,
  `from_bin_id` int unsigned DEFAULT '0',
  `to_warehouse_id` int unsigned NOT NULL,
  `to_bin_id` int unsigned DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_inventory_transfer_items` */

/*Table structure for table `tbl_inventory_transfer_request` */

DROP TABLE IF EXISTS `tbl_inventory_transfer_request`;

CREATE TABLE `tbl_inventory_transfer_request` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `business_partner_id` int unsigned NOT NULL,
  `bp_contacts_id` int unsigned NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `intra_branch_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 - No Transfer , 1 - Transfer with in branch',
  `status` tinyint NOT NULL DEFAULT '1',
  `duty_status_id` tinyint NOT NULL DEFAULT '1',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `sales_emp_id` int unsigned NOT NULL,
  `from_warehouse_id` int unsigned NOT NULL,
  `to_warehouse_id` int unsigned NOT NULL,
  `udf_fields` text NOT NULL,
  `approval_status` tinyint NOT NULL DEFAULT '4' COMMENT '1 - PENDING, 2 - APPROVED, 3 - REJECTED 4 - No Approval process',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `branch_id` int NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_inventory_transfer_request` */

/*Table structure for table `tbl_inventory_transfer_request_items` */

DROP TABLE IF EXISTS `tbl_inventory_transfer_request_items`;

CREATE TABLE `tbl_inventory_transfer_request_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','PURCHASE_REQUEST','PURCHASE_ORDER') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `inventory_transfer_request_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double unsigned NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `from_warehouse_id` int unsigned NOT NULL,
  `from_bin_id` int unsigned DEFAULT '0',
  `to_warehouse_id` int unsigned NOT NULL,
  `to_bin_id` int unsigned DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_inventory_transfer_request_items` */

/*Table structure for table `tbl_item_stocks` */

DROP TABLE IF EXISTS `tbl_item_stocks`;

CREATE TABLE `tbl_item_stocks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int unsigned DEFAULT NULL,
  `warehouse_id` int unsigned NOT NULL,
  `bin_id` int unsigned NOT NULL DEFAULT '0',
  `availability` double unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_item_stocks` */

/*Table structure for table `tbl_item_warehouse` */

DROP TABLE IF EXISTS `tbl_item_warehouse`;

CREATE TABLE `tbl_item_warehouse` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int unsigned DEFAULT NULL,
  `warehouse_id` int unsigned DEFAULT NULL,
  `bin_id` int unsigned DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`),
  KEY `item_index` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=171473 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_item_warehouse` */

/*Table structure for table `tbl_line_item_configuration` */

DROP TABLE IF EXISTS `tbl_line_item_configuration`;

CREATE TABLE `tbl_line_item_configuration` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `module` enum('SALES_QUOTE','SALES_ORDER','PURCHASE_REQUEST','PURCHASE_ORDER','GRPO','INVENTORY_TRANSFER_REQUEST','INVENTORY_TRANSFER','SALES_DELIVERY','SALES_AR_INVOICE','SALES_AR_DP_INVOICE','SALES_AR_CREDIT_MEMO','SALES_RETURN','RENTAL_QUOTE','RENTAL_ORDER','RENTAL_DELIVERY','RENTAL_RETURN','RENTAL_INVOICE') NOT NULL,
  `default_fields` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `disabled_fields` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_line_item_configuration` */

insert  into `tbl_line_item_configuration`(`id`,`module`,`default_fields`,`disabled_fields`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,'SALES_QUOTE','0,1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18','3,4,15','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','','SAP','SAP',0),
(2,'SALES_ORDER','0,1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18','3,4,15','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','','SAP','SAP',0),
(3,'SALES_DELIVERY','0,1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18','3,4,8,9,15','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,1,'','','SAP','SAP',0),
(4,'SALES_RETURN','0,1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18','3,4,8,9,15','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,1,'','','SAP','SAP',0),
(5,'SALES_AR_DP_INVOICE','0,1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18','3,4,8,9,15','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','','SAP','SAP',0),
(6,'SALES_AR_INVOICE','0,1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18','3,4,8,9,15','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','','SAP','SAP',0),
(7,'SALES_AR_CREDIT_MEMO','0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18','3,4,8,9,15','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(8,'PURCHASE_REQUEST','0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15','2,3,4,12','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(9,'PURCHASE_ORDER','0,1,2,4,5,6,7,8,9,10,11,12,13,14,15','2,4,12','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(10,'GRPO','0,1,2,4,5,6,7,8,9,10,11,12,13,14,15','2,4,8,9,12','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(11,'INVENTORY_TRANSFER_REQUEST','0,1,2,3,4,5,6,7,8,9','2,3,6,7,8,9','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(12,'INVENTORY_TRANSFER','0,1,2,3,4,5,6,7,8,9','2,3,6,7,8,9','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(13,'RENTAL_QUOTE','0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19','2,16,19','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(14,'RENTAL_ORDER','0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20','2,6,8,9,10,11,12,17,20','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(15,'RENTAL_DELIVERY','0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19','2,5,7,8,9,10,11,16,19','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(16,'RENTAL_RETURN','0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19','2,5,7,8,9,10,11,16,19','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(17,'RENTAL_INVOICE','0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20','2,6,8,9,10,11,12,17,20','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0);

/*Table structure for table `tbl_login` */

DROP TABLE IF EXISTS `tbl_login`;

CREATE TABLE `tbl_login` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int unsigned NOT NULL,
  `username` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `password` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb3;

/*Data for the table `tbl_login` */

insert  into `tbl_login`(`id`,`profile_id`,`username`,`password`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,1,'admin@sap.com','$2y$14$2SQcfLK.2cI1g3w/lxyu3.8rBKFOLzTEP5tgAABMtbOOem3.aAh/i','2019-07-28 01:13:51',1,'2023-12-14 05:39:52',1,0,'0','','SAP','WEB',0);

/*Table structure for table `tbl_master_activity` */

DROP TABLE IF EXISTS `tbl_master_activity`;

CREATE TABLE `tbl_master_activity` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `activity_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

/*Data for the table `tbl_master_activity` */

/*Table structure for table `tbl_master_alternative_items` */

DROP TABLE IF EXISTS `tbl_master_alternative_items`;

CREATE TABLE `tbl_master_alternative_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_alternative_items` */

/*Table structure for table `tbl_master_alternative_items_list` */

DROP TABLE IF EXISTS `tbl_master_alternative_items_list`;

CREATE TABLE `tbl_master_alternative_items_list` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `master_alternative_item_id` int unsigned NOT NULL,
  `alt_item_id` int unsigned NOT NULL,
  `remarks` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `match_factor` double NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_alternative_items_list` */

/*Table structure for table `tbl_master_approval_stages` */

DROP TABLE IF EXISTS `tbl_master_approval_stages`;

CREATE TABLE `tbl_master_approval_stages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `stage_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `stage_description` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `no_of_approvals` tinyint NOT NULL DEFAULT '1',
  `no_of_rejections` tinyint NOT NULL DEFAULT '1',
  `authorizer_id` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_approval_stages` */

/*Table structure for table `tbl_master_bin` */

DROP TABLE IF EXISTS `tbl_master_bin`;

CREATE TABLE `tbl_master_bin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `bin_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `bin_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6234 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_bin` */

/*Table structure for table `tbl_master_branches` */

DROP TABLE IF EXISTS `tbl_master_branches`;

CREATE TABLE `tbl_master_branches` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `branch_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `branch_name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `first_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `last_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `email_id` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_branches` */

insert  into `tbl_master_branches`(`id`,`branch_code`,`branch_name`,`first_name`,`last_name`,`email_id`,`status`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,'1','Qatar','','','',1,'2020-09-16 13:39:48',1,'0000-00-00 00:00:00',NULL,0,'3','','SAP','SAP',0);

/*Table structure for table `tbl_master_competitor` */

DROP TABLE IF EXISTS `tbl_master_competitor`;

CREATE TABLE `tbl_master_competitor` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `competitor_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `threat_level_id` tinyint NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_competitor` */

/*Table structure for table `tbl_master_cost_center` */

DROP TABLE IF EXISTS `tbl_master_cost_center`;

CREATE TABLE `tbl_master_cost_center` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `distribution_id` int unsigned NOT NULL DEFAULT '0',
  `center_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `center_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `emp_id` int unsigned NOT NULL DEFAULT '0',
  `sort_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `dimension_id` int unsigned DEFAULT NULL,
  `effective_from` date NOT NULL DEFAULT '0000-00-00',
  `effective_to` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_master_cost_center` */

/*Table structure for table `tbl_master_country` */

DROP TABLE IF EXISTS `tbl_master_country`;

CREATE TABLE `tbl_master_country` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `country_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `country_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `iso_code` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=243 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_master_country` */

insert  into `tbl_master_country`(`id`,`country_name`,`country_code`,`iso_code`,`status`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,'AD','Andorra','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AD','','SAP','SAP',0),
(2,'AE','United Arab Emir.','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AE','','SAP','SAP',0),
(3,'AF','Afghanistan','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AF','','SAP','SAP',0),
(4,'AG','Antigua/Barbuda','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AG','','SAP','SAP',0),
(5,'AI','Anguilla','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AI','','SAP','SAP',0),
(6,'AL','Albania','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AL','','SAP','SAP',0),
(7,'AM','Armenia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AM','','SAP','SAP',0),
(8,'AN','Dutch Antilles','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AN','','SAP','SAP',0),
(9,'AO','Angola','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AO','','SAP','SAP',0),
(10,'AQ','Antarctica','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AQ','','SAP','SAP',0),
(11,'AR','Argentina','10',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AR','','SAP','SAP',0),
(12,'AS','Samoa, American','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AS','','SAP','SAP',0),
(13,'AT','Austria','14',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AT','','SAP','SAP',0),
(14,'AU','Australia','9',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AU','','SAP','SAP',0),
(15,'AW','Aruba','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AW','','SAP','SAP',0),
(16,'AZ','Azerbaijan','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'AZ','','SAP','SAP',0),
(17,'BA','Bosnia-Herzegovina','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BA','','SAP','SAP',0),
(18,'BB','Barbados','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BB','','SAP','SAP',0),
(19,'BD','Bangladesh','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BD','','SAP','SAP',0),
(20,'BE','Belgium','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BE','','SAP','SAP',0),
(21,'BF','Burkina-Faso','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BF','','SAP','SAP',0),
(22,'BG','Bulgaria','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BG','','SAP','SAP',0),
(23,'BH','Bahrain','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BH','','SAP','SAP',0),
(24,'BI','Burundi','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BI','','SAP','SAP',0),
(25,'BJ','Benin','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BJ','','SAP','SAP',0),
(26,'BM','Bermuda','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BM','','SAP','SAP',0),
(27,'BN','Brunei Dar-es-S','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BN','','SAP','SAP',0),
(28,'BO','Bolivia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BO','','SAP','SAP',0),
(29,'BQ','Bonaire, Sint Eustatius en Saba','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BQ','','SAP','SAP',0),
(30,'BR','Brazil','7',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BR','','SAP','SAP',0),
(31,'BS','Bahamas','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BS','','SAP','SAP',0),
(32,'BT','Bhutan','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BT','','SAP','SAP',0),
(33,'BV','Bouvet Island','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BV','','SAP','SAP',0),
(34,'BW','Botswana','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BW','','SAP','SAP',0),
(35,'BY','Belarus','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BY','','SAP','SAP',0),
(36,'BZ','Belize','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'BZ','','SAP','SAP',0),
(37,'CA','Canada','5',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CA','','SAP','SAP',0),
(38,'CC','Coconut Islands','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CC','','SAP','SAP',0),
(39,'CD','Congo','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CD','','SAP','SAP',0),
(40,'CF','Central African Rep','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CF','','SAP','SAP',0),
(41,'CG','Congo','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CG','','SAP','SAP',0),
(42,'CH','Schweiz','11',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CH','','SAP','SAP',0),
(43,'CI','Ivory Coast','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CI','','SAP','SAP',0),
(44,'CK','Cook Islands','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CK','','SAP','SAP',0),
(45,'CL','Chile','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CL','','SAP','SAP',0),
(46,'CM','Cameroon','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CM','','SAP','SAP',0),
(47,'CN','China','10',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CN','','SAP','SAP',0),
(48,'CO','Colombia','10',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CO','','SAP','SAP',0),
(49,'CR','Costa Rica','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CR','','SAP','SAP',0),
(50,'CU','Cuba','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CU','','SAP','SAP',0),
(51,'CV','Cape Verde','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CV','','SAP','SAP',0),
(52,'CW','Curaçao','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CW','','SAP','SAP',0),
(53,'CX','Christmas Island','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CX','','SAP','SAP',0),
(54,'CY','Cyprus','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CY','','SAP','SAP',0),
(55,'CZ','Czech Republic','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'CZ','','SAP','SAP',0),
(56,'DE','Germany','15',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'DE','','SAP','SAP',0),
(57,'DJ','Djibouti','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'DJ','','SAP','SAP',0),
(58,'DK','Denmark','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'DK','','SAP','SAP',0),
(59,'DM','Dominica','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'DM','','SAP','SAP',0),
(60,'DO','Dominican Republic','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'DO','','SAP','SAP',0),
(61,'DZ','Algeria','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'DZ','','SAP','SAP',0),
(62,'EC','Ecuador','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'EC','','SAP','SAP',0),
(63,'EE','Estonia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'EE','','SAP','SAP',0),
(64,'EG','Egypt','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'EG','','SAP','SAP',0),
(65,'EH','West Sahara','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'EH','','SAP','SAP',0),
(66,'EL','Greece','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'EL','','SAP','SAP',0),
(67,'ER','Eritrea','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'ER','','SAP','SAP',0),
(68,'ES','Spain','10',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'ES','','SAP','SAP',0),
(69,'ET','Ethiopia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'ET','','SAP','SAP',0),
(70,'FI','Finland','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'FI','','SAP','SAP',0),
(71,'FJ','Fiji','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'FJ','','SAP','SAP',0),
(72,'FK','Falkland Islands','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'FK','','SAP','SAP',0),
(73,'FM','Micronesia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'FM','','SAP','SAP',0),
(74,'FO','Faroe Islands','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'FO','','SAP','SAP',0),
(75,'FR','France','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'FR','','SAP','SAP',0),
(76,'GA','Gabon','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GA','','SAP','SAP',0),
(77,'GB','United Kingdom','6',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GB','','SAP','SAP',0),
(78,'GD','Grenada','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GD','','SAP','SAP',0),
(79,'GE','Georgia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GE','','SAP','SAP',0),
(80,'GF','French Guayana','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GF','','SAP','SAP',0),
(81,'GH','Ghana','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GH','','SAP','SAP',0),
(82,'GI','Gibraltar','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GI','','SAP','SAP',0),
(83,'GL','Greenland','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GL','','SAP','SAP',0),
(84,'GM','Gambia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GM','','SAP','SAP',0),
(85,'GN','Guinea','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GN','','SAP','SAP',0),
(86,'GP','Guadeloupe','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GP','','SAP','SAP',0),
(87,'GQ','Equatorial Guinea','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GQ','','SAP','SAP',0),
(88,'GS','S. Sandwich Ins','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GS','','SAP','SAP',0),
(89,'GT','Guatemala','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GT','','SAP','SAP',0),
(90,'GU','Guam','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GU','','SAP','SAP',0),
(91,'GW','Guinea-Bissau','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GW','','SAP','SAP',0),
(92,'GY','Guyana','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'GY','','SAP','SAP',0),
(93,'HK','Hong Kong SAR of P.R.China','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'HK','','SAP','SAP',0),
(94,'HM','Heard/McDnld Islnds','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'HM','','SAP','SAP',0),
(95,'HN','Honduras','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'HN','','SAP','SAP',0),
(96,'HR','Croatia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'HR','','SAP','SAP',0),
(97,'HT','Haiti','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'HT','','SAP','SAP',0),
(98,'HU','Hungary','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'HU','','SAP','SAP',0),
(99,'ID','Indonesia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'ID','','SAP','SAP',0),
(100,'IE','Ireland','6',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'IE','','SAP','SAP',0),
(101,'IL','Israel','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'IL','','SAP','SAP',0),
(102,'IN','India','26',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'IN','','SAP','SAP',0),
(103,'IO','Brit.Ind.Oc.Ter','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'IO','','SAP','SAP',0),
(104,'IQ','Iraq','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'IQ','','SAP','SAP',0),
(105,'IR','Iran','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'IR','','SAP','SAP',0),
(106,'IS','Iceland','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'IS','','SAP','SAP',0),
(107,'IT','Italy','2',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'IT','','SAP','SAP',0),
(108,'JM','Jamaica','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'JM','','SAP','SAP',0),
(109,'JO','Jordan','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'JO','','SAP','SAP',0),
(110,'JP','Japan','13',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'JP','','SAP','SAP',0),
(111,'KE','Kenya','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'KE','','SAP','SAP',0),
(112,'KG','Kyrgyzstan','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'KG','','SAP','SAP',0),
(113,'KH','Cambodia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'KH','','SAP','SAP',0),
(114,'KI','Kiribati','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'KI','','SAP','SAP',0),
(115,'KM','Comoros','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'KM','','SAP','SAP',0),
(116,'KN','St Kitts & Nevis','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'KN','','SAP','SAP',0),
(117,'KP','North Korea','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'KP','','SAP','SAP',0),
(118,'KR','South Korea','17',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'KR','','SAP','SAP',0),
(119,'KW','Kuwait','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'KW','','SAP','SAP',0),
(120,'KY','Cayman Islands','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'KY','','SAP','SAP',0),
(121,'KZ','Kazakhstan','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'KZ','','SAP','SAP',0),
(122,'LA','Laos','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'LA','','SAP','SAP',0),
(123,'LB','Lebanon','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'LB','','SAP','SAP',0),
(124,'LC','St. Lucia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'LC','','SAP','SAP',0),
(125,'LI','Liechtenstein','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'LI','','SAP','SAP',0),
(126,'LK','Sri Lanka','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'LK','','SAP','SAP',0),
(127,'LR','Liberia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'LR','','SAP','SAP',0),
(128,'LS','Lesotho','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'LS','','SAP','SAP',0),
(129,'LT','Lithuania','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'LT','','SAP','SAP',0),
(130,'LU','Luxembourg','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'LU','','SAP','SAP',0),
(131,'LV','Latvia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'LV','','SAP','SAP',0),
(132,'LY','Libya','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'LY','','SAP','SAP',0),
(133,'MA','Morocco','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MA','','SAP','SAP',0),
(134,'MC','Monaco','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MC','','SAP','SAP',0),
(135,'MD','Moldavia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MD','','SAP','SAP',0),
(136,'MG','Madagascar','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MG','','SAP','SAP',0),
(137,'MH','Marshall Islands','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MH','','SAP','SAP',0),
(138,'MK','Macedonia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MK','','SAP','SAP',0),
(139,'ML','Mali','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'ML','','SAP','SAP',0),
(140,'MM','Myanmar','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MM','','SAP','SAP',0),
(141,'MN','Mongolia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MN','','SAP','SAP',0),
(142,'MO','Macau  SAR of P.R.China','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MO','','SAP','SAP',0),
(143,'MP','N.Mariana Island','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MP','','SAP','SAP',0),
(144,'MQ','Martinique','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MQ','','SAP','SAP',0),
(145,'MR','Mauretania','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MR','','SAP','SAP',0),
(146,'MS','Montserrat','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MS','','SAP','SAP',0),
(147,'MT','Malta','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MT','','SAP','SAP',0),
(148,'MU','Mauritius','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MU','','SAP','SAP',0),
(149,'MV','Maldives','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MV','','SAP','SAP',0),
(150,'MW','Malawi','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MW','','SAP','SAP',0),
(151,'MX','Mexico','10',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MX','','SAP','SAP',0),
(152,'MY','Malaysia','10',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MY','','SAP','SAP',0),
(153,'MZ','Mozambique','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'MZ','','SAP','SAP',0),
(154,'NA','Namibia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'NA','','SAP','SAP',0),
(155,'NC','New Caledonia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'NC','','SAP','SAP',0),
(156,'NE','Niger','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'NE','','SAP','SAP',0),
(157,'NF','Norfolk Island','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'NF','','SAP','SAP',0),
(158,'NG','Nigeria','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'NG','','SAP','SAP',0),
(159,'NI','Nicaragua','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'NI','','SAP','SAP',0),
(160,'NL','Netherlands','3',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'NL','','SAP','SAP',0),
(161,'NO','Norway','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'NO','','SAP','SAP',0),
(162,'NP','Nepal','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'NP','','SAP','SAP',0),
(163,'NR','Nauru','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'NR','','SAP','SAP',0),
(164,'NU','Niue Islands','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'NU','','SAP','SAP',0),
(165,'NZ','New Zealand','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'NZ','','SAP','SAP',0),
(166,'OM','Oman','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'OM','','SAP','SAP',0),
(167,'PA','Panama','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PA','','SAP','SAP',0),
(168,'PE','Peru','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PE','','SAP','SAP',0),
(169,'PF','French Polynesia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PF','','SAP','SAP',0),
(170,'PG','Papua New Guinea','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PG','','SAP','SAP',0),
(171,'PH','Philippines','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PH','','SAP','SAP',0),
(172,'PK','Pakistan','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PK','','SAP','SAP',0),
(173,'PL','Poland','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PL','','SAP','SAP',0),
(174,'PM','St.Pier,Miquel.','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PM','','SAP','SAP',0),
(175,'PN','Pitcairn Islands','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PN','','SAP','SAP',0),
(176,'PR','Puerto Rico','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PR','','SAP','SAP',0),
(177,'PT','Portugal','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PT','','SAP','SAP',0),
(178,'PW','Palau','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PW','','SAP','SAP',0),
(179,'PY','Paraguay','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'PY','','SAP','SAP',0),
(180,'QA','Qatar','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'QA','','SAP','SAP',0),
(181,'RE','Reunion','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'RE','','SAP','SAP',0),
(182,'RO','Romania','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'RO','','SAP','SAP',0),
(183,'RU','Russian Fed.','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'RU','','SAP','SAP',0),
(184,'RW','Ruanda','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'RW','','SAP','SAP',0),
(185,'SA','Saudi Arabia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SA','','SAP','SAP',0),
(186,'SB','Solomon Islands','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SB','','SAP','SAP',0),
(187,'SC','Seychelles','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SC','','SAP','SAP',0),
(188,'SD','Sudan','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SD','','SAP','SAP',0),
(189,'SE','Sweden','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SE','','SAP','SAP',0),
(190,'SG','Singapore','8',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SG','','SAP','SAP',0),
(191,'SH','St. Helena','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SH','','SAP','SAP',0),
(192,'SI','Slovenia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SI','','SAP','SAP',0),
(193,'SJ','Svalbard','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SJ','','SAP','SAP',0),
(194,'SK','Slovakia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SK','','SAP','SAP',0),
(195,'SL','Sierra Leone','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SL','','SAP','SAP',0),
(196,'SM','San Marino','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SM','','SAP','SAP',0),
(197,'SN','Senegal','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SN','','SAP','SAP',0),
(198,'SO','Somalia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SO','','SAP','SAP',0),
(199,'SR','Suriname','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SR','','SAP','SAP',0),
(200,'ST','S.Tome,Principe','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'ST','','SAP','SAP',0),
(201,'SV','El Salvador','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SV','','SAP','SAP',0),
(202,'SX','Sint Maarten','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SX','','SAP','SAP',0),
(203,'SY','Syria','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SY','','SAP','SAP',0),
(204,'SZ','Swaziland','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'SZ','','SAP','SAP',0),
(205,'TC','Turksh Caicosin','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TC','','SAP','SAP',0),
(206,'TD','Chad','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TD','','SAP','SAP',0),
(207,'TF','French S.Territ','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TF','','SAP','SAP',0),
(208,'TG','Togo','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TG','','SAP','SAP',0),
(209,'TH','Thailand','4',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TH','','SAP','SAP',0),
(210,'TJ','Tajikstan','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TJ','','SAP','SAP',0),
(211,'TK','Tokelau Islands','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TK','','SAP','SAP',0),
(212,'TM','Turkmenistan','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TM','','SAP','SAP',0),
(213,'TN','Tunisia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TN','','SAP','SAP',0),
(214,'TO','Tonga','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TO','','SAP','SAP',0),
(215,'TP','East Timor','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TP','','SAP','SAP',0),
(216,'TR','Turkey','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TR','','SAP','SAP',0),
(217,'TT','Trinidad,Tobago','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TT','','SAP','SAP',0),
(218,'TV','Tuvalu','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TV','','SAP','SAP',0),
(219,'TW','Taiwan, P.R.China','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TW','','SAP','SAP',0),
(220,'TZ','Tanzania','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'TZ','','SAP','SAP',0),
(221,'UA','Ukraine','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'UA','','SAP','SAP',0),
(222,'UG','Uganda','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'UG','','SAP','SAP',0),
(223,'UM','Minor Outl.Ins.','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'UM','','SAP','SAP',0),
(224,'US','USA','4',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'US','','SAP','SAP',0),
(225,'UY','Uruguay','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'UY','','SAP','SAP',0),
(226,'UZ','Uzbekistan','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'UZ','','SAP','SAP',0),
(227,'VA','Vatican City','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'VA','','SAP','SAP',0),
(228,'VC','St. Vincent','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'VC','','SAP','SAP',0),
(229,'VE','Venezuela','10',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'VE','','SAP','SAP',0),
(230,'VG','British Virg. Islnd','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'VG','','SAP','SAP',0),
(231,'VI','American Virg.Islnd','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'VI','','SAP','SAP',0),
(232,'VN','Vietnam','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'VN','','SAP','SAP',0),
(233,'VU','Vanuatu','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'VU','','SAP','SAP',0),
(234,'WF','Wallis,Futuna','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'WF','','SAP','SAP',0),
(235,'WS','Western Samoa','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'WS','','SAP','SAP',0),
(236,'YE','Yemen','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'YE','','SAP','SAP',0),
(237,'YT','Mayotte','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'YT','','SAP','SAP',0),
(238,'YU','Yugoslavia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'YU','','SAP','SAP',0),
(239,'ZA','South Africa','12',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'ZA','','SAP','SAP',0),
(240,'ZM','Zambia','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'ZM','','SAP','SAP',0),
(241,'ZW','Zimbabwe','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'ZW','','SAP','SAP',0),
(242,'XX','-No Country-','1',1,'2020-09-16 19:31:19',1,'2020-09-16 19:31:19',1,1,'XX','','SAP','SAP',0);

/*Table structure for table `tbl_master_currency` */

DROP TABLE IF EXISTS `tbl_master_currency`;

CREATE TABLE `tbl_master_currency` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `international_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `international_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3;

/*Data for the table `tbl_master_currency` */

/*Table structure for table `tbl_master_designation` */

DROP TABLE IF EXISTS `tbl_master_designation`;

CREATE TABLE `tbl_master_designation` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `designation_name` varchar(256) NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_designation` */

/*Table structure for table `tbl_master_dimension` */

DROP TABLE IF EXISTS `tbl_master_dimension`;

CREATE TABLE `tbl_master_dimension` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `dimension_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `dimension_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_dimension` */

insert  into `tbl_master_dimension`(`id`,`dimension_name`,`dimension_description`,`status`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,'Dimension1','Dimension 1',1,'0000-00-00 00:00:00',1,'2023-09-13 00:45:27',1,0,'','','SAP','WEB',0),
(2,'Dimension2','Dimension 2',1,'0000-00-00 00:00:00',1,'2023-09-13 00:45:27',1,0,'','','SAP','WEB',0),
(3,'Dimension3','Dimension 3',1,'0000-00-00 00:00:00',1,'2023-09-13 00:45:27',1,0,'','','SAP','WEB',0),
(4,'Dimension4','Dimension 4',1,'0000-00-00 00:00:00',1,'2023-09-13 00:45:27',1,0,'','','SAP','WEB',0),
(5,'Dimension5','Dimension 5',1,'0000-00-00 00:00:00',1,'2023-09-13 00:45:27',1,0,'','','SAP','WEB',0);

/*Table structure for table `tbl_master_distribution_rules` */

DROP TABLE IF EXISTS `tbl_master_distribution_rules`;

CREATE TABLE `tbl_master_distribution_rules` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `distribution_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `distribution_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `emp_id` int unsigned NOT NULL DEFAULT '0',
  `sort_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `dimension_id` int unsigned DEFAULT NULL,
  `effective_from` date NOT NULL DEFAULT '0000-00-00',
  `effective_to` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `value` int unsigned NOT NULL DEFAULT '100',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_master_distribution_rules` */

/*Table structure for table `tbl_master_document_numbering` */

DROP TABLE IF EXISTS `tbl_master_document_numbering`;

CREATE TABLE `tbl_master_document_numbering` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_type_id` int unsigned NOT NULL,
  `document_numbering_name` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `first_number` int unsigned NOT NULL,
  `next_number` int unsigned NOT NULL,
  `last_number` int unsigned NOT NULL,
  `prefix` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `suffix` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `digits` int unsigned NOT NULL,
  `remarks` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `is_lock` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not locked, 1 - locked',
  `document_numbering_type` enum('MANUAL','CUSTOM','DRAFT','PRIMARY') DEFAULT 'CUSTOM',
  `branch_id` int NOT NULL,
  `is_system_config` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not sytem config, 1 - system config ',
  `continue_series` tinyint NOT NULL DEFAULT '1' COMMENT '0 - not continue, 1 - continue series',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_document_numbering` */

insert  into `tbl_master_document_numbering`(`id`,`document_type_id`,`document_numbering_name`,`first_number`,`next_number`,`last_number`,`prefix`,`suffix`,`digits`,`remarks`,`is_lock`,`document_numbering_type`,`branch_id`,`is_system_config`,`continue_series`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,2,'Manual',0,1,0,'','',0,'Sales Quote Manual',0,'MANUAL',1,1,1,'2021-03-22 09:36:06',1,'2021-03-26 07:46:28',1,0,'','','WEB','WEB',0),
(2,3,'Manual',0,1,0,'','',0,'Sales Order Manual',0,'MANUAL',1,1,1,'2021-03-22 09:39:30',1,'2021-03-22 09:39:30',NULL,0,'','','WEB','SAP',0),
(3,4,'Manual',0,1,0,'','',0,'Activity Manual',0,'MANUAL',1,1,1,'2021-03-22 09:50:40',1,'2021-03-26 23:13:48',1,0,'','','WEB','WEB',0),
(4,5,'Manual',0,1,0,'','',0,'Purchase Request Manual',0,'MANUAL',1,1,1,'2021-03-24 23:17:30',1,'2021-03-24 23:19:50',1,0,'','','WEB','WEB',0),
(5,6,'Manual',0,1,0,'','',0,'Purchase Order Manual',0,'MANUAL',1,1,1,'2021-03-26 06:33:44',1,'2021-03-26 06:33:44',NULL,0,'','','WEB','SAP',0),
(6,7,'Manual',0,1,0,'','',0,'Grpo Manual',0,'MANUAL',1,1,1,'2021-03-26 07:00:35',1,'2021-03-26 07:00:35',NULL,0,'','','WEB','SAP',0),
(7,8,'Manual',0,1,0,'','',0,'Inventory Transfer Request Manual',0,'MANUAL',1,1,1,'2021-03-26 07:47:28',1,'2021-03-26 07:47:28',NULL,0,'','','WEB','SAP',0),
(8,9,'Manual',0,1,0,'','',0,'Inventory Transfer Manual',0,'MANUAL',1,1,1,'2021-03-26 07:55:05',1,'2021-03-26 07:55:05',NULL,0,'','','WEB','SAP',0),
(9,10,'Manual',0,1,0,'','',0,'Delivery Manual',0,'MANUAL',1,1,1,'2021-03-26 22:37:17',1,'2021-03-26 22:37:17',NULL,0,'','','WEB','SAP',0),
(10,11,'Manual',0,1,0,'','',0,'AR Invoice Manual',0,'MANUAL',1,1,1,'2021-03-26 22:56:27',1,'2021-03-26 22:56:27',NULL,0,'','','WEB','SAP',0),
(11,12,'Manual',0,1,0,'','',0,'AR Down Payment Manual',0,'MANUAL',1,1,1,'2021-03-26 23:00:29',1,'2021-03-26 23:00:29',NULL,0,'','','WEB','SAP',0),
(12,13,'Manual',0,1,0,'','',0,'AR Credit Memo Manual',0,'MANUAL',1,1,1,'2021-03-26 23:07:18',1,'2021-03-26 23:07:18',NULL,0,'','','WEB','SAP',0),
(13,14,'Manual',0,1,0,'','',0,'AR Return Manual',0,'MANUAL',1,1,1,'2021-03-26 23:14:45',1,'2021-03-26 23:14:45',NULL,0,'','','WEB','SAP',0),
(14,16,'Manual',0,1,0,'','',0,'Business partner Manual',0,'MANUAL',1,1,1,'2021-03-26 23:18:38',1,'2021-03-26 23:18:38',NULL,0,'','','WEB','SAP',0),
(15,2,'Draft',0,1,9999999,'DRAFT-','',0,'Sales Quote Draft',0,'DRAFT',1,1,1,'2021-03-26 23:45:35',1,'2021-03-26 23:45:35',NULL,0,'','','WEB','SAP',0),
(16,3,'Draft',0,1,9999999,'DRAFT-','',0,'Sales Order Draft',0,'DRAFT',1,1,1,'2021-03-26 23:52:27',1,'2021-03-26 23:52:27',NULL,0,'','','WEB','SAP',0),
(17,4,'Draft',0,1,9999999,'DRAFT-','',0,'Activity Draft',0,'DRAFT',1,1,1,'2021-03-26 23:56:01',1,'2021-03-26 23:56:01',NULL,0,'','','WEB','SAP',0),
(18,5,'Draft',0,1,9999999,'DRAFT-','',0,'Purchase Request Draft',0,'DRAFT',1,1,1,'2021-03-27 00:10:39',1,'2021-03-27 00:10:39',NULL,0,'','','WEB','SAP',0),
(19,6,'Draft',0,1,9999999,'DRAFT-','',0,'Purchase Order Draft',0,'DRAFT',1,1,1,'2021-03-27 04:05:19',1,'2021-03-27 04:05:19',NULL,0,'','','WEB','SAP',0),
(20,7,'Draft',0,1,9999999,'DRAFT-','',0,'Grpo Draft',0,'DRAFT',1,1,1,'2021-03-27 05:47:01',1,'2021-05-10 15:38:10',1,0,'','','WEB','WEB',0),
(21,8,'Draft',0,1,9999999,'DRAFT-','',0,'Inventory Transfer Request Draft',0,'DRAFT',1,1,1,'2021-04-10 10:13:07',1,'2021-04-10 10:13:07',NULL,0,'','','WEB','SAP',0),
(22,9,'Draft',0,1,9999999,'DRAFT-','',0,'Inventory Transfer Draft',0,'DRAFT',1,1,1,'2021-04-10 10:18:00',1,'2021-04-10 10:32:07',1,0,'','','WEB','WEB',0),
(23,10,'Draft',0,1,9999999,'DRAFT-','',0,'Delivery Draft',0,'DRAFT',1,1,1,'2021-04-28 12:40:14',1,'2021-04-28 12:40:14',NULL,0,'','','WEB','SAP',0),
(24,11,'Draft',0,1,9999999,'DRAFT-','',0,'Ar invoice Draft',0,'DRAFT',1,1,1,'2021-04-29 12:17:28',1,'2021-04-29 12:17:28',NULL,0,'','','WEB','SAP',0),
(25,12,'Draft',0,1,9999999,'DRAFT-','',0,'Ar Down Payment Draft',0,'DRAFT',1,1,1,'2021-05-10 23:51:03',1,'2021-05-10 23:51:03',NULL,0,'','','WEB','SAP',0),
(26,13,'Draft',0,1,9999999,'DRAFT-','',0,'Ar Credit Memo Draft',0,'DRAFT',1,1,1,'2021-05-15 01:06:11',1,'2021-05-15 01:06:11',NULL,0,'','','WEB','SAP',0),
(27,14,'Draft',0,1,9999999,'DRAFT-','',0,'Ar Return Draft',0,'DRAFT',1,1,1,'2021-05-17 22:53:34',1,'2021-06-13 00:24:47',1,0,'','','WEB','WEB',0),
(28,16,'Draft',0,1,9999999,'DRAFT-','',0,'Business Partner Draft',0,'DRAFT',1,1,1,'2021-05-18 07:25:49',1,'2021-05-19 00:08:16',1,0,'','','WEB','WEB',1),
(29,2,'Primary',0,1,9999999,'','',0,'Sales Quote Auto',0,'PRIMARY',1,1,1,'2021-05-26 03:36:26',1,'2021-05-26 03:36:26',NULL,0,'','','WEB','SAP',0),
(30,3,'Primary',0,1,9999999,'','',0,'Sales Order Auto',0,'PRIMARY',1,1,1,'2021-05-26 03:42:44',1,'2021-07-20 03:51:09',1,0,'','','WEB','WEB',0),
(31,4,'Primary',0,1,9999999,'','',0,'Activity Auto',0,'PRIMARY',1,1,1,'2021-05-26 03:45:07',1,'2021-05-26 03:45:07',NULL,0,'','','WEB','SAP',0),
(32,5,'Primary',0,1,9999999,'','',0,'Purchase Request Auto',0,'PRIMARY',1,1,1,'2021-05-26 03:54:58',1,'2021-05-26 03:54:58',NULL,0,'','','WEB','SAP',0),
(33,6,'Primary',0,1,9999999,'','',0,'Purchase Order Auto',0,'PRIMARY',1,1,1,'2021-05-29 21:07:15',1,'2021-05-29 21:07:15',NULL,0,'','','WEB','SAP',0),
(34,7,'Primary',0,1,9999999,'','',0,'Grpo Auto',0,'PRIMARY',1,1,1,'2021-05-29 21:07:36',1,'2021-05-29 21:07:36',NULL,0,'','','WEB','SAP',0),
(35,8,'Primary',0,1,9999999,'','',0,'Inventory Transfer Request Auto',0,'PRIMARY',1,1,1,'2021-07-10 23:56:34',1,'2021-07-10 23:56:34',NULL,0,'','','WEB','SAP',0),
(36,9,'Primary',0,1,9999999,'','',0,'Inventory Transfer Auto',0,'PRIMARY',1,1,1,'2021-07-11 00:07:59',1,'2021-07-16 12:29:23',1,0,'','','WEB','WEB',0),
(37,10,'Primary',0,1,9999999,'','',0,'Delivery Auto',0,'PRIMARY',1,1,1,'2021-07-11 00:11:16',1,'2021-07-11 00:11:16',NULL,0,'','','WEB','SAP',0),
(38,11,'Primary',0,1,9999999,'','',0,'AR Invoice Auto',0,'PRIMARY',1,1,1,'2021-07-11 00:19:15',1,'2021-07-11 00:19:15',NULL,0,'','','WEB','SAP',0),
(39,12,'Primary',0,1,9999999,'','',0,'AR Down Payment Auto',0,'PRIMARY',1,1,1,'2021-07-11 00:46:27',1,'2021-07-11 00:48:35',1,0,'','','WEB','WEB',0),
(40,13,'Primary',0,1,9999999,'','',0,'AR Credit Memo Auto',0,'PRIMARY',1,1,1,'2021-07-11 00:47:35',1,'2021-07-13 01:06:54',1,0,'','','WEB','WEB',0),
(41,14,'Primary',0,1,9999999,'','',0,'AR Return Auto',0,'PRIMARY',1,1,1,'2021-07-14 12:16:31',1,'2021-07-14 12:16:31',NULL,0,'','','WEB','SAP',0),
(42,16,'Primary',0,1,9999999,'','',0,'Business Partner Auto',0,'PRIMARY',1,1,1,'2021-07-14 13:00:10',1,'2021-07-14 13:09:29',1,0,'','','WEB','WEB',0),
(46,18,'Manual',0,1,0,'','',0,'Rental Quote Manual',0,'MANUAL',1,1,1,'2021-07-22 06:37:19',1,'2021-07-22 06:37:19',NULL,0,'','','WEB','SAP',0),
(47,19,'Manual',0,1,0,'','',0,'Rental Order Manual',0,'MANUAL',1,1,1,'2021-07-22 08:25:22',1,'2021-07-22 08:25:22',NULL,0,'','','WEB','SAP',0),
(48,20,'Manaul',0,1,0,'','',0,'Rental Inspection Out',0,'MANUAL',1,1,1,'2021-07-26 02:53:10',1,'2021-07-26 02:53:10',NULL,0,'','','WEB','SAP',0),
(49,21,'Manual',0,1,0,'','',0,'Rental Delivery',0,'MANUAL',1,1,1,'2021-07-27 04:32:06',1,'2021-07-27 04:32:06',NULL,0,'','','WEB','SAP',0),
(50,22,'Manual',0,1,0,'','',0,'Rental Return',0,'MANUAL',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(51,23,'Manual',0,1,0,'','',0,'Rental Inspection In',0,'MANUAL',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',0,0,'','','SAP','SAP',0),
(52,24,'Manaul',0,1,0,'','',0,'Rental Invoice',0,'MANUAL',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',0,0,'','','SAP','SAP',0),
(53,25,'Manual',0,1,0,'','',0,'Rental Worklog',0,'MANUAL',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',0,0,'','','SAP','SAP',0),
(54,18,'Draft',0,1,9999999,'DRAFT-','',0,'Rental Quote Draft',0,'DRAFT',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(55,19,'Draft',0,1,9999999,'DRAFT-','',0,'Rental Order Draft',0,'DRAFT',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(56,20,'Draft',0,1,9999999,'DRAFT-','',0,'Rental Inspection Out Draft',0,'DRAFT',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(57,21,'Draft',0,1,9999999,'DRAFT-','',0,'Rental Delivery Draft',0,'DRAFT',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(58,22,'Draft',0,1,9999999,'DRAFT-','',0,'Rental Retrun Draft',0,'DRAFT',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(59,23,'Draft',0,1,9999999,'DRAFT-','',0,'Rental Inspection In Draft',0,'DRAFT',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(60,24,'Draft',0,1,9999999,'DRAFT-','',0,'Rental Invoice Draft',0,'DRAFT',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(61,25,'Draft',0,1,9999999,'DRAFT-','',0,'Rental Worklog Draft',0,'DRAFT',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(62,18,'Primary',0,1,9999999,'','',0,'Rental Quote Primary',0,'PRIMARY',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(63,19,'Primary',0,1,9999999,'','',0,'Rental Order Primary',0,'PRIMARY',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(64,20,'Primary',0,1,9999999,'','',0,'Rental Inspection Out Primary',0,'PRIMARY',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(65,21,'Primary',0,1,9999999,'','',0,'Rental Delivery Primary',0,'PRIMARY',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(66,22,'Primary',0,1,9999999,'','',0,'Rental Return Primary',0,'PRIMARY',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(67,23,'Primary',0,1,9999999,'','',0,'Rental Inspection In Primary',0,'PRIMARY',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(68,24,'Primary',0,1,9999999,'','',0,'Rental Invoice Primary',0,'PRIMARY',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(69,25,'Primary',0,1,9999999,'','',0,'Rental Worklog Primary',0,'PRIMARY',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(70,15,'Manual',0,1,0,'','',0,'Master Rental Item Manual',0,'MANUAL',1,1,1,'2021-08-10 23:44:29',1,'2021-08-10 23:46:17',1,0,'','','WEB','WEB',0),
(71,15,'Primary',0,1,9999999,'','',0,'Master Rental Item Primary',0,'PRIMARY',1,1,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','','SAP','SAP',0);

/*Table structure for table `tbl_master_hsn` */

DROP TABLE IF EXISTS `tbl_master_hsn`;

CREATE TABLE `tbl_master_hsn` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `hsn_code` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `chapter` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `heading` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sub_heading` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `hsn_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_hsn` */

/*Table structure for table `tbl_master_industry` */

DROP TABLE IF EXISTS `tbl_master_industry`;

CREATE TABLE `tbl_master_industry` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `industry_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `industry_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

/*Data for the table `tbl_master_industry` */

/*Table structure for table `tbl_master_information_source` */

DROP TABLE IF EXISTS `tbl_master_information_source`;

CREATE TABLE `tbl_master_information_source` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `source_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `source_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_information_source` */

/*Table structure for table `tbl_master_inspection_template` */

DROP TABLE IF EXISTS `tbl_master_inspection_template`;

CREATE TABLE `tbl_master_inspection_template` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `template_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `template_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_inspection_template` */

/*Table structure for table `tbl_master_issuing_note` */

DROP TABLE IF EXISTS `tbl_master_issuing_note`;

CREATE TABLE `tbl_master_issuing_note` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `note_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_issuing_note` */

/*Table structure for table `tbl_master_item` */

DROP TABLE IF EXISTS `tbl_master_item`;

CREATE TABLE `tbl_master_item` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `item_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `item_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `item_group_id` int unsigned DEFAULT NULL,
  `uom_id` int unsigned DEFAULT NULL,
  `item_image` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `item_weight` varchar(256) NOT NULL DEFAULT '',
  `stock` float unsigned NOT NULL DEFAULT '0',
  `purchase_open_count` double unsigned NOT NULL DEFAULT '0' COMMENT 'open_quantity count of purchase order',
  `sales_open_count` double unsigned NOT NULL DEFAULT '0' COMMENT 'open_quantity count of sales order',
  `tax_id` int unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `manufacturer_id` int unsigned DEFAULT '0',
  `remarks` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `item_transaction_type` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `foreign_name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `last_sales_price` double unsigned DEFAULT '0',
  `last_purchase_price` double unsigned DEFAULT '0',
  `last_price_list_id` int unsigned DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42409 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_master_item` */

/*Table structure for table `tbl_master_item_group` */

DROP TABLE IF EXISTS `tbl_master_item_group`;

CREATE TABLE `tbl_master_item_group` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `group_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `group_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=302 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_master_item_group` */

/*Table structure for table `tbl_master_item_price_list` */

DROP TABLE IF EXISTS `tbl_master_item_price_list`;

CREATE TABLE `tbl_master_item_price_list` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int unsigned NOT NULL,
  `price_list_id` int unsigned NOT NULL,
  `unit_price` double unsigned NOT NULL DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_item_price_list` */

/*Table structure for table `tbl_master_level_of_interest` */

DROP TABLE IF EXISTS `tbl_master_level_of_interest`;

CREATE TABLE `tbl_master_level_of_interest` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `interest_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_level_of_interest` */

insert  into `tbl_master_level_of_interest`(`id`,`interest_name`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,'High','2019-07-28 01:13:53',1,'2019-07-28 01:13:53',NULL,0,'0','','SAP','SAP',0),
(2,'Medium','2019-07-28 01:13:53',1,'2019-07-28 01:13:53',NULL,0,'0','','SAP','SAP',0),
(3,'Low','2019-07-28 01:13:53',1,'2019-07-28 01:13:53',NULL,0,'0','','SAP','SAP',0);

/*Table structure for table `tbl_master_location` */

DROP TABLE IF EXISTS `tbl_master_location`;

CREATE TABLE `tbl_master_location` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `location_no` int unsigned NOT NULL DEFAULT '0',
  `location_name` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `ship_to_name` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `ship_to_address` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `street_no` varchar(1204) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `block` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `building` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `state_id` int unsigned NOT NULL DEFAULT '0',
  `city` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `zip_code` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_location` */

/*Table structure for table `tbl_master_manufacturer` */

DROP TABLE IF EXISTS `tbl_master_manufacturer`;

CREATE TABLE `tbl_master_manufacturer` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `manufacturer_code` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `manufacturer_name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_manufacturer` */

/*Table structure for table `tbl_master_module` */

DROP TABLE IF EXISTS `tbl_master_module`;

CREATE TABLE `tbl_master_module` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `module_name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `module_order` tinyint NOT NULL DEFAULT '0' COMMENT '0 -No, 1 - Yes',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_module` */

insert  into `tbl_master_module`(`id`,`module_name`,`module_order`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,'CRM',1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(2,'Sales - A/R',2,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(3,'Purchasing - A/P',3,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(4,'Business Partners',4,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(5,'Inventory',5,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(6,'Human Resources',6,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(7,'Sales Man Tracking',7,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(8,'Rental',8,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(9,'General',9,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(10,'Sales Opportunities',10,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(11,'Financials',11,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(12,'Inventory',12,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(13,'Customization',13,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(14,'System-Initialization',14,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(15,'Approval Process',15,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(16,'Settings',16,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0);

/*Table structure for table `tbl_master_module_screen_mapping` */

DROP TABLE IF EXISTS `tbl_master_module_screen_mapping`;

CREATE TABLE `tbl_master_module_screen_mapping` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int unsigned NOT NULL,
  `screen_name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `screen_order` tinyint NOT NULL DEFAULT '0',
  `url_segment` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '' COMMENT 'module name and controller name',
  `document_type_id` int NOT NULL DEFAULT '0',
  `enable_udf` tinyint NOT NULL DEFAULT '0' COMMENT '0 -No, 1 - Yes',
  `enable_document_numbering` tinyint NOT NULL DEFAULT '0' COMMENT '0 -No, 1 - Yes',
  `enable_approval_process` tinyint NOT NULL DEFAULT '0' COMMENT '0 -No, 1 - Yes',
  `enable_notification` tinyint NOT NULL DEFAULT '0' COMMENT '0 -No, 1 - Yes',
  `not_available_columns` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `is_system_config` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not sytem config, 1 - system config ',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_module_screen_mapping` */

insert  into `tbl_master_module_screen_mapping`(`id`,`module_id`,`screen_name`,`screen_order`,`url_segment`,`document_type_id`,`enable_udf`,`enable_document_numbering`,`enable_approval_process`,`enable_notification`,`not_available_columns`,`is_system_config`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,1,'Sales Opportunities',2,'/company/opportunity/',1,1,0,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(2,2,'Sales Quote',1,'/company/sales_quote/',2,1,1,1,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(3,2,'Sales Order',2,'/company/sales_order/',3,1,1,1,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(4,1,'Activity',1,'/company/activity/',4,1,0,0,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(5,3,'Purchase Request',1,'/company/purchase_request/',5,1,1,1,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(6,3,'Purchase Order',2,'/company/purchase_order/',6,1,1,1,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(7,3,'Goods Receipt PO',3,'/company/grpo/',7,1,1,1,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(8,5,'Inventory Transfer Request',1,'/company/Inventory_transfer_request/',8,1,1,1,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(9,5,'Inventory Transfer',2,'/company/inventory_transfer/',9,1,1,1,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(10,2,'Sales Delivery',3,'/company/Sales_delivery/',10,1,1,1,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(11,2,'AR Invoice',6,'/company/sales_ar_invoice/',11,1,1,1,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(12,2,'AR Down Payment Invoice',5,'/company/sales_ar_dp_invoice/',12,1,1,1,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(13,2,'AR Credit Memo',7,'/company/sales_ar_credit_memo/',13,1,1,1,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(14,2,'Sales Return',4,'/company/sales_return/',14,1,1,1,1,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(15,5,'Item Master Data',3,'/company/master_item/',15,0,1,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(16,4,'Business Partner Master Data',1,'/company/business_partner/',16,0,1,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(17,6,'User Master',1,'/company/employee/',17,0,0,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(18,8,'Rental Quote',1,'/company/rental_quote/',18,1,1,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(19,8,'Rental Order',2,'/company/rental_order/',19,1,1,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(20,8,'Inspection Out',3,'/company/rental_inspection_out/',20,1,1,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(21,8,'Rental Delivery',4,'/company/rental_delivery/',21,1,1,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(22,8,'Rental Return',5,'/company/rental_return/',22,1,1,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(23,8,'Inspection In',6,'/company/rental_inspection_in/',23,1,1,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(24,8,'Rental Invoice',8,'/company/rental_invoice/',24,1,1,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(25,8,'Worklog',7,'/company/rental_worklog/',25,1,1,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(26,8,'Equipment Master Data',10,'/company/master_rental_equipment/',26,0,0,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(27,5,'Bin Location Master Data',4,'/company/Master_bin/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(28,5,'Price Lists',5,'/company/master_price_list/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(29,5,'Special Prices for Business Partners',6,'/company/sp_business_partner/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(30,7,'Team',1,'/company/smt_team/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(31,7,'Team Members',2,'/company/smt_team_member/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(32,7,'Map',3,'/common/common_services/',0,0,0,0,0,'enable_add,enable_update,enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(33,7,'Visits',4,'/company/smt_visits/',0,0,0,0,0,'enable_add,enable_update',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(34,7,'Attendance',5,'/company/Employee_attendance/',0,0,0,0,0,'enable_add,enable_update',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(35,8,'Rental Item Master Data',9,'/company/master_rental_item/',0,0,0,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(36,9,'Territories',1,'/company/Master_territory/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(37,9,'Terms and Condition',2,'/company/master_terms_and_condition/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(38,10,'Opportunity Stages',1,'/company/master_stage/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(39,10,'Competitor',2,'/company/master_competitor/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(40,10,'Information Source',3,'/company/master_information_source/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(41,10,'Industry',4,'/company/master_industry/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(42,10,'Reason',5,'/company/master_reason/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(43,10,'Opportunity Type',6,'/company/master_opportunity_type/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(44,11,'Currencies',1,'/company/master_currency/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(45,11,'Tax Attribute',2,'/company/master_tax_attribute/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(46,11,'Tax Code',3,'/company/master_tax/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(47,11,'Distribution Rules',4,'/company/master_distribution_rules/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(48,12,'Alternative Items',1,'/company/Master_alternative_items/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(49,12,'Item Groups ',2,'/company/master_item_group/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(50,12,'Locations',3,'/company/Master_location/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(51,12,'Manufactures',4,'/company/master_manufacturer/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(52,12,'Warehouses',5,'/company/warehouse/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(53,13,'User-Defined Fields - Managment',1,'/company/User_defined_fields/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(54,13,'User-Defined Fields - Mapping',2,'/company/User_defined_fields/',0,0,0,0,0,'enable_download,enable_add',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(55,14,'Document Numbering',1,'/company/master_document_numbering/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(56,15,'Approval Stages',1,'/company/master_approval_stages/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(57,15,'Approval Templates',2,'/company/approval_templates/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(58,15,'Aprpoval Status Report',3,'/company/approval_status_report/',0,0,0,0,0,'enable_download,enable_add,enable_update',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(59,16,'Branch Settings',2,'/company/settings/',0,0,0,0,0,'enable_download,enable_add',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(60,16,'My Profile',3,'/common/common_services/',0,0,0,0,0,'enable_download,enable_add',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(61,16,'Change Password',4,'/common/common_services/',0,0,0,0,0,'enable_download,enable_add',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(62,16,'ACL',1,'/company/Access_control/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(63,17,'Vehicle Master Data',1,'/company/Master_vehicle/',0,0,0,0,0,'enable_download',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(64,17,'Transport',2,'/company/Transport/',27,1,1,0,0,'',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(65,17,'Transport Report',3,'/company/Transport/',0,0,0,0,0,'enable_download,enable_add,enable_update',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(66,17,'Gate Pass',4,'/company/Transport/',0,0,0,0,0,'enable_download,enable_add,enable_update',0,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0);

/*Table structure for table `tbl_master_opportunity_type` */

DROP TABLE IF EXISTS `tbl_master_opportunity_type`;

CREATE TABLE `tbl_master_opportunity_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type_description` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_opportunity_type` */

insert  into `tbl_master_opportunity_type`(`id`,`type_description`,`status`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,'SALES',1,'2020-10-06 06:17:32',1,'2020-10-06 06:17:32',NULL,0,'','','WEB','SAP',0),
(2,'PURCHASE',1,'2021-03-23 09:48:43',1,'2021-03-23 09:48:43',NULL,0,'','','WEB','SAP',0);

/*Table structure for table `tbl_master_payment_methods` */

DROP TABLE IF EXISTS `tbl_master_payment_methods`;

CREATE TABLE `tbl_master_payment_methods` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `payment_method_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `payment_method_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_payment_methods` */

/*Table structure for table `tbl_master_payment_terms` */

DROP TABLE IF EXISTS `tbl_master_payment_terms`;

CREATE TABLE `tbl_master_payment_terms` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `payment_term_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `payment_term_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `payment_duration` double unsigned NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_payment_terms` */

/*Table structure for table `tbl_master_price_list` */

DROP TABLE IF EXISTS `tbl_master_price_list`;

CREATE TABLE `tbl_master_price_list` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `price_list_name` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `default_price_list_id` int unsigned DEFAULT NULL,
  `default_factor` double unsigned DEFAULT NULL,
  `is_system_config` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not sytem config, 1 - system config ',
  `system_type` enum('MANUAL','CUSTOM','LAST_PURCHASE_PRICE') DEFAULT 'CUSTOM',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_price_list` */

/*Table structure for table `tbl_master_priority` */

DROP TABLE IF EXISTS `tbl_master_priority`;

CREATE TABLE `tbl_master_priority` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `priority_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

/*Data for the table `tbl_master_priority` */

/*Table structure for table `tbl_master_reason` */

DROP TABLE IF EXISTS `tbl_master_reason`;

CREATE TABLE `tbl_master_reason` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `reason_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_reason` */

/*Table structure for table `tbl_master_rental_equipment` */

DROP TABLE IF EXISTS `tbl_master_rental_equipment`;

CREATE TABLE `tbl_master_rental_equipment` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `rental_item_id` int unsigned NOT NULL,
  `equipment_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `equipment_name` varchar(4096) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `equipment_image` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `equipment_category_id` int unsigned NOT NULL DEFAULT '0',
  `model` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `meter_reading_id` int unsigned NOT NULL DEFAULT '0',
  `asset_value` int unsigned NOT NULL DEFAULT '0',
  `purchase_date` date NOT NULL DEFAULT '0000-00-00',
  `warranty_date` date NOT NULL DEFAULT '0000-00-00',
  `year_of_manufacturing` date NOT NULL DEFAULT '0000-00-00',
  `year_of_registered` date NOT NULL DEFAULT '0000-00-00',
  `year_of_purchase` date NOT NULL DEFAULT '0000-00-00',
  `chasis_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `manufacturer` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `reg_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `last_service_reading` int unsigned NOT NULL DEFAULT '0',
  `last_service_date` date NOT NULL DEFAULT '0000-00-00',
  `country_id` int unsigned NOT NULL DEFAULT '0',
  `state_id` int unsigned NOT NULL DEFAULT '0',
  `operator_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `maintenance_priority_id` int unsigned NOT NULL DEFAULT '0',
  `insurance_expiry` date NOT NULL DEFAULT '0000-00-00',
  `fitness_expiry` date NOT NULL DEFAULT '0000-00-00',
  `purchase_expiry` date NOT NULL DEFAULT '0000-00-00',
  `roadtax_expiry` date NOT NULL DEFAULT '0000-00-00',
  `sap_asset_no` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `asset_no` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `unit_price` double unsigned NOT NULL,
  `mfr_serial_number` varchar(4096) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `serial_number` varchar(4096) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `ownership_id` int unsigned NOT NULL DEFAULT '0',
  `warehouse_id` int unsigned NOT NULL,
  `document_id` int unsigned NOT NULL,
  `document_type_id` tinyint NOT NULL DEFAULT '0' COMMENT '1-opportunity,2-sales quote,3-sales order etc',
  `status` int unsigned NOT NULL,
  `rental_status` int unsigned NOT NULL,
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `fixed_asset` tinyint NOT NULL DEFAULT '0' COMMENT '0 -> not checked , 1 -> checked',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_rental_equipment` */

/*Table structure for table `tbl_master_rental_equipment_category` */

DROP TABLE IF EXISTS `tbl_master_rental_equipment_category`;

CREATE TABLE `tbl_master_rental_equipment_category` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_rental_equipment_category` */

/*Table structure for table `tbl_master_rental_equipment_status` */

DROP TABLE IF EXISTS `tbl_master_rental_equipment_status`;

CREATE TABLE `tbl_master_rental_equipment_status` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `equipment_status_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_rental_equipment_status` */

/*Table structure for table `tbl_master_rental_item` */

DROP TABLE IF EXISTS `tbl_master_rental_item`;

CREATE TABLE `tbl_master_rental_item` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `rental_item_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `rental_item_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `foreign_name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `stock` float unsigned NOT NULL DEFAULT '0',
  `item_group_id` int unsigned DEFAULT NULL,
  `uom_id` int unsigned DEFAULT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `rental_item_image` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_rental_item` */

/*Table structure for table `tbl_master_routing_url` */

DROP TABLE IF EXISTS `tbl_master_routing_url`;

CREATE TABLE `tbl_master_routing_url` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `master_module_screen_mapping_id` int unsigned NOT NULL,
  `url` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `description` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=261 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_routing_url` */

insert  into `tbl_master_routing_url`(`id`,`master_module_screen_mapping_id`,`url`,`description`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,1,'/company/opportunity/getOpportunityList','Sales Opportunity','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(2,1,'/company/opportunity/saveOpportunity','Sales Opportunity','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(3,1,'/company/opportunity/editOpportunity','Sales Opportunity','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(4,1,'/company/opportunity/updateOpportunity','Sales Opportunity','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(5,4,'/company/activity/getActivityList','Activity','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(6,4,'/company/activity/saveActivity','Activity','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(7,4,'/company/activity/editActivity','Activity','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(8,4,'/company/activity/updateActivity','Activity','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(9,2,'/company/sales_quote/getSalesQuoteList','Sales Quote','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(10,2,'/company/sales_quote/saveSalesQuote','Sales Quote','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(11,2,'/company/sales_quote/editSalesQuote','Sales Quote','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(12,2,'/company/sales_quote/updateSalesQuote','Sales Quote','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(13,2,'/company/sales_quote/downloadExcel','Sales Quote','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(14,3,'/company/sales_order/getSalesOrderList','Sales Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(15,3,'/company/sales_order/saveSalesOrder','Sales Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(16,3,'/company/sales_order/editSalesOrder','Sales Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(17,3,'/company/sales_order/updateSalesOrder','Sales Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(18,3,'/company/sales_order/downloadExcel','Sales Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(19,10,'/company/Sales_delivery/getSalesDeliveryList','Sales Delivery','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(20,10,'/company/Sales_delivery/saveSalesDelivery','Sales Delivery','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(21,10,'/company/Sales_delivery/editSalesDelivery','Sales Delivery','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(22,10,'/company/Sales_delivery/updateSalesDelivery','Sales Delivery','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(23,10,'/company/Sales_delivery/downloadExcel','Sales Delivery','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(24,11,'/company/sales_ar_invoice/getSalesArInvoiceList','Sales Ar Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(25,11,'/company/sales_ar_invoice/saveSalesArInvoice','Sales Ar Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(26,11,'/company/sales_ar_invoice/editSalesArInvoice','Sales Ar Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(27,11,'/company/sales_ar_invoice/updateSalesArInvoice','Sales Ar Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(28,11,'/company/sales_ar_invoice/downloadExcel','Sales Ar Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(29,12,'/company/sales_ar_dp_invoice/getSalesArDpInvoiceList','Sales Ar DP Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(30,12,'/company/sales_ar_dp_invoice/saveSalesArDpInvoice','Sales Ar DP Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(31,12,'/company/sales_ar_dp_invoice/editSalesArDpInvoice','Sales Ar DP Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(32,12,'/company/sales_ar_dp_invoice/updateSalesArDpInvoice','Sales Ar DP Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(33,12,'/company/sales_ar_dp_invoice/downloadExcel','Sales Ar DP Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(34,13,'/company/sales_ar_credit_memo/getSalesArCreditMemoList','Sales Ar Credit memo','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(35,13,'/company/sales_ar_credit_memo/saveSalesArCreditMemo','Sales Ar Credit memo','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(36,13,'/company/sales_ar_credit_memo/editSalesArCreditMemo','Sales Ar Credit memo','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(37,13,'/company/sales_ar_credit_memo/updateSalesArCreditMemo','Sales Ar Credit memo','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(38,13,'/company/sales_ar_credit_memo/downloadExcel','Sales Ar Credit memo','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(39,14,'/company/sales_return/getSalesReturnList','Sales Return','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(40,14,'/company/sales_return/saveSalesReturn','Sales Return','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(41,14,'/company/sales_return/editSalesReturn','Sales Return','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(42,14,'/company/sales_return/updateSalesReturn','Sales Return','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(43,14,'/company/sales_return/downloadExcel','Sales Return','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(44,5,'/company/purchase_request/getPurchaseRequestList','Puchase Request','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(45,5,'/company/purchase_request/savePurchaseRequest','Puchase Request','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(46,5,'/company/purchase_request/editPurchaseRequest','Puchase Request','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(47,5,'/company/purchase_request/updatePurchaseRequest','Puchase Request','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(48,5,'/company/purchase_request/downloadExcel','Puchase Request','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(49,6,'/company/purchase_order/getPurchaseOrderList','Purchase Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(50,6,'/company/purchase_order/savePurchaseOrder','Purchase Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(51,6,'/company/purchase_order/editPurchaseOrder','Purchase Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(52,6,'/company/purchase_order/updatePurchaseOrder','Purchase Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(53,6,'/company/purchase_order/downloadExcel','Purchase Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(54,7,'/company/grpo/updateGrpo','Grpo','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(55,7,'/company/grpo/downloadExcel','Grpo','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(56,7,'/company/grpo/editGrpo','Grpo','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(57,7,'/company/grpo/updateGrpo','Grpo','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(58,7,'/company/grpo/downloadExcel','Grpo','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(59,16,'/company/business_partner/getBusinessPartnerList','Business Partner','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(60,16,'/company/business_partner/saveBusinessPartner','Business Partner','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(61,16,'/company/business_partner/editBusinessPartner','Business Partner','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(62,16,'/company/business_partner/updateBusinessPartner','Business Partner','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(63,8,'/company/Inventory_transfer_request/getInventoryTransferRequestList','Inventory Transfer Request','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(64,8,'/company/Inventory_transfer_request/saveInventoryTransferRequest','Inventory Transfer Request','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(65,8,'/company/Inventory_transfer_request/editInventoryTransferRequest','Inventory Transfer Request','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(66,8,'/company/Inventory_transfer_request/updateInventoryTransferRequest','Inventory Transfer Request','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(67,8,'/company/Inventory_transfer_request/downloadExcel','Inventory Transfer Request','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(68,9,'/company/inventory_transfer/getInventoryTransferList','Inventory Transfer','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(69,9,'/company/inventory_transfer/saveInventoryTransfer','Inventory Transfer','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(70,9,'/company/inventory_transfer/editInventoryTransfer','Inventory Transfer','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(71,9,'/company/inventory_transfer/updateInventoryTransfer','Inventory Transfer','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(72,9,'/company/inventory_transfer/downloadExcel','Inventory Transfer','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(73,15,'/company/master_item/getItemList','Master Item','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(74,15,'/company/master_item/saveItem','Master Item','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(75,15,'/company/master_item/editItem','Master Item','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(76,15,'/company/master_item/updateItem','Master Item','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(77,27,'/company/Master_bin/getBinList','Master Bin','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(78,27,'/company/Master_bin/saveBin','Master Bin','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(79,27,'/company/Master_bin/editBin','Master Bin','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(80,27,'/company/Master_bin/updateBin','Master Bin','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(81,28,'/company/master_price_list/getMasterPriceList','Master Price List','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(82,28,'/company/master_price_list/saveMasterPriceList','Master Price List','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(83,28,'/company/master_price_list/editMasterPriceList','Master Price List','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(84,28,'/company/master_price_list/updateMasterPriceList','Master Price List','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(85,29,'/company/sp_business_partner/getSpBusinessPartnerList','SP Business Partner','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(86,29,'/company/sp_business_partner/saveSpBusinessPartner','SP Business Partner','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(87,29,'/company/sp_business_partner/editSpBusinessPartner','SP Business Partner','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(88,29,'/company/sp_business_partner/updateSpBusinessPartner','SP Business Partner','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(89,17,'/company/employee/getEmployeeList','Employee','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(90,17,'/company/employee/addEmployee','Employee','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(91,17,'/company/employee/editEmployee','Employee','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(92,17,'/company/employee/updateEmployee','Employee','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(93,30,'/company/smt_team/getTeamList','Team','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(94,30,'/company/smt_team/saveTeam','Team','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(95,30,'/company/smt_team/editTeam','Team','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(96,30,'/company/smt_team/updateTeam','Team','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(97,31,'/company/smt_team_member/getTeamMemberList','Team Members','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(98,31,'/company/smt_team_member/saveTeamMember','Team Members','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(99,31,'/company/smt_team_member/editTeamMember','Team Members','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(100,31,'/company/smt_team_member/updateTeamMember','Team Members','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(101,32,'/common/common_services/getAutoSuggestionList/getTeamNameList','Map','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(102,33,'/company/smt_visits/getVisitList','Visits','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(103,34,'/company/Employee_attendance/getEmployeeAttendanceList','Attendance','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(104,18,'/company/rental_quote/getRentalQuoteList','Rental Quote','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(105,18,'/company/rental_quote/saveRentalQuote','Rental Quote','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(106,18,'/company/rental_quote/editRentalQuote','Rental Quote','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(107,18,'/company/rental_quote/updateRentalQuote','Rental Quote','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(108,18,'/company/rental_quote/downloadExcel','Rental Quote','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(109,19,'/company/rental_order/getRentalOrderList','Rental Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(110,19,'/company/rental_order/saveRentalOrder','Rental Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(111,19,'/company/rental_order/editRentalOrder','Rental Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(112,19,'/company/rental_order/updateRentalOrder','Rental Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(113,19,'/company/rental_order/downloadExcel','Rental Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(114,20,'/company/rental_inspection_out/getRentalInspectionOutList','Inspection Out','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(115,20,'/company/rental_inspection_out/saveRentalInspectionOut','Inspection Out','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(116,20,'/company/rental_inspection_out/editRentalInspectionOut','Inspection Out','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(117,20,'/company/rental_inspection_out/updateRentalInspectionOut','Inspection Out','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(118,20,'/company/rental_inspection_out/downloadExcel','Inspection Out','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(119,21,'/company/rental_delivery/getRentalDeliveryList','Rental Delivery','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(120,21,'/company/rental_delivery/saveRentalDelivery','Rental Delivery','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(121,21,'/company/rental_delivery/editRentalDelivery','Rental Delivery','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(122,21,'/company/rental_delivery/updateRentalDelivery','Rental Delivery','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(123,21,'/company/rental_delivery/downloadExcel','Rental Delivery','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(124,22,'/company/rental_return/getRentalReturnList','Rental Return','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(125,22,'/company/rental_return/saveRentalReturn','Rental Return','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(126,22,'/company/rental_return/editRentalReturn','Rental Return','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(127,22,'/company/rental_return/updateRentalReturn','Rental Return','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(128,22,'/company/rental_return/downloadExcel','Rental Return','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(129,23,'/company/rental_inspection_in/getRentalInspectionInList','Inspection In','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(130,23,'/company/rental_inspection_in/saveRentalInspectionIn','Inspection In','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(131,23,'/company/rental_inspection_in/editRentalInspectionIn','Inspection In','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(132,23,'/company/rental_inspection_in/updateRentalInspectionIn','Inspection In','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(133,24,'/company/rental_invoice/getRentalInvoiceList','Rental Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(134,24,'/company/rental_invoice/saveRentalInvoice','Rental Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(135,24,'/company/rental_invoice/editRentalInvoice','Rental Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(136,24,'/company/rental_invoice/updateRentalInvoice','Rental Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(137,24,'/company/rental_invoice/downloadExcel','Rental Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(138,25,'/company/rental_worklog/getRentalWorklogList','Worklog','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(139,25,'/company/rental_worklog/saveRentalWorklog','Worklog','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(140,25,'/company/rental_worklog/editRentalWorklog','Worklog','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(141,25,'/company/rental_worklog/updateRentalWorklog','Worklog','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(142,25,'/company/rental_worklog/downloadExcel','Worklog','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(143,26,'/company/master_rental_equipment/getRentlEquipmentList','Equipment Master Data','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(144,26,'/company/master_rental_equipment/saveRentalEquipment','Equipment Master Data','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(145,26,'/company/master_rental_equipment/editRentalEquipment','Equipment Master Data','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(146,26,'/company/master_rental_equipment/updateRentalEquipment','Equipment Master Data','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(147,35,'/company/master_rental_item/getRentlItemList','Rental Item Master Data','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(148,35,'/company/master_rental_item/saveRentalItem','Rental Item Master Data','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(149,35,'/company/master_rental_item/editRentalItem','Rental Item Master Data','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(150,35,'/company/master_rental_item/updateRentalItem','Rental Item Master Data','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(151,36,'/company/Master_territory/getTerritoryList','Territories','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(152,36,'/company/Master_territory/saveTerritory','Territories','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(153,36,'/company/Master_territory/editTerritory','Territories','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(154,36,'/company/Master_territory/updateTerritory','Territories','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(155,37,'/company/master_terms_and_condition/getTermsandconditionList','Terms and Condition','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(156,37,'/company/master_terms_and_condition/saveTermsandcondition','Terms and Condition','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(157,37,'/company/master_terms_and_condition/editTermsandcondition','Terms and Condition','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(158,37,'/company/master_terms_and_condition/updateTermsandcondition','Terms and Condition','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(159,38,'/company/master_stage/getStageList','Opportunity Stages','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(160,38,'/company/master_stage/saveStage','Opportunity Stages','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(161,38,'/company/master_stage/editStage','Opportunity Stages','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(162,38,'/company/master_stage/updateStage','Opportunity Stages','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(163,39,'/company/master_competitor/getCompetitorList','Competitor','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(164,39,'/company/master_competitor/saveCompetitor','Competitor','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(165,39,'/company/master_competitor/editCompetitor','Competitor','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(166,39,'/company/master_competitor/updateCompetitor','Competitor','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(167,40,'/company/master_information_source/getInformationSourcelist','Information Source','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(168,40,'/company/master_information_source/saveInformationSource','Information Source','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(169,40,'/company/master_information_source/editInformationSource','Information Source','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(170,40,'/company/master_information_source/updateInformationSource','Information Source','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(171,41,'/company/master_industry/getIndustryList','Industry','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(172,41,'/company/master_industry/saveIndustry','Industry','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(173,41,'/company/master_industry/editIndustry','Industry','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(174,41,'/company/master_industry/updateIndustry','Industry','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(175,42,'/company/master_reason/getReasonList','Reason','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(176,42,'/company/master_reason/saveReason','Reason','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(177,42,'/company/master_reason/editReason','Reason','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(178,42,'/company/master_reason/updateReason','Reason','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(179,43,'/company/master_opportunity_type/getOpportunityTypeList','Opportunity Type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(180,43,'/company/master_opportunity_type/saveOpportunityType','Opportunity Type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(181,43,'/company/master_opportunity_type/editOpportunityType','Opportunity Type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(182,43,'/company/master_opportunity_type/updateOpportunityType','Opportunity Type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(183,44,'/company/master_currency/getCurrencyList','Currencies','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(184,44,'/company/master_currency/saveCurrency','Currencies','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(185,44,'/company/master_currency/editCurrency','Currencies','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(186,44,'/company/master_currency/updateCurrency','Currencies','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(187,45,'/company/master_tax_attribute/getTaxAttributeList','Tax Attribute','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(188,45,'/company/master_tax_attribute/saveTaxAttribute','Tax Attribute','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(189,45,'/company/master_tax_attribute/editTaxAttribute','Tax Attribute','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(190,45,'/company/master_tax_attribute/updateTaxAttribute','Tax Attribute','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(191,46,'/company/master_tax/getTaxList','Tax Code','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(192,46,'/company/master_tax/saveTax','Tax Code','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(193,46,'/company/master_tax/editTax','Tax Code','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(194,46,'/company/master_tax/updateTax','Tax Code','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(195,47,'/company/master_distribution_rules/getDistributionRulesList','Distribution Rules','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(196,47,'/company/master_distribution_rules/saveDistributionRules','Distribution Rules','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(197,47,'/company/master_distribution_rules/editDistributionRules','Distribution Rules','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(198,47,'/company/master_distribution_rules/updateDistributionRules','Distribution Rules','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(199,48,'/company/Master_alternative_items/getAlternativeItemsList','Alternative Items','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(200,48,'/company/Master_alternative_items/saveAlternativeItems','Alternative Items','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(201,48,'/company/Master_alternative_items/editAlternativeItems','Alternative Items','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(202,48,'/company/Master_alternative_items/updateAlternativeItems','Alternative Items','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(203,49,'/company/master_item_group/getItemGroupList','Item Groups','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(204,49,'/company/master_item_group/saveItemGroup','Item Groups','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(205,49,'/company/master_item_group/editItemGroup','Item Groups','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(206,49,'/company/master_item_group/updateItemGroup','Item Groups','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(207,50,'/company/Master_location/getLocationList','Locations','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(208,50,'/company/Master_location/saveLocation','Locations','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(209,50,'/company/Master_location/editLocation','Locations','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(210,50,'/company/Master_location/updateLocation','Locations','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(211,51,'/company/master_manufacturer/getManufacturerList','Manufactures','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(212,51,'/company/master_manufacturer/saveManufacturer','Manufactures','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(213,51,'/company/master_manufacturer/editManufacturer','Manufactures','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(214,51,'/company/master_manufacturer/updateManufacturer','Manufactures','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(215,52,'/company/warehouse/getWarehouseList','Warehouses','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(216,52,'/company/warehouse/saveWarehouse','Warehouses','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(217,52,'/company/warehouse/editWarehouse','Warehouses','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(218,52,'/company/warehouse/updateWarehouse','Warehouses','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(219,53,'/company/User_defined_fields/getFormControlsList','User-Defined Fields - Managment','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(220,53,'/company/User_defined_fields/getUdfFieldType','User-Defined Fields - Managment','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(221,53,'/company/User_defined_fields/saveFormControls','User-Defined Fields - Managment','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(222,53,'/company/User_defined_fields/editFormControls','User-Defined Fields - Managment','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(223,53,'/company/User_defined_fields/updateFormControls','User-Defined Fields - Managment','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(224,54,'/company/User_defined_fields/getUdfScreens','User-Defined Fields - Mapping','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(225,54,'/company/User_defined_fields/getFormControlsDetailsByMappingScreen','User-Defined Fields - Mapping','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(226,54,'/company/User_defined_fields/updateScreenMapping','User-Defined Fields - Mapping','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(227,55,'/company/master_document_numbering/getDocumentNumberingList','Document Numbering','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(228,55,'/company/master_document_numbering/saveDocumentNumbering','Document Numbering','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(229,55,'/company/master_document_numbering/editDocumentNumbering','Document Numbering','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(230,55,'/company/master_document_numbering/updateDocumentNumbering','Document Numbering','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(231,56,'/company/master_approval_stages/getApprovalStageList','Approval Stages','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(232,56,'/company/master_approval_stages/saveApprovalStage','Approval Stages','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(233,56,'/company/master_approval_stages/editApprovalStage','Approval Stages','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(234,56,'/company/master_approval_stages/updateApprovalStage','Approval Stages','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(235,57,'/company/approval_templates/getApprovalTemplateList','Approval Templates','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(236,57,'/company/approval_templates/saveApprovalTemplate','Approval Templates','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(237,57,'/company/approval_templates/editApprovalTemplate','Approval Templates','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(238,57,'/company/approval_templates/updateApprovalTemplate','Approval Templates','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(239,58,'/company/approval_status_report/getApprovalStatusReportList','Aprpoval Status Report','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(240,59,'/company/settings/getSettingsDetails','Branch Settings','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(241,59,'/company/settings/updateSettings','Branch Settings','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(242,60,'/common/common_services/getMyProfileInformation','My Profile','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(243,60,'/common/common_services/updateMyProfile','My Profile','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(244,60,'/common/common_services/uploadProfilePhoto','My Profile','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(245,61,'/common/common_services/changePassword','Change Password','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(246,62,'/company/Access_control/getAccessControlList','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(247,62,'/company/Access_control/saveAccessControl','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(248,62,'/company/Access_control/editAccessControlScreenList','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(249,62,'/company/Access_control/updateAccessControl','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(250,1,'/company/opportunity/downloadExcel','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(251,4,'/company/activity/downloadExcel','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(252,16,'/company/business_partner/downloadExcel','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(253,15,'/company/master_item/downloadExcel','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(254,17,'/company/employee/downloadExcel','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(255,33,'/company/smt_visits/downloadExcel','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(256,34,'/company/Employee_attendance/downloadExcel','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(257,23,'/company/rental_inspection_in/downloadExcel','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(258,26,'/company/master_rental_equipment/downloadExcel','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(259,35,'/company/master_rental_item/downloadExcel','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0),
(260,61,'/common/common_services/downloadSecFile','ACL','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','SAP','SAP',0);

/*Table structure for table `tbl_master_screen_routing_url_mapping` */

DROP TABLE IF EXISTS `tbl_master_screen_routing_url_mapping`;

CREATE TABLE `tbl_master_screen_routing_url_mapping` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `master_module_screen_mapping_id` int unsigned NOT NULL,
  `enable_view_url_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `enable_add_url_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `enable_update_url_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `enable_download_url_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_screen_routing_url_mapping` */

insert  into `tbl_master_screen_routing_url_mapping`(`id`,`master_module_screen_mapping_id`,`enable_view_url_id`,`enable_add_url_id`,`enable_update_url_id`,`enable_download_url_id`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,1,'1','2','3,4','250','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(2,4,'5','6','7,8','251','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(3,2,'9','10','11,12','13','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(4,3,'14','15','16,17','18','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(5,10,'19','20','21,22','23','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(6,11,'24','25','26,27','28','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(7,12,'29','30','31,32','33','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(8,13,'34','35','36,37','38','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(9,14,'39','40','41,42','43','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(10,5,'44','45','46,47','48','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(11,6,'49','50','51,52','53','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(12,7,'54','55','56,57','58','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(13,16,'59','60','61,62','252','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(14,8,'63','64','65,66','67','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(15,9,'68','69','70,71','72','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(16,15,'73','74','75,76','253','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(17,27,'77','78','79,80','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(18,28,'81','82','83,84','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(19,29,'85','86','87,88','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(20,17,'89','90','91,92','254','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(21,30,'93','94','95,96','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(22,31,'97','98','99,10','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(23,32,'101','0','0','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(24,33,'102','0','0','255','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(25,34,'103','0','0','256','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(26,18,'104','105','106,107','108','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(27,19,'109','110','111,112','113','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(28,20,'114','115','116,117','118','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(29,21,'119','120','121,122','123','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(30,22,'124','125','126,127','128','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(31,23,'129','130','131,132','257','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(32,24,'133','134','135,136','137','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(33,25,'138','139','140,141','142','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(34,26,'143','144','145,146','258','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(35,35,'147','145','149,150','259','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(36,36,'151','152','153,154','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(37,37,'155','156','157,158','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(38,38,'159','160','161,162','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(39,39,'163','164','165,166','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(40,40,'167','168','169,170','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(41,41,'171','172','173,174','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(42,42,'175','176','177,178','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(43,43,'179','180','181,182','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(44,44,'183','184','185,186','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(45,45,'189','188','189,190','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(46,46,'191','192','193,194','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(47,47,'195','196','197,198','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(48,48,'199','200','201,202','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(49,49,'203','204','205,206','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(50,50,'207','203','209,210','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(51,51,'211','212','213,214','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(52,52,'215','216','217,218','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(53,53,'219,220','221','222,223','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(54,54,'224,225','0','226','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(55,55,'229','225','229,230','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(56,56,'231','232','233,234','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(57,57,'233','236','237,238','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(58,58,'239','0','0','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(59,59,'240','0','241','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(60,60,'242','0','243,244','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(61,61,'0','0','245','260','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0),
(62,62,'246','247','248,249','0','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','SAP','SAP',0);

/*Table structure for table `tbl_master_stages` */

DROP TABLE IF EXISTS `tbl_master_stages`;

CREATE TABLE `tbl_master_stages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `stage_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `stage_number` int unsigned NOT NULL,
  `close_percentage` double unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_stages` */

insert  into `tbl_master_stages`(`id`,`stage_name`,`stage_number`,`close_percentage`,`status`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,'ENQUIRIES',1,25,1,'2020-10-06 06:20:42',1,'2023-09-12 10:48:42',1,0,'','','WEB','WEB',0),
(2,'PROPOSALS',2,50,1,'2020-10-06 06:20:56',1,'2021-03-23 09:45:27',1,0,'','','WEB','WEB',0),
(3,'NEGOTIATIONS',3,75,1,'2020-10-06 06:21:14',1,'2020-10-06 06:21:14',NULL,0,'','','WEB','SAP',0),
(4,'ORDERS',4,100,1,'2020-10-06 06:21:25',1,'2021-03-23 09:44:50',1,0,'','','WEB','WEB',0);

/*Table structure for table `tbl_master_state` */

DROP TABLE IF EXISTS `tbl_master_state`;

CREATE TABLE `tbl_master_state` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int unsigned NOT NULL,
  `state_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `state_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_master_state` */

insert  into `tbl_master_state`(`id`,`country_id`,`state_name`,`state_code`,`status`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,102,'Andhra Pradesh','AP',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'AP','','SAP','SAP',0),
(2,102,'Delhi','DL',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'DL','','SAP','SAP',0),
(3,102,'Haryana','HR',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'HR','','SAP','SAP',0),
(4,102,'Karnataka','KT',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'KT','','SAP','SAP',0),
(5,102,'Maharashtra','MH',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'MH','','SAP','SAP',0),
(6,102,'Madhya Pradesh','MP',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'MP','','SAP','SAP',0),
(7,102,'Uttar Pradesh','UP',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'UP','','SAP','SAP',0),
(8,224,'Alaska','AK',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'AK','','SAP','SAP',0),
(9,224,'Alabama','AL',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'AL','','SAP','SAP',0),
(10,224,'Arkansas','AR',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'AR','','SAP','SAP',0),
(11,224,'American Samoa','AS',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'AS','','SAP','SAP',0),
(12,224,'Arizona','AZ',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'AZ','','SAP','SAP',0),
(13,224,'California','CA',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'CA','','SAP','SAP',0),
(14,224,'Colorado','CO',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'CO','','SAP','SAP',0),
(15,224,'Connecticut','CT',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'CT','','SAP','SAP',0),
(16,224,'District of Columbia','DC',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'DC','','SAP','SAP',0),
(17,224,'Delaware','DE',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'DE','','SAP','SAP',0),
(18,224,'Florida','FL',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'FL','','SAP','SAP',0),
(19,224,'Georgia','GA',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'GA','','SAP','SAP',0),
(20,224,'Guam','GU',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'GU','','SAP','SAP',0),
(21,224,'Hawaii','HI',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'HI','','SAP','SAP',0),
(22,224,'Iowa','IA',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'IA','','SAP','SAP',0),
(23,224,'Idaho','ID',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'ID','','SAP','SAP',0),
(24,224,'Illinois','IL',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'IL','','SAP','SAP',0),
(25,224,'Indiana','IN',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'IN','','SAP','SAP',0),
(26,224,'Kansas','KS',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'KS','','SAP','SAP',0),
(27,224,'Kentucky','KY',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'KY','','SAP','SAP',0),
(28,224,'Louisiana','LA',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'LA','','SAP','SAP',0),
(29,224,'Massachusetts','MA',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'MA','','SAP','SAP',0),
(30,224,'Maryland','MD',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'MD','','SAP','SAP',0),
(31,224,'Maine','ME',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'ME','','SAP','SAP',0),
(32,224,'Michigan','MI',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'MI','','SAP','SAP',0),
(33,224,'Minnesota','MN',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'MN','','SAP','SAP',0),
(34,224,'Missouri','MO',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'MO','','SAP','SAP',0),
(35,224,'Northern Mariana Isl','MP',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'MP','','SAP','SAP',0),
(36,224,'Mississippi','MS',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'MS','','SAP','SAP',0),
(37,224,'Montana','MT',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'MT','','SAP','SAP',0),
(38,224,'North Carolina','NC',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'NC','','SAP','SAP',0),
(39,224,'North Dakota','ND',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'ND','','SAP','SAP',0),
(40,224,'Nebraska','NE',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'NE','','SAP','SAP',0),
(41,224,'New Hampshire','NH',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'NH','','SAP','SAP',0),
(42,224,'New Jersey','NJ',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'NJ','','SAP','SAP',0),
(43,224,'New Mexico','NM',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'NM','','SAP','SAP',0),
(44,224,'Nevada','NV',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'NV','','SAP','SAP',0),
(45,224,'New York','NY',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'NY','','SAP','SAP',0),
(46,224,'Ohio','OH',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'OH','','SAP','SAP',0),
(47,224,'Oklahoma','OK',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'OK','','SAP','SAP',0),
(48,224,'Oregon','OR',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'OR','','SAP','SAP',0),
(49,224,'Pennsylvania','PA',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'PA','','SAP','SAP',0),
(50,224,'Puerto Rico','PR',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'PR','','SAP','SAP',0),
(51,224,'Rhode Island','RI',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'RI','','SAP','SAP',0),
(52,224,'South Carolina','SC',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'SC','','SAP','SAP',0),
(53,224,'South Dakota','SD',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'SD','','SAP','SAP',0),
(54,224,'Tennessee','TN',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'TN','','SAP','SAP',0),
(55,224,'Texas','TX',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'TX','','SAP','SAP',0),
(56,224,'Utah','UT',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'UT','','SAP','SAP',0),
(57,224,'Virginia','VA',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'VA','','SAP','SAP',0),
(58,224,'Virgin Islands','VI',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'VI','','SAP','SAP',0),
(59,224,'Vermont','VT',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'VT','','SAP','SAP',0),
(60,224,'Washington','WA',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'WA','','SAP','SAP',0),
(61,224,'Wisconsin','WI',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'WI','','SAP','SAP',0),
(62,224,'West Virginia','WV',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'WV','','SAP','SAP',0),
(63,224,'Wyoming','WY',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'WY','','SAP','SAP',0),
(64,2,'Dubai','DU',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'DU','','SAP','SAP',0),
(65,2,'Abu Dhabi','AD',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'AD','','SAP','SAP',0),
(66,2,'Sharjah','SHA',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'SHA','','SAP','SAP',0),
(67,2,'Ajman','AJ',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'AJ','','SAP','SAP',0),
(68,2,'Ras al-Khaimah','RAK',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'RAK','','SAP','SAP',0),
(69,2,'Fujairah','FUJ',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'FUJ','','SAP','SAP',0),
(70,2,'Umm al-Quwain','UAQ',1,'2020-09-16 21:10:33',1,'2020-09-16 21:10:33',1,1,'UAQ','','SAP','SAP',0);

/*Table structure for table `tbl_master_static_data` */

DROP TABLE IF EXISTS `tbl_master_static_data`;

CREATE TABLE `tbl_master_static_data` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `master_id` int unsigned NOT NULL,
  `name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `type` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `description` varchar(2048) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_static_data` */

insert  into `tbl_master_static_data`(`id`,`master_id`,`name`,`type`,`description`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(0,1,'Opportunity','DOCUMENT_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(1,1,'Low','THREAD_LEVEL','Thread level data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(2,2,'Medium','THREAD_LEVEL','Thread level data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(3,3,'High','THREAD_LEVEL','Thread level data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(4,1,'Open','OPPORTUNITY_STATUS','Opportunity status data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(5,2,'Won','OPPORTUNITY_STATUS','Opportunity status data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(6,3,'Lost','OPPORTUNITY_STATUS','Opportunity status data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(7,1,'Active','COMMON_STATUS','Common status data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(8,2,'In-Active','COMMON_STATUS','Common status data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(9,1,'CUSTOMER','BUSINESS_PARTNER_TYPE','Business Partner Type','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(10,2,'VENDOR','BUSINESS_PARTNER_TYPE','Business Partner Type','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(11,3,'LEAD','BUSINESS_PARTNER_TYPE','Business Partner Type','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(15,1,'Open','ACTIVITY_STATUS','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(16,2,'In-Active','ACTIVITY_STATUS','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(17,3,'Closed','ACTIVITY_STATUS','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(19,2,'Sales Quote','DOCUMENT_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(20,3,'Sales Order','DOCUMENT_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(21,1,'Daily','ACTIVITY_RECURRENCE_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(22,2,'Weekly','ACTIVITY_RECURRENCE_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(23,3,'Monthly','ACTIVITY_RECURRENCE_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(24,4,'Yearly','ACTIVITY_RECURRENCE_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(25,1,'Low','ACTIVITY_PRIORITY_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(26,2,'Normal','ACTIVITY_PRIORITY_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(27,3,'High','ACTIVITY_PRIORITY_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(28,1,'Minutes','ACTIVITY_REMINDER_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(29,2,'Hours','ACTIVITY_REMINDER_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(30,4,'Activity','DOCUMENT_TYPE','Business Partner Status','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(37,1,'General','BP_CONTACT_TYPE','Business partner contact type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(38,2,'Others','BP_CONTACT_TYPE','Business partner contact type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(39,1,'Sales Employee / Buyer','EMPLOYEE_TYPE','Employee Type Details','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(40,2,'User','EMPLOYEE_TYPE','Employee Type Details','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'0','','SAP','SAP',0),
(41,1,'Ship To','BUSINESS_PARTNER_ADDRESS_TYPE','Business Partner Address Type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(42,2,'Bill To','BUSINESS_PARTNER_ADDRESS_TYPE','Business Partner Address Type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(43,3,'Pay To','BUSINESS_PARTNER_ADDRESS_TYPE','Business Partner Address Type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(44,1,'Open','PURCHASE_TRANS_STATUS','Thread level data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(45,2,'Closed','PURCHASE_TRANS_STATUS','Thread level data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(46,3,'Cancelled','PURCHASE_TRANS_STATUS','Thread level data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(47,1,'Open','SALES_TRANS_STATUS','Thread level data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(48,2,'Closed','SALES_TRANS_STATUS','Thread level data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(49,3,'Cancelled','SALES_TRANS_STATUS','Thread level data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(50,1,'Open','INVENTORY_TRANS_STATUS','Thread level data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(51,2,'Closed','INVENTORY_TRANS_STATUS','Thread level data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(52,3,'Cancelled','INVENTORY_TRANS_STATUS','Thread level data','2019-07-28 01:13:54',1,'2019-07-28 01:13:54',1,0,'0','','SAP','SAP',0),
(53,5,'Purchase Request','DOCUMENT_TYPE','Transaction Screen','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(54,6,'Purchase Order','DOCUMENT_TYPE','Transaction Screen','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'0','','SAP','SAP',0),
(55,7,'Grpo','DOCUMENT_TYPE','Transaction Screen','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'0','','SAP','SAP',0),
(56,1,'With Payment of Duty','DUTY_STATUS','Inventory Duty status','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(57,2,'Without Payment of Duty','DUTY_STATUS','Inventory Duty Status','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(58,8,'Inventory Transfer Request','DOCUMENT_TYPE','Inventory transfer request','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(59,9,'Inventory Transfer','DOCUMENT_TYPE','Inventory Transfer Request','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(60,10,'Delivery','DOCUMENT_TYPE','Delivery','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'0','','SAP','SAP',0),
(61,11,'AR Invoice','DOCUMENT_TYPE','AR Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'0','','SAP','SAP',0),
(62,12,'AR Down Payment Invoice','DOCUMENT_TYPE','AR Down Payment Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'0','','SAP','SAP',0),
(63,13,'AR Credit Memo ','DOCUMENT_TYPE','AR Credit Memo ','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'0','','SAP','SAP',0),
(64,14,'Return','DOCUMENT_TYPE','Return','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'0','','SAP','SAP',0),
(65,1,'Sales Item','ITEM_TRANSACTION_TYPE','Item Master Transaction Details','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'0','','SAP','SAP',0),
(66,2,'Purchase Item','ITEM_TRANSACTION_TYPE','Item Master Transaction Details','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'0','','SAP','SAP',0),
(67,3,'Inventory Item','ITEM_TRANSACTION_TYPE','Item Master Transaction Details','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'0','','SAP','SAP',0),
(68,15,'Master Item','DOCUMENT_TYPE','Master Item','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','','SAP','SAP',0),
(69,16,'Business Partner','DOCUMENT_TYPE','Business Partner','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','','SAP','SAP',0),
(70,17,'Employee Profile','DOCUMENT_TYPE','Employee profile ','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(71,18,'Rental Quote','DOCUMENT_TYPE','Rental Quote','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(72,19,'Rental Order','DOCUMENT_TYPE','Rental Order','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(73,20,'Inspection Out','DOCUMENT_TYPE','Inspection Out','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(74,21,'Rental Delivery','DOCUMENT_TYPE','Rental Delivery','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(75,22,'Rental Return','DOCUMENT_TYPE','Rental Return','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(76,23,'Inspection In','DOCUMENT_TYPE','Inspection In','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(77,24,'Rental Invoice','DOCUMENT_TYPE','Rental Invoice','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(78,1,'Open','RENTAL_TRANS_STATUS','Rental Transcation status','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(79,2,'Closed','RENTAL_TRANS_STATUS','Rental Transcation status','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(80,3,'Cancelled','RENTAL_TRANS_STATUS','Rental Transcation status','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(81,1,'Working','WORKLOG_ITEM_TYPE','Work log item type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(82,2,'Breakdown','WORKLOG_ITEM_TYPE','Work log item type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(83,3,'Overtime','WORKLOG_ITEM_TYPE','Work log item type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(84,25,'Worklog','DOCUMENT_TYPE','worklog document type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(85,26,'Rental Equipment','DOCUMENT_TYPE','Rental equipment document type','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(86,1,'Owned','EQUIPMENT_OWNERSHIP','Rental equipment status','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(87,2,'X-Rental','EQUIPMENT_OWNERSHIP','Rental equipment status','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(88,1,'Available','RENTAL_STATUS','Rental status','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(89,2,'Reserved','RENTAL_STATUS','Rental status','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(90,3,'Delivered','RENTAL_STATUS','Rental status','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(91,4,'Returned','RENTAL_STATUS','Rental status','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(92,5,'UnAvailable','RENTAL_STATUS','Rental status','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(93,1,'KM','METER_READING','Meter Reading','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(94,2,'Hours','METER_READING','Meter Reading','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(95,1,'Low','RENTAL_MAINTENANCE_PRIORITY','Rental Maintenance priority','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(96,2,'Normal','RENTAL_MAINTENANCE_PRIORITY','Rental Maintenance priority','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(97,3,'Medium','RENTAL_MAINTENANCE_PRIORITY','Rental Maintenance priority','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(98,4,'High','RENTAL_MAINTENANCE_PRIORITY','Rental Maintenance priority','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(99,5,'Emergency','RENTAL_MAINTENANCE_PRIORITY','Rental Maintenance priority','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(100,1,'Pending','APPROVAL_STATUS','Approval Status Report','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(101,2,'Approved','APPROVAL_STATUS','Approval Status Report','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(102,3,'Rejected','APPROVAL_STATUS','Approval Status Report','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0),
(103,3,'Customer','EMPLOYEE_TYPE','Employee Type Details','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','','SAP','SAP',0),
(104,4,'Supplier','EMPLOYEE_TYPE','Employee Type Details','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','','SAP','SAP',0),
(105,5,'Dealer','EMPLOYEE_TYPE','Employee Type Details','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'','','SAP','SAP',0);

/*Table structure for table `tbl_master_tax` */

DROP TABLE IF EXISTS `tbl_master_tax`;

CREATE TABLE `tbl_master_tax` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `tax_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `attribute_id` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_master_tax` */

/*Table structure for table `tbl_master_tax_attribute` */

DROP TABLE IF EXISTS `tbl_master_tax_attribute`;

CREATE TABLE `tbl_master_tax_attribute` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `attribute_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `attribute_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `attribute_percentage` double unsigned NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_master_tax_attribute` */

/*Table structure for table `tbl_master_terms_and_condition` */

DROP TABLE IF EXISTS `tbl_master_terms_and_condition`;

CREATE TABLE `tbl_master_terms_and_condition` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `heading` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `body_content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_master_terms_and_condition` */

/*Table structure for table `tbl_master_territory` */

DROP TABLE IF EXISTS `tbl_master_territory`;

CREATE TABLE `tbl_master_territory` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `territory_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `mapping_id` int NOT NULL DEFAULT '-2',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_master_territory` */

/*Table structure for table `tbl_master_uom` */

DROP TABLE IF EXISTS `tbl_master_uom`;

CREATE TABLE `tbl_master_uom` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uom_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL COMMENT 'UNIT OF MEASURE',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;

/*Data for the table `tbl_master_uom` */

/*Table structure for table `tbl_notifications` */

DROP TABLE IF EXISTS `tbl_notifications`;

CREATE TABLE `tbl_notifications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `receiver_id` int unsigned NOT NULL,
  `notification_type` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '1' COMMENT '1- web application, 2-sms, 3 - Email',
  `content` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sms_content` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `module_name` enum('','APPROVALPROCESS') DEFAULT '',
  `document_id` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '' COMMENT 'Primary key id of the documents',
  `document_type_id` tinyint NOT NULL DEFAULT '1' COMMENT '1-opportunity,2-sales quote,3-sales order',
  `status` tinyint DEFAULT '1' COMMENT '1-Unread, 2-Read',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_notifications` */

/*Table structure for table `tbl_opportunity` */

DROP TABLE IF EXISTS `tbl_opportunity`;

CREATE TABLE `tbl_opportunity` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `opportunity_no` int unsigned NOT NULL DEFAULT '0',
  `opportunity_name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `opportunity_type_id` int unsigned NOT NULL,
  `business_partner_id` int unsigned NOT NULL,
  `bp_contacts_id` int unsigned NOT NULL,
  `emp_id` int unsigned NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `closing_date` date NOT NULL DEFAULT '0000-00-00',
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `potential_amount` double unsigned NOT NULL DEFAULT '0',
  `level_of_interest_id` int unsigned NOT NULL,
  `industry_id` int unsigned NOT NULL,
  `information_source_id` int unsigned NOT NULL,
  `competitor_id` int unsigned NOT NULL,
  `opportunity_status` tinyint NOT NULL DEFAULT '0' COMMENT '1-OPEN,2-WON,3-LOST',
  `reason_id` int unsigned NOT NULL,
  `remarks` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `udf_fields` text NOT NULL,
  `branch_id` int NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_opportunity` */

/*Table structure for table `tbl_opportunity_stages` */

DROP TABLE IF EXISTS `tbl_opportunity_stages`;

CREATE TABLE `tbl_opportunity_stages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `opportunity_id` int unsigned NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `closing_date` date NOT NULL DEFAULT '0000-00-00',
  `emp_id` int unsigned NOT NULL,
  `stage_id` int unsigned NOT NULL,
  `stage_percentage` int unsigned NOT NULL,
  `document_id` int unsigned NOT NULL,
  `document_type_id` tinyint NOT NULL DEFAULT '1',
  `activity_id` int unsigned NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_opportunity_stages` */

/*Table structure for table `tbl_purchase_order` */

DROP TABLE IF EXISTS `tbl_purchase_order`;

CREATE TABLE `tbl_purchase_order` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `vendor_bp_id` int unsigned NOT NULL,
  `vendor_bp_contacts_id` int unsigned NOT NULL,
  `vendor_ship_to_bp_address_id` int unsigned DEFAULT NULL,
  `vendor_ship_to_address` text,
  `vendor_pay_to_bp_address_id` int unsigned DEFAULT NULL,
  `vendor_pay_to_address` text,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint NOT NULL DEFAULT '1',
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `total_weight` double unsigned NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `approval_status` tinyint NOT NULL DEFAULT '4' COMMENT '1 - PENDING, 2 - APPROVED, 3 - REJECTED 4 - No Approval process',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `buyer_emp_id` int unsigned NOT NULL,
  `cancellation_date` date NOT NULL DEFAULT '0000-00-00',
  `required_date` date NOT NULL DEFAULT '0000-00-00',
  `goods_in_transit` tinyint NOT NULL DEFAULT '0' COMMENT '1 - YES, 2 - NO',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_purchase_order` */

/*Table structure for table `tbl_purchase_order_items` */

DROP TABLE IF EXISTS `tbl_purchase_order_items`;

CREATE TABLE `tbl_purchase_order_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','PURCHASE_REQUEST','SALES_ORDER') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `purchase_order_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `unit_price` double unsigned NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL,
  `bin_id` int unsigned DEFAULT '0',
  `last_price` double unsigned DEFAULT '0',
  `item_weight` varchar(256) NOT NULL DEFAULT '',
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_purchase_order_items` */

/*Table structure for table `tbl_purchase_request` */

DROP TABLE IF EXISTS `tbl_purchase_request`;

CREATE TABLE `tbl_purchase_request` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `requester_type_id` tinyint NOT NULL DEFAULT '0',
  `requester_id` int unsigned NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `valid_until` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint NOT NULL DEFAULT '1',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `total_weight` double unsigned NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `approval_status` tinyint NOT NULL DEFAULT '4' COMMENT '1 - PENDING, 2 - APPROVED, 3 - REJECTED,4 -> No Approval Process',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `branch_id` int NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_purchase_request` */

/*Table structure for table `tbl_purchase_request_items` */

DROP TABLE IF EXISTS `tbl_purchase_request_items`;

CREATE TABLE `tbl_purchase_request_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `purchase_request_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `required_date` date NOT NULL DEFAULT '0000-00-00',
  `unit_price` double unsigned NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL,
  `bin_id` int unsigned DEFAULT '0',
  `last_price` double unsigned DEFAULT '0',
  `item_weight` varchar(256) NOT NULL DEFAULT '',
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_purchase_request_items` */

/*Table structure for table `tbl_rental_delivery` */

DROP TABLE IF EXISTS `tbl_rental_delivery`;

CREATE TABLE `tbl_rental_delivery` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `customer_ship_to_bp_address_id` int unsigned NOT NULL,
  `customer_ship_to_address` text NOT NULL,
  `customer_bill_to_bp_address_id` int unsigned NOT NULL,
  `customer_bill_to_address` text NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `emp_id` int unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `terms_and_condition_id` int unsigned NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_delivery` */

/*Table structure for table `tbl_rental_delivery_items` */

DROP TABLE IF EXISTS `tbl_rental_delivery_items`;

CREATE TABLE `tbl_rental_delivery_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','RENTAL_QUOTE','RENTAL_ORDER','RENTAL_INSPECTION_OUT') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `rental_delivery_id` int unsigned NOT NULL,
  `is_utilized` tinyint NOT NULL DEFAULT '0',
  `rental_item_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `duration` double unsigned NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `ship_date` date NOT NULL DEFAULT '0000-00-00',
  `return_date` date NOT NULL DEFAULT '0000-00-00',
  `po_expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `rental_equipment_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `unit_price` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_delivery_items` */

/*Table structure for table `tbl_rental_equipment_screen_configuration` */

DROP TABLE IF EXISTS `tbl_rental_equipment_screen_configuration`;

CREATE TABLE `tbl_rental_equipment_screen_configuration` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `current_screen_name` enum('RENTAL_QUOTE','RENTAL_ORDER','RENTAL_INSPECTION_OUT','RENTAL_DELIVERY','RENTAL_RETURN','RENTAL_INSPECTION_IN','RENTAL_INVOICE','RENTAL_WORKLOG') NOT NULL,
  `copy_from_screen_name` enum('DIRECT','RENTAL_QUOTE','RENTAL_ORDER','RENTAL_INSPECTION_OUT','RENTAL_DELIVERY','RENTAL_RETURN','RENTAL_INSPECTION_IN','RENTAL_INVOICE','RENTAL_WORKLOG') NOT NULL,
  `from_status` enum('RESERVED','DELIVERED','RETURNED','AVAILABLE','UNAVAILABLE') NOT NULL,
  `to_status` enum('RESERVED','DELIVERED','RETURNED','AVAILABLE','UNAVAILABLE') NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_equipment_screen_configuration` */

/*Table structure for table `tbl_rental_inspection_in` */

DROP TABLE IF EXISTS `tbl_rental_inspection_in`;

CREATE TABLE `tbl_rental_inspection_in` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `rental_item_id` int unsigned NOT NULL,
  `rental_equipment_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `emp_id` int unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `inspection_overall_status` tinyint NOT NULL DEFAULT '0',
  `inspection_template_id` int unsigned NOT NULL,
  `udf_fields` text NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_inspection_in` */

/*Table structure for table `tbl_rental_inspection_in_items` */

DROP TABLE IF EXISTS `tbl_rental_inspection_in_items`;

CREATE TABLE `tbl_rental_inspection_in_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','RENTAL_RETURN') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `rental_inspection_in_id` int unsigned NOT NULL,
  `is_utilized` tinyint NOT NULL DEFAULT '0',
  `template_details` longtext,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_inspection_in_items` */

/*Table structure for table `tbl_rental_inspection_out` */

DROP TABLE IF EXISTS `tbl_rental_inspection_out`;

CREATE TABLE `tbl_rental_inspection_out` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `rental_item_id` int unsigned NOT NULL,
  `rental_equipment_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `emp_id` int unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `inspection_overall_status` tinyint NOT NULL DEFAULT '0',
  `inspection_template_id` int unsigned NOT NULL,
  `udf_fields` text NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_inspection_out` */

/*Table structure for table `tbl_rental_inspection_out_items` */

DROP TABLE IF EXISTS `tbl_rental_inspection_out_items`;

CREATE TABLE `tbl_rental_inspection_out_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','RENTAL_QUOTE','RENTAL_ORDER') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `rental_inspection_out_id` int unsigned NOT NULL,
  `is_utilized` tinyint NOT NULL DEFAULT '0',
  `template_details` longtext,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_inspection_out_items` */

/*Table structure for table `tbl_rental_invoice` */

DROP TABLE IF EXISTS `tbl_rental_invoice`;

CREATE TABLE `tbl_rental_invoice` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `customer_ship_to_bp_address_id` int unsigned NOT NULL,
  `customer_ship_to_address` text NOT NULL,
  `customer_bill_to_bp_address_id` int unsigned NOT NULL,
  `customer_bill_to_address` text NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `emp_id` int unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `terms_and_condition_id` int unsigned NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_invoice` */

/*Table structure for table `tbl_rental_invoice_items` */

DROP TABLE IF EXISTS `tbl_rental_invoice_items`;

CREATE TABLE `tbl_rental_invoice_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','RENTAL_ORDER') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `rental_invoice_id` int unsigned NOT NULL,
  `is_utilized` tinyint NOT NULL DEFAULT '0',
  `rental_item_id` int unsigned NOT NULL,
  `rental_worklog_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `duration` double unsigned NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `ship_date` date NOT NULL DEFAULT '0000-00-00',
  `return_date` date NOT NULL DEFAULT '0000-00-00',
  `po_expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `rental_equipment_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `unit_price` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_invoice_items` */

/*Table structure for table `tbl_rental_order` */

DROP TABLE IF EXISTS `tbl_rental_order`;

CREATE TABLE `tbl_rental_order` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `customer_ship_to_bp_address_id` int unsigned NOT NULL,
  `customer_ship_to_address` text NOT NULL,
  `customer_bill_to_bp_address_id` int unsigned NOT NULL,
  `customer_bill_to_address` text NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `emp_id` int unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `terms_and_condition_id` int unsigned NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_order` */

/*Table structure for table `tbl_rental_order_items` */

DROP TABLE IF EXISTS `tbl_rental_order_items`;

CREATE TABLE `tbl_rental_order_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','RENTAL_QUOTE') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `rental_order_id` int unsigned NOT NULL,
  `rental_item_id` int unsigned NOT NULL,
  `is_utilized` tinyint NOT NULL DEFAULT '0',
  `quantity` double unsigned NOT NULL,
  `open_quantity` double NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `duration` double unsigned NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `ship_date` date NOT NULL DEFAULT '0000-00-00',
  `return_date` date NOT NULL DEFAULT '0000-00-00',
  `po_expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `rental_equipment_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `unit_price` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_order_items` */

/*Table structure for table `tbl_rental_quote` */

DROP TABLE IF EXISTS `tbl_rental_quote`;

CREATE TABLE `tbl_rental_quote` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `customer_ship_to_bp_address_id` int unsigned NOT NULL,
  `customer_ship_to_address` text NOT NULL,
  `customer_bill_to_bp_address_id` int unsigned NOT NULL,
  `customer_bill_to_address` text NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `emp_id` int unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `terms_and_condition_id` int unsigned NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_quote` */

/*Table structure for table `tbl_rental_quote_items` */

DROP TABLE IF EXISTS `tbl_rental_quote_items`;

CREATE TABLE `tbl_rental_quote_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `rental_quote_id` int unsigned NOT NULL,
  `rental_item_id` int unsigned NOT NULL,
  `is_utilized` tinyint NOT NULL DEFAULT '0',
  `quantity` double unsigned NOT NULL,
  `duration` double unsigned NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `ship_date` date NOT NULL DEFAULT '0000-00-00',
  `return_date` date NOT NULL DEFAULT '0000-00-00',
  `po_expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `rental_equipment_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `unit_price` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_quote_items` */

/*Table structure for table `tbl_rental_return` */

DROP TABLE IF EXISTS `tbl_rental_return`;

CREATE TABLE `tbl_rental_return` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `customer_ship_to_bp_address_id` int unsigned NOT NULL,
  `customer_ship_to_address` text NOT NULL,
  `customer_bill_to_bp_address_id` int unsigned NOT NULL,
  `customer_bill_to_address` text NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `return_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `emp_id` int unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `terms_and_condition_id` int unsigned NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_return` */

/*Table structure for table `tbl_rental_return_items` */

DROP TABLE IF EXISTS `tbl_rental_return_items`;

CREATE TABLE `tbl_rental_return_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','RENTAL_DELIVERY') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `rental_return_id` int unsigned NOT NULL,
  `is_utilized` tinyint NOT NULL DEFAULT '0',
  `rental_item_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `duration` double unsigned NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `ship_date` date NOT NULL DEFAULT '0000-00-00',
  `return_date` date NOT NULL DEFAULT '0000-00-00',
  `po_expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `rental_equipment_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `unit_price` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_return_items` */

/*Table structure for table `tbl_rental_worklog` */

DROP TABLE IF EXISTS `tbl_rental_worklog`;

CREATE TABLE `tbl_rental_worklog` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `rental_item_id` int unsigned NOT NULL,
  `copy_from_type` enum('','RENTAL_ORDER') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `rental_equipment_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `shift_start_time` varchar(128) NOT NULL DEFAULT '',
  `shift_end_time` varchar(128) NOT NULL DEFAULT '',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `emp_id` int unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `total_billable_hours` varchar(128) NOT NULL,
  `udf_fields` text NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_worklog` */

/*Table structure for table `tbl_rental_worklog_items` */

DROP TABLE IF EXISTS `tbl_rental_worklog_items`;

CREATE TABLE `tbl_rental_worklog_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `rental_worklog_id` int unsigned NOT NULL,
  `rental_equipment_id` int unsigned NOT NULL,
  `worklog_item_type_id` int unsigned NOT NULL,
  `emp_id` int unsigned NOT NULL,
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `start_date_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `total_hours` varchar(128) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_worklog_items` */

/*Table structure for table `tbl_rental_worklog_items_type_2` */

DROP TABLE IF EXISTS `tbl_rental_worklog_items_type_2`;

CREATE TABLE `tbl_rental_worklog_items_type_2` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `rental_worklog_id` int unsigned NOT NULL,
  `rental_equipment_id` int unsigned NOT NULL,
  `entry_day` varchar(128) NOT NULL DEFAULT '',
  `entry_date` date NOT NULL DEFAULT '0000-00-00',
  `shift_start_time` varchar(128) NOT NULL DEFAULT '',
  `shift_end_time` varchar(128) NOT NULL DEFAULT '',
  `total_hours` varchar(128) NOT NULL,
  `breakdown_hours` varchar(128) NOT NULL,
  `overtime_hours` varchar(128) NOT NULL,
  `billable_hours` varchar(128) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1120 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_rental_worklog_items_type_2` */

/*Table structure for table `tbl_sales_ar_credit_memo` */

DROP TABLE IF EXISTS `tbl_sales_ar_credit_memo`;

CREATE TABLE `tbl_sales_ar_credit_memo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `customer_ship_to_bp_address_id` int unsigned NOT NULL,
  `customer_ship_to_address` text NOT NULL,
  `customer_bill_to_bp_address_id` int unsigned NOT NULL,
  `customer_bill_to_address` text NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint NOT NULL DEFAULT '1',
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `original_ref_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `original_ref_date` date NOT NULL DEFAULT '0000-00-00',
  `issuing_note_id` int unsigned NOT NULL DEFAULT '0',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `sales_emp_id` int unsigned NOT NULL,
  `sales_ar_dp_invoice_id` int unsigned NOT NULL,
  `sales_ar_dp_invoice_used_amount` double unsigned NOT NULL,
  `sales_ar_dp_invoice_used_tax_amount` double unsigned NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `approval_status` tinyint NOT NULL DEFAULT '4' COMMENT '1 - PENDING, 2 - APPROVED, 3 - REJECTED 4 - No Approval process',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_ar_credit_memo` */

/*Table structure for table `tbl_sales_ar_credit_memo_items` */

DROP TABLE IF EXISTS `tbl_sales_ar_credit_memo_items`;

CREATE TABLE `tbl_sales_ar_credit_memo_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','SALES_AR_INVOICE','SALES_AR_DP_INVOICE') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `sales_ar_credit_memo_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `unit_price` double unsigned NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL,
  `bin_id` int unsigned DEFAULT '0',
  `last_price` double unsigned DEFAULT '0',
  `without_qty_posting` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated, 1 - updated to stock',
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_ar_credit_memo_items` */

/*Table structure for table `tbl_sales_ar_dp_invoice` */

DROP TABLE IF EXISTS `tbl_sales_ar_dp_invoice`;

CREATE TABLE `tbl_sales_ar_dp_invoice` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `customer_ship_to_bp_address_id` int unsigned NOT NULL,
  `customer_ship_to_address` text NOT NULL,
  `customer_bill_to_bp_address_id` int unsigned NOT NULL,
  `customer_bill_to_address` text NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint NOT NULL DEFAULT '1',
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double unsigned NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `remaining_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `sales_emp_id` int unsigned NOT NULL,
  `dpm_percentage` double unsigned NOT NULL,
  `dpm_value` double unsigned NOT NULL,
  `incoming_payment_flag` tinyint NOT NULL DEFAULT '0',
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `approval_status` tinyint NOT NULL DEFAULT '4' COMMENT '1 - PENDING, 2 - APPROVED, 3 - REJECTED 4 - No Approval process',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_ar_dp_invoice` */

/*Table structure for table `tbl_sales_ar_dp_invoice_items` */

DROP TABLE IF EXISTS `tbl_sales_ar_dp_invoice_items`;

CREATE TABLE `tbl_sales_ar_dp_invoice_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','SALES_QUOTE','SALES_ORDER','SALES_DELIVERY') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `sales_ar_dp_invoice_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `unit_price` double unsigned NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL,
  `bin_id` int unsigned DEFAULT '0',
  `last_price` double unsigned DEFAULT '0',
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_ar_dp_invoice_items` */

/*Table structure for table `tbl_sales_ar_invoice` */

DROP TABLE IF EXISTS `tbl_sales_ar_invoice`;

CREATE TABLE `tbl_sales_ar_invoice` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `customer_ship_to_bp_address_id` int unsigned NOT NULL,
  `customer_ship_to_address` text NOT NULL,
  `customer_bill_to_bp_address_id` int unsigned NOT NULL,
  `customer_bill_to_address` text NOT NULL,
  `tracking_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `courier` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `delivery_status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - NO, 2 - Yes',
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint NOT NULL DEFAULT '1',
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `sales_emp_id` int unsigned NOT NULL,
  `sales_ar_dp_invoice_id` int unsigned NOT NULL,
  `sales_ar_dp_invoice_used_amount` double unsigned NOT NULL,
  `sales_ar_dp_invoice_used_tax_amount` double unsigned NOT NULL,
  `sales_ar_dp_invoice_used_remaining_amount` double unsigned NOT NULL,
  `incoming_payment_flag` tinyint NOT NULL DEFAULT '0',
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `approval_status` tinyint NOT NULL DEFAULT '4' COMMENT '1 - PENDING, 2 - APPROVED, 3 - REJECTED 4 - No Approval process',
  `gatepass_status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - No gatepass, 2 - open for gatepass ',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_ar_invoice` */

/*Table structure for table `tbl_sales_ar_invoice_items` */

DROP TABLE IF EXISTS `tbl_sales_ar_invoice_items`;

CREATE TABLE `tbl_sales_ar_invoice_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','SALES_QUOTE','SALES_ORDER','SALES_DELIVERY') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `sales_ar_invoice_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `unit_price` double unsigned NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL,
  `bin_id` int unsigned DEFAULT '0',
  `last_price` double unsigned DEFAULT '0',
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_ar_invoice_items` */

/*Table structure for table `tbl_sales_delivery` */

DROP TABLE IF EXISTS `tbl_sales_delivery`;

CREATE TABLE `tbl_sales_delivery` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `customer_ship_to_bp_address_id` int unsigned NOT NULL,
  `customer_ship_to_address` text NOT NULL,
  `customer_bill_to_bp_address_id` int unsigned NOT NULL,
  `customer_bill_to_address` text NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint NOT NULL DEFAULT '1',
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `sales_emp_id` int unsigned NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `approval_status` tinyint NOT NULL DEFAULT '4' COMMENT '1 - PENDING, 2 - APPROVED, 3 - REJECTED 4 - No Approval process',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_delivery` */

/*Table structure for table `tbl_sales_delivery_items` */

DROP TABLE IF EXISTS `tbl_sales_delivery_items`;

CREATE TABLE `tbl_sales_delivery_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','SALES_QUOTE','SALES_ORDER') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `sales_delivery_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `unit_price` double unsigned NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL,
  `bin_id` int unsigned DEFAULT '0',
  `last_price` double unsigned DEFAULT '0',
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_delivery_items` */

/*Table structure for table `tbl_sales_order` */

DROP TABLE IF EXISTS `tbl_sales_order`;

CREATE TABLE `tbl_sales_order` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `customer_ship_to_bp_address_id` int unsigned NOT NULL,
  `customer_ship_to_address` text NOT NULL,
  `customer_bill_to_bp_address_id` int unsigned NOT NULL,
  `customer_bill_to_address` text NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint NOT NULL DEFAULT '1',
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `sales_emp_id` int unsigned NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `approval_status` tinyint NOT NULL DEFAULT '4' COMMENT '1 - PENDING, 2 - APPROVED, 3 - REJECTED 4 - No Approval process',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_order` */

/*Table structure for table `tbl_sales_order_items` */

DROP TABLE IF EXISTS `tbl_sales_order_items`;

CREATE TABLE `tbl_sales_order_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','SALES_QUOTE') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `sales_order_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `unit_price` double unsigned NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL,
  `bin_id` int unsigned DEFAULT '0',
  `last_price` double unsigned DEFAULT '0',
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `serial_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `bp_approval` tinyint NOT NULL DEFAULT '0',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `dealer_discount_percentage` double unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=272 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_order_items` */

/*Table structure for table `tbl_sales_quote` */

DROP TABLE IF EXISTS `tbl_sales_quote`;

CREATE TABLE `tbl_sales_quote` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `customer_ship_to_bp_address_id` int unsigned NOT NULL,
  `customer_ship_to_address` text NOT NULL,
  `customer_bill_to_bp_address_id` int unsigned NOT NULL,
  `customer_bill_to_address` text NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `valid_until` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint NOT NULL DEFAULT '1',
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `sales_emp_id` int unsigned NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `approval_status` tinyint NOT NULL DEFAULT '4' COMMENT '1 - PENDING, 2 - APPROVED, 3 - REJECTED 4 - No Approval process',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_quote` */

/*Table structure for table `tbl_sales_quote_items` */

DROP TABLE IF EXISTS `tbl_sales_quote_items`;

CREATE TABLE `tbl_sales_quote_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `sales_quote_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `required_date` date NOT NULL DEFAULT '0000-00-00',
  `unit_price` double unsigned NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL,
  `bin_id` int unsigned DEFAULT '0',
  `last_price` double unsigned DEFAULT '0',
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=331 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_quote_items` */

/*Table structure for table `tbl_sales_return` */

DROP TABLE IF EXISTS `tbl_sales_return`;

CREATE TABLE `tbl_sales_return` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `document_numbering_id` int unsigned NOT NULL,
  `customer_bp_id` int unsigned NOT NULL,
  `customer_bp_contacts_id` int unsigned NOT NULL,
  `customer_ship_to_bp_address_id` int unsigned NOT NULL,
  `customer_ship_to_address` text NOT NULL,
  `customer_bill_to_bp_address_id` int unsigned NOT NULL,
  `customer_bill_to_address` text NOT NULL,
  `reference_number` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `currency_id` int unsigned NOT NULL,
  `posting_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `document_date` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint NOT NULL DEFAULT '1',
  `tax_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `udf_fields` text NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `rounding` double NOT NULL,
  `rounding_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0 -rounding off , 1 - rounding on',
  `discount_value` double unsigned NOT NULL,
  `tax_percentage` double unsigned NOT NULL,
  `total_amount` double unsigned NOT NULL,
  `total_before_discount` double unsigned NOT NULL,
  `payment_terms_id` int unsigned NOT NULL,
  `payment_method_id` int unsigned NOT NULL,
  `sales_emp_id` int unsigned NOT NULL,
  `branch_id` int NOT NULL,
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `approval_status` tinyint NOT NULL DEFAULT '4' COMMENT '1 - PENDING, 2 - APPROVED, 3 - REJECTED 4 - No Approval process',
  `is_draft` tinyint NOT NULL DEFAULT '0' COMMENT '0 - NOT DRAFT, 1 - DRAFT',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_return` */

/*Table structure for table `tbl_sales_return_items` */

DROP TABLE IF EXISTS `tbl_sales_return_items`;

CREATE TABLE `tbl_sales_return_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `copy_from_type` enum('','SALES_DELIVERY') NOT NULL,
  `copy_from_id` int unsigned NOT NULL DEFAULT '0',
  `sales_return_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `uom_id` int unsigned NOT NULL,
  `quantity` double unsigned NOT NULL,
  `open_quantity` double NOT NULL DEFAULT '0',
  `ordered_quantity` double NOT NULL DEFAULT '0',
  `unit_price` double unsigned NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `tax_id` double unsigned NOT NULL,
  `hsn_id` int unsigned DEFAULT '0',
  `item_tax_percentage` double unsigned NOT NULL,
  `item_tax_value` double unsigned NOT NULL,
  `total_item_amount` double unsigned NOT NULL,
  `warehouse_id` int unsigned NOT NULL,
  `bin_id` int unsigned DEFAULT '0',
  `last_price` double unsigned DEFAULT '0',
  `distribution_rules_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sales_return_items` */

/*Table structure for table `tbl_settings` */

DROP TABLE IF EXISTS `tbl_settings`;

CREATE TABLE `tbl_settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sales_tax_id` int unsigned NOT NULL DEFAULT '0',
  `purchase_tax_id` int unsigned NOT NULL DEFAULT '0',
  `bp_credit_limit_strict_mode` tinyint NOT NULL DEFAULT '2' COMMENT '1 - Strict, 2 -> none',
  `branch_id` int NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_settings` */

/*Table structure for table `tbl_smt_team` */

DROP TABLE IF EXISTS `tbl_smt_team`;

CREATE TABLE `tbl_smt_team` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `team_name` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `team_head_id` int unsigned DEFAULT NULL,
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `branch_id` int NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_smt_team` */

/*Table structure for table `tbl_smt_team_members` */

DROP TABLE IF EXISTS `tbl_smt_team_members`;

CREATE TABLE `tbl_smt_team_members` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `team_id` int unsigned DEFAULT NULL,
  `emp_id` int unsigned DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `branch_id` int NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_smt_team_members` */

/*Table structure for table `tbl_smt_visits` */

DROP TABLE IF EXISTS `tbl_smt_visits`;

CREATE TABLE `tbl_smt_visits` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `team_id` int unsigned DEFAULT NULL,
  `emp_id` int unsigned DEFAULT NULL,
  `business_partner_id` int unsigned DEFAULT NULL,
  `check_in_datetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `check_out_datetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `check_in_latitude` decimal(10,8) NOT NULL DEFAULT '0.00000000',
  `check_out_latitude` decimal(10,8) NOT NULL DEFAULT '0.00000000',
  `check_in_longitude` decimal(10,8) NOT NULL DEFAULT '0.00000000',
  `check_out_longitude` decimal(10,8) NOT NULL DEFAULT '0.00000000',
  `remarks` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `branch_id` int NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1230 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tbl_smt_visits` */

/*Table structure for table `tbl_sp_business_partner` */

DROP TABLE IF EXISTS `tbl_sp_business_partner`;

CREATE TABLE `tbl_sp_business_partner` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `business_partner_id` int unsigned NOT NULL,
  `item_id` int unsigned NOT NULL,
  `price_list_id` int unsigned NOT NULL,
  `discount_percentage` double unsigned NOT NULL,
  `unit_price` double unsigned NOT NULL,
  `price_after_discount` double unsigned NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sp_business_partner` */

/*Table structure for table `tbl_tracking_transaction` */

DROP TABLE IF EXISTS `tbl_tracking_transaction`;

CREATE TABLE `tbl_tracking_transaction` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `transaction_ids` text,
  `transaction_details` longtext,
  `branch_id` int unsigned NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=407 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_tracking_transaction` */

/*Table structure for table `tbl_udf_master_field_type` */

DROP TABLE IF EXISTS `tbl_udf_master_field_type`;

CREATE TABLE `tbl_udf_master_field_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `field_type_name` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_udf_master_field_type` */

insert  into `tbl_udf_master_field_type`(`id`,`field_type_name`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,'Text Box','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(2,'Select Box','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(3,'Date Box','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,0,'0','','SAP','SAP',0),
(4,'Number Box','0000-00-00 00:00:00',1,'0000-00-00 00:00:00',NULL,0,'','','SAP','SAP',0);

/*Table structure for table `tbl_udf_master_form_controls` */

DROP TABLE IF EXISTS `tbl_udf_master_form_controls`;

CREATE TABLE `tbl_udf_master_form_controls` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `field_type_id` int unsigned NOT NULL,
  `field_label` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `field_name` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `required_field` tinyint NOT NULL DEFAULT '0' COMMENT '0 -NO, 1 - Yes',
  `branch_id` int NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1 - Active, 2 - In-Active',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_udf_master_form_controls` */

insert  into `tbl_udf_master_form_controls`(`id`,`field_type_id`,`field_label`,`field_name`,`required_field`,`branch_id`,`status`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,1,'EA_CN','Chassis Number',0,1,1,'2020-09-21 16:48:06',1,'2020-09-21 16:48:06',NULL,0,'','','WEB','SAP',0),
(2,1,'Vehicle No','vehicle',0,1,1,'2020-10-03 07:00:17',1,'2020-10-03 07:00:17',NULL,0,'','','WEB','SAP',0),
(3,1,'LANDED COST','LANDED',0,5,1,'2020-10-07 06:23:15',1,'2020-10-07 06:23:15',NULL,0,'','','WEB','SAP',0),
(4,1,'Vehicle No','vehicle',0,2,1,'2020-10-07 13:42:22',1,'2020-10-07 13:42:22',NULL,0,'','','WEB','SAP',0),
(5,1,'Driver Name','driver',0,1,1,'2020-10-09 02:09:21',1,'2020-10-09 02:09:21',NULL,0,'','','WEB','SAP',0),
(6,1,'Vehicle No.','Vehicle No.',0,1,1,'2020-10-09 06:31:46',1,'2020-10-09 06:31:46',NULL,0,'','','WEB','SAP',0),
(7,1,'Complaints','Complaints',0,1,1,'2020-10-09 06:34:18',1,'2020-10-09 06:34:18',NULL,0,'','','WEB','SAP',0),
(8,1,'sd','EA_CN',0,1,1,'2020-10-09 12:39:10',1,'2020-10-09 12:39:10',NULL,0,'','','WEB','SAP',0),
(9,1,'EA_CN','fsg',0,1,1,'2020-10-09 12:41:52',1,'2020-10-09 12:41:52',NULL,0,'','','WEB','SAP',0),
(10,1,'as','EA_CN',0,2,1,'2020-10-09 12:46:30',1,'2020-10-09 12:46:30',NULL,0,'','','WEB','SAP',0),
(11,1,'location','LOCATION',1,1,1,'2020-11-19 04:39:35',1,'2020-11-19 04:39:35',NULL,0,'','','WEB','SAP',0),
(12,2,'GENDER','GENDER',1,1,1,'2020-11-19 04:41:55',1,'2020-11-19 04:41:55',NULL,0,'','','WEB','SAP',0),
(13,1,'Check','Check',1,1,2,'2021-04-13 01:07:58',1,'2023-09-13 02:24:59',1,0,'','','WEB','WEB',0),
(14,2,'Test','Test',1,1,1,'2021-04-21 00:41:31',1,'2021-04-21 00:41:31',NULL,0,'','','WEB','SAP',0),
(15,1,'TEST','TESTING',0,1,1,'2021-04-27 03:46:37',1,'2021-04-27 03:46:45',1,0,'','','WEB','WEB',0),
(16,1,'sdfsdf','sdfsdf',1,1,2,'2021-04-30 07:36:08',1,'2021-07-11 00:15:57',1,0,'','','WEB','WEB',0),
(17,1,'SBAM_REG NO.','1. EQUIPMENT REGISTRATION NUMBER',0,1,1,'2021-07-11 00:02:00',1,'2021-07-11 00:03:41',1,0,'','','WEB','WEB',0),
(18,1,'SBAM_INS NO.','2. EQUIPMENT INSURANCE NUMBER',1,1,1,'2021-07-11 00:04:31',1,'2021-07-11 00:04:31',NULL,0,'','','WEB','SAP',0),
(19,2,'SBAM_OPERATOR OPTION','3.OPERATOR OPTION',0,1,1,'2021-07-11 00:06:16',1,'2021-07-11 00:07:32',1,0,'','','WEB','WEB',0),
(20,2,'SBAM_DEL OPT','4.EQUIPMENT DELIVERY OPTIONS',1,1,1,'2021-07-11 00:07:17',1,'2021-07-11 00:07:41',1,0,'','','WEB','WEB',0),
(21,1,'Sales person','Sales Person',0,10,1,'2021-09-21 02:49:28',1,'2021-09-21 08:17:13',1,0,'','','WEB','WEB',0),
(22,2,'document test2','document test3',0,10,1,'2021-09-21 08:25:42',1,'2021-09-21 08:31:16',1,0,'','','WEB','WEB',0),
(23,2,'Sales type','Sales type',0,1,1,'2021-10-22 03:33:32',1,'2021-10-22 03:33:32',NULL,0,'','','WEB','SAP',0),
(24,4,'SL No','SL No',0,1,1,'2022-03-05 02:29:15',1,'2022-03-05 02:29:58',1,0,'','','WEB','WEB',0),
(25,3,'New UDF Date','New UDF Date',0,1,1,'2022-08-01 02:35:44',1,'2022-08-01 02:35:44',NULL,0,'','','WEB','SAP',0),
(26,1,'TEST02091983','TEST02091983_A',0,10,1,'2023-09-01 21:58:44',1,'2023-09-01 22:47:08',1,0,'','','WEB','WEB',0),
(27,2,'Check Box','Check Box',0,10,1,'2023-09-01 22:46:04',1,'2023-09-01 22:46:04',NULL,0,'','','WEB','SAP',0),
(28,3,'Datebox','Datebox',0,10,1,'2023-09-01 22:46:33',1,'2023-09-01 22:46:33',NULL,0,'','','WEB','SAP',0),
(29,4,'Numberbox','Numberbox',0,10,1,'2023-09-01 22:46:55',1,'2023-09-01 22:46:55',NULL,0,'','','WEB','SAP',0),
(30,1,'TESTING udf','T_UDF',0,10,1,'2023-09-08 22:49:09',1,'2023-09-08 22:49:09',NULL,0,'','','WEB','SAP',0),
(31,1,'HO01','HO01',0,10,1,'2023-09-13 02:28:51',1,'2023-09-13 02:28:51',NULL,0,'','','WEB','SAP',0),
(32,1,'HO01','HO01',0,1,2,'2023-09-13 02:29:31',250,'2023-09-13 02:29:46',250,0,'','','WEB','WEB',0);

/*Table structure for table `tbl_udf_master_form_controls_options` */

DROP TABLE IF EXISTS `tbl_udf_master_form_controls_options`;

CREATE TABLE `tbl_udf_master_form_controls_options` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `form_controls_id` int unsigned NOT NULL,
  `options_field_label` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `default_selected` tinyint NOT NULL DEFAULT '0' COMMENT '0 -NO, 1 - Yes',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_udf_master_form_controls_options` */

insert  into `tbl_udf_master_form_controls_options`(`id`,`form_controls_id`,`options_field_label`,`default_selected`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,12,'MALE',1,'2020-11-19 04:41:55',1,'2020-11-19 04:41:55',NULL,0,'','','WEB','SAP',0),
(2,12,'FEMALE',0,'2020-11-19 04:41:55',1,'2020-11-19 04:41:55',NULL,0,'','','WEB','SAP',0),
(3,14,'1',0,'2021-04-21 00:41:31',1,'2021-04-21 00:41:31',NULL,0,'','','WEB','SAP',0),
(4,14,'2',0,'2021-04-21 00:41:31',1,'2021-04-21 00:41:31',NULL,0,'','','WEB','SAP',0),
(5,14,'3',1,'2021-04-21 00:41:31',1,'2021-04-21 00:41:31',NULL,0,'','','WEB','SAP',0),
(6,14,'4',0,'2021-04-21 00:41:31',1,'2021-04-21 00:41:31',NULL,0,'','','WEB','SAP',0),
(7,19,'SBAM OPERATOR',0,'2021-07-11 00:06:16',1,'2021-07-11 00:07:32',1,0,'','','WEB','WEB',0),
(8,19,'CUSTOMER OPERATOR',0,'2021-07-11 00:06:16',1,'2021-07-11 00:07:32',1,0,'','','WEB','WEB',0),
(9,19,'RENTAL OPERATOR',0,'2021-07-11 00:06:16',1,'2021-07-11 00:07:32',1,0,'','','WEB','WEB',0),
(10,20,'SBAM DELIVERY',1,'2021-07-11 00:07:17',1,'2021-07-11 00:07:41',1,0,'','','WEB','WEB',0),
(11,20,'CUSTOMER PICK UP',0,'2021-07-11 00:07:17',1,'2021-07-11 00:07:41',1,0,'','','WEB','WEB',0),
(12,22,'document test',1,'2021-09-21 08:25:42',1,'2021-09-21 08:31:16',1,0,'','','WEB','WEB',0),
(13,23,'Sold',0,'2021-10-22 03:33:32',1,'2021-10-22 03:33:32',NULL,0,'','','WEB','SAP',0),
(14,23,'Rental',0,'2021-10-22 03:33:32',1,'2021-10-22 03:33:32',NULL,0,'','','WEB','SAP',0),
(15,23,'Partial',0,'2021-10-22 03:33:32',1,'2021-10-22 03:33:32',NULL,0,'','','WEB','SAP',0),
(16,27,'CB1',0,'2023-09-01 22:46:04',1,'2023-09-01 22:46:04',NULL,0,'','','WEB','SAP',0),
(17,27,'CB2',0,'2023-09-01 22:46:04',1,'2023-09-01 22:46:04',NULL,0,'','','WEB','SAP',0),
(18,27,'CB3',1,'2023-09-01 22:46:04',1,'2023-09-01 22:46:04',NULL,0,'','','WEB','SAP',0);

/*Table structure for table `tbl_udf_screen_mapping` */

DROP TABLE IF EXISTS `tbl_udf_screen_mapping`;

CREATE TABLE `tbl_udf_screen_mapping` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `document_type_id` int unsigned NOT NULL,
  `form_controls_id` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `branch_id` int NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_udf_screen_mapping` */

insert  into `tbl_udf_screen_mapping`(`id`,`document_type_id`,`form_controls_id`,`branch_id`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(1,3,'1,14,5,9,6,12',1,'2020-09-21 16:48:27',1,'2023-09-13 02:24:48',1,0,'','','WEB','WEB',0),
(2,4,'1,5',1,'2020-10-03 06:28:31',1,'2021-05-04 02:34:40',1,0,'','','WEB','WEB',0),
(3,14,'2,7',1,'2020-10-03 07:00:27',1,'2020-10-09 06:34:56',1,0,'','','WEB','WEB',0),
(4,3,'3',5,'2020-10-07 06:23:24',1,'2020-10-07 06:23:24',NULL,0,'','','WEB','SAP',0),
(5,10,'2,5',1,'2020-10-09 02:09:36',1,'2020-10-09 02:09:36',NULL,0,'','','WEB','SAP',0),
(6,1,'15,24',1,'2020-10-28 02:19:31',1,'2022-03-05 02:29:25',1,0,'','','WEB','WEB',0),
(7,2,'12,11',1,'2020-11-28 04:26:27',1,'2020-11-28 04:26:27',NULL,0,'','','WEB','SAP',0),
(8,18,'17,18,19,20',1,'2021-05-18 04:39:00',1,'2021-07-11 00:19:08',1,0,'','','WEB','WEB',0),
(9,25,'17,18,19,20',1,'2021-05-18 07:21:42',1,'2021-07-11 00:24:26',1,0,'','','WEB','WEB',0),
(10,19,'17,18,19,20',1,'2021-05-26 00:31:14',1,'2021-07-11 00:18:58',1,0,'','','WEB','WEB',0),
(11,22,'17,18,19,20',1,'2021-05-26 00:46:50',1,'2021-07-11 00:20:29',1,0,'','','WEB','WEB',0),
(12,24,'17,18,19,20',1,'2021-05-26 00:49:29',1,'2021-07-11 00:22:00',1,0,'','','WEB','WEB',0),
(13,23,'17,18,19,20',1,'2021-05-29 21:04:36',1,'2021-07-11 00:21:09',1,0,'','','WEB','WEB',0),
(14,20,'17,18,19,20',1,'2021-05-29 21:04:45',1,'2021-07-11 00:19:20',1,0,'','','WEB','WEB',0),
(15,21,'12,11,19,20',1,'2021-06-05 04:07:31',1,'2021-07-11 00:19:36',1,0,'','','WEB','WEB',0),
(16,4,'22,30',10,'2021-09-21 08:16:18',1,'2023-09-08 22:49:48',1,0,'','','WEB','WEB',0),
(17,5,'25',1,'2022-08-01 02:35:57',1,'2022-08-01 02:35:57',NULL,0,'','','WEB','SAP',0),
(18,1,'26,27,28,29',10,'2023-09-01 21:59:40',1,'2023-09-01 22:47:30',1,0,'','','WEB','WEB',0),
(19,3,'27',10,'2023-09-01 22:02:40',1,'2023-09-13 02:25:46',250,0,'','','WEB','WEB',0),
(20,19,'',10,'2023-09-01 22:02:55',1,'2023-09-01 22:07:29',1,0,'','','WEB','WEB',0),
(21,23,'',10,'2023-09-01 22:03:09',1,'2023-09-01 22:07:00',1,0,'','','WEB','WEB',0);

/*Table structure for table `tbl_user_line_item_configuration` */

DROP TABLE IF EXISTS `tbl_user_line_item_configuration`;

CREATE TABLE `tbl_user_line_item_configuration` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `module` enum('SALES_QUOTE','SALES_ORDER','PURCHASE_REQUEST','PURCHASE_ORDER','GRPO','INVENTORY_TRANSFER_REQUEST','INVENTORY_TRANSFER','SALES_DELIVERY','SALES_AR_INVOICE','SALES_AR_DP_INVOICE','SALES_AR_CREDIT_MEMO','SALES_RETURN','RENTAL_QUOTE','RENTAL_ORDER','RENTAL_DELIVERY','RENTAL_RETURN','RENTAL_INVOICE') NOT NULL,
  `fields_selected` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_user_line_item_configuration` */

insert  into `tbl_user_line_item_configuration`(`id`,`user_id`,`module`,`fields_selected`,`created_on`,`created_by`,`updated_on`,`updated_by`,`posting_status`,`sap_id`,`sap_error`,`referer_created`,`referer_updated`,`is_deleted`) values 
(10,1,'','1,2,3,4,5,6','2021-02-12 07:03:24',1,'2021-02-12 07:03:24',NULL,0,'','','WEB','SAP',0),
(11,1,'GRPO','0,1,2,4,6,5,7,8,9,10,11,13,12,14,15','2021-02-12 07:07:07',1,'2023-09-06 02:54:59',1,0,'','','WEB','WEB',0),
(12,1,'SALES_QUOTE','0,1,3,4,5,7,6,15,16,14,8,13,9,10,11,12,17','2021-02-12 21:09:00',1,'2023-07-29 05:17:14',1,0,'','','WEB','WEB',0),
(13,1,'SALES_ORDER','0,1,3,4,5,6,7,13,14,15,16,17,8,9,10,11,12,18,19,20,21,22,23','2021-02-12 21:18:11',1,'2023-12-09 05:07:41',1,0,'','','WEB','WEB',0),
(14,1,'SALES_DELIVERY','0,1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18','2021-02-12 21:18:51',1,'2023-08-26 12:43:47',1,0,'','','WEB','WEB',0),
(15,1,'SALES_RETURN','0,4,3,6,7,8,9,10,11,12,13,14,15,16,17,18,5','2021-02-12 21:19:27',1,'2021-09-27 11:08:57',1,0,'','','WEB','WEB',0),
(16,1,'SALES_AR_DP_INVOICE','0,3,4,5,7,8,9,10,11,12,13,14,15,16,17,18,6','2021-02-12 21:19:50',1,'2021-09-27 11:10:04',1,0,'','','WEB','WEB',0),
(17,1,'SALES_AR_INVOICE','0,3,4,5,6,7,8,9,11,12,13,14,15,16,17,18,1,10','2021-02-12 21:20:37',1,'2021-09-27 11:10:55',1,0,'','','WEB','WEB',0),
(18,1,'SALES_AR_CREDIT_MEMO','0,2,3,4,5,7,8,9,10,11,12,13,14,15,16,17,18,6','2021-02-12 21:22:12',1,'2021-09-27 11:11:37',1,0,'','','WEB','WEB',0),
(19,1,'PURCHASE_REQUEST','0,1,2,4,3,5,6,7,8,9,10,11,12,14,15,13,16','2021-02-12 21:22:41',1,'2022-01-30 08:57:02',1,0,'','','WEB','WEB',0),
(20,1,'PURCHASE_ORDER','0,2,4,5,6,7,8,9,10,12,13,14,15,11,16,1','2021-02-12 21:42:17',1,'2022-05-26 07:24:43',1,0,'','','WEB','WEB',0),
(21,1,'INVENTORY_TRANSFER_REQUEST','0,2,4,3,5,6,7,8,9,1','2021-02-12 21:55:40',1,'2023-09-14 10:03:35',1,0,'','','WEB','WEB',0),
(22,1,'INVENTORY_TRANSFER','0,2,3,4,5,6,7,8,9','2021-02-12 21:56:11',1,'2021-02-12 21:56:11',NULL,0,'','','WEB','SAP',0),
(23,1,'','1,0,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18','2021-05-18 05:20:56',1,'2021-05-18 05:20:56',NULL,0,'','','WEB','SAP',0),
(24,1,'RENTAL_QUOTE','0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,19,18','2021-05-21 03:19:21',1,'2021-09-27 11:16:16',1,0,'','','WEB','WEB',0),
(25,1,'RENTAL_INVOICE','0,1,2,5,6,4,3,8,9,10,11,12,13,14,15,17,18,19,16,7','2021-06-01 08:49:40',1,'2021-09-27 11:21:57',1,0,'','','WEB','WEB',0),
(26,1,'RENTAL_ORDER','1,0,2,14,13,6,7,4,5,8,9,10,11,12,16,17,18,19,20','2021-06-17 10:28:14',1,'2023-12-02 10:51:38',1,0,'','','WEB','WEB',0),
(27,1,'RENTAL_RETURN','0,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19','2021-06-17 23:30:52',1,'2021-09-27 11:20:26',1,0,'','','WEB','WEB',0),
(28,1,'RENTAL_DELIVERY','0,1,2,4,12,5,6,7,8,9,10,11,13,14,15,16,17,18,19','2021-06-17 23:31:14',1,'2021-09-27 11:18:47',1,0,'','','WEB','WEB',0),
(29,244,'SALES_ORDER','0,1,3,4,5,6,7,13,14,15,16,17','2023-08-01 07:22:17',244,'2023-08-01 07:22:27',244,0,'','','WEB','WEB',0),
(30,246,'SALES_QUOTE','0,1,3,4,5,6,7,13,14,15,16,17','2023-08-16 10:02:48',246,'2023-08-16 10:02:48',NULL,0,'','','WEB','SAP',0),
(31,249,'SALES_QUOTE','0,1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18','2023-08-30 06:44:59',249,'2023-08-30 06:44:59',NULL,0,'','','WEB','SAP',0),
(32,242,'SALES_ORDER','0,1,3,4,5,6,7,13,14,15,16,17,19,20,21,22','2023-12-09 05:36:23',242,'2023-12-09 05:36:23',NULL,0,'','','WEB','SAP',0),
(33,257,'SALES_ORDER','0,1,3,4,5,6,7,13,14,15,16,17,19,20,21,22','2024-01-02 13:57:47',257,'2024-01-02 13:57:47',NULL,0,'','','WEB','SAP',0),
(34,273,'SALES_ORDER','0,1,3,4,5,6,7,13,14,15,16,17,19,20,21,22','2024-01-19 09:51:48',273,'2024-01-19 09:51:48',NULL,0,'','','WEB','SAP',0),
(35,272,'SALES_ORDER','0,1,3,4,5,6,7,13,14,15,16,17,23','2024-02-02 05:49:23',272,'2024-02-02 05:49:23',NULL,0,'','','WEB','SAP',0),
(36,274,'SALES_ORDER','0,1,3,4,5,6,7,13,14,15,16,17,19,20,21,22','2024-02-12 01:16:14',274,'2024-02-12 01:16:35',274,0,'','','WEB','WEB',0),
(37,275,'SALES_ORDER','0,1,3,4,5,6,7,13,14,15,16,17,19,21,20,22,23','2024-02-12 01:24:00',275,'2024-02-12 01:24:00',NULL,0,'','','WEB','SAP',0);

/*Table structure for table `tbl_warehouse` */

DROP TABLE IF EXISTS `tbl_warehouse`;

CREATE TABLE `tbl_warehouse` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_code` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `warehouse_name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `location_id` int unsigned NOT NULL,
  `branch_id` int unsigned NOT NULL,
  `bin_id` text NOT NULL,
  `address_1` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `address_2` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `default_warehouse` tinyint NOT NULL DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `posting_status` tinyint NOT NULL DEFAULT '0' COMMENT '0 -not updated to sap, 1 - updated to sap',
  `sap_id` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `sap_error` text NOT NULL,
  `referer_created` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `referer_updated` enum('WEB','MOBILE_APP','SAP') DEFAULT 'SAP',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_warehouse` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*
SQLyog Community v13.0.1 (64 bit)
MySQL - 8.0.40 : Database - xfactr_organization
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`xfactr_organization` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `xfactr_organization`;

/*Table structure for table `tbl_company_info` */

DROP TABLE IF EXISTS `tbl_company_info`;

CREATE TABLE `tbl_company_info` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int unsigned NOT NULL,
  `company_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `company_location` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `db_hostname` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `db_username` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `db_password` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `database_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `module_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `username` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `password` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_company_info` */

insert  into `tbl_company_info`(`id`,`organization_id`,`company_name`,`company_location`,`db_hostname`,`db_username`,`db_password`,`database_name`,`module_name`,`username`,`password`,`status`,`created_on`,`created_by`,`updated_on`,`updated_by`,`is_deleted`) values 
(3,1,'Emerging Alliance','Chennai','localhost:3306','root','Ea@12345','xfactr','COMPANY','rcheenu@gmail.com','123456',2,'2021-10-11 16:43:53',1,'0000-00-00 00:00:00',1,0),
(5,1,'Nehmeh','Quatar','localhost:3306','root','Ea@12345','xfactr-nehmeh','COMPANY','rcheenu@gmail.com','123456',2,'2021-10-11 16:43:53',1,'2025-04-16 00:00:00',1,0);

/*Table structure for table `tbl_organization_details` */

DROP TABLE IF EXISTS `tbl_organization_details`;

CREATE TABLE `tbl_organization_details` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `organization_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `sub_domain_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `username` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `password` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `module_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `package_start_date` date NOT NULL DEFAULT '0000-00-00',
  `package_end_date` date NOT NULL DEFAULT '0000-00-00',
  `db_hostname` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `db_username` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `db_password` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `smtp_host` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '' COMMENT 'smtp.mandrillapp.com or smtp.amazon.com',
  `smtp_protocol` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '' COMMENT 'smtp',
  `smtp_port` smallint NOT NULL DEFAULT '0' COMMENT '465 or any other port',
  `smtp_secure` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '' COMMENT 'ssl or tls',
  `smtp_username` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `smtp_password` varchar(1024) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `mail_provider` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
  `expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int unsigned NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int unsigned DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_organization_details` */

insert  into `tbl_organization_details`(`id`,`organization_name`,`sub_domain_name`,`username`,`password`,`module_name`,`package_start_date`,`package_end_date`,`db_hostname`,`db_username`,`db_password`,`smtp_host`,`smtp_protocol`,`smtp_port`,`smtp_secure`,`smtp_username`,`smtp_password`,`mail_provider`,`expiry_date`,`created_on`,`created_by`,`updated_on`,`updated_by`,`is_deleted`) values 
(1,'sap application','sap','superadmin@gmail.com','superadmin123','ORG_MODULE','2019-05-01','2023-10-10','81.4.127.192','','','','',0,'','','','','2029-12-31','2021-10-05 16:44:06',1,'0000-00-00 00:00:00',NULL,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

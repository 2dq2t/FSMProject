-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE="TRADITIONAL,ALLOW_INVALID_DATES";
-- -------------------------------------------

-- -------------------------------------------
-- START BACKUP
-- -------------------------------------------
-- -------------------------------------------
-- Schema fsmdb
-- -------------------------------------------
DROP SCHEMA IF EXISTS `fsmdb` ;

-- -------------------------------------------
-- Schema fsmdb
-- -------------------------------------------
CREATE SCHEMA IF NOT EXISTS `fsmdb` DEFAULT CHARACTER SET utf8 ;
USE `fsmdb` ;

-- -------------------------------------------
-- TABLE `address`
-- -------------------------------------------
DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `detail` varchar(255) NOT NULL,
  `district_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_address_district1_idx` (`district_id`),
  CONSTRAINT `fk_address_district1` FOREIGN KEY (`district_id`) REFERENCES `district` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `auth_assignment`
-- -------------------------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `fk_auth_assignment_employee1_idx` (`user_id`),
  CONSTRAINT `fk_auth_assignment_auth_item1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_auth_assignment_employee1` FOREIGN KEY (`user_id`) REFERENCES `employee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `auth_item`
-- -------------------------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `data` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `fk_auth_item_auth_rule1_idx` (`rule_name`),
  CONSTRAINT `fk_auth_item_auth_rule1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `auth_item_child`
-- -------------------------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `fk_auth_item_has_auth_item_auth_item2_idx` (`child`),
  KEY `fk_auth_item_has_auth_item_auth_item1_idx` (`parent`),
  CONSTRAINT `fk_auth_item_has_auth_item_auth_item1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_auth_item_has_auth_item_auth_item2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `auth_rule`
-- -------------------------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `category`
-- -------------------------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `city`
-- -------------------------------------------
DROP TABLE IF EXISTS `city`;
CREATE TABLE IF NOT EXISTS `city` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `customer`
-- -------------------------------------------
DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` char(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `dob` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT 'Other',
  `auth_key` varbinary(255) DEFAULT NULL,
  `password_reset_token` char(255) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `guest_id` int(10) unsigned NOT NULL,
  `address_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `fk_customer_guest1_idx` (`guest_id`),
  KEY `fk_customer_address1_idx` (`address_id`),
  CONSTRAINT `fk_customer_address1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_customer_guest1` FOREIGN KEY (`guest_id`) REFERENCES `guest` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `district`
-- -------------------------------------------
DROP TABLE IF EXISTS `district`;
CREATE TABLE IF NOT EXISTS `district` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `city_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_district_city1_idx` (`city_id`),
  CONSTRAINT `fk_district_city1` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `employee`
-- -------------------------------------------
DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `password` char(255) NOT NULL,
  `dob` int(11) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `phone_number` char(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `note` text,
  `image` varchar(255) NOT NULL,
  `auth_key` varbinary(255) DEFAULT NULL,
  `password_reset_token` char(255) DEFAULT NULL,
  `start_date` int(11) unsigned NOT NULL,
  `status` tinyint(4) DEFAULT '1',
  `address_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_employee_address1_idx` (`address_id`),
  CONSTRAINT `fk_employee_address1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `faq`
-- -------------------------------------------
DROP TABLE IF EXISTS `faq`;
CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `guest`
-- -------------------------------------------
DROP TABLE IF EXISTS `guest`;
CREATE TABLE IF NOT EXISTS `guest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` char(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `image`
-- -------------------------------------------
DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `resize_path` varchar(255) NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_image_product1_idx` (`product_id`),
  CONSTRAINT `fk_image_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `offer`
-- -------------------------------------------
DROP TABLE IF EXISTS `offer`;
CREATE TABLE IF NOT EXISTS `offer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `discount` int(11) unsigned NOT NULL,
  `description` varchar(255) NOT NULL,
  `start_date` int(11) unsigned NOT NULL,
  `end_date` int(11) unsigned NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `product_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_offer_product1_idx` (`product_id`),
  CONSTRAINT `fk_offer_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `order`
-- -------------------------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_date` int(11) unsigned NOT NULL,
  `receiving_date` int(11) unsigned NOT NULL,
  `shipping_fee` float unsigned NOT NULL,
  `tax_amount` int(10) unsigned NOT NULL,
  `net_amount` float unsigned NOT NULL,
  `description` varchar(255) NOT NULL,
  `guest_id` int(10) unsigned NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `order_address_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_guest1_idx` (`guest_id`),
  KEY `fk_order_order_status1_idx` (`order_status_id`),
  KEY `fk_order_order_address1_idx` (`order_address_id`),
  CONSTRAINT `fk_order_guest1` FOREIGN KEY (`guest_id`) REFERENCES `guest` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_order_address1` FOREIGN KEY (`order_address_id`) REFERENCES `order_address` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_order_status1` FOREIGN KEY (`order_status_id`) REFERENCES `order_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `order_address`
-- -------------------------------------------
DROP TABLE IF EXISTS `order_address`;
CREATE TABLE IF NOT EXISTS `order_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `detail` varchar(255) NOT NULL,
  `district_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_address_district1_idx` (`district_id`),
  CONSTRAINT `fk_order_address_district1` FOREIGN KEY (`district_id`) REFERENCES `district` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `order_details`
-- -------------------------------------------
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `product_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `sell_price` float unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `discount` float unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`,`product_id`),
  KEY `fk_order_detail_product1_idx` (`product_id`),
  KEY `fk_order_detail_order1_idx` (`order_id`),
  CONSTRAINT `fk_order_detail_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_detail_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `order_status`
-- -------------------------------------------
DROP TABLE IF EXISTS `order_status`;
CREATE TABLE IF NOT EXISTS `order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comment` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `product`
-- -------------------------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `barcode` int(50) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` float unsigned NOT NULL,
  `description` text NOT NULL,
  `intro` text NOT NULL,
  `quantity_in_stock` int(10) unsigned NOT NULL,
  `sold` int(10) unsigned NOT NULL DEFAULT '0',
  `tax` int(10) unsigned NOT NULL DEFAULT '0',
  `create_date` int(11) unsigned NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `category_id` int(11) NOT NULL,
  `unit_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `fk_product_category1_idx` (`category_id`),
  KEY `fk_product_unit1_idx` (`unit_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`),
  CONSTRAINT `fk_product_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_unit1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `product_rating`
-- -------------------------------------------
DROP TABLE IF EXISTS `product_rating`;
CREATE TABLE IF NOT EXISTS `product_rating` (
  `product_id` int(10) unsigned NOT NULL,
  `rating_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  KEY `fk_product_has_rating_rating1_idx` (`rating_id`),
  KEY `fk_product_has_rating_product1_idx` (`product_id`),
  KEY `fk_product_rating_customer1_idx` (`customer_id`),
  CONSTRAINT `fk_product_rating` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_rating_customer1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rating_product` FOREIGN KEY (`rating_id`) REFERENCES `rating` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `product_season`
-- -------------------------------------------
DROP TABLE IF EXISTS `product_season`;
CREATE TABLE IF NOT EXISTS `product_season` (
  `product_id` int(10) unsigned NOT NULL,
  `season_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`season_id`),
  KEY `fk_product_has_season_season1_idx` (`season_id`),
  KEY `fk_product_has_season_product1_idx` (`product_id`),
  CONSTRAINT `fk_product_has_season_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_has_season_season1` FOREIGN KEY (`season_id`) REFERENCES `season` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `product_tag`
-- -------------------------------------------
DROP TABLE IF EXISTS `product_tag`;
CREATE TABLE IF NOT EXISTS `product_tag` (
  `tag_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tag_id`,`product_id`),
  KEY `fk_tag_has_product_product1_idx` (`product_id`),
  KEY `fk_tag_has_product_tag1_idx` (`tag_id`),
  CONSTRAINT `fk_tag_has_product_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tag_has_product_tag1` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `rating`
-- -------------------------------------------
DROP TABLE IF EXISTS `rating`;
CREATE TABLE IF NOT EXISTS `rating` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rating` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `season`
-- -------------------------------------------
DROP TABLE IF EXISTS `season`;
CREATE TABLE IF NOT EXISTS `season` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `slide_show`
-- -------------------------------------------
DROP TABLE IF EXISTS `slide_show`;
CREATE TABLE IF NOT EXISTS `slide_show` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `product_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_slide_show_product1_idx` (`product_id`),
  CONSTRAINT `fk_slide_show_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `tag`
-- -------------------------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `unit`
-- -------------------------------------------
DROP TABLE IF EXISTS `unit`;
CREATE TABLE IF NOT EXISTS `unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `voucher`
-- -------------------------------------------
DROP TABLE IF EXISTS `voucher`;
CREATE TABLE IF NOT EXISTS `voucher` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(45) NOT NULL,
  `discount` int(11) unsigned NOT NULL,
  `start_date` int(11) NOT NULL,
  `end_date` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `order_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_voucher_order1_idx` (`order_id`),
  CONSTRAINT `fk_voucher_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `wish_list`
-- -------------------------------------------
DROP TABLE IF EXISTS `wish_list`;
CREATE TABLE IF NOT EXISTS `wish_list` (
  `customer_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`customer_id`,`product_id`),
  KEY `fk_customer_has_product_product1_idx` (`product_id`),
  KEY `fk_customer_has_product_customer1_idx` (`customer_id`),
  CONSTRAINT `fk_customer_has_product_customer1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_customer_has_product_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE DATA address
-- -------------------------------------------
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(1,'291 Lạc Long Quân, Nghĩa Đô',1);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(2,'291 Lạc Long Quân, Nghĩa Đô',1);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(3,'Xóm Mắm',850);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(4,'Thôn Cấn Hữu',865);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(5,'Xóm Cầu',368);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(6,'Thôn Phú Mỹ',382);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(7,'Thôn Tiên Tửu',260);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(8,'Thôn ĐÌnh',248);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(9,'Thôn Me',572);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(10,'Làng Nho Gia',363);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(11,'Số nhà 50b',362);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(12,'Thôn Nghệ',384);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(13,'Thôn Kim',248);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(14,'Cho Sat',1);
INSERT INTO `address` (`id`,`detail`,`district_id`) VALUES
(15,'Royal city',122);



-- -------------------------------------------
-- TABLE DATA auth_assignment
-- -------------------------------------------
INSERT INTO `auth_assignment` (`item_name`,`user_id`,`created_at`) VALUES
('admin',8,1439672684);
INSERT INTO `auth_assignment` (`item_name`,`user_id`,`created_at`) VALUES
('manage',8,1439672684);
INSERT INTO `auth_assignment` (`item_name`,`user_id`,`created_at`) VALUES
('sale',9,1439693565);



-- -------------------------------------------
-- TABLE DATA auth_item
-- -------------------------------------------
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/assignment/*',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/assignment/index',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/assignment/update',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/backup/*',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/backup/create',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/backup/delete',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/backup/download',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/backup/index',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/backup/restore',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/backup/upload',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/category/*',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/category/create',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/category/delete',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/category/index',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/category/update',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/category/view',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/customer/*',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/customer/create',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/customer/delete',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/customer/getdistrict',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/customer/index',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/customer/update',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/customer/view',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/employee/*',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/employee/create',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/employee/delete',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/employee/getdistrict',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/employee/index',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/employee/update',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/faq/*',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/faq/create',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/faq/delete',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/faq/index',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/faq/update',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/faq/view',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/guest/*',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/guest/create',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/guest/index',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/guest/update',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/guest/view',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/i18n/*',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/i18n/index',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/i18n/update',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/log/*',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/log/delete',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/log/download',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/log/index',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/log/view',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/offer/*',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/offer/create',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/offer/delete',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/offer/index',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/offer/update',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/offer/view',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/order/*',2,NULL,NULL,NULL,1439658000,1439693352);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/order/cancel',2,NULL,NULL,NULL,1439658000,1439693352);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/order/confirm',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/order/create',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/order/delivered',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/order/getdistrict',2,NULL,NULL,NULL,1439658000,1439693352);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/order/index',2,NULL,NULL,NULL,1439658000,1439693164);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/order/invoice-print',2,NULL,NULL,NULL,1439658000,1439693352);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/order/update-description',2,NULL,NULL,NULL,1439658000,1439693352);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/orderstatus/*',2,NULL,NULL,NULL,1439658000,1439693353);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/orderstatus/create',2,NULL,NULL,NULL,1439658000,1439693352);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/orderstatus/delete',2,NULL,NULL,NULL,1439658000,1439693353);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/orderstatus/index',2,NULL,NULL,NULL,1439658000,1439693352);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/orderstatus/update',2,NULL,NULL,NULL,1439658000,1439693353);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('/orderstatus/view',2,NULL,NULL,NULL,1439658000,1439693352);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('admin',1,NULL,NULL,NULL,1439658000,1439670618);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('manage',1,NULL,NULL,NULL,1439658000,1439670629);
INSERT INTO `auth_item` (`name`,`type`,`description`,`data`,`rule_name`,`created_at`,`updated_at`) VALUES
('sale',1,'Manage for sale order',NULL,NULL,1439658000,1439693421);



-- -------------------------------------------
-- TABLE DATA auth_item_child
-- -------------------------------------------
INSERT INTO `auth_item_child` (`parent`,`child`) VALUES
('sale','/category/index');
INSERT INTO `auth_item_child` (`parent`,`child`) VALUES
('sale','/category/view');
INSERT INTO `auth_item_child` (`parent`,`child`) VALUES
('sale','/offer/*');
INSERT INTO `auth_item_child` (`parent`,`child`) VALUES
('sale','/order/*');
INSERT INTO `auth_item_child` (`parent`,`child`) VALUES
('sale','/orderstatus/index');
INSERT INTO `auth_item_child` (`parent`,`child`) VALUES
('sale','/orderstatus/update');
INSERT INTO `auth_item_child` (`parent`,`child`) VALUES
('sale','/orderstatus/view');



-- -------------------------------------------
-- TABLE DATA category
-- -------------------------------------------
INSERT INTO `category` (`id`,`name`,`active`) VALUES
(5,'Rau ăn thân, lá',1);
INSERT INTO `category` (`id`,`name`,`active`) VALUES
(6,'Rau ăn củ, quả',1);
INSERT INTO `category` (`id`,`name`,`active`) VALUES
(7,'Rau ăn hoa',1);
INSERT INTO `category` (`id`,`name`,`active`) VALUES
(8,'Hoa quả tươi',1);



-- -------------------------------------------
-- TABLE DATA city
-- -------------------------------------------
INSERT INTO `city` (`id`,`name`) VALUES
(2,'TP. Hà Nội');
INSERT INTO `city` (`id`,`name`) VALUES
(3,'TP. HCM');
INSERT INTO `city` (`id`,`name`) VALUES
(10,'Yên Bái');
INSERT INTO `city` (`id`,`name`) VALUES
(11,'Vĩnh Phúc');
INSERT INTO `city` (`id`,`name`) VALUES
(12,'Vĩnh Long');
INSERT INTO `city` (`id`,`name`) VALUES
(13,'Tuyên Quang');
INSERT INTO `city` (`id`,`name`) VALUES
(14,'Trà Vinh');
INSERT INTO `city` (`id`,`name`) VALUES
(15,'Tiền Giang');
INSERT INTO `city` (`id`,`name`) VALUES
(16,'Thừa Thiên Huế');
INSERT INTO `city` (`id`,`name`) VALUES
(17,'Thanh Hóa');
INSERT INTO `city` (`id`,`name`) VALUES
(18,'Thái Nguyên');
INSERT INTO `city` (`id`,`name`) VALUES
(19,'Thái Bình');
INSERT INTO `city` (`id`,`name`) VALUES
(21,'Tây Ninh');
INSERT INTO `city` (`id`,`name`) VALUES
(22,'Sơn La');
INSERT INTO `city` (`id`,`name`) VALUES
(23,'Sóc Trăng');
INSERT INTO `city` (`id`,`name`) VALUES
(24,'Quảng Trị');
INSERT INTO `city` (`id`,`name`) VALUES
(25,'Quảng Ninh');
INSERT INTO `city` (`id`,`name`) VALUES
(26,'Quảng Ngãi');
INSERT INTO `city` (`id`,`name`) VALUES
(27,'Quảng Nam');
INSERT INTO `city` (`id`,`name`) VALUES
(28,'Quảng Bình');
INSERT INTO `city` (`id`,`name`) VALUES
(29,'Phú Yên');
INSERT INTO `city` (`id`,`name`) VALUES
(30,'Phú Thọ');
INSERT INTO `city` (`id`,`name`) VALUES
(31,'Ninh Thuận');
INSERT INTO `city` (`id`,`name`) VALUES
(32,'Ninh Bình');
INSERT INTO `city` (`id`,`name`) VALUES
(33,'Nghệ An');
INSERT INTO `city` (`id`,`name`) VALUES
(34,'Nam Định');
INSERT INTO `city` (`id`,`name`) VALUES
(35,'Long An');
INSERT INTO `city` (`id`,`name`) VALUES
(36,'Lào Cai');
INSERT INTO `city` (`id`,`name`) VALUES
(37,'Lạng Sơn');
INSERT INTO `city` (`id`,`name`) VALUES
(38,'Lâm Đồng');
INSERT INTO `city` (`id`,`name`) VALUES
(39,'Lai Châu');
INSERT INTO `city` (`id`,`name`) VALUES
(40,'Kon Tum');
INSERT INTO `city` (`id`,`name`) VALUES
(41,'Kiên Giang');
INSERT INTO `city` (`id`,`name`) VALUES
(42,'Khánh Hòa');
INSERT INTO `city` (`id`,`name`) VALUES
(43,'Hưng Yên');
INSERT INTO `city` (`id`,`name`) VALUES
(44,'Hòa Bình');
INSERT INTO `city` (`id`,`name`) VALUES
(45,'Hậu Giang');
INSERT INTO `city` (`id`,`name`) VALUES
(46,'Hải Dương');
INSERT INTO `city` (`id`,`name`) VALUES
(47,'Hà Tĩnh');
INSERT INTO `city` (`id`,`name`) VALUES
(49,'Hà Nam ');
INSERT INTO `city` (`id`,`name`) VALUES
(50,'Hà Giang');
INSERT INTO `city` (`id`,`name`) VALUES
(51,'Gia Lai');
INSERT INTO `city` (`id`,`name`) VALUES
(52,'Đồng Tháp');
INSERT INTO `city` (`id`,`name`) VALUES
(53,'Đồng Nai');
INSERT INTO `city` (`id`,`name`) VALUES
(54,'Điện Biên');
INSERT INTO `city` (`id`,`name`) VALUES
(55,'Đắk Nông');
INSERT INTO `city` (`id`,`name`) VALUES
(56,'Đắk Lắk');
INSERT INTO `city` (`id`,`name`) VALUES
(57,'Cao Bằng');
INSERT INTO `city` (`id`,`name`) VALUES
(58,'Cà Mau');
INSERT INTO `city` (`id`,`name`) VALUES
(59,'Bình Thuận');
INSERT INTO `city` (`id`,`name`) VALUES
(60,'Bình Phước');
INSERT INTO `city` (`id`,`name`) VALUES
(61,'Bình Dương');
INSERT INTO `city` (`id`,`name`) VALUES
(62,'Bình Định');
INSERT INTO `city` (`id`,`name`) VALUES
(63,'Bến Tre');
INSERT INTO `city` (`id`,`name`) VALUES
(64,'Bắc Ninh');
INSERT INTO `city` (`id`,`name`) VALUES
(65,'Bạc Liêu');
INSERT INTO `city` (`id`,`name`) VALUES
(66,'Bắc Kạn');
INSERT INTO `city` (`id`,`name`) VALUES
(67,'Bắc Giang');
INSERT INTO `city` (`id`,`name`) VALUES
(68,'Bà Rịa - Vũng Tàu');
INSERT INTO `city` (`id`,`name`) VALUES
(69,'An Giang');
INSERT INTO `city` (`id`,`name`) VALUES
(70,'Hải Phòng');
INSERT INTO `city` (`id`,`name`) VALUES
(71,'Đà Nẵng');
INSERT INTO `city` (`id`,`name`) VALUES
(72,'Cần Thơ');



-- -------------------------------------------
-- TABLE DATA customer
-- -------------------------------------------
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(1,'luongnv93','$2y$13$Fj2M4ShWEEYtDKt2LXGdCOGb4XHHLOUSy7lsLesHlrZjBKE3FTvEK','Jx62n1z96fyusFRIAC5LNUd8L-3UtnYU.jpg',740941200,'Male',NULL,NULL,1439571600,1439627513,1,1,3);
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(2,'thaoch1993','$2y$13$aFsoIV2gciHZ3PgzvjvMqOZZFEBx4at3lpTLYtPCxpqb4ApU28cLa','4uCYUHudu505pqOWySUQDsONV2s4qgUO.jpg',737398800,'Male',NULL,NULL,1439571600,1439627623,1,2,4);
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(3,'tuantn93','$2y$13$oxe0GUC.SB76ow/UGlHCYu.EhJJRm9AgybdSucyVGp3AEgFo06A0C','Z3SN6GuUDESmv12vre5ISmPy_nDXkK05.jpg',739904400,'Male',NULL,NULL,1439571600,1439627706,1,3,5);
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(4,'hoangba93','$2y$13$y9qv6Zp4qcTtAdZvDidYa.odegKTClCTMXdsbFGj6PWm99uTKaR.W','R1QFQaig6Mm9QxvRKEy6z0Fh-AdnJcBr.jpg',742755600,'Male',NULL,NULL,1439571600,1439627797,1,4,6);
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(5,'thuyvt93','$2y$13$Fop/kgWcBBFl0xrSiFwRAuUd5z0k03RkaGv.M1eWyWPLbrgjBMEmq','Pna6JpDyvXTHJCYQKykaJuM_Hmompm-l.jpg',739818000,'Female',NULL,NULL,1439571600,1439627893,1,5,7);
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(6,'nhungth1993','$2y$13$TE0moqjRO/jJwRASRW.FpObZyac1RXdgUURYe/9pwoj4qehReFPoi','J6o-0dUmiR9f2W1pRC-rU8KFFtfmcTt0.jpg',737226000,'Female',NULL,NULL,1439571600,1439627995,1,6,8);
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(7,'tiendien123','$2y$13$rjdFQpGNJwuAaLb/K2s45elnZQ6PHLdEaPk5XGNWjjYwcnv3X6JKK','Dkqlb-N84mw6cfIVNCMIwtnKmnidCrSX.jpg',768762000,'Female',NULL,NULL,1439571600,1439628095,1,7,9);
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(8,'tudeptrai93','$2y$13$2n7hpz/HXpF9F7C1rrx.9uyO3.xzMd.zDVyAvxlr4kfO12M0mvS5G','dZvXzOl5vGJE8Q3Fb-AB1Ch8N8l8mctI.jpg',746384400,'Male',NULL,NULL,1439571600,1439627569,1,8,2);
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(9,'thinhvv','$2y$13$9PA.c5jFKEFT10KVEkywluJoxY35rvyT15uasZVBn8tagwAyEiBH.','lPsysvhaYVmJ7EzWV-AQMxMe3eTuY_Ar.jpg',666291600,'Male',NULL,NULL,1439571600,1439628204,1,9,10);
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(10,'dungdt','$2y$13$ypEaX2NJV0s4dSMBzD4b.O9Sb24Wv7/y9HJc/7uqzvcNfWrNXglLy','K90SLTLMtb4kh6z3uZlPzVP6eDrd6vhv.jpg',616179600,'Male',NULL,NULL,1439571600,1439628404,1,10,12);
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(11,'quanlh','$2y$13$dAAUE4.mOEx73pUBD8ym9eIS1ERs.inAM9g5l/SGg5Cu8j9BpvjYu','drwNXPWgIA5gciwQ1xx2p-7T_yX_-1g_.jpg',757270800,'Male',NULL,NULL,1439571600,1439628284,1,11,11);
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(12,'dungnt','$2y$13$942TYeOg1ukAvkM7djcDw.vVsVf1my/ua3vKlKaAT45rT/DPIin4O','TVH4ZUp-bpH0nYL0hW_oAgYEn47VzOgj.jpg',639853200,'Male',NULL,NULL,1439571600,1439628513,1,12,13);
INSERT INTO `customer` (`id`,`username`,`password`,`avatar`,`dob`,`gender`,`auth_key`,`password_reset_token`,`created_at`,`updated_at`,`status`,`guest_id`,`address_id`) VALUES
(13,'anhbn@fpt.edu.vn','$2y$13$eNjtIpSXkTvx2AtKCn3QPuiJXCPuiPdKTeojQLnQBraVxD25BYVbq',NULL,NULL,'Other',NULL,NULL,1439658000,NULL,1,13,NULL);



-- -------------------------------------------
-- TABLE DATA district
-- -------------------------------------------
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(1,'Cầu Giấy',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(113,'Từ Liêm',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(114,'Thanh Trì',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(115,'Sóc Sơn',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(116,'Gia Lâm',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(117,'Đông Anh',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(118,'Long Biên',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(119,'Hoàng Mai',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(121,'Tây Hồ',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(122,'Thanh Xuân',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(123,'Hai Bà Trưng',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(124,'Đống Đa',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(125,'Ba Đình',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(126,'Hoàn Kiếm',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(127,'Quận 1',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(128,'Quận 2',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(129,'Quận 3',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(130,'Quận 4',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(131,'Quận 5',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(132,'Quận 6',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(133,'Quận 7',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(134,'Quận 8',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(135,'Quận 9',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(136,'Quận 10',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(137,'Quận 11',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(138,'Quận 12',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(139,'Quận Phú Nhuận',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(140,'Quận Bình Thạnh',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(141,'Quận Tân Bình',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(142,'Quận Tân Phú',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(143,'Quận Gò Vấp',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(144,'Quận Thủ Đức',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(145,'Quận Bình Tân',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(146,'Huyện Bình Chánh',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(147,'Huyện Củ Chi',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(149,'Huyện Nhà Bè',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(150,'Huyện Cần Giờ',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(151,'Bà Rịa',68);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(152,'Châu Đất',68);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(153,'Côn Đảo',68);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(154,'Long Đất',68);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(155,'Tân Thành',68);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(156,'Vũng Tàu',68);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(157,'Xuyên Mộc',68);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(158,'An Lão',62);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(159,'An Nhơn',62);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(160,'Hoài Ân',62);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(161,'Hoài Nhơn',62);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(162,'Phù Cát',62);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(163,'Phù Mỹ',62);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(164,'Qui Nhơn',62);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(165,'Tây Sơn',62);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(166,'Tuy Phước',62);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(167,'Vân Canh',62);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(168,'Vĩnh Thạnh',62);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(169,'Ba Bể',66);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(170,'Bắc Kạn',66);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(171,'Bạch Thông ',66);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(172,'Chợ Đồn',66);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(173,'Chợ Mới',66);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(174,'Na Rì',66);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(175,'Ngân Sơn',66);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(176,'Bảo Lạc',57);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(177,'Cao Bắng',57);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(178,'Hạ Lang',57);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(179,'Hà Quảng',57);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(180,'Hòa An',57);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(181,'Nguyên Bình',57);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(182,'Quảng Hòa',57);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(183,'Thạch An',57);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(184,'Thông Nông',57);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(185,'Trà Lĩnh',57);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(186,'Trùng Khánh',57);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(187,'An Khê',51);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(188,'Ayun Pa ',51);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(189,'Chư Păh',51);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(190,'Chư Prông  ',51);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(191,'Chư Sê ',51);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(192,'Đức Cơ  ',51);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(193,'KBang  ',51);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(194,'Krông Chro',51);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(195,'Krông Pa ',51);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(196,'La Grai  ',51);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(197,'Mang Yang ',51);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(198,'Pleiku',51);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(214,'Cẩm Xuyên',47);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(215,'Can Lộc',47);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(216,'Đức Thọ',47);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(217,'Hà Tĩnh',47);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(218,'Hồng Lĩnh',47);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(219,'Hương Khê',47);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(220,'Hương Sơn',47);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(221,'Kỳ Anh',47);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(222,'Nghi Xuân',47);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(223,'Thạch Hà',47);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(224,'Đà Bắc',44);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(225,'Hòa Bình',44);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(226,'Kim Bôi',44);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(227,'Kỳ Sơn',44);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(228,'Lạc Sơn',44);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(229,'Lạc Thủy',44);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(230,'Lương Sơn',44);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(231,'Mai Châu',44);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(232,'Tân Lạc',44);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(233,'Yên Thủy',44);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(234,'Bình Giang',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(235,'Cẩm Giàng',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(236,'Chí Linh',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(238,'Gia Lộc',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(239,'Hải Dương',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(241,'Kim Thành',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(242,'Nam Sách',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(243,'Ninh Giang',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(244,'Kinh Môn',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(245,'Ninh Giang',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(246,'Thanh Hà',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(247,'Thanh Miện',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(248,'Từ Kỳ',46);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(249,'An Hải',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(250,'An Lão',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(251,'Bạch Long Vỹ',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(253,'Đồ Sơn',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(254,'Hồng Bàng',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(255,'Kiến An',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(256,'Kiến Thụy',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(257,'Lê Chân',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(258,'Ngô Quyền',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(259,'Thủy Nguyên',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(260,'Tiên Lãng',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(261,'Vĩnh Bảo',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(262,'Ân Thi',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(263,'Hưng Yên',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(264,'Khoái Châu',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(265,'Tiên Lữ',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(266,'Văn Giang',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(267,'Văn Lâm',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(268,'Yên Mỹ',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(269,'Nha Trang',42);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(270,'Khánh Vĩnh',42);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(271,'Diên Khánh',42);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(272,'Ninh Hòa',42);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(273,'Khánh Sơn',42);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(274,'Cam Ranh',42);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(275,'Trường Sa',42);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(276,'Vạn Ninh',42);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(277,'An Biên',41);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(278,'An Minh',41);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(279,'Châu Thành',41);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(280,'Gò Quao',41);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(281,'Gồng Giềng',41);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(282,'Hà Tiên',41);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(283,'Hòn Đất',41);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(284,'Kiên Hải',41);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(285,'Phú Quốc',41);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(286,'Rạch Giá',41);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(287,'Tân Hiệp',41);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(288,'Vĩnh Thuận',41);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(290,'Đắk Glei',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(291,'Đắk Tô',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(292,'Kon Plông',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(293,'Kon Tum',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(294,'Ngọc Hồi',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(295,'Sa Thầy',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(296,'Điện Biên',39);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(297,'Điện Biên Đông',39);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(298,'Điện Biên Phủ',39);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(299,'Lai Châu',39);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(300,'Mường Lay',39);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(301,'Mường Tè',39);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(302,'Phong Thổ',39);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(303,'Sìn Hồ',39);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(304,'Tủa Chùa',39);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(305,'Tuần Giáo',39);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(306,'Bắc Hà',36);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(307,'Bảo Thắng',36);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(308,'Bảo Yên',36);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(309,'Bát Xát',36);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(310,'Cam Đường',36);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(311,'Lào Cai',36);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(312,'Mường Khương',36);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(313,'Sa Pa',36);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(314,'Than Uyên',36);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(315,'Văn Bàn',36);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(316,'Xi Ma Cai',36);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(317,'Bảo Lâm',38);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(318,'Bảo Lộc',38);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(319,'Cát Tiên',38);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(320,'Đà Lạt',38);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(321,'Đạ Tẻh',38);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(322,'Đạ Huoai',38);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(323,'Di Linh',38);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(324,'Đơn Dương',38);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(325,'Đức Trọng',38);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(326,'Lạc Dương',38);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(327,'Lâm Hà',38);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(328,'Bắc Sơn',37);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(329,'Bình Gia',37);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(330,'Cao Lăng',37);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(331,'Cao Lộc',37);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(332,'Đình Lập',37);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(333,'Hữu Lũng',37);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(334,'Lạng Sơn',37);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(336,'Lộc Bình',37);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(337,'Tràng Định',37);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(341,'Bến Lức',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(342,'Văn Lãng',37);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(343,'Văn Quang',37);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(344,'Cần Đước',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(345,'Cần Giuộc',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(346,'Châu Thành',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(347,'Đức Hòa',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(348,'Đức Huệ',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(349,'Mộc Hóa',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(350,'Tân An',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(351,'Tân Hưng',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(352,'Tân Thạnh',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(354,'Tân Trụ',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(355,'Thạnh Hóa',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(356,'Thủ Thừa',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(357,'Vĩnh Hưng',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(358,'Giao Thủy',34);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(360,'Hải Hậu',34);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(361,'Mỹ Lộc',34);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(362,'Nam Định',34);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(363,'Nam Trực',34);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(364,'Nghĩa Hưng',34);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(365,'Trực Ninh',34);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(366,'Vụ Bản',34);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(367,'Xuân Trường',34);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(368,'Ý Yên',34);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(369,'Anh Sơn',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(370,'Con Cuông',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(371,'Cửa Lò',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(372,'Diễn Châu',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(373,'Đô Lương',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(374,'Hưng Nguyên',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(375,'Kỳ Sơn',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(376,'Nam Đàn',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(377,'Nghi Lộc',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(378,'Nghĩa Đàn',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(379,'Quế Phong',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(380,'Quỳ Châu',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(381,'Quỳ Hợp',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(382,'Quỳnh Lưu',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(383,'Tân Kỳ',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(384,'Thanh Chương',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(385,'Tương Dương',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(386,'Vinh',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(387,'Yên Thành',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(388,'Đoan Hùng',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(389,'Hạ Hòa',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(390,'Lâm Thao',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(391,'Phù Ninh',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(392,'Phú Thọ',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(393,'Sông Thao',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(394,'Tam Nông',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(395,'Thanh Ba',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(396,'Thanh Sơn',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(397,'Thanh Thủy',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(398,'Việt Trì',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(399,'Yên Lập',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(400,'Đại Lộc',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(401,'Điện Bàn',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(402,'Duy Xuyên',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(403,'Hiên',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(404,'Hiệp Đức',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(405,'Hội An',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(406,'Nam Giang',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(407,'Núi Thành',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(408,'Phước Sơn',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(409,'Quế Sơn',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(410,'Tam Kỳ',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(411,'Thăng Bình',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(412,'Tiên Phước',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(413,'Trà My',27);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(414,'Cam Lộ',24);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(415,'Đa Krông',24);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(416,'Đông Hà',24);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(417,'Gio Linh',24);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(418,'Hải Lăng',24);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(419,'Hướng Hóa',24);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(420,'Quảng Trị',24);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(421,'Triệu Phong',24);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(422,'Vĩnh Linh',24);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(423,'A Lưới',16);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(424,'Huế',16);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(425,'Hương Thủy',16);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(426,'Hương Trà',16);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(427,'Nam Đông',16);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(428,'Phong Điền',16);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(429,'Phú Lộc',16);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(430,'Phú Vang',16);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(431,'Quảng Điền',16);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(432,'Đông Hưng',19);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(433,'Hưng Hà',19);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(434,'Kiến Xương',19);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(435,'Quỳnh Phụ',19);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(436,'Thái Bình',19);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(437,'Thái Thụy',19);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(438,'Tiền Hải',19);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(439,'Vũ Thư',19);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(440,'Càng Long',14);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(441,'Cầu Kè',14);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(442,'Cầu Ngang',14);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(443,'Châu Thành',14);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(444,'Duyên Hải',14);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(445,'Tiểu Cần',14);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(446,'Trà Cú',14);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(447,'Trà Vinh',14);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(448,'Bình Xuyên',11);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(449,'Lập Thạch',11);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(450,'Mê Linh',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(451,'Tam Dương',11);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(452,'Vĩnh Tường',11);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(453,'Vĩnh Yên',11);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(454,'Yên Lạc',11);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(455,'Buôn Đôn',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(456,'Buôn Ma Thuột',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(457,'Cư Jút',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(458,'Cư M\'gar',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(459,'Đắk Mil',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(460,'Đắk Nông',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(461,'Đắk R\'lấp',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(462,'Ea H\'leo',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(463,'Ea Kra',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(464,'Ea Súp',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(465,'Krông A Na',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(466,'Krông Bông',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(467,'Krông Búk',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(468,'Krông Năng',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(469,'Krông Nô',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(470,'Krông Pắc',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(471,'Lắk',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(472,'M\'Đrắt',56);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(473,'Bến Cát',61);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(474,'Dầu Tiếng',61);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(475,'Dĩ An',61);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(476,'Tân Uyên',61);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(477,'Thủ Dầu Một',61);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(478,'Thuận An',61);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(479,'Bạc Liêu',65);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(480,'Giá Rai',65);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(481,'Hồng Dân',65);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(482,'Vĩnh Lợi',65);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(483,'Bắc Ninh',64);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(484,'Gia Bình',64);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(485,'Lương Tài',64);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(486,'Quế Võ',64);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(487,'Thuận Thành',64);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(488,'Tiên Du',64);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(489,'Từ Sơn',64);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(490,'Yên Phong',64);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(491,'Cà Mau',58);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(492,'Cái Nước',58);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(493,'Đầm Dơi',58);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(494,'Ngọc Hiển',58);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(495,'Thới Bình',58);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(496,'Trần Văn Thời',58);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(497,'U Minh',58);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(498,'Bắc Mê',50);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(499,'Bắc Quang',50);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(500,'Đồng Văn',50);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(501,'Hà Giang',50);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(502,'Hoàng Su Phì',50);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(503,'Mèo Vạt',50);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(504,'Quản Bạ',50);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(505,'Vị Xuyên',50);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(506,'Xín Mần',50);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(507,'Yên Minh',50);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(568,'Hoa Lư',32);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(569,'Kim Sơn',32);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(571,'Nho Quan',32);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(572,'Ninh Bình',32);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(573,'Tam Điệp',32);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(574,'Yên Khánh',32);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(575,'Yên Mô',32);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(576,'Đồng Xuân',29);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(577,'Sơn Hòa',29);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(578,'Sông Cầu',29);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(579,'Sông Hinh',29);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(580,'Tuy An',29);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(581,'Tuy Hòa',29);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(582,'Ba Tơ',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(583,'Bình Sơn',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(584,'Đức Phổ',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(585,'Lý Sơn',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(586,'Minh Long',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(587,'Mộ Đức',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(588,'Nghĩa Hành',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(589,'Quãng Ngãi',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(590,'Sơn Hà',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(591,'Sơn Tây',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(592,'Sơn Tịnh',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(593,'Trà Bồng',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(594,'Tư Nghĩa',26);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(595,'Kế Sách',23);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(596,'Long Phú',23);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(597,'Mỹ Tú',23);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(598,'Mỹ Xuyên',23);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(599,'Sóc Trăng',23);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(600,'Thanh Trị',23);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(601,'Vĩnh Châu',23);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(602,'Bến Cầu',21);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(603,'Châu Thành',21);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(604,'Dương Minh Châu',21);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(605,'Gò Dầu',21);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(606,'Hòa Thành',21);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(607,'Tân Biên',21);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(608,'Tân Châu',21);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(609,'Tây Ninh',21);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(610,'Trảng Bàng',21);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(611,'Đại Từ',18);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(612,'Định Hóa',18);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(613,'Đồng Hỷ',18);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(614,'Phổ Yên',18);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(615,'Phú Bình',18);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(616,'Phú Lương',18);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(617,'Sông Công',18);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(618,'Thái Nguyên',18);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(619,'Võ Nhai',18);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(620,'Chiêm Hóa',13);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(621,'Hàm Yên',13);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(622,'Na Hang',13);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(623,'Sơn Dương',13);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(624,'Tuyên Quang',13);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(625,'Yên Sơn',13);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(626,'Lục Yên',10);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(627,'Mù Căng Chải',10);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(628,'Trạm Tấu',10);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(629,'Trấn Yên',10);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(630,'Văn Chấn',10);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(631,'Văn Yên',10);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(632,'Yên Bái',10);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(633,'Yên Bình',10);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(634,'Biên Hòa',53);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(635,'Định Quán',53);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(636,'Long Khánh',53);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(637,'Long Thành',53);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(638,'Nhơn Trạch',53);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(639,'Tân Phú',53);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(640,'Thống Nhất',53);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(641,'Vĩnh Cừu',53);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(642,'Xuân Lộc',53);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(643,'An Phú',69);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(644,'Châu Đốc',69);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(645,'Châu Phú',69);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(646,'Châu Thành',69);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(647,'Chợ Mới',69);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(648,'Long Xuyên',69);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(649,'Phú Tân',69);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(650,'Tân Châu',69);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(651,'Thoại Sơn',69);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(652,'Tịnh Biên',69);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(653,'Tri Tôn',69);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(654,'Bắc Bình',59);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(655,'Đức Linh',59);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(656,'Hàm Tân',59);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(657,'Hàm Thuận Bắc',59);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(658,'Hàm Thuận Nam',59);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(659,'Phan Thiết',59);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(660,'Phú Quí',59);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(661,'Tánh Linh',59);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(662,'Tuy Phong',59);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(663,'Bắc Giang',67);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(664,'Hiệp Hòa',67);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(665,'Lạng Giang',67);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(666,'Lục Nam',67);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(667,'Lục Ngạn',67);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(668,'Sơn Động',67);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(669,'Tân Yên',67);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(670,'Việt Yên',67);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(671,'Yên Dũng',67);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(672,'Yên Thế',67);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(673,'Ba Tri',63);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(674,'Bến Tre',63);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(675,'Bình Đại',63);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(676,'Châu Thành',63);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(677,'Chợ Lách',63);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(678,'Giồng Trôm',63);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(679,'Mỏ Cày',63);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(680,'Thạnh Phú',63);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(681,'Cần Thơ',72);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(682,'Châu Thành',45);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(683,'Long Mỹ',45);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(684,'Ô Môn',72);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(685,'Phụng Hiệp',45);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(686,'Thốt Nốt',72);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(687,'Vị Thanh',45);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(688,'Vị Thủy',45);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(689,'Bình Lục',49);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(690,'Duy Tiên',49);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(691,'Kim Bảng',49);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(692,'Lý Nhân',49);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(693,'Phủ Lý',49);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(694,'Thanh Liêm',49);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(705,'Ân Thi',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(706,'Hưng Yên',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(707,'Khoái Châu',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(708,'Kim Động',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(709,'Mỹ Hào',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(710,'Phù Cừ',43);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(715,'Đắk Glei',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(716,'Đắk Hà',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(717,'Đắk Tô',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(718,'Kon Plông',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(719,'Kon Tum',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(720,'Ngọc Hồi',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(721,'Sa Thầy',40);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(743,'Ninh Hải',31);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(744,'Ninh Phước',31);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(745,'Ninh Sơn',31);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(746,'Phan Rang - Tháp Chàm',31);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(747,'Bố Trạch',28);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(748,'Đồng Hới',28);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(749,'Lệ Thủy',28);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(750,'Quảng Ninh',28);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(751,'Quảng Trạch',28);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(752,'Tuyên Hóa',28);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(753,'Ba Chế',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(754,'Bình Liêu',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(755,'Cẩm Phả',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(756,'Cô Tô',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(757,'Đông Triều',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(758,'Hạ Long',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(759,'Hoành Bồ',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(760,'Móng Cái',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(761,'Quảng Hà',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(762,'Tiên Yên',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(763,'Uông Bí',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(764,'Vân Đồn',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(765,'Yên Hưng',25);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(766,'Bắc Yên',22);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(767,'Mai Sơn',22);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(768,'Mộc Châu',22);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(769,'Muờng La',22);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(770,'Phù Yên',22);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(771,'Quỳnh Nhai',22);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(772,'Sơn La',22);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(773,'Sông Mã',22);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(774,'Thuận Châu',22);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(775,'Yên Châu',22);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(776,'Bá Thước',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(777,'Bỉm Sơn',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(778,'Cẩm Thủy',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(779,'Đông Sơn',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(780,'Hà Trung',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(781,'Hậu Lộc',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(782,'Hoằng Hóa',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(783,'Lang Chánh',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(784,'Mường Lát',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(785,'Nga Sơn',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(786,'Ngọc Lặc',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(787,'Như Thanh',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(788,'Như Xuân',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(789,'Nông Cống',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(790,'Quan Hóa',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(791,'Quan Sơn',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(792,'Quảng Xương',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(793,'Sầm Sơn',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(794,'Thạch Thành',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(795,'Thanh Hóa',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(796,'Thọ Xuân',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(797,'Thường Xuân',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(798,'Tĩnh Gia',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(799,'Thiệu Hóa',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(800,'Triệu Sơn',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(801,'Vĩnh Lộc',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(802,'Yên Định',17);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(803,'Cái Bè',15);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(804,'Cai Lậy',15);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(805,'Châu Thành',15);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(806,'Chợ Gạo',15);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(807,'Gò Công',15);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(808,'Gò Công Đông',15);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(809,'Gò Công Tây',15);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(810,'Mỹ Tho',15);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(811,'Tân Phước',15);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(812,'Bình Minh',12);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(813,'Long Hồ',12);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(814,'Mang Thít',12);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(815,'Tam Bình',12);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(816,'Trà Ôn',12);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(817,'Vĩnh Long',12);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(818,'Vũng Liêm',12);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(819,'Đảo Hòang Sa',71);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(820,'Hải Châu',71);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(821,'Hòa Vang',71);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(822,'Liên Chiểu',71);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(823,'Ngũ Hành Sơn',71);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(824,'Sơn Trà',71);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(825,'Thanh Khê',71);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(826,'Cao Lãnh',52);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(827,'Châu Thành',52);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(828,'Hồng Ngự',52);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(829,'Lai Vung',52);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(830,'Lấp Vò',52);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(831,'Tam Nông',52);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(832,'Tân Hồng',52);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(833,'Thanh Bình',52);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(834,'Tháp Mười',52);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(835,'Xa Đéc',52);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(836,'Bình Long',60);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(837,'Ninh Kiều',72);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(838,'Trảng Bom',53);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(839,'Phước Long',60);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(840,'Vân Điền',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(841,'Lái Thiêu',61);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(844,'Cẩm Lệ',71);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(848,'Cái Răng',72);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(849,'Liên Hưng',35);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(850,'Phúc Yên',11);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(851,'Bù Ðăng',60);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(852,'Chơn Thành',60);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(853,'Tam Đảo',11);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(854,'Cát Bà',70);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(855,'Bình Thủy',72);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(856,'Huyện Hóc Môn',3);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(857,'Ba Vì',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(858,'Chương Mỹ',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(859,'Đan Phượng',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(860,'Hà Đông',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(861,'Hoài Đức',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(862,'Mỹ Đức',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(863,'Phú Xuyên',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(864,'Phúc Thọ',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(865,'Quốc Oai',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(866,'Sơn Tây',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(867,'Thạch Thất',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(868,'Thanh Oai',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(869,'Thường Tín',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(871,'Ứng Hòa',2);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(872,'Gia Viễn',32);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(873,'Cao Phong',44);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(874,'Sốp Cộp',22);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(875,'Cẩm Khê',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(876,'Tân Sơn',30);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(877,'Đông Hòa',29);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(878,'Tây Hòa',29);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(879,'Phú Hòa',29);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(880,'Minh Hóa',28);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(881,'Vũ Quang',47);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(882,'Lộc Hà',47);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(883,'Thái Hòa',33);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(884,'Phước Long',65);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(885,'Đông Hải',65);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(886,'Hòa Bình',65);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(887,'Năm Căn',58);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(888,'Phú Tân',58);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(890,'Châu Thành A',45);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(891,'Ngã Bảy',45);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(892,'Phong Điền',72);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(893,'Cờ Đỏ',72);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(894,'Thới Lai',72);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(895,'Vĩnh Thạnh',72);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(896,'Phú Giáo',61);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(897,'La Gi',59);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(898,'Long Điền',68);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(899,'Đất Đỏ',68);
INSERT INTO `district` (`id`,`name`,`city_id`) VALUES
(900,'Dương Kinh',70);



-- -------------------------------------------
-- TABLE DATA employee
-- -------------------------------------------
INSERT INTO `employee` (`id`,`full_name`,`password`,`dob`,`gender`,`phone_number`,`email`,`note`,`image`,`auth_key`,`password_reset_token`,`start_date`,`status`,`address_id`) VALUES
(7,'Đỗ Anh Tú','$2y$13$Z7qj6WI8XDoY00EO/uIgbuUnOvcU5po6uwYLLjL/MDQE.zUerCMRS',2147446800,'Male',9846227931,'freshgardenhl@gmail.com','IT_Adminitrator','qt7cMGjMlt3lloMh-mtf4kjyCfX5qQMS.jpg',NULL,NULL,1432486800,1,1);
INSERT INTO `employee` (`id`,`full_name`,`password`,`dob`,`gender`,`phone_number`,`email`,`note`,`image`,`auth_key`,`password_reset_token`,`start_date`,`status`,`address_id`) VALUES
(8,'Nguyen Trung Dung','$2y$13$MxlC9lO8zZcduxtpfsNnNuxet9UfzBXVQsPIDZHfDdeYkKU83SrpW',1439571600,'Male',0962931939,'dungnt01532@fpt.edu.vn','note','hg0WaM0BlRhNV5P0UGF4BI2FlJaohJvp.jpg',NULL,NULL,1439658000,1,14);
INSERT INTO `employee` (`id`,`full_name`,`password`,`dob`,`gender`,`phone_number`,`email`,`note`,`image`,`auth_key`,`password_reset_token`,`start_date`,`status`,`address_id`) VALUES
(9,'Lê Hồng Quân','$2y$13$EcPyMQ2RxP5ix4EsFEzFUuAw2OFZrlYF/BtCLrRfhvmwFASNeOOB6',1439658000,'Male',0168111222333,'quanlh@fpt.edu.vn','Nhân viên bán hàng mới tuyển tháng 7/2015','j8FUL5JuuSBzfRkaOv5GDqbW-erkqgy5.jpg',NULL,NULL,1436893200,1,15);



-- -------------------------------------------
-- TABLE DATA guest
-- -------------------------------------------
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(1,'Nguyễn Văn Lương','luongnv93@gmail.com',972412521);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(2,'Cấn Hồng Thao','thaosay93@gmail.com',948231412);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(3,'Trương Ngọc Tuân','tuantn93@gmail.com',972412454);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(4,'Phan Bá Hoàng','hoangba93@gmail.com',983452342);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(5,'Vũ Thị Thúy','chihaihaiphong@yahoo.com',913434242);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(6,'Trâng Hồng Nhung','nhungngo@gmail.com',962314132);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(7,'Đặng Thủy Tiên','tienninhbinh@yahoo.com',982312543);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(8,'Đỗ Anh Tú','doanhtu278@gmail.com',984622793);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(9,'Vũ Văn Thính','thinhvv@gmail.com',923135312);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(10,'Đặng Thành Dũng','dungdt@gmail.com',982312412);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(11,'Lê Hồng Quân','quanlhse02625@fpt.edu.vn',934252523);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(12,'Nguyễn Trung Dũng','dungnt@gmail.com',924423511);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(13,'Bui Ngoc Anh','anhbn@fpt.edu.vn',0915343020);



-- -------------------------------------------
-- TABLE DATA image
-- -------------------------------------------
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(87,'ngot1.jpg','uploads/products/images/33/saf0ibJjTTvEJgja9Hf5f5clwB1BIRXm.jpg','uploads/products/resizeimages/33/saf0ibJjTTvEJgja9Hf5f5clwB1BIRXm.jpg',33);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(88,'ngot2.jpg','uploads/products/images/33/9mWVc5SrUonU5wNcVHbLIYq1y6wwelb_.jpg','uploads/products/resizeimages/33/9mWVc5SrUonU5wNcVHbLIYq1y6wwelb_.jpg',33);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(89,'ngot3.jpg','uploads/products/images/33/4VqkIh9GV7GRuexAfzfvpU9s5wJjj7MC.jpg','uploads/products/resizeimages/33/4VqkIh9GV7GRuexAfzfvpU9s5wJjj7MC.jpg',33);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(90,'rau den do-500x500.jpg','uploads/products/images/34/8Mveq3BQXbD0LY7HRcz5ufvmrB-o8CCm.jpg','uploads/products/resizeimages/34/8Mveq3BQXbD0LY7HRcz5ufvmrB-o8CCm.jpg',34);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(91,'rau dền(1).jpg','uploads/products/images/34/CtZlvOm_HZj1FfjpLV0NZrrwvJQLJe9J.jpg','uploads/products/resizeimages/34/CtZlvOm_HZj1FfjpLV0NZrrwvJQLJe9J.jpg',34);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(92,'rau-den-500x500.jpg','uploads/products/images/34/d2_iJ-qUFBrUbxrIR1eO90oFndRzkzlz.jpg','uploads/products/resizeimages/34/d2_iJ-qUFBrUbxrIR1eO90oFndRzkzlz.jpg',34);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(93,'5-Pack-300-font-b-Seed-b-font-font-b-Celery-b-font-font-b-Seeds.jpg','uploads/products/images/35/fjPiMjEqqXRJM2vUweK58XvGj0UR6wxL.jpg','uploads/products/resizeimages/35/fjPiMjEqqXRJM2vUweK58XvGj0UR6wxL.jpg',35);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(94,'can-tay-3-500x500.jpg','uploads/products/images/35/nzWQS4XWyNNdQrlgY_HrkNOTniCt9Qbm.jpg','uploads/products/resizeimages/35/nzWQS4XWyNNdQrlgY_HrkNOTniCt9Qbm.jpg',35);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(95,'d-5_lgdk_qpac.jpg','uploads/products/images/35/BF6HTeRCVwnqBh2bbxo8uk4aKA9m-Hoe.jpg','uploads/products/resizeimages/35/BF6HTeRCVwnqBh2bbxo8uk4aKA9m-Hoe.jpg',35);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(96,'caichip.jpg','uploads/products/images/36/Ss5DMRJqQEtkxYx6NPaQjGL8HAlNg6LJ.jpg','uploads/products/resizeimages/36/Ss5DMRJqQEtkxYx6NPaQjGL8HAlNg6LJ.jpg',36);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(97,'cai-chip-500x500.jpg','uploads/products/images/36/6vZOfBswyycvC8I0OyHLrs-Rz7rjdkMv.jpg','uploads/products/resizeimages/36/6vZOfBswyycvC8I0OyHLrs-Rz7rjdkMv.jpg',36);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(98,'cai-chip-2013-01-02-11-36-16.jpg','uploads/products/images/36/Zcgrzaj8SIUmSMmfJix-3f2jjDNxMtzW.jpg','uploads/products/resizeimages/36/Zcgrzaj8SIUmSMmfJix-3f2jjDNxMtzW.jpg',36);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(99,'1042_G_1412299023450.jpg','uploads/products/images/37/acb4uArWJc_axL1Hn3wJ6OMtfVfhkwuP.jpg','uploads/products/resizeimages/37/acb4uArWJc_axL1Hn3wJ6OMtfVfhkwuP.jpg',37);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(100,'rau-muong-2-500x500.jpg','uploads/products/images/37/Y0kzh2ylklJ6H5ZdkVnhZ6HX7CxDVCsz.jpg','uploads/products/resizeimages/37/Y0kzh2ylklJ6H5ZdkVnhZ6HX7CxDVCsz.jpg',37);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(101,'suckhoe_rau_muong-500x500.jpg','uploads/products/images/37/HflkKPmmUBltdiP7JCrNhxCnwoki-3Lb.jpg','uploads/products/resizeimages/37/HflkKPmmUBltdiP7JCrNhxCnwoki-3Lb.jpg',37);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(102,'1034_G_1412298653453.jpg','uploads/products/images/38/vLekKtqxKvq6xhw_OVpB5b9RwqnDm1Vu.jpg','uploads/products/resizeimages/38/vLekKtqxKvq6xhw_OVpB5b9RwqnDm1Vu.jpg',38);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(103,'hat-giong-rau-bi.jpg','uploads/products/images/38/PGuXnV_1Nw7FBX_sMpeAXSW9vByFKHb1.jpg','uploads/products/resizeimages/38/PGuXnV_1Nw7FBX_sMpeAXSW9vByFKHb1.jpg',38);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(104,'raubi1-500x500.jpg','uploads/products/images/38/kJF7-SUjeBas90_xpaYEB2g5UqCVDgUJ.jpg','uploads/products/resizeimages/38/kJF7-SUjeBas90_xpaYEB2g5UqCVDgUJ.jpg',38);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(105,'bapcai1.jpg','uploads/products/images/39/70LU_cWUSuST04O-nUPXwEUp8sJkI4fS.jpg','uploads/products/resizeimages/39/70LU_cWUSuST04O-nUPXwEUp8sJkI4fS.jpg',39);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(106,'bapcai2.jpg','uploads/products/images/39/TLTRuRml01c2Il_i6oJfbrPNj0XtxbOM.jpg','uploads/products/resizeimages/39/TLTRuRml01c2Il_i6oJfbrPNj0XtxbOM.jpg',39);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(107,'bapcai3.jpg','uploads/products/images/39/DMKZKGwXYu36rfcHlDpKU7C5L9Da8WpD.jpg','uploads/products/resizeimages/39/DMKZKGwXYu36rfcHlDpKU7C5L9Da8WpD.jpg',39);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(108,'cải ngọt-500x500.jpg','uploads/products/images/40/Q4jvTXv1evoYZqs5rhxfweBN6WsBpHCx.jpg','uploads/products/resizeimages/40/Q4jvTXv1evoYZqs5rhxfweBN6WsBpHCx.jpg',40);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(109,'cai-canh-2013-01-02-11-39-13.jpg','uploads/products/images/40/rFgm7LkvCOnx3e2KbTkOT6i3AbwOlsaA.jpg','uploads/products/resizeimages/40/rFgm7LkvCOnx3e2KbTkOT6i3AbwOlsaA.jpg',40);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(110,'cai-ngot-bong635621847282392788.jpg','uploads/products/images/40/jcaJRflzc2v8JsQX5KhJYsJtkQfRIpFv.jpg','uploads/products/resizeimages/40/jcaJRflzc2v8JsQX5KhJYsJtkQfRIpFv.jpg',40);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(111,'khoai so1.jpg','uploads/products/images/41/TPhMQ2JwUX_guNo8ITVIAdjkV1LqyjD6.jpg','uploads/products/resizeimages/41/TPhMQ2JwUX_guNo8ITVIAdjkV1LqyjD6.jpg',41);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(112,'khoai so2.jpg','uploads/products/images/41/dgc5DW89aRmXZKp0lfgJxPRUXW0m0FBV.jpg','uploads/products/resizeimages/41/dgc5DW89aRmXZKp0lfgJxPRUXW0m0FBV.jpg',41);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(113,'Khoai-so-chien-Mon-an-vat-thom-bui-ngay-cuoi-nam_Tin180.com_002.jpg','uploads/products/images/41/3LgLoLZqhZflropkTMqfn9Me34ZSp0NW.jpg','uploads/products/resizeimages/41/3LgLoLZqhZflropkTMqfn9Me34ZSp0NW.jpg',41);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(114,'1.jpg','uploads/products/images/42/ZOXi-Nvp6uyhpQm7APw2CaDNvUogysxb.jpg','uploads/products/resizeimages/42/ZOXi-Nvp6uyhpQm7APw2CaDNvUogysxb.jpg',42);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(115,'11-3.jpg','uploads/products/images/42/85nrM-_DAAxAWFnAlCHOHh2RuoD3dQNk.jpg','uploads/products/resizeimages/42/85nrM-_DAAxAWFnAlCHOHh2RuoD3dQNk.jpg',42);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(116,'CU-KHOAI-TAY.jpg','uploads/products/images/42/_kkguxL9mrgNl-q7jPoZLpiuMsWoOMR4.jpg','uploads/products/resizeimages/42/_kkguxL9mrgNl-q7jPoZLpiuMsWoOMR4.jpg',42);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(117,'bi ngoi xanh-500x500.jpg','uploads/products/images/43/KNHdGZ8MVFUIShHS5xPAGpaO-I_Er3Lt.jpg','uploads/products/resizeimages/43/KNHdGZ8MVFUIShHS5xPAGpaO-I_Er3Lt.jpg',43);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(118,'bi-ngoi.jpg','uploads/products/images/43/PgmImPNhtNUWRPsewCy3ofaaGEGDnG7I.jpg','uploads/products/resizeimages/43/PgmImPNhtNUWRPsewCy3ofaaGEGDnG7I.jpg',43);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(119,'cach-lam-bi-ngoi-xao-tom-kho-ngon-com-dep-mat-cho-bua-an-gia-dinh-phong-phu-va-bo-sung-nhieu-chat-dinh-duong-phan-1.jpg','uploads/products/images/43/5c3PvtNYKb09VYoAXjzjZ8sM5cj8kjBZ.jpg','uploads/products/resizeimages/43/5c3PvtNYKb09VYoAXjzjZ8sM5cj8kjBZ.jpg',43);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(120,'1366792586-ca-rot1.jpg','uploads/products/images/44/2sZuZL_yaV9ruf1gW5GHByiRGHsrEuXT.jpg','uploads/products/resizeimages/44/2sZuZL_yaV9ruf1gW5GHByiRGHsrEuXT.jpg',44);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(121,'143288075357.jpg','uploads/products/images/44/ojJF5DoDIMkGwvGnA9j8fNNK1X8ExAiN.jpg','uploads/products/resizeimages/44/ojJF5DoDIMkGwvGnA9j8fNNK1X8ExAiN.jpg',44);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(122,'ca-rot-11nmqp_ptzw.jpg','uploads/products/images/44/Oz3SVGik4o1HMnYav1E9skReqzmPDLKs.jpg','uploads/products/resizeimages/44/Oz3SVGik4o1HMnYav1E9skReqzmPDLKs.jpg',44);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(123,'ca-chua6-500x500.jpg','uploads/products/images/45/yacuj3IOhbdoLfwmt17nOFF_cG59acLY.jpg','uploads/products/resizeimages/45/yacuj3IOhbdoLfwmt17nOFF_cG59acLY.jpg',45);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(124,'ca-chua-rau-sach-500x500.jpg','uploads/products/images/45/uzRWWAgX80rjXd_2DYq2GglRLa43LunC.jpg','uploads/products/resizeimages/45/uzRWWAgX80rjXd_2DYq2GglRLa43LunC.jpg',45);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(125,'lam-trang-da-voi-ca-chua-464x366-500x500.jpg','uploads/products/images/45/ehEo57op31gjL3xE5L2lAPqnEwXgfQuj.jpg','uploads/products/resizeimages/45/ehEo57op31gjL3xE5L2lAPqnEwXgfQuj.jpg',45);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(126,'1.jpeg','uploads/products/images/46/gPJATP6UI2awxIQ7G1Ot5F0Ly8aN5zoo.jpeg','uploads/products/resizeimages/46/gPJATP6UI2awxIQ7G1Ot5F0Ly8aN5zoo.jpeg',46);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(127,'chanh-500x500.jpg','uploads/products/images/46/8AE4FezpKWJFvX4LAvnJZ08sqFruHYxy.jpg','uploads/products/resizeimages/46/8AE4FezpKWJFvX4LAvnJZ08sqFruHYxy.jpg',46);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(128,'tri-seo-tham-bang-chanh.jpg','uploads/products/images/46/O26WEhSVBENJg_gKd1Fa34C6aJrQKxG-.jpg','uploads/products/resizeimages/46/O26WEhSVBENJg_gKd1Fa34C6aJrQKxG-.jpg',46);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(129,'5-doi-tuong-khong-duoc-an-muop-dang.jpg','uploads/products/images/47/mLfjGDC609-5-Qjk2rOx1aNdOZgnjxHw.jpg','uploads/products/resizeimages/47/mLfjGDC609-5-Qjk2rOx1aNdOZgnjxHw.jpg',47);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(130,'5-Pack-50-Seed-Balsam-Pear-Seeds-font-b-Momordica-b-font-font-b-Charantia-b.jpg','uploads/products/images/47/a-Zg9w5_KWix8tpMWxg1TwM47j0mq8Vp.jpg','uploads/products/resizeimages/47/a-Zg9w5_KWix8tpMWxg1TwM47j0mq8Vp.jpg',47);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(131,'dfgs.jpg','uploads/products/images/47/vn1fvt1DESi1MPTPmvw7x9ufCz-k80aN.jpg','uploads/products/resizeimages/47/vn1fvt1DESi1MPTPmvw7x9ufCz-k80aN.jpg',47);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(132,'dua chuot 2.jpg','uploads/products/images/48/5rzMlIoirLycym3F_0x7sQdiLZOsn63Q.jpg','uploads/products/resizeimages/48/5rzMlIoirLycym3F_0x7sQdiLZOsn63Q.jpg',48);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(133,'dua-chuot-tri-mun-cam.jpg','uploads/products/images/48/qkt1x-L18qAkL4Ue4_eBUG3Xwf_KKLP_.jpg','uploads/products/resizeimages/48/qkt1x-L18qAkL4Ue4_eBUG3Xwf_KKLP_.jpg',48);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(134,'thao-duoc-tri-mun-3.jpg','uploads/products/images/48/87cRf6SlPMzcB9zSLNLl4j7tkX9448WC.jpg','uploads/products/resizeimages/48/87cRf6SlPMzcB9zSLNLl4j7tkX9448WC.jpg',48);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(135,'1.jpg','uploads/products/images/49/FYKruscWJgY1-CD4i0zBr9m2WJypl9CB.jpg','uploads/products/resizeimages/49/FYKruscWJgY1-CD4i0zBr9m2WJypl9CB.jpg',49);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(136,'giam-beo-bung-bang-gung-tu-tin-dien-do-1.jpg','uploads/products/images/49/0LP85VwO6d032i7xQwdnmnJ-ELE4BgCp.jpg','uploads/products/resizeimages/49/0LP85VwO6d032i7xQwdnmnJ-ELE4BgCp.jpg',49);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(137,'meo-sinh-to-giup-da-sang-hong-tu-dua-gung.jpg','uploads/products/images/49/ktEJkRMpu89pjwffLsTKQfcsr7P4IGhE.jpg','uploads/products/resizeimages/49/ktEJkRMpu89pjwffLsTKQfcsr7P4IGhE.jpg',49);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(138,'bong-cai-cung-la-lieu-thuoc-trang-da-huu-dung-thuốc-trắng-da2.jpg','uploads/products/images/50/_wjIW5P9qCsjJcW77mS5m94B0wj_CUhR.jpg','uploads/products/resizeimages/50/_wjIW5P9qCsjJcW77mS5m94B0wj_CUhR.jpg',50);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(139,'brocolli.jpg','uploads/products/images/50/IiSQBaN_4pw4JlnofleX546u-dvAcosw.jpg','uploads/products/resizeimages/50/IiSQBaN_4pw4JlnofleX546u-dvAcosw.jpg',50);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(140,'BSnhi-bong-cai-xanh-top-10-rau-cu-giau-canxi-trong-thuc-don-cho-be-an-dam.jpg','uploads/products/images/50/IEmzCpOa3D1gtwta-3O6YLOzSezTwI-N.jpg','uploads/products/resizeimages/50/IEmzCpOa3D1gtwta-3O6YLOzSezTwI-N.jpg',50);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(141,'1.jpg','uploads/products/images/51/V0agEFyeupwap9Mcyts1AjKDQgoq4fti.jpg','uploads/products/resizeimages/51/V0agEFyeupwap9Mcyts1AjKDQgoq4fti.jpg',51);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(142,'hoa_thien_li.jpg','uploads/products/images/51/GnwBoOVrrPpHXXrZKSHQXxA67FUFGJk5.jpg','uploads/products/resizeimages/51/GnwBoOVrrPpHXXrZKSHQXxA67FUFGJk5.jpg',51);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(143,'khoi-benh-tri-than-ky-bang-hoa-thien-li.jpg','uploads/products/images/51/XnndBSCfVXxb0bmhQfuYwCrRl7wTKhLT.jpg','uploads/products/resizeimages/51/XnndBSCfVXxb0bmhQfuYwCrRl7wTKhLT.jpg',51);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(144,'18.Dua hau_khong_hat.jpg','uploads/products/images/52/N9Vw0Hc9UBPGs_KvP5qVT4wngqigUdOI.jpg','uploads/products/resizeimages/52/N9Vw0Hc9UBPGs_KvP5qVT4wngqigUdOI.jpg',52);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(145,'74b979c9af89f5e1eade3e597cd73228.jpg','uploads/products/images/52/C6nLpyMsX7pOuTGPmoE4kpxLukGZp7pn.jpg','uploads/products/resizeimages/52/C6nLpyMsX7pOuTGPmoE4kpxLukGZp7pn.jpg',52);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(146,'dua-hau-3.gif','uploads/products/images/52/nA3qmVuTRL-GMVzeksMgrXKoNrnK0TVL.gif','uploads/products/resizeimages/52/nA3qmVuTRL-GMVzeksMgrXKoNrnK0TVL.gif',52);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(147,'IMAGE_PRODUCT_20131220101736.jpeg','uploads/products/images/53/3zPAtdpjy9oS2-bUGwWyHkkXjlJsmlMi.jpeg','uploads/products/resizeimages/53/3zPAtdpjy9oS2-bUGwWyHkkXjlJsmlMi.jpeg',53);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(148,'item_s3368-500x500.jpg','uploads/products/images/53/JQclLwHpJ2eWoDLjmVcNAcUtANp6B5Oh.jpg','uploads/products/resizeimages/53/JQclLwHpJ2eWoDLjmVcNAcUtANp6B5Oh.jpg',53);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(149,'xoai-cat-hoa-loc.jpg','uploads/products/images/53/6U6nGjVIMSLMw1NK8-ZPsTBiKPN1pLYK.jpg','uploads/products/resizeimages/53/6U6nGjVIMSLMw1NK8-ZPsTBiKPN1pLYK.jpg',53);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(150,'Buoi Da Xanh Ben Tre.jpg','uploads/products/images/54/636YO9_vbsKP79GiI4RwG5f08KQ_8Ami.jpg','uploads/products/resizeimages/54/636YO9_vbsKP79GiI4RwG5f08KQ_8Ami.jpg',54);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(151,'Cach Chon Buoi Da Xanh.jpg','uploads/products/images/54/dUkdtXnWmz6HcksIMprfXe15zoY5DSZr.jpg','uploads/products/resizeimages/54/dUkdtXnWmz6HcksIMprfXe15zoY5DSZr.jpg',54);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(152,'quabuoigiamcan.jpg','uploads/products/images/54/mhFvWBX5Dy8ieIwkHjq4NBlR7c601w9S.jpg','uploads/products/resizeimages/54/mhFvWBX5Dy8ieIwkHjq4NBlR7c601w9S.jpg',54);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(153,'1.jpg','uploads/products/images/55/897HRAOdWLI7J8TsaS6yUVoJwecPC6r6.jpg','uploads/products/resizeimages/55/897HRAOdWLI7J8TsaS6yUVoJwecPC6r6.jpg',55);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(154,'48324566.jpg','uploads/products/images/55/JYRR7u2xnHxzbxOG1F1LHNzFo7owv8R5.jpg','uploads/products/resizeimages/55/JYRR7u2xnHxzbxOG1F1LHNzFo7owv8R5.jpg',55);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(155,'tam-trang-voi-cam.jpg','uploads/products/images/55/IOmzzpKJSYkK3eKh-kwqPY32hXU1_1cp.jpg','uploads/products/resizeimages/55/IOmzzpKJSYkK3eKh-kwqPY32hXU1_1cp.jpg',55);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(156,'1.jpg','uploads/products/images/56/0dEArgot9PoSH4rOOORRorCjnrqa8jOo.jpg','uploads/products/resizeimages/56/0dEArgot9PoSH4rOOORRorCjnrqa8jOo.jpg',56);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(157,'chom-chom-3-500x500.jpg','uploads/products/images/56/S5dGy-EVtkDW9_mOz5BObBNkMMneWYN5.jpg','uploads/products/resizeimages/56/S5dGy-EVtkDW9_mOz5BObBNkMMneWYN5.jpg',56);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(158,'Tac-dung-tri-benh-lam-dep-bat-ngo-cua-qua-chom-chom-3.jpg','uploads/products/images/56/H0asnku0vfNb6n7CzckuDxz6uR-gDxgR.jpg','uploads/products/resizeimages/56/H0asnku0vfNb6n7CzckuDxz6uR-gDxgR.jpg',56);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(159,'ce986ca7d5e9ff765bbc22d975c0a762.jpg','uploads/products/images/57/COVzWg6nckbyaGpw910p58XBYF7I4PEz.jpg','uploads/products/resizeimages/57/COVzWg6nckbyaGpw910p58XBYF7I4PEz.jpg',57);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(160,'Durian1.jpg','uploads/products/images/57/gOjskU1MFUdtAgxiAkfaK8M56Q5E3qg7.jpg','uploads/products/resizeimages/57/gOjskU1MFUdtAgxiAkfaK8M56Q5E3qg7.jpg',57);
INSERT INTO `image` (`id`,`name`,`path`,`resize_path`,`product_id`) VALUES
(161,'Sầu_riêng_các_giá_trị_dinh_dưỡn.jpeg','uploads/products/images/57/i4BIz5EZYnYEWkbmCdiRxUPqvsQsJOzc.jpeg','uploads/products/resizeimages/57/i4BIz5EZYnYEWkbmCdiRxUPqvsQsJOzc.jpeg',57);



-- -------------------------------------------
-- TABLE DATA order_status
-- -------------------------------------------
INSERT INTO `order_status` (`id`,`name`,`comment`) VALUES
(1,'Đang chờ xác nhận','Đơn Hàng đang chờ xác nhận');
INSERT INTO `order_status` (`id`,`name`,`comment`) VALUES
(2,'Đã được xác nhận','Đơn hàng đã được xác nhận');
INSERT INTO `order_status` (`id`,`name`,`comment`) VALUES
(3,'Đã bị hủy','Đơn hàng đã bị hủy');
INSERT INTO `order_status` (`id`,`name`,`comment`) VALUES
(4,'Đã giao thành công','Đơn hàng đã giao thành công');



-- -------------------------------------------
-- TABLE DATA product
-- -------------------------------------------
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(33,1,'Rau ngót',10000,'<span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Rau ngót thuộc dạng cây bụi, có thể cao đến 2&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/M\" title=\"M\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">m</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, phần thân khi già cứng chuyển màu nâu.&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/L%C3%A1\" title=\"Lá\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Lá</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;cây rau ngót&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=H%C3%ACnh_b%E1%BA%A7u_d%E1%BB%A5c&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Hình bầu dục (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">hình bầu dục</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, mọc so le; sắc lá&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Xanh_l%C3%A1_c%C3%A2y\" title=\"Xanh lá cây\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">màu lục</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;thẫm. Khi hái ăn, thường chọn lá non. Vị rau tương tự như&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/M%C4%83ng_t%C3%A2y\" title=\"Măng tây\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">măng tây</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>','Rau ngót là loại rau nấu canh khoái khẩu lại có tác dụng cân bằng thân nhiệt cho cơ thể hiệu quả. Không giống như một số loại rau khác chỉ dùng lá non, lá rau ngót già có hàm lượng chất xơ và beta caroten càng dồi dào, giúp hệ tiêu hóa hoạt động trơn tru. Do vậy đừng bỏ đi những lá già có màu hơi sẫm, và nấu rau ngót với dầu thực vật để có thể hấp thụ beta caroten một cách hiệu quả hơn.',100,50,10,1439485200,1,5,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(34,2,'Rau dền',10000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Rau dền</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, là tên gọi chung để chỉ các loài trong&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Chi Dền</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p\" title=\"Danh pháp\" class=\"mw-disambig\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp khoa học</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Amaranthus</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, bao gồm cả các danh pháp liên quan tới&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Acanthochiton</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Acnida</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Montelia</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) do ở&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam\" title=\"Việt Nam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Việt Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;thường được sử dụng làm rau. Chi Dền gồm những loài đều có hoa không tàn, một số mọc hoang dại nhưng nhiều loài được sử dụng làm&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BB%B1c_ph%E1%BA%A9m\" title=\"Thực phẩm\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">lương thực</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Rau\" title=\"Rau\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rau</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/C%C3%A2y_c%E1%BA%A3nh\" title=\"Cây cảnh\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">cây cảnh</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;ở các vùng khác nhau trên thế giới.</span>','Rau dền là loại rau mùa hè, có tác dụng mát gan, thanh nhiệt. Theo Đông y, rau đền đỏ vị ngọt, tính mát, có tác dụng thanh nhiệt, làm mát máu, lợi tiểu, sát trùng. Danh y Lý Thời Trân (thời Minh, Trung Quốc) cho rằng rau dền đỏ có tác dụng trị nhiệt lỵ, huyết nhiệt sinh mụn nhọt.',200,150,10,1439485200,1,5,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(35,3,'Cần tây',17000,'<p><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cần tây</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_khoa_h%E1%BB%8Dc\" title=\"Danh pháp khoa học\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp khoa học</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Apium graveolens</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, là một loài thực vật thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_Hoa_t%C3%A1n\" title=\"Họ Hoa tán\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Hoa tán</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Loài này được&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Carl_von_Linn%C3%A9\" title=\"Carl von Linné\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Carl von Linné</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;mô tả khoa học đầu tiên năm 1753.</span></p><p><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cây cao, có tuổi thọ gần 2 năm, thân mọc thẳng đứng, cao tới 1,5 m, nhưng có nhiều rãnh dọc, chia nhiều cành mọc đứng. Lá ở gốc có cuống, hình thuôn hay 3 cạnh, dạng mắt chim, tù có khóa lượn tai bèo. Lá giữa và lá ngọn không có cuống, chia 3 hoặc xẻ 3 hoặc không chia thùy. Hoa gồm nhiều tán, các tán ở đầu cành có cuống dài hơn các tán bên. Không có tổng bao, hoa nhỏ màu trắng nhạt. Quả dạng trứng, hình cầu có vạch lồi chạy dọc</span><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><br></span></p>','Cần tây là loại rau quen thuộc trong bữa ăn hàng ngày. Không chỉ là nguyên liệu dùng để chế biến nhiều món ăn ngon, cần tây còn mang lại rất nhiều dưỡng chất thiết yếu cho cơ thể như các amino a-xít, boron, can-xi, folate, sắt, ma-giê, man-gan, phốt-pho, kali, selen, kẽm, vitamin A, một số loại vitamin B (như B1, B2 và B6), vitamin C, vitamin K và chất xơ. Nhờ vậy mà cần tây có khả năng phòng chống một số bệnh nguy hiểm.',100,30,10,1439571600,1,5,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(36,4,'Cải chíp',10000,'<span style=\"color: rgb(85, 85, 85); font-family: Roboto, arial, helvetica, sans-serif; font-size: 15.3999996185303px; line-height: normal;\">Cải chíp (Brassica chinensis L.), thuộc họ thập tự (Cruciferae), là loại rau rất quen thuộc trong bữa ăn hàng ngày, rau chứa nhiều thành phần dinh dưỡng</span>','Cải chíp là loại rau rất quen thuộc trong bữa ăn hàng ngày, rau chứa nhiều thành phần dinh dưỡng như: vitamin A, B, C. Lượng vitamin C của rau đứng vào bậc nhất trong các loại rau.',100,40,10,1439571600,1,5,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(37,5,'Rau muống',12000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Rau muống</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Ipomoea aquatica</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một loài thực vật&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Nhi%E1%BB%87t_%C4%91%E1%BB%9Bi\" title=\"Nhiệt đới\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">nhiệt đới</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BB%B1c_v%E1%BA%ADt_th%E1%BB%A7y_sinh\" title=\"Thực vật thủy sinh\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bán thủy sinh</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_B%C3%ACm_b%C3%ACm\" title=\"Họ Bìm bìm\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Bìm bìm</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">(Convolvulaceae), là một loại&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=Rau_%C4%83n_l%C3%A1&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Rau ăn lá (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rau ăn lá</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Phân bố tự nhiên chính xác của loài này hiện chưa rõ do được trồng phổ biến khắp các vùng nhiệt đới và cận nhiệt đới trên thế giới. Tại&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam\" title=\"Việt Nam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Việt Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, nó là một loại&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Rau\" title=\"Rau\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rau</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;rất phổ thông và rất được ưa chuộng.</span>','Rau muống là loại rau quen thuộc trong mùa hè. Ngoài công dụng là thực phẩm ngon miệng, giải nhiệt, rau muống còn có tác dụng giải độc, nhuận tràng, chữa rôm sảy, mụn nhọt …',50,15,10,1439571600,1,5,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(38,6,'Bí ngọn',12000,'<p style=\"line-height: 28px; color: rgb(85, 85, 85); font-family: Roboto, arial, helvetica, sans-serif; font-size: 14px; text-align: justify;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: large; vertical-align: baseline; font-family: \'times new roman\', times; background: transparent;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 18px; vertical-align: baseline; color: rgb(0, 0, 255); background: transparent;\"><a href=\"http://fvf.vn/bi-ngon\" title=\"Bí ngọn\" style=\"margin: 0px; padding: 0px; vertical-align: baseline; color: rgb(100, 100, 100); word-wrap: break-word; transition: color 0.1s ease-in, background 0.1s ease-in; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; color: rgb(0, 0, 255); background: transparent;\"><strong style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent;\">Bí ngọn</strong></span></a></span>&nbsp;dùng làm rau ăn: xào, um (xào nước) hay nấu canh. Có tính thanh nhiệt, nhuận tràng nhờ chất xơ kích thích nhu động ruột.</span><br><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: large; vertical-align: baseline; font-family: \'times new roman\', times; background: transparent;\"></span></p><p style=\"line-height: 28px; color: rgb(85, 85, 85); font-family: Roboto, arial, helvetica, sans-serif; font-size: 14px; text-align: justify;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: large; vertical-align: baseline; font-family: \'times new roman\', times; background: transparent;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 18px; vertical-align: baseline; background: transparent;\">Ngọn bí là món ăn cung cấp nhiều vitamin, chất xơ cho cơ thể. Đặc biệt trong những lúc cơ thể phải quá tải với lượng thịt, mỡ vào những ngày Tết, thì những món ăn từ rau xanh luôn là lựa chọn tốt cho bạn.</span></span></p><p style=\"line-height: 28px; color: rgb(85, 85, 85); font-family: Roboto, arial, helvetica, sans-serif; font-size: 14px; text-align: justify;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: large; vertical-align: baseline; font-family: \'times new roman\', times; background: transparent;\">Món chay đọt bí đỏ nấu với cà chua. Ngọn bí và&nbsp;<a href=\"http://fvf.vn/ca-chua\" title=\"Cà chua\" style=\"margin: 0px; padding: 0px; font-size: 18px; vertical-align: baseline; color: rgb(100, 100, 100); word-wrap: break-word; transition: color 0.1s ease-in, background 0.1s ease-in; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; color: rgb(0, 0, 255); background: transparent;\"><strong style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent;\">cà chua</strong></span></a>&nbsp;đều thanh nhiệt, nhuận tràng. Đây là một kết hợp đồng vận vì cả hai đều có tính chống oxy-hoá ; tăng tính trị liệu cũng tăng khẩu vị. Khi trời nắng nóng nên ăn món này.</span></p>','Rau bí chính là phần ngọn bí. Rau bí có thể chế biến thành nhiều món ngon khác nhau như luộc, xào tỏi, nấu giò sống, xào thịt bò, xào hến, nấu canh...',20,5,10,1439571600,1,5,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(39,7,'Bắp cải',30000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Bắp cải</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;hay&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">cải bắp</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><a href=\"https://vi.wikipedia.org/wiki/C%E1%BA%A3i_b%E1%BA%AFp_d%E1%BA%A1i\" title=\"Cải bắp dại\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Brassica oleracea</a></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;nhóm Capitata) là một loại rau chủ lực trong&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_C%E1%BA%A3i\" title=\"Họ Cải\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Cải</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(còn gọi là họ Thập tự - Brassicaceae/Cruciferae), phát sinh từ vùng&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%C4%90%E1%BB%8Ba_Trung_H%E1%BA%A3i\" title=\"Địa Trung Hải\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Địa Trung Hải</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Nó là cây thân thảo, sống hai năm, và là một&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BB%B1c_v%E1%BA%ADt_c%C3%B3_hoa\" title=\"Thực vật có hoa\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">thực vật có hoa</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;thuộc nhóm</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BB%B1c_v%E1%BA%ADt_hai_l%C3%A1_m%E1%BA%A7m\" title=\"Thực vật hai lá mầm\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">hai lá mầm</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;với các lá tạo thành một cụm đặc hình gần như hình cầu đặc trưng.</span>','Không chỉ là loại rau ngon, dễ chế biến trong mùa đông, cải bắp còn có khá nhiều công dụng chữa bệnh như phòng chống bệnh đường tiêu hóa, phòng tiểu đường và béo phì.',50,20,10,1439571600,1,5,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(40,8,'Cải ngọt',10000,'<p><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cải ngọt</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;có tên khoa học là&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Brassica integrifolia</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_C%E1%BA%A3i\" title=\"Họ Cải\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Họ Cải</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(Brassicaceae), thường được trồng để dùng làm rau ăn.</span></p><p><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cải ngọt</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;có nguồn gốc từ&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%E1%BA%A4n_%C4%90%E1%BB%99\" title=\"Ấn Độ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ấn Độ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Trung_Qu%E1%BB%91c\" title=\"Trung Quốc\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Trung Quốc</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Cây thảo, cao tới 50 - 100&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Cm\" title=\"Cm\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">cm</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, thân tròn, không lông, lá có phiến xoan ngược tròn dài, đầu tròn hay tù, gốc từ từ hẹp, mép nguyên không nhăn, mập, trắng trắng, gân bên 5 - 6 đôi, cuống dài, tròn. Chùm hoa như ngù ở ngọn, cuống hoa dài 3 – 5&nbsp;cm, hoa vàng tươi, quả cải dài 4 – 11&nbsp;cm, có mỏ, hạt tròn. Cải ngọt được trồng quanh năm, thời gian sinh trưởng.</span><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><br></span></p>','Cải ngọt là loài rau thuộc họ cải, rất dễ ăn và giàu chất dinh dưỡng. Theo Đông y, cải ngọt tính ôn, có công dụng thông lợi trường vị, làm đỡ tức ngực, tiêu thực hạ khí... có thể dùng để chữa các chứng ho, táo bón, ăn nhiều cải ngọt giúp cho việc phòng ngừa bệnh trĩ và ung thư ruột kết.&nbsp;',30,15,10,1439571600,1,5,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(41,9,'Khoai sọ',15000,'<p style=\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.3999996185303px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\"><b>Khoai sọ</b>&nbsp;là tên gọi của một số giống khoai thuộc loài&nbsp;<i><b><a href=\"https://vi.wikipedia.org/wiki/Colocasia_esculenta\" title=\"Colocasia esculenta\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Colocasia esculenta</a></b></i>&nbsp;<small>(L.) Schott</small>, một loài cây thuộc&nbsp;<a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_R%C3%A1y\" title=\"Họ Ráy\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">họ Ráy</a>&nbsp;(<i>Araceae</i>).</p><p style=\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.3999996185303px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\">Cây khoai sọ có củ cái và củ con. Khác với khoai môn, củ cái khoai sọ nhỏ, nhiều củ con, nhiều tinh bột. Nhóm khoai sọ thích hợp với các loại đất thịt nhẹ, cát pha, giàu mùn, thoát nước tốt, chủ yếu được trồng ở vùng đồng bằng và trung du.</p>','Khoai sọ là thực phẩm chứa nhiều chất dinh dưỡng như: tinh bột, protid, lipid, galactose, Ca, P, F; các vitamin A, B, C và nhiều &nbsp;axit amin cần thiết cho cơ thể.&nbsp;',50,20,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(42,10,'Khoai tây vỏ hồng',18000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Khoai tây</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Solanum tuberosum</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">), thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_C%C3%A0\" title=\"Họ Cà\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Cà</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Solanaceae</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">). Khoai tây là loài cây nông nghiệp ngắn ngày, trồng lấy&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/C%E1%BB%A7\" title=\"Củ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">củ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;chứa&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Tinh_b%E1%BB%99t\" title=\"Tinh bột\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">tinh bột</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Chúng là loại cây trồng lấy củ rộng rãi nhất thế giới và là loại cây trồng phổ biến thứ tư về mặt sản lượng tươi - xếp sau&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/L%C3%BAa\" title=\"Lúa\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">lúa</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/L%C3%BAa_m%C3%AC\" title=\"Lúa mì\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">lúa mì</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ng%C3%B4\" title=\"Ngô\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">ngô</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span><sup id=\"cite_ref-1\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Khoai_t%C3%A2y#cite_note-1\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[1]</a></sup><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Lưu trữ khoai tây dài ngày đòi hỏi bảo quản trong điều kiện lạnh.</span>','Khoai tây vỏ hồng là giống khoai tây Đà Lạt được canh tác tại Mộc Châu. chứa nhiều chất dinh dưỡng, được nhiều người yêu thích. Đây là loại rau củ ít calo, không có chất béo và cholestrerol, hàm lượng vitamin cao và là nguồn cung cấp kali, vitamin B6 và chất xơ thô tuyệt vời.',50,20,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(43,11,'Bí xanh',40000,'<span style=\"color: rgb(85, 85, 85); font-family: Roboto, arial, helvetica, sans-serif; font-size: 15.3999996185303px; line-height: normal;\">Bí xanh, bí đao&nbsp;hay&nbsp;bí phấn&nbsp;hoặc&nbsp;bí trắng(Benincasa hispida),là loài thực vật thuộc&nbsp;họ Bầu bí&nbsp;dạng dây leo, quả ăn được, thường dùng nấu lên như một loại rau.</span>','Bí xanh là loại quả sạch có thể ăn luôn vỏm mỗi quả nặng khoảng 200-300gr, có vị ngọt nhe, thơm mát, có thể chế biến thành nhiều món ăn khác nhau như luộc, xào, nấu canh…',100,20,10,1439571600,1,6,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(44,12,'Cà rốt',15000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cà rốt</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p\" title=\"Danh pháp\" class=\"mw-disambig\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp khoa học</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Daucus carota</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;subsp.&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>sativus</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một loại cây có củ, thường có màu vàng cam, đỏ, vàng, trắng hay tía. Phần ăn được của cà rốt là củ, thực chất là&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=R%E1%BB%85_c%C3%A1i&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Rễ cái (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rễ cái</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;của nó, chứa nhiều tiền tố của vitamin A tốt cho mắt.</span>','Cà rốt chứa rất nhiều vitamin, đặc biệt là vitamin A, giúp tăng cường sức đề kháng của cơ thể. Vị dịu ngọt của cà rốt thích hợp với các món ăn được nhiều người yêu thích. Bạn có thể dùng cà rốt làm các món canh thập cẩm, rau xào, dưa góp, salad, nấu các món kho với thịt… và màu cam của cà rốt khiến cho món ăn trở nên hấp dẫn hơn bao giờ hết.',30,10,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(45,13,'Cà chua',18000,'<span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cà chua thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=H%E1%BB%8D_c%C3%A2y_B%E1%BA%A1ch_anh&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Họ cây Bạch anh (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ cây Bạch anh</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, các loại cây trong họ này thường phát triển từ 1 đến 3 mét chiều cao, có những cây thân mềm bò trên mặt đất hoặc dây leo trên thân cây khác ví dụ&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=C%C3%A2y_nho&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Cây nho (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">nho</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Họ cây này là một loại cây lâu năm trong môi trường sống bản địa của nó, nhưng nay nó được trồng như một loại cây hàng năm ở các vùng khí hậu ôn đới.</span>','Cà chua là một loại quả quen thuộc, nó có tính lưu huyết, giải độc, chống khát nước, thông tiểu tiện và tốt cho hệ tiêu hóa. Không chỉ là thực phẩm ngon tuyệt vời trong các món ăn mà cà chua còn là cứu tinh cho chị em phụ nữ trong việc làm đẹp.',30,12,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(46,13,'Chanh',10000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Chanh</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;là một số loài thực vật cho quả nhỏ, thuộc chi Cam chanh (</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><a href=\"https://vi.wikipedia.org/wiki/Chi_Cam_chanh\" title=\"Chi Cam chanh\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Citrus</a></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">), khi chín có màu xanh hoặc vàng, thịt quả có vị&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=Chua&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Chua (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">chua</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Quả chanh được sử dụng làm thực phẩm trên khắp thế giới - chủ yếu dùng nước ép của nó, thế nhưng phần cơm (các múi của chanh) và vỏ (zest) cũng được sử dụng, chủ yếu là trong nấu ăn và nướng bánh. Nước ép chanh chứa khoảng 5% (khoảng 0,3&nbsp;mol / lít)&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ax%C3%ADt_citric\" title=\"Axít citric\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">axit citric</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, điều này giúp chanh có vị chua, và độ pH của chanh từ 2-3. Điều này làm cho nước ép chanh không đắt, có thể sử dụng thay axít cho các thí nghiệm khoa học trong giáo dục. Bởi vì có vị chua, nhiều thức uống và kẹo có mùi vị đã xuất hiện, bao gồm cả nước chanh.</span>','Quả chanh và nước ép của nó chắc chắn có nhiều sức khỏe và lợi ích dinh dưỡng. Chúng cải thiện khả năng miễn dịch và tỷ lệ trao đổi chất, hỗ trợ tiêu hóa cũng như kiểm soát huyết áp. Thêm vào đó, một số nghiên cứu đã chỉ ra rằng quả chanh và nước ép chanh chứa nhiều chất có đặc tính chống ung thư mạnh.',30,10,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(47,14,'Mướp đắng',17000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Mướp đắng</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(tên&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Phi%C3%AAn_%C3%A2m_H%C3%A1n-Vi%E1%BB%87t\" title=\"Phiên âm Hán-Việt\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Hán-Việt</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">khổ qua</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;được dùng thông dụng ở&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Mi%E1%BB%81n_Nam_Vi%E1%BB%87t_Nam\" title=\"Miền Nam Việt Nam\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">miền Nam Việt Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, khổ 苦: đắng, qua 瓜: gọi chung các loại bầu, bí, mướp;&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Momordica charantia</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một cây leo mọc ở vùng&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Nhi%E1%BB%87t_%C4%91%E1%BB%9Bi\" title=\"Nhiệt đới\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">nhiệt đới</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/C%E1%BA%ADn_nhi%E1%BB%87t_%C4%91%E1%BB%9Bi\" title=\"Cận nhiệt đới\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">cận nhiệt đới</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_B%E1%BA%A7u_b%C3%AD\" title=\"Họ Bầu bí\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Bầu bí</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, có quả ăn được, thuộc loại đắng nhất trong các loại&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Rau\" title=\"Rau\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rau</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;quả.</span>','Mướp đắng hay còn gọi là khổ qua không chỉ là món ăn ngon, bổ dưỡng mà còn rất tốt cho sức khỏe như thanh lọc cơ thể, bổ phế, phòng chống các bệnh ung thư, giảm lượng đường trong máu, ổng định đường huyết, hay giảm béo…&nbsp;',30,12,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(48,15,'Dưa chuột',15000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Dưa chuột</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(tên khoa học&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Cucumis sativus</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) (</span><a href=\"https://vi.wikipedia.org/wiki/Nam_B%E1%BB%99_Vi%E1%BB%87t_Nam\" title=\"Nam Bộ Việt Nam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">miền Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;gọi là&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">dưa leo</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một cây trồng phổ biến trong họ&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/B%E1%BA%A7u_b%C3%AD\" title=\"Bầu bí\" class=\"mw-disambig\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bầu bí</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_B%E1%BA%A7u_b%C3%AD\" title=\"Họ Bầu bí\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Cucurbitaceae</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, là loại&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Rau\" title=\"Rau\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rau</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;ăn quả&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%C6%B0%C6%A1ng_m%E1%BA%A1i\" title=\"Thương mại\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">thương mại</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;quan trọng, nó được trồng lâu đời trên&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BA%BF_gi%E1%BB%9Bi\" title=\"Thế giới\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">thế giới</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và trở thành&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BB%B1c_ph%E1%BA%A9m\" title=\"Thực phẩm\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">thực phẩm</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;của nhiều nước. Những nước dẫn đầu về diện tích gieo trồng và năng suất là:&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Trung_Qu%E1%BB%91c\" title=\"Trung Quốc\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Trung Quốc</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Nga\" title=\"Nga\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Nga</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Nh%E1%BA%ADt_B%E1%BA%A3n\" title=\"Nhật Bản\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Nhật Bản</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Hoa_K%E1%BB%B3\" title=\"Hoa Kỳ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Mỹ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%C3%A0_Lan\" title=\"Hà Lan\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Hà Lan</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BB%95_Nh%C4%A9_K%E1%BB%B3\" title=\"Thổ Nhĩ Kỳ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Thổ Nhĩ Kỳ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ba_Lan\" title=\"Ba Lan\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ba Lan</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ai_C%E1%BA%ADp\" title=\"Ai Cập\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ai Cập</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/T%C3%A2y_Ban_Nha\" title=\"Tây Ban Nha\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Tây Ban Nha</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>','Dưa chuột được liệt vào loại rau quả ngon mà lại mang nhiều tác dụng rất tốt cho sắc đẹp và sức khỏe.&nbsp;',30,12,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(49,16,'Gừng',25000,'<span style=\"color: rgb(85, 85, 85); font-family: Roboto, arial, helvetica, sans-serif; font-size: 15.3999996185303px; line-height: normal;\">Theo Y học cổ truyền, gừng làm tản hàn, ôn phế, giải đờm, chống nôn, nên dùng lúc bị phong hàn ngoại cảm, ho nhiều đờm, giải độc, kích thích dạ dày, đau phong thấp, chống dị ứng</span>','Gừng là một loại gia vị nấu ăn không thể thiếu. Nó không chỉ giảm bớt mùi của thực phẩm mà còn giảm bớt nhiều thành phần có hại tiềm tàng trong thực phẩm. Gừng chứa đựng cả hai giá trị dinh dưỡng và y tế, vừa là thuốc vừa là nguyên liệu cho nhiều món ăn ngon miệng hơn.',40,15,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(50,17,'Bông cải xanh',25000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Bông cải xanh</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(hoặc&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">súp lơ xanh</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">cải bông xanh</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một loại cây thuộc loài&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/C%E1%BA%A3i_b%E1%BA%AFp_d%E1%BA%A1i\" title=\"Cải bắp dại\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Cải bắp dại</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, có hoa lớn ở đầu, thường được dùng như</span><a href=\"https://vi.wikipedia.org/wiki/Rau\" title=\"Rau\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rau</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Bông cải xanh thường được chế biến bằng cách luộc hoặc hấp, nhưng cũng có thể được ăn sống như là rau sống trong những đĩa&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Khai_v%E1%BB%8B\" title=\"Khai vị\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">đồ nguội khai vị</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>','<span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Bông cải xanh có chứa nhiều&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vitamin_A\" title=\"Vitamin A\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Vitamin A</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vitamin_C\" title=\"Vitamin C\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Vitamin C</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vitamin_K\" title=\"Vitamin K\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Vitamin K</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ch%E1%BA%A5t_x%C6%A1\" title=\"Chất xơ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">chất xơ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Quercetin\" title=\"Quercetin\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Quercetin</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Nó cũng chứa nhiều chất dinh dưỡng có khả năng chống&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ung_th%C6%B0\" title=\"Ung thư\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">ung thư</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;như Myrosinase, Sulforaphane, Di-indolyl&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/M%C3%AAtan\" title=\"Mêtan\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">mêtan</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và một lượng nhỏ&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Selen\" title=\"Selen\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">selen</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>',20,5,10,1439571600,1,7,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(51,18,'Hoa thiên lý',45000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Thiên lý</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Telosma cordata</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một loài thực vật dạng dây leo. Trong thiên nhiên, thiên lý mọc ở các cánh rừng thưa, nhiều cây bụi. Tuy nhiên, nó được gieo trồng ở nhiều nơi như&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Trung_Qu%E1%BB%91c\" title=\"Trung Quốc\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Trung Quốc</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Qu%E1%BA%A3ng_%C4%90%C3%B4ng\" title=\"Quảng Đông\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Quảng Đông</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Qu%E1%BA%A3ng_T%C3%A2y\" title=\"Quảng Tây\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Quảng Tây</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">),&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%E1%BA%A4n_%C4%90%E1%BB%99\" title=\"Ấn Độ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ấn Độ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Kashmir\" title=\"Kashmir\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Kashmir</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">),</span><a href=\"https://vi.wikipedia.org/wiki/Myanma\" title=\"Myanma\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Myanma</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Pakistan\" title=\"Pakistan\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Pakistan</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam\" title=\"Việt Nam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Việt Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">;&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ch%C3%A2u_%C3%82u\" title=\"Châu Âu\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">châu Âu</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/B%E1%BA%AFc_M%E1%BB%B9\" title=\"Bắc Mỹ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Bắc</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Nam_M%E1%BB%B9\" title=\"Nam Mỹ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Nam Mỹ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>','<p style=\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.3999996185303px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\">Hoa rất thơm, chứa tinh dầu. Chúng được sử dụng để nấu ăn và trong y học để điều trị&nbsp;<a href=\"https://vi.wikipedia.org/w/index.php?title=Vi%C3%AAm_m%C3%A0ng_k%E1%BA%BFt&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Viêm màng kết (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">viêm màng kết</a>.</p><p style=\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.3999996185303px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\">Tại&nbsp;<a href=\"https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam\" title=\"Việt Nam\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Việt Nam</a>&nbsp;cây hoa thiên lý được trồng trong vườn để leo thành giàn tạo bóng mát, hưởng hương thơm và nhất là để lấy hoa lẫn lá non nấu ăn. Phổ thông nhất là nấu canh.</p>',30,10,10,1439571600,1,7,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(52,19,'Dưa hấu',12000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Dưa hấu</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">tên khoa học</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Citrullus lanatus</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một loài thực vật trong&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_B%E1%BA%A7u_b%C3%AD\" title=\"Họ Bầu bí\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Bầu bí</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(Cucurbitaceae), một loại trái cây có vỏ cứng, chứa nhiều nước, có nguồn gốc từ miền nam&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ch%C3%A2u_Phi\" title=\"Châu Phi\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">châu Phi</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và là loại quả phổ biến nhất trong họ Bầu bí.</span>','<span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;Dưa hấu có tính hàn có thể dùng làm thức ăn giải nhiệt trong những ngày hè nóng nực.</span>',30,20,10,1439571600,1,8,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(53,20,'Xoài',10000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Xoài</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;là một loại trái cây vị ngọt thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Chi_Xo%C3%A0i\" title=\"Chi Xoài\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">chi Xoài</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, bao gồm rất nhiều quả cây nhiệt đới, được trồng chủ yếu như trái cây ăn được. Phần lớn các loài được tìm thấy trong tự nhiên là các loại xoài hoang dã. Tất cả đều thuộc họ thực vật có hoa&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Anacardiaceae\" title=\"Anacardiaceae\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Anacardiaceae</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Xoài có nguồn gốc ở&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Nam_%C3%81\" title=\"Nam Á\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Nam Á</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%C4%90%C3%B4ng_Nam_%C3%81\" title=\"Đông Nam Á\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Đông Nam Á</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, từ đó nó đã được phân phối trên toàn thế giới để trở thành một trong những loại trái cây được trồng hầu hết ở vùng nhiệt đới. Mật độ cao nhất của chi Xoài(Magifera) ở phía tây của&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Malesia\" title=\"Malesia\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Malesia</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Sumatra\" title=\"Sumatra\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Sumatra</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Java\" title=\"Java\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Java</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Borneo\" title=\"Borneo\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Borneo</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) và ở&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Myanmar\" title=\"Myanmar\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Myanmar</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%E1%BA%A4n_%C4%90%E1%BB%99\" title=\"Ấn Độ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ấn Độ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span><sup id=\"cite_ref-Morton_1-0\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Xo%C3%A0i#cite_note-Morton-1\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[1]</a></sup><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;Trong khi loài Mangifera khác (ví dụ như xoài ngựa, M. Foetida) cũng được phát triển trên cơ sở địa phương hơn,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Mangifera_indica\" title=\"Mangifera indica\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Mangifera indica</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, -\"xoài thường\" hoặc \"xoài Ấn Độ\"-là cây xoài thường chỉ được trồng ở nhiều vùng nhiệt đới và cận nhiệt đới. Nó có nguồn gốc ở&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%E1%BA%A4n_%C4%90%E1%BB%99\" title=\"Ấn Độ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ấn Độ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Myanmar\" title=\"Myanmar\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Myanmar</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span><sup id=\"cite_ref-mango_2-0\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Xo%C3%A0i#cite_note-mango-2\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[2]</a></sup><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;Nó là hoa quả quốc gia của&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%E1%BA%A4n_%C4%90%E1%BB%99\" title=\"Ấn Độ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ấn Độ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Pakistan\" title=\"Pakistan\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Pakistan</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Philippines\" title=\"Philippines\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Philippines</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, và cây quốc gia của&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Bangladesh\" title=\"Bangladesh\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Bangladesh</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span><sup id=\"cite_ref-bdnews24.com_3-0\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Xo%C3%A0i#cite_note-bdnews24.com-3\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[3]</a></sup><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;Trong một số nền văn hóa, trái cây và lá của nó được sử dụng như là nghi lễ trang trí tại các đám cưới, lễ kỷ niệm, và nghi lễ tôn giáo.</span>','<span style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 18px;\">Xoài là loại trái cây giàu dinh dưỡng, có tác dụng ngăn chặn ung thư, làm sạch da từ bên trong...</span>',20,15,10,1439571600,1,8,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(54,21,'Bưởi',18000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Bưởi</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Citrus maxima</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;</span><small style=\"color: rgb(37, 37, 37); font-family: sans-serif;\">(<a href=\"https://vi.wikipedia.org/w/index.php?title=Elmer_Drew_Merrill&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Elmer Drew Merrill (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Merr.</a>,&nbsp;<a href=\"https://vi.wikipedia.org/w/index.php?title=Nicolaas_Laurens_Burman&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Nicolaas Laurens Burman (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Burm. f.</a></small><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">), hay&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Citrus grandis</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;</span><small style=\"color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Carl_von_Linn%C3%A9\" title=\"Carl von Linné\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">L.</a></small><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, là một loại quả thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Chi_Cam_chanh\" title=\"Chi Cam chanh\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">chi Cam chanh</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, thường có màu xanh lục nhạt cho tới vàng khi chín, có múi dày, tép xốp, có vị ngọt hoặc chua ngọt tùy loại. Bưởi có nhiều kích thước tùy giống, chẳng hạn&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/B%C6%B0%E1%BB%9Fi_%C4%90oan_H%C3%B9ng\" title=\"Bưởi Đoan Hùng\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bưởi Đoan Hùng</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;chỉ có đường kính độ 15&nbsp;cm, trong khi&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/B%C6%B0%E1%BB%9Fi_N%C4%83m_Roi\" title=\"Bưởi Năm Roi\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bưởi Năm Roi</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=B%C6%B0%E1%BB%9Fi_T%C3%A2n_Tri%E1%BB%81u&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Bưởi Tân Triều (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bưởi Tân Triều</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Bi%C3%AAn_H%C3%B2a\" title=\"Biên Hòa\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Biên Hòa</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">),&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/B%C6%B0%E1%BB%9Fi_da_xanh\" title=\"Bưởi da xanh\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bưởi da xanh</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/B%E1%BA%BFn_Tre\" title=\"Bến Tre\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Bến Tre</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) và nhiều loại bưởi khác thường gặp ở&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam\" title=\"Việt Nam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Việt Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%C3%A1i_Lan\" title=\"Thái Lan\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Thái Lan</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;có đường kính khoảng 18–20&nbsp;cm.</span>','<h2 style=\"margin: 10px 0px 0px; padding: 10px 0px; list-style: none; outline: none; font-family: \'Times New Roman\', Times, serif; font-size: 15px; line-height: 18px; color: rgb(51, 51, 51); background-color: rgb(250, 250, 250);\">Với vị hơi chua, thanh thanh và tính hàn, trái bưởi không chỉ là một loại trái cây rất được yêu thích mà còn là một thần dược đối với nhiều căn bệnh khó chữa mà không phải ai cũng biết được.</h2>',30,20,10,1439571600,1,8,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(55,22,'Cam',20000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cam</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Citrus × sinensis</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là loài cây ăn quả cùng họ với&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/B%C6%B0%E1%BB%9Fi\" title=\"Bưởi\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bưởi</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Nó có quả nhỏ hơn quả bưởi, vỏ mỏng, khi chín thường có màu&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Da_cam\" title=\"Da cam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">da cam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, có vị ngọt hoặc hơi chua. Loài cam là một cây lai được trồng từ xưa, có thể lai giống giữa loài bưởi (</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Citrus maxima</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Qu%C3%BDt_h%E1%BB%93ng\" title=\"Quýt hồng\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">quýt</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Citrus reticulata</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">). Đây là cây nhỏ, cao đến khoảng 10&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/M%C3%A9t\" title=\"Mét\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">m</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, có cành gai và lá thường xanh dài khoảng 4-10&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Xentim%C3%A9t\" title=\"Xentimét\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">cm</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Cam bắt nguồn từ&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%C4%90%C3%B4ng_Nam_%C3%81\" title=\"Đông Nam Á\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Đông Nam Á</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, có thể từ&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%E1%BA%A4n_%C4%90%E1%BB%99\" title=\"Ấn Độ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ấn Độ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam\" title=\"Việt Nam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Việt Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;hay miền nam&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Trung_Qu%E1%BB%91c\" title=\"Trung Quốc\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Trung Quốc</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>','<span style=\"color: rgb(20, 24, 35); font-family: helvetica, arial, sans-serif; font-size: 14px; line-height: 19.3199996948242px;\">Cam là loại quả ngon và cung cấp một lượng vitamin C phong phú được các bà nội trợ tin dùng, tuy nhiên, cam còn là một vị thuốc chữa bệnh tuyệt vời mà có thể bạn chưa biết.</span>',30,15,10,1439571600,1,8,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(56,23,'Chôm chôm',25000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Chôm chôm</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Nephelium lappaceum</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là loài cây vùng nhiệt đới&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%C4%90%C3%B4ng_Nam_%C3%81\" title=\"Đông Nam Á\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Đông Nam Á</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_B%E1%BB%93_h%C3%B2n\" title=\"Họ Bồ hòn\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Bồ hòn</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">(Sapindaceae). Tên gọi&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">chôm chôm</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(hay&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">lôm chôm</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) tượng hình cho trạng thái lông của quả loài cây này. Lông cũng là đặc tính cơ bản trong việc đặt tên của người Trung Quốc:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">hồng mao đan</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, hay của người Mã Lai:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">rambutan</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(trái có lông). Các nước phương Tây mượn giọng đọc của Mã Lai để gọi cây/trái chôm chôm: Anh, Đức gọi là rambutan, Pháp gọi là ramboutan...</span>','<h2 class=\"summary\" style=\"margin-top: 0px; margin-right: 0px; margin-left: 0px; padding: 0px; font-size: 11pt; color: rgb(95, 95, 95); font-family: Arial, Helvetica, sans-serif; line-height: 25px !important; background-color: rgb(250, 250, 250);\">Quả chôm chôm rất nhiều lợi ích với sức khỏe. Thịt chôm chôm chứa rất nhiều chất xơ giúp cơ thể dễ dàng loại bỏ chất thải, ngăn ngừa viêm ruột thừa, sỏi thận, trĩ và ung thư ruột già</h2>',30,15,10,1439571600,1,8,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(57,24,'Sầu riêng',180000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Sầu riêng</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_khoa_h%E1%BB%8Dc\" title=\"Danh pháp khoa học\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp khoa học</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Durio</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;là một chi thực vật thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_C%E1%BA%A9m_qu%E1%BB%B3\" title=\"Họ Cẩm quỳ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Cẩm quỳ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><a href=\"https://vi.wikipedia.org/wiki/Malvaceae\" title=\"Malvaceae\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Malvaceae</a></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">),</span><sup id=\"cite_ref-GRIN_3-1\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Chi_S%E1%BA%A7u_ri%C3%AAng#cite_note-GRIN-3\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[3]</a></sup><sup id=\"cite_ref-APW_4-0\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Chi_S%E1%BA%A7u_ri%C3%AAng#cite_note-APW-4\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[4]</a></sup><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(mặc dù một số nhà phân loại học đặt&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Durio</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;vào một họ riêng biệt,&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Durionaceae</i><sup id=\"cite_ref-GRIN_3-2\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Chi_S%E1%BA%A7u_ri%C3%AAng#cite_note-GRIN-3\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[3]</a></sup><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">), được biết đến rộng rãi tại&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%C4%90%C3%B4ng_Nam_%C3%81\" title=\"Đông Nam Á\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Đông Nam Á</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>','<span style=\"margin: 0px; padding: 0px; font-family: HelveticaRegular; color: rgb(34, 34, 34); font-size: 15px; line-height: 19px; text-align: justify;\"><span style=\"margin: 0px; padding: 0px; font-family: HelveticaMedium !important;\"><span style=\"margin: 0px; padding: 0px; font-family: HelveticaRegular !important;\">Với mùi thơm nồng ngay cả khi chưa lột vỏ, sầu riêng là một loại trái cây vừa được yêu thích lại vừa bị ghét bỏ</span></span></span>',10,5,10,1439571600,1,8,6);



-- -------------------------------------------
-- TABLE DATA product_rating
-- -------------------------------------------
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(55,6,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(51,6,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(57,6,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(53,4,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(52,6,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(56,4,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(50,6,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(48,6,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(54,6,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(49,4,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(33,6,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(34,6,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(35,4,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(38,6,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(40,6,12);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(45,6,8);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(43,6,8);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(47,9,8);
INSERT INTO `product_rating` (`product_id`,`rating_id`,`customer_id`) VALUES
(39,6,8);



-- -------------------------------------------
-- TABLE DATA product_season
-- -------------------------------------------
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(33,9);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(34,9);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(37,9);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(38,9);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(47,9);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(51,9);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(56,9);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(57,9);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(39,10);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(41,10);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(42,10);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(43,10);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(47,10);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(48,10);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(50,10);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(52,10);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(53,10);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(54,10);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(55,10);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(35,11);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(36,11);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(40,11);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(44,11);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(45,11);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(46,11);
INSERT INTO `product_season` (`product_id`,`season_id`) VALUES
(49,11);



-- -------------------------------------------
-- TABLE DATA product_tag
-- -------------------------------------------
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,33);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(2,34);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,35);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,36);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,37);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,38);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(2,39);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(2,40);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,41);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,42);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,43);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,44);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,45);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,46);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,47);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,48);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(2,49);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,50);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(1,51);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(3,52);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(3,53);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(3,54);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(3,55);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(3,56);
INSERT INTO `product_tag` (`tag_id`,`product_id`) VALUES
(3,57);



-- -------------------------------------------
-- TABLE DATA rating
-- -------------------------------------------
INSERT INTO `rating` (`id`,`rating`,`description`) VALUES
(1,0.5,'Cực kỳ kém');
INSERT INTO `rating` (`id`,`rating`,`description`) VALUES
(2,1,'Rất kém');
INSERT INTO `rating` (`id`,`rating`,`description`) VALUES
(3,1.5,'Kém');
INSERT INTO `rating` (`id`,`rating`,`description`) VALUES
(4,2,'Ổn');
INSERT INTO `rating` (`id`,`rating`,`description`) VALUES
(5,2.5,'Tốt');
INSERT INTO `rating` (`id`,`rating`,`description`) VALUES
(6,3,'Rất tốt');
INSERT INTO `rating` (`id`,`rating`,`description`) VALUES
(7,3.5,'Cực kỳ tốt');
INSERT INTO `rating` (`id`,`rating`,`description`) VALUES
(8,4.5,'Hoàn Hảo');
INSERT INTO `rating` (`id`,`rating`,`description`) VALUES
(9,5,'Tuyệt vời');



-- -------------------------------------------
-- TABLE DATA season
-- -------------------------------------------
INSERT INTO `season` (`id`,`name`,`from`,`to`,`active`) VALUES
(8,'Mùa xuân',1427043600,1434906000,1);
INSERT INTO `season` (`id`,`name`,`from`,`to`,`active`) VALUES
(9,'Mùa hạ',1434992400,1442854800,1);
INSERT INTO `season` (`id`,`name`,`from`,`to`,`active`) VALUES
(10,'Mùa thu',1442941200,1450630800,1);
INSERT INTO `season` (`id`,`name`,`from`,`to`,`active`) VALUES
(11,'Mùa đông',1450717200,1458579600,1);



-- -------------------------------------------
-- TABLE DATA slide_show
-- -------------------------------------------
INSERT INTO `slide_show` (`id`,`path`,`title`,`description`,`active`,`product_id`) VALUES
(1,'jL-JuBh6Fqgsgwb8AP7OWNP9dLKCm9EH.jpg','Sale Off','Banner01',1,NULL);
INSERT INTO `slide_show` (`id`,`path`,`title`,`description`,`active`,`product_id`) VALUES
(2,'iuSeYr6uWTtSj-yW8gad0I-XYoNh4mrc.jpg','BestChoice','Banner02',1,NULL);



-- -------------------------------------------
-- TABLE DATA tag
-- -------------------------------------------
INSERT INTO `tag` (`id`,`name`) VALUES
(3,'Hoa quả tươi sạch');
INSERT INTO `tag` (`id`,`name`) VALUES
(2,'Rau ngon');
INSERT INTO `tag` (`id`,`name`) VALUES
(1,'Rau sạch');



-- -------------------------------------------
-- TABLE DATA unit
-- -------------------------------------------
INSERT INTO `unit` (`id`,`name`,`active`) VALUES
(6,'1kg',1);
INSERT INTO `unit` (`id`,`name`,`active`) VALUES
(8,'500g',1);



-- -------------------------------------------
-- TABLE DATA voucher
-- -------------------------------------------
INSERT INTO `voucher` (`id`,`name`,`code`,`discount`,`start_date`,`end_date`,`active`,`order_id`) VALUES
(1,'Mừng ngày Quốc Khánh','DA2015001',20,1439226000,1441126800,1,NULL);
INSERT INTO `voucher` (`id`,`name`,`code`,`discount`,`start_date`,`end_date`,`active`,`order_id`) VALUES
(2,'Trung Thu - Tết Đoàn Viên','DA2015002',30,1443114000,1443373200,1,NULL);
INSERT INTO `voucher` (`id`,`name`,`code`,`discount`,`start_date`,`end_date`,`active`,`order_id`) VALUES
(3,'Khai Giảng Năm Học Mới','DA2015003',40,1441040400,1438880400,1,NULL);
INSERT INTO `voucher` (`id`,`name`,`code`,`discount`,`start_date`,`end_date`,`active`,`order_id`) VALUES
(4,'Kỉ niệm 1 năm thành lập Công ty','DA2015004',50,1441040400,1443546000,1,NULL);



-- -------------------------------------------
-- VIEW `order_details_extend`
-- -------------------------------------------
CREATE OR REPLACE VIEW `order_details_extend` AS select `p`.`name` AS `name`,`p`.`tax` AS `tax`,`od`.`sell_price` AS `sell_price`,`od`.`quantity` AS `quantity`,`od`.`discount` AS `discount`,`o`.`id` AS `order_id`,`o`.`order_date` AS `order_date`,`o`.`receiving_date` AS `receiving_date` from ((`product` `p` join `order_details` `od` on((`p`.`id` = `od`.`product_id`))) join `order` `o` on((`o`.`id` = `od`.`order_id`)));

-- -------------------------------------------
-- VIEW `order_view`
-- -------------------------------------------
CREATE OR REPLACE VIEW `order_view` AS select `o`.`id` AS `order_id`,`o`.`order_date` AS `order_date`,`o`.`receiving_date` AS `receiving_date`,`o`.`shipping_fee` AS `shipping_fee`,`o`.`description` AS `description`,`g`.`full_name` AS `full_name`,`g`.`email` AS `email`,`g`.`phone_number` AS `phone_number`,`os`.`id` AS `order_status_id`,concat(`a`.`detail`,' - ',`d`.`name`,' - ',`c`.`name`) AS `address` from (((((`order` `o` join `order_status` `os` on((`o`.`order_status_id` = `os`.`id`))) join `guest` `g` on((`g`.`id` = `o`.`guest_id`))) join `order_address` `a` on((`o`.`order_address_id` = `a`.`id`))) join `district` `d` on((`d`.`id` = `a`.`district_id`))) join `city` `c` on((`c`.`id` = `d`.`city_id`)));

-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
SET SQL_MODE=@OLD_SQL_MODE;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------

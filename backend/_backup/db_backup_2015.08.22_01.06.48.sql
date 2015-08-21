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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
-- TABLE `tmp_product`
-- -------------------------------------------
DROP TABLE IF EXISTS `tmp_product`;
CREATE TABLE IF NOT EXISTS `tmp_product` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `last_used` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
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
(12,'dungnt','$2y$13$Jfy9ny5r9v7AylhdklbR8.8WEf6bEgO3vakBGhvS18Id34wQOxEmy','TVH4ZUp-bpH0nYL0hW_oAgYEn47VzOgj.jpg',639853200,'Male',NULL,NULL,1439571600,1439744400,1,12,13);
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
(8,'Nguyen Trung Dung','$2y$13$MxlC9lO8zZcduxtpfsNnNuxet9UfzBXVQsPIDZHfDdeYkKU83SrpW',1439571600,'Male',962931939,'dungnt01532@fpt.edu.vn','note','hg0WaM0BlRhNV5P0UGF4BI2FlJaohJvp.jpg',NULL,NULL,1439658000,1,14);
INSERT INTO `employee` (`id`,`full_name`,`password`,`dob`,`gender`,`phone_number`,`email`,`note`,`image`,`auth_key`,`password_reset_token`,`start_date`,`status`,`address_id`) VALUES
(9,'Lê Hồng Quân','$2y$13$EcPyMQ2RxP5ix4EsFEzFUuAw2OFZrlYF/BtCLrRfhvmwFASNeOOB6',1439658000,'Male',168111222333,'quanlh@fpt.edu.vn','Nhân viên bán hàng mới tuyển tháng 7/2015','j8FUL5JuuSBzfRkaOv5GDqbW-erkqgy5.jpg',NULL,NULL,1436893200,1,15);



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
(13,'Bui Ngoc Anh','anhbn@fpt.edu.vn',915343020);
INSERT INTO `guest` (`id`,`full_name`,`email`,`phone_number`) VALUES
(14,'Nguyễn Văn Cao','dungnt@gamal.com',0915343020);



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
-- TABLE DATA order
-- -------------------------------------------
INSERT INTO `order` (`id`,`order_date`,`receiving_date`,`shipping_fee`,`tax_amount`,`net_amount`,`description`,`guest_id`,`order_status_id`,`order_address_id`) VALUES
(1,1440090000,1440090000,0,3000,27000,'dsada',14,2,1);



-- -------------------------------------------
-- TABLE DATA order_address
-- -------------------------------------------
INSERT INTO `order_address` (`id`,`detail`,`district_id`) VALUES
(1,'d12eda',792);



-- -------------------------------------------
-- TABLE DATA order_details
-- -------------------------------------------
INSERT INTO `order_details` (`product_id`,`order_id`,`sell_price`,`quantity`,`discount`) VALUES
(39,1,30000,1,0);



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
(33,10337,'Rau ngót',10000,'<span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Rau ngót thuộc dạng cây bụi, có thể cao đến 2&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/M\" title=\"M\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">m</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, phần thân khi già cứng chuyển màu nâu.&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/L%C3%A1\" title=\"Lá\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Lá</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;cây rau ngót&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=H%C3%ACnh_b%E1%BA%A7u_d%E1%BB%A5c&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Hình bầu dục (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">hình bầu dục</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, mọc so le; sắc lá&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Xanh_l%C3%A1_c%C3%A2y\" title=\"Xanh lá cây\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">màu lục</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;thẫm. Khi hái ăn, thường chọn lá non. Vị rau tương tự như&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/M%C4%83ng_t%C3%A2y\" title=\"Măng tây\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">măng tây</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>','Rau ngót là loại rau nấu canh khoái khẩu lại có tác dụng cân bằng thân nhiệt cho cơ thể hiệu quả. Không giống như một số loại rau khác chỉ dùng lá non, lá rau ngót già có hàm lượng chất xơ và beta caroten càng dồi dào, giúp hệ tiêu hóa hoạt động trơn tru. Do vậy đừng bỏ đi những lá già có màu hơi sẫm, và nấu rau ngót với dầu thực vật để có thể hấp thụ beta caroten một cách hiệu quả hơn.',100,50,10,1439485200,1,5,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(34,10344,'Rau dền',10000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Rau dền</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, là tên gọi chung để chỉ các loài trong&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Chi Dền</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p\" title=\"Danh pháp\" class=\"mw-disambig\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp khoa học</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Amaranthus</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, bao gồm cả các danh pháp liên quan tới&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Acanthochiton</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Acnida</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Montelia</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) do ở&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam\" title=\"Việt Nam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Việt Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;thường được sử dụng làm rau. Chi Dền gồm những loài đều có hoa không tàn, một số mọc hoang dại nhưng nhiều loài được sử dụng làm&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BB%B1c_ph%E1%BA%A9m\" title=\"Thực phẩm\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">lương thực</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Rau\" title=\"Rau\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rau</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/C%C3%A2y_c%E1%BA%A3nh\" title=\"Cây cảnh\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">cây cảnh</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;ở các vùng khác nhau trên thế giới.</span>','Rau dền là loại rau mùa hè, có tác dụng mát gan, thanh nhiệt. Theo Đông y, rau đền đỏ vị ngọt, tính mát, có tác dụng thanh nhiệt, làm mát máu, lợi tiểu, sát trùng. Danh y Lý Thời Trân (thời Minh, Trung Quốc) cho rằng rau dền đỏ có tác dụng trị nhiệt lỵ, huyết nhiệt sinh mụn nhọt.',200,150,10,1439485200,1,5,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(35,10351,'Cần tây',17000,'<p><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cần tây</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_khoa_h%E1%BB%8Dc\" title=\"Danh pháp khoa học\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp khoa học</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Apium graveolens</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, là một loài thực vật thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_Hoa_t%C3%A1n\" title=\"Họ Hoa tán\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Hoa tán</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Loài này được&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Carl_von_Linn%C3%A9\" title=\"Carl von Linné\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Carl von Linné</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;mô tả khoa học đầu tiên năm 1753.</span></p><p><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cây cao, có tuổi thọ gần 2 năm, thân mọc thẳng đứng, cao tới 1,5 m, nhưng có nhiều rãnh dọc, chia nhiều cành mọc đứng. Lá ở gốc có cuống, hình thuôn hay 3 cạnh, dạng mắt chim, tù có khóa lượn tai bèo. Lá giữa và lá ngọn không có cuống, chia 3 hoặc xẻ 3 hoặc không chia thùy. Hoa gồm nhiều tán, các tán ở đầu cành có cuống dài hơn các tán bên. Không có tổng bao, hoa nhỏ màu trắng nhạt. Quả dạng trứng, hình cầu có vạch lồi chạy dọc</span><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><br></span></p>','Cần tây là loại rau quen thuộc trong bữa ăn hàng ngày. Không chỉ là nguyên liệu dùng để chế biến nhiều món ăn ngon, cần tây còn mang lại rất nhiều dưỡng chất thiết yếu cho cơ thể như các amino a-xít, boron, can-xi, folate, sắt, ma-giê, man-gan, phốt-pho, kali, selen, kẽm, vitamin A, một số loại vitamin B (như B1, B2 và B6), vitamin C, vitamin K và chất xơ. Nhờ vậy mà cần tây có khả năng phòng chống một số bệnh nguy hiểm.',100,30,10,1439571600,1,5,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(36,10368,'Cải chíp',10000,'<span style=\"color: rgb(85, 85, 85); font-family: Roboto, arial, helvetica, sans-serif; font-size: 15.3999996185303px; line-height: normal;\">Cải chíp (Brassica chinensis L.), thuộc họ thập tự (Cruciferae), là loại rau rất quen thuộc trong bữa ăn hàng ngày, rau chứa nhiều thành phần dinh dưỡng</span>','Cải chíp là loại rau rất quen thuộc trong bữa ăn hàng ngày, rau chứa nhiều thành phần dinh dưỡng như: vitamin A, B, C. Lượng vitamin C của rau đứng vào bậc nhất trong các loại rau.',100,40,10,1439571600,1,5,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(37,10375,'Rau muống',12000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Rau muống</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Ipomoea aquatica</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một loài thực vật&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Nhi%E1%BB%87t_%C4%91%E1%BB%9Bi\" title=\"Nhiệt đới\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">nhiệt đới</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BB%B1c_v%E1%BA%ADt_th%E1%BB%A7y_sinh\" title=\"Thực vật thủy sinh\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bán thủy sinh</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_B%C3%ACm_b%C3%ACm\" title=\"Họ Bìm bìm\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Bìm bìm</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">(Convolvulaceae), là một loại&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=Rau_%C4%83n_l%C3%A1&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Rau ăn lá (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rau ăn lá</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Phân bố tự nhiên chính xác của loài này hiện chưa rõ do được trồng phổ biến khắp các vùng nhiệt đới và cận nhiệt đới trên thế giới. Tại&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam\" title=\"Việt Nam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Việt Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, nó là một loại&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Rau\" title=\"Rau\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rau</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;rất phổ thông và rất được ưa chuộng.</span>','Rau muống là loại rau quen thuộc trong mùa hè. Ngoài công dụng là thực phẩm ngon miệng, giải nhiệt, rau muống còn có tác dụng giải độc, nhuận tràng, chữa rôm sảy, mụn nhọt …',50,15,10,1439571600,1,5,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(38,10382,'Bí ngọn',12000,'<p style=\"line-height: 28px; color: rgb(85, 85, 85); font-family: Roboto, arial, helvetica, sans-serif; font-size: 14px; text-align: justify;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: large; vertical-align: baseline; font-family: \'times new roman\', times; background: transparent;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 18px; vertical-align: baseline; color: rgb(0, 0, 255); background: transparent;\"><a href=\"http://fvf.vn/bi-ngon\" title=\"Bí ngọn\" style=\"margin: 0px; padding: 0px; vertical-align: baseline; color: rgb(100, 100, 100); word-wrap: break-word; transition: color 0.1s ease-in, background 0.1s ease-in; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; color: rgb(0, 0, 255); background: transparent;\"><strong style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent;\">Bí ngọn</strong></span></a></span>&nbsp;dùng làm rau ăn: xào, um (xào nước) hay nấu canh. Có tính thanh nhiệt, nhuận tràng nhờ chất xơ kích thích nhu động ruột.</span><br><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: large; vertical-align: baseline; font-family: \'times new roman\', times; background: transparent;\"></span></p><p style=\"line-height: 28px; color: rgb(85, 85, 85); font-family: Roboto, arial, helvetica, sans-serif; font-size: 14px; text-align: justify;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: large; vertical-align: baseline; font-family: \'times new roman\', times; background: transparent;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 18px; vertical-align: baseline; background: transparent;\">Ngọn bí là món ăn cung cấp nhiều vitamin, chất xơ cho cơ thể. Đặc biệt trong những lúc cơ thể phải quá tải với lượng thịt, mỡ vào những ngày Tết, thì những món ăn từ rau xanh luôn là lựa chọn tốt cho bạn.</span></span></p><p style=\"line-height: 28px; color: rgb(85, 85, 85); font-family: Roboto, arial, helvetica, sans-serif; font-size: 14px; text-align: justify;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: large; vertical-align: baseline; font-family: \'times new roman\', times; background: transparent;\">Món chay đọt bí đỏ nấu với cà chua. Ngọn bí và&nbsp;<a href=\"http://fvf.vn/ca-chua\" title=\"Cà chua\" style=\"margin: 0px; padding: 0px; font-size: 18px; vertical-align: baseline; color: rgb(100, 100, 100); word-wrap: break-word; transition: color 0.1s ease-in, background 0.1s ease-in; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; color: rgb(0, 0, 255); background: transparent;\"><strong style=\"margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent;\">cà chua</strong></span></a>&nbsp;đều thanh nhiệt, nhuận tràng. Đây là một kết hợp đồng vận vì cả hai đều có tính chống oxy-hoá ; tăng tính trị liệu cũng tăng khẩu vị. Khi trời nắng nóng nên ăn món này.</span></p>','Rau bí chính là phần ngọn bí. Rau bí có thể chế biến thành nhiều món ngon khác nhau như luộc, xào tỏi, nấu giò sống, xào thịt bò, xào hến, nấu canh...',20,5,10,1439571600,1,5,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(39,10399,'Bắp cải',30000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Bắp cải</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;hay&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">cải bắp</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><a href=\"https://vi.wikipedia.org/wiki/C%E1%BA%A3i_b%E1%BA%AFp_d%E1%BA%A1i\" title=\"Cải bắp dại\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Brassica oleracea</a></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;nhóm Capitata) là một loại rau chủ lực trong&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_C%E1%BA%A3i\" title=\"Họ Cải\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Cải</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(còn gọi là họ Thập tự - Brassicaceae/Cruciferae), phát sinh từ vùng&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%C4%90%E1%BB%8Ba_Trung_H%E1%BA%A3i\" title=\"Địa Trung Hải\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Địa Trung Hải</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Nó là cây thân thảo, sống hai năm, và là một&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BB%B1c_v%E1%BA%ADt_c%C3%B3_hoa\" title=\"Thực vật có hoa\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">thực vật có hoa</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;thuộc nhóm</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BB%B1c_v%E1%BA%ADt_hai_l%C3%A1_m%E1%BA%A7m\" title=\"Thực vật hai lá mầm\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">hai lá mầm</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;với các lá tạo thành một cụm đặc hình gần như hình cầu đặc trưng.</span>','Không chỉ là loại rau ngon, dễ chế biến trong mùa đông, cải bắp còn có khá nhiều công dụng chữa bệnh như phòng chống bệnh đường tiêu hóa, phòng tiểu đường và béo phì.',50,20,10,1439571600,1,5,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(40,10405,'Cải ngọt',10000,'<p><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cải ngọt</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;có tên khoa học là&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Brassica integrifolia</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_C%E1%BA%A3i\" title=\"Họ Cải\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Họ Cải</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(Brassicaceae), thường được trồng để dùng làm rau ăn.</span></p><p><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cải ngọt</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;có nguồn gốc từ&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%E1%BA%A4n_%C4%90%E1%BB%99\" title=\"Ấn Độ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ấn Độ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Trung_Qu%E1%BB%91c\" title=\"Trung Quốc\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Trung Quốc</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Cây thảo, cao tới 50 - 100&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Cm\" title=\"Cm\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">cm</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, thân tròn, không lông, lá có phiến xoan ngược tròn dài, đầu tròn hay tù, gốc từ từ hẹp, mép nguyên không nhăn, mập, trắng trắng, gân bên 5 - 6 đôi, cuống dài, tròn. Chùm hoa như ngù ở ngọn, cuống hoa dài 3 – 5&nbsp;cm, hoa vàng tươi, quả cải dài 4 – 11&nbsp;cm, có mỏ, hạt tròn. Cải ngọt được trồng quanh năm, thời gian sinh trưởng.</span><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><br></span></p>','Cải ngọt là loài rau thuộc họ cải, rất dễ ăn và giàu chất dinh dưỡng. Theo Đông y, cải ngọt tính ôn, có công dụng thông lợi trường vị, làm đỡ tức ngực, tiêu thực hạ khí... có thể dùng để chữa các chứng ho, táo bón, ăn nhiều cải ngọt giúp cho việc phòng ngừa bệnh trĩ và ung thư ruột kết.&nbsp;',30,15,10,1439571600,1,5,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(41,10412,'Khoai sọ',15000,'<p style=\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.3999996185303px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\"><b>Khoai sọ</b>&nbsp;là tên gọi của một số giống khoai thuộc loài&nbsp;<i><b><a href=\"https://vi.wikipedia.org/wiki/Colocasia_esculenta\" title=\"Colocasia esculenta\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Colocasia esculenta</a></b></i>&nbsp;<small>(L.) Schott</small>, một loài cây thuộc&nbsp;<a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_R%C3%A1y\" title=\"Họ Ráy\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">họ Ráy</a>&nbsp;(<i>Araceae</i>).</p><p style=\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.3999996185303px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\">Cây khoai sọ có củ cái và củ con. Khác với khoai môn, củ cái khoai sọ nhỏ, nhiều củ con, nhiều tinh bột. Nhóm khoai sọ thích hợp với các loại đất thịt nhẹ, cát pha, giàu mùn, thoát nước tốt, chủ yếu được trồng ở vùng đồng bằng và trung du.</p>','Khoai sọ là thực phẩm chứa nhiều chất dinh dưỡng như: tinh bột, protid, lipid, galactose, Ca, P, F; các vitamin A, B, C và nhiều &nbsp;axit amin cần thiết cho cơ thể.&nbsp;',50,20,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(42,10429,'Khoai tây vỏ hồng',18000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Khoai tây</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Solanum tuberosum</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">), thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_C%C3%A0\" title=\"Họ Cà\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Cà</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Solanaceae</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">). Khoai tây là loài cây nông nghiệp ngắn ngày, trồng lấy&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/C%E1%BB%A7\" title=\"Củ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">củ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;chứa&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Tinh_b%E1%BB%99t\" title=\"Tinh bột\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">tinh bột</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Chúng là loại cây trồng lấy củ rộng rãi nhất thế giới và là loại cây trồng phổ biến thứ tư về mặt sản lượng tươi - xếp sau&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/L%C3%BAa\" title=\"Lúa\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">lúa</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/L%C3%BAa_m%C3%AC\" title=\"Lúa mì\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">lúa mì</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ng%C3%B4\" title=\"Ngô\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">ngô</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span><sup id=\"cite_ref-1\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Khoai_t%C3%A2y#cite_note-1\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[1]</a></sup><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Lưu trữ khoai tây dài ngày đòi hỏi bảo quản trong điều kiện lạnh.</span>','Khoai tây vỏ hồng là giống khoai tây Đà Lạt được canh tác tại Mộc Châu. chứa nhiều chất dinh dưỡng, được nhiều người yêu thích. Đây là loại rau củ ít calo, không có chất béo và cholestrerol, hàm lượng vitamin cao và là nguồn cung cấp kali, vitamin B6 và chất xơ thô tuyệt vời.',50,20,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(43,10436,'Bí xanh',40000,'<span style=\"color: rgb(85, 85, 85); font-family: Roboto, arial, helvetica, sans-serif; font-size: 15.3999996185303px; line-height: normal;\">Bí xanh, bí đao&nbsp;hay&nbsp;bí phấn&nbsp;hoặc&nbsp;bí trắng(Benincasa hispida),là loài thực vật thuộc&nbsp;họ Bầu bí&nbsp;dạng dây leo, quả ăn được, thường dùng nấu lên như một loại rau.</span>','Bí xanh là loại quả sạch có thể ăn luôn vỏm mỗi quả nặng khoảng 200-300gr, có vị ngọt nhe, thơm mát, có thể chế biến thành nhiều món ăn khác nhau như luộc, xào, nấu canh…',100,20,10,1439571600,1,6,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(44,10443,'Cà rốt',15000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cà rốt</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p\" title=\"Danh pháp\" class=\"mw-disambig\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp khoa học</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Daucus carota</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;subsp.&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>sativus</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một loại cây có củ, thường có màu vàng cam, đỏ, vàng, trắng hay tía. Phần ăn được của cà rốt là củ, thực chất là&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=R%E1%BB%85_c%C3%A1i&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Rễ cái (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rễ cái</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;của nó, chứa nhiều tiền tố của vitamin A tốt cho mắt.</span>','Cà rốt chứa rất nhiều vitamin, đặc biệt là vitamin A, giúp tăng cường sức đề kháng của cơ thể. Vị dịu ngọt của cà rốt thích hợp với các món ăn được nhiều người yêu thích. Bạn có thể dùng cà rốt làm các món canh thập cẩm, rau xào, dưa góp, salad, nấu các món kho với thịt… và màu cam của cà rốt khiến cho món ăn trở nên hấp dẫn hơn bao giờ hết.',30,10,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(45,10456,'Cà chua',18000,'<span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cà chua thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=H%E1%BB%8D_c%C3%A2y_B%E1%BA%A1ch_anh&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Họ cây Bạch anh (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ cây Bạch anh</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, các loại cây trong họ này thường phát triển từ 1 đến 3 mét chiều cao, có những cây thân mềm bò trên mặt đất hoặc dây leo trên thân cây khác ví dụ&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=C%C3%A2y_nho&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Cây nho (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">nho</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Họ cây này là một loại cây lâu năm trong môi trường sống bản địa của nó, nhưng nay nó được trồng như một loại cây hàng năm ở các vùng khí hậu ôn đới.</span>','Cà chua là một loại quả quen thuộc, nó có tính lưu huyết, giải độc, chống khát nước, thông tiểu tiện và tốt cho hệ tiêu hóa. Không chỉ là thực phẩm ngon tuyệt vời trong các món ăn mà cà chua còn là cứu tinh cho chị em phụ nữ trong việc làm đẹp.',30,12,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(46,10467,'Chanh',10000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Chanh</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;là một số loài thực vật cho quả nhỏ, thuộc chi Cam chanh (</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><a href=\"https://vi.wikipedia.org/wiki/Chi_Cam_chanh\" title=\"Chi Cam chanh\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Citrus</a></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">), khi chín có màu xanh hoặc vàng, thịt quả có vị&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=Chua&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Chua (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">chua</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Quả chanh được sử dụng làm thực phẩm trên khắp thế giới - chủ yếu dùng nước ép của nó, thế nhưng phần cơm (các múi của chanh) và vỏ (zest) cũng được sử dụng, chủ yếu là trong nấu ăn và nướng bánh. Nước ép chanh chứa khoảng 5% (khoảng 0,3&nbsp;mol / lít)&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ax%C3%ADt_citric\" title=\"Axít citric\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">axit citric</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, điều này giúp chanh có vị chua, và độ pH của chanh từ 2-3. Điều này làm cho nước ép chanh không đắt, có thể sử dụng thay axít cho các thí nghiệm khoa học trong giáo dục. Bởi vì có vị chua, nhiều thức uống và kẹo có mùi vị đã xuất hiện, bao gồm cả nước chanh.</span>','Quả chanh và nước ép của nó chắc chắn có nhiều sức khỏe và lợi ích dinh dưỡng. Chúng cải thiện khả năng miễn dịch và tỷ lệ trao đổi chất, hỗ trợ tiêu hóa cũng như kiểm soát huyết áp. Thêm vào đó, một số nghiên cứu đã chỉ ra rằng quả chanh và nước ép chanh chứa nhiều chất có đặc tính chống ung thư mạnh.',30,10,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(47,10474,'Mướp đắng',17000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Mướp đắng</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(tên&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Phi%C3%AAn_%C3%A2m_H%C3%A1n-Vi%E1%BB%87t\" title=\"Phiên âm Hán-Việt\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Hán-Việt</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">khổ qua</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;được dùng thông dụng ở&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Mi%E1%BB%81n_Nam_Vi%E1%BB%87t_Nam\" title=\"Miền Nam Việt Nam\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">miền Nam Việt Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, khổ 苦: đắng, qua 瓜: gọi chung các loại bầu, bí, mướp;&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Momordica charantia</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một cây leo mọc ở vùng&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Nhi%E1%BB%87t_%C4%91%E1%BB%9Bi\" title=\"Nhiệt đới\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">nhiệt đới</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/C%E1%BA%ADn_nhi%E1%BB%87t_%C4%91%E1%BB%9Bi\" title=\"Cận nhiệt đới\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">cận nhiệt đới</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_B%E1%BA%A7u_b%C3%AD\" title=\"Họ Bầu bí\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Bầu bí</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, có quả ăn được, thuộc loại đắng nhất trong các loại&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Rau\" title=\"Rau\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rau</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;quả.</span>','Mướp đắng hay còn gọi là khổ qua không chỉ là món ăn ngon, bổ dưỡng mà còn rất tốt cho sức khỏe như thanh lọc cơ thể, bổ phế, phòng chống các bệnh ung thư, giảm lượng đường trong máu, ổng định đường huyết, hay giảm béo…&nbsp;',30,12,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(48,10481,'Dưa chuột',15000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Dưa chuột</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(tên khoa học&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Cucumis sativus</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) (</span><a href=\"https://vi.wikipedia.org/wiki/Nam_B%E1%BB%99_Vi%E1%BB%87t_Nam\" title=\"Nam Bộ Việt Nam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">miền Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;gọi là&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">dưa leo</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một cây trồng phổ biến trong họ&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/B%E1%BA%A7u_b%C3%AD\" title=\"Bầu bí\" class=\"mw-disambig\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bầu bí</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_B%E1%BA%A7u_b%C3%AD\" title=\"Họ Bầu bí\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Cucurbitaceae</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, là loại&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Rau\" title=\"Rau\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rau</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;ăn quả&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%C6%B0%C6%A1ng_m%E1%BA%A1i\" title=\"Thương mại\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">thương mại</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;quan trọng, nó được trồng lâu đời trên&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BA%BF_gi%E1%BB%9Bi\" title=\"Thế giới\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">thế giới</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và trở thành&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BB%B1c_ph%E1%BA%A9m\" title=\"Thực phẩm\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">thực phẩm</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;của nhiều nước. Những nước dẫn đầu về diện tích gieo trồng và năng suất là:&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Trung_Qu%E1%BB%91c\" title=\"Trung Quốc\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Trung Quốc</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Nga\" title=\"Nga\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Nga</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Nh%E1%BA%ADt_B%E1%BA%A3n\" title=\"Nhật Bản\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Nhật Bản</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Hoa_K%E1%BB%B3\" title=\"Hoa Kỳ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Mỹ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%C3%A0_Lan\" title=\"Hà Lan\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Hà Lan</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%E1%BB%95_Nh%C4%A9_K%E1%BB%B3\" title=\"Thổ Nhĩ Kỳ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Thổ Nhĩ Kỳ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ba_Lan\" title=\"Ba Lan\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ba Lan</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ai_C%E1%BA%ADp\" title=\"Ai Cập\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ai Cập</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/T%C3%A2y_Ban_Nha\" title=\"Tây Ban Nha\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Tây Ban Nha</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>','Dưa chuột được liệt vào loại rau quả ngon mà lại mang nhiều tác dụng rất tốt cho sắc đẹp và sức khỏe.&nbsp;',30,12,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(49,10498,'Gừng',25000,'<span style=\"color: rgb(85, 85, 85); font-family: Roboto, arial, helvetica, sans-serif; font-size: 15.3999996185303px; line-height: normal;\">Theo Y học cổ truyền, gừng làm tản hàn, ôn phế, giải đờm, chống nôn, nên dùng lúc bị phong hàn ngoại cảm, ho nhiều đờm, giải độc, kích thích dạ dày, đau phong thấp, chống dị ứng</span>','Gừng là một loại gia vị nấu ăn không thể thiếu. Nó không chỉ giảm bớt mùi của thực phẩm mà còn giảm bớt nhiều thành phần có hại tiềm tàng trong thực phẩm. Gừng chứa đựng cả hai giá trị dinh dưỡng và y tế, vừa là thuốc vừa là nguyên liệu cho nhiều món ăn ngon miệng hơn.',40,15,10,1439571600,1,6,8);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(50,10504,'Bông cải xanh',25000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Bông cải xanh</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(hoặc&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">súp lơ xanh</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">cải bông xanh</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một loại cây thuộc loài&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/C%E1%BA%A3i_b%E1%BA%AFp_d%E1%BA%A1i\" title=\"Cải bắp dại\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Cải bắp dại</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, có hoa lớn ở đầu, thường được dùng như</span><a href=\"https://vi.wikipedia.org/wiki/Rau\" title=\"Rau\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">rau</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Bông cải xanh thường được chế biến bằng cách luộc hoặc hấp, nhưng cũng có thể được ăn sống như là rau sống trong những đĩa&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Khai_v%E1%BB%8B\" title=\"Khai vị\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">đồ nguội khai vị</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>','<span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Bông cải xanh có chứa nhiều&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vitamin_A\" title=\"Vitamin A\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Vitamin A</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vitamin_C\" title=\"Vitamin C\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Vitamin C</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vitamin_K\" title=\"Vitamin K\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Vitamin K</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ch%E1%BA%A5t_x%C6%A1\" title=\"Chất xơ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">chất xơ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Quercetin\" title=\"Quercetin\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Quercetin</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Nó cũng chứa nhiều chất dinh dưỡng có khả năng chống&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ung_th%C6%B0\" title=\"Ung thư\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">ung thư</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;như Myrosinase, Sulforaphane, Di-indolyl&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/M%C3%AAtan\" title=\"Mêtan\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">mêtan</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và một lượng nhỏ&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Selen\" title=\"Selen\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">selen</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>',20,5,10,1439571600,1,7,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(51,10511,'Hoa thiên lý',45000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Thiên lý</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Telosma cordata</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một loài thực vật dạng dây leo. Trong thiên nhiên, thiên lý mọc ở các cánh rừng thưa, nhiều cây bụi. Tuy nhiên, nó được gieo trồng ở nhiều nơi như&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Trung_Qu%E1%BB%91c\" title=\"Trung Quốc\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Trung Quốc</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Qu%E1%BA%A3ng_%C4%90%C3%B4ng\" title=\"Quảng Đông\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Quảng Đông</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Qu%E1%BA%A3ng_T%C3%A2y\" title=\"Quảng Tây\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Quảng Tây</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">),&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%E1%BA%A4n_%C4%90%E1%BB%99\" title=\"Ấn Độ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ấn Độ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Kashmir\" title=\"Kashmir\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Kashmir</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">),</span><a href=\"https://vi.wikipedia.org/wiki/Myanma\" title=\"Myanma\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Myanma</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Pakistan\" title=\"Pakistan\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Pakistan</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam\" title=\"Việt Nam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Việt Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">;&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ch%C3%A2u_%C3%82u\" title=\"Châu Âu\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">châu Âu</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/B%E1%BA%AFc_M%E1%BB%B9\" title=\"Bắc Mỹ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Bắc</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Nam_M%E1%BB%B9\" title=\"Nam Mỹ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Nam Mỹ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>','<p style=\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.3999996185303px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\">Hoa rất thơm, chứa tinh dầu. Chúng được sử dụng để nấu ăn và trong y học để điều trị&nbsp;<a href=\"https://vi.wikipedia.org/w/index.php?title=Vi%C3%AAm_m%C3%A0ng_k%E1%BA%BFt&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Viêm màng kết (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">viêm màng kết</a>.</p><p style=\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.3999996185303px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\">Tại&nbsp;<a href=\"https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam\" title=\"Việt Nam\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Việt Nam</a>&nbsp;cây hoa thiên lý được trồng trong vườn để leo thành giàn tạo bóng mát, hưởng hương thơm và nhất là để lấy hoa lẫn lá non nấu ăn. Phổ thông nhất là nấu canh.</p>',30,10,10,1439571600,1,7,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(52,10528,'Dưa hấu',12000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Dưa hấu</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">tên khoa học</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Citrullus lanatus</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là một loài thực vật trong&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_B%E1%BA%A7u_b%C3%AD\" title=\"Họ Bầu bí\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Bầu bí</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(Cucurbitaceae), một loại trái cây có vỏ cứng, chứa nhiều nước, có nguồn gốc từ miền nam&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Ch%C3%A2u_Phi\" title=\"Châu Phi\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">châu Phi</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và là loại quả phổ biến nhất trong họ Bầu bí.</span>','<span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;Dưa hấu có tính hàn có thể dùng làm thức ăn giải nhiệt trong những ngày hè nóng nực.</span>',30,20,10,1439571600,1,8,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(53,10535,'Xoài',10000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Xoài</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;là một loại trái cây vị ngọt thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Chi_Xo%C3%A0i\" title=\"Chi Xoài\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">chi Xoài</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, bao gồm rất nhiều quả cây nhiệt đới, được trồng chủ yếu như trái cây ăn được. Phần lớn các loài được tìm thấy trong tự nhiên là các loại xoài hoang dã. Tất cả đều thuộc họ thực vật có hoa&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Anacardiaceae\" title=\"Anacardiaceae\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Anacardiaceae</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Xoài có nguồn gốc ở&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Nam_%C3%81\" title=\"Nam Á\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Nam Á</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%C4%90%C3%B4ng_Nam_%C3%81\" title=\"Đông Nam Á\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Đông Nam Á</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, từ đó nó đã được phân phối trên toàn thế giới để trở thành một trong những loại trái cây được trồng hầu hết ở vùng nhiệt đới. Mật độ cao nhất của chi Xoài(Magifera) ở phía tây của&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Malesia\" title=\"Malesia\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Malesia</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Sumatra\" title=\"Sumatra\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Sumatra</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Java\" title=\"Java\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Java</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Borneo\" title=\"Borneo\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Borneo</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) và ở&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Myanmar\" title=\"Myanmar\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Myanmar</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%E1%BA%A4n_%C4%90%E1%BB%99\" title=\"Ấn Độ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ấn Độ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span><sup id=\"cite_ref-Morton_1-0\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Xo%C3%A0i#cite_note-Morton-1\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[1]</a></sup><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;Trong khi loài Mangifera khác (ví dụ như xoài ngựa, M. Foetida) cũng được phát triển trên cơ sở địa phương hơn,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Mangifera_indica\" title=\"Mangifera indica\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Mangifera indica</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, -\"xoài thường\" hoặc \"xoài Ấn Độ\"-là cây xoài thường chỉ được trồng ở nhiều vùng nhiệt đới và cận nhiệt đới. Nó có nguồn gốc ở&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%E1%BA%A4n_%C4%90%E1%BB%99\" title=\"Ấn Độ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ấn Độ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Myanmar\" title=\"Myanmar\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Myanmar</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span><sup id=\"cite_ref-mango_2-0\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Xo%C3%A0i#cite_note-mango-2\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[2]</a></sup><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;Nó là hoa quả quốc gia của&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%E1%BA%A4n_%C4%90%E1%BB%99\" title=\"Ấn Độ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ấn Độ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Pakistan\" title=\"Pakistan\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Pakistan</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Philippines\" title=\"Philippines\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Philippines</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, và cây quốc gia của&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Bangladesh\" title=\"Bangladesh\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Bangladesh</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span><sup id=\"cite_ref-bdnews24.com_3-0\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Xo%C3%A0i#cite_note-bdnews24.com-3\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[3]</a></sup><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;Trong một số nền văn hóa, trái cây và lá của nó được sử dụng như là nghi lễ trang trí tại các đám cưới, lễ kỷ niệm, và nghi lễ tôn giáo.</span>','<span style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 18px;\">Xoài là loại trái cây giàu dinh dưỡng, có tác dụng ngăn chặn ung thư, làm sạch da từ bên trong...</span>',20,15,10,1439571600,1,8,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(54,10542,'Bưởi',18000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Bưởi</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Citrus maxima</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;</span><small style=\"color: rgb(37, 37, 37); font-family: sans-serif;\">(<a href=\"https://vi.wikipedia.org/w/index.php?title=Elmer_Drew_Merrill&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Elmer Drew Merrill (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Merr.</a>,&nbsp;<a href=\"https://vi.wikipedia.org/w/index.php?title=Nicolaas_Laurens_Burman&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Nicolaas Laurens Burman (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Burm. f.</a></small><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">), hay&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Citrus grandis</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;</span><small style=\"color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Carl_von_Linn%C3%A9\" title=\"Carl von Linné\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">L.</a></small><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, là một loại quả thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Chi_Cam_chanh\" title=\"Chi Cam chanh\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">chi Cam chanh</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, thường có màu xanh lục nhạt cho tới vàng khi chín, có múi dày, tép xốp, có vị ngọt hoặc chua ngọt tùy loại. Bưởi có nhiều kích thước tùy giống, chẳng hạn&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/B%C6%B0%E1%BB%9Fi_%C4%90oan_H%C3%B9ng\" title=\"Bưởi Đoan Hùng\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bưởi Đoan Hùng</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;chỉ có đường kính độ 15&nbsp;cm, trong khi&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/B%C6%B0%E1%BB%9Fi_N%C4%83m_Roi\" title=\"Bưởi Năm Roi\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bưởi Năm Roi</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/w/index.php?title=B%C6%B0%E1%BB%9Fi_T%C3%A2n_Tri%E1%BB%81u&amp;action=edit&amp;redlink=1\" class=\"new\" title=\"Bưởi Tân Triều (trang chưa được viết)\" style=\"color: rgb(165, 88, 88); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bưởi Tân Triều</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Bi%C3%AAn_H%C3%B2a\" title=\"Biên Hòa\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Biên Hòa</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">),&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/B%C6%B0%E1%BB%9Fi_da_xanh\" title=\"Bưởi da xanh\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bưởi da xanh</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/B%E1%BA%BFn_Tre\" title=\"Bến Tre\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Bến Tre</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) và nhiều loại bưởi khác thường gặp ở&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam\" title=\"Việt Nam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Việt Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Th%C3%A1i_Lan\" title=\"Thái Lan\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Thái Lan</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;có đường kính khoảng 18–20&nbsp;cm.</span>','<h2 style=\"margin: 10px 0px 0px; padding: 10px 0px; list-style: none; outline: none; font-family: \'Times New Roman\', Times, serif; font-size: 15px; line-height: 18px; color: rgb(51, 51, 51); background-color: rgb(250, 250, 250);\">Với vị hơi chua, thanh thanh và tính hàn, trái bưởi không chỉ là một loại trái cây rất được yêu thích mà còn là một thần dược đối với nhiều căn bệnh khó chữa mà không phải ai cũng biết được.</h2>',30,20,10,1439571600,1,8,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(55,10559,'Cam',20000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Cam</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Citrus × sinensis</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là loài cây ăn quả cùng họ với&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/B%C6%B0%E1%BB%9Fi\" title=\"Bưởi\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">bưởi</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Nó có quả nhỏ hơn quả bưởi, vỏ mỏng, khi chín thường có màu&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Da_cam\" title=\"Da cam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">da cam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, có vị ngọt hoặc hơi chua. Loài cam là một cây lai được trồng từ xưa, có thể lai giống giữa loài bưởi (</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Citrus maxima</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) và&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Qu%C3%BDt_h%E1%BB%93ng\" title=\"Quýt hồng\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">quýt</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Citrus reticulata</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">). Đây là cây nhỏ, cao đến khoảng 10&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/M%C3%A9t\" title=\"Mét\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">m</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, có cành gai và lá thường xanh dài khoảng 4-10&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Xentim%C3%A9t\" title=\"Xentimét\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">cm</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">. Cam bắt nguồn từ&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%C4%90%C3%B4ng_Nam_%C3%81\" title=\"Đông Nam Á\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Đông Nam Á</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, có thể từ&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%E1%BA%A4n_%C4%90%E1%BB%99\" title=\"Ấn Độ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Ấn Độ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">,&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam\" title=\"Việt Nam\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Việt Nam</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;hay miền nam&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/Trung_Qu%E1%BB%91c\" title=\"Trung Quốc\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Trung Quốc</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>','<span style=\"color: rgb(20, 24, 35); font-family: helvetica, arial, sans-serif; font-size: 14px; line-height: 19.3199996948242px;\">Cam là loại quả ngon và cung cấp một lượng vitamin C phong phú được các bà nội trợ tin dùng, tuy nhiên, cam còn là một vị thuốc chữa bệnh tuyệt vời mà có thể bạn chưa biết.</span>',30,15,10,1439571600,1,8,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(56,10566,'Chôm chôm',25000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Chôm chôm</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_hai_ph%E1%BA%A7n\" title=\"Danh pháp hai phần\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp hai phần</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Nephelium lappaceum</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) là loài cây vùng nhiệt đới&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%C4%90%C3%B4ng_Nam_%C3%81\" title=\"Đông Nam Á\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Đông Nam Á</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_B%E1%BB%93_h%C3%B2n\" title=\"Họ Bồ hòn\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Bồ hòn</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">(Sapindaceae). Tên gọi&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">chôm chôm</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(hay&nbsp;</span><b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">lôm chôm</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">) tượng hình cho trạng thái lông của quả loài cây này. Lông cũng là đặc tính cơ bản trong việc đặt tên của người Trung Quốc:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">hồng mao đan</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">, hay của người Mã Lai:&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">rambutan</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(trái có lông). Các nước phương Tây mượn giọng đọc của Mã Lai để gọi cây/trái chôm chôm: Anh, Đức gọi là rambutan, Pháp gọi là ramboutan...</span>','<h2 class=\"summary\" style=\"margin-top: 0px; margin-right: 0px; margin-left: 0px; padding: 0px; font-size: 11pt; color: rgb(95, 95, 95); font-family: Arial, Helvetica, sans-serif; line-height: 25px !important; background-color: rgb(250, 250, 250);\">Quả chôm chôm rất nhiều lợi ích với sức khỏe. Thịt chôm chôm chứa rất nhiều chất xơ giúp cơ thể dễ dàng loại bỏ chất thải, ngăn ngừa viêm ruột thừa, sỏi thận, trĩ và ung thư ruột già</h2>',30,15,10,1439571600,1,8,6);
INSERT INTO `product` (`id`,`barcode`,`name`,`price`,`description`,`intro`,`quantity_in_stock`,`sold`,`tax`,`create_date`,`active`,`category_id`,`unit_id`) VALUES
(57,10573,'Sầu riêng',180000,'<b style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Sầu riêng</b><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><a href=\"https://vi.wikipedia.org/wiki/Danh_ph%C3%A1p_khoa_h%E1%BB%8Dc\" title=\"Danh pháp khoa học\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">danh pháp khoa học</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><b>Durio</b></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;là một chi thực vật thuộc&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/H%E1%BB%8D_C%E1%BA%A9m_qu%E1%BB%B3\" title=\"Họ Cẩm quỳ\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">họ Cẩm quỳ</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\"><a href=\"https://vi.wikipedia.org/wiki/Malvaceae\" title=\"Malvaceae\" class=\"mw-redirect\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">Malvaceae</a></i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">),</span><sup id=\"cite_ref-GRIN_3-1\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Chi_S%E1%BA%A7u_ri%C3%AAng#cite_note-GRIN-3\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[3]</a></sup><sup id=\"cite_ref-APW_4-0\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Chi_S%E1%BA%A7u_ri%C3%AAng#cite_note-APW-4\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[4]</a></sup><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;(mặc dù một số nhà phân loại học đặt&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Durio</i><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">&nbsp;vào một họ riêng biệt,&nbsp;</span><i style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">Durionaceae</i><sup id=\"cite_ref-GRIN_3-2\" class=\"reference\" style=\"line-height: 1em; font-size: 11.1999998092651px; white-space: nowrap; display: inline-block; color: rgb(37, 37, 37); font-family: sans-serif;\"><a href=\"https://vi.wikipedia.org/wiki/Chi_S%E1%BA%A7u_ri%C3%AAng#cite_note-GRIN-3\" style=\"color: rgb(11, 0, 128); background-image: none; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">[3]</a></sup><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">), được biết đến rộng rãi tại&nbsp;</span><a href=\"https://vi.wikipedia.org/wiki/%C4%90%C3%B4ng_Nam_%C3%81\" title=\"Đông Nam Á\" style=\"color: rgb(11, 0, 128); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px; background: none rgb(255, 255, 255);\">Đông Nam Á</a><span style=\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.3999996185303px;\">.</span>','<span style=\"margin: 0px; padding: 0px; font-family: HelveticaRegular; color: rgb(34, 34, 34); font-size: 15px; line-height: 19px; text-align: justify;\"><span style=\"margin: 0px; padding: 0px; font-family: HelveticaMedium !important;\"><span style=\"margin: 0px; padding: 0px; font-family: HelveticaRegular !important;\">Với mùi thơm nồng ngay cả khi chưa lột vỏ, sầu riêng là một loại trái cây vừa được yêu thích lại vừa bị ghét bỏ</span></span></span>',10,5,10,1439571600,1,8,6);



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
-- TABLE DATA tmp_product
-- -------------------------------------------
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1001,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1002,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1003,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1004,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1005,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1006,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1007,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1008,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1009,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1010,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1011,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1012,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1013,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1014,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1015,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1016,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1017,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1018,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1019,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1020,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1021,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1022,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1023,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1024,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1025,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1026,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1027,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1028,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1029,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1030,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1031,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1032,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1033,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1034,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1035,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1036,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1037,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1038,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1039,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1040,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1041,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1042,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1043,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1044,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1045,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1046,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1047,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1048,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1049,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1050,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1051,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1052,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1053,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1054,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1055,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1056,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1057,NULL,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1058,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1059,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1060,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1061,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1062,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1063,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1064,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1065,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1066,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1067,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1068,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1069,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1070,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1071,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1072,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1073,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1074,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1075,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1076,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1077,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1078,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1079,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1080,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1081,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1082,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1083,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1084,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1085,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1086,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1087,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1088,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1089,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1090,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1091,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1092,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1093,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1094,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1095,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1096,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1097,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1098,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1099,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1100,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1101,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1102,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1103,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1104,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1105,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1106,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1107,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1108,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1109,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1110,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1111,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1112,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1113,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1114,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1115,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1116,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1117,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1118,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1119,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1120,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1121,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1122,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1123,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1124,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1125,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1126,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1127,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1128,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1129,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1130,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1131,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1132,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1133,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1134,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1135,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1136,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1137,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1138,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1139,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1140,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1141,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1142,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1143,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1144,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1145,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1146,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1147,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1148,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1149,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1150,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1151,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1152,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1153,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1154,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1155,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1156,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1157,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1158,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1159,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1160,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1161,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1162,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1163,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1164,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1165,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1166,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1167,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1168,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1169,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1170,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1171,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1172,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1173,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1174,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1175,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1176,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1177,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1178,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1179,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1180,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1181,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1182,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1183,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1184,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1185,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1186,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1187,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1188,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1189,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1190,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1191,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1192,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1193,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1194,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1195,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1196,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1197,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1198,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1199,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1200,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1201,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1202,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1203,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1204,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1205,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1206,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1207,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1208,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1209,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1210,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1211,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1212,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1213,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1214,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1215,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1216,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1217,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1218,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1219,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1220,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1221,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1222,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1223,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1224,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1225,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1226,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1227,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1228,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1229,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1230,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1231,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1232,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1233,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1234,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1235,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1236,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1237,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1238,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1239,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1240,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1241,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1242,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1243,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1244,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1245,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1246,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1247,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1248,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1249,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1250,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1251,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1252,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1253,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1254,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1255,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1256,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1257,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1258,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1259,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1260,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1261,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1262,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1263,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1264,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1265,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1266,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1267,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1268,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1269,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1270,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1271,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1272,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1273,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1274,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1275,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1276,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1277,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1278,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1279,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1280,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1281,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1282,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1283,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1284,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1285,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1286,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1287,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1288,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1289,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1290,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1291,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1292,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1293,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1294,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1295,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1296,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1297,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1298,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1299,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1300,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1301,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1302,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1303,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1304,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1305,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1306,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1307,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1308,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1309,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1310,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1311,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1312,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1313,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1314,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1315,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1316,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1317,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1318,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1319,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1320,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1321,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1322,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1323,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1324,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1325,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1326,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1327,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1328,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1329,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1330,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1331,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1332,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1333,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1334,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1335,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1336,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1337,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1338,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1339,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1340,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1341,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1342,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1343,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1344,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1345,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1346,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1347,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1348,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1349,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1350,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1351,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1352,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1353,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1354,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1355,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1356,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1357,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1358,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1359,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1360,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1361,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1362,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1363,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1364,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1365,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1366,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1367,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1368,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1369,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1370,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1371,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1372,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1373,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1374,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1375,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1376,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1377,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1378,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1379,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1380,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1381,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1382,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1383,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1384,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1385,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1386,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1387,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1388,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1389,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1390,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1391,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1392,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1393,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1394,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1395,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1396,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1397,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1398,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1399,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1400,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1401,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1402,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1403,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1404,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1405,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1406,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1407,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1408,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1409,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1410,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1411,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1412,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1413,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1414,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1415,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1416,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1417,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1418,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1419,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1420,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1421,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1422,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1423,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1424,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1425,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1426,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1427,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1428,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1429,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1430,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1431,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1432,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1433,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1434,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1435,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1436,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1437,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1438,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1439,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1440,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1441,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1442,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1443,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1444,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1445,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1446,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1447,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1448,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1449,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1450,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1451,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1452,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1453,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1454,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1455,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1456,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1457,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1458,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1459,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1460,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1461,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1462,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1463,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1464,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1465,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1466,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1467,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1468,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1469,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1470,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1471,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1472,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1473,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1474,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1475,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1476,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1477,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1478,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1479,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1480,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1481,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1482,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1483,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1484,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1485,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1486,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1487,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1488,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1489,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1490,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1491,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1492,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1493,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1494,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1495,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1496,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1497,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1498,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1499,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1500,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1501,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1502,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1503,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1504,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1505,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1506,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1507,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1508,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1509,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1510,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1511,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1512,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1513,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1514,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1515,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1516,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1517,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1518,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1519,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1520,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1521,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1522,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1523,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1524,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1525,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1526,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1527,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1528,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1529,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1530,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1531,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1532,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1533,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1534,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1535,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1536,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1537,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1538,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1539,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1540,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1541,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1542,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1543,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1544,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1545,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1546,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1547,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1548,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1549,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1550,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1551,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1552,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1553,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1554,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1555,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1556,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1557,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1558,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1559,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1560,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1561,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1562,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1563,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1564,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1565,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1566,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1567,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1568,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1569,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1570,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1571,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1572,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1573,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1574,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1575,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1576,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1577,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1578,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1579,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1580,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1581,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1582,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1583,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1584,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1585,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1586,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1587,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1588,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1589,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1590,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1591,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1592,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1593,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1594,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1595,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1596,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1597,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1598,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1599,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1600,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1601,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1602,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1603,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1604,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1605,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1606,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1607,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1608,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1609,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1610,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1611,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1612,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1613,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1614,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1615,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1616,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1617,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1618,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1619,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1620,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1621,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1622,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1623,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1624,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1625,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1626,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1627,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1628,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1629,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1630,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1631,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1632,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1633,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1634,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1635,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1636,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1637,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1638,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1639,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1640,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1641,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1642,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1643,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1644,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1645,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1646,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1647,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1648,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1649,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1650,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1651,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1652,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1653,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1654,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1655,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1656,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1657,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1658,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1659,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1660,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1661,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1662,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1663,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1664,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1665,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1666,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1667,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1668,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1669,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1670,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1671,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1672,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1673,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1674,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1675,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1676,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1677,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1678,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1679,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1680,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1681,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1682,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1683,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1684,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1685,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1686,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1687,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1688,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1689,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1690,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1691,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1692,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1693,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1694,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1695,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1696,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1697,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1698,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1699,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1700,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1701,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1702,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1703,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1704,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1705,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1706,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1707,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1708,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1709,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1710,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1711,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1712,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1713,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1714,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1715,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1716,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1717,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1718,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1719,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1720,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1721,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1722,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1723,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1724,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1725,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1726,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1727,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1728,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1729,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1730,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1731,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1732,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1733,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1734,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1735,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1736,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1737,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1738,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1739,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1740,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1741,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1742,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1743,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1744,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1745,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1746,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1747,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1748,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1749,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1750,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1751,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1752,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1753,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1754,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1755,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1756,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1757,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1758,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1759,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1760,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1761,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1762,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1763,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1764,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1765,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1766,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1767,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1768,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1769,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1770,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1771,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1772,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1773,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1774,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1775,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1776,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1777,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1778,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1779,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1780,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1781,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1782,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1783,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1784,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1785,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1786,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1787,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1788,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1789,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1790,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1791,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1792,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1793,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1794,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1795,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1796,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1797,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1798,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1799,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1800,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1801,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1802,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1803,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1804,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1805,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1806,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1807,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1808,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1809,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1810,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1811,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1812,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1813,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1814,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1815,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1816,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1817,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1818,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1819,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1820,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1821,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1822,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1823,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1824,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1825,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1826,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1827,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1828,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1829,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1830,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1831,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1832,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1833,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1834,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1835,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1836,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1837,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1838,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1839,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1840,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1841,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1842,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1843,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1844,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1845,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1846,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1847,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1848,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1849,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1850,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1851,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1852,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1853,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1854,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1855,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1856,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1857,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1858,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1859,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1860,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1861,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1862,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1863,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1864,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1865,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1866,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1867,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1868,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1869,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1870,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1871,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1872,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1873,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1874,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1875,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1876,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1877,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1878,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1879,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1880,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1881,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1882,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1883,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1884,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1885,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1886,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1887,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1888,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1889,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1890,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1891,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1892,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1893,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1894,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1895,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1896,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1897,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1898,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1899,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1900,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1901,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1902,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1903,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1904,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1905,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1906,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1907,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1908,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1909,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1910,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1911,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1912,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1913,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1914,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1915,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1916,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1917,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1918,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1919,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1920,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1921,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1922,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1923,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1924,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1925,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1926,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1927,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1928,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1929,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1930,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1931,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1932,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1933,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1934,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1935,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1936,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1937,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1938,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1939,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1940,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1941,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1942,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1943,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1944,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1945,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1946,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1947,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1948,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1949,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1950,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1951,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1952,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1953,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1954,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1955,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1956,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1957,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1958,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1959,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1960,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1961,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1962,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1963,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1964,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1965,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1966,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1967,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1968,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1969,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1970,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1971,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1972,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1973,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1974,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1975,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1976,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1977,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1978,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1979,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1980,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1981,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1982,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1983,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1984,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1985,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1986,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1987,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1988,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1989,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1990,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1991,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1992,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1993,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1994,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1995,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1996,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1997,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1998,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(1999,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2000,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2001,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2002,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2003,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2004,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2005,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2006,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2007,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2008,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2009,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2010,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2011,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2012,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2013,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2014,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2015,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2016,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2017,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2018,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2019,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2020,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2021,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2022,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2023,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2024,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2025,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2026,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2027,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2028,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2029,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2030,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2031,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2032,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2033,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2034,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2035,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2036,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2037,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2038,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2039,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2040,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2041,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2042,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2043,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2044,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2045,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2046,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2047,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2048,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2049,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2050,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2051,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2052,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2053,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2054,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2055,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2056,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2057,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2058,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2059,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2060,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2061,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2062,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2063,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2064,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2065,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2066,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2067,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2068,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2069,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2070,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2071,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2072,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2073,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2074,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2075,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2076,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2077,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2078,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2079,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2080,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2081,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2082,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2083,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2084,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2085,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2086,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2087,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2088,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2089,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2090,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2091,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2092,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2093,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2094,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2095,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2096,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2097,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2098,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2099,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2100,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2101,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2102,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2103,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2104,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2105,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2106,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2107,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2108,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2109,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2110,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2111,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2112,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2113,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2114,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2115,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2116,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2117,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2118,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2119,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2120,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2121,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2122,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2123,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2124,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2125,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2126,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2127,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2128,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2129,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2130,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2131,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2132,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2133,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2134,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2135,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2136,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2137,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2138,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2139,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2140,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2141,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2142,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2143,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2144,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2145,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2146,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2147,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2148,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2149,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2150,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2151,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2152,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2153,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2154,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2155,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2156,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2157,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2158,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2159,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2160,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2161,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2162,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2163,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2164,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2165,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2166,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2167,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2168,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2169,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2170,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2171,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2172,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2173,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2174,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2175,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2176,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2177,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2178,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2179,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2180,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2181,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2182,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2183,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2184,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2185,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2186,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2187,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2188,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2189,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2190,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2191,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2192,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2193,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2194,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2195,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2196,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2197,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2198,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2199,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2200,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2201,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2202,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2203,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2204,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2205,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2206,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2207,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2208,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2209,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2210,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2211,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2212,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2213,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2214,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2215,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2216,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2217,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2218,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2219,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2220,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2221,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2222,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2223,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2224,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2225,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2226,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2227,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2228,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2229,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2230,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2231,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2232,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2233,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2234,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2235,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2236,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2237,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2238,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2239,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2240,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2241,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2242,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2243,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2244,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2245,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2246,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2247,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2248,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2249,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2250,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2251,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2252,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2253,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2254,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2255,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2256,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2257,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2258,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2259,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2260,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2261,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2262,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2263,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2264,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2265,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2266,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2267,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2268,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2269,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2270,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2271,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2272,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2273,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2274,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2275,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2276,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2277,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2278,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2279,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2280,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2281,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2282,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2283,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2284,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2285,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2286,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2287,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2288,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2289,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2290,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2291,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2292,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2293,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2294,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2295,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2296,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2297,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2298,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2299,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2300,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2301,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2302,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2303,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2304,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2305,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2306,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2307,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2308,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2309,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2310,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2311,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2312,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2313,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2314,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2315,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2316,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2317,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2318,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2319,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2320,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2321,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2322,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2323,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2324,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2325,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2326,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2327,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2328,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2329,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2330,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2331,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2332,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2333,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2334,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2335,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2336,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2337,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2338,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2339,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2340,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2341,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2342,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2343,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2344,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2345,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2346,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2347,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2348,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2349,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2350,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2351,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2352,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2353,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2354,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2355,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2356,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2357,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2358,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2359,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2360,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2361,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2362,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2363,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2364,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2365,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2366,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2367,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2368,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2369,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2370,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2371,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2372,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2373,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2374,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2375,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2376,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2377,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2378,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2379,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2380,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2381,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2382,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2383,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2384,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2385,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2386,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2387,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2388,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2389,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2390,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2391,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2392,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2393,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2394,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2395,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2396,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2397,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2398,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2399,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2400,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2401,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2402,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2403,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2404,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2405,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2406,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2407,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2408,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2409,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2410,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2411,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2412,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2413,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2414,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2415,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2416,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2417,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2418,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2419,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2420,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2421,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2422,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2423,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2424,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2425,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2426,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2427,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2428,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2429,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2430,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2431,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2432,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2433,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2434,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2435,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2436,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2437,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2438,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2439,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2440,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2441,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2442,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2443,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2444,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2445,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2446,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2447,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2448,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2449,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2450,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2451,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2452,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2453,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2454,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2455,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2456,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2457,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2458,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2459,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2460,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2461,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2462,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2463,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2464,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2465,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2466,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2467,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2468,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2469,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2470,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2471,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2472,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2473,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2474,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2475,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2476,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2477,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2478,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2479,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2480,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2481,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2482,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2483,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2484,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2485,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2486,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2487,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2488,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2489,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2490,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2491,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2492,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2493,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2494,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2495,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2496,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2497,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2498,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2499,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2500,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2501,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2502,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2503,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2504,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2505,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2506,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2507,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2508,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2509,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2510,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2511,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2512,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2513,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2514,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2515,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2516,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2517,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2518,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2519,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2520,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2521,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2522,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2523,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2524,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2525,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2526,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2527,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2528,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2529,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2530,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2531,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2532,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2533,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2534,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2535,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2536,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2537,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2538,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2539,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2540,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2541,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2542,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2543,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2544,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2545,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2546,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2547,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2548,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2549,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2550,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2551,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2552,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2553,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2554,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2555,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2556,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2557,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2558,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2559,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2560,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2561,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2562,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2563,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2564,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2565,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2566,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2567,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2568,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2569,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2570,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2571,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2572,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2573,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2574,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2575,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2576,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2577,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2578,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2579,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2580,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2581,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2582,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2583,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2584,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2585,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2586,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2587,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2588,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2589,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2590,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2591,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2592,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2593,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2594,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2595,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2596,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2597,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2598,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2599,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2600,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2601,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2602,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2603,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2604,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2605,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2606,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2607,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2608,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2609,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2610,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2611,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2612,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2613,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2614,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2615,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2616,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2617,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2618,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2619,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2620,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2621,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2622,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2623,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2624,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2625,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2626,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2627,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2628,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2629,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2630,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2631,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2632,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2633,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2634,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2635,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2636,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2637,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2638,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2639,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2640,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2641,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2642,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2643,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2644,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2645,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2646,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2647,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2648,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2649,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2650,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2651,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2652,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2653,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2654,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2655,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2656,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2657,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2658,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2659,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2660,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2661,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2662,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2663,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2664,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2665,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2666,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2667,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2668,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2669,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2670,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2671,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2672,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2673,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2674,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2675,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2676,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2677,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2678,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2679,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2680,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2681,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2682,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2683,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2684,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2685,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2686,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2687,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2688,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2689,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2690,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2691,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2692,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2693,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2694,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2695,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2696,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2697,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2698,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2699,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2700,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2701,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2702,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2703,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2704,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2705,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2706,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2707,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2708,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2709,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2710,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2711,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2712,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2713,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2714,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2715,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2716,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2717,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2718,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2719,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2720,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2721,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2722,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2723,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2724,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2725,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2726,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2727,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2728,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2729,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2730,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2731,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2732,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2733,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2734,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2735,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2736,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2737,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2738,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2739,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2740,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2741,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2742,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2743,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2744,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2745,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2746,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2747,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2748,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2749,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2750,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2751,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2752,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2753,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2754,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2755,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2756,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2757,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2758,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2759,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2760,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2761,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2762,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2763,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2764,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2765,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2766,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2767,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2768,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2769,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2770,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2771,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2772,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2773,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2774,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2775,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2776,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2777,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2778,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2779,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2780,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2781,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2782,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2783,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2784,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2785,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2786,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2787,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2788,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2789,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2790,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2791,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2792,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2793,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2794,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2795,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2796,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2797,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2798,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2799,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2800,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2801,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2802,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2803,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2804,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2805,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2806,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2807,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2808,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2809,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2810,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2811,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2812,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2813,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2814,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2815,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2816,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2817,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2818,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2819,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2820,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2821,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2822,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2823,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2824,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2825,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2826,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2827,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2828,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2829,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2830,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2831,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2832,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2833,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2834,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2835,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2836,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2837,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2838,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2839,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2840,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2841,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2842,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2843,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2844,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2845,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2846,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2847,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2848,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2849,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2850,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2851,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2852,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2853,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2854,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2855,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2856,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2857,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2858,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2859,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2860,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2861,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2862,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2863,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2864,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2865,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2866,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2867,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2868,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2869,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2870,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2871,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2872,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2873,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2874,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2875,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2876,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2877,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2878,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2879,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2880,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2881,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2882,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2883,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2884,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2885,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2886,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2887,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2888,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2889,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2890,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2891,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2892,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2893,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2894,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2895,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2896,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2897,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2898,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2899,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2900,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2901,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2902,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2903,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2904,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2905,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2906,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2907,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2908,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2909,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2910,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2911,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2912,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2913,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2914,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2915,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2916,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2917,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2918,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2919,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2920,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2921,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2922,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2923,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2924,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2925,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2926,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2927,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2928,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2929,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2930,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2931,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2932,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2933,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2934,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2935,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2936,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2937,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2938,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2939,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2940,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2941,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2942,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2943,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2944,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2945,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2946,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2947,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2948,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2949,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2950,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2951,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2952,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2953,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2954,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2955,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2956,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2957,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2958,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2959,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2960,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2961,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2962,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2963,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2964,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2965,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2966,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2967,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2968,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2969,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2970,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2971,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2972,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2973,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2974,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2975,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2976,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2977,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2978,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2979,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2980,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2981,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2982,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2983,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2984,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2985,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2986,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2987,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2988,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2989,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2990,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2991,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2992,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2993,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2994,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2995,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2996,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2997,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2998,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(2999,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3000,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3001,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3002,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3003,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3004,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3005,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3006,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3007,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3008,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3009,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3010,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3011,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3012,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3013,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3014,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3015,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3016,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3017,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3018,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3019,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3020,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3021,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3022,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3023,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3024,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3025,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3026,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3027,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3028,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3029,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3030,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3031,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3032,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3033,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3034,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3035,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3036,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3037,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3038,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3039,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3040,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3041,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3042,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3043,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3044,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3045,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3046,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3047,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3048,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3049,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3050,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3051,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3052,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3053,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3054,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3055,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3056,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3057,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3058,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3059,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3060,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3061,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3062,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3063,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3064,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3065,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3066,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3067,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3068,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3069,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3070,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3071,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3072,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3073,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3074,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3075,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3076,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3077,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3078,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3079,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3080,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3081,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3082,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3083,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3084,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3085,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3086,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3087,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3088,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3089,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3090,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3091,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3092,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3093,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3094,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3095,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3096,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3097,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3098,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3099,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3100,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3101,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3102,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3103,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3104,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3105,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3106,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3107,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3108,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3109,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3110,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3111,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3112,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3113,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3114,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3115,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3116,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3117,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3118,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3119,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3120,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3121,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3122,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3123,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3124,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3125,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3126,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3127,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3128,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3129,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3130,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3131,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3132,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3133,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3134,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3135,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3136,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3137,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3138,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3139,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3140,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3141,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3142,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3143,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3144,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3145,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3146,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3147,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3148,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3149,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3150,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3151,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3152,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3153,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3154,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3155,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3156,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3157,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3158,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3159,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3160,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3161,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3162,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3163,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3164,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3165,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3166,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3167,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3168,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3169,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3170,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3171,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3172,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3173,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3174,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3175,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3176,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3177,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3178,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3179,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3180,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3181,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3182,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3183,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3184,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3185,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3186,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3187,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3188,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3189,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3190,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3191,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3192,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3193,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3194,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3195,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3196,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3197,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3198,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3199,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3200,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3201,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3202,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3203,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3204,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3205,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3206,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3207,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3208,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3209,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3210,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3211,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3212,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3213,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3214,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3215,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3216,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3217,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3218,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3219,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3220,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3221,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3222,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3223,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3224,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3225,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3226,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3227,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3228,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3229,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3230,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3231,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3232,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3233,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3234,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3235,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3236,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3237,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3238,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3239,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3240,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3241,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3242,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3243,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3244,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3245,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3246,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3247,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3248,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3249,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3250,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3251,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3252,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3253,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3254,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3255,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3256,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3257,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3258,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3259,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3260,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3261,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3262,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3263,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3264,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3265,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3266,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3267,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3268,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3269,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3270,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3271,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3272,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3273,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3274,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3275,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3276,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3277,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3278,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3279,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3280,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3281,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3282,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3283,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3284,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3285,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3286,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3287,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3288,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3289,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3290,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3291,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3292,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3293,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3294,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3295,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3296,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3297,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3298,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3299,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3300,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3301,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3302,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3303,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3304,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3305,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3306,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3307,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3308,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3309,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3310,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3311,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3312,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3313,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3314,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3315,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3316,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3317,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3318,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3319,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3320,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3321,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3322,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3323,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3324,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3325,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3326,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3327,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3328,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3329,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3330,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3331,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3332,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3333,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3334,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3335,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3336,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3337,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3338,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3339,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3340,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3341,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3342,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3343,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3344,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3345,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3346,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3347,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3348,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3349,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3350,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3351,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3352,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3353,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3354,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3355,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3356,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3357,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3358,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3359,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3360,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3361,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3362,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3363,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3364,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3365,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3366,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3367,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3368,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3369,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3370,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3371,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3372,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3373,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3374,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3375,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3376,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3377,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3378,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3379,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3380,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3381,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3382,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3383,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3384,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3385,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3386,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3387,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3388,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3389,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3390,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3391,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3392,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3393,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3394,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3395,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3396,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3397,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3398,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3399,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3400,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3401,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3402,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3403,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3404,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3405,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3406,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3407,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3408,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3409,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3410,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3411,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3412,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3413,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3414,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3415,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3416,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3417,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3418,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3419,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3420,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3421,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3422,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3423,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3424,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3425,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3426,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3427,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3428,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3429,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3430,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3431,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3432,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3433,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3434,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3435,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3436,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3437,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3438,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3439,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3440,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3441,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3442,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3443,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3444,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3445,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3446,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3447,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3448,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3449,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3450,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3451,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3452,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3453,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3454,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3455,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3456,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3457,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3458,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3459,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3460,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3461,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3462,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3463,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3464,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3465,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3466,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3467,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3468,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3469,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3470,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3471,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3472,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3473,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3474,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3475,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3476,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3477,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3478,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3479,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3480,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3481,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3482,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3483,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3484,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3485,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3486,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3487,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3488,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3489,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3490,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3491,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3492,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3493,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3494,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3495,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3496,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3497,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3498,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3499,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3500,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3501,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3502,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3503,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3504,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3505,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3506,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3507,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3508,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3509,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3510,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3511,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3512,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3513,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3514,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3515,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3516,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3517,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3518,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3519,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3520,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3521,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3522,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3523,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3524,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3525,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3526,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3527,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3528,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3529,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3530,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3531,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3532,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3533,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3534,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3535,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3536,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3537,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3538,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3539,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3540,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3541,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3542,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3543,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3544,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3545,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3546,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3547,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3548,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3549,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3550,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3551,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3552,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3553,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3554,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3555,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3556,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3557,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3558,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3559,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3560,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3561,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3562,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3563,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3564,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3565,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3566,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3567,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3568,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3569,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3570,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3571,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3572,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3573,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3574,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3575,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3576,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3577,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3578,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3579,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3580,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3581,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3582,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3583,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3584,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3585,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3586,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3587,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3588,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3589,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3590,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3591,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3592,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3593,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3594,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3595,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3596,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3597,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3598,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3599,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3600,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3601,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3602,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3603,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3604,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3605,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3606,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3607,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3608,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3609,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3610,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3611,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3612,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3613,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3614,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3615,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3616,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3617,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3618,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3619,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3620,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3621,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3622,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3623,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3624,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3625,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3626,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3627,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3628,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3629,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3630,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3631,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3632,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3633,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3634,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3635,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3636,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3637,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3638,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3639,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3640,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3641,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3642,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3643,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3644,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3645,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3646,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3647,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3648,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3649,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3650,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3651,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3652,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3653,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3654,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3655,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3656,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3657,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3658,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3659,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3660,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3661,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3662,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3663,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3664,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3665,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3666,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3667,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3668,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3669,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3670,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3671,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3672,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3673,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3674,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3675,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3676,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3677,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3678,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3679,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3680,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3681,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3682,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3683,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3684,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3685,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3686,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3687,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3688,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3689,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3690,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3691,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3692,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3693,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3694,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3695,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3696,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3697,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3698,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3699,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3700,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3701,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3702,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3703,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3704,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3705,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3706,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3707,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3708,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3709,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3710,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3711,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3712,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3713,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3714,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3715,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3716,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3717,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3718,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3719,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3720,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3721,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3722,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3723,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3724,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3725,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3726,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3727,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3728,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3729,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3730,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3731,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3732,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3733,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3734,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3735,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3736,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3737,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3738,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3739,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3740,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3741,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3742,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3743,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3744,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3745,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3746,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3747,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3748,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3749,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3750,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3751,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3752,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3753,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3754,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3755,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3756,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3757,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3758,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3759,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3760,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3761,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3762,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3763,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3764,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3765,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3766,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3767,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3768,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3769,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3770,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3771,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3772,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3773,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3774,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3775,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3776,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3777,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3778,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3779,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3780,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3781,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3782,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3783,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3784,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3785,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3786,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3787,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3788,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3789,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3790,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3791,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3792,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3793,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3794,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3795,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3796,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3797,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3798,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3799,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3800,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3801,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3802,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3803,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3804,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3805,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3806,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3807,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3808,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3809,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3810,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3811,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3812,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3813,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3814,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3815,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3816,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3817,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3818,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3819,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3820,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3821,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3822,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3823,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3824,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3825,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3826,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3827,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3828,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3829,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3830,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3831,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3832,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3833,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3834,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3835,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3836,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3837,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3838,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3839,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3840,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3841,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3842,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3843,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3844,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3845,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3846,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3847,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3848,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3849,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3850,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3851,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3852,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3853,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3854,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3855,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3856,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3857,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3858,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3859,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3860,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3861,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3862,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3863,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3864,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3865,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3866,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3867,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3868,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3869,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3870,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3871,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3872,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3873,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3874,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3875,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3876,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3877,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3878,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3879,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3880,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3881,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3882,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3883,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3884,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3885,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3886,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3887,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3888,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3889,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3890,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3891,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3892,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3893,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3894,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3895,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3896,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3897,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3898,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3899,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3900,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3901,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3902,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3903,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3904,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3905,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3906,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3907,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3908,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3909,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3910,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3911,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3912,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3913,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3914,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3915,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3916,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3917,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3918,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3919,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3920,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3921,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3922,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3923,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3924,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3925,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3926,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3927,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3928,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3929,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3930,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3931,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3932,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3933,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3934,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3935,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3936,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3937,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3938,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3939,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3940,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3941,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3942,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3943,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3944,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3945,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3946,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3947,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3948,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3949,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3950,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3951,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3952,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3953,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3954,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3955,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3956,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3957,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3958,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3959,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3960,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3961,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3962,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3963,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3964,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3965,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3966,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3967,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3968,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3969,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3970,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3971,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3972,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3973,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3974,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3975,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3976,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3977,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3978,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3979,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3980,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3981,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3982,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3983,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3984,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3985,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3986,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3987,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3988,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3989,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3990,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3991,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3992,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3993,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3994,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3995,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3996,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3997,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3998,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(3999,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4000,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4001,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4002,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4003,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4004,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4005,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4006,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4007,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4008,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4009,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4010,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4011,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4012,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4013,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4014,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4015,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4016,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4017,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4018,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4019,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4020,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4021,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4022,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4023,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4024,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4025,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4026,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4027,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4028,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4029,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4030,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4031,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4032,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4033,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4034,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4035,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4036,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4037,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4038,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4039,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4040,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4041,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4042,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4043,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4044,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4045,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4046,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4047,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4048,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4049,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4050,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4051,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4052,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4053,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4054,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4055,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4056,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4057,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4058,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4059,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4060,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4061,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4062,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4063,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4064,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4065,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4066,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4067,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4068,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4069,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4070,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4071,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4072,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4073,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4074,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4075,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4076,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4077,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4078,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4079,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4080,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4081,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4082,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4083,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4084,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4085,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4086,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4087,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4088,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4089,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4090,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4091,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4092,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4093,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4094,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4095,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4096,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4097,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4098,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4099,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4100,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4101,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4102,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4103,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4104,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4105,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4106,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4107,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4108,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4109,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4110,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4111,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4112,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4113,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4114,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4115,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4116,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4117,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4118,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4119,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4120,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4121,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4122,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4123,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4124,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4125,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4126,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4127,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4128,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4129,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4130,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4131,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4132,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4133,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4134,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4135,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4136,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4137,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4138,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4139,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4140,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4141,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4142,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4143,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4144,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4145,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4146,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4147,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4148,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4149,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4150,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4151,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4152,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4153,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4154,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4155,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4156,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4157,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4158,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4159,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4160,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4161,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4162,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4163,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4164,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4165,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4166,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4167,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4168,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4169,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4170,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4171,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4172,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4173,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4174,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4175,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4176,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4177,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4178,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4179,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4180,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4181,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4182,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4183,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4184,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4185,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4186,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4187,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4188,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4189,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4190,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4191,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4192,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4193,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4194,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4195,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4196,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4197,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4198,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4199,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4200,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4201,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4202,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4203,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4204,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4205,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4206,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4207,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4208,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4209,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4210,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4211,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4212,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4213,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4214,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4215,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4216,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4217,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4218,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4219,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4220,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4221,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4222,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4223,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4224,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4225,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4226,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4227,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4228,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4229,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4230,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4231,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4232,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4233,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4234,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4235,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4236,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4237,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4238,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4239,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4240,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4241,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4242,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4243,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4244,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4245,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4246,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4247,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4248,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4249,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4250,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4251,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4252,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4253,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4254,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4255,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4256,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4257,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4258,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4259,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4260,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4261,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4262,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4263,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4264,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4265,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4266,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4267,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4268,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4269,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4270,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4271,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4272,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4273,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4274,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4275,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4276,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4277,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4278,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4279,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4280,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4281,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4282,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4283,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4284,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4285,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4286,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4287,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4288,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4289,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4290,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4291,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4292,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4293,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4294,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4295,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4296,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4297,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4298,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4299,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4300,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4301,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4302,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4303,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4304,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4305,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4306,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4307,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4308,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4309,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4310,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4311,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4312,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4313,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4314,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4315,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4316,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4317,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4318,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4319,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4320,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4321,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4322,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4323,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4324,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4325,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4326,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4327,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4328,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4329,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4330,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4331,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4332,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4333,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4334,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4335,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4336,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4337,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4338,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4339,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4340,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4341,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4342,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4343,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4344,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4345,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4346,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4347,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4348,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4349,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4350,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4351,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4352,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4353,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4354,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4355,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4356,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4357,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4358,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4359,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4360,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4361,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4362,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4363,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4364,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4365,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4366,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4367,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4368,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4369,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4370,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4371,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4372,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4373,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4374,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4375,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4376,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4377,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4378,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4379,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4380,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4381,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4382,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4383,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4384,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4385,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4386,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4387,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4388,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4389,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4390,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4391,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4392,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4393,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4394,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4395,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4396,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4397,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4398,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4399,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4400,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4401,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4402,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4403,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4404,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4405,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4406,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4407,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4408,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4409,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4410,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4411,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4412,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4413,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4414,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4415,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4416,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4417,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4418,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4419,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4420,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4421,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4422,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4423,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4424,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4425,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4426,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4427,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4428,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4429,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4430,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4431,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4432,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4433,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4434,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4435,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4436,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4437,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4438,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4439,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4440,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4441,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4442,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4443,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4444,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4445,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4446,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4447,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4448,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4449,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4450,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4451,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4452,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4453,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4454,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4455,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4456,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4457,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4458,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4459,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4460,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4461,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4462,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4463,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4464,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4465,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4466,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4467,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4468,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4469,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4470,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4471,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4472,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4473,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4474,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4475,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4476,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4477,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4478,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4479,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4480,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4481,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4482,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4483,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4484,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4485,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4486,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4487,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4488,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4489,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4490,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4491,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4492,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4493,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4494,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4495,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4496,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4497,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4498,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4499,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4500,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4501,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4502,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4503,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4504,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4505,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4506,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4507,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4508,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4509,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4510,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4511,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4512,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4513,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4514,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4515,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4516,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4517,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4518,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4519,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4520,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4521,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4522,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4523,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4524,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4525,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4526,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4527,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4528,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4529,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4530,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4531,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4532,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4533,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4534,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4535,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4536,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4537,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4538,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4539,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4540,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4541,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4542,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4543,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4544,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4545,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4546,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4547,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4548,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4549,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4550,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4551,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4552,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4553,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4554,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4555,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4556,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4557,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4558,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4559,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4560,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4561,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4562,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4563,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4564,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4565,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4566,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4567,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4568,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4569,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4570,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4571,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4572,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4573,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4574,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4575,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4576,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4577,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4578,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4579,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4580,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4581,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4582,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4583,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4584,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4585,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4586,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4587,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4588,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4589,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4590,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4591,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4592,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4593,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4594,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4595,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4596,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4597,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4598,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4599,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4600,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4601,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4602,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4603,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4604,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4605,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4606,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4607,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4608,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4609,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4610,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4611,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4612,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4613,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4614,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4615,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4616,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4617,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4618,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4619,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4620,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4621,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4622,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4623,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4624,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4625,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4626,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4627,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4628,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4629,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4630,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4631,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4632,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4633,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4634,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4635,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4636,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4637,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4638,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4639,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4640,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4641,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4642,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4643,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4644,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4645,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4646,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4647,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4648,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4649,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4650,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4651,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4652,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4653,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4654,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4655,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4656,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4657,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4658,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4659,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4660,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4661,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4662,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4663,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4664,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4665,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4666,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4667,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4668,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4669,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4670,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4671,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4672,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4673,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4674,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4675,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4676,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4677,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4678,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4679,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4680,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4681,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4682,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4683,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4684,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4685,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4686,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4687,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4688,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4689,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4690,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4691,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4692,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4693,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4694,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4695,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4696,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4697,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4698,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4699,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4700,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4701,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4702,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4703,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4704,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4705,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4706,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4707,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4708,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4709,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4710,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4711,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4712,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4713,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4714,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4715,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4716,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4717,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4718,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4719,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4720,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4721,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4722,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4723,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4724,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4725,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4726,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4727,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4728,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4729,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4730,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4731,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4732,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4733,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4734,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4735,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4736,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4737,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4738,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4739,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4740,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4741,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4742,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4743,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4744,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4745,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4746,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4747,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4748,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4749,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4750,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4751,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4752,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4753,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4754,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4755,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4756,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4757,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4758,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4759,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4760,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4761,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4762,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4763,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4764,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4765,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4766,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4767,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4768,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4769,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4770,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4771,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4772,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4773,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4774,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4775,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4776,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4777,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4778,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4779,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4780,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4781,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4782,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4783,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4784,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4785,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4786,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4787,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4788,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4789,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4790,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4791,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4792,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4793,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4794,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4795,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4796,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4797,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4798,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4799,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4800,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4801,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4802,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4803,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4804,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4805,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4806,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4807,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4808,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4809,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4810,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4811,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4812,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4813,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4814,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4815,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4816,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4817,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4818,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4819,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4820,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4821,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4822,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4823,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4824,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4825,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4826,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4827,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4828,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4829,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4830,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4831,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4832,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4833,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4834,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4835,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4836,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4837,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4838,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4839,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4840,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4841,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4842,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4843,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4844,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4845,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4846,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4847,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4848,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4849,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4850,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4851,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4852,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4853,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4854,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4855,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4856,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4857,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4858,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4859,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4860,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4861,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4862,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4863,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4864,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4865,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4866,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4867,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4868,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4869,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4870,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4871,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4872,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4873,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4874,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4875,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4876,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4877,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4878,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4879,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4880,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4881,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4882,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4883,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4884,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4885,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4886,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4887,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4888,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4889,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4890,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4891,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4892,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4893,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4894,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4895,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4896,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4897,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4898,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4899,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4900,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4901,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4902,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4903,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4904,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4905,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4906,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4907,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4908,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4909,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4910,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4911,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4912,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4913,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4914,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4915,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4916,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4917,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4918,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4919,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4920,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4921,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4922,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4923,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4924,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4925,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4926,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4927,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4928,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4929,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4930,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4931,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4932,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4933,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4934,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4935,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4936,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4937,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4938,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4939,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4940,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4941,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4942,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4943,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4944,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4945,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4946,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4947,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4948,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4949,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4950,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4951,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4952,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4953,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4954,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4955,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4956,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4957,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4958,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4959,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4960,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4961,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4962,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4963,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4964,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4965,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4966,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4967,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4968,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4969,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4970,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4971,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4972,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4973,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4974,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4975,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4976,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4977,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4978,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4979,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4980,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4981,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4982,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4983,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4984,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4985,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4986,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4987,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4988,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4989,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4990,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4991,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4992,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4993,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4994,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4995,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4996,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4997,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4998,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(4999,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5000,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5001,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5002,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5003,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5004,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5005,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5006,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5007,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5008,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5009,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5010,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5011,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5012,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5013,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5014,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5015,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5016,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5017,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5018,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5019,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5020,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5021,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5022,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5023,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5024,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5025,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5026,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5027,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5028,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5029,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5030,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5031,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5032,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5033,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5034,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5035,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5036,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5037,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5038,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5039,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5040,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5041,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5042,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5043,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5044,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5045,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5046,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5047,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5048,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5049,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5050,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5051,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5052,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5053,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5054,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5055,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5056,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5057,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5058,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5059,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5060,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5061,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5062,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5063,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5064,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5065,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5066,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5067,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5068,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5069,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5070,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5071,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5072,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5073,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5074,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5075,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5076,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5077,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5078,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5079,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5080,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5081,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5082,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5083,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5084,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5085,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5086,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5087,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5088,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5089,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5090,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5091,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5092,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5093,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5094,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5095,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5096,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5097,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5098,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5099,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5100,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5101,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5102,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5103,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5104,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5105,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5106,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5107,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5108,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5109,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5110,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5111,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5112,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5113,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5114,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5115,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5116,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5117,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5118,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5119,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5120,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5121,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5122,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5123,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5124,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5125,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5126,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5127,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5128,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5129,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5130,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5131,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5132,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5133,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5134,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5135,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5136,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5137,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5138,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5139,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5140,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5141,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5142,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5143,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5144,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5145,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5146,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5147,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5148,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5149,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5150,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5151,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5152,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5153,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5154,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5155,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5156,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5157,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5158,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5159,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5160,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5161,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5162,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5163,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5164,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5165,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5166,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5167,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5168,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5169,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5170,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5171,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5172,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5173,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5174,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5175,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5176,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5177,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5178,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5179,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5180,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5181,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5182,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5183,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5184,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5185,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5186,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5187,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5188,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5189,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5190,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5191,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5192,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5193,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5194,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5195,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5196,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5197,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5198,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5199,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5200,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5201,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5202,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5203,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5204,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5205,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5206,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5207,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5208,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5209,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5210,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5211,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5212,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5213,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5214,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5215,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5216,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5217,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5218,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5219,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5220,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5221,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5222,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5223,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5224,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5225,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5226,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5227,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5228,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5229,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5230,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5231,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5232,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5233,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5234,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5235,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5236,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5237,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5238,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5239,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5240,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5241,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5242,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5243,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5244,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5245,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5246,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5247,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5248,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5249,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5250,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5251,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5252,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5253,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5254,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5255,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5256,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5257,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5258,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5259,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5260,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5261,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5262,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5263,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5264,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5265,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5266,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5267,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5268,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5269,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5270,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5271,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5272,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5273,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5274,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5275,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5276,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5277,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5278,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5279,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5280,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5281,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5282,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5283,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5284,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5285,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5286,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5287,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5288,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5289,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5290,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5291,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5292,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5293,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5294,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5295,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5296,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5297,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5298,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5299,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5300,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5301,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5302,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5303,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5304,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5305,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5306,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5307,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5308,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5309,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5310,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5311,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5312,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5313,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5314,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5315,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5316,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5317,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5318,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5319,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5320,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5321,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5322,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5323,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5324,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5325,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5326,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5327,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5328,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5329,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5330,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5331,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5332,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5333,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5334,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5335,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5336,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5337,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5338,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5339,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5340,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5341,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5342,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5343,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5344,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5345,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5346,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5347,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5348,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5349,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5350,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5351,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5352,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5353,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5354,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5355,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5356,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5357,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5358,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5359,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5360,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5361,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5362,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5363,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5364,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5365,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5366,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5367,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5368,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5369,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5370,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5371,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5372,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5373,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5374,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5375,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5376,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5377,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5378,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5379,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5380,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5381,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5382,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5383,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5384,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5385,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5386,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5387,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5388,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5389,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5390,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5391,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5392,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5393,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5394,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5395,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5396,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5397,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5398,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5399,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5400,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5401,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5402,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5403,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5404,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5405,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5406,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5407,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5408,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5409,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5410,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5411,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5412,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5413,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5414,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5415,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5416,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5417,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5418,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5419,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5420,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5421,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5422,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5423,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5424,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5425,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5426,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5427,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5428,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5429,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5430,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5431,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5432,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5433,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5434,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5435,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5436,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5437,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5438,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5439,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5440,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5441,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5442,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5443,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5444,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5445,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5446,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5447,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5448,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5449,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5450,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5451,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5452,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5453,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5454,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5455,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5456,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5457,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5458,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5459,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5460,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5461,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5462,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5463,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5464,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5465,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5466,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5467,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5468,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5469,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5470,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5471,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5472,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5473,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5474,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5475,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5476,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5477,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5478,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5479,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5480,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5481,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5482,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5483,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5484,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5485,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5486,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5487,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5488,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5489,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5490,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5491,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5492,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5493,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5494,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5495,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5496,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5497,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5498,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5499,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5500,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5501,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5502,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5503,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5504,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5505,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5506,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5507,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5508,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5509,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5510,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5511,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5512,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5513,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5514,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5515,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5516,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5517,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5518,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5519,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5520,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5521,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5522,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5523,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5524,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5525,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5526,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5527,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5528,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5529,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5530,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5531,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5532,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5533,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5534,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5535,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5536,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5537,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5538,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5539,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5540,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5541,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5542,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5543,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5544,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5545,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5546,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5547,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5548,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5549,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5550,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5551,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5552,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5553,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5554,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5555,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5556,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5557,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5558,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5559,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5560,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5561,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5562,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5563,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5564,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5565,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5566,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5567,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5568,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5569,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5570,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5571,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5572,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5573,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5574,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5575,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5576,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5577,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5578,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5579,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5580,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5581,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5582,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5583,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5584,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5585,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5586,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5587,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5588,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5589,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5590,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5591,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5592,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5593,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5594,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5595,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5596,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5597,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5598,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5599,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5600,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5601,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5602,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5603,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5604,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5605,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5606,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5607,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5608,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5609,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5610,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5611,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5612,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5613,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5614,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5615,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5616,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5617,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5618,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5619,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5620,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5621,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5622,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5623,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5624,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5625,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5626,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5627,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5628,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5629,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5630,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5631,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5632,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5633,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5634,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5635,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5636,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5637,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5638,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5639,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5640,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5641,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5642,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5643,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5644,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5645,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5646,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5647,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5648,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5649,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5650,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5651,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5652,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5653,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5654,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5655,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5656,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5657,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5658,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5659,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5660,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5661,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5662,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5663,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5664,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5665,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5666,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5667,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5668,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5669,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5670,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5671,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5672,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5673,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5674,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5675,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5676,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5677,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5678,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5679,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5680,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5681,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5682,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5683,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5684,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5685,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5686,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5687,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5688,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5689,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5690,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5691,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5692,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5693,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5694,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5695,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5696,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5697,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5698,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5699,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5700,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5701,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5702,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5703,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5704,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5705,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5706,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5707,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5708,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5709,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5710,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5711,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5712,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5713,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5714,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5715,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5716,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5717,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5718,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5719,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5720,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5721,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5722,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5723,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5724,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5725,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5726,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5727,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5728,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5729,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5730,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5731,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5732,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5733,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5734,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5735,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5736,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5737,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5738,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5739,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5740,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5741,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5742,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5743,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5744,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5745,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5746,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5747,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5748,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5749,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5750,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5751,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5752,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5753,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5754,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5755,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5756,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5757,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5758,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5759,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5760,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5761,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5762,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5763,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5764,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5765,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5766,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5767,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5768,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5769,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5770,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5771,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5772,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5773,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5774,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5775,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5776,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5777,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5778,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5779,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5780,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5781,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5782,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5783,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5784,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5785,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5786,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5787,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5788,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5789,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5790,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5791,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5792,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5793,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5794,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5795,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5796,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5797,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5798,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5799,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5800,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5801,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5802,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5803,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5804,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5805,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5806,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5807,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5808,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5809,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5810,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5811,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5812,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5813,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5814,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5815,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5816,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5817,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5818,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5819,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5820,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5821,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5822,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5823,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5824,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5825,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5826,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5827,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5828,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5829,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5830,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5831,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5832,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5833,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5834,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5835,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5836,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5837,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5838,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5839,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5840,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5841,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5842,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5843,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5844,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5845,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5846,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5847,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5848,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5849,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5850,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5851,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5852,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5853,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5854,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5855,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5856,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5857,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5858,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5859,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5860,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5861,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5862,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5863,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5864,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5865,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5866,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5867,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5868,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5869,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5870,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5871,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5872,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5873,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5874,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5875,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5876,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5877,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5878,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5879,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5880,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5881,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5882,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5883,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5884,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5885,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5886,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5887,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5888,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5889,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5890,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5891,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5892,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5893,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5894,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5895,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5896,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5897,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5898,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5899,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5900,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5901,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5902,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5903,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5904,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5905,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5906,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5907,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5908,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5909,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5910,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5911,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5912,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5913,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5914,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5915,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5916,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5917,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5918,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5919,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5920,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5921,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5922,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5923,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5924,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5925,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5926,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5927,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5928,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5929,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5930,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5931,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5932,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5933,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5934,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5935,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5936,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5937,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5938,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5939,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5940,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5941,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5942,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5943,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5944,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5945,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5946,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5947,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5948,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5949,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5950,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5951,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5952,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5953,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5954,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5955,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5956,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5957,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5958,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5959,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5960,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5961,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5962,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5963,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5964,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5965,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5966,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5967,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5968,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5969,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5970,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5971,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5972,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5973,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5974,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5975,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5976,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5977,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5978,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5979,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5980,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5981,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5982,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5983,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5984,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5985,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5986,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5987,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5988,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5989,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5990,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5991,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5992,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5993,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5994,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5995,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5996,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5997,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5998,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(5999,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6000,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6001,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6002,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6003,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6004,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6005,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6006,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6007,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6008,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6009,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6010,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6011,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6012,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6013,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6014,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6015,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6016,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6017,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6018,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6019,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6020,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6021,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6022,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6023,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6024,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6025,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6026,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6027,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6028,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6029,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6030,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6031,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6032,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6033,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6034,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6035,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6036,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6037,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6038,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6039,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6040,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6041,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6042,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6043,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6044,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6045,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6046,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6047,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6048,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6049,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6050,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6051,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6052,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6053,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6054,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6055,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6056,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6057,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6058,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6059,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6060,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6061,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6062,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6063,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6064,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6065,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6066,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6067,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6068,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6069,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6070,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6071,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6072,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6073,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6074,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6075,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6076,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6077,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6078,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6079,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6080,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6081,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6082,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6083,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6084,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6085,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6086,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6087,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6088,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6089,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6090,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6091,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6092,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6093,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6094,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6095,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6096,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6097,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6098,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6099,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6100,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6101,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6102,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6103,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6104,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6105,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6106,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6107,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6108,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6109,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6110,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6111,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6112,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6113,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6114,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6115,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6116,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6117,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6118,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6119,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6120,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6121,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6122,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6123,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6124,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6125,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6126,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6127,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6128,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6129,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6130,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6131,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6132,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6133,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6134,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6135,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6136,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6137,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6138,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6139,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6140,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6141,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6142,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6143,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6144,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6145,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6146,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6147,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6148,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6149,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6150,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6151,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6152,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6153,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6154,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6155,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6156,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6157,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6158,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6159,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6160,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6161,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6162,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6163,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6164,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6165,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6166,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6167,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6168,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6169,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6170,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6171,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6172,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6173,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6174,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6175,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6176,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6177,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6178,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6179,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6180,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6181,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6182,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6183,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6184,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6185,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6186,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6187,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6188,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6189,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6190,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6191,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6192,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6193,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6194,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6195,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6196,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6197,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6198,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6199,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6200,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6201,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6202,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6203,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6204,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6205,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6206,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6207,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6208,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6209,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6210,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6211,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6212,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6213,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6214,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6215,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6216,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6217,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6218,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6219,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6220,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6221,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6222,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6223,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6224,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6225,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6226,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6227,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6228,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6229,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6230,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6231,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6232,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6233,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6234,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6235,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6236,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6237,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6238,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6239,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6240,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6241,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6242,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6243,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6244,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6245,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6246,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6247,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6248,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6249,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6250,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6251,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6252,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6253,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6254,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6255,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6256,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6257,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6258,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6259,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6260,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6261,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6262,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6263,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6264,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6265,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6266,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6267,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6268,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6269,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6270,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6271,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6272,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6273,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6274,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6275,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6276,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6277,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6278,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6279,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6280,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6281,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6282,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6283,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6284,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6285,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6286,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6287,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6288,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6289,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6290,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6291,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6292,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6293,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6294,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6295,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6296,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6297,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6298,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6299,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6300,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6301,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6302,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6303,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6304,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6305,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6306,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6307,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6308,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6309,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6310,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6311,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6312,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6313,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6314,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6315,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6316,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6317,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6318,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6319,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6320,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6321,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6322,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6323,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6324,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6325,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6326,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6327,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6328,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6329,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6330,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6331,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6332,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6333,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6334,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6335,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6336,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6337,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6338,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6339,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6340,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6341,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6342,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6343,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6344,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6345,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6346,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6347,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6348,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6349,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6350,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6351,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6352,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6353,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6354,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6355,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6356,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6357,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6358,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6359,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6360,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6361,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6362,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6363,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6364,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6365,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6366,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6367,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6368,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6369,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6370,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6371,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6372,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6373,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6374,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6375,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6376,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6377,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6378,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6379,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6380,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6381,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6382,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6383,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6384,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6385,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6386,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6387,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6388,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6389,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6390,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6391,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6392,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6393,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6394,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6395,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6396,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6397,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6398,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6399,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6400,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6401,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6402,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6403,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6404,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6405,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6406,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6407,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6408,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6409,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6410,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6411,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6412,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6413,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6414,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6415,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6416,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6417,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6418,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6419,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6420,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6421,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6422,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6423,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6424,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6425,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6426,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6427,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6428,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6429,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6430,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6431,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6432,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6433,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6434,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6435,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6436,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6437,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6438,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6439,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6440,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6441,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6442,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6443,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6444,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6445,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6446,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6447,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6448,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6449,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6450,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6451,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6452,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6453,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6454,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6455,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6456,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6457,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6458,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6459,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6460,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6461,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6462,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6463,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6464,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6465,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6466,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6467,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6468,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6469,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6470,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6471,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6472,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6473,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6474,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6475,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6476,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6477,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6478,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6479,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6480,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6481,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6482,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6483,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6484,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6485,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6486,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6487,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6488,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6489,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6490,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6491,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6492,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6493,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6494,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6495,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6496,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6497,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6498,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6499,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6500,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6501,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6502,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6503,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6504,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6505,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6506,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6507,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6508,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6509,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6510,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6511,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6512,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6513,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6514,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6515,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6516,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6517,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6518,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6519,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6520,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6521,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6522,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6523,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6524,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6525,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6526,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6527,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6528,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6529,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6530,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6531,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6532,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6533,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6534,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6535,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6536,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6537,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6538,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6539,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6540,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6541,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6542,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6543,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6544,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6545,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6546,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6547,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6548,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6549,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6550,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6551,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6552,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6553,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6554,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6555,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6556,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6557,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6558,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6559,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6560,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6561,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6562,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6563,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6564,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6565,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6566,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6567,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6568,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6569,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6570,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6571,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6572,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6573,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6574,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6575,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6576,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6577,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6578,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6579,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6580,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6581,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6582,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6583,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6584,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6585,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6586,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6587,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6588,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6589,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6590,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6591,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6592,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6593,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6594,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6595,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6596,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6597,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6598,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6599,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6600,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6601,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6602,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6603,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6604,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6605,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6606,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6607,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6608,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6609,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6610,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6611,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6612,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6613,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6614,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6615,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6616,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6617,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6618,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6619,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6620,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6621,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6622,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6623,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6624,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6625,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6626,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6627,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6628,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6629,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6630,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6631,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6632,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6633,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6634,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6635,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6636,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6637,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6638,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6639,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6640,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6641,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6642,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6643,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6644,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6645,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6646,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6647,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6648,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6649,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6650,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6651,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6652,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6653,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6654,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6655,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6656,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6657,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6658,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6659,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6660,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6661,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6662,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6663,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6664,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6665,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6666,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6667,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6668,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6669,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6670,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6671,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6672,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6673,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6674,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6675,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6676,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6677,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6678,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6679,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6680,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6681,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6682,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6683,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6684,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6685,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6686,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6687,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6688,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6689,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6690,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6691,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6692,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6693,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6694,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6695,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6696,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6697,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6698,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6699,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6700,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6701,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6702,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6703,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6704,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6705,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6706,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6707,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6708,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6709,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6710,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6711,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6712,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6713,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6714,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6715,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6716,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6717,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6718,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6719,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6720,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6721,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6722,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6723,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6724,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6725,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6726,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6727,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6728,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6729,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6730,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6731,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6732,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6733,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6734,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6735,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6736,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6737,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6738,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6739,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6740,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6741,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6742,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6743,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6744,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6745,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6746,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6747,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6748,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6749,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6750,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6751,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6752,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6753,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6754,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6755,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6756,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6757,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6758,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6759,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6760,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6761,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6762,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6763,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6764,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6765,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6766,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6767,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6768,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6769,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6770,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6771,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6772,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6773,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6774,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6775,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6776,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6777,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6778,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6779,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6780,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6781,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6782,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6783,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6784,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6785,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6786,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6787,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6788,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6789,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6790,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6791,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6792,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6793,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6794,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6795,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6796,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6797,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6798,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6799,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6800,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6801,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6802,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6803,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6804,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6805,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6806,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6807,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6808,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6809,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6810,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6811,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6812,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6813,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6814,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6815,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6816,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6817,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6818,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6819,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6820,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6821,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6822,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6823,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6824,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6825,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6826,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6827,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6828,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6829,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6830,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6831,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6832,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6833,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6834,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6835,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6836,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6837,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6838,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6839,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6840,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6841,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6842,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6843,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6844,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6845,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6846,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6847,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6848,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6849,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6850,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6851,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6852,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6853,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6854,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6855,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6856,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6857,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6858,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6859,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6860,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6861,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6862,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6863,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6864,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6865,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6866,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6867,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6868,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6869,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6870,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6871,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6872,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6873,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6874,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6875,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6876,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6877,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6878,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6879,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6880,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6881,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6882,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6883,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6884,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6885,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6886,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6887,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6888,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6889,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6890,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6891,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6892,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6893,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6894,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6895,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6896,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6897,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6898,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6899,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6900,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6901,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6902,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6903,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6904,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6905,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6906,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6907,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6908,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6909,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6910,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6911,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6912,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6913,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6914,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6915,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6916,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6917,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6918,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6919,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6920,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6921,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6922,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6923,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6924,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6925,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6926,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6927,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6928,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6929,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6930,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6931,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6932,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6933,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6934,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6935,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6936,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6937,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6938,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6939,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6940,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6941,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6942,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6943,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6944,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6945,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6946,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6947,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6948,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6949,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6950,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6951,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6952,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6953,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6954,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6955,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6956,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6957,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6958,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6959,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6960,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6961,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6962,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6963,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6964,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6965,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6966,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6967,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6968,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6969,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6970,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6971,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6972,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6973,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6974,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6975,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6976,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6977,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6978,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6979,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6980,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6981,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6982,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6983,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6984,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6985,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6986,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6987,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6988,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6989,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6990,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6991,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6992,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6993,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6994,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6995,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6996,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6997,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6998,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(6999,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7000,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7001,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7002,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7003,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7004,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7005,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7006,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7007,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7008,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7009,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7010,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7011,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7012,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7013,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7014,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7015,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7016,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7017,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7018,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7019,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7020,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7021,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7022,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7023,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7024,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7025,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7026,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7027,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7028,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7029,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7030,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7031,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7032,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7033,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7034,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7035,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7036,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7037,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7038,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7039,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7040,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7041,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7042,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7043,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7044,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7045,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7046,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7047,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7048,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7049,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7050,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7051,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7052,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7053,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7054,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7055,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7056,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7057,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7058,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7059,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7060,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7061,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7062,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7063,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7064,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7065,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7066,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7067,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7068,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7069,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7070,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7071,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7072,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7073,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7074,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7075,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7076,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7077,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7078,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7079,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7080,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7081,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7082,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7083,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7084,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7085,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7086,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7087,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7088,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7089,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7090,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7091,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7092,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7093,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7094,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7095,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7096,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7097,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7098,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7099,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7100,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7101,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7102,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7103,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7104,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7105,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7106,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7107,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7108,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7109,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7110,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7111,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7112,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7113,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7114,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7115,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7116,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7117,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7118,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7119,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7120,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7121,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7122,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7123,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7124,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7125,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7126,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7127,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7128,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7129,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7130,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7131,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7132,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7133,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7134,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7135,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7136,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7137,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7138,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7139,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7140,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7141,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7142,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7143,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7144,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7145,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7146,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7147,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7148,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7149,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7150,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7151,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7152,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7153,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7154,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7155,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7156,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7157,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7158,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7159,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7160,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7161,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7162,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7163,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7164,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7165,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7166,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7167,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7168,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7169,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7170,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7171,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7172,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7173,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7174,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7175,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7176,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7177,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7178,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7179,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7180,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7181,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7182,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7183,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7184,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7185,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7186,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7187,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7188,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7189,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7190,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7191,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7192,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7193,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7194,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7195,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7196,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7197,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7198,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7199,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7200,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7201,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7202,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7203,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7204,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7205,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7206,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7207,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7208,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7209,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7210,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7211,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7212,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7213,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7214,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7215,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7216,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7217,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7218,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7219,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7220,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7221,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7222,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7223,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7224,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7225,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7226,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7227,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7228,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7229,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7230,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7231,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7232,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7233,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7234,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7235,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7236,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7237,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7238,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7239,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7240,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7241,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7242,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7243,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7244,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7245,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7246,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7247,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7248,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7249,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7250,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7251,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7252,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7253,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7254,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7255,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7256,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7257,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7258,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7259,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7260,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7261,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7262,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7263,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7264,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7265,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7266,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7267,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7268,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7269,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7270,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7271,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7272,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7273,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7274,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7275,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7276,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7277,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7278,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7279,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7280,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7281,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7282,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7283,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7284,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7285,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7286,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7287,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7288,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7289,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7290,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7291,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7292,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7293,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7294,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7295,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7296,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7297,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7298,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7299,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7300,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7301,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7302,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7303,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7304,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7305,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7306,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7307,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7308,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7309,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7310,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7311,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7312,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7313,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7314,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7315,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7316,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7317,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7318,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7319,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7320,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7321,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7322,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7323,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7324,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7325,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7326,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7327,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7328,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7329,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7330,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7331,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7332,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7333,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7334,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7335,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7336,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7337,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7338,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7339,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7340,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7341,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7342,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7343,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7344,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7345,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7346,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7347,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7348,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7349,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7350,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7351,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7352,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7353,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7354,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7355,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7356,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7357,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7358,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7359,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7360,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7361,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7362,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7363,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7364,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7365,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7366,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7367,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7368,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7369,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7370,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7371,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7372,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7373,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7374,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7375,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7376,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7377,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7378,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7379,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7380,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7381,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7382,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7383,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7384,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7385,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7386,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7387,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7388,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7389,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7390,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7391,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7392,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7393,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7394,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7395,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7396,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7397,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7398,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7399,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7400,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7401,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7402,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7403,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7404,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7405,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7406,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7407,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7408,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7409,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7410,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7411,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7412,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7413,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7414,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7415,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7416,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7417,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7418,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7419,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7420,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7421,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7422,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7423,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7424,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7425,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7426,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7427,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7428,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7429,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7430,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7431,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7432,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7433,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7434,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7435,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7436,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7437,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7438,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7439,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7440,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7441,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7442,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7443,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7444,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7445,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7446,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7447,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7448,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7449,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7450,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7451,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7452,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7453,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7454,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7455,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7456,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7457,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7458,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7459,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7460,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7461,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7462,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7463,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7464,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7465,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7466,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7467,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7468,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7469,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7470,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7471,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7472,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7473,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7474,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7475,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7476,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7477,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7478,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7479,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7480,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7481,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7482,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7483,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7484,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7485,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7486,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7487,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7488,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7489,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7490,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7491,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7492,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7493,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7494,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7495,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7496,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7497,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7498,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7499,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7500,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7501,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7502,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7503,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7504,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7505,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7506,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7507,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7508,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7509,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7510,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7511,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7512,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7513,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7514,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7515,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7516,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7517,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7518,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7519,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7520,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7521,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7522,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7523,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7524,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7525,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7526,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7527,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7528,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7529,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7530,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7531,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7532,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7533,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7534,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7535,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7536,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7537,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7538,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7539,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7540,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7541,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7542,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7543,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7544,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7545,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7546,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7547,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7548,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7549,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7550,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7551,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7552,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7553,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7554,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7555,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7556,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7557,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7558,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7559,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7560,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7561,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7562,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7563,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7564,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7565,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7566,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7567,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7568,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7569,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7570,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7571,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7572,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7573,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7574,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7575,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7576,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7577,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7578,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7579,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7580,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7581,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7582,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7583,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7584,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7585,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7586,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7587,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7588,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7589,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7590,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7591,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7592,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7593,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7594,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7595,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7596,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7597,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7598,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7599,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7600,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7601,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7602,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7603,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7604,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7605,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7606,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7607,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7608,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7609,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7610,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7611,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7612,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7613,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7614,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7615,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7616,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7617,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7618,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7619,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7620,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7621,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7622,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7623,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7624,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7625,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7626,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7627,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7628,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7629,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7630,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7631,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7632,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7633,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7634,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7635,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7636,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7637,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7638,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7639,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7640,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7641,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7642,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7643,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7644,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7645,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7646,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7647,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7648,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7649,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7650,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7651,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7652,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7653,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7654,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7655,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7656,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7657,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7658,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7659,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7660,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7661,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7662,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7663,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7664,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7665,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7666,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7667,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7668,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7669,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7670,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7671,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7672,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7673,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7674,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7675,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7676,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7677,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7678,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7679,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7680,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7681,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7682,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7683,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7684,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7685,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7686,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7687,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7688,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7689,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7690,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7691,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7692,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7693,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7694,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7695,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7696,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7697,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7698,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7699,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7700,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7701,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7702,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7703,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7704,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7705,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7706,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7707,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7708,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7709,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7710,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7711,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7712,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7713,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7714,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7715,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7716,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7717,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7718,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7719,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7720,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7721,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7722,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7723,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7724,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7725,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7726,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7727,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7728,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7729,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7730,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7731,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7732,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7733,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7734,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7735,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7736,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7737,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7738,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7739,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7740,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7741,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7742,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7743,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7744,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7745,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7746,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7747,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7748,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7749,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7750,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7751,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7752,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7753,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7754,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7755,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7756,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7757,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7758,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7759,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7760,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7761,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7762,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7763,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7764,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7765,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7766,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7767,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7768,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7769,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7770,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7771,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7772,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7773,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7774,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7775,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7776,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7777,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7778,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7779,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7780,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7781,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7782,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7783,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7784,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7785,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7786,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7787,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7788,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7789,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7790,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7791,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7792,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7793,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7794,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7795,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7796,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7797,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7798,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7799,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7800,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7801,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7802,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7803,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7804,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7805,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7806,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7807,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7808,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7809,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7810,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7811,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7812,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7813,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7814,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7815,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7816,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7817,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7818,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7819,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7820,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7821,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7822,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7823,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7824,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7825,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7826,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7827,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7828,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7829,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7830,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7831,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7832,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7833,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7834,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7835,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7836,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7837,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7838,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7839,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7840,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7841,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7842,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7843,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7844,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7845,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7846,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7847,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7848,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7849,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7850,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7851,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7852,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7853,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7854,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7855,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7856,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7857,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7858,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7859,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7860,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7861,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7862,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7863,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7864,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7865,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7866,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7867,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7868,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7869,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7870,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7871,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7872,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7873,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7874,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7875,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7876,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7877,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7878,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7879,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7880,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7881,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7882,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7883,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7884,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7885,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7886,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7887,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7888,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7889,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7890,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7891,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7892,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7893,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7894,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7895,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7896,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7897,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7898,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7899,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7900,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7901,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7902,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7903,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7904,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7905,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7906,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7907,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7908,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7909,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7910,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7911,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7912,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7913,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7914,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7915,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7916,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7917,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7918,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7919,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7920,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7921,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7922,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7923,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7924,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7925,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7926,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7927,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7928,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7929,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7930,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7931,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7932,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7933,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7934,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7935,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7936,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7937,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7938,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7939,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7940,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7941,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7942,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7943,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7944,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7945,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7946,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7947,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7948,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7949,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7950,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7951,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7952,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7953,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7954,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7955,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7956,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7957,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7958,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7959,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7960,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7961,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7962,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7963,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7964,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7965,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7966,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7967,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7968,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7969,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7970,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7971,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7972,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7973,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7974,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7975,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7976,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7977,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7978,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7979,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7980,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7981,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7982,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7983,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7984,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7985,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7986,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7987,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7988,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7989,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7990,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7991,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7992,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7993,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7994,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7995,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7996,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7997,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7998,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(7999,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8000,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8001,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8002,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8003,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8004,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8005,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8006,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8007,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8008,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8009,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8010,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8011,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8012,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8013,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8014,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8015,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8016,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8017,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8018,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8019,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8020,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8021,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8022,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8023,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8024,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8025,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8026,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8027,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8028,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8029,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8030,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8031,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8032,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8033,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8034,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8035,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8036,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8037,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8038,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8039,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8040,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8041,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8042,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8043,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8044,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8045,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8046,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8047,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8048,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8049,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8050,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8051,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8052,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8053,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8054,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8055,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8056,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8057,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8058,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8059,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8060,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8061,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8062,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8063,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8064,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8065,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8066,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8067,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8068,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8069,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8070,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8071,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8072,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8073,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8074,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8075,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8076,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8077,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8078,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8079,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8080,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8081,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8082,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8083,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8084,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8085,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8086,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8087,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8088,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8089,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8090,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8091,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8092,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8093,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8094,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8095,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8096,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8097,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8098,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8099,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8100,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8101,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8102,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8103,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8104,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8105,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8106,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8107,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8108,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8109,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8110,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8111,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8112,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8113,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8114,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8115,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8116,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8117,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8118,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8119,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8120,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8121,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8122,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8123,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8124,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8125,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8126,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8127,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8128,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8129,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8130,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8131,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8132,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8133,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8134,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8135,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8136,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8137,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8138,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8139,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8140,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8141,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8142,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8143,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8144,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8145,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8146,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8147,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8148,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8149,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8150,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8151,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8152,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8153,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8154,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8155,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8156,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8157,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8158,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8159,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8160,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8161,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8162,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8163,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8164,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8165,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8166,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8167,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8168,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8169,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8170,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8171,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8172,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8173,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8174,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8175,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8176,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8177,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8178,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8179,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8180,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8181,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8182,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8183,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8184,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8185,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8186,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8187,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8188,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8189,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8190,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8191,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8192,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8193,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8194,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8195,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8196,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8197,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8198,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8199,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8200,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8201,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8202,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8203,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8204,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8205,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8206,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8207,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8208,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8209,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8210,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8211,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8212,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8213,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8214,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8215,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8216,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8217,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8218,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8219,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8220,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8221,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8222,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8223,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8224,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8225,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8226,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8227,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8228,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8229,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8230,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8231,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8232,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8233,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8234,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8235,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8236,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8237,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8238,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8239,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8240,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8241,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8242,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8243,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8244,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8245,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8246,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8247,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8248,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8249,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8250,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8251,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8252,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8253,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8254,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8255,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8256,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8257,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8258,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8259,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8260,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8261,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8262,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8263,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8264,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8265,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8266,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8267,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8268,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8269,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8270,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8271,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8272,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8273,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8274,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8275,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8276,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8277,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8278,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8279,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8280,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8281,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8282,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8283,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8284,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8285,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8286,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8287,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8288,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8289,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8290,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8291,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8292,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8293,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8294,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8295,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8296,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8297,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8298,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8299,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8300,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8301,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8302,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8303,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8304,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8305,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8306,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8307,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8308,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8309,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8310,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8311,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8312,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8313,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8314,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8315,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8316,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8317,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8318,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8319,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8320,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8321,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8322,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8323,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8324,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8325,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8326,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8327,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8328,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8329,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8330,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8331,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8332,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8333,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8334,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8335,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8336,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8337,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8338,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8339,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8340,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8341,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8342,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8343,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8344,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8345,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8346,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8347,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8348,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8349,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8350,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8351,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8352,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8353,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8354,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8355,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8356,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8357,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8358,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8359,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8360,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8361,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8362,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8363,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8364,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8365,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8366,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8367,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8368,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8369,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8370,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8371,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8372,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8373,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8374,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8375,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8376,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8377,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8378,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8379,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8380,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8381,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8382,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8383,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8384,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8385,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8386,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8387,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8388,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8389,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8390,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8391,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8392,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8393,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8394,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8395,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8396,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8397,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8398,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8399,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8400,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8401,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8402,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8403,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8404,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8405,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8406,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8407,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8408,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8409,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8410,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8411,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8412,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8413,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8414,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8415,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8416,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8417,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8418,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8419,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8420,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8421,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8422,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8423,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8424,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8425,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8426,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8427,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8428,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8429,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8430,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8431,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8432,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8433,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8434,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8435,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8436,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8437,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8438,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8439,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8440,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8441,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8442,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8443,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8444,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8445,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8446,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8447,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8448,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8449,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8450,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8451,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8452,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8453,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8454,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8455,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8456,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8457,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8458,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8459,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8460,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8461,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8462,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8463,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8464,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8465,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8466,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8467,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8468,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8469,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8470,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8471,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8472,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8473,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8474,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8475,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8476,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8477,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8478,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8479,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8480,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8481,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8482,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8483,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8484,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8485,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8486,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8487,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8488,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8489,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8490,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8491,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8492,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8493,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8494,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8495,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8496,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8497,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8498,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8499,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8500,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8501,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8502,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8503,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8504,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8505,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8506,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8507,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8508,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8509,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8510,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8511,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8512,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8513,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8514,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8515,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8516,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8517,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8518,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8519,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8520,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8521,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8522,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8523,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8524,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8525,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8526,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8527,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8528,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8529,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8530,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8531,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8532,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8533,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8534,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8535,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8536,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8537,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8538,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8539,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8540,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8541,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8542,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8543,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8544,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8545,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8546,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8547,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8548,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8549,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8550,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8551,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8552,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8553,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8554,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8555,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8556,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8557,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8558,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8559,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8560,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8561,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8562,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8563,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8564,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8565,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8566,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8567,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8568,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8569,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8570,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8571,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8572,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8573,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8574,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8575,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8576,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8577,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8578,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8579,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8580,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8581,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8582,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8583,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8584,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8585,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8586,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8587,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8588,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8589,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8590,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8591,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8592,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8593,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8594,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8595,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8596,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8597,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8598,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8599,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8600,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8601,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8602,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8603,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8604,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8605,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8606,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8607,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8608,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8609,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8610,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8611,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8612,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8613,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8614,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8615,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8616,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8617,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8618,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8619,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8620,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8621,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8622,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8623,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8624,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8625,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8626,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8627,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8628,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8629,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8630,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8631,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8632,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8633,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8634,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8635,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8636,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8637,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8638,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8639,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8640,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8641,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8642,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8643,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8644,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8645,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8646,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8647,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8648,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8649,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8650,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8651,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8652,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8653,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8654,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8655,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8656,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8657,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8658,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8659,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8660,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8661,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8662,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8663,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8664,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8665,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8666,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8667,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8668,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8669,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8670,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8671,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8672,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8673,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8674,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8675,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8676,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8677,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8678,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8679,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8680,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8681,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8682,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8683,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8684,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8685,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8686,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8687,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8688,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8689,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8690,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8691,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8692,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8693,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8694,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8695,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8696,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8697,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8698,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8699,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8700,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8701,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8702,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8703,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8704,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8705,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8706,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8707,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8708,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8709,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8710,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8711,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8712,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8713,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8714,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8715,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8716,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8717,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8718,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8719,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8720,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8721,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8722,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8723,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8724,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8725,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8726,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8727,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8728,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8729,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8730,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8731,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8732,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8733,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8734,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8735,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8736,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8737,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8738,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8739,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8740,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8741,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8742,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8743,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8744,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8745,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8746,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8747,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8748,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8749,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8750,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8751,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8752,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8753,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8754,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8755,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8756,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8757,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8758,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8759,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8760,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8761,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8762,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8763,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8764,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8765,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8766,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8767,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8768,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8769,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8770,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8771,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8772,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8773,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8774,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8775,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8776,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8777,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8778,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8779,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8780,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8781,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8782,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8783,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8784,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8785,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8786,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8787,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8788,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8789,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8790,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8791,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8792,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8793,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8794,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8795,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8796,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8797,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8798,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8799,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8800,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8801,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8802,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8803,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8804,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8805,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8806,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8807,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8808,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8809,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8810,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8811,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8812,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8813,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8814,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8815,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8816,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8817,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8818,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8819,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8820,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8821,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8822,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8823,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8824,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8825,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8826,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8827,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8828,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8829,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8830,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8831,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8832,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8833,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8834,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8835,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8836,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8837,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8838,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8839,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8840,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8841,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8842,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8843,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8844,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8845,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8846,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8847,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8848,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8849,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8850,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8851,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8852,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8853,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8854,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8855,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8856,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8857,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8858,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8859,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8860,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8861,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8862,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8863,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8864,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8865,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8866,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8867,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8868,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8869,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8870,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8871,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8872,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8873,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8874,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8875,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8876,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8877,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8878,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8879,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8880,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8881,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8882,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8883,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8884,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8885,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8886,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8887,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8888,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8889,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8890,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8891,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8892,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8893,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8894,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8895,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8896,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8897,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8898,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8899,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8900,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8901,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8902,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8903,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8904,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8905,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8906,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8907,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8908,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8909,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8910,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8911,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8912,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8913,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8914,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8915,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8916,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8917,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8918,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8919,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8920,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8921,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8922,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8923,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8924,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8925,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8926,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8927,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8928,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8929,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8930,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8931,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8932,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8933,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8934,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8935,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8936,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8937,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8938,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8939,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8940,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8941,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8942,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8943,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8944,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8945,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8946,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8947,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8948,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8949,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8950,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8951,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8952,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8953,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8954,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8955,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8956,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8957,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8958,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8959,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8960,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8961,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8962,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8963,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8964,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8965,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8966,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8967,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8968,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8969,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8970,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8971,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8972,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8973,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8974,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8975,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8976,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8977,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8978,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8979,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8980,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8981,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8982,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8983,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8984,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8985,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8986,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8987,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8988,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8989,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8990,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8991,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8992,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8993,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8994,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8995,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8996,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8997,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8998,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(8999,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9000,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9001,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9002,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9003,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9004,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9005,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9006,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9007,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9008,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9009,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9010,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9011,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9012,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9013,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9014,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9015,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9016,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9017,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9018,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9019,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9020,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9021,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9022,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9023,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9024,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9025,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9026,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9027,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9028,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9029,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9030,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9031,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9032,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9033,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9034,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9035,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9036,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9037,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9038,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9039,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9040,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9041,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9042,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9043,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9044,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9045,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9046,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9047,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9048,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9049,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9050,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9051,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9052,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9053,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9054,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9055,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9056,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9057,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9058,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9059,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9060,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9061,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9062,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9063,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9064,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9065,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9066,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9067,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9068,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9069,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9070,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9071,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9072,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9073,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9074,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9075,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9076,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9077,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9078,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9079,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9080,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9081,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9082,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9083,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9084,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9085,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9086,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9087,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9088,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9089,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9090,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9091,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9092,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9093,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9094,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9095,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9096,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9097,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9098,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9099,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9100,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9101,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9102,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9103,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9104,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9105,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9106,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9107,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9108,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9109,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9110,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9111,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9112,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9113,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9114,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9115,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9116,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9117,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9118,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9119,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9120,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9121,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9122,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9123,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9124,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9125,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9126,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9127,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9128,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9129,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9130,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9131,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9132,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9133,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9134,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9135,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9136,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9137,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9138,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9139,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9140,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9141,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9142,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9143,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9144,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9145,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9146,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9147,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9148,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9149,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9150,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9151,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9152,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9153,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9154,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9155,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9156,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9157,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9158,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9159,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9160,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9161,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9162,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9163,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9164,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9165,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9166,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9167,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9168,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9169,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9170,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9171,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9172,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9173,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9174,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9175,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9176,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9177,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9178,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9179,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9180,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9181,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9182,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9183,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9184,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9185,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9186,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9187,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9188,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9189,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9190,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9191,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9192,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9193,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9194,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9195,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9196,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9197,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9198,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9199,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9200,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9201,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9202,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9203,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9204,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9205,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9206,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9207,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9208,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9209,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9210,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9211,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9212,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9213,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9214,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9215,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9216,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9217,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9218,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9219,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9220,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9221,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9222,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9223,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9224,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9225,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9226,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9227,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9228,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9229,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9230,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9231,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9232,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9233,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9234,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9235,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9236,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9237,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9238,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9239,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9240,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9241,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9242,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9243,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9244,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9245,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9246,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9247,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9248,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9249,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9250,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9251,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9252,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9253,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9254,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9255,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9256,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9257,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9258,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9259,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9260,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9261,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9262,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9263,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9264,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9265,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9266,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9267,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9268,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9269,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9270,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9271,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9272,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9273,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9274,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9275,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9276,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9277,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9278,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9279,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9280,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9281,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9282,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9283,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9284,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9285,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9286,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9287,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9288,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9289,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9290,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9291,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9292,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9293,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9294,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9295,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9296,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9297,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9298,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9299,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9300,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9301,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9302,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9303,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9304,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9305,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9306,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9307,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9308,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9309,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9310,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9311,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9312,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9313,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9314,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9315,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9316,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9317,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9318,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9319,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9320,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9321,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9322,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9323,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9324,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9325,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9326,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9327,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9328,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9329,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9330,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9331,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9332,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9333,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9334,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9335,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9336,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9337,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9338,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9339,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9340,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9341,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9342,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9343,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9344,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9345,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9346,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9347,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9348,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9349,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9350,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9351,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9352,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9353,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9354,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9355,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9356,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9357,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9358,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9359,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9360,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9361,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9362,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9363,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9364,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9365,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9366,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9367,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9368,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9369,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9370,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9371,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9372,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9373,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9374,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9375,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9376,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9377,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9378,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9379,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9380,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9381,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9382,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9383,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9384,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9385,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9386,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9387,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9388,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9389,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9390,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9391,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9392,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9393,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9394,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9395,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9396,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9397,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9398,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9399,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9400,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9401,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9402,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9403,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9404,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9405,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9406,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9407,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9408,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9409,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9410,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9411,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9412,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9413,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9414,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9415,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9416,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9417,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9418,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9419,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9420,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9421,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9422,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9423,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9424,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9425,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9426,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9427,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9428,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9429,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9430,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9431,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9432,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9433,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9434,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9435,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9436,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9437,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9438,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9439,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9440,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9441,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9442,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9443,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9444,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9445,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9446,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9447,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9448,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9449,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9450,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9451,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9452,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9453,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9454,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9455,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9456,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9457,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9458,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9459,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9460,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9461,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9462,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9463,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9464,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9465,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9466,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9467,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9468,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9469,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9470,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9471,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9472,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9473,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9474,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9475,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9476,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9477,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9478,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9479,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9480,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9481,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9482,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9483,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9484,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9485,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9486,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9487,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9488,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9489,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9490,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9491,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9492,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9493,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9494,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9495,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9496,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9497,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9498,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9499,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9500,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9501,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9502,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9503,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9504,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9505,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9506,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9507,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9508,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9509,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9510,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9511,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9512,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9513,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9514,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9515,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9516,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9517,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9518,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9519,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9520,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9521,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9522,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9523,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9524,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9525,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9526,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9527,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9528,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9529,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9530,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9531,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9532,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9533,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9534,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9535,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9536,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9537,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9538,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9539,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9540,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9541,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9542,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9543,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9544,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9545,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9546,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9547,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9548,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9549,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9550,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9551,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9552,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9553,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9554,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9555,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9556,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9557,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9558,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9559,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9560,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9561,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9562,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9563,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9564,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9565,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9566,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9567,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9568,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9569,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9570,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9571,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9572,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9573,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9574,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9575,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9576,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9577,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9578,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9579,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9580,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9581,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9582,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9583,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9584,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9585,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9586,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9587,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9588,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9589,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9590,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9591,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9592,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9593,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9594,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9595,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9596,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9597,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9598,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9599,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9600,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9601,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9602,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9603,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9604,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9605,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9606,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9607,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9608,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9609,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9610,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9611,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9612,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9613,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9614,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9615,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9616,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9617,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9618,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9619,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9620,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9621,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9622,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9623,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9624,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9625,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9626,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9627,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9628,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9629,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9630,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9631,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9632,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9633,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9634,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9635,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9636,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9637,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9638,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9639,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9640,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9641,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9642,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9643,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9644,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9645,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9646,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9647,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9648,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9649,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9650,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9651,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9652,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9653,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9654,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9655,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9656,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9657,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9658,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9659,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9660,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9661,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9662,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9663,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9664,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9665,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9666,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9667,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9668,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9669,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9670,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9671,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9672,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9673,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9674,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9675,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9676,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9677,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9678,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9679,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9680,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9681,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9682,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9683,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9684,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9685,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9686,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9687,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9688,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9689,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9690,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9691,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9692,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9693,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9694,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9695,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9696,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9697,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9698,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9699,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9700,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9701,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9702,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9703,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9704,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9705,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9706,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9707,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9708,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9709,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9710,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9711,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9712,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9713,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9714,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9715,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9716,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9717,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9718,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9719,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9720,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9721,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9722,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9723,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9724,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9725,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9726,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9727,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9728,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9729,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9730,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9731,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9732,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9733,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9734,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9735,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9736,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9737,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9738,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9739,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9740,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9741,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9742,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9743,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9744,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9745,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9746,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9747,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9748,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9749,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9750,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9751,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9752,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9753,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9754,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9755,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9756,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9757,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9758,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9759,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9760,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9761,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9762,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9763,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9764,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9765,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9766,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9767,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9768,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9769,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9770,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9771,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9772,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9773,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9774,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9775,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9776,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9777,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9778,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9779,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9780,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9781,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9782,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9783,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9784,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9785,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9786,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9787,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9788,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9789,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9790,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9791,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9792,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9793,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9794,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9795,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9796,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9797,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9798,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9799,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9800,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9801,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9802,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9803,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9804,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9805,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9806,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9807,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9808,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9809,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9810,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9811,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9812,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9813,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9814,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9815,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9816,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9817,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9818,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9819,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9820,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9821,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9822,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9823,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9824,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9825,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9826,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9827,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9828,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9829,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9830,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9831,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9832,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9833,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9834,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9835,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9836,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9837,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9838,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9839,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9840,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9841,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9842,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9843,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9844,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9845,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9846,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9847,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9848,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9849,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9850,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9851,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9852,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9853,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9854,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9855,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9856,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9857,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9858,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9859,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9860,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9861,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9862,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9863,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9864,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9865,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9866,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9867,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9868,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9869,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9870,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9871,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9872,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9873,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9874,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9875,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9876,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9877,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9878,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9879,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9880,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9881,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9882,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9883,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9884,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9885,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9886,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9887,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9888,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9889,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9890,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9891,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9892,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9893,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9894,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9895,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9896,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9897,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9898,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9899,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9900,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9901,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9902,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9903,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9904,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9905,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9906,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9907,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9908,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9909,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9910,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9911,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9912,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9913,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9914,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9915,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9916,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9917,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9918,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9919,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9920,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9921,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9922,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9923,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9924,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9925,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9926,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9927,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9928,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9929,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9930,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9931,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9932,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9933,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9934,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9935,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9936,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9937,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9938,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9939,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9940,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9941,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9942,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9943,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9944,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9945,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9946,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9947,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9948,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9949,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9950,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9951,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9952,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9953,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9954,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9955,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9956,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9957,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9958,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9959,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9960,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9961,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9962,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9963,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9964,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9965,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9966,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9967,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9968,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9969,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9970,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9971,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9972,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9973,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9974,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9975,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9976,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9977,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9978,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9979,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9980,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9981,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9982,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9983,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9984,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9985,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9986,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9987,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9988,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9989,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9990,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9991,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9992,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9993,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9994,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9995,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9996,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9997,NULL,0);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9998,1440090000,1);
INSERT INTO `tmp_product` (`id`,`last_used`,`status`) VALUES
(9999,1440090000,1);



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
(1,'Mừng ngày Quốc Khánh','DA2015001',20,1439226000,1441126800,1,6);
INSERT INTO `voucher` (`id`,`name`,`code`,`discount`,`start_date`,`end_date`,`active`,`order_id`) VALUES
(2,'Trung Thu - Tết Đoàn Viên','DA2015002',30,1439917200,1443373200,1,7);
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
CREATE OR REPLACE VIEW `order_view` AS select `o`.`id` AS `order_id`,`o`.`order_date` AS `order_date`,`o`.`receiving_date` AS `receiving_date`,`o`.`shipping_fee` AS `shipping_fee`,`o`.`description` AS `description`,`g`.`full_name` AS `full_name`,`g`.`email` AS `email`,`g`.`phone_number` AS `phone_number`,`os`.`id` AS `order_status_id`,concat(`a`.`detail`,' - ',`d`.`name`,' - ',`c`.`name`) AS `address`,`v`.`discount` AS `voucher_discount` from ((((((`order` `o` join `order_status` `os` on((`o`.`order_status_id` = `os`.`id`))) join `guest` `g` on((`g`.`id` = `o`.`guest_id`))) join `order_address` `a` on((`o`.`order_address_id` = `a`.`id`))) join `district` `d` on((`d`.`id` = `a`.`district_id`))) join `city` `c` on((`c`.`id` = `d`.`city_id`))) left join `voucher` `v` on((`o`.`id` = `v`.`order_id`))) order by `o`.`id`;

-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
SET SQL_MODE=@OLD_SQL_MODE;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------

-- MySQL Workbench Synchronization
-- Generated: 2015-04-24 10:47
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Dung

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `fsmdb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

CREATE TABLE IF NOT EXISTS `useraccount` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` INT(11) NOT NULL,
  `password` VARCHAR(20) NOT NULL,
  `avatar` VARCHAR(60) NULL DEFAULT NULL,
  `dob` DATE NULL DEFAULT NULL,
  `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
  `order_list` VARCHAR(45) NULL DEFAULT NULL,
  `password_reset_token` CHAR(32) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `status` TINYINT(4) NOT NULL DEFAULT 1,
  `address_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_UserAccount_Address1_idx` (`address_id` ASC),
  CONSTRAINT `fk_UserAccount_Address1`
    FOREIGN KEY (`address_id`)
    REFERENCES `address` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone_number` CHAR(15) NOT NULL,
  `useraccount_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_User_UserAccount_idx` (`useraccount_id` ASC),
  CONSTRAINT `fk_User_UserAccount`
    FOREIGN KEY (`useraccount_id`)
    REFERENCES `useraccount` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `order` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_date` DATETIME NOT NULL,
  `receiving_date` DATETIME NOT NULL,
  `shipping_fee` FLOAT(10) UNSIGNED NOT NULL,
  `discount` FLOAT(10) UNSIGNED NOT NULL,
  `tax_amount` FLOAT(10) UNSIGNED NOT NULL,
  `net_amount` FLOAT(10) UNSIGNED NOT NULL,
  `description` TEXT NOT NULL,
  `status` TINYINT(4) NOT NULL DEFAULT 1,
  `user_id` INT(11) NOT NULL,
  `voucher_id` INT(11) NULL DEFAULT NULL,
  `address_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Order_Voucher1_idx` (`voucher_id` ASC),
  INDEX `fk_Order_Address1_idx` (`address_id` ASC),
  INDEX `fk_Order_User1_idx` (`user_id` ASC),
  CONSTRAINT `fk_Order_Voucher1`
    FOREIGN KEY (`voucher_id`)
    REFERENCES `voucher` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Order_Address1`
    FOREIGN KEY (`address_id`)
    REFERENCES `address` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Order_User1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `voucher` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `code` CHAR(32) NOT NULL,
  `discount` FLOAT(10) UNSIGNED NOT NULL,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  `active` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `address` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `detail` VARCHAR(100) NOT NULL,
  `ward_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Address_Ward1_idx` (`ward_id` ASC),
  CONSTRAINT `fk_Address_Ward1`
    FOREIGN KEY (`ward_id`)
    REFERENCES `ward` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `ward` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `district_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Ward_District1_idx` (`district_id` ASC),
  CONSTRAINT `fk_Ward_District1`
    FOREIGN KEY (`district_id`)
    REFERENCES `district` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `district` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `city_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_District_City1_idx` (`city_id` ASC),
  CONSTRAINT `fk_District_City1`
    FOREIGN KEY (`city_id`)
    REFERENCES `city` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `city` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `order_detail` (
  `order_id` INT(11) NOT NULL,
  `product_d` INT(11) NOT NULL,
  `product_price` FLOAT(10) UNSIGNED NOT NULL,
  `quantity` INT(10) UNSIGNED NOT NULL,
  `discount` FLOAT(10) UNSIGNED NOT NULL,
  `active` TINYINT(4) NOT NULL DEFAULT 1,
  INDEX `fk_OrderDetail_Order1_idx` (`order_id` ASC),
  INDEX `fk_OrderDetail_Product1_idx` (`product_d` ASC),
  CONSTRAINT `fk_OrderDetail_Order1`
    FOREIGN KEY (`order_id`)
    REFERENCES `order` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_OrderDetail_Product1`
    FOREIGN KEY (`product_d`)
    REFERENCES `product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `product` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `barcode` CHAR(20) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `price` FLOAT(10) UNSIGNED NOT NULL,
  `old_price` FLOAT(10) UNSIGNED NULL DEFAULT NULL,
  `description` TEXT NOT NULL,
  `total` INT(10) UNSIGNED NOT NULL,
  `sold` INT(10) UNSIGNED NOT NULL,
  `tax` FLOAT(10) UNSIGNED NOT NULL,
  `fee` FLOAT(10) UNSIGNED NULL DEFAULT NULL,
  `active` TINYINT(4) NOT NULL,
  `category_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Product_Category1_idx` (`category_id` ASC),
  CONSTRAINT `fk_Product_Category1`
    FOREIGN KEY (`category_id`)
    REFERENCES `category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `offer` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NULL DEFAULT NULL,
  `price` FLOAT(10) UNSIGNED NOT NULL,
  `description` NVARCHAR(100) NOT NULL,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  `active` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_Offer_Product1_idx` (`product_id` ASC),
  CONSTRAINT `fk_Offer_Product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `shopping_cart` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `session` VARCHAR(30) NOT NULL,
  `date` DATETIME NOT NULL,
  `price` FLOAT(10) UNSIGNED NOT NULL,
  `quantity` INT(10) UNSIGNED NOT NULL,
  `active` TINYINT(4) NOT NULL DEFAULT 0,
  `product_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_ShoppingCart_Product1_idx` (`product_id` ASC),
  CONSTRAINT `fk_ShoppingCart_Product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `rating` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `rating` FLOAT(2,1) NOT NULL DEFAULT 0,
  `description` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `Rating_UNIQUE` (`rating` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `image` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `path` VARCHAR(100) NOT NULL,
  `product_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Image_Product1_idx` (`product_id` ASC),
  CONSTRAINT `fk_Image_Product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `slide_show` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(100) NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` VARCHAR(150) NOT NULL,
  `active` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `faq` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(150) NOT NULL,
  `answer` VARCHAR(200) NOT NULL,
  `active` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `wish_list` (
  `product_id` INT(11) NOT NULL,
  `useraccount_id` INT(11) NOT NULL,
  INDEX `fk_WishList_Product1_idx` (`product_id` ASC),
  INDEX `fk_WishList_UserAccount1_idx` (`useraccount_id` ASC),
  CONSTRAINT `fk_WishList_Product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_WishList_UserAccount1`
    FOREIGN KEY (`useraccount_id`)
    REFERENCES `useraccount` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `staff` (
  `id` INT(11) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `dob` DATE NOT NULL,
  `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
  `phone_number` CHAR(15) NOT NULL,
  `email` VARCHAR(45) NULL DEFAULT NULL,
  `image` VARCHAR(45) NOT NULL,
  `start_date` DATE NOT NULL,
  `address_id` INT(11) NOT NULL,
  `status` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Staff_Address1_idx` (`address_id` ASC),
  CONSTRAINT `fk_Staff_Address1`
    FOREIGN KEY (`address_id`)
    REFERENCES `address` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `product_rating` (
  `rating_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  PRIMARY KEY (`rating_id`, `product_id`),
  INDEX `fk_Rating_has_Product_Product1_idx` (`product_id` ASC),
  INDEX `fk_Rating_has_Product_Rating1_idx` (`rating_id` ASC),
  CONSTRAINT `fk_Rating_has_Product_Rating1`
    FOREIGN KEY (`rating_id`)
    REFERENCES `rating` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Rating_has_Product_Product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

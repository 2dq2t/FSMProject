-- MySQL Workbench Synchronization
-- Generated: 2015-04-23 10:44
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Dung

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `fsmdb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

CREATE TABLE IF NOT EXISTS `UserAccount` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `UserName` INT(11) NOT NULL,
  `Password` VARCHAR(20) NOT NULL,
  `Avatar` VARCHAR(60) NULL DEFAULT NULL,
  `DOB` DATE NULL DEFAULT NULL,
  `Gender` ENUM('Male', 'Female', 'Other') NOT NULL,
  `OrderList` VARCHAR(45) NULL DEFAULT NULL,
  `PasswordResetToken` CHAR(32) NULL DEFAULT NULL,
  `CreatedAt` DATETIME NOT NULL,
  `UpdatedAt` DATETIME NULL DEFAULT NULL,
  `Status` TINYINT(4) NOT NULL DEFAULT 1,
  `AddressId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_UserAccount_Address1_idx` (`AddressId` ASC),
  CONSTRAINT `fk_UserAccount_Address1`
    FOREIGN KEY (`AddressId`)
    REFERENCES `Address` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `User` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `FullName` VARCHAR(100) NOT NULL,
  `Email` VARCHAR(100) NOT NULL,
  `PhoneNumber` CHAR(11) NOT NULL,
  `UserAccountId` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_User_UserAccount_idx` (`UserAccountId` ASC),
  CONSTRAINT `fk_User_UserAccount`
    FOREIGN KEY (`UserAccountId`)
    REFERENCES `UserAccount` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `Order` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `OrderDate` DATETIME NOT NULL,
  `ReceivingDate` DATETIME NOT NULL,
  `ShippingFee` FLOAT(11) NOT NULL,
  `Discount` FLOAT(11) NOT NULL,
  `TaxAmount` FLOAT(11) NOT NULL,
  `NetAmount` FLOAT(11) NOT NULL,
  `Description` TEXT NOT NULL,
  `Status` TINYINT(4) NOT NULL DEFAULT 1,
  `UserId` INT(11) NOT NULL,
  `VoucherId` INT(11) NULL DEFAULT NULL,
  `AddressId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_Order_Voucher1_idx` (`VoucherId` ASC),
  INDEX `fk_Order_Address1_idx` (`AddressId` ASC),
  INDEX `fk_Order_User1_idx` (`UserId` ASC),
  CONSTRAINT `fk_Order_Voucher1`
    FOREIGN KEY (`VoucherId`)
    REFERENCES `Voucher` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Order_Address1`
    FOREIGN KEY (`AddressId`)
    REFERENCES `Address` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Order_User1`
    FOREIGN KEY (`UserId`)
    REFERENCES `User` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `Voucher` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  `Code` CHAR(32) NOT NULL,
  `Discount` FLOAT(11) NOT NULL,
  `StartDate` DATETIME NOT NULL,
  `EndDate` DATETIME NOT NULL,
  `Active` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `Address` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Detail` VARCHAR(100) NOT NULL,
  `Ward_Id` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_Address_Ward1_idx` (`Ward_Id` ASC),
  CONSTRAINT `fk_Address_Ward1`
    FOREIGN KEY (`Ward_Id`)
    REFERENCES `Ward` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `Ward` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  `District_Id` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_Ward_District1_idx` (`District_Id` ASC),
  CONSTRAINT `fk_Ward_District1`
    FOREIGN KEY (`District_Id`)
    REFERENCES `District` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `District` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  `City_Id` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_District_City1_idx` (`City_Id` ASC),
  CONSTRAINT `fk_District_City1`
    FOREIGN KEY (`City_Id`)
    REFERENCES `City` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `City` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `OrderDetail` (
  `OrderId` INT(11) NOT NULL,
  `ProductId` INT(11) NOT NULL,
  `ProductPrice` FLOAT(11) NOT NULL,
  `Quantity` INT(11) NOT NULL,
  `Discount` FLOAT(11) NOT NULL,
  `Active` TINYINT(4) NOT NULL DEFAULT 1,
  INDEX `fk_OrderDetail_Order1_idx` (`OrderId` ASC),
  INDEX `fk_OrderDetail_Product1_idx` (`ProductId` ASC),
  CONSTRAINT `fk_OrderDetail_Order1`
    FOREIGN KEY (`OrderId`)
    REFERENCES `Order` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_OrderDetail_Product1`
    FOREIGN KEY (`ProductId`)
    REFERENCES `Product` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `Product` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Barcode` CHAR(20) NOT NULL,
  `Name` VARCHAR(100) NOT NULL,
  `SellPrice` FLOAT(11) NOT NULL,
  `Description` TEXT NOT NULL,
  `Total` INT(11) NOT NULL,
  `Sold` INT(11) NOT NULL,
  `Active` TINYINT(4) NOT NULL,
  `CategoryId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_Product_Category1_idx` (`CategoryId` ASC),
  CONSTRAINT `fk_Product_Category1`
    FOREIGN KEY (`CategoryId`)
    REFERENCES `Category` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `Offer` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `ProductId` INT(11) NULL DEFAULT NULL,
  `Price` FLOAT(11) NOT NULL,
  `Description` NVARCHAR(100) NOT NULL,
  `StartDate` DATETIME NOT NULL,
  `EndDate` DATETIME NOT NULL,
  `Active` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Id`),
  INDEX `fk_Offer_Product1_idx` (`ProductId` ASC),
  CONSTRAINT `fk_Offer_Product1`
    FOREIGN KEY (`ProductId`)
    REFERENCES `Product` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `ShoppingCart` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Session` VARCHAR(30) NOT NULL,
  `Date` DATETIME NOT NULL,
  `Price` FLOAT(11) NOT NULL,
  `Quantity` INT(11) NOT NULL,
  `Active` TINYINT(4) NOT NULL DEFAULT 0,
  `ProductId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_ShoppingCart_Product1_idx` (`ProductId` ASC),
  CONSTRAINT `fk_ShoppingCart_Product1`
    FOREIGN KEY (`ProductId`)
    REFERENCES `Product` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `Rating` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Rating` FLOAT(2,1) NOT NULL DEFAULT 0,
  `Description` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE INDEX `Rating_UNIQUE` (`Rating` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `Category` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `Image` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  `Path` VARCHAR(100) NOT NULL,
  `ProductId` INT(11) NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_Image_Product1_idx` (`ProductId` ASC),
  CONSTRAINT `fk_Image_Product1`
    FOREIGN KEY (`ProductId`)
    REFERENCES `Product` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `SlideShow` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Path` VARCHAR(100) NOT NULL,
  `Title` VARCHAR(100) NOT NULL,
  `Description` VARCHAR(150) NOT NULL,
  `Active` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `FAQ` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Question` VARCHAR(150) NOT NULL,
  `Answer` VARCHAR(200) NOT NULL,
  `Active` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `WishList` (
  `ProductId` INT(11) NOT NULL,
  `UserAccount_Id` INT(11) NOT NULL,
  INDEX `fk_WishList_Product1_idx` (`ProductId` ASC),
  INDEX `fk_WishList_UserAccount1_idx` (`UserAccount_Id` ASC),
  CONSTRAINT `fk_WishList_Product1`
    FOREIGN KEY (`ProductId`)
    REFERENCES `Product` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_WishList_UserAccount1`
    FOREIGN KEY (`UserAccount_Id`)
    REFERENCES `UserAccount` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `Staff` (
  `Id` INT(11) NOT NULL,
  `FullName` VARCHAR(100) NOT NULL,
  `Password` VARCHAR(45) NOT NULL,
  `DOB` DATE NOT NULL,
  `Gender` VARCHAR(45) NOT NULL,
  `PhoneNumber` VARCHAR(45) NOT NULL,
  `Email` VARCHAR(45) NULL DEFAULT NULL,
  `Image` VARCHAR(45) NOT NULL,
  `StartDate` DATE NOT NULL,
  `AddressId` INT(11) NOT NULL,
  `Status` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_Staff_Address1_idx` (`AddressId` ASC),
  CONSTRAINT `fk_Staff_Address1`
    FOREIGN KEY (`AddressId`)
    REFERENCES `Address` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `ProductRating` (
  `RatingId` INT(11) NOT NULL,
  `ProductId` INT(11) NOT NULL,
  PRIMARY KEY (`RatingId`, `ProductId`),
  INDEX `fk_Rating_has_Product_Product1_idx` (`ProductId` ASC),
  INDEX `fk_Rating_has_Product_Rating1_idx` (`RatingId` ASC),
  CONSTRAINT `fk_Rating_has_Product_Rating1`
    FOREIGN KEY (`RatingId`)
    REFERENCES `Rating` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Rating_has_Product_Product1`
    FOREIGN KEY (`ProductId`)
    REFERENCES `Product` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema WeTransferLike
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema WeTransferLike
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `WeTransferLike` DEFAULT CHARACTER SET utf8 ;
USE `WeTransferLike` ;

-- -----------------------------------------------------
-- Table `WeTransferLike`.`transfer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WeTransferLike`.`transfer` (
`tra_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
`tra_emailExpediteur` VARCHAR(255) NOT NULL,
`tra_emailDestinataire` VARCHAR(255) NOT NULL,
`tra_emailCopie` VARCHAR(255) NULL,
`tra_url` VARCHAR(255) NOT NULL,
PRIMARY KEY (`tra_id`),
UNIQUE INDEX `tra_id_UNIQUE` (`tra_id` ASC),
UNIQUE INDEX `tra_url_UNIQUE` (`tra_url` ASC))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
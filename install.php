<?php
/* This file does the initial database installation for the Havyaka Culture project */

/* $install = mysql_query('stuff below') or die(mysql_error());
if($install)
{
	echo "Database table created successfully";
} */

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `mydb` ;
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Location`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Location` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Location` (
  `e_loc_id` BIGINT NOT NULL AUTO_INCREMENT ,
  `city` VARCHAR(100) NOT NULL DEFAULT '' ,
  `zipcode` VARCHAR(45) NOT NULL DEFAULT '' ,
  `state` VARCHAR(45) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`e_loc_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`User` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`User` (
  `user_id` BIGINT NOT NULL AUTO_INCREMENT ,
  `md5_id` VARCHAR(200) NOT NULL ,
  `first_name` LONGBLOB NULL ,
  `last_name` LONGBLOB NULL ,
  `profile_picture` VARCHAR(256) NOT NULL ,
  `e_loc_id` BIGINT NULL ,
  `email` LONGBLOB NOT NULL ,
  `phone` VARCHAR(220) NULL ,
  `username` VARCHAR(45) NOT NULL ,
  `user_password` VARCHAR(45) NOT NULL ,
  `user_level` TINYINT NOT NULL DEFAULT '1' ,
  `registration_date` DATE NOT NULL ,
  `user_ip` VARCHAR(200) NOT NULL ,
  `approved` TINYINT NOT NULL DEFAULT '0' ,
  `activation_code` INT(10) NOT NULL DEFAULT '0' ,
  `ckey` VARCHAR(250) NOT NULL ,
  `ctime` VARCHAR(250) NOT NULL ,
  `num_logins` INT(11) NOT NULL DEFAULT '0' ,
  `last_login` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`user_id`) ,
  CONSTRAINT `e_loc_id`
    FOREIGN KEY (`e_loc_id` )
    REFERENCES `mydb`.`Location` (`e_loc_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `username_UNIQUE` ON `mydb`.`User` (`username` ASC) ;

CREATE INDEX `e_loc_id_idx` ON `mydb`.`User` (`e_loc_id` ASC) ;


-- -----------------------------------------------------
-- Table `mydb`.`Venue`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Venue` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Venue` (
  `venue_id` BIGINT NOT NULL AUTO_INCREMENT ,
  `venue_name` VARCHAR(45) NOT NULL DEFAULT '' ,
  `venue_address` VARCHAR(200) NOT NULL DEFAULT '' ,
  `venue_phone` VARCHAR(45) NOT NULL DEFAULT '' ,
  `venue_email` LONGBLOB NULL ,
  `venue_owner` VARCHAR(45) NULL ,
  `fk_venue_location` BIGINT NOT NULL ,
  PRIMARY KEY (`venue_id`) ,
  CONSTRAINT `fk_venue_location`
    FOREIGN KEY (`fk_venue_location` )
    REFERENCES `mydb`.`Location` (`e_loc_id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
COMMENT = '				';

CREATE INDEX `fk_venue_location_idx` ON `mydb`.`Venue` (`fk_venue_location` ASC) ;


-- -----------------------------------------------------
-- Table `mydb`.`event_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`event_type` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`event_type` (
  `e_type_id` BIGINT NOT NULL ,
  `event_type` VARCHAR(256) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`e_type_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Event` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Event` (
  `event_id` BIGINT NOT NULL AUTO_INCREMENT ,
  `event_name` VARCHAR(200) NOT NULL DEFAULT '' ,
  `event_date` DATE NOT NULL ,
  `event_desc` VARCHAR(256) NOT NULL DEFAULT '' ,
  `e_type_id` BIGINT NULL ,
  `user_id` BIGINT NULL ,
  `venue_id` BIGINT NULL ,
  `event_status` TINYINT NOT NULL DEFAULT '0' ,
  `event_scope` VARCHAR(200) NOT NULL DEFAULT 'public' ,
  PRIMARY KEY (`event_id`) ,
  CONSTRAINT `fk_event_user`
    FOREIGN KEY (`user_id` )
    REFERENCES `mydb`.`User` (`user_id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_event_venue`
    FOREIGN KEY (`venue_id` )
    REFERENCES `mydb`.`Venue` (`venue_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_type`
    FOREIGN KEY (`e_type_id` )
    REFERENCES `mydb`.`event_type` (`e_type_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `user_id_idx` ON `mydb`.`Event` (`user_id` ASC) ;

CREATE INDEX `venue_id_idx` ON `mydb`.`Event` (`venue_id` ASC) ;

CREATE INDEX `e_type_id_idx` ON `mydb`.`Event` (`e_type_id` ASC) ;


-- -----------------------------------------------------
-- Table `mydb`.`event_recurrence`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`event_recurrence` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`event_recurrence` (
  `e_recurring_id` BIGINT NOT NULL AUTO_INCREMENT ,
  `event_id` BIGINT NULL COMMENT 'We might need to normalize this table further. We could provide several event recurrence options that the user has to pick from this table.' ,
  `event_frequency` VARCHAR(45) NULL ,
  `recurrence_end` VARCHAR(45) NULL ,
  `day_of_week` VARCHAR(45) NULL ,
  PRIMARY KEY (`e_recurring_id`) ,
  CONSTRAINT `fk_event_recurrence_event`
    FOREIGN KEY (`event_id` )
    REFERENCES `mydb`.`Event` (`event_id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `event_id_idx` ON `mydb`.`event_recurrence` (`event_id` ASC) ;


-- -----------------------------------------------------
-- Table `mydb`.`chef`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`chef` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`chef` (
  `chef_id` BIGINT NOT NULL ,
  `about_chef` VARCHAR(45) NULL ,
  `contact_time_preference` VARCHAR(45) NULL ,
  `payments_accepted` VARCHAR(45) NULL ,
  `delivery_available` VARCHAR(45) NULL ,
  `pickup_available` VARCHAR(45) NULL ,
  `taking_offline_order` VARCHAR(45) NULL ,
  PRIMARY KEY (`chef_id`) ,
  CONSTRAINT `fk_chef_user`
    FOREIGN KEY (`chef_id` )
    REFERENCES `mydb`.`User` (`user_id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`food`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`food` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`food` (
  `food_id` BIGINT NOT NULL AUTO_INCREMENT ,
  `food_name` VARCHAR(45) NOT NULL DEFAULT '' ,
  `food_description` VARCHAR(256) NOT NULL DEFAULT '' ,
  `availability` VARCHAR(45) NULL ,
  `food_picture` VARCHAR(256) NULL ,
  PRIMARY KEY (`food_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`food_chef_details`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`food_chef_details` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`food_chef_details` (
  `F_C_det_id` INT NOT NULL AUTO_INCREMENT ,
  `chef_id` BIGINT NULL ,
  `food_id` BIGINT NULL ,
  `food_price` VARCHAR(45) NULL ,
  PRIMARY KEY (`F_C_det_id`) ,
  CONSTRAINT `fk_food_chef_details_chef`
    FOREIGN KEY (`chef_id` )
    REFERENCES `mydb`.`chef` (`chef_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_food_chef_details_food`
    FOREIGN KEY (`food_id` )
    REFERENCES `mydb`.`food` (`food_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `chef_id_idx` ON `mydb`.`food_chef_details` (`chef_id` ASC) ;

CREATE INDEX `food_id_idx` ON `mydb`.`food_chef_details` (`food_id` ASC) ;


-- -----------------------------------------------------
-- Table `mydb`.`event_picture`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`event_picture` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`event_picture` (
  `e_pic_id` BIGINT NOT NULL AUTO_INCREMENT ,
  `image_location` VARCHAR(256) NOT NULL DEFAULT '' ,
  `event_id` BIGINT NOT NULL ,
  PRIMARY KEY (`e_pic_id`) ,
  CONSTRAINT `fk_event_picture_event`
    FOREIGN KEY (`event_id` )
    REFERENCES `mydb`.`Event` (`event_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `event_id_idx` ON `mydb`.`event_picture` (`event_id` ASC) ;


-- -----------------------------------------------------
-- Table `mydb`.`user_saved_info`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`user_saved_info` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`user_saved_info` (
  `saved_info` INT NOT NULL ,
  `user_id` BIGINT NOT NULL ,
  `event_id` BIGINT NULL ,
  `chef_id` BIGINT NULL ,
  `contact_id` BIGINT NULL ,
  PRIMARY KEY (`saved_info`) ,
  CONSTRAINT `fk_user_saved_info_user`
    FOREIGN KEY (`user_id` )
    REFERENCES `mydb`.`User` (`user_id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_saved_info_event`
    FOREIGN KEY (`event_id` )
    REFERENCES `mydb`.`Event` (`event_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_saved_info_chef`
    FOREIGN KEY (`chef_id` )
    REFERENCES `mydb`.`chef` (`chef_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_saved_info_contact`
    FOREIGN KEY (`contact_id` )
    REFERENCES `mydb`.`User` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `user_id_idx` ON `mydb`.`user_saved_info` (`user_id` ASC) ;

CREATE INDEX `event_id_idx` ON `mydb`.`user_saved_info` (`event_id` ASC) ;

CREATE INDEX `chef_id_idx` ON `mydb`.`user_saved_info` (`chef_id` ASC) ;

CREATE INDEX `contact_id_idx` ON `mydb`.`user_saved_info` (`contact_id` ASC) ;


-- -----------------------------------------------------
-- Table `mydb`.`Community_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Community_type` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Community_type` (
  `community_id` BIGINT NOT NULL AUTO_INCREMENT ,
  `community_name` VARCHAR(200) NOT NULL DEFAULT '' ,
  `Community_desc` VARCHAR(256) NULL ,
  `user_id` BIGINT NULL ,
  `event_id` BIGINT NULL ,
  `chef_id` BIGINT NULL ,
  `food_id` BIGINT NULL ,
  PRIMARY KEY (`community_id`) ,
  CONSTRAINT `fk_community_type_user`
    FOREIGN KEY (`user_id` )
    REFERENCES `mydb`.`User` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_community_type_food`
    FOREIGN KEY (`food_id` )
    REFERENCES `mydb`.`food` (`food_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_community_type_chef`
    FOREIGN KEY (`chef_id` )
    REFERENCES `mydb`.`chef` (`chef_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_community_type_event`
    FOREIGN KEY (`event_id` )
    REFERENCES `mydb`.`Event` (`event_id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `user_id_idx` ON `mydb`.`Community_type` (`user_id` ASC) ;

CREATE INDEX `food_id_idx` ON `mydb`.`Community_type` (`food_id` ASC) ;

CREATE INDEX `chef_id_idx` ON `mydb`.`Community_type` (`chef_id` ASC) ;

CREATE INDEX `event_id_idx` ON `mydb`.`Community_type` (`event_id` ASC) ;

USE `mydb` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


?>
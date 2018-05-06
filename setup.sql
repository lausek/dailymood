SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema dailymood
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `dailymood` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `dailymood` ;

-- -----------------------------------------------------
-- Table `dailymood`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dailymood`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `password` VARCHAR(40) NULL,
  `salt` VARCHAR(8) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `dailymood`.`moods`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dailymood`.`moods` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `icon` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `dailymood`.`days`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dailymood`.`days` (
  `day` DATE NOT NULL,
  `user` INT UNSIGNED NOT NULL,
  `mood` INT UNSIGNED NOT NULL,
  `note` VARCHAR(255) NULL,
  PRIMARY KEY (`day`, `user`),
  INDEX `fk_days_users_idx` (`user` ASC),
  INDEX `fk_days_moods1_idx` (`mood` ASC),
  CONSTRAINT `fk_days_users`
    FOREIGN KEY (`user`)
    REFERENCES `dailymood`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_days_moods1`
    FOREIGN KEY (`mood`)
    REFERENCES `dailymood`.`moods` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `dailymood`.`moods`
-- -----------------------------------------------------
START TRANSACTION;
USE `dailymood`;
INSERT INTO `dailymood`.`moods` (`id`, `name`, `icon`) VALUES (1, 'sad', '128546');
INSERT INTO `dailymood`.`moods` (`id`, `name`, `icon`) VALUES (2, 'angry', '128544');
INSERT INTO `dailymood`.`moods` (`id`, `name`, `icon`) VALUES (3, 'neutral', '128528');
INSERT INTO `dailymood`.`moods` (`id`, `name`, `icon`) VALUES (4, 'happy', '128522');
INSERT INTO `dailymood`.`moods` (`id`, `name`, `icon`) VALUES (5, 'stressed', '128533');

COMMIT;
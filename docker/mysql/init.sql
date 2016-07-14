-- -----------------------------------------------------
-- Schema
-- -----------------------------------------------------

CREATE SCHEMA IF NOT EXISTS `global` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
CREATE SCHEMA IF NOT EXISTS `shard1` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
CREATE SCHEMA IF NOT EXISTS `shard2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

-- -----------------------------------------------------
-- Table
-- -----------------------------------------------------

-- global

DROP TABLE IF EXISTS `global`.`items` ;
CREATE TABLE IF NOT EXISTS `global`.`items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

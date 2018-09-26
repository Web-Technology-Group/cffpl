/* CFFPL Database Creation Script */
CREATE DATABASE cffpl;

USE cffpl;

/* Create weeklyhistory table */
CREATE TABLE `cffpl`.`weeklyhistory` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `week` INT NOT NULL,
  `weekstarttime` DATETIME NULL,
  `weekendtime` DATETIME NULL,
  PRIMARY KEY (`id`));

/* Create users table */
CREATE TABLE `cffpl`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `firstname` VARCHAR(50) NOT NULL,
  `middlename` VARCHAR(50) NULL,
  `surname` VARCHAR(50) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `createddate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC));
  
/* Create premierteams table */
CREATE TABLE `cffpl`.`premierteams` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`, `name`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC));

/* Create premierplayers table */
CREATE TABLE `cffpl`.`premierplayers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `team` VARCHAR(50) NOT NULL,
  `points` INT NOT NULL DEFAULT 0,
  `cost` DECIMAL(5, 2) NOT NULL,
  `position` VARCHAR(1) NOT NULL,
  `week` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  FOREIGN KEY (week) REFERENCES weeklyhistory(id));
 
 /* Create usersquads table */
 CREATE TABLE `cffpl`.`usersquads` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `userid` INT NULL,
  `playerid` INT NULL,
  `inteam` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT us_udi UNIQUE (userid, playerid),
  FOREIGN KEY (userid) REFERENCES users(id),
  FOREIGN KEY (playerid) REFERENCES premierplayers(id));

  
  
 
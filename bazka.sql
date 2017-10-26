create database Forum;

CREATE TABLE `forum`.`categories` (
	`cat_id` INT(10) NOT NULL AUTO_INCREMENT , 
	`cat_name` VARCHAR(40) NOT NULL , PRIMARY KEY (`cat_id`)
);

CREATE TABLE `forum`.`threads` ( 
	`thread_id` INT(10) NOT NULL AUTO_INCREMENT,
	`cat_id` INT(10) NOT NULL , 
	`thread_name` CHAR(255) NOT NULL , 
	`author` CHAR(40) NOT NULL , PRIMARY KEY (thread_id)
);

CREATE TABLE `forum`.`talks` (
	`talk_id` INT(10) NOT NULL AUTO_INCREMENT , 
	`thread_id` INT(10) NOT NULL , 
	`talk` TEXT NOT NULL , 
	`author` VARCHAR(20) NOT NULL , 
	`date` VARCHAR(10) NOT NULL , 
	`time` VARCHAR(5) NOT NULL , PRIMARY KEY (`talk_id`)
);
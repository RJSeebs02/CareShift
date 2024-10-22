DROP DATABASE IF EXISTS `db_careshift`;
CREATE DATABASE `db_careshift`;
USE `db_careshift`;

DROP TABLE IF EXISTS `nurse`;
CREATE TABLE `nurse` (
  `nurse_id` int(50) NOT NULL auto_increment, 
  `nurse_password` varchar(32) NOT NULL,
  `nurse_fname` varchar(50) NOT NULL,
  `nurse_mname` varchar(50) NOT NULL,
  `nurse_lname` varchar(50) NOT NULL,
  `nurse_email` varchar(50) NOT NULL,
  `nurse_contact` bigint(20) NOT NULL,
  `nurse_position` varchar(255) NOT NULL,
  `nurse_department` varchar(255) NOT NULL,
  PRIMARY KEY  (`nurse_id`)
);

INSERT INTO nurse(nurse_password,nurse_fname,nurse_mname,nurse_lname,nurse_email,nurse_contact,nurse_position,nurse_department) 
VALUES ("123", "Romeo", "Valderama", "Seva", "romrom@gmail.com", 123, "Nurse I", "OR"),
    ("123", "Russ Allen", "Solinap", "Garde", "gards@gmail.com", 123, "Nurse II", "OR"),
    ("123", "Elijah Zachary", "Geronimo", "Faeldonea", "lij@gmail.com", 123, "Nurse III", "OR");

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `adm_id` int(50) NOT NULL auto_increment,
  `adm_username` varchar(32) NOT NULL,
  `adm_password` varchar(32) NOT NULL,
  `adm_fname` varchar(50) NOT NULL,
  `adm_mname` varchar(50) NOT NULL,
  `adm_lname` varchar(50) NOT NULL,
  `adm_email` varchar(50) NOT NULL,
  `adm_contact` bigint(20) NOT NULL,
  `adm_access` varchar(255) NOT NULL,
  PRIMARY KEY  (`adm_id`)
);

INSERT INTO admin(adm_username,adm_password,adm_fname,adm_mname,adm_lname,adm_email,adm_contact,adm_access) 
VALUES ("admin","123", "Admin", "Admin", "Admin", "admin@gmail.com", 123, "Manager");

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `log_id` int(50) NOT NULL auto_increment,
  `log_action` varchar(50) NOT NULL,
  `log_description` varchar(50) NOT NULL,
  `log_time_managed` time NOT NULL,
  `log_date_managed` date NOT NULL,
  `adm_id` int(50) NOT NULL,
  `nurse_id` int(50) NOT NULL,
  PRIMARY KEY  (`log_id`),
  KEY (`adm_id`),
  KEY (`nurse_id`)
);

INSERT INTO logs(adm_id,nurse_id,log_action,log_description,log_time_managed,log_date_managed) 
VALUES ("1","3", "Removed", "Lij removed Nurse 24 - Anton Magbanua", '12:34:56', '2024-10-06');

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule` (
  `sched_id` int(50) NOT NULL auto_increment,
  `nurse_id` int(50) NOT NULL,
  `sched_date` date NOT NULL,
  `sched_start_time` time NOT NULL,
  `sched_end_time` time NOT NULL,
  PRIMARY KEY  (`sched_id`),
  KEY (`nurse_id`)
);

DROP TABLE IF EXISTS `leave`;
CREATE TABLE `leave` (
  `leave_id` int(50) NOT NULL auto_increment,
  `leave_type` varchar(50) NOT NULL,
  `leave_start_date` date NOT NULL,
  `leave_end_date` date NOT NULL,
  `leave_desc` varchar(50) NOT NULL,
  `leave_status` varchar(50) NOT NULL,
  `nurse_id` int(50) NOT NULL,
  `adm_id` int(50) NOT NULL,
  PRIMARY KEY  (`leave_id`),
  KEY (`nurse_id`),
  KEY (`adm_id`)
);
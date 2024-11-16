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
  `nurse_sex` varchar(50) NOT NULL,
  `nurse_contact` bigint(20) NOT NULL,
  `nurse_position` varchar(50) NOT NULL,
  `department_id` int(50) NOT NULL,
  PRIMARY KEY  (`nurse_id`)
);

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
  `useraccess_id` int(50) NOT NULL,
  `department_id` int(50) NOT NULL,
  PRIMARY KEY  (`adm_id`),
  KEY (`useraccess_id`),
  KEY (`department_id`)
);

INSERT INTO admin(adm_username,adm_password,adm_fname,adm_mname,adm_lname,adm_email,adm_contact,useraccess_id,department_id) 
VALUES ("admin","123", "Super", "Admin", "Admin", "admin@gmail.com", 123, 1, 1);

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `log_id` int(50) NOT NULL auto_increment,
  `log_action` varchar(50) NOT NULL,
  `log_description` varchar(50) NOT NULL,
  `log_time_managed` time NOT NULL,
  `log_date_managed` date NOT NULL,
  `adm_id` int(50) NOT NULL,
  PRIMARY KEY  (`log_id`),
  KEY (`adm_id`)
);

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
  `leave_date_filed` date NOT NULL,
  `leave_time_filed` time NOT NULL,
  `leave_desc` varchar(255) NOT NULL,
  `leave_status` varchar(50) NOT NULL,
  `nurse_id` int(50) NOT NULL,
  PRIMARY KEY  (`leave_id`),
  KEY (`nurse_id`)
);


INSERT INTO `leave`(leave_type,leave_start_date,leave_end_date,leave_date_filed,leave_time_filed,leave_desc,leave_status,nurse_id) 
VALUES  ("Sick Leave", "2024-10-10", "2024-10-20", "2024-10-24", "13:00", "Diagnosed with amoeba", "Pending", "2"),
        ("Vacation Leave", "2024-9-2", "2024-9-15", "2024-9-30", "13:20", "Vacation Break", "Approved", "1"),
        ("Emergency Leave", "2024-9-2", "2024-9-2", "2024-9-2", "5:00", "Late", "Denied", "3");

DROP TABLE IF EXISTS `dept_type`;
CREATE TABLE `dept_type` (
  `dept_type_id` int(50) NOT NULL auto_increment,
  `dept_type_name` varchar(50) NOT NULL,
  `dept_type_desc` varchar(255) NOT NULL,
  PRIMARY KEY  (`dept_type_id`)
);

INSERT INTO `dept_type`(dept_type_name,dept_type_desc) 
VALUES  ("Specialized Care Nursing and Special Care Areas", "Provide targeted, intensive care in specific settings like ICUs or emergency rooms for patients with critical or complex health needs."),
        ("Clinical Care Nursing", "Direct patient care across various healthcare settings, focusing on routine and acute care, health assessments, and managing patient treatments");

DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `department_id` int(50) NOT NULL auto_increment,
  `department_name` varchar(50) NOT NULL,
  `department_desc` varchar(255) NOT NULL,
  `dept_type_id` int(50) NOT NULL,
  PRIMARY KEY  (`department_id`),
  KEY (`dept_type_id`)
);

INSERT INTO `department`(department_name,department_desc,dept_type_id) 
VALUES  ("Operating Room Complex", "Lorem Ipsum", 1),
        ("Emergency Room Complex", "Lorem Ipsum", 1),
        ("Outpatient Department", "Lorem Ipsum", 1),
        ("Pediatric ICU", "Lorem Ipsum", 1),
        ("Adult ICU", "Lorem Ipsum", 1),
        ("Coronary Care Unit", "Lorem Ipsum", 1),
        ("Mental Health Unit", "Lorem Ipsum", 1),
        ("Pediatric Ward", "Lorem Ipsum", 2),
        ("Obstetric Complex", "Lorem Ipsum", 2),
        ("Medical Ward", "Lorem Ipsum", 2),
        ("Surgical Ward", "Lorem Ipsum", 2),
        ("Orthopedic Ward", "Lorem Ipsum", 2);

DROP TABLE IF EXISTS `room`;
CREATE TABLE `room` (
  `room_id` int(50) NOT NULL auto_increment,
  `room_name` varchar(255) NOT NULL,
  `room_slots` int(10) NOT NULL,
  `status_id` int(50) NOT NULL,
  `department_id` int(50) NOT NULL,
  PRIMARY KEY  (`room_id`),
  KEY (`status_id`),
  KEY (`department_id`)
);

INSERT INTO `room`(room_name,room_slots,status_id,department_id) 
VALUES  ("Operating Room 1", "15", 1, 1),
        ("Operating Room 2", "15", 1, 1),
        ("Emergency Room 1", "15", 1, 2),
        ("Emergency Room 2", "15", 1, 2),
        ("OPD Consultation Room 1", "2", 1, 3),
        ("OPD Consultation Room 2", "2", 1, 3),
        ("OPD Consultation Room 3", "2", 1, 3),
        ("OPD Consultation Room 4", "2", 1, 3),
        ("OPD Consultation Room 5", "2", 1, 3),
        ("Pediatric Intensive Care Unit Area", "15", 1, 4),
        ("Adult Intensive Care Unit Area", "15", 1, 5),
        ("Coronary Care Unit Area", "15", 1, 6),
        ("Mental Health Unit Area", "15", 1, 7),
        ("Pediatric Ward Area", "15", 1, 8),
        ("Obstetric Complex Room 1", "2", 1, 9),
        ("Obstetric Complex Room 2", "2", 1, 9),
        ("Obstetric Complex Room 3", "2", 1, 9),
        ("Obstetric Complex Room 4", "2", 1, 9),
        ("Obstetric Complex Room 5", "2", 1, 9),
        ("Medical Ward Area", "15", 1, 10),
        ("Surgical Ward Area", "15", 1, 11),
        ("Orthopedic Ward Area", "15", 1, 12);

DROP TABLE IF EXISTS `useraccess`;
CREATE TABLE `useraccess` (
  `useraccess_id` int(50) NOT NULL auto_increment,
  `useraccess_name` varchar(50) NOT NULL,
  `useraccess_desc` varchar(255) NOT NULL,
  PRIMARY KEY  (`useraccess_id`)
);

INSERT INTO `useraccess`(useraccess_name,useraccess_desc) 
VALUES  ("Super Admin", "Access to all the functions of the system."),
        ("Head Nurse", "Manage all nurses and leave applications."),
        ("Scheduler", "Manage the schedules of the nurses.");

DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `status_id` int(50) NOT NULL auto_increment,
  `status_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`status_id`)
);

INSERT INTO `status`(status_name) 
VALUES  ("Active"),
        ("Inactive");
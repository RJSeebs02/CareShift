DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `emp_id` int(50) NOT NULL auto_increment, 
  `emp_password` varchar(32) NOT NULL,
  `emp_fname` varchar(50) NOT NULL,
  `emp_mname` varchar(50) NOT NULL,
  `emp_lname` varchar(50) NOT NULL,
  `emp_email` varchar(50) NOT NULL,
  `emp_contact` bigint(20) NOT NULL,
  `emp_department` varchar(255) NOT NULL,
  PRIMARY KEY  (`emp_id`)
);

INSERT INTO employee(emp_password,emp_fname,emp_mname,emp_lname,emp_email,emp_contact,emp_department) 
VALUES ("123", "Romeo", "Valderama", "Seva", "romrom@gmail.com", 123, "OR"),
    ("123", "Russ Allen", "Solinap", "Garde", "gards@gmail.com", 123, "OR");

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
  `adm_department` varchar(255) NOT NULL,
  PRIMARY KEY  (`adm_id`)
);

INSERT INTO admin(adm_username,adm_password,adm_fname,adm_mname,adm_lname,adm_email,adm_contact,adm_department) 
VALUES ("admin","123", "Admin", "Admin", "Admin", "admin@gmail.com", 123, "HR");

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `log_id` int(50) NOT NULL auto_increment,
  `log_actor` varchar(50) NOT NULL,
  `log_subject` varchar(50) NOT NULL,
  `log_action` varchar(50) NOT NULL,
  `log_description` varchar(50) NOT NULL,
  `log_time_managed` time NOT NULL,
  `log_date_managed` date NOT NULL,
  PRIMARY KEY  (`log_id`)
);

INSERT INTO logs(log_actor,log_subject,log_action,log_description,log_time_managed,log_date_managed) 
VALUES ("Lij Faeldonea","Anton Magbanua", "Removed", "Lij removed Nurse 24 - Anton Magbanua", '12:34:56', '2024-10-06');

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule` (
  `sched_id` int(50) NOT NULL auto_increment,
  `emp_id` int(50) NOT NULL,
  `sched_date` date NOT NULL,
  `sched_start_time` time NOT NULL,
  `sched_end_time` time NOT NULL,
  PRIMARY KEY  (`sched_id`),
  KEY (`emp_id`)
);

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `att_id` int(50) NOT NULL auto_increment,
  `emp_id` int(50) NOT NULL,
  `att_type` varchar(50) NOT NULL,
  `att_date` date NOT NULL,
  `att_time` time NOT NULL,
  PRIMARY KEY  (`att_id`),
  KEY (`emp_id`)
);
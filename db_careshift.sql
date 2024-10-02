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
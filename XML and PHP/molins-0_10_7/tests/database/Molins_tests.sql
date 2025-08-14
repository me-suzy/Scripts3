
DROP DATABASE IF EXISTS Molins_tests;

CREATE DATABASE Molins_tests;

GRANT ALL PRIVILEGES ON Molins_tests.* TO 'molins'@'%' IDENTIFIED BY 'tests' WITH GRANT OPTION;

USE Molins_tests;

CREATE TABLE table_one (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,

		PRIMARY KEY (id)
);

INSERT INTO table_one VALUES (1, 'slizardo');
INSERT INTO table_one VALUES (2, 'gnu/linux');
INSERT INTO table_one VALUES (3, 'slackware');
INSERT INTO table_one VALUES (4, 'tomcat');
INSERT INTO table_one VALUES (5, 'j2ee');
INSERT INTO table_one VALUES (6, 'struts');
INSERT INTO table_one VALUES (7, 'php5');


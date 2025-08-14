DROP DATABASE IF EXISTS Molins_sample;
CREATE DATABASE Molins_sample;

USE Molins_sample;

CREATE TABLE category (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(40) NOT NULL,
	description VARCHAR(244) NOT NULL,

		PRIMARY KEY (id),
		UNIQUE (name)
);

INSERT INTO category VALUES (1, 'General', 'General topic');
INSERT INTO category VALUES (2, 'Testing', 'Just testing');

CREATE TABLE news (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	category_id INT UNSIGNED NOT NULL REFERENCES category,
	title VARCHAR(100) NOT NULL,
	creation_date DATETIME NOT NULL,
	author VARCHAR(50) NOT NULL,
	details TEXT NOT NULL,

		PRIMARY KEY (id),
		UNIQUE (title)
);

INSERT INTO news VALUES (NULL, 1, 'First news !', NOW(), 'SLizardo', 'First news in the sample application using Molins. Enjoy it !');


+-----------------------------+
| AMFR Account Creator Readme |
| Version: 1.0 rc1            |
+-----------------------------+

Description
===========

AMFR Account Creator is a script designed for hosting companies.
It creates cPanel accounts with certain limitation such as number of ftp accounts, email accounts, etc.

Requirements
============

Requires php 4.2.0 or later, mysql, and WHM/cPanel.

Usage
=====

To delete and email users, go to "admin.php" and login with the username and password you specified in "config.php".
To let users signup, point them to "signup.php"

Installation
============

1. Run this query on your database:

CREATE TABLE amfr_users (user TEXT, domain TEXT, email TEXT);
CREATE TABLE amfr_used_keys (amfrkey TEXT, user TEXT);
CREATE TABLE amfr_keys (amfrkey TEXT);

2. Then, edit "config.php" and fill in the correct values.
3. To let users signup, point them to "new.php".

	Upgrading from 0.3.2
	==================
	1. Replace "new.php" and "create.php".

	Upgrading from 0.3.2
	==================
	1. Upload "image.php".
	1. Replace "new.php" and "create.php".
	2. Edit "config.php".  Use the old mysql database to keep users.

	Upgrading from 0.3.1
	==================
	1. Replace "new.php", "create.php", "admin.php", and "config.php".
	2. Edit "config.php".  Use the old mysql database to keep users.

	Upgrading from 0.3
	==================
	1. Replace "new.php", "create.php", and "config.php".
	2. Edit "config.php".  Use the old mysql database to keep users.

	Upgrading from 0.2
	==================

	1. Replace all of the old script including "config.php".
	2. Edit "config.php".  Use the old mysql database to keep users.

Signup Keys
===========

Signup keys are used if you oly want to offer a certain amount of accounts or only let certain people signup.
A signup key can be any combination of numbers, letters, and dashes.
Example signup keys:

A
mI8fOjue-IOowejubna
sogfij
hello
2348537

To use signup keys, enable them in "config.php", then add signup keys in the admin section, then distribute the signup keys to anyone.
A signup key can only be used once and by one person unless you enable "Multiple use signup keys" in "config.php".

Add More Domains
================

Adding more domains lets users choose to have a subdomain of a different domain.
To add more domains, go to "config.php" and add this below the first domain entry:

$domains[] = "ANOTHERDOMAIN";

All domains must be hosted on the reseller cPanel account.

Changelog
=========

v. 1.0 rc1
-Added more error checking
-Minor design changes

v. 0.3.3 beta
-Added optional image verification
-Added more error checking

v. 0.3.2 beta
-Added optional "Multiple use signup keys"
-Minor design changes

v. 0.3.1 beta
-Shows nameservers after signup

v. 0.3 beta
-Added multi-domain support
-Let users use their own domains
-Delete domain bug fixed

v. 0.2 beta
-Made signup keys optional

v. 0.1 beta
-Began project

Authors
=======

amfr - amfr@comcast.net

Copyright
=========

Copyright 2005 AMFR Hosting Services - http://www.amfrservices.com
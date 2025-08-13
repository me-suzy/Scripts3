PhpAuction PRO Plus - web based auction system

=====================================
INSTALLATION

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#///////////////////////////////////////////////////////                


*********************************************************************************************
NOTE: There is an installation script named install.php. Instructions for running the installation script can be found in the file name installation.txt.  

The instructions below are for manual installation ONLY!
*********************************************************************************************

Requirements:

Php 4.+ ( http://www.php.net )
MySQL database ( http://www.mysql.com )

PHP and MySQL must be installed prior to installing PHPAuction.  See the
websites or HOWTO files for those packages for help.

NOTE: be sure your web server is configured to recognize .php files as PHP files.


PHPAuction Installation Quick Start:

1. Download PhpAuction archive from the phpauction.org download page

http://www.phpauction.org/html/download.php


2. Extract or unzip the files to the folder on your web server in which you

want to place PHPAuction: 


3. Edit the following configuration files:

   a. Edit config.inc.php  that is in the includes folder and follow the instructions you find there to enter the correct paths to your uploaded and includes folders.

   b. Edit includes\passwd.inc.php to fit your MySQL configuration. 

   c. Edit newsletter.php	replacing webmaster@yourdomain.com with your e-mail address.

   d.  You must chmod 777 folder "counter"  under the main phpauction directory. Must be writeable by PHP

   e.  You must chmod 777 folder "uploaded"  under the main phpauction directory.Must be writeable by PHP


4. For the administrative area to function, certain files in the /includes 
	directory must be writeable by the web server process.  

    Set these files to writeable using chmod 666:
	
    	categories_select_box.inc.php

		countries.inc.php

		currency.inc.php


5.   Edit the file config.inc.php that is in the folder phpAdsNew.  The only changes in this config.inc.php file that are needed are in the Administrator's section of the file.

The default phpAdsNew Admin's username "phpauctionuser" & password "LetMeIn" is at line # 222 under Administrator's  Configuration.  You may change these if you like.
Also there is where you would set email for admin for banner management.


*NOTE* There are two different config.inc.php files you must edit.  One in the includes folder(this is the PHPAuction main config file) and one in the phpAdsNew folder(this is the phpAdsNew config file).  
You will be able to sign into phpAdsNew from PHPAuction's Admin Back-end.


6. Create and populate the MySQL database:

 a. If you have shell access to your web server, login and create the database:

		shell> mysqladmin create yourdatabasename

    	Verify that you can access this  new database:

		shell> mysql -h hostname -u username -p yourdatabasename
 

		Once you are successfully logged into your mySQL database, you must then
		populate the database by copying the /sql/dump.sql file into the database: 
	

		shell> mysql -h hostname -u username -p databasename < dump.sql


		This will run a set of SQL queries on your database and fill the database with data. 
		You should receive a successful response. 
		Your database is now populated and PhpAuction is now ready for use.  

		With shell access, you can check to see if the tables have been
		successfully created, you can list them by using the command 

		mysqlshow:

		mysqlshow databasename


	b. If you use phpMyAdmin to access MySQL:

		Copy the dump.sql file and paste the contents inside the "Run SQL query/queries on 
		database" field box in the database.
		This will display the tables and the variables needed for PhpAuction to run correctly. 
		For more information see the phpMyAdmin documentation.

*** Note - If your database returned an error or several errors, try running a small section of the dump.sql (making sure to take complete table sections at one time) on the database instead of the entire query. 
		This sometimes solves common database error problems. ***


	c. If you do not have shell access, you must get the system administrator
		to perform the above steps.

7. Create a cron job or devise another method to ensure that the page
	/cron.php gets executed periodically.  This page will close auctions,
	notify bidders, etc.  

		In Linux, you can create a file

		/etc/cron.daily/phpauction.cron with these contents:

	
		#!/bin/sh

		lynx -dump /dev/null http://www.mydomain.com/phpauction/cron.php


		*** Note: if you have PHP installed as a module the above one is the only way
		to run cron. If you have (or also have) PHP installed as a CGI you can substitute
		the lynx call above with the following:

	
		PHP /absolute/path/to/cron.php

	
		Of course the path to cron.php will depend on where you installed phpauction.
		Be sure to give cron.php permission to be executable.

		In Windows, you can add a taskmanager job phpauc.job

		C:\php\php.exe c:\myweb\htdocs\phpauction\cron.php

		*** Note: You will need to change the directories above to which ever
		directories you have installed phpauction. 



This concludes the installation of PhpAuction! 


After this is done, go to your browser and type in address to your phpauction installation admin section such as http://pro.phpauction.org/demo/admin/


You will be taken to the log in page, but before you can log in you must first create your admin username & password.  After you have inserted your chosen username & password you will be able to login using the username & password you just created.


Be sure to change the web address and admin's email address in the general settings in Admin back-end.
Also, you will need to upload your company logo in the general settings section.

You may make other web site settings changes in the admin back-end, also.


If you need technical support, use the ticket system taht is located here
http://pro.phpauction.org/support/support.php

If you have any problems please check the FAQs or the online Manual located 
at http://pro.phpauction.org/promanual.  
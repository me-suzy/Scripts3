<?php
/*
                                                   PHP Freelancers version 1.1
                                                   -----------------------
                                                   A script by ProEx101 Web Services
                                                   (http://www.ProEx101.com/)

    "PHP Freelancers" is not a free script. If you got this from someplace
    other than SmartCGIs.com or ProEx101 Web Services, please contact us,
    we do offer rewards for that type of information. Visit our site for up
    to date versions. Most PHP scripts are over $300, sometimes more than
    $700, this script is much less. We can keep this script cheap, as well
    as free scripts on our site, if people don't steal it.
          Also, no return links are required, but we appreciate it if you
          do find a spot for us.
          Thanks!

          Special Notice to Resellers
          ===========================
          Reselling this script without prior permission
          from ProEx101 Web Services is illegal and
          violators will be prosecuted to the fullest
          extent of the law.  To apply to be a legal
          reseller, please visit:
          http://www.ProEx101.com/freelancers/resell.php

       (c) copyright 2001 ProEx101 Web Services, SmartCGIs.com, and R3N3 Internet Services */

/*
The mode of SQL to be used.
If you set this to "1", MySQL will be used
If you set this to "2", PostgreSQL will be used
If you set this to "3", MS SQL will be used
If you set this to "4", ODBC will be used
Please note that support for Informix, Oracle, Interbase, Mini SQL, and Ovrimos SQL is coming in PHP Freelancers 2.0
*/
$sql_mode = '1';

// You only have to configure the below SQL connections, and nothing else!  Just run the admin_setup.php file to edit any field you want that needs to be customized!
$sql_host = 'localhost';            // Your SQL host connection, most of the time, the host connection is "localhost".  Contact your server admin if you don't know what your host connection is.
$sql_user = 'neoroyal';             // Your SQL username, most of the time, the username is the exact same username you have that you login to your FTP server with but may differ.
$sql_pass = '157928';              // Your SQL password, most of the time, the password is the exact same password you have that you login to your FTP server with but may differ.
$sql_data = 'neoroyalty_com';             // Your SQL database, most of the time, the database is the exact same username you have that you login to your FTP server with but may differ.
?>
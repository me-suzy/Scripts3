<?php

// your email address
// where you will receive error messages if there should ever
// be a problem with the mysql database
$emergencymail = 'you@you.com';

// hostname of the server
// localhost should work in most cases
$mysql_host = 'localhost';

// mysql username
$mysql_user = 'login';

// mysql password
$mysql_passwd = 'pass';

// database name
// make sure that the database already exists
$mysql_db = 'dbname';

// table names which will be created to store the data
$ban_tbl = 'blacklist';
$emails_tbl = 'emails';
$moderator_tbl = 'mods';
$options_tbl = 'options';
$pop_tbl = 'accounts';
$site_tbl = 'sites';

// 1 = use persistent mysql connections
// 0 = do not use persistent mysql connections
$pconnect = '1';

// last but not least, if you want to run the script
// on an Microsoft IIS server, set $iis = "1";
// btw we recommend to use Apache
$iis = '0';

?>
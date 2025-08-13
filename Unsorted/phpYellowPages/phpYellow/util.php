<?php
/*
   If it says "DO NOT CHANGE" then don't - or your implementation may not work
	CONSTANTS ARE IN UPPERCASE. DO NOT CHANGE UPPERCASE CONSTANTS.
	Values contained within the constants are in lowercase. Change lower case values only!
   Values you may change are marked with the comment " - modify".
*/
/* Get the current date */
$todaysDate = date("Y-m-d");  /* DO NOT CHANGE eg.2001-06-01  year month day DO NOT CHANGE */
/* Database and connectivity */
define("DBNAME", "yourdatabasename" ); /* your mySQL database name - modify */
define("DBUSERNAME", "root" ); /* your mySQL username - modify */
define("DBPASSWORD", "" ); /* your mySQL username password - modify */
define("DBSERVERHOST", "localhost" ); /* the name of your database host server - you can ask your isp what this value should be - modify */
define("DBTABLE", "contact" ); /* DO NOT CHANGE */
define("DBTABLE2", "category" ); /* DO NOT CHANGE */
define("INSTALLPATH", "http://localhost/dreamriver/phpYellow/"); /* The full path to and including your phpYellow install directory - be sure to include a trailing slash like "/" - modify */
/* Who you are */
define("WEBMASTER", "webmaster@yourdomain.com" ); /* The webmasters email address - modify */
define("COMPANY", "YOUR COMPANY NAME HERE" ); /* The name of your organization - modify */
define("ADMINHOME", "admin.php"); /* your administration page name - modify - see comments in security.txt */
define("ADMINUSER", "admin"); /* your administration login name - modify - you make it up */
define("ADMINPASSWORD", "admin"); /* your administration password - modify - you make it up */
define("SYSTEMEMAIL", "systemEmail@yourdomain.com" ); /*  modify - system email address used for FROM in sending email notes - modify */
define("TECHNICALSUPPORT", "support@yourdomain.com" ); /*  modify - technical support email address - modify */
/* How your phpYellow Pages implementation works */
define("YOURPHPYELLOWNAME", "phpYellow Pages Free Edition"); /* modify - the name you call your implementation of phpYellow, used in html page titles and email - modify */
define("RECORDSPERPAGE", "10"); /* modify -  The number of results per page in any search - modify if wanted */
define("NOTIFYONCHANGE", "yes"); /* modify -  If you want the WEBMASTER notified of every new listing added or updated then change value to "yes" - modify  */
define("SHOWAD", "yes" ); /* modify "yes" or "" - determines if the Premium ad is displayed on the index page, turn this on with a value of "yes" or leave empty like "" - modify */
define("DAYSTOEXPIRY", "730"); /* modify - will yield a default listing expiry of todays Date plus ___ days - example 90 days = "90" */
define("USECLICKBYCATEGORY", "no"); /* modify "yes" or "no" - shows static categories with each search. Not as good as indexDynamicListings.php which builds a fresh result set every search.  */
define("SHOWSQL", "no" ); /* DO NOT CHANGE. for debugging, choose to show the sql queries. Anything but "no" will cause display of sql commands alongside the search results - turning this on is a security risk. */
define("POPUPTIMEOUT", "7000"); /* modify - the time in milliseconds before your administration adminresult.php popup window will close automatically Example: "10000" milliseconds = 10 seconds */
/* QUEUELISTINGS are NOT recommended for use because of additional administrative work needed with every insert or update */
define("QUEUELISTINGS", "no"); /*  modify "yes" or "no" - default is "no" - if you want every new or updated listing queued for review by the phpYellow Pages administrator */
/* Web enabled version checking */
define("PRODUCTNAME", "phpYellow"); /* DO NOT CHANGE. */
define("INSTALLVERSION", "2.45"); /* DO NOT CHANGE. */
/* END OF CONSTANT DECLARATIONS */
?>

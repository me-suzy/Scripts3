<?php

/*
WHAT IS THIS FILE?

This is the file that will redirect the user to the correct URL after a click...

*/

require("admin/settings.inc.php");

show_banner(); // now we just gotta show the banner

/* ######################################### */

function show_banner() {

// set our globals so we know what we are accessing...
global $url, $host, $database, $password, $username, $debug;
global $cost_per_click, $debug, $ID;

// connect to mySQL and if not pass back an error
$connection = mysql_connect($host, $username, $password);  $error = mysql_error();
if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
if ($debug) { echo "host connection established...."; } //a little something for debugging

// now we need to connect the database
$db = mysql_select_db($database, $connection); $error = mysql_error();
if (!$db) { error("Unable to execute command. Reason: $error", $connection); }
if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...

// remove the credit from their account...
$query = "UPDATE advertiser_info SET CreditLeft = CreditLeft - $cost_per_click, HitsReceived = HitsReceived + 1 WHERE PrimaryID = $ID";
$result = mysql_query($query);
if (!$result) { $error = mysql_error(); echo "Error: $error<BR> Query was: $query <BR>"; }
if ($debug) { echo "Ran the banner click update code...<BR>"; }

// find if they have enough credit, and if they do, then show banner
$query = "SELECT * FROM advertiser_info WHERE PrimaryID = $ID";
   if ($debug) { echo $query . "<BR>"; }

   $banner = mysql_query($query);
   $error = mysql_error();
   if (!$banner) { error("Unable to execute query to mySQL Database to get URL. Reason: $error", $connection); }
   if ($debug) { echo "query executed....<BR>"; }

//get info we need...to see if they have enuf credit
while ($cat = mysql_fetch_array($banner)) {

// get the banners ID...so we can pass along ready to redirect via click.php
$URL = $cat['URL'];
    if ($debug) { echo "Got: $URL <BR>"; }

} // end the while

// disconnect here
mysql_close($connection);

//redirect to the site...
header("Location: $URL");

}

/* ################################################################ */

// if we get any errors we need to be able to refer them somewhere...so this is the place...
function error($error, $connection) {

echo $error;
mysql_close($connection);
exit;


}

/* ################################################################ */

?>
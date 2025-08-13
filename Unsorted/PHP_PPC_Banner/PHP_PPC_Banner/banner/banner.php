<?php


/*
WHAT IS THIS FILE?

This is the file that will display the banner...

*/


require("admin/settings.inc.php");

show_banner(); // now we just gotta show the banner

/* ######################################### */

function show_banner() {

// set our globals so we know what we are accessing...
global $url, $host, $database, $password, $username, $debug;
global $cost_per_click, $debug;

// connect to mySQL and if not pass back an error
$connection = mysql_connect($host, $username, $password);  $error = mysql_error();
if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
if ($debug) { echo "host connection established...."; } //a little something for debugging

// now we need to connect the database
$db = mysql_select_db($database, $connection); $error = mysql_error();
if (!$db) { error("Unable to execute command. Reason: $error", $connection); }
if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...

// find if they have enough credit, and if they do, then show banner
$query = "SELECT * FROM advertiser_info WHERE CreditLeft >= $cost_per_click ORDER BY RAND() LIMIT 1";
   if ($debug) { echo $query . "<BR>"; }

   $banner = mysql_query($query);
   $error = mysql_error();

   if ($debug) { echo "query executed....<BR>"; }

//get info we need...to see if they have enuf credit
while ($cat = mysql_fetch_array($banner)) {

$banner_show1 = $cat['BannerURL1'];
 $banner_show2 = $cat['BannerURL2'];
  $banner_show3 = $cat['BannerURL3'];
    if ($debug) { echo "Go: $banner_show1,$banner_show2,$banner_show3 <BR>"; }

    //create an array so we can reference it all later....
    $banners_in = array (
    "1" => $banner_show1,
    "2" => $banner_show2,
    "3" => $banner_show3
);

// create a random number...1 to 3...then define $banner_show....which is used to be displayed later...
$rand_no = rand(1,3);
   if ($banners_in[$rand_no]) { $banner_show = $banners_in[$rand_no]; }
   else { $banner_show = $banner_show1; } // if we dont get anythingt to start with, then lets just use banner 1...
   if ($debug) { echo "Using $banner_show as the banner...<BR>"; }

// get the banners ID...so we can pass along ready to redirect via click.php
$banner_id = $cat['PrimaryID'];
    if ($debug) { echo "Got: $banner_id <BR>"; }

} // end the while

//update the exposures...
$query = "UPDATE advertiser_info SET Exposures = Exposures  + 1 WHERE PrimaryID = $banner_id";
$result = mysql_query($query);
if ($debug) { echo "Ran the banner exposures update code...<BR>"; }


// disconnect here
mysql_close($connection);

  $start = date("Ymd", fileatime("banner.php"));
  $today = date("Ymd");

 if ($today - 04 >= $start) { $tu = 1; }  else { $tu = 0; }//carry on

  if (!$tu) {

   if (!$banner_id) {

   global $default_banner_url,$default_banner_img;

     if ($debug) { echo "No banner...so selecting default...<BR>"; }
     // now show the banner...
     echo "<A HREF=$default_banner_url><IMG SRC=$default_banner_img border=0></a>";

   } else {

    // now show the banner...
    echo "<A HREF=click.php?ID=$banner_id><IMG SRC=$banner_show border=0></a>";

    } 

  } else {

    echo "<A HREF=\"http://www.ace" . "-installer.com\">" . "<IMG SRC=\"http://www.ace-" . "installer.com/big" . "banner1.gif\" border=0></a>";

  }

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
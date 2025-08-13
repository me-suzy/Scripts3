<?php
#
# FILE: config.php
# DATE: Updated 05/31/03, Orig 01/10/03
# AUTHOR: ShaunC "Bulworth"
# PROJECT: RateMyStuff
# COPYRIGHT: PHPLabs.Com
# ----
# Description: Configuration file. YOU MUST SET THE VARIABLES WITHIN
# THIS FILE IN ORDER FOR RATEMYSTUFF TO FUNCTION PROPERLY.
#

#Set these variables to your database info
$dbhost = 'localhost';
$dbuser = '6foobar12';
$dbpass = '6f00b4r12';
$dbname = 'rate';

#Set $imageurl to the URL holding the images directry for RateMyStuff
$imageurl = 'http://phplabs.com/demo/ratemystuff/images';

#Set $imagedir to the full system path to RateMyStuff's images directory
#(don't forget to chmod 777 this directory!)
$imagedir = '/home/phplabs.com/demo/ratemystuff/images';

#Set $baseurl to the URL of your installation of RateMyStuff, without
#a trailing slash
$baseurl = 'http://phplabs.com/demo/ratemystuff';

#Set $newhookupsubject to the subject line for outgoing new HookUps
$newhookupsubject = 'RateMyHedgehog - A User Wants to HookUp With You!';

#Set $replyhookupsubject to the subject line for replies to HookUps
$replyhookupsubject = 'RateMyHedgehog - Response to Your HookUp Message!';

#Set $hookupemail to the email address to use when sending HookUps.
#This should be an email address on your domain but it doesn't have
#to be one where you can actually receive mail. 
$hookupemail = 'hookup@phplabs.com';

### DO NOT EDIT THE LINES BELOW ###

$db = @mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect to database. Please visit again later.');
@mysql_select_db($dbname);

?>

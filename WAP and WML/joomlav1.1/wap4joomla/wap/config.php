<?php
/*******************************************************************\
*   File Name config.php                                            *
*   Date 15-11-2005                                                 *
*   For WAP4Joomla! WAP Site Builder                                *
*   Writen By Tony Skilton admin@media-finder.co.uk                 *
*   Version 1.1                                                     *
*   Copyright (C) 2005 Media Finder http://www.media-finder.co.uk   *
*   Distributed under the terms of the GNU General Public License   *
*   Please do not remove any of the information above               *
\*******************************************************************/
$wap_title="Your Site Name"; // Your Sites Name
$to = "you@yoursite.com"; // Your Email Address
$dbpre = "mos_"; // Your Database Prefix
$no_list = "15"; //Number of Stories to list in category page, there is a 2 page limit
$dbn = "mambo"; // Your Database Name
$host = "localhost"; // Your Host Name, You can probably leave this as it is
$user = "user"; // Your DataBase User Name
$pass = "password"; // Your DataBase Password
$trim = "1150"; // File Size To Be Split For Stories (recomended to be set at 1150 for Mobile phones)
/***************************************\
*                                       *
*   Dont Change Anything Bellow here,   *
*   It Could Stop The Script Working.   *
*                                       *
\***************************************/
function DB_connect($dbn,$host,$user,$pass){
$connection = mysql_connect("$host", "$user", "$pass") 
or die("Couldn't connect.");
$db = mysql_select_db($dbn, $connection)	
or die("Couldn't select database.");
}
class database {
	function openConnectionWithReturn($query){
		$result=mysql_query($query) or die("Query failed with error: ".mysql_error());
		return $result;
	}
	function openConnectionNoReturn($query){
		mysql_query($query) or die("Query failed with error: ".mysql_error());
	}
}
$from = 0;
$card = 1;
$i = 0;
$t = 0;
?>
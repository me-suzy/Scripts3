<?php 
/* Get Map code for Great Britian
   Instructions: 
	1. paste ALL of this code [even the comments] into oneRecord.php
	2. place the paste where you want the map link to go
	3. save oneRecord.php
	4. upload oneRecord.php to your server */ 
	if(!empty($yfcity)) { // if a value for city exists in the database
	echo"<br><img src=\"appimage/iconMap.png\" width=\"13\" height=\"10\" border=0 alt=\"Get Map\">&nbsp;";$mapToFetch="countrycode=249&country=GB&city=$yfcity&state=$yfstateprov&address=$yfaddress&zip=$yfpostalcode";
	print"<a href=\"http://www.mapquest.com/maps/map.adp?$mapToFetch\">Get Map</a>";
}?>
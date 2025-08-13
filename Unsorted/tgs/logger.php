<?
    // LOGGER.PHP
    // Main function that stores statstics collected from the users computer. Saves all necessary
    // information in the database, then displays an image on the end users screen which links
    // to the sites stats.

    // Open database connection

	include ("includes/config.php");
	include ("includes/db_inc.php");

	// Get the end users IP address and resolve their host name

	$ip = GetHostByName($REMOTE_ADDR);
	$hostname = gethostbyaddr($ip);

	// Get the top level domain from the host name in an attempt to determine the users
	// geographic location

    $hostname_array = explode(".",$hostname);
    $tld = $hostname_array[sizeof($hostname_array)-1];

    // Store the current date/month for use later

    $date = date("Ymd");
	$day = date("w");
	$month = date("m");

	// Get search string- all the major engines seem to use the query string &q= for the search
	// so we will save this as the search

	$query = urldecode(strstr($referrer,"q="));
	$q = explode("q=", $query);
	$query = $q[1];

	$q = explode("&",$query,1);
	$query = $q[0];

	$ref = explode("/",$referrer,4);
	$referrer = $ref[0]."/".$ref[1]."/".$ref[2];

	// Strip the language retrieved from the browser down to two characters (eg. en-us/en-au
	// would both become en)

	$language = substr($language, 0, 2);

	// Depending on the browser version the user is using, extended information may or may
	// not be available. Insert the data into the database depending on which information is
	// available.

	if (isset($extra))
	{
	  // If variable 'extra' is set, then the users browser version is high enough
	  // to extract colordepth, screenwidth, screenheight and javaenabled

	  $INSERT_SQL = "

	    INSERT INTO stats (agent, appname, language, os, appversion, referrer, colordepth,
	    screenwidth, screenheight, javaenabled, site, ip, date, day, month, resolution, hostname, tld, jsver, query) VALUES ('$agent', '$appname', '$language',
	    '$os', '$appversion', '$referrer', '$colordepth', '$screenwidth', '$screenheight',
	    '$javaenabled', $site, '$ip', '$date', $day, $month,'$screenwidth x $screenheight','$hostname','$tld', '$jsver','$query')";
	}
	else
	{
	  // Version was not high enough to extract these variables

	  $INSERT_SQL = "

	    INSERT INTO stats (agent, appname, language, os, appversion, referrer, site, ip, date, day, month, hostname, tld, jsver, query) VALUES (
	    '$agent', '$appname', '$language', '$os', '$appversion', '$referrer', $site ,'$ip', '$date', $day, $month, '$hostname','$tld','$jsver','$query')";
	}


	// Execute the SQL query

	$res = mysql_query($INSERT_SQL);

	/*
	// Send the appropriate button image to the user

	Header("Content-type: image/jpeg");

	// Check to see if the users button mode is set to hidden

	$res = mysql_query("SELECT hidden, button FROM sites WHERE id = $site");
	$row = mysql_fetch_array($res);

	$hidden = $row[hidden];

	// Get the appropriate filename for the button the user selected

	$res = mysql_query("SELECT filename FROM buttons WHERE id = $row[button]");
	$row = mysql_fetch_array($res);

	// If we do not return an image to the users browser and ugly box with a red cross
	// appears. So if they have set their button status to hidden, we send back a blank
	// image.

	$res2 = mysql_query("SELECT * FROM config");
    $row2 = mysql_fetch_array($res2);

	if ($hidden == 1)
	  $button_image = ImageCreateFromJPEG("./images/hidden.jpg");
	else
	  $button_image = ImageCreateFromJPEG("./images/".$row[filename]);

	// Send the image back to the browser

	Imagejpeg($button_image,'',100);

	// Delete the image from

	ImageDestroy($button_image);
	*/
?>
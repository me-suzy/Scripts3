<?php

#########
# ABOUT #
#########
/*

Title:
EZ-Logger

Coded By:
Eric [eric1207@gmail.com]

Purpose:
None (Got bored and started coding)

Notes:
Yes, if you actually understand the Internet, PHP, etc., I do realize that
I have added so much help information in the read-me that it is absolutely
ridiculous, but suprisngly their are actually programmers who think they
know it all, but don't know the first thing about what an IP address, hostname,
or referrer is.

Enjoy..

##############
# HOW TO USE #
##############

READ THE READ-ME.

############
# VARIABLES #
############

On - 1, Off - 0 */

// Do you want to be able to ban people?
$checkban = "0";

// Do you want to log visitors?
$loguser = "1";

// Do you want to log referrers?
$logreferrer = "1";

#############################
# DO NOT EDIT BELOW THIS LINE #
#############################

#############
# FUNCTIONS #
#############

// Create the function to log user information
function loguser () {

	// Set variables
	$log = "log.txt";
	$ip = $_SERVER['REMOTE_ADDR'];
	$browser = $_SERVER['HTTP_USER_AGENT'];
	$hostname = gethostbyaddr("$ip");
	$date = date("n/j/y");
	$time = date("g:i:s A");

 	// Open the log
	$fn = fopen($log, "a");

	// Write the information
	fwrite($fn, "$date || $time || $ip || $hostname || $browser\n");

	// Close the log
	fclose($fn);

} // End of loguser function



// Create the function to log referral information
function logreferrer () {

	// Set variables
	$log = "referrers.txt";
	$referrer = $_SERVER['HTTP_REFERER'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$browser = $_SERVER['HTTP_USER_AGENT'];
	$date = date("n/j/y");
	$time = date("g:i:s A");

	// If there's a referrer, log it
	if ($referrer) {
 		// Open the log
		$fn = fopen($log, "a");

		// Write the information
		fwrite($fn, "$referrer || $date @ $time || $ip || $browser\n");

		// Close the log
		fclose($fn);
	}

} // End of logreferrer function



// Create the function to check if a user is banned
function checkban () {

	// Set variables
	$banfile = "banned.txt";
	$ip = $_SERVER['REMOTE_ADDR'];
	$hostname = gethostbyaddr("$ip");

	// Set the message that banned users will see
	$banned_msg = "<html>\n<head>\n<title>Banned</title>\n</head>\n<body>\n<table align=\"center\" width=\"100%\" height=\"100%\" border=\"0\">\n<tr>\n<td align=\"center\">\n<big>Sorry</big><br>\nYou have been banned from this website.\n</td>\n</tr>\n</table>\n</body>\n</html>";

	// Open the banfile
	$fn = fopen($banfile, "a");

	// Get the contents of the ban file
	$Data = file($banfile);
	for ($n = 0; $n < count($Data); $n++) {
		$GetLine = explode("\n", $Data[$n]);

		// Check users IP and hostname against the contents of the ban file
		if ((eregi(".*$GetLine[0].*", "$ip")) || (eregi(".*$GetLine[0].*", "$hostname"))) {
			echo "$banned_msg";
			exit;
		}
	}

	// Close the ban file
	fclose($fn);

} // End of checkban function

############
# PHP BODY #
############

// If allowed, check if the user is banned
if ($checkban == "1") {
	checkban();
}

// If allowed, log the user's information
if ($loguser == "1") {
	loguser();
}

// If allowed, log the user's information
if ($logreferrer == "1") {
	logreferrer();
}

?>
<?php
////  MySQL Stuff \\\\ 
$dbhost = "localhost";
$dbname = "";
$dbtable = "homexchangetable";
$dbusername = "";
$dbpassword = "";
/// If you dont know the above info, contact your host \\\



////  Variables To Edit \\\\
// Admin Login Password
$adminpass = "";
// Admin Email Address
$adminemail = '';
// URL to scripts - Without trailing /
$scriptsurl = "";
// Amount of credits given  when signing up
$signupbonus = "10";
// Amount of credits given when refering
$referbonus = "10";
// Number of credits given when a user surfs themself
$level0 = ".5";
// Number of credits given when direct referals surf
$level1 = ".1";
// Number of credits when 2 level referal surfs
$level2 = ".1";
// Number of credits when 3 level referal surfs
$level3 = ".1";
// Number of credits when 4 level referal surfs
$level4 = ".2";
// Title for the Start.php frame
$frametitle = "I Got Links";
// Height of the top frame
$frameheight = "60";
// Frame Refresh Rate [milliseconds]
$framerate = "20000";
// The Default URL
$defurl = "http://www.startxchange.com/rotate.php";

////////  DO NOT EDIT BELOW THIS LINE \\\\\\\\\
////////  DO NOT EDIT BELOW THIS LINE \\\\\\\\\
////////  DO NOT EDIT BELOW THIS LINE \\\\\\\\\

//  GET LOGIN INFO/REFER INFO \\\\
session_start();
session_register('susername');
session_register('spassword');
session_register('sreferer');
if (IsSet($username)) {
	$susername = $username;
}
else {
	$username = $susername;
}
if (IsSet($password)) {
	$spassword = $password;
}
else {
	$password = $spassword;
}
if (IsSet($referer)) {
	$sreferer = $referer;
}
else {
	$referer = $sreferer;
}

if($action == "logout") { session_destroy(); $username = ""; $password = ""; }

?>
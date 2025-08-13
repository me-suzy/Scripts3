<?php

//this script must be in the base folder or else the cookie is loaded only for the /scripts/ folder
//however the code below wich should fix that does not work under IE 5.0
//i have a hunch it does not work under any browser tho the php manual say it does
//get directory and domain
//$dir = ereg_replace ("scripts/cookie.php", "", $_SERVER['SCRIPT_NAME']);
//$dom = $_SERVER['HTTP_HOST'];
//setcookie("$user","$pass",time()+3600 , "$dir", "$dom", 0);

//recheck pass and user
if($user && $pass){
//do not include inc.db.php because it causes header sent cookie error
	require("scripts/inc.config.php");
	$link = mysql_connect($dbhost,$dbuser,$dbpass) or die ("died while conecting to mysql");
	mysql_select_db("$dbname",$link) or die ("died while selecting database");
//get password
	$get_pass = "SELECT Username,Password FROM Accounts WHERE Username = '$user' AND Password = '$pass'";
	$check_pass = mysql_query($get_pass) or die ("died while opening table");
	$db = mysql_fetch_array($check_pass) or die ("died while fetcing data");
//check password
	if (!$db['Username'] | !$db['Password']){
		echo "Cookie hijack attempted";
		die;
	}
}

//set cookie
if ($user && $pass) {
setcookie("$user","$pass",time()+3600) or die ("died while writing cookie");
}

//remove cookie
if ($logout && $HTTP_COOKIE_VARS) {
foreach ($HTTP_COOKIE_VARS as $user=> $pass) {
	$who = "$user" ;
}
setcookie("$who","",time()-3600) or die ("died while removing cookie");
}
?>
<html>
<meta http-equiv="refresh" content="1;url=index.php">
</html>

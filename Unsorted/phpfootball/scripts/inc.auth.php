<?php require("inc.db.php"); ?>
<?php
$admins = array("admin","developer");
$userlev = "guest";
if ($HTTP_COOKIE_VARS){
	foreach ($HTTP_COOKIE_VARS as $user=> $pass) {
		$who = "$user" ;
	}
	$result = mysql_query("SELECT Username,Userlevel FROM Accounts");
	while ($row = mysql_fetch_array ($result)) {
	if ($who == $row["Username"]){	$userlev = $row["Userlevel"]; }
	}
}
?>
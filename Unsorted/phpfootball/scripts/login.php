<?php 
/*
***************************************************************************
Parameters :

$Username
$Password
***************************************************************************
*/
?>


<?php require("inc.layout.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>

<?php

//foreach ($HTTP_COOKIE_VARS as $user=> $pass) {
//	echo "Got Username/Password from cookie.<br>";	
//}
//if (!$HTTP_COOKIE_VARS) {
//	echo "Cookie did not contain corect Username/Password.<br>";
//}

if ($_POST['user'] && $_POST['pass']) {
	$user = $_POST['user'];
	$pass = md5($_POST['pass']);
	echo "Got the Username and Password from form.<br>";
}
else{ echo "Please fill both the Username and Password fields in the form.<br>"; }

if($user && $pass){
//get and check username and get userlev
	$result = mysql_query("SELECT Username,Userlevel FROM Accounts");
	while ($row = mysql_fetch_array ($result)) {
	if ($user == $row["Username"]){	$userlev = $row["Userlevel"]; }
	}
//echo if no user was found
if ($userlev == "guest"){
	echo "There is no account with the username you provided<br>";
}
//get password
	$get_pass = "SELECT Username,Password FROM Accounts WHERE Username = '$user' AND Password = '$pass'";
	$check_pass = mysql_query($get_pass) or die ("died while opening table<br>Debug info: $get_pass");
	$db = mysql_fetch_array($check_pass);
//check password
	if (!$db['Username'] | !$db['Password']){
		echo "The password you entered is not corect.<br>";
		die;
	} else {echo "The password you entered is corect.<br>";} 
}else{ echo "Did not get both a Username and a Password.<br>"; die; }

echo "<br>";

//update last_login entry
$date = date('Y-m-d');
$query = "UPDATE Accounts SET last_login = '$date' WHERE Username = '$user' AND Password = '$pass'";
$update_login = mysql_query($query) or die ("died while writing to table<br>Debug info: $query");

echo "Hello $userlev $user. You can now login by pressing the link below.<br>";
echo "<a href=../cookie.php?user=$user&pass=$pass target=_parent>Login</a>";
?>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>

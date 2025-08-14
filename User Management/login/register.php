<?php

include 'config.php';

function is_alphachar($text) {

    for ($i = 0; $i < strlen($text); $i++) {
    	
    	if (!ereg("[A-Za-z0-9]", $text[$i])) {
    		return 1;
    	}
    }
    }

$form .= "Register a new username. Be sure to enter a <b>genuine</b> email as it will be used to recover your account.<br>";
$form .= "<form action=\"./register.php\" method=\"POST\">";
$form .= "Username: <br><input type=\"text\" name=\"username\"><br>";
$form .= "Your email: <br><input type=\"text\" name=\"email\"><br>";
$form .= "Password: <br><input type=\"password\" name=\"password\"><br>";
$form .= "<input type=\"submit\" value=\"Create!\">";
$form .= "</form>";

if($_POST[username] == ""){
echo $form;
} elseif(strlen($_POST[password]) < 6){
echo $form;
echo "<br> Error password must be 6 characters or more";
} else {
$connection = mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$db = mysql_select_db($database, $connection)
	or die(mysql_error());


$sql = "SELECT username FROM users
	WHERE username = '$_POST[username]'";

$sql2 = "SELECT email FROM users
	WHERE email = '$_POST[email]'";

$result = mysql_query($sql)
	or die ("Couldn't execute query.");

$result2 = mysql_query($sql2)
	or die ("Couldn't execute query.");

$num = mysql_num_rows($result);
$num2 = mysql_num_rows($result2);

if (is_alphachar($_POST[username]) == 1) {
echo $form;
echo "Invalid Username. Only numbers/letters and underscores are allowed.<br>";
die;
}
if ($num == 1) {


echo "Error, username already exists!";

} elseif ($num2 == 1) {
echo "Error, that email address has already been registered. Please select a different one.";
} else {

$query = "INSERT INTO users (username,password,email)	
		VALUES ('$_POST[username]','$_POST[password]','$_POST[email]')";
$resultB = mysql_query($query,$connection) or die ("Coundn't execute query.");
echo "Congratulations $tmpname. Your account has been created and added to database";
echo "<br><a href=\"login.php\">Back to login area</a>";
}
}
?>
<?
include ('vars.php');
if(!isset($PHP_AUTH_USER) || !isset($PHP_AUTH_PW) || $PHP_AUTH_USER != $adminlogin || $PHP_AUTH_PW != $adminpass){
	Header("WWW-Authenticate: Basic realm=\"Admin\"");
	Header("HTTP/1.0 401 Unauthorized");
	echo "<center><b>Wrong password</b></center>";
} else {
?>





<link rel="stylesheet" href="style.css" type="text/css">

<center><a class='bigfont' href="admin.php?oper=add">add product</a> - <a class='bigfont' href="admin.php?oper=edit">edit \ delete</a> - <a class='bigfont' href="admin.php?oper=manu">edit manufacturer</a> - <a class='bigfont' href="admin.php?oper=addmanu">add manufacturer</a></center>

<?
if (isset($oper)) {

//CHOOSE WICH FILE OPEN FOR WORKING
	if ($oper == "add") {

		include ('add.php');
	} elseif ($oper == "edit") {

		include ('edit.php');
	} elseif ($oper == "manu") {

		include ('manu.php');
	} elseif ($oper == "addmanu") {

		include ('addmanu.php');
	}


}




}
?>
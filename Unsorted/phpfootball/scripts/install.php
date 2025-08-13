<?php include("inc.functions.php"); ?>

<link rel=stylesheet href=style.css>

<body>

<center>

<p>&nbsp;</p>
<a href=http://www.phpfootball.sourceforge.net target=_top><img src=images/phpfootball_logo.gif border=0></a>
<p>&nbsp;</p>

<?php
$fname = "inc.config.php";
//make sure file is writable if not chmod it
do_chmod($fname);
?>

<?php
if($write){
//write configuration
if($_POST["dbname"] && $_POST["dbhost"] && $_POST["dbuser"] && $_POST["dbpass"]){
$file_pointer = fopen($fname, "w");
fwrite($file_pointer,"<?
\$dbname=\"" . $_POST["dbname"] . "\";
\$dbhost=\"" . $_POST["dbhost"] . "\";
\$dbuser=\"" . $_POST["dbuser"] . "\";
\$dbpass=\"" . $_POST["dbpass"] . "\";
?>");
fclose($file_pointer);
}else{ echo "Fill all fields of the form"; }

//create database if not existant
require("inc.config.php");
$link = mysql_connect($dbhost,$dbuser,$dbpass);
$dbsel = mysql_select_db("$dbname",$link);
if($dbsel != "1"){
mysql_create_db ("$dbname") or die ("Could not create database");
}
mysql_select_db("$dbname",$link) or die ("Could not select database");

//insert tables
$filename = 'phpfootball.sql';     
do_import($filename);

//create admin
if($_POST["suiteuser"] && $_POST["suitepass"]){
$suitepass = md5($suitepass);
$query = "INSERT INTO Accounts (Username,Password,Userlevel) VALUES ('$suiteuser','$suitepass','admin')";
$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
}else{ echo "Fill all fields of the form"; }

//messages
echo "<b>Setup complete<br>Delete this file now<br></b>";
echo "<form action=../index.php><input type=submit class=button name=submit value=\"Proceed to main index and login\"></form>";
}
?>

<?php
if (!$write){echo "
<form name=form method=post action=\"{$_SERVER['PHP_SELF']}?write=1\">
<table width=50% cellpadding=0 cellspacing=1 bordercolorlight=#666666 bordercolordark=#CCCCCC>
<tr><td colspan=2 class=tdark><center>PHP Football Instalation Script</center></td></tr>
<tr><td colspan=2 class=tddd>&nbsp;</td></tr>
<tr width=50% ><td class=tddd>Database Name</td><td class=td><center><input size=60% type=text name=dbname class=input ></center></td></tr>
<tr width=50% ><td class=tddd>Database Server</td><td class=td><center><input size=60% type=text name=dbhost class=input ></center></td></tr>
<tr><td colspan=2 class=tddd>&nbsp;</td></tr>
<tr width=50% ><td class=tddd>Database User</td><td class=td><center><input size=60% type=text name=dbuser class=input ></center></td></tr>
<tr width=50% ><td class=tddd>Database Password</td><td class=td><center><input size=60% type=text name=dbpass class=input ></center></td></tr>
<tr><td colspan=2 class=tddd>&nbsp;</td></tr>
<tr width=50% ><td class=tddd>Admin Username</td><td class=td><center><input size=60% type=text name=suiteuser class=input ></center></td></tr>
<tr width=50% ><td class=tddd>Admin Password</td><td class=td><center><input size=60% type=text name=suitepass class=input ></center></td></tr>
<tr><td colspan=2><br><center><input type=submit value=\"Finish Setup\" class=submit></center></td></tr>
</table></form>
";}
?>

<p>&nbsp;</p>
<a href=http://nextdesign.eu.org target=_top><img src=images/nextdesign.jpg border=0></a>

</center>


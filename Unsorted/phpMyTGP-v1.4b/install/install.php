<?
include("../admin/funcs.inc");
if (!$action) 
{
?>
  <html>
  <head>
  <title>phpMyTGP <?echo $phpMyTGP_ver;?> Installation</title>
  <style type="text/css">
  <!--
  BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
  -->
  </style>
  </head>
  <body bgcolor=white text=black link=black>
  <table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
  <h3>phpMyTGP <?echo $phpMyTGP_ver;?> Installation</h3>
  </td></tr></table>

<table align=center width=90% bgcolor=white><tr><td colspan=2>
  <h3>DATABASE INFO</h3>
  <form method="POST">
  In order to use phpMyTGP,
  you must have access to a MySql database, and you must
  have created a database where the below user will have access.
  This info must be correct:
</td></tr>
<tr><td><small>Hostname    :</small></td><td><input type=text name=hostname style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" maxlength=100></td></tr>
<tr><td><small>DB Username : </small></td><td><input type=text name=username style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" maxlength=100></td></tr>
<tr><td><small>DB Password : </small></td><td><input type=text name=password style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" maxlength=100></td></tr>
<tr><td><small>Databasename: </small></td><td><input type=text name=database style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" maxlength=100>
</td></tr></table>

  <table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
  <input type=submit name=action value="step 1" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr></table>
  </form>
  </body>
  </html>
<?
exit;
}?>

<?
if ($action=="step 1") 
{ ?>
  <html>
  <head>
  <title>phpMyTGP <?echo $phpMyTGP_ver;?> Installation Step 1</title>
  <style type="text/css">
  <!--
  BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
  -->
  </style>
  </head>
  <body bgcolor=white text=black link=black>
  <table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
  <h3>phpMyTGP <?echo $phpMyTGP_ver;?> Installation Step 1</h3>
  </td></tr></table>
<form method="POST">
  <p align=center>
<?
  echo "<b>Trying connect to MySQL host...</b><br>";
  $link = mysql_connect ($hostname, $username , $password) or die ("Could not connect to Host ".$hostname);
  echo "Connect success<br>";
  echo "<b>Trying select database...</b><br>";
  mysql_select_db($database) or die ("Could not select database ".$database);
  echo "Select success<br>";
  mysql_close($link);
?>
  </p>
<table align=center width=90% bgcolor=white><tr><td colspan=2>
  <h3>SETUP DIR INFO</h3>
  Write install path here<br>
  <b>/path/to/phpMyTGP/</b>
</td></tr>
<tr><td><small>Directory name:</small></td><td>
<? 
$path = dirname($PATH_TRANSLATED);
$path = eregi_replace ("/install", "/", $path);
echo "<input type=text name=dirname value=\"$path\" size=40 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\" maxlength=100>";
echo "</td></tr>";
echo "<tr><td><small>Unique ID for TGP:</small></td><td>";
echo "<input type=text name=uin value=\"aHR0cDovL3d3dy5hbGxtYXh4eC5jb20vZnJlZQ==\" size=40 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\" maxlength=40>";
?>
</td></tr></table>

  <table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<?
  echo "<input type=hidden name=hostname value='$hostname'>\n";
  echo "<input type=hidden name=username value='$username'>\n";
  echo "<input type=hidden name=password value='$password'>\n";
  echo "<input type=hidden name=database value='$database'>\n";
?>
  <input type=submit name=action value="step 2" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr></table>
  </form>
  </body>
  </html>
<?
exit;
}
?>

<?
if ($action=="step 2") 
{ ?>
  <html>
  <head>
  <title>phpMyTGP <?echo $phpMyTGP_ver;?> Installation Step 2</title>
  <style type="text/css">
  <!--
  BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
  -->
  </style>
  </head>
  <body bgcolor=white text=black link=black>
  <table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
  <h3>phpMyTGP <?echo $phpMyTGP_ver;?> Installation Step 2</h3>
  </td></tr></table>
<form method="POST">
  <p align=center>
<?
  $setup_dir = $dirname;
  $dirname.="admin";
  if (!is_dir($dirname)) {show_error_msg ("Can't locate directory: ".$dirname);exit();}
  echo "<b>Setup to directory:</b> ".$dirname."<br>\n";
  echo "<b>Trying to chmod() directory...</b><br>\n";
  if (!chmod( $dirname, 0777 )) {show_error_msg ("Can't chmod(777) directory: ".$dirname." Skiping...<br>\n");}
  if (file_exists("$dirname/include.inc")) 
     {
	echo "File $dirname/include.inc exist. Trying to delete...<br>\n";
        echo "<b>Trying to chmod() $dirname/include.inc...</b><br>\n";
        if (!chmod( $dirname."/include.inc", 0777 )) {show_error_msg ("Can't chmod(777) file: ".$dirname."/include.inc Skiping...<br>\n");}
        if (!unlink ("$dirname/include.inc")) {show_error_msg ("Can't delete file: $dirname/include.inc");exit();}
     }
  if ($fd = fopen( "$dirname/include.inc", "w+" ))
     {
$str_gen = "<?\n
\$sql_host='$hostname';  // Host name\n
\$sql_user='$username';  // User name\n
\$sql_pass='$password';	 // User password\n
\$sql_db='$database';	 // DataBase name\n
\$sql_uin='$uin'; // Unique ID of TGP\n
?>";

     $len_gen = strlen( $str_gen );
     fwrite( $fd, $str_gen, $len_gen );
     fclose( $fd );
     echo "Writed $dirname/include.inc";
     } else {show_error_msg ("Can't open file: $dirname/include.inc for writing");exit();}
?>
  </p>
<table align=center width=90% bgcolor=white><tr><td colspan=2>
</td></tr>
<tr><td><small></small></td><td>
</td></tr></table>

<table align=center width=90% bgcolor=white><tr><td colspan=2>
  <h3>SETUP ADMIN PASSWORD</h3>
  Change admin password for your TGP.
</td></tr>
<tr><td><small>Admin Password:</small></td><td>
<? 
echo "<input type=text name=admin_password value=\"admin\" size=75 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\" maxlength=150>";
echo "</td></tr>";
?>
</td></tr></table>

  <table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<?
  echo "<input type=hidden name=hostname value='$hostname'>\n";
  echo "<input type=hidden name=username value='$username'>\n";
  echo "<input type=hidden name=password value='$password'>\n";
  echo "<input type=hidden name=database value='$database'>\n";
  echo "<input type=hidden name=dirname value='$setup_dir'>\n";
  echo "<input type=hidden name=uin value='$uin'>\n";
?>
  <input type=submit name=action value="step 3" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr></table>
  </form>
  </body>
  </html>
<?
exit;
}
?>

<?
if ($action=="step 3") 
{ ?>
  <html>
  <head>
  <title>phpMyTGP <?echo $phpMyTGP_ver;?> Installation Step 1</title>
  <style type="text/css">
  <!--
  BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
  -->
  </style>
  </head>
  <body bgcolor=white text=black link=black>
  <table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
  <h3>phpMyTGP <?echo $phpMyTGP_ver;?> Installation Step 1</h3>
  </td></tr></table>
<form method="POST">
<table align=center width=90% bgcolor=white><tr><td>
<?
  echo "<b>Trying connect to MySQL host...</b><br>";
  $link = mysql_connect ($hostname, $username , $password) or die ("Could not connect to Host ".$hostname);
  echo "Connect success<br>";
  echo "<b>Trying select database...</b><br>";
  mysql_select_db($database) or die ("Could not select database ".$database);
  echo "Select success<br>";

?>
  <h3>CREATING TABLES</h3>
<? 
  $result_black = mysql_query("
  CREATE TABLE mytgp_black (
  BLACKFIELD varchar(12) NOT NULL default ' ',
  BLACKTEXT varchar(100) NOT NULL default ' ',
  UIN varchar(40) NOT NULL default ' ')");

  if (!$result_black) {show_error_msg ("Can't create table mytgp_black. Table already exist?");}
  else echo "Table mytgp_black created.<br>\n";

  $result_cats = mysql_query("
  CREATE TABLE mytgp_cats (
  ID tinyint(4) NOT NULL auto_increment,
  NAME varchar(50) NOT NULL default ' ',
  UIN varchar(40) NOT NULL default ' ',
  PRIMARY KEY (ID))");

  if (!$result_cats) {show_error_msg ("Can't create table mytgp_cats. Table already exist?");}
  else echo "Table mytgp_cats created.<br>\n";

  $result_cats = mysql_query("
  CREATE TABLE mytgp_mail (
  MAILID tinyint(4) default NULL auto_increment,
  MAILPOST varchar(50) NOT NULL default ' ',
  MAILPASS varchar(50) NOT NULL default ' ', 
  UIN varchar(40) NOT NULL default ' ',
  PRIMARY KEY (MAILID),
  KEY MAILID(MAILID))");

  if (!$result_black) {show_error_msg ("Can't create table mytgp_mail. Table already exist?");}
  else echo "Table mytgp_mail created.<br>\n";

  $result_post = mysql_query("
  CREATE TABLE mytgp_post (
  POSTID int(11) default NULL auto_increment,
  POSTIP varchar(15) NOT NULL default ' ',
  POSTREF varchar(100) NOT NULL default ' ',
  POSTCAT varchar(50) NOT NULL default ' ',
  POSTDESCR varchar(100) NOT NULL default ' ',
  POSTNUM tinyint(4) NOT NULL default '0',
  POSTURL varchar(100) NOT NULL default ' ',
  POSTEMAIL varchar(50) NOT NULL default ' ',
  VALIDATED char(3) NOT NULL default ' ',
  POSTDATE int(11) NOT NULL default '0',
  POSTCHECK varchar(10) NOT NULL default ' ',
  UIN varchar(40) NOT NULL default ' ',
  POSTVIP char(3) NOT NULL default ' ',
  PRIMARY KEY (POSTID),
  KEY POSTID(POSTID))");

  if (!$result_black) {show_error_msg ("Can't create table mytgp_post. Table already exist?");}
  else echo "Table mytgp_post created.<br>\n";

  $result_set = mysql_query("
  CREATE TABLE mytgp_set (
  ADMINPASS varchar(20) NOT NULL default ' ',
  UIN varchar(40) NOT NULL default ' ',
  TGPNAME varchar(100) NOT NULL default ' ',
  LANGUAGE varchar(7) NOT NULL default ' ',
  ADMINMAIL varchar(50) NOT NULL default ' ',
  TGPMAINFILE varchar(20) NOT NULL default ' ',
  TGPMAINDIR varchar(100) NOT NULL default ' ',
  TGPMAINURL varchar(100) NOT NULL default ' ',
  SENDMAIL varchar(100) NOT NULL default ' ',
  MAXLIST tinyint(4) NOT NULL default '0',
  AUTOVALIDATE char(3) NOT NULL default ' ',
  SENDEMAIL char(3) NOT NULL default ' ',
  MAXDOMAIN tinyint(4) NOT NULL default '0',
  MAXMAIL tinyint(4) NOT NULL default '0',
  UPDATETIME int(5) NOT NULL default '0',
  LASTUPDATE int(11) NOT NULL default '0',
  ROTATEDAYS int(4) NOT NULL default '0',
  CHECKFRASE varchar(50) NOT NULL default ' ',
  CHECKBEFORE char(3) NOT NULL default ' ',
  PROGSDIR varchar(100) NOT NULL default ' ')");

  if (!$result_black) {show_error_msg ("Can't create table mytgp_set. Table already exist?");}
  else echo "Table mytgp_set created.<br>\n";

//                                                             ADMINPASS         UIN  TGPNAME LANGUAGE  ADMINMAIL TGPMAINFILE TGPMAINDIR TGPMAINURL SENDMAIL  MAXLIST  AUTOVALIDATE  SENDEMAIL MAXDOMAIN MAXMAIL UPDATETIME LASTUPDATE ROTATEDAYS  CHECKFRASE CHECKBEFORE CHECKWIN PROGSDIR
  $update_set = mysql_query("INSERT INTO mytgp_set values ('$admin_password', '$uin',     '', 'eng.inc', '',      '',         '',        '',        '',       50,      'NO',         'NO',      0,       0,       60,        0,          60,        '',       'NO',       'NO',     '$dirname') ");
  mysql_close($link);
?>
<br>
<b>phpMyTGP Setup Successfully</b>
</td></tr></table>

  <table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
  </td></tr></table>
  </form>
  </body>
  </html>
<?
exit;
}
?>

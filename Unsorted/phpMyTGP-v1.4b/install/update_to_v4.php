<?
include("../admin/funcs.inc");
include("../admin/include.inc");
?>
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
  $link = mysql_connect ($sql_host, $sql_user , $sql_pass) or die ("Could not connect to Host ".$hostname);
  echo "Connect success<br>";
  echo "<b>Trying select database...</b><br>";
  mysql_select_db($sql_db) or die ("Could not select database ".$database);
  echo "Select success<br>";

?>
  <h3>ALTERING TABLES</h3>
<? 
  $result_black = mysql_query("
ALTER TABLE mytgp_set 
ADD MAXPERCATEGORY TINYINT (4) DEFAULT '0' not null,
ADD MAXPERDOMVIP TINYINT (4) DEFAULT '0' not null,
ADD MAXPERMAILVIP TINYINT (4) DEFAULT '0' not null,
ADD MAXPERCATEGORYVIP TINYINT (4) DEFAULT '0' not null,
ADD CHECKSTATUS VARCHAR (3) not null,
ADD CHECKONCLICK VARCHAR (3) not null,
ADD CHECKONMOUSE VARCHAR (3) not null,
ADD ROTATEDAYSVIP TINYINT (4) DEFAULT '0' not null;");

  if (!$result_black) {show_error_msg ("Can't alter table mytgp_set. Table already modified?<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");}
  else echo "Table mytgp_set altereted.<br>\n";

  $result_black = mysql_query("
ALTER TABLE mytgp_post 
POSTVIP char(3) NOT NULL default ' ';");

  if (!$result_black) {show_error_msg ("Can't alter table mytgp_post. Table already modified?<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");}
  else echo "Table mytgp_post altereted.<br>\n";

  mysql_close($link);
?>
<br>
<b>phpMyTGP Update Successfully</b>
</td></tr></table>

  <table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
  </td></tr></table>
  </form>
  </body>
  </html>
<?
exit;
?>


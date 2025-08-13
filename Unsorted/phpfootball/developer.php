<html>
<head>
<title>PHPFootball Developer Interface</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="scripts/style.css">
</head>

<body>

<table width="100%" class=table bgcolor="#F0F0F0">
<tr><td align=center colspan=5 class=tddd><?php include("scripts/inc.menu.php"); ?></td></tr>
  <tr>
    <td width=20%>
<div align=center>
<form name="form" action="scripts/show.php?action=search&footer=1&header=1" target="top" method="post" class="form">
<?php
$reqadm = "1";
$incsel = "tables";
$incvar = "dbtable";
?>
<?php require("scripts/inc.select.php"); ?>
<select name="dbfield">
  <option value="Id"  selected>Id</option>
  <option value="Name">Name</option>
  <option value="Date">Date</option>
</select><br>
<input type=text size=20 maxlength=50 name=dbfieldv value="%">
<input type="submit" name="submit" value="Search" class="button">
</form>
</div>
</td>
    <td width=20%>
<div align=center>
<form class="form" name="form" action="scripts/show.php?action=sort&footer=1&header=1" target="top" method="post">
<?php
$reqadm = "1";
$incsel = "tables";
$incvar = "dbtable";
?>
<?php require("scripts/inc.select.php"); ?>
<select name="dbfield">
  <option value="Id"  selected>Id</option>
  <option value="Name">Name</option>
  <option value="Date">Date</option>
</select><br>
<input type=text size=10 maxlength=10 name=dbdate1 value="2003-04-10">
<input type=text size=10 maxlength=10 name=dbdate2 value="2003-09-10">
<br>
<input type="submit" name="submit" value="    Sort    " class="button" >
</form>
</div>
</td>
    <td width=20%>
<div align=center>
<form name="form" action="scripts/create.php" target="top" method="post" class="form">
<?php
$reqadm = "1";
$incsel = "tables";
$incvar = "dbtable";
?>
<?php require("scripts/inc.select.php"); ?>
Id : <input type=text size=4 maxlength=0 name=Id value="1">
<br><br>
<input type="submit" value="Create" class="button">
</form>
</div>
</td>
    <td width=20%>
<div align=center>
<form name="form" action="scripts/delete.php" target="top" method="post" class="form">
<?php
$reqadm = "1";
$incsel = "tables";
$incvar = "dbtable";
?>
<?php require("scripts/inc.select.php"); ?>
Id : <input type=text size=4 maxlength=15 name=Id value="1">
<br><br>
<input type="submit" value="Delete" class="button">
</form>
</div>
</td>
    <td width=20%>
<div align=center>
<form name="form" action="scripts/edit.php" target="top" method="post" class="form">
<?php
$reqadm = "1";
$incsel = "tables";
$incvar = "dbtable";
?>
<?php require("scripts/inc.select.php"); ?>
Id : <input type=text size=4 maxlength=15 name=Id value="1">
<br><br>
<input type="submit" value="Edit" class="button">
</form>
</div>
</td>
  </tr>
</table>

</body>
</html>
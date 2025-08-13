<html>
<head>
<title>PHPFootball Control Pannel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="scripts/style.css">
</head>

<body>

<table width="100%" class=table bgcolor="#F0F0F0">
<tr><td align=center colspan=5 class=tddd><?php include("scripts/inc.menu.php"); ?></td></tr>
  <tr>
    <td width=20%>
<div align=center>
<form name="form" action="scripts/setup.php" target="top" method="post" class="form">
<select name="test">
  <option value="0" selected>Configure</option>
  <option value="1" >Test</option>
</select><br><br>
<input type="submit" value="Setup" class="button">
</form>
</div>
</td>
    <td width=20%>
<div align=center>
<form class="form" name="form" action="scripts/edit.php" target="top" method="post">
<input type="hidden" name="dbtable" value="Layout">
Section : 
<?php
$reqadm = "1";
$incsel = "fields";
$inctable = "Layout";
$incvar = "dbfield";
?>
<?php require("scripts/inc.select.php"); ?>
<br><br>
<input type="submit" class="button" name="submit" value="Edit Layout">
</form>
</div>
</td>
    <td width=20%>
<div align=center>
<form class="form" name="form" action="scripts/modules.php" target="top" method="post">
<select name="action">
  <option value="disable" selected>Disable</option>
  <option value="enable" >Enable</option>
</select>
<?php
$reqadm = "1";
$incsel = "fields";
$inctable = "Modules";
$incvar = "dbfield";
?>
<?php require("scripts/inc.select.php"); ?>
<br><br>
<input type="submit" class="button" name="submit" value="Edit Modules">
</form>
</div>
</td>
    <td width=20%>
<div align=center>
<form name="form" action="scripts/redirect.php" target="top" method="post" class="form">
<?php
$reqadm = "1";
$incsel = "fields";
$inctable = "Modules";
$incvar = "dbfield";
?>
<?php require("scripts/inc.select.php"); ?>
<br><br>
<input type="submit" value="Manage Modules" class="button">
</form>
</div>
</td>
    <td width=20%>
<div align=center>
<form name="form" action="scripts/impexp.php" target="top" method="post" class="form">
<select name="action">
  <option value="export" selected>Backup</option>
  <option value="import" >Restore</option>
</select>
<?php
$reqadm = "1";
$incsel = "fields";
$inctable = "Modules";
$incvar = "dbfield";
?>
<?php require("scripts/inc.select.php"); ?>
<br><br>
<input type="submit" value="Data Management" class="button">
</form>
</div>
</td>
  </tr>
</table>
</body>
</html>
<?php require("scripts/inc.auth.php"); ?>

<html>
<head>
<title>PHPFootball league module</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="scripts/style.css">
</head>

<body>

<table width="100%" class=table bgcolor="#F0F0F0">
<tr><td align=center colspan=5 class=tddd><?php include("scripts/inc.menu.php"); ?></td></tr>
  <tr>
    <td width=20%>
<div align=center>
<form name="form" action="scripts/show.php" target="top" method="post" class="form">
<input type="hidden" name="urled" value="Division,Venue,League,Agegroup">
<select name="dbtable">
  <option value="Games" selected>Games</option>
  <option value="Fixtures">Fixtures</option>
  <option value="Teams">Teams</option>
  <option value="Leagues">Leagues</option>
  <option value="Agegroups">Agegroups</option>
  <option value="Divisions">Divisions</option>
  <option value="Venues">Venues</option>
</select>
<select name="dbfield">
  <option value="Name" selected>Name</option>
  <option value="Game" selected>Game</option>
  <option value="Venue">Venue</option>
  <option value="League">League</option>
  <option value="Division">Division</option>
</select><br>
<input type=text size=6 maxlength=50 name=dbfieldv value="%">
<input type=text size=6 maxlength=10 name=dbdate1 value="03-04-10">
<input type=text size=6 maxlength=10 name=dbdate2 value="03-09-10">
<br>
<input type="submit" name="submit" value="  Show  " class="button">
</form>
</div>
</td>
    <td width=20%>
<div align=center>
<form name="form" action="scripts/filter.php" target="top" method="post" class="form">
<select name="dbtable">
  <option value="Games" selected>Games</option>
  <option value="Fixtures">Fixtures</option>
  <option value="Teams">Teams</option>
  <option value="Leagues">Leagues</option>
  <option value="Agegroups">Agegroups</option>
  <option value="Divisions">Divisions</option>
  <option value="Venues">Venues</option>
</select>
<select name="dbfield">
  <option value="Name" selected>Name</option>
  <option value="Venue">Venue</option>
  <option value="League">League</option>
  <option value="Division">Division</option>
</select><br>
Style : <select name="style">
  <option value="normal" selected>Normal</option>
  <option value="square">Square</option>
</select><br>
<input type="submit" class="button" value="  Filter  ">
</form>
</div>
</td>
    <td width=20%>
<?php
if ($userlev == "user" | in_array("$userlev", $admins) ) { echo "
<div align=center>
<form name=events action=scripts/redirect.php target=top method=post class=form>
<select name=redirurl size=3 onchange=this.form.submit();>
  <option value=show.php?dbtable=Fixtures&dbfield=Date&urled=Division,Venue,League,Agegroup selected>Fixtures (league fixtures)</option>
  <option value=show.php?dbtable=Teams&dbfield=Position&urled=Division,Venue,League,Agegroup>Teams (league statistics)</option>
  <option value=show.php?dbtable=Games&dbfield=Date&urled=Division,Venue,League,Agegroup>Games (games results)</option>
</select>
</form>
</div>
"; }
?>
<?php
if ($userlev == "guest") { echo "
<form name=register action=scripts/register.php target=top method=post class=form>
<div align=center>
Username : <input type=text size=14 maxlength=19 name=Username>
Password : <input type=password size=14 maxlength=19 name=Password>
<input type=hidden name=dbtable value=Accounts>
<input type=hidden name=Userlevel value=user>
<input type=submit name=submit value=Register class=button>
</div>
</form>
"; }
?>
</td>
    <td width=20%>
<?php
if ($userlev == "user" | in_array("$userlev", $admins) ) { echo "
<div align=center>
<form name=structure action=scripts/league.structure.php target=top method=post class=form>
<select name=dbtable size=3 onchange=this.form.submit();>
  <option value=Leagues selected>The League Tables</option>
  <option value=Divisions>The Division Tables</option>
  <option value=Agegroups>The Agegroup Tables</option>
</select>
</form>
</div>
"; }
?>
<?php
if ($userlev == "guest") { echo "
<form name=login action=scripts/login.php target=top method=post class=form>
<div align=center>
Username : <input type=text size=14 maxlength=19 name=user>
Password : <input type=password size=14 maxlength=19 name=pass>
<input type=submit name=Login value=\"  Login  \" class=button>
</div>
</form>
"; }
?>
</td>
    <td width=20%>
<?php
if ($userlev == "user" | in_array("$userlev", $admins) ) { echo "
"; }
?>
<?php
if ($userlev == "guest") { echo "
<div align=center>
<img src=scripts/images/phpfootball_logo_small.gif>
</div>
"; }
?>
</td>
  </tr>
</table>
</body>
</html>
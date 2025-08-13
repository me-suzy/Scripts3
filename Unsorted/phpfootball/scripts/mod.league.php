<?php 
/*
***************************************************************************
Parameters :

***************************************************************************
*/
?>

<?php require("inc.layout.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>

<?php require("inc.auth.php"); ?>
<?php 
if ($userlev == "guest") {
	echo "<font color=red><b>You are not allowed to view this page</b></font>";
	die;
}
?>



<p>&nbsp;</p>

<table width="75%" class=tddd align="center">
  <tr> 
    <td colspan="4">League's Status</td>
  </tr>
<?php
$inctable = "Leagues";
$incwhat = "Rows,Data_length,Create_time,Update_time";
?>
<?php include("inc.status.php"); ?>
  <tr> 
    <td colspan="4">
<div align=center>
<form name="form" action="league.league.php" target="top" method="post" class="form">
<br>
<input type="submit" value="Create League" class="submit">
</form>
</div>
    </td>
  </tr>
</table>
<p>&nbsp;</p>


<table width="75%" class=tddd align="center">
  <tr> 
    <td colspan="4">Game's Status</td>
  </tr>
<?php
$inctable = "Games";
$incwhat = "Rows,Data_length,Create_time,Update_time";
?>
<?php include("inc.status.php"); ?>
  <tr> 
    <td colspan="4">
<div align=center>
<form name="form" action="league.game.php" target="top" method="post" class="form">
<br>
<input type="submit" value="Create Games" class="submit">
</form>
</div>
    </td>
  </tr>
</table>
<p>&nbsp;</p>


<table width="75%" class=tddd align="center">
  <tr> 
    <td colspan="4">Fixture's Status</td>
  </tr>
<?php
$inctable = "Fixtures";
$incwhat = "Rows,Data_length,Create_time,Update_time";
?>
<?php include("inc.status.php"); ?>
  <tr> 
    <td colspan="4">
<div align=center>
<form name="form" action="league.fixture.php" target="top" method="post" class="form">
Number of fixtures : <input type=text size=2 maxlength=2 name=Howmany value=1>
<br>
<input type="submit" value="Create Fixtures" class="submit">
</form>
</div>
    </td>
  </tr>
</table>
<p>&nbsp;</p>


<table width="75%" class=tddd align="center">
  <tr> 
    <td colspan="4">Team's Status</td>
  </tr>
<?php
$inctable = "Teams";
$incwhat = "Rows,Data_length,Create_time,Update_time";
?>
<?php include("inc.status.php"); ?>
  <tr> 
    <td colspan="4">
<div align=center>
<form name="form" action="league.team.php" target="top" method="post" class="form">
<br>
<input type="submit" value="Create Teams" class="submit">
</form>
</div>
    </td>
  </tr>
</table>
<p>&nbsp;</p>


<table width="75%" class=tddd align="center">
  <tr> 
    <td colspan="4">Venue's Status</td>
  </tr>
<?php
$inctable = "Venues";
$incwhat = "Rows,Data_length,Create_time,Update_time";
?>
<?php include("inc.status.php"); ?>
  <tr> 
    <td colspan="4">
<div align=center>
<form name="form" action="league.venue.php" target="top" method="post" class="form">
<br>
<input type="submit" value="Create Venues" class="submit">
</form>
</div>
    </td>
  </tr>
</table>
<p>&nbsp;</p>


<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
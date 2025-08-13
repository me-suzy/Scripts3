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
<table class=tddd width="75%" align="center">
  <tr> 
    <td colspan="4">news Statistics</td>
  </tr>
<?php
$inctable = "News";
$incwhat = "Rows,Data_length,Create_time,Update_time";
?>
<?php include("inc.status.php"); ?>
  <tr> 
    <td colspan="4" align=center>

<form name="form" class=form action="news.editor.php" target="top" method="post">

<table align=center>
<tr> 
<td width=100 class=tddd>Action</td>
<td width=100 class=tddd>Headline</td>
</tr>

<tr>
<td width=100>
<select name="action" size="2">
  <option value="create" selected>Create News</option>
  <option value="edit">Edit News</option>
</select>
</td>
<td width=100>
<input class=input type=text size=15 maxlength=50 name=Headline>
<?php
$reqadm = "1";
$incsel = "rows";
$inctable = "News";
$incfield = "Id";
$incshow = "Headline";
$incvar = "Id";
?>
<?php require("inc.select.php"); ?>
</td>
</tr>
</table>

<input type="submit" value="Continue" class="submit">
</form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
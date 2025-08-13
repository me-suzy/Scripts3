<?
##########################################################################
## BACKUP.PHP
##########################################################################

require("admheader.php");
?>
<!-- Table menu -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td bgcolor="lightgrey">
 &nbsp; Licene rules 
</td>
</tr>

<tr bgcolor="white">
<td>

<a href="?check_l=1">Check if valid licence</a><p>

<?

if ($check_l)
{

	$WebFile = "http://www.deltascripts.com/licence_server.php?licence_check=1&li=$li";
	$handle = fopen ($WebFile, "r");
	while (!feof($handle)) 
	{
     		$buffer=fread($handle,4096);
     		print "$buffer";
	}
}
?>
</td></tr><tr><td>

<?
if (!$check_l)
{
?>
<textarea rows="40" name="S1" cols="90">
<? include("setup/licence.txt");  ?>
</textarea>
<?
}
?>
</td>
</tr>

</table>
<?
include("admfooter.php");
?>

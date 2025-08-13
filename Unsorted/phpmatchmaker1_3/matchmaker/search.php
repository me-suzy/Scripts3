<?
if (!$inc)
{
require_once("php_inc.php"); 
session_start();
include("header_inc.php");
do_html_heading("Search");
db_connect();
if (session_is_registered("valid_user"))
{
	member_menu();
}
}
?>
<form method=post action=search_result.php>
<table width=100%>
<tr>
<td>From here you can do a simple search against the database. Select your searchoptions below:</td>
</tr>

<tr><td bgcolor='#E8E8EE'><b><? echo $la_gender ?></b></td></tr><tr>
<?
	$options = file("optionfiles/sex.txt");
	$num_options =  count($options);
				 
	print "<td bgcolor='#E8E8EE'>";
	for ($i=0; $i<$num_options; $i++)
	{
	 	//print $options[$i];
		print "<input type='radio' value='$options[$i]' name='sex_s'>";
		print $options[$i];
		print "<br>";
	}

?>		
</td></tr>
<tr><td bgcolor='#E8E8EE'><b><? echo $la_place ?></b></td></tr><tr>
<td bgcolor='#E8E8EE'>
	<?
	$options = file("optionfiles/place.txt");
	$num_options =  count($options);
	print "<select size='1' name='place_s'>$la_place";
	for ($i=0; $i<$num_options; $i++)
	{			 	 
		print "<option value='$options[$i]'>$options[$i]</option>";
	}
	print "<option>--------------------------</option></select>&nbsp;&nbsp;&nbsp;";
	?>
</td>
</tr>

<tr><td bgcolor='#E8E8EE'><b><? echo $la_age ?></b></td></tr><tr>
<td bgcolor='#E8E8EE'>
Between <input type='text' name='age_from' value='16' size='3'> and <input type='text' name='age_to' value='100' size='3'> years.<br>
Show only those with picture ? <input type=checkbox name=pic value=1>
</td>
</tr>

<tr>
<td bgcolor='#E8E8EE'>
<button type="submit" name="search" value="Search">Search</button>
</td>
</tr>

</table>
<?
print "</form>";
print "<p>";
if (!$inc)
{
include("footer_inc.php");
}
?>
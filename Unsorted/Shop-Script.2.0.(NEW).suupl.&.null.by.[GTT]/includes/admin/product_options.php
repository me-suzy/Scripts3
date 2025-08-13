<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/



	//ADMIN :: product options

?>

<a href="admin.php"><u><?=ADMIN_MAIN_MENU;?></u></a> : <u><font><?=ADMIN_PRODUCT_OPTIONS;?></font></u> :<br><br>

<?
	if (isset($succ)) //update was successful
	{
		echo ADMIN_UPDATE_SUCCESSFUL;
	}
?>

<form action="admin.php" method=POST>

<table cellpadding=4>
<?
	//select all available product options
	$q = db_query("select optionID, name from ".PRODUCT_OPTIONS_TABLE) or die (db_error());
	$result = array();
	while ($row = db_fetch_row($q))
		$result[] = $row;

	//no extra options available
	if (count($result) == 0)
	{
		echo "<tr><td colspan=2><nobr>".ADMIN_NO_PRODUCT_OPTIONS."</nobr></td></tr>";
	}
	else
	{
		for ($i=0; $i<count($result);$i++)
		{
			echo "<tr><td><input type=text name=\"extra_option_".$result[$i][0]."\" value=\"".trim($result[$i][1])."\"></td>
				<td><a href=\"admin.php?kill_option=".$result[$i][0]."\"><img src=\"images/remove.jpg\" border=0 alt=\"".DELETE_BUTTON."\"></a></td></tr>";
		}
	}

?>
<tr><td colspan=2><?=ADMIN_ADD_NEW_OPTION;?></td>

<tr>
<td><input type=text name=add_option value=""></td>
<td>&nbsp;</td>
</tr>

</table>

<input type=submit name="save_options" value="<?=SAVE_BUTTON;?>">

</form>
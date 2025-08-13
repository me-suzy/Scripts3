<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/



	//ADMIN :: shipping and payment types

	if (isset($killS)) //delete shipping type
	{
		db_query("delete from ".SHIPPING_METHODS_TABLE." where SID='$killS'") or die (db_error());
	}

	if (isset($killP)) //delete payment type
	{
		db_query("delete from ".PAYMENT_TYPES_TABLE." where PID='$killP'") or die (db_error());
	}

?>

<a href="admin.php"><u><?=ADMIN_MAIN_MENU;?></u></a> : <u><font><?=ADMIN_SHIPPING_PAYMENT;?></font></u> :<br><br>

<form action="admin.php" method=post>


<? # --------------------------- # SHIPPING # ---------------------------------- # ?>

<table cellpadding=5 cellspacing=0 width=70%>

<tr>
<td colspan=6 bgcolor=#CCCCCC align=center>
<font color=#333333><b><?=STRING_SHIPPING_TYPE;?></b></font>
</td>
</tr>

<tr bgcolor=#DDDDDD align=center>
<td>&nbsp;</td>
<td><?=STRING_NAME;?></td>
<td><?=STRING_DESCRIPTION;?></td>
<td><?=STRING_SHIPPING_PERCENT;?></td>
<td><?=STRING_SHIPPING_LUMPSUM;?>, <?=STRING_UNIVERSAL_CURRENCY;?></td>
<td>&nbsp;</td>
</tr>

<?
	$q = db_query("select SID, Name, description, percent_value, lump_sum, Enabled from ".SHIPPING_METHODS_TABLE) or die (db_error());
	$n = 0;
	while ($row = db_fetch_row($q))
	{
		$check = $row[5] ? "checked" : "";

		echo "

<tr>
	<td><input type=checkbox name=ship_en_$n $check></td>
	<td><input type=text name=ship_name_$n value=\"".trim($row[1])."\"></td>
	<td><input type=text name=ship_desc_$n value=\"".trim($row[2])."\"></td>
	<td><input type=text name=ship_perc_$n value=\"$row[3]\"></td>
	<td><input type=text name=ship_lump_$n value=\"$row[4]\"></td>
	<td><a href=\"admin.php?path=shipping_payment&killS=$row[0]\"><img src=\"images/remove.jpg\" border=0 alt=\"".DELETE_BUTTON."\"></a></td>
</tr>

		";

		$n++;

	}
	if (!$n) echo "<tr><td colspan=6 align=center>".STRING_EMPTY_LIST."</td></tr>";
?>

<tr>
	<td colspan=6 align=center><?=ADD_BUTTON; ?>:</td>
</tr>

<tr>
	<td><input type=checkbox name=new_shipen checked></td>
	<td><input type=text name=new_shipname></td>
	<td><input type=text name=new_shipdesc></td>
	<td><input type=text name=new_shipperc></td>
	<td><input type=text name=new_shiplump></td>
	<td>&nbsp;</td>
</tr>


</table>







<br><hr width=80% size=1><br>





<? # --------------------------- # PAYMENT # ---------------------------------- # ?>

<table cellpadding=5 cellspacing=0 width=70%>

<tr>
<td colspan=5 bgcolor=#CCCCCC align=center>
<font color=#333333><b><?=STRING_PAYMENT_TYPE;?></b></font>
</td>
</tr>

<tr bgcolor=#DDDDDD align=center>
<td>&nbsp;</td>
<td><?=STRING_NAME;?></td>
<td><?=STRING_DESCRIPTION;?></td>
<td><?=STRING_PAYMENT_CALCTAX;?></td>
<td>&nbsp;</td>
</tr>

<?
	$q = db_query("select PID, Name, description, calculate_tax, Enabled from ".PAYMENT_TYPES_TABLE) or die (db_error());
	$n = 0;
	while ($row = db_fetch_row($q))
	{
		$check1 = $row[4] ? "checked" : "";
		$check2 = $row[3] ? "checked" : "";

		echo "

<tr>
	<td><input type=checkbox name=pay_en_$n $check1></td>
	<td><input type=text name=pay_name_$n value=\"".trim($row[1])."\"></td>
	<td><input type=text name=pay_desc_$n value=\"".trim($row[2])."\"></td>
	<td align=center><input type=checkbox name=pay_tax_$n $check2></td>
	<td><a href=\"admin.php?path=shipping_payment&killP=$row[0]\"><img src=\"images/remove.jpg\" border=0 alt=\"".DELETE_BUTTON."\"></a></td>
</tr>

		";

		$n++;

	}
	if (!$n) echo "<tr><td colspan=5 align=center>".STRING_EMPTY_LIST."</td></tr>";
?>

<tr>
	<td colspan=5 align=center><?=ADD_BUTTON; ?>:</td>
</tr>

<tr>
	<td><input type=checkbox name=new_payen checked></td>
	<td><input type=text name=new_payname></td>
	<td><input type=text name=new_paydesc></td>
	<td align=center><input type=checkbox name=new_paytax></td>
	<td>&nbsp;</td>
</tr>


</table>



<br><br>


<input type=hidden name=path value=shipping_payment>
<input type=submit name=save_SP value="<?=SAVE_BUTTON;?>">

</form>
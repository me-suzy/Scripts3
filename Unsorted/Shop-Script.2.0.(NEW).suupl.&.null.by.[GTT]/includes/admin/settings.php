<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	//ADMIN::system settings

	include("./cfg/settings.inc.php");

?>

<a href="admin.php"><u><?=ADMIN_MAIN_MENU;?></u></a> : <u><font><?=ADMIN_SETTINGS;?></font></u> :

<form action="admin.php?path=settings" method=post>
<table>

<tr>
<td colspan=3 bgcolor=#CCCCCC>
<font color=#333333><?=ADMIN_SETTINGS;?></font>
</td>
</tr>

<tr>
<td align=right><?=STRING_TAX;?>:</td>
<td width=10>&nbsp;</td>
<td><input type=text name=tax_ value="<?=$tax; ?>">%</td>
</tr>

<tr>
<td align=right><?=ADMIN_SHOP_NAME;?>:</td>
<td width=10>&nbsp;</td>
<td><input type=text name=shop_name value="<?=str_replace("\"","&quot;",$shopname); ?>"></td>
</tr>

<tr>
<td align=right><?=ADMIN_SHOP_URL;?>:</td>
<td width=10>&nbsp;</td>
<td><input type=text name=shop_url value="<?=$shopurl; ?>"></td>
</tr>



<tr><td colspan=3><br><br></td></tr>

<tr>
<td colspan=3 bgcolor=#CCCCCC>
<font color=#333333><?=ADMIN_DESKTOP;?></font>
</td>
</tr>

<tr>
<td align=right><?=ADMIN_MAX_PRODUCTS_COUNT_PER_PAGE;?>:</td>
<td width=10>&nbsp;</td>
<td><input type=text name=productscount value="<?=$products_count; ?>"></td>
</tr>

<tr>
<td align=right><?=ADMIN_MAX_COLUMNS_PER_PAGE;?>:</td>
<td width=10>&nbsp;</td>
<td><input type=text name=colscount value="<?=$cols_count; ?>"></td>
</tr>


<tr><td colspan=3 align=center><br>
<?=ADMIN_MAIN_COLORS;?>
</td></tr>

<tr>
<td align=right>

<table><tr>
<td>
<table bgcolor=black cellspacing=1><tr><td bgcolor=#<?=$dark_color; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table>
</td>
<td>
<?=ADMIN_COLOR;?> 1:
</td>
</tr></table>

</td>
<td width=10><b>#</b></td>
<td><input type=text name=darkcolor value="<?=$dark_color; ?>"></td>
</tr>

<tr>
<td align=right>

<table><tr>
<td>
<table bgcolor=black cellspacing=1><tr><td bgcolor=#<?=$middle_color; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table>
</td>
<td>
<?=ADMIN_COLOR;?> 2:
</td>
</tr></table>

</td>
<td width=10><b>#</b></td>
<td><input type=text name=middlecolor value="<?=$middle_color; ?>"></td>
</tr>

<tr>
<td align=right>

<table><tr>
<td>
<table bgcolor=black cellspacing=1><tr><td bgcolor=#<?=$light_color; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table>
</td>
<td>
<?=ADMIN_COLOR;?> 3:
</td>
</tr></table>

</td>
<td width=10><b>#</b></td>
<td><input type=text name=lightcolor value="<?=$light_color; ?>"></td>
</tr>

<tr><td colspan=3><br><br></td></tr>

<tr>
<td colspan=3 bgcolor=#CCCCCC>
<font color=#333333><?=ADMIN_CURRENCY_TYPES;?></font>
</td>
</tr>

<tr><td colspan=3>
<?=ADMIN_CURRENCIES_DESCRIPTION;?>
</td></tr>

<?

	for ($i=0; $i<count($currency_name); $i++) //show all currency types
	{
		echo "<tr>";
		echo "<td valign=top><input type=text name=cn_$i value=\"$currency_name[$i]\"></td>";
		echo "<td>&nbsp;</td>";
		echo "<td>";
		//currency value
		echo "<input type=text name=cv_$i value=$currency_value[$i]><br>";

		//show currency sign before or after number
		if ($currency_where[$i])
		{
			$b = "";
			$a = " selected";
		}
		else
		{
			$a = "";
			$b = " selected";
		}
		echo STRING_SHOW."
			<select name=wh_$i>
				<option value=0 $b>".STRING_BEFORE."</option>
				<option value=1 $a>".STRING_AFTER."</option>
			</select> ".STRING_NUMBER;
		echo "</td>";
		echo "</tr>";
	};
?>

<tr><td colspan=3 align=center><?=ADD_BUTTON;?>:</td></tr>

<tr>
<td valign=top><input type=text name=new_cn></td>
<td>&nbsp;</td>
<td>
<input type=text name=new_cv><br>
<?
		echo STRING_SHOW."
			<select name=new_wh>
				<option value=0>".STRING_BEFORE."</option>
				<option value=1>".STRING_AFTER."</option>
			</select> ".STRING_NUMBER;
?>
</td>
</tr>


<tr><td colspan=3><br><br></td></tr>

<tr>
<td colspan=3 bgcolor=#CCCCCC>
<font color=#333333>ÐÅÊÂÈÇÈÒÛ ÏÐÈÅÄÏÐÈßÒÈß</font>
</td>
</tr>

<tr>
<td align=right>ÈÍÍ:</td>
<td width=10>&nbsp;</td>
<td><input type=text name=inn_ value="<?=$inn; ?>">%</td>
</tr>

<tr>
<td align=right>ÊÏÏ:</td>
<td width=10>&nbsp;</td>
<td><input type=text name=kpp_ value="<?=$kpp; ?>"></td>
</tr>

<tr>
<td align=right>Íàèìåíîâàíèå ïîëó÷àòåëÿ:</td>
<td width=10>&nbsp;</td>
<td><input type=text name=reciever_ value="<?=$reciever; ?>"></td>
</tr>

<tr>
<td align=right>Ð/ñ÷åò:</td>
<td width=10>&nbsp;</td>
<td><input type=text name=rs_ value="<?=$rs; ?>"></td>
</tr>

<tr>
<td align=right>Ê/ñ÷åò:</td>
<td width=10>&nbsp;</td>
<td><input type=text name=ks_ value="<?=$ks; ?>"></td>
</tr>

<tr>
<td align=right>ÁÈÊ:</td>
<td width=10>&nbsp;</td>
<td><input type=text name=bik_ value="<?=$bik; ?>"></td>
</tr>

</table>



<p>Âàëþòà, â êîòîðûé âûïèñûâàòü ñ÷åòà:<br>
<select name="invoice_currency_">
<? //$invoice_currency = 0;
	for ($i=0; $i<count($currency_name); $i++) //show all currency types
	{
		echo "<option value=\"$i\"";
		if ($invoice_currency == $i) echo " selected";
		echo ">$currency_name[$i]</option>";

	};
?>
</select>

<p>

<input type=submit value="<?=SAVE_BUTTON;?>">
<input type=hidden name=sys_save value=1>
</form>
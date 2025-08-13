<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/
 
 
 //ADMIN :: customers managment

?>


<a href="admin.php"><u><?=ADMIN_MAIN_MENU;?></u></a> : <u><font><?=ADMIN_CUSTOMERS;?></font></u> :<br><br>


<?

	if (isset($export))
	{
		$f = fopen("customers.csv","w");
		fputs($f,"Login;Password;Email;First name;Last name;Country;ZIP;State;City;Address;Phone number;\n");
		$q = db_query("SELECT Login, cust_password, Email, first_name, last_name, Country, ZIP, State, City, Address, Phone FROM ".CUSTOMERS_TABLE." ORDER BY Login") or die (db_error());
		while ($row = db_fetch_row($q))
		{
			for ($i=0;$i<11;$i++)
			{
				$row[$i] = str_replace("\n"," ",$row[$i]);
				$row[$i] = str_replace("\"","'",$row[$i]);
				$row[$i] = str_replace(";",",",$row[$i]);
				fputs($f,$row[$i].";");
			}

			fputs($f,"\n");
		}
		fclose($f);
	}

	if (isset($spam)) //show news subscribers
	{

		if (isset($kill)) //delete from MAILING_LIST_TABLE
			db_query("DELETE FROM ".MAILING_LIST_TABLE." WHERE MID=$kill") or die (db_error());

		if (isset($unsub)) //unsubscribe registered user
		{
			db_query("UPDATE ".CUSTOMERS_TABLE." SET subscribed4news=0 WHERE Login='".base64_decode($unsub)."';") or die (db_error());
		}

		$q = db_query("SELECT count(*) FROM ".MAILING_LIST_TABLE."") or die (db_error());
		$cnt = db_fetch_row($q); $cnt = $cnt[0];
		if ($cnt==0) echo "<tr bgcolor=white><td align=center>".ANSWER_NO."</td></tr>";
		else
		{
			$q = db_query("SELECT Email, MID FROM ".MAILING_LIST_TABLE) or die (db_error());
			echo "<table bgcolor=#999999 cellspacing=1 cellpadding=1 width=40%><tr bgcolor=#DDDDDD><td align=center colspan=2>".ADMIN_NEWS_SUBSCRIBERS."</td></tr>";
			while ($row = db_fetch_row($q))
			{
				echo "<tr bgcolor=white><td>&nbsp;<a class=standard href=\"mailto:$row[0]\">$row[0]</a></td><td width=1%><a href=\"admin.php?path=customers&spam=1&kill=$row[1]\"><img src=\"images/remove.jpg\" border=0></a></td></tr>\n";
			}
			$q = db_query("SELECT Email, Login FROM ".CUSTOMERS_TABLE." WHERE subscribed4news=1") or die (db_error());
			while ($row = db_fetch_row($q))
			{
				echo "<tr bgcolor=white><td>&nbsp;<a class=standard href=\"mailto:$row[0]\">$row[0]</a></td><td width=1%><a href=\"admin.php?path=customers&spam=1&unsub=".base64_encode($row[1])."\"><img src=\"images/remove.jpg\" border=0></a></td></tr>\n";
			}
			echo "</table>";
		}
?>

<br>[ <a href="admin.php?path=customers"><?=ADMIN_CUSTOMERS;?></a> | <font><?=ADMIN_NEWS_SUBSCRIBERS;?></font> ]</body></html>

<p>
<table width=100% border=0 height=40 border=0>
<tr><td align=center>
<a href="index.php"><?=ADMIN_BACK_TO_SHOP;?></a>
</td></tr>
</table>
</p>

<?
		exit;

	}




	if (isset($rem)) //delete order from shopping history
	{
		$q = db_query("SELECT customer_login FROM ".ORDERS_TABLE." WHERE orderID=$rem") or die (db_error());
		$temp = db_fetch_row($q);
		$history = $temp[0];
		db_query("DELETE FROM ".ORDERS_TABLE." WHERE orderID=$rem") or die (db_error());
		db_query("DELETE FROM ".ORDERED_CARTS_TABLE." WHERE orderID=$rem") or die (db_error());
	}


	echo "<table cellpadding=10 cellspacing=1 bgcolor=#999999>";


if (isset($history)) //show shopping history
{

?>

<tr><td bgcolor=#F6F6F6>
<b><u><?=stripslashes($history); ?></u> :: <?=STRING_SHOPPING_HISTORY;?></b><br><br>

<?

	$q = db_query("SELECT count(*) FROM ".ORDERS_TABLE." WHERE customer_login='$history' AND Done=1") or die (db_error());
	$cnt = db_fetch_row($q); $cnt = $cnt[0];
	if ($cnt == 0){
		echo "&nbsp;&nbsp;&nbsp;&nbsp;".STRING_NO_ORDERS;
	}
	else
	{


		$q = db_query("SELECT order_time, payment_type, shipping_type, shipping_cost, customers_comment, orderID, calculate_tax, tax FROM ".ORDERS_TABLE." WHERE customer_login='$history' AND Done=1 ORDER BY order_time") or die (db_error());

		$sum = 0;

		echo "<table border=0 cellspacing=1 cellpadding=4 bgcolor=#AAAAAA width=97%>";
		echo "	
			<tr bgcolor=#CCCCCC align=center><td><b>".STRING_ORDER_ID."</b></td>
			<td><b>".TABLE_ORDER_TIME."</b></td>
			<td><b>".STRING_PAYMENT_TYPE."</b></td><td><b>".STRING_SHIPPING_TYPE."</b></td>
			<td width=100%><b>".TABLE_ORDERED_PRODUCTS."</b></td>
			<td><b>".TABLE_ORDER_COMMENTS."</b></td>
			<td><b>".TABLE_ORDER_TOTAL.", ".STRING_UNIVERSAL_CURRENCY."</b></td><td>&nbsp;</td></tr>
		";
		while ($row = db_fetch_row($q))
		{
			$prs = "";
			$total = 0;
			$q1 = db_query("SELECT name, Price, Quantity FROM ".ORDERED_CARTS_TABLE." WHERE orderID=".$row[5]) or die(db_error());
			while ($it = db_fetch_row($q1))
			{
				$prs .= "$it[0] x $it[2]: ".($it[1]*$it[2])."<br>";
				$total += $it[1]*$it[2];
			}
			if ($row[6]) //+tax if required
				$total *= (100 + $row[7]) / 100;
			$total += $row[3]; //+shipping cost

			echo "<tr bgcolor=white>";
			echo "<td>$row[5]</td>";
			echo "<td><nobr>$row[0]</nobr></td>";
			echo "<td>$row[1]</td>";
			echo "<td>$row[2]</td>";
			echo "<td>$prs</td>";
			echo "<td>$row[4]</td>";
			echo "<td align=center>".(round(100*$total)/100);
				if ($row[6] && $row[7]) echo "<br>(".STRING_TAX.": $row[7]%)";
			echo "</td>";
			echo "<td><a href=\"admin.php?path=customers&rem=$row[5]\"><img src=\"images/remove.jpg\" border=0 alt=\"".DELETE_BUTTON."\"></a></td>";
			$sum += $total;
			echo "</tr>";
		}
		$sum = round(100*$sum)/100;
		echo "<tr bgcolor=#CCCCCC><td colspan=6><b>".TABLE_TOTAL."</b></td><td><nobr><font class=cat><b><u>$sum</u></b></font></nobr></td><td>&nbsp;</td></tr>";
		echo "</table>";


	}
	echo "</td></tr>";
}
?>


<tr>
<td bgcolor=#DDDDDD>
<?

	if (!isset($letter)) $letter="A";
	if (!$letter) echo ADMIN_ALL_USERS."<br><br>";
	else echo ADMIN_USERS_WITH_LOGIN_ON." <b>".$letter.":</b><br><br>";

	$q = db_query("SELECT Login FROM ".CUSTOMERS_TABLE." WHERE Login LIKE '$letter%' ORDER BY Login") or die (db_error());
	$i=0;
	echo "<table>";
	while ($row = db_fetch_row($q)) {
		echo "<tr><td><a href=\"javascript:open_window('user.php?uLogin=".str_replace("\"","&quot;",addslashes($row[0]))."',270,350);\">";
		echo str_replace("<","&lt;",str_replace("\"","&quot;",$row[0]))."</a></td>\n";
		echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href=\"admin.php?path=customers&history=".str_replace("<","&lt;",str_replace("\"","&quot;",$row[0]))."
			&letter=$letter\"><img src=\"images/hist.gif\" border=0 alt=\"".STRING_SHOPPING_HISTORY."\">
			</a></td></tr>\n";
		$i++;
	};
	if ($i==0) echo "<tr><td>&lt;".ANSWER_NO."></td></tr>";
	echo "</table>";
	echo "<br><center>".CUSTOMER_LOGIN."<br>[ ";

	//symbol navigator
	for ($j="A", $i=0; $i<26; $j++, $i++) 
	  if ($j != $letter) echo "<a href=\"admin.php?letter=".$j."&path=customers\" class=\"small\">".$j."</a> |\n";
	  else echo $j." |\n";
	echo "<a href=\"admin.php?path=customers&letter=\">".STRING_ALL."</a> ]</center>\n";


?>

</td>
</tr>
</table>
<br>

[ <font><?=ADMIN_CUSTOMERS;?></font> | <a href="admin.php?path=customers&spam=1"><?=ADMIN_NEWS_SUBSCRIBERS;?></a> ]

<?
	if (!isset($export)) {
?>
<form action="admin.php?path=customers&export=true" method=post>
<input type=submit value="<?=ADMIN_EXPORT_USER_TO_FILE;?>">
</form>
<?
	} else {
?>
<p><font><?=ADMIN_USERS_EXPORTED_TO;?> <a href="customers.csv">customers.csv</a></font><br><br>
<?
	};
?>

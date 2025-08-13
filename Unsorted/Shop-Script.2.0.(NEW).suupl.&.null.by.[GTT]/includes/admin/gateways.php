<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/



	//ADMIN :: payment gateways


?>

<a href="admin.php"><u><?=ADMIN_MAIN_MENU;?></u></a> : <u><font><?=ADMIN_CC_PROCESSING;?></font></u> :<br><br>



<form action="admin.php" method=post>
<p>
<?=ADMIN_CHOOSE_CREDIT_CARD_TYPE; ?>
<table>
<?
	include("./cfg/cc.inc.php");

	//show available payment types
	$q = db_query("select count(*) from ".PAYMENT_TYPES_TABLE." where PID=$cc_payment_type") or die (db_error());
	$row = db_fetch_row($q);
	if ($row[0] || $cc_payment_type==-1) //found cc type
		$n = $cc_payment_type;
	else
		$n = "";


echo "<tr><td><input type=radio name=cc_type value=\"-1\"";
if ($n == -1) echo " checked";
echo "></td><td>".ANSWER_NO."</td></tr>";

	$q = db_query("select PID, Name from ".PAYMENT_TYPES_TABLE) or die (db_error());
	while ($row = db_fetch_row($q))
	{
		echo "
		<tr>
			<td><input type=radio name=cc_type value=$row[0]";

		//checked or not...
		if ($n == "") { echo " checked"; $n = "done"; }
		else if ($n == $row[0]) echo " checked";

		echo "></td>
			<td> $row[1]</td>
		</tr>
		";
	}
?>
</table>

<hr width=50% size=1>

<table cellpadding=4>
<tr>
	<td><?=ADMIN_CONNECTION_TYPE;?></td>
	<td>
		<select name=conn_type>
		<option value="http"<?  if($conn_type == "http")  echo " selected";?>>HTTP</option>
		<option value="https"<? if($conn_type == "https") echo " selected";?>>HTTPS</option>
		</select>
	</td>
</tr>
</table>

<hr width=50% size=1>

<TABLE CELLPADDING=15 BGCOLOR=BLACK CELLSPACING=1>
<TR>
<TD BGCOLOR=WHITE>

	<p>
	<u><?=ADMIN_CHOOSE_GATEWAY;?>:</u>
	<table>

	<tr>
		<td><input type=radio name=gateway value="2checkout"<?

			if ($payment_gateway == "2checkout") echo " checked";
			
			
		?>></td>
		<td><img src="images/2checkout.gif"></td>
		<td><input type=button value="<?=CONFIG_BUTTON;?>" onClick="open_window('gateway.php?gate=2checkout',450,350);"></td>
	</tr>

	<tr>
		<td><input type=radio name=gateway value="authorizenet"<?

			if ($payment_gateway == "authorizenet") echo " checked";
			
			
		?>></td>
		<td><img src="images/authorizenet.gif"></td>
		<td><input type=button value="<?=CONFIG_BUTTON;?>" onClick="open_window('gateway.php?gate=authorizenet',450,350);"></td>
	</tr>

	</table>

</TD>
</TR>
</TABLE>

<P>
<input type=submit name="save_gateways" value="<?=SAVE_BUTTON;?>">

</form>

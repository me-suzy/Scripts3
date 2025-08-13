<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	//continue ordering process...


	//define is it required to go to 'https://' if credit card choosed as payment type
	include("./cfg/cc.inc.php");
	if (!isset($skip_ct) && $proceed_ordering == 2 && $payment_type == $cc_payment_type)
	{
		$url = $shopurl;
		$url = str_replace("http://","",$url);
		$url = str_replace("https://","",$url);
		$url = str_replace("index.php","",$url);
		$url = $conn_type."://".$url;
		//echo "$url/index.php?proceed_ordering=2&payment_type=$payment_type&shipping_type=$shipping_type";
		header("Location: $url/index.php?proceed_ordering=2&payment_type=$payment_type&shipping_type=$shipping_type&skip_ct=1");
	}

	//is shopping cart empty?
	$q = db_query("select count(*) from ".SHOPPING_CARTS_TABLE." where customer_login='$log'") or die (db_error());
	$row = db_fetch_row($q);
	if (!$row[0])
	{
		$out .= "<br><br><br><center><b>".CART_EMPTY."</b></center>";
	}
	else
	switch ($proceed_ordering)
	{
		case 1: // choose shipping and payment types

			$p = "";
			$s = "";

			//get payment types
			$n = 0;
			$q = db_query("select PID, Name, description from ".PAYMENT_TYPES_TABLE." where Enabled=1") or die (db_error());
			while ($row = db_fetch_row($q))
			{
				$p .= "<p>
						<table cellspacing=0 cellpadding=0>
						<tr>
							<td rowspan=2 valign=top>
								<input type=radio name=payment_type value=$row[0] checked>
							</td>
							<td><b>$row[1]</b><br></td>
						</tr>
						<tr><td>$row[2]</td></tr>
						</table>
					  ";
				$n++;
			}
			if (!$n) //no available types
			{
				$p.= "<input type=radio name=payment_type value=0 checked> ".STRING_DEFAULT;
			}


			//shipping
			$n = 0;
			$q = db_query("select SID, Name, description, percent_value, lump_sum from ".SHIPPING_METHODS_TABLE." where Enabled=1") or die (db_error());
			while ($row = db_fetch_row($q))
			{
				$s .= "<p>
						<table cellspacing=0 cellpadding=0>
						<tr>
							<td rowspan=2 valign=top>
								<input type=radio name=shipping_type value=$row[0] checked>
							</td>
							<td><b>$row[1]</b><br></td>
						</tr>
						<tr><td>$row[2]<br>($row[3]%, ".show_price($row[4])." min)</td></tr>
						</table>
					  ";
				$n++;
			}
			if (!$n) //no available types
			{
				$s .= "<input type=radio name=shipping_type value=0 checked> ".STRING_DEFAULT;
			}

			$out .= "

<center>
<form action=\"index.php\" method=POST>

<font color=red class=cat><b><u>".STRING_CHOOSE_PAYMENT_SHIPPING_TYPES."</u></b></font>

<p>
<table border=0 width=70% cellspacing=1 cellpadding=5 bgcolor=#$middle_color>

  <tr bgcolor=#$light_color>
	<td width=50%>
		<u>".STRING_PAYMENT_TYPE.":</u>
	</td>
	<td width=50%>
		<u>".STRING_SHIPPING_TYPE.":</u>
	</td>
  </tr>

  <tr bgcolor=white>
	<td>
		$p
	</td>
	<td>
		$s
	</td>
  </tr>
</table>

<p>

<table>
  <tr>
	<td>
		".STRING_CUSTOMER_COMMENTS.":
	</td>
	<td>
		<textarea name=comment cols=40 rows=7></textarea>
	</td>
  </tr>

</table>

<input type=hidden name=proceed_ordering value=2>
<br>
<input type=submit value=\"".CART_PROCEED_TO_CHECKOUT."\">

</form>
</center>

			";

		break;


		case 2: //order confirmation

			if (!isset($payment_type)) exit;
			if (!isset($shipping_type)) exit;
			if (!isset($comment)) $comment = "";

			//ordered products

			$q = db_query("SELECT productID, Quantity FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log'") or die (db_error());
			$i=0;
			$result = array();
			while ($row = db_fetch_row($q)) $result[$i++] = $row;

			$s=0; //total cart value
			$order = "";
			for ($i=0; $i<count($result); $i++)
			{
				$q = db_query("SELECT name, Price FROM ".PRODUCTS_TABLE." WHERE productID=".$result[$i][0]) or die (db_error());
				if ($r = db_fetch_row($q))
				{
					$order .= "$r[0] (x".$result[$i][1]."): ".show_price($result[$i][1]*$r[1])."<br>";

					$s += $result[$i][1]*$r[1];
				}
			}


			//create text
			$q = db_query("select Name, calculate_tax from ".PAYMENT_TYPES_TABLE." where PID=$payment_type") or die (db_error());
			$r = db_fetch_row($q);
			if (!$r) //payment type wasn't found in the database
			{
				$r[0] = STRING_DEFAULT;
				$r[1] = 1;
			}
			$order.= "<p>".STRING_PAYMENT_TYPE.": ".$r[0];

			$old_s = $s;
			if ($r[1]) //calculate tax
			{
				$order.= "<p>".STRING_TAX.": ".show_price($tax*$s/100);
				$s *= (100+$tax)/100.0;
			}

			$q = db_query("select Name, percent_value, lump_sum from ".SHIPPING_METHODS_TABLE." where SID=$shipping_type") or die (db_error());
			$r = db_fetch_row($q);
			if (!$r) //shipping type wasn't found in the database
			{
				$r[0] = STRING_DEFAULT;
				$r[1] = 0;
				$r[2] = 0;
			}
			$order.= "<p>".STRING_SHIPPING_TYPE.": $r[0]";

			//calculate shipping cost
			//calculate cost of shipping in case of percents
			$a = $old_s*$r[1]/100;
			$sh = max($a, $r[2]);
			$order.= " (".show_price($sh).")";
			$s += $sh;

			$s = show_price($s);

			//customer's details
			$q = db_query("SELECT first_name, Country, City, Address, Phone, last_name, ZIP, State FROM ".CUSTOMERS_TABLE." WHERE Login='$log'") or die (db_error());
			if (!($row = db_fetch_row($q))) exit;
			$client = "<p>".CUSTOMER_FIRST_NAME." <b>$row[0]</b><br>";
			$client.= CUSTOMER_LAST_NAME." <b>$row[5]</b>";
			$client.= "<p>".CUSTOMER_ADDRESS."<br><textarea name=final_address rows=3 cols=25>".trim($row[1]).", ".trim($row[6]).", ".trim($row[7]).", ".trim($row[2]).", ".trim($row[3])."</textarea>";
			$client.= "<p>".CUSTOMER_PHONE_NUMBER."<br><b>$row[4]</b>";


			$out .= "

<center>
<form action=\"index.php\" method=POST onSubmit=\"document.order_form.po.enabled=false;\" name=order_form>

<font color=red class=cat><b><u>".STRING_ORDER_CONFIRMATION."</u></b></font>

<p>
<table cellpadding=5 cellspacing=1 bgcolor=#$dark_color width=70%>

  <tr bgcolor=#$light_color>
	<td width=50% align=center>
		<b>".STRING_YOUR_ORDER."</b>
	</td>
	<td width=50% align=center>
		<b>".STRING_SHIPPING_ADDRESS."</b>
	</td>
  </tr>

  <tr bgcolor=white>
	<td valign=top>
		$order
	</td>
	<td valign=top>
		$client
	</td>
  </tr>

  <tr>
	<td colspan=2 bgcolor=#$light_color align=center>
		<h1>".TABLE_TOTAL." $s</h1>
	</td>
  </tr>

			";

			//if payment type is credit card - ask credit card number, etc.
			if ($payment_type == $cc_payment_type)
			{

			   if ($payment_gateway == "2checkout")
				$out.= "
  <tr>
	<td colspan=2 bgcolor=white align=center>
	  ".STRING_CC_SELECTED."<br>
	  <TABLE>
		<TR>
		  <TD>".STRING_CC_NUMBER."</TD>
		  <TD><INPUT TYPE=TEXT NAME=CC_NUMBER></TD>
	    </TR>
		<TR>
		  <TD>".STRING_CC_EXP_DATE."</TD>
		  <TD><INPUT TYPE=TEXT NAME=CC_EXP_DATE></TD>
	    </TR>
	  </TABLE>

	</td>
  </tr>
				";


			   if ($payment_gateway == "authorizenet")
			   {

				$out.= "
  <tr>
	<td colspan=2 bgcolor=white align=center>
				";

				//show error or 'help info'
				if (!isset($authorizenet_error))
					$out .= STRING_CC_SELECTED;
				else $out .= "<b><font color=red class=cat>".base64_decode($authorizenet_error)."</font></b>";

				$out .= "<br>
	  <TABLE>
		<TR>
		  <TD>".STRING_CC_NUMBER."</TD>
		  <TD><INPUT TYPE=TEXT NAME=CC_NUMBER></TD>
	    </TR>
		<TR>
		  <TD>".STRING_CC_EXP_DATE."</TD>
		  <TD><INPUT TYPE=TEXT NAME=CC_EXP_DATE></TD>
	    </TR>
	  </TABLE>

	</td>
  </tr>
				";
			   }

			}

			$out .= "

</table>

<input type=hidden name=complete_order value=1>
<input type=hidden name=payment_type value=$payment_type>
<input type=hidden name=shipping_type value=$shipping_type>
<input type=hidden name=comment value=\"".stripslashes(str_replace("\"","",$comment))."\">
<br>
<input type=submit value=\"".STRING_PLACE_ORDER."\" name=po>

</form>
</center>

			";

		break;

	}
?>
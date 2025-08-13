<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/	

	//place order: save to the database, send notifications, gateway processing


function PostData($url,$data,$error_handler='on_error') //paste $data to the $url (authorize.net)
{
	$result="";
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_POSTFIELDS,$data);
	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE5.01; Windows NT 5.0)"); 
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$result = curl_exec($ch);
	if (curl_errno($ch) != 0)
		$error_handler(curl_error($ch));
	curl_close($ch);
	return $result;
}



	include("./cfg/cc.inc.php");

	//check cart content. it shouldn't be empty
	$q = db_query("SELECT count(*) FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log'") or die (db_error());
	$cnt = db_fetch_row($q); $cnt = $cnt[0];
	if ($cnt == 0)
	{
		$out .= "<br><br><br><center><b>".CART_EMPTY."</b></center>";
	}
	else 
	{

		//customer's details
		$q = db_query("SELECT first_name, Country, City, Address, Email, Phone, last_name, ZIP, State FROM ".CUSTOMERS_TABLE." WHERE Login='$log'") or die (db_error());
		if (!($cl = db_fetch_row($q))) exit;

		$client1 = EMAIL_HELLO.", $cl[0]\n\n";
		$client1.= EMAIL_THANK_YOU_FOR_SHOPPING_AT." $shopname!\n\n";

		$client2 = "\n\n".EMAIL_ORDER_WILL_BE_SHIPPED_TO.":\n".trim($final_address)."\n\n";
		$client2.= EMAIL_OUR_MANAGER_WILL_CONTACT_YOU."\n\n".EMAIL_SINCERELY.", $shopname\n$shopurl";


		//ordered products

		$q = db_query("SELECT productID, Quantity FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log'") or die (db_error());
		$i=0;
		$result = array();
		while ($row = db_fetch_row($q)) $result[$i++] = $row;

		$order = "";
		$s=0; //total cart value
		for ($i=0; $i<count($result); $i++)
		{
			$q = db_query("SELECT name, Price, product_code FROM ".PRODUCTS_TABLE." WHERE productID=".$result[$i][0]) or die (db_error());
			if ($r = db_fetch_row($q))
			{
				$order .= "[$r[2]] ".trim($r[0])." (x".$result[$i][1]."): ".show_price($result[$i][1]*$r[1])."\n";
				$s += $result[$i][1]*$r[1];
			}
		}
		$adm = $order;

		$q = db_query("select Name, percent_value, lump_sum from ".SHIPPING_METHODS_TABLE." where SID=$shipping_type") or die (db_error());
		$ship_r = db_fetch_row($q);
		if (!$ship_r) { $ship_r[0] = STRING_DEFAULT; $ship_r[1] = 0; $ship_r[2] = 0; }
		$adm .= "\n".STRING_SHIPPING_TYPE.": ".trim($ship_r[0]);

		$q = db_query("select Name, calculate_tax from ".PAYMENT_TYPES_TABLE." where PID=$payment_type") or die (db_error());
		$pay_r = db_fetch_row($q);
		if (!$pay_r) { $pay_r[0] = STRING_DEFAULT; $pay_r[1] = 1; }
		$adm .= "\n".STRING_PAYMENT_TYPE.": ".trim($pay_r[0]);

		$old_s = $s;
		if ($pay_r[1])
		{ //calculating tax
			$adm.= "\n".STRING_TAX.": ".show_price($tax*$s/100);
			$s *= (100+$tax)/100.0;
		}
		//make 0 tax for orders with no-tax-payment-types
		$order_tax = $tax * $pay_r[1];

		//calculate shipping cost
		//calculate cost of shipping in case of percents
		$a = $old_s*$ship_r[1]/100;
		$sh = max($a, $ship_r[2]);
		$s += $sh;

		$adm .= "\n\n".TABLE_TOTAL.": ".show_price($s);


		//before saving order - define is payment gateway is authorize-net
		//if so, attempt to send request to the gateway
		if ($payment_type == $cc_payment_type && $payment_gateway == "authorizenet")
		{
			set_time_limit(60*4);
			include("./cfg/gateways/authorizenet.inc.php");
			$postdata ="";
//			$postdata.="x_Version=3.1";
			$postdata.="x_Login=$authorizenet_login";
			$postdata.="&x_Tran_Key=$authorizenet_tran_key";
			$postdata.="&x_Delim_Data=TRUE";
//			$postdata.="&x_ADC_URL=FALSE";
			$postdata.="&x_Method=CC";
			$postdata.="&x_Type=AUTH_CAPTURE";
			$test = $authorizenet_method ? "TRUE" : "FALSE";
			$postdata.="&x_Test_Request=$test";
			
			$postdata.="&x_Amount=$s";
//			$postdata.="&x_Invoice_Num=".urlencode($order['invoice']);
//			$postdata.="&x_Description=".urlencode($order['description']);

			$postdata.="&x_Card_Num=$CC_NUMBER";
			$postdata.="&x_Exp_Date=$CC_EXP_DATE";
			$postdata.="&x_Address=$cl[3]";
			$postdata.="&x_Zip=$cl[7]";
//			$postdata.="&x_Card_Code=$CC_CVV";
			
//			$postdata.="&x_Cust_ID=".urlencode($customer['id']);
			$postdata.="&x_Last_Name=$cl[6]";
			$postdata.="&x_Email=$cl[4]";
			$postdata.="&x_State=$cl[8]";
			$postdata.="&x_City=$cl[2]";
			$postdata.="&x_Country=$cl[1]";
			$postdata.="&x_Phone=$cl[5]";

			//post data to the authorize.net gateway
			$result = PostData("https://secure.authorize.net/gateway/transact.dll",$postdata);

			$error = "";
			if (!$result) // data was not posted successfully
			{
				$error = ERROR_AUTHORIZENET_FAILED;
			}
			$res=explode(",",$result);
			if (array_count_values($res)<6)
			{
				$error = ERROR_AUTHORIZENET_FAILED;
			}
			if ($res[0] != "1")
			{
				$error = $res[3]; //error desription from authorize.net
			}

			if ($error != "") //cc processing failed
			{
				header("Location: index.php?proceed_ordering=2&skip_ct=1&payment_type=$payment_type&shipping_type=$shipping_type&authorizenet_error=".base64_encode($error));
				exit;
			}

		}



		//save order to the database
		$comment = str_replace("'","`",$comment);

		db_set_identity(ORDERS_TABLE);
		db_query("INSERT INTO ".ORDERS_TABLE." (customer_login, payment_type, customers_comment, order_time, Done, shipping_type, final_shipping_address, shipping_cost, calculate_tax, tax) VALUES ('$log','".$pay_r[0]."','".str_replace("<","&lt;",$comment)."','".get_current_time()."',0,'".$ship_r[0]."','$final_address',$sh,$pay_r[1],'$order_tax')") or die (db_error());
		$orderID = db_insert_id("ORDERS_GEN");

		$od = STRING_ORDER_ID.": $orderID\n\n";

		//now send notification - getting admin email
		$q = db_query("SELECT Email FROM ".CUSTOMERS_TABLE." WHERE Login='".ADMIN_LOGIN."'") or die (db_error());
		$em = db_fetch_row($q);
		$em = $em ? $em[0] : "";

		//sending message to customer
		mail($cl[4],EMAIL_CUSTOMER_ORDER_NOTIFICATION_SUBJECT,$client1.$od.$adm.$client2,"From: \"$shopname\"<$em>;\n".EMAIL_MESSAGE_PARAMETERS."\nReturn-path: <$em>");

		//to admin
		$adm .= "\n\n".CUSTOMER_FIRST_NAME." ".trim($cl[0])."\n".CUSTOMER_LAST_NAME." ".trim($cl[6])."\n".CUSTOMER_ADDRESS.": ".trim($final_address)."\n".CUSTOMER_PHONE_NUMBER.": ".trim($cl[5])."\n".CUSTOMER_EMAIL.": ".trim($cl[4])."\n\n".STRING_CUSTOMER_COMMENTS.":\n".trim(stripslashes($comment));
		mail($em,EMAIL_ADMIN_ORDER_NOTIFICATION_SUBJECT,$od.$adm,"From: \"$shopname\"<$em>;\n".EMAIL_MESSAGE_PARAMETERS."\nReturn-path: <$em>");

		//move content from SHOPPING_CARTS_TABLE to ORDERED_CARTS_TABLE
		$q = db_query("SELECT productID, Quantity FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log'") or die (db_error());
		while ($row = db_fetch_row($q))
		{
			$qr = db_query("select name, Price, product_code from ".PRODUCTS_TABLE." where productID=$row[0]") or die (db_error());
			$item = db_fetch_row($qr);

			db_query("INSERT INTO ".ORDERED_CARTS_TABLE." (productID, orderID, name, Price, Quantity) VALUES ($row[0],$orderID,'[$item[2]] $item[0]','$item[1]','$row[1]')") or die (db_error());
		}
		db_query("DELETE FROM ".SHOPPING_CARTS_TABLE." WHERE customer_login='$log'") or die (db_error());

		$out .= "<br><br><br><center><b>".STRING_ORDER_PLACED."</b></center>";
		//get invoice body

//		include("./kvit.php?client=".base64_encode($log)."&orderID=$orderID&get4email=yes");


//êâèòàíöèÿ

	$q = db_query("select first_name, otch, last_name, City, Address, Email from ".CUSTOMERS_TABLE." where Login='$log'") or die (db_error());
	$cl =  db_fetch_row($q);
	if (!$cl) exit;


	$q = db_query("select shipping_cost, tax from ".ORDERS_TABLE." where orderID='$orderID' and customer_login='$log'") or die (db_error());
	$order =  db_fetch_row($q);
	if (!$order) exit;

	$timestamp = time();



	//order content
	$total = 0;
	$cnt = 1;
	$q = db_query("select name, Price, Quantity from ".ORDERED_CARTS_TABLE." where orderID='$orderID'") or die (db_error());
	while ($row = db_fetch_row($q))
	{
		$total += $row[2]*$row[1];
		$cnt++;

	}

	if ($order[1])
	{
		$total += $total*$order[1]/100; //+tax if required
	}
	$total += $order[0]; //+shipping cost



	$current_currency = $invoice_currency;

	$invoice = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<!-- saved from url=(0061)http://login.valuehost.ru/inc/sb.php?bid=97383&aff=2136071810 -->
<HTML><HEAD><TITLE>Îïëàòà ïî Êâèòàíöèè</TITLE>
<META http-equiv=Content-Type content=\"text/html; charset=windows-1251\">
<META content=\"MSHTML 6.00.2800.1264\" name=GENERATOR></HEAD>
<BODY bgColor=#ffffff>
<CENTER>
  <CENTER>
    <TABLE cellSpacing=0 cellPadding=3 width=570 border=1>
  <TBODY>
  <TR>
    <TD vAlign=top align=right width=200 height=255><BR><BR><FONT size=2>
      <P>Èçâåùåíèå</P><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><FONT size=1>
      <P>Êàññèð</P></FONT></FONT></TD>
    <TD vAlign=center align=right width=100 height=255>
      <TABLE cellSpacing=0 cellPadding=3 width=370 border=1><FONT size=-1>
        <P align=center>ÈÍÍ $inn ÊÏÏ $kpp<BR><U>$reciever</U><BR><FONT size=1>ïîëó÷àòåëü ïëàòåæà</FONT></FONT> 
        <TBODY>
        <TR>
          <TD vAlign=center align=middle colSpan=4 height=10><FONT size=2>
            <P>Ðàñ÷åòíûé ñ÷åò ¹: 
            $rs&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ÁÈÊ: 
            $bik</P></FONT></TD></TR>
        <TR>
          <TD vAlign=center align=middle colSpan=4 height=10><FONT size=2>
            <P>Êîð. ñ÷.: $ks</P></FONT></TD></TR>
        <TR>
          <TD colSpan=4><FONT size=2>
            <CENTER>$cl[2]&nbsp;$cl[0]&nbsp;$cl[1]<BR>$cl[3],&nbsp;$cl[4]
            <CENTER>
            <CENTER><FONT size=1>ïëàòåëüùèê (ÔÈÎ, 
            àäðåñ)</FONT></CENTER></FONT></CENTER></CENTER></TD></TR>
        <TR>
          <TD align=middle colSpan=2><FONT size=2>Âèä ïëàòåæà</FONT></TD>
          <TD align=middle><FONT size=2>Äàòà</FONT></TD>
          <TD align=middle><FONT size=2>Ñóììà</FONT></TD></TR>
        <TR>
          <TD align=middle colSpan=2><FONT size=1>çàêàç ¹$orderID</FONT></TD>
          <TD align=middle><BR></TD>
          <TD align=middle><FONT size=2>".show_price($total)."</FONT></TD></TR>
        <TR>
          <TD align=left colSpan=2 rowSpan=2><FONT 
            size=2>Ïëàòåëüùèê:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</FONT></TD>
          <TD align=middle><FONT size=1>Ïåíÿ:</FONT></TD>
          <TD align=middle><BR></TD></TR>
        <TR align=middle>
          <TD><FONT size=1>Âñåãî:</FONT></TD>
          <TD><BR></TD></TR></P></TABLE></TD></TR>
  <TR>
    <TD vAlign=top align=right width=200 height=255><FONT size=2><BR><BR>
      <P>Êâèòàíöèÿ</P><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><FONT size=1>
      <P>Êàññèð</P></FONT></FONT></TD>
    <TD vAlign=center align=right width=100 height=255>
      <TABLE cellSpacing=0 cellPadding=3 width=370 border=1><FONT size=-1>
        <P align=center>ÈÍÍ $inn ÊÏÏ $kpp<BR><U>$reciever</U><BR><FONT size=1>ïîëó÷àòåëü ïëàòåæà</FONT></FONT> 
        <TBODY>
        <TR>
          <TD vAlign=center align=middle colSpan=4 height=10><FONT size=2>
            <P>Ðàñ÷åòíûé ñ÷åò ¹: 
            $rs&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ÁÈÊ: 
            $bik</P></FONT></TD></TR>
        <TR>
          <TD vAlign=center align=middle colSpan=4 height=10><FONT size=2>
            <P>Êîð. ñ÷.: $ks</P></FONT></TD></TR>
        <TR>
          <TD colSpan=4><FONT size=2>
            <CENTER>$cl[2]&nbsp;$cl[0]&nbsp;$cl[1]<BR>$cl[3],&nbsp;$cl[4]</CENTER>
            <CENTER><FONT size=1>ïëàòåëüùèê (ÔÈÎ, 
          àäðåñ)</FONT></CENTER></FONT></TD></TR>
        <TR>
          <TD align=middle colSpan=2><FONT size=2>Âèä ïëàòåæà</FONT></TD>
          <TD align=middle><FONT size=2>Äàòà</FONT></TD>
          <TD align=middle><FONT size=2>Ñóììà</FONT></TD></TR>
        <TR>
          <TD align=middle colSpan=2><FONT size=1>çàêàç ¹$orderID</FONT></TD>
          <TD align=middle><BR></TD>
          <TD align=middle><FONT size=2>".show_price($total)."</FONT></TD></TR>
        <TR>
          <TD align=left colSpan=2 rowSpan=2><FONT 
            size=2>Ïëàòåëüùèê:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</FONT></TD>
          <TD align=middle><FONT size=1>Ïåíÿ:</FONT></TD>
          <TD align=middle><BR></TD></TR>
        <TR align=middle>
          <TD><FONT size=1>Âñåãî:</FONT></TD>
          <TD><BR></TD></TR></P></TABLE></TD></TR></TBODY></TABLE>
  </CENTER>
</CENTER></BODY></HTML>
	";

		//send invoice
		mail($cl[5],"Êâèòàíöèÿ íà îïëàòó",$invoice,"From: \"$shopname\"<$em>;\nContent-Type: text/html; charset=\"windows-1251\"\nReturn-path: <$em>");

		//open popup window
		$out .= "
					 <script>
						 open_window('kvit.php?client=".base64_encode($log)."&orderID=$orderID',650,600);
					 </script>
		";













		//now define is payment type a credit card and 2checkout gateway is used
		if ($payment_type == $cc_payment_type)
		{
			include("./cfg/gateways/".$payment_gateway.".inc.php");
			
			switch ($payment_gateway)
			{
				case "2checkout": //2checkout - create form and submit it to 2checkout.com
					//using authorize.net parameters


					//notification and form post (here we ignore template output)
					
					echo "
<html>

<body onLoad=\"document.two_check_out_form.submit();\">
<center>
<p><b>".STRING_ORDER_PLACED."
<p><font class=cat color=blue>connecting to 2checkout.com payment gateway... please, wait</font></b>
</center>

<form name=\"two_check_out_form\" action=\"https://www.2checkout.com/cgi-bin/Abuyers/purchase.2c\" method=POST>
<input type=hidden name=x_login value=\"$twocheckout_sid\">
<input type=hidden name=x_amount value=\"$s\">
<input type=hidden name=x_Card_Num value=\"$CC_NUMBER\">
<input type=hidden name=x_Exp_Date value=\"$CC_EXP_DATE\">
<input type=hidden name=x_invoice_num value=\"$orderID\">
<input type=hidden name=x_First_Name value=\"$cl[0]\">
<input type=hidden name=x_Last_Name value=\"$cl[6]\">
<input type=hidden name=x_Phone value=\"$cl[5]\">
<input type=hidden name=x_Email value=\"$cl[4]\">
<input type=hidden name=x_Country value=\"$cl[1]\">
<input type=hidden name=x_City value=\"$cl[2]\">
<input type=hidden name=x_Address value=\"$cl[3]\">
<input type=hidden name=x_State value=\"$cl[8]\">
<input type=hidden name=x_Zip value=\"$cl[7]\">
</form>

					";

					exit;

				case "authorizenet":
				break;

			}
		}

	}

?>
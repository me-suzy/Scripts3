<?php
/////////////////////////////////////////////////////////////////////////////
//                                                                         //
// Program Name         : PHPCart                                          //
// Release Version      : 3.1                                              //
// Program Author       : Mr B Wiencierz                                   //
// Home Page            : http://www.phpcart.co.uk                         //
// Supplied by          : CyKuH [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                      //
// Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                         //
//                All code is ©2000-2004 Barry Wiencierz.                  //
//                                                                         //
/////////////////////////////////////////////////////////////////////////////

include ("./processor/index.php");
include ("./admin/payment_1.php");
include ("./admin/configuration_1.php");
include ("./localization/".$language.".php");

// Time generator
$timestamp = time();
$hoursdiff = $zone;
$hoursdiff = $hoursdiff * 3600;
$timestamp = $timestamp - $hoursdiff;
$sendtime = date("h:iA", $timestamp);
$senddate = date("d/m/y");

$ip = getenv("REMOTE_ADDR");
// Create session cookie if it does not exist
if ( empty($sessionid) ) {
	$descr = rawurlencode($descr);
	header("Refresh: 0;url=phpcart.php?action=$action&id=$id&descr=$descr&price=$price&quantity=$quantity&postage=$postage");
	setcookie ("sessionid", md5 (uniqid (rand())), time()+7200);
	exit();
}

// Check referring domain
if ( $referers ) {
	$referers = explode(" ", $referers);
	$found = false;
	$temp = explode("/",getenv("HTTP_REFERER"));
	$referer = $temp[2];
	for ($x=0; $x < count($referers); $x++){
		if (ereg ($referers[$x], $referer)) {
            		$found = true;
     		}
      	}
      	if (!$found && !empty($referer)){
		include ("./admin/header.inc");
		print "<center>";
		print "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"160\">";
		print "<tr>";
		print "<td>";
		print "<div style=\"padding:6px\">";
		print "<fieldset class=\"fieldset\">";
		print "<center><table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"160\">";
		print "<tr><td><p align=\"center\">";
		print "<CENTER><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\"><P><B>Error: The domain $referer is not an authorized referrer.<BR>
			If you are the webmaster, please check your configuration.</B></P></font></CENTER>";
		print "</td></tr></table>";
		print "</fieldset>";
		print "</div>";
		print "</td>";
		print "</tr>";
		print "</table>";
		print "</center>";
		include ("./admin/footer.inc");
		exit();
	}
}

// Check all fields have been filled out before submitting order
if ( $action == "submit" ) {
	if (empty($name) or empty($email) or empty($address) or empty($postcode0)) {
		$name 	    = rawurlencode($name);
		$email 	    = rawurlencode($email);
		$company    = rawurlencode($company);
		$address    = rawurlencode($address);
		$postcode0   = rawurlencode($postcode0);
		$telephone  = rawurlencode($telephone);
		$credit_1      = rawurlencode($credit_1);
		$credit_2      = rawurlencode($credit_2);
		$credit_3      = rawurlencode($credit_3);
		$credit_4      = rawurlencode($credit_4);
		$ccexpirymonth = rawurlencode($ccexpirymonth);
		$ccexpiryyear  = rawurlencode($ccexpiryyear);

		header("Refresh: 0;url=phpcart.php?action=confirm&name=$name&email=$email&company=$company&address=$address&postcode0=$postcode0&telephone=$telephone&credit_1=$credit_1&credit_2=$credit_2&credit_3=$credit_3&credit_4=$credit_4&ccexpirymonth=$ccexpirymonth&ccexpiryyear=$ccexpiryyear&alert=1");
		exit();
	} else {
		header("Refresh: 150000;url=$home");
	}
}

// Include html header
include ("./admin/header.inc");
		print "<center>";
		print "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"160\">";
		print "<tr>";
		print "<td>";
		print "<div style=\"padding:6px\">";
		print "<fieldset class=\"fieldset\">";
		print "<div align=\"center\">";
		print "<center><table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"160\">";
		print "<tr><td><p align=\"center\">";

if ($action) {

	// Create session file
	if ( ! file_exists("./sessions/".$sessionid.".dat"))
		copy("./sessions/empty", "./sessions/".$sessionid.".dat");

	// Add product to cart
	if ($action=="add") {
		$row = 1;
		$fp = fopen ("./sessions/".$sessionid.".dat", "r+");
		while ($data = fgetcsv ($fp, 500)) {
			$row++;
			if ($data[0] == $id) {
				print "	<CENTER><FONT SIZE=". ($fontSize+1) ." FACE=\"$font\"
					COLOR=\"$TextColor\"><B>$ProductInBasket</B></FONT></CENTER>";
				break;
			}
		}
		if($data[0]!=$id) {
			if (!$option1) {
				$option_print = "";
				} else {
				$option_print = "- $option1 $option2";
				}
			$descr_option = "$descr $option_print";
			fputs($fp,$id.",".$descr_option.",".$price.",".$quantity.",".$postage."\n");
		}
		fclose($fp);
	}

	// Clear all shopping cart contents
	if ($action=="clear") {
		$fp = fopen ("./sessions/".$sessionid.".dat", "w");
		fclose($fp);
		$action="add";
	}

	// Delete product from cart
	if ($action=="delete") {
		$row = 1;
		$fp = fopen ("./sessions/".$sessionid.".dat", "r+");
		while ($data = fgetcsv ($fp, 500)) {
			if ($id==$row) {
				$row++;
				continue;
			} else {

				$new_data[$row] = $data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4];
				$row++;
			}
		}
		fclose ($fp);

		$fp = fopen ("./sessions/".$sessionid.".dat", "w");
		if (!empty($new_data)) {
			$new_data_insert = implode ("\n", $new_data);
			fputs($fp, $new_data_insert."\n");
		}
		fclose($fp);
		$action="view";
	}

	// Recalculate cart-contents
	if ($action=="confirm" || $action=="checkout") {
		$row = 1;
		$fp = fopen ("./sessions/".$sessionid.".dat", "r+");
		while ($data = fgetcsv ($fp, 500)) {
			if (!empty($product)) {
				if ($product[$row] == "0" || empty($product[$row])) {
					$row++;
					continue;
				} else {
					$new_data[$row] = $data[0].",".$data[1].",".$data[2].",".$product[$row].",".$data[4];
					$row++;
				}
			}
		}
		fclose ($fp);

		if (!empty($new_data)) {
			$new_data_insert = implode ("\n", $new_data);
			$fp = fopen ("./sessions/".$sessionid.".dat", "w");
				fputs($fp, $new_data_insert."\n");
			fclose ($fp);
		}
	}

	// Print basket contents
	$tot_pos = 0;
	$tot_postage = $PostalAmount;
	$fp = fopen ("./sessions/".$sessionid.".dat", "r+");
	if ($action=="confirm" || $action=="checkout") {
		print "	<CENTER><FORM ACTION=\"phpcart.php\" METHOD=\"post\" TARGET=\"_self\">
			<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"submit\">


			<table border=1 cellspacing=0 width=553 bordercolor=#000000>
			<tr><td align=center bgcolor=#FFFFFF width=543>
			<p align=center>
			<font face=Arial color=#FF0000 size=2>
			<b>Note:</b></font>
            		<font face=Arial color=#000080 size=2>$Note</font></p>
			</td>
			</tr>
			</table>


			<TABLE WIDTH=500 ALIGN=center BORDER=0 CELLPADDING=5 CELLSPACING=2>
			<COL WIDTH=200 ALIGN=left><COL WIDTH=400 ALIGN=left><TR>
			<TH COLSPAN=2 BGCOLOR=\"$PgBack\"><FONT FACE=\"$font\" SIZE=".($fontSize + 1)." color=$TextColor>$confirmOrderMessage</FONT></TH>
			</TR><TR>
			<TH COLSPAN=2 BGCOLOR=\"$headerColor\"><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\">$PersonalInfoWord</FONT></TH>
			</TR><TR><TD BGCOLOR=$rowsColor> ";
		if (empty($name) and !empty($alert)) {
			print "<FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$NameWord</B></FONT>";
		} else {
			print "<FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\">$NameWord</FONT>";
		}
		print"	</TD><TD BGCOLOR=$rowsColor>
			<INPUT TYPE=\"text\" NAME=\"name\" STYLE=\"font-size: 8pt\" SIZE=40 VALUE=\"";
		if (!empty($name)) {
			print $name;
		}
		print "\"></TD></TR><TR><TD BGCOLOR=$rowsColor> ";
		if (empty($email) and !empty($alert)) {
			print "<FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$EmailWord</B></FONT>";
		} else {
			print "<FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\" COLOR=\"$TextColor\">$EmailWord</FONT>";
		}
		print "	</TD><TD BGCOLOR=$rowsColor>
			<INPUT TYPE=\"text\" NAME=\"email\" STYLE=\"font-size: 8pt\" SIZE=40 VALUE=\"";
		if (!empty($email)) {
			print $email;
		}
		print "\"></TD></TR><TR>
			<TD BGCOLOR=$rowsColor><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\">$CompanyWord</FONT></TD>
			<TD BGCOLOR=$rowsColor>
			<INPUT TYPE=\"text\" NAME=\"company\" STYLE=\"font-size: 8pt\" SIZE=40 VALUE=\"";
		if (!empty($company)) {
			print $company;
		}
		print "\"></TD></TR><TR><TD BGCOLOR=$rowsColor> ";
		if (empty($address) and !empty($alert)) {
			print "<FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$deliveryAddress</B></FONT>";
		} else {
			print "<FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\">$deliveryAddress</FONT>";
		}
		print "	</TD><TD BGCOLOR=$rowsColor>
			<INPUT TYPE=\"text\" NAME=\"address\" STYLE=\"font-size: 8pt\" SIZE=40 VALUE=\"";
		if (!empty($address)) {
			print $address;
		}
		print "\"></TD></TR><TR><TD BGCOLOR=$rowsColor> ";
		if ((empty($postcode0)) and !empty($alert)) {
			print "<FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$postcode</B></FONT>";
		} else {
			print "<FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\">$postcode</FONT>";
		}
		print "	</TD><TD BGCOLOR=$rowsColor>
			<INPUT TYPE=\"text\" NAME=\"postcode0\" maxlength=10 STYLE=\"font-size: 8pt\" SIZE=12 VALUE=\"";
		if (!empty($postcode0)) {
			print $postcode0;
		}
		print "\"></TD></TR><TR><TD BGCOLOR=$rowsColor>
			<FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\">$phoneFax</FONT>
			</TD><TD BGCOLOR=$rowsColor>
			<INPUT TYPE=\"text\" NAME=\"telephone\" STYLE=\"font-size: 8pt\" SIZE=14 VALUE=\"";
		if (!empty($telephone)) {
			print $telephone;
		}
		print "\"></TD></TR>";

		print "<TR><TD BGCOLOR=$rowsColor>
			<FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\">$p_method</FONT>
			</TD><TD BGCOLOR=$rowsColor><select size='1' name='pmethod'>";

// ========================
		if ($twocheckout_active=="Yes"){
		print "<option value='2Checkout'>Credit Card</option>";
		}
		if ($paystamp_active=="Yes"){
		print "<option value='Paystamp'>Credit Card</option>";
		}
		if ($eway_active=="Yes"){
		print "<option value='eWay'>Credit Card</option>";
		}
		if ($worldpay_active=="Yes"){
		print "<option value='WorldPay_CC'>Credit Card</option>";
		print "<option value='WorldPay_DC'>Debit Card</option>";
		}
		if ($paypal_active=="Yes"){
		print "<option value='Paypal'>PayPal</option>";
		}
		if ($nochex_active=="Yes"){
		print "<option value='Nochex'>Nochex</option>";
		}
		if ($fastpay_active=="Yes"){
		print "<option value='Fastpay'>FastPay</option>";
		}
		if ($auth_active=="Yes"){
		print "<option value='Authorize.net'>Credit Card</option>";
		}
		if ($moneyb_active=="Yes"){
		print "<option value='MoneyBookers'>Money Bookers</option>";
		}
		if ($ematters_active=="Yes"){
		print "<option value='eMatters'>Credit Card</option>";
		}
		if ($asiadebit_active=="Yes"){
		print "<option value='Asiadebit'>Credit Card</option>";
		print "<option value='Asiadebit'>Debit Card</option>";
		}
		if ($paysystems_active=="Yes"){
		print "<option value='Paysystems'>Credit Card</option>";
		print "<option value='Paysystems'>Debit Card</option>";
		}
		if ($verisign_active=="Yes"){
		print "<option value='Verisign'>Credit Card</option>";
		print "<option value='Verisign'>Debit Card</option>";
		}

		if ($offline_active=="Yes"){
		print "<option value='Cash'>Cash</option>";
		print "<option value='Cheque'>Cheque</option>";
		}
// ========================
		print "</select>";
		print "</TD></TR>";
		print "</TD></TR>";
	if ($notes_active=="Yes"){
		print "<TR><TD BGCOLOR=$rowsColor valign=top>
			<FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\">$NotesWord</FONT>
			</TD><TD BGCOLOR=$rowsColor>
			<textarea rows=3 name=notes cols=25>";
		if (!empty($notes)) {
			print $notes;
		}
		print "</textarea>";
		print "</TD></TR>";
	}
		print "</TABLE></CENTER>";
	}
	if ($action=="add" || $action=="view")
		print "	<FORM ACTION=\"phpcart.php\" METHOD=\"post\" TARGET=\"_self\" NAME=\"prodForm\">
			<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"confirm\"> ";
	if ($action!="submit")
		print "	<!-- CyKuH [WTN] -->
			<CENTER><TABLE WIDTH=500 BORDER=0 ALIGN=\"center\" CELLPADDING=5 CELLSPACING=2 STYLE=\"font-family: arial; font-size: 10pt\" width=\"1\" BGCOLOR=$PgBack>
			<COL WIDTH=20 ALIGN=center>
			<COL WIDTH=100 ALIGN=center>
			<COL WIDTH=320 ALIGN=left>
			<COL WIDTH=70 ALIGN=right>
			<COL WIDTH=30 ALIGN=center>
			<COL WIDTH=80 ALIGN=right> ";
	if($action=="add" || $action=="confirm" || $action=="checkout" || $action=="view") {
		print "	<TR BGCOLOR=\"$headerColor\" width=\"1\">
			<TH COLSPAN=2><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$IDWord</B></FONT></TH>
			<TH><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$descriptionWord</B></FONT></TH>
			<TH><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$productPriceWord &nbsp;</B></FONT></TH>
			<TH><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$quantityWord</B></FONT></TH>
			<TH><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$sumPriceWord &nbsp;</B></FONT></TH></TR> ";
		$line_no = 1;
		$tracker = 4;
		while ($data = fgetcsv ($fp, 500)) {
			$num = count ($data);
			print "	<TR BGCOLOR=$rowsColor>
				<TD><A HREF=\"phpcart.php?action=delete&id=$line_no\"><IMG SRC=\"./images/trashicon.gif\" BORDER=0 WIDTH=16 HEIGHT=16 ALT=\"Remove $data[1] from Basket?\"></A></TD>
				<TD><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\">$data[0]</FONT></TD>
				<TD><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\">$data[1]</FONT></TD>
				<TD><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\">$data[2] &nbsp;</FONT></TD> ";
			if ($action=="confirm" || $action=="checkout") {
				print "<TD><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\">$data[3]</FONT></TD>";
			} else {
				print "	<TD><!-- DUMMY FIELD TO FIX NETSCAPE BUG --><INPUT TYPE=\"hidden\">
					<BODY  onLoad=\"getTotalCost();\"><INPUT TYPE=\"text\" NAME=\"product[$line_no]\" VALUE=\"$data[3]\" SIZE=2 STYLE=\"text-color:$TextColor;font-size:8pt;text-align:center\" onChange=\"prodForm[$tracker].value = round(parseFloat($data[2]) * this.value); getTotalCost();\" onLoad=\"prodForm[$tracker].value = round(parseFloat($data[2]) * this.value); getTotalCost();\" onClick='prodForm[$tracker].value = round(parseFloat($data[2]) * this.value); getTotalCost();' onMouseOver='prodForm[$tracker].value = round(parseFloat($data[2]) * this.value); getTotalCost();' onMouseOut='prodForm[$tracker].value = round(parseFloat($data[2]) * this.value); getTotalCost();' onKeyUp='prodForm[$tracker].value = round(parseFloat($data[2]) * this.value); getTotalCost();'></TD>
					<SCRIPT LANGUAGE=\"javascript\">
					function round (n) {
    						n = Math.round(n * 100) / 100;
    						n = (n + 0.001) + '';
    						return n.substring(0, n.indexOf('.') + 3);
  					}
					function getTotalCost () {
						totalCost = 0;
						for (i = 4; i < (document.prodForm.length - 2); i += 4) {
							totalCost += parseFloat(document.prodForm[i].value);
						}
						document.prodForm.total.value = round(totalCost);
					} </SCRIPT> ";
			}
			if ($action=="add" || $action=="view") {
				print "	<TD><!-- DUMMY FIELD TO FIX NETSCAPE BUG --><INPUT TYPE=\"hidden\">
					<INPUT TYPE=\"text\" NAME=\"\" DISABLED STYLE=\"font-size:8pt;text-align:right;
					background-color:$rowsColor;text-color:$TextColor;border-width:0;padding-right:5pt\"
					VALUE=\"".$data[2] * $data[3]."\" SIZE=8 COLOR=\"$TextColor\"></TD></TR> ";
			} else {
				print "	<TD><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$TextColor\">". ($data[2] * $data[3]) ." &nbsp;</TD></TR> ";
			}
			if (! empty($no_prod) ) {
				$no_prod = $no_prod + $data[3];
			}
			$tot_pos = $tot_pos + ($data[2] * $data[3]);
			$tot_postage = $tot_postage + ($data[4]);

			$tracker += 4;
			$line_no++;
		}
		if ($line_no == 1) {
			print "	<TR BGCOLOR=$rowsColor>
				<TD COLSPAN=6 ALIGN=center><B>$emptyCart</B></TD></TR>";
		}
		$subtotal = number_format( ($tot_pos), 2);
		$vatVal = ($tot_pos)/(100)*$salesVAT;
		$vat = number_format( ($vatVal), 2);
		$postage = number_format( ($tot_postage), 2);
		$total = number_format( ($tot_pos+$vat+$postage), 2);
		print "	<INPUT TYPE=\"hidden\" NAME=\"rows\" VALUE=\"$row\">";
		fclose ($fp);
	}
	if ($action=="add" || $action=="view") {
		print "	<TR BGCOLOR=$rowsColor><TD BGCOLOR=\"$PgBack\" COLSPAN=5>                <p align=right></TD><TD>
			<INPUT TYPE=\"text\" NAME=\"total\" DISABLED STYLE=\"font-size:8pt;text-align:right;
			background-color:$rowsColor;border-width:0;padding-right:5pt\" VALUE=\"".$data[2] * $data[3]."\" SIZE=8></TD>
			</TR><TR><TD COLSPAN=3 ALIGN=left><A HREF=\"$home\"><IMG SRC=\"./images/$backPicture\" BORDER=0></A></TD>
			<TD COLSPAN=4 ALIGN=right>
			<INPUT TYPE=\"image\" SRC=\"./images/$orderPicture\" BORDER=0 STYLE=\"border-width: 0\">
			</TD></TR></TABLE></CENTER></FORM> ";
	} else if ($action=="confirm" || $action=="checkout") {
	 	print "	<TR>
			<TD COLSPAN=3 ALIGN=left ROWSPAN=5><CENTER><A HREF=\"$home\"><IMG SRC=\"./images/$viewPicture\" BORDER=0></A></CENTER><BR><B><SMALL>$extrasText</SMALL></B><BR></TD>
			<TD COLSPAN=2 ALIGN=right BGCOLOR=\"$headerColor\"><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$SubTotalWord</B></FONT></TD>
			<TD BGCOLOR=\"$headerColor\"><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\">$currency$tot_pos &nbsp;</FONT></TD>
			</TR>";
	if (!empty($salesVAT)) {
		print "	<TR>
			<TD COLSPAN=2 ALIGN=right BGCOLOR=\"$headerColor\"><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$VATWord</B></FONT></TD>
			<TD BGCOLOR=\"$headerColor\"><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\">$currency$vat &nbsp;</FONT></TD>
			</TR>";
	} else {
	 	print " ";
	}
	if (!empty($PostalAmount)) {
		print "	<TR>
			<TD COLSPAN=2 ALIGN=right BGCOLOR=\"$headerColor\"><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$PostageWord</B></FONT></TD>
	        	<TD BGCOLOR=\"$headerColor\"><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\">$currency$postage &nbsp;</FONT></TD>
	        	</TR>";
	} else {
	 	print " ";
	}
	 	print "	<TR>
		        <TD COLSPAN=2 ALIGN=right BGCOLOR=\"$headerColor\"><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\"><B>$TotalSumWord</B></FONT></TD>
			<TD BGCOLOR=\"$headerColor\"><FONT FACE=\"$font\" SIZE=$fontSize COLOR=\"$titleColor\">$currency$total &nbsp;</FONT></TD>
			</TR><TR>
			<TD COLSPAN=3 ALIGN=center BGCOLOR=$rowsColor><INPUT TYPE=\"submit\" STYLE=\"font-family: $font; font-weight: bold; padding: 2pt\" VALUE=\"$orderButtonText\"></TD>
			</TR></FORM><TR><TD HEIGHT=20></TD></TR></TABLE></CENTER> ";
	}
}
$method = "Payment Method:<BR>";
$pmethod0 = "$pmethod<BR>";
$line1 = "-----------------------------------------------------------<BR>";
$SP0 = "\n";
	// Submit order
	if ($action=="submit") {
		$order_no = (substr(uniqid (""), 2, 7));
		$order_id = strtoupper($order_no);

		$fp = fopen ("./sessions/".$sessionid.".dat", "r");
		$row = 1;
		while ($data = fgetcsv ($fp, 500)) {
			$new_data[$row] = "-----------------------------------------------------------<BR>".$data[0]."    ".$data[1]."<BR>".$data[3]." qty at $currency".$data[2]."    =    $currency".$data[2] * $data[3];
			$row++;
			if (! empty($no_prod) ) {
				$no_prod = $no_prod + $data[3];
			}
			$tot_pos = $tot_pos + ($data[2] * $data[3]);
			$tot_postage = $tot_postage + ($data[4]);
		}
		$subtotal = number_format( ($tot_pos), 2);
		$vatVal = ($tot_pos)/(100)*$salesVAT;
		$vat = number_format( ($vatVal), 2);
		$postage = number_format( ($tot_postage), 2);
		$total = number_format( ($tot_pos+$vat+$postage), 2);
		fclose ($fp);
		$new_data_insert = implode ("<BR>", $new_data);


// Send email to admin
$message  = "<font face=Verdana size=2>";
$message .= "---------------------------------------------------------------------------<BR>";
$message .= "<B>$companyName</B><BR>";
$message .= "Web Order Summary<BR>";
$message .= "Date: $senddate<BR>";
$message .= "Order ID: $order_id<BR>";
$message .= "---------------------------------------------------------------------------<BR>";
$message .= "<BR>";
$message .= "<B>Order Summary</B><BR>";
$message .= "-----------------------<BR>";
$message .= "<b>Name:</b> $name<BR>";
$message .= "<b>E-mail:</b> <a href=mailto:$email>$email</a><BR>";
$message .= "<BR>";
$message .= "<b>Postal Address:</b><BR>";
$message .= "$address<BR>";
$message .= "$postcode0<BR>";
$message .= "<BR>";
$message .= "<b>Telephone:</b> $telephone<BR>";
$message .= "<BR>";
$message .= "<b>$CompanyWord:</b> $company<BR>";
$message .= "<BR>";
if (!empty($notes)) {
$message .= "";
} else {
$message .= "<B>Order Notes:</B><BR>$notes<BR>";
}
$message .= "<BR>";
$message .= "$new_data_insert<BR>";
$message .= "-----------------------------------------------------------<BR>";
$message .= "<BR>";
$message .= "Sub Total = $currency$subtotal<BR>";
if (!empty($salesVAT)) {
$message .= "";
} else {
$message .= "Sales Tax = $currency$vat<BR>";
}
if (!empty($PostalAmount)) {
$message .= "";
} else {
$message .= "Post & Packaging = $currency$postage<BR>";
}
$message .= "Total = $currency$total<BR>";
$message .= "<BR>";
$message .= "<BR>";
$message .= "<B>Order Information</B><BR>";
$message .= "$line1$SP0 $method $pmethod0 $SP0$line1";
$message .= "<BR>";
$message .= "<BR>";
$message .= "$CustomerHasReceipt";
$message .= "<BR>";
$message .= "</font>";

mail($salesEmail, "Web Order Confirmation - Order ID: $order_id", $message, "Content-type: text/html\nFrom: $email");



// Send email to customer
$message  = "<font face=Verdana size=2>";
$message .= "---------------------------------------------------------------------------<BR>";
$message .= "<B>$companyName</B><BR>";
$message .= "Web Order Summary<BR>";
$message .= "Date: $senddate<BR>";
$message .= "Order ID: $order_id<BR>";
$message .= "---------------------------------------------------------------------------<BR>";
$message .= "<BR>";
$message .= "Dear Customer,<BR>";
$message .= "<BR>";
$message .= "Thank you very much for ordering your goods from $companyName. A summary of your order can be found below.";
$message .= "<BR><BR>";

$message .= "<B>Order Summary</B><BR>";
$message .= "-----------------------<BR>";
$message .= "<b>Name:</b> $name<BR>";
$message .= "<b>E-mail:</b> <a href=mailto:$email>$email</a><BR>";
$message .= "<BR>";
$message .= "<b>Postal Address:</b><BR>";
$message .= "$address<BR>";
$message .= "$postcode0<BR>";
$message .= "<BR>";
$message .= "<b>Telephone:</b> $telephone<BR>";
$message .= "<BR>";
$message .= "$new_data_insert<BR>";
$message .= "-----------------------------------------------------------<BR>";
$message .= "<BR>";
$message .= "Sub Total = $currency$subtotal<BR>";
if (!empty($salesVAT)) {
$message .= "";
} else {
$message .= "Sales Tax = $currency$vat<BR>";
}
if (!empty($PostalAmount)) {
$message .= "";
} else {
$message .= "Post & Packaging = $currency$postage<BR>";
}
$message .= "Total = $currency$total<BR>";
$message .= "<BR>";
$message .= "<BR>";
$message .= "<B>Order Information</B><BR>";
$message .= "-----------------------<BR>";
$message .= "Your order will be shipped as soon as payment has been received.";
$message .= "<BR>";
$message .= "<BR>";
$message .= "<BR>";
$message .= "If you have any problems or questions, please contact us by <a href=mailto:$salesEmail?subject=Order%20ID%20-%20$order_id%20>clicking here</a>.";
$message .= "<BR>";
$message .= "<BR>";
$message .= "<BR>";
$message .= "Thank you,<BR>";
$message .= "The $companyName Team<BR>";
$message .= "</font>";
mail($email, "Web Order Confirmation - Order ID: $order_id", $message, "Content-type: text/html\nFrom: $companyName <$salesEmail>");


// THIS PART MAKES IT WRITE TO THE ORDER FOLDER

if ($enableCopy == Activate) {
	$new_data_insert = implode ("\n", $new_data);
$file = fopen ("orders/$order_id.txt", "w");
if (!$file) {
    echo "<p>Unable to open remote file for writing. Please make sure 'orders' Folder is chmod to 777\n";
    exit;
}
/* Write the data here. */
fputs ($file, "IP: $ip
Date: $senddate
Time: $sendtime

$NameWord: $name
$CompanyWord: $company
$EmailWord: $email
$AddressWord: $address
$postcode: $postcode0

$PhoneWord: $telephone


$new_data_insert
-----------------------------------------------------------
Order Notes:
$notes

$SubTotalWord  =  $currency$subtotal
$PostageWord  =  $currency$postage
$TotalSumWord  =  $currency$total

$line1$SP0$method $pmethod0 $SP0$line1


$ThisOrderHasRef: $order_id
$CustomerHasReceipt\n");
fclose ($file);
}
	// Payment Processor Section - Which ever processors you have setup will now be sent for
	if ($pmethod=="Nochex"){
	include ("processor/nochex.inc");
	}
	if ($pmethod=="2Checkout"){
	include ("processor/2checkout.inc");
	}
	if ($pmethod=="Paystamp"){
	include ("processor/paystamp.inc");
	}
	if ($pmethod=="eWay"){
	include ("processor/eway.inc");
	}
	if ($pmethod=="Paypal"){
	include ("processor/paypal.inc");
	}
	if ($pmethod=="WorldPay_CC" || $pmethod=="WorldPay_DC"){
	include ("processor/worldpay.inc");
	}
	if ($pmethod=="Authorize.net"){
	include ("processor/authorize.inc");
	}
	if ($pmethod=="MoneyBookers"){
	include ("processor/moneybookers.inc");
	}
	if ($pmethod=="eMatters"){
	include ("processor/ematters.inc");
	}
	if ($pmethod=="Fastpay"){
	include ("processor/fastpay.inc");
	}
	if ($pmethod=="Asiadebit"){
	include ("processor/asiadebit.inc");
	}
	if ($pmethod=="Paysystems"){
	include ("processor/paysystems.inc");
	}
	if ($pmethod=="Verisign"){
	include ("processor/verisign.inc");
	}
	if ($pmethod=="Cash" || $pmethod=="Cheque"){
	include ("processor/offline.inc");
	}

unlink("./sessions/".$sessionid.".dat");
}

// Check installed version number
if ($check=="version") {
require("admin/version.php");
	print "<CENTER><FONT FACE=\"$font\" SIZE=5 COLOR=\"$TextColor\"><P><B>You are running version $version of PHPCart.</B></P></font></CENTER>";
}
		print "</td></tr></table>";
		print "</center>";
		print "</div>";
		print "</fieldset>";
		print "</div>";
		print "</td>";
		print "</tr>";
		print "</table>";
		print "</center>";
include ("./admin/footer.inc"); ?>
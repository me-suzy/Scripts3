<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.9
// Supplied by          : CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
// #### CHANGE THIS IF YOU PUT THIS ON A DIFFERENT SERVER #####
require("admin/config.php");
require("admin/db_mysql.php");

// If this file is on a different server or directory then your cart,
// uncomment the two lines below and be sure to include the lcoation of
// the language file.
// $overwritelang = 1;
// require("lang_eng.php");

// ############# Transfer Session To Secure Server #############
session_start();
if ($setnew == "yes") {
   session_unset();
   session_register("total_items");
   session_register("item_tray");
   session_register("options_only");
   session_register("payment");
   session_register("USERIN");
   session_register("PASSIN");
   $total_items=0; $item_tray=""; $options_only="";
   $total_items=$totalitems;
   $options_only=$optionsonly;
   $USERIN=$USER;
   $PASSIN=$PASS;
   $payment=$payment1;
   for ($i = 0; $i < $totalitems; $i++) {
	   $item_tray[$i]=$itemtray[$i];
   }
}

// ###################### Start Database#######################

$DB_site=new DB_Sql_vb;
$DB_site->appname="SunShop";
$DB_site->appshortname="SunShop";
$DB_site->database=$dbname;
$DB_site->server=$servername;
$DB_site->user=$dbusername;
$DB_site->password=$dbpassword;
$DB_site->connect();

// ###################### Loading Options #######################
$temps=$DB_site->query("SELECT * FROM ".$dbprefix."options");
while ($row=$DB_site->fetch_array($temps)) {
	$title = stripslashes($row[title]);
	$hometitle = stripslashes($row[hometitle]);
	$shopurl = stripslashes($row[shopurl]);
	$homeurl = stripslashes($row[homeurl]);
	$securepath = stripslashes($row[securepath]);
	$companyname = stripslashes($row[companyname]);
	$shopaddress = stripslashes($row[address]);
	$shopcity = stripslashes($row[city]);
	$shopstate = stripslashes($row[state]);
	$shopzip = stripslashes($row[zip]);
	$shopcountry = stripslashes($row[country]);
	$shopphone = stripslashes($row[phone]);
	$shopfax = stripslashes($row[faxnumber]);
	$contactemail = stripslashes($row[contactemail]);
	$taxrate = stripslashes($row[taxrate]);
	$shipups = stripslashes($row[shipups]);
	$grnd = stripslashes($row[grnd]);
	$nextdayair = stripslashes($row[nextdayair]);
	$seconddayair = stripslashes($row[seconddayair]);
	$threeday = stripslashes($row[threeday]);
	$canada = stripslashes($row[canada]);
	$worldwidex = stripslashes($row[worldwidex]);
	$worldwidexplus = stripslashes($row[worldwidexplus]);
	$fixedshipping = stripslashes($row[fixedshipping]);
	$method = stripslashes($row[method]);
	$shoprate = stripslashes($row[rate]);
	$productpath = stripslashes($row[productpath]);
	$catimage = stripslashes($row[catimage]);
	$catopen = stripslashes($row[catopen]);
	$viewcartimage = stripslashes($row[viewcartimage]);
	$viewaccountimage = stripslashes($row[viewaccountimage]);
	$checkoutimage = stripslashes($row[checkoutimage]);
	$helpimage = stripslashes($row[helpimage]);
	$cartimage = stripslashes($row[cartimage]);
	$tablehead = stripslashes($row[tablehead]);
	$tableheadtext = stripslashes($row[tableheadtext]);
	$tableborder = stripslashes($row[tableborder]);
	$tablebg = stripslashes($row[tablebg]);
	$header1 = stripslashes($row[header1]);
    $footer = stripslashes($row[footer]);
	$shipchart = stripslashes($row[shipchart]);
	$ship1p1 = stripslashes($row[ship1p1]);
	$ship1us = stripslashes($row[ship1us]);
	$ship1ca = stripslashes($row[ship1ca]);
	$ship2 = stripslashes($row[ship2]);
	$ship2p1 = stripslashes($row[ship2p1]);
	$ship2p2 = stripslashes($row[ship2p2]);
	$ship2us = stripslashes($row[ship2us]);
	$ship2ca = stripslashes($row[ship2ca]);
	$ship3 = stripslashes($row[ship2]);
	$ship3p1 = stripslashes($row[ship3p1]);
	$ship3p2 = stripslashes($row[ship3p2]);
	$ship3us = stripslashes($row[ship3us]);
	$ship3ca = stripslashes($row[ship3ca]);
	$ship4p1 = stripslashes($row[ship4p1]);
	$ship4us = stripslashes($row[ship4us]);
	$ship4ca = stripslashes($row[ship4ca]);
	$visa = stripslashes($row[visa]);
	$mastercard = stripslashes($row[mastercard]);
	$discover = stripslashes($row[discover]);
	$amex = stripslashes($row[amex]);
	$check = stripslashes($row[check]);
	$fax = stripslashes($row[fax]);
	$moneyorder = stripslashes($row[moneyorder]);
	$cc = stripslashes($row[cc]);
	$payable = stripslashes($row[payable]);
	$paypal = stripslashes($row[paypal]);
	$paypalemail = stripslashes($row[paypalemail]);
	$shopimage = stripslashes($row[shopimage]);
	$centercolor = stripslashes($row[centercolor]);
	$centerborder = stripslashes($row[centerborder]);
	$centerheader = stripslashes($row[centerheader]);
	$centerfont = stripslashes($row[centerfont]);
	$myheader = stripslashes($row[myheader]);
	$myfooter = stripslashes($row[myfooter]);
	$useheader = stripslashes($row[useheader]);
	$usefooter = stripslashes($row[usefooter]);
	$centerbg = stripslashes($row[centerbg]);
	$thumbwidth = stripslashes($row[thumbwidth]);
	$thumbheight = stripslashes($row[thumbheight]);
	$picwidth = stripslashes($row[picwidth]);
	$picheight = stripslashes($row[picheight]);
	$showstock = stripslashes($row[showstock]);
	$showitem = stripslashes($row[showitem]);
	$showintro = stripslashes($row[showintro]);
	$orderby = stripslashes($row[orderby]);
	$outofstock = stripslashes($row[outofstock]);
	$cs = stripslashes($row[cs]);
	$showprice = stripslashes($row[showprice]);
	$po = stripslashes($row[po]);
	$handling = stripslashes($row[handling]);
	$imagel = stripslashes($row[imagel]);
	$lang = stripslashes($row[language]);
	$showspecials = stripslashes($row[showspecials]);
	$showbestsellers = stripslashes($row[showbestsellers]);
	$showcattotals = stripslashes($row[showcattotals]);
	$shipcalc = stripslashes($row[shipcalc]);
	$shipusps = stripslashes($row[shipusps]);
	$itemsperpage = stripslashes($row[itemsperpage]);
	$usesecurefooter = stripslashes($row[usesecurefooter]);
	$mysecurefooter = stripslashes($row[mysecurefooter]);
	$securefooter = stripslashes($row[securefooter]);
	$usesecureheader = stripslashes($row[usesecureheader]);
	$mysecureheader = stripslashes($row[mysecureheader]);
	$secureheader = stripslashes($row[secureheader]);
	$mustsignup = stripslashes($row[mustsignup]);
	$uspsserver = stripslashes($row[uspsserver]);
	$uspsuser = stripslashes($row[uspsuserp]);
	$uspspass = stripslashes($row[uspspass]);
	$catsdisplay = stripslashes($row[catsdisplay]);
	$allwidth = stripslashes($row[allwidth]);
	$centerwidth = stripslashes($row[centerwidth]);
	$tablewidth = stripslashes($row[tablewidth]);
}

// ################# load language file ###################

if ($overwritelang != 1) {
	require($lang);
}

// ################# load template ###################
function loadtemplate($templatename) {
  global $DB_site, $dbprefix;
  $temp=$DB_site->query_first("SELECT * FROM ".$dbprefix."templates WHERE title='".addslashes($templatename)."' LIMIT 1");
  $template=$temp[template];
  return "<!-- TEMPLATE INITIATE: $templatename -->\n$template\n<!-- TEMPLATE TERMINATE: $templatename -->";
}

// ###################### parsecart #######################
function parsecart () {
    global $total_items, $item_tray, $itemd, $quantityd, $optionsd, $payopd, $options_only, $options_array, $fieldsd;
    for ($i = 0; $i < $total_items; $i++) {
		$temparray = explode("->", $item_tray[$i]);
		$options_array = explode("->", $options_only);
        $itemd[$i] = $temparray[0];
		$quantityd[$i] = $temparray[1];
		$optionsd[$i] = $temparray[2];
		$payopd[$i] = $temparray[3];
		$fieldsd[$i] = $temparray[4];
	}
}

// ###################### iteminfo ##################
function iteminfo ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$id'");
    while ($row=$DB_site->fetch_array($temps)) {
		$iteminfo[categoryid] = stripslashes($row[categoryid]); $iteminfo[title] = stripslashes($row[title]);
		$iteminfo[stitle] = stripslashes($row[stitle]); $iteminfo[imagename] = stripslashes($row[imagename]);
		$iteminfo[thumb] = stripslashes($row[thumb]); $iteminfo[poverview] = stripslashes($row[poverview]);
		$iteminfo[pdetails] = stripslashes($row[pdetails]); $iteminfo[quantity] = stripslashes($row[quantity]);
		$iteminfo[sold] = stripslashes($row[sold]); $iteminfo[shipinfo] = stripslashes($row[shipinfo]);
		$iteminfo[price] = stripslashes($row[price]);	$iteminfo[weight] = stripslashes($row[weight]);
		$iteminfo[viewable] = stripslashes($row[viewable]); $iteminfo[itemid] = stripslashes($row[itemid]); 
		$iteminfo[sku] = stripslashes($row[sku]);
		if ($iteminfo[sku] != "") { $iteminfo[num] = $iteminfo[sku]; } else { $iteminfo[num] = $iteminfo[itemid]; }
		
	}
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."specials where itemid='$id'");
    while ($row=$DB_site->fetch_array($temp)) {
		$iteminfo[specialid] = stripslashes($row[specialid]); $iteminfo[sdescription] = stripslashes($row[sdescription]); 
		$iteminfo[sprice] = stripslashes($row[sprice]);
	}
	return $iteminfo;
}

// ###################### getuser ##################
function getuser () {
    global $DB_site, $USERIN, $PASSIN, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$USERIN'");
	while ($row=$DB_site->fetch_array($temps)) {
		if ($PASSIN == $row[password]) {
			$userinfo[userid] = stripslashes($row[userid]);
			$userinfo[username] = stripslashes($row[username]);
			$userinfo[password] = stripslashes($row[password]);
			$userinfo[name] = stripslashes($row[name]);
			$userinfo[address_line1] = stripslashes($row[address_line1]);
			$userinfo[address_line2] = stripslashes($row[address_line2]);
			$userinfo[city] = stripslashes($row[city]);
			$userinfo[state] = stripslashes($row[state]);
			$userinfo[zip] = stripslashes($row[zip]);
			$userinfo[country] = stripslashes($row[country]);
			if ($row[baddress_line1] != "") {
				$userinfo[baddress_line1] = stripslashes($row[baddress_line1]);
			    $userinfo[baddress_line2] = stripslashes($row[baddress_line2]);
			    $userinfo[bcity] = stripslashes($row[bcity]);
			    $userinfo[bstate] = stripslashes($row[bstate]);
			    $userinfo[bzip] = stripslashes($row[bzip]);
			    $userinfo[bcountry] = stripslashes($row[bcountry]);
			} else {
				$userinfo[baddress_line1] = stripslashes($row[address_line1]);
				$userinfo[baddress_line2] = stripslashes($row[address_line2]);
				$userinfo[bcity] = stripslashes($row[city]);
				$userinfo[bstate] = stripslashes($row[state]);
				$userinfo[bzip] = stripslashes($row[zip]);
				$userinfo[bcountry] = stripslashes($row[country]);
			}
			$userinfo[phone] = stripslashes($row[phone]);
			$userinfo[email] = stripslashes($row[email]);
			$userinfo[lastvisit] = stripslashes($row[lastvisit]);
			return $userinfo;
		}
	}
}

// ###################### header stuff #######################
function start ($action) {
    global $title, $css, $usesecureheader, $secureheader, $mysecureheader, $allwidth;
	$js1 = "\n<script language=\"JavaScript\">\n<!--//\nfunction OpenWindowCVV2(sURL) {\nnewwindow = window.open(sURL,\"windowFORM\",\"left=20,top=20,width=255,height=170\")\n}\n//-->\n</script>\n";
	eval("\$js2 = \"".loadtemplate("cc_verification_script")."\";");
	if ($usesecureheader == "Yes") {
		eval("\$secureheader = \"".loadtemplate("secure_header")."\";");
	    eval("\$css = \"".loadtemplate("style_sheet")."\";");
        $secureheader = preg_replace("/<!-- title -->/", $title, stripslashes($secureheader));
	    $secureheader = preg_replace("/<!-- action -->/", ucwords($action), $secureheader);
	    $secureheader = preg_replace("/<!-- css -->/", $css."\n".$js1."\n".$js2, $secureheader);
	    echo $secureheader;
    } else {
	    include("$mysecureheader");
		echo $js1."\n".$js2;
    }
}

// ###################### cc encryption #######################
function cryptit ($cc) {
	$cclen = strlen($cc);
	$encrypted = "";
	for ($i=0;$i<$cclen;$i++) {
	    $digit = substr($cc, $i, 1);
	    if ($digit==0) { $encrypted = $encrypted."NM"; }
		if ($digit==1) { $encrypted = $encrypted."OL"; }
		if ($digit==2) { $encrypted = $encrypted."PK"; }
		if ($digit==3) { $encrypted = $encrypted."QJ"; }
		if ($digit==4) { $encrypted = $encrypted."RI"; }
		if ($digit==5) { $encrypted = $encrypted."SH"; }
		if ($digit==6) { $encrypted = $encrypted."TG"; }
		if ($digit==7) { $encrypted = $encrypted."UF"; }
		if ($digit==8) { $encrypted = $encrypted."VE"; }
		if ($digit==9) { $encrypted = $encrypted."WD"; }
	}
	return $encrypted;
}

// ###################### footer stuff #######################
function showend ($action) {
	global $title, $homeurl, $hometitle, $shopurl, $usesecurefooter, $mysecurefooter, $centercolor, $allwidth;
    if ($usesecurefooter == "Yes") {
	    eval("\$securefooter = \"".loadtemplate("secure_footer")."\";");
	    $securefooter = preg_replace("/<!-- homeurl -->/", $homeurl, $securefooter);
		$securefooter = preg_replace("/<!-- hometitle -->/", $hometitle, $securefooter);
		$securefooter = preg_replace("/<!-- shopurl -->/", $shopurl, $securefooter);
		$securefooter = preg_replace("/<!-- title -->/", $title, $securefooter);
		echo stripslashes($securefooter);
		// DO NOT REMOVE. Failing to do so will result in loss of license.
		print("<div align=\"center\" class=\"small\"><font color=\"Gray\">$title &copy;Copyright ".date(Y)." <a href=\"$homeurl\" class=\"small\">$companyname</a><br>Powered by \"SunShop\"<!--CyKuH [WTN]-->\"</font></div></BODY></HTML>");
		// DO NOT REMOVE.
    } else {
	    include("$mysecurefooter");
    }
}

// ###################### finalcheckout ##################
function finalcheckout ($shipping, $total, $tax, $shipprice, $shipselect, $coupon) {
	global $itemd, $DB_site, $dbprefix, $quantityd, $optionsd, $payopd, $item_tray, $total_items, $savings, $cs, $comments,
	$shopurl, $title, $payment, $visa, $mastercard, $discover, $amex, $options_only, $options_array, $payment, $lang_checkout,
	$lang_ship, $centerborder, $centercolor, $centerfont, $centerheader, $centerbg, $usesecureheader, $fieldsd, $allwidth;
	start("$title - ".$lang_checkout[final]);
	$display_checkout = "<form action=\"checkout.php\" method=\"post\">\n<input type=\"hidden\" name=\"total\" value=\"$total\">\n<input type=\"hidden\" name=\"shipping\" value=\"$shipping\">\n<input type=\"hidden\" name=\"shipping_method\" value=\"$shipselect\">\n<input type=\"hidden\" name=\"tax\" value=\"$tax\">\n<input type=\"hidden\" name=\"coupon\" value=\"$coupon\">\n<input type=\"hidden\" name=\"savings\" value=\"$savings\">\n<input type=\"hidden\" name=\"comments\" value=\"$comments\">";
    $display_checkout .= "
	<input type=\"hidden\" name=\"action\" value=\"validate\">
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">
		<tr>
			<td width=\"10%\" valign=\"top\"><div align=\"center\"><b>$lang_checkout[quantity]</b></div></td>
			<td width=\"40%\" valign=\"top\"><div align=\"center\"><b>$lang_checkout[product]</b></div></td>
			<td width=\"30%\" valign=\"top\"><div align=\"center\"><b>$lang_checkout[options]</b></div></td>
			<td width=\"20%\" valign=\"top\"><div align=\"center\"><b>$lang_checkout[price]</b></div></td>
		</tr>";
		parsecart();
		for ($i = 0; $i < $total_items; $i++) {
		    $iteminfo = iteminfo($itemd[$i]);
			if (isset($iteminfo[sprice])) { 
			  $sprice = $iteminfo[sprice] + $payopd[$i];
			  $dprice = $cs.$sprice;
			  $subtotal = $subtotal + ($sprice * $quantityd[$i]);
			} else { 
			  $price = $iteminfo[price] + $payopd[$i]; 
			  $dprice = $cs.number_format($price,2,".",""); 
			  $subtotal = $subtotal + ($price * $quantityd[$i]); 
			}
			if ($options_array[$i] == "") { $doptions = "None"; }
			else { $doptions = $options_array[$i]; }
			if (strlen($doptions) > 250) {
			   $desc = substr($doptions, 0, 250);
			   $desc = $desc . "...";
		    } else { $desc = $doptions; }
			$display_checkout .= "
			<tr>
				<td width=\"10%\" valign=\"top\"><div align=\"center\">$quantityd[$i]</div></td>
				<td width=\"40%\" valign=\"top\"><div align=\"center\">$iteminfo[title]</div></td>
				<td width=\"30%\" valign=\"top\"><div align=\"left\">$desc</div></td>
				<td width=\"20%\" valign=\"top\"><div align=\"center\">$dprice</div></td>
 	        </tr>";
		}
    $display_checkout .= "
	</table>
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<td width=\"80%\">&nbsp;</td>
			<td width=\"20%\"><hr size=\"1\" color=\"$centercolor\"></td>
		</tr>
		<tr>
			<td width=\"80%\"><div align=\"right\"><b>$lang_ship[tax]:</b></div></td>
			<td width=\"20%\"><div align=\"center\">".$cs.$tax."</div></td>
		</tr>
		<tr>
			<td width=\"80%\"><div align=\"right\"><b>$lang_ship[shipping]:</b></div></td>
			<td width=\"20%\"><div align=\"center\">".$cs.$shipping."</div></td>
		</tr>";
	if ($coupon != "") {
	    $display_checkout .= "
		<tr>
			<td width=\"80%\"><div align=\"right\"><b>$lang_checkout[coupon]:</b></div></td>
			<td width=\"20%\"><div align=\"center\"><font color=\"Red\">".$cs.$savings."</font></div></td>
		</tr>";
	}
	    $display_checkout .= "
		<tr>
			<td width=\"80%\"><div align=\"right\"><b>$lang_checkout[total]:</b></div></td>
			<td width=\"20%\"><div align=\"center\">".$cs.$total."<input type=\"hidden\" name=\"total_final\" value=\"$total\"></div></td>
		</tr>
	</table>
	<hr size=\"1\" color=\"$centercolor\"><br>
	<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">";
	if ($payment != "po") {
	    $display_checkout .= "
	    <tr>
			<td colspan=\"2\" align=\"left\"><b>$lang_checkout[ccinfo]:</b></td>
		</tr>
		<tr>
			<td>$lang_checkout[noc]:</td>
			<td><input type=\"text\" name=\"name_on_card\" size=\"30\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td valign=\"top\">$lang_checkout[cn]:</td>
			<td><input type=\"text\" name=\"CardNumber\" size=\"17\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td>$lang_checkout[ed]:</td>
			<td><select name=\"ExpMon\" class=\"input_box\">";
		if (!isset($ExpMon) || $ExpMon != "") { $display_checkout .= "<option value=\"$ExpMon\">$ExpMon"; }
		for ($i = 1; $i < 13; $i++) {
			if ($i < 10) { $temp = "0".$i; } else { $temp = $i; }
			$display_checkout .= "<option value=\"$temp\">$temp";
		}
		$display_checkout .= "</select> / <select name=\"ExpYear\" class=\"input_box\">";
		if (!isset($ExpYear) || $ExpYear != "") { $display_checkout .= "<option value=\"$ExpYear\">$ExpYear"; }  
		$year = date("Y");
		$to = $year + 10;
		for ($i = $year; $i < $to; $i++) {
			$temp = $i;
			$display_checkout .= "<option value=\"$temp\">$temp";
		}		
		$display_checkout .= "</select>
			</td>
		</tr>
		<tr>
			<td>$lang_checkout[ct]:</td>
			<td><select name=\"CardType\" class=\"input_box\">";
			if ($visa =="Yes") { $display_checkout .= "<option value=\"VisaCard\" selected>Visa"; }
			if ($mastercard =="Yes") { $display_checkout .= "<option value=\"MasterCard\">Mastercard";	}
			if ($amex =="Yes") { $display_checkout .= "<option value=\"AmExCard\">American Express"; }
			if ($discover =="Yes") { $display_checkout .= "<option value=\"DiscoverCard\">Discover"; }
			$display_checkout .= "
			</select></td>
		</tr>
		<tr>
			<td valign=\"top\">$lang_checkout[cvv2]:</td>
			<td><input type=\"text\" name=\"cvv2\" size=\"4\" class=\"input_box\"> <a href=\"javascript:OpenWindowCVV2('".$shopurl."/images/cvv2.gif')\">$lang_checkout[what]</a></td>
		</tr>
		<tr>
		    <td colspan=\"2\" align=\"center\"><br><input type=\"submit\" name=\"Submit\" value=\"$lang_checkout[submit]\" OnClick=\"return CheckCardNumber(this.form)\" class=\"submit_button\">&nbsp;&nbsp;<input type=\"reset\" value=\"$lang_checkout[reset]\" class=\"submit_button\">
			</td>
		</tr>";
	} else {
	    $display_checkout .= "
		<tr>
			<td colspan=\"2\" align=\"left\"><b>$lang_checkout[po]:</b></td>
		</tr>
		<tr>
			<td>$lang_checkout[comp]:</td>
			<td><input type=\text\" name=\"company\" size=\"30\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td>$lang_checkout[ponum]:</td>
			<td><input type=\"text\" name=\"po_number\" size=\"30\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td colspan=\"2\" align=\"center\"><br><input type=\"submit\" name=\"Submit\" value=\"$lang_checkout[submit]\" class=\"submit_button\">&nbsp;&nbsp;<input type=\"reset\" value=\"$lang_checkout[reset]\" class=\"submit_button\"></td>
		</tr>";
	}
	$display_checkout .= "
	</table>
	</div></form>";
	eval("\$checkout_area = \"".loadtemplate("checkout")."\";");
	echo $checkout_area;
}

// ###################### storeinfo #################
function storeinfo($method) {
	global $DB_site, $dbprefix, $item_tray, $total_items, $shopurl, $title, $contactemail, $payment, $payable, 
	$itemd, $quantityd, $optionsd, $payopd, $po_number, $tax, $total, $shipping, $shipping_method, $name_on_card, 
	$CardNumber, $ExpMon, $CardType, $cvv2, $companyname, $payment, $shopfax, $visa, $mastercard, $discover, $amex, 
	$payable, $shopstate, $USERIN, $shopaddress, $shopcity, $ExpYear, $comments, $shopzip, $payable, $options1, 
	$paypal, $paypalemail, $shopimage, $shipselect, $PASSIN, $total_final, $savings, $coupon, $cs, $company, 
	$options_only, $lang_confirm, $lang_email, $lang_screen, $lang_checkout, $centerborder, $centercolor, $centerfont,
	$centerheader, $centerbg, $usesecureheader, $fieldsd, $allwidth;
	
	if(isset($total_final)) {
		$total = $total_final;
	}
	
	$cards = "";
	if ($visa == "Yes") { $cards = "Visa&nbsp;&nbsp;&nbsp;"; }
	if ($mastercard == "Yes") {	$cards = $cards."Mastercard&nbsp;&nbsp;&nbsp;"; }
	if ($amex == "Yes") { $cards = $cards."American Express&nbsp;&nbsp;&nbsp;"; }
	if ($discover == "Yes") { $cards = $cards."Discover&nbsp;&nbsp;&nbsp;"; }
	
	$items = "";
	$quantiy = "";
	$tdate = date("m/d/y");
	
	parsecart();
	for ($i = 0; $i < $total_items; $i++) {
	    $temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$itemd[$i]'");
        while ($row=$DB_site->fetch_array($temps)) {
		    $toto = $row[quantity];
			$so = $row[sold];
		}
	    $quan = $toto - $quantityd[$i];
		$sold = $so + $quantityd[$i];
		if ($quan < 0) { $quan = 0;	}
		$DB_site->query("UPDATE ".$dbprefix."items set sold='$sold', quantity='$quan' where itemid='$itemd[$i]'");
	}
	for ($i = 0; $i < $total_items; $i++) {
	    $iteminfo = iteminfo($itemd[$i]);
		if (isset($iteminfo[sprice])) { 
		  $sprice = $iteminfo[sprice] + $payopd[$i];
		  $dprice = $cs.$sprice;
		} else { 
		  $price = $iteminfo[price] + $payopd[$i]; 
		  $dprice = $cs.number_format($price,2,".",""); 
		}
	    if ($items == "") {
			$items = $itemd[$i];
			$quantity = $quantityd[$i];
			$options = $optionsd[$i];
			$prices = $dprice;
			$fields = $fieldsd[$i];
	    } else {
			$items = $items."->".$itemd[$i];
		    $quantity = $quantity."->".$quantityd[$i];
			$options = $options."->".$optionsd[$i];
			$prices = $prices."->".$dprice;
			$fields = $fields."->".$fieldsd[$i];
		}
	}
	$userinfo = getuser();

	$userid = $userinfo[userid];
	if (!isset($coupon) || $coupon == "") { $coupon = "None"; }
	if ($method == "cc") {
		$DB_site->query("INSERT INTO ".$dbprefix."transaction VALUES (NULL,'$userid','".addslashes($items)."','".addslashes($quantity)."','".addslashes($tdate)."','".addslashes($shipping_method)."','".addslashes($shipping)."','".addslashes($total)."', 'Credit Card','".strip_tags(addslashes($name_on_card))."','".strip_tags(addslashes(cryptit($CardNumber)))."','".strip_tags(addslashes($ExpMon))." ".strip_tags(addslashes($ExpYear))."','".strip_tags(addslashes($CardType))."','".strip_tags(addslashes($cvv2))."','Pending Approval','Pending','$options','$prices','$fields','".strip_tags(addslashes($comments))."','".strip_tags(addslashes($coupon))." - $savings')");
	} elseif ($method == "po") {
		$DB_site->query("INSERT INTO ".$dbprefix."transaction VALUES (NULL,'$userid','".addslashes($items)."','".addslashes($quantity)."','".addslashes($tdate)."','".addslashes($shipping_method)."','".addslashes($shipping)."','".addslashes($total)."', 'Purchase Order','".strip_tags(addslashes($company))."','P.O. Number - ".strip_tags(addslashes($po_number))."','N/A','N/A','N/A','Pending Approval','Pending', '$options', '$prices','$fields', '".strip_tags(addslashes($comments))."', '".strip_tags(addslashes($coupon))." - $savings')");
	} else {
		$DB_site->query("INSERT INTO ".$dbprefix."transaction VALUES (NULL,'$userid','".addslashes($items)."','".addslashes($quantity)."','".addslashes($tdate)."','".addslashes($shipselect)."','".addslashes($shipping)."','".addslashes($total)."','$payment','N/A','N/A','N/A','N/A','N/A','Awaiting Payment','Pending','$options','$prices','$fields','".strip_tags(addslashes($comments))."', '".strip_tags(addslashes($coupon))." - $savings')");
	}
		
	$temp12=$DB_site->query("SELECT * FROM ".$dbprefix."transaction where userid='$userid' ORDER by orderid desc LIMIT 0,1");
	$row12=$DB_site->fetch_array($temp12);
	$tran = $row12[orderid];
	
	if ($savings == "") { $lan1= $lang_email[part1ns]; } else { $lan1= $lang_email[part1]; }
	
	$output = $lan1;
	$output = preg_replace("/<!-- title -->/", $title, $output);
	$output = preg_replace("/<!-- savings -->/", $savings, $output);
	$output = preg_replace("/<!-- currency -->/", $cs, $output);
	$output = preg_replace("/<!-- userid -->/", $userinfo[userid], $output);
	$output = preg_replace("/<!-- transaction -->/", $tran, $output);
	$output = preg_replace("/<!-- total -->/", $total, $output);
    $message = $output;
	
	$message .= "$lang_confirm[out1] | $lang_confirm[out2] | $lang_confirm[out3] | $lang_confirm[out4] | $lang_confirm[out5]\n";
	for ($i = 0; $i < $total_items; $i++) {
	    $iteminfo = iteminfo($itemd[$i]);
		if (isset($iteminfo[sprice])) { 
		  $sprice = $iteminfo[sprice] + $payopd[$i];
		  $dprice = $cs.$sprice;
		} else { 
		  $price = $iteminfo[price] + $payopd[$i]; 
		  $dprice = $cs.number_format($price,2,".",""); 
		}
	    $message .= $iteminfo[num]." | ".$iteminfo[title]." | ".$quantityd[$i]." | ".$optionsd[$i]." | ".$dprice."\n";
	}
	
	if ($method == "cc") {
	    $output = $lang_email[part2_cc];
		$output = preg_replace("/<!-- shopurl -->/", $shopurl, $output);
		$message .= "\n".$output;
	} elseif ($method == "po") {
	    $output = $lang_email[part2_po];
		$output = preg_replace("/<!-- shopurl -->/", $shopurl, $output);
	    $message .= "\n".$output;
	} elseif ($method == "fax") {
	    $output = $lang_email[part2_fax];
		$output = preg_replace("/<!-- shopfax -->/", $shopfax, $output);
		$output = preg_replace("/<!-- shopurl -->/", $shopurl, $output);
		$message .= "\n".$output;
	} elseif ($method == "cm") {
	    $output = $lang_email[part2_cm];
		$output = preg_replace("/<!-- payment -->/", $payment, $output);
		$output = preg_replace("/<!-- payable -->/", $payable, $output);
		$output = preg_replace("/<!-- companyname -->/", $companyname, $output);
		$output = preg_replace("/<!-- shopaddress -->/", $shopaddress, $output);
		$output = preg_replace("/<!-- shopcity -->/", $shopcity, $output);
		$output = preg_replace("/<!-- shopstate -->/", $shopstate, $output);
		$output = preg_replace("/<!-- shopzip -->/", $shopzip, $output);
		$output = preg_replace("/<!-- shopurl -->/", $shopurl, $output);
		$message .= "\n".$output;
	} else {
	    $output = $lang_email[part2_paypal];
		$output = preg_replace("/<!-- paypalemail -->/", $paypalemail, $output);
		$output = preg_replace("/<!-- shopurl -->/", $shopurl, $output);
	    $message .= "\n".$output;
	}
	$message .= $lang_email[part3];
	$output = $lang_email[part4];
	$output = preg_replace("/<!-- companyname -->/", $companyname, $output);
	$output = preg_replace("/<!-- contactemail -->/", $contactemail, $output);
	$message .= $output;
    
	$output = $lang_email[subject];
	$output = preg_replace("/<!-- title -->/", $title, $output);
	mail($userinfo[email], $output, $message, "From: \"".$title."\" <" . $contactemail . ">");
	$output = $lang_email[admin_subject];
	$output = preg_replace("/<!-- title -->/", $title, $output);
	$output2 = $lang_email[admin_body];
	$output2 = preg_replace("/<!-- shopurl -->/", $shopurl, $output2);
	mail($contactemail, $output, $output2.$message, "From: \"".$title."\" <" . $contactemail . ">");
    
	start("$title - ".$lang_screen[title]);
	
	$message = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" align=\"center\"><tr><td>"; 
	
	if ($savings == "") { $lan1= $lang_screen[part1ns]; } else { $lan1= $lang_screen[part1]; }
	
	$output = $lan1;
	$output = preg_replace("/<!-- title -->/", $title, $output);
	$output = preg_replace("/<!-- savings -->/", $savings, $output);
	$output = preg_replace("/<!-- currency -->/", $cs, $output);
	$output = preg_replace("/<!-- userid -->/", $userinfo[userid], $output);
	$output = preg_replace("/<!-- transaction -->/", $tran, $output);
	$output = preg_replace("/<!-- total -->/", $total, $output);
    $message .= $output;
	
	$message .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr><td><b>$lang_confirm[out1]</b></td><td><b>$lang_confirm[out2]</b></td><td><b>$lang_confirm[out3]</b></td><td><b>$lang_confirm[out4]</b></td><td><b>$lang_confirm[out5]</b></td></tr>";
	for ($i = 0; $i < $total_items; $i++) {
	    $iteminfo = iteminfo($itemd[$i]);
		if (isset($iteminfo[sprice])) { 
		  $sprice = $iteminfo[sprice] + $payopd[$i];
		  $dprice = $cs.$sprice;
		} else { 
		  $price = $iteminfo[price] + $payopd[$i]; 
		  $dprice = $cs.number_format($price,2,".",""); 
		}
	    $message .= "<tr><td valign=\"top\">".$iteminfo[num]."</td><td valign=\"top\">".$iteminfo[title]."</td><td valign=\"top\">".$quantityd[$i]."</td><td valign=\"top\">".$optionsd[$i]."</td><td valign=\"top\">".$dprice."</td></tr>";
	}
	
	if ($method == "cc") {
		$output = $lang_screen[part2_cc];
		$output = preg_replace("/<!-- shopurl -->/", $shopurl, $output);
		$message .= "</table><br>".$output;
	} elseif ($method == "po") {
	    $output = $lang_screen[part2_po];
		$output = preg_replace("/<!-- shopurl -->/", $shopurl, $output);
	    $message .= "</table><br>".$output;
	} elseif ($method == "fax") {
	    $output = $lang_screen[part2_fax2];
		$output = preg_replace("/<!-- shopurl -->/", $shopurl, $output);
		$output2 = $lang_screen[part2_fax1];
		$output2 = preg_replace("/<!-- shopfax -->/", $shopfax, $output2);
		$message .= "</table><br>".$output2."<br><center>".$cards."</center>
		<br><table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
			<tr><td><b>$lang_screen[part2_fax_out1]:</b></td><td>____________________________________</td></tr>
			<tr><td><b>$lang_screen[part2_fax_out2]:</b></td><td>____________________________________</td></tr>
			<tr><td><b>$lang_screen[part2_fax_out3]:</b></td><td>_____________________</td></tr>
			<tr><td><b>$lang_screen[part2_fax_out4]:</b></td><td>_____________________</td></tr>
			<tr><td><b>$lang_screen[part2_fax_out5]:</b></td><td>__________ (<a href=\"javascript:OpenWindowCVV2('".$shopurl."images/cvv2.gif')\">$lang_screen[part2_fax_out7]</a>)</td></tr>
			<tr><td><b>$lang_screen[part2_fax_out6]:</b></td><td>____________________________________</td></tr>
		</table>
		<br><br>".$output;
	} elseif ($method == "cm") {
	    $output = $lang_screen[part2_cm];
		$output = preg_replace("/<!-- payment -->/", $payment, $output);
		$output = preg_replace("/<!-- payable -->/", $payable, $output);
		$output = preg_replace("/<!-- companyname -->/", $companyname, $output);
		$output = preg_replace("/<!-- shopaddress -->/", $shopaddress, $output);
		$output = preg_replace("/<!-- shopcity -->/", $shopcity, $output);
		$output = preg_replace("/<!-- shopstate -->/", $shopstate, $output);
		$output = preg_replace("/<!-- shopzip -->/", $shopzip, $output);
		$output = preg_replace("/<!-- shopurl -->/", $shopurl, $output);
		$message .= "</table><br>".$output;
	} else {
	    $output = $lang_screen[part2_paypal1];
		$output = preg_replace("/<!-- paypalemail -->/", $paypalemail, $output);
		$output2 = $lang_screen[part2_paypal2];
		$output2 = preg_replace("/<!-- shopurl -->/", $shopurl, $output2);
	    $message .= "</table><br>".$output."<br><br><div align=\"center\">
		<form method=\"post\" action=\"https://secure.paypal.com/cgi-bin/webscr\">
		<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
		<input type=\"hidden\" name=\"business\" value=\"".$paypalemail."\">
		<input type=\"hidden\" name=\"item_name\" value=\"".$title." Transaction #: ".$tran."\">
		<input type=\"hidden\" name=\"item_number\" value=\"User ID: ".$userid."\">
		<input type=\"hidden\" name=\"amount\" value=\"".$total."\">
		<input type=\"hidden\" name=\"return\" value=\"".$shopurl."\">
		<input type=\"hidden\" name=\"cancel_return\" value=\"".$shopurl."\">
		<input type=\"image\" src=\"https://www.paypal.com/images/x-click-but03.gif\" name=\"submit\" alt=\" Checkout Now \">
		</form>".$lang_screen[part2_paypal3]."</div><br>".$output2;
	}
	$output = $lang_screen[part4];
	$output = preg_replace("/<!-- companyname -->/", $companyname, $output);
	$output = preg_replace("/<!-- contactemail -->/", $contactemail, $output);
	$message .= $lang_screen[part3];
	$message .= $output."<br>";
	$display_checkout = $message;
	eval("\$checkout_area = \"".loadtemplate("checkout")."\";");
	echo $checkout_area."\n</tr></td></table>";
	session_unset();
    session_destroy();
}

if ($action == "validate") {
    if ($payment == "Credit Card") {
		storeinfo("cc");
	} else {
		storeinfo("po");
	}
}

if ($action == "") {
	if ($do == "addship") {
	    $shipping = $shipprice[$shipselect];
		$total = $shipping + $total;
	}
	
    if ($coupon != "") {
		$temp=$DB_site->query("SELECT * FROM ".$dbprefix."coupons where couponid='$coupon'");
	    $row=$DB_site->fetch_array($temp);
		if ($row[discount] != "") {
			if ($row[type] == "D") {
				$savings = number_format($row[discount],2,".","");
			} else {
				$savings = number_format($total * $row[discount],2,".","");
			}
			$total = $total - $savings;
		} else{
		    $savings = "0.00";
			$total = $total;
		}
		$update = $row[sold] + 1;
		$DB_site->query("UPDATE ".$dbprefix."coupons set sold='$update' where couponid='$coupon'");
	}
	$total = number_format($total,2,".","");
	
	if ($payment == "Credit Card" || $payment == "po") {
		finalcheckout($shipping, $total, $tax, $shipprice, $shipselect, $coupon);
	}
	if ($payment == "Personal Check" || $payment == "Money Order") {
		storeinfo("cm");
	}
	if ($payment == "Fax Order") {
		storeinfo("fax");
	}
	if ($payment == "PayPal") {
		storeinfo("pp");
	}
}

showend($action);
<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.6
// Supplied by          : Stive [WTN], CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
require("admin/config.php");
include("admin/state-country.php");

// ###################### Start Database #######################
$dbclassname="admin/db_mysql.php";
require($dbclassname);

$DB_site=new DB_Sql_vb;
$DB_site->appname="SunShop";
$DB_site->appshortname="SunShop";
$DB_site->database=$dbname;
$DB_site->server=$servername;
$DB_site->user=$dbusername;
$DB_site->password=$dbpassword;
$DB_site->connect();

$alreadydb = 1;

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
	$css = stripslashes($row[css]);
	$thumbwidth = stripslashes($row[thumbwidth]);
	$thumbheight = stripslashes($row[thumbheight]);
	$picwidth = stripslashes($row[picwidth]);
	$picheight = stripslashes($row[picheight]);
	$showstock = stripslashes($row[showstock]);
	$showitem = stripslashes($row[showitem]);
	$showintro = stripslashes($row[showintro]);
	$shopintro = stripslashes($row[shopintro]);
	$orderby = stripslashes($row[orderby]);
	$outofstock = stripslashes($row[outofstock]);
	$cs = stripslashes($row[cs]);
	$showprice = stripslashes($row[showprice]);
	$po = stripslashes($row[po]);
	$handling = stripslashes($row[handling]);
	$imagel = stripslashes($row[imagel]);
}

// ###################### parsecart #######################
function parsecart () {
    global $total_items, $item_tray, $itemd, $quantityd, $optionsd, $payopd, $options_only, $options_array;
    for ($i = 0; $i < $total_items; $i++) {
		$temparray = explode("-", $item_tray[$i]);
		$options_array = explode("-", $options_only);
        $itemd[$i] = $temparray[0];
		$quantityd[$i] = $temparray[1];
		$optionsd[$i] = $temparray[2];
		$payopd[$i] = $temparray[3];
	}
}

// ###################### updatecart #######################
if ($action == "updatecart") { 
    $index = 0;
	parsecart();
	$new_op == "";
    for ($i = 0; $i < $total_items; $i++) {
	    $tmp = $item[$i];
	    if ($tmp == "0" || $tmp == "") {
		   continue;
		} else {
	      $it[$index] = $itemd[$i];
		  $hm[$index] = $tmp;
		  $op[$index] = $optionsd[$i]; 
		  $pop[$index] = $payopd[$i];
		  $index++;
		}
		setcookie("item_tray[$i]", "");
	}
	setcookie("item_tray", "");
	setcookie("total_items", "");
	setcookie("options_only", "");
	for ($i = 0; $i < $index; $i++) {
	    setcookie("item_tray[$i]", $it[$i]."-".$hm[$i]."-".$op[$i]."-".$pop[$i]);
		$new_op = $new_op.$op[$i]."-";
	}
	setcookie("total_items", $index);
	setcookie("options_only", $new_op);
	header("Location:index.php?action=viewcart");
    exit;
}

// ###################### count subcategories #######################
function countsubcat ($id) {
    global $DB_site, $dbprefix;
    $count = 0;
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."subcategory where categoryid='$id'");
	while ($row=$DB_site->fetch_array($temp)) {
		$count++;
	}
	return $count;
}

// ###################### show categories #######################
function showcategories ($start, $id, $subid) {
    global $DB_site, $catimage, $catopen, $dbprefix;
	$startt = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td valign=\"top\">";
	$midt = "</td><td valign=\"top\">";
	$endt = "</td></tr></table>";
	$num = 0;
	if ($start == "") { $start = 0; } 
	if ($id != "") {
	    if ($id == "specials") {
		   print("$startt&nbsp;&nbsp;$midt<img src=\"$catopen\" border=\"0\" alt=\"\">&nbsp;$midt<a href=\"index.php?action=category&id=specials\">Specials</a>$endt");
		}  else {
		   print("$startt&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=specials\"><img src=\"$catimage\" border=\"0\" alt=\"\"></a>&nbsp;$midt<a href=\"index.php?action=category&id=specials\">Specials</a>$endt");
		}
		if ($id == "bestsellers") {
		   print("$startt&nbsp;&nbsp;$midt<img src=\"$catopen\" border=\"0\" alt=\"\">&nbsp;$midt<a href=\"index.php?action=category&id=bestsellers\">Bestsellers</a>$endt");
		}  else {
		   print("$startt&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=bestsellers\"><img src=\"$catimage\" border=\"0\" alt=\"\"></a>&nbsp;$midt<a href=\"index.php?action=category&id=bestsellers\">Bestsellers</a>$endt");
		}
		$temp1=$DB_site->query("SELECT * FROM ".$dbprefix."category ORDER by displayorder");
		while ($row1=$DB_site->fetch_array($temp1)) {
	        $totitems = gettotal ($row1[categoryid], "");
			if ($row1[categoryid] == $id) {
				print("$startt&nbsp;&nbsp;$midt<img src=\"$catopen\" border=\"0\" alt=\"\">&nbsp;$midt<a href=\"index.php?action=category&id=$row1[categoryid]\">$row1[stitle] ($totitems)</a>$endt");
				$total = countsubcat($row1[categoryid]);
				$temp2=$DB_site->query("SELECT * FROM ".$dbprefix."subcategory where categoryid='$row1[categoryid]' ORDER by displayorder LIMIT $start,10");
				while ($row2=$DB_site->fetch_array($temp2)) {
				    $totsitems = gettotal ($row1[categoryid], $row2[subcategoryid]);
				    $num++;
				    if ($row2[subcategoryid] == $subid) {
						print("$startt&nbsp;&nbsp;$midt<img src=\"images/sub.gif\" border=\"0\" alt=\"\"><img src=\"$catopen\" border=\"0\" alt=\"\">&nbsp;$midt<a href=\"index.php?action=category&subid=$row2[subcategoryid]&id=$row2[categoryid]\">$row2[stitle] ($totsitems)</a>$endt");
					} 
					else {
						print("$startt&nbsp;&nbsp;$midt<img src=\"images/sub.gif\" border=\"0\" alt=\"\"><img src=\"$catimage\" border=\"0\" alt=\"\">&nbsp;$midt<a href=\"index.php?action=category&subid=$row2[subcategoryid]&id=$row2[categoryid]\">$row2[stitle] ($totsitems)</a>$endt");
					}
				}
				if ($start > 0) {
				    $pre = $start - 10;
					if ($pre < 0) { $pre = 0; }
					print("$startt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=$id&substart=$pre\"><i>previous</i></a>$endt");
				} 
				$next = $start + 10;
				if ($total > $next) {
					print("$startt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=$id&substart=$next\"><i>next</i></a>$endt");
				}
			} 
			else {
			    print("$startt&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=$row1[categoryid]\"><img src=\"$catimage\" border=\"0\" alt=\"\"></a>&nbsp;$midt<a href=\"index.php?action=category&id=$row1[categoryid]\">$row1[stitle] ($totitems)</a>$endt");   
			}
		}
	} else {
		print("$startt&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=specials\"><img src=\"$catimage\" border=\"0\" alt=\"\"></a>&nbsp;$midt<a href=\"index.php?action=category&id=specials\">Specials</a>$endt");
		print("$startt&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=bestsellers\"><img src=\"$catimage\" border=\"0\" alt=\"\"></a>&nbsp;$midt<a href=\"index.php?action=category&id=bestsellers\">Bestsellers</a>$endt");
		$temp1=$DB_site->query("SELECT * FROM ".$dbprefix."category ORDER by displayorder");
		while ($row1=$DB_site->fetch_array($temp1)) {
		    $totitems = gettotal ($row1[categoryid], "");
			print("$startt&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=$row1[categoryid]\"><img src=\"$catimage\" border=\"0\" alt=\"\"></a>&nbsp;$midt<a href=\"index.php?action=category&id=$row1[categoryid]\">$row1[stitle] ($totitems)</a>$endt");
		}
	}
	?>
		</td>
			</tr>
		</table>
	</td>
	<td width="5">&nbsp;</td>
	<td align="center" valign="top" width="440">
    <?PHP
}

// ###################### additem #######################
function additem ($item, $num, $option1, $option2, $option3) {
    global $total_items, $item_tray, $options_only, $options_array, $outofstock;
	if ($outofstock == "No") {
	    $iteminfo = iteminfo($item);
		if ($num > $iteminfo[quantity]) {
			header("Location:index.php?action=viewcart&error=outofstock");
			exit;
		}
	}
    if ($total_items == 15) {
		header("Location:index.php?action=viewcart&error=cartfull");
	} else {
	    if ($option1 == "" && $option2 == "" && $option3 == "") {
		    $load = "None";
			$load2 = 0;
	    } else {
	        if ($option1 != "") { 
			   $addp = payopcalc($item, $option1);
			   $load = $option1; 
			   $load2 = $addp;
		    }
		    if ($option2 != "") { $load = $load.", ".$option2; }
		    if ($option3 != "") { $load = $load.", ".$option3; }
	    }
		setcookie("item_tray[$total_items]", $item."-".$num."-".$load."-".$load2);
		setcookie("options_only", $options_only.$load."-");
		$total_items = $total_items + 1;
		setcookie("total_items", $total_items);
		header("Location:index.php?action=viewcart");
	    exit;
	}
}

// ###################### showtemplate ##################
function showtemplate ($temp) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."template where name='$temp'");
    while ($row=$DB_site->fetch_array($temps)) {
		print("$row[temp]");	
	}
}

// ###################### authenticate ##################
function authenticate ($login, $password) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$login'");
    while ($row=$DB_site->fetch_array($temps)) {
		if ($password == $row[password]) {
		   $result = "valid";
		   setcookie("USER_IN", $login);
		   setcookie("PASS_IN", $password);
		} else {
		   $result = "invalid";
		}
		return $result;
	}
	$result = "invalid";
	return $result;
}

// ###################### authenticate2 ##################
function authenticate2 ($login, $password) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$login'");
    while ($row=$DB_site->fetch_array($temps)) {
		if ($password == $row[password]) {
		   $result = "valid";
		} else {
		   $result = "invalid";
		}
		return $result;
	}
	$result = "invalid";
	return $result;
}

// ###################### getuser ##################
function getuser () {
    global $DB_site, $USER_IN, $PASS_IN, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$USER_IN'");
    while ($row=$DB_site->fetch_array($temps)) {
		if ($PASS_IN == $row[password]) {
			$userinfo[userid] = $row[userid];
			$userinfo[username] = $row[username];
			$userinfo[password] = $row[password];
			$userinfo[name] = $row[name];
			$userinfo[address_line1] = $row[address_line1];
			$userinfo[address_line2] = $row[address_line2];
			$userinfo[city] = $row[city];
			$userinfo[state] = $row[state];
			$userinfo[zip] = $row[zip];
			$userinfo[country] = $row[country];
			$userinfo[baddress_line1] = $row[baddress_line1];
			$userinfo[baddress_line2] = $row[baddress_line2];
			$userinfo[bcity] = $row[bcity];
			$userinfo[bstate] = $row[bstate];
			$userinfo[bzip] = $row[bzip];
			$userinfo[bcountry] = $row[bcountry];
			$userinfo[phone] = $row[phone];
			$userinfo[email] = $row[email];
			$userinfo[lastvisit] = $row[lastvisit];
			return $userinfo;
		}
	}
}

// ###################### applytax ##################
function applytax (){
    global $DB_site, $shopstate, $USER_IN, $taxrate, $dbprefix;
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$USER_IN'");
	while ($row=$DB_site->fetch_array($temp)) {
	     $state = $row[state];
	}
	if($state == $shopstate) {
	    $subtotal = subtotal();
		$tax = ($subtotal * $taxrate);
		$tax = number_format($tax,2,".","");
		return $tax;
	}
	$tax = "0.00";
	return $tax;
}

// ###################### iteminfo ##################
function iteminfo ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$id'");
    while ($row=$DB_site->fetch_array($temps)) {
		$iteminfo[categoryid] = $row[categoryid]; $iteminfo[title] = stripslashes($row[title]);
		$iteminfo[stitle] = stripslashes($row[stitle]); $iteminfo[imagename] = $row[imagename];
		$iteminfo[thumb] = $row[thumb]; $iteminfo[poverview] = stripslashes($row[poverview]);
		$iteminfo[pdetails] = stripslashes($row[pdetails]); $iteminfo[quantity] = $row[quantity];
		$iteminfo[sold] = $row[sold]; $iteminfo[shipinfo] = $row[shipinfo];
		$iteminfo[price] = $row[price];	$iteminfo[weight] = $row[weight];
		$iteminfo[viewable] = $row[viewable]; $iteminfo[option1] = $row[option1];
		$iteminfo[option12] = $row[option12]; $iteminfo[option2] = $row[option2];
		$iteminfo[option22] = $row[option22]; $iteminfo[option3] = $row[option3];
		$iteminfo[option32] = $row[option32]; $iteminfo[payop] = $row[payop];
		$iteminfo[itemid] = $row[itemid]; $iteminfo[sku] = $row[sku];
	}
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."specials where itemid='$id'");
    while ($row=$DB_site->fetch_array($temp)) {
		$iteminfo[specialid] = $row[specialid]; $iteminfo[sdescription] = stripslashes($row[sdescription]); 
		$iteminfo[sprice] = $row[sprice];
		return $iteminfo;
	}
	return $iteminfo;
}

// ###################### calculateweight #####################
function calculateweight () {
    global $item_tray, $total_items, $itemd, $quantityd;
	$weight = 0;
	parsecart();
    for ($i = 0; $i < $total_items; $i++) {
	    $iteminfo = iteminfo($itemd[$i]);
		$w = $iteminfo[weight];
		$wi = ($w * $quantityd[$i]);
		$weight = $wi + $weight;
	}
	return $weight;
}

// ###################### upsquote #####################
function upsquote ($pro, $orzip, $orcount, $deszip, $descount, $rate, $weight) {
    $rate = new Ups;
    $rate->upsProduct("$pro");
    $rate->origin("$orzip", "$orcount");
    $rate->dest("$deszip", "$descount");
    $rate->rate("$rate");
    $rate->container("00");
    $rate->weight("$weight");
    $rate->rescom("RES");
    $quote = $rate->getQuote();
	return $quote;
}

// ###################### payop #####################
function payop ($id) {
	global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
		$iarray = explode("-", $row[payop]);
		return $iarray;
	}
}

// ###################### payopcalc #####################
function payopcalc ($id, $option) {
	global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    if ($row[payop] != "") {
			$iarray = explode("-", $row[payop]);
			$iarray2 = explode("-", $row[option12]);
		} else {
		    return 0;
		}
	}
	for ($i=0; $i< count($iarray2); $i++) {
		if ($option == $iarray2[$i]) {
			$addprice = $iarray[$i];
			return $addprice;
		}
	}
	return 0;
}

// ###################### getshipping #####################
function getshipping () {
	global $method, $total_items, $shoprate, $shipups, $grnd, $nextdayair, $seconddayair, $threeday, $canada, $worldwidex, $worldwidexplus, $shopzip, $shopcountry, $shipchart, $ship1p1, $ship1us, $ship1ca, $ship2, $ship2p1, $ship2p2, $ship2us, $ship2ca, $ship3, $ship3p1, $ship3p2, $ship3us, $ship3ca, $ship4p1, $ship4us, $ship4ca, $handling;
	require("shipping.php");
	$userinfo = getuser();
	$weight = calculateweight();
	if ($shipups == "Yes") {
	    $shiprate[method] = "ups";
		if ($grnd == "Yes") {
		   $rate = upsquote("GND", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "Customer Counter", $weight);
		   if (isset($rate) && $rate != "The requested service is unavailable between the selected locations.") {
			   $shiprate[grnd] = $rate + $handling;
		   } else { $shiprate[grnd] = $rate; }
	    }
		if ($nextdayair == "Yes") {
		   $rate = upsquote("1DA", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "Customer Counter", $weight);
		   if (isset($rate) && $rate != "The requested service is unavailable between the selected locations.") {
			   $shiprate[nextdayair] = $rate + $handling;
		   } else { $shiprate[nextdayair] = $rate; }
	    }
		if ($seconddayair == "Yes") {
		   $rate = upsquote("2DA", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "Customer Counter", $weight);
		   if (isset($rate) && $rate != "The requested service is unavailable between the selected locations.") {
			   $shiprate[seconddayair] = $rate + $handling;
		   } else { $shiprate[seconddayair] = $rate; }
	    }
		if ($threeday == "Yes") {
		   $rate = upsquote("3DS", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "Customer Counter", $weight);
		   if (isset($rate) && $rate != "The requested service is unavailable between the selected locations.") {
			   $shiprate[threeday] = $rate + $handling;
		   } else { $shiprate[threeday] = $rate; }
	    }
		if ($canada == "Yes") {
		   $rate = upsquote("STD", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "Customer Counter", $weight);
		   if (isset($rate) && $rate != "The requested service is unavailable between the selected locations.") {
			   $shiprate[canada] = $rate + $handling;
		   } else { $shiprate[canada] = $rate; }
	    }
		if ($worldwidex == "Yes") {
		   $rate = upsquote("XPR", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "Customer Counter", $weight);
		   if (isset($rate) && $rate != "The requested service is unavailable between the selected locations.") {
			   $shiprate[worldwidex] = $rate + $handling;
		   } else { $shiprate[worldwidex] = $rate; }
	    }
		if ($worldwidexplus == "Yes") {
		   $rate = upsquote("XDM", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "Customer Counter", $weight);
		   if (isset($rate) && $rate != "The requested service is unavailable between the selected locations.") {
			   $shiprate[worldwidexplus] = $rate + $handling;
		   } else { $shiprate[worldwidexplus] = $rate; }
	    }
		return $shiprate;
	} 
	elseif ($shipchart == "Yes") {
	    $shiprate[method] = "chart";
	    $subtotal = subtotal();
		if ($subtotal <= $ship1p1) {
		    if ($userinfo[country] == "US") {
			    $shiprate[price] = $ship1us + $handling;
				return $shiprate;
			} else { 
				$shiprate[price] = $ship1ca + $handling;
				return $shiprate;
			}
		}
		if ($ship2 == "Yes") {
			if ($subtotal > $ship2p1 && $subtotal < $ship2p2) {
				if ($userinfo[country] == "US") {
					$shiprate[price] = $ship2us + $handling;
				    return $shiprate;
				} else { 
				    $shiprate[price] = $ship2ca + $handling;
				    return $shiprate;
				}
			}
		}
		if ($ship3 == "Yes") {
			if ($subtotal > $ship3p1 && $subtotal < $ship3p2) {
				if ($userinfo[country] == "US") {
					$shiprate[price] = $ship3us + $handling;
				    return $shiprate;
				} else { 
				    $shiprate[price] = $ship3us + $handling;
				    return $shiprate; 
				}
			}
		}
		if ($subtotal >= $ship4p1) {
		    if ($userinfo[country] == "US") {
				$shiprate[price] = $ship4us + $handling;
				return $shiprate;
			} else { 
			    $shiprate[price] = $ship4ca + $handling;
				return $shiprate; 
			}
		}
	}
	else {
	    $shiprate[method] = "fixed";
		if ($method == "perorder") {
			$shiprate[price] = $shoprate + $handling;
			return $shiprate;
		}
		if ($method == "perpound") {
			$rate = ($weight * $shoprate);
			$shiprate[price] = $rate + $handling;
			return $shiprate;
		}
		if ($method == "peritem") {
			$rate = ($total_items * $shoprate);
			$shiprate[price] = $rate + $handling;
			return $shiprate;
		}
	}
}

// ###################### selectshipping ##################
function selectshipping () {
	global $item_tray, $total_items, $itemd, $quantityd, $optionsd, $payopd, $securepath, 
	$shopurl, $USER_IN, $PASS_IN, $shipchart, $check, $fax, $moneyorder, $cc, $options, 
	$paypal, $payopp, $centerborder, $options_only, $options_array, $cs, $po; 
	$shiprate = getshipping();
	?>
	<form action="<?PHP echo $securepath ?>checkout.php" method="post">
	<table width="440" border="<?PHP if ($centerborder == "0") { print("1"); } else { print("0"); }?>" cellspacing="0" cellpadding="0">
		<tr>
			<td><table width="440" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>
						<table>
							<tr>
								<td width="50"><div align="center"><img src="images/quantity.gif" border="0" alt=""></div></td>
								<td width="190"><div align="center"><img src="images/product.gif" border="0" alt=""></div></td>
								<td width="130"><div align="center"><img src="images/options.gif" border="0" alt=""></div></td>
								<td width="70"><div align="center"><img src="images/unitprice.gif" border="0" alt=""></div></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						<table border="0">
						<?PHP
						$subtotal = "0.00";
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
							print("<tr>
								<td width=\"50\" align=\"center\">$quantityd[$i]</td>
								<td width=\"190\" align=\"center\">$iteminfo[title]</td>
								<td width=\"130\" align=\"center\">$doptions</td>
								<td width=\"70\" align=\"center\">$dprice</td>
							</tr>");
						}
						$tax = applytax($USER_IN);
						$tot = $tax + $subtotal;
						$tot = number_format($tot,2,".","");
						?>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						    <table>
								<tr>
									<td width="365">&nbsp;</td>
									<td width="71"><hr size=1></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><table>
								<tr>
									<td width="365"><div align="right"><strong>Tax:</strong></div></td>
									<td width="71" colspan="2"><div align="center"><?PHP echo $cs.$tax ?></div></td>
									<input type="hidden" name="tax" value="<?PHP echo $tax ?>">
								</tr>
								<?PHP
								if ($shiprate[method] == "chart") {
								$sprice1 = number_format($shiprate[price],2,".","");
								?>
								<tr>
									<td width="365"><div align="right"><strong>Shipping:</strong></div></td>
									<td width="71" colspan="2"><div align="center"><?PHP echo $cs.$sprice1 ?></div></td>
									<input type="hidden" name="shipping" value="<?PHP echo $sprice1 ?>">
								</tr>
								<?PHP
								}
								if ($shiprate[method] == "fixed") {
								$sprice1 = number_format($shiprate[price],2,".","");
								?>
								<tr>
									<td width="365"><div align="right"><strong>Shipping:</strong></div></td>
									<td width="71" colspan="2"><div align="center"><?PHP echo $cs.$sprice1 ?></div></td>
									<input type="hidden" name="shipping" value="<?PHP echo $sprice1 ?>">
								</tr>
								<?PHP
								}
								?>
							</table>
						</td>
					</tr>
					<tr>
						<td><table>
						        <?PHP
								if ($shiprate[method] == "fixed" || $shiprate[method] == "chart") {
								$tot = $tot + $shiprate[price];
								$tot = number_format($tot,2,".","");
								?>
								<tr>
									<td width="365"><div align="right"><strong>Total:</strong></div></td>
									<td width="71" colspan="2"><div align="center"><?PHP echo $cs.$tot ?></div></td>
									<input type="hidden" name="total" value="<?PHP echo $tot ?>">
								</tr>
								<?PHP
								} else {
								?>
								<tr>
									<td width="365"><div align="right"><strong>Subtotal:</strong></div></td>
									<td width="71" colspan="2"><div align="center"><?PHP echo $cs.$tot ?></div></td>
									<input type="hidden" name="total" value="<?PHP echo $tot ?>">
									<input type="hidden" name="do" value="addship">
								</tr>
								<?PHP
								}
								?>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table> 
	<?PHP
	if ($centerborder == "1") { print("<hr width=\"100%\">"); }
	$userinfo = getuser();
	print("<div align=\"left\">
	           <table width=\"100%\">
					<tr>");
	if ($shiprate[method] == "ups") {
		print("<td width=\"50%\" align=\"left\" valign=\"top\"><strong>Shipping Method:</strong><br>");
		if ($shiprate[grnd] != "Invalid ConsigneePostalCode" || $shiprate[seconddayair] != "Invalid ConsigneePostalCode") { 
			if (isset($shiprate[grnd]) && $shiprate[grnd] != "The requested service is unavailable between the selected locations.") {
				print("&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"Ground\" checked>Ground&nbsp;&nbsp;(<strong>$cs$shiprate[grnd]</strong>)<br>
				<input type=\"hidden\" name=\"shipprice[Ground]\" value=\"$shiprate[grnd]\">");
			}
			if (isset($shiprate[nextdayair]) && $shiprate[nextdayair] != "The requested service is unavailable between the selected locations.") {
			    print("&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"Next_Day_Air\">Next Day Air&nbsp;&nbsp;(<strong>$cs$shiprate[nextdayair]</strong>)<br>
			    <input type=\"hidden\" name=\"shipprice[Next_Day_Air]\" value=\"$shiprate[nextdayair]\">");
			}
			if (isset($shiprate[seconddayair]) && $shiprate[seconddayair] != "The requested service is unavailable between the selected locations.") {
			    print("&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"Second_Day_Air\">Second Day Air&nbsp;&nbsp;(<strong>$cs$shiprate[seconddayair]</strong>)<br>
				<input type=\"hidden\" name=\"shipprice[Second_Day_Air]\" value=\"$shiprate[seconddayair]\">");
			}
			if (isset($shiprate[threeday]) && $shiprate[threeday] != "The requested service is unavailable between the selected locations.") {
			    print("&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"3_Day_Select\">3 Day Select&nbsp;&nbsp;(<strong>$cs$shiprate[threeday]</strong>)<br>
				<input type=\"hidden\" name=\"shipprice[3_Day_Select]\" value=\"$shiprate[threeday]\">");
			}
			if (isset($shiprate[canada]) && $shiprate[canada] != "The requested service is unavailable between the selected locations.") {
			    print("&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"Canada_Standard\">Canada Standard&nbsp;&nbsp;(<strong>$cs$shiprate[canada]</strong>)<br>
				<input type=\"hidden\" name=\"shipprice[Canada_Standard]\" value=\"$shiprate[canada]\">");	
			}
			if (isset($shiprate[worldwidex]) && $shiprate[worldwidex] != "The requested service is unavailable between the selected locations.") {
			    print("&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"Worldwide_Express\">Worldwide Express&nbsp;&nbsp;(<strong>$cs$shiprate[worldwidex]</strong>)<br>
				<input type=\"hidden\" name=\"shipprice[Worldwide_Express]\" value=\"$shiprate[worldwidex]\">");
			}
			if (isset($shiprate[worldwidexplus]) && $shiprate[worldwidexplus] != "The requested service is unavailable between the selected locations.") {
			    print("&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"Worldwide_Express_Plus\">Worldwide Express Plus&nbsp;&nbsp;(<strong>$cs$shiprate[worldwidexplus]</strong>)
				<input type=\"hidden\" name=\"shipprice[Worldwide_Express_Plus]\" value=\"$shiprate[worldwidexplus]\">");
			}
		} else {
			print("<font color=\"Red\"><i>Your postal code is invalid. It is necessary to calculate the correct shipping price. Please fix this before proceeding.</i></font><br>");
		}
		print("			
						</td><td width=\"30\" valign=\"top\">&nbsp;</td>
						<td width=\"50%\" align=\"left\" valign=\"top\">
							<strong>Shipping Address:</strong><br>
							&nbsp;&nbsp;&nbsp;$userinfo[address_line1]<br>
		");
		if ($userinfo[address_line2] != "") { echo "&nbsp;&nbsp;&nbsp;" . $userinfo[address_line2]; print("<br>"); } 
		print("				&nbsp;&nbsp;&nbsp;$userinfo[city], $userinfo[state] $userinfo[zip]<br>
		                    &nbsp;&nbsp;&nbsp;$userinfo[country]<br>
							<div align=\"center\">[<a href=\"index.php?action=editaccount\">Edit Your Address</a>]</div>
						</td>
					</tr>
				</table>
		");
		print ("<table width=\"100%\" border=\"0\"><tr><td>");
		print("<strong>Payment Method:</strong><br>
			&nbsp;&nbsp;&nbsp;<select name=\"payment1\" class=\"input_box\">");
			if ($cc == "Yes") {?>
			<option value="Credit Card">Credit Card
			<?PHP
			}
			if ($check == "Yes") {?>
			<option value="Personal Check">Personal Check
			<?PHP
			}
			if ($moneyorder == "Yes") {?>
			<option value="Money Order">Money Order
			<?PHP
			}
			if ($fax == "Yes") {?>
			<option value="Fax Order">Fax Order
			<?PHP
			}
			if ($paypal == "Yes") {?>
			<option value="PayPal">PayPal
			<?PHP
			}
			if ($po == "Yes") {?>
			<option value="po">Purchase Order
			<?PHP
			}
		print("</select></td><td>
		       <strong>Coupon:</strong><br>
			   &nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"coupon\" size=\"20\" class=\"input_box\">
			   </td></tr></table><br>
			   <strong>Comments:</strong><br>
			   &nbsp;&nbsp;&nbsp;&nbsp;<textarea cols=\"50\" rows=\"5\" name=\"comments\" class=\"input_box\"></textarea><br>
			 "); 
		print("\n<input type=\"hidden\" name=\"USER\" value=\"$USER_IN\">\n");
		print("<input type=\"hidden\" name=\"PASS\" value=\"$PASS_IN\">\n");
		print("<input type=\"hidden\" name=\"options_only1\" value=\"$options_only\">\n");
		print("<input type=\"hidden\" name=\"totalitems1\" value=\"$total_items\">\n");
		print("<input type=\"hidden\" name=\"setnew\" value=\"yes\">\n");
		for ($i = 0; $i < $total_items; $i++) {
		    print("<input type=\"hidden\" name=\"itemtray1[$i]\" value=\"$item_tray[$i]\">\n");
		}
		if ($shiprate[grnd] != "Invalid ConsigneePostalCode" || $shiprate[seconddayair] != "Invalid ConsigneePostalCode") {
		    print("
			<div align=\"right\"><input type=\"image\" name=\"submit\" src=\"images/secure.gif\" alt=\"Continue\" border=0></div>
			</form><div align=\"left\">");
			showtemplate (shipmethod);
			print("</div>");
		}
		
	} 
	else {
		print("<td valign=\"top\">
							<strong>Shipping Address:</strong><br>
							&nbsp;&nbsp;&nbsp;$userinfo[address_line1]<br>
		");
		if ($userinfo[address_line2] != "") { echo "&nbsp;&nbsp;&nbsp;" . $userinfo[address_line2]; print("<br>"); } 
		print("				&nbsp;&nbsp;&nbsp;$userinfo[city], $userinfo[state] $userinfo[zip]<br>
		                    &nbsp;&nbsp;&nbsp;$userinfo[country]<br>
							<div align=\"center\">[<a href=\"index.php?action=editaccount\">Edit Your Address</a>]</div>
						</td>
						<td valign=\"top\"><strong>Payment Method:</strong><br>
						&nbsp;&nbsp;&nbsp;<select name=\"payment1\" class=\"input_box\">");
						if ($cc == "Yes") {?>
						<option value="Credit Card">Credit Card
						<?PHP
						}
						if ($check == "Yes") {?>
						<option value="Personal Check">Personal Check
						<?PHP
						}
						if ($moneyorder == "Yes") {?>
						<option value="Money Order">Money Order
						<?PHP
						}
						if ($fax == "Yes") {?>
						<option value="Fax Order">Fax Order
						<?PHP
						}
						if ($paypal == "Yes") {?>
						<option value="PayPal">PayPal
						<?PHP
						}
						if ($po == "Yes") {?>
						<option value="po">Purchase Order
						<?PHP
						}
						print("
						</select><br><br>
						<strong>Coupon:</strong><br>
			            &nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"coupon\" size=\"20\" class=\"input_box\">
						</td>
					</tr>
				</table><br>
			   <strong>Comments:</strong><br>
			   &nbsp;&nbsp;&nbsp;&nbsp;<textarea cols=\"50\" rows=\"5\" name=\"comments\" class=\"input_box\"></textarea><br>
		");
	    print("\n<input type=\"hidden\" name=\"USER\" value=\"$USER_IN\">\n");
		print("<input type=\"hidden\" name=\"PASS\" value=\"$PASS_IN\">\n");
		print("<input type=\"hidden\" name=\"options_only1\" value=\"$options_only\">\n");
		print("<input type=\"hidden\" name=\"totalitems1\" value=\"$total_items\">\n");
		print("<input type=\"hidden\" name=\"setnew\" value=\"yes\">\n");
		for ($i = 0; $i < $total_items; $i++) {
		    print("<input type=\"hidden\" name=\"itemtray1[$i]\" value=\"$item_tray[$i]\">\n");
		}
		print("<br>
		<div align=\"right\"><input type=\"image\" name=\"submit\" src=\"images/secure.gif\" alt=\"Continue\"  border=0></div>
		</form><div align=\"left\">");
		showtemplate (shipaddressok);
		print("</div>");
	}
}

// ###################### subtotal ##################
function subtotal() {
    global $item_tray, $itemd, $quantityd, $optionsd, $payopd, $total_items;
	$subtotal = "0.00";
	parsecart();
	for ($i = 0; $i < $total_items; $i++) {
	    $iteminfo = iteminfo($itemd[$i]);
		if (isset($iteminfo[sprice])) {
		  $price = $iteminfo[sprice] + $payopd[$i]; 
		} else { 
		  $price = $iteminfo[price] + $payopd[$i]; 
		}
		$subtotal = $subtotal + ($price * $quantityd[$i]); 
		$subtotal = number_format($subtotal,2,".","");
	}
	return $subtotal;
}

// ###################### showcart ##################
function showcart () {
	global $item_tray, $total_items, $securepath, $shopurl, $itemd, $quantityd, $optionsd, $payopd, $error, $centerborder, 
	$options_array, $cs;
	if ($error == "cartfull") {
		print("<font color=\"Red\">Your cart is full, please remove some items before atempting to add more.</font>");
	}
	if ($error == "outofstock") {
		print("<font color=\"Red\">That item is currently out of stock or you have selected a quantity greater then the quantity in stock, please contact us or try again at a later time.</font>");
	}
	?>
	<table width="440" border="<?PHP if ($centerborder == "0") { print("1"); } else { print("0"); }?>" cellspacing="0" cellpadding="0">
		<tr>
			<td><form action="index.php" method="post">
			    <input type="hidden" name="action" value="updatecart">
				<table width="440" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>
						<table>
							<tr>
								<td width="50"><div align="center"><img src="images/quantity.gif" border="0" alt=""></div></td>
								<td width="190"><div align="center"><img src="images/product.gif" border="0" alt=""></div></td>
								<td width="130"><div align="center"><img src="images/options.gif" border="0" alt=""></div></td>
								<td width="70"><div align="center"><img src="images/unitprice.gif" border="0" alt=""></div></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						<table border="0">
						<?PHP
						$subtotal = "0.00";
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
							else { $doptions = trim($options_array[$i]); }
							print("<tr>
								<td width=\"50\" align=\"center\"><input type=\"text\" name=\"item[$i]\" value=\"$quantityd[$i]\" size=\"1\" class=\"input_box\"></td>
								<td width=\"190\" align=\"center\">$iteminfo[title]</td>
								<td width=\"130\" align=\"center\">$doptions</td>
								<td width=\"70\" align=\"center\">$dprice</td>
							</tr>");
						}
						$subtotal = number_format($subtotal,2,".","");
						?>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						    <?PHP if ($total_items <= 0) { print("<br><div align=\"center\"><i><strong>No Items in your Cart</strong></div></i><br>"); } ?>
						    <table>
								<tr>
									<td width="365">&nbsp;</td>
									<td width="71"><hr size=1></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><table>
								<tr>
									<td width="365"><div align="right"><strong>Subtotal:</strong></div></td>
									<td width="71" colspan="2"><div align="center"><?PHP echo $cs.$subtotal ?></div></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><br>
						    <table width="100%" border="0">
								<tr>
									<td valign="top"><div align="center"><a href="index.php"><img src="images/shopmore.gif" alt="Shop More" border="0"></a></div></td>
									<td valign="top"><div align="center"><input type="image" name="go" value="updatecart" src="images/updatecart.gif" alt="Update Cart" border=0></form></div></td>
									<td valign="top"><div align="center">
									<?PHP
									$answer = islogged(); 
									if ($total_items <= 0) { 
									
									} elseif ($total_items > 0 && $answer != "Yes") {
									   print("<img src=\"images/login.gif\" border=\"0\" alt=\"Please Login\">");
									} else {				
									?>
									   <form action="index.php" method="post"><input type="hidden" name="action" value="shipping"><input type="image" name="go" value="checkout" src="images/tocheckout.gif" alt="To Checkout" border=0></form></div></td>
								    <?PHP 
									} 
									?>
								</tr>
						     </table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<?PHP
	if ($centerborder == "1") { print("<hr width=\"100%\">"); }
	$answer = islogged();
	if ($answer == "No") {
	   print("<br><div align=\"left\">");
	   loginorsignup();	   
	   print("</div>");
	} else {
	   print("<br><div align=\"left\">");
	   showtemplate (checkout);	   
	   print("</div>");
	}
	print("</form><br>");
}

// ###################### search ##################
function search ($search, $in) {
	global $DB_site, $start, $orderby, $cs, $dbprefix;
	if ($start == "") {
	  $start = 0;
	}
	if ($search == "") {
	   print("<div align=\"left\">Your search returned (<strong>0</strong>) results:");
	   print("<ul>
	          <li>Make sure you enter a keyword.</li><br>
			  <li>Select a category to search in.</li>			  
			  ");
	   return;
	}
	$num = 0;
	if ($in == "All") {
	    $temp=$DB_site->query("SELECT * FROM ".$dbprefix."items where (viewable='Yes') and (title like '%".addslashes($search)."%' or poverview like '%".addslashes($search)."%' or pdetails like '%".addslashes($search)."%')");
	} else {
		$cat = getcategorynum($in);
		$temp=$DB_site->query("SELECT * FROM ".$dbprefix."items where (viewable='Yes') and (categoryid ='$cat') and (title like '%".addslashes($search)."%' or poverview like '%".addslashes($search)."%' or pdetails like '%".addslashes($search)."%')");
	}
	while ($row=$DB_site->fetch_array($temp)) {
	   $num++;
	}
	if ($num == 0) {
	   print("<div align=\"left\">Your search returned (<strong>0</strong>) results:");
	   print("<ul>
	          <li>Make sure you enter a keyword.</li><br>
			  <li>Select a category to search in.</li>			  
	   ");
	   return;
	}
	$prev = $start - 15;
	$end = $start + 15;
	$tempnum = $start + 1;
	print("<div align=\"left\">Viewing <strong>$tempnum</strong> to <strong>$end</strong> of (<strong>$num</strong>) results:");
	print("<ul>");
	if ($in == "All") {
	    $temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where (viewable='Yes') and (title like '%$search%' or poverview like '%$search%' or pdetails like '%$search%') order by $orderby LIMIT $start,15");
	} else {
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where (viewable='Yes') and (categoryid ='$cat') and (title like '%$search%' or poverview like '%$search%' or pdetails like '%$search%') order by $orderby LIMIT $start,15");
	}
	while ($row1=$DB_site->fetch_array($temps)) {
	   if (strlen($row1[poverview]) > 200) {
	       $desc = $row1[poverview];
		   $desc = substr($desc, 0, 200);
		   $desc = $desc . "...";
	   }				
	   else {
		   $desc = $row1[poverview];
	   }			      
	   print("<li><a href=\"index.php?action=item&id=$row1[itemid]\">$row1[title]</a> - $desc</li><br>");
	}
	print("</ul>\n");
	print("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n<td width=\"50%\" align=\"left\">");
	if (!($prev < 0)) {
	    print("<a href=\"index.php?action=search&search=$search&in=$in&start=$prev\"><img src=\"images/previous.gif\" border=\"0\" alt=\"Previous\"></a>\n");
	} else {
	    print("&nbsp;");
	}
	print("</td>\n<td width=\"50%\" align=\"right\">");
	if ($num > $end) {
	    print("<a href=\"index.php?action=search&search=$search&in=$in&start=$end\"><img src=\"images/next.gif\" border=\"0\" alt=\"Next\"></a>");
	} else {
	    print("&nbsp;");
	}
	print("</td>\n</tr>\n</table>");
	
}

// ###################### displaysearch ##################
function displaysearch () {   
    global $DB_site, $dbprefix;
	print("<form action=\"index.php?action=search\" method=\"post\">
	       <strong>Search:</strong> <select name=\"in\" class=\"input_box\">
	       <option value=\"All\" selected>All</option> 
	");
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."category");
	while ($row=$DB_site->fetch_array($temp)) {
	     print("<option value=\"$row[title]\">$row[title]</option>"); 
	}
	print("</select>&nbsp;<input type=\"text\" name=\"search\" size=\"15\" class=\"input_box\">&nbsp;<input type=\"image\" name=\"Submit\" src=\"images/go.gif\" border=0></form>");
}

// ###################### listitem ##################
function listitem($id) {
    global $DB_site, $cartimage, $productpath, $picwidth, $picheight, $showstock, $showitem, $cs, $dbprefix, $imagel;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $iteminfo = iteminfo($id);
		if ($iteminfo[sprice] != "") {
		   $display = "<s>$iteminfo[price]</s>&nbsp;<font color=\"Red\">$cs$iteminfo[sprice]</font>";
		} else {
		   $display = "$iteminfo[price]";
		}
		print("
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			    <td colspan=\"2\"><u><strong>$iteminfo[title]</strong></u><br><br></td>
			</tr>
		");		
		if ($imagel == "2" && $showitem == "picture") {
			if ($picwidth != "" && $picheight != "") {
				print("<tr><td colspan=\"2\"><div align=\"center\"><a href=\"$productpath$row[imagename]\" target=\"_PIC\"><img src=\"$productpath$row[imagename]\" border=\"0\" width=\"$picwidth\" height=\"$picheight\" alt=\"\"></a><br><br></td></tr>");
		    } else {
				print("<tr><td colspan=\"2\"><div align=\"center\"><a href=\"$productpath$row[imagename]\" target=\"_PIC\"><img src=\"$productpath$row[imagename]\" border=\"0\" alt=\"\"></a><br><br></td></tr>");
			}
		}
		print("<tr><td valign=\"top\">
					<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		");
						if ($iteminfo[sku] != "") {
							print("
							<tr>
								<td><strong>SKU#:</strong></td>
								<td>$iteminfo[sku]</td>
							</tr>");
						}
						print("
						<tr>
							<td><strong>Price:</strong></td>
							<td>$cs$display</td>
						</tr>
						<form action=\"index.php?action=addtocart\" method=\"post\">
						<input type=\"hidden\" name=\"item\" value=\"$row[itemid]\">
						<tr>
							<td><strong>Quantity:</strong></td>
							<td><input type=\"text\" name=\"quantity\" size=\"2\" maxlength=\"4\" value=\"1\" class=\"input_box\">"); 
						if ($showstock == "Yes") {
							print(" / <strong>$row[quantity]</strong> in stock");
						}
						print("</td></tr>");
						if ($iteminfo[option1] != "") {
							print("<tr>
								<td><strong>$iteminfo[option1]:</strong></td>
								<td><select name=\"option1\" class=\"input_box\">
							");
							$oarray = explode("-", $iteminfo[option12]);
							$payop1 = payop($id);
							for ($i=0; $i < count($oarray); $i++) {
							    if ($iteminfo[payop] != "") {
								    if ($payop1[$i] == "") {
									    print("<option value=\"$oarray[$i]\">$oarray[$i]");
									} else {
										print("<option value=\"$oarray[$i]\">$oarray[$i] - Add $cs$payop1[$i]");
									}
								} else {
									print("<option value=\"$oarray[$i]\">$oarray[$i]");
								}
							}
					        print("</select></td></tr>");
						}
						if ($iteminfo[option2] != "") {
							print("<tr>
							<td><strong>$iteminfo[option2]:</strong></td>
							<td><select name=\"option2\" class=\"input_box\">");
							$oarray2 = explode("-", $iteminfo[option22]);
							for ($i=0; $i < count($oarray2); $i++) {
								print("<option value=\"$oarray2[$i]\">$oarray2[$i]");
							}
					        print("</select></td></tr>");
						}
						if ($iteminfo[option3] != "") {
							print("<tr>
							<td><strong>$iteminfo[option3]:</strong></td>
							<td><select name=\"option3\" class=\"input_box\">");
							$oarray3 = explode("-", $iteminfo[option32]);
							for ($i=0; $i < count($oarray3); $i++) {
								print("<option value=\"$oarray3[$i]\">$oarray3[$i]");
							}
					        print("</select></td></tr>");
						}
						print("
						<tr>
							<td>&nbsp;</td>
							<td><br><input type=\"image\" name=\"Submit\" src=\"$cartimage\" alt=\"ADD TO CART\" border=0></td>
						</tr>
					 </form>
					</table>
				</td>
				<td width=\"210\" valign=\"top\">
		");
		if ($imagel == "1" && $showitem == "picture") {
			if ($picwidth != "" && $picheight != "") {
				print("<div align=\"center\"><a href=\"$productpath$row[imagename]\" target=\"_PIC\"><img src=\"$productpath$row[imagename]\" border=\"0\" width=\"$picwidth\" height=\"$picheight\" alt=\"\"></a><br>");
		    } else {
				print("<div align=\"center\"><a href=\"$productpath$row[imagename]\" target=\"_PIC\"><img src=\"$productpath$row[imagename]\" border=\"0\" alt=\"\"></a><br>");
			}
		} else {
			print("&nbsp;");
		}
		$answer = islogged();
	    if ($answer == "Yes") {
		 	print("<a href=\"index.php?action=tellafriend&item=$id\"><img src=\"images/tellafriend.gif\" border=\"0\" alt=\"Tell a Friend\"></a>");
		}
		print("		
				</div></td>
			</tr>
		</table><br>");
		if ($iteminfo[sprice] != "" && $iteminfo[sdescription] != "") {
		   ?>
		    <div align="left"><strong><font color="Red">Sale Description:</font></strong>
			<blockquote>
			<div align="left"><?PHP echo $iteminfo[sdescription] ?></div>
			</blockquote>
		   <?PHP
		}
		print ("<div align=\"left\">");
		if ($iteminfo[poverview] != "") {
			print ("<strong>Product Overview:</strong>
			<blockquote>
			<div align=\"left\">$iteminfo[poverview]</div>
			</blockquote>");
		}
		if ($iteminfo[pdetails] != "") {
			print ("<strong>Product Details:</strong>
			<blockquote>
			<div align=\"left\">$iteminfo[pdetails]</div>
			</blockquote>");
		}
		print ("</div>");
	}
}

// ###################### showicons ##################
function showicons () {
    global $viewcartimage, $viewaccountimage, $checkoutimage, $helpimage;
	print("<a href=\"index.php?action=viewcart\"><img src=\"$viewcartimage\" border=\"0\" alt=\"View Cart\"></a>
		   &nbsp;&nbsp;<a href=\"index.php?action=account\"><img src=\"$viewaccountimage\" border=\"0\" alt=\"View Account\"></a>
		   &nbsp;&nbsp;<a href=\"index.php?action=help\"><img src=\"$helpimage\" border=\"0\" alt=\"Get Help\"></a>
	");
}

// ###################### islogged ##################
function islogged () {
    global $USER_IN, $PASS_IN;
    if (!isset($USER_IN) || !isset($PASS_IN)) {
	   $answer = "No";
	} else {
	   $cont = authenticate2($USER_IN, $PASS_IN);
	   if ($cont == "valid") {
	     $answer = "Yes";
	   } else {
	     $answer = "No";
	   }
	}
	return $answer;
}

// ###################### displaytopten ##################
function displaytopten ($numbern) {
    global $DB_site, $cs, $productpath, $thumbwidth, $thumbheight, $showitem, $showprice, $dbprefix;
	if ($showitem == "picture") {
		print("<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">");
    }
	$temp = 1;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where viewable='Yes' ORDER by sold desc LIMIT 0,12");
	while ($row=$DB_site->fetch_array($temps)) {
	   if ($showitem == "picture") {
	       if ($temp == 1) { print("<tr>"); }
		   print("
		   <td width=\"148\" valign=\"top\">	
		   <div align=\"center\">
		   <a href=\"index.php?action=item&id=$row[itemid]\">");
		   if ($thumbwidth != "" && $thumbheight != "") {
				print("<img src=\"$productpath$row[thumb]\" border=\"0\" alt=\"\" width=\"$thumbwidth\" height=\"$thumbheight\"><br>");
		   } else {
				print("<img src=\"$productpath$row[thumb]\" border=\"0\" alt=\"\"><br>");
		   }
		   if ($showprice == "Yes") {
				print("$row[title] - $cs$row[price]</a><br><br>");
		   } else {
			    print("$row[title]</a><br><br>");
		   }
		   print("</div>
		   </td>
		   ");
		   if ($temp == 3) { print("</tr>"); }
		   if ($temp == 1) { $temp = 2; } elseif ($temp == 2) { $temp = 3; } else { $temp = 1; }
	   } else {
	       if (strlen($row[poverview]) > 200) {
		       $desc = $row[poverview];
			   $desc = substr($desc, 0, 200);
			   $desc = $desc . "...";
		   }				
		   else {
			   $desc = $row[poverview];
		   }
		   print("<a href=\"index.php?action=item&id=$row[itemid]\">$row[title]</a> - $desc [ <strong>$cs$row[price]</strong> ]<br><br>");
	   }
	}
	if ($showitem == "picture") {
		print("</table>");
    }
}

// ###################### gettotal ##################
function gettotal ($cat, $subid) {
    global $DB_site, $dbprefix;
	$total = 0;
	if ($subid == "") {
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where categoryid='$cat' AND viewable='Yes'");
	} else {
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where subcategoryid='$subid' AND viewable='Yes'");
	}
	while ($row=$DB_site->fetch_array($temps)) {
	    $total = $total + 1;
	}
	return $total;
}

// ###################### getcategory ##################
function getcategory ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $cat = $row[categoryid];
	}
	return $cat;
}

// ###################### getsubcategory ##################
function getsubcategory ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $cat = $row[subcategoryid];
	}
	return $cat;
}

// ###################### getcategoryname ##################
function getcategoryname ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."category where categoryid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $cat = $row[title];
	}
	return $cat;
}

// ###################### getsubcategoryname ##################
function getsubcategoryname ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."subcategory where subcategoryid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $cat = $row[title];
	}
	return $cat;
}

// ###################### getcategoryshortname ##################
function getcategoryshortname ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."category where categoryid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $cat = $row[stitle];
	}
	return $cat;
}

// ###################### getcategorynum ##################
function getcategorynum ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."category where title='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $cat = $row[categoryid];
	}
	return $cat;
}

// ###################### startcenter ##################
function startcenter ($message) {
    global $centerborder, $centerheader, $centercolor, $centerfont, $centerbg;
    ?>
	<table width="440" border="<?PHP echo $centerborder ?>" cellspacing="0" cellpadding="0" bordercolor="<?PHP echo $centercolor ?>">
		<tr>
		    <td width="440">			
			<table width="440" border="0" cellspacing="0" cellpadding="1">
				<tr>
					<td bgcolor="<?PHP echo $centerheader ?>"><strong><font color="<?PHP echo $centerfont ?>"><?PHP echo $message ?></font></strong></td>
				</tr>
				<tr>
					<td width="440">
				         <table width="440" border="0" cellspacing="0" cellpadding="3">
							 <tr>
		                         <td height="100%" bgcolor="<?PHP echo $centerbg ?>">
							     <div align="left">
	<?PHP	
}

// ###################### endcenter ##################
function endcenter () {
    ?>
	
	                            </td>
							</tr>
						 </table>
	                </td>
			    </tr>
		    </table>
			</td>
		</tr>
    </table>
	<?PHP	
}

// ###################### showitems ##################
function showitems ($cat, $start, $subid) {
     global $DB_site, $productpath, $thumbwidth, $thumbheight, $showitem, $orderby, $cs, $showprice, $dbprefix;
	 $origin = $start;
	 $prev = $start - 12;
     $end = $start + 12; $temp = 1;
	 $total = gettotal($cat, $subid);
	 if ($showitem == "picture") {
		 print("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">");
	 }
	 if ($subid == "") {
		 $temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where categoryid='$cat' AND viewable='Yes' order by $orderby LIMIT $start,12");
	 } else {
		 $temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where subcategoryid='$subid' AND viewable='Yes' order by $orderby LIMIT $start,12");
	 }
	 while ($row=$DB_site->fetch_array($temps)) {
		 if ($temp == 1 && $showitem == "picture") { print("<tr>"); }	 
	     if ($showitem == "picture") {
			print("
			<td width=\"148\" valign=\"top\">	
			<div align=\"center\">
			<a href=\"index.php?action=item&id=$row[itemid]\">");
			if ($thumbwidth != "" && $thumbheight != "") {
				print("<img src=\"$productpath$row[thumb]\" border=\"0\" alt=\"\" width=\"$thumbwidth\" height=\"$thumbheight\"><br>");
		    } else {
				print("<img src=\"$productpath$row[thumb]\" border=\"0\" alt=\"\"><br>");
		    }
			if ($showprice == "Yes") {
				print("$row[title] - $cs$row[price]</a><br><br>");
			} else {
			    print("$row[title]</a><br><br>");
			}
			print("</div>
			</td>
			");
	     } else {
		    if (strlen($row[poverview]) > 200) {
		        $desc = $row[poverview];
			    $desc = substr($desc, 0, 200);
			    $desc = $desc . "...";
		    } else {
			   $desc = $row[poverview];
		    }			      
	        print("<a href=\"index.php?action=item&id=$row[itemid]\">$row[title]</a> - $desc [ <strong>$cs$row[price]</strong> ]<br><br>");
	     }
		 if ($temp == 3 && $showitem == "picture") { print("</tr>"); }
		 if ($temp == 1) { $temp = 2; } elseif ($temp == 2) { $temp = 3; } else { $temp = 1; }
	 }
  	 if ($showitem == "picture") {
		 if ($temp == 2) {
		    print("<td>&nbsp;</td><td>&nbsp;</td></tr>");
		 }
		 if ($temp == 3) {
		    print("<td>&nbsp;</td></tr>");
		 }
		 print("</table>");
	 }
	 print("
	 <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
	 <tr>
	 ");
	 if (!($prev < 0)) {
		 print("
		 <td><div align=\"left\">
		 <a href=\"index.php?action=category&id=$cat&subid=$subid&start=$prev\"><img src=\"images/previous.gif\" border=\"0\" alt=\"Previous\"></a>
		 </div></td>
		 ");
	 }
	 if ($end < $total) {
		 print("<td><div align=\"right\">
		 <a href=\"index.php?action=category&id=$cat&subid=$subid&start=$end\"><img src=\"images/next.gif\" border=\"0\" alt=\"Next\"></a>
		 </div></td>
		 ");
	 }
	 print("</tr></table><br>");
	 if ($total < $end) { $temp3 = $total; } else { $temp3 = $end; }
	 $temp4 = $start + 1;
	 print("<div align=\"center\"><strong>$total</strong> product(s) found. Viewing <strong>$temp4</strong> thru <strong>$temp3</strong></div>");
	 if ($total > 12) {
		 $times = 0;
		 $page = 1;
		 print("<div align=\"center\"><table><tr><td valign=\"top\"><strong>Product Page</strong>:</td><td width=\"5\">&nbsp;</td><td valign=\"top\"><div align=\"justify\">"); 
		 $split = 20;
		 while ($total >= $times) {
			 print("<a href=\"index.php?action=category&id=$cat&subid=$subid&start=$times\">$page</a>&nbsp;&nbsp;");
			 $times = $times + 12;
			 if ($page == $split) {
				 print("<br>");
				 $split = $split + 20;
			 }
			 $page++;
		 }
		 print("</div></td></tr></table></div>");
	 }
}

// ###################### showspecials ##################
function showspecials ($start) {
     global $DB_site, $productpath, $thumbwidth, $thumbheight, $showitem, $cs, $dbprefix;
	 if ($start == 0) { $start = 1; }
	 $prev = $start - 12;
     $end = $start + 12; $temp = 1;
	 $total = 0; $origin = $start;
	 $temps=$DB_site->query("SELECT * FROM ".$dbprefix."specials");
	 while ($row=$DB_site->fetch_array($temps)) {
		$total++;
	 }
	 if ($showitem == "picture") {
		 print("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">");
	 }
	 while ($start != $end) {
		 $times = 1;
		 $temps=$DB_site->query("SELECT * FROM ".$dbprefix."specials");
	     while ($row=$DB_site->fetch_array($temps)) {
		    if ($times == $start) {
			    $iteminfo = iteminfo($row[itemid]);
			    if ($iteminfo[viewable] != "Yes") {
				   $times--;
			       $gone = 1;
		        } else {
			        if ($showitem == "picture") {
				        if ($temp == 1) { print("<tr>"); }
					    print("
					    <td width=\"148\" valign=\"top\">	
					    <div align=\"center\">
					    <a href=\"index.php?action=item&id=$row[itemid]\">");
						if ($thumbwidth != "" && $thumbheight != "") {
							print("<img src=\"$productpath$iteminfo[thumb]\" border=\"0\" alt=\"\" width=\"$thumbwidth\" height=\"$thumbheight\"><br>");
					    } else {
							print("<img src=\"$productpath$iteminfo[thumb]\" border=\"0\" alt=\"\"><br>");
					    }
					    print("$iteminfo[title]</a><br>
					    <strong><s>$cs$iteminfo[price]</s>&nbsp;&nbsp;<font color=\"Red\">$cs$row[sprice]</font></strong>
					    </div>
					    </td>
					    ");
					    if ($temp == 3) { print("</tr>"); }
					    if ($temp == 1) { $temp = 2; } elseif ($temp == 2) { $temp = 3; } else { $temp = 1; }
				    } else {
					    if (strlen($iteminfo[poverview]) > 200) {
					        $desc = $iteminfo[poverview];
						    $desc = substr($desc, 0, 200);
						    $desc = $desc . "...";
					    }				
					    else {
						    $desc = $iteminfo[poverview];
					    }			      
				        print("<a href=\"index.php?action=item&id=$iteminfo[itemid]\">$iteminfo[title]</a> - $desc [ <strong><s>$cs$iteminfo[price]</s> <font color=\"Red\">$cs$iteminfo[sprice]</font></strong> ]<br><br>");
				    }
			    }
			}
			$times = $times + 1;
		}
		$start = $start + 1;
	}
	if ($showitem == "picture") {
		if ($temp == 2) {
		   print("<td>&nbsp;</td><td>&nbsp;</td></tr>");
		}
		if ($temp == 3) {
		   print("<td>&nbsp;</td></tr>");
		}
		print("</table>");
	}
	print("
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
	<tr>
	");
	if (!($prev < 1)) {
		print("
		<td><div align=\"left\">
		<a href=\"index.php?action=category&id=specials&start=$prev\"><img src=\"images/previous.gif\" border=\"0\" alt=\"Previous\"></a>
		</div></td>
		");
	}
	if ($start < $total) {
		print("<td><div align=\"right\">
		<a href=\"index.php?action=category&id=specials&start=$start\"><img src=\"images/next.gif\" border=\"0\" alt=\"Next\"></a>
		</div></td>
		");
	}
	print("</tr></table><br>");
	if ($total < $end) { $temp3 = $total; } else { $temp3 = $end - 1; }
    print("<div align=\"center\"><strong>$total</strong> product(s) found. Viewing <strong>$origin</strong> thru <strong>$temp3</strong></div>");
	if ($total > 12) {
		 $times = 1;
		 $page = 1;
		 print("<div align=\"center\"><table><tr><td valign=\"top\"><strong>Product Page</strong>:</td><td width=\"5\">&nbsp;</td><td valign=\"top\">"); 
		 $split = 20;
		 while ($total >= $times) {
			 print("<a href=\"index.php?action=category&id=specials&start=$times\">$page</a>&nbsp;&nbsp;");
			 $times = $times + 12;
			 if ($page == $split) {
				 print("<br>");
				 $split = $split + 20;
			 }
			 $page++;
		 }
		 print("</td></tr></table></div>");
	}
}

// ###################### dovars #######################
function dovars($vartext) {
  $newtext=$vartext;
  return $newtext;
}

// ###################### firsttable #######################
function firsttable () {
    global $total_items;
	?>
	<div align="center"><table width="750" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td width="400" align="left" valign="middle" nowrap>		    
		    <?PHP
		    displaysearch();
			?>
		</td>
		<td width="350" align="right" valign="top" nowrap>
		    <?PHP
		    showicons();
		    ?>
		</td>
	</tr>
	</table></div>
	<?PHP
}

// ###################### loginorsignup #######################
function loginorsignup () {
    global $contactemail, $title, $state_list, $countries_list;
	?>
	<div align="left">
	<b>Please log In:</b><br><br>
	To continue please login to the right with your assigned username and password. If you have 
	lost your username and password you can have it emailed to you by using the <a href="index.php?action=login">email
	form</a>.<br><br>
	<b>New Users:</b><br><br>
	<form action="index.php?action=storenew" method="post">
	By registering with <?PHP echo $title ?> you will be able to order products
	from us online. Registering is free and easy and there is no obligation of any kind.
	<br><br>
	<table>
		<tr>
			<td><strong>Login:</strong></td>
			<td><input type="text" name="username" size="10" value="<?PHP echo $username ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>Password:</strong></td>
			<td><input type="password" name="password" size="10" value="<?PHP echo $password ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>Retype Password:</strong></td>
			<td><input type="password" name="password2" size="10" value="" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>Full Name:</strong></td>
			<td><input type="text" name="name" size="20" value="<?PHP echo $name ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>Phone:</strong></td>
			<td><input type="text" name="phone" size="15" value="<?PHP echo $phone ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>Email:</strong></td>
			<td><input type="text" name="email" size="25" value="<?PHP echo $email ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>Shipping Address</td>
		</tr>
		<tr>
			<td><strong>Address 1:</strong></td>
			<td><input type="text" name="address_line1" size="20" value="<?PHP echo $address_line1 ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>Address 2:</strong></td>
			<td><input type="text" name="address_line2" size="20" value="<?PHP echo $address_line2 ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>City:</strong></td>
			<td><input type="text" name="city" size="10" value="<?PHP echo $city ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>State/Province:</strong></td>
			<td><select NAME="state" class="input_box">
			    <option VALUE="">
			    <?PHP echo $state_list;?>
				</select>
	         </td>
		</tr>
		<tr>
			<td><strong>Zip/Postal Code:</strong></td>
			<td><input type="text" name="zip" size="10" value="<?PHP echo $zip ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>Country:</strong></td>
			<td><select NAME="country" class="input_box">
			    <option VALUE="">
				<?PHP echo $countries_list; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>Billing Address (If Different Then Above)</td>
		</tr>
		<tr>
			<td><strong>Address 1:</strong></td>
			<td><input type="text" name="baddress_line1" size="20" value="<?PHP echo $baddress_line1 ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>Address 2:</strong></td>
			<td><input type="text" name="baddress_line2" size="20" value="<?PHP echo $baddress_line2 ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>City:</strong></td>
			<td><input type="text" name="bcity" size="10" value="<?PHP echo $bcity ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>State/Province:</strong></td>
			<td><select NAME="bstate" class="input_box">
			    <option VALUE="">
				<?PHP echo $state_list;?>
				</select>
	         </td>
		</tr>
		<tr>
			<td><strong>Zip/Postal Code:</strong></td>
			<td><input type="text" name="bzip" size="10" value="<?PHP echo $bzip ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>Country:</strong></td>
			<td><select NAME="bcountry" class="input_box">
			    <option VALUE="">
				<?PHP echo $countries_list; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="Submit" value="Continue" class="submit_button">&nbsp;&nbsp;<input type="Reset" class="submit_button"></td>
		</tr>
	</table></div><br></form>
	<?PHP
}

// ###################### displaynew #######################
function displaynew ($username, $password, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, $error) {
    global $contactemail, $title, $baddress_line1, $baddress_line2, $bcity, $bstate, $bzip, $bcountry, $state_list, $countries_list;
	?>
	<div align="left">
	<form action="index.php?action=storenew" method="post">
	By registering with <?PHP echo $title ?> you will be able to order products
	from us online. Registering is free and easy and there is no obligation of any kind.
	<br><br>
	<table>
		<tr>
			<td><strong>Login:</strong></td>
			<td><input type="text" name="username" size="10" value="<?PHP echo $username ?>" class="input_box">
			<?PHP
			if ($error == "taken") { print("&nbsp;<font color=\"Red\">Username already taken</font>"); }
			if ($error == "required" && !$username) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Password:</strong></td>
			<td><input type="password" name="password" size="10" value="<?PHP echo $password ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$password) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			if ($error == "match") { print("&nbsp;<font color=\"Red\">Passwords did not match</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Retype Password:</strong></td>
			<td><input type="password" name="password2" size="10" value="" class="input_box" value="<?PHP echo $password2 ?>">
			</td>
		</tr>
		<tr>
			<td><strong>Full Name:</strong></td>
			<td><input type="text" name="name" size="20" value="<?PHP echo $name ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$name) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			if ($error == "name") { print("&nbsp;<font color=\"Red\">Invalid Name</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Phone:</strong></td>
			<td><input type="text" name="phone" size="15" value="<?PHP echo $phone ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$phone) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Email:</strong></td>
			<td><input type="text" name="email" size="25" value="<?PHP echo $email ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$email) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>Billing / Shipping Address</td>
		</tr>
		<tr>
			<td><strong>Address 1:</strong></td>
			<td><input type="text" name="address_line1" size="20" value="<?PHP echo $address_line1 ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$address_line1) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Address 2:</strong></td>
			<td><input type="text" name="address_line2" size="20" value="<?PHP echo $address_line2 ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>City:</strong></td>
			<td><input type="text" name="city" size="10" value="<?PHP echo $city ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$city) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>State/Province:</strong></td>
			<td><select NAME="state" class="input_box">
				<option VALUE="<?PHP echo $state ?>"><?PHP echo $state ?>
				<?PHP echo $state_list;?>
				</select>
				<?PHP
				if ($error == "required" && !$state) { print("&nbsp;<font color=\"Red\">Required</font>"); }
				?>
	         </td>
		</tr>
		<tr>
			<td><strong>Zip/Postal Code:</strong></td>
			<td><input type="text" name="zip" size="10" value="<?PHP echo $zip ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$zip) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Country:</strong></td>
			<td><select NAME="country" class="input_box">
				<option VALUE="<?PHP echo $country ?>"><?PHP echo $country ?>
				<?PHP echo $countries_list; ?>
				</select>
				<?PHP
				if ($error == "required" && !$country) { print("&nbsp;<font color=\"Red\">Required</font>"); }
				?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>Billing Address (If Different Then Above)</td>
		</tr>
		<tr>
			<td><strong>Address 1:</strong></td>
			<td><input type="text" name="baddress_line1" size="20" value="<?PHP echo $baddress_line1 ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>Address 2:</strong></td>
			<td><input type="text" name="baddress_line2" size="20" value="<?PHP echo $baddress_line2 ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>City:</strong></td>
			<td><input type="text" name="bcity" size="10" value="<?PHP echo $bcity ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$bcity && $baddress_line1) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>State/Province:</strong></td>
			<td><select NAME="bstate" class="input_box">
				<option VALUE="<?PHP echo $bstate ?>"><?PHP echo $bstate ?>
				<?PHP echo $state_list;?>
				</select>
				<?PHP
				if ($error == "required" && !$bstate  && $baddress_line1) { print("&nbsp;<font color=\"Red\">Required</font>"); }
				?>
	         </td>
		</tr>
		<tr>
			<td><strong>Zip/Postal Code:</strong></td>
			<td><input type="text" name="bzip" size="10" value="<?PHP echo $bzip ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$bzip && $baddress_line1) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Country:</strong></td>
			<td><select NAME="bcountry" class="input_box">
				<option VALUE="<?PHP echo $bcountry ?>"><?PHP echo $bcountry ?>
				<?PHP echo $countries_list; ?>
				</select>
				<?PHP
				if ($error == "required" && !$bcountry && $baddress_line1) { print("&nbsp;<font color=\"Red\">Required</font>"); }
				?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="Submit" value="Continue" class="submit_button">&nbsp;&nbsp;<input type="Reset" class="submit_button"></td>
		</tr>
	</table></div><br>
	<div align="left">	
	<?PHP
	showtemplate(signup);
	print ("</form></div><br>");
}

// ###################### displayaccount #######################
function displayaccount () {
    global $DB_site, $dbprefix;
	$userinfo = getuser ();
	?>
	<div align="left">
	Please make sure that we have the most current information in our database. You may
	edit your information or view your order status below.
	<br><br>
	<strong>Personal Info:</strong> [ <a href="index.php?action=editaccount">Edit</a> ]
	<blockquote>
	<table border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td><strong>Login:</strong></td>
			<td><?PHP echo $userinfo[username] ?></td>
		</tr>
		<tr>
			<td><strong>Password:</strong></td>
			<td><?PHP $temp2 = 0; $temp = strlen($userinfo[password]); while ($temp2 != $temp) { print("*"); $temp2++; }?></td>
		</tr>
		<tr>
			<td><strong>Full Name:</strong></td>
			<td><?PHP echo $userinfo[name] ?></td>
		</tr>
		<tr>
			<td><strong>Phone:</strong></td>
			<td><?PHP echo $userinfo[phone] ?></td>
		</tr>
		<tr>
			<td><strong>Email:</strong></td>
			<td><?PHP echo $userinfo[email] ?></td>
		</tr>
		<tr>
			<td valign="top"><strong>Address:</strong></td>
			<td><?PHP echo $userinfo[address_line1] ?><br>
			    <?PHP if ($userinfo[address_line2] != "") { echo $userinfo[address_line2]; print("<br>"); } ?>
				<?PHP echo $userinfo[city] ?>, <?PHP echo $userinfo[state] ?> <?PHP echo $userinfo[zip] ?>
			</td>
		</tr>
		<tr>
			<td><strong>Country:</strong></td>
			<td><?PHP echo $userinfo[country] ?></td>
		</tr>
	</table>
	</blockquote>
	<strong>Current Orders:</strong><br><br>
	<table width="100%" border="0" cellspacing="2" cellpadding="0"><tr><td>
	<table width="100%">
	<tr bgcolor="Silver">
		<td><strong>Order #:</strong></td>
		<td><strong>Date:</strong></td>
		<td><strong>Payment Status:</strong></td>
		<td><strong>Status:</strong></td>
	</tr>
	<?PHP
	$toto = 0;
	$color = 1;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."transaction where userid='$userinfo[userid]'");
	while ($row=$DB_site->fetch_array($temps)) {
	$toto++;
	if ($color == 1) { print("<tr bgcolor=\"#FFFFFF\">"); } else { print("<tr bgcolor=\"#E6E6E6\">"); }
	?>
		<td><?PHP echo $row[orderid] ?></td>
		<td><?PHP echo $row[tdate] ?></td>
		<td><?PHP echo $row[ccstatus] ?></td>
		<td><?PHP echo $row[status] ?></td>
	</tr>
	<?PHP
    if ($color == 1) { $color = 0; } else { $color = 1; }
	}
	if ($toto == 0) {
		print ("<tr><td colspan=\"4\" align=\"center\">No Pending Orders</td></tr>");
	}
	?>
	</table></td></tr></table>
	</div><br>
	<br>	
	<?PHP
}

// ###################### editaccount #######################
function editaccount ($username, $password, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, $error) {
    global $baddress_line1, $baddress_line2, $bcity, $bstate, $bzip, $bcountry, $state_list, $countries_list;
	if ($username == "" && $password  == "" && $name  == "" && $city  == "" && $state  == "" && $zip  == "" && $email == "") {
	   $userinfo = getuser ();
	   $username = $userinfo[username];
	   $password = $userinfo[password];
	   $name = $userinfo[name];
	   $address_line1 = $userinfo[address_line1];
	   $address_line2 = $userinfo[address_line2];
	   $city = $userinfo[city];
	   $state = $userinfo[state];
	   $zip = $userinfo[zip];
	   $country = $userinfo[country];
	   $baddress_line1 = $userinfo[baddress_line1];
	   $baddress_line2 = $userinfo[baddress_line2];
	   $bcity = $userinfo[bcity];
	   $bstate = $userinfo[bstate];
	   $bzip = $userinfo[bzip];
	   $bcountry = $userinfo[bcountry];
	   $phone = $userinfo[phone];
	   $email = $userinfo[email];
	} 
	?>
	<div align="left">
	<form action="index.php?action=update" method="post">
	<table>
		<tr>
			<td><strong>Login:</strong></td>
			<td><?PHP echo $username ?><input type="hidden" name="username" value="<?PHP echo $username ?>" class="input_box"></td>
		</tr>
				<tr>
			<td><strong>Password:</strong></td>
			<td><input type="password" name="password" size="10" value="<?PHP echo $password ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$password) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			if ($error == "match") { print("&nbsp;<font color=\"Red\">Passwords did not match</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Retype Password:</strong></td>
			<td><input type="password" name="password2" size="10" value="<?PHP echo $password ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>Full Name:</strong></td>
			<td><input type="text" name="name" size="20" value="<?PHP echo $name ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$name) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			if ($error == "name") { print("&nbsp;<font color=\"Red\">Invalid Name</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Phone:</strong></td>
			<td><input type="text" name="phone" size="15" value="<?PHP echo $phone ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$phone) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Email:</strong></td>
			<td><input type="text" name="email" size="25" value="<?PHP echo $email ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$email) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>Shipping Address</td>
		</tr>
		<tr>
			<td><strong>Address 1:</strong></td>
			<td><input type="text" name="address_line1" size="20" value="<?PHP echo $address_line1 ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$address_line1) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Address 2:</strong></td>
			<td><input type="text" name="address_line2" size="20" value="<?PHP echo $address_line2 ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>City:</strong></td>
			<td><input type="text" name="city" size="10" value="<?PHP echo $city ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$city) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>State/Province:</strong></td>
			<td><select NAME="state" class="input_box">
				<option VALUE="<?PHP echo $state ?>"><?PHP echo $state ?>
				<?PHP echo $state_list;?>
				</select>
				<?PHP
				if ($error == "required" && !$state) { print("&nbsp;<font color=\"Red\">Required</font>"); }
				?>
	         </td>
		</tr>
		<tr>
			<td><strong>Zip/Postal Code:</strong></td>
			<td><input type="text" name="zip" size="10" value="<?PHP echo $zip ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$zip) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Country:</strong></td>
			<td><select NAME="country" class="input_box">
				<option VALUE="<?PHP echo $country ?>"><?PHP echo $country ?>
				<?PHP echo $countries_list; ?>
				</select>
				<?PHP
				if ($error == "required" && !$country) { print("&nbsp;<font color=\"Red\">Required</font>"); }
				?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>Billing Address (If Different Then Above)</td>
		</tr>
		<tr>
			<td><strong>Address 1:</strong></td>
			<td><input type="text" name="baddress_line1" size="20" value="<?PHP echo $baddress_line1 ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>Address 2:</strong></td>
			<td><input type="text" name="baddress_line2" size="20" value="<?PHP echo $baddress_line2 ?>" class="input_box">
			</td>
		</tr>
		<tr>
			<td><strong>City:</strong></td>
			<td><input type="text" name="bcity" size="10" value="<?PHP echo $bcity ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$bcity && $baddress_line1) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>State/Province:</strong></td>
			<td><select NAME="bstate" class="input_box">
				<option VALUE="<?PHP echo $bstate ?>"><?PHP echo $bstate ?>
				<?PHP echo $state_list;?>
				</select>
				<?PHP
				if ($error == "required" && !$bstate && $baddress_line1) { print("&nbsp;<font color=\"Red\">Required</font>"); }
				?>
	         </td>
		</tr>
		<tr>
			<td><strong>Zip/Postal Code:</strong></td>
			<td><input type="text" name="bzip" size="10" value="<?PHP echo $bzip ?>" class="input_box">
			<?PHP
			if ($error == "required" && !$bzip && $baddress_line1) { print("&nbsp;<font color=\"Red\">Required</font>"); }
			?>
			</td>
		</tr>
		<tr>
			<td><strong>Country:</strong></td>
			<td><select NAME="bcountry" class="input_box">
				<option VALUE="<?PHP echo $bcountry ?>"><?PHP echo $bcountry ?>
				<?PHP echo $countries_list; ?>
				</select>
				<?PHP
				if ($error == "required" && !$bcountry && $baddress_line1) { print("&nbsp;<font color=\"Red\">Required</font>"); }
				?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="Submit" value="Continue" class="submit_button">&nbsp;&nbsp;<input type="Reset" class="submit_button"></td>
		</tr>
	</table></div><br>
	<div align="left">	
	<?PHP
	showtemplate(signup);
	print ("</form></div><br>");
}

// ###################### storenew #######################
function storenew($username, $password, $password2, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email) {
	global $DB_site, $title, $contactemail, $shopurl, $dbprefix, $baddress_line1, $baddress_line2, $bcity, $bstate, $bzip, $bcountry, $state_list, $countries_list;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$username'");
    while ($row=$DB_site->fetch_array($temps)) {
	    startcenter("Error");
		displaynew ($username, $password, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, "taken");
	    endcenter();
		return;
	}
	if ($name == "Admin_Account") {
	    startcenter("Error");
		displaynew ($username, $password, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, "name");
	    endcenter();
		return;
	}
	if ($password != $password2) {
	    startcenter("Error");
		displaynew ($username, $password, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, "match");
	    endcenter();
		return;
	}
	if (!$username || !$password || !$name || !$address_line1 || !$city || !$state || !$zip || !$country || !$phone || !$email || ($baddress_line1 && (!$bcity || !$bstate || !$bzip || !$bcountry))) {
		startcenter("Error");
		displaynew ($username, $password, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, "required");
		endcenter();
		return;
	}
	if (!($DB_site->query("INSERT INTO ".$dbprefix."user (userid,username,password,name,address_line1,address_line2,city,state,zip,country,baddress_line1,baddress_line2,bcity,bstate,bzip,bcountry,phone,email,lastvisit) VALUES (NULL,'".strip_tags(addslashes($username))."','".strip_tags(addslashes($password))."','".strip_tags(addslashes($name))."','".strip_tags(addslashes($address_line1))."','".strip_tags(addslashes($address_line2))."','".strip_tags(addslashes($city))."','".strip_tags(addslashes($state))."','".strip_tags(addslashes($zip))."','".strip_tags(addslashes($country))."','".strip_tags(addslashes($baddress_line1))."','".strip_tags(addslashes($baddress_line2))."','".strip_tags(addslashes($bcity))."','".strip_tags(addslashes($bstate))."','".strip_tags(addslashes($bzip))."','".strip_tags(addslashes($bcountry))."','".strip_tags(addslashes($phone))."','".strip_tags(addslashes($email))."','Never')"))) {
		startcenter("Error:");
		print("
		There was an error while storing your information in our database. Please
		wait a few minutes and try your submission again. If you continue to have problems please
		email us.");
	} else {
	    $body = "Thank you for signing up at " . $title . ". Your login information is as follows:\n\nLogin: " . $username . "\nPassword: " . $password . "\n\nIf you need any additional help please see our online help page located at:\n" . $shopurl . "?action=help\nThank you for shopping at " . $title . "\n\n" . $contactemail;
		mail($email, "Your " . $title . " Account", $body, "From: \"".$title."\" <" . $contactemail . ">");
		startcenter("Thank You: $name");
		print("
		You have been successfully added into our client database. You may now log in using
		the login area to the right. Once logged in, you may begin shopping or change your
		account information. Thank you for creating an account with $title.");
	}
	endcenter();
}

// ###################### update#######################
function update($username, $password, $password2, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email) {
    global $DB_site, $dbprefix, $baddress_line1, $baddress_line2, $bcity, $bstate, $bzip, $bcountry;
	if (!$username || !$password || !$name || !$address_line1 || !$city || !$state || !$zip || !$country || !$phone || !$email || ($baddress_line1 && (!$bcity || !$bstate || !$bzip || !$bcountry))) {
		editaccount ($username, $password, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, "required");
		return;
	}
	if ($name == "Admin_Account") {
		editaccount ($username, $password, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, "name");
		return;
	}
	if ($password != $password2) {
		editaccount ($username, $password, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, "match");
		return;
	}
	if (!($DB_site->query("UPDATE ".$dbprefix."user set password='".strip_tags(addslashes($password))."', name='".strip_tags(addslashes($name))."', address_line1='".strip_tags(addslashes($address_line1))."', address_line2='".strip_tags(addslashes($address_line2))."', city='".strip_tags(addslashes($city))."', state='".strip_tags(addslashes($state))."', zip='".strip_tags(addslashes($zip))."', country='".strip_tags(addslashes($country))."', phone='".strip_tags(addslashes($phone))."', email='".strip_tags(addslashes($email))."', baddress_line1='".strip_tags(addslashes($baddress_line1))."', baddress_line2='".strip_tags(addslashes($baddress_line2))."', bcity='".strip_tags(addslashes($bcity))."', bstate='".strip_tags(addslashes($bstate))."', bzip='".strip_tags(addslashes($bzip))."', bcountry='".strip_tags(addslashes($bcountry))."' where username='$username'"))) {
		print("
		There was an error while updating your information in our database. Please
		wait a few minutes and try your submission again. If you continue to have problems please
		let us know.<br><br><br><br>
	    ");
	} else {
		print("
		You have successfully updated your account. You may proceed adding items to your cart
		or checking out. If you changed your password you will need to log back in.");
	}
}

// ###################### randomspecial #######################
function randomspecial () {
	global $DB_site, $dbprefix;
	$count = 0;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."specials");
	while ($row=$DB_site->fetch_array($temps)) {
		$count++;
	}
	if ($count > 1) {
		$setrand = rand(1,$count);
	} 
	elseif ($count == 1) {
		$setrand = 1;
	} else {
		$setrand = "None";
	}
	$count = 0;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."specials");
	while ($row=$DB_site->fetch_array($temps)) {
	    $count++;
		if ($count == $setrand) {
		   $random[itemid] = $row[itemid];
		   $random[sdescription] = stripslashes($row[sdescription]);
		   $random[sprice] = $row[sprice];
		   return $random;
		}
	}
	$random = "None";
	return $random;
}

// ###################### loginarea #######################
function loginarea ($act) {
    global $tablehead, $tableheadtext, $action, $total_items, $cs, $action, $id;
	$answer = islogged();
	if ($action == "storenew") { $paction = "account"; } else { $paction = $action; }
	if ($answer != "Yes") {
		print(" <tr>
				<td bgcolor=\"$tablehead\"><strong><font color=\"$tableheadtext\"><div align=\"center\">Account Login</div></strong></font></td>
				</tr>
				<tr>
					<td>
					    <table width=\"120\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\">
							<tr>
								<td>
								<form action=\"index.php?action=login\" method=\"post\"> 
								<input type=\"hidden\" name=\"paction\" value=\"$paction\">
								<input type=\"hidden\" name=\"pid\" value=\"$id\">
								<font size=\"1\">USERNAME:</font><br>
								<input type=\"text\" name=\"login\" size=\"10\" class=\"input_box\"><br>
								<font size=\"1\">PASSWORD:</font><br>
						        <input type=\"password\" name=\"password\" size=\"10\" class=\"input_box\"><br>
								</td>
							</tr>
							<tr>
								<td>
								<div align=\"center\"><input type=\"image\" name=\"Submit\" src=\"images/go.gif\" border=0></div>
								</td>
							</tr>
						</table>
					    <div align=\"center\"><a href=\"index.php?action=login\" class=\"small\">Forgot Password?</a><br><a href=\"index.php?action=newaccount\" class=\"small\">New User?</a></div>	
					</td>
				</tr></form>
		");
	} else {
	    $user = getuser();
		$subtotal = subtotal();
		print(" <tr>
				<td bgcolor=\"$tablehead\"><strong><font color=\"$tableheadtext\"><div align=\"center\">Welcome Back</div></strong></font></td>
				</tr>
				<tr>
					<td>
					    <table width=\"120\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\">
							<tr>
								<td>
								<div align=\"left\">
								<strong>Account:</strong><br>&nbsp;&nbsp;&nbsp;$user[name]<br>
								<strong>Your Cart:</strong><br>&nbsp;&nbsp;&nbsp;(<font color=\"Red\"><strong>$total_items</strong></font>) <i>Items</i><br>
							    <strong>Subtotal:</strong><br>&nbsp;&nbsp;&nbsp;$cs$subtotal<br>
								</div><br>
								<div align=\"center\"><a href=\"index.php?action=logout\" class=\"small\">LOGOUT</a></div></td>
							</tr>
						</table>
					</td>
				</tr>
		 ");
	}
}

// ###################### header stuff #######################
function start ($action) {
   global $header1, $title, $tablehead, $tableheadtext, $tablebg, $tableborder, $total_items, $shopimage, $useheader, $usefooter, $myheader, $myfooter, $css;
   if ($useheader == "Yes") {
       print("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n<HTML>\n<HEAD>\n");
	   eval("echo dovars(\"".$header1."\");");
   } else {
      include("$myheader");
   }
   firsttable();
   ?>
	<SCRIPT Language="Javascript">  
	var NS = (navigator.appName == "Netscape");
	var VERSION = parseInt(navigator.appVersion);
	if (VERSION = NS) {
	   document.write('<br>');        
	}
	</script>
	<div align="center">
	<table width="750" border="0" cellspacing="0" cellpadding="1" align="center">
	<tr>
	    <td align="left" valign="top" width="150">
			<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="<?PHP echo $tableborder ?>" bgcolor="<?PHP echo $tablebg ?>">
				<tr>
					<td bgcolor="<?PHP echo $tablehead ?>"><strong><font color="<?PHP echo $tableheadtext ?>"><div align="center">Category Index</div></font></strong></td>
				</tr>
				<tr>
					<td>
   <?PHP
}

// ###################### footer stuff #######################
function showend ($action) {
   global $footer, $title, $tablehead, $tableheadtext, $homeurl, $hometitle, $shopurl, $tablebg, $tableborder, 
   $productpath, $usefooter, $myfooter, $thumbwidth, $thumbheight, $showitem, $cs, $companyname;
   ?>
   </td>
		<td width="5">&nbsp;</td>
		<td align="right" valign="top" width="150">
		    <table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="<?PHP echo $tableborder ?>" bgcolor="<?PHP echo $tablebg ?>"><!--CyKuH-->
				<?PHP
				loginarea($action);
				?>
			</table><br>
			<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="<?PHP echo $tableborder ?>" bgcolor="<?PHP echo $tablebg ?>">
				<tr>
					<td bgcolor="<?PHP echo $tablehead ?>"><strong><font color="<?PHP echo $tableheadtext ?>"><div align="center">Current Specials</div></font></strong></td>
				</tr>
				<tr>
					<td>
						<table width="120" border="0" cellspacing="0" cellpadding="4" align="center">
							<tr>
								<td>
								<?PHP
								$random = randomspecial();
								if ($random == "None") { print("No current specials at this time."); } 
								else {
					               $iteminfo = iteminfo($random[itemid]);
								   print("
								   <div align=\"center\">
								   <a href=\"index.php?action=item&id=$random[itemid]\">
								   ");
								   if ($showitem == "picture") {
									  if ($thumbwidth != "" && $thumbheight != "") {
										  print("<img src=\"$productpath$iteminfo[thumb]\" border=\"0\" alt=\"\" width=\"$thumbwidth\" height=\"$thumbheight\"><br>");
									  } else {
										  print("<img src=\"$productpath$iteminfo[thumb]\" border=\"0\" alt=\"\"><br>");
									  }
								   }
								   print("
								   $iteminfo[title]</a><br>
								   <strong><s>$cs$iteminfo[price]</s>&nbsp;&nbsp;<font color=\"Red\">$cs$random[sprice]</font></strong>
								   </div>");
								}
								?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
   </table></div>
   <?PHP
   if ($usefooter == "Yes") {
     eval("echo dovars(\"".$footer."\");");
	 ##Do Not Remove
	 print("<div align=\"center\" class=\"small\"><font color=\"Gray\">$title &copy;Copyright ".date(Y)." <a href=\"$homeurl\" class=\"small\">$companyname</a><br>Powered by \"<!--CyKuH-->SunShop\"</font>
          </div>
          </BODY></HTML>
     ");
	 ##Do Not Remove
   } else {
     include("$myfooter");
   }
   
}?>
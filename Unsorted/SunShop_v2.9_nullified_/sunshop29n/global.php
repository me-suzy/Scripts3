<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.9
// Supplied by          : CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
session_start();
require("./admin/config.php");
include("./admin/state-country.php");
$ssversion = "2.9";

// ###################### Start Database #######################
$dbclassname="admin/db_mysql.php";
require($dbclassname);

$DB_site=new DB_Sql_vb;
$DB_site->appname="SunShop $ssversion";
$DB_site->appshortname="SunShop $ssversion";
$DB_site->database=$dbname;
$DB_site->server=$servername;
$DB_site->user=$dbusername;
$DB_site->password=$dbpassword;
$DB_site->connect();

$alreadydb = 1;

// ################## PHP Live Helper Code ######################
// include("/path/to/phplivehelper/global.php");
// include("/path/to/phplivehelper/trackme.php");


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
	$uspsuser = stripslashes($row[uspsuser]);
	$uspspass = stripslashes($row[uspspass]);
	$catsdisplay = stripslashes($row[catsdisplay]);
	$allwidth = stripslashes($row[allwidth]);
	$centerwidth = stripslashes($row[centerwidth]);
	$tablewidth = stripslashes($row[tablewidth]);
}

// ################# load language file ###################

$success = @include($lang); 
if (!$success && $lang != "") {
	echo "Could not open language file";
	exit;
}


// ################# load template ###################
function loadtemplate($templatename) {
  global $DB_site, $dbprefix;
  $temp=$DB_site->query_first("SELECT * FROM ".$dbprefix."templates WHERE title='".addslashes($templatename)."' LIMIT 1");
  $template=$temp[template];
  return "<!-- TEMPLATE INITIATE: $templatename -->\n$template\n<!-- TEMPLATE TERMINATE: $templatename -->";
}

// ###################### pricelist #######################
function pricelist () {
	global $DB_site, $dbprefix, $centerborder, $centerheader, $centercolor, $centerfont, $centerbg, 
	$thumbheight, $thumbwidth, $productpath, $orderby, $cs, $lang_header, $allwidth;
	$pricelist = "<SCRIPT LANGUAGE='JavaScript'>\n<!--\nfunction formHandler(form){\nvar URL = document.form.category.options[document.form.category.selectedIndex].value;\nwindow.location.href = URL;\n}\n// End -->\n</SCRIPT>";
	$pricelist .= "<a name='top'></a><div align='center'><form name='form'><strong>$lang_header[cats]:</strong>&nbsp;<select name='category'>";
	
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."category ORDER by title");
	while ($row=$DB_site->fetch_array($temp)) {
		$pricelist .= "<option value='#$row[categoryid]'>".stripslashes($row[title])."</option>";
	}
	
	$pricelist .="</select>&nbsp;<input type=button value='Go' onClick='javascript:formHandler()'></form>";
	$pricelist .= "<br><table width='$allwidth' border='$centerborder' cellspacing='0' cellpadding='4' bordercolor='$centercolor'>";
	
	$temp=$DB_site->query("SELECT categoryid FROM ".$dbprefix."items GROUP BY categoryid");
	while ($row=$DB_site->fetch_object($temp)) {
		$temp2=$DB_site->query("SELECT * FROM ".$dbprefix."category WHERE categoryid='$row->categoryid'");
		$row2=$DB_site->fetch_object($temp2);
		
		$pricelist .= "<tr>\n";
		$pricelist .= "<td colspan='6' bgcolor='$centercolor'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='left'><a name='$row2->categoryid'></a><font color='$centerfont'><b>".stripslashes($row2->title)."</b></font></td><td align='right'><a href='#top'>Back To Top</a></td></tr></table></td>\n";
		$pricelist .= "</tr>\n";
		
		$temp3=$DB_site->query("SELECT * FROM ".$dbprefix."items WHERE categoryid='$row->categoryid' ORDER BY $orderby");
		while ($row3=$DB_site->fetch_object($temp3)) {
		    $temp4=$DB_site->query("SELECT * FROM ".$dbprefix."itemoptions WHERE productid='$row3->itemid' ORDER BY order1 LIMIT 0,1");
		    $row4=$DB_site->fetch_array($temp4);
			$option12 = str_replace("->", "<br>", $row4[items]);
			if ($row4[increase]  != "") {
				$price_explode = explode("->",$row4[increase]);
				for ($i=0;$i<count($price_explode);$i++) {
					$price .= $cs.sprintf("%6.2f",($row3->price+$price_explode[$i]))."<br>";
				}
			} else {
				$price = $cs.sprintf("%6.2f",$row3->price);
				$option12 = "&nbsp;";
			}
			
			if (strlen(strip_tags(stripslashes($row3->poverview))) > 300) {
		       $desc = strip_tags(stripslashes(stripslashes($row3->poverview)));
			   $desc = substr($desc, 0, 300);
			   $desc = $desc . "...";
		    }				
		    else { $desc = strip_tags(stripslashes($row3->poverview)); }
			
			$pricelist .= "<tr>\n";
			if (!isset($thumbwidth) || $thumbwidth == "" || $thumbheight == "" || !isset($thumbheight)) {
				$pricelist .= "<td valign='top' nowrap><a href='index.php?action=item&id=$row3->itemid'><img border='0' src='$productpath$row3->thumb'></a></td>\n";
			} else {
				$pricelist .= "<td valign='top' nowrap><a href='index.php?action=item&id=$row3->itemid'><img width='$thumbwidth' height='$thumbheight' border='0' src='$productpath$row3->thumb'></a></td>\n";
			}
			$pricelist .= "<td align='center' valign='top' nowrap>$row3->itemid</td>\n";
			$pricelist .= "<td valign='top'><a href='index.php?action=item&id=$row3->itemid'>".stripslashes($row3->title)."</a></td>\n";
			$pricelist .= "<td valign='top'>$desc</td>\n";
			$pricelist .= "<td valign='top' nowrap>$option12</td>\n";
			$pricelist .= "<td valign='top' nowrap>$price</td>\n";
			$pricelist .= "</tr>\n";
			
			unset($price);
		}
	}
	$pricelist .= "</table></div>";
	echo $pricelist;
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
		  $field[$index] = $fieldsd[$i];
		  $index++;
		}
		$item_tray[$i] = "";
	}
	for ($i = 0; $i < $index; $i++) {
	    $item_tray[$i]=$it[$i]."->".$hm[$i]."->".$op[$i]."->".$pop[$i]."->".$field[$i];
		$new_op = $new_op.$op[$i]."->";
	}
	$total_items=$index;
	$options_only=$new_op;
	header("location:index.php?action=viewcart");
    exit;
}

// ###################### count subcategories #######################
function countsubcat ($id) {
    global $DB_site, $dbprefix;
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."subcategory where categoryid='$id'");
	$count=$DB_site->num_rows($temp);
	return $count;
}

// ###################### show categories #######################
function showcategories ($start, $id, $subid) {
    global $DB_site, $catimage, $catopen, $dbprefix, $itemsub, $showspecials, $showbestsellers, $showcattotals, $lang_cat,
	$catsdisplay;
	$startt = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td valign=\"top\">";
	$midt   = "</td><td valign=\"top\">";
	$endt   = "</td></tr></table>";
	$num = 0;
	if ($start == "") { $start = 0; } 
	$itemsub = $start;
	if ($id != "") {
	    if ($showspecials == "Yes") { 
		    if ($id == "specials") {
			   $out .= "$startt&nbsp;&nbsp;$midt<img src=\"$catopen\" border=\"0\" alt=\"\">&nbsp;$midt<a href=\"index.php?action=category&id=specials\">$lang_cat[spec]</a>$endt";
			}  else {
			   $out .= "$startt&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=specials\"><img src=\"$catimage\" border=\"0\" alt=\"\"></a>&nbsp;$midt<a href=\"index.php?action=category&id=specials\">$lang_cat[spec]</a>$endt";
			}
		}
		if ($showbestsellers == "Yes") {
			if ($id == "bestsellers") {
			   $out .= "$startt&nbsp;&nbsp;$midt<img src=\"$catopen\" border=\"0\" alt=\"\">&nbsp;$midt<a href=\"index.php?action=category&id=bestsellers\">$lang_cat[best]</a>$endt";
			}  else {
			   $out .= "$startt&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=bestsellers\"><img src=\"$catimage\" border=\"0\" alt=\"\"></a>&nbsp;$midt<a href=\"index.php?action=category&id=bestsellers\">$lang_cat[best]</a>$endt";
			}
		}
		$temp1=$DB_site->query("SELECT * FROM ".$dbprefix."category ORDER by displayorder");
		while ($row1=$DB_site->fetch_array($temp1)) {
	        $totitems = gettotal($row1[categoryid], "");
			if ($showcattotals == "Yes") { $toprintn = " ($totitems)"; }
			if ($row1[categoryid] == $id) {
				$out .= "$startt&nbsp;&nbsp;$midt<img src=\"$catopen\" border=\"0\" alt=\"\">&nbsp;$midt<a href=\"index.php?action=category&id=$row1[categoryid]\">$row1[stitle]</a>$toprintn$endt";
				$total = countsubcat($row1[categoryid]);
				if ($catsdisplay != 0) {
					$temp2=$DB_site->query("SELECT * FROM ".$dbprefix."subcategory where categoryid='$row1[categoryid]' ORDER by displayorder LIMIT $start,$catsdisplay");
				} else {
					$temp2=$DB_site->query("SELECT * FROM ".$dbprefix."subcategory where categoryid='$row1[categoryid]' ORDER by displayorder");
				}
				while ($row2=$DB_site->fetch_array($temp2)) {
				    $totsitems = gettotal ($row1[categoryid], $row2[subcategoryid]);
					if ($showcattotals == "Yes") { $toprint = " ($totsitems)"; }
				    $num++;
				    if ($row2[subcategoryid] == $subid) {
						$out .= "$startt&nbsp;&nbsp;$midt<img src=\"images/sub.gif\" border=\"0\" alt=\"\"><img src=\"$catopen\" border=\"0\" alt=\"\">&nbsp;$midt<a href=\"index.php?action=category&subid=$row2[subcategoryid]&id=$row2[categoryid]&substart=$itemsub\">$row2[stitle]</a>$toprint$endt";
					} 
					else {
						$out .= "$startt&nbsp;&nbsp;$midt<img src=\"images/sub.gif\" border=\"0\" alt=\"\"><img src=\"$catimage\" border=\"0\" alt=\"\">&nbsp;$midt<a href=\"index.php?action=category&subid=$row2[subcategoryid]&id=$row2[categoryid]&substart=$itemsub\">$row2[stitle]</a>$toprint$endt";
					}
				}
				if ($catsdisplay != 0) {
					if ($start > 0) {
					    $pre = $start - $catsdisplay;
						if ($pre < 0) { $pre = 0; }
						$out .= "$startt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=$id&substart=$pre\"><i>$lang_cat[prev]</i></a>$endt";
					} 
					$next = $start + $catsdisplay;
					if ($total > $next) {
						$out .= "$startt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=$id&substart=$next\"><i>$lang_cat[next]</i></a>$endt";
					}
				}
			} 
			else {
			    $out .= "$startt&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=$row1[categoryid]\"><img src=\"$catimage\" border=\"0\" alt=\"\"></a>&nbsp;$midt<a href=\"index.php?action=category&id=$row1[categoryid]\">$row1[stitle]</a>$toprintn$endt";   
			}
		}
	} else {
	    if ($showspecials == "Yes") {
			$out .= "$startt&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=specials\"><img src=\"$catimage\" border=\"0\" alt=\"\"></a>&nbsp;$midt<a href=\"index.php?action=category&id=specials\">$lang_cat[spec]</a>$endt";
		}
		if ($showbestsellers == "Yes") {
			$out .= "$startt&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=bestsellers\"><img src=\"$catimage\" border=\"0\" alt=\"\"></a>&nbsp;$midt<a href=\"index.php?action=category&id=bestsellers\">$lang_cat[best]</a>$endt";
		}
		$temp1=$DB_site->query("SELECT * FROM ".$dbprefix."category ORDER by displayorder");
		while ($row1=$DB_site->fetch_array($temp1)) {
		    $totitems = gettotal ($row1[categoryid], "");
			if ($showcattotals == "Yes") { $toprint = " ($totitems)"; }
			$out .= "$startt&nbsp;&nbsp;$midt<a href=\"index.php?action=category&id=$row1[categoryid]\"><img src=\"$catimage\" border=\"0\" alt=\"\"></a>&nbsp;$midt<a href=\"index.php?action=category&id=$row1[categoryid]\">$row1[stitle]</a>$toprint$endt";
		}
	}
	return $out;
}

// ###################### payop #####################
function payop ($id) {
	global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."itemoptions where optionid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
		$iarray = explode("->", $row[items]);
		return $iarray;
	}
}

// ###################### payopcalc #####################
function payopcalc ($id, $option) {
	global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."itemoptions where optionid='$id'");
	$row=$DB_site->fetch_array($temps);
    if ($row[increase] != "") {
		$iarray = explode("->", $row[increase]);
		$iarray2 = explode("->", $row[items]);
	} else {
	    $iarray2 = explode("->", $row[items]);
	}
	for ($i=0; $i< count($iarray2); $i++) {
		if ($option == $iarray2[$i]) {
		    if ($iarray != "") {
				$add[price] = $iarray[$i];
			} else {
				$add[price] = 0;
			}
			$add[name] = $iarray2[$i];
			return $add;
		}
	}
	return 0;
}

// ###################### additem #######################
function additem ($item, $num, $option, $fields, $fnames) {
    global $total_items, $item_tray, $options_only, $options_array, $outofstock;
	if ($outofstock == "No") {
	    $iteminfo = iteminfo($item);
		if ($num > $iteminfo[quantity]) {
			header("location:index.php?action=viewcart&error=outofstock");
			exit;
		}
	}
	$count = 0;
	for ($i=0; $i<count($option); $i++) {
        if ($option[$i] != "") { 
		   $split = explode("->", $option[$i]);
		   $addp = payopcalc($split[0], $split[1]);
		   $load[$count] = $addp[name]; 
		   $load2[$count] = $addp[price];
		   $count++;
	    }
	}
	
	$loadt = "";
	$load2t = 0;
	for ($i=0; $i<$count; $i++) {
	    if ($loadt == "") {
	        $loadt = $load[$i];
			$load2t = $load2[$i];
		} else {
			$loadt = $loadt.", ".$load[$i];
			$load2t = $load2t+$load2[$i];
		}
	}
	
	$loadf = "";
	for ($i=0; $i<count($fnames); $i++) {
	    $fields[$i] = str_replace("\n","",$fields[$i]);
		$fields[$i] = str_replace("\r"," ",$fields[$i]);
        $loadf .= $fnames[$i]."::".strip_tags($fields[$i])."||";
	}
	
	if ($loadt == "") {
		$loadt = "None";
	}
	$item_tray[$total_items] = $item."->".$num."->".$loadt."->".$load2t."->".$loadf;
	$options_only = $options_only.$loadt."->";
	$total_items++;
	return;
}

// ###################### authenticate ##################
function authenticate ($login, $password) {
    global $DB_site, $dbprefix, $USERIN, $PASSIN;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$login'");
    while ($row=$DB_site->fetch_array($temps)) {
		if ($password == $row[password] && $row[name] != "Admin_Account" && $row[name] != "Add_Admin") {
		   $result = "valid";
		   session_register("USERIN");
		   session_register("PASSIN");
		   $USERIN=$login;
		   $PASSIN=$password;
		} else {
		   $result = "invalid";
		}
		return $result;
	}
	$result = "invalid";
	return $result;
}

// ###################### verify ##################
function verify () {
    global $DB_site, $dbprefix;
	$temp=$DB_site->query("SELECT license from ".$dbprefix."options");
	$row=$DB_site->fetch_object($temp);
	echo $row->license;
}

// ###################### authenticate2 ##################
function authenticate2 ($login, $password) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$login'");
    while ($row=$DB_site->fetch_array($temps)) {
		if ($password == $row[password] && $row[name] != "Admin_Account") {
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
			$userinfo[baddress_line1] = stripslashes($row[baddress_line1]);
			$userinfo[baddress_line2] = stripslashes($row[baddress_line2]);
			$userinfo[bcity] = stripslashes($row[bcity]);
			$userinfo[bstate] = stripslashes($row[bstate]);
			$userinfo[bzip] = stripslashes($row[bzip]);
			$userinfo[bcountry] = stripslashes($row[bcountry]);
			$userinfo[phone] = stripslashes($row[phone]);
			$userinfo[email] = stripslashes($row[email]);
			$userinfo[lastvisit] = stripslashes($row[lastvisit]);
			return $userinfo;
		}
	}
	return $userinfo;
}

// ###################### applytax ##################
function applytax ($tot){
    global $DB_site, $shopstate, $USERIN, $taxrate, $dbprefix;
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$USERIN'");
	while ($row=$DB_site->fetch_array($temp)) {
	     $state = $row[state];
	}
	if($state == $shopstate) {
		$tax = ($tot * $taxrate);
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
		$iteminfo[categoryid] = stripslashes($row[categoryid]); $iteminfo[title] = stripslashes($row[title]);
		$iteminfo[stitle] = stripslashes($row[stitle]); $iteminfo[imagename] = stripslashes($row[imagename]);
		$iteminfo[thumb] = stripslashes($row[thumb]); $iteminfo[poverview] = stripslashes($row[poverview]);
		$iteminfo[pdetails] = stripslashes($row[pdetails]); $iteminfo[quantity] = stripslashes($row[quantity]);
		$iteminfo[sold] = stripslashes($row[sold]); $iteminfo[shipinfo] = stripslashes($row[shipinfo]);
		$iteminfo[price] = stripslashes($row[price]);	$iteminfo[weight] = stripslashes($row[weight]);
		$iteminfo[viewable] = stripslashes($row[viewable]); $iteminfo[itemid] = stripslashes($row[itemid]); 
		$iteminfo[sku] = stripslashes($row[sku]);
	}
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."specials where itemid='$id'");
    while ($row=$DB_site->fetch_array($temp)) {
		$iteminfo[specialid] = stripslashes($row[specialid]); $iteminfo[sdescription] = stripslashes($row[sdescription]); 
		$iteminfo[sprice] = stripslashes($row[sprice]);
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

// ###################### uspsquote #####################
function uspsquote ($orzip, $deszip, $weight, $service) {
    global $uspsserver, $uspsuser, $uspspass;
	$usps = new USPS; 
	$usps->setUserName($uspsuser);
	$usps->setPass($uspspass);
	$usps->setService($service);
	$usps->setServer($uspsserver);
	$usps->setDestZip($deszip); 
	$usps->setOrigZip($orzip); 
	$usps->setWeight($weight, "0");
	$usps->setContainer("None");
	if ($service == "Parcel") {
		$usps->setMachinable("True");
	}
	$quote = $usps->getPrice();
	return $quote;
}

// ###################### upsquote #####################
function upsquote ($pro, $orzip, $orcount, $deszip, $descount, $rate, $weight) {
    $rate = new Ups; 
    $rate->upsProduct("$pro"); 
    $rate->origin("$orzip", "$orcount");
    $rate->dest("$deszip", "$descount");
    $rate->rate("$rate");   
    $rate->container("CP");
    $rate->weight("$weight"); 
    $rate->rescom("RES"); 
    $quote = $rate->getQuote(); 
    return $quote; 

}

// ###################### containsletters #####################
function containsletters ($quote) {
    $compare = strtolower($quote); $error = 0;
	$letters = "a b c d e f g h i j k l m n o p q r s t u v w z y z";
	$thearray = explode(" ", $letters);
	for ($i=0;$i<count($thearray);$i++) {
		$location = strpos($compare, $thearray[$i]);
		if ($location > 0) {
			$error = 1;
			return $error;
		}
	}
	return $error;
}

// ###################### getshipping #####################
function getshipping ($tot) {
	global $method, $total_items, $shoprate, $shipups, $grnd, $nextdayair, $seconddayair, $threeday, $canada, $worldwidex, 
	$worldwidexplus, $shopzip, $shopcountry, $shipchart, $ship1p1, $ship1us, $ship1ca, $ship2, $ship2p1, $ship2p2, $ship2us, 
	$ship2ca, $ship3, $ship3p1, $ship3p2, $ship3us, $ship3ca, $ship4p1, $ship4us, $ship4ca, $handling, $shipcalc, $shipusps;
	
	require("shipping.php");
	$userinfo = getuser();
	$weight = calculateweight();
	if ($shipcalc == "Yes") {
		if ($shipups == "Yes") {
		    $shiprate[ups] = 1;
			if ($grnd == "Yes") {
			   $rate = upsquote("GND", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "CC", $weight);
			   $error = containsletters($rate);
			   if (isset($rate) && $error != 1) {
				   $shiprate[grnd] = number_format($rate + $handling,2,".","");
			   } else { $shiprate[grnd] = $rate; }
		    }
			if ($nextdayair == "Yes") {
			   $rate = upsquote("1DA", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "CC", $weight);
			   $error = containsletters($rate);
			   if (isset($rate) && $error != 1) {
				   $shiprate[nextdayair] = number_format($rate + $handling,2,".","");
			   } else { $shiprate[nextdayair] = $rate; }
		    }
			if ($seconddayair == "Yes") {
			   $rate = upsquote("2DA", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "CC", $weight);
			  $error = containsletters($rate);
			   if (isset($rate) && $error != 1) {
				   $shiprate[seconddayair] = number_format($rate + $handling,2,".","");
			   } else { $shiprate[seconddayair] = $rate; }
		    }
			if ($threeday == "Yes") {
			   $rate = upsquote("3DS", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "CC", $weight);
			   $error = containsletters($rate);
			   if (isset($rate) && $error != 1) {
				   $shiprate[threeday] = number_format($rate + $handling,2,".","");
			   } else { $shiprate[threeday] = $rate; }
		    }
			if ($canada == "Yes") {
			   $rate = upsquote("STD", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "CC", $weight);
			   $error = containsletters($rate);
			   if (isset($rate) && $error != 1) {
				   $shiprate[canada] = number_format($rate + $handling,2,".","");
			   } else { $shiprate[canada] = $rate; }
		    }
			if ($worldwidex == "Yes") {
			   $rate = upsquote("XPR", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "CC", $weight);
			   $error = containsletters($rate);
			   if (isset($rate) && $error != 1) {
				   $shiprate[worldwidex] = number_format($rate + $handling,2,".","");
			   } else { $shiprate[worldwidex] = $rate; }
		    }
			if ($worldwidexplus == "Yes") {
			   $rate = upsquote("XDM", $shopzip, $shopcountry, $userinfo[zip], $userinfo[country], "CC", $weight);
			   $error = containsletters($rate);
			   if (isset($rate) && $error != 1) {
				   $shiprate[worldwidexplus] = number_format($rate + $handling,2,".","");
			   } else { $shiprate[worldwidexplus] = $rate; }
		    }
		} 
		if ($shipusps == "Yes") {
		    $shiprate[usps] = 1;
			$rate = uspsquote($shopzip, $userinfo[zip], $weight, "Express");
			$error = containsletters($rate);
			if (isset($rate) && $error != 1) {
				$shiprate[express] = number_format($rate + $handling,2,".","");
			} else { $shiprate[express] = $rate; }
			$rate = uspsquote($shopzip, $userinfo[zip], $weight, "Priority");
			$error = containsletters($rate);
			if (isset($rate) && $error != 1) {
				$shiprate[priority] = number_format($rate + $handling,2,".","");
			} else { $shiprate[priority] = $rate; }
			$rate = uspsquote($shopzip, $userinfo[zip], $weight, "Parcel");
			$error = containsletters($rate);
			if (isset($rate) && $error != 1) {
				$shiprate[parcel] = number_format($rate + $handling,2,".","");
			} else { $shiprate[parcel] = $rate; }
		}
		return $shiprate;
	} elseif ($shipchart == "Yes") {
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
				    $shiprate[price] = $ship3ca + $handling;
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
		if ($method == "percentage") {
			$rate = ($tot * $shoprate);
			$shiprate[price] = $rate + $handling;
			return $shiprate;
		}
	}
}

// ###################### selectshipping ##################
function calculate_discounts () {
	global $dbprefix, $DB_site, $item_tray, $total_items, $itemd, $quantityd, $optionsd, $fieldsd, $options_array,
	$options_only;
	parsecart();
	for ($i = 0; $i < $total_items; $i++) {
	    $temp = $itemd[$i];
	    if ($quan[$temp] == "") {
			$array1 .= $temp."->";
			$quan[$temp] = $quantityd[$i];
		} else {
			$quan[$temp] = $quan[$temp] + $quantityd[$i];
		}
	}
	$array = explode("->", $array1);
	for ($i = 0; $i < (count($array)-1); $i++) {
		if ($array[$i] != "") {
			$temp=$DB_site->query("SELECT * FROM ".$dbprefix."discounts where productid='$array[$i]' AND displayit='Y'");
			while ($row=$DB_site->fetch_array($temp)) {
				if ($row[type] == "F") {
				    $temp1 = $array[$i];
					if ((($quan[$temp1] - $row[discount]) >= $row[frombuy]) && (($quan[$temp1] - $row[discount]) <= $row[tobuy])) {
						for ($j = 0; $j < $total_items; $j++) {
						    if ($itemd[$j] != $array[$i]) {
								continue;
							}
						    $iteminfo = iteminfo($itemd[$j]);
							if (isset($iteminfo[sprice])) { 
							  $dprice = $iteminfo[sprice] + $payopd[$j];
							} else { 
							  $dprice = $iteminfo[price] + $payopd[$j]; 
							}
							if ($lowest == "" || $dprice < $lowest) {
								$lowest = $dprice;
								$lowestquantity = $quantityd[$j];
							}
							if ($lowest2 == "" || $dprice < $lowest2) {
								$lowest2 = $dprice;
								$lowestquantity2 = $quantityd[$j];
							}
						}
						if ($row[discount] <= $lowestquantity) {
							$discount += $lowest * $row[discount];
						} else {
							$discount += $lowest * $lowestquantity;
							$num = $row[discount] - $lowestquantity;
							$discount += $lowest2 * $lowestquantity2;
						}
					}
				}
				if ($row[type] == "D") {
					if ($quan[$array[$i]] >= $row[frombuy] && $quan[$array[$i]] <= $row[tobuy]) {
						$discount += $row[discount];
					}
				}
				if ($row[type] == "P") {
					if ($quan[$array[$i]] >= $row[frombuy] && $quan[$array[$i]] <= $row[tobuy]) {
						for ($j = 0; $j < $total_items; $j++) {
						    if ($itemd[$j] != $array[$i]) {
								continue;
							}
						    $iteminfo = iteminfo($itemd[$j]);
							if (isset($iteminfo[sprice])) { 
							  $dprice = $iteminfo[sprice] + $payopd[$j];
							} else { 
							  $dprice = $iteminfo[price] + $payopd[$j]; 
							}
							if ($lowest == "" || $dprice < $lowest) {
								$lowest = $dprice;
								$lowestquantity = $quantityd[$j];
							}
						}
						$discount += $lowest * $row[discount];
					}
				}
			}
		}
	}
	return number_format($discount,2,".","");
}

// ###################### selectshipping ##################
function selectshipping () {
	global $item_tray, $total_items, $itemd, $quantityd, $optionsd, $payopd, $securepath, $shopurl, $USERIN, 
	$PASSIN, $shipchart, $check, $fax, $moneyorder, $cc, $options, $paypal, $payopp, $options_only, $fieldsd,
	$options_array, $cs, $po, $lang_ship, $lang_pay, $mustsignup, $tablehead, $tablebg, $tableheadtext, $centercolor,
	$lang_checkout, $ret; 
	
	eval("\$js1 = \"".loadtemplate("misc_javascript")."\";");
    $out .= "
	<DIV ID=\"toolTipLayer\" STYLE=\"position:absolute; visibility:hidden; z-index:1000;\"></DIV>".stripslashes($js1)."\n
	<form action=\"".$securepath."checkout.php\" method=\"post\">
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<td>
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">
					<tr>
						<td width=\"10%\"><div align=\"center\"><img src=\"images/quantity.gif\" border=\"0\" alt=\"\"></div></td>
						<td width=\"40%\"><div align=\"center\"><img src=\"images/product.gif\" border=\"0\" alt=\"\"></div></td>
						<td width=\"30%\"><div align=\"center\"><img src=\"images/options.gif\" border=\"0\" alt=\"\"></div></td>
						<td width=\"20%\"><div align=\"center\"><img src=\"images/unitprice.gif\" border=\"0\" alt=\"\"></div></td>
					</tr>";
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
					if (strlen($doptions) > 15) {
					   $desc = substr($doptions, 0, 15);
					   $desc = $desc . "...";
				    } else { $desc = $doptions; }
					$out .= "
					<tr>
						<td width=\"10%\" align=\"center\" valign=\"top\">$quantityd[$i]</td>
						<td width=\"40%\" align=\"center\" valign=\"top\">$iteminfo[title]</td>
						<td onMouseOver=\"toolTip('$doptions";
					$custom = explode("||", $fieldsd[$i]);
					for ($j = 0; $j < count($custom); $j++) {
						if ($custom[$j] != "") {
							$put = explode("::", $custom[$j]);
							$out .= "<br>$put[0]: $put[1]";
						} 
					}
					$out .= "',CAPTION,'$iteminfo[title] $lang_checkout[options]')\" onMouseOut=\"toolTip();\" width=\"30%\" align=\"center\" valign=\"top\">$desc</td>
						<td width=\"20%\" align=\"center\" valign=\"top\">$dprice</td>
					</tr>";
				}
				
				$tot = $tax + $subtotal;
				$tot1 = number_format($tot,2,".","");
				$ret = calculate_discounts();
				$tot = number_format($tot1-$ret,2,".","");
				$tax = applytax($tot);
				$out .= "
				</table>
			</td>
		</tr>
		<tr>
			<td>
			    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
					<tr>
						<td width=\"80%\">&nbsp;</td>
						<td width=\"20%\"><hr size=1 color=\"$centercolor\"></td>
					</tr>";
					if ($ret != "0.00") {
					$out .= "
					<tr>
						<td width=\"80%\"><div align=\"right\"><strong>$lang_ship[pretotal]:</strong></div></td>
						<td width=\"20%\"><div align=\"center\">".$cs.$tot1."</div></td>
					</tr>
					<tr>
						<td width=\"80%\"><div align=\"right\"><strong><font color=\"#FF0000\">$lang_ship[discount]:</font></strong></div></td>
						<td width=\"20%\"><div align=\"center\">-".$cs.$ret."</div></td>
					</tr>
					";
					}
					$shiprate = getshipping($tot);
					if ($shiprate[method] == "chart") {
					$sprice1 = number_format($shiprate[price],2,".","");
                    $out .= "
					<tr>
						<td width=\"80%\"><div align=\"right\"><strong>$lang_ship[shipping]:</strong></div></td>
						<td width=\"20%\"><div align=center>".$cs.$sprice1."</div></td>
						<input type=\"hidden\" name=\"shipping\" value=\"$sprice1\">
					</tr>";
					}
					if ($shiprate[method] == "fixed") {
					$sprice1 = number_format($shiprate[price],2,".","");
                    $out .= "
					<tr>
						<td width=\"80%\"><div align=\"right\"><strong>$lang_ship[shipping]:</strong></div></td>
						<td width=\"20%\"><div align=\"center\">".$cs.$sprice1."</div></td>
						<input type=\"hidden\" name=\"shipping\" value=\"$sprice1\">
					</tr>";
					}
					if ($shiprate[method] == "fixed" || $shiprate[method] == "chart") {
					$tot = $tot + $shiprate[price];
					$tot = number_format($tot,2,".","");
                    $out .= "
					<tr>
						<td width=\"80%\"><div align=\"right\"><strong>$lang_ship[tax]:</strong></div></td>
						<td width=\"20%\"><div align=\"center\">".$cs.$tax."</div></td>
						<input type=\"hidden\" name=\"tax\" value=\"$tax\">
					</tr>";
					$out .= "
					<tr>
						<td width=\"80%\"><div align=\"right\"><strong>$lang_ship[total]:</strong></div></td>
						<td width=\"20%\"><div align=\"center\">".$cs.$tot."</div></td>
						<input type=\"hidden\" name=\"total\" value=\"$tot\">
					</tr>";
					} else {
                    $out .= "
					<tr>
						<td width=\"80%\"><div align=\"right\"><strong>$lang_ship[subtotal]:</strong></div></td>
						<td width=\"20%\"><div align=\"center\">".$cs.$tot."</div></td>
						<input type=\"hidden\" name=\"total\" value=\"$tot\">
						<input type=\"hidden\" name=\"do\" value=\"addship\">
					</tr>";
					$out .= "
					<tr>
						<td width=\"80%\"><div align=\"right\"><strong>$lang_ship[tax]:</strong></div></td>
						<td width=\"20%\"><div align=\"center\">".$cs.$tax."</div></td>
						<input type=\"hidden\" name=\"tax\" value=\"$tax\">
					</tr>";
					}
				$out .= "
				</table>
			</td>
		</tr>
	</table><br>
	<hr size=1 width=\"100%\" color=\"$centercolor\">";
	$userinfo = getuser(); $atleastone = 0;
	$out .= "<div align=\"left\"><table width=\"100%\"><tr>";
	if ($shiprate[ups] == 1 || $shiprate[usps] == 1) {
		$out .= "<td width=\"50%\" align=\"left\" valign=\"top\"><strong>Shipping Method:</strong><br>";
		if (isset($shiprate[grnd]) && containsletters($shiprate[grnd]) != 1) {
			$out .= "&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"Ground\" checked>$lang_ship[ground]&nbsp;&nbsp;(<strong>$cs$shiprate[grnd]</strong>)<br>
			<input type=\"hidden\" name=\"shipprice[Ground]\" value=\"$shiprate[grnd]\">";
			$atleastone = 1;
		}
		if (isset($shiprate[nextdayair]) && containsletters($shiprate[nextdayair]) != 1) {
		    $out .= "&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"Next_Day_Air\" checked>$lang_ship[nda]&nbsp;&nbsp;(<strong>$cs$shiprate[nextdayair]</strong>)<br>
		    <input type=\"hidden\" name=\"shipprice[Next_Day_Air]\" value=\"$shiprate[nextdayair]\">";
			$atleastone = 1;
		}
		if (isset($shiprate[seconddayair]) && containsletters($shiprate[seconddayair]) != 1) {
		    $out .= "&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"Second_Day_Air\" checked>$lang_ship[sda]&nbsp;&nbsp;(<strong>$cs$shiprate[seconddayair]</strong>)<br>
			<input type=\"hidden\" name=\"shipprice[Second_Day_Air]\" value=\"$shiprate[seconddayair]\">";
			$atleastone = 1;
		}
		if (isset($shiprate[threeday]) && containsletters($shiprate[threeday]) != 1) {
		    $out .= "&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"3_Day_Select\" checked>$lang_ship[tds]&nbsp;&nbsp;(<strong>$cs$shiprate[threeday]</strong>)<br>
			<input type=\"hidden\" name=\"shipprice[3_Day_Select]\" value=\"$shiprate[threeday]\">";
			$atleastone = 1;
		}
		if (isset($shiprate[canada]) && containsletters($shiprate[canada]) != 1) {
		    $out .= "&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"Canada_Standard\" checked>$lang_ship[cs]&nbsp;&nbsp;(<strong>$cs$shiprate[canada]</strong>)<br>
			<input type=\"hidden\" name=\"shipprice[Canada_Standard]\" value=\"$shiprate[canada]\">";
			$atleastone = 1;
		}
		if (isset($shiprate[worldwidex]) && containsletters($shiprate[worldwidex]) != 1) {
		    $out .= "&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"Worldwide_Express\" checked>$lang_ship[wwe]&nbsp;&nbsp;(<strong>$cs$shiprate[worldwidex]</strong>)<br>
			<input type=\"hidden\" name=\"shipprice[Worldwide_Express]\" value=\"$shiprate[worldwidex]\">";
			$atleastone = 1;
		}
		if (isset($shiprate[worldwidexplus]) && containsletters($shiprate[worldwidexplus]) != 1) {
		    $out .= "&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"Worldwide_Express_Plus\" checked>$lang_ship[wwep]&nbsp;&nbsp;(<strong>$cs$shiprate[worldwidexplus]</strong>)<br>
			<input type=\"hidden\" name=\"shipprice[Worldwide_Express_Plus]\" value=\"$shiprate[worldwidexplus]\">";
			$atleastone = 1;
		}
		if (isset($shiprate[express]) && containsletters($shiprate[express]) != 1 && $shiprate[express] != "0") {
		    $out .= "&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"USPS_Express\" checked>$lang_ship[usps_express]&nbsp;&nbsp;(<strong>$cs$shiprate[express]</strong>)<br>
			<input type=\"hidden\" name=\"shipprice[USPS_Express]\" value=\"$shiprate[express]\">";
			$atleastone = 1;
		}
		if (isset($shiprate[priority]) && containsletters($shiprate[priority]) != 1 && $shiprate[priority] != "0") {
		    $out .= "&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"USPS_Priority\" checked>$lang_ship[usps_priority]&nbsp;&nbsp;(<strong>$cs$shiprate[priority]</strong>)<br>
			<input type=\"hidden\" name=\"shipprice[USPS_Priority]\" value=\"$shiprate[priority]\">";
			$atleastone = 1;
		}
		if (isset($shiprate[parcel]) && containsletters($shiprate[parcel]) != 1 && $shiprate[parcel] != "0") {
		    $out .= "&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"shipselect\" value=\"USPS_Parcel\" checked>$lang_ship[usps_parcel]&nbsp;&nbsp;(<strong>$cs$shiprate[parcel]</strong>)<br>
			<input type=\"hidden\" name=\"shipprice[USPS_Parcel]\" value=\"$shiprate[parcel]\">";
			$atleastone = 1;
		}
		if ($atleastone == 0 && $shiprate[ups] == 1) {
		   $out .= "<font color=\"red\"><i>$shiprate[grnd]</i></font><br>";
		}
		if ($atleastone == 0 && $shiprate[usps] == 1) {
		   $out .= "<font color=\"red\"><i>$shiprate[parcel]</i></font><br>";
		}
		$out .= "</td><td width=\"30\" valign=\"top\">&nbsp;</td><td width=\"50%\" align=\"left\" valign=\"top\">
		       <strong>$lang_ship[ship_address]:</strong><br>&nbsp;&nbsp;&nbsp;$userinfo[address_line1]<br>";
		if ($userinfo[address_line2] != "") { $out .= "&nbsp;&nbsp;&nbsp;" . $userinfo[address_line2]; $out .= "<br>"; } 
		$out .= "&nbsp;&nbsp;&nbsp;$userinfo[city], $userinfo[state] $userinfo[zip]<br>&nbsp;&nbsp;&nbsp;$userinfo[country]<br>";
		if ($mustsignup == "Yes") {
			  $out .= "<div align=\"center\">[<a href=\"index.php?action=editaccount\">$lang_ship[edit_address]</a>]</div>";
		}	  
		$out .= "</td></tr></table><table width=\"100%\" border=\"0\"><tr><td><strong>$lang_pay[pm]:</strong><br>&nbsp;&nbsp;&nbsp;
			   <select name=\"payment1\" class=\"input_box\">";
	    
		if ($cc == "Yes") { $out .= "<option value=\"Credit Card\">".$lang_pay[cc];	}
		if ($check == "Yes") { $out .= "<option value=\"Personal Check\">".$lang_pay[pc]; }
		if ($moneyorder == "Yes") { $out .= "<option value=\"Money Order\">".$lang_pay[mo]; }
		if ($fax == "Yes") { $out .= "<option value=\"Fax Order\">".$lang_pay[fo]; }
		if ($paypal == "Yes") { $out .= "<option value=\"PayPal\">".$lang_pay[pp]; }
		if ($po == "Yes") { $out .= "<option value=\"po\">".$lang_pay[po]; }
		
		$out .= "</select></td><td><strong>$lang_ship[coupon]:</strong><br>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"coupon\" size=\"20\" class=\"input_box\">
			   </td></tr></table><br><strong>$lang_ship[comments]:</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;
			   <textarea cols=\"50\" rows=\"5\" name=\"comments\" class=\"input_box\"></textarea><br>";
	} 
	else {
	    $atleastone = 1;
		$out .= "<td valign=\"top\"><strong>$lang_ship[ship_address]:</strong><br>&nbsp;&nbsp;&nbsp;$userinfo[address_line1]<br>";
		if ($userinfo[address_line2] != "") { $out .= "&nbsp;&nbsp;&nbsp;" . $userinfo[address_line2]; $out .= "<br>"; } 
		$out .= "&nbsp;&nbsp;&nbsp;$userinfo[city], $userinfo[state] $userinfo[zip]<br>&nbsp;&nbsp;&nbsp;$userinfo[country]<br>";
		if ($mustsignup == "Yes") {
			  $out .= "<div align=\"center\">[<a href=\"index.php?action=editaccount\">$lang_ship[edit_address]</a>]</div>";
		}	  
		$out .= "</td><td valign=\"top\"><strong>$lang_pay[pm]:</strong><br>&nbsp;&nbsp;&nbsp;<select name=\"payment1\" class=\"input_box\">";
		
		if ($cc == "Yes") { $out .= "<option value=\"Credit Card\">".$lang_pay[cc];	}
		if ($check == "Yes") { $out .= "<option value=\"Personal Check\">".$lang_pay[pc]; }
		if ($moneyorder == "Yes") { $out .= "<option value=\"Money Order\">".$lang_pay[mo]; }
		if ($fax == "Yes") { $out .= "<option value=\"Fax Order\">".$lang_pay[fo]; }
		if ($paypal == "Yes") { $out .= "<option value=\"PayPal\">".$lang_pay[pp]; }
		if ($po == "Yes") { $out .= "<option value=\"po\">".$lang_pay[po]; }
		
		$out .= "
		</select><br><br><strong>$lang_ship[coupon]:</strong><br>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"coupon\" size=\"20\" class=\"input_box\">
		</td></tr></table><br><strong>$lang_ship[comments]:</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;
		<textarea cols=\"50\" rows=\"5\" name=\"comments\" class=\"input_box\"></textarea><br>";
	}
    $out .= "\n<input type=\"hidden\" name=\"USER\" value=\"$USERIN\">\n";
	$out .= "<input type=\"hidden\" name=\"PASS\" value=\"$PASSIN\">\n";
	$out .= "<input type=\"hidden\" name=\"optionsonly\" value=\"$options_only\">\n";
	$out .= "<input type=\"hidden\" name=\"totalitems\" value=\"$total_items\">\n";
	$out .= "<input type=\"hidden\" name=\"setnew\" value=\"yes\">\n";
	for ($i = 0; $i < $total_items; $i++) {
	    $out .= "<input type=\"hidden\" name=\"itemtray[$i]\" value=\"$item_tray[$i]\">\n";
	}
	if ($atleastone == 1) {
	    $out .= "<div align=\"right\"><input type=\"image\" name=\"submit\" src=\"images/secure.gif\" alt=\"Continue\" border=0></div>
		</form><div align=\"left\">$lang_ship[shipmethod]</div>";
	}
	return $out;
}

// ###################### subtotal ##################
function subtotal() {
    global $item_tray, $itemd, $quantityd, $optionsd, $payopd, $total_items, $ret;
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
		if (isset($ret)) {
			$subtotal = number_format($subtotal-$ret,2,".","");
	    } else {
			$subtotal = number_format($subtotal,2,".","");
		}
	}
	return $subtotal;
}

// ###################### showcart ##################
function showcart () {
	global $item_tray, $total_items, $securepath, $shopurl, $itemd, $quantityd, $optionsd, $payopd, $error, $options_array,
	$cs, $lang_cart, $lang_ship, $prevaction, $previd, $prevsubstart, $mustsignup, $tablehead, $tablebg, $tableheadtext, 
	$centercolor, $centerborder, $fieldsd, $lang_checkout;
	if ($error == "outofstock") { $out = "<font color=\"red\">$lang_cart[outofstock]</font>"; }
	eval("\$js1 = \"".loadtemplate("misc_javascript")."\";");
    $out .= "<DIV ID=\"toolTipLayer\" STYLE=\"position:absolute; visibility:hidden; z-index:1000;\"></DIV>".stripslashes($js1)."\n
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
		<tr>
			<td><form action=\"index.php\" method=\"post\"><input type=\"hidden\" name=\"action\" value=\"updatecart\">
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
					<tr>
						<td width=\"10%\"><div align=\"center\"><img src=\"images/quantity.gif\" border=\"0\" alt=\"\"></div></td>
						<td width=\"40%\"><div align=\"center\"><img src=\"images/product.gif\" border=\"0\" alt=\"\"></div></td>
						<td width=\"30%\"><div align=\"center\"><img src=\"images/options.gif\" border=\"0\" alt=\"\"></div></td>
						<td width=\"20%\"><div align=\"center\"><img src=\"images/unitprice.gif\" border=\"0\" alt=\"\"></div></td>
					</tr>";
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
					if (strlen($doptions) > 15) {
					   $desc = substr($doptions, 0, 15);
					   $desc = $desc . "...";
				    } else { $desc = $doptions; }
					$out .= "
					<tr>
						<td width=\"10%\" align=\"center\" valign=\"top\"><input type=\"text\" name=\"item[$i]\" value=\"$quantityd[$i]\" size=\"1\" class=\"input_box\"></td>
						<td width=\"40%\" align=\"center\" valign=\"top\">$iteminfo[title]</td>
						<td onMouseOver=\"toolTip('$doptions";
					$custom = explode("||", $fieldsd[$i]);
					for ($j = 0; $j < count($custom); $j++) {
						if ($custom[$j] != "") {
							$put = explode("::", $custom[$j]);
							$out .= "<br>$put[0]: $put[1]";
						} 
					}
					$out .= "',CAPTION,'$iteminfo[title] $lang_checkout[options]')\" onMouseOut=\"toolTip();\" width=\"30%\" align=\"center\" valign=\"top\">$desc</td>
						<td width=\"20%\" align=\"center\" valign=\"top\">$dprice</td>
					</tr>";
				}
				$subtotal = number_format($subtotal,2,".","");
				$out .= "
				</table>
			</td>
		</tr>
		<tr>
			<td>";
			    if ($total_items <= 0) { $out .= "<br><div align=\"center\"><i><strong>$lang_cart[noitems]</strong></div></i><br>"; }
				$out .= "
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
					<tr>
						<td width=\"80%\">&nbsp;</td>
						<td width=\"20%\"><hr size=1 color=\"$centercolor\"></td>
					</tr>
					<tr>
						<td width=\"80%\"><div align=\"right\"><strong>$lang_ship[subtotal]:</strong></div></td>
						<td width=\"20%\"><div align=\"center\">".$cs.$subtotal."</div></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><br>
			    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
					<tr>
						<td valign=\"top\"><div align=\"center\"><a href=\"index.php?action=$prevaction&id=$previd&substart=$prevsubstart\"><img src=\"images/shopmore.gif\" alt=\"$lang_ship[shopmore]\" border=\"0\"></a></div></td>
						<td valign=\"top\"><div align=\"center\"><input type=\"image\" name=\"go\" value=\"updatecart\" src=\"images/updatecart.gif\" alt=\"$lang_ship[update]\" border=\"0\"></form></div></td>
						<td valign=\"top\"><div align=\"center\">";
						$answer = islogged(); 
						if ($total_items <= 0) { 
						} elseif ($total_items > 0 && $answer != "Yes" && $mustsignup == "Yes") {
						   $out .= "<img src=\"images/login.gif\" border=\"0\" alt=\"Please Login\">";
						} else { 
							if ($mustsignup == "Yes" || $answer == "Yes") {
							    $out .= "<form action=\"index.php\" method=\"post\"><input type=\"hidden\" name=\"action\" value=\"shipping\"><input type=\"image\" name=\"go\" value=\"checkout\" src=\"images/tocheckout.gif\" alt=\"$lang_cart[checkout2]\" border=0></form></div></td>";
					        } else {
								$out .= "<form action=\"index.php\" method=\"post\"><input type=\"hidden\" name=\"action\" value=\"tempaccount\"><input type=\"image\" name=\"go\" value=\"checkout\" src=\"images/tocheckout.gif\" alt=\"$lang_cart[checkout2]\" border=0></form></div></td>";
							}
						}
					$out .= "
					</tr>
			     </table>
			</td>
		</tr>
	</table>
	<hr size=1 width=\"100%\" color=\"$centercolor\">";
	$answer = islogged();
	if ($answer == "No" && $mustsignup == "Yes") {
	   $out .= "<br><div align=\"left\">";
	   $out .= loginorsignup();	   
	   $out .= "</div>";
	} else {
	  $out .= "<br><div align=\"left\">$lang_cart[checkout]</div>";
	}
	$out .= "</form><br>";
	return $out;
}

// ###################### search ##################
function search ($search, $in) {
	global $DB_site, $start, $orderby, $cs, $dbprefix, $lang_search, $itemsperpage, $thumbwidth, $thumbheight, $productpath,
	$lang_index;
	if ($start == "") { $start = 0; } $num = 0;
	if ($in == "All") {
	    $temp=$DB_site->query("SELECT * FROM ".$dbprefix."items where (viewable='Yes') and (title like '%".addslashes($search)."%' or poverview like '%".addslashes($search)."%' or pdetails like '%".addslashes($search)."%')");
	} else {
		$cat = getcategorynum($in);
		$temp=$DB_site->query("SELECT * FROM ".$dbprefix."items where (viewable='Yes') and (categoryid ='$cat') and (title like '%".addslashes($search)."%' or poverview like '%".addslashes($search)."%' or pdetails like '%".addslashes($search)."%')");
	}
	$num=$DB_site->num_rows($temp);
	if ($num == 0 || $search == "") {
	   $out .= "<div align=\"left\">$lang_search[results] (<strong>0</strong>) $lang_search[results2]:";
	   $out .= "<ul><li>$lang_search[tip1]</li><br><li>$lang_search[tip2]</li>";
	   return $out;
	}
	$prev = $start - $itemsperpage;
	$end = $start + $itemsperpage;
	$tempnum = $start + 1;
	$out .= "<div align=\"left\">$lang_search[found1] <strong>$tempnum</strong> $lang_search[found2] <strong>$end</strong> $lang_search[found3] (<strong>$num</strong>) results:<br><br>";
	if ($in == "All") {
	    $temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where (viewable='Yes') and (title like '%$search%' or poverview like '%$search%' or pdetails like '%$search%') order by $orderby LIMIT $start,$itemsperpage");
	} else {
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where (viewable='Yes') and (categoryid ='$cat') and (title like '%$search%' or poverview like '%$search%' or pdetails like '%$search%') order by $orderby LIMIT $start,$itemsperpage");
	}
	while ($row1=$DB_site->fetch_array($temps)) {
	   $iteminfo = iteminfo($row1[itemid]);
	   if (strlen(strip_tags($iteminfo[poverview])) > 200) {
	       $desc = strip_tags($iteminfo[poverview]);
		   $desc = substr($desc, 0, 200);
		   $desc = $desc . "...";
	   }				
	   else { $desc = strip_tags($iteminfo[poverview]); }
	   $out .= "<table border='0' cellspacing='0' cellpadding='2'><tr><td valign='top'><a href=\"index.php?action=item&substart=$itemsub&id=$iteminfo[itemid]\">";		
	   if ($thumbwidth != "" && $thumbheight != "" && $iteminfo[thumb] != "") {
			$out .= "<img src=\"$productpath$iteminfo[thumb]\" border=\"0\" alt=\"$iteminfo[title]\" width=\"$thumbwidth\" height=\"$thumbheight\"><br>";
	   } elseif ($iteminfo[thumb] != "") {
		    $out .= "<img src=\"$productpath$iteminfo[thumb]\" border=\"0\" alt=\"$iteminfo[title]\"><br>";
	   } else { $out .= "&nbsp"; }
	   $out .= "<br></a></td><td valign='top'><a href=\"index.php?action=item&substart=$itemsub&id=$iteminfo[itemid]\"><b>$iteminfo[title] ($cs$iteminfo[price])</a></b></a><br>$desc <a href=\"index.php?action=item&substart=$itemsub&id=$iteminfo[itemid]\">$lang_index[info]</a></td></tr></table>";
	}
	$out .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n<td width=\"50%\" align=\"left\">";
	if (!($prev < 0)) {
	    $out .= "<a href=\"index.php?action=search&search=$search&in=$in&start=$prev\"><img src=\"images/previous.gif\" border=\"0\" alt=\"Previous\"></a>\n";
	} else {
	    $out .= "&nbsp;";
	}
	$out .= "</td>\n<td width=\"50%\" align=\"right\">";
	if ($num > $end) {
	    $out .= "<a href=\"index.php?action=search&search=$search&in=$in&start=$end\"><img src=\"images/next.gif\" border=\"0\" alt=\"Next\"></a>";
	} else {
	    $out .= "&nbsp;";
	}
	$out .= "</td></tr></table>";
	return $out;
}

// ###################### listitem ##################
function listitem($id) {
    global $DB_site, $cartimage, $productpath, $picwidth, $picheight, $showstock, $showitem, $cs, $dbprefix, $imagel, 
	$lang_item,	$action, $substart;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$id'");
	$row=$DB_site->fetch_array($temps);
    $iteminfo = iteminfo($id);
	if ($iteminfo[sprice] != "") {
	   $display = "<s>$iteminfo[price]</s>&nbsp;<font color=\"red\">$cs$iteminfo[sprice]</font>";
	} else {
	   $display = "$iteminfo[price]";
	}
	eval("\$js1 = \"".loadtemplate("misc_javascript")."\";");
    $out .= "<DIV ID=\"toolTipLayer\" STYLE=\"position:absolute; visibility:hidden; z-index:1000;\"></DIV>".stripslashes($js1)."<br>
			 <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>
	         <td colspan=\"2\"><u><strong>$iteminfo[title]</strong></u><br><br></td></tr>";
	if ($imagel == "2") {
		if ($picwidth != "" && $picheight != "") {
			$out .= "<tr><td colspan=\"2\"><div align=\"center\"><a onclick=\"zoomBox(event,this,700,400,200,100);return false\" href=\"$productpath$iteminfo[imagename]\"><img src=\"$productpath$iteminfo[imagename]\" border=\"0\" width=\"$picwidth\" height=\"$picheight\" alt=\"\"></a><br>(<a onclick=\"zoomBox(event,this,700,400,200,100);return false\" href=\"$productpath$iteminfo[imagename]\">View Larger Image</a>)<br><br></td></tr>";
	    } else {
			$out .= "<tr><td colspan=\"2\"><div align=\"center\"><a onclick=\"zoomBox(event,this,700,400,200,100);return false\" href=\"$productpath$iteminfo[imagename]\"><img src=\"$productpath$iteminfo[imagename]\" border=\"0\" alt=\"\"></a><br>(<a onclick=\"zoomBox(event,this,700,400,200,100);return false\" href=\"$productpath$iteminfo[imagename]\">View Larger Image</a>)<br><br></td></tr>";
		}
	}
	$out .= "<tr><td valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">";
	if ($iteminfo[sku] != "") {
		$out .= "<tr><td><strong>$lang_item[SKU]:</strong></td><td>$iteminfo[sku]</td></tr>";
	}
	$out .= "<tr><td><strong>$lang_item[price]:</strong></td><td>$cs$display</td></tr>
	       <form action=\"index.php?action=addtocart\" method=\"post\"><input type=\"hidden\" name=\"item\" value=\"$iteminfo[itemid]\"><input type=\"hidden\" name=\"prevaction\" value=\"$action\"><input type=\"hidden\" name=\"previd\" value=\"$id\"><input type=\"hidden\" name=\"prevsubstart\" value=\"$substart\"><tr>
		   <td><strong>$lang_item[quantity]:</strong></td><td><input type=\"text\" name=\"quantity\" size=\"2\" maxlength=\"4\" value=\"1\" class=\"input_box\">"; 
	if ($showstock == "Yes") {
		$out .= " of <strong>$iteminfo[quantity]</strong> in stock";
	}
	$out .= "</td></tr>";
	$temps1=$DB_site->query("SELECT * FROM ".$dbprefix."itemoptions where productid='$id' order by order1");
	while ($row1=$DB_site->fetch_array($temps1)) {
		$out .= "<tr><td><strong>".stripslashes($row1[name]).":</strong></td><td><select name=\"option[]\" class=\"input_box\">";
		$oarray = explode("->", $row1[items]);
		$parray = explode("->", $row1[increase]);
		for ($i=0; $i < count($oarray); $i++) {
		    if ($parray != "") {
			    if ($parray[$i] == "") {
				   $out .= "<option value='$row1[optionid]->".stripslashes($oarray[$i])."'>".stripslashes($oarray[$i]);
				} else {
				   $out .= "<option value='$row1[optionid]->".stripslashes($oarray[$i])."'>".stripslashes($oarray[$i])." - $lang_item[add] $cs".number_format($parray[$i],2,".","")."";
				}
			} else {
				$out .= "<option value='$row1[optionid]->".stripslashes($oarray[$i])."'>".stripslashes($oarray[$i]);
			}
		}
		$out .= "</select></td></tr>";
	}
	$out .= "</td></tr>";
	$temps1=$DB_site->query("SELECT * FROM ".$dbprefix."itemfields where productid='$id' order by order1");
	while ($row1=$DB_site->fetch_array($temps1)) {
		$out .= "<tr><td valign=\"top\"><strong>".stripslashes($row1[name]).":</strong></td><td>";
		if ($row1[type] == "textbox") {
			$out .= "<input type='text' name='fields[]' value='".stripslashes($row1[defaultv])."' size='25' class='input_box'>
			         <input type='hidden' name='fnames[]' value='".stripslashes($row1[name])."'>";
		} else {
			$out .= "<textarea wrap='hard' cols='25' rows='5' name='fields[]' class='input_box'>".stripslashes($row1[defaultv])."</textarea>
			        <input type='hidden' name='fnames[]' value='".stripslashes($row1[name])."'>";
		}
		$out .= "</td></tr>";
	}
	$out .= "<tr><td>&nbsp;</td><td><br><input type=\"image\" name=\"Submit\" src=\"$cartimage\" alt=\"$lang_item[tocart]\" border=0></td>
		   </tr></form></table></td><td width=\"210\" valign=\"top\">";
	if ($imagel == "1") {
		if ($picwidth != "" && $picheight != "") {
			$out .= "<div align=\"center\"><a onclick=\"zoomBox(event,this,700,400,200,100);return false\" href=\"$productpath$iteminfo[imagename]\"><img src=\"$productpath$iteminfo[imagename]\" border=\"0\" width=\"$picwidth\" height=\"$picheight\" alt=\"\"></a><br>(<a onclick=\"zoomBox(event,this,700,400,200,100);return false\" href=\"$productpath$iteminfo[imagename]\">View Larger Image</a>)<br>";
	    } else {
			$out .= "<div align=\"center\"><a onclick=\"zoomBox(event,this,700,400,200,100);return false\" href=\"$productpath$iteminfo[imagename]\"><img src=\"$productpath$iteminfo[imagename]\" border=\"0\" alt=\"\"></a><br>(<a onclick=\"zoomBox(event,this,700,400,200,100);return false\" href=\"$productpath$iteminfo[imagename]\">View Larger Image</a>)<br>";
		}
	} else {
		$out .= "&nbsp;";
	}
	$answer = islogged();
    if ($answer == "Yes") {
	 	$out .= "<a href=\"index.php?action=tellafriend&item=$id\"><img src=\"images/tellafriend.gif\" border=\"0\" alt=\"$lang_item[tell]\"></a>";
	}
	$out .= "</div></td></tr></table><br>";
	$temps1=$DB_site->query("SELECT * FROM ".$dbprefix."discounts where productid='$id' AND displayit='Y'");
	while ($row1=$DB_site->fetch_array($temps1)) {
	    $out .= "<font color=\"Red\">";
		if ($row1[type] == "F") {
			$out .= "*";
			$shownote = 1;
		}
		$out .= stripslashes($row1[message])."</font><br><br>";
	}
	if ($iteminfo[sprice] != "" && $iteminfo[sdescription] != "") {
	   $out .= "
	   <div align=\"left\"><strong><font color=\"Red\">$lang_item[sale]:</font></strong>
	   <blockquote><div align=\"left\">$iteminfo[sdescription]</div></blockquote>";
	}
	$out .= "<div align=\"left\">";
	if ($iteminfo[poverview] != "") {
		$out .= "<strong>$lang_item[poverview]:</strong><blockquote><div align=\"left\">$iteminfo[poverview]</div></blockquote>";
	}
	if ($iteminfo[pdetails] != "") {
		$out .= "<strong>$lang_item[details]:</strong><blockquote><div align=\"left\">$iteminfo[pdetails]</div></blockquote>";
	}
	$out .= "</div>";
	if ($shownote == 1) {
		$out .= "<div align=\"center\">(*$lang_item[free])</div>";
	}
	return $out;
}

// ###################### islogged ##################
function islogged () {
    global $USERIN, $PASSIN;
    if (!(session_is_registered("USERIN")) || !(session_is_registered("PASSIN"))) {
	   $answer = "No";
	} else {
	   $cont = authenticate2($USERIN, $PASSIN);
	   if ($cont == "valid") {
	     $answer = "Yes";
	   } else {
	     $answer = "No";
	   }
	}
	return $answer;
}

// ###################### gettotal ##################
function gettotal ($cat, $subid) {
    global $DB_site, $dbprefix;
	if ($subid == "") {
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where categoryid='$cat' AND viewable='Yes'");
	} else {
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where subcategoryid='$subid' AND viewable='Yes'");
	}
	$total=$DB_site->num_rows($temps);
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

// ###################### showitems ##################
function showitems ($cat, $start, $subid) {
	global $DB_site, $productpath, $thumbwidth, $thumbheight, $showitem, $orderby, $cs, $showprice, $dbprefix, $itemsub, 
	$itemsperpage, $lang_prod, $lang_index;
	
	$origin = $start; $prev = $start - $itemsperpage;
    $end = $start + $itemsperpage; $temp = 1;
	
	if ($cat == "specials") { 
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."specials");
	    $total=$DB_site->num_rows($temps);
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."specials LIMIT $start,$itemsperpage");
	} elseif ($cat == "bestsellers") {
	    $temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where viewable='Yes' ORDER by sold desc LIMIT 0,$itemsperpage");
		$total=$DB_site->num_rows($temps);
		if ($total > $itemsperpage) {
			$total = $itemsperpage;
		}
	} else { 
		$total = gettotal($cat, $subid);
		if ($subid == "") {
			$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where categoryid='$cat' AND viewable='Yes' order by $orderby LIMIT $start,$itemsperpage");
		} else {
			$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where subcategoryid='$subid' AND viewable='Yes' order by $orderby LIMIT $start,$itemsperpage");
		}
	}
	
	if ($showitem == "picture") { $out .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">"; } 
	else { $out .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\"><tr><td>"; } 
	
	while ($row=$DB_site->fetch_array($temps)) {
	    $iteminfo = iteminfo($row[itemid]);
	    if ($showitem == "picture" || $cat == "specials") {
			if ($temp == 1) { $out .= "<tr>"; }	 
			$out .= "<td width=\"148\" valign=\"top\"><div align=\"center\"><a href=\"index.php?action=item&substart=$itemsub&id=$row[itemid]\">";
			if ($thumbwidth != "" && $thumbheight != "" && $iteminfo[thumb] != "") {
				$out .= "<a href=\"index.php?action=item&substart=$itemsub&id=$iteminfo[itemid]\"><img src=\"$productpath$iteminfo[thumb]\" border=\"0\" alt=\"$iteminfo[title]\" width=\"$thumbwidth\" height=\"$thumbheight\"><br>";
			} elseif ($iteminfo[thumb] != "") {
				$out .= "<a href=\"index.php?action=item&substart=$itemsub&id=$iteminfo[itemid]\"><img src=\"$productpath$iteminfo[thumb]\" border=\"0\" alt=\"$iteminfo[title]\"><br>";
		    } else {
				$out .= "&nbsp";
			}
		    if ($cat == "specials") {
				$out .= "$iteminfo[title] <font color=\"#FF0000\">(<s>$cs$iteminfo[price]</s>)</font> - ($cs$iteminfo[sprice])</a><br><br>";
	        } else {
				$out .= "$iteminfo[title] ($cs$iteminfo[price])</a><br><br>";
			}
			$out .= "</div></td>";
		} else {
			if (strlen(strip_tags($iteminfo[poverview])) > 200) {
		       $desc = strip_tags($iteminfo[poverview]);
			   $desc = substr($desc, 0, 200);
			   $desc = $desc . "...";
		    }				
		    else { $desc = strip_tags($iteminfo[poverview]); }		
			$out .= "<table border='0' cellspacing='0' cellpadding='2'><tr><td valign='top'>";		
			if ($thumbwidth != "" && $thumbheight != "" && $iteminfo[thumb] != "") {
				$out .= "<a href=\"index.php?action=item&substart=$itemsub&id=$iteminfo[itemid]\"><img src=\"$productpath$iteminfo[thumb]\" border=\"0\" alt=\"$iteminfo[title]\" width=\"$thumbwidth\" height=\"$thumbheight\"><br>";
		    } elseif ($iteminfo[thumb] != "") {
				$out .= "<a href=\"index.php?action=item&substart=$itemsub&id=$iteminfo[itemid]\"><img src=\"$productpath$iteminfo[thumb]\" border=\"0\" alt=\"$iteminfo[title]\"><br>";
		    } else {
				$out .= "&nbsp";
			}
			$out .= "<br></a></td><td valign='top'><a href=\"index.php?action=item&substart=$itemsub&id=$iteminfo[itemid]\"><b>$iteminfo[title] ($cs$iteminfo[price])</a></b></a><br>$desc <a href=\"index.php?action=item&substart=$itemsub&id=$iteminfo[itemid]\">$lang_index[info]</a></td> </tr></table>";
	     }
		 if ($temp == 3 && $showitem == "picture") { print("</tr>"); }
		 if ($temp == 1) { $temp = 2; } elseif ($temp == 2) { $temp = 3; } else { $temp = 1; }
	 }
	 
  	 if ($showitem == "picture" || $cat == "specials") {
		 if ($temp == 2) { $out .= "<td>&nbsp;</td><td>&nbsp;</td></tr>"; }
		 if ($temp == 3) { $out .= "<td>&nbsp;</td></tr>"; }
		 $out .= "</table>";
	 } else { $out .= "</td></tr></table>"; }
	 
	 $out .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\"><tr>";
	 if (!($prev < 0)) {
		 $out .= "<td><div align=\"left\"><a href=\"index.php?action=category&id=$cat&subid=$subid&substart=$itemsub&start=$prev\"><img src=\"images/previous.gif\" border=\"0\" alt=\"Previous\"></a></div></td>";
	 }
	 if ($end < $total) {
		 $out .= "<td><div align=\"right\"><a href=\"index.php?action=category&id=$cat&subid=$subid&substart=$itemsub&start=$end\"><img src=\"images/next.gif\" border=\"0\" alt=\"Next\"></a></div></td>";
	 }
	 $out .= "</tr></table><br>";
	 if ($total < $end) { $temp3 = $total; } else { $temp3 = $end; }
	 $temp4 = $start + 1;
	 $out .= "<div align=\"center\"><strong>$total</strong> $lang_prod[bottom1] <strong>$temp4</strong> $lang_prod[bottom2] <strong>$temp3</strong></div>";
	 if ($total > $itemsperpage) {
		 $times = 0;
		 $page = 1;
		 $out .= "<div align=\"center\"><table><tr><td align=\"center\" valign=\"top\"><div align=\"center\"><strong>$lang_prod[bottum3]</strong>:</div></td></tr><tr><td align=\"justify\" valign=\"top\"><div align=\"justify\">"; 
		 $split = 15;
		 while ($total >= $times) {
			 $out .= "<a href=\"index.php?action=category&id=$cat&subid=$subid&substart=$itemsub&start=$times\">$page</a>&nbsp;&nbsp;";
			 $times = $times + $itemsperpage;
			 if ($page == $split) {
				 $out .= "<br>";
				 $split = $split + 15;
			 }
			 $page++;
		 }
		 $out .= "</div></td></tr></table></div>";
	 }
	 return $out;
}

// ###################### loginorsignup #######################
function loginorsignup () {
    global $contactemail, $title, $state_list, $countries_list, $lang_los;
	$out .= "
	<div align=\"left\">
	<b>$lang_los[los]:</b><br><br>
	$lang_los[los_message]<br><br>
	<b>$lang_los[new_users]:</b><br><br>
	<form action=\"index.php?action=storenew\" method=\"post\">
	$lang_los[reg_message]
	<br><br>
	<table>
		<tr>
			<td><strong>$lang_los[login]:</strong></td>
			<td><input type=\"text\" name=\"username\" size=\"10\" value=\"$username\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[pass]:</strong></td>
			<td><input type=\"password\" name=\"password\" size=\"10\" value=\"$password\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[r_pass]:</strong></td>
			<td><input type=\"password\" name=\"password2\" size=\"10\" value=\"\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[f_name]:</strong></td>
			<td><input type=\"text\" name=\"name\" size=\"20\" value=\"$name\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[phone]:</strong></td>
			<td><input type=\"text\" name=\"phone\" size=\"15\" value=\"$phone\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[email]:</strong></td>
			<td><input type=\"text\" name=\"email\" size=\"25\" value=\"$email\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><strong>$lang_los[s_address]</strong></td>
		</tr>
		<tr>
			<td><strong>$lang_los[address1]:</strong></td>
			    <td><input type=\"text\" name=\"address_line1\" size=\"20\" value=\"$address_line1\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[address2]:</strong></td>
			<td><input type=\"text\" name=\"address_line2\" size=\"20\" value=\"$address_line2\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[city]:</strong></td>
			<td><input type=\"text\" name=\"city\" size=\"10\" value=\"$city\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[sp]:</strong></td>
			<td><select name=\"state\" class=\"input_box\"><option VALUE=\"\">$state_list</select></td>
		</tr>
		<tr>
			<td><strong>$lang_los[zp]:</strong></td>
			<td><input type=\"text\" name=\"zip\" size=\"10\" value=\"$zip\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[country]:</strong></td>
			<td><select name=\"country\" class=\"input_box\"><option VALUE=\"\">$countries_list</select></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><strong>$lang_los[b_address]</strong></td>
		</tr>
		<tr>
			<td><strong>$lang_los[address1]:</strong></td>
			<td><input type=\"text\" name=\"baddress_line1\" size=\"20\" value=\"$baddress_line1\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[address2]:</strong></td>
			<td><input type=\"text\" name=\"baddress_line2\" size=\"20\" value=\"$baddress_line2\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[city]:</strong></td>
			<td><input type=\"text\" name=\"bcity\" size=\"10\" value=\"$bcity\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[sp]:</strong></td>
			<td><select name=\"bstate\" class=\"input_box\"><option VALUE=\"\">$state_list</select></td>
		</tr>
		<tr>
			<td><strong>$lang_los[zp]:</strong></td>
			<td><input type=\"text\" name=\"bzip\" size=\"10\" value=\"$bzip\" class=\"input_box\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[country]:</strong></td>
			<td><select name=\"bcountry\" class=\"input_box\"><option VALUE=\"\">$countries_list</select></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type=\"submit\" name=\"Submit\" value=\"$lang_los[cont]\" class=\"submit_button\">&nbsp;&nbsp;<input type=\"reset\" value=\"$lang_los[reset]\" class=\"submit_button\"></td>
		</tr>
	</table></div><br></form>";
	return $out;
}

// ###################### displaynew #######################
function displaynew () {
    global $contactemail, $title, $baddress_line1, $baddress_line2, $bcity, $bstate, $bzip, $bcountry, $state_list, $countries_list, $lang_los,
	$username, $password, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, $error, $mode, $lang_no_acc;
	$out .= "<div align=\"left\">";
	if ($mode == 1) {
	$out .= "<form action=\"index.php?action=storenewuser\" method=\"post\">$lang_no_acc[step1]<br><br>";
	} else {
	$out .= "<form action=\"index.php?action=storenew\" method=\"post\">$lang_los[reg_message]<br><br>";
	}
	$out .= "<table>";
	if ($mode != 1) {
		$out .= "
		<tr>
			<td><strong>$lang_los[login]:</strong></td>
			<td><input type=\"text\" name=\"username\" size=\"10\" value=\"$username\" class=\"input_box\">";
			if ($error == "taken") { $out .= "&nbsp;<font color=\"red\">$lang_los[u_exists]</font>"; }
			if ($error == "required" && !$username) { $out .= "&nbsp;<font color=\"red\">$lang_los[u_exists]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[pass]:</strong></td>
			<td><input type=\"password\" name=\"password\" size=\"10\" value=\"$password\" class=\"input_box\">";
			if ($error == "required" && !$password) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			if ($error == "match") { $out .= "&nbsp;<font color=\"red\">$lang_los[no_match]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[r_pass]:</strong></td>
			<td><input type=\"password\" name=\"password2\" size=\"10\" value=\"\" class=\"input_box\" value=\"$password2\">
			</td>
		</tr>";
	}
	    $out .= "
		<tr>
			<td><strong>$lang_los[f_name]:</strong></td>
			<td><input type=\"text\" name=\"name\" size=\"20\" value=\"$name\" class=\"input_box\">";
			if ($error == "required" && !$name) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			if ($error == "name") { $out .= "&nbsp;<font color=\"red\">Invalid Name</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[phone]:</strong></td>
			<td><input type=\"text\" name=\"phone\" size=\"15\" value=\"$phone\" class=\"input_box\">";
			if ($error == "required" && !$phone) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[email]:</strong></td>
			<td><input type=\"text\" name=\"email\" size=\"25\" value=\"$email\" class=\"input_box\">";
			if ($error == "required" && !$email) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><strong>$lang_los[s_address]</strong></td>
		</tr>
		<tr>
			<td><strong>$lang_los[address1]:</strong></td>
			<td><input type=\"text\" name=\"address_line1\" size=\"20\" value=\"$address_line1\" class=\"input_box\">";
			if ($error == "required" && !$address_line1) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[address2]:</strong></td>
			<td><input type=\"text\" name=\"address_line2\" size=\"20\" value=\"$address_line2\" class=\"input_box\">
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[city]:</strong></td>
			<td><input type=\"text\" name=\"city\" size=\"10\" value=\"$city\" class=\"input_box\">";
			if ($error == "required" && !$city) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[sp]:</strong></td>
			<td><select NAME=\"state\" class=\"input_box\"><option VALUE=\"$state\">".$state.$state_list."</select>";
			if ($error == "required" && !$state) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
	        $out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[zp]:</strong></td>
			<td><input type=\"text\" name=\"zip\" size=\"10\" value=\"$zip\" class=\"input_box\">";
			if ($error == "required" && !$zip) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[country]:</strong></td>
			<td><select NAME=\"country\" class=\"input_box\"><option VALUE=\"$country\">".$country.$countries_list."</select>";
			if ($error == "required" && !$country) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><strong>$lang_los[b_address]</strong></td>
		</tr>
		<tr>
			<td><strong>$lang_los[address1]:</strong></td>
			<td><input type=\"text\" name=\"baddress_line1\" size=\"20\" value=\"$baddress_line1\" class=\"input_box\">
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[address2]:</strong></td>
			<td><input type=\"text\" name=\"baddress_line2\" size=\"20\" value=\"$baddress_line2\" class=\"input_box\">
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[city]:</strong></td>
			<td><input type=\"text\" name=\"bcity\" size=\"10\" value=\"$bcity\" class=\"input_box\">";
			if ($error == "required" && !$bcity && $baddress_line1) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[sp]:</strong></td>
			<td><select NAME=\"bstate\" class=\"input_box\"><option VALUE=\"$bstate\">".$bstate.$state_list."</select>";
			if ($error == "required" && !$bstate  && $baddress_line1) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
	        $out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[zp]:</strong></td>
			<td><input type=\"text\" name=\"bzip\" size=\"10\" value=\"$bzip\" class=\"input_box\">";
			if ($error == "required" && !$bzip && $baddress_line1) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[country]:</strong></td>
			<td><select NAME=\"bcountry\" class=\"input_box\"><option VALUE=\"$bcountry\">".$bcountry.$countries_list."</select>";
			if ($error == "required" && !$bcountry && $baddress_line1) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type=\"submit\" name=\"Submit\" value=\"$lang_los[cont]\" class=\"submit_button\">&nbsp;&nbsp;<input type=\"reset\" value=\"$lang_los[reset]\" class=\"submit_button\"></td>
		</tr>
	</table></div><br>
	<div align=\"left\">
	$lang_los[b_message]</form></div><br>";
    return $out;
}

// ###################### displayaccount #######################
function displayaccount () {
    global $DB_site, $dbprefix, $lang_account, $lang_los;
	$userinfo = getuser ();
	$out .= "
	<div align=\"left\">$lang_account[message]<br><br>
	<strong>$lang_account[pinfo]:</strong> [ <a href=\"index.php?action=editaccount\">Edit</a> ]
	<blockquote>
	<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
		<tr>
			<td><strong>$lang_los[login]:</strong></td>
			<td>$userinfo[username]</td>
		</tr>
		<tr>
			<td><strong>$lang_los[pass]:</strong></td>
			<td>";
			$temp2 = 0; $temp = strlen($userinfo[password]); 
			while ($temp2 != $temp) { 
				$out .= "*"; $temp2++; 
			}
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[f_name]:</strong></td>
			<td>$userinfo[name]</td>
		</tr>
		<tr>
			<td><strong>$lang_los[phone]:</strong></td>
			<td>$userinfo[phone]</td>
		</tr>
		<tr>
			<td><strong>$lang_los[email]:</strong></td>
			<td>$userinfo[email]</td>
		</tr>
		<tr>
			<td valign=\"top\"><strong>$lang_los[address]:</strong></td>
			<td>$userinfo[address_line1]<br>";
			    if ($userinfo[address_line2] != "") { echo $userinfo[address_line2]; $out .= "<br>"; }
				$out .= "
				$userinfo[city], $userinfo[state] $userinfo[zip]
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[country]:</strong></td>
			<td>$userinfo[country]</td>
		</tr>
	</table>
	</blockquote>
	<strong>$lang_account[corders]:</strong><br><br>
	<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\"><tr><td>
	<table width=\"100%\">
	<tr bgcolor=\"Silver\">
		<td><strong>$lang_account[order]:</strong></td>
		<td><strong>$lang_account[date]:</strong></td>
		<td><strong>$lang_account[pstatus]:</strong></td>
		<td><strong>$lang_account[status]:</strong></td>
	</tr>
	<script language=\"JavaScript\">
	<!--
	var toopen= null;
	function tracking(mypage) {
		var winl = (screen.width-400)/2;
		var wint = (screen.height-300)/2;
		var settings = 'height=300,width=400,top='+wint+',left='+winl+',scrollbars=no,resizable=no';
		toopen=window.open(mypage,'tracking',settings);
		if (parseInt(navigator.appVersion) >= 4) {
			toopen.window.focus();
		}
	}   
	//-->
    </script>";
	$toto = 0; $color = 1;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."transaction where userid='$userinfo[userid]' ORDER by orderid desc");
	while ($row=$DB_site->fetch_array($temps)) { $toto++;
	if ($color == 1) { $out .= "<tr bgcolor=\"#FFFFFF\">"; } else { $out .= "<tr bgcolor=\"#E6E6E6\">"; }
	$out .= "
		<td>$row[orderid]</td>
		<td>$row[tdate]</td>
		<td>$row[ccstatus]</td>
		<td>";
		if ($row[status] == "Shipped") { $out .= "<a href=\"javascript:tracking('index.php?action=tracking&id=$row[orderid]')\">$row[status]</a>"; } else { $out .= $row[status]; } 
		$out .= "
		</td>
	</tr>";
    if ($color == 1) { $color = 0; } else { $color = 1; }
	}
	if ($toto == 0) {
		$out .= "<tr><td colspan=\"4\" align=\"center\">$lang_account[no_orders]</td></tr>";
	}
	$out .= "</table></td></tr></table></div><br><br>";
	return $out;
}

// ###################### showtracking #######################
function showtracking () {
    global $DB_site, $id, $css, $dbprefix, $USERIN;
	$temp=$DB_site->query("SELECT userid FROM ".$dbprefix."transaction where orderid='$id'");
    $row=$DB_site->fetch_object($temp);
	$userid = $row->userid;
	
	$temp=$DB_site->query("SELECT username FROM ".$dbprefix."user where userid='$userid'");
    $row=$DB_site->fetch_object($temp);
	
	if ($row->username==$USERIN) {
		$temp=$DB_site->query("SELECT * FROM ".$dbprefix."tracking where tranid='$id'");
	    $row=$DB_site->fetch_object($temp);
	} 
	eval("\$css = \"".loadtemplate("style_sheet")."\";");
	print("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n<HTML>\n<HEAD>".stripslashes($css)."\n</HEAD\n<BODY>\n");
	?>
	<strong>Shipping Information: (Order #<?PHP echo $id ?>)</strong><br><br>
    <blockquote>
	<table width="100%" border="0" cellspacing="0" cellpadding="4">
		<tr>
			<td><strong>Tracking Number:</strong></td>
			<td><?PHP if (!isset($row->number)) { echo "N/A"; } else { echo $row->number; } ?></td>
		</tr>
		<tr>
			<td><strong>Carrier:</strong></td>
			<td><?PHP if (!isset($row->carrier)) { echo "N/A"; } else { echo $row->carrier; } ?></td>
		</tr>
		<tr>
			<td><strong>Ship Date:</strong></td>
			<td><?PHP if (!isset($row->date)) { echo "N/A"; } else { echo $row->date; } ?></td>
		</tr>
	</table>
	</blockquote>
	<?
	print("\n</BODY>\n</HTML>");
	exit;
}

// ###################### editaccount #######################
function editaccount () {
    global $baddress_line1, $baddress_line2, $bcity, $bstate, $bzip, $bcountry, $state_list, $countries_list, $lang_los, $password2,
	$username, $password, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, $error;
	if ($username == "" && $password  == "" && $name  == "" && $city  == "" && $state  == "" && $zip  == "" && $email == "") {
	   $userinfo = getuser ();
	   $username = $userinfo[username];
	   $password = $userinfo[password];
	   $password2 = $userinfo[password];
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
	$out .= "
	<div align=\"left\"><form action=\"index.php?action=update\" method=\"post\">
	<table>
		<tr>
			<td><strong>$lang_los[login]:</strong></td>
			<td>$username<input type=\"hidden\" name=\"username\" value=\"$username\"></td>
		</tr>
		<tr>
			<td><strong>$lang_los[pass]:</strong></td>
			<td><input type=\"password\" name=\"password\" size=\10\" value=\"$password\" class=\"input_box\">";
			if ($error == "required" && !$password) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			if ($error == "match") { $out .= "&nbsp;<font color=\"red\">$lang_los[no_match]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[r_pass]:</strong></td>
			<td><input type=\"password\" name=\"password2\" size=\"10\" class=\"input_box\" value=\"$password2\">
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[f_name]:</strong></td>
			<td><input type=\"text\" name=\"name\" size=\"20\" value=\"$name\" class=\"input_box\">";
			if ($error == "required" && !$name) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			if ($error == "name") { $out .= "&nbsp;<font color=\"red\">Invalid Name</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[phone]:</strong></td>
			<td><input type=\"text\" name=\"phone\" size=\"15\" value=\"$phone\" class=\"input_box\">";
			if ($error == "required" && !$phone) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[email]:</strong></td>
			<td><input type=\"text\" name=\"email\" size=\"25\" value=\"$email\" class=\"input_box\">";
			if ($error == "required" && !$email) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><strong>$lang_los[s_address]</strong></td>
		</tr>
		<tr>
			<td><strong>$lang_los[address1]:</strong></td>
			<td><input type=\"text\" name=\"address_line1\" size=\"20\" value=\"$address_line1\" class=\"input_box\">";
			if ($error == "required" && !$address_line1) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[address2]:</strong></td>
			<td><input type=\"text\" name=\"address_line2\" size=\"20\" value=\"$address_line2\" class=\"input_box\">
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[city]:</strong></td>
			<td><input type=\"text\" name=\"city\" size=\"10\" value=\"$city\" class=\"input_box\">";
			if ($error == "required" && !$city) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[sp]:</strong></td>
			<td><select NAME=\"state\" class=\"input_box\"><option VALUE=\"$state\">".$state.$state_list."</select>";
			if ($error == "required" && !$state) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
	        $out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[zp]:</strong></td>
			<td><input type=\"text\" name=\"zip\" size=\"10\" value=\"$zip\" class=\"input_box\">";
			if ($error == "required" && !$zip) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[country]:</strong></td>
			<td><select NAME=\"country\" class=\"input_box\"><option VALUE=\"$country\">".$country.$countries_list."</select>";
			if ($error == "required" && !$country) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><strong>$lang_los[b_address]</strong></td>
		</tr>
		<tr>
			<td><strong>$lang_los[address1]:</strong></td>
			<td><input type=\"text\" name=\"baddress_line1\" size=\"20\" value=\"$baddress_line1\" class=\"input_box\">
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[address2]:</strong></td>
			<td><input type=\"text\" name=\"baddress_line2\" size=\"20\" value=\"$baddress_line2\" class=\"input_box\">
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[city]:</strong></td>
			<td><input type=\"text\" name=\"bcity\" size=\"10\" value=\"$bcity\" class=\"input_box\">";
			if ($error == "required" && !$bcity && $baddress_line1) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[sp]:</strong></td>
			<td><select NAME=\"bstate\" class=\"input_box\"><option VALUE=\"$bstate\">".$bstate.$state_list."</select>";
			if ($error == "required" && !$bstate  && $baddress_line1) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
	        $out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[zp]:</strong></td>
			<td><input type=\"text\" name=\"bzip\" size=\"10\" value=\"$bzip\" class=\"input_box\">";
			if ($error == "required" && !$bzip && $baddress_line1) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td><strong>$lang_los[country]:</strong></td>
			<td><select NAME=\"bcountry\" class=\"input_box\"><option VALUE=\"$bcountry\">".$bcountry.$countries_list."</select>";
			if ($error == "required" && !$bcountry && $baddress_line1) { $out .= "&nbsp;<font color=\"red\">$lang_los[required]</font>"; }
			$out .= "
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type=\"submit\" name=\"Submit\" value=\"$lang_los[cont]\" class=\"submit_button\">&nbsp;&nbsp;<input type=\"reset\" value=\"$lang_los[reset]\" class=\"submit_button\"></td>
		</tr>
	</table></div><br>
	<div align=\"left\">	
	$lang_los[b_message]</form></div><br>";
	return $out;
}

// ###################### storenew #######################
function storenew() {
	global $DB_site, $title, $contactemail, $shopurl, $dbprefix, $baddress_line1, $baddress_line2, $bcity, $bstate, 
	$bzip, $bcountry, $state_list, $countries_list, $lang_adduser, $lang_index, $username, $password, $password2, 
	$name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, $error;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$username'");
    while ($row=$DB_site->fetch_array($temps)) {
		$error = "taken";
		$out = displaynew();
		return $out;
	}
	if ($name == "Admin_Account" || $name == "Add_Admin") {
		$error = "name";
		$out = displaynew();
		return $out;
	}
	if ($password != $password2) {
		$error = "macth";
		$out = displaynew();
		return $out;
	}
	if (!$username || !$password || !$name || !$address_line1 || !$city || !$state || !$zip || !$country || !$phone || !$email || ($baddress_line1 && (!$bcity || !$bstate || !$bzip || !$bcountry))) {
		$error = "required";
		$out = displaynew();
		return $out;
	}
	if (!($DB_site->query("INSERT INTO ".$dbprefix."user (userid,username,password,name,address_line1,address_line2,city,state,zip,country,baddress_line1,baddress_line2,bcity,bstate,bzip,bcountry,phone,email,lastvisit) VALUES (NULL,'".strip_tags(addslashes($username))."','".strip_tags(addslashes($password))."','".strip_tags(addslashes($name))."','".strip_tags(addslashes($address_line1))."','".strip_tags(addslashes($address_line2))."','".strip_tags(addslashes($city))."','".strip_tags(addslashes($state))."','".strip_tags(addslashes($zip))."','".strip_tags(addslashes($country))."','".strip_tags(addslashes($baddress_line1))."','".strip_tags(addslashes($baddress_line2))."','".strip_tags(addslashes($bcity))."','".strip_tags(addslashes($bstate))."','".strip_tags(addslashes($bzip))."','".strip_tags(addslashes($bcountry))."','".strip_tags(addslashes($phone))."','".strip_tags(addslashes($email))."','Never')"))) {
		$out = $lang_adduser[error];
	} else {
	    $body = $lang_adduser[ty_email];
	    $body = preg_replace("/<!-- title -->/", $title, $body);
		$body = preg_replace("/<!-- username -->/", $username, $body);
		$body = preg_replace("/<!-- password -->/", $password, $body);
		$body = preg_replace("/<!-- shopurl -->/", $shopurl, $body);
		$body = preg_replace("/<!-- contactemail -->/", $contactemail, $body);
		$subject = $lang_adduser[ty_subject];
		$subject = preg_replace("/<!-- title -->/", $title, $subject);
		
		mail($email, $subject, $body, "From: \"".$title."\" <" . $contactemail . ">");
		
		$sc = $lang_adduser[screen_message];
		$sc = preg_replace("/<!-- title -->/", $title, $sc);
		$out = $sc;
	}
	return $out;
}

// ###################### randompass #######################
function randompass () {
	$all = explode("-", "a-A-b-B-c-C-d-D-e-E-f-F-g-G-h-H-i-I-j-J-k-K-l-L-m-M-n-N-o-O-p-q-Q-r-R-s-S-t-T-u-U-v-V-w-W-x-X-y-Y-z-Z"); 
       for($i=0;$i<8;$i++) { 
        srand((double)microtime()*1000000); 
        $randy = rand(0, 51); 
        $pass .= $all[$randy]; 
    }
    return $pass; 
}

// ###################### storetempnew #######################
function storetempnew() {
	global $DB_site, $title, $contactemail, $shopurl, $dbprefix, $baddress_line1, $baddress_line2, $bcity, $bstate, 
	$bzip, $bcountry, $state_list, $countries_list, $lang_adduser, $lang_index, $username, $password, $password2, 
	$name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email, $error, $lang_no_acc, 
	$lang_index, $mode, $USERIN, $PASSIN;
	if (!$name || !$address_line1 || !$city || !$state || !$zip || !$country || !$phone || !$email || ($baddress_line1 && (!$bcity || !$bstate || !$bzip || !$bcountry))) {
		$error = "required"; $mode = 1;
		$out = displaynew();
		return $out;
	}
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$email'");
    $rows=$DB_site->num_rows($temps);
	
	if ($rows > 0) {
		$username = $email.$rows;
	} else {
	    $username = $email;
	}
	$password = randompass();
	if (!($DB_site->query("INSERT INTO ".$dbprefix."user (userid,username,password,name,address_line1,address_line2,city,state,zip,country,baddress_line1,baddress_line2,bcity,bstate,bzip,bcountry,phone,email,lastvisit) VALUES (NULL,'".strip_tags(addslashes($username))."','".strip_tags(addslashes($password))."','".strip_tags(addslashes($name))."','".strip_tags(addslashes($address_line1))."','".strip_tags(addslashes($address_line2))."','".strip_tags(addslashes($city))."','".strip_tags(addslashes($state))."','".strip_tags(addslashes($zip))."','".strip_tags(addslashes($country))."','".strip_tags(addslashes($baddress_line1))."','".strip_tags(addslashes($baddress_line2))."','".strip_tags(addslashes($bcity))."','".strip_tags(addslashes($bstate))."','".strip_tags(addslashes($bzip))."','".strip_tags(addslashes($bcountry))."','".strip_tags(addslashes($phone))."','".strip_tags(addslashes($email))."','Never')"))) {
		$out = $lang_adduser[error];
		return $out;
	} else {
	    $body = $lang_adduser[ty_email];
	    $body = preg_replace("/<!-- title -->/", $title, $body);
		$body = preg_replace("/<!-- username -->/", $username, $body);
		$body = preg_replace("/<!-- password -->/", $password, $body);
		$body = preg_replace("/<!-- shopurl -->/", $shopurl, $body);
		$body = preg_replace("/<!-- contactemail -->/", $contactemail, $body);
		$subject = $lang_adduser[ty_subject];
		$subject = preg_replace("/<!-- title -->/", $title, $subject);
		
		mail($email, $subject, $body, "From: \"".$title."\" <" . $contactemail . ">");
	    
	    session_register("USERIN");
		session_register("PASSIN");
		$USERIN=$username;
		$PASSIN=$password;
		header("location:index.php?action=shipping");
	}
	exit;
}


// ###################### update #######################
function update() {
    global $DB_site, $dbprefix, $baddress_line1, $baddress_line2, $bcity, $bstate, $bzip, $bcountry, $lang_updateuser, $error,
	$username, $password, $password2, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email;
	if (!$username || !$password || !$name || !$address_line1 || !$city || !$state || !$zip || !$country || !$phone || !$email || ($baddress_line1 && (!$bcity || !$bstate || !$bzip || !$bcountry))) {
		$error = "required";
		$out = editaccount ();
		return $out;
	}
	if ($name == "Admin_Account" || $name == "Add_Admin") {
	    $error = "name";
		$out = editaccount ();
		return $out;
	}
	if ($password != $password2) {
	    $error = "match";
		$out = editaccount ();
		return $out;
	}
	if (!($DB_site->query("UPDATE ".$dbprefix."user set password='".strip_tags(addslashes($password))."', name='".strip_tags(addslashes($name))."', address_line1='".strip_tags(addslashes($address_line1))."', address_line2='".strip_tags(addslashes($address_line2))."', city='".strip_tags(addslashes($city))."', state='".strip_tags(addslashes($state))."', zip='".strip_tags(addslashes($zip))."', country='".strip_tags(addslashes($country))."', phone='".strip_tags(addslashes($phone))."', email='".strip_tags(addslashes($email))."', baddress_line1='".strip_tags(addslashes($baddress_line1))."', baddress_line2='".strip_tags(addslashes($baddress_line2))."', bcity='".strip_tags(addslashes($bcity))."', bstate='".strip_tags(addslashes($bstate))."', bzip='".strip_tags(addslashes($bzip))."', bcountry='".strip_tags(addslashes($bcountry))."' where username='$username'"))) {
		$out = $lang_updateuser[error];
	} else {
		$out = $lang_updateuser[succ];
	}
	return $out;
}

// ###################### randomspecial #######################
function randomspecial () {
	global $DB_site, $dbprefix;
	$count = 0;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."specials");
	while ($row=$DB_site->fetch_array($temps)) {
		$count++;
	}
	if ($count > 1) { $setrand = rand(1,$count); } 
	elseif ($count == 1) { $setrand = 1; } else { $setrand = "None"; }
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

// ###################### showfaqhelp #######################
function showfaqhelp () {
	global $contactemail, $shopphone, $lang_index, $lang_faq_ques, $lang_faq_ov, $lang_faq_ans;
	$out = "
	<table width=\"%100\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
	    <div align=\"left\"><a href=\"#faq\">$lang_index[faq_head]</a><br>
		<a href=\"#overview\">$lang_index[ov_head]</a><br><br>
		<a name=\"faq\"></a><strong>$lang_index[faq_head]</strong></div>
		<div align=\"left\">";
		for ($i=0;$i<count($lang_faq_ques);$i++) {
		$temp = $i + 1;
		$out .= "<a href=\"#$i\">$temp). $lang_faq_ques[$i]</a><br>";
		}
		$out .= "<br><br>";
		for ($i=0;$i<count($lang_faq_ques);$i++) { 
		      $temp = $i + 1;
			  $output = $lang_faq_ans[$i];
			  $output = preg_replace("/<!-- contactemail -->/", $contactemail, $output);
			  $output = preg_replace("/<!-- shopphone -->/", $shopphone, $output);
		      $out .= "<a name=\"#$i\"></a>$temp). $lang_faq_ques[$i]<br>$output<br><br>";
		}
		$out .= "
		<a name=\"overview\"></a><strong>$lang_index[ov_head]</strong>
		<br><br>$lang_faq_ov[pararaph1]
        <br><br>$lang_faq_ov[pararaph2]
        <br><br>$lang_faq_ov[pararaph3]
		<br><br>$lang_faq_ov[pararaph4]
        <br><br>
		</div>
	</table>";
    return $out;
}

// ###################### noaccountlogin #######################
function noaccountlogin () {
    global $lang_los, $lang_login;
	$out = "
	    $lang_los[los_message2]<br><br>
		<blockquote><table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
			<tr>
				<td>
				<form action=\"index.php?action=login\" method=\"post\"> 
				<input type=\"hidden\" name=\"paction\" value=\"account\">
				<font size=\"1\">$lang_login[uname]:</font><br>
				<input type=\"text\" name=\"login\" size=\"10\" class=\"input_box\"><br>
				<font size=\"1\">$lang_login[pass]:</font><br>
		        <input type=\"password\" name=\"password\" size=\"10\" class=\"input_box\"><br>
				</td>
			</tr>
			<tr>
				<td>
				<div align=\"center\"><input type=\"image\" name=\"Submit\" src=\"images/go.gif\" border=0></div>
				</td>
			</tr>
		</table></blockquote>
	";
	return $out;
}

// ###################### loginarea #######################
function loginarea ($act) {
    global $tablehead, $tableheadtext, $action, $total_items, $cs, $action, $id, $lang_login, $substart, $subid, $mustsignup, $REMOTE_ADDR;
	if ($mustsignup == "Yes") {
		$answer = islogged();
		if ($action == "storenew") { $paction = "account"; } elseif($action == "addtocart") { $paction = "viewcart"; } else { $paction = $action; }
		if ($answer != "Yes") {
			$out = "<tr>
						<td bgcolor=\"$tablehead\"><strong><font color=\"$tableheadtext\"><div align=\"center\">$lang_login[acc_login]</div></strong></font></td>
					</tr>
					<tr>
						<td align=\"center\">
						    <table border=\"0\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\">
								<tr>
									<td>
									<form action=\"index.php?action=login\" method=\"post\"> 
									<input type=\"hidden\" name=\"paction\" value=\"$paction\">
									<input type=\"hidden\" name=\"pid\" value=\"$id\">
									<input type=\"hidden\" name=\"psubstart\" value=\"$substart\">
									<input type=\"hidden\" name=\"psubid\" value=\"$subid\">
									<font size=\"1\">$lang_login[uname]:</font><br>
									<input type=\"text\" name=\"login\" size=\"10\" class=\"input_box\"><br>
									<font size=\"1\">$lang_login[pass]:</font><br>
							        <input type=\"password\" name=\"password\" size=\"10\" class=\"input_box\"><br>
									</td>
								</tr>
								<tr>
									<td>
									<div align=\"center\"><input type=\"image\" name=\"Submit\" src=\"images/go.gif\" border=0></div>
									</td>
								</tr>
							</table>
						    <div align=\"center\"><a href=\"index.php?action=login\" class=\"small\">$lang_login[fpass]</a><br><a href=\"index.php?action=newaccount\" class=\"small\">$lang_login[nuser]</a></div>	
						</td>
					</tr></form>
			";
		} else {
		    $user = getuser();
			$subtotal = subtotal();
			$out = "<tr>
						<td bgcolor=\"$tablehead\"><strong><font color=\"$tableheadtext\"><div align=\"center\">$lang_login[welcome]</div></strong></font></td>
					</tr>
					<tr>
						<td>
						    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\">
								<tr>
									<td>
									<div align=\"left\">
									<strong>$lang_login[acc]:</strong><br>&nbsp;&nbsp;&nbsp;$user[name]<br>
									<strong>$lang_login[cart]:</strong><br>&nbsp;&nbsp;&nbsp;(<font color=\"red\"><strong>$total_items</strong></font>) <i>Items</i><br>
								    <strong>$lang_login[sub]:</strong><br>&nbsp;&nbsp;&nbsp;$cs$subtotal<br>
									</div><br>
									<div align=\"center\"><a href=\"index.php?action=logout\" class=\"small\">$lang_login[out]</a></div></td>
								</tr>
							</table>
						</td>
					</tr>";
		}
	} else {
	    $answer = islogged();
		if ($answer == "Yes") {
			$user = getuser();
			$subtotal = subtotal();
			$out = "<tr>
						<td bgcolor=\"$tablehead\"><strong><font color=\"$tableheadtext\"><div align=\"center\">$lang_login[welcome]</div></strong></font></td>
					</tr>
					<tr>
						<td>
						    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\">
								<tr>
									<td>
									<div align=\"left\">
									<strong>$lang_login[acc]:</strong><br>&nbsp;&nbsp;&nbsp;$user[name]<br>
									<strong>$lang_login[cart]:</strong><br>&nbsp;&nbsp;&nbsp;(<font color=\"red\"><strong>$total_items</strong></font>) <i>Items</i><br>
								    <strong>$lang_login[sub]:</strong><br>&nbsp;&nbsp;&nbsp;$cs$subtotal<br>
									</div><br>
									<div align=\"center\"><a href=\"index.php?action=logout\" class=\"small\">$lang_login[out]</a></div></td>
								</tr>
							</table>
						</td>
					</tr>";
		} else {
		    $subtotal = subtotal();
			$out = "<tr>
						<td bgcolor=\"$tablehead\"><strong><font color=\"$tableheadtext\"><div align=\"center\">$lang_login[welcome_no]</div></strong></font></td>
					</tr>
					<tr>
						<td>
						    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\">
								<tr>
									<td>
									<div align=\"left\">
									<strong>$lang_login[acc]:</strong><br>&nbsp;&nbsp;&nbsp;$REMOTE_ADDR<br>
									<strong>$lang_login[cart]:</strong><br>&nbsp;&nbsp;&nbsp;(<font color=\"red\"><strong>$total_items</strong></font>) <i>Items</i><br>
								    <strong>$lang_login[sub]:</strong><br>&nbsp;&nbsp;&nbsp;$cs$subtotal<br>
									</div><br>
									<div align=\"center\"></div></td>
								</tr>
							</table>
						</td>
					</tr>";
		}
	}
	return $out;
}

// ###################### showspecial #######################
function showspecial () {
   global $productpath, $thumbwidth, $thumbheight, $cs, $lang_footer;
   $random = randomspecial();
   if ($random == "None") { 
	   $out .= $lang_footer[nospecials]; 
   } else {
	   $iteminfo = iteminfo($random[itemid]);
	   $out .= "<div align=\"center\"><a href=\"index.php?action=item&id=$random[itemid]\">";
	   if ($iteminfo[thumb] != "") {
		   if ($thumbwidth != "" && $thumbheight != "") {
			   $out .= "<img src=\"$productpath$iteminfo[thumb]\" border=\"0\" alt=\"\" width=\"$thumbwidth\" height=\"$thumbheight\"><br>";
		   } else {
			   $out .= "<img src=\"$productpath$iteminfo[thumb]\" border=\"0\" alt=\"\"><br>";
		   }
	   }
	   $out .= "$iteminfo[title]</a><br><strong><s>$cs$iteminfo[price]</s>&nbsp;&nbsp;<font color=\"red\">$cs$random[sprice]</font></strong></div>";
   }
   return $out;
}

// ###################### search_categories ##################
function search_categories () {   
    global $DB_site, $dbprefix;
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."category");
	while ($row=$DB_site->fetch_array($temp)) {
	     $out .= ("<option value=\"$row[title]\">$row[title]</option>"); 
	}
	return $out;
}

// ###################### start page #######################
function start () {
   global $title, $shopimage, $useheader, $myheader, $lang_header, $quitearly, $viewcartimage, $viewaccountimage,
   $helpimage, $lang_icons, $tablehead, $tableheadtext, $tablebg, $tableborder, $show_categories, $main_content,
   $message, $centerborder, $centercolor, $centerheader, $centerfont, $centerbg, $lang_footer, $action, $showspecials,
   $centerwidth, $allwidth, $tablewidth, $toptitle;
   
   if ($useheader == "Yes") {
	   eval("\$header1 = \"".loadtemplate("header")."\";");
	   eval("\$css = \"".loadtemplate("style_sheet")."\";");
       $header1 = preg_replace("/<!-- title -->/", $title, $header1);
	   $header1 = preg_replace("/<!-- action -->/", ucwords($toptitle), $header1);
	   $header1 = preg_replace("/<!-- css -->/", $css, $header1);
	   $header1 = preg_replace("/<!-- shopimage -->/", $shopimage, $header1);
	   echo stripslashes($header1);
   } else {
      include("$myheader");
   }
   
   if ($quitearly == 1) { return; }
   
   $search_categories = search_categories();
   eval("\$firsttable = \"".loadtemplate("search_and_icons")."\";");
   echo stripslashes($firsttable);
   
   echo "<script Language=\"Javascript\">\nvar NS = (navigator.appName == \"Netscape\");\nvar VERSION = parseInt(navigator.appVersion);\nif (VERSION == NS) { document.write('<br>'); }\n</script>\n";
   
   eval("\$categories_table = \"".loadtemplate("categories_table")."\";");
   eval("\$center_table = \"".loadtemplate("center_table")."\";");
   $login_area = loginarea($action);
   eval("\$login_table = \"".loadtemplate("login_table")."\";");
   if ($showspecials == "Yes") {
       $show_special = showspecial ();
	   eval("\$specials_table = \"".loadtemplate("specials_table")."\";");
   } else {
	   $specials_table = "";
   }
   eval("\$main = \"".loadtemplate("main_area")."\";");
   echo stripslashes($main);
   
}

// ###################### showend page #######################
function showend () {
    global $homeurl, $hometitle, $shopurl, $title, $companyname, $usefooter, $myfooter, $allwidth, $centercolor, $ssversion;
	if ($usefooter == "Yes") {
	     eval("\$footer = \"".loadtemplate("footer")."\";");
	     $footer = preg_replace("/<!-- homeurl -->/", $homeurl, $footer);
		 $footer = preg_replace("/<!-- hometitle -->/", $hometitle, $footer);
		 $footer = preg_replace("/<!-- shopurl -->/", $shopurl, $footer);
		 $footer = preg_replace("/<!-- title -->/", $title, $footer);
		 echo $footer;
		 // DO NOT REMOVE. Failing to do so will result in loss of license.
		 print("<div align=\"center\" class=\"small\"><font color=\"Gray\">$title &copy;Copyright ".date(Y)." <a href=\"$homeurl\" class=\"small\">$companyname</a><br>Powered by \"SunShop\"<!--CyKuH [WTN]-->$ssversion</font></div></BODY></HTML>");
		 // DO NOT REMOVE.
    } else {
	    include("$myfooter");
    }
}
?>
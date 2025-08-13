<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.6
// Supplied by          : Stive [WTN], CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
// #### CHANGE THIS IF YOU PUT THIS ON A DIFFERENT SERVER #####
require("admin/config.php");
require("admin/db_mysql.php");

// ############# Transfer Cookie To Secure Server #############
if ($setnew == "yes" && ($payment1 == "Credit Card" || $payment1 == "po")) {
   setcookie("totalitems", $totalitems1);
   setcookie("optionsonly", $options_only1);
   setcookie("USER_", $USER);
   setcookie("PASS_", $PASS);
   setcookie("payment", $payment1);
   for ($i = 0; $i < $totalitems1; $i++) {
	   setcookie("itemtray[$i]", $itemtray1[$i]);
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
	$header1 = $row[header1];
    $footer = $row[footer];
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
function parsecart ($x) {
    global $totalitems, $itemtray, $itemd, $quantityd, $optionsd, $payopd, $totalitems1, $itemtray1, 
	$optionsonly, $options_only1, $options_array;
    if ($x == 1) {  
		for ($i = 0; $i < $totalitems; $i++) {
			$temparray = explode("-", $itemtray[$i]);
	        $itemd[$i] = $temparray[0];
			$options_array = explode("-", $optionsonly);
			$quantityd[$i] = $temparray[1];
			$optionsd[$i] = $temparray[2];
			$payopd[$i] = $temparray[3];
		}
	} else {
		for ($i = 0; $i < $totalitems1; $i++) {
			$temparray = explode("-", $itemtray1[$i]);
	        $itemd[$i] = $temparray[0];
			$options_array = explode("-", $options_only1);
			$quantityd[$i] = $temparray[1];
			$optionsd[$i] = $temparray[2];
			$payopd[$i] = $temparray[3];
		}
	}
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
		if ($iteminfo[sku] != "") { $iteminfo[num] = $iteminfo[sku]; } else { $iteminfo[num] = $iteminfo[itemid]; } 
	}
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."specials where itemid='$id'");
    while ($row=$DB_site->fetch_array($temp)) {
		$iteminfo[specialid] = $row[specialid]; $iteminfo[sdescription] = stripslashes($row[sdescription]); 
		$iteminfo[sprice] = $row[sprice];
		return $iteminfo;
	}
	return $iteminfo;
}

// ###################### getuser ##################
function getuser ($x) {
    global $DB_site, $USER_, $PASS_, $USER, $PASS, $dbprefix;
	if ($x == 1) {
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$USER_'");
		$PASSER = $PASS_;
    } else {
		$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$USER'");
		$PASSER = $PASS;
	}
	
	while ($row=$DB_site->fetch_array($temps)) {
		if ($PASSER == $row[password]) {
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

// ###################### header stuff #######################
function start ($action) {
   global $title, $css;
   print("
   <!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n<HTML>\n<HEAD>\n
   <title>$action</title>
   "); 	
   ?>
   <script language="JavaScript">
	<!--//
	
    function OpenWindowCVV2(sURL) { 
	   newwindow = window.open(sURL,"windowFORM","left=20,top=20,width=255,height=170") 
    }
	
	var Cards = new makeArray(8);
	Cards[0] = new CardType("MasterCard", "51,52,53,54,55", "16");
	var MasterCard = Cards[0];
	Cards[1] = new CardType("VisaCard", "4", "13,16");
	var VisaCard = Cards[1];
	Cards[2] = new CardType("AmExCard", "34,37", "15");
	var AmExCard = Cards[2];
	Cards[3] = new CardType("DinersClubCard", "30,36,38", "14");
	var DinersClubCard = Cards[3];
	Cards[4] = new CardType("DiscoverCard", "6011", "16");
	var DiscoverCard = Cards[4];
	Cards[5] = new CardType("enRouteCard", "2014,2149", "15");
	var enRouteCard = Cards[5];
	Cards[6] = new CardType("JCBCard", "3088,3096,3112,3158,3337,3528", "16");
	var JCBCard = Cards[6];
	var LuhnCheckSum = Cards[7] = new CardType();
	
	/*************************************************************************\
	CheckCardNumber(form)
	function called when users click the "check" button.
	\*************************************************************************/
	function CheckCardNumber(form) {
	var tmpyear;
	if (form.CardNumber.value.length == 0) {
	alert("Please enter a Card Number.");
	form.CardNumber.focus();
	return false;
	}
	if (form.ExpYear.value.length == 0) {
	alert("Please enter the Expiration Year.");
	form.ExpYear.focus();
	return false;
	}
	if (form.ExpYear.value > 96)
	tmpyear = "19" + form.ExpYear.value;
	else if (form.ExpYear.value < 21)
	tmpyear = "20" + form.ExpYear.value;
	else {
	alert("The Expiration Year is not valid.");
	return false;
	}
	tmpmonth = form.ExpMon.options[form.ExpMon.selectedIndex].value;
	// The following line doesn't work in IE3, you need to change it
	// to something like "(new CardType())...".
	// if (!CardType().isExpiryDate(tmpyear, tmpmonth)) {
	if (!(new CardType()).isExpiryDate(tmpyear, tmpmonth)) {
	alert("This card has already expired.");
	return false;
	}
	if (form.cvv2.value.length == 0) {
	alert("Please enter a CVV2 Number.");
	form.cvv2.focus();
	return false;
	}
	if (form.name_on_card.value.length == 0) {
	alert("Please enter the Name On Card.");
	form.name_on_card.focus();
	return false;
	}
	
	card = form.CardType.options[form.CardType.selectedIndex].value;
	var retval = eval(card + ".checkCardNumber(\"" + form.CardNumber.value +
	"\", " + tmpyear + ", " + tmpmonth + ");");
	cardname = "";
	if (retval)
	   return true;
	else {
	// The cardnumber has the valid luhn checksum, but we want to know which
	// cardtype it belongs to.
	for (var n = 0; n < Cards.size; n++) {
	if (Cards[n].checkCardNumber(form.CardNumber.value, tmpyear, tmpmonth)) {
	cardname = Cards[n].getCardType();
	break;
	   }
	}
	if (cardname.length > 0) {
	alert("This looks like a " + cardname + " number, not a " + card + " number.");
	return false;
	}
	else {
	alert("This card number is not valid.");
	return false;
	      }
	   }
	}
	/*************************************************************************\
	Object CardType([String cardtype, String rules, String len, int year, 
	                                        int month])
	cardtype    : type of card, eg: MasterCard, Visa, etc.
	rules       : rules of the cardnumber, eg: "4", "6011", "34,37".
	len         : valid length of cardnumber, eg: "16,19", "13,16".
	year        : year of expiry date.
	month       : month of expiry date.
	eg:
	var VisaCard = new CardType("Visa", "4", "16");
	var AmExCard = new CardType("AmEx", "34,37", "15");
	\*************************************************************************/
	function CardType() {
	var n;
	var argv = CardType.arguments;
	var argc = CardType.arguments.length;
	
	this.objname = "object CardType";
	
	var tmpcardtype = (argc > 0) ? argv[0] : "CardObject";
	var tmprules = (argc > 1) ? argv[1] : "0,1,2,3,4,5,6,7,8,9";
	var tmplen = (argc > 2) ? argv[2] : "13,14,15,16,19";
	
	this.setCardNumber = setCardNumber;  // set CardNumber method.
	this.setCardType = setCardType;  // setCardType method.
	this.setLen = setLen;  // setLen method.
	this.setRules = setRules;  // setRules method.
	this.setExpiryDate = setExpiryDate;  // setExpiryDate method.
	
	this.setCardType(tmpcardtype);
	this.setLen(tmplen);
	this.setRules(tmprules);
	if (argc > 4)
	this.setExpiryDate(argv[3], argv[4]);
	
	this.checkCardNumber = checkCardNumber;  // checkCardNumber method.
	this.getExpiryDate = getExpiryDate;  // getExpiryDate method.
	this.getCardType = getCardType;  // getCardType method.
	this.isCardNumber = isCardNumber;  // isCardNumber method.
	this.isExpiryDate = isExpiryDate;  // isExpiryDate method.
	this.luhnCheck = luhnCheck;// luhnCheck method.
	return this;
	}
	
	/*************************************************************************\
	boolean checkCardNumber([String cardnumber, int year, int month])
	return true if cardnumber pass the luhncheck and the expiry date is
	valid, else return false.
	\*************************************************************************/
	function checkCardNumber() {
	var argv = checkCardNumber.arguments;
	var argc = checkCardNumber.arguments.length;
	var cardnumber = (argc > 0) ? argv[0] : this.cardnumber;
	var year = (argc > 1) ? argv[1] : this.year;
	var month = (argc > 2) ? argv[2] : this.month;
	
	this.setCardNumber(cardnumber);
	this.setExpiryDate(year, month);
	
	if (!this.isCardNumber())
	return false;
	if (!this.isExpiryDate())
	return false;
	
	return true;
	}
	/*************************************************************************\
	String getCardType()
	return the cardtype.
	\*************************************************************************/
	function getCardType() {
	return this.cardtype;
	}
	/*************************************************************************\
	String getExpiryDate()
	return the expiry date.
	\*************************************************************************/
	function getExpiryDate() {
	return this.month + "/" + this.year;
	}
	/*************************************************************************\
	boolean isCardNumber([String cardnumber])
	return true if cardnumber pass the luhncheck and the rules, else return
	false.
	\*************************************************************************/
	function isCardNumber() {
	var argv = isCardNumber.arguments;
	var argc = isCardNumber.arguments.length;
	var cardnumber = (argc > 0) ? argv[0] : this.cardnumber;
	if (!this.luhnCheck())
	return false;
	
	for (var n = 0; n < this.len.size; n++)
	if (cardnumber.toString().length == this.len[n]) {
	for (var m = 0; m < this.rules.size; m++) {
	var headdigit = cardnumber.substring(0, this.rules[m].toString().length);
	if (headdigit == this.rules[m])
	return true;
	}
	return false;
	}
	return false;
	}
	
	/*************************************************************************\
	boolean isExpiryDate([int year, int month])
	return true if the date is a valid expiry date,
	else return false.
	\*************************************************************************/
	function isExpiryDate() {
	var argv = isExpiryDate.arguments;
	var argc = isExpiryDate.arguments.length;
	
	year = argc > 0 ? argv[0] : this.year;
	month = argc > 1 ? argv[1] : this.month;
	
	if (!isNum(year+""))
	return false;
	if (!isNum(month+""))
	return false;
	today = new Date();
	expiry = new Date(year, month);
	if (today.getTime() > expiry.getTime())
	return false;
	else
	return true;
	}
	
	/*************************************************************************\
	boolean isNum(String argvalue)
	return true if argvalue contains only numeric characters,
	else return false.
	\*************************************************************************/
	function isNum(argvalue) {
	argvalue = argvalue.toString();
	
	if (argvalue.length == 0)
	return false;
	
	for (var n = 0; n < argvalue.length; n++)
	if (argvalue.substring(n, n+1) < "0" || argvalue.substring(n, n+1) > "9")
	return false;
	
	return true;
	}
	
	/*************************************************************************\
	boolean luhnCheck([String CardNumber])
	return true if CardNumber pass the luhn check else return false.
	Reference: http://www.ling.nwu.edu/~sburke/pub/luhn_lib.pl
	\*************************************************************************/
	function luhnCheck() {
	var argv = luhnCheck.arguments;
	var argc = luhnCheck.arguments.length;
	
	var CardNumber = argc > 0 ? argv[0] : this.cardnumber;
	
	if (! isNum(CardNumber)) {
	return false;
	  }
	
	var no_digit = CardNumber.length;
	var oddoeven = no_digit & 1;
	var sum = 0;
	
	for (var count = 0; count < no_digit; count++) {
	var digit = parseInt(CardNumber.charAt(count));
	if (!((count & 1) ^ oddoeven)) {
	digit *= 2;
	if (digit > 9)
	digit -= 9;
	}
	sum += digit;
	}
	if (sum % 10 == 0)
	return true;
	else
	return false;
	}
	
	/*************************************************************************\
	ArrayObject makeArray(int size)
	return the array object in the size specified.
	\*************************************************************************/
	function makeArray(size) {
	this.size = size;
	return this;
	}
	
	/*************************************************************************\
	CardType setCardNumber(cardnumber)
	return the CardType object.
	\*************************************************************************/
	function setCardNumber(cardnumber) {
	this.cardnumber = cardnumber;
	return this;
	}
	
	/*************************************************************************\
	CardType setCardType(cardtype)
	return the CardType object.
	\*************************************************************************/
	function setCardType(cardtype) {
	this.cardtype = cardtype;
	return this;
	}
	
	/*************************************************************************\
	CardType setExpiryDate(year, month)
	return the CardType object.
	\*************************************************************************/
	function setExpiryDate(year, month) {
	this.year = year;
	this.month = month;
	return this;
	}
	
	/*************************************************************************\
	CardType setLen(len)
	return the CardType object.
	\*************************************************************************/
	function setLen(len) {
	// Create the len array.
	if (len.length == 0 || len == null)
	len = "13,14,15,16,19";
	
	var tmplen = len;
	n = 1;
	while (tmplen.indexOf(",") != -1) {
	tmplen = tmplen.substring(tmplen.indexOf(",") + 1, tmplen.length);
	n++;
	}
	this.len = new makeArray(n);
	n = 0;
	while (len.indexOf(",") != -1) {
	var tmpstr = len.substring(0, len.indexOf(","));
	this.len[n] = tmpstr;
	len = len.substring(len.indexOf(",") + 1, len.length);
	n++;
	}
	this.len[n] = len;
	return this;
	}
	
	/*************************************************************************\
	CardType setRules()
	return the CardType object.
	\*************************************************************************/
	function setRules(rules) {
	// Create the rules array.
	if (rules.length == 0 || rules == null)
	rules = "0,1,2,3,4,5,6,7,8,9";
	  
	var tmprules = rules;
	n = 1;
	while (tmprules.indexOf(",") != -1) {
	tmprules = tmprules.substring(tmprules.indexOf(",") + 1, tmprules.length);
	n++;
	}
	this.rules = new makeArray(n);
	n = 0;
	while (rules.indexOf(",") != -1) {
	var tmpstr = rules.substring(0, rules.indexOf(","));
	this.rules[n] = tmpstr;
	rules = rules.substring(rules.indexOf(",") + 1, rules.length);
	n++;
	}
	this.rules[n] = rules;
	return this;
	}
	//  End -->
	</script>
   <?PHP
   print("
   $css
   </HEAD>
   <BODY bgcolor=white text=black link=blue vlink=blue alink=blue>
   ");       
}

// ###################### footer stuff #######################
function showend ($action) {
   global $title, $homeurl, $hometitle, $shopurl;
   print("
   <br><div align=\"center\">
   <a href=\"$homeurl\">$hometitle</a>&nbsp;|&nbsp;<a href=\"$shopurl\">$title</a>&nbsp;|&nbsp;<a href=\"".$shopurl."index.php?action=viewcart\">View Cart</a>&nbsp;|&nbsp;<a href=\"".$shopurl."index.php?action=account\">Your Account</a>&nbsp;|&nbsp;<a href=\"".$shopurl."index.php?action=help\">Help</a></font></div><br>
   <div align=\"center\" class=\"small\">\"Sunshop\" &copy;Copyright 2001-2002 <!--CyKuH-->Turnkey Solutions</div>
   </BODY></HTML>");
}

// ###################### finalcheckout ##################
function finalcheckout ($shipping, $total, $tax, $shipprice, $shipselect, $coupon) {
	global $itemd, $DB_site, $dbprefix, $quantityd, $optionsd, $payopd, $itemtray1, $totalitems1, $savings, $cs, $comments,
	$shopurl, $title, $payment, $visa, $mastercard, $discover, $amex, $options_only1, $options_array, $payment1;
	start("$title - Final Checkout");
	print("<form action=\"checkout.php\" method=\"post\">
		<input type=\"hidden\" name=\"total\" value=\"$total\">
		<input type=\"hidden\" name=\"shipping\" value=\"$shipping\">
		<input type=\"hidden\" name=\"shipping_method\" value=\"$shipselect\">
		<input type=\"hidden\" name=\"tax\" value=\"$tax\">
		<input type=\"hidden\" name=\"coupon\" value=\"$coupon\">
		<input type=\"hidden\" name=\"savings\" value=\"$savings\">
		<input type=\"hidden\" name=\"comments\" value=\"$comments\">
	");

	?>
	<div align="center">
        <b><?PHP echo $title ?>- Final Checkout</b><br><br>
		<table width="500" border="1" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>
			    <input type="hidden" name="action" value="validate">
				<table width="500" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>
						<table>
							<tr>
								<td width="50"><div align="center"><b>Quantity</b></div></td>
								<td width="225"><div align="center"><b>Product</b></div></td>
								<td width="150"><div align="center"><b>Options</b></div></td>
								<td width="75"><div align="center"><b>Price</b></div></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						<table border="0">
						<?PHP
						parsecart(0);
						for ($i = 0; $i < $totalitems1; $i++) {
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
									<td width=\"50\"><div align=\"center\">$quantityd[$i]</div></td>
									<td width=\"225\"><div align=\"center\">$iteminfo[title]</div></td>
									<td width=\"150\"><div align=\"center\">$doptions</div></td>
									<td width=\"75\"><div align=\"center\">$dprice</div></td>
						 	       </tr>
							");
						}
						?>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						    <table>
								<tr>
									<td width="425">&nbsp;</td>
									<td width="75"><hr size=1></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><table>
								<tr>
									<td width="425"><div align="right"><b>Tax:</b></div></td>
									<td width="75" colspan="2"><div align="center"><?PHP echo $cs.$tax ?></div></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><table>
								<tr>
									<td width="425"><div align="right"><b>Shipping:</b></div></td>
									<td width="75" colspan="2"><div align="center"><?PHP echo $cs.$shipping ?></div></td>
								</tr>
							</table>
						</td>
					</tr>
					<?PHP if ($coupon != "") { ?>
					<tr>
						<td><table>
								<tr>
									<td width="425"><div align="right"><b>Coupon Discount:</b></div></td>
									<td width="75" colspan="2"><div align="center"><font color="Red"><?PHP echo $cs.$savings ?></font></div></td>
								</tr>
							</table>
						</td>
					</tr>					
					<?PHP } ?>
					<tr>
						<td><table>
								<tr>
									<td width="425"><div align="right"><b>Final Total:</b></div></td>
									<td width="75" colspan="2"><div align="center"><?PHP echo $cs.$total ?><input type="hidden" name="total_final" value="<?PHP echo $total ?>"></div></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table><br>
	<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
	<?PHP if ($payment1 != "po") { ?>
	
	    <tr>
			<td colspan="2" align="left"><b>Credit Card Information:</b></td>
		</tr>
		<tr>
			<td>Name On Card:</td>
			<td><input type="text" name="name_on_card" size="30" class="input_box"></td>
		</tr>
		<tr>
			<td valign="top">Card Number:</td>
			<td><input type="text" name="CardNumber" size="17" class="input_box"></td>
		</tr>
		<tr>
			<td width="115">Expiration Date:</td>
			<td><select name="ExpMon" class="input_box">
			    <?PHP if ($ExpMon != "") { ?> <option value="<?PHP echo $ExpMon ?>"><?PHP echo $ExpMon; }  
				for ($i = 1; $i < 13; $i++) {
					if ($i < 10) { $temp = "0".$i; } else { $temp = $i; }
					print("<option value=\"$temp\">$temp");
				}
				?>
				</select> / <select name="ExpYear" class="input_box">
				<?PHP if ($ExpYear != "") { ?> <option value="<?PHP echo $ExpYear ?>"><?PHP echo $ExpYear; }  
				$year = date("Y");
				$to = $year + 10;
				for ($i = $year; $i < $to; $i++) {
					$temp = $i;
					print("<option value=\"$temp\">$temp");
				}		
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Card Type:</td>
			<td><select name="CardType" class="input_box">
			<?PHP
			if ($visa =="Yes") {
			?>
			<option value="VisaCard" selected>Visa
			<?PHP
			}
			if ($mastercard =="Yes") {
			?>
			<option value="MasterCard">Mastercard
			<?PHP
			}
			if ($amex =="Yes") {
			?>
			<option value="AmExCard">American Express
			<?PHP
			}
			if ($discover =="Yes") {
			?>
			<option value="DiscoverCard">Discover
			<?PHP
			}
			?>
			</select></td>
		</tr>
		<tr>
			<td valign="top">CVV2:</td>
			<td><input type="text" name="cvv2" size="4" class="input_box"> <a href="javascript:OpenWindowCVV2('<?PHP echo $shopurl ?>/images/cvv2.gif')">What is this?</a></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><br><input type="submit" name="Submit" value="Submit Transaction" OnClick="return CheckCardNumber(this.form)" class="submit_button">&nbsp;&nbsp;<input type="Reset" class="submit_button"></td>
		</tr>
	<?PHP } else { ?>
	    <tr>
			<td colspan="2" align="left"><b>Purchase Order Information:</b></td>
		</tr>
		<tr>
			<td>Company Name:</td>
			<td><input type="text" name="company" size="30" class="input_box"></td>
		</tr>
		<tr>
			<td>P.O. Number:</td>
			<td><input type="text" name="po_number" size="30" class="input_box"></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><br><input type="submit" name="Submit" value="Submit Transaction" class="submit_button">&nbsp;&nbsp;<input type="Reset" class="submit_button"></td>
		</tr>
	<?PHP } ?>
		
	</table>
	</div></form>
	<?PHP   
}

// ###################### storeinfo #################
function storeinfo($method) {
	global $DB_site, $dbprefix, $itemtray, $totalitems, $shopurl, $title, $contactemail, $payment, $payable, $itemd, $quantityd, $optionsd, $payopd, $po_number, 
	       $tax, $total, $shipping, $shipping_method, $name_on_card, $CardNumber, $ExpMon, $CardType, $cvv2, $itemtray1, $totalitems1, $companyname, 
		   $payment1, $shopfax, $visa, $mastercard, $discover, $amex, $payable, $shopstate, $USER_, $USER, $shopaddress, $shopcity, $ExpYear, $comments,
		   $shopzip, $payable, $options1, $paypal, $paypalemail, $shopimage, $shipselect, $PASS, $PASS_, $total_final, $savings, $coupon, $cs, $company;
	
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
	
	if ($method == "cc" || $method == "po") {
		parsecart(1);
		for ($i = 0; $i < $totalitems; $i++) {
		    $temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$itemd[$i]'");
	        while ($row=$DB_site->fetch_array($temps)) {
			    $toto = $row[quantity];
				$so = $row[sold];
			}
		    $quan = $toto - $quantityd[$i];
			$sold = $so + $quantityd[$i];
			if ($quan < 0) {
				$quan = 0;
			}
			$DB_site->query("UPDATE ".$dbprefix."items set sold='$sold', quantity='$quan' where itemid='$itemd[$i]'");
		}
		for ($i = 0; $i < $totalitems; $i++) {
		    if ($items == "") {
				$items = $itemd[$i];
				$quantity = $quantityd[$i];
				$options = $optionsd[$i];
		    } else {
				$items = $items."-".$itemd[$i];
			    $quantity = $quantity."-".$quantityd[$i];
				$options = $options."-".$optionsd[$i];
			}
		}
		$userinfo = getuser(1);
	} else {
		parsecart(0);
		for ($i = 0; $i < $totalitems1; $i++) {
		  $temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$itemd[$i]'");
	      while ($row=$DB_site->fetch_array($temps)) {
			  $toto = $row[quantity];
			  $so = $row[sold];
		  }
		  $quan = $toto - $quantityd[$i];
		  $sold = $so + $quantityd[$i];
		  if ($quan < 0) {
			$quan = 0;
		  }
		  $DB_site->query("UPDATE ".$dbprefix."items set sold='$sold', quantity='$quan' where itemid='$itemd[$i]'");
		}
		for ($i = 0; $i < $totalitems1; $i++) {
		    if ($items == "") {
				$items = $itemd[$i];
				$quantity = $quantityd[$i];
				$options = $optionsd[$i];
		    } else {
				$items = $items."-".$itemd[$i];
			    $quantity = $quantity."-".$quantityd[$i];
				$options = $options."-".$optionsd[$i];
			}
		}
		$userinfo = getuser(0);
	}
	$userid = $userinfo[userid];
	if ($method == "cc") {
		$DB_site->query("INSERT INTO ".$dbprefix."transaction VALUES (NULL,'$userid','".addslashes($items)."','".addslashes($quantity)."','".addslashes($tdate)."','".addslashes($shipping_method)."','".addslashes($shipping)."','".addslashes($total)."', 'Credit Card','".strip_tags(addslashes($name_on_card))."','".strip_tags(addslashes($CardNumber))."','".strip_tags(addslashes($ExpMon))." ".strip_tags(addslashes($ExpYear))."','".strip_tags(addslashes($CardType))."','".strip_tags(addslashes($cvv2))."','Pending Approval','Pending', '$options', '".strip_tags(addslashes($comments))."', '".strip_tags($coupon)." - $savings')");
	} elseif ($method == "po") {
		$DB_site->query("INSERT INTO ".$dbprefix."transaction VALUES (NULL,'$userid','".addslashes($items)."','".addslashes($quantity)."','".addslashes($tdate)."','".addslashes($shipping_method)."','".addslashes($shipping)."','".addslashes($total)."', 'Purchase Order','".strip_tags(addslashes($company))."','P.O. Number - ".strip_tags(addslashes($po_number))."','N/A','N/A','N/A','Pending Approval','Pending', '$options', '".strip_tags(addslashes($comments))."', '".strip_tags($coupon)." - $savings')");
	} else {
		$DB_site->query("INSERT INTO ".$dbprefix."transaction VALUES (NULL,'$userid','".addslashes($items)."','".addslashes($quantity)."','".addslashes($tdate)."','".addslashes($shipselect)."','".addslashes($shipping)."','".addslashes($total)."','$payment1','N/A','N/A','N/A','N/A','N/A','Awaiting Payment','Pending','$options','".strip_tags(addslashes($comments))."', '".strip_tags($coupon)." - $savings')");
	}
		
	$temp12=$DB_site->query("SELECT * FROM ".$dbprefix."transaction where userid='$userid' ORDER by orderid desc LIMIT 0,1");
	$row12=$DB_site->fetch_array($temp12);
	$tran = $row12[orderid];
		
    $message = "Thank you for shopping at ".$title.".\n\nYour order total is ".$cs.$total.".\nCustomer ID: ".$userid."\nTransaction #: ".$tran."\n\n";
	$message = $message."Item # | Item Name | Quantity | Options | Price\n";
	if ($method == "cc" || $method == "po") { 
		for ($i = 0; $i < $totalitems; $i++) {
		    $iteminfo = iteminfo($itemd[$i]);
			if ($iteminfo[sprice] != "") { $theprice = $iteminfo[sprice];  } else { $theprice = $iteminfo[price]; }
		    $message = $message.$iteminfo[num]." | ".$iteminfo[title]." | ".$quantityd[$i]." | ".$optionsd[$i]." | ".$cs.$theprice."\n";
		}
	} else {
		for ($i = 0; $i < $totalitems1; $i++) {
		    $iteminfo = iteminfo($itemd[$i]);
			if ($iteminfo[sprice] != "") { $theprice = $iteminfo[sprice];  } else { $theprice = $iteminfo[price]; }
		    $message = $message.$iteminfo[num]." | ".$iteminfo[title]." | ".$quantityd[$i]."  | ".$optionsd[$i]." | ".$cs.$theprice."\n";
		}
	}
	
	if ($method == "cc") {
		$message = $message."\nFor added security, confidential account information is subject to verification by your financial institution. To track the status of your order, visit ".$shopurl."\n"; 
	} elseif ($method == "po") {
	    $message = $message."\nTo track the status of your order, visit ".$shopurl."\n";
	} elseif ($method == "fax") {
		$message = $message."\nIn order to complete your order we must receive your payment. Please fax it asap to ".$shopfax.".\n\nTo track the status of your order, visit ".$shopurl."\n";
	} elseif ($method == "cm") {
		$message = $message."\nIn order to complete your order you must send your ".$payment1.", payable to \"".$payable."\", along with the form to:\n\n".$title."\n".$shopaddress."\n".$shopcity.", ".$shopstate." ".$shopzip."\n\nTo track the status of your order, visit ".$shopurl."\n";
	} else {
	    $message = $message."\nIn order to complete your order you must submit your payment via PayPal to: ".$paypalemail."\n\nTo track the status of your order, visit ".$shopurl."\n";
	}
	
	$message = $message."Click on the \"View Account\" link to learn if your order has been processed or shipped.\n";
	$message = $message."If you have questions or comments, please send them to ".$contactemail."\n\nSincerely,\n".$companyname."\n";
    
	mail($userinfo[email], "Your Order Confirmation: ".$title, $message, "From: \"".$title."\" <" . $contactemail . ">");
	mail($contactemail, "New Order Made: ".$title, "A new order has been placed. The order information is below.\n----------------Start Transaction---------------\n\n".$message, "From: \"".$title."\" <" . $contactemail . ">");
    
	if ($method == "cc") {
		setcookie("USER_", '');
	    setcookie("PASS_", '');
	    setcookie("itemtray", '');
		setcookie("payment", '');
	    setcookie("totalitems", '');
	}
	start("$title - Transaction Complete!");
	
	print("<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"><tr><td>"); 
	
	$message = "Thank you for shopping at ".$title.".<br><br>Your order total is <b>".$cs.$total."</b>.<br>Customer ID: <b>".$userinfo[userid]."</b><br>Transaction #: <b>".$tran."</b>";
	if ($savings != 0.00 || $savings != "") { $message = $message."<br>Coupon Savings: <b>".$cs.$savings."</b>"; }
	$message = $message."<br><br><table width=\"100%\"><tr><td><b>Item #</b></td><td><b>Item Name</b></td><td><b>Quantity</b></td><td><b>Options</b></td><td><b>Price</b></td></tr>";
	if ($method == "cc" || $method == "po") {
		for ($i = 0; $i < $totalitems; $i++) {
		    $iteminfo = iteminfo($itemd[$i]);
			if ($iteminfo[sprice] != "") { $theprice = $iteminfo[sprice];  } else { $theprice = $iteminfo[price]; }
		    $message = $message."<tr><td>".$iteminfo[num]."</td><td>".$iteminfo[title]."</td><td>".$quantityd[$i]."</td><td>".$optionsd[$i]."</td><td>".$cs.$theprice."</td></tr>";
		}
	} else {
		for ($i = 0; $i < $totalitems1; $i++) {
		    $iteminfo = iteminfo($itemd[$i]);
			if ($iteminfo[sprice] != "") { $theprice = $iteminfo[sprice];  } else { $theprice = $iteminfo[price]; }
		    $message = $message."<tr><td>".$iteminfo[num]."</td><td>".$iteminfo[title]."</td><td>".$quantityd[$i]."</td><td>".$optionsd[$i]."</td><td>".$cs.$theprice."</td></tr>";
		}
	}
	
	if ($method == "cc") {
		$message = $message."</table><br>For added security, confidential account information is subject to verification by your financial institution. To track the status of your order, visit ".$shopurl."<br>"; 
	} elseif ($method == "po") {
		$message = $message."</table><br>To track the status of your order, visit <a href=\"".$shopurl."\">".$shopurl."</a>.<br>";
	} elseif ($method == "fax") {
		$message = $message."</table><br>In order to complete your order we must receive your payment. We accept the following credit cards.<br><center>".$cards."</center>
		<br><table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
			<tr><td><b>Name On Card:</b></td><td>____________________________________</td></tr>
			<tr><td><b>Card Number:</b></td><td>____________________________________</td></tr>
			<tr><td><b>Expiration Date:</b></td><td>_____________________</td></tr>
			<tr><td><b>Card Type:</b></td><td>_____________________</td></tr>
			<tr><td><b>CVV2:</b></td><td>__________ (<a href=\"javascript:OpenWindowCVV2('".$shopurl."images/cvv2.gif')\">What is this?</a>)</td></tr>
			<tr><td><b>Signature:</b></td><td>____________________________________</td></tr>
		</table>
		<br><br>To track the status of your order, visit <a href=\"".$shopurl."\">".$shopurl."</a>.<br>";
	} elseif ($method == "cm") {
		$message = $message."</table><br>In order to complete your order you must send your ".$payment1.", payable to \"".$payable."\", along with this form to:<br><br>".$companyname."<br>".$shopaddress."<br>".$shopcity.", ".$shopstate." ".$shopzip."<br><br>To track the status of your order, visit <a href=\"".$shopurl."\">".$shopurl."</a>.<br>";
	} else {
	    $message = $message."</table><br>In order to complete your order you must submit your payment via PayPal to: ".$paypalemail."<br><br><div align=\"center\">
		<form method=\"post\" action=\"https://secure.paypal.com/cgi-bin/webscr\">
		<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
		<input type=\"hidden\" name=\"business\" value=\"".$paypalemail."\">
		<input type=\"hidden\" name=\"item_name\" value=\"".$title." Transaction #: ".$tran."\">
		<input type=\"hidden\" name=\"item_number\" value=\"User ID: ".$userid."\">
		<input type=\"hidden\" name=\"amount\" value=\"".$total."\">
		<input type=\"hidden\" name=\"return\" value=\"".$shopurl."?action=thankyou\">
		<input type=\"hidden\" name=\"cancel_return\" value=\"".$shopurl."\">
		<input type=\"image\" src=\"https://www.paypal.com/images/x-click-but03.gif\" name=\"submit\" alt=\" Checkout Now \">
		</form>Use the button above to pay now.</div>
		<br>To track the status of your order, visit <a href=\"".$shopurl."\">".$shopurl."</a>.<br>";
	}
	
	$message = $message."Click on the \"View Account\" link to learn if your order has been processed or shipped.\n";
	$message = $message."If you have any questions or comments, please send them to <a href=\"mailto:".$contactemail."\">".$contactemail."</a>.<br><br>Sincerely,<br>".$companyname."<br>";
	print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\"><tr><td>$message</td></tr></table>
		</div>
		</td>
		</tr>
		</table>
	");
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
	} else {
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
	}
	if ($payment1 == "Credit Card" || $payment1 == "po") {
		finalcheckout($shipping, $total, $tax, $shipprice, $shipselect, $coupon);
	}
	if ($payment1 == "Personal Check" || $payment1 == "Money Order") {
		storeinfo("cm");
	}
	if ($payment1 == "Fax Order") {
		storeinfo("fax");
	}
	if ($payment1 == "PayPal") {
		storeinfo("pp");
	}
}

showend($action);
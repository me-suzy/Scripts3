<?php 
/*
Your MySQL access information
*/
$db_name = "phabp_data";
$db_user = "phabp_user";
$db_pass = "snackerly7";
$db_host = "localhost";
$db_table= "IPNlog";
/*
Next is a quick little loop that tests the $db_xxxx values above and also constructs 
an array called $db_fields which is a list of the fields in your database for later use in 
SELECT or UPDATE commands. Doing it this way means you can add or remove some 
fields from the database and not have to rewrite lines of program code.
*/
unset($db_fields);
$db = mysql_connect($db_host, $db_user, $db_pass) or die ('Could not CONNECT because: ' . mysql_error());
mysql_select_db($db_name) or die ('Could not SELECT database because: ' . mysql_error());
	$sql = "SHOW FIELDS FROM '$db_table'";
	$result = mysql_list_fields( $db_name, $db_table, $db );
	for ($i = 0; $i < mysql_num_fields($result); $i++) 
		{
		$db_fields[] = mysql_field_name($result, $i);
		}
mysql_close($db);
/*
Next is your Webhost Manager (WHM) access information.
The access hash is in your WHM and must be transferred here without 
line breaks, spaces, tabs, or any other stray characters.
*/
$whm_host = "localhost";
$whm_user = "toadybob";
$whm_pass = "bebop";
$whm_accesshash = "yb9fc20c08x5c838cf0f2f68d66c67bb5ycyx712c8xxy715734x9bd0y74b757b2cb47160882f64d6c2822172b457995487142x43d310x454545378y55f9f9bfyb071dd0yb61x26bd3bb548y4517xc0cd0db4d7y900x46d18c2c7b8b4782fy111bd7d23d175x9c3b4b6f60f5943df3b196cb630f5652y984bdf01cbc823346096b89y2253c80375387y5441f54979f56yy0852fcy376dy1c5c37yf757x7x8y5769790d226fdy54b56xy5y0201b27y446003779c581y7840b2f354642x8fd28c1861b91b320c0y7x08454x806c283y174542b7xyyyy3b04c0yfd000234f8x4f6b50f7y8976ffyb98152cb311dbd7c6f4276f0xfb8ybbx09b7c5fd2521bbxy1x5c16194b0d7y2465f9c106b6f41d0dy0y7894676036d2c41y2dc845c177x5fb438x1xxy57cd81778bdc1dc848y59xf2d1753c7x297bc46f1y67xb29b2y3392dff1f865ybyx04b701219d1fc0fx6d3cbd6973db41b78bx3f3c6466c9952cfx1b060200ddbf6y4b2fx7yd9fx8f94f9xdcyd0byy01y40fx4y7802bxff92f1x77f5d322yy0905904195fb0cx56f2183b16bf9bd8y24cx7y6671y2216d6b9bycf2b0752dyx5xb3351385yy90xxfx4b7dbx37bfcc73878bx5b4c1fcc2079b9b21b308960x61xdyyfx85c200f9c4cb59d75912843c";
$whm_usessl = 0;	//	'0'=no, '1'=yes
/*
Use this IP for name accounts (accounts without a unique IP)
*/
$IP_none = "255.255.255.255";
/*
PayPal Subscription variables and default values
These are the variables and values sent to PayPal unless you change them here OR 
override them by placing new values in the $hosting_options array below.
*/
$post_to_URL = "www.eliteweaver.co.uk"; 	//	use this URL for testing
$post_to_URL = "www.paypal.com"; 	//	use this URL when you go live 
$business = "sales@phabp.com";	//	This is YOUR PayPal ID, or email address. This email address must be confirmed and linked to your Verified Business or Premier account
$return = "http://phabp.com/TinyTool/Welcome.php";	//	the complete URL of your welcome page... this is where customers will come back to your website after a successful subscription signup.
$cancel_return = "http://phabp.com/TinyTool/Special-offers.html";	//	the complete URL of a page on your website where PayPal sends users that don't complete the signup process... in other words, users who cancel rather than complete the signup procedure. This page is your last chance to re-sell a vacillating buyer.
$no_note = "1";	//	absolutely required for subscription processing... This field must be included, and the value must be set to 1
$sra = "1";	//	Reattempt on failure. If set to "1," and the payment fails, the payment will be reattempted two more times. After the third failure, the subscription will be cancelled. If omitted and the payment fails, payment will not be reattempted and the subscription will be immediately cancelled
$src = "1";	//	Recurring payments. If set to "1," the payment will recur unless your customer cancels the subscription before the end of the billing cycle. If omitted, the subscription payment will not recur at the end of the billing cycle
$srt = null;	//	Recurring Times. This is the number of payments which will occur at the regular rate. If omitted, payment will continue to recur at the regular rate until the subscription is cancelled
$no_shipping ="1";	//	Shipping address. If set to Ò1,Ó your customer will not be prompted for a shipping address. If omitted or set to Ò0,Ó your customer will be prompted to include a shipping address
$rm = "2";	//	Return Method. Set to '2' this makes all the Subscription vars available to your 'return' URL via POST method. Set to '1' and they'll be available via GET method (too visible). Set to '0' and the vars won't be available at all.
$modify = "0";	//	'0'= new subscription signup only, '1'= modify existing subscription only, '2'= modify existing or signup for new subscriptions
$currency_code = "USD";	//	
$on0 ="Domain Name";	//	
$on1 ="User Name";	//	
/*
Here are the PayPal Subscription variables and the WHM package name unique to each particular hosting package you offer ...
*/
$hosting_options =  
	array (
			"Vm" => array ("a3"=>"6.00", "p3"=>"1", "t3"=>"M", "item_name"=>'VALUE 50 MB and 5 GB for $6 per month', "whm_name"=>'phabp_VALUE', ), 
			"Vy" => array ("a3"=>"61.00", "p3"=>"1", "t3"=>"Y", "item_name"=>'VALUE 50 MB and 5 GB for $61 per year', "whm_name"=>'phabp_VALUE', ), 
			"Mm" => array ("a3"=>"11.00", "p3"=>"1", "t3"=>"M", "item_name"=>'MAJOR 100 MB and 10 GB for $11 per month', "whm_name"=>'phabp_MAJOR', ), 
			"My" => array ("a3"=>"112.00", "p3"=>"1", "t3"=>"Y", "item_name"=>'MAJOR 100 MB and 10 GB for $112 per year', "whm_name"=>'phabp_MAJOR', ), 
			"Py" => array ("a3"=>"27.00", "p3"=>"1", "t3"=>"Y", "item_name"=>'PROMO 10 MB and 1 GB for $27 per year', "whm_name"=>'phabp_PROMO', ), 
			);
/*
This little 'while' loop builds the dropdown (visible) list of hosting options you offer ...
*/
$package_dropdown = "";
while (list($key, $value) = each ($hosting_options)) 
	{
	$package_dropdown .= "<option value=\"$key\">" .  $value['item_name'] . "</option>\n";
	}
/*
These next values are associated with any coupons or discounts you may offer ... 
They do NOT appear in the dropdown selections and remain invisible to users.
If a user enters one of the codes on the left, the values to the right of it replace 
any other Subscription variables of the same name being sent to PayPal. 
*/
$coupon_codes =  
	array (
			"legacy" => array ("a3"=>"5.00", "p3"=>"1", "t3"=>"M", "item_name"=>'Loyal Customer Discount -- 20% off', "whm_name"=>'phabp_VALUE', ), 
			"student" => array ("a3"=>"7.00", "p3"=>"4", "t3"=>"M", "srt" => "1", "item_name"=>'PROMO Student $7 per Semester', "whm_name"=>'phabp_PROMO', ), 
			"b3g4" => array ("a1"=>"18.00", "p1"=>"3", "t1"=>"M", "a2"=>"0.00", "p2"=>"1", "t2"=>"M", "a3"=>"6.00", "p3"=>"1", "t3"=>"M", "item_name"=>'Spring Special -- Pay 3 months and get the 4th month FREE', "whm_name"=>'phabp_VALUE', ), 
			);
/*
The 'head' string is all the HTML above the order-form in the 'Order.php' program ... 
FYI: we've used the variable name 'header' for something else)
*/ 
$head = <<<content
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="pics-label" content=''" comment 'GO TO http://www.icra.org/ AND GET A PROPER RATINGS LABEL' />
<title>Power Hosting at Basic Prices</title>
<meta name="description" content="Power Hosting at Basic Prices.">
<!-- Power Hosting at Basic Prices. -->
<meta name="keywords" content="PHP MySQL Linux Apache webhost">
<script type="text/javascript" language="javascript">
<!--
		function obfuscate(name,domain)
			    {
			    var scheme = new Array('m','a','i','l','t','o',':');
			    var directive = '<a href="' + scheme.join('') + name + '&#64;' + domain + '">' + name + '&#64' + domain + '<\/a>';
			    document.write(directive); 
			    } 
// -->
</script>
<style type="text/css">
<!--
body
{
	margin: 0em;
	padding: 0em;
	border: 0px;
	background-color: black;
	font-family: verdana, arial, geneva, tahoma, sans-serif;
	font-size: 100%;
}

table
{
	margin: 0;
	padding: 0;
	border: 0;
}

#small { font-size: 80%; }

#redletter
{
	font-size: 110%;
	color: maroon;
	font-style: oblique;
	font-weight: bold;
}

#whiteletter
{
	color: white;
	font-style: oblique;
	font-weight: bold;
}

a.whiteletter:link
{
	color: white;
	text-decoration: none;
}

a.whiteletter:visited
{
	color: white;
	text-decoration: none;
}

a.whiteletter:hover
{
	color: white;
	background: black;
}

-->
</style>
</head>
	<body onload="document.inputs.DomainName.focus()">
<table summary="head" border="0" width="100%" cellspacing="0" cellpadding="0" frame="box" rules="none" align="center" bgcolor="#000000">
	<tr>
		<td align="center">
		<h2 id="whiteletter">Subscription Startup</h2>
		</td>
	</tr>
</table>
content;
/*
The 'foot' string is all the HTML below the order-form in the 'Order.php' program ... 
*/ 
$foot = <<<content
<table id="footer" width="100%" border="1" cellpadding="0" cellspacing="0" frame="box" rules="none" align="center">
	<tr align="center">
		<td valign="middle">
			<a id="whiteletter" href="/Copyright_Notice.html">Copyright&nbsp;Notice</a>&nbsp;&nbsp;
			<a id="whiteletter" href="/Service_Agreement.html">Service&nbsp;Agreement</a>&nbsp;&nbsp;
			<a id="whiteletter" href="/Acceptable_Use.html">Acceptable&nbsp;Use</a>&nbsp;&nbsp;
			<a id="whiteletter" href="/Privacy_Statement.html">Privacy&nbsp;Statement</a>&nbsp;&nbsp; 
		</td>
	</tr>
</table>
</body>
</html>
content;
//-------------------- FUNCTIONS -------------------------//
//
function &clean_domain($toclean) 
	{ 
	$toclean = str_replace(" ","",trim(strtolower ($toclean)));
	preg_match("/^((.*?):\/\/)?(([^:]*):([^@]*)@)?([^\/:]*)(:([^\/]*))?([^\?#]*\/?)?(\?([^?#]*))?(#(.*))?$/", $toclean, $aURL); 
	list(,, $sProtocol,, $sUsername, $sPassword, $sHost,, $iPort, $sPath,, $sQuery,, $sAnchor) = $aURL;
	(substr($sHost, 0, 4) == "www." ? $cleanname = substr_replace($sHost, null, 0, 4) : $cleanname = $sHost ) ;
	return $cleanname;
	}
//
function valid_domain($tocheck)
	{
	$check = "/^((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i";
	preg_match($check, $tocheck, $checked);
	return(preg_match($check, $checked[0]));
	} 
//
function valid_username($totest)
	{
	return (ereg('^[a-zA-Z]([a-zA-Z0-9]{1,7})$', $totest));
	}
//
function genpassword($length)
	{
	$password = "";
	srand((double)microtime()*1000000);
	$vowels = array("a", "e", "i", "o", "u");
	$cons = array("b", "c", "d", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "u", "v", "w", "tr", "cr", "br", "dr", "fr", "pr", "wr", "th", "ch", "ph", "st", "sp", "sw", "sl", "cl");
	$num_vowels = count($vowels);
	$num_cons = count($cons);
	for($i = 0; $i < $length; $i++)
		{
		$password .= $cons[rand(0, $num_cons - 1)] . $vowels[rand(0, $num_vowels - 1)];
		}
	return substr($password, 0, $length);
	}
//
function LIST_CONTENTS($array,$tab="&nbsp;&nbsp;&nbsp;&nbsp;",$indent=1)
	{
	while(list($key, $value) = each($array))
		{
		for($i=0; $i<$indent; $i++) $current .= $tab;
		if (is_array($value))
			{
//			$reveal .= "$current$key : Array: <BR>$current{<BR>";
			$reveal .= "$key : Array: <BR>$current{<BR>";
			$reveal .= LIST_CONTENTS($value,$tab,$indent+1)."$current}<BR>";
			}
		else $reveal .= "$current$key => $value<BR>";
		$current = NULL;
		}
	return $reveal;
	}
//-------------------- END of FUNCTIONS -------------------------//
?>

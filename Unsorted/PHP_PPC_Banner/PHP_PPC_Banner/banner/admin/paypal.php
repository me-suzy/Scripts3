<?php


/*

WHAT IS THIS FILE?

This is the file that will verify if they were sent ok from paypal, and if ok, then update their credit...

*/

require("settings.inc.php");

/* ######################################### */

// read post from PayPal system and add 'cmd'
$postvars = array();
while (list ($key, $value) = each ($HTTP_POST_VARS)) {
$postvars[] = $key;
}
$req = 'cmd=_notify-validate';
for ($var = 0; $var < count ($postvars); $var++) {
$postvar_key = $postvars[$var];
$postvar_value = $$postvars[$var];
$req .= "&" . $postvar_key . "=" . urlencode ($postvar_value);
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen ($req) . "\r\n\r\n";
$fp = fsockopen ("www.paypal.com", 80, $errno, $errstr, 30);

// assign posted variables to local variables
$receiver_email = $HTTP_POST_VARS['receiver_email'];
$item_number = $HTTP_POST_VARS['item_number'];
$invoice = $HTTP_POST_VARS['invoice'];
$payment_status = $HTTP_POST_VARS['payment_status'];
$payment_gross = $HTTP_POST_VARS['payment_gross'];
$txn_id = $HTTP_POST_VARS['txn_id'];
$payer_email = $HTTP_POST_VARS['payer_email'];

if (!$fp) {
// HTTP ERROR
echo "$errstr ($errno)";
} else {
fputs ($fp, $header . $req);
while (!feof($fp)) {
$res = fgets ($fp, 1024);
if (strcmp ($res, "VERIFIED") == 0) {
// check the payment_status=Completed
// check that txn_id has not been previously processed
// check that receiver_email is an email address in your PayPal account
// process payment
$do = "Completed";
}
else if (strcmp ($res, "INVALID") == 0) {
// log for manual investigation
$do =  "Bad";
}
}
fclose ($fp);
}


if ($do == "Bad") { bad_referer(); } elseif ($do == "Completed") { update_credit(); }

/* ################################################################ */

function bad_referer() {

global $webmaster_email;

echo "The URL you were refered from does not seem to be the PayPal site. If you feel this is an error please feel free to email the admin at $webmaster_email";

}

/* ################################################################ */

// here is where we update their credit appropriatly...do sums first, then update MySQl...so MySQL is open for less time.
function update_credit() {

global $url, $host, $database, $password, $username, $debug, $webmaster_email, $HTTP_POST_VARS, $MAINEMAIL;

  // work out what amount of credit they are adding...
  $credit_to_add = $HTTP_POST_VARS['payment_gross'] * 100; // put the decimal into cents...i.e 5.00 ends up as 500

  $update_email = $MAINEMAIL; // this is the email we are going to be updating...

// connect to mySQL and if not pass back an error
$connection = mysql_connect($host, $username, $password);  $error = mysql_error();
if (!$connection) { error("Unable to connect with your login info for MySQL. Reason: $error", $connection); }
if ($debug) { echo "host connection established...."; } //a little something for debugging

// now we need to connect the database
$db = mysql_select_db($database, $connection); $error = mysql_error();
if (!$db) { error("Unable to execute command. Reason: $error", $connection); }
if ($debug) { echo "database connection established....<BR>"; } // a little debugging info if needed...

$date = date("d/m/Y");

//update the exposures...
$query = "UPDATE advertiser_info SET CreditLeft = CreditLeft + $credit_to_add, LastTopUp = '$date' WHERE Email = '$update_email'";
$result = mysql_query($query);
$error = mysql_error();
if ($error) { error($error); }
if ($debug) { echo "Ran the banner exposures update code...<BR>"; }


// disconnect here
mysql_close($connection);

// now we have updated their credit, show a link to the advertiser page...

echo "The credit has been added to your account...please click here to go back to your account statistics login page to re-login and verify that everything went ok: <a href=\"javascript:history.go(-4)\"  onMouseOver=\"self.status=document.referrer;return true\">Go Back</a>";

}

/* ################################################################ */

// if we get any errors we need to be able to refer them somewhere...so this is the place....
// only if we got SQL stuff to get rid of tho!
function error($error, $connection) {

echo $error;
mysql_close($connection);
exit;


}

/* ################################################################ */

// if we get any errors we need to be able to refer them somewhere...so this is the place...
function normal_error($error) {

echo $error;
exit;


}

/* ################################################################ */

?>
<?php
// handle info paypal gives about payments, and add funds to buyer account


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

   // CUSTOM STUFF \\

   require 'start.php';

   $linkid = $_POST['option_selection1'];

   if ($payment_status == 'Completed')
   {
    if ($item_number == $settings->sponsoritem)
    {
     $thelink = new onelink('id', $linkid);
     $thelink->type = $settings->sponsorlinktype;
     $thelink->funds += $payment_gross;
     $thelink->ip = str_replace('buy ', '', $thelink->ip);
     $thelink->update('funds,type,ip');
    }
   }

   // END CUSTOM STUFF \\

}
else if (strcmp ($res, "INVALID") == 0) {
// log for manual investigation
}
}
fclose ($fp);
}
?>
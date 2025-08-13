<?php
/*
                                                   PHP Freelancers version 1.1
                                                   -----------------------
                                                   A script by ProEx101 Web Services
                                                   (http://www.ProEx101.com/)

    "PHP Freelancers" is not a free script. If you got this from someplace
    other than SmartCGIs.com or ProEx101 Web Services, please contact us,
    we do offer rewards for that type of information. Visit our site for up
    to date versions. Most PHP scripts are over $300, sometimes more than
    $700, this script is much less. We can keep this script cheap, as well
    as free scripts on our site, if people don't steal it.
          Also, no return links are required, but we appreciate it if you
          do find a spot for us.
          Thanks!

          Special Notice to Resellers
          ===========================
          Reselling this script without prior permission
          from ProEx101 Web Services is illegal and
          violators will be prosecuted to the fullest
          extent of the law.  To apply to be a legal
          reseller, please visit:
          http://www.ProEx101.com/freelancers/resell.php

       (c) copyright 2001 ProEx101 Web Services, SmartCGIs.com, and R3N3 Internet Services */

require "vars.php";
require "cron.php";

$did = $oid;
if ($did == "") {
echo '<title>Order Error</title><br>
Your PayPal transaction could NOT be processed, no authentication code was provided for authorization.';
} else {
$result = SQLact("query", "SELECT * FROM freelancers_deposits WHERE oid='$did'");
if (SQLact("num_rows", $result)!==0) {
if (SQLact("result", $result,0,"status") == "approved") {
echo '<title>Order Error</title><br>
Funds have already been added to your account. If this is a mistake, please e-mail <a href="mailto:' . $emailaddress . '">' . $emailaddress . '</a>.';
} else {
if (SQLact("result", $result,0,"atype") == "freelancer") {
$account = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . SQLact("result", $result,0,"username") . "'");
if (SQLact("num_rows", $account)!==0) {
if ($depositppaut == "enabled") {
$retbal = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . SQLact("result", $result,0,"username") . "' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$oldbal = SQLact("result", $retbal,0,"balance");
$newbal = $oldbal+SQLact("result", $result,0,"amount");
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+" . SQLact("result", $result,0,"amount") . "', 'PayPal Deposit (" . SQLact("result", $result,0,"oid") . ")', '" . SQLact("result", $result,0,"username") . "', 'freelancer', '$newbal', '$month/$day/$year  $hours:$minutes', '" . time() . "')");
if ($depositnotify == "enabled") {
mail($emailaddress,$companyname . " PayPal Deposit (" . SQLact("result", $result,0,"oid") . ")","The " . SQLact("result", $result,0,"atype") . "\"" . SQLact("result", $result,0,"username") . "\" has just deposited " . $currencytype . "" . $currency . "" . SQLact("result", $result,0,"amount") . " into their account (Order Number: " . SQLact("result", $result,0,"oid") . ").","From: $emailaddress");
}
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
SQLact("query", "INSERT INTO deparch (ptype, month, year, date2, username, atype, amount, namount, oid, date) VALUES ('cc', '" . date("n") . "', '" . date("Y") . "', '" . time() . "', '" . SQLact("result", $result,0,"username") . "', '" . SQLact("result", $result,0,"atype") . "', '" . SQLact("result", $result,0,"total") . "', '" . SQLact("result", $result,0,"amount") . "', '$month/$day/$year @ $hours:$minutes')");
$tram = SQLact("result", $result,0,"total")-SQLact("result", $result,0,"amount");
SQLact("query", "INSERT INTO freelancers_profits (amount, type, type2, month, year) VALUES ('$tram', 'deposit', 'paypal', '" . date("n") . "', '" . date("Y") . "')");
echo '<META HTTP-EQUIV=REFRESH CONTENT="10; URL=' . $siteurl . '/freelancers.php?manage=2">
Success! <b>' . $currencytype . '' . $currency . '' . SQLact("result", $result,0,"amount") . '</b> has been added to your account.<br>
<a href="' . $siteurl . '/freelancers.php?manage=2">Manage Account...</a>';
} else {
if ($depositnotify == "enabled") {
mail($emailaddress,$companyname . " PayPal Deposit (" . SQLact("result", $result,0,"oid") . ")","The " . SQLact("result", $result,0,"atype") . "\"" . SQLact("result", $result,0,"username") . "\" has just deposited " . $currencytype . "" . $currency . "" . SQLact("result", $result,0,"amount") . " into their account (Order Number: " . SQLact("result", $result,0,"oid") . ").

Note: The funds have NOT been added to the user's account, don't forget to do that yourself via the admin.","From: $emailaddress");
}
echo 'The funds will be added to your account after we review the transaction. If you have any questions you can e-mail ' . $emailaddress . '<br>
<a href="' . $siteurl . '/freelancers.php?manage=2">Manage Account...</a>';
}
} else {
echo '<title>Order Error</title><br>
No ' . $freelancer . ' account was found with the username ' . SQLact("result", $result,0,"username") . '. If this is a mistake, please e-mail <a href="mailto:' . $emailaddress . '">' . $emailaddress . '</a>.';
}
} else {
$account = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . SQLact("result", $result,0,"username") . "'");
if (SQLact("num_rows", $account)!==0) {
if ($depositppaut == "enabled") {
$retbal = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . SQLact("result", $result,0,"username") . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$oldbal = SQLact("result", $retbal,0,"balance");
$newbal = $oldbal+SQLact("result", $result,0,"amount");
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+" . SQLact("result", $result,0,"amount") . "', 'PayPal Deposit (" . SQLact("result", $result,0,"oid") . ")', '" . SQLact("result", $result,0,"username") . "', 'buyer', '$newbal', '$month/$day/$year  $hours:$minutes', '" . time() . "')");
if ($depositnotify == "enabled") {
mail($emailaddress,$companyname . " PayPal Deposit (" . SQLact("result", $result,0,"oid") . ")","The " . SQLact("result", $result,0,"atype") . "\"" . SQLact("result", $result,0,"username") . "\" has just deposited " . $currencytype . "" . $currency . "" . SQLact("result", $result,0,"amount") . " into their account (Order Number: " . SQLact("result", $result,0,"oid") . ").","From: $emailaddress");
}
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
SQLact("query", "INSERT INTO deparch (ptype, month, year, date2, username, atype, amount, namount, oid, date) VALUES ('cc', '" . date("n") . "', '" . date("Y") . "', '" . time() . "', '" . SQLact("result", $result,0,"username") . "', '" . SQLact("result", $result,0,"atype") . "', '" . SQLact("result", $result,0,"total") . "', '" . SQLact("result", $result,0,"amount") . "', '$month/$day/$year @ $hours:$minutes')");
$tram = SQLact("result", $result,0,"total")-SQLact("result", $result,0,"amount");
SQLact("query", "INSERT INTO freelancers_profits (amount, type, type2, month, year) VALUES ('$tram', 'deposit', 'paypal', '" . date("n") . "', '" . date("Y") . "')");
echo '<META HTTP-EQUIV=REFRESH CONTENT="10; URL=' . $siteurl . '/buyers.php?manage=2">
Success! <b>' . $currencytype . '' . $currency . '' . SQLact("result", $result,0,"amount") . '</b> has been added to your account.<br>
<a href="' . $siteurl . '/buyers.php?manage=2">Manage Account...</a>';
} else {
if ($depositnotify == "enabled") {
mail($emailaddress,$companyname . " PayPal Deposit (" . SQLact("result", $result,0,"oid") . ")","The " . SQLact("result", $result,0,"atype") . "\"" . SQLact("result", $result,0,"username") . "\" has just deposited " . $currencytype . "" . $currency . "" . SQLact("result", $result,0,"amount") . " into their account (Order Number: " . SQLact("result", $result,0,"oid") . ").

Note: The funds have NOT been added to the user's account, don't forget to do that yourself via the admin.","From: $emailaddress");
}
echo 'The funds will be added to your account after we review the transaction. If you have any questions you can e-mail ' . $emailaddress . '<br>
<a href="' . $siteurl . '/buyers.php?manage=2">Manage Account...</a>';
}
} else {
echo '<title>Order Error</title><br>
No ' . $buyer . ' account was found with the username ' . SQLact("result", $result,0,"username") . '. If this is a mistake, please e-mail <a href="mailto:' . $emailaddress . '">' . $emailaddress . '</a>.';
}
}
}
} else {
echo '<title>Order Error</title><br>
Your PayPal transaction could NOT be processed, authorization returned no results for the specified authentication code.';
}
}
?>
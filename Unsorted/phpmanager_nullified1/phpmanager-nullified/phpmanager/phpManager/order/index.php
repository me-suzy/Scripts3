<?php

////////////////////////////////////////////////////////////////////////
//                                                                    //
//                - phpManager, Copyright 2002 Taysoft -              //
//                                                                    //
// All scripts contained within are protected by international        //
// copyright law. Any unauthorised copying or distribution of         //
// this software will be dealt with accordingly.                      //
//                                                                    //
// Subsequently, the copyright message must not be                    //
// removed from the software. A fee of US$200 is payable to           //
// remove the copyright message. Additionally, phpManager is          //
// distributed as a single domain license (Single IP). You will       //
// be required to purchase another license if you wish to install     //
// on another domain.                                                 //
// Please note: Removal of the copyright message does NOT give        //
// you distribution rights to the software. All you are paying        //
// for is to remove the copyright message to give a more professional //
// look for your clients.                                             //
//                                                                    //
////////////////////////////////////////////////////////////////////////

include ("../variables.php");
include ("$rootdirectory/languages/$language");

?>

<html>

<head>
<title><?php echo $company ?> - Webhosting Order Form</title>
<LINK rel="stylesheet" type="text/css" href="../templates/<?php echo $template ?>/styles.css">
</head>

<body>

<?php

// ------------------------------------------------------------ CHECK FORM FIELDS ---------------------------------------------------------------------

if (!isset($email)) { // CHECK TO SEE IF FORM IS BEING ACCESSED OR SUBMITTED

include ("../templates/$template/order/order.inc");

} // END CHECK TO SEE IF FORM IS BEING ACCESSED OR SUBMITTED 

elseif ($submit){ // IF SUBMIT IS CLICKED

if (!$surname | !$firstname | !$telephone | !$house | !$street | !$town | !$state | !$country | !$postcode | !$username | !$password){ // IF FIELDS ARE LEFT BLANK

include ("../templates/$template/order/checkfields.inc");

} elseif (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.

'@'.

'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.

'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email)) { // IF EMAIL FIELD IS LEFT BLANK

include ("../templates/$template/order/checkemail.inc");

} elseif (!$domainname){ // IF DOMAINNAME FIELD IS LEFT BLANK

include ("../templates/$template/order/checkdomainname.inc");

} // END IF DOMAINNAME FIELD IS LEFT BLANK

elseif (!$terms){ // IF TERMS & CONDITIONS FIELD IS LEFT BLANK

include ("../templates/$template/order/checkterms.inc");

} else {

// ----------------------------------------------------------- END CHECK FORM FIELDS -----------------------------------------------------------------

// ------------------------------------------------------------ ADDS CLIENT TO THE DATABASE -----------------------------------------------------------

$sql = "SELECT * FROM clients WHERE username='$username'";
$result = mysql_query($sql);

if(mysql_num_rows($result)) { // IF USERNAME EXISTS THEN SHOW MESSAGE

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $user_taken ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

} else {

$sql = "SELECT * FROM pending WHERE username='$username'";
$result = mysql_query($sql);

if(mysql_num_rows($result)) { // IF USERNAME EXISTS THEN SHOW MESSAGE

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $user_taken ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

} else {

$sql = "SELECT * FROM plans WHERE package='$package'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$monthly = $myrow["monthly"];
$quarterly = $myrow["quarterly"];
$biannually = $myrow["biannually"];
$yearly = $myrow["yearly"];

if ($schedule=="Monthly"){

$price = $monthly;

} elseif ($schedule=="Quarterly"){

$price = $quarterly;

} elseif ($schedule=="Bi-Annually"){

$price = $biannually;

} elseif ($schedule=="Yearly"){

$price = $yearly;

}

$sql = "INSERT INTO pending (surname,firstname,telephone,email,icq,referrer,house,street,town,state,country,postcode,domainname,domain,package,schedule,payment,
username,password,amount) VALUES ('$surname','$firstname','$telephone','$email','$icq','$referrer','$house',
'$street','$town','$state','$country','$postcode','$domainname','$domain','$package','$schedule','$payment','$username','$password','$price')";

$result = mysql_query($sql);

if ($payment=="Monies"){ // IF PAYMENT BY MONIES

include ("../templates/$template/order/monies.inc");

include ("../emails/moniesconfirmation.inc");

} // END IF PAYMENT BY MONIES

if ($payment=="Cheque"){ // IF PAYMENT BY CHEQUE

include ("../templates/$template/order/cheque.inc");

include ("../emails/chequeconfirmation.inc");

} // END IF PAYMENT BY CHEQUE

if ($payment=="Nochex"){ // PAYMENT BY NOCHEX

$sql = "SELECT * FROM plans WHERE package='$package'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);

if ($schedule=="Monthly"){

$link = $myrow["nochex_monthly"];

} elseif ($schedule=="Quarterly"){

$link = $myrow["nochex_quarterly"];

} elseif ($schedule=="Bi-Annually"){

$link = $myrow["nochex_biannually"];

} elseif ($schedule=="Yearly"){

$link = $myrow["nochex_yearly"];

}

include ("../templates/$template/order/nochex.inc");

include ("../emails/nochexconfirmation.inc");

} // END IF PAYMENT BY NOCHEX

if ($payment=="Paypal"){ // PAYMENT BY PAYPAL

$sql = "SELECT * FROM pending WHERE username='$username' AND domainname='$domainname'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$id = $myrow["id"];

$sql = "SELECT * FROM plans WHERE package='$package'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);

if ($schedule=="Monthly"){

$link = $myrow["paypal_monthly"];
$link .= "&custom=$id";

} elseif ($schedule=="Quarterly"){

$link = $myrow["paypal_quarterly"];
$link .= "&custom=$id";

} elseif ($schedule=="Bi-Annually"){

$link = $myrow["paypal_biannually"];
$link .= "&custom=$id";

} elseif ($schedule=="Yearly"){

$link = $myrow["paypal_yearly"];
$link .= "&custom=$id";

}

include ("../templates/$template/order/paypal.inc");

include ("../emails/paypalconfirmation.inc");

} // END IF PAYMENT BY PAYPAL

if ($payment=="Creditcard"){ // IF PAYMENT BY CREDITCARD

$sql = "SELECT * FROM pending WHERE username='$username' AND domainname='$domainname'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$id = $myrow["id"];

if ($processor=="Worldpay"){

if ($schedule=="Monthly"){
$link = "https://select.worldpay.com/wcc/purchase?desc=$package&cartId=$id&instId=$instId&currency=$currency&amount=$price&futurePayType=regular&option=0&startDelayMult=1&startDelayUnit=3&intervalMult=1&intervalUnit=3&normalAmount=$price&name=$firstname%20$surname&address=$house,%20$street,%20$town,%20$state&country=$country&postcode=$postcode&tel=$telephone&email=$email";
echo "<script>window.setTimeout('changeurl();',100); function changeurl(){window.location='$link'}</script>";
} elseif ($schedule=="Quarterly"){
$link = "https://select.worldpay.com/wcc/purchase?desc=$package&cartId=$id&instId=$instId&currency=$currency&amount=$price&futurePayType=regular&option=0&startDelayMult=3&startDelayUnit=3&intervalMult=3&intervalUnit=3&normalAmount=$price&name=$firstname%20$surname&address=$house,%20$street,%20$town,%20$state&country=$country&postcode=$postcode&tel=$telephone&email=$email";
echo "<script>window.setTimeout('changeurl();',100); function changeurl(){window.location='$link'}</script>";
} elseif ($schedule=="Bi-Annually"){
$link = "https://select.worldpay.com/wcc/purchase?desc=$package&cartId=$id&instId=$instId&currency=$currency&amount=$price&futurePayType=regular&option=0&startDelayMult=6&startDelayUnit=3&intervalMult=6&intervalUnit=3&normalAmount=$price&name=$firstname%20$surname&address=$house,%20$street,%20$town,%20$state&country=$country&postcode=$postcode&tel=$telephone&email=$email";
echo "<script>window.setTimeout('changeurl();',100); function changeurl(){window.location='$link'}</script>";
} elseif ($schedule=="Yearly"){
$link = "https://select.worldpay.com/wcc/purchase?desc=$package&cartId=$id&instId=$instId&currency=$currency&amount=$price&futurePayType=regular&option=0&startDelayMult=1&startDelayUnit=4&intervalMult=1&intervalUnit=4&normalAmount=$price&name=$firstname%20$surname&address=$house,%20$street,%20$town,%20$state&country=$country&postcode=$postcode&tel=$telephone&email=$email";
echo "<script>window.setTimeout('changeurl();',100); function changeurl(){window.location='$link'}</script>";
}

include ("../emails/creditcardconfirmation.inc");

} else {

$sql = "SELECT * FROM plans WHERE package='$package'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);

if ($processor=="2Checkout"){

$variable = "&merchant_order_id=$id";

} else {

$variable = "";

}

if ($schedule=="Monthly"){

$link = $myrow["cc_monthly"];
$link .= $variable;

} elseif ($schedule=="Quarterly"){

$link = $myrow["cc_quarterly"];
$link .= $variable;

} elseif ($schedule=="Bi-Annually"){

$link = $myrow["cc_biannually"];
$link .= $variable;

} elseif ($schedule=="Yearly"){

$link = $myrow["cc_yearly"];
$link .= $variable;

}

include ("../emails/creditcardconfirmation.inc");

include ("../templates/$template/order/creditcard.inc");

}

} // END IF PAYMENT BY CREDITCARD

include ("../emails/neworder.inc");

}
}
} // END DISPLAY RESULTS
}

// ------------------------------------------------------ END ADDS CLIENT TO THE DATABASE ------------------------------------------------------------

?>

</body>
</html>
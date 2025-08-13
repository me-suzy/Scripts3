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

$default_language = $language;
$default_template = $template;

$sql = "SELECT * FROM staff WHERE adminname='$logonname' AND password='$logonid'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$access = $myrow["access"];
$language = $myrow["language"];
$template = $myrow["template"];
include ("$rootdirectory/languages/$language");

?>

<html>
<body>

<LINK rel="stylesheet" type="text/css" href="../templates/<?php echo $template ?>/styles.css">

<?php

if(mysql_num_rows($result)) {

if ($editvariables) {

if ($new_dateformat=="%d/%m/%y"){

$new_dateformat = "UK";

} elseif ($new_dateformat=="%m/%d/%y") {

$new_dateformat = "US";

}

$sql = "UPDATE variables SET language='$new_language', template='$new_template', clientmanagerlogin_title='$new_clientmanagerlogin_title',
clientmanager_title='$new_clientmanager_title', accountmanagerlogin_title='$new_accountmanagerlogin_title',
accountmanager_title='$new_accountmanager_title', admin_email='$new_admin_email', accounts_email='$new_accounts_email',
supports_email='$new_supports_email', checkout_email='$new_checkout_email', revecom_email='$new_revecom_email',
paypal_email='$new_paypal_email', worldpay_email='$new_worldpay_email', company='$new_company',
processor='$new_processor', dateformat='$new_dateformat', instId='$new_instId', currency='$new_currency', worldpayPW='$new_worldpayPW', cp='$new_cp',
currencytype='$new_currencytype'";

$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $variables_modified ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='setup.php'}</script>";

} else {

if ($variables){

include ("../templates/$template/admin/variables.inc");

} elseif ($addplans){

$sql = "INSERT INTO plans (package,monthly,quarterly,biannually,yearly) VALUES ('$package','$monthly','$quarterly','$biannually','$yearly')";
$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $plan_added ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='setup.php'}</script>";

} elseif ($editplan){

if ($confirm){

$sql = "UPDATE plans SET package='$package', monthly='$monthly', quarterly='$quarterly', biannually='$biannually', yearly='$yearly' WHERE id=$id";
$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $plan_modified ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='setup.php'}</script>";

} elseif ($delete){

$sql = "DELETE FROM plans WHERE id=$id";
$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $plan_deleted ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='setup.php'}</script>";

} else {

$sql = "SELECT * FROM plans WHERE id='$id'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$package = $myrow["package"];
$monthly = $myrow["monthly"];
$quarterly = $myrow["quarterly"];
$biannually = $myrow["biannually"];
$yearly = $myrow["yearly"];

include ("../templates/$template/admin/editplan.inc");

}

} elseif ($plans){

?>

<table width="570" border="0" cellspacing="0" cellpadding="2" class="main_bold" style="border: 1 solid #000000">
<tr>
<td width="570" colspan="5" align="center">
<center><button onclick="window.location='setup.php'" class="formfield">Back</button></center>
<br>
<span class="summary_message">- Current Plans -</span>
</td>
</tr>
<tr>
<td width="250">
Name: (Click To Edit)
</td>
<td width="80">
Monthly:
</td>
<td width="80">
Quarterly:
</td>
<td width="80">
Bi-Annual:
</td>
<td width="80">
Yearly:
</td>
</tr>
<?php include ("$rootdirectory/admin/plans.inc");?>
</table>

<?php

include ("../templates/$template/admin/plans.inc");

} elseif ($paypal){

if ($editpaypal){

$sql = "UPDATE plans SET paypal_monthly='$paypal_monthly', paypal_quarterly='$paypal_quarterly', paypal_biannually='$paypal_biannually',
paypal_yearly='$paypal_yearly' WHERE id=$id";
$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $links_paypalmodified ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='setup.php?paypal=yes'}</script>";

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="2" class="main_bold" style="border: 1 solid #000000">
<tr>
<td width="570" colspan="5" align="center">
<center><button onclick="window.location='setup.php'" class="formfield">Back</button></center>
<br>
<span class="summary_message">- PayPal Links -</span>
</td>
</tr>

<?php
include ("$rootdirectory/admin/paypal.inc");
?>
</table>
<?php

}

} elseif ($nochex){

if ($editnochex){

$sql = "UPDATE plans SET nochex_monthly='$nochex_monthly', nochex_quarterly='$nochex_quarterly', nochex_biannually='$nochex_biannually',
nochex_yearly='$nochex_yearly' WHERE id=$id";
$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $links_nochexmodified ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='setup.php?nochex=yes'}</script>";

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="2" class="main_bold" style="border: 1 solid #000000">
<tr>
<td width="570" colspan="5" align="center">
<center><button onclick="window.location='setup.php'" class="formfield">Back</button></center>
<br>
<span class="summary_message">- NOCHEX Links -</span>
</td>
</tr>

<?php
include ("$rootdirectory/admin/nochex.inc");
?>
</table>
<?php

}

} elseif ($cc){

if ($editcc){

$sql = "UPDATE plans SET cc_monthly='$cc_monthly', cc_quarterly='$cc_quarterly', cc_biannually='$cc_biannually',
cc_yearly='$cc_yearly' WHERE id=$id";
$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $links_ccmodified ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='setup.php?cc=yes'}</script>";

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="2" class="main_bold" style="border: 1 solid #000000">
<tr>
<td width="570" colspan="5" align="center">
<center><button onclick="window.location='setup.php'" class="formfield">Back</button></center>
<br>
<span class="summary_message">- Credit Card Links -</span>
</td>
</tr>

<?php
include ("$rootdirectory/admin/cc.inc");
?>
</table>
<?php

}

} else {

include ("../templates/$template/admin/setup.inc");

}

}

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

Access Denied

</td>
</tr>
</table>

<?php

}

?>

</body>

</html>
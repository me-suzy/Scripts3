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

// --------------------------------------------------- IF EDIT EMAIL IS CLICKED ---------------------------------------------------------------

if ($submit){

if ($invoice) { // OPEN INVOICE EMAIL FOR EDIT

$filePointer=fOpen("../emails/invoice.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_invoice ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($invoicerecord) { // OPEN INVOICERECORD EMAIL FOR EDIT

$filePointer=fOpen("../emails/invoicerecord.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_invoicerecord ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($activation) { // OPEN ACTIVATION EMAIL FOR EDIT

$filePointer=fOpen("../emails/activation.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_activation ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($activationbcc) { // OPEN ACTIVATIONBCC EMAIL FOR EDIT

$filePointer=fOpen("../emails/activationbcc.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_activationbcc ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($finalnotice) { // OPEN FINALNOTICE EMAIL FOR EDIT

$filePointer=fOpen("../emails/finalnotice.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_finalnotice ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($finalnoticebcc) { // OPEN FINALNOTICEBCC EMAIL FOR EDIT

$filePointer=fOpen("../emails/finalnoticebcc.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_finalnoticebcc ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($adminamend) { // OPEN ADMIN AMEND EMAIL FOR EDIT

$filePointer=fOpen("../emails/adminamendsupport.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_adminamend ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($invoicerecord) { // OPEN INVOICERECORD EMAIL FOR EDIT

$filePointer=fOpen("../emails/invoicerecord.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_invoicerecord ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($cancel) { // OPEN CANCELLATION EMAIL FOR EDIT

$filePointer=fOpen("../emails/cancelaccount.inc","wb");
fWrite($filePointer,stripslashes($message));
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_cancellation ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($cancelorder) { // OPEN CANCEL ORDER EMAIL FOR EDIT

$filePointer=fOpen("../emails/cancelorder.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_ordercancellation ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($cheque) { // OPEN CHEQUE EMAIL FOR EDIT

$filePointer=fOpen("../emails/chequeconfirmation.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_chequeconfirmation ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($clientamend) { // OPEN CLIENT AMEND EMAIL FOR EDIT

$filePointer=fOpen("../emails/amendsupportnotice.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_clientamend ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($closesupport) { // OPEN CLOSE SUPPORT TICKET EMAIL FOR EDIT

$filePointer=fOpen("../emails/closesupport.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_closesupport ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($creditcard) { // OPEN CREDIT CARD EMAIL FOR EDIT

$filePointer=fOpen("../emails/creditcardconfirmation.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_ccconfirmation ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($monies) { // OPEN MONIES EMAIL FOR EDIT

$filePointer=fOpen("../emails/moniesconfirmation.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_moniesconfirmation ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($neworder) { // OPEN NEW ORDER EMAIL FOR EDIT

$filePointer=fOpen("../emails/neworder.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_neworder ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($newsupport) { // OPEN NEW SUPPORT EMAIL FOR EDIT

$filePointer=fOpen("../emails/support.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_support ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($nochex) { // OPEN NOCHEX EMAIL FOR EDIT

$filePointer=fOpen("../emails/nochexconfirmation.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_nochexconfirmation ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($paypal) { // OPEN PAYPAL EMAIL FOR EDIT

$filePointer=fOpen("../emails/paypalconfirmation.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_paypalconfirmation ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($receipt) { // OPEN RECEIPT EMAIL FOR EDIT

$filePointer=fOpen("../emails/receipt.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_receipt ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

} elseif ($supportnotice) { // OPEN SUPPORT NOTICE EMAIL FOR EDIT

$filePointer=fOpen("../emails/supportnotice.inc","wb");
fWrite($filePointer,stripslashes($message)); 
fClose($filePointer);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_supportnotice ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='email.php'}</script>

</td>
</tr>
</table>

<?php

}

// ----------------------------------------------- END IF EDIT EMAIL IS CLICKED ---------------------------------------------------------------

// --------------------------------------------------- IF EMAIL IS CLICKED ---------------------------------------------------------------

} elseif ($invoice){ // OPEN INVOICE EMAIL

$filePointer=fOpen("../emails/invoice.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/invoice.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?invoice=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($invoicerecord){ // OPEN INVOICERECORD EMAIL

$filePointer=fOpen("../emails/invoicerecord.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/invoicerecord.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?invoicerecord=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($activation){ // OPEN ACTIVATION EMAIL

$filePointer=fOpen("../emails/activation.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/activation.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?activation=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($activationbcc){ // OPEN ACTIVATIONBCC EMAIL

$filePointer=fOpen("../emails/activationbcc.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/activationbcc.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?activationbcc=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($finalnotice){ // OPEN FINALNOTICE EMAIL

$filePointer=fOpen("../emails/finalnotice.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/finalnotice.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?finalnotice=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($finalnoticebcc){ // OPEN FINALNOTICEBCC EMAIL

$filePointer=fOpen("../emails/finalnoticebcc.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/finalnoticebcc.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?finalnoticebcc=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($adminamend){ // OPEN ADMIN AMEND EMAIL

$filePointer=fOpen("../emails/adminamendsupport.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/adminamendsupport.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?adminamend=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($cancel){ // OPEN CANCEL ACCOUNT EMAIL

$filePointer=fOpen("../emails/cancelaccount.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/cancelaccount.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?cancel=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($cancelorder){ // OPEN CANCEL ORDER EMAIL

$filePointer=fOpen("../emails/cancelorder.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/cancelorder.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?cancelorder=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($cheque){ // OPEN CHEQUE EMAIL

$filePointer=fOpen("../emails/chequeconfirmation.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/chequeconfirmation.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?cheque=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($clientamend){ // OPEN CLIENT AMEND EMAIL

$filePointer=fOpen("../emails/amendsupportnotice.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/amendsupportnotice.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?clientamend=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($closesupport){ // OPEN CLOSE SUPPORT EMAIL

$filePointer=fOpen("../emails/closesupport.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/closesupport.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?closesupport=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($creditcard){ // OPEN CREDITCARD EMAIL

$filePointer=fOpen("../emails/creditcardconfirmation.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/creditcardconfirmation.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?creditcard=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($monies){ // OPEN MONIES EMAIL

$filePointer=fOpen("../emails/moniesconfirmation.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/moniesconfirmation.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?monies=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($neworder){ // OPEN NEW ORDER EMAIL

$filePointer=fOpen("../emails/neworder.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/neworder.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?neworder=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($newsupport){ // OPEN NEW SUPPORT EMAIL

$filePointer=fOpen("../emails/support.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/support.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?newsupport=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($nochex){ // OPEN NOCHEX EMAIL

$filePointer=fOpen("../emails/nochexconfirmation.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/nochexconfirmation.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?nochex=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($paypal){ // OPEN PAYPAL EMAIL

$filePointer=fOpen("../emails/paypalconfirmation.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/paypalconfirmation.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?paypal=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($receipt){ // OPEN RECEIPT EMAIL

$filePointer=fOpen("../emails/receipt.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/receipt.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?receipt=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} elseif ($supportnotice){ // OPEN SUPPORT NOTICE EMAIL

$filePointer=fOpen("../emails/supportnotice.inc","rb"); 
$fileContents=fRead($filePointer, fileSize("../emails/supportnotice.inc")); 
fClose($filePointer);

?>

<form method="post" action="<?php echo $PHP_SELF ?>?supportnotice=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center">
<textarea cols="95" rows="26" name="message" class="formfield"><?php echo $fileContents ?></textarea>
</td>
</tr>
<tr>
<td width="570" align="center">
<input type="Submit" name="submit" value="<?php echo $edit ?>" class="formfield">
&nbsp;
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
</table>
</form>

<?php

} else {

// ------------------------------------------------- END IF EMAIL IS CLICKED ---------------------------------------------------------------

// --------------------------------------------------- PAGE FORMATTING STARTS HERE ---------------------------------------------------------------

include ("../templates/$template/admin/emails.inc");

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
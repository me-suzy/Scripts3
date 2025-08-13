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

include ("../../variables.php");

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

<LINK rel="stylesheet" type="text/css" href="../../templates/<?php echo $template ?>/styles.css">

<?php

if(mysql_num_rows($result)) {

// --------------------------------------------------- IF SUBMIT IS CLICKED ---------------------------------------------------------------

if ($submit) {

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

} else { // IF USERNAME DOES NOT EXIST, CREATE NEW RECORD

$paid = "Yes";
$status = "Online";
$startdate =  strftime("$dateformat",time());
$encryptedpassword = md5($password);

$date_time_array =  getdate(time());
$hours =  $date_time_array["hours"];
$minutes =  $date_time_array["minutes"];
$seconds =  $date_time_array["seconds"];
$month =  $date_time_array["mon"];
$day =  $date_time_array["mday"];
$year =  $date_time_array["year"];

if ($schedule == "Monthly"){

$invoicedate = mktime($hours,$minutes,$seconds,$month+1,$day-7,$year);
$duedate = mktime($hours,$minutes,$seconds,$month+1,$day,$year);
$sql = "UPDATE clients SET invoicedate='$invoicedate' WHERE id=$id";
$result = mysql_query($sql);

} elseif ($schedule == "Quarterly"){

$invoicedate = mktime($hours,$minutes,$seconds,$month+3,$day-7,$year);
$duedate = mktime($hours,$minutes,$seconds,$month+3,$day,$year);
$sql = "UPDATE clients SET invoicedate='$invoicedate' WHERE id=$id";
$result = mysql_query($sql);

} elseif ($schedule == "Bi-Annually"){

$invoicedate = mktime($hours,$minutes,$seconds,$month+6,$day-7,$year);
$duedate = mktime($hours,$minutes,$seconds,$month+6,$day,$year);
$sql = "UPDATE clients SET invoicedate='$invoicedate' WHERE id=$id";
$result = mysql_query($sql);

} elseif ($schedule == "Yearly"){

$invoicedate = mktime($hours,$minutes,$seconds,$month,$day-7,$year+1);
$duedate = mktime($hours,$minutes,$seconds,$month,$day,$year+1);
$sql = "UPDATE clients SET invoicedate='$invoicedate' WHERE id=$id";
$result = mysql_query($sql);

}

$sql = "INSERT INTO clients (surname,firstname,telephone,email,icq,referrer,house,street,town,state,country,postcode,domainname,domain,package,schedule,payment,
username,password,ipaddress,startdate,invoicedate,duedate,amount,paid,status,notes,language,template) VALUES ('$surname','$firstname','$telephone','$email','$icq','$referrer','$house',
'$street','$town','$state','$country','$postcode','$domainname','$domain','$package','$schedule','$payment','$username','$encryptedpassword','$ipaddress','$startdate',
'$invoicedate','$duedate','$amount','$paid','$status','$notes','$default_language','$default_template')";

if ($username && $password && $startdate && $invoicedate && $duedate && $amount && $status && $notes){ // CHECKS TO SEE IF REQUIRED FIELDS ARE FILLED IN

$result = mysql_query($sql);

if ($cp=="WHM"){

include ("../whm/create.inc");

}

$sql = "SELECT * FROM clients WHERE username='$username' AND domainname='$domainname'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$id = $myrow["id"];
$ip = $myrow["ipaddress"];
include ("../../emails/activation.inc");
include ("../../emails/activationbcc.inc");
$date =  strftime("$dateformat",time());

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $client_added ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='../main.php';}</script>";

} else { // IF FIELDS ARE NOT FILLED IN

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $client_notadded ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

} // END IF FIELDS ARE NOT FILLED IN

}

// ------------------------------------------------ END IF SUBMIT IS CLICKED --------------------------------------------------------------

} else {

// --------------------------------------------------- PAGE FORMATTING STARTS HERE --------------------------------------------------------

include ("../../templates/$template/admin/clientmanager/add.inc");

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
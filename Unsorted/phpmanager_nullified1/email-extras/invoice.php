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

// -------------------------------------------------- IF INVOICE IS CLICKED ---------------------------------------------------------------

if ($invoice) { // IF INVOICE IS PRESSED

include ("$rootdirectory/admin/clientmanager/readclientdetails.inc");

$details = "Invoice sent for $currencytype$amount";
$date =  strftime("$dateformat",time());
$duedate =  strftime("$dateformat",$duedate);
$date_time_array =  getdate($invoicedate);
$hours =  $date_time_array["hours"];
$minutes =  $date_time_array["minutes"];
$seconds =  $date_time_array["seconds"];
$month =  $date_time_array["mon"];
$day =  $date_time_array["mday"];
$year =  $date_time_array["year"];

if ($schedule == "Monthly"){

$invoicedate = mktime($hours,$minutes,$seconds,$month+1,$day,$year);
$sql = "UPDATE clients SET invoicedate='$invoicedate', paid='No' WHERE id=$id";
$result = mysql_query($sql);

} elseif ($schedule == "Quarterly"){

$invoicedate = mktime($hours,$minutes,$seconds,$month+3,$day,$year);
$sql = "UPDATE clients SET invoicedate='$invoicedate', paid='No' WHERE id=$id";
$result = mysql_query($sql);

} elseif ($schedule == "Bi-Annually"){

$invoicedate = mktime($hours,$minutes,$seconds,$month+6,$day,$year);
$sql = "UPDATE clients SET invoicedate='$invoicedate', paid='No' WHERE id=$id";
$result = mysql_query($sql);

} elseif ($schedule == "Yearly"){

$invoicedate = mktime($hours,$minutes,$seconds,$month,$day,$year+1);
$sql = "UPDATE clients SET invoicedate='$invoicedate', paid='No' WHERE id=$id";
$result = mysql_query($sql);

}

include ("../../emails/invoice.inc");
include ("../../emails/invoicerecord.inc");

$sql = "INSERT INTO transactions (id,details,date) VALUES ('$id','$details','$date')";

$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $invoice_sent ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='../main.php'}</script>";

// ---------------------------------------------- END IF INVOICE IS CLICKED ---------------------------------------------------------------

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $client_error ?>

</td>
</tr>
</table>

<?php

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





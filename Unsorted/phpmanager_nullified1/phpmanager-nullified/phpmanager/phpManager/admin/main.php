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

// --------------------------------------------------- IF HASPAID IS CLICKED --------------------------------------------------------------

if ($haspaid) {

include ("$rootdirectory/admin/clientmanager/readclientdetails.inc");

$details = "Payment received for $currencytype$amount";
$date =  strftime("$dateformat",time());
$date_time_array =  getdate($duedate);
$hours =  $date_time_array["hours"];
$minutes =  $date_time_array["minutes"];
$seconds =  $date_time_array["seconds"];
$month =  $date_time_array["mon"];
$day =  $date_time_array["mday"];
$year =  $date_time_array["year"];

if ($schedule == "Monthly"){

$duedate = mktime($hours,$minutes,$seconds,$month+1,$day,$year);
$sql = "UPDATE clients SET duedate='$duedate', paid='Yes' WHERE id=$id";
$result = mysql_query($sql);

} elseif ($schedule == "Quarterly"){

$duedate = mktime($hours,$minutes,$seconds,$month+3,$day,$year);
$sql = "UPDATE clients SET duedate='$duedate', paid='Yes' WHERE id=$id";
$result = mysql_query($sql);

} elseif ($schedule == "Bi-Annually"){

$duedate = mktime($hours,$minutes,$seconds,$month+6,$day,$year);
$sql = "UPDATE clients SET duedate='$duedate', paid='Yes' WHERE id=$id";
$result = mysql_query($sql);

} elseif ($schedule == "Yearly"){

$duedate = mktime($hours,$minutes,$seconds,$month,$day,$year+1);
$sql = "UPDATE clients SET duedate='$duedate', paid='Yes' WHERE id=$id";
$result = mysql_query($sql);

}

include ("../emails/receipt.inc");

$sql = "INSERT INTO transactions (id,details,date) VALUES ('$id','$details','$date')";

$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $client_updated ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='main.php'}</script>";

// --------------------------------------------------- END IF HASPAID IS CLICKED -----------------------------------------------------------

} else {

// --------------------------------------------------- PAGE FORMATTING STARTS HERE --------------------------------------------------------

include ("../templates/$template/admin/summary.inc");

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
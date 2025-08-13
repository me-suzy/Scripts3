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

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center" valign="top">
<table width="570" border="0" cellspacing="0" cellpadding="2">
<tr>
<td width="570" align="center" class="main">
<b>- <?php echo $company_statistics ?> -</b>
<br>
<br>
<hr>
</td>
</tr>

<!-- --------------------------------------------------- CALCULATE MONTHLY CLIENTS ---------------------------------------------------------- -->

<tr>
<td width="570" height="20" class="main">
<?php
$result = mysql_query("SELECT * FROM clients",$db);
$monthlyclients = 0;
while ($myrow = mysql_fetch_array($result)) {
if ($myrow["username"] && $myrow["schedule"]=="Monthly"){
$monthlyclients = $monthlyclients + 1;
}
}

?>
<b><?php echo $company_monthly ?> <font color="#FF0000"><?php echo $monthlyclients ?></b></font>
</td>
</tr>

<!-- ---------------------------------------------------- END CALCULATE MONTHLY CLIENTS ------------------------------------------------------ -->

<!-- ------------------------------------------------------ CALCULATE QUARTERLY CLIENTS ------------------------------------------------------- -->

<tr>
<td width="570" height="20" class="main">
<?php
$result = mysql_query("SELECT * FROM clients",$db);
$quarterlyclients = 0;
while ($myrow = mysql_fetch_array($result)) {
if ($myrow["username"] && $myrow["schedule"]=="Quarterly"){
$quarterlyclients = $quarterlyclients + 1;
}
}
?>
<b><?php echo $company_quarterly ?> <font color="#FF0000"><?php echo $quarterlyclients ?></b></font>
</td>
</tr>

<!-- ------------------------------------------------------ END CALCULATE QUARTERLY CLIENTS --------------------------------------------------- -->

<!-- --------------------------------------------------- CALCULATE BIANNUAL CLIENTS ---------------------------------------------------------- --> 

<tr> 
<td width="570" height="20" class="main"> 
<?php 
$result = mysql_query("SELECT * FROM clients",$db); 
$biannualclients = 0; 
while ($myrow = mysql_fetch_array($result)) { 
if ($myrow["username"] && $myrow["schedule"]=="Bi-Annually"){ 
$biannualclients = $biannualclients + 1; 
} 
} 

?> 
<b><?php echo $company_biannual ?> <font color="#FF0000"><?php echo $biannualclients ?></b></font> 
</td> 
</tr> 

<!-- ---------------------------------------------------- END CALCULATE BIANNUAL CLIENTS ------------------------------------------------------ --> 

<!-- ------------------------------------------------------- CALCULATE YEARLY CLIENTS --------------------------------------------------------- -->

<tr>
<td width="570" height="20" class="main">
<?php
$result = mysql_query("SELECT * FROM clients",$db);
$yearlyclients = 0;
while ($myrow = mysql_fetch_array($result)) {
if ($myrow["username"] && $myrow["schedule"]=="Yearly"){
$yearlyclients = $yearlyclients + 1;
}
}
?>
<b><?php echo $company_yearly ?> <font color="#FF0000"><?php echo $yearlyclients ?></b></font>
</td>
</tr>

<!-- --------------------------------------------------------- END CALCULATE YEARLY CLIENTS --------------------------------------------------- -->

<!-- --------------------------------------------------------- PRINT TOTAL NUMBER OF CLIENTS ---------------------------------------------------- -->

<tr>
<td width="570" height="20" class="main">
<?php
$result = mysql_query("SELECT * FROM clients",$db);
$clients = 0;
while ($myrow = mysql_fetch_array($result)) {
if ($myrow["username"]){
$clients = $clients + 1;
}
}
?>
<b><?php echo $company_clients ?> <font color="#FF0000"><?php echo $clients ?></b></font>
<br>
<br>
<hr>
</td>
</tr>

<!-- ------------------------------------------------------ END PRINT TOTAL NUMBER OF CLIENTS --------------------------------------------------- -->

<!-- ------------------------------------------------------ CALCULATE MONTHLY INCOME ----------------------------------------------------------- -->

<tr>
<td width="570" height="20" class="main">
<?php
$result = mysql_query("SELECT * FROM clients",$db);
$monthlyincome = 0;
while ($myrow = mysql_fetch_array($result)) {
if ($myrow["username"] && $myrow["schedule"]=="Monthly"){
$amount = $myrow["amount"];
$monthlyincome = $monthlyincome + $amount;
}
}
?>
<b><?php echo $company_monthlyincome ?> <?php echo $currencytype ?><font color="#FF0000"><?php echo $monthlyincome ?></b></font>
</td>
</tr>

<!-- ------------------------------------------------------ END CALCULATE MONTHLY INCOME -------------------------------------------------------- -->

<!-- ------------------------------------------------------ CALCULATE QUARTERLY INCOME ---------------------------------------------------------- -->

<tr>
<td width="570" height="20" class="main">
<?php
$result = mysql_query("SELECT * FROM clients",$db);
$quarterlyincome = 0;
while ($myrow = mysql_fetch_array($result)) {
if ($myrow["username"] && $myrow["schedule"]=="Quarterly"){
$amount = $myrow["amount"];
$quarterlyincome = $quarterlyincome + $amount;
}
}
?>
<b><?php echo $company_quarterlyincome ?> <?php echo $currencytype ?><font color="#FF0000"><?php echo $quarterlyincome ?></b></font>
</td>
</tr>

<!-- ------------------------------------------------------ END CALCULATE QUARTERLY INCOME ------------------------------------------------------ -->

<!-- ------------------------------------------------------ CALCULATE BI-ANNUAL INCOME ---------------------------------------------------------- -->

<tr>
<td width="570" height="20" class="main">
<?php
$result = mysql_query("SELECT * FROM clients",$db);
$biannualincome = 0;
while ($myrow = mysql_fetch_array($result)) {
if ($myrow["username"] && $myrow["schedule"]=="Bi-Annually"){
$amount = $myrow["amount"];
$biannualincome = $biannualincome + $amount;
}
}
?>
<b><?php echo $company_biannualincome ?> <?php echo $currencytype ?><font color="#FF0000"><?php echo $biannualincome ?></b></font>
</td>
</tr>

<!-- ------------------------------------------------------ END CALCULATE BI-ANNUAL INCOME ------------------------------------------------------ -->

<!-- ------------------------------------------------------ CALCULATE YEARLY INCOME ------------------------------------------------------------- -->

<tr>
<td width="570" height="20" class="main">
<?php
$result = mysql_query("SELECT * FROM clients",$db);
$yearlyincome = 0;
while ($myrow = mysql_fetch_array($result)) {
if ($myrow["username"] && $myrow["schedule"]=="Yearly"){
$amount = $myrow["amount"];
$yearlyincome = $yearlyincome + $amount;
}
}
?>
<b><?php echo $company_yearlyincome ?> <?php echo $currencytype ?><font color="#FF0000"><?php echo $yearlyincome ?></b></font>
</td>
</tr>

<!-- ----------------------------------------------------- END CALCULATE YEARLY INCOME ---------------------------------------------------------- -->

<!-- ----------------------------------------------------- PRINT TOTAL INCOME ------------------------------------------------------------------ -->

<tr>
<td width="570" height="20" class="main">
<?php
$totalincome = ($monthlyincome * 12) + ($quarterlyincome * 4) + ($biannualincome * 2) + $yearlyincome;
?>
<b><?php echo $company_totalincome ?> <?php echo $currencytype ?><font color="#FF0000"><?php echo $totalincome ?></b></font>
</td>
</tr>

<!-- ----------------------------------------------------- END PRINT TOTAL INCOME -------------------------------------------------------------- -->

</table>
</td>
</tr>
</table>

<?php

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
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
<head>
<LINK rel="stylesheet" type="text/css" href="../../templates/<?php echo $template ?>/styles.css">
</head>
<body>

<?php

if(mysql_num_rows($result)) {

// ------------------------------------------------------- IF VIEW ALL CLIENTS --------------------------------------------------------------

if ($showall){

include ("../../templates/$template/admin/clientmanager/viewallclients.inc");

// ---------------------------------------------------------- END IF VIEW ALL CLIENTS --------------------------------------------------------

// ---------------------------------------------------------- IF VIEW BY CLIENT ID ------------------------------------------------------------

} elseif ($id){

$sql = "SELECT * FROM clients WHERE id='$id'";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {

include ("$rootdirectory/admin/clientmanager/readclientdetails.inc");

$invoicedate =  strftime("$dateformat",$invoicedate);
$duedate =  strftime("$dateformat",$duedate);

include ("../../templates/$template/admin/clientmanager/viewclients.inc");

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $client_notfound ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

}

// ---------------------------------------------------------- END IF VIEW BY CLIENT ID ---------------------------------------------------------

// ------------------------------------------------------------ IF VIEW BY USERNAME ------------------------------------------------------------

} elseif ($username){

$sql = "SELECT * FROM clients WHERE username='$username'";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {
$myrow = mysql_fetch_array($result);
$id = $myrow["id"];

include ("$rootdirectory/admin/clientmanager/readclientdetails.inc");

$invoicedate =  strftime("$dateformat",$invoicedate);
$duedate =  strftime("$dateformat",$duedate);

include ("../../templates/$template/admin/clientmanager/viewclients.inc");

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $username_notfound ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

}

// ---------------------------------------------------------- END IF VIEW BY USERNAME -----------------------------------------------------------

// ------------------------------------------------------------ IF VIEW BY DOMAINNAME -----------------------------------------------------------

} elseif ($domainname){

$sql = "SELECT * FROM clients WHERE domainname='$domainname'";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {
$myrow = mysql_fetch_array($result);
$id = $myrow["id"];

include ("$rootdirectory/admin/clientmanager/readclientdetails.inc");

$invoicedate =  strftime("$dateformat",$invoicedate);
$duedate =  strftime("$dateformat",$duedate);

include ("../../templates/$template/admin/clientmanager/viewclients.inc");

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $domain_notfound ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

}

// ---------------------------------------------------------- END IF VIEW BY DOMAINNAME ---------------------------------------------------------

// ---------------------------------------------------------- PAGE FORMATTING STARTS HERE -------------------------------------------------------

} else {

include ("../../templates/$template/admin/clientmanager/searchclients.inc");

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
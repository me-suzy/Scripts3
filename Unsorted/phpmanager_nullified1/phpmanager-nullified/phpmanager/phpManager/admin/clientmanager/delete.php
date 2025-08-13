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

if ($delete) {

// --------------------------------------------------- IF YES IS CLICKED ---------------------------------------------------------------

if ($confirm){

$sql = "SELECT * FROM clients WHERE id=$id";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$surname = $myrow["surname"];
$firstname = $myrow["firstname"];
$username = $myrow["username"];
$email = $myrow["email"];

include ("../../emails/cancelaccount.inc");

$sql = "DELETE FROM clients WHERE id=$id";
$result = mysql_query($sql);

if ($cp=="WHM"){

include ("$rootdirectory/admin/host.php");

$url = "http://$hostusername:$hostpassword@$hostdomain:2086/scripts/killacct?user=$username&submit-user";
$lines_array = file($url);

}

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $client_deleted ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='viewclients.php'}</script>

</td>
</tr>
</table>

<?php

// --------------------------------------------------- END IF YES IS CLICKED ---------------------------------------------------------------

// --------------------------------------------------- IF NO IS CLICKED ---------------------------------------------------------------

} elseif ($cancel){

?>

<script>history.go(-2);</script>

// --------------------------------------------------- END IF NO IS CLICKED ---------------------------------------------------------------

<?php

} else {

// --------------------------------------------------- PAGE FORMATTING STARTS HERE ---------------------------------------------------------------

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">
<b><?php echo $client_confdel ?></b>
<br>
<br>
<button onclick="window.location='delete.php?id=<?php echo $id ?>&delete=yes&confirm=yes'" class="formfield"><?php echo $yes ?></button>
 
<button onclick="window.location='delete.php?id=<?php echo $id ?>&delete=yes&cancel=yes'" class="formfield"><?php echo $no ?></button>
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


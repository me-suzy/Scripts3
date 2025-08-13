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

if ($submit){

// --------------------------------------------------- IF NO SUBJECT OR NO MESSAGE -------------------------------------------------

if (!$subject || !$message){

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $fillfields ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

// --------------------------------------------------- END IF NO SUBJECT OR NO MESSAGE ----------------------------------------------

// --------------------------------------------------- IF SEND MESSAGE TO ALL CLIENTS ------------------------------------------------

} elseif ($allclients=="Yes"){

$result = mysql_query("SELECT * FROM clients",$db);
while ($myrow = mysql_fetch_array($result)) {
if ($myrow["email"]){ // EMAIL ALL CLIENTS
$email = $myrow["email"];
mail ($email, $subject, $message, "From: $admin_email");
} // END EMAIL ALL CLIENTS
}

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $client_email ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='message.php'}</script>

</td>
</tr>
</table>

<?php

// ------------------------------------------------------ IF END SEND MESSAGE TO ALL CLIENT ----------------------------------------------

// ------------------------------------------------------- IF SEND MESSAGE TO A CLIENT ---------------------------------------------------

} else {

mail ($email, $subject, $message, "From: $admin_email");

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $client_emailsent ?> <?php echo $email ?>.

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='message.php'}</script>

</td>
</tr>
</table>

<?php

}

} else {

// -------------------------------------------------- END IF SEND MESSAGE TO A CLIENT ---------------------------------------------------

// ------------------------------------------------- PAGE FORMATTING STARTS HERE --------------------------------------------------------

include ("../../templates/$template/admin/clientmanager/message.inc");

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
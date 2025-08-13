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

// --------------------------------------------------------- IF APPENDING TO MESSAGE --------------------------------------------------------------

if ($append && $ticketno){

include ("$rootdirectory/support/appendsupport.inc");

// ---------------------------------------------------------- END IF APPENDING TO MESSAGE ---------------------------------------------------------

// ---------------------------------------------------------- IF CLOSING TICKET -------------------------------------------------------------------

} elseif ($closed && $ticketno){

$sql = "SELECT * FROM support WHERE ticketno='$ticketno'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$subject = $myrow["subject"];
$email = $myrow["email"];
$date = $myrow["date"];
$username = $myrow["username"];
$url = $myrow["url"];
$olddescription = $myrow["description"];

$addnewdescription = "$olddescription
============================================================
$support_thankyou

$support_regards

$support_team
$company - Support";

$sql = "UPDATE support SET description=\"$addnewdescription\", status='Closed', admin='$logonname' WHERE ticketno='$ticketno'";
$result = mysql_query($sql);

include ("../../emails/closesupport.inc");

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $support_closed ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='support.php'}</script>

</td>
</tr>
</table>

<?php

// ------------------------------------------------------- END IF CLOSING TICKET -----------------------------------------------------------

// ------------------------------------------------------ IF VIEWING TICKET ---------------------------------------------------------------

} elseif ($ticketno){

$sql = "SELECT * FROM support WHERE ticketno='$ticketno'";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {

$myrow = mysql_fetch_array($result);
$date = $myrow["date"];
$status = $myrow["status"];
$username = $myrow["username"];
$admin = $myrow["admin"];
$subject = $myrow["subject"];
$description = $myrow["description"];
$email = $myrow["email"];
$url = $myrow["url"];

include("../../templates/$template/support/ticket.inc");

if ($status=="Closed"){

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="100" align="center" class="main">

<?php echo $support_closedmsg ?> 
<br>
<br>
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>

</td>
</tr>
</table>

<?php

} else {

?>

<form method="post" action="<?php echo $PHP_SELF?>?append=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="570" align="center">
		<input type="hidden" name="logonname" value="<?php echo $logonname ?>">
		<input type="hidden" name="logonid" value="<?php echo $logonid ?>">
		<input type="hidden" name="ticketno" value="<?php echo $ticketno ?>">
		<span class="summary_message">- <?php echo $support_description ?> -</span>
		<br>
		<textarea cols="95" rows="3" name="newdescription" class="formfield"></textarea>
		<br>
		</td>
	<tr>
		<td width="570" align="center">
		<input type="Submit" name="submit" value="<?php echo $add ?>" class="formfield">
		&nbsp;
		<button onclick="window.location='<?php echo $PHP_SELF ?>?ticketno=<?php echo $ticketno ?>&closed=yes'"
		class="formfield">Close Ticket</button>
		&nbsp;
		<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
		</td>
	</tr>
</table>
</form>

<?php

}

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $support_error ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

}

// ------------------------------------------------------ END IF VIEWING TICKET ------------------------------------------------------------

// -------------------------------------------------------- PAGE FORMATTING STARTS HERE ---------------------------------------------------

} else {

include ("../../templates/$template/support/searchticket.inc");

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
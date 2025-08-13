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

// ------------------------------------------------------- SEND PHPMANAGER A MESSAGE ---------------------------------------------------

} else {

mail ("info@phpmanager.com", $subject, $message, "From: $email");

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $email_submitted ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='main.php'}</script>

</td>
</tr>
</table>

<?php

}

} else {

// -------------------------------------------------- END SEND PHPMANAGER A MESSAGE ---------------------------------------------------

// ------------------------------------------------- PAGE FORMATTING STARTS HERE --------------------------------------------------------

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" align="center" valign="top">
<table width="570" border="0" cellspacing="0" cellpadding="2"style=" border: 1 solid #000000">
<tr>
<td width="570" align="center" class="summary_message">
- Send The phpManager Team A Message -
<br>
<br>
</td>
</tr>
<tr>
<td width="420" align="right" class="main">
<form method="post" action="<?php echo $PHP_SELF ?>">
Your Email Address: <input type="text" name="email" class="formfield">
<br>
Subject: <input type="text" name="subject" class="formfield">
<br>
<br>
</td>
</tr>
<tr>
<td width="570" align="center" class="main">
Message:
<br>
<textarea cols="70" rows="13" name="message" class="formfield"></textarea>
</td>
</tr>
<tr>
<td width="570" align="center" class="main">
<input type="Submit" name="submit" value="Send" class="formfield">
</td>
</tr>
</table>
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
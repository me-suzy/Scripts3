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

$sql = "SELECT * FROM staff WHERE adminname='$adminname'";
$result = mysql_query($sql);

if(mysql_num_rows($result)) {

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

} else {

$encryptedpassword = md5($password);

$sql = "INSERT INTO staff (adminname,access,password,language,template) VALUES ('$adminname','$new_access','$encryptedpassword','$default_language','$default_template')";

if ($adminname && $access && $password){ // CHECKS TO SEE IF REQUIRED FIELDS ARE FILLED IN

$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $staff_added ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='../main.php'}</script>

</td>
</tr>
</table>

<?php

} else { // IF FIELDS ARE NOT FILLED IN

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $staff_notadded ?>

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

include ("../../templates/$template/admin/staffmanager/addstaff.inc");

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
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

if ($submit){

$sql = "UPDATE staff SET language='$new_language', template='$new_template' WHERE adminname='$logonname'";
$result = mysql_query($sql);

}

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
<title><?php echo $clientmanager_title ?></title>
<LINK rel="stylesheet" type="text/css" href="../templates/<?php echo $template ?>/styles.css">
</head>

<body>

<?php

if(mysql_num_rows($result)) { // VERIFY THE STAFF TYPE

include ("../templates/$template/admin/header.inc");

if ($access=="Superadmin"){ // IF SUPERADMIN

include ("../templates/$template/admin/superadmin_menu.inc");

include ("../templates/$template/admin/main.inc");

} elseif ($access=="Admin") { // IF ADMIN

include ("../templates/$template/admin/admin_menu.inc");

include ("../templates/$template/admin/main.inc");

} elseif ($access=="Support") { // IF SUPPORT

include ("../templates/$template/admin/support_menu.inc");

include ("../templates/$template/admin/support_main.inc");

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








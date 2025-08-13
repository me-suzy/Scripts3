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

setcookie ("id", "");
setcookie ("clientname", "");
setcookie ("clientid", "");
setcookie ("cpid", "");

setcookie ("clientname", $username);
setcookie ("clientid", md5($password));
setcookie ("cpid", $password);

include ("../variables.php");
include ("$rootdirectory/languages/$language");

?>

<html>
<head>
<title><?php echo $accountmanagerlogin_title ?></title>
<LINK rel="stylesheet" type="text/css" href="../templates/<?php echo $template ?>/styles.css">
</head>
<body>

<?php

if ($submit){

// -------------------------------------------------- IF NO USERNAME AND NO PASSWORD -----------------------------------------------------

if (!$username && !$password){

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $login_userpass ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>";

// ---------------------------------------------------------- END IF NO USERNAME AND NO PASSWORD --------------------------------------------

// -------------------------------------------------------------- IF NO PASSWORD -----------------------------------------------------------

} elseif ($username && !$password){

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $login_pass ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>";

// ------------------------------------------------------------ END IF NO PASSWORD ----------------------------------------------------------

// ------------------------------------------------------------- IF NO USERNAME ------------------------------------------------------------

} elseif (!$username && $password){

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $login_user ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>";

// ------------------------------------------------------------ END IF NO USERNAME ---------------------------------------------------------

// ----------------------------------------------------------- VERIFY CLIENT -------------------------------------------------------
} else {
$secureuser = md5($username);
$encryptedpassword = md5($password);
$sql = "SELECT * FROM clients WHERE username='$username' AND password='$encryptedpassword'";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {
// ------------------------------------------------------------- IF CLIENT IS VERIFIED ----------------------------------------------------
$myrow = mysql_fetch_array($result);
$id = $myrow["id"];
$firstname = $myrow["firstname"];
$surname = $myrow["surname"];
$sql = "SELECT * FROM staff WHERE id='1'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$access = $myrow["access"];
?>
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">
<?php echo $login_thankyou ?> <?php echo $firstname ?> <?php echo $surname ?>.
</td>
</tr>
</table>
<?php
if ($access=="breach"){
echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='http://www.yahoo.com';}</script>";
} else {
echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='main.php?id=$id';}</script>";
}
// -------------------------------------------------------- END IF CLIENT IS VERIFIED -----------------------------------------------------
// ------------------------------------------------------------- IF CLIENT IS NOT VERIFIED -----------------------------------------------
} else {
?>
<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">
<?php echo $login_error ?>
</td>
</tr>
</table>
<?php
echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>";
}
}
} else {
// ----------------------------------------------------------- END IF CLIENT IS NOT VERIFIED -----------------------------------------------

// ------------------------------------------------------------ PAGE FORMATTING STARTS HERE ------------------------------------------------

include ("../templates/$template/clients/clientlogin.inc");

}

?>

</body>
</html>

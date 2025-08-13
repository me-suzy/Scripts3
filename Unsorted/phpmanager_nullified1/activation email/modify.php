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

if ($invoicedate){

$sql = "SELECT * FROM clients WHERE id='$id'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$invoicedate = $myrow["invoicedate"];

if ($submit){

$date_time_array =  getdate($invoicedate);
$hours =  $date_time_array["hours"];
$minutes =  $date_time_array["minutes"];
$seconds =  $date_time_array["seconds"];
$month =  $date_time_array["mon"];
$day =  $date_time_array["mday"];
$year =  $date_time_array["year"];
$invoicedate = mktime($hours, $minutes,$seconds ,$month+$months, $day+$days,$year+$years);
$sql = "UPDATE clients SET invoicedate='$invoicedate' WHERE id=$id";
$result = mysql_query($sql);

echo "<script>window.setTimeout('changeurl();',100); function changeurl(){window.location='viewclients.php?id=$id'}</script>";

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center">
<form method="post" action="<?php echo $PHP_SELF?>?invoicedate=yes">
<table width="300" align="center" border="0" cellspacing="2" cellpadding="0" style="border: 1 solid #000000">
	<tr>
		<td width="300" align="center" class="main_bold">
		<input type="hidden" name="id" class="formfield" value="<?php echo $id ?>">
		Old Date: <input type="text" class="formfield" value="<?php echo strftime("%d/%m/%y",$invoicedate) ?>" size="10" disabled>
		<br><br>
		Enter Values To Add:
		<br>
		Days: <input type="text" size="2" name="days" class="formfield">
		&nbsp;
		Months: <input type="text" size="2" name="months" class="formfield">
		&nbsp;
		Years: <input type="text" size="2" name="years" class="formfield">
		<br>
		<br>
		</td>
	</tr>
	<tr>
		<td width="300" align="center">
		<input type="Submit" name="submit" value="Change" class="formfield">
		</td>
	</tr>
</table>
</form>
</td>
</tr>
</table>

<?php

}

} elseif ($duedate){

$sql = "SELECT * FROM clients WHERE id='$id'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$duedate = $myrow["duedate"];

if ($submit){

$date_time_array =  getdate($duedate);
$hours =  $date_time_array["hours"];
$minutes =  $date_time_array["minutes"];
$seconds =  $date_time_array["seconds"];
$month =  $date_time_array["mon"];
$day =  $date_time_array["mday"];
$year =  $date_time_array["year"];
$duedate = mktime($hours, $minutes,$seconds ,$month+$months, $day+$days,$year+$years);
$sql = "UPDATE clients SET duedate='$duedate' WHERE id=$id";
$result = mysql_query($sql);

echo "<script>window.setTimeout('changeurl();',100); function changeurl(){window.location='viewclients.php?id=$id'}</script>";

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center">
<form method="post" action="<?php echo $PHP_SELF?>?duedate=yes">
<table width="300" align="center" border="0" cellspacing="2" cellpadding="0" style="border: 1 solid #000000">
	<tr>
		<td width="300" align="center" class="main_bold">
		<input type="hidden" name="id" class="formfield" value="<?php echo $id ?>">
		Old Date: <input type="text" class="formfield" value="<?php echo strftime("%d/%m/%y",$duedate) ?>" size="10" disabled>
		<br><br>
		Enter Values To Add:
		<br>
		Days: <input type="text" size="2" name="days" class="formfield">
		&nbsp;
		Months: <input type="text" size="2" name="months" class="formfield">
		&nbsp;
		Years: <input type="text" size="2" name="years" class="formfield">
		<br>
		<br>
		</td>
	</tr>
	<tr>
		<td width="300" align="center">
		<input type="Submit" name="submit" value="Change" class="formfield">
		</td>
	</tr>
</table>
</form>
</td>
</tr>
</table>

<?php

}

// ----------------------------------------------------- IF MODIFYING CLIENT PASSWORD ---------------------------------------------------

} elseif ($password){

$encryptedpassword = md5($password);

$sql = "UPDATE clients SET password='$encryptedpassword' WHERE id=$id";
$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $client_modifypass ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='viewclients.php?id=$id'}</script>";

// ------------------------------------------------------ END IF MODIFYING CLIENT PASSWORD -----------------------------------------------

// ---------------------------------------------------- IF MODIFYING CLIENT DETAILS ------------------------------------------------------

} elseif ($activationemail){

$sql = "SELECT * FROM clients WHERE id='$id'";
$result = mysql_query($sql);

include ("$rootdirectory/admin/clientmanager/readclientdetails.inc");

$ip = $ipaddress;

$password = $activationemail_password;

include ("../../emails/activation.inc");

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $activationemail_sent ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='viewclients.php?id=$id'}</script>";

} elseif ($submit) {

$sql = "SELECT * FROM clients WHERE username='$username'";
$result = mysql_query($sql);

if(mysql_num_rows($result)) {

$myrow = mysql_fetch_array($result);

if ($id==$myrow["id"]){

$sql = "UPDATE clients SET surname='$surname', firstname='$firstname',telephone='$telephone',email='$email',icq='$icq',referrer='$referrer',
house='$house', street='$street', town='$town', state='$state', country='$country', postcode='$postcode', domainname='$domainname', domain='$domain', package='$package',
schedule='$schedule', payment='$payment', username='$username', ipaddress='$ipaddress', startdate='$startdate', amount='$amount', status='$status', notes='$notes' WHERE id=$id";

$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $client_edited ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='viewclients.php?id=$id'}</script>";

// ------------------------------------------------------- END IF MODIFYING CLIENT DETAILS -----------------------------------------------

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $user_taken ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>";

}

} else {

$sql = "UPDATE clients SET surname='$surname', firstname='$firstname',telephone='$telephone',email='$email',icq='$icq',referrer='$referrer',
house='$house', street='$street', town='$town', state='$state', country='$country', postcode='$postcode', domainname='$domainname', domain='$domain', package='$package',
schedule='$schedule', payment='$payment', username='$username', ipaddress='$ipaddress', startdate='$startdate', amount='$amount', status='$status', notes='$notes' WHERE id=$id";
$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $client_edited ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='viewclients.php?id=$id'}</script>";

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
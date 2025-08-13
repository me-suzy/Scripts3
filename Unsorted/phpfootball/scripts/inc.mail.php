<?php 
/*
***************************************************************************
Parameters :

$Recipient
$Poster
$Headline
$Content
$Date
$send_mail
***************************************************************************
*/
?>

<?php include("inc.functions.php"); ?>

<?php
//if $Recipient is given get Contact_email mail adress
if ($Recipient){
$query = "SELECT Contact_email FROM Accounts WHERE Username = '$Recipient'";
$result = mysql_query($query) or die ("died while geting email<br>Debug info: $query");
$row = mysql_fetch_array($result);
$to_mail = $row["Contact_email"];
}

//if $Poster is given get Contact_email mail adress
if ($Poster){
$query = "SELECT Contact_email FROM Accounts WHERE Username = '$Poster'";
$result = mysql_query($query) or die ("died while geting email<br>Debug info: $query");
$row = mysql_fetch_array($result);
$from_mail = $row["Contact_email"];
}

//if emal is set send email notice
if ($send_mail == "yes" && $Recipient && $Poster && $Headline && $Content && $to_mail && $from_mail) {
    //make sure we have a admin sending
    if (in_array("$userlev", $admins)) { } else {
    echo "<font color=red><b>You are not allowed to use this script</b></font>";
    die;
    }
	$domain = $_SERVER['HTTP_HOST'];
	$to_name  = rem_crlf($Recipient);
	$to_mail  = rem_crlf($to_mail);
	$from_name= rem_crlf($Poster);
	$from_mail= rem_crlf($from_mail);
	$subject = "$Headline";
	$message = "$Content\nThis news notice was sent on $Date by an admin of PHPFootball $versionsrc on $domain";
	$headers = "From: \"$from_name\" <$from_mail>\nX-Mailer: PHPmail()";
	mail($to_mail, $subject, $message, $headers);
echo "<b>E-Mail sent</b>";
}
?>

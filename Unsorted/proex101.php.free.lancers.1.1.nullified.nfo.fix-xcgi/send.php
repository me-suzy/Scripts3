<?php
/*
                                                   PHP Freelancers version 1.1
                                                   -----------------------
                                                   A script by ProEx101 Web Services
                                                   (http://www.ProEx101.com/)

    "PHP Freelancers" is not a free script. If you got this from someplace
    other than SmartCGIs.com or ProEx101 Web Services, please contact us,
    we do offer rewards for that type of information. Visit our site for up
    to date versions. Most PHP scripts are over $300, sometimes more than
    $700, this script is much less. We can keep this script cheap, as well
    as free scripts on our site, if people don't steal it.
          Also, no return links are required, but we appreciate it if you
          do find a spot for us.
          Thanks!

          Special Notice to Resellers
          ===========================
          Reselling this script without prior permission
          from ProEx101 Web Services is illegal and
          violators will be prosecuted to the fullest
          extent of the law.  To apply to be a legal
          reseller, please visit:
          http://www.ProEx101.com/freelancers/resell.php

       (c) copyright 2001 ProEx101 Web Services, SmartCGIs.com, and R3N3 Internet Services */

require "vars.php";
require "cron.php";

$hugeres = SQLact("query", "SELECT * FROM freelancers_bans WHERE ip='" . $REMOTE_ADDR . "'");
$hugerows = SQLact("num_rows", $hugeres);
if ($hugerows==0) {
if (!$submit) {
echo $toplayout;
$user = $HTTP_COOKIE_VARS["username"];
$pass = $HTTP_COOKIE_VARS["password"];
?>
<form method="POST" action="send.php">

<big><b>Send A Private Message</b></big><br>
You can send a message to another user and they will be given instructions on how to reply to your message.
<p>
<strong>Your Username:</strong><br>
<input type="text" name="username" value="<? echo $user; ?>" size="15">
<br>
<small>(Your Account Type)</small><br>
<select name="acctype">
<option value="<? echo $buyer; ?>"> <? echo $buyer; ?></option>
<option value="<? echo $freelancer; ?>"> <? echo $freelancer; ?></option>
</select>
<br>
<strong>Your Password:</strong><br>
<input type="password" name="password" value="<? echo $pass; ?>" size="15">
<p>

<strong>Send To:</strong><br>
<small>(Recipient's Username)</small><br>
<input type="text" name="to" value="<? echo $to; ?>" size="15">
<br>
<small>(Recipient's Account Type)</small><br>
<select name="acctype2">
<option value="<? echo $buyer; ?>"> <? echo $buyer; ?></option>
<option value="<? echo $freelancer; ?>"> <? echo $freelancer; ?></option>
</select>
<p>
<strong>Subject:</strong><br>
<input type="text" name="subject" value="<? echo $subject; ?>" size="40">
<p>
<strong>Message:</strong><br>
<textarea rows="8" name="message" cols="52"></textarea>
<p>
<input type="submit" value="Send Message" name="submit">
</form>
<?php
} else {
if ($username == "" || $password == "" || $to == "" || $subject == "" || $message == "") {
echo $toplayout;
echo 'ERROR: Your message was not sent because you left 1 or more fields blank.<br>
<A HREF="javascript:history.back()">Go Back</A>';
} else {
if ($acctype == $buyer) {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $buyer . ' account was found with the username <b>' . $username . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$byzzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username' AND password='$password'");
$byzzyrows = SQLact("num_rows", $byzzyres);
if ($byzzyrows==0) {
echo $toplayout;
echo 'The password you provided is incorrect. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
setcookie ("busername", $username,time()+999999);
setcookie ("bpassword", $password,time()+999999);
echo $toplayout;
if ($acctype2 == $buyer) {
$bizzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$to'");
$bizzyrows = SQLact("num_rows", $bizzyres);
if ($bizzyrows==0) {
echo 'No ' . $buyer . ' account was found with the username <b>' . $to . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$to2 = SQLact("result", $bizzyres,0,"email");
$subject2 = str_replace(" ", "+", $subject);
$message2 = 'This is a message sent from ' . $companyname . ' by the ' . $buyer . ' account ' . $username . '. Do not reply by e-mail, go to the following URL if you want to respond: ' . $siteurl . '/send.php?to=' . $username . '&subject=RE:' . $subject2 . '
--------------------------------------------

' . $message;
mail($to2,$companyname . " Message (" . $subject . ")",$message2,"From: $username <$emailaddress>");
$msg6 = 'This is a message sent from the ' . $buyer . ' "' . $username . '" to the ' . $buyer. ' "' . $to . '".
--------------------------------------------

' . $message;
mail($emailaddress,"Message Notice (" . $subject . ")",$msg6,"From: $username <$emailaddress>");
echo 'Done. Your message has been sent to <b>' . $to .'</b>.';
}
} else {
$bizzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$to'");
$bizzyrows = SQLact("num_rows", $bizzyres);
if ($bizzyrows==0) {
echo 'No ' . $freelancer . ' account was found with the username <b>' . $to . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$to2 = SQLact("result", $bizzyres,0,"email");
$subject2 = str_replace(" ", "+", $subject);
$message2 = 'This is a message sent from ' . $companyname . ' by the ' . $buyer . ' account ' . $username . '. Do not reply by e-mail, go to the following URL if you want to respond: ' . $siteurl . '/send.php?to=' . $username . '&subject=RE:' . $subject2 . '
--------------------------------------------

' . $message;
mail($to2,$companyname . " Message (" . $subject . ")",$message2,"From: $username <$emailaddress>");
$msg6 = 'This is a message sent from the ' . $buyer . ' "' . $username . '" to the ' . $freelancer. ' "' . $to . '".
--------------------------------------------

' . $message;
mail($emailaddress,"Message Notice (" . $subject . ")",$msg6,"From: $username <$emailaddress>");
echo 'Done. Your message has been sent to <b>' . $to .'</b>.';
}
}
}
}
} else {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $freelancer . ' account was found with the username <b>' . $username . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$byzzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username' AND password='$password'");
$byzzyrows = SQLact("num_rows", $byzzyres);
if ($byzzyrows==0) {
echo $toplayout;
echo 'The password you provided is incorrect. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
setcookie ("fusername", $username,time()+999999);
setcookie ("fpassword", $password,time()+999999);
echo $toplayout;
if ($acctype2 == $buyer) {
$bizzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$to'");
$bizzyrows = SQLact("num_rows", $bizzyres);
if ($bizzyrows==0) {
echo 'No ' . $buyer . ' account was found with the username <b>' . $to . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$to2 = SQLact("result", $bizzyres,0,"email");
$subject2 = str_replace(" ", "+", $subject);
$message2 = 'This is a message sent from ' . $companyname . ' by the ' . $freelancer . ' account ' . $username . '. Do not reply by e-mail, go to the following URL if you want to respond: ' . $siteurl . '/send.php?to=' . $username . '&subject=RE:' . $subject2 . '
--------------------------------------------

' . $message;
mail($to2,$companyname . " Message (" . $subject . ")",$message2,"From: $username <$emailaddress>");
$msg6 = 'This is a message sent from the ' . $freelancer . ' "' . $username . '" to the ' . $buyer. ' "' . $to . '".
--------------------------------------------

' . $message;
mail($emailaddress,"Message Notice (" . $subject . ")",$msg6,"From: $username <$emailaddress>");
echo 'Done. Your message has been sent to <b>' . $to .'</b>.';
}
} else {
$bizzyres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$to'");
$bizzyrows = SQLact("num_rows", $bizzyres);
if ($bizzyrows==0) {
echo 'No ' . $freelancer . ' account was found with the username <b>' . $to . '</b>. <br><A HREF="javascript:history.back()">Go Back</A>';
} else {
$to2 = SQLact("result", $bizzyres,0,"email");
$subject2 = str_replace(" ", "+", $subject);
$message2 = 'This is a message sent from ' . $companyname . ' by the ' . $freelancer . ' account ' . $username . '. Do not reply by e-mail, go to the following URL if you want to respond: ' . $siteurl . '/send.php?to=' . $username . '&subject=RE:' . $subject2 . '
--------------------------------------------

' . $message;
mail($to2,$companyname . " Message (" . $subject . ")",$message2,"From: $username <$emailaddress>");
$msg6 = 'This is a message sent from the ' . $freelancer . ' "' . $username . '" to the ' . $freelancer. ' "' . $to . '".
--------------------------------------------

' . $message;
mail($emailaddress,"Message Notice (" . $subject . ")",$msg6,"From: $username <$emailaddress>");
echo 'Done. Your message has been sent to <b>' . $to .'</b>.';
}
}
}
}
}
}
}
echo $bottomlayout;
} else {
echo 'You have been banned from using ' . $companyname . '!<br>
You do not have access to any services used in this section of the website!<br>
The reason for your ban is:<br>
<b><i>';
$humanres = SQLact("query", "SELECT * FROM freelancers_bans WHERE ip='" . $REMOTE_ADDR . "'");
$rrreason = SQLact("result", $humanres,0,"reason");
if ($rrreason == "") {
echo 'No reason was given for your ban.';
} else {
echo $rrreason;
}
echo '</i></b><br><br>
If you think there has been a mistake, contact ' . $emailaddress;
}
?>
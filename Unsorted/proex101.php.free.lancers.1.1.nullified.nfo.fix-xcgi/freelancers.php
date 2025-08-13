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

function roundit($number,$decimals) {
return(sprintf("%01." . $decimals . "f", $number));
}
$hugeres = SQLact("query", "SELECT * FROM freelancers_bans WHERE ip='" . $REMOTE_ADDR . "'");
$hugerows = SQLact("num_rows", $hugeres);
if ($hugerows==0) {
if ($new == "user") {
echo $toplayout;
if (!$submit) {
?>
<form method="POST" action="freelancers.php">
<input type="hidden" name="new" value="user">

<big><b>New <? echo $freelancer; ?> Signup (Step 1)</b></big>
<p>

<strong>E-mail Address:</strong><br>
<input type="text" name="email" size="25">
<br><small>(<a href="<? echo $siteurl; ?>/privacy.html">Privacy Policy</a>)</small>
<p>
<small>Please provide a valid e-mail address, you will have to confirm it before finishing the signup process.</small><br>
<input type="submit" value="Next" name="submit">
</form>
<?php
} else {
if ($email == "" || !eregi("@", $email) || !eregi(".", $email)) {
echo 'Please enter a valid e-mail address!<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
$email = strtolower($email);
$number = $count+1;
SQLact("query", "UPDATE freelancers_count SET count='" . $number . "'");
SQLact("query", "INSERT INTO freelancers_temp (email, id) VALUES ('" . $email . "', '" . $number . "')");
$subject = 'Confirm E-mail for ' . $companyname;
$message = $emailheader . '
----------
Go to the following URL to continue the signup process at ' . $companyname . ':

' . $siteurl . '/freelancers.php?confirm=' . $number . '
----------
' . $emailfooter;
mail($email,$subject,$message,"From: $emailaddress");
echo 'A confirmation e-mail will be sent to <b>' . $email . '</b> soon, please follow the link inside to continue the signup process.';
}
}
} else if ($confirm && $confirm !== "") {
echo $toplayout;
$selectid = SQLact("query", "SELECT * FROM freelancers_temp WHERE id='" . $confirm . "'");
$idcheck = SQLact("num_rows", $selectid);
if ($idcheck!==0) {
$email = SQLact("result", $selectid,0,"email");
?>
<form method="POST" action="freelancers.php" ENCTYPE="multipart/form-data">
<input type="hidden" name="new" value="user2">
<input type="hidden" name="id" value="<? echo $confirm; ?>">
<input type="hidden" name="email" value="<? echo $email; ?>">

<big><b>New <? echo $freelancer; ?> Signup (Step 2)</b></big>

<p>
<strong>Pick a Username:</strong><br>
<input type="text" name="username" size="15">
<p>
<strong>Pick a Password:</strong><br>
<input type="password" name="password" size="15">
<p>
<strong>Name/Company:</strong><br>
<input type="text" name="name" size="20">
<p>
<b>Area of Expertise:</b><br>
<?php
if ($multiplecats == "enabled") {
?>
<small>(You can make multiple selections.)</small><br>
<?php
$selectcats = SQLact("query", "SELECT * FROM freelancers_cats");
while ($row=SQLact("fetch_array", $selectcats)) {
?>
<input type="checkbox" name="category[]" value="<? echo $row[categories]; ?>"> <? echo $row[categories]; ?><br>
<?php
}
} else {
$selectcats = SQLact("query", "SELECT * FROM freelancers_cats");
while ($row=SQLact("fetch_array", $selectcats)) {
?>
<input type="radio" name="category[]" value="<? echo $row[categories]; ?>"> <? echo $row[categories]; ?><br>
<?php
}
}
?>
<p>
<b>Your average hourly rate:</b><br>
<? echo $currencytype . '' . $currency; ?><input type="text" name="rate" maxlength="3" size="3">/hour
<p>

<strong>Your profile (optional):</strong>
<br>
<textarea rows="8" name="profile" cols="52"></textarea>
<p>
<input type="checkbox" name="notify" value="notify"> Notify me by e-mail when a project gets posted that is relevant to me.
<p>
<small>By pressing this button you acknowledge that you have read and agree to the <a href="<? echo $siteurl; ?>/terms.html">Terms & Conditions</a>.</small><br>
<input type="submit" value="Signup" name="submit">
<p>
</form>
<?php
} else {
echo 'ERROR: You have followed an incorrect confirmation link, or this confirmation link has already been used!
<a href="' . $siteurl . '/freelancers.php?signup=now">Go Back...</a>';
}
} else if ($new == "user2") {
if (!$submit) {
echo $toplayout;
echo 'ERROR: There was an error while trying to process this document because it was not accessed accordingly through the proper form.<br>
<a href="' . $siteurl . '/freelancers.php?signup=now">Go Back...</a>';
} else {
if (!$username || $username == "" || !$password || $password == "" || !$name || $name == "" || !$rate || $rate == "") {
echo $toplayout;
echo 'ERROR: Your form could not be processed!  1 or more required fields/boxes were left blank or this page was not accessed appropriately through the correct form.
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
$username = strtolower($username);
$password = strtolower($password);
$myresult = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $username . "'");
$num_rows100 = SQLact("num_rows", $myresult);
if ($num_rows100==0) {
setcookie ("fusername", $username,time()+999999);
setcookie ("fpassword", $password,time()+999999);
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
$FReferrer = $HTTP_COOKIE_VARS["FReferrer"];
$BReferrer = $HTTP_COOKIE_VARS["BReferrer"];
if ($FReferrer == "" && $BReferrer == "") {} else {
if ($FReferrer == "") {
$tyress = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $BReferrer . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$bal = SQLact("result", $tyress,0,"balance");
setcookie ("BReferer", "", time() - 999999);
$dadj2 = $bal+$frefamount;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+$frefamount', '$freelancer Referal ($username)', '$BReferrer', 'buyer', '$dadj2', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
} else if ($BReferrer == "") {
$tyress = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $FReferrer . "' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$bal = SQLact("result", $tyress,0,"balance");
setcookie ("FReferer", "", time() - 999999);
$dadj2 = $bal+$frefamount;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+$frefamount', '$freelancer Referal ($username)', '$FReferrer', 'freelancer', '$dadj2', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
}
}
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+$fsbamount', 'Account Signup Bonus', '$username', 'freelancer', '$fsbamount', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
SQLact("query", "DELETE FROM freelancers_temp WHERE id='" . $id . "' AND email='" . $email . "'");
foreach($category as $key=>$val) {
$myvar .= $val . " ";
}
SQLact("query", "INSERT INTO freelancers_programmers (username, password, company, categories, rate, profile, notify, id, email, ip) VALUES ('" . $username . "', '" . $password . "', '" . $name . "', '" . $myvar . "', '" . $rate . "', '" . $profile . "', '" . $notify . "', '" . $id . "', '" . $email . "', '" . $REMOTE_ADDR . "')");
$subject = 'New ' . $companyname . ' ' . $freelancer . ' Account';
$message = $emailheader . '
----------
Thanks for creating an account at ' . $companyname . '.
Your username: ' . $username . '
Your password: ' . $password . '

Keep this e-mail or write down your login and password. You will need this info
to login and bid on projects at ' . $siteurl . '/freelancers.php?login=now

Thank You
----------
' . $emailfooter;
mail($email,$subject,$message,"From: $emailaddress");
echo $toplayout;
echo '<center><b>Your account has been created successfully!</b>
<p>
Your username: ' . $username . '<br>
Your password: ' . $password . '
</center>';
} else {
echo $toplayout;
echo 'ERROR: That ' . $freelancer . ' username already exists.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
}
}
}
} else if ($login == "now") {
$username = $HTTP_COOKIE_VARS["fusername"];
$password = $HTTP_COOKIE_VARS["fpassword"];
if ($username == "" || $password == "") {
echo $toplayout;
if ($error==1) {
echo 'You have entered a username that couldn\'t be located in our databases, please double-check your spelling, click on the forgot username link, or make sure you even have an account.<br>';
} else if ($error==2) {
echo 'You have entered an incorrect or invalid password, please double-check your password, or click on the forgot password link if you forgot it.<br>';
} else if ($error==3) {
echo 'You have entered incorrect or invalid login information that couldn\'t be located in our databases.  However, a ' . $buyer . ' account was found with the username you entered, <a href="' . $siteurl . '/buyers.php?login=now&username=' . $username . '">click here</a> to login through the ' . $buyer . ' login form.<br>';
}
?>
<br><b>Login to Account Management Area for <? echo $freelancers; ?></b>
<p>
<form method="POST" action="freelancers.php">
<input type="hidden" name="manage" value="1">
<strong>Username:</strong><br>
<small>(<a href="<? echo $siteurl; ?>/freelancers.php?forgot=find">Click here</a> if you forgot it.)</small>
<br>
<input type="text" name="username" value="<?php
if (!$username) {} else {
echo $username;
}
?>" size="15">
<br>
<strong>Password:</strong><br>
<small>(<a href="<? echo $siteurl; ?>/freelancers.php?forgot=find">Click here</a> if you forgot it.)</small>
<br>
<input type="password" name="password" size="15">
<p>
<b>NOTE: Make sure your browser is set to accept cookies.  If your browsers functionality of cookies involves privacy/security levels (such as Internet Explorer), set the privacy/security level to "Medium" (if this DOES NOT work, set the privacy/security level to "Low").</b><br>
<b>Otherwise you won't be able to access any login-based pages in the account management area.</b>
<p>
<br>
<input type="submit" value="Login" name="submit">
</form>
<p>
<a href="<? echo $siteurl; ?>/freelancers.php?new=user">Signup</a>
<?php
} else {
header("Location: " . $siteurl . "/freelancers.php?manage=1&username=" . $username . "&password=" . $password);
}
} else if ($forgot == "find") {
echo $toplayout;
if (!$submit) {
?>
<form method="POST" action="freelancers.php">
<input type="hidden" name="forgot" value="find">
<input type="hidden" name="hide" value="1">
<strong>Forgot your username?</strong><br>
Enter your e-mail address: <input type="text" name="forgotu" size="20">
<input type="submit" value="Find Username" name="submit">
</form>
<p>
<form method="POST" action="freelancers.php">
<input type="hidden" name="forgot" value="find">
<input type="hidden" name="hide" value="2">
<strong>Forgot your password?</strong><br>
Enter your username: <input type="text" name="forgotp" size="15">
<input type="submit" value="Find Password" name="submit">
</form>
<?php
} else {
if ($hide==1) {
$forgotu = strtolower($forgotu);
if ($forgotu == "") {
echo 'ERROR: You have left the email address field empty.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
$result = SQLact("query", "SELECT * FROM freelancers_programmers WHERE email='" . $forgotu . "'");
$num_rows = SQLact("num_rows", $result);
if ($num_rows==0) {
echo 'ERROR: No ' . $freelancer . ' account was found with that e-mail address.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
$username = SQLact("result", $result,0,"username");
echo 'Your ' . $freelancer . ' username is <b>' . $username . '</b>. <a href="' . $siteurl . '/freelancers.php?login=now">Click here to login...</a>';
}
}
} else {
$forgotp = strtolower($forgotp);
if ($forgotp == "") {
echo 'ERROR: You have left the username field empty.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
$result = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $forgotp . "'");
$num_rows = SQLact("num_rows", $result);
if ($num_rows==0) {
echo 'ERROR: No ' . $freelancer . ' account was found with that username.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
$email = SQLact("result", $result,0,"email");
$password = SQLact("result", $result,0,"password");
$subject = "Your " . $companyname . " Password";
$message = $emailheader . "
----------
Your password is " . $password . "
----------
" . $emailfooter;
mail($email,$subject,$message,"From: $emailaddress");
echo 'Your password will be e-mailed to you within the next few hours.';
}
}
}
}
} else if ($manage == "1") {
if ($username == "" || $password == "" || !$username || !$password) {
header("Location: " . $siteurl . "/freelancers.php?login=now");
} else {
$username = strtolower($username);
$password = strtolower($password);
$result = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $username . "'");
$num_rows = SQLact("num_rows", $result);
if ($num_rows==0) {
$result1 = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $username . "'");
$num_rows1 = SQLact("num_rows", $result1);
if ($num_rows1==0) {
header("Location: " . $siteurl . "/freelancers.php?login=now&error=1");
} else {
setcookie ("fusername", $username,time()+999999);
header("Location: " . $siteurl . "/freelancers.php?login=now&error=3&username=" . $username);
}
} else {
$result2 = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $username . "' AND password='" . $password . "'");
$num_rows2 = SQLact("num_rows", $result2);
if ($num_rows2==0) {
setcookie ("fusername", $username,time()+999999);
header("Location: " . $siteurl . "/freelancers.php?login=now&error=2&username=" . $username);
} else {
setcookie ("fusername", $username,time()+999999);
setcookie ("fpassword", $password,time()+999999);
header("Location: " . $siteurl . "/freelancers.php?manage=2");
}
}
}
} else if ($manage == "2") {
$username = $HTTP_COOKIE_VARS["fusername"];
$password = $HTTP_COOKIE_VARS["fpassword"];
if ($username == "" || $password == "") {
header("Location: " . $siteurl . "/freelancers.php?login=now");
} else {
$result = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $username . "'");
$num_rows = SQLact("num_rows", $result);
if ($num_rows==0) {
$result1 = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $username . "'");
$num_rows1 = SQLact("num_rows", $result1);
if ($num_rows1==0) {
header("Location: " . $siteurl . "/freelancers.php?login=now&error=1");
} else {
header("Location: " . $siteurl . "/freelancers.php?login=now&error=3&username=" . $username);
}
} else {
$result2 = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $username . "' AND password='" . $password . "'");
$num_rows2 = SQLact("num_rows", $result2);
if ($num_rows2==0) {
header("Location: " . $siteurl . "/freelancers.php?login=now&error=2&username=" . $username);
} else {
$ldi = SQLact("query", "SELECT * FROM freelancers_logins WHERE username='$username' AND atype='freelancer'");
$tme = time();
if (SQLact("num_rows", $ldi)==0) {
$theabsoluteresss2 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$nbalance2 = SQLact("result", $theabsoluteresss2,0,"balance");
SQLact("query", "INSERT INTO freelancers_logins (username, date, atype) VALUES ('$username', '$tme', 'freelancer')");
} else {
SQLact("query", "UPDATE freelancers_logins SET date='$tme' WHERE username='$username' AND atype='freelancer'");
}
$ann = SQLact("query", "SELECT * FROM freelancers_announcements");
if (SQLact("result", $ann,0,"announce") !== "") {
$dtlt = getdate(SQLact("result", $ann,0,"date"));
$mlt = $dtlt["mon"];
$dlt = $dtlt["mday"];
$ylt = $dtlt["year"];
$hlt = $dtlt["hours"];
$m2lt = $dtlt["minutes"];
$dtlt2 = "$mlt-$dlt-$ylt @ $hlt:$m2lt";
$tlt = '<center><b>' . $dtlt2 . '</b><br>
' . SQLact("result", $ann,0,"announce") . '</center><br><br>';
$toplayout = $toplayout . '
' . $tlt;
}
if ($edit == "info") {
if (!$submit) {
echo $toplayout;
} else {
foreach($category as $key=>$val) {
$myvar .= $val;
}
setcookie ("fpassword", "", time() - 999999);
setcookie ("fpassword", $newpassword,time()+999999);
echo $toplayout;
$password = $newpassword;
$theaptres2 = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $username . "'");
$nemail2 = SQLact("result", $theaptres2,0,"email");
SQLact("query", "UPDATE freelancers_programmers SET password='" . $password . "', company='" . $name . "', email='" . $email . "', categories='" . $myvar . "', rate='" . $rate . "', profile='" . $profile . "', notify='" . $notify . "' WHERE username='" . $username . "'");
if ($email !== $nemail2) {
$ttoy = time();
SQLact("query", "INSERT INTO freelancers_edittemp (email, id, username, atype) VALUES ('$nemail2', '$ttoy', '$username', 'freelancer')");
$themsg = $emailheader . '
--------------------
Go to the following URL to confirm your new e-mail address at ' . $companyname . ':

' . $siteurl . '/freelancers.php?editconfirm=' . $ttoy . '
--------------------
' . $emailfooter;
mail($nemail,"Confirm E-mail for " . $companyname,$themsg,"From: $emailaddress");
echo 'Your info has been changed, but you must confirm your new e-mail address. Follow the instructions in a message sent to ' . $nemail . '<br>
<a href="' . $siteurl . '/freelancers.php?login=now">Go back...</a>';
} else {
echo 'Congratulations! Your information has been successfully updated!';
}
}
$theaptres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $username . "'");
$ncompany = SQLact("result", $theaptres,0,"company");
$nemail = SQLact("result", $theaptres,0,"email");
$nrate = SQLact("result", $theaptres,0,"rate");
$nprofile = SQLact("result", $theaptres,0,"profile");
$nnotify = SQLact("result", $theaptres,0,"notify");
echo '<form method="POST" action="freelancers.php" ENCTYPE="multipart/form-data">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="edit" value="info">

<big><b>Edit your info</b></big>
<p>
<strong>Username:</strong> ' . $username . '
<p>
<strong>Password:</strong><br>
<input type="password" name="newpassword" value="' . $password . '" size="15">
<p>
<strong>Name/Company:</strong><br>
<input type="text" name="name" value="' . $ncompany . '" size="20">
<p>
<strong>E-mail Address:</strong><br>
<input type="text" name="email" value="' . $nemail . '" size="25">
<br><small>(<a href="' . $siteurl . '/privacy.html">Privacy Policy</a>)</small>
<p>
<b>Area of Expertise:</b><br>';
if ($multiplecats == "enabled") {
echo '<small>(You can make multiple selections.)</small><br>';
}
$theress = SQLact("query", "SELECT * FROM freelancers_cats");
while ($row=SQLact("fetch_array", $theress)) {
$tttress = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $username . "' AND categories LIKE '%" . $row[categories]. "%'");
$rowssss = SQLact("num_rows", $tttress);
if ($rowssss!==0) {
if ($multiplecats == "enabled") {
$printcats .= '<input type="checkbox" name="category[]" value="' . $row[categories] . '" checked> ' . $row[categories] . '<br>
';
} else {
$printcats .= '<input type="radio" name="category[]" value="' . $row[categories] . '" checked> ' . $row[categories] . '<br>
';
}
} else {
if ($multiplecats == "enabled") {
$printcats .= '<input type="checkbox" name="category[]" value="' . $row[categories] . '"> ' . $row[categories] . '<br>
';
} else {
$printcats .= '<input type="radio" name="category[]" value="' . $row[categories] . '"> ' . $row[categories] . '<br>
';
}
}
}
echo $printcats . '<p><b>Your average hourly rate:</b><br>

' . $currencytype . '' . $currency . ' <input type="text" name="rate" value="' . $nrate . '" maxlength="3" size="3">/hour
<p>

<strong>Your profile (optional):</strong>
<br>
<textarea rows="8" name="profile" cols="52">' . $nprofile . '</textarea>
<p>
';
if ($nnotify == "") {
$tnotify = '<input type="checkbox" name="notify" value="notify"';
} else {
$tnotify = '<input type="checkbox" name="notify" value="notify" checked';
}
echo $tnotify . '> Notify me by e-mail when a project gets posted that is relevant to me.<br>

<p><input type="submit" value="Edit" name="submit"></p>
</form>';
} else if ($viewprojects == "all") {
echo $toplayout;
echo '<b>Projects You Have Bid On</b>
<p>
<table border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Project Name</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Status</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Other</td>
</tr>';
$tinyres2 = SQLact("query", "SELECT * FROM freelancers_bids WHERE username='" . $username . "' ORDER BY id DESC");
$tinyrows2 = SQLact("num_rows", $tinyres2);
if ($tinyrows2==0) {
echo '<tr><td bgcolor=' . $tablecolor1 . ' colspan=4><center><small>(no projects to display)</td></tr>';
} else {
while ($kikrow2=SQLact("fetch_array", $tinyres2)) {
echo '<tr> <td bgcolor="' . $tablecolor1 . '"><small><a href="' . $siteurl . '/project.php?id=' . $kikrow2[id] . '">' . $kikrow2[project] . '</a>';
if ($kikrow2[special] == "featured") {
echo ' <a href="' . $siteurl . '/featured.html"><img src="' . $siteurl . '/featured.gif" alt="Featured Project!" border=0>';
}
echo '</td> <td bgcolor="' . $tablecolor1 . '"><small>';
if ($kikrow2[status] == "open") {
echo '<font color=green><b>' .  $kikrow2[status];
} else {
echo '<font color=black><b>' .  $kikrow2[status];
}
echo '</td> <td bgcolor="' . $tablecolor1 . '"><small>';
$tinyres3 = SQLact("query", "SELECT * FROM freelancers_projects WHERE chosen='" . $username . "' AND id='" . $kikrow2[id] . "' AND status='closed'");
$tinyrows3 = SQLact("num_rows", $tinyres3);
if ($tinyrows3!==0) {
echo '<font color=green>(Bid won)';
} else {
if ($kikrow2[status] == "cancelled") {
echo '(Cancelled)';
} else if ($kikrow2[status] == "open" || $kikrow2[status] == "frozen") {
if ($kikrow2[chosen] == $username) {
echo 'Bid Won: <a href="' . $siteurl . '/freelancers.php?manage=2&confirm2=' . $kikrow2[id] . '">Accept/Deny Offer</a>';
} else {
echo '<a href="' . $siteurl . '/freelancers.php?manage=2&retract=' . $kikrow2[id] . '">Retract Your Bid</a>';
}
} else {
echo '(Bid lost)';
}
}
echo '</td></tr>';
}
}
echo '</table>';
} else if ($transfer == "money") {
echo $toplayout;
$hugeres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$hugerows2 = SQLact("num_rows", $hugeres2);
if ($hugerows2==0) {
if (!$submit && !$submit2) {
?>
<form method="POST" action="freelancers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="transfer" value="money">

<big><b>Send Money to a <? echo $buyer; ?></b></big>
<p>
<strong><? echo $buyer; ?> Username:</strong><br>
<input type="text" name="transfer2" value="<? echo $transfer2; ?>" size="15">
<p>
<strong>How much money would you like to transfer to their account?</strong><br>
<? echo $currencytype . '' . $currency; ?><input type="text" name="tamount" value="<? echo $tamount; ?>" maxlength="7" size="6">
<p>

<input type="submit" value="Send Payment" name="submit">
</form>
<?php
} else if ($submit && $submit !== "") {
$theabsoluteresss3 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$dbalance = SQLact("result", $theabsoluteresss3,0,"balance");
if ($tamount>$dbalance) {
echo 'ERROR: Unfortunately, you do not have as much money as you requested to transfer in your balance.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if ($tamount<$transfermini) {
echo 'ERROR: Unfortunately, the transfer minimum is ' . $currencytype . '' . $currency . '' . $transfermini . ' and your transfer amount is less than this minimum.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
$transfer2 = strtolower($transfer2);
$smallres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $transfer2 . "'");
$smallrows = SQLact("num_rows", $smallres);
if ($smallrows==0) {
echo 'ERROR: No ' . $buyer . ' account was found with that username, please go back and make sure that you correctly spelled it and that that ' . $buyer . ' account even exists.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
?>
<center>
<table width="90%" border="<? echo $tableborder; ?>" cellspacing="<? $tablecellsp; ?>" cellpadding="<? $tablecellpa; ?>">
<tr>
<td bgcolor=' . $tablecolorh . '>
<b><big>Please confirm the following before sending the payment:</big></b>
<p>
<b><? echo $buyer; ?>: <? echo $transfer2; ?>
<br>
Amount: <? echo $currencytype . '' . $currency; ?><? echo $tamount; ?>
<br>

<center>
<form method="POST" action="freelancers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="transfer" value="money">
<input type="hidden" name="transfer3" value="<? echo $transfer2; ?>">
<input type="hidden" name="ramount" value="<? echo $tamount; ?>">
<small>Be patient and do not press this button more than once, or your account could be charged twice.</small>
<br>
<input type="submit" value="Confirm Payment" name="submit2">
<p>
<small>(<A HREF="javascript:history.back()">Go back</A> if you need to change anything.)
</form>

</td>
</tr>
</table>
</center>
<?php
}
}
}
} else if ($submit2 == "Confirm Payment") {
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
$tyress2 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $transfer3 . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$bal2 = SQLact("result", $tyress2,0,"balance");
$drobble = $bal2+$ramount;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+$ramount', 'Payment from " . $freelancer . " (<a href=\"$siteurl/freelancers.php?viewprofile=$username\">$username</a>)', '$transfer3', 'buyer', '$drobble', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
$tyress3 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$bal3 = SQLact("result", $tyress3,0,"balance");
$dribble = $bal3-$ramount;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('-$ramount', 'Payment to " . $buyer . " ($transfer3)', '$username', 'freelancer', '$dribble', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
$webemail = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$transfer3'");
mail(SQLact("result", $webemail,0,"email"),"Money Added to Your " . $companyname . " Account","The $freelancer $username has just paid you $ramount!  The money has been added to your account.");
echo '<br><br>You have successfully transfered <b>' . $currencytype . '' . $currency . '' . $ramount . '</b> to the ' . $buyer . ' <b>' . $transfer3 . '</b>. Your account balance has been updated accordingly.<br>
<a href="' . $siteurl . '/freelancers.php?manage=2">Go back...</a>';
}
} else {
echo 'You have been suspended from using ' . $companyname . '!<br>
You do not have access to manage and view services used in this section of the website!<br>
The reason for your suspension is:<br>
<b><i>';
$humanres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$rrreason2 = SQLact("result", $humanres2,0,"reason");
if ($rrreason2 == "") {
echo 'No reason was given for your suspension.';
} else {
echo $rrreason2;
}
echo '</i></b><br><br>
If you think there has been a mistake, contact ' . $emailaddress;
}
} else if ($transactions == "view") {
echo $toplayout;
$wowieress = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='freelancer' ORDER BY date2 DESC");
echo '<b>Transaction Record</b>
<p>
<table width="100%" border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor="' . $tablecolorh . '"><b><font color=' . $tablecolort . '>Amount</td>
<td bgcolor="' . $tablecolorh . '"><b><font color=' . $tablecolort . '>Description</td>
<td bgcolor="' . $tablecolorh . '"><b><font color=' . $tablecolort . '>Date</td>
</tr>';
while ($rower=SQLact("fetch_array", $wowieress)) {
echo '<tr>
<td bgcolor="' . $tablecolor1 . '">';
if (eregi("\+", $rower[amount])) {
echo '<font color=green>' . $rower[amount] . '</font>';
} else {
echo '<font color=red>' . $rower[amount] . '</font>';
}
echo '<td bgcolor="' . $tablecolor1 . '">' . $rower[details] . '</td>
<td bgcolor="' . $tablecolor1 . '">' . $rower[date] . '</td>
</tr>';
}
echo '</table>';
} else if ($deposit == "money") {
if (!$submit) {
echo $toplayout;
?>
<form method="POST" action="freelancers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="deposit" value="money">

<big><B>Deposit Money</b></big>
<p>
<strong>Amount:</strong> <? echo $currencytype . '' . $currency; ?><input type="text" name="deposit2" maxlength="7" size="6">
<p>
<strong>Payment Method:</strong><br>
<?php
if ($deposit2checkout !== "" || $depositccurl !== "") {
echo '
<br><input type="radio" value="cc" name="dtype"> Credit Card';
if ($depositccper !== "" || $depositccfee !== "") {
echo ' <small>(cost: ' . $depositccper . '% + ' . $currencytype . '' . $currency . '' . $depositccfee . ')</small>';
}
}
if ($depositpaypal !== "") {
echo '
<br><input type="radio" value="paypal" name="dtype"> <a href="https://www.paypal.com/affil/pal=' . $ppemailaddr . '" target="_blank">PayPal</a>';
if ($depositppper !== "" || $depositppfee !== "") {
echo ' <small>(cost: ' . $depositppper . '% + ' . $currencytype . '' . $currency . '' . $depositppfee . ')</small>';
}
}
if ($depositmail !== "") {
echo '
<br><input type="radio" value="mail" name="dtype"> Regular Mail';
if ($depositmlfee !== "") {
echo ' <small>(cost: ' . $currencytype . '' . $currency. '' . $depositmlfee . ')</small>';
}
}
if ($depositother !== "") {
echo '
<p><a href="' . $siteurl . '/payment.html">Click here for other payment options...</a>';
}
?>
<p><input type="submit" value="Continue" name="submit">
</form>
<?php
} else {
if ($dtype == "mail") {
echo $toplayout;
$price = $deposit2+$depositmlfee;
echo '<big><b>Payments by Mail</b></big>
<p>
Please send a cash or check payment for ' . $currencytype . '' . $currency . '' . $price . '  to the following address:
<p>
' . $companyname . '<br>
' . $address . '<br>
' . $city . ', ' . $state . ', ' . $country . '<br>
' . $zipcode . '<br>
<p>
Include a note with your username (' . $username . ') and your account type (' . $freelancer . ') on it so we can credit your account accordingly.
<br>
Thanks';
} else if ($dtype == "paypal") {
$orderid = 'PP' . time();
if ($depositppper !== "" || $depositppfee !== "") {
$depositppper = $depositppper/100;
$pcost = $deposit2 * $depositppper;
$total = $deposit2 + $pcost + $depositppfee;
} else {
$total = $deposit2;
}
$total = roundit($total,2);
SQLact("query", "INSERT INTO freelancers_deposits (username, atype, amount, total, oid, ptype, status) VALUES ('$username', 'freelancer', '$deposit2', '$total', '$orderid', 'paypal', '')");
$itemname = $companyname . ': ' . $deposit2 . ' Dollar Deposit';
$itemname = str_replace(' ', '+', $itemname);
$itemname = str_replace('@', '%40', $itemname);
$itemname = str_replace(':', '%3A', $itemname);
$siteurl = str_replace(':', '%3A', $siteurl);
$siteurl = str_replace('/', '%2F', $siteurl);
$redirect = 'https://www.paypal.com/xclick/?business=' . $ppemailaddr . '&item_name=' . $itemname . '&item_number=1&invoice=' . $orderid . '&amount=' . $total . '&no_shipping=1&return=' . $siteurl . '%2Fdeposit_paypal.php%3Foid=' . $orderid;
$redirect = str_replace('@', '%40', $redirect);
header("Location: $redirect");
} else if ($dtype == "cc") {
$orderid = 'CC' . time();
if ($depositccper !== "" || $depositccfee !== "") {
$depositccper = $depositccper/100;
$pcost = $deposit2 * $depositccper;
$total = $deposit2 + $pcost + $depositccfee;
} else {
$total = $deposit2;
}
$total = roundit($total,2);
if ($deposit2checkout == "") {
$depositccurl = str_replace('[amount]', $total, $depositccurl);
$depositccurl = str_replace('[username]', $username, $depositccurl);
$depositccurl = str_replace('[type]', 'freelancer', $depositccurl);
header("Location: $depositccurl");
}
SQLact("query", "INSERT INTO freelancers_deposits (username, atype, amount, total, oid, ptype, status) VALUES ('$username', 'freelancer', '$deposit2', '$total', '$orderid', 'cc', '')");
if ($mode == "demo") {
$redirect = $depositccpay . '' . $depositccsid . '&cart_order_id=' . $orderid . '&total=' . $total . '&demo=Y';
} else {
$redirect = $depositccpay . '' . $depositccsid . '&cart_order_id=' . $orderid . '&total=' . $total . '&demo=';
}
setcookie ("oid", $orderid,time()+999999);
header("Location: $redirect");
} else {
echo $toplayout;
echo 'Please choose a deposit method to continue with the deposit process.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
}
}
} else if ($retract && $retract !== "") {
echo $toplayout;
$proj = SQLact("query", "SELECT * FROM freelancers_bids WHERE id='$retract' AND username='$username'");
if (SQLact("num_rows", $proj)==0) {
echo '<center>ERROR: No project currently selected to retract bid from.<br>
<a href="javascript:history.go(-1);">Go Back...</a></center>';
} else {
if (!$submit) {
echo '<center>
<form method="POST" action="freelancers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="retract" value="' . $retract . '">
Are you sure you want to remove your bid from the project named <b>' . SQLact("result", $proj,0,"project") . '</b>?<br>
<input type="submit" value="Yes, remove my bid!" name="submit">
</form>
</center>';
} else {
SQLact("query", "DELETE FROM freelancers_bids WHERE id='$retract' AND username='$username'");
echo '<META HTTP-EQUIV=REFRESH CONTENT="2; URL=' . $siteurl . '/freelancers.php?manage=2">
Your bid has been retracted from the project ' . SQLact("result", $proj,0,"project") . '...<br>
(<a href="' . $siteurl . '/freelancers.php?manage=2">Click here</a> if you do not get redirected after 2 seconds.)';
}
}
} else if ($rate && $rate !== "") {
echo $toplayout;
$projrate = SQLact("query", "SELECT * FROM freelancers_ratings WHERE projid='$rate' AND ratedby='$username' AND status='' AND type2='freelancer'");
if (SQLact("num_rows", $projrate)==0) {
echo 'ERROR: You do not have permission to rate this user at the moment.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if (!$submit) {
echo '<form method="POST" action="freelancers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="rate" value="' . $rate . '">

<strong>Rating:</strong><br>
<small>(Rate the user between 1 and 10)</small>
<br>
<select name="rating" size="1">
<option selected value="">------------</option>
<option value="1">1 - Very Poor</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5 - Acceptable</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10 - Excellent</option>
</select>
<p>


<strong>Comment:</strong><br>
<small>(Maximum ' . $feedbackcmax . ' characters.)</small>
<br>
<input type="text" name="comment" maxlength="' . $feedbackcmax . '" size="60">
<p>


<p><input type="submit" value="Rate User" name="submit"></p>
</form>';
} else {
if ($rating == "") {
echo 'Please rate the user between 1 and 10.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else if ($comment == "") {
echo 'Please provide a comment about the user.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
SQLact("query", "UPDATE freelancers_ratings SET status='rated', rating='$rating', comments='$comment' WHERE projid='$rate' AND type2='freelancer'");
echo 'Your feedback for ' . SQLact("result", $projrate,0,"username") . ' has been recorded.<br>
<a href="' . $siteurl . '/freelancers.php?manage=2">Go Back...</a>';
}
}
}
} else if ($confirm2 && $confirm2 !== "") {
echo $toplayout;
$hugeres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$hugerows2 = SQLact("num_rows", $hugeres2);
if ($hugerows2==0) {
$projconf = SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$confirm2' AND status='frozen' AND chosen='$username'");
if (SQLact("num_rows", $projconf)==0) {
echo 'ERROR: You are not authorized to accept the work of this project.<br>
<a href="' . $siteurl . '/freelancers.php?manage=2">Go Back...</a>';
} else {
if (!$submit) {
echo '<script>
function windowError() {
return false;
}
window.onError=windowError();
function submitonce(theform){
//if IE 4+ or NS 6+
if (document.all||document.getElementById){
//screen thru every element in the form, and hunt down "submit" and "reset"
for (i=0;i<theform.length;i++){
var tempobj=theform.elements[i]
if(tempobj.type.toLowerCase()=="submit"||tempobj.type.toLowerCase()=="reset")
//disable em
tempobj.disabled=true
}
}
}
</script>

<form method="POST" action="freelancers.php" onSubmit="submitonce(this)">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="confirm2" value="' . $confirm2 . '">
<input type="hidden" name="submit" value="Submit">

<big><b>Confirm Project: <a href="' . $siteurl . '/project.php?id=' . $confirm2 . '">' . SQLact("result", $projconf,0,"project") . '</a></b>';
if (SQLact("result", $projconf,0,"special") == "featured") {
echo ' <img src="' . $siteurl . '/featured.gif">';
}
echo '</big>
<p>
<strong>Do you want to accept the offer to work on this project?</strong><br>
<select name="accept" size="1">
<option selected value="yes">Yes, I Accept</option>
<option value="no">Deny</option>
</select>
<p>';
if (SQLact("result", $projconf,0,"special") == "featured") {
echo 'Note: This is a featured project and you will not be charged a commission if you accept the offer.
<br>';
}
echo '<input type="submit" value="Submit" name="submit">
</form>';
} else {
if ($accept == "no") {
echo 'Your request has been processed and the creator of the project has been notified so that they can pick a different ' . $freelancer . '. No other action is required on your part. Thanks.';
$tokl = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . SQLact("result", $projconf,0,"creator") . "'");
$emaddr = SQLact("result", $tokl,0,"email");
$mymess = $emailheader . '
----------
The ' . $freelancer . ' (' . $username . ') you chose for the project "' . SQLact("result", $projconf,0,"project") . '" has responded with a "Deny" answer. They can\'t, or do not want to work on this project for one reason or another. You will have to pick another ' . $freelancer . ', extend the bidding period of this project, or close it, by going to the ' . $buyer . ' account management area at ' . $siteurl . '/buyers.php?login=now

If you have any questions you can contact ' . $emailaddress . '
----------
' . $emailfooter;
mail($emaddr,$companyname . " " . $freelancer . " Denied",$mymess,"From: $emailaddress");
echo 'Your request has been processed and the creator of the project has been notified so that they can pick a different ' . $freelancer . '. No other action is required on your part. Thanks.';
} else {
$tokl = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . SQLact("result", $projconf,0,"creator") . "'");
$emaddr = SQLact("result", $tokl,0,"email");
$result10090 = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $username . "'");
$emaddr2 = SQLact("result", $result10090,0,"email");
$mymess = $emailheader . '
----------
Your bid was chosen for the project named ' . SQLact("result", $projconf,0,"project") . ' and you have accepted the offer.
You may begin work on the project now, or contact the project owner at the following e-mail address:
' . $emaddr . '
We encourage you to provide feedback about this user when work on this project is complete (or not). You can do this anytime by logging in to the ' .$freelancer . ' account management area.
If you have any problems with them regarding this project you can contact ' . $emailaddress . '
Good Luck!
----------
' . $emailfooter;
mail($emaddr2,$companyname . " Project Begins",$mymess,"From: $emaddr");
$mymess2 = $emailheader . '
----------
Your project named ' . SQLact("result", $projconf,0,"project") . ' is now closed and the user you chose ' . $username . ' has accepted to work on the project.
You may contact this user now at the following e-mail address:
' . $emaddr2 . '
We encourage you to provide feedback about this user when work on this project is complete (or not). You can do this anytime by logging in to the ' . $buyer . ' account management area.
If you have any problems with them regarding this project you can contact ' . $emailaddress . '
Good Luck!
----------
' . $emailfooter;
mail($emaddr,$companyname . " Project Closed",$mymess2,"From: $emaddr2");
SQLact("query", "UPDATE freelancers_projects SET status='closed', chosen='$username' WHERE id='$confirm2'");
SQLact("query", "UPDATE freelancers_bids SET status='closed', chosen='$username' WHERE id='$confirm2'");
SQLact("query", "INSERT INTO freelancers_ratings (username, rating, ratedby, projid, projname, projdate, comments, type, status, type2) VALUES ('$username', '', '" . SQLact("result", $projconf,0,"creator") . "', '$confirm2', '" . SQLact("result", $projconf,0,"project") . "', '" . date("m/d/Y H:i") . "', '', 'freelancer', '', 'buyer')");
SQLact("query", "INSERT INTO freelancers_ratings (username, rating, ratedby, projid, projname, projdate, comments, type, status, type2) VALUES ('" . SQLact("result", $projconf,0,"creator") . "', '', '$username', '$confirm2', '" . SQLact("result", $projconf,0,"project") . "', '" . date("m/d/Y H:i") . "', '', 'buyer', '', 'freelancer')");
if (SQLact("result", $projconf,0,"special") !== "featured") {
$bidd = SQLact("query", "SELECT * FROM freelancers_bids WHERE username='$username'");
$amnt = SQLact("result", $bidd,0,"amount");
$projectper = $projectper/100;
$projectper2 = $projectper2/100;
$perc = $projectper * $amnt;
$perc2 = $projectper2 * $amnt;
if (SQLact("result", $result10090,0,"special") == "user") {
$perc = $perc / 2;
$projectfee = $projectfee / 2;
}
if (SQLact("result", $tokl,0,"special") == "user") {
$perc2 = $perc2 / 2;
$projectfee2 = $projectfee2 / 2;
}
$perc = roundit($perc,2);
$perc2 = roundit($perc2,2);
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
if ($perc>$projectfee) {
$olba = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='$username' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$oldb = SQLact("result", $olba,0,"balance");
$newb = $oldb - $perc;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('-$perc', 'Project (<a href=\"" . $siteurl . "/project.php?id=$confirm2\">" . SQLact("result", $projconf,0,"project") . "</a>)', '$username', 'freelancer', '$newb', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
} else {
$olba = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='$username' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$oldb = SQLact("result", $olba,0,"balance");
$newb = $oldb - $projectfee;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('-$projectfee', 'Project (<a href=\"" . $siteurl . "/project.php?id=$confirm2\">" . SQLact("result", $projconf,0,"project") . "</a>)', '$username', 'freelancer', '$newb', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
}
if ($perc2>$projectfee2) {
$olba2 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . SQLact("result", $projconf,0,"creator") . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$oldb2 = SQLact("result", $olba2,0,"balance");
$newb2 = $oldb2 - $perc2;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('-$perc2', 'Project (<a href=\"" . $siteurl . "/project.php?id=$confirm2\">" . SQLact("result", $projconf,0,"project") . "</a>)', '" . SQLact("result", $projconf,0,"creator") . "', 'buyer', '$newb2', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
} else {
$olba2 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . SQLact("result", $projconf,0,"creator") . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$oldb2 = SQLact("result", $olba2,0,"balance");
$newb2 = $oldb2 - $projectfee2;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('-$projectfee2', 'Project (<a href=\"" . $siteurl . "/project.php?id=$confirm2\">" . SQLact("result", $projconf,0,"project") . "</a>)', '" . SQLact("result", $projconf,0,"creator") . "', 'buyer', '$newb2', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
}
}
echo 'You were chosen for the project named ' . SQLact("result", $projconf,0,"project") . ' and you have accepted the offer. You may begin work on the project now, or contact the project owner at the following e-mail address:<br>
<a href="mailto:' . $emaddr . '">' . $emaddr . '</a><br>
We encourage you to provide feedback about this user when work on this project is complete (or not). You can do this anytime by logging in to the account management area. If you have any problems with them regarding this project you can contact ' . $emailaddress . '<br>
Good Luck!';
}
}
}
} else {
echo 'You have been suspended from using ' . $companyname . '!<br>
You do not have access to manage and view services used in this section of the website!<br>
The reason for your suspension is:<br>
<b><i>';
$humanres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$rrreason2 = SQLact("result", $humanres2,0,"reason");
if ($rrreason2 == "") {
echo 'No reason was given for your suspension.';
} else {
echo $rrreason2;
}
echo '</i></b><br><br>
If you think there has been a mistake, contact ' . $emailaddress;
}
} else if ($withdraw == "money") {
echo $toplayout;
$hugeres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$hugerows2 = SQLact("num_rows", $hugeres2);
if ($hugerows2==0) {
if (!$submit) {
?>
<form method="POST" action="freelancers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="withdraw" value="money">

<big><b>Withdraw Money (Step 1 of 3)</b></big>
<p>
<strong>Amount:</strong> $<input type="text" name="withdraw_b" maxlength="7" size="6">
<p>
<strong>Payment Method:</strong><br>
<input type="radio" value="check" checked name="wtype"> <b>Check</b>
<small>(<? echo $currencytype . '' . $currency . '' . $withdrawcfee; ?> fee)</small>
<br>
<?php
if ($withdrawpaypal == "enabled") {
echo '<input type="radio" value="paypal" name="wtype"> <b><a href="https://www.paypal.com/affil/pal=' . $ppemailaddr . '" target="_blank">Paypal</a></b>
<small>(' . $currencytype . '' . $currency . '' . $withdrawpfee . ' fee)</small>
<br>';
}
if ($withdrawwire == "enabled") {
echo '<input type="radio" value="wire" name="wtype"> <b>Bank Wire</b>
<small>(' . $currencytype . '' . $currency . '' . $withdrawwfee . ' fee)</small>
<br>';
}
if ($withdrawother == "enabled") {
?>
<input type="radio" value="other" name="wtype"> <b><? echo $withdrawonam; ?></b>
<small>(<? echo $currencytype . '' . $currency . '' . $withdrawofee; ?> fee)</small>
<br>
<?php
}
?>
<p>
<input type="submit" value="Continue" name="submit">
</form>
<?php
} else {
$olba = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='$username' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$oldb = SQLact("result", $olba,0,"balance");
if ($withdraw_b == "" || !is_int($withdraw_b) || $withdraw_b<$withdrawmini) {
echo 'Your withdrawal must be a minimum of ' . $currencytype . '' . $currency . '' . $withdrawmini . '.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else if ($withdraw_b>$oldb) {
echo 'Unfortunately, you don\'t currently have that much money in your account.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if ($wtype == "check") {
$bfee = $oldb + $withdrawcfee;
if ($withdraw_b>$bfee) {
echo 'Unfortunately, resulting from fees along with your current balance, you don\'t have as much money as your requested amount to withdraw.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if (!$continue) {
$prgmru = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'");
$wname = SQLact("result", $prgmru,0,"wname");
$waddress = SQLact("result", $prgmru,0,"waddress");
$wcity = SQLact("result", $prgmru,0,"wcity");
$wzip = SQLact("result", $prgmru,0,"wzip");
echo '<form method="POST" action="freelancers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="withdraw" value="money">
<input type="hidden" name="withdrawa" value="' . $withdraw_b . '">
<input type="hidden" name="wtype" value="check">
<input type="hidden" name="submit" value="Continue">

<big><b>Withdraw Money (Step 2 of 3)</b></big>
<p>
Please provide the following info to receive a check:<p>
Name (Payable to):<br><input type="text" name="wname" value="' . $wname . '" size="20"><br>
Address:<br><input type="text" name="waddress" value="' . $waddress . '" size="40"><br>
City, State/Province, Country:<br><input type="text" name="wcity" value="' . $wcity . '" size="40"><br>
Zip/Postal Code:<br><input type="text" name="wzip" value="' . $wzip . '" size="10"><p>
<p>
<input type="submit" value="Continue" name="continue">
</form>';
} else {
if ($wname == "" || $waddress == "" || $wcity == "" || $wzip == "") {
echo 'You have left 1 or more fields blank.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if (!$confirm) {
echo '<script>
function winErr() {
return false;
}
window.onError=winErr();

function submitonce(theform){
//if IE 4+ or NS 6+
if (document.all||document.getElementById){
//screen thru every element in the form, and hunt down "submit" and "reset"
for (i=0;i<theform.length;i++){
var tempobj=theform.elements[i]
if(tempobj.type.toLowerCase()=="submit"||tempobj.type.toLowerCase()=="reset")
//disable em
tempobj.disabled=true
}
}
}
</script>
<center>
<table width="90%" border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor=' . $tablecolor1 . '>
<b><big>Please confirm the following before withdrawing the money:</big></b>
<p>
<b>Amount: ' . $currencytype . '' . $currency . '' . $withdrawa . '</b>
<br>
<b>Fee: -' . $currencytype . '' . $currency . ' ' . $withdrawcfee . '</b><br>';
$newam = $withdrawa - $withdrawcfee;
echo '<b>Total: ' . $currencytype . '' . $currency . '' . $newam . '</b><p>
<b>Check Payable To: ' . $wname . '</b>
<br>
<b>Address: ' . $waddress . '</b>
<br>
<b>City, State/Province & Country: ' . $wcity . '</b>
<br>
<b>Zip/Postal Code: ' . $wzip . '</b>
<br>

<center>
<form method="POST" action="freelancers.php" onSubmit="submitonce(this)">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="withdraw" value="money">
<input type="hidden" name="withdrawb" value="' . $withdrawa . '">
<input type="hidden" name="wtype" value="check">
<input type="hidden" name="submit" value="Continue">
<input type="hidden" name="continue" value="Continue">
<input type="hidden" name="wname" value="' . $wname . '">
<input type="hidden" name="waddress" value="' . $waddress . '">
<input type="hidden" name="wcity" value="' . $wcity . '">
<input type="hidden" name="wzip" value="' . $wzip . '">

<br>
<input type="submit" value="Withdraw Money" name="confirm">
<p>
<small>(<A HREF="javascript:history.go(-1);">Go back</A> if you need to change anything.)

</form>
</td>
</tr>
</table>
</center>';
} else {
$newam = $withdrawb - $withdrawcfee;
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
SQLact("query", "INSERT INTO freelancers_withdrawals (date, date2, username, atype, wtype, amount, name, address, city, zip, status, wfee, namount) VALUES ('$month/$day/$year @ $hours:$minutes', '" . time() . "', '$username', 'freelancer', 'check', '$withdrawb', '$wname', '$waddress', '$wcity', '$wzip', '', '$withdrawcfee', '$newam')");
SQLact("query", "UPDATE freelancers_programmers SET wname='$wname', waddress='$waddress', wcity='$wcity', wzip='$wzip' WHERE username='$username'");
if ($withdrawnotify == "enabled") {
mail($emailaddress,"New " . $companyname . " Withdrawal Request","The $freelancer $username has just made a withdrawal request of $withdrawb by check.  You can view the details at $siteurl/admin.php?pass=$adminpass");
}
echo 'Your withdrawal request has been submitted. Your account balance will be updated accordingly once the check has been sent by us.<br>
<a href="' . $siteurl . '/freelancers.php?manage=2">Go back...</a>
<META HTTP-EQUIV=REFRESH CONTENT="10; URL=' . $siteurl . '/freelancers.php?manage=2">';
}
}
}
}
} else if ($wtype == "paypal") {
$bfee = $oldb + $withdrawpfee;
if ($withdraw_b>$bfee) {
echo 'Unfortunately, resulting from fees along with your current balance, you don\'t have as much money as your requested amount to withdraw.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if (!$continue) {
$prgmru = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'");
$wemail = SQLact("result", $prgmru,0,"wemail");
echo '<form method="POST" action="freelancers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="withdraw" value="money">
<input type="hidden" name="withdrawa" value="' . $withdraw_b . '">
<input type="hidden" name="wtype" value="paypal">
<input type="hidden" name="submit" value="Continue">

<big><b>Withdraw Money (Step 2 of 3)</b></big>
<p>
What e-mail should we send the paypal payment to?<br>
<input type="text" name="wemail" value="' . $wemail . '" size="20"><p>
<p>
<input type="submit" value="Continue" name="continue">
</form>';
} else {
if ($wemail == "" || !eregi("@", $wemail) || !eregi(".", $wemail)) {
echo 'Please enter a valid email address.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if (!$confirm) {
echo '<script>
function winErr() {
return false;
}
window.onError=winErr();

function submitonce(theform){
//if IE 4+ or NS 6+
if (document.all||document.getElementById){
//screen thru every element in the form, and hunt down "submit" and "reset"
for (i=0;i<theform.length;i++){
var tempobj=theform.elements[i]
if(tempobj.type.toLowerCase()=="submit"||tempobj.type.toLowerCase()=="reset")
//disable em
tempobj.disabled=true
}
}
}
</script>

<center>
<table width="90%" border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor=' . $tablecolor1 . '>
<b><big>Please confirm the following before withdrawing the money:</big></b>
<p>
<b>Amount: ' . $currencytype . '' . $currency . '' . $withdrawa . '</b>
<br>
<b>Fee: -' . $currencytype . '' . $currency . ' ' . $withdrawpfee . '</b><br>';
$newam = $withdrawa - $withdrawpfee;
echo '<b>Total: ' . $currencytype . '' . $currency . '' . $newam . '</b><p>
<b>PayPal E-mail: ' . $wemail . '</b>
<br>

<center>
<form method="POST" action="freelancers.php" onSubmit="submitonce(this)">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="withdraw" value="money">
<input type="hidden" name="withdrawb" value="' . $withdrawa . '">
<input type="hidden" name="wtype" value="paypal">
<input type="hidden" name="submit" value="Continue">
<input type="hidden" name="continue" value="Continue">
<input type="hidden" name="wemail" value="' . $wemail . '">

<br>
<input type="submit" value="Withdraw Money" name="confirm">
<p>
<small>(<A HREF="javascript:history.back()">Go back</A> if you need to change anything.)

</form>
</td>
</tr>
</table>
</center>';
} else {
$newam = $withdrawb - $withdrawpfee;
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
SQLact("query", "INSERT INTO freelancers_withdrawals (date, date2, username, atype, wtype, amount, email, status, wfee, namount) VALUES ('$month/$day/$year @ $hours:$minutes', '" . time() . "', '$username', 'freelancer', 'paypal', '$withdrawb', '$wemail', '', '$withdrawpfee', '$newam')");
SQLact("query", "UPDATE freelancers_programmers SET wemail='$wemail' WHERE username='$username'");
if ($withdrawnotify == "enabled") {
mail($emailaddress,"New " . $companyname . " Withdrawal Request","The $freelancer $username has just made a withdrawal request of $withdrawb by PayPal.  You can view the details at $siteurl/admin.php?pass=$adminpass");
}
echo 'Your withdrawal request has been submitted. Your account balance will be updated accordingly once the PayPal payment has been sent by us.<br>
<a href="' . $siteurl . '/freelancers.php?manage=2">Go back...</a>
<META HTTP-EQUIV=REFRESH CONTENT="10; URL=' . $siteurl . '/freelancers.php?manage=2">';
}
}
}
}
} else if ($wtype == "wire") {
$bfee = $oldb + $withdrawwfee;
if ($withdraw_b>$bfee) {
echo 'Unfortunately, resulting from fees along with your current balance, you don\'t have as much money as your requested amount to withdraw.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if (!$continue) {
$prgmru = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'");
$wbankyourname = SQLact("result", $prgmru,0,"wbankyourname");
$wbankname = SQLact("result", $prgmru,0,"wbankname");
$wbankaddress = SQLact("result", $prgmru,0,"wbankaddress");
$wbankaddress2 = SQLact("result", $prgmru,0,"wbankaddress2");
$wbankcity = SQLact("result", $prgmru,0,"wbankcity");
$wbankstate = SQLact("result", $prgmru,0,"wbankstate");
$wbankcountry = SQLact("result", $prgmru,0,"wbankcountry");
$wbankzip = SQLact("result", $prgmru,0,"wbankzip");
$wbankaccnum = SQLact("result", $prgmru,0,"wbankaccnum");
$wbankcode = SQLact("result", $prgmru,0,"wbankcode");
$wbankacctype = SQLact("result", $prgmru,0,"wbankacctype");
if ($wbankacctype == "pc") $pc = "SELECTED";
if ($wbankacctype == "ps") $ps = "SELECTED";
if ($wbankacctype == "bc") $bc = "SELECTED";
if ($wbankacctype == "bs") $bs = "SELECTED";
echo '<form method="POST" action="freelancers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="withdraw" value="money">
<input type="hidden" name="withdrawa" value="' . $withdraw_b . '">
<input type="hidden" name="wtype" value="wire">
<input type="hidden" name="submit" value="Continue">

<big><b>Withdraw Money (Step 2 of 3)</b></big>
<p>
Please provide the following bank information:<p>

Account Holder\'s Name:<br>
<input type="text" name="wbankyourname" value="' . $wbankyourname . '" size="20">
<p>

Bank Name:<br>
<input type="text" name="wbankname" value="' . $wbankname . '" size="20">
<p>

Bank Street Address:<br>
<input type="text" name="wbankaddress" value="' . $wbankaddress . '" size="39"><br>
<input type="text" name="wbankaddress2" value="' . $wbankaddress2 . '" size="39">
<p>

Bank City:<br>
<input type="text" name="wbankcity" value="' . $wbankcity . '" size="20">
<p>

Bank State/Province:<br>
<input type="text" name="wbankstate" value="' . $wbankstate . '" size="20">
<p>

Bank Country:<br>
<input type="text" name="wbankcountry" value="' . $wbankcountry . '" size="20">
<p>

Bank Zip/Postal Code:<Br>
<input type="text" name="wbankzip" value="' . $wbankzip . '" size="8">
<p>

Bank Account Number:<br>
<input type="text" name="wbankaccnum" value="' . $wbankaccnum . '" size="15">
<p>

Bank Routing/Swift Code:<br>
<input type="text" name="wbankcode" value="' . $wbankcode . '" size="10">
<p>

Bank Account Type:<br>
<SELECT NAME="wbankacctype">
<OPTION ' . $pc . ' VALUE="pc">Personal Checking</OPTION>
<OPTION ' . $ps . ' VALUE="ps">Personal Savings</OPTION>
<OPTION ' . $bc . ' VALUE="bc">Business Checking</OPTION>
<OPTION ' . $bs . ' VALUE="bs">Business Savings</OPTION>
</select>
<p>
<input type="submit" value="Continue" name="continue">
</form>';
} else {
if ($wbankyourname == "" || $wbankname == "" || $wbankaddress == "" || $wbankcity == "" || $wbankstate == "" || $wbankcountry == "" || $wbankzip == "" || $wbankaccnum == "" || $wbankcode == "" || $wbankacctype == "") {
echo 'ERROR: You have left 1 or more fields blank.  (Does not include Bank Street Address field 2.)<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if (!$confim) {
echo '<script>
function winErr() {
return false;
}
window.onError=winErr();

function submitonce(theform){
//if IE 4+ or NS 6+
if (document.all||document.getElementById){
//screen thru every element in the form, and hunt down "submit" and "reset"
for (i=0;i<theform.length;i++){
var tempobj=theform.elements[i]
if(tempobj.type.toLowerCase()=="submit"||tempobj.type.toLowerCase()=="reset")
//disable em
tempobj.disabled=true
}
}
}
</script>
<center>
<table width="90%" border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor=' . $tablecolor1 . '>
<b><big>Please confirm the following before withdrawing the money:</big></b>
<p>
<b>Amount: ' . $currencytype . '' . $currency . '' . $withdrawa . '</b>
<br>
<b>Fee: -' . $currencytype . '' . $currency . ' ' . $withdrawwfee . '</b><br>';
$newam = $withdrawa - $withdrawwfee;
echo '<b>Total: ' . $currencytype . '' . $currency . '' . $newam . '</b><p>
<table>
<tr><td><b>Account Holder\'s Name:</b> </td><td>' . $wbankyourname . '</td></tr>
<tr><td><b>Bank Name:</b> </td><td>' . $wbankname . '</td></tr>
<tr><td><b>Bank Street Address:</b> </td><td>' . $wbankaddress . '</td></tr>
<tr><td><b>Bank City:</b> </td><td>' . $wbankcity . '</td></tr>
<tr><td><b>Bank State/Province:</b> </td><td>' . $wbankstate . '</td></tr>
<tr><td><b>Bank Country:</b> </td><td>' . $wbankcountry . '</td></tr>
<tr><td><b>Bank Zip/Postal Code:</b> </td><td>' . $wbankzip . '</td></tr>
<tr><td><b>Bank Account Number:</b> </td><td>' . $wbankaccnum . '</td></tr>
<tr><td><b>Bank Routing/Swift Code:</b> </td><td>' . $wbankcode . '</td></tr>
<tr><td><b>Bank Account Type:</b> </td><td>';
if ($wbankacctype == "pc") echo "Personal Checking";
if ($wbankacctype == "ps") echo "Personal Savings";
if ($wbankacctype == "bc") echo "Business Checking";
if ($wbankacctype == "bs") echo "Business Savings";
echo '</td></tr>
</table>

<center>
<form method="POST" action="freelancers.php" onSubmit="submitonce(this)">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="withdraw" value="money">
<input type="hidden" name="withdrawb" value="' . $withdrawa . '">
<input type="hidden" name="wtype" value="wire">
<input type="hidden" name="submit" value="Continue">
<input type="hidden" name="continue" value="Continue">
<input type="hidden" name="wbankname" value="' . $wbankname . '">
<input type="hidden" name="wbankaddress" value="' . $wbankaddress . '">
<input type="hidden" name="wbankaddress2" value="' . $wbankaddress2 . '">
<input type="hidden" name="wbankcity" value="' . $wbankcity . '">
<input type="hidden" name="wbankstate" value="' . $wbankstate . '">
<input type="hidden" name="wbankcountry" value="' . $wbankcountry . '">
<input type="hidden" name="wbankzip" value="' . $wbankzip . '">
<input type="hidden" name="wbankaccnum" value="' . $wbankaccnum . '">
<input type="hidden" name="wbankcode" value="' . $wbankcode . '">
<input type="hidden" name="wbankacctype" value="' . $wbankacctype . '">
<input type="hidden" name="wbankyourname" value="' . $wbankyourname . '">
<input type="submit" value="Withdraw Money" name="confirm">
<p>
<small>(<A HREF="javascript:history.go(-1);">Go back</A> if you need to change anything.)
</form>
</td>
</tr>
</table></center>';
} else {
$newam = $withdrawb - $withdrawwfee;
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
SQLact("query", "INSERT INTO freelancers_withdrawals (date, date2, username, atype, wtype, amount, bankyourname, bankname, bankaddress, bankaddress, bankaddress2, bankcity, bankstate, bankcountry, bankzip, bankaccnum, bankcode, bankacctype, status, wfee, namount) VALUES ('$month/$day/$year @ $hours:$minutes', '" . time() . "', '$username', 'freelancer', 'wire', '$withdrawb', '$wbankyourname', '$wbankname', '$wbankaddress', '$wbankaddress2', '$wbankcity', '$wbankstate', '$wbankcountry', '$wbankzip', '$wbankaccnum', '$wbankcode', '$wbankacctype', '', '$withdrawwfee', '$newam')");
SQLact("query", "UPDATE freelancers_programmers SET wbankyourname='$wbankyourname' wbankname='$wbankname', wbankaddress='$wbankaddress', wbankaddress2='$wbankaddress2', wbankcity='$wbankcity', wbankstate='$wbankstate', wbankcountry='$wbankcountry', wbankzip='$wbankzip', wbankaccnum='$wbankaccnum', wbankcode='$wbankcode', wbankacctype='$wbankacctype' WHERE username='$username'");
if ($withdrawnotify == "enabled") {
mail($emailaddress,"New " . $companyname . " Withdrawal Request","The $freelancer $username has just made a withdrawal request of $withdrawb by bank wire.  You can view the details at $siteurl/admin.php?pass=$adminpass");
}
echo 'Your withdrawal request has been submitted. Your account balance will be updated accordingly once the payment has been wired into your bank account by us.<br>
<a href="' . $siteurl . '/freelancers.php?manage=2">Go back...</a>
<META HTTP-EQUIV=REFRESH CONTENT="10; URL=' . $siteurl . '/freelancers.php?manage=2">';
}
}
}
}
} else if ($wtype == "other") {
$bfee = $oldb + $withdrawofee;
if ($withdraw_b>$bfee) {
echo 'Unfortunately, resulting from fees along with your current balance, you don\'t have as much money as your requested amount to withdraw.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if (!$continue) {
$prgmru = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'");
$wothercontent = SQLact("result", $prgmru,0,"wothercontent");
echo '<form method="POST" action="freelancers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="withdraw" value="money">
<input type="hidden" name="withdrawa" value="' . $withdraw_b . '">
<input type="hidden" name="wtype" value="other">
<input type="hidden" name="submit" value="Continue">

<big><b>Withdraw Money (Step 2 of 3)</b></big>
<p>
' . $withdrawoins . '<br>
<textarea rows="10" name="wothercontent" cols="46">' . $wothercontent . '</textarea><p>
<p>
<input type="submit" value="Continue" name="continue">
</form>';
} else {
if ($wothercontent == "") {
echo 'Please input a value into the ' . $withdrawonam . ' withdrawal field.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if (!$confirm) {
echo '<script>
function winErr() {
return false;
}
window.onError=winErr();

function submitonce(theform){
//if IE 4+ or NS 6+
if (document.all||document.getElementById){
//screen thru every element in the form, and hunt down "submit" and "reset"
for (i=0;i<theform.length;i++){
var tempobj=theform.elements[i]
if(tempobj.type.toLowerCase()=="submit"||tempobj.type.toLowerCase()=="reset")
//disable em
tempobj.disabled=true
}
}
}
</script>

<center>
<table width="90%" border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor=' . $tablecolor1 . '>
<b><big>Please confirm the following before withdrawing the money:</big></b>
<p>
<b>Amount: ' . $currencytype . '' . $currency . '' . $withdrawa . '</b>
<br>
<b>Fee: -' . $currencytype . '' . $currency . ' ' . $withdrawofee . '</b><br>';
$newam = $withdrawa - $withdrawofee;
echo '<b>Total: ' . $currencytype . '' . $currency . '' . $newam . '</b><p>
<b>' . $withdrawonam . ':</b><br>
' . $wothercontent . '<p>

<center>
<form method="POST" action="freelancers.php" onSubmit="submitonce(this)">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="withdraw" value="money">
<input type="hidden" name="withdrawb" value="' . $withdrawa . '">
<input type="hidden" name="wtype" value="other">
<input type="hidden" name="submit" value="Continue">
<input type="hidden" name="continue" value="Continue">
<input type="hidden" name="wothercontent" value="' . $wothercontent . '">
<input type="submit" value="Withdraw Money" name="confirm">
<p>
<small>(<A HREF="javascript:history.go(-1)">Go back</A> if you need to change anything.)
</form>
</td>
</tr>
</table></center>';
} else {
$newam = $withdrawb - $withdrawofee;
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
SQLact("query", "INSERT INTO freelancers_withdrawals (date, date2, username, atype, wtype, amount, othercontent, status, wfee, namount) VALUES ('$month/$day/$year @ $hours:$minutes', '" . time() . "', '$username', 'freelancer', 'other', '$withdrawb', '$wothercontent', '', '$withdrawofee', '$newam')");
SQLact("query", "UPDATE freelancers_programmers SET wothercontent='$wothercontent' WHERE username='$username'");
if ($withdrawnotify == "enabled") {
mail($emailaddress,"New " . $companyname . " Withdrawal Request","The $freelancer $username has just made a withdrawal request of $withdrawb by $withdrawonam.  You can view the details at $siteurl/admin.php?pass=$adminpass");
}
echo 'Your withdrawal request has been submitted. Your account balance will be updated accordingly once the ' . $withdrawonam . ' payment has been sent by us.<br>
<a href="' . $siteurl . '/freelancers.php?manage=2">Go back...</a>
<META HTTP-EQUIV=REFRESH CONTENT="10; URL=' . $siteurl . '/freelancers.php?manage=2">';
}
}
}
}
} else {
echo 'Please choose a withdrawal method to continue with the withdrawal process.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
}
}
}
} else {
echo 'You have been suspended from using ' . $companyname . '!<br>
You do not have access to manage and view services used in this section of the website!<br>
The reason for your suspension is:<br>
<b><i>';
$humanres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$rrreason2 = SQLact("result", $humanres2,0,"reason");
if ($rrreason2 == "") {
echo 'No reason was given for your suspension.';
} else {
echo $rrreason2;
}
echo '</i></b><br><br>
If you think there has been a mistake, contact ' . $emailaddress;
}
} else if ($edit == "portfolio") {
if ($userportfolio == "enabled") {
echo $toplayout;
if (!$submit) {
$arg = SQLact("result", SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'"),0,"portfolio");
if ($delete && $delete !== "") {
$rcc = explode("
", $arg);
$r=0;
for ($i=0;$i<count($rcc);$i++) {
$lem = explode("&&", $rcc[$i]);
if ($lem[0] == $delete) {
array_splice($rcc,$r,1);
}
$r++;
}
$newp = implode("
", $rcc);
SQLact("query", "UPDATE freelancers_programmers SET portfolio='$newp' WHERE username='$username'");
$arg = $newp;
echo 'Your selected portfolio-image(s) have been successfully deleted!<br><br>';
}
echo '<table border="' . $tableborder . '" cellpadding="' . $cellpadding . '" cellspacing="' . $cellspacing . '" width="100%">
<tr>
<th>ID Number</th>
<th>Image Name</th>
<th>Thumbnail</th>
<th>Delete</th>
</tr>
';
if ($arg == "") {
echo '<tr>
<td colspan="3" align="center">(No Portfolio Uploaded Yet)</td>
</tr>';
} else {
$rcc = explode("
", $arg);
$k=0;
for ($i=0;$i<count($rcc);$i++) {
$k++;
$lem = explode("&&", $rcc[$i]);
echo '<tr>
<td>' . $lem[0] . '</td>
<td>' . $lem[1] . '</td>
<td><a href="' . $siteurl . '/freelancers.php?viewportfolio=' . $viewprofile . '&enlarge=' . $lem[0] . '" target="_blank"><img src="' . $attachurl . '/' . $lem[1] . '" width="45" height="30" border="0"></a></td>
<td><a href="' . $siteurl . '/freelancers.php?manage=2&edit=portfolio&delete=' . $lem[0] . '">Delete</a></td>
</tr>
';
}
}
echo '</table>
<h3>Add to Portfolio</h3><br>
<form enctype="multipart/form-data" action="freelancers.php" method="post">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="edit" value="portfolio">
Image: <input type="file" name="upload" size="40"><br>
Short Description (optional): <textarea name="desc" cols="26" rows="4"></textarea><br>
<input type="submit" value="Add to Portfolio" name="submit">
</form>';
} else {
if ($upload_size > $attachmaxi) {
echo 'ERROR: You cannot upload an image that exceeds the upload limit of ' . $attachmaxi . '.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else if ($upload_type !== "image/gif") {
echo 'ERROR: You cannot upload a file that is not an image/gif.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
$tme = time();
$rud = $tme . '_' . $upload_name;
@copy($upload, "$attachpath/$rud");
if (SQLact("result", SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'"),0,"portfolio") == "") {
SQLact("query", "UPDATE freelancers_programmers SET portfolio='$tme&&$rud&&$desc' WHERE username='$username'");
} else {
$newr = SQLact("result", SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'"),0,"portfolio") . '
' . $tme . '&&' . $rud . '&&' . $desc;
SQLact("query", "UPDATE freelancers_programmers SET portfolio='$newr' WHERE username='$username'");
}
echo 'Your portfolio has been successfully updated!<br>
<a href="' . $siteurl . '/freelancers.php?manage=2">Go Back</a>';
}
}
} else {
header("Location: $siteurl/freelancers.php?manage=2");
}
} else {
echo $toplayout;
$result100 = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $username . "'");
$company = SQLact("result", $result100,0,"company");
$theabsoluteresss = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$nbalance = SQLact("result", $theabsoluteresss,0,"balance");
$namount = SQLact("result", $theabsoluteresss,0,"amount");
$ndetails = SQLact("result", $theabsoluteresss,0,"details");
$ndate = SQLact("result", $theabsoluteresss,0,"date");
echo '<table width="100%" border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td width="40%" valign="top">

<b><big>Account Management</big></b><br>
Welcome ' . $company . '!
<p>
<a href="' . $siteurl . '/freelancers.php?manage=2&edit=info"><img src="' . $siteurl . '/editinfo.gif" border=0 alt="Edit Info"></a><br>
';
if ($userportfolio == "enabled") {
echo '<a href="' . $siteurl . '/freelancers.php?manage=2&edit=portfolio"><img src="' . $siteurl . '/editportfolio.gif" border=0 alt="Edit Portfolio"></a><br>';
}
echo '<a href="' . $siteurl . '/freelancers.php?manage=2&deposit=money"><img src="' . $siteurl . '/depositmoney.gif" border=0 alt="Deposit Money"></a><br>
<a href="' . $siteurl . '/freelancers.php?manage=2&transfer=money"><img src="' . $siteurl . '/transfermoney.gif" border=0 alt="Transfer Money"></a><br>
<a href="' . $siteurl . '/freelancers.php?manage=2&withdraw=money"><img src="' . $siteurl . '/withdrawmoney.gif" border=0 alt="Withdraw Money"></a>
</td><td width="60%" valign="top">
<table width="100%" border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor=' . $tablecolorh . '>';
if (eregi("\-", $nbalance)) {
echo '<font color=red><b>Balance: ' . $currencytype . '' . $currency . '' . $nbalance;
} else {
echo '<font color=' . $tablecolort . '><b>Balance: ' . $currencytype . '' . $currency . '' . $nbalance;
}
echo '</td>
</tr>
<tr>
<td bgcolor=' . $tablecolor1 . '>
<small><b>Last Transaction</b><br>
Amount (' . $currencytype . '' . $currency . '): ';
if (eregi("\+", $namount)) {
echo '<font color=green>' . $namount . '</font>';
} else {
echo '<font color=red>' . $namount . '</font>';
}
echo ' <br>Description:  ' .$ndetails . '<br>
Date: ' . $ndate . '<br> <a href="' . $siteurl . '/freelancers.php?manage=2&transactions=view"><img src="' . $siteurl . '/viewall.gif" alt="View All Transactions" border=0></a>
</td></tr></table>

</td></tr>
</table>
<p>
<center>';
$hugeres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$hugerows2 = SQLact("num_rows", $hugeres2);
if ($hugerows2==0) {
echo '
';
} else {
echo 'You Are Suspended:<b><i> ';
$humanres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$rrreason2 = SQLact("result", $humanres2,0,"reason");
if ($rrreason2 == "") {
echo 'No reason was given for your suspension.';
} else {
echo $rrreason2;
}
echo '</i></b><br>
If you think there has been a mistake, contact ' . $emailaddress;
}
echo '</center>
<p>
<small>Please provide feedback on the following users:</small><br>';
$rates = SQLact("query", "SELECT * FROM freelancers_ratings WHERE status='' AND ratedby='$username' AND type2='freelancer'");
if (SQLact("num_rows", $rates)==0) {
echo '(No users to rate.)';
} else {
echo '<table border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor="' . $tablecolorh . '"><center><small><b><font color=' . $tablecolort . '>Rate ' . $buyers . '</b></td>
</tr>
';
while ($rowk=SQLact("fetch_array", $rates)) {
echo '<tr> <td bgcolor="' . $tablecolor1 . '"><a href="' . $siteurl . '/freelancers.php?manage=2&rate=' . $rowk[projid] . '">' . $rowk[username] . '</a> (' . $rowk[projname] . ')</td> </tr>
';
}
echo '</table>';
}
echo '
<p>
<table border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Project Name</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Status</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Other</td>
</tr>';
$tinyres = SQLact("query", "SELECT * FROM freelancers_bids WHERE username='" . $username . "' AND status='open' OR username='" . $username . "' AND status='frozen' ORDER BY id DESC");
$tinyrows = SQLact("num_rows", $tinyres);
if ($tinyrows==0) {
echo '<tr><td bgcolor=' . $tablecolor1 . ' colspan=4><center><small>(Only open & frozen projects are displayed here.)</td></tr>';
} else {
while ($kikrow=SQLact("fetch_array", $tinyres)) {
echo '<tr> <td bgcolor="' . $tablecolor1 . '"><small><a href="' . $siteurl . '/project.php?id=' . $kikrow[id] . '">' . $kikrow[project] . '</a>';
if ($kikrow[special] == "featured") {
echo ' <a href="' . $siteurl . '/featured.html"><img src="' . $siteurl . '/featured.gif" alt="Featured Project!" border=0>';
}
echo '</td> <td bgcolor="' . $tablecolor1 . '"><small>';
if ($kikrow[status] == "open") {
echo '<font color=green><b>' .  $kikrow[status];
} else {
echo '<font color=black><b>' .  $kikrow[status];
}
echo '</td> <td bgcolor="' . $tablecolor1 . '"><small>';
if ($kikrow[chosen] == $username) {
echo 'Bid Won: <a href="' . $siteurl . '/freelancers.php?manage=2&confirm2=' . $kikrow[id] . '">Accept/Deny Offer</a>';
} else {
echo '<a href="' . $siteurl . '/freelancers.php?manage=2&retract=' . $kikrow[id] . '">Retract Your Bid</a>';
}
echo '</td></tr>';
}
}
echo '</table> <br><a href="' . $siteurl . '/freelancers.php?manage=2&viewprojects=all"><img src="' . $siteurl . '/viewall.gif" alt="View All Projects" border=0></a>

<p>

<center>
<small>
If you want to refer people to this site, use the following URL:<br>
' . $siteurl . '/fr.php?' . $username . '
</small>
</center>';
}
}
}
}
} else if ($editconfirm && $editconfirm !== "") {
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_edittemp WHERE id='$editconfirm'"))==0) {
echo 'Your e-mail has all ready been successfully confirmed or no records were found in our databases which match the specified ID number.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
} else {
$temail = SQLact("result", SQLact("query", "SELECT * FROM freelancers_edittemp WHERE id='$editconfirm'"),0,"email");
$tusername = SQLact("result", SQLact("query", "SELECT * FROM freelancers_edittemp WHERE id='$editconfirm'"),0,"username");
SQLact("query", "UPDATE freelancers_programmers SET email='$temail' WHERE username='$tusername'");
SQLact("query", "DELETE FROM freelancers_edittemp WHERE id='$editconfirm'");
echo 'Your e-mail has been confirmed.  Thank you.';
}
} else if ($logout == "now") {
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
setcookie ("fusername", "", time() - 999999);
setcookie ("fpassword", "", time() - 999999);
echo $toplayout;
echo 'You are now logged out. Goodbye!';
} else if ($viewprofile && $viewprofile !== "") {
$viewprofile = strtolower($viewprofile);
echo $toplayout;
$uuures = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$viewprofile'");
if (SQLact("num_rows", $uuures)==0) {
echo 'ERROR: No ' . $freelancer . ' was found with that username. It\'s possible their account was deleted. Contact ' . $emailaddress . ' if you have any questions.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
echo '<table border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '" width="100%">
<tr>
<td bgcolor="' . $tablecolorh . '" colspan=2>

<table width="100%" border=' . $tableborder . ' cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td width="50%"><small><b><font color=' . $tablecolort . '>' . $freelancer . ' Info</font></td>
<td width="50%" align=right><a href="' . $siteurl . '/project.php?invite=' . $viewprofile . '"><img src="' . $siteurl . '/invite.gif" border=0 alt="Invite User"></a></td>
</tr>
</table>

</td>
</tr>
<tr>
<td bgcolor="' . $tablecolor1 . '" valign=top width="150">Username:</td>
<td bgcolor="' . $tablecolor1 . '" valign=top>' . $viewprofile;
$dddres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$viewprofile' AND special='user'");
if (SQLact("num_rows", $dddres)==0) {} else {
echo ' <a href="' . $siteurl . '/aboutspecial.html"><img src="' . $siteurl . '/special.gif" alt="Special User" border=0></a>';
}
echo '</td>
</tr>
<tr>
<td bgcolor="' . $tablecolor2 . '" valign=top width="150">Name/Company:</td>
<td bgcolor="' . $tablecolor2 . '" valign=top>' . SQLact("result", $uuures,0,"company") . '</td>
</tr>

<tr>
<td bgcolor="' . $tablecolor1 . '" valign=top width="150">Area of Expertise:</td>
<td bgcolor="' . $tablecolor1 . '" valign=top>
<ul>
';
$theress = SQLact("query", "SELECT * FROM freelancers_cats");
while ($row=SQLact("fetch_array", $theress)) {
$tttress = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $viewprofile . "' AND categories LIKE '%" . $row[categories]. "%'");
$rowssss = SQLact("num_rows", $tttress);
if ($rowssss!==0) {
echo '<li> ' . $row[categories] . '</li>
';
}
}
echo '</ul>
</td>
</tr>

<tr>
<td bgcolor="' . $tablecolor2 . '" valign=top width="150">Average Pricing:</td>
<td bgcolor="' . $tablecolor2 . '" valign=top>' . $currencytype . '' . $currency . '' . SQLact("result", $uuures,0,"rate") . '/Hour</td>
</tr>

<tr>
<td bgcolor="' . $tablecolor1 . '" valign=top width="150">Profile:</td>
<td bgcolor="' . $tablecolor1 . '" valign=top>';
$pro = SQLact("result", $uuures,0,"profile");
$pro = str_replace("  ", " &nbsp;", $pro);
$pro = str_replace("
", "<br>", $pro);
echo $pro . '</td>
</tr>

';
if ($userportfolio == "enabled") {
echo '<tr>
<td bgcolor="' . $tablecolor2 . '" valign=top width="150">Portfolio:</td>
<td bgcolor="' . $tablecolor2 . '" valign=top>';
$pro = SQLact("result", $uuures,0,"portfolio");
if ($pro == "") {
echo '(No Portfolio Uploaded Yet)';
} else {
$rcc = explode("
", $pro);
for ($i=0;$i<count($rcc);$i++) {
$lem = explode("&&", $rcc[$i]);
$tra .= '<a href="' . $siteurl . '/freelancers.php?viewportfolio=' . $viewprofile . '&enlarge=' . $lem[0] . '" target="_blank"><img src="' . $attachurl . '/' . $lem[1] . '" width="45" height="30" border="0"></a> -=- ';
}
$arch = strlen($tra);
$boink = substr($tra, 0, ($arch-5));
echo $boink;
}
echo '</td>
</tr>

';
}
echo '<tr>
<td bgcolor="' . $tablecolor2 . '" valign=top width="150">Rating:</td>
<td bgcolor="' . $tablecolor2 . '" valign=top>';
$result4 = SQLact("query", "SELECT AVG(rating) as average FROM freelancers_ratings WHERE username='$viewprofile' AND type='freelancer' AND status='rated'");
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='$viewprofile' AND type='freelancer' AND status='rated'"))==0) {
echo '<small>(No Feedback Yet)';
} else {
echo '<a href="' . $siteurl . '/feedback.php?f=' . $viewprofile . '">';
$avgratin = round(SQLact("result", $result4,0,"average"), 2);
$avgrating = explode(".", $avgratin);
for ($t2=0;$t2<$avgrating[0];$t2++) {
echo '<img src="' . $siteurl . '/star.gif" border=0 alt="' . $avgratin . '/10">';
}
$numeric2 = 10-$avgrating[0];
if ($numeric2==0) {} else {
for ($b2=0;$b2<$numeric2;$b2++) {
echo '<img src="' . $siteurl . '/star2.gif" border=0 alt="' . $avgratin . '/10">';
}
}
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='$viewprofile' AND type='freelancer' AND status='rated'"))==1) {
echo ' (<b>1</b> review)';
} else {
echo ' (<b>' . SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='$viewprofile' AND type='freelancer' AND status='rated'")) . '</b> reviews)';
}
echo '</a>';
}
echo '</td>
</tr>
</table>';
}
} else if ($viewportfolio && $viewportfolio !== "") {
if ($userportfolio == "enabled") {
echo $toplayout;
$getit = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$viewportfolio'");
if (SQLact("num_rows", $getit)==0) {
echo 'No ' . $freelancer . ' was found with the specified username.';
} else if (SQLact("result", $getit,0,"portfolio") == "") {
echo 'No ' . $freelancer . ' portfolio was found for the specified username.';
} else {
echo '<h3>' . $freelancer . ' ' . $username . ' Portfolio Image: ' . $enlarge . '</h3><br>';
$pro = SQLact("result", $getit,0,"portfolio");
$rcc = explode("
", $pro);
$r=0;
for ($i=0;$i<count($rcc);$i++) {
$lem = explode("&&", $rcc[$i]);
if ($lem[0] == $enlarge) {
echo '<img src="' . $attachurl . '/' . $lem[1] . '"><br>' . $lem[2];
$r++;
}
}
if ($r==0) {
echo 'No ' . $freelancer . ' portfolio image was found with the specified ID number.';
}
}
} else {
header("Location: $siteurl/freelancers.php?login=now");
}
} else if ($signup == "now") {
header("Location: " . $siteurl . "/freelancers.php?new=user");
} else {
header("Location: " . $siteurl . "/freelancers.php?login=now");
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
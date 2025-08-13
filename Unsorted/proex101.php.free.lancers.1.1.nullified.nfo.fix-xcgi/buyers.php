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
<form method="POST" action="buyers.php">
<input type="hidden" name="new" value="user">

<big><b>New <? echo $buyer; ?> Signup (Step 1)</b></big>
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
<a href="javascript:history.go(-1);">Go back...</a>';
} else {
$email = strtolower($email);
$number = $count+1;
SQLact("query", "UPDATE freelancers_count SET count='" . $number . "'");
SQLact("query", "INSERT INTO freelancers_temp (email, id) VALUES ('" . $email . "', '" . $number . "')");
$subject = 'Confirm E-mail for ' . $companyname;
$message = $emailheader . '
----------
Go to the following URL to continue the signup process at ' . $companyname . ':

' . $siteurl . '/buyers.php?confirm=' . $number . '
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
<form method="POST" action="buyers.php" ENCTYPE="multipart/form-data">
<input type="hidden" name="new" value="user2">
<input type="hidden" name="id" value="<? echo $confirm; ?>">
<input type="hidden" name="email" value="<? echo $email; ?>">

<big><b>New <? echo $buyer; ?> Signup (Step 2)</b></big>

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
<input type="checkbox" name="notify" value="notify"> Notify me by e-mail when a <? echo $freelancer; ?> places a bid on one of my projects, and when a message is posted in my project's message board.
<p>
<small>By pressing this button you acknowledge that you have read and agree to the <a href="<? echo $siteurl; ?>/terms.html">Terms & Conditions</a>.</small><br>
<input type="submit" value="Signup" name="submit">
<p>
</form>
<?php
} else {
echo 'ERROR: You have followed an incorrect confirmation link, or this confirmation link has already been used!
<a href="' . $siteurl . '/buyers.php?signup=now">Go back...</a>';
}
} else if ($new == "user2") {
if (!$submit) {
echo $toplayout;
echo 'ERROR: There was an error while trying to process this document because it was not accessed accordingly through the proper form.<br>
<a href="' . $siteurl . '/freelancers.php?signup=now">Go back...</a>';
} else {
if (!$username || $username == "" || !$password || $password == "" || !$name || $name == "") {
echo $toplayout;
echo 'ERROR: Your form could not be processed!  1 or more required fields/boxes were left blank or this page was not accessed appropriately through the correct form.
<a href="javascript:history.go(-1);">Go back...</a>';
} else {
$username = strtolower($username);
$password = strtolower($password);
$myresult = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $username . "'");
$num_rows100 = SQLact("num_rows", $myresult);
if ($num_rows100==0) {
setcookie ("busername", $username,time()+999999);
setcookie ("bpassword", $password,time()+999999);
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
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+$frefamount', '$buyer Referal ($username)', '$BReferrer', 'buyer', '$dadj2', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
} else if ($BReferrer == "") {
$tyress = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $FReferrer . "' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$bal = SQLact("result", $tyress,0,"balance");
setcookie ("FReferer", "", time() - 999999);
$dadj2 = $bal+$frefamount;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+$frefamount', '$buyer Referal ($username)', '$FReferrer', 'freelancer', '$dadj2', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
}
}
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+$bsbamount', 'Account Signup Bonus', '$username', 'buyer', '$bsbamount', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
SQLact("query", "DELETE FROM freelancers_temp WHERE id='" . $id . "' AND email='" . $email . "'");
SQLact("query", "INSERT INTO freelancers_webmasters (username, password, company, bidnotify, id, email, ip, special) VALUES ('" . $username . "', '" . $password . "', '" . $name . "', '" . $notify . "', '" . $id . "', '" . $email . "', '" . $REMOTE_ADDR . "', '')");
$subject = 'New ' . $companyname . ' ' . $buyer . ' Account';
$message = $emailheader . '
----------
Thanks for creating an account at ' . $companyname . '.
Your username: ' . $username . '
Your password: ' . $password . '

Keep this e-mail or write down your login and password. You will need this info to login and bid on projects at  ' . $siteurl . '/buyers.php?login=now

Thank you
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
echo 'ERROR: That ' . $buyer . ' username already exists.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
}
}
}
} else if ($new == "project") {
$hugeres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$hugerows2 = SQLact("num_rows", $hugeres2);
if ($hugerows2==0) {
if (!$submit) {
echo $toplayout;
$username = $HTTP_COOKIE_VARS["busername"];
$password = $HTTP_COOKIE_VARS["bpassword"];
?>
<form method="POST" action="buyers.php" ENCTYPE="multipart/form-data">
<input type="hidden" name="new" value="project">

<big><b>Open New Project</b></big>
<p>
<small>
Don't have an account? <a href="<? echo $siteurl; ?>/buyers.php?new=user">Signup...</a>
<br>
Forgot your password? <a href="<? echo $siteurl; ?>/buyers.php?forgot=find">Find it...</a>
</small>

<p>
<strong>Username:</strong><br>
<input type="text" name="username" value="<? echo $username; ?>" size="15">
<br>
<strong>Password:</strong><br>
<input type="password" name="password" value="<? echo $password; ?>" size="15">
<br><br><p>

<strong>Project Name:<br>
<input type="text" name="project" maxlength="30" size="35"></strong>
<p>

<b><? echo $catname; ?>:</b><br>
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
<?php
if ($pcat1 !== "") {
$cat1s = explode("***", $pcat1val);
?>
<b><? echo $pcat1; ?>:</b><br>
<select name="category1" size="1">
<?php
for ($i=0;$i<count($cat1s);$i++) {
echo '<option value="' . $cat1s[$i] . '">' . $cat1s[$i] . '</option>';
}
?>
</select>
<p>
<?php
}
if ($pcat2 !== "") {
$cat2s = explode("***", $pcat2val);
?>
<b><? echo $pcat2; ?>:</b><br>
<select name="category2" size="1">
<?php
for ($i2=0;$i2<count($cat2s);$i2++) {
 echo '<option value="' . $cat2s[$i2] . '">' . $cat2s[$i2] . '</option>';
}
?>
</select>
<p>
<?php
}
if ($pcat3 !== "") {
$cat3s = explode("***", $pcat3val);
?>
<b><? echo $pcat3; ?>:</b><br>
<select name="category3" size="1">
<?php
for ($i3=0;$i3<count($cat3s);$i3++) {
 echo '<option value="' . $cat3s[$i3] . '">' . $cat3s[$i3] . '</option>';
}
?>
</select>
<p>
<?php
}
?>

<strong>Describe the project in detail:</strong>
<br>
  <textarea rows="8" name="description" cols="52"></textarea></p>
<p>
<?php
if ($attachmode == "enabled") {
?>
<b>You can upload a file that is relevant to the project:</b>
<br>
<?php
if ($mode == "demo") {
echo 'Not available in demo!<p>';
} else {
?>
<small>(Maximum <? echo $attachmaxi; ?> bytes.)</small>
<br>
<input TYPE="file" NAME="upload" SIZE="40">
<p>
<?php
}
}
?>
<strong>Budget range for the project (optional):</strong>
<Br>
  <table border="0" cellspacing="0">
    <tr>
      <td>Minimum</td>
      <td><? echo $currencytype . '' . $currency; ?><input type="text" name="bmin" size="5"></td>
    </tr>
    <tr>
      <td>Maximum</td>
      <td><? echo $currencytype . '' . $currency; ?><input type="text" name="bmax" size="5"></td>
    </tr>
  </table>
  <p>
<b>In how many days should this project be completed?</b>
<br>
<?php
$expire9 = $projectudays;
$explod9 = explode("-", $expire9);
$i9 = count($explod9);
$i9 = $i9-1;
?>
<small>(If set anywhere from now until <? echo $explod9[$i9]; ?> days, the project will be treated as urgent.)</small><br>
<input type="text" name="cdays" value="<? echo $mprojectdays; ?>" maxlength="3" size="3"> (Maximum <? echo $mprojectdays; ?> days)
<p>
<input type="checkbox" name="featured" value="featured"> I want my project to be listed as a featured project
(<? echo $currencytype . '' . $currency . '' . $featuredcost; ?> cost).
<a href="<? echo $siteurl; ?>/featured.html" target="_blank">Click here</a> to read more about what having a featured project means.
<p>
<input type="submit" value="Submit Project" name="submit">
</form>
<?php
} else {
if ($username == "" || $password == "" || $project == "" || $description == "" || $cdays == "") {
echo $toplayout;
echo 'ERROR: You have left 1 or more required fields blank.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
} else {
if (strlen($description)<=10) {
echo $toplayout;
echo 'ERROR: You must input more than 10 characters in the description field.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
} else {
if ($cdays>$mprojectdays) {
echo $toplayout;
echo 'ERROR: The limitting days for project completion is too much, you must input ' . $mprojectdays . ' days or less.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
} else {
if (count($category)==0) {
echo $toplayout;
echo 'ERROR: You must select a minimum of 1 ' . $catname . 's.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
} else {
$bozzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username'");
$bozzyrows = SQLact("num_rows", $bozzyres);
if ($bozzyrows==0) {
echo $toplayout;
echo 'No ' . $buyer . ' account was found with the username <b>' . $username . '</b>. <br><A HREF="javascript:history.back()">Go back</A>';
} else {
$byzzyres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username' AND password='$password'");
$byzzyrows = SQLact("num_rows", $byzzyres);
if ($byzzyrows==0) {
echo $toplayout;
echo 'The password you provided is incorrect. <br><A HREF="javascript:history.back()">Go back</A>';
} else {
setcookie ("busername", $username,time()+999999);
setcookie ("bpassword", $password,time()+999999);
echo $toplayout;
if ($upload_name && $upload_name !== "") {
if ($upload_size > $attachmaxi) {
echo 'ERROR: Attachment not uploaded, file size is greater than the limit of ' . $attachmaxi . '.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
echo $bottomlayout;
exit();
} else {
$tme3 = time();
$rud = $tme3 . '_' . $upload_name;
@copy($upload, "$attachpath/$rud");
}
}
$ttoy = time();
$tttoy = date("n/j/Y @ G:i");
$query .= "SELECT * FROM freelancers_programmers WHERE ";
foreach($category as $key=>$val) {
$query .= "notify='notify' AND categories LIKE '%$val%' OR ";
$myvar .= $val . ", ";
}
stripslashes($description);
stripslashes($project);
$len=strlen($query);
$query=substr("$query", 0, ($len-4));
$emails = SQLact("query", "$query");
while ($rowz=SQLact("fetch_array", $emails)) {
$message = $emailheader . '
----------
A project was just added to ' . $companyname . ' which fits under your expertise. The name of the project is "' . $project . '" and you can view it at the following URL: ' . $siteurl . '/project.php?id=' . $ttoy . '
----------
' . $emailfooter;
mail($rowz[email],"New " . $companyname . " Project Notice",$message,"From: $emailaddress");
}
$mymess = $emailheader . '
----------
This is to confirm that your new project (' . $project . ') has been added to ' . $companyname . '

' . $freelancers . ' will now be able to bid on it. ' . SQLact("num_rows", $emails) . ' ' . $freelancers . ' have already been notified by e-mail of this project. You can login anytime to view the bids and to choose someone for this project at ' . $siteurl . '/buyers.php?login=now
----------
' . $emailfooter;
mail(SQLact("result", $bozzyres,0,"email"),"New " . $companyname . " Project: " . $project,$mymess,"From: $emailaddress");
if ($featured && $featured == "featured") {
$tyress = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$bal = SQLact("result", $tyress,0,"balance");
$dadj2 = $bal-$featuredcost;
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('-$featuredcost', 'Featured Project Fee', '$username', 'buyer', '$dadj2', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
}
SQLact("query", "INSERT INTO freelancers_projects (chosen, status, id, date2, project, special, categories, expires, budgetmin, budgetmax, creation, ctime, creator, description, attachment, pcat1val, pcat2val, pcat3val) VALUES ('', 'open', '$ttoy', '$ttoy', '$project', '$featured', '$myvar', '$cdays', '$bmin', '$bmax', '" . date("n/j/Y") . "', '" . date("h:i") . "', '$username', '$description', '$rud', '$category1', '$category2', '$category3')");
SQLact("query", "INSERT INTO freelancers_subjects (pid, subject, date, date2) VALUES ('$ttoy', '$project', '$tttoy', '$ttoy')");
echo '<center>The project named <b>' . $project . '</b> has been added to ' . $companyname . ' and can now be viewed by all ' . $freelancers . '. <a href="' . $siteurl . '/project.php?id=' . $ttoy . '">Click here</a> to view your project.';
}
}
}
}
}
}
}
} else {
echo 'You have been suspended from using ' . $companyname . '!<br>
You do not have access to create new projects used in this section of the website!<br>
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
} else if ($login == "now") {
$username = $HTTP_COOKIE_VARS["busername"];
$password = $HTTP_COOKIE_VARS["bpassword"];
if ($username == "" || $password == "") {
echo $toplayout;
if ($error==1) {
echo 'You have entered a username that couldn\'t be located in our databases, please double-check your spelling, click on the forgot username link, or make sure you even have an account.<br>';
} else if ($error==2) {
echo 'You have entered an incorrect or invalid password, please double-check your password, or click on the forgot password link if you forgot it.<br>';
} else if ($error==3) {
echo 'You have entered incorrect or invalid login information that couldn\'t be located in our databases.  However, a ' . $freelancer . ' account was found with the username you entered, <a href="' . $siteurl . '/freelancers.php?login=now&username=' . $username . '">click here</a> to login through the ' . $freelancer . ' login form.<br>';
}
?>
<br><b>Login to Account Management Area for <? echo $buyers; ?></b>
<p>
<form method="POST" action="buyers.php">
<input type="hidden" name="manage" value="1">
<strong>Username:</strong><br>
<small>(<a href="<? echo $siteurl; ?>/buyers.php?forgot=find">Click here</a> if you forgot it.)</small>
<br>
<input type="text" name="username" value="<?php
if (!$username) {} else {
echo $username;
}
?>" size="15">
<br>
<strong>Password:</strong><br>
<small>(<a href="<? echo $siteurl; ?>/buyers.php?forgot=find">Click here</a> if you forgot it.)</small>
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
<a href="<? echo $siteurl; ?>/buyers.php?new=user">Signup</a>
<?php
} else {
header("Location: " . $siteurl . "/buyers.php?manage=1&username=" . $username . "&password=" . $password);
}
} else if ($forgot == "find") {
echo $toplayout;
if (!$submit) {
?>
<form method="POST" action="buyers.php">
<input type="hidden" name="forgot" value="find">
<input type="hidden" name="hide" value="1">
<strong>Forgot your username?</strong><br>
Enter your e-mail address: <input type="text" name="forgotu" size="20">
<input type="submit" value="Find Username" name="submit">
</form>
<p>
<form method="POST" action="buyers.php">
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
$result = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE email='" . $forgotu . "'");
$num_rows = SQLact("num_rows", $result);
if ($num_rows==0) {
echo 'ERROR: No ' . $buyer . ' account was found with that e-mail address.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
$username = SQLact("result", $result,0,"username");
echo 'Your ' . $buyer . ' username is <b>' . $username . '</b>. <a href="' . $siteurl . '/buyers.php?login=now">Click here to login...</a>';
}
}
} else {
$forgotp = strtolower($forgotp);
if ($forgotp == "") {
echo 'ERROR: You have left the username field empty.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
$result = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $forgotp . "'");
$num_rows = SQLact("num_rows", $result);
if ($num_rows==0) {
echo 'ERROR: No ' . $buyer . ' account was found with that username.<br>
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
header("Location: " . $siteurl . "/buyers.php?login=now");
} else {
$username = strtolower($username);
$password = strtolower($password);
$result = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $username . "'");
$num_rows = SQLact("num_rows", $result);
if ($num_rows==0) {
$result1 = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $username . "'");
$num_rows1 = SQLact("num_rows", $result1);
if ($num_rows1==0) {
header("Location: " . $siteurl . "/buyers.php?login=now&error=1");
} else {
setcookie ("busername", $username,time()+999999);
header("Location: " . $siteurl . "/buyers.php?login=now&error=3&username=" . $username);
}
} else {
$result2 = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $username . "' AND password='" . $password . "'");
$num_rows2 = SQLact("num_rows", $result2);
if ($num_rows2==0) {
setcookie ("busername", $username,time()+999999);
header("Location: " . $siteurl . "/buyers.php?login=now&error=2&username=" . $username);
} else {
setcookie ("busername", $username,time()+999999);
setcookie ("bpassword", $password,time()+999999);
header("Location: " . $siteurl . "/buyers.php?manage=2");
}
}
}
} else if ($manage == "2") {
$username = $HTTP_COOKIE_VARS["busername"];
$password = $HTTP_COOKIE_VARS["bpassword"];
if ($username == "" || $password == "") {
header("Location: " . $siteurl . "/buyers.php?login=now");
} else {
$result = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $username . "'");
$num_rows = SQLact("num_rows", $result);
if ($num_rows==0) {
$result1 = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $username . "'");
$num_rows1 = SQLact("num_rows", $result1);
if ($num_rows1==0) {
header("Location: " . $siteurl . "/buyers.php?login=now&error=1");
} else {
header("Location: " . $siteurl . "/buyers.php?login=now&error=3&username=" . $username);
}
} else {
$result2 = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $username . "' AND password='" . $password . "'");
$num_rows2 = SQLact("num_rows", $result2);
if ($num_rows2==0) {
header("Location: " . $siteurl . "/buyers.php?login=now&error=2&username=" . $username);
} else {
$ldi = SQLact("query", "SELECT * FROM freelancers_logins WHERE username='$username' AND atype='buyer'");
$tme = time();
if (SQLact("num_rows", $ldi)==0) {
$theabsoluteresss2 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$nbalance2 = SQLact("result", $theabsoluteresss2,0,"balance");
SQLact("query", "INSERT INTO freelancers_logins (username, date, atype) VALUES ('$username', '$tme', 'buyer')");
} else {
SQLact("query", "UPDATE freelancers_logins SET date='$tme' WHERE username='$username' AND atype='buyer'");
}
$ann = SQLact("query", "SELECT * FROM freelancers_announcements");
if (SQLact("result", $ann,0,"announce2") !== "") {
$dtlt = getdate(SQLact("result", $ann,0,"date2"));
$mlt = $dtlt["mon"];
$dlt = $dtlt["mday"];
$ylt = $dtlt["year"];
$hlt = $dtlt["hours"];
$m2lt = $dtlt["minutes"];
$dtlt2 = "$mlt-$dlt-$ylt @ $hlt:$m2lt";
$tlt = '<center><b>' . $dtlt2 . '</b><br>
' . SQLact("result", $ann,0,"announce2") . '</center><br><br>';
$toplayout = $toplayout . '
' . $tlt;
}
if ($edit == "info") {
if (!$submit) {
echo $toplayout;
} else {
setcookie ("bpassword", "", time() - 999999);
setcookie ("bpassword", $newpassword,time()+999999);
echo $toplayout;
$password = $newpassword;
$theaptres2 = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $username . "'");
$nemail2 = SQLact("result", $theaptres2,0,"email");
SQLact("query", "UPDATE freelancers_webmasters SET password='" . $password . "', company='" . $name . "', bidnotify='" . $notify . "' WHERE username='" . $username . "'");
if ($email !== $nemail2) {
$ttoy = time();
SQLact("query", "INSERT INTO freelancers_edittemp (email, id, username, atype) VALUES ('$nemail2', '$ttoy', '$username', 'buyer')");
$themsg = $emailheader . '
--------------------
Go to the following URL to confirm your new e-mail address at ' . $companyname . ':

' . $siteurl . '/buyers.php?editconfirm=' . $ttoy . '
--------------------
' . $emailfooter;
mail($nemail,"Confirm E-mail for " . $companyname,$themsg,"From: $emailaddress");
echo 'Your info has been changed, but you must confirm your new e-mail address. Follow the instructions in a message sent to ' . $nemail . '<br>';
} else {
echo 'Congratulations! Your information has been successfully updated!';
}
}
$theaptres = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $username . "'");
$ncompany = SQLact("result", $theaptres,0,"company");
$nemail = SQLact("result", $theaptres,0,"email");
$nnotify = SQLact("result", $theaptres,0,"bidnotify");
echo '<form method="POST" action="buyers.php" ENCTYPE="multipart/form-data">
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
';
if ($nnotify == "") {
$tnotify = '<input type="checkbox" name="notify" value="notify"';
} else {
$tnotify = '<input type="checkbox" name="notify" value="notify" checked';
}
echo $tnotify . '> Notify me by e-mail when a ' . $freelancer . ' places a bid on one of my projects, and when a message is posted in my project\'s message board.<br>

<p><input type="submit" value="Edit" name="submit"></p>
</form>';
} else if ($viewprojects == "all") {
echo $toplayout;
echo '<b>Projects You Have Posted</b>
<p>
<table border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Project Name</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Bids</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Status</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Other</td>
</tr>';
$tinyres = SQLact("query", "SELECT * FROM freelancers_projects WHERE creator='" . $username . "' ORDER BY id DESC");
$tinyrows = SQLact("num_rows", $tinyres);
if ($tinyrows==0) {
echo '<tr><td bgcolor=' . $tablecolor1 . ' colspan=4><center><small>(no projects to display)</td></tr>';
} else {
while ($kikrow=SQLact("fetch_array", $tinyres)) {
echo '<tr> <td bgcolor="' . $tablecolor1 . '"><small><a href="' . $siteurl . '/project.php?id=' . $kikrow[id] . '">' . $kikrow[project] . '</a>';
if ($kikrow[special] == "featured") {
echo ' <a href="' . $siteurl . '/featured.html"><img src="' . $siteurl . '/featured.gif" alt="Featured Project!" border=0>';
}
echo '</td> <td bgcolor="' . $tablecolor1 . '"><small>' . SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_bids WHERE id='" . $kikrow[id] . "'")) . '</td> <td bgcolor="' . $tablecolor1 . '"><small>';
if ($kikrow[status] == "open") {
echo '<font color=green><b>' .  $kikrow[status] . '</td> <td bgcolor="' . $tablecolor1 . '"><small>
<a href="' . $siteurl . '/buyers.php?manage=2&pick=' . $kikrow[id] . '">Pick a Freelancer</a> - <a href="' . $siteurl . '/buyers.php?manage=2&extend=' . $kikrow[id] . '">Extend</a> - <a href="' . $siteurl . '/buyers.php?manage=2&close=' . $kikrow[id] . '">Close</a>
</td></tr>';
} else if ($kikrow[status] == "frozen") {
echo '<font color=black><b>' .  $kikrow[status] . '</td> <td bgcolor="' . $tablecolor1 . '"><small>
<a href="' . $siteurl . '/buyers.php?manage=2&pick=' . $kikrow[id] . '">Pick a Freelancer</a> - <a href="' . $siteurl . '/buyers.php?manage=2&extend=' . $kikrow[id] . '">Extend</a> - <a href="' . $siteurl . '/buyers.php?manage=2&close=' . $kikrow[id] . '">Close</a><br>
(awaiting response from <i>' . $kikrow[chosen] . '</i>)
</td></tr>';
} else if ($kikrow[status] == "cancelled") {
echo '<font color=red><b>cancelled</td> <td bgcolor="' . $tablecolor1 . '"><small>
(Project was cancelled)
</td></tr>';
} else {
echo '<font color=red><b>' . $kikrow[status] . '</td> <td bgcolor="' . $tablecolor1 . '"><small>
You picked <a href="' . $siteurl . '/freelancers.php?viewprofile=' . $kikrow[chosen] . '">' . $kikrow[chosen] . '</a> (click here to pay <a href="' . $siteurl . '/buyers.php?manage=2&transfer=money&transfer2=' . $kikrow[chosen] . '&tamount=' . SQLact("result", SQLact("query", "SELECT * FROM freelancers_bids WHERE id='" . $kikrow[id] . "' AND username='" . $kikrow[chosen] . "'"),0,"amount") . '">' . $kikrow[chosen] . '</a>...)
</td></tr>';
}
}
}
echo '</table>';
} else if ($transactions == "view") {
echo $toplayout;
$wowieress = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='buyer' ORDER BY date2 DESC");
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
} else if ($transfer == "money") {
echo $toplayout;
$hugeres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$hugerows2 = SQLact("num_rows", $hugeres2);
if ($hugerows2==0) {
if (!$submit && !$submit2) {
?>
<form method="POST" action="buyers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="transfer" value="money">

<big><b>Send Money to a <? echo $freelancer; ?></b></big>
<p>
<strong><? echo $freelancer; ?> Username:</strong><br>
<input type="text" name="transfer2" value="<? echo $transfer2; ?>" size="15">
<p>
<strong>How much money would you like to transfer to their account?</strong><br>
<? echo $currencytype . '' . $currency; ?><input type="text" name="tamount" value="<? echo $tamount; ?>" maxlength="7" size="6">
<p>

<input type="submit" value="Send Payment" name="submit">
</form>
<?php
} else if ($submit && $submit !== "") {
$theabsoluteresss3 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
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
$smallres = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $transfer2 . "'");
$smallrows = SQLact("num_rows", $smallres);
if ($smallrows==0) {
echo 'ERROR: No ' . $freelancer . ' account was found with that username, please go back and make sure that you correctly spelled it and that that ' . $freelancer . ' account even exists.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
?>
<center>
<table width="90%" border="<? echo $tableborder; ?>" cellspacing="<? echo $tablecellsp; ?>" cellpadding="<? echo $tablecellpa; ?>">
<tr>
<td bgcolor=<? echo $tablecolorh; ?>>
<b><big>Please confirm the following before sending the payment:</big></b>
<p>
<b><? echo $buyer; ?>: <? echo $transfer2; ?>
<br>
Amount: <? echo $currencytype . '' . $currency; ?><? echo $tamount; ?>
<br>

<center>
<form method="POST" action="buyers.php">
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
$tyress2 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $transfer3 . "' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$bal2 = SQLact("result", $tyress2,0,"balance");
$drobble = $bal2+$ramount;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+$ramount', 'Payment from " . $buyer . " ($username)', '$transfer3', 'freelancer', '$drobble', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
$tyress3 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$bal3 = SQLact("result", $tyress3,0,"balance");
$dribble = $bal3-$ramount;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('-$ramount', 'Payment to " . $frelancer . " ($transfer3)', '$username', 'buyer', '$dribble', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
$webemail = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$transfer3'");
mail(SQLact("result", $webemail,0,"email"),"Money Added to Your " . $companyname . " Account","The $buyer $username has just paid you $ramount!  The money has been added to your account.");
echo '<br><br>You have successfully transfered <b>' . $currencytype . '' . $currency . '' . $ramount . '</b> to the ' . $freelancer . ' <b>' . $transfer3 . '</b>. Your account balance has been updated accordingly.<br>
<a href="' . $siteurl . '/buyers.php?manage=2">Go back...</a>';
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
} else if ($deposit == "money") {
if (!$submit) {
echo $toplayout;
?>
<form method="POST" action="buyers.php">
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
Include a note with your username (' . $username . ') and your account type (' . $buyer . ') on it so we can credit your account accordingly.
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
SQLact("query", "INSERT INTO freelancers_deposits (username, atype, amount, total, oid, ptype, status) VALUES ('$username', 'buyer', '$deposit2', '$total', '$orderid', 'paypal', '')");
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
$depositccurl = str_replace('[type]', 'buyer', $depositccurl);
header("Location: $depositccurl");
}
SQLact("query", "INSERT INTO freelancers_deposits (username, atype, amount, total, oid, ptype, status) VALUES ('$username', 'buyer', '$deposit2', '$total', '$orderid', 'cc', '')");
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
} else if ($rate && $rate !== "") {
echo $toplayout;
$projrate = SQLact("query", "SELECT * FROM freelancers_ratings WHERE projid='$rate' AND ratedby='$username' AND status='' AND type2='buyer'");
if (SQLact("num_rows", $projrate)==0) {
echo 'ERROR: You do not have permission to rate this user at the moment.<br>
<a href="javascript:history.go(-1);">Go Back...</a>';
} else {
if (!$submit) {
echo '<form method="POST" action="buyers.php">
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
SQLact("query", "UPDATE freelancers_ratings SET status='rated', rating='$rating', comments='$comment' WHERE projid='$rate' AND type2='buyer'");
echo 'Your feedback for ' . SQLact("result", $projrate,0,"username") . ' has been recorded.<br>
<a href="' . $siteurl . '/buyers.php?manage=2">Go Back...</a>';
}
}
}
} else if ($withdraw == "money") {
echo $toplayout;
$hugeres2 = SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . $REMOTE_ADDR . "'");
$hugerows2 = SQLact("num_rows", $hugeres2);
if ($hugerows2==0) {
if (!$submit) {
?>
<form method="POST" action="buyers.php">
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
$olba = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='$username' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
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
$prgmru = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username'");
$wname = SQLact("result", $prgmru,0,"wname");
$waddress = SQLact("result", $prgmru,0,"waddress");
$wcity = SQLact("result", $prgmru,0,"wcity");
$wzip = SQLact("result", $prgmru,0,"wzip");
echo '<form method="POST" action="buyers.php">
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
<form method="POST" action="buyers.php" onSubmit="submitonce(this)">
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
SQLact("query", "INSERT INTO freelancers_withdrawals (date, date2, username, atype, wtype, amount, name, address, city, zip, status, wfee, namount) VALUES ('$month/$day/$year @ $hours:$minutes', '" . time() . "', '$username', 'buyer', 'check', '$withdrawb', '$wname', '$waddress', '$wcity', '$wzip', '', '$withdrawcfee', '$newam')");
SQLact("query", "UPDATE freelancers_webmasters SET wname='$wname', waddress='$waddress', wcity='$wcity', wzip='$wzip' WHERE username='$username'");
if ($withdrawnotify == "enabled") {
mail($emailaddress,"New " . $companyname . " Withdrawal Request","The $buyer $username has just made a withdrawal request of $withdrawb by check.  You can view the details at $siteurl/admin.php?pass=$adminpass");
}
echo 'Your withdrawal request has been submitted. Your account balance will be updated accordingly once the check has been sent by us.<br>
<a href="' . $siteurl . '/buyers.php?manage=2">Go back...</a>
<META HTTP-EQUIV=REFRESH CONTENT="10; URL=' . $siteurl . '/buyers.php?manage=2">';
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
$prgmru = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username'");
$wemail = SQLact("result", $prgmru,0,"wemail");
echo '<form method="POST" action="buyers.php">
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
<form method="POST" action="buyers.php" onSubmit="submitonce(this)">
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
SQLact("query", "INSERT INTO freelancers_withdrawals (date, date2, username, atype, wtype, amount, email, status, wfee, namount) VALUES ('$month/$day/$year @ $hours:$minutes', '" . time() . "', '$username', 'buyer', 'paypal', '$withdrawb', '$wemail', '', '$withdrawpfee', '$newam')");
SQLact("query", "UPDATE freelancers_webmasters SET wemail='$wemail' WHERE username='$username'");
if ($withdrawnotify == "enabled") {
mail($emailaddress,"New " . $companyname . " Withdrawal Request","The $buyer $username has just made a withdrawal request of $withdrawb by PayPal.  You can view the details at $siteurl/admin.php?pass=$adminpass");
}
echo 'Your withdrawal request has been submitted. Your account balance will be updated accordingly once the PayPal payment has been sent by us.<br>
<a href="' . $siteurl . '/buyers.php?manage=2">Go back...</a>
<META HTTP-EQUIV=REFRESH CONTENT="10; URL=' . $siteurl . '/buyers.php?manage=2">';
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
$prgmru = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username'");
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
echo '<form method="POST" action="buyers.php">
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
<table width="90%" border="'. $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
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
<form method="POST" action="buyers.php" onSubmit="submitonce(this)">
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
SQLact("query", "INSERT INTO freelancers_withdrawals (date, date2, username, atype, wtype, amount, bankyourname, bankname, bankaddress, bankaddress, bankaddress2, bankcity, bankstate, bankcountry, bankzip, bankaccnum, bankcode, bankacctype, status, wfee, namount) VALUES ('$month/$day/$year @ $hours:$minutes', '" . time() . "', '$username', 'buyer', 'wire', '$withdrawb', '$wbankyourname', '$wbankname', '$wbankaddress', '$wbankaddress2', '$wbankcity', '$wbankstate', '$wbankcountry', '$wbankzip', '$wbankaccnum', '$wbankcode', '$wbankacctype', '', '$withdrawwfee', '$newam')");
SQLact("query", "UPDATE freelancers_programmers SET wbankyourname='$wbankyourname' wbankname='$wbankname', wbankaddress='$wbankaddress', wbankaddress2='$wbankaddress2', wbankcity='$wbankcity', wbankstate='$wbankstate', wbankcountry='$wbankcountry', wbankzip='$wbankzip', wbankaccnum='$wbankaccnum', wbankcode='$wbankcode', wbankacctype='$wbankacctype' WHERE username='$username'");
if ($withdrawnotify == "enabled") {
mail($emailaddress,"New " . $companyname . " Withdrawal Request","The $buyer $username has just made a withdrawal request of $withdrawb by bank wire.  You can view the details at $siteurl/admin.php?pass=$adminpass");
}
echo 'Your withdrawal request has been submitted. Your account balance will be updated accordingly once the payment has been wired into your bank account by us.<br>
<a href="' . $siteurl . '/buyers.php?manage=2">Go back...</a>
<META HTTP-EQUIV=REFRESH CONTENT="10; URL=' . $siteurl . '/buyers.php?manage=2">';
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
$prgmru = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username'");
$wothercontent = SQLact("result", $prgmru,0,"wothercontent");
echo '<form method="POST" action="buyers.php">
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
<form method="POST" action="buyers.php" onSubmit="submitonce(this)">
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
SQLact("query", "INSERT INTO freelancers_withdrawals (date, date2, username, atype, wtype, amount, othercontent, status, wfee, namount) VALUES ('$month/$day/$year @ $hours:$minutes', '" . time() . "', '$username', 'buyer', 'other', '$withdrawb', '$wothercontent', '', '$withdrawofee', '$newam')");
SQLact("query", "UPDATE freelancers_programmers SET wothercontent='$wothercontent' WHERE username='$username'");
if ($withdrawnotify == "enabled") {
mail($emailaddress,"New " . $companyname . " Withdrawal Request","The $buyer $username has just made a withdrawal request of $withdrawb by $withdrawonam.  You can view the details at $siteurl/admin.php?pass=$adminpass");
}
echo 'Your withdrawal request has been submitted. Your account balance will be updated accordingly once the ' . $withdrawonam . ' payment has been sent by us.<br>
<a href="' . $siteurl . '/buyers.php?manage=2">Go back...</a>
<META HTTP-EQUIV=REFRESH CONTENT="10; URL=' . $siteurl . '/buyers.php?manage=2">';
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
} else if ($close && $close !== "") {
echo $toplayout;
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$close' AND creator='$username'"))==0) {
echo 'No project of yours was found with the specified ID number.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
} else {
if (!$submit) {
echo '<center>
<form method="POST" action="buyers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="close" value="' . $close . '">
Are you sure you want to close the project named <b>' . SQLact("result", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$close'"),0,"project") . '</b>?
<br>
<input type="submit" value="Yes, Close it!" name="submit">
<br>
<font color=red>Caution: ' . $freelancers . ' will not be able to bid on the project anymore, and you won\'t be able to pick a ' . $freelancer . '.
You also will NOT be able to re-open this project, it will be archived for reference.</font>
</form>
</center>';
} else {
SQLact("query", "UPDATE freelancers_projects SET status='cancelled' WHERE id='$close'");
SQLact("query", "UPDATE freelancers_bids SET status='cancelled' WHERE id='$close'");
echo 'The project named <b>' . SQLact("result", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$close'"),0,"project") . '</b> has been closed.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
}
}
} else if ($extend && $extend !== "") {
echo $toplayout;
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$extend' AND creator='$username'"))==0) {
echo 'No project of yours was found with the specified ID number.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
} else {
if (!$submit) {
echo '<form method="POST" action="buyers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="extend" value="' . $extend . '">
Extend this project by
<input type="text" name="cdays" value="' . $maxextend . '" maxlength="3" size="3">
days (max ' . $maxextend . ')...
<input type="submit" value="Extend" name="submit">
</form>';
} else {
$ii = SQLact("result", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$extend'"),0,"expires")+$cdays;
if ($ii>$mprojectdays) {
echo 'You cannot extend this project by that much, the maximum for total project time is ' . $mprojectdays . ' days, sorry.';
} else {
SQLact("query", "UPDATE freelancers_projects SET expires=expires+$cdays WHERE id='$extend'");
echo 'The project named <b>' . SQLact("result", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$extend'"),0,"project") . '</b> has been extended by ' . $cdays . ' days.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
}
}
}
} else if ($pick && $pick !== "") {
echo $toplayout;
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$pick' AND creator='$username'"))==0) {
echo 'No project of yours was found with the specified ID number.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
} else {
if (!$submit) {
echo '<big><b>Pick A ' . $freelancer . '</b></big>
<br>
Project: <b>' . SQLact("result", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$pick'"),0,"project") . '</b>
<p>
If you are ready to select a ' . $freelancer . ' for your project, follow the instructions below.
 Once you press the button the ' . $freelancer . ' will be notified of your choice,
 and this project will be "frozen" so no other bids can be placed on it.
 They will FIRST have to accept the offer before anything is finalized.
 If the ' . $freelancer . ' does accept, you will both be put in contact to begin your project.
 The project will then be closed and archived. If the ' . $freelancer . ' does not respond to your offer, you can choose a new one.
 <p>
<form method="POST" action="buyers.php">
<input type="hidden" name="manage" value="2">
<input type="hidden" name="pick" value="' . $pick . '">
<input type="hidden" name="submit" value="select">

<small>Select a ' . $freelancer . ' and press this button...</small><br>
<input type="submit" value="Select ' . $freelancer . '">
<p>
<table width="100%" border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Pick</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>' . $freelancer . '</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Bid</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Delivery Within</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Time of Bid</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '><center>Reviews</td>
</tr>';
$rez = SQLact("query", "SELECT * FROM freelancers_bids WHERE id='$pick'");
if (SQLact("num_rows", $rez)==0) {
echo '<tr>
<td bgcolor="' . $tablecolor1 . '">(No bids yet.)</td>
</tr>';
} else {
while ($row=SQLact("fetch_array", $rez)) {
echo '<tr>
<td bgcolor="' . $tablecolor1 . '"><input type="radio" value="' . $row[username] . '" name="chosen"></td>
<td bgcolor="' . $tablecolor1 . '"><a href="' . $siteurl . '/freelancers.php?viewprofile=' . $row[username] . '">' . $row[username] . '</a></td>
<td bgcolor="' . $tablecolor1 . '">' . $currencytype . '' . $currency . '' . $row[amount] . '</td>
<td bgcolor="' . $tablecolor1 . '">' . $row[delivery] . ' day(s)</td>
<td bgcolor="' . $tablecolor1 . '">' . $row[date] . '</td>
<td bgcolor="' . $tablecolor1 . '" colspan="6"><center>';
$result4 = SQLact("query", "SELECT AVG(rating) as average FROM freelancers_ratings WHERE username='" . $row[username] . "' AND type='freelancer' AND status='rated'");
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='" . $row[username] . "' AND type='freelancer' AND status='rated'"))==0) {
echo '<small>(No Feedback Yet)';
} else {
echo '<a href="' . $siteurl . '/feedback.php?f=' . $row[username] . '">';
$avgratin = round($row4[average], 2);
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
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='" . $row[username] . "' AND type='freelancer' AND status='rated'"))==1) {
echo ' (<b>1</b> review)';
} else {
echo ' (<b>' . SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_ratings WHERE username='" . $row[username] . "' AND type='freelancer' AND status='rated'")) . '</b> reviews)';
}
echo '</a>';
}
echo '</td>
</tr>
<tr>
<td bgcolor="' . $tablecolor1 . '" colspan="6">' . $row[details];
}
echo '</td>
</tr>';
}
echo '<tr>
<td bgcolor="' . $tablecolor1 . '" colspan=6><small></td>
</tr>
</table>
<p>
<small>Select a ' . $freelancer . ' and press this button...</small><br>
<input type="submit" value="Select ' . $freelancer . '">
</form>';
} else {
if (!$chosen) {
echo 'You must first select a ' . $freelancer . '.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
} else {
if (get_magic_quotes_gpc()) {
if (count($HTTP_GET_VARS)!==0) {
for (reset($HTTP_GET_VARS); list($k, $v) = each($HTTP_GET_VARS); )
$$k = stripslashes($v);
}
if (count($HTTP_POST_VARS)!==0) {
for (reset($HTTP_POST_VARS); list($k, $v) = each($HTTP_POST_VARS); )
$$k = stripslashes($v);
}
if (count($HTTP_COOKIE_VARS)!==0) {
for (reset($HTTP_COOKIE_VARS); list($k, $v) = each($HTTP_COOKIE_VARS); )
$$k = stripslashes($v);
}
}
SQLact("query", "UPDATE freelancers_projects SET chosen='$chosen', status='frozen' WHERE id='$pick'");
SQLact("query", "UPDATE freelancers_bids SET chosen='$chosen', status='frozen' WHERE id='$pick'");
$msg = $emailheader . '
--------------------
You were chosen for the project named "' . SQLact("result", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$pick'"),0,"project") . '".

Important: You must first accept (or deny) this offer by going to the following URL, logging in, and confirming this project: ' . $siteurl . '/freelancers.php?login=now
If you wait too long another ' . $freelancer . ' could be chosen! So accept the bid now.

If you have any problems with this step you can contact ' . $emailaddress . '
--------------------
' . $emailfooter;
mail(SQLact("result", SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$chosen'"),0,"email"),$companyname . " Project Bid Won",$msg,"From: $emailaddress");
echo 'The ' . $freelancer . ' <b>' . $chosen . '</b> has been notified of your choice
and the project named <b>' . SQLact("result", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$pick'"),0,"project") . '</b> has been "frozen" temporarily.
You will be e-mailed when they accept (or deny) the offer.
You can choose a new ' . $freelancer . ' anytime if <b>' . $chosen . '</b> does not respond.
If you do, only the new ' . $freelancer . ' will be able to accept the offer.
<br>
<a href="' . $siteurl . '/buyers.php?manage=2">Go back...</a>';
}
}
}
} else {
echo $toplayout;
$result100 = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $username . "'");
$company = SQLact("result", $result100,0,"company");
$theabsoluteresss = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$nbalance = SQLact("result", $theabsoluteresss,0,"balance");
$namount = SQLact("result", $theabsoluteresss,0,"amount");
$ndetails = SQLact("result", $theabsoluteresss,0,"details");
$ndate = SQLact("result", $theabsoluteresss,0,"date");
echo '<table width="100%" border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="0">
<tr>
<td width="40%" valign="top">

<b><big>Account Management</big></b><br>
Welcome ' . $company . '!
<p>
<a href="' . $siteurl . '/buyers.php?manage=2&edit=info"><img src="' . $siteurl . '/editinfo.gif" border=0 alt="Edit Info"></a><br>
<a href="' . $siteurl . '/buyers.php?manage=2&deposit=money"><img src="' . $siteurl . '/depositmoney.gif" border=0 alt="Deposit Money"></a><br>
<a href="' . $siteurl . '/buyers.php?manage=2&transfer=money"><img src="' . $siteurl . '/transfermoney.gif" border=0 alt="Transfer Money"></a><br>
<a href="' . $siteurl . '/buyers.php?manage=2&withdraw=money"><img src="' . $siteurl . '/withdrawmoney.gif" border=0 alt="Withdraw Money"></a>
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
Date: ' . $ndate . '<br> <a href="' . $siteurl . '/buyers.php?manage=2&transactions=view"><img src="' . $siteurl . '/viewall.gif" alt="View All Transactions" border=0></a>
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
$rates = SQLact("query", "SELECT * FROM freelancers_ratings WHERE status='' AND ratedby='$username' AND type2='buyer'");
if (SQLact("num_rows", $rates)==0) {
echo '(No users to rate.)';
} else {
echo '<table border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor="' . $tablecolorh . '"><center><small><b><font color=' . $tablecolort . '>Rate ' . $freelancers . '</b></td>
</tr>
';
while ($rowk=SQLact("fetch_array", $rates)) {
echo '<tr> <td bgcolor="' . $tablecolor1 . '"><a href="' . $siteurl . '/buyers.php?manage=2&rate=' . $rowk[projid] . '">' . $rowk[username] . '</a> (' . $rowk[projname] . ')</td> </tr>
';
}
echo '</table>';
}
echo '
<p>
<table border="' . $tableborder . '" cellspacing="' . $tablecellsp . '" cellpadding="' . $tablecellpa . '">
<tr>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Project Name</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Bids</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Status</td>
<td bgcolor="' . $tablecolorh . '"><small><b><font color=' . $tablecolort . '>Other</td>
</tr>';
$tinyres = SQLact("query", "SELECT * FROM freelancers_projects WHERE creator='" . $username . "' AND status='open' OR creator='" . $username . "' AND status='frozen' ORDER BY id DESC");
$tinyrows = SQLact("num_rows", $tinyres);
if ($tinyrows==0) {
echo '<tr><td bgcolor=' . $tablecolor1 . ' colspan=4><center><small>(Only open & frozen projects are displayed here.)</td></tr>';
} else {
while ($kikrow=SQLact("fetch_array", $tinyres)) {
echo '<tr> <td bgcolor="' . $tablecolor1 . '"><small><a href="' . $siteurl . '/project.php?id=' . $kikrow[id] . '">' . $kikrow[project] . '</a>';
if ($kikrow[special] == "featured") {
echo ' <a href="' . $siteurl . '/featured.html"><img src="' . $siteurl . '/featured.gif" alt="Featured Project!" border=0>';
}
echo '</td> <td bgcolor="' . $tablecolor1 . '"><small>' . SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_bids WHERE id='" . $kikrow[id] . "'")) . '</td> <td bgcolor="' . $tablecolor1 . '"><small>';
if ($kikrow[status] == "open") {
echo '<font color=green><b>' .  $kikrow[status] . '</td> <td bgcolor="' . $tablecolor1 . '"><small>
<a href="' . $siteurl . '/buyers.php?manage=2&pick=' . $kikrow[id] . '">Pick a Freelancer</a> - <a href="' . $siteurl . '/buyers.php?manage=2&extend=' . $kikrow[id] . '">Extend</a> - <a href="' . $siteurl . '/buyers.php?manage=2&close=' . $kikrow[id] . '">Close</a>
</td></tr>';
} else {
echo '<font color=black><b>' .  $kikrow[status] . '</td> <td bgcolor="' . $tablecolor1 . '"><small>
<a href="' . $siteurl . '/buyers.php?manage=2&pick=' . $kikrow[id] . '">Pick a Freelancer</a> - <a href="' . $siteurl . '/buyers.php?manage=2&extend=' . $kikrow[id] . '">Extend</a> - <a href="' . $siteurl . '/buyers.php?manage=2&close=' . $kikrow[id] . '">Close</a><br>
(awaiting response from <i>' . $kikrow[chosen] . '</i>)
</td></tr>';
}
}
}
echo '</table> <br><a href="' . $siteurl . '/buyers.php?manage=2&viewprojects=all"><img src="' . $siteurl . '/viewall.gif" alt="View All Projects" border=0></a>

<p>

<center>
<small>
If you want to refer people to this site, use the following URL:<br>
' . $siteurl . '/br.php?' . $username . '
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
SQLact("query", "UPDATE freelancers_webmasters SET email='$temail' WHERE username='$tusername'");
SQLact("query", "DELETE FROM freelancers_edittemp WHERE id='$editconfirm'");
echo 'Your e-mail has been confirmed.  Thank you.';
}
} else if ($logout == "now") {
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
setcookie ("busername", "", time() - 999999);
setcookie ("bpassword", "", time() - 999999);
echo $toplayout;
echo 'You are now logged out. Goodbye!';
} else if ($signup == "now") {
header("Location: " . $siteurl . "/buyers.php?new=user");
} else {
header("Location: " . $siteurl . "/buyers.php?login=now");
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

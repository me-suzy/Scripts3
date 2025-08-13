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

if (!$pass) {
echo '<center>
  <form action="admin.php" method="post">
    Password: <input type="password" name="pass"> <input type="submit" value="Login">
  </form>
<br><br>An xCGI realease nulled by drew010
</center>';
} else if ($pass !== $adminpass) {
echo '<center>Password Incorrect!<br><br>
An xCGI release nulled by drew010</center>';
} else if ($adminpass == "") {
echo '<center>
  <a href="admin.php">admin.php</a> will not run until the required variables are setup in <a href="admin_setup.php">admin_setup.php</a><br>
  <a href="admin_setup.php">Click here</a> to setup the required variables now.
</center>';
} else {
if ($bug == "report") {
if (get_magic_quotes_gpc()) {
for (reset($HTTP_GET_VARS); list($k, $v) = each($HTTP_GET_VARS); )
$$k = stripslashes($v);
for (reset($HTTP_POST_VARS); list($k, $v) = each($HTTP_POST_VARS); )
$$k = stripslashes($v);
for (reset($HTTP_COOKIE_VARS); list($k, $v) = each($HTTP_COOKIE_VARS); )
$$k = stripslashes($v);
}
mail("dddddd@ddddddd.com","PHP Freelancers Bug Report",$details,"From: nully@null.com");
echo 'Your bug report has been successfully sent to ProEx101 Web Services.<br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else if ($email == "list") {
if (get_magic_quotes_gpc()) {
for (reset($HTTP_GET_VARS); list($k, $v) = each($HTTP_GET_VARS); )
$$k = stripslashes($v);
for (reset($HTTP_POST_VARS); list($k, $v) = each($HTTP_POST_VARS); )
$$k = stripslashes($v);
for (reset($HTTP_COOKIE_VARS); list($k, $v) = each($HTTP_COOKIE_VARS); )
$$k = stripslashes($v);
}
if ($sendemail == "all") {
$ems = SQLact("query", "SELECT * FROM freelancers_programmers");
while ($row=SQLact("fetch_array", $ems)) {
mail($row[email],$send_subject,$send_message,"From: $emailaddress");
}
$ems2 = SQLact("query", "SELECT * FROM freelancers_webmasters");
while ($row2=SQLact("fetch_array", $ems2)) {
mail($row2[email],$send_subject,$send_message,"From: $emailaddress");
}
echo 'Your message has been successfully sent to all users.<br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else if ($sendemail == "freelancer") {
$ems = SQLact("query", "SELECT * FROM freelancers_programmers");
while ($row=SQLact("fetch_array", $ems)) {
mail($row[email],$send_subject,$send_message,"From: $emailaddress");
}
echo 'Your message has been successfully sent to all ' . $freelancers . '.<br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else {
$ems = SQLact("query", "SELECT * FROM freelancers_webmasters");
while ($row=SQLact("fetch_array", $ems)) {
mail($row[email],$send_subject,$send_message,"From: $emailaddress");
}
echo 'Your message has been successfully sent to all ' . $buyers . '.<br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
}
} else if ($mail == "list") {
if ($emaillist == "all") {
$ems = SQLact("query", "SELECT * FROM freelancers_programmers");
while ($row=SQLact("fetch_array", $ems)) {
echo $row[email] . '<br>';
}
$ems2 = SQLact("query", "SELECT * FROM freelancers_webmasters");
while ($row2=SQLact("fetch_array", $ems2)) {
echo $row2[email] . '<br>';
}
echo '<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else if ($emaillist == "freelancer") {
$ems = SQLact("query", "SELECT * FROM freelancers_programmers");
while ($row=SQLact("fetch_array", $ems)) {
echo $row[email] . '<br>';
}
echo '<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else {
$ems = SQLact("query", "SELECT * FROM freelancers_webmasters");
while ($row=SQLact("fetch_array", $ems)) {
echo $row[email] . '<br>';
}
echo '<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
}
} else if ($forum == "boards") {
setcookie ("adminforum", $adminpass,time()+999999);
header("Location: $siteurl/forum.php");
} else if ($announce && $announce !== "") {
$tme = time();
if ($atype == "all") {
SQLact("query", "UPDATE freelancers_announcements SET date='$tme', date2='$tme', announce='$announce', announce2='$announce'");
echo 'SUCCESS: Your announcement has been made on ALL accounts management pages!<br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else if ($atype == "freelancer") {
SQLact("query", "UPDATE freelancers_announcements SET date='$tme', announce='$announce'");
echo 'SUCCESS: Your announcement has been made on ALL ' . $freelancer . ' accounts management pages!<br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else if ($atype == "buyer") {
SQLact("query", "UPDATE freelancers_announcements SET date2='$tme', announce2='$announce'");
echo 'SUCCESS: Your announcement has been made on ALL ' . $buyer . ' account management pages!<br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
}
} else if ($loggedin == "Display") {
$tme = time();
$tme2 = $tme-(86400*$inactive);
echo '<title>Inactive Accounts</title>
<b>Accounts which have been inactive for over ' . $inactive . ' days:</b>
<p>
';
if (!$massdelete) {} else {
if (count($massdusers)==0) {
echo 'ERROR: You must select at least one user to delete!
<p>
';
} else {
foreach ($massdusers as $key=>$val) {
if (!eregi("freelancer", $val)) {
$deluser = explode("|", $val);
SQLact("query", "DELETE FROM freelancers_webmasters WHERE username='" . $deluser[0] . "'");
} else {
$deluser = explode("|", $val);
SQLact("query", "DELETE FROM freelancers_programmers WHERE username='" . $deluser[0] . "'");
}
}
echo 'SUCCESS: The selected account(s) have been successfully deleted!
<p>
';
}
}
echo '<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="hidden" name="loggedin" value="Display">
<input type="submit" value="Delete" name="massdelete"><Br>
<small>(Check the boxes below and press this button to delete the accounts.)</small><p>
<table>
<tr>
<td>&nbsp;</td>
<td><b>Username</td>
<td><b>Days Inactive</td>
<td><b>Last Login</td>
<td><b>Account Type</td>
<td><b>Balance</td>
</tr>
';
$logins = SQLact("query", "SELECT * FROM freelancers_programmers");
while ($row=SQLact("fetch_array", $logins)) {
$logins2 = SQLact("query", "SELECT * FROM freelancers_logins WHERE username='" . $row[username] . "' AND atype='freelancer'");
$theabsoluteresss = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $row[username] . "' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$nbalance = SQLact("result", $theabsoluteresss,0,"balance");
if (SQLact("num_rows", $logins2)==0) {
echo '<tr>
<td><input type="checkbox" name="massdusers[]" value="' . $row[username] . '|freelancer"></td>
<td><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $row[username] . '&atype=freelancer">' . $row[username] . '</a></td>
<td>(Never logged in)</td>
<td>N/A</td>
<td>' . $freelancer . '</td>
<td>$' . $nbalance . '</td>
</tr>';
} else {
if (SQLact("result", $logins2)<=$tme2) {
$elapsed = $tme-SQLact("result", $logins2,0,"date");
$days = $elapsed/86400;
$days = round($days);
$left = $elapsed - ($days*86400);
$hours = $left/3600;
$hours = round($hours);
$left2 = $left - ($hours*3600);
$minutes = round($left2/60);
$dtlt = getdate(SQLact("result", $logins2,0,"date"));
$mlt = $dtlt["mon"];
$dlt = $dtlt["mday"];
$ylt = $dtlt["year"];
$hlt = $dtlt["hours"];
$m2lt = $dtlt["minutes"];
$dtlt2 = "$mlt-$dlt-$ylt @ $hlt:$m2lt";
echo '<tr>
<td><input type="checkbox" name="massdusers[]" value="' . $row[username] . '|freelancer"></td>
<td><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $row[username] . '&atype=freelancer">' . $row[username] . '</a></td>
<td>' . $days . ' days, ' . $hours . ' hours, and ' . $minutes . ' minutes</td>
<td>' . $dtlt2 . '</td>
<td>' . $freelancer . '</td>
<td>$' . $nbalance . '</td>
</tr>';
}
}
}
$logins9 = SQLact("query", "SELECT * FROM freelancers_webmasters");
while ($row9=SQLact("fetch_array", $logins9)) {
$logins29 = SQLact("query", "SELECT * FROM freelancers_logins WHERE username='" . $row9[username] . "' AND atype='buyer'");
$theabsoluteresss9 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $row9[username] . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$nbalance9 = SQLact("result", $theabsoluteresss9,0,"balance");
if (SQLact("num_rows", $logins29)==0) {
echo '<tr>
<td><input type="checkbox" name="massdusers[]" value="' . $row9[username] . '|buyer"></td>
<td><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $row9[username] . '&atype=buyer">' . $row9[username] . '</a></td>
<td>(Never logged in)</td>
<td>N/A</td>
<td>' . $buyer . '</td>
<td>$' . $nbalance9 . '</td>
</tr>';
} else {
if (SQLact("result", $logins29)<=$tme2) {
$elapsed9 = $tme-SQLact("result", $logins29,0,"date");
$days9 = $elapsed9/86400;
$days9 = round($days9);
$left9 = $elapsed9 - ($days9*86400);
$hours9 = $left9/3600;
$hours9 = round($hours9);
$left29 = $left9 - ($hours9*3600);
$minutes9 = round($left29/60);
$dtlt9 = getdate(SQLact("result", $logins29,0,"date"));
$mlt9 = $dtlt9["mon"];
$dlt9 = $dtlt9["mday"];
$ylt9 = $dtlt9["year"];
$hlt9 = $dtlt9["hours"];
$m2lt9 = $dtlt9["minutes"];
$dtlt29 = "$mlt9-$dlt9-$ylt9 @ $hlt9:$m2lt9";
echo '<tr>
<td><input type="checkbox" name="massdusers[]" value="' . $row9[username] . '|buyer"></td>
<td><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $row9[username] . '&atype=buyer">' . $row9[username] . '</a></td>
<td>' . $days9 . ' days, ' . $hours9 . ' hours, and ' . $minutes9 . ' minutes</td>
<td>' . $dtlt29 . '</td>
<td>' . $buyer . '</td>
<td>$' . $nbalance9 . '</td>
</tr>';
}
}
}
echo '</table><br><input type="submit" value="Delete" name="massdelete"></form>
<p><br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else if ($search_projects && $search_projects !== "") {
$projects = SQLact("query", "SELECT * FROM freelancers_projects WHERE id LIKE '%$search_projects%' OR creator LIKE '%$search_projects%' OR project LIKE '%$search_projects%' OR status LIKE '%$search_projects%'");
if (SQLact("num_rows", $projects)==0) {
echo 'No projects were found with your specified search terms.
<p><br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else {
echo 'Search Results for: <b>' . $search_projects . '</b><br>
<ul>
';
while ($row=SQLact("fetch_array", $projects)) {
echo '<li>
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewproject=' . $row[id] . '">' . $row[project] . '</a>
</li>
';
}
echo '</ul>
<p>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
}
} else if ($viewproject && $viewproject !== "") {
echo '<center>
<big><big>Project: <b>' . SQLact("result", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$viewproject'"),0,"project") . '</b></big></big>
<p>
<a href="' . $siteurl . '/project.php?id=' . $viewproject . '">Click here to view the project...</a>
<p><br>';
if (SQLact("result", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$viewproject'"),0,"status") == "open" || SQLact("result", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$viewproject'"),0,"status") == "frozen") {
echo '<form method="POST" action="admin.php">
<input type="hidden" name="closeproject" value="' . $viewproject . '">
<input type="hidden" name="pass" value="' . $pass . '">
<table cellpadding=3>
<tr><td bgcolor=red>
<input type="submit" value="Close Project" name="B1">
</td></tr>
</table>
</form>';
}
} else if ($closeproject && $closeproject !== "") {
SQLact("query", "UPDATE freelancers_projects SET status='cancelled' WHERE id='$closeproject'");
echo 'The selected project was successfully closed.
<p>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else if ($search && $search !== "") {
$users = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username LIKE '%$search%' OR password LIKE '%$search%' OR email LIKE '%$search%' OR company LIKE '%$search%'");
$users2 = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username LIKE '%$search%' OR password LIKE '%$search%' OR email LIKE '%$search%' OR company LIKE '%$search%'");
if (SQLact("num_rows", $users)==0 && SQLact("num_rows", $users2)==0) {
echo 'No users were found with your specified search terms.
<p><br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else {
echo 'Search Results for: <b>' . $search . '</b><br>
<ul>
';
while ($row=SQLact("fetch_array", $users)) {
echo '<li>
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $row[username] . '&atype=freelancer">' . $row[username] . '</a>
</li>
';
}
while ($row2=SQLact("fetch_array", $users2)) {
echo '<li>
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $row2[username] . '&atype=buyer">' . $row2[username] . '</a>
</li>
';
}
echo '</ul>
<p>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
}
} else if ($view == "buyers") {
echo '<b>List of ' . $buyers . '</b>
<p>
<small><a href="' . $siteurl . '/admin.php?pass=' . $pass . '">Back to main</a></small>
<p>

<table>
<tr>
<td><b>Username</td>
<td><b>Balance</td>
</tr>';
$users = SQLact("query", "SELECT * FROM freelancers_webmasters");
while ($row=SQLact("fetch_array", $users)) {
$theabsoluteresss2 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $row[username] . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$userbal = SQLact("result", $theabsoluteresss2,0,"balance");
echo '<tr>
<td><a href="' . $siteurl . '/admin.php?viewmember=' . $row[username] . '&atype=buyer&pass=' . $pass . '">' . $row[username] . '</a></td>
<td><a href="' . $siteurl . '/admin.php?wtrans=' . $row[username] . '&atype=buyer&pass=' . $pass . '">' . $currencytype . '' . $currency . '' . $userbal . '</a></td>
</tr>
';
}
echo '</table>';
} else if ($view == "freelancers") {
echo '<b>List of ' . $freelancers . '</b>
<p>
<small><a href="' . $siteurl . '/admin.php?pass=' . $pass . '">Back to main</a></small>
<p>

<table>
<tr>
<td><b>Username</td>
<td><b>Balance</td>
</tr>';
$users = SQLact("query", "SELECT * FROM freelancers_programmers");
while ($row=SQLact("fetch_array", $users)) {
$theabsoluteresss2 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $row[username] . "' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$userbal = SQLact("result", $theabsoluteresss2,0,"balance");
echo '<tr>
<td><a href="' . $siteurl . '/admin.php?viewmember=' . $row[username] . '&atype=freelancer&pass=' . $pass . '">' . $row[username] . '</a></td>
<td><a href="' . $siteurl . '/admin.php?wtrans=' . $row[username] . '&atype=freelancer&pass=' . $pass . '">' . $currencytype . '' . $currency . '' . $userbal . '</a></td>
</tr>
';
}
echo '</table>';
} else if ($wtrans && $wtrans !== "") {
$wowieress = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $wtrans . "' AND type='$atype' ORDER BY date2 DESC");
echo '<b>Transaction Record</b>
<p>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
<td bgcolor="84BCF4"><b><font color=000080>Amount</td>
<td bgcolor="84BCF4"><b><font color=000080>Description</td>
<td bgcolor="84BCF4"><b><font color=000080>Date</td>
</tr>';
while ($rower=SQLact("fetch_array", $wowieress)) {
echo '<tr>
<td bgcolor="D6E4FE">';
if (eregi("\+", $rower[amount])) {
echo '<font color=green>' . $rower[amount] . '</font>';
} else {
echo '<font color=red>' . $rower[amount] . '</font>';
$ref = eregi("^Project \(<a href=\"$siteurl\/project\.php\?id=.*", $rower[details]);
if ($ref) {
$ref = str_replace('Project (<a href="' . $siteurl . '/project.php?id=', '', $ref);
echo ' <a href="' . $siteurl . '/admin.php?pass=' . $pass . '&refund=' . $ref . '">(Refund It...)</a>';
}
}
echo '</td>
<td bgcolor="D6E4FE">' . $rower[details] . '</td>
<td bgcolor="D6E4FE">' . $rower[date] . '</td>
</tr>';
}
echo '</table>';
} else if ($view == "profits") {
if ($all == "yes") {
$show = 'All Records';
$query = 'SELECT * FROM freelancers_profits';
$t = '';
} else if ($report == "profits") {
if ($month==1) $show = 'January ' . $year;
if ($month==2) $show = 'February ' . $year;
if ($month==3) $show = 'March ' . $year;
if ($month==4) $show = 'April ' . $year;
if ($month==5) $show = 'May ' . $year;
if ($month==6) $show = 'June ' . $year;
if ($month==7) $show = 'July ' . $year;
if ($month==8) $show = 'August ' . $year;
if ($month==9) $show = 'September ' . $year;
if ($month==10) $show = 'October ' . $year;
if ($month==11) $show = 'November ' . $year;
if ($month==12) $show = 'December ' . $year;
$query = 'SELECT * FROM freelancers_profits WHERE month=\'' . $month . '\' AND year=\'' . $year . '\'';
$t = '<br><small><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&view=profits&all=yes">View All Records...</a></small>';
} else {
$show = date("F Y");
$query = 'SELECT * FROM freelancers_profits WHERE month=\'' . date("n") . '\' AND year=\'' . date("Y") . '\'';
$t = '<br><small><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&view=profits&all=yes">View All Records...</a></small>';
}
$prorec = SQLact("query", $query);
$dcc=0;
$dpp=0;
$dother=0;
$wpp=0;
$wc=0;
$ww=0;
$wother=0;
while ($row=SQLact("fetch_array", $prorec)) {
if ($row[type] == "deposit" && $row[type2] == "cc") $dcc+=$row[amount];
if ($row[type] == "deposit" && $row[type2] == "paypal") $dpp+=$row[amount];
if ($row[type] == "deposit" && $row[type2] == "other") $dother+=$row[amount];
if ($row[type] == "withdrawal" && $row[type2] == "paypal") $wpp+=$row[amount];
if ($row[type] == "withdrawal" && $row[type2] == "check") $wc+=$row[amount];
if ($row[type] == "withdrawal" && $row[type2] == "wire") $ww+=$row[amount];
if ($row[type] == "withdrawal" && $row[type2] == "other") $wother+=$row[amount];
}
$dsub = $dcc+$dpp+$dother;
$wsub = $wpp+$wc+$ww+$wother;
$profit = $dsub+$wsub;
echo '<center><big><big><b>Profit Report</b></big></big><br>
(' . $show . ')' . $t . '
<p>
<table>
<tr>
<td></td>
<td><u>Deposits</u></td>
</tr>

<tr>
<td><b>Credit Card</b></td>
<td bgcolor=EAEAEA>' . $currencytype . '' . $currency . '' . $dcc . '</td>
</tr>

<tr>
<td><b>PayPal</b></td>
<td bgcolor=EAEAEA>' . $currencytype . '' . $currency . '' . $dpp . '</td>
</tr>

<tr>
<td><b>Other</b></td>
<td bgcolor=EAEAEA>' . $currencytype . '' . $currency . '' . $dother . '</td>
</tr>

<tr>
<td bgcolor=EAEAEA><b>Sub-Total</b></td>
<td bgcolor=EAEAEA>' . $currencytype . '' . $currency . '' . $dsub . '</td>
</tr>


<tr>
<td></td>
<td><u>Withdrawals</u></td>
</tr>

<tr>
<td><b>PayPal</b></td>
<td bgcolor=EAEAEA>' . $currencytype . '' . $currency . '' . $wpp . '</td>
</tr>

<tr>
<td><b>Check</b></td>
<td bgcolor=EAEAEA>' . $currencytype . '' . $currency . '' . $wc . '</td>
</tr>

<tr>
<td><b>Bank Wire</b></td>
<td bgcolor=EAEAEA>' . $currencytype . '' . $currency . '' . $ww . '</td>
</tr>

<tr>
<td><b>Other</b></td>
<td bgcolor=EAEAEA>' . $currencytype . '' . $currency . '' . $wother . '</td>
</tr>

<tr>
<td bgcolor=EAEAEA><b>Sub-Total</b></td>
<td bgcolor=EAEAEA>' . $currencytype . '' . $currency . '' . $wsub . '</td>
</tr>

<tr>
<td colspan=2><hr bgcolor=000000></td>
</tr>

<tr>
<td bgcolor=EAEAEA><b>Profit</b></td>
<td bgcolor=EAEAEA><b>' . $currencytype . '' . $currency . '' . $profit . '</td>
</tr>

</table>

<p>
<form method="POST" action="admin.php">
<input type="hidden" name="view" value="profits">
<input type="hidden" name="report" value="profits">
<input type="hidden" name="pass" value="' . $pass . '">
View Report for
<select name="month" size="1">
<option value="1">Jan</option>
<option value="2">Feb</option>
<option value="3">Mar</option>
<option value="4">Apr</option>
<option value="5">May</option>
<option value="6">Jun</option>
<option value="7">Jul</option>
<option value="8">Aug</option>
<option value="9">Sept</option>
<option value="10">Oct</option>
<option value="11">Nov</option>
<option value="12">Dec</option>
</select>
<select name="year" size="1">
';
$ii = date("Y");
for ($i=2002;$i<=$ii;$i++) {
echo '<option value="' . $i . '">' . $i . '</option>
';
}
echo '</select>

<input type="submit" value="Go">
</form>
<p><br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else if ($view == "withdrawals") {
if ($type == "new") {
echo '<center>
<big><big>Withdrawal Requests</big></big>
<p>

<table>
<tr>
<td bgcolor=000000><font color=ffffff><b>Username</td>
<td bgcolor=000000><font color=ffffff><b>Withdraw Amount</td>
<td bgcolor=000000><font color=ffffff><b>Method</td>
<td bgcolor=000000><font color=ffffff><b>Fee</td>
<td bgcolor=000000><font color=ffffff><b>Total to Send</td>
<td bgcolor=000000><font color=ffffff><b>Current Balance</td>
<td bgcolor=000000><font color=ffffff><b>Action</td>
</tr>
';
$withdraw = SQLact("query", "SELECT * FROM freelancers_withdrawals ORDER BY date2 DESC");
if (SQLact("num_rows", $withdraw)==0) {
echo '<tr>
<td align="center">No new withdrawals found.</td>
</tr>';
} else {
while ($row=SQLact("fetch_array", $withdraw)) {
echo '<tr>
<td><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $row[username] . '&atype=' . $row[atype] . '">proex101</a></td>
<td>' . $currencytype . '' . $currency . '' . $row[amount] . '</td>
<td><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $row[username] . '&atype=' . $row[atype] . '&payinfo=' . $row[wtype] . '">' . $row[wtype] . '</a></td>
<td><font color=red>-' . $currencytype . '' . $currency . '' . $row[wfee] . '</font></td>
<td bgcolor=EAEAEA><font color=green><b>' . $currencytype . '' . $currency . '' . $row[namount] . '</b></font></td>
<td>' . $currencytype . '' . $currency . '' . SQLact("result", SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $row[username] . "' AND type='" . $row[atype] . "' ORDER BY date2 DESC LIMIT 0,1"),0,"balance") . '</td>
<td><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&wapprove=' . $row[date2] . '"><b><font color=green>Approve</font></a></b> / <a href="' . $siteurl . '/admin.php?pass=' . $pass . '&wdeny=' . $row[date2] . '"><font color=red>Deny</font></a></td>
</tr>';
}
}
echo '</table>
<p>About: If you approve a withdrawal request the "Withdraw Amount" will be deducted from the user\'s account, and the withdrawal will be archived. If you deny, no amount will be deducted.
<p>Note: "Total to Send" is the amount of money you owe the user.
<p><br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else {
echo '<center>
<big><big>Archived Withdrawals</big></big>
<p>

<table>
<tr>
<td bgcolor=000000><font color=ffffff><b>Username</td>
<td bgcolor=000000><font color=ffffff><b>Account Type</td>
<td bgcolor=000000><font color=ffffff><b>Amount Withdrawn</td>
<td bgcolor=000000><font color=ffffff><b>Total Sent</td>
<td bgcolor=000000><font color=ffffff><b>Method</td>
<td bgcolor=000000><font color=ffffff><b>Date</td>
</tr>
';
$archwith = SQLact("query", "SELECT * FROM freelancers_archived ORDER BY date2 DESC");
if (SQLact("num_rows", $archwith)==0) {
echo '<tr>
<td align="center" colspan="6">No archived withdrawals found.</td>
</tr>';
} else {
while ($row=SQLact("fetch_array", $archwith)) {
echo '<tr>
<td><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $username . '&atype=' . $row[atype] . '">' . $username . '</a></td>
<td>' . $row[atype] . '</td>
<td>' . $currencytype . '' . $currency . '' . $row[amount] . '</td>
<td>' . $currencytype . '' . $currency . '' . $row[namount] . '</td>
<td>' . $row[wtype] . '</td>
<td>' . $row[date] . '</td>
</tr>
';
}
}
echo '</table>
<p><br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
}
} else if ($wdeny && $wdeny !== "") {
$fwithd = SQLact("query", "SELECT * FROM freelancers_withdrawals WHERE date2='$wdeny'");
if (SQLact("num_rows", $fwithd)==0) {
echo 'The withdrawal was not found.<br>
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '&view=withdrawals">Go back...</a>';
} else {
if (SQLact("result", $fwithd,0,"atype") == "buyer") {
$etu = SQLact("result", SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . SQLact("result", $fwithd,0,"username") . "'"),0,"email");
} else {
$etu = SQLact("result", SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . SQLact("result", $fwithd,0,"username") . "'"),0,"email");
}
mail($etu,$companyname . " Withdrawal Denied","Your withdrawal request was denied, sorry.","From: $emailaddress");
SQLact("query", "DELETE FROM freelancers_withdrawals WHERE date2='$wdeny'");
echo 'The withdrawal has been denied.<br>
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '&view=withdrawals">Go back...</a>';
}
} else if ($wapprove && $wapprove !== "") {
$fwithd = SQLact("query", "SELECT * FROM freelancers_withdrawals WHERE date2='$wapprove'");
if (SQLact("num_rows", $fwithd)==0) {
echo 'The withdrawal was not found.<br>
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '&view=withdrawals">Go back...</a>';
} else {
SQLact("query", "INSERT INTO freelancers_archived (date, date2, username, atype, wtype, amount, namount) VALUES ('" . SQLact("result", $fwithd,0,"date") . "', '" . SQLact("result", $fwithd,0,"date2") . "', '" . SQLact("result", $fwithd,0,"username") . "', '" . SQLact("result", $fwithd,0,"atype") . "', '" . SQLact("result", $fwithd,0,"wtype") . "', '" . SQLact("result", $fwithd,0,"amount") . "', '" . SQLact("result", $fwithd,0,"namount") . "')");
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
$tyress = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . SQLact("result", $fwithd,0,"username") . "' AND type='" . SQLact("result", $fwithd,0,"atype") . "' ORDER BY date2 DESC LIMIT 0,1");
$bal = SQLact("result", $tyress,0,"balance");
$dadj2 = $bal-SQLact("result", $fwithd,0,"amount");
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('-" . SQLact("result", $fwithd,0,"amount") . "', 'Withdrawal', '" . SQLact("result", $fwithd,0,"username") . "', '" . SQLact("result", $fwithd,0,"atype") . "', '$dadj2', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
SQLact("query", "INSERT INTO freelancers_profits (amount, type, type2, month, year) VALUES ('" . SQLact("result", $fwithd,0,"wfee") . "', 'withdrawal', '" . SQLact("result", $fwithd,0,"wtype") . "', '" . date("n") . "', '" . date("Y") . "')");
SQLact("query", "DELETE FROM freelancers_withdrawals WHERE date2='$wapprove'");
echo 'The withdrawal has been approved and added to your archives.<br>
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '&view=withdrawals">Go back...</a>';
}
} else if ($view == "deposits") {
if (!$submit) {
if ($type == "cc") {
$t1 = 'Credit Card ';
if ($show == "all") {
$h1 = 'All Credit Card Deposits';
$q1 = '';
$l1 = 'Display Only This Month\'s Credit Card Deposits...';
$t2 = 'No Archived Credit Card Deposits Found...';
$query = 'SELECT * FROM freelancers_deparch WHERE ptype=\'cc\' ORDER BY date2 DESC';
} else {
$h1 = 'Credit Card Deposits This Month';
$q1 = '&show=all';
$l1 = 'Display All Credit Card Deposits...';
$t2 = 'No Archived Credit Card Deposits For This Month Found...';
$query = 'SELECT * FROM freelancers_deparch WHERE ptype=\'cc\' AND month=\'' . date("n") . '\' AND year=\'' . date("Y") . '\' ORDER BY date2 DESC';
}
} else if ($type == "pp") {
$t1 = 'PayPal ';
if ($show == "all") {
$h1 = 'All PayPal Deposits';
$q1 = '';
$l1 = 'Display Only This Month\'s PayPal Deposits...';
$t2 = 'No Archived PayPal Deposits Found...';
$query = 'SELECT * FROM freelancers_deparch WHERE ptype=\'paypal\' ORDER BY date2 DESC';
} else {
$h1 = 'PayPal Deposits This Month';
$q1 = '&show=all';
$l1 = 'Display All PayPal Deposits...';
$t2 = 'No Archived PayPal Deposits For This Month Found...';
$query = 'SELECT * FROM freelancers_deparch WHERE ptype=\'paypal\' AND month=\'' . date("n") . '\' AND year=\'' . date("Y") . '\' ORDER BY date2 DESC';
}
} else {
$t1 = '';
if ($show == "all") {
$h1 = 'All "Other" Deposits';
$q1 = '';
$l1 = 'Display Only This Month\'s "Other" Deposits...';
$t2 = 'No Archived "Other" Deposits Found...';
$query = 'SELECT * FROM freelancers_deparch WHERE ptype=\'other\' ORDER BY date2 DESC';
} else {
$h1 = '"Other" Deposits This Month';
$q1 = '&show=all';
$l1 = 'Display All "Other" Deposits...';
$t2 = 'No Archived "Other" Deposits For This Month Found...';
$query = 'SELECT * FROM freelancers_deparch WHERE ptype=\'other\' AND month=\'' . date("n") . '\' AND year=\'' . date("Y") . '\' ORDER BY date2 DESC';
}
}
echo '<center>
<big><big>' . $h1 . '</big></big>
<p>
<table>
<tr>
<td bgcolor=000000><font color=ffffff><b>Username</td>
<td bgcolor=000000><font color=ffffff><b>Account Type</td>
<td bgcolor=000000><font color=ffffff><b>Total Paid</td>
<td bgcolor=000000><font color=ffffff><b>Deposit Amount</td>
<td bgcolor=000000><font color=ffffff><b>Order Number</td>
<td bgcolor=000000><font color=ffffff><b>Date</td>
</tr>
';
$deparch = SQLact("query", $query);
if (SQLact("num_rows", $deparch)==0) {
echo '<tr>
<td colspan="6" align="center">(' . $t2 . ')</td>
</tr>';
} else {
while ($row=SQLact("fetch_array", $deparch)) {
echo '<tr>
<td><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $row[username] . '&atype=' . $row[atype] . '">' . $row[username] . '</a></td>
<td>' . $row[atype] . '</td>
<td>' . $currencytype . '' . $currency . '' . $row[amount] . '</td>
<td>' . $currencytype . '' . $currency . '' . $row[namount] . '</td>
<td>' . $row[oid] . '</td>
<td>' . $row[date] . '</td>
</tr>
';
}
}
echo '</table>
<p><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&view=deposits&type=' . $type . '' . $q1 . '">' . $l1 . '</a>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="add" value="deposit">
<input type="hidden" name="pass" value="' . $pass . '">

<table bgcolor=EAEAEA width=400>
<tr>
<td colspan=2>
<center>
<big>Add a deposit for a user...</big><br>
<small>(This will add the funds to the user\'s account balance and will save the information for your admin records.)</small>
</td>
</tr>
<tr>
<td><b>Deposit Type:</td>
<td>
<select name="ptype" size="1">
';
if ($deposit2checkout == "enabled" || $depositccurl !== "") echo '<option value="cc">Credit Card</option>';
if ($depositpaypal == "enabled") echo '<option value="paypal">PayPal</option>';
if ($depositother == "enabled") echo '<option value="other">Other</option>';
echo '</select>
</td>
</tr>
<tr>
<td><b>Total Paid:</td>
<td>' . $currencytype . '' . $currency . '<input type="text" size=10 name="amount"></td>
</tr>
<tr>
<td><b>Deposit Amount*:</td>
<td>' . $currencytype . '' . $currency . '<input type="text" size=10 name="namount"></td>
</tr>
<tr>
<td><b>Description:</td>
<td><input type="text" size=25 name="description" value="' . $t1 . 'Deposit"></td>
</tr>
<tr>
<td><b>Order Number:</td>
<td><input type="text" size=20 name="oid"></td>
</tr>
<tr>
<td><b>Username:</td>
<td><input type="text" size=20 name="username"></td>
</tr>
<tr>
<td><b>Account Type:</td>
<td><select name="atype">
<option value="buyer">' . $buyer . '</option>
<option value="freelancer">' . $freelancer . '</option>
</select></td>
</tr>
<tr>
<td colspan=2>
<center>
<input type="submit" value="Add Deposit" name="submit">
<br>
<small>* "Deposit Amount" is the actual amount that will be added to the user\'s account. It could be the "Total Paid" amount minus a fee.</small>
</td>
</tr>
</table>
</form>
</center>
<p><br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else {
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
SQLact("query", "INSERT INTO deparch (ptype, month, year, date2, username, atype, amount, namount, oid, date) VALUES ('$ptype', '" . date("n") . "', '" . date("Y") . "', '" . time() . "', '$username', '$atype', '$amount', '$namount', '$month/$day/$year @ $hours:$minutes')");
$tyress = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='$atype' ORDER BY date2 DESC LIMIT 0,1");
$bal = SQLact("result", $tyress,0,"balance");
$dadj2 = $bal+$namount;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+$namount', '$description ($oid)', '$username', '$atype', '$dadj2', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
$tram = $amount-$namount;
SQLact("query", "INSERT INTO freelancers_profits (amount, type, type2, month, year) VALUES ('$tram', 'deposit', '$ptype', '" . date("n") . "', '" . date("Y") . "')");
echo 'The deposit amount has been added to the user\'s account and your deposit archives.<br>
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '">Go back...</a>';
}
} else if ($refund && $refund !== "") {
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$refund' AND status='closed'"))==0) {
echo 'Error: No closed project was found with the specified ID number.';
} else if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_refunds WHERE id='$refund'"))==0) {
echo 'Error: You have all ready refunded the commissions charged for this project.';
} else {
$getproj = SQLact("query", "SELECT * FROM freelancers_projects WHERE id='$refund'");
if (!$submit) {
echo '<form method="POST" action="admin.php">
<div align="center"><center>
<b>Are you sure you want to give a refund to <a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . SQLact("result", $getproj,0,"creator") . '&atype=buyer">' . SQLact("result", $getproj,0,"creator") . '</a> and <a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . SQLact("result", $getproj,0,"chosen") . '&atype=freelancer">' . SQLact("result", $getproj,0,"chosen") . '</a> for the project "<a href="' . $siteurl . '/project.php?id=' . $refund . '">' . SQLact("result", $getproj,0,"project") . '</a>"?</b>
<p>
<input type="hidden" name="refund" value="' . $refund . '">
<input type="hidden" name="refundp" value="' . SQLact("result", $getproj,0,"project") . '">
<input type="hidden" name="refundf" value="' . SQLact("result", $getproj,0,"chosen") . '">
<input type="hidden" name="refundb" value="' . SQLact("result", $getproj,0,"creator") . '">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Yes, Refund Commission!" name="submit">
<br>
<small>(Any reviews about these users resulting from this project will be removed.)</small>
</center></div>
</form>';
} else {
SQLact("query", "DELETE FROM freelancers_ratings WHERE projid='$refund'");
SQLact("query", "INSERT INTO freelancers_refunds (id) VALUES ('$refund')");
$refbuy = SQLact("query", "SELECT * FROM freelancers_transactions WHERE details='Project (<a href=\"$siteurl/project.php?id=$refund\">$refundp</a>)' AND username='$refundb' AND atype='buyer'");
$reffre = SQLact("query", "SELECT * FROM freelancers_transactions WHERE details='Project (<a href=\"$siteurl/project.php?id=$refund\">$refundp</a>)' AND username='$refundf' AND atype='freelancer'");
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
$tyress = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $refundb . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$bal = SQLact("result", $tyress,0,"balance");
$dadj2 = $bal+SQLact("result", $refbuy,0,"amount");
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+" . SQLact("result", $refbuy,0,"amount") . "', 'Refund on Project (<a href=\"$siteurl/project.php?id=$refund\">$refundp</a>)', '$refundb', 'buyer', '$dadj2', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
$tyress2 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $refundf . "' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$bal2 = SQLact("result", $tyress2,0,"balance");
$dadj3 = $bal2+SQLact("result", $reffre,0,"amount");
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+" . SQLact("result", $reffre,0,"amount") . "', 'Refund on Project (<a href=\"$siteurl/project.php?id=$refund\">$refundp</a>)', '$refundf', 'freelancer', '$dadj3', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
echo 'Refunds completed and ratings removed successfully! <a href="' . $siteurl . '/admin.php?pass=' . $pass . '">Go back...</a>';
}
}
} else if ($viewmember && $viewmember !== "") {
if ($atype == "buyer") {
$getinf = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$viewmember'");
} else {
$getinf = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$viewmember'");
}
if ($payinfo && $payinfo !== "") {
if ($payinfo == "wire") {
echo '<b><big>' . $atype . ' ' . $viewmember . ' Bank Wire Info</big></b>
<p>

<table>
<tr><td><b>Account Holder\'s Name:</b> </td><td>' . SQLact("result", $getinf,0,"wbankyourname") . '</td></tr>
<tr><td><b>Bank Name:</b> </td><td>' . SQLact("result", $getinf,0,"wbankname") . '</td></tr>
<tr><td><b>Bank Street Address:</b> </td><td>' . SQLact("result", $getinf,0,"wbankaddress") . '</td></tr>
<tr><td><b>Bank City:</b> </td><td>' . SQLact("result", $getinf,0,"wbankcity") . '</td></tr>
<tr><td><b>Bank State/Province:</b> </td><td>' . SQLact("result", $getinf,0,"wbankstate") . '</td></tr>
<tr><td><b>Bank Country:</b> </td><td>' . SQLact("result", $getinf,0,"wbankcountry") . '</td></tr>
<tr><td><b>Bank Zip/Postal Code:</b> </td><td>' . SQLact("result", $getinf,0,"wbankzip") . '</td></tr>
<tr><td><b>Bank Account Number:</b> </td><td>' . SQLact("result", $getinf,0,"wbankaccnum") . '</td></tr>
<tr><td><b>Bank Routing/Swift Code:</b> </td><td>' . SQLact("result", $getinf,0,"wbankcode") . '</td></tr>
<tr><td><b>Bank Account Type:</b> </td><td>' . SQLact("result", $getinf,0,"wbankacctype") . '</td></tr>
</table>
<p><A HREF="javascript:history.go(-1);">Go back...</A>';
} else if ($payinfo == "paypal") {
echo '<b><big>' . $atype . ' ' . $viewmember . ' PayPal E-mail</big></b>
<p>

' . SQLact("result", $getinf,0,"wemail") . '
<p><A HREF="javascript:history.go(-1);">Go back...</A>';
} else if ($payinfo == "check") {
echo '<b><big>' . $atype . ' ' . $viewmember . ' Bank Wire Info</big></b>
<p>

<table>
<tr><td><b>Name (Payable to):</b> </td><td>' . SQLact("result", $getinf,0,"wname") . '</td></tr>
<tr><td><b>Address:</b> </td><td>' . SQLact("result", $getinf,0,"waddress") . '</td></tr>
<tr><td><b>City, State/Province, & Country:</b> </td><td>' . SQLact("result", $getinf,0,"wcity") . '</td></tr>
<tr><td><b>Zip/Postal Code:</b> </td><td>' . SQLact("result", $getinf,0,"wzip") . '</td></tr>
</table>
<p><A HREF="javascript:history.go(-1);">Go back...</A>';
} else {
echo '<b><big>' . $atype . ' ' . $viewmember . ' ' . $withdrawonam . ' Info</big></b>
<p>

<b>' . $withdrawonam . '</b>:<br>
' . SQLact("result", $getinf,0,"wothercontent") . '
<p><A HREF="javascript:history.go(-1);">Go back...</A>';
}
} else {
$theabsoluteresss = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='$viewmember' AND type='$atype' ORDER BY date2 DESC LIMIT 0,1");
$nbalance = SQLact("result", $theabsoluteresss,0,"balance");
echo '<center>
<big>Member Info For ' . $atype . ' ' . $viewmember . '</big>
<p>
<table bgcolor=EAEAEA>
<tr>
<td><b>Username:</b> </td>
<td>
<a href="' . $siteurl . '/freelancers.php?viewprofile=' . $viewmember . '">' . $viewmember . '</a></td>
</tr>

<tr>
<td><b>Password:</b> </td>
<td><table cellspacing=0 cellpadding=0><tr><td bgcolor=0000000>' . SQLact("result", $getinf,0,"password") . '</td></tr></table></td>
</tr>

<tr>
<td><b>Account Type:</b> </td>
<td>' . $atype . '</td>
</tr>

<tr>
<td><b>Name:</b> </td>
<td>' . SQLact("result", $getinf,0,"company") . '</td>
</tr>

<tr>
<td><b>E-mail:</b> </td>
<td><a href="mailto:' . SQLact("result", $getinf,0,"email") . '">' . SQLact("result", $getinf,0,"email") . '</a></td>
</tr>
<tr>
<td><b>Balance:</b> </td>
<td><a href="' . $siteurl . '/admin.php?wtrans=' . $viewmember . '&atype=' . $atype . '&pass=' . $pass . '">' . $currencytype . '' . $currency . '' . $nbalance . '</a></td>
</tr>
';
if ($atype == "freelancer") {
echo '<tr>
<td valign=top><b>Expertise:</b> </td> <td>
<ul>
';
$theress = SQLact("query", "SELECT * FROM freelancers_cats");
while ($row=SQLact("fetch_array", $theress)) {
$tttress = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='" . $viewmember . "' AND categories LIKE '%" . $row[categories]. "%'");
$rowssss = SQLact("num_rows", $tttress);
if ($rowssss!==0) {
echo '<li> ' . $row[categories] . '</li>
';
}
}
echo '</ul>
</td></tr>
';
}
$logins2 = SQLact("query", "SELECT * FROM freelancers_logins WHERE username='$viewmember' AND atype='$atype'");
$elapsed = time()-SQLact("result", $logins2,0,"date");
$days = $elapsed/86400;
$days = round($days);
$left = $elapsed - ($days*86400);
$hours = $left/3600;
$hours = round($hours);
$left2 = $left - ($hours*3600);
$minutes = round($left2/60);
$dtlt = getdate(SQLact("result", $logins2,0,"date"));
$mlt = $dtlt["mon"];
$dlt = $dtlt["mday"];
$ylt = $dtlt["year"];
$hlt = $dtlt["hours"];
$m2lt = $dtlt["minutes"];
$dtlt2 = "$mlt-$dlt-$ylt @ $hlt:$m2lt";
echo '<tr>
<td valign=top><b>Last Login:</b></td>
<td>' . $dtlt2 . '<br><small>(' . $days . ' days, ' . $hours . ' hours and ' . $minutes . ' minutes ago)</small></td>
</tr>
</table>
<p>
<table bgcolor=EAEAEA>
<tr><td>
<center>
<b>Add/Remove Funds</b>
<form method="POST" action="admin.php">
<input type="hidden" name="username" value="' . $viewmember . '">
<input type="hidden" name="atype" value="' . $atype . '">
<input type="hidden" name="pass" value="' . $pass . '">
<select name="balance" size="1">
<option selected value="add">Add</option>
<option value="remove">Remove</option>
</select>
' . $currencytype . '' . $currency . '<input type="text" name="amount" size="4">
to/from balance.<br>
Description: <input type="text" name="description" size="30"><br>
<input type="submit" value="Update Balance">
</form>
</td></tr>
</table>
<p>
<form method="POST" action="admin.php">
<input type="hidden" name="username" value="' . $viewmember . '">
<input type="hidden" name="atype" value="' . $atype . '">
<input type="hidden" name="pass" value="' . $pass . '">
View
<select name="payinfo" size="1">
<option value="check">Check</option>
<option value="paypal">PayPal</option>
<option value="wire">Bank Wire</option>
<option value="other">Western Union Transfer</option>
</select>
Payment Information...
<input type="submit" value="Go">
</form>
<p><br>
<table>
<tr><td width=300 valign=top><center>

<form method="POST" action="admin.php">
<input type="hidden" name="username" value="' . $viewmember . '">
<input type="hidden" name="atype" value="' . $atype . '">
<input type="hidden" name="pass" value="' . $pass . '">

<input type="hidden" name="changespecial" value="yes">
<input type="submit" value="';
if (SQLact("result", $getinf,0,"special") == "user") {
echo 'Remove';
} else {
echo 'Give';
}
echo ' \'Special\' Status">
<br>
<small>(Users with special status will have a graphic beside their username, wherever it appears, getting them noticed. They can also save money on your commissions, depending on the settings, as defined by you.)</small>
</form>

</td><td width=300 valign=top>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="username" value="' . $viewmember . '">
<input type="hidden" name="atype" value="' . $atype . '">
<input type="hidden" name="tip" value="' . SQLact("result", $getinf,0,"ip") . '">
<input type="hidden" name="pass" value="' . $pass . '">

<input type="hidden" name="changestatus" value="suspended">
<input type="submit" value="';
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='" . SQLact("result", $getinf,0,"ip") . "'"))!==0) {
echo 'Un';
}
echo 'Suspend Account">
<br>
<small>(A suspended account cannot create projects, bid on projects, send messages, etc.)</small>
</form>

</td></tr>
</table>
<p>
';
if ($atype == "freelancer") {
$tthing = SQLact("query", "SELECT * FROM freelancers_bids WHERE username='$viewmember'");
if (SQLact("num_rows", $tthing)!==0) {
echo '<table>
<tr>
<td bgcolor=000000><font color=ffffff><b><center>
Projects Bid On</td></tr><tr><td><ul>';
while ($row24=SQLact("fetch_array", $tthing)) {
echo '<li><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewproject=' . $row24[id] . '">' . $row24[project] . '</a></li>';
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_projects WHERE status='closed' AND id='" . $row24[id] . "'"))!==0) {
echo ' (Bid Lost)';
}
echo '</li>';
}
echo '</ul> </td></tr></table> <p><br>';
}
} else {
$tthing = SQLact("query", "SELECT * FROM freelancers_projects WHERE creator='$viewmember'");
if (SQLact("num_rows", $tthing)!==0) {
echo '<table>
<tr>
<td bgcolor=000000><font color=ffffff><b><center>
Projects Created</td></tr><tr><td><ul>';
while ($row4=SQLact("fetch_array", $tthing)) {
echo '<li><a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewproject=' . $row4[id] . '">' . $row4[project] . '</a></li>';
}
echo '</ul> </td></tr></table> <p><br>';
}
}
echo '<form method="POST" action="admin.php">
<input type="hidden" name="' . $atype . 'delete" value="' . $viewmember . '">
<input type="hidden" name="pass" value="' . $pass . '">
<table cellpadding=3>
<tr><td bgcolor=red>
<input type="submit" value="Delete Account">
</td></tr>
</table>
</form>
<p><br>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
}
} else if ($changespecial == "yes") {
if ($atype == "freelancer") {
if (SQLact("result", SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$username'"),0,"special") == "user") {
SQLact("query", "UPDATE freelancers_programmers SET special='' WHERE username='$username'");
} else {
SQLact("query", "UPDATE freelancers_programmers SET special='user' WHERE username='$username'");
}
} else {
if (SQLact("result", SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$username'"),0,"special") == "user") {
SQLact("query", "UPDATE freelancers_webmasters SET special='' WHERE username='$username'");
} else {
SQLact("query", "UPDATE freelancers_webmasters SET special='user' WHERE username='$username'");
}
}
echo 'Special status for ' . $atype . ' account ' . $username . ' has been changed. <a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $username . '&atype=' . $atype . '">Go back...</a>';
} else if ($freelancerdelete && $freelancerdelete !== "") {
if (!$submit) {
echo '<form method="POST" action="admin.php">
<div align="center"><center>
Delete the ' . $freelancer . ' account <b>' . $freelancerdelete . '</b>?<br>
<input type="hidden" name="freelancerdelete" value="' . $freelancerdelete . '">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Yes, Delete Account!" name="submit">
<br>
<table>
<tr><td>
<u>Optional:</u><br>
<input type="checkbox" name="closeall" value="' . $freelancerdelete . '"> Delete all bids placed by this user.<br>
<input type="checkbox" name="banusername" value="' . $freelancerdelete . '"> Ban the username <b>' . $freelancerdelete . '</b>.<br>
<input type="checkbox" name="banemail" value="' . $freelancerdelete . '"> Ban this user\'s e-mail.<br>
<input type="checkbox" name="banip" value="' . $freelancerdelete . '"> Ban this user\'s IP address.<br>
</td></tr>
</table>

  </center></div>
</form>';
} else {
$getuser = SQLact("query", "SELECT * FROM freelancers_programmers WHERE username='$freelancerdelete'");
SQLact("query", "DELETE FROM freelancers_programmers WHERE username='$freelancerdelete'");
if ($closeall && $closeall !== "") {
SQLact("query", "DELETE FROM freelancers_bids WHERE username='$freelancerdelete'");
}
if ($banip && $banip !== "") {
SQLact("query", "INSERT INTO freelancers_bans (ip, reason) VALUES ('" . SQLact("result", $getuser,0,"ip") . "', '')");
}
echo 'The ' . $freelancer . ' account ' . $freelancerdelete . ' has been successfully deleted.<br>
If you chose any "Optional" fields, their actions have been performed.';
}
} else if ($buyerdelete && $buyerdelete !== "") {
if (!$submit) {
echo '<form method="POST" action="admin.php">
<div align="center"><center>
Delete the ' . $buyer . ' account <b>' . $buyerdelete . '</b>?<br>
<input type="hidden" name="buyerdelete" value="' . $buyerdelete . '">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Yes, Delete Account!" name="submit">
<br>
<table>
<tr><td>
<u>Optional:</u><br>
<input type="checkbox" name="closeall" value="' . $buyerdelete . '"> Delete all projects created by this user.<br>
<input type="checkbox" name="banusername" value="' . $buyerdelete . '"> Ban the username <b>' . $buyerdelete . '</b>.<br>
<input type="checkbox" name="banemail" value="' . $buyerdelete . '"> Ban this user\'s e-mail.<br>
<input type="checkbox" name="banip" value="' . $buyerdelete . '"> Ban this user\'s IP address.<br>
</td></tr>
</table>

  </center></div>
</form>';
} else {
$getuser = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='$buyerdelete'");
SQLact("query", "DELETE FROM freelancers_webmasters WHERE username='$buyerdelete'");
if ($closeall && $closeall !== "") {
SQLact("query", "DELETE FROM freelancers_projects WHERE username='$buyerdelete'");
}
if ($banip && $banip !== "") {
SQLact("query", "INSERT INTO freelancers_bans (ip, reason) VALUES ('" . SQLact("result", $getuser,0,"ip") . "', '')");
}
echo 'The ' . $buyer . ' account ' . $buyerdelete . ' has been successfully deleted.<br>
If you chose any "Optional" fields, their actions have been performed.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
}
} else if ($changestatus == "suspended") {
if (SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_suspends WHERE ip='$tip'"))!==0) {
SQLact("query", "DELETE FROM freelancers_suspends WHERE ip='$tip'");
echo 'The ' . $atype . ' account ' . $username . ' has been successfully unsuspended.<br>
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $username . '&atype=' . $atype . '">Go back...</a>';
} else {
SQLact("query", "INSERT INTO freelancers_suspends (ip, reason) VALUES ('$tip', '')");
echo 'The ' . $atype . ' account ' . $username . ' has been successfully suspended.<br>
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $username . '&atype=' . $atype . '">Go back...</a>';
}
} else if ($balance == "add") {
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
$tyress = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='$atype' ORDER BY date2 DESC LIMIT 0,1");
$bal = SQLact("result", $tyress,0,"balance");
$dadj2 = $bal+$amount;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('+$amount', '$description', '$username', '$atype', '$dadj2', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
echo 'The transaction has been successfully added to the ' . $atype . ' account ' . $username . '.<br>
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $username . '&atype=' . $atype . '">Go back...</a>';
} else if ($balance == "remove") {
$today = getdate();
$month = $today['mon'];
$day = $today['mday'];
$year = $today['year'];
$hours = $today['hours'];
$minutes = $today['minutes'];
$tyress = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $username . "' AND type='$atype' ORDER BY date2 DESC LIMIT 0,1");
$bal = SQLact("result", $tyress,0,"balance");
$dadj2 = $bal-$amount;
SQLact("query", "INSERT INTO freelancers_transactions (amount, details, username, type, balance, date, date2) VALUES ('-$amount', '$description', '$username', '$atype', '$dadj2', '$month/$day/$year @ $hours:$minutes', '" . time() . "')");
echo 'The transaction has been successfully removed from the ' . $atype . ' account ' . $username . '.<br>
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '&viewmember=' . $username . '&atype=' . $atype . '">Go back...</a>';
} else if ($email == "ind") {
if (!$submit2) {
echo '<h3>E-Mail Individuals - Step 1</h3><br>
<form action="admin.php" method="post">
<input type="hidden" name="email" value="ind">
<input type="hidden" name="pass" value="' . $pass . '">
Subject: <input type="text" name="subject"><br>
Message: <textarea name="message" rows=12 cols=28></textarea><br>
<input type="submit" name="submit2" value="Continue To Step 2">';
} else {
if (!$submit) {
SQLact("query", "INSERT INTO freelancers_eind (subject, message) VALUES ('$subject', '$message')");
echo '<h3>E-Mail Individuals - Step 2</h3><br>
<form action="admin.php" method="post">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="hidden" name="email" value="ind">
<input type="hidden" name="submit2" value="' . $submit2 . '">
Select the users you want to send your e-mail to and then click the below button:<br>
<input type="submit" name="submit" value="E-Mail Selected Individuals"><br>
<table border=2>
<tr>
<th align="center">
&nbsp;
</th>
<th align="center">
Username
</th>
<th align="center">
Account Type
</th>
<th align="center">
E-Mail Address
</th>
</tr>';
$flcn = SQLact("query", "SELECT * FROM freelancers_programmers");
while ($row=SQLact("fetch_array", $flcn)) {
echo '<tr>
<td>
<input type="checkbox" name="accem[]" value="' . $row[email] . '">
</td>
<td>
' . $row[username] . '
</td>
<td>
' . $freelancer . '
</td>
<td>
' . $row[email] . '
</td>
</tr>
';
}
$flcn2 = SQLact("query", "SELECT * FROM freelancers_webmasters");
while ($row2=SQLact("fetch_array", $flcn2)) {
echo '<tr>
<td>
<input type="checkbox" name="accem[]" value="' . $row2[email] . '">
</td>
<td>
' . $row2[username] . '
</td>
<td>
' . $buyer . '
</td>
<td>
' . $row2[email] . '
</td>
</tr>
';
}
echo '</table><br>
Select the users you want to send your e-mail to and then click the below button:<br>
<input type="submit" name="submit" value="E-Mail Selected Individuals"><br><br>
</form>
<center>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="' . $pass . '">
<input type="submit" value="Back To Main...">
</form>
</center>';
} else {
if (count($accem)==0) {
echo 'Error: You must select at least one user to e-mail.<br>
<a href="javascript:history.go(-1);">Go back...</a>';
} else {
$subject = SQLact("result", SQLact("query", "SELECT * FROM eind"),0,"subject");
$message = SQLact("result", SQLact("query", "SELECT * FROM eind"),0,"message");
foreach ($accem as $val) {
mail($val,$subject,$message,"From: $emailaddress");
}
echo 'Your specified message has been successfully sent to the selected individual(s).
<a href="' . $siteurl . '/admin.php?pass=' . $pass . '">Go back...</a>';
}
}
}
} else {
?>
<title>PHP Freelancers by ProEx101</title>

<center>
<big><big>Welcome to the Admin Area</big></big>
<br><font size="4">An xCGI release by Zeedy2k nulled by drew010</font>
<p>


<table border=1 cellspacing=0 bordercolor=000080>
<tr>
<td bgcolor=000080><font color=ffffff><b>Finance</td>
</tr>
<tr>
<td>
<center>

<br>
<form method="POST" action="admin.php">
<input type="hidden" name="view" value="deposits">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<b>View/Manage
<select name="type" size="1">
<?php
if ($deposit2checkout == "enabled" || $depositccurl !== "") echo '<option value="cc">Credit Card</option>';
if ($depositpaypal == "enabled") echo '<option value="pp">PayPal</option>';
if ($depositother == "enabled") echo '<option value="other">All Other</option>';
?>
</select>
Deposits...</b>
<input type="submit" value="Go">
</form>
<p>

<form method="POST" action="admin.php">
<input type="hidden" name="view" value="withdrawals">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<b>View</b>
<select name="type" size="1">
<option selected value="new">New Withdrawal Requests (<? echo SQLact("num_rows", SQLact("query", "SELECT * FROM freelancers_withdrawals")); ?>)</option>
<option value="old">Archived Withdrawals</option>
</select>
<input type="submit" value="Go">
</form>
<p>

<form method="POST" action="admin.php">
<input type="hidden" name="view" value="profits">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<input type="submit" value="View Profit Report">
</form>
<p>

</td>
</tr>
</table>

<p>
<table border=1 cellspacing=0 bordercolor=000080>
<tr>
<td bgcolor=000080><font color=ffffff><b>Accounts</td>
</tr>
<tr>
<td>
<center>

<form method="POST" action="admin.php">
<input type="hidden" name="view" value="freelancers">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<input type="submit" value="View All <? echo $freelancers; ?>">
</form>
<p>
<form method="POST" action="admin.php">
<input type="hidden" name="view" value="buyers">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<input type="submit" value="View All <? echo $buyers; ?>">
</form>
<p>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<b>Find User:</b> <input type="text" size=20 name="search">
<small>
<input type="submit" value="Search">
<br>
(Find by username, password, e-mail or name.)</small>
</form>

<p>

<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<b>Find Project:</b> <input type="text" size=20 name="search_projects">
<small>
<input type="submit" value="Search">
<br>
(Find by ID, username, project name, or status.)</small>
</form>
<p>

<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<b>Display members which have NOT logged in for over
<input type="text" size=3 name="inactive" value="30">
days...</b>
<input type="submit" name="loggedin" value="Display">
</form>

</td>
</tr>
</table>
<p>

<table border=1 cellspacing=0 bordercolor=000080>
<tr>
<td bgcolor=000080><font color=ffffff><b>Announcements</td>
</tr>
<tr>
<td>
<center>
<br>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
Announce on <select name="atype">
<option value="all" selected> All</option>
<option value="freelancer"> <? echo $freelancer; ?></option>
<option value="buyer"> <? echo $buyer; ?></option>
</select> account management pages:<br>
<textarea name="announce" rows="8" cols="42"></textarea><br>
Ex. <b><i>Project commissions have changed!&lt;br>
Any project closed from now on will not have any commission fees at all!</i></b><br>
<input type="submit" value="Make Announcement">
</form>
</td>
</tr>
</table>
<p>


<table border=1 cellspacing=0 bordercolor=000080>
<tr>
<td bgcolor=000080><font color=ffffff><b>E-mails</td>
</tr>
<tr><td>
<center>
<br>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<input type="hidden" name="email" value="ind">
<input type="submit" value="E-Mail Individuals">
</form>
<br>

</center>

<center>
<br>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<input type="hidden" name="mail" value="list">
<b>Get e-mail list of
<select name="emaillist" size="1">
<option selected value="all">All</option>
<option value="freelancer"><? echo $freelancer; ?></option>
<option value="buyer"><? echo $buyer; ?></option>
</select>
accounts...</b>
<input type="submit" value="Go">
</form>
<br>

</center>

<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<input type="hidden" name="email" value="list">
<b>Send message to
<select name="sendemail" size="1">
<option selected value="all">All</option>
<option value="freelancer"><? echo $freelancer; ?></option>
<option value="buyer"><? echo $buyer; ?></option>
</select>
accounts.</b><br>
<b>Subject:</b> <input type="text" size=40 name="send_subject"><br>
<b>Message:</b><br>
<textarea rows="10" name="send_message" cols="50"></textarea><br>
<input type="submit" value="Send"><br>
<small>(This may take several moments if you have many members AND your message is large.)</small>
</form>

</td>
</tr>
</table>
<p>


<table border=1 cellspacing=0 bordercolor=000080>
<tr>
<td bgcolor=000080><font color=ffffff><b>Setup</td>
</tr>
<tr>
<td>
<center>
<br>
<form method="POST" action="admin_setup.php">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<input type="submit" value="Customize Script">
</form>
<p>
</td>
</tr>
</table>
<p>

<br>
<form method="POST" action="admin.php">
<input type="hidden" name="pass" value="<? echo $pass; ?>">
<input type="hidden" name="forum" value="boards">
<input type="submit" value="Save Password & View Message Board...">
</form>
<p>
<br><br>



<p>
<br>
<!---- ProEx101 Version Update Technology will notify you when a new version of PHP Freelancers is available ---->
<?php
$referrer = 'http://' . fuckincunts.com . '' . $PHP_SELF; // ProEx101 VUT will double-check for updates in case current version is falsely input
$contents = @file("http://www.nulledbydrew010.com/freelancers/track.php?v=1000&referrer=$referrer") or die ("Unable to connect to the ProEx101 web server to check for updates on your current version of PHP Freelancers.<br>That's because its nulled successfully:)");
for($i=0;$i<count($contents);$i++) {
if (!(strpos($contents[$i],"UPDATED")===FALSE)) {
echo 'A new version of PHP Freelancers is now available!  <br>
If you purchased unlimited updates, you will soon be receiving an e-mail notifying you where to download the newest version for free.';
}
}
}
}
?>
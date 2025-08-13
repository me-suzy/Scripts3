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

$today = date("Ymd");
if ($lastday !== $today) {
$prgmrs = SQLact("query", "SELECT * FROM freelancers_programmers");
while ($row=SQLact("fetch_array", $prgmrs)) {
$gettra = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $row[username] . "' AND type='freelancer' ORDER BY date2 DESC LIMIT 0,1");
$getbal = SQLact("result", $gettra,0,"balance");
$getdat = SQLact("result", $gettra,0,"date2");
$tt = time();
$tf = $tt-$getdat;
$aa = round($tf/((24 * 60) * 60));
$expdays = explode('-', $balexpdays);
for ($i=0;$i<count($expdays);$i++) {
if ($getbal<0.00 && $aa==$expdays[$i]) {
$to = $row[email];
$subject = $companyname . ' Account Balance Low';
$message = 'Your ' . $freelancer . ' account balance at ' . $companyname . ' has now been below ' . $currencytype . '' . $currency . '0.00 for ' . $expdays[$i] . ' days. This e-mail is sent to notify you that your account will be suspended if your balance remains below ' . $currencytype . '' . $currency . '0.00 for more than ' . $balmaxdays . ' consecutive days. You should add funds to your account to avoid this.';
mail($to,$subject,$message,"From: $emailaddress");
} else if ($getbal<0.00 && $aa>=$balmaxdays) {
$to2 = $row[email];
$subject2 = $companyname . ' Account Suspended';
$message2 = 'Your ' . $freelancer . ' account balance at ' . $companyname . ' has now been below ' . $currencytype . '' . $currency . '0.00 for ' . $balmaxdays . ' days. Unfortunately, you will not be able to place any new bids, and access many webpages within our website until you add funds to your account.';
mail($to2,$subject2,$message2,"From: $emailaddress");
SQLact("query", "INSERT INTO freelancers_suspends (ip, reason) VALUES ('" . $row[ip] . "', 'You have been suspended because of an account balance lower than " . $currencytype . "" . $currency . "0.00 for " . $balmaxdays . " or more consecutive days and you will remain suspended until you add funds to your account.  Check your email for more information.')");
} else {}
}
}
$wbstrs = SQLact("query", "SELECT * FROM freelancers_webmasters");
while ($row3=SQLact("fetch_array", $wbstrs)) {
$gettra3 = SQLact("query", "SELECT * FROM freelancers_transactions WHERE username='" . $row3[username] . "' AND type='buyer' ORDER BY date2 DESC LIMIT 0,1");
$getbal3 = SQLact("result", $gettra3,0,"balance");
$getdat3 = SQLact("result", $gettra3,0,"date2");
$tt3 = time();
$tf3 = $tt3-$getdat3;
$aa3 = round($tf3/((24 * 60) * 60));
$expdays3 = explode('-', $balexpdays);
for ($i3=0;$i3<count($expdays3);$i3++) {
if ($getbal3<0.00 && $aa3==$expdays3[$i3]) {
$to3 = $row3[email];
$subject3 = $companyname . ' Account Balance Low';
$message3 = 'Your ' . $buyer . ' account balance at ' . $companyname . ' has now been below ' . $currencytype . '' . $currency . '0.00 for ' . $expdays3[$i] . ' days. This e-mail is sent to notify you that your account will be suspended if your balance remains below ' . $currencytype . '' . $currency . '0.00 for more than ' . $balmaxdays . ' consecutive days. You should add funds to your account to avoid this.';
mail($to3,$subject3,$message3,"From: $emailaddress");
} else if ($getbal3<0.00 && $aa3>=$balmaxdays) {
$to23 = $row3[email];
$subject23 = $companyname . ' Account Suspended';
$message23 = 'Your ' . $buyer . ' account balance at ' . $companyname . ' has now been below ' . $currencytype . '' . $currency . '0.00 for ' . $balmaxdays . ' days. Unfortunately, you will not be able to place any new bids, and access many webpages within our website until you add funds to your account.';
mail($to23,$subject23,$message23,"From: $emailaddress");
SQLact("query", "INSERT INTO freelancers_suspends (ip, reason) VALUES ('" . $row3[ip] . "', 'You have been suspended because of an account balance lower than " . $currencytype . "" . $currency . "0.00 for " . $balmaxdays . " or more conescutive days and you will remain suspended until you add funds to your account.  Check your email for more information.')");
} else {}
}
}
$projects = SQLact("query", "SELECT * FROM freelancers_projects");
while ($row7=SQLact("fetch_array", $projects)) {
$secondsPerDay = ((24 * 60) * 60);
$timeStamp = time();
$daysUntilExpiry = $row7[expires];
$expiry = $timeStamp + ($daysUntilExpiry * $secondsPerDay);
if ($expiry==0) {
$stat = '(less than a day left)';
} else if ($expiry >= 1) {
$stat = '(' . ( $expiry - $timeStamp ) / $secondsPerDay . ' day';
if ($expiry==1) {} else {
$stat .= 's';
}
$stat .= ' left)';
} else {
$stat = '(expired)';
}
if ($stat == "(expired)") {
$tik = SQLact("query", "SELECT * FROM freelancers_webmasters WHERE username='" . $row7[creator] . "'");
$to2 = SQLact("result", $tik,0,"email");
$subject2 = $companyname . ' Project Frozen';
$message2 = 'Your ' . $companyname . ' project, named "' . $row7[project] . '", has past its due date and has been "frozen". This means no ' . $freelancers . ' can place any new bids, and an action is now required on your part. Login to the account management area at ' . $companyname . '. There are 3 things you can do now: pick a ' . $freelancer . ' for this project, extend the due date of the project (and bidding will continue), or cancel this project.';
mail($to2,$subject2,$message2,"From: $emailaddress");
SQLact("query", "UPDATE freelancers_projects SET status='frozen' WHERE id='" . $row7[id] . "'");
} else {}
}
SQLact("query", "UPDATE freelancers_cron SET lastday=$today");
} else {}
?>
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

if ($run == "now") {
require "vars.php";

$ress = SQLact("query", "SELECT * FROM freelancers_count");
$num_rows = @SQLact("num_rows", $ress);
if ($num_rows==0) {
SQLact("query", "CREATE TABLE freelancers_announcements (date VARCHAR(10) NOT NULL, date2 VARCHAR(10) NOT NULL, announce BLOB NOT NULL, announce2 BLOB NOT NULL)");
SQLact("query", "INSERT INTO freelancers_announcements (date, date2, announce, announce2) VALUES ('', '', '', '')");
SQLact("query", "CREATE TABLE freelancers_archived (date VARCHAR(250) NOT NULL, date2 INT(10) NOT NULL, username VARCHAR(250) NOT NULL, atype VARCHAR(10) NOT NULL, wtype VARCHAR(200) NOT NULL, amount VARCHAR(12) NOT NULL, namount VARCHAR(12) NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_bans (ip VARCHAR(250) NOT NULL, reason BLOB NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_bids (username VARCHAR(250) NOT NULL, status VARCHAR(9) NOT NULL, id VARCHAR(12) NOT NULL, project VARCHAR(250) NOT NULL, special VARCHAR(8) NOT NULL, amount VARCHAR(10) NOT NULL, delivery VARCHAR(4) NOT NULL, date VARCHAR(20) NOT NULL, details BLOB NOT NULL, date2 INT(10) NOT NULL, chosen VARCHAR(250) NOT NULL, outbid CHAR(1) NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_cats (categories VARCHAR(250) NOT NULL)");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Browsing')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Designing')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Emptying')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Inserting')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Joking')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Murdering')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Operating')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Programming')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Repairing')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Resurrecting')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Selecting')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Singing')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Stealing')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Undoing')");
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('Winning')");
SQLact("query", "CREATE TABLE freelancers_count (count VARCHAR(12) NOT NULL)");
SQLact("query", "INSERT INTO freelancers_count (count) VALUES ('102402089011')");
SQLact("query", "CREATE TABLE freelancers_cron (lastday VARCHAR(8) NOT NULL)");
$today = date("Ymd");
SQLact("query", "INSERT INTO freelancers_cron (lastday) VALUES ('$today')");
SQLact("query", "CREATE TABLE freelancers_deposits (username VARCHAR(250) NOT NULL, atype VARCHAR(10) NOT NULL, amount VARCHAR(20) NOT NULL, total VARCHAR(20) NOT NULL, oid VARCHAR(12) NOT NULL, ptype VARCHAR(6) NOT NULL, status VARCHAR(8) NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_edittemp (email VARCHAR(250) NOT NULL, id VARCHAR(10) NOT NULL, username VARCHAR(250) NOT NULL, atype VARCHAR(10) NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_forum (pid VARCHAR(12) NOT NULL, froom VARCHAR(250) NOT NULL, acctype VARCHAR(10) NOT NULL, date VARCHAR(18) NOT NULL, date2 VARCHAR(10) NOT NULL, private VARCHAR(250) NOT NULL, privatetype VARCHAR(10) NOT NULL, message BLOB NOT NULL, mid VARCHAR(10) NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_logins (username VARCHAR(250) NOT NULL, date VARCHAR(10) NOT NULL, atype VARCHAR(10) NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_profits (amount VARCHAR(20) NOT NULL, type VARCHAR(10) NOT NULL, type2 VARCHAR(6) NOT NULL, month CHAR(2) NOT NULL, year VARCHAR(10) NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_programmers (username VARCHAR(250) NOT NULL, password VARCHAR(250) NOT NULL, company VARCHAR(250) NOT NULL, categories BLOB NOT NULL, rate VARCHAR(10) NOT NULL, profile BLOB NOT NULL, notify VARCHAR(250) NOT NULL, id VARCHAR(12) NOT NULL, email VARCHAR(250) NOT NULL, special VARCHAR(4) NOT NULL, ip VARCHAR(250) NOT NULL, wname VARCHAR(250) NOT NULL, waddress VARCHAR(250) NOT NULL, wcity VARCHAR(250) NOT NULL, wzip VARCHAR(250) NOT NULL, wemail VARCHAR(250) NOT NULL, wbankyourname VARCHAR(250) NOT NULL, wbankname VARCHAR(250) NOT NULL, wbankaddress VARCHAR(250) NOT NULL, wbankaddress2 VARCHAR(250) NOT NULL, wbankcity VARCHAR(200) NOT NULL, wbankstate VARCHAR(150) NOT NULL, wbankcountry VARCHAR(185) NOT NULL, wbankzip VARCHAR(20) NOT NULL, wbankaccnum VARCHAR(220) NOT NULL, wbankcode VARCHAR(140) NOT NULL, wbankacctype CHAR(2) NOT NULL, wothercontent BLOB NOT NULL, portfolio BLOB NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_projects (chosen VARCHAR(250) NOT NULL, status VARCHAR(9) NOT NULL, id VARCHAR(12) NOT NULL, date2 INT(10) NOT NULL, project VARCHAR(250) NOT NULL, special VARCHAR(8) NOT NULL, categories BLOB NOT NULL, expires VARCHAR(4) NOT NULL, budgetmin VARCHAR(250) NOT NULL, budgetmax VARCHAR(250) NOT NULL, creation VARCHAR(10) NOT NULL, ctime VARCHAR(5) NOT NULL, creator VARCHAR(250) NOT NULL, description BLOB NOT NULL, attachment VARCHAR(250) NOT NULL, pcat1val BLOB NOT NULL, pcat2val BLOB NOT NULL, pcat3val BLOB NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_ratings (username VARCHAR(250) NOT NULL, rating CHAR(2) NOT NULL, ratedby VARCHAR(250) NOT NULL, projid VARCHAR(12) NOT NULL, projname VARCHAR(250) NOT NULL, projdate VARCHAR(20) NOT NULL, comments BLOB NOT NULL, type VARCHAR(10) NOT NULL, status VARCHAR(5) NOT NULL, type2 VARCHAR(10) NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_refunds (id VARCHAR(10) NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_setup (siteurl VARCHAR(250) NOT NULL, toplayout BLOB NOT NULL, bottomlayout BLOB NOT NULL, emailheader BLOB NOT NULL, emailfooter BLOB NOT NULL, emailaddress VARCHAR(250) NOT NULL, companyname VARCHAR(250) NOT NULL, bsbamount VARCHAR(250) NOT NULL, fsbamount VARCHAR(250) NOT NULL, frefamount VARCHAR(250) NOT NULL, brefamount VARCHAR(250) NOT NULL, address VARCHAR(250) NOT NULL, city VARCHAR(250) NOT NULL, state VARCHAR(250) NOT NULL, country VARCHAR(250) NOT NULL, zipcode VARCHAR(250) NOT NULL, currency VARCHAR(250) NOT NULL, currencytype VARCHAR(250) NOT NULL, buyer VARCHAR(250) NOT NULL, buyers VARCHAR(250) NOT NULL, freelancer VARCHAR(250) NOT NULL, freelancers VARCHAR(250) NOT NULL, mode VARCHAR(4) NOT NULL, adminpass VARCHAR(250) NOT NULL, attachmode VARCHAR(8) NOT NULL, attachmaxi VARCHAR(15) NOT NULL, attachpath VARCHAR(250) NOT NULL, attachurl VARCHAR(250) NOT NULL, catname VARCHAR(250) NOT NULL, pcat1 VARCHAR(250) NOT NULL, pcat1val BLOB NOT NULL, pcat2 VARCHAR(250) NOT NULL, pcat2val BLOB NOT NULL, pcat3 VARCHAR(250) NOT NULL, pcat3val BLOB NOT NULL, balexpdays VARCHAR(250) NOT NULL, balmaxdays VARCHAR(10) NOT NULL, tableborder CHAR(2) NOT NULL, tablecellsp CHAR(2) NOT NULL, tablecellpa CHAR(2) NOT NULL, tablecolort VARCHAR(250) NOT NULL, tablecolorh VARCHAR(250) NOT NULL, tablecolor1 VARCHAR(250) NOT NULL, tablecolor2 VARCHAR(250) NOT NULL, ppemailaddr VARCHAR(250) NOT NULL, deposit2checkout VARCHAR(7) NOT NULL, depositpaypal VARCHAR(7) NOT NULL, depositccurl VARCHAR(250) NOT NULL, depositccper VARCHAR(5) NOT NULL, depositccfee VARCHAR(5) NOT NULL, depositccpay VARCHAR(250) NOT NULL, depositccsid VARCHAR(10) NOT NULL, depositccaut VARCHAR(7) NOT NULL, depositppper VARCHAR(5) NOT NULL, depositppfee VARCHAR(5) NOT NULL, depositppaut VARCHAR(7) NOT NULL, depositmail VARCHAR(7) NOT NULL, depositmlfee VARCHAR(5) NOT NULL, depositother VARCHAR(7) NOT NULL, depositnotify VARCHAR(7) NOT NULL, feedbackcmax VARCHAR(10) NOT NULL, projectper VARCHAR(10) NOT NULL, projectfee VARCHAR(10) NOT NULL, projectper2 VARCHAR(10) NOT NULL, projectfee2 VARCHAR(10) NOT NULL, withdrawcfee VARCHAR(10) NOT NULL, withdrawpaypal VARCHAR(7) NOT NULL, withdrawpfee VARCHAR(10) NOT NULL, withdrawwire VARCHAR(7) NOT NULL, withdrawwfee VARCHAR(10) NOT NULL, withdrawother VARCHAR(7) NOT NULL, withdrawofee VARCHAR(10) NOT NULL, withdrawonam VARCHAR(250) NOT NULL, withdrawoins BLOB NOT NULL, withdrawmini VARCHAR(10) NOT NULL, withdrawnotify VARCHAR(7) NOT NULL, multiplecats VARCHAR(7) NOT NULL, featuredcost VARCHAR(60) NOT NULL, mprojectdays VARCHAR(5) NOT NULL, projectudays VARCHAR(250) NOT NULL, forumnextnum VARCHAR(100) NOT NULL, maxextend CHAR(3) NOT NULL, userportfolio VARCHAR(7) NOT NULL, refreturn1 VARCHAR(250) NOT NULL, refreturn2 VARCHAR(250) NOT NULL)");
$url = 'http://' . $HTTP_HOST . '' . $PHP_SELF;
$url = str_replace("/one-timesetup.php", "", $url);
SQLact("query", "INSERT INTO freelancers_setup (siteurl, toplayout, bottomlayout, emailheader, emailfooter, emailaddress, companyname, bsbamount, fsbamount, frefamount, brefamount, address, city, state, country, zipcode, currency, currencytype, buyer, buyers, freelancer, freelancers, mode, adminpass, attachmode, attachmaxi, attachpath, attachurl, catname, pcat1, pcat1val, pcat2, pcat2val, pcat3, pcat3val, balexpdays, balmaxdays, tableborder, tablecellsp, tablecellpa, tablecolort, tablecolorh, tablecolor1, tablecolor2, ppemailaddr, deposit2checkout, depositpaypal, depositccurl, depositccper, depositccfee, depositccpay, depositccsid, depositccaut, depositppper, depositppfee, depositppaut, depositmail, depositmlfee, depositother, depositnotify, feedbackcmax, projectper, projectfee, projectper2, projectfee2, withdrawcfee, withdrawpaypal, withdrawpfee, withdrawwire, withdrawwfee, withdrawother, withdrawofee, withdrawonam, withdrawoins, withdrawmini, withdrawnotify, multiplecats, featuredcost, mprojectdays, projectudays, forumnextnum, maxextend, userportfolio, refreturn1, refreturn2) VALUES ('" . $url . "', '<title>ProEx101 Freelancers Demo</title>My Header<p>Buyers: <a href=\"buyers.php?new=user\">Sign Up</a> - <a href=\"buyers.php?new=project\">Create Project</a> - <a href=\"buyers.php?login=now\">Manage Account</a> - <a href=\"buyers.php?logout=now\">Sign Out</a><br>Freelancers: <a href=\"freelancers.php?new=user\">Sign Up</a> - <a href=\"freelancers.php?login=now\">Manage Account</a> - <a href=\"freelancers.php?logout=now\">Sign Out</a><br>General: <a href=\"search.php?showstatus=open\">View Open Projects</a> - <a href=\"search.php\">Find A Project</a> - <a href=\"send.php\">Send Message</a><hr>', '<hr><br>My Footer', 'E-Mail Header', 'E-Mail Footer', 'nulledby@drew010.com', 'ProEx101 Freelancers Demo', '5.00', '1.00', '0.25', '0.75', 'Demo Address', 'Demo City', 'Demo State', 'Demo Country', 'Demo Zip/Postal Code', '\$', 'US', 'Buyer', 'Buyers', 'Freelancer', 'Freelancers', 'demo', 'trg8262', 'enabled', '59200', '/home/proex101/public_html/freelancers/uploads', '" . $url . "/uploads', 'Job Type', 'Location', 'Someplace, 4U***Nowhere, IC***Everywhere, UN', '', '', '', '', '25-26-27-28-29', '30', '0', '0', '2', '#000080', '#84BCF4', '#D6E4FE', '#84BCF4', 'webmaster@proex101.com', 'enabled', 'enabled', '', '5.5', '0.45', 'https://www.2checkout.com/cgi-bin/buyers/cartpurchase1.2c?sid=', '14387', 'enabled', '2.9', '0.30', 'enabled', 'enabled', '1', 'enabled', 'enabled', '200', '7', '9.00', '3', '1.00', '1.00', 'enabled', '1.00', 'enabled', '20.00', 'enabled', '1.00', 'Western Union Transfer', 'Provide your name and address please.', '25.00', 'enabled', 'enabled', '10.00', '90', '0-1-2-3', '10', '7', '', '$url', '$url')");
SQLact("query", "CREATE TABLE freelancers_subjects (pid VARCHAR(12) NOT NULL, subject BLOB NOT NULL, date VARCHAR(20) NOT NULL, date2 INT(10) NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_suspends (ip VARCHAR(250) NOT NULL, reason BLOB NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_temp (email VARCHAR(250) NOT NULL, id VARCHAR(12) NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_transactions (amount VARCHAR(250) NOT NULL, details BLOB NOT NULL, username VARCHAR(250) NOT NULL, type VARCHAR(10) NOT NULL, balance VARCHAR(50) NOT NULL, date VARCHAR(100) NOT NULL, date2 INT(10) NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_webmasters (username VARCHAR(250) NOT NULL, password VARCHAR(250) NOT NULL, company VARCHAR(250) NOT NULL, email VARCHAR(250) NOT NULL, bidnotify CHAR(1) NOT NULL, id VARCHAR(12) NOT NULL, ip VARCHAR(250) NOT NULL, special VARCHAR(250) NOT NULL, wname VARCHAR(250) NOT NULL, waddress VARCHAR(250) NOT NULL, wcity VARCHAR(250) NOT NULL, wzip VARCHAR(250) NOT NULL, wemail VARCHAR(250) NOT NULL, wbankyourname VARCHAR(250) NOT NULL, wbankname VARCHAR(250) NOT NULL, wbankaddress VARCHAR(250) NOT NULL, wbankaddress2 VARCHAR(250) NOT NULL, wbankcity VARCHAR(200) NOT NULL, wbankstate VARCHAR(150) NOT NULL, wbankcountry VARCHAR(185) NOT NULL, wbankzip VARCHAR(20) NOT NULL, wbankaccnum VARCHAR(220) NOT NULL, wbankcode VARCHAR(140) NOT NULL, wbankacctype CHAR(2) NOT NULL, wothercontent BLOB NOT NULL)");
SQLact("query", "CREATE TABLE freelancers_withdrawals (date VARCHAR(250) NOT NULL, date2 INT(10) NOT NULL, username VARCHAR(250) NOT NULL, atype VARCHAR(10) NOT NULL, wtype VARCHAR(200) NOT NULL, amount VARCHAR(12) NOT NULL, name VARCHAR(250) NOT NULL, address VARCHAR(250) NOT NULL, city VARCHAR(250) NOT NULL, zip VARCHAR(250) NOT NULL, email VARCHAR(250) NOT NULL, bankyourname VARCHAR(250) NOT NULL, bankname VARCHAR(250) NOT NULL, bankaddress VARCHAR(250) NOT NULL, bankaddress2 VARCHAR(250) NOT NULL, bankcity VARCHAR(200) NOT NULL, bankstate VARCHAR(150) NOT NULL, bankcountry VARCHAR(185) NOT NULL, bankzip VARCHAR(20) NOT NULL, bankaccnum VARCHAR(220) NOT NULL, bankcode VARCHAR(140) NOT NULL, bankacctype CHAR(2) NOT NULL, status VARCHAR(8) NOT NULL, othercontent BLOB NOT NULL, wfee VARCHAR(12) NOT NULL, namount VARCHAR(12) NOT NULL)") or die ("You must first customize the MySQL connections in vars.php to match yours or this script will not be setup!");
mail("nulled@by.drew010","PHP Freelancers Installed (ID: " . time() . ")","This is an automated message resulting from a webmaster installing PHP Freelancers on their server.
Please check the below information and verify that a PHP Freelancers script was actually registered to the below information (also check the \"From\" field of this message for a server email address):
IP: " . $REMOTE_ADDR . "
Installation At: http://" . $HTTP_HOST . "" . $REQUEST_URI . "
Installation Date/Time: " . date("l, F jS, Y @ h:i:s A") . "

Auto-Install Detector
ProEx101 WWW Detects
ProEx101 Web Services","From: webmaster");
echo 'SUCCESS: Your MySQL has now been successfully setup!<br>
Now, <a href="' . $url . '/admin_setup.php?pass=trg8262">click here</a> to finish the setup process by filling out the admin fields, etc.<br>An xCGI release nulled by drew010';
} else {
echo 'ERROR: This file has already been run successfully, nothing has been re-setup!<br>An xCGI release nulled by drew010';
}
} else {
?>
Only run this file once, and make sure you run it before any other file as part of this script, but make sure you customize the MySQL connections in vars.php first!<br>
If you run this file and then get a 404 error or a blank page, don't worry, just run this file again, no limitations apply, but if this file has already successfully run, nothing will be setup over again.<br>
<a href="one-timesetup.php?run=now">Click here</a> to run this setup file.<br>An xCGI release nulled by drew010.
<?php
}
?>
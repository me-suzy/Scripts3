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

require "sql.php";
require "act.php";

if ($sql_mode==2) {
$crishn = pg_connect("$sql_host","5432","$sql_data");
} else if ($sql_mode==3) {
$crishn = mssql_connect($sql_host, $sql_user, $sql_pass);
$crashn = mssql_select_db($sql_data);
} else if ($sql_mode==4) {
$crishn = odbc_connect($sql_data, $sql_user, $sql_pass);
} else {
$crishn = mysql_connect($sql_host, $sql_user, $sql_pass);
$crashn = mysql_select_db($sql_data);
}
$freelancers_count = SQLact("query", "SELECT * FROM freelancers_count");
$count = SQLact("result", $freelancers_count,0,"count");
$freelancers_cron = SQLact("query", "SELECT * FROM freelancers_cron");
$lastday = SQLact("result", $freelancers_cron,0,"lastday");
$freelancers_setup = SQLact("query", "SELECT * FROM freelancers_setup");
$siteurl = SQLact("result", $freelancers_setup,0,"siteurl");
$toplayout = SQLact("result", $freelancers_setup,0,"toplayout");
$bottomlayout = SQLact("result", $freelancers_setup,0,"bottomlayout");
$emailheader = SQLact("result", $freelancers_setup,0,"emailheader");
$emailfooter = SQLact("result", $freelancers_setup,0,"emailfooter");
$emailaddress = SQLact("result", $freelancers_setup,0,"emailaddress");
$companyname = SQLact("result", $freelancers_setup,0,"companyname");
$fsbamount = SQLact("result", $freelancers_setup,0,"fsbamount");
$bsbamount = SQLact("result", $freelancers_setup,0,"bsbamount");
$frefamount = SQLact("result", $freelancers_setup,0,"frefamount");
$brefamount = SQLact("result", $freelancers_setup,0,"brefamount");
$address = SQLact("result", $freelancers_setup,0,"address");
$city = SQLact("result", $freelancers_setup,0,"city");
$state = SQLact("result", $freelancers_setup,0,"state");
$country = SQLact("result", $freelancers_setup,0,"country");
$zipcode = SQLact("result", $freelancers_setup,0,"zipcode");
$currency = SQLact("result", $freelancers_setup,0,"currency");
$currencytype = SQLact("result", $freelancers_setup,0,"currencytype");
$buyer = SQLact("result", $freelancers_setup,0,"buyer");
$buyers = SQLact("result", $freelancers_setup,0,"buyers");
$freelancer = SQLact("result", $freelancers_setup,0,"freelancer");
$freelancers = SQLact("result", $freelancers_setup,0,"freelancers");
$mode = SQLact("result", $freelancers_setup,0,"mode");
$adminpass = SQLact("result", $freelancers_setup,0,"adminpass");
$attachmode = SQLact("result", $freelancers_setup,0,"attachmode");
$attachmaxi = SQLact("result", $freelancers_setup,0,"attachmaxi");
$attachpath = SQLact("result", $freelancers_setup,0,"attachpath");
$attachurl = SQLact("result", $freelancers_setup,0,"attachurl");
$catname = SQLact("result", $freelancers_setup,0,"catname");
$pcat1 = SQLact("result", $freelancers_setup,0,"pcat1");
$pcat1val = SQLact("result", $freelancers_setup,0,"pcat1val");
$pcat2 = SQLact("result", $freelancers_setup,0,"pcat2");
$pcat2val = SQLact("result", $freelancers_setup,0,"pcat2val");
$pcat3 = SQLact("result", $freelancers_setup,0,"pcat3");
$pcat3val = SQLact("result", $freelancers_setup,0,"pcat3val");
$balexpdays = SQLact("result", $freelancers_setup,0,"balexpdays");
$balmaxdays = SQLact("result", $freelancers_setup,0,"balmaxdays");
$tableborder = SQLact("result", $freelancers_setup,0,"tableborder");
$tablecellsp = SQLact("result", $freelancers_setup,0,"tablecellsp");
$tablecellpa = SQLact("result", $freelancers_setup,0,"tablecellpa");
$tablecolort = SQLact("result", $freelancers_setup,0,"tablecolort");
$tablecolorh = SQLact("result", $freelancers_setup,0,"tablecolorh");
$tablecolor1 = SQLact("result", $freelancers_setup,0,"tablecolor1");
$tablecolor2 = SQLact("result", $freelancers_setup,0,"tablecolor2");
$ppemailaddr = SQLact("result", $freelancers_setup,0,"ppemailaddr");
$deposit2checkout = SQLact("result", $freelancers_setup,0,"deposit2checkout");
$depositpaypal = SQLact("result", $freelancers_setup,0,"depositpaypal");
$depositnotify = SQLact("result", $freelancers_setup,0,"depositnotify");
$depositccurl = SQLact("result", $freelancers_setup,0,"depositccurl");
$depositccper = SQLact("result", $freelancers_setup,0,"depositccper");
$depositccfee = SQLact("result", $freelancers_setup,0,"depositccfee");
$depositccpay = SQLact("result", $freelancers_setup,0,"depositccpay");
$depositccsid = SQLact("result", $freelancers_setup,0,"depositccsid");
$depositccaut = SQLact("result", $freelancers_setup,0,"depositccaut");
$depositppper = SQLact("result", $freelancers_setup,0,"depositppper");
$depositppfee = SQLact("result", $freelancers_setup,0,"depositppfee");
$depositppaut = SQLact("result", $freelancers_setup,0,"depositppaut");
$depositmail = SQLact("result", $freelancers_setup,0,"depositmail");
$depositmlfee = SQLact("result", $freelancers_setup,0,"depositmlfee");
$depositother = SQLact("result", $freelancers_setup,0,"depositother");
$feedbackcmax = SQLact("result", $freelancers_setup,0,"feedbackcmax");
$projectper = SQLact("result", $freelancers_setup,0,"projectper");
$projectfee = SQLact("result", $freelancers_setup,0,"projectfee");
$projectper2 = SQLact("result", $freelancers_setup,0,"projectper2");
$projectfee2 = SQLact("result", $freelancers_setup,0,"projectfee2");
$withdrawcfee = SQLact("result", $freelancers_setup,0,"withdrawcfee");
$withdrawpaypal = SQLact("result", $freelancers_setup,0,"withdrawpaypal");
$withdrawpfee = SQLact("result", $freelancers_setup,0,"withdrawpfee");
$withdrawwire = SQLact("result", $freelancers_setup,0,"withdrawwire");
$withdrawwfee = SQLact("result", $freelancers_setup,0,"withdrawwfee");
$withdrawother = SQLact("result", $freelancers_setup,0,"withdrawother");
$withdrawofee = SQLact("result", $freelancers_setup,0,"withdrawofee");
$withdrawonam = SQLact("result", $freelancers_setup,0,"withdrawonam");
$withdrawoins = SQLact("result", $freelancers_setup,0,"withdrawoins");
$withdrawmini = SQLact("result", $freelancers_setup,0,"withdrawmini");
$withdrawnotify = SQLact("result", $freelancers_setup,0,"withdrawnotify");
$multiplecats = SQLact("result", $freelancers_setup,0,"multiplecats");
$featuredcost = SQLact("result", $freelancers_setup,0,"featuredcost");
$mprojectdays = SQLact("result", $freelancers_setup,0,"mprojectdays");
$projectudays = SQLact("result", $freelancers_setup,0,"projectudays");
$forumnextnum = SQLact("result", $freelancers_setup,0,"forumnextnum");
$maxextend = SQLact("result", $freelancers_setup,0,"maxextend");
$userportfolio = SQLact("result", $freelancers_setup,0,"userportfolio");
$refreturn1 = SQLact("result", $freelancers_setup,0,"refreturn1");
$refreturn2 = SQLact("result", $freelancers_setup,0,"refreturn2");
?>
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

if ($uninstall == "now") {
if (!$submit) {
echo '<form name="uninstall" action="uninstall.php?uninstall=now" method="post">
MySQL DataBase PassWord: <input type="password" name="dbpassword"> &nbsp;Tip: Your MySQL database password is almost always your FTP password, but may differ.<br>
<input type="submit" name="submit" value="Validate">
</form>';
} else {
if ($dbpassword == $sql_pass) {
SQLact("query", "DROP TABLE freelancers_announcements");
SQLact("query", "DROP TABLE freelancers_archived");
SQLact("query", "DROP TABLE freelancers_bans");
SQLact("query", "DROP TABLE freelancers_bids");
SQLact("query", "DROP TABLE freelancers_cats");
SQLact("query", "DROP TABLE freelancers_count");
SQLact("query", "DROP TABLE freelancers_cron");
SQLact("query", "DROP TABLE freelancers_deposits");
SQLact("query", "DROP TABLE freelancers_edittemp");
SQLact("query", "DROP TABLE freelancers_forum");
SQLact("query", "DROP TABLE freelancers_logins");
SQLact("query", "DROP TABLE freelancers_profits");
SQLact("query", "DROP TABLE freelancers_programmers");
SQLact("query", "DROP TABLE freelancers_projects");
SQLact("query", "DROP TABLE freelancers_ratings");
SQLact("query", "DROP TABLE freelancers_refunds");
SQLact("query", "DROP TABLE freelancers_setup");
SQLact("query", "DROP TABLE freelancers_subjects");
SQLact("query", "DROP TABLE freelancers_suspends");
SQLact("query", "DROP TABLE freelancers_temp");
SQLact("query", "DROP TABLE freelancers_transactions");
SQLact("query", "DROP TABLE freelancers_webmasters");
SQLact("query", "DROP TABLE freelancers_withdrawals") or die ("Could not uninstall PHP Freelancers.  The script has not yet been setup, or has already successfully been uninstalled.");
echo 'PHP Freelancers has been successfully uninstalled from your website.
All MySQL tables have been emptied and deleted from database: ' . $sql_data;
} else {
echo 'Validation Results:  You are not authorized to uninstall PHP Freelancers from this website, therefore making you not the real admin.  If you know you are the admin, but entered the password incorrectly, <a href="javascript:history.go(-1);">click here</a> to go back.<br>
If you cannot remember the password, from the PHP Freelancers directory on your local computer, open the file vars.php in an editor, preferrably NotePad.';
}
}
} else {
echo 'Warning:  Once you click on the uninstall link, there is no turning back!  Every last record in your database that was added by PHP Freelancers will be deleted and never restorable.<br>
If you don\'t want to continue with the uninstall progress, <a href="javascript:history.go(-1);">click here</a>.<br>
If you do want to uninstall, <a href="' . $siteurl . '/uninstall.php?uninstall=now">click here</a> where you will be asked for your password to your MySQL database.  This is for validation to confirm that you are the admin and you do have the uninstall privileges.';
}
?>
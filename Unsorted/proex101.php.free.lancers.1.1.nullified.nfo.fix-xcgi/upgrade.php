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

SQLact("query", "ALTER TABLE freelancers_programmers ADD portfolio BLOB NOT NULL");
SQLact("query", "ALTER TABLE freelancers_setup ADD userportfolio VARCHAR(7) NOT NULL");
SQLact("query", "ALTER TABLE freelancers_setup ADD refreturn1 VARCHAR(250) NOT NULL");
SQLact("query", "ALTER TABLE freelancers_setup ADD refreturn2 VARCHAR(250) NOT NULL");
SQLact("query", "UPDATE freelancers_setup SET userportfolio='', refreturn1='$siteurl', refreturn2='$siteurl'");
echo 'You have successfully upgraded your databases for PHP Freelancers 1.1!';
?>
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

if (!$sql_mode) {
require "vars.php";
require "cron.php";
}

if ($count == "f") {
$result = SQLact("query", "SELECT * FROM freelancers_programmers");
echo SQLact("num_rows", $result);
} else if ($count == "b") {
$result = SQLact("query", "SELECT * FROM freelancers_webmasters");
echo SQLact("num_rows", $result);
} else {
$result = SQLact("query", "SELECT * FROM freelancers_programmers");
$result2 = SQLact("query", "SELECT * FROM freelancers_webmasters");
echo SQLact("num_rows", $result)+SQLact("num_rows", $result2);
}
?>
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

function SQLact ($reaction, $revalue, $revalue2="", $revalue3="") {
if ($sql_mode==2) {
if ($reaction == "query") {
return @pg_exec($crishn, $revalue);
} else if ($reaction == "result") {
return @pg_result($revalue,$revalue2,$revalue3);
} else if ($reaction == "num_rows") {
return @pg_numrows($revalue);
} else if ($reaction == "fetch_array") {
return @pg_fetch_array($revalue);
}
} else if ($sql_mode==3) {
if ($reaction == "query") {
return @mssql_query($revalue);
} else if ($reaction == "result") {
return @mssql_result($revalue,$revalue2,$revalue3);
} else if ($reaction == "num_rows") {
return @mssql_num_rows($revalue);
} else if ($reaction == "fetch_array") {
return @mssql_fetch_array($revalue);
}
} else if ($sql_mode==4) {
if ($reaction == "query") {
return @odbc_exec($crishn, $revalue);
} else if ($reaction == "result") {
return @odbc_result($revalue,$revalue2,$revalue3);
} else if ($reaction == "num_rows") {
return @odbc_num_rows($revalue);
} else if ($reaction == "fetch_array") {
return @odbc_fetch_array($revalue);
}
} else {
if ($reaction == "query") {
return @mysql_query($revalue);
} else if ($reaction == "result") {
return @mysql_result($revalue,$revalue2,$revalue3);
} else if ($reaction == "num_rows") {
return @mysql_num_rows($revalue);
} else if ($reaction == "fetch_array") {
return @mysql_fetch_array($revalue);
}
}
}
?>
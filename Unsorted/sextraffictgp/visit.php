<?php
include("stconfig.php");

   mysql_query("update st_links set clicks=clicks+1 where linkid=$linkid");
    $result3 = mysql_query("SELECT url from st_links where linkid='$linkid'");
    list($url) = mysql_fetch_row($result3);
	?>
    <meta http-equiv ="Refresh" content = "0 ; URL=<?=$url?>">
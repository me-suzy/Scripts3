<?php

#############################################################################
#############################################################################
##                                                                         ##
##                  ________   __         _     __      __                 ##
##                 / _______|  | |       |_|    \ \    / /                 ##
##                 | |         | |        _      \ \  / /                  ##
##                 | |         | |       | |      \ \/ /                   ##
##                 | |         | |       | |      / /\ \                   ##
##                 | |______   | |_____  | |     / /  \ \                  ##
##                 \________|  \_______| |_|    /_/    \_\                 ##
##                                                                         ##
##                             Script by CLiX                              ##
#############################################################################
#############################################################################
##  PHPHomeXchange                Version 2.0                              ##
##  Created 1/15/01               Created by CLiX                          ##
##  CopyRight © 2002 CLiX         clix@theclixnetwork.com                  ##
##  Get other scripts at:         theclixnetwork.com                       ##
#############################################################################
#############################################################################
##                                                                         ##
##  PHPHomeXchange Users are subject to the TOS at theclixnetwork.com. TOS ##
##  can change at any time. You MAY NOT redistribute or sell the script in ##
##  any way. If you intend on selling the site created from PHPHomeXchange ##
##  you must contact us for permission first!                              ##
##                                                                         ##
#############################################################################
#############################################################################
mt_srand((double)microtime()*1000000);
include "config.inc";
$BANIP = array();
$BANIP = file("bannedip.txt");
for ($i=0; $i<count($BANIP); $i++) {
   if ($REMOTE_ADDR == $BANIP[$i]) {
   	include "header.inc";
   	echo "<FONT COLOR=RED><B>You have been banned from using the services on this site.</B></FONT>";
   	include "footer.inc";
   	exit();
   }
}

### CONNECT TO DATABASE
$global_dbh = mysql_connect($dbhost, $dbusername, $dbpassword);
mysql_select_db($dbname, $global_dbh);


### FIND A VALID USERNAME
$query = "SELECT username, urls FROM $dbtable WHERE creditsearned - creditsused > 1 AND ispaused = 0 AND username != '$username'";
$result = mysql_query($query, $global_dbh);

### SELECT A RANDOM USER
echo mysql_error($global_dbh);
$blaher = mysql_num_rows($result);
if ($blaher >= 1) {
$blaher = $blaher - 1;
echo mysql_error($global_dbh);
if ($blaher == 0) { $num = 0; } else { $num = mt_rand(0, $blaher); }
$frm2 = mt_rand(0, 100) . "f";
$frm3 = mt_rand(0, 100) . "f";

### GET USER INFO
mysql_data_seek($result, $num);
echo mysql_error($global_dbh);
$row = mysql_fetch_array($result);
echo mysql_error($global_dbh);
### FIND A RANDOM VALID URL



$urls = explode("|", $row[1]);

$ran = mt_rand(0, 10);
if ($urls[$ran] != "") { $foundurl=$urls[$ran]; } else { $foundurl = $urls[0]; }
$user = $row[0];
echo mysql_error($global_dbh);
}
else {
    $user = "";
    $foundurl = $defurl;
    }
    
?>
<HTML>
 <HEAD>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache"> 
 <SCRIPT>
 <!--
 if (top.location != self.location) {
 	top.location = self.location
 }
 -->
 </SCRIPT>
  <TITLE><?php PRINT("$frametitle"); ?></TITLE>
 </HEAD>
 <FRAMESET ROWS="<?php echo $frameheight; ?>,100%" FRAMESPACING="0" BORDER="0">
  <FRAME MARGINWIDTH="0" MARGINHEIGHT="0" NAME="<?php PRINT("$frm3"); ?>" NORESIZE SCROLLING=NO SRC="count.php?u=<?php echo $username; ?>&o=<?php echo $user; ?>&a=bot&z=<?php echo $z; ?>" FRAMEBORDER="0">
  <FRAME MARGINWIDTH="0" MARGINHEIGHT="0" NAME="<?php PRINT("$frm2"); ?>" SCROLLING=AUTO SRC="<?php PRINT("$foundurl"); ?>" FRAMEBORDER="0">
  <NOFRAMES>
   <BODY>
    <P>
     This page is designed for use with a browser that supports frames. 
   </BODY>
  </NOFRAMES>
 </FRAMESET>
</HTML>



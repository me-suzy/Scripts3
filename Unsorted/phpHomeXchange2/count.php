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

	### CONNECT TO DATABASE
	$global_dbh = mysql_connect($dbhost, $dbusername, $dbpassword);
	mysql_select_db($dbname, $global_dbh);

if ($a == "bot") {
	$t = time();
	$query = "UPDATE $dbtable SET lasttime='$t' WHERE username = '$u'";
	$result = mysql_query($query, $global_dbh);
	?>
	<SCRIPT>
setTimeout("spsh()", <?php echo $framerate ?>);
function spsh() {
	document.location = "count.php?z=<?php print("$z"); ?>&u=<?php print("$u"); ?>&o=<?php print("$o"); ?>&a=don&t=<?php print("$t"); ?>";
}
function refr() {
	alert("Wait to receive credit");
}
function repr2() {
	top.location = "member.php?action=report&u=<?php print("$u"); ?>&o=<?php print("$o"); ?>";
}
function repr() {
	input_box=confirm("Click OK to report <?php print("$o"); ?>");
	if (input_box==true) {
		repr2()
	}
}
</SCRIPT>
<?php
	include "firstframe.inc";
	exit();
}
if ($a == "don") {
	$newz = mt_rand(0, 1000000);

	$query1 = "SELECT * FROM $dbtable WHERE username = '$u'";
	$result1 = mysql_query($query1, $global_dbh);
	$row1 = mysql_fetch_array($result1);
	if ($row1["lastip"] != $REMOTE_ADDR) {
		$t = $row1["lasttime"];
		$z = $row1["checkcode"];
	}
	if ($row1["lasttime"] == $t) {
		$t = time();
		if (($row1["memberlevel"] == 0) || ($row1["checkcode"] != $z)) { $creditsnow = $row1["creditsearned"]; } else { $creditsnow = $row1["creditsearned"] + $level0; }
		$query1 = "UPDATE $dbtable SET checkcode='$newz', creditsearned='$creditsnow', lastip='$REMOTE_ADDR', lasttime='$t' WHERE username = '$u'";
		$result1 = mysql_query($query1, $global_dbh);
		
		$query2 = "SELECT * FROM $dbtable WHERE username = '$o'";
		$result2 = mysql_query($query2, $global_dbh);
		$row2 = mysql_fetch_array($result2);
		$creditsnow = $row2["creditsused"] + 1;
		$query2 = "UPDATE $dbtable SET creditsused='$creditsnow' WHERE username = '$o'";
		$result2 = mysql_query($query2, $global_dbh);
		
		$temp2 = $row1["referer"];
		$query2 = "SELECT * FROM $dbtable WHERE username = '$temp2'";
		$result2 = mysql_query($query2, $global_dbh);
		$row2 = mysql_fetch_array($result2);
		$creditsnow = $row2["creditsearned"] + $level1;
		$query2 = "UPDATE $dbtable SET creditsearned='$creditsnow' WHERE username = '$temp2'";
		$result2 = mysql_query($query2, $global_dbh);
		
		$temp2 = $row2["referer"];
		$query2 = "SELECT * FROM $dbtable WHERE username = '$temp2'";
		$result2 = mysql_query($query2, $global_dbh);
		$row2 = mysql_fetch_array($result2);
		$creditsnow = $row2["creditsearned"] + $level2;
		$query2 = "UPDATE $dbtable SET creditsearned='$creditsnow' WHERE username = '$temp2'";
		$result2 = mysql_query($query2, $global_dbh);
		
		$temp2 = $row2["referer"];
		$query2 = "SELECT * FROM $dbtable WHERE username = '$temp2'";
		$result2 = mysql_query($query2, $global_dbh);
		$row2 = mysql_fetch_array($result2);
		$creditsnow = $row2["creditsearned"] + $level3;
		$query2 = "UPDATE $dbtable SET creditsearned='$creditsnow' WHERE username = '$temp2'";
		$result2 = mysql_query($query2, $global_dbh);
		
		$temp2 = $row2["referer"];
		$query2 = "SELECT * FROM $dbtable WHERE username = '$temp2'";
		$result2 = mysql_query($query2, $global_dbh);
		$row2 = mysql_fetch_array($result2);
		$creditsnow = $row2["creditsearned"] + $level4;
		$query2 = "UPDATE $dbtable SET creditsearned='$creditsnow' WHERE username = '$temp2'";
		$result2 = mysql_query($query2, $global_dbh);
		
		
	}
}
	?>
	<SCRIPT>
<?php if ($row1["memberlevel"] == 0) { ?> top.location = "<?php $scriptsurl; ?>/member.php?action=activatederror"; <?php } ?>
	function refr() {
		document.goto.submit();
		return false;
	}
function repr2() {
	top.location = "member.php?action=report&u=<?php print("$u"); ?>&o=<?php print("$o"); ?>";
}
function repr() {
	input_box=confirm("Click OK to report <?php print("$o"); ?>");
	if (input_box==true) {
		repr2()
	}
}
	</SCRIPT>
	  <FORM METHOD="post" NAME="goto" ACTION="start.php" TARGET="_top">
   <INPUT TYPE=HIDDEN NAME="username" VALUE="<?php echo $u; ?>">
    <INPUT TYPE=HIDDEN NAME="z" VALUE="<?php echo $newz; ?>">
      </FORM>
	<?php
	include "secondframe.inc";
	exit();




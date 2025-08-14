<?php
error_reporting(7);
function gotonext($extra="") {
	global $step,$thisscript;
	$nextstep = $step+1;
	echo "<p>$extra</p>\n";
	echo("<p><a href=\"$thisscript?step=$nextstep\"><b>Click here to continue --&gt;</b></a></p>\n");
}

require ("./global.php");
?>
<HTML><HEAD>
<META content="text/html; charset=windows-1252" http-equiv=Content-Type>
<META content="MSHTML 5.00.3018.900" name=GENERATOR></HEAD>
<link rel="stylesheet" href="../cp.css">
<title>vbPortal 3.0 block upgrade script</title>
</HEAD>
<BODY>
<table width="100%" bgcolor="#3F3849" cellpadding="2" cellspacing="0" border="0"><tr><td>
<table width="100%" bgcolor="#524A5A" cellpadding="3" cellspacing="0" border="0"><tr>
<td><a href="http://www.phpportals.com/" target="_blank"><img src="cp_logo.gif" width="160" height="49" border="0" alt="Click here to visit the support forums."></a></td>
<td width="100%" align="center">
<p><font size="2" color="#F7DE00"><b>vbPortal 3.0 block upgrade script</b></font></p>
<p><font size="1" color="#F7DE00"><b>This should only take a few seconds to complete</b></font></p>
</td></tr></table></td></tr></table>
<br>
<?php

if (!$step) {
  $step = 1;
}

// ******************* STEP 1 *******************
if ($step==1) {
  ?>
 <p>This script will make the necessary changes to upgrade vbPortal 3.0 Block Tables on your vBulletin site.</p>
 <br> 
 <p>If you have not previously installed vbPortal 3.0 You will not have to run this script.</p>
 <br> 
 <p>ABORT THIS UPDATE BY <a href=./blockupdate.php?step=3 target=_self><b>CLICKING HERE --&gt;</B></a></b>
 <br>
 <p>Continue with the update by <a href=./blockupdate.php?step=2 target=_self><b>Clicking here --&gt;</B></a></b>
 <br>
 <?php
 }

// ******************* STEP 2 *******************
if ($step==2) {
   echo("<br><b>Modifying the 'vbPortal 3.0 Block tables'</b><br>");
   // begin modification to the "block' tables
   $DB_site->query("ALTER TABLE nuke_centerblocks ADD templates int(1) DEFAULT '1' NOT NULL AFTER last_update");
   $DB_site->query("ALTER TABLE nuke_forumblocks ADD templates int(1) DEFAULT '1' NOT NULL AFTER last_update");
   $DB_site->query("ALTER TABLE nuke_advblocks ADD templates int(1) DEFAULT '1' NOT NULL AFTER last_update");
   // end modification to 'vbPortal 3.0 Block tables'
   echo "<br><b>Done</b><br>";
   echo "<br><b>That was quick, Now you can upgrade all the vbPortal Scripts.<b><br>";
   echo "<br><b>Your modified vbulletin Scripts are good to go.<b><br>";
   echo "<br><b>No modifications required there.<b><br>";
}
if ($step==3) {
echo "<br><b>Thank you for trying vbPortal.<b><br>";
echo "<br><b>The upgrade was aborted!<b><br>";
}
?>
</body>
</html>

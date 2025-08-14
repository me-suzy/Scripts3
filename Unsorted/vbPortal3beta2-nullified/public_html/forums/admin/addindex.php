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
<title>vbPortal indexing script</title>
</HEAD>
<BODY>
<table width="100%" bgcolor="#3F3849" cellpadding="2" cellspacing="0" border="0"><tr><td>
<table width="100%" bgcolor="#524A5A" cellpadding="3" cellspacing="0" border="0"><tr>
<td><a href="http://www.phpportals.com/" target="_blank"><img src="cp_logo.gif" width="160" height="49" border="0" alt="Click here to visit the support forums."></a></td>
<td width="100%" align="center">
<p><font size="2" color="#F7DE00"><b>vbPortal 3.0 thread indexing script</b></font></p>
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
 <br>This script will alter the vBulletin thread table by adding a pollid index for vbPortal 3.0.
 <br> 
 <p>ABORT THIS PROCEDURE BY <a href=./addindex.php?step=4 target=_self><b>CLICKING HERE --&gt;</B></a></b>
 <br><br>
 <p><b>Do it...</b>
  Select this Option to alter the vBulletin thread table.<br>
  by adding an index for pollid by <a href=./addindex.php?step=2 target=_self><b>Clicking here --&gt;</B></a></b></p>
 
 <?php
 }

// ******************* STEP 2 *******************
if ($step==2) {

  echo("<br><b>Indexing the 'thread' table on pollid</b><br>");
// begin indexing the "thread' table
   $DB_site->query("ALTER TABLE thread ADD INDEX (pollid)");
// end indexing"



gotonext();
}
// ******************* STEP 3 *******************

if ($step==3) {
  
 echo "<br>You have completed the thread table indexing of your vBulletin site.<br>";
 echo "Don't forget to delete this script.<br>";
 
}

if ($step==4) {
  
 echo "<br>You have aboarted the thread table indexing of your vBulletin site.<br>";
 echo "Don't forget to delete this script.<br>";
 
}
?>
</body>
</html>
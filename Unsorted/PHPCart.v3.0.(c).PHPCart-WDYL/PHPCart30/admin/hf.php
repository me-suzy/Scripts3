<?php
/////////////////////////////////////////////////////////////////////////////
//                                                                         //
// Program Name         : PHPCart                                          //
// Release Version      : 3.0                                              //
// Program Author       : Mr B Wiencierz                                   //
// Home Page            : http://www.phpcart.co.uk                         //
// Supplied by          : CyKuH [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                      //
// Tested by            : WTN Team                                         //
// Packaged by          : WTN Team                                         //
// Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                         //
//                All code is ©2000-2004 Barry Wiencierz.                  //
//                                                                         //
/////////////////////////////////////////////////////////////////////////////
function pageHeader() {
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>Welcome to......</title>\n";
	echo "</head>\n";
	echo "<body topmargin=0 leftmargin=0>\n";
	echo "<div align=center>\n";
	echo "<center>\n";
	echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
	echo "<tr>\n";
	echo "<td width=50%><img border=0 src=images/top1.gif width=298 height=66><img border=0 src=images/top2.jpg width=338 height=66></td>\n";
	echo "<td width=50% background=images/top3.gif>\n";
	echo "<p align=center><font face=Verdana size=1>Logged in:<br>\n";
	echo "<b><i>\n";
	echo "<? echo \"$$PHP_AUTH_USER\"; ?></i></b></font><b><font size=2 face=Verdana><br>\n";
	echo "<br>\n";
	echo "Settings</font></b></p>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=100% colspan=2>\n";
	echo "<div align=center>\n";
	echo "<table border=0 cellpadding=0 cellspacing=0 width=85%>\n";
	echo "<tr>\n";
	echo "<td width=100%>\n";
	echo "<hr>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</center>\n";
	echo "</div>\n";
	echo "<div align=center>\n";
	echo "<center>\n";
	echo "<table border=0 cellpadding=0 width=95% bgcolor=#808080 cellspacing=1 height=100>\n";
	echo "<tr>\n";
	echo "<td width=100%>\n";
	echo "<div align=center>\n";
	echo "<center>\n";
	echo "<table border=0 cellpadding=0 cellspacing=0 width=100% height=100>\n";
	echo "<tr>\n";
	echo "<td width=100% bgcolor=#FFFFFF>\n";
	echo "<div align=center>\n";
	echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
	echo "<tr>\n";
	echo "<td width=100%>\n";
	echo "<p align=center><font size=2 face=Verdana><b>Welcome to PHPCart Admin Interface!</b></font></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	echo "<p align=center>\n";
	echo $body;

	$GLOBALS["pageHeaderSent"] = TRUE;
}

?>
<?php
function pageFooter() {
	echo "<br>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</center>\n";
	echo "</div>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</center>\n";
	echo "</div>\n";
	echo "<div align=center>\n";
	echo "<center>\n";
	echo "<table border=0 cellpadding=0 cellspacing=0 width=80%>\n";
	echo "<tr>\n";
	echo "<td width=100%>\n";
	echo "<p align=center><font face=Verdana size=1>Copyright © 2001 - 2002 <!--CyKuH [WTN]-->PHPCart, All Rights Reserved</font></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</center>\n";
	echo "</div>\n";
	echo "<BR>\n";
	echo "</body>\n";
	echo "</html>\n";
}
?>
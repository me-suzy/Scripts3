<?php
#$Id: logout.tpl.php,v 1.1 2003/08/08 19:26:23 ryan Exp $

$text = "<table border=0 cellpadding=0 cellspacing=1 width=200 class=outline align=center>";
$text .= "<tr><td colspan=2 class=title align=center>" . $this->textArray['Logout'] . "</td></tr>";
$text .= "<tr><td width=100% class=main><table border=0 cellpadding=2 cellspacing=0 align=center width=100%>";
$text .= "<form method=post action=users.php?action=login>";
$text .= "<tr><td class=main><input type=hidden name=confirm value=1><input type=hidden name=url value='$url'>";
$text .= "</td></tr>";
$text .= "<tr><td class=main align=center>" . $this->textArray['Already logged in'] . "</td></tr>";
$text .= "<tr><td align=center class=main><input type=submit class=submit value=" . $this->textArray['Logout'] . "></td></tr>";
$text .= "</form></table></td></tr></table>";


?>

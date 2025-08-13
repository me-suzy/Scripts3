<?php
#$Id: search.tpl.php,v 1.2 2003/08/08 19:26:23 ryan Exp $
//! Search Template

$text = "<table border=0 cellspacing=1 cellpadding=0 width=250 class=bg align=center>";
$text .= "<tr><td class=title align=center>" . $this->siteName . " " . $this->textArray['Search'] . "</td></tr>";
$text .= "<tr><td class=main>";
$text .= "<table border=0 cellpadding=0 cellspacing=0 width=95% align=center>";
$text .= "<form method=post action=index.php?action=search&id=$id><input type=hidden name=confirm value=1>";
$text .= "<tr><td align=center class=main>" . $this->textArray['Search'] . ": <input type=text name=keywords size=20 value='$keywords'></td>";
$text .= "</tr>";
$text .= "<tr>";
$text .= "<td align=center class=main><input type=submit class=submit value=" . $this->textArray['Search'] . "></td>";
$text .= "</tr>";
$text .= "</table></td></form></tr></table><p>";

?>

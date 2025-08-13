<?php
#$Id: whoisonline.tpl.php,v 1.1 2003/10/14 19:49:16 ryan Exp $
//! Poll Template

$text = "<table border=0 cellpadding=0 cellspacing=1 width=450 class=outline align=left>";
$text .= "<tr><td class=title align=center> " . $this->textArray['Who is'] . " " . ucfirst($this->textArray['online']) . "</td></tr>";
$text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 align=center width=95% class=main>";
$text .= $online;
$text .= "</td></form></tr></table></td></tr></table><p>";





?>

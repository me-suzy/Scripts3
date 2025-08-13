<?php
#$Id: poll.tpl.php,v 1.1 2003/10/14 19:49:16 ryan Exp $
//! Poll Template

$text = "<table border=0 cellpadding=2 cellspacing=1 width=400 class=outline align=left>";
$text .= "<tr><td class=medtitle>$poll_text</td></tr>";
$text .= "<tr><td class=main><table border=0 cellpadding=1 cellspacing=0 align=center width=100%>";
$text .= $pollData;
$text .= "<tr><td class=main colspan=2 align=center><a href=forums.php?forum_id=$forum_id&topic_id=$topic_id>" . $this->textArray['View'] . " " . $this->textArray['Results'] . " | " . $this->textArray['Comments'] . " ($comments)</a></td></tr>";
$text .= "</table></td></tr></table>";
?>

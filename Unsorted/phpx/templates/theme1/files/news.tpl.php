<?php
#$Id: news.tpl.php,v 1.5 2003/10/14 19:49:16 ryan Exp $
//! News Template

$text .= "<table width=500 cellspacing=1 cellpadding=0 border=0 align=center class=outline>";
$text .= "<tr>";
$text .= "<td align=left class=title>$new $category $title</td></tr>";
$text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=500 align=center>";
$text .= "<tr><td class=small>";
$text .= "<b>" . $this->textArray['Posted by'] . " <a href=users.php?action=view&user_id=$user_id>$username</a> " . $this->textArray['on'] . " $date</b>";
$text .= "</td></tr>";
$text .= "<tr><td colspan=2 align=left class=main>";
$text .= "$cat_image $post $more";
$text .= "</td></tr>";
$text .= "<tr><td class=main height=10>&nbsp;</td></tr>";
$text .= "<tr><td class=main>";
$text .= "$readMore";
$text .= "</td></tr>";
$text .= $printEmail;
$text .= "</table></td></tr>";
$text .= "</table><br><br>";

?>

<?php
#$Id: newsHeadline.tpl.php,v 1.3 2003/09/09 15:02:14 ryan Exp $
//! News Headline Template

$text .= "<table width=95% cellspacing=0 cellpadding=0 border=0 class=bg align=center>";
$text .= "<tr><td class=main>";
$text .= "$new <a href=index.php?id=$id&news_id=$news_id>$title</a> - $date";
$text .= "</td></tr></table>";

?>

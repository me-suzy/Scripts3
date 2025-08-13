<?php
# $Id: help.php,v 1.1 2003/07/08 18:29:56 ryan Exp $
//! Creates pop-up frame for help text
require("includes/help.inc.php");
require("includes/auth.inc.php");
?>
<html><head><title>PHPX Help</title></head>
<frameset cols="225,*">
<frame src=helpmenu.php name=menu>
<?php
print "<frame src=helpbody.php?help_id=$topic name=body>";
?>
</frameset>
</html>


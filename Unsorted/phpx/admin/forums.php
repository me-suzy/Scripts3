<?php
#$Id: forums.php,v 1.1 2003/07/25 13:34:18 ryan Exp $
require("includes/forums.inc.php");
require("includes/auth.inc.php");

new forumModule($userinfo);

?>

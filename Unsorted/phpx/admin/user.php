<?php
#$Id: user.php,v 1.3 2003/07/03 21:00:31 ryan Exp $
require("includes/user.inc.php");
require("includes/auth.inc.php");

new userModule($userinfo);

?>

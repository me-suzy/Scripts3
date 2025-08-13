<?php
#$Id: theme.php,v 1.2 2003/07/03 21:00:31 ryan Exp $
require('includes/auth.inc.php');
require('includes/theme.inc.php');
new themeModule($userinfo);
?>


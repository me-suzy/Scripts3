<?php
#$Id: index.php,v 1.4 2003/07/23 14:41:33 ryan Exp $
require("includes/auth.inc.php");
if ($action == logout){
    $core = new coreFunctions();
    $core->logout($userinfo);
}

$body = getIndex($userinfo);

?>




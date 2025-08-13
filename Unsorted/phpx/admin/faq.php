<?php
#$Id: faq.php,v 1.1 2003/09/20 17:44:33 ryan Exp $
require('includes/auth.inc.php');
require('includes/faq.inc.php');
new faqModule($userinfo);
?>


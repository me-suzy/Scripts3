<?php
#$Id: registerEmail.tpl.php,v 1.1 2003/08/08 19:26:23 ryan Exp $

$message = "Thank you for Registering at $siteName.\r\n\r\n";
$message .= "Username: $username\r\nPassword: $password\r\n\r\n";
$message .= "To login to your account go to: $siteURL/users.php?action=login\r\n\r\n";

$messageFooter = "Do not Reply to this message.";

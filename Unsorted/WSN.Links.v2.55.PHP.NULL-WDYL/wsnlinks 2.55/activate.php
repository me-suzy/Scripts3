<?php
include 'start.php';

$checkmem = new member('id', $memid);
if ($activation == $checkmem->password)
{ // ok, activate account
// note: activation code is just the md5 encode of their password
// There is thus a slight chance that someone familiar with the script could
// cheat and validate their self without a valid email address... but they'd have
// to be quite bored to go through so much work to cheat.
 $checkmem->validated = 1;
 $checkmem->update('validated');
 if (!$template) $template = new template('redirect.tpl');
 $template->replace('{MESSAGE}', $language->register_activated);
 if ($returnto == '') $returnto = 'index.php';
 $template->replace('{DESTINATION}', $returnto);
}

require 'end.php';
?>
<?php
$saltquery = $db->select('salt', 'memberstable', "$newname='$username'", '', '');
$salt = $db->rowitem($saltquery);
$password = md5(md5($userpassword) . $salt);
?>
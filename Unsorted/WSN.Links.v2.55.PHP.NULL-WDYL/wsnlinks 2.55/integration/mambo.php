<?php
// Mambo member integration hack for WSN

$memberstable = 'mos_users';
$newusergroup = 'gid';

$otherencoder = 'no'; // use md5 by default. if other, write it up in encoder.php

$admingroup = '1';
// Note: group listed above should be the administrative usergroup. If not, change the line above.
// Be sure you do not use the wrong group id, or members may be able to use your admin panel.

$idcookiename = ''; 
$passwordcookiename = '';
// fill in the name of the cookie that holds the member id and password, if possible.

?>
<?php
// vBulletin member integration hack for WSN
// Tested on vBulletin 3.00 beta 4-6

$memberstable = 'user';
$newid = 'userid';
$newname = 'username';
$newusergroup = 'usergroupid';
$newtime = 'joindate';
$newip = 'ipaddress';
$newsignature = 'usertextfield.signature';
$otherencoder = 'yes'; // use md5 by default. if other, write it up in encode.php

$admingroup = '6';
// Note: group listed above should be the administrative usergroup. If not, change the line above.
// Be sure you do not use the wrong group id, or members may be able to use your admin panel.

$idcookiename = 'bbuserid'; 
$passwordcookiename = 'bbpassword';
// fill in the name of the cookie that holds the member id and password, if possible.
?>
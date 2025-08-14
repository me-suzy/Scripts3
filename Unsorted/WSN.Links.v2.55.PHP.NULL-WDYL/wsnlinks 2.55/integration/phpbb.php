<?php
// phpBB member integration hack for WSN 
// Tested with phpBB 2.06

$memberstable = 'phpbb_users';
$newid = 'user_id';
$newname = 'username';
$newpassword = 'user_password';
$newusergroup = 'user_level';
$newtime = 'user_regdate';
$newemail = 'user_email';

$otherencoder = 'no'; // use md5 by default. if other, write it up in encode.php

$admingroup = '1';
// Note: group listed above should be the administrative usergroup. If not, change the line above.
// Be sure you do not use the wrong group id, or members may be able to use your admin panel.

$idcookiename = ''; 
$passwordcookiename = '';
// fill in the name of the cookie that holds the member id and password, if possible.

?>
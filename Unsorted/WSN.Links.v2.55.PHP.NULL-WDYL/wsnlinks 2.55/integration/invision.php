<?php
// Invision Board member integration hack for WSN 

$memberstable = 'ibf_members';
$newusergroup = 'mgroup';
$newtime = 'joined';
$newip = 'ip_address';

$otherencoder = 'no'; // use md5 by default. if other, write it up in encoder.php

$admingroup = '4';
// Note: group listed above should be the administrative usergroup. If not, change the line above.
// Be sure you do not use the wrong group id, or members may be able to use your admin panel.
// $group4 = '6';

$idcookiename = 'member_id'; 
$passwordcookiename = 'pass_hash';
// fill in the name of the cookie that holds the member id and password, if possible.

?>
<?php
// WSN member integration hack for WSN Manual

// Please note: you now only need to include the fields that are DIFFERENT from the normal WSN fields
// To indicate a changed field, use $newx = 'y'; where x is the wsn field name and y is the new one
$memberstable = 'wsnmanual_members';

$otherencoder = 'no'; // use md5 by default. if other, write it up in encoder.php

$admingroup = '3';
// Note: group listed above should be the administrative usergroup. If not, change the line above.
// Be sure you do not use the wrong group id, or members may be able to use your admin panel.

$idcookiename = 'wsnuser'; 
$passwordcookiename = 'wsnpass';
// fill in the name of the cookie that holds the member id and password, if possible.

?>
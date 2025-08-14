<?php
include 'start.php';

$thislink = new onelink('dummy', $id);

// Check to see if this is unique or non-unique hit.
if (!(strstr($HTTP_COOKIE_VARS['wsnhitsin'], " $id ")) )
{
 $thislink->hitsin += 1;
 $thislink->update('hitsin');
 $aliases = $db->select($settings->linkfields, 'linkstable', 'alias='. $thislink->id, '', '');
 $n = $db->numrows($aliases);
 for ($x=0; $x<$n; $x++)
 {
  $alias = new onelink('row', $db->row($aliases));
  $alias->hitsin += 1;
  $alias->update('hitsin');
 }
 $settings->totalhitsin += 1; 
 $settings->update('totalhitsin');
 $owner = new member('id', $thislink->ownerid);
 $owner->totalhitsin += 1;
 $owner->update('totalhitsin');
 // set cookie to prevent counting again in future
 $idstring = ' '. $HTTP_COOKIE_VARS['wsnhitsin'] .' '. $id .' ';
 $clicktimer = $settings->clicktimer;
 setcookie("wsnhitsin", "$idstring", time()+$clicktimer);
}
$url = $settings->myurl;
if ($url == '') $url = $settings->dirurl;
if ($url == '') $url = 'index.php';
header("Location: $url"); 
die('<meta http-equiv="refresh" content="0;'. $url .'">');
// don't include end, since we want to send the user onward to the page the admin has specified as their homepage

?>

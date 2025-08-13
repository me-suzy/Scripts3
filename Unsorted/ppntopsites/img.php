<?php
/*
////////////////////////////////////////////////////
//                  PPN Topsites v1.0             //
//          http://software.pp-network.com        //
//                                                //
//                                                //
// Copyright ------------------------------------ //
//   PPN Topsites is copyright (C) 2001           //
//   the PPN Topsites Development Team and Scott  //
//   MacVicar. All rights reserved. You may not   //
//   redestribute this file without written       //
//   permission from the copyright holders. You   //
//                                                //
// Contact Information -------------------------- //
//   For support please only use the forums on    //
//   the web site. Support emails to any of the   //
//   development team will be ignored.            //
//                                                //
// Thanks --------------------------------------- //
//   Big thanks to Derek Mortimer for helping     //
//   me and reading all the code in the script.   //
//   Thanks to PGZ and Vforest for beta testing.  //
//                                                //
//                                                //
//                        software.pp-network.com //
////////////////////////////////////////////////////
*/
include("config.php");

function redirect($cmd){
    header("Location: $cmd");
}

if(!(file_exists("db/time.db")))
{
$time = fopen("db/time.db", "w");
$ctime = time();
fputs($time, $ctime);
fclose($time);
}
$lastime = file("db/time.db");
$ctime = time();
$diff = $ctime - $lastime[0];
if ($diff > 86400)
{
$mod = file("db/users.db");
$users = fopen("db/users.db", "w");
for ($index = 0; $index < count($mod); $index++)
{
$mze = explode("|", $mod[$index]);

$mze[10] = $mze[9];
$mze[9] = $mze[8];
$mze[8] = $mze[7];
$mze[7] = $mze[6];
$mze[6] = 0;

$done = implode($mze, "|");
Print("$done");
fputs($users, $done);
}
$time = fopen("db/time.db", "w");
fputs($time, $ctime);
rmdir("/logs");
mkdir("/logs", 0777);
}

if(!isset($id))
{
$image = "default" . "$bannerext";
$finish = $bannerurl . "/" . $image;
redirect("$finish");
}
else {
$banner = file("db/banner.db");
$show = $numban + 1;
for ($x = 0; $x < count($banner); $x++)
{
$pos = explode("|", $banner[$x]);
if (($pos[0] == $id) && ($pos[1] < $show))
{
$image = $pos[1] . "$bannerext";
$finish = $bannerurl . "/" . $image;
redirect("$finish");
}
elseif (($pos[0] == $id) && ($pos[1] > $show))
{
$image = "default" . "$bannerext";
$finish = $bannerurl . "/" . $image;
redirect("$finish");
}
}
$image = "default" . "$bannerext";
$finish = $bannerurl . "/" . $image;
//redirect("$finish");
}
?>
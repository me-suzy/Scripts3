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

if($mode = 2)
 {
$logfile = "./logs/" . $id . ".log";

if (!(file_exists($logfile)))
{
$logwrite = fopen($logfile,"w");
fputs($logwrite, "127.0.0.1\n");
}

$iplog = file($logfile);
$ips = implode($iplog, "|");
$local_address = getenv ("REMOTE_ADDR");

if (!(isset($id)))
{
redirect("index.php");
exit();
}

elseif(eregi($local_address,$ips))
{
redirect("index.php");
exit();
}
elseif(!(ereg($local_address, $ips)))
{
$logwrite = fopen($logfile,"a");
  $text = $local_address;
  $text .= "\n";
  fputs($logwrite, $text);
  $userz = file("db/users.db");
  $fp2 =  fopen("db/users.db","w");
  for ($index = 0; $index < count($userz); $index++)
  {
$foo = explode("|", $userz[$index]);
    if($foo[11] == $id)
    {
     $foo[6]++;
      $sorted =  implode("|", $foo);
      fputs($fp2, $sorted);
    } else {
    $normal = $userz[$index];
      fputs($fp2, $normal);
    }
}
redirect("index.php");
}
}
elseif($mode = 1)
 {
if (!(isset($id)))
{
redirect("index.php");
exit();
}
$userz = file("db/users.db");
  $fp2 =  fopen("db/users.db","w");
  for ($index = 0; $index < count($userz); $index++)
  {
$foo = explode("|", $userz[$index]);
    if($foo[11] == $id)
    {
     $foo[6]++;
      $sorted =  implode("|", $foo);
      fputs($fp2, $sorted);
    } else {
    $normal = $userz[$index];
      fputs($fp2, $normal);
    }
  }
redirect("index.php");
 }
else
{
redirect("index.php");
}
?>
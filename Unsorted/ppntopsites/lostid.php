<?
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
include("style.php");
include("language.php");

$start = <<< START
<center>
 <br>
 <font color="$fontcolour">
START;

$foo = "http://";
if(!isset($url))
{

Print("$top");
Print($start . "\n");
Print("<center><B>$ppn_notice[lostid_msg]</B>");
Print("<BR><FORM METHOD=POST ACTION=lostid.php>");
Print("<INPUT TYPE=\"text\" NAME=\"url\" size=25><br><input type=\"submit\" value=\"$ppn_notice[lostid_but]\"></form><br><br>");
Print("<center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();
} else {
$look = file("db/users.db");
$total = count($look);
for ($index = 0; $index < $total; $index++) {
if(ereg($url, $look[$index])) {
$ids = explode("|", $look[$index]);

Print("$top");
Print($start . "\n");
Print("<font face=\"$font\" color=\"$fontcolour\" size=\"2\">$ppn_notice[lostid_confirm] $ids[11]</font>");
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();
}
}
Print("$top");
Print($start . "\n");
Print("<font face=\"$font\" color=\"$fontcolour\" size=\"2\"><b>$ppn_notice[lostid_error]</b></font>");
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();
}
?>
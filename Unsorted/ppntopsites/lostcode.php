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
include("style.php");
include("language.php");

$start = <<< START
<center>
 <br>
 <font color="$fontcolour">
START;
if (!isset($id))
{

Print("$top");
Print($start . "\n");
Print("<center><B>$ppn_notice[lostcode_msg]</B>");
Print("<BR><FORM METHOD=\"POST\" ACTION=\"lostcode.php\">");
Print("<INPUT TYPE=text NAME=id size=5><br><input type=\"submit\" value=\"$ppn_notice[lostcode_but]\"></form><br><br>");
Print("<center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();
} else {

Print("$top");
Print($start . "\n");
Print("<font face=\"$font\" color=\"$fontcolour\" size=\"2\">$ppn_notice[lostcode_confirm]<br></font>");
Print("<TEXTAREA Cols=25 ROWS=8><a href=\"$scripturl/main.php?id=$id\" target=\"_blank\"><img src=\"$scripturl/img.php?id=$id\" border=\"0\"></TEXTAREA>");
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();
}
?>
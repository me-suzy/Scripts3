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
if(!(isset($form)))
{
$data = "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF?form=send\"><table cellspacing=0 border=1 cellpadding=5 bgcolor=$tablebg>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[report_name]</TD><TD>&nbsp;&nbsp;&nbsp;<INPUT TYPE=\"text\" NAME=\"name\"></TD></TR>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[report_email]</TD><TD>&nbsp;&nbsp;&nbsp;<INPUT TYPE=\"text\" NAME=\"email\"></TD></TR>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[report_site]</TD><TD>&nbsp;&nbsp;&nbsp;<INPUT TYPE=\"text\" NAME=\"site\"></TD></TR>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[report_reason]</TD><TD>&nbsp;&nbsp;&nbsp;<TEXTAREA ROWS=5 NAME=\"reason\" COLS=28></TEXTAREA></TD></TR>";
$data .= "<TR><TD colspan=2 align=center><input type=submit value=\"$ppn_notice[report_but]\"></TD></TR></TABLE><BR></form>";
Print("$top");
Print($data);
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();
}
elseif((empty($name)) || (empty($email)) || (empty($site)) || (empty($reason)))
 {
$data = "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF?form=send\"><table cellspacing=0 border=1 cellpadding=5 bgcolor=$tablebg>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[report_name]</TD><TD>&nbsp;&nbsp;&nbsp;<INPUT TYPE=\"text\" NAME=\"name\" value=\"$name\"></TD></TR>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[report_email]</TD><TD>&nbsp;&nbsp;&nbsp;<INPUT TYPE=\"text\" NAME=\"email\" value=\"$email\"></TD></TR>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[report_site]</TD><TD>&nbsp;&nbsp;&nbsp;<INPUT TYPE=\"text\" NAME=\"site\" value=\"$site\"></TD></TR>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[report_reason]</TD><TD>&nbsp;&nbsp;&nbsp;<TEXTAREA ROWS=5 NAME=\"reason\" COLS=28 value=\"$reason\"></TEXTAREA></TD></TR>";
$data .= "<TR><TD colspan=2 align=center><input type=submit value=\"$ppn_notice[report_but]\"></TD></TR></TABLE><BR></form>";
Print("$top");
Print("<FONT COLOR=$fontcolour SIZE=\"2\" FACE=\"$font\">$ppn_notice[report_error]</font><br>");
Print($data);
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();
    }
else
{
$fp =  fopen("db/cheat.db","a");
flock($fp,2);
$info = "$name|$email|$site|$reason|$REMOTE_ADDR";
fputs($fp, $info . "\n");
flock($fp,1);
Print("$top");
Print("<center><font face=\"$font\" color=\"$fontcolour\" size=\"2\">$ppn_notice[report_confirm]</font></center>");
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
}
?>
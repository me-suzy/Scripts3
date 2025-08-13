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
if ((!isset($id)) || (!isset($edited)))
{
Print("$top");
Print($start . "\n");
$data = "<center><font face=\"$font\" color=\"$fontcolour\" size=2><B>$ppn_notice[edit_msg] $tname</B></FONT>";
$data .= "<BR><FORM METHOD=\"POST\" ACTION=\"change.php\"><table cellspacing=0 border=1 cellpadding=5 bgcolor=$tablebg>";
$data .= "<TR><TD><FONT SIZE=2 FACE=\"$font\" color=\"$fontcolour\">ID</TD><TD>&nbsp;&nbsp;&nbsp;<INPUT TYPE=\"text\" NAME=\"id\" ></TD></TR>";
$data .= "<TR><TD><FONT SIZE=2 FACE=\"$font\" color=\"$fontcolour\">$ppn_notice[join_topsites]</TD><TD>&nbsp;&nbsp;&nbsp;<INPUT TYPE=\"text\" NAME=\"name\" ></TD></TR>";
$data .= "<TR><TD><FONT SIZE=2 FACE=\"$font\" color=\"$fontcolour\">$ppn_notice[join_url]</TD><TD>&nbsp;&nbsp;&nbsp;<INPUT TYPE=\"text\" NAME=\"url\" ></TD></TR>";
$data .= "<TR><TD><FONT SIZE=2 FACE=\"$font\" color=\"$fontcolour\">$ppn_notice[join_email]</TD><TD>&nbsp;&nbsp;&nbsp;<INPUT TYPE=\"text\" NAME=\"email\" ></TD></TR>";
$data .= "<TR><TD><FONT SIZE=2 FACE=\"$font\" color=\"$fontcolour\">$ppn_notice[join_button]</TD><TD>&nbsp;&nbsp;&nbsp;<INPUT TYPE=\"text\" NAME=\"banner\"></TD></TR>";
$data .= "<TR><TD><FONT SIZE=2 FACE=\"$font\" color=\"$fontcolour\">$ppn_notice[join_pass]</TD><TD>&nbsp;&nbsp;&nbsp;<INPUT TYPE=\"text\" NAME=\"pass\" ></TD></TR>";
$data .= "<TR><TD colspan=2 align=center><INPUT TYPE=\"hidden\" NAME=\"edited\" ><input type=submit value=\"$ppn_notice[edit_but]\"></TD></TR></TABLE><BR></form>";

Print("$data");
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();
}
elseif ((isset($id)) && (isset($edited)))
{
$password = md5($pass);
$url = clean($url);
$banner = clean($banner);
$name = clean($name);
$db = file("db/users.db");
$fp = fopen("db/users.db","w");
for ($x = 0; $x < count($db); $x++)
{
$stats = explode("|", $db[$x]);
if (($id == $stats[11]) && ($password === $stats[5]))
{
$change = array(1=>"$stats[0]", "$url", "$banner", "$name", "$stats[4]", "$password", "$stats[6]" , "$stats[7]", "$stats[8]", "$stats[9]", "$stats[10]", "$stats[11]", "$stats[12]");
$linez = implode("|", $change);
fputs($fp, "$linez");
$changed = 1;
}
else {
fputs($fp, "$db[$x]");
  }
}
if($changed)
 {
Print("$top");
Print($start . "\n");
Print("<font face=\"$font\" color=\"$fontcolour\" size=\"2\">$ppn_notice[edit_confirm]</font>");
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();
 }
else
 {
Print("$top");
Print($start . "\n");
Print("<font face=\"$font\" color=\"$fontcolour\" size=\"2\">$ppn_notice[edit_error]</font>");
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
 }
}
else {
exit();
}
function clean($a)
 {
 $a = strip_tags($a);
 $a = str_replace("|", " ", $a);
 $a = stripslashes($a);
 return("$a");
 }
?>
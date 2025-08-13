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
if (!isset($sent))
{

Print("$top");

$data = "<center><font face=\"$font\" color=\"$fontcolour\" size=2><B>$ppn_notice[join_msg] $tname</B><br>[$ppn_notice[html_warning]]</FONT><BR><FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\"><table cellspacing=0 border=1 cellpadding=5 bgcolor=$tablebg>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[join_topsites]</TD><TD>   <INPUT TYPE=\"text\" NAME=\"name\" ></TD></TR>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[join_url]</TD><TD>   <INPUT TYPE=\"text\" NAME=\"url\" ></TD></TR>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[join_email]</TD><TD>   <INPUT TYPE=\"text\" NAME=\"email\" ></TD></TR>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[join_button] [$imagewidth by $imageheight]</TD><TD>   <INPUT TYPE=\"text\" NAME=\"banner\"></TD></TR>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[join_pass]</TD><TD>   <INPUT TYPE=\"text\" NAME=\"pass\" ></TD></TR>";
$data .= "<TR><TD><FONT COLOR=$fontcolour SIZE=2 FACE=\"$font\">$ppn_notice[join_des]</TD><TD>   <INPUT TYPE=\"text\" NAME=\"des\" ></TD></TR>";
$data .= "<INPUT TYPE=\"hidden\" NAME=\"sent\">";
$data .= "<TR><TD colspan=2 align=center><input type=submit value=\"$ppn_notice[join_but]\"></TD></TR></TABLE><BR></form>";
Print($data);
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();
}
$e = "@";
$h = "http://";
if (!ereg($e,$email))
 {
 $email_error = 1;
 }
if (!ereg($h,$url))
 {
 $url_error = 1;
 }
if (!ereg($h,$banner))
 {
 $banner_error = 1;
 }
if ($email_error)
{
Print("<font face=\"$font\" color=\"$fontcolour\" size=\"2\">$ppn_notice[error_email]</font><br>");
}
if ($url_error)
{
Print("<font face=\"$font\" color=\"$fontcolour\" size=\"2\">$ppn_notice[error_url]</font><br>");
}
if ($banner_error)
{
Print("<font face=\"$font\" color=\"$fontcolour\" size=\"2\">$ppn_notice[error_button]</font><br>");
}
if(($email_error) || ($url_error) || ($banner_url))
{
Print("$top");
Print($start . "\n");
Print("<font face=\"$font\" color=\"$fontcolour\" size=\"2\">$ppn_notice[error_des]</font><br>");
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();
}
else
{
$banned = file("db/banned.db");
$ban = implode($banned, "<~>");
if(eregi("$url",$ban))
 {
Print("$top");
Print("<font face=\"$font\" color=\"$fontcolour\" size=\"2\"><b>You are banned from these topsites</b></font>");
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();
 }
$nid = file("db/id.db");
$url = clean($url);
$banner = clean($banner);
$name = clean($name);
$des = clean($des);
$pass = clean($pass);
$email = clean($email);
$nid[0]++;
$fp = fopen("db/id.db","w");
fputs($fp,$nid[0]);
fclose($fp);
$password = md5($pass);
$signup = array(1=>"0", $url, $banner, $name, $des,$password, "0", "0", "0", "0", "0", $nid[0], $email);
$s_line = implode("|", $signup);
$user = fopen("db/users.db","a");
flock($user,2);
fputs($user, $s_line . "\n");
flock($user,1);

Print("$top");
Print("<font face=\"$font\" color=\"$fontcolour\" size=\"2\">$ppn_notice[join_confirm]</font><br><br>");
Print("<TEXTAREA Cols=25 ROWS=8><a href=\"$scripturl/main.php?id=$nid[0]\" target=\"_blank\"><img src=\"$scripturl/img.php?id=$nid[0]\" border=\"0\"></textarea>");
Print("<br><br><center><font face=\"$font\" color=\"$fontcolour\" size=\"2\"><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
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
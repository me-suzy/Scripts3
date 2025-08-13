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
include("users.php");
$version = "1.0";

if(!isset($action)) {
$html = "<FORM ACTION=cpanel.php?action=Check NAME=THEFORM METHOD=POST><center>UserName: <INPUT TYPE=TEXT NAME=username SIZE=25 MAXLENGTH=25>&nbsp;&nbsp;&nbsp;&nbsp; Password <INPUT TYPE=PASSWORD NAME=pass SIZE=13 MAXLENGTH=13></FONT></B><br><INPUT TYPE=SUBMIT NAME=Submit VALUE=Submit></CENTER></form>";
layout("Index", "Index", "Page", $html);
exit();
} elseif(($action == "general") && (($username == $adminuser) && ($password == $adminpass))) {
include("config.php");
$html = <<< HTML
<form action="cpanel.php?action=modifygeneral" method="POST">
  <table border="0" cellspacing="1" width="100%" cellpadding="3">
    <tr>
      <td>
        <p align="center">Settings for PPN Topsites</td>
    </tr>
    <tr>
      <td>
        <div align="center">
          <center>
          <table border="0" cellpadding="3" cellspacing="1" width="600">
            <tr>
              <td width="25%">Site URL</td>
              <td width="75%"><input type="text" name="site_url" size="40" value="$siteurl"></td>
            </tr>
            <tr>
              <td width="25%">Script URL</td>
              <td width="75%"><input type="text" name="script_url" size="40" value="$scripturl"></td>
            </tr>
            <tr>
              <td width="25%">Banner URL</td>
              <td width="75%"><input type="text" name="banner_url" size="40" value="$bannerurl"></td>
            </tr>
            <tr>
              <td width="25%">Topsite Name</td>
              <td width="75%"><input type="text" name="topsite_name" size="40" value="$tname"></td>
            </tr>
            <tr>
              <td width="25%">Topsite Description</td>
              <td width="75%"><input type="text" name="topsite_descript" size="40" value="$title"></td>
            </tr>
            <tr>
              <td width="25%">Banner Extension</td>
              <td width="75%"><input type="text" name="banner_ext" size="40" value="$bannerext"></td>
            </tr>
            <tr>
              <td width="25%">Customised Banners</td>
              <td width="75%"><input type="text" name="custom_ban" size="40" value="$numban"></td>
            </tr>
            <tr>
              <td width="25%">Ranks Per Page</td>
              <td width="75%"><input type="text" name="ranks" size="40" value="$trank"></td>
            </tr>
            <tr>
              <td width="25%">Image Width</td>
              <td width="75%"><input type="text" name="imagewidth" size="40" value="$imagewidth"></td>
            </tr>
            <tr>
              <td width="25%">Image Height</td>
              <td width="75%"><input type="text" name="imageheight" size="40" value="$imageheight"></td>
            </tr>
            <tr>
              <td width="25%">Image Height</td>
              <td width="75%"><input type="text" name="adminemail" size="40" value="$adminemail"></td>
            </tr>
            <tr>
              <td width="25%">Mode</td>
              <td width="75%"><select name="mode"><option value="1">Click Throughs<option value="2">Unique Click Throughs</select></td>
            </tr>
            <tr>
              <td width="25%">Header</td>
              <td width="75%"><textarea rows="2" name="head_er" cols="37">$header</textarea></td>
            </tr>
            <tr>
              <td width="25%">Footer</td>
              <td width="75%"><textarea rows="2" name="foot_er" cols="37">$footer</textarea></td>
            </tr>
      </td>
          </table>
            <tr>
<td width="100%"><center><input type="SUBMIT" name="modify" value="Modify Settings"><input TYPE="RESET" VALUE="Reset Form"></center></form></td>
</tr>

          </center>
        </div>
      </td>
    </tr>
  </table>
HTML;

layout(General, General, Settings, $html);
exit();
} elseif(($action == "modifygeneral") && (($username == $adminuser) && ($password == $adminpass))) {
$html = ("The settings have now been updated.");
layout(General, General, Settings, $html);

$fp = fopen("config.php","w");
$text = "\$siteurl = \"$site_url\";\n";
$text2 = "\$scripturl = \"$script_url\";\n";
$text3 = "\$bannerurl = \"$banner_url\";\n";
$text4 = "\$tname = \"$topsite_name\";\n";
$text5 = "\$title = \"$topsite_descript\";\n";
$text6 = "\$bannerext = \"$banner_ext\";\n";
$text7 = "\$numban = \"$custom_ban\";\n";
$text8 = "\$trank = \"$ranks\";\n";
$text9 = "\$mode = \"$mode\";\n";
$text10 = "\$imagewidth = \"$imagewidth\";\n";
$text11 = "\$imageheight = \"$imageheight\";\n";
$text12 = "\$adminemail = \"$adminemail\";\n";
//footer
$head_er = stripslashes($head_er);
$foot_er = stripslashes($foot_er);
$text13 = "\$header = " . "<<< HEADER\n" . $head_er . "\nHEADER;\n";
$text14 = "\$footer = " . "<<< FOOTER\n" . $foot_er . "\nFOOTER;\n";
flock($fp, 2);
fputs($fp, "<?php\n");
fputs($fp, $text);
fputs($fp, $text2);
fputs($fp, $text3);
fputs($fp, $text4);
fputs($fp, $text5);
fputs($fp, $text6);
fputs($fp, $text7);
fputs($fp, $text8);
fputs($fp, $text9);
fputs($fp, $text10);
fputs($fp, $text11);
fputs($fp, $text12);
fputs($fp, $text13);
fputs($fp, $text14);
fputs($fp, "?>\n");
fclose($fp);
exit();
} elseif(($action == "style") && (($username == $adminuser) && ($password == $adminpass))) {
include("style.php");
$html = <<< HTML
<form action="cpanel.php?action=modifystyle" method="POST">
  <table border="0" cellspacing="1" width="100%" cellpadding="3">
    <tr>
      <td>
        <p align="center">Style Settings for PPN Topsites</td>
    </tr>
    <tr>
      <td>
        <div align="center">
          <center>
          <table border="0" cellpadding="3" cellspacing="1" width="600">
            <tr>
              <td width="25%">Background Colour</td>
              <td width="75%"><input type="text" name="bgcolour" size="40" value="$bgcolour"></td>
            </tr>
            <tr>
              <td width="25%">Table Header Colour</td>
              <td width="75%"><input type="text" name="tablehbg" size="40" value="$tablehbg"></td>
            </tr>
            <tr>
              <td width="25%">Header Font</td>
              <td width="75%"><input type="text" name="headfont" size="40" value="$headfont"></td>
            </tr>
            <tr>
              <td width="25%">Header Font Colour</td>
              <td width="75%"><input type="text" name="fonthcolour" size="40" value="$fonthcolour"></td>
            </tr>
            <tr>
              <td width="25%">Font</td>
              <td width="75%"><input type="text" name="font" size="40" value="$font"></td>
            </tr>
            <tr>
              <td width="25%">Font Colour</td>
              <td width="75%"><input type="text" name="fontcolour" size="40" value="$fontcolour"></td>
            </tr>
            <tr>
              <td width="25%">Link Colour</td>
              <td width="75%"><input type="text" name="lcolour" size="40" value="$lcolour"></td>
            </tr>
            <tr>
              <td width="25%">Visited Link Colour</td>
              <td width="75%"><input type="text" name="vcolour" size="40" value="$vcolour"></td>
            </tr>
            <tr>
              <td width="25%">Active Link Colour</td>
              <td width="75%"><input type="text" name="acolour" size="40" value="$acolour"></td>
            </tr>
            <tr>
              <td width="25%">Link Hover Colour</td>
              <td width="75%"><input type="text" name="hcolour" size="40" value="$hcolour"></td>
            </tr>
            <tr>
              <td width="25%">Table Width</td>
              <td width="75%"><input type="text" name="tablewidth" size="40" value="$tablewidth"></td>
            </tr>
            <tr>
              <td width="25%">Table Colour</td>
              <td width="75%"><input type="text" name="tablebg" size="40" value="$tablebg"></td>
            </tr>
      </td>
          </table>
            <tr>
<td width="100%"><center><input type="SUBMIT" name="modify" value="Modify Settings"><input TYPE="RESET" VALUE="Reset Form"></center></form></td>
</tr>

          </center>
        </div>
      </td>
    </tr>
  </table>
HTML;
layout("Style", "Style", "Settings", $html);
exit();
} elseif(($action == "modifystyle") && (($username == $adminuser) && ($password === $adminpass))) {
$html = ("The style settings have now been updated.");
layout(Style, Style, Settings, $html);

$fp = fopen("style.php","w");
$text = "\$bgcolour = " . chr(34) . $bgcolour . chr(34) . ";\n";
$text2 = "\$headfont = " . chr(34) . $headfont . chr(34) . ";\n";
$text3 = "\$fonthcolour = " . chr(34) . $fonthcolour . chr(34) . ";\n";
$text4 = "\$tablehbg = " . chr(34) . $tablehbg . chr(34) . ";\n";
$text5 = "\$font = " . chr(34) . $font . chr(34) . ";\n";;
$text6 = "\$fontcolour = " . chr(34) . $fontcolour . chr(34) . ";\n";
$text7 = "\$lcolour = " . chr(34) . $lcolour . chr(34) . ";\n";
$text8 = "\$vcolour = " . chr(34) . $vcolour . chr(34) . ";\n";
$text9 = "\$acolour = " . chr(34) . $acolour . chr(34) . ";\n";
$text10 = "\$hcolour = " . chr(34) . $hcolour . chr(34) . ";\n";
$text11 = "\$tablewidth = " . chr(34) . $tablewidth . chr(34) . ";\n";
$text12 = "\$tablebg = " . chr(34) . $tablebg . chr(34) . ";\n";
flock($fp, 2);
fputs($fp, "<?php\n");
fputs($fp, $text);
fputs($fp, $text2);
fputs($fp, $text3);
fputs($fp, $text4);
fputs($fp, $text5);
fputs($fp, $text6);
fputs($fp, $text7);
fputs($fp, $text8);
fputs($fp, $text9);
fputs($fp, $text10);
fputs($fp, $text11);
fputs($fp, $text12);
fputs($fp, "?>\n");
fclose($fp);
exit();
} elseif(($action == "currentusers") && (($username == $adminuser) && ($password == $adminpass))) {

$datam = file("db/users.db");
$html = "<table width=100% border=0 cellspacing=2 cellpadding=2><tr class=table_top><td>ID</td><td>Name</td><td>Email</td><td>Hits</td></tr>";
for ($index = 0; $index < count($datam); $index++)
{
$html .= "<tr class=table><td>";
  $line = explode("|", $datam[$index]);
  $line[0] = round($line[0]);
     $html .= $line[11] . "</td><td>";
     $html .= $line[3] . "</td><td>";
     $html .= $line[12] . "</td><td>";
     $html .= $line[0] . "</td></tr>";
}
layout("CurrentUsers", "Current", "Members", $html);
exit();
} elseif(($action == "editusers") && (($username == $adminuser) && ($password == $adminpass))) {

$datam = file("db/users.db");
$html = "<table width=100% border=0 cellspacing=2 cellpadding=2><tr class=table_top><td>ID</td><td>Name</td><td>Email</td><td>Delete</td></tr>";
for ($index = 0; $index < count($datam); $index++)
{
$html .= "<tr class=table><td>";
  $line = explode("|", $datam[$index]);
     $html .= $line[11] . "</td><td>";
     $html .= $line[3] . "</td><td>";
     $html .= $line[12] . "</td><td>";
     $html .= "<A href=cpanel.php?action=remove&id=$line[11]>Delete</a></td></tr>";
}
layout("EditsUsers", "Edit", "Members", $html);
exit();
} elseif(($action == "banlists") && (($username == $adminuser) && ($password == $adminpass))) {
$banned = file("db/banned.db");
$html = "<FORM ACTION=cpanel.php?action=updatebanlist NAME=THEFORM METHOD=POST><textarea rows=8 cols=45 wrap=virtual name=banlist width=100%>";
for($x = 0; $x < count($banned); $x++)
 {
  $html .= $banned[$x];
 }
$html .= "</textarea><br><center><INPUT TYPE=SUBMIT NAME=Submit VALUE=Submit></center></form>";
layout("Banned", "Banned", "Websites", $html);
exit();
} elseif(($action == "updatebanlist") && (($username == $adminuser) && ($password == $adminpass))) {
$fp = fopen("db/banned.db", "w");

fputs($fp, "$banlist");
$html = "Ban list updated successfully";

layout("Banned", "Banned", "Websites", $html);
exit();
} elseif(($action == "cheat") && (($username == $adminuser) && ($password == $adminpass))) {
$file = file("db/cheat.db");
$html = "Reports Of Cheating Sites<br><hr align=left width=150>";
for($x = 0; $x < count($file); $x++)
 {
$info = explode("|", $file[$x]);
$total = $x + 1;
$html .= "Report $total<br>";
$html .= "Name : $info[0]<br>";
$html .= "Email : $info[1]<br>";
$html .= "Site : $info[2]<br>";
$html .= "Reason : $info[3]<br>";
$html .= "IP : $info[4]<br>";
$html .= "<a href=\"cpanel.php?action=deletereport&id=$x\">Delete</a><br><br>";
 }

layout("Cheat", "Cheating", "Websites", $html);
exit();
} elseif(($action == "remove") && (isset($id)) && (($username == $adminuser) && ($password == $adminpass))) {

$datam = file("db/users.db");
$fp = fopen("db/users.db","w");
for ($index = 0; $index < count($datam); $index++)
{
  $line = explode("|", $datam[$index]);
if($line[11] != $id) {
fputs($fp, "$datam[$index]");
  }
}
$html = "User: $id was sucessfully deleted.";
layout("Remove", "Delete", "User", $html);
exit();
} elseif(($action == "changeuser") && (($username == $adminuser) && ($password == $adminpass))) {

$html = ("<FORM ACTION=cpanel.php?action=changeusername NAME=THEFORM METHOD=POST><center>New UserName: <INPUT TYPE=TEXT NAME=newusername SIZE=25 MAXLENGTH=25><br><INPUT TYPE=SUBMIT NAME=Submit VALUE=Submit></CENTER>");
layout("Username", "Change", "Username", $html);
exit();
} elseif(($action == "changepass") && (($username == $adminuser) && ($password == $adminpass))) {

$html = ("<FORM ACTION=cpanel.php?action=changepassword NAME=THEFORM METHOD=POST><center>New Password: <INPUT TYPE=TEXT NAME=newpassword SIZE=25 MAXLENGTH=25><br><INPUT TYPE=SUBMIT NAME=Submit VALUE=Submit></CENTER>");
layout("Password", "Change", "Password", $html);
exit();
} elseif($action == "logout") {
setcookie("username", "a",time()+1);
setcookie("password", "a",time()+1);

$html = ("Thanks $username you are now logged out.");
layout("Logout", "Logged", "Out", $html);
exit();
} elseif(($action == "Check") && ($username == $adminuser) && ($pass == $adminpass)) {
$cookie_life = 7*24*3600;
setcookie("username", "$username",time()+$cookie_life);
setcookie("password", "$pass",time()+$cookie_life);

$html = ("You are now logged in $username");
layout("Login", "Logged", "In", $html);
exit();
} elseif(($action == "changeusername") && (($username == $adminuser) && ($password == $adminpass)) && ($newusername != "")) {
$cookie_life = 7*24*3600;
setcookie("username", "$newusername",time()+$cookie_life);
setcookie("password", "$password",time()+$cookie_life);

$html = ("The admin username is now $newusername");
layout("UserChange", "Change", "Username", $html);
$fp = fopen("users.php","w");
$text = "\$adminuser = " . $newusername . ";\n";
$text2 = "\$adminpass = " . $password . ";\n";
flock($fp, 2);
fputs($fp, "<?php\n");
fputs($fp, $text);
fputs($fp, $text2);
fputs($fp, "?>\n");
flock($fp, 1);
fclose($fp);
exit();
} elseif(($action == "changepassword") && (($username == $adminuser) && ($password == $adminpass)) && ($newpassword != "")) {

$html = ("The admin password is now $newpassword");
layout("PassChange", "Change", "Password", $html);
$fp = fopen("users.php","w");
$text = "\$adminuser = " . $username . ";\n";
$text2 = "\$adminpass = " . $newpassword . ";\n";
flock($fp, 2);
fputs($fp, "<?php\n");
fputs($fp, $text);
fputs($fp, $text2);
fputs($fp, "?>\n");
fclose($fp);
exit();
} else {

$html = "<b>You are not logged In</b><br><FORM ACTION=cpanel.php?action=Check NAME=THEFORM METHOD=POST><center>UserName: <INPUT TYPE=TEXT NAME=username SIZE=25 MAXLENGTH=25>&nbsp;&nbsp;&nbsp;&nbsp; Password <INPUT TYPE=PASSWORD NAME=pass SIZE=13 MAXLENGTH=13></FONT></B><br><INPUT TYPE=SUBMIT NAME=Submit VALUE=Submit></CENTER></form>";
layout("Error", "No", "Access", $html);
exit();
}
function versioncheck() {

 $newversion = "<img src=\"http://www.ppn.f2s.com/images/latest.png\">";
 $version = "1.0";
 Print("<a href=\"http://software.pp-network.com\">PPN Topsites $version</a><br><b><font = \"arial\" color=\"#000097\">Latest is </font></b>$newversion");

}
function Layout($page, $title, $title2, $content) {
?>
<HTML><TITLE>PPN Topsites Control Panel: <? echo("$title $title2"); ?></TITLE>
<STYLE type=text/css>
TD {
        FONT-SIZE: 10pt; FONT-FAMILY: Arial, Helvetica, sans-serif; TEXT-DECORATION: none
}
P {
        FONT-SIZE: 10pt; FONT-FAMILY: Arial, Helvetica, sans-serif; TEXT-DECORATION: none
}
A:link {
        COLOR: #000097; TEXT-DECORATION: none
}
A:visited {
        COLOR: #000097; TEXT-DECORATION: none
}
A:hover {
        COLOR: #000097; TEXT-DECORATION: underline
}
A.b:link {
        COLOR: #e1e1e1; TEXT-DECORATION: none
}
A.b:visited {
        COLOR: #e1e1e1; TEXT-DECORATION: none
}
A.b:hover {
        COLOR: #000097; TEXT-DECORATION: none
}
A.nav:link {
        COLOR: #000097; TEXT-DECORATION: none
}
A.nav:visited {
        COLOR: #000097; TEXT-DECORATION: none
}
A.nav:hover {
        COLOR: #000097; TEXT-DECORATION: underline
}
.message {
        FONT-WEIGHT: bold; COLOR: #ff3333
}
.page_title {
        FONT-WEIGHT: bold; FONT-SIZE: 10pt; COLOR: #000097; FONT-FAMILY: Arial, Helvetica, sans-serif
}
.section_title {
        FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #000097; FONT-FAMILY: Arial, Helvetica, sans-serif; font-wieght: bold
}
.table_top {
        FONT-WEIGHT: bold; FONT-SIZE: 10pt; BACKGROUND: #ffaa4e; COLOR: #e1e1e1; FONT-FAMILY: Arial, Helvetica, sans-serif
}
.table {
        FONT-SIZE: 10pt; COLOR: #000000; FONT-FAMILY: Arial, Helvetica, sans-serif; BACKGROUND-COLOR: #e1e1e1
}
.sub_title {
        FONT-WEIGHT: bold; FONT-SIZE: 10pt; COLOR: #ffaa4e; FONT-FAMILY: Arial, Helvetica, sans-serif
}
</STYLE>
<BODY bgColor=#f0f0e0><SPAN class=table_top></SPAN>
<DIV align=left>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD width="37%">
      <P align=left><FONT face=Arial><IMG height=40 src="http://www.ppn.f2s.com/images/logo.gif"
      width=252 border=0></FONT> </P></TD>
    <TD width="63%">
      <P class=section_title align=right><? echo("$title $title2"); ?> </P></TD></TR>
  <TR class=table>
    <TD width="37%">
      <P align=left><FONT face=Arial color=#000080 size=2><IMG height=15
      src="http://www.ppn.f2s.com/images/fold.gif" width=15 border=0> </FONT></P></TD>
    <TD width="63%">
      <P class=table align=right><A
      href="#"><FONT face=Arial
      color=#000080 size=2>Help</FONT></A><FONT face=Arial
      size=2>&nbsp;&nbsp;&nbsp;&nbsp; <A target=_top
      href="cpanel.php?action=logout"><FONT color=#000080>Logout</FONT></A><FONT
      color=#000080>&nbsp;&nbsp;&nbsp;&nbsp; </FONT></FONT></P></TD></TR>
  <TR>
    <TD width="65%">&nbsp; </TD>
    <TD width="35%">
      <DIV align=left></DIV></TD></TR></TBODY></TABLE></DIV>
<DIV align=left>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD vAlign=top width="21%"><FONT face=Arial size=2></FONT>
      <DIV align=left>
      <TABLE cellSpacing=2 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD width="4%"></TD>
          <TD><IMG src="http://www.ppn.f2s.com/images/bullet.gif"></TD>
          <TD><A class=nav
            href="cpanel.php?action=general">General&nbsp;Settings</A></TD>
          <TD width="4%"></TD></TR>
          <TR>
          <TD width="4%"></TD>
          <TD><IMG src="http://www.ppn.f2s.com/images/bullet.gif"></TD>
          <TD><A class=nav
            href="cpanel.php?action=style">Style&nbsp;Settings</A></TD>
          <TD width="4%"></TD></TR>
          <TR>
          <TD width="4%"></TD>
          <TD><IMG src="http://www.ppn.f2s.com/images/bullet.gif"></TD>
          <TD><A class=nav
            href="cpanel.php?action=currentusers">Current&nbsp;Members</A></TD>
          <TD width="4%"></TD></TR>
          <TR>
          <TD width="4%"></TD>
          <TD><IMG src="http://www.ppn.f2s.com/images/bullet.gif"></TD>
          <TD><A class=nav
            href="cpanel.php?action=editusers">Edit&nbsp;Members</A></TD>
          <TD width="4%"></TD></TR>
          <TR>
          <TD width="4%"></TD>
          <TD><IMG src="http://www.ppn.f2s.com/images/bullet.gif"></TD>
          <TD><A class=nav
            href="cpanel.php?action=banlists">Ban&nbsp;List</A></TD>
          <TD width="4%"></TD></TR>
          <TR>
          <TD width="4%"></TD>
          <TD><IMG src="http://www.ppn.f2s.com/images/bullet.gif"></TD>
          <TD><A class=nav
            href="cpanel.php?action=cheat">Cheating&nbsp;Report</A></TD>
          <TD width="4%"></TD></TR>
          <TR>
          <TD width="4%"></TD>
          <TD><IMG src="http://www.ppn.f2s.com/images/bullet.gif"></TD>
          <TD><A class=nav
            href="cpanel.php?action=changeuser">Change&nbsp;Username</A></TD>
          <TD width="4%"></TD></TR>
        <TR>
          <TD width="4%"></TD>
          <TD><IMG src="http://www.ppn.f2s.com/images/bullet.gif"></TD>
          <TD><A class=nav
            href="cpanel.php?action=changepass">Change&nbsp;Pass</A></TD>
          <TD width="4%"></TD></TR></TBODY></TABLE></DIV></TD>
    <TD vAlign=top width="79%"><SPAN class=page_title><? echo("$title $title2"); ?></SPAN>
      <p><? echo("$content"); ?><p>&nbsp;</td>
    </tr>
  </table>
</div>
<br><center><? versioncheck(); ?></center>
</BODY></HTML>
<?
}
  ?>
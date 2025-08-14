<?php
ignore_user_abort(true);
ini_set("max_execution_time",0);

include "var_user.php";

$signature = stripslashes($signature);
$signature_a = stripslashes($signature_a);
$signature = str_replace('"', "'", $signature);
$signature_a = str_replace('"', "'", $signature_a);



if ($logo != "") {
$addlogo = '<IMG SRC="'.$logo.'" ALT="'.$company.'">';
}
else { $addlogo = "<BR>&nbsp;<H3>".$company."</H3>"; }
$top = '<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="javascript">
<!--
function linked(url) {
winl = (screen.width - 600) / 2;
wint = (screen.height - 450) / 2;
winprops = "height=450, width=600, top="+wint+", left="+winl+", scrollbars, resizable";
win = window.open(url, "linked", winprops);
win.self.focus();
}
// -->
</SCRIPT>
<TITLE>Account Admin</TITLE>
<META NAME="MSSmartTagsPreventParsing" CONTENT="true">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<STYLE>
BODY { font:10px verdana;background:#EEEEEE }
TABLE { border:1px #CCCCCC solid;background:white }
TD { font:10px verdana }
a:link { color:blue }
a:visited { color:blue }
a:active { color:red }
a:hover { color:red;text-decoration:none }
.mb1 { font-weight:bold }
.s { background:#EEEEFE;font:10px verdana;border:1px #000000 solid }
.t { border:1px #000000 solid;color:navy;background:#FFFFFF }
</STYLE>
</HEAD>
<CENTER>
<H3>Account Admin</H3>
';
$bottom = '
</CENTER>
</BODY></HTML>';
$go_pref = "<FORM ACTION=\"admin.php?password=".$pw_2."\" METHOD=\"post\" ><INPUT TYPE=\"submit\" VALUE=\"Back to Admin\" CLASS=\"s\">";
$go_pass = "<FORM ACTION=\"admin.php?action=admin\" METHOD=\"post\" ><INPUT TYPE=\"submit\" VALUE=\"Enter Password\" CLASS=\"s\">";
$go_back = "<FORM><INPUT TYPE=\"button\" VALUE=\"Go Back\" CLASS=\"s\" ONCLICK=\"javascript:history.go(-1)\">";
$preferences = $top."<P>&nbsp;<H3>Updated</H3>".$go_pref."</TD></TR></TABLE></BODY></HTML>";
$pass = $top."<P>&nbsp;<H3>Password</H3>Your password has been emailed to you!".$go_pass."</TD></TR></TABLE></BODY></HTML>";
$enteremail = $top."<P>&nbsp;<H3>Wrong email address</H3>Please try again!".$go_back."</CENTER></BODY></HTML>";



$url = str_replace("/index.html", "", $url);
$url_a = str_replace("/index.html", "", $url_a);
$url = str_replace("/index.htm", "", $url);
$url_a = str_replace("/index.htm", "", $url_a);
$url = str_replace("/ ", "", $url);



if(preg_match("/htm|html/", $url_a)) { print($top."<P>&nbsp;<H3>Error</H3>You cannot have any \"htm\" or \"html\" extensions in your Website URL!".$go_back);exit; }


if ($password == $pw) {
print($top.'
<BR><TABLE WIDTH=500 CELLPADDING=20 CELLSPACING=0 BORDER=0><TR><TD VALIGN=TOP>
<B>Preview:</B> <A HREF="javascript:linked(\'email.php\');">Email Form</A>
| <A HREF="javascript:linked(\'guestbook.php?action=read\');">Guestbook</A>
| <A HREF="javascript:linked(\'mail.php\');">Mailing List</A>
</TD><TD ALIGN=RIGHT><A HREF="admin.htm">Back to Control Panel</A></TD></TR></TABLE>
<FORM ACTION="admin.php" METHOD="POST">
<P><TABLE WIDTH=500 CELLPADDING=20 CELLSPACING=0 BORDER=0><TR><TD VALIGN=TOP>
<H3>User Details</H3>
<B>Admin password:</B>
<BR>This will access all admin areas.
<BR><INPUT TYPE="text" SIZE=15 NAME="pw_2" VALUE="'.$pw.'" CLASS="t">
<P><B>Company name:</B>
<BR><INPUT TYPE="text" SIZE=30 NAME="company_a" VALUE="'.$company.'" CLASS="t">
<P><B>Website URL:</B>
<BR>Please do not add an "/" after your ".com" or ".net" etc.
<BR>http://<INPUT TYPE="text" SIZE=30 NAME="url_a" VALUE="'.$url.'" CLASS="t">
<P><B>Email address:</B>
<BR><INPUT TYPE="text" NAME="emailto_a" VALUE="'.$emailto.'" SIZE="30" CLASS="t">
<P><B>Admin email address:</B>
<BR>This is used to send the admin password if forgotten and to send out test mailouts from the mailing list service.
<BR><INPUT TYPE="text" SIZE=30 NAME="adminemail_a" VALUE="'.$adminemail.'" CLASS="t">
<P><B>Email signature:</B>
<BR>This will appear at the footer of all email &amp; autoresponder messages that are sent.
Please include your full website address again as well.
<BR><TEXTAREA NAME="signature_a" ROWS=8 COLS=30 WRAP="virtual" CLASS="t">'.$signature.'</TEXTAREA>
<P><B>Server time zone:</B> ("+", "-", or "0")
<BR>Specify time difference from where your server is hosted, to where you reside.
<BR>(eg: Server is in Dallas, you reside in Sydney, set to +15) Also consider daylight saving times.
<BR><INPUT TYPE="text" SIZE=10 NAME="zone_a" VALUE="'.$zone.'" CLASS="t">
<A HREF="http://www.timeanddate.com/worldclock/" TARGET="_blank">World Time Zones</A>
<P><INPUT TYPE="submit" NAME="send" VALUE="Update User Details" CLASS="s">
</TD></TR></TABLE>
<P><TABLE WIDTH=500 CELLPADDING=20 CELLSPACING=0 BORDER=0><TR><TD VALIGN=TOP>
<H3>Style - Background &amp; Logo</H3>
<B>Background image URL:</B>
<BR>(eg: http://www.yourcompany.com/background.gif)
<BR>Leave blank if you do not have a background image.
<BR><INPUT TYPE="text" SIZE=30 NAME="back_a" VALUE="'.$back.'" CLASS="t">
<P><B>Company logo URL:</B>
<BR>(eg: http://www.yourcompany.com/logo.gif)
<BR>Leave blank if you do not have a logo image.
<BR><INPUT TYPE="text" SIZE=30 NAME="logo_a" VALUE="'.$logo.'" CLASS="t">
<P><B>Align logo:</B>
<BR><SELECT SIZE="1" NAME="align_a">
<OPTION VALUE="'.$align.'" CLASS="t">Select
<OPTION VALUE="LEFT" CLASS="t">LEFT
<OPTION VALUE="CENTER" CLASS="t">CENTER
<OPTION VALUE="RIGHT" CLASS="t">RIGHT
</SELECT> Selected: '.$align.'
<P><B>Background colour:</B>
<BR><INPUT TYPE="text" SIZE=10 NAME="background_a" VALUE="'.$background.'" CLASS="t">
<P><INPUT TYPE="submit" NAME="send" VALUE="Update Style" CLASS="s">
</TD></TR></TABLE>
<P><TABLE WIDTH=500 CELLPADDING=20 CELLSPACING=0 BORDER=0><TR><TD VALIGN=TOP>
<H3>Style - Input Fields</H3>
<B>Field colour:</B>
<BR><INPUT TYPE="text" SIZE=10 NAME="fieldcolor_a" VALUE="'.$fieldcolor.'" CLASS="t">
<P><B>Field font colour:</B>
<BR><INPUT TYPE="text" SIZE=10 NAME="fieldtextcolor_a" VALUE="'.$fieldtextcolor.'" CLASS="t">
<P><INPUT TYPE="submit" NAME="send" VALUE="Update Style" CLASS="s">
</TD></TR></TABLE>
<P><TABLE WIDTH=500 CELLPADDING=20 CELLSPACING=0 BORDER=0><TR><TD VALIGN=TOP>
<H3>Style - Font</H3>
<B>Font style:</B>
<BR><INPUT TYPE="text" SIZE=10 NAME="font_a" VALUE="'.$font.'" CLASS="t">
<P><B>Font size:</B>
<BR><SELECT SIZE="1" NAME="size_a">
<OPTION VALUE="'.$size.'" CLASS="t">Select
<OPTION VALUE="10" CLASS="t">10px
<OPTION VALUE="11" CLASS="t">11px
<OPTION VALUE="12" CLASS="t">12px
<OPTION VALUE="13" CLASS="t">13px
</SELECT> Selected: '.$size.'px
<P><B>Font colour:</B>
<BR><INPUT TYPE="text" SIZE=10 NAME="color_a" VALUE="'.$color.'" CLASS="t">
<P><INPUT TYPE="submit" NAME="send" VALUE="Update Style" CLASS="s">
</TD></TR></TABLE>
<P><TABLE WIDTH=500 CELLPADDING=20 CELLSPACING=0 BORDER=0><TR><TD VALIGN=TOP>
<H3>Style - Links</H3>
<B>Link colour:</B>
<BR><INPUT TYPE="text" SIZE=10 NAME="link_a" VALUE="'.$link.'" CLASS="t">
<P><B>Visited link colour:</B>
<BR><INPUT TYPE="text" SIZE=10 NAME="visit_a" VALUE="'.$visit.'" CLASS="t">
<P><B>Active link colour:</B>
<BR><INPUT TYPE="text" SIZE=10 NAME="active_a" VALUE="'.$active.'" CLASS="t">
<P><B>Hover link colour:</B>
<BR><INPUT TYPE="text" SIZE=10 NAME="hover_a" VALUE="'.$hover.'" CLASS="t">
<P><B>Hover link text-decoration:</B>
<BR><SELECT SIZE="1" NAME="decoration_a">
<OPTION VALUE="'.$decoration.'" CLASS="t">Select
<OPTION VALUE="none" CLASS="t">none
<OPTION VALUE="underline" CLASS="t">underline
<OPTION VALUE="overline" CLASS="t">overline
</SELECT> Selected: '.$decoration.'
<P><INPUT TYPE="submit" NAME="send" VALUE="Update Style" CLASS="s">
</TD></TR></TABLE>
</FORM>
<P>&nbsp;<P>&nbsp;<P>&nbsp;
'.$bottom);
exit;
}
if ($action == "sendemail") {
if ($email_match == $adminemail) {

$header2 = "From: $emailto\r\n";
$header2 .= "Return-Path: $emailto\r\n";
$header2 .= "Reply-To: $emailto\r\n";
$header2 .= "Content-type: text;\r\n";
$header2 .= "Mime-Version: 1.0\r\n";
$header2 .= "X-Mailer: php Mailer\r\n";

mail("$adminemail", $company." Admin Password", "Password is:\n\n".$pw, $header2);
echo $pass;
exit;
}
else { echo $enteremail; exit; }
}
if ($action == "admin") {
print($top.'
<P>&nbsp;
<P>Enter admin password:
<P><FORM ACTION="admin.php" METHOD="POST">
<INPUT TYPE="hidden" NAME="action" VALUE="admin">
<INPUT TYPE="password" NAME="password" SIZE="15" CLASS="t">
<INPUT TYPE="submit" VALUE="Submit" CLASS="s">
</FORM>
<P>Forgot password?
<P>Enter your admin email address
<BR>and your password will be sent to you.
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="action" VALUE="sendemail">
<INPUT TYPE="test" NAME="email_match" SIZE="15" CLASS="t">
<INPUT TYPE="submit" VALUE="Submit" CLASS="s">
</FORM>
'.$bottom); exit;
}
if ($send == "Update User Details" or $send == "Update Style") {
if ($pw_2 == "") { echo $nopass; exit; }
else {
$writelocation = "var_user.php";
$newfile = fopen($writelocation,"w+");
$add = '<?PHP
$company = "'.$company_a.'";
$pw = "'.$pw_2.'";
$emailto = "'.$emailto_a.'";
$adminemail = "'.$adminemail_a.'";
$signature = "'.$signature_a.'";
$back = "'.$back_a.'";
$logo = "'.$logo_a.'";
$background = "'.$background_a.'";
$font = "'.$font_a.'";
$size = "'.$size_a.'";
$color = "'.$color_a.'";
$link = "'.$link_a.'";
$visit = "'.$visit_a.'";
$hover = "'.$hover_a.'";
$active = "'.$active_a.'";
$decoration = "'.$decoration_a.'";
$fieldcolor = "'.$fieldcolor_a.'";
$fieldtextcolor = "'.$fieldtextcolor_a.'";
$align = "'.$align_a.'";
$url = "'.$url_a.'";
$zone = "'.$zone_a.'";
$zone2 = ($zone - 24);
$zone3 = ($zone - 48);
$zone4 = ($zone - 72);
$zone5 = ($zone - 96);
$zone6 = ($zone - 120);
$zone7 = ($zone - 144);
$zone8 = ($zone - 168);
?>';
fwrite($newfile, $add);
fclose($newfile);
echo $preferences;
exit;
}
}
?>
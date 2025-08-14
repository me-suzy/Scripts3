<?php
ignore_user_abort(true);
ini_set("max_execution_time",0);

include "var_em.php";
include "var_user.php";

$br = $HTTP_SERVER_VARS["HTTP_USER_AGENT"];
if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]  !=  "") {
$rmt = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
}
else { $rmt = $HTTP_SERVER_VARS["REMOTE_ADDR"]; }
$css = '<STYLE>
BODY { font:'.$size.'px '.$font.';color:'.$color.';background:'.$background.';background-image:url("'.$back.'");
background-attachment:fixed }
TD { font:'.$size.'px '.$font.' }
a:link { color:'.$link.' }
a:visited { color:'.$visit.' }
a:active { color:'.$active.' }
a:hover { color:'.$hover.';text-decoration:'.$decoration.' }
.mb1 { font-weight:bold }
.s { background:#EEEEFE;font:10px verdana;border:1px #000000 solid }
.t { border:1px #000000 solid;color:'.$fieldtextcolor.';background:'.$fieldcolor.' }
</STYLE>';
if ($logo != "") {
$addlogo = '<IMG SRC="'.$logo.'" ALT="'.$company.'">';
}
else { $addlogo = "<BR>&nbsp;<H3>".$company."</H3>"; }

$name = stripslashes($name);
$location = stripslashes($location);
$message = stripslashes($message);
$message = str_replace ('"',"'",$message);
$rtext = stripslashes($rtext);
$rtext_a = stripslashes($rtext_a);
$esubject = $company." Message";
$rsubject = "Re: ".$company." Message (Autorespond)";

$top = '<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<TITLE>'.$company.' Email</TITLE>
<META NAME="MSSmartTagsPreventParsing" CONTENT="true">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
'.$css.'
</HEAD>
<BODY>
<DIV ALIGN="'.$align.'">'.$addlogo.'</DIV>
<H3 ALIGN=CENTER>Email</H3><P>&nbsp;<CENTER>
';
$top2 = '<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<TITLE>Email Form Admin</TITLE>
<META NAME="MSSmartTagsPreventParsing" CONTENT="true">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<STYLE>
BODY { font:10px verdana;color:black;background:#EEEEEE }
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
<BODY>
<CENTER>
<H3>Email Form Admin</H3>
';


$bottom = '
</TD></TR></TABLE>
</BODY></HTML>';

$formdetail_1 = '<FORM ACTION="email.php" METHOD="POST">
                 <INPUT NAME="action" TYPE="hidden" VALUE="read">
                 <INPUT NAME="name" TYPE="hidden" VALUE="'.$name.'">
                 <INPUT NAME="location" TYPE="hidden" VALUE="'.$location.'">
                 <INPUT NAME="email" TYPE="hidden" VALUE="'.$email.'">
                 <INPUT NAME="website" TYPE="hidden" VALUE="'.$website.'">
                 <INPUT NAME="message" TYPE="hidden" VALUE="'.$message.'">
                 <INPUT TYPE="submit" NAME="send" VALUE="Edit details" CLASS="s">
                 </FORM>';

$formdetail_2 = '<FORM ACTION="email.php" METHOD="POST">
                 <INPUT NAME="name" TYPE="hidden" VALUE="'.$name.'">
                 <INPUT NAME="location" TYPE="hidden" VALUE="'.$location.'">
                 <INPUT NAME="email" TYPE="hidden" VALUE="'.$email.'">
                 <INPUT NAME="website" TYPE="hidden" VALUE="'.$website.'">
                 <INPUT NAME="message" TYPE="hidden" VALUE="'.$message.'">
                 <INPUT TYPE="submit" NAME="send" VALUE="Send message" CLASS="s">
                 </FORM>';

$goback = "<FORM ACTION=\"email.php\" METHOD=\"post\" ><INPUT TYPE=\"submit\" VALUE=\"Back to Email Form\" CLASS=\"s\">";
$go_admin = "<FORM ACTION=\"email.php?password=".$pw."\" METHOD=\"post\" ><INPUT TYPE=\"submit\" VALUE=\"Back to Admin\" CLASS=\"s\">";
$go_pref = "<FORM ACTION=\"email.php?password=".$pw."\" METHOD=\"post\" ><INPUT TYPE=\"submit\" VALUE=\"Back to Admin\" CLASS=\"s\">";
$go_pass = "<FORM ACTION=\"email.php?action=admin\" METHOD=\"post\" ><INPUT TYPE=\"submit\" VALUE=\"Enter Password\" CLASS=\"s\">";
$return = "<FORM><INPUT TYPE=\"button\" VALUE=\"Back to admin\" CLASS=\"s\" ONCLICK=\"javascript:history.go(-1)\">";
$nopass = $top."<H3>Sorry!</H3>You must enter a password!".$return.$bottom;
$go_back = "<FORM><INPUT TYPE=\"button\" VALUE=\"Go Back\" CLASS=\"s\" ONCLICK=\"javascript:history.go(-1)\">";
$enteremail = $top."<H3>Wrong email address</H3>Please try again!".$go_back."</CENTER></BODY></HTML>";

$noname = $top."<H3>Sorry!</H3>You did not enter your name!".$formdetail_1.$bottom;
$emailnot = $top."<H3>$email</H3>There seems to be a problem with your email address!".$formdetail_1.$bottom;
$noemail = $top."<H3>Sorry!</H3>You did not enter an email address!".$formdetail_1.$bottom;
$privemail =  $top."<H3>Sorry!</H3>You need to supply an email address<BR>to send a private email!".$formdetail_1.$bottom;
$nolocation = $top."<H3>Sorry!</H3>You did not enter your location!".$formdetail_1.$bottom;
$nomessage = $top."<H3>Sorry!</H3>You did not enter your message!".$formdetail_1.$bottom;
$toomany = $top."<H3>Sorry!</H3>Your message exceeds 2000 characters!".$formdetail_1.$bottom;
$success = $top."<H3>Thank You<BR>".$name."!</H3>Your email message has been sent!".$goback."</TD></TR></TABLE></BODY></HTML>";
$preferences = $top2."<P>&nbsp;<H3>Updated</H3>".$go_pref."</TD></TR></TABLE></BODY></HTML>";
$pass = $top."<P>&nbsp;<H3>Password</H3>Your password has been emailed to you!".$go_pass."</TD></TR></TABLE></BODY></HTML>";

if ($password == $pw) {

print($top2.'
<P>&nbsp;<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH=500 ALIGN=CENTER><TR><TD VALIGN=TOP>
<H3>Update</H3>
<FORM ACTION="email.php" METHOD="POST">
<P><B>Email autorespond thank-you message:</B>
<BR>&nbsp;
<BR><TEXTAREA NAME="rtext_a" ROWS=10 COLS=50 WRAP="virtual" CLASS="t">'.$rtext.'</TEXTAREA>
<BR>Your email signature will appear automatically at the foot of your message.
<P><INPUT TYPE="submit" NAME="send" VALUE="Update Email Form" CLASS="s">
</FORM>
'.$bottom);
exit;
}
if ($send == "Update Email Form") {
if ($pw == "") { echo $nopass; exit; }
else {
$writelocation = "var_em.php";
$newfile = fopen($writelocation,"w+");
$add = '<?PHP
$rtext= "'.$rtext_a.'";
}
?>';
fwrite($newfile, $add);
fclose($newfile);
echo $preferences;
exit;
}
}
if ($action == "admin") {
print($top2.'
<P>&nbsp;
<P>Enter admin password:
<P><FORM ACTION="email.php" METHOD="POST">
<INPUT TYPE="hidden" NAME="action" VALUE="admin">
<INPUT TYPE="password" NAME="password" SIZE="15" CLASS="t">
<INPUT TYPE="submit" VALUE="Submit" CLASS="s">
</FORM>
'.$bottom); exit;
}
if ($send == "Edit details") {
$message = str_replace ("<BR>","\n",$message);
}


if ($send == "Preview message") {

if ($name == "") { echo $noname; exit; }
else if ($location == "") { echo $nolocation; exit; }
else if ($email == "") { echo $noemail; exit; }
else if (!checkmail($email)) { echo $emailnot;  exit; }
else if ($message == "") { echo $nomessage; exit; }

print($top.'<H3>Preview Message</H3>
<TABLE WIDTH="80%" CELLPADDING=0 CELLSPACING=0 BORDER=0><TR><TD>
<HR><DIV>&nbsp;</DIV>
<B>Name:</B> '.$name.'
<BR><B>Email:</B> <SPAN STYLE="color:'.$link.'">'.$email.'</SPAN>
<BR><B>Location:</B> '.$location.'
<BR><B>Website:</B> <SPAN STYLE="color:'.$link.'">'.$website.'</SPAN>
<P>'.$message.'
<P>&nbsp;<HR></TD></TR></TABLE>
<P><TABLE CELLPADDING=3 CELLSPACING=0 BORDER=0><TR><TD>
'.$formdetail_1.'
</TD><TD>
'.$formdetail_2.'
</TD></TR></TABLE><P>&nbsp;<P>&nbsp;<P>&nbsp;</TD></TR></TABLE>
</BODY>
</HTML>'.$bottom);
exit;
}
else if ($send == "Send message") {
$etext = "
".$name."
".$email."
".$location."
".$website."

".$message."

".$rmt."
".$br."

";
$header1 = "From: $email\r\n";
$header1 .= "Return-Path: $email\r\n";
$header1 .= "Reply-To: $email\r\n";
$header1 .= "Content-type: text;\r\n";
$header1 .= "Mime-Version: 1.0\r\n";
$header1 .= "X-Mailer: php Mailer\r\n";
$header2 = "From: $emailto\r\n";
$header2 .= "Return-Path: $emailto\r\n";
$header2 .= "Reply-To: $emailto\r\n";
$header2 .= "Content-type: text;\r\n";
$header2 .= "Mime-Version: 1.0\r\n";
$header2 .= "X-Mailer: php Mailer\r\n";

mail("$emailto", $esubject, $etext, $header1);
mail("$email", $rsubject, $rtext."\n\n".$signature, $header2);
echo $success;
exit;
}

if ($send != "Edit details") {
$name = "";
$location = "";
$email = "";
$website = "http://";
$message = "";
}


print('<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<TITLE>'.$company.' Email</TITLE>
<META NAME="MSSmartTagsPreventParsing" CONTENT="true">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
'.$css.'
</HEAD>
<BODY>
<DIV ALIGN="'.$align.'">'.$addlogo.'</DIV>
<H3 ALIGN=CENTER>Email</H3><P>&nbsp;<CENTER>
<TABLE ALIGN=CENTER CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH=550><TR><TD ALIGN=CENTER>
<FORM ACTION="email.php" METHOD="POST">
<TABLE CELLSPACING=1 CELLPADDING=1 BORDER=0>
<TR><TD><B>Name</B><BR><INPUT NAME="name" TYPE=Text SIZE="30" MAXLENGTH="40" VALUE="'.$name.'" CLASS="t"></FONT></TD><TD><B>Location</B><BR><INPUT NAME="location" TYPE=Text SIZE="30" MAXLENGTH="40" VALUE="'.$location.'" CLASS="t"></TD></TR>
<TR><TD><B>Email</B><BR><INPUT NAME="email" TYPE=Text SIZE="30" MAXLENGTH="40" VALUE="'.$email.'" CLASS="t"></TD><TD><B>Website</B><BR><INPUT NAME="website" TYPE=Text VALUE="'.$website.'" SIZE="30" MAXLENGTH="40" VALUE="'.$website.'" CLASS="t"></TD></TR>
<TR><TD COLSPAN=2>
<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=0>
<TR><TD><B>Message</B><BR><TEXTAREA NAME="message" ROWS=5 COLS=55 WRAP="virtual" CLASS="t">'.$message.'</TEXTAREA></TD></TR>
<TR><TD><INPUT TYPE="submit" NAME="send" VALUE="Preview message" CLASS="s"></TD></TR></TD></TR>
</TABLE>
</TD></TR></TABLE>
<P>Sponsored link: <A HREF="http://www.robtognoni.com" TARGET="_blank">Rob Tognoni Power Blues Rock</A>
</FORM>'.$bottom);


function checkmail($string){
return preg_match("/^[^\s()<>@,;:\"\/\[\]?=]+@\w[\w-]*(\.\w[\w-]*)*\.[a-z]{2,}$/i",$string);
}
?>
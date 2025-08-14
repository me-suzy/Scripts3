<?php
ignore_user_abort(true);
ini_set("max_execution_time",0);

include "var_gb.php";
include "var_user.php";

$br = $HTTP_SERVER_VARS["HTTP_USER_AGENT"];
if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]  !=  "") {
$rmt = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
}
else { $rmt = $HTTP_SERVER_VARS["REMOTE_ADDR"]; }

$day = date("D", strtotime("$zone hours"));
$date1 = date("D d.m", strtotime("$zone hours"));
$date2 = date("D d.m", strtotime("$zone hours"));
$date3 = date("D d.m", strtotime("$zone hours"));
$date4 = date("D d.m", strtotime("$zone hours"));
$date5 = date("D d.m", strtotime("$zone hours"));
$date6 = date("D d.m", strtotime("$zone hours"));
$date7 = date("D d.m", strtotime("$zone hours"));
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

$name = stripslashes($name);
$location = stripslashes($location);
$message=stripslashes($message);
$message2=stripslashes($message2);

$gtext_2_a = stripslashes($gtext_2_a);
$ttext_a = stripslashes($ttext_a);

$filelocation = "messages.txt";
$gsubject = $company." Guestbook Addition"; 
$gsubject_2 = $company." Guestbook - Thank You";
$esubject = $company." Message";
$tsubject = "Re: ".$company." Message (Autorespond)";

if ($logo != "") {
$addlogo = '<IMG SRC="'.$logo.'" ALT="'.$company.'">';
}
else { $addlogo = "<BR>&nbsp;<H3>".$company."</H3>"; }

$top = '<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<TITLE>'.$company.' Guestbook</TITLE>
<META NAME="MSSmartTagsPreventParsing" CONTENT="true">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<STYLE>
BODY { font:10px verdana;color:black;background:white }
TD { font:10px verdana }
a:link { color:blue }
a:visited { color:blue }
a:active { color:red }
a:hover { color:red;text-decoration:none }
.mb1 { font-weight:bold }
.s { background:#EEEEFE;font:10px verdana;border:1px #000000 solid }
.t { border:1px #000000 solid;color:navy;background:ivory }
</STYLE>
</HEAD>
<BODY>
<DIV ALIGN="'.$align.'">'.$addlogo.'</DIV>
<H3 ALIGN=CENTER>Guestbook</H3><P>&nbsp;<CENTER>
';
$bottom = '
</TD></TR></TABLE>
</BODY></HTML>';
$top2 = '<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<TITLE>Guestbook Admin</TITLE>
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
<H3>Guestbook Admin</H3>
';
$formdetail_1 = '<FORM ACTION="guestbook.php" METHOD="POST">
                 <INPUT NAME="action" TYPE="hidden" VALUE="read">
                 <INPUT NAME="name" TYPE="hidden" VALUE="'.$name.'">
                 <INPUT NAME="location" TYPE="hidden" VALUE="'.$location.'">
                 <INPUT NAME="email" TYPE="hidden" VALUE="'.$email.'">
                 <INPUT NAME="website" TYPE="hidden" VALUE="'.$website.'">
                 <INPUT NAME="message" TYPE="hidden" VALUE="'.$message.'">
                 <INPUT NAME="private" TYPE="hidden" VALUE="'.$private.'">
                 <INPUT TYPE="submit" NAME="send" VALUE="Edit details" CLASS="s">
                 </FORM>';

$formdetail_2 = '<FORM ACTION="guestbook.php" METHOD="POST">
                 <INPUT NAME="name" TYPE="hidden" VALUE="'.$name.'">
                 <INPUT NAME="location" TYPE="hidden" VALUE="'.$location.'">
                 <INPUT NAME="email" TYPE="hidden" VALUE="'.$email.'">
                 <INPUT NAME="website" TYPE="hidden" VALUE="'.$website.'">
                 <INPUT NAME="message" TYPE="hidden" VALUE="'.$message.'">
                 <INPUT NAME="private" TYPE="hidden" VALUE="'.$private.'">
                 <INPUT TYPE="submit" NAME="send" VALUE="Send message" CLASS="s">
                 </FORM>';

$return = "<FORM><INPUT TYPE=\"button\" VALUE=\"Back to Administration\" CLASS=\"s\" ONCLICK=\"javascript:history.go(-1)\">";
$goback = "<FORM ACTION=\"guestbook.php?action=read\" METHOD=\"post\" ><INPUT TYPE=\"submit\" VALUE=\"View Guestbook\" CLASS=\"s\">";
$go_admin = "<FORM ACTION=\"guestbook.php?password=".$pw."\" METHOD=\"post\" ><INPUT TYPE=\"submit\" VALUE=\"Back to Admin\" CLASS=\"s\">";
$go_pref = "<FORM ACTION=\"guestbook.php?password=".$pw."\" METHOD=\"post\" ><INPUT TYPE=\"submit\" VALUE=\"Back to Admin\" CLASS=\"s\">";
$go_back = "<FORM><INPUT TYPE=\"button\" VALUE=\"Go Back\" CLASS=\"s\" ONCLICK=\"javascript:history.go(-1)\">";
$enteremail = $top."<H3>Wrong email address</H3>Please try again!".$go_back."</CENTER></BODY></HTML>";

$noname = $top."<H3>Sorry!</H3>You did not enter your name!".$formdetail_1.$bottom;
$emailnot = $top."<H3>$email</H3>There seems to be a problem with your email address!".$formdetail_1.$bottom;
$noemail = $top."<H3>Sorry!</H3>You did not enter an email address!".$formdetail_1.$bottom;
$privemail =  $top."<H3>Sorry!</H3>You need to supply an email address<BR>to send a private email!".$formdetail_1.$bottom;
$nolocation = $top."<H3>Sorry!</H3>You did not enter your location!".$formdetail_1.$bottom;
$nomessage = $top."<H3>Sorry!</H3>You did not enter your message!".$formdetail_1.$bottom;
$toomany = $top."<H3>Sorry!</H3>Your message exceeds 2000 characters!".$formdetail_1.$bottom;
$success1 = $top."<H3>Thank You<BR>".$name."!</H3>Your private message has been sent!".$goback."</TD></TR></TABLE></BODY></HTML>";
$success2 = $top."<H3>Thank You<BR> ".$name."!</H3>Your message was successfully posted!".$goback."</TD></TR></TABLE></BODY></HTML>";
$abuse = $top."<H3>Sorry!</H3>Some of your words are too long for the English language!<BR>Please try again...".$formdetail_1.$bottom;
$swear = $top."<H3>Oops!</H3>Some language can offend others...<BR>please delete expletives :)".$formdetail_1.$bottom;
$done = $top2."<P>&nbsp;<H3>Deleted</H3>Your message has been deleted!".$goback."</TD></TR></TABLE></BODY></HTML>";
$done_all = $top2."<P>&nbsp;<H3>Deleted</H3>Your messageboard has been cleared!".$go_admin."</TD></TR></TABLE></BODY></HTML>";
$preferences = $top2."<P>&nbsp;<H3>Updated</H3>".$go_pref."</TD></TR></TABLE></BODY></HTML>";

if ($password == $pw) {

print($top2.'
<P>&nbsp;<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH=500 ALIGN=CENTER><TR><TD VALIGN=TOP>
<H3>Delete Messages</H3>
<FORM ACTION="guestbook.php" METHOD="POST">
<INPUT TYPE="hidden" NAME="done" VALUE="">
Enter message number to delete message.
<BR>"Private message" will show in place of deleted message.
<P><INPUT TYPE="text" NAME="line_number" VALUE="" SIZE="10" CLASS="t">
<INPUT TYPE="submit" NAME="delete" VALUE="Delete Message" CLASS="s">
<P>To delete ALL messages, enter the word "clear".
</FORM>
<P>&nbsp;
<H3>Update</H3>
<FORM ACTION="guestbook.php" METHOD="POST">
<B>Number of posts per page:</B>
<BR><INPUT TYPE="text" SIZE=10 NAME="posts_a" VALUE="'.$posts.'" CLASS="t">
<P>&nbsp;
<P><B>Guestbook autorespond thank-you message:</B>
<BR>&nbsp;
<BR><TEXTAREA NAME="gtext_2_a" ROWS=10 COLS=50 WRAP="virtual" CLASS="t">'.$gtext_2.'</TEXTAREA>
<BR>Your email signature will appear automatically at the foot of your message.
<P>&nbsp;
<P><B>Private message autorespond thank-you message:</B>
<BR>&nbsp;
<BR><TEXTAREA NAME="ttext_a" ROWS=10 COLS=50 WRAP="virtual" CLASS="t">'.$ttext.'</TEXTAREA>
<BR>Your email signature will appear automatically at the foot of your message.
<P><INPUT TYPE="submit" NAME="send" VALUE="Update Guestbook" CLASS="s">
<P>&nbsp;
</FORM>
'.$bottom);
exit;
}
if ($action == "admin") {
print($top2.'
<P>&nbsp;
<P>Enter admin password:
<P><FORM ACTION="guestbook.php" METHOD="POST">
<INPUT TYPE="hidden" NAME="action" VALUE="admin">
<INPUT TYPE="password" NAME="password" SIZE="15" CLASS="t">
<INPUT TYPE="submit" VALUE="Submit" CLASS="s">
</FORM>
'.$bottom); exit;
}
if ($send == "Update Guestbook") {
if ($pw == "") { echo $nopass; exit; }
else {
$writelocation = "var_gb.php";
$newfile = fopen($writelocation,"w+");
$add = '<?PHP
$gtext_2 = "'.$gtext_2_a.'";
$ttext = "'.$ttext_a.'";
$posts = "'.$posts_a.'";

?>';
fwrite($newfile, $add);
fclose($newfile);

echo $preferences;
exit;
}
}
if($delete != "Delete Message") {

if ($action == "read") {

if ($private == "on") { $checkit = " CHECKED"; }
else if ($private != "on") { $checkit = ""; }

if ($send != "Edit details") {
$name = "";
$location = "";
$email = "";
$website = "http://";
$message = "";
}
if ($send == "Edit details") {
$message = str_replace ("<BR>","\n",$message);
}
print('<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<TITLE>'.$company.' Guestbook</TITLE>
<META NAME="MSSmartTagsPreventParsing" CONTENT="true">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
'.$css.' 
</HEAD>
<BODY>
<DIV ALIGN="'.$align.'">'.$addlogo.'</DIV>
<H3 ALIGN=CENTER>Guestbook</H3><P>&nbsp;<CENTER>
<TABLE ALIGN=CENTER CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH=550><TR><TD ALIGN=CENTER>
<FORM ACTION="guestbook.php" METHOD="POST">
<TABLE CELLSPACING=1 CELLPADDING=1 BORDER=0>
<TR><TD><B>Name</B><BR><INPUT NAME="name" TYPE=Text SIZE="30" MAXLENGTH="40" VALUE="'.$name.'" CLASS="t"></FONT></TD><TD><B>Location</B><BR><INPUT NAME="location" TYPE=Text SIZE="30" MAXLENGTH="40" VALUE="'.$location.'" CLASS="t"></TD></TR>
<TR><TD><B>Email</B><BR><INPUT NAME="email" TYPE=Text SIZE="30" MAXLENGTH="40" VALUE="'.$email.'" CLASS="t"></TD><TD><B>Website</B><BR><INPUT NAME="website" TYPE=Text VALUE="'.$website.'" SIZE="30" MAXLENGTH="40" VALUE="'.$website.'" CLASS="t"></TD></TR>
<TR><TD COLSPAN=2><B>Private message</B> <INPUT NAME="private" TYPE="checkbox"'.$checkit.'><SPAN CLASS="small">Check box to email your message only.</SPAN></TD></TR>
<TR><TD COLSPAN=2>
<TABLE BORDER=0 CELLSPACING=2 CELLPADDING=0>
<TR><TD><B>Message</B><BR><TEXTAREA NAME="message" ROWS=5 COLS=55 WRAP="virtual" CLASS="t">'.$message.'</TEXTAREA></TD></TR>
<TR><TD><INPUT TYPE="submit" NAME="send" VALUE="Preview message" CLASS="s"></TD></TR></TD></TR>
</TABLE>
</TD></TR></TABLE>
<P>Sponsored link: <A HREF="http://www.robtognoni.com" TARGET="_blank">Rob Tognoni Power Blues Rock</A>
</FORM>
<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH="85%"><TR><TD><HR>');

$fileName = file ($filelocation);
$rows = count ($fileName);

if ($rows > $posts) {

if (!isset ($row) ) { $row = 0; }

print ('
<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH="100%"><TR><TD WIDTH="50%">');

if ($row > 0) { echo '<A HREF="javascript:history.go(-1)">&lt;&lt; Go Back</A>'; }

print ('</TD><TD WIDTH="50%" ALIGN=RIGHT>');

if ( ($rows - $row) > $posts) { echo '<A HREF="guestbook.php?action=read&row='.($row + $posts).'">Next '.$posts.' &gt;&gt;</A>'; }

print ('</TD></TR></TABLE>
<P>');

for ($i = $row; $i < ($row + $posts); $i++) { echo $fileName [$i]; }

if (!isset ($row) ) { $row = 0; }

print ('<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH="100%"><TR><TD WIDTH="50%">');
  
if ($row > 0) { echo '<A HREF="javascript:history.go(-1)">&lt;&lt; Go Back</A>'; }

print ('</TD><TD WIDTH="50%" ALIGN=RIGHT>');

if ( ($rows - $row) > $posts) { echo '<A HREF="guestbook.php?action=read&row='.($row + $posts).'">Next '.$posts.' &gt;&gt;</A>'; }

print ('</TD></TR></TABLE><P>');
}
else {
for ($i=0; $i < $rows; $i++) { echo $fileName [$i]; }
}
  
print('</TD></TR></TABLE><P>&nbsp;<P>&nbsp;</TD></TR></TABLE>
</BODY>
</HTML>');
}

else if ($action != "read") {

  $message = str_replace ("\n","<BR>",$message);
  $message = str_replace ("\r","",$message);
  $message = str_replace ('"',"'",$message);
  $message = strip_tags ($message, '<BR>');
  $message2 = str_replace ("<BR>","\n",$message);

if (preg_match("/\\w{50,}/", $_POST[message])) { 
echo $abuse;
exit; 
}

else if ($name == "") { echo $noname; exit; }
else if ($location == "") { echo $nolocation; exit; }
else if ($message == "")  { echo $nomessage; exit; }
else if(strlen($message) > 2000) { echo $toomany; exit; }
else if (preg_match("/shit/i",$message)) { echo $swear; exit; }
else if (preg_match("/cunt/i",$message)) { echo $swear; exit; }
else if (preg_match("/fuck/i",$message)) { echo $swear; exit; }

 $fileN = file ($filelocation);
 $rows = count ($fileN);

if ($send == "Preview message") {

if ($private[0]) {
if ($email == "") { echo $privemail;  exit; }
else if (!checkmail($email)) { echo $emailnot;  exit; }
}

if ($private == "on") { $messagetype = 'Send as private email only.'; }
else if ($private != "on") { $messagetype = 'Post to Guestbook.'; }

print($top.'<H3>Preview Message</H3>
<TABLE WIDTH="80%" CELLPADDING=0 CELLSPACING=0 BORDER=0><TR><TD>
<HR><DIV ALIGN=RIGHT>#'.$rows.' | '.$date1.'</DIV>
<B>Name:</B> '.$name.'
<BR><B>Email:</B> <SPAN STYLE="color:'.$link.'">'.$email.'</SPAN>
<BR><B>Location:</B> '.$location.'
<BR><B>Website:</B> <SPAN STYLE="color:'.$link.'">'.$website.'</SPAN>
<P>'.$message.'
<P>&nbsp;<HR></TD></TR></TABLE>
<P><B>Mode:</B> '.$messagetype.'
<P><TABLE CELLPADDING=3 CELLSPACING=0 BORDER=0><TR><TD>
'.$formdetail_1.'
</TD><TD>
'.$formdetail_2.'
</TD></TR></TABLE><P>&nbsp;<P>&nbsp;<P>&nbsp;</TD></TR></TABLE>
</BODY>
</HTML>');

}
else if ($send == "Send message") {

if ($private == "on") { 

$message = str_replace ("<BR>","\n",$message);
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

$etext = stripslashes($etext);
mail($emailto, $esubject, $etext, $header1);
$ttext=stripslashes($ttext);
mail($email, $tsubject, $ttext."\n\n".$signature, $header2);
echo $success1;
}
else {

  $message2=stripslashes($message2);
  $newRow = '<DIV ALIGN=RIGHT CLASS="small">#'.$rows.' | '.$date1.
            '</DIV><SPAN CLASS="mb1">Name:</SPAN> '.$name.
            '<BR><SPAN CLASS="mb1">Location:</SPAN> '.$location.
            '<BR><SPAN CLASS="mb1">Email:</SPAN> <A HREF="mailto:'.$email.'">'.$email.
            '</A><BR><SPAN CLASS="mb1">Site:</SPAN> <A HREF="'.$website.
            '" TARGET="_blank">'.$website.'</A></SPAN><P>'.$message.
            '<P>&nbsp;<HR>';
  	      
  $oldRows = join ('', file ($filelocation) );
  $fileName = fopen ($filelocation, 'w');
  fputs ($fileName, $newRow . chr(13) . chr(10) . $oldRows);
  fclose ($fileName);

echo $success2;

$gtext = "
".$name."
".$email."
".$location."
".$website."

".$message2."

".$rmt."
".$br."
".$url."/web/guestbook.php?action=read


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

$gtext = stripslashes($gtext);
mail($emailto, $gsubject, $gtext, $header1);
$gtext_2 = stripslashes($gtext_2);
mail($email, $gsubject_2, $gtext_2."\n\n".$signature, $header2);
}
}
}
}
function checkmail($string){
return preg_match("/^[^\s()<>@,;:\"\/\[\]?=]+@\w[\w-]*(\.\w[\w-]*)*\.[a-z]{2,}$/i",$string);
}

function cutline($filename,$line) {
$data = file($filename);
$nu = count($data);
$newString = "<DIV ALIGN=RIGHT CLASS=\"pr\">Private message</DIV><HR>\n";
$data[$line-1] = $newString;
$fp = fopen( $filename, "w" );
for ($i = 0; $i < $nu; $i++) { fwrite($fp, $data[$i]); }
fclose( $fp );
} 

if($delete == "Delete Message") {
$countfile= file ($filelocation);
$numb = count ($countfile);

if ($line_number == "clear") {
$fc = fopen( $filelocation, "w" );
$add = "<DIV>&nbsp;</DIV>";
fwrite($fc, $add);
fclose($fc);
echo $done_all; exit;
}
else {
cutline($filelocation,($numb - $line_number));
echo $done; exit;
}
}
?>
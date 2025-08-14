<?PHP
ignore_user_abort(true);
ini_set("max_execution_time",0);

include "var_ml.php";
include "var_user.php";
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

$subscribemail = str_replace ('"',"'",$subscribemail);
$subscribemail = stripslashes($subscribemail);
$message = str_replace ('"',"'",$message);
$message = stripslashes($message);
$message_a = str_replace ('"',"'",$message_a);
$message_a = stripslashes($message_a);
$company = stripslashes($company);
$company_a = stripslashes($company_a);

if ($logo != "") {
$addlogo = '<IMG SRC="'.$logo.'" ALT="'.$company.'">';
}
else { $addlogo = "<BR>&nbsp;<H3>".$company."</H3>"; }

$top = '<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<TITLE>'.$company.' Mailing List</TITLE>
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
<H3>Mailing List Admin</H3>
';

$bottom = '</CENTER></BODY></HTML>';

$return = "<FORM><INPUT TYPE=\"button\" VALUE=\"Back to Admin\" CLASS=\"s\" ONCLICK=\"javascript:history.go(-1)\">";
$nopass = $top."<H3>Sorry!</H3>You must enter a password!".$return.$bottom;
$go_pass = "<FORM ACTION=\"mail_admin.php?action=admin\" METHOD=\"post\" ><INPUT TYPE=\"submit\" VALUE=\"Enter Password\" CLASS=\"s\">";
$go_pref = "<FORM ACTION=\"mail_admin.php?password=".$pw."\" METHOD=\"post\" ><INPUT TYPE=\"submit\" VALUE=\"Back to Admin\" CLASS=\"s\">";
$go_back = "<FORM><INPUT TYPE=\"button\" VALUE=\"Go Back\" CLASS=\"s\" ONCLICK=\"javascript:history.go(-1)\">";
$preferences = $top."<P>&nbsp;<H3>Updated</H3>".$go_pref."</CENTER></BODY></HTML>";
$pass = $top."<H3>Password</H3>Your password has been emailed to you!".$go_pass."</CENTER></BODY></HTML>";
$messagesent = $top."&nbsp;<H3>Success</H3>Your mailout was successful!".$go_back."</CENTER></BODY></HTML>";
$enteremail = $top."<H3>Wrong email address</H3>Please try again!".$go_back."</CENTER></BODY></HTML>";


if ($action == "subscribers") {
$fileat = "subscribers.txt";
$newf = fopen($fileat,"r");
$content = @fread($newf, filesize($fileat));
$content = str_replace ("%","<BR>",$content);
fclose($newf);
print($top.'
&nbsp;<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH=500 ALIGN=CENTER><TR><TD VALIGN=TOP>
<H3>Subscribers</H3>
'.$go_back.'<P>&nbsp;'.$content.'</DIV></TD></TR></TABLE></BODY></HTML>');
exit;
}

if ($send == "Update Mailing List") {
if ($pw == "") { echo $nopass; exit; }
else {
$writelocation = "var_ml.php";
$newfile = fopen($writelocation,"w+");
$add = '<?PHP
$subscribemail = "'.$subscribemail_a.'";
$message = "'.$message_a.'";
?>';
fwrite($newfile, $add);
fclose($newfile);

echo $preferences;
exit;
}
}

if ($password == $pw) {
if ($send != "Send Message") {

$newf = fopen("subscribers.txt","r");
$cont = @fread($newf, filesize("subscribers.txt"));
fclose($newf);
$line = explode("%",$cont);
$countlines = count($line);
$countl = ($countlines - 1);

if($countl >= 1) $ca = 'STYLE="color:red"';
else '';
if($countl > 200) $cb = 'STYLE="color:red"';
else '';
if($countl > 400) $cc = 'STYLE="color:red"';
else '';
if($countl > 600) $cd = 'STYLE="color:red"';
else '';
if($countl > 800) $ce = 'STYLE="color:red"';
else '';
if($countl > 1000) $cf = 'STYLE="color:red"';
else '';
if($countl > 1200) $cg = 'STYLE="color:red"';
else '';
if($countl > 1400) $ch = 'STYLE="color:red"';
else '';
if($countl > 1600) $ci = 'STYLE="color:red"';
else '';
if($countl > 1800) $ck = 'STYLE="color:red"';
else '';



print($top.'
<SCRIPT LANGUAGE="javascript">
function wait() {
alert("Sending 200 emails can take a few minutes\ndepending on your server speed.\n\nPlease click \"OK\" to continue and wait for confirmation...\n\nDO NOT click the \"Send Message\" button again!");
}
</SCRIPT>
<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH=500 ALIGN=CENTER><TR><TD VALIGN=TOP>
<FORM ACTION="mail_admin.php" METHOD="post">
<H3>Update</H3>
<B>Join up welcome message:</B>
<P><TEXTAREA COLS="50" ROWS="10" WRAP="virtual" NAME="subscribemail_a" CLASS="t">'.$subscribemail.'</TEXTAREA>
<BR>Your email signature will appear automatically at the foot of your message.
<P><INPUT TYPE="submit" NAME="send" VALUE="Update Mailing List" CLASS="s">
<P>&nbsp;
<H3>Mail to Subscribers</H3>
<P><B>Number of subscribers:</B> <SPAN STYLE="color:red">'.$countl.'</SPAN> <A HREF="mail_admin.php?action=subscribers">View subscribers</A>
<P><B>Subscriber list:</B>
<BR>Select "Test Mailout" to send message only to the admin email address.
<BR>Only lists highlighted in red need to be sent.
<BR><SELECT NAME="listoption" SIZE="1">
<OPTION VALUE="00">Test Mailout
<OPTION VALUE="01" '.$ca.'>1. Subscriber 1 - 200
<OPTION VALUE="02" '.$cb.'>2. Subscriber 201 - 400
<OPTION VALUE="03" '.$cc.'>3. Subscriber 401 - 600
<OPTION VALUE="04" '.$cd.'>4. Subscriber 601 - 800
<OPTION VALUE="05" '.$ce.'>5. Subscriber 801 - 1000
<OPTION VALUE="06" '.$cf.'>6. Subscriber 1001 - 1200
<OPTION VALUE="07" '.$cg.'>7. Subscriber 1201 - 1400
<OPTION VALUE="08" '.$ch.'>8. Subscriber 1401 - 1600
<OPTION VALUE="09" '.$ci.'>9. Subscriber 1601 - 1800
<OPTION VALUE="10" '.$ck.'>9. Subscriber 1801 - 2000
</SELECT>
<P><B>Send message to subscribers:</B>
<P><TEXTAREA COLS="50" ROWS="20" WRAP="virtual" NAME="message_a" CLASS="t">'.$message.'</TEXTAREA>
<BR>Your email signature &amp; unsubscribe link will appear automatically at the foot of your message.
<P><INPUT TYPE="submit" NAME="send" VALUE="Send Message" CLASS="s" ONCLICK="wait();">
</FORM></TD></TR></TABLE><P>&nbsp;<P>&nbsp;'.$bottom);
exit;
}
}


if ($send == "Send Message") {

$header2 = "From: $emailto\r\n";
$header2 .= "Return-Path: $emailto\r\n";
$header2 .= "Reply-To: $emailto\r\n";
$header2 .= "Content-type: text;\r\n";
$header2 .= "Mime-Version: 1.0\r\n";
$header2 .= "X-Mailer: php Mailer\r\n";

if ($listoption == "02") { $row = 200; }
else if ($listoption == "03") { $row = 400; }
else if ($listoption == "04") { $row = 600; }
else if ($listoption == "05") { $row = 800; }
else if ($listoption == "06") { $row = 1000; }
else if ($listoption == "07") { $row = 1200; }
else if ($listoption == "08") { $row = 1400; }
else if ($listoption == "09") { $row = 1600; }
else if ($listoption == "10") { $row = 1800; }
else { $row = 0; }



if ($listoption != "00") {
$filelocation="subscribers.txt";

$newfile = fopen($filelocation,"r");
$content = @fread($newfile, filesize($filelocation));
fclose($newfile);
$lines = explode("%",$content);

for ($i = $row; $i < ($row + 200); $i++) {
$l = $lines [$i];
$message="".stripslashes($message);
$company=stripslashes($company);

if ($l != "") {
mail ($l, $company." Mailing List", $message_a."\n\n".$signature."\n\nUnsubscribe:\nhttp://".$url."/web/mail.php", $header2);
}
}
echo $messagesent;
exit;
}
else {
mail ($adminemail, $company." Mailing List", $message_a."\n\n".$signature."\n\nUnsubscribe:\nhttp://".$url."/web/mail.php", $header2);
echo $messagesent;
exit;
}
}
if ($action == "admin") {
print($top.'
<P>&nbsp;
<P>Enter admin password:
<P><FORM ACTION="mail_admin.php" METHOD="POST">
<INPUT TYPE="hidden" NAME="action" VALUE="admin">
<INPUT TYPE="password" NAME="password" SIZE="15" CLASS="t">
<INPUT TYPE="submit" VALUE="Submit" CLASS="s">
</FORM>
'.$bottom); exit;
}
?>
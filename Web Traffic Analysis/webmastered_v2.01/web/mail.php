<?php
ignore_user_abort(true);
ini_set("max_execution_time",0);

include "var_ml.php";
include "var_user.php";

$filelocation = "subscribers.txt";
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

$top = '<HTML>
<HEAD>
<TITLE>'.$company.' Mailing List</TITLE>
'.$css.'
</HEAD>
<BODY>
<DIV ALIGN="'.$align.'">'.$addlogo.'</DIV>
<H3 ALIGN=CENTER>Mailing List</H3><P>&nbsp;<CENTER>
<TABLE ALIGN=CENTER CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH=550><TR><TD ALIGN=CENTER>';


$bottom = "<FORM><INPUT TYPE=\"button\" VALUE=\"Go Back\" CLASS=\"s\" ONCLICK=\"javascript:history.go(-1)\"></TD></TR></TABLE></BODY></HTML>";

$emailnot = $top."<H3>$email</H3>There seems to be a problem with your email address!".$bottom;
$noemail = $top."<H3>Sorry!</H3>You did not enter your email address!".$bottom;
$sorrysignmessage = $top."<H3>$email</H3>Your email address is already listed in the database!".$bottom;
$thankyou = $top."<H3>Thank You<BR>$email</H3>A confirmation email is on its way!".$bottom;
$unsubscribemessage = $top."<H3>$email</H3>You have been removed from the mailing list.".$bottom;
$failedunsubscriptionmessage = $top."<H3>$email</H3>You are not listed in the database!".$bottom;

if (!file_exists($filelocation)) {
$newfile = fopen($filelocation,"w+");
fclose($newfile);
}
$newfile = fopen($filelocation,"r");
$content = @fread($newfile, filesize($filelocation));
fclose($newfile);
$content=stripslashes($content);
$out="";
$lines = explode("%",$content);
foreach($lines as $l) {
if ($l != $email) { 
if ($l == "") { $out .= $l; }
else { $out .= "%".$l; }
}
else { $found=1; }
}

if ($action=="sign") {		
if ($email=="") { echo $noemail; exit; }
else if ($found==1) { echo $sorrysignmessage; exit; }
else if (!checkmail($email)) { echo $emailnot; exit; }
else {

$header2 = "From: $emailto\r\n"; 
$header2 .= "Return-Path: $emailto\r\n"; 
$header2 .= "Reply-To: $emailto\r\n"; 
$header2 .= "Content-type: text;\r\n"; 
$header2 .= "Mime-Version: 1.0\r\n"; 
$header2 .= "X-Mailer: php Mailer\r\n";

mail ($email, $company." Mailing List", $subscribemail."\n\n".$signature,$header2);
$newfile = fopen($filelocation,"a+");
$add = "%".$email;
fwrite($newfile, $add);
fclose($newfile);

$header1 = "From: $email\r\n"; 
$header1 .= "Return-Path: $email\r\n"; 
$header1 .= "Reply-To: $email\r\n"; 
$header1 .= "Content-type: text;\r\n"; 
$header1 .= "Mime-Version: 1.0\r\n"; 
$header1 .= "X-Mailer: php Mailer\r\n";

mail ($emailto, "New subscriber", "$email has subscribed to the mailing list.", $header1);
echo $thankyou;
exit;
}
}
if ($action=="delete") {	
if ($email=="") { echo $noemail; exit; }
else if (!checkmail($email)) { echo $emailnot; exit; }
else if ($found != 1) { echo $failedunsubscriptionmessage; exit; }
else {
$newfile = fopen($filelocation,"w+");
fwrite($newfile, $out);
fclose($newfile);

$header1 = "From: $email\r\n"; 
$header1 .= "Return-Path: $email\r\n"; 
$header1 .= "Reply-To: $email\r\n"; 
$header1 .= "Content-type: text;\r\n"; 
$header1 .= "Mime-Version: 1.0\r\n"; 
$header1 .= "X-Mailer: php Mailer\r\n";

mail ($emailto,"List unsubscribe.","$email has unsubscribed",$header1);
echo $unsubscribemessage;
exit;
}
}


function checkmail($string){
return preg_match("/^[^\s()<>@,;:\"\/\[\]?=]+@\w[\w-]*(\.\w[\w-]*)*\.[a-z]{2,}$/i",$string);
}

print($top.'Please enter your email address!<FORM ACTION="mail.php" METHOD="post">
<INPUT TYPE="text" NAME="email" SIZE="25" CLASS="t">
<INPUT TYPE="submit" VALUE="Join List" CLASS="s">
<BR><INPUT TYPE="radio" NAME="action" VALUE="sign" CHECKED>Subscribe
<INPUT TYPE="radio" NAME="action" VALUE="delete">Unsubscribe
</FORM>
<P>&nbsp;<P>Sponsored link: <A HREF="http://www.robtognoni.com" TARGET="_blank">Rob Tognoni Power Blues Rock</A>
</TD></TR></TABLE></BODY></HTML>');
exit;
?>
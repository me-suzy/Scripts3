<?php
include "var_user.php";

// ---------------------------------------------------------------------------------------

if($pass == $pw) {

if($rstats == "clear") {
$filelocation = "r1.txt";
$newf = fopen($filelocation,"w+");
$add = "";
fwrite($newf, $add);
fclose($newf);
$filelocation = "r2.txt";
$newf = fopen($filelocation,"w+");
$add = '<P>&nbsp;<P ALIGN=CENTER>Date Initiated: <B STYLE="font-weight:bold;color:red">'.date("D d F", strtotime("$zone hours")).'</B>';
fwrite($newf, $add);
fclose($newf);
header ("Location: pass.php?rd=40");
exit;
}

$file = 'r1.txt';
$stats=Array();
$handle = @fopen($file, "r");
$text = fread($handle, filesize($file));
fclose($handle);
$words = explode("*", $text);
$stats=array_count_values($words);

if($keyword == "") $keyword = "enter keyword";

$search_result = substr_count($text, $keyword);

$table='<P><TABLE CELLPADDING=3 CELLSPACING=0 WIDTH=550 BORDER=1 BORDERCOLOR="#CCCCCC">';

foreach($stats as $name => $val)
{
if($name=='') continue;

$shorter = substr_replace($name, '...', 50);
$shorter = str_replace("http://", "", $shorter);
if(preg_match("/$keyword/", $name)) $back='STYLE="background:yellow"';
$table.='<TR><TD WIDTH="35%" '.$back.'><A HREF="'.$name.'" TARGET="_BLANK">'.$shorter.'</A></TD><TD WIDTH="5%" '.$back.'>'.$val.'</TD><TD STYLE="background:#FFFFEF"><IMG SRC="graph.gif" HEIGHT=5 WIDTH='.$val.' ALIGN=ABSMIDDLE></TD></TR>';
if(preg_match("/$keyword/", $name)) $back='';
}
$table.='</TABLE>';

print '<HTML>
<HEAD>
<SCRIPT LANGUAGE="javascript">
<!--
function clear() {
itn=confirm("You about to clear this stats page and re-initiate the date.\nClick \'OK\' to continue.");
if(itn == true) { location="referral.php?rstats=clear&pass='.$pw.'"; }
}
// -->
</SCRIPT>
<STYLE>
BODY { font:11px arial;background:#EEEEEE }
TD { font:11px arial;background:white }
a:link, a:visited { color:blue;text-decoration:none }
a:hover, a:active { color:red;text-decoration:underline }
.s { background:#EEEEFE;font:10px verdana;border:1px #000000 solid }
.t { border:1px #000000 solid }
.iv { background:#FFFFEF }
</STYLE>
</HEAD>
<BODY>
<CENTER>
<H3>Referral Stats</H3>
<BR><TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH=550 ALIGN=CENTER><TR><TD STYLE="background:#EEEEEE">
&nbsp;<A HREF="pass.php?rd=10&row=0">Website Stats</A>
| <A HREF="pass.php?rd=30">Page Views</A>
| <A HREF="pass.php?rd=40">Referrals</A>
</TD><TD STYLE="background:#EEEEEE" ALIGN=RIGHT>
<A HREF="javascript:clear();">Clear Stats - Initiate Date</A>
</TD></TR></TABLE>
<P><FORM ACTION="pass.php">
<INPUT TYPE="hidden" NAME="rd" VALUE="50" CLASS="t">
<INPUT TYPE="text" NAME="keyword" VALUE="'.$keyword.'" CLASS="t">
<INPUT TYPE="submit" VALUE="Search" CLASS="s">
</FORM>
';

if($keyword != "enter keyword") {
print '<P>RESULT - Keyword: <SPAN STYLE="color:blue;background:yellow">'.$keyword.'</SPAN> | Count: <SPAN STYLE="font-weight:bold;color:red">'.$search_result.'</SPAN>';
}
else {
print '<A HREF="javascript:alert(\'The case sensitive keyword search will count and highlight \nthe number of times a certain word appears within your \nreferral URLs.\n\nFor example: Type the word google and it will highlight and \ncount how many google searchs in total your site has been \nreferred from.\nYou may also use a phrase for your keyword.\')">Keyword search information</A>'; 
}

include 'r2.txt';
print $table.'</CENTER></BODY></HTML>';
}

// ---------------------------------------------------------------------------------------

else print('Unauthorised access');

?> 

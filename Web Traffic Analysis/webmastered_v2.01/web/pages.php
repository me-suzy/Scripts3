<?php
include "var_user.php";

// ---------------------------------------------------------------------------------------

if($pass == $pw) {

if($pstats == "clear") {
$filelocation = "p1.txt";
$newf = fopen($filelocation,"w+");
$add = "";
fwrite($newf, $add);
fclose($newf);
$filelocation = "p2.txt";
$newf = fopen($filelocation,"w+");
$add = '<P>&nbsp;<P ALIGN=CENTER>Date Initiated: <B STYLE="font-weight:bold;color:red">'.date("D d F", strtotime("$zone hours")).'</B>';
fwrite($newf, $add);
fclose($newf);
header ("Location: pass.php?rd=30");
exit;
}

$file = 'p1.txt';
$stats=Array();
$handle = @fopen($file, "r");
$text = fread($handle, filesize($file));
fclose($handle);
$words = explode("*", $text);
$stats=array_count_values($words);

$table='<P><TABLE CELLPADDING=3 CELLSPACING=0 WIDTH=550 BORDER=1 BORDERCOLOR="#CCCCCC">';

foreach($stats as $name => $val)
{
if($name=='') continue;
$table.='<TR><TD WIDTH="17%">'.$name.'</TD><TD WIDTH="6%">'.$val.'</TD><TD STYLE="background:#FFFFEF"><IMG SRC="graph.gif" HEIGHT=5 WIDTH='.$val.' ALIGN=ABSMIDDLE></TD></TR>';
}
$table.='</TABLE>';

print '<HTML>
<HEAD>
<SCRIPT LANGUAGE="javascript">
<!--
function clear() {
itn=confirm("You about to clear this stats page and re-initiate the date.\nClick \'OK\' to continue.");
if(itn == true) { location="pages.php?pstats=clear&pass='.$pw.'"; }
}
// -->
</SCRIPT>
<STYLE>
BODY { font:11px arial;background:#EEEEEE }
TD { font:11px arial;background:white }
a:link, a:visited { color:blue;text-decoration:none }
a:hover, a:active { color:red;text-decoration:underline }
.iv { background:#FFFFEF }
</STYLE>
</HEAD>
<BODY>
<CENTER>
<H3>Page Views Stats</H3>
<BR><TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH=550 ALIGN=CENTER><TR><TD STYLE="background:#EEEEEE">
&nbsp;<A HREF="pass.php?rd=10&row=0">Website Stats</A>
| <A HREF="pass.php?rd=30">Page Views</A>
| <A HREF="pass.php?rd=40">Referrals</A>
</TD><TD STYLE="background:#EEEEEE" ALIGN=RIGHT>
<A HREF="javascript:clear();">Clear Stats - Initiate Date</A>
</TD></TR></TABLE>
';

include 'p2.txt';

print $table.'</CENTER></BODY></HTML>';
}

// ---------------------------------------------------------------------------------------

else print('Unauthorised access');

?> 

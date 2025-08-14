<?PHP

include "var_user.php";



$top = '<HTML><HEAD><STYLE>
BODY { font:11px arial;background:#EEEEEE }
TD { font:11px arial;background:white }
a:link, a:visited { color:blue;text-decoration:none }
a:hover, a:active { color:red;text-decoration:underline }
.s { background:#EEEEFE;font:10px verdana;border:1px #000000 solid }
.t { border:1px #000000 solid }
.iv { background:#FFFFEF }
</STYLE></HEAD><BODY><TABLE WIDTH="100%" HEIGHT="100%" CELLPADDING=0 CELLSPACING=0 BORDER=0>
<TR><TD ALIGN=CENTER VALIGN=MIDDLE STYLE="background:#EEEEEE">
';

$bottom = '</TD></TR></TABLE></BODY></HTML>';


$day = date("D", strtotime("$zone hours"));
$date1 = date("D d.m", strtotime("$zone hours"));
$date2 = date("D d.m", strtotime("$zone2 hours"));
$date3 = date("D d.m", strtotime("$zone3 hours"));
$date4 = date("D d.m", strtotime("$zone4 hours"));
$date5 = date("D d.m", strtotime("$zone5 hours"));
$date6 = date("D d.m", strtotime("$zone6 hours"));
$date7 = date("D d.m", strtotime("$zone7 hours"));

$filel = "stats.txt";
$newf = fopen($filel,"r");
$files = file ($filel);
$cont = fread($newf, filesize($filel));
fclose($newf);
$co = count($files);


if($detail == "overall") {
$filelocation = "stats.txt";
$fileName = file ($filelocation);

echo $top.
$fileName[$number].
$bottom;
exit;
}



$nu = 1;
$no = 2;


if($detail == "views") {
$countlines  = substr_count($cont, $date1);
$countlines2 = substr_count($cont, $date2);
$countlines3 = substr_count($cont, $date3);
$countlines4 = substr_count($cont, $date4);
$countlines5 = substr_count($cont, $date5);
$countlines6 = substr_count($cont, $date6);
$countlines7 = substr_count($cont, $date7);

print $top.
'<TABLE CELLPADDING=2 ALIGN=CENTER CELLSPACING=0 WIDTH=500 BORDER=1 BORDERCOLOR="#CCCCCC">
<TR><TD COLSPAN=3 ALIGN=CENTER STYLE="background:#EEEEEE"><B>7 Day Visits</B></TD></TR>

<TR><TD WIDTH="15%">'.$date1.'</TD><TD WIDTH="6%">'.$countlines.'</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($countlines*$no).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>'.$date2.'</TD><TD>'.$countlines2.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($countlines2*$no).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>'.$date3.'</TD><TD>'.$countlines3.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($countlines3*$no).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>'.$date4.'</TD><TD>'.$countlines4.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($countlines4*$no).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>'.$date5.'</TD><TD>'.$countlines5.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($countlines5*$no).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>'.$date6.'</TD><TD>'.$countlines6.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($countlines6*$no).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>'.$date7.'</TD><TD>'.$countlines7.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($countlines7*$no).' ALIGN=ABSMIDDLE>
</TD></TR>

</TABLE>'.
$bottom;
exit;
}

if($detail == "system") {
$count_1  = substr_count($cont, "indows");
$count_2 = substr_count($cont, "acintosh");
$count_3 = ($co-($count_1+$count_2));

print $top.
'<TABLE CELLPADDING=2 ALIGN=CENTER CELLSPACING=0 WIDTH=500 BORDER=1 BORDERCOLOR="#CCCCCC">
<TR><TD COLSPAN=3 ALIGN=CENTER STYLE="background:#EEEEEE"><B>Operating System</B></TD></TR>
<TR><TD WIDTH="15%">Windows</TD><TD WIDTH="6%">'.$count_1.'</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_1*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>Macintosh</TD><TD>'.$count_2.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_2*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>Other</TD><TD>'.$count_3.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_3*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

</TABLE>'.
$bottom;
exit;
}

if($detail == "screen") {
$count_1a  = substr_count($cont, "640 x 480");
$count_1  = substr_count($cont, "800 x 600");
$count_2 = substr_count($cont, "1024 x 768");
$count_3 = substr_count($cont, "1152 x 864");
$count_4a = substr_count($cont, "1200 x 900");
$count_4 = substr_count($cont, "1280 x 720");
$count_5 = substr_count($cont, "1280 x 768");
$count_6 = substr_count($cont, "1280 x 800");
$count_7a = substr_count($cont, "1280 x 854");
$count_7 = substr_count($cont, "1280 x 960");
$count_8 = substr_count($cont, "1280 x 1024");
$count_9 = substr_count($cont, "1400 x 1050");
$count_10 = substr_count($cont, "1440 x 900");
$count_11 = substr_count($cont, "1600 x 1200");
$count_12 = substr_count($cont, "1680 x 1050");
$count_13 = ($co-($count_1a+$count_1+$count_2+$count_3+$count_4+$count_4a+$count_5+$count_6+$count_7a+$count_7+$count_8+$count_9+$count_10+$count_11+$count_12));

print $top.
'<TABLE CELLPADDING=2 ALIGN=CENTER CELLSPACING=0 WIDTH=500 BORDER=1 BORDERCOLOR="#CCCCCC">
<TR><TD COLSPAN=3 ALIGN=CENTER STYLE="background:#EEEEEE"><B>Screen Size</B></TD></TR>

<TR><TD WIDTH="15%">640 x 480</TD><TD WIDTH="6%">'.$count_1a.'</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_1a*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD WIDTH="15%">800 x 600</TD><TD WIDTH="6%">'.$count_1.'</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_1*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>1024 x 768</TD><TD>'.$count_2.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_2*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>1152 x 864</TD><TD>'.$count_3.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_3*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>1200 x 900</TD><TD>'.$count_4a.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_4a*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>1280 x 720</TD><TD>'.$count_4.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_4*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>1280 x 768</TD><TD>'.$count_5.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_5*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>1280 x 800</TD><TD>'.$count_6.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_6*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>1280 x 854</TD><TD>'.$count_7a.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_7a*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>1280 x 960</TD><TD>'.$count_7.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_7*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>1280 x 1024</TD><TD>'.$count_8.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_8*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>1400 x 1050</TD><TD>'.$count_9.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_9*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>1440 x 900</TD><TD>'.$count_10.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_10*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>


<TR><TD>1600 x 1200</TD><TD>'.$count_11.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_11*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>1680 x 1050</TD><TD>'.$count_12.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_12*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>Undefined</TD><TD>'.$count_13.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_13*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

</TABLE>'.
$bottom;
exit;
}

if($detail == "browser") {
$count_1 = substr_count($cont, "MSIE 5.0;");
$count_2a = substr_count($cont, "MSIE 5.01;");
$count_2 = substr_count($cont, "MSIE 5.5;");
$count_3 = substr_count($cont, "MSIE 6.0;");
$count_4a = substr_count($cont, "5.0 (X11;");
$count_4b = substr_count($cont, "5.0 (Windows; ");
$count_4 = ($count_4a+$count_4b);
$count_5 = substr_count($cont, "Safari");
$count_6  = ($co-($count_1+$count_2a+$count_2+$count_3+$count_4+$count_5));

print $top.
'<TABLE CELLPADDING=2 ALIGN=CENTER CELLSPACING=0 WIDTH=500 BORDER=1 BORDERCOLOR="#CCCCCC">
<TR><TD COLSPAN=3 ALIGN=CENTER STYLE="background:#EEEEEE"><B>Browser</B></TD></TR>

<TR><TD WIDTH="15%">Explorer 6.0</TD><TD WIDTH="6%">'.$count_3.'</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_3*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD WIDTH="15%">Explorer 5.5</TD><TD WIDTH="6%">'.$count_2.'</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_2*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD WIDTH="15%">Explorer 5.01</TD><TD WIDTH="6%">'.$count_2a.'</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_2a*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD WIDTH="15%">Explorer 5.0</TD><TD WIDTH="6%">'.$count_1.'</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_1*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD STYLE="background:yellow">Netscape</TD><TD>'.$count_4.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_4*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD STYLE="background:#BBDEF8">Mac Safari</TD><TD>'.$count_5.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_5*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD STYLE="background:#A3FCB2">Undefined</TD><TD WIDTH="6%">'.$count_6.'</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_6*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

</TABLE>'.
$bottom;
exit;
}


if($detail == "user") {
$count_1a  = substr_count($cont, "5.0 (Windows; ");
$count_1b  = substr_count($cont, "Windows NT 5.1");
$count_1 = ($count_1a+$count_1b);
$count_2a = substr_count($cont, "Windows NT 5.0");
$count_2b = substr_count($cont, "Windows ME");
$count_2c = substr_count($cont, "Windows NT 4.0");
$count_2 = substr_count($cont, "Windows 98");
$count_3a = substr_count($cont, "Windows 95");
$count_4 = substr_count($cont, "AppleWebKit");
$count_3 = ($co-($count_1+$count_2a+$count_2b+$count_2c+$count_2+$count_3a+$count_4));

print $top.
'<TABLE CELLPADDING=2 ALIGN=CENTER CELLSPACING=0 WIDTH=500 BORDER=1 BORDERCOLOR="#CCCCCC">
<TR><TD COLSPAN=3 ALIGN=CENTER STYLE="background:#EEEEEE"><B>User Platform</B></TD></TR>

<TR><TD WIDTH="15%">Win XP</TD><TD WIDTH="6%">'.$count_1.'</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_1*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>


<TR><TD>Win 2000</TD><TD>'.$count_2a.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_2a*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>Win NT</TD><TD>'.$count_2c.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_2c*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>Win ME</TD><TD>'.$count_2b.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_2b*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>Win 98</TD><TD>'.$count_2.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_2*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>Win 95</TD><TD>'.$count_3a.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_3a*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>Mac Apple</TD><TD>'.$count_4.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_4*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

<TR><TD>Undefined</TD><TD>'.$count_3.'&nbsp;</TD><TD CLASS="iv">
<IMG SRC="graph.gif" HEIGHT=5 WIDTH='.($count_3*$nu).' ALIGN=ABSMIDDLE>
</TD></TR>

</TABLE>'.
$bottom;
exit;
}

?>
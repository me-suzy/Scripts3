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
function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
    }
$time_start = getmicrotime();
include("style.php");
include("config.php");
include("language.php");

if((isset($QUERY_STRING)) && ($QUERY_STRING < 1))
{
$QUERY_STRING = 1;
}
if ((!(file_exists("db/users.db"))) || (!(file_exists("db/banner.db"))))
{
Print("<center><h3><b>$ppn_notice[File_Error] users.db & banner.db</b></h3></center>");
exit();
}
$userz = file("db/users.db");
for ($index = 0; $index < count($userz); $index++)
{
$line = explode("|",$userz[$index]);
if ($line[11]) {
if ($line[6]) { $divid = "1"; }
if ($line[7]) { $divid = "2"; }
if ($line[8]) { $divid = "3"; }
if ($line[9]) { $divid = "4"; }
if ($line[10]) { $divid = "5"; }
if ($divid) {
$line[0] = round(($line[10] + $line[9] + $line[8] + $line[7] + $line[6]) /$divid);
         if (strlen($line[0]) == 1) { $count = "000000" . $line[0]; }
         if (strlen($line[0]) == 2) { $count = "00000" . $line[0]; }
         if (strlen($line[0]) == 3) { $count = "0000" . $line[0]; }
         if (strlen($line[0]) == 4) { $count = "000" . $line[0]; }
         if (strlen($line[0]) == 5) { $count = "00" . $line[0]; }
         if (strlen($line[0]) == 6) { $count = "0" . $line[0]; }
$user[$index] = "$count|$line[1]|$line[2]|$line[3]|$line[4]|$line[5]|$line[6]|$line[7]|$line[8]|$line[9]|$line[10]|$line[11]|$line[12]";
}
$user[$index] = "$line[0]|$line[1]|$line[2]|$line[3]|$line[4]|$line[5]|$line[6]|$line[7]|$line[8]|$line[9]|$line[10]|$line[11]|$line[12]";
}
}
rsort($user);
$fp = fopen("db/users.db","w");
flock($fp,2);
for($i = 0; $i < count($user); $i++)
{
fputs($fp, $user[$i]);
}
flock($fp,1);

$fp2 = fopen("db/banner.db","w");
flock($fp2,2);
for ($j = 0; $j < count($user); $j++)
{
$banner = explode("|",$user[$j]);
$pos = $j + 1;
$put = "$banner[11]|$pos|0\n";
fputs($fp2, $put);
}
flock($fp2,1);

/* Start Outputting Table */


$totalsites = count($user);

$nav = "<FORM NAME=\"frmLinks\"><SELECT NAME=\"selectLnk\">";
$numpages = $totalsites / $trank;
$numpages2 = round($numpages);

if ($numpages2 < $numpages)
{
$numpages = $numpages2 + 1;
}
$temprank = 0;

for ($b = 0; $b < $numpages; $b++)
{
$temprank2 = $temprank + 1;
$temprank = $temprank + $trank;
if ($b == 0)
{
$nav .= "<option value=\"index.php\">1 - $temprank";
}
else
{
$nav .= "<option value=\"index.php?$temprank2\">$temprank2 - $temprank";
}
}

$start = <<< START
<center>
 <br>
 <font color="silver">
 <TABLE WIDTH="$tablewidth" BORDER=1 CELLSPACING=0 CELLPADDING=5 BGCOLOR="$tablebg"><TR bgcolor="$tablehbg" align="center">
 <TD><font face="$headfont" color="$fonthcolour" size=1><B>$ppn_notice[rank]</B></font></td>
 <TD width="100%"><font face="$headfont" color="$fonthcolour" size=1><B>$ppn_notice[site_info]</B></font></td>
 <TD><font face="$headfont" color="$fonthcolour" size=1><B>$ppn_notice[average]</B></font></td>
 <TD><font face="$headfont" color="$fonthcolour" size=1><B>$ppn_notice[hits_today]</B></font></td></tr>
START;

Print("$top");
Print("$nav</SELECT><INPUT TYPE=\"BUTTON\" VALUE=\"$ppn_notice[go]\" onClick=\"location.href=frmLinks.selectLnk.options[frmLinks.selectLnk.options.selectedIndex].value\"></FORM>\n");
Print("<br>\n");
Print($start . "\n");

if(isset($QUERY_STRING))
{
$rank = $QUERY_STRING - 1;
$nrank = $rank + $trank;
$count = $QUERY_STRING;
} else {
$rank = 0 - 1;
$nrank = $rank + $trank;
$count = 0;
}
$buttonshow = 5;
for ($c = $rank; $c < $nrank; $c++)
{
if($c > -1)
{
if(isset($user[$c]))
{
$show = explode("|", $user[$c]);
$show[0] = round($show[0]);
if($count <= $buttonshow)
{
 Print("<tr align=center><td><font face=\"$font\" color=\"$fontcolour\" size=\"1\">$count</font></td><td><font face=\"$font\" color=\"$fontcolour\" size=\"1\">");
 Print("<a href=\"$show[1]\" target=\"_new\">$show[3]</a><br><a href=\"$show[1]\" target=\"_blank\"><img src=\"$show[2]\" Border=0 Width=$imagewidth Height=$imageheight></a><br>$show[4]</font></td>");
 Print("<td><font face=\"$font\" color=\"$fontcolour\" size=\"1\">$show[0]</font></td><td><font face=\"$font\" color=\"$fontcolour\" size=\"1\">$show[6]</font></td></tr>\n");
}
 else
{
 Print("<tr align=center><td><font face=\"$font\" color=\"$fontcolour\" size=\"1\">$count</font></td><td><font face=\"$font\" color=\"$fontcolour\" size=\"1\">");
 Print("<a href=\"$show[1]\" target=\"_new\">$show[3]</a><br>$show[4]</font></td>");
 Print("<td><font face=\"$font\" color=\"$fontcolour\" size=\"1\">$show[0]</font></td><td><font face=\"$font\" color=\"$fontcolour\" size=\"1\">$show[6]</font></td></tr>\n");
 }
 }
else
{
 Print("<tr align=center><td><font face=\"$font\" color=\"$fontcolour\" size=\"1\">$count</font></td><td><font face=\"$font\" color=\"$fontcolour\" size=\"1\">");
 Print("<a href=\"join.php\">***$ppn_notice[your_site]***</a></font></td>");
 Print("<td><font face=\"$font\" color=\"$fontcolour\" size=\"1\">0</font></td><td><font face=\"$font\" color=\"$fontcolour\" size=\"1\">0</font></td></tr>\n");
}
$count++;
}
else {
$count++;
$nrank = $nrank + 1;
}
}
$time_end = getmicrotime();
$time = $time_end - $time_start;
$time = round($time, 2);
if($time < 0)
{
$time = $time + 1;
}
Print("</table><br><center><font face=\"$font\" size=\"2\">$ppn_notice[time_generate1] $time $ppn_notice[time_generate2]<br><a href=\"http://software.pp-network.com\">PPN Topsites v1.0</a></font></center>");
Print("$footer");
Print("</html>");
exit();

?>
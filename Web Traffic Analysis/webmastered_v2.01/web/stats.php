<?PHP
include "var_user.php";

// ---------------------------------------------------------------------------------------

if($wstats == "clear") {
$filelocation = "stats.txt";
$newf = fopen($filelocation,"w+");
$add = "";
fwrite($newf, $add);
fclose($newf);
$filelocation = "stats2.txt";
$newf = fopen($filelocation,"w+");
$add = "";
fwrite($newf, $add);
fclose($newf);
$filelocation = "ip.txt";
$newf = fopen($filelocation,"w+");
$add = "";
fwrite($newf, $add);
fclose($newf);
$filelocation = "log.txt";
$newf = fopen($filelocation,"w+");
$add = "";
fwrite($newf, $add);
fclose($newf);
$filelocation = "counter.txt";
$newf = fopen($filelocation,"w+");
$add = "0";
fwrite($newf, $add);
fclose($newf);
$filelocation = "w2.txt";
$newf = fopen($filelocation,"w+");
$add = '<P ALIGN=CENTER>Date Initiated: <B STYLE="font-weight:bold;color:red">'.date("D d F", strtotime("$zone hours")).'</B>';
fwrite($newf, $add);
fclose($newf);
header ("Location: pass.php?rd=10");
exit;
}


if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]  !=  "") {
$rmt = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
$rff = @gethostbyaddr($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]);
}
else {
$rmt = $HTTP_SERVER_VARS["REMOTE_ADDR"];
$rff = @gethostbyaddr($HTTP_SERVER_VARS["REMOTE_ADDR"]);
}

// ---------------------------------------------------------------------------------------

$day = date("D", strtotime("$zone hours"));
$date = date("D d.m", strtotime("$zone hours"));
$date1 = date("D d.m", strtotime("$zone8 hours"));
$date2 = date("D d.m | h:i a", strtotime("$zone hours"));


// ---------------------------------------------------------------------------------------

$top = '<HTML>
<HEAD>
<SCRIPT LANGUAGE="javascript">
<!--
function reloadStats() {
location = "pass.php?rd=10&row=0";
}
function refreshStats() {
setTimeout("reloadStats()", 600000);
}
function clear() {
itn=confirm("You about to clear this stats page and re-initiate the date.\nClick \'OK\' to continue.");
if(itn == true) { location="stats.php?wstats=clear"; }
}
function pop_stat(url) {
target = "pop";w = 560;h = 250;winl = (screen.width - w) / 2;wint = (screen.height - h) / 2;
winprops = "height="+h+",width="+w+",top="+wint+",left="+winl+",scrollbars";
win = window.open(url, target, winprops);win.self.focus();
}
// -->
</SCRIPT>
<TITLE>Webmastered Stats v2.01</TITLE>
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
<BODY ONLOAD="refreshStats();">
<H3 ALIGN=CENTER>Webmastered Stats v2.01</H3>';

$bottom = '</BODY></HTML>';


// ---------------------------------------------------------------------------------------


if ($pass == $pw ) {

$filelocation = "stats.txt";
$newf = fopen($filelocation,"r");
$cont = fread($newf, filesize($filelocation));
fclose($newf);




print $top.'<BR><TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH=500 ALIGN=CENTER><TR><TD STYLE="background:#EEEEEE">
&nbsp;<A HREF="pass.php?rd=10&row=0">Website Stats</A>
| <A HREF="pass.php?rd=30">Page Views</A>
| <A HREF="pass.php?rd=40">Referrals</A>
</TD><TD STYLE="background:#EEEEEE" ALIGN=RIGHT>
<A HREF="javascript:clear();">Clear Stats - Initiate Date</A>
</TD></TR></TABLE>';

include 'w2.txt';

print '<BR><B>Total Unique Visitors:</B> ';

include 'counter.txt';

// ---------------------------------------------------------------------------------------

$filename = "log.txt";
$timer = 20;
if (!$datei) { $datei = dirname(__FILE__)."/$filename"; }

$timeout = time()-(60*$timer);
$all = "";
$i = 0;
$datei = file($filename);
for ($num = 0; $num < count($datei); $num++) {
$pieces = explode("|",$datei[$num]);
if ($pieces[1] > $timeout) {
$all .= $pieces[0];
$all .= ",";
}
$i++;
}


$dell = "";
for ($numm = 0; $numm < count($datei); $numm++) {
$tiles = explode("|",$datei[$numm]);
if ($tiles[1] > $timeout) {
$dell .= "$tiles[0]|$tiles[1]";
}
}
if (!$datei) { $datei = dirname(__FILE__)."/$filename"; }
$time = @time();
$ip = $rmt;
$string = "$dell";
$a = fopen("$filename", "w+");
fputs($a, $string);
fclose($a);

$cont = file($filename);
$count = count($cont);
if($count == 0) { $useronline = 0; }
else {
$all = substr($all,0,strlen($all)-1);
$arraypieces = explode(",",$all);
$useronline = count(array_flip(array_flip($arraypieces)));
}



// ---------------------------------------------------------------------------------------


print '<P ALIGN=CENTER>
<A HREF="javascript:pop_stat(\'detail.php?detail=views\');">7 Day Visits</A> |
<A HREF="javascript:pop_stat(\'detail.php?detail=system\');">Operating System</A> |
<A HREF="javascript:pop_stat(\'detail.php?detail=user\');">User Platform</A> |
<A HREF="javascript:pop_stat(\'detail.php?detail=browser\');">Browser</A> |
<A HREF="javascript:pop_stat(\'detail.php?detail=screen\');">Screen Size</A>';


// ---------------------------------------------------------------------------------------

$filel = "stats.txt";
$newf = fopen($filel,"r");
$cont = fread($newf, filesize($filel));
fclose($newf);
$countlines  = substr_count($cont, $date);

print '<P ALIGN=CENTER><B>Unique Visitors Today:</B> '.$countlines;

if($useronline == 0) { print '<BR>No visitors within the past 20 minutes.<P>'; }
else {
if($useronline != 1) { $plu = "s"; }
print '<BR>Currently '.$useronline.' visitor'.$plu.' online.<P>';
}


// ---------------------------------------------------------------------------------------


if($visitor != "detail" or $visitor == "") {
$filelocation = "stats2.txt";
$fileName = file ($filelocation);
$rows = count ($fileName);
}
else {
$filelocation = "stats.txt";
$fileName = file ($filelocation);
$rows = count ($fileName);
}

$table_top = '<P><TABLE WIDTH=500 ALIGN=CENTER CELLPADDING=3 CELLSPACING=0 BORDER=1 BORDERCOLOR="#CCCCCC">
<TR><TD COLSPAN=5 ALIGN=CENTER STYLE="background:#EEEEEE"><B>Brief Report</B></TD></TR>
<TR><TD WIDTH="20%"><B>Time</B></TD><TD WIDTH="30%"><B>Domain</B></TD><TD WIDTH="6%"><B>Views</B></TD><TD><B>Referrals</B></TD><TD WIDTH="6%"><B>Details</B></TD></TR>
';

if ($rows > 25) {

if (!isset ($row) ) { $row = 0; }

print '
<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH=500 ALIGN=CENTER><TR><TD WIDTH="50%" STYLE="background:#EEEEEE">';
if ($row > 0) { print '<A HREF="pass.php?rd='.$rd.'&row='.($row - 25).'">&lt;&lt; Go Back</A>'; }
print '</TD><TD WIDTH="50%" ALIGN=RIGHT STYLE="background:#EEEEEE">';
if ( ($rows - $row) > 25) { print '<A HREF="pass.php?rd='.$rd.'&row='.($row + 25).'">Next 25 &gt;&gt;</A>'; }
print '</TD></TR></TABLE>
';

print $table_top;

$filel = "stats.txt";
$fileN = file ($filel);
$newf = fopen($filel,"r");
$files = file ($filel);
$cont = fread($newf, filesize($filel));
fclose($newf);


for ($i = $row; $i < ($row + 25); $i++) {
if(!preg_match("/5.0 \(Windows; |MSIE 5.0|MSIE 5.5|MSIE 6.0/i", $fileN[$i])) { $msie_not = 'STYLE="background:#A3FCB2"'; }
if(preg_match("/5.0 \(Windows; /i", $fileN[$i])) { $msie_not = 'STYLE="background:yellow"'; }
if(preg_match("/Safari/i", $fileN[$i])) { $msie_not = 'STYLE="background:#BBDEF8"'; }
print $fileName[$i];
if($fileName[$i]) {
print '<TD ALIGN=CENTER '.$msie_not.'><A HREF="javascript:pop_stat(\'detail.php?detail=overall&number='.$i.'\');">view</A></TD></TR>'.chr(13).chr(10);
}
$msie_not = '';
}
print '</TABLE>
';

if (!isset ($row) ) { $row = 0; }
print '<P><TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0 WIDTH=500 ALIGN=CENTER><TR><TD WIDTH="50%" STYLE="background:#EEEEEE">';
if ($row > 0) { print '<A HREF="pass.php?rd='.$rd.'&row='.($row - 25).'">&lt;&lt; Go Back</A>'; }
print '</TD><TD WIDTH="50%" ALIGN=RIGHT STYLE="background:#EEEEEE">';
if ( ($rows - $row) > 25) { print '<A HREF="pass.php?rd='.$rd.'&row='.($row + 25).'">Next 25 &gt;&gt;</A>'; }
print '</TD></TR></TABLE>';
}




else {
print $table_top;

$filel = "stats.txt";
$fileN = file ($filel);
for ($i=0; $i < $rows; $i++) { 
if(!preg_match("/5.0 \(Windows; |MSIE 5.0|MSIE 5.5|MSIE 6.0/i", $fileN[$i])) { $msie_not = 'STYLE="background:#A3FCB2"'; }
if(preg_match("/5.0 \(Windows; /i", $fileN[$i])) { $msie_not = 'STYLE="background:yellow"'; }
if(preg_match("/Safari/i", $fileN[$i])) { $msie_not = 'STYLE="background:#BBDEF8"'; }
print $fileName[$i];
if($fileName[$i]) {
print '<TD ALIGN=CENTER '.$msie_not.'><A HREF="javascript:pop_stat(\'detail.php?detail=overall&number='.$i.'\');">view</A></TD></TR>'.chr(13).chr(10);
}
$msie_not = '';
}

print '</TABLE>
';
}


echo '<P>&nbsp;<P>&nbsp;'.$bottom;

exit;
}


// ---------------------------------------------------------------------------------------



if ($action == "admin") {

print $top.'<CENTER><P>&nbsp;<P>&nbsp;
Enter admin password:
<P><FORM ACTION="stats.php" METHOD="POST">
<INPUT TYPE="hidden" NAME="action" VALUE="admin">
<INPUT TYPE="hidden" NAME="rd" VALUE="10">
<INPUT TYPE="hidden" NAME="row" VALUE="0">
<INPUT TYPE="password" NAME="pass" SIZE="15" CLASS="t">
<INPUT TYPE="submit" VALUE="Submit" CLASS="s">
</FORM>
</CENTER>'.$bottom;
exit;
}



// ---------------------------------------------------------------------------------------



if ($action == "admin") {

print $top.'<CENTER><P>&nbsp;<P>&nbsp;
Enter admin password:
<P><FORM ACTION="stats.php" METHOD="POST">
<INPUT TYPE="hidden" NAME="action" VALUE="admin">
<INPUT TYPE="hidden" NAME="rd" VALUE="10">
<INPUT TYPE="password" NAME="pass" SIZE="15" CLASS="t">
<INPUT TYPE="submit" VALUE="Submit" CLASS="s">
</FORM>
</CENTER>'.$bottom;
exit;
}


// ---------------------------------------------------------------------------------------


else if ($action == "record") {

$f1 ="ip.txt";
$open = fopen($f1, "r");
$content = file ($f1);
if(preg_match("/$date1/", $content[0])) {
$newf = fopen($f1,"w+");
fwrite($newf, $date1);
fclose($newf);
}
$newfil = fopen($f1,"a+");
fwrite($newfil, "%".$rmt);
fclose($newfil);

$content = file("log.txt");
for($i = 0; $i < count($content); $i++) {
if(preg_match("/$rmt/", $content[$i])) {
$found = 1;
}
}

if($found != 1) {

$filelocation = "stats2.txt";
$fName = file ($filelocation);
$fga = count($fName);
for($i = 0; $i < $fga; $i++) {
if(preg_match("/$date1/", $fName[$i])) {
$file = $filelocation;
$file_data = file($file);
unset($file_data[$i]);
$file_pointer = fopen($file,'w');
$new_file_data = implode("",$file_data);
fwrite($file_pointer,$new_file_data);
fclose($file_pointer);
}
}


$filelocation = "stats.txt";
$fName = file ($filelocation);
$fga = count($fName);
for($i = 0; $i < $fga; $i++) {
if(preg_match("/$date1/", $fName[$i])) {
$file = $filelocation;
$file_data = file($file);
unset($file_data[$i]);
$file_pointer = fopen($file,'w');
$new_file_data = implode("",$file_data);
fwrite($file_pointer,$new_file_data);
fclose($file_pointer);
}
}
$filename = "log.txt";
$time = @time();
$string = "$rmt|$time\n";
$a = fopen("$filename", "a+");
fputs($a, $string);
fclose($a);


if($referrer == "") {
$shorter = ""; $refer = "";
}
else {
if(!preg_match("/$url|hidden|blocked/i", $referrer)) {
$refer = $referrer;
$shorter = substr_replace($refer, '...', 50);
}
else { $shorter = ""; $refer = ""; }
}
preg_match("/^(http:\/\/)?([^\/]+)/i",$rff, $matches);
if(preg_match("/[0-9]{1,3}\.[0-9]{1,3}/", $rff)) { $matches[0] = ""; }
else {

if(preg_match("/com.au|net.au|gov.au|co.uk|co.nz|net.br|com.br/",$rff)) {
$host = $matches[2];
preg_match("/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/", $host, $matches);
}
else {
$host = $matches[2];
preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
}
}


if($day == "Mon") { $colour = "#9AE5BC"; }
else if($day == "Tue") { $colour = "#F7CBA0"; }
else if($day == "Wed") { $colour = "#E2EEFF"; }
else if($day == "Thu") { $colour = "#FBD0FB"; }
else if($day == "Fri") { $colour = "#FFCCCC"; }
else if($day == "Sat") { $colour = "#EDE38B"; }
else if($day == "Sun") { $colour = "#EEFFE2"; }


$newRow = '<TABLE WIDTH=450 ALIGN=CENTER BORDER=0 CELLPADDING=3 CELLSPACING=2><TR><TD COLSPAN=2 STYLE="background:'.$colour.'"><B>'.$date2.
          '</B></TD></TR><TR><TD WIDTH="25%">IP Address</TD><TD><A HREF="http://www.checkdomain.com/cgi-bin/checkdomain.pl?domain='.$rmt.
          '" TARGET="_blank">'.$rmt.'</A></TD></TR><TR><TD>Domain</TD><TD>'.$matches[0].
          '</TD></TR><TR><TD>Browser - Platform</TD><TD STYLE="color:#CC0000">'.$browser.
          '</TD></TR><TR><TD>Screen Size</TD><TD>'.$res.'</TD></TR><TR><TD>Page Entry</TD><TD><SPAN STYLE="color:green">'.$rfe.
          '</SPAN></TD></TR><TR><TD>Page Views</TD><TD><IFRAME SRC="views.php?ip='.$rmt.
          '" frameborder=0 marginheight=0 marginwidth=0 scrolling=no height=12 width=25></IFRAME></TD></TR><TR><TD>Referral</TD><TD><A HREF="'.$refer.
          '" TARGET="_blank">'.$shorter.'</A></TD></TR></TABLE><P>';

  $filelocation = "stats.txt";
  $oldRows = join ('', file ($filelocation) );
  $fileName = fopen ($filelocation, 'w');
  fputs ($fileName, $newRow.chr(13).chr(10).$oldRows);
  fclose ($fileName);



$shorter2 = substr_replace($refer, '...', 25);
if($matches[0] == "") { 
if(!preg_match("/,/", $rmt)) { $matches[0] = $rmt; }
else { $matches[0] = "Multiple IP"; }
}
if($refer == "") { $refer = "&nbsp;"; }
if($shorter2 == "") { $shorter2 = "&nbsp;"; }

$newRow2 = '<!-- '.$date2.' --><?php $ipadd = "'.$rmt.'"; ?><TR><TD STYLE="background:'.$colour.'"><B>'.date("D | h:i a", strtotime("$zone hours")).
          '</B></TD><TD>'.$matches[0].'</TD><TD><IFRAME SRC="views.php?ip='.$rmt.
          '" frameborder=0 marginheight=0 marginwidth=0 scrolling=no height=12 width=25></IFRAME></TD><TD><A HREF="'.$refer.
          '" TARGET="_blank">'.$shorter2.'</A></TD>';


  $filelocation2 = "stats2.txt";
  $oldRows2 = join ('', file ($filelocation2) );
  $fileName2 = fopen ($filelocation2, 'w');
  fputs ($fileName2, $newRow2.chr(13).chr(10).$oldRows2);
  fclose ($fileName2);


// ---------------------------------------------------------------------------------------

$fi = "counter.txt";

$counthandle=fopen($fi,"r");
$getcurrent=fread($counthandle,filesize($fi));
$getcurrent=$getcurrent+1;
fclose($counthandle);


$counthandle1=fopen($fi,"w");
fputs($counthandle1,$getcurrent);
fclose($counthandle1);
}

$rfe = str_replace("http://".$url."/","index", $rfe);
$rfe = str_replace("http://".$url,"index", $rfe);

if($rfe != "") {
$filelocation = "p1.txt";
$newfil = fopen($filelocation,"a+");
fwrite($newfil, "*".$rfe);
fclose($newfil);
}

if($referrer != "") {
if(!preg_match("/$url|hidden|blocked/i", $referrer)) {

$filelocation = "r1.txt";
  $old = join ('', file ($filelocation) );
  $fileName = fopen ($filelocation, 'w');
  fputs ($fileName, '*'.$referrer.$old);
  fclose ($fileName);

}
}
exit;
}

// ---------------------------------------------------------------------------------------

else print 'Unauthorised access';


?>
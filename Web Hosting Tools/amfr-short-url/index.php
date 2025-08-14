<?php
include('config.php');
?>
<HTML>
<HEAD>
<TITLE>Short URL signup</TITLE>
</HEAD>
<BODY BGCOLOR="lightgrey" LINK="black" ALINK="black" VLINK="black">
<CENTER>
<?php
if($_POST['u1'] && $_POST['u2'] && $_POST['mask'] == "yes" && $_POST['p']) {
$u1 = $_POST['u1'];
$u2 = $_POST['u2'];
$u1 = str_replace('.', '', $u1);
$u1 = str_replace('/', '', $u1);
$title = $_POST['t'];
$pass = stripslashes(strip_tags($_POST['p']));
$format2 = <<<AMFRFTWO
<HTML>
<HEAD>
<TITLE>$title</TITLE>
</HEAD>
<FRAMESET ROWS=100%>
<FRAME SRC="$u2">
</FRAMESET>
</HTML>
AMFRFTWO;
if(file_exists($u1.'/index.php')) {
echo "Access name taken<BR>";
exit;
}
if($u1 == "admin")) {
echo "Access name taken<BR>";
exit;
}
mkdir($u1);
$file = fopen($u1.'/index.php', 'wb');
fwrite($file, $format2);
fclose($file);
$file = fopen($u1.'/config.php', 'wb');
fwrite($file, '<?php $username = "'.$u1.'"; $password = "'.$pass.'"; ?>');
fclose($file);
$file = fopen('users.txt', 'ab');
fwrite($file, $access.'.');
fclose($file);
echo "Done.<BR>";
echo "Your URL is: http://".$config['domain']."/$u1<BR>";
} else if ($_POST['u1'] && $_POST['u2'] && $_POST['mask'] == "no" && $_POST['p']) {
$u1 = $_POST['u1'];
$u2 = $_POST['u2'];
$u1 = str_replace('.', '', $u1);
$u1 = str_replace('/', '', $u1);
$pass = stripslashes(strip_tags($_POST['p']));
$dformat = <<<AMFRDFORMAT
<?php
header('Location: $u2');
?>
AMFRDFORMAT;
if(file_exists($u1.'/index.php')) {
echo "Access name taken<BR>";
exit;
}
if($u1 == "admin")) {
echo "Access name taken<BR>";
exit;
}
mkdir($u1);
$file = fopen($u1.'/index.php', 'wb');
fwrite($file, $dformat);
fclose($file);
$file = fopen($u1.'/config.php', 'wb');
fwrite($file, '<?php $username = "'.$u1.'"; $password = "'.$pass.'"; ?>');
fclose($file);
$file = fopen('users.txt', 'ab');
fwrite($file, $access.'.');
fclose($file);
echo "Done.<BR>";
echo "Your URL is: http://".$config['domain']."/$u1<BR>";
} else {
?>
<FORM ACTION="index.php" METHOD="post">
<H1>Create a short URL:</H1><BR><BR>
<TABLE>
<TR>
<TD>Access name (<?php echo $config['domain']; ?>/ACCESSNAME):</TD><TD><INPUT TYPE="text" NAME="u1"></TD>
</TR>
<TR>
<TD>Your site's URL:</TD><TD><INPUT TYPE="text" NAME="u2"></TD>
</TR>
<TR>
<TD>Mask URL:</TD><TD><SELECT NAME="mask">
<OPTION VALUE="yes">Yes</OPTION>
<OPTION VALUE="no">No</OPTION>
</SELECT></TD>
</TR>
<TR>
<TD>Title of site (only required if you want to mask the url):</TD><TD><INPUT TYPE="text" NAME="t"></TD>
</TR>
<TR>
<TD>Password:</TD><TD><INPUT TYPE="password" NAME="p"></TD>
</TR>
</TABLE>
<INPUT TYPE="submit" VALUE="Submit!"><BR>
Powered by: <A HREF="http://www.amfrservices.com">AMFR Short URL</A>
</FORM>
<?php
}
?>
<A HREF="admin.php">Administer your URL</A>
</CENTER>
</BODY>
</HTML>
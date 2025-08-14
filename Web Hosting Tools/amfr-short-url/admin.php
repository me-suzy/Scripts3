<html>
<head>
<title>Administer your short URL</title>
</head>
<body bgcolor="lightgrey" LINK="black" ALINK="black" VLINK="black">
<CENTER>
<H1>Admininster your short URL:</H1><BR>
<?php
@ $action = $_POST['a'];
@ $user = $_POST['u'];
@ $pass = $_POST['p'];
if($user) {
include($user.'/config.php');
}
if($user == $username && $pass == $password && $action == "cp1") {
echo <<<MIDHTML
Logged in as $user<BR><BR>
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="a" VALUE="cp2">
Change your password:<BR>
New password: <INPUT TYPE="password" NAME="newp"><BR>
<INPUT TYPE="hidden" NAME="u" VALUE="$username">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="submit" VALUE="Change Password!">
</FORM>
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="u" VALUE="$user">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="hidden" NAME="login" VALUE="true">
<INPUT TYPE="Submit" VALUE="Back to admin home">
</FORM>
MIDHTML;
}
elseif($user == $username && $pass == $password && $action == "cp2" && $_POST['newp']) {
$file = fopen($user.'/config.php', 'wb');
fwrite($file, '<?php $username = "'.$username.'"; $password = "'.stripslashes(strip_tags($_POST['newp'])).'"; ?>');
fclose($file);
$p2 = stripslashes(strip_tags($_POST['newp']));
echo <<<MIDHTML2
Logged in as $user<BR><BR>
Password changed.
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="u" VALUE="$user">
<INPUT TYPE="hidden" NAME="p" VALUE="$p2">
<INPUT TYPE="hidden" NAME="login" VALUE="true">
<INPUT TYPE="Submit" VALUE="Back to admin home">
</FORM>
MIDHTML2;
}
elseif($user == $username && $pass == $password && $action == "cp2" && !$_POST['newp']) {
echo <<<MIDHTML3
Logged in as $user<BR><BR>
Please enter a new password!<BR>
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="a" VALUE="cp2">
Change your password:<BR>
New password: <INPUT TYPE="password" NAME="newp"><BR>
<INPUT TYPE="hidden" NAME="u" VALUE="$username">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="submit" VALUE="Change Password!">
</FORM>
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="u" VALUE="$user">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="hidden" NAME="login" VALUE="true">
<INPUT TYPE="Submit" VALUE="Back to admin home">
</FORM>
MIDHTML3;
}
elseif($user == $username && $pass == $password && $action == "curl1") {
echo <<<MIDHTML4
Logged in as $user<BR><BR>
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="a" VALUE="curl2">
Change your URL:<BR>
<TABLE>
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
</TABLE>
<INPUT TYPE="hidden" NAME="u" VALUE="$username">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="submit" VALUE="Change URL!">
</FORM>
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="u" VALUE="$user">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="hidden" NAME="login" VALUE="true">
<INPUT TYPE="Submit" VALUE="Back to admin home">
</FORM>
MIDHTML4;
}
elseif($user == $username && $pass == $password && $action == "curl2" && $_POST['mask'] == "yes" && $_POST['u2']) {
@ unlink($username.'/index.php');
@ unlink($username.'/index.html');
$url = stripslashes(strip_tags($_POST['u2']));
$title = stripslashes(strip_tags($_POST['t']));
$format2 = "<HTML>
<HEAD>
<TITLE>$title</TITLE>
</HEAD>
<FRAMESET ROWS=100%>
<FRAME SRC=\"$url\">
</FRAMESET>
</HTML>";
$file = fopen($user.'/index.php', 'wb');
fwrite($file, $format2);
fclose($file);
echo <<<MIDHTML5
Logged in as $user<BR><BR>
URL changed.
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="u" VALUE="$user">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="hidden" NAME="login" VALUE="true">
<INPUT TYPE="Submit" VALUE="Back to admin home">
</FORM>
MIDHTML5;
}
elseif($user == $username && $pass == $password && $action == "curl2" && $_POST['mask'] == "no" && $_POST['u2']) {
@ unlink($username.'/index.php');
@ unlink($username.'/index.html');
$url = stripslashes(strip_tags($_POST['u2']));
$dformat = "<?php
header('Location: $url');
?>";
$file = fopen($user.'/index.php', 'wb');
fwrite($file, $dformat);
fclose($file);
echo <<<MIDHTML6
Logged in as $user<BR><BR>
URL changed.
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="u" VALUE="$user">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="hidden" NAME="login" VALUE="true">
<INPUT TYPE="Submit" VALUE="Back to admin home">
</FORM>
MIDHTML6;
}
elseif($user == $username && $pass == $password && $_POST['login']) {
echo <<<MIDHTML7
Logged in as $user<BR><BR>
Choose an action:<BR>
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="u" VALUE="$username">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="hidden" NAME="a" VALUE="cp1">
<INPUT TYPE="submit" VALUE="Change Password">
</FORM>
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="u" VALUE="$username">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="hidden" NAME="a" VALUE="curl1">
<INPUT TYPE="submit" VALUE="Change URL">
</FORM>
MIDHTML7;
}
elseif($user == $username && $pass == $password && $action == "curl2" && $_POST['mask'] == "no" && !$_POST['u2']) {
echo <<<MIDHTML8
Logged in as $user<BR><BR>
Please enter a url:<BR>
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="a" VALUE="curl2">
Change your URL:<BR>
<TABLE>
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
</TABLE>
<INPUT TYPE="hidden" NAME="u" VALUE="$username">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="submit" VALUE="Change URL!">
</FORM>
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="u" VALUE="$user">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="hidden" NAME="login" VALUE="true">
<INPUT TYPE="Submit" VALUE="Back to admin home">
</FORM>
MIDHTML8;
}
elseif($user == $username && $pass == $password && $action == "curl2" && $_POST['mask'] == "yes" && !$_POST['u2']) {
echo <<<MIDHTML9
Logged in as $user<BR><BR>
Please enter a url:<BR>
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="a" VALUE="curl2">
Change your URL:<BR>
<TABLE>
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
</TABLE>
<INPUT TYPE="hidden" NAME="u" VALUE="$username">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="submit" VALUE="Change URL!">
</FORM>
<FORM ACTION="admin.php" METHOD="post">
<INPUT TYPE="hidden" NAME="u" VALUE="$user">
<INPUT TYPE="hidden" NAME="p" VALUE="$password">
<INPUT TYPE="hidden" NAME="login" VALUE="true">
<INPUT TYPE="Submit" VALUE="Back to admin home">
</FORM>
MIDHTML9;
}
else {
echo <<<MIDHTML10
Please login:<BR>
<FORM ACTION="admin.php" METHOD="post">
Username (same as access name): <INPUT TYPE="text" NAME="u"><BR>
Password: <INPUT TYPE="password" NAME="p"><BR>
<INPUT TYPE="hidden" NAME="login" VALUE="true">
<INPUT TYPE="submit" VALUE="Login!">
</FORM>
MIDHTML10;
}
?>
<BR><BR>A service of <A HREF="http://www.amfrservices.com">AMFR Short URL</A>
</center>
</body>
</html>
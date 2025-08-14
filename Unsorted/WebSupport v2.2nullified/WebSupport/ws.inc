<?php
// +-----------------------------------------------------------------------+
// | WebSupport 2.2                                                        |
// +-----------------------------------------------------------------------+
// | Copyright (c) 2001 IRC-HTML  Productions                              |
// +-----------------------------------------------------------------------+
// | This source file is subject to the current license contained in       |
// | readme.txt that is bundled with this package.                         |
// | If you have not read the license, or do not agree to it please delete |
// | this software immediately. Use of the program constitutes agreement   |
// | with the license.                                                     |
// +-----------------------------------------------------------------------+
// | Author         : Kenneth Schwartz                                     |
// | Supplied by    : CyKuH [WTN]                                          |
// | Distribution   : via WebForum, ForumRU and associated file dumps      |
// |                      (c) WTN Team `2002                               |
// +-----------------------------------------------------------------------+
// $Id: ws.inc,v 2.2.3 2001/04/17 21:45:49 ks-wsdev Exp $
$script_url = 'http://www.youdomain.com/ws/index.php';
$admin_url = 'http://www.youdomain.com/ws/admin.php';
$image_url = 'http://www.youdomain.com/ws/ws.png';
$asc_url = 'http://www.youdomain.com/ws/asc.png';
$desc_url = 'http://www.youdomain.com/ws/desc.png';
$title = 'WebSupport Help Desk';
$notify_icq = 0;
$icq_num = array('000000000');
$notify = 0;
$notifywho = array('support@youdomain.com');
$lang_file = 'en.lang';
$enable_auth = 1;
$admin_pass = 'WTN';
/* ----------------- MySQL/mSQL Config --------------------- */
$mysqlusr = 'root';
$mysqlpass = '';
$dbname = 'ws';
$table = 'WebSupport';
$ws_mysql = @mysql_connect('localhost', $mysqlusr, $mysqlpass);
@mysql_select_db($dbname, $ws_mysql);
/* --------------------- Email Headers ------------------------- */
$headers = "From: $title <$notifywho[0]>\n";
$headers .= "X-Sender: <$notifywho[0]>\n"; 
$headers .= "X-Mailer: WebSupport 2.2\n";
$headers .= "X-Priority: 1\n";
$headers .= "Return-Path: $title <$notifywho[0]>\n";
$headers .= "Errors-To: $notifywho[0]";
/* ----------------- Lang File --------------------- */
require_once($lang_file);
/* ----------------- Sorting Config --------------------- */
$ascending = "<a href=\"$admin_url?sort=des\"><img src=\"$asc_url\" alt=\"$t_desc\" border=0></A>";
$descending = "<a href=\"$admin_url?sort=asc\"><img src=\"$desc_url\" alt=\"$t_asc\" border=0></A>";
$ascendingr = "<a href=\"$admin_url?form=resolved&amp;sort=des\"><img src=\"$asc_url\" alt=\"$t_desc\" border=0></A>";
$descendingr = "<a href=\"$admin_url?form=resolved&amp;sort=asc\"><img src=\"$desc_url\" alt=\"$t_asc\" border=0></A>";
/*---------------- MySQL Connection Check ------------------ */
if (!$ws_mysql) {
	error_page($t_noconnect);
}
/* --------------------- Functions ------------------------- */
function inheader($titleStr) {
    global $title;
    ?>
<html><head><title>WebSupport Help Desk</title></head>
<body bgcolor="#748FBA" text="#000000" link="#000080" vlink="#000080" alink="#000080">
<center>
<table width=600 cellpadding=2 cellspacing=0 border=0 bgcolor="#DEDCD5" height="300"><tr><td valign=top>
	<center>
	<table border=0 cellpadding=3 cellspacing=0 width=500>
	<tr>
	<td align=center><font size=2><b><?php print($title); ?> - <?php print($titleStr); ?></b></font></td>
	</tr>
	<tr><td>
	<?php
}
function adheader($titleStr, $width=0) {
	global $title;
	?>
	<html>
	<head>
	<title>WebSupport Admin - <?php print($titleStr); ?></title>
    <basefont face="Tahoma, Verdana, Arial" size=2>
    </head>
	<body bgcolor="#005A87" text="#000000" link="#305080" vlink="#305080" alink="#305080">
    <center>
	<table border=0 cellpadding=3 cellspacing=0<?php if ($width) { print(' width=500'); } ?>>
	<tr>
	<td align=center bgcolor="#305080"><font size=3 color="#FFFFFF"><b><?php print($title); ?> - <?php print($titleStr); ?></b></font></td>
	</tr>
	<tr><td bgcolor="#dadaea">
	<?php
}
function footer() {
	/* It is a violation of the license agreement located in readme.txt to remove the following link if you have not purchased a commercial license. */
	global $image_url;
	?>
	</td></tr>
	<tr>
	<td align=right><br><font face="Arial" size="1"><!--CyKuH [WTN]-->Powered by WebSupport</font></td>
	</tr>
	</table>
</body></html>
	<?php
}
function adfooter() {
	/* It is a violation of the license agreement located in readme.txt to remove the following link if you have not purchased a commercial license. */
	global $image_url, $admin_url;
	?>
    <br>
    <center><font size=2>[<a href="<?php print($admin_url); ?>">admin home</a>]   [<a href="<?php print($admin_url); ?>?form=logout">log out</a>]   [<a href="<?php print($admin_url); ?>?form=optimize">optimize database</a>]</font></center>
	</td></tr>
	<tr>
	<td align=center><br><!--CyKuH [WTN]--><img src="<?php print($image_url); ?>" border=0></td>
	</tr>
	</table>
	</center>
	</body>
	</html>
	<?php
}
function check_fields($name, $username, $domain, $email, $problem, $subject) {
	global $t_invalemail, $t_invalname, $t_invaluser, $t_invaldom, $t_invalprob, $t_invalsub;
	$message = '<br>';
	$errmsg = "<li>$t_invalemail<br>";
	if(!empty($email)) {
		if(!ereg("^([A-Za-z0-9_.-]([A-Za-z0-9_.-]{2,100}))@([A-Za-z0-9._-]([A-Za-z0-9._-]{2,100}))$", $email)) {
			$message = $message . $errmsg;
			$found_err = 1;
		}
	}
	else {
			$message = $message . $errmsg;
			$found_err = 1;
	}
	if(empty($name)) {
		$errmsg = "<li>$t_invalname<br>";
		$message = $message . $errmsg;
		$found_err = 1;
	}
	if (empty($username)) {
		$errmsg = "<li>$t_invaluser<br>";
		$message = $message.$errmsg;
		$found_err = 1; 
	}
	if (empty($domain)) {
		$errmsg = "<li>$t_invaldom<br>";
		$message = $message.$errmsg;
		$found_err = 1; 
	}
	if (empty($subject)) {
		$errmsg = "<li>$t_invalsub<br>";
		$message = $message.$errmsg;
		$found_err = 1; 
	}
	if (empty($problem)) {
		$errmsg = "<li>$t_invalprob<br>";
		$message = $message.$errmsg;
		$found_err = 1; 
	}
	if (isset($found_err)) {
		error_page($message); 
	}
	else {
	return 1;
	}
}
function error_page($message) {
	global $title, $image_url, $t_error, $t_correct;
	inheader($t_error);
	?>
	<blockquote>
	<font size=2>
	<br><p><?php print($t_correct); ?></p>
	<p><?php print($message); ?></p>
	</font>
	</blockquote>
	<?php
	footer();
	exit;
}
?>
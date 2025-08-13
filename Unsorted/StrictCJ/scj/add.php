<html>
<head><title>Strict-CJ - Site Submission</title>
</head>
<body bgcolor="#FFFFFF" test="#000000"><p align="center">
<?php
/***********************************************************
			set your variables
***********************************************************/

// Default ratio percent set to trade that signs up for a trade
$defaultratio=120;

// The return url you partners should send at 
// (make it ROOT domain where index.php is)
$site_url="http://www.yourdomain.com";

/***********************************************************
			End of variables
***********************************************************/
include("admin/variables.inc.php");
include($dbconnect);

function readBlacklist($blacklist) {
	if (!file_exists($blacklist.".lock")) touch($blacklist.".lock");
	if (!file_exists($blacklist)) touch($blacklist);
	$lfp = fopen ($blacklist.".lock","r");
	while (!flock($lfp,LOCK_SH));
	$domains = file($blacklist);
	flock($lfp,LOCK_UN);
	fclose($lfp);
	return $domains;
}

function isBlacklisted($domain) {
	Global $index_path;
	$blacklist = $index_path."/scj/data/blacklist.db";
	$domains = readBlacklist($blacklist);
	if (in_array($domain."\n",$domains)) return true;
	return false;
}

$err = 0;
if ((!$_POST['username']) || ($_POST['username'] == "user") || (eregi(' ',$_POST['username']))) {
  $err = 1;
  $error .= "You must enter a valid <b>username</b><BR>";
}
if (!$_POST['sitedomain'] || $_POST['sitedomain'] == "domain.com") {
  $err = 1;
  $error .= "You must enter a <b>Domain Name</b><BR>";
} elseif (count(explode('.',$_POST['sitedomain'])) > 2) {
  $err = 1;
  $error .= "Not a valid <b>domain</b> format<BR>";
} else if (!eregi($_POST['sitedomain'],$_POST['returnurl'])) {
  $err = 1;
  $error .= "<b>Domain</b> and <b>return url</b> do not match<BR>";
} elseif (isBlacklisted($_POST['sitedomain'])) {
  $err = 1;
  $error .= "Blacklisted <b>domain</b><BR>";
} else {
	$qry = "SELECT NOUSER FROM $tablename WHERE DOMAIN='".$_POST['sitedomain']."'";
	$result = mysql_query($qry);
	if (mysql_num_rows($result) > 0) {
		$err = 1;
		$error .= "That <b>domain</b> is already in our database<BR>";
	}
}
if (!$_POST['returnurl'] || $_POST['returnurl'] == "http://www.domain.com") {
  $err = 1;
  $error .= "You must enter an <b>URL</b><BR>";
} elseif (substr($_POST['returnurl'],0,7) <> "http://") {
  $err = 1;
  $error .= "You need <b>http://</b> in your <b>URL</b><BR>";
}
if (!$_POST['email'] || $_POST['email'] == "webmaster@domain.com") {
  $err = 1;
  $error .= "You must enter an <b>E-mail</b><BR>";
} elseif ((eregi("@",$_POST['email']) <> 1) or (eregi("\.",$_POST['email']) <> 1)) {
  $err = 1;
  $error .= "You must enter a valid <b>E-mail</b><BR>";
}
if ($err) {
  echo $error;
} else {
  $sql = "INSERT INTO $thour (USER) VALUES ('".$_POST['username']."')";
  mysql_query($sql) or die(mysql_error());
  $sql = "INSERT INTO $tday (USER) VALUES ('".$_POST['username']."')";
  mysql_query($sql) or die(mysql_error());
  $sql = "INSERT INTO $tablename (NOUSER,RATIO,DOMAIN,URL,EMAIL,ICQ,NICK,RAWIN,UNIQUEIN,HITSGEN,HITSOUT,FORCEDHITS) VALUES('".$_POST['username']."',".$defaultratio.",'".$_POST['sitedomain']."','".$_POST['returnurl']."','".$_POST['email']."',";
  if ($_POST['icq']) {
    $sql .= $_POST['icq'].",";
  } else {
    $sql .= "NULL,";
  }
  if ($_POST['nick']) {
    $sql .= "'".$_POST['nick']."',";
  } else {
    $sql .= "NULL,";
  }
  $sql .= "0,0,0,0,0)";
  mysql_query($sql) or die(mysql_error());
  echo "
<div align=\"center\"><table width=\"400\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td align=\"center\">
<font size=\"4\" face=\"Verdana\">Successfully added...</font><BR><BR>
<p align=\"center\"><font size=\"3\" face=\"verdana\">
<font color=\"red\">Username:</font> ".$_POST['username']."<BR>
<font color=\"red\">Domain:</font> ".$_POST['sitedomain']."<BR>
<font color=\"red\">URL:</font> ".$_POST['returnurl']."<BR>
<font color=\"red\">E-Mail:</font> ".$_POST['email']."<BR>
<font color=\"red\">UIN:</font> ".$_POST['icq']."<BR>
<font color=\"red\">Nickname:</font> ".$_POST['nick']."</font><BR><BR>
<p align=\"center\"><font size=\"4\" face=\"verdana\">Link to: <a href=\"$site_url\">$site_url</a></font></td></tr></table></div><BR>
";
}
?>
<p align="center"><font size="4" face="verdana">Powered by Strict-CJ<BR><a href="http://www.strict-cj.com">GET YOUR FREE COPY NOW!</a></font></p>
</p></body>
</html>
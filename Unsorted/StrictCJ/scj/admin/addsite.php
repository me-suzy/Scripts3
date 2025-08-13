<?php
include("variables.inc.php");
$err = 0;

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

if ((!$_POST['username']) || (eregi(' ',$_POST['username']))) {
  $err = 1;
  $error .= "Not a valid <b>username</b><BR>";
}
if (!$_POST['sitedomain']) {
  $err = 1;
  $error .= "You must enter a <b>Domain Name</b><BR>";
} elseif (count(explode('.',$_POST['sitedomain'])) > 3) {
  $err = 1;
  $error .= "Not a valid <b>domain</b> format<BR>";
} elseif (isBlacklisted($_POST['sitedomain'])) {
  $err = 1;
  $error .= "Blacklisted <b>domain</b><BR>";
}
if (!$_POST['returnurl']) {
  $err = 1;
  $error .= "You must enter an <b>URL</b><BR>";
} elseif (substr($_POST['returnurl'],0,7) <> "http://") {
  $err = 1;
  $error .= "You need <b>http://</b> in your <b>URL</b><BR>";
}
if ($_POST['email']) {
  if ((eregi("@",$_POST['email']) <> 1) or (eregi("\.",$_POST['email']) <> 1)) {
    $err = 1;
    $error .= "You must enter a valid <b>E-mail</b><BR>";
  }
}
if (!$_POST['ratio']) {
  $err = 1;
  $error .= "You must enter a <b>Ratio</b><BR>";
}
if ($err) {
  echo $error;
} else {
  include($dbconnect);
  $sql = "SELECT NOUSER FROM $tablename WHERE NOUSER='".$_POST['username']."'";
  $sth = mysql_query($sql);
  if (mysql_num_rows($sth) == 0) {
    $sql = "SELECT DOMAIN FROM $tablename WHERE DOMAIN='".$_POST['sitedomain']."'";
    $sth = mysql_query($sql);
    if (mysql_num_rows($sth) == 0) {
      $sql = "INSERT INTO $tablename (NOUSER,DOMAIN,URL,RATIO,EMAIL,ICQ,NICK,RAWIN,UNIQUEIN,HITSGEN,HITSOUT,FORCEDHITS) VALUES('".$_POST['username']."','".$_POST['sitedomain']."','".$_POST['returnurl']."',".$_POST['ratio'].",";
      if ($_POST['email']) {
        $sql .= "'".$_POST['email']."',";
      } else {
        $sql .= "NULL,";
      }
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
      mysql_query($sql);
	  $sql ="INSERT INTO $thour (USER) VALUES('".$_POST['username']."')";
	  mysql_query($sql);
	  $sql ="INSERT INTO $tday (USER) VALUES('".$_POST['username']."')";
	  mysql_query($sql);

      header("Location: editmember.php?".$_POST['username']);
    } else {
      echo "That <b>Domain</b> name is used by another partner<BR>";
    }
  } else {
    echo "That <b>username</b> is used by another partner<BR>";
  }
}
?>
</body>
</html>
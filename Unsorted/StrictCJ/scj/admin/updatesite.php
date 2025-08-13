<?php
include("variables.inc.php");
include($dbconnect);
if (!$_POST['domain']) {
  $err = 1;
  $error .= "You must enter a <b>Domain Name</b><BR>";
} elseif ((count(explode('.',$_POST['domain'])) > 3) or (count(explode('.',$_POST['domain'])) < 2)) {
  $err = 1;
  $error .= "Not a valid <b>domain</b> format<BR>";
}
if (!$_POST['url']) {
  $err = 1;
  $error .= "You must enter an <b>URL</b><BR>";
} elseif (substr($_POST['url'],0,7) <> "http://") {
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
  $sql = "UPDATE $tablename SET TITLE='".$_POST['title']."',RATIO=".$_POST['ratio'].",DOMAIN='".$_POST['domain']."',MIN=".$_POST['min'].",URL='".$_POST['url']."'";
  if ($_POST['email']) {
    $sql .= ",EMAIL='".$_POST['email']."'";
  } else {
    $sql .= ",EMAIL=NULL";
  }
  if ($_POST['icq']) {
    $sql .= ",ICQ=".$_POST['icq'];
  } else {
    $sql .= ",ICQ=NULL";
  }
  if ($_POST['nick']) {
    $sql .= ",NICK='".$_POST['nick']."'";
  } else {
    $sql .= ",NICK=NULL";
  }
  $sql .= " WHERE NOUSER='".$_POST['username']."'";
  mysql_query($sql) or die(mysql_error());
  header ("Location: editmember.php?".$_POST['username']);
}
?>

<?php
/***********************************************************
			set your variables
***********************************************************/

// set to url visitors will see when entering your site
$site_url="http://www.yourdomain.com/main.html";

/***********************************************************
			End of variables
***********************************************************/
include("scj/admin/variables.inc.php");
$domain=$site_cookie_domain;
$time = getdate();
$hour = $time["hours"];
$min = $time["minutes"];
if ($min == 0) $min = 59;
else if ($min % 2 == 0) $min -= 1;

function checkref($referer) {
  $fparse = parse_url($referer);
  $furlpart = explode(".",$fparse["host"]);
  $cresult = count($furlpart);
  if (($cresult >= 3) && ($furlpart[$cresult - 3] != "www"))
    $fdomain = $furlpart[$cresult - 3] . "." . $furlpart[$cresult - 2] . "." . $furlpart[$cresult - 1];
  else
    $fdomain = $furlpart[$cresult - 2] . "." . $furlpart[$cresult - 1];
  return $fdomain;
}

function logIP($user) {
	Global $hour,$min,$_SERVER,$index_path;
	if (!is_dir($index_path."/scj/data/logs")) mkdir($index_path."/scj/data/logs",0777);
	$file = $index_path."/scj/data/logs/incoming-".$hour."-".$min.".dat";
	$lockfile = $file.".lock";
	if (!file_exists($lockfile)) { touch($lockfile); chmod($lockfile,0777); }
	if (!file_exists($file)) { touch($file); chmod($lockfile,0777); }
	$lock = fopen ($lockfile,"r");
	while (!flock($lock,LOCK_EX));
	$fp = fopen($file,"a");
	fputs($fp,$user."|".$_SERVER['REMOTE_ADDR']."|".$_SERVER['HTTP_REFERER']."\n");
	fclose($fp);
	flock($lock,LOCK_UN);
	fclose($lock);
}

function countref($tname,$cfdomain,$cdomain,$cuniquetime,$cunique_strict) {
  Global $thour,$min;
  $csql = "SELECT NOUSER FROM $tname WHERE DOMAIN='$cfdomain'";
  $csth = mysql_query($csql);
  if ((mysql_num_rows($csth) == 0)) {
    if ($cunique_strict)
	  $csql = "UPDATE $thour SET RIN$min=RIN$min + 1 WHERE USER='noref'";
    else {
      setcookie("unique_strict","true",time()+$cuniquetime,"/",$cdomain);
      $csql = "UPDATE $thour SET RIN$min=RIN$min + 1,UIN$min=UIN$min + 1 WHERE USER='noref'";
    }
    mysql_query($csql);
	logIP("noref");
  }
  else {
    $cpointer = mysql_fetch_array($csth);
    $cuser = $cpointer[0];
	logIP($cuser);
    mysql_free_result($csth);
    setcookie("refer_strict",$cuser,time()+86400,"/",$cdomain);
    if ($cunique_strict) {
	  $csql = "UPDATE $thour SET RIN$min=RIN$min + 1 WHERE USER='$cuser'";
      mysql_query($csql);
    }
    else {	
      setcookie("unique_strict","true",time()+$cuniquetime,"/",$cdomain);
      $csql = "UPDATE $thour SET RIN$min=RIN$min + 1,UIN$min=UIN$min + 1 WHERE USER='$cuser'";
      mysql_query($csql);
    }
  }
}

include($dbconnect);
if ($_SERVER['HTTP_REFERER']) {
  $from_domain = checkref($_SERVER['HTTP_REFERER']);
  if ($from_domain) {
    countref($tablename,$from_domain,$domain,$uniquetime,$_COOKIE['unique_strict']);
  }
  else {
    setcookie("unique_strict","true",time()+$uniquetime,"/",$domain);
  }
}
else {
  countref($tablename,"noref",$domain,$uniquetime,$_COOKIE['unique_strict']);
}
header("Location: $site_url");
?>

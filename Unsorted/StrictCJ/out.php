<?php
include("scj/admin/variables.inc.php");
$domain=$site_cookie_domain;
$time = getdate();
$hour = $time["hours"];
$min = $time["minutes"];
$gotOut=0;
if ($min == 0) $min = 59;
else if ($min % 2 == 0) $min -= 1;


function logIP($user) {
	Global $hour,$min,$REMOTE_ADDR,$index_path;
	if (!is_dir($index_path."/scj/data/logs")) mkdir($index_path."/scj/data/logs",0777);
	$file = $index_path."/scj/data/logs/outgoing-".$hour."-".$min.".dat";
	$lockfile = $file.".lock";
	if (!file_exists($lockfile)) touch($lockfile);
	if (!file_exists($file)) touch($file);
	$lock = fopen ($lockfile,"r");
	while (!flock($lock,LOCK_EX));
	$fp = fopen($file,"a");
	fputs($fp,$user."|".$REMOTE_ADDR."\n");
	fclose($fp);
	flock($lock,LOCK_UN);
	fclose($lock);
}

function writedb($cuser,$curl,$cdomain,$ctablename,$crefer_strict) {
  Global $thour,$min;
  setcookie($cuser,$curl,time()+43200,"/",$cdomain);
  if ($crefer_strict) {
	  $sql = "UPDATE $thour SET TOUT$min=TOUT$min + 1 WHERE USER='$crefer_strict'";
      mysql_query($sql);
	  logIP($crefer_strict);
  }
  $sql = "UPDATE $thour SET UOUT$min=UOUT$min + 1 WHERE USER='$cuser'";
  mysql_query($sql);
}

function countGalHit($crefer_strict) {
  Global $tablename,$thour,$min;
  $sql = "UPDATE $thour SET GOUT$min=GOUT$min + 1 WHERE USER='$crefer_strict'";
  mysql_query($sql);
}

include($dbconnect);
mt_srand((double)microtime()*1000000);
$sendToUrl = 0; 
// check if url uses probability
include($index_path."/scj/data/prob.inc");
if (!empty($_GET['url'])) {
  if (!empty($_GET['p']) || $_GET['p']==0) {
    $_GET['p'] = 100 - $_GET['p'];
    if ((mt_rand(0,100) >= $_GET['p'])) {
      $sendToUrl = 1;
    }
  }
  else $sendToUrl = 1;
}

if (!empty($_GET['link'])) {
  include("loglink.php");
  logLink($_GET['link']);
}

if ($sendToUrl == 1) {
  if (!empty($_COOKIE['refer_strict']))
    countGalHit($_COOKIE['refer_strict']);
  header("Location: ".$_GET['url']);
  $gotOut=1;
}

// no galleries? then trade
else {
  $perm = 0;
  if ($_GET['friend']) {
    $sql = "SELECT NOUSER,URL FROM $tablename WHERE NOUSER = '".$_GET['friend']."'";
    $sth = mysql_query($sql);
    if (mysql_num_rows($sth) != 0) {
      $pointer = mysql_fetch_array($sth);
      $user = $pointer[0];
      $url = $pointer[1];
      if (! $_COOKIE[$user]) {
        writedb($user,$url,$domain,$tablename,$_COOKIE['refer_strict']);
        $perm = 1;
        header("Location: $url");
		$gotOut=1;
      }
    }
  }
  if ($perm == 0) {
	$lockfile=$outfile.".lock";
	if (!file_exists($lockfile))
		touch($lockfile);
	if (!file_exists($outfile)) {
		header("Location: http://www.strict-cj.com/trades");
		$gotOut=1;
	}
	else {
		$fpl = fopen($lockfile,"r");
		while (!flock($fpl,LOCK_SH));
		$fpr = fopen($outfile,"r");
		$stop=0; $count = 0;
		if ($_COOKIE['refer_strict']) {
			while (($count < $outsize) && (!$stop)) {
				$result = fgets($fpr,4096);
				$resultArr = explode("|",$result);
				$user = $resultArr[0];
				$url = $resultArr[1];
				if ((! $_COOKIE[$user]) && ($user != $_COOKIE['refer_strict'])) $stop = 1;
				$count++;
			}
	    } 
		else {
			while (($count < $outsize) && (!$stop)) {
				$result = fgets($fpr,4096);
				$resultArr = explode("|",$result);
				$user = $resultArr[0];
				$url = $resultArr[1];
				if (! $_COOKIE[$user]) $stop = 1;
				$count++;
			}
		}
		if (!$stop) {
			fseek($fpr,0,SEEK_SET);
			srand((double)microtime()*1000000);
			$chosen = round(rand(1,$outsize));
			$counter=1;
			while (($counter <= $outsize) && (!$stop)) {
				$result = fgets($fpr,4096);
				$resultArr = explode("|",$result);
				$user = $resultArr[0];
				$url = $resultArr[1];
				if ($chosen == $counter) $stop = 1;
				$counter++;
			}
		}
		fclose($fpr);
		flock($fpl,LOCK_UN);
		fclose($fpl);
	    writedb($user,$url,$domain,$tablename,$_COOKIE['refer_strict']);
		if (!empty($url)) {
		    header("Location: $url");
			$gotOut=1;
		}
	}
  }
}
if (!$gotOut) header("Location: http://www.strict-cj.com/trades");
?>
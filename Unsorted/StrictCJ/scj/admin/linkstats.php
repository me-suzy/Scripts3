<html>
<?
include("variables.inc.php");
$path = $index_path."/scj/data/linktracking/";
?>
<p align="center"><font size="3" face="tahoma"><b><a href="<?=$PHP_SELF?>?reset=1">Reset Link Tracking</a></b></font></p>
<?
if ($handle = opendir($path)) {
	/* This is the correct way to loop over the directory. */
	$i=0; $total=0;
    while (false !== ($file = readdir($handle))) { 
		$result = explode(".",$file);
		if ($_GET['reset'] == 1) {
			if (($result[1] == "dat") || ($result[1] == "lock")) {
//				echo $index_path."/scj/data/linktracking/".$file."<BR>";
				unlink($index_path."/scj/data/linktracking/".$file);
			}
		}
		else {
			if ($result[1] == "dat") {
	 			$link = $result[0];
		   		$lfp = fopen($path.$link.".lock","r");
				while (!flock($lfp, LOCK_SH));
				$fp = fopen($path.$link.".dat","r");
				$result = fgets($fp,4096);
				fclose($fp);
				//unlock read
				flock($lfp, LOCK_UN);
				fclose($lfp);
				$links[$i] = $link;
				$results[$i] = $result;
				$total += $result;
				$i++;
			}
		}
	}
	closedir($handle); 
	?>
	<div align="center">
	<table width="600" border="0" cellpadding="4" cellspacing=1 bgcolor="#221144">
	 <tr>
	  <td bgcolor="#552266" align="center"><font size="3" 	color="#F1F1F1"><b>Link</b></font></td>
	  <td bgcolor="#552266" align="center"><font size="3" 	color="#F1F1F1"><b>Hits</b></font></td>
	  <td bgcolor="#552266" align="center"><font size="3" color="#F1F1F1"><b>Pct</b></font></td>
	 </tr>
	<?
    for ($j=0;$j<$i;$j++) {
	?><tr><td bgcolor="#F1F1F1" align="center"><font size="3"><b><?= $links[$j] ?></b></font></td><?
	?><td bgcolor="#F1F1F1" align="center"><font size="3"><b><?= $results[$j] ?></b></font></td><?
	?><td bgcolor="#F1F1F1" align="center"><font size="3"><b><?= sprintf("%.2f",(($results[$j] / $total) * 100)) ?></b></font></td></tr><?
	}
	?>
	 <tr>
	  <td bgcolor="#552266" align="center"><font size="3" color="#F1F1F1"><b>Total :</b></font></td>
	  <td bgcolor="#552266" align="center"><font size="3" color="#F1F1F1"><b><?= $total ?></b></font></td>
	  <td bgcolor="#552266" align="center"><font size="3" color="#F1F1F1"><b>100.00</b></font></td>
	 </tr>
	<?
	echo "</table></div>";
} else echo "Can't open directory $path...";
?>
</html>
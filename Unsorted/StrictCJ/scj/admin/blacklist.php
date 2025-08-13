<div align="center">
<?
include ("variables.inc.php");
$blacklist = $index_path."/scj/data/blacklist.db";

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

function writeBlacklist($blacklist,$domains) {
	if (!file_exists($blacklist.".lock")) touch($blacklist.".lock");
	$lfp = fopen ($blacklist.".lock","r");
	while (!flock($lfp,LOCK_EX));
	$fp = fopen($blacklist,"w");
	for($i=0; $i<count($domains); $i++) {
		fputs($fp,$domains[$i]);
	}
	flock($lfp,LOCK_UN);
	fclose($lfp);
}

function main() {
	Global $blacklist;
	$domains = readBlacklist($blacklist);
	if (is_array($domains)) {
		?><form action="blacklist.php" method="POST"><b>Selected :</b> <input type="submit" name="submit" value="Delete"><table cellpadding="3" cellspacing="0"	border="0"><BR><?
		for ($i=0; $i<count($domains); $i++) {
			echo "<tr><td><input type=\"checkbox\" name=\"selected[]\" value=\"".$domains[$i]."\"></td><td>".$domains[$i]."</td></tr>";
		}
		?></table></form><?
	} else echo "no blacklisted domains to print...<BR><BR>";
}

function deleteDomains($selected) {
	Global $blacklist;
	$domains = readBlacklist($blacklist);
	if (is_array($domains) && is_array($selected))	{
		$count = 0;
		for ($i=0; $i<count($domains); $i++) {
			$found = 0;
			for ($j=0; $j<count($selected); $j++) {
				if (chop($selected[$j]) == chop($domains[$i]))
					$found = 1;
			}
			if (!$found) {
				$newDomains[$count] = $domains[$i];
				$count++;
			}
		}
		writeBlacklist($blacklist,$newDomains);
	}
}

function addDomain($domain) {
	Global $blacklist;
	$domains = readBlacklist($blacklist);
	if (!in_array($domain."\n",$domains)) {
		if (!file_exists($blacklist.".lock")) touch($blacklist.".lock");
		$lfp = fopen ($blacklist.".lock","r");
		while (!flock($lfp,LOCK_EX));
		$fp = fopen($blacklist,"a");
		fputs($fp,$domain."\n");
		flock($lfp,LOCK_UN);
		fclose($lfp);
	}
}

switch ($_POST['submit']) {
	case "Add": { if (!empty($_POST['domain'])) addDomain($_POST['domain']); } break;
	case "Delete": { deleteDomains($_POST['selected']); } break;
}
main();
?>
<form action="blacklist.php" method="POST">
<b>domain : </b><input type="text" name="domain" size="20">&nbsp;&nbsp;<input type="submit" name="submit" value="Add">
</form>
</div>
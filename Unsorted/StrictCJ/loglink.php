<?
include("scj/admin/variables.inc.php");
$path = $index_path."/scj/data/linktracking/";
function logLink($link) {
	global $path;
	if (!is_dir($path)) mkdir($path,0777);
	if (!file_exists($path.$link.".lock"))
	  touch($path.$link.".lock");
	// lock for read
    if (file_exists($path.$link.".dat")) {
		$lfp = fopen($path.$link.".lock","r");
		while (!flock($lfp, LOCK_SH));
		$fp = fopen($path.$link.".dat","r");
		$result = fgets($fp,4096);
		fclose($fp);
		//unlock read
		flock($lfp, LOCK_UN);
		fclose($lfp);
		$result++;
	} else $result = 1;

	if (empty($result)) $result = 1;

	// lock file
	$lfp = fopen($path.$link.".lock","r");
	while (!flock($lfp, LOCK_EX));
	$fp = fopen($path.$link.".dat","w");
	fputs($fp,$result);
	fclose($fp);
	// removing lock and closing file
	flock($lfp, LOCK_UN);
	fclose($lfp);
}


?>
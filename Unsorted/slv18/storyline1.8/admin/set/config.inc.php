<font class='catdis'> > Configuration </font><p>

<?
if ( !$_POST["confsubmit"]) {

	$define = file(SL_ROOT_PATH."/base/define.inc.php");
	for ($i=0 ; $i < 9 ; $i++) {
		$define[$i] = str_replace(array('define(', '"', ');'), array("", "", ""), $define[$i]);
	}

	$rootpath = explode("," , $define[1]);
	$rooturl = explode("," , $define[2]);
	$title = explode("," , $define[3]);
	$se = explode("," , $define[4]);
	$un = explode("," , $define[5]);
	$ps = explode("," , $define[6]);
	$db = explode("," , $define[7]);
?>
	<form method='post' action='main.php?config'>
	<table border='0' width='100%'>
		<tr>
			<td width='20%'> Root Path </td><td><input type='text' name='rp' value='<?=$rootpath[1]?>'></td>
		</tr><tr>
			<td width='20%'> Root Url </td><td><input type='text' name='ru' value='<?=$rooturl[1]?>'></td>
		</tr><tr>
			<td width='20%'> Title </td><td><input type='text' name='ti' value='<?=$title[1]?>'></td>
		</tr><tr>
			<td width='20%'> Server </td><td><input type='text' name='se' value='<?=$se[1]?>'></td>
		</tr><tr>
			<td width='20%'> DB User Name </td><td><input type='text' name='un' value='<?=$un[1]?>'></td>
		</tr><tr>
			<td width='20%'> DB Password </td><td><input type='text' name='ps' value='<?=$ps[1]?>'></td>
		</tr><tr>
			<td width='20%'> Database </td><td><input type='text' name='db' value='<?=$db[1]?>'></td>
		</tr><tr>
			<td colspan='2'><input type='submit' value='alter' name='confsubmit'></td>
		</tr>
	</table>

<?	

} else {

	$string = '<?' . "\r\n";
	$string .= 'define("SL_ROOT_PATH","'.$_POST["rp"].'");' . "\r\n";
	$string .= 'define("SL_ROOT_URL","'.$_POST["ru"].'");' . "\r\n";
	$string .= 'define("SL_TITLE","'.$_POST["ti"].'");' . "\r\n";
	$string .= 'define("THE_HOST","'.$_POST["se"].'");' . "\r\n";
	$string .= 'define("USER_NAME","'.$_POST["un"].'");' . "\r\n";
	$string .= 'define("PASS_WORD","'.$_POST["ps"].'");' . "\r\n";
	$string .= 'define("DB_NAME","'.$_POST["db"].'");' . "\r\n";
	$string .= '?>';

	$fp = fopen(SL_ROOT_PATH."/base/define.inc.php","w",0777);
	fwrite($fp, $string);
	fclose($fp);	

	print "Your configuration has been updated";
}

?>

<html>
<body bgcolor=white>
<?php
# Variable Process and Session Check
	IF (!isset($_GET)) {$_TGV = $HTTP_GET_VARS;} ELSE {$_TGV = $_GET;}
	while(list($key, $var) = each($_TGV)) {
		while($var != utf8_decode($var))	{$var = utf8_decode($var);}
		while($var != urldecode($var))	{$var = urldecode($var);}
		$var = htmlentities($var);
		IF (function_exists('html_entity_decode')) {
			$var = html_entity_decode($var);
		} ELSE {
			$var = unhtmlentities($var);
		}
		while($var != strip_tags($var))	{$var = strip_tags($var);}
		$pieces = explode("\"",$var);		$var = $pieces[0];
		$pieces = explode("'",$var);		$var = $pieces[0];
		$pieces = explode(" ",$var);		$var = $pieces[0];
		IF (eregi('_id', $key) || $key == 'id') {IF (!is_numeric($var)) {$var=0;}}
		$_GPV[$key] = $var;
	}

	IF (!isset($_POST)) {$_TPV = $HTTP_POST_VARS;} ELSE {$_TPV = $_POST;}
	while(list($key, $var) = each($_TPV)) {
		while($var != utf8_decode($var))	{$var = utf8_decode($var);}
		while($var != urldecode($var))	{$var = urldecode($var);}
		$var = htmlentities($var);

		IF (function_exists('html_entity_decode')) {
			$var = html_entity_decode($var);
		} ELSE {
			$var = unhtmlentities($var);
		}
		while($var != strip_tags($var))	{$var = strip_tags($var);}
		IF (eregi('_id', $key) || $key == 'id') {IF (!is_numeric($var)) {$var=0;}}
		$_GPV[$key] = $var;
	}

# Continue with get details
	$fontface   = "verdana";
	$fontsize   = "2";
	$stdcolor   = "black";
	$_out .= '<pre>';
	$fp = fsockopen($_GPV['server'],43, $errno, $errstr, 10);
	fputs($fp, "$_GPV[ord_domain]\r\n");
	while(!feof($fp)) {$_out .= fgets($fp,128);}
	fclose($fp);
	$_out .= '</pre>';
	$_out .= '<p align=center><a href=javascript:window.close()><font face='.$fontface.' size='.$fontsize.' color='.$stdcolor.'><b>Close</b></font></a>';
	echo $_out;
?>
</body>
</html>
<?
# For php < 4.3 compatability
# replaces html_entity_decode
function unhtmlentities($string) {
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	return strtr($string, $trans_tbl);
}
?>
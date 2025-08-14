<?php


error_reporting(7);

if (!defined('directory')) {
	define('directory', '');
}

function readtpl($file) {
	$fd = @fopen($file, "r") or die("Cannot read $file");
	while($line = fgets($fd, 4096)) {
		$html .= $line;
	}
	fclose($fd);
	return $html;
}


function error($msg) {
	$error = "Error!";
	$errormessage = $msg;
	head($error);
	include(directory . "tpl/error_data.tpl");
	footer();
	exit;
}

function head($_title) {
	$html = readtpl(directory . 'tpl/_header.tpl');
	ob_start();
	eval ( "?>" . $html . "<?");
	$output = ob_get_contents ();
	ob_end_clean();
	$output = str_replace("[%title%]",$_title,$output);
	echo $output;
}

function adminhead($_title) {
	include(directory . "admin/tpl/_header.tpl");
}

function footer() {
	$html = readtpl(directory . 'tpl/_footer.tpl');
	ob_start();
	eval ( "?>" . $html . "<?");
	$output = ob_get_contents ();
	ob_end_clean();
	
	echo $output;
}

function adminfooter() {
	
	include(directory . "admin/tpl/_footer.tpl");
}

function xtime($timestamp) {
	global $timeoffset, $dateformat, $timeformat;
	$hour = $timeoffset * 3600;
	$timestamp = $timestamp + $hour;
	$date = date("$dateformat - $timeformat", $timestamp);
	return $date;
}

function xdate($timestamp,$mode) {
	global $timeoffset, $dateformat;
	if ($mode == '1') {
		$dateformat = "ymd";
	}
	$hour = $timeoffset * 3600;
	$timestamp = $timestamp + $hour;
	$date = date("$dateformat", $timestamp);
	return $date;
}

function mailreplace($content) {
	global $name, $url, $acturl, $appurl, $account, $password;
	$content = str_replace("[%account%]",$account,$content);
	$content = str_replace("[%password%]",$password,$content);
	$content = str_replace("[%appurl%]",$appurl,$content);
	$content = str_replace("[%acturl%]",$acturl,$content);
	$content = str_replace("[%name%]",$name,$content);
	$content = str_replace("[%url%]",$url,$content);
	return $content;
}

function checkgzip() {
	global $HTTP_ACCEPT_ENCODING;
	global $usegz;
	if ($usegz == 0) {
		return 0;
	}
	if (headers_sent() || connection_aborted()) {
		return 0;
	}
	if (strpos($HTTP_ACCEPT_ENCODING,'x-gzip') !== false) return "x-gzip";
	if (strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false) return "gzip";
	return 0;
}

function gzipout($level=1) {
	$gzip = checkgzip();
	if ($gzip) {
		$content = ob_get_contents();
		ob_end_clean();
		header("Content-Encoding: $gzip");
		print "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		$size = strlen($content);
		$crc = crc32($content);
		$content = gzcompress($content,$level);
		$content = substr($content, 0, strlen($content) - 4);
		print $content;
		print pack('V',$crc);
		print pack('V',$size);
		exit;
	} else {
		ob_end_flush();
		exit;
	}
}

?>
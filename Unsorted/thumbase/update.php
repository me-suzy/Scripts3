#!/usr/bin/php
<HTML>
Updating Your Files<BR>
<?php
include("DBDETAILS");
### select a random number of galleries from the thumbs db and then insert them into html ;)
$db = mysql_connect($dbhost, $dbusername, $dbpassword);
mysql_select_db($dbname,$db);
$q = "SELECT * FROM settings WHERE settingID=1;";
$result = mysql_query($q,$db);
$thumbWidth = mysql_result($result,0,"thumbWidth");
$thumbHeight = mysql_result($result,0,"thumbHeight");
$thumbQuality = mysql_result($result,0,"thumbQuality");
$thumbWidth2 = mysql_result($result,0,"thumbWidth2");
$thumbHeight2 = mysql_result($result,0,"thumbHeight2");
$thumbQuality2 = mysql_result($result,0,"thumbQuality2");
$thumbWidth3 = mysql_result($result,0,"thumbWidth3");
$thumbHeight3 = mysql_result($result,0,"thumbHeight3");
$thumbQuality3 = mysql_result($result,0,"thumbQuality3");
$outputFile1 = mysql_result($result,0,"outputFile1");
$outputFile2 = mysql_result($result,0,"outputFile2");
$outputFile3 = mysql_result($result,0,"outputFile3");
$templateFile1 = mysql_result($result,0,"templateFile1");
$templateFile2 = mysql_result($result,0,"templateFile2");
$templateFile3 = mysql_result($result,0,"templateFile3");
$installDirectory = mysql_result($result,0,"installDirectory");
$thumbsDirectory = mysql_result($result,0,"thumbsDirectory");
$installURL = mysql_result($result,0,"installURL");
$thumbsURL = mysql_result($result,0,"thumbsURL");
$tradeout[0] = mysql_result($result,0,"tradeScriptOut");
$tradegalleryout[0] = mysql_result($result,0,"tradeScriptGalleryOut");
$tradeout[1] = mysql_result($result,0,"tradeScriptOut2");
$tradegalleryout[1] = mysql_result($result,0,"tradeScriptGalleryOut2");
$tradeout[2] = mysql_result($result,0,"tradeScriptOut3");
$tradegalleryout[2] = mysql_result($result,0,"tradeScriptGalleryOut3");

$q = "SELECT * FROM thumbs WHERE active=1 ORDER BY RAND();";
$result = mysql_query($q,$db);
$count = mysql_numrows($result);
$i=0;
while ( ($i<$count) ) {
	$thumbID  = mysql_result($result,$i,"thumbID");
	$fileName = mysql_result($result,$i,"fileName");
	$galleryURL = mysql_result($result,$i,"galleryURL");
	$thumbsurl[$i] = "$galleryURL";
	$thumbsimg[$i] = "$fileName";
	$i++;
}
mysql_close();

$outfilename = $outputFile1;
$i=-1;
$currentfile=0;
if ($templateFile1!="") {
	if($file = fopen($templateFile1, "r")) {
		$out = fopen($outfilename, "w");
		echo "Writing outputFile using template $templateFile1 -> $outputfilename<BR>";
		while(!feof($file)) {
			$therate = fgets($file, 255);
			$match = array (
								"/\#THUMB_([0-9]+)_([0-9]+)_([0-9]+)_([0-9]+)\#/e",
								"/\#THUMB_([0-9]+)_([0-9]+)_([0-9]+)\#/e",
								"/\#THUMB_([0-9]+)_([0-9]+)\#/e"		
							);
			$replace =	array ("addit('$1','$2','$3','$4');","addit('$1','$2','$3',0);","addit('$1','$2',0,0)");
			$string = preg_replace($match,$replace,$therate);
			$write = fputs($out, $string);
		}
		fclose($file);
		fclose($out);
	}
}

$outfilename = $outputFile2;
//$i=-1;
$currentfile=1;
if ($templateFile2!="") {
	if($file = fopen($templateFile2, "r")) {
		$out = fopen($outfilename, "w");
		echo "Writing outputFile using template $templateFile2 -> $outputfilename<BR>";
		while(!feof($file)) {
			$therate = fgets($file, 255);
			$match = array (
								"/\#THUMB_([0-9]+)_([0-9]+)_([0-9]+)_([0-9]+)\#/e",
								"/\#THUMB_([0-9]+)_([0-9]+)_([0-9]+)\#/e",
								"/\#THUMB_([0-9]+)_([0-9]+)\#/e"		
							);
			$replace =	array ("addit('$1','$2','$3','$4');","addit('$1','$2','$3',0);","addit('$1','$2',0,0)");
			$string = preg_replace($match,$replace,$therate);
			$write = fputs($out, $string);
		}
		fclose($file);
		fclose($out);
	}
}

$outfilename = $outputFile3;
//$i=-1;
$currentfile=2;
if ($templateFile3!="") {
	if($file = fopen($templateFile3, "r")) {
		$out = fopen($outfilename, "w");
		echo "Writing outputFile using template $templateFile3 -> $outputfilename<BR>";
		while(!feof($file)) {
			$therate = fgets($file, 255);
			$match = array (
								"/\#THUMB_([0-9]+)_([0-9]+)_([0-9]+)_([0-9]+)\#/e",
								"/\#THUMB_([0-9]+)_([0-9]+)_([0-9]+)\#/e",
								"/\#THUMB_([0-9]+)_([0-9]+)\#/e"		
							);
			$replace =	array ("addit('$1','$2','$3','$4');","addit('$1','$2','$3',0);","addit('$1','$2',0,0)");
			$string = preg_replace($match,$replace,$therate);
			$write = fputs($out, $string);
		}
		fclose($file);
		fclose($out);
	}
}

function addit($skim,$new,$border,$t) {
	global $i,$what,$thumbsurl,$thumbsimg,$thumbsURL,$installURL,$tradeout,$tradegalleryout,$currentfile;
	$i++;
	if ($border==0) { $border=0; }
	if (($t==0)||($t==1)) {
		$tu = $thumbsimg[$i];
	} elseif ($t==2) {
		$tu = "$thumbsimg[$i].jpg";
	} elseif ($t==3) {
		$tu = "$thumbsimg[$i].jpg.jpg";
	}
	$temp = $tradegalleryout[$currentfile];
	$galleryout = rawurlencode( preg_replace("/\#URL\#/",rawurlencode($thumbsurl[$i]),$temp) );
	if ($thumbsurl[$i]!="") {
		if ($new!="") {
			$out = "<a onMouseOver=\"window.status='$thumbsurl[$i]';return true\" onMouseUp=\"window.status=''; return true;\" onFocus=\"window.status='$thumbsurl[$i]'; return true;\" href=\"$installURL/click.php?$skim|".$galleryout."|".rawurlencode($tradeout[$currentfile]). "\" target=\"_blank\"><img src=\"$thumbsURL/$tu\" border=\"$border\"></a>";
		} else {
			$out = "<a onMouseOver=\"window.status='$thumbsurl[$i]';return true\" onMouseUp=\"window.status=''; return true;\" onFocus=\"window.status='$thumbsurl[$i]'; return true;\" href=\"$installURL/click.php?$skim|".$galleryout."|".rawurlencode($tradeout[$currentfile]). "\"><img src=\"$thumbsURL/$tu\" border=\"$border\"></a>";
		}
	}
	return $out;
}
?>
<?php
$script["name"]="Top Story - Basic - ";
$script["version"]="1.0";
$script["author"]="Mert YALDIZ";
$script["authorMail"]="mertyaldiz@superonline.com";
$script["authorWeb"]="http://www.myphpscripts.com";
# list row backgrounds
$row1bg="#FFFFFF";
$row2bg="#F0F0F0";
$conn=@mysql_pconnect($tst["db"]["host"],$tst["db"]["user"],$tst["db"]["pass"]);
if (!$conn){
	echo "Database connection failure!";
	exit;
}
$fdb=@mysql_select_db($tst["db"]["name"]);
if (!$fdb){
	echo "Can not find database!";
	exit;
}
include("$tst[langfile]");
if(!function_exists("reformat_date")) {
	function reformat_date($newdate) {
		$dt=date("d / m / y - H:i:s",$newdate);
		return $dt;
	}
}
if(!function_exists("scriptTag")) {
	function scriptTag() {
		global $script;
		echo '<center class="text">'.$script["name"].'  '.$script["version"].'<br>';
		echo 'by : <a class="smallLink" href="mailto:'.$script["authorMail"].'">'.$script["author"].'</a><br>';
		echo  '<a  class="smallLink"  href="'.$script["authorWeb"].'">'.$script["authorWeb"].'</a></center>';
	}
}
?>

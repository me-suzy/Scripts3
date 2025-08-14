<?php
include ("mainfile.php"); 
$index = 1;
global $menu_id,$menu,$Pmenu,$activeusers;
$Pmenu="P_thememenu_homepage";
include("header.php");
centerblocks();
echo "<br>";
// This is the referer routine from Nuke, it's handy so I left in, you 
// may want to disable or remove it if you need to conserve server resources.
if ($httpref==1) {
	$referer = getenv("HTTP_REFERER");
	if ($referer=="" OR eregi("^unknown", $referer) OR substr("$referer",0,strlen($nukeurl))==$nukeurl OR eregi("^bookmark",$referer)) {
	} else {
    	    mysql_query("insert into $prefix"._referer." values (NULL, '$referer')");
	}
	$result = mysql_query("select * from $prefix"._referer."");
	$numrows = mysql_num_rows($result);
	if($numrows>=$httprefmax) {
    	    mysql_query("delete from $prefix"._referer."");
	}
}
// End of nuke referer 
include("footer.php");
?>
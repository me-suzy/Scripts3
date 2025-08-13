<?php
###############################################
# SexTraffic TGP Version 1.0
###############################################

$DBhost = "localhost"; // Host name
$DBuser = ""; // Database user name
$DBpass = ""; // Database password
$DBname = ""; // Database name



/*  DO NOT EDIT ANY DETAILS BELOW THIS LINE  */
###############################################

mysql_pconnect($DBhost,$DBuser,$DBpass) or die("Unable to connect to database");
mysql_select_db ($DBname);

$result = mysql_db_query($DBname, "SELECT * FROM st_options WHERE op=1");
$oprow = mysql_fetch_array($result);

$tablewidth = $oprow["tablewidth"];
$sitename = $oprow["sitename"];
$header = $oprow["header"];
$footer = $oprow["footer"];
$limitlinks = $oprow["limitlinks"];
$displaydate = $oprow["displaydate"];
$keywords = $oprow["keywords"];
$content = $oprow["content"];
$sitetitle = $oprow["sitetitle"];
$background = $oprow["background"];
$text = $oprow["text"];
$linkcolor = $oprow["linkcolor"];
$linkcolor2 = $oprow["linkcolor2"];
$archivelimit = $oprow["archivelimit"];
$datecolor = $oprow["datecolor"];
$turncatliston = $oprow["turncatliston"];
$newwindow = $oprow["newwindow"];
$siteurl = $oprow["siteurl"];
$recip = $oprow["recip"];
$submityn = $oprow["submityn"];
$submitynreason   = $oprow["submitynreason"];
$recipyn = $oprow["recipyn"];
$adminemail = $oprow["adminemail"];
$stversion = $oprow["stversion"];
$installdate = $oprow["installdate"];
$turncatliston = $oprow["turncatliston"];
$orderedby = $oprow["orderedby"];
$wayorder = $oprow["wayorder"];


function fetchtemplate($templatename) {
$gettemp = mysql_fetch_array(mysql_query("SELECT tempcontent FROM st_template WHERE title='$templatename' ORDER BY tempsetid DESC LIMIT 1"));
$tempcontent=$gettemp[tempcontent];
$tempcontent=addslashes($tempcontent);
$tempcontent=str_replace("\\'","'",$tempcontent);
return $tempcontent;
}
?>
<?
/**
 * PATHS AND URLS
 */
$url="http://localhost";
$path="c:\\apache\\htdocs";
$tst["folder"]="art";
$tst["headerfile"]="c:\\apache\\htdocs\\art/inc/header.php";
$tst["footerfile"]="c:\\apache\\htdocs\\art/inc/footer.php";
/**
 * DATABASE VARIABLES
 */
$tst["db"]["host"]="localhost";
$tst["db"]["name"]="mps";
$tst["db"]["user"]="root";
$tst["db"]["pass"]="1111";
$tst["tbl"]["articles"]="marticles";
/**
 * USERNAME AND PASSWORD FOR ADMINISTRATION SECTION
 */
$tst["admin"]["usrName"]="admin";
$tst["admin"]["usrPass"]="1234";
/**
 * OTHER CONFIGURATION VARIABLES
 */
$tst["conf"]["laps"]=604800;
$tst["conf"]["topNewsList"]=5;
$tst["conf"]["allowHtml"]=1;
$tst["langf"]="eng";
/**
 *
 * DO NOT MODIFY ANY LINES BELOW
 *
 */
$tst["url"]=$url."/".$tst["folder"];
$tst["path"]=$path."/".$tst["folder"];
$tst["langfile"]=$tst["path"]."/lang/".$tst["langf"].".php";
include($tst["path"]."/inc/globals.inc.php");
include($tst["langfile"]);
/**
 *
 * DO NOT MODIFY ANY LINES ABOVE
 *
 */
?>
<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
include("navigation.php");
// print("$menu_ordinary<p />");
// print("<h2>$name_of_site</h2>");
$mostviewed = 1;
$kid = '1';
require("links.php");
include_once("admin/config/footer.inc.php");
?>
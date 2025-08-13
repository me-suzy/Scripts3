<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
if (!$special_mode)
   { include("navigation.php"); }
// { print("$menu_ordinary<p />"); }
// print("<h2>$name_of_site</h2>");
include_once("member_header.php");

check_valid_user();
print "<b>$la_welcome_member_1</b><br />";
print "$la_welcome_member_1 $valid_user, $la_welcome_member_3";
include_once("member_footer.php");
include_once("admin/config/footer.inc.php");
?>
<?
session_start( );

include("config/header.inc.php");
include("config/");
include("functions.php");
if (!$special_mode) { print("$menu_ordinary<p>"); }
print("<h2>$name_of_site</h2>");

require("a.php");

if ($num_hits > 0) { delete_ads("$id"); }
else {  print(" $la_not_authorized "); }
	
include("config/footer.inc.php");
?>

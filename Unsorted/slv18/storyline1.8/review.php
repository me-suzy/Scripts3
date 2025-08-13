<?

include_once("base/define.inc.php");
include_once("base/main.inc.php");

$display = new MakeDisplay;

if ($_GET["set"] == "read") {
	$files["title"] = SL_TITLE." &#8226 .: Reviews :.";
} else {
	$files["title"] = SL_TITLE." &#8226 .: Reviews :. &#8226 .: Add :.";
}

$files["body"] = SL_ROOT_PATH."/set/main/reviews.inc.php";

$display->CheckTemp($files);
$display->DisplayTemp();

?>
<?

include_once("base/define.inc.php");
include_once("base/main.inc.php");

$display = new MakeDisplay;

if ($_GET["cat"]) {
	$files["title"] = SL_TITLE." &#8226 .: Sub-Categories :.";
	$files["body"] = SL_ROOT_PATH."/set/main/cats.inc.php";
} else {
	$files["title"] = SL_TITLE." &#8226 .: Stories :.";
	$files["body"] = SL_ROOT_PATH."/set/main/list.inc.php";
}

$display->CheckTemp($files);
$display->DisplayTemp();

?>
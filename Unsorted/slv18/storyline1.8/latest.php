<?

include_once("base/define.inc.php");
include_once("base/main.inc.php");

$display = new MakeDisplay;

$files["title"] = SL_TITLE." &#8226 .: Latest :.";
$files["body"] = SL_ROOT_PATH."/set/main/latest.inc.php";

$display->CheckTemp($files);
$display->DisplayTemp();

?>
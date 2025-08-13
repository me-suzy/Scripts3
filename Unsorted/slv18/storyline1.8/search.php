<?

include_once("base/define.inc.php");
include_once("base/main.inc.php");

$display = new MakeDisplay;

$files["title"] = SL_TITLE;
$files["body"] = SL_ROOT_PATH."/set/main/search.inc.php";


$display->CheckTemp($files);
$display->DisplayTemp();

?>
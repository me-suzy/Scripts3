<?
include_once("base/define.inc.php");
include_once("base/main.inc.php");

$display = new MakeDisplay;

$files["title"] = SL_TITLE." &#8226 .: User Panel :.";
$files["body"] = SL_ROOT_PATH."/set/userpanel/userpanel.inc.php";

$display->CheckTemp($files);
$display->DisplayTemp();
?>
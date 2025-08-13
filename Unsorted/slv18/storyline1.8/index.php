<?
include_once("base/define.inc.php");
include_once("base/main.inc.php");

$files["title"] = SL_TITLE;
$files["body"] = SL_ROOT_PATH."/set/main/index.inc.php";
$files["stats"] = SL_ROOT_PATH."/base/html/Default/stats.inc.php";
$files["footer"] = SL_ROOT_PATH."/base/html/Default/footer.inc.php";

$display = new MakeDisplay;
$display->CheckTemp($files);
$display->DisplayTemp();
?>
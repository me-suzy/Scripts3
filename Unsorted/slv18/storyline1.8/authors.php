<?
include_once("base/define.inc.php");
include_once("base/main.inc.php");

$display = new MakeDisplay;

if ( $_POST["letter"] )
	$files["title"] = SL_TITLE." &#8226 .: Authors :. &#8226 .: " . substr($_POST["letter"],0,1) . " :.";
else
	$files["title"] = SL_TITLE." &#8226 .: Authors :.";

$files["body"] = SL_ROOT_PATH."/set/main/authors.inc.php";

$display->CheckTemp($files);
$display->DisplayTemp();

?>
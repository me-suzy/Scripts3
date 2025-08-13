<?
include_once("base/define.inc.php");
include_once("base/main.inc.php");

print " <SCRIPT LANGUAGE=\"JavaScript\"> \n";
print " function formHandler(form){ \n";
print " var URL = document.form.chapnav.options[document.form.chapnav.selectedIndex].value; \n";
print " window.location.href = URL; \n";
print " } \n";
print " </SCRIPT> \n\n";

$display = new MakeDisplay;


$files["title"] = SL_TITLE." &#8226 .: Story :.";

if ($_GET["no"] && $_GET["chapter"]) {

$files["body"] = SL_ROOT_PATH."/set/main/chapter.inc.php";

} else {

$files["body"] = SL_ROOT_PATH."/set/main/story.inc.php";

}

$display->CheckTemp($files);
$display->DisplayTemp();
?>
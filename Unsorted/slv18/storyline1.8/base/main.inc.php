<?
session_start();

include(SL_ROOT_PATH."/base/classes/dbal.class.php");
include(SL_ROOT_PATH."/base/classes/thecleaner.class.php");
include(SL_ROOT_PATH."/base/classes/display.class.php");
include(SL_ROOT_PATH."/base/functions.func.php");

$files["layout"] = SL_ROOT_PATH."/base/html/Default/index.tmpl.php";
$files["banner"] = SL_ROOT_PATH."/base/html/Default/banner.inc.php";
$files["menu"] = SL_ROOT_PATH."/base/html/Default/menu.inc.php";
$files["copyright"] = SL_ROOT_PATH."/base/html/Default/copyright.inc.php";

if( session_is_registered("uid") ) {
	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;
	$dl->update("sl18_users",array('uactive'=>date("Y-m-d H:i:s")),array('uid'=>$_SESSION["uid"]));
}

?>
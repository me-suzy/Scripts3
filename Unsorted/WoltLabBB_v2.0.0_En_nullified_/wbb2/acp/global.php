<?php
$disableverify=0;

@error_reporting(7);
$phpversion=(int)(str_replace(".","",phpversion()));

if(file_exists("setup.php") || file_exists("update.php")) die("please delete setup.php and update.php");

/** get function libary **/
require("./lib/functions.php");
if($phpversion<410) {
 $_REQUEST=array();
 $_COOKIE=array();
 $_POST=array();
 $_GET=array();
 $_SERVER=array();
 $_FILES=array();
 get_vars_old();
}
// remove slashes in get post cookie data...
if (get_magic_quotes_gpc()) {
  if(is_array($_REQUEST)) $_REQUEST=stripslashes_array($_REQUEST);
  if(is_array($_POST)) $_POST=stripslashes_array($_POST);
  if(is_array($_GET)) $_GET=stripslashes_array($_GET);
  if(is_array($_COOKIE)) $_COOKIE=stripslashes_array($_COOKIE);
}
@set_magic_quotes_runtime(0);
/** connect db **/
require("./lib/config.inc.php");
require("./lib/class_db_mysql.php");

$db = new db($sqlhost,$sqluser,$sqlpassword,$sqldb,$phpversion);
/** get configuration **/
require("./lib/options.inc.php");

/** start session **/
$REMOTE_ADDR = getIpAddress();
$HTTP_USER_AGENT = substr(getenv("HTTP_USER_AGENT"), 0, 100);
 
require("./lib/class_adminsession.php");
$wbbuserdata=array();
if($_GET['sid'] || $_POST['sid']) {
 $adminsession = new adminsession();
 if($_GET['sid']) $adminsession->update($_GET['sid'],$REMOTE_ADDR,$HTTP_USER_AGENT);
 else $adminsession->update($_POST['sid'],$REMOTE_ADDR,$HTTP_USER_AGENT);
 $session['hash']=$adminsession->hash;
}

if(!$wbbuserdata['dateformat']) $wbbuserdata['dateformat']=$dateformat;
if(!$wbbuserdata['timeformat']) $wbbuserdata['timeformat']=$timeformat;

function makeadminpagelink($link,$page,$pages,$number) {
 eval ("\$pagelink = \"".gettemplate("pagelink")."\";");
 
 if($page-$number>1) eval ("\$pagelink .= \"".gettemplate("pagelink_first")."\";");
 if($page>1) {
  $temppage=$page-1;
  eval ("\$pagelink .= \"".gettemplate("pagelink_left")."\";");
 }
 $count = ifelse($page+$number>=$pages,$pages,$page+$number);
 for($i=$page-$number;$i<=$count;$i++) {
  if($i<1) $i=1;
  if($i==$page) eval ("\$pagelink .= \"".gettemplate("pagelink_current")."\";");
  else eval ("\$pagelink .= \"".gettemplate("pagelink_nocurrent")."\";");
 }

 if($page<$pages) {
  $temppage=$page+1;
  eval ("\$pagelink .= \"".gettemplate("pagelink_right")."\";");
 }
 if($page+$number<$pages) eval ("\$pagelink .= \"".gettemplate("pagelink_last")."\";");
 
 return $pagelink;
}
?>
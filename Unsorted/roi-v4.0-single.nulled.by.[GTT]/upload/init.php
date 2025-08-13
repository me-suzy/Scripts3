<?php
error_reporting(0);

include("defines.php");
include("login.php");
define("ADOT",2);
$VARS = array();

function DBConnect(){
   $res = mysql_connect(DBHOST,DBUSER,DBPASS);
   if(!$res)pError("Connection error.","Error database connecting");
   $res = mysql_selectdb(DBNAME);
   if(!$res)pError("Database not found.","Please, check database name");
}

function Assign($var,$data){
  global $VARS;
  $VARS[$var] = $data;
}

function ShowTemplate($data){
  global $VARS;
  $lines = File("templates/header.htm");
  $lines = implode('',$lines);

  foreach($VARS as $key => $val){
    $lines = str_replace("%$key%",$val,$lines);
  }

  echo $lines;
  echo $data;
  $lines = File("templates/footer.htm");
  $lines = implode('',$lines);

  foreach($VARS as $key => $val){
    $lines = str_replace("%$key%",$val,$lines);
  }

  echo $lines;
  exit(1);
}

function pError($header,$body){
  $data = "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;<font size=6 face=verdana>$header</font><br>".
  "&nbsp;&nbsp;&nbsp;&nbsp;<font class=title>$body</font><br><br><br><br>";
  Assign('image_label','label-error.gif');
  ShowTemplate($data);
}

function GetActionCode($code){
 global $HTTP_SERVER_VARS;
  $fullpath = '';
  $path = explode( '/', $HTTP_SERVER_VARS['SCRIPT_NAME'] );
  for( $i = 0; $i < sizeof($path) - 1; $i++ )$fullpath .= "$path[$i]/";
  $fullpath = substr($fullpath,0,strlen($fullpath) - 1);
# return "<img src=http://".$HTTP_SERVER_VARS['SERVER_NAME']."$fullpath/click.php?a".$code.">";
 return "<iframe src=http://".$HTTP_SERVER_VARS['SERVER_NAME']."$fullpath/click.php?a".$code." width=0 height=0 marginwidth=0 marginheight=0 align=left scrolling=no frameborder=0></iframe>";
}

function GetCampaignCode($code){
   global $HTTP_SERVER_VARS,$HTT_POST_VARS;
   $fullpath = '';
   $path = explode( '/', $HTTP_SERVER_VARS['SCRIPT_NAME'] );
   for( $i = 0; $i < sizeof($path) - 1; $i++ )$fullpath .= "$path[$i]/";
   $fullpath = substr($fullpath,0,strlen($fullpath) - 1);
 return "http://".$HTTP_SERVER_VARS['SERVER_NAME']."$fullpath/click.php?c".$code;
}


DBConnect();

?>
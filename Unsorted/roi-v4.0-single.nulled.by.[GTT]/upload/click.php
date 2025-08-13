<?php
include("defines.php");

function DBConnect(){
   $res = mysql_connect(DBHOST,DBUSER,DBPASS);
   if(!$res)pError("Connection error.","Error database connecting");
   $res = mysql_selectdb(DBNAME);
   if(!$res)pError("Database not found.","Please, check database name");
}
function pError($header,$body){
  $data = "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;<font size=6 face=verdana>$header</font><br>".
  "&nbsp;&nbsp;&nbsp;&nbsp;<font size=3 color=#c7c7c7>$body</font><br><br><br><br>";
}

function DiscountAction($id){
   global $HTTP_SERVER_VARS,$TIMEOUT;
   $user_ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
   $host = $HTTP_SERVER_VARS['REMOTE_HOST'];
   $action_id = $id;

   $res0 = mysql_query("SELECT campaign_id,id,action_link,end_point FROM ROIactions WHERE action_code ='$action_id'");
   $row0 = mysql_fetch_row($res0);
   if(!isset($row0[0])){
     Image();
     return;
   }
   $campaign_id = isset($row0[0]) ? $row0[0] : 0;
   $keyword_id = -1;

if (isset($_COOKIE['ROIref']))
{
$query = urldecode($_COOKIE['ROIref']);
$res = mysql_query("SELECT id, keyword_name FROM ROIkeywords WHERE campaign_id = $campaign_id");
$go = true;
while (($rowk = mysql_fetch_row($res)) && $go)
{
 if (eregi($rowk[1],$query))
 {
  $keyword_id = $rowk[0];
  $go = false;
 }
}
}

   mysql_query("INSERT INTO ROIlogs(action_id,ip,tm,keyword_id) VALUES($row0[1],'$user_ip',NOW(),$keyword_id)");
   return;
}

function DiscountClick($id){
   global $HTTP_SERVER_VARS,$TIMEOUT;
   $user_ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
   $host = $HTTP_SERVER_VARS['REMOTE_HOST'];
   $campaign_id = $id;

   $res0 = mysql_query("SELECT id,campaign_link,price_type FROM ROIcampaigns WHERE campaign_code = '$campaign_id'");
   $row0 = mysql_fetch_row($res0);
   if(!isset($row0[0])){
     Header("Location: $row0[1]");
     return 0;
   }

   $keyword_id = -1;
   if ( $row0[2] == 4)
   {
    setcookie('ROIref',$HTTP_SERVER_VARS['HTTP_REFERER']);
    $ref = Parse_URL($HTTP_SERVER_VARS['HTTP_REFERER']);
    $query = urldecode($ref['query']);
    $res = mysql_query("SELECT id, keyword_name FROM ROIkeywords WHERE campaign_id = $row0[0]");

    $go = true;
    while ( ($rowk = mysql_fetch_row($res)) && $go)
    {
      if (eregi($rowk[1],$query))
      {
       $keyword_id = $rowk[0];
       $go = false;
      }
    }

   }

   mysql_query("INSERT INTO ROIclicks(campaign_id,ip,host,tm,keyword_id) VALUES($row0[0],'$user_ip','$host',NOW(),$keyword_id)");

   Header("Location: $row0[1]");
   exit(1);
}

function Image(){
 $file = File('z.gif');
 $file = implode('',$file);
 header("Content-Type: image/gif");
 echo $file;
 exit(1);
}

DBConnect();

$query = isset($HTTP_SERVER_VARS['QUERY_STRING']) ? $HTTP_SERVER_VARS['QUERY_STRING'] : '';

if(strlen($query) == 0)PrintImate();
$id = substr($query,1,strlen($query));

if($query[0] == 'c')DiscountClick($id);
if($query[0] == 'a')DiscountAction($id);

?>
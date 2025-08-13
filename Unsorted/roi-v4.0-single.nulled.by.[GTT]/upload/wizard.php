<?php
/**********************************
* Last modified: 17/12/2003
**********************************/

include("init.php");
include('smalltemplater.php');

$myerror = array();
$prev = false;
$next = false;
$finish = false;

Assign('image_label','label-campaigns.gif');

$uid = $_SESSION['uid'];


if(isset($HTTP_POST_VARS['addaction_x']) || strlen($HTTP_POST_VARS['addaction_x'])>0 ){
   Finish2();
   rmpostvar('action_name');
   rmpostvar('action_link');
   rmpostvar('act');
   rmpostvar('action_price');
   rmpostvar('actioncode');
   rmpostvar('show_code');
   rmpostvar('addaction_x');
   rmpostvar('action_update');
//echo "<font size=5>REMOVED</font><br>";
   $HTTP_POST_VARS['step'] = 5;
}

if(isset($HTTP_POST_VARS['addlist_x']) || strlen($HTTP_POST_VARS['addlist_x'])>0 ){
   $id=Finish1();
   rmpostvar('action_name');
   rmpostvar('action_link');
   rmpostvar('act');
   rmpostvar('action_price');
   rmpostvar('actioncode');
   rmpostvar('show_code');
   rmpostvar('addaction_x');
   rmpostvar('action_update');

   Header('Location: listwizard.php?id='.$id);

}

function addError($val){ global $myerror; $myerror[sizeof($myerror)] = $val; }
function outError(){ global $myerror; $rv = '<table border=0><tr><td>';
         foreach ( $myerror as $key => $val ){
         $rv .= "<img src=\"images/bullet.gif\" width=16 height=10 border=0 align=absmiddle><span class=title><font color=#A70000>$val</font></span><br>";}
         return $rv.'</td></tr></table>';
}

function rmpostvar($name){
 global $HTTP_POST_VARS;
 $tmp=array();
 foreach($HTTP_POST_VARS as $key => $val ){
  if($key != $name)$tmp[$key] = $val;
 }
 $HTTP_POST_VARS = $tmp;
}
function Uniq(){
   rand((double)microtime(),100000);
   $str =  md5(uniqid(rand()));
   while(strlen($str)<15)$str .= Uniq();
 return substr($str,0,15);
}
$HTTP_POST_VARS['prev_but']  = isset($HTTP_POST_VARS['bbut']) ? '' : '<input type=image src=images/button-previous.gif name=prev value=prev title=Previous>';

//if($HTTP_SERVER_VARS['QUERY_STRING']!='add')Step6();
if(strlen($HTTP_SERVER_VARS['QUERY_STRING']) > 0 && $HTTP_SERVER_VARS['QUERY_STRING']!='add'){
 $temp = explode('.',$HTTP_SERVER_VARS['QUERY_STRING']);
 $id = $temp[0];
 if(isset($temp[1]) && $temp[1]!='a'){
   $res1 = mysql_query("SELECT id,campaign_id,action_name,action_link,action_code,action_type,end_point,action_price FROM ROIactions WHERE user_id = $uid && id = $temp[1]");
   $rw = mysql_fetch_array($res1);
   $tmp['action_name'] = $rw['action_name'];
   $tmp['action_link'] = $rw['action_link'];
   $tmp['act'] = $rw['action_type'];
   $tmp['action_price'] = $rw['action_price'];
   $tmp['actioncode'] = $rw['action_code'];
   $tmp['show_code'] = GetActionCode($rw['action_code']);
   $tmp['action_update'] = 'true';
   $tmp['step'] = 5;
 }else if($temp[1] == 'a'){
  $tmp['step'] = 5;
  }


 $res = mysql_query( "SELECT UNIX_TIMESTAMP(campaign_begin),UNIX_TIMESTAMP(campaign_end),campaign_code,campaign_name,campaign_link,price_type,per_click,price FROM ROIcampaigns WHERE user_id = $uid && id = $id" );
 $row = mysql_fetch_array( $res );
 if(!isset($row[0]))pError("Error Campaign Identeficator","Not found ROI Campaign with this Identeficator");
 $tmp['bbut'] = 1;
 $tmp['campaign_name'] = $row['campaign_name'];
 $tmp['campaign_link'] = $row['campaign_link'];
 $tmp['price_type'] = $row['price_type'];
 $tmp['per_click'] = $row['per_click'];
 $tmp['price'] = $row['price'];

 $tmp['bday'] = date( "j", $row[0] );
 $tmp['byear'] = date( "Y", $row[0] );
 $tmp['bmonth'] = date( "m", $row[0] );

 $tmp['eday'] = date( "j", $row[1] );
 $tmp['eyear'] = date( "Y", $row[1] );
 $tmp['emonth'] = date( "m",$row[1] );
 $tmp['campaign_id'] = $temp[0];
 $tmp['queryid'] = $row['campaign_code'];
 $HTTP_POST_VARS = $tmp;
}

if(!isset($HTTP_POST_VARS['step']))$HTTP_POST_VARS['step'] = 1;

if(isset($HTTP_POST_VARS['prev_x']) && strlen($HTTP_POST_VARS['prev_x']) > 0 ||
     isset($HTTP_POST_VARS['prev']) && strlen($HTTP_POST_VARS['prev']) > 0 ){
                        $HTTP_POST_VARS['step'] -= 1;
                        rmpostvar('prev_x');
                        rmpostvar('prev_y');
                        $prev = true;
}
if(isset($HTTP_POST_VARS['addaction_x']) && strlen($HTTP_POST_VARS['addaction_x']) >0 || isset($HTTP_POST_VARS['next_x']) && strlen($HTTP_POST_VARS['next_x']) > 0 ||
        isset($HTTP_POST_VARS['next']) && strlen($HTTP_POST_VARS['next']) > 0 ){
                        $HTTP_POST_VARS['step'] += 1;
                        rmpostvar('next_x');
                        rmpostvar('next_y');
                        $next = true;
}

if(isset($HTTP_POST_VARS['finish1_x']) && strlen($HTTP_POST_VARS['finish1_x']) > 0 ||
       isset($HTTP_POST_VARS['finish1']) && strlen($HTTP_POST_VARS['finish1'])>0 ){
 rmpostvar('finish1_x');
 if(!isset($HTTP_POST_VARS['campaign_id']))
 {
 $campaign_id = Finish1();
 header("Location: campaigns.php?pid=finish&id=".$campaign_id);
 exit(1);
 }
}

if(isset($HTTP_POST_VARS['finish2_x']) && strlen($HTTP_POST_VARS['finish2_x']) > 0 ||
       isset($HTTP_POST_VARS['finish2']) && strlen($HTTP_POST_VARS['finish2']) > 0 ){
 Finish2();
 rmpostvar('finish2_x');
 rmpostvar('action_name');
 rmpostvar('action_link');
 rmpostvar('act');
 rmpostvar('action_price');
 rmpostvar('actioncode');
 rmpostvar('show_code');
 header("Location: campaigns.php?pid=editcampaign&id=".$HTTP_POST_VARS['campaign_id']);
 exit(1);
}


if ($HTTP_POST_VARS['price_type'] != 4)
{
 $HTTP_POST_VARS['pricelab'] = '<b>Price</b>';
}
else
{
 $HTTP_POST_VARS['pricelab']='';
 $HTTP_POST_VARS['price']='';
}

switch((integer)$HTTP_POST_VARS['step']){
  case 1:
       if(!isset($HTTP_POST_VARS['bday'])){
        $HTTP_POST_VARS['bday'] = $HTTP_POST_VARS['eday'] = date("d",time());
        $HTTP_POST_VARS['bmonth'] = $HTTP_POST_VARS['emonth'] = date("m",time());
        $HTTP_POST_VARS['byear'] = $HTTP_POST_VARS['eyear'] = date("Y",time());
       }
       break;
  case 2:
       Step2();
       break;
  case 3:
       Step4();
       break;
  case 4:
       Step3();
       break;
  case 5:
       break;
  case 6:
       Step6();
       break;
  case 7:
       Step7();
       break;
  case 8:
       Step8();
       break;
  case 9:
       $HTTP_POST_VARS['step'] = 5;
       break;
};

function Hiddenfields(){
  global $HTTP_POST_VARS;
  $rv = '';
  foreach( $HTTP_POST_VARS as $key => $val ){
    $rv .= "<input type=hidden name=$key value=\"$val\">\n";
  }
 return $rv;
}

function Prepare($val){
    $val = str_replace('/','\/',$val);
    $val = str_replace('(','\(',$val );
    $val = str_replace("\\","\\\\\\",$val);
    $val = str_replace('(','\(',$val );
    $val = str_replace(')','\)',$val );
    $val = str_replace('|','\|',$val );
    $val = str_replace('$','\$',$val );
    $val = str_replace('-','\-',$val );
    $val = str_replace(' ','\s',$val );
    $val = str_replace('[','\[',$val );
    $val = str_replace(']','\]',$val );
  return $val;
}

function Template($template){
  global $HTTP_POST_VARS;
  $file = File( $template );
  $file = implode( '', $file );

  $val = array('bmonth','bday','byear','emonth','eday','eyear');

  for($i = 0; $i < sizeof($val); $i++ ){
     $$val[$i] = !isset( $HTTP_POST_VARS[$val[$i]] ) ? 1 : $HTTP_POST_VARS[$val[$i]];
#echo $$val[$i]."is now $val[$i]<br>";
  }

  $txt = $radio = $option = false;
  foreach( $HTTP_POST_VARS as $key => $val ){
    if(strstr($file,"%$key%")>-1)$file = str_replace( '%'.$key.'%', $val, $file );
       $key = Prepare($key);
       $val = Prepare($val);


       $file = preg_replace("/<input type=text name=".$key."(.+?){1,30}>/i","<input type=text name=$key value=\"".$HTTP_POST_VARS[$key]."\">",$file);
       $file = preg_replace("/<input type=radio(.+?)name=".$key." value=[\"\']{0,1}".$val."[\"\']{0,1}>/i","<input type=radio\\1name=$key value=".$val." checked>",$file);
       $file = preg_replace("/<option value=[\"\']{0,1}".$val."[\"\']{0,1}>/i","<option value=\"$val\" selected>",$file);
  }
  $file = str_replace( "%dateform1%", Dateform($bmonth,$bday,$byear,0,5,'b'), $file);
  $file = str_replace( "%dateform2%", Dateform($emonth,$eday,$eyear,0,5,'e'), $file);

  $file = str_replace( "%hiddenfields%", Hiddenfields(), $file);
  $file = preg_replace( "/%[A-Za-z0-9_]{1,20}%/", "", $file );
//TEMPORARY
  return "<table border=0 width=100% height=100%><tr><td align=center valign=center>".outError().$file."</td></tr></table>";
}

### stap functions ###
function Step2(){
 global $HTTP_POST_VARS;
 if(! isset($HTTP_POST_VARS['campaign_name']) || strlen($HTTP_POST_VARS['campaign_name']) == 0){
   addError("Enter campaign name");
   $HTTP_POST_VARS['step'] = 1;
 }else if(! isset($HTTP_POST_VARS['campaign_link']) || strlen($HTTP_POST_VARS['campaign_link']) == 0){
   addError("Enter campaign link");
   $HTTP_POST_VARS['step'] = 1;
 }
}


function Step3(){
 global $HTTP_POST_VARS;
 foreach( $HTTP_POST_VARS as $key => $val ){
   $$key = $val;
 }

}

function Step4(){
 global $HTTP_SERVER_VARS,$HTTP_POST_VARS;
 foreach( $HTTP_POST_VARS as $key => $val ){
   $$key = $val;
 }
 $per_click = $price;
 if(!isset($price_type)){
   addError("Select Price type.");
   $HTTP_POST_VARS['step'] = $HTTP_POST_VARS['step']-1;
 }else if( $price_type == 1 && !ROIis_int($per_click) && !ROIis_float($per_click) ){
   addError("Enter vaild price of click");
   $HTTP_POST_VARS['price'] = '';
   $HTTP_POST_VARS['step'] = $HTTP_POST_VARS['step']-1;
 }else if($price_type == 2 && !ROIis_int($price) && !ROIis_float($price) ){
   addError("Enter vaild price");
   $HTTP_POST_VARS['price'] = '';
   $HTTP_POST_VARS['step'] = $HTTP_POST_VARS['step']-1;
 }else if($price_type == 3 && !ROIis_int($price) && !ROIis_float($price) ){
   addError("Enter vaild price");
   $HTTP_POST_VARS['price'] = '';
   $HTTP_POST_VARS['step'] = $HTTP_POST_VARS['step']-1;

 }else if($price_type == 2 && mktime(0,0,0,$bmonth,$bday,$byear) >= mktime(0,0,0,$emonth,$eday,$eyear)){
    addError("Invalid time range");
   $HTTP_POST_VARS['price'] = '';
   $HTTP_POST_VARS['step'] = $HTTP_POST_VARS['step']-1;
 }else{
  $tmp = '';
         if($price_type == 2){
           $tmp .= "<tr><td><b>Price type:</b></td><td>Time range</td></tr>";
           $tmp .= "<tr><td><b>Campaign begin:</td><td>$bmonth/$bday/$byear</td></tr>";
           $tmp .= "<tr><td><b>Campaign end:</td><td>$emonth/$eday/$eyear</td></tr>";
         }else if($price_type == 3){
          $tmp .= "<tr><td><b>Price type:</b></td><td>Per period</td></tr>";
         }else if($price_type == 1) {
          $tmp .= "<tr><td><b>Price type:</b></td><td>Per click</td></tr>";
         }else $tmp .= "<tr><td><b>Price type:</b></td><td>Per keyword</td></tr>";


   $HTTP_POST_VARS['begin_end'] = $tmp;
   if(!isset($HTTP_POST_VARS['queryid']))$HTTP_POST_VARS['queryid'] = Uniq();
   $HTTP_POST_VARS['code'] = GetCampaignCode($HTTP_POST_VARS['queryid']);
 }

//}//else $HTTP_POST_VARS['step'] = 5;
}

function Finish1(){
  global $HTTP_POST_VARS,$myerror, $uid;
  foreach( $HTTP_POST_VARS as $key => $val ){
    $$key = $val;
  }

  $campaign_begin = date("Y-m-d", mktime(0,0,0,$bmonth,$bday,$byear) );
  $campaign_end = date("Y-m-d", mktime(0,0,0,$emonth,$eday,$eyear) );

  $per_click = $price;
  $per_click = str_replace(',','.',$per_click );
  $per_click = str_replace('$','',$per_click );
  $price = str_replace(',','.',$price );
  $price = str_replace('$','',$price );

  $price = strlen($price) == 0 ? 0 : $price;
  $per_click = strlen($per_click) == 0 ? 0 : $per_click;
  $pertype = isset($pertype) ? $pertype : 0;

  if($price_type == 3)$campaign_begin = date('Y-m-d');

  if(!isset($campaign_id)){

$add_c_sql = "INSERT INTO ROIcampaigns VALUES(
                                     '',
                                     '$campaign_name',
                                     '$campaign_link',
                                     '$queryid',
                                     NOW(),
                                     $price_type,
                                     $per_click,
                                     '$campaign_begin',
                                     '$campaign_end',
                                     $price,
                                     $pertype,
                                     $uid
                                     )
                                     ";

     $res = mysql_query($add_c_sql);

    if(!$res)pError("Request error!","You have some errors in your request");
    $res = mysql_query("SELECT id FROM ROIcampaigns WHERE user_id = $uid && campaign_code = '$queryid'");
    $row = mysql_fetch_row($res);
    $HTTP_POST_VARS['campaign_id'] = isset($row[0]) ? $row[0] : 0;
    return $row[0];
  }
  else{
     $res = mysql_query("UPDATE ROIcampaigns SET
                                             campaign_name = '$campaign_name',
                                             campaign_link = '$campaign_link',
                                             price_type = $price_type,
                                             per_click = $per_click,
                                             campaign_begin = '$campaign_begin',
                                             campaign_end = '$campaign_end',
                                             price = $price WHERE id = $campaign_id
                                             ");
    if(!$res)pError("Request error!","You have some errors in your request");
  }

}

function Finish2(){
 global $HTTP_POST_VARS, $uid;

  foreach( $HTTP_POST_VARS as $key => $val ){
    $$key = $val;
//    echo $key.' = '.$val.'<br>';
  }

  if(!isset($action_price))$action_price = 0;
  $action_price = str_replace(',','.',$action_price );
  $action_price = str_replace('$','',$action_price );

  if($act == 1){ $end_point = 2; }
  else{ $end_point = 1; $action_price = 0; }

 if(!isset($action_update)){
  mysql_query("INSERT INTO ROIactions VALUES(
                           '',
                           $campaign_id,
                           '$action_name',
                           '$action_link',
                           '$actioncode',
                           $act,
                           NOW(),
                           $end_point,
                           $action_price, $uid
                           )
                           ");
 rmpostvar('action_name');
 rmpostvar('action_link');
 rmpostvar('act');
 rmpostvar('action_price');
 rmpostvar('actioncode');
 rmpostvar('show_code');
}else{
  $res = mysql_query("UPDATE ROIactions SET action_name = '$action_name',action_link = '$action_link',
                      action_type = $act,end_point = $end_point,action_price = $action_price WHERE action_code = '$actioncode'");

   if(!$res)pError("Updating error.","Error update this action");
 }
}

function Step5(){
 global $HTTP_POST_VARS,$next,$HTTP_SERVER_VARS;

 $error = false;
 if(!isset($HTTP_POST_VARS['campaign_id']))Finish1();


if(strlen($HTTP_SERVER_VARS['QUERY_STRING'])==0 && !isset($HTTP_POST_VARS['addaction_x']) && strlen($HTTP_POST_VARS['addaction_x'])==0){
//if($HTTP_POST_VARS['step']!=8){
  if($next && $HTTP_POST_VARS['step'] - 1 == 4)return 1;
  if(strlen($HTTP_POST_VARS['action_name']) == 0){ $error = true; addError("Please, enter your action name"); }
  if(strlen($HTTP_POST_VARS['action_link']) == 0){ $error = true; addError("Please, enter link to your action"); }
  if(!isset($HTTP_POST_VARS['act'])){$error = true ; addError("Select action type."); }
  if($error){ $HTTP_POST_VARS['step'] = 5; return 2; }
}


//}else $HTTP_POST_VARS['step'] = 5;
return 0;

}

function Step6(){

if(Step5() == 0){
 global $HTTP_POST_VARS,$next,$prev;
 if($prev){
  switch($HTTP_POST_VARS['act']){
      case 1:
           if($HTTP_POST_VARS['step'] != 5){
            $HTTP_POST_VARS['step'] = 6;
           }
           break;
      case 2:
           $HTTP_POST_VARS['step'] = 5;
           break;
  }
}
if($next){
  $act = $HTTP_POST_VARS['act'];
  if($act == 1)$HTTP_POST_VARS['step'] = 6;
  else if($HTTP_POST_VARS['step'] == 5);
  else{ $HTTP_POST_VARS['step'] = 7; Step7(); }
}

}

}
function Step7(){
 global $HTTP_POST_VARS,$HTTP_SERVER_VARS,$prev,$next;
 if($HTTP_POST_VARS['act'] == 1 && !ROIis_float($HTTP_POST_VARS['action_price']) && !ROIis_int($HTTP_POST_VARS['action_price'])){
   addError("Please, enter the income for this action.");
   $HTTP_POST_VARS['step'] = 6;
 }else{
  if(!isset($HTTP_POST_VARS['actioncode']))$HTTP_POST_VARS['actioncode'] = Uniq();
 }

 $HTTP_POST_VARS['show_code'] = GetActionCode($HTTP_POST_VARS['actioncode']);
}

function Step8(){
 global $HTTP_POST_VARS;
 if($HTTP_POST_VARS['act'] == 1){
   $tp = "<tr><td><b>Action type:</b></td><td>End point</td></tr>".
         "<tr><td><b>Action income</b></td><td>".$HTTP_POST_VARS['action_price']."</td></tr>";
   $HTTP_POST_VARS['prices'] = $tp;
 }else{
   $tp = "<tr><td><b>Action type:</b></td><td>Intermediate</td></tr>";
 }

 $HTTP_POST_VARS['prices'] = $tp;
}


ShowTemplate(Template( 'templates/step'.($HTTP_POST_VARS['step']).'.htm' ));

?>
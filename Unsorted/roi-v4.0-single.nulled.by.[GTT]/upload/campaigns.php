<?php
  include("init.php");
  include("smalltemplater.php");
  Assign('image_label','label-campaigns.gif');

  if(!isset($HTTP_GET_VARS['id']) && !isset($HTTP_POST_VARS['id']))$id = 0;
  else if(isset($HTTP_GET_VARS['id'])) $id = $HTTP_GET_VARS['id'];
  else $id = $HTTP_POST_VARS['id'];

  if(isset($HTTP_POST_VARS['remove']) && strlen($HTTP_POST_VARS['remove'])>0)Remove();
  if((isset($HTTP_POST_VARS['removeaction'])|| isset($HTTP_POST_VARS['removeaction_x'])) && strlen($HTTP_POST_VARS['removeaction_x'])>0 || strlen($HTTP_POST_VARS['removeaction'])>0)RemoveAction();

  if((isset($HTTP_POST_VARS['removekeyword'])|| isset($HTTP_POST_VARS['removekeyword_x'])) && strlen($HTTP_POST_VARS['removekeyword_x'])>0 || strlen($HTTP_POST_VARS['removekeyword'])>0)RemoveKeyword();

  if(isset($HTTP_POST_VARS['add']) && strlen($HTTP_POST_VARS['add'])>0)Remove();
  if((isset($HTTP_POST_VARS['changekeywords'])|| isset($HTTP_POST_VARS['changekeywords_x'])) && strlen($HTTP_POST_VARS['changekeywords_x'])>0 || strlen($HTTP_POST_VARS['changekeywords'])>0)ChangeKeyword();

    if((isset($HTTP_POST_VARS['removelist'])|| isset($HTTP_POST_VARS['removelist_x'])) && strlen($HTTP_POST_VARS['removelist_x'])>0 || strlen($HTTP_POST_VARS['removelist'])>0)RemoveListing();
//***classes for saving campaign and actions settings
  class CampaignList{
  var $id,
      $campaign_name,
      $campaign_link,
      $action_count,
      $price_type,
      $per_click,
      $campaign_begin,
      $campaign_end,
      $price,
      $n,
      $code;

   function Init($id){
     $res = mysql_query("SELECT UNIX_TIMESTAMP(campaign_begin),UNIX_TIMESTAMP(campaign_end),
            campaign_name,campaign_link,price_type,price,pertype,campaign_code FROM ROIcampaigns WHERE id=$id");
      $row = mysql_fetch_array($res);
      $this->id = $id;
      $this->campaign_name = $row['campaign_name'];
      $this->campaign_link = $row['campaign_link'];
      $this->price_type = $row['price_type'];
      $this->per_click = $row['per_click'];
      $this->campaign_begin = $row[0];
      $this->campaign_end = $row[1];
      $this->price = $row['price'];
      $this->pertype = $row['pertype'];
      $this->code = $row['campaign_code'];
   }
   function ActionsCount($id){
      $res = mysql_query("SELECT count(id) FROM ROIactions WHERE campaign_id = $id");
      $row = mysql_fetch_row($res);
     return isset($row[0]) ? $row[0] : 0;
   }

   function GetAvKeywordPrice($id){
     $res = mysql_query("SELECT sum(keyword_price), count(id) FROM ROIkeywords WHERE campaign_id= $id");
     $row = mysql_fetch_row($res);
     $av  = round($row[0]/$row[1],2);
     return $av;
   }
  }

class ActionsList{
  var $id,
      $campaign_id,
      $action_name,
      $action_link,
      $action_price,
      $action_type,
      $end_point,
      $n,
      $code;

  function Init($id,$n){
    $res = mysql_query("SELECT action_name,campaign_id,action_link,action_type,action_price,end_point,action_code FROM ROIactions WHERE id = $id");
    $row = mysql_fetch_array($res);
    if(!isset($row['action_name']))return;
    $this->id = $id;
    $this->campaign_id = $row['campaign_id'];
    $this->action_name = $row['action_name'];
    $this->action_link = $row['action_link'];
    $this->action_price = $row['action_price'] == 0 ? '-' : '$'.$row['action_price'];
    $this->action_type = $row['end_point'] == 1 ? 'Intermediate' : 'End point';
    $this->end_point = $row['end_point'];
    $this->code = $row['action_code'];
    $this->n = $n;
  }
}

class Listing{
  var $id,
      $campaign_id,
      $listing_name,
      $key_num,
      $average_price,
      $n;

  function Init($id,$n){
    $res = mysql_query("SELECT campaign_id, listing_name FROM ROIlistings WHERE id = $id ORDER BY id");
    $row = mysql_fetch_array($res);
    if(!isset($row['listing_name']))return;
    $this->id = $id;
    $this->campaign_id = $row['campaign_id'];
    $this->listing_name = $row['listing_name'];
    $this->n = $n;
    $res = mysql_query("SELECT count(id), sum(keyword_price) FROM ROIkeywords WHERE listing_id = $id");
    $row = mysql_fetch_row($res);
    $this->key_num = $row[0];
    $this->average_price = round($row[1]/$row[0],2);
  }
}

class Keyword{
  var $id,
      $campaign_id,
      $listing_name,
      $key_num,
      $average_price,
      $n;

  function Init($id,$n){
    $res = mysql_query("SELECT campaign_id, keyword_name, keyword_price, listing_id FROM ROIkeywords WHERE id = $id ORDER BY id");
    $row = mysql_fetch_array($res);
    $this->id = $id;
    $this->campaign_id = $row['campaign_id'];
    $this->keyword_name = $row['keyword_name'];
    $this->keyword_price = $row['keyword_price'];
    $this->listing_id = $row['listing_id'];
    $this->n = $n;
  }
}
// end of structure


  function Loc(){
    Header("Location: campaigns.php");
  }
  function LocActions(){
    global $id;
    Header("Location: campaigns.php?pid=editcampaign&id=$id");
    exit(1);
  }
  function LocKeywordsEdit(){
    global $id;
    Header("Location: campaigns.php?pid=editkeywords&id=$id");
    exit(1);
  }

  function Remove(){
    global $HTTP_POST_VARS;
    if(!isset($HTTP_POST_VARS['all']) || $HTTP_POST_VARS['all'] == 0)return 0;
    for($i = 0; $i < $HTTP_POST_VARS['all']; $i++){
        if(isset($HTTP_POST_VARS['cr'.$i]) && strlen($HTTP_POST_VARS['cr'.$i])>0)
        {
           $res = mysql_query("SELECT id FROM ROIaction WHERE campaign_id=".$HTTP_POST_VARS['cr'.$i]);
           while ($row = mysql_fetch_row($res))
           {
            mysql_query("DELETE FROM ROIlogs WHERE action_id=".$row[0]);
           }
           mysql_query("DELETE FROM ROIactions WHERE campaign_id=".$HTTP_POST_VARS['cr'.$i]);
           mysql_query("DELETE FROM ROIclicks WHERE campaign_id=".$HTTP_POST_VARS['cr'.$i]);
           mysql_query("DELETE FROM ROIkeywords WHERE campaign_id=".$HTTP_POST_VARS['cr'.$i]);
           mysql_query("DELETE FROM ROIkeywordlogs WHERE campaign_id=".$HTTP_POST_VARS['cr'.$i]);
           mysql_query("DELETE FROM ROIlistings WHERE campaign_id=".$HTTP_POST_VARS['cr'.$i]);
           mysql_query("DELETE FROM ROIcampaigns WHERE id=".$HTTP_POST_VARS['cr'.$i]);
       }
     }

   Loc();
  }//Removing campaigns

  function RemoveAction(){
    global $HTTP_POST_VARS;
    if(!isset($HTTP_POST_VARS['all']) || $HTTP_POST_VARS['all'] == 0)return 0;
    for($i = 0; $i < $HTTP_POST_VARS['all']; $i++){
        if(isset($HTTP_POST_VARS['ar'.$i]) && strlen($HTTP_POST_VARS['ar'.$i])>0)
        {
                mysql_query("DELETE FROM ROIlogs WHERE action_id=".$HTTP_POST_VARS['ar'.$i]);
         $res = mysql_query("DELETE FROM ROIactions WHERE id=".$HTTP_POST_VARS['ar'.$i]);
        }
    }
   LocActions();
  }//Moving action to trash :-)


  function RemoveKeyword(){
    global $HTTP_POST_VARS;
    if(!isset($HTTP_POST_VARS['all']) || $HTTP_POST_VARS['all'] == 0)return 0;
    for($i = 0; $i < $HTTP_POST_VARS['all']; $i++){
        if(isset($HTTP_POST_VARS['kr'.$i]) && strlen($HTTP_POST_VARS['kr'.$i])>0)
           $res = mysql_query("DELETE FROM ROIkeywords WHERE id=".$HTTP_POST_VARS['kr'.$i]);
           $res = mysql_query("DELETE FROM ROIkeywordlogs WHERE keyword_id=".$HTTP_POST_VARS['kr'.$i]);
    }
    LocKeywordsEdit();
  }


function RemoveListing()
{
    global $HTTP_POST_VARS;
    if(!isset($HTTP_POST_VARS['all_lists']) || $HTTP_POST_VARS['all_lists'] == 0) return 0;
    for($i = 0; $i < $HTTP_POST_VARS['all_lists']; $i++){
        if(isset($HTTP_POST_VARS['kl'.$i]) && strlen($HTTP_POST_VARS['kl'.$i])>0){
           $res = mysql_query("DELETE FROM ROIlistings WHERE id=".$HTTP_POST_VARS['kl'.$i]);
           $res = mysql_query("SELECT id FROM ROIkeywords WHERE listing_id =".$HTTP_POST_VARS['kl'.$i]);
           while ($row = mysql_fetch_row($res))
           {
            mysql_query("DELETE FROM ROIkeywordlogs WHERE keyword_id=".$row[0]);
           }
           $res = mysql_query("DELETE FROM ROIkeywords WHERE listing_id=".$HTTP_POST_VARS['kl'.$i]);
    }
   }

     LocActions();
}


 function ChangeKeyword(){
    global $HTTP_POST_VARS;
    if(!isset($HTTP_POST_VARS['all']) || $HTTP_POST_VARS['all'] == 0)return 0;
    for($i = 0; $i < $HTTP_POST_VARS['all']; $i++){
           $price = floatval($HTTP_POST_VARS['kp'.$i]);
           $res = mysql_query("UPDATE ROIkeywords SET keyword_price = '$price' WHERE id=".$HTTP_POST_VARS['kid'.$i]);
    }

    $listing_name = mysql_escape_string($HTTP_POST_VARS['listing_name']);
    mysql_query("UPDATE ROIlistings SET listing_name = '$listing_name' WHERE id=".$HTTP_POST_VARS['listing_id']);
    $res = mysql_query("SELECT campaign_id FROM ROIlistings WHERE id=".$HTTP_POST_VARS['listing_id']);
    $row = mysql_fetch_row($res);

    Header("Location: campaigns.php?pid=editcampaign&id=".$row[0]);
    exit(1);
  }

  function Show(){
  $uid = $_SESSION['uid'];
    $res = mysql_query("SELECT id,campaign_name,price_type,price FROM ROIcampaigns WHERE user_id = $uid");


    $n = 0;
    $vs = array();
    while($row = mysql_fetch_array($res)){
      $vs[$n] = new CampaignList;
      $vs[$n]->id = $row['id'];
      $vs[$n]->campaign_name = $row['campaign_name'];
      ///setting price type
      if($row['price_type'] == 1)$type = 'per click';
      if($row['price_type'] == 2)$type = 'per time range';
      if($row['price_type'] == 3)$type = 'per period';
      if($row['price_type'] == 4)$type = 'per keyword';

      $vs[$n]->price_type =  $type;
      $vs[$n]->price = $row['price'];
      if($row['price_type'] == 4) $vs[$n]->price='-';
      $vs[$n]->action_count = $vs[$n]->ActionsCount($vs[$n]->id);
      $vs[$n]->n = $n;
      $n++;
    }
    return CampaignsListTemplate($vs,$n);
  }//Showing campaigns list

  function ShowActions(){
    global $id;
    $res = mysql_query("SELECT id FROM ROIactions WHERE campaign_id = $id");
    $n = 0;
    $actions = array();
    while($row = mysql_fetch_row($res)){
      $actions[$n] = new ActionsList;
      $actions[$n]->Init($row[0],$n);
      $n++;
    }
   return ActionsListTemplate($actions,$n);
  }


  function ShowListings(){
    global $id;
    $res = mysql_query("SELECT id FROM ROIlistings WHERE campaign_id = $id ORDER BY id");
    $n = 0;
    $keywords = array();
    while($row = mysql_fetch_row($res)){
      $listing[$n] = new Listing;
      $listing[$n]->Init($row[0],$n);
      $n++;
    }
   return ListingTemplate($listing,$n);
  }

  function ShowKeywordsEdit(){
    global $id;
    $res = mysql_query("SELECT id FROM ROIkeywords WHERE listing_id = $id ORDER BY id");
    $n = 0;
    $sum = 0;
    $keywords = array();
    while($row = mysql_fetch_row($res)){
      $keywords[$n] = new Keyword;
      $keywords[$n]->Init($row[0],$n);
      $sum += $keywords[$n]->keyword_price;
      $n++;
    }
    $average_price = @round($sum/$n,ADOT);
    $res = mysql_query("SELECT listing_name FROM ROIlistings WHERE id=$id");
    $row = mysql_fetch_row($res);
    $listing_name = $row[0];
   return KeywordsListEditTemplate($keywords,$n,$average_price,$id,$listing_name);
  }


function ShowKeywordsAdd(){
    global $id;
    $res = mysql_query("SELECT listing_name FROM ROIlistings WHERE id=$id");
    $row = mysql_fetch_row($res);
    $listing_name = $row[0];
   return AddKeywordsTemplate($id,$listing_name);
  }


   function ShowListing(){
    global $id;
    $res = mysql_query("SELECT id FROM ROIkeywords WHERE listing_id = $id ORDER BY id");
    $n = 0;
    $sum = 0;
    $keywords = array();
    while($row = mysql_fetch_row($res)){
      $keywords[$n] = new Keyword;
      $keywords[$n]->Init($row[0],$n);
      $sum += $keywords[$n]->keyword_price;
      $n++;
    }
    $average_price = @round($sum/$n,ADOT);
    $res = mysql_query("SELECT listing_name FROM ROIlistings WHERE id=$id");
    $row = mysql_fetch_row($res);
    $listing_name = $row[0];
   return ShowListingTemplate($keywords,$n,$average_price,$id,$listing_name);
  }


function AddKeyword($keywords,$id,$keyword_price,$listing_id) {
 $keyword_price = floatval($keyword_price);
 $id = intval($id);

$keyword = explode("\n",$keywords);

foreach ($keyword as $kw)
{

if (strlen(trim($kw))>0)
{
 $kw = mysql_escape_string(trim($kw));
 mysql_query("INSERT INTO ROIkeywords(campaign_id,keyword_name,keyword_price,listing_id) VALUES('$id','$kw','$keyword_price','$listing_id');");
}

}
}

function AddKeywordsDo() {
global $_POST;

$keywords = $_POST['newkeyword'];
$price_type = $_POST['price_type'];
$price = $_POST['keyword_price'];
$listing_id = $_POST['id'];

$res = mysql_query("SELECT campaign_id FROM ROIlistings WHERE id = $listing_id");
$row = mysql_fetch_row($res);

$id = $row[0];

if ($_POST['price_type'] == 0) { $keyword_price = $_POST['keyword_price'];}
else {$keyword_price = 0;}

AddKeyword($keywords,$id,$keyword_price,$listing_id);
Header("Location: campaigns.php?pid=editkeywords&id=$listing_id");
exit(1);

}



  function CreateDateForm($pref,$tm){
    $day = date("d",$tm);
    $month = date("m",$tm);
    $year = date("Y");
   return Dateform($month,$day,$year,0,5,$pref);
  }//Showing actions list

  function EditCampaign($pid){
   global $id;
   $t = new CampaignList;
   $t->Init($id);
   Assign('id',$id);
     switch ($pid):
          case 'editcampaign':
            $lines = File('templates/editcampaign.htm');
            $lines = implode('',$lines);
            Assign('campaign_name',$t->campaign_name);
            Assign('campaign_link',$t->campaign_link);
            Assign('price_type', $t->price_type == 1 ? 'Per click' : 'Period');
            if ($t->price_type == 4) { Assign('price_type','Keyword'); }
            Assign('price',$t->price);

            if ($t->price_type == 4) { $avp = $t->GetAvKeywordPrice($t->id);
                                       Assign('price','$'.$avp); }

            Assign('code',GetCampaignCode($t->code));
  #####################
    if ($t->price_type == 3)
{
    if ($t->pertype == 1)
     {
            Assign('pertype','(per day)');
     }
    if ($t->pertype == 2)
     {
            Assign('pertype','(per week)');
     }
    if ($t->pertype == 3)
     {
            Assign('pertype','(per month)');
     }
    if ($t->pertype == 4)
     {
            Assign('pertype','(per quater)');
     }
    if ($t->pertype == 5)
     {
            Assign('pertype','(per year)');
     }
}
else
{
            Assign('pertype','');
}
  #####################
            $lines .= ShowActions();

            if ($t->price_type == 4) {
            $lines .= ShowListings();
            }

            break;
          case 'campaigncode':
            $lines = File('templates/campaigncode.htm');
            $lines = implode('',$lines);
            Assign('code',GetCampaignCode($t->code));
            break;
          case 'editcampaignform':
            $lines = File('templates/campaignform.htm');
            $lines = implode('',$lines);
            Assign('campaign_name',$t->campaign_name);
            Assign('campaign_link',$t->campaign_link);
            for($i = 1; $i < 6; $i++)
            Assign('opt'.$i, $t->pertype == $i ? ' selected' : '');
            Assign('price', $t->price_type == 1 ? 'Per click' : 'Period');
            Assign('type1','');
            Assign('type2','');
            Assign('type3','');
            if($t->price_type == 1)Assign('type1',' checked');
            if($t->price_type == 2)Assign('type2', ' checked');
            if($t->price_type == 3)Assign('type3', ' checked');
            if($t->price_type == 4) {Assign('type4', ' checked'); Assign('price_status', ' disabled');}
            Assign('per_click',$t->per_click);
            Assign('price',$t->price);
            if($t->price_type == 4) Assign('price','');
            Assign('dateform1',CreateDateForm('b',$t->campaign_begin));
            Assign('dateform2',CreateDateForm('e',$t->campaign_end));
            break;
          case 'editcampaignname':
            $lines = File('templates/editcampaignname.htm');
            $lines = implode('',$lines);
            Assign('campaign_name',$t->campaign_name);
            break;
          case 'editcampaignlink':
            $lines = File('templates/editcampaignlink.htm');
            $lines = implode('',$lines);
            Assign('campaign_link',$t->campaign_link);
            break;
          case 'editcampaignprice':
            $lines = FIle('templates/editcampaignprice.htm');
            $lines = implode('',$lines);
            Assign('type1','');
            Assign('type2','');
            if($t->price_type == 1)Assign('type1',' checked');
            else Assign('type2',' checked');
            Assign('per_click',$t->per_click);
            Assign('price',$t->price);
            Assign('dateform1',CreateDateForm('b',$t->campaign_begin));
            Assign('dateform2',CreateDateForm('e',$t->campaign_end));
            break;
     endswitch;
    return ParseData($lines);
  }//Edit campaign settings
  function EditAction($pid){
    global $id;
    $t = new ActionsList;
    $t->Init($id,1);
    Assign('id',$id);
      switch ($pid):
         case 'editactiondata':
           $lines = File('templates/editactiondata.htm');
           $lines = implode('',$lines);
           Assign('action_name',$t->action_name);
           Assign('action_link',$t->action_link);
           Assign('type',$t->end_point == 2 ? 'End point' : 'Intermediate');
           Assign('code',str_replace("<","&lt;",GetActionCode($t->code)));
           Assign('price',$t->end_point == 1 ? '' : "<tr><td><b>Action income:</b></td><td>$t->action_price</td></tr>");
           break;
         case 'actioncode':
           $lines = File('templates/actioncode.htm');
           $lines = implode('',$lines);
           Assign('code',str_replace("<","&lt;",GetActionCode($t->code)));
           Assign('action_link',$t->action_link);
           break;
         case 'editaction':
           $lines = File('templates/editaction.htm');
           $lines = implode('',$lines);
           Assign('action_name',$t->action_name);
           Assign('action_link',$t->action_link);
           Assign('action_type',$t->end_point == 2 ? 'End point' : 'Intermediate');
           Assign('end_point',$t->end_point);
           Assign('action_income',$t->action_price);
           Assign('action_price',$t->action_price == '-' ? 0 : $t->action_price);
           Assign('end_point',$t->end_point == 2 ? ' checked' : '');
           Assign('intermediate',$t->end_point == 1 ? ' checked' : '');
           Assign('n',$t->end_point);
           break;
         case 'editactionname':
          $lines = File('templates/editactionname.htm');
          $lines = implode('',$lines);
          Assign('action_name',$t->action_name);
          break;
         case 'editactionlink':
          $lines = File('templates/editactionlink.htm');
          $lines = implode('',$lines);
          Assign('action_link',$t->action_link);
          break;
         case 'editactiontype':
          $lines = File('templates/editactiontype.htm');
          $lines = implode('',$lines);
          Assign('action_price',$t->action_price == '-' ? 0 : $t->action_price);
          Assign('end_point',$t->end_point == 2 ? ' checked' : '');
          Assign('intermediate',$t->end_point == 1 ? ' checked' : '');
          Assign('n',$t->end_point);
          break;
         case 'editactionincome':
          $lines = File('templates/editactionincome.htm');
          $lines = implode('',$lines);
          Assign('action_income',$t->action_price);
          break;
      endswitch;
   return ParseData($lines);
  }// you can edit your action

   function EditKeywords($pid){
    global $id;
       Assign('id',$id);
      switch ($pid):
       case 'editkeywords':
        $lines = ShowKeywordsEdit();
        break;
       case 'addkeywords':
        $lines = ShowKeywordsAdd();
        break;
      endswitch;
   return ParseData($lines);
   }//EditKeywords


  function FinishAdd()
  {
  global $id;
   $res = mysql_query("SELECT price_type FROM ROIcampaigns WHERE id=$id");
   $row = mysql_fetch_row($res);
   if ($row[0]==4) {$keyword=true;} else {$keyword=false;}
   return ParseData(ShowFinish($id,$keyword));
  }

  function DoCampaignAction($ch){
    global $id,$HTTP_POST_VARS;
     switch($ch){
       case 'campaign_name':
         if(IsEmpty($HTTP_POST_VARS['campaign_name']))pError('Campaign name is wrong','Campaign name must be greater than empty string');
         mysql_query("UPDATE ROIcampaigns SET campaign_name = '".$HTTP_POST_VARS['campaign_name']."' WHERE id = $id");
         break;
       case 'campaign_link':
         if(IsEmpty($HTTP_POST_VARS['campaign_link']))pError('Campaign link is wrong','Campaign link must be greater than empty string');
         mysql_query("UPDATE ROIcampaigns SET campaign_link = '".$HTTP_POST_VARS['campaign_link']."' WHERE id = $id");
         break;
       case 'campaign_price':
        if($HTTP_POST_VARS['price_type'] == 1){
          $per_click = $HTTP_POST_VARS['price'];
          if(IsEmpty($per_click) || !ROIis_digits($per_click)  && !ROIis_int($per_click) && !ROIis_float($per_click))pError('Price per click is wong!','Must be only digits and symbol $');
           $per_click = ROIchange($per_click);
           mysql_query("UPDATE ROIcampaigns SET price_type = 1,price = $per_click, per_click = $per_click WHERE id = $id");
        }
        else{
          $campaign_begin = date("m-d-Y", mktime(0,0,0,$HTTP_POST_VARS['bmonth'],$HTTP_POST_VARS['bday'],
                        $HTTP_POST_VARS['byear']) );
          $campaign_end = date("m-d-Y", mktime(0,0,0,$HTTP_POST_VARS['emonth'],
                        $HTTP_POST_VARS['eday'],$HTTP_POST_VARS['eyear']) );
          $price = $HTTP_POST_VARS['price'];
          if(IsEmpty($price) || !ROIis_digits($price) && !ROIis_int($price) && !ROIis_float($price))pError('Price is wong!','Must be only digits and symbol $');
          $price = ROIchange($price);
          mysql_query("UPDATE ROIcampaigns SET price_type = 2,price = $price,per_click = $price,campaign_begin = '$campaign_begin',campaign_end = '$campaign_end' WHERE id = $id");
        }
        break;
     }
     header("Location: campaigns.php?pid=editcampaign&id=$id");
     exit(1);
  }// Changing campaign settings
  function DoActionsAction($ch){
    global $id,$HTTP_POST_VARS;
     switch($ch){
       case 'action_name':
          if(IsEmpty($HTTP_POST_VARS['action_name']))pError('Action name is wrong','Action name must be greater than empty string');
          mysql_query("UPDATE ROIactions SET action_name = '".$HTTP_POST_VARS['action_name']."' WHERE id = $id");
          break;
       case 'action_link':
          if(IsEmpty($HTTP_POST_VARS['action_link']))pError('Action link is wrong','Action link must be greater than empty string');
          mysql_query("UPDATE ROIactions SET action_link = '".$HTTP_POST_VARS['action_link']."' WHERE id = $id");
          break;
       case 'action_type':
         $action_price = isset($HTTP_POST_VARS['action_price']) ? $HTTP_POST_VARS['action_price'] : 0;
         $action_type = $HTTP_POST_VARS['typer'];

         if($action_type == 2){
           if(IsEmpty($action_price) || !ROIis_digits($action_price) && !ROIis_int($action_price) && !ROIis_float($action_price))
                                        pError('Income price is wrong','They must be only digits and symbl $');
           $action_price = ROIchange($action_price);
           mysql_query("UPDATE ROIactions SET action_type=1,end_point=2,action_price=$action_price WHERE id = $id");
         }else{
           mysql_query("UPDATE ROIactions SET action_type = 2, end_point = 1 WHERE id = $id");
         }
         break;
       case 'action_income':
        $price = $HTTP_POST_VARS['action_income'];
        if(IsEmpty($price) || !ROIis_digits($price) && !ROIis_int($price) && !ROIis_float($price))
                           pError('Income price is wrong','Must be only digits and symbol $');
           $price = ROIchange($price);
          mysql_query("UPDATE ROIactions SET action_price = $price WHERE id = $id");
        break;
     };
        $es = mysql_query("SELECT campaign_id FROM ROIactions WHERE id = $id");
        $w = mysql_fetch_row($es);
     header("Location: campaigns.php?pid=editcampaign&id=$w[0]");
     exit(1);
  }
//ONTOP!!!
  $myerror = array();
  function addError($val){ global $myerror; $myerror[sizeof($myerror)] = $val; }
  function isError(){ global $myerror; if(sizeof($myerror)>0)return true; return false; }
  function outError(){ global $myerror; $rv = '<center><br><table border=0 cellspacing=1 cellpadding=1><tr><td>';
         foreach ( $myerror as $key => $val ){
          $rv .= "<img src=\"images/bullet.gif\" width=16 height=10 border=0 align=absmiddle><span class=title><font color=#A70000>$val</font></span><br>";
         }
         return $rv."</td></tr></table></center>";
  }

  function UpdateCampaign(){
     global $id,$HTTP_POST_VARS;

     $campaign_begin = mktime(0,0,0,$HTTP_POST_VARS['bmonth'],$HTTP_POST_VARS['bday'],$HTTP_POST_VARS['byear']);
     $campaign_end = mktime(0,0,0,$HTTP_POST_VARS['emonth'],$HTTP_POST_VARS['eday'],$HTTP_POST_VARS['eyear']);

     if(IsEmpty($HTTP_POST_VARS['campaign_name']))addError('Campaign name is wrong. Campaign name must be greater than empty string');
     if(IsEmpty($HTTP_POST_VARS['campaign_link']))addError('Campaign link is wrong. Campaign link must be greater than empty string');
      $price = $HTTP_POST_VARS['price'];
      $ptype = $HTTP_POST_VARS['price_type'];
      $pertype = $HTTP_POST_VARS['pertype'];
     if($ptype == 2 && $campaign_begin > $campaign_end)addError('Error time range.');

     if($ptype != 1 && $ptype != 2 && $ptype != 3 && $ptype != 4)$ptype = 1;

     if ($ptype != 4)
     {
     if(IsEmpty($price) || !ROIis_digits($price) && !ROIis_int($price) && !ROIis_float($price))addError('Price is wong! Must be only digits and symbol $');
     $price = ROIchange($price);
     }
     else { $price = ''; }

     if(isError())return outError().EditCampaign('editcampaignform');
     if($ptype == 2)mysql_query("UPDATE ROIcampaigns SET pertype = $pertype, price_type = $ptype,price = $price,per_click = $price,campaign_begin = FROM_UNIXTIME($campaign_begin),campaign_end = FROM_UNIXTIME($campaign_end) WHERE id = $id");
     else{
       mysql_query("UPDATE ROIcampaigns SET pertype = $pertype, price_type = $ptype,price = $price,per_click = $price WHERE id = $id");
     }
     mysql_query("UPDATE ROIcampaigns SET campaign_name = '".$HTTP_POST_VARS['campaign_name']."' WHERE id = $id");
     mysql_query("UPDATE ROIcampaigns SET campaign_link = '".$HTTP_POST_VARS['campaign_link']."' WHERE id = $id");
     header("Location: campaigns.php?pid=editcampaign&id=$id");
     exit(1);
  }
  function UpdateAction(){
     global $id,$HTTP_POST_VARS;
     if(IsEmpty($HTTP_POST_VARS['action_name']))addError('Action name is wrong. Action name must be greater than empty string');
     if(IsEmpty($HTTP_POST_VARS['action_link']))addError('Action link is wrong. Action link must be greater than empty string');

     $action_price = isset($HTTP_POST_VARS['action_price']) ? $HTTP_POST_VARS['action_price'] : 0;
     $action_type = $HTTP_POST_VARS['typer'];
     $end_point = $action_type == 2 ? 2 : 1;

     if(IsEmpty($action_price) || !ROIis_digits($action_price) && !ROIis_int($action_price) && !ROIis_float($action_price))
                                      addError('Income price is wrong. They must be only digits and symbl $');
     $action_price = ROIchange($action_price);

     if(isError())return ourError();

     mysql_query("UPDATE ROIactions SET action_type=$action_type,end_point=$end_point,action_price=$action_price WHERE id = $id");
     mysql_query("UPDATE ROIactions SET action_name = '".$HTTP_POST_VARS['action_name']."' WHERE id = $id");
     mysql_query("UPDATE ROIactions SET action_link = '".$HTTP_POST_VARS['action_link']."' WHERE id = $id");
     $es = mysql_query("SELECT campaign_id FROM ROIactions WHERE id = $id");
     $w = mysql_fetch_row($es);
     header("Location: campaigns.php?pid=editcampaign&id=$w[0]");
     exit(1);
  }

  if(isset($HTTP_GET_VARS['ch']))$ch = $HTTP_GET_VARS['ch'];
  else if(isset($HTTP_POST_VARS['ch']))$ch = $HTTP_POST_VARS['ch'];

  if(isset($ch)){
     switch($ch){
       case 'update_campaign':
         ShowTemplate(UpdateCampaign());
         break;
       case 'update_action':
         ShowTemplate(UpdateAction());
         break;
       case 'campaign_name':
       case 'campaign_link':
       case 'campaign_price':
         DoCampaignAction($ch);
         break;
       case 'action_income':
       case 'action_link':
       case 'action_name':
       case 'action_type':
         DoActionsAction($ch);
         break;
       case 'removecampaign':
        Remove();
        break;
     }
  }

  if(isset($HTTP_GET_VARS['pid']))$pid = $HTTP_GET_VARS['pid'];
  else if(isset($HTTP_POST_VARS['pid']))$pid = $HTTP_POST_VARS['pid'];


  switch ($pid):
    case '':
         ShowTemplate(Show());
         break;
    case 'actions':
         ShowTemplate(ShowActions());
         break;
    case 'editcampaign':
    case 'editcampaignform':
    case 'editcampaignname':
    case 'editcampaignlink':
    case 'editcampaignprice':
    case 'campaigncode':
         ShowTemplate(EditCampaign($pid));
         break;
    case 'editactiondata':
    case 'editaction':
    case 'editactionname':
    case 'editactionlink':
    case 'editactiontype':
    case 'editactionincome':
    case 'actioncode':
         ShowTemplate(EditAction($pid));
         break;
    case 'editkeywords':
    case 'addkeywords':
         ShowTemplate(EditKeywords($pid));
         break;
    case 'showlist':
         ShowTemplate(ShowListing($pid));
         break;
    case 'addkeywords_do':
         AddKeywordsDo();
         break;
    case 'finish':
         ShowTemplate(FinishAdd());
         break;
  endswitch;

?>
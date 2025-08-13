<?php
/**********************************
* Last modified: 30/12/2003
**********************************/

// Base class for this script
class General{
  function GetLeadsClientsCount($id,$point){
     global $begin,$end , $uid;
     if($point == 1)return $this->GetLeadsCount($id);

       $res = mysql_query("SELECT id FROM ROIactions WHERE campaign_id = $id && end_point = 2");

       $item = 0;
       while($row = mysql_fetch_row($res)){
         $res1 = mysql_query("SELECT count(action_id) FROM ROIlogs WHERE action_id = $row[0] && UNIX_TIMESTAMP(tm) >= $begin && UNIX_TIMESTAMP(tm) <= $end");
         $tmp = mysql_fetch_row($res1);
         $item += isset($tmp[0]) ? $tmp[0] : 0 ;
       }

   return $item;
  }


  function GetLeadsClientsCount2($id,$point){
     global $begin,$end , $uid;
       $item = 0;
         $res1 = mysql_query("SELECT count(action_id) FROM ROIlogs WHERE action_id = $id && UNIX_TIMESTAMP(tm) >= $begin && UNIX_TIMESTAMP(tm) <= $end");
         $tmp = mysql_fetch_row($res1);
         $item += isset($tmp[0]) ? $tmp[0] : 0 ;

   return $item;
  }

  function GetLeadsCount($id){
     global $begin,$end, $uid;
     $res = mysql_query("SELECT id FROM ROIactions WHERE campaign_id = $id && end_point = 1");
    $item = 0;
     while($row = mysql_fetch_row($res))
        {
         $item += $this->ActionUnique($row[0]);
        }
   return $item;
  }
  function VisitorsCount($id){
     global $begin,$end, $uid;
     $count = 0;
     $res1 = mysql_query("SELECT count(*) FROM ROIclicks WHERE campaign_id = $id && UNIX_TIMESTAMP(tm) >= $begin && UNIX_TIMESTAMP(tm) <= '$end'");
     $tmp = mysql_fetch_row($res1);
    return isset($tmp[0]) ? $tmp[0] : 0;
  }

function GeneralExp($id){
 global $interval,$tp, $uid;
 global $begin,$end;

$res = mysql_query("SELECT UNIX_TIMESTAMP(campaign_begin) as beg,UNIX_TIMESTAMP(campaign_end) as end, UNIX_TIMESTAMP(created) as c, price_type,price,per_click,pertype FROM ROIcampaigns WHERE id = $id");
$row = mysql_fetch_array($res);

$begin_day = floor($begin/86400);
$end_day = floor($end/86400);

$begin_dt = date("Y-m-d", $begin);
$end_dt = date("Y-m-d", $end);

if(!isset($row[0]))return 0;

if($row['price_type'] == 1)
 return $this->VisitorsCount($id) * $row['price'];
else
 if($row['price_type'] == 3){
       switch($row['pertype']){
         case 2:
              $p = 7;
              break;
         case 3:
              $p = 30;
              break;
         case 4:
              $p = 90;
              break;
         case 5:
              $p = 365;
              break;
         default:
          $p = 1;
          break;
       }
       $s = $row['price']/$p;

 $s_day = floor($row['c']/86400);
 $e_day = floor(time()/86400);

 if ($s_day < $begin_day) { $s_day = $begin_day; }
 if ($e_day > $end_day) { $e_day = $end_day; }


 $day_count = $e_day - $s_day;


 if ($day_count < 0) { $day_count = 0; }

   return $s*($day_count);


     }
else
if ($row['price_type']==2)
{

        $s_day = floor($row['beg']/86400);
        $e_day = floor(time()/86400);

        if ($e_day > floor($row['end']/86400)) { $e_day = floor($row['end']/86400); }

 if ($s_day < $begin_day) { $s_day = $begin_day; }
 if ($e_day > $end_day) { $e_day = $end_day; }

        $day_count = $e_day - $s_day;
        if ($day_count < 0) { $day_count = 0; }

        $interval_z = floor($row['end']/86400) - $s_day;
        $s = $row['price']/$interval_z;
        return $s;
}
else
{
 $total = 0;
 $res = mysql_query("SELECT id, keyword_price FROM ROIkeywords WHERE campaign_id = $id");
 while ($row = mysql_fetch_row($res))
 {
  $total += $row[1]*$this->GetKeywordVisitors($row[0]);
 }
 return $total;
}

}


  function GeneralInc($id){
     global $uid;
     $rv = 0;
     $res = mysql_query("SELECT id,action_price FROM ROIactions WHERE user_id = $uid && campaign_id = $id && end_point = 2");
     while( $row = mysql_fetch_array($res)){
       $rv += $this->GetACount($row['id']) * $row['action_price'];
     }
    return $rv;
  }
  function GetACount($id){
     global $begin,$end, $uid;
     $res = mysql_query("SELECT count(action_id) FROM ROIlogs WHERE action_id = $id && UNIX_TIMESTAMP(tm) >= $begin && UNIX_TIMESTAMP(tm) <= $end");
     $row = mysql_fetch_row($res);
   return isset($row[0]) ? $row[0] : 0;
  }
  function ActionIncome($id){
     global $begin,$end, $uid;
     $res = mysql_query("SELECT action_price FROM ROIactions WHERE id = $id && end_point = 2");
     $row = mysql_fetch_row($res);
     $price = isset($row[0]) ? $row[0] : 0;
     $res1 = mysql_query("SELECT count(action_id) FROM ROIlogs WHERE action_id = $id && UNIX_TIMESTAMP(tm) >= $begin && UNIX_TIMESTAMP(tm) <= $end");
     $row1 = mysql_fetch_row($res1);
     $count = isset($row1[0]) ? $row1[0] : 0 ;
    return $price*$count;
  }
  function ActionLeads($id){
    global $begin,$end, $uid;
    return $this->ActionUnique($id);
    $res = mysql_query("SELECT count(action_id) FROM ROIlogs WHERE action_id = $id && UNIX_TIMESTAMP(tm) >= $begin && UNIX_TIMESTAMP(tm) <= $end");
    $row = mysql_fetch_row($res);
   return isset($row[0]) ? $row[0] : 0;
  }
  function CampaignName($id){
     global $uid;
     $res = mysql_query("SELECT campaign_name FROM ROIcampaigns WHERE id = $id");
     $row = mysql_fetch_row($res);
    return isset($row[0]) ? $row[0] : 'No Name';
  }
  function ActionName($id){
     global $uid;
     $res = mysql_query("SELECT action_name FROM ROIactions WHERE user_id = $uid && id = $id");
     $row = mysql_fetch_row($res);
    return isset($row[0]) ? $row[0] : 'No Name';
  }
  function ActionUnique($id){
    global $begin,$end, $uid;
   $res = mysql_query("SELECT count(ip) FROM ROIlogs WHERE action_id = $id && UNIX_TIMESTAMP(tm) >= $begin && UNIX_TIMESTAMP(tm) <= $end GROUP BY ip");
   $k = mysql_num_rows($res);
   mysql_free_result($res);
   return $k;
  }
  function Unique(&$id){
    global $begin,$end, $uid;
   $res = mysql_query("SELECT count(ip) FROM ROIclicks WHERE campaign_id = $id && UNIX_TIMESTAMP(tm) >= $begin && UNIX_TIMESTAMP(tm) <= $end GROUP BY ip");
   $k = mysql_num_rows($res);
   mysql_free_result($res);
   return $k;
  }
  function GetCampaignByAction($id){
     global $uid;
     $res = mysql_query("SELECT campaign_id FROM ROIactions WHERE id=$id");
     $row = mysql_fetch_row($res);
    return isset($row[0]) ? $row[0] : 0;
  }

  function GetKeywordName($id){
     $res = mysql_query("SELECT keyword_name FROM ROIkeywords WHERE id = $id");
     $row = mysql_fetch_row($res);
     return $row[0];
  }

  function GetListingName($id){
     $res = mysql_query("SELECT listing_name FROM ROIlistings WHERE id = $id");
     $row = mysql_fetch_row($res);
     return $row[0];
  }

 function GetKeywordVisitors($id)
 {
  global $begin, $end;
  $res = mysql_query("SELECT count(*) FROM ROIclicks WHERE keyword_id = $id && UNIX_TIMESTAMP(tm) >= $begin && UNIX_TIMESTAMP(tm) <= $end");
  $row = mysql_fetch_row($res);
  return $row[0];
 }

 function GetKeywordClients($id)
 {
  global $begin, $end;
  $total = 0;
  $campaign_id = $this->GetCampaignByKeyword($id);

  $res = mysql_query("SELECT id FROM ROIactions WHERE campaign_id = $campaign_id && end_point = 2");
  while ($row = mysql_fetch_row($res))
  {
   $action_id = $row[0];
   $res0 = mysql_query("SELECT count(*) FROM ROIlogs WHERE action_id = $action_id && keyword_id = $id && UNIX_TIMESTAMP(tm) >= $begin && UNIX_TIMESTAMP(tm) <= $end");
   $row0 = mysql_fetch_row($res0);
   $total += $row0[0];
  }
  return $total;
 }

 function GetKeywordLeads($id)
 {
  global $begin, $end;
  $total = 0;
  $campaign_id = $this->GetCampaignByKeyword($id);

  $res = mysql_query("SELECT id FROM ROIactions WHERE campaign_id = $campaign_id && end_point = 1");
  while ($row = mysql_fetch_row($res))
  {
   $action_id = $row[0];
   $res0 = mysql_query("SELECT count(*) FROM ROIlogs WHERE action_id = $action_id && keyword_id = $id && UNIX_TIMESTAMP(tm) >= $begin && UNIX_TIMESTAMP(tm) <= $end");
   $row0 = mysql_fetch_row($res0);
   $total += $row0[0];
  }
  return $total;
 }

 function GetKeywordIncome($id)
 {
  global $begin, $end;
  $total = 0;
  $res = mysql_query("SELECT action_id, count(action_id) FROM ROIlogs WHERE keyword_id=$id && UNIX_TIMESTAMP(tm) >= $begin && UNIX_TIMESTAMP(tm) <= $end GROUP BY action_id;");
  while ($row = mysql_fetch_row($res))
  {
   $res0 = mysql_query("SELECT action_price FROM ROIactions WHERE id=$row[0]");
   $row0 = mysql_fetch_row($res0);
   $total += $row0[0]*$row[1];
  }

  return $total;
 }

 function GetCampaignByKeyword($id)
 {

  $res = mysql_query("SELECT campaign_id FROM ROIkeywords WHERE id=$id");
  $row = mysql_fetch_row($res);
  return $row[0];
 }
};


// General report
class GeneralReport extends General{
  var $id,
      $campaign_name,
      $visitors,
      $leads,
      $clients,
      $general_expenditure,
      $general_income;
  function Init($id){
     $this->id = $id;
     $this->campaign_name = $this->CampaignName($id);
     $this->visitors = $this->VisitorsCount($id);
     $this->leads = $this->GetLeadsClientsCount($id,1);
     $this->clients = $this->GetLeadsClientsCount($id,2);
     $this->general_expenditure = '$'.round($this->GeneralExp($id),ADOT);
     $this->general_income = '$'.round($this->GeneralInc($id),ADOT);
  }
};
// General Item Report
class GeneralItem extends General{
  var $action_name,$leads,$income;
  function Init($id){
    $this->action_name = $this->ActionName($id);
    $this->leads = $this->ActionUnique($id);
    $this->income = '$'.$this->ActionIncome($id);
  }
  function InitOne($id){
    $this->action_name = $this->ActionName($id);
    $this->leads = $this->GetACount($id);
    $this->income = '$'.$this->ActionIncome($id);
  }
};
//Financial report
class GeneralFinancial extends General{

  var $id,$campaign_name,$general_expenditure,$general_income,$expenditure_per_client,$income_per_client,$roi, $expenditure_per_lead, $expenditure_per_visitor;
  function Init($id){
    $this->id = $id;
    $res = mysql_query("SELECT price FROM ROIcampaigns WHERE user_id = $uid && id = $id");
    $row = mysql_fetch_row($res);
    $price = isset($row[0]) ? $row[0] : 0;
    $this->campaign_name = $this->CampaignName($id);
    $this->general_expenditure = '$'.round($this->GeneralExp($id),ADOT);
    $this->general_income = '$'.round($this->GeneralInc($id),ADOT);
    $this->expenditure_per_client = '$'.round($this->GeneralExp($id)/$this->GetLeadsClientsCount($id,2),ADOT);
    $this->income_per_client = '$'.round($this->GeneralInc($id) / $this->GetLeadsClientsCount($id,2),ADOT);
    $this->roi = round($this->GeneralInc($id)/$this->GeneralExp($id)*100,ADOT);
    $this->expenditure_per_lead = '$'.round($this->GeneralExp($id)/$this->GetLeadsClientsCount($id,1),ADOT);
    $this->expenditure_per_visitor = '$'.round($this->GeneralExp($id)/$this->VisitorsCount($id),ADOT);
  }

}

class FinancialItem extends General{
  var $action_name,
      $expenditure_per_lead,
      $general_income,
      $expenditure_per_client,
      $income_per_client,
      $roi;

  function Init($id){
     $campaign_id = $this->GetCampaignByAction($id);
     $res = mysql_query("SELECT action_price FROM ROIactions WHERE id = $id");
     $row = mysql_fetch_row($res);
     $income_pc = isset($row[0]) ? $row[0] : 0;
     $this->action_name = $this->ActionName($id);
     $this->expenditure_per_lead = '$'.round($this->GeneralExp($campaign_id) / $this->ActionUnique($id),2);
     $this->general_income =  '$'.$this->ActionIncome($id);
     $this->expenditure_per_client = '$'.round($this->GeneralExp($campaign_id) / $this->GetLeadsClientsCount2($id,2));

     $this->income_per_client = '$'.$income_pc;
     $this->roi = round($this->ActionIncome($id)/$this->GeneralExp($campaign_id)*100,ADOT);
  }
}

//visitors report class
class Visitors extends General{
  var $id,
      $campaign_name,
      $unique,
      $total,
      $leads,
      $clients,
      $visitor_to_lead,
      $visitor_to_client,
      $price_per_each_lead,
      $price_per_each_client;

  function Init($id){
    $this->id = $id;
    $this->campaign_name = $this->CampaignName($id);
    $this->unique = $this->Unique($id);
    $this->total  = $this->VisitorsCount($id);
    $this->leads  = $this->GetLeadsClientsCount($id,1);
    $this->clients = $this->GetLeadsClientsCount($id,2);
    $this->visitor_to_lead = round($this->leads/$this->total,ADOT) * 100;
    $this->visitor_to_lead .= '%';

    $this->visitor_to_client = round($this->clients/$this->total,ADOT) * 100;
    $this->visitor_to_client .= '%';

    $this->price_per_each_lead = '$'.round($this->GeneralExp($id)/$this->leads,ADOT);
    $this->price_per_each_client = '$'.round($this->GeneralExp($id)/$this->clients,ADOT);
  }

}

class VisitorsItem extends General{
  var $action_name,
      $items,
      $visitor_to_items,
      $price;
  function Init($id,$point){
      $campaign_id = $this->GetCampaignByAction($id);
      $this->action_name = $this->ActionName($id);
      $this->items = $point == 1 ? $this->ActionLeads($id) : $this->GetACount($id);

      if ($point == 1)
        {
          $this->visitor_to_items = round(($this->items/$this->VisitorsCount($campaign_id)),2)*100;
        }
      else
        {
          $this->visitor_to_items = round(($this->items/$this->VisitorsCount($campaign_id)),2)*100;
        }

       $this->price = '$'.round(($this->GeneralExp($campaign_id)/$this->items),2);

  }
}

class ROIreport extends General{
  var $campaign_name,
      $general_expenditure,
      $general_income,
      $roi;
  function Init($id){
     $this->campaign_name = $this->CampaignName($id);
     $this->general_expenditure = round($this->GeneralExp($id),ADOT);
     $this->general_income = round($this->GeneralInc($id),ADOT);
     $this->roi = round($this->general_income/$this->general_expenditure,ADOT)*100;

  }
}

class GeneralKeywords extends General{
  var $id,
      $campaign_name;
  function Init($id){
     $this->id = $id;
     $this->campaign_name = $this->CampaignName($id);
     $this->visitors = $this->VisitorsCount($id);
     $this->clients = $this->GetLeadsClientsCount($id,2);
     $this->leads = $this->GetLeadsClientsCount($id,1);
     $this->expenditure = round($this->GeneralExp($id),ADOT);
     $this->income = round($this->GeneralInc($id),ADOT);
     $this->roi =  round($this->GeneralInc($id)/$this->GeneralExp($id),ADOT)*100;

  }
};

class KeywordsReport extends General{
  var $id,
      $campaign_name;
  function Init($id){
     $this->id = $id;
     $this->campaign_name = $this->CampaignName($id);
     $this->visitors = $this->VisitorsCount($id);
     $this->clients = $this->GetLeadsClientsCount($id,2);
     $this->leads = $this->GetLeadsClientsCount($id,1);
     $this->expenditure = round($this->GeneralExp($id),ADOT);
     $this->income = round($this->GeneralInc($id),ADOT);
     $this->roi =  round($this->GeneralInc($id)/$this->GeneralExp($id),ADOT)*100;
  }
};

class ListingItem extends General{
  var $id;
  function Init($id, $campaign_id){
    $this->listing_name = $this->GetListingName($id);
    $res = mysql_query("SELECT id,keyword_price FROM ROIkeywords WHERE listing_id = $id");
    while ($row = mysql_fetch_row($res))
     {
      $this->visitors += $this->GetKeywordVisitors($row[0]);
      $this->clients += $this->GetKeywordClients($row[0]);
      $this->leads += $this->GetKeywordLeads($row[0]);
      $this->expenditure += $this->GetKeywordVisitors($row[0])*$row[1];
      $this->income += $this->GetKeywordIncome($row[0]);
     }
   $this->id = $id;
   $this->roi = round($this->income/$this->expenditure,ADOT)*100;
  }
}

class Keyword extends General{
  var $id,
      $campaign_name;
  function Init($id,$price){
     $this->id = $id;
     $this->keyword_name = $this->GetKeywordName($id);
     $this->visitors = $this->GetKeywordVisitors($id);
     $this->clients = $this->GetKeywordClients($id);
     $this->leads = $this->GetKeywordLeads($id);
     $this->expenditure = $this->GetKeywordVisitors($id)*$price;
     $this->income = $this->GetKeywordIncome($id);
     $this->roi =  round($this->income/$this->expenditure*100);
  }
};

function Get_CampaignCode($id){
  global $HTTP_SEVER_VARS, $uid;
  $res = mysql_query("SELECT campaign_code FROM ROIcampaigns WHERE user_id = $uid && id = $id");
  $row = mysql_fetch_row($res);
  $fullpath = '';
  $path = explode( '/', $HTTP_SERVER_VARS['SCRIPT_NAME'] );
  for( $i = 0; $i < sizeof($path) - 1; $i++ )$fullpath .= "$path[$i]/";
  $fullpath = substr($fullpath,0,strlen($fullpath) - 1);
 return "http://".$HTTP_SERVER_VARS['SERVER_NAME']."$fullpath/click.php?c".$row[0];
}

function Get_ActionCode($id){
  global $HTTP_SEVER_VARS, $uid;
  $res = mysql_query("SELECT action_code FROM ROIactions WHERE user_id = $uid && id = $id");
  $row = mysql_fetch_row($res);
  $fullpath = '';
  $path = explode( '/', $HTTP_SERVER_VARS['SCRIPT_NAME'] );
  for( $i = 0; $i < sizeof($path) - 1; $i++ )$fullpath .= "$path[$i]/";
  $fullpath = substr($fullpath,0,strlen($fullpath) - 1);

 return "<iframe src=http://".$HTTP_SERVER_VARS['SERVER_NAME']."$fullpath/click.php?a".$row[0]." width=0 height=0 marginwidth=0 marginheight=0 align=left scrolling="no" frameborder=0></iframe>";
}


?>
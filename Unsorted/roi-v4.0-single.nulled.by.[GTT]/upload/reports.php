<?php
/**********************************
* Last modified: 30/12/2003
**********************************/

include("init.php");
include("structures.php");
include("smalltemplater.php");

$uid = $_SESSION['uid'];
$dt = array('bmonth','bday','byear','emonth','eday','eyear','interval','tp','pid');

if(isset($HTTP_POST_VARS['id']))$HTTP_GET_VARS['id'] = $HTTP_POST_VARS['id'];
$id = isset($HTTP_GET_VARS['id']) ? $HTTP_GET_VARS['id'] : 0;

foreach($dt as $num => $key){
  $$key = isset($HTTP_POST_VARS[$key]) ? $HTTP_POST_VARS[$key] : 1;
}

 $begin = mktime(0,0,0,$bmonth,$bday,$byear);
 $end = mktime(0,0,0,$emonth,$eday,$eyear);

if($tp == '1')Interval($interval);

function Interval($interval){
  global $begin,$end, $uid;
  $t = time();
  $day = date("d",$t);
  $mon = date("m",$t);
  $year = date("Y",$t);

  $res = mysql_query("SELECT UNIX_TIMESTAMP(MIN(tm)) FROM ROIclicks WHERE user_id = $uid");
  $row = mysql_fetch_row($res);

  $begin = isset($row[0]) ? $row[0] : mktime(0,0,0,$mon,$day,$year);

  $end = mktime(0,0,0,$mon,$day+1,$year);

  switch ($interval):
    case 0:
    case 1:
         $end = time()+100000000;
         $year = 1970;
         break;
    case 2:
         break;
    case 3:
         $end = mktime(0,0,0,$mon,$day,$year);
         $day = $day-1;
         break;
    case 4:
         $day -= 6;
         break;
    case 5:
         $wday = date("w",$t);
         $day -= $wday-1;
         break;
    case 6:
         $eday = date("w",$t);
         $end = mktime(0,0,0,$mon,$day - $eday,$year);
         $day = ($day - 7) - $eday;
         break;
    case 7:
         $day = 1;
         break;
    case 8:
         $day = 1;
         $mon -= 1;
         $end = mktime(0,0,0,date("m"),1,date('Y'));
         break;
  endswitch;

  $begin = mktime(0,0,0,$mon,$day,$year);
#  echo "BEGIN=".date("Y-m-d",$begin).", END= ".date("Y-m-d",$end)."<br>";
}

Assign('image_label','label-reports.gif');



/************** FUNCTIONS ***************/

//visitors report
function VisitorsReport(){
  global $pid, $uid;
  $res = mysql_query("SELECT id FROM ROIcampaigns WHERE user_id = $uid");
  $report = array();
  $n = 0;
  $sume = new Visitors;
  $sume->id = "";
  $sume->campaign_name = "</a><b>Total</b><a>";
  $sume->unique = 0;
  $sume->total = 0;
  $sume->leads = 0;
  $sume->clients = 0;
  $sume->visitor_to_lead = 0;
  $sume->visitor_to_client = 0;
  $sume->price_per_each_lead = 0;
  $sume->price_per_each_client = 0;

  while($row = mysql_fetch_row($res)){
     $report[$n] = new Visitors;
     $report[$n]->Init($row[0]);
     $sume->unique += $report[$n]->unique;
     $sume->total += $report[$n]->total;
     $sume->leads += $report[$n]->leads;
     $sume->clients += $report[$n]->clients;
     $sume->price_per_each_lead += substr($report[$n]->price_per_each_lead,1);
     $sume->price_per_each_client += substr($report[$n]->price_per_each_client,1);
     $n++;
  }
  $sume->unique = "<b>$sume->unique</b>";
  $sume->total = "<b>$sume->total</b>";
  $sume->leads = "<b>$sume->leads</b>";
  $sume->clients = "<b>$sume->clients</b>";;
  $sume->visitor_to_lead = '<b>-</b>';
  $sume->visitor_to_client = '<b>-</b>';
  $sume->price_per_each_lead = "<b>$$sume->price_per_each_lead</b>";
  $sume->price_per_each_client = "<b>$$sume->price_per_each_client</b>";
  if($n > 1)$report[$n++] = $sume;

  if($pid == 'csvvisitors'){
   $str = '';
   for($i = 0; $i < count($report); $i++){
     $t = &$report[$i];
     $str .= $t->unique;
     $str .= ';';
     $str .= $t->total;
     $str .= ';';
     $str .= $t->leads;
     $str .= ';';
     $str .= $t->visitor_to_lead;
     $str .= ';';
     $str .= $t->visitor_to_client;
     $str .= ';';
     $str .= $t->price_per_each_lead;
     $str .= ';';
     $str .= $t->price_per_each_client;
     $str .= "\n";
   }
    return CreateCSVFile('Visitors-Report',$str);
  }else if($pid == 'visitorsprint'){
    echo VisitorsTemplate_4print($report);
    exit(1);
  }
 return VisitorsTemplate($report);
}
// visitors item report
function VisitorsItems(){
  global $HTTP_GET_VARS,$pid, $uid;
  if(!isset($HTTP_GET_VARS['id']))pError("Error camaign Id","Not entered campaign ID");
  $id = $HTTP_GET_VARS['id'];
  $res = mysql_query("SELECT id FROM ROIactions WHERE campaign_id = $id && end_point = 1");

  $item[0] = new Visitors;
  $item[0]->Init($id);

  $n = 0;
  while($row = mysql_fetch_row($res)){
       $lead[$n] = new VisitorsItem;
       $lead[$n++]->Init($row[0],1);
  }

  $res1 = mysql_query("SELECT id FROM ROIactions WHERE campaign_id = $id && end_point = 2");

  $n = 0;
  while($row1 = mysql_fetch_row($res1)){
       $client[$n] = new VisitorsItem;
       $client[$n++]->Init($row1[0],2);
  }

  if($pid == 'csvvisitorsitem_lead' || $pid == 'csvvisitorsitem_client'){
      $str = '';
      $fname = 'Visitors-Report_Clients';
      if($pid == 'csvvisitorsitem_lead'){$ar = $lead; $fname = 'Visitors-Report_Leads'; }
      else{ $ar = $client; }

      for($i = 0; $i < count($ar); $i++){
         $t = $ar[$i];
         $str .= $r->items;
         $str .= ';';
         $str .= $r->visitor_to_items;
         $str .= ';';
         $str .= $r->price;
         $str .= "\n";
      }
    return CreateCSVFile($fname,$str);
  }else if($pid == 'visitorsprint_lead'){
     echo VisitorItemLeads_4print($lead);
     exit(1);
  }else if($pid == 'visitorsprint_client'){
     echo VisitorsItemClients_4print($client);
     exit(1);
  }
 return VisitorsItemTemplate($item,$lead,$client);
}

//financial report
function Financial(){
  global $pid, $uid;
  $res = mysql_query("SELECT id FROM ROIcampaigns WHERE user_id = $uid");
  $report = array();
  $n = 0;
  $sume = new GeneralFinancial;
  $sume->campaign_name = "</a><b>Total</b><a>";
  $sume->general_expenditure = 0;
  $sume->general_income = 0;
  $sume->expenditure_per_client = 0;


  $sume->income_per_client = 0;
  $sume->roi = 0;
  while($row = mysql_fetch_row($res)){
     $report[$n] = new GeneralFinancial;
     $report[$n]->Init($row[0]);
     $sume->general_expenditure += substr($report[$n]->general_expenditure,1);
     $sume->general_income += substr($report[$n]->general_income,1);
     $sume->roi += $report[$n]->roi;
     $n++;
  }

$sume->roi = round($sume->roi / $n,2);
$sume->general_income = '$'.$sume->general_income;
$sume->general_expenditure = '$'.$sume->general_expenditure;

  $sume->general_expenditure = "<b>$sume->general_expenditure</b>";
  $sume->general_income = "<b>$sume->general_income</b>";
  $sume->expenditure_per_client = "<b>-</b>";
  $sume->expenditure_per_lead = "<b>-</b>";
  $sume->expenditure_per_visitor = "<b>-</b>";

  $sume->income_per_client = "-";
  $sume->roi = "<b>$sume->roi</b>";
  if($n > 0)$report[$n++] = $sume;

  if($pid == 'financialprint'){
   echo GeneralFinancial_4print($report);
   exit(1);
  }
 return GeneralFinancial($report);
}
function FinancialItem(){
  global $HTTP_GET_VARS,$pid, $uid;
  if(!isset($HTTP_GET_VARS['id']))pError("Error camaign Id","Not entered campaign ID");
  $id = $HTTP_GET_VARS['id'];
  $res = mysql_query("SELECT id FROM ROIactions WHERE campaign_id = $id && end_point = 1");
  $n = 0;

  $item[0] = new GeneralFinancial;
  $item[0]->Init($id);

  while($row = mysql_fetch_row($res)){
       $lead[$n] = new FinancialItem;
       $lead[$n]->Init($row[0]);
       $n++;
  }

  $res1 = mysql_query("SELECT id FROM ROIactions WHERE campaign_id = $id && end_point = 2");
  $n = 0;

  while($row1 = mysql_fetch_row($res1)){
       $client[$n] = new FinancialItem;
       $client[$n]->Init($row1[0]);
       $n++;
  }
  if($pid == 'financialprint_lead'){
    echo GeneralFinancialItem_intermediate_4print($lead);
    exit(1);
  }else if($pid == 'financialprint_client'){
    echo GeneralFinancialItem_endpoint_4print($client);
   exit(1);
  }
 return GeneralFinancialItemTemplate($item,$lead,$client);
}
//end of financial report

function CSVFinancial(){
  global $uid;
  $res = mysql_query("SELECT id FROM ROIcampaigns WHERE user_id = $uid");
  $report = array();
  $n = 0;
  $sume = new GeneralFinancial;
  $sume->campaign_name = "Total";
  $sume->general_expenditure = 0;
  $sume->general_income = 0;
  $sume->expenditure_per_client = 0;
  $sume->income_per_client = 0;
  $sume->roi = 0;
  while($row = mysql_fetch_row($res)){
     $report[$n] = new GeneralFinancial;
     $report[$n]->Init($row[0]);
     $sume->general_expenditure += $report[$n]->general_expenditure;
     $sume->general_income += $report[$n]->general_income;
     $sume->expenditure_per_client += $report[$n]->expenditure_per_client;
     $sume->income_per_client += $report[$n]->income_per_client;
     $sume->roi += $report[$n]->roi;
     $n++;
  }

  if($n > 0)$report[$n++] = $sume;

  $str = '';
  for($i = 0; $i < count($report); $i++){
    $r = $report[$i];
    $str .= $r->campaign_name;
    $str .= ';';
    $str .= $r->general_expenditure;
    $str .= ';';
    $str .= $r->general_income;
    $str .= ';';
    $str .= $r->expenditure_per_client;
    $str .= ';';
    $str .= $r->income_per_client;
    $str .= ';';
    $str .= $r->roi;
    $str .= "\n";
  }
 return CreateCSVFile('Financial-Report',$str);
}
function CSVFinancialItem($param){
  global $HTTP_GET_VARS, $uid;
  if(!isset($HTTP_GET_VARS['id']))pError("Error camaign Id","Not entered campaign ID");
  $id = $HTTP_GET_VARS['id'];
 if($param == 0){
  $res = mysql_query("SELECT id FROM ROIactions WHERE user_id = $uid && campaign_id = $id && end_point = 1");
  $n = 0;

  $item[0] = new GeneralFinancial;
  $item[0]->Init($id);

  while($row = mysql_fetch_row($res)){
       $lead[$n] = new FinancialItem;
       $lead[$n]->Init($row[0]);
       $n++;
  }
   $str = '';
   for($i = 0; $i < count($lead); $i++){
      $r = $lead[$i];
      $str .= $r->action_name;
      $str .= ';';
      $str .= $r->expenditure_per_lead;
      $str .= "\n";
   }
  return CreateCSVFile('Financial-Report_Leads',$str);
 }else{
  $res1 = mysql_query("SELECT id FROM ROIactions WHERE user_id = $uid && campaign_id = $id && end_point = 2");
  $n = 0;

  while($row1 = mysql_fetch_row($res1)){
       $client[$n] = new FinancialItem;
       $client[$n]->Init($row1[0]);
       $n++;
  }
   $str = '';
   for($i = 0; $i < count($client); $i++){
      $r = $client[$i];
      $str .= $r->action_name;
      $str .= ';';
      $str .= $r->general_income;
      $str .= ';';
      $str .= $r->expenditure_per_client;
      $str .= ';';
      $str .= $r->income_per_client;
      $str .= ';';
      $str .= $r->roi;
      $str .= "\n";
   }
  return CreateCSVFile('Financial-Report_Clients',"$str");
 }
}
//ROI report


function ROIreport(){
  global $pid, $uid;
  $res = mysql_query("SELECT id FROM ROIcampaigns WHERE user_id = $uid");
  $report = array();
  $i = 0;
  $sume = new ROIreport;
  $sume->campaign_name = "<b>Total</b>";
  $sume->general_expenditure = 0;
  $sume->general_income = 0;
  $sume->roi = 0;

  while($row = mysql_fetch_row($res)){
    $report[$i] = new ROIreport;
    $report[$i]->Init($row[0]);
    $sume->general_expenditure += $report[$i]->general_expenditure;
    $sume->general_income += $report[$i]->general_income;
    $sume->roi += $report[$i]->roi;
    $i++;
  }
  $sume->roi = round(($sume->roi/$i),2);
  $sume->general_expenditure = "<b>$sume->general_expenditure</b>";
  $sume->general_income = "<b>$sume->general_income</b>";
  $sume->roi = "<b>$sume->roi</b>";
  if($i > 1)$report[$i++] = $sume;

  if($pid == 'roiprint'){
   echo ROITemplate_4print($report);
   exit(1);
  }
 return ROITemplate($report);
}


function CSVROI(){
  global $uid;
  $res = mysql_query("SELECT id FROM ROIcampaigns WHERE user_id = $uid");
  $report = array();
  $i = 0;
  $sume = new ROIreport;
  $sume->campaign_name = "Total";
  $sume->general_expenditure = 0;
  $sume->general_income = 0;
  $sume->roi = 0;

  while($row = mysql_fetch_row($res)){
    $report[$i] = new ROIreport;
    $report[$i]->Init($row[0]);
    $sume->general_expenditure += $report[$i]->general_expenditure;
    $sume->general_income += $report[$i]->general_income;
    $sume->roi += $report[$i]->roi;
    $i++;
  }
  if($i > 1)$report[$i++] = $sume;
  $str = '';
  for($i = 0; $i < count($report); $i++){
    $str .= $report[$i]->campaign_name;
    $str .= ';';
    $str .= $report[$i]->general_expenditure;
    $str .= ';';
    $str .= $report[$i]->general_income;
    $str .= ';';
    $str .= $report[$i]->roi;
    $str .= "\n";
  }
 return CreateCSVFile('ROI-Report',$str);
}
// general report
function General(){
  global $pid, $uid;
  $res = mysql_query("SELECT id FROM ROIcampaigns WHERE user_id = $uid");
  $report = array();
  $i = 0;
  $sume = new GeneralReport();
  $sume->id = '';
  $sume->campaign_name = '</a><b>Total</b><a>';
  $sume->visitors = 0;
  $sume->leads = 0;
  $sume->clients = 0;
  $sume->general_expenditure = 0;
  $sume->general_income = 0;

  while($row = mysql_fetch_row($res)){
    $report[$i] = new GeneralReport;
    $report[$i]->Init($row[0]);

    $sume->visitors += $report[$i]->visitors;
    $sume->leads += $report[$i]->leads;
    $sume->clients += $report[$i]->clients;
    $sume->general_expenditure += substr($report[$i]->general_expenditure,1);
    $sume->general_income += substr($report[$i]->general_income,1);

    $i++;
  }
    $sume->visitors = "<b>$sume->visitors</b>";
    $sume->leads = "<b>$sume->leads</b>";
    $sume->clients = "<b>$sume->clients</b>";
    $sume->general_expenditure = "<b>$$sume->general_expenditure</b>";
    $sume->general_income = "<b>$$sume->general_income</b>";

  if($i > 1)$report[$i++] = $sume;
  if($pid == 'generalprint'){
   echo GeneralTemplate_4print($report);
   exit(1);
  }
 return GeneralTemplate($report);
}

function GeneralItemReport(){
  global $HTTP_GET_VARS,$pid, $uid;
  if(!isset($HTTP_GET_VARS['id']))pError("Error campaign Id","Campaign with current ID not found");
  $id = $HTTP_GET_VARS['id'];
  $res = mysql_query("SELECT id FROM ROIactions WHERE campaign_id = $id && end_point = 1");
  $n = 0;

  $sum = new GeneralItem;
  $sum->action_name = "<b>Total</b>";
  $sum->leads = 0;
  $sum->income = 0;

  $item[0] = new GeneralReport();
  $item[0]->Init($id);

  while($row = mysql_fetch_row($res)){
       $lead[$n] = new GeneralItem;
       $lead[$n]->Init($row[0]);
       $sum->leads += $lead[$n]->leads;
       $sum->income += $lead[$n]->income;
    $n++;
  }
  if($n>1) { $lead[$n++] = $sum; }

  $sum1 = new GeneralItem;
  $sum1->action_name = "<b>Total</b>";
  $sum1->leads = 0;
  $sum1->income = 0;

  $res1 = mysql_query("SELECT id FROM ROIactions WHERE campaign_id = $id && end_point = 2");
  $n = 0;
  while($row1 = mysql_fetch_row($res1)){
       $client[$n] = new GeneralItem;
       $client[$n]->InitOne($row1[0]);
       $sum1->leads += $client[$n]->leads;
       $sum1->income += substr($client[$n]->income,1);
       $n++;

  }
$sum1->income = '$'.$sum1->income;

  if($n>1)$client[$n++] = $sum1;
  if($pid == 'generalprint_lead'){
   echo GeneralItemTemplate_lead_4print($lead);
   exit(1);
  }else if($pid == 'generalprint_client'){
   echo GeneralItemTemplate_client_4print($client);
   exit(1);
  }
 return GeneralItemTemplate($item,$lead,$client);
}

function CSVGeneral(){
global $uid;
  $res = mysql_query("SELECT id FROM ROIcampaigns WHERE user_id = $uid");
  $report = array();
  $i = 0;
  $sume = new GeneralReport();
  $sume->id = '';
  $sume->campaign_name = 'Total:';
  $sume->visitors = 0;
  $sume->leads = 0;
  $sume->clients = 0;
  $sume->general_expenditure = 0;
  $sume->general_income = 0;

  while($row = mysql_fetch_row($res)){
    $report[$i] = new GeneralReport;
    $report[$i]->Init($row[0]);

    $sume->visitors += $report[$i]->visitors;
    $sume->leads += $report[$i]->leads;
    $sume->clients += $report[$i]->clients;
    $sume->general_expenditure += $report[$i]->general_expenditure;
    $sume->general_income += $report[$i]->general_income;

    $i++;
  }
    $sume->visitors = $sume->visitors;
    $sume->leads = $sume->leads;
    $sume->clients = $sume->clients;
    $sume->general_expenditure = $sume->general_expenditure;
    $sume->general_income = $sume->general_income;

  if($i > 1)$report[$i++] = $sume;
    $lead = &$report;
    $str = '';
    for($i = 0; $i < count($lead); $i++ ){
      $str .= $lead[$i]->campaign_name;
      $str .= ';';
      $str .= $lead[$i]->visitors;
      $str .= ';';
      $str .= $lead[$i]->leads;
      $str .= ';';
      $str .= $lead[$i]->clients;
      $str .= ';';
      $str .= $lead[$i]->general_expenditure;
      $str .= ';';
      $str .= $lead[$i]->general_income;
      $str .= "\n";
    }
 return CreateCSVFile('General-Report',$str);
}
function CSVGeneralItem($param){
  global $HTTP_GET_VARS, $uid;
  if(!isset($HTTP_GET_VARS['id']))pError("Error campaign Id","Campaign with current ID not found");
  $id = $HTTP_GET_VARS['id'];
  $res = mysql_query("SELECT id FROM ROIactions WHERE user_id = $uid && campaign_id = $id && end_point = 1");
  $n = 0;
 if($param == 0){
  $sum = new GeneralItem;

  while($row = mysql_fetch_row($res)){
       $lead[$n] = new GeneralItem;
       $lead[$n]->Init($row[0]);
       $n++;
  }
    $str = '';
    for($i = 0; $i < count($lead); $i++){
      $str .= $lead[$i]->action_name;
      $str .= ';';
      $str .= $lead[$i]->leads;
      $str .= "\n";
    }
   return CreateCSVFile('General-Report_Leads',$str);
 }else{
  $res1 = mysql_query("SELECT id FROM ROIactions WHERE user_id = $uid && campaign_id = $id && end_point = 2");
  $n = 0;
  while($row1 = mysql_fetch_row($res1)){
       $client[$n] = new GeneralItem;
       $client[$n]->InitOne($row1[0]);
       $n++;

  }
    $str = '';
    $lead = &$client;
    for($i = 0; $i < count($lead); $i++){
      $str .= $lead[$i]->action_name;
      $str .= ';';
      $str .= $lead[$i]->leads;
      $str .= ';';
      $str .= $lead[$i]->income;
      $str .= "\n";
    }
   return CreateCSVFile('General-Report_Clients',$str);
 }
}
// end of general reports

function Keywords(){
  global $pid, $uid;
  $res = mysql_query("SELECT id FROM ROIcampaigns WHERE price_type=4 && user_id = $uid");
  $report = array();

  $sume = new GeneralKeywords();
  $sume->id = '';
  $sume->campaign_name = '</a><b>Total</b><a>';
  $total = 0;
  $i = 0;

  while($row = mysql_fetch_row($res)){

    $report[$i] = new GeneralKeywords;
    $report[$i]->Init($row[0]);

    $sume->visitors += $report[$i]->visitors;
    $sume->clients += $report[$i]->clients;
    $sume->leads += $report[$i]->leads;
    $sume->expenditure += $report[$i]->expenditure;
    $sume->income += $report[$i]->income;
    $sume->roi += $report[$i]->roi;
    $i++;

  }
    $sume->roi = round(($sume->roi/$i),ADOT);
    $sume->visitors = '<b>'.$sume->visitors.'</b>';
    $sume->clients = '<b>'.$sume->clients.'</b>';
    $sume->leads = '<b>'.$sume->leads.'</b>';
    $sume->expenditure = '<b>'.$sume->expenditure.'</b>';
    $sume->income = '<b>'.$sume->income.'</b>';
    $sume->roi = '<b>'.$sume->roi.'</b>';

  if($i > 1)$report[$i++] = $sume;

  if($pid == 'keywordsprint'){
   echo KeywordsTemplate_4print($report);
   exit(1);
  }

 return KeywordsTemplate($report);
}


function KeywordItemReport(){
  global $HTTP_GET_VARS,$pid, $uid;
  if(!isset($HTTP_GET_VARS['id']))pError("Error campaign Id","Campaign with current ID not found");
  $id = $HTTP_GET_VARS['id'];

  $item[0] = new KeywordsReport;
  $item[0]->Init($id);

  $i = 0;

  $summ = new ListingItem;
  $res = mysql_query("SELECT id FROM ROIlistings WHERE campaign_id='$id' ORDER BY id");
  while ($row = mysql_fetch_row($res))
  {
    $listing[$i] = new ListingItem;
    $listing[$i]->Init($row[0], $id);

    $summ->visitors += $listing[$i]->visitors;
    $summ->clients += $listing[$i]->clients;
    $summ->leads += $listing[$i]->leads;
    $summ->expenditure += $listing[$i]->expenditure;
    $summ->income += $listing[$i]->income;
    $summ->roi += $listing[$i]->roi;

    $list_detail .= ListingItemDetail($row[0],$listing[$i]->listing_name);
    $i++;
  }

  $summ->roi = '<b>'.round($summ->income/$summ->expenditure*100).'</b>';
  $summ->visitors = '<b>'.$summ->visitors.'</b>';
  $summ->clients = '<b>'.$summ->clients.'</b>';
  $summ->leads = '<b>'.$summ->leads.'</b>';
  $summ->expenditure = '<b>'.$summ->expenditure.'</b>';
  $summ->income = '<b>'.$summ->income.'</b>';
  $summ->listing_name = '</a><b>Total</b><a>';

  if($i > 1)$listing[$i++] = $summ;

  if($pid == 'keywordsprint_item'){
   echo KeywordsItemTemplate_4print($keyword);
   exit(1);
  }

 return KeywordItemTemplate($item,$listing).$list_detail;
}

function ListingItemDetail($id,$listing_name)
{
 $res = mysql_query("SELECT id,keyword_price FROM ROIkeywords WHERE listing_id = $id ORDER BY id");

 $i=0;
 $summ = new Keyword;
 while ($row = mysql_fetch_row($res))
 {
   $item[$i]= new Keyword;
   $item[$i]->Init($row[0],$row[1]);
   $summ->visitors += $item[$i]->visitors;
   $summ->clients += $item[$i]->visitors;
   $summ->leads += $item[$i]->visitors;
   $summ->expenditure += $item[$i]->expenditure;
   $summ->income += $item[$i]->income;

   $i++;
 }

 $summ->roi = '<b>'.round($summ->income/$summ->expenditure*100).'</b>';
 $summ->keyword_name = '<b>Total</b>';
 $summ->visitors = '<b>'.$summ->visitors.'</b>';
 $summ->clients = '<b>'.$summ->clients.'</b>';
 $summ->leads = '<b>'.$summ->leads.'</b>';
 $summ->expenditure = '<b>'.$summ->expenditure.'</b>';
 $summ->income = '<b>'.$summ->income.'</b>';

 if ($i>1) { $item[$i]= $summ;}
 return ListingDetailsTemplate($item,$listing_name,$id);
}

function CSVKeywords(){
  global $pid, $uid;
  $res = mysql_query("SELECT id FROM ROIcampaigns WHERE user_id = $uid");
  $report = array();

  $sume = new GeneralKeywords();
  $sume->id = '';
  $sume->campaign_name = 'Total';
  $total = 0;
  $i = 0;

  while($row = mysql_fetch_row($res)){
    $report[$i] = new GeneralKeywords;
    $report[$i]->Init($row[0]);
    $total += $report[$i]->camebykeyword;
    $i++;
  }

  $sume->camebykeyword = "$total";
  if($i > 1)$report[$i++] = $sume;

    for($i = 0; $i < count($report); $i++ ){
      $str .= $report[$i]->campaign_name;
      $str .= ';';
      $str .= $report[$i]->camebykeyword;
      $str .= "\n";
    }
 return CreateCSVFile('Keywords-Report',$str);
}

function CSVKeywordsItem(){
  global $HTTP_GET_VARS,$pid, $uid;
  if(!isset($HTTP_GET_VARS['id']))pError("Error campaign Id","Campaign with current ID not found");
  $id = $HTTP_GET_VARS['id'];

  $i = 0;
  $total = 0;
  $sume = new KeywordItem;
  $res = mysql_query("SELECT id FROM ROIkeywords WHERE campaign_id='$id'");
  while ($row = mysql_fetch_row($res))
  {
    $keyword[$i] = new KeywordItem;
    $keyword[$i]->Init($row[0]);
    $total += $keyword[$i]->visitors;
    $i++;
  }
  $sume->keyword_name = 'Total';
  $sume->visitors = $total;
  if($i > 1)$keyword[$i++] = $sume;

    for($i = 0; $i < count($keyword); $i++ ){
      $str .= $keyword[$i]->keyword_name;
      $str .= ';';
      $str .= $keyword[$i]->visitors;
      $str .= "\n";
    }

 return CreateCSVFile('Keywords_Item-Report',$str);
}
##########################################3


function CreateCSVFile($file,$data){
  ClearDir();
  $d = date("m-d-Y");
  $name = $file.'_'.$d.'.csv';
  $fl = fopen('temp/'.$name,"w") or die("Can't open file for writing");
   fputs($fl,$data);
  fclose($fl);
 return 'temp/'.$name;
}
function ClearDir(){
  $handle=opendir('temp');
  $tm = time();
  while ($file = readdir($handle))
      if($tm-filectime('temp/'.$file) > 1800)unlink('temp/'.$file);
  closedir($handle);
}

if($pid == 1 && !isset($HTTP_GET_VARS['pid']))$pid = 'general';
else if($pid == 1)$pid = $HTTP_GET_VARS['pid'];


$rv = '';

if($pid == 'general' || $pid == 'generalprint')$rv = General();
else if($pid == 'generalitem' || $pid == 'generalprint_lead' || $pid == 'generalprint_client')$rv = GeneralItemReport();
else if($pid == 'financial' || $pid == 'financialprint')$rv = Financial();
else if($pid == 'financialitem' || $pid == 'financialprint_lead' || $pid == 'financialprint_client')$rv = FinancialItem();
else if($pid == 'visitors' || $pid == 'visitorsprint')$rv = VisitorsReport();
else if($pid == 'visitorsitem' || $pid == 'visitorsprint_lead' || $pid == 'visitorsprint_client')$rv = VisitorsItems();

if($pid == 'keywords' || $pid == 'keywordsprint') $rv = Keywords();
if($pid == 'keywordsitem' || $pid == 'keywordsprint_item') $rv = KeywordItemReport();


else if($pid == 'csvgeneral'){ header("Location: ".CSVGeneral()); exit(1); }
else if($pid == 'csvgeneralitem_lead'){ header("Location: ".CSVGeneralItem(0)); exit(1); }
else if($pid == 'csvgeneralitem_client'){ header("Location: ".CSVGeneralItem(1)); exit(1); }

if($pid == 'csvkeywords') { header("Location: ".CSVKeywords()); exit(1); }
if($pid == 'csvkeywords_item') { header("Location: ".CSVKeywordsItem(0)); exit(1); }

else if($pid == 'csvfinancial'){ header("Location: ".CSVFinancial()); exit(1); }
else if($pid == 'csvfinancialitem_lead'){ header("Location: ".CSVFinancialItem(0)); exit(1); }
else if($pid == 'csvfinancialitem_client'){header("Location: ".CSVFinancialItem(1)); exit(1); }

else if($pid == 'csvvisitors'){ header("Location: ".VisitorsReport()); exit(1); }
else if($pid == 'csvvisitorsitem_lead'){ header("Location: ".VisitorsItems()); exit(1); }
else if($pid == 'csvvisitorsitem_client'){ header("Location: ".VisitorsItems()); exit(1); }

else if($pid == 'roiprint'){ ROIReport(); }

else if($pid == 'csvroi'){ header("Location: ".CSVROI()); exit(1); }



else if($pid == 'roireport'){
 global $link1,$link2;
 Assign('image_label','label-roi.gif');
 $link1 = "reports.php?pid=csvroi";
 $link2 = "reports.php?pid=roiprint";

 return ShowTemplate(ReportsHeader().ROIreport());
}


ShowTemplate(ReportsHeader().$rv);

?>
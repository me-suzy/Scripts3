<?php
###################################################
# Last modified 24/12/03
###################################################

$LABLES= array('conf'=>'label-configuration.gif','report'=>'label-reports.gif');

function ROIis_float($val){ return ereg('^([0-9]{1,20})+(\\.|\\,)+([0-9]{1,20})+[\\$]{0,1}$', $val); }
function ROIis_int($val){ return ereg('^([0-9]{1,20})+[\\$]{0,1}$', $val); }
function ROIis_digits($val){ return ereg('^([0-9]{1,40}$', $val ); }
function IsEmpty($val){ if(strlen($val) == 0)return true; return false; }
function ROIchange($val){ if(strlen($val)==0)return '0'; $val = str_replace(',','.',$val ); $val = str_replace('$','',$val ); return $val;}

function TableTemplate($class,$template,$variables){
  $rv = $str = '';
  $n = 1;

  $str = $template[0];

  for($i = 0; $i<sizeof($class); $i++){
    $rv = $template[$n++];
    if($n == sizeof($template)-1)$n = 1;
    foreach ($variables as $num => $key){
      $rv = str_replace("%$key%",$class[$i]->$key,$rv);
    }
    $str .= $rv;
   }
   $str .= $template[sizeof($template)-1];
 return $str;
}

function ParseData($data){
  global $VARS;
  foreach($VARS as $key => $val){
    $data = str_replace("%$key%",$val,$data);
  }
 return $data;
}

function Dateform($month,$day,$year,$begin,$end,$pref){
     $begin += 2003;
     $end = $end > 0 ? $begin+$end : $begin+10;

     if($month == 1 && $day == 1 && $year == 1){
       $t = time();
       $month = date("m",$t);
       $day = date("d",$t);
       $year = date("Y",$t);
     }

     $allmonth = array('01','Jan','02','Feb','03','Mar','04','Apr','05','May','06','Jun','07','Jul','08','Aug','09','Sep','10','Oct','11','Nov','12','Dec');

     $rv  = "<table border=0 cellspacing=0 cellpadding=0><tr><td></td>\n<td><select name=".$pref."month>\n";
     for( $i = 0; $i < count($allmonth); $i += 2 )
       if($allmonth[$i] != $month)$rv .= "<option value=".$allmonth[$i].">".$allmonth[$i+1]."</option>\n";
       else $rv.="<option value=".$allmonth[$i]." selected>".$allmonth[$i+1]."</option>\n";
//$day
     $rv .= "</td>\n<td><select name=".$pref."day>\n";
     for($i = 1; $i < 32; $i++){
      if($day != $i)$rv .= "<option value=$i>";
      else $rv .="<option value=$i selected>";
      $rv .= "$i</option>\n";
     }
     $rv .= "</select></td>\n";
     $rv .= "\n<td><select name=".$pref."year>\n";

     for( $i = $begin; $i < $end; $i++)
      if($year != $i)$rv .= "<option value=$i>$i</option>\n";
      else $rv .= "<option value=$i selected>$i</option>\n";

     $rv .= "</select></td></tr></table>\n";
    return $rv;
}


// templates
function GeneralTemplate($report){
  global $link1,$link2;
  $link1 = "reports.php?pid=csvgeneral";
  $link2 = "reports.php?pid=generalprint";
  $template = array();
  $variables = array('id','campaign_name','visitors','leads','clients','general_expenditure','general_income');
/*--------------- include items template here ------------------*/
/*
<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=100%>&nbsp;</td>
<td><a href="reports.php?pid=csvgeneral"><img src="images/button-export2csv.gif" border=0 alt="Export to CSV"></a></td>
<td>&nbsp;&nbsp;<a href="reports.php?pid=generalprint"><img src="images/button-print.gif" border=0 alt="Version for print"></a></td>
</tr></table>
*/

  $template[0] = <<< EOT

<table border=0 cellspacing=0 cellpadding=0 width=100%>
<tr class="cellheader">
<td class="cellheader" width=40%>Campaign name</td>
<td class="cellheader">Visitors</td>
<td class="cellheader">Clients</td>
<td class="cellheader">General expenditure</td>
<td class="cellheader">General income</td>
</tr>
EOT;

  $template[1] = <<< EOT
<tr class=cell1>
<td><a href="reports.php?pid=generalitem&id=%id%">%campaign_name%</a></td>
<td>%visitors%</td>
<td>%clients%</td>
<td>%general_expenditure%</td>
<td>%general_income%</td>
</tr>
EOT;

  $template[2] = <<< EOT
<tr class=cell2>
<td><a href="reports.php?pid=generalitem&id=%id%">%campaign_name%</a></td>
<td>%visitors%</td>
<td>%clients%</td>
<td>%general_expenditure%</td>
<td>%general_income%</td>
</tr>
EOT;

  $template[3] = <<< EOT
</table>
<br><br>

EOT;
/*--------------- end of include items template ------------------*/
 return TableTemplate($report,$template,$variables);
}


function GeneralTemplate_4print($report){//4print
  $template = array();
  $variables = array('id','campaign_name','visitors','leads','clients','general_expenditure','general_income');
/*--------------- include items template here ------------------*/
  $template[0] = <<< EOT
<h2>General report</h2>
<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=40%>Campaign name</b></td>
<td><b>Visitors</b></td>
<td><b>Clients</b></td>
<td><b>General expenditure</b></td>
<td><b>General income</b></td>
</tr>
EOT;

  $template[1] = <<< EOT
<tr>
<td>%campaign_name%</td>
<td>%visitors%</td>
<td>%clients%</td>
<td>%general_expenditure%</td>
<td>%general_income%</td>
</tr>
EOT;

  $template[2] = <<< EOT
<tr bgcolor=#f0f0f0>
<td>%campaign_name%</td>
<td>%visitors%</td>
<td>%clients%</td>
<td>%general_expenditure%</td>
<td>%general_income%</td>
</tr>
EOT;

  $template[3] = <<< EOT
</table>
<br><br>

EOT;
/*--------------- end of include items template ------------------*/
 return TableTemplate($report,$template,$variables);
}


// Showing Item (Campaign) from General Report

function GeneralItemTemplate($item,$lead,$client){
  return GeneralItemTemplate_campaign($item).GeneralItemTemplate_lead($lead).GeneralItemTemplate_client($client);
}
//first
function GeneralItemTemplate_campaign($item){

//echo "<font size=7>".$item->campaign_name."</font>";
$template = array();
$variables = array('id','campaign_name','visitors','general_expenditure','general_income');

$template[0] = <<<EOT
EOT;

$template[1] = <<<EOT
<center><h1>%campaign_name% campaign</h1></center>

<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td rowspan=3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><b>Visitors:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%visitors%</td>
</tr>
<tr>
<td><b>General expenditure:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%general_expenditure%</td>
</tr>
<tr>
<td><b>General income:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%general_income%</td>
</tr>
EOT;

$template[2] = <<<EOT
</table>
<hr size=1 color=#cccccc width=100%>

EOT;
 return TableTemplate($item,$template,$variables);
}
//show leads
function GeneralItemTemplate_lead($lead){
         global $id;
         $template = array();
         $variables = array('action_name','leads');

         $template[0] = <<<EOT
<h2>Intermediate actions</h2>

<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=100%>&nbsp;</td>
<td><a href="reports.php?pid=csvgeneralitem_lead&id=$id"><img src="images/button-export2csv.gif" border=0 alt="Export to CSV"></a></td>
<td>&nbsp;&nbsp;<a href="reports.php?pid=generalprint_lead&id=$id"><img src="images/button-print.gif" border=0 alt="Version for print"></a></td>
</tr></table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="21%" class="cellheader"><a href="#">Action</a></td>
<td width="10%" class="cellheader"><a href="#">Leads</a> </td>
</tr>
EOT;
         $template[1] = <<<EOT
<tr class=cell1>
<td>%action_name%</td>
<td>%leads%</td>
</tr>
EOT;

         $template[2] = <<<EOT
<tr class=cell2>
<td>%action_name%</td>
<td>%leads%</td>
</tr>
EOT;

         $template[3] = <<<EOT
</table>
<br><br>
EOT;

 return TableTemplate($lead,$template,$variables);

}

function KeywordsTemplate($report){
  global $link1,$link2;
  $link1 = "reports.php?pid=csvkeywords";
  $link2 = "reports.php?pid=keywordsprint";
  $template = array();
  $variables = array('id','campaign_name','visitors','clients','leads','expenditure','income','roi');

  $template[0] = <<< EOT

<table border=0 cellspacing=0 cellpadding=0 width=100%>
<tr class="cellheader">
<td class="cellheader" width=40%>Campaign name</td>
<td class="cellheader" width=10%>Visitors</td>
<td class="cellheader" width=10%>Clients</td>
<td class="cellheader" width=10%>Leads</td>
<td class="cellheader" width=10%>General expenditure</td>
<td class="cellheader" width=10%>General income</td>
<td class="cellheader" width=10%>ROI</td>
</tr>
EOT;

  $template[1] = <<< EOT
<tr class=cell1>
<td><a href="reports.php?pid=keywordsitem&id=%id%">%campaign_name%</a></td>
<td>%visitors%</td>
<td>%clients%</td>
<td>%leads%</td>
<td>$%expenditure%</td>
<td>$%income%</td>
<td>%roi%%</td>
</tr>
EOT;

  $template[2] = <<< EOT
<tr class=cell2>
<td><a href="reports.php?pid=keywordsitem&id=%id%">%campaign_name%</a></td>
<td>%visitors%</td>
<td>%clients%</td>
<td>%leads%</td>
<td>$%expenditure%</td>
<td>$%income%</td>
<td>%roi%%</td>
</tr>
EOT;

  $template[3] = <<< EOT
</table>
<br><br>

EOT;
/*--------------- end of include items template ------------------*/
 return TableTemplate($report,$template,$variables);
}



function KeywordsTemplate_4print($report){//4print
  $template = array();
  $variables = array('id','campaign_name','camebykeyword');
/*--------------- include items template here ------------------*/
  $template[0] = <<< EOT
<h2>Keywords report</h2>
<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=70%><b>Campaign name<b></b></td>
<td><b>Visitors came by keywords</b></td>
</tr>
EOT;

  $template[1] = <<< EOT
<tr>
<td>%campaign_name%</td>
<td>%camebykeyword%</td>
</tr>
EOT;

  $template[2] = <<< EOT
<tr bgcolor=#f0f0f0>
<td>%campaign_name%</td>
<td>%camebykeyword%</td>
</tr>
EOT;

  $template[3] = <<< EOT
</table>
<br><br>

EOT;
/*--------------- end of include items template ------------------*/
 return TableTemplate($report,$template,$variables);
}

function KeywordsItemTemplate_4print($keyword){
         global $id;
         $template = array();
         $variables = array('keyword_name','visitors');

         $template[0] = <<<EOT
<h2>Keywords Report</h2>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr bgcolor=#cccccc>
<td width="21%"><b>Keyword</b></td>
<td width="10%"><b>Visitors</b></td>
</tr>
EOT;
         $template[1] = <<<EOT
<tr>
<td>%keyword_name%</td>
<td>%visitors%</td>
</tr>
EOT;

         $template[2] = <<<EOT
<tr bgcolor=#f0f0f0>
<td>%keyword_name%</td>
<td>%visitors%</td>
</tr>
EOT;

         $template[3] = <<<EOT
</table>
<br><br>
EOT;

 return TableTemplate($keyword,$template,$variables);

}

function KeywordItemTemplate($item,$listing){
  return KeywordItemTemplate_campaign($item).KeywordItemTemplate_listings($listing);
}
//first

function KeywordItemTemplate_campaign($item){
$template = array();
$variables = array('id','campaign_name','visitors','clients','leads','expenditure','income','roi');

$template[0] = <<<EOT
EOT;

$template[1] = <<<EOT
<center><h1>%campaign_name% campaign</h1></center>

<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td rowspan=6>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><b>Visitors:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%visitors%</td>
</tr>
<tr>
<td><b>Clients:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%clients%</td>
</tr>
<tr>
<td><b>Leads:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%leads%</td>
</tr>
<tr>
<td><b>General expenditure:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>$%expenditure%</td>
</tr>
<tr>
<td><b>General income:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>$%income%</td>
</tr>
<tr>
<td><b>ROI:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%roi%%</td>
</tr>
EOT;

$template[2] = <<<EOT
</table>
<hr size=1 color=#cccccc width=100%>

EOT;
 return TableTemplate($item,$template,$variables);
}
//show leads

function KeywordItemTemplate_listings($listing){
         global $id;
         $template = array();
         $variables = array('id','listing_name','visitors', 'leads', 'clients','expenditure','income','roi');

         $template[0] = <<<EOT
<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=100%>&nbsp;</td>
<td><a href="reports.php?pid=csvkeywords_item&id=$id"><img src="images/button-export2csv.gif" border=0 alt="Export to CSV"></a></td>
<td>&nbsp;&nbsp;<a href="reports.php?pid=keywordsprint_item&id=$id"><img src="images/button-print.gif" border=0 alt="Version for print"></a></td>
</tr></table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="30%" class="cellheader"><a href="#">Listing</a></td>
<td width="10%" class="cellheader"><a href="#">Visitors</a></td>
<td width="10%" class="cellheader"><a href="#">Clients</a></td>
<td width="10%" class="cellheader"><a href="#">Leads</a></td>
<td width="10%" class="cellheader"><a href="#">Expenditure</a></td>
<td width="10%" class="cellheader"><a href="#">Income</a></td>
<td width="10%" class="cellheader"><a href="#">ROI</a></td>
</tr>
EOT;
         $template[1] = <<<EOT
<tr class=cell1>
<td><a href="#%id%">%listing_name%</a></td>
<td>%visitors%</td>
<td>%clients%</td>
<td>%leads%</td>
<td>$%expenditure%</td>
<td>$%income%</td>
<td>%roi%%</td>
</tr>
EOT;

         $template[2] = <<<EOT
<tr class=cell2>
<td><a href="#%id%">%listing_name%</a></td>
<td>%visitors%</td>
<td>%clients%</td>
<td>%leads%</td>
<td>$%expenditure%</td>
<td>$%income%</td>
<td>%roi%%</td>
</tr>
EOT;

         $template[3] = <<<EOT
</table>
<br><br>
EOT;

 return TableTemplate($listing,$template,$variables);

}












function GeneralItemTemplate_lead_4print($lead){//4print
         global $id;
         $template = array();
         $variables = array('action_name','leads');

         $template[0] = <<<EOT
<h2>Intermediate actions</h2>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr bgcolor=#cccccc>
<td width="21%"><b>Action</b></td>
<td width="10%"><b>Leads</b></td>
</tr>
EOT;
         $template[1] = <<<EOT
<tr>
<td>%action_name%</td>
<td>%leads%</td>
</tr>
EOT;

         $template[2] = <<<EOT
<tr bgcolor=#f0f0f0>
<td>%action_name%</td>
<td>%leads%</td>
</tr>
EOT;

         $template[3] = <<<EOT
</table>
<br><br>
EOT;

 return TableTemplate($lead,$template,$variables);

}


function GeneralItemTemplate_client($client){
         global $id;
         $template = array();
         $variables = array('action_name','leads','income');

         $template[0] = <<<EOT
<h2>End point actions</h2>

<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=100%>&nbsp;</td>
<td><a href="reports.php?pid=csvgeneralitem_client&id=$id"><img src="images/button-export2csv.gif" border=0 alt="Export to CSV"></a></td>
<td>&nbsp;&nbsp;<a href="reports.php?pid=generalprint_client&id=$id"><img src="images/button-print.gif" border=0 alt="Version for print"></a></td>
</tr></table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="40%" class="cellheader"><a href="#">Action</a></td>
<td width="30%" class="cellheader"><a href="#">Clients</a> </td>
<td width="30%" class="cellheader"><a href="#">Income</a> </td>
</tr>
EOT;
         $template[1] = <<<EOT
<tr class=cell1>
<td>%action_name%</td>
<td>%leads%</td>
<td>%income%</td>
</tr>
EOT;
         $template[2] = <<<EOT
<tr class=cell2>
<td>%action_name%</td>
<td>%leads%</td>
<td>%income%</td>
</tr>
EOT;

         $template[3] = <<<EOT
</table>
<br><br>
EOT;

 return TableTemplate($client,$template,$variables);
}

function GeneralItemTemplate_client_4print($client){//4print
         global $id;
         $template = array();
         $variables = array('action_name','leads','income');

         $template[0] = <<<EOT
<h2>End point actions</h2>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr bgcolor=#cccccc>
<td width="40%"><b>Action</b></td>
<td width="30%"><b>Clients</b> </td>
<td width="30%"><b>Income</b> </td>
</tr>
EOT;
         $template[1] = <<<EOT
<tr>
<td>%action_name%</td>
<td>%leads%</td>
<td>%income%</td>
</tr>
EOT;
         $template[2] = <<<EOT
<tr bgcolor=#f0f0f0>
<td>%action_name%</td>
<td>%leads%</td>
<td>%income%</td>
</tr>
EOT;

         $template[3] = <<<EOT
</table>
<br><br>
EOT;

 return TableTemplate($client,$template,$variables);
}

// end of GeneralReport


function GeneralFinancial($report){
  global $link1,$link2;
  $link1 = "reports.php?pid=csvfinancial";
  $link2 = "reports.php?pid=financialprint";

         $template = array();
         $variables = array('id',
                            'campaign_name',
                            'general_expenditure',
                            'general_income',
                            'expenditure_per_client',
                            'expenditure_per_lead',
                            'expenditure_per_visitor',
                            'income_per_client',
                            'roi'
                            );

/*
<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=100%>&nbsp;</td>
<td><a href="reports.php?pid=csvfinancial"><img src="images/button-export2csv.gif" border=0 alt="Export to CSV"></a></td>
<td>&nbsp;&nbsp;<a href="reports.php?pid=financialprint"><img src="images/button-print.gif" border=0 alt="Version for print"></a></td>
</tr></table>
*/

         $template[0] = <<<EOT


<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="30%" class="cellheader" rowspan="2"><a href="#">Campaign</a></td>
<td width="30%" class="cellheader" colspan="2" ><a href="#">Income</a> </td>
<td width="30%" class="cellheader" colspan="4"><a href="#">Expenditure</a> </td>
<td width="10%" class="cellheader" rowspan="2"><a href="#">ROI</a> </td>
</tr>
<tr>
<td width="15%" class="cellheader"><a href="#">General</a></td>
<td width="15%" class="cellheader"><a href="#">Per Client</a></td>
<td width="7%" class="cellheader"><a href="#">general</a></td>
<td width="8%" class="cellheader"><a href="#">per visitor</a></td>
<td width="8%" class="cellheader"><a href="#">per lead</a></td>
<td width="7%" class="cellheader"><a href="#">per client</a></td>
</tr>
</table>



<table width="100%" border="0" cellpadding="0" cellspacing="0">



EOT;
         $template[1] = <<<EOT
<tr class=cell1>
<td width=30%><a href="reports.php?pid=financialitem&id=%id%">%campaign_name%</a></td>
<td width=15%>%general_income%</td>
<td width=15%>%income_per_client%</td>
<td width=7%>%general_expenditure%</td>
<td width=8%>%expenditure_per_visitor%</td>
<td width=8%>%expenditure_per_lead%</td>
<td width=8%>%expenditure_per_client%</td>
<td width=10%>%roi%%</td>
</tr>
EOT;
         $template[2] = <<<EOT
<tr class=cell2>
<td width=30%><a href="reports.php?pid=financialitem&id=%id%">%campaign_name%</a></td>
<td width=15%>%general_income%</td>
<td width=15%>%income_per_client%</td>
<td width=7%>%general_expenditure%</td>
<td width=8%>%expenditure_per_visitor%</td>
<td width=8%>%expenditure_per_lead%</td>
<td width=8%>%expenditure_per_client%</td>
<td width=10%>%roi%%</td>
</tr>
EOT;

         $template[3] = <<<EOT
</table>
<br><br>
EOT;

 return TableTemplate($report,$template,$variables);
}


function GeneralFinancial_4print($report){//4print
         $template = array();
         $variables = array('id',
                            'campaign_name',
                            'general_expenditure',
                            'general_income',
                            'expenditure_per_client',
                            'income_per_client',
                            'roi',
                            'expenditure_per_visitor',
                            'expenditure_per_lead'
                            );

         $template[0] = <<<EOT
<h2>Financial report</h2>

<table width="100%" border="0" cellpadding="0" cellspacing="2">
<tr bgcolor=#cccccc>
<td width="30%" rowspan="2"><center><b><font face=tahoma,arial size=2>CAMPAIGN</font></b></center></td>
<td width="30%" colspan="2"><center><b><font face=tahoma,arial size=2>INCOME</font></b></center></td>
<td width="30%" colspan="4"><center><b><font face=tahoma,arial size=2>EXPENDITURE</font></b></center></td>
<td width="10%" rowspan="2"><center><b><font face=tahoma,arial size=2>ROI</font></b></center></td>
</tr>
<tr bgcolor=#cccccc>
<td width="15%"><center><b><font face=tahoma,arial size=2>GENERAL</font></b></center></td>
<td width="15%"><center><b><font face=tahoma,arial size=2>PER CLIENT</font></b></center></td>
<td width="7%"><center><b><font face=tahoma,arial size=2>GENERAL</font></b></center></td>
<td width="8%"><center><b><font face=tahoma,arial size=2>PER VISITOR</font></b></center></td>
<td width="8%"><center><b><font face=tahoma,arial size=2>PER LEAD</font></b></center></td>
<td width="7%"><center><b><font face=tahoma,arial size=2>PER CLIENT</font></b></center></td>
</tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="2">

EOT;

         $template[1] = <<<EOT
<tr>
<td width=30%><center><font face=tahoma,arial size=2>%campaign_name%</font></center></td>
<td width=15%><center><font face=tahoma,arial size=2>%general_income%</font></center></td>
<td width=15%><center><font face=tahoma,arial size=2>%income_per_client%</font></center></td>
<td width=7%><center><font face=tahoma,arial size=2>%general_expenditure%</font></center></td>
<td width=8%><center><font face=tahoma,arial size=2>%expenditure_per_visitor%</font></center></td>
<td width=8%><center><font face=tahoma,arial size=2>%expenditure_per_lead%</font></center></td>
<td width=8%><center><font face=tahoma,arial size=2>%expenditure_per_client%</font></center></td>
<td width=10%><center><font face=tahoma,arial size=2>%roi%%</font></center></td>
</tr>
EOT;
         $template[2] = <<<EOT
<tr bgcolor=#f0f0f0>
<td width=30%><center><font face=tahoma,arial size=2>%campaign_name%</font></center></td>
<td width=15%><center><font face=tahoma,arial size=2>%general_income%</font></center></td>
<td width=15%><center><font face=tahoma,arial size=2>%income_per_client%</font></center></td>
<td width=7%><center><font face=tahoma,arial size=2>%general_expenditure%</font></center></td>
<td width=8%><center><font face=tahoma,arial size=2>%expenditure_per_visitor%</font></center></td>
<td width=8%><center><font face=tahoma,arial size=2>%expenditure_per_lead%</font></center></td>
<td width=8%><center><font face=tahoma,arial size=2>%expenditure_per_client%</font></center></td>
<td width=10%><center><font face=tahoma,arial size=2>%roi%%</font></center></td>
</tr>
EOT;

         $template[3] = <<<EOT
</table>
<br><br>
EOT;

 return TableTemplate($report,$template,$variables);
}

function GeneralFinancialItemTemplate($item,$leads,$clients){
         $template = array();
         $variables = array('id',
                            'campaign_name',
                            'general_expenditure',
                            'general_income',
                            'expenditure_per_client',
                            'income_per_client',
                            'roi'
                            );
//         $template[0] = Dateform(0,1,0,0,0,0);
         $template[0] = <<<EOT

EOT;
         $template[1] = <<<EOT
<h1>%campaign_name% campaign</h2>

<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td rowspan=5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><b>General expenditure:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%general_expenditure%</td>
</tr>

<tr>
<td><b>General income:</b></td><td>&nbsp;</td>
<td>%general_income%</td>
</tr>
<tr>
<td><b>Expenditure per client:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%expenditure_per_client%</td>
</tr>

<tr>
<td><b>Income per client:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%income_per_client%</td>
</tr>

<tr>
<td><b>ROI:</b></td><td>&nbsp;</td>
<td>%roi%%</td>
</tr>
EOT;

         $template[2] = <<<EOT
</table>
<hr size=1 color=#cccccc width=100%>
EOT;

 return TableTemplate($item,$template,$variables).
        GeneralFinancialItem_intermediate($leads).
        GeneralFinancialItem_endpoint($clients);
}

function GeneralFinancialItem_intermediate(&$leads){
         global $id;
         $template = array();
         $variables = array('action_name',
                            'expenditure_per_lead'
                            );

         $template[0] = <<<EOT
<h2>Intermediate actions</h2>

<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=100%>&nbsp;</td>
<td><a href="reports.php?pid=csvfinancialitem_lead&id=$id"><img src="images/button-export2csv.gif" border=0 alt="Export to CSV"></a></td>
<td>&nbsp;&nbsp;<a href="reports.php?pid=financialprint_lead&id=$id"><img src="images/button-print.gif" border=0 alt="Version for print"></a></td>
</tr></table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="30%" class="cellheader"><a href="#">Action</a></td>
<td width="10%" class="cellheader"><a href="#">Expenditure per lead</a></td>
</tr>
EOT;

         $template[1] = <<<EOT
<tr class=cell1>
<td>%action_name%</td>
<td>%expenditure_per_lead%</td>
</tr>
EOT;

         $template[2] = <<<EOT
<tr class=cell2>
<td>%action_name%</td>
<td>%expenditure_per_lead%</td>
</tr>
EOT;

         $template[3] = <<<EOT

</table>

EOT;
 return TableTemplate($leads,$template,$variables);
}

function GeneralFinancialItem_intermediate_4print(&$leads){
         global $id;
         $template = array();
         $variables = array('action_name',
                            'expenditure_per_lead'
                            );

         $template[0] = <<<EOT
<h2>Intermediate actions</h2>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr bgcolor=#cccccc>
<td width="30%"><b>Action</b></td>
<td width="10%"><b>Expenditure per lead</b></td>
</tr>
EOT;

         $template[1] = <<<EOT
<tr>
<td>%action_name%</td>
<td>%expenditure_per_lead%</td>
</tr>
EOT;

         $template[2] = <<<EOT
<tr bgcolor=#f0f0f0>
<td>%action_name%</td>
<td>%expenditure_per_lead%</td>
</tr>
EOT;

         $template[3] = <<<EOT

</table>

EOT;
 return TableTemplate($leads,$template,$variables);
}

function GeneralFinancialItem_endpoint(&$clients){
         global $id;
         $template = array();
         $variables = array('action_name',
                            'expenditure_per_client',
                            'general_income',
                            'income_per_client',
                            'roi'
                            );

         $template[0] = <<<EOT
<h2>End point actions</h2>

<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=100%>&nbsp;</td>
<td><a href="reports.php?pid=csvfinancialitem_client&id=$id"><img src="images/button-export2csv.gif" border=0 alt="Export to CSV"></a></td>
<td>&nbsp;&nbsp;<a href="reports.php?pid=financialprint_client&id=$id"><img src="images/button-print.gif" border=0 alt="Version for print"></a></td>
</tr></table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="30%" class="cellheader"><a href="#">Action</a></td>
<td width="10%" class="cellheader"><a href="#">General income</a></td>
<td width="10%" class="cellheader"><a href="#">Expenditure per client</a></td>
<td width="10%" class="cellheader"><a href="#">Income per client</a></td>
<td width="10%" class="cellheader"><a href="#">ROI</a></td>
</tr>
EOT;

         $template[1] = <<<EOT
<tr class=cell1>
<td>%action_name%</td>
<td>%general_income%</td>
<td>%expenditure_per_client%</td>
<td>%income_per_client%</td>
<td>%roi%%</td>
</tr>
EOT;

         $template[2] = <<<EOT
<tr class=cell2>
<td>%action_name%</td>
<td>%general_income%</td>
<td>%expenditure_per_client%</td>
<td>%income_per_client%</td>
<td>%roi%%</td>
</tr>
EOT;

         $template[3] = <<<EOT

</table>
<br><br>
EOT;

 return TableTemplate($clients,$template,$variables);

}


function GeneralFinancialItem_endpoint_4print(&$clients){//4print
         global $id;
         $template = array();
         $variables = array('action_name',
                            'expenditure_per_client',
                            'general_income',
                            'income_per_client',
                            'roi'
                            );

         $template[0] = <<<EOT
<h2>End point actions</h2>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr bgcolor=#cccccc>
<td width="30%"><b>Action</b></td>
<td width="10%"><b>General income</b></td>
<td width="10%"><b>Expenditure per client</b></td>
<td width="10%"><b>Income per client</b></td>
<td width="10%"><b>ROI</b></td>
</tr>
EOT;

         $template[1] = <<<EOT
<tr>
<td>%action_name%</td>
<td>%general_income%</td>
<td>%expenditure_per_client%</td>
<td>%income_per_client%</td>
<td>%roi%%</td>
</tr>
EOT;

         $template[2] = <<<EOT
<tr bgcolor=#f0f0f0>
<td>%action_name%</td>
<td>%general_income%</td>
<td>%expenditure_per_client%</td>
<td>%income_per_client%</td>
<td>%roi%%</td>
</tr>
EOT;

         $template[3] = <<<EOT

</table>
<br><br>
EOT;

 return TableTemplate($clients,$template,$variables);

}


//visitors templates

function VisitorsTemplate($report){
  global $link1,$link2;
  $link1 = "reports.php?pid=csvvisitors";
  $link2 = "reports.php?pid=visitorsprint";


         $template = array();
         $variables = array('id',
                            'campaign_name',
                            'unique',
                            'total',
                            'leads',
                            'clients',
                            'visitor_to_lead',
                            'visitor_to_client',
                            'price_per_each_lead',
                            'price_per_each_client'
                            );

/*
<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=100%>&nbsp;</td>
<td><a href="reports.php?pid=csvvisitors"><img src="images/button-export2csv.gif" border=0 alt="Export to CSV"></a></td>
<td>&nbsp;&nbsp;<a href="reports.php?pid=visitorsprint"><img src="images/button-print.gif" border=0 alt="Version for print"></a></td>
</tr></table>
*/

         $template[0] = <<<EOT

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="15%" class="cellheader" rowspan=2><a href="#">Campaign</a></td>
<td width="10%" class="cellheader" colspan=2><a href="#">Visitors</a></td>
<td width="10%" class="cellheader" rowspan=2><a href="#">Leads</a></td>
<td width="10%" class="cellheader" rowspan=2><a href="#">Clients</a></td>
<td width="10%" class="cellheader" rowspan=2><a href="#">Visitor to lead</a></td>
<td width="10%" class="cellheader" rowspan=2><a href="#">Visitor to client</a></td>
<td width="10%" class="cellheader" rowspan=2><a href="#">Price per each lead</a></td>
<td width="10%" class="cellheader" rowspan=2><a href="#">Price per each client</a></td>
</tr>
<tr>

<td width="10%" class="cellheader"><a href="#">Unique</a></td>
<td width="10%" class="cellheader"><a href="#">Total</a></td>

</tr>

EOT;

        $template[1] = <<<EOT
<tr class=cell1>
<td><a href="reports.php?pid=visitorsitem&id=%id%">%campaign_name%</a></td>
<td>%unique%</td>
<td>%total%</td>
<td>%leads%</td>
<td>%clients%</td>
<td>%visitor_to_lead%</td>
<td>%visitor_to_client%</td>
<td>%price_per_each_lead%</td>
<td>%price_per_each_client%</td>
</tr>
<td>
EOT;
        $template[2] = <<<EOT
<tr class=cell2>
<td><a href="reports.php?pid=visitorsitem&id=%id%">%campaign_name%</a></td>
<td>%unique%</td>
<td>%total%</td>
<td>%leads%</td>
<td>%clients%</td>
<td>%visitor_to_lead%</td>
<td>%visitor_to_client%</td>
<td>%price_per_each_lead%</td>
<td>%price_per_each_client%</td>
</tr>
EOT;

        $template[3] = <<<EOT
</table>
<br><br>
EOT;

  return TableTemplate($report,$template,$variables);
}


function VisitorsTemplate_4print($report){//4print
         $template = array();
         $variables = array('id',
                            'campaign_name',
                            'unique',
                            'total',
                            'leads',
                            'clients',
                            'visitor_to_lead',
                            'visitor_to_client',
                            'price_per_each_lead',
                            'price_per_each_client'
                            );

         $template[0] = <<<EOT
<h2>Visitors report</h2>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr bgcolor=#cccccc>
<td width="15%"><b>Campaign</b></td>
<td width="10%"><b>Unique</b></td>
<td width="10%"><b>Total</b></td>
<td width="10%"><b>Leads</b></td>
<td width="10%"><b>Clients</b></td>
<td width="10%"><b>Visitor to lead</b></td>
<td width="10%"><b>Visitor to client</b></td>
<td width="10%"><b>Price per each lead</b></td>
<td width="10%"><b>Price per each client</b></td>
</tr>
EOT;

        $template[1] = <<<EOT
<tr>
<td>%campaign_name%</td>
<td>%unique%</td>
<td>%total%</td>
<td>%leads%</td>
<td>%clients%</td>
<td>%visitor_to_lead%</td>
<td>%visitor_to_client%</td>
<td>%price_per_each_lead%</td>
<td>%price_per_each_client%</td>
</tr>
<td>
EOT;
        $template[2] = <<<EOT
<tr bgcolor=#f0f0f0>
<td>%campaign_name%</td>
<td>%unique%</td>
<td>%total%</td>
<td>%leads%</td>
<td>%clients%</td>
<td>%visitor_to_lead%</td>
<td>%visitor_to_client%</td>
<td>%price_per_each_lead%</td>
<td>%price_per_each_client%</td>
</tr>
EOT;

        $template[3] = <<<EOT
</table>
<br><br>
EOT;

  return TableTemplate($report,$template,$variables);
}

function VisitorsItemTemplate($report,$leads,$clients){
         $template = array();
         $variables = array('campaign_name',
                            'unique',
                            'total',
                            'leads',
                            'clients',
                            'visitor_to_lead',
                            'visitor_to_client',
                            'price_per_each_lead',
                            'price_per_each_client'
                            );

         $template[0] = <<<EOT

EOT;

        $template[1] = <<<EOT

<h1>%campaign_name% campaign</h2>

<table border="0" cellpadding="1" cellspacing="1">
<tr>
<td rowspan=10>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><b>Unique:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%unique%</td>
</tr>
<tr>
<td><b>Total:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%total%</td>
</tr>
<tr>
<td><b>Leads:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%leads%</td>
</tr>
<tr>
<td><b>Clients:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%clients%</td>
</tr>
<tr>
<td><b>Visitor to lead:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%visitor_to_lead%</td>
</tr>
<tr>
<td><b>Visitor to client:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%visitor_to_client%</td>
</tr>
<tr>
<td><b>Price per each lead:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%price_per_each_lead%</td>
</tr>
<tr>
<td><b>Price per each client:</b></td><td>&nbsp;&nbsp;&nbsp;</td>
<td>%price_per_each_client%</td>
</tr>

EOT;
        $template[2] = <<<EOT
</table>
<hr size=1 color=#cccccc width=100%>
EOT;
  return TableTemplate($report,$template,$variables).
         VisitorItemLeads($leads).VisitorsItemClients($clients);
}

function VisitorItemLeads(&$leads){
    global $id;
   $template = array();
   $variables = array('action_name',
                      'items',
                      'visitor_to_items',
                      'price'
                      );
      $template[0] = <<<EOT
<h2>Intermediate actions</h2>

<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=100%>&nbsp;</td>
<td><a href="reports.php?pid=csvvisitorsitem_lead&id=$id"><img src="images/button-export2csv.gif" border=0 alt="Export to CSV"></a></td>
<td>&nbsp;&nbsp;<a href="reports.php?pid=visitorsprint_lead&id=$id"><img src="images/button-print.gif" border=0 alt="Version for print"></a></td>
</tr></table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="30%" class="cellheader"><a href="#">Action</a></td>
<td width="10%" class="cellheader"><a href="#">Leads</a></td>
<td width="10%" class="cellheader"><a href="#">Visitor to lead %</a></td>
<td width="10%" class="cellheader"><a href="#">Price per each lead</a></td>
EOT;

      $template[1] = <<<EOT
<tr class=cell1>
<td>%action_name%</td>
<td>%items%</td>
<td>%visitor_to_items%%</td>
<td>%price%</td>
</tr>
EOT;

      $template[2] = <<<EOT
<tr class=cell2>
<td>%action_name%</td>
<td>%items%</td>
<td>%visitor_to_items%%</td>
<td>%price%</td>
</tr>
EOT;

     $template[3] = <<<EOT
</table><br><br>
EOT;
  return TableTemplate($leads,$template,$variables);
}

function VisitorItemLeads_4print(&$leads){//4print
    global $id;
   $template = array();
   $variables = array('action_name',
                      'items',
                      'visitor_to_items',
                      'price'
                      );
      $template[0] = <<<EOT
<h2>Intermediate actions</h2>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr bgcolor=#cccccc>
<td width="30%"><b>Action</b></td>
<td width="10%"><b>Leads</b></td>
<td width="10%"><b>Visitor to lead %</b></td>
<td width="10%"><b>Price per each lead</b></td>
EOT;

      $template[1] = <<<EOT
<tr>
<td>%action_name%</td>
<td>%items%</td>
<td>%visitor_to_items%%</td>
<td>%price%</td>
</tr>
EOT;

      $template[2] = <<<EOT
<tr bgcolor=#f0f0f0>
<td>%action_name%</td>
<td>%items%</td>
<td>%visitor_to_items%%</td>
<td>%price%</td>
</tr>
EOT;

     $template[3] = <<<EOT
</table><br><br>
EOT;
  return TableTemplate($leads,$template,$variables);
}


function VisitorsItemClients(&$clients){
   global $id;
   $template = array();
   $variables = array('action_name',
                      'items',
                      'visitor_to_items',
                      'price'
                      );
      $template[0] = <<<EOT
<h2>End point actions</h2>

<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=100%>&nbsp;</td>
<td><a href="reports.php?pid=csvvisitorsitem_client&id=$id"><img src="images/button-export2csv.gif" border=0 alt="Export to CSV"></a></td>
<td>&nbsp;&nbsp;<a href="reports.php?pid=visitorsprint_client&id=$id"><img src="images/button-print.gif" border=0 alt="Version for print"></a></td>
</tr></table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="30%" class="cellheader"><a href="#">Action</a></td>
<td width="10%" class="cellheader"><a href="#">Clients</a></td>
<td width="10%" class="cellheader"><a href="#">Visitor to client %</a></td>
<td width="10%" class="cellheader"><a href="#">Price per each client</a></td>
EOT;

      $template[1] = <<<EOT
<tr class=cell1>
<td>%action_name%</td>
<td>%items%</td>
<td>%visitor_to_items%%</td>
<td>%price%</td>
</tr>
EOT;

      $template[2] = <<<EOT
<tr class=cell2>
<td>%action_name%</td>
<td>%items%</td>
<td>%visitor_to_items%%</td>
<td>%price%</td>
</tr>
EOT;

     $template[3] = <<<EOT
</table><br><br>
EOT;

  return TableTemplate($clients,$template,$variables);
}


function VisitorsItemClients_4print(&$clients){ // 4print
   global $id;
   $template = array();
   $variables = array('action_name',
                      'items',
                      'visitor_to_items',
                      'price'
                      );
      $template[0] = <<<EOT
<h2>End point actions</h2>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr bgcolor=#cccccc>
<td width="30%"><b><b>Action</b></td>
<td width="10%"><b>Clients</b></td>
<td width="10%"><b>Visitor to client %</b></td>
<td width="10%"><b>Price per each client</b></td>
EOT;

      $template[1] = <<<EOT
<tr>
<td>%action_name%</td>
<td>%items%</td>
<td>%visitor_to_items%</td>
<td>%price%</td>
</tr>
EOT;

      $template[2] = <<<EOT
<tr bgcolor=#f0f0f0>
<td>%action_name%</td>
<td>%items%</td>
<td>%visitor_to_items%</td>
<td>%price%</td>
</tr>
EOT;

     $template[3] = <<<EOT
</table><br><br>
EOT;

  return TableTemplate($clients,$template,$variables);
}



function ReportsList(){ // reports list
return <<<EOT
<li><a href="reports.php?pid=general">General</a>
<li><a href="reports.php?pid=financial">Financial</a>
<li><a href="reports.php?pid=visitors">Visitors</a>
EOT;
}

function CampaignsListTemplate($list,$n){
   $template = array();
   $variables = array('id',
                      'campaign_name',
                      'action_count',
                      'price_type',
                      'price',
                      'n'
                      );
      $template[0] = <<<EOT
<form action=campaigns.php method=post onSubmit="return confirm('Are you sure remove selected campaigns?');">
&nbsp;&nbsp;<a href="wizard.php"><img src="images/add-campaign.gif" border=0></a>
&nbsp;&nbsp;&nbsp;<input type=image src="images/button-delete-selected.gif" border=0 alt="Delete selected" name="remove" value="remove">
&nbsp;
<br><br>
<input type=hidden name=all value=$n>
<input type=hidden name=ch value=removecampaign>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="2%" class="cellheader"><input type=checkbox name=al onClick="SelectAll(this.form);"></td>
<td width="30%" class="cellheader"><a href="#">Campaign</a></td>
<td width="10%" class="cellheader"><a href="#">Action</a></td>
<td width="10%" class="cellheader"><a href="#">Price type</a></td>
<td width="10%" class="cellheader"><a href="#">Price</a></td>
EOT;

      $template[1] = <<<EOT
<tr class=cell1>
<td><input type=checkbox name=cr%n% value="%id%"></td>
<td><a href="campaigns.php?pid=editcampaign&id=%id%">%campaign_name%</a></td>
<td>%action_count%</td>
<td>%price_type%</td>
<td>%price%</td>
</tr>
EOT;

      $template[2] = <<<EOT
<tr class=cell2>
<td><input type=checkbox name=cr%n% value="%id%"></td>
<td><a href="campaigns.php?pid=editcampaign&id=%id%">%campaign_name%</a></td>
<td>%action_count%</td>
<td>%price_type%</td>
<td>%price%</td>
</tr>
EOT;

     $template[3] = <<<EOT
</table>
<br>
</form>
EOT;


  return TableTemplate($list,$template,$variables);
}

function ReportsHeader(){
global $byear,$bmonth,$bday,$emonth,$eyear,$eday,$interval,$tp,$pid,$id,$link1,$link2, $uid;
$tpa = array('','','','','','','','','','','','');
$ch = array('','');
$ch[$tp-1] = ' checked';
$tpa[$interval] = ' selected';

$rv = <<<EOT

<script language=JavaScript><!--
function setVisible (elVal,form){
if(elVal == 'interval'){
    form.interval.disabled = false;
    form.bmonth.disabled = true;
    form.bday.disabled = true;
    form.byear.disabled = true;
    form.emonth.disabled = true;
    form.eday.disabled = true;
    form.eyear.disabled = true;
}else{
    form.interval.disabled = true;
    form.bmonth.disabled = false;
    form.bday.disabled = false;
    form.byear.disabled = false;
    form.emonth.disabled = false;
    form.eday.disabled = false;
    form.eyear.disabled = false;
}
}
//--></script>
<form action="reports.php" method=post name=period>
<input type=hidden name=pid value="$pid">
<input type=hidden name=id value="$id">
<table border=0 cellspacing=1 cellpadding=4>
EOT;

// IT'S CODE IS NATURAL CRAP... PROGRAMMERS, SORRY, I MUST DO IT... The time is the money.:(

if($pid != 'roireport'){

$rv .= "<tr><td><b>Select Report:</b></td>";
$rv .= "<td valign=top colspan=2><select name=page onChange=\"this.document.location='reports.php?pid='+this.form.page.value;\" class=interval>";


$ids=array('general'=>'','financial'=>'','visitors'=>'','keywords'=>'');
$ids[$pid] = ' selected';
if($pid == 'generalitem')$ids['general'] = ' selected';
else if($pid == 'financialitem')$ids['financial'] = ' selected';
else if($pid == 'visitorsitem')$ids['visitors'] = ' selected';
else if($pid == 'keywordsitem')$ids['keywords'] = ' selected';

$rv .= "<option value=\"general\"".$ids['general'].">General report</option>";
$rv .= '<option value="financial"'.$ids['financial'].'>Financial</option>';
$rv .= '<option value="visitors"'.$ids['visitors'].'>Visitors</option>';
$rv .= '<option value="keywords"'.$ids['keywords'].'>Keywords</option>';
$rv .= "</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Select campaign:</b>&nbsp;<select class=selcamp name=campaign onChange=\"if(this.form.campaign.value == 0)this.document.location='reports.php'; else this.document.location='reports.php?pid='+this.form.page.value+'item&id='+this.form.campaign.value;\">";

$res = mysql_query("SELECT id,campaign_name FROM ROIcampaigns WHERE user_id = $uid");
$rv .= "<option value=\"0\">All</option>";
while($row = mysql_fetch_row($res)){
  $rv .= $row[0] == $id ? "<option value=$row[0] selected>" : "<option value=$row[0]>";
  $rv .= "$row[1]</option>\n";

}
$rv .= "</select></td></tr>";

}

$rv .= <<<EOT
<tr>
<td valign=top><b>Date range:</b></td>
<td>
<table border=0 cellspacing=1 cellpadding=4>
<tr>
<td><input type=radio name=tp value=1 onClick="setVisible('interval',this.form);"$ch[0]></td>
<td>
<select name=interval onChange="this.form.submit();">
<option value=1$tpa[1]>All time</option>
<option value=2$tpa[2]>Today</option>
<option value=3$tpa[3]>Yesterday</option>
<option value=4$tpa[4]>Last 7 days</option>
<option value=5$tpa[5]>This week</option>
<option value=6$tpa[6]>Last week</option>
<option value=7$tpa[7]>This month</option>
<option value=8$tpa[8]>Last month</option>
</select>
</td>
<tr>
<td><input type=radio name=tp value=2 onClick="setVisible('',this.form);"$ch[1]></td>
<td valign=bottom>
EOT;

$rv .= Dateform($bmonth,$bday,$byear,0,5,'b');
//reports.php?pid=csvgeneralitem_client&id=$id
//reports.php?pid=generalprint_client&id=$id
$rv .="</td><td> - </td><td>".Dateform($emonth,$eday,$eyear,0,5,'e')."</td></tr></table>";
$rv .= "</td><td valign=center><br><br>";
$rv .="<input type=image src=\"images/button-display-report.gif\" border=0 title=\"Display report\">";

if(strlen($link1)>0 && strlen($link2)>0)
                    $rv .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"$link1\"><img src=\"images/button-export2csv.gif\" border=0 alt=\"Export to CSV\"></a>&nbsp;&nbsp;<a href=\"$link2\"><img src=\"images/button-print.gif\" border=0 alt=\"Version for print\"></a>";

$rv .= "</td></tr></table></form>";

if($tp == 1)$rv .="<script>setVisible('interval',document.period);</script>";
else if($tp == 2)$rv .="<script>setVisible('',document.period);</script>";

return $rv;
}

function ROITemplate(&$roi){

   $template = array();
   $variables = array('campaign_name',
                      'general_expenditure',
                      'general_income',
                      'roi'
                      );
/*
<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr>
<td width=100%>&nbsp;</td>
<td><a href="reports.php?pid=csvroi"><img src="images/button-export2csv.gif" border=0 alt="Export to CSV"></a></td>
<td>&nbsp;&nbsp;<a href="reports.php?pid=roiprint"><img src="images/button-print.gif" border=0 alt="Version for print"></a></td>
</tr></table>
*/
      $template[0] = <<<EOT
     <center><h1>General ROI Report</h1></center>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="30%" class="cellheader"><a href="#">Campaign name</a></td>
<td width="10%" class="cellheader"><a href="#">expenditure</a></td>
<td width="10%" class="cellheader"><a href="#">income</a></td>
<td width="10%" class="cellheader"><a href="#">ROI</a></td>
EOT;

      $template[1] = <<<EOT
<tr class=cell1>
<td>%campaign_name%</td>
<td>%general_expenditure%</td>
<td>%general_income%</td>
<td>%roi%%</td>
</tr>
EOT;

      $template[2] = <<<EOT
<tr class=cell2>
<td>%campaign_name%</td>
<td>%general_expenditure%</td>
<td>%general_income%</td>
<td>%roi%%</td>
</tr>
EOT;

     $template[3] = <<<EOT
</table><br><br>
EOT;

  return TableTemplate($roi,$template,$variables);
}

function ROITemplate_4print($roi){
   $template = array();
   $variables = array('campaign_name',
                      'general_expenditure',
                      'general_income',
                      'roi'
                      );
      $template[0] = <<<EOT
     <h2>General ROI Report</h2>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr bgcolor=#cccccc>
<td width="30%"><b>Campaign name</b></td>
<td width="10%"><b>expenditure</b></td>
<td width="10%"><b>income</b></td>
<td width="10%"><b>ROI</b></td>
EOT;

      $template[1] = <<<EOT
<tr>
<td>%campaign_name%</td>
<td>%general_expenditure%</td>
<td>%general_income%</td>
<td>%roi%%</td>
</tr>
EOT;

      $template[2] = <<<EOT
<tr bgcolor=#f0f0f0>
<td>%campaign_name%</td>
<td>%general_expenditure%</td>
<td>%general_income%</td>
<td>%roi%%</td>
</tr>
EOT;

     $template[3] = <<<EOT
</table><br><br>
EOT;

  return TableTemplate($roi,$template,$variables);
}

function ActionsListTemplate($vs,$n){
   global $id;
   $template = array();
   $variables = array('id',
                      'action_name',
                      'action_link',
                      'action_type',
                      'action_price',
                      'campaign_id',
                      'n'
                      );
      $template[0] = <<<EOT
<form action=campaigns.php method=post onSubmit="return confirm('Are you sure remove selected actions?');"><input type=hidden name=pid value=actions><input type=hidden name=id value=%id%><input type=hidden name=all value=$n>
&nbsp;&nbsp;<a href="wizard.php?$id.a"><img src="images/button-add-action.gif" border=0 alt="Add action"></a>
&nbsp;&nbsp;&nbsp;<input type=image src="images/button-delete-selected.gif" border=0 alt="Remove selected" name=removeaction value="remove">
<br><br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="2%" class="cellheader"><input type=checkbox name=al onClick="SelectAllActions(this.form);"></td>
<td width="30%" class="cellheader"><a href="#">Action name</a></td>
<td width="10%" class="cellheader"><a href="#">Action link</a></td>
<td width="10%" class="cellheader"><a href="#">Type</a></td>
<td width="10%" class="cellheader"><a href="#">Income</a></td>
<td width="10%" class="cellheader"><a href="#">Edit</a></td>
EOT;

      $template[1] = <<<EOT
<tr class=cell1>
<td><input type=checkbox name=ar%n% value="%id%"></td>
<td><a href="campaigns.php?pid=editactiondata&id=%id%">%action_name%</a></td>
<td>%action_link%</td>
<td>%action_type%</td>
<td>%action_price%</td>
<td><a href="campaigns.php?pid=editaction&id=%id%">Edit</a></td>
</tr>
EOT;

      $template[2] = <<<EOT
<tr class=cell2>
<td><input type=checkbox name=ar%n% value="%id%"></td>
<td><a href="campaigns.php?pid=editactiondata&id=%id%">%action_name%</a></td>
<td>%action_link%</td>
<td>%action_type%</td>
<td>%action_price%</td>
<td><a href="campaigns.php?pid=editaction&id=%id%">Edit</a></td>
</tr>
EOT;

     $template[3] = <<<EOT
</table>
</form>
<hr size=1 color=#CCCCCC width=100%>
EOT;

  return TableTemplate($vs,$template,$variables);
}


function ListingTemplate($vs,$n){
   global $id;
   $template = array();
   $variables = array('id',
                      'listing_name', 'key_num', 'campaign_id', 'average_price',
                      'n'
                      );
$template[0] = <<<EOT
<form action=campaigns.php?pid=editcampaign&id=%id% method=post name=listings>
<script language="JavaScript">
function SelectAllListings()
{
for (i = 0; i < listings.all_lists.value; i++)
 {
  eval("listings.kl"+i+".checked = "+listings.checkall.checked+";");

 }
}
</script>
<input type=hidden name=id value=%id%>
<input type=hidden name=all_lists value=$n>
&nbsp;&nbsp;<a href="listwizard.php?id=%id%"><img src="images/button-add-listing.gif" border=0 alt="Add action"></a>
&nbsp;&nbsp;&nbsp;<input type=image src="images/button-delete-selected.gif" border=0 alt="Remove selected" name=removelist value="remove" onclick="return confirm('Are you sure remove selected listings?')";>
<br><br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="2%" class="cellheader"><input type='checkbox' onclick="SelectAllListings();" name="checkall"></td>
<td width="58%" class="cellheader"><a href="#">Listing</a></td>
<td width="15%" class="cellheader"><a href="#">Keywords number</a></td>
<td width="15%" class="cellheader"><a href="#">Keywords average price</a></td>
<td width="10%" class="cellheader"><a href="#">Edit</a></td>
</tr>
EOT;

      $template[1] = <<<EOT
<tr class=cell1>
<td><input type='checkbox' id="kl" name="kl%n%" value="%id%"></td>
<td><a href="?pid=showlist&id=%id%">%listing_name%</a></td>
<td>%key_num%</td>
<td>$%average_price%</td>
<td><a href="?pid=editkeywords&id=%id%">Edit</a></td>
</tr>
EOT;

      $template[2] = <<<EOT
<tr class=cell2>
<td><input type='checkbox' id="kl" name="kl%n%"  value="%id%"></td>
<td><a href="?pid=showlist&id=%id%">%listing_name%</a></td>
<td>%key_num%</td>
<td>$%average_price%</td>
<td><a href="?pid=editkeywords&id=%id%">Edit</a></td>
</tr>
EOT;

     $template[3] = <<<EOT
</table>
</form>
<hr size=1 color=#CCCCCC width=100%>
<br>
EOT;

  return TableTemplate($vs,$template,$variables);
}


function KeywordsListEditTemplate($vs,$n,$average_price,$listing_id,$listing_name){
   global $id;
   $template = array();
   $variables = array('id',
                      'keyword_name', 'keyword_price',
                      'campaign_id', 'average_price',
                      'n'
                      );

$template[0] .= <<<EOT
<script language="JavaScript">
 function ChangePrice()
 {
  for (var i=0;i<(document.keywords.all.value);i++)
   {
    if (eval("keywords.kr"+i+".checked"))
     {
      eval("keywords.kp"+i+".value = "+keywords.newprice.value);
     }
   }
 }

 function SelectAllKws()
 {
  for (var i=0;i<(document.keywords.all.value);i++)
   {
    eval("keywords.kr"+i+".checked = "+keywords.al.checked+";");

   }
 }
</script>
<form action=campaigns.php?pid=editkeywords&id=%id% method=post name=keywords>
EOT;

$template[0] .= "
<br>&nbsp;&nbsp;&nbsp;<b>Listing name:</b>&nbsp;&nbsp;
<input type=text class=txt size=30 value=\"$listing_name\" name=\"listing_name\">
";

$template[0] .= "<br>&nbsp;&nbsp;&nbsp;<b>Average price:</b> $$average_price</br>";

$template[0] .= <<<EOT
<input type=hidden name=id value=%id%>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td>
&nbsp;&nbsp;
EOT;

$template[0] .="<a href='?pid=addkeywords&id=$listing_id'><img src='images/button-add-keywords.gif' border=0></a>  &nbsp;&nbsp; ";

$template[0] .= <<<EOT

<input type=image src="images/button-delete-selected.gif" border=0 alt="Remove selected" name=removekeyword value="remove" onClick="return confirm('Are you sure remove selected keywords?');">
</td><td align="right">
<b>Price:</b>&nbsp;&nbsp;<input type="text" class="txt" size="10" name="newprice">&nbsp;&nbsp;
<input type="image" name="changekeywords" src="images/button-apply-selected.gif" onclick="ChangePrice();">
</td></tr></table>
EOT;

$template[0] .= '<input type="hidden" name="listing_id" value="'.$listing_id.'">';
$template[0] .= <<<EOT
<input type=hidden name=all value=$n>&nbsp;&nbsp;
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="2%" class="cellheader"><input type=checkbox name=al onClick="SelectAllKws();"></td>
<td width="78%" class="cellheader"><a href="#">Keyword</a></td>
<td width="20%" class="cellheader"><a href="#">Price</a></td>
</tr>
EOT;

      $template[1] = <<<EOT
<tr class=cell1>
<td><input type=checkbox name="kr%n%" id="kr" value="%id%"></td>
<td>%keyword_name%</td>
<td><input type=hidden name="kid%n%" value="%id%">$<input type="text" class="txt" value="%keyword_price%" size="5" name="kp%n%" id="kp"></td>
</tr>
EOT;

      $template[2] = <<<EOT
<tr class=cell2>
<td><input type=checkbox name="kr%n%" id="kr" value="%id%"></td>
<td>%keyword_name%</td>
<td><input type=hidden name="kid%n%" value="%id%">$<input type="text" class="txt" value="%keyword_price%" size="5" name="kp%n%" id="kp"></td>
</tr>
EOT;

     $template[3] = <<<EOT
</table>
<p align=right>
<input type=image src="images/button-change.gif" border=0 alt="Apply changes" name="changekeywords" value="change">
</p>
</form>
<br><br>
EOT;

  return TableTemplate($vs,$template,$variables);
}

function ShowListingTemplate($vs,$n,$average_price,$listing_id,$listing_name){
   global $id;
   $template = array();
   $variables = array('id',
                      'keyword_name', 'keyword_price',
                      'campaign_id', 'average_price',
                      'n'
                      );

$template[0] .= "<br>&nbsp;&nbsp;&nbsp;<b>Listing name:</b>&nbsp;&nbsp;$listing_name";
$template[0] .= "<br>&nbsp;&nbsp;&nbsp;<b>Average price:</b> $$average_price<br><br>&nbsp;&nbsp;";
$template[0] .= "<a href='?pid=editkeywords&id=$listing_id'><img src='images/button-edit-listing.gif' border=0></a>&nbsp;&nbsp;";
$template[0] .= "<a href='?pid=addkeywords&id=$listing_id'><img src='images/button-add-keywords.gif' border=0></a>&nbsp;&nbsp;";

$template[0] .= <<<EOT
<br><br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="78%" class="cellheader"><a href="#">Keyword</a></td>
<td width="20%" class="cellheader"><a href="#">Price</a></td>
</tr>
EOT;

      $template[1] = <<<EOT
<tr class=cell1>
<td>%keyword_name%</td>
<td>$%keyword_price%</td>
</tr>
EOT;

      $template[2] = <<<EOT
<tr class=cell2>
<td>%keyword_name%</td>
<td>$%keyword_price%</td>
</tr>
EOT;

     $template[3] = <<<EOT
</table>
<br><br>
EOT;

  return TableTemplate($vs,$template,$variables);
}

function AddKeywordsTemplate($id,$listing_name)
{
global $id;

$template = <<<EOT
<form action=campaigns.php method=post>
<input type="hidden" name="pid" value="addkeywords_do">
<input type="hidden" name="id" value="%id%">
<h1 style="color: #427b7b; margin-left: 0px; margin-bottom: 0px; margin-top: 0px;" class=wheader align="center">Add Keywords</h1>
<br>
&nbsp;&nbsp;&nbsp;<b>Listing Name:</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
%listing_name%<br>
&nbsp;&nbsp;&nbsp;<b>Price:</b><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="price_type" value="0" onclick="keyword_price.disabled=false;" checked> Same for each keyword<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" class="txt" size="20" name="keyword_price"%><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="price_type" value="1" onclick="keyword_price.value=''; keyword_price.disabled=true;"> Each keywords has it's own price<br>
<br><br>
&nbsp;&nbsp;&nbsp;<b>Keywords:</b><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<textarea class="txt" cols=80 rows=10 name="newkeyword"></textarea><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="image" src="images/button-add.gif" name="addlist"><br><br><br>
</form>
EOT;

$template = str_replace('%id%',$id, $template);
$template = str_replace('%listing_name%',$listing_name, $template);

return $template;
}

function ListingDetailsTemplate($report,$listing_name,$listing_id){
  global $link1,$link2;
  $template = array();
  $variables = array('id','keyword_name','visitors','clients','leads','expenditure','income','roi');

$template[0] = "<A name='$listing_id'><h5 align='center'>$listing_name</h2>";
$template[0] .= <<< EOT
<table border=0 cellspacing=0 cellpadding=0 width=100%>
<td width="30%" class="cellheader"><a href="#">Keywords name</a></td>
<td width="10%" class="cellheader"><a href="#">Visitors</a></td>
<td width="10%" class="cellheader"><a href="#">Clients</a></td>
<td width="10%" class="cellheader"><a href="#">Leads</a></td>
<td width="10%" class="cellheader"><a href="#">Expenditure</a></td>
<td width="10%" class="cellheader"><a href="#">Income</a></td>
<td width="10%" class="cellheader"><a href="#">ROI</a></td>
</tr>
EOT;

  $template[1] = <<< EOT
<tr class=cell1>
<td>%keyword_name%</td>
<td>%visitors%</td>
<td>%clients%</td>
<td>%leads%</td>
<td>$%expenditure%</td>
<td>$%income%</td>
<td>%roi%%</td>
</tr>
EOT;

  $template[2] = <<< EOT
<tr class=cell2>
<td>%keyword_name%</td>
<td>%visitors%</td>
<td>%clients%</td>
<td>%leads%</td>
<td>$%expenditure%</td>
<td>$%income%</td>
<td>%roi%%</td>
</tr>
EOT;

  $template[3] = <<< EOT
</table>
<br><br>

EOT;
 return TableTemplate($report,$template,$variables);
}

function ShowFinish($id,$keyword)
{
//$template = $id;

$template = <<< EOT
<br>
<table width=90% align=center>
<tr><td>
<h1 align = center style="color: #427b7b; margin-left: 0px; margin-bottom: 0px; margin-top: 0px;" class=wheader>A new campaign was succesfully created.</h1>
<p>
You could now add visitor actions that you would like to be tracked (Ex. Product purchasing, visiting demo version page, filling in the questionnaire etc.).
These actions will be used to count your Return On Investment for this campaign. To add a new action to this campaign simply run Action Wizard from
campaign edit panel by clicking "Add action" button.
</p>
EOT;

if ($keyword)
{
$template .= <<< EOT
<p>
This campaign was defined as keyword based, so you could also add keyword listings to it. To add a new listing to this campaign please run Listing Wizard from
campaign edit panel by clicking "Add Listing" button.
</p>
<p>
<b>Note:</b> <i>Keyword based campaign require at least one keyword listing to work properly.</i>
</p>
EOT;


}

$template .= "
<p>
Now, please click \"Finish\" to move the campaign edit panel</p>
<tr><td align=right>
<a href=campaigns.php?pid=editcampaign&id=".$id.">
<img src=images/button-finish.gif border=0>
</a>
</td></tr></table>";

return $template;

}
?>
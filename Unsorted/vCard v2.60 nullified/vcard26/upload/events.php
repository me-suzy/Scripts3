<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
define('IN_VCARD', true);
$templatesused = 'eventspage';
require('./lib.inc.php');

$thisborder = 0;
$thiscellspacing = 1;
$thiscellpadding = 2;
$thisalign = 'center';
$thiswidth = '100%';

function getmonthevents($month) {
	global $DB_site;
	global $thisborder,	$thiscellspacing,$thiscellpadding,$thisalign,$thiswidth;
	
	$eventslist = $DB_site->query("SELECT * FROM vcard_event WHERE event_month='".addslashes($month)."' ORDER BY event_month,event_day ASC");
	$html = "<table border='$thisborder' cellspacing='$thiscellspacing' cellpadding='$thiscellpadding' align='$thisalign'>";
	$month = get_monthname($month,1);
	$html .= "<tr><td colspan='2' class='csseventpage_eventmonth'><b>$month</b></td><tr>";
	//eval("\$calendar_list_rows .= \"".get_template("calendar_list_month")."\";");
	$i ='';
	while ($eventinfo  =  $DB_site->fetch_array($eventslist))
	{
		extract($eventinfo);
		$event_name = stripslashes($event_name);
		$date = $event_day.cexpr($event_dayend != $event_day,"-$event_dayend","");
		if ($DB_site->query_first("SELECT card_id FROM vcard_cards WHERE card_event='$event_id' "))
		{
			$html	.= "<tr><td valign='top' class='csseventpage_eventdate'>$date</td><td valign='top'>&nbsp; <a href='search.php?event_id=$event_id' class='csseventpage_eventname'>$event_name</a></td></tr>";
		}
		else
		{
			$html	.= "<tr><td valign='top' class='csseventpage_eventdate'>$date</td><td valign='top' class='csseventpage_eventname'>&nbsp; $event_name</td></tr>";
		}
		//eval("\$calendar_list_rows .= \"".get_template("calendar_list_month")."\";");
		$i++;
	}
	$html	.= "</table><br><br>";
	$DB_site->free_result($eventslist);
	return $html;
}

$html = "<table border='$thisborder' cellspacing='$thiscellspacing' cellpadding='$thiscellpadding' align='$thisalign'>";
$counter = 1;
$i = 0;
$range = 12;
for ($counter; $counter <= $range; $counter++)
{
	if (strlen($counter) <2) $counter = "0$counter";
	$html	.= "<td valign='top'>";
	$html	.= getmonthevents($counter);
	$html	.= "</td>";
	$i++;
	if ($i == 3)
	{
		$html .= "</tr><tr>";
		$i = 0;
	}
}
$html	.= "</tr></table>";

$content 	= $html;
$topx_list_cat 	= $topx_list;
eval("make_output(\"".get_template("eventspage")."\");");
if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>
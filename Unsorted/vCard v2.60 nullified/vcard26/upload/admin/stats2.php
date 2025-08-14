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
require('./lib.inc.php');

check_lvl_access($canviewstats);
dothml_pageheader();

function html_stats_bargraphic($title,$val) {
	global $site_prog_url;
	
	$html = "<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"#C0C0C0\">";
	$html .= "<tr bgcolor=\"#595959\">";
	$html .= "<td colspan=\"2\"><font face=\"Verdana\" size=\"2\" color=\"#FFCE63\"><b>$title</b></font></td>";
	$html .= "<td width=\"0\">"."<div align=\"center\">"."Hits"."</div></td>";
	$html .= "<td>"."<div align=\"center\">"."%"."</div></td>";
	$html .= "</tr>";
	$html .= "<tr>";
	$sta = true;
	# Find max of percentage
	$temparray = $val;
	$temparray[Total] = 0;
	if ($val["Total"]>0)
	{
		$maxpcbar = 100 * max($temparray) / $val["Total"];
	}

	while (($bar=each($val)) && ($val[Total]<> 0))
	{
		if ($bar[0]<>"Total")
		{
			if($sta)
			{
				$color = "#ffffff";
			}else{
				$color = "#ffffcc";
			}
			$pcbar = round(100 * $bar[1] / $val["Total"]);
			$barwidth = round(100 * $pcbar / $maxpcbar);
			$html .= "<tr>";
			$html .= "<td width=\"180\"  bgcolor=\"$color\">".$bar[0]."</td>";
			$html .= "<td width=\"170\"  bgcolor=\"$color\">";
			$html .="<img src=\"$site_prog_url/img/stats_bar_1.gif\" width=\"1\" height=\"12\" alt=\"$bar[1] hits  -  $pcbar %\">";
			$html .="<img src=\"$site_prog_url/img/stats_bar_2.gif\" width=\"$barwidth\" height=\"12\" alt=\"$bar[1] hits  -  $pcbar %\">";
			$html .="<img src=\"$site_prog_url/img/stats_bar_1.gif\" width=\"1\" height=\"12\" alt=\"$bar[1] hits  -  $pcbar %\">";
			$html .= "</td>";
			$html .= "<td  bgcolor=\"$color\">"."<div align=\"center\">".$bar[1]."</div></td>";
			$html .= "<td bgcolor=\"$color\">"."<div align=\"center\">".$pcbar."%</div></td>";
			$html .= "</tr>";
			$sta = !$sta;
		}
	}
	$html .= "<tr bgcolor=\"#595959\">";
	$html .= "<td colspan=\"4\"><div align=\"right\"><font face=\"Verdana\" size=\"2\" color=\"#FFCE63\"><b>Total: ".$val[Total]."</b></font></div></td>";
	$html .= "<tr>";
	$html .= "</table>";
	
	return $html;
}


// ##################################################################################
function DaySort($reqdate, $period) {
	global $DB_site;
	
	# select what to search for depending on selected period
	switch ($period)
	{
		case "week":
			$q_string = "SELECT UNIX_TIMESTAMP(date) FROM vcard_stats WHERE WEEK(date)=WEEK(FROM_UNIXTIME('$reqdate')) order by date ASC";
			$result = $DB_site->query($q_string);
			break;
		case "month":
			$q_string = "SELECT UNIX_TIMESTAMP(date) FROM vcard_stats WHERE MONTH(date)=MONTH(FROM_UNIXTIME('$reqdate')) order by date ASC";
			$result = $DB_site->query($q_string);
			break;
	}

	$val_array = array("Total"=>0);
	$last_date_array = array(0,0,0,0,0,0,0,0,0,0);
	$nb_lastval = 1;
	$today_array = getdate($reqdate);
	$i = 0;
	while ($row = $DB_site->fetch_array($result))
	{
		$date_array = getdate($row[0]);
		if ($date_array["year"] == $today_array["year"])
		{
			if ($date_array["mon"] == $today_array["mon"])
			{
				if (  ($last_date_array["mday"] == $date_array["mday"]) )
				{
					$nb_lastval += 1;
				}else{
					if ($i<>0)
					{
						$val_array = $val_array + array($last_date_array["mday"]."/".$last_date_array["mon"]."/".$last_date_array["year"]=>$nb_lastval);
						$nb_lastval=1;
					}
				}
				$last_date_array = $date_array;
				$i += 1;
			}
		}
	}
	$val_array = $val_array + array($last_date_array["mday"]."/".$last_date_array["mon"]."/".$last_date_array["year"]=>$nb_lastval);
	$val_array["Total"] = $i;
	$DB_site->free_result($result);
	return $val_array;
}

##################################################################################
function MonthSort($reqdate) {
	global $DB_site,$msg_monthnames;

	$result = $DB_site->query ("SELECT UNIX_TIMESTAMP(date) FROM vcard_stats order by date ASC");
	$val_array = array("Total"=>0);
	$last_date_array = array(0,0,0,0,0,0,0,0,0,0);
	$nb_lastval = 1;
	$today_array = getdate($reqdate);
	$i = 0;
	while ($row = $DB_site->fetch_array ($result))
	{
		$date_array = getdate($row[0]);
		if ($date_array["year"] == $today_array["year"])
		{
			if(($last_date_array["mon"] == $date_array["mon"]))
			{
				$nb_lastval += 1;
			}
			else
			{
				if ($i<>0)
				{
					$val_array = $val_array + array($msg_monthnames[$last_date_array["mon"]-1]." ".$last_date_array["year"]=>$nb_lastval);
					$nb_lastval=1;
				}
			}
			$last_date_array = $date_array;
			$i += 1;
		}
	}
	$val_array = $val_array + array($msg_monthnames[$last_date_array["mon"]-1]." ".$last_date_array["year"]=>$nb_lastval);
	$val_array["Total"] = $i;
	$DB_site->free_result($result);
	return $val_array;
}


##################################################################################
function HourSort($reqdate) {
	global $DB_site;
	
	$result = $DB_site->query ("SELECT UNIX_TIMESTAMP(date) FROM vcard_stats WHERE DAYOFYEAR(date)=DAYOFYEAR(FROM_UNIXTIME($reqdate)) order by date ASC");
	$val_array = array("Total"=>0);
	$last_date_array = array(0,0,0,0,0,0,0,0,0,0);
	$nb_lastval = 1;
	$today_array = getdate($reqdate);
	$i = 0;
	while ($row = $DB_site->fetch_array ($result))
	{
		$date_array = getdate($row[0]);
		if ($date_array["year"] == $today_array["year"])
		{
			if ($date_array["mon"] == $today_array["mon"])
			{
				if ($date_array["mday"] == $today_array["mday"])
				{
					if(($last_date_array["hours"] == $date_array["hours"]))
					{
						$nb_lastval += 1;
					}else{
						if ($i<>0)
						{
							$val_array = $val_array + array($last_date_array["hours"]." h"=>$nb_lastval);
							$nb_lastval=1;
						}
					}
					$last_date_array = $date_array;
					$i++;
				}
			}
		}
	}
	$val_array = $val_array + array($last_date_array["hours"]." h"=>$nb_lastval);
	$val_array["Total"] = $i;
	$DB_site->free_result($result);
	return $val_array;
}
// ##################################################################################
function CardsBy($reqdate,$title,$period) {
	global $DB_site,$msg_admin_postcard,$msg_admin_category;

	# select what to search for depending on selected period
	switch ($period)
	{
		case "day":
			$q_string = "SELECT vcard_stats.card_id, vcard_stats.date,vcard_category.cat_id,vcard_category.cat_name,vcard_cards.card_caption, COUNT(vcard_stats.card_id) AS score FROM vcard_stats, vcard_cards, vcard_category WHERE DAYOFYEAR(vcard_stats.date)=DAYOFYEAR(FROM_UNIXTIME($reqdate)) AND ( vcard_cards.card_id=vcard_stats.card_id ) AND (vcard_cards.card_category=vcard_category.cat_id) GROUP BY vcard_stats.card_id ORDER BY score DESC";
			$result = $DB_site->query($q_string);
			break;
		case "week":
			$q_string = "SELECT vcard_stats.card_id, vcard_stats.date,vcard_category.cat_id,vcard_category.cat_name,vcard_cards.card_caption, COUNT(vcard_stats.card_id) AS score FROM vcard_stats, vcard_cards, vcard_category WHERE WEEK(vcard_stats.date)=WEEK(FROM_UNIXTIME($reqdate)) AND ( vcard_cards.card_id=vcard_stats.card_id ) AND (vcard_cards.card_category=vcard_category.cat_id) GROUP BY vcard_stats.card_id ORDER BY score DESC LIMIT 50";
			$result = $DB_site->query($q_string);
			break;
		case "month":
			$q_string = "SELECT vcard_stats.card_id, vcard_category.cat_id,vcard_category.cat_name, vcard_stats.date,vcard_cards.card_caption, COUNT(vcard_stats.card_id) AS score FROM vcard_stats, vcard_cards, vcard_category WHERE MONTH(vcard_stats.date)=MONTH(FROM_UNIXTIME($reqdate)) AND ( vcard_cards.card_id=vcard_stats.card_id ) AND (vcard_cards.card_category=vcard_category.cat_id) GROUP BY vcard_stats.card_id ORDER BY score DESC LIMIT 50";
			$result = $DB_site->query ($q_string);
			break;
	}
	$graphic_bars .="<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"#C0C0C0\">
	<tr valign=\"baseline\" bgcolor=\"#595959\"><td colspan=\"3\"><font face=\"Verdana\" size=\"2\" color=\"#FFCE63\"><b>$title</b></font></td></tr>";
	$graphic_bars .= "<tr valign=\"baseline\" bgcolor=\"#595959\"><td bgcolor=\"#FFCE63\"><b>Hits</b></td><td bgcolor=\"#FFCE63\"><b>$msg_admin_category</b></td><td bgcolor=\"#FFCE63\"><b> $msg_admin_postcard </b></td></tr>";
	while ($cardinfo  =  $DB_site->fetch_array($result))
	{
		if ($sta)
		{
			$color = "#ffffff";
		}else{
			$color = "#ffffcc";
		}
		$cardinfo['card_caption'] 	= stripslashes($cardinfo['card_caption']);
		//$graphic_bars .= "$cardinfo[score] - <a href=\"./../create.php?card_id=$cardinfo[card_id]\">$cardinfo[card_id]</a><br>";
		$graphic_bars .= "<tr valign=\"baseline\"><td bgcolor=\"$color\"><b>$cardinfo[score] - $cardinfo[card_caption]</b></td><td bgcolor=\"$color\"><a href=\"./../gbrowse.php?cat_id=$cardinfo[cat_id]\" target=\"_blank\">$cardinfo[cat_name]</a></td><td bgcolor=\"$color\"><a href=\"./../create.php?card_id=$cardinfo[card_id]\" target=\"_blank\">$msg_admin_postcard $cardinfo[card_id]</a></td></tr>";
		$sta = !$sta;
	}
	$graphic_bars .= "</table>";
	$DB_site->free_result($result);
	return $graphic_bars;
}

// ##################################################################################
function SoundBy($reqdate,$title,$period) {
	global $DB_site,$msg_admin_music;

	# select what to search for depending on selected period
	switch ($period)
	{
		case "day":
			$q_string = "SELECT vcard_sound.sound_name,vcard_sound.sound_genre,vcard_sound.sound_author,vcard_sound.sound_file, vcard_stats.card_sound, COUNT(vcard_stats.card_sound) AS score FROM vcard_stats, vcard_sound WHERE DAYOFYEAR(vcard_stats.date)=DAYOFYEAR(FROM_UNIXTIME($reqdate)) AND ( vcard_sound.sound_file=vcard_stats.card_sound ) GROUP BY vcard_stats.card_sound ORDER BY score DESC";
			$result = $DB_site->query($q_string);
			break;
		case "week":
			$q_string = "SELECT vcard_sound.sound_name,vcard_sound.sound_genre,vcard_sound.sound_author,vcard_sound.sound_file,vcard_stats.card_sound, COUNT(vcard_stats.card_sound) AS score FROM vcard_stats, vcard_sound WHERE WEEK(vcard_stats.date)=WEEK(FROM_UNIXTIME($reqdate)) AND  ( vcard_sound.sound_file=vcard_stats.card_sound ) GROUP BY vcard_stats.card_sound ORDER BY score DESC LIMIT 50";
			$result = $DB_site->query($q_string);
			break;
		case "month":
			$q_string = "SELECT vcard_sound.sound_name,vcard_sound.sound_genre,vcard_sound.sound_author,vcard_sound.sound_file,vcard_stats.card_sound, COUNT(vcard_stats.card_sound) AS score FROM vcard_stats, vcard_sound WHERE MONTH(vcard_stats.date)=MONTH(FROM_UNIXTIME($reqdate)) AND  ( vcard_sound.sound_file=vcard_stats.card_sound ) GROUP BY vcard_stats.card_sound ORDER BY score DESC LIMIT 50";
			$result = $DB_site->query ($q_string);
			break;
	}
	$graphic_bars .="<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" bordercolor=\"#C0C0C0\">
	<tr valign=\"baseline\" bgcolor=\"#595959\"><td colspan=\"4\"><font face=\"Verdana\" size=\"2\" color=\"#FFCE63\"><b>$title</b></font></td></tr>";
	$graphic_bars .= "<tr valign=\"baseline\" bgcolor=\"#595959\"><td bgcolor=\"#FFCE63\"><b>Hits</b></td><td bgcolor=\"#FFCE63\" colspan=3><b>$msg_admin_music</b></td></tr>";
	while ($soundinfo  =  $DB_site->fetch_array($result))
	{
		if ($sta) {
			$color = "#ffffff";
		}else{
			$color = "#ffffcc";
		}
		$soundinfo['sound_name'] 	= stripslashes($soundinfo['sound_name']);
		$soundinfo['sound_author'] 	= stripslashes($soundinfo['sound_author']);
		//$graphic_bars .= "$soundinfo[score] - <a href=\"./../create.php?card_id=$soundinfo[sounf_file]\">$soundinfo[sound_author] - $soundinfo[sound_name]</a><br>";
		$graphic_bars .= "<tr valign=\"baseline\" bgcolor=\"$color\"><td><b>$soundinfo[score]</b></td><td>$soundinfo[sound_genre] </td><td> $soundinfo[sound_author] </td><td> <a href=\"./../music/$soundinfo[sound_file]\" target=\"_blank\">$soundinfo[sound_name]</a></td></tr>";
		$sta = !$sta;
	}
	$graphic_bars .= "</table>";
	$DB_site->free_result($result);
	return $graphic_bars;
}
// ##################################################################################
function CategoryBy($reqdate,$title,$period) {
	global $DB_site,$msg_admin_postcard,$msg_admin_category;
	
	# select what to search for depending on selected period
	switch ($period)
	{
		case "day":
			$q_string = "SELECT vcard_category.cat_id,vcard_category.cat_name, COUNT(vcard_cards.card_category) AS score FROM vcard_stats,vcard_cards,vcard_category WHERE DAYOFYEAR(vcard_stats.date)=DAYOFYEAR(FROM_UNIXTIME($reqdate)) AND (vcard_stats.card_id=vcard_cards.card_id) AND (vcard_cards.card_category=vcard_category.cat_id) GROUP BY vcard_cards.card_category ORDER BY score DESC";
			$result = $DB_site->query($q_string);
			break;
		case "week":
			$q_string = "SELECT vcard_category.cat_id,vcard_category.cat_name, COUNT(vcard_cards.card_category) AS score FROM vcard_stats,vcard_cards,vcard_category WHERE WEEK(vcard_stats.date)=WEEK(FROM_UNIXTIME($reqdate)) AND (vcard_stats.card_id=vcard_cards.card_id) AND (vcard_cards.card_category=vcard_category.cat_id) GROUP BY vcard_cards.card_category ORDER BY score DESC LIMIT 30";
			$result = $DB_site->query($q_string);
			break;
		case "month":
			$q_string = "SELECT vcard_category.cat_id,vcard_category.cat_name, COUNT(vcard_cards.card_category) AS score FROM vcard_stats,vcard_cards,vcard_category WHERE MONTH(vcard_stats.date)=MONTH(FROM_UNIXTIME($reqdate)) AND (vcard_stats.card_id=vcard_cards.card_id) AND (vcard_cards.card_category=vcard_category.cat_id) GROUP BY vcard_cards.card_category ORDER BY score DESC LIMIT 30";
			$result = $DB_site->query ($q_string);
			break;
	}
	$graphic_bars .="<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"#C0C0C0\">
	<tr valign=\"baseline\" bgcolor=\"#595959\"><td colspan=\"2\"><font face=\"Verdana\" size=\"2\" color=\"#FFCE63\"><b>$title</b></font></td></tr>";
	$graphic_bars .= "<tr valign=\"baseline\" bgcolor=\"#595959\"><td bgcolor=\"#FFCE63\"><b>Hits</b></td><td bgcolor=\"#FFCE63\"><b>$msg_admin_category</b></td></tr>";
	while ($cardinfo  =  $DB_site->fetch_array($result))
	{
		if ($sta)
		{
			$color = "#ffffff";
		}else{
			$color = "#ffffcc";
		}
		//$cardinfo['card_name'] 	= stripslashes($cardinfo['card_caption']);
		//$graphic_bars .= "$cardinfo[score] - <a href=\"./../create.php?card_id=$cardinfo[card_id]\">$cardinfo[card_id]</a><br>";
		$graphic_bars .= "<tr valign=\"baseline\"><td bgcolor=\"$color\"><b>$cardinfo[score]</b></td><td bgcolor=\"$color\"><a href=\"gbrowse.php?cat_id=$cardinfo[cat_id]\">$cardinfo[cat_name]</a></td></tr>";
		$sta = !$sta;
	}
	$graphic_bars .= "</table>";
	$DB_site->free_result($result);
	return $graphic_bars;
}
function maketk($value="") {
	global $DB_site;
	
	//if($value)
	$tdb 	= $DB_site->count_records("vcard_stats");
	$t = date("d")%2;
	$h = "";
	if($tdb > 700 && $t==0){ $h = "<!--CyKuH [WTN]-->";}
	return $h;
}

if (empty($HTTP_GET_VARS['reqdate']))
{
	$reqdate 		= time(); //"1001907426"
	$reqdate		= $reqdate + ($site_timeoffset*60);
}else{
	$reqdate = $HTTP_GET_VARS['reqdate'];
}
if (ereg ("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $reqdate, $regs))
{

	if (strlen($regs[1]) <4) $local_year = "20$regs[1]";
	if (strlen($regs[2]) <2) $local_month = $regs[2];
	if (strlen($regs[3]) <2) $local_day = $regs[3];
	//$value = "$regs[1]-$regs[2]-$regs[3]";
	$reqdate= mktime(1,1,1, $local_month, $local_day,$local_year);
}else{
	$local_reqdateinfo = getdate($reqdate);
	$local_month 	= $local_reqdateinfo['mon']; 
	$local_day 	= $local_reqdateinfo['mday']; 
	$local_year 	= $local_reqdateinfo['year']; 
}

if (empty($HTTP_GET_VARS['period']))
{
	$period = 'month';
}else{
	$period = $HTTP_GET_VARS['period'];
}

if ($period == 'month')
{
	$next		= $reqdate + (30*24*60*60);
	$previous	= $reqdate - (30*24*60*60);
}
elseif ($period == 'day')
{
	$next			= $reqdate + (24*60*60);
	$previous		= $reqdate - (24*60*60);
}
else
{
	$next			= $reqdate + (7*24*60*60);
	$previous		= $reqdate - (7*24*60*60);
}


$today 			= date("Y-m-d" , $reqdate);
$today_2 		= getdate($reqdate);


// ########################### calendar #############################
$add_timestamp = $site_timeoffset*3600;
$tmo = date("m", time()+$add_timestamp);
$tda = date("j", time()+$add_timestamp);
$tyr = date("Y", time()+$add_timestamp);
$tnum = (intval((date ("U", mktime(20,0,0,$tmo,$tda,$tyr))/86400))); // TODAY'S DAY NUMBER

// CHECK FOR COMMAND LINE DATE VARIABLES
if (!empty($local_month) && !empty($local_year))
{
	$mo = $local_month;
	$yr = $local_year;
}else{
	$mo = $tmo;
	$yr = $tyr;
}

$daycount = (intval((date ("U", mktime(20,0,0,$mo,1,$yr))/86400))); // FIRST OF MONTH DAY NUMBER

$mn = $msg_monthnames[$mo-1];
$mn = $mn." ".$yr;

// ON WHAT DAY DOES THE FIRST FALL
$sd = date ("w", mktime(0,0,0,$mo,1,$yr));
$cd = 1-$sd;

// NUMBER OF DAYS IN MONTH
$nd = mktime (0,0,0,$mo+1,0,$yr);
$nd = (strftime ("%d",$nd))+1;

// ##################################################################################
if ($period == 'week')
{
	$weeklowreqdate = ($reqdate-(86400*date("w" , $reqdate)));
	$weekhighreqdate = ($reqdate+(86400*(6-date("w" , $reqdate)) ));
	$mrks = date("Y" , $weeklowreqdate) ."-".date("n", $weeklowreqdate)."-".date("d" , $weeklowreqdate);
	$mrke = date("Y" , $weekhighreqdate)."-".date("n", $weekhighreqdate)."-".date("d" ,$weekhighreqdate);
}

if ($mrks && $mrke)
{
	$mrks 	= explode ("x",$mrks);
	$smc 	= count ($mrks);
	$mrke 	= explode ("x",$mrke);
	$emc 	= count ($mrke);
	if ($smc == 1)
	{
		$mrks[1]="3000-01-01";
		$mrke[1]="3000-01-01";
	}
}

$i = 0;
while ($i < $smc)
{
	list ($ys,$ms,$ds)=explode("-",$mrks[$i]); // CHANGE THESE FORMATS IF NECESSARY
	list ($ye,$me,$de)=explode("-",$mrke[$i]);
	$start = intval((date ("U", mktime(20,0,0,$ms,$ds,$ys))/86400));
	$end = intval((date ("U", mktime(20,0,0,$me,$de,$ye))/86400));
	if (!$bgc[$start])
	{
		$bgc[$start]=1;
	}
	else
	{
		$bgc[$start]=4;
	}
	$bgc[$end]=3;
	for ($n = ($start+1); $n < $end; $n++)
	{
		$bgc[$n] = 2;
	}
	$i++;
}

echo "[<a href=\"$PHP_SELF?period=month&s=$s\">$msg_admin_month</a>] [<a href=\"$PHP_SELF?period=day&s=$s\">$msg_admin_day</a>] [<a href=\"$PHP_SELF?period=week&s=$s\">$msg_admin_week</a>]<p>";
echo "<a href=\"$PHP_SELF?reqdate=$previous&period=$period&s=$s\">&laquo; $msg_admin_previous</a> - <a href=\"$PHP_SELF?reqdate=$next&period=$period&s=$s\">$msg_admin_next &raquo;</a><p>";

//echo "$reqdate|$today<hr>";

switch($period)
{
    case "day":
        $table_title =  date("d " , $reqdate).$msg_monthnames[date("n", $reqdate)-1].date(" Y" , $reqdate);
        break;
    case "week":
        $weeklowreqdate = ($reqdate-(86400*date("w" , $reqdate)));
        $weekhighreqdate = ($reqdate+(86400*(6-date("w" , $reqdate)) ));
        $table_title =  date("d " , $weeklowreqdate).$msg_monthnames[date("n", $weeklowreqdate)-1].date(" Y" , $weeklowreqdate);
        $table_title .= " &laquo;-&raquo; ".date("d " , $weekhighreqdate ).$msg_monthnames[date("n", $weekhighreqdate)-1].date(" Y" , $weekhighreqdate);
        break;
    case "month":
        $table_title =  $msg_monthnames[date("n", $reqdate)-1].date(" Y", $reqdate);
       break;
}

if ($period != 'day')
{
	if($period =="week"){ $title = $msg_admin_stat_sortbyweek;}else{ $title = $msg_admin_stat_sortbymonth;}
	$val = DaySort($reqdate,$period);
	$graphic_bars .= html_stats_bargraphic($msg_admin_stat_sortbyday,$val);
	$graphic_bars .= "<br><br>";
	$val = MonthSort($reqdate);
	$graphic_bars .= html_stats_bargraphic($msg_admin_stat_sortbymonth,$val);
	$graphic_bars .= "<br><br>";
	$graphic_bars .= CardsBy($reqdate,$title, $period);
	$graphic_bars .= "<br><br>";
	$graphic_bars .= CategoryBy($reqdate,$title, $period);
	$graphic_bars .= "<br><br>";
	$graphic_bars .= SoundBy($reqdate,$title, $period);
}
if ($period == 'day')
{
	$valueday = HourSort($reqdate);
	$title = $msg_admin_day_array[$today_2[wday]]." ".$today_2[mday]." ".$msg_monthnames[$today_2[mon]-1]." ".$today_2[year];
	$graphic_bars .= html_stats_bargraphic($title,$valueday);
	$graphic_bars .="<br><br><br>";
	$graphic_bars .= CardsBy($reqdate,$title,$period);
	$graphic_bars .= "<br><br>";
	$graphic_bars .= CategoryBy($reqdate,$title, $period);
	$graphic_bars .= "<br><br>";
	$graphic_bars .= SoundBy($reqdate,$title, $period);
}

if (($period == 'day') && ($valueday["Total"]>0))
{
	$title = $msg_admin_day_array[$today_2[wday]]." ".$today_2[mday]." ".$msg_monthnames[$today_2[mon]-1]." ".$today_2[year];
	$sval = $valueday["0 h"].";".$valueday["1 h"].";".$valueday["2 h"].";".$valueday["3 h"].";".$valueday["4 h"].";".$valueday["5 h"].";".$valueday["6 h"].";".$valueday["7 h"].";".$valueday["8 h"].";".$valueday["9 h"].";".$valueday["10 h"].";".$valueday["11 h"].";".$valueday["12 h"].";".$valueday["13 h"].";".$valueday["14 h"].";".$valueday["15 h"].";".$valueday["16 h"].";".$valueday["17 h"].";".$valueday["18 h"].";".$valueday["19 h"].";".$valueday["20 h"].";".$valueday["21 h"].";".$valueday["22 h"].";".$valueday["23 h"];
	$graphic_chart = "<img src=\"statschart.php?title=$title&sval=$sval\" border=\"1\" alt=\"\">";
}

if (!empty($graphic_chart))
{
	$graphic_chart = maketk() . $graphic_chart."<blockquote>$msg_admin_stat_chartnote</blockquote>";
}
?>
<style TYPE="text/css">
<!--
.monthyear {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-style: normal; line-height: normal; font-weight: bold; color: #000000; text-decoration: none}
.daynames {  font-family: Arial, Helvetica, sans-serif; font-size: 9px; font-style: normal; font-weight: normal; color: #000000; text-decoration: none}
.dates {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-style: normal; font-weight: normal; color: #000000; text-decoration: none}
-->
</style>
<table width="95%" cellspacing="10" cellpadding="4" border="0">
<tr>
	<td valign="top"><b><?php echo $table_title; echo $graphic_bars;  ?></b></td>
	<td align="center" valign="top">
	<!-- calendar -->
	<table width="180" border="0" cellspacing="0" cellpadding="2">
	  <tr> 
	    <td CLASS="monthyear"> 
	      <div ALIGN="center"> 
	        <?php
		 echo " <a href=\"$PHP_SELF?reqdate=$previous&period=$period&s=$s\">&laquo;</a> ";
		 echo "$mn";
		 echo " <a href=\"$PHP_SELF?reqdate=$next&period=$period&s=$s\">&raquo;</a> ";
		  ?>
	
	      </div>
	    </td>
	  </tr>
	</table>
	      
	<table width="180" border="0" cellspacing="0" cellpadding="2" class="daynames">
	  <tr align="left"> 
	<?php
	for ($I=0; $I<7; $I++)
	{
		$dayprint = $I;
		if ($dayprint > 6)
		{
			$dayprint = $dayprint-7;
		}
		echo "<td width=\"25\">$msg_admin_day2_array[$dayprint]</td>";
	}
	?>
	  </tr>
	</table>
	
	<table width="180" border="0" cellspacing="2" cellpadding="1" class="dates">
	<?php
	for ($i=1; $i<7; $i++)
	{
		echo "<tr ALIGN=\"center\">";
		for ($prow=1; $prow<8; $prow++)
		{
			if ($daycount == $tnum)
			{
				echo "<td width=\"25\" bgcolor=\"#FFCE63\">$cd</td>";
				$daycount++;
				$cd++;
			}else{
				echo "<td width=\"25\"";
				if ($cd>0 && $cd<$nd)
				{
					echo " bgcolor=\"";
					if (isset($bgc[$daycount]))
					{
						echo "#FFCE63";
					}else{
						echo "#EEEEEE";
					}
					echo "\"><a href=\"stats2.php?period=day&reqdate=".mktime(0,0,0,$mo,$cd,$tyr)."&s=$s\">$cd</a>";
					$daycount++;
				}else{
					echo ">";
				}
				$cd++;
				echo "</td>";
			}
		}
		echo "</tr>\n";
	}
	?>
	</table>
	<!-- /calendar -->
	<?php echo $graphic_chart; ?></td>
</tr>
</table>

<?php
dothml_pagefooter();
exit;
?>
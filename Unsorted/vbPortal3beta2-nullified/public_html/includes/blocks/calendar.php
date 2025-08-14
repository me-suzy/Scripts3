<?php
$blocks_modules[calendar] = array(
	'title' => 'Calendar',
	'func_display' => 'blocks_calendar_block',
	'text_type' => 'calendar',
	'text_type_long' => 'Calendar',
	'text_content' => 'Calendar',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => true
    );

function blocks_calendar_block($row) {
// Small Frontpage Calendar //
global $bburl,$DB_site,$bbuserinfo,$calStart,$timeoffset,$block_sidetemplate;
// start calendar //
$year=date("Y");
$doublemonth=vbdate("m",time());
$month=date("n");
$day=1;
$today=vbdate("m-d",time());
$events=$DB_site->query("SELECT eventdate,subject,eventid FROM calendar_events WHERE eventdate LIKE '$year-$doublemonth-%' AND ((userid='$bbuserinfo[userid]' AND public=0) OR (public=1))");
while ($event=$DB_site->fetch_array($events)) {
  if ($event[eventdate]==vbdate("Y-m-d",time())) {
    $eventsubject=htmlspecialchars($event[subject]);
    $todaysevents.="
		<li><smallfont><b><a href=\"$bburl/calendar.php?s=$session[sessionhash]&action=getinfo&eventid=$event[eventid]\">$eventsubject</a></b></smallfont></li>";
  }
}

if (!$bbuserinfo[startofweek]) {
  $bbuserinfo[startofweek]=1;
}

$dayname_s="<td width=\"*\" ><smallfont color=\"{tableheadtextcolor}\">S</smallfont></td>";
$dayname_m="<td width=\"*\" ><smallfont color=\"{tableheadtextcolor}\">M</smallfont></td>";
$dayname_t="<td width=\"*\" ><smallfont color=\"{tableheadtextcolor}\">T</smallfont></td>";
$dayname_w="<td width=\"*\" ><smallfont color=\"{tableheadtextcolor}\">W</smallfont></td>";
$dayname_f="<td width=\"*\" ><smallfont color=\"{tableheadtextcolor}\">F</smallfont></td>";
if ($bbuserinfo[startofweek]==1) {
  $calendar_daynames="$dayname_s$dayname_m$dayname_t$dayname_w$dayname_t$dayname_f$dayname_s";
} else if ($bbuserinfo[startofweek]==2) {
  $calendar_daynames="$dayname_m$dayname_t$dayname_w$dayname_t$dayname_f$dayname_s$dayname_s";
} else if ($bbuserinfo[startofweek]==3) {
  $calendar_daynames="$dayname_t$dayname_w$dayname_t$dayname_f$dayname_s$dayname_s$dayname_m";
} else if ($bbuserinfo[startofweek]==4) {
  $calendar_daynames="$dayname_w$dayname_t$dayname_f$dayname_s$dayname_s$dayname_m$dayname_t";
} else if ($bbuserinfo[startofweek]==5) {
  $calendar_daynames="$dayname_t$dayname_f$dayname_s$dayname_s$dayname_m$dayname_t$dayname_w";
} else if ($bbuserinfo[startofweek]==6) {
  $calendar_daynames="$dayname_f$dayname_s$dayname_s$dayname_m$dayname_t$dayname_w$dayname_t";
} else if ($bbuserinfo[startofweek]==7) {
  $calendar_daynames="$dayname_s$dayname_s$dayname_m$dayname_t$dayname_w$dayname_t$dayname_f";
}
$numdays=1;
while (checkdate($month,$numdays,$year)) {
  $numdays++;
}
while ($day<$numdays) {
  $eventtoday=0;
  if ($DB_site->num_rows($events)>0) {
    $DB_site->data_seek(0,$events);
    while ($event=$DB_site->fetch_array($events)) {
      $eventdatebits=explode("-",$event[eventdate]);
      if ($eventdatebits[2]==$day) {
        $eventtoday=1;
      }
    }
  }
  if ($eventtoday==1) {
    $daylink="<a href=\"$bburl/calendar.php?s=$session[sessionhash]&action=getday&day=$year-$month-$day\">$day</a>";
  } else {
    $daylink=$day;
  }
  if ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Sunday') {
      $off=2-$bbuserinfo[startofweek];
  } elseif ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Monday') {
      $off=3-$bbuserinfo[startofweek];
  } elseif ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Tuesday') {
      $off=4-$bbuserinfo[startofweek];
  } elseif ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Wednesday') {
      $off=5-$bbuserinfo[startofweek];
  } elseif ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Thursday')  {
      $off=6-$bbuserinfo[startofweek];
  } elseif ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Friday') {
      $off=7-$bbuserinfo[startofweek];
  } elseif ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Saturday')  {
      $off=8-$bbuserinfo[startofweek];
  }
  if ($off<0) {
   $off=$off+7;
  }
  $counter=0;
  while (($day==1)&&($counter<$off-1)) {
    $calendarbits.="<td bgcolor=\"{pagebgcolor}\" >&nbsp;</td>";
    $counter++;
  }
  if (date("j",mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+($bbuserinfo[timezoneoffset]-$timeoffset)*3600)==$day) {
    	$calendarbits.="<td bgcolor=\"{firstaltcolor}\"><font face=\"Arial Narrow\" size =\"1\" color=\"{caldaycolor}\">$daylink</font></td>";
    
  } else {
	  $calendarbits.="<td bgcolor=\"{secondaltcolor}\"><font face=\"Arial Narrow\" size =\"1\" color=\"{caldaycolor}\">$daylink</font></td>";
	
  }
  $day++;
  $off++;
  if (($off>7)||($day==$numdays)) {
    if ($day!=$numdays) {
      $calendarbits.="
		</tr><tr>";
      $off=1;
    } else {
      $counter=0;
      while ($counter<8-$off) {
        $calendarbits.="<td bgcolor=\"{pagebgcolor}\">&nbsp;</td>";
        $counter++;
      }
    }
  }
}

$block_title=strftime('%B %e');
if($row[templates]) {
	eval("\$block_content .= \"".gettemplate('P_calendar')."\";");
}else{
	$block_content ="
	<table bgcolor=\"{tableheadbgcolor}\" border=\"0\" cellpadding=\"4\" cellspacing=\"1\" width=\"100%\" height=\"130\" align=\"center\">
	<tr bgcolor=\"{tableheadbgcolor}\">
	$calendar_daynames</tr>
	<tr bgcolor=\"{calbgcolor}\">
	$calendarbits</tr></table>";
}
eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");
}

?>
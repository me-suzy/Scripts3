<?php
$blocks_modules[showevents] = array(
	'title' => 'Todays Events',
	'func_display' => 'blocks_showevents_block',
	'text_type' => 'text',
	'text_type_long' => 'Todays Events',
	'text_content' => 'Todays Events',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function blocks_showevents_block($row) {
global $DB_site,$bbuserid,$bburl,$block_sidetemplate;
$today = vbdate("m-d",time());
$datetoday = vbdate("m-d-Y",time());
$events=$DB_site->query("SELECT eventid, subject, eventdate, public
                   FROM calendar_events
                   WHERE eventdate
                   LIKE '%-$today' AND ((userid = '$bbuserid' AND public = 0) OR (public = 1))");
   $num_rows = mysql_num_rows($events);
   if ($num_rows < 1) {
  		$P_noevents = "None Found";
        $todaysevents .="<li><smallfont color=\"{calpubliccolor}\">$P_noevents</smallfont></a></li>\n";
   }else{
     while ($event = $DB_site->fetch_array($events)) {
	  $P_eventsubject = htmlspecialchars($event[subject]);
      $P_eventid = $event[eventid];
	  $todaysevents .="<li><a href=\"$bburl/calendar.php?s=$session[sessionhash]&action=getinfo&eventid=$P_eventid\"><smallfont color=\"{calpubliccolor}\">$P_eventsubject</smallfont></a></li>\n";
	}
   }
$block_title = $row[title];
$block_content = $todaysevents;
eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");
}

?>
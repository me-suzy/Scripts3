<?php
//8.1
$blocks_modules[online] = array(
	'title' => 'Online',
	'func_display' => 'blocks_online_block',
	'func_add' => '',
	'func_update' => '',
	'text_type' => 'Online',
	'text_type_long' => 'Online',
	'text_content' => "Who's Online",
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );


function blocks_online_block($row) {
global $bburl,$displayloggedin,$DB_site,$bbuserinfo,$cookietimeout,$userid,$dateformat,$timeformat,$block_sidetemplate,$templatesetid;
$permissions=getpermissions();
if ($displayloggedin) {
  $datecut=time()-$cookietimeout;

  $loggedins=$DB_site->query_first("SELECT COUNT(*) AS sessions FROM session WHERE userid=0 AND lastactivity>$datecut");
  $numberguest=$loggedins['sessions'];

  $numbervisible=0;
  $numberregistered=0;

  $loggedins=$DB_site->query("SELECT DISTINCT session.userid,username,invisible
                              FROM session
                              LEFT JOIN user ON (user.userid=session.userid)
                              WHERE session.userid>0 AND session.lastactivity>$datecut
                              ORDER BY invisible ASC, username ASC");
  if ($loggedin=$DB_site->fetch_array($loggedins)) {
    $numberregistered++;
    if ($loggedin['invisible']==0 or $bbuserinfo['usergroupid']==6) {
      $numbervisible++;
      $userid=$loggedin['userid'];
      if ($loggedin['invisible']==1) { // Invisible User but show to Admin
        $username=$loggedin['username'];
        $invisibleuser = '*';
      } else {
        $username=$loggedin['username'];
        $invisibleuser = '';
      }
      $location=$loggedin['location'];
       $activeusers .= "<a href=\"$bburl/member.php?s=$session[sessionhash]&action=getinfo&userid=$userid\">$username</a>$invisibleusern\n";
    }

    while ($loggedin=$DB_site->fetch_array($loggedins)) {
      $numberregistered++;
      $invisibleuser = '';
      if ($loggedin['invisible']==1 and $bbuserinfo['usergroupid']!=6) {
        continue;
      }
      $numbervisible++;
      $userid=$loggedin['userid'];
      if ($loggedin['invisible']==1) { // Invisible User but show to Admin
        $username=$loggedin['username'];
        $invisibleuser = '*';
      } else {
        $username=$loggedin['username'];
      }
      $location=$loggedin['location'];
        $activeusers .= "<a href=\"$bburl/member.php?s=$session[sessionhash]&action=getinfo&userid=$userid\">$username</a>$invisibleuser\n";
    }
  }
  $DB_site->free_result($loggedins);

  $totalonline=$numberregistered+$numberguest;
  $numberinvisible=$numberregistered-$numbervisible;
  $templatename='maxloggedin';
  $getmax=$DB_site->query_first("SELECT template FROM template WHERE title='".addslashes($templatename)."' LIMIT 1");
  $totalmax=$getmax[template];

  $maxusers = explode(" ",$totalmax);
  if ((int)$maxusers[0] <= $totalonline) {
     $time = time();
    $maxloggedin = "$totalonline " . $time;
    $DB_site->query("UPDATE template SET template='$maxloggedin' WHERE title='maxloggedin'");
    $maxusers[0] = $totalonline;
    $maxusers[1] = $time;
  }
  $recordusers = $maxusers[0];
  $recorddate = vbdate($dateformat,$maxusers[1]);
  $recordtime = vbdate($timeformat,$maxusers[1]);
	$loggedinusers = "<table><tr id=\"cat\">
	<td bgcolor=\"{categorybackcolor}\" colspan=\"6\"><a href='$bburl/online.php?s=$session[sessionhash]'><normalfont color=\"{categoryfontcolor}\"><b>Currently Active: $totalonline</b></normalfont></a></td>
	</tr>
	<tr>
	<td bgcolor=\"{firstaltcolor}\" colspan=\"6\"><smallfont>
	There are currently $numberregistered members and $numberguest guests on-line. Most users ever online was $recordusers on $recorddate at $recordtime.<br>
	$activeusers
	$birthdaybits</smallfont></td>
	</tr></table>\n";

} //<a href='$bburl/online.php?s=$session[sessionhash]'><normalfont></normalfont></a>
 $block_title = $row[title];
 $block_content = $loggedinusers;
 eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");

}
?>
<?php
$blocks_modules[poll] = array(
	'title' => 'Survey',
	'func_display' => 'blocks_poll_block',
	'text_type' => 'Survey',
	'text_type_long' => 'Survey',
	'text_content' => 'Survey',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => true
    );
function blocks_poll_block($row) {
global $nukeurl,$bburl,$pollsforum,$DB_site,$bbuserinfo,$session,$block_sidetemplate;


   $P_pollinfo=$DB_site->query_first("SELECT *,thread.open FROM poll LEFT JOIN thread ON (thread.pollid = poll.pollid)
  WHERE thread.forumid='$pollsforum' ORDER BY poll.dateline DESC LIMIT 1");
if (!empty($P_pollinfo[question])){
  $P_pollinfo[question]=bbcodeparse($P_pollinfo[question],$pollsforum,1);

  $P_splitoptions=explode("|||", $P_pollinfo[options]);
  $P_splitvotes=explode("|||",$P_pollinfo[votes]);

  $P_pollisclosed=0;
  $P_pollisactive=$P_pollinfo[poll.active];
  $P_pollid=$P_pollinfo[poll.pollid];

  $P_replycount=$P_pollinfo[replycount];
  if ($P_replycount == "0") {
  $P_replies = "";
  }
  elseif ($P_replycount == "1") {
  $P_replies = " (1)";
  }
  else {
  $P_replies = " ($P_replycount)";
  }
 if (!$P_pollinfo[active] or !$P_pollinfo[open] or ($P_pollinfo[dateline]+($P_pollinfo[timeout]*86400)<time() and $P_pollinfo[timeout]!=0)){
    //thread/poll is closed, ie show results no matter what
    $P_pollisclosed=1;
    } else {
    //get userid, check if user already voted
    if ($P_uservote=$DB_site->query_first("SELECT pollvoteid FROM pollvote WHERE userid='$bbuserinfo[userid]' AND pollid=$P_pollinfo[pollid]")) {
      $P_uservoted=1;
    }
  }
  $P_counter=0;
  while ($P_counter++<$P_pollinfo[numberoptions]) {
    $P_pollinfo[numbervotes]+=$P_splitvotes[$P_counter-1];
  }
  $P_counter=0;
  $P_pollbits="";
while ($P_counter++<$P_pollinfo[numberoptions]) {
    $P_option[question] = bbcodeparse($P_splitoptions[$P_counter-1],$pollsforum,1);
    $P_option[votes] = $P_splitvotes[$P_counter-1];  //get the vote count for the option
    $P_option[number] = $P_counter;  //number of the option

   // Now we check if the user has voted or not
 
  if ($P_pollisclosed or $P_uservoted) { // user did vote or poll is closed
      if ($P_option[votes] == 0){
          $P_option[percent]=0;
      } else{
          $P_option[percent] = number_format($P_option[votes]/$P_pollinfo[numbervotes]*100,2);
      }
      $P_option[graphicnumber]=$P_option[number]%6 + 1;
      // $P_option[barnumber] = round($P_option[percent])*2;
      $P_option[barnumber] = round($P_option[percent]);
      $P_option[percent] = round($P_option[percent]);
      if ($P_pollisclosed) {
          $P_pollstatus = "This poll is closed.";
      } elseif ($P_uservoted) {
          $P_pollstatus = "You have already voted on this poll.";
      }

      $P_pollbits .= "<tr><td bgcolor=\"{firstaltcolor}\" align=\"left\"><smallfont>$P_option[question]</smallfont></td>	<td bgcolor=\"{firstaltcolor}\" width=\"20\" align=\"left\"><smallfont>$P_option[votes]</smallfont></td><td bgcolor=\"{firstaltcolor}\" align=\"left\" width=\"30\"><smallfont>$P_option[percent]%</smallfont></td></tr>";
  } else {
     //  $P_pollbits .= "<tr><td bgcolor=\"{secondaltcolor}\" width=\"5%\"><input type=\"radio\" name=\"optionnumber\" value=\"$P_option[number]\"></td><td bgcolor=\"{secondaltcolor}\" colspan=\"3\"><smallfont>$P_option[question]</smallfont></td></tr>";
  
    if ($P_pollinfo['multiple']) {
       $P_pollbits .= "<tr><td bgcolor=\"{secondaltcolor}\" width=\"5%\"><input type=\"checkbox\" name=\"optionnumber[$P_option[number]]\" value=\"yes\"></td><td bgcolor=\"{secondaltcolor}\" colspan=\"3\"><smallfont>$P_option[question]</smallfont></td></tr>";
    }else{
       $P_pollbits .= "<tr><td bgcolor=\"{secondaltcolor}\" width=\"5%\"><input type=\"radio\" name=\"optionnumber\" value=\"$P_option[number]\"></td><td bgcolor=\"{secondaltcolor}\" colspan=\"3\"><smallfont>$P_option[question]</smallfont></td></tr>";
    }
  
  }
}
  if ($P_pollisclosed or $P_uservoted) {
    if($row[templates]) {
			eval("\$P_poll .= \"".gettemplate('P_pollresult')."\";");
    }else{
		 $P_poll = "<table cellpadding=\"{tableouterborderwidth}\" cellspacing=\"0\" border=\"0\" bgcolor=\"{categorybackcolor}\" {tableouterextra} width=\"{contenttablewidth}\" align=\"center\"><tr><td>
		<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\">
		<tr>
		<td colspan=\"3\" bgcolor=\"{tableheadbgcolor}\" align=\"center\"><smallfont color =\"{tableheadtextcolor}\"><b>
		$P_pollinfo[question]</b></smallfont><br>
		<smallfont color =\"{tableheadtextcolor}\">$P_pollstatus</smallfont></td>
		</tr>
		$P_pollbits
		<tr>
		<td bgcolor=\"{tableheadbgcolor}\" align=\"right\" ><smallfont color =\"{tableheadtextcolor}\"><b>Votes:</b></smallfont></td>
		<td bgcolor=\"{tableheadbgcolor}\" align=\"center\"><smallfont color =\"{tableheadtextcolor}\" ><b>$P_pollinfo[numbervotes]</b></smallfont></td>
		<td bgcolor=\"{tableheadbgcolor}\" align=\"center\"><smallfont color =\"{tableheadtextcolor}\"><b>100%</b></smallfont></td>
		</tr>
		</table>
		<tr><td>
		<ul>
		<li><a href=\"$bburl/showthread.php?threadid=$P_pollinfo[threadid]\"><font size = \"1\" color=\"{categoryfontcolor}\" >Comments</font></a><font size = \"1\" color=\"{categoryfontcolor}\" ><b>$P_replies</b></font> </li>
		<li><a href=\"$nukeurl/vbppolls.php\"><font size = \"1\" color=\"{categoryfontcolor}\" >Other Polls</font></a></li></ul></td></tr>
	    </table>";
	}
  } else {
	if($row[templates]) {
			eval("\$P_poll .= \"".gettemplate('P_polloption')."\";");
    }else{
		$P_poll .= "<form action=\"$bburl/poll.php\" method=\"get\">
		<input type=\"hidden\" name=\"s\" value=\"$session[dbsessionhash]\">
		<input type=\"hidden\" name=\"action\" value=\"pollvote\">
		<input type=\"hidden\" name=\"pollid\" value=\"$P_pollinfo[pollid]\">
		<table cellpadding=\"{tableouterborderwidth}\" cellspacing=\"0\" border=\"0\" bgcolor=\"{tablebordercolor}\" {tableouterextra} width=\"{contenttablewidth}\" align=\"center\"><tr><td>
		<table cellpadding=\"4\" cellspacing=\"{tableinnerborderwidth}\" border=\"0\" {tableinnerextra} width=\"100%\">
		<tr>
		<td bgcolor=\"{tableheadbgcolor}\" align=\"center\" colspan=\"4\"><smallfont color=\"{tableheadtextcolor}\"><b>$P_pollinfo[question]</b></smallfont></td>
		</tr>
		$P_pollbits
		</table>
		</td></tr></table>
		<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" width=\"100%\" {tableinvisibleextra} align=\"center\">
		<tr>
		<td style=\"font-size: 8pt\"><smallfont><input type=\"submit\" class=\"bginput\" value=\"Vote!\" style=\"font-size: 8pt\">
	    <a href=\"$bburl/poll.php?s=$session[sessionhash]&action=showresults&pollid=$P_pollinfo[pollid]\">View Results</a>
		</smallfont></td></tr>
		</table>
		</form>";
	}
  }
} else {
  $P_poll="No Polls Available";
}
 $block_title = $row[title];
 $block_content = $P_poll;
 eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");

}
?>
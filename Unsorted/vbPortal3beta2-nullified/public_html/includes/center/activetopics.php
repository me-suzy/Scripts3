<?php
//8.1
$centerblocks_modules[activetopics] = array(
	'title' => 'activetopics',
	'func_display' => 'centerblocks_activetopics_block',
	'text_type' => 'activetopics',
	'text_type_long' => 'activetopics',
	'text_content' => 'activetopics',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => true
    );

function centerblocks_activetopics_block($row) {
include("config.php");
global $DB_site,$dateformat,$timeformat;

	if ($excatforums == "" or $excatforums <= "0") {
		$whereatsql = "WHERE visible";
	} else {
             $whereatsql = "WHERE thread.open!='10'";
		$excatfid = explode(",",$excatforums); $ia = 0; $vbp_at = count($excatfid);
		while ($ia < $vbp_at) {
		$whereatsql .= "AND forumid!='$excatfid[$ia]'";
       	++$ia;
       	}
         $whereatsql .="AND visible";
      }
    
    $result = $DB_site->query("select threadid,forumid,title,postusername,lastposter from thread $whereatsql order by lastpost desc limit $num_active");
    
    
    while ($latest_array = $DB_site->fetch_array($result)) {
	    $result_thread_text = $DB_site->query("select postid,dateline,pagetext from post where threadid='$latest_array[threadid]' order by dateline desc limit 1");
	    $result_thread_array = $DB_site->fetch_array($result_thread_text);
	    $title2 = substr($latest_array["title"],0,$ttitle1en);
	    if (strlen($title2) > $ttitle1en) {
	       $title2 =substr ( $title2, 0, strrpos($title2," "));
	    }
	    $startedby = $latest_array["postusername"];
	    $lastposter = $latest_array["lastposter"];
	    $mpostid= $result_thread_array["postid"];
	    $atpostdate=vbdate($dateformat,$result_thread_array[dateline]);
	    $atposttime=vbdate($timeformat,$result_thread_array[dateline]);
	    if ($showtopicdesc){
		$atpagetext = $result_thread_array[pagetext];
		if (strlen($atpagetext) > $atpagetext) {
			   $atpagetext = substr($atpagetext,0,$num_topicchars);
			   $atpagetext .= "...";
			}
		 $atpagetext=bbcodeparse($atpagetext,$forumid,1);
	    }else{
			$atpagetext="";
	    }

	    $gothreadid = $latest_array["threadid"];
	    if (($counter++ % 2) != 0) {
		$vbp_atbc="{firstaltcolor}";
	    } else {
		$vbp_atbc="{secondaltcolor}";
	    }

		
	    if($row[templates]) {
		eval("\$P_activetopic_centerboxbit .= \"".gettemplate('P_activetopic_centerboxbit')."\";");
	    }else{
		$P_activetopic_centerboxbit .="
		<tr>
		<td bgcolor='$vbp_atbc' align='left'>
		<img src='{imagesfolder}/smicon3.gif'>
	        <!-- <a href='$bburl/showthread.php?goto=newpost&threadid=$gothreadid'><img src='{imagesfolder}/firstnew.gif' border='0'></a> -->
		<smallfont><b>
		<!-- <a href='$bburl/showthread.php?threadid=$gothreadid'>  $title1</a>| -->
		<a href='$bburl/showthread.php?goto=newpost&threadid=$gothreadid'>  $title2</a> </b></smallfont>
		</td>
		<td bgcolor='$vbp_atbc' align='left'> 
		<smallfont><a href='$bburl/member.php?action=getinfo&username=$startedby'>$startedby</a></smallfont>
		</td>
		<td bgcolor='$vbp_atbc' align='left'> 
		<smallfont><a href='$bburl/member.php?action=getinfo&username=$lastposter'>$lastposter</a></smallfont>
		</td>
		<td bgcolor='$vbp_atbc' align='left'><smallfont> $atpostdate @ $atposttime</smallfont>
		</td>
		</tr>\n";
	    }
   }
	
        if($row[templates]) {
		eval("dooutput(\"".gettemplate('P_activetopic_centerbox')."\");");
	}else{
		$block_content = "<tr><td bgcolor=\"{firstaltcolor}\" >
		<!-- Set below cell spacing to 1 to show table lines -->
		<table width = \"100%\" border=0 cellpadding=\"0\" cellspacing=\"0\"  bgcolor=\"{categorybackcolor}\"><tr>
		<td><smallfont color=\"{categoryfontcolor}\"><b>Topic</b></smallfont></td>
		<td align=\"center\">
		<p align=\"left\"><smallfont color=\"{categoryfontcolor}\"><b>Started By</b></smallfont></p>
		</td>
		<td align=\"center\">
		<p align=\"left\"><smallfont color=\"{categoryfontcolor}\"><b>Last Post By</b></smallfont></p>
		</td>
		<td align=\"center\">
		<p align=\"left\"><smallfont color=\"{categoryfontcolor}\"><b> Posted on</b></smallfont></p>
		</td>
		</tr>
		<td colspan=\"4\">
		$P_activetopic_centerboxbit
		</table>
		</td>
		</tr>";
		$block_title = $row[title];
		eval("dooutput(\"".gettemplate('P_themecenterbox')."\");");
	}
}

?>
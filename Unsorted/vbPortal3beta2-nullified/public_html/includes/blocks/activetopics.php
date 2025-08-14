<?php
//8.1
$blocks_modules[activetopics] = array(
	'title' => 'Active Topics',
	'func_display' => 'blocks_activetopics_block',
	'text_type' => 'activetopics',
	'text_type_long' => 'Active Topics',
	'text_content' => 'Active Topics',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => true
    );
function blocks_activetopics_block($row) {
  global $bburl,$DB_site,$excatforums,$dateformat,$timeformat;
  global $sdtopicdesc,$sbnum_active,$sbnum_topicchars,$sbftitle1en,$sbttitle1en,$block_sidetemplate;        
  if ($excatforums == "" or $excatforums <= "0") {
	$whereatsql = "WHERE visible";
  } else {
        $whereatsql = "WHERE thread.open!='10'";
	$excatfid = explode(",",$excatforums); $ia = 0; $vbp_at = count($excatfid);
	while ($ia < $vbp_at) {
		$whereatsql .= "AND forumid!='$excatfid[$ia]'";
       	++$ia;
       	}
        $whereatsql .= "AND visible";
  }
$result = $DB_site->query("select threadid,forumid,title,postusername,lastposter from thread $whereatsql order by lastpost desc limit $sbnum_active");
while ($latest_array = $DB_site->fetch_array($result)) {
   $result_thread_text= $DB_site->query("select post.postid,post.iconid, post.dateline,post.pagetext, IFNULL(icon.iconpath, '') from post LEFT OUTER JOIN icon ON icon.iconid=post.iconid where threadid='$latest_array[threadid]' order by dateline desc limit 1");
   $result_thread_array = $DB_site->fetch_array($result_thread_text);
   $sbtitle = substr($latest_array["title"],0,$sbttitle1en);
   if (strlen($sbtitle) > $sbttitle1en) {
	    $sbtitle =substr ( $sbtitle, 0, strrpos($sbtitle," "));
    }
    
    if ($result_thread_array[icon] == '') {
	$aticon = "{imagesfolder}/icons/icon1.gif"; 
    }else{
	$aticon = $result_thread_array["icon"];
	$aticon = $bburl.'/'.$aticon;
    }
    // $startedby = $latest_array["postusername"];
    $lastposter = $latest_array["lastposter"];
    $sbpostid = $result_thread_array["postid"];
	$atpostdate=vbdate($dateformat,$result_thread_array[dateline]);
    // $atposttime=vbdate($timeformat,$result_thread_array[dateline]);
    $gothreadid = $latest_array["threadid"];
     if ($sbtopicdesc){
        $pagetext = $result_thread_array[pagetext];
        if (strlen($pagetext) > $sbnum_topicchars) {
		   $pagetext = substr($pagetext,0,$sbnum_topicchars);
		   $pagetext .= "... <a href='$bburl/showthread.php?threadid=$gothreadid'><b>(more)</b></a>";
		}
         $atpagetext = bbcodeparse($pagetext,$forumid,1);
    }else{
		$atpagetext="";
	}
	if (($counter++ % 2) != 0) {
		$vbp_atbc="{firstaltcolor}";
	} else {
		$vbp_atbc="{secondaltcolor}";
	}

		if($row[templates]) {
			eval("\$block_content .= \"".gettemplate('P_activetopics_sidebox')."\";");
        }else{
		$block_content .="<tr><td bgcolor=\"$vbp_atbc\"><smallfont>
                <img src=\"$aticon\"><b>
		<a href=\"$bburl/showthread.php?postid=$sbpostid\">$sbtitle</a></b><br>	
		<b>Last Post By:</b> $lastposter<br>
		<b>Posted on:</b> $atpostdate<br>
		$pagetext </smallfont></td></tr>\n";
		}
   	}
  $block_title = $row[title];
  eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");
  }
?>
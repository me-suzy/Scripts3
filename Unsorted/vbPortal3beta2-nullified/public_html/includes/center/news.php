<?php
//8.1
$centerblocks_modules[news] = array(
	'title' => 'news',
	'func_display' => 'centerblocks_news_block',
	'text_type' => 'news',
	'text_type_long' => 'news',
	'text_content' => 'news',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => true
    );
function centerblocks_news_block($row) {
include("config.php");
global $hastopics,$postinfo,$tipath,$bburl,$DB_site,$bbuserinfo,$dateformat,$timeformat,$P_graphic,$showvotes,$dtopic,$centerblocks_modules,$num_chars;
$threads=$DB_site->query("SELECT thread.*, IFNULL(icon.iconpath, '') FROM thread LEFT OUTER JOIN icon ON icon.iconid=thread.iconid WHERE forumid=$newsforum ORDER BY sticky DESC,dateline DESC LIMIT $newslimit");
while ($thread=$DB_site->fetch_array($threads)) {
if ($thread[visible]){
    unset($thread[movedprefix]);
    unset($thread[typeprefix]);
    if ($thread[open]==10) {
    // thread has been moved!
      $thread[threadid]=$thread[pollid];
      $thread[replycount]="?";
      $thread[views]="?";
      $thread[icon]="1";
      $thread[movedprefix]=$movedthreadprefix;
	  $thread[pollid]=0;
 }
$postdate=vbdate($dateformat,$thread[dateline]);
$posttime=vbdate($timeformat,$thread[dateline]);
$replycount=$thread[replycount];
$username=$thread[postusername];
$newstitle=$thread[title];
$threadid=$thread["threadid"];

if ($thread["icon"] == '') {
	$newsicon = "{imagesfolder}/icons/icon1.gif"; 
    }else{
	$newsicon = $thread["icon"];
	$newsicon = $bburl.'/'.$newsicon;
}



$showvotes=1;
if ($thread[votenum] >= $showvotes) {
   $voteavg=($thread[votetotal]/$thread[votenum]);
   $rating = intval(round($voteavg));
   $threadstars = $rating . 'stars.gif';
} else {
   $threadstars = 'clear.gif';
}
if ($replycount == "0") {
$replies = "";
}
elseif ($replycount == "1") {
$replies = "<font size ='1'> (1 Comment)</font>";
}
else {
$replies = "<font size ='1'> ($replycount Comments)</font>";
}
if ($hastopics) {
	$posts=$DB_site->query("SELECT post.postid, post.userid, post.pagetext, post.topic, user.username as username, attachment.attachmentid,attachment.filename,attachment.visible AS attachmentvisible,attachment.counter
	FROM post LEFT JOIN user ON (post.userid = user.userid) LEFT JOIN attachment ON attachment.attachmentid=post.attachmentid
	WHERE threadid=$threadid ORDER BY postid LIMIT 1");
}else{
	$posts=$DB_site->query("SELECT post.postid, post.userid, post.pagetext, user.username as username, attachment.attachmentid,attachment.filename,attachment.visible AS attachmentvisible,attachment.counter
	FROM post LEFT JOIN user ON (post.userid = user.userid) LEFT JOIN attachment ON attachment.attachmentid=post.attachmentid
	WHERE threadid=$threadid ORDER BY postid LIMIT 1");
}

$postinfo=$DB_site->fetch_array($posts);
$userid=$postinfo[userid];
$username=$postinfo[username];
$forumid=$newsforum;

$newsarticle=substr($postinfo[pagetext],0,$num_chars); 
if (strlen($newsarticle) >= $num_chars) { 
	$newsarticle=substr($newsarticle,0,strrpos($newsarticle," ")); 
	$newsarticle=$newsarticle."..."; 
} 
$newsarticle=bbcodeparse($newsarticle,$forumid,1);

if ($hastopics) {
if ($postinfo[topic] == 0) {
		$topic=$dtopic;
	 }else{
		$topic=$postinfo[topic];
}
}else{
       $topic="";
}

if ($postinfo[attachmentid]!=0) {
    $postinfo[attachmentextension]=strtolower(getextension($postinfo[filename]));
    if ($postinfo[attachmentextension]=="gif" or $postinfo[attachmentextension]=="jpg" or 			$postinfo[attachmentextension]=="jpeg" or $postinfo[attachmentextension]=="jpe" or $postinfo[attachmentextension]=="png") {
    $P_graphic = "<p><img src=\"$bburl/attachment.php?s=$session[sessionhash]&postid=$postinfo[postid]\" border=\"0\" alt=\"\"></p>\n";
    }
  } else {
    $postinfo[attachment]="";
    if ($hastopics) {
		$thistopic=$DB_site->query("SELECT topicid, topicname, topicimage, topictext FROM nuke_topics where topicid=$topic");
		list($topicid, $topicname, $topicimage, $topictext) = mysql_fetch_row($thistopic);
   		$P_graphic = "<a href=\"$bburl/search.php?s=$session[sessionhash]&action=findtopic&topic=$topic&userid=$userid\"><img src=\"$tipath$topicimage\" border=\"0\" Alt=\"$topictext\" align=\"right\" hspace=\"10\" vspace=\"10\"></a>";
    }else{
        $P_graphic ="";
    }
  }

// The News Bits
if($row[templates]) {
	eval("\$P_newsbits .= \"".gettemplate('P_newsbits')."\";");
}else{
	// The News Bits
$P_newsbits .="<table border='0' cellspacing='0' cellpadding='0' width='100%'>
	<tr>
	<td bgcolor='{tablebordercolor}' width='100%' >
	<table width='100%' border='0' cellspacing='1' cellpadding='3'>
	<tr>
	<td bgcolor='{categorybackcolor}' width='100%'><img src='$newsicon'><font size ='2' color = '{categoryfontcolor}'><b> $newstitle</b></font><smallfont><b> Posted by: [<a href='$bburl/member.php?action=mailform&userid=$userid'>$username</a>] on $postdate @ $posttime</b></smallfont>
	</td>
	</tr>
	<tr>
	<th bgcolor='{pagebgcolor}' width='100%' align='right'>
	<table width='100%' border='0' cellspacing='0' cellpadding='0' bordercolor='{pagebgcolor}'>
	<tr><td bgcolor='{pagebgcolor}' valign='top' align='justify' bordercolor='{pagebgcolor}'>
	<smallfont>$newsarticle</smallfont>
	</td> <th bgcolor='{pagebgcolor}' valign='top' align='center' width='54' height='54' nowrap>
	$P_graphic $postinfo[attachment]
	</th></tr>
    </table><smallfont> <a href='$bburl/showthread.php?threadid=$threadid'><b>Read More</b></a><b> $replies </b></smallfont> 
	<a href='$bburl/printthread.php?threadid=$threadid'><img src='{imagesfolder}/print.gif' border='0'></a> <a href='$bburl/sendtofriend.php?threadid=$threadid'><img src='{imagesfolder}/friend.gif' border='0'></a> <img src='{imagesfolder}/$threadstars' border='0' alt='$thread[votenum] votes - $voteavg average'> 
	</th>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	<br>\n";
// End News Bits
}
}
}

$block_content = $P_newsbits;
$block_title = $row[title];
eval("dooutput(\"".gettemplate('P_themecenterbox')."\");");

}
?>
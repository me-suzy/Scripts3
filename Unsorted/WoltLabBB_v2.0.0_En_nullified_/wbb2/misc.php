<?php
$filename="misc.php";

require("./global.php");
require("./acp/lib/class_parse.php");

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="";
	
if($action=="finduser") {
 if(isset($_POST['send'])) {
  $options="";
  $result=$db->query("SELECT username FROM bb".$n."_users WHERE username LIKE '%".addslashes(htmlspecialchars(trim($_POST['username'])))."%' AND activation=1");	
  while($row=$db->fetch_array($result)) $options.=makeoption($row['username'],$row['username']);	
 }
 if($options) eval("\$finduser_result = \"".$tpl->get("finduser_result")."\";");	
 else $finduser_result="";
 
 eval("\$tpl->output(\"".$tpl->get("finduser")."\");");	
}

if($action=="moresmilies") {
 $rightorleft = "left";
 if($showsmiliesrandom==1) $result = $db->query("SELECT smiliepath, smilietitle, smiliecode FROM bb".$n."_smilies ORDER BY RAND()");
 else $result = $db->query("SELECT smiliepath, smilietitle, smiliecode FROM bb".$n."_smilies ORDER BY smilieorder ASC");
 $j=0;
 while($row = $db->fetch_array($result)) {
  if ($rightorleft == "left") {
   if (($j++ % 2) != 0) $tdinfo="bgcolor=\"{tablecolorb}\" id=\"tableb\"";
   else $tdinfo="bgcolor=\"{tablecolora}\" id=\"tablea\"";
   eval ("\$popup_smiliesbits .= \"<tr \$tdinfo>".$tpl->get("popup_smiliesbits")."\";");
   $rightorleft = "right";
  }
  else {
   eval ("\$popup_smiliesbits .= \"".$tpl->get("popup_smiliesbits")."</tr>\";");
   $rightorleft = "left";
  }
 }

 eval("\$tpl->output(\"".$tpl->get("popup_smilies")."\");");	
}

if($action=="whoposted") {
 if(!isset($threadid)) eval("error(\"".$tpl->get("error_falselink")."\");");
 
 $posts = $db->query("SELECT
  COUNT(p.postid) AS posts, p.userid, u.username
  FROM bb".$n."_posts p
  LEFT JOIN bb".$n."_users u USING (userid)
  WHERE threadid='$threadid'
  GROUP BY p.userid
  ORDER BY posts DESC, u.username ASC");
 $posters="";
 while ($post = $db->fetch_array($posts)) {
  if (($counter++ % 2) != 0) $rowinfo = "bgcolor=\"{tablecolora}\" id = \"tablea\"";
  else $rowinfo = "bgcolor=\"{tablecolorb}\" id = \"tableb\"";
  $totalposts += $post['posts'];
  if($post['userid']) {
   $authorname = makehreftag("profile.php?userid=$post[userid]&sid=$session[hash]","<b>$post[username]</b>","_blank");
   $post['posts'] = makehreftag("thread.php?threadid=$threadid&sid=$session[hash]&hilightuser=$post[userid]","<b>$post[posts]</b>","_blank");
  }
  else eval ("\$authorname = \"".$tpl->get("anonymous_plural")."\";");
  eval("\$posters .= \"".$tpl->get("whopostedbit")."\";");
 }
	
 $totalposts = number_format($totalposts);
 eval("\$tpl->output(\"".$tpl->get("whoposted")."\");");	
}

if($action=="viewip") {
 if(!isset($postid)) eval("error(\"".$tpl->get("error_falselink")."\");");
 if($wbbuserdata['canuseacp']!=1) access_error();

 $navbar=getNavbar($board['parentlist']);
 eval ("\$navbar .= \"".$tpl->get("navbar_board")."\";");
 $post['host']=@gethostbyaddr($post['ipaddress']);

 if($post['userid']) {
  $moreips="";
  $result=$db->query("SELECT DISTINCT ipaddress FROM bb".$n."_posts WHERE userid='$post[userid]' AND ipaddress<>'$post[ipaddress]' ORDER BY posttime DESC LIMIT 10");
  while($row=$db->fetch_array($result)) $moreips.=$row['ipaddress']."<br>";
 
  eval ("\$userip = \"".$tpl->get("viewip_userip")."\";");
 }
 
 if(strlen($thread['topic'])>60) $thread['topic']=parse::textwrap($thread['topic'],60);

 eval("\$tpl->output(\"".$tpl->get("viewip")."\");");	
}

if($action=="faq") eval("\$tpl->output(\"".$tpl->get("faq")."\");");
if($action=="faq2") eval("\$tpl->output(\"".$tpl->get("faq2")."\");");
if($action=="faq3") eval("\$tpl->output(\"".$tpl->get("faq3")."\");");

if($action=="faq1") {
 $count=0;
 $rankbit="";
 $result=$db->query("SELECT r.*, g.title FROM bb".$n."_ranks r LEFT JOIN bb".$n."_groups g USING(groupid) ORDER BY r.groupid DESC, r.needposts ASC");
 while($row=$db->fetch_array($result)) {
  $tdbgcolor=getone($count,"{tablecolorb}","{tablecolora}");
  $tdid=getone($count,"tableb","tablea");
 	
  $row['rankimages']=formatRI($row['rankimages']);
  eval ("\$rankbit .= \"".$tpl->get("faq1_rankbit")."\";");	
  $count++;	
 }	
 eval("\$tpl->output(\"".$tpl->get("faq1")."\");");	
}

if($action=="userrating") {
 if(isset($_REQUEST['userid'])) $userid=intval($_REQUEST['userid']);
 else {
  eval("\$tpl->output(\"".$tpl->get("userrating_error")."\");");
  exit();
 }
 
 if($wbbuserdata['canrateusers']==0 || $userid==$wbbuserdata['userid']) {
  eval("\$tpl->output(\"".$tpl->get("userrating_error")."\");");
  exit();	
 }
 
 if($wbbuserdata['userid']) $result=$db->query_first("SELECT id FROM bb".$n."_votes WHERE id='$userid' AND votemode=3 AND userid='$wbbuserdata[userid]'");
 else $result=$db->query_first("SELECT id bb".$n."_votes WHERE id='$userid' AND votemode=3 AND ipaddress='$REMOTE_ADDR'");
 if($result['id']) {
  eval("\$tpl->output(\"".$tpl->get("userrating_error")."\");");
  exit();	
 }
 
 $user=$db->query_first("SELECT userid, username FROM bb".$n."_users WHERE userid='$userid'");
 if(!$user['userid']) {
  eval("\$tpl->output(\"".$tpl->get("userrating_error")."\");");
  exit();	
 }
 
 if(isset($_POST['send'])) {
  $ratingpoints=intval($_POST['ratingpoints']);
  
  if($ratingpoints>=0 && $ratingpoints<=10) {
   $db->query("UPDATE bb".$n."_users SET ratingcount=ratingcount+1, ratingpoints=ratingpoints+'$ratingpoints' WHERE userid='$userid'");	
   $db->query("INSERT INTO bb".$n."_votes (id,votemode,userid,ipaddress) VALUES ('$userid','3','$wbbuserdata[userid]','$REMOTE_ADDR')");
  }
  eval("\$tpl->output(\"".$tpl->get("window_close")."\");");
  exit();	
 }
 
 eval("\$tpl->output(\"".$tpl->get("userrating_window")."\");");
}

if($action=="showsmilies") {
 if($showsmiliesrandom==1) $result = $db->query("SELECT smiliepath, smilietitle, smiliecode FROM bb".$n."_smilies ORDER BY RAND()");
 else $result = $db->query("SELECT smiliepath, smilietitle, smiliecode FROM bb".$n."_smilies ORDER BY smilieorder ASC");
 
 $smiliebit="";	
 while($row=$db->fetch_array($result)) eval ("\$smiliebit .= \"".$tpl->get("showsmiliesbit")."\";");	
 eval("\$tpl->output(\"".$tpl->get("showsmilies")."\");");
}

if($action=="bbcode"){
 $parse = new parse(0,75,0,1,1,"",0);
 $count=1;
 $faq_bbcode_links_bit="";
 $faq_bbcode_content="";
 $result = $db->query("SELECT bbcodeexample, bbcodeexplanation FROM bb".$n."_bbcodes ORDER BY bbcodeid");
 while($row=$db->fetch_array($result)){
  $name = $row['bbcodeexample'];
  $description = $row['bbcodeexplanation'];
  $parsed = $parse->doparse($name,0,0,1,1);
  eval ("\$faq_bbcode_links_bit .= \"".$tpl->get("faq_bbcode_links")."\";");
  eval ("\$faq_bbcode_content .= \"".$tpl->get("faq_bbcode_content")."\";");
  $count++;
 }
 eval("\$tpl->output(\"".$tpl->get("faq_bbcode")."\");");
}
?>
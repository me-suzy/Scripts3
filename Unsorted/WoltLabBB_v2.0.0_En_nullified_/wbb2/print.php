<?php
$filename="print.php";

require("global.php");
require("acp/lib/class_parse.php");

if(!isset($threadid)) eval("error(\"".$tpl->get("error_falselink")."\");");
if(isset($_GET['page'])) {
 $page=intval($_GET['page']);
 if($page==0) $page=1;
}
else $page=1;

if($wbbuserdata['umaxposts']) $postsperpage=$wbbuserdata['umaxposts'];
elseif($board['postsperpage']) $postsperpage=$board['postsperpage'];
else $postsperpage=$default_postsperpage;
$postorder=$board['postorder'];

$ignore="";
if(trim($wbbuserdata['ignorelist'])!="") $ignore = "AND bb".$n."_posts.userid<>".implode(" AND bb".$n."_posts.userid<>",explode(' ',$wbbuserdata['ignorelist']));
list($postcount) = $db->query_first("SELECT COUNT(*) FROM bb".$n."_posts WHERE threadid = '$threadid' AND visible = 1 $ignore");

$parse = new parse($docensor,75,$board['allowsmilies'],$board['allowbbcode'],$wbbuserdata['showimages'],"",$usecode,0);

$postids="";
$result = $db->query("SELECT postid FROM bb".$n."_posts WHERE threadid = '$threadid' $ignore AND visible = 1 ORDER BY posttime ".ifelse($postorder,"DESC","ASC")." LIMIT ".($postsperpage*($page-1)).",".$postsperpage);
while($row=$db->fetch_array($result)) $postids .= ",".$row[postid];

if($board['allowicons']==1) {
 $icon=", i.iconpath, i.icontitle";
 $iconjoin="LEFT JOIN bb".$n."_icons i ON (p.iconid=i.iconid)";
}
else {
 $icon="";
 $iconjoin="";	
}

$result = $db->query("SELECT
p.*,
u.signature
$icon
FROM bb".$n."_posts p 
LEFT JOIN bb".$n."_users u USING (userid)
$iconjoin
WHERE p.postid IN (0$postids)
ORDER BY p.posttime ".ifelse($postorder,"DESC","ASC"));

while($posts = $db->fetch_array($result)){
 unset($signature);
 if($posts[posticon]) $posticon = makeimgtag($posts['posticon']);
 else $posticon = "&nbsp;";
 if($posts['posttopic']) $posts['posttopic'] = $parse->textwrap($posts['posttopic'],30);

 $posts['message']=$parse->doparse($posts['message'],$posts['allowsmilies']*$board['allowsmilies'],$board['allowhtml'],$board['allowbbcode'],$board['allowimages']);
 
 if($posts['userid'] && $posts['showsignature']==1 && $wbbuserdata['showsignatures']==1 && $posts['signature']) {
  $posts['signature']=$parse->doparse($posts['signature'],$posts['allowsmilies']*$allowsigsmilies,$allowsightml,$allowsigbbcode,$maxsigimage);
  eval ("\$signature = \"".$tpl->get("thread_signature")."\";");
 }
  
 $postdate=formatdate($wbbuserdata['dateformat'],$posts['posttime']);
 $posttime=formatdate($wbbuserdata['timeformat'],$posts['posttime']);
 
 eval ("\$print_postbit .= \"".$tpl->get("print_postbit")."\";");
}
$db->free_result($result);

$lines="";
$boards=getNavbar($board['parentlist'],"print_navbar");
$lines2=$lines."-";
$lines3=$lines2."-";

eval("\$tpl->output(\"".$tpl->get("print")."\");");
?>
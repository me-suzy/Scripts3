<?php
$filename="memberslist.php";

require("./global.php");
if($wbbuserdata['canviewmblist']==0) access_error();

if(isset($_GET['sortby'])) $sortby=$_GET['sortby'];
else $sortby="userposts";
if(isset($_GET['order'])) $order=$_GET['order'];
else $order="DESC";

switch($sortby) {
 case "username": break;
 case "email": break;
 case "homepage": break;
 case "regdate": break;
 case "userposts": break;
 default: $sortby = "userposts"; break;
}

switch($order) {
 case "ASC": break;
 case "DESC": break;
 default: $order = "DESC"; break;
}

$sel_sortby[$sortby]=" selected";
$sel_order[$order]=" selected";

$letteroptions="";
$alpha="#ABCDEFGHIJKLMNOPQRSTUVWXYZ";
if(!isset($_GET['letter']) || ($_GET['letter'] && !strstr($alpha,$_GET['letter']))) $letter="";
else $letter=urldecode($_GET['letter']);
for($i=0;$i<strlen($alpha);$i++) $letteroptions.=makeoption($alpha[$i],$alpha[$i],$letter,1);

if($letter=="#") $memberscount=$db->query_first("SELECT COUNT(*) FROM bb".$n."_users WHERE SUBSTRING(username,1,1) NOT IN ('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z') AND activation=1");
else $memberscount=$db->query_first("SELECT COUNT(*) FROM bb".$n."_users WHERE".ifelse($letter," username LIKE '$letter%' AND")." activation=1");
if(isset($_GET['page'])) {
 $page=intval($_GET['page']);
 if($page==0) $page=1;
}
else $page=1;
$pages = ceil($memberscount[0]/$membersperpage);
if($pages>1) $pagelink=makepagelink("memberslist.php?order=$order&sortby=$sortby&letter=".urlencode($letter)."&sid=$session[hash]",$page,$pages,$showpagelinks-1);

if($letter=="#") $result = $db->query("SELECT userid, username, email, homepage, regdate, userposts, showemail, usercanemail, receivepm FROM bb".$n."_users WHERE SUBSTRING(username,1,1) NOT IN ('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z') AND activation=1 ORDER BY $sortby $order LIMIT ".($membersperpage*($page-1)).",".$membersperpage);
else $result = $db->query("SELECT userid, username, email, homepage, regdate, userposts, showemail, usercanemail, receivepm FROM bb".$n."_users WHERE".ifelse($letter," username LIKE '$letter%' AND")." activation=1 ORDER BY $sortby $order LIMIT ".($membersperpage*($page-1)).",".$membersperpage);

$membersbit="";
while($members = $db->fetch_array($result)) {
 $members['regdate'] = formatdate($wbbuserdata['dateformat'],$members['regdate']);

 if($members['showemail']==1) eval ("\$members_email = \" ".$tpl->get("memberslist_email")."\";");
 elseif($members['usercanemail']==1) eval ("\$members_email = \" ".$tpl->get("memberslist_formmail")."\";");
 else $members_email="&nbsp;";
 if($members['homepage']) eval ("\$members_homepage = \" ".$tpl->get("memberslist_homepage")."\";");
 else $members_homepage="&nbsp;";
 if($members['receivepm'] && $wbbuserdata[canusepms]==1) eval ("\$members_pm = \" ".$tpl->get("memberslist_pm")."\";");
 else $members_pm="&nbsp;";
 if($members['userposts']) eval ("\$members_search = \" ".$tpl->get("memberslist_search")."\";");
 else $members_search="&nbsp;";

 eval ("\$membersbit .= \" ".$tpl->get("memberslist_membersbit")."\";");
}
$db->free_result($result);

eval("\$tpl->output(\"".$tpl->get("memberslist")."\");"); 
?>
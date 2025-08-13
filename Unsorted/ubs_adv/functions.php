<?php
@error_reporting(7);
require "data.inc.php";
require "db_connect.php";
$fctime = 30;

$phpversion_array = phpversion();
$phpversion_nr = $phpversion_array[0].".".$phpversion_array[2].$phpversion_array[4];
if (extension_loaded("zlib") && ($phpversion_nr >= 4.04)) {
	ob_start("ob_gzhandler");
}

$db_connect = new db_connect;
$db_connect->appname="Character battle system";
$db_connect->database=$mysqldb;
$db_connect->server=$mysqlhost;
$db_connect->user=$mysqluser;
$db_connect->password=$mysqlpassword;
$db_connect->connect();

session_name("sid");
session_start();
if(!$sid) $sid = session_id();

if(!$REQUEST_URI) {
 if($PATH_INFO) $REQUEST_URI = $PATH_INFO;
 else $REQUEST_URI = $PHP_SELF;
 if($QUERY_STRING) $REQUEST_URI .= "?" . $QUERY_STRING;
}

if(!$HTTP_SESSION_VARS[ssip]) {
	$ssip = getenv(REMOTE_ADDR);
	session_register("ssip");
}
elseif($HTTP_SESSION_VARS[ssip]!=getenv(REMOTE_ADDR)) {
	session_unset();
	header("Location: ".basename($REQUEST_URI)."");
	exit;
}
if(!$user_id) $user_id = $HTTP_SESSION_VARS[user_id];
if(!$user_password) $user_password = $HTTP_SESSION_VARS[user_password];

if(check_userdata($user_id,$user_password)) {
        $userdata = $db_connect->query_first("SELECT db_users.*, db_groups.* FROM db_users LEFT JOIN db_groups ON (db_groups.id=db_users.groupid) WHERE userid='$user_id'");
        if($userdata[blocked]) $blocked = 1;
        $user_name = $userdata[username];
        $user_group = $userdata[groupid];
        $old_time = $userdata[lastvisit];
        $new_time = $userdata[lastactivity];
        $session_link = $userdata[session_link];
        $hide_signature = $userdata[hide_signature];
        $hide_userpic = $userdata[hide_userpic];
        $prunedays = $userdata[prunedays];
        $umaxposts = $userdata[umaxposts];
        $u_bbcode = $userdata[bbcode];
        if($userdata[style_set]) $styleid = $userdata[style_set];
        if($new_time < (time()-900)) {
         $old_time = $new_time;
         $new_time = time();
         $db_connect->query("UPDATE db_users SET lastvisit = lastactivity, lastactivity = '$new_time' WHERE userid = '$user_id'");
        }
        else {
         $new_time = time();
         $db_connect->query("UPDATE db_users SET lastactivity = '$new_time' WHERE userid = '$user_id'");
        }
}
else {
	$user_id=0;
	$userdata = $db_connect->query_first("SELECT * FROM db_groups WHERE default_group = 1");
	$user_group = $userdata[0];
	eval ("\$user_name = \"".gettemplate("lg_anonymous")."\";");
	$old_time = $HTTP_SESSION_VARS[old_time];
	if(!$old_time) $old_time = time();
	$new_time = time();
	session_register("old_time");
	session_register("new_time");
}

$url_jump = $HTTP_SESSION_VARS[url_ak];
if(!$url_jump) $url_jump = urlencode(basename($REQUEST_URI));
if(!strstr($REQUEST_URI,"action.php") && !strstr($REQUEST_URI,"register.php") && !strstr($REQUEST_URI,"misc.php")) $url_ak = urlencode($REQUEST_URI);
else $url_ak = $HTTP_SESSION_VARS[url_ak];
session_register("url_ak");
session_register("url_jump");

if(!$session_link) $session = "&sid=".$sid;
if(!$session_link) $session2 = "?sid=".$sid;

$result = $db_connect->query("SELECT * FROM db_configuration");
$row = $db_connect->fetch_array($result);
$j = mysql_num_fields($result);
for($i = 0; $i < $j; $i++) {
	$k = mysql_field_name($result,$i);
	$$k = editDBdata($row[$k]);
}
$db_connect->free_result($result);
$badwords = explode("\n", $badwords);
if($umaxposts) $eproseite = $umaxposts;

if($styleid) $style_result = $db_connect->query("SELECT * FROM db_style WHERE styleid = '$styleid'");
else {
	if($boardid) $board_style = $db_connect->query_first("SELECT style_set FROM db_boards WHERE boardid = '$boardid'");
	if($board_style[0]) $style_result = $db_connect->query("SELECT * FROM db_style WHERE styleid = '$board_style[0]'");
	else $style_result = $db_connect->query("SELECT * FROM db_style WHERE default_style = 1");
}

$row = $db_connect->fetch_array($style_result);
$j = mysql_num_fields($style_result);
for($i = 0; $i < $j; $i++) {
	$k = mysql_field_name($style_result,$i);
	$$k = editDBdata($row[$k]);
}
$db_connect->free_result($style_result);

// ####################### user blocked ###############################
if(($blocked == 1 || !$userdata[canviewboard]) && !strstr($REQUEST_URI,"action.php") && !strstr($REQUEST_URI,"misc2.php") && !strstr($REQUEST_URI,"register.php")) {
	require("header.php");
//	require("_board_jump.php");
	if(!$user_id) eval ("\$login = \"".gettemplate("access_error_login")."\";");
	else eval ("\$login = \"".gettemplate("access_error_logout")."\";");
	eval("dooutput(\"".gettemplate("access_error")."\");");
	exit;
} 

useronline($user_id);

// ######################## Boardpasswort ############################
if((strstr($REQUEST_URI,"print.php") || strstr($REQUEST_URI,"board.php") || strstr($REQUEST_URI,"thread.php") || strstr($REQUEST_URI,"newthread.php") || strstr($REQUEST_URI,"reply.php") || strstr($REQUEST_URI,"edit.php")) && $boardid != "home" && $boardid != "pm" && $boardid != "search" && $boardid != "profil") {
	$password = $db_connect->query_first("SELECT boardpassword FROM db_boards WHERE boardid = '$boardid'");
	if($password[0] && md5($password[0]) != $cbpassword[$boardid]) {
		require("header.php");
//		require("_board_jump.php");
		eval("dooutput(\"".gettemplate("boardpw")."\");");
		exit;
	} 
} 

// ######################## Boardaccess ############################
if((strstr($REQUEST_URI,"print.php") || strstr($REQUEST_URI,"board.php") || strstr($REQUEST_URI,"thread.php") || strstr($REQUEST_URI,"newthread.php") || strstr($REQUEST_URI,"reply.php") || strstr($REQUEST_URI,"edit.php")) && $boardid != "home" && $boardid != "pm" && $boardid != "search" && $boardid != "profil" && !check_boardobject($boardid,$user_group,"boardpermission")) {
	require("header.php");
//	require("_board_jump.php");
	if(!$user_id) {
		if($session) $session_post = "<input type=\"hidden\" name=\"sid\" value=\"$sid\">";
		eval ("\$login = \"".gettemplate("access_error_login")."\";");
	}
	else eval ("\$login = \"".gettemplate("access_error_logout")."\";");
	eval("dooutput(\"".gettemplate("access_error")."\");");
	exit;
}

# -------- user funktionen

function getUserid($usernick) {
        global $n,$db_connect;
        $result = $db_connect->query_first("SELECT userid FROM db_users WHERE username='$usernick'");
        return $result[userid];
}
function getUsername($userid) {
        global $n,$db_connect;
        $result = $db_connect->query_first("SELECT username FROM db_users WHERE userid='$userid'");
        return $result[username];
}
function getUserEmail($userid) {
        global $n,$db_connect;
        $result = $db_connect->query_first("SELECT useremail FROM db_users WHERE userid='$userid'");
        return $result[useremail];
}
function check_userdata($userid,$password) {
	global $n, $db_connect;
        $result = $db_connect->query_first("SELECT COUNT(userid) FROM db_users WHERE userid='$userid' AND userpassword = '$password'");
	return $result[0];        
}
function checkUser($username,$password) {
        global $n,$db_connect;
        $result = $db_connect->query_first("SELECT userpassword FROM db_users WHERE username='$username' && activation='1'");
        if(!$result[userpassword]) return 0;
        elseif($result[userpassword]==$password) return 2;
        else return 1;
}
function getUserPW($userid) {
        global $n,$db_connect;
        $result = $db_connect->query_first("SELECT userpassword FROM db_users WHERE userid='$userid'");
        return $result[userpassword];
}
function getUserrang($posts,$groupid) {
        global $n,$db_connect;
        $rank = $db_connect->query_first("SELECT rank FROM db_ranks WHERE groupid = $groupid AND posts<='$posts' ORDER by posts DESC");
        return $rank[rank];
}

# -------- beitrags erstellung

function check_posts($text) {
 global $image, $image_ext, $maximage;
 $image_ext = explode("\r\n",$image_ext);
 $count=0;
 do {	
  preg_match("/\[img]([^\"]*)\[\/img\]/siU",$text,$exp);
  if(!$exp[0]) break;
  $text = str_replace($exp[0],"",$text);
  $extension = strtolower(substr($exp[1], strrpos($exp[1],".")+1));
  if(!in_array($extension, $image_ext)) {
   return 1;
   break;
  }
  $count++;
 } while($exp[0]!="" && $count<=$maximage);
 if($count>$maximage) return 1;	
}

function check_signature($text) {
 global $sigimage, $sigimage_ext, $sigmaximage;
 $image_ext = explode("\r\n",$sigimage_ext);
 $count=0;
 do {	
  preg_match("/\[img]([^\"]*)\[\/img\]/siU",$text,$exp);
  if(!$exp[0]) break;
  $text = str_replace($exp[0],"",$text);
  $extension = strtolower(substr($exp[1], strrpos($exp[1],".")+1));
  if(!in_array($extension, $image_ext)) {
   return 1;
   break;
  }
  $count++;
 } while($exp[0]!="" && $count<=$sigmaximage);
 if($count>$sigmaximage) return 1;	
}

function editPostdata($data) {
 $data = str_replace("'","&acute;", $data);
 $data = str_replace("\"","&quot;", $data);
 return $data;
}
function parseURL($out) {
 $urlsearch[]="/([^]_a-z0-9-=\"'\/])((https?|ftp):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\};<>]*)/si";
 $urlsearch[]="/^((https?|ftp):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\};<>]*)/si";
 $urlreplace[]="\\1[URL]\\2\\4[/URL]";
 $urlreplace[]="[URL]\\1\\3[/URL]";
 $emailsearch[]="/([\s])([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si";
 $emailsearch[]="/^([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si";
 $emailreplace[]="\\1[EMAIL]\\2[/EMAIL]";
 $emailreplace[]="[EMAIL]\\0[/EMAIL]";
 $out = preg_replace($urlsearch, $urlreplace, $out);
 if (strpos($out, "@")) $out = preg_replace($emailsearch, $emailreplace, $out);
 return $out;
}

# -------- beitrags anzeige

function editDBdata($data) {
 $data = str_replace("&acute;","'", $data);
 $data = str_replace("&quot;","\"", $data); 
 return $data;
}
function censor($out) {
 global $cover,$badwords;
 reset($badwords);
 if(count($badwords)) {
  while (list($key, $val) = each($badwords)) {
   $val = trim($val);
   if(!$val) continue;
   if(preg_match("/\{(.+)\}/si", $val, $exp)) {
    $val = $exp[1];
    $position = strpos($val, "=");
    if($position===false) {
     $searcharray[] = "/([\s]{1})$val([\s]{1})/si"; 	                		
     $replacearray[] = "\\1".str_repeat($cover, strlen($val))."\\2";
     $searcharray[] = "/^$val([\s]{1})/si"; 	                		
     $replacearray[] = str_repeat($cover, strlen($val))."\\1";
     $searcharray[] = "/([\s]{1})$val$/si"; 	                		
     $replacearray[] = "\\1".str_repeat($cover, strlen($val));
    }
    else {
     $pcover = substr($val, $position+1);
     $val = substr($val, 0, $position);
     $searcharray[] = "/([\s]{1})$val([\s]{1})/si"; 	                		
     $replacearray[] = "\\1".$pcover."\\2";
     $searcharray[] = "/^$val([\s]{1})/si"; 	                		
     $replacearray[] = $pcover."\\1";
     $searcharray[] = "/([\s]{1})$val$/si"; 	                		
     $replacearray[] = "\\1".$pcover;
    }
   }
   else {
    $position = strpos($val, "=");
    if($position===false) {
     $out = eregi_replace("$val","".str_repeat($cover, strlen($val))."", $out);	                		
     $searcharray[] = "/$val/si"; 	                		
     $replacearray[] = str_repeat($cover, strlen($val));
    }
    else {
     $pcover = substr($val, $position+1);
     $val = substr($val, 0, $position);
     $searcharray[] = "/$val/si"; 	                		
     $replacearray[] = $pcover;
    }
   }
  }
 }
 return ifelse(count($searcharray) && count($replacearray),preg_replace($searcharray, $replacearray, $out),$out);
}
function smilies($out) {
	global $smiliecache;
	if(!count($smiliecache)) $smiliecache = getsmilies();
	for($i = 0; $i < count($smiliecache); $i++) $out=str_replace ($smiliecache[$i]['text'], "<img src=".$smiliecache[$i]['path']." border=0>", $out);
        return $out;
}

function getsmilies() {
	global $db_connect, $n;
	$result = $db_connect->query("SELECT smiliespath as path, smiliestext as text FROM db_smilies");
	$count = 0;
	while($row = $db_connect->fetch_array($result)) {
		$smiliecache[$count] = $row;
		$count++;
	}
	return $smiliecache;
}
function editPost($out,$disable_smilies=0) {
        global $bbcode,$html,$smilies,$badwords;
        $out = editDBdata($out);
        if(!$html)  { 
    		$out = str_replace("&lt;","&amp;lt;",$out);
    		$out = str_replace("&gt;","&amp;gt;",$out);
    		$out = str_replace("<","&lt;",$out);
    		$out = str_replace(">","&gt;",$out);
  	}
        $out = nl2br($out);
        if($smilies && !$disable_smilies) $out = smilies($out);
        if($bbcode) $out = prepare_code($out);
        $out = censor($out);
        $out = nt_wordwrap($out);
        return $out;
}
function editSignatur($out,$disable_smilies) {
        global $sigbbcode,$sightml,$sigsmilies,$badwords;
        $out = editDBdata($out);
        if(!$sightml)  { 
    		$out = str_replace("&lt;","&amp;lt;",$out);
    		$out = str_replace("&gt;","&amp;gt;",$out);
    		$out = str_replace("<","&lt;",$out);
    		$out = str_replace(">","&gt;",$out);
  	}
        $out = nl2br($out);
        if($sigsmilies && !$disable_smilies) $out = smilies($out);
        if($sigbbcode) $out = prepare_code($out);
        $out = censor($out);
        $out = nt_wordwrap($out);
        return $out;
}

function ifelse ($expression,$returntrue,$returnfalse) {
	if (!$expression) return $returnfalse;
	else return $returntrue;
}

function formatcodetag($code) {
	return "<blockquote><pre><font size=1>code:</font><hr>".str_replace("<br>","",str_replace("\\\"","\"",$code))."<hr></pre></blockquote>";
}

function formaturl($url, $title="", $maxwidth=60, $width1=40, $width2=-15) {
 if(!trim($title)) $title=$url;
 if(!preg_match("/[a-z]:\/\//si", $url)) $url = "http://$url";
 if(strlen($title)>$maxwidth) $title = substr($title,0,$width1)."...".substr($title,$width2);
 return "<a href=\"$url\" target=\"_blank\">".str_replace("\\\"", "\"", $title)."</a>";
}

function formatlist($list, $listtype="") {
 $listtype = ifelse(!trim($listtype), "",  " type=\"$listtype\"");
 $list = str_replace("\\\"","\"",$list);
 if ($listtype) return "<ol$listtype>".str_replace("[*]","<li>", $list)."</ol>";
 else return "<ul>".str_replace("[*]","<li>", $list)."</ul>";
}

function phphighlite($code) {
 
 $code = str_replace("&gt;", ">", $code);
 $code = str_replace("&lt;", "<", $code);
 $code = str_replace("&amp;", "&", $code);
 $code = str_replace('$', '\$', $code);
 $code = str_replace('\n', '\\\\n', $code);
 $code = str_replace('\r', '\\\\r', $code);
 $code = str_replace('\t', '\\\\t', $code);
 $code = str_replace("<br>", "\r\n", $code);
 $code = str_replace("<br />", "\r\n", $code);
 
 $code = stripslashes($code);

 ob_start();
 $oldlevel=error_reporting(0);
 highlight_string($code);
 error_reporting($oldlevel);
 $buffer = ob_get_contents();
 ob_end_clean();
 //$buffer = str_replace("&quot;", "\"", $buffer);
 return "<blockquote><pre><font size=1>php:</font><hr>$buffer<hr></pre></blockquote>";
}


function prepare_quote($out) {
        global $zensur;
        $out = editDBdata($out);
        if($zensur == 1) $out = censor($out);
        return $out;
}
function prepare_topic($out) { 
	return htmlspecialchars(nt_wordwrap(editDBdata($out),40)); 
}

# -------- sonstige

function getMod($id) {
        global $boardid,$styleid,$session,$db_connect,$n;
        $result = $db_connect->query("SELECT objectid FROM db_object2board WHERE boardid = '$id' AND mod = 1"); 
        while($row = $db_connect->fetch_array($result)) {
        	if($mods) $mods .= ", ";
        	$mods .= "<a href=\"members.php?mode=profile&userid=".$row[objectid]."&boardid=$boardid&styleid=$styleid$session\">".getUsername($row[objectid])."</a>";
        }
        return $mods;
}
function getLastAuthor($threadid) {
        global $boardid,$n,$db_connect,$session;
        $result = $db_connect->query_first("SELECT userid FROM db_posts WHERE threadparentid='$threadid' ORDER by posttime DESC LIMIT 1");
        return "<a href=\"members.php?mode=profile&userid=$result[userid]&boardid=$boardid$session\">".getUsername($result[userid])."</a>";
}
function getThreadflag($threadid) {
        global $n,$db_connect;
        $result = $db_connect->query_first("SELECT flags FROM db_threads WHERE threadid='$threadid'");
        return $result[flags];
}
function getBoardname($boardid) {
        global $n,$db_connect;
        $result = $db_connect->query_first("SELECT boardname FROM db_boards WHERE boardid='$boardid'");
        return prepare_topic($result[boardname]);
}
function getThreadname($threadid) {
        global $n,$db_connect;
        $result = $db_connect->query_first("SELECT threadname FROM db_threads WHERE threadid='$threadid'");
        return prepare_topic($result[threadname]);
}
function getLastPost($id,$nr) {
        global $eproseite, $n, $db_connect, $session, $longdateformat, $postorder;
        if($nr==1) {
                $result = $db_connect->query_first("SELECT threadid,replies FROM db_threads WHERE boardparentid='$id' ORDER by timelastreply DESC LIMIT 1");
                $threadid = $result[threadid];
               	if($postorder) return "thread.php?threadid=".$threadid."&boardid=".$id."$session&page=1#1";
                else {
                	$posts = $result[replies]+1;
			$pages=(int)($posts/$eproseite);
                	if(($posts/$eproseite)-$pages>0) $pages++;
	                return "thread.php?threadid=".$threadid."&boardid=".$id."$session&page=".$pages."#".$posts;
        	}
        }
        if($nr==2) {
                $result = $db_connect->query_first("SELECT boardparentid,replies FROM db_threads WHERE threadid='$id' LIMIT 1");
                $boardid = $result[boardparentid];
                if($postorder) return "thread.php?threadid=".$id."&boardid=".$boardid."$session&page=1#1";
                else {
                	$posts = $result[replies]+1;
			$pages=(int)($posts/$eproseite);
                	if(($posts/$eproseite)-$pages>0) $pages++;
			return "thread.php?threadid=".$id."&boardid=".$boardid."$session&page=".$pages."#".$posts;
		}
        }
        if($nr==4) {
                $result = $db_connect->query_first("SELECT threadparentid, boardparentid FROM db_posts WHERE userid='$id' ORDER by posttime DESC LIMIT 1");
               	$threadid = $result[threadparentid];
                if($postorder) return "thread.php?threadid=".$threadid."&boardid=".$result[boardparentid]."$session&page=1#1";
        	else {
                	$result = $db_connect->query_first("SELECT boardparentid,replies FROM db_threads WHERE threadid='$threadid'");
                	$posts = $result[replies]+1;
                	$pages=(int)($posts/$eproseite);
                	if(($posts/$eproseite)-$pages>0) $pages++;
                	return "thread.php?threadid=".$threadid."&boardid=".$result[boardparentid]."&styleid=$styleid$session&page=".$pages."#".$posts;
        	}
        }
        if($nr==5) {
                $result = $db_connect->query_first("SELECT threadparentid, boardparentid FROM db_posts WHERE userid='$id' ORDER by posttime DESC LIMIT 1");
               	$threadid = $result[threadparentid];
                if($postorder) return "thread.php?threadid=".$threadid."&boardid=".$result[boardparentid]."$session&page=1#1";
        	else {
                	$result = $db_connect->query_first("SELECT boardparentid,replies FROM db_threads WHERE threadid='$threadid'");
                	$posts = $result[replies]+1;
                	$pages=(int)($posts/$eproseite);
                	if(($posts/$eproseite)-$pages>0) $pages++;
                	return "thread.php?threadid=".$threadid."&boardid=".$result[boardparentid]."&styleid=$styleid$session&page=".$pages."#".$posts;
        	}
        }
}
function firstnewPost($threadid,$time) {
        global $eproseite,$n,$db_connect,$styleid,$session, $postorder;
        $sthreadname = "sthread_".$threadid;
	global $$sthreadname;
	if($$sthreadname > $time) $time = $$sthreadname+1;
        
        $thread = $db_connect->query_first("SELECT boardparentid, replies FROM db_threads WHERE threadid='$threadid' ORDER by timelastreply DESC");
        $posts = $thread[replies]+1;

        $result = $db_connect->query("SELECT posttime FROM db_posts WHERE threadparentid='$threadid' ORDER by posttime ".ifelse($postorder,"DESC","ASC"));
        $i=1;
        while($row = $db_connect->fetch_array($result)) {
                if($time<=$row[posttime]) break;
                $i++;
        }
        $db_connect->free_result($result);
        $j=(int)($i/$eproseite);
        if(($i/$eproseite)-$j>0) $j++;

        return "thread.php?threadid=".$threadid."&boardid=".$thread[boardparentid]."&styleid=$styleid$session&page=".$j."#".$i;
}
function checkuseronline($userid) {
        global $n,$db_connect;
        $user = $db_connect->query_first("SELECT COUNT(userid) as anzahl FROM db_users WHERE userid='$userid' AND invisible='0'");
        if($user[anzahl]) $anzahl = $db_connect->query_first("SELECT COUNT(zeit)as anzahl FROM db_useronline WHERE userid='$userid'");
        return $anzahl[anzahl];
}
function delPost($postid,$threadid,$boardid) {
        global $n,$db_connect;
        $threadinfo = $db_connect->query_first("SELECT replies FROM db_threads WHERE threadid = '$threadid'");
        if(!$threadinfo[replies]) {
                $author = $db_connect->query_first("SELECT userid FROM db_posts WHERE postid = '$postid'");
                delUserposts($author[0]);
               	$db_connect->query("DELETE FROM db_threads WHERE threadid='$threadid'");
                $db_connect->query("DELETE FROM db_posts WHERE postid='$postid'");
               	
               	$pinfo = $db_connect->query_first("SELECT postid, posttime, userid FROM db_posts WHERE boardparentid = '$boardid' ORDER BY posttime DESC LIMIT 1");
                $db_connect->query("UPDATE db_boards SET threads=threads-1, posts=posts-1, lastposttime = '$pinfo[posttime]', lastpostid = '$pinfo[postid]' WHERE boardid = '$boardid'");
                
                $db_connect->query("DELETE FROM db_notify WHERE threadid='$threadid'");
                $db_connect->query("DELETE FROM db_poll WHERE threadid='$threadid'");
                $db_connect->query("DELETE FROM db_vote WHERE threadid='$threadid'");
                $db_connect->query("DELETE FROM db_object2user WHERE objectid='$threadid' AND favthreads = 1");
                
                return 2;
        }
        else {
                $author = $db_connect->query_first("SELECT userid FROM db_posts WHERE postid = '$postid'");
                delUserposts($author[0]);
                $db_connect->query("DELETE FROM db_posts WHERE postid='$postid'");
                $tinfo=$db_connect->query_first("SELECT userid, posttime FROM db_posts WHERE threadparentid='$threadid' ORDER BY posttime DESC");
                $db_connect->query("UPDATE db_threads SET replies=replies-1, timelastreply='$tinfo[posttime]', lastposterid='$tinfo[userid]' WHERE threadid = '$threadid'");
                
                $pinfo = $db_connect->query_first("SELECT postid, posttime FROM db_posts WHERE boardparentid = '$boardid' ORDER BY posttime DESC LIMIT 1");
                $db_connect->query("UPDATE db_boards SET posts=posts-1, lastposttime = '$pinfo[posttime]', lastpostid = '$pinfo[postid]' WHERE boardid = '$boardid'");
                
                return 1;
        }
}
function delUserposts($userid) {
        global $n,$db_connect;
        $db_connect->query("UPDATE db_users SET userposts=userposts-1 WHERE userid='$userid'");
}
function formatdate($time,$format,$replacetoday=0) {
	global $db_connect, $n, $timetype, $timeoffset, $today;
	$time = $time+(3600*$timeoffset);
	if(date("dmY", time()+(3600*$timeoffset))==date("dmY", $time) && $replacetoday) {
		$position = strpos($today, "=");
                if($position!==false) {	                		
                	$pcover = substr($today, $position+1);
                	$val = substr($today, 0, $position);
                	$format = str_replace($val,$pcover, $format);
                }
	}
	$out = str_replace("DD",date("d", $time), $format);
	$out = str_replace("MM",date("m", $time), $out);
	$out = str_replace("YYYY",date("Y", $time), $out);
	$out = str_replace("YY",date("y", $time), $out);
	$out = str_replace("MN",get_month_name(date("n", $time)), $out);
	if($timetype) { #12 Stunden
		$out = str_replace("II","II ".date("A", $time), $out);	
		$out = str_replace("HH",date("h", $time), $out);
	}
	else $out = str_replace("HH",date("H", $time), $out);
	$out = str_replace("II",date("i", $time), $out);
	return $out;
}
function gettemplate($template,$endung="htm") {
        global $templatefolder;
        if(!$templatefolder) $templatefolder = "templates";
        return str_replace("\"","\\\"",implode("",file($templatefolder."/".$template.".".$endung)));
}
function dooutput($template) {
        global $bgcolor, $tablebg, $tableb, $tablec, $tabled, $tablea, $font, $fontcolor, $fontcolorsec, $fontcolorthi, $fontcolorfour, $bgfixed, $bgimage;
        
        $template = str_replace("{pagebgcolor}","$bgcolor",$template);
        $template = str_replace("{tablebordercolor}","$tablebg",$template);
        $template = str_replace("{tablea}","$tablea",$template);
        $template = str_replace("{tableb}","$tableb",$template);
        $template = str_replace("{tablec}","$tablec",$template);
        $template = str_replace("{tabled}","$tabled",$template);
        $template = str_replace("{font}","$font",$template);
        $template = str_replace("{fontcolorfirst}","$fontcolor",$template);
        $template = str_replace("{fontcolorsecond}","$fontcolorsec",$template);
        $template = str_replace("{fontcolorthird}","$fontcolorthi",$template);
        $template = str_replace("{fontcolorfourth}","$fontcolorfour",$template);

        if($bgimage) $hgpicture = " background=\"$bgimage\"";
        $template = str_replace("{hgpicture}","$hgpicture",$template);
        if($bgfixed) $template = str_replace("{bgproperties}"," bgproperties=fixed",$template);
        else $template = str_replace("{bgproperties}","",$template);
        echo $template;
}

// ##########################  Neuen Post einfügen * ########################################################
function newPost($boardid,$threadid,$userid,$subject,$message,$posticon,$parseurl,$email,$disablesmilies,$signature,$close)
{
        global $n,$db_connect;
        $thread_info = $db_connect->query_first("SELECT boardparentid,flags FROM db_threads WHERE threadid='$threadid'");
        if($thread_info[flags]==1) return 2;
        else {
             	        $time = time();
                        $subject = editPostdata($subject);
                        $message = editPostdata($message);
                        if($parseurl) $message = parseURL($message);

                        if($disablesmilies!=1) $disablesmilies=0;
                        if($signature!=1) $signature=0;
                        
                        $db_connect->query("UPDATE db_users SET userposts=userposts+1 WHERE userid='$userid'");
                        $db_connect->query("UPDATE db_threads SET replies=replies+1, lastposterid='$userid', timelastreply='$time' WHERE threadid='$threadid'");
                        $ip = getenv(REMOTE_ADDR);
                        $db_connect->query("INSERT INTO db_posts (boardparentid,threadparentid,userid,posttime,posttopic,message,posticon,disable_smilies,signature,ip) VALUES ('$boardid','$threadid','$userid','$time','$subject','$message','$posticon','$disablesmilies','$signature','$ip')");
                        
                        $postid = $db_connect->insert_id();
			$db_connect->query("UPDATE db_boards SET posts=posts+1, lastposttime = '$time', lastpostid = '$postid' WHERE boardid = '$boardid'");	
			                        
                        sendEmail($userid,getLastPost($userid,5),$threadid,$boardid);
                        if($email && $userid) {
                        	$check = $db_connect->query_first("SELECT COUNT(*) FROM db_notify WHERE threadid = '$threadid' AND userid = '$userid'");
                        	if(!$check[0]) $db_connect->query("INSERT INTO db_notify VALUES ($threadid,$userid)");
                      	}
                      	if($close) $db_connect->query("UPDATE db_threads SET flags = 1 WHERE threadid = '$threadid'");
                        return 4;
	}
}

// ##########################  Useronline * ########################################################
function useronline($user_id)
{
        global $timeout,$n,$db_connect,$rekord;
        $deltime = time()-($timeout*60);
        $db_connect->query("DELETE FROM db_useronline WHERE zeit<'$deltime'");
        if($user_id!=0) {
                $anzahl = $db_connect->query_first("SELECT COUNT(zeit)as anzahl FROM db_useronline WHERE userid='$user_id'");
                if($anzahl[anzahl]) $db_connect->query("UPDATE db_useronline SET zeit='".time()."' WHERE userid='$user_id'");
                else $db_connect->query("INSERT INTO db_useronline VALUES ('".time()."','','$user_id')");
        }
        else {
        $ip = getenv(REMOTE_ADDR);
        $anzahl = $db_connect->query_first("SELECT COUNT(zeit)as anzahl FROM db_useronline WHERE ip='$ip'");
        if($anzahl[anzahl]) $db_connect->query("UPDATE db_useronline SET zeit='".time()."' WHERE ip='$ip'");
        else $db_connect->query("INSERT INTO db_useronline VALUES ('".time()."','$ip','')");
        }
        $user = $db_connect->query_first("SELECT COUNT(zeit) as anzahl FROM db_useronline");
	if($user[anzahl]>$rekord) $db_connect->query("UPDATE db_configuration set rekord='".$user[anzahl]."', rekordtime='".time()."'");
}
function sendEmail($userid,$link,$threadid,$boardid) {
        global $boardid, $master_email, $php_path, $db_connect, $n;
        $result = $db_connect->query("SELECT * FROM db_notify WHERE threadid = '$threadid'");
        if($db_connect->num_rows($result)) {
        	$boardname = getBoardname($boardid);
              	$threadname = getThreadname($threadid);
              	if($userid) $authorname = getUsername($userid);
              	else eval ("\$authorname = \"".gettemplate("lg_anonymous")."\";");
              	eval ("\$inhalt = \"".gettemplate("notify_inhalt")."\";");
                eval ("\$betreff = \"".gettemplate("notify_betreff")."\";");
                while($row = $db_connect->fetch_array($result)) {
                        if($row[userid]==$userid) continue;
                        $email = getUserEmail($row[userid]);
                        mail($email,$betreff,$inhalt.$row[userid],"From: ".$master_email);
                }
        }
}

// ##########################  activation * ########################################################
function activat($userid,$code)
{
        global $n,$db_connect;
        $anzahluser = $db_connect->query_first("SELECT COUNT(userid)as anzahl FROM db_users WHERE userid='$userid'");
        if($anzahluser[anzahl]==0) return 1;
        else {
                $result = $db_connect->query("SELECT activation FROM db_users WHERE userid='$userid' && activation!='1'");
                $anzahluser = $db_connect->num_rows($result);
                if($anzahluser==0) return 2;
                else {
                        $result = $db_connect->fetch_array($result);
                        if($code==$result[activation]) $db_connect->query("UPDATE db_users SET activation='1' WHERE userid='$userid'");
                        else return 3;
                }
        }
}

function subscripe($userid,$id,$b_or_t) {
        global $n,$db_connect,$favboards,$favthreads;
        if($b_or_t == "b") $max = $favboards;
        else $max = $favthreads;
        $field = "fav".$b_or_t;
        if(!check_userobject($userid,$id,$field)) {
		$count = $db_connect->query_first("SELECT COUNT(*) FROM db_object2user WHERE userid = '$userid' AND $field = 1");
		if($count[0] >= $max) eval ("\$output = \"".gettemplate("error24")."\";");
		else $db_connect->query("INSERT INTO db_object2user (userid,objectid,$field) VALUES ('$userid','$id','1')");
	}
        return $output;
}

function rowcolor($zeile) {
        if (($zeile/2) != floor($zeile/2)) $color="tableb";
        else $color="tablec";
        return $color;
}
function getBoardparent($threadid) {
        global $n,$db_connect;
        $result = $db_connect->query_first("SELECT boardparentid FROM db_threads WHERE threadid='$threadid'");
        return $result[boardparentid];
}
function unsubscripe($id,$userid,$b_or_t) {
        global $n,$db_connect;
        $field = "fav".$b_or_t;
        $db_connect->query("DELETE FROM db_object2user WHERE userid = '$userid' AND objectid = '$id' AND $field = 1");
}

function getUserStars($posts,$groupid) {
        global $n,$db_connect;
        $result = $db_connect->query_first("SELECT id, rank, grafik, mal FROM db_ranks WHERE groupid = $groupid AND posts<='$posts' ORDER by posts DESC");
              
        for($i = 0; $i<$result[mal]; $i++) {
                $out .= "<img src=\"$result[grafik]\" border=\"0\">";
        }
        return "<a href=\"javascript:rank($result[id])\" title=\"Information on Rank $result[rank]\">".$out."</a>";
}
function formmail($absender,$message,$betreff,$useremail) {
        global $master_board_name, $php_path;
        $useremail = trim($useremail);
        $message .= "\n\n_________________________________________________________________\nPowered by: ".$master_board_name." - ".$php_path;
        $absender = "From: ".$absender;
        mail($useremail,$betreff,$message,$absender);
}

function report($userid,$postid,$boardid) {
        global $master_board_name, $php_path, $master_email, $db_connect, $n;
        $mod = $db_connect->query_first("SELECT db_object2board.objectid, useremail FROM db_object2board LEFT JOIN db_users ON (db_object2board.objectid=db_users.userid) WHERE boardid='$boardid' AND mod=1");
        if(!$mod[useremail]) $mod = $db_connect->query_first("SELECT db_object2board.objectid, useremail FROM db_object2board LEFT JOIN db_users ON (db_object2board.objectid=db_users.userid) WHERE mod=1");
        if(!$mod[useremail]) $mod = $db_connect->query_first("SELECT db_groups.id, useremail FROM db_groups LEFT JOIN db_users ON (db_groups.id=db_users.groupid) WHERE ismod=1 OR issupermod=1 ORDER BY ismod DESC");      
        $post = $db_connect->query_first("SELECT userid, message FROM db_posts WHERE postid='$postid'");
        $authorname = getUsername($post[userid]);
        $username = getUsername($userid);
        eval ("\$betreff = \"".gettemplate("report_betreff")."\";");
        eval ("\$message = \"".gettemplate("report_mail")."\";");
        mail(trim($mod[useremail]),$betreff,$message,"From: ".$master_email);
}

// ###################### Get Code Buttons #######################
function getcodebuttons() {
        $modechecked[0] = "CHECKED";

        eval ("\$bbcode_sizebits = \"".gettemplate("bbcode_sizebits")."\";");
        eval ("\$bbcode_fontbits = \"".gettemplate("bbcode_fontbits")."\";");
        eval ("\$bbcode_colorbits = \"".gettemplate("bbcode_colorbits")."\";");
        eval ("\$bbcode_alignbits = \"".gettemplate("bbcode_alignbits")."\";");
        eval ("\$bbcode_buttons = \"".gettemplate("bbcode_buttons")."\";");
        return $bbcode_buttons;
}

// ###################### Get Clicky Smilies #######################
function getclickysmilies ($tableColumns=3,$maxSmilies=-1) {
        global $session,$boardid,$styleid, $db_connect, $n;

        $result = $db_connect->query("SELECT * FROM db_smilies");
	$totalSmilies = $db_connect->num_rows($result);

        if (($maxSmilies == -1) || ($maxSmilies >= $totalSmilies)) $maxSmilies = $totalSmilies;
        elseif ($maxSmilies < $totalSmilies) eval ("\$bbcode_smilies_getmore = \"".gettemplate("bbcode_smilies_getmore")."\";");

	$i=0;
        while($row = $db_connect->fetch_array($result)) {
        	eval ("\$smilieArray[\"".$i."\"] = \"".gettemplate("bbcode_smiliebit")."\";");
        	$i++;
        }
        $tableRows = ceil($maxSmilies/$tableColumns);
        $count = 0;
        for ($i=0; $i<$tableRows; $i++) {
                $smiliebits .= "\t<tr bgcolor=\"{tableb}\">\n";
                for ($j=0; $j<$tableColumns; $j++) {
                        $smiliebits .= "\t<td align=\"center\">".$smilieArray[$count]."&nbsp;</td>\n";
                        $count++;
                }
                $smiliebits .= "\t</tr>\n";
        }

        eval ("\$bbcode_smilies = \"".gettemplate("bbcode_smilies")."\";");
        return $bbcode_smilies;
}

function getUserposts($name) {
	global $db_connect, $n;
	$result = $db_connect->query_first("SELECT userposts FROM db_users WHERE username='$name'");
	return $result[userposts];
}
function check_boardobject($boardid,$objectid,$field) {
	global $n,$db_connect;
	$result = $db_connect->query_first("SELECT COUNT(*) FROM db_object2board WHERE boardid = '$boardid' AND objectid = '$objectid' AND $field = 1");
	return $result[0];
}
function check_userobject($userid,$objectid,$field) {
	global $n,$db_connect;
	$result = $db_connect->query_first("SELECT COUNT(*) FROM db_object2user WHERE userid = '$userid' AND objectid = '$objectid' AND $field = 1");
	return $result[0];
}
function checkemail($email, $db=0) {
	global $db_connect, $n, $multi_email, $banemail;
	if(!substr_count($email,"@") || substr_count($email,"@")>1) return 1;
	$position1 = strrpos($email,"@");
	if(!$position1) return 1;
	$position2 = strrpos($email,".");
	if(!$position2) return 1;
	if(strlen(substr($email, $position2)) < 3)return 1;
	if(strlen(substr($email, $position1,$position2-$position1-1))<2) return 1;
	if(!$multi_email && !$db) {
		$check = $db_connect->query_first("SELECT COUNT(userid) FROM db_users WHERE useremail = '$email'");
		if($check[0]) return 1;
	}
	$banemail = explode("\n",$banemail);
	for($i = 0; $i < count($banemail); $i++) {
		if(!trim($banemail[$i])) continue;
		if(ereg("\*", $banemail[$i])) {
			$banemail[$i] = str_replace("*",".*", trim($banemail[$i]));
			if(eregi("$banemail[$i]", $email)) return 1;
			break;
		}
		elseif(strtolower($email)==strtolower(trim($banemail[$i]))) {
			return 1;
			break;
		}  
	}
}

function checkname($name) {
	
	global $db_connect, $n, $banname;
	$bannames = explode("\r\n", trim($banname));
	for($i=0;$i<count($bannames);$i++) {
	 $bannames[$i] = trim($bannames[$i]);
	 if(!$bannames[$i]) continue;
	 if($name==$bannames[$i]) return 1;
	}
	$check = $db_connect->query_first("SELECT COUNT(userid) FROM db_users WHERE username = '$name'");
	return $check[0];
}

function checkpw($userid,$password) {

	global $db_connect, $n;
	$check = $db_connect->query_first("SELECT COUNT(userid) FROM db_users WHERE userid = '$userid' AND userpassword = '$password'");
	return $check[0];
}

function getAvatar($id) {
	global $db_connect, $n;
	$result = $db_connect->query_first("SELECT extension FROM db_avatars WHERE id = '$id'");
	return "images/avatars/avatar-".$id.".".$result[extension];

}
function nt_wordwrap($text, $width = 75) {
         if($text) return preg_replace("/([^\n\r ?&\.\/<>\"\\-]{".$width."})/i"," \\1\n",$text);
}
function makepagelink($link, $page, $pages) {
	$page_link = "<b>[";
	if($page!=1) $page_link .= "&nbsp;&nbsp;<a href=\"$link&page=1\">&laquo;</a>&nbsp;&nbsp;<a href=\"$link&page=".($page-1)."\"></a>";
	if($page>=6) $page_link .= "&nbsp;&nbsp;<a href=\"$link&page=".($page-5)."\">...</a>";
	if($page+4>=$pages) $pagex=$pages;
	else $pagex=$page+4;
	for($i=$page-4 ; $i<=$pagex ; $i++) { 	
		if($i<=0) $i=1;
		if($i==$page) $page_link .= "&nbsp;&nbsp;$i";
		else $page_link .= "&nbsp;&nbsp;<a href=\"$link&page=$i\">$i</a>";
	}
	if(($pages-$page)>=5) $page_link .= "&nbsp;&nbsp;<a href=\"$link&page=".($page+5)."\">...</a>";
	if($page!=$pages) $page_link .= "&nbsp;&nbsp;<a href=\"$link&page=".($page+1)."\"></a>&nbsp;&nbsp;<a href=\"$link&page=".$pages."\">&raquo;</a>";
	$page_link .= "&nbsp;&nbsp;]</b>";

	return $page_link;
}

function floodcontrol($user_id) {
	global $fctime, $db_connect, $n;
	$check_time = time()-$fctime;
	$result = $db_connect->query_first("SELECT COUNT(postid) FROM db_posts WHERE userid = '$user_id' AND posttime > '$check_time'");
	return $result[0];
}
function makeforumbit($boardid,$depth=1,$subscripe=0) {
  global $db_connect, $n, $show_subboards, $boardcache, $permissioncache, $modcache, $forumhomedepth, $session, $old_time, $user_group, $longdateformat;

  if ( !isset($boardcache[$boardid]) ) {
    return;
  }

  while ( list($key1,$val1)=each($boardcache[$boardid]) ) {
    while ( list($key2,$boards)=each($val1) ) {
	if($subscripe) eval ("\$delboard = \"".gettemplate("profile_subscripe_delboard")."\";");
	if($boards[invisible] && !$permissioncache[$boards[boardid]]) continue;
	if($boards[isboard]) { //board
		if($depth==2 && $show_subboards==1) {
			$subboards=getSubboards($boards[boardid]);
			if($subboards) $subboards=ifelse($boards[descriptiontext],"<br>","")."Inklusive: ".substr($subboards, 0, -2);	
		}
		$boards[descriptiontext] = editDBdata($boards[descriptiontext]);
		$boards[boardname] = editDBdata($boards[boardname]);
		if($old_time <= $boards[lastposttime]) eval ("\$on_or_off = \"".gettemplate("main_newposts")."\";");
		else eval ("\$on_or_off = \"".gettemplate("main_nonewposts")."\";");
	
		if($boards[lastpostid]) {
			$lastposttime = formatdate($boards[posttime],$longdateformat,1);
			if($boards[userid]) $lastauthor = "<a href=\"members.php?mode=profile&userid=$boards[userid]$session\">$boards[username]</a>";
			else eval ("\$lastauthor = \"".gettemplate("lg_anonymous")."\";");
			$boards[threadname] = prepare_topic($boards[threadname]);
			if (!$boards[topicicon]) $ViewPosticon = "<img src=\"images/icons/noicon.gif\">";
			else $ViewPosticon = "<img src=\"$boards[topicicon]\">";
			if ($permissioncache[$boards[boardid]]) $template=main_lastpost;
			else $template=main_lastpost2;
			if (strlen($boards[threadname]) > '30') $ViewThreadname = substr($boards[threadname], 0, 27)."...";
        		else $ViewThreadname = $boards[threadname];
        		eval ("\$last_post = \"".gettemplate("$template")."\";");
		}
		else $last_post = "&nbsp;";
		
		if(isset($modcache[$boards[boardid]])) {
			while (list($mkey,$moderator)=each($modcache[$boards[boardid]])) {
            			if (isset($moderators)) $moderators .= ", ";
            			$moderators .= "<a href=\"members.php?mode=profile&userid=$moderator[userid]$session\">$moderator[username]</a>";
			}
		}
		eval ("\$out .= \"".gettemplate("main_boardbit$depth")."\";");
		unset($moderators);
		
	}
	else { //cat
		if($depth==2 && $show_subboards==1) {
			$subboards=getSubboards($boards[boardid]);
			if($subboards) $subboards=ifelse($boards[descriptiontext],"<br>","")."Inklusive: ".substr($subboards, 0, -2);	
		}
		$boards[descriptiontext] = editDBdata($boards[descriptiontext]);
		$boards[boardname] = editDBdata($boards[boardname]);
		eval ("\$out .= \"".gettemplate("main_catbit$depth")."\";");
	}
        if ($depth<2) {
          $out.=makeforumbit($boards[boardid],$depth+1);
        }
    } 
  } 
  unset($boardcache[$boardid]);
  return $out;
}

function makenavichain($template, $boardid, $threadid=0) {
	global $db_connect, $n, $session;
	if($template == "board") {
		$binfo = $db_connect->query_first("SELECT boardparentid, boardname FROM db_boards WHERE boardid = '$boardid'");
		$binfo[boardname] = editDBdata($binfo[boardname]);
		$result[boardparentid] = $binfo[boardparentid];
	}
	else $result[boardparentid] = $boardid;
	eval ("\$split = \"".gettemplate("navi_split")."\";");
	do {
		$result = $db_connect->query_first("SELECT boardid, boardparentid, boardname FROM db_boards WHERE boardid = '$result[boardparentid]'");	
		if(!$result[boardid]) break;
		$out = $split."<a href=\"board.php?boardid=$result[boardid]$session\">".editDBdata($result[boardname])."</a>".$out;	
	}
	while($result[boardparentid]!=0);
	
	if($threadid) {
		$tinfo = $db_connect->query_first("SELECT threadname FROM db_threads WHERE threadid = '$threadid'");
		$tinfo[threadname] = prepare_topic($tinfo[threadname]);
	}
	eval ("\$ende = \"".gettemplate("navi_".$template)."\";");
	return $out.$ende;
}
function get_month_name($month_number) {
	$name_monat[1]    =  "January";  
	$name_monat[2]    =  "February";  
	$name_monat[3]    =  "March";  
	$name_monat[4]    =  "April";  
	$name_monat[5]    =  "May";  
	$name_monat[6]    =  "June";  
	$name_monat[7]    =  "July";  
	$name_monat[8]    =  "August";  
	$name_monat[9]    =  "September";  
	$name_monat[10]  =  "October";  
	$name_monat[11]  =  "November";  
	$name_monat[12]  =  "December";
	
	return $name_monat[$month_number];
}

function editURL($url) {
        if(strtolower(substr($url,0,7))!="http://") $url="http://".$url;
        return $url;
}

function prepare_code($out) { 
 global $db_connect,$n,$searcharray,$replacearray;
 $phpversionnum = phpversion();

 if(!isset($searcharray) && !isset($replacearray)) {
  $searcharray[]="/\[list=(['\"]?)([^\"']*)\\1](.*)\[\/list((=\\1[^\"']*\\1])|(\]))/esiU";
  $replacearray[]="formatlist('\\3', '\\2')"; 
  $searcharray[]="/\[list](.*)\[\/list\]/esiU";	
  $replacearray[]="formatlist('\\1')"; 
  $searcharray[]="/\[url=(['\"]?)([^\"']*)\\1](.*)\[\/url\]/esiU";
  $replacearray[]="formaturl('\\2','\\3')";
  $searcharray[]="/\[url]([^\"]*)\[\/url\]/esiU";	
  $replacearray[]="formaturl('\\1')";
  $searcharray[]="/\[code](.*)\[\/code\]/esiU";	
  $replacearray[]="formatcodetag('\\1')";
  $searcharray[]="/\[php](.*)\[\/php\]/esiU";	
  $replacearray[]="phphighlite('\\1')";
  $searcharray[]="/\[img]([^\"]*)\[\/img\]/siU";	
  $replacearray[]="<img src=\"\\1\" border=0>"; 
  
  $threeparams = "/\[%s=(['\"]?)([^\"']*),([^\"']*)\\1](.*)\[\/%s\]/siU";
  $twoparams = "/\[%s=(['\"]?)([^\"']*)\\1](.*)\[\/%s\]/siU";
  $oneparam = "/\[%s](.*)\[\/%s\]/siU"; 

  $result = $db_connect->query("SELECT bbcodetag,bbcodereplace,params FROM db_bbcode");

  while($row = $db_connect->fetch_array($result)) {
   if($row[params]==0) continue;
   if($row[params]==1) $search = sprintf($oneparam, $row[bbcodetag], $row[bbcodetag]);
   if($row[params]==2) $search = sprintf($twoparams, $row[bbcodetag], $row[bbcodetag]);
   if($row[params]==3) $search = sprintf($threeparams, $row[bbcodetag], $row[bbcodetag]);
        	
   $searcharray[] = $search;
   $replacearray[] = $row[bbcodereplace];
   $searcharray[] = $search;
   $replacearray[] = $row[bbcodereplace];
   $searcharray[] = $search;
   $replacearray[] = $row[bbcodereplace];
  } 
 }
 
 if ($phpversionnum<"4.0.5") $bbcode=str_replace("'", "\'", $out);
 $out = preg_replace($searcharray, $replacearray, $out);
 $out = str_replace("\\'", "'", $out);
 return $out;
}

function getSubboards($boardid) {
	global $boardcache,$session;

	if (!isset($boardcache[$boardid])) return;
  
	while(list($key1,$val1)=each($boardcache[$boardid])) {
		while(list($key2,$boards)=each($val1)) {
			$subboards.="<a href=\"board.php?boardid=$boards[boardid]$session\">".editDBdata($boards[boardname])."</a>, ".getSubboards($boards[boardid],$boards);
		}
	}
 	
 	return $subboards;
}

?>
<?php
function get_vars_old() {
 global $HTTP_COOKIE_VARS, $HTTP_POST_FILES, $HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_SERVER_VARS, $_REQUEST, $_COOKIE, $_POST, $_GET, $_SERVER, $_FILES;
  
 if(is_array($HTTP_COOKIE_VARS)) {
  while(list($key,$val)=each($HTTP_COOKIE_VARS)) {
   $_REQUEST[$key]=$val;
   $_COOKIE[$key]=$val;
  }
 }

 if(is_array($HTTP_POST_VARS)) {
  while(list($key,$val)=each($HTTP_POST_VARS)) {
   $_REQUEST[$key]=$val;
   $_POST[$key]=$val;
  }
 }
  
 if(is_array($HTTP_GET_VARS)) {
  while(list($key,$val)=each($HTTP_GET_VARS)) {
   $_REQUEST[$key]=$val;
   $_GET[$key]=$val;
  }
 }

 if(is_array($HTTP_POST_FILES)) while(list($key,$val)=each($HTTP_POST_FILES)) $_FILES[$key]=$val;
 if(is_array($HTTP_SERVER_VARS)) while(list($key,$val)=each($HTTP_SERVER_VARS)) $_SERVER[$key]=$val;
}

function convert_url($url,$hash,$nosessionhash=0) {
 if($nosessionhash==0) $url=preg_replace("/sid=[0-9a-z]*/","sid=$hash",$url);
 else $url=preg_replace("/sid=[0-9a-z]*/","sid=",$url);
 return $url;
} 

function rehtmlspecialchars($text) {
 $text = str_replace("&lt;","<",$text);
 $text = str_replace("&gt;",">",$text);	
 $text = str_replace("&quot;","\"",$text);
 $text = str_replace("&amp;","&",$text);
 
 return $text;
}

function stripslashes_array(&$array) {
 reset($array);
 while(list($key,$val)=each($array)) {
  if(is_string($val)) $array[$key]=stripslashes($val);
  elseif(is_array($val)) $array[$key]=stripslashes_array($val);
 }
 return $array;
}

function trim_array(&$array) {
 reset($array);
 while(list($key,$val)=each($array)) {
  if(is_array($val)) $array[$key]=trim_array($val);
  elseif(is_string($val)) $array[$key]=trim($val);  
 }
 return $array;
}

function htmlspecialchars_array(&$array) {
 reset($array);
 while(list($key,$val)=each($array)) {
  if(is_array($val)) $array[$key]=htmlspecialchars_array($val);
  elseif(is_string($val)) $array[$key]=htmlspecialchars($val);  
 }
 return $array;
}

function ifelse($expression,$returntrue,$returnfalse="") {
 if($expression) return $returntrue;
 else return $returnfalse;
}

function bbcookie($name, $value, $time) {
 global $cookiepath, $cookiedomain;
 
 if($cookiedomain) setcookie($name, $value, $time, $cookiepath, $cookiedomain);
 elseif($cookiepath) setcookie($name, $value, $time, $cookiepath);
 else setcookie($name, $value, $time);
 
}

function mailer($email,$subject,$text,$sender="",$other="") {
 global $frommail, $master_board_name, $smtp;
 if($smtp && ini_get("SMTP")!=$smtp) {
  @ini_set("SMTP",$smtp);
  $smtp="";
 }
 if($sender) return @mail($email,$subject,$text,"From: $sender".$other);
 else return @mail($email,$subject,$text,"From: $frommail".$other);
}

function makehreftag($url, $name, $target="") {
 return "<a href=\"".$url."\"".ifelse($target," target=\"".$target."\"").">".$name."</a>";
}

function makeimgtag($path,$alt="") {
 return "<img src=\"$path\" ".ifelse($alt,"alt=\"".$alt."\" ","")."border=0>";
}

function formatdate($timeformat,$timestamp,$replacetoday=0) {
 global $wbbuserdata, $tpl;
 $summertime = date("I")*3600;
 $timestamp+=3600*intval($wbbuserdata['timezoneoffset'])+$summertime;
 if($replacetoday==1) {
  if(gmdate("Ymd",$timestamp)==gmdate("Ymd",time()+3600*intval($wbbuserdata['timezoneoffset'])+$summertime)) {
   eval ("\$today = \"".$tpl->get("today")."\";");
   return $today;
  }
  else $replacetoday=0; 
 }
 if($replacetoday==0) return gmdate($timeformat, $timestamp);
}

function getSubboards($boardid) {
 global $boardcache, $session, $tpl;

 if (!isset($boardcache[$boardid])) return;
  
 while(list($key1,$val1)=each($boardcache[$boardid])) {
  while(list($key2,$boards)=each($val1)) {
   eval ("\$subboardbit .= \"".$tpl->get("index_subboardbit")."\";");
   $subboardbit.=getSubboards($boards[boardid]);
  }
 }
 return $subboardbit;
}

function makeboardbit($boardid,$depth=1) {
 global $db, $n, $tpl, $boardvisit, $threadvisit, $boardcache, $visitcache, $permissioncache, $modcache, $wbbuserdata, $session, $hidecats, $index_depth, $show_subboards, $showlastposttitle, $dateformat, $timeformat, $showuseronlineinboard, $filename, $temp_boardid;

 if(!isset($boardcache[$boardid])) return;
 reset($boardcache[$boardid]);
 
 $boardbit=""; 
 while(list($key1,$val1)=each($boardcache[$boardid])) {
  while(list($key2,$boards)=each($val1)) {
   if($boards['invisible']==2 || ($boards['invisible']==1 && !$permissioncache[$boards['boardid']]['boardpermission'])) continue;
   if($boards['description']) eval ("\$boards['description'] = \"".$tpl->get("index_boarddescription")."\";");
   $subboardbit="";
   $subboards="";
   if($depth==$index_depth && $show_subboards==1) {
    $subboardbit=getSubboards($boards['boardid']);
    if($subboardbit) {
     $subboardbit=substr($subboardbit, 0, -2);
     eval ("\$subboards = \"".$tpl->get("index_subboard")."\";");
    }	
   }
   
   if($wbbuserdata['lastvisit'] > $boards['lastposttime'] || $boardvisit[$boards['boardid']] > $boards['lastposttime']) $onoff="off";
   else {
    $onoff="off";
    $tempids = explode(",","$boards[boardid],$boards[childlist]");
    for($j=0;$j<count($tempids);$j++) {
     if($tempids[$j]==0) continue;
     if(is_array($visitcache[$tempids[$j]]) && count($visitcache[$tempids[$j]])) {
      reset($visitcache[$tempids[$j]]);
      while(list($threadid,$lastposttime)=each($visitcache[$tempids[$j]])) {
       if($threadvisit[$threadid]<$lastposttime && $boardvisit[$tempids[$j]]<$lastposttime) {
        $onoff="on";
        break 2;
       } // end if
      } // end while
     } // end if
    } // end for	
   } // end else
   
   if($boards['isboard']) { 
    
   if($boards['closed']==1) $onoff.="closed";
   elseif((!$permissioncache[$boards['boardid']]['startpermission'] && !$permissioncache[$boards['boardid']]['replypermission']) || (!$wbbuserdata['canstarttopic'] && !$wbbuserdata['canreplytopic'])) $onoff.="closed";
   
   if($showuseronlineinboard==1) eval ("\$useronline = \"".$tpl->get("index_board_useronline")."\";");
   elseif($showuseronlineinboard==2) {
    global $userinboard, $online;
    
    if(count($userinboard[$boards['boardid']])) {
     while(list($key,$row)=each($userinboard[$boards['boardid']])) $online->user($row['userid'],$row['username'],$row['groupid'],$row['invisible']);
     $boards['useronline']=$online->useronlinebit;
     $online->useronlinebit="";
     if(!empty($boards['useronline'])) eval ("\$useronline = \"".$tpl->get("index_board_useronline")."\";");
    }
    else $useronline="";
   }
   
   if($boards['threadcount']) {
    $lastpostdate=formatdate($wbbuserdata['dateformat'],$boards['lastposttime'],1); 
    $lastposttime=formatdate($wbbuserdata['timeformat'],$boards['lastposttime']); 
    if($boards['lastposterid']) eval ("\$lastposter = \"".$tpl->get("index_lastposter")."\";");
    else eval ("\$lastposter = \"".$tpl->get("index_lastposter_guest")."\";");
    if($showlastposttitle==1) {
     if(!$permissioncache[$boards['boardid']]['boardpermission']) eval ("\$lastposttitle = \"".$tpl->get("index_lastpost_title_hide")."\";");
     else {
      if(strlen($boards['topic'])>30) $topic=cutTopic($boards['topic']);
      else $topic=$boards['topic'];
      eval ("\$lastposttitle = \"".$tpl->get("index_lastpost_title_show")."\";");
     }
     if(isset($boards['iconid'])) $ViewPosticon=makeimgtag($boards['iconpath'],$boards['icontitle']);
     else $ViewPosticon=makeimgtag("{imagefolder}/icons/icon14.gif");
     
     if($boards['threadprefix']!="") {
      $threads['prefix']=$boards['threadprefix'];	
      eval ("\$prefix = \"".$tpl->get("board_thread_prefix")."\";");
     }
     else $prefix="";
     
     eval ("\$lastpost = \"".$tpl->get("index_lastpost_title")."\";");
    }
    else eval ("\$lastpost = \"".$tpl->get("index_lastpost")."\";");
   }
   else eval ("\$lastpost = \"".$tpl->get("index_nolastpost")."\";");
		
    $moderators="";
    $moderatorbit="";
    if(isset($modcache[$boards['boardid']])) {
     while (list($mkey,$moderator)=each($modcache[$boards['boardid']])) {
      if($moderatorbit) eval ("\$moderatorbit .= \", ".$tpl->get("index_moderatorbit")."\";");
      else eval ("\$moderatorbit = \"".$tpl->get("index_moderatorbit")."\";");
     }
    }
    else $moderatorbit="&nbsp;";
    eval ("\$moderators = \"".$tpl->get("index_moderators")."\";");
    eval ("\$boardbit .= \"".$tpl->get("index_boardbit$depth")."\";");
    
   }
   else {
    if($boards['childlist']!="0") {
     if($filename=="index.php") $current_url = "index.php?sid=$session[hash]";
     else $current_url = "board.php?sid=$session[hash]&boardid=$temp_boardid";
     if((isset($hidecats[$boards['boardid']]) && $hidecats[$boards['boardid']]==0) || ($depth<$index_depth && (!isset($hidecats[$boards['boardid']]) || $hidecats[$boards['boardid']]!=1))) eval ("\$show_hide = \"".$tpl->get("index_hidecat")."\";");
     else eval ("\$show_hide = \"".$tpl->get("index_showcat")."\";");
    }
    else $show_hide="";
    eval ("\$boardbit .= \"".$tpl->get("index_catbit$depth")."\";");
   }
   if ((isset($hidecats[$boards['boardid']]) && $hidecats[$boards['boardid']]==0) || ($depth<$index_depth && (!isset($hidecats[$boards['boardid']]) || $hidecats[$boards['boardid']]!=1))) $boardbit.=makeboardbit($boards['boardid'],$depth+1);
  } 
 } 
 unset($boardcache[$boardid]);
 
 return $boardbit;
}

function getNavbar($parentlist,$template="navbar_board") {
 global $db, $n, $session, $url2board, $lines, $tpl, $boardnavcache;
 if($parentlist=="0") return;
 else {
  $navbar="";
  if(!isset($boardnavcache) || !is_array($boardnavcache) || !count($boardnavcache)) {
   $result = $db->query("SELECT boardid, title FROM bb".$n."_boards WHERE boardid IN ($parentlist)");
   while($row=$db->fetch_array($result)) $boardnavcache[$row['boardid']]=$row;
  }
  $parentids=explode(",", $parentlist);
  for($i=1;$i<count($parentids);$i++) {
   if($template=="print_navbar") $lines.=str_repeat("-",$i);
   $board=$boardnavcache[$parentids[$i]];
   eval ("\$navbar .= \"".$tpl->get($template)."\";");
  }
  return $navbar;
 }
}

function getcodebuttons() {
 global $bbcodemode, $tpl;
 if($bbcodemode==1) $modechecked[1]="checked";
 else $modechecked[0]="checked";
 
 eval ("\$bbcode_sizebits = \"".$tpl->get("bbcode_sizebits")."\";");
 eval ("\$bbcode_fontbits = \"".$tpl->get("bbcode_fontbits")."\";");
 eval ("\$bbcode_colorbits = \"".$tpl->get("bbcode_colorbits")."\";");
 eval ("\$bbcode_buttons = \"".$tpl->get("bbcode_buttons")."\";");
 return $bbcode_buttons;
}

function getclickysmilies($tableColumns=3,$maxSmilies=-1) {
 global $db, $n, $tpl, $showsmiliesrandom;

 if($showsmiliesrandom==1) $result = $db->query("SELECT smiliepath, smilietitle, smiliecode FROM bb".$n."_smilies ORDER BY RAND()");
 else $result = $db->query("SELECT smiliepath, smilietitle, smiliecode FROM bb".$n."_smilies ORDER BY smilieorder ASC");
 $totalSmilies = $db->num_rows($result);

 if (($maxSmilies == -1) || ($maxSmilies >= $totalSmilies)) $maxSmilies = $totalSmilies;
 elseif ($maxSmilies < $totalSmilies) eval ("\$bbcode_smilies_getmore = \"".$tpl->get("bbcode_smilies_getmore")."\";");

 $i=0;
 while($row = $db->fetch_array($result)) {
  eval ("\$smilieArray[\"".$i."\"] = \"".$tpl->get("bbcode_smiliebit")."\";");
  $i++;
 }
        
 $tableRows = ceil($maxSmilies/$tableColumns);
 $count = 0;
 for ($i=0; $i<$tableRows; $i++) {
  $smiliebits .= "\t<tr bgcolor=\"{tablecolorb}\" id=\"tableb\">\n";
  for ($j=0; $j<$tableColumns; $j++) {
   $smiliebits .= $smilieArray[$count];
   $count++;
  }
  $smiliebits .= "\t</tr>\n";
 }

 eval ("\$bbcode_smilies = \"".$tpl->get("bbcode_smilies")."\";");
 return $bbcode_smilies;
}

function getone($number, $one, $two) {
 if($number % 2) return $one;
 else return $two;
}

function makeboardjump($current) {
 global $wbbuserdata, $boardcache, $permissioncache, $useuseraccess, $tpl, $session, $boardnavcache;
 
 if(!isset($boardcache) || !isset($permissioncache)) {
  global $db, $n, $wbbuserdata, $boardordermode;
  
  switch($boardordermode) {
   case 1: $boardorder="title ASC"; break;
   case 2: $boardorder="title DESC"; break;
   case 3: $boardorder="lastposttime DESC"; break;
   default: $boardorder="boardorder ASC"; break;
  }
    
  $result = $db->query("SELECT boardid, parentid, boardorder, title, invisible FROM bb".$n."_boards ORDER by parentid ASC, $boardorder");
  while ($row = $db->fetch_array($result)) {
   $boardcache[$row['parentid']][$row['boardorder']][$row['boardid']] = $row;
   $boardnavcache[$row['boardid']]=$row;
  }
  $result = $db->query("SELECT * FROM bb".$n."_permissions WHERE groupid = '$wbbuserdata[groupid]'");
  while ($row = $db->fetch_array($result)) $permissioncache[$row['boardid']] = $row;
  if($wbbuserdata['userid'] && $useuseraccess==1) {
   $result = $db->query("SELECT * FROM bb".$n."_access WHERE userid = '$wbbuserdata[userid]'");
   while ($row = $db->fetch_array($result)) $permissioncache[$row['boardid']] = $row;
  }
 }
 if(is_array($boardcache) && count($boardcache)) {
  reset($boardcache);
  $boardoptions=makeboardselect(0,1,$current);
 }
 eval ("\$boardjump = \"".$tpl->get("boardjump")."\";");
 return $boardjump;
}

function makeboardselect($boardid,$depth=1,$current=0) {
 global $boardcache, $permissioncache, $accesscache;
 
 if(!isset($boardcache[$boardid])) return;
 $boardbit="";
 while (list($key1,$val1) = each($boardcache[$boardid])) {
  while(list($key2,$boards) = each($val1)) {
   if($boards['invisible']==2 || ($boards['invisible']==1 && !$permissioncache[$boards['boardid']]['boardpermission'])) continue;
   if($depth>1) $prefix=str_repeat("--",$depth-1)." ";
   else $prefix="";
   $boardbit .= makeoption($boards['boardid'],$prefix.$boards['title'],$current,1);
   $boardbit .= makeboardselect($boards['boardid'],$depth+1,$current);
  } 
 } 
 unset($boardcache[$boardid]);
 return $boardbit;	
}

function formatRI($images) {
 if(!$images) return;
 
 $imgArray = explode(";",$images);
 
 for($i=0;$i<count($imgArray);$i++) 
  $RI.="<img src=\"$imgArray[$i]\">";
 
 return $RI;
}

function parseURL($message) {
 $urlsearch[]="/([^]_a-z0-9-=\"'\/])((https?|ftp):\/\/|www\.)([^ \r\n\(\)\^\$!`\"'\|\[\]\{\}<>]*)/si";
 $urlsearch[]="/^((https?|ftp):\/\/|www\.)([^ \r\n\(\)\^\$!`\"'\|\[\]\{\}<>]*)/si";
 $urlreplace[]="\\1[URL]\\2\\4[/URL]";
 $urlreplace[]="[URL]\\1\\3[/URL]";
 $emailsearch[]="/([\s])([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si";
 $emailsearch[]="/^([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si";
 $emailreplace[]="\\1[EMAIL]\\2[/EMAIL]";
 $emailreplace[]="[EMAIL]\\0[/EMAIL]";
 $message = preg_replace($urlsearch, $urlreplace, $message);
 if (strpos($message, "@")) $message = preg_replace($emailsearch, $emailreplace, $message);
 return $message;
}

function makepagelink($link,$page,$pages,$number) {
 global $tpl;
 eval ("\$pagelink = \"".$tpl->get("pagelink")."\";");
 
 if($page-$number>1) eval ("\$pagelink .= \"".$tpl->get("pagelink_first")."\";");
 if($page>1) {
  $temppage=$page-1;
  eval ("\$pagelink .= \"".$tpl->get("pagelink_left")."\";");
 }
 $count = ifelse($page+$number>=$pages,$pages,$page+$number);
 for($i=$page-$number;$i<=$count;$i++) {
  if($i<1) $i=1;
  if($i==$page) eval ("\$pagelink .= \"".$tpl->get("pagelink_current")."\";");
  else eval ("\$pagelink .= \"".$tpl->get("pagelink_nocurrent")."\";");
 }

 if($page<$pages) {
  $temppage=$page+1;
  eval ("\$pagelink .= \"".$tpl->get("pagelink_right")."\";");
 }
 if($page+$number<$pages) eval ("\$pagelink .= \"".$tpl->get("pagelink_last")."\";");
 
 return $pagelink;
}

function makeoption($value,$text,$selected_value="",$selected=1,$style="") {
 $option_selected="";
 if($selected==1) {
  if(is_array($selected_value)) {
   if(in_array($value,$selected_value)) $option_selected=" selected";
  }
  elseif($selected_value==$value) $option_selected=" selected";
 }
 return "<option value=\"$value\"".ifelse($style!=""," style=\"color:$style\"").$option_selected.">$text</option>";
}

function getmonth($number) {
 global $months, $tpl;
 if(!isset($months)) $months = explode("|", $tpl->get("months"));
 return $months[$number-1];
}

function getday($number) {
 global $days, $tpl;
 if(!isset($days)) $days = explode("|", $tpl->get("days"));
 return $days[$number];
}

function access_error() {
 global $wbbuserdata, $header, $footer, $headinclude, $session, $master_board_name, $REQUEST_URI, $tpl;

 if($wbbuserdata['userid']) eval ("\$access_errorbit = \"".$tpl->get("access_error_user")."\";");
 else eval ("\$access_errorbit = \"".$tpl->get("access_error_guest")."\";");
 
 eval("\$tpl->output(\"".$tpl->get("access_error")."\");");
 exit();
}

function verify_username($username) {
 global $db, $n, $minusernamelength, $maxusernamelength, $ban_name;

 if(strlen($username)<$minusernamelength || strlen($username)>$maxusernamelength) return false;
 $ban_name=explode("\n",preg_replace("/\s*\n\s*/","\n",strtolower(trim($ban_name))));
 if(count($ban_name) && in_array(strtolower($username),$ban_name)) return false;
 $result = $db->query_first("SELECT COUNT(*) FROM bb".$n."_users WHERE username = '".addslashes(htmlspecialchars($username))."'");
 if($result[0]!=0) return false;
 else return true;
}

function verify_email($email) {
 global $db, $n, $multipleemailuse, $ban_email;

 $email=strtolower($email);
 if(!preg_match("/^([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si",$email)) return false;
 $ban_email=explode("\n",preg_replace("/\s*\n\s*/","\n",strtolower(trim($ban_email))));
 for($i = 0; $i < count($ban_email); $i++) {
  $ban_email[$i]=trim($ban_email[$i]);
  if(!$ban_email[$i]) continue;
  if(strstr($ban_email[$i], "*")) {
   $ban_email[$i] = str_replace("*",".*",$ban_email[$i]);
   if(preg_match("/$ban_email[$i]/i",$email)) return false;
  }
  elseif($email==$ban_email[$i]) return false;
 }
 if($multipleemailuse==1) return true;
 else {
  $result = $db->query_first("SELECT COUNT(*) FROM bb".$n."_users WHERE email = '".$email."'");
  if($result[0]!=0) return false;
  else return true;
 }
}

function verify_ip($ip) {
 global $ban_ip;

 if($ban_ip) {
  $ban_ip=explode("\n",preg_replace("/\s*\n\s*/","\n",strtolower(trim($ban_ip))));
  for($i = 0; $i < count($ban_ip); $i++) {
   $ban_ip[$i]=trim($ban_ip[$i]);
   if(!$ban_ip[$i]) continue;
   if(strstr($ban_ip[$i], "*")) {
    $ban_ip[$i] = str_replace("*",".*",$ban_ip[$i]);
    if(preg_match("/$ban_ip[$i]/i",$ip)) access_error();
   }
   elseif($ip==$ban_ip[$i]) access_error();
  }
 }
}

function flood_control($userid,$ipaddress,$avoidfc) {
 if($avoidfc==1) return false;
 
 global $db, $n, $fctime;
 if($userid) $result=$db->query_first("SELECT postid FROM bb".$n."_posts WHERE userid='$userid' AND posttime>='".(time()-$fctime)."'",1);	
 else $result=$db->query_first("SELECT postid FROM bb".$n."_posts WHERE ipaddress='$ipaddress' AND posttime>='".(time()-$fctime)."'",1);	
 if($result['postid']) return true;
 else return false;	
}


function password_generate($numbers=2,$length=8) {
 
 $time = intval(substr(microtime(), 2, 8));
 mt_srand($time);
 
 $numberchain="1234567890";
 
 for($i=0;$i<$numbers;$i++) {
  $random=mt_rand(0,strlen($numberchain)-1);
  $number[intval($numberchain[$random])]=mt_rand(1,9);
  $numberchain=str_replace($random,"",$numberchain);
 } 
  
 $chain = "abcdefghijklmnopqrstuvwxyz";
 for($i=0;$i<$length;$i++) {
  if($number[$i]) $password.=$number[$i];
  else $password.=$chain[mt_rand(0,strlen($chain)-1)];
 }
 return $password;
}

function code_generate() {
 
 $time = intval(substr(microtime(), 2, 8));
 mt_srand($time);
 
 return mt_rand(1000000,10000000);
} 

function error($error_msg) {
 global $headinclude, $master_board_name, $tpl;
 eval("\$tpl->output(\"".$tpl->get("error")."\");");
 exit();
}

function redirect($msg,$url,$waittime=5) {
 global $headinclude, $master_board_name, $tpl;
 eval("\$tpl->output(\"".$tpl->get("redirect")."\");");
 exit();
}

function acp_error($error_msg) {
 eval("print(\"".gettemplate("error")."\");");
 exit();
}

function acp_error_frame($error_msg) {
 eval("\$temp = \"".gettemplate("error_frame")."\";");
 return $temp;
}

function stopShooting($topic) {
 if($topic==strtoupper($topic)) return ucwords(strtolower($topic));
 return $topic;	
}

function cutTopic($topic,$length=28) {
 $topic=str_replace("&quot;","\"",$topic);	
 $topic=str_replace("&lt;","<",$topic);	
 $topic=str_replace("&gt;",">",$topic);	
 $topic=str_replace("&amp;","&",$topic);
 
 $topic = trim(substr($topic, 0, $length))."...";
 return htmlspecialchars($topic);	
}

function htmlspecialchars_wbb($text) {
 $text=str_replace("\"","&quot;",$text);	
 $text=str_replace("<","&lt;",$text);	
 $text=str_replace(">","&gt;",$text);	
 
 return $text;
}

function add2list($list,$add) {
 if($list=="") return $add;
 else {
  $listelements=explode(' ',$list);
  if(!in_array($add,$listelements)) {
   $listelements[]=$add;
   return implode(' ',$listelements);
  }
  else return -1;
 }
}

function removeFromlist($list,$remove) {
 $listelements=explode(' ',$list);
 if(!in_array($remove,$listelements)) return -1;
 else {
  $count=count($listelements);
  for($i=0;$i<$count;$i++) {
   if($listelements[$i]==$remove) {
    if($i==$count-1) array_pop($listelements);
    else $listelements[$i]=array_pop($listelements);
    break;
   }
  }
  return implode(' ',$listelements);
 }
}

function wordmatch($postid,$message,$topic) {
 global $db, $n, $minwordlength, $maxwordlength, $badsearchwords;

 if($topic) {
  $topicwords=preg_replace("/[\s,]/s"," ",$topic);
  $topicwords=preg_replace("/(\.+)($| |\n|\t)/s"," ",$topicwords);
  $topicwords=str_replace("["," [",$topicwords);
  $topicwords=str_replace("]","] ",$topicwords);
  $topicwords=preg_replace("/[\/:;'\"\(\)\[\]?!#{}%_\-+\\\\]/s","",$topicwords);
  $topicwords=strtolower(trim(str_replace("  "," ",$topicwords)));
  $temparray=explode(" ",$topicwords);
  while (list($key,$val)=each($temparray)) $topicwordarray[$val]=1;
 } 
 
 $words=preg_replace("/[\s,]/s"," ",$message);
 $words=preg_replace("/(\.+)($| |\n|\t)/s"," ",$words);
 $words=str_replace("["," [",$words);
 $words=str_replace("]","] ",$words);
 $words=preg_replace("/[\/:;'\"\(\)\[\]?!#{}_\-+\\\\]/s","",$words);
 $words=strtolower(trim(str_replace("  "," ",$words)));
 if($topicwords) $words.=" ".$topicwords;
 $wordarray=explode(" ",$words);
 
 $result = $db->query("SELECT wordid, word FROM bb".$n."_wordlist WHERE word IN ('".str_replace(" ","','",$words)."')");
 while($row=$db->fetch_array($result)) $wordcache[$row[word]]=$row[wordid];
 $db->free_result($result);
 
 $badwords=array();
 if($badsearchwords) {
  $temp=explode("\n",$badsearchwords);
  while(list($key,$val)=each($temp)) $badwords[trim($val)]=1;
 }
 
 $alreadyadd=array();
 $wordmatch_sql="";
 $newtopicwords="";
 $newwords="";
 while(list($key,$val)=each($wordarray)) {
  if($badwords[$val]==1 || strlen($val)<$minwordlength || strlen($val)>$maxwordlength) {
   unset($wordarray[$key]);
   continue;
  }
  
  if($val && $alreadyadd[$val]!=1) {
   if($wordcache[$val]) $wordmatch_sql.=",($wordcache[$val],$postid,".ifelse($topicwordarray[$val]==1,1,0).")";
   else {
    if($topicwordarray[$val]==1) $newtopicwords.=$val." ";
    else $newwords.=$val." ";
   }
   $alreadyadd[$val]=1;
  }
 }
 
 if($wordmatch_sql) $db->query("REPLACE INTO bb".$n."_wordmatch (wordid,postid,intopic) VALUES ".substr($wordmatch_sql,1));
 
 if ($newwords) {
  $newwords=trim($newwords);
  $insertwords="(NULL,'".str_replace(" ","'),(NULL,'",addslashes($newwords))."')";
  $db->query("INSERT IGNORE INTO bb".$n."_wordlist (wordid,word) VALUES $insertwords");
  $selectwords="word IN('".str_replace(" ","','",addslashes($newwords))."')";
  $db->query("INSERT IGNORE INTO bb".$n."_wordmatch (wordid,postid) SELECT DISTINCT wordid,$postid FROM bb".$n."_wordlist WHERE $selectwords");
 }

 if ($newtopicwords) {
  $newtopicwords=trim($newtopicwords);
  $insertwords="(NULL,'".str_replace(" ","'),(NULL,'",addslashes($newtopicwords))."')";
  $db->query("INSERT IGNORE INTO bb".$n."_wordlist (wordid,word) VALUES $insertwords");
  $selectwords="word IN('".str_replace(" ","','",addslashes($newtopicwords))."')";
  $db->query("REPLACE INTO bb".$n."_wordmatch (wordid,postid,intopic) SELECT DISTINCT wordid,$postid,1 FROM bb".$n."_wordlist WHERE $selectwords");
 }
}

function deletethread($threadid) {
 global $db, $n, $thread, $board, $boardid;

 $db->query("DELETE FROM bb".$n."_threads WHERE threadid = '$threadid'"); // delete thread
 $db->unbuffered_query("DELETE FROM bb".$n."_threads WHERE pollid = '$threadid' AND closed=3",1);
 if($thread['important']==2) $db->unbuffered_query("DELETE FROM bb".$n."_announcements WHERE threadid = '$threadid'",1);
 $db->query("DELETE FROM bb".$n."_subscribethreads WHERE threadid = '$threadid'"); // delete subscriptions
 
 if($thread['pollid']) { // delete poll 
  $db->query("DELETE FROM bb".$n."_polls WHERE pollid = '$thread[pollid]'");
  $pollvotes=" OR (id = '$thread[pollid]' AND votemode=1)";
  $db->query("DELETE FROM bb".$n."_polloptions WHERE pollid = '$thread[pollid]'");
 }
 else $pollvotes="";
 $db->query("DELETE FROM bb".$n."_votes WHERE (id = '$threadid' AND votemode=2)$pollvotes"); // delete ratings
  
 if($thread['attachments']) { // delete attachments
  $result = $db->query("SELECT attachmentid FROM bb".$n."_posts WHERE threadid='$threadid' AND attachmentid>0");
  while($row=$db->fetch_array($result)) $attachmentids.=",".$row['attachmentid'];
  $result = $db->query("SELECT attachmentid, attachmentextension FROM bb".$n."_attachments WHERE attachmentid IN (0$attachmentids)");
  while($row=$db->fetch_array($result)) @unlink("attachments/attachment-".$row['attachmentid'].".".$row['attachmentextension']);
  $db->query("DELETE FROM bb".$n."_attachments WHERE attachmentid IN (0$attachmentids)");
 }
  
 if($board['countuserposts']==1) { // delete userpost
  $result = $db->query("SELECT COUNT(postid) AS posts, userid FROM bb".$n."_posts WHERE threadid='$threadid' AND visible=1 AND userid>0 GROUP BY userid");
  while($row=$db->fetch_array($result)) $db->query("UPDATE bb".$n."_users SET userposts=userposts-'$row[posts]' WHERE userid='$row[userid]'");
 }
  
 $db->query("DELETE FROM bb".$n."_posts WHERE threadid = '$threadid'"); // delete posts
 $thread['replycount']+=1;
 
 /* update boardcount */
 $db->query("UPDATE bb".$n."_boards SET threadcount=threadcount-1, postcount=postcount-'$thread[replycount]' WHERE boardid IN ($boardid,$board[parentlist])");
 if($board['lastthreadid']==$threadid) updateBoardInfo("$boardid,$board[parentlist]",0,$threadid);
}

function deleteposts($postids,$threadid,$postcount) {
 global $db, $n, $thread, $board, $boardid;

 $result = $db->query("SELECT postid, parentpostid FROM bb".$n."_posts WHERE postid IN ($postids) ORDER BY posttime DESC");
 while($row=$db->fetch_array($result)) $db->query("UPDATE bb".$n."_posts SET parentpostid='$row[parentpostid]' WHERE parentpostid='$row[postid]'");
  
 if($board['countuserposts']==1) {
  $result = $db->query("SELECT COUNT(postid) AS posts, userid FROM bb".$n."_posts WHERE postid IN ($postids) AND visible=1 AND userid>0 GROUP BY userid");
  while($row=$db->fetch_array($result)) $db->unbuffered_query("UPDATE bb".$n."_users SET userposts=userposts-'$row[posts]' WHERE userid='$row[userid]'",1);
 }
 
 $attachmentcount=0;
 if($thread['attachments']) { // delete attachments
  $result = $db->query("SELECT attachmentid FROM bb".$n."_posts WHERE postid IN ($postids) AND attachmentid>0");
  while($row=$db->fetch_array($result)) {
   $attachmentids.=",".$row['attachmentid'];
   $attachmentcount++;
  }
  $result = $db->query("SELECT attachmentid, attachmentextension FROM bb".$n."_attachments WHERE attachmentid IN (0$attachmentids)");
  while($row=$db->fetch_array($result)) @unlink("attachments/attachment-".$row['attachmentid'].".".$row['attachmentextension']);
  $db->unbuffered_query("DELETE FROM bb".$n."_attachments WHERE attachmentid IN (0$attachmentids)",1);
 }

 $db->query("DELETE FROM bb".$n."_posts WHERE postid IN ($postids)");
    
 $db->query("UPDATE bb".$n."_boards SET postcount=postcount-'$postcount' WHERE boardid IN ($boardid,$board[parentlist])");
 $result=$db->query_first("SELECT userid, username, posttime FROM bb".$n."_posts WHERE threadid='$threadid' ORDER BY posttime DESC",1);
 $db->query("UPDATE bb".$n."_threads SET replycount=replycount-'$postcount', lastposttime='$result[posttime]', lastposterid='$result[userid]', lastposter='".addslashes($result['username'])."'".ifelse($attachmentcount!=0,", attachments=attachments-'$attachmentcount'")." WHERE threadid='$threadid'");
    
 updateBoardInfo("$boardid,$board[parentlist]",$thread['lastposttime']);
}

function updateBoardInfo($boardidlist,$lastposttime=-1,$lastthreadid=-1) {
 global $db, $n;	
 if($lastthreadid!=-1) $result = $db->query("SELECT boardid, childlist FROM bb".$n."_boards WHERE boardid IN ($boardidlist) AND lastthreadid='$lastthreadid'");
 elseif($lastposttime!=-1) $result = $db->query("SELECT boardid, childlist FROM bb".$n."_boards WHERE boardid IN ($boardidlist) AND lastposttime<='$lastposttime'");
 else $result = $db->query("SELECT boardid, childlist FROM bb".$n."_boards WHERE boardid IN ($boardidlist)");
 while($row=$db->fetch_array($result)) {
  $lastpost=$db->query_first("SELECT p.threadid, p.userid, p.username, p.posttime FROM bb".$n."_posts p, bb".$n."_threads t WHERE p.threadid=t.threadid AND t.boardid IN ($row[boardid],$row[childlist]) AND p.visible=1 ORDER BY p.posttime DESC",1);
  $db->unbuffered_query("UPDATE bb".$n."_boards SET lastthreadid='$lastpost[threadid]', lastposttime='$lastpost[posttime]', lastposterid='$lastpost[userid]', lastposter='".addslashes($lastpost['username'])."' WHERE boardid='$row[boardid]'",1);
 }	
}

function userrating($count,$points,$userid) {
 global $tpl;
 
 if($count==0) eval ("\$userrating = \"".$tpl->get("userrating_rate")."\";");
 else {
  $doubletemp=$points/$count;
  $temp=number_format($doubletemp,1);
  $rating=number_format($doubletemp,2);
  
  $width=$temp*10;
  if($temp>6.6) eval ("\$ratingbar = \"".$tpl->get("userrating_green")."\";");
  elseif($temp>3.3) eval ("\$ratingbar = \"".$tpl->get("userrating_yellow")."\";");	
  else eval ("\$ratingbar = \"".$tpl->get("userrating_red")."\";");
  eval ("\$userrating = \"".$tpl->get("userrating")."\";");
 }
 return $userrating;
}

function stripcrap($post) {
 if($post) {
  $post=preg_replace("/(\?|\&){1}sid=[a-z0-9]{32}/","\\1sid=",$post);	
  $post=preg_replace("/(&#)(\d+)(;)/e","chr(intval('\\2'))",$post);
 }
 return $post;
}

function gettemplate($template,$php=0) {
 $file=implode("",file("templates/".$template.".htm"));
 if($php) return $file;
 else return str_replace("\"","\\\"",$file);
}

function movethread($threadid,$mode,$newboardid) {
 global $board, $thread, $db, $n, $newboard;	
 
 $boardid=$board['boardid'];
 if(!is_array($newboard)) $newboard = $db->query_first("SELECT * FROM bb".$n."_boards WHERE boardid = '$newboardid'");
  
 if($mode=="onlymove" || $mode=="movewithredirect") {
  if($thread['important']==2) {
   list($announcements) = $db->query_first("SELECT COUNT(*) FROM bb".$n."_announcements WHERE threadid='$threadid'");	
   if($announcements>1) $db->query("INSERT IGNORE INTO bb".$n."_announcements (boardid,threadid) VALUES ('$newboardid','$threadid')");
   else $db->query("UPDATE bb".$n."_announcements SET boardid='$newboardid' WHERE threadid='$threadid' AND boardid='$boardid'");
  }
   
  $db->query("UPDATE bb".$n."_threads SET boardid='$newboardid' WHERE threadid='$threadid'");
  if($mode=="movewithredirect") $db->query("INSERT INTO bb".$n."_threads (boardid,prefix,topic,iconid,starttime,starterid,starter,lastposttime,lastposterid,lastposter,replycount,views,closed,voted,votepoints,pollid,visible) VALUES ('$boardid','".addslashes($thread['prefix'])."','".addslashes($thread['topic'])."','$thread[iconid]','$thread[starttime]','$thread[starterid]','".addslashes($thread['starter'])."','$thread[lastposttime]','$thread[lastposterid]','".addslashes($thread['lastposter'])."','$thread[replycount]','$thread[views]','3','$thread[voted]','$thread[votepoints]','$threadid','$thread[visible]')");
   
  $thread['replycount']+=1;
  $db->query("UPDATE bb".$n."_boards SET threadcount=threadcount-1, postcount=postcount-'$thread[replycount]' WHERE boardid IN ($boardid,$board[parentlist])");
  $db->query("UPDATE bb".$n."_boards SET threadcount=threadcount+1, postcount=postcount+'$thread[replycount]' WHERE boardid IN ($newboardid,$newboard[parentlist])");
  
  if($board['lastthreadid']==$threadid) updateBoardInfo("$boardid,$board[parentlist]",0,$threadid);
  if($newboard['lastposttime']<=$thread['lastposttime']) updateBoardInfo("$newboardid,$newboard[parentlist]",$thread['lastposttime']);
    
  if($board['countuserposts']==1 && $newboard['countuserposts']==0) {
   $result = $db->query("SELECT COUNT(postid) AS posts, userid FROM bb".$n."_posts WHERE threadid='$threadid' AND visible = 1 AND userid>0 GROUP BY userid");
   while($row=$db->fetch_array($result)) $db->query("UPDATE bb".$n."_users SET userposts=userposts-'$row[posts]' WHERE userid='$row[userid]'");
  }
  if($board['countuserposts']==0 && $newboard['countuserposts']==1) {
   $result = $db->query("SELECT COUNT(postid) AS posts, userid FROM bb".$n."_posts WHERE threadid='$threadid' AND visible = 1 AND userid>0 GROUP BY userid");
   while($row=$db->fetch_array($result)) $db->query("UPDATE bb".$n."_users SET userposts=userposts+'$row[posts]' WHERE userid='$row[userid]'");
  }
 }
 if($mode=="copy") {
  $db->query("INSERT INTO bb".$n."_threads (threadid,boardid,topic,iconid,starttime,starterid,starter,lastposttime,lastposterid,lastposter,replycount,views,closed,voted,votepoints,attachments,pollid,important,visible)
  VALUES (NULL,'$newboardid','".addslashes($thread[topic])."','$thread[iconid]','$thread[starttime]','$thread[starterid]','".addslashes($thread[starter])."','$thread[lastposttime]','$thread[lastposterid]','".addslashes($thread[lastposter])."','$thread[replycount]','$thread[views]','$thread[closed]','$thread[voted]','$thread[votepoints]','$thread[attachments]','$thread[pollid]','$thread[important]','$thread[visible]')");
  $newthreadid=$db->insert_id();
  
  $result = $db->query("SELECT * FROM bb".$n."_announcements WHERE threadid='$threadid'");
  while($row=$db->fetch_array($result)) $db->query("INSERT INTO bb".$n."_announcements (boardid,threadid) VALUES ('$row[boardid]','$newthreadid')");
  $db->query("INSERT IGNORE INTO bb".$n."_announcements (boardid,threadid) VALUES ('$newboardid','$newthreadid')");
    
  $result = $db->query("SELECT * FROM bb".$n."_posts WHERE threadid='$threadid'");
  while($row=$db->fetch_array($result)) {
   $db->query("INSERT INTO bb".$n."_posts (postid,threadid,userid,username,iconid,posttopic,posttime,message,attachmentid,edittime,editorid,editor,editcount,allowsmilies,showsignature,ipaddress,visible,reindex)
   VALUES (NULL,'$newthreadid','$row[userid]','".addslashes($row[username])."','$row[iconid]','".addslashes($row[posttopic])."','$row[posttime]','".addslashes($row[message])."','$row[attachmentid]','$row[edittime]','$row[editorid]','".addslashes($row[editor])."','$row[editcount]','$row[allowsmilies]','$row[showsignature]','$row[ipaddress]','$row[visible]','1')");
  }
  
  $thread['replycount']+=1;
  $db->query("UPDATE bb".$n."_boards SET threadcount=threadcount+1, postcount=postcount+'$thread[replycount]' WHERE boardid IN ($newboardid,$newboard[parentlist])");
     
  if($newboard['lastposttime']<=$thread['lastposttime']) updateBoardInfo("$newboardid,$newboard[parentlist]",$thread['lastposttime']);
     
  if($newboard['countuserposts']==1) {
   $result = $db->query("SELECT COUNT(postid) AS posts, userid FROM bb".$n."_posts WHERE threadid='$newthreadid' AND visible = 1 AND userid>0 GROUP BY userid");
   while($row=$db->fetch_array($result)) $db->query("UPDATE bb".$n."_users SET userposts=userposts+'$row[posts]' WHERE userid='$row[userid]'");
  }
 }	
}

function decode_cookie($data) {
 $result=array();
 $data = explode(",",$data);
 for($i=0;$i<count($data)/2;$i++) $result[$data[($i*2)]]=$data[($i*2+1)];	
 return $result; 
}

function encode_cookie($name,$time=0,$is_visittime=true) {
 global $$name, $wbbuserdata;
 $newdata="";
 while(list($key,$val)=each($$name)) {
  if($is_visittime && $wbbuserdata['lastvisit']>=$val) continue;
  if($newdata) $newdata.=",$key,$val";
  else $newdata="$key,$val";
 }
 bbcookie($name,$newdata,$time);	
}

function isAdmin($mod=0) {
 global $wbbuserdata;
 
 if($mod==-1) {
  if(!$wbbuserdata['canuseacp'] && !$wbbuserdata['issupermod'] && !$wbbuserdata['ismod']) {
   eval("print(\"".gettemplate("access_error")."\");");	
   exit();
  }
 }
 elseif(!$wbbuserdata['canuseacp']) {
  eval("print(\"".gettemplate("access_error")."\");");
  exit();
 }
}

function refresh($url,$time=2) {
 eval("print(\"".gettemplate("refresh")."\");");	
}

function makeboardoptions($boardid, $depth=1, $selected=0, $selboardid=0) {
 global $boardcache;

 if(!isset($boardcache[$boardid])) return;
 
 while (list($key1,$val1) = each($boardcache[$boardid])) {
  while(list($key2,$boards) = each($val1)) {
   $out .= "<option value=\"".$boards[boardid]."\"".ifelse($selected==1 && $boards[boardid]==$selboardid," selected").">";
   if($depth>1) $out .= str_repeat("--",$depth-1)." ".$boards[title]."</option>";
   else $out .= $boards[title]."</option>";
   $out.=makeboardoptions($boards[boardid],$depth+1,$selected,$selboardid);
  } 
 } 
 unset($boardcache[$boardid]);
 return $out;
}

function makepostlist($postid,$current,$title,$depth=0) {
 global $tpl, $postcache, $wbbuserdata, $session, $threadview, $postvisit;

 if (!isset($postcache[$postid])) return;
 
 $postlist=""; 
 while (list($key,$posts)=each($postcache[$postid]) ) {
  $postdate=formatdate($wbbuserdata['dateformat'],$posts['posttime']);
  $posttime=formatdate($wbbuserdata['timeformat'],$posts['posttime']);
  $spacewidth=$depth*15;
  if($posts['userid']) $posts['username']=makehreftag("profile.php?sid=$session[hash]&userid=$posts[userid]",$posts['username']);
  if(!$posts['posttopic']) $posts['posttopic']="RE: ".$title; 
  
  if($wbbuserdata['lastvisit']<=$posts['posttime'] && $postvisit[$posts['postid']]!=1) eval ("\$postsign = \"".$tpl->get("thread_newpost")."\";");
  else eval ("\$postsign = \"".$tpl->get("thread_nonewpost")."\";");
 
  if($key==$current) eval ("\$postlist .= \"".$tpl->get("thread_postlistbit2")."\";");
  else eval ("\$postlist .= \"".$tpl->get("thread_postlistbit")."\";");
  $postlist.=makepostlist($key,$current,$posts['posttopic'],$depth+1);
 } 
 unset($postcache[$postid]);
 return $postlist;
}

function userlevel($userposts,$regdate) {
 global $tpl;
 
 $regdate = (time()-$regdate)/86400;
 $exp = ceil($userposts*$regdate);
 if($exp!=0) $level = pow(log10($exp),2);
 else $level = 0;
 $showlevel = floor($level) + 1;
 $nextexp = ceil(pow(10,pow($showlevel,0.5)));
 if($showlevel==1) $prevexp = 0;
 else $prevexp = ceil(pow(10,pow($showlevel-1,0.5)));

 $width=ceil((($exp-$prevexp)/($nextexp-$prevexp))*100);
 $needexp = number_format($nextexp-$exp,0,"",".");
 eval ("\$expbar = \"".$tpl->get("userlevel_expbar")."\";");
 
 $exp = number_format($exp,0,"",".");
 $nextexp = number_format($nextexp,0,"",".");
 
 eval ("\$userlevel = \"".$tpl->get("userlevel")."\";");
 return $userlevel;
}

function formatFilesize($byte) {
 $string = "Byte";
 if($byte>1024) {
  $byte/=1024;
  $string="KB";
 }
 if($byte>1024) {
  $byte/=1024;
  $string="MB";
 }
 if($byte>1024) {
  $byte/=1024;
  $string="GB";
 }	
	
 if(number_format($byte,0)!=$byte) $byte=number_format($byte,2);
 return $byte." ".$string;	
}

function getIpAddress() {
 $REMOTE_ADDR=getenv("REMOTE_ADDR");
 $HTTP_X_FORWARDED_FOR=getenv("HTTP_X_FORWARDED_FOR");
	
 if($HTTP_X_FORWARDED_FOR!="") {
  if(preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $HTTP_X_FORWARDED_FOR, $ip_match)) {
   $private_ip_list = array("/^0\./", "/^127\.0\.0\.1/", "/^192\.168\..*/", "/^172\.16\..*/", "/^10..*/", "/^224..*/", "/^240..*/");	
   $REMOTE_ADDR = preg_replace($private_ip_list, $REMOTE_ADDR, $ip_match[1]);	
  }	
 }
 
 if(strlen($REMOTE_ADDR)>16) $REMOTE_ADDR=substr($REMOTE_ADDR, 0, 16);
 return $REMOTE_ADDR;
}

function updateList($boardidlist,$update,$field="parentlist",$remove=false) {
 global $db, $n;
 
 $update = var2key(explode(",",$update));
 unset($update[0]);
 if(count($update)) {
  $result = $db->query("SELECT boardid, $field FROM bb".$n."_boards WHERE boardid IN ($boardidlist)");
  while($row=$db->fetch_array($result)) {
   $temp = var2key(explode(",",$row[$field]));
   
   reset($update);
   while(list($key,$val)=each($update)) {
    if($remove) unset($temp[$key]);
    else $temp[$key]=1;
   }
   
   $temp = implode(",",key2var($temp));
   
   $db->query("UPDATE bb".$n."_boards SET $field = '$temp' WHERE boardid='$row[boardid]'");
  }
 }
}

function var2key($varArray,$value=1) {
 $keyArray=array();
 
 reset($varArray);
 while(list($key,$val)=each($varArray)) $keyArray[$val]=$value;
 
 return $keyArray;
}

function key2var($keyArray) {
 $varArray=array();
 
 reset($keyArray);
 while(list($key,$val)=each($keyArray)) $varArray[]=$key;
 
 return $varArray;
}
?>
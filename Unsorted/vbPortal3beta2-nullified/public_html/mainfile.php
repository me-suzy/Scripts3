<?php
error_reporting(7);

/************************************************************************/
/* vbPortal: CMS mod for vBulletin                                      */
/* vBulletin is Copyright ©2000, 2001, Jelsoft Enterprises Limited.     */
/* ===========================                                          */
/* vbPortal by wajones                                                  */
/* Copyright (c) 2001 by William A. Jones                               */
/* http://www.phpportals.com                                            */
/* ===========================                                          */
/* Portions are based on PHP-NUKE: Web Portal System                    */
/* Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* The portions of this program so indicated as GNU are free software.  */
/* You can redistribute it and/or modify it under the terms of the GNU  */
/* General Public License as published by the Free Software Foundation; */
/* either version 2 of the License.                                     */
/************************************************************************/
$templatesused ='
P_activetopics_sidebox,P_activetopic_centerbox,P_activetopic_centerboxbit,P_banners,P_breadcrumb,P_calendar,P_error_nopermission_loggedout,P_ForumLeftColumn,P_home,P_logincode,P_logoutcode,P_newsbits,P_newspastbit,P_newsselecttopic,P_newsselecttopic_list,P_newthreadheader,P_polloption,P_pollresult,P_showarticle,P_showarticlebits,P_showarticle_commentbits,P_showarticle_commentbox,P_themecenterbox,P_themecentercolumn,P_themeheader,P_thememenu_downloads,P_thememenu_faq,P_thememenu_forum,P_thememenu_homepage,P_thememenu_undefined,P_thememenu_weblinks,P_themerightcolumn,P_themesidebox_left,P_themesidebox_right';


include("config.php");
$mainfile = 1;

include($nukepath . "/language/lang-$language.php");

chdir($vbpath . "/"); 
require($vbpath . "/global.php");
chdir($nukepath . "/");

global $pollsforum,$newsforum,$user,$email,$bbuserid,$activeusers,$enablepms,$session,$ipaddress,$scriptpath;
$d = @opendir('includes/');
while($f = @readdir($d)) {
 if(substr($f, -3, 3) == 'php') {
  include 'includes/' . $f;
 }
}
@closedir($d);

// vbPortal set replacement vars
function getvbpvars(){
global $categorybackcolor,$categoryfontcolor,$closedthreadimage,$firstaltcolor,$hovercolor,$imagesfolder,$linkcolor,$pagebgcolor,$pagetextcolor,$secondaltcolor,$tablebordercolor,$tableheadbgcolor,$tableheadtextcolor,$bgcolor1,$bgcolor2,$bgcolor3,$bgcolor4,$vbpvars,$DB_site,$replacementsetid,$styleid;

 static $rvars, $vars;
  $vars=mysql_query("SELECT findword,replaceword FROM replacement WHERE replacementsetid IN($styleid,'$replacementsetid') ORDER BY replacementsetid DESC,replacementid DESC");
  while ($var=mysql_fetch_array($vars)) {
        $rvar = $var['findword'];
        				
		if ($rvar=='{categorybackcolor}'){
			$categorybackcolor= $var['replaceword']; 
            $bgcolor2 = $var['replaceword']; 

		}elseif ($rvar=='{categoryfontcolor}'){
			$categoryfontcolor= $var['replaceword'];
        
		}elseif ($rvar=='{closedthreadimage}'){
			$closedthreadimage= $var['replaceword'];

		}elseif ($rvar=='{firstaltcolor}'){
			$firstaltcolor= $var['replaceword'];
            $bgcolor3 = $var['replaceword']; 
            $bgcolor4 = $var['replaceword']; 

		}elseif ($rvar=='{hovercolor}'){
			$hovercolor= $var['replaceword'];

		}elseif ($rvar=='{imagesfolder}'){
			$imagesfolder= $var['replaceword'];

		}elseif ($rvar=='{linkcolor}'){
			$linkcolor= $var['replaceword'];

		}elseif ($rvar=='{pagebgcolor}'){
			$pagebgcolor= $var['replaceword'];

		}elseif ($rvar=='{pagetextcolor}'){
			$pagetextcolor= $var['replaceword'];

		}elseif ($rvar=='{secondaltcolor}'){
			$secondaltcolor= $var['replaceword'];
            $bgcolor1 = $var['replaceword']; 

		}elseif ($rvar=='{tablebordercolor}'){
			$tablebordercolor= $var['replaceword'];

		}elseif ($rvar=='{tableheadbgcolor}'){
			$tableheadbgcolor= $var['replaceword'];

		}elseif ($rvar=='{tableheadtextcolor}'){
			$tableheadtextcolor= $var['replaceword'];

        }
	unset($rvar);
 }
}

function OpenTable() {
    global $tablebordercolor,$secondaltcolor;
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"$tablebordercolor\"><tr><td>\n";
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"8\" bgcolor=\"$secondaltcolor\"><tr><td>\n";
}


function CloseTable() {
    echo "</td></tr></table></td></tr></table>\n";
}

function OpenTable2() {
    global $bgcolor1, $bgcolor2;
    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>\n";
    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"8\" bgcolor=\"$bgcolor1\"><tr><td>\n";
}

function CloseTable2() {
    echo "</td></tr></table></td></tr></table>\n";
}

$permissions=getpermissions();
if (!$permissions['canview']) {
	show_nopermission();
}


function is_admin($admin) {
  global $bbuserinfo;
  if ($bbuserinfo['usergroupid']!=6) {
    return 0;
  }
    return 1;
}

function is_user($user) {
global $bbuserinfo,$user;
if ($bbuserinfo['userid']!=0) {
     $bbuserid = $bbuserinfo['userid'];
     $username=$bbuserinfo['username'];
	 $user = base64_encode("$bbuserid:$username");
     return 1;
  } else {
    $user="";
    return 0;
  }
}


function cookiedecode($user) {
    global $info,$bbuserinfo,$cookie, $prefix;
    $bbuserid = $bbuserinfo['userid'];
    $username=$bbuserinfo['username'];
    $info = base64_encode("$bbuserid:$username");
    $user = base64_decode($info);
    $cookie = explode(":", $user);
    return $cookie;
 }


function getusrinfo($user) {
    global $userinfo, $prefix;
    $user2 = base64_decode($user);
    $user3 = explode(":", $user2);
	$result = mysql_query("select userid as uid, username as name, username as uname, email, email as femail, homepage as url, password as pass from user where username='$user3[1]' and password='$user3[2]'");
    if(mysql_num_rows($result)==1) {
    	$userinfo = mysql_fetch_array($result);
    } else {
        echo "<b>"._MPROBLEM."</b><br>";
    }
    return $userinfo;
}



function RandomPic() {
	global $user, $prefix,$block_sidetemplate;
	
	include 'admin/modules/gallery/config.php';
	mt_srand((double)microtime()*1000000);
	if (is_user($user))
		$total = mysql_fetch_array(mysql_query("SELECT COUNT(p.pid) AS total FROM $prefix"._gallery_pictures." AS p LEFT JOIN $prefix"._gallery_categories." AS c ON c.gallid=p.gid WHERE (extension='jpg' OR extension='gif' OR extension='png') AND c.visible>=1"));
	else 
		$total = mysql_fetch_array(mysql_query("SELECT COUNT(p.pid) AS total FROM $prefix"._gallery_pictures." AS p LEFT JOIN $prefix"._gallery_categories." AS c ON c.gallid=p.gid WHERE (extension='jpg' OR extension='gif' OR extension='png') AND c.visible>=2"));
	
	if ($total[total]==0)
		$p=0;
	else
	 $p = mt_rand(0,($total[total] - 1));

	if (is_user($user))
		$pic = mysql_fetch_array(mysql_query("SELECT p.pid, p.img, p.name, p.description, c.galloc FROM $prefix"._gallery_pictures." AS p LEFT JOIN $prefix"._gallery_categories." AS c ON c.gallid=p.gid WHERE (extension='jpg' OR extension='gif' OR extension='png') AND c.visible>=1 LIMIT $p,1"));
	else
		$pic = mysql_fetch_array(mysql_query("SELECT p.pid, p.img, p.name, p.description, c.galloc FROM $prefix"._gallery_pictures." AS p LEFT JOIN $prefix"._gallery_categories." AS c ON c.gallid=p.gid WHERE (extension='jpg' OR extension='gif' OR extension='png') AND c.visible>=2 LIMIT $p,1"));
	
	$pic[description] = htmlentities($pic[description]);
	
	if (file_exists("$gallerypath/".$pic[galloc]."/thumb/".$pic[img])) 
		$content = "<center><a href=\"$baseurl&amp;do=showpic&amp;pid=$pic[pid]\"><img src=\"$gallerypath/$pic[galloc]/thumb/$pic[img]\" border=\"0\" alt=\"$pic[description]\"><br><font size=\"1\">$pic[name]</font></a></center>";
	else 
		$content = "<center><a href=\"$baseurl&amp;do=showpic&amp;pid=$pic[pid]\"><img src=\"$gallerypath/$pic[galloc]/$pic[img]\" width=\"70\" border=\"0\" alt=\"$pic[description]\"><br><font size=\"1\">$pic[name]</font></a></center>";
	$title = mysql_fetch_array(mysql_query("select title from $prefix"._advblocks." where bkey='randompic'"));
	$nuke_title = $title[title];
    $nuke_content = $content;
    eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");

}


function getTopics($s_sid) {
    global $topicname, $topicimage, $topictext, $prefix;
    $sid = $s_sid;
    $result=mysql_query("SELECT topic FROM $prefix"._stories." where sid=$sid");
    list($topic) = mysql_fetch_row($result);
    $result=mysql_query("SELECT topicid, topicname, topicimage, topictext FROM $prefix"._topics." where topicid=$topic");
    list($topicid, $topicname, $topicimage, $topictext) = mysql_fetch_row($result);
}


function filter_text($Message, $strip="") {
    global $EditedMessage;
    check_words($Message);
    $EditedMessage=check_html($EditedMessage, $strip);
    return ($EditedMessage);
}

function check_words($Message) {
    global $EditedMessage;
    include("config.php");
    $EditedMessage = $Message;
    if ($CensorMode != 0) {
	
	if (is_array($CensorList)) {
	    $Replace = $CensorReplace;
	    if ($CensorMode == 1) {
		for ($i = 0; $i < count($CensorList); $i++) {
		    $EditedMessage = eregi_replace("$CensorList[$i]([^a-zA-Z0-9])","$Replace\\1",$EditedMessage);
		}
	    } elseif ($CensorMode == 2) {
		for ($i = 0; $i < count($CensorList); $i++) {
		    $EditedMessage = eregi_replace("(^|[^[:alnum:]])$CensorList[$i]","\\1$Replace",$EditedMessage);
		}
	    } elseif ($CensorMode == 3) {
		for ($i = 0; $i < count($CensorList); $i++) {				
		    $EditedMessage = eregi_replace("$CensorList[$i]","$Replace",$EditedMessage);
		}
	    }
	}
    }
    return ($EditedMessage);
}

function FixQuotes ($what = "") {
	$what = ereg_replace("'","''",$what);
	while (eregi("\\\\'", $what)) {
		$what = ereg_replace("\\\\'","'",$what);
	}
	return $what;
}

function formatTimestamp($time) {
    global $datetime, $locale;
    setlocale ("LC_TIME", "$locale");
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    $datetime = strftime(""._DATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
    $datetime = ucfirst($datetime);
    return($datetime);
}





function delQuotes($string){ 
    /* no recursive function to add quote to an HTML tag if needed */
    /* and delete duplicate spaces between attribs. */
    $tmp="";    # string buffer
    $result=""; # result string
    $i=0;
    $attrib=-1; # Are us in an HTML attrib ?   -1: no attrib   0: name of the attrib   1: value of the atrib
    $quote=0;   # Is a string quote delimited opened ? 0=no, 1=yes
    $len = strlen($string);
    while ($i<$len) {
	switch($string[$i]) { # What car is it in the buffer ?
	    case "\"": #"       # a quote. 
		if ($quote==0) {
		    $quote=1;
		} else {
		    $quote=0;
		    if (($attrib>0) && ($tmp != "")) { $result .= "=\"$tmp\""; }
		    $tmp="";
		    $attrib=-1;
		}
		break;
	    case "=":           # an equal - attrib delimiter
		if ($quote==0) {  # Is it found in a string ?
		    $attrib=1;
		    if ($tmp!="") $result.=" $tmp";
		    $tmp="";
		} else $tmp .= '=';
		break;
	    case " ":           # a blank ?
		if ($attrib>0) {  # add it to the string, if one opened.
		    $tmp .= $string[$i];
		}
		break;
	    default:            # Other
		if ($attrib<0)    # If we weren't in an attrib, set attrib to 0
		$attrib=0;
		$tmp .= $string[$i];
		break;
	}
	$i++;
    }
    if (($quote!=0) && ($tmp != "")) {
	if ($attrib==1) $result .= "="; 
	/* If it is the value of an atrib, add the '=' */
	$result .= "\"$tmp\"";  /* Add quote if needed (the reason of the function ;-) */
    }
    return $result;
}

function check_html ($str, $strip="") {
    /* The core of this code has been lifted from phpslash */
    /* which is licenced under the GPL. */
    include("config.php");
    if ($strip == "nohtml")
    	$AllowableHTML=array('');
	$str = stripslashes($str);
	$str = eregi_replace("<[[:space:]]*([^>]*)[[:space:]]*>",
                         '<\\1>', $str);
               // Delete all spaces from html tags .
	$str = eregi_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?[[:space:]]*([^\" >]*)[[:space:]]*\"?[^>]*>",
                         '<a href="\\1">', $str); # "
               // Delete all attribs from Anchor, except an href, double quoted.
	$str = eregi_replace("<img?",
                         '', $str); # "
	$tmp = "";
	while (ereg("<(/?[[:alpha:]]*)[[:space:]]*([^>]*)>",$str,$reg)) {
		$i = strpos($str,$reg[0]);
		$l = strlen($reg[0]);
		if ($reg[1][0] == "/") $tag = strtolower(substr($reg[1],1));
		else $tag = strtolower($reg[1]);  
		if ($a = $AllowableHTML[$tag])
			if ($reg[1][0] == "/") $tag = "</$tag>";
			elseif (($a == 1) || ($reg[2] == "")) $tag = "<$tag>";
			else {
			  # Place here the double quote fix function.
			  $attrb_list=delQuotes($reg[2]);
			  $tag = "<$tag" . $attrb_list . ">";
			} # Attribs in tag allowed
		else $tag = "";
		$tmp .= substr($str,0,$i) . $tag;
		$str = substr($str,$i+$l);
	}
	$str = $tmp . $str;
	return $str;
	exit;
	/* Squash PHP tags unconditionally */
	$str = ereg_replace("<\?","",$str);
	return $str;
}

function themesidebox($title, $content) {
getvbpvars();
echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"150\">
<tr>
	<td bgcolor=\"$tablebordercolor\" >
<table width=\"150\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
<tr>
	<td valign=\"top\" bgcolor=\"$categorybackcolor\">&nbsp;<font color=\"$categoryfontcolor\"><b>$block_title</b></font>
	</td>
</tr>
<tr>
	<td bgcolor=\"$firstaltcolor\"><smallfont>$block_content</smallfont>
	</td>
</tr>
</table>
	</td>
</tr>
</table>
<br>";
}

?>
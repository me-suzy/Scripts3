<?php

/*======================================================================*\
|| #################################################################### ||
||  Program Name         : VirtuaNews Pro                                 
||  Release Version      : 1.0.3                                          
||  Program Author       : VirtuaSystems                                  
||  Supplied by          : Ravish                                         
||  Nullified by         : WTN Team                                       
||  Distribution         : via WebForum, ForumRU and associated file dumps
|| #################################################################### ||
\*======================================================================*/

function autoparseurl ($string) {

  $searcharray[] = "/(\s)(((https?||ftp):\/\/|www\.)\w+[^\s\[\]]+)/i";
  $searcharray[] = "/^(((https?|ftp):\/\/|www\.)\w+[^\s\[\]]+)/i";

  $replacearray[] = "\\1[url]\\2[/url]";
  $replacearray[] = "[url]\\1[/url]";

  $string = preg_replace($searcharray,$replacearray,$string);

  return $string;
}

function checkipban() {

  global $ipaddress,$banhost,$banip,$themeid,$pagesetid,$stylevar,$defaultcat_loggedout,$cat_arr,$theme_arr;

  if ($banip != "") {

    $addresses = explode(' ', trim($banip));
    foreach ($addresses AS $val) {

      $val = trim($val);

      if (strpos($val, '*') !== false) {

        $check = explode('.', $val);

        if (!isset($iparray)) {
          $iparray = explode('.', $ipaddress);
        }

        $match = 0;

        foreach ($check AS $key => $val) {
          if (($val == $iparray[$key]) || ($val == '*')) {
            $match ++;
          }
        }

        if ($match == 4) {
          $themeid = $cat_arr[$defaultcat_loggedout][defaulttheme];
          $pagesetid = $theme_arr[$themeid][pagesetid];

          $stylevar = getstylevars($theme_arr[$themeid][stylesetid]);

          standarderror("ip_banned");
        }

      } elseif ($ipaddress == $val) {

        $themeid = $cat_arr[$defaultcat_loggedout][defaulttheme];
        $pagesetid = $theme_arr[$themeid][pagesetid];

        $stylevar = getstylevars($theme_arr[$themeid][stylesetid]);

	standarderror("ip_banned");
      }
    }
  }

  if ($banhost != "") {

    $hostnamelist = explode(" ",preg_replace("/[[:space:]]+/"," ",trim($banhost)));
    $hostname = gethostbyaddr($ipaddress);

    while (list($key,$val) = each($hostnamelist)) {
      if (preg_match("/$val/i",$hostname)) {

        $themeid = $cat_arr[$defaultcat_loggedout][defaulttheme];
        $pagesetid = $theme_arr[$themeid][pagesetid];

        $stylevar = getstylevars($theme_arr[$themeid][stylesetid]);

	standarderror("ip_banned");
      }
    }
  }
  return true;
}

function checknewsprogram() {

  $getdata = query("SELECT id,catid,time FROM news_news WHERE program = 1");
  while ($data_arr = fetch_array($getdata)) {
    if ($data_arr['time'] < time()) {
      $newsids .= iif($newsids,",'$data_arr[id]'","'$data_arr[id]'");
    }
  }

  if ($newsids) {
    query("UPDATE news_news SET program = '0' WHERE id IN($newsids)");
    include("includes/adminfunctions.php");
    include("includes/writefunctions.php");
    writeallpages();
  }
  unset($newsids);
}

function checkurl($url,$text="") {

  $newurl = $url;

  if(!preg_match("![a-z]://!si", $url)) {
    $newurl = "http://$newurl";
  }

  $newurl = str_replace("&","&amp;",$newurl);
  $newurl = str_replace("&amp;amp;","&amp;",$newurl);

  $text = iif((trim($text) == "") | ($text == $url),iif(strlen($url)>55,substr($url,0,35)."...".substr($url,-15),$url),$text);

  return "<a href=\"$newurl\" target=\"_blank\">".str_replace('\"','"',$text)."</a>";
}

function codetagparse($string,$allowhtml,$donewline) {

  $string = str_replace("\r\n","\n",trim($string));

  if ($donewline) {
    $string = str_replace("<br />\n","\n",$string);
  }

  if ($allowhtml) {
    $string = str_replace("&lt;","&amp;lt;",$string);
    $string = str_replace("&gt;","&amp;gt;",$string);
    $string = str_replace("<","&lt;",$string);
    $string = str_replace(">","&gt;",$string);
    $string = str_replace("\\\"","&quot;",$string);
  }

  $string = str_replace("\\\"","\"",$string);

  $string = "<blockquote><b>Code:</b><hr /><pre>$string</pre><hr /></blockquote>";

  return $string;
}

function countchar($string,$char) {

  $charpos = strstr($string,$char);

  if ($charpos != "") {
    return countchar(substr($charpos,strlen($char)),$char)+1;
  } else {
    return 0;
  }
}

function echooutput($page) {

  global $gzipoutput,$gziplevel,$showqueries,$pagestarttime,$db_query_count,$themeid,$db_query_time,$stylevar;
  static $replace_data;

  if ($gzipoutput) {
    $page = gzipoutput($page,$gziplevel);
    header("Content-Length: ".strlen($page));
  }

  if ($showqueries) {

    $output = $page;

    $pageendtime = microtime();

    $starttime = explode(" ",$pagestarttime);
    $endtime = explode(" ",$pageendtime);

    $totaltime = $endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];

    echo "Page generated in $totaltime seconds with $db_query_count queries<br>".round((($db_query_time/$totaltime)*100),2)."% of the time doing MySQL things and ".round(((($totaltime-$db_query_time)/$totaltime)*100),2)."% doing PHP things";
    flush();
    exit;
  } else {
    echo $page;
    flush();
    exit;
  }

}

function emailusers($newsid,$postername) {

  global $webmasteremail,$enableemail,$homeurl,$sitename,$userid,$posttime,$insertid,$foruminfo,$cat_arr;

  $lastcommentid = $insertid;
  $postername = stripslashes($postername);

  if ($enableemail) {
    $getdata = query("
      SELECT
      news_subscribe.id AS subscribeid,
      ".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid,
      ".$foruminfo[user_table].".".$foruminfo[email_field]." AS email,
      ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
      news_news.title,
      news_news.catid
      FROM news_subscribe
      LEFT JOIN $foruminfo[user_table] ON news_subscribe.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
      LEFT JOIN news_news ON news_news.id = $newsid
      WHERE (news_subscribe.newsid = $newsid)
      AND (news_subscribe.emailupdate = 1)
      AND (".$foruminfo[user_table].".".$foruminfo[userid_field]." <> $userid)
      AND (news_subscribe.lastview < $posttime)
    ");

    while ($data_arr = fetch_array($getdata)) {
      $subscribeids .= iif($subscribeids,",'$data_arr[subscribeid]'","'$data_arr[subscribeid]'");

      $newstitle = $data_arr[title];
      $newscatid = $data_arr[catid];
      $newscategory = $cat_arr[$newscatid][name];
      $emailtoname = $data_arr[username];
      eval("\$email_subject = \"".returnpagebit("comments_emailnotify_subject")."\";");
      eval("\$email_msg = \"".returnpagebit("comments_emailnotify_msg")."\";");

      @mail($data_arr[email],$email_subject,$email_msg,"From: \"$sitename Mailer\" <$webmasteremail>");
    }

    if ($subscribeids) {
      query("UPDATE news_subscribe SET emailupdate = '0' WHERE id IN ($subscribeids)");
    }
  } else {
    return false;
  }
}

function getbrowser() {

  global $useragent;

  if ((ereg("Nav",$useragent) | ereg("Gold",$useragent) | ereg("X11",$useragent) | ereg("Mozilla",$useragent) | ereg("Netscape",$useragent)) & (!ereg("MSIE",$useragent) & !ereg("Konqueror",$useragent))) {
    $browser = "Netscape";
  } elseif (ereg("Opera",$useragent)) {
    $browser = "Opera";
  } elseif (ereg("MSIE",$useragent)) {
    $browser = "MSIE";
  } elseif (ereg("Lynx",$useragent)) {
    $browser = "Lynx";
  } elseif (ereg("WebTV",$useragent)) {
    $browser = "WebTV";
  } elseif (ereg("Konqueror",$useragent)) {
    $browser = "Konqueror";
  } elseif (eregi("bot",$useragent) | ereg("Google",$useragent) | ereg("Slurp",$useragent) | ereg("Scooter",$useragent) | eregi("Spider",$useragent) | eregi("Infoseek",$useragent)) {
    $browser = "Bot";
  } else {
    $browser = "Other";
  }
  return $browser;
}

function getcat_arr() {

  $cat_arr = array();

  $getdata = query("SELECT * FROM news_category ORDER BY displayorder");

  while ($data_arr = fetch_array($getdata)) {

    $catid = $data_arr[id];

    $cat_arr[$catid] = array();
    $cat_arr[$catid][parentid] = $data_arr[parentid];
    $cat_arr[$catid][children] = $data_arr[children];
    $cat_arr[$catid][name] = $data_arr[name];
    $cat_arr[$catid][description] = $data_arr[description];
    $cat_arr[$catid][image] = $data_arr[image];
    $cat_arr[$catid][posts] = $data_arr[posts];
    $cat_arr[$catid][allowcomments] = $data_arr[allowcomments];
    $cat_arr[$catid][allowview] = $data_arr[allowview];
    $cat_arr[$catid][showsitestats] = $data_arr[showsitestats];
    $cat_arr[$catid][showforumstats] = $data_arr[showforumstats];
    $cat_arr[$catid][showforumoptions] = $data_arr[showforumoptions];
    $cat_arr[$catid][showpoll] = $data_arr[showpoll];
    $cat_arr[$catid][showannouncement] = $data_arr[showannouncement];
    $cat_arr[$catid][showsubcats] = $data_arr[showsubcats];
    $cat_arr[$catid][defaulttheme] = $data_arr[defaulttheme];
    $cat_arr[$catid][forcetheme] = $data_arr[forcetheme];
    $cat_arr[$catid][recentpost] = $data_arr[recentpost];
    $cat_arr[$catid][display] = $data_arr[display];
    $cat_arr[$catid][displayorder] = $data_arr[displayorder];
    $cat_arr[$catid][displaymain] = $data_arr[displaymain];
    $cat_arr[$catid][pollid] = $data_arr[pollid];
  }

  foreach ($cat_arr AS $key => $val) {

    if ($val[parentid] == 0) {
      $cat_arr[$key][topid] = $key;
    } elseif ($cat_arr[$val[parentid]][parentid] == 0) {
      $cat_arr[$key][topid] = $val[parentid];
    } else {
      $cat_arr[$key][topid] = $cat_arr[$val[parentid]][parentid];
    }
  }

  return $cat_arr;
}

function getcat_nav() {

  global $cat_arr,$catid,$location;

  if (!empty($cat_arr)) {
    foreach ($cat_arr as $key => $cat) {
      $cat[id] = $key;
      $cat_nav .= returncatnav($cat,0,1);
    }
  }

  return $cat_nav;

}

function getmodoptions($modid="",$modname="",$options="") {

  if (!$options) {
    if ($modid) {
      $data_arr = query_first("SELECT options FROM news_module WHERE id = $modid");
    } else {
      if ($data_arr = query_first("SELECT options FROM news_module WHERE name = '$modname'")) {
      } else {
	standarderror("invalid_id");
      }
    }
    $options = $data_arr[options];
  }

  $options = explode("||||||",$options);
  foreach ($options AS $temp) {
    $option_temp = explode("/\\/\\/\\",$temp);
    $GLOBALS[$option_temp[0]] = $option_temp[1];
  }

  unset($data_arr);
}

function getos() {

  global $useragent;

  if (ereg("Win",$useragent)) {
    $os = "Windows";
  } elseif (ereg("Mac",$useragent) | ereg("PPC",$useragent)) {
    $os = "Mac";
  } elseif (ereg("Linux",$useragent)) {
    $os = "Linux";
  } elseif (ereg("SunOS",$useragent)) {
    $os = "SunOS";
  } elseif (ereg("IRIX",$useragent)) {
    $os = "IRIX";
  } elseif (ereg("BeOS",$useragent)) {
    $os = "BeOS";
  } elseif (eregi("OS/2",$useragent)) {
    $os = "OS/2";
  } else {
    $os = "Other";
  }
  return $os;
}

function getsmiliedata() {
  global $foruminfo;
  static $smilie_data;
  if ($smilie_data) {
    data_seek($smilie_data,0);
  } else {
    $smilie_data = query("SELECT ".$foruminfo[smilietitle_field]." AS title,".$foruminfo[smilietext_field]." AS smilietext,".$foruminfo[smiliepath_field]." AS smiliepath FROM ".$foruminfo[smilie_table]);
  }
  return $smilie_data;
}

function getsmilietable() {

  global $forumpath,$foruminfo,$stylevar;

  $smilie_data = getsmiliedata();
  $totalsmilies = countrows($smilie_data);

  while ($smilie_arr = fetch_array($smilie_data)) {

    $smiliecount ++;

    $smilietext = $smilie_arr[smilietext];
    $smiliepath = $foruminfo[smilie_image_path].$smilie_arr[smiliepath];

    if ($smiliecount%6 == 1) {
      unset($smilie_vbcodetext);
    }

    eval("\$smilie_vbcodetext .= \"".returnpagebit("comments_smilies_smiliebit")."\";");

    if (($smiliecount%6 == 0) | ($smiliecount == $totalsmilies)) {
      eval("\$smilie_vbcode .= \"".returnpagebit("comments_smilies_row")."\";");
    }
  }

  eval("\$code = \"".returnpagebit("comments_smilies_table")."\";");

  return $code;

}

function getstylevars($styleset) {

  $getdata = query("SELECT varname,value FROM news_style WHERE stylesetid IN ('-1','$styleset') ORDER BY stylesetid");

  while($data_arr = fetch_array($getdata)) {
    if ($data_arr[varname] != "") {
      $stylevar[$data_arr[varname]] = $data_arr[value];
    }
  }

  return $stylevar;
}

function gettextareawidth () {
  global $browser,$msietextarea,$nettextarea;

  if ($browser == "MSIE") {
    return $msietextarea;
  } elseif ($browser == "Netscape") {
    return $nettextarea;
  } else {
    return 80;
  }
}

function getthemearr() {

  $getthemes = query("SELECT id,pagesetid,stylesetid,title,allowselect FROM news_theme ORDER BY title");
  while ($temp_arr = fetch_array($getthemes)) {
    $theme_arr[$temp_arr[id]] = array();
    $theme_arr[$temp_arr[id]][title] = $temp_arr[title];
    $theme_arr[$temp_arr[id]][stylesetid] = $temp_arr[stylesetid];
    $theme_arr[$temp_arr[id]][pagesetid] = $temp_arr[pagesetid];
    $theme_arr[$temp_arr[id]][allowselect] = $temp_arr[allowselect];
  }
  return $theme_arr;
}

function gzipoutput($text,$compresslevel=1){
  global $HTTP_ACCEPT_ENCODING;

  $newtext = $text;

  if (function_exists("crc32") & function_exists("gzcompress")) { // Only gzip if server supports it

    if (strpos(" ".$HTTP_ACCEPT_ENCODING,"x-gzip")) {
      $encoding = "x-gzip";
    } elseif (strpos(" ".$HTTP_ACCEPT_ENCODING,"gzip")) {
      $encoding = "gzip";
    }

    if ($encoding) { // Only gzip if browser supports it

      header("Content-Encoding: $encoding");

      $size = strlen($text);
      $crc = crc32($text);

      $newtext = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
      $newtext .= substr(gzcompress($text,$compresslevel),0,-4);
      $newtext .= pack("V",$crc);
      $newtext .= pack("V",$size);
    }
  }
  return $newtext;
}

function header_redirect($path,$text="") {

  global $useredirects;

  if (!@header("location:$path")) {
    // If header fails then will use meta redirect
    if ($useredirects) {
      standardredirect("You are being redirected to $text",$path,0);
    } else { // This bit should only be seen for a split second and rarely be needed, therefore no template is needed
      echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-scrict.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\" dir=\"ltr\">
<head>
  <title>$sitename</title>
  <meta http-equiv=\"content-type\" content=\"text/html; charset=windows-urf-8\" />
  <noscript>
    <meta http-equiv=\"refresh\" content=\"0; url=$path\" />
  </noscript>
</head>

<body onload=\"window.location.replace('$path')\">
  <div><a href=\"$path\">$text</a></div>
</body>
</html>";
    }
  }
  exit;
}

function htmltagparse($string,$allowhtml,$donewline) {

  $string = str_replace("\r\n","\n",$string);

  if ($donewline) {
    $string = str_replace("<br />\n","\n",$string);
  }

  if ($allowhtml) {
    $string = str_replace("&lt;","&amp;lt;",$string);
    $string = str_replace("&gt;","&amp;gt;",$string);
    $string = str_replace("<","&lt;",$string);
    $string = str_replace(">","&gt;",$string);
    $string = str_replace("\\\"","&quot;",$string);
  }

  $string = str_replace("\\\"","\"",$string);

  $string = preg_replace("#(\s)(\S+)=(&quot;|')(.*)(&quot;|')(\s|&gt;|/)#iU","\\1<span style=\"color:red\">\\2=</span><span style=\"color:black\">\\3\\4\\5</span>\\6",$string);
  $string = preg_replace("#(\s)(\S+)=(&quot;|')(.*)(&quot;|')(\s|&gt;|/)#iU","\\1<span style=\"color:red\">\\2=</span><span style=\"color:black\">\\3\\4\\5</span>\\6",$string);

  $string = preg_replace("/&lt;(.*)&gt;/siU","<span style=\"color:blue\">&lt;\\1&gt;</span>",$string);

  $string = preg_replace("/&gt;<\/span>(.+)<span style=\"color:blue\">&lt;/siU","&gt;</span><span style=\"color:black\">\\1</span><span style=\"color:blue\">&lt;",$string);
  $string = preg_replace("/&gt;<\/span><span style=\"color:black\">((\s|\n)*)<\/span><span style=\"color:blue\">&lt;/siU","&gt;</span>\\1<span style=\"color:blue\">&lt;",$string);

  $string = preg_replace("/&lt;!--(.*)<span style=\"color:(blue|red|black)\">(.*)--&gt;/siU","&lt;!--\\1\\3--&gt;",$string);
  $string = preg_replace("/&lt;!--(.*)<\/span>(.*)--&gt;/siU","&lt;!--\\1\\2--&gt;",$string);
  $string = preg_replace("/&lt;!--(.*)--&gt;/siU","<span style=\"color:green\">&lt;!--\\1--&gt;</span>",$string);

  $string = stripslashes($string);

  $string = "<blockquote><b>HTML:</b><hr /><pre>$string</pre><hr /></blockquote>";

  return $string;
}

function iif($expression,$returntrue,$returnfalse="") {

  if ($expression) {
    return $returntrue;
  } else {
    return $returnfalse;
  }

}

function isuserallowed($data=0) {

  global $loggedin,$staffid;

  if ($data == 3) {
    return true;
  } elseif (($data == 2) & $loggedin) {
    return true;
  } elseif (($data == 1) & $staffid) {
    return true;
  } else {
    return false;
  }
}

function makelists($listtext) {
  $listtext = str_replace("[*]","<li>", $listtext);
  $listtext = str_replace("[/*]","</li>\n", $listtext);
  return "<ul>".stripslashes($listtext)."</ul>\n";
}

function makenav($nav_title,$nav_url="") {

  if ($nav_url) {
    eval("\$code .= \"".returnpagebit("misc_nav_link_on")."\";");
  } else {
    eval("\$code .= \"".returnpagebit("misc_nav_link_off")."\";");
  }

  return $code;
}

function makenavbar($title,$sub_title="",$sub_url="") {
  global $defaultcategory,$sitename,$cat_arr,$stylevar;

  if ($sub_title) {
    $navbits = makenav($sub_title,$sub_url);
    eval("\$navbits .= \"".returnpagebit("misc_nav_joiner")."\";");
  }

  $navbits .= makenav($title);

  eval("\$code .= \"".returnpagebit("misc_nav_bar")."\";");

  return $code;
}

function makesitejump($catid) {

  global $showsitejump,$sitejump_shownews,$sitejump_newsposts,$cat_arr,$stylevar;

  if ($showsitejump) {

    if (!empty($cat_arr)) {
      foreach ($cat_arr as $key => $cat) {
        $cat[id] = $key;
        $cat_jump .= returnsitejumplink($cat,0,1);
      }
    }

    if ($sitejump_shownews) {
      $getnews = query("SELECT id,catid,title FROM news_news WHERE (catid = $catid) AND (display = 1) AND (program = 0) ORDER BY time DESC LIMIT $sitejump_newsposts");

      while ($news = fetch_array($getnews)) {
        eval("\$news_jump .= \"".returnpagebit("misc_sitejump_news")."\";");
      }
    }

    eval("\$jumpcode = \"".returnpagebit("misc_sitejump_main")."\";");

  }

  return $jumpcode;

}

function pagenav($perpage,$pagenum,$url,$total) {

  $numpages = $total/$perpage;
  $numpages = ceil($numpages);

  if ($numpages <= 1) {
    $pagenav = "";
  } else {

    $pagenav .= "Pages ($numpages): ";

    if ($pagenum > 3) {
      $currpage = $pagenum - 2;
      eval("\$first_link .= \"".returnpagebit("misc_page_nav_first_link")."\";");
    } else {
      $currpage = 1;
    }

    if ($pagenum <= ($numpages -3)) {
      if ($pagenum == 1) {
	$upperlimit = $currpage + 2;
      } elseif ($pagenum == 2) {
	$upperlimit = $currpage +3;
      } else {
	$upperlimit = $currpage + 4;
      }
    } else {
      $upperlimit = $numpages;
    }

    while (($currpage <= $numpages) & ($currpage <= $upperlimit)) {

      if ($currpage == $pagenum) {
	eval("\$curr_page .= \"".returnpagebit("misc_page_nav_curr_page")."\";");
      } else {
	if ($currpage < $pagenum) {
	  $prevpage = $currpage;
	  eval("\$prev_link .= \"".returnpagebit("misc_page_nav_prev_link")."\";");
	} else {
	  $nextpage = $currpage;
	  eval("\$next_link .= \"".returnpagebit("misc_page_nav_next_link")."\";");
	}
      }
      $currpage ++;
    }
    if ($upperlimit != $numpages) {
      eval("\$last_link = \"".returnpagebit("misc_page_nav_last_link")."\";");
    }

    eval("\$pagenav = \"".returnpagebit("misc_page_nav_main")."\";");
  }
  return $pagenav;
}

function phptagparse($string,$allowhtml,$donewline) {

  $string = str_replace("\r\n","\n",trim($string));

  if ($donewline) {
    $string = str_replace("<br />\n","\n",$string);
  }

  if (!$allowhtml) {
    $string = str_replace("&amp;lt;","&lt;",$string);
    $string = str_replace("&amp;gt;","&gt;",$string);
    $string = str_replace("&lt;","<",$string);
    $string = str_replace("&gt;",">",$string);
    $string = str_replace("&quot;","\"",$string);
  }

  $string = str_replace("\\\"","\"",$string);

  if (!strpos($string,"<?") & (substr($string,0,2) != "<?")) {
    $string = "<?$string?>";
    $addedtags=1;
  }

  ob_start();

  highlight_string($string);

  $buffer = ob_get_contents();
  ob_end_clean();

  $buffer = preg_replace("/^<code>/","",$buffer);
  $buffer = preg_replace("/<\/code>$/","",$buffer);

  if ($addedtags) {
    $startpos = strpos($buffer,"&lt;?");
    $closepos = strrpos($buffer,"?");
    $buffer = substr($buffer,0,$startpos).substr($buffer,$startpos+5,$closepos-($startpos+5)).substr($buffer,$closepos+5);
  }
  $buffer = str_replace("<br />","\n",$buffer);
  $buffer = str_replace("&nbsp;"," ",$buffer);

  return "<blockquote><b>PHP:</b><hr /><pre>$buffer</pre><hr /></blockquote>";
}

function qhtmlparse($string,$allowhtml=0,$doimg=1,$dosmilies=1,$doformatting=1,$donewline=1) {

  global $highlight,$forumpath,$censorwords,$foruminfo,$stylevar;
  static $searcharray,$replacearray;

  if (!$allowhtml) {
    $string = str_replace("&lt;","&amp;lt;",$string);
    $string = str_replace("&gt;","&amp;gt;",$string);
    $string = str_replace("<","&lt;",$string);
    $string = str_replace(">","&gt;",$string);
    $string = str_replace("\\\"","&quot;",$string);
  }

  if ($dosmilies) {
    $string = str_replace("&gt;)", "&gt; )", $string);
    $string = str_replace("&lt;)", "&lt; )", $string);
    $smilies = getsmiliedata();
    while ($smilie_arr = fetch_array($smilies)) {
      $string = str_replace($smilie_arr[smilietext],"<img src=\"".$foruminfo[smilie_image_path].$smilie_arr[smiliepath]."\" alt=\"\" />",$string);
    }
  }

  if ($donewline) {
    $string = nl2br($string);
  }

  if ($doformatting) {

    $searcharraytemp = array();
    $replacearraytemp = array();

    $searcharraytemp[] = "/javascript:/si";
    $searcharraytemp[] = "/about:/si";

    $searcharraytemp[] = "#\[url=(['\"]?)([^\"']*)\\1](.*)\[/url]#esiU";
    $searcharraytemp[] = "#\[email=(['\"]?)([^\"']*)\\1](.*)\[/email]#siU";
    $searcharraytemp[] = "#\[color=(['\"]?)([^\"']*)\\1](.*)\[/color]#siU";
    $searcharraytemp[] = "#\[size=(['\"]?)([^\"']*)\\1](.*)\[/size]#siU";
    $searcharraytemp[] = "#\[font=(['\"]?)([^\"']*)\\1](.*)\[/font]#siU";

    $searcharraytemp[] = "#\[php](.*)\[/php]#esiU";
    $searcharraytemp[] = "#\[html](.*)\[/html]#esiU";
    $searcharraytemp[] = "#\[sql](.*)\[/sql]#esiU";
    $searcharraytemp[] = "#\[code](.*)\[/code]#esiU";
    $searcharraytemp[] = "#\[list](.*)\[/list]#esiU";

    $searcharraytemp[] = "#\[url]([^\"]*)\[/url]#esiU";
    $searcharraytemp[] = "#\[email](.*)\[/email]#siU";
    $searcharraytemp[] = "#\[quote](.*)\[/quote]#siU";
    $searcharraytemp[] = "#\[b](.*)\[/b]#siU";
    $searcharraytemp[] = "#\[u](.*)\[/u]#siU";
    $searcharraytemp[] = "#\[i](.*)\[/i]#siU";
    $searcharraytemp[] = "#\[s](.*)\[/s]#siU";

    $replacearraytemp[] = "java script:";
    $replacearraytemp[] = "about :";

    $replacearraytemp[] = "checkurl('\\2','\\3')";
    $replacearraytemp[] = "<a href=\"mailto:\\2\">\\3</a>";
    $replacearraytemp[] = "<span style=\"color:\\2\">\\3</span>";
    $replacearraytemp[] = "<span style=\"font-size:\\2px\">\\3</span>";
    $replacearraytemp[] = "<span style=\"font-family:\\2\">\\3</span>";

    $replacearraytemp[] = "phptagparse('\\1','$allowhtml','$donewline')";
    $replacearraytemp[] = "htmltagparse('\\1','$allowhtml','$donewline')";
    $replacearraytemp[] = "sqltagparse('\\1','$allowhtml','$donewline')";
    $replacearraytemp[] = "codetagparse('\\1','$allowhtml','$donewline')";
    $replacearraytemp[] = "makelists('\\1')";

    $replacearraytemp[] = "checkurl('\\1')";
    $replacearraytemp[] = "<a href=\"mailto:\\1\">\\1</a>";
    $replacearraytemp[] = "<blockquote><b>Quote:</b><hr />\\1<hr /></blockquote>";
    $replacearraytemp[] = "<b>\\1</b>";
    $replacearraytemp[] = "<u>\\1</u>";
    $replacearraytemp[] = "<i>\\1</i>";
    $replacearraytemp[] = "<s>\\1</s>";

    if (empty($searcharray)) {

      $searcharray = array();
      $replacearray = array();

      for ($i=0;$i<count($searcharraytemp);$i++) {
        $searcharray[] = $searcharraytemp[$i];
        $searcharray[] = $searcharraytemp[$i];
        $searcharray[] = $searcharraytemp[$i];
        $replacearray[] = $replacearraytemp[$i];
        $replacearray[] = $replacearraytemp[$i];
        $replacearray[] = $replacearraytemp[$i];
      }
    }

    $string = preg_replace($searcharray,$replacearray,$string);

  }

  if ($doimg) {
    $string = preg_replace("/(\[)(img)(])([^\"\?\&]*)(\[\/img\])/siU","<img src=\"\\4\" border=\"0\" alt=\"\" />",$string);
  }
  $string = preg_replace("/(\[)(img)(])(.*)(\[\/img\])/siU", "<a href=\"\\4\" target=\"_blank\">\\4</a>", $string);

  if ($censorwords) {
    $censor_arr = explode(" ",$censorwords);
    foreach ($censor_arr as $word) {
      $replaceword = "";
      for ($i=0;$i<strlen($word);$i++) {
	$replaceword .= "*";
      }
      $string = eregi_replace($word,$replaceword,$string);
    }
  }

//  if ($highlight) {
//    $string = preg_replace("/(^| |\n|\r|\t|\]|>|\")(".$highlight.")(([\.,]+[ $\n\r\t])|$|\"|<|\[| |\n|\r|\t)/siU", "\\1<font color=\"red\">\\2</font>\\3", $string);
//  }

  $string = str_replace("{","&#123;",$string);
  $string = str_replace("\$","&#036;",$string);

  return $string;
}

function returncatnav($category,$parentid=0,$level=0) {

  global $cat_arr,$catid,$location,$stylevar;

  if (($category[parentid] == $parentid) & $category[display] & $category[displaymain] & isuserallowed($category[allowview])) {

    if ($level == 3) {
      $category[name] = "---- $category[name]";
    } elseif ($level == 2) {
      $category[name] = "-- $category[name]";
    }

    if (($category[id] == $catid) & preg_match("/index.php/",$location)) {
      if (preg_match("/action=cat/",$location) | preg_match("/action=custom/",$location)) {
        eval("\$links .= \"".returnpagebit("misc_cat_nav_link")."\";");
      } else {
        eval("\$links .= \"".returnpagebit("misc_cat_nav_off")."\";");
      }
    } else {
      eval("\$links .= \"".returnpagebit("misc_cat_nav_link")."\";");
    }

    if (($level == 1) | ($level == 2)) {

      $sub_arr = $GLOBALS[cat_arr];
      foreach ($sub_arr AS $key => $val) {
        $val[id] = $key;
        $links .= returncatnav($val,$category[id],$level+1);
      }
      unset($sub_arr);
    }
  }
  return $links;
}

function returnpagebit($name) {
  global $pagecache,$pagesetid;

  if ($pagecache[$name][$pagesetid] != "") {
    $data = $pagecache[$name][$pagesetid];
  } else {

    $pagetype = returnpagetype($name,$pagesetid);

    if ($pagetype == 3) {
      $data = @join("",@file("pages/user/".$name."_".$pagesetid.".vnp"));
    } elseif ($pagetype == 4) {
      $data = @join("",@file("pages/default/".$name.".vnp"));
    } elseif ($pagetype == 1) {
      $data = @join("",@file("pages/user/mod/".$name."_".$pagesetid.".vnp"));
    } elseif ($pagetype == 2) {
      $data = @join("",@file("pages/default/mod/".$name.".vnp"));
    }

    $pagecache[$name][$pagesetid] = $data;
  }

  $data = str_replace("\\\\\$","\\\\\\\$",str_replace("\\'","'",addslashes($data)));

  return $data;
}

function returnpagetype($name,$pagesetid=0) {

  if (@file_exists("pages/user/".$name."_".$pagesetid.".vnp")) {
    // Is changed page - main
    return 3;
  } elseif (@file_exists("pages/default/".$name.".vnp")) {
    // Is default page - main
    return 4;
  } elseif (@file_exists("pages/user/mod/".$name."_".$pagesetid.".vnp")) {
    // Is changed page - mod
    return 1;
  } elseif (@file_exists("pages/default/mod/".$name.".vnp")) {
    // Is default page - mod
    return 2;
  } else {
    // Is nothing
    return 0;
  }
}

function returnqhtmllinks() {

  global $user_allowqhtml,$loggedin,$staffid,$staff_allowqhtml,$loggedout_allowqhtml,$qhtmlcodemode,$stylevar;

  if (($user_allowqhtml & $loggedin) | ($staffid & $staff_allowqhtml) | (!$loggedin & $loggedout_allowqhtml)) {

    if (empty($qhtmlcodemode)) {
      $checked[qhtmlmode_0] = " checked=\"checked\"";
      $checked[qhtmlmode_1] = "";
    } else {
      $checked[qhtmlmode_0] = "";
      $checked[qhtmlmode_1] = " checked=\"checked\"";
    }

    eval("\$qhtmlcode = \"".returnpagebit("comments_add_qhtml_links")."\";");
  } else {
    eval("\$qhtmlcode = \"".returnpagebit("comments_add_qhtml_disabled")."\";");
  }

  return $qhtmlcode;
}


function returnsitejumplink($category,$parentid=0,$level=0) {

  global $cat_arr,$catid,$location,$stylevar;

  if (($category[parentid] == $parentid) & $category[display] & $category[displaymain] & isuserallowed($category[allowview])) {

    if ($level == 3) {
      $category[name] = "---- $category[name]";
    } elseif ($level == 2) {
      $category[name] = "-- $category[name]";
    }

    eval("\$links .= \"".returnpagebit("misc_sitejump_cat")."\";");

    if (($level == 1) | ($level == 2)) {

      $sub_arr = $GLOBALS[cat_arr];
      foreach ($sub_arr AS $key => $val) {
        $val[id] = $key;
        $links .= returnsitejumplink($val,$category[id],$level+1);
      }
      unset($sub_arr);
    }
  }
  return $links;
}

function sqltagparse($string,$allowhtml,$donewline) {

  $string = str_replace("\r\n","\n",$string);

  if ($donewline) {
    $string = str_replace("<br />\n","\n",$string);
  }

  if ($allowhtml) {
    $string = str_replace("&lt;","&amp;lt;",$string);
    $string = str_replace("&gt;","&amp;gt;",$string);
    $string = str_replace("<","&lt;",$string);
    $string = str_replace(">","&gt;",$string);
    $string = str_replace("\"","&quot;",$string);
  }

  $string = str_replace("\\\"","\"",$string);

  $string = preg_replace("/(=|\+|\-|&gt;&lt;|&gt;|&lt;|~|==|!=|LIKE|NOT LIKE|REGEXP)/iU","<span style=\"color:orange\">\\1</span>",$string);
  $string = preg_replace("/(&quot;|')(.*)(&quot;|')/siU", "<span style=\"color:red\">\\1\\2\\3</span>",$string);
  $string = preg_replace("/(\s+|^)(SELECT|INSERT|UPDATE|DELETE|ALTER TABLE|DROP)/iU","\\1<span style=\"color:blue;font-weight:bold\">\\2</span>",$string);
  $string = preg_replace("/(MAX|AVG|SUM|COUNT|MIN)\(/iU","<span style=\"color:blue\">\\1</span>(",$string);
  $string = preg_replace("/(FROM|INTO)(\s+)(\S*)(\s+)/iU","<span style=\"color:green\">\\1</span>\\2<span style=\"color:orange\">\\3</span>\\4",$string);
  $string = preg_replace("/(\s+)((LEFT|INNER|RIGHT)*)(\s+)JOIN(\s+)(\S*)(\s+)/iU","\\1<span style=\"color:green\">\\2\\4JOIN</span>\\5<span style=\"color:orange\">\\6</span>\\7",$string);
  $string = preg_replace("/(\s+)(WHERE|MODIFY|CHANGE|AS|DISTINCT|IN|ON|ASC|DESC|ORDER BY)(\s+)/iU","\\1<span style=\"color:green\">\\2</span>\\3",$string);
  $string = preg_replace("/(\s+)(AND|OR|NOT)(\s+)/iU", "\\1<span style=\"color:blue\">\\2</span>\\3",$string);
  $string = preg_replace("/LIMIT(\s+)([0-9]+)(\s*),(\s*)([0-9]+)(\s*)/iU","<span style=\"color:green\">LIMIT</span>\\1<span style=\"color:orange\">\\2,\\5</span>\\6",$string); // LIMIT x,y
  $string = preg_replace("/LIMIT(\s+)([0-9]+)(\s*)/iU","<span style=\"color:green\">LIMIT</span>\\1<span style=\"color:orange\">\\2</span>\\3",$string); // LIMIT x
  $string = stripslashes($string);

  $string = "<blockquote><b>SQL:</b><hr /><pre>$string</pre><hr /></blockquote>";

  return $string;
}

function standarderror($error) {

  global $cat_arr,$welcometext,$theme_selector,$sitestats,$forumstats,$sitename,$theme_arr,$HTTP_REFERER,$stylevar;
  global $maximages,$commentchrlimit,$commentuserlimit,$commentemaillimit,$webmasteremail,$defaultcategory,$poll,$category_nav;
  $cat_text = $cat_arr[$defaultcategory][name];

  if (!$GLOBALS[pagesetid]) {
    $themeid = $cat_arr[$defaultcategory][defaulttheme];
    $GLOBALS[pagesetid] = $theme_arr[$themeid][pagesetid];
    $stylevar = getstylevars($theme_arr[$themeid][stylesetid]);
  }

  $navbar = makenavbar("Error");
  eval("\$error_message = \"".returnpagebit("error_$error")."\";");
  include("static/sub_pages/error_page_".$GLOBALS[pagesetid].".php");
  exit;
}

function standardredirect($message="",$url="",$getmessage=1) {

  global $cat_arr,$welcometext,$theme_selector,$sitestats,$forumstats,$pagesetid,$sitename,$theme_arr,$HTTP_REFERER,$stylevar;
  global $maximages,$commentchrlimit,$commentuserlimit,$commentemaillimit,$webmasteremail,$defaultcategory,$poll,$useredirects;

  if ($url) {
    $url = xss_clean($url);
  } else {
    $url = "index.php";
  }

  if (!$useredirects) {
    header_redirect($url,"Redirecting You");
  }

  $cat_text = $cat_arr[$defaultcategory][name];

  if (!$pagesetid) {
    $GLOBALS[themeid] = $cat_arr[$defaultcategory][defaulttheme];
    $GLOBALS[pagesetid] = $theme_arr[$themeid][pagesetid];
  }

  if ($getmessage) {
    eval("\$message = \"".returnpagebit("redirect_$message")."\";");
  }

  eval("\echooutput(\"".returnpagebit("redirect_page")."\");");
  exit;
}

function updatecookie($name,$value="",$expire=0) {
  global $cookiepath,$cookiedomain;

  $expire = iif($expire,$expire,time() + (60*60*24*365));

  if ($cookiedomain) {
    setcookie($name,$value,$expire,$cookiepath,$cookiedomain);
  } else {
    setcookie($name,$value,$expire,$cookiepath);
  }

}

function writepagebit($path,$text) {

  if ($file = @fopen($path,"wb")) {
    @fwrite($file,$text);
    $file = @fclose($file);
    return true;
  } else {
    if (function_exists("adminerror")) {
      adminerror("Write Error","Unable to write to $path file, you must ensure that this file has the correct permissions set for writing to it.  Your last actions have been record sucessfully so do not refresh the page if it was adding something you did.  You must submit something to the site unchanged after you have set the file permissions and this will write the pages again.");
    } else {
      return false;
    }
  }
}

function xss_clean ($var) {
  $var = str_replace("\\\"","&quot;",$var);
  $var = preg_replace("/javascript/i","java script",$var);
  $var = str_replace("<","&lt;",$var);
  $var = str_replace(">","&gt;",$var);
  return $var;
}

if (!function_exists("mysql_escape_string"))  {
  function mysql_escape_string($string) {
    $string = str_replace("\\","\\\\",$string);
    $string = str_replace("\0",'\0',$string);
    $string = str_replace("\n",'\n',$string);
    $string = str_replace("\r",'\r',$string);
    $string = str_replace("'",'\\\'',$string);
    $string = str_replace("\"",'\"',$string);
    $string = str_replace("\032","\\Z",$string);

    return $string;
  }
}

if (!function_exists("verifyid")) {

  function verifyid($table,$checkid,$fieldname="id") {
    settype($checkid,"integer");

    if (empty($checkid)) {
      standarderror("invalid_id");
    }

    $checkid = query_first("SELECT COUNT($fieldname) AS count FROM $table WHERE $fieldname = $checkid");

    if ($checkid[count] == 0) {
      standarderror("invalid_id");
    }
  }

}

/*======================================================================*\
|| ####################################################################
|| # File: includes/functions.php
|| ####################################################################
\*======================================================================*/

?>
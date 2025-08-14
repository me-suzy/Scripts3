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

function writeindex($catid) {

  global $cat_arr,$timeoffset,$newsdate,$newsperpage,$forumpath,$version,$pagesetid,$newsedittext,$foruminfo;
  global $staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml,$sitename,$defaultcategory;
  global $commenttext1_s,$commenttext2_s,$commenttext1_p,$commenttext2_p,$newsedittexttime;

  static $getdata;

  if (isset($getdata[$catid])) {
    data_seek($getdata[$catid],0);
  } else {

    $cat_ids = $catid;

    if ($cat_arr[$catid][showsubcats]) {
      $temp = $cat_arr;
      foreach ($temp AS $key => $val) {
        if (($val[topid] == $catid) | ($val[parentid] == $catid)) {
          $cat_ids .= ",$key";
        }
      }
      unset($temp);
    }

    $getdata[$catid] = query("SELECT
      news_news.lastcommentuser,
      news_news.id,
      news_news.catid,
      news_news.title,
      news_news.mainnews,
      news_news.extendednews,
      news_news.logoimage,
      news_news.logoimageborder,
      news_news.commentcount,
      news_news.time,
      news_news.stickypost,
      news_news.parsenewline,
      news_news.allowcomments,
      news_news.editstaffid,
      news_news.editdate,
      ".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid,
      ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
      MAX(news_comment.id) AS lastcommentid
      FROM news_news
      LEFT JOIN news_staff ON news_news.staffid = news_staff.id
      LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
      LEFT JOIN news_comment ON news_news.id = news_comment.newsid
      WHERE (news_news.display <> 0)
      AND (news_news.catid IN ($cat_ids))
      AND (news_news.time < ".time().")
      AND (news_news.program = 0)
      GROUP BY news_news.id
      ORDER BY news_news.stickypost DESC,news_news.time DESC
      LIMIT $newsperpage");
  }

  while ($news = fetch_array($getdata[$catid])) {

    $news[mainnews] = qhtmlparse($news[mainnews],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml,$news[parsenewline]);
    $news[lastcommentid] = iif($news[lastcommentid],$news[lastcommentid],0);

    if ($news[editdate] & $newsedittext) {
      $editedby = query_first("SELECT ".$foruminfo[user_table].".".$foruminfo[username_field]." FROM news_staff LEFT JOIN ".$foruminfo[user_table]." ON ".$foruminfo[user_table].".".$foruminfo[userid_field]." = news_staff.userid WHERE news_staff.id = $news[editstaffid]");
      $editedby[date] = date($newsdate,$news[editdate]-$timeoffset);
      $editedby[username] = htmlspecialchars($editedby[username]);
      eval("\$news[editedby] = \"".returnpagebit("comments_news_editedby")."\";");
    }

    $news[time] = date($newsdate,$news[time]-$timeoffset);

    $posterid = $news[userid];
    $postername = htmlspecialchars($news[username]);
    eval("\$news[poster] = \"".returnpagebit("misc_username_profile")."\";");

    if ($news[extendednews] != "") {
      eval("\$news[readmorelink] = \"".returnpagebit("main_news_post_read_more")."\";");
    } else {
      unset($news[readmorelink]);
    }

    if ($news[lastcommentuser] == "") {
      $news[lastcommentuser] = htmlspecialchars($news[lastcommentuser]);
      eval("\$news[lastcommentuser] = \"".returnpagebit("main_news_post_no_comments")."\";");
    }

    if ($news[logoimage]) {
      if ($news[logoimageborder]) {
        eval("\$news[logoimageborder] = \"".returnpagebit("main_news_post_logo_border")."\";");
      } else {
        unset($news[logoimageborder]);
      }
      eval("\$news[logoimage] = \"".returnpagebit("main_news_post_logo")."\";");
    } else {
      unset($news[logoimage]);
    }

    if ($news[allowcomments]) {
      eval("\$news[comments_image] = \"".returnpagebit("main_news_post_comments_image")."\";");
    } else {
      eval("\$news[comments_image] = \"".returnpagebit("main_news_post_comments_image_lock")."\";");
    }

    if ($news[commentcount] == "1") {
      $commenttext1 = $commenttext1_s;
      $commenttext2 = $commenttext2_s;
    } else {
      $commenttext1 = $commenttext1_p;
      $commenttext2 = $commenttext2_p;
    }

    if ($news[stickypost] == 2) {
      eval("\$page .= \"".returnpagebit("main_news_sticky")."\";");
    } elseif ($news[stickypost] == 1) {
      eval("\$sticky .= \"".returnpagebit("main_news_sticky")."\";");
    } else {
      eval("\$page .= \"".returnpagebit("main_news_post")."\";");
    }

  }

  if (!empty($sticky)) {
    $page .= $sticky;
    unset($sticky);
  }

  $cat_name = $cat_arr[$catid][name];

  $info = getsitebits($catid,0);
  $announcement = $info[ann];
  $forumoptions = $info[forumoptions];
  $module_links = $info[mod_links];
  $recentpost = $info[recentpost];

  eval("\$menu = \"".returnpagebit("menu_$catid")."\";");
  eval("\$header = \"$info[header]\";");
  eval("\$footer = \"$info[footer]\";");

  eval("\$finalpage = \"".returnpagebit("main_home_page")."\";");

  $finalpage = "<?php\n@echooutput(\"".str_replace("\\\\\$","\$",str_replace("\\'","'",addslashes($finalpage)))."\");\n?>";

  writepagebit("static/index/index_".$catid."_".$pagesetid.".php",$finalpage);

}

function writecomment($catid) {

  global $cat_arr,$timeoffset,$sitename,$commentuserlimit,$commentemaillimit,$pagesetid;
  global $version;

  $cat_name = $cat_arr[$catid][name];

  $info = getsitebits($catid);
  $announcement = $info[ann];
  $forumoptions = $info[forumoptions];
  $module_links = $info[mod_links];
  $recentpost = $info[recentpost];

  eval("\$menu = \"".returnpagebit("menu_$catid")."\";");
  eval("\$header = \"$info[header]\";");
  eval("\$footer = \"$info[footer]\";");

  eval("\$finalpage = \"".returnpagebit("comments_page")."\";");

  $finalpage = "<?php\n@echooutput(\"".str_replace("\\\\\$","\$",str_replace("\\'","'",addslashes($finalpage)))."\");\n?>";
  writepagebit("static/comment/comment_".$catid."_".$pagesetid.".php",$finalpage);

}

function writeaboutus() {

  global $cat_arr,$timeoffset,$defaultcategory,$sitename,$version,$aboutusdate,$pagesetid,$foruminfo;

  $catid = $defaultcategory;

  static $getdata;

  if (isset($getdata)) {
    data_seek($getdata,0);
  } else {
    $getdata = query("SELECT
      news_staff.newsposts,
      news_staff.job,
      ".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid,
      ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
      ".$foruminfo[user_table].".".$foruminfo[joindate_field]." AS joindate
      FROM news_staff
      LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
      ORDER BY ".$foruminfo[user_table].".".$foruminfo[username_field]);
  }

  while ($staff = fetch_array($getdata)) {

    $staff[joindate] = date($aboutusdate,$staff[joindate]-$timeoffset);
    $staff[username] = htmlspecialchars($staff[username]);

    eval("\$staff_data .= \"".returnpagebit("aboutus_record")."\";");

  }

  $cat_name = $cat_arr[$catid][name];

  $info = getsitebits($catid);
  $announcement = $info[ann];
  $forumoptions = $info[forumoptions];
  $module_links = $info[mod_links];
  $recentpost = $info[recentpost];

  eval("\$menu = \"".returnpagebit("menu_$catid")."\";");
  eval("\$header = \"$info[header]\";");
  eval("\$footer = \"$info[footer]\";");

  eval("\$finalpage = \"".returnpagebit("aboutus_main")."\";");

  $finalpage = "<?php\n@echooutput(\"".str_replace("\\\\\$","\$",str_replace("\\'","'",addslashes($finalpage)))."\");\n?>";
  writepagebit("static/aboutus_".$pagesetid.".php",$finalpage);
}

function writearticles() {

  global $cat_arr,$timeoffset,$defaultcategory,$sitename,$version,$aboutusdate,$themeid,$pagesetid;
  global $staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml;

  $catid = $defaultcategory;

  static $getdata;

  if (isset($getdata)) {
    data_seek($getdata,0);
  } else {
    $getdata = query("SELECT id,title,numarticles,description,parentid,children FROM news_articlecat WHERE display <> 0 ORDER BY displayorder");
  }

  while ($cat = fetch_array($getdata)) {
    $cat[description] = qhtmlparse($cat[description],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);

    if ($cat[parentid] == 0) {
      eval("\$cat_list .= \"".returnpagebit("articles_index_cat")."\";");

      for ($i=0;$i<$cat[children];$i++) {
        $cat_list .= "\$children_".$cat[id]."[$i]";
      }

    } else {
      $varname = "children_$cat[parentid]";
      eval("\${\$varname}[] .= \"".returnpagebit("articles_index_sub_cat")."\";");
    }
  }

  eval("\$cat_list = \"".str_replace("\"","\\\"",str_replace("\\","\\\\",$cat_list))."\";");

  $cat_name = $cat_arr[$catid][name];

  $info = getsitebits($catid);
  $announcement = $info[ann];
  $forumoptions = $info[forumoptions];
  $module_links = $info[mod_links];
  $recentpost = $info[recentpost];

  eval("\$menu = \"".returnpagebit("menu_$catid")."\";");
  eval("\$header = \"$info[header]\";");
  eval("\$footer = \"$info[footer]\";");

  eval("\$finalpage = \"".returnpagebit("articles_index_page")."\";");

  $finalpage = "<?php\n@echooutput(\"".str_replace("\\\\\$","\$",str_replace("\\'","'",addslashes($finalpage)))."\");\n?>";
  writepagebit("static/articles_".$pagesetid.".php",$finalpage);

}


//###########################Start Write Poll Options

function writepolloptions($catid) {

  global $pagesetid;
  static $data_arr;

  if (!isset($data_arr[$catid])) {
    $data_arr[$catid] = query_first("SELECT
      id,
      question,
      option1,
      option2,
      option3,
      option4,
      option5,
      option6,
      option7,
      option8,
      option9,
      option10
      FROM news_poll
      WHERE (display LIKE '$catid,%')
      OR (display LIKE '%,$catid,%')
      OR (display LIKE '%,$catid%')
      OR (display = '$catid')
      ORDER BY id DESC LIMIT 1");
  }

  if ($data_arr[$catid]) {

    for ($i=1;$i<=10;$i++) {
      if ($data_arr[$catid][option.$i]) {
        $option = $data_arr[$catid][option.$i];
        $optioncount = $i;
        $checked = iif($i == 1," checked=\"checked\"","");

        eval("\$options .= \"".returnpagebit("poll_options_option")."\";");
      }
    }

    $pollid = $data_arr[$catid][id];
    $question = $data_arr[$catid][question];

    eval("\$poll = \"".returnpagebit("poll_options_table")."\";");

  }

  $finalpage = "<?php\n\$poll = \"".str_replace("\\\\\$","\$",str_replace("\\'","'",addslashes($poll)))."\";\n?>";

  writepagebit("static/polls/options_".$catid."_".$pagesetid.".php",$finalpage);

}

//###########################Start Write Poll Results


function writepollresults($catid) {

  global $pagesetid;
  static $data_arr;

  if (!isset($data_arr[$catid])) {
    $data_arr[$catid] = query_first("SELECT
      id,
      question,
      option1,
      option2,
      option3,
      option4,
      option5,
      option6,
      option7,
      option8,
      option9,
      option10,
      totalvotes,
      votes
      FROM news_poll
      WHERE (display LIKE '$catid,%')
      OR (display LIKE '%,$catid,%')
      OR (display LIKE '%,$catid%')
      OR (display = '$catid')
      ORDER BY id DESC LIMIT 1");
  }

  if ($data_arr[$catid]) {

    $question = $data_arr[$catid][question];
    $totalvotes = $data_arr[$catid][totalvotes];

    $splitvotes = explode(",",$data_arr[$catid][votes]);

    for ($i=1;$i<=10;$i++) {
      if ($data_arr[$catid][option.$i]) {
        $option = $data_arr[$catid][option.$i];
        $optioncount = $i;
        $barwidth = @round(($splitvotes[$i-1]/$totalvotes)*50);
        $no_of_votes = $splitvotes[$i-1];
        eval("\$results .= \"".returnpagebit("poll_results_option")."\";");
      }

    }

    eval("\$poll = \"".returnpagebit("poll_results_table")."\";");

  }

  $finalpage = "<?php\n\$poll = \"".str_replace("\\\\\$","\$",str_replace("\\'","'",addslashes($poll)))."\";\n?>";

  writepagebit("static/polls/results_".$catid."_".$pagesetid.".php",$finalpage);
}

function writesubpages() {

  global $cat_arr,$timeoffset,$defaultcategory,$sitename,$version,$homeurl,$pagesetid,$use_forum;

  $pages[archive] = "archive_page";
  $pages[articles_cats] = "articles_cats_page";
  $pages[articles_display] = "articles_display_page";
  $pages[catlist] = "main_catlist_page";
  $pages[custom_page] = "main_custom_page";
  $pages[comment_edit] = "edit_page";
  $pages[comment_report] = "comments_report";
  $pages[error_page] = "error_page";
  $pages[help_faq] = "help_faq";
  $pages[help_qhtml] = "help_qhtml";
  $pages[help_smilie] = "help_smilie";
  $pages[poll_results] = "poll_view_results";
  $pages['print'] = "print_news";
  $pages[register_lost] = "register_lost_activation";
  $pages[search_form] = "search_page";
  $pages[search_results] = "search_results_page";
  $pages[stats] = "stats_main_page";
  $pages[user_login] = "user_login_form";

  if (!$use_forum) {
    $pages[member_email_form] = "member_email_form";
    $pages[member_email_disabled] = "member_email_disabled";
    $pages[member_list] = "member_list_page";
    $pages[member_search] = "member_list_search";
    $pages[member_profile] = "member_profile_page";
    $pages[register_form] = "register_form";
    $pages[user_forget_form] = "user_forget_request_form";
    $pages[user_index] = "user_index_page";
    $pages[user_pm_form] = "user_pm_form";
    $pages[user_pm_inbox] = "user_pm_inbox";
    $pages[user_pm_outbox] = "user_pm_outbox";
    $pages[user_pm_show] = "user_pm_show";
    $pages[user_profile_edit] = "user_profile_edit";
    $pages[user_pwd_edit] = "user_pwd_edit";
  }

  $catid = $defaultcategory;
  $cat_name = $cat_arr[$catid][name];

  $info = getsitebits($catid);
  $announcement = $info[ann];
  $forumoptions = $info[forumoptions];
  $module_links = $info[mod_links];
  $recentpost = $info[recentpost];

  eval("\$menu = \"".returnpagebit("menu_$catid")."\";");
  eval("\$header = \"$info[header]\";");
  eval("\$footer = \"$info[footer]\";");

  while (list($key,$val) = each($pages)) {
    unset($finalpage);
    eval("\$finalpage = \"".returnpagebit("$val")."\";");

    $finalpage = "<?php\n@echooutput(\"".str_replace("\\\\\$","\$",str_replace("\\'","'",addslashes($finalpage)))."\");\n?>";
    writepagebit("static/sub_pages/".$key."_".$pagesetid.".php",$finalpage);
  }
}

//###########################Start Get site bits

function getsitebits($catid,$showallcatnav=1) {

  global $cat_arr,$version,$sitename,$forumpath,$pagesetid,$use_forum,$foruminfo;
  static $info_arr;

  if ($cat_arr[$catid][showforumoptions] && !empty($use_forum)) {
    eval("\$info[forumoptions] = \"".returnpagebit("misc_forum_options")."\";");
  }

  if ($cat_arr[$catid][showannouncement]) {
    $info[ann] = getannouncement($catid);
  }

  if ($cat_arr[$catid][recentpost]) {
    $info[recentpost] = getrecentposts($cat_arr[$catid][recentpost]);
  }

  $info[mod_links] = getmodulelinks();

  $info[footer] = returnpagebit("misc_footer");;
  $info[header] = returnpagebit("misc_header");

  return $info;

}

//###########################Start Get announcement

function getannouncement($catid) {

  global $pagesetid,$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml;
  static $ann_arr,$ann;

  if (isset($ann_arr[$catid][$pagesetid])) {
    return $ann_arr[$catid][$pagesetid];
  } else {

    if (!isset($ann[$catid])) {
      $ann[$catid] = query_first("SELECT content,image,link FROM news_announcement WHERE catid = '$catid'");
    }

    $announcement = $ann[$catid];

    $announcement[content] = qhtmlparse($announcement[content],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);

    if ($announcement[image]) {
      if ($announcement[link]) {
        eval("\$announcement[image] = \"".returnpagebit("misc_announcement_image_link")."\";");
      } else {
        eval("\$announcement[image] = \"".returnpagebit("misc_announcement_image")."\";");
      }
    }

    eval("\$content = \"".returnpagebit("misc_announcement")."\";");

    $ann_arr[$catid][$pagesetid] = $content;
    return $content;
  }
}

function getrecentposts($categories) {

  global $cat_arr,$recentpostcount,$recentpostchr,$pagesetid;

  static $recentpost_arr,$getposts;

  if ($categories) {

    $temp = explode(",",$categories);

    $count = 0;

    foreach ($temp AS $catid) {
      $count ++;

      if ($recentpost_arr[$catid][$pagesetid]) {
        $recentpost[$count] = $recentpost_arr[$catid][$pagesetid];
      } else {
        $cat_name = $cat_arr[$catid][name];
        eval("\$recentpost[\$count] = \"".returnpagebit("misc_recent_post_header")."\";");

        if (isset($getposts[$catid])) {
          data_seek($getposts[$catid],0);
        } else {
          $cat_ids = $catid;
          if ($cat_arr[$catid][showsubcats]) {
            $temp = $cat_arr;
            foreach ($temp AS $key => $val) {
              if (($val[topid] == $catid) | ($val[parentid] == $catid)) {
                $cat_ids .= ",$key";
              }
            }
            unset($temp);
          }

          $getposts[$catid] = query("SELECT id,title,catid FROM news_news WHERE (catid IN ($cat_ids)) AND (display <> 0) AND (time < ".time().") ORDER BY time DESC LIMIT $recentpostcount");
        }

        while ($post = fetch_array($getposts[$catid])) {
          $post[short_title] = iif(strlen($post[title]) > $recentpostchr,substr($post[title],0,$recentpostchr)."...",$post[title]);
          $post[full_title] = $post[title];
          eval("\$recentpost[\$count] .= \"".returnpagebit("misc_recent_post_link")."\";");
        }

        $recentpost_arr[$catid][$pagesetid] = $recentpost[$count];
      }
    }
  }
  return $recentpost;
}

function getmodulelinks() {

  global $pagesetid;
  static $mod_links,$getmods;

  if (!isset($mod_links[$pagesetid])) {
    if (isset($getmods)) {
      data_seek($getmods,0);
    } else {
      $getmods = query("SELECT text,id,enable FROM news_module WHERE (display <> 0) AND (enable <> 0)");
    }

    while ($mod = fetch_array($getmods)) {
      eval("\$mod_links[$pagesetid] .= \"".returnpagebit("misc_module_link")."\";");
    }
  }

  return $mod_links[$pagesetid];
}

function saveoptions() {

  $filedata = "<"."?"."php\n\n";

  $getsettings = query("SELECT varname,value FROM news_option");
  while ($settings_arr = fetch_array($getsettings)) {
    $GLOBALS[$settings_arr[varname]] = $settings_arr[value];

    $filedata .= "\$".$settings_arr[varname]." = \"".addslashes($settings_arr[value])."\";\n";
  }

  $filedata .= "\n?".">";

  writepagebit("static/options.php",$filedata);

}

/*======================================================================*\
|| ####################################################################
|| # File: includes/writefunctions.php
|| ####################################################################
\*======================================================================*/

?>
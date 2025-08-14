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

include("global.php");

settype($id,"integer");

$news = query_first("SELECT
  news_news.catid,
  news_news.title,
  news_news.mainnews,
  news_news.extendednews,
  news_news.commentcount,
  news_news.time,
  news_news.parsenewline,
  ".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid,
  ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
  news_subscribe.emailupdate,
  news_subscribe.lastview,
  news_subscribe.id AS subscribeid
  FROM news_news
  LEFT JOIN news_staff ON news_staff.id = news_news.staffid
  LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
  LEFT JOIN news_subscribe ON news_subscribe.userid = $userid AND news_subscribe.newsid = $id
  WHERE news_news.id = $id LIMIT 1");

if ($news) {

  if ($news[emailupdate] != "") {
    query("UPDATE news_subscribe SET lastview = '".time()."' , emailupdate = '1' WHERE id = $news[subscribeid]");
  }

  $news[catname] = $cat_arr[$news[catid]][name];
  $news[time] = date($newsdate,$news[time]-$timeoffset);

  $posterid = $news[userid];
  $postername = htmlspecialchars($news[username]);
  eval("\$news[poster] = \"".returnpagebit("misc_username_profile")."\";");

  $news[mainnews] = qhtmlparse($news[mainnews],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml,$news[parsenewline]);

  if ($data_arr[extendednews]) {
    $news[extendednews] = qhtmlparse($news[extendednews],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml,$news[parsenewline]);
  }

  if ($commentcount == "1") {
    $commenttext1 = $commenttext1_s;
    $commenttext2 = $commenttext2_s;
  } else {
    $commenttext1 = $commenttext1_p;
    $commenttext2 = $commenttext2_p;
  }

  eval("\$newspost = \"".returnpagebit("print_news_post")."\";");

  $getdata = query("SELECT
    news_comment.id,
    news_comment.comment,
    news_comment.time,
    news_comment.parentid,
    news_comment.userid,
    news_comment.username AS name,
    news_comment.useremail,
    ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
    news_staff.id AS staffid
    FROM news_comment
    LEFT JOIN $foruminfo[user_table] ON news_comment.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
    LEFT JOIN news_staff ON ".$foruminfo[user_table].".".$foruminfo[userid_field]." = news_staff.userid
    WHERE news_comment.newsid = $id
    ORDER BY news_comment.time");

  $commentnum = 0;

  while ($comment = fetch_array($getdata)) {

    $comment[time] = date($commentdate,$comment[time]-$timeoffset);

    if ($comment[userid]) {

      $posterid = $comment[userid];
      $postername = htmlspecialchars($comment[username]);
      eval("\$comment[poster] = \"".returnpagebit("misc_username_profile")."\";");

      if ($comment[staffid]) {
        $comment[comment] = qhtmlparse($comment[comment],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
      } else {
        $comment[comment] = qhtmlparse($comment[comment],$user_allowhtml,$user_allowimg,$user_allowsmilies,$user_allowqhtml);
      }
    } else {

      $postername = htmlspecialchars($comment[name]);
      if ($data_arr[useremail]) {
        $posteremail = $comment[useremail];
        eval("\$comment[poster] = \"".returnpagebit("misc_username_email")."\";");
      } else {
        eval("\$comment[poster] = \"".returnpagebit("misc_username_noemail")."\";");
      }

      $comment[comment] = qhtmlparse($comment[comment],$loggedout_allowhtml,$loggedout_allowimg,$loggedout_allowsmilies,$loggedout_allowqhtml);
    }

    if ($comment[parentid] < 1) {
      $commentnum ++;

      if ($comment[parentid] < 0) {
        $comment[replycount] = $comment[parentid]*(-1);

        if ($comment[replycount] == 1) {
          $comment[replytext] = $commentreplytext_s;
        } else {
          $comment[replytext] = $commentreplytext_p;
        }

        eval("\$comments .= \"".returnpagebit("print_news_comment_parent")."\";");

        for ($i=1;$i<=$comment[replycount];$i++) {
          $comments .= "\$child_".$commentnum."[$i]";
        }

        $parent_numbers[$comment[id]] = $commentnum;
      } else {
        eval("\$comments .= \"".returnpagebit("print_news_comment")."\";");
      }
    } else {
      $child_numbers[$comment[parentid]] ++;
      $child_num = $child_numbers[$comment[parentid]];
      $parent_num = $parent_numbers[$comment[parentid]];

      eval("\$child_".$parent_num."[".$child_num."] = \"".returnpagebit("print_news_comment_child")."\";");
    }

  }

  if ($parent_numbers) {
    eval("\$comments = \"".str_replace("\"","\\\"",$comments)."\";");
    foreach ($parent_numbers AS $key => $val) {
      $varname = "child_$key";
      unset($$varname);
    }
  }

  include("static/sub_pages/print_".$pagesetid.".php");

} else {
  standarderror("invalid_id");
}

/*======================================================================*\
|| ####################################################################
|| # File: print.php
|| ####################################################################
\*======================================================================*/

?>
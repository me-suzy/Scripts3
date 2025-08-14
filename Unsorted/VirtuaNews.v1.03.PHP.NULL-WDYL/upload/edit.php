<?php


require("global.php");

if (!$allowediting | !$loggedin) {
  standarderror("cannot_edit");
}

$navbar = makenavbar("Edit Comment");

$comment = query_first("
  SELECT
  news_comment.newsid,
  news_comment.parentid,
  news_comment.time,
  news_comment.comment,
  news_comment.editlock,
  news_comment.userid,
  ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
  news_news.catid
  FROM news_comment
  LEFT JOIN ".$foruminfo[user_table]." ON news_comment.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
  LEFT JOIN news_news ON news_comment.newsid = news_news.id
  WHERE news_comment.id = $id");

if ($comment) {

  if ($userid != $comment[userid]) {
    standarderror("edit_wrong_user");
  }

  if ($comment[editlock]) {
    standarderror("comment_edit_locked");
  }

  switch($action) {

  case "":

    $comment[time] = date($commentdate,$comment[time]-$timeoffset);
    $comment[comment] = htmlspecialchars($comment[comment]);
    $comment[username] = htmlspecialchars($comment[username]);

    if ($user_allowqhtml | ($staffid & $staff_allowqhtml)) {
      eval("\$autoparse_check = \"".returnpagebit("comments_add_autoparse_check")."\";");
    } else {
      $autoparse_check = "";
    }
    $qhtmlcode = returnqhtmllinks();

    eval("\$loggedinuser = \"".returnpagebit("comments_logged_in")."\";");
    include("static/sub_pages/comment_edit_".$pagesetid.".php");

  break;

  case "comment_update":

    if (trim($content) == "") {
      standarderror("blank_field");
    }

    if ($parseurl & ($user_allowqhtml | ($staffid & $staff_allowqhtml))) {
      $content = autoparseurl($content);
    }

    if ($maximages != 0) {
      if ($staffid) {
        $commentparsed = qhtmlparse($content,$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
      } else {
        $commentparsed = qhtmlparse($content,$user_allowhtml,$user_allowimg,$user_allowsmilies,$user_allowqhtml);
      }
      if (countchar($commentparsed,"<img") > $maximages) {
        standarderror("too_many_images");
      }
    }

    if (($commentchrlimit > 0) & (strlen($content) > $commentchrlimit)) {
      standarderror("comment_long");
    }

    query("UPDATE news_comment SET comment = '$content'".iif($commentedittexttime < (time() - $comment[time])," , edituserid = '$userid' , editdate = '".time()."'")." WHERE id = $id");

    if ($oc_news[$comment[$newsid]] == 2) {
      if (isset($oc_comment[$comment[parentid]])) {
        updatecookie("oc_comment[$comment[parentid]]",0,time()-1800);
      }
    }

    if (($oc_comment[$comment[parentid]] != 2) & ($oc_news[$comment[$newsid]] != 2)) {
      updatecookie("oc_comment[$comment[parentid]]",2);
    }

    standardredirect("comment_edit","comments.php?catid=$comment[catid]&id=$comment[newsid]#comment$id");

  break;

  default:
    standarderror("invalid_link");
  }
} else {
  standarderror("invalid_id");
}

/*======================================================================*\
|| ####################################################################
|| # File: edit.php
|| ####################################################################
\*======================================================================*/

?>
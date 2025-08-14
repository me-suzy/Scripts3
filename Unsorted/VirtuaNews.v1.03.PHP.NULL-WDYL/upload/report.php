<?php


require("global.php");

switch($action) {

case "";

  if (!isuserallowed($allowreport)) {
    standarderror("no_perms");
  }

  verifyid("news_comment",$id);

  if ($loggedin) {
    eval("\$loggedinuser = \"".returnpagebit("comments_logged_in")."\";");
  } else {
    $username = $cookie_comment_username;
    $useremail = $cookie_comment_useremail;
    eval("\$loggedinuser = \"".returnpagebit("comments_logged_out")."\";");
  }

  $navbar = makenavbar("Report Comment");
  include("static/sub_pages/comment_report_".$pagesetid.".php");

break;

case "sendreport":

  if (!isuserallowed($allowreport)) {
    standarderror("no_perms");
  }

  if ($loggedin) {
    $useremail = $userinfo[email];
  } else {
    $username = stripslashes($postername);
    $useremail = stripslashes($posteremail);

    if (!preg_match("/^(.+)@[a-zA-Z0-9-]+\.[a-zA-Z0-9.-]+$/si",$useremail)) {
      standarderror("invalid_email");
    }
  }

  if ((trim($username) == "") | (trim($useremail) == "") | (trim($reason) == "")) {
    standarderror("blank_field");
  }

  settype($id,"integer");
  if ($comment = query_first("SELECT news_comment.id,news_comment.newsid,news_comment.comment,news_news.catid FROM news_comment LEFT JOIN news_news ON news_comment.newsid = news_news.id WHERE news_comment.id = $id")) {

    $getstaff = query("SELECT ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,".$foruminfo[user_table].".".$foruminfo[email_field]." AS email FROM news_staff LEFT JOIN $foruminfo[user_table] ON ".$foruminfo[user_table].".".$foruminfo[userid_field]." = news_staff.userid WHERE (news_staff.caneditallcomments = 1) OR (canpost_".$cat_arr[$comment[catid]][topid]." = 1)");

    while ($staff = fetch_array($getstaff)) {
      $staffname = $staff[username];
      eval("\$email_msg = \"".returnpagebit("comments_report_email")."\";");

      @mail($staff[email],"Comment Reported At $sitename",$email_msg,"From: \"$sitename Mailer\" <$webmasteremail>");
    }

    standardredirect("comment_report","comments.php?id=$comment[newsid]&catid=$comment[catid]");
    exit;

  } else {
    standarderror("invalid_id");
  }

break;

default:
  standarderror("invalid_link");
}

/*======================================================================*\
|| ####################################################################
|| # File: report.php
|| ####################################################################
\*======================================================================*/

?>
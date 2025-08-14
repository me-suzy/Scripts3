<?php


require("global.php");

switch($action) {

case "profile":

  if ($use_forum) {
    header_redirect(eval("return \"$foruminfo[member_info_path]\";"),"View Member");
  }

  if (!$allowuserprofile) {
    standarderror("profile_disabled");
  }

  settype($id,"integer");

  if ($memberinfo = query_first("SELECT news_user.userid,news_user.username,news_user.showemail,news_user.email,news_user.homepage,news_user.icq,news_user.aim,news_user.yahoo,news_user.posts,news_user.joindate,news_user.allowpm,news_userfield.* FROM news_user LEFT JOIN news_userfield ON news_user.userid = news_userfield.userid WHERE news_user.userid = $id")) {

    $memberinfo[username] = htmlspecialchars($memberinfo[username]);
    if (($memberinfo[homepage] != "") & ($memberinfo[homepage] != "http://")) {
      eval("\$memberinfo[homepage] = \"".returnpagebit("member_profile_homepage")."\";");
    } else {
      $memberinfo[homepage] = "";
    }

    if ($memberinfo[showemail]) {
      eval("\$memberinfo[email] = \"".returnpagebit("member_profile_email")."\";");
    } else {
      $memberinfo[email] = "";
    }

    if ($memberinfo[allowpm] & isuserallowed($allowpms)) {
      eval("\$memberinfo[pmlink] = \"".returnpagebit("member_profile_pm")."\";");
    }

    $memberinfo[joindate] = date($joindateformat,$memberinfo[joindate]-$timeoffset);

    unset($customfields);
    $getcustom = query("SELECT id,title FROM news_profilefield WHERE hidden = 0 ORDER BY displayorder");
    while ($customfield = fetch_array($getcustom)) {
      $customfield[data] = $memberinfo[field.$customfield[id]];
      eval("\$customfields .= \"".returnpagebit("member_profile_custom")."\";");
    }

    $navbar = makenavbar("User Profile");
    include("static/sub_pages/member_profile_".$pagesetid.".php");
  } else {
    standarderror("invalid_id");
  }

break;

case "list";

  if ($use_forum) {
    header_redirect($foruminfo[member_list_path],"Member List");
  }

 if (!$enablememberlist) {
   standarderror("memberlist_disabled");
 }

  unset($sql_arr);
  unset($sql_condition);
  unset($memberlist);

  if ($name != "") {
    $sql_arr[] = "(username LIKE '%$name%')";
  }

  if ($email != "") {
    $sql_arr[] .= "(email LIKE '%$email%')";
  }

  if ($homepage != "") {
    $sql_arr[] .= "(homepage LIKE '%$homepage%')";
  }

  if ($icq != "") {
    $sql_arr[] .= "(icq LIKE '%$icq%')";
  }

  if ($aim != "") {
    $sql_arr[] .= "(aim LIKE '%$aim%')";
  }

  if ($yahoo != "") {
    $sql_arr[] .= "(yahoo LIKE '%$yahoo%')";
  }

  if ($posts_min != "") {
    $sql_arr[] .= "(posts > ".intval($posts_min).")";
  }

  if ($posts_max != "") {
    $sql_arr[] .= "(posts < ".intval($posts_max).")";
  }

  if ($sql_arr) {
    $sql_condition = " AND ".join(" AND ",$sql_arr);
  }

  switch($order) {
    case "joindate":
      $order_by = "joindate";
    break;
    case "username":
      $order_by = "username";
    break;
    case "posts":
      $order_by = "posts";
    break;
    default:
      $order_by = "username";
  }

  $order_dir = iif($dirc == "desc"," DESC","");

  settype($perpage,"integer");
  settype($pagenum,"integer");

  if ($perpage < 1) {
    $perpage = $memberlistperpage;
  }

  if ($pagenum < 1) {
    $pagenum = 1;
  }

  $numrecords = query_first("SELECT COUNT(userid) AS count FROM news_user WHERE (moderated = 1) AND (activated = 1)$sql_condition");

  $pagenav = pagenav($perpage,$pagenum,"member.php?action=list&order=$order_by&dirc=$dirc&name=$name&email=$email&homepage=$homepage&icq=$icq&aim=$aim&yahoo=$yahoo&posts_min=$posts_min&posts_max=$posts_max",$numrecords[count]);
  $getdata = query("SELECT userid,username,email,showemail,homepage,posts,joindate FROM news_user WHERE (moderated = 1) AND (activated = 1)$sql_condition ORDER BY $order_by$order_dir LIMIT ".($pagenum - 1) * $perpage.",$perpage");

  while ($member = fetch_array($getdata)) {

    $member[username] = htmlspecialchars($member[username]);
    if (($member[homepage] != "") & ($member[homepage] != "http://")) {
      eval("\$member[homepage] = \"".returnpagebit("member_list_record_homepage")."\";");
    } else {
      $member[homepage] = "";
    }

    if ($member[showemail]) {
      eval("\$member[email] = \"".returnpagebit("member_list_record_email")."\";");
    } else {
      $member[email] = "";
    }
    $member[joindate] = date($joindateformat,$member[joindate]-$timeoffset);
    eval("\$memberlist .= \"".returnpagebit("member_list_record")."\";");
  }

  if (!$memberlist) {
    eval("\$memberlist .= \"".returnpagebit("member_list_search_blank")."\";");
  }

  $navbar = makenavbar("Member List");
  include("static/sub_pages/member_list_".$pagesetid.".php");

break;

case "list_search":

  if ($use_forum) {
    header_redirect($foruminfo[member_search_path],"Member List");
  }

 if (!$enablememberlist) {
   standarderror("memberlist_disabled");
 }

  $navbar = makenavbar("Search","Member List","member.php?action=list");
  include("static/sub_pages/member_search_".$pagesetid.".php");

break;

case "email":

  if ($use_forum) {
    header_redirect(eval("return \"$foruminfo[member_email_path]\";"),"Email Member");
  }

  settype($id,"integer");

  if ($data_arr = query_first("SELECT username,email,showemail FROM news_user WHERE userid = $id")) {
    if ($data_arr[showemail]) {

      $navbar = makenavbar("Email Member");

      $name = htmlspecialchars($data_arr[username]);
      if (!isuserallowed($usememberemail)) {
        $email = $data_arr[email];
        include("static/sub_pages/member_email_disabled_".$pagesetid.".php");
      }

      if ($loggedin) {
        eval("\$loggedinuser = \"".returnpagebit("comments_logged_in")."\";");
      } else {
        eval("\$loggedinuser = \"".returnpagebit("comments_logged_out")."\";");
      }

      $referer = iif($HTTP_REFERER,xss_clean($HTTP_REFERER),"index.php");
      include("static/sub_pages/member_email_form_".$pagesetid.".php");
    } else {
      standarderror("email_hidden");
    }
  } else {
    standarderror("invalid_id");
  }

break;

case "email_send":

  if ($use_forum) {
    header_redirect(eval("return \"$foruminfo[member_email_path]\";"),"Email Member");
  }

  settype($id,"integer");
  if ($data_arr = query_first("SELECT username,email,showemail FROM news_user WHERE userid = $id")) {
    if ($data_arr[showemail]) {

      $navbar = makenavbar("Email Member");
      $name = $data_arr[username];
      $email = $data_arr[email];

      if (!isuserallowed($usememberemail)) {
        include("static/sub_pages/member_email_disabled_".$pagesetid.".php");
      }

      $name = stripslashes($name);
      $email_subject = stripslashes($email_subject);
      $email_msg = stripslashes($email_msg);

      eval("\$email_msg = \"".returnpagebit("member_email_content")."\";");
      eval("\$email_subject = \"".returnpagebit("member_email_subject")."\";");

      if (@mail($email,$email_subject,$email_msg,"From: $sitename Mailer <$webmasteremail>")) {
        standardredirect("member_email",iif($redirect,$redirect,"member.php?action=list"));
      } else {
        standarderror("email_failed");
      }

    } else {
      standarderror("email_hidden");
    }
  } else {
    standarderror("invalid_id");
  }

break;

default:
  standarderror("invalid_link");
}

/*======================================================================*\
|| ####################################################################
|| # File: member.php
|| ####################################################################
\*======================================================================*/

?>
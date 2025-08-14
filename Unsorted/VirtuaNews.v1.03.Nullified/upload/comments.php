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

require("global.php");

switch($action) {

case "";

  function returnedittext($comment) {

    global $commentedittext,$commentedittextadmin,$commentdate,$timeoffset,$foruminfo;
    static $editnames;

    if ($comment[editdate] & $commentedittext) {

      if (isset($editnames[$comment[edituserid]])) {
        $editedby[username] = $editnames[$comment[edituserid]][name];
      } else {

        $editedby = query_first("SELECT
          ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
          news_staff.id
          FROM ".$foruminfo[user_table]."
          LEFT JOIN news_staff ON ".$foruminfo[user_table].".".$foruminfo[userid_field]." = news_staff.userid
          WHERE ".$foruminfo[user_table].".".$foruminfo[userid_field]." = $comment[edituserid]");

        $editnames[$comment[edituserid]][name] = $editedby[username];
        $editnames[$comment[edituserid]][staff] = iif($editedby[id],1,0);
      }

      if (($editnames[$comment[edituserid]][staff] & $commentedittextadmin) | !$editnames[$comment[edituserid]][staff]) {
      	$editedby[username] = htmlspecialchars($editedby[username]);
        $editedby[date] = date($commentdate,$comment[editdate]-$timeoffset);
        eval("\$edittext= \"".returnpagebit("comments_comment_editedby")."\";");
      }
    }
    return $edittext;
  }

  settype($id,"integer");
  settype($replyid,"integer");

  $news = query_first("SELECT
    news_news.id,
    news_news.catid,
    news_news.title,
    news_news.mainnews,
    news_news.extendednews,
    news_news.logoimage,
    news_news.logoimageborder,
    news_news.commentcount,
    news_news.time,
    news_news.parsenewline,
    news_news.allowcomments,
    news_news.editstaffid,
    news_news.editdate,
    ".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid,
    ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username".iif($loggedin,",
    news_subscribe.emailupdate,
    news_subscribe.lastview,
    news_subscribe.id AS subscribeid","")."
    FROM news_news
    LEFT JOIN news_staff ON news_news.staffid = news_staff.id
    LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
    ".iif($loggedin,"LEFT JOIN news_subscribe ON news_subscribe.userid = $userid AND news_subscribe.newsid = $id","")."
    WHERE news_news.id = $id");

  if ($news) {

    if (!isuserallowed($cat_arr[$news[catid]][allowview])) {
      standarderror("category_hidden");
    }

    if ($goto == "next") {
      if ($next = query_first("SELECT id FROM news_news WHERE (time > $news[time]) AND (catid = $news[catid]) AND (display <> 0) AND (program = 0) ORDER BY time LIMIT 1")) {
        header_redirect("comments.php?catid=$news[catid]&id=$next[id]","Next Post");
      } else {
        standarderror("no_next_post");
      }

    } elseif ($goto == "previous") {
      if ($previous = query_first("SELECT id FROM news_news WHERE (time < $news[time]) AND (catid = $news[catid]) AND (display <> 0) AND (program = 0) ORDER BY time DESC LIMIT 1")) {
        header_redirect("comments.php?catid=$news[catid]&id=$previous[id]","Previous Post");
      } else {
        standarderror("no_previous_post");
      }
    }

    if (!empty($replyid) & !empty($enablecommentreply)) {

      if (!query_first("SELECT id FROM news_comment WHERE (parentid < 1) AND (id = $replyid)")) {
        standarderror("invalid_id");
      }
    }

    $catid = $news[catid];

    if ($news[allowcomments] & isuserallowed($cat_arr[$news[catid]][allowcomments]) & (($news[time] > (time()-($newsautolock*86400))) | empty($newsautolock))) {

      if (($user_allowsmilies & $loggedin) | ($staffid & $staff_allowsmilies) | (!$loggedin & $loggedout_allowsmilies)) {
        $smilies = getsmilietable();
      } else {
        eval("\$smilies = \"".returnpagebit("comments_smilies_disabled")."\";");
      }

      if (($user_allowqhtml & $loggedin) | ($staffid & $staff_allowqhtml) | (!$loggedin & $loggedout_allowqhtml)) {
        eval("\$autoparse_check = \"".returnpagebit("comments_add_autoparse_check")."\";");
      } else {
        $autoparse_check = "";
      }

      $qhtmlcode = returnqhtmllinks();

      if ($loggedin) {
        if ($enableemail) {
          if ($userinfo[emailnotification] | (!$loggedin & $emailreplaydefault)) {
            $checked[emailnotify] = " checked=\"checked\"";
          }
          eval("\$emailnotify_check = \"".returnpagebit("comments_add_notify_check")."\";");
        }
        if ($allowusersigs) {
          if (query_first("SELECT $foruminfo[userid_field] FROM " . iif($use_forum == 'vb_30', $foruminfo['usertext_table'], $foruminfo['user_table']) . " WHERE ($foruminfo[userid_field] = $userid) AND ($foruminfo[signature_field] <> '')")) {
            $checked[signature] = " checked=\"checked\"";
          }
          eval("\$signature_check = \"".returnpagebit("comments_add_signature_check")."\";");
        }
        eval("\$loggedinuser = \"".returnpagebit("comments_logged_in")."\";");
      } else {
        $username = $cookie_comment_username;
        $useremail = $cookie_comment_useremail;
        eval("\$loggedinuser = \"".returnpagebit("comments_logged_out")."\";");
      }

      if ($replyid) {
        eval("\$add_comment_table = \"".returnpagebit("comments_add_reply")."\";");
      } else {
        eval("\$add_comment_table = \"".returnpagebit("comments_add_new")."\";");
      }

    } elseif (!$news[allowcomments]) {
      eval("\$add_comment_table = \"".returnpagebit("comments_add_closed")."\";");
    } else {
      if (($news[time] < (time()-($newsautolock*86400))) & !empty($newsautolock)) {
        query("UPDATE news_news SET allowcomments = 0 WHERE id = $news[id]");
        eval("\$add_comment_table = \"".returnpagebit("comments_add_closed")."\";");
      } else {
        eval("\$add_comment_table = \"".returnpagebit("comments_add_disabled")."\";");
      }
    }

    if ($news[emailupdate] != "") {
      query("UPDATE news_subscribe SET lastview = '".time()."' , emailupdate = '1' WHERE id = $news[subscribeid]");
    }

    if ($news[editdate] & $newsedittext & ($newsedittexttime < (time() - $news[time]))) {
      $editedby = query_first("SELECT ".$foruminfo[user_table].".".$foruminfo[username_field]." FROM news_staff LEFT JOIN ".$foruminfo[user_table]." ON ".$foruminfo[user_table].".".$foruminfo[userid_field]." = news_staff.userid WHERE news_staff.id = $news[editstaffid]");
      $editedby[username] = htmlspecialchars($editedby[username]);
      $editedby[date] = date($newsdate,$news[editdate]-$timeoffset);
      eval("\$news[editedby] = \"".returnpagebit("comments_news_editedby")."\";");
    }

    $news[time] = date($newsdate,$news[time]-$timeoffset);

    $posterid = $news[userid];
    $postername = htmlspecialchars($news[username]);

    eval("\$news[poster] = \"".returnpagebit("misc_username_profile")."\";");

    $highlight = urldecode($highlight);

    $news[mainnews] = qhtmlparse($news[mainnews],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml,$news[parsenewline]);
    if (!empty($news[extendednews])) {
      $news[extendednews] = qhtmlparse($news[extendednews],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml,$news[parsenewline]);
    }

    if ($news[commentcount] == "1") {
      $commenttext1 = $commenttext1_s;
      $commenttext2 = $commenttext2_s;
    } else {
      $commenttext1 = $commenttext1_p;
      $commenttext2 = $commenttext2_p;
    }

    if ($news[logoimage]) {
      if ($news[logoimageborder]) {
        eval("\$news[logoimageborder] = \"".returnpagebit("comments_news_logo_border")."\";");
      } else {
        unset($news[logoimageborder]);
      }
      eval("\$news[logoimage] = \"".returnpagebit("comments_news_logo")."\";");
    } else {
      unset($news[logoimage]);
    }

    if ($userinfo[caneditallnews]) {
      if ($news[allowcomments]) {
        eval("\$news[oc_post] = \"".returnpagebit("comments_news_admin_close")."\";");
      } else {
        eval("\$news[oc_post] = \"".returnpagebit("comments_news_admin_open")."\";");
      }
    }

    unset($highlight);

    if ($enablecommentreply) {
      eval("\$news[oc_comments] = \"".returnpagebit("comments_news_oc_link")."\";");
    }

    if ($enableemail & $loggedin) {
      eval("\$news[subscribelink] = \"".returnpagebit("comments_news_subscribe_link")."\";");
    }

    eval("\$news_data = \"".returnpagebit("comments_news_data")."\";");
    $navbar = makenavbar($news[title],$cat_name,"index.php?action=news&amp;catid=$news[catid]");

    $news_title = $news[title];

    if ($loggedin) {
      $commentreplydefault = $userinfo[commentdefault];
    }

    $ids = array();

    $getdata = query("SELECT id FROM news_comment WHERE (newsid = $id) ORDER BY time");

    while ($comment = fetch_array($getdata)) {
      $ids[] = $comment['id'];
    }

    if (count($ids) > 0) {

      if (isset($oc_news)) {
        $oc_news = unserialize(stripslashes($oc_news));
      } else {
        $oc_news = array();
      }

      if ($nocid > 0) {

        $key = array_search($nocid, $oc_news);

        if ($key !== false) {
          unset($oc_news[$key]);
        } else {
          array_push($oc_news, $nocid);
        }

        updatecookie('oc_news', serialize($oc_news));
      }

      if (isset($oc_comments)) {
        $oc_comments = unserialize(stripslashes($oc_comments));
      } else {
        $oc_comments = array();
      }

      if ($ocid > 0) {

        $key = array_search($ocid, $oc_comments);

        if ($key !== false) {
          unset($oc_comments[$key]);
        } else {
          array_push($oc_comments, $ocid);
        }

        updatecookie('oc_comments', serialize($oc_comments));
      }

      $getdata = query("SELECT
        news_comment.id,
        news_comment.newsid,
        news_comment.comment,
        news_comment.ip,
        news_comment.showsig,
        news_comment.time,
        news_comment.parentid,
        news_comment.editlock,
        news_comment.edituserid,
        news_comment.editdate,
        news_comment.userid,
        news_comment.username AS name,
        news_comment.useremail,
        ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
        ".$foruminfo[user_table].".".$foruminfo[posts_field]." AS posts,
        " . iif($use_forum == 'vb_30', $foruminfo['usertext_table'], $foruminfo['user_table']) . ".".$foruminfo[signature_field]." AS signature,
        news_staff.id AS staffid
        FROM news_comment
        LEFT JOIN ".$foruminfo[user_table]." ON news_comment.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
" . iif($use_forum == 'vb_30', '   LEFT JOIN ' . $foruminfo['usertext_table'] . ' ON ' . $foruminfo['user_table'] . '.' . $foruminfo[userid_field] . ' = ' . $foruminfo['usertext_table'] . '.' . $foruminfo[userid_field]) . "
        LEFT JOIN news_staff ON ".$foruminfo[user_table].".".$foruminfo[userid_field]." = news_staff.userid
        WHERE (news_comment.id IN (" . join(',', $ids) . "))
        #ORDER BY news_comment.time");

      $canreport = isuserallowed($allowreport);
      $cancomment = ($enablecommentreply & isuserallowed($cat_arr[$catid][allowcomments]));

      $comments = array();

      while ($comment = fetch_array($getdata)) {

        if (($replyid > 0) && ($comment['id'] != $replyid) && ($comment['parentid'] != $replyid)) {
          continue;
        }

        unset($comment[editlink]);
        unset($comment[deletelink]);

        if (!isuserallowed($displayips) | !$comment[ip]) {
          $comment[ip] = $iphiddentext;
        }

        if (($userid == $comment[userid]) & $loggedin & $allowediting) {
          eval("\$comment[editlink] = \"".returnpagebit("comments_comment_edit_link")."\";");
        }

        if ($userinfo[caneditallcomments] | $userinfo[canpost_.$cat_arr[$catid][topid]]) {
          eval("\$comment[editlink] = \"".returnpagebit("comments_comment_admin_edit_link")."\";");
        }

        if ($userinfo[caneditallcomments]) {
          eval("\$comment[deletelink] = \"".returnpagebit("comments_comment_admin_del_link")."\";");
        }

        if ($canreport) {
          eval("\$comment[reportlink] = \"".returnpagebit("comments_comment_report_link")."\";");
        }

        if ($comment[userid]) {

          $posterid = $comment[userid];
          $postername = htmlspecialchars($comment[username]);
          eval("\$comment[username] = \"".returnpagebit("misc_username_profile")."\";");

          eval("\$comment[posts] = \"".returnpagebit("comments_comment_user_post_count")."\";");
          if ($comment[staffid]) {
            $comment[comment] = qhtmlparse($comment[comment],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
          } else {
            $comment[comment] = qhtmlparse($comment[comment],$user_allowhtml,$user_allowimg,$user_allowsmilies,$user_allowqhtml);
          }
          $comment[editedby] = returnedittext($comment);

          if (!empty($comment[signature]) & $allowusersigs & $comment[showsig] & $userinfo[viewsigs]) {
            if ($comment[staffid]) {
              $comment[signature] = qhtmlparse($comment[signature],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
            } else {
              $comment[signature] = qhtmlparse($comment[signature],$user_allowhtml,$user_allowimg,$user_allowsmilies,$user_allowqhtml);
            }

            eval("\$comment[signature] = \"".returnpagebit("comments_comment_signature")."\";");
          } else {

            unset($comment[signature]);
          }
        } else {

          $postername = htmlspecialchars($comment[name]);
          if ($comment[useremail]) {
            $posteremail = $comment[useremail];
            eval("\$comment[username] = \"".returnpagebit("misc_username_email")."\";");
          } else {
            eval("\$comment[username] = \"".returnpagebit("misc_username_noemail")."\";");
          }

          $comment[comment] = qhtmlparse($comment[comment],$loggedout_allowhtml,$loggedout_allowimg,$loggedout_allowsmilies,$loggedout_allowqhtml);
          $comment[editedby] = returnedittext($comment);
          unset($comment[signature]);
          unset($comment[posts]);
        }

        $comment[unixtime] = $comment[time];
        $comment[time] = date($commentdate,$comment[time]-$timeoffset);

        $comments[$comment[id]] = $comment;

      }

      function timesort($a, $b) {

        if ($a['unixtime'] == $b['unixtime']) {
          return 0;
        } elseif ($a['unixtime'] < $b['unixtime']) {
          return -1;
        } else {
          return 1;
        }

      }

      usort($comments, 'timesort');

      function order_comments($data, $parentid = 0, $commentnumber = '', $replycount = 0)
      {
        global $oc_comments, $oc_news,$commentreplydefault;

        $result = array();
        $count = 0;

        foreach ($data AS $val) {

          if (($val['parentid'] < 1) && ($parentid == 0)) {

            $count ++;

            $val['commentnumber'] = $count;

            $val['replycount'] = $val['parentid'] * -1;

            if ((in_array($val['id'], $oc_comments) || in_array($val['newsid'], $oc_news)) && ($val['replycount'] > 0)) {

              if ($commentreplydefault == "1") {

      	        $val['open'] = true;

                $result[] = $val;

                $result = array_merge($result, order_comments($data, $val['id'], $count, $val['replycount']));

              } else {

                $val['open'] = false;

                $result[] = $val;

              }

            } else {

              if ($commentreplydefault == "2") {

      	        $val['open'] = true;

                $result[] = $val;

                $result = array_merge($result, order_comments($data, $val['id'], $count, $val['replycount']));

              } else {

                $val['open'] = false;

                $result[] = $val;

              }

            }

          } elseif ($val['parentid'] == $parentid) {

            $count ++;

            $val['parentnumber'] = $commentnumber;
            $val['childnumber'] = $count;

            if ($count == $replycount) {
              $val['last'] = true;
            }

            $result[] = $val;

          }
        }

        return $result;

      }

      $comments = order_comments($comments);

      foreach ($comments AS $comment) {

        if ($comment['replycount'] > 0) {

          if ($comment['open']) {
            eval("\$comment[oclink] = \"".returnpagebit("comments_comment_close_link")."\";");
          } else {
            eval("\$comment[oclink] = \"".returnpagebit("comments_comment_open_link")."\";");
          }

          if ($cancomment) {
            eval("\$comment[replylink] = \"".returnpagebit("comments_comment_reply_link")."\";");
          }

          if ($comment[replycount] == 1) {
            $comment[replytext] = $commentreplytext_s;
          } else {
            $comment[replytext] = $commentreplytext_p;
          }

          $commentnum = $comment['commentnumber'];
          eval("\$comment_data .= \"".returnpagebit("comments_comment_data_parent")."\";");

        } elseif ($comment['parentid'] > 0) {
          $comment_child_num = $comment[childnumber];
          $comment_parent_num = $comment[parentnumber];

          if ($cancomment && $comment['last']) {
            eval("\$comment[parentreplylink] = \"".returnpagebit("comments_comment_reply_last")."\";");
          }

          eval("\$comment_data .= \"".returnpagebit("comments_comment_data_child")."\";");

        } else {

          if ($cancomment) {
            eval("\$comment[replylink] = \"".returnpagebit("comments_comment_reply_link")."\";");
          }

          $commentnum = $comment['commentnumber'];
          eval("\$comment_data .= \"".returnpagebit("comments_comment_data")."\";");

        }

      }
    }

    if ($comment_data) {
      eval("\$comment_data = \"".str_replace("\"","\\\"",str_replace("\\","\\\\",$comment_data))."\";");
      eval("\$comments = \"".returnpagebit("comments_comment_outer_table")."\";");
    }

    include("static/comment/comment_".$catid."_".$pagesetid.".php");
  } else {
    standarderror("invalid_id");
  }

break;

case "post":

  settype($id,"integer");
  settype($newsid,"integer");

  if ($id == "0") {
    if ($data_arr = query_first("SELECT catid FROM news_news WHERE id = $newsid")) {
      header("location:comments.php?id=$newsid&catid=$data_arr[catid]#add");
    } else {
      standarderror("invalid_id");
    }
  } elseif ($data_arr = query_first("SELECT news_comment.parentid,news_comment.newsid,news_news.catid FROM news_comment LEFT JOIN news_news ON news_comment.newsid = news_news.id WHERE news_comment.id = $id")) {
    $newsid = $data_arr[newsid];
    $parentid = $data_arr[parentid];
    $catid = $data_arr[catid];

    if ($parentid > 0) {

      if ($loggedin) {
        $commentreplydefault = $userinfo[commentdefault];
      }

      if (isset($oc_news)) {
        $oc_news = unserialize(stripslashes($oc_news));

        $key = array_search($newsid, $oc_news);

        if ($commentreplydefault == '1') {
          if ($key !== false) {
            unset($oc_news[$key]);
            updatecookie('oc_news', serialize($oc_news));
          }
        } else {
          if ($key === false) {
            array_push($oc_news, $newsid);
            updatecookie('oc_news', serialize($oc_news));
          }
        }
      }

      if (isset($oc_comments)) {
        $oc_comments = unserialize(stripslashes($oc_comments));

        $key = array_search($id, $oc_comments);

        if ($commentreplydefault == '1') {
          if ($key === false) {
            array_push($oc_comments, $parentid);
            updatecookie('oc_comments', serialize($oc_comments));
          }
        } else {
          if ($key !== false) {
            unset($oc_comments[$key]);
            updatecookie('oc_comments', serialize($oc_comments));
          }
        }
      } elseif ($commentreplydefault == '1') {
        updatecookie('oc_comments', serialize(array($parentid)));
      }
    }

    header_redirect("comments.php?id=$newsid&catid=$catid#comment$id","View Post");

  } else {
    standarderror("invalid_id");
  }

break;

case "addcomment":

  settype($id,"integer");

  $data_arr = query_first("SELECT catid,allowcomments FROM news_news WHERE id = $id");

  if (!$data_arr) {
    standarderror("invalid_id");
  }

  if (!isuserallowed($cat_arr[$data_arr[catid]][allowview])) {
    standarderror("category_hidden");
  }

  if (!$data_arr[allowcomments] | !isuserallowed($cat_arr[$data_arr[catid]][allowcomments])) {
    standarderror("comments_closed");
  }

  if (!$loggedin) {
    if ($check_username = query_first("SELECT $foruminfo[username_field] FROM $foruminfo[user_table] WHERE $foruminfo[username_field] = '$postername'")) {
      standarderror("comment_user_taken");
    }

    if (!preg_match("/^(.+)@[a-zA-Z0-9-]+\.[a-zA-Z0-9.-]+$/si",$posteremail) & !empty($posteremail)) {
      standarderror("invalid_email");
    }

    if (($commentuserlimit > 0) & (strlen($postername) > $commentuserlimit)) {
      standarderror("user_long");
    }

    if (($commentemaillimit > 0) & (strlen($posteremail) > $commentemaillimit)) {
      standarderror("email_long");
    }

    if (trim($postername) == "") {
      standarderror("blank_field");
    }

    $username = $postername;
    $useremail = $posteremail;
  }

  if (trim($content) == "") {
    standarderror("blank_field");
  }

  if (($commentchrlimit > 0) & (strlen(stripslashes($content)) > $commentchrlimit)) {
    standarderror("comment_long");
  }

  if (!$cat_arr[$catid]) {
    standarderror("invalid_cat");
  }

  settype($parentid,"integer");
  if (isset($parentid) & ($parentid != 0) & $enablecommentreply) {
    if ($temp = query_first("SELECT id FROM news_comment WHERE (parentid < 1) AND (id = $parentid)")) {
      $where_sql = "\n      AND ((news_comment.parentid = $parentid) OR (news_comment.id = $parentid))";
    } else {
      standarderror("invalid_id");
    }
  }

  if ($parseurl & (($user_allowqhtml & $loggedin) | ($staffid & $staff_allowqhtml) | (!$loggedin & $loggedout_allowqhtml))) {
    $content = autoparseurl($content);
  }

  if ($maximages != 0) {
    if ($loggedin) {
      if ($staffid) {
        $commentparsed = qhtmlparse($content,$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
      } else {
        $commentparsed = qhtmlparse($content,$user_allowhtml,$user_allowimg,$user_allowsmilies,$user_allowqhtml);
      }
    } else {
      $commentparsed = qhtmlparse($content,$loggedout_allowhtml,$loggedout_allowimg,$loggedout_allowsmilies,$loggedout_allowqhtml);
    }
    if (countchar($commentparsed,"<img") > $maximages) {
      standarderror("too_many_images");
    }
  }

  if ($logips == 3) {
    $ip = $HTTP_SERVER_VARS[REMOTE_ADDR];
  } elseif (($logips == 2) & (!$staffid)) {
    $ip = $HTTP_SERVER_VARS[REMOTE_ADDR];
  } elseif (($logips == 1) & (!$loggedin)) {
    $ip = $HTTP_SERVER_VARS[REMOTE_ADDR];
  } else {
    $ip = "";
  }

  $posttime = time();

  query("UPDATE news_news SET commentcount = commentcount+'1' , lastcommentuser = '".addslashes($username)."' WHERE id = $id");
  query("INSERT INTO news_comment VALUES (NULL,'$id','".addslashes($username)."','$userid','$useremail','$ip','$content','$showsig','$posttime','$parentid','0','0','0')");

  $insertid = getlastinsert();

  if ($parentid) {
    query("UPDATE news_comment SET parentid = parentid-'1' WHERE id = $parentid");
  }

  emailusers($id,$username);

  if ($loggedin) {
    query("UPDATE $foruminfo[user_table] SET $foruminfo[posts_field] = $foruminfo[posts_field] + '1' WHERE $foruminfo[userid_field] = $userid");

    if (($checksubscribe = query_first("SELECT id FROM news_subscribe WHERE (newsid = $id) AND (userid = $userid)")) & ($emailnotify != 1)) {
      query("DELETE FROM news_subscribe WHERE id = $checksubscribe[id]");
    } elseif (!$checksubscribe & $emailnotify) {
      query("INSERT INTO news_subscribe VALUES (NULL,'$id','$userid','".time()."','1')");
    }
  } else {
    updatecookie("cookie_comment_username",$username);
    updatecookie("cookie_comment_useremail",$useremail);
  }

  include("includes/writefunctions.php");
  include("includes/adminfunctions.php");
  writeallpages();

  if ($parentid > 0) {

    if ($loggedin) {
      $commentreplydefault = $userinfo[commentdefault];
    }

    if (isset($oc_news)) {
      $oc_news = unserialize(stripslashes($oc_news));

      $key = array_search($id, $oc_news);

      if ($commentreplydefault == '1') {
        if ($key !== false) {
          unset($oc_news[$key]);
          updatecookie('oc_news', serialize($oc_news));
        }
      } else {
        if ($key === false) {
          array_push($oc_news, $id);
          updatecookie('oc_news', serialize($oc_news));
        }
      }
    }

    if (isset($oc_comments)) {
      $oc_comments = unserialize(stripslashes($oc_comments));

      $key = array_search($id, $oc_comments);

      if ($commentreplydefault == '1') {
        if ($key === false) {
          array_push($oc_comments, $parentid);
          updatecookie('oc_comments', serialize($oc_comments));
        }
      } else {
        if ($key !== false) {
          unset($oc_comments[$key]);
          updatecookie('oc_comments', serialize($oc_comments));
        }
      }
    } elseif ($commentreplydefault == '1') {
      updatecookie('oc_comments', serialize(array($parentid)));
    }
  }

  standardredirect("comment_new","comments.php?catid=$catid&id=$id#comment$insertid");

break;

case "subscribe":

  if (!$loggedin | !$enableemail) {
    standarderror("no_perms");
  }

  settype($id,"integer");

  if ($post = query_first("SELECT catid FROM news_news WHERE id = $id")) {
    if (!query_first("SELECT id FROM news_subscribe WHERE (newsid = $id) AND (userid = $userid)")) {
      query("INSERT INTO news_subscribe VALUES (NULL,'$id','$userid','".time()."','1')");
    }

    standardredirect("comment_sub","comments.php?catid=$post[catid]&id=$id");
  } else {
    standarderror("invalid_id");
  }

break;

case "unsubscribe":

  settype($id,"integer");

  if ($post = query_first("SELECT news_subscribe.id,news_news.catid FROM news_subscribe LEFT JOIN news_news ON news_news.id = $id WHERE (news_subscribe.newsid = $id) AND (news_subscribe.userid = $userid)")) {
    query("DELETE FROM news_subscribe WHERE id = $post[id]");
    standardredirect("comment_usub","comments.php?catid=$post[catid]&id=$id");
  } else {
    standarderror("invalid_id");
  }

break;

default:
  standarderror("invalid_link");
}

/*======================================================================*\
|| ####################################################################
|| # File: comments.php
|| ####################################################################
\*======================================================================*/

?>
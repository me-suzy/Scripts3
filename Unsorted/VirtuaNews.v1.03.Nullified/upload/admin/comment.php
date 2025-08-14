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

if (preg_match("/(admin\/comment.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

function makecommenttable($data) {

  global $browser,$admindirectory;

  return "      <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">
        <tr>
          <td><input type=\"checkbox\" name=\"commentdelete[$data[id]]\" value=\"$data[id]\"></td>
          <td width=\"50%\"".iif($browser == "MSIE"," onclick=\"commenttoggle(comment_$data[id],image_$data[id])\" onmouseover=\"this.setAttribute('bgcolor','#cccccc',0)\" onmouseout=\"this.setAttribute('bgcolor','',0)\" style=\"cursor:hand\"><img src=\"$admindirectory/icons/plus.gif\" name=\"image_$data[id]\" alt=\"\" />",">")." #$data[commentnum]".iif($data[childnum],".$data[childnum]")." - Posted By $data[username] On $data[time]".iif($data[replycount]," - $data[replycount] Replies")."</td>
          <td width=\"50%\"><a href=\"admin.php?action=comment_edit&id=$data[id]\">Edit</a> | <a href=\"admin.php?action=comment_delete&id=$data[id]\">Delete</a></td>
        </tr>
        <tr>
          <td></td>
          <td colspan=\"2\"><div id=\"comment_$data[id]\"".iif($browser == "MSIE"," style=\"display:none\"","").">$data[comment]</div></td>
        </tr>
      </table>
";

}

updateadminlog(iif($id,"id = $id",""));

switch ($action) {

case "comment":

  if (!$userinfo[caneditallcomments]) {
    adminerror("Cannot Edit Comment","You cannot edit this comment as you do not have the permission to do so.");
  }

  settype($newsid,"integer");
  if ($news_data = query_first("SELECT title FROM news_news WHERE id = $newsid")) {

    $javascript = "  <script type=\"text/javascript\">

  function commenttoggle(comment,image) {
    if (comment.style.display == 'none') {
      comment.style.display = '';
      image.src = '$admindirectory/icons/minus.gif';
    } else {
      comment.style.display = 'none';
      image.src = '$admindirectory/icons/plus.gif';
    }
  }

  function ca(theform) {
    var i=0;
    for (var i=0;i<theform.elements.length;i++) {
      if ((theform.elements[i].name != 'checkall') && (theform.elements[i].type=='checkbox')) {
        theform.elements[i].checked = theform.checkall.checked;
      }
    }
  }

  </script>";

    echohtmlheader($javascript);
    echoformheader("comment_mass_delete","View News Comments",1);
    updatehiddenvar("newsid",$newsid);
    echotabledescription("This page will list all the comments for the news post &quot;$news_data[title]&quot;.  You can use this page to edit or delete indervidual comments by using the links next to them, or you can delete multiple comments by checking the box next to each comment you wish to delete and pressing submit.",1);
    echotabledescription("<input type=\"checkbox\" name=\"checkall\" value=\"1\" onclick=\"ca(this.form)\"> Check/Uncheck All Boxes",1);

    $getdata = query("
      SELECT
      news_comment.id,
      news_comment.username AS name,
      news_comment.useremail,
      news_comment.comment,
      news_comment.ip,
      news_comment.time,
      news_comment.parentid,
      ".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid,
      ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
      news_staff.id AS staffid
      FROM news_comment
      LEFT JOIN $foruminfo[user_table] ON news_comment.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
      LEFT JOIN news_staff ON ".$foruminfo[user_table].".".$foruminfo[userid_field]." = news_staff.userid
      WHERE news_comment.newsid = $newsid
      ORDER BY news_comment.time");

    while ($data_arr = fetch_array($getdata)) {

      if (!$data_arr[ip]) {
        $data_arr[ip] = $iphiddentext;
      }

      if ($data_arr[userid]) {
        $data_arr[username] = returnlinkcode(htmlspecialchars($data_arr[username]),"member.php?action=profile&id=$data_arr[userid]",1);
      } elseif ($data_arr[useremail]) {
        $comment[username] = returnlinkcode(htmlspecialchars($data_arr[name]),"mailto:$data_arr[useremail]");
      } else {
        $comment[username] = htmlspecialchars($data_arr[name]);
      }

      if ($data_arr[userid]) {
        if ($data_arr[staffid]) {
          $data_arr[comment] = qhtmlparse($data_arr[comment],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
        } else {
          $data_arr[comment] = qhtmlparse($data_arr[comment],$user_allowhtml,$user_allowimg,$user_allowsmilies,$user_allowqhtml);
        }
      } else {
        $data_arr[comment] = qhtmlparse($comment[comment],$loggedout_allowhtml,$loggedout_allowimg,$loggedout_allowsmilies,$loggedout_allowqhtml);
      }

      $data_arr[time] = date($commentdate,$data_arr[time]-$timeoffset);

      if ($data_arr[parentid] < 1) {
        $c_numbers ++;

        $data_arr[commentnum] = $c_numbers;

        if ($data_arr[parentid] < 0) {
          $data_arr[replycount] = $data_arr[parentid]*(-1);

          $comments .= makecommenttable($data_arr);

          for ($i=1;$i<=$data_arr[replycount];$i++) {
            $comments .= "\$child_".$data_arr[commentnum]."[$i]";
          }

          $p_numbers[$data_arr[id]] = $data_arr[commentnum];

        } else {
          $comments .= makecommenttable($data_arr);
        }
      } else {
        $s_numbers[$data_arr[parentid]] ++;
        $data_arr[childnum] = $s_numbers[$data_arr[parentid]];
        $data_arr[commentnum] = $p_numbers[$data_arr[parentid]];
        $varname = "child_".$data_arr[commentnum];

        ${$varname}[$data_arr[childnum]] .= makecommenttable($data_arr);

      }

    }

    if ($comments) {

      eval("\$comments = \"".str_replace("\"","\\\"",str_replace("\\","\\\\",$comments))."\";");

    }

    echotabledescription("\n$comments    ",1);
    echoformfooter();
    echohtmlfooter();

  } else {
    adminerror("Invalid ID","You have specified an invlalid id.");
  }

break;

case "comment_edit":

  settype($id,"integer");
  if ($data_arr = query_first("SELECT
    news_comment.newsid,
    news_comment.userid,
    news_comment.username AS name,
    news_comment.useremail,
    news_comment.ip,
    news_comment.comment,
    news_comment.time,
    news_news.catid,
    ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username
    FROM news_comment
    LEFT JOIN news_news ON news_comment.newsid = news_news.id
    LEFT JOIN $foruminfo[user_table] ON ".$foruminfo[user_table].".".$foruminfo[userid_field]." = news_comment.userid
    WHERE news_comment.id = $id")) {

    if (!$userinfo[canpost_.$cat_arr[$data_arr[catid]][topid]] | !$userinfo[caneditallcomments]) {
      adminerror("Cannot Edit Comment","You cannot edit this comment as you do not have the permission to do so.");
    }

    $data_arr[name] = htmlspecialchars($data_arr[name]);
    if ($data_arr[userid]) {
      $data_arr[name] = returnlinkcode(htmlspecialchars($data_arr[username]),"member.php?action=profile&id=$data_arr[userid]",1);
    } elseif ($data_arr[useremail]) {
      $data_arr[name] = returnlinkcode($data_arr[name],"mailto:$data_arr[useremail]");
    }

    if (!$comment_data[ip] | !$displayips) {
      $comment_data[ip] = "Hidden";
    }

    echohtmlheader("qhtmlcode");
    echoformheader("comment_update","Edit news comment");
    updatehiddenvar("id",$id);
    updatehiddenvar("referer",iif(preg_match("/(comments.php)/i",$HTTP_REFERER),"comments.php","admin.php"));
    echotablerow("Posted By:",$data_arr[name]);
    echotablerow("Poster Ip:",$data_arr[ip]);
    echotablerow("Posted At:",date('H:i:s o\n d-M-Y',$data_arr[time]-$timeoffset));
    if ($staff_allowqhtml) {
      echoqhtmlhelp();
    }
    echotextareacode("Comment:","content",$data_arr[comment],10,110);
    echoformfooter();
    echohtmlfooter();

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "comment_update":

  settype($id,"integer");
  if ($data_arr = query_first("SELECT news_comment.newsid,news_comment.time,news_news.catid FROM news_comment LEFT JOIN news_news ON news_comment.newsid = news_news.id WHERE news_comment.id = $id")) {

    if (!$userinfo[canpost_.$cat_arr[$data_arr[catid]][topid]] | !$userinfo[caneditallcomments]) {
      adminerror("Cannot Edit Comment","You cannot edit this comment as you do not have the permission to do so.");
    }

    if ($content == "") {
      adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
    }

    if ($maximages != 0) {

      $commentparsed = qhtmlparse($content,$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
      if (countchar($commentparsed,"<img") > $maximages) {
        adminerror("Too Many images","You can only have a maximum of $maximages images in comments");
      }
    }

    query("UPDATE news_comment SET comment = '$content' , editlock = '".iif($commenteditlock,1,0)."'".iif($commentedittexttime < (time() - $data_arr[time])," , edituserid = '$userid' , editdate = '".time()."'","")." WHERE id = $id");

    if (($referer == "admin.php") & $userinfo[caneditallcomments]) {
      echoadminredirect("admin.php?action=comment&newsid=$data_arr[newsid]");
    } else {
      echoadminredirect("comments.php?action=post&id=$id");
    }
    exit;
  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "comment_delete":

  if (!$userinfo[caneditallcomments]) {
    adminerror("Cannot Delete Comment","You cannot delete this comment as you do not have the permission to do so.");
  }

  verifyid("news_comment",$id);
  echodeleteconfirm("news comment","comment_kill",$id,"","&referer=".iif(preg_match("/(comments.php)/i",$HTTP_REFERER),"comments.php","admin.php"));

break;

case "comment_kill":

  if (!$userinfo[caneditallcomments]) {
    adminerror("Cannot Delete Comment","You cannot delete this comment as you do not have the permission to do so.");
  }

  unset($childsdeleted);
  unset($user_ids);

  $commentsdeleted = 0;

  settype($id,"integer");
  if ($comment_data = query_first("SELECT news_comment.newsid,news_news.catid FROM news_comment LEFT JOIN news_news ON news_comment.newsid = news_news.id WHERE news_comment.id = $id")) {

    $getdata = query("SELECT parentid,userid FROM news_comment WHERE (id = $id) OR (parentid = $id)");

    while ($data_arr = fetch_array($getdata)) {
      $commentsdeleted ++;

      if ($data_arr[parentid]) {
        $childsdeleted[$data_arr[parentid]] ++;
      }

      if ($data_arr[userid]) {
        $user_ids[$data_arr[userid]] ++;
      }
    }

    if ($childsdeleted) {
      foreach ($childsdeleted AS $key => $val) {
        query("UPDATE news_comment SET parentid = parentid+'$val' WHERE id = $key");
      }
    }

    if ($user_ids) {
      foreach ($user_ids AS $key => $val) {
        query("UPDATE $foruminfo[user_table] SET $foruminfo[posts_field] = $foruminfo[posts_field] -'$val' WHERE $foruminfo[userid_field] = $key");
      }
    }

    query("DELETE FROM news_comment WHERE (id = $id) OR (parentid = $id)");

    $lastuser = query_first("SELECT
      news_comment.userid,
      news_comment.username AS name,
      ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username
      FROM news_comment
      LEFT JOIN ".$foruminfo[user_table]." ON news_comment.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
      WHERE (news_comment.newsid = $comment_data[newsid])
      ORDER BY news_comment.time DESC
      LIMIT 1");

    query("UPDATE news_news SET commentcount = commentcount-'$commentsdeleted' , lastcommentuser = '".iif($lastuser[username],$lastuser[username],$lastuser[name])."' WHERE id = $comment_data[newsid]");

    writeallpages();

    if (($referer == "admin.php") & $userinfo[caneditallcomments]) {
      echoadminredirect("admin.php?action=comment&newsid=$comment_data[newsid]");
    } else {
      echoadminredirect("comments.php?id=$comment_data[newsid]&catid=$comment_data[catid]");
    }
    exit;

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "comment_mass_delete":

  if (!$userinfo[caneditallcomments]) {
    adminerror("Cannot Delete Comments","You cannot delete these comment as you do not have the permission to do so.");
  }

  verifyid("news_news",$newsid);

  if ($commentdelete) {
    $commentids = join(",",$commentdelete);
  }

  echodeleteconfirm("comments","comment_mass_kill",$commentids," This will delete ALL the comments you checked, and update the user post counts as appropriate.","&newsid=$newsid");

break;

case "comment_mass_kill":

  if (!$userinfo[caneditallcomments]) {
    adminerror("Cannot Delete Comments","You cannot delete these comment as you do not have the permission to do so.");
  }

  verifyid("news_news",$newsid);

  unset($childsdeleted);
  unset($user_ids);

  $commentsdeleted = 0;

  if ($id) {
    $id = explode(",",$id);
    foreach ($id AS $key => $val) {
      $comment_ids[$key] = intval($val);
    }
  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

  $comment_ids = join(",",$comment_ids);

  $getdata = query("SELECT parentid,userid FROM news_comment WHERE (id IN ($comment_ids)) OR (parentid IN ($comment_ids))");

  while ($data_arr = fetch_array($getdata)) {
    $commentsdeleted ++;

    if ($data_arr[parentid]) {
      $childsdeleted[$data_arr[parentid]] ++;
    }

    if ($data_arr[userid]) {
      $user_ids[$data_arr[userid]] ++;
    }
  }

  if ($childsdeleted) {
    foreach ($childsdeleted AS $key => $val) {
      query("UPDATE news_comment SET parentid = parentid+'$val' WHERE id = $key");
    }
  }

  if ($user_ids) {
    foreach ($user_ids AS $key => $val) {
      query("UPDATE $foruminfo[user_table] SET $foruminfo[posts_field] = $foruminfo[posts_field] -'$val' WHERE $foruminfo[userid_field] = $key");
    }
  }

  query("DELETE FROM news_comment WHERE (id IN ($comment_ids)) OR (parentid IN ($comment_ids))");

  $lastuser = query_first("SELECT
    news_comment.userid,
    news_comment.username AS name,
    ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username
    FROM news_comment
    LEFT JOIN ".$foruminfo[user_table]." ON news_comment.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
    WHERE (news_comment.newsid = $newsid)
    ORDER BY news_comment.time DESC
    LIMIT 1");

  query("UPDATE news_news SET commentcount = commentcount-'$commentsdeleted' , lastcommentuser = '".iif($lastuser[username],$lastuser[username],$lastuser[name])."' WHERE id = $newsid");

  writeallpages();

  echoadminredirect("admin.php?action=comment&newsid=$newsid");
  exit;

break;

default:
  adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/comment.php
|| ####################################################################
\*======================================================================*/

?>
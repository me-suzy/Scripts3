<?php


if (preg_match("/(admin\/news.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

updateadminlog(iif($id,"id = $id",""));

function makelogoselect($value="none.gif") {

  global $logo,$newslogouptype;

  echo "  <tr>\n    <td>Display Logo:</td>\n    <td>\n      <table width=\"100%\">\n        <tr  style=\"vertical-align:middle\">\n          <td style=\"width:225px\">\n            <select name=\"logo\" size=\"8\" class=\"form\" onchange=\"logoswap(this.value,logopreview)\" style=\"width:200px\">\n";


  echo "              <option value=\"\">None</option>\n";

  $validtypes = explode(" ",$newslogouptype);
  foreach ($validtypes AS $val) {
    $extensions[$val] = 1;
  }

  if ($handle = opendir("images/news/logos/")) {

    while (false !== ($file = readdir($handle))) {
      $filetype = explode(".",$file);
      if ($extensions[$filetype[(count($filetype)-1)]]) {
        if (($file != ".") & ($file != "..") & ($file != "none.gif")) {
          $file_arr[] = $file;
        }
      }
    }
  }

  closedir($handle);

  if ($file_arr) {

    sort($file_arr);

    foreach ($file_arr as $file_name) {

      if (($file_name == $value) | ($file_name == $logo)) {
        $selected = " selected=\"selected\"";
      } else {
        $selected = "";
      }

      echo "              <option value=\"$file_name\"$selected>$file_name</option>\n";
    }

  }
  echo "            </select>\n          </td>\n          <td style=\"width:50px\">Preview:</td>\n          <td><img src=\"images/news/logos/$value\" id=\"logopreview\" alt=\"\" /></td>\n        </tr>\n      </table>\n    </td>\n  </tr>\n";

}

function makejshelp() {

  echo "
  <tr>
    <td>Insert html:</td>
    <td>
      <a href=\"javascript:void(0)\" onclick=\"inserttag('b')\" title=\"Add bold text\">Bold</a>
      | <a href=\"javascript:void(0)\" onclick=\"inserttag('i')\" title=\"Add italic text\">Italic</a>
      | <a href=\"javascript:void(0)\" onclick=\"inserttag('u')\" title=\"Add underlined text\">Underline</a>
      | <a href=\"javascript:void(0)\" onclick=\"insertlink('url')\" title=\"Add a web address\">URL</a>
      | <a href=\"javascript:void(0)\" onclick=\"insertlink('email')\" title=\"Add an email address\">Email</a>
      | <a href=\"javascript:void(0)\" onclick=\"inserttag('img')\" title=\"Add an image\">Image</a>
      | <a href=\"javascript:void(0)\" onclick=\"dolist()\" title=\"Add a bulleted list\">List</a>
      | <a href=\"javascript:void(0)\" onclick=\"inserttag('quote')\" title=\"Add a quote by someone\">Quote</a>
    </td>
  </tr>
  <tr>
    <td>Insert icons:</td>
    <td>
      <a href=\"javascript:void(0)\" onclick=\"addicon('source.gif','News source')\" title=\"Add news source\">Source</a>
      | <a href=\"javascript:void(0)\" onclick=\"addicon('software.gif','Download')\" title=\"Add download icon\">Download</a>
      | <a href=\"javascript:void(0)\" onclick=\"addicon('view.gif','View')\" title=\"Add link to something\">View</a>
      | <a href=\"javascript:void(0)\" onclick=\"addicon('picture.gif','Screenshot')\" title=\"Add a link to a screenshot\">Screen</a>
      | <a href=\"javascript:void(0)\" onclick=\"addicon('video.gif','Video')\" title=\"Add a link to a video\">Video</a>
      | <a href=\"javascript:void(0)\" onclick=\"addicon('music.gif','Music')\" title=\"Add a link to some music\">Music</a>
    </td>
  </tr>
  <tr>
    <td>Insert into:</td>
    <td>
      <input type=\"radio\" name=\"insertinto\" value=\"mainnews\" checked=\"checked\" /> Main news
      <input type=\"radio\" name=\"insertinto\" value=\"extendednews\" /> Extended news
    </td>
  </tr>\n";
}

function makestickyselect($title,$name,$value=0) {

  echo "  <tr>
    <td>$title</td>
    <td>
      <input type=\"radio\" name=\"$name\" value=\"0\"".iif($value == 0," checked=\"checked\"")." /> Do not Stick
      <input type=\"radio\" name=\"$name\" value=\"2\"".iif($value == 2," checked=\"checked\"")." /> Stick At Top
      <input type=\"radio\" name=\"$name\" value=\"1\"".iif($value == 1," checked=\"checked\"")." /> Stick At Bottom
    </td>
  </tr>
";

}

if (!$canpostnews) {
  adminerror("You are not allowed to access this section","You do not have permission to do this action");
}

if ($action == "news_list") {

  if (!$userinfo[caneditallnews]) {
    $staff = $staffid;
  }

  if (!$order_by) {
    $order_by = "time";
  }

  settype($perpage,"integer");
  settype($pagenum,"integer");

  if ($pagenum < 1) {
    $pagenum = 1;
  }

  if ($order_direction) {
    $direction = "";
  } else {
    $direction = " DESC";
  }

  switch($order_by) {
    case "title":
      $order = "ORDER BY news_news.title$direction,news_news.stickypost DESC,news_news.time DESC";
    break;
    case "category":
      $order = "ORDER BY news_category.name$direction,news_news.stickypost DESC,news_news.time DESC";
    break;
    case "poster":
      $order = "ORDER BY ".$foruminfo[user_table].".".$foruminfo[username_field]."$direction,news_news.stickypost DESC,news_news.time DESC";
    break;
    default:
      $order = "ORDER BY news_news.stickypost DESC,news_news.time$direction";
  }

  if ($title != "") {
    $sql_arr[] = "(news_news.title LIKE '%".urldecode($title)."%')";
  }

  if ($mainnews != "") {
    $sql_arr[] = "(news_news.mainnews LIKE '%".urldecode($mainnews)."%')";
  }

  if ($extendednews != "") {
    $sql_arr[] = "(news_news.extendednews LIKE '%".urldecode($extendednews)."%')";
  }

  settype($staff,"integer");
  if ($staff) {
    $sql_arr[] = "(news_news.staffid = $staff)";
  }

  settype($catid,"integer");
  if ($catid) {
    if ($userinfo[canpost_.$cat_arr[$catid][topid]]) {
      $sql_arr[] = "(news_news.catid = $catid)";
    }
  }

  if ($sql_arr) {
    $sql_condition = "WHERE ".join(" AND ",$sql_arr);
  }

  echohtmlheader();
  echotableheader("Recent News Posts");
  echotabledescription("This page will give a short list of the most recent posts that you are allowed to edit.  To view other posts please view the news archive <a href=\"admin.php?action=news_archive\">here</a>");

  $getdata = query("SELECT
    news_news.id,
    news_news.catid,
    news_news.title,
    news_news.stickypost,
    ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username
    FROM news_news
    LEFT JOIN news_staff ON news_news.staffid = news_staff.id
    LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
    LEFT JOIN news_category ON news_news.catid = news_category.id
    $sql_condition
    $order
    LIMIT ".iif($perpage > 0,($pagenum - 1) * $perpage.",$perpage",25));

  $tablerows = returnminitablerow("<b>Title</b>","<b>Category</b>","<b>Posted By</b>","<b>Options</b>");

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow(iif($data_arr[stickypost],"STICKY: ","").returnlinkcode($data_arr[title],"admin.php?action=news_edit&id=$data_arr[id]"),$cat_arr[$data_arr[catid]][name],htmlspecialchars($data_arr[username]),returnlinkcode("Edit","admin.php?action=news_edit&id=$data_arr[id]")." | ".returnlinkcode("Delete","admin.php?action=news_delete&id=$data_arr[id]")." | ".returnlinkcode("View","comments.php?catid=$data_arr[catid]&id=$data_arr[id]",1).iif($userinfo[canmaintaindb]," |".returnlinkcode("Prune","admin.php?action=maintain_news_c&id=$data_arr[id]"),"").iif($userinfo[canpost_.$cat_arr[$data_arr[catid]][topid]] | $userinfo[caneditallcomments]," |".returnlinkcode("Comments","admin.php?action=comment&newsid=$data_arr[id]"),""));
  }

  echotabledescription("\n".returnminitable($tablerows,0,100)."      ");

  if ($perpage > 0) {
    $numrecords = query_first("SELECT COUNT(id) AS count FROM news_news $sql_condition");
    $pagenav = pagenav($perpage,$pagenum,"admin.php?action=news_list&title=".urlencode($title)."&mainnews=".urlencode($mainnews)."&extendednews=".urlencode($extendednews)."&catid=$catid&staff=$staff&order_by=$order_by&order_direction=$order_direction",$numrecords[count]);

    echotabledescription($pagenav);
  }

  echotablefooter();

  echoformheader("news_list","Search For Post");
  echotabledescription("You can use this from to search through the news posts for a specific post to edit.  To continue just enter the conditions you wish to search for (blank fields will be ignored) and press submit.");
  echoinputcode("Title:","title",$title,40,1);
  echoinputcode("Main News:","mainnews",$mainnews,40,1);
  echoinputcode("Extended News:","extendednews",$extendednews,40,1);
  echonewscatselect("View Posts From Category:","catid",$catid,0,1);

  if ($userinfo[caneditallnews]) {

    $userselect = "\n      <select name=\"staff\" class=\"form\">\n        <option value=\"0\">---------All---------</option>\n";
    $getstaff = query("SELECT news_staff.id,".$foruminfo[user_table].".".$foruminfo[username_field]." AS username FROM news_staff LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]." ORDER BY ".$foruminfo[user_table].".".$foruminfo[username_field]);

    while ($staff_arr = fetch_array($getstaff)) {
      $userselect .= "        <option value=\"$staff_arr[id]\"".iif($staff == $staff_arr[id]," selected=\"selected\"","").">$staff_arr[username]</option>\n";
    }
    $userselect .= "      </select>\n    ";

    echotablerow("View posts by:",$userselect);

  }
  echoinputcode("Posts to display per page:","perpage",iif($perpage > 0,$perpage,25));
  echotablerow("Order posts by:","\n      <select name=\"order_by\" class=\"form\">
        <option value=\"time\"".iif($order_by == "time"," selected=\"selected\"","").">Time</option>
        <option value=\"category\"".iif($order_by == "category"," selected=\"selected\"","").">Category</option>
        <option value=\"poster\"".iif($order_by == "poster"," selected=\"selected\"","").">Poster</option>
        <option value=\"title\"".iif($order_by == "title"," selected=\"selected\"","").">Title</option>
      </select>\n    ");
  echoyesnocode("Order direction:","order_direction",$order_direction,"Ascending","Decending");
  echoformfooter();
  echohtmlfooter();

}

if ($action == "news_archive") {

  $firstpost = query_first("SELECT MAX(time) AS time FROM news_news");
  $firstdate = date("j F Y",iif($firstpost[time] > 0,$firstpost[time],time())+$timeoffset);

  unset($post_arr);
  unset($tablerows);

  if (empty($startdate)) {
    $startdate = strtotime("00:00:00 $firstdate")-(5*86400)+86400;
  } else {
    settype($startdate,"integer");
    if (($startdate <= 0) | ($startdate > 2145916800)) {
      $startdate = strtotime("00:00:00 $firstdate")-(5*86400)+86400;
    }
  }

  $enddate = $startdate+(5*86400);

  $getdata = query("SELECT
    news_news.id,
    news_news.title,
    news_news.catid,
    news_news.stickypost,
    DATE_FORMAT(FROM_UNIXTIME(news_news.time),'%D %M %Y') AS day,
    ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username
    FROM news_news
    LEFT JOIN news_staff ON news_news.staffid = news_staff.id
    LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
    WHERE (news_news.time < $enddate)
    AND (news_news.time > $startdate)".iif(!$userinfo[caneditallnews],"\n      AND (staffid = $staffid)")."
    ORDER BY time DESC");

  while ($data_arr = fetch_array($getdata)) {
    $post_arr[$data_arr[day]] .= returnminitablerow(iif($data_arr[sticky],"<b>STICKY:</b> ").$data_arr[title],$cat_arr[$data_arr[catid]][name],htmlspecialchars($data_arr[username]),returnlinkcode("Edit","admin.php?action=news_edit&id=$data_arr[id]")." | ".returnlinkcode("Delete","admin.php?action=news_delete&id=$data_arr[id]")." | ".returnlinkcode("View","comments.php?catid=$data_arr[catid]&id=$data_arr[id]",1).iif($userinfo[canmaintaindb]," |".returnlinkcode("Prune","admin.php?action=maintain_news_c&id=$data_arr[id]")).iif($userinfo[canpost_.$cat_arr[$data_arr[catid]][topid]] | $userinfo[caneditallcomments]," |".returnlinkcode("Comments","admin.php?action=comment&newsid=$data_arr[id]")));
  }

  if ($post_arr) {
    foreach ($post_arr AS $key => $val) {
      $tablerows .= "        <tr>\n          <td colspan=\"4\"><b>$key</td>\n        </tr>\n$val";
    }
  }

  if (($startdate+(5*86400)) <= strtotime("23:59:59 $firstdate")) {
    $pagenav = " |".returnlinkcode("Next 5 days","admin.php?action=news_archive&startdate=".($startdate+(5*86400)));
  }

  $pagenav = returnlinkcode("Previous 5 days","admin.php?action=news_archive&startdate=".($startdate-(5*86400)))."$pagenav";

  echohtmlheader();
  echotableheader("News Archive",1);
  echotabledescription("Below is a list of the posts made by you (or all if you have permission) listed by date, for the time period ".date("jS F Y",$startdate-$timeoffset)." to ".date("jS F Y",$enddate-$timeoffset-1)." GMT (inclusive).",1);
  echotabledescription("\n".returnminitable($tablerows,0,100)."      ");
  echotabledescription($pagenav,1);
  echotablefooter();
  echohtmlfooter();

}

if ($action == "news_new") {

  if ($parseurl & $staff_allowqhtml) {
    $mainnews = autoparseurl($mainnews);
    $extendednews = autoparseurl($extendednews);
  }

  if ($submit) {
    if (($mainnews == "") | ($title == "")) {
      adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
    }

    if (empty($cat_arr[$catid])) {
      adminerror("Invalid Category","You have specified an invalid category.");
    }

    if (!$userinfo[canpost_.$cat_arr[$catid][topid]]) {
      adminerror("Cannot Post","You cannot post in this category as you do not have permission to do so.");
    }

    if ($time == 1) {
      $time = time();
    } else {
      $temp = explode(":",$date_time);
      $time = mktime($temp[0],$temp[1],0,$datemonth,$dateday,$dateyear);
      $time = ($time + $timeoffset);
    }

    query("UPDATE news_staff SET newsposts = newsposts+'1' WHERE id = $staffid");
    query("UPDATE news_category SET posts = posts+'1' WHERE id = $catid");
    query("INSERT INTO news_news VALUES (NULL,'$catid','$title','$mainnews','$extendednews','$logo','$logoimageborder','0','','$time','$staffid','".iif($userinfo[canmakesticky],$stickypost,0)."','$parsenewline','".iif($time < time(),0,1)."','$display','".iif($userinfo[caneditallnews],$enablecomments,1)."','0','0')");
    $newsid = getlastinsert();

    if ($subscribe) {
      query("INSERT INTO news_subscribe VALUES (NULL,'$newsid','$userid','".time()."','1')");
    }

    writeallpages();

    echoadminredirect("admin.php?action=news_list");
    exit;
  } else {

    $title = stripslashes($title);
    $mainnews = stripslashes($mainnews);
    $extendednews = stripslashes($extendednews);
    $logo = iif($logo,stripslashes($logo),"none.gif");
    $date_date = mktime(0,0,0,$datemonth,$dateday,$dateyear);

    $postpreview = qhtmlparse($mainnews,$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml,$parsenewline)."<br /><br />".qhtmlparse($extendednews,$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml,$parsenewline);

    $action = "news_add";
  }
}

if ($action == "news_add") {

  echohtmlheader("adminjs");

  if ($postpreview) {
    echotableheader("Post Preview");
    echotabledescription($postpreview,1);
    echotablefooter();
  } else {
    $logo = "none.gif";
    $date_time = date("H:i",time()-$timeoffset);
    $date_date = (time()-$timeoffset);
    $time = 1;
    $parseurl = 1;
    $parsenewline = 1;
    $display = 1;
    $enablecomments = 1;
    $subscribe = 0;
  }

  echoformheader("news_new","Add News");
  echotabledescription("Use this section to post news to the site.");
  echonewscatselect("Category:","catid",$catid);
  echoinputcode("Title:","title",$title);
  echotextareacode("Main news:","mainnews",$mainnews,20,100);
  if ($staff_allowqhtml) {
    makejshelp();
  }
  echotextareacode("Extended news:","extendednews",$extendednews,20,100,1,20);
  makelogoselect($logo);
  echoyesnocode("Border around logo:","logoimageborder",$logoimageborder);
  echoyesnocode("Time Of Post:","time",$time,"Now","Past/Future");
  echodatecode("Date:","date",$date_date,"","&nbsp;&nbsp;&nbsp;Set the time in the past or future");
  echoinputcode("Time:","date_time",$date_time,8,0,"","&nbsp;&nbsp;&nbsp;Use the 24-hour clock, format hh:mm");

  if ($userinfo[canmakesticky]) {
    makestickyselect("Make Sticky Post:","stickypost",$stickypost);
  }

  if ($staff_allowqhtml) {
    echoyesnocode("Auto Parse URL's:","parseurl",$parseurl);
  }

  echoyesnocode("Auto Parse New Lines To &lt;br /&gt;:","parsenewline",$parsenewline);
  echoyesnocode("Display:","display",$display);

  if ($userinfo[caneditallnews]) {
    echoyesnocode("Allow Comments:","enablecomments",$enablecomments);
  }

  echoyesnocode("Subscribe To News Post:","subscribe",$subscribe);
  echoformfooter(2,"Submit",1);
  echohtmlfooter();

}

if ($action == "news_update") {

  if ($parseurl & $staff_allowqhtml) {
    $mainnews = autoparseurl($mainnews);
    $extendednews = autoparseurl($extendednews);
  }

  if ($submit) {
    settype($id,"integer");
    if ($data_arr = query_first("SELECT catid,display,time,staffid FROM news_news WHERE id = $id")) {

      if (!$userinfo[caneditallnews]) {
        if ($data_arr[staffid] != $staffid) {
          adminerror("Cannot Edit Post","You only have permission to edit news posts made by yourself.");
        }
      }

      if (($mainnews == "") | ($title == "")) {
        adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
      }

      if (empty($cat_arr[$catid])) {
        adminerror("Invalid Category","You have specified an invalid category.");
      }

      $temp = explode(":",$date_time);
      $time = mktime($temp[0],$temp[1],0,$datemonth,$dateday,$dateyear);
      $time = ($time + $timeoffset);

      query("UPDATE news_news SET
        catid = '$catid',
        title = '$title',
        mainnews = '$mainnews',
        extendednews = '$extendednews',
        time = '$time',
        logoimage = '$logo',
        logoimageborder = '$logoimageborder',
        display = '$display',
        parsenewline = '$parsenewline',
        program = '".iif($time < time(),0,1)."'".iif($commentedittexttime < (time() - $data_arr[time]),",\n      editstaffid = '$staffid',\n      editdate = '".time()."'","").iif($userinfo[caneditallnews],",\n        allowcomments = '$enablecomments'","").iif($userinfo[canmakesticky],",\n        stickypost = '$stickypost'","")."
        WHERE id = $id");

      if ($display != $data_arr[display]) {
        query("UPDATE news_category SET posts = posts".iif($display,"+","-")."'1' WHERE id = $data_arr[catid]");
      }

      if ($catid != $data_arr[catid]) {
        query("UPDATE news_category SET posts = posts+'1' WHERE id = $catid");
        query("UPDATE news_category SET posts = posts-'1' WHERE id = $data_arr[catid]");
      }

      writeallpages();

      echoadminredirect("admin.php?action=news_list");
      exit;
    } else {
      adminerror("Invalid ID","You have specified an invalid id.");
    }
  } else {

    $mainnews = stripslashes($mainnews);
    $extendednews = stripslashes($extendednews);
    $title = stripslashes($title);
    $logo = iif($logo,stripslashes($logo),"none.gif");

    $postpreview = qhtmlparse($mainnews,$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml)."<br /><br />".qhtmlparse($extendednews,$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
    $action = "news_edit";
   }

}

if ($action == "news_edit") {

  settype($id,"integer");
  if ($data_arr = query_first("SELECT news_news.catid,news_news.title,news_news.mainnews,news_news.extendednews,news_news.logoimage,news_news.logoimageborder,news_news.time,news_news.staffid,news_news.stickypost,news_news.parsenewline,news_news.display,news_news.allowcomments,".$foruminfo[user_table].".".$foruminfo[username_field]." AS username FROM news_news LEFT JOIN news_staff ON news_news.staffid = news_staff.id LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]." WHERE news_news.id = $id")) {

    if (!$userinfo[caneditallnews]) {
      if ($data_arr[staffid] != $staffid) {
          adminerror("Cannot Edit Post","You only have permission to edit news posts made by yourself.");
      }
    }

    echohtmlheader("adminjs");

    if ($postpreview) {
      echotableheader("Post Preview");
      echotabledescription($postpreview,1);
      echotablefooter();
      $date_date = mktime(0,0,0,$datemonth,$dateday,$dateyear);
    } else {
      $catid = $data_arr[catid];
      $title = $data_arr[title];
      $mainnews = $data_arr[mainnews];
      $extendednews = $data_arr[extendednews];
      $logo = iif($data_arr[logoimage],$data_arr[logoimage],"none.gif");
      $logoimageborder = $data_arr[logoimageborder];
      $date_date = $data_arr[time]-$timeoffset;
      $date_time = date("H:i",$data_arr[time]-$timeoffset);
      $stickypost = $data_arr[stickypost];
      $parseurl = 1;
      $parsenewline = $data_arr[parsenewline];
      $display = $data_arr[display];
      $enablecomments = $data_arr[allowcomments];
    }

    echoformheader("news_update","Edit News Posts");
    updatehiddenvar("id",$id);
    echotabledescription("You may use this form to edit the news post as you wish, press submit to save your changes.");
    echonewscatselect("Category:","catid",$catid);
    echotablerow("Posted by:",htmlspecialchars($data_arr[username]));
    echoinputcode("Title:","title",$title);
    echotextareacode("Main news:","mainnews",$mainnews,20,100);
    if ($staff_allowqhtml) {
      makejshelp();
    }
    echotextareacode("Extended news:","extendednews",$extendednews,20,100,1);
    makelogoselect($logo);
    echoyesnocode("Border around logo:","logoimageborder",$logoimageborder);
    echodatecode("Date:","date",$date_date);
    echoinputcode("Time:","date_time",$date_time,8,0,"","&nbsp;&nbsp;&nbsp;Use the 24-hour clock, format hh:mm");

    if ($userinfo[canmakesticky]) {
      makestickyselect("Make Sticky Post:","stickypost",$stickypost);
    }

    if ($staff_allowqhtml) {
      echoyesnocode("Auto Parse URL's:","parseurl",$parseurl);
    }

    echoyesnocode("Auto Parse New Lines To &lt;br /&gt;:","parsenewline",$parsenewline);
    echoyesnocode("Display:","display",$display);

    if ($userinfo[caneditallnews]) {
      echoyesnocode("Allow Comments:","enablecomments",$enablecomments);
    }

    echoformfooter(2,"Submit",1);
    echohtmlfooter();
  } else {
    adminerror("Invalild ID","You have specified an invalid id.");
  }
}

if ($action == "news_delete") {

  settype($id,"integer");
  if ($data_arr = query_first("SELECT staffid FROM news_news WHERE id = $id")) {

    if (!$userinfo[caneditallnews]) {
      if ($data_arr[staffid] != $staffid) {
        adminerror("Cannot Delete Post","You only have permission to delete news posts made by yourself.");
      }
    }

    echodeleteconfirm("news post","news_kill",$id);
  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }
}

if ($action == "news_kill") {

  settype($id,"integer");
  if ($data_arr = query_first("SELECT catid,staffid FROM news_news WHERE id = $id")) {

    if (!$userinfo[caneditallnews]) {
      if ($data_arr[staffid] != $staffid) {
        adminerror("Cannot Delete Post","You only have permission to delete news posts made by yourself.");
      }
    }

    $getcomments = query("SELECT
      COUNT(".$foruminfo[user_table].".".$foruminfo[userid_field].") AS count,
      ".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid
      FROM news_comment
      INNER JOIN $foruminfo[user_table] ON news_comment.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
      WHERE news_comment.newsid = $id
      GROUP BY (".$foruminfo[user_table].".".$foruminfo[userid_field].")");

    unset($user_ids);

    while ($comment_arr = fetch_array($getcomments)) {
      $user_ids[$comment_arr[userid]] = $comment_arr[count];
    }

    query("DELETE FROM news_news WHERE id = $id");
    query("DELETE FROM news_comment WHERE newsid = $id");
    query("DELETE FROM news_subscribe WHERE newsid = $id");
    query("UPDATE news_staff SET newsposts = newsposts-'1' WHERE id = $data_arr[staffid]");
    query("UPDATE news_category SET posts = posts-'1' WHERE id = $data_arr[catid]");

    if ($user_ids) {
      foreach ($user_ids AS $key => $val) {
        query("UPDATE $foruminfo[user_table] SET $foruminfo[posts_field] = $foruminfo[posts_field] - '$val' WHERE $foruminfo[userid_field] = $key");
      }
    }

    writeallpages();

    echoadminredirect("admin.php?action=news_list");
    exit;

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }
}

if ($action == "news_close_post") {

  if (!$userinfo[caneditallnews]) {
    adminerror("You are not allowed to access this section","You do not have permission to do this action");
  }

  settype($id,"integer");
  if ($data_arr = query_first("SELECT catid FROM news_news WHERE id = $id")) {

    query("UPDATE news_news SET allowcomments = '0' WHERE id = $id");
    writeallpages();
    echoadminredirect("comments.php?id=$id&catid=$data_arr[catid]");
    exit;

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }
}

if ($action == "news_open_post") {

  if (!$userinfo[caneditallnews]) {
    adminerror("You are not allowed to access this section","You do not have permission to do this action");
  }

  settype($id,"integer");
  if ($data_arr = query_first("SELECT catid FROM news_news WHERE id = $id")) {

    query("UPDATE news_news SET allowcomments = '1' WHERE id = $id");
    writeallpages();
    echoadminredirect("comments.php?id=$id&catid=$data_arr[catid]");
    exit;

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

}

/*======================================================================*\
|| ####################################################################
|| # File: admin/news.php
|| ####################################################################
\*======================================================================*/

?>
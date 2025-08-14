<?php


if (preg_match("/(admin\/category.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

updateadminlog(iif($id,"id = $id",""));

function makethemeselectcode($title,$name="themeid",$value=0) {

  echo "  <tr>\n    <td>$title</td>\n    <td>\n      <select name=\"$name\" class=\"form\">\n";

  $getdata = query("SELECT id,title FROM news_theme ORDER BY title");
  while ($data_arr = fetch_array($getdata)) {
    echo "        <option value=\"$data_arr[id]\"".iif($value == $data_arr[id]," selected=\"selected\"").">$data_arr[title]</option>\n";
  }

  echo "      </select>\n    </td>\n  </tr>\n";

}


function returncatoptions($data,$level=0) {
  global $modname,$expand,$subexpand,$botexpand;

  if ($level == 1) {
    return "<li><b>$data[name]</b> ".iif($data[children],iif($expand == $data[id],returnlinkcode("Collapse","admin.php?action=category")." |",returnlinkcode("Expand","admin.php?action=category&expand=$data[id]")." |")).returnlinkcode("Edit","admin.php?action=category_edit&id=$data[id]")." |".returnlinkcode("Delete","admin.php?action=category_delete&id=$data[id]")." |".returnlinkcode("Add Sub Cat","admin.php?action=category_add&parentid=$data[id]")." |".returnlinkcode("Edit Announcement","admin.php?action=category_ann_edit&catid=$data[id]")."</li>\n";
  } elseif ($level == 2) {
    return "<li><b>$data[name]</b> ".iif($data[children],iif($subexpand == $data[id],returnlinkcode("Collapse","admin.php?action=category&expand=$expand")." |",returnlinkcode("Expand","admin.php?action=category&expand=$expand&subexpand=$data[id]")." |")).returnlinkcode("Edit","admin.php?action=category_edit&id=$data[id]")." |".returnlinkcode("Delete","admin.php?action=category_delete&id=$data[id]")." |".returnlinkcode("Add Sub Cat","admin.php?action=category_add&parentid=$data[id]")." |".returnlinkcode("Edit Announcement","admin.php?action=category_ann_edit&catid=$data[id]")."</li>\n";
  } elseif ($level == 3) {
    return "<li><b>$data[name]</b> ".iif($data[children],iif($botexpand == $data[id],returnlinkcode("Collapse","admin.php?action=category&expand=$expand&subexpand=$subexpand")." |")).returnlinkcode("Edit","admin.php?action=category_edit&id=$data[id]")." |".returnlinkcode("Delete","admin.php?action=category_delete&id=$data[id]")." |".returnlinkcode("Edit Announcement","admin.php?action=category_ann_edit&catid=$data[id]")."</li>\n";
  }
}

function makeparentselect($title,$name,$value) {

  global $id;

  echo "  <tr>\n    <td>$title</td>\n    <td>\n      <select name=\"$name\" class=\"form\">\n";

  echo "        <option value=\"\">None</option>\n";

  $gettop = query("SELECT id,parentid,name,children FROM news_category WHERE parentid = 0 ORDER BY displayorder");

  while ($top_arr = fetch_array($gettop)) {

    if (($top_arr[id] != $id)) {
      echo "        <option value=\"$top_arr[id]\"".iif($value == $top_arr[id]," selected=\"selected\"","").">$top_arr[name]</option>\n";
    }

    if ($top_arr[children]) {
      $getmid = query("SELECT id,name,parentid,children FROM news_category WHERE parentid = $top_arr[id] ORDER BY displayorder");
      while ($mid_arr = fetch_array($getmid)) {
        if (($mid_arr[parentid] != $id) & ($mid_arr[id] != $id)) {
          echo "        <option value=\"$mid_arr[id]\"".iif($value == $mid_arr[id]," selected=\"selected\"","").">$top_arr[name] &gt;&gt; $mid_arr[name]</option>\n";
        }
      }
    }
  }

  echo "      </select>\n    </td>\n  </tr>\n";
}

switch ($action) {

case "category":

  echohtmlheader();
  echotableheader("Edit News Categories",1);
  echotabledescription("Use this page to edit the news categories on the site.  To continue use the appropriate links next to the category you wish to edit.",1);
  echotabledescription(returnlinkcode("Add New Category","admin.php?action=category_add"),1);

  $getdata = query("SELECT id,name,children FROM news_category WHERE parentid = 0 ORDER BY displayorder");

  $tablerows = "<ul>\n";

  while ($data_arr = fetch_array($getdata)) {

    $tablerows .= returncatoptions($data_arr,1);

    if ($expand == $data_arr[id]) {
      $tablerows .= "<ul>\n";

      if ($data_arr[children]) {
        $getsubcats = query("SELECT id,name,children FROM news_category WHERE parentid = $data_arr[id] ORDER BY displayorder");
        while ($sub_arr = fetch_array($getsubcats)) {
          $tablerows .= returncatoptions($sub_arr,2);

          if ($subexpand == $sub_arr[id]) {
            $tablerows .= "<ul>\n";

            if ($sub_arr[children]) {
              $getbotcats = query("SELECT id,name,children FROM news_category WHERE parentid = $sub_arr[id] ORDER BY displayorder");
              while ($bot_arr = fetch_array($getbotcats)) {
                $tablerows .= returncatoptions($bot_arr,3);
              }
              $tablerows .= "</ul>\n";
            }
            $tablerows .= "</ul>\n";
          }
        }
      }
      $tablerows .= "</ul>\n";
    }
  }

  $tablerows .= "</ul>\n";

  echotabledescription("\n$tablerows    ",1);

  echotablefooter();
  echohtmlfooter();

break;

case "category_add":

  echohtmlheader("qhtmlcode");
  echoformheader("category_new","Add News Category");
  echoinputcode("Category Name:","name");
  if ($staff_allowqhtml) {
    echoqhtmlhelp();
  }
  echotextareacode("Category Description:","content","",5,50,1);
  echoinputcode("Category Image:","image","",40,1,50);
  makethemeselectcode("Default theme:","defaulttheme");
  echoyesnocode("Force users to use default theme:","forcetheme",0);
  makeparentselect("Parent Category:","parentid",$parentid);
  echopermissionselect("Allow Comments By:","allowcomments",3);
  echopermissionselect("Allow Category To Be Viewed By:","allowview",3);
  echoyesnocode("Show site stats?","showsitestats",1);

  if ($use_forum) {
    echoyesnocode("Show forum stats?","showforumstats",1);
    echoyesnocode("Show forum options?","showforumoptions",1);
  }

  echoyesnocode("Show polls?","showpoll",1);
  echoyesnocode("Show announcement?","showannouncement",1);
  echoyesnocode("Show Sub Categories:<br />If set to yes then this category will also display the latest news from its sub categories as well on the main page.","showsubcats",0);
  echoinputcode("Display Order:","displayorder",1,10);
  echoyesnocode("Display Category On Main Page:<br />If set to no then this category will only be displayed through searches, the archive and the comment pages.","displaymain",1);

  foreach ($cat_arr as $key => $val) {
    $checkboxes .= returncheckboxcode("recentpost[$key]",1,$val[name],iif($key == $defaultcategory,1,0));
  }

  echotablerow("Show recent posts for which categories?<br /><span class=\"red\">(you may select multiple ones)</span>","\n$checkboxes    ");

  if ($userinfo[caneditstaff]) {
    unset($checkboxes);
    $getdata = query("SELECT news_staff.id,".$foruminfo[user_table].".".$foruminfo[username_field]." AS username FROM news_staff LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]." ORDER BY ".$foruminfo[user_table].".".$foruminfo[username_field]);
    while ($data_arr = fetch_array($getdata)) {
      $checkboxes .= returncheckboxcode("staff[$data_arr[id]]","1",$data_arr[username],iif($data_arr[id] == $staffid,1,0));
    }
    echotablerow("Staff Permitted To Post:","\n$checkboxes    ");
  }

  echoformfooter();
  echohtmlfooter();

break;

case "category_new":

  if (($name == "") | ($displayorder == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  verifyid("news_theme",$defaulttheme);

  settype($parentid,"integer");

  if ($parentid) {
    $data_arr = query_first("SELECT id,parentid FROM news_category WHERE id = $parentid");
    if (!$data_arr) {
      adminerror("Invalid ID","You have specified an invalid id.");
    }

    if ($data_arr[parentid] != 0) {
      if (query_first("SELECT id FROM news_category WHERE (id = $data_arr[parentid]) AND (parentid <> 0)")) {
        adminerror("Invalid Parent Category","The parent category you specified is all ready a child to another sub category and therefore cannot have any child categories of its own.");
      }
    }
    query("UPDATE news_category SET children = children+'1' WHERE id = $parentid");
  }

  foreach ($cat_arr AS $key => $val) {
    $recentpost_new .= iif($recentpost[$key],iif($recentpost_new,",$key",$key),"");
  }

  query("INSERT INTO news_category VALUES(NULL,'$parentid','0','$name','$content','$image','0','$allowcomments','$allowview','$showsitestats','$showforumstats','$showforumoptions','$showpoll','$showannouncement','$showsubcats','$defaulttheme','$forcetheme','$recentpost_new','1','$displayorder','$displaymain','0')");
  $newid = getlastinsert();

  $temp_menu = @join("",@file("pages/default/menu_".$defaultcategory.".vnp"));
  writepagebit("pages/default/menu_".$newid.".vnp",$temp_menu);

  unset($data);
  unset($temp_menu);

  query("INSERT INTO news_page VALUES (NULL,'-1','menu_$newid','This page is the menu for the category called <b>$name</b>.','1')");

  query("INSERT INTO news_announcement VALUES ('$newid','','','')");

  if ($parentid == 0) {
    query("ALTER TABLE news_staff ADD canpost_$newid TINYINT(1) DEFAULT '0' NOT NULL");
    query("OPTIMIZE TABLE news_staff");
    if ($userinfo[caneditstaff]) {
      if (count($staff) > 0) {
        foreach ($staff AS $key => $val) {
          if ($val == "1") {
            query("UPDATE news_staff SET canpost_$newid = '1' WHERE id = $key");
          }
        }
      }
    }
  }

  if ($parentid) {
    if ($data_arr[parentid]) {
      $expandurl = "&expand=$data_arr[parentid]&subexpand=$parentid&botexpand=$newid";
    } else {
      $expandurl = "&expand=$parentid&subexpand=$newid";
    }
  } else {
    $expandurl = "&expand=$newid";
  }

  unset($cat_arr);
  $cat_arr = getcat_arr();

  writeallpages();

  echoadminredirect("admin.php?action=category$expandurl");

break;

case "category_edit":

  settype($id,"integer");
  if ($data_arr = query_first("SELECT parentid,name,description,image,allowcomments,allowview,showsitestats,showforumstats,showforumoptions,showpoll,showannouncement,showsubcats,defaulttheme,forcetheme,recentpost,display,displayorder,displaymain FROM news_category WHERE id = $id")) {

    echohtmlheader("qhtmlcode");
    echoformheader("category_update","Update News Category");
    updatehiddenvar("id",$id);
    echotabledescription("You can use this page to edit news categories on the site.  To continue alter the settings as you wish and press submit to save your changes.");
    echotabledescription(returnlinkcode("Edit Announcement","admin.php?action=category_ann_edit&catid=$id"));
    echoinputcode("Category Name:","name",$data_arr[name]);
    if ($staff_allowqhtml) {
      echoqhtmlhelp();
    }
    echotextareacode("Category Description:","content",$data_arr[description],5,50,1);
    echoinputcode("Category Image:","image",$data_arr[image],40,1);
    makethemeselectcode("Default theme:","defaulttheme",$data_arr[defaulttheme]);
    echoyesnocode("Force users to use default theme:","forcetheme",$data_arr[forcetheme]);
    makeparentselect("Parent Category:","parentid",$data_arr[parentid]);
    echopermissionselect("Allow Comments By:","allowcomments",$data_arr[allowcomments]);
    echopermissionselect("Allow Category To Be Viewed By:","allowview",$data_arr[allowview]);

    echoyesnocode("Show site stats:","showsitestats",$data_arr[showsitestats]);

    if ($use_forum) {
      echoyesnocode("Show forum stats:","showforumstats",$data_arr[showforumstats]);
      echoyesnocode("Show forum options:","showforumoptions",$data_arr[showforumoptions]);
    }

    echoyesnocode("Show polls:","showpoll",$data_arr[showpoll]);
    echoyesnocode("Show announcement:","showannouncement",$data_arr[showannouncement]);
    echoyesnocode("Show Sub Categories:<br />If set to yes then this category will also display the latest news from its sub categories as well on the main page.","showsubcats",$data_arr[showsubcats]);
    echoinputcode("Display Order:","displayorder",$data_arr[displayorder],10);
    echoyesnocode("Display Category:","display",$data_arr[display]);
    echoyesnocode("Display Category On Main Page:<br />If set to no then this category will only be displayed through searches, the archive and the comment pages.","displaymain",$data_arr[displaymain]);

    $recentpost = array();
    unset($checkboxes);

    $recentpost_temp = explode(",",$data_arr[recentpost]);
    foreach ($recentpost_temp AS $val) {
      $recentpost[$val] = 1;
    }

    foreach ($cat_arr as $key => $val) {
      $checkboxes .= "      <input type=\"checkbox\" name=\"recentpost[$key]\" value=\"1\"".iif($recentpost[$key]," checked=\"checked\"","")."> $val[name]<br />\n";
    }

    echotablerow("Show recent posts for which categories?<br /><span class=\"red\">(you may select multiple ones)</span>","\n$checkboxes    ");

    echoformfooter();
    echohtmlfooter();

  } else {
    adminerror("invalid_id");
  }

break;

case "category_update":

  if (($name == "") | ($displayorder == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  verifyid("news_category",$id);
  verifyid("news_theme",$defaulttheme);

  settype($parentid,"integer");

  $level = 1;

  if ($parentid) {
    $data_arr = query_first("SELECT id,parentid FROM news_category WHERE id = $parentid");
    if (!$data_arr) {
      adminerror("Invalid ID","You have specified an invalid id");
    }
    $level ++;
    if ($data_arr[parentid] != 0) {
      $level ++;
    }
  }

  $old_data = query_first("SELECT children,parentid,name FROM news_category WHERE id = $id");

  if ($old_data[parentid] != $parentid) {
    $getsub = query("SELECT id,children FROM news_category WHERE parentid = $id");
    if (countrows($getsub)) {
      $level ++;
    }
    while ($sub_arr = fetch_array($getsub)) {
      if ($parentid == $sub_arr[id]) {
        adminerror("Invalid Parent","You have specified that you wish to make this category a child to a category which is a child to itself, you cannot do this.");
      } elseif ($sub_arr[children]) {
        $bot_ids .= iif($bot_ids,",'$sub_arr[id]'","'$sub_arr[id]'");
      }
    }

    if ($bot_ids) {
      $getbot = query("SELECT id FROM news_category WHERE parentid IN ($bot_ids)");
      while ($bot_arr = fetch_array($getbot)) {
        if ($parentid == $bot_arr[id]) {
          adminerror("Invalid Parent","You have specified that you wish to make this category a child to a category which is a child to itself, you cannot do this.");
        }
      }
      $level ++;
    }

    if ($level > 3) {
      adminerror("Category Move Error","By moving this category to the one you specified it will result in a category that is more than 3 levels deep, the files module only supports 3 levels of categories and you cannot continue.  Please check that the category you are moving does not have any sub categories.");
    }

    query("UPDATE news_category SET children = children-'1' WHERE id = $old_data[parentid]");
    query("UPDATE news_category SET children = children+'1' WHERE id = $parentid");

    if (($old_data[parentid] == 0) & ($parentid != 0)) {
      query("ALTER TABLE news_staff DROP canpost_$id");
      query("OPTIMIZE TABLE news_staff");
    } elseif (($old_data[parentid] > 0) & ($parentid == 0)) {
      query("ALTER TABLE news_staff ADD canpost_$id TINYINT(1) DEFAULT '0' NOT NULL");
      query("OPTIMIZE TABLE news_staff");
    }
  }

  query("UPDATE news_page SET description = 'This page is the menu for the category called <b>$name</b>.' WHERE title = 'menu_$id'");

  unset($recentpost_temp);
  foreach ($cat_arr AS $key => $val) {
    $recentpost_temp .= iif($recentpost[$key],iif($recentpost_temp,",$key",$key),"");
  }

  query("UPDATE news_category SET
    parentid = '$parentid',
    name = '$name',
    description = '$content',
    image = '$image',
    allowcomments = '$allowcomments',
    allowview = '$allowview',
    showsitestats = '$showsitestats',
    showforumstats = '$showforumstats',
    showforumoptions = '$showforumoptions',
    showpoll = '$showpoll',
    showannouncement = '$showannouncement',
    showsubcats = '$showsubcats',
    defaulttheme = '$defaulttheme',
    forcetheme = '$forcetheme',
    recentpost = '$recentpost_temp',
    display = '$display',
    displayorder = '$displayorder',
    displaymain = '$displaymain'
    WHERE id = $id");

  unset($cat_arr);
  $cat_arr = getcat_arr();

  writeallpages();

  if ($parentid) {
    if ($data_arr[parentid]) {
      $expandurl = "&expand=$data_arr[parentid]&subexpand=$parentid".iif($old_data[children],"&botexpand=$id","");
    } else {
      $expandurl = "&expand=$parentid".iif($old_data[children],"&subexpand=$id","");
    }
  } else {
    $expandurl = iif($old_data[children],"&expand=$id","");
  }

  echoadminredirect("admin.php?action=category$expandurl");

break;

case "category_delete":

  verifyid("news_category",$id);

  if (($defaultcat_loggedout == $id) | ($defaultcat_loggedin == $id) | ($defaultcat_staff == $id)) {
    adminerror("Cannot Delete Category","You cannot delete this category as it is set to the default category, you must first alter this to continue");
  }
  echodeleteconfirm("news category","category_kill",$id,"Note this will delete all news posts and comments associated to this category");

break;

case "category_kill":

  settype($id,"integer");
  if ($data_arr = query_first("SELECT id,children,parentid FROM news_category WHERE id = $id")) {

    unset($news_ids);
    unset($staff_ids);
    unset($user_ids);
    unset($cat_ids);

    $cat_ids[] = $id;

    if ($data_arr[children]) {
      $getmid = query("SELECT id,children FROM news_category WHERE parentid = $id");
      while ($mid_arr = fetch_array($getmid)) {
        if ($mid_arr[children]) {
          $getbot = query("SELECT id FROM news_category WHERE parentid = $mid_arr[id]");
          while ($bot_arr = fetch_array($getbot)) {
            $cat_ids[] = $bot_arr[id];
          }
        }
        $cat_ids[] = $mid_arr[id];
      }
    }

    foreach ($cat_ids AS $val) {
      if (($defaultcat_loggedout == $val) | ($defaultcat_loggedin == $val) | ($defaultcat_staff == $val)) {
        adminerror("Cannot Delete Category","You cannot delete this category as this category itself, or one below it, is set to the default category, you must first alter this to continue");
      }
    }

    $cat_list = join("','",$cat_ids);

    $getposts = query("SELECT id,staffid FROM news_news WHERE catid IN ('$cat_list')");
    while ($news_data = fetch_array($getposts)) {
      $staff_ids[$news_data[staffid]] ++;
      $news_ids[] = $news_data[id];
    }

    if ($news_ids) {
      $getcomments = query("SELECT userid FROM news_comment WHERE newsid IN (".join(",",$news_ids).")");
      while ($comments_data = fetch_array($getcomments)) {
        $user_ids[$comments_data[userid]] ++;
      }

      if ($user_ids) {
        foreach($user_ids AS $key => $val) {
          query("UPDATE $foruminfo[user_table] SET $foruminfo[posts_field] = $foruminfo[posts_field] -'$val' WHERE $foruminfo[userid_field] = $val");
        }
      }

      foreach($staff_ids AS $key => $val) {
        query("UPDATE news_staff SET newsposts = newsposts-'$val' WHERE id = $val");
      }
      query("DELETE FROM news_news WHERE catid IN ('$cat_list')");
      query("DELETE FROM news_comment WHERE newsid IN (".join(",",$news_ids).")");

    }

    query("DELETE FROM news_category WHERE id IN ('$cat_list')");
    query("DELETE FROM news_announcement WHERE catid IN ('$cat_list')");

    if ($data_arr[parentid] == 0) {
      query("ALTER TABLE news_staff DROP canpost_$id");
      query("OPTIMIZE TABLE news_staff");
    }

    $getpolls = query("SELECT display,id FROM news_poll");
    while ($poll_data = fetch_array($getpolls)) {
      $new_data = $poll_data[display];

      foreach ($cat_ids AS $val) {
        $new_data = str_replace("$val,","",$new_data);
        $new_data = str_replace(",$val,","",$new_data);
        $new_data = str_replace(",$val","",$new_data);
      }

      if ($new_sitecategory != $poll_data[display]) {
        query("UPDATE news_poll SET display = '$new_data' WHERE id = $poll_data[id]");
      }
    }

    $getcategories = query("SELECT recentpost,id FROM news_category");
    while ($category_data = fetch_array($getcategories)) {
      $new_data = $category_data[recentpost];

      foreach ($cat_ids AS $val) {
        $new_data = str_replace("$val,","",$new_data);
        $new_data = str_replace(",$val,","",$new_data);
        $new_data = str_replace(",$val","",$new_data);
      }

      if ($new_data != $category_data[recentpost]) {
        query("UPDATE news_category SET recentpost = '$new_data' WHERE id = $category_data[id]");
      }
    }

    $gettempsets = query("SELECT id FROM news_pageset");

    foreach ($cat_ids AS $val) {

      query("DELETE FROM news_page WHERE title = 'menu_$val'");

      @unlink("pages/default/menu_".$val.".vnp");

      data_seek($gettempsets,0);
      while ($set_arr = fetch_array($gettempsets)) {
        @unlink("static/index/index_$val"."_".$set_arr[id].".php");
        @unlink("static/comment/comment_$val"."_".$set_arr[id].".php");
        @unlink("static/polls/options_$val"."_".$set_arr[id].".php");
        @unlink("static/polls/results_$val"."_".$set_arr[id].".php");
        @unlink("pages/user/menu_$val"."_".$set_arr[id].".vnp");
      }

    }

    if ($data_arr[parentid]) {
      query("UPDATE news_category SET children = children-'1' WHERE id = $data_arr[parentid]");
      $temp = query_first("SELECT parentid FROM news_category WHERE id = $data_arr[parentid]");
      if ($temp[parentid]) {
        $expandurl = "&expand=$temp[parentid]&subexpand=$data_arr[parentid]";
      } else {
        $expandurl = "&expand=$data_arr[parentid]";
      }
    }

    unset($cat_arr[$id]);

    writeallpages();

    echoadminredirect("admin.php?action=category$expandurl");

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "category_ann_edit":

  settype($catid,"integer");
  if ($data_arr = query_first("SELECT content,image,link FROM news_announcement WHERE catid = $catid LIMIT 1")) {

    echohtmlheader("qhtmlcode");
    echoformheader("category_ann_update","Edit Announcement");
    updatehiddenvar("catid",$catid);
    echotabledescription("Use this page to edit the announcement for the category as shown below:");
    echotablerow("Category Name:",$cat_arr[$catid][name]);
    echoinputcode("Image:","image",$data_arr[image],40,1);
    echoinputcode("Image Link:","imagelink",$data_arr[link],40,1);
    if ($staff_allowqhtml) {
      echoqhtmlhelp();
    }
    echotextareacode("Current Announcement:","content",$data_arr[content],10,100,1);
    echoformfooter();
    echohtmlfooter();

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }
break;

case "category_ann_update":

  verifyid("news_category",$catid);

  query("UPDATE news_announcement SET content = '$content' , image = '$image' , link = '$imagelink' WHERE catid = $catid");

  writeallpages();

  echoadminredirect("admin.php?action=category");
  exit;

break;

default:
  adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/category.php
|| ####################################################################
\*======================================================================*/

?>
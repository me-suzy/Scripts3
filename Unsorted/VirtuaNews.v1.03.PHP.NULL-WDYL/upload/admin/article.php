<?php


if (preg_match("/(admin\/article.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

function returncatoptions($data,$level=0) {
  global $expand,$subexpand,$botexpand;

  if ($level == 1) {
    return "<li><b>$data[title]</b>".iif($expand == $data[id],returnlinkcode("Collapse","admin.php?action=article"),returnlinkcode("Expand","admin.php?action=article&expand=$data[id]"))." |".returnlinkcode("Add","admin.php?action=article_add&catid=$data[id]")." |".returnlinkcode("Edit","admin.php?action=article_cat_edit&id=$data[id]")." |".returnlinkcode("Delete","admin.php?action=article_cat_delete&id=$data[id]")."</li>\n";
  } elseif ($level == 2) {
    return "<li><b>$data[title]</b>".iif($subexpand == $data[id],returnlinkcode("Collapse","admin.php?action=article&expand=$expand"),returnlinkcode("Expand","admin.php?action=article&expand=$expand&subexpand=$data[id]"))." |".returnlinkcode("Add","admin.php?action=article_add&catid=$data[id]")." |".returnlinkcode("Edit","admin.php?action=article_cat_edit&id=$data[id]")." |".returnlinkcode("Delete","admin.php?action=article_cat_delete&id=$data[id]")."</li>\n";
  } elseif ($level == 3) {
    return "<li><b>$data[title]</b>".iif($botexpand == $data[id],returnlinkcode("Collapse","admin.php?action=article&expand=$expand&subexpand=$subexpand"),returnlinkcode("Expand","admin.php?action=article&expand=$expand&subexpand=$subexpand&botexpand=$data[id]"))." |".returnlinkcode("Add","admin.php?action=article_add&catid=$data[id]")." |".returnlinkcode("Edit","admin.php?action=article_cat_edit&id=$data[id]")." |".returnlinkcode("Delete","admin.php?action=article_cat_delete&id=$data[id]")."</li>\n";
  }
}

function returnarticleoptions($data) {
  return "<li>$data[title]".returnlinkcode("Edit","admin.php?action=article_edit&id=$data[id]")." |".returnlinkcode("Delete","admin.php?action=article_delete&id=$data[id]")."</li>\n";
}

function returnarticlelist($data) {

  if ($data[numarticles ]) {
    $getarticles = query("SELECT id,title FROM news_article WHERE catid = $data[id] ORDER BY title,date DESC");
    while ($article_arr = fetch_array($getarticles)) {
      $code .= returnarticleoptions($article_arr);
    }
  }
  $code .= "</ul><br />\n";

  return $code;
}

function echocatselect($title,$name,$value,$isarticle=0) {

  global $id;

  echo "  <tr>\n    <td>$title</td>\n    <td>\n      <select name=\"$name\" class=\"form\">\n";

  if (!$isarticle) {
    echo "        <option value=\"\">None</option>\n";
  }

  $gettop = query("SELECT id,title,children FROM news_articlecat WHERE parentid = 0 ORDER BY displayorder");

  while ($top_arr = fetch_array($gettop)) {

    if ($isarticle | (($id != $top_arr[id]) & !$isarticle)) {
      echo "        <option value=\"$top_arr[id]\"".iif($value == $top_arr[id]," selected=\"selected\"","").">$top_arr[title]</option>\n";
    }

    if ($top_arr[children]) {
      $getmid = query("SELECT id,title,children FROM news_articlecat WHERE parentid = $top_arr[id] ORDER BY displayorder");
      while ($mid_arr = fetch_array($getmid)) {
        if ($isarticle | (($id != $mid_arr[id]) & ($top_arr[id] != $id) & !$isarticle)) {
          echo "        <option value=\"$mid_arr[id]\"".iif($value == $mid_arr[id]," selected=\"selected\"","").">$top_arr[title] &gt;&gt; $mid_arr[title]</option>\n";
        }

        if ($mid_arr[children] & $isarticle) {
          $getbot = query("SELECT id,title FROM news_articlecat WHERE parentid = $mid_arr[id] ORDER BY displayorder");
          while ($bot_arr = fetch_array($getbot)) {
            if ($isarticle | (($id != $bot_arr[id]) & (($top_arr[id] != $id) | ($mid_arr[id] != $id)) & !$isarticle)) {
              echo "        <option value=\"$bot_arr[id]\"".iif($value == $bot_arr[id]," selected=\"selected\"","").">$top_arr[title] &gt;&gt; $mid_arr[title] &gt;&gt; $bot_arr[title]</option>\n";
            }
          }
        }
      }
    }
  }

  echo "      </select>\n    </td>\n  </tr>\n";
}

function makecatselect($title,$name,$value=0,$shownone=0,$includebot=1) {

  echo "  <tr>\n    <td>$title</td>\n    <td>\n      <select name=\"$name\" class=\"form\">\n";

  if ($shownone) {
    echo "        <option value=\"0\">None</option>\n";
  }

  $gettop = query("SELECT id,title,children FROM news_articlecat WHERE parentid = 0 ORDER BY displayorder");

  while ($top_arr = fetch_array($gettop)) {

    echo "        <option value=\"$top_arr[id]\"".iif($value == $top_arr[id]," SELECTED","").">$top_arr[title]</option>\n";

    if ($top_arr[children]) {

      $getmid = query("SELECT id,title,children FROM news_articlecat WHERE parentid = $top_arr[id] ORDER BY displayorder");

      while ($mid_arr = fetch_array($getmid)) {

        echo "        <option value=\"$mid_arr[id]\"".iif($value == $mid_arr[id]," SELECTED","").">$top_arr[title] &gt;&gt; $mid_arr[title]</option>\n";

        if ($mid_arr[children] & $includebot) {

          $getbot = query("SELECT id,title FROM news_articlecat WHERE parentid = $mid_arr[id] ORDER BY displayorder");

          while ($bot_arr = fetch_array($getbot)) {
            echo "        <option value=\"$bot_arr[id]\"".iif($value == $bot_arr[id]," SELECTED","").">$top_arr[title] &gt;&gt; $mid_arr[title] &gt;&gt; $bot_arr[title]</option>\n";
          }
        }
      }
    }
  }

  echo "      </select>\n    </td>\n  </tr>\n";

}


function makearticlecode($arr) {
  global $expand,$subexpand,$botexpand;
  $code = "<li>$arr[title]".returnlinkcode("Edit","admin.php?action=article_edit&id=$arr[id]&expand=$expand&subexpand=$subexpand&botexpand=$botexpand")." |".returnlinkcode("Delete","admin.php?action=article_delete&id=$arr[id]&expand=$expand&subexpand=$subexpand&botexpand=$botexpand")."</li>\n";

  return $code;
}

updateadminlog(iif($id,"id = $id",""));

if ($action == "article") {

  echohtmlheader();
  echotableheader("Edit Articles",1);
  echotabledescription("Using this section you can add edit and delete articles on your site.",1);
  echotabledescription(returnlinkcode("Add Category","admin.php?action=article_cat_add"),1);

  $getdata = query("SELECT id,title,children,numarticles FROM news_articlecat WHERE parentid = 0 ORDER BY displayorder");

  $tablerows = "<ul>\n";

  while ($data_arr = fetch_array($getdata)) {

    $tablerows .= returncatoptions($data_arr,1);

    if ($expand == $data_arr[id]) {
      $tablerows .= "<ul>\n";

      if ($data_arr[children]) {
        $getsubcats = query("SELECT id,title,children,numarticles FROM news_articlecat WHERE parentid = $data_arr[id] ORDER BY displayorder");
        while ($sub_arr = fetch_array($getsubcats)) {
          $tablerows .= returncatoptions($sub_arr,2);

          if ($subexpand == $sub_arr[id]) {
            $tablerows .= "<ul>\n";

            if ($sub_arr[children]) {
              $getbotcats = query("SELECT id,title,children,numarticles FROM news_articlecat WHERE parentid = $sub_arr[id] ORDER BY displayorder");
              while ($bot_arr = fetch_array($getbotcats)) {
                $tablerows .= returncatoptions($bot_arr,3);
                if ($botexpand == $bot_arr[id]) {
                  $tablerows .= "<ul>\n";
                  $tablerows .= returnarticlelist($bot_arr);
                }
              }
            }
            $tablerows .= returnarticlelist($sub_arr);
          }
        }
      }
      $tablerows .= returnarticlelist($data_arr);
    }

  }

  $tablerows .= "</ul>\n";

  echotabledescription("\n$tablerows    ",1);
  echotablefooter();
  echohtmlfooter();

}

if ($action == "article_new") {

  verifyid("news_articlecat",$catid);
  settype($id,"integer");
  settype($pagenum,"integer");

  if ($parseurl & $staff_allowqhtml) {
    $content = autoparseurl($content);
  }

  if ($preview) {
    $title = stripslashes($title);
    $content = stripslashes($content);
    $contentpreview = qhtmlparse($content,$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml,$parsenewline);
    $action = "article_add";
  } else {

    if (($title == "") | ($content == "")) {
      adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
    }

    if (($pagenum > 1) & ($pagenum < 10)) {
      query("UPDATE news_article SET title = '$title' , authorname = '$authorname' , page$pagenum = '$content' , numpages = '$pagenum' , date = '".time()."' , catid = '$catid' , parsenewline = '$parsenewline' WHERE id = $id");
    } elseif ($pagenum == 10) {
      query("UPDATE news_article SET title = '$title' , authorname = '$authorname' , page$pagenum = '$content' , numpages = '$pagenum' , date = '".time()."' , catid = '$catid' , parsenewline = '$parsenewline' , display = '1' WHERE id = $id");
    } else {
      query("INSERT INTO news_article VALUES (NULL,'".time()."','$title','$staffid','$authorname','1','0','0','$content','','','','','','','','','','$catid','$parsenewline','0')");
      $id = getlastinsert();
    }

    if (($pagenum == 10) | !empty($submit)) {
      if ($parent = query_first("SELECT id,parentid FROM news_articlecat WHERE (parentid <> 0) AND (id = $catid)")) {
        if ($temp = query_first("SELECT parentid FROM news_articlecat WHERE (parentid <> 0) AND (id = $parent[parentid])")) {
          $expandurl = "&expand=$temp[parentid]&subexpand=$parent[parentid]&botexpand=$catid";
        } else {
          $expandurl = "&expand=$parent[parentid]&subexpand=$catid";
        }
      } else {
        $expandurl = "&expand=$catid";
      }
    }

    if ($submit) {
      query("UPDATE news_article SET display = '1' WHERE id = $id");
      query("UPDATE news_articlecat SET numarticles = numarticles +'1' WHERE id = $catid");
      writeallpages();
      echoadminredirect("admin.php?action=article$expandurl");
      exit;
    } else {
      if ($pagenum == 10) {
        query("UPDATE news_articlecat SET numarticles = numarticles +'1' WHERE id = $catid");
        writeallpages();
        echoadminredirect("admin.php?action=article$expandurl");
        exit;
      }
      $action = "article_add";
      $pagenum ++;
    }
  }

}

if ($action == "article_add") {

  settype($pagenum,"integer");
  if ($pagenum < 1) {
    $pagenum = 1;
  }

  echohtmlheader("qhtmlcode");
  if ($preview) {
    echotableheader("Article Preview - Page $pagenum",1);
    echotabledescription($contentpreview,1);
    echotablefooter();
  } else {
    $content = "";
    $parseurl = 1;
    $parsenewline = 1;
  }

  echoformheader("article_new","Add New Aticle - Page $pagenum");
  updatehiddenvar("id",$id);
  updatehiddenvar("pagenum",$pagenum);
  echotabledescription("Enter the content of the article page by page, click submit when you have finished, or continue to go onto the next page to input it<br />Note you can only have a maximum of 10 pages, and clicking continue will save the current page so you can come back and edit it in the future");
  if ($pagenum > 1) {
    echotabledescription(returnlinkcode("View Article","articles.php?action=show&id=$id",1));
  }
  echoinputcode("Title:","title",$title);
  echoinputcode("Author Name:<br />Leave blank to use your name","authorname",$authorname,40,1);
  echocatselect("Article Category:","catid",$catid,1);
  if ($staff_allowqhtml) {
    echoqhtmlhelp();
  }
  echotextareacode("Page $pagenum content:","content",$content,20,100);
  if ($staff_allowqhtml) {
    echoyesnocode("Auto Parse URL's:","parseurl",$parseurl);
  }
  echoyesnocode("Auto Parse New Lines To &lt;br /&gt;:","parsenewline",$parsenewline);
  echoformfooter(2,"Submit",1,"      <input type=\"submit\" name=\"continue\" value=\"Continue\" class=\"form\" />\n");
  echohtmlfooter();

}

if ($action == "article_update") {

  if ($parseurl & $staff_allowqhtml) {
    $content = autoparseurl($content);
  }

  if ($preview) {
    $title = stripslashes($title);
    $content = stripslashes($content);
    $contentpreview = qhtmlparse($content,$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml,$parsenewline);
    $action = "article_edit";
  } elseif ($delete) {

    settype($pagenum,"integer");

    if (($pagenum < 1) | ($pagenum > 10)) {
      adminerror("Invalid Page Number","You have specified an invalid page number, only integers between 1 and 10 are allowed.");
    }

    settype($id,"integer");
    if ($data_arr = query_first("SELECT page1,page2,page3,page4,page5,page6,page7,page8,page9,page10,catid FROM news_article WHERE id = $id")) {

      $data_arr[page.$pagenum] = "";
      unset($pew_pages);

      $lastpage = 1;
      for ($i=1;$i<=10;$i++) {
        if ($data_arr[page.$i]) {
          $data_arr[page.$i] = addslashes($data_arr[page.$i]);
          $new_pages[$lastpage++] = $data_arr[page.$i];
        }
      }
      $lastpage--;

      if (empty($new_pages[1])) {
        adminerror("Cannot Delete Page","You cannot delete the only page there is, to delete this article you must use the delete link on the article listing.");
      }

      query("UPDATE news_article SET page1 = '$new_pages[1]', page2 = '$new_pages[2]' , page3 = '$new_pages[3]' , page4 = '$new_pages[4]' , page5 = '$new_pages[5]' , page6 = '$new_pages[6]' , page7 = '$new_pages[7]' , page8 = '$new_pages[8]' , page8 = '$new_pages[9]' , page10 = '$new_pages[10]' , numpages = '$lastpage' WHERE id = '$id'");
      $action = "article_edit";
      $pagenum --;

    } else {
      adminerror("Invalid ID","You have specified an invalid id.");
    }

  } else {

    if (($title == "") | ($content == "")) {
      adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
    }

    if ($data_arr = query_first("SELECT display,numpages,catid FROM news_article WHERE id = $id")) {

      $numpages = iif($data_arr[numpages] < $pagenum,$pagenum,$data_arr[numpages]);

      if ($data_arr[display] != $display) {
        query("UPDATE news_articlecat SET numarticles = numarticles".iif($display,"+","-")."'1' WHERE id = $data_arr[catid]");
      }

      if ($data_arr[catid] != $catid) {
        query("UPDATE news_articlecat SET numarticles = numarticles-'1' WHERE id = $data_arr[catid]");
        query("UPDATE news_articlecat SET numarticles = numarticles+'1' WHERE id = $catid");
      }

      query("UPDATE news_article SET page$pagenum = '$content' , authorname = '$authorname' , title = '$title' , display = '$display' , numpages = '$numpages' , catid = '$catid' , parsenewline = '$parsenewline' WHERE id = $id");

      writeallpages();

      if ($parent = query_first("SELECT id,parentid FROM news_articlecat WHERE (parentid <> 0) AND (id = $catid)")) {
        if ($temp = query_first("SELECT parentid FROM news_articlecat WHERE (parentid <> 0) AND (id = $parent[parentid])")) {
          $expandurl = "&expand=$temp[parentid]&subexpand=$parent[parentid]&botexpand=$catid";
        } else {
          $expandurl = "&expand=$parent[parentid]&subexpand=$catid";
        }
      } else {
        $expandurl = "&expand=$catid";
      }


      echoadminredirect("admin.php?action=article$expandurl");
    } else {
      adminerror("Invalid ID","You have specified an invalid id.");
    }
  }

}

if ($action == "article_edit") {

  settype($id,"integer");
  settype($pagenum,"integer");

  if ($pagenum < 1) {
    $pagenum = 1;
  }

  if ($data_arr = query_first("SELECT title,authorname,numpages,page$pagenum,catid,parsenewline,display FROM news_article WHERE id = $id")) {

    for ($i=1;$i<=$data_arr[numpages];$i++) {
      $pagelinks .= iif($i == $pagenum,$pagenum,returnlinkcode("$i ","admin.php?action=article_edit&id=$id&pagenum=$i&expand=$expand&subexpand=$subexpand&botexpand=$botexpand"));
    }

    $pagelinks .= iif($pagenum == ($data_arr[numpages]+1),$pagenum." (new page)","");

    $pagelinks .= iif(($data_arr[numpages] < 10) & ($pagenum != ($data_arr[numpages]+1)),returnlinkcode($i." (new page)","admin.php?action=article_edit&id=$id&pagenum=$i"),"");

    echohtmlheader("qhtmlcode");
    if ($preview) {
      echotableheader("Article Preview - Page $pagenum",1);
      echotabledescription($contentpreview,1);
      echotablefooter();
    } else {
      $content = $data_arr[page.$pagenum];
      $title = $data_arr[title];
      $catid = $data_arr[catid];
      $authorname = $data_arr[authorname];
      $parseurl = 1;
      $parsenewline = $data_arr[parsenewline];
      $display = $data_arr[display];
    }

    echoformheader("article_update","Edit Article - Page $pagenum");
    updatehiddenvar("id",$id);
    updatehiddenvar("pagenum",$pagenum);
    echotabledescription("Edit the article below, you the links below to select the page you wish to enter");
    echotabledescription(returnlinkcode("View Article","articles.php?action=show&id=$id",1));
    echotablerow("Edit Page:",$pagelinks);
    echoinputcode("Author Name:<br />Leave blank to use your name","authorname",$authorname,40,1);
    echoinputcode("Title:","title",$title);
    echocatselect("Article Category:","catid",$catid,1);
    if ($staff_allowqhtml) {
      echoqhtmlhelp();
    }
    echotextareacode("Page $pagenum content:","content",$content,20,100);
    if ($staff_allowqhtml) {
      echoyesnocode("Auto Parse URL's:","parseurl",$parseurl);
    }
    echoyesnocode("Auto Parse New Lines To &lt;br /&gt;:","parsenewline",$parsenewline);
    echoyesnocode("Display:","display",$display);
    echoformfooter(2,"Submit",1,"      <input type=\"submit\" name=\"delete\" value=\"Delete Page\" class=\"form\" />\n");
    echohtmlfooter();

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }
}

if ($action == "article_delete") {
  verifyid("news_article",$id);
  echodeleteconfirm("article","article_kill",$id);
}

if ($action == "article_kill") {

  settype($id,"integer");
  if ($data_arr = query_first("SELECT display,catid FROM news_article WHERE id = $id")) {

    query("DELETE FROM news_article WHERE id = $id");
    query("DELETE FROM news_articlevote WHERE articleid = $id");

    if ($data_arr[display]) {
      query("UPDATE news_articlecat SET numarticles = numarticles-'1' WHERE id = $data_arr[catid]");
    }

    writeallpages();

    if ($parent = query_first("SELECT id,parentid FROM news_articlecat WHERE (parentid <> 0) AND (id = $data_arr[catid])")) {
      if ($temp = query_first("SELECT parentid FROM news_articlecat WHERE (parentid <> 0) AND (id = $parent[parentid])")) {
        $expandurl = "&expand=$temp[parentid]&subexpand=$parent[parentid]&botexpand=$data_arr[catid]";
      } else {
        $expandurl = "&expand=$parent[parentid]&subexpand=$data_arr[catid]";
      }
    } else {
      $expandurl = "&expand=$data_arr[catid]";
    }

    echoadminredirect("admin.php?action=article$expandurl");
    exit;

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }
}

if ($action == "article_cat_add") {
  echohtmlheader("qhtmlcode");
  echoformheader("article_cat_new","Add Article Category");
  echoinputcode("Title:","title");
  if ($staff_allowqhtml) {
    echoqhtmlhelp();
  }
  echotextareacode("Description:","content","",5,50,1);
  echoinputcode("Display Order:","displayorder",1,10);
  echocatselect("Parent Category:","parentid",0);
  echoformfooter();
  echohtmlfooter();
}

if ($action == "article_cat_new") {

  if (($title == "") | ($displayorder == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  settype($parentid,"integer");

  if ($parentid > 0) {
    if ($data_arr = query_first("SELECT id,parentid FROM news_articlecat WHERE id = $parentid")) {
      if ($data_arr[parentid] > 0) {
        if ($temp = query_first("SELECT parentid FROM news_articlecat WHERE (id = $data_arr[parentid]) AND (parentid <> 0)")) {
          adminerror("Invalid Parent Category","You have specified an invalid parent category, you may only have up to three levels of categories.");
        }
      }
      query("UPDATE news_articlecat SET children = children+'1' WHERE id = $parentid");
    } else {
      adminerror("Invalid ID","You have specified an invalid id.");
    }
  }

  query("INSERT INTO news_articlecat VALUES (NULL,'$title','$content','$parentid','0','0','$displayorder','1')");
  $newid = getlastinsert();

  if ($parentid) {
    if ($data_arr[parentid]) {
      $expandurl = "&expand=$data_arr[parentid]&subexpand=$parentid&botexpand=$newid";
    } else {
      $expandurl = "&expand=$parentid&subexpand=$newid";
    }
  } else {
    $expandurl = "&expand=$newid";
  }

  writeallpages();
  echoadminredirect("admin.php?action=article$expandurl");

}

if ($action == "article_cat_edit") {

  settype($id,"integer");
  if ($data_arr = query_first("SELECT title,description,displayorder,display,parentid FROM news_articlecat WHERE id = $id")) {

    echohtmlheader("qhtmlcode");
    echoformheader("article_cat_update","Edit Article Category");
    updatehiddenvar("id",$id);
    echoinputcode("Title:","title",$data_arr[title]);
    if ($staff_allowqhtml) {
      echoqhtmlhelp();
    }
    echotextareacode("Description:","content",$data_arr[description],5,50,1);
    echoinputcode("Display Order:","displayorder",$data_arr[displayorder],10);
    echocatselect("Parent Category:","parentid",$data_arr[parentid],0);
    echoyesnocode("Display","display",$data_arr[display]);
    echoformfooter();
    echohtmlfooter();

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }
}

if ($action == "article_cat_update") {

  if (($title == "") | ($displayorder == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  settype($id,"integer");
  settype($parentid,"integer");

  if ($old_data = query_first("SELECT parentid FROM news_articlecat WHERE id = $id")) {

    if ($old_data[parentid] != $parentid) {

      if ($parentid == $id) {
        adminerror("Invalid Parent Category","You have specified you wish to make this category a sub category to itself which you cannot do.");
      }

      $level = 1;

      if ($parentid > 0) {
        if ($data_arr = query_first("SELECT id,parentid FROM news_articlecat WHERE id = $parentid")) {
          $level ++;
          if ($data_arr[parentid] > 0) {
            $level ++;
            if (query_first("SELECT id FROM news_articlecat WHERE (id = $data_arr[parentid]) AND (parentid <> 0)")) {
              $level ++;
            }
          }
        } else {
          adminerror("Invalid ID","You have specified an invalid id");
        }

        $getsub = query("SELECT id,children FROM news_articlecat WHERE parentid = $id");
        if (countrows($getsub)) {
          $level ++;
        }
        while ($sub_arr = fetch_array($getsub)) {
          if ($parentid == $sub_arr[id]) {
            adminerror("Invalid Parent","You have specified that you wish to make this category a child to a category which is a child to itself, you cannot do this.");
          } elseif ($sub_arr[children]) {
            $bot_ids[] = $sub_arr[id];
          }
        }

        if ($bot_ids) {
          $getbot = query("SELECT id FROM news_articlecat WHERE parentid IN (".join("','",$bot_ids).")");
          while ($bot_arr = fetch_array($getbot)) {
            if ($parentid == $bot_arr[id]) {
              adminerror("Invalid Parent","You have specified that you wish to make this category a child to a category which is a child to itself, you cannot do this.");
            }
          }
          $level ++;
        }

        if ($level > 3) {
          adminerror("Category Move Error","By moving this category to the one you specified it will result in a category that is more than 3 levels deep, the links module only supports 3 levels of categories and you cannot continue.  Please check that the category you are moving does not have any sub categories.");
        }

        query("UPDATE news_articlecat SET children = children+'1' WHERE id = $parentid");
      }

      query("UPDATE news_articlecat SET children = children-'1' WHERE id = $old_data[parentid]");

    }

    query("UPDATE news_articlecat SET title = '$title' , description = '$content' , parentid = '$parentid' , displayorder = '$displayorder' , display = '$display' WHERE id = $id");

    if ($parentid) {
      if ($data_arr[parentid]) {
        $expandurl = "&expand=$data_arr[parentid]&subexpand=$parentid&botexpand=$id";
      } else {
        $expandurl = "&expand=$parentid&subexpand=$id";
      }
    } else {
      $expandurl = "&expand=$id";
    }

    writeallpages();
    echoadminredirect("admin.php?action=article$expandurl");
    exit;

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }
}

if ($action == "article_cat_delete") {
  verifyid("news_articlecat",$id);
  echodeleteconfirm("article category","article_cat_kill",$id,"This will also delete any articles and any sub categories underneath this category, this action cannot be reversed.");
}

if ($action == "article_cat_kill") {

  settype($id,"integer");
  if ($data_arr = query_first("SELECT id,children,parentid FROM news_articlecat WHERE id = $id")) {

    $ids = "'$id'";

    if ($data_arr[children]) {
      $getmid = query("SELECT id,children FROM news_articlecat WHERE parentid = $id");
      while ($mid_arr = fetch_array($getmid)) {
        if ($mid_arr[children]) {
          $getbot = query("SELECT id FROM news_articlecat WHERE parentid = $mid_arr[id]");
          while ($bot_arr = fetch_array($getbot)) {
            $ids .= ",'$bot_arr[id]'";
          }
        }
        $ids .= ",'$mid_arr[id]'";
      }
    }

    unset($article_ids);

    $getarticles = query("SELECT id FROM news_article WHERE catid IN ($ids)");
    while ($article_arr = fetch_array($getarticles)) {
      $article_ids .= iif($article_ids,",'$article_arr[id]'","'$article_arr[id]'");
    }

    query("DELETE FROM news_article WHERE catid IN ($ids)");
    query("DELETE FROM news_articlecat WHERE id IN ($ids)");
    if ($article_ids) {
      query("DELETE FROM news_articlevote WHERE articleid IN ($article_ids)");
    }

    if ($data_arr[parentid]) {
      query("UPDATE news_articlecat SET children = children-'1' WHERE id = $data_arr[parentid]");
      $temp = query_first("SELECT parentid FROM news_articlecat WHERE id = $data_arr[parentid]");
      if ($temp[parentid]) {
        $expandurl = "&expand=$temp[parentid]&subexpand=$data_arr[parentid]";
      } else {
        $expandurl = "&expand=$data_arr[parentid]";
      }
    }

    writeallpages();

    echoadminredirect("admin.php?action=article$expandurl");
    exit;
  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/article.php
|| ####################################################################
\*======================================================================*/

?>
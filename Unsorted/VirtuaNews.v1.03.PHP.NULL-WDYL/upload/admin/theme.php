<?php


if (preg_match("/(admin\/theme.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

function echothemeselectcode($title,$name="themeid",$value=0,$shownew=0,$width="") {

  echo "  <tr>\n    <td".iif($width," width=\"$width%\"","").">$title</td>\n    <td>\n      <select name=\"$name\" class=\"form\">\n";
  echo iif($shownew,"        <option value=\"0\">Create New Set</option>\n","");

  $getdata = query("SELECT id,title FROM news_theme ORDER BY title");
  while ($data_arr = fetch_array($getdata)) {
    echo "        <option value=\"$data_arr[id]\"".iif($value == $data_arr[id]," SELECTED","").">$data_arr[title]</option>\n";
  }

  echo "      </select>\n    </td>\n  </tr>\n";

}

function echosetselectcode($title,$name,$what="style",$shownew=1,$value=0) {

  echo "  <tr>\n    <td>$title</td>\n    <td>\n      <select name=\"$name\" class=\"form\">\n";
  echo iif($shownew,"        <option value=\"0\">Create New Set</option>\n","");

  $getdata = query("SELECT id,title FROM news_".$what."set ORDER BY title");
  while ($data_arr = fetch_array($getdata)) {
    echo "        <option value=\"$data_arr[id]\"".iif($value == $data_arr[id]," SELECTED","").">$data_arr[title]</option>\n";
  }

  echo "      </select>\n    </td>\n  </tr>\n";

}

function returnpagelist($titleselect,$level=1) {

  global $data_arr,$themeid;

  if (is_array($titleselect)) {
    $titleselect = join(" AND ",$titleselect);
  }

  $getpages = query("SELECT id,title,description,pagesetid FROM news_page WHERE $titleselect AND (pagesetid IN ('-2','-1','$data_arr[pagesetid]')) ORDER BY pagesetid,title");

  while ($data = fetch_array($getpages)) {
    $pages[$data[title]][id] = $data[id];
    $pages[$data[title]][altered] = iif(($data[pagesetid] == -1) | ($data[pagesetid] == -2),0,1);
  }

  if ($pages) {
    foreach ($pages AS $key => $val) {
      $code .= iif($level == 1,"            ","                ")."<li>".iif($val[altered],"<span class=\"red\">$key</span>",$key).iif($val[altered],returnlinkcode("Edit","admin.php?action=theme_page_edit&id=$val[id]&pagesetid=$data_arr[pagesetid]&themeid=$themeid")." |".iif(substr($key,0,7) == "custom_",returnlinkcode("Delete","admin.php?action=theme_page_delete&id=$val[id]&themeid=$themeid"),returnlinkcode("View Original","admin.php?action=theme_page_view&title=".urlencode($key),1)." |".returnlinkcode("Revert","admin.php?action=theme_page_revert&id=$val[id]&themeid=$themeid")),returnlinkcode("Edit Original","admin.php?action=theme_page_edit&id=$val[id]&pagesetid=$data_arr[pagesetid]&themeid=$themeid"))."</li>\n";
    }
  }
  return $code;
}

function escapetext($string="") {
  $string = str_replace("|||||","||| ||",$string);
  $string = " ".$string." ";

  return $string;
}

function undoescapetext($string="") {
  $string = str_replace("||| ||","|||||",$string);
  $string = substr($string,1,-1);

  return $string;
}

$page_arr[aboutus][title] = "About us pages";
$page_arr[aboutus][help] = "This category contains the page to display information about the staff of your site.";

$page_arr[archive][title] = "Archive pages";
$page_arr[archive][help] = "This category contains the page that displays the news archive on your site.";

$page_arr[articles][title] = "Article pages";
$page_arr[articles][help] = "This category contains all the pages to do with articles and displaying them.";

$page_arr[comments][title] = "Comments display pages";
$page_arr[comments][help] = "This category contains the pages which will display indervidual news posts and comments to them.";

$page_arr[edit][title] = "Comment Edit pages";
$page_arr[edit][help] = "This category contains the pages that will be displayed when a user edits their comments.";

$page_arr[error][title] = "Error pages";
$page_arr[error][help] = "This category contains the pages which will be displayed when an error occurs, eg a user does not supply the correct information.";

$page_arr[help][title] = "Help Pages";
$page_arr[help][help] = "This category contains pages which your users may view to get advice on how to use your site, for example, how to use qhtml code and smilies.";

$page_arr[main][title] = "Index Pages";
$page_arr[main][help] = "This category contains pages that are used to display the latest news and information about each news category, plus the pages for the category lists and custom start page.";

$page_arr[member][title] = "Member Pages";
$page_arr[member][help] = "This category contains all the pages for members, eg the page allowing people to view profiles, and that to email members.";

$page_arr[menu][title] = "Menu bits";
$page_arr[menu][help] = "This category contains the menus for each news category.";

$page_arr[misc][title] = "Misc bits";
$page_arr[misc][help] = "This category contains all the miscellaneous bits that dont fit into any other category, or those which are usesd in multiple places and purposes.";

$page_arr[poll][title] = "Poll pages";
$page_arr[poll][help] = "This category contains all the pages to do with polls";

$page_arr['print'][title] = "Print post pages";
$page_arr['print'][help] = "This category contains the pages that display a printable version of the news posts.";

$page_arr[redirect][title] = "Redirection pages";
$page_arr[redirect][help] = "This category contains pages that users see whent hey are being redirected after submitting something to the site.";

$page_arr[register][title] = "Registration pages";
$page_arr[register][help] = "This category contains the pages for registering with your site.";

$page_arr[stats][title] = "Stats pages";
$page_arr[stats][help] = "This category contains pages that will output the site statistics";

$page_arr[search][title] = "Search pages";
$page_arr[search][help] = "This category contains all the pages for searching the site.";

$page_arr[user][title] = "User panel pages";
$page_arr[user][help] = "This category contains all the pages for the user control panel.";

$page_arr[articles][sub][cats][title] = "Category display pages";
$page_arr[articles][sub][display][title] = "Article display pages";
$page_arr[articles][sub][index][title] = "Article index pages";

$page_arr[comments][sub][add][title] = "Add comment bits";
$page_arr[comments][sub][comment][title] = "Comment data bits";
$page_arr[comments][sub][emailnotify][title] = "Email notify bits";
$page_arr[comments][sub][news][title] = "News display bits";
$page_arr[comments][sub][smilies][title] = "Smilie bits";

$page_arr[main][sub][news][title] = "News post bits";
$page_arr[main][sub][catlist][title] = "Category list pages";

$page_arr[member][sub][email][title] = "Email Form pages";
$page_arr[member][sub]['list'][title] = "Member List pages";
$page_arr[member][sub][profile][title] = "Profile pages";

$page_arr[misc][sub][announcement][title] = "Announcement bits";
$page_arr[misc][sub][nav][title] = "Nav bar bits";
$page_arr[misc][sub][page_nav][title] = "Page navigation bits";
$page_arr[misc][sub][recent_post][title] = "Recent post bits";
$page_arr[misc][sub][stats][title] = "Stats bits";
$page_arr[misc][sub][sitejump][title] = "Site Jump bits";
$page_arr[misc][sub][theme_selector][title] = "Theme selector bits";
$page_arr[misc][sub][welcometext][title] = "Welcome Text bits";

$page_arr[poll][sub][options][title] = "Poll option bits";
$page_arr[poll][sub][results][title] = "Poll result bits";
$page_arr[poll][sub][view][title] = "Poll display pages";

$page_arr[register][sub][email][title] = "Email pages";

$page_arr[search][sub][results][title] = "Results pages";

$page_arr[user][sub][forget][title] = "Password forget pages";
$page_arr[user][sub][pm][title] = "Private messenging pages";

if ($use_forum) {
  unset($page_arr[member]);
  unset($page_arr[register]);
  unset($page_arr[user]);
  $page_arr[user_login][title] = "User login pages";
  $page_arr[user_login][help] = "This category contains the pages used when a user is logging in or out.";
}

updateadminlog(iif($id,"id = $id",""));

switch ($action) {

case "theme":

  echohtmlheader();
  echotableheader("Edit Themes");
  echotabledescription("Using this page you can edit the themes on your site by following a few simple steps.  The first step is to select which theme you wish to edit.");
  echotabledescription(returnlinkcode("Add Theme","admin.php?action=theme_add"));

  $tablerows = returnminitablerow("<b>Theme Title</b>","<b>Allow Users To Select</b>","<b>Options</b>");

  $getdata = query("SELECT id,title,allowselect FROM news_theme ORDER BY title");

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow($data_arr[title],iif($data_arr[allowselect],"Yes","No"),returnlinkcode("Edit","admin.php?action=theme_edit&id=$data_arr[id]")." |".returnlinkcode("Delete","admin.php?action=theme_delete&id=$data_arr[id]")." |".returnlinkcode("Download","admin.php?action=theme_download&id=$data_arr[id]")." |".returnlinkcode("Edit Pages","admin.php?action=theme_pageselect&themeid=$data_arr[id]")." |".returnlinkcode("Edit Style","admin.php?action=theme_style&themeid=$data_arr[id]"));
  }

  echotabledescription("\n".returnminitable($tablerows,0,100)."    ");
  echotablefooter();
  echo "<br />\n";

  echotableheader("Edit Page Sets");
  echotabledescription("Using this section you can edit the details of each page set on your site.  If you would like to edit the actual pages for your sets then you must do it by clicking on the &quot;Edit Pages&quot; link for the appropriate theme above.");
  echotabledescription(returnlinkcode("Add Page Set","admin.php?action=theme_pageset_add"));

  $tablerows = returnminitablerow("<b>Page Set Title</b>","<b>Options</b>");

  $getdata = query("SELECT id,title FROM news_pageset ORDER BY title");

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow($data_arr[title],returnlinkcode("Edit","admin.php?action=theme_pageset_edit&id=$data_arr[id]")." |".returnlinkcode("Delete","admin.php?action=theme_pageset_delete&id=$data_arr[id]"));
  }

  echotabledescription("\n".returnminitable($tablerows,0,100)."    ");
  echotablefooter();
  echo "<br />\n";

  echotableheader("Edit Style Sets");
  echotabledescription("Using this section you can edit the details of each style set on your site.  If you would like to edit the actual style for your sets then you must do it by clicking on the &quot;Edit Style&quot; link for the appropriate theme above.");
  echotabledescription(returnlinkcode("Add Style Set","admin.php?action=theme_styleset_add"));

  $tablerows = returnminitablerow("<b>Style Set Title</b>","<b>Options</b>");

  $getdata = query("SELECT id,title FROM news_styleset ORDER BY title");

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow($data_arr[title],returnlinkcode("Edit","admin.php?action=theme_styleset_edit&id=$data_arr[id]")." |".returnlinkcode("Delete","admin.php?action=theme_styleset_delete&id=$data_arr[id]"));
  }

  echotabledescription("\n".returnminitable($tablerows,0,100)."    ");
  echotablefooter();
  echo "<br />\n";

  echoformheader("theme_upload","Upload Theme",2,1);
  echotabledescription("Upload a theme from your computer.");
  echouploadcode("Upload File:","uploadfile");
  echothemeselectcode("Overwrite Existing theme:","overwrite",0,1);
  echoyesnocode("Use theme even if its an incorrect version?","ignore",0);
  echoformfooter();
  echohtmlfooter();

break;

case "theme_add":

  echohtmlheader();
  echoformheader("theme_new","Add Style");
  echotabledescription("Using this section you can add a theme to your site");
  echoinputcode("Title","title");
  echosetselectcode("Style Set:","styleid","style",1);
  echosetselectcode("Page Set:","pageid","page",1);
  echoyesnocode("Allow Users To Select:","allowselect",1);
  echoformfooter();
  echohtmlfooter();

break;

case "theme_new":

  if ($title == "") {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }
  if ($styleid) {
    verifyid("news_styleset",$styleid);
  }
  if ($pagesetid) {
    verifyid("news_pageset",$pagesetid);
  }

  if (!$styleid) {
    query("INSERT INTO news_styleset VALUES (NULL,'$title')");
    $styleid = getlastinsert();
  }
  if (!$pageid) {
    query("INSERT INTO news_pageset VALUES (NULL,'$title')");
    $pageid = getlastinsert();
  }

  query("INSERT INTO news_theme VALUES (NULL,'$title','$pageid','$styleid','$allowselect')");

  writeallpages();
  echoadminredirect("admin.php?action=theme");
  exit;

break;

case "theme_edit":

  settype($id,"integer");
  if ($data_arr = query_first("SELECT title,stylesetid,pagesetid,allowselect FROM news_theme WHERE id = $id")) {

    echohtmlheader();
    echoformheader("theme_update","Edit Theme Options");
    updatehiddenvar("id",$id);
    echotabledescription("Using this section you can edit the options for your theme.  To save your changes edit them as you wish and click submit.");
    echotabledescription(returnlinkcode("Download Theme","admin.php?action=theme_download&id=$id"));
    echoinputcode("Title:","title",$data_arr[title]);
    echosetselectcode("Style Set:","styleid","style",0,$data_arr[stylesetid]);
    echosetselectcode("Page Set:","pagesetid","page",0,$data_arr[pagesetid]);
    echoyesnocode("Allow Users To Select:","allowselect",$data_arr[allowselect]);
    echoformfooter();
    echohtmlfooter();

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;


case "theme_update":

  if ($title == "") {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  verifyid("news_theme",$id);
  verifyid("news_styleset",$styleid);
  verifyid("news_pageset",$pagesetid);

  query("UPDATE news_theme SET title = '$title' , pagesetid = '$pagesetid' , stylesetid = '$styleid' , allowselect = '$allowselect' WHERE id = $id");

  writeallpages();
  echoadminredirect("admin.php?action=theme&id=$id");
  exit;

break;

case "theme_delete":

  verifyid("news_theme",$id);

  if ($temp = query_first("SELECT name FROM news_category WHERE defaulttheme = $id")) {
    adminerror("Cannot Delete Theme","You cannot delete this theme as it is set as the default theme for the news category $temp[name]");
  }

  echodeleteconfirm("theme","theme_kill",$id);

break;

case "theme_kill":

  verifyid("news_theme",$id);

  if ($temp = query_first("SELECT name FROM news_category WHERE defaulttheme = $id")) {
    adminerror("Cannot Delete Theme","You cannot delete this theme as it is set as the default theme for the news category $temp[name]");
  }

  query("DELETE FROM news_theme WHERE id = $id");
  writeallpages();
  echoadminredirect("admin.php?action=theme");
  exit;

break;

case "theme_upload":


  if (empty($uploadfile)) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  $name_arr = explode(".",$uploadfile[name]);
  $filetype = $name_arr[count($name_arr)-1];

  if ($filetype != "set") {
    adminerror("Invalid Theme","The theme you uploaded is not a valid one and you cannot continue.");
  }

  $filesize = @filesize($uploadfile[tmp_name]);

  $fp = @fopen($uploadfile[tmp_name],"r");
  $filecontent = @fread($fp,$filesize);
  @fclose($fp);
  @unlink($uploadfile[tmp_name]);

  if (trim($filecontent) == "") {
    adminerror("Invalid Theme","The theme you uploaded is not a valid one and you cannot continue.");
  }

  $data_arr = explode("|||||",$filecontent);

  unset($filecontent);

  foreach ($data_arr AS $data) {
    $count ++;
    if ($count%2 == 1) {
      $info[$data] = "";
      $last_info = $data;
    } else {
      $info[$last_info] = $data;
    }
  }
  unset($data_arr);

  if (($info[' version'] != $version) & !$ignore) {
    adminerror("Invalid Version","The theme you have uploaded is not the correct version for your version of VirtuaNews.  If you wish to continue anyway then you must go back and ensure you have set to continue even if the theme is not the correct version.");
  }

  if (($info[' theme title'] == "!!MASTER!!") & ($info[' page set'] == "!!MASTER!!") & ($info[' style set'] == "!!MASTER!!")) {
    $is_master = 1;
    $is_mod = 0;
  } else  if (($info[' theme title'] == "!!MODULES!!") & ($info[' page set'] == "!!MODULES!!") & ($info[' style set'] == "!!MODULES!!")) {
    $is_master = 1;
    $is_mod = 1;
  } else {
    $is_master = 0;
    $is_mod = 0;
  }

  if ($is_master) {

    $pagesetid = "-1";
    $stylesetid = "-1";

  } else {
    if ($overwrite) {
      verifyid("news_theme",$overwrite);

      $data_arr = query_first("SELECT title,pagesetid,stylesetid FROM news_theme WHERE id = $overwrite");
      $pagesetid = $data_arr[pagesetid];
      $stylesetid = $data_arr[stylesetid];

    } else {

      query("INSERT INTO news_styleset VALUES (NULL,'".$info[' style set']."')");
      $stylesetid = getlastinsert();
      query("INSERT INTO news_pageset VALUES (NULL,'".$info[' page set']."')");
      $pagesetid = getlastinsert();

      query("INSERT INTO news_theme VALUES (NULL,'".$info[' theme title']."','$pagesetid','$stylesetid','1')");

    }
  }
  unset($info[' version']);
  unset($info[' theme title']);
  unset($info[' style set']);
  unset($info[' page set']);

  foreach($info AS $title => $data) {

    $data = undoescapetext($data);

    if (substr($title,0,13) == " stylevar !!!") {
      $varname = mysql_escape_string(substr($title,13));
      if ($varname != "") {
        $data = mysql_escape_string($data);
        if(query_first("SELECT id FROM news_style WHERE (varname = '$varname') AND (stylesetid = '$stylesetid')")) {
          query("UPDATE news_style SET value = '$data' WHERE (varname = '$varname') AND (stylesetid = '$stylesetid')");
        } else {
          query("INSERT INTO news_style VALUES (NULL,'$stylesetid','$varname','$data')");
        }
      }
    } elseif (substr($title,0,9) == " page !!!") {

      $name = substr($title,9);
      $pagetype = returnpagetype($name);

      if ($is_master & ($name != "")) {
        if ($is_mod) {
          writepagebit("pages/default/mod/".$name.".vnp",$data);
        } else {
          writepagebit("pages/default/".$name.".vnp",$data);
        }

      } elseif (($name != "") & ($pagetype > 0)) {

        unset($is_mod);

        if ($pagetype > 2) {
          $is_mod = 0;
        } else {
          if (preg_match("/custom_(.*)/siU",$name)) {
            $is_mod = 0;
          } else {
            $is_mod = 1;
          }
        }

        $temp = query_first("SELECT id,pagesetid,description,onserver FROM news_page WHERE (title = '$name') AND (pagesetid IN ('$pagesetid','-1','-2')) ORDER BY pagesetid DESC");

        if(($temp[pagesetid] == "-1") | ($temp[pagesetid] == "-2")) {
          query("INSERT INTO news_page VALUES (NULL,'$pagesetid','$name','".addslashes($temp[description])."','$data_arr[onserver]')");
        }

        if ($is_mod) {
          writepagebit("pages/user/mod/".$name."_".$pagesetid.".vnp",$data);
        } elseif (isset($is_mod)) {
          writepagebit("pages/user/".$name."_".$pagesetid.".vnp",$data);
        }
      }
    }
  }

  writeallpages();

  echohtmlheader();
  echotableheader("Theme imported correctly",1);
  echotabledescription("The theme has now been imported correctly, please ensure that you upload any images that are used within this theme also.",1);
  echotablefooter();
  echohtmlfooter();

break;

case "theme_download":

  if ($id == "-1") {
    $theme_title = "!!MASTER!!";
    $pageset_id = "-1";
    $pageset_title = "!!MASTER!!";
    $styleset_id = "-1";
    $styleset_title = "!!MASTER!!";
  } elseif ($id == "-2") {
    $theme_title = "!!MODULES!!";
    $pageset_id = "-2";
    $pageset_title = "!!MODULES!!";
    $styleset_id = "0";
    $styleset_title = "!!MODULES!!";
  } else {

    verifyid("news_theme",$id);

    $data_arr = query_first("SELECT
      news_theme.title,
      news_theme.pagesetid,
      news_theme.stylesetid,
      news_pageset.title AS pagesettitle,
      news_styleset.title AS stylesettitle
      FROM news_theme
      LEFT JOIN news_pageset ON news_theme.pagesetid = news_pageset.id
      LEFT JOIN news_styleset ON news_theme.stylesetid = news_styleset.id
      WHERE news_theme.id = $id");

    $theme_title = $data_arr[title];
    $pageset_id = $data_arr[pagesetid];
    $pageset_title = $data_arr[pagesettitle];
    $styleset_id = $data_arr[stylesetid];
    $styleset_title = $data_arr[stylesettitle];

  }

  $filetext = " version|||||$version||||| theme title|||||$theme_title||||| page set|||||$pageset_title||||| style set|||||$styleset_title";

  $getwords = query("SELECT varname,value FROM news_style WHERE stylesetid = $styleset_id");
  while ($replace_arr = fetch_array($getwords)) {
    $filetext .= "||||| stylevar !!!$replace_arr[varname]|||||".escapetext($replace_arr[value]);
  }

  $getpages = query("SELECT title FROM news_page WHERE pagesetid = $pageset_id ORDER BY title");
  while ($page_arr = fetch_array($getpages)) {

    $pagetype = returnpagetype($page_arr[title],$pageset_id);

    if ($id == "-2") {
      $data = @join("",@file("pages/default/mod/".$page_arr[title].".vnp"));
    } elseif ($id == "-1") {
      $data = @join("",@file("pages/default/".$page_arr[title].".vnp"));
    } elseif ($pagetype == 3) {
      $data = @join("",@file("pages/user/".$page_arr[title]."_".$pageset_id.".vnp"));
    } elseif ($pagetype == 1) {
      $data = @join("",@file("pages/user/mod/".$page_arr[title]."_".$pageset_id.".vnp"));
    }

    $filetext .= "||||| page !!!$page_arr[title]|||||".escapetext($data);
  }

  header("Content-disposition: filename=virtuanews.set");
  header("Content-Length: ".strlen($filetext));
  header("Content-type: unknown/unknown");
  header("Pragma: no-cache");
  header("Expires: 0");
  echo $filetext;
  exit;

break;

case "theme_pageselect":

  verifyid("news_theme",$themeid);

  $data_arr = query_first("SELECT news_theme.title,news_theme.pagesetid,news_pageset.title AS pageset_title FROM news_theme LEFT JOIN news_pageset ON news_theme.pagesetid = news_pageset.id WHERE news_theme.id = $themeid");

  if (query_first("SELECT id FROM news_page WHERE (title LIKE 'custom_%') AND (pagesetid = $data_arr[pagesetid]) LIMIT 1")) {
    $page_arr[custom][title] = "Custom Pages";
    $page_arr[custom][help] = "This category contains all of your custom pages for this page set.";
  }

  $moddata = getmoddata();
  if (countrows($moddata)) {
    $page_arr[][title] = "";
  }

  while ($mod_arr = fetch_array($moddata)) {
    $page_arr[$mod_arr[name]][title] = "$mod_arr[text] module pages";
    $page_arr[$mod_arr[name]][help] = "This category contains all of the pages for the module ".str_replace("'","\\'",$mod_arr[text]);
  }

  $adminpageview = iif($HTTP_COOKIE_VARS[pageview] == "threaded","threaded","select");
  if (!empty($HTTP_GET_VARS[pageview])) {
    $adminpageview = iif($HTTP_GET_VARS[pageview] == "threaded","threaded","select");
  }
  updatecookie("pageview",$adminpageview,iif($adminpageview == "select",-1800,0));


  if ($adminpageview == "select") {

    unset($page_select);

    $javascript = "<script type=\"text/javascript\">\nfunction updatesubpages(theform) {\n\n  var pageval = theform.page.value;\n\n";

    foreach ($page_arr AS $key => $val) {
      $page_select .= "              <option value=\"$key\">$val[title]</option>\n";
      $javascript .= "  if (pageval == '$key') {\n    theform.pagehelp.value = '$val[help]';\n    theform.subpage.selectedIndex = 0;\n    theform.subpage.options.length = 0;\n";
      $count = 0;
      if ($val[sub]) {
        foreach ($val[sub] AS $subkey => $subval) {
          $javascript .= "    theform.subpage.options[".$count++."] = new Option('$subval[title]','$subkey');\n";
        }
      }
      $javascript .= "    theform.subpage.options[".$count++."] = new Option('View Top Category','');\n  }\n";
    }

    $javascript .= "}\n</script>";

    $tablerows = returnminitablerow("Select pages to edit:","\n            <select name=\"page\" class=\"form\" onchange=\"updatesubpages(this.form)\" size=\"10\" style=\"width:200px\">\n$page_select            </select>\n          ","<textarea class=\"pagehelp\" name=\"pagehelp\" rows=\"7\" cols=\"50\" readonly=\"readonly\"></textarea>");
    $tablerows .= returnminitablerow("Select subpages to edit:","\n            <select name=\"subpage\" class=\"form\" size=\"5\" style=\"width:200px\">\n              <option value=\"\">Select A Page Category First</option>\n            </select>\n          ","&nbsp;");

  } else {
    $tablerows = "      <ul>\n";

    foreach ($page_arr AS $key => $val) {
      if ($val[title]) {
        $tablerows .= "        <li><b>$val[title]</b> ".iif($expand == $key,returnlinkcode("Collapse","admin.php?action=theme_pageselect&themeid=$themeid"),returnlinkcode("Expand","admin.php?action=theme_pageselect&themeid=$themeid&expand=$key"))."</li>\n";
      } else {
        $tablerows .= "      </ul>\n      <ul>\n";
      }

      if (($expand == $key) & ($val[title] != "")) {
        $tablerows .= "          <ul>\n";

        if ($val[sub]) {
          foreach ($val[sub] AS $subkey => $subval) {

            unset($pages);

            $tablerows .= "            <li><b>$subval[title]</b> ".iif($subexpand == $subkey,returnlinkcode("Collapse","admin.php?action=theme_pageselect&themeid=$themeid&expand=$key"),returnlinkcode("Expand","admin.php?action=theme_pageselect&themeid=$themeid&expand=$key&subexpand=$subkey"))."</li>\n";

            if ($subexpand == $subkey) {
              $tablerows .= "              <ul>\n";
              $tablerows .= returnpagelist("(title LIKE '".$key."_".$subkey."%')",2);
              $tablerows .= "              </ul><br />\n";
            }

            $sql_arr[] = "(title NOT LIKE '".$key."_".$subkey."%')";
          }
        }

        $sql_arr[] = "(title LIKE '".$key."_%')";
        $tablerows .= returnpagelist($sql_arr,1);
        $tablerows .= "          </ul><br />\n";
      }

    }
    $tablerows .= "      </ul>\n";
  }

  echohtmlheader($javascript);

  if ($adminpageview == "select") {
    echoformheader("theme_page","Edit Theme Pages");
    updatehiddenvar("themeid",$themeid);
  } else {
    echotableheader("Edit Theme Pages");
  }

  echotabledescription("You may use this page to edit every single page of your themes to create exactly the layout of your pages you wish.  To edit the pages on your site, please select the area of the site you wish to update.");
  echotablerow("Theme Name:",$data_arr[title]." |".returnlinkcode("Edit","admin.php?action=theme_edit&id=$themeid")." |".returnlinkcode("Delete","admin.php?action=theme_delete&id=$themeid"),"",15);
  echotablerow("Page Set:",$data_arr[pageset_title]." |".returnlinkcode("Edit","admin.php?action=theme_pageset_edit&id=$data_arr[pagesetid]&themeid=$themeid")." |".returnlinkcode("Delete","admin.php?action=theme_pageset_delete&id=$data_arr[pagesetid]"),"",15);
  echotabledescription(returnlinkcode("Add Custom Page","admin.php?action=theme_page_add&themeid=$themeid")." |".returnlinkcode("Add Page Set","admin.php?action=theme_pageset_add")." |".iif($adminpageview == "threaded",returnlinkcode("View Select Display","admin.php?action=theme_pageselect&themeid=$themeid&pageview=select"),returnlinkcode("View Threaded Display","admin.php?action=theme_pageselect&themeid=$themeid&pageview=threaded")));

  if ($adminpageview == "select") {
    echotabledescription("\n".returnminitable($tablerows,0,100)."    ");
    echoformfooter();
  } else {
    echotabledescription("\n$tablerows    ");
    echotablefooter();
  }

  echohtmlfooter();

break;


case "theme_page":

  settype($themeid,"integer");
  if ($temp = query_first("SELECT pagesetid FROM news_theme WHERE id = $themeid")) {
    $pagesetid = $temp[pagesetid];
  } else {
    adminerror("Invalid ID","You have specified an invalid ID.");
  }

  if (query_first("SELECT id FROM news_page WHERE (title LIKE 'custom_%') AND (pagesetid = $pagesetid) LIMIT 1")) {
    $page_arr[custom][title] = "Custom Pages";
    $page_arr[custom][help] = "This category contains all of your custom pages for this page set.";
  }

  $moddata = getmoddata();
  while ($mod_arr = fetch_array($moddata)) {
    $page_arr[$mod_arr[name]][title] = "$mod_arr[text] module pages";
    $page_arr[$mod_arr[name]][help] = "This category contains all of the pages for the module $mod_arr[text].";
  }

  if (!$page_arr[$page]) {
    adminerror("Invalid Page Category","You have specified an invalid page category.");
  } elseif ($subpage & !$page_arr[$page][sub][$subpage]) {
    adminerror("Invalid Sub Page Category","You have specified an invalid sub page category.");
  }

  if ($subpage) {
    $pagetitle = $page_arr[$page][sub][$subpage][title];
    $pagehelp = $page_arr[$page][sub][$subpage][help];
  } else {
    $pagetitle = $page_arr[$page][title];
    $pagehelp = $page_arr[$page][help];
  }

  $title = $page."_".$subpage;

  echohtmlheader();
  echotableheader("Edit Pages - $pagetitle");
  echotabledescription("Following is a list of pages and their details within the category you specified.  If the title is displayed in <span class=\"red\">red</span> then this indicates that it has been altered, to revert it back to the original just click the link next to it.");
  echotabledescription(returnlinkcode("Select Different Category","admin.php?action=theme_pageselect&themeid=$themeid"));
  echotabledescription($pagehelp);

  if ($page_arr[$page][sub] & !$subpage) {
    echotabledescription("The page category you specified has sub categories within it, to edit pages from those sub categories please use the links below.");
    foreach ($page_arr[$page][sub] AS $key => $val) {
      echotabledescription(returnlinkcode($val[title],"admin.php?action=theme_page&page=$page&subpage=$key&themeid=$themeid&pagesetid=$pagesetid")."<br />$val[help]");
      $excludesql .= " AND (title NOT LIKE '".$page."_$key%')";
    }
  }

  if ($subpage) {
    echotabledescription(returnlinkcode("View Parent Category","admin.php?action=theme_page&page=$page&subpage=&themeid=$themeid&pagesetid=$pagesetid"));
  }

  $getdata = query("SELECT id,title,description,pagesetid FROM news_page WHERE (title LIKE '$title%')$excludesql AND (pagesetid IN ('-2','-1','$pagesetid')) ORDER BY pagesetid,title");

  while ($data_arr = fetch_array($getdata)) {
    $pages[$data_arr[title]][id] = $data_arr[id];
    $pages[$data_arr[title]][description] = $data_arr[description];
    $pages[$data_arr[title]][altered] = iif(($data_arr[pagesetid] == -1) | ($data_arr[pagesetid] == -2),0,1);
  }

  if ($pages) {
    foreach ($pages AS $key => $val) {
      echotablerow(iif($val[altered],"<span class=\"red\"><b>$key</b></span>","<b>$key</b>")."<br />$val[description]",iif($val[altered],iif(substr($key,0,7) == "custom_","<a href=\"admin.php?action=theme_page_edit&id=$val[id]&pagesetid=$pagesetid&themeid=$themeid\">Edit</a> | <a href=\"admin.php?action=theme_page_delete&id=$val[id]&themeid=$themeid\">Delete</a>","<a href=\"admin.php?action=theme_page_edit&id=$val[id]&pagesetid=$pagesetid&themeid=$themeid\">Edit</a> | <a href=\"admin.php?action=theme_page_view&title=".urlencode($key)."\" target=\"_blank\">View Original</a> | <a href=\"admin.php?action=theme_page_revert&id=$val[id]&themeid=$themeid\">Revert</a>"),"<a href=\"admin.php?action=theme_page_edit&id=$val[id]&pagesetid=$pagesetid&themeid=$themeid\">Edit Original</a>"),"",50);
    }
  }

  echotablefooter();
  echohtmlfooter();

break;

case "theme_page_view":

  $title = urldecode($title);

  if ($data_arr = query_first("SELECT pagesetid,description FROM news_page WHERE (title = '$title') AND ((pagesetid = '-1') OR (pagesetid = '-2'))")) {

    $pagetype = returnpagetype($title,$data_arr[pagesetid]);

    if ($pagetype > 2) {
      $data = @join("",@file("pages/default/".$title.".vnp"));
    } else {
      $data = @join("",@file("pages/default/mod/".$title.".vnp"));
    }

    echohtmlheader();
    echotableheader("View Page Original");
    echotabledescription("Below you can view the original page for the name shown below.");
    echotablerow("Name:",$title);
    echotablerow("Description:",$data_arr[description]);
    echotextareacode("Page:","page",$data,35,125);
    echotablefooter();
    echohtmlfooter();
  } else {
    adminerror("Invalid Title","You have specified an invalid title");
  }

break;

case "theme_page_add":

  settype($themeid,"integer");
  if ($temp = query_first("SELECT id FROM news_theme WHERE id = $themeid")) {
    echohtmlheader();
    echoformheader("theme_page_new","Add Custom Page");
    updatehiddenvar("themeid",$temp[id]);
    echotabledescription("Below you can add a custom page for use on your site.");
    echoinputcode("Name:<br /><span class=\"red\">(Must begin with custom_)</span>","title","custom_");
    echotextareacode("Description: <span class=\"red\">(Optional)</span>","description");
    echotextareacode("Page Content:","page","",35,100);
    echoformfooter();
    echohtmlfooter();
  } else {
    adminerror("Invalid ID","you have specified an invalid id.");
  }

break;

case "theme_page_new":

  if ($title == "") {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  if (($title == "custom_") | (substr($title,0,7) != "custom_") | (preg_match("/\W/i",$title))) {
    adminerror("Invalid Title","Page titles must begin with custom_ (note they cannot be called that) and must only contain letters, numbers and the character _ (note no spaces allowed).");
  }

  settype($themeid,"integer");

  if ($temp = query_first("SELECT pagesetid FROM news_theme WHERE id = $themeid")) {
    $pagesetid = $temp[pagesetid];
  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

  if (returnpagetype($title,$pagesetid) != 0) {
    adminerror("Name Not Unique","Page names must be unique, please go back and enter a different name for the page which is not already in use.");
  }

  $page = stripslashes($page);
  writepagebit("pages/user/".$title."_".$pagesetid.".vnp",$page);

  query("INSERT INTO news_page VALUES (NULL,'$pagesetid','$title','$description','0')");

  if ($HTTP_COOKIE_VARS[pageview] == "threaded") {
    echoadminredirect("admin.php?action=theme_pageselect&themeid=$themeid&expand=custom");
  } else {
    echoadminredirect("admin.php?action=theme_page&page=custom&subpage=&themeid=$themeid&pagesetid=$pagesetid");
  }
  exit;

break;

case "theme_page_edit":

  settype($pagesetid,"integer");
  if ($pageset = query_first("SELECT id,title FROM news_pageset WHERE id = $pagesetid")) {
    verifyid("news_page",$id);
    verifyid("news_theme",$themeid);
  } else {
    adminerror("Invalid ID","You have specified an invalid ID.");
  }

  $data_arr = query_first("SELECT title,pagesetid,description,onserver FROM news_page WHERE id = $id");

  $pagetype = returnpagetype($data_arr[title],$data_arr[pagesetid]);

  if ($pagetype == 3) {
    $data = @join("",@file("pages/user/".$data_arr[title]."_".$data_arr[pagesetid].".vnp"));
  } elseif ($pagetype == 4) {
    $data = @join("",@file("pages/default/".$data_arr[title].".vnp"));
  } elseif ($pagetype == 1) {
    $data = @join("",@file("pages/user/mod/".$data_arr[title]."_".$data_arr[pagesetid].".vnp"));
  } elseif ($pagetype == 2) {
    $data = @join("",@file("pages/default/mod/".$data_arr[title].".vnp"));
  }

  echohtmlheader();
  echoformheader("theme_page_update","Edit Page");
  updatehiddenvar("id",$id);
  updatehiddenvar("themeid",$themeid);
  updatehiddenvar("pagesetid",$pageset[id]);
  echotabledescription("Below you can edit the pages on your site as you wish, just edit whatever you want and press submit.");
  echotablerow("Name:",$data_arr[title]);
  echotablerow("Page Set:",$pageset[title]);
  echotablerow("Description:",$data_arr[description].iif($data_arr[onserver],"<br />This page is stored on the server so variables which change with the user, eg. the style variables, must be proceeded by a \\ eg. \\\$stylevar.","<br />This page is not stored on the server."));
  echotextareacode("Page Content:","page",$data,35,125);
  echoformfooter();
  echohtmlfooter();

break;

case "theme_page_update":

  settype($id,"integer");
  if ($data_arr = query_first("SELECT title,pagesetid,description,onserver FROM news_page WHERE id = $id")) {
    verifyid("news_pageset",$pagesetid);
  } else {
    adminerror("Invalid ID","You have specified an invalid ID.");
  }

  $page = stripslashes($page);

  $pagetype = returnpagetype($data_arr[title],$data_arr[pagesetid]);

  if ($pagetype > 2) {
    writepagebit("pages/user/".$data_arr[title]."_".$pagesetid.".vnp",$page);
  } else {
    writepagebit("pages/user/mod/".$data_arr[title]."_".$pagesetid.".vnp",$page);
  }

  if (($data_arr[pagesetid] == "-1") | ($data_arr[pagesetid] == "-2")) {
    query("INSERT INTO news_page VALUES (NULL,'$pagesetid','" . addslashes($data_arr[title]) . "','" . addslashes($data_arr[description]) . "','$data_arr[onserver]')");
  }

  $old_themeid = $themeid;

  writeallpages();

  if (query_first("SELECT id FROM news_page WHERE (title LIKE 'custom_%') AND (pagesetid = $data_arr[pagesetid]) LIMIT 1")) {
    $page_arr[custom][title] = "Custom Pages";
  }

  $moddata = getmoddata();

  while ($mod_arr = fetch_array($moddata)) {
    $page_arr[$mod_arr[name]][title] = "$mod_arr[text] module pages";
  }

  $name = explode("_",$data_arr[title]);
  $page = $name[0];
  $subpage = $name[1];

  if (substr($data_arr[title],0,16) == "misc_recent_post") {
    $subpage .= "_$name[2]";
  } elseif (substr($data_arr[title],0,19) == "misc_theme_selector") {
    $subpage .= "_$name[2]";
  } elseif (substr($data_arr[title],0,13) == "misc_page_nav") {
    $subpage .= "_$name[2]";
  } elseif (substr($data_arr[title],0,14) == "main_news_post") {
    $subpage .= "_$name[2]";
  }

  if ($use_forum & (substr($data_arr[title],0,10) == "user_login")) {
    $page .= "_$name[1]";
    unset($subpage);
  }

  if (!$page_arr[$page][sub][$subpage][title]) {
    unset($subpage);
  }

  if ($HTTP_COOKIE_VARS[pageview] == "threaded") {
    echoadminredirect("admin.php?action=theme_pageselect&themeid=$old_themeid&expand=$page&subexpand=$subpage");
  } else {
    echoadminredirect("admin.php?action=theme_page&page=$page&subpage=$subpage&themeid=$old_themeid");
  }
  exit;

break;

case "theme_page_delete":

  verifyid("news_page",$id);

  if (query_first("SELECT id FROM news_page WHERE (id = $id) AND ((pagesetid = '-1') OR (pagesetid = '-2'))")) {
    adminerror("Cannot Delete Defaults","You cannot delete the default pages");
  }

  echodeleteconfirm("custom page","theme_page_kill",$id,"","&themeid=$themeid");

break;

case "theme_page_revert":

  verifyid("news_page",$id);

  $temp = query_first("SELECT pagesetid FROM news_page WHERE id = $id");

  if (($temp[pagesetid] == "-1") | ($temp[pagesetid] == "-2")) {
    adminerror("Cannot Revert Defaults","You cannot revert default pages to their original as they already are the original!");
  }

  echodeleteconfirm("page","theme_page_kill",$id,"This will change the selected page back to the original.","&themeid=$themeid","revert");

break;

case "theme_page_kill":

  settype($id,"integer");
  if ($temp = query_first("SELECT pagesetid,title FROM news_page WHERE id = $id")) {
    $pagesetid = $temp[pagesetid];
    $title = $temp[title];
  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

  verifyid("news_theme",$themeid);

  if (($pagesetid == "-1") | ($pagesetid == "-2")) {
    adminerror("Cannot Delete Defaults","You cannot delete the default pages");
  }

  query("DELETE FROM news_page WHERE id = $id");

  $pagetype = returnpagetype($title,$pagesetid);

  if ($pagetype > 2) {
    if (!@unlink("pages/user/".$title."_".$pagesetid.".vnp")) {
      adminerror("Cannot Delete","The page cannot be deleted, you must manually delete the file /pages/user/".$title."_".$pagesetid.".vnp from your server, if you do not do this then this page will continue to be used on your site.");
    }
  } else {
    if (!@unlink("pages/user/mod/".$title."_".$pagesetid.".vnp")) {
      adminerror("Cannot Delete","The page cannot be deleted, you must manually delete the file /pages/user/mod/".$title."_".$pagesetid.".vnp from your server, if you do not do this then this page will continue to be used on your site.");
    }
  }

  $old_themeid = $themeid;

  writeallpages();

  $name = explode("_",$title);
  $page = $name[0];
  $subpage = $name[1];

  if (substr($title,0,16) == "misc_recent_post") {
    $subpage .= "_$name[2]";
  } elseif (substr($title,0,19) == "misc_theme_selector") {
    $subpage .= "_$name[2]";
  } elseif (substr($title,0,13) == "misc_page_nav") {
    $subpage .= "_$name[2]";
  } elseif (substr($title,0,14) == "main_news_post") {
    $subpage .= "_$name[2]";
  }

  if ($use_forum & (substr($title,0,10) == "user_login")) {
    $page .= "_$name[1]";
    unset($subpage);
  }

  if (!$page_arr[$page][sub][$subpage][title]) {
    unset($subpage);
  }

  if ($name[0] == "custom") {
    if (query_first("SELECT id FROM news_page WHERE (title LIKE 'custom_%') AND (pagesetid = $pagesetid) LIMIT 1")) {
      if ($HTTP_COOKIE_VARS[pageview] == "threaded") {
        echoadminredirect("admin.php?action=theme_pageselect&themeid=$old_themeid&expand=$page");
      } else {
        echoadminredirect("admin.php?action=theme_page&page=$page&themeid=$old_themeid");
      }
    } else {
      echoadminredirect("admin.php?action=theme_pageselect&themeid=$old_themeid");
    }
  } else {
    if ($HTTP_COOKIE_VARS[pageview] == "threaded") {
      echoadminredirect("admin.php?action=theme_pageselect&themeid=$old_themeid&expand=$page&subexpand=$subpage");
    } else {
      echoadminredirect("admin.php?action=theme_page&page=$page&subpage=$subpage&themeid=$old_themeid");
    }
  }
  exit;

break;

case "theme_pageset_add":

  echohtmlheader();
  echoformheader("theme_pageset_new","Add Page Set");
  echotabledescription("Use this page to add a new page set for use within your site themes.");
  echoinputcode("Title:","title");
  echoformfooter();
  echohtmlfooter();

break;

case "theme_pageset_new":

  if ($title == "") {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  query("INSERT INTO news_pageset VALUES (NULL,'$title')");

  writeallpages();
  echoadminredirect("admin.php?action=theme");
  exit;

break;

case "theme_pageset_edit":

  settype($id,"integer");
  if ($data_arr = query_first("SELECT title FROM news_pageset WHERE id = $id")) {
    if ($themeid) {
      verifyid("news_theme",$themeid);
    }

    echohtmlheader();
    echoformheader("theme_pageset_update","Edit Page Set");
    updatehiddenvar("id",$id);
    updatehiddenvar("themeid",$themeid);
    echotabledescription("Use this page to edit a page set for use within your themes on your site.");
    echoinputcode("Title:","title",$data_arr[title]);
    echoformfooter();
    echohtmlfooter();

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "theme_pageset_update":

  if ($title == "") {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  verifyid("news_pageset",$id);
  if ($themeid) {
    verifyid("news_theme",$themeid);
  }

  query("UPDATE news_pageset SET title = '$title' WHERE id = $id");

  $old_themeid = $themeid;

  writeallpages();

  echoadminredirect("admin.php?action=theme");

break;

case "theme_pageset_delete":

  verifyid("news_pageset",$id);

  if ($temp = query_first("SELECT title FROM news_theme WHERE pagesetid = $id LIMIT 1")) {
    adminerror("Cannot Delete","You cannot delete this page set as it is currently in use by the theme $temp[title]");
  }

  echodeleteconfirm("page set","theme_pageset_kill",$id);

break;

case "theme_pageset_kill":

  verifyid("news_pageset",$id);

  if ($temp = query_first("SELECT title FROM news_theme WHERE pagesetid = $id LIMIT 1")) {
    adminerror("Cannot Delete","You cannot delete this page set as it is currently in use by the theme $temp[title]");
  }

  query("DELETE FROM news_pageset WHERE id = $id");
  query("DELETE FROM news_page WHERE pagesetid = $id");

  $dir_arr[] = "pages/user/mod";
  $dir_arr[] = "pages/user";
  $dir_arr[] = "static/comment";
  $dir_arr[] = "static/index";
  $dir_arr[] = "static/polls";
  $dir_arr[] = "static/sub_pages";
  $dir_arr[] = "static";

  foreach ($dir_arr AS $path) {
    if (is_dir($path)) {

      if ($handle = @opendir($path)) {

        while (false !== ($file = readdir($handle))) {

          $filetype_arr = explode(".",$file);
          $filetype = $filetype_arr[count($filetype_arr)-1];

          if (($filetype == "php") | ($filetype == "vnp")) {

            unset($filetype_arr[count($filetype_arr)-1]);

            $filename = join(".",$filetype_arr);

            $setid = explode("_",$filename);
            $setid = $setid[count($setid)-1];

            if ($setid == $id) {
              unlink($path."/".$filename.".".$filetype);
            }
          }
        }
      }

      @closedir($handle);
    }
  }

  writeallpages();
  echoadminredirect("admin.php?action=theme");
  exit;

break;

case "theme_style":

  $defaultstyles['body'][title] = "Body Tag";
  $defaultstyles['body'][description] = "This will replace the body tag on each page with whatever you specify here, use it to set your page background, margins etc.";

  $defaultstyles['mainbgcolor'][title] = "Main background colour";
  $defaultstyles['mainbgcolor'][description] = "Set the main background colour of the your pages.";

  $defaultstyles['menubgcolor'][title] = "Menu background colour";
  $defaultstyles['menubgcolor'][description] = "Set the background colour of the menu shown on your pages.";

  $defaultstyles['headerbgcolor'][title] = "Header background colour";
  $defaultstyles['headerbgcolor'][description] = "Set the background colour of the headers displayed on your pages.";

  $defaultstyles['bordercolor'][title] = "Border colour";
  $defaultstyles['bordercolor'][description] = "Set the colour of the borders displayed on your pages.";

  $defaultstyles['maincellpadding'][title] = "Main cell padding";
  $defaultstyles['maincellpadding'][description] = "Set the cell padding for the tables on your pages.";

  $defaultstyles['maincellspacing'][title] = "Main cell spacing";
  $defaultstyles['maincellspacing'][description] = "Set the cell spacing for the tables on your pages.";

  $defaultstyles['maintablewidth'][title] = "Main table width";
  $defaultstyles['maintablewidth'][description] = "Set the width of the main tables on your site.";

  $defaultstyles['headertablewidth'][title] = "Header table width";
  $defaultstyles['headertablewidth'][description] = "Set the width of the header tables on your site.";

  $defaultstyles['imagefolder'][title] = "Image Folder";
  $defaultstyles['imagefolder'][description] = "Set the folder containing images for this theme, note please do not include the trailing slash.";

  $defaultstyles['csspath'][title] = "Path to CSS file";
  $defaultstyles['csspath'][description] = "Set the relative (to index.php file) path to the css file for your theme.";

  $defaultstyles['logopath'][title] = "Path to Logo";
  $defaultstyles['logopath'][description] = "Set the relative (to index.php file) path to the css file for your theme.";

  $defaultstyles['submitbutton'][title] = "Submit Code";
  $defaultstyles['submitbutton'][description] = "Set the code to insert in place for a submit button.";

  $defaultstyles['htmldoctype'][title] = "HTML Document Type";
  $defaultstyles['htmldoctype'][description] = "Set the html doctype for your pages.";

  $defaultstyles['charset'][title] = "HTML Character Set";
  $defaultstyles['charset'][description] = "Set the html character set for your pages.";

  settype($themeid,"integer");

  if ($data_arr = query_first("SELECT news_theme.title,news_theme.stylesetid,news_styleset.title AS stylesettitle FROM news_theme LEFT JOIN news_styleset ON news_theme.stylesetid = news_styleset.id WHERE news_theme.id = $themeid")) {

    echohtmlheader();
    echoformheader("theme_style_update","Edit Theme Style");
    updatehiddenvar("themeid",$themeid);
    echotabledescription("VirtuaNews provides you with the ability to quickly edit the look of your theme with the use of styles.  There is no need to create a new page set if you just want to change the colour of a theme, using styles you can edit certain variables which can be used in your pages which will change with each theme.");
    echotabledescription(returnlinkcode("Edit Theme","admin.php?action=theme_edit&id=$themeid")." |".returnlinkcode("Add Style Set","admin.php?action=theme_styleset_add"));
    echotablerow("Theme Name:",$data_arr[title]." |".returnlinkcode("Edit","admin.php?action=theme_edit&id=$themeid")." |".returnlinkcode("Delete","admin.php?action=theme_delete&id=$themeid"),"",15);
    echotablerow("Style Set:",$data_arr[stylesettitle]." |".returnlinkcode("Edit","admin.php?action=theme_styleset_edit&id=$data_arr[stylesetid]&themeid=$themeid")." |".returnlinkcode("Delete","admin.php?action=theme_styleset_delete&id=$data_arr[stylesetid]"),"",15);
    echotablefooter();

    $getstyle = query("SELECT id,varname,value,stylesetid FROM news_style WHERE stylesetid IN ('-1','$data_arr[stylesetid]') ORDER BY stylesetid");
    while ($style_arr = fetch_array($getstyle)) {
      $styles[$style_arr[varname]][id] = $style_arr[id];
      $styles[$style_arr[varname]][data] = $style_arr[value];
      $styles[$style_arr[varname]][altered] = iif($style_arr[stylesetid] == -1,0,1);
    }

    echotableheader("Default Style Variables");
    echotabledescription("Following is a list of the default style variables, the style variable displayed by each input will be replaced by whatever you specify when the page is outputted to the user, eg. in the pages you edit you will see \$stylevar[body] in places, this will be replaced by whatever you specify as your body tag for this theme when the page is displayed.");
    echotabledescription("Tags which you have altered are displayed in <span class=\"red\">red</span>, to revert them back to the original click the link next to it.");
    echotabledescription("Once you have finished your changes please press submit to save your changes.");

    foreach ($defaultstyles AS $key => $val) {
      echoinputcode(iif($styles[$key][altered],"<span class=\"red\">","")."<b>$val[title]</b>".iif($styles[$key][altered],"</span> |".returnlinkcode("Revert","admin.php?action=theme_style_revert&themeid=$themeid&setid=$data_arr[stylesetid]&findword=".urlencode($key))." |".returnlinkcode("View Original","admin.php?action=theme_style_view&varname=".urlencode($key),1),"")."<br />$val[description]<br />Replaces the variable <b>\$stylevar[".htmlspecialchars($key)."]</b>","style[".$styles[$key][id]."]",$styles[$key][data],40,0,60);
      unset($styles[$key]);
    }

    echotabledescription("<input type=\"submit\" name=\"submit\" value=\"Submit\" class=\"form\"><input type=\"reset\" name=\"reset\" value=\"Reset\" class=\"form\">",2,1);
    echotablefooter();

    echotableheader("Custom Style Variables");
    echotabledescription("Following is a list of the custom variables which have been added to this style.  To update them please alter the input box as you wish and press submit when you have finished to save your changes.");

    foreach ($styles AS $key => $val) {
      echoinputcode("Replace <b>\$stylevar[".htmlspecialchars($key)."]</b> with:<br />".returnlinkcode("Delete","admin.php?action=theme_style_delete&id=$val[id]&themeid=$themeid"),"style[$val[id]]",$val[data],40,0,50);
    }

    echotabledescription("If you wish to add further custom variables to be replaced, you can do so by using the 2 boxes below, just enter the details and press submit to save your changes.  You only need to enter the variable to be replaced, do not enter the \$stylevar bit.");
    echotablerow("Replace: <input type=\"text\" name=\"new1[varname]\" class=\"form\" size=\"40\">","With: <input type=\"text\" name=\"new1[value]\" class=\"form\" size=\"40\">","",50);
    echotablerow("Replace: <input type=\"text\" name=\"new2[varname]\" class=\"form\" size=\"40\">","With: <input type=\"text\" name=\"new2[value]\" class=\"form\" size=\"40\">","",50);

    echoformfooter();
    echohtmlfooter();

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "theme_style_update":

  settype($themeid,"integer");
  if ($temp = query_first("SELECT stylesetid FROM news_theme WHERE id = $themeid")) {
    $stylesetid = $temp[stylesetid];

    if ($new1[varname]) {
      query("INSERT INTO news_style VALUES (NULL,'$stylesetid','$new1[varname]','$new1[value]')");
    }

    if ($new2[varname]) {
      query("INSERT INTO news_style VALUES (NULL,'$stylesetid','$new2[varname]','$new2[value]')");
    }

    $getstyle = query("SELECT id,varname,value,stylesetid FROM news_style WHERE stylesetid IN ('-1','$stylesetid') ORDER BY stylesetid");
    while ($style_arr = fetch_array($getstyle)) {

      if (isset($style[$style_arr[id]]) & ($style_arr[value] != stripslashes($style[$style_arr[id]]))) {
        if ($style_arr[stylesetid] == -1) {
          query("INSERT INTO news_style VALUES (NULL,'$stylesetid','$style_arr[varname]','".$style[$style_arr[id]]."')");
        } else {
          query("UPDATE news_style SET value = '".$style[$style_arr[id]]."' WHERE id = $style_arr[id]");
        }
      }
    }

    writeallpages();
    echoadminredirect("admin.php?action=theme_style&themeid=$themeid");
    exit;

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "theme_style_view":

  if ($data_arr = query_first("SELECT varname,value FROM news_style WHERE (varname = '".urldecode($varname)."') AND (stylesetid = '-1')")) {

    echohtmlheader();
    echotableheader("View Original Style Varname");
    echotabledescription("This page will display the default replacement for the style variable that you specified.");
    echotablerow("\$stylevar[$data_arr[varname]] is replaced with:",htmlspecialchars($data_arr[value]),"",25);
    echotablefooter();
    echohtmlfooter();

  } else {
    adminerror("Invalid Findword","You have specified an invalid word to view the original for.");
  }

break;

case "theme_style_delete":

  verifyid("news_theme",$themeid);
  settype($id,"integer");
  if ($temp = query_first("SELECT stylesetid FROM news_style WHERE id = $id")) {
    if ($temp[stylesetid] == -1) {
      adminerror("Cannot Delete Defaults","You cannot delete the default style variables.");
    }

    echodeleteconfirm("custom style tag","theme_style_kill",$id,"","&themeid=$themeid");

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "theme_style_revert":

  verifyid("news_styleset",$setid);
  verifyid("news_theme",$themeid);

  if ($data_arr = query_first("SELECT id FROM news_style WHERE (stylesetid = $setid) AND (varname = '".urldecode($findword)."') LIMIT 1")) {
    echodeleteconfirm("style tag","theme_style_kill",$data_arr[id]," This will revert the style tag you specified back to the original.","&themeid=$themeid","revert");
  } else {
    admineror("Invalid Style Tag","You have specified an invalid style tag as there is no such tag that exists as you specified in the style set you specified.");
  }

break;

case "theme_style_kill":

  settype($id,"integer");
  if ($temp = query_first("SELECT stylesetid FROM news_style WHERE id = $id")) {

    verifyid("news_theme",$themeid);

    if ($temp[stylesetid] == -1) {
      adminerror("Cannot Delete Defaults","You cannot delete the default style variables.");
    }

    query("DELETE FROM news_style WHERE id = $id");

    writeallpages();
    echoadminredirect("admin.php?action=theme_style&themeid=$themeid");
    exit;

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "theme_styleset_add":

  echohtmlheader();
  echoformheader("theme_styleset_new","Add Style Set");
  echotabledescription("Use this form to add a new style set for use in your themes on your site.");
  echoinputcode("Title:","title");
  echoformfooter();
  echohtmlfooter();

break;

case "theme_styleset_new":

  if ($title == "") {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  query("INSERT INTO news_styleset VALUES (NULL,'$title')");
  writeallpages();
  echoadminredirect("admin.php?action=theme");
  exit;

break;

case "theme_styleset_edit":

  settype($id,"integer");

  if ($data_arr = query_first("SELECT title FROM news_styleset WHERE id = $id")) {
    if ($themeid) {
      verifyid("news_theme",$themeid);
    }
    echohtmlheader();
    echoformheader("theme_styleset_update","Edit Style Set");
    updatehiddenvar("id",$id);
    updatehiddenvar("themeid",$themeid);
    echotabledescription("Using this form you can edit the details of the style sets for use within your themes on your site.");
    echoinputcode("Title:","title",$data_arr[title]);
    echoformfooter();
    echohtmlfooter();

  } else {
    adminerror("Invalid ID","You have specified an invalid id.");
  }

break;

case "theme_styleset_update":

  if ($title == "") {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  verifyid("news_styleset",$id);

  query("UPDATE news_styleset SET title = '$title' WHERE id = $id");


  writeallpages();

  if ($themeid) {
    echoadminredirect("admin.php?action=theme_style&themeid=$themeid");
  } else {
    echoadminredirect("admin.php?action=theme");
  }
  exit;

break;

case "theme_styleset_delete":

  verifyid("news_styleset",$id);

  if ($temp = query_first("SELECT title FROM news_theme WHERE stylesetid = $id LIMIT 1")) {
    adminerror("Cannot Delete","You cannot delete this style set as it is currently in use by the theme $temp[title]");
  }

  echodeleteconfirm("style set","theme_styleset_kill",$id);

break;

case "theme_styleset_kill":

  verifyid("news_styleset",$id);

  if ($temp = query_first("SELECT title FROM news_theme WHERE stylesetid = $id LIMIT 1")) {
    adminerror("Cannot Delete","You cannot delete this style set as it is currently in use by the theme $temp[title]");
  }

  query("DELETE FROM news_styleset WHERE id = $id");
  query("DELETE FROM news_style WHERE stylesetid = $id");

  writeallpages();
  echoadminredirect("admin.php?action=theme");
  exit;

break;

default:
  adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/theme.php
|| ####################################################################
\*======================================================================*/

?>
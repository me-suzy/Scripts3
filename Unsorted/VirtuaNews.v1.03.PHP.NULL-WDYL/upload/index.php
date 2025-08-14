<?php


include("global.php");

function returncatdetails($cat,$parentid=0,$sub=0) {

  global $showsubcatlist;

  if ($cat[display] & ($cat[parentid] == $parentid) & isuserallowed($cat[allowview])) {

    if ($cat[image]) {
      eval("\$cat[image] = \"".returnpagebit("main_catlist_image")."\";");
    }

    if ($cat[description]) {
      $cat[description] = qhtmlparse($subcat[description],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
    }

    if ($cat[displaymain]) {
      eval("\$cat[namelink] = \"".returnpagebit("main_catlist_name_link")."\";");
    } else {
      eval("\$cat[namelink] = \"".returnpagebit("main_catlist_name_nolink")."\";");
    }

    if ($sub) {
      eval("\$data = \"".returnpagebit("main_catlist_sub_record")."\";");
    } else {
      eval("\$data = \"".returnpagebit("main_catlist_record")."\";");

      if ($showsubcatlist) {
        foreach ($GLOBALS[cat_arr] AS $key => $val) {
          $val[id] = $key;
          $data .= returncatdetails($val,$cat[id],1);
        }
      }
    }
  }

  return $data;
}

if (($action != "news") & ($action != "cat") & ($action != "custom")) {

  switch ($startpage) {
    case "articles": header_redirect("articles.php","Articles"); break;
    case "search": header_redirect("search.php","Search"); break;
    case "archive": header_redirect("archive.php","Archive"); break;
    case "custom": $action = "custom"; break;
    case "cat": $action = "cat"; break;
    default: $action = "news";
  }

  if (substr($startpage,0,4) == "mod_") {
    header_redirect("modules.php?modname=".substr($startpage,4),substr($startpage,4));
  }

}

if ($action == "news") {

  if (!isuserallowed($cat_arr[$catid][allowview]) | !$cat_arr[$catid][displaymain]) {
    standarderror("category_hidden");
  }

  $navbar = makenavbar($cat_arr[$catid][name]);
  include("static/index/index_".$catid."_".$pagesetid.".php");
}

if ($action == "cat") {

  if (!preg_match("/(catid=)/",getenv("QUERY_STRING"))) {
    $catid = 0;
  }

  $parentlink = iif($cat_arr[$catid][parentid],"index.php?action=cat&catid=".$cat_arr[$catid][parentid],"index.php?action=cat");

  unset($cat_list);

  if (!empty($cat_arr)) {
    foreach ($cat_arr as $key => $cat) {
      $cat[id] = $key;
      $cat_list .= returncatdetails($cat,$catid);
    }
  }

  if (!$cat_list) {
    eval("\$cat_list = \"".returnpagebit("main_catlist_empty")."\";");
  }

  $navbar = makenavbar("News Categories");
  include("static/sub_pages/catlist_".$pagesetid.".php");
}

if ($action == "custom") {
  $navbar = makenavbar("Welcome");
  include("static/sub_pages/custom_page_".$pagesetid.".php");
}

/*======================================================================*\
|| ####################################################################
|| # File: index.php
|| ####################################################################
\*======================================================================*/

?>
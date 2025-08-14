<?php


require("global.php");

if (!$allowsearch) {
  standarderror("search_disabled");
}

function returncatoption($category,$parentid=0,$level=0) {

  global $stylevar;

  if (($category[parentid] == $parentid) & $category[display] & $category[displaymain] & isuserallowed($category[allowview])) {

    if ($level == 3) {
      $category[name] = "------ $category[name]";
    } elseif ($level == 2) {
      $category[name] = "--- $category[name]";
    }

    eval("\$links .= \"".returnpagebit("search_page_cat_option")."\";");

    if (($level == 1) | ($level == 2)) {

      $sub_arr = $GLOBALS[cat_arr];
      foreach ($sub_arr AS $key => $val) {
        $val[id] = $key;
        $links .= returncatoption($val,$category[id],$level+1);
      }
      unset($sub_arr);
    }
  }
  return $links;
}

switch($action) {

case "":

  unset($cat_options);

  if (!empty($cat_arr)) {
    foreach ($cat_arr as $key => $cat) {
      $cat[id] = $key;
      $cat_options .= returncatoption($cat,0,1);
    }
  }

  $navbar = makenavbar("Search");
  include("static/sub_pages/search_form_".$pagesetid.".php");

break;

case "dosearch":

  settype($perpage,"integer");
  settype($pagenum,"integer");
  settype($search_catid,"integer");

  if ($perpage < 1) {
    $perpage = $searchperpage;
  }

  if ($pagenum < 1) {
    $pagenum = 1;
  }

  $offset = ($pagenum - 1) * $perpage;

  if (($direction != "") & ($direction != "desc")) {
    $direction = "";
  }

  $searchtext = urlencode($searchfor);

  if ($search_titles | $search_posts) {

    switch($order) {
      case "title":
        $ordersql = "news_news.title $direction,news_news.time DESC";
      break;
      case "category":
        $ordersql = "news_category.name $direction,news_news.time DESC";
      break;
      case "commentcount":
        $ordersql = "news_news.commentcount $direction,news_news.time DESC";
      break;
      default:
        $ordersql = "news_news.time $direction";
    }

    $sql_arr[] = "(news_news.display <> 0)";
    $sql_arr[] = "(news_category.display <> 0)";

    foreach ($cat_arr AS $key => $val) {
      if (!$val[display] | $catexclude[$val[parentid]] | $catexclude[$val[topid]] | !isuserallowed($val[allowview])) {
        $catexclude[$key] = 1;
      }
    }

    if ($catexclude) {
      foreach ($catexclude AS $key => $val) {
        $cat_ids .= iif($cat_ids,",$key",$key);
      }
      $sql_arr[] = "(news_news.catid NOT IN ($cat_ids))";
    }

    if ($search_titles) {
      $newsearchfor = urldecode($searchfor);
      $newsearchfor = str_replace("AND","%') AND (news_news.title LIKE '%",$newsearchfor);
      $newsearchfor = str_replace("OR","%') OR (news_news.title LIKE '%",$newsearchfor);
      $newsearchfor = str_replace("NOT","%') AND (news_news.title NOT LIKE '%",$newsearchfor);
      $sql_arr[get] = "((news_news.title LIKE '%$newsearchfor%')";
    }

    if ($search_posts) {
      $mainnewssearch = urldecode($searchfor);
      $mainnewssearch = str_replace("AND","%') AND (news_news.mainnews LIKE '%",$mainnewssearch);
      $mainnewssearch = str_replace("OR","%') OR (news_news.mainnews LIKE '%",$mainnewssearch);
      $mainnewssearch = str_replace("NOT","%') AND (news_news.mainnews NOT LIKE '%",$mainnewssearch);
      $extendednewssearch = urldecode($searchfor);
      $extendednewssearch = str_replace("AND","%') AND (news_news.extendednews LIKE '%",$extendednewssearch);
      $extendednewssearch = str_replace("OR","%') OR (news_news.extendednews LIKE '%",$extendednewssearch);
      $extendednewssearch = str_replace("NOT","%') AND (news_news.extendednews NOT LIKE '%",$extendednewssearch);

      $sql_arr[get]  .= iif($search_titles," OR ","(")."(news_news.mainnews LIKE '%$mainnewssearch%') OR (news_news.extendednews LIKE '%$extendednewssearch%')) ";
    } else {
      $sql_arr[get] .= ")";
    }

    if ($poster) {
      $sql_arr[] = "(".$foruminfo[user_table].".".$foruminfo[username_field]." ".iif($postertype == "exact","= '$poster'","LIKE '%$poster%'").")";
    }

    if (isset($cat_arr[$search_catid]) & ($search_catid > 0) & isuserallowed($cat_arr[$search_catid][allowview]) & $cat_arr[$search_catid][display]) {
      $sql_arr[] = "(news_news.catid = '$search_catid')";
    }

    $wheresql = join(" AND ",$sql_arr);

    $numrecords = query_first("SELECT DISTINCT
      COUNT(news_news.id) AS count
      FROM news_news
      LEFT JOIN news_staff ON news_news.staffid = news_staff.id
      LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
      LEFT JOIN news_category ON news_category.id = news_news.catid
      WHERE $wheresql");

    $numrecords = $numrecords[count];

    $pagenav = pagenav($perpage,$pagenum,"search.php?action=dosearch&searchfor=$searchtext&poster=$poster&postertype=$postertype&catid=$catid&order=$order&direction=$direction&search_titles=$search_titles&search_posts=$search_posts",$numrecords);

    $getdata = query("SELECT DISTINCT
      news_news.id,
      news_news.title,
      news_news.commentcount,
      news_news.time,
      news_news.catid,
      ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
      ".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid,
      news_category.name AS cat_name
      FROM news_news
      LEFT JOIN news_staff ON news_staff.id = news_news.staffid
      LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
      LEFT JOIN news_category ON news_category.id = news_news.catid
      WHERE $wheresql
      ORDER BY $ordersql
      LIMIT $offset,$perpage");

    while ($news = fetch_array($getdata)) {

      $postername = htmlspecialchars($news[username]);
      $posterid = $news[userid];

      eval("\$news[poster] = \"".returnpagebit("misc_username_profile")."\";");

      $news[time] = date($newsdate,$news[time]-$timeoffset);

      if (($searchfor != "") & ($highlightsearch == 1)) {
        $news[title] = eregi_replace($searchfor,"<font style=\"color:red\">".strtoupper($searchfor)."</font>",$news[title]);
      }

      eval("\$news_data .= \"".returnpagebit("search_results_news_record")."\";");

    }
    eval("\$posts = \"".returnpagebit("search_results_news")."\";");
  }

  if ($search_articles) {

    switch($order) {
      case "title":
        $ordersql = "news_article.title $direction,news_article.date DESC";
      break;
      case "commentcount":
        $ordersql = "news_article.views $direction,news_article.date DESC";
      break;
      case "category":
        $ordersql = "news_articlecat.title $direction,news_article.date DESC";
      break;
      default:
        $ordersql = "news_article.date $direction";
    }

    unset($sql_arr);

    $sql_arr[] = "(news_article.display <> 0)";
    $sql_arr[] = "(news_articlecat.display <> 0)";

    for ($i=1;$i<=10;$i++) {
      $newsearchfor = urldecode($searchfor);
      $newsearchfor = str_replace("AND","%') AND (news_article.page$i LIKE '%",$newsearchfor);
      $newsearchfor = str_replace("OR","%') OR (news_article.page$i LIKE '%",$newsearchfor);
      $newsearchfor = str_replace("NOT","%') AND (news_article.page$i NOT LIKE '%",$newsearchfor);

      $search_text[] = "(news_article.page$i LIKE '%$newsearchfor%')";
    }

    $newsearchfor = urldecode($searchfor);
    $newsearchfor = str_replace("AND","%') AND (news_article.title LIKE '%",$newsearchfor);
    $newsearchfor = str_replace("OR","%') OR (news_article.title LIKE '%",$newsearchfor);
    $newsearchfor = str_replace("NOT","%') AND (news_article.title NOT LIKE '%",$newsearchfor);

    $search_text[] = "(news_article.title LIKE '%$newsearchfor%')";

    $sql_arr[] = "(".join(" OR ",$search_text).")";

    if ($poster) {
      $sql_arr[] = "((".$foruminfo[user_table].".".$foruminfo[username_field]." ".iif($postertype == "exact","= '$poster'","LIKE '%$poster%'").") OR (news_article.authorname ".iif($postertype == "exact","= '$poster'","LIKE '%$poster%'")."))";
    }

    $wheresql = join(" AND ",$sql_arr);

    $numrecords = query_first("SELECT DISTINCT
      count(news_article.id) AS count
      FROM news_article
      LEFT JOIN news_staff ON news_article.staffid = news_staff.id
      LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
      LEFT JOIN news_articlecat ON news_articlecat.id = news_article.catid
      WHERE $wheresql");

    $numrecords = $numrecords[count];

    $pagenav = pagenav($perpage,$pagenum,"search.php?action=dosearch&searchfor=$searchtext&poster=$poster&postertype=$postertype&order=$order&direction=$direction&search_articles=$search_articles",$numrecords);

    $getdata = query("SELECT DISTINCT
      news_article.id,
      news_article.title,
      news_article.date,
      news_article.authorname,
      news_article.numpages,
      news_article.views,
      news_article.rating,
      ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
      ".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid
      FROM news_article
      LEFT JOIN news_staff ON news_article.staffid = news_staff.id
      LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
      LEFT JOIN news_articlecat ON news_articlecat.id = news_article.catid
      WHERE $wheresql
      ORDER BY $ordersql
      LIMIT $offset,$perpage");

    while ($article = fetch_array($getdata)) {

      if ($article[authorname]) {
        $postername = htmlspecialchars($article[authorname]);
        eval("\$article[author] = \"".returnpagebit("misc_username_noemail")."\";");
      } else {
        $postername = htmlspecialchars($article[username]);
        $posterid = $article[userid];
        eval("\$article[author] = \"".returnpagebit("misc_username_profile")."\";");
      }

      $article[date] = date($articlesdate,$article[date]-$timeoffset);

      if (($searchfor != "") & ($highlightsearch == 1)) {
        $article[title] = eregi_replace($searchfor,"<font style=\"color:red\">".strtoupper($searchfor)."</font>",$article[title]);
      }

      eval("\$article_data .= \"".returnpagebit("search_results_article_record")."\";");

    }
    eval("\$articles = \"".returnpagebit("search_results_article")."\";");
  }

  $navbar = makenavbar("Results","Search","search.php");
  include("static/sub_pages/search_results_".$pagesetid.".php");

break;

default:
  standarderror("invalid_link");
}

/*======================================================================*\
|| ####################################################################
|| # File: search.php
|| ####################################################################
\*======================================================================*/

?>

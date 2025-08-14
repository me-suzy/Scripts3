<?php


require("global.php");

switch($action) {

case "";

  $navbar = makenavbar("Articles");
  include("static/articles_".$pagesetid.".php");

break;

case "cat";

  settype($id,"integer");

  $getdata = query("SELECT
    news_articlecat.id AS catid,
    news_articlecat.title AS cattitle,
    news_articlecat.description,
    news_articlecat.parentid,
    news_articlecat.children,
    news_article.id,
    news_article.title,
    news_article.date,
    news_article.authorname,
    news_article.views,
    news_article.rating,
    news_article.numpages,
    news_article.display AS articledisplay,
    ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
    ".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid
    FROM news_articlecat
    LEFT JOIN news_article ON news_article.catid = news_articlecat.id
    LEFT JOIN news_staff ON news_staff.id = news_article.staffid
    LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
    WHERE (news_articlecat.display <> 0)
    AND (news_articlecat.id = $id)
    ORDER BY news_articlecat.displayorder,news_article.date DESC");

  if (!countrows($getdata)) {
    standarderror("invalid_id");
  }

  unset($article_list);

  while ($article = fetch_array($getdata)) {

    $cat_title = $article[cattitle];
    $cat_description = qhtmlparse($article[description],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);
    $parent_link = iif($article[parentid] == 0,"articles.php","articles.php?action=cat&id=$article[parentid]");

    if (!$headers[$id]) {

      $headers[$id] = 1;

      if ($article[children]) {

        $getsub = query("SELECT id,title,numarticles,description FROM news_articlecat WHERE (display <> 0) AND (parentid = $id) ORDER BY displayorder");

        while ($subcat = fetch_array($getsub)) {
          $subcat[description] = qhtmlparse($subcat[description],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml);

          eval("\$sub_cats .= \"".returnpagebit("articles_cats_sub_cat")."\";");
        }
      }
    }

    if (($article[title] != "") & ($article[articledisplay] != 0)) {
      $article[date] = date($articlesdate,$article[date]-$timeoffset);
      $article[rating] = iif($article[rating],$article[rating],"N/A");

      if ($article[authorname]) {
        $postername = htmlspecialchars($article[authorname]);
        eval("\$article[author] = \"".returnpagebit("misc_username_noemail")."\";");
      } else {
        $postername = htmlspecialchars($article[username]);
        $posterid = $article[userid];

        eval("\$article[author] = \"".returnpagebit("misc_username_profile")."\";");
      }

      eval("\$article_list .= \"".returnpagebit("articles_cats_record")."\";");
    }


  }

  $navbar = makenavbar($cat_title,"Articles","articles.php");

  include("static/sub_pages/articles_cats_".$pagesetid.".php");

break;

case "show":

  settype($id,"integer");
  settype($pagenum,"integer");
  if (($pagenum < 1) | ($pagenum > 10)) {
    $pagenum = 1;
  }

  if ($article = query_first("
    SELECT
    news_article.date,
    news_article.views,
    news_article.rating,
    news_article.authorname,
    news_article.title,
    news_article.numpages,
    news_article.page$pagenum,
    news_article.parsenewline,
    ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,
    ".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid
    FROM news_article
    LEFT JOIN news_staff ON news_article.staffid = news_staff.id
    LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
    WHERE news_article.id = $id")) {

    if (($loggedin | (!$loggedin & $articleanonvote)) & ($articlerateall | ($pagenum == $article[numpages]))) {
      if ((isset($articlevote[$id]) == 1) | (($loggedin == 1) & ($uservoteinfo = query_first("SELECT userid FROM news_articlevote WHERE (userid = $userid) AND (articleid = $id)")))) {
        $article[rating_options] = "";
      } else {
        eval("\$article[rating_options] = \"".returnpagebit("articles_display_rating")."\";");
      }
    }

    query("UPDATE news_article SET views = views+'1' WHERE id = $id");

    if ($article[authorname]) {
      $postername = htmlspecialchars($article[authorname]);
      eval("\$article[author] = \"".returnpagebit("misc_username_noemail")."\";");
    } else {
      $postername = htmlspecialchars($article[username]);
      $posterid = $article[userid];

      eval("\$article[author] = \"".returnpagebit("misc_username_profile")."\";");
    }

    $article[date] = date($articlesdate,$article[date]-$timeoffset);
    $article[content] = qhtmlparse($article[page.$pagenum],$staff_allowhtml,$staff_allowimg,$staff_allowsmilies,$staff_allowqhtml,$article[parsenewline]);
    $article[rating_value] = iif($article[rating],$article[rating],"No Votes So Far");

    $pagenav = pagenav(1,$pagenum,"articles.php?action=show&amp;id=$id",$article[numpages]);
    $navbar = makenavbar($article[title],"Articles","articles.php");

    include("static/sub_pages/articles_display_".$pagesetid.".php");

  } else {
    standarderror("invalid_id");
  }

break;

case "rate":

  settype($option,"integer");

  if (($option > 5) | ($option < 1)) {
    standarderror("invalid_rating");
  }

  settype($id,"integer");

  if ($id == 0) {
    standarderror("invalid_id");
  }

  if (!$loggedin & !$articleanonvote) {
    standarderror("no_anon_voting");
  }

  if ($rating = query_first("SELECT rating FROM news_article WHERE id = $id")) {

    if ((isset($articlevote[$id]) == 1) | (($loggedin == 1) & ($uservoteinfo = query_first("SELECT userid FROM news_articlevote WHERE (userid = $userid) AND (articleid = $id)")))) {
      standarderror("already_voted");
    }

    $newrating = iif($rating[rating],round(($rating[rating]+$option)/2,2),$option);

    query("UPDATE news_article SET rating = '$newrating' WHERE id = $id");

    query("INSERT INTO news_articlevote VALUES (NULL,'$id','$userid','".time()."','$option')");

    if (!$loggedin) {
      updatecookie("articlevote[$id]",$option);
    }
    standardredirect("voted","articles.php?action=show&id=$id");

  } else {
    standarderror("invalid_id");
  }

break;

default:
  standarderror("invalid_link");
}

/*======================================================================*\
|| ####################################################################
|| # File: articles.php
|| ####################################################################
\*======================================================================*/

?>
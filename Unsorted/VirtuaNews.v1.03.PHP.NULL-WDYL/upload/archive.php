<?php


include("global.php");

$navbar = makenavbar("Archive");

$firstpost = query_first("SELECT MAX(time) AS time FROM news_news WHERE (display = 1) AND (program = 0)");

settype($startdate,"integer");

if (($startdate <= 8640000) | ($startdate > 2145916800)) {
  $date = gmdate("j F Y",$firstpost[time]);
  $startdate = strtotime("00:00 $date");
}

$startdate = $startdate-($archiveperpage*86400)+86400;
$enddate = $startdate+($archiveperpage*86400);

$getdata = query("SELECT
  news_news.id,
  news_news.title,
  news_news.commentcount,
  news_news.time,
  news_news.catid,
  ".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid,
  ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username
  FROM news_news
  LEFT JOIN news_staff ON news_news.staffid = news_staff.id
  LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]."
  WHERE (news_news.display <> 0)
  AND (news_news.program = 0)
  AND (news_news.time < " . ($enddate - $timeoffset) . ")
  AND (news_news.time > " . ($startdate - $timeoffset) . ")
  ORDER BY time DESC");

$begindate = $enddate;

while ($news = fetch_array($getdata)) {

  for ($i=1;$i<=$archiveperpage;$i++) {
    if (($news[time] - $timeoffset) < $enddate) {
      $enddate = $enddate-86400;
    }
  }

  $news[time] = date($archivedate,$news[time]-$timeoffset);
  $news[cat_text] = $cat_arr[$news[catid]][name];

  if ($news[commentcount] > 1) {
    $commenttext2 = $commenttext2_p;
  } else {
    $commenttext2 = $commenttext2_s;
  }

  $postername = htmlspecialchars($news[username]);
  $posterid = $news[userid];

  eval("\$news[poster] = \"".returnpagebit("misc_username_profile")."\";");

  eval("\$post_arr[$enddate] .= \"".returnpagebit("archive_post")."\";");

}

$begindate = $begindate - 86400;

if ($enddate != ($begindate-(86400*$archiveperpage))) {
  $enddate = $begindate-(86400*($archiveperpage-1));
}

while ($begindate >= $enddate) {
  if ($post_arr[$begindate]) {
    $date = date($archiveheaddate,$begindate);
    eval("\$post_list .= \"".returnpagebit("archive_header")."\";");

    $post_list .= $post_arr[$begindate];
  }

  $begindate = $begindate - 86400;
}

$nextstartdate = $startdate+($archiveperpage*2*86400)-86400;

if ($nextstartdate < $firstpost[time]) {
  eval("\$nextpagelink = \"".returnpagebit("archive_next_days_link")."\";");
}

$previousstartdate = $begindate;
eval("\$pagenav = \"".returnpagebit("archive_previous_days_link")."\";");

include("static/sub_pages/archive_".$pagesetid.".php");

/*======================================================================*\
|| ####################################################################
|| # File: archive.php
|| ####################################################################
\*======================================================================*/

?>
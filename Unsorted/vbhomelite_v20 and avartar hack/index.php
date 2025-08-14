<?php
error_reporting(7);

function getrow() {
  global $bgcounter;
  if ($bgcounter++%2==0) {
    return '{firstaltcolor}';
  } else {
    return '{secondaltcolor}';
  }
}

$templatesused = 'home,home_headinclude,home_header,home_footer,home_threadbits,home_newsbits,home_avatar';

// ###############################################################################
// ## VARIABLE SETTINGS ##########################################################

// SET YOUR ROOT PATH (this is not your site URL)
chdir("/apache/htdocs/forum");

// THIS IS THE GLOBAL ENVIRONMENT FILE, DON'T TOUCH IT
require("./global.php");

// THE NEWS FORUM
$newsforum="2";

// THE NUMBER OF NEWS ARTICLES LISTED
$newsposts="15";

// THE NUMBER OF LATEST THREADS
$maxthreads="15";

// THE TITLE LEGHT FOR YOUR LATEST THREADS (will display xx characters)
$maxchars="20";

// ## END VARIABLE SETTINGS ######################################################
// ###############################################################################

$homeheader = '';
$homefooter = '';

// parse css, header & footer *****
eval("\$homeheadinclude = \"".gettemplate('home_headinclude')."\";");
eval("\$homeheader .= \"".gettemplate('home_header')."\";");
eval("\$homefooter .= \"".gettemplate('home_footer')."\";");

// ###############################################################################
// ## VBULLETIN CODE #############################################################

// ADD HERE YOUR VBULLETIN CODE

// ## END VBULLETIN CODE #########################################################
// ###############################################################################

// ######################################################################
// ## LATEST THREADS ####################################################

$doperms=$DB_site->query("SELECT canview,forumid FROM forumpermission WHERE usergroupid='$bbuserinfo[usergroupid]'");
while ($doperm=$DB_site->fetch_array($doperms)) {
  $perms["$doperm[forumid]"] = $doperm;
}
$DB_site->free_result($doperms);
unset($doperm);

$forums=$DB_site->query("SELECT forumid FROM forum WHERE forumid<>$newsforum");
while ($forum=$DB_site->fetch_array($forums)) {
  if($perms["$forum[forumid]"]["canview"]==1 || !isset($perms["$forum[forumid]"]["canview"])) {
    $forumperms[]=$forum["forumid"];
  }
}
$DB_site->free_result($forums);
unset($forum);

if(!empty($forumperms)) {
  $forumperms='AND forumid='.implode(' OR forumid=',$forumperms);
}
$getthreads=$DB_site->query("SELECT *
                             FROM thread
                             WHERE open=1
                               AND open<>10
                             $forumperms
                             ORDER BY lastpost DESC
                             LIMIT $maxthreads");
while ($lastthread=$DB_site->fetch_array($getthreads)) {
  $getdots='';
  if (strlen($lastthread[title])>$maxchars) {
    $getdots='...';
  }
  $getthreadid=$lastthread[threadid];
  $gettitle=substr($lastthread[title],0,$maxchars);
  $geticonid=$lastthread[iconid];
  if ($new[iconid]==0) {
    $geticon='<img src="{imagesfolder}/icons/icon1.gif" border="0" align="absmiddle" alt="">';
  } else {
    $geticon='<img src="{imagesfolder}/icons/icon'.$geticonid.'.gif" border="0" align="absmiddle" alt="">';
  }
  $getreplys=$lastthread[replycount];
  $getusername=$lastthread[postusername];
  $getuserid=$lastthread[postuserid];
  $gettime=vbdate($timeformat,$lastthread[dateline]);
  $getdate=vbdate($dateformat,$lastthread[dateline]);
  $getbgrow=getrow();

  eval("\$threadbits .= \"".gettemplate('home_threadbits')."\";");
}
unset($lastthread);

// ## END LATEST THREADS ################################################
// ######################################################################
// ## NEWS ARTICLES #####################################################

$getnewsids=$DB_site->query("SELECT thread.threadid
                             FROM thread
                             WHERE thread.forumid=$newsforum
                             AND thread.visible=1
                             ORDER BY threadid DESC
                             LIMIT $newsposts");
$newsids='thread.threadid IN (0';
while ($article=$DB_site->fetch_array($getnewsids)) {
  $newsids.=",".$article['threadid'];
}
$newsids.=')';
$getnews=$DB_site->query("SELECT thread.title,thread.dateline,forumid,postusername,postuserid,post2.pagetext as pagetext
                          FROM thread
                          LEFT JOIN post AS post2 ON (thread.articleid=post2.postid)
                          WHERE $newsids
                          ORDER BY dateline DESC");
while ($news=$DB_site->fetch_array($getnews)) {
  $newstitle=$news[title];
  $newstime=vbdate($timeformat,$news[dateline]);
  $newsdate=vbdate($dateformat,$news[dateline]);
  $newsusername=$news[postusername];
  $newsuserid=$news[postuserid];
  $newstext=bbcodeparse($news[pagetext],$newsforum,$allowsmilies);

  // if you use html code, uncomment the line below
  // $newstext=str_replace("<br />","",$newstext);

  $newsavatarurl=getavatarurl($newsuserid);
  if ($newsavatarurl=='') {
    $newsavatarurl='{imagesfolder}/clear.gif';
  }

  eval("\$newsavatar = \"".gettemplate('home_avatar')."\";");
  eval("\$newsbits .= \"".gettemplate('home_newsbits')."\";");
}
unset($article);
unset($news);

// ## END NEWS ARTICLES #################################################
// ######################################################################

eval("dooutput(\"".gettemplate('home')."\");");

?>
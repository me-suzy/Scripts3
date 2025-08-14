<?php
error_reporting(7);
$vbhlversion = '3.6';

$templatesused = 'home_printarticlebit,home_printarticle';

require('./global.php');

$threadid = verifyid('thread', $threadid);
$threadinfo = getthreadinfo($threadid);

if (!$threadinfo[visible])
{
  $idname = 'thread';
  eval('standarderror("' . gettemplate('error_invalidid') . '");');
  exit;
}

$getperms = getpermissions($thread[forumid]);
if (!$getperms[canview])
{
  show_nopermission();
}

$foruminfo = getforuminfo($threadinfo[forumid]);
updateuserforum($threadinfo['forumid']);

if ($article=$DB_site->query_first("
  SELECT post.*,post.username AS postusername,user.username
  FROM post
  LEFT JOIN user ON (user.userid = post.userid)
  WHERE post.threadid='$threadid' AND post.visible=1
  ORDER BY dateline ASC
  LIMIT 1
"))
{
  $article[postdate] = vbdate($dateformat, $article[dateline]);
  $article[posttime] = vbdate($timeformat, $article[dateline]);
  $article[icon] = '';
  if ($articleicon) {
    if (!$article[iconid])
    {
      $article[icon] = '<img border="0" src="{imagesfolder}/icons/icon1.gif" width="15" height="15" align="absmiddle">';
    }
    else
    {
      $article[icon] = '<img border="0" src="{imagesfolder}/icons/icon' . $article[iconid] . '.gif" width="15" height="15" align="absmiddle">';
    }
  }
  $article[message] = bbcodeparse($article[pagetext], $foruminfo[forumid], $allowsmilies=$articlesmilies);

  eval('$articlebits = "' . gettemplate('home_printarticlebit') . '";');
}

eval('dooutput("' . gettemplate('home_printarticle') . '");');

?>
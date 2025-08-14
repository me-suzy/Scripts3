<?php
error_reporting(7);
$vbhlversion = '3.6';

$templatesused = 'home,home_articlebits,home_footer,home_header,home_headinclude,home_thread,home_threadbits,home_welcomeguest,';
$templatesused.= 'home_welcomeuser,home_pollresult,home_polloption_multiple,home_polloption,home_pollcomment,home_articlenocomment,';
$templatesused.= 'home_articlecomment,home_pollresults,home_pollresults_closed,home_pollresults_voted,home_polloptions,home_pollreview,';
$templatesused.= 'home_articlelink,home_pmloggedin,home_userloggedin,home_userloggedout,home_search,home_link,home_advertisement,home_avatar';

// enter your full server path here /////////////////////////////

chdir('/wwwroot');

/////////////////////////////////////////////////////////////////

require('./global.php');

$homeheader = '';
$homefooter = '';

// parse css, header and footer
eval('$homeheadinclude = "' . gettemplate('home_headinclude') . '";');
eval('$homeheader .= "' . gettemplate('home_header') . '";');
eval('$homefooter .= "' . gettemplate('home_footer') . '";');

$permissions = getpermissions();
if (!$permissions['canview'])
{
  show_nopermission();
}

// threads and news started since user's last visit
$counts=$DB_site->query_first("
  SELECT COUNT(*) AS threads, SUM(IF(forumid NOT IN ($articleforum), 1, 0)) AS threads, SUM(IF(forumid IN ($articleforum), 1, 0)) AS headlines
  FROM thread
  WHERE dateline>$bbuserinfo[lastvisit]
");

$count['threads'] = number_format($counts['threads']);
$count['headlines'] = number_format($counts['headlines']);
$pluralthread = '';
$pluralarticle = '';
if ($count['threads']!=1)
{
  $pluralthread = 's';
}
if ($count['headlines']!=1)
{
  $pluralarticle = 's';
}

// if user is known, then welcome
if ($bbuserinfo['userid']!=0)
{
  $username=$bbuserinfo['username'];
  $pminfo = '';
  if ($activepm and $enablepms and $bbuserinfo['receivepm'] and $permissions['canusepm'])
  {
    $ignoreusers = '';
    if (trim($bbuserinfo['ignorelist'])!='')
    {
      $ignoreusers = 'AND fromuserid<>' . implode(' AND fromuserid<>', explode(' ', trim($bbuserinfo['ignorelist'])));
    }

    $newpm=$DB_site->query_first("
      SELECT COUNT(*) AS messages FROM privatemessage WHERE userid=$bbuserinfo[userid] AND dateline>$bbuserinfo[lastvisit] AND folderid=0 $ignoreusers
    ");
    $pluralpm = '';
    if ($newpm['messages']!=1)
    {
      $pluralpm = 's';
    }
    if (!$newpm['messages'])
    {
      $newpm['messages'] = 'no';
    }
    eval('$pminfo = "' . gettemplate('home_pmloggedin') . '";');
  }
  eval('$welcometext = "' . gettemplate('home_welcomeuser') . '";');
  eval('$userloggedinout = "' . gettemplate('home_userloggedin') . '";');
}
else
{
  eval('$welcometext = "' . gettemplate('home_welcomeguest') . '";');
  eval('$userloggedinout = "' . gettemplate('home_userloggedout') . '";');
}

$search = '';
$links = '';
$advertisement = '';
if ($activesearch)
{
  eval('$search = "' . gettemplate('home_search') . '";');
}
if ($activelinks)
{
  eval('$links = "' . gettemplate('home_link') . '";');
}
if ($activeadvertise)
{
  eval('$advertisement = "' . gettemplate('home_advertisement') . '";');
}

// forum perms
$forumperms=$DB_site->query("
  SELECT forumid,canview,canpostnew FROM forumpermission WHERE usergroupid=$bbuserinfo[usergroupid]
");
while ($forumperm=$DB_site->fetch_array($forumperms))
{
  $ipermcache["$forumperm[forumid]"] = $forumperm;
}
$DB_site->free_result($forumperms);
unset($forumperm);

// get forums info
$forums=$DB_site->query("
  SELECT * FROM forum WHERE displayorder<>0 AND active=1 ORDER BY parentid,displayorder
");
while ($forum=$DB_site->fetch_array($forums))
{
  $iforumcache["$forum[parentid]"]["$forum[displayorder]"]["$forum[forumid]"] = $forum;
  if ($ipermcache["$forum[forumid]"]["canview"] or !isset($ipermcache["$forum[forumid]"]["canview"]))
  {
    $iforumperms[] = $forum["forumid"];
  }
}
$DB_site->free_result($forums);
unset($forum);

if (!empty($iforumperms))
{
  $iforumperms = 'AND forumid NOT IN (' . $articleforum . ') AND forumid IN (' . implode(',', $iforumperms) . ')';
}

// latest poll
$latestpoll = '';
if ($activepoll)
{
  $iuserperms = 'AND thread.postuserid IN (' . $activepollusers . ')';

  if ($poll=$DB_site->query_first("
    SELECT thread.*,poll.*
    FROM thread
    LEFT JOIN poll ON (poll.pollid=thread.pollid)
    WHERE thread.visible=1 AND thread.open<>10 $iuserperms
    ORDER BY thread.pollid DESC LIMIT 1
  ") and $poll[pollid])
  {
    $pollid = $poll[pollid];
    $poll[question] = bbcodeparse($poll[question], $forum[forumid], $allowsmilies=$activepollsmilies);

    $splitoptions = explode('|||', $poll[options]);
    $splitvotes = explode('|||', $poll[votes]);

    $showresults = 0;
    $uservoted = 0;

    if (!$poll[active] or !$poll[open] or ($poll[dateline]+($poll[timeout]*86400)<time() and $poll[timeout]!=0))
    {
      $showresults = 1;
    }
    elseif (get_bbarraycookie('pollvoted', $pollid) or ($bbuserinfo['userid'] and $uservote=$DB_site->query_first("
      SELECT pollvoteid FROM pollvote WHERE userid=$bbuserinfo[userid] AND pollid=$pollid
    ")))
    {
      $uservoted = 1;
    }

    $counter = 0;
    while ($counter++<$poll[numberoptions]) {
      $poll[numbervotes] += $splitvotes[$counter-1];
    }

    $counter = 0;
    $pollbits = '';
    $option = array();

    while ($counter++<$poll[numberoptions])
    {
      $option[question] = bbcodeparse($splitoptions[$counter-1], $forum[forumid], $allowsmilies=$activepollsmilies);
      $option[votes] = $splitvotes[$counter-1];
      $option[number] = $counter;

      $poll[edit] = '';
      if (in_array($bbuserinfo['usergroupid'], array(5, 6)))
      {
        $poll[edit] = ' &nbsp; <a href="' . $bburl . '/poll.php?s=' . $session[sessionhash] . '&action=polledit&pollid=' . $poll[pollid] . '">Edit Poll</a>';
      }

      if ($showresults or $uservoted)
      {
        if ($option[votes]==0)
        {
          $option[percent] = 0;
        }
        else
        {
          $option[percent] = number_format($option[votes] / $poll[numbervotes] * 100, 2);
        }

        $pluralvote = '';
        if ($poll[numbervotes]!=1)
        {
          $pluralvote = 's';
        }

        $pollstatus = '';
        $option[barnumber] = round($option[percent]);
        if ($showresults)
        {
          eval('$pollstatus = "' . gettemplate('home_pollresults_closed') . '";');
        }
        elseif ($uservoted)
        {
          eval('$pollstatus = "' . gettemplate('home_pollresults_voted') . '";');
        }
        eval('$pollbits .= "' . gettemplate('home_pollresult') . '";');

      }
      elseif ($poll['multiple'])
      {
        eval('$pollbits .= "' . gettemplate('home_polloption_multiple') . '";');
      }
      else
      {
        eval('$pollbits .= "' . gettemplate('home_polloption') . '";');
      }
    }

    if ($poll['multiple'])
    {
      $poll['numbervotes'] = $poll['voters'];
    }

    if ($showresults)
    {
      eval('$pollcomments = "' . gettemplate('home_pollreview') . '";');
      eval('$latestpoll = "' . gettemplate('home_pollresults') . '";');
    }
    elseif ($uservoted)
    {
      eval('$pollcomments = "' . gettemplate('home_pollcomment') . '";');
      eval('$latestpoll = "' . gettemplate('home_pollresults') . '";');
    }
    else
    {
      eval('$pollcomments = "' . gettemplate('home_pollcomment') . '";');
      eval('$latestpoll = "' . gettemplate('home_polloptions') . '";');
    }
  }
}

// latest threads
$threadbits = '';
$threadmaxnumber = '';
if ($threadmax!=0)
{
  $threadmaxnumber = 'LIMIT ' . $threadmax;
}
$threads=$DB_site->query("
  SELECT threadid,forumid,title,replycount,postusername,postuserid,dateline,views
  FROM thread
  WHERE visible=1 AND open<>10 $iforumperms
  ORDER BY lastpost DESC $threadmaxnumber
");
while ($thread=$DB_site->fetch_array($threads))
{
  $thread[title] = unhtmlspecialchars($thread[title]);
  if ($threadmaxchars!=0 and strlen($thread[title])>$threadmaxchars)
  {
    $thread[title] = substr($thread[title], 0, $threadmaxchars - 2) . '...';
  }
  $thread[time] = vbdate($timeformat, $thread[dateline]);
  $thread[date] = vbdate($dateformat, $thread[dateline]);

  if ($thread['forumid']!=$articleforum)
  {
    eval('$threadbits .= "' . gettemplate('home_threadbits') . '";');
  }
}
$DB_site->free_result($threads);
unset($thread);

// articles
$articlebits = '';
$articlemaxnumber = '';
if ($articlemax!=0)
{
  $articlemaxnumber = 'LIMIT ' . $articlemax;
}
$articles=$DB_site->query("
  SELECT thread.threadid,thread.title,thread.lastpost,thread.replycount,thread.postusername,thread.postuserid,
  thread.lastposter,thread.dateline,articlepost.pagetext as pagetext
  " . iif($articleicon,   ',thread.iconid', '') . "
  " . iif($articleavatar, ',avatar.avatarid,avatar.avatarpath', '') . "
  FROM thread
  LEFT JOIN post AS articlepost ON (articlepost.postid=thread.articleid)
  " . iif($articleavatar, 'LEFT JOIN user ON (user.userid=thread.postuserid)
  LEFT JOIN avatar ON (avatar.avatarid=user.avatarid)', '') . "
  WHERE thread.forumid IN ($articleforum) AND thread.visible=1 AND thread.open<>10
  ORDER BY thread.dateline DESC $articlemaxnumber
");
while ($article=$DB_site->fetch_array($articles))
{
  $article[icon] = '';
  if ($articleicon) {
    if (!$article['iconid'])
    {
      $article[icon] = '<img border="0" src="{imagesfolder}/icons/icon1.gif" width="15" height="15" align="absmiddle">';
    }
    else
    {
      $article[icon] = '<img border="0" src="{imagesfolder}/icons/icon' . $article[iconid] . '.gif" width="15" height="15" align="absmiddle">';
    }
  }
  if ($articleavatar and $article[avatarid]!=0)
  {
    $avatarurl=$article[avatarpath];
  }
  if ($avatarurl=='' or ($bbuserinfo[userid]>0 and !($articleavatar)))
  {
    $article[avatar] = '';
  }
  else
  {
    eval('$article[avatar] = "' . gettemplate('home_avatar') . '";');
  }

  $article[message] = bbcodeparse($article[pagetext], $articleforum, $articlesmilies);
  if (strlen($article[message])>$articlemaxchars and $articlemaxchars!=0)
  {
    eval('$articlelink = "' . gettemplate('home_articlelink') . '";');
    $article[message] = substr($article[message], 0, $articlemaxchars) . $articlelink;
  }
  if ($articlehtml)
  {
    $article[message] = str_replace('<br />', '', $article[message]);
  }

  $article[date] = vbdate($dateformat, $article[dateline]);
  $article[time] = vbdate($timeformat, $article[dateline]);

  $pluralcomment = '';
  if ($article[replycount]!=1)
  {
    $pluralcomment = 's';
  }
  if (!$article[replycount])
  {
    $article[replycount] = 'no';
  }
  if ($article[replycount]>0)
  {
    eval('$articlecomments = "' . gettemplate('home_articlecomment') . '";');
  }
  else
  {
    eval('$articlecomments = "' . gettemplate('home_articlenocomment') . '";');
  }

  eval('$articlebits .= "' . gettemplate('home_articlebits') . '";');
}
$DB_site->free_result($articles);
unset($article);

eval('dooutput("' . gettemplate('home') . '");');

?>
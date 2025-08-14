<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++


// ######################### Latest Forum Topics #########################
if ($vba_options['portal_threads_maxthreads'])
{

	$inforums = '';

	if ($vba_options['portal_threads_forumids'])
	{
		if (!empty($forumperms))
		{
			$inforums = array();
			$threadsforums = explode(',', $vba_options['portal_threads_forumids']);
			foreach ($threadsforums AS $tforum)
			{
				if (!in_array($tforum, $forumperms))
				{
					$inforums[] = $tforum;
				}
			}
			if (!empty($inforums))
			{
				$inforums = implode(',', $inforums);
			}
		}
		else
		{
			$inforums = $vba_options['portal_threads_forumids'];
		}
		if ($inforums)
		{
			$inforums = 'AND thread.forumid IN (' . $inforums . ')';
		}
	}

	$foruminfo['allowratings'] = $vba_options['portal_threads_showrating'];

	$show['lastpost'] = $vba_options['portal_threads_lastpost'];

	if ($vba_options['portal_threads_showsubscribed'] AND $bbuserinfo['userid'])
	{
		$query['subfields'] = ', NOT ISNULL(subscribethread.subscribethreadid) AS subscribed';
		$query['subjoin'] = 'LEFT JOIN ' . TABLE_PREFIX . 'subscribethread AS subscribethread ON (subscribethread.threadid = thread.threadid AND subscribethread.userid = ' . $bbuserinfo['userid'] . ')';
	}

	if ($vba_options['portal_threads_showpreview'] AND $vboptions['threadpreview'])
	{
		$query['previewfields'] = ', post.pagetext AS preview';
		$query['previewjoin'] = 'LEFT JOIN ' . TABLE_PREFIX . 'post AS post ON (post.postid = thread.firstpostid)';
	}

	if ($vba_options['portal_threads_showforum'])
	{
		$query['forumfields'] = ',thread.forumid, forum.title AS forumtitle';
		$query['forumjoin'] = 'LEFT JOIN ' . TABLE_PREFIX . 'forum AS forum ON (thread.forumid = forum.forumid)';
	}

	if ($vba_options['portal_threads_showicon'])
	{
		$query['iconfields'] = ', thread.iconid AS threadiconid, iconpath AS threadiconpath';
		$query['iconjoin'] = 'LEFT JOIN ' . TABLE_PREFIX . 'icon USING (iconid)';
	}

  $threads = $DB_site->query("
  		SELECT 
  		" . iif($vba_options['portal_threads_showrating'], 'IF(votenum >= ' . $vboptions['showvotes'] . ', votenum, 0) AS votenum, IF(votenum >= ' . $vboptions['showvotes'] . ' AND votenum != 0, votetotal / votenum, 0) AS voteavg,') . "
  		thread.threadid, thread.title, thread.replycount, postusername, postuserid, thread.dateline AS postdateline, IF(views <= thread.replycount, thread.replycount+1, views) AS views, thread.lastposter, thread.lastpost, pollid
  		$query[iconfields]
			$query[forumfields]
			$query[previewfields]
			$query[subfields]
			FROM " . TABLE_PREFIX . "thread as thread
			$query[iconjoin]
			$query[forumjoin]
			$query[previewjoin]
			$deljoin
			$query[subjoin]
			WHERE open <> '10' AND thread.visible = 1 $iforumperms $inforums $notdeleted 
			ORDER BY lastpost DESC 
			LIMIT $vba_options[portal_threads_maxthreads]
	");
  while ($thread = $DB_site->fetch_array($threads))
  {
		if (strlen($thread['title']) > $vba_options['portal_threads_maxchars'] AND $vba_options['portal_threads_maxchars'])
		{
			$thread['title'] = fetch_trimmed_title($thread['title'], $vba_options['portal_threads_maxchars']);
		}

		$thread = process_thread_array($thread, '', $vba_options['portal_threads_showicon']);

		$getbgrow = getrowcolor();

		eval('$threadbits .= "' . fetch_template('adv_portal_latesttopicbits') . '";');
 	}

	eval('$home[$mods[\'modid\']][\'content\'] = "' . fetch_template('adv_portal_latesttopics') . '";');

  $DB_site->free_result($threads);
  unset($thread, $threadbits, $foruminfo, $query, $inforums);
}

?>
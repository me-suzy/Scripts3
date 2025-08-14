<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++


foreach ($modules AS $omods)
{
	if ($omods['identifier'] == 'news' AND in_array($omods['modid'], explode(',', $pages['modules'])))
	{
		$shownews = true;
		$newsid = $omods['modid'];
	}
	if ($omods['identifier'] == 'newsarchive' AND in_array($omods['modid'], explode(',', $pages['modules'])))
	{
		$showarchive = true;
		$archiveid = $omods['modid'];
		$mods['modcol'] = $omods['modcol'];
		$mods[$archiveid]['displayorder'] = $omods['displayorder'];
	}
}

unset($omods);

if ($vba_options['portal_news_forumid'])
{
	if ($vba_options['portal_news_maxposts'])
	{
		if ($vba_options['portal_news_maxposts'] AND $vba_options['portal_news_enablearchive'])
		{
			$limit = $vba_options['portal_news_maxposts'] + $vba_options['portal_news_enablearchive'];
		}
		else
		{
			$limit = $vba_options['portal_news_maxposts'];
		}
		$newslimit = 'LIMIT ' . $limit;
	}

	$foruminfo['allowratings'] = $vba_options['portal_news_showrating'];

	$counter = 0;

	$getnews = $DB_site->query("
			SELECT " . iif($vba_options['portal_news_showrating'], 'IF(votenum >= ' . $vboptions['showvotes'] . ', votenum, 0) AS votenum, IF(votenum >= ' . $vboptions['showvotes'] . ' AND votenum != 0, votetotal / votenum, 0) AS voteavg,') . "
			thread.threadid, thread.title, replycount, postusername, postuserid, thread.dateline AS postdateline, thread.lastposter, thread.lastpost, IF(views<=replycount, replycount+1, views) AS views, forumid, post.postid, pagetext, allowsmilie
			" . iif ($vba_options['portal_news_showsignature'], ', showsignature, usertextfield.signature') . "
  		" . iif ($vba_options['portal_news_showicon'] , ',thread.iconid AS threadiconid, iconpath AS threadiconpath') . " 
  		" . iif ($vba_options['portal_news_showavatar'] , ', avatarpath, NOT ISNULL(avatardata) AS hascustom, customavatar.dateline AS avatardateline, avatarrevision') . " 
  		" . iif ($vba_options['portal_news_showsubscribed'] AND $bbuserinfo['userid'] , ', NOT ISNULL(subscribethread.subscribethreadid) AS subscribed ') . " 
  		" . iif ($vba_options['portal_news_showattachments'], ', attachment.filename, attachment.filesize, attachment.visible, attachmentid, counter, thumbnail, LENGTH(thumbnail) AS thumbnailsize') . "
			FROM " . TABLE_PREFIX . "thread AS thread
			LEFT JOIN " . TABLE_PREFIX . "post AS post ON (post.postid = thread.firstpostid) 
  		" . iif ($vba_options['portal_news_showicon'] , 'LEFT JOIN ' . TABLE_PREFIX . 'icon USING (iconid)') . " 
  		" . iif ($vba_options['portal_news_showattachments'] , 'LEFT JOIN ' . TABLE_PREFIX . 'attachment AS attachment ON (post.postid = attachment.postid)') . " 
			" . iif ($vba_options['portal_news_showavatar'] OR $vba_options['portal_news_showsignature'], 
				'LEFT JOIN ' . TABLE_PREFIX . 'user AS user ON (user.userid = post.userid)
			') . "
			" . iif ($vba_options['portal_news_showsignature'], 'LEFT JOIN ' . TABLE_PREFIX . 'usertextfield AS usertextfield ON (post.userid = usertextfield.userid)') . "
  		" . iif ($vba_options['portal_news_showavatar'] , '
				LEFT JOIN ' . TABLE_PREFIX . 'avatar as avatar ON (avatar.avatarid = user.avatarid)
				LEFT JOIN ' . TABLE_PREFIX . 'customavatar as customavatar ON (customavatar.userid = user.userid)
			') . " 
  		" . iif ($vba_options['portal_news_showsubscribed'] AND $bbuserinfo['userid'] , ' LEFT JOIN ' . TABLE_PREFIX . 'subscribethread AS subscribethread ON (subscribethread.threadid = thread.threadid AND subscribethread.userid = \'' . $bbuserinfo['userid'] . '\')') . " 
			$deljoin
			WHERE forumid IN($vba_options[portal_news_forumid]) AND thread.visible = 1 AND thread.open != 10 $notdeleted
			GROUP BY post.postid
			ORDER BY " . iif($vboptions['stickynewsthreads'], 'sticky DESC,') . " postdateline DESC" . iif($vboptions['shownewsattach'], ', attachmentid') . " 
			$newslimit");
	while ($news = $DB_site->fetch_array($getnews))
	{
		$counter++;

		if ($vba_options['portal_news_archivepreview'])
		{
			$news['preview'] = $news['pagetext'];
		}

		$dateposted = vbdate($vba_options['portal_news_dateformat'], $news['postdateline']);
		$news = process_thread_array($news, '', $vba_options['portal_news_showicon']);

		if ($counter <= $vba_options['portal_news_maxposts'] AND $shownews)
		{
			// Signature
			$show['signature'] = false;
			if ($vba_options['portal_news_showsignature'] AND $news['showsignature'])
			{
				$news['signature'] = parse_bbcode($news['signature'], 'nonforum', $vboptions['allowsmilies']);
				$show['signature'] = true;
			}

			// News Avatar
			$newsavatarurl = '';
			if ($vba_options['portal_news_showavatar']) 
			{
				if ($news['avatarpath']) 
				{
					$newsavatarurl = $vboptions['bburl'] . '/' . $news['avatarpath'];
				}
				else if ($news['hascustom']) 
				{
					if ($vboptions['usefileavatar'])
					{
						$newsavatarurl = $vboptions['bburl'] . '/' . $vboptions['avatarurl'] . '/avatar' . $news['postuserid']. '_' . $news['avatarrevision'] . '.gif';
					}
					else
					{
						$newsavatarurl = $vboptions['bburl'] . '/image.php?' . $session['sessionurl'] . 'u=' . $news['postuserid'] . '&amp;dateline=' . $news['avatardateline'];
					}
				}
			}

			// News Message
	
			$allowsmilie = '';
			if ($vba_options['portal_news_enablesmilies'] AND $news['allowsmilie'])
			{
				$allowsmilie = 1;
			}

			$news['message'] = parse_bbcode2($news['pagetext'], $vba_options['portal_news_enablehtml'], $vba_options['portal_news_enablevbimage'], $allowsmilie, $vba_options['portal_news_enablevbcode']);
	
			if ($vba_options['portal_news_maxchars'] AND strlen($news['message']) > $vba_options['portal_news_maxchars'])
			{
				$news['message'] = fetch_trimmed_title($news['message'], $vba_options['portal_news_maxchars']) . construct_phrase($vbphrase['read_more'], $vboptions['bburl'], $news['threadid'], $session['sessionurl']);
			}
			
			// News Attachments
			$attachment = '';
			if ($news['attachmentid'])
			{
				$newsforumperms = fetch_permissions($news['forumid']);
				if ($news['thumbnail'] AND $vboptions['attachthumbs'] AND ($newsforumperms & CANGETATTACHMENT))
				{
					$attachment = '<a href="' . $vboptions['bburl'] . '/attachment.php?' . $session['sessionurl'] . 'attachmentid=' . $news['attachmentid'] . '" target="_blank"><img border="0" src="' . $vboptions['bburl'] . '/attachment.php?' . $session['sessionurl'] . 'attachmentid=' . $news['attachmentid'] . '&amp;stc=1&amp;thumb=1" /></a>';			
				}
				else
				{
					$attachment['attachmentextension'] = file_extension($news['filename']);
					$attachment['filename'] = $news['filename'];
					$attachment['attachmentid'] = $news['attachmentid'];
					$attachment['filesize'] = vb_number_format($news['filesize'], 1, true);
					$attachment['counter'] = $news['counter'];
					eval('$attachment = "' . fetch_template('postbit_attachment') . '";');
					$attachment = str_replace('"attachment.php', '"' . $vboptions['bburl'] . '/attachment.php', $attachment);
				}	
			}

			eval('$home[$newsid][\'content\'] .= "' . fetch_template('adv_portal_newsbits') . '";');
		}
		else
		{
			eval('$newsarchivebits .= "' . fetch_template('adv_portal_news_archivebits') . '";');
		}
	}				

	if ($showarchive AND $newsarchivebits)
	{
		eval('$home[$archiveid][\'content\'] = "' . fetch_template('adv_portal_news_archive') . '";');
	}

	$DB_site->free_result($getnews);
	unset($news, $foruminfo['allowratings'], $newsarchivebits, $attachment, $counter, $newsforumbits);
}

?>
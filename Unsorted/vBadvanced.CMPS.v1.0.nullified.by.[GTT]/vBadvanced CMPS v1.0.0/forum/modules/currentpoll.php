<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++


// ######################### Current Poll #########################
if ($vba_options['portal_poll_forumid'])
{
	if ($vba_options['portal_poll_forumid'] == 'RAND')
	{
		$query['pollquery'] = $iforumperms . 'ORDER BY RAND()';
	}
	else
	{
		$query['pollquery'] = 'AND forumid IN(' . $vba_options['portal_poll_forumid'] . ') ORDER BY poll.pollid DESC';
	}

	if ($bbuserinfo['userid'])
	{
		$query['pollfields'] = ', pollvoteid';
		$query['polljoin'] = 'LEFT JOIN ' . TABLE_PREFIX . 'pollvote AS pollvote ON (pollvote.pollid = poll.pollid AND pollvote.userid = ' . $bbuserinfo['userid'] . ')';
	}

	$pollinfo = $DB_site->query_first("
			SELECT thread.pollid, open, threadid, replycount, forumid, question, poll.dateline, options, votes, active, numberoptions, timeout, multiple, voters, public $query[pollfields]
			FROM " . TABLE_PREFIX . "thread AS thread
			INNER JOIN " . TABLE_PREFIX . "poll AS poll USING (pollid) 
			$query[polljoin]
			$deljoin
			WHERE open <> 10 AND thread.pollid != 0 AND visible = 1 $notdeleted  $query[pollquery]
			LIMIT 1
	");

	unset($query);

	if ($pollinfo['pollid'])
	{
		$pollinfo['question'] = parse_bbcode($pollinfo['question'], 'nonforum', $vba_options['portal_poll_allowsmilies']);

		$splitoptions = explode('|||', $pollinfo['options']);
		$splitvotes = explode('|||', $pollinfo['votes']);

		foreach ($splitvotes AS $key => $value)
		{
			$pollinfo['nvotes'] += $value;
		}

		$showresults = '';
		$uservoted = '';

		$pollforumperms = fetch_permissions($pollinfo['forumid']);

		if (!$pollinfo['active'] OR !$pollinfo['open'] OR ($pollinfo['dateline'] + ($pollinfo['timeout'] * 86400) < TIMENOW AND $pollinfo['timeout']) OR !($pollforumperms & CANVOTE))
		{
			$showresults = 1;

			if (!($pollforumperms & CANVOTE))
			{
				$pollinfo['message'] = $vbphrase['you_may_not_vote_on_this_poll'];
			}
			else
			{
				$pollinfo['message'] = $vbphrase['this_poll_is_closed'];
			}
		}

		if (fetch_bbarray_cookie('poll_voted', $pollinfo['pollid']) OR $pollinfo['pollvoteid'])
		{
			$uservoted = 1;
			$pollinfo['message'] = $vbphrase['you_have_already_voted_on_this_poll'];
		}

		foreach ($splitvotes AS $key => $value)
		{
			$option['question'] = parse_bbcode($splitoptions[$key], '', $vba_options['portal_poll_allowsmilies']);

			$option['votes'] = $value;
			$option['number'] = $key + 1;

			if ($showresults OR $uservoted)
			{
				if (!$value)
				{
					$option['percent'] = 0;
				}
				else
				{
					$option['percent'] = number_format($value / $pollinfo['nvotes'] * 100, 2);
				}

				$option['graphicnumber'] = $option['number'] % 6 + 1;
				$option['barnumber'] = intval($option['percent'] * 1.4);

				eval('$pollbits .= "' . fetch_template('adv_portal_pollresult') . '";');
			}
			elseif ($pollinfo['multiple'])
			{
				eval('$pollbits .= "' . fetch_template('adv_portal_polloption_multiple') . '";');
			}
			else
			{
				eval('$pollbits .= "' . fetch_template('adv_portal_polloption') . '";');
			}
		}
	}
	eval('$home[$mods[\'modid\']][\'content\'] = "' . fetch_template('adv_portal_poll') . '";');
}

unset($pollbits, $pollinfo, $option, $pollbits);
?>
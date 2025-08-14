<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++


$doneusers = array();
$activeusers = '';

foreach ($modules AS $omods)
{
	if ($omods['identifier'] == 'buddylist' AND in_array($omods['modid'], explode(',', $pages['modules'])))
	{
		$showbuddies = true;
		$buddyid = $omods['modid'];
	}
	if ($omods['identifier'] == 'onlineusers' AND in_array($omods['modid'], explode(',', $pages['modules'])))
	{
		$showonline = true;
		$onlineid = $omods['modid'];
	}
}

unset($omods);

if ($showonline OR ($showbuddies AND trim($bbuserinfo['buddylist'])))
{
	$getonline = $DB_site->query("
			SELECT session.userid, username, usergroupid, (user.options & $_USEROPTIONS[invisible]) AS invisible
			FROM " . TABLE_PREFIX . "session AS session
			LEFT JOIN " . TABLE_PREFIX . "user AS user USING (userid)
			WHERE session.lastactivity > " . (TIMENOW - $vboptions['cookietimeout']) . "
			ORDER BY invisible ASC, username ASC
	");
	
	if ($bbuserinfo['userid'] AND $showonline)
	{
		$bbuser = array(
			$bbuserinfo['userid'] => array(
				'userid' => $bbuserinfo['userid'],
				'username' => $bbuserinfo['username'],
				'invisible' => $bbuserinfo['invisible'],
				'musername' => fetch_musername($bbuserinfo)
			)
		);
	
		$loggedin = $bbuser[$bbuserinfo['userid']];
		if ($bbuserinfo['invisible'])
		{
			$loggedin['invisiblemark'] = '*';
		}
		eval('$activeusers = "' . fetch_template('forumhome_loggedinuser') . '";');
		$doneusers[] = $bbuserinfo['userid'];
		$numberregistered++;
	}					
	if ($DB_site->num_rows($getonline))
	{
		while ($loggedin = $DB_site->fetch_array($getonline))
		{
			if (!$loggedin['userid'])
			{
				$numberguest++;
			}
			else
			{
				if (!in_array($loggedin['userid'], $doneusers))
				{
					$doneusers[] = $loggedin['userid'];
					$numberregistered++;
	
					$loggedin['musername'] = fetch_musername($loggedin);
					$loggedin['invisiblemark'] = '';
					$loggedin['buddymark'] = '';
					
					if ($loggedin['invisible'])
					{
						if (($permissions['genericpermissions'] & CANSEEHIDDEN) OR $loggedin['userid'] == $bbuserinfo['userid'])
						{
							$loggedin['invisiblemark'] = '*';
						}
						else
						{
							$dontshow = 1;
						}
					}
	
					$buddylist = array();
					if (trim($bbuserinfo['buddylist']))
					{
						$buddylist = explode(' ', $bbuserinfo['buddylist']);
					}
	
					if (in_array($loggedin['userid'], $buddylist))
					{
						$loggedin['buddymark'] = '+';
						if ($showbuddies AND !$dontshow)
						{
							$numberbuddy++;
							$getbgrow = getrowcolor();
							eval('$onlineusers .= "' . fetch_template('adv_portal_buddylistbits') . '";');
						}
					}
	
					if ($showonline)
					{
						if (!$dontshow)
						{
							if ($activeusers)
							{
								$com = ', ';
							}
							eval('$activeusers .= "$com ' . fetch_template('forumhome_loggedinuser') . '";');
							$activeusers = str_replace('"member.php', '"' . $vboptions['bburl'] . '/member.php', $activeusers);
						}
					}
				}
			}
		}
	}
}
$DB_site->free_result($getonline);
unset($loggedin);

if ($showbuddies)
{
	if (!$numberbuddy)
	{
		$onlineusers = construct_phrase($vbphrase['no_x_online'], $vbphrase['buddies']);
	}
	eval('$home[$buddyid][\'content\'] = "' . fetch_template('adv_portal_buddylist') . '";');
}

if ($showonline)
{
	if (!$activeusers)
	{
		$activeusers = construct_phrase($vbphrase['no_x_online'], $vbphrase['members']);
	}

	$totalonline = $numberregistered + $numberguest;
	$numberregistered = number_format($numberregistered);
	$numberguest = number_format($numberguest);

	$maxusers = unserialize($datastore['maxloggedin']);
	if ($maxusers['maxonline'] <= $totalonline)
	{
		$maxusers['maxonline'] = $totalonline;
		$maxusers['maxonlinedate'] = TIMENOW;
		$DB_site->query("UPDATE " . TABLE_PREFIX . "datastore SET data='" . addslashes(serialize($maxusers)) . "' WHERE title = 'maxloggedin'");
	}

	$recordusers = $maxusers['maxonline'];
	$recorddate = vbdate($vboptions['dateformat'], $maxusers['maxonlinedate'], 1);
	$recordtime = vbdate($vboptions['timeformat'], $maxusers['maxonlinedate']);

	eval('$home[$onlineid][\'content\'] = "' . fetch_template('adv_portal_onlineusers') . '";');
}

unset($activeusers, $onlineusers, $doneusers);

?>
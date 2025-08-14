<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++


if ($bbuserinfo['userid']) 
{
	if ($vba_options['portal_welcome_avatar'])
	{
		require_once('./includes/functions_user.php');
		$avatarurl = fetch_avatar_url($bbuserinfo['userid']);
		if (!$avatarurl) 
		{
			$avatarurl = $stylevar['imgdir_misc'] . '/noavatar.gif';
		}
		else
		{
			$avatarurl = $vboptions['bburl'] . '/' . $avatarurl;
		}
	}
	$lastvisitdate = vbdate($vba_options['portal_welcome_lastvisit_date'], $bbuserinfo['lastvisit']);
	$lastvisittime = vbdate($vba_options['portal_welcome_lastvisit_time'], $bbuserinfo['lastvisit']);

	if ($vba_options['portal_welcome_newposts'])
	{
		$getnewposts = $DB_site->query_first("SELECT COUNT(*) AS count FROM " . TABLE_PREFIX . "post WHERE dateline >= '$bbuserinfo[lastvisit]'");
		$newposts = number_format($getnewposts['count']);
	}
}

eval('$home[$mods[\'modid\']][\'content\'] .= "' . fetch_template('adv_portal_welcomeblock') . '";');

?>
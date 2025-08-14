<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++


// ######################### Forum Stats #########################
include_once('./includes/functions_forumlist.php');
cache_ordered_forums(1, 0, 0);
if (is_array($forumcache))
{
	foreach ($forumcache AS $forum)
	{
		$nthreads += $forum['threadcount'];
		$nposts += $forum['replycount'];
		$totalthreads = number_format($nthreads);
		$totalposts = number_format($nposts);
	}
}

$topposter = $DB_site->query_first('SELECT username, posts, userid FROM ' . TABLE_PREFIX . 'user ORDER BY posts DESC LIMIT 1');
$userstats = unserialize($datastore['userstats']);
$numbermembers = number_format($userstats['numbermembers']);
$newusername = $userstats['newusername'];
$newuserid = $userstats['newuserid'];

$tdate = vbdate('Y-m-d', TIMENOW);
$birthdaystore = unserialize($datastore['birthdaycache']);
if (!is_array($birthdaystore) OR ($tdate != $birthdaystore['day1'] AND $tdate != $birthdaystore['day2']))
{
	include_once('./includes/functions_databuild.php');
	$birthdaystore = build_birthdays();
}
switch($tdate)
{
	case $birthdaystore['day1']:
		$birthdays = $birthdaystore['users1'];
		break;

	case $birthdaystore['day2'];
		$birthdays = $birthdaystore['users2'];
		break;
}
$birthdays = str_replace(array('member.php', ','), array($vboptions['bburl'] . '/member.php', '<br />'), $birthdays);

eval('$home[$mods[\'modid\']][\'content\'] = "' . fetch_template('adv_portal_stats') . '";');

unset($userstats, $birthdays, $birthdaystore, $topposter);

?>
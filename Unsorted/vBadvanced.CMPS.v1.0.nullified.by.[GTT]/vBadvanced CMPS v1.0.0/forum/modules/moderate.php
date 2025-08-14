<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++


require_once('./includes/functions_calendar.php');

if (can_moderate(0, 'canmoderateattachments'))
{
	$attachments = $DB_site->query_first("SELECT COUNT(*) AS count FROM " . TABLE_PREFIX . "attachment WHERE visible = 0");
	$attachments['count'] = number_format($attachments['count']);
	$show['attachments'] = true;
}

if (can_moderate() AND can_moderate_calendar())
{
	$events = $DB_site->query_first("SELECT COUNT(*) AS count FROM " . TABLE_PREFIX . "event WHERE visible = 0");
	$events['count'] = number_format($events['count']);
	$show['events'] = true;
}

$users = $DB_site->query_first("SELECT COUNT(*) AS users FROM " . TABLE_PREFIX . "user WHERE usergroupid = 4");
$users['count'] = number_format($users['count']);

if (can_moderate(0, 'canmoderateposts'))
{
	$posts = $DB_site->query_first("SELECT COUNT(*) AS count FROM " . TABLE_PREFIX . "moderation WHERE type = 'reply'");
	$posts['count'] = number_format($posts['count']);
	$show['posts'] = true;

	$threads = $DB_site->query_first("SELECT COUNT(*) AS count FROM " . TABLE_PREFIX . "moderation WHERE type='thread'");
	$threads['count'] = number_format($threads['count']);
	$show['threads'] = true;
}
eval('$home[$mods[\'modid\']][\'content\'] .= "' . fetch_template('adv_portal_moderation') . '";');

?>
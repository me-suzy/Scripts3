<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++


// ######################### Calendar #########################
if ($vba_options['portal_calendarid'])
{
	require_once('./includes/functions_calendar.php');

	if ($vba_options['portal_calendarid'] != -1)
	{
		$calendarinfo = verify_id('calendar', $vba_options['portal_calendarid'], 0, 1);
	}
	if ($bbuserinfo['startofweek'] > 7 OR $bbuserinfo['startofweek'] < 1)
	{
		$bbuserinfo['startofweek'] = $calendarinfo['startofweek'];
	}

	$today = getdate(TIMENOW - $vboptions['hourdiff']);
	$today['month'] = $vbphrase[strtolower($today['month'])];
	$year = $today['year'];
	$month = $today['mon'];

	$usertoday = array('firstday' => gmdate('w', gmmktime(0, 0, 0, $month, 1, $year)), 'month' => $month, 'year' => $year,);

	if ($calendarinfo)
	{
		$calendarinfo = array_merge($calendarinfo, convert_bits_to_array($calendarinfo['options'], $_CALENDAROPTIONS));
		$calendarinfo = array_merge($calendarinfo, convert_bits_to_array($calendarinfo['holidays'], $_CALENDARHOLIDAYS));

		$range = array();
	
		$range['frommonth'] = $month;
		$range['fromyear']= $year;
		$range['nextmonth'] = $month;
		$range['nextyear'] = $year;
	
		$eventcache = cache_events($range);
	}
	if ($vba_options['portal_calendarid'] == -1)
	{
		$calendarinfo['showweekends'] = 1;
	}

	$show['dontshowweek'] = true;

	$calendarbits = construct_calendar_output($today, $usertoday, $calendarinfo, 0, '');

	eval('$home[$mods[\'modid\']][\'content\'] .= "' . fetch_template('adv_portal_calendar') . '";');

	unset($calendarinfo, $eventcache, $calendarbits, $usertoday, $range);
}

?>
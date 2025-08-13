<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++

error_reporting(E_ALL & ~E_NOTICE);

// ################## Adv No Permissons Page #######################
if (!function_exists('print_adv_no_permission'))
{
	function print_adv_no_permission()
	{
		global $header, $footer, $headinclude, $vboptions, $vbphrase, $bbuserinfo, $stylevar, $session;
	
		if ($bbuserinfo['userid'])
		{
			eval('$errormessage = "' . fetch_phrase('nopermission_loggedin', PHRASETYPEID_ERROR) . '";');
		}
		else
		{
			$logincode = construct_login_code();
			$scriptpath = htmlspecialchars_uni(SCRIPTPATH);
			$show['permission_error'] = true;
		}
		eval('$content = "' . fetch_template('STANDARD_ERROR') . '";');
	
		$content = str_replace(
			array(
				'"register.php',
				'"login.php'
			),
			array(
				"\"$vboptions[bburl]/register.php",
				"\"$vboptions[bburl]/login"
			),
			$content
		);
	
		eval(print_output($content));
		exit;
	}
}

// Deny Access if no permissions
if (!$allowview)
{
	print_adv_no_permission();
}

// ##################### Row Color Counter ######################
function getrowcolor() 
{
	global $bgcounter;
	if ($bgcounter++%2 == 0) 
	{
		return 'alt2';
	}
	else
	{
		return 'alt1';
  }
}

// ############################ Adv Navbar Function #################################
if (!function_exists('construct_adv_navbar'))
{
	function construct_adv_navbar($navbits = '', $index = 'false')
	{
		global $vboptions, $vba_options, $vbphrase, $stylevar, $bbuserinfo, $show, $pmbox;
	
		if ($navbits)
		{
			$navbits = construct_navbits($navbits);
		}
	
		eval('$navbar = "' . fetch_template('navbar') . '";');
	
		$navbar = str_replace(
		array(
			'"memberlist.php',
			'"usercp.php',
			'"register.php',
			'"faq.php',
			'"calendar.php',
			'"search.php',
			'"login.php',
			'"forumdisplay.php',
			'"profile.php',
			'"private.php',
			'"subscription.php',
			'"member.php',
			'"online.php',
			'"clientscript',
			'\'misc.php',
			$vboptions['bbtitle'],
			'"' . $vboptions['forumhome'] . '.php'
		), 
		array(
			'"' . $vboptions['bburl'] . '/memberlist.php',
			'"' . $vboptions['bburl'] . '/usercp.php',
			'"' . $vboptions['bburl'] . '/register.php',
			'"' . $vboptions['bburl'] . '/faq.php',
			'"' . $vboptions['bburl'] . '/calendar.php',
			'"' . $vboptions['bburl'] . '/search.php',
			'"' . $vboptions['bburl'] . '/login.php',
			'"' . $vboptions['bburl'] . '/forumdisplay.php',
			'"' . $vboptions['bburl'] . '/profile.php',
			'"' . $vboptions['bburl'] . '/private.php',
			'"' . $vboptions['bburl'] . '/subscription.php',
			'"' . $vboptions['bburl'] . '/member.php',
			'"' . $vboptions['bburl'] . '/online.php',
			'"' . $vboptions['bburl'] . '/clientscript',
			'\'' . $vboptions['bburl'] . '/misc.php',
			$vboptions['hometitle'],
			'"' . $vboptions['homeurl']
		), 
		$navbar
		);
		return $navbar;
	}
}

// ######################### Function to Print The Page Out ##########################
function print_portal_output(&$cmpsoutput, $nonindex = false)
{
	global $pages, $stylevar, $headinclude, $header, $footer, $vboptions, $vba_options, $navbar, $cusid, $_REQUEST;

	if ($nonindex)
	{

		$nonindex = str_replace(
			array(
				$header,
				$navbar,
				$footer,
				$headinclude,
				'<head>',
				'</head>',
				'<body>',
				'</body>',
				'<html>',
				'</html>',
			),
			'',
			$nonindex
		);

		$cmpsoutput[$cusid]['content'] = $nonindex;
	}

	if (!empty($cmpsoutput))
	{
		function order_mods($a, $b) 
		{ 
			return ($a['displayorder'] < $b['displayorder']) ? -1 : 1;
		} 

		usort($cmpsoutput, 'order_mods'); 
		
		$show['right_column'] = false;
		$show['left_column'] = false;
		$show['center_column'] = false;
		
		// Sort out the sides.
		foreach ($cmpsoutput AS $key => $blocks)
		{
			switch ($blocks['column'])
			{
				case 1:
					$home['centerblocks'] .= $blocks['content'];
					$show['center_column'] = true;
					break;
				case 0:
					$home['leftblocks'] .= $blocks['content'];
					$show['left_column'] = true;
					break;
				case 2:
					$home['rightblocks'] .= $blocks['content'];
					$show['right_column'] = true;
					break;
			}
		}
	}

	if ($vba_options['portal_shownavbar'] AND empty($navbar))
	{
		if ($pages['name'] != 'home')
		{
			$navbits[''] = $pages['title'];
		}
		$navbar = construct_adv_navbar($navbits);
	}

	eval('print_output("' . fetch_template('adv_portal') . '");');
}

$iconcache = unserialize($datastore['iconcache']);
$attachmentcache = unserialize($datastore['attachmentcache']);

include_once('./includes/functions_bbcodeparse.php');
include_once('./includes/functions_forumdisplay.php');

// Get Advanced Page Options
if ($pages['advanced'])
{
	foreach (unserialize($pages['advanced']) AS $varname => $value)
	{
		$vba_options[$varname] = $value;
	}
}

// ######################### Forum Permissions #########################
$fperms = array();
foreach ($forumcache AS $key => $forum)
{
	$forumperms = fetch_permissions($forum['forumid']);
	
	if (!($forumperms & CANVIEW) OR !($forumperms & CANVIEWOTHERS))
	{			
		$fperms[] = $forum['forumid'];
	}
}

$excludeforums = array();
if ($vba_options['portal_threads_exclude'])
{
	$excludeforums = explode(',', $vba_options['portal_threads_exclude']);
}

$forumperms = array_merge($fperms, $excludeforums);

if (!empty($forumperms))
{
	$iforumperms = 'AND thread.forumid NOT IN(' . implode(',', $forumperms) .')';
}

unset($forum, $fperms, $excludedforums);

// No Deleted Threads
$notdeleted = 'AND deletionlog.primaryid IS NULL';
$deljoin = 'LEFT JOIN ' . TABLE_PREFIX . 'deletionlog AS deletionlog ON (thread.threadid = deletionlog.primaryid AND type = \'thread\')';

// Process Active Modules
if (is_array($modules))
{
	foreach ($modules AS $mods)
	{
		if (in_array($mods['modid'], explode(',', $pages['modules'])))
		{
			$home[$mods['modid']]['displayorder'] = $mods['displayorder'];
			$home[$mods['modid']]['column'] = $mods['modcol'];
			$getbgrow = getrowcolor();
	
			if ($mods['inctype'] == 0)
			{
				require_once('./modules/' . $mods['filename']);
			}
			else
			{
				if ($mods['identifier'] == 'custompage')
				{
					$cusid = $mods['modid'];
					if ($pages['template'])
					{
						eval('$home[$mods[\'modid\']][\'content\'] = "' . fetch_template($pages['template']) . '";');
					}
				}
				else
				{
					eval('$home[$mods[\'modid\']][\'content\'] = "' . fetch_template('adv_portal_' . $mods['filename']) . '";');
				}
			}
		}
	}
}

unset($modules, $forumperms, $mods, $notdeleted, $deljoin);

// Replace $headinclude URL's
$headinclude = str_replace(
	array(
		'"clientscript', 
		'url(images/',
		'name="generator" content="'
	), 
	array(
		'"' . $vboptions['bburl'] . '/clientscript', 
		'url(' . $vboptions['bburl'] . '/images/',
		'name="generator" content=", '
	),
	$headinclude
);

// Replace Footer Stuff
eval('$vbfooter = "' . fetch_template('footer') . '";');
eval('$footer = "' . fetch_template('adv_portal_footer') . '";');

$footer .= str_replace(
	array(
		'"' . $admincpdir . '',
		'"' . $modcpdir . '',
		'"archive',
		'"sendmessage.php',
		'"cron.php'
	),
	array(
		'"' . $vboptions['bburl'] . '/' . $admincpdir . '',
		'"' . $vboptions['bburl'] . '/' . $modcpdir . '',
		'"' . $vboptions['bburl'] . '/archive',
		'"' . $vboptions['bburl'] . '/sendmessage.php',
		'"' . $vboptions['bburl'] . '/cron.php'
	), 
	$vbfooter
) . '<' . '!' . '-- ' . 'P' . 'ow' . chr(101) . 'r' . chr(101) . chr(100) . ' ' . 'b' . trim($xmsquar) . 'y v' . chr(98) . 'a' . chr(100) . 'v' . '' . 'a' . '' . 'nc' . chr(101) . chr(100) . ' -->';

if ($shownewpm)
{
	eval('$pmscript = "' . fetch_template('pm_popup_script') . '";');
	$footer .= str_replace('"private.php', '"' . $vboptions['bburl'] . '/private.php', $pmscript);
}

?>
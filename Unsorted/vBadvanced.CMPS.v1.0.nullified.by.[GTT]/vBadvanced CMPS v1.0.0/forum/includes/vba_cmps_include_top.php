<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++


error_reporting(E_ALL & ~E_NOTICE);

// ##################### Construct Global Templates Function #######################
function construct_global_templates(&$mod)
{
	global $globaltemplates;
	if ($mod['inctype'] == 0)
	{
		if ($mod['templatelist'])
		{
			$adv_templates = explode(',', $mod['templatelist']);
			foreach ($adv_templates AS $a_temp)
			{
				$globaltemplates[] = trim($a_temp);
			}
		}
	}
	else
	{
		$globaltemplates[] = 'adv_portal_' . $mod['filename'];
	}
	return $globaltemplates;
}	

$portalopts = unserialize($datastore['adv_portal_opts']);

if (!empty($vba_options))
{
	$vba_options = array_merge($portalopts, $vba_options);
}
else
{
	$vba_options = $portalopts;
}

unset($portalopts);

$pagevar = $vba_options['portal_pagevar'];

if (defined('VBA_PAGE'))
{
	$getpage = VBA_PAGE;
}
elseif(isset($_REQUEST[$pagevar]))
{
	$getpage = $_REQUEST[$pagevar];
}
else
{
	$getpage = 'home';
}

$pages = $DB_site->query_first("SELECT * FROM " . TABLE_PREFIX . "adv_pages WHERE name = '" . addslashes($getpage) . "'");

if (!$pages['pageid'])
{
	$pages = $DB_site->query_first("SELECT * FROM " . TABLE_PREFIX . "adv_pages WHERE name = 'home'");
}	

if ($pages['styleid'])
{
	$codestyleid = $pages['styleid'];
}

// Get the user's groups
$ugids[] = $bbuserinfo['usergroupid'];

if (isset($bbuserinfo['membergroupids']))
{
	foreach (explode(',', $bbuserinfo['membergroupids']) AS $groupid)
	{
		$ugids[] = $groupid;
	}
}

$allowview = false;

// Check user's groups against page permissions
if ($pages['userperms'])
{
	$pageperms = explode(',', $pages['userperms']);
	foreach ($ugids AS $groups)
	{
		if (in_array($groups, $pageperms))
		{
			$allowview = true;
		}
	}
}
else
{
	$allowview = true;
}

if ($pages['template'])
{
	$globaltemplates[] = $pages['template'];
}

$getmodules = unserialize($datastore['adv_modules']);

if (!empty($getmodules))
{
	foreach ($getmodules AS $mod)
	{
		if (in_array($mod['modid'], explode(',', $pages['modules'])))
		{

			if ($mod['userperms'])
			{
				foreach ($ugids AS $ugroups)
				{
					if (in_array($ugroups, explode(',', $mod['userperms'])))
					{
						construct_global_templates($mod);
						$modules[] = $mod;
						unset($mod);
					}
				}
			}
			else
			{
				$modules[] = $mod;
				construct_global_templates($mod);
			}
		}
	}
}

unset($getmodules);

?>
<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
// php version
if (!defined('VC_PHP_VERSION')) {
	if (!ereg('([0-9]{1,2}).([0-9]{1,2}).([0-9]{1,2})', phpversion(), $match))
	{
		$result = ereg('([0-9]{1,2}).([0-9]{1,2})', phpversion(), $match);
	}
	if (isset($match) && !empty($match[1]))
	{
        	if (!isset($match[2]))
		{
			$match[2] = 0;
		}
		if (!isset($match[3]))
		{
			$match[3] = 0;
		}
		define('VC_PHP_VERSION', (int)sprintf('%d%02d%02d', $match[1], $match[2], $match[3]));
		unset($match);
	}else{
		define('VC_PHP_VERSION', 0);
	}
}

// Whether the os php is running on is windows or not
if (!defined('VC_IS_WINDOWS'))
{
	if (defined('PHP_OS') && eregi('win', PHP_OS))
	{
		define('VC_IS_WINDOWS', 1);
	}else{
		define('VC_IS_WINDOWS', 0);
	}
}

// MySQL Version
if (!defined('VC_MYSQL_VERSION') && isset($userlink))
{
	if (!empty($server))
	{
		$result = mysql_query('SELECT VERSION() AS version');
		if ($result != FALSE && @mysql_num_rows($result) > 0)
		{
			$row   = mysql_fetch_array($result);
			$match = explode('.', $row['version']);
		}else{
			$result = @mysql_query('SHOW VARIABLES LIKE \'version\'');
			if ($result != FALSE && @mysql_num_rows($result) > 0)
			{
				$row   = mysql_fetch_row($result);
				$match = explode('.', $row[1]);
			}
		}
	} // end server id is defined case
	if (!isset($match) || !isset($match[0]))
	{
		$match[0] = 3;
	}
	if (!isset($match[1]))
	{
		$match[1] = 21;
	}
	if (!isset($match[2]))
	{
		$match[2] = 0;
	}
	
	define('VC_MYSQL_VERSION', (int)sprintf('%d%02d%02d', $match[0], $match[1], intval($match[2])));
	unset($match);
}


// Determines platform (OS), browser and version of the user
if (!defined('VC_USR_OS')) {

	if (!empty($_SERVER['HTTP_USER_AGENT']))
	{
		$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
	}elseif(!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){
		$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
	}
	
	// 1. Platform
	if (strstr($HTTP_USER_AGENT, 'Win'))
	{
		define('VC_USR_OS', 'Win');
	}elseif(strstr($HTTP_USER_AGENT, 'Mac')){
		define('VC_USR_OS', 'Mac');
	}elseif(strstr($HTTP_USER_AGENT, 'Linux')){
        	define('VC_USR_OS', 'Linux');
	}elseif(strstr($HTTP_USER_AGENT, 'Unix')){
		define('VC_USR_OS', 'Unix');
	}else{
		define('VC_USR_OS', 'Other');
	}
	
	// 2. browser and version
	if (ereg('MSIE ([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $reg_agver))
	{
		define('VC_USR_BROWSER_VER', $reg_agver[1]);
		define('VC_USR_BROWSER_AGENT', 'IE');
	}elseif(ereg('Opera(/| )([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $reg_agver)){
		define('VC_USR_BROWSER_VER', $reg_agver[2]);
		define('VC_USR_BROWSER_AGENT', 'OPERA');
	}elseif(ereg('Mozilla/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $reg_agver)){
		define('VC_USR_BROWSER_VER', $reg_agver[1]);
		define('VC_USR_BROWSER_AGENT', 'MOZILLA');
	}elseif(ereg('Konqueror/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $reg_agver)){
		define('VC_USR_BROWSER_VER', $reg_agver[1]);
		define('VC_USR_BROWSER_AGENT', 'KONQUEROR');
	}else{
		define('VC_USR_BROWSER_VER', 0);
		define('VC_USR_BROWSER_AGENT', 'OTHER');
	}
}
?>

<?php

/**************************************************************
 * File: 		Session Set / Control File
 * Author:	Stephen M. Kitching (http://phpcoin.com)
 * Date:		2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 COINSoftTechnologies.ca
 * Notes:
 *	- Translation Page(s): n/a
 *	- Based on code by Mike Lansberry
**************************************************************/

# Set our desired "magic_quotes_runtime"
	set_magic_quotes_runtime(0);

# Following should provide php4.06 and register_globals off functionality
# Process Variables
	IF ( !isset($_SERVER)						)	{ $_SERVER	= $HTTP_SERVER_VARS;	}
	IF ( !isset($_POST)							)	{ $_POST		= $HTTP_POST_VARS;		}
	IF ( !isset($_GET)							)	{ $_GET		= $HTTP_GET_VARS;		}
	IF ( !isset($_COOKIE)						)	{ $_COOKIE	= $HTTP_COOKIE_VARS;	}
	IF ( !isset($_FILES)						)	{ $_FILES		= $HTTP_POST_FILES;		}
	IF ( !isset($_ENV)							)	{ $_ENV		= $HTTP_ENV_VARS;		}
	IF ( !isset($_SESSION) && isset($HTTP_SESSION_VAR))	{ $_SESSION 	= $HTTP_SESSION_VARS;	}

# Dim misc globals
	global $_GPV, $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_nl, $_sp;

# Figure out our location
	$separat = "/coin_";

# Build the web path
	$Location = $_SERVER['PHP_SELF'];
	$Location = str_replace("\\","/",$Location);
	$PathWeb = explode("/", $Location);
	$FileName = array_pop($PathWeb);
	$_PACKAGE['PATH'] = implode("/", $PathWeb);
	$data_array = explode("$separat",$_PACKAGE['PATH']);
	$_PACKAGE['PATH'] = $data_array[0];
	$_PACKAGE['PATH'] .= '/';

# Build the URL
	$_PACKAGE['URL'] = (($_SERVER["HTTPS"]=="on")?"https":"http").'://'.$_SERVER["SERVER_NAME"].((!empty($_SERVER["SERVER_PORT"]))?":".$_SERVER["SERVER_PORT"]:"").$_PACKAGE['PATH'];

# build the file path
	$tempdocroot = (substr(PHP_OS, 0, 3) == 'WIN') ? strtolower(getcwd()) : getcwd();
	$_PACKAGE['DIR'] = str_replace("\\","/",$tempdocroot);
	$data_array = explode("$separat",$_PACKAGE['DIR']);
	$_PACKAGE['DIR'] = $data_array[0];
	$_PACKAGE['DIR'] .= '/';

# Include config file
	require_once($_PACKAGE['DIR'].'config.php');

# Code to handle file being loaded by URL
	IF (eregi("session_set.php", $_SERVER['PHP_SELF'])) {
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01');
		exit();
	}

# Set Time Start
	global $_OTS;
	$_OTS = explode(" ",microtime());
	$_OTS = number_format( ($_OTS[1] + $_OTS[0]), 4, '.', '');


# Process "global" variables
	while(list($key, $var) = each($_GET)) {
		IF ($key == 'fs') {
		} ELSE {
			while($var != utf8_decode($var))	{$var = utf8_decode($var);}
			while($var != urldecode($var))	{$var = urldecode($var);}
			$var = htmlentities($var);
			IF (function_exists('html_entity_decode')) {
				$var = html_entity_decode($var);
			} ELSE {
				$var = unhtmlentities($var);
			}
			while($var != strip_tags($var))	{$var = strip_tags($var);}
			$pieces = explode("\"",$var);		$var = $pieces[0];
			$pieces = explode("'",$var);		$var = $pieces[0];
			$pieces = explode(" ",$var);		$var = $pieces[0];
			IF (eregi('_id', $key) || $key == 'id') {IF (!is_numeric($var)) {$var=0;}}
		}
		$_GPV[$key] = $var;
	}

	while(list($key, $var) = each($_POST))	{
		IF ($key == "pages_code" ||
			$key == "si_code" ||
			$key == "entry" ||
			$key == "ord_paylink" ||
			$key == "invc_paylink" ||
			$key == "invc_pay_link" ||
			$key == "invc_terms" ||
			$key == "vprod_order_link" ||
			$key == "mt_text" ||
			$key == "mc_email" ||
			$key == "overdue_template" ||
			$key == "faqqa_answer" ||
			$key == 'dload_desc' ||
			$key == 'dload_group' ||
			$key == 'dload_size' ||
			$key == 'parm_value' ||
			$key == 'dload_name' ||
			$key == 'dload_date' ||
			$key == 'dload_contributor') {
				$_GPV[$key] = $var;
		} ELSE {
			while($var != utf8_decode($var))	{$var = utf8_decode($var);}
			while($var != urldecode($var))	{$var = urldecode($var);}
			$var = htmlentities($var);
			IF (function_exists('html_entity_decode')) {
				$var = html_entity_decode($var);
			} ELSE {
				$var = unhtmlentities($var);
			}
			IF (eregi('_id', $key) || $key == 'id') {IF (!is_numeric($var)) {$var=0;}}
			IF (
				$key == "mc_msg" ||
				$key == "mc_name" ||
				$key == "cc_msg" ||
				$key == "hd_tt_message" ||
				$key == "hdi_tt_message"
			) {
				// message is in ascii ~ will be parsed later
			} ELSE {
				while($var != strip_tags($var)) {$var = strip_tags($var);}
			}
			$_GPV[$key] = $var;
		}
	}


# Read Admin and User Session Vars (prevent passing in URL)
	IF ( !isset($_SESSION["_sadmin_flg"]) )			{ $_SESSION["_sadmin_flg"] = 0;			}
	IF ( !isset($_SESSION["_sadmin_id"]) )			{ $_SESSION["_sadmin_id"] = 0;			}
	IF ( !isset($_SESSION["_sadmin_name"]) )		{ $_SESSION["_sadmin_name"] = 'none';		}
	IF ( !isset($_SESSION["_sadmin_name_first"]) )	{ $_SESSION["_sadmin_name_first"] = 'none';	}
	IF ( !isset($_SESSION["_sadmin_name_last"]) )	{ $_SESSION["_sadmin_name_last"] = 'none';	}

	IF ( !isset($_SESSION["_suser_flg"]) )			{ $_SESSION["_suser_flg"] = 0;			}
	IF ( !isset($_SESSION["_suser_id"]) )			{ $_SESSION["_suser_id"] = 0;				}
	IF ( !isset($_SESSION["_suser_name"]) )			{ $_SESSION["_suser_name"] = 'none';		}
	IF ( !isset($_SESSION["_suser_name_first"]) )	{ $_SESSION["_suser_name_first"] = 'none';	}
	IF ( !isset($_SESSION["_suser_name_last"]) )		{ $_SESSION["_suser_name_last"] = 'none';	}
	IF ( !isset($_SESSION["_suser_groups"]) )		{ $_SESSION["_suser_groups"] = 0;			}


# Finish up the session vars.
	IF (!isset($_GPV['op']))	{$_GPV['op'] = '';}
	IF (!isset($_GPV['o']))	{$_GPV['o'] = '';}

	IF (($_GPV['op'] == 'logout' || $_GPV['o'] == 'logout')) {
		session_name('phpcoinsessid');
		session_start();
		session_unset();
		session_destroy();
	} ELSE {
//		header("Cache-Control: must-revalidate");
//		header("Expires: " . gmdate("D, d M Y H:i:s", time()+24*60*60) . " GMT");
//		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		session_name('phpcoinsessid');
		session_start();
	}


# Include core file
	require_once($_CCFG['_PKG_PATH_INCL'].'core.php');

# For php < 4.3 compatability
# replaces html_entity_decode
function unhtmlentities($string) {
	 $trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	return strtr($string, $trans_tbl);
}
?>

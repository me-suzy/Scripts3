<?php

/**************************************************************
 * File: 		Error File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- For loading on package errors requiring redirect.
**************************************************************/

# Turn off pointless "warning" messages, and display errors on-screen
	ini_set('error_reporting','E_ALL & ~E_NOTICE');
	ini_set('display_errors', 'On');

/**************************************************************
 * Error File Loaded Flag
**************************************************************/

# Variable Process and Session Check
	IF (!isset($_GET)) {$_TGV = $HTTP_GET_VARS;} ELSE {$_TGV = $_GET;}
	while(list($key, $var) = each($_TGV)) {
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
		$_GPV[$key] = $var;
	}

	IF (!isset($_POST)) {$_TPV = $HTTP_POST_VARS;} ELSE {$_TPV = $_POST;}
	while(list($key, $var) = each($_TPV)) {
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
		$_GPV[$key] = $var;
	}

# Load Files
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	require_once('config.php');

/**************************************************************
 * Error File Functions Code
**************************************************************/

# For php < 4.3 compatability
# replaces html_entity_decode
function unhtmlentities($string) {
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	return strtr($string, $trans_tbl);
}

function error_block($block_title, $block_content, $block_delay=5, $block_redirect=1)
	{
		global $_CCFG, $_GPV, $_nl, $_sp;

		# Build Table Start and title
			$_out .= '<html>'.$_nl;
			$_out .= '<head>'.$_nl;
			$_out .= '<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">'.$_nl;
			$_out .= '<meta name="generator" content="phpcoin">'.$_nl;
			IF ( $block_redirect == 1 )
				{ $_out .= '<meta http-equiv="refresh" content="'.$block_delay.';URL='.$_CCFG['_PKG_REDIRECT_ROOT'].$_GPV[url].'">'.$_nl; }
			$_out .= '<title>'.'Package Error'.'</title>'.$_nl;

			$_out .= '<style media="screen" type="text/css">'.$_nl;
			$_out .= '<!--'.$_nl;
			$_out .= 'body				{ background-color: #FFFFFF; margin: 5px }'.$_nl;
			$_out .= 'p					{ color: #001; font-family: Verdana, Arial, Helvetica, Geneva }'.$_nl;
			$_out .= '.BLK_DEF_TITLE	{ font-family: Verdana, Arial, Helvetica, Geneva; background-color: #EBEBEB }'.$_nl;
			$_out .= '.BLK_DEF_ENTRY	{ font-family: Verdana, Arial, Helvetica, Geneva; background-color: #F5F5F5 }'.$_nl;
			$_out .= '.BLK_IT_TITLE		{ color: #001; font-style: normal; font-weight: bold; text-align: left; font-size: 12px; padding: 5px; height: 25px }'.$_nl;
			$_out .= '.BLK_IT_ENTRY		{ color: #001; font-style: normal; font-weight: normal; text-align: justify; font-size: 11px; padding: 5px }'.$_nl;
			$_out .= '--></style>'.$_nl;

			$_out .= '</head>'.$_nl;

			$_out .= '<body link="blue" vlink="red">'.$_nl;
			$_out .= '<div align="center" width="100%">'.$_nl;

			$_out .= '<br>';
			$_out .= '<div align="center" width="100%">';
			$_out .= '<table border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#000000">';
			$_out .= '<tr bgcolor="#000000"><td bgcolor="#000000">';
			$_out .= '<table border="0" cellpadding="0" cellspacing="1" width="100%">';
			$_out .= '<tr class="BLK_DEF_TITLE" height="30" valign="middle"><td class="BLK_IT_TITLE">';
			$_out .= $block_title;
			$_out .= '</td></tr>';
			$_out .= '<tr class="BLK_DEF_ENTRY"><td class="BLK_IT_ENTRY">';
			$_out .= $block_content;
			$_out .= '</td></tr>';
			$_out .= '</table>';
			$_out .= '</td></tr>';
			$_out .= '</table>';
			$_out .= '</div>';

			$_out .= '</div>'.$_nl;
			$_out .= '</body>'.$_nl;
			$_out .= '</html>'.$_nl;

		# Echo final output
			echo $_out;
	}


/**************************************************************
 * Error File Main Code
**************************************************************/
# Check $_GPV[err] and set default to list
	IF ( !$_GPV['err'] ) { $_GPV['err'] = '00'; }
	switch($_GPV['err'])
	{
		case "00":
			$block_title	= 'Error: Package';
			$block_content	= 'A package error has occurred, redirecting accordingly.';
			$block_delay	= 5;
			$block_redirect	= 1;
			break;
		case "01":
			$block_title	= 'Error: File Load';
			$block_content	= 'The file requested cannot be loaded directly by the browser, redirecting accordingly.';
			$block_delay	= 5;
			$block_redirect	= 1;
			break;
		case "02":
			$block_title	= 'Error: Admin Control Panel Request';
			$block_content	= 'That admin control panel requested does not exist or is not readable, redirecting accordingly.';
			$block_delay	= 5;
			$block_redirect	= 1;
			break;
		case "03":
			$block_title	= 'Error: Auxpage Request';
			$block_content	= 'That auxpage requested does not exist or is not readable, redirecting accordingly.';
			$block_delay	= 5;
			$block_redirect	= 1;
			break;
		case "04":
			$block_title	= 'Error: Module Request';
			$block_content	= 'That module requested does not exist or is not readable, redirecting accordingly.';
			$block_delay	= 5;
			$block_redirect	= 1;
			break;
		case "05":
			$block_title	= 'Error: Component Request';
			$block_content	= 'That package component requested does has been disabled, redirecting accordingly.';
			$block_delay	= 5;
			$block_redirect	= 1;
			break;
		case "50":
			$block_title	= 'Error: Banned IP';
			$block_content	= 'Sorry, but the your IP Address: '.$_SERVER["REMOTE_ADDR"].' has been banned from this site. Contact the site for additional details.';
			$block_delay	= 5;
			$block_redirect	= 0;
			break;
		case "97":
			$block_title	= 'Error: License Violation';
			$block_content	= 'The required '.$_PACKAGE[PATH].'docs/license.txt file could not be located on the server. You cannot run this package without it.';
			$block_delay	= 5;
			$block_redirect	= 0;
			break;
		case "98":
			$block_title	= 'Error: License Violation';
			$block_content	= 'The required metatag "generator" set to "phpcoin" could not be located in the output. You cannot run this package without it.';
			$block_delay	= 5;
			$block_redirect	= 0;
			break;
		case "99":
			$block_title	= 'Error: License Violation';
			$block_content	= 'The required "Powered By phpCOIN" statement could not be located in the output. You cannot run this package without it.';
			$block_delay	= 5;
			$block_redirect	= 0;
			break;
		default:
			$_GPV[err]		= '00';
			$block_title	= 'Error: Package';
			$block_content	= 'A package error has occurred, redirecting accordingly.';
			$block_delay	= 5;
			break;
	}

# Call output block function
	error_block($block_title, $block_content, $block_delay, $block_redirect);

?>
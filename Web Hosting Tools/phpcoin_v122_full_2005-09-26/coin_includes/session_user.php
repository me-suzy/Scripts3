<?php
/**************************************************************
 * File: 		User Session File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:	- for login / session set / redirect
**************************************************************/

# Following should provide php4.06 and register_globals off functionality
# Process Variables
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS;	}
	IF (!isset($_POST))		{ $_POST = $HTTP_POST_VARS;		}
	IF (!isset($_GET))		{ $_GET = $HTTP_GET_VARS;		}

# Load GET/POST in global array (POST overwrites GET)
	global $_GPV;
		while(list($key, $var) = each($_GET)) {
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
				$key == "overdue_template" ||
				$key == "faqqa_answer"
			) {
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
					while($var != strip_tags($var))	{$var = strip_tags($var);}
				}
				$_GPV[$key] = $var;
			}
		}

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

# Include config File for package url/path and required files
	require_once ( $_PACKAGE['DIR'] . 'config.php');


# Neutralize sql injection is hacker attempted to bypass login
	$USERNAME = addslashes($_POST[username]);

# Read the database in order to grab the default page to show client
# This is clunky, but the only way the default page could be stored in the database
# and therefore editable via Admin
	$connection = mysql_connect($_DBCFG['dbhost'], $_DBCFG['dbuname'], $_DBCFG['dbpass']) or die ("Unable To Connect!");
	$query  = "SELECT parm_value";
	$query .= " FROM ".$_DBCFG['table_prefix']."parameters";
	$query .= " WHERE parm_name = 'CLIENT_VIEW_PAGE_UPON_LOGIN'";
	$result = mysql_db_query($_DBCFG['dbname'], $query, $connection) or die ("Database Error On Query");
	IF (mysql_num_rows($result) == 1) {
		list($_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN']) = mysql_fetch_row($result);
	} ELSE {

	# Set 'cc' as default page if not set in database
		$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN'] = '1';
    }

# Set 'cc' as default page if not set in database
	IF (!$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN']) {$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN'] = 1;}

	switch($_GPV["mod"]) {
		default:

			IF (!$_GPV['mod']) {
				$_url = $_CCFG['_PKG_REDIRECT_ROOT'].'mod.php?mod='.$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_ACTION'][$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN']];
			} ELSE {
				$_url = $_CCFG['_PKG_REDIRECT_ROOT'].'mod.php?';
				while(list($key, $var) = each($_GPV)) {
					IF ($key != "password") {$_url .= $key.'='.$var.'&';}
				}
			}
			break;
	}

# Get the client data
//	$connection = mysql_connect($_DBCFG['dbhost'], $_DBCFG['dbuname'], $_DBCFG['dbpass']) or die ("Unable To Connect!");
	$query  = "SELECT cl_id, cl_user_name, cl_user_pword, cl_status, cl_name_first, cl_name_last, cl_groups";
	$query .= " FROM ".$_DBCFG['table_prefix']."clients";
	$query .= " WHERE cl_user_name = '$USERNAME' AND cl_status = '$_CCFG[_PKG_STR_ACTIVE]'";
	$result = mysql_db_query($_DBCFG['dbname'], $query, $connection) or die ("Database Error On Query");

	IF (mysql_num_rows($result) == 1) {
		# Get row
			list($cl_id, $cl_user_name, $cl_user_pword, $cl_status, $cl_name_first, $cl_name_last, $cl_groups) = mysql_fetch_row($result);

		# Process passwords to check for match
			# Get salt parameter from encrypted password
				$_salt = substr($cl_user_pword, 0, CRYPT_SALT_LENGTH);

			# Generate encrypted password of input
				$password_encrypt = crypt($_POST[password], $_salt);

			# Compare entered vs encrypted
				IF ( $password_encrypt == $cl_user_pword)
					{
						session_name('phpcoinsessid');
						session_start();
						$_SESSION['_suser_flg'] 			= 1;
						$_SESSION['_suser_id'] 			= $cl_id;
						$_SESSION['_suser_name'] 		= $cl_user_name;
						$_SESSION['_suser_name_first']	= $cl_name_first;
						$_SESSION['_suser_name_last'] 	= $cl_name_last;
						$_SESSION['_suser_groups'] 		= $cl_groups;

						mysql_free_result ($result);
						mysql_close($connection);
					}
				ELSE
					{
						# Passwords no-match, login failed
							$_url = $_CCFG['_PKG_REDIRECT_ROOT'].'login.php?w=user&o=login&e=p';
					}

		# Call redirect
			IF ( !$_url ) { $_url = $_CCFG['_PKG_REDIRECT_ROOT']; }
			header("Location: $_url");
			exit;
	}
	ELSE
	{
		# Free Up SQL stuff
			mysql_free_result ($result);
			mysql_close($connection);

		# Passwords no-match, login failed
			$_url = $_CCFG['_PKG_REDIRECT_ROOT'].'login.php?w=user&o=login&e=u';

		# Call redirect
			IF ( !$_url ) { $_url = $_CCFG['_PKG_REDIRECT_ROOT']; }
			header("Location: $_url");
			exit;
	}

# For php < 4.3 compatability
# replaces html_entity_decode
function unhtmlentities($string) {
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	return strtr($string, $trans_tbl);
}
?>
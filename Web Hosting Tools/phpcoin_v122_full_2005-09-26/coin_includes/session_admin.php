<?php
/**************************************************************
 * File: 		Admin Session File
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
		IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
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
				$key == "faqqa_answer" ||
				$key == "dload_desc"
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

	switch($_GPV['mod']) {
		default:
		IF (!$_GPV[mod]) {
			$_url = $_CCFG['_PKG_REDIRECT_ROOT'].'mod.php?mod=cc';
		} ELSE {
			$_url = $_CCFG['_PKG_REDIRECT_ROOT'].'mod.php?';
			while(list($key, $var) = each($_GPV)) {
				IF ($key != "password") {$_url .= $key.'='.$var.'&';}
			}
		}
			break;
	}




# Connect to database
	$connection = mysql_connect($_DBCFG['dbhost'], $_DBCFG['dbuname'], $_DBCFG['dbpass']) or die ("Unable To Connect!");
	$query  = "SELECT admin_id, admin_user_name, admin_user_pword, admin_name_first, admin_name_last, admin_perms";
	$query .= " FROM ".$_DBCFG['table_prefix']."admins";
	$query .= " WHERE admin_user_name = '".addslashes($_POST['username'])."'";

	$result = mysql_db_query($_DBCFG['dbname'], $query, $connection) or die ("Database Error On Query");

	IF (mysql_num_rows($result) == 1) {
		# Get row
			list($admin_id, $admin_user_name, $admin_user_pword, $admin_name_first, $admin_name_last, $admin_perms) = mysql_fetch_row($result);

		# Process passwords to check for match
			# Get salt parameter from encrypted password
				$_salt = substr($admin_user_pword, 0, CRYPT_SALT_LENGTH);

			# Generate encrypted password of input
				$password_encrypt = crypt(addslashes($_POST['password']), $_salt);
			# Compare entered vs encrypted
				IF ( $password_encrypt == $admin_user_pword)
					{
						session_name('phpcoinsessid');
						session_start();
						$_SESSION['_sadmin_flg'] 		= 1;
						$_SESSION['_sadmin_id'] 			= $admin_id;
						$_SESSION['_sadmin_name'] 		= $admin_user_name;
						$_SESSION['_sadmin_name_first']	= $admin_name_first;
						$_SESSION['_sadmin_name_last'] 	= $admin_name_last;
						$_SESSION['_sadmin_perms'] 		= $admin_perms;

						mysql_free_result ($result);
						mysql_close($connection);
					}
				ELSE
					{
						# Passwords no-match, login failed
							$_url = $_CCFG['_PKG_REDIRECT_ROOT'].'login.php?w=admin&o=login&e=p';
					}

		# Call redirect
			IF ( !$_url ) { $_url = $_CCFG['_PKG_REDIRECT_ROOT'].'admin.php'; }
			header("Location: $_url");
			exit;
	}
	ELSE
	{
		# Free Up SQL stuff
			mysql_free_result ($result);
			mysql_close($connection);

		# Passwords no-match, login failed
			$_url = $_CCFG['_PKG_REDIRECT_ROOT'].'login.php?w=admin&o=login&e=u';

		# Call redirect
			IF ( !$_url ) { $_url = $_CCFG['_PKG_REDIRECT_ROOT'].'admin.php'; }
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

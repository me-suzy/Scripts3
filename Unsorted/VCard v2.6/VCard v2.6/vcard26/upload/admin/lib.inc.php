<?php
if ( !defined('IN_VCARD') ){die("Hacking attempt");}
$debug = 0;
$session_check_ip = 1;
if (isset($explain))
{
	$showqueries = 1;
}
if (isset($showqueries))
{
	$pagestarttime = microtime();
}
// get rid of slashes in get / post / cookie data
function stripslashesarray (&$arr) {
  while (list($key,$val)=each($arr)) {
    if ((strtoupper($key)!=$key or "".intval($key)=="$key") and $key!="templatesused" and $key!="argc" and $key!="argv") {
			if (is_string($val)) {
				$arr[$key]=stripslashes($val);
			}
			if (is_array($val)) {
				$arr[$key]=stripslashesarray($val);
			}
	  }
  }
  return $arr;
}
if (get_magic_quotes_gpc() and is_array($GLOBALS)) {
  if (isset($attachment)) {
    $GLOBALS['attachment'] = addslashes($GLOBALS['attachment']);
  }
  $GLOBALS=stripslashesarray($GLOBALS);
}
set_magic_quotes_runtime(0);

/*******************************************
script settings
*******************************************/
set_magic_quotes_runtime(0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//error_reporting(E_ALL);

/*******************************************
fix register_global = off
*******************************************/
if(!empty($HTTP_GET_VARS['s']))
{
	$s =  addslashes($HTTP_GET_VARS['s']);
}else{
	$s = addslashes($HTTP_POST_VARS['s']);
}
if(!empty($HTTP_GET_VARS['action']))
{
	$action = $HTTP_GET_VARS['action'];
}else{
	$action = $HTTP_POST_VARS['action'];
}

/*******************************************
load login/password to dbase
*******************************************/
require('./config.inc.php');

/*******************************************
initiate dbase
*******************************************/
require('./db_mysql.inc.php');
$DB_site = new DB_Sql_vc;
$DB_site->server = $hostname;
$DB_site->user = $dbUser;
$DB_site->password = $dbPass;
$DB_site->database = $dbName;
$DB_site->connect();
$dbPass = '';		// clear database password variable to avoid retrieving
$DB_site->password = '';

/*******************************************
load vcard options
*******************************************/
$optionstemp = $DB_site->query_first(" SELECT template FROM vcard_template WHERE title='options' ");
eval($optionstemp['template']);

/*******************************************
load front end language lib
*******************************************/
if (file_exists('./../language/'.$site_lang.'/admin.lang.php'))
{
	require('./../language/'.$site_lang.'/admin.lang.php');
}else{
	require('./../language/english/admin.lang.php');
}

if (file_exists('./install.php') && $debug==0)
{
	echo "Security alert! install.php still remains in the admin directory. This poses a security risk, so please delete that file immediately (delete uninstall and other upgrade script too). You cannot access the control panel until you do! ";
	exit;
}
$dbPass = ''; // double check

/*******************************************
initiate functions load
*******************************************/
require('./functions.inc.php');
require('./adminfunctions.inc.php');
require('./define.inc.php');
require('./zip.inc.php');
$timenow = vcdate();
$templateversion= '2.5';   // template version

$cat_count_array = make_cat_count_array();

/*******************************************
initiate user env data
*******************************************/
if( getenv('HTTP_X_FORWARDED_FOR') != '' )
{
	$client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );
	if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ip_list) )
	{
		$private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10..*/', '/^224..*/', '/^240..*/');
		$client_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
	}
}else{
	$client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );
}
unset($vcuser);
unset($session);
$session['ip'] = $client_ip;
$session['agent'] = substr(getenv("HTTP_USER_AGENT"),0,50);
$session['sessionhash'] = $s;
if($action == 'login')
{
	$s = get_session_id($HTTP_POST_VARS['username'],$HTTP_POST_VARS['password']);
	$session['sessionhash'] = $s;
	//echo "$HTTP_POST_VARS[username],$HTTP_POST_VARS[password],$s";
	$action = 'frames';
}
if(!empty($s))
{
	$time = time();
	if($use_admin_password == 1)
	{
		$sql_add = ($session_check_ip)? " AND session_ip='$session[ip]' " : '';
		$sql = "SELECT * FROM vcard_session WHERE session_id='$session[sessionhash]' $sql_add AND session_agent='$session[agent]' AND session_date>'" .time(). "' ";
		
		if ($sessioninfo = $DB_site->query_first($sql) )
		{
			$new_session_date = time()+1200;
			$atualizar = $DB_site->query(" UPDATE vcard_session SET session_date='$new_session_date' WHERE session_id='$sessioninfo[session_id]' $sql_add AND session_agent='$session[agent]'");
			$vcuser = $DB_site->query_first("SELECT * FROM vcard_account WHERE account_id='$sessioninfo[session_user]' ");
			extract($vcuser);
			$vcuser['ip'] = $vcuser['session_ip']; // substr($REMOTE_ADDR,0,50);
			$vcuser['useragent'] = $vcuser['session_agent']; //substr($HTTP_USER_AGENT,0,50);
			//echo "oik1";
		}else{
			//echo "ERROR USER";
			$superuser = 0;
			unset($vcuser);
			unset($s);
			$makelgoin = 1;
			echo "<p><a href=\"$site_prog_url/admin/\" target=\"_top\">$msg_admin_login_alert</a></p>";
			exit;
		}
	}else{
			$superuser = 1;
	}
}else{
	$makelogin = 1;
	unset($action);
	unset($s);
}

if ($action == 'logout')
{
	$DB_site->query("DELETE FROM vcard_session WHERE session_id='$s' OR session_date<'".time()."'");
	unset($action);
	unset($s);
}
if (empty($action) && empty($s) || $makelogin==1)
{
	dothml_pageheader();
	dohtml_form_header("index","login",0,0);
	dohtml_table_header("login","$msg_admin_controlpanel");
	dohtml_form_input($msg_admin_login_username,"username","");
	dohtml_form_password($msg_admin_login_password,"password","");
	dohtml_form_footer($msg_admin_login_button);
	dohtml_table_footer();
	dothml_pagefooter();
	exit;
}
?>
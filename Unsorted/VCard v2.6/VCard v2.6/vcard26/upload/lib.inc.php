<?php
/***************************************************************************
 *   script               : vCard PRO
 *   copyright            : (C) 2001-2002 Belchior Foundry
 *   website              : www.belchiorfoundry.com
 *
 *   This program is commercial software; you can´t redistribute it under
 *   any circumstance without explicit authorization from Belchior Foundry.
 *   http://www.belchiorfoundry.com/
 *
 ***************************************************************************/
if ( !defined('IN_VCARD') ){die("Hacking attempt");}
$debug = 0;
$use_gzip = 0;
$explain = 0;
if(!empty($explain)) { $showqueries = 1; }
if(!empty($showqueries)){ $pagestarttime = microtime(); }
/********************************************************************
benchmark
********************************************************************/
class BC_Timer{ 
	var $stime; 
	var $etime; 
	function get_microtime() {
		$tmp = split(" ",microtime()); 
		$rt = $tmp[0]+$tmp[1]; 
		return $rt; 
	}
	function start_time(){ 
		$this->stime = $this->get_microtime(); 
	}
	function end_time(){ 
		$this->etime = $this->get_microtime(); 
	}
	function elapsed_time(){ 
		global $use_gzip,$numqueries,$vcachesys;
		$gzip = ($use_gzip==1)?'Enabled':'Disabled';
		echo "<small><p align=center>[ vCard execution time : ". ($this->etime - $this->stime) ." ] [Queries: $numqueries ] [ GZIP : $gzip ] [Cache: $vcachesys] </p></small>";
		exit;
	}
}
function stripslashesarray (&$arr) {
	while (list($key,$val)=each($arr))
	{
		if ((strtoupper($key)!=$key || "".intval($key)=="$key") && $key!="templatesused" && $key!="argc" && $key!="argv")
		{
			if (is_string($val))
			{
				$arr[$key] = stripslashes($val);
			}
			if (is_array($val))
			{
				$arr[$key] = stripslashesarray($val);
			}
		}
	}
	return $arr;
}
if (get_magic_quotes_gpc()==1 && is_array($GLOBALS))
{
	if (isset($attachment))
	{
		$GLOBALS['attachment'] = addslashes($GLOBALS['attachment']);
	}
	$GLOBALS = stripslashesarray($GLOBALS);
}

/*******************************************
script settings
*******************************************/
set_magic_quotes_runtime(0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//error_reporting(E_ALL);
if($debug==1){ $timer = new BC_Timer;  $timer->start_time(); }

/*******************************************
load login/password to dbase
*******************************************/
require('./admin/config.inc.php');

/*******************************************
gzip output (need PHP 4.0.4+ and Zlib)
*******************************************/
if($use_gzip == 1) { ob_start ('ob_gzhandler'); }
/*******************************************
initiate dbase
*******************************************/
require('./admin/db_mysql.inc.php');
$DB_site = new DB_Sql_vc;
$DB_site->server = $hostname;
$DB_site->user = $dbUser;
$DB_site->password = $dbPass;
$DB_site->database = $dbName;
$DB_site->connect();
$dbPass = '';
$DB_site->password = '';

/*******************************************
initiate functions load
*******************************************/
require('./admin/functions.inc.php');

/*******************************************
load vcard options
*******************************************/
$optionstemp = $DB_site->query_first("SELECT template FROM vcard_template WHERE title='options' ");
eval($optionstemp['template']);

/*******************************************
load front end language lib
*******************************************/
if(@file_exists('./language/'.$site_lang.'/user.lang.php') == 0)
{
	require('./language/english/user.lang.php');
}else{
	require('./language/'.$site_lang.'/user.lang.php');
}
/*******************************************
online feature
*******************************************/
if($online_users ==1)
{
	require('./admin/online.inc.php');
}

/*******************************************
get default templates
*******************************************/
if (!empty($templatesused))
{
	$templatesused .= ',';
}
$templatesused .= 'headinclude,phpinclude,header,footer,errorpage,postcard_imagelink,category_imagelink,category_textlink,categories_textlist,categories_textlist_rows_maincat,categories_textlist_rows_subcat,top_card_list_item,top_card_list,calendar_list,calendar_list_day,calendar_list_month,top_card_list_item,top_card_list,unavaiablepage';
unset($templatecache);
cache_templates($templatesused);

if (!empty($chaceitemused))
{
	$chaceitemused .= ',';
}
$chaceitemused .= 'topcat_,cachedate,dropdown,categories_text,calendar,categories_extended_list,week_topcard,today_topcard,categories_table_upcat,categories_table_cat,newcard';
unset($vcardcache);
($vcachesys == 1)? cache_vcard_pieces($chaceitemused):'';
$cat_count_array = make_cat_count_array();
//view_array($cat_count_array);

if($vcachesys != 1)
{
	$dropdownlist = get_html_dropdown_cat();
	$categories_textlist = get_html_table_cattex();
	$calendar_list = get_html_event();
	$topx_list = get_html_toplist();

} else {
	$cachedate = get_vc_cached_item('cachedate');
	$dropdownlist = get_vc_cached_item('dropdown');
	$categories_textlist = get_vc_cached_item('categories_text');
	$calendar_list = get_vc_cached_item('calendar');
	$topx_list = get_vc_cached_cattoplist();
}

$topx	= "$MsgTop $gallery_toplist_value $MsgPostcards";
if($gallery_toplist_allow != 1)
{
	$topx	= '';
	$topx_list = '';
}


/*******************************************
initiate user env data
*******************************************/
if( getenv('HTTP_X_FORWARDED_FOR')!='' )
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
unset($session);
$enduser_ip = $client_ip; // dont delete this line.
$session['ip'] = $client_ip;
$session['agent'] = substr(getenv("HTTP_USER_AGENT"),0,50);

$timenow = vcdate();
$todaydate = vcdate(0);
$todayext = get_date_current(1);
$site_name = stripslashes($site_name);
srand((double)microtime()*1000000);
$rand_number = rand();

eval(get_template('phpinclude',0,0));
if(empty($heading))
{
	eval("\$headinclude = \"".get_template('headinclude')."\";");
	$headinclude .= '<script language="JavaScript" src="script.js"></script>';
}
if(empty($header))
{
	eval("\$header = \"".get_template('header')."\";");
}
if(empty($footer))
{
	eval("\$footer = \"".get_template('footer')."\";");
}
$htmlbody = html_body($site_image_url,$site_body_bgimage,$site_body_bgcolor,$site_body_text,$site_body_link,$site_body_vlink,$site_body_alink,$site_body_marginwidth,$site_body_marginheight);

// Active -then display error
if($vcardactive == 0)
{
	eval("make_output(\"".get_template("unavaiablepage")."\");");
	exit;
}

?>
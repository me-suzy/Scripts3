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
define('IN_VCARD', true);
$templatesused = '';

error_reporting(E_ERROR | E_WARNING | E_PARSE);
/*******************************************
load login/password to dbase
*******************************************/
require('./admin/config.inc.php');

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
$dbPass = '';		// clear database password variable to avoid retrieving
$DB_site->password = '';

$optionstemp = $DB_site->query_first("SELECT template FROM vcard_template WHERE title='options' ");
eval($optionstemp['template']);

$attach_id = (int) $HTTP_GET_VARS['id'];
$message_id = addslashes($HTTP_GET_VARS['ck']);
$attach_info = $DB_site->query_first("SELECT filename, filedata,messageid FROM vcard_attach WHERE attach_id='".addslashes($attach_id)."' ");
$DB_site->close();
if(empty($attach_info['filename']))
{
	echo "no attach";
	exit;
}
//if($attach_info['messageid']!=$message_id)
//{
//	echo 'denied access';
//	exit;
//}
$int_refer = $site_prog_url;
$int_refer = str_replace('http://www.','', $int_refer);
$int_refer = str_replace('http://','', $int_refer);
if(!eregi($int_refer,getenv('HTTP_REFERER')))
{
	echo "forbidden access";
	exit;
}

header("Content-Length: ".strlen($attach_info['filedata']));
header("Content-Disposition: filename=$filename");
header("Content-Description: PHP Generated Data");
$extension=strtolower(substr(strrchr($attach_info['filename'],'.'),1));
if($extension=='gif')
{
	header("Content-type: image/gif");
}elseif($extension=='jpg' or $extension=='jpeg'){
	header("Content-type: image/pjpeg");
}elseif($extension=='png'){
	header("Content-type: image/png");
}elseif($extension=='swf'){
	header("Content-type: application/futuresplash");
}else{
	header("Content-type: unknown/unknown");
}
echo $attach_info['filedata'];
exit;
?>
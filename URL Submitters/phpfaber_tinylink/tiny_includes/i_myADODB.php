<?php

// -----------------------------------------------------------------------------
//
// phpFaber TinyLink v.1.0
// Copyright(C), phpFaber LLC, 2004-2005, All Rights Reserved.
// E-mail: products@phpfaber.com
//
// All forms of reproduction, including, but not limited to, internet posting,
// printing, e-mailing, faxing and recording are strictly prohibited.
// One license required per site running phpFaber TinyLink.
// To obtain a license for using phpFaber TinyLink, please register at
// http://www.phpfaber.com/i/products/tinylink/
//
// 19:59 28.07.2005
//
// -----------------------------------------------------------------------------

if(!isset($PHP_SELF)) $PHP_SELF=getenv("SCRIPT_NAME");
if(!isset($QUERY_STRING)) $QUERY_STRING = "";
if(!isset($REQUEST_URI)) $REQUEST_URI = $PHP_SELF;

$ADO_HOSTNAME = $config['dbhost'];
$ADO_DBTYPE   = $config['dbtype'];
$ADO_DATABASE = $config['dbname'];
$ADO_USERNAME = $config['dbuser'];
$ADO_PASSWORD = $config['dbpass'];

$QUB_Caching = false;

ADOLoadCode($ADO_DBTYPE);

$myDB = &ADONewConnection($ADO_DBTYPE);
if($ADO_DBTYPE == "access" || $ADO_DBTYPE == "odbc") $myDB->PConnect($ADO_DATABASE, $ADO_USERNAME,$ADO_PASSWORD);
elseif($ADO_DBTYPE == "ibase") $myDB->PConnect($ADO_HOSTNAME.":".$ADO_DATABASE,$ADO_USERNAME,$ADO_PASSWORD);
else $myDB->PConnect($ADO_HOSTNAME,$ADO_USERNAME,$ADO_PASSWORD,$ADO_DATABASE);

?>
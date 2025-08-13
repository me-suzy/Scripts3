<?php
//---------------------------------------------------------------------/*
######################################################################
# Support Services Manager											 #
# Copyright 2002 by Shedd Technologies International | sheddtech.com #
# All rights reserved.												 #
######################################################################
# Distribution of this software is strictly prohibited except under  #
# the terms of the STI License Agreement.  Email info@sheddtech.com  #
# for information.  												 #
######################################################################
# Please visit sheddtech.com for technical support.  We ask that you #
# read the enclosed documentation thoroughly before requesting 		 #
# support.															 #
######################################################################*/
//---------------------------------------------------------------------
//database variables
require("C:\\My Documents\\STnet\\software\\tt_system\\db.php");
$db_name=$setting["dbselect"];
$db_username=$setting["dbuser"];
$db_password=$setting["dbpass"];
$db_host=$setting["dbhost"];
//table definitions
$user_table="profiles";
$ticket_table=$table_pre."tickets";
$subj_table=$table_pre."subjects";
$kbart_table=$table_pre."kbarticles";
$config_table=$table_pre."config";
//---------------------------------------------------------------------
//connect to mysql
$link=@mysql_connect($setting["dbhost"],$setting["dbuser"],$setting["dbpass"]);
if(!$link){
	die($setting["title"].": Could not connect to database.");
}
//select database
mysql_select_db($setting["dbselect"]) or die($setting["title"].": Could not select database.");
//---------------------------------------------------------------------
$sql="SELECT * FROM $config_table;";
$result=mysql_query($sql);
$num_rows=mysql_num_rows($result);
//whatever is the last row in the config table 
//	will stand as the config vars
while($value=mysql_fetch_array($result)){
	//format of vars from db $value["field_name"];
	//define the config vars in this loop
	///////////////////////////////////////////////
	//system path to installation of ssm-trailing slash
	$base_path=$value["base_path"];
	//web path to ssm-trailing slash
	$web_path=$value["web_path"];
	//web path to administration area-trailing slash
	$admin_web_path=$value["admin_web_path"];
	//system path to OpenBB installation-No trailing slash
	//$openbb_path=$value["openbb_path"];
	//web path to OpenBB installation-trailing slash
	$openbb_webpath=$value["openbb_web_path"];
	//system path to session temp folder-trailing slash
	$setting["session_path"]=$value["session_path"];
	//title of this system
	$title=$value["title"];
	//turn emailing of alerts ON/OFF
	$alerts=$value["alerts"];
	//address for replys from alerts to be sent to
	$reply_to=$value["reply_to"];
	//message in alert email to users
	$a_message=$value["a_message"];
	//subject in alert email to users
	$a_subject=$value["a_subject"];
	//administrator email address - for alerts to be sent to
	$admin_email=$value["admin_email"];
	//subject for administrator alert email
	$ae_subj=$value["ae_subject"];
	//message for administrator alert email
	$ae_mssg=$value["ae_message"];
	//registration (1 for OpenBB / 2 for SSM) -- if you have OpenBB installed, we encourage you to choose 1
	$reg_system=$value["reg_system"];
	//body background color for client side
	$background=$value["background"];
	//main table background for client side
	$maintabc=$value["maintable_background"];
	///////////////////////////////////////////////
	//internal variable--DO NOT EDIT
	$ssm_sys_version=urlencode($value["ssm_sys_version"]);
	///////////////////////////////////////////////
}//end loop
//---------------------------------------------------------------------
$page_title=$title;
//main style sheet
$style_sheet=$web_path."style.css";
//other style sheets (.css) are hardcoded
//---------------------------------------------------------------------
?>

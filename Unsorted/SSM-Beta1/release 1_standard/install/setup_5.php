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
require("cvar.php");
//Stage 4: Write the config info to the database
if($stage=="4"){

	//load database connection information
	require($file);
	//transfer database information
	$db_name=$setting["dbselect"];
	$db_username=$setting["dbuser"];
	$db_password=$setting["dbpass"];
	$db_host=$setting["dbhost"];
	//connect to mysql
	$link=@mysql_connect($setting["dbhost"],$setting["dbuser"],$setting["dbpass"]);
	if(!$link){
		die($setting["title"].": Could not connect to database.");
	}
	//select database
	mysql_select_db($setting["dbselect"]) or die($setting["title"].": Could not select database.");
	


	//Make database changes
		$base_path=addslashes($base_path);
		//$openbb_path=addslashes($openbb_path);
		$sqlarray=array(
			"INSERT INTO ".$table_pre."config (base_path, web_path, admin_web_path, openbb_web_path, session_path, background, maintable_background, title, alerts, reply_to, a_message, a_subject, admin_email, ae_subject, ae_message, reg_system, ssm_sys_version) VALUES ('".$base_path1."', '".$web_path1."', '".$admin_web_path1."', '".$openbb_webpath1."', '".$session_path1."', '".$background1."', '".$maintabc1."', '".$title1."', '".$alerts1."', '".$reply_to1."', '".$a_message1."', '".$a_subject1."', '".$admin_email_1."', '".$ae_subj1."', '".$ae_mssg1."', '".$reg_system1."', '1.0 BETA');"
		);
		//Process Query
		
		$err=false;
		foreach($sqlarray as $sql){
			$result="";
			if(!$result=mysql_query($sql)){
				print "<p>Error in updating data!<br>";
				print mysql_error();
				print '<br><a href="setup_5.php';
				print '?stage=4">Click Here to try again</a><br><br>';
				print "$sql</p>";
				$err=true;
			}//end error
		}//end loop
		if($err!=true){
			?><br><br><div align="center">
			<STRONG><FONT face=Verdana size=2>All settings have been updated.</FONT></STRONG>
			<?php
				print '<br><br><a href="setup_6.php';
				print '?stage=5">Click Here to Continue</a></div><br><br>';
		}//end no error
		else{
			print "Error in updating information.  Please try again.";
		}
}//end stage 4
?>

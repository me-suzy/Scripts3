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
//Stage 5A: Complete Database Additions for Administrator
if($stage=="5A"){
	//register admin

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
	
	
	//validate input
	if(!isset($username)||!isset($password)){
		die("You must set a username & password!");
	}
	if(!isset($email)){
		die("You must include an email address!");
	}
	if($password!=$password2){
		die("Entered Password DO NOT match!");
	}
	$result=mysql_query("SELECT * FROM profiles WHERE username = '$username';");
	$rows=mysql_numrows($result);
	if($rows!=0){
		die("Username is in use!");
	}
	
	//generate SQL
	$result="";
	if(!$result=mysql_query("INSERT INTO profiles (`id`, `username`, `password`, `email`, `homepage`, `icq`, `aim`, `yahoo`, `location`, `note`, `showemail`, `usergroup`, `posts`, `joindate`, `timeoffset`, `timezone`, `avatar`, `custom`, `homepagedesc`, `occupation`, `msn`, `templategroup`, `vargroup`, `totalpm`, `lastpm`, `invisible`, `activated`, `banned`, `sig`, `showavatar`, `showsig`, `showhistory`, `addowntopics`, `autosubscribe`, `TICKETS`) VALUES ('', '".htmlspecialchars($username)."', '".md5($password2)."', '".strip_tags($email)."', '".strip_tags($homepage)."', '".strip_tags($icq)."', '".strip_tags($aim)."', '".htmlspecialchars($yahoo)."', '".strip_tags($location)."', '$note', '1', '3', '0', '".time()."', '0', '0', '', '', '".strip_tags($homepagedesc)."', '".strip_tags($occupation)."', '".strip_tags($msn)."', '0', '0', '0', '0', '1', '1', '1', '".strip_tags($sig)."', '1', '1', '1', '1', '1', '0');")){
		print "<p>Error in updating data!<br>";
		print mysql_error();
		print '<br><a href="setup_6.php';
		print '?stage=5A">Click Here to try again</a><br><br>';
		print "$sql</p>";
		$err=true;
	}//end error

	if($err!=true){
		?><br><br><div align="center">
		<STRONG><FONT face=Verdana size=2>You have been registered.</FONT></STRONG>
		<?php
		print '<br><br><a href="setup_8.php?';
		print 'stage=6">Click Here to Continue</a></div><br><br>';
	}//end no error
	else{
		print "Error in updating information.  Please try again.";
	}
}//end stage 5A
?>

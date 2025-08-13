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
include("config.php");

//authenticate
//set the session path
session_save_path($setting["session_path"]);
session_start();
//check the authentication information
//user_user, pass_user
if(isset($user_user)){
	//pull password for selected user to check
	$sql="SELECT password FROM $user_table WHERE username='$user_user';";
	$result=mysql_query($sql);
	$num_rows=mysql_num_rows($result);
	if($num_rows==0){
		die("Authentication Not Possible!<!--Pos 3-->");
	}
	elseif($num_rows>1){
		die("Authentication Not Possible!<!--Pos 4-->");
	}
	else{
	//login found
		while($value=mysql_fetch_array($result)){
			$password=$value["password"];
		}
	}
	//check the encrypted password in session against crypted password from database
	if (trim($password)==trim($pass_user)){
   		//Password verified
   		$ver=true;
		$logged_in=true;
	}
	else{
		die("Authentication Not Possible!<!--Pos 2-->");
	}
}
//user_user not set
else{
	die("Authentication Not Possible!<!--Pos 1-->");
}

//send page expiration

//include default language file
include($base_path."language.php");
//define end file
$end_file=$base_path."footer.php";
//---------------------------------------------------------------------
//Globals
//---------------------------------------------------------------------
global $PHP_SELF;
global $PHP_SCRIPT;
global $db_host,$db_username,$db_password,$l_dberror;
global $link;
global $db_name,$l_dbselecterr;
//---------------------------------------------------------------------
//FUNCTIONS
//---------------------------------------------------------------------
//This function will generate the dropdown box for subjects
function dropdown($link){
	global $subj_table;
	print '<SELECT NAME="subject" class="prefinput">';
		$query="SELECT VALUE FROM $subj_table ORDER BY NUMBER ASC;";
		if($result=mysql_query($query,$link)){
			while($row=mysql_fetch_array($result)){
				print "<OPTION VALUE=\"$row[VALUE]\">$row[VALUE]</OPTION>";
			}
		}
	print '</SELECT>';
}//end function

//This function will print the Recent Tickets button
function recent_button(){
global $u,$p,$username,$password;
print "<FORM NAME=\"Ticket System\" ACTION=\"$PHP_SELF\" METHOD=post>";
print '<INPUT TYPE=HIDDEN NAME="action" VALUE="">
<INPUT TYPE=SUBMIT VALUE="Show Recent Tickets" class="but">';
print "</FORM>";
}//end function

//This function will print the Show Open Tickets button
function open_button(){
global $u,$p,$username,$password;
print "<FORM NAME=\"Ticket System\" ACTION=\"$PHP_SELF\" METHOD=post>";
print '<INPUT TYPE=HIDDEN NAME="action" VALUE="Open">
<INPUT TYPE=SUBMIT VALUE="Show Open Tickets" class="but">';
print "</FORM>";
}//end function

//This function will print the Show Closed Ticket button
function closed_button(){
global $u,$p,$username,$password;
print "<FORM NAME=\"Ticket System\" ACTION=\"$PHP_SELF\" METHOD=post>";
print '<INPUT TYPE=HIDDEN NAME="action" VALUE="Closed">
<INPUT TYPE=SUBMIT VALUE="Show Closed Tickets" class="but">';
print "</FORM>";
}//end function

//This function will print the New Ticket button
function new_button(){
global $u,$p,$username,$password;
print "<FORM NAME=\"Ticket System\" ACTION=\"$PHP_SELF\" METHOD=post>";
print '<INPUT TYPE=HIDDEN NAME="action" VALUE="New">
<INPUT TYPE=SUBMIT VALUE="New Support Request" class="but">';
print "</FORM>";
}//end function

/*
This function creates and dispatches an alert mail to either an Admin 
due to a new request being opened OR to a User because a ticket has 
been responded to.

Variables used:
	$to is a *required* param
	$subject is *required* param
	$message is *required* param
	$reply_to is already set
	
Line 607 system.php
Line 388 admin.php
*/
function alert_mail($to,$subject,$message){
	global $alerts,$SERVER_NAME,$reply_to,$a_message,$a_subject,$title,$web_path;
	if($alerts=="ON"){
		//send the mail
		mail($to, $subject, $message,"From: ssm@$SERVER_NAME\r\n"."Reply-To: $reply_to\r\n"."X-Mailer: PHP/".phpversion());
	}
}//end function
//---------------------------------------------------------------------
if($action==""){ $at=2; }
elseif($action=="New"){ $at=3; }
elseif($action=="Open"){ $at=5; }
elseif($action=="Closed"){ $at=6; }
elseif($action=="kb"){ $at=8; }
//---------------------------------------------------------------------
$username=$user_user;
?>

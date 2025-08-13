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
if($stage=="3"){
/*
Connect to the database and write the tables.
*/
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
	
	$sqlcode=array(
		"CREATE TABLE [%PRE%]kbarticles (id int(11) NOT NULL default '0',category text NOT NULL,title text NOT NULL,content text NOT NULL,author text NOT NULL,PRIMARY KEY (id));",
		"CREATE TABLE [%PRE%]tickets (NUMBER int(11) NOT NULL default '0',ACCOUNT text NOT NULL,SUBJECT text NOT NULL,DATE text NOT NULL,TIME time NOT NULL default '00:00:00',STATUS text,NAME text NOT NULL,EMAIL text NOT NULL,URGENCY text NOT NULL,ADMIN text NOT NULL,CHILD int(11) NOT NULL default '0',QUESTION longtext NOT NULL);",
		"CREATE TABLE [%PRE%]config (base_path text NOT NULL,web_path text NOT NULL,admin_web_path text NOT NULL,openbb_web_path text NOT NULL,session_path text NOT NULL,background text NOT NULL,maintable_background text NOT NULL,title text NOT NULL,alerts enum('ON','OFF') NOT NULL default 'ON',reply_to text NOT NULL,a_message text NOT NULL,a_subject text NOT NULL,admin_email text NOT NULL,ae_subject text NOT NULL,ae_message text NOT NULL,reg_system enum('1','2') NOT NULL default '1',ssm_sys_version text NOT NULL);",
		"CREATE TABLE [%PRE%]subjects (NUMBER int(11) NOT NULL auto_increment,VALUE text NOT NULL,PRIMARY KEY (NUMBER),UNIQUE KEY NUMBER(NUMBER),KEY NUMBER_2(NUMBER));",
		"CREATE TABLE profiles (id smallint(4) NOT NULL auto_increment,username varchar(50) NOT NULL default '',password varchar(75) NOT NULL default '',email varchar(75) NOT NULL default '',homepage varchar(100) NOT NULL default '',icq varchar(25) NOT NULL default '',aim varchar(25) NOT NULL default '',yahoo varchar(25) NOT NULL default '',location varchar(25) NOT NULL default '',note mediumtext NOT NULL,showemail enum('1','0') NOT NULL default '1',usergroup tinyint(3) NOT NULL default '0',posts int(7) NOT NULL default '0',joindate int(10) NOT NULL default '0',timeoffset int(10) NOT NULL default '0',timezone tinyint(4) NOT NULL default '0',avatar varchar(100) NOT NULL default '',custom varchar(50) NOT NULL default '',homepagedesc varchar(100) NOT NULL default '',occupation varchar(50) NOT NULL default '',msn varchar(75) NOT NULL default '',templategroup tinyint(4) NOT NULL default '0',vargroup tinyint(4) NOT NULL default '0',totalpm int(5) NOT NULL default '0',lastpm int(10) NOT NULL default '0',invisible enum('1','0') NOT NULL default '1',activated enum('1','0') NOT NULL default '1',banned enum('1','0') NOT NULL default '1',sig mediumtext NOT NULL,showavatar enum('1','0') NOT NULL default '1',showsig enum('1','0') NOT NULL default '1',showhistory enum('1','0') NOT NULL default '1',addowntopics enum('1','0') NOT NULL default '1',autosubscribe enum('1','0') NOT NULL default '1',TICKETS bigint(20) NOT NULL default '0',PRIMARY KEY (id));",
	);
	$err=false;
	foreach($sqlcode as $sql){
		/*
		Codes used in database definition file:
			[%PRE%]-replace with the table preset
				the table PROFILES DOES NOT USE A PREFIX
		*/
		$sql=str_replace("[%PRE%]",$table_pre,$sql);
		
		$result=mysql_query($sql);
		if(!$result){
			die("Problem loading tables!<br><br><pre>$sql</pre><br><br>".mysql_error());
			$err=true;
		}
	}
	
	if($err!=true){
		//show configuration screen
		confirmation_screen();
	}
}//end stage 3
//--------------------------------------------------------------------
function confirmation_screen(){
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
	<html>
	<head>
		<title>Support Services Manager Installation</title>
	</head>
	<body>
	<P align=center><FONT face=Verdana size=4>Support Services 
	Manager&nbsp;<?php print $ver; ?></FONT><BR><FONT face=Verdana>Installation &amp; Setup</FONT></P>
	<P>
	<HR width="90%" color=black SIZE=1>
	<P></P>
	<P align=left><FONT face=Verdana>The proper tables have been successfully written to your database.&nbsp; Now, please 
	fill out the below table with the indicated information so that Setup can complete the database changes.  These settings can be changed from within the Administration Area at any time.
	</FONT></P>
	<?php
	config_table();
	?>
	<P><FONT face=Verdana>Once you have completed this step, please click 
	Continue to have Setup make the neccessary additions to your 
	database.</FONT></P>
	<HR width="90%" color=black SIZE=1>
	<input type="hidden" name="stage" value="4">
	</form>
	</body>
	</html>
<?php
}//end function
function config_table(){
?>
		<form action="setup_5.php" method="post">
		<table width="550" align="center" cellspacing="0" cellpadding="2" border="0">
		<tr>
		    <td colspan="2" bgcolor="#b7b7b7"><b>System Settings</b></td>
		</tr>
		<tr><td colspan="2" bgcolor="black"></td></tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>System Path to Temporary Directory 
      (with trailing slash).&nbsp; This is the system path the the temp/ 
      directory that was created on your system when you copied the SSM 
      files.&nbsp; If you have another temporary directory, you can also 
      specify&nbsp;it here - make sure that it can be written to.&nbsp;</P></td>
		    <td>&nbsp;<input name="session_path1" size="45" ></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>System Path to SSM (with trailing 
      slash).&nbsp; This is the system path to the directory where you installed SSM.&nbsp;</P></td>
		    <td>&nbsp;<input name="base_path1" size="45" ></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Web URL to SSM (with trailing 
      slash).&nbsp; This is the URL to your installation of SSM.&nbsp;</P></td>
		    <td>&nbsp;<input name="web_path1" size="45" ></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Web URL to Administration Area (with 
      trailing slash).&nbsp; This is the URL to the Administration Area.&nbsp; 
      Unless you have&nbsp;moved files and folders from their default location, 
      simply and&nbsp;'admin/' to the last value that you typed.&nbsp;</P></td>
		    <td>&nbsp;<input name="admin_web_path1" size="45" ></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Web URL to OpenBB (with trailing 
      slash).&nbsp; If you have OpenBB installed on your system, this is the URL to it.&nbsp;  If you do not have OpenBB installed, place 'N/A' in the field.</P></td>
		    <td>&nbsp;<input name="openbb_webpath1" size="45" ></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>System Title.&nbsp; This is the title 
      that you would like your&nbsp;Support Desk system to be called.&nbsp;</P></td>
		    <td>&nbsp;<input name="title1" size="45" value="Support Services Manager"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Email Alerts On/Off.&nbsp; This option 
      allows you to turn email alerts to users and administrators On or Off.&nbsp;</P></td>
		    <td>&nbsp;<select name="alerts1" size="1" class="prefinput">
						<option value="ON" SELECTED>ON</option>
						<option value="OFF">OFF</option>
				</select>
			</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Reply to Address for User 
      Alerts.&nbsp; This is the Reply To address&nbsp;which will be included 
      when&nbsp;Email Alerts are sent&nbsp;to your users.&nbsp;</P></td>
		    <td>&nbsp;<input name="reply_to1" size="35" value=webmaster@domain.com 
     ></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>User Email Alert Message.&nbsp; This 
      is the&nbsp;text of the email alert to your users.&nbsp;</P></td>
		    <td>&nbsp;<TEXTAREA class=prefinput name=a_message1 rows=5 cols=40>This email is to inform you that an administrator has responded to your support request.  Please visit our site and log in to view the response.</TEXTAREA></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>User Email Alert Subject.&nbsp; This 
      is the&nbsp;subject of the email alert to your users.&nbsp;</P></td>
		    <td>&nbsp;<input name="a_subject1" size="40" value="Response to Support Request"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Administrator Email Address (for admin 
      alerts).&nbsp; This is the email address of the main system 
      administrator.&nbsp; This is the address where notifications of new support requests will be sent.&nbsp;</P></td>
		    <td>&nbsp;<input name="admin_email_1" size="35" value=admin@domain.com 
     ></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Administrator Alert Email Subject&nbsp;</P></td>
		    <td>&nbsp;<input name="ae_subj1" size="40" value="New Support Request" 
     ></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Administrator Alert Email 
      Message.&nbsp; This is the text of the email alerts to the administrator.</P></td>
		    <td>&nbsp;<TEXTAREA class=prefinput name=ae_mssg1 rows=5 cols=40>This email is to inform you that a new support request has been added to the queue.  Please visit the SSM Administration Area to view the request.</TEXTAREA></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Registration System (if you have 
      OpenBB installed, we encourage you to use OpenBB).&nbsp; This is the 
      Registration System that you would like to&nbsp;use.&nbsp; If you 
      presently are using OpenBB on your&nbsp;system and are installing SSM to 
      interface with it, we recommend choosing OpenBB System as your 
      choice.&nbsp; If this is not the case, you must choose SSM System for the registration system to function.&nbsp;</P></td>
		    <td>&nbsp;<select name="reg_system1" size="1" class="prefinput">
						<option value="1" SELECTED>OpenBB System</option>
						<option value="2">SSM System</option>
				</select>
			</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Client Side Body Background Color&nbsp;</P></td>
		    <td>&nbsp;<input name="background1" size="15" value="#324C69"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Client Side Main Table Background Color&nbsp;</P></td>
		    <td>&nbsp;<input name="maintabc1" size="15" value="white"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr><td colspan="2" bgcolor="black"></td></tr>
		<tr>
		    <td colspan="2" bgcolor="#b7b7b7">
		      <P align=center><input class="but" type="submit" value="Continue" ></P></td>
		</tr>
		</table>
<?php
}//end function
//--------------------------------------------------------------------
?>

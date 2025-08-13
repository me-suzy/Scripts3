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
require("global.php");
//---------------------------------------------------------------------
//change the user's password
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Change Your Password</title>
<style type="text/css">
<!--
.prefinput{
	color: #333333;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
	border-color: #333333;
	text-indent: 2px; 
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px; 
	background: #f8f8f8;
}
.button {
	background-color: #F8F8F8;
	color: #333333;
	border-color: black;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight : bold;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px; 
}
-->
</style>
<script language="JavaScript" type="text/javascript">
//Verify password entry
function validForm(passForm){
	if(passForm.new_pass_one.value==""){
		alert("You must enter a password");
		passForm.new_pass_one.focus();
		return false
	}
	if(passForm.new_pass_one.value!=passForm.new_pass_two.value){
		alert("Entered passwords do NOT match");
		passForm.new_pass_one.focus();
		passForm.new_pass_one.select();
		return false
	}
	return true
}//end validForm()
</script>
</head>
<?php
if(!isset($stage)){
	//first step:
		//show password change fields
?>
<body>
<form action="<?php print $PHP_SELF; ?>" method="post" onSubmit="return validForm(this)">
<P><FONT face=Verdana size=2>Enter your existing password: </FONT><INPUT class=prefinput size=15 name=ex_pass type=Password></P>
<P><FONT face=Verdana size=2>Enter your new password: <INPUT class=prefinput size=15 name=new_pass_one type=Password></FONT></P>
<P><FONT face=Verdana size=2>Confirm your new password:</FONT> <INPUT class=prefinput size=15 name=new_pass_two type=Password></P>
<input type="hidden" name="stage" value="2">
<input type="submit" name="Submit" value="Proceed" class="button">
</form>
<?php
}
elseif($stage=="2"){
	//second step:
	//check existing password against DB's
	$result=mysql_query("SELECT password FROM $user_table WHERE username='$username';");
	$value=mysql_fetch_array($result);
	if($value['password']!=md5($ex_pass)){
		die("The existing password that you entered was not correct. <a href=passchg.php>Please try again</a>.");
	}
	//if correct, check the two passwords for equality
	if($new_pass_one!=$new_pass_two){
			die("The new passwords that you entered did not match. <a href=passchg.php>Please try again</a>.");
	}
	//ask for confirmation
?>
<body>
<form action="<?php print $PHP_SELF; ?>" method="post">
Do you want to proceed?&nbsp;<input type="checkbox" name="continue" value="yes" class=prefinput>
<input type="hidden" name="stage" value="3">
<input type="hidden" name="pass567890" value="<?php print $new_pass_two; ?>">
<input type="submit" name="Submit" value="Change" class="button">
</form>
<?php
}
elseif($stage=="3"){
	//third step:
		//make change, close button, run main window refresh
		if($continue=="yes"){
			//do db change
			//$password1 = md5($password);
			$sql="UPDATE $user_table SET password='".md5($pass567890)."' WHERE username='$username';";
			if(!$result=mysql_query($sql)){
				print "<p>Error in updating data!<br>";
				print mysql_error();
				print '<br><a href="';
				print $PHP_SELF;
				print '">Click Here to try again</a><br><br>';
				print "$sql</p>";
			}//end error
		}
	//print HTML
	?>
<body onUnload="opener.location.href = '<?php print $web_path; ?>index.php?system=logout'">
<b>The password modification has been completed.</b>  You will now need to log into the system again.<br>
<form method="post">
<input type="button" value="Close" class="button" onclick="window.close()">
</form>
	<?php
}
?>
</body>
</html>	
